                        <div class="panel-body">
                            <div class="dataTable_wrapper">
                                <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="table">
                                    <thead>
                                        <tr>
                                            <th>*<br/></th>
                                            <th>Titulo<br/></th>
                                            <th>Precio<br/></th>
                                            <th>Acciones<br/></th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                       <?php
                                       $i = 0;
                                       $a = 1;
                                        foreach($query->result() as $row){
                                        ?>
                                            <tr <?php if($moved == $a){echo'class="warning"';} ?>>
                                              <td><?php echo $row->priority; ?></td>
                                              <td><?php if($moved == $a){echo'<b>';} ?><?php echo $row->title; ?><?php if($moved == $a){echo'</b>';} ?></td>
                                              <td>$<?php echo $row->price; ?></td>
                                              <td>
                                              <a href="<?php echo base_url(); ?>shipping/create/<?php echo $row->id; ?>"><i class="fa fa-pencil"></i></a>
                                              &nbsp;
                                              &nbsp;
                                               <a href="<?php echo base_url(); ?>shipping/del/<?php echo $row->id; ?>"><i class="fa fa-trash" style="color:#B00000"></i></a></td>
                                              </td>
                                              <td>
                                                 <?php if($i == 0){ echo '<i class="fa fa-caret-square-o-up" style="color:grey;"></i>'; }else{ echo "<a href='".base_url()."shipping/up/".$row->priority."'><i class='fa fa-caret-square-o-up'></i></a>";}?>
                                                 <?php if($row->priority == $priority_max){ echo '<i class="fa fa-caret-square-o-down" style="color:grey;"></i>';}else{ echo "<a href='".base_url()."shipping/down/".$row->priority."'><i class='fa fa-caret-square-o-down'></i></a>";}?>
                                              </td> 
                                            </tr>     
                                        <?php
                                        $i++; 
                                        $a++;        
                                        }
                                        ?>
                                                                            
                                  </tbody>
                                </table>
                              </div>
                            </div>
                            <!-- /.dataTable_wrapper -->
                           </div>
                           <!-- /.panel-body -->
                            
