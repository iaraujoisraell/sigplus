<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [];
$where = [];

$aColumns = array_merge($aColumns, [
    db_prefix() . '_intranet_portal_tickets.date_open as date',
    db_prefix() . '_intranet_portal_tickets.dt_created as dt_created',
    db_prefix() . '_intranet_portal_tickets.user_create as user_created',
    db_prefix() . '_intranet_portal_tickets.active as active'
        ]);
$sIndexColumn = 'id';
$sTable = db_prefix() . '_intranet_portal_tickets';

$join = [];

$additionalSelect = ['id'];

// FILTRO CONVÊNIOS
$empresa_id = $this->ci->session->userdata('empresa_id');

array_push($where, 'AND ' . db_prefix() . '_intranet_portal_tickets.deleted = 0');
array_push($where, ' AND ' . db_prefix() . '_intranet_portal_tickets.empresa_id = ' . $empresa_id);

//$where   = [];
$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, $additionalSelect);

$output = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];

    $row[] = $aRow['date'];
    $row[] = date("d/m/Y H:i:s", strtotime($aRow['dt_created'])) . ' - ' . get_staff_full_name($aRow['user_created']);

    $checked = '';
    if ($aRow['active'] == 1) {
        $checked = 'checked';
    }
    $option = '<div class="onoffswitch">'
            . '<input type="checkbox" ' . $checked . ' class="onoffswitch-checkbox" onchange="active(this, ' . "'" . $aRow['id'] . "'" . ')" id="active' . $aRow['id'] . '">'
            . '<label class="onoffswitch-label" for="active' . $aRow['id'] . '"></label>'
            . '</div>';
    

    $row[] = $option;
    
    $row[] = '<button class="btn btn-danger btn-xs " style="margin: 2px;" data-toggle="tooltip" onclick="_delete(' . "'" . $aRow['id'] . "'" . ')"><i class="fa fa-trash"></i></button>';

    $output['aaData'][] = $row;
}
