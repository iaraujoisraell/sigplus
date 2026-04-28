<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<script>
function mascara(o,f){
    v_obj=o
    v_fun=f
    setTimeout("execmascara()",1)
}
function execmascara(){
    v_obj.value=v_fun(v_obj.value)
}
function mtel(v){
    v=v.replace(/\D/g,"");             //Remove tudo o que não é dígito
    v=v.replace(/^(\d{2})(\d)/g,"($1) $2"); //Coloca parênteses em volta dos dois primeiros dígitos
    v=v.replace(/(\d)(\d{4})$/,"$1-$2");    //Coloca hífen entre o quarto e o quinto dígitos
    return v;
}


 $(document).ready(function () {
       // $("#monthly-invoice-table").trigger("click");
       // loadInvoicesTable("#monthly-invoice-table", "monthly");
       <?php if ($invoice->do_not_redirect && $invoice->status != 2){ ?>
        record_payment(<?php echo $invoice->id; ?>); return false;
       <?php } ?>
    });
</script>
<script> 
function Mudarestado(el) {
    var display = document.getElementById(el).style.display;
    if(display == "none")
        document.getElementById(el).style.display = 'block';
    else
        document.getElementById(el).style.display = 'none';
}

 function moeda(a, e, r, t) {
    let n = ""
      , h = j = 0
      , u = tamanho2 = 0
      , l = ajd2 = ""
      , o = window.Event ? t.which : t.keyCode;
    if (13 == o || 8 == o)
        return !0;
    if (n = String.fromCharCode(o),
    -1 == "0123456789".indexOf(n))
        return !1;
    for (u = a.value.length,
    h = 0; h < u && ("0" == a.value.charAt(h) || a.value.charAt(h) == r); h++)
        ;
    for (l = ""; h < u; h++)
        -1 != "0123456789".indexOf(a.value.charAt(h)) && (l += a.value.charAt(h));
    if (l += n,
    0 == (u = l.length) && (a.value = ""),
    1 == u && (a.value = "0" + r + "0" + l),
    2 == u && (a.value = "0" + r + l),
    u > 2) {
        for (ajd2 = "",
        j = 0,
        h = u - 3; h >= 0; h--)
            3 == j && (ajd2 += e,
            j = 0),
            ajd2 += l.charAt(h),
            j++;
        for (a.value = "",
        tamanho2 = ajd2.length,
        h = tamanho2 - 1; h >= 0; h--)
            a.value += ajd2.charAt(h);
        a.value += r + l.substr(u - 2, u)
    }
    return !1
}
</script>
    


<?php echo form_hidden('_attachment_sale_id',$invoice->id); ?>
<?php echo form_hidden('_attachment_sale_type','invoice'); ?>
<?php


?>
<div class="col-md-12 no-padding">
   <div class="panel_s">
      <div class="panel-body">
        
         <div class="horizontal-scrollable-tabs preview-tabs-top">
            <div class="scroller arrow-left"><i class="fa fa-angle-left"></i></div>
            <div class="scroller arrow-right"><i class="fa fa-angle-right"></i></div>
            <div class="horizontal-tabs">
               <ul class="nav nav-tabs nav-tabs-horizontal mbot15" role="tablist">
                  <li role="presentation" class="active">
                     <a href="#tab_parcela" aria-controls="tab_parcela" role="tab" data-toggle="tab">
                     <?php echo 'Parcelas'; ?>
                     </a>
                  </li>
                 
                  <li role="presentation" class="tab-separator">
                     <a href="#tab_notes" onclick="get_sales_notes(<?php echo $invoice->id; ?>,'invoices'); return false" aria-controls="tab_notes" role="tab" data-toggle="tab">
                     <?php echo _l('estimate_notes'); ?> <span class="notes-total">
                     <?php if($totalNotes > 0){ ?>
                     <span class="badge"><?php echo $totalNotes; ?></span>
                     <?php } ?>
                     </span>
                     </a>
                  </li>
                  
                 
                  <li role="presentation" data-toggle="tooltip" data-title="<?php echo _l('toggle_full_view'); ?>" class="tab-separator toggle_view">
                     <a href="#" onclick="small_table_full_view(); return false;">
                     <i class="fa fa-expand"></i></a>
                  </li>
               </ul>
            </div>
         </div>
         <div class="row mtop10">
            <div class="col-md-3">
               <?php
               
               if($invoice->status == 0){
                   $status = 'TÍTULO ABERTO';
                   $label_class = 'warning';
                   echo '<span class="label label-' . $label_class . ' ' . $classes . ' s-status invoice-status-' . $id . '">' . $status . '</span>';
               }else if($invoice->status == 1){
                   $status = 'TÍTULO FECHADO ';
                   $label_class = 'success';
                   echo '<span class="label label-' . $label_class . ' ' . $classes . ' s-status invoice-status-' . $id . '">' . $status . '</span>';
               }
               
               ?>
            </div>
            <div class="col-md-9 _buttons">
               <div class="visible-xs">
                  <div class="mtop10"></div>
               </div>
               <div class="pull-right">
                   <!-- Editar cadastro Cliente -->
                  <?php
                     $_tooltip = _l('invoice_sent_to_email_tooltip');
                     $_tooltip_already_send = '';
                     if($invoice->sent == 1 && is_date($invoice->datesend)){
                      $_tooltip_already_send = _l('invoice_already_send_to_client_tooltip',time_ago($invoice->datesend));
                     }
                     ?>
                  <?php
                  /*
                  
                  if($invoice->status != 2){
                      
                  if(has_permission('invoices','','edit')){ ?>
                  <a href="<?php echo admin_url('invoices/invoice/'.$invoice->id); ?>" data-toggle="tooltip" title="<?php echo _l('edit_invoice_tooltip'); ?>" class="btn btn-default btn-with-tooltip" data-placement="bottom"><i class="fa fa-pencil-square-o"></i></a>
                  <?php }
                  }else if(is_admin()){
                   ?>
                     <a href="<?php echo admin_url('invoices/invoice/'.$invoice->id); ?>" data-toggle="tooltip" title="<?php echo _l('edit_invoice_tooltip'); ?>" class="btn btn-default btn-with-tooltip" data-placement="bottom"><i class="fa fa-pencil-square-o"></i></a>
                 
                  <?php
                  }
                   * 
                   
                  ?>
                  <div class="btn-group">
                     <a href="#" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-file-pdf-o"></i><?php if(is_mobile()){echo ' PDF';} ?> <span class="caret"></span></a>
                     <ul class="dropdown-menu dropdown-menu-right">
                        <li class="hidden-xs"><a href="<?php echo admin_url('invoices/pdf/'.$invoice->id.'?output_type=I'); ?>"><?php echo _l('view_pdf'); ?></a></li>
                        <li class="hidden-xs"><a href="<?php echo admin_url('invoices/pdf/'.$invoice->id.'?output_type=I'); ?>" target="_blank"><?php echo _l('view_pdf_in_new_window'); ?></a></li>
                        <li><a href="<?php echo admin_url('invoices/pdf/'.$invoice->id); ?>"><?php echo _l('download'); ?></a></li>
                        <li>
                           <a href="<?php echo admin_url('invoices/pdf/'.$invoice->id.'?print=true'); ?>" target="_blank">
                           <?php echo _l('print'); ?>
                           </a>
                        </li>
                     </ul>
                  </div>
                
                  <!-- Single button -->
                  <div class="btn-group">
                     <button type="button" class="btn btn-default pull-left dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                     <?php echo _l('more'); ?> <span class="caret"></span>
                     </button>
                     <ul class="dropdown-menu dropdown-menu-right">
                        <li><a href="<?php echo site_url('invoice/' . $invoice->id . '/' .  $invoice->hash) ?>" target="_blank"><?php echo _l('view_invoice_as_customer_tooltip'); ?></a></li>
                        <li>
                           <?php hooks()->do_action('after_invoice_view_as_client_link', $invoice); ?>
                           <?php if(($invoice->status == Invoices_model::STATUS_OVERDUE
                              || ($invoice->status == Invoices_model::STATUS_PARTIALLY && !empty($invoice->duedate) && $invoice->duedate && date('Y-m-d') > date('Y-m-d',strtotime(to_sql_date($invoice->duedate)))))
                              && is_invoices_overdue_reminders_enabled()){ ?>
                           <a href="<?php echo admin_url('invoices/send_overdue_notice/'.$invoice->id); ?>"><?php echo _l('send_overdue_notice_tooltip'); ?></a>
                           <?php } ?>
                        </li>
                        <?php if($invoice->status != Invoices_model::STATUS_CANCELLED
                           && has_permission('credit_notes','','create')
                           && !empty($invoice->clientid)) {?>
                            <?php //if(Invoices_model::STATUS_PAID != 2){ ?>
                        <li>
                           <a href="<?php echo admin_url('credit_notes/credit_note_from_invoice/'.$invoice->id); ?>" id="invoice_create_credit_note" data-status="<?php echo $invoice->status; ?>"><?php echo _l('create_credit_note'); ?></a>
                        </li>
                        <li>
                           <a href="<?php echo admin_url('invoices/criarAgendamento/'.$invoice->id); ?>" target="_blank" ><?php echo 'Criar agendamento'; ?></a>
                        </li>
                            <?php //} ?>
                        <?php } ?>
                        <li>
                           <a href="#" data-toggle="modal" data-target="#sales_attach_file"><?php echo _l('invoice_attach_file'); ?></a>
                        </li>
                        <?php if(has_permission('invoices','','create')){ ?>
                        <li>
                           <a href="<?php echo admin_url('invoices/copy/'.$invoice->id); ?>"><?php echo _l('invoice_copy'); ?></a>
                        </li>
                        <?php } ?>
                        <?php if($invoice->sent == 0){ ?>
                        <li>
                           <a href="<?php echo admin_url('invoices/mark_as_sent/'.$invoice->id); ?>"><?php echo _l('invoice_mark_as_sent'); ?></a>
                        </li>
                        <?php } ?>
                        <?php if(has_permission('invoices','','edit') || has_permission('invoices','','create')){ ?>
                        <li>
                           <?php  if($invoice->status != Invoices_model::STATUS_CANCELLED
                              && $invoice->status != Invoices_model::STATUS_PAID
                              && $invoice->status != Invoices_model::STATUS_PARTIALLY){ ?>
                           <a href="<?php echo admin_url('invoices/mark_as_cancelled/'.$invoice->id); ?>"><?php echo _l('invoice_mark_as',_l('invoice_status_cancelled')); ?></a>
                           <?php } else if($invoice->status == Invoices_model::STATUS_CANCELLED) { ?>
                           <a href="<?php echo admin_url('invoices/unmark_as_cancelled/'.$invoice->id); ?>"><?php echo _l('invoice_unmark_as',_l('invoice_status_cancelled')); ?></a>
                           <?php } ?>
                        </li>
                        <?php } ?>
                        <?php if(!in_array($invoice->status, array(Invoices_model::STATUS_PAID, Invoices_model::STATUS_CANCELLED, Invoices_model::STATUS_DRAFT))
                           && has_permission('invoices','','edit')
                           && $invoice->duedate
                           && is_invoices_overdue_reminders_enabled()){ ?>
                        <li>
                           <?php if($invoice->cancel_overdue_reminders == 1) { ?>
                           <a href="<?php echo admin_url('invoices/resume_overdue_reminders/'.$invoice->id); ?>"><?php echo _l('resume_overdue_reminders'); ?></a>
                           <?php } else { ?>
                           <a href="<?php echo admin_url('invoices/pause_overdue_reminders/'.$invoice->id); ?>"><?php echo _l('pause_overdue_reminders'); ?></a>
                           <?php } ?>
                        </li>
                        <?php } ?>
                        <?php
                           if((get_option('delete_only_on_last_invoice') == 1 && is_last_invoice($invoice->id)) || (get_option('delete_only_on_last_invoice') == 0)){ ?>
                        <?php if(has_permission('invoices','','delete')){ ?>
                        <li data-toggle="tooltip" data-title="<?php echo _l('delete_invoice_tooltip'); ?>">
                           <a href="<?php echo admin_url('invoices/delete/'.$invoice->id); ?>" class="text-danger delete-text _delete"><?php echo _l('delete_invoice'); ?></a>
                        </li>
                        <?php } ?>
                        <?php } ?>
                     </ul>
                  </div>
              <?php */ ?> 
               </div>
            </div>
         </div>
         <div class="clearfix"></div>
         <hr class="hr-panel-heading" />
         <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="tab_parcela">
               
                <div class="table-responsive">
                    <table class="table dt-table scroll-responsive" id="table-parcelas-titulo" data-order-col="0" data-order-type="desc">
                     <thead>
                        <th><span class="bold"><?php echo 'Parcela'; ?> #</span></th>
                        <th><span class="bold"><?php echo 'Vencimento'; ?></span></th>
                        <th><span class="bold"><?php echo 'Valor'; ?></span></th>
                        <th><span class="bold"><?php echo 'Banco'; ?></span></th>
                        <th><span class="bold"><?php echo 'Dt Pgto'; ?></span></th>
                        <th><span class="bold"><?php echo 'Forma Pgto'; ?></span></th>
                        <th><span class="bold"><?php echo 'Status'; ?></span></th>
                        <th><span class="bold"><?php echo 'Opções'; ?></span></th>
                     </thead>
                     <tbody>
                        <?php foreach($parcelas as $parc) { ?>
                        <tr>
                           <td>
                              <?php echo $parc['parcela']; ?>
                           </td>
                           <td><?php echo _d($parc['data_vencimento']); ?></td>
                           <td><?php echo app_format_money($parc['valor_parcela'], 'R$') ?></td>
                           <td><?php echo _d($parc['data_pagamento']); ?></td>
                           <td><?php echo $parc['modo_pagamento']; ?></td>
                           <td><?php if($parc['banco']){ echo $parc['banco'].' ['.$parc['numero_conta'].']'; } ?></td>
                           <td>
                              <?php
                              $status = $parc['status'];
                              if($status == 0){
                                  $status_t = "<label class='label label-warning'>Aberto</label>";
                              }else if($status == 1){
                                  $status_t = "<label class='label label-success'>Pago</label>";
                              }
                              echo $status_t; ?>
                           </td>
                           <td>
                               
                              <?php
                               
                                   
                                 
                              if(has_permission('credit_notes','','delete')){ ?>
                               <a  class="btn btn-warning  new new-invoice-list mright5"  data-toggle="modal" data-target="#add_parcela_modal" data-id="<?php echo $parc['id']; ?>"><i class="fa fa-edit"></i>  </a> 
                             
                              
                              <?php } 
                              
                              
                              if($status == 0){
                              if(has_permission('credit_notes','','delete')){
                                  ?>
                               <a href="<?php echo admin_url('financeiro/delete_conta_pagar_parcela/'.$parc['id']); ?>" class=" btn btn-danger _delete"><i class="fa fa-trash"></i> </a>
                              <?php 
                              }  
                              ?>
                              <?php
                             
                              if(has_permission('credit_notes','','delete')){ ?>
                              <a href="#" class="btn btn-success pull-left new new-invoice-list mright5"  data-toggle="modal" data-target="#add_pagamento_modal" data-id="<?php echo $parc['id']; ?>"><i class="fa fa-money"></i> <?php echo 'Pagar'; ?></a> 
                              <?php } 
                              
                              
                              } 
                              ?>
                           </td>
                        </tr>
                        <?php } ?>
                     </tbody>
                  </table>
               </div>
            </div>
             
            <div role="tabpanel" class="tab-pane" id="tab_notes">
               <?php echo form_open(admin_url('invoices/add_note/'.$invoice->id),array('id'=>'sales-notes','class'=>'invoice-notes-form')); ?>
               <?php echo render_textarea('description'); ?>
               <div class="text-right">
                  <button type="submit" class="btn btn-info mtop15 mbot15"><?php echo _l('estimate_add_note'); ?></button>
               </div>
               <?php echo form_close(); ?>
               <hr />
               <div class="panel_s mtop20 no-shadow" id="sales_notes_area"></div>
            </div>
            
            
         </div>
      </div>
   </div>
</div>
<?php $this->load->view('admin/financeiro/conta_pagar/novo_pagamento'); ?>
<?php $this->load->view('admin/financeiro/conta_pagar/parcelas/modal_parcela'); ?>
<?php init_tail(); ?>
<script>
   init_items_sortable(true);
   init_btn_with_tooltips();
   init_datepicker();
   init_selectpicker();
   init_form_reminder();
   init_tabs_scrollable();
   <?php if($record_payment) { ?>
      record_payment(<?php echo $invoice->id; ?>);
   <?php } else if($send_later) { ?>
      schedule_invoice_send(<?php echo $invoice->id; ?>);
   <?php } ?>
</script>
