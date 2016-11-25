<h1 class="mtop10"><?= $headline ?></h1>
<div class='panel panel-default grid'>
          <div class='panel-heading mbottom10'>
            <a class='btn btn-info' href='<?= base_url(), 'site_security/create/' ?>'>
  <i class='fa fa-plus'></i>
</a>
          </div>
          <div class="mleft10 mright10">
          <table id="table" class='table mtop20'>
            <thead>
              <tr>
                <th>Usuario</th>
                <th>Permisos</th>
                <th class='actions'>
                </th>
              </tr>
            </thead>
            <tbody>
             <?php foreach($query->result() as $row): ?>
              <tr>
                <td><?= $row->username ?></td>
                <td><?= $row->permissions ?></td>
                <td class='action'>
                  <a class='btn btn-info' href='<?php echo base_url(), 'site_security/create/', $row->id ?>'>
                    <i class='fa fa-cogs'></i>
                  </a>
                </td>
              </tr>
             <?php endforeach; ?>
            </tbody>
          </table>
          </div>
        </div>