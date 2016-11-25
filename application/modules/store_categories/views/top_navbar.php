<?php if(!isset($select)){$select='';} ?>
   <div class="mainmenu-area">
        <div class="container">
            <div class="row">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                </div> 
                <div class="navbar-collapse collapse">
                    <ul class="nav navbar-nav">
                      <li <?php if($select == 'home'): ?>class="active"<?php endif; ?>><a href="<?=base_url()?>">Home</a></li>
                      <?php
                      $this->load->library('user_agent');
                      $this->load->module('store_categories');
                      foreach($query->result() as $row): 
                      $query = $this->store_categories->get_where_custom('parent_id', $row->id);
                      ?>
                      <?php if($query->num_rows()>0): ?>
                        <li class="dropdown <?php if($select == $row->id): ?>active<?php endif; ?>">
                          <a class="dropdown-toggle <?php if($this->agent->is_mobile() != TRUE): ?>disabled<?php endif; ?>" data-toggle="dropdown" href="<?=base_url(),'categoria/',$row->url?>"><?=$row->title?> 
                          <span class="caret"></span></a>
                          <ul class="dropdown-menu">
                            <?php foreach($query->result() as $sub): ?>
                             <li><a href="<?=base_url(),'categoria/',$sub->url?>"><?=$sub->title?></a></li>
                            <?php endforeach; ?>
                          </ul>
                        </li>
                       <?php else: ?>
                        <li <?php if($select == $row->id): ?>class="active"<?php endif; ?>><a href="<?=base_url(),'categoria/',$row->url?>"><?=$row->title?></a></li>
                        <?php endif; ?>
                      <?php endforeach; ?>
                    </ul>
                </div>  
            </div>
        </div>
    </div> <!-- End mainmenu area -->
<!-- /.navbar-collapse -->