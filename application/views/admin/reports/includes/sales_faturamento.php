<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div id="faturamento-report" class="hide">
    <div class="row">
        <div class="col-md-6">
        <h3>Relatório de Faturamento</h3>
        </div>
    </div>
    <div class="row">   
      <div class="col-md-3">
        <div class="form-group">
           <label for="medicos"><?php echo _l('medico'); ?></label>
           <select class="selectpicker"
                   data-toggle="<?php echo $this->input->get('allowed_payment_modes'); ?>"
                   name="medicos_faturamento"
                   data-actions-box="true"
                   multiple="true"
                   data-width="100%"
                   data-title="<?php echo _l('dropdown_non_selected_tex'); ?>">
                       <?php
                       
                       foreach ($medicos as $medico) {
                       $selected = ' selected';
                       ?>
                         <option  value="<?php echo $medico['medicoid']; ?>" <?php echo $selected; ?>><?php echo get_medico_full_name($medico['medicoid']); ?></option>
                <?php } ?>
           </select>
        </div>
      </div>      
      <div class="col-md-3">
          <div class="form-group">
               <label for="convenios"><?php echo _l('convenio'); ?></label>
               <select class="selectpicker"
                       data-toggle="<?php echo $this->input->get('allowed_payment_modes'); ?>"
                       name="convenios_faturamento"
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
               <label for="categorias"><?php echo _l('expense_dt_table_heading_category'); ?></label>
               <select class="selectpicker"
                       data-toggle="<?php echo $this->input->get('allowed_payment_modes'); ?>"
                       name="categorias_faturamento"
                       data-actions-box="true"
                       multiple="true"
                       data-width="100%"
                       data-title="<?php echo _l('dropdown_non_selected_tex'); ?>">
                           <?php
                           foreach ($categorias as $categoria) {
                              $selected = ' selected';
                               ?>
                             <option  value="<?php echo $categoria['id']; ?>" <?php echo $selected; ?>><?php echo $categoria['name']; ?></option>

                    <?php } ?>
               </select>
            </div>
      </div>  
        
      <div class="col-md-3">
          <div class="form-group">
               
               <?php
                  $selected = '';
                  
                  echo render_select('procedimento_fatura', $procedimentos, array('itemid', array('description','group_name')), 'procedimentos', $selected,array('multiple'=>'true'));
                  ?>
            </div>
                  
      </div>  
          
    </div>
      <table class="table table-faturamento-report scroll-responsive">
         <thead>
            <tr>
              <th><?php echo 'Fatura'; ?></th>
              <th><?php echo 'Registro'; ?></th>
              <th><?php echo _l('medico'); ?></th>
              <th><?php echo 'Centro Custo'; ?></th>
              <th><?php echo _l('convenio'); ?></th>  
              <th><?php echo _l('client'); ?></th>  
              <th><?php echo _l('expense_dt_table_heading_category'); ?></th>
              <th><?php echo _l('procedimento');  ?></th>
              <th><?php echo 'Dt Fatura'; ?></th>  
              <th><?php echo 'Dt Pagamento'; ?></th>  
              <th><?php echo _l('quantity_as_qty'); //total_amount ?></th>
              <th><?php echo 'Vl Proc.'; ?></th>
              <th><?php echo 'Desc.'; ?></th>
              <th><?php echo 'Total Faturado'; ?></th>  
              
             <th><?php echo 'Valor Empresa'; ?></th>
             <th><?php echo 'Repasse Médico'; ?></th>
             <th><?php echo 'Log'; ?></th> 
            </tr>
          </thead>
         <tbody></tbody>
         <tfoot>
            <tr>
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
              <td class="qty"></td>
              <td></td>
              
              <td class="desconto"></td>
              <td class="rate_total"></td>
              <td class="valor_faturado_produzido"></td>  
               <td class="valor_medico"></td>
              <td></td>
            </tr>
         </tfoot>
         <thead>
            <tr>
              <th><?php echo 'Fatura'; ?></th>  
              <th><?php echo 'Registro'; ?></th>
              <th><?php echo _l('medico'); ?></th>
              <th><?php echo 'Centro Custo'; ?></th>
              <th><?php echo _l('convenio'); ?></th>  
              <th><?php echo _l('client'); ?></th>  
              <th><?php echo _l('expense_dt_table_heading_category'); ?></th>
              <th><?php echo _l('procedimento');  ?></th>
              <th><?php echo 'Dt Fatura'; ?></th>  
              <th><?php echo 'Dt Pagamento'; ?></th>  
              <th><?php echo _l('quantity_as_qty'); //total_amount ?></th>
              <th><?php echo _l('valor_procedimento'); ?></th>
              <th><?php echo 'Desconto'; ?></th>
              <th><?php echo 'Total Faturado'; ?></th> 
             <th><?php echo 'Valor Empresa'; ?></th>
             <th><?php echo 'Repasse Médico'; ?></th>
             <th><?php echo 'Log'; ?></th> 
            </tr>
          </thead>
      </table>
   </div>
