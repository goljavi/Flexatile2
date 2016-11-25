 <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Secciones de envío</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-9">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <strong><?php echo $headline, $title;?></strong><strong class="pull-right"><?php if (isset($id)) {echo "ID: ", $id;} ?></strong>
                        </div>
                        <?php
                         if (isset($flash_create)){
                                      echo $flash_create;
                                    }
                        ?>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-12">

                                  <?php 
                                  echo validation_errors("<div class='alert alert-danger'>
                                    <strong>Alerta!</strong> ", "</div>")
                                  ?>
                                 <?php echo form_open($form_location); ?>

                                    <!--<form method="post" action="" role="form" enctype="multipart/form-data">-->
                                        <div class="form-group">
                                            <label>Título:</label>
                                             <?php echo form_input('title', $title, "class='form-control'")?>
                                        </div>
                                        <div class="form-group">
                                            <label>Precio:</label>
                                             <?php echo form_input('price', $price, "class='form-control'")?>
                                        </div> 
                                        <br/> 
                                          <?php echo form_submit('submit', 'Guardar', "class='btn btn-primary'")?>
                                        </div>
                                        <div class="col-sm-11">
                                        </div>
                                        <div class="col-sm-1">
                                        <a href="<?php echo base_url() ?>shipping/manage" class="btn btn-default">Volver</a>
                                      </div>
                                    <?php echo form_close(); ?>


                                        
                                </div>
                                <!-- /.col-lg-6 (nested) -->
                            </div>
                            <!-- /.row (nested) -->
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-9 -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /#page-wrapper -->