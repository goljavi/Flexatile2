<?php
//Flexatile 2
class Item_images extends MX_Controller 
{

function __construct() {
parent::__construct();
}

function editor(){
    if(!move_uploaded_file($_FILES['file']['tmp_name'], './images/'.$_FILES['file']['name']))
    {
        echo "./images/img_upload_error.jpg";
    }else
    {
        echo base_url().'images/'.$_FILES['file']['name'];
    }
}

function sort()
{
   $this->load->module('site_security');
   $this->site_security->_make_sure_is_admin(); 

   $number = $this->input->post('number', TRUE);

   for($i=1; $i <= $number; $i++){
        $update_id = $_POST['order'.$i];
        $data['priority'] = $i;
        echo  'id: '.$update_id.' prioridad: '.$data['priority'].' ';
        $this->_update($update_id, $data);
   }
}

function get_first_item_image($item_id)
{
    $query = $this->_custom_query('select * from item_images where item_id='.$item_id.' ORDER BY priority LIMIT 1');
    foreach($query->result() as $row){
        $thumb = $row->thumb;
    }
    if($query->num_rows() < 1){
        return 'noimage';
    }
    return $thumb;
}

function get_item_images($item_id)
{
    if(!is_numeric($item_id)){redirect('index');}
    $data['query'] = $this->_custom_query('select * from item_images where item_id='.$item_id.' order by priority');
    $this->load->view('item_images', $data);
}

//displays a table with images uploaded and a button to upload them
function upload_image($item_id)
{
    if(!is_numeric($item_id)){redirect('index');}
    $this->load->module('site_security');
    $this->site_security->_make_sure_is_admin();


    $data['query'] = $this->_custom_query('select * from item_images where item_id='.$item_id.' order by priority');
    $data['item_id'] = $item_id;
    $data['flash_image_error'] = $this->session->flashdata('flash_image_error');
    $data['headline'] = 'Subir imagen';
    $data['flash'] = $this->session->flashdata('item');
    $data['form_location'] = 'item_images/do_upload/'.$item_id;
    $this->load->view('upload_image', $data);
}

//uploads an image
function do_upload($item_id)
{
        if(!is_numeric($item_id)){redirect('index');}
        $this->load->module('site_security');
        $this->site_security->_make_sure_is_admin();

        $path = './uploads/images/'.$item_id;
        
        if(!is_dir($path)) 
        {
          mkdir($path,0755,TRUE);
        }

        $config['upload_path']          = './uploads/images/'.$item_id;
        $config['allowed_types']        = 'gif|jpg|png';
        $config['max_size']             = 2000;
        $config['max_width']            = 3000;
        $config['max_height']           = 3000;
        $config['file_name'] = rand();

        $this->load->library('upload', $config);

        if ( ! $this->upload->do_upload('userfile'))
        {
                $value = '<div class="alert alert-danger">'.$this->upload->display_errors().'</div>';
                $this->session->set_flashdata('flash_image_error', $value);
                redirect('store_items/create/'.$item_id.'#image_gallery');
        }
        else
        {       
                $data = array('upload_data' => $this->upload->data());

                $upload_data = $data['upload_data'];
                $file_name = $upload_data['file_name'];
                $this->_generate_thumbnail($file_name, $item_id);

                //Actualizar la base de datos
                $raw_file_name = $upload_data['raw_name'];
                $file_ext = $upload_data['file_ext'];

                $image['image_file'] = $upload_data['file_name'];
                $image['item_id'] = $item_id;
                $image['thumb'] = $raw_file_name.'_thumb'.$file_ext;
                $image['priority'] = $this->_check_priority($item_id)+1;

                $this->_insert($image);

                $value = '<div class="alert alert-success">La imagen fue subida correctamente</div>';
                $this->session->set_flashdata('flash_image_error', $value);
                redirect('store_items/create/'.$item_id.'#image_gallery');
        }
}

//function called by "do_upload()". makes a thumbnail for the uploaded image
function _generate_thumbnail($file_name, $item_id)
{
        $config['image_library'] = 'gd2';
        $config['source_image'] = './uploads/images/'.$item_id.'/'.$file_name;
        $config['new_image'] = './uploads/images/'.$item_id.'/'.$file_name;
        $config['create_thumb'] = TRUE;
        $config['maintain_ratio'] = TRUE;
        $config['width']         = 200;
        $config['height']       = 200;
        
        $this->load->library('image_lib', $config);
        
        $this->image_lib->resize();
}

//Chequea el numero de imagenes subidas
function _check_priority($item_id){
    if(!is_numeric($item_id)){redirect('index');}
    $query = $this->_custom_query("SELECT priority FROM item_images WHERE item_id='".$item_id."' ORDER BY priority ASC");
    foreach($query->result() as $row){$number = $row->priority;}
    if (!isset($number)){$number = 0;}
    return $number;
}

//removes an image
function del($priority, $item_id){
    $this->load->module('site_security');
    $this->site_security->_make_sure_is_admin();

    if(!isset($priority)){redirect('index');}
    if(!is_numeric($priority)){redirect('index');}
    if(!isset($item_id)){redirect('index');}
    if(!is_numeric($item_id)){redirect('index');}

    $id = $this->_check_id($priority, $item_id);

    $submit = $this->input->post('submit', TRUE);
    if ($submit=='No, cancelar.'){
    redirect('store_items/create/'.$item_id.'#image_gallery');
    }

    if ($submit=='Si, borrar.'){

    //attempt to remove the images from the db
    $data = $this->_get_image_name($id);
    $this->_delete($id);

    //attempt to remove the images from folder
    if(file_exists('./uploads/images/'.$item_id.'/'.$data['image_file']))
    {
        unlink('./uploads/images/'.$item_id.'/'.$data['image_file']);
    }
    if(file_exists('./uploads/images/'.$item_id.'/'.$data['thumb']))
    {
        unlink('./uploads/images/'.$item_id.'/'.$data['thumb']);
    }
    


    $value = "<div class='alert alert-danger'>
            <strong>Hecho!</strong> La imagen fue <strong>eliminada</strong> satisfactoriamente.
            </div>";
        
    $this->session->set_flashdata('delete_item', $value);
    redirect('store_items/create/'.$item_id.'#image_gallery');


    }   

    $data['item_id'] = $item_id;
    $data['query'] = $this->get_where($id);
    $data['form_location'] = current_url();
    $data['view_module'] = 'item_images';
    $data['view_file'] = "delete_image";
    $this->load->module('templates');
    $this->templates->admin($data);
}

//this function is called by "_delete_all_records()" from store_items when an item is removed
function _del_all($item_id){
    $query = $this->get_where_custom('item_id', $item_id);
    foreach($query->result() as $row)
    {
        //attempt to remove the images from folder
        if(file_exists('./uploads/images/'.$item_id.'/'.$row->image_file))
        {
            unlink('./uploads/images/'.$item_id.'/'.$row->image_file);
        }
        if(file_exists('./uploads/images/'.$item_id.'/'.$row->thumb))
        {
            unlink('./uploads/images/'.$item_id.'/'.$row->thumb);
        }

        //attempt to remove image record from db
        $this->_delete($row->id);
    }
}





/* FETCHING FUNCTIONS */

//function called by "del()". Checks if id exists
function _check_id($priority, $item_id){
    if(!is_numeric($priority)){redirect('index');}
    if(!is_numeric($item_id)){redirect('index');}

    $query = $this->_custom_query("SELECT id FROM item_images WHERE item_id='".$item_id."' AND priority='".$priority."'");

    foreach($query->result() as $row){
    $id = $row->id;
    }
    if (!isset($id)){redirect('index');}
    return $id;
}

//function called by "del()" to display the image before removing it
function _get_image_name($id){
    if(!isset($id)){redirect('index');}
    $query = $this->_custom_query("SELECT image_file, thumb FROM item_images WHERE id='".$id."'");
    foreach($query->result() as $row){
    $data['image_file'] = $row->image_file;
    $data['thumb'] = $row->thumb;
    }

    return $data;
}

/* MODEL FUNCTIONS */
function get($order_by)
{
    $this->load->model('mdl_item_images');
    $query = $this->mdl_item_images->get($order_by);
    return $query;
}

function get_with_limit($limit, $offset, $order_by) 
{
    if ((!is_numeric($limit)) || (!is_numeric($offset))) {
        die('Non-numeric variable!');
    }

    $this->load->model('mdl_item_images');
    $query = $this->mdl_item_images->get_with_limit($limit, $offset, $order_by);
    return $query;
}

function get_where($id)
{
    if (!is_numeric($id)) {
        die('Non-numeric variable!');
    }

    $this->load->model('mdl_item_images');
    $query = $this->mdl_item_images->get_where($id);
    return $query;
}

function get_where_custom($col, $value) 
{
    $this->load->model('mdl_item_images');
    $query = $this->mdl_item_images->get_where_custom($col, $value);
    return $query;
}

function _insert($data)
{
    $this->load->model('mdl_item_images');
    $this->mdl_item_images->_insert($data);
}

function _update($id, $data)
{
    if (!is_numeric($id)) {
        die('Non-numeric variable!');
    }

    $this->load->model('mdl_item_images');
    $this->mdl_item_images->_update($id, $data);
}

function _delete($id)
{
    if (!is_numeric($id)) {
        die('Non-numeric variable!');
    }

    $this->load->model('mdl_item_images');
    $this->mdl_item_images->_delete($id);
}

function count_where($column, $value) 
{
    $this->load->model('mdl_item_images');
    $count = $this->mdl_item_images->count_where($column, $value);
    return $count;
}

function get_max() 
{
    $this->load->model('mdl_item_images');
    $max_id = $this->mdl_item_images->get_max();
    return $max_id;
}

function _custom_query($mysql_query) 
{
    $this->load->model('mdl_item_images');
    $query = $this->mdl_item_images->_custom_query($mysql_query);
    return $query;
}

}