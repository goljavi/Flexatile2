<?php
//Flexatile 2
class Config extends MX_Controller 
{

function __construct() {
parent::__construct();
}

function get_phpmailer_data(){
	$data['noreply'] = 'noreply@floreria.com.ar';
	$data['noreply-name'] = 'Floreria';
	$data['sendto'] = 'somossedentarios@gmail.com';
	return $data;
}

function get_site_name(){
	return 'Floreria';
}

function subcategory_depth_allowed(){
	return 2;
}

}