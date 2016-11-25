<ul class="breadcrumb">
	<?php $i = 0; foreach(array_reverse($list) as $cat): ?>
		<li><a href="<?php echo base_url(); ?>store_categories/index/<?php echo $cat ?>"><?php echo Modules::run('store_categories/_get_category_name', $cat); ?></a></li>
	<?php $i++; endforeach; if($i == 1){echo ' (Categoría de primer nivel)';}?>
</ul>