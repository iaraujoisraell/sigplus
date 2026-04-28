<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<link rel="stylesheet" href="<?php echo base_url() ?>assets/menu/dist/css/AdminLTE.min.css">
<link rel="stylesheet" href="<?php echo base_url() ?>assets/menu/bower_components/Ionicons/css/ionicons.min.css">
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
        <center><h2>AGENDAMENTO</h2></center>
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
                     
                  <div class="f_client_id">
                      <div class="form-group select-placeholder">
                        <label for="clientid" class="control-label"><?php echo 'Paciente'; ?></label>
                        <select id="clientid" name="clientid" data-live-search="true" data-width="100%" class="ajax-search<?php if(isset($invoice) && empty($invoice->clientid)){echo ' customer-removed';} ?>" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                       <?php $selected = (isset($invoice) ? $invoice->clientid : '');
                         if($selected == ''){
                           $selected = (isset($customer_id) ? $customer_id: '');
                         }
                         if($selected != ''){
                            $rel_data = get_relation_data('customer',$selected);
                            $rel_val = get_relation_values($rel_data,'customer');
                            echo '<option value="'.$rel_val['id'].'" selected>'.$rel_val['id'].'-'.$rel_val['name'].'</option>';
                         } ?>
                        </select>
                      </div>
                    </div> 
                  
                    <div class="row">
                        <div class="col-md-12">
                             <?php echo 'Médico '; ?> 
                            <select name="medicoid" id="medicoid" class="selectpicker"  onchange="filtraMedicoHorario();" data-live-search="true" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">

                                    <?php
                                     foreach($medicos as $medico){
                                         if($invoice->medicoid == $medico['medicoid']) {
                                             $selected = 'selected="true" ';
                                            echo '<option value="'.$medico['medicoid'].'" selected="true" >'.$medico['nome_profissional'].' ['.$medico['especialidade'].'] '.'</option>';    
                                        }
                                         
                                        echo '<option value="'.$medico['medicoid'].'" >'.$medico['nome_profissional'].' ['.$medico['especialidade'].'] ' .'</option>';
                                     }
                                   ?>
                               </select>
                                <br><br>
                          </div>
                        
                        <div class="col-md-12">
                            <?php echo 'Convênio'; ?> 

                                <select name="convenio" id="convenio" class="selectpicker"   data-live-search="true" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">

                                    <?php
                                     foreach($convenios as $convenio){
                                        if($invoice->convenio == $convenio['id']) {
                                             $selected = 'selected="true" ';
                                            echo '<option value="'.$convenio['id'].'" selected="true" >'.$convenio['name'].'</option>';    
                                        }
                                        echo '<option value="'.$convenio['id'].'" >'.$convenio['name'].'</option>';
                                     }
                                   ?>
                               </select>
                                
                            <br><br>
                          </div>
                         <br><br>
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
                                echo render_select_required_true('centrocustoid',$centro_custo,array('id',array('nome')),'selecione_centro_custo',$selected);
                                ?>
                          </div>
                       <div class="col-md-12">
                       <hr class="hr-10" />
                         
                       </div>
                      
                   
                    </div>
                   
                    
                      
                 </div>
                 <div class="col-md-6">
                    <div class="panel_s no-shadow">

                           
                       <div class="row">
                       <div class="col-md-6">
                           <label for="appointment_hours">Horário do Agendamento</label> <br>
                           <div id="horario_medico">
                           <?php //$this->load->view('admin/invoice_items/item_select__particular'); ?>
                            </div> 
                         
                         
                       </div>
                       <div class="col-md-6">
                          <?php
                          $appointment_types = get_appointment_types();
                           ?>
                          <div class="form-group select-placeholder">
                                <label for="appointment_select_type" class="control-label"><?= 'Tipo de Agendamento'; ?></label>
                                <select required="true" class="form-control selectpicker" required="true" name="type_id" id="appointment_select_type">
                                    <option value=""><?= _l('dropdown_non_selected_tex'); ?></option>
                                    <?php foreach ($appointment_types as $app_type) { ?>
                                        <option class="form-control" data-color="<?= $app_type['color']; ?>" value="<?= $app_type['id']; ?>"><?= $app_type['type']; ?>
                                        </option>
                                    <?php } ?>
                                </select>
                               
                            </div>
                       </div>
                    </div>

                       
                       <?php echo render_textarea('adminnote','invoice_add_edit_admin_note',$value); ?>

                    </div>
                     
                 </div>
              </div>
           </div>
           <div class="panel-body mtop10">
              
              <?php if(isset($invoice_from_project)){ echo '<hr class="no-mtop" />'; } ?>
              <div class="table-responsive s_table">
                 <table class="table invoice-items-table items table-main-invoice-edit has-calculations no-mtop">
                    <thead>
                       <tr>
                          <th></th>
                          <th width="40%" align="left"><i class="fa fa-exclamation-circle" aria-hidden="true" data-toggle="tooltip" data-title="<?php echo _l('item_description_new_lines_notice'); ?>"></i> <?php echo _l('invoice_table_item_heading'); ?></th>
                         
                          
                          <th width="10%" align="left" class="qty">Qtde</th>
                          <th width="10%" align="left"><?php echo 'Vl Unitário'; ?></th>
                       
                          <th width="20%" align="left"><?php echo 'Profissional'; ?></th>
                          <th width="10%" align="right"><?php echo 'Subtotal'; ?></th>
                          <th align="center"><i class="fa fa-cog"></i></th>
                       </tr>
                    </thead>
                    <tbody>
                       
                       <?php if (isset($invoice) || isset($add_items)) {
                          $i               = 1;
                          $items_indicator = 'newitems';
                          if (isset($invoice)) {
                            $add_items       = $invoice->items;
                            $items_indicator = 'items';
                          }
                          foreach ($add_items as $item) {

                            $manual    = false;
                            $table_row = '<tr class="sortable item">';
                            $table_row .= '<td class="dragger">';
                            if (!is_numeric($item['qty'])) {
                              $item['qty'] = 1;
                            }
                         
                            $table_row .= form_hidden('' . $items_indicator . '[' . $i . '][itemid]', $item['id']);
                            $amount = $item['rate'] * $item['qty'];
                            $amount = app_format_number($amount);
                            // order input
                            $table_row .= '<input type="hidden" class="order" name="' . $items_indicator . '[' . $i . '][order]">';
                            $table_row .= '<input type="hidden" value="' . $item['item_id'] . '"  name="' . $items_indicator . '[' . $i . '][item_id]">';
                            $table_row .= '</td>';
                            $table_row .= '<td class="bold description"><textarea readonly="true" name="' . $items_indicator . '[' . $i . '][description]" class="form-control" rows="5">' . clear_textarea_breaks($item['description']) . '</textarea></td>';
                            //$table_row .= '<td><textarea readonly="true" name="' . $items_indicator . '[' . $i . '][long_description]" class="form-control" rows="5">' . clear_textarea_breaks($item['long_description']) . '</textarea></td>';

                            $table_row .= render_custom_fields_items_table_in($item,$items_indicator.'['.$i.']');
                            if(!has_permission("invoices","","campo_qty")){ $readonly_qtd = 'readonly="true"';   }
                            $table_row .= '<td><input  type="number" min="0" onblur="calculate_total();" onchange="calculate_total();" '.$readonly_qtd.'  data-quantity name="' . $items_indicator . '[' . $i . '][qty]" value="' . $item['qty'] . '" class="form-control">';

                            $unit_placeholder = '';
                            if(!$item['unit']){
                              $unit_placeholder = _l('unit');
                              $item['unit'] = '';
                            }
                            if(!has_permission("invoices","","campo_qty")){ $readonly_qtd_unit = 'readonly="true"';   }
                            $table_row .= '<input  type="text" placeholder="'.$unit_placeholder.'" name="'.$items_indicator.'['.$i.'][unit]"  '.$readonly_qtd_unit.' class="form-control input-transparent text-right" value="'.$item['unit'].'">';
                            $table_row .= '</td>';

                            // VALOR PROCEDIMENTO LISTA
                            if(!has_permission("invoices","","campo_valor")){ $readonly_valor = 'readonly="true"';   }
                            $table_row .= '<td class="rate"><input  type="number" data-toggle="tooltip" readonly="true" title="' . _l('numbers_not_formatted_while_editing') . '" onblur="calculate_total();" onchange="calculate_total();" '.$readonly_valor.'   name="' . $items_indicator . '[' . $i . '][rate]" value="' . $item['rate'] . '" class="form-control"></td>';

                        
                               //$table_row .= '<td class="taxrate">' . $this->misc_model->get_taxes_dropdown_template('' . $items_indicator . '[' . $i . '][taxname][]', $invoice_item_taxes, 'invoice', $item['id'], true, $manual) . '</td>';
                             $table_row .= '<td class="medicoid">' . $this->misc_model->get_medicos_dropdown_template($items_indicator . '[' . $i . '][medicoid]',$item['medicoid']) . '</td>';
                            $table_row .= '<td class="amount" align="right">' . $amount . '</td>';
                            $table_row .= '<td><a href="#" class="btn btn-danger pull-left" onclick="delete_item(this,' . $item['id'] . '); return false;"><i class="fa fa-times"></i></a></td>';
                            
                            $table_row .= '</tr>';
                            echo $table_row;
                            $i++;
                          }
                          }
                          ?>
                    </tbody>
                 </table>
              </div>
              <div class="col-md-8 col-md-offset-4">
                 <table class="table text-right">
                    <tbody>
                       <tr id="subtotal">
                          <td><span class="bold"><?php echo _l('invoice_subtotal'); ?> :</span>
                          </td>
                          <td class="subtotal">
                          </td>
                       </tr>
                       <tr id="discount_area">
                          <td><span class="bold">  <?php echo _l('invoice_discount'); ?> :</span>
                                 <input type="hidden" readonly="true" data-toggle="tooltip" data-title="<?php echo _l('numbers_not_formatted_while_editing'); ?>" value="<?php echo (isset($invoice) ? $invoice->discount_total : 0); ?>" class="form-control pull-left input-discount-fixed" min="0" name="discount_total">

                          </td>
                          <td class="discount-total"></td>
                       </tr>

                   
                       <tr>
                          <td><span class="bold"><?php echo _l('invoice_total'); ?> :</span>
                          </td>
                          <td class="total">
                          </td>
                       </tr>
                    </tbody>
                 </table>
              </div>
           
             
           </div>
           <div class="row">
              <div class="col-md-12">
                 <div class="panel-body ">
                   
                    <div class=" text-right">
                        <?php /* if(!isset($invoice)){ ?>
                        <button class="btn-tr btn btn-default mleft10 text-right invoice-form-submit save-as-draft transaction-submit">
                        <?php echo _l('save_as_draft'); ?>
                        </button>
                        <?php } */ ?>
                      <button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
                     </div>
                 </div>
                
              </div>
           </div>
          
        </div>

    </div>
</div>


<?php init_tail(); ?>

<script>
	$(function(){
		validate_invoice_form();
                filtraMedicoHorario();
	    // Init accountacy currency symbol
	    init_currency();
	    // Project ajax search
	 //   init_ajax_project_search_by_customer_id();
	    // Maybe items ajax search
	    init_ajax_search('items','#item_select.ajax-search',undefined,admin_url+'items/search');
	});
           
</script>
<script>
    
    function filtraMedicoHorario() {
      $.ajax({
        type: "POST",
        url: "<?php echo admin_url("invoices/retorno_horario_medico"); ?>",
        data: {
          medicoid: $('#medicoid').val()
        },
        success: function(data) {
          $('#horario_medico').html(data);
        }
      });
    }
     
</script>
</body>
</html>


