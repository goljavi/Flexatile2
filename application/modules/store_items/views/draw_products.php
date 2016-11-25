<?php if($inner_category): ?>
<?php foreach($query->result() as $row): ?>
<div class="col-xs-6 col-sm-4 col-md-3">
<div class="single-product">
    <div class="product-f-image">
    <a href="<?=base_url(),'catalogo/',$row->item_url?>">
        <img src="<?=base_url(),'uploads/images/',$row->item_id,'/',$row->thumb?>" class="product-img" alt="">
    </a>
    </div>
    <h2 class="product-title"><a href="<?=base_url(),'catalogo/',$row->item_url?>"><?=$row->item_title?></a></h2>
    <div class="product-carousel-price">
        <ins>$<?=$row->item_price?></ins>
        <?php if($row->was_price>0): ?>
            <s>$<?=$row->was_price?></s>
        <?php endif; ?>
    </div> 
    <div class="j2store-addtocart-form">
        <div id="add-to-cart-151" class="j2store-add-to-cart">
                <a href="<?=base_url(),'catalogo/',$row->item_url?>" class="j2store-cart-button btn btn-primary"><i class="fa fa-shopping-cart" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;Ver detalle</a>
        </div>
    </div>                           
</div>
</div>
<?php endforeach; ?>
<?php else: ?>
<?php foreach($query->result() as $row): ?>
<div class="single-product">
    <div class="product-f-image">
    <a href="<?=base_url(),'catalogo/',$row->item_url?>">
        <img src="<?=base_url(),'uploads/images/',$row->item_id,'/',$row->thumb?>" class="product-img" alt="">
    </a>
    </div>
    <h2 class="product-title"><a href="<?=base_url(),'catalogo/',$row->item_url?>"><?=$row->item_title?></a></h2>
    <div class="product-carousel-price">
        <ins>$<?=$row->item_price?></ins>
        <?php if($row->was_price>0): ?>
            <s>$<?=$row->was_price?></s>
        <?php endif; ?>
    </div> 
    <div class="j2store-addtocart-form">
        <div id="add-to-cart-151" class="j2store-add-to-cart">
                <a href="<?=base_url(),'catalogo/',$row->item_url?>" class="j2store-cart-button btn btn-primary"><i class="fa fa-shopping-cart" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;Ver detalle</a>
        </div>
    </div>                           
</div>
<?php endforeach; ?>
<?php endif; ?>