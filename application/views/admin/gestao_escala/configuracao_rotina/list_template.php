<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="col-md-12">
    <section class="content-header">
      <h1>
        Gestão de Escala - Unidades Hospitalares
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo admin_url(''); ?>"><i class="fa fa-dashboard"></i> Gestão de Plantão </a></li>
        <li class="active"><a href="<?php echo admin_url('financeiro'); ?>">Gestão de Escala </a></li>
      </ol>
    </section>
   <div class="panel_s mbot10">
      <div class="panel-body _buttons">
         <?php $this->load->view('admin/financeiro/conta_pagar/invoices_top_stats'); ?>
         <?php if(has_permission('invoices','','create')){ ?>
            <a href="#" class="btn btn-info pull-left new new-invoice-list mright5"  data-toggle="modal" data-target="#add_escala_modal" data-id=""><?php echo 'Add Escala'; ?></a>
         <?php } ?>
         
         
      </div>
   </div>
   <div class="row">
      <div class="col-md-12" id="small-table">
         <div class="panel_s">
            <div class="panel-body">
               <!-- if invoiceid found in url -->
               <?php echo form_hidden('invoiceid',$invoiceid); ?>
               <?php $this->load->view('admin/gestao_escala/configuracao_rotina/table_html'); ?>
            </div>
         </div>
      </div>
      <div class="col-md-7 small-table-right-col">
         <div id="invoice" class="hide">
         </div>
      </div>
   </div>
</div>
