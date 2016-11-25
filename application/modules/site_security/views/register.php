<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8">
		<meta charset="utf-8">
		<title>AR | Admin Register</title>
		<meta name="generator" content="Bootply" />
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<link href="<?php echo base_url(); ?>css/bootstrap.min.css" rel="stylesheet">
		<!--[if lt IE 9]>
			<script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->
		<link href="css/styles.css" rel="stylesheet">
	</head>
	<body>
<!--login modal-->
<div id="loginModal" class="modal show" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog">
  <div class="modal-content">
      <div class="modal-header">
          <h1 class="text-center">Registrar</h1>
      </div>
      <div class="modal-body">
        <?php
        echo validation_errors("<div class='alert alert-danger'>
        <strong>Alerta!</strong> ", "</div>");
        if(isset($flash_error)){
          echo $flash_error;
        }
        echo form_open('admin/create_member', 'class="form col-md-12 center-block"'); 
        ?>   
            <div class="form-group">
              <?php echo form_input('username', '', 'class="form-control input-lg" placeholder="Usuario"') ?>
            </div>
            <div class="form-group">
              <?php echo form_password('password', '', 'class="form-control input-lg" placeholder="Contraseña"') ?>
            </div>
            <div class="form-group">
              <?php echo form_password('password_confirm', '', 'class="form-control input-lg" placeholder="Confirmar contraseña"') ?>
            </div>
            <div class="form-group">
              <?php echo form_password('admpassword', '', 'class="form-control input-lg" placeholder="Contraseña del admin"') ?>
            </div>
            <div class="form-group">
              <?php echo form_submit('submit', 'Crear cuenta', 'class="btn btn-primary btn-lg btn-block"') ?>
            </div>
          <?php echo form_close(); ?>
      </div>
      <div class="modal-footer">	
      </div>
  </div>
  </div>
</div>
	<!-- script references -->
		<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
		<script src="<?php echo base_url(); ?>js/bootstrap.min.js"></script>
	</body>
</html>