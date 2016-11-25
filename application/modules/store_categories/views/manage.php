<?php if(isset($update_id) && $update_id!=0): ?> 
<h1 class="mtop10">Categoría: <?php echo Modules::Run('store_categories/_get_category_name', $update_id); ?></h1>
<?php else: ?>
  <h1 class="mtop10">Categorías</h1>
<?php endif; ?>

<?php 
$this->load->module('store_categories');
if($this->store_categories->_is_new_category_allowed($update_id)):
?>
<div class='panel panel-default grid'>
          <div class='panel-heading mbottom10'>
          <?php if(isset($update_id) && $update_id!=0): ?> 
            Subcategorías
          <?php else: ?>
            Categorías Principales
          <?php endif; ?>
          </div>
          <div class="mleft10 mright10">
          <ul id="sortlist" class="list-group">
             <?php
             foreach($query->result() as $row): 
             $subcategory_number = $this->store_categories->_count_subcategories($row->id); 
             ?>
                <li id="<?=$row->id?>" class="list-group-item"><i class="fa fa-sort" aria-hidden="true"></i> <?= $row->title ?> <span class="badge"><a href='<?php echo base_url(), 'store_categories/index/', $row->id ?>'><i class='fa fa-cogs'></i></a></span><span class="badge"><i class='fa fa-list'></i> <?=$subcategory_number?></span>
                  </li>
             <?php endforeach; ?>
             </ul>
          </div>
        </div>
  <?php if(isset($update_id) && $update_id!=0): ?> 
    <?php $this->load->module('store_categories'); $this->store_categories->create($update_id, 1); ?>
<?php endif; endif;?>
<?php $this->load->module('store_categories'); $this->store_categories->create($update_id); ?>