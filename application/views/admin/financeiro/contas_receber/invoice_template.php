<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
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

<div class="panel_s<?php if(!isset($invoice) || (isset($invoice) && count($invoices_to_merge) == 0 && (isset($invoice) && !isset($invoice_from_project) && count($expenses_to_bill) == 0 || $invoice->status == Invoices_model::STATUS_CANCELLED))){echo ' hide';} ?>" id="invoice_top_info">
   <div class="panel-body">
      <div class="row">
         
         <!--  When invoicing from project area the expenses are not visible here because you can select to bill expenses while trying to invoice project -->
     
      </div>
   </div>
</div>

<?php
//print_r($invoice);
?>

<div class="panel_s invoice accounting-template">
   <div class="additional"></div>
   <div class="panel-body">
      <?php if(isset($invoice)){ ?>
      <?php  echo format_invoice_status($invoice->status); ?>
      <hr class="hr-panel-heading" />
      <?php } ?>
      <?php hooks()->do_action('before_render_invoice_template'); ?>
      <?php if(isset($invoice)){
        echo form_hidden('merge_current_invoice',$invoice->id);
      } ?>
      <div class="row">
         <div class="col-md-6">
             <?php $rel_id = (isset($invoice) ? $invoice->id : false); ?>
               <?php
                  if(isset($custom_fields_rel_transfer)) {
                      $rel_id = $custom_fields_rel_transfer;
                  }
               ?>
               <?php echo render_custom_fields('invoice',$rel_id); ?>
             
             <?php
               $next_invoice_number = get_option('next_invoice_number');
               $format = get_option('invoice_number_format');

               if(isset($invoice)){
                  $format = $invoice->number_format;
               }

               $prefix = get_option('invoice_prefix');

               if ($format == 1) {
                 $__number = $next_invoice_number;
                 if(isset($invoice)){
                   $__number = $invoice->number;
                   $prefix = '<span id="prefix">' . $invoice->prefix . '</span>';
                 }
               } else if($format == 2) {
                 if(isset($invoice)){
                   $__number = $invoice->number;
                   $prefix = $invoice->prefix;
                   $prefix = '<span id="prefix">'. $prefix . '</span><span id="prefix_year">' .date('Y',strtotime($invoice->date)).'</span>/';
                 } else {
                  $__number = $next_invoice_number;
                  $prefix = $prefix.'<span id="prefix_year">'.date('Y').'</span>/';
                }
               } else if($format == 3) {
                  if(isset($invoice)){
                   $yy = date('y',strtotime($invoice->date));
                   $__number = $invoice->number;
                   $prefix = '<span id="prefix">'. $invoice->prefix . '</span>';
                 } else {
                  $yy = date('y');
                  $__number = $next_invoice_number;
                }
               } else if($format == 4) {
                  if(isset($invoice)){
                   $yyyy = date('Y',strtotime($invoice->date));
                   $mm = date('m',strtotime($invoice->date));
                   $__number = $invoice->number;
                   $prefix = '<span id="prefix">'. $invoice->prefix . '</span>';
                 } else {
                  $yyyy = date('Y');
                  $mm = date('m');
                  $__number = $next_invoice_number;
                }
               }

               $_is_draft = (isset($invoice) && $invoice->status == Invoices_model::STATUS_DRAFT) ? true : false;
               $_invoice_number = str_pad($__number, get_option('number_padding_prefixes'), '0', STR_PAD_LEFT);
               $isedit = isset($invoice) ? 'true' : 'false';
               $data_original_number = isset($invoice) ? $invoice->number : 'false';

               if($invoice->number){
               ?>
             
                <div class="form-group">
                   <label for="number">
                      <?php echo _l('invoice_add_edit_number'); ?> 
                      <i class="fa fa-question-circle" data-toggle="tooltip" data-title="<?php echo _l('invoice_number_not_applied_on_draft') ?>" data-placement="top"></i>
                    </label>
                   <div class="input-group">
                      <span class="input-group-addon">
                      <?php if(isset($invoice)){ ?>
                        <a href="#" onclick="return false;" data-toggle="popover" data-container='._transaction_form' data-html="true" data-content="<label class='control-label'><?php echo _l('settings_sales_invoice_prefix'); ?></label>">
                        <i class="fa fa-cog"></i>
                        </a>
                      <?php }
                        echo $prefix;
                      ?>
                      </span>
                       <input type="text" name="number" readonly="true" class="form-control" value="<?php echo ($_is_draft) ? 'DRAFT' : $_invoice_number; ?>" data-isedit="<?php echo $isedit; ?>" data-original-number="<?php echo $data_original_number; ?>" <?php echo ($_is_draft) ? 'disabled' : '' ?>>
                     
                   </div>
                </div>
             
               <?php } ?>
             
            <label>Categoria</label>
            <select name="categoria_id" id="categoria_id" onchange="plano_conta_categoria(this.value)" required="true" class="form-control">
                <option checked="true" value="">Selecione</option>
                <?php foreach ($categorias_financeira as $cat){
                    $selected = "";
                     if($invoice->categoria_id == $cat['id']) {
                         $selected = 'selected';
                       }
                    
                    ?>
                 <option value="<?php echo $cat['id']; ?>" <?php echo $selected ?>><?php echo $cat['name']; ?></option>   
                <?php } ?>
            </select>
            <br>
            <div id="plano_contas">
                <?php 
                if($invoice->categoria_id){
                   
                  ?>
                    <label>Plano de Contas</label>
                    <select name="plano_conta_id" id="plano_conta_id" required="true" class="form-control">
                        <option checked="true" value="">Selecione</option>
                        <?php 
                        $planos_conta = $this->Financeiro_model->get_plano_contas("");   
                        foreach ($planos_conta as $plano){
                            $selectd = "";
                            if($invoice->plano_conta_id == $plano['id']){
                                $selectd = 'selected';
                            }
                            ?>
                         <option value="<?php echo $plano['id']; ?>" <?php echo $selectd ?>><?php echo $plano['descricao']; ?></option>   ;
                        <?php } ?>
                    </select>
                <?php
                }
                ?>
            </div>
            <br>
            <?php
            $selected = '';
            foreach($clientes as $cli){
             if(isset($invoice)){
               if($invoice->clientid == $cli['id']) {
                 $selected = $cli['id'];
               }
             }
            }
            echo render_select('client_id',$clientes,array('id',array('company')),'Cliente',$selected);
            ?>
        
            <div class="row">
                 <div class="col-md-12">
                         <?php
                        $selected = '';
                        foreach($centro_custo as $custo){
                         if(isset($invoice)){
                           if($invoice->centrocustoid == $custo['id']) {
                             $selected = $custo['id'];
                           }
                         }
                        }
                        echo render_select('centrocustoid',$centro_custo,array('id',array('descricao')),'selecione_centro_custo',$selected);
                        ?>
                  </div>
               <div class="col-md-12">
               <hr class="hr-10" />
                  <!--<a href="#" class="edit_shipping_billing_info" data-toggle="modal" data-target="#billing_and_shipping_details"><i class="fa fa-pencil-square-o"></i></a>-->
                  <?php //include_once(APPPATH .'views/admin/invoices/billing_and_shipping_template.php'); ?>
               </div>
             
            </div>
            
            <div class="row">
               <div class="col-md-6">
                  <?php $value = (isset($invoice) ? _d($invoice->date) : _d(date('Y-m-d')));
                  $date_attrs = array();
                  if(isset($invoice) && $invoice->recurring > 0 && $invoice->last_recurring_date != null) {
                  //  $date_attrs['disabled'] = false;
                    
                  }
                  ?>
                   
                  <?php echo render_date_input('date','Data de Envio',$value,$date_attrs); ?>
               </div>
               <div class="col-md-6">
                  <?php
                  $value = '';
                  if(isset($invoice)){
                    $value = _d($invoice->duedate);
                  } else {
                    if(get_option('invoice_due_after') != 0){
                        $value = _d(date('Y-m-d'));
                    }
                  }
                   ?>
                  <?php echo render_date_input('duedate','invoice_add_edit_duedate',$value); ?>
               </div>
            </div>
            
            
            
              
                   
                <div class="form-group">
                    <?php $value = (isset($invoice) ? $invoice->adminnote : ''); ?>
                   <?php echo render_textarea('adminnote','invoice_add_edit_admin_note',$value); ?>
                </div>
                
               
            
            
         </div>
         <div class="col-md-6">
            <div class="panel_s no-shadow">

                <div class="form-group">
                  <label>Competência</label>
                  <select name="competencia" id="competencia" required="true" class="form-control">
                    <option <?php if(!$invoice->competencia){ ?> checked="true" <?php } ?> value="<?php echo date('m').'/'.date('Y'); ?>"><?php echo date('m').'/'.date('Y'); ?></option>
                    <option <?php if($invoice->competencia == '01/2022'){ ?> checked="true" <?php } ?> value="01/2022">01/2022</option>
                    <option <?php if($invoice->competencia == '02/2022'){ ?> checked="true" <?php } ?> value="02/2022">02/2022</option>
                    <option <?php if($invoice->competencia == '03/2022'){ ?> checked="true" <?php } ?> value="03/2022">03/2022</option>
                    <option <?php if($invoice->competencia == '04/2022'){ ?> checked="true" <?php } ?> value="04/2022">04/2022</option>
                    <option <?php if($invoice->competencia == '05/2022'){ ?> checked="true" <?php } ?> value="05/2022">05/2022</option>
                    <option <?php if($invoice->competencia == '06/2022'){ ?> checked="true" <?php } ?> value="06/2022">06/2022</option>
                    <option <?php if($invoice->competencia == '07/2022'){ ?> checked="true" <?php } ?> value="07/2022">07/2022</option>
                    <option <?php if($invoice->competencia == '08/2022'){ ?> checked="true" <?php } ?> value="08/2022">08/2022</option>
                    <option <?php if($invoice->competencia == '09/2022'){ ?> checked="true" <?php } ?> value="09/2022">09/2022</option>
                    <option <?php if($invoice->competencia == '10/2022'){ ?> checked="true" <?php } ?> value="10/2022">10/2022</option>
                    <option <?php if($invoice->competencia == '11/2022'){ ?> checked="true" <?php } ?> value="11/2022">11/2022</option>
                    <option <?php if($invoice->competencia == '12/2022'){ ?> checked="true" <?php } ?> value="12/2022">12/2022</option>
                  </select>
                  
                </div>
                
                <div class="form-group">
                  <label>Valor Faturado</label>
                  <input type="text" name="subtotal" id="subtotal"  value="<?php echo $invoice->subtotal;  ?>"   maxlength="20" placeholder="1000,00"  class="form-control " required="true">
                </div>
                
                <div class="form-group">
                  <label>Impostos</label>
                  <input type="text" name="total_tax" id="total_tax" value="<?php echo $invoice->total_tax; ?>"  maxlength="20" placeholder="0,00"  class="form-control " >
                </div>
                
                <div class="form-group">
                  <label>Desconto</label>
                  <input type="text" name="desconto" id="desconto" value="<?php echo $invoice->discount_total; ?>"  maxlength="20"  class="form-control " >
                </div>
                
                
                
                
            </div>
        
         </div>
          
      </div>
      
      
      
      <div class="row">
      <div class="col-md-12 ">
         <div class="btn-bottom text-right">
                <button type="button" class="btn-tr btn btn-info invoice-form-submit transaction-submit"><?php echo _l('submit'); ?></button>
              
             </div>
        
      </div>
   </div>
      
      
   </div>

  
</div>

 


 
<script>
    
    
    
    function filtraConvenio() {
      $.ajax({
        type: "POST",
        url: "<?php echo admin_url("invoices/retorno_procedimentos"); ?>",
        data: {
          convenio: $('#convenio').val()
        },
        success: function(data) {
          $('#procedimentos').html(data);
        }
      });
    }
     
  function plano_conta_categoria(categoria_id, plano_id) {
    $.ajax({
        type: "POST",
        url: "<?php echo admin_url("financeiro/retorno_plano_conta"); ?>",
        data: {
          categoria_id: categoria_id,
          plano_id: plano_id
        },
        success: function(data) {
          $('#plano_contas').html(data);
        }
      });
}   
</script>