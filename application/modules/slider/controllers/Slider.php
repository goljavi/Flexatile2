<?php
//Flexatile 2
class Slider extends MX_Controller 
{

function __construct() {
parent::__construct();
}

function submit()
{
    $this->load->module('site_security');
    $this->site_security->_make_sure_is_admin();

    $data['link'] = $this->input->post('link', TRUE);
    $id = $this->input->post('id', TRUE);


    $this->_update($id, $data);
    redirect('slider');
}

function view()
{
    $data['query'] = $this->get('priority');
    $this->load->view('slider', $data);
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

//displays a table with images uploaded and a button to upload them
function index()
{
    $this->load->module('site_security');
    $this->site_security->_make_sure_is_admin();
    $data['query'] = $this->get('priority');
    $data['headline'] = 'Slider de fotos';

    $data['flash_image_error'] = $this->session->flashdata('flash_image_error');
    $data['flash'] = $this->session->flashdata('item');
    $data['flash_del'] = $this->session->flashdata('delete_item');

    $data['sort_this'] = TRUE;
    $data['form_location'] = 'slider/do_upload/';
    $data['view_module'] = 'slider';
    $data['view_file'] = "upload_image";
    $this->load->module('templates');
    $this->templates->admin($data);

}

//uploads an image
function do_upload()
{
        $this->load->module('site_security');
        $this->site_security->_make_sure_is_admin();

        $path = './uploads/slider/';
        
        if(!is_dir($path)) 
        {
          mkdir($path,0755,TRUE);
        }

        $config['upload_path']          = './uploads/slider/';
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
                redirect('slider');
        }
        else
        {       
                $data = array('upload_data' => $this->upload->data());

                $upload_data = $data['upload_data'];
                $file_name = $upload_data['file_name'];
                $this->_generate_thumbnail($file_name);

                //Actualizar la base de datos
                $raw_file_name = $upload_data['raw_name'];
                $file_ext = $upload_data['file_ext'];

                $image['image_file'] = $upload_data['file_name'];
                $image['thumb'] = $raw_file_name.'_thumb'.$file_ext;
                $image['priority'] = 0;
                $image['link'] = '#';

                $this->_insert($image);

                $value = '<div class="alert alert-success">La imagen fue subida correctamente</div>';
                $this->session->set_flashdata('flash_image_error', $value);
                redirect('slider');
        }
}

//function called by "do_upload()". makes a thumbnail for the uploaded image
function _generate_thumbnail($file_name)
{
        $config['image_library'] = 'gd2';
        $config['source_image'] = './uploads/slider/'.$file_name;
        $config['new_image'] = './uploads/slider/'.$file_name;
        $config['create_thumb'] = TRUE;
        $config['maintain_ratio'] = TRUE;
        $config['width']         = 200;
        $config['height']       = 200;
        
        $this->load->library('image_lib', $config);
        $this->image_lib->resize();
}


//removes an image
function del($id){
    $this->load->module('site_security');
    $this->site_security->_make_sure_is_admin();
    if(!is_numeric($id)){redirect('index');}

    $submit = $this->input->post('submit', TRUE);
    if ($submit=='No, cancelar.'){
    redirect('slider');
    }

    if ($submit=='Si, borrar.'){

    //attempt to remove the images from the db
    $data = $this->_get_image_name($id);
    $this->_delete($id);

    //attempt to remove the images from folder
    if(file_exists('./uploads/slider/'.$data['image_file']))
    {
        unlink('./uploads/slider/'.$data['image_file']);
    }
    if(file_exists('./uploads/slider/'.$data['thumb']))
    {
        unlink('./uploads/slider/'.$data['thumb']);
    }
    
    $value = "<div class='alert alert-danger'>
            <strong>Hecho!</strong> La imagen fue <strong>eliminada</strong> satisfactoriamente.
            </div>";
        
    $this->session->set_flashdata('delete_item', $value);
    redirect('slider');
    }   

    $data['query'] = $this->get_where($id);
    $data['form_location'] = current_url();
    $data['view_module'] = 'slider';
    $data['view_file'] = "delete_image";
    $this->load->module('templates');
    $this->templates->admin($data);
}

/* FETCHING FUNCTIONS */
//function called by "del()" to display the image before removing it
function _get_image_name($id){
    if(!isset($id)){redirect('index');}
    $query = $this->_custom_query("SELECT image_file, thumb FROM slider WHERE id='".$id."'");
    foreach($query->result() as $row){
    $data['image_file'] = $row->image_file;
    $data['thumb'] = $row->thumb;
    }

    return $data;
}


/* MODEL FUNCTIONS */
function get($order_by)
{
    $this->load->model('mdl_slider');
    $query = $this->mdl_slider->get($order_by);
    return $query;
}

function get_with_limit($limit, $offset, $order_by) 
{
    if ((!is_numeric($limit)) || (!is_numeric($offset))) {
        die('Non-numeric variable!');
    }

    $this->load->model('mdl_slider');
    $query = $this->mdl_slider->get_with_limit($limit, $offset, $order_by);
    return $query;
}

function get_where($id)
{
    if (!is_numeric($id)) {
        die('Non-numeric variable!');
    }

    $this->load->model('mdl_slider');
    $query = $this->mdl_slider->get_where($id);
    return $query;
}

function get_where_custom($col, $value) 
{
    $this->load->model('mdl_slider');
    $query = $this->mdl_slider->get_where_custom($col, $value);
    return $query;
}

function _insert($data)
{
    $this->load->model('mdl_slider');
    $this->mdl_slider->_insert($data);
}

function _update($id, $data)
{
    if (!is_numeric($id)) {
        die('Non-numeric variable!');
    }

    $this->load->model('mdl_slider');
    $this->mdl_slider->_update($id, $data);
}

function _delete($id)
{
    if (!is_numeric($id)) {
        die('Non-numeric variable!');
    }

    $this->load->model('mdl_slider');
    $this->mdl_slider->_delete($id);
}

function count_where($column, $value) 
{
    $this->load->model('mdl_slider');
    $count = $this->mdl_slider->count_where($column, $value);
    return $count;
}

function get_max() 
{
    $this->load->model('mdl_slider');
    $max_id = $this->mdl_slider->get_max();
    return $max_id;
}

function _custom_query($mysql_query) 
{
    $this->load->model('mdl_slider');
    $query = $this->mdl_slider->_custom_query($mysql_query);
    return $query;
}

}