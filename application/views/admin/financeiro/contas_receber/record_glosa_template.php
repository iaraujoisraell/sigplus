<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(false); ?>
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
        Add Glosa
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo admin_url(''); ?>"><i class="fa fa-dashboard"></i> Financeiro </a></li>
        <li><a href="<?php echo admin_url('financeiro_invoices'); ?>"><i class="fa fa-dashboard"></i> Títulos a Receber </a></li>
        <li class="active"><a href="<?php echo admin_url(''); ?>">Glosas</a></li>
      </ol>
    </section>
        <div class="col-md-12 no-padding animated fadeIn">
    <div class="panel_s">
        <?php echo form_open(admin_url('financeiro_invoices/record_glosa'),array('id'=>'record_payment_form')); ?>
        <?php echo form_hidden('invoiceid',$invoice->id); ?>
       
        
        <div class="panel-body">
            <h4 class="no-margin"><?php echo 'Registrar glosa para '; ?> <?php echo format_invoice_number($invoice->id); ?> <label class="btn btn-primary pull-right"><?php echo app_format_money($invoice->total,' R$ '); ?></label></h4> 
           <hr class="hr-panel-heading" />
            <div class="row">
                
                <div class="col-md-6">
                    <?php
                    
                    $amount = $invoice->total_left_to_pay;
                    $totalAllowed = 0;
                    echo render_input('amount','Valor da Glosa',$amount,'number',array('max'=>$amount)); ?>
                    <?php echo render_date_input('date','Data',_d(date('Y-m-d'))); ?>
                    
                    <div class="form-group">
                  <label>Tipo</label>
                  <select name="tipo" id="tipo" required="true" class="form-control">
                      <option value="antes">Antes do Imposto</option>
                      <option value="depois">Depois do Imposto</option>
                  </select>
                    </div>
                     <div class="form-gruoup">
                        <label for="note" class="control-label"><?php echo _l('record_payment_leave_note'); ?></label>
                        <textarea name="note" class="form-control" rows="8" placeholder="<?php echo _l('invoice_record_payment_note_placeholder'); ?>" id="note"></textarea>
                    </div>
                  
                    
             
                </div>
               
                
               
                
                
                
            </div>
            <div class="pull-right mtop15">
                <a href="<?php echo admin_url('financeiro_invoices/list_invoices/' . $invoice->id) ?>" class="btn btn-danger" ><?php echo _l('cancel'); ?></a>
             
                <button type="submit" autocomplete="off" data-loading-text="<?php echo _l('wait_text'); ?>" data-form="#record_payment_form" class="btn btn-success"><?php echo _l('submit'); ?></button>
               
            </div>
           
         
            
           

        </div>
        
        <?php echo form_close();
        
        ?>
    </div>
</div>
       </div>     
    </div>
</div>    
<?php init_tail(); ?>
<script>
   $(function(){
     init_selectpicker();
     init_datepicker();
     appValidateForm($('#record_payment_form'),{amount:'required',date:'required',paymentmode:'required'});
     var $sMode = $('select[name="paymentmode"]');
     var total_available_payment_modes = $sMode.find('option').length - 1;
     if(total_available_payment_modes == 1) {
        $sMode.selectpicker('val',$sMode.find('option').eq(1).attr('value'));
        $sMode.trigger('change');
     }
 });
</script>
         