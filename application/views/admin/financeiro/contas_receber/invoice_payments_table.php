<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="table-responsive">
    <br>

    
    <?php echo form_open(admin_url('invoices/gerar_nota_fiscal_apartir_lista_pagamento'),array('id'=>'record_payment_form')); ?>
      <?php echo form_hidden('invoiceid',$invoice->id); ?>
        <table class="table table-hover no-mtop">
        <thead>
            <tr>
                <th><span class="bold"><?php echo _l('payments_table_number_heading'); ?></span></th>
                <th><span class="bold"><?php echo 'Nro NF'; ?></span></th>
                <th><span class="bold"><?php echo _l('payments_table_mode_heading'); ?></span></th>
                <th><span class="bold"><?php echo 'Info'; ?></span></th>
                <th><span class="bold"><?php echo _l('payments_table_date_heading'); ?></span></th>
                <th><span class="bold"><?php echo _l('payments_table_amount_heading'); ?></span></th>
                <th><span class="bold"><?php echo 'Banco'; ?></span></th>
                
                <th><span class="bold"><?php echo _l('options'); ?></span></th>
            </tr>
        </thead>
        <tbody>
            <?php
             
            foreach($invoice->payments as $payment){ ?>
            <tr class="payment">
                <td>
                 
                    <?php echo $payment['id']; ?>
                    
                </td>
                <td>
                    <?php echo $payment['numero_nf']; ?>
                </td>
                <td><?php echo $payment['name']; ?>
                    <?php if(!empty($payment['paymentmethod'])){
                        echo ' - ' . $payment['paymentmethod'];
                    }
                    if($payment['transactionid']){
                        echo '<br />'._l('payments_table_transaction_id',$payment['transactionid']);
                    }
                    ?>
                </td>
                
                <td>
                    <?php if($payment['transactionid']){  echo 'Transação : '.$payment['transactionid']; } ?> <br>
                    <?php if($payment['numero_cartao']){ echo 'Nro Cartão : '.$payment['numero_cartao']; } ?> <br>
                    <?php if($payment['numero_parcela']){ echo 'Nro Parcela : '.$payment['numero_parcela']; } ?>
                </td>
                
                <td><?php echo _d($payment['date']); ?></td>
                <td><?php echo app_format_money($payment['amount'], $invoice->currency_name); ?></td>
                <td><?php echo $payment['conta_financeira']; ?></td>
               
                <td>
                   
                    <?php if(has_permission('payments','','delete')){ ?>
                    <a href="<?php echo admin_url('financeiro_invoices/delete_payment/'.$payment['id'] . '/' . $payment['invoiceid']); ?>" class="btn btn-danger btn-icon _delete"><i class="fa fa-remove"></i></a>
                    <?php
                        }
                   // }//else if(get_staff_user_id() == '' || get_staff_user_id() == ''){
                        
                    //}
                    
                    ?>
                </td>
            </tr>
            <?php
            $soma_valor_pagamento += $payment['amount'];
            } ?>
            <tr class="payment">
                <td>Recibo <?php echo icon_btn('payments/pdf_pgto_total/' . $payment['invoiceid'], 'file-pdf-o','btn-default pull-right'); ?></td>
                
                <td colspan="4">Total Pago</td>
                
                
                <td><?php echo app_format_money($soma_valor_pagamento, $invoice->currency_name); ?></td>
                <td></td>
                <td></td>
            </tr>
        </tbody>
    </table>

     <?php echo form_close(); ?>
</div>
