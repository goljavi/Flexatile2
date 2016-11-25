<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Shipping extends MX_Controller
{

function __construct() {
parent::__construct();
}

function _get_value($id){
	$data = $this->get_data_from_db($id);
	return $data['price'];
}

function _get_shipping_id(){
        $shipping_found = false;
        foreach ($this->cart->contents() as $items){
            if(strpos($items['id'], "shipping_") !== false){
            $shipping_id = $items['id'];
			$shipping_found = true; 	
            }
        }
        //De no haber metodo de envío se emite un error
        if(!$shipping_found){ die('No se seleccionó ninguna opción de envío'); }
    return $shipping_id;
}

function _delete_shipping(){
	//Elimina el metodo de envío por si el cliente decide volver atrás
        $shipping_found = false;
        foreach ($this->cart->contents() as $items){
            if(strpos($items['id'], "shipping_") !== false){
            $shipping_id = $items['id'];
            $data['rowid'] = $items['rowid'];
            $data['qty'] = 0;
			$this->cart->update($data);
			$shipping_found = true; 	
            }
        }
        //De no haber metodo de envío se emite un error
        if(!$shipping_found){ die('No se seleccionó ninguna opción de envío'); }
    return $shipping_id;
}

function _generate_shipping($shipping_id){
	if(!isset($shipping_id)){Modules::Run('err/_grab_data', 'x180'); die();}

	$shipping = $this->get_data_from_db($shipping_id);
	$cart['id'] = 'shipping_'.$shipping['id'];
	$cart['qty'] = 1;
	$cart['price'] = $shipping['price'];
	$cart['name'] = $shipping['title'];
	$rowid = $this->cart->insert($cart);
}

function _dropdown(){
	$data['query'] = $this->get('priority ASC');
	$this->load->view('dropdown', $data);
}

function _get_latest_id($title){
	if(!isset($title)){Modules::Run('err/_grab_data', 'x181'); die();}
	$query = $this->_custom_query('SELECT id FROM shipping WHERE title='."'".$title."'");
	foreach($query->result() as $row){
		$number = $row->id;
	}
	return $number;
}

function up($priority){
	Modules::run('site_security/_check_is_admin');

	if(!is_numeric($priority)){Modules::Run('err/_grab_data', 'x182'); die();}
	if(!isset($priority)){Modules::Run('err/_grab_data', 'x183'); die();}
	if($priority <= 1){Modules::Run('err/_grab_data', 'x184'); die();}

	$new_priority = $priority-1;

	//Se obtiene el id de la seccion
	$id = $this->_check_id($priority);
	//Se obtiene el id de la imagen a cambiar lugar con
	$id_cambio = $this->_check_id($new_priority);
    
    //Se procede a restarle un numero a la prioridad a la seccion a querer subir (teniendo en cuenta que "1" es la seccion mas importante)
	$data['priority'] = $new_priority;
    $this->_update($id, $data);
    unset($data);
    /*Se procede a poner la vieja prioridad de la otra seccion en la seccion a querer bajar (ejemplo, si la seccion a querer pasar
    estaba en el puesto 2, y se la quiere pasar al puesto 1, primero se ponen en 1 las dos, y luego esta seccion pasa a ser la 2)*/
	$data['priority'] = $priority;
    $this->_update($id_cambio, $data);
    unset($data);

    $this->db->cache_delete_all();

    redirect('shipping/manage/'.$new_priority.'#shipping');
}

function down($priority){
	Modules::run('site_security/_check_is_admin');

	if(!is_numeric($priority)){Modules::Run('err/_grab_data', 'x185'); die();}
	if(!isset($priority)){Modules::Run('err/_grab_data', 'x186'); die();}

	$priority_max = $this->_check_priority();
	if($priority >= $priority_max){Modules::Run('err/_grab_data', 'x187'); die();}

	$new_priority = $priority+1;

	//Se obtiene el id de la seccion
	$id = $this->_check_id($priority);
	//Se obtiene el id de la seccion a cambiar lugar con
	$id_cambio = $this->_check_id($new_priority);

    //Se procede a sumarle un numero a la seccion a querer bajar
	$data['priority'] = $new_priority;
    $this->_update($id, $data);
    unset($data);
    //Se procede a poner la vieja prioridad de la otra seccion en la imagen a querer subir
	$data['priority'] = $priority;
    $this->_update($id_cambio, $data);
    unset($data);

    $this->db->cache_delete_all();

    redirect('shipping/manage/'.$new_priority.'#shipping');
}

function _check_id($priority){
	if(!isset($priority)){Modules::Run('err/_grab_data', 'x188'); die();}

	$query = $this->_custom_query("SELECT id FROM shipping WHERE priority='".$priority."'");

	foreach($query->result() as $row){
	$id = $row->id;
	}
	if (!isset($id)){Modules::Run('err/_grab_data', 'x237'); die();}
	return $id;
}

function del($id){
	Modules::run('site_security/_check_is_admin');
	if(!isset($id)){Modules::Run('err/_grab_data', 'x189'); die();}

	$submit = $this->input->post('submit', TRUE);
	if ($submit=='No, cancelar.'){
		redirect('shipping/manage');
	}
	if ($submit=='Si, borrar.'){
		$this->_delete($id);
	$value = "<div class='alert alert-danger'>
	  		<strong>Hecho!</strong> La seccion fue <strong>eliminada</strong> satisfactoriamente.
			</div>";
		
	$this->session->set_flashdata('delete', $value);
		redirect('shipping/manage');
	}

	$data = $this->get_data_from_db($id);
	$template ="admin";
	$current_url = current_url();
	$data['form_location'] = current_url();
	$data['view_file'] = "delete";
	$this->load->module('template');
	$this->template->$template($data);
}

function manage($moved = 0) {

	$data['moved'] = $moved;
	$data['priority_max'] = $this->_check_priority();
	$flash_delete = $this->session->flashdata('delete');
	if ($flash_delete!=""){
		$data['flash_delete'] = $flash_delete;
	}
	$data['view_module'] = 'shipping';
    $data['view_file'] = "manage";
    $this->load->module('templates');
    $this->templates->admin($data);

}

function _display_table(){
	$data['query'] = $this->get('priority ASC');
	$this->load->view('table', $data);
}

function _check_priority(){

	$query = $this->_custom_query("SELECT priority FROM shipping ORDER BY priority ASC");

	foreach($query->result() as $row){
	$number = $row->priority;
	}

	if (!isset($number)){
		$number = 0;
	}
	return $number;

}

function submit(){
		Modules::run('site_security/_check_is_admin');
		$this->load->helper(array('form', 'url'));

		$this->load->library('form_validation');
		$this->form_validation->set_rules('title', 'Título', 'required');
		$this->form_validation->set_rules('price', 'Precio', 'required|numeric');

		if ($this->form_validation->run() == FALSE)
		{
			$this->create();
		}
		else
		{

			$data = $this->get_data_from_post();
			$id = $this->uri->segment(3);
			
			$data['title'] = $this->_check_title($data['title']);

			if ($id>0){
				$this->_update($id, $data);
				$value = "<div class='alert alert-success'>
	  			<strong>Hurra!</strong> La sección fue <strong>editada</strong> satisfactoriamente.
				</div>";
				$this->session->set_flashdata('create', $value);

				$this->db->cache_delete_all();

				redirect('shipping/create/'.$id);
			}else{
				$data['priority'] = $this->_check_priority()+1;
				$this->_insert($data);
				
				$value = "<div class='alert alert-success'>
	  			<strong>Hurra!</strong> La sección fue <strong>creada</strong> satisfactoriamente.
				</div>";
				$this->session->set_flashdata('create', $value);

				$id = $this->_get_latest_id($data['title']);

				$this->db->cache_delete_all();

				redirect('shipping/create/'.$id);
			}
			
		}
}

function _check_title($title){
		$symbols = array('º' => '', 'ª' => '', '!' => '', '|' => '', '@' => '', '·' => '', '¡' => '', 
						 '/' => '',  '?' => '',  '¿' => '', '[' => '',  ']' => '',  '{' => '',  '}' => '',  
						 '^' => '',  '¨' => '',  'ç' => '', 'Ç' => '',  '*' => '', '"' => '', '$' => '', 
						 '%' => '', '#' => '', '&' => '', '=' => '',  '(' => '', ')' => '', "'" => '', 
						 '¬' => '', '+' => '', '\\' => '');
		$checked_title = strtr($title, $symbols);
		if($checked_title == ''){$checked_title = 'a';}
		return $checked_title;
}

function get_data_from_post(){
	$data['title'] = $this->input->post('title', TRUE);
	$data['price'] = $this->input->post('price', TRUE);
	return $data;
}

function create(){
	$id = $this->uri->segment(3);
	$data = $this->get_data_from_post();
	if ($id>0){
		$data = $this->get_data_from_db($id);
		$data['headline'] = "Editando: ";
	}else{
		$data['headline'] = "Creando nueva sección de envíos";
	}

	$current_url = current_url();
    $data['form_location'] = str_replace('/create', '/submit', $current_url);

	$flash_create = $this->session->flashdata('create');
	if ($flash_create!=""){
		$data['flash_create'] = $flash_create;
	}

	$data['view_module'] = 'shipping';
    $data['view_file'] = "create";
    $this->load->module('templates');
    $this->templates->admin($data);
}

function get_data_from_db($id) {
	if(!isset($id)){Modules::Run('err/_grab_data', 'x190'); die();}
	$query = $this->get_where($id);
	foreach($query->result() as $row){
		$data['id'] = $row->id;
		$data['priority'] = $row->priority;
		$data['title'] = $row->title;
		$data['price'] = $row->price;
	}
	if(!isset($data)){
		die('id de envío invalida');
	}


	return $data;
}

function get($order_by) {
$this->load->model('mdl_shipping');
$query = $this->mdl_shipping->get($order_by);
return $query;
}

function get_with_limit($limit, $offset, $order_by) {
$this->load->model('mdl_shipping');
$query = $this->mdl_shipping->get_with_limit($limit, $offset, $order_by);
return $query;
}

function get_where($id) {
$this->load->model('mdl_shipping');
$query = $this->mdl_shipping->get_where($id);
return $query;
}

function get_where_custom($col, $value) {
$this->load->model('mdl_shipping');
$query = $this->mdl_shipping->get_where_custom($col, $value);
return $query;
}

function _insert($data) {
$this->load->model('mdl_shipping');
$this->mdl_shipping->_insert($data);
}

function _update($id, $data) {
$this->load->model('mdl_shipping');
$this->mdl_shipping->_update($id, $data);
}

function _delete($id) {
$this->load->model('mdl_shipping');
$this->mdl_shipping->_delete($id);
}

function count_where($column, $value) {
$this->load->model('mdl_shipping');
$count = $this->mdl_shipping->count_where($column, $value);
return $count;
}

function get_max() {
$this->load->model('mdl_shipping');
$max_id = $this->mdl_shipping->get_max();
return $max_id;
}

function _custom_query($mysql_query) {
$this->load->model('mdl_shipping');
$query = $this->mdl_shipping->_custom_query($mysql_query);
return $query;
}

}