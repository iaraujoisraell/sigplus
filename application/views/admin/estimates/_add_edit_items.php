<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="panel-body mtop10">
    <div class="row">
<!--      <div class="col-md-4">
          <?php //$this->load->view('admin/invoice_items/item_select'); ?>
      </div>
      <div class="col-md-4">
          <?php //$this->load->view('admin/invoice_items/item_select__particular'); ?>
      </div> 
      <div class="col-md-4">
          <?php //$this->load->view('admin/invoice_items/item_select__outros'); ?>
      </div>-->
            <div class="col-md-8">
               <div id="procedimentos">
               <?php $this->load->view('admin/invoice_items/item_select__particular'); ?>
                </div> 
            </div>
     <?php /* if(!isset($invoice_from_project) && isset($billable_tasks)){
      ?>
     <div class="col-md-3">
        <div class="form-group select-placeholder input-group-select form-group-select-task_select popover-250">
          <div class="input-group input-group-select">
           <select name="task_select" data-live-search="true" id="task_select" class="selectpicker no-margin _select_input_group" data-width="100%" data-none-selected-text="<?php echo _l('bill_tasks'); ?>">
              <option value=""></option>
              <?php foreach($billable_tasks as $task_billable){ ?>
              <option value="<?php echo $task_billable['id']; ?>"<?php if($task_billable['started_timers'] == true){ ?>disabled class="text-danger important" data-subtext="<?php echo _l('invoice_task_billable_timers_found'); ?>" <?php } else {
                 $task_rel_data = get_relation_data($task_billable['rel_type'],$task_billable['rel_id']);
                 $task_rel_value = get_relation_values($task_rel_data,$task_billable['rel_type']);
                 ?>
                 data-subtext="<?php echo $task_billable['rel_type'] == 'project' ? '' : $task_rel_value['name']; ?>" <?php } ?>><?php echo $task_billable['name']; ?></option>
              <?php } ?>
           </select>
            <div class="input-group-addon input-group-addon-bill-tasks-help">
              <?php
                if(isset($invoice) && !empty($invoice->project_id)) {
                   $help_text = _l('showing_billable_tasks_from_project') . ' ' . get_project_name_by_id($invoice->project_id);
                } else {
                   $help_text = _l('invoice_task_item_project_tasks_not_included');
                }
                echo '<span class="pointer popover-invoker" data-container=".form-group-select-task_select"
                  data-trigger="click" data-placement="top" data-toggle="popover" data-content="'.$help_text.'">
                  <i class="fa fa-question-circle"></i></span>';
              ?>
            </div>
           </div>
        </div>
     </div>
     <?php } */?>
     <div class="col-md-4 text-right show_quantity_as_wrapper">
         <div class="mtop10">
           <div class="radio radio-primary radio-inline">
               <input type="hidden" value="1" id="sq_1" name="show_quantity_as" data-text="<?php echo _l('invoice_table_quantity_heading'); ?>" <?php if(isset($invoice) && $invoice->show_quantity_as == 1){echo 'checked';}else if(!isset($hours_quantity) && !isset($qty_hrs_quantity)){echo'checked';} ?>>
           </div>
         <!--  <div class="radio radio-primary radio-inline">
              <input type="radio" value="2" id="sq_2" name="show_quantity_as" data-text="<?php echo _l('invoice_table_hours_heading'); ?>" <?php if(isset($invoice) && $invoice->show_quantity_as == 2 || isset($hours_quantity)){echo 'checked';} ?>>
              <label for="sq_2"><?php echo _l('quantity_as_hours'); ?></label>
           </div>
           <div class="radio radio-primary radio-inline">
              <input type="radio" value="3" id="sq_3" name="show_quantity_as" data-text="<?php echo _l('invoice_table_quantity_heading'); ?>/<?php echo _l('invoice_table_hours_heading'); ?>" <?php if(isset($invoice) && $invoice->show_quantity_as == 3 || isset($qty_hrs_quantity)){echo 'checked';} ?>>
              <label for="sq_3"><?php echo _l('invoice_table_quantity_heading'); ?>/<?php echo _l('invoice_table_hours_heading'); ?></label>
           </div> -->
        </div>
     </div>
    </div>
   <div class="table-responsive s_table">
      <table class="table estimate-items-table items table-main-estimate-edit has-calculations no-mtop">
         <thead>
            <tr>
               <th></th>
               <th width="20%" align="left"><i class="fa fa-exclamation-circle" aria-hidden="true" data-toggle="tooltip" data-title="<?php echo _l('item_description_new_lines_notice'); ?>"></i> <?php echo _l('estimate_table_item_heading'); ?></th>
               <th width="25%" align="left"><?php echo _l('estimate_table_item_description'); ?></th>
               <?php
                  $custom_fields = get_custom_fields('items');
                  foreach($custom_fields as $cf){
                   echo '<th width="15%" align="left" class="custom_field">' . $cf['name'] . '</th>';
                  }

                  $qty_heading = _l('estimate_table_quantity_heading');
                  if(isset($estimate) && $estimate->show_quantity_as == 2){
                  $qty_heading = _l('estimate_table_hours_heading');
                  } else if(isset($estimate) && $estimate->show_quantity_as == 3){
                  $qty_heading = _l('estimate_table_quantity_heading') . '/' . _l('estimate_table_hours_heading');
                  }
                  ?>
               <th width="10%" class="qty" align="left"><?php echo $qty_heading; ?></th>
               <th width="10%" align="left"><?php echo _l('estimate_table_amount_heading'); ?></th>
               <th width="10%" align="left"><?php echo 'Desconto'; ?></th>
             <!--  <th width="5%" align="right"><?php echo _l('estimate_table_tax_heading'); ?></th> -->
               <th width="20%" align="left"><?php echo _l('medico'); ?></th>
               <th width="10%" align="right"><?php echo _l('estimate_table_amount_heading'); ?></th>
               <th align="center"><i class="fa fa-cog"></i></th>
            </tr>
         </thead>
         <tbody>
            <tr class="main">
                <td>
                    <input type="hidden"  name="item_id" class="form-control" style="width: 100px"></td>
               <td>
                  <textarea name="description" rows="4" class="form-control" placeholder="<?php echo _l('item_description_placeholder'); ?>"></textarea>
               </td>
               <td>
                  <textarea name="long_description" rows="4" class="form-control" placeholder="<?php echo _l('item_long_description_placeholder'); ?>"></textarea>
               </td>
               <?php echo render_custom_fields_items_table_add_edit_preview(); ?>
               <td>
                  <input type="number" name="quantity" min="0" value="1" class="form-control" placeholder="<?php echo _l('item_quantity_placeholder'); ?>">
                  <input type="text" placeholder="<?php echo _l('unit'); ?>" name="unit" class="form-control input-transparent text-right">
               </td>
               <td>
                  <input type="number" name="rate" class="form-control" placeholder="<?php echo _l('valor_unitario'); ?>">
               </td>
               <td>
                   <div class="row">
                        <div class="input-group" id="discount-total">
                          <div class="col-md-12">
                            <input type="number" id="valor_desconto"  name="valor_desconto" class="form-control" value="0.00" placeholder="<?php echo 'Desconto'; ?>">
   
                         <!--  <input type="number" value="0.00" class="form-control pull-left input-discount-percent<?php //if(isset($estimate) && !is_sale_discount($estimate,'percent') && is_sale_discount_applied($estimate)){echo ' hide';} ?>" min="0" max="100" name="valor_desconto"> -->
                          </div>
                            <div class="col-md-12">
                                <select name="destino_desconto" id="destino_desconto"  class="form-control">
                                    <option value="AMBOS">AMBOS</option>
                                    <option value="EMPRESA">EMPRESA</option>
                                    <option value="MÉDICO">MÉDICO</option>
                                  </select>
                            </div>   
                           
                        </div>
                    
                  </div>
                     
                  </td>
              <!-- <td>
                  <?php
                  /*
                     $default_tax = unserialize(get_option('default_tax'));
                     $select = '<select class="selectpicker display-block tax main-tax" data-width="100%" name="taxname" multiple data-none-selected-text="'._l('no_tax').'">';
                     foreach($taxes as $tax){
                       $selected = '';
                       if(is_array($default_tax)){
                         if(in_array($tax['name'] . '|' . $tax['taxrate'],$default_tax)){
                           $selected = ' selected ';
                         }
                       }
                       $select .= '<option value="'.$tax['name'].'|'.$tax['taxrate'].'"'.$selected.'data-taxrate="'.$tax['taxrate'].'" data-taxname="'.$tax['name'].'" data-subtext="'.$tax['name'].'">'.$tax['taxrate'].'%</option>';
                     }
                     $select .= '</select>';
                     echo $select; */
                     ?>
               </td> -->
               <td>
                  <?php /*
                     $select = '<select class="selectpicker display-block " data-width="100%" name="medicoid" id="medicoid"  data-none-selected-text="'._l('sem_medico').'">';
                     $select .= '<option value=""'.$selected.'" >'._l('sem_medico').'</option>';
                     foreach($medicos as $med){
                       $selected = '';
                       if(is_array($default_medico)){
                         if(in_array($med['nome_profissional'] ,$default_medico)){
                           $selected = ' selected ';
                         }
                       }
                       $select .= '<option value="'.$med['medicoid'].'"'.$selected.'" data-medicoid="'.$med['medicoid'].'" >'.$med['nome_profissional'].'</option>';
                     }
                     $select .= '</select>';
                     echo $select; */
                     ?>
               </td>
               
               <td></td>
               <td>
                  <?php
                     $new_item = 'undefined';
                     if(isset($estimate)){
                       $new_item = true;
                     }
                     ?>
                  <button type="button" onclick="add_item_to_table('undefined','undefined',<?php echo $new_item; ?>); return false;" class="btn pull-right btn-info"><i class="fa fa-check"></i></button>
               </td>
            </tr>
            <?php if (isset($estimate) || isset($add_items)) {
               $i               = 1;
               $items_indicator = 'newitems';
               if (isset($estimate)) {
                 $add_items       = $estimate->items;
                 $items_indicator = 'items';
               }

               foreach ($add_items as $item) {
                 $manual    = false;
                 $table_row = '<tr class="sortable item">';
                 $table_row .= '<td class="dragger">';
                 if ($item['qty'] == '' || $item['qty'] == 0) {
                   $item['qty'] = 1;
                 }
                 if(!isset($is_proposal)){
                  $estimate_item_taxes = get_estimate_item_taxes($item['id']);
                } else {
                  $estimate_item_taxes = get_proposal_item_taxes($item['id']);
                }
                if ($item['id'] == 0) {
                 $estimate_item_taxes = $item['taxname'];
                 $manual              = true;
               }
               
              
          
               $table_row .= form_hidden('' . $items_indicator . '[' . $i . '][itemid]', $item['id']);
               $amount = $item['rate'] * $item['qty'];
               $amount = app_format_number($amount);
               // order input
               $table_row .= '<input type="hidden" class="order" name="' . $items_indicator . '[' . $i . '][order]">';
                $table_row .= '<input type="hidden" value="' . $item['item_id'] . '"  name="' . $items_indicator . '[' . $i . '][item_id]">';
               $table_row .= '</td>';
               $table_row .= '<td class="bold description"><textarea name="' . $items_indicator . '[' . $i . '][description]" class="form-control" rows="5">' . clear_textarea_breaks($item['description']) . '</textarea></td>';
               $table_row .= '<td><textarea name="' . $items_indicator . '[' . $i . '][long_description]" class="form-control" rows="5">' . clear_textarea_breaks($item['long_description']) . '</textarea></td>';
               $table_row .= render_custom_fields_items_table_in($item,$items_indicator.'['.$i.']');
               $table_row .= '<td><input type="number" min="0" onblur="calculate_total();" onchange="calculate_total();" data-quantity name="' . $items_indicator . '[' . $i . '][qty]" value="' . $item['qty'] . '" class="form-control">';
               $unit_placeholder = '';
               if(!$item['unit']){
                 $unit_placeholder = _l('unit');
                 $item['unit'] = '';
               }
               $table_row .= '<input type="text" placeholder="'.$unit_placeholder.'" name="'.$items_indicator.'['.$i.'][unit]" class="form-control input-transparent text-right" value="'.$item['unit'].'">';
               $table_row .= '</td>';
               $table_row .= '<td class="rate"><input type="number" data-toggle="tooltip" title="' . _l('numbers_not_formatted_while_editing') . '" onblur="calculate_total();" onchange="calculate_total();" name="' . $items_indicator . '[' . $i . '][rate]" value="' . $item['rate'] . '" class="form-control"></td>';
              // $table_row .= '<td class="taxrate">' . $this->misc_model->get_taxes_dropdown_template('' . $items_indicator . '[' . $i . '][taxname][]', $estimate_item_taxes, (isset($is_proposal) ? 'proposal' : 'estimate'), $item['id'], true, $manual) . '</td>';
             
               //$table_row .= '<td class="desconto"><input  type="number" data-toggle="tooltip"  title="' . _l('desconto') . '" onblur="calculate_total();" onchange="calculate_total();" data-desconto_valor name="' . $items_indicator . '[' . $i . '][desconto_valor]" value="' . $item['desconto_valor'] . '" class="form-control"></td>';
                $table_row .= '<td class="desconto">'
                                 . '<div class="row">'
                                . '<div class="col-md-12">'
                                . '<input  type="number" data-toggle="tooltip"  title="' . _l('desconto') . '" onblur="calculate_total();" onchange="calculate_total();" data-desconto_valor name="' . $items_indicator . '[' . $i . '][desconto_valor]" value="' . $item['desconto_valor'] . '" class="form-control">'
                               
                                . '</div>'
                                . '<div class="col-md-12">'
                                . '   <select name="' . $items_indicator . '[' . $i . '][destino_desconto]"  class="form-control">'
                                . '   <option value="'.$item['destino_desconto'].'">'.$item['destino_desconto'].'</option>'
                                . '   <option value="AMBOS">AMBOS</option>'
                                . '   <option value="EMPRESA">EMPRESA</option>'
                                . '   <option value="MÉDICO">MÉDICO</option>'
                                . '   </select>'
                                . '</div>'
                                . '</div>'
                                . '</td>';
                
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
               <td><span class="bold"><?php echo _l('estimate_subtotal'); ?> :</span>
               </td>
               <td class="subtotal">
               </td>
            </tr>
         <!--   <tr id="discount_area">
               <td>
                  <div class="row">
                     <div class="col-md-7">
                        <span class="bold"><?php echo _l('estimate_discount'); ?></span>
                     </div>
                     <div class="col-md-5">
                        <div class="input-group" id="discount-total">

                           <input type="number" value="<?php echo (isset($estimate) ? $estimate->discount_percent : 0); ?>" class="form-control pull-left input-discount-percent<?php if(isset($estimate) && !is_sale_discount($estimate,'percent') && is_sale_discount_applied($estimate)){echo ' hide';} ?>" min="0" max="100" name="discount_percent">

                           <input type="number" data-toggle="tooltip" data-title="<?php echo _l('numbers_not_formatted_while_editing'); ?>" value="<?php echo (isset($estimate) ? $estimate->discount_total : 0); ?>" class="form-control pull-left input-discount-fixed<?php if(!isset($estimate) || (isset($estimate) && !is_sale_discount($estimate,'fixed'))){echo ' hide';} ?>" min="0" name="discount_total">

                           <div class="input-group-addon">
                              <div class="dropdown">
                                 <a class="dropdown-toggle" href="#" id="dropdown_menu_tax_total_type" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                 <span class="discount-total-type-selected">
                                  <?php if(!isset($estimate) || isset($estimate) && (is_sale_discount($estimate,'percent') || !is_sale_discount_applied($estimate))) {
                                    echo '%';
                                    } else {
                                    echo _l('discount_fixed_amount');
                                    }
                                    ?>
                                 </span>
                                 <span class="caret"></span>
                                 </a>
                                 <ul class="dropdown-menu" id="discount-total-type-dropdown" aria-labelledby="dropdown_menu_tax_total_type">
                                   <li>
                                    <a href="#" class="discount-total-type discount-type-percent<?php if(!isset($estimate) || (isset($estimate) && is_sale_discount($estimate,'percent')) || (isset($estimate) && !is_sale_discount_applied($estimate))){echo ' selected';} ?>">%</a>
                                  </li>
                                  <li>
                                    <a href="#" class="discount-total-type discount-type-fixed<?php if(isset($estimate) && is_sale_discount($estimate,'fixed')){echo ' selected';} ?>">
                                      <?php echo _l('discount_fixed_amount'); ?>
                                    </a>
                                  </li>
                                 </ul>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </td>
               <td class="discount-total"></td>
            </tr> -->
            <tr>
               <td>
                  <div class="row">
                     <div class="col-md-7">
                        <span class="bold"><?php echo _l('estimate_adjustment'); ?></span>
                     </div>
                     <div class="col-md-5">
                        <input type="number" data-toggle="tooltip" data-title="<?php echo _l('numbers_not_formatted_while_editing'); ?>" value="<?php if(isset($estimate)){echo $estimate->adjustment; } else { echo 0; } ?>" class="form-control pull-left" name="adjustment">
                     </div>
                  </div>
               </td>
               <td class="adjustment"></td>
            </tr>
            <tr>
               <td><span class="bold"><?php echo _l('estimate_total'); ?> :</span>
               </td>
               <td class="total">
               </td>
            </tr>
         </tbody>
      </table>
   </div>
   <div id="removed-items"></div>
</div>
