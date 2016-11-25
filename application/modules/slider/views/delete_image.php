<div id="page-wrapper">
  <div class="row">
      <div class="col-lg-9">
          <div class="panel panel-primary">
              <div class="panel-heading">
                 <strong>Esta seguro que desea borrar esta imagen?
              </div>
              <div class="panel-body">
                  <div class="row">
                      <div class="col-lg-12">
                        <?php foreach($query->result() as $row): ?>
                         <p><img src="<?=base_url(),'uploads/slider/',$row->thumb?>"></p>
                        <?php endforeach; ?>
                        <?php
                        echo form_open($form_location);
                        echo form_submit('submit', 'Si, borrar.', "class='btn btn-primary'");
                        echo nbs(7);         
                        echo form_submit('submit', 'No, cancelar.', "class='btn btn-primary'");
                        ?>
                      </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>