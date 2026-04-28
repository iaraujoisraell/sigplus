<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [];
$where = [];

$aColumns = array_merge($aColumns, [
    db_prefix() . '_intranet_acess_type.name as name',
    db_prefix() . '_intranet_acess_type.id as id',
    db_prefix() . '_intranet_acess_type.date_created as dt_created',
    db_prefix() . '_intranet_acess_type.user_created as user_created',
    db_prefix() . '_intranet_acess_type.obs as description',
    db_prefix() . '_intranet_categorias.titulo as api'
    
        ]);
$sIndexColumn = 'id';
$sTable = db_prefix() . '_intranet_acess_type';

$join = ['left join tbl_intranet_categorias on tbl_intranet_categorias.id = tbl_intranet_acess_type.api_id'];

$additionalSelect = ['id'];

// FILTRO CONVÊNIOS
$empresa_id = $this->ci->session->userdata('empresa_id');

array_push($where, 'AND ' . db_prefix() . '_intranet_acess_type.deleted = 0');
array_push($where, ' AND ' . db_prefix() . '_intranet_acess_type.empresa_id = ' . $empresa_id);

//$where   = [];
$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where);

$output = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];

    $row[] = $aRow['name'];
   // $row[] = date("d/m/Y H:i:s", strtotime($aRow['dt_created'])) . ' - ' . get_staff_full_name($aRow['user_created']);
    $row[] = $aRow['description'];
    $row[] = $aRow['api'];

    $row[] = '<button class="btn btn-danger btn-xs " style="margin: 2px;" data-toggle="tooltip" onclick="_delete(' . "'" . $aRow['id'] . "'" . ')"><i class="fa fa-trash"></i></button>';

    $output['aaData'][] = $row;
}
