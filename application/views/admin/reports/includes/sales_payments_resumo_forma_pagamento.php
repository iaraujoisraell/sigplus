<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div id="payments-received-report_forma_pagamento" class="hide">
     <div class="col-md-4">
      <div class="form-group">
       <label for="medicos_pagamento_recebido"><?php echo _l('medico'); ?></label>
       <select class="selectpicker"
               data-toggle="<?php echo $this->input->get('allowed_payment_modes'); ?>"
               name="medicos_resumo_forma_pagamento"
               data-actions-box="true"
               multiple="true"
               data-width="100%"
               data-title="<?php echo _l('dropdown_non_selected_tex'); ?>">
                   <?php
                    foreach ($contas_financeiras as $medico) {
                      $propria = $medico['nota_fiscal_propria'];
                      if($propria == 0){
                        $selected = 'selected';
                      }else{
                         $selected = '';  
                      }
                    ?>
                     <option  value="<?php echo $medico['id']; ?>" <?php echo $selected; ?>><?php echo $medico['nome']; ?></option>
            <?php } ?>
       </select>
       
       
    </div>
  </div>
    <div class="col-md-4">
      <div class="form-group">
       <label for="medicos_pagamento_recebido"><?php echo 'Caixas'; ?></label>
       
       <select class="selectpicker"
               data-toggle="<?php echo $this->input->get('allowed_payment_modes'); ?>"
               name="caixa_forma_pagamento"
               data-actions-box="true"
               multiple="true"
               data-width="100%"
               data-title="<?php echo _l('dropdown_non_selected_tex'); ?>">
                   <?php
                   foreach ($caixas as $caixa) {
                     
                         $selected = '';  
                      
                       ?>
                     <option  value="<?php echo $caixa['id']; ?>" <?php echo $selected; ?>><?php echo $caixa['name']; ?></option>
              
            <?php } ?>
       </select>
       
       
    </div>
  </div>
      <table class="table table-payments-received-report_forma_pagamento scroll-responsive">
         <thead>
            <tr>
               <th><?php echo _l('quantity_as_qty'); ?></th>
               <th><?php echo _l('payments_table_mode_heading'); ?></th>
               <th><?php echo _l('payments_table_amount_heading'); ?></th>
            </tr>
         </thead>
         <tbody></tbody>
         <tfoot>
            <td></td>
            <td></td>
            <td class="total"></td>
         </tfoot>
      </table>
   </div>
