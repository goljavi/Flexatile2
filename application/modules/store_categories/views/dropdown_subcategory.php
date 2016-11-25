<?php foreach($query->result() as $row): ?>
	<option 
		<?php if(Modules::run('store_cat_assign/_check_if_already_assigned', $row->id, $item_id)): ?>
			disabled="disabled"
		<?php else: ?>
			value="<?=$row->id?>"
		<?php endif; ?>
		>----<?=$row->title?>
	</option>
<?php endforeach; ?>