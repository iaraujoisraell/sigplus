<?php

defined('BASEPATH') or exit('No direct script access allowed');
$table_data = [];

if (has_permission('items', '', 'delete')) {
    //$table_data[] = '<span class="hide"> - </span><div class="checkbox mass_select_all_wrap"><input type="checkbox" id="mass_select_all" data-to-table="invoice-items"><label></label></div>';
    $table_data[] = '<span class="hide"> - </span><label>#</label>';
}

$table_data = array_merge($table_data, array(
    'Unidade',
    'Setor',
    'Titular',
    'Substituto',
    'Data Validade',
    'Motivo',
    'Opções',
    
    
     ));
render_datatable($table_data, 'medicos_substitutos');
?>


