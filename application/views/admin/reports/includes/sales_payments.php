<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div id="payments-received-report" class="hide">
    <div class="row">
        <div class="col-md-6">
        <h3>Relatório de Recebimentos (Tesouraria)</h3>
        </div>
    </div>
    <div class="col-md-3">
      <div class="form-group">
       <label for="medicos_pagamento_recebido"><?php echo _l('medico'); ?></label>
       
       <select class="selectpicker"
               data-toggle="<?php echo $this->input->get('allowed_payment_modes'); ?>"
               name="medicos_pagamento_recebido"
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
    <div class="col-md-3">
          <div class="form-group">
               <label for="convenios"><?php echo _l('convenio'); ?></label>
               <select class="selectpicker"
                       data-toggle="<?php echo $this->input->get('allowed_payment_modes'); ?>"
                       name="convenios_pagamento"
                       data-actions-box="true"
                       multiple="true"
                       data-width="100%"
                       data-title="<?php echo _l('dropdown_non_selected_tex'); ?>">
                           <?php
                           foreach ($convenios as $convenio) {
                              $selected = ' selected';
                               ?>
                             <option  value="<?php echo $convenio['id']; ?>" <?php echo $selected; ?>><?php echo $convenio['name']; ?></option>

                    <?php } ?>
               </select>
            </div>
      </div>
    <div class="col-md-3">
      <div class="form-group">
       <label for="medicos_pagamento_recebido"><?php echo 'Caixas'; ?></label>
       
       <select class="selectpicker"
               data-toggle="<?php echo $this->input->get('allowed_payment_modes'); ?>"
               name="caixa_pagamento_recebido"
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
      <table class="table table-payments-received-report scroll-responsive">
         <thead>
            <tr>
               <th><?php echo _l('payments_table_number_heading'); ?></th>
               <th><?php echo _l('payments_table_date_heading'); ?></th>
               <th><?php echo _l('payments_table_invoicenumber_heading'); ?></th>
               <th><?php echo 'Status'; ?></th>
               <th><?php echo _l('payments_table_client_heading'); ?></th>
                <th><?php echo 'Conta'; ?></th>
               <th><?php echo 'Convênio'; ?></th>
               <th><?php echo _l('payments_table_mode_heading'); ?></th>
               <th><?php echo _l('payment_transaction_id'); ?></th>
               <th><?php echo 'Nota Fiscal'; ?></th>
               <th><?php echo _l('note'); ?></th>
               <th><?php echo 'Valor'; ?></th>
               <th><?php echo 'Caixa'; ?></th>
               <th><?php echo 'Log'; ?></th>
            </tr>
         </thead>
         <tbody></tbody>
         <tfoot>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
             <td class="total"></td>
            <td></td>
            <td></td>
         </tfoot>
         <thead>
            <tr>
               <th><?php echo _l('payments_table_number_heading'); ?></th>
               <th><?php echo _l('payments_table_date_heading'); ?></th>
               <th><?php echo _l('payments_table_invoicenumber_heading'); ?></th>
               <th><?php echo 'Status'; ?></th>
               <th><?php echo _l('payments_table_client_heading'); ?></th>
                <th><?php echo 'Conta'; ?></th>
               <th><?php echo 'Convênio'; ?></th>
               <th><?php echo _l('payments_table_mode_heading'); ?></th>
               <th><?php echo _l('payment_transaction_id'); ?></th>
               <th><?php echo 'Nota Fiscal'; ?></th>
               <th><?php echo _l('note'); ?></th>
               <th><?php echo 'Valor'; ?></th>
              <th><?php echo 'Caixa'; ?></th>
               <th><?php echo 'Log'; ?></th>
            </tr>
         </thead>
      </table>
   </div>
