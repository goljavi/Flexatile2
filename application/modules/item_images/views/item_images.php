<?php if($query->num_rows()>1): ?>

<div id="myCarousel" class="carousel slide" data-ride="carousel">
  <!-- Wrapper for slides -->
  <div class="carousel-inner" role="listbox">
  <?php $i=0; foreach($query->result() as $row): $i++;?>
    <div class="item <?php if($i==1): ?>active<?php endif; ?>">
      <img class="img-responsive" src="<?=base_url()?>uploads/images/<?= $row->item_id ?>/<?= $row->image_file ?>">
    </div>
  <?php endforeach; ?>
</div>
  <!-- Left and right controls -->
  <a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
    <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
    <span class="sr-only">Previous</span>
  </a>
  <a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
    <span class="sr-only">Next</span>
  </a>
  </div>
<?php elseif($query->num_rows()==1): ?>
  <?php foreach($query->result() as $row):  ?>
    <img class="img-responsive" src="<?=base_url()?>uploads/images/<?= $row->item_id ?>/<?= $row->image_file ?>">
  <?php endforeach; ?>
<?php endif; ?>