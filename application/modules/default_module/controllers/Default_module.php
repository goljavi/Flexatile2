<?php
//Flexatile 2
class Default_module extends MX_Controller 
{

function __construct() {
parent::__construct();
}

function index()
{
 $data['view_module'] = 'default_module';
 $data['view_file'] = "home";
 $data['select'] = 'home';
 $this->load->module('templates');
 $this->templates->public_bootstrap($data);
}

function notfound(){
 $data['view_module'] = 'default_module';
 $data['view_file'] = "notfound";
 $this->load->module('templates');
 $this->templates->public_bootstrap($data);
}

}