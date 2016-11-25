<?php
//Flexatile 2
class Store_items extends MX_Controller 
{

function __construct() {
parent::__construct();
}

function buy($product_id){
    $data = $this->fetch_data_from_db($product_id);
    (float)$shipping = Modules::run('shipping/_get_value', $this->input->post('shipping', TRUE));
    $email = $this->input->post('email', TRUE);

    $preference_data = array(
        "payer" => array(
            "email" => $email
        ),
        "items" => array(
            array(
                "title" =>  $data['item_title'],
                "quantity" => 1,
                "currency_id" => "ARS",
                "unit_price" => (float)$shipping + (float)$data['item_price'],
                "picture_url" => base_url().'uploads/images/'.$data['id'].'/'.Modules::run('item_images/get_first_item_image', $data['id'])
            )
        )
    );

    $this->load->library('mp');
    $mp = new MP();
    $data['preference'] = $mp->create_preference($preference_data);
    redirect($data['preference']['response']["init_point"]);
}


function _draw_products($category_id, $inner_category = FALSE, $offset = 0)
{
    if($inner_category)
    {
        $data['query'] = $this->_custom_query
        ("SELECT * FROM store_cat_assign 
        INNER JOIN store_items
        ON store_cat_assign.item_id=store_items.id
        INNER JOIN item_images
        ON store_items.id=item_images.item_id
        WHERE store_cat_assign.category_id='$category_id'
        AND store_items.item_active='1'
        AND item_images.priority='1'
        LIMIT 9 OFFSET $offset");  
    }else
    {
        $data['query'] = $this->_custom_query
        ("SELECT * FROM store_cat_assign 
        INNER JOIN store_items
        ON store_cat_assign.item_id=store_items.id
        INNER JOIN item_images
        ON store_items.id=item_images.item_id
        WHERE store_cat_assign.category_id='$category_id'
        AND store_items.item_active='1'
        AND item_images.priority='1'");
    }

    $data['inner_category'] = $inner_category;
    $this->load->view('draw_products', $data);
}

function view($url)
{
	$data = $this->fetch_data_from_db_url($url);
    if($data==''){redirect('404');}
    $data['form_location'] = 'store_items/buy/'.$data['id'];
    $data['view_module'] = 'store_items';
    $data['view_file'] = "view";
    $this->load->module('templates');
    $this->templates->public_bootstrap($data);
}

//deletes a store item
function del($item_id)
{
    $this->load->module('site_security');
    $this->site_security->_make_sure_is_admin();
    if(!is_numeric($item_id)){redirect('index');}

    $submit = $this->input->post('submit', TRUE);
    if ($submit=='No, cancelar.'){
    redirect('store_items/index');
    }

    if ($submit=='Si, borrar.'){

    $this->_delete_all_records($item_id);
    
    $value = "<div class='alert alert-danger'>
            <strong>Hecho!</strong> El producto fue <strong>eliminado</strong> satisfactoriamente.
            </div>";
        
    $this->session->set_flashdata('delete_item', $value);
    redirect('store_items/index');

    }   

    $data = $this->fetch_data_from_db($item_id);
    $data['form_location'] = current_url();
    $data['view_module'] = 'store_items';
    $data['view_file'] = "delete_item";
    $this->load->module('templates');
    $this->templates->admin($data);

}

//function called by "del()" from store_items. it removes all item-relative data
function _delete_all_records($id)
{
    //attempt to delete item images
    $this->load->module('item_images');
    $this->item_images->_del_all($id);

    //attempt to remove item record from db
    $this->_delete($id);
}

//creates a store item
function create()
{
    $this->load->module('site_security');
    $this->site_security->_make_sure_is_admin();

    $update_id = $this->uri->segment(3);
    $submit = $this->input->post('submit', TRUE);

    if($submit=='Guardar')
    {

        $this->load->library('form_validation');
        $this->form_validation->set_rules('item_title', 'Título', 'required|max_length[250]');
        $this->form_validation->set_rules('item_price', 'Precio', 'is_numeric|required');
        $this->form_validation->set_rules('was_price', 'Precio Viejo', 'is_numeric');
        $this->form_validation->set_rules('item_description', 'Descripción', 'required');
        $this->form_validation->set_rules('item_active', 'Estatus', 'required|numeric');
        $this->form_validation->set_rules('item_url', 'URL', 'alpha_dash|max_length[250]');

        if ($this->form_validation->run() == TRUE)
        {
            $data = $this->fetch_data_from_post();

            $data['item_title'] = $this->title_check($data['item_title']);
            
            if($data['item_url']=='')
            {
                $data['item_url'] = $this->url_check(url_title($data['item_title']));
            }
            else
            {
                $data['item_url'] = $this->url_check($data['item_url']);
            }

            if($data['was_price']==''){$data['was_price']=0;}
            
            if(is_numeric($update_id))
            {
                $data['editby'] = $this->session->userdata('username');
                $data['date_edit'] = time();
                $this->_update($update_id, $data);
                $flash_msg = 'El producto fue actualizado correctamente';
                $value = '<div class="alert alert-success">'.$flash_msg.'</div>';
            }
            else
            {
                $data['createdby'] = $this->session->userdata('username');
                $data['date_created'] = time();
                $this->_insert($data);
                $update_id = $this->get_max(); //Get the id of the new item
                $flash_msg = 'El producto fue creado correctamente';
                $value = '<div class="alert alert-success">'.$flash_msg.'</div>';
            }
            $this->session->set_flashdata('item', $value);
            redirect('store_items/create/'.$update_id);
        }

    }
    if(is_numeric($update_id))
    {
        $data = $this->fetch_data_from_db($update_id);
        $data['headline'] = 'Editando Producto';
        
        $this->load->module('timedate');
        $data['date_created'] = $this->timedate->get_nice_date($data['date_created'], 'oldschool');
        $data['date_edit'] = $this->timedate->get_nice_date($data['date_edit'], 'oldschool');
    }
    else
    {
        $data = $this->fetch_data_from_post();
        $data['headline'] = 'Agregar Nuevo Producto';
    }

    $data['sort_this'] = TRUE;
    $data['update_id'] = $update_id;
    $data['flash'] = $this->session->flashdata('item');
    $data['form_location'] = 'store_items/create/'.$update_id;
    $data['view_module'] = 'store_items';
    $data['view_file'] = 'create';
    $this->load->module('templates');
    $this->templates->admin($data);
}

//fetches all records from store_items and displays them in a table 
function index()
{
    $this->load->module('site_security');
    $this->site_security->_make_sure_is_admin();

    $data['query'] = $this->get('item_title');
    
    $data['flash'] = $this->session->flashdata('delete_item');
    $data['headline'] = 'Catálogo';
    $data['view_module'] = 'store_items';
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
            $query = $this->_custom_query('select * from store_items where item_url="'.$str.'" and id!='.$update_id);
        }
        else
        {
            //Si no hay update_id singfica que se está creando un nuevo producto por lo que se debe buscar en todos los demás la coincidencia de url
            $query = $this->get_where_custom('item_url', $str);
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
    $data['item_title'] = $this->input->post('item_title', TRUE);
    $data['item_price'] = $this->input->post('item_price', TRUE);
    $data['was_price'] = $this->input->post('was_price', TRUE);
    $data['item_description'] = $this->input->post('item_description', TRUE);
    $data['item_url'] = $this->input->post('item_url', TRUE);
    $data['item_active'] = $this->input->post('item_active', TRUE);
    return $data;
}

//fetches data from db to fill "create()" form
function fetch_data_from_db($update_id)
{
    $query = $this->get_where($update_id);
    foreach($query->result() as $row)
    {
        $data['id'] = $row->id;
        $data['item_title'] = $row->item_title;
        $data['item_price'] = $row->item_price;
        $data['was_price'] = $row->was_price;
        $data['item_description'] = $row->item_description;
        $data['item_url'] = $row->item_url;
        $data['item_active'] = $row->item_active;
        $data['date_created'] = $row->date_created;
        $data['date_edit'] = $row->date_edit;
        $data['createdby'] = $row->createdby;
        $data['editby'] = $row->editby; 
    }
    
    if(!isset($data))
    {
        $data = '';
    }

    return $data;
}

//fetches data from db to fill "view()"
function fetch_data_from_db_url($url)
{
    $query = $this->get_where_custom('item_url', $url);
    foreach($query->result() as $row)
    {
        $data['id'] = $row->id;
        $data['item_title'] = $row->item_title;
        $data['item_price'] = $row->item_price;
        $data['was_price'] = $row->was_price;
        $data['item_description'] = $row->item_description;
        $data['item_url'] = $row->item_url;
        $data['item_active'] = $row->item_active;
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
    $this->load->model('mdl_store_items');
    $query = $this->mdl_store_items->get($order_by);
    return $query;
}

function get_with_limit($limit, $offset, $order_by) 
{
    if ((!is_numeric($limit)) || (!is_numeric($offset))) {
        die('Non-numeric variable!');
    }

    $this->load->model('mdl_store_items');
    $query = $this->mdl_store_items->get_with_limit($limit, $offset, $order_by);
    return $query;
}

function get_where($id)
{
    if (!is_numeric($id)) {
        die('Non-numeric variable!');
    }

    $this->load->model('mdl_store_items');
    $query = $this->mdl_store_items->get_where($id);
    return $query;
}

function get_where_custom($col, $value) 
{
    $this->load->model('mdl_store_items');
    $query = $this->mdl_store_items->get_where_custom($col, $value);
    return $query;
}

function _insert($data)
{
    $this->load->model('mdl_store_items');
    $this->mdl_store_items->_insert($data);
}

function _update($id, $data)
{
    if (!is_numeric($id)) {
        die('Non-numeric variable!');
    }

    $this->load->model('mdl_store_items');
    $this->mdl_store_items->_update($id, $data);
}

function _delete($id)
{
    if (!is_numeric($id)) {
        die('Non-numeric variable!');
    }

    $this->load->model('mdl_store_items');
    $this->mdl_store_items->_delete($id);
}

function count_where($column, $value) 
{
    $this->load->model('mdl_store_items');
    $count = $this->mdl_store_items->count_where($column, $value);
    return $count;
}

function get_max() 
{
    $this->load->model('mdl_store_items');
    $max_id = $this->mdl_store_items->get_max();
    return $max_id;
}

function _custom_query($mysql_query) 
{
    $this->load->model('mdl_store_items');
    $query = $this->mdl_store_items->_custom_query($mysql_query);
    return $query;
}

}