<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [];
$where = [];

$aColumns = array_merge($aColumns, [
    db_prefix() . '_intranet_list_options.option as list',
    db_prefix() . '_intranet_list_options.date_created as dt_created',
    db_prefix() . '_intranet_list_options.user_created as user_created'
        ]);
$sIndexColumn = 'id';
$sTable = db_prefix() . '_intranet_list_options';

$join = [];

$additionalSelect = ['id'];

// FILTRO CONVÊNIOS
$empresa_id = $this->ci->session->userdata('empresa_id');

array_push($where, 'AND ' . db_prefix() . '_intranet_list_options.deleted = 0');
array_push($where, ' AND ' . db_prefix() . '_intranet_list_options.empresa_id = ' . $empresa_id);
array_push($where, ' AND ' . db_prefix() . '_intranet_list_options.list_id = ' . $this->ci->input->post('list_id'));

//$where   = [];
$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, $additionalSelect);

$output = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];

    $row[] = $aRow['list'];
    $row[] = date("d/m/Y H:i:s", strtotime($aRow['dt_created'])) . ' - ' . get_staff_full_name($aRow['user_created']);

    
    $row[] =  '<button class="btn btn-danger btn-xs " style="margin: 2px;" onclick="delete_list_option(' . "'" . $aRow['id'] . "'" . ')"><i class="fa fa-trash"></i></button>';

    $output['aaData'][] = $row;
}
