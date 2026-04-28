<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [];
$where = [];

$aColumns = array_merge($aColumns, [
    db_prefix() . '_intranet_macs.mac as date',
    db_prefix() . '_intranet_macs.description as description',
    db_prefix() . '_intranet_macs.date_limit as date_limit',
    db_prefix() . '_intranet_macs.date_created as dt_created',
    db_prefix() . '_intranet_macs.user_created as user_created',
    db_prefix() . '_intranet_macs.active as active'
        ]);
$sIndexColumn = 'id';
$sTable = db_prefix() . '_intranet_macs';

$join = [];

$additionalSelect = ['id'];

// FILTRO CONVÊNIOS
$empresa_id = $this->ci->session->userdata('empresa_id');

array_push($where, 'AND ' . db_prefix() . '_intranet_macs.deleted = 0');
array_push($where, ' AND ' . db_prefix() . '_intranet_macs.empresa_id = ' . $empresa_id);

//$where   = [];
$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, $additionalSelect);

$output = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];

    $row[] = $aRow['date'];
    $row[] = $aRow['description'];
    $row[] = _d($aRow['date_limit']);
    $row[] = date("d/m/Y H:i:s", strtotime($aRow['dt_created'])) . ' - ' . get_staff_full_name($aRow['user_created']);
    

    $checked = '';
    if ($aRow['active'] == 1) {
        $checked = 'checked';
    }
    $option = '<div class="onoffswitch">'
            . '<input type="checkbox" ' . $checked . ' class="onoffswitch-checkbox" onchange="active_mac(this, ' . "'" . $aRow['id'] . "'" . ')" id="active_' . $aRow['id'] . '">'
            . '<label class="onoffswitch-label" for="active_' . $aRow['id'] . '"></label>'
            . '</div>';
    

    $row[] = $option;
    
    $row[] = '<button class="btn btn-danger btn-xs " style="margin: 2px;" data-toggle="tooltip" onclick="delete_mac(' . "'" . $aRow['id'] . "'" . ')"><i class="fa fa-trash"></i></button>';

    $output['aaData'][] = $row;
}
