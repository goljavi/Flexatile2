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
            <?php echo form_textarea('content', $content, "class='form-control summernote' placeholder='Contenidos'")?>
       </div>  
       <div class="col-sm-4">
       <div class="form-group">
            <?php echo form_input('title', $title, "class='form-control' placeholder='Título (se permiten letras, espacios y números)'")?>
       </div>
       </div>
       <div class="col-sm-4">
        <div class="form-group">
             <?php echo form_input('description', $description, "class='form-control' placeholder='Descripción corta'")?>
        </div>
       </div>
       <div class="col-sm-4">
         <div class="form-group">
           <div class="input-group">
            <span class="input-group-addon"><?php echo base_url(); ?>contenido/</span>
            <?php echo form_input('url', $url, "class='form-control' placeholder='URL (Opcional)'")?>
           </div>
       </div> 
       </div>
       <div class="col-sm-4">
        <div class="form-group">
             <?php 
                if(isset($update_id)){$date = date("d/m/Y", strtotime($date));}
                else{$date = Modules::run('timedate/datenow');}
                echo form_input('date', $date, "class='form-control' id='fecha' placeholder='Fecha'")
             ?>
        </div>
       </div>
       <div class="col-sm-8">
        <div class="form-group">
             <?php echo form_input('keywords', $keywords, "class='form-control' placeholder='TAGS'")?>
        </div>
       </div>
       
      <div class="col-sm-6">
         <?php echo form_submit('submit', 'Guardar', "class='btn btn-primary pull-left'")?>
         <?php if(isset($update_id)): ?><a href="<?= base_url() ?>contenido/<?= $url ?>" class="btn btn-info mleft10">Ver Articulo</a><?php endif; ?>
         <a href="<?= base_url(), 'blog' ?>" class="btn btn-default mleft10">Volver</a>

      </div>
      <div class="col-sm-6">
        <?php if(isset($update_id)): ?><a href="<?= base_url() ?>blog/del/<?= $update_id ?>" class="btn btn-danger pull-right">Eliminar Articulo</a><?php endif; ?>
      </div>
       </div>
    <?php echo form_close(); ?>
</div>
<?php if(isset($image_file)){echo Modules::Run('blog/_upload_image', $id, $image_file, $thumbnail);} ?>
