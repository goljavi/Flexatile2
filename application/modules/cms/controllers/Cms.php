<?php
//Flexatile 2
class Cms extends MX_Controller 
{

function __construct() {
parent::__construct();
}

function view($url)
{
    $data = $this->fetch_data_from_db_url($url);
    if($data==''){redirect('404');}
    $data['view_module'] = 'cms';
    $data['view_file'] = "view";
    $this->load->module('templates');
    $this->templates->public_bootstrap($data);
}

function get_content($id)
{
    $data = $this->fetch_data_from_db($id);
    return $data['content'];
}

//creates a cms page
function create()
{
    $this->load->module('site_security');
    $this->site_security->_make_sure_is_admin();

    $update_id = $this->uri->segment(3);
    $submit = $this->input->post('submit', TRUE);

    if($submit=='Guardar')
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('title', 'Título', 'required|max_length[250]');
        $this->form_validation->set_rules('url', 'URL', 'alpha_dash');
        $this->form_validation->set_rules('description', 'Descripción', 'required|max_length[250]');
        $this->form_validation->set_rules('content', 'Contenido', 'required');

        if ($this->form_validation->run() == TRUE)
        {
            $data = $this->fetch_data_from_post();

            $data['title'] = $this->title_check($data['title']);
            
            if($data['url']=='')
            {
                $data['url'] = $this->url_check(url_title($data['title']));
            }
            else
            {
                $data['url'] = $this->url_check($data['url']);
            }
            
            if(is_numeric($update_id))
            {
                $this->_update($update_id, $data);
                $flash_msg = 'El contenido fue actualizado correctamente';
                $value = '<div class="alert alert-success">'.$flash_msg.'</div>';
            }
            else
            {
                $this->_insert($data);
                $update_id = $this->get_max(); //Get the id of the new item
                $flash_msg = 'El contenido fue creado correctamente';
                $value = '<div class="alert alert-success">'.$flash_msg.'</div>';
            }
            $this->session->set_flashdata('cms', $value);
            redirect('cms/create/'.$update_id);
        }

    }

    if(is_numeric($update_id) && ($submit!='Guardar'))
    {
        $data = $this->fetch_data_from_db($update_id);
        $data['headline'] = 'Editando Contenido';
    }
    else
    {
        $data = $this->fetch_data_from_post();
        $data['headline'] = 'Agregar Contenido';
    }

    $data['update_id'] = $update_id;
    $data['flash'] = $this->session->flashdata('cms');
    $data['form_location'] = 'cms/create/'.$update_id;
    $data['view_module'] = 'cms';
    $data['view_file'] = 'create';
    $this->load->module('templates');
    $this->templates->admin($data);
}

//deletes a store item
function del($id)
{
    $this->load->module('site_security');
    $this->site_security->_make_sure_is_admin();
    if(!is_numeric($id)){redirect('index');}

    $data = $this->fetch_data_from_db($id);
    if($data['important']==1){redirect('index');}

    $submit = $this->input->post('submit', TRUE);
    if ($submit=='No, cancelar.'){
    redirect('cms/index');
    }

    if ($submit=='Si, borrar.'){

    $this->_delete($id);
    
    $value = "<div class='alert alert-danger'>
            <strong>Hecho!</strong> El contenido fue <strong>eliminado</strong> satisfactoriamente.
            </div>";
        
    $this->session->set_flashdata('delete', $value);
    redirect('cms/index');

    }   

    $data['form_location'] = current_url();
    $data['view_module'] = 'cms';
    $data['view_file'] = "delete";
    $this->load->module('templates');
    $this->templates->admin($data);

}

//fetches all records from cms and displays them in a table 
function index()
{
    $this->load->module('site_security');
    $this->site_security->_make_sure_is_admin();

    $data['query'] = $this->get('date');
    $data['flash'] = $this->session->flashdata('delete');
    $data['headline'] = 'Contenidos';
    $data['view_module'] = 'cms';
    $data['view_file'] = 'manage';
    $this->load->module('templates');
    $this->templates->admin($data);
}

function title_check($str)
{
    //Se quita cualquier simbolo que no se admita en la url o el título (son una bocha lpm)
    $symbols = array('º' => '', 'ª' => '', '!' => '', '|' => '', '@' => '', '·' => '', '¡' => '', 
                    '/' => '',  '?' => '',  '¿' => '', '[' => '',  ']' => '',  '{' => '',  '}' => '',  
                    '^' => '',  '¨' => '',  'ç' => '', 'Ç' => '',  '*' => '', 'ñ' => 'n', '"' => '', 
                    '$' => '', '%' => '', '#' => '', '&' => '', '=' => '',  '(' => '', ')' => '', 
                    "'" => '', '¬' => '', '+' => '', '\\' => '');
    $str = strtr($str, $symbols);

    //Si el titulo solo tenía alguno de esos simbolos, va a quedar completamente vacío. Para que esto no ocurra se agrega una 'a'
    if($str == ''){$str = 'a';}

    return $str;
}

//function called by "create()" checks if url is already in use, if it is, word "copy" will be added at the end
function url_check($str)
{
    //En caso de ser una edición, la URI contiene el id
    $update_id = $this->uri->segment(3);

    //variable que se va a usar en un while
    $isnotok = true;

    //Convertir los caracteres acentuados a texto pleno
    $this->load->helper('text');
    $str = convert_accented_characters($str);
    
    //Se pasa todo a minuscula por las dudas
    $str = strtolower($str);
    
    //Se quita cualquier simbolo que no se admita en la url (son una bocha lpm)
    $symbols = array('º' => '', 'ª' => '', '!' => '', '|' => '', '@' => '', '·' => '', '¡' => '', 
                    '/' => '',  '?' => '',  '¿' => '', '[' => '',  ']' => '',  '{' => '',  '}' => '',  
                    '^' => '',  '¨' => '',  'ç' => '', 'Ç' => '',  '*' => '', 'ñ' => 'n', '"' => '', 
                    '$' => '', '%' => '', '#' => '', '&' => '', '=' => '',  '(' => '', ')' => '', 
                    "'" => '', '¬' => '', '+' => '', '\\' => '');
    $str = strtr($str, $symbols);
    
    //Si la url solo tenía alguno de esos simbolos, va a quedar completamente vacía. Para que esto no ocurra se agrega una 'a'
    if($str == ''){$str = 'a';}

    //Mientras se siga repitiendo la url, no se va a cambiar el while
    while($isnotok){
        if(is_numeric($update_id))
        {
            //Si update_id pasa el if, significa que es una edición del item. La query que se utiliza entonces es mas larga
            //Porque si se deja la url tal cual está en un item que está siendo modificado el filtro va a interpretar que
            //Se está queriendo ingresar una nueva url cuando en realidad es la misma en el mismo item
            $query = $this->_custom_query('select * from cms where url="'.$str.'" and id!='.$update_id);
        }
        else
        {
            //Si no hay update_id singfica que se está creando un nuevo producto por lo que se debe buscar en todos los demás la coincidencia de url
            $query = $this->get_where_custom('url', $str);
        }
    
        //Solo nos interesa saber si la query devuelve rows, si es mayor a 0, se encontró coincidencia, no hace falta nada mas
        $num_rows = $query->num_rows();
    
        //De haber coincidencia, a la url se le agrega un "copy"
        if($num_rows>0)
        {
            $str .= 'copy';
        }
        else
        {
            $isnotok = false;
        }
    }
    
    return $str;
}

/* FETCHING FUNCTIONS */
//fetches data from "create()" form
function fetch_data_from_post()
{
    $data['title'] = $this->input->post('title', TRUE);
    $data['url'] = $this->input->post('url', TRUE);
    $data['description'] = $this->input->post('description', TRUE);
    $data['content'] = $this->input->post('content', TRUE);
    return $data;
}

//fetches data from db to fill "create()" form
function fetch_data_from_db($update_id)
{
    $query = $this->get_where($update_id);
    foreach($query->result() as $row)
    {
        $data['id'] = $row->id;
        $data['title'] = $row->title;
        $data['url'] = $row->url;
        $data['description'] = $row->description;
        $data['content'] = $row->content;
        $data['date'] = $row->date;
        $data['important'] = $row->important;
    }
    
    if(!isset($data))
    {
        $data = '';
    }

    return $data;
}
//fetches data from db to fill content
function fetch_data_from_db_url($url)
{
    $query = $this->get_where_custom('url', $url);
    foreach($query->result() as $row)
    {
        $data['id'] = $row->id;
        $data['title'] = $row->title;
        $data['url'] = $row->url;
        $data['description'] = $row->description;
        $data['content'] = $row->content;
        $data['date'] = $row->date;
        $data['important'] = $row->important;
    }
    
    if(!isset($data))
    {
        $data = '';
    }

    return $data;
}


/* MODEL FUNCTIONS */
function get($order_by)
{
    $this->load->model('mdl_cms');
    $query = $this->mdl_cms->get($order_by);
    return $query;
}

function get_with_limit($limit, $offset, $order_by) 
{
    if ((!is_numeric($limit)) || (!is_numeric($offset))) {
        die('Non-numeric variable!');
    }

    $this->load->model('mdl_cms');
    $query = $this->mdl_cms->get_with_limit($limit, $offset, $order_by);
    return $query;
}

function get_where($id)
{
    if (!is_numeric($id)) {
        die('Non-numeric variable!');
    }

    $this->load->model('mdl_cms');
    $query = $this->mdl_cms->get_where($id);
    return $query;
}

function get_where_custom($col, $value) 
{
    $this->load->model('mdl_cms');
    $query = $this->mdl_cms->get_where_custom($col, $value);
    return $query;
}

function _insert($data)
{
    $this->load->model('mdl_cms');
    $this->mdl_cms->_insert($data);
}

function _update($id, $data)
{
    if (!is_numeric($id)) {
        die('Non-numeric variable!');
    }

    $this->load->model('mdl_cms');
    $this->mdl_cms->_update($id, $data);
}

function _delete($id)
{
    if (!is_numeric($id)) {
        die('Non-numeric variable!');
    }

    $this->load->model('mdl_cms');
    $this->mdl_cms->_delete($id);
}

function count_where($column, $value) 
{
    $this->load->model('mdl_cms');
    $count = $this->mdl_cms->count_where($column, $value);
    return $count;
}

function get_max() 
{
    $this->load->model('mdl_cms');
    $max_id = $this->mdl_cms->get_max();
    return $max_id;
}

function _custom_query($mysql_query) 
{
    $this->load->model('mdl_cms');
    $query = $this->mdl_cms->_custom_query($mysql_query);
    return $query;
}

}