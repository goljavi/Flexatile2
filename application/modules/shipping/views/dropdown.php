<div class="form-group">
  <label for="sel1">Envío:</label>
  <select class="form-control" name="shipping">
<?php foreach($query->result() as $row){ ?>
<option value="<?=$row->id?>"><?=$row->title?> - $<?=$row->price?></option>
<?php } ?>
  </select>
</div>
