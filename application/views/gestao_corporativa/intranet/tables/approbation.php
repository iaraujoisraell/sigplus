<?php

defined('BASEPATH') or exit('No direct script access allowed');
$aColumns = [
    'id as id',
    'rel_type as type',
    'rel_id as rel_id',
    'date_created as date',
    'user_created as user',
    'status as status'
];

$sIndexColumn = 'id';

$sTable = db_prefix() . '_intranet_approbation';

$where = [];

$join = [
        'INNER JOIN ' . db_prefix() . 'departments ON ' . db_prefix() . 'departments.departmentid = ' . db_prefix() . '_intranet_approbation.departmentid_staffid',
        'INNER JOIN ' . db_prefix() . 'staff ON ' . db_prefix() . 'staff.staffid = ' . db_prefix() . 'departments.aprovador'
];

$additionalSelect = [];
$staff_id = get_staff_user_id();
$empresa_id = $this->ci->session->userdata('empresa_id');
array_push($where, ' AND ' . db_prefix() . '_intranet_approbation.empresa_id = ' . $empresa_id);
array_push($where, ' AND ' . db_prefix() . '_intranet_approbation.deleted = 0');
array_push($where, ' AND ' . db_prefix() . 'staff.staffid = ' . $staff_id);
$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, $additionalSelect, $GroupBy, [], $orderby);

$output = $result['output'];

$rResult = $result['rResult'];
//print_r($rResult); exit;
foreach ($rResult as $aRow) {

    $row = [];

    $row[] = $aRow['id'];
    if($aRow['type'] == 'workflow'){
     $row[] = '<a onclick="escolha('."'".$aRow['id']."',"."'".$aRow['type']."',"."'".$aRow['rel_id']."'".');">WORKFLOW - #'.$aRow['rel_id'] . '</a>';   
    } else {
        $row[] = '<a onclick="">' . $aRow['type'].' - '.$aRow['rel_id'] . '</a>';
    
    }
    $row[] = date("d/m/Y h:i:s", strtotime($aRow['date'])) . ' - ' . get_staff_full_name($aRow['user']);
    if($aRow['status'] == 0){
        $row[] = '<a onclick="escolha('."'".$aRow['id']."',"."'".$aRow['type']."',"."'".$aRow['rel_id']."'".');" class="label label-sm label-info">AGUARDANDO APROVAÇÃO</a>';
    } elseif($aRow['status'] == 1) {
        $row[] = '<a onclick="escolha('."'".$aRow['id']."',"."'".$aRow['type']."',"."'".$aRow['rel_id']."'".');" class="label label-sm label-success">APROVADO</a>';
    } else {
        $row[] = '<a onclick="escolha('."'".$aRow['id']."',"."'".$aRow['type']."',"."'".$aRow['rel_id']."'".');" class="label label-sm label-danger">REPROVADO</a>';
    }
    

    //$row[] = '<a class="btn btn-danger btn-xs mleft5 _delete" data-toggle="tooltip" onclick="delete_campo(' . $aRow['id'] . ');"><i class="fa fa-trash"></i></a>';

    $output['aaData'][] = $row;
}



