<?php defined('BASEPATH') or exit('No direct script access allowed');

$table_data = [
  _l('the_number_sign'),
  'Caixa',
  _l('note'),
  'Status',
 
  'Saldo Atual',
  'Data/Hora (Último Registro)',
  'Usuário (ÚltimoRegistro)',  
  'Opções',  
];


$custom_fields = get_custom_fields('expenses', ['show_on_table' => 1]);

foreach ($custom_fields as $field) {
  array_push($table_data, $field['name']);
}

$table_data = hooks()->apply_filters('expenses_table_columns', $table_data);
render_datatable($table_data, (isset($class) ? $class : 'expenses'), [], []);


