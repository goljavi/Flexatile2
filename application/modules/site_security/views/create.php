<div class="col-md-8">
<div class='panel panel-default'>
  <div class='panel-heading'>
    <i class='fa fa-edit'></i>
    <?= $headline ?>
  </div>
  <div class='panel-body'>
  <?= validation_errors("<div class='alert alert-danger'  <strong>Alerta!</strong> ", "</div>") ?>
  <?php if(isset($flash)){echo $flash;} ?>
   <?php echo form_open($form_location); ?>
       <div class="form-group">
            <?php echo form_input('username', $username, "class='form-control' placeholder='Nombre de usuario'")?>
       </div>
       <div class="form-group">
            <?php echo form_password('password', '', "class='form-control' placeholder='Password'")?>
       </div>
       <div class="form-group">
            <?php echo form_password('password_confirm', '', "class='form-control' placeholder='Confirmar Password'")?>
       </div>
 
      <div class="col-sm-6">
         <?php echo form_submit('submit', 'Guardar', "class='btn btn-primary pull-left'")?>
         <a href="<?= base_url(), 'site_security/manage' ?>" class="btn btn-default mleft10">Volver</a>

      </div>
      <div class="col-sm-6">
        <?php if(isset($update_id) && $permissions!=1): ?><a href="<?= base_url() ?>site_security/del/<?= $id ?>" class="btn btn-danger pull-right">Eliminar Cuenta</a><?php endif; ?>
      </div>
       </div>
    <?php echo form_close(); ?>
</div>