<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div id="agendamentos-resumo-report" class="hide">
    <div class="row">
        <div class="col-md-6">
        <h3>Relatório de Agendamento - RESUMO</h3>
        </div>
    </div>
    <div class="col-md-3">
      <div class="form-group">
       <label for="medicos_pagamento_recebido"><?php echo _l('medico'); ?></label>
       
       <select class="selectpicker"
               data-toggle="<?php echo $this->input->get('allowed_payment_modes'); ?>"
               name="medicos_agendamento_resumo"
               data-actions-box="true"
               multiple="true"
               data-width="100%"
               data-title="<?php echo _l('dropdown_non_selected_tex'); ?>">
                   <?php
                   foreach ($medicos as $medico) {
                         $selected = 'selected';
                      
                       ?>
                     <option  value="<?php echo $medico['medicoid']; ?>" <?php echo $selected; ?>><?php echo $medico['nome_profissional']; ?></option>
              
            <?php } ?>
       </select>
    </div>
  </div>
    <div class="col-md-3">
          <div class="form-group">
               <label for="convenios"><?php echo 'Agrupar por Convênio ?'; ?></label>
               <select class="selectpicker"
                       data-toggle="<?php echo $this->input->get('allowed_payment_modes'); ?>"
                       name="convenios_agendamento_resumo"
                       data-actions-box="true"
                       data-width="100%"
                       data-title="<?php echo _l('dropdown_non_selected_tex'); ?>">
                        <option  value="" selected="true"><?php echo 'NÃO'; ?></option>
                        <option  value="1">SIM</option>
               </select>
            </div>
      </div>

  <div class="col-md-3">
      <div class="form-group">
       <label for="medicos_pagamento_recebido"><?php echo 'Tipo'; ?></label>
       <select class="selectpicker"
               data-toggle="<?php echo $this->input->get('allowed_payment_modes'); ?>"
               name="tipo_agendamento_lista"
               data-actions-box="true"
               multiple="true"
               data-width="100%"
               data-title="<?php echo _l('dropdown_non_selected_tex'); ?>">
                   <?php
                   foreach ($tipo as $tipo_ag) {
                         $selected = 'selected';
                      
                       ?>
                     <option  value="<?php echo $tipo_ag['id']; ?>" <?php echo $selected; ?>><?php echo $tipo_ag['type']; ?></option>
              
            <?php } ?>
       </select>
    </div>
  </div>  
    
    <div class="col-md-3">
      <div class="form-group">
       <label for="medicos_pagamento_recebido"><?php echo 'Status'; ?></label>
       <select class="selectpicker"
               data-toggle="<?php echo $this->input->get('allowed_payment_modes'); ?>"
               name="status_agendamento_resumo"
               data-actions-box="true"
             
               data-width="100%"
               data-title="<?php echo _l('dropdown_non_selected_tex'); ?>">
           <option  value="" selected="true">TODOS</option>    
           <option  value="ATENDIDO" >ATENDIDO</option>
           <option  value="FALTOU" >FALTOU</option>
           <option  value="CANCELADO" >CANCELADO</option>
           <option  value="CONFIRMADO" >CONFIRMADO</option>
           <option  value="EMESPERA" >EM ESPERA</option>
           <option  value="EMATENDIMENTO" >EM ATENDIMENTO</option>
              
           
       </select>
    </div>
  </div>
    
      <table class="table table-agenda-resumo-report scroll-responsive">
         <thead>
            <tr>
               <th><?php echo 'Médico'; ?></th> 
               <th><?php echo 'Quantidade'; ?></th>
               <th><?php echo 'tipo'; ?></th>
               <th><?php echo 'Convênio'; ?></th>
               
               
            </tr>
         </thead>
         <tbody></tbody>
         <tfoot>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
         </tfoot>
         <thead>
            <tr>
               <th><?php echo 'Médico'; ?></th> 
               <th><?php echo 'Quantidade'; ?></th>
               <th><?php echo 'tipo'; ?></th>
               <th><?php echo 'Convênio'; ?></th>
            </tr>
         </thead>
      </table>
   </div>
