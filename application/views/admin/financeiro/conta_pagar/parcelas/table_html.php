<?php defined('BASEPATH') or exit('No direct script access allowed');

$table_data = [];

        if(has_permission('items','','delete')) {
          $table_data[] = '<span class="hide"> - </span><div class="checkbox mass_select_all_wrap"><input type="checkbox" id="mass_select_all" data-to-table="clients"><label></label></div>';
           // $table_data[] = '<span class="hide"> - </span><label># Número Doc</label>';
        }

        $table_data = array_merge($table_data, array(
          '# Número Doc',  
          'Título',  
          'Plano Conta',
          'Fornecedor',
          'Centro de Custo',
          'Parcela',
          'Vl Parcela',
          'Vl Acumulado',
            'Dt Emissão',  
          'Dt Vencto',  
          'Dt Pgto',
          'Forma Pagto',
          'Status'
           ));

$table_data = hooks()->apply_filters('invoices_table_columns', $table_data);
render_datatable($table_data, (isset($class) ? $class : 'conta_pagar_parcelas'));
?>
