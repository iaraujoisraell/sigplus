<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="col-md-12">
    <section class="content-header">
      <h1>
        Parcelas
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo admin_url('financeiro'); ?>"><i class="fa fa-dashboard"></i> Títulos a pagar</a></li>
        <li class="active">Parcelas</li>
      </ol>
    </section>
   <div class="panel_s mbot10">
       
      <div class="panel-body _buttons">
         <?php $this->load->view('admin/financeiro/conta_pagar/invoices_top_stats'); ?>
         <?php if(has_permission('invoices','','create')){ /* ?>
            <a href="<?php echo admin_url('financeiro'); ?>" class="btn btn-info pull-left new new-estimate-btn"><?php echo 'Títulos - Conta a Pagar'; ?></a>
         <?php */ } ?>
         <div class="col-md-3">
            <div class="form-group">
               <?php
                  $selected = '';
                  echo render_select('fornecedor', $fornecedores, array('id', array('company')), 'Fornecedores', $selected,array('multiple'=>'true','onchange'=>'procedimentos_table_reload()'));
                  ?>
            </div>
          </div>    
         <div class="col-md-3">
            <div class="form-group">
               <?php
                  $selected = '';
                  echo render_select('categorias', $categorias_financeira, array('id', array('name')), 'categorias', $selected,array('multiple'=>'true','onchange'=>'procedimentos_table_reload()'));
                  ?>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
                <?php
                $hoje = date('Y-m-d');
                ?>
                <label>Vencimento de</label>
                <input type="date" name="data_de" id="data_de" value="<?php echo $hoje; ?>" onchange="procedimentos_table_reload();" class="form-control">
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
                <?php
                $hoje = date('Y-m-d');
                ?>
                <label>Vencimento Até</label>
                <input type="date" name="data_ate" id="data_ate" value="<?php echo $hoje; ?>" onchange="procedimentos_table_reload();" class="form-control">
            </div>
          </div> 
         <div class="display-block text-right">
            <div class="btn-group pull-right mleft4 invoice-view-buttons btn-with-tooltip-group _filter_data" data-toggle="tooltip" data-title="<?php echo _l('filter_by'); ?>">
               <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
               <i class="fa fa-filter" aria-hidden="true"></i>
               </button>
               <ul class="dropdown-menu width300">
                  <li>
                     <a href="#" data-cview="all" onclick="dt_custom_view('','.table-invoices',''); return false;">
                     <?php echo _l('invoices_list_all'); ?>
                     </a>
                  </li>
                  <li class="divider"></li>
                  <li class="<?php if($this->input->get('filter') == 'not_sent'){echo 'active';} ?>">
                     <a href="#" data-cview="not_sent" onclick="dt_custom_view('not_sent','.table-invoices','not_sent'); return false;">
                     <?php echo _l('not_sent_indicator'); ?>
                     </a>
                  </li>
                  <li>
                     <a href="#" data-cview="not_have_payment" onclick="dt_custom_view('not_have_payment','.table-invoices','not_have_payment'); return false;">
                     <?php echo _l('invoices_list_not_have_payment'); ?>
                     </a>
                  </li>
                  <li>
                     <a href="#" data-cview="recurring" onclick="dt_custom_view('recurring','.table-invoices','recurring'); return false;">
                     <?php echo _l('invoices_list_recurring'); ?>
                     </a>
                  </li> 
                  <li class="divider"></li>
                  <?php foreach($invoices_statuses as $status){ ?>
                  <li class="<?php if($status == $this->input->get('status')){echo 'active';} ?>">
                     <a href="#" data-cview="invoices_<?php echo $status; ?>" onclick="dt_custom_view('invoices_<?php echo $status; ?>','.table-invoices','invoices_<?php echo $status; ?>'); return false;"><?php echo format_invoice_status($status,'',false); ?></a>
                  </li>
                  <?php } ?>
                  <?php if(count($invoices_years) > 0){ ?>
                  <li class="divider"></li>
                  <?php foreach($invoices_years as $year){ ?>
                  <li class="active">
                     <a href="#" data-cview="year_<?php echo $year['year']; ?>" onclick="dt_custom_view(<?php echo $year['year']; ?>,'.table-invoices','year_<?php echo $year['year']; ?>'); return false;"><?php echo $year['year']; ?>
                     </a>
                  </li>
                  <?php } ?>
                  <?php } ?>
                  
                  <?php if(count($medicos) > 0){ ?>
                  <div class="clearfix"></div>
                  <li class="divider"></li>
                  <li class="dropdown-submenu pull-left">
                     <a href="#" tabindex="-1"><?php echo 'Médicos'; ?></a>
                     <ul class="dropdown-menu dropdown-menu-left">
                        <?php foreach($medicos as $medico){ ?>
                        <li>
                           <a href="#" data-cview="medicos_<?php echo $medico['medicoid']; ?>" onclick="dt_custom_view(<?php echo $medico['medicoid']; ?>,'.table-invoices','medicos_<?php echo $medico['medicoid']; ?>'); return false;"><?php echo $medico['nome_profissional']; ?>
                           </a>
                        </li>
                        <?php } ?>
                     </ul>
                  </li>
                  <?php } ?>
                  
                  <?php if(count($convenios) > 0){ ?>
                  <div class="clearfix"></div>
                  <li class="divider"></li>
                  <li class="dropdown-submenu pull-left">
                     <a href="#" tabindex="-1"><?php echo 'Convênios'; ?></a>
                     <ul class="dropdown-menu dropdown-menu-left">
                        <?php foreach($convenios as $convenio){ ?>
                        <li>
                           <a href="#" data-cview="convenio_<?php echo $convenio['id']; ?>" onclick="dt_custom_view(<?php echo $convenio['id']; ?>,'.table-invoices','convenio_<?php echo $convenio['id']; ?>'); return false;"><?php echo $convenio['name']; ?>
                           </a>
                        </li>
                        <?php } ?>
                     </ul>
                  </li>
                  <?php } ?>
                  
                  <?php if(count($invoices_sale_agents) > 0){ ?>
                  <div class="clearfix"></div>
                  <li class="divider"></li>
                  <li class="dropdown-submenu pull-left">
                     <a href="#" tabindex="-1"><?php echo _l('sale_agent_string'); ?></a>
                     <ul class="dropdown-menu dropdown-menu-left">
                        <?php foreach($invoices_sale_agents as $agent){ ?>
                        <li>
                           <a href="#" data-cview="sale_agent_<?php echo $agent['sale_agent']; ?>" onclick="dt_custom_view(<?php echo $agent['sale_agent']; ?>,'.table-invoices','sale_agent_<?php echo $agent['sale_agent']; ?>'); return false;"><?php echo $agent['full_name']; ?>
                           </a>
                        </li>
                        <?php } ?>
                     </ul>
                  </li>
                  <?php } ?>  
                  <div class="clearfix"></div>
                  <?php if(count($payment_modes) > 0){ ?>
                  <li class="divider"></li>
                  <?php } ?>
                  <?php foreach($payment_modes as $mode){
                     if(total_rows(db_prefix().'invoicepaymentrecords',array('paymentmode'=>$mode['id'])) == 0){continue;}
                     ?>
                  <li>
                     <a href="#" data-cview="invoice_payments_by_<?php echo $mode['id']; ?>" onclick="dt_custom_view('<?php echo $mode['id']; ?>','.table-invoices','invoice_payments_by_<?php echo $mode['id']; ?>'); return false;">
                     <?php echo _l('invoices_list_made_payment_by',$mode['name']); ?>
                     </a>
                  </li>
                  <?php } ?>
               </ul>
            </div>
            <a href="#" class="btn btn-default btn-with-tooltip toggle-small-view hidden-xs" onclick="toggle_small_view('.table-conta_pagar_parcelas','#invoice'); return false;" data-toggle="tooltip" title="<?php echo _l('invoices_toggle_table_tooltip'); ?>"><i class="fa fa-angle-double-left"></i></a>
            <a href="#" class="btn btn-default btn-with-tooltip invoices-total" onclick="slideToggle('#stats-top'); init_conta_pagar(true); return false;" data-toggle="tooltip" title="<?php echo _l('view_stats_tooltip'); ?>"><i class="fa fa-bar-chart"></i></a>
         </div>
      </div>
   </div>
   <div class="row">
      <div class="col-md-12" id="small-table">
         <div class="panel_s">
            <div class="panel-body">
               <!-- if invoiceid found in url -->
               <?php echo form_hidden('invoiceid',$invoiceid); ?>
               <?php $this->load->view('admin/financeiro/conta_pagar/parcelas/table_html'); ?>
            </div>
         </div>
      </div>
      <div class="col-md-7 small-table-right-col">
         <div id="invoice" class="hide">
         </div>
      </div>
   </div>
</div>
