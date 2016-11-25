        <div class="row">
            <div class="col-md-12">
                <div class="thumbnail">
                    <?php echo Modules::Run('item_images/get_item_images', $id); ?>
                    <div class="caption-full">
                        <h4 class="pull-right"><?php if($was_price>0): ?><s>$<?=$was_price?></s><?php endif; ?> $<?=$item_price?></h4>
                        <h4><?=$item_title?></h4>
                        <p><?=$item_description?></p>
                    </div>
                </div>
            </div>

        </div>