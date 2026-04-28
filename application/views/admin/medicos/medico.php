<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head();?>

<div id="wrapper">
   <div class="content">
      <div class="row">
         <div class="col-md-12">
       
            <?php if(isset($medico) && (!has_permission('customers','','view') && is_customer_admin($medico->medicoid))){?>
            <div class="alert alert-info">
               <?php echo _l('customer_admin_login_as_client_message',get_staff_full_name(get_staff_user_id())); ?>
            </div>
            <?php } ?>
         </div>
      
          
         <?php if(isset($medico)){ ?>
         <div class="col-md-3">
            <div class="panel_s mbot5">
               <div class="panel-body padding-10">
                  <h4 class="bold">
                     #<?php echo $medico->medicoid . ' ' . $title; ?>
                     <?php //if(has_permission('customers','','delete') || is_admin()){ ?>
                     <div class="btn-group">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-right">
                           <?php /* if(is_admin()){  ?>
                           <li>
                              <a href="<?php echo admin_url('clients/login_as_client/'.$medico->medicoid); ?>" target="_blank">
                              <i class="fa fa-share-square-o"></i> <?php echo _l('login_as_client'); ?>
                              </a>
                           </li>
                           <?php } */ ?>
                           <?php if(has_permission('customers','','delete')){ ?>
                           <li>
                              <a href="<?php echo admin_url('clients/delete/'.$medico->medicoid); ?>" class="text-danger delete-text _delete"><i class="fa fa-remove"></i> <?php echo _l('delete'); ?>
                              </a>
                           </li>
                           <?php } ?>
                        </ul>
                     </div>
                     <?php //} ?>
                    
                  </h4>
               </div>
            </div>
            <?php $this->load->view('admin/medicos/tabs'); ?>
         </div>
         <?php } ?>
         <div class="col-md-<?php if(isset($medico)){echo 9;} else {echo 12;} ?>">
            <div class="panel_s">
               <div class="panel-body">
                  <?php if(isset($medico)){ ?>
                  <?php echo form_hidden('isedit'); ?>
                  <?php echo form_hidden('medicoid', $medico->medicoid); ?>
                  <div class="clearfix"></div>
                  <?php } ?>
                  <div>
                     <div class="tab-content">
                        <?php $this->load->view((isset($tab) ? $tab['view'] : 'admin/medicos/groups/profile')); ?>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <?php if($group == 'profile_medico'){ ?>
         <div class="btn-bottom-pusher"></div>
      <?php } ?>
   </div>
</div>
<?php init_tail(); ?>

<?php //$this->load->view('admin/medicos/client_js'); ?>
</body>
</html>
