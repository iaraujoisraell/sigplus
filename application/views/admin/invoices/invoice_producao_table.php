<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="table-responsive">
    <?php echo form_open(admin_url('invoices/atualiza_valor_repasse_fatura'),array('id'=>'record_payment_form')); ?>
      
        <table class="table table-hover no-mtop">
        <thead>
            <tr>
                <th><span class="bold"><?php echo 'Médico'; ?></span></th>
                <th><span class="bold"><?php echo 'Categoria'; ?></span></th>
                <th><span class="bold"><?php echo 'Procedimento'; ?></span></th>
                <th><span class="bold"><?php echo 'Qtde'; ?></span></th>
                <th><span class="bold"><?php echo 'Valor Procedimento'; ?></span></th>  
                <th><span class="bold"><?php echo 'Valor Desconto'; ?></span></th>  
                <th><span class="bold"><?php echo 'Valor Empresa'; ?></span></th>  
                <th><span class="bold"><?php echo 'Valor do Repasse'; ?></span></th>
              
            </tr>
        </thead>
        <tbody>
             <input type="hidden" value="<?php echo $invoice->id; ?>" name="invoiceid">
            <?php
            $soma_valor_pagamento=0;
            $soma_valor_pagamento_empresa=0;
            $soma_valor_desconto=0;
            $producao       = $this->invoices_model->get_medico_invoice_item_producao($invoice->id); 
            foreach($producao as $producao_medico){   ?>
            <input type="hidden" value="<?php echo $producao_medico['id']; ?>" name="ids[]">
           
            <tr>
                <td><?php echo $producao_medico['nome_profissional']; ?></td>
                <td><?php echo $producao_medico['grupo']; ?></td>
                <td><?php echo $producao_medico['description']; ?></td>
                <td><?php echo $producao_medico['qty']; ?></td>
                <td><?php echo app_format_money($producao_medico['rate'], 'R$'); ?></td>
                <td><input type="text" value="<?php echo $producao_medico['desconto_valor']; ?>" name="desconto_valor[]"><br> <?php echo $producao_medico['destino_desconto']; ?></td>
                <td><input type="text" value="<?php echo $producao_medico['valor_tesouraria']; ?>" name="valor_tesouraria[]"></td>
                <td><input type="text" value="<?php echo $producao_medico['valor_medico']; ?>" name="repasses_medico[]"></td>
            </tr>
            
            <?php
            $soma_valor_pagamento_empresa  += $producao_medico['valor_tesouraria'];
            $soma_valor_desconto  += $producao_medico['desconto_valor'];
            $soma_valor_pagamento += $producao_medico['valor_medico'];
            } ?>
            <tr class="payment">
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td>Total Valores</td>
                <td><?php echo app_format_money($soma_valor_desconto, $invoice->currency_name); ?></td>
                <td><?php echo app_format_money($soma_valor_pagamento_empresa, $invoice->currency_name); ?></td>
                <td><?php echo app_format_money($soma_valor_pagamento, $invoice->currency_name); ?></td>
            </tr>
        </tbody>
    </table>
        <div class="pull-right mtop15">
            <button type="submit" autocomplete="off" data-loading-text="<?php echo _l('wait_text'); ?>" data-form="#record_payment_form" class="btn btn-primary"><?php echo 'Salvar Alterações'; ?></button>
            <a href="<?php echo admin_url('invoices/recalculaRepasses/'.$invoice->id); ?>" class="btn btn-warning">Recalcular Repasse</a>
        </div>    
     <?php echo form_close(); ?>
</div>
 