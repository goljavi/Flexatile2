<div class="carousel-holder">

        <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner">
              <?php $i=0; foreach($query->result() as $row): $i++; ?>
                <div class="item <?php if($i==1): ?>active<?php endif; ?>">
                    <a href="<?=$row->link?>">
                        <img class="slide-image fullwidth" src="<?=base_url(),'uploads/slider/',$row->image_file?>" class="" alt="">
                    </a>
                </div>
              <?php endforeach; ?>  
            </div>
            <a class="left carousel-control" href="#carousel-example-generic" data-slide="prev">
                <span class="glyphicon glyphicon-chevron-left"></span>
            </a>
            <a class="right carousel-control" href="#carousel-example-generic" data-slide="next">
                <span class="glyphicon glyphicon-chevron-right"></span>
            </a>
</div>
</div>