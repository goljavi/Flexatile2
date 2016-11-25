<div id="page-wrapper">
            <div class="row">
                <div class="col-lg-9">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                           <strong>Esta seguro que desea borrar <?= $item_title ?> ?
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-12">
		<?php
		echo form_open($form_location);
		echo form_submit('submit', 'Si, borrar.', "class='btn btn-primary'");
		echo nbs(7);         
		echo form_submit('submit', 'No, cancelar.', "class='btn btn-primary'");
		?>
 </div>
                                <!-- /.col-lg-6 (nested) -->
                            </div>
                            <!-- /.row (nested) -->
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
              <!-- /.col-lg-3 -->
              
            </div>
            <!-- /.row -->
        </div>