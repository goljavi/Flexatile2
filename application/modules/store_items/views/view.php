<div class="single-product-area">
    <div class="container">
        <div class="col-sm-6">
            <div class="product-images">
                <?php echo Modules::Run('item_images/get_item_images', $id); ?>
            </div>
        </div>
            <div class="col-sm-6 description">
                <div class="product-inner">
                    <h2 class="product-name"><?=$item_title?></h2>
                    <div class="col-md-9">
                        <h2>Descripción:</h2>  
                        <p><?=$item_description?></p>
                    </div>
                    <div class="col-md-3">
                        <div class="product-inner-price">
                             <ins>$<?=$item_price?></ins> <?php if($was_price>0): ?><del>$<?=$was_price?></del><?php endif; ?>
                        </div>
                            <!-- Trigger the modal with a button -->
<button type="button" class="add_to_cart_button" data-toggle="modal" data-target="#shipping">COMPRAR</button>

<div id="shipping" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body">
      <?=form_open($form_location)?>
        <?=Modules::run('shipping/_dropdown')?>
        <?php echo form_input('email', '', "class='form-control' placeholder='Email de contacto'")?>
        <hr>
        <?php echo form_submit('submit', 'Comprar', "class='btn btn-primary'")?>
      <?=form_close()?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>
    </div>

  </div>
</div>
                    </div>
                    </div>
                        <h3>Contactanos</h3>
                        <?=Modules::run('contacto/_load_form', 'Producto: '.$item_title)?>
                </div>
            </div>
        </div>
        </div>