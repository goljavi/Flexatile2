<!DOCTYPE html>
<html class="no-js" lang="en">
  <head>
    <meta charset="utf-8">
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
    <title>Panel de Admin</title>
    <meta content="lab2023" name="author">
    <meta content="" name="description">
    <meta content="" name="keywords">

    <link href="<?php echo base_url(); ?>assets-admin/images/favicon.ico" rel="icon" type="image/ico" />

    <!-- CSS -->  
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url(); ?>assets-admin/stylesheets/application-a07755f5.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url(); ?>assets-admin/stylesheets/dataTables.bootstrap.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets-admin/stylesheets/dataTables.responsive.css" rel="stylesheet">
    <link href="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.6.4/summernote.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.0/jquery-ui.css" rel="stylesheet">

    <!-- Javascripts -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/modernizr/2.6.2/modernizr.min.js" type="text/javascript"></script>
    <script src="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.6.4/summernote.js"></script>
    <script src="<?php echo base_url(); ?>assets-admin/javascripts/application-985b892b.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>assets-admin/javascripts/jquery.dataTables.min.js"></script>
    <script src="<?php echo base_url(); ?>assets-admin/javascripts/dataTables.bootstrap.min.js"></script>
    
  </head>
  <body class="main page">

<?php 
if (isset($sort_this))
{
  require_once('sort_this_code.php');
}
?>

    <!-- Navbar -->
    <div class="navbar navbar-default" id="navbar">
      <a class="navbar-brand" href="#">
        Florería
      </a>
       <?php if((bool)$this->session->userdata('is_logged_in')): ?>
      <ul class="nav navbar-nav pull-right">
        <li class="dropdown user">
          <a class="dropdown-toggle" data-toggle="dropdown" href="#">
            <strong><?=$this->session->userdata('username');?></strong>
            <i class="fa fa-user"></i>
            <b class="caret"></b>
          </a>
          <ul class="dropdown-menu">
            <li>
              <a href="<?=base_url(),'site_security/close'?>">Salir</a>
            </li>
          </ul>
        </li>
      </ul>
      <?php endif; ?>
    </div>
    <div id="wrapper">
      <!-- Sidebar -->
      <section id="sidebar">
        <i class="fa fa-align-justify fa fa-large"></i>
        <?php if((bool)$this->session->userdata('is_logged_in')): ?>
        <ul id="dock">
          <li class="<?php if($this->uri->segment(1) == 'store_items'): ?>active<?php endif; ?> launcher">
            <i class="fa fa-shopping-basket"></i>
            <a href="<?=base_url(),'store_items'?>">Catálogo</a>
          </li>
          <li class="<?php if($this->uri->segment(1) == 'store_categories'): ?>active<?php endif; ?> launcher">
            <i class="fa fa-list"></i>
            <a href="<?=base_url(),'store_categories'?>">Categorías</a>
          </li>
          <li class="<?php if($this->uri->segment(1) == 'slider'): ?>active<?php endif; ?> launcher">
            <i class="fa fa-image"></i>
            <a href="<?=base_url(),'slider'?>">Slider</a>
          </li>
          <li class="<?php if($this->uri->segment(1) == 'shipping'): ?>active<?php endif; ?> launcher">
            <i class="fa fa-truck"></i>
            <a href="<?=base_url(),'shipping/manage'?>">Envíos</a>
          </li>
          <?php if($this->session->userdata('permissions')): ?>
            <li class="<?php if($this->uri->segment(1) == 'blog'): ?>active<?php endif; ?> launcher">
            <i class="fa fa-file-text"></i>
            <a href="<?=base_url(),'blog'?>">Blog</a>
          </li>
            <li class="<?php if($this->uri->segment(1) == 'cms'): ?>active<?php endif; ?> launcher">
            <i class="fa fa-text-height"></i>
            <a href="<?=base_url(),'cms'?>">Contenidos</a>
          </li>
            <li class="<?php if($this->uri->segment(1) == 'site_security'): ?>active<?php endif; ?> launcher">
            <i class="fa fa-cogs"></i>
            <a href="<?=base_url(),'site_security/manage'?>">Configuración</a>
          </li>
          <?php endif; ?>
        </ul>
      <?php endif; ?>
      </section>
      <!-- Tools -->
      <section id="tools">
        <ul class="breadcrumb" id="breadcrumb">
          <li class="title">Panel de control</li>
        </ul>
      </section>
      <!-- Content -->
      <div id="content">
        <?php 
        if(isset($view_file) && isset($view_module))
        {
          $this->load->view($view_module.'/'.$view_file);
        }
        ?>
      </div>
    </div>
    <!-- Footer -->

    <script>
    $(document).ready(function() {
        $('#table').DataTable({
                responsive: true,
                "lengthMenu": [ 25, 50, 75, 100 ]
        });
    });
    </script>
    <script type="text/javascript">                         
    
 $(function() {
          $('.summernote').summernote({
              height: 300, 
              onImageUpload: function(files, editor, $editable) {
              sendFile(files[0],editor,$editable);
              }  
            });

           function sendFile(file,editor,welEditable) {
              data = new FormData();
              data.append("file", file);
               $.ajax({
               url: "<?=base_url();?>item_images/editor",
               data: data,
               cache: false,
               contentType: false,
               processData: false,
               type: 'POST',
               success: function(data){  
                editor.insertImage(welEditable, data);              
            },
               error: function(jqXHR, textStatus, errorThrown) {
               console.log(textStatus+" "+errorThrown);
              }
            });
           }
    });
    </script>
  <script>
 $.datepicker.regional['es'] = {
 closeText: 'Cerrar',
 prevText: '<Ant',
 nextText: 'Sig>',
 currentText: 'Hoy',
 monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
 monthNamesShort: ['Ene','Feb','Mar','Abr', 'May','Jun','Jul','Ago','Sep', 'Oct','Nov','Dic'],
 dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
 dayNamesShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
 dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sá'],
 weekHeader: 'Sm',
 dateFormat: 'dd/mm/yy',
 firstDay: 1,
 isRTL: false,
 showMonthAfterYear: false,
 yearSuffix: ''
 };
 $.datepicker.setDefaults($.datepicker.regional['es']);
$(function () {
$("#fecha").datepicker();
$( "#datepicker" ).datepicker( "option", "showAnim", "slideDown");
});
</script>
  </body>
</html>