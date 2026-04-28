<?php defined('BASEPATH') or exit('No direct script access allowed');
if($invoice->status == Invoices_model::STATUS_DRAFT){ ?>
   <div class="alert alert-info">
      <?php echo _l('invoice_draft_status_info'); ?>
   </div>
<?php }
if(isset($invoice->scheduled_email) && $invoice->scheduled_email) { ?>
   <div class="alert alert-warning">
      <?php echo _l('invoice_will_be_sent_at', _dt($invoice->scheduled_email->scheduled_at)); ?>
      <?php if(staff_can('edit', 'invoices') || $invoice->addedfrom == get_staff_user_id()) { ?>
         <a href="#"
         onclick="edit_invoice_scheduled_email(<?php echo $invoice->scheduled_email->id; ?>); return false;">
            <?php echo _l('edit'); ?>
         </a>
      <?php } ?>
   </div>
<?php } ?>

<div id="invoice-preview">
   <div class="row">
      <?php
      
        ?>
<?php if($invoice->project_id != 0){ ?>
   <div class="col-md-12">
      <h4 class="font-medium mtop15 mbot20"><?php echo _l('related_to_project',array(
         _l('invoice_lowercase'),
         _l('project_lowercase'),
         '<a href="'.admin_url('projects/view/'.$invoice->project_id).'" target="_blank">' . $invoice->project_data->name . '</a>',
         )); ?></h4>
      </div>
   <?php } ?>
   <div class="col-md-6 col-sm-6">
      <h4 class="bold">
         <?php
         $tags = get_tags_in($invoice->id,'invoice');
         if(count($tags) > 0){
           echo '<i class="fa fa-tag" aria-hidden="true" data-toggle="tooltip" data-title="'.html_escape(implode(', ',$tags)).'"></i>';
        }
        
        ?>
        <a href="<?php echo admin_url('invoices/invoice/'.$invoice->id); ?>">
         <span id="invoice-number">
            <?php echo format_invoice_number($invoice->id); ?>
         </span>
      </a>
   </h4>
   <address>
      <?php  echo format_organization_info(); ?>
   </address>
</div>
<div class="col-sm-6 text-right">
   <span class="bold"><?php echo _l('invoice_bill_to'); ?>:</span>
   <address>
      <?php echo $invoice->client->company; ?>
   </address>
   <?php if($invoice->include_shipping == 1 && $invoice->show_shipping_on_invoice == 1){ ?>
      <span class="bold"><?php echo _l('ship_to'); ?>:</span>
      <address>
         <?php echo format_customer_info($invoice, 'invoice', 'shipping'); ?>
      </address>
   <?php } ?>
   <p class="no-mbot">
      <span class="bold">
         <?php echo _l('invoice_data_date'); ?>
      </span>
      <?php echo $invoice->date; ?>
   </p>
   <?php if(!empty($invoice->duedate)){ ?>
      <p class="no-mbot">
         <span class="bold">
            <?php echo _l('invoice_data_duedate'); ?>
         </span>
         <?php echo $invoice->duedate; ?>
      </p>
   <?php } ?>
   <?php if($invoice->sale_agent != 0 && get_option('show_sale_agent_on_invoices') == 1){ ?>
      <p class="no-mbot">
         <span class="bold"><?php echo 'Cadastrdo por :'; ?>: </span>
         <?php echo get_staff_full_name($invoice->sale_agent); ?>
      </p>
   <?php } ?>
   <?php if($invoice->medicoid != 0 ){ ?>
      <p class="no-mbot">
        <span class="bold"><?php echo _l('medico'); ?>:</span>
        <?php echo get_medico_full_name($invoice->medicoid); ?>
      </p>
    <?php } ?>
      <?php if($invoice->convenio != 0 ){ ?>
      <p class="no-mbot">
        <span class="bold"><?php echo _l('convenio'); ?>:</span>
        <?php echo get_convenio_full_name($invoice->convenio); ?>
      </p>
    <?php } ?>
    <?php if($invoice->centrocustoid > 0){ ?>
      <p class="no-mbot">
        <span class="bold"><?php echo 'Centro de Custo'; ?>:</span>
        <?php echo get_centro_custo_financiero_full_name($invoice->centrocustoid); ?>
      </p>
    <?php } ?>  
   <?php if($invoice->project_id != 0 && get_option('show_project_on_invoice') == 1){ ?>
      <p class="no-mbot">
         <span class="bold"><?php echo _l('project'); ?>:</span>
         <?php echo get_project_name_by_id($invoice->project_id); ?>
      </p>
   <?php } ?>
   <?php $pdf_custom_fields = get_custom_fields('invoice',array('show_on_pdf'=>1));
   foreach($pdf_custom_fields as $field){
    $value = get_custom_field_value($invoice->id,$field['id'],'invoice');
    if($value == ''){continue;} ?>
    <p class="no-mbot">
      <span class="bold"><?php echo $field['name']; ?>: </span>
      <?php echo $value; ?>
   </p>
<?php } 



?>
</div>
</div>
    <br>
<div class="row">
   <div class="col-md-12">
      <p class="no-mbot">
         <span class="bold"><?php echo 'Observação'; ?>:</span>
         <?php echo $invoice->adminnote; ?>
      </p>
   </div>
   <div class="col-md-5 col-md-offset-7">
      <table class="table text-right">
         <tbody>
            <tr id="subtotal">
               <td><span class="bold"><?php echo _l('invoice_subtotal'); ?></span>
               </td>
               <td class="subtotal">
                  <?php echo app_format_money($invoice->subtotal, $invoice->currency_name); ?>
               </td>
            </tr>
            
            <?php if(is_sale_discount_applied($invoice)){ ?>
               <tr>
                  <td>
                     <span class="bold"><?php echo _l('invoice_discount'); ?>
                     <?php if(is_sale_discount($invoice,'percent')){ ?>
                        (<?php echo app_format_number($invoice->discount_percent,true); ?>%)
                        <?php } ?></span>
                     </td>
                     <td class="discount">
                        <?php echo '-' . app_format_money($invoice->discount_total, $invoice->currency_name); ?>
                     </td>
                  </tr>
               <?php } ?>
                <?php if((int)$invoice->glosa_antes > 0){ ?>
                  <tr>
                      <td>
                         <span class="bold"><?php echo 'Glosas Antes'; ?></span>
                      </td>
                      <td class="adjustment">
                         <?php echo app_format_money($invoice->glosa_antes, $invoice->currency_name); ?>
                      </td>
                   </tr>
                   <tr id="subtotal">
                   <td><span class="bold"><?php echo 'Subtotal antes do imposto'; ?></span>
                   </td>
                   <td>
                      <?php
                      $subtotal_novo = $invoice->subtotal - $invoice->glosa_antes;
                      echo app_format_money($subtotal_novo, $invoice->currency_name); ?>
                   </td>
                </tr>
                 <?php } ?> 
               <?php if((int)$invoice->total_tax > 0){ ?>
               <tr>
                  <td>
                     <span class="bold"><?php echo 'Impostos'; ?></span>
                  </td>
                  <td class="adjustment">
                     <?php echo app_format_money($invoice->total_tax, $invoice->currency_name); ?>
                  </td>
               </tr>
            <?php } ?>
               
               <?php if((int)$invoice->glosa_pos > 0){ ?>
               <tr>
                  <td>
                     <span class="bold"><?php echo 'Glosas Depois'; ?></span>
                  </td>
                  <td class="adjustment">
                     <?php echo app_format_money($invoice->glosa_pos, $invoice->currency_name); ?>
                  </td>
               </tr>
            <?php } ?>
                  
              <?php if((int)$invoice->adjustment != 0){ ?>
               <tr>
                  <td>
                     <span class="bold"><?php echo _l('invoice_adjustment'); ?></span>
                  </td>
                  <td class="adjustment">
                     <?php echo app_format_money($invoice->adjustment, $invoice->currency_name); ?>
                  </td>
               </tr>
            <?php } ?>
            <tr>
               <td><span class="bold"><?php echo _l('invoice_total'); ?></span>
               </td>
               <td class="total">
                  <?php echo app_format_money($invoice->total, $invoice->currency_name); ?>
               </td>
            </tr>
            <?php if(count($invoice->payments) > 0 && get_option('show_total_paid_on_invoice') == 1) { ?>
               <tr>
                  <td><span class="bold"><?php echo _l('invoice_total_paid'); ?></span></td>
                  <td>
                     <?php echo '-' . app_format_money(sum_from_table(db_prefix().'fin_invoicepaymentrecords',array('field'=>'amount','where'=>array('invoiceid'=>$invoice->id, 'deleted'=>0))), $invoice->currency_name); ?>
                  </td>
               </tr>
            <?php }   ?>
     
            <?php if(get_option('show_amount_due_on_invoice') == 1 && $invoice->status != Invoices_model::STATUS_CANCELLED) { ?>
               <tr>
                  <td><span class="<?php if($invoice->total_left_to_pay > 0){echo 'text-danger ';} ?>bold"><?php echo _l('invoice_amount_due'); ?></span></td>
                  <td>
                     <span class="<?php if($invoice->total_left_to_pay > 0){echo 'text-danger';} ?>">
                        <?php echo app_format_money($invoice->total_left_to_pay, $invoice->currency_name); ?>
                     </span>
                  </td>
               </tr>
            <?php } 
          //  print_r($invoice); exit;
            ?>
         </tbody>
      </table>
   </div>
</div>

<hr />

</div>
