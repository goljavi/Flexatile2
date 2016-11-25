<?php
//Flexatile 2
class Store_categories extends MX_Controller 
{

function __construct() {
parent::__construct();
}

function load_pagination_config()
{
        $config['full_tag_open'] = "<ul class='pagination'>";
        $config['full_tag_close'] ="</ul>";
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = "<li class='disabled'><li class='active'><a href='#'>";
        $config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
        $config['next_tag_open'] = "<li>";
        $config['next_tagl_close'] = "</li>";
        $config['prev_tag_open'] = "<li>";
        $config['prev_tagl_close'] = "</li>";
        $config['first_tag_open'] = "<li>";
        $config['first_tagl_close'] = "</li>";
        $config['last_tag_open'] = "<li>";
        $config['last_tagl_close'] = "</li>";
        return $config;
}

function view($url, $page = 0)
{
    $data = $this->fetch_data_from_db_url($url);
    if($data==''){redirect('404');}
    if($data['parent_id'] == 0)
    {
        $data['select'] = $data['id'];
    }else
    {
        $data['select'] = $data['parent_id'];
    }

     $this->load->library('pagination');

    $query = $this->_custom_query('SELECT COUNT(*) FROM store_cat_assign WHERE category_id='.$data['id']);
        
    $config = $this->load_pagination_config();
    $config['base_url'] = base_url().'tips';
    $config['total_rows'] = $query->num_rows();
    $config['per_page'] = 9;
    $this->pagination->initialize($config); 

    $data['page'] = $page;
    $data['view_module'] = 'store_categories';
    $data['view_file'] = "view";
    $this->load->module('templates');
    $this->templates->public_bootstrap($data);
}

function _draw_top_navbar(){
    $data['query'] = $this->_custom_query('SELECT * FROM store_categories WHERE parent_id=0 AND important!=1 ORDER BY priority ASC');
    $this->load->view('top_navbar', $data);
}

function _is_new_category_allowed($id){
    if(count($this->_get_category_hierarchy($id)) >= Modules::run('config/subcategory_depth_allowed'))
    {
        return false;
    }else
    {
        return true;
    }
}

function get_subcategory_array($id)
{
    $subcat_array = array();
    $check_subcats_array = array();
    array_push($check_subcats_array, $id);
    $more_subcats = true;
    while($more_subcats)
    {
        foreach($check_subcats_array as $check)
        {
            $query = $this->get_where_custom('parent_id', $check);
            foreach($query->result() as $row)
            {
                array_push($subcat_array, $row->id);
                print_r($subcat_array);
                echo ' fue sumado al subcat_array. <br>';

                $query = $this->get_where_custom('parent_id', $row->id);

                echo 'la subcategoría con id '.$row->id.' tiene '.$query->num_rows().' subcategorias <br>';

                if($query->num_rows()>0)
                {
                   array_push($check_subcats_array, $row->id); 
                }

            } 
            print_r($check_subcats_array);
                echo 'este es el estado actual del array de chequeo <br>';
                array_shift($check_subcats_array);
                print_r($check_subcats_array);
                echo 'este es el estado del array de chequeo despues del array_shift <br>';
                echo 'fin del bucle<br><br>';


                if(empty($check_subcats_array))
                {
                    $more_subcats = false;
                    print_r($check_subcats_array);
                    echo '<br> el array está vacío <br>';
                }   
        } 
    }
    return $subcat_array;
}


function _check_id($priority, $parent_id){
    $query = $this->_custom_query("SELECT id FROM store_categories WHERE parent_id='".$parent_id."' AND priority='".$priority);
    if($query->num_rows()<1){redirect('index');}
    return $id;
}

function del($id){
    if(!is_numeric($id)){redirect('index');}

    $this->load->module('site_security');
    $this->site_security->_make_sure_is_admin(); 

    $category = $this->fetch_data_from_db($id);
    if($category['important']==1){redirect('index');}

    $submit = $this->input->post('submit', TRUE);

    //If there are subcategories, they have to be notified to the admin
    $subcategories = $this->_count_subcategories($id);

    if ($submit=='No, cancelar.')
    {
        redirect('store_categories/index/'.$id);
    }
    if ($submit=='Si, borrar.')
    {

    if($subcategories<1)
    {
        //No subcats? Easy piece lemon squeezy
        //attempt to remove all products that might be assigned to this category
        Modules::Run('store_cat_assign/_delete_all_products_assigned', $id);
        //attempt to remove store_category record
        $this->_delete($id);
    }else
    {
        //Wha? ... What? Subcategories? That sucks man
        $subcategory_array = $this->get_subcategory_array($id);
        foreach($subcategory_array as $sub)
        {
            //attempt to remove all products that might be assigned to all subcategories from all levels
            Modules::Run('store_cat_assign/_delete_all_products_assigned', $sub);
            //attempt to remove all subcategories from all levels
            $this->_delete($sub);
        }
        //attempt to remove all products that might be assigned to this category
        Modules::Run('store_cat_assign/_delete_all_products_assigned', $id);
        //attempt to remove store_category record
        $this->_delete($id);
    }

     $value = "<div class='alert alert-danger'>
            <strong>Hecho!</strong> La categoría fue <strong>eliminada</strong> satisfactoriamente.
            </div>";  
    $this->session->set_flashdata('delete_category', $value);
        redirect('store_categories/index');
    }

    $data = $this->fetch_data_from_db($id);
    $data['subcategories'] = $subcategories;
    $data['form_location'] = current_url();
    $data['view_module'] = 'store_categories';
    $data['view_file'] = "delete_category";
    $this->load->module('templates');
    $this->templates->admin($data);
}

function sort()
{
   $this->load->module('site_security');
   $this->site_security->_make_sure_is_admin(); 

   $number = $this->input->post('number', TRUE);
   for($i=1; $i <= $number; $i++){
        $update_id = $_POST['order'.$i];
        $data['priority'] = $i;
        $this->_update($update_id, $data);
   }
}

function _category_list($id){
    $data['list'] = $this->_get_category_list($id);
    $this->load->view('category_list', $data);
}

function _get_category_list($id){
    $list = $this->_get_category_hierarchy($id);
    return $list;
}

function _get_category_hierarchy($id){
    if($id==0){return 0;}
    $data = $this->fetch_data_from_db($id);
    $parent_id = $data['parent_id'];
    $categorias = array($data['id']);
    if($parent_id>=1){
        while($parent_id!= 0){
            $data = $this->fetch_data_from_db($parent_id);
            $parent_id = $data['parent_id'];
            array_push($categorias, $data['id']);
        }
    }
    return $categorias;
}

function _get_category_name($id){
    $query = $this->get_where($id);
    foreach($query->result() as $row){
        $title = $row->title;
    }
    return $title;
}

function _dropdown($item_id){
    $data['query'] = $this->_custom_query('SELECT * FROM store_categories WHERE parent_id=0 ORDER BY priority ASC');
    $data['item_id'] = $item_id;
    $this->load->view('dropdown', $data);
}

function _dropdown_subcategory($id){
    $data['query'] = $this->_custom_query('SELECT * FROM store_categories WHERE parent_id="'.$id.'" ORDER BY priority ASC');
    $this->load->view('dropdown_subcategory', $data);
}


//creates a store category
function create($update_id = 0, $subcategory = 0)
{
    $this->load->module('site_security');
    $this->site_security->_make_sure_is_admin();

    $check_category = $this->fetch_data_from_db($update_id);

    if(isset($check_category['parent_id'])){
        if($check_category['parent_id']>0){
            if(!$this->_is_new_category_allowed($check_category['parent_id'])){redirect('index');}
        }
    }

    $submit = $this->input->post('submit', TRUE);

    if($submit=='Guardar')
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('title', 'Título', 'required|max_length[250]');
        $this->form_validation->set_rules('url', 'URL', 'alpha_dash|max_length[250]');

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
            
            if($update_id > 0 && $subcategory != 1)
            {
                $this->_update($update_id, $data);
                $flash_msg = 'La <strong>categoría</strong> fue actualizada correctamente';
                $value = '<div class="alert alert-success">'.$flash_msg.'</div>';
            }
            else
            {
                if($subcategory == 1)
                {
                    $data['parent_id'] = $update_id;
                    $flash_msg = 'La <strong>Subcategoría</strong> fue creada correctamente';

                }else
                {
                    $flash_msg = 'La <strong>categoría</strong> fue creada correctamente';
                }
                $this->_insert($data);
                $value = '<div class="alert alert-success">'.$flash_msg.'</div>';
            }    
        }else
        {
            $value = validation_errors("<div class='alert alert-danger'  <strong>Alerta!</strong> ", "</div>");
        }
        $this->session->set_flashdata('category', $value);
        redirect('store_categories/index/'.$update_id.'#categories');
    }

    if($update_id > 0 && $submit!='Guardar' && $subcategory != 1)
    {
        $data = $this->fetch_data_from_db($update_id);
        $data['headline'] = 'Editar Categoría';
    }
    else
    {
        $data = $this->fetch_data_from_post();
        if($subcategory==1)
        {
            $data['headline'] = 'Crear Nueva Subcategoría';
        }
        else
        {
            $data['headline'] = 'Crear Nueva Categoría';
        }
        
    }

    $data['update_id'] = $update_id;
    $data['flash'] = $this->session->flashdata('category');
    $data['form_location'] = 'store_categories/create/'.$update_id.'/'.$subcategory;
    $data['subcategory'] = $subcategory;
    $this->load->view('create', $data);
}

function index()
{
    $this->load->module('site_security');
    $this->site_security->_make_sure_is_admin();

    $data['update_id'] = $this->uri->segment(3);
    if($data['update_id']==''){$data['update_id']=0;}

    $data['query'] = $this->_custom_query('select * from store_categories where parent_id='.$data['update_id'].' and important=0 order by priority');
    
    $data['sort_this'] = TRUE;
    $data['flash'] = $this->session->flashdata('delete_category');
    $data['headline'] = 'Categorías';
    $data['view_module'] = 'store_categories';
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
            $query = $this->_custom_query('select * from store_categories where url="'.$str.'" and id!='.$update_id);
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

function _count_subcategories($id){
    $query = $this->get_where_custom('parent_id', $id);
    return $query->num_rows();
}

//fetches data from "create()" form
function fetch_data_from_post()
{
    $data['title'] = $this->input->post('title', TRUE);
    $data['url'] = $this->input->post('url', TRUE);
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
        $data['parent_id'] = $row->parent_id;
        $data['url'] = $row->url;
        $data['priority'] = $row->priority;
        $data['important'] = $row->important;
    }
    
    if(!isset($data))
    {
        $data = '';
    }

    return $data;
}

//fetches data from db to draw a category with products
function fetch_data_from_db_url($url)
{
    $query = $this->get_where_custom('url', $url);
    foreach($query->result() as $row)
    {
        $data['id'] = $row->id;
        $data['title'] = $row->title;
        $data['parent_id'] = $row->parent_id;
        $data['url'] = $row->url;
        $data['priority'] = $row->priority;
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
    $this->load->model('mdl_store_categories');
    $query = $this->mdl_store_categories->get($order_by);
    return $query;
}

function get_with_limit($limit, $offset, $order_by) 
{
    if ((!is_numeric($limit)) || (!is_numeric($offset))) {
        die('Non-numeric variable!');
    }

    $this->load->model('mdl_store_categories');
    $query = $this->mdl_store_categories->get_with_limit($limit, $offset, $order_by);
    return $query;
}

function get_where($id)
{
    if (!is_numeric($id)) {
        die('Non-numeric variable!');
    }

    $this->load->model('mdl_store_categories');
    $query = $this->mdl_store_categories->get_where($id);
    return $query;
}

function get_where_custom($col, $value) 
{
    $this->load->model('mdl_store_categories');
    $query = $this->mdl_store_categories->get_where_custom($col, $value);
    return $query;
}

function _insert($data)
{
    $this->load->model('mdl_store_categories');
    $this->mdl_store_categories->_insert($data);
}

function _update($id, $data)
{
    if (!is_numeric($id)) {
        die('Non-numeric variable!');
    }

    $this->load->model('mdl_store_categories');
    $this->mdl_store_categories->_update($id, $data);
}

function _delete($id)
{
    if (!is_numeric($id)) {
        die('Non-numeric variable!');
    }

    $this->load->model('mdl_store_categories');
    $this->mdl_store_categories->_delete($id);
}

function count_where($column, $value) 
{
    $this->load->model('mdl_store_categories');
    $count = $this->mdl_store_categories->count_where($column, $value);
    return $count;
}

function get_max() 
{
    $this->load->model('mdl_store_categories');
    $max_id = $this->mdl_store_categories->get_max();
    return $max_id;
}

function _custom_query($mysql_query) 
{
    $this->load->model('mdl_store_categories');
    $query = $this->mdl_store_categories->_custom_query($mysql_query);
    return $query;
}

}