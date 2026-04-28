<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div id="payments-fluxo_caixa-report" class="hide">
    <div class="row">
        <div class="col-md-6">
        <h3>Fluxo de Caixa</h3>
        </div>
    </div>
 

      <table class="table table-fluxo_caixa-report scroll-responsive">
         <thead>
            <tr>
              <th><?php echo 'Tipo'; ?></th>
              <th><?php echo 'Dinheiro'; ?></th>
              <th><?php echo 'Cartão de Débito'; ?></th>
              <th><?php echo 'Cartão de Crédito'; ?></th>
              <th><?php echo 'Outros'; ?></th>
              <th><?php echo 'Totais'; ?></th>
            </tr>
         </thead>
         <tbody></tbody>
         <tfoot>
            <td>Total</td>
            <td class="saldo_dinheiro"></td>
            <td class="saldo_debito"></td>
            <td class="saldo_credito"></td>
            <td class="saldo_outros"></td>
            <td class="saldo_total"></td>
            
         </tfoot>
      </table>
    
    <br>
    <div class="row">
        <div class="col-md-6">
        <h3>Fluxo de Caixa Detalhado</h3>
        </div>
    </div>
    <table class="table dt-table table-fluxo_caixa_total-report scroll-responsive" data-order-col="2" data-order-type="desc">
         <thead>
            <tr>
              <th><?php echo 'Tipo'; ?></th>
              <th><?php echo 'Descrição'; ?></th>
              <th><?php echo 'Valor Entrada'; ?></th>
              <th><?php echo 'Valor Saída'; ?></th>
              <th><?php echo 'Forma Pagamento'; ?></th>
            </tr>
         </thead>
         <tbody></tbody>
         <tfoot>
            <td></td>
            <td ></td>
            <td class="total_entradas"></td>
            <td class="total_saidas"></td>
            <td class="saldo"></td>
            
         </tfoot>
      </table>
   </div>
