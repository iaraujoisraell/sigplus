<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="col-md-12">
    <section class="content-header">
      <h1>
        Títulos a Receber
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo admin_url(''); ?>"><i class="fa fa-home"></i> Home </a></li>  
        <li><a href="<?php echo admin_url('dashboard/menu_financeiro'); ?>"> Financeiro </a></li>
        <li class="active">Títulos a Receber</li>
      </ol>
    </section>
   <div class="panel_s mbot10">
      <div class="panel-body _buttons">
         <?php $this->load->view('admin/financeiro/contas_receber/invoices_top_stats'); ?>
         <?php if(has_permission('invoices','','create')){ ?>
            <a href="<?php echo admin_url('financeiro_invoices/invoice'); ?>" class="btn btn-info pull-left new new-invoice-list mright5"><?php echo _l('create_new_invoice'); ?></a>
         <?php } ?>
    
         <div class="display-block text-right">
           
            <a href="#" class="btn btn-default btn-with-tooltip toggle-small-view hidden-xs" onclick="toggle_small_view('.table-invoices','#invoice'); return false;" data-toggle="tooltip" title="<?php echo _l('invoices_toggle_table_tooltip'); ?>"><i class="fa fa-angle-double-left"></i></a>
            <a href="#" class="btn btn-default btn-with-tooltip invoices-total" onclick="slideToggle('#stats-top'); init_invoices_total(true); return false;" data-toggle="tooltip" title="<?php echo _l('view_stats_tooltip'); ?>"><i class="fa fa-bar-chart"></i></a>
         </div>
      </div>
       
       <div class="panel-body _buttons">
      <!-- CATEGORIA -->
      <div class="col-md-4">
          <div class="form-group">
                  <label>Competência</label>
                  <select name="competencia" id="competencia" onchange="procedimentos_table_reload()" class="form-control">
                    <option value="<?php echo date('m').'/'.date('Y'); ?>"><?php echo date('m').'/'.date('Y'); ?></option>
                    <option value="">Todos</option>
                    <option value="01/2022">01/2022</option>
                    <option value="02/2022">02/2022</option>
                    <option value="03/2022">03/2022</option>
                    <option value="04/2022">04/2022</option>
                    <option value="05/2022">05/2022</option>
                    <option value="06/2022">06/2022</option>
                    <option value="07/2022">07/2022</option>
                    <option value="08/2022">08/2022</option>
                    <option value="09/2022">09/2022</option>
                    <option value="10/2022">10/2022</option>
                    <option value="11/2022">11/2022</option>
                    <option value="12/2022">12/2022</option>
                  </select>
                  
                </div>
      </div>
      
       <!-- FORNECEDOR -->
      <div class="col-md-4">
          <div class="form-group">
               <?php
                  $selected = '';
                  echo render_select('clientes', $clientes, array('id', array('company')), 'Convênios/ Clientes', $selected,array('multiple'=>'true','onchange'=>'procedimentos_table_reload()'));
                  ?>
          </div>
      </div>  
       
     <!-- NÚMERO DO DOCUMENTO -->
      <div class="col-md-4">
          <div class="form-group">
                  <label>Situação</label>
                  <select name="situacao" id="situacao" onchange="procedimentos_table_reload()" class="form-control">
                    <option value="">Todos</option>
                    <option value="2">Pago</option>
                    <option value="1">Não Pago</option>
                    <option value="3">Parcialmente Pago</option>
                    <option value="4">Vencido</option>
                  </select>
                  
                </div>
      </div>  
      
     <!-- PLANO DE CONTA -->
      <div class="col-md-3">
            <div class="form-group">
                <?php
                $hoje = date('Y-m-d');
                ?>
                <label>Vencimento de</label>
                <input type="date" name="vencimento_de" id="vencimento_de" value="" onkeypress="procedimentos_table_reload();" class="form-control">
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
                <?php
                $hoje = date('Y-m-d');
                ?>
                <label>Vencimento Até</label>
                <input type="date" name="vencimento_ate" id="vencimento_ate" value="" onkeypress="procedimentos_table_reload();" class="form-control">
            </div>
          </div> 
     
          <div class="col-md-3">
            <div class="form-group">
                <?php
                $hoje = date('Y-m-d');
                ?>
                <label>Envio de</label>
                <input type="date" name="envio_de" id="envio_de" value="" onkeypress="procedimentos_table_reload();" class="form-control">
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
                <?php
                $hoje = date('Y-m-d');
                ?>
                <label>Envio Até</label>
                <input type="date" name="envio_ate" id="envio_ate" value="" onkeypress="procedimentos_table_reload();" class="form-control">
            </div>
          </div> 
     
      
      <div class="col-md-12">
          <div class="modal-footer">
                 
                   <a href="#" class="btn btn-info" onclick="procedimentos_table_reload(); return false;"><?php echo 'Buscar'; ?></a>
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
               <?php $this->load->view('admin/financeiro/contas_receber/table_html'); ?>
            </div>
         </div>
      </div>
      <div class="col-md-7 small-table-right-col">
         <div id="invoice" class="hide">
         </div>
      </div>
   </div>
</div>
