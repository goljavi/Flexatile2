<?php
//Flexatile 2
class Contacto extends MX_Controller 
{

function __construct() {
parent::__construct();
}

function _load_form($place){
	$data['place'] = $place;
	$this->load->view('form', $data);
}

function submit(){
	$this->load->helper(array('form', 'url'));

	$this->load->library('form_validation');
	$this->form_validation->set_rules('name', 'Nombre y Apellido', 'trim|required');
	$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
	$this->form_validation->set_rules('phone', 'Telefono', 'trim|required');
	$this->form_validation->set_rules('message', 'Mensaje', 'trim|required');

	if ($this->form_validation->run() == FALSE)
		{
			echo validation_errors("<div class='alert alert-danger'>", "</div>");
		}
		else
		{
			$contact = Modules::Run('config/get_phpmailer_data');
			$data = $this->get_data_from_post();
			$this->load->library('my_phpmailer');

			$mail = new PHPMailer;
			$mail->SMTPDebug = 2;

			$mail->setFrom($contact['noreply'], $contact['noreply-name']);
			$mail->addAddress($contact['sendto']);
			$mail->addReplyTo($data['email'], $data['name']);

			$mail->WordWrap = 50;                              // set word wrap
			$mail->isHTML(true);

			$mail->Subject = 'Consulta desde '.Modules::Run('config/get_site_name');

			$mail->Body ='
				<html><body>
				<h3>Consulta desde formulario de contacto</h3>
				<table rules="all" style="border-color: #666;" cellpadding="10">
			    <tr style="background: #eee;"><td><strong>Nombre y Apellido: </strong></td><td>'.$data['name'].'</td></tr>
			    <tr><td><strong>Email: </strong></td><td>'.$data['email'].'</td></tr>
			    <tr><td><strong>Telefono: </strong></td><td>'.$data['phone'].'</td></tr>
			    <tr><td><strong>Desde: </strong></td><td>'.$data['place'].'</td></tr>
			    <tr><td><strong>Mensaje: </strong></td><td><p>'.$data['message'].'</p></td></tr>
			    </table>
			    </body></html>
			    ';

			$mail->AltBody  =  "Para visualizar correctamente este mensaje utiliza un cliente de correo que soporte HTML";


			if(!$mail->send()) {
				echo "<div class='alert alert-warning'>
		  		El mensaje no pudo ser enviado, Error: $mail->ErrorInfo
				</div>";
			} else {
				echo '<div class="alert alert-success">Gracias por ponerse en contacto, le responderemos a la brevedad</div>';
			} 
		}
}

function get_data_from_post(){
	$data['name'] = $this->input->post('name', TRUE);
	$data['email'] = $this->input->post('email', TRUE);
	$data['phone'] = $this->input->post('phone', TRUE);
	$data['message'] = $this->input->post('message', TRUE);
	$data['place'] = $this->input->post('place', TRUE);
	return $data;
}

function index()
{
    $data['view_module'] = 'contacto';
    $data['view_file'] = "index";
    $this->load->module('templates');
    $this->templates->public_bootstrap($data);
}

}