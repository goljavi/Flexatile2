<div class="col-md-12">
    <div class="panel panel-primary">
        <div class="panel-heading" id="image_gallery">
           <strong>Subir imagen</strong>
        </div>
        <div class="panel-body">
          <?php if (isset($flash_image_error)){ echo $flash_image_error;} ?>
          <?php if($image_file != ''): ?>
            <img src="<?php echo base_url(); ?>blog_images/<?php echo $thumb_image; ?>">
            <a href="<?php echo base_url(); ?>blog/del_image/<?php echo $id; ?>" class="btn btn-primary">Borrar Imagen</a>
          <?php endif; ?>
          <?php if($image == ''): ?>
		        <?php  echo form_open_multipart('blog/submit_image/'.$id); ?>
            <input type='file' name='userfile' size='20' id='file' class='btn btn-primary'/> 
		        <br/>
            <input type='submit' name='submit' value='Subir' class='btn btn-primary'/>
		      <?php echo form_close(); endif; ?>
        </div>
    </div>
</div>
  

