


<div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Secciones de envío</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <a href="<?php echo base_url(); ?>shipping/create" class="btn btn-primary btn-circle"><span class="glyphicon glyphicon-plus"></span></a>
            <br/>
            <br/>
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-primary">
                        <div id="shipping" class="panel-heading">
                            Todas las secciones de envío
                        </div>
                        <!-- /.panel-heading -->

<?php
if (isset($flash_delete)){
  echo $flash_delete;
}
if (isset($flash_create)){
  echo $flash_create;
}

echo Modules::run('shipping/_display_table');
?>
                          </div>
                          <!-- /.panel panel-default -->
                         </div>
                         <!-- /.col-lg-12 -->
                        </div>
                        <!-- /.row -->
                       </div>     
                       <!-- /#page-wrapper -->    