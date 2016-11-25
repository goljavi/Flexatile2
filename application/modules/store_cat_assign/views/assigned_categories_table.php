<div class="table-responsive">
<table class="table table-bordered table-hover">
      <thead>
          <tr>                                          
              <th>Categoría</th>
              <th>Acciones</th>
          </tr>
      </thead>
      <tbody>
         <?php foreach($query->result() as $row): ?>
              <tr>
                <td><?php echo Modules::run('store_categories/_category_list', $row->category_id); ?></td>
                <td><a href="<?php echo base_url(); ?>store_cat_assign/del/<?php echo $row->id; ?>/<?php echo $row->item_id; ?>"><span class="glyphicon glyphicon-remove" style="color:#B00000"></span></a></td>
              </tr>     
          <?php endforeach; ?>                                       
    </tbody>
  </table>
</div>