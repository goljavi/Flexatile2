<div class="panel panel-primary">
  <div class="panel-heading" id="assign">
  Asignar categorías
  </div>
  <?php
  if (isset($flash_edit)){
  echo $flash_edit;
  }
  ?>
  <div class="panel-body"> 
    <div class="row">
      <div class="col-lg-12">
      <?php echo Modules::run('store_cat_assign/_assigned_categories_table', $item_id); ?>  
        <hr>
      <?php echo form_open('store_cat_assign/assign/'.$item_id); ?>  
      <div class="col-lg-12">
      <div class="form-group">
        <?php echo Modules::run('store_categories/_dropdown', $item_id); ?>
      </div>
        <?php echo form_submit('submit', 'Asignar', "class='btn btn-primary'"); ?>
      </div>
      <?php echo form_close(); ?>

     <!-- /.panel panel-default -->
    </div>
    <!-- /.col-lg-12 -->
   </div>
   <!-- /.row -->
  </div>     
  <!-- /#page-wrapper --> 
  </div>