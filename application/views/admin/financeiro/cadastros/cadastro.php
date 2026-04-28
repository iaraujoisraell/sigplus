<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head_menu_admin(false); ?>

<style>
 body {
 font-family:'Open Sans';
 background:#f1f1f1;
 }
 h3 {
 margin-top: 7px;
 font-size: 16px;
 }

 .install-row.install-steps {
 margin-bottom:15px;
 box-shadow: 0px 0px 1px #d6d6d6;
 }

 .control-label {
 font-size:13px;
 font-weight:600;
 }
 .padding-10 {
 padding:10px;
 }
 .mbot15 {
 margin-bottom:15px;
 }
 .bg-default {
 background: #03a9f4;
 border:1px solid #03a9f4;
 color:#fff;
 }
 .bg-success {
 border: 1px solid #dff0d8;
 }
 .bg-not-passed {
 border:1px solid #f1f1f1;
 border-radius:2px;
 }
 .bg-not-passed {
 border-right:0px;
 }
 .bg-not-passed.finish {
 border-right:1px solid #f1f1f1 !important;
 }
 .bg-not-passed h5 {
 font-weight:normal;
 color:#6b6b6b;
 }
 .form-control {
 box-shadow:none;
 }
 .bold {
 font-weight:600;
 }
 .col-xs-5ths,
 .col-sm-5ths,
 .col-md-5ths,
 .col-lg-5ths {
 position: relative;
 min-height: 1px;
 padding-right: 15px;
 padding-left: 15px;
 }
 .col-xs-5ths {
 width: 20%;
 float: left;
 }
 b {
 font-weight:600;
 }
 .bootstrap-select .btn-default {
 background: #fff !important;
 border: 1px solid #d6d6d6 !important;
 box-shadow: none;
 color: #494949 !important;
 padding: 6px 12px;
 }
</style>


 <div class="content">
      <div class="row">
         <div class="col-md-12">
             <section class="content-header">
              <h1>
                Cadastros Financeiros
              </h1>
              <ol class="breadcrumb">
                <li><a href="<?php echo admin_url(''); ?>"><i class="fa fa-home"></i> Home </a></li>  
                <li><a href="<?php echo admin_url('dashboard/menu_financeiro'); ?>"> Financeiro </a></li>
                <li class="active">Cadastros Financeiros</li>
              </ol>
            </section>
            <?php if(isset($medico) && (!has_permission('customers','','view') && is_customer_admin($medico->medicoid))){?>
            <div class="alert alert-info">
               <?php echo _l('customer_admin_login_as_client_message',get_staff_full_name(get_staff_user_id())); ?>
            </div>
            <?php } ?>
         </div>
      
         <div class="col-md-3">
            <div class="panel_s mbot5">
               <div class="panel-body padding-10">
                  <h4 class="bold">
                     <?php echo $title; ?>
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
            <?php $this->load->view('admin/financeiro/cadastros/tabs'); ?>
         </div>
         
         <div class="col-md-9">
            <div class="panel_s">
               <div class="panel-body">
                
                  <div>
                     <div class="tab-content">
                        <?php $this->load->view((isset($tab) ? $tab['view'] : 'admin/financeiro/cadastros/groups/plano_conta')); ?>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <?php if($group == 'plano_contas'){ ?>
         <div class="btn-bottom-pusher"></div>
      <?php } ?>
   </div>

