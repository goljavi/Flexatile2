<div class='panel panel-default' id="image_gallery">
          <div class='panel-heading'>
            <i class='icon-edit icon-large'></i>
            <?= $headline ?>
          </div>

<div class='panel-body'>
 <div class="col-sm-12">
 <?php if(isset($flash_image_error)){echo $flash_image_error;} ?>
 <?php if(isset($flash_del)){echo $flash_del;} ?>
<ul id="sortlist" class="img-sort">
<?php foreach($query->result() as $row): ?>
  <li id="<?=$row->id?>">
  <div class="item-container">
  <div class="img-container">
    <img class="img-responsive img-rounded img-gallery" src="<?=base_url()?>uploads/slider/<?=$row->thumb;?>"/>
  </div>
  <?php echo form_open('slider/submit'); ?>
  <?php echo form_input('link', $row->link, "class='form-control' placeholder='URL'")?>
  <input type="hidden" name="id" value="<?=$row->id?>">
  <div class="col-sm-6"><button type="submit" class='btn btn-success'><i class='fa fa-floppy-o'></i></button></div>
  <?php echo form_close(); ?>
  <div class="col-sm-6"><a class='btn btn-danger' href='<?php echo base_url(); ?>slider/del/<?php echo $row->id; ?>'><i class='fa fa-trash'></i>
</a></div>
</div>
  </li>  
<?php endforeach; ?>
</ul>
</div>

          <div class="col-sm-12 mtop20">
          <hr>
          <?php echo form_open_multipart($form_location);?>
          <input type="file" name="userfile" size="20" />
          <br>
          <input class="btn btn-primary" type="submit" value="Subir"/>
          <?php echo form_close(); ?>
          </div>
</div>
</div>