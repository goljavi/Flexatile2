<div class='panel panel-default' id="image_gallery">
          <div class='panel-heading'>
            <i class='icon-edit icon-large'></i>
            <?= $headline ?>
          </div>

<div class='panel-body'>
 <div class="col-sm-12">
 <?php if(isset($flash_image_error)){echo $flash_image_error;} ?>
<ul id="sortlist" class="img-sort">
<?php foreach($query->result() as $row): ?>
  <li id="<?=$row->id?>">
  <div class="item-container">
  <div class="img-container">
    <img class="img-responsive img-rounded img-gallery" src="<?php echo base_url(); ?>uploads/images/<?php echo $item_id; ?>/<?php echo $row->thumb; ?>"/>
  </div>
  <a class='btn btn-danger' href='<?php echo base_url(); ?>item_images/del/<?php echo $row->priority; ?>/<?php echo $item_id; ?>'><i class='fa fa-trash'></i>
</a>
</div>
  </li>  
<?php endforeach; ?>
</ul>
</div>
<hr>
          <div class="col-sm-12">
          <?php echo form_open_multipart($form_location);?>
          <input type="file" name="userfile" size="20" />
          <br>
          <input class="btn btn-primary" type="submit" value="Subir"/>
          <?php echo form_close(); ?>
          </div>
</div>
</div>