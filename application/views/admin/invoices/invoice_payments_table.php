<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="table-responsive">
    <br>
<?php $payments_resumo = $this->payments_model->get_resumo_invoice_payments_por_conta_financeira($invoice->id); ?>
    <h5>Resumo</h5>
    <table class="table table-hover no-mtop">
        <thead>
            <tr>
                <th><span class="bold"><?php echo 'Conta Financeira'; ?></span></th>
                <th><span class="bold"><?php echo _l('payments_table_amount_heading'); ?></span></th>
            </tr>
        </thead>
    <tbody>
            <?php
            $soma_valor_pagamento_conta=0;           
            foreach($payments_resumo as $payment){ ?>
            <tr class="payment">
                <td>
                  <?php echo $payment['conta_financeira']; ?>
                </td>
                <td><?php echo app_format_money($payment['amount'], $invoice->currency_name); ?></td>
                
            </tr>
            <?php
            $soma_valor_pagamento_conta += $payment['amount'];
            } ?>
            <tr class="payment">
                <td>Total Pago</td>
                <td><?php echo app_format_money($soma_valor_pagamento_conta, $invoice->currency_name); ?></td>
                
            </tr>
        </tbody>
    </table>
    <br>
    <h5>Detalhado</h5>
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
                <th><span class="bold"><?php echo 'Conta'; ?></span></th>
                <th><span class="bold"><?php echo 'Caixa'; ?></span></th>
                <th><span class="bold"><?php echo _l('options'); ?></span></th>
            </tr>
        </thead>
        <tbody>
            <?php
             $nota_fiscal = get_option('nota_fiscal');
             
           
             
             
            $soma_valor_pagamento=0;
            $member = $this->staff_model->get(get_staff_user_id());
            $caixa_atual = $member->caixa_id;
            foreach($invoice->payments as $payment){ ?>
            <tr class="payment">
                <td>
                    <?php if ($nota_fiscal == 1){ ?>
                    <input type="checkbox" name="pagamento_nf[]" value="<?php echo $payment['paymentid']; ?>" class="btn-default pull-left">
                    <?php } ?>
                    <?php echo $payment['paymentid']; ?>
                    <?php echo icon_btn('payments/pdf/' . $payment['paymentid'], 'file-pdf-o','btn-default pull-right'); ?>
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
                <td><?php echo $payment['caixa']; ?></td>
                <td>
                     <?php if(has_permission('payments','','edit')){ ?>
                    <a href="<?php echo admin_url('payments/payment/'.$payment['paymentid']); ?>" class="btn btn-default btn-icon"><i class="fa fa-pencil-square-o"></i></a>
                     <?php } ?>
                    <?php if(has_permission('payments','','delete')){ ?>
                    <a href="<?php echo admin_url('invoices/delete_payment/'.$payment['paymentid'] . '/' . $payment['invoiceid']); ?>" class="btn btn-danger btn-icon _delete"><i class="fa fa-remove"></i></a>
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
    <?php if ($nota_fiscal == 1){ ?>
        <div class="pull-right mtop15">
            <button type="submit" autocomplete="off" data-loading-text="<?php echo _l('wait_text'); ?>" data-form="#record_payment_form" class="btn btn-primary"><?php echo 'Gerar Nota Fiscal'; ?></button>
        </div>    
     <?php } ?>
     <?php echo form_close(); ?>
</div>
<div class="mtop25 inline-block full-width">
                <h5 class="bold"><?php echo 'Orientações de Valores'; ?></h5>
                <?php include_once(APPPATH . 'views/admin/invoices/info_invoice_payments_table.php'); ?>
            </div>