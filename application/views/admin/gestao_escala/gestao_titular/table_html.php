<?php defined('BASEPATH') or exit('No direct script access allowed');


        
    $table_data = [
            
          'Competência',
          'Unidade',  
          'Setor',
          'Hora Início',
          'Hora Fim',
          'Qtde Plantão',
          'Log',
          'opções',
    ];


$table_data = hooks()->apply_filters('invoices_table_columns', $table_data);
render_datatable($table_data, (isset($class) ? $class : 'escala_gestao'));




?>
