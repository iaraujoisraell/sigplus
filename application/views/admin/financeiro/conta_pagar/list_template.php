<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="col-md-12">
   <section class="content-header">
      <h1>
        Títulos a Pagar
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo admin_url(''); ?>"><i class="fa fa-home"></i> Home </a></li>  
        <li><a href="<?php echo admin_url('dashboard/menu_financeiro'); ?>"> Financeiro </a></li>
        <li class="active"><a href="">Títulos a pagar</a></li>
      </ol>
    </section>
   <div class="panel_s mbot10">
      <div class="panel-body _buttons">
         <?php $this->load->view('admin/financeiro/conta_pagar/invoices_top_stats'); ?>
         <?php if(has_permission('invoices','','create')){ ?>
            <a href="<?php echo admin_url('financeiro/add_conta_pagar/' . $aRow['id']); ?>" class="btn btn-info pull-left new new-invoice-list mright5"  ><?php echo 'Add Conta a Pagar'; ?></a>
            
            <a href="<?php echo admin_url('financeiro/list_conta_pagar_parcelas'); ?>" class="btn btn-info pull-left new new-estimate-btn"><?php echo 'Parcelas'; ?></a>
            
         <?php } ?>
         
      
      </div>
       
       <div class="panel-body _buttons">
      <!-- CATEGORIA -->
      <div class="col-md-4">
          <div class="form-group">
               <?php
              $selected = '';
              echo render_select('categorias', $categorias_financeira, array('id', array('name')), 'Categoria', $selected,array('multiple'=>'true','onchange'=>'procedimentos_table_reload()'));
              ?>
            </div>
      </div>
      
     <!-- PLANO DE CONTA -->
      <div class="col-md-4">
          <div class="form-group">
               <?php
              $selected = '';
              echo render_select('plano_conta', $planos_conta, array('id', array('descricao')), 'Plano Contas', $selected,array('multiple'=>'true','onchange'=>'procedimentos_table_reload()'));
              ?>
            </div>
      </div>
      <!-- FORNECEDOR -->
      <div class="col-md-4">
          <div class="form-group">
               <?php
                  $selected = '';
                  echo render_select('fornecedor', $fornecedores, array('id', array('company')), 'Fornecedores', $selected,array('multiple'=>'true','onchange'=>'procedimentos_table_reload()'));
                  ?>
          </div>
      </div>  
      
      <!-- NÚMERO DO DOCUMENTO -->
      <div class="col-md-4">
          <div class="form-group">
               <?php
                  $selected = '';
                  echo render_select('documento', $numero_documentos, array('numero_documento', array('numero_documento')), 'Número Documento', $selected,array('multiple'=>'true','onchange'=>'procedimentos_table_reload()'));
                  ?>
          </div>
      </div>
      
      </div> 
      
   </div>
   <div class="row">
      <div class="col-md-12" id="small-table">
         <div class="panel_s">
            <div class="panel-body">
               <!-- if invoiceid found in url -->
               <?php echo form_hidden('invoiceid',$invoiceid); ?>
               <?php $this->load->view('admin/financeiro/conta_pagar/table_html'); ?>
            </div>
         </div>
      </div>
      <div class="col-md-7 small-table-right-col">
         <div id="invoice" class="hide">
         </div>
      </div>
   </div>
</div>
