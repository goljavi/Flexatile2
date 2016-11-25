
     <div class="row text-center">
   <button type="button" class="btn btn-default" data-toggle="modal" data-target="#loginModal">Login</button>
 </div>
<!--login modal-->
<div id="loginModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog">
  <div class="modal-content">
    <div class="modal-header">
        <div class="row text-center">
                                   <h2>Panel de admin</h2>
                                        </div>
    </div>
      <div class="modal-body row">
        <?php
        echo validation_errors("<div class='alert alert-danger'>
        <strong>Alerta!</strong> ", "</div>");
        if(isset($flash_error)){
          echo $flash_error;
        }
        echo form_open('site_security/validate_credentials', 'class="form col-md-12 center-block"'); 
        ?>   
            <div class="form-group">
              <?php echo form_input('username', '', 'class="form-control input-lg" placeholder="Usuario"') ?>
            </div>
            <div class="form-group">
              <?php echo form_password('password', '', 'class="form-control input-lg" placeholder="Contraseña"') ?>
            </div>
            <div class="form-group">
              <?php echo form_submit('submit', 'Entrar', 'class="btn btn-primary btn-lg btn-block"') ?>
            </div>
          <?php echo form_close(); ?>
      </div>
      <div class="modal-footer">
      </div>
  </div>
  </div>
</div>
<script>
$("#loginModal").modal("show");
</script>
