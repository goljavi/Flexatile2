<?php
//Flexatile 2
class Templates extends MX_Controller 
{

function __construct() {
parent::__construct();
}

//carga el template publico
function public_bootstrap($data)
{
	$this->load->view('public_bootstrap', $data);
}

//carga el template de admin
function admin($data)
{
	$this->load->view('admin', $data);
}

}