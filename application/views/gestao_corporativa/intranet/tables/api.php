<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [];
$where = [];

$aColumns = array_merge($aColumns, [
    db_prefix() . '_intranet_api.name as name',
    db_prefix() . '_intranet_api.id as id',
    db_prefix() . '_intranet_api.date_created as dt_created',
    db_prefix() . '_intranet_api.user_created as user_created',
    db_prefix() . '_intranet_api.description as description'
    
        ]);
$sIndexColumn = 'id';
$sTable = db_prefix() . '_intranet_api';

$join = [];

$additionalSelect = ['id'];

// FILTRO CONVÊNIOS
$empresa_id = $this->ci->session->userdata('empresa_id');

array_push($where, 'AND ' . db_prefix() . '_intranet_api.deleted = 0');
array_push($where, ' AND ' . db_prefix() . '_intranet_api.empresa_id = ' . $empresa_id);

//$where   = [];
$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, $additionalSelect);

$output = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];

    $row[] = $aRow['name'];
    $row[] = date("d/m/Y H:i:s", strtotime($aRow['dt_created'])) . ' - ' . get_staff_full_name($aRow['user_created']);
    $row[] = $aRow['description'];
    $row[] = '<a class="btn btn-default btn-icon" data-toggle="tooltip" onclick="Campos(' . $aRow['id'] . ', '."'".'api_in'."'".');" ><i class="fa fa-list"></i> Campos </a>';
    $row[] = '<a class="btn btn-default btn-icon" data-toggle="tooltip" onclick="" ><i class="fa fa-list"></i> Campos </a>';

    $row[] = '<button class="btn btn-danger btn-xs " style="margin: 2px;" data-toggle="tooltip" onclick="_delete(' . "'" . $aRow['id'] . "'" . ')"><i class="fa fa-trash"></i></button>';

    $output['aaData'][] = $row;
}
