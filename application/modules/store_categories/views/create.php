<div id="categories" class='panel panel-default'>
  <div class='panel-heading'>
    <i class='icon-edit icon-large'></i>
    <?= $headline ?>
  </div>
  <div class='panel-body'>
  <?php if($subcategory==0): ?>
  <?= validation_errors("<div class='alert alert-danger'  <strong>Alerta!</strong> ", "</div>") ?>
  <?php if(isset($flash)){echo $flash;} ?>
<?php endif; ?>
   <?php echo form_open($form_location); ?>
       <div class="col-sm-6">
       <div class="form-group">
            <?php echo form_input('title', $title, "class='form-control' placeholder='Título (se permiten letras, espacios y números)'")?>
       </div>
       </div>
       <div class="col-sm-6">
       <div class="form-group">
           <div class="input-group">
            <span class="input-group-addon"><?php echo base_url(); ?>categoria/</span>
            <?php echo form_input('url', $url, "class='form-control' placeholder='URL (Opcional)'")?>
           </div>
       </div>
       </div>
      <div class="col-sm-6">
         <?php echo form_submit('submit', 'Guardar', "class='btn btn-primary pull-left'")?>
         <?php if($update_id!=0 && $subcategory==0): ?>
         <a href="<?= base_url() ?>categoria/<?= $url ?>" class="btn btn-info mleft10">Ver Categoría</a>
         <a href="<?= base_url(), 'store_categories','/index/',$parent_id ?>" class="btn btn-default mleft10">Volver</a>
         <?php endif; ?>

      </div>
      <div class="col-sm-6">
        <?php if($update_id!=0 && $subcategory==0 && $important==0): ?><a href="<?= base_url() ?>store_categories/del/<?= $update_id ?>" class="btn btn-danger pull-right">Eliminar Categoría</a><?php endif; ?>
      </div>
       </div>
    <?php echo form_close(); ?>
</div>