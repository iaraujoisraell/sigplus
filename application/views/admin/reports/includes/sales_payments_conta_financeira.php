<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div id="payments-conta_financeira-report" class="hide">
    <div class="row">
        <div class="col-md-6">
        <h3>Resumo de Recebimentos Por Conta Financeira</h3>
        </div>
    </div>
    <div class="col-md-3">
      <div class="form-group">
       <label for="medicos_pagamento_recebido"><?php echo _l('medico'); ?></label>
       
       <select class="selectpicker"
               data-toggle="<?php echo $this->input->get('allowed_payment_modes'); ?>"
               name="medicos_conta_financeira"
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
                       name="convenios_pagamento_conta"
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
      <table class="table table-payments-conta_financeira-report scroll-responsive">
         <thead>
            <tr>
              <th><?php echo 'Conta'; ?></th>
              <th><?php echo 'Valor'; ?></th>
            </tr>
         </thead>
         <tbody></tbody>
         <tfoot>
            <td></td>
             <td class="total"></td>
            
         </tfoot>
      </table>
   </div>
