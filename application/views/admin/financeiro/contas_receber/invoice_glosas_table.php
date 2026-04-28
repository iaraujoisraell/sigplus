<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="table-responsive">
    <br>

    
    <?php echo form_open(admin_url('invoices/gerar_nota_fiscal_apartir_lista_pagamento'),array('id'=>'record_payment_form')); ?>
      <?php echo form_hidden('invoiceid',$invoice->id); ?>
        <table class="table table-hover no-mtop">
        <thead>
            <tr>
                <th><span class="bold"><?php echo 'ID'; ?></span></th>
                <th><span class="bold"><?php echo 'Valor Glosa'; ?></span></th>
                <th><span class="bold"><?php echo 'Data'; ?></span></th>
                <th><span class="bold"><?php echo 'Info'; ?></span></th>
                <th><span class="bold"><?php echo _l('options'); ?></span></th>
            </tr>
        </thead>
        <tbody>
            <?php
             $soma_valor_glosa = 0;
            foreach($invoice->glosas as $glosa){ ?>
            <tr class="payment">
                <td>
                    <?php echo $glosa['id']; ?>
                </td>
                <td><?php echo app_format_money($glosa['amount'], ' R$ '); ?></td>
                <td><?php echo _d($glosa['date']); ?></td>
                <td>
                    <?php echo $glosa['note']; ?>
                </td>
                
                <td>
                   
                    <?php if(has_permission('payments','','delete')){ ?>
                    <a href="<?php echo admin_url('financeiro_invoices/delete_glosa/'.$glosa['id'] . '/' . $glosa['invoiceid']); ?>" class="btn btn-danger btn-icon _delete"><i class="fa fa-remove"></i></a>
                    <?php
                        }
                   // }//else if(get_staff_user_id() == '' || get_staff_user_id() == ''){
                        
                    //}
                    
                    ?>
                </td>
            </tr>
            <?php
            $soma_valor_glosa += $glosa['amount'];
            } ?>
            <tr class="payment">
                
                
                <td >Total Glosado</td>
                
                
                <td><?php echo app_format_money($soma_valor_glosa, $invoice->currency_name); ?></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        </tbody>
    </table>

     <?php echo form_close(); ?>
</div>
