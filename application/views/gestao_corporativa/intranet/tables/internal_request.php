<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [];
$where = [];

$aColumns = array_merge($aColumns, [
    db_prefix() . '_intranet_workflow_internal_request.workflow_id as workflow_id',
    db_prefix() . '_intranet_workflow_internal_request.user_created as user_created',
    db_prefix() . '_intranet_workflow_internal_request.date_created as date_created',
    db_prefix() . '_intranet_workflow_internal_request.description as description',
    db_prefix() . '_intranet_workflow_internal_request.status as status'
        ]);
$sIndexColumn = 'id';
$sTable = db_prefix() . '_intranet_workflow_internal_request';

$join = [];

$additionalSelect = ['id'];


array_push($where, ' AND ' . db_prefix() . '_intranet_workflow_internal_request.staffid = ' . get_staff_user_id());
$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, $additionalSelect, '', [], 'order by id desc');
$output = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];

    $row[] = '<a onclick="Open_internal_request('."'".$aRow['id']."'".')">#WORKFLOW #'.strtoupper($aRow['workflow_id']).'</a>';
    $row[] = get_staff_full_name($aRow['user_created']);
    $row[] = date("d/m/Y", strtotime($aRow['date_created']));

    $row[] = $aRow['description'];
    
    if($aRow['status'] == 0){
        $row[] = '<button class="btn btn-danger btn-icon" onclick="this.disabled = true; Open_internal_request('."'".$aRow['id']."'".');" > <i class="fa fa-spinner"></i> Aguardando Resposta</button>';
    } else {
        $row[] = '<button class="btn btn-success btn-icon" onclick="this.disabled = true; Open_internal_request('."'".$aRow['id']."'".');" > <i class="fa fa-check"></i> Respondido</button>';
    }
    $row[] = get_staff_full_name('user_created');
    $row[] = date("d/m/Y", strtotime($aRow['date_created']));

    $row[] = $aRow['description'];

    
    
    $output['aaData'][] = $row;
}
