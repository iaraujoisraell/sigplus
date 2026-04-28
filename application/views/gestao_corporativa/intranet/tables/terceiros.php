<?php
defined('BASEPATH') or exit('No direct script access allowed');
$aColumns = [
    'company as nome',
    'id as id',
    'razao as razao',
    'cnpj as cnpj',
    'email as email',
    'fone as fone',
];

$sIndexColumn = 'id';
$sTable       = db_prefix() . '_intranet_terceiros';
$where  = [];
$join = [];

$additionalSelect = [];


$empresa_id = $this->ci->session->userdata('empresa_id');


array_push($where, 'AND ' . db_prefix() . '_intranet_terceiros.deleted = 0');
array_push($where, 'AND ' . db_prefix() . '_intranet_terceiros.empresa_id = ' . $empresa_id);


$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, $additionalSelect, $GroupBy);

$output  = $result['output'];

$rResult = $result['rResult'];




foreach ($rResult as $aRow) {

    $row = [];

    $row[] = $aRow['nome'];

    $options = '';
    if (has_permission_intranet('terceiros', '', 'lista')) {
        $options .= '<a class="btn btn-warning btn-xs mleft5"  onclick="view_terceiros(' . $aRow['id'] . ');"><i class="fa fa-bars"></i></a>';
    }
  
    if (has_permission_intranet('terceiros', '', 'edit')) {
        $options .= '<a class="btn btn-info btn-xs mleft5" data-toggle="tooltip" onclick="add_terceiro(' . $aRow['id'] . ');"><i class="fa fa-pencil"></i></a>';
    }

    if (has_permission_intranet('terceiros', '', 'delete')) {
       // $options .= '<a class="btn btn-danger btn-xs mleft5 _delete" data-toggle="tooltip" href="' . base_url() . 'gestao_corporativa/Company/delete_terceiro/' . $aRow['id'] . '"><i class="fa fa-trash"></i></a>';
    }
    $row[] = $options;

    $output['aaData'][] = $row;
}
