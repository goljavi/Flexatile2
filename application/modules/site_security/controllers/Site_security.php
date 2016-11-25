<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Site_security extends MX_Controller
{

function __construct() {
parent::__construct();
}

function del($id)
{
    $this->load->module('site_security');
    $this->site_security->_make_sure_is_admin();
    if(!is_numeric($id)){redirect('index');}

    $submit = $this->input->post('submit', TRUE);
    $data = $this->fetch_data_from_db($id);
    if($data['permissions'] == 1){redirect('index');}
    
    if ($submit=='No, cancelar.')
    {
    	redirect('site_security/manage');
    }
    
    if ($submit=='Si, borrar.')
    {
    	$this->_delete($id);
    	redirect('site_security/manage');
    }   

    $data['form_location'] = current_url();
    $data['view_module'] = 'site_security';
    $data['view_file'] = "delete";
    $this->load->module('templates');
    $this->templates->admin($data);

}


function manage()
{
    $this->load->module('site_security');
    $this->site_security->_make_sure_is_admin();
    if(!$this->session->userdata('permissions')){redirect('index');}

    $data['query'] = $this->get('username');
    $data['headline'] = 'Usuarios';
    $data['view_module'] = 'site_security';
    $data['view_file'] = 'manage';
    $this->load->module('templates');
    $this->templates->admin($data);
}

function _make_sure_is_admin(){
	if(!(bool)$this->session->userdata('is_logged_in'))
	{
		redirect('admin');
		die();
	}
}

function index()
{
	if((bool)$this->session->userdata('is_logged_in'))
	{
     redirect('store_items');
	}
	$data = "";
	$flash_error = $this->session->flashdata('error');
	if ($flash_error!=""){
		$data['flash_error'] = $flash_error;
	}
	$data['view_module'] = 'site_security';
    $data['view_file'] = "admin";
    $this->load->module('templates');
    $this->templates->admin($data);
}

function close(){
	if((bool)$this->session->userdata('is_logged_in'))
	{
		$this->session->sess_destroy();
	}
	redirect('admin');
	die();
}

function create(){
	$this->load->module('site_security');
    $this->site_security->_make_sure_is_admin();

	$id = $this->uri->segment(3);
    $submit = $this->input->post('submit', TRUE);

    if($submit=='Guardar'){

	$this->load->library('form_validation');
	$this->form_validation->set_rules('username', 'Usuario', 'trim|required|min_length[4]|max_length[15]');
	$this->form_validation->set_rules('password', 'Contraseña', 'trim|required|min_length[4]|max_length[32]');
	$this->form_validation->set_rules('password_confirm', 'Confirmar contraseña', 'trim|required|matches[password]');

	if ($this->form_validation->run() == TRUE)
		{
			$data = $this->get_data_from_register_post();
			if(is_numeric($id))
            {
            	if($this->check_if_username_not_exists($data['username'], $id))
            	{
            		$insert['username'] = $data['username'];
            		$insert['password'] = $this->md5($data['password']);
                	$this->_update($id, $insert);
                	$flash_msg = 'La cuenta fue actualizada correctamente';
                	$value = '<div class="alert alert-success">'.$flash_msg.'</div>';
                	$this->session->set_flashdata('error', $value);
            		redirect('site_security/create/'.$id);
            	}else{
            		$flash_msg = 'Ese nombre de usuario ya existe';
                	$value = '<div class="alert alert-success">'.$flash_msg.'</div>';
                	$this->session->set_flashdata('error', $value);
            		redirect('site_security/create/'.$id);
            	}
            }
            else
            {
            	if($this->check_if_username_not_exists($data['username']))
            	{
					$insert['username'] = $data['username'];
					$insert['password'] = $this->md5($data['password']);
					$this->_insert($insert);
			    	$value = "<div class='alert alert-success'>
		  			<strong>Hurra!</strong> La cuenta fue <strong>creada</strong> satisfactoriamente.
					</div>";
					$this->session->set_flashdata('error', $value);
					redirect('site_security/manage');
				}else
				{
				    $value = "<div class='alert alert-warning'>
		  			Ese nombre de usuario ya existe.
					</div>";
					$this->session->set_flashdata('error', $value);
					redirect('site_security/create');
				}
            }

		}
}
	
	if(is_numeric($id))
	{
		$data = $this->fetch_data_from_db($id);
		$data['headline'] = 'Editando Cuenta';
	}else
	{
		$data = $this->fetch_data_from_post();
		$data['headline'] = 'Creando Cuenta';
	}

	$data['update_id'] = $id;
    $data['flash'] = $this->session->flashdata('error');
    $data['form_location'] = 'site_security/create/'.$id;
    $data['view_module'] = 'site_security';
    $data['view_file'] = 'create';
    $this->load->module('templates');
    $this->templates->admin($data);

}

function get_data_from_register_post(){
	$data['username'] = $this->input->post('username', TRUE);
	$data['password'] = $this->input->post('password', TRUE);
	$data['password_confirm'] = $this->input->post('password_confirm', TRUE);
	$data['admpassword'] = $this->input->post('admpassword', TRUE);
	return $data;
}

function get_data_from_login_post(){
	$data['username'] = $this->input->post('username', TRUE);
	$data['password'] = $this->input->post('password', TRUE);
	return $data;
}

function check_if_username_not_exists($username, $id = 0){
	if(!isset($username)){redirect('index');}

	if($id==0)
	{
		$query = $this->get_where_custom('username', $username);
	}else
	{
		$query = $this->_custom_query('SELECT * FROM site_security WHERE username="'.$username.'" AND id!='.$id);
	}

	if ($query->num_rows() < 1)
	{
		return TRUE;
	}else
	{
		return FALSE;
	}
}

function check_admpassword($admpassword){
	if(!isset($admpassword)){redirect('index');}
	if($admpassword == Modules::Run('config/get_admpassword')){
		return TRUE;
	}else{
		return FALSE;
	}
}

function validate_credentials(){
	$this->load->helper(array('form', 'url'));

	$this->load->library('form_validation');
	$this->form_validation->set_rules('username', 'Usuario', 'trim|required');
	$this->form_validation->set_rules('password', 'Contraseña', 'trim|required');

		if ($this->form_validation->run() == FALSE)
		{
			$this->index();
		}
		else
		{

	$data = $this->get_data_from_login_post();
	$encpass = $this->md5($data['password']);
	$query_user = $this->get_where_custom('username', $data['username']);
	$query_pass = $this->get_where_custom('password', $encpass);
	foreach($query_user->result() as $row){
	$username = $row->username;
	}
	foreach($query_pass->result() as $row){
	$password = $row->password;
	$permissions = $row->permissions;
	}
	if (isset($username) && isset($password)){
	unset($data);
	$data['username'] = $username;
	$data['is_logged_in'] = TRUE;
	if($permissions == 1)
	{
		$data['permissions'] = TRUE;
	}
	$this->session->set_userdata($data);
	redirect('store_items');
	}else{
	 $value = "<div class='alert alert-warning'>
		  		El nombre de usuario o la contraseña son incorrectos
				</div>";
	$this->session->set_flashdata('error', $value);
	$this->index();
	}
}
}

function md5($password){
	if(!isset($password)){redirect('index');}
	$this->load->helper('security');
	$encrypt = do_hash($password, 'md5');
	return $encrypt;
}

/* FETCHING FUNCTIONS */
//fetches data from "create()" form
function fetch_data_from_post()
{
    $data['username'] = $this->input->post('username', TRUE);
    $data['password'] = $this->input->post('password', TRUE);
    $data['password_confirm'] = $this->input->post('password_confirm', TRUE);
    return $data;
}

//fetches data from db to fill "create()" form
function fetch_data_from_db($id)
{
    $query = $this->get_where($id);
    foreach($query->result() as $row)
    {
        $data['id'] = $row->id;
        $data['username'] = $row->username;
        $data['password'] = $row->password;
        $data['permissions'] = $row->permissions;
    }
    
    if(!isset($data))
    {
        $data = '';
    }

    return $data;
}

function get($order_by) {
$this->load->model('mdl_site_security');
$query = $this->mdl_site_security->get($order_by);
return $query;
}

function get_with_limit($limit, $offset, $order_by) {
$this->load->model('mdl_site_security');
$query = $this->mdl_site_security->get_with_limit($limit, $offset, $order_by);
return $query;
}

function get_where($id) {
$this->load->model('mdl_site_security');
$query = $this->mdl_site_security->get_where($id);
return $query;
}

function get_where_custom($col, $value) {
$this->load->model('mdl_site_security');
$query = $this->mdl_site_security->get_where_custom($col, $value);
return $query;
}

function _insert($data) {
$this->load->model('mdl_site_security');
$this->mdl_site_security->_insert($data);
}

function _update($id, $data) {
$this->load->model('mdl_site_security');
$this->mdl_site_security->_update($id, $data);
}

function _delete($id) {
$this->load->model('mdl_site_security');
$this->mdl_site_security->_delete($id);
}

function count_where($column, $value) {
$this->load->model('mdl_site_security');
$count = $this->mdl_site_security->count_where($column, $value);
return $count;
}

function get_max() {
$this->load->model('mdl_site_security');
$max_id = $this->mdl_site_security->get_max();
return $max_id;
}

function _custom_query($mysql_query) {
$this->load->model('mdl_site_security');
$query = $this->mdl_site_security->_custom_query($mysql_query);
return $query;
}

}