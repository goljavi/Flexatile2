<h1 class="mtop10"><?= $headline ?></h1>
 <?php if(isset($flash)){echo $flash;} ?>
<div class='panel panel-default grid'>
          <div class='panel-heading mbottom10'>
            <a class='btn btn-info' href='<?= base_url(), 'blog/create/' ?>'>
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
             <?php foreach($query->result() as $row): $date = date("d/m/Y", strtotime($row->date)); ?>
              <tr>
                <td><?=$row->title?></td>
                <td><?=$row->description?></td>
                <td><?=$date?></td>
                <td class='action'>
                  <a class='btn btn-info' href='<?php echo base_url(), 'blog/create/', $row->id ?>'>
                    <i class='fa fa-cogs'></i>
                  </a>
                </td>
              </tr>
             <?php endforeach; ?>
            </tbody>
          </table>
          </div>
        </div>