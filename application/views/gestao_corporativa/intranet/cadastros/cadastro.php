<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head_menu_admin(false); ?>


<?php $this->load->view('gestao_corporativa/css_background'); ?>

<div class="content">
      <div class="row">
         <div class="col-md-12">
             <section class="content-header">
              <h1>
                Cadastros Intranet
              </h1>
              <ol class="breadcrumb">
                <li><a href="<?= base_url('gestao_corporativa/intranet');?>"><i class="fa fa-home"></i> Home </a></li>  
                <li class="active">Cadastros Intranet</li>
              </ol>
            </section>
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
                     </div>
                     <?php //} ?>
                    
                  </h4>
               </div>
            </div>
            <?php $this->load->view('gestao_corporativa/intranet/cadastros/tabs'); ?>
         </div>
         
         <div class="col-md-9">
            <div class="panel_s">
               <div class="panel-body">
                
                  <div>
                     <div class="tab-content">
                        <?php $this->load->view((isset($tab) ? $tab['view'] : 'gestao_corporativa/intranet/cadastros/groups/no_permission')); ?>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>




