<?php
//Flexatile 2
class Blog extends MX_Controller 
{

function __construct() {
parent::__construct();
}

function del_image($id, $direct_delete=FALSE){
	$this->load->module('site_security');
    $this->site_security->_make_sure_is_admin();
	if(!is_numeric($id)){redirect('index');}
	
	$data = $this->fetch_data_from_db($id);

	if($direct_delete)
	{
		if(file_exists('./blog_images/'.$data['image_file']))
    	{	
			unlink('./blog_images/'.$data['image_file']);
		}
		if(file_exists('./blog_images/'.$data['thumbnail']))
    	{
			unlink('./blog_images/'.$data['thumbnail']);
		}
	}else
	{
		$submit = $this->input->post('submit', TRUE);
		if ($submit=='No, cancelar.'){
    	redirect('blog/create/'.$id);
		}
	
		if ($submit=='Si, borrar.'){
		
		if(file_exists('./blog_images/'.$data['image_file']))
    	{	
			unlink('./blog_images/'.$data['image_file']);
		}
		if(file_exists('./blog_images/'.$data['thumbnail']))
    	{
			unlink('./blog_images/'.$data['thumbnail']);
		}
		unset($data);
		$data['image_file'] = '';
		$data['thumbnail'] = '';
		$this->_update($id, $data);
		$value = "<div class='alert alert-danger'>
		<strong>Hecho!</strong> La imagen fue <strong>eliminada</strong> satisfactoriamente.
		</div>";
			
		$this->session->set_flashdata('flash_image_error;', $value);
		
    	redirect('blog/create/'.$id);
		}	
    	$data['form_location'] = current_url();
		$data['view_module'] = 'blog';
    	$data['view_file'] = 'delete_image';
    	$this->load->module('templates');
    	$this->templates->admin($data);
	}
}


function submit_image($id){
	$this->load->module('site_security');
    $this->site_security->_make_sure_is_admin();
	if(!is_numeric($id)){redirect('index');}

	$config['upload_path'] = './blog_images';
	$config['allowed_types'] = 'gif|jpg|png';
	$config['max_size']	= '3000';
	$config['max_width']  = '2000';
	$config['max_height']  = '2000';

	$this->load->library('upload', $config);
	
	if ( ! $this->upload->do_upload())
		{
			$value = '<div class="alert alert-danger">'.$this->upload->display_errors().'</div>';
			$this->session->set_flashdata('flash_image_error', $value);
			redirect('blog/create/'.$id);
		}
		else
		{
			$data = $this->upload->data();
			$file_name = $data['file_name'];

			//crea el thumbnail
			$config['image_library'] = 'gd2';
			$config['source_image']	= './blog_images/'.$file_name;
			$config['create_thumb'] = TRUE;
			$config['maintain_ratio'] = TRUE;
			$config['width']	= 500;
			$config['height']	= 500;
			$config['file_name'] = rand();

			$this->load->library('image_lib', $config); 
			$this->image_lib->resize();
			
			//Actualizar la base de datos
			$raw_file_name = $data['raw_name'];
			$file_ext = $data['file_ext'];

			unset($data);
			$data['image_file'] = $file_name;
			$data['thumbnail'] = $raw_file_name.'_thumb'.$file_ext;
			$this->_update($id, $data);

			redirect('blog/create/'.$id);
		}
}

function _upload_image($id, $image, $thumb_image){
	$data['id'] = $id;
	$data['image'] = $image;
	$data['thumb_image'] = $thumb_image;
	$data['flash_image_error'] = $this->session->flashdata('flash_image_error');
	$this->load->view('upload_image', $data);
}

//creates a blog page
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

            $data['date'] = DateTime::createFromFormat('d/m/Y', $data['date']);
            $data['date'] = $data['date']->format('Y-m-d');
            
            if(is_numeric($update_id))
            {
                $this->_update($update_id, $data);
                $flash_msg = 'El articulo fue actualizado correctamente';
                $value = '<div class="alert alert-success">'.$flash_msg.'</div>';
            }
            else
            {
                $this->_insert($data);
                $update_id = $this->get_max(); //Get the id of the new item
                $flash_msg = 'El articulo fue creado correctamente';
                $value = '<div class="alert alert-success">'.$flash_msg.'</div>';
            }
            $this->session->set_flashdata('blog', $value);
            redirect('blog/create/'.$update_id);
        }

    }

    if(is_numeric($update_id) && ($submit!='Guardar'))
    {
        $data = $this->fetch_data_from_db($update_id);
        $data['headline'] = 'Editando Articulo';
    }
    else
    {
        $data = $this->fetch_data_from_post();
        $data['headline'] = 'Agregar Articulo';
    }

    $data['update_id'] = $update_id;
    $data['flash'] = $this->session->flashdata('blog');
    $data['form_location'] = 'blog/create/'.$update_id;
    $data['view_module'] = 'blog';
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

    $submit = $this->input->post('submit', TRUE);
    if ($submit=='No, cancelar.'){
    redirect('blog/index');
    }

    if ($submit=='Si, borrar.'){
    $this->del_image($id, TRUE);
    $this->_delete($id);
    
    $value = "<div class='alert alert-danger'>
            <strong>Hecho!</strong> El articulo fue <strong>eliminado</strong> satisfactoriamente.
            </div>";
        
    $this->session->set_flashdata('delete', $value);
    	redirect('blog/index');
    }   

    $data['form_location'] = current_url();
    $data['view_module'] = 'blog';
    $data['view_file'] = "delete";
    $this->load->module('templates');
    $this->templates->admin($data);

}

//fetches all records from blog and displays them in a table 
function index()
{
    $this->load->module('site_security');
    $this->site_security->_make_sure_is_admin();

    $data['query'] = $this->get('date');
    
    $data['flash'] = $this->session->flashdata('delete');
    $data['headline'] = 'Blog';
    $data['view_module'] = 'blog';
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
            $query = $this->_custom_query('select * from blog where url="'.$str.'" and id!='.$update_id);
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
    $data['date'] = $this->input->post('date', TRUE);
    $data['keywords'] = $this->input->post('keywords', TRUE);
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
        $data['image_file'] = $row->image_file;
        $data['thumbnail'] = $row->thumbnail;
        $data['createdby'] = $row->createdby;
        $data['keywords'] = $row->keywords;
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
    $this->load->model('mdl_blog');
    $query = $this->mdl_blog->get($order_by);
    return $query;
}

function get_with_limit($limit, $offset, $order_by) 
{
    if ((!is_numeric($limit)) || (!is_numeric($offset))) {
        die('Non-numeric variable!');
    }

    $this->load->model('mdl_blog');
    $query = $this->mdl_blog->get_with_limit($limit, $offset, $order_by);
    return $query;
}

function get_where($id)
{
    if (!is_numeric($id)) {
        die('Non-numeric variable!');
    }

    $this->load->model('mdl_blog');
    $query = $this->mdl_blog->get_where($id);
    return $query;
}

function get_where_custom($col, $value) 
{
    $this->load->model('mdl_blog');
    $query = $this->mdl_blog->get_where_custom($col, $value);
    return $query;
}

function _insert($data)
{
    $this->load->model('mdl_blog');
    $this->mdl_blog->_insert($data);
}

function _update($id, $data)
{
    if (!is_numeric($id)) {
        die('Non-numeric variable!');
    }

    $this->load->model('mdl_blog');
    $this->mdl_blog->_update($id, $data);
}

function _delete($id)
{
    if (!is_numeric($id)) {
        die('Non-numeric variable!');
    }

    $this->load->model('mdl_blog');
    $this->mdl_blog->_delete($id);
}

function count_where($column, $value) 
{
    $this->load->model('mdl_blog');
    $count = $this->mdl_blog->count_where($column, $value);
    return $count;
}

function get_max() 
{
    $this->load->model('mdl_blog');
    $max_id = $this->mdl_blog->get_max();
    return $max_id;
}

function _custom_query($mysql_query) 
{
    $this->load->model('mdl_blog');
    $query = $this->mdl_blog->_custom_query($mysql_query);
    return $query;
}

}