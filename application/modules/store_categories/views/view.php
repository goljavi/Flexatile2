<div class="product-big-title-area" style="background: url(<?=base_url()?>images/crossword.png) repeat scroll 0 0 #5a88ca">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="product-bit-title text-center">
                    <h2><?=$title?></h2>
                </div>
            </div>
        </div>
    </div>
</div>
 <div class="single-product-area">
        <div class="zigzag-bottom"></div>
        <div class="container">
            <div class="row">
                <?=Modules::run('store_items/_draw_products', $id, TRUE, $page)?>
            </div>
            
            <div class="row">
                <div class="col-md-12">
                    <div class="product-pagination text-center">
                        <nav>
                          <?=$this->pagination->create_links()?>
                        </nav>                        
                    </div>
                </div>
            </div>
        </div>
    </div>