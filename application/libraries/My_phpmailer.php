<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
require_once('PHPMailer-master/PHPMailerAutoload.php');

class My_PHPMailer extends PHPMailer{
 public function __construct() {
		parent::__construct();
	}
}
?>