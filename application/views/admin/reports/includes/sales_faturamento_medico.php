<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div id="faturamento-medico-report" class="hide">
    <div class="row">
        <div class="col-md-6">
        <h3>Rel. Sintético Faturamento por Médico</h3>
        </div>
    </div>
    <div class="col-md-4">
      <div class="form-group">
       <label for="medicos_pagamento_recebido"><?php echo _l('medico'); ?></label>
       
       <select class="selectpicker"
               data-toggle="<?php echo $this->input->get('allowed_payment_modes'); ?>"
               name="medicos_faturamento_medico"
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
      <table class="table table-faturamento-medico-report scroll-responsive">
         <thead>
            <tr>
              <th><?php echo 'Médico'; ?></th>
              <th><?php echo 'Procedimentos'; ?></th>
              <th><?php echo 'Total Faturado'; ?></th>
              <th><?php echo 'Total Descontos'; ?></th>
              <th><?php echo 'Valor Empresa'; ?></th>
              <th><?php echo 'Valor Médico'; ?></th>
            </tr>
         </thead>
         <tbody></tbody>
         <tfoot>
            <td></td>
            <td class="qty"></td>
            <td class="total"></td>
            <td class="desconto"></td>
            <td class="valor_empresa"></td>
            <td class="valor_medico"></td>
         </tfoot>
      </table>
   </div>
