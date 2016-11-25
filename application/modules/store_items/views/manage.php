<h1 class="mtop10"><?= $headline ?></h1>
 <?php if(isset($flash)){echo $flash;} ?>
<div class='panel panel-default grid'>
          <div class='panel-heading mbottom10'>
            <a class='btn btn-info' href='<?= base_url(), 'store_items/create/' ?>'>
  <i class='fa fa-plus'></i>
</a>
          </div>
          <div class="mleft10 mright10">
          <table id="table" class='table mtop20'>
            <thead>
              <tr>
                <th></th>
                <th></th>
                <th></th>
                <th class='actions'>
                </th>
              </tr>
            </thead>
            <tbody>
             <?php foreach($query->result() as $row): ?>
              <tr>
                <td><?= $row->item_title ?></td>
                <td><?php if($row->was_price!=0): ?>
                	<s class="muted">$<?= $row->was_price ?></s>
                <?php endif; ?>
                  $<?= $row->item_price ?>
                </td>
                <td><?php if($row->item_active==1){$status_label='success'; $status_desc='Activo';}elseif($row->item_active==0){$status_label='default'; $status_desc='Pausado';} ?>
                <span class="label label-<?= $status_label; ?>"><?= $status_desc ?></span>
                </td>
                <td class='action'>
                  <a class='btn btn-info' href='<?php echo base_url(), 'store_items/create/', $row->id ?>'>
                    <i class='fa fa-cogs'></i>
                  </a>
                </td>
              </tr>
             <?php endforeach; ?>
            </tbody>
          </table>
          </div>
        </div>