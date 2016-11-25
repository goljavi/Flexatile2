<select name="category_id" class="form-control">
	<option selected disabled="disabled" value="0">Elegir una categoría</option>
     <?php foreach($query->result() as $row): ?>
	 <option 
	 	<?php if(Modules::run('store_cat_assign/_check_if_already_assigned', $row->id, $item_id)): ?> 
	 		disabled="disabled"
	 	<?php else: ?>
	 		value="<?=$row->id?>" 
	 	<?php endif; ?>
	 		>//<?=$row->title?>
	 </option>
		<?php echo Modules::run('store_categories/_dropdown_subcategory', $row->id); ?>
      <?php endforeach; ?>
</select>