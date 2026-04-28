<?php defined('BASEPATH') or exit('No direct script access allowed');


        
    $table_data = [
    //  _l('the_number_sign'),
      'Horá Início',
      'Hora Fim',
      'Plantão',
      'Status', 
    ];


$table_data = hooks()->apply_filters('invoices_table_columns', $table_data);
render_datatable($table_data, (isset($class) ? $class : 'conta_pagar_parcelas'));


