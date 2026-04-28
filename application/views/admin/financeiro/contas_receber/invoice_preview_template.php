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

<div class="col-md-12 no-padding">
   <div class="panel_s">
      <div class="panel-body">
         
         <div class="horizontal-scrollable-tabs preview-tabs-top">
            <div class="scroller arrow-left"><i class="fa fa-angle-left"></i></div>
            <div class="scroller arrow-right"><i class="fa fa-angle-right"></i></div>
            <div class="horizontal-tabs">
               <ul class="nav nav-tabs nav-tabs-horizontal mbot15" role="tablist">
                  <li role="presentation" class="active">
                     <a href="#tab_invoice" aria-controls="tab_invoice" role="tab" data-toggle="tab">
                     <?php echo _l('invoice'); ?>
                     </a>
                  </li>
                  <?php //if(count($invoice->payments) > 0) { ?>
                  
                  
                  <li role="presentation" data-toggle="tooltip" title="<?php echo _l('view_tracking'); ?>" class="tab-separator">
                     <a href="#tab_views" aria-controls="tab_views" role="tab" data-toggle="tab">
                     <?php if(!is_mobile()){ ?>
                     <i class="fa fa-eye"></i>
                     <?php } else { ?>
                     <?php echo _l('view_tracking'); ?>
                     <?php } ?>
                     </a>
                  </li>
                  <?php if(has_permission('payments','','view')){ ?>
                  <li role="presentation">
                     <a href="#invoice_payments_received" aria-controler="invoice_payments_received" role="tab" data-toggle="tab">
                     <?php echo _l('payments'); ?> <span class="badge"><?php echo count($invoice->payments); ?></span>
                     </a>
                  </li>
                  <?php } ?>
                  <li role="presentation">
                     <a href="#invoice_glosas" aria-controler="invoice_glosas" role="tab" data-toggle="tab">
                     <?php echo 'Glosas'; ?> <span class="badge"><?php echo count($invoice->glosas); ?></span>
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
               //echo $invoice->status.'<br>';
               echo format_invoice_status($invoice->status,'mtop5'); ?>
               <?php if($invoice->status == Invoices_model::STATUS_PARTIALLY || $invoice->status == Invoices_model::STATUS_OVERDUE){
                  if($invoice->duedate && date('Y-m-d') > date('Y-m-d',strtotime(to_sql_date($invoice->duedate)))){
                    echo '<p class="text-danger mtop15 no-mbot">'._l('invoice_is_overdue',floor((abs(time() - strtotime(to_sql_date($invoice->duedate))))/(60*60*24))).'</p>';
                  }
                  } ?>
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
                  if($invoice->status != 2){
                  if(has_permission('invoices','','edit')){ ?>
                  <a href="<?php echo admin_url('financeiro_invoices/invoice/'.$invoice->id); ?>" data-toggle="tooltip" title="<?php echo _l('edit_invoice_tooltip'); ?>" class="btn btn-default btn-with-tooltip" data-placement="bottom"><i class="fa fa-pencil-square-o"></i></a>
                  <?php }
                  }else if(is_admin()){
                   ?>
                     <a href="<?php echo admin_url('financeiro_invoices/invoice/'.$invoice->id); ?>" data-toggle="tooltip" title="<?php echo _l('edit_invoice_tooltip'); ?>" class="btn btn-default btn-with-tooltip" data-placement="bottom"><i class="fa fa-pencil-square-o"></i></a>
                 
                  <?php
                  }
                  ?>
              
                  <!-- Single button -->
                  <div class="btn-group">
                     <button type="button" class="btn btn-default pull-left dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                     <?php echo _l('more'); ?> <span class="caret"></span>
                     </button>
                     <ul class="dropdown-menu dropdown-menu-right">
                         
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
                  <a class="mleft10 pull-right btn btn-primary  new new-invoice-list mright5"  href="<?php echo admin_url('financeiro_invoices/record_invoice_glosa_ajax/' . $invoice->id)  ?> " ><i class="fa fa-plus-square"></i> <?php echo 'Glosa'; ?></a>
                  <a class="mleft10 pull-right btn btn-success  new new-invoice-list mright5"  href="<?php echo admin_url('financeiro_invoices/record_invoice_payment_ajax/' . $invoice->id)  ?> " ><i class="fa fa-plus-square"></i> <?php echo _l('payment'); ?></a>
                 <!-- <a  class="mleft10 pull-right btn btn-success  new new-invoice-list mright5"  data-toggle="modal" data-target="#add_recebimento_modal" data-id="<?php echo $invoice->id; ?>"><i class="fa fa-plus-square"></i> <?php echo _l('payment'); ?> </a> 
                 <a href="#" onclick="record_payment_receber(<?php echo $invoice->id; ?>); return false;"  class="mleft10 pull-right btn btn-success<?php if($invoice->status == Invoices_model::STATUS_CANCELLED){echo ' disabled';} ?>">
                     <i class="fa fa-plus-square"></i> <?php echo _l('payment'); ?></a> -->
               </div>
            </div>
         </div>
         <div class="clearfix"></div>
         <hr class="hr-panel-heading" />
         <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="tab_invoice">
               <?php if($invoice->status == Invoices_model::STATUS_CANCELLED && $invoice->recurring > 0) { ?>
               <div class="alert alert-info">
                  Recurring invoice with status Cancelled <b>is still ongoing recurring invoice</b>. If you want to stop this recurring invoice you should update the invoice recurring field to <b>No</b>.
               </div>
               <?php } ?>
               <?php $this->load->view('admin/financeiro/contas_receber/invoice_preview_html'); ?>
            </div>
            <?php //if(count($invoice->payments) > 0) { ?>
            <div class="tab-pane" role="tabpanel" id="invoice_payments_received">
               <?php include_once(APPPATH . 'views/admin/financeiro/contas_receber/invoice_payments_table.php'); ?>
            </div>
            <?php //} ?>
           
            <div class="tab-pane" role="tabpanel" id="invoice_glosas">
               <?php include_once(APPPATH . 'views/admin/financeiro/contas_receber/invoice_glosas_table.php'); ?>
            </div>
           
            <div role="tabpanel" class="tab-pane" id="tab_reminders">
               <a href="#" class="btn btn-info btn-xs" data-toggle="modal" data-target=".reminder-modal-invoice-<?php echo $invoice->id; ?>"><i class="fa fa-bell-o"></i> <?php echo _l('invoice_set_reminder_title'); ?></a>
               <hr />
               <?php render_datatable(array( _l( 'reminder_description'), _l( 'reminder_date'), _l( 'reminder_staff'), _l( 'reminder_is_notified')), 'reminders'); ?>
               <?php $this->load->view('admin/includes/modals/reminder',array('id'=>$invoice->id,'name'=>'invoice','members'=>$members,'reminder_title'=>_l('invoice_set_reminder_title'))); ?>
            </div>
         
            <div role="tabpanel" class="tab-pane" id="tab_emails_tracking">
               <?php
                  $this->load->view('admin/includes/emails_tracking',array(
                     'tracked_emails'=>
                     get_tracked_emails($invoice->id, 'invoice'))
                  );
                  ?>
            </div>
             
             <div role="tabpanel" class="tab-pane" id="tab_producao">
               <?php include_once(APPPATH . 'views/admin/invoices/invoice_producao_table.php'); ?>
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
            
            <div role="tabpanel" class="tab-pane" id="tab_views">
               <?php
                  $views_activity = get_views_tracking('invoice',$invoice->id);
                  if(count($views_activity) === 0) {
                     echo '<h4 class="no-mbot">'._l('not_viewed_yet',_l('invoice_lowercase')).'</h4>';
                  }
                  foreach($views_activity as $activity){ ?>
               <p class="text-success no-margin">
                  <?php echo _l('view_date') . ': ' . _dt($activity['date']); ?>
               </p>
               <p class="text-muted">
                  <?php echo _l('view_ip') . ': ' . $activity['view_ip']; ?>
               </p>
               <hr />
               <?php } ?>
            </div>
         </div>
      </div>
   </div>
</div>
<?php $this->load->view('admin/invoices/invoice_send_to_client'); ?>
<?php $this->load->view('admin/credit_notes/apply_invoice_credits'); ?>
<?php $this->load->view('admin/credit_notes/invoice_create_credit_note_confirm'); ?>
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
