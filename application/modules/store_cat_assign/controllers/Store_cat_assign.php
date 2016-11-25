<?php
//Flexatile 2
class Store_cat_assign extends MX_Controller 
{

function __construct() {
parent::__construct();
}

function _delete_all_products_assigned($category_id){
    $this->_custom_query('DELETE FROM store_cat_assign WHERE category_id='.$category_id);
    return true;
}

function del($id, $item_id){
    $this->load->module('site_security');
    $this->site_security->_make_sure_is_admin(); 

    if(!is_numeric($id)){redirect('index');}
    if(!is_numeric($item_id)){redirect('index');}

    if($this->_check_if_assignment_exists($id)){
        $this->_delete($id);
    }else{
    die('Esta asignación no existe');   
    }

    redirect('store_items/create/'.$item_id.'#assign');
}

function _check_if_assignment_exists($id){
    if(!is_numeric($id)){redirect('index');}
    $query = $this->get_where($id);
    if($query->num_rows() > 0){return true;}else{return false;}
}

function _check_if_already_assigned($category_id, $item_id){

    $query = $this->_custom_query('SELECT id FROM store_cat_assign WHERE category_id='.$category_id.' AND item_id='.$item_id);
    
    foreach($query->result() as $row)
    {
        $id = $row->id;
    }

    if($query->num_rows() > 0)
    {
        return true;
    }else
    {
        return false;
    }
}

function _assigned_categories_table($item_id){
    $data['query'] = $this->get_where_custom('item_id', $item_id);
    $this->load->view('assigned_categories_table', $data);
}

function assign($item_id){
    $this->load->module('site_security');
    $this->site_security->_make_sure_is_admin(); 

    $submit = $this->input->post('submit', TRUE);

    if ($submit=="Asignar"){
        $category_id = $this->input->post('category_id', TRUE);
        if($category_id == 0){redirect('store_items/create/'.$item_id.'#assign');}
        $assign['category_id'] = $category_id;
        $assign['item_id'] = $item_id;
        if(!$this->_check_if_already_assigned($category_id, $item_id)){
        $this->_insert($assign);
        redirect('store_items/create/'.$item_id.'#assign');
        }
    }
    $data['item_id'] = $item_id;
    $data['form_location'] = current_url();
    $this->load->view('assign', $data);

}

/* MODEL FUNCTIONS */
function get($order_by)
{
    $this->load->model('mdl_store_cat_assign');
    $query = $this->mdl_store_cat_assign->get($order_by);
    return $query;
}

function get_with_limit($limit, $offset, $order_by) 
{
    if ((!is_numeric($limit)) || (!is_numeric($offset))) {
        die('Non-numeric variable!');
    }

    $this->load->model('mdl_store_cat_assign');
    $query = $this->mdl_store_cat_assign->get_with_limit($limit, $offset, $order_by);
    return $query;
}

function get_where($id)
{
    if (!is_numeric($id)) {
        die('Non-numeric variable!');
    }

    $this->load->model('mdl_store_cat_assign');
    $query = $this->mdl_store_cat_assign->get_where($id);
    return $query;
}

function get_where_custom($col, $value) 
{
    $this->load->model('mdl_store_cat_assign');
    $query = $this->mdl_store_cat_assign->get_where_custom($col, $value);
    return $query;
}

function _insert($data)
{
    $this->load->model('mdl_store_cat_assign');
    $this->mdl_store_cat_assign->_insert($data);
}

function _update($id, $data)
{
    if (!is_numeric($id)) {
        die('Non-numeric variable!');
    }

    $this->load->model('mdl_store_cat_assign');
    $this->mdl_store_cat_assign->_update($id, $data);
}

function _delete($id)
{
    if (!is_numeric($id)) {
        die('Non-numeric variable!');
    }

    $this->load->model('mdl_store_cat_assign');
    $this->mdl_store_cat_assign->_delete($id);
}

function count_where($column, $value) 
{
    $this->load->model('mdl_store_cat_assign');
    $count = $this->mdl_store_cat_assign->count_where($column, $value);
    return $count;
}

function get_max() 
{
    $this->load->model('mdl_store_cat_assign');
    $max_id = $this->mdl_store_cat_assign->get_max();
    return $max_id;
}

function _custom_query($mysql_query) 
{
    $this->load->model('mdl_store_cat_assign');
    $query = $this->mdl_store_cat_assign->_custom_query($mysql_query);
    return $query;
}

}