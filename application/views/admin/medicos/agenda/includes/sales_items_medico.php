<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div id="items-medico-report" class="hide">
  <?php if($mysqlVersion && strpos($mysqlVersion->version,'5.6') !== FALSE && $sqlMode && strpos($sqlMode->mode,'ONLY_FULL_GROUP_BY') !== FALSE){ ?>
    <div class="alert alert-danger">
      Sales Report may not work properly because ONLY_FULL_GROUP_BY is enabled, consult with your hosting provider to disable ONLY_FULL_GROUP_BY in sql_mode configuration. In case the items report is working properly you can just ignore this message.
    </div>
  <?php } ?>
  <p class="mbot20 text-info"><?php echo _l('item_report_paid_invoices_notice'); ?></p>
  <?php if(count($invoices_sale_agents) > 0 ) { ?>
    <div class="row">
   <div class="col-md-4">
          <div class="form-group">
           <label for="medicos"><?php echo _l('medico'); ?></label>
           <select class="selectpicker"
                   data-toggle="<?php echo $this->input->get('allowed_payment_modes'); ?>"
                   name="medicos"
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
  
  <div class="col-md-4">
      <div class="form-group">
       <label for="convenios"><?php echo _l('convenio'); ?></label>
       <select name="convenios" class="selectpicker" multiple data-width="100%" data-none-selected-text="<?php echo _l('invoice_status_report_all'); ?>">
        <?php foreach($convenios as $convenio){ ?>
          <option value="<?php echo $convenio['id']; ?>"><?php echo $convenio['name']; ?></option>
        <?php } ?>
      </select>
    </div>
  </div>     
  <div class="col-md-4">
      <div class="form-group">
       <label for="categorias"><?php echo _l('expense_dt_table_heading_category'); ?></label>
       <select name="categorias" class="selectpicker" multiple data-width="100%" data-none-selected-text="<?php echo _l('invoice_status_report_all'); ?>">
        <?php foreach($categorias as $categoria){ ?>
          <option value="<?php echo $categoria['id']; ?>"><?php echo $categoria['name']; ?></option>
        <?php } ?>
      </select>
    </div>
  </div>      
</div>
<?php } ?>
<table class="table table-items-medico-report scroll-responsive">
  <thead>
    <tr>
      <th><?php echo 'Fatura'; ?></th>  
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
      <th><?php echo 'Total Faturado'; ?></th>  
      <th><?php echo _l('invoice_discount'); ?></th>
      <th><?php echo 'Ajustes'; ?></th>
      <th><?php echo 'Crédito Aplicado'; ?></th>
      <th><?php echo 'Valor Aberto'; ?></th>
      <th><?php echo 'Valor Recebido'; ?></th>
      <th><?php echo 'Log'; ?></th> 
    </tr>
  </thead>
  <tbody>

  </tbody>
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
      <td class="qty"></td>
      <td class="valor_procedimento"></td>
      <td class="rate_total"></td>
      <td class="desconto"></td>
      <td class="ajuste"></td>
      <td class="applied_credits"></td>
      <td class="valor_aberto"></td>
      <td class="valor_faturado_produzido"></td>  
      <td></td>
    </tr>
  </tfoot>
</table>
</div>
