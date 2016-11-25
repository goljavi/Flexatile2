<div class="col-md-8">
<div class='panel panel-default'>
  <div class='panel-heading'>
    <i class='fa fa-edit'></i>
    <?= $headline ?>
    <div class="pull-right">
    <?php if(isset($update_id)): ?><p><strong>Creado por:</strong> <?=$createdby?> - <?=$date_created?> / <strong>Editado por:</strong> <?=$editby?> - <?=$date_edit?></p><?php endif; ?>
    </div>
  </div>
  <div class='panel-body'>
  <?= validation_errors("<div class='alert alert-danger'  <strong>Alerta!</strong> ", "</div>") ?>
  <?php if(isset($flash)){echo $flash;} ?>
   <?php echo form_open($form_location); ?>
       <div class="col-sm-6">
       <div class="form-group">
            <?php echo form_input('item_title', $item_title, "class='form-control' placeholder='Título (se permiten letras, espacios y números)'")?>
       </div>
       </div>
       <div class="col-sm-6">
         <div class="form-group">
           <div class="input-group">
            <span class="input-group-addon"><?php echo base_url(); ?>catalogo/</span>
            <?php echo form_input('item_url', $item_url, "class='form-control' placeholder='URL (Opcional)'")?>
           </div>
       </div> 
       </div>
       <div class="form-group">
            <?php echo form_textarea('item_description', $item_description, "class='form-control' placeholder='Descripción' id='editable'")?>
       </div>  
         <div class="col-sm-4">
       <div class="form-group">
            <div class="input-group">
            <span class="input-group-addon">Precio: $</span>
           <?php echo form_input('item_price', $item_price, "class='form-control'")?>
             </div>
        </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group">
            <div class="input-group">
            <span class="input-group-addon">Antes $</span>
           <?php echo form_input('was_price', $was_price, "class='form-control' placeholder='En caso de oferta (opcional)'")?>
             </div>
       </div>
       </div>
       <div class="col-sm-4">
       <div class="form-group">
        <?php 
        $options = array(
          '1'         => 'Estatus: Activo',
          '0'           => 'Estatus: Pausado'
        );
        echo form_dropdown('item_active', $options, $item_active, 'class="form-control"'); 
        ?>
      </div> 
      </div>
      <div class="col-sm-6">
         <?php echo form_submit('submit', 'Guardar', "class='btn btn-primary pull-left'")?>
         <?php if(isset($update_id)): ?><a href="<?= base_url() ?>catalogo/<?= $item_url ?>" class="btn btn-info mleft10">Ver Producto</a><?php endif; ?>
         <a href="<?= base_url(), 'store_items' ?>" class="btn btn-default mleft10">Volver</a>

      </div>
      <div class="col-sm-6">
        <?php if(isset($update_id)): ?><a href="<?= base_url() ?>store_items/del/<?= $update_id ?>" class="btn btn-danger pull-right">Eliminar Producto</a><?php endif; ?>
      </div>
       </div>
    <?php echo form_close(); ?>
</div>
<?php if(isset($update_id)){echo Modules::Run('item_images/upload_image', $update_id);} ?>
</div>
<div class="col-md-4">
<?php
if(isset($update_id)){
  $this->load->module('store_cat_assign');
  $this->store_cat_assign->assign($update_id);
}
?>
</div>