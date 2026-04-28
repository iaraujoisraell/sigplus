<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>



<div class="panel_s accounting-template estimate">
   <div class="panel-body">
      <?php if(isset($estimate)){ ?>
      <?php echo format_estimate_status($estimate->status); ?>
      <hr class="hr-panel-heading" />
      <?php } ?>
      <div class="row">
         <div class="col-md-6">
            <div class="f_client_id">
             <div class="form-group select-placeholder">
                <label for="clientid" class="control-label"><?php echo _l('estimate_select_customer'); ?></label>
                <select id="clientid" name="clientid" data-live-search="true" data-width="100%" class="ajax-search<?php if(isset($estimate) && empty($estimate->clientid)){echo ' customer-removed';} ?>" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
               <?php $selected = (isset($estimate) ? $estimate->clientid : '');
                 if($selected == ''){
                   $selected = (isset($customer_id) ? $customer_id: '');
                 }
                 if($selected != ''){
                    $rel_data = get_relation_data('customer',$selected);
                    $rel_val = get_relation_values($rel_data,'customer');
                    echo '<option value="'.$rel_val['id'].'" selected>'.$rel_val['name'].'</option>';
                 } ?>
                </select>
              </div>
            </div>
             
             
             
            <div class="form-group select-placeholder projects-wrapper<?php if((!isset($estimate)) || (isset($estimate) && !customer_has_projects($estimate->clientid))){ echo ' hide';} ?>">
             <label for="project_id"><?php echo _l('project'); ?></label>
             <div id="project_ajax_search_wrapper">
               <select name="project_id" id="project_id" class="projects ajax-search" data-live-search="true" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                <?php
                  if(isset($estimate) && $estimate->project_id != 0){
                    echo '<option value="'.$estimate->project_id.'" selected>'.get_project_name_by_id($estimate->project_id).'</option>';
                  }
                ?>
              </select>
            </div>
           </div>
             
             
            <div class="row">
                <div class="col-md-12">
                         <?php
                        $selected = '';
                        foreach($medicos as $medico){
                         if(isset($estimate)){
                           if($estimate->medicoid == $medico['medicoid']) {
                             $selected = $medico['medicoid'];
                           }
                         }
                        }
                        echo render_select_required_true('medicoid',$medicos,array('medicoid',array('nome_profissional')),'selecione_medico_encaminhamento',$selected);
                        ?>
                  </div>
                 <div class="col-md-12">
                    <?php echo 'Convênio'; ?> 
                    
                     <select name="convenio" id="convenio" required="true" class="selectpicker"  onload="filtraConvenio()"  onchange="filtraConvenio();" data-live-search="true" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                            
                            <?php
                             $selected = '';
                             foreach($convenios as $convenio){
                                if($estimate->convenio == $convenio['id']) {
                                    $selected = 'selected="true" ';
                                    echo '<option value="'.$convenio['id'].'" selected="true" >'.$convenio['name'].'</option>';    
                                     
                                }
                                echo '<option value="'.$convenio['id'].'"  >'.$convenio['name'].'</option>';
                             }
                           ?>
                       </select>
                       
                    <br><br>
                  </div>
                <div class="col-md-12">
                     <?php 
                     /*
                    $selected = '';
                    foreach($convenios as $convenio){
                     if(isset($estimate)){
                       if($estimate->convenio == $convenio['id']) {
                         $selected = $convenio['id'];
                       }
                     }
                    }
                    echo render_select_required_true('convenio',$convenios,array('id',array('name')),'convenio',$selected); */
                    ?>
              </div>
                <div class="col-md-12">
                         <?php
                        $selected = '';
                        foreach($centro_custo as $custo){
                         if(isset($estimate)){
                           if($estimate->centrocustoid == $custo['id']) {
                             $selected = $custo['id'];
                           }
                         }
                        }
                        echo render_select('centrocustoid',$centro_custo,array('id',array('nome')),'selecione_centro_custo',$selected);
                        ?>
                  </div>
             
              
            </div>
            <?php
               $next_estimate_number = get_option('next_estimate_number');
               $format = get_option('estimate_number_format');

                if(isset($estimate)){
                  $format = $estimate->number_format;
                }

               $prefix = get_option('estimate_prefix');

               if ($format == 1) {
                 $__number = $next_estimate_number;
                 if(isset($estimate)){
                   $__number = $estimate->number;
                   $prefix = '<span id="prefix">' . $estimate->prefix . '</span>';
                 }
               } else if($format == 2) {
                 if(isset($estimate)){
                   $__number = $estimate->number;
                   $prefix = $estimate->prefix;
                   $prefix = '<span id="prefix">'. $prefix . '</span><span id="prefix_year">' . date('Y',strtotime($estimate->date)).'</span>/';
                 } else {
                   $__number = $next_estimate_number;
                   $prefix = $prefix.'<span id="prefix_year">'.date('Y').'</span>/';
                 }
               } else if($format == 3) {
                  if(isset($estimate)){
                   $yy = date('y',strtotime($estimate->date));
                   $__number = $estimate->number;
                   $prefix = '<span id="prefix">'. $estimate->prefix . '</span>';
                 } else {
                  $yy = date('y');
                  $__number = $next_estimate_number;
                }
               } else if($format == 4) {
                  if(isset($estimate)){
                   $yyyy = date('Y',strtotime($estimate->date));
                   $mm = date('m',strtotime($estimate->date));
                   $__number = $estimate->number;
                   $prefix = '<span id="prefix">'. $estimate->prefix . '</span>';
                 } else {
                  $yyyy = date('Y');
                  $mm = date('m');
                  $__number = $next_estimate_number;
                }
               }

               $_estimate_number = str_pad($__number, get_option('number_padding_prefixes'), '0', STR_PAD_LEFT);
               $isedit = isset($estimate) ? 'true' : 'false';
               $data_original_number = isset($estimate) ? $estimate->number : 'false';
               
               
              if($estimate->number){
               ?>
             
                <div class="form-group">
                   <label for="number">
                      <?php echo _l('estimate_add_edit_number'); ?> 
                    </label>
                   <div class="input-group">
                      <span class="input-group-addon">
                      <?php if(isset($estimate)){ ?>
                        <a href="#" onclick="return false;" data-toggle="popover" data-container='._transaction_form' data-html="true" data-content="<label class='control-label'><?php echo _l('settings_sales_estimate_prefix'); ?></label>">
                        <i class="fa fa-cog"></i>
                        </a>
                      <?php }
                        echo $prefix;
                      ?>
                      </span>
                       <input type="text" name="number" readonly="true"  class="form-control" value="<?php echo $_estimate_number; ?>" data-isedit="<?php echo $isedit; ?>" data-original-number="<?php echo $data_original_number; ?>">
                    <?php /* if($format == 3) { ?>
                      <span class="input-group-addon">
                         <span id="prefix_year" class="format-n-yy"><?php echo $yy; ?></span>
                      </span>
                      <?php } else if($format == 4) { ?>
                       <span class="input-group-addon">
                         <span id="prefix_month" class="format-mm-yyyy"><?php echo $mm; ?></span>
                         /
                         <span id="prefix_year" class="format-mm-yyyy"><?php echo $yyyy; ?></span>
                      </span>
                      <?php }*/ ?>
                   </div>
                </div>
             
               <?php } ?>
             
             
            

            <div class="row">
               <div class="col-md-6">
                  <?php $value = (isset($estimate) ? _d($estimate->date) : _d(date('Y-m-d'))); ?>
                  <?php echo render_date_input('date','estimate_add_edit_date',$value); ?>
               </div>
               <div class="col-md-6">
                  <?php
                  $value = '';
                  if(isset($estimate)){
                    $value = _d($estimate->expirydate);
                  } else {
                      if(get_option('estimate_due_after') != 0){
                          $value = _d(date('Y-m-d', strtotime('+' . get_option('estimate_due_after') . ' DAY', strtotime(date('Y-m-d')))));
                      }
                  }
                  echo render_date_input('expirydate','estimate_add_edit_expirydate',$value); ?>
               </div>
            </div>
            <div class="clearfix mbot15"></div>
            <?php $rel_id = (isset($estimate) ? $estimate->id : false); ?>
            <?php
                  if(isset($custom_fields_rel_transfer)) {
                      $rel_id = $custom_fields_rel_transfer;
                  }
             ?>
            <?php echo render_custom_fields('estimate',$rel_id); ?>
         </div>
          
          
         <div class="col-md-6">
            <div class="panel_s no-shadow">
                <?php //if(is_admin()){ ?>
             <div class="col-md-12">
                    <?php echo 'TIPO DE PAGAMENTO '; ?> 
                    
                        <select name="payment_modes" id="payment_modes" class="selectpicker"   data-live-search="true" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                         
                            <?php
                             echo '<option value="" selected="true" >TODOS</option>';   
                             echo '<option value="01"  >Dinheiro, Débito, PIX, Transferência</option>';   
                             echo '<option value="02"  >Crédito</option>';   
                            // foreach($payment_modes_tipo as $tipo){
                               
                             //   echo '<option value="'.$tipo['id'].'" >'.$tipo['name'].'</option>';
                             //}
                           ?>
                       </select>
                 
                  </div>
             <?php //} ?>
                
               <div class="form-group">
                  <label for="tags" class="control-label"><i class="fa fa-tag" aria-hidden="true"></i> <?php echo _l('tags'); ?></label>
                  <input type="text" class="tagsinput" id="tags" name="tags" value="<?php echo (isset($estimate) ? prep_tags_input(get_tags_in($estimate->id,'estimate')) : ''); ?>" data-role="tagsinput">
               </div>
               <div class="row">
                  <div class="col-md-6">
                     <?php

                        $currency_attr = array('disabled'=>true,'data-show-subtext'=>true);
                        $currency_attr = apply_filters_deprecated('estimate_currency_disabled', [$currency_attr], '2.3.0', 'estimate_currency_attributes');
                        foreach($currencies as $currency){
                          if($currency['isdefault'] == 1){
                            $currency_attr['data-base'] = $currency['id'];
                          }
                          if(isset($estimate)){
                            if($currency['id'] == $estimate->currency){
                              $selected = $currency['id'];
                            }
                          } else{
                           if($currency['isdefault'] == 1){
                            $selected = $currency['id'];
                          }
                        }
                        }
                        $currency_attr = hooks()->apply_filters('estimate_currency_attributes',$currency_attr);
                        ?>
                     <?php echo render_select('currency', $currencies, array('id','name','symbol'), 'estimate_add_edit_currency', $selected, $currency_attr); ?>
                  </div>
                   <div class="col-md-6">
                     <div class="form-group select-placeholder">
                        <label class="control-label"><?php echo _l('estimate_status'); ?></label>
                        <select class="selectpicker display-block mbot15" name="status" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                           <?php foreach($estimate_statuses as $status){ ?>
                           <option value="<?php echo $status; ?>" <?php if(isset($estimate) && $estimate->status == $status){echo 'selected';} ?>><?php echo format_estimate_status($status,'',false); ?></option>
                           <?php } ?>
                        </select>
                     </div>
                  </div>
                  <div class="col-md-12">
                    <?php $value = (isset($estimate) ? $estimate->reference_no : ''); ?>
                    <?php echo render_input('reference_no','reference_no',$value); ?>
                  </div>
                  <div class="col-md-6">
                         <?php
                        $selected = '';
                        foreach($staff as $member){
                         if(isset($estimate)){
                           if($estimate->sale_agent == $member['staffid']) {
                             $selected = $member['staffid'];
                           }else{
                               $selected = get_staff_user_id();
                           }
                         }
                        }
                        if($estimate->sale_agent == null){
                           $selected = get_staff_user_id();
                        }
                        echo render_select('sale_agent',$staff,array('staffid',array('firstname','lastname')),'sale_agent_string',$selected);
                        ?>
                  </div>
                  <div class="col-md-6">
                       <div class="form-group select-placeholder">
                        <label for="discount_type" class="control-label"><?php echo _l('discount_type'); ?></label>
                        <select name="discount_type" class="selectpicker" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                           <option value="" selected><?php echo _l('no_discount'); ?></option>
                           <option value="before_tax" <?php
                              if(isset($estimate)){ if($estimate->discount_type == 'before_tax'){ echo 'selected'; }}?>><?php echo _l('discount_type_before_tax'); ?></option>
                           <option value="after_tax" <?php if(isset($estimate)){if($estimate->discount_type == 'after_tax'){echo 'selected';}} ?>><?php echo _l('discount_type_after_tax'); ?></option>
                        </select>
                     </div>
                  </div>
               </div>
               <?php $value = (isset($estimate) ? $estimate->adminnote : ''); ?>
               <?php echo render_textarea('adminnote','estimate_add_edit_admin_note',$value); ?>

            </div>
         </div>
      </div>
   </div>
    
   <?php $this->load->view('admin/estimates/_add_edit_items'); ?>
   <div class="row">
    <div class="col-md-12 mtop15">
      <div class="panel-body bottom-transaction">
        <?php $value = (isset($estimate) ? $estimate->clientnote : get_option('predefined_clientnote_estimate')); ?>
        <?php echo render_textarea('clientnote','estimate_add_edit_client_note',$value,array(),array(),'mtop15'); ?>
        <?php $value = (isset($estimate) ? $estimate->terms : get_option('predefined_terms_estimate')); ?>
        <?php echo render_textarea('terms','terms_and_conditions',$value,array(),array(),'mtop15'); ?>
        <div class="btn-bottom-toolbar text-right">
          <div class="btn-group dropup">
            <button type="button" class="btn-tr btn btn-info estimate-form-submit transaction-submit">
              <?php echo _l('submit'); ?>
            </button>
          <button type="button"
            class="btn btn-info dropdown-toggle"
            data-toggle="dropdown"
            aria-haspopup="true"
            aria-expanded="false">
            <span class="caret"></span>
          </button>
          <ul class="dropdown-menu dropdown-menu-right width200">
            <li>
              <a href="#" class="estimate-form-submit save-and-send transaction-submit">
                <?php echo _l('save_and_send'); ?>
              </a>
            </li>
            <?php if(!isset($estimate)) { ?>
              <li>
                <a href="#" class="estimate-form-submit save-and-send-later transaction-submit">
                  <?php echo _l('save_and_send_later'); ?>
                </a>
              </li>
            <?php } ?>
          </ul>
        </div>
      </div>
    </div>
    <div class="btn-bottom-pusher"></div>
  </div>
</div>
</div>


<script>
    $(function(){
       filtraConvenio();
    });
</script>

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
    

    

     
</script>