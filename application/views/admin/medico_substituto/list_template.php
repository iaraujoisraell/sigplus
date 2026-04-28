<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="col-md-12">
    <section class="content-header">
      <h1>
        Médicos Substitutos
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo admin_url(''); ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active"><a href="<?php echo admin_url('medico_substituto'); ?>">Medicos substitutos</a></li>
      </ol>
    </section>
   <div class="panel_s mbot10">
       <div class="panel-body _buttons">
            <a href="#" class="btn btn-info pull-left new new-invoice-list mright5"  data-toggle="modal" data-target="#add_substituto" data-id=""><?php echo 'Add Médico substituto'; ?></a>
   
      </div>
      
   </div>
   <div class="row">
      <div class="col-md-12" id="small-table">
         <div class="panel_s">
            <div class="panel-body">
               <?php $this->load->view('admin/medico_substituto/table_html'); ?>
            </div>
         </div>
      </div>
      <div class="col-md-7 small-table-right-col">
         <div id="invoice" class="hide">
         </div>
      </div>
   </div>
</div>
