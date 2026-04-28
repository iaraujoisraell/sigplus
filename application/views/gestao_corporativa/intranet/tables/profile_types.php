<?php
defined('BASEPATH') or exit('No direct script access allowed');
$aColumns = [
    'name as titulo',
    'id as id',
];

$sIndexColumn = 'id';
$sTable       = db_prefix() . '_intranet_profile_types';
$where  = [];
$join = [];

$additionalSelect = [];


$empresa_id = $this->ci->session->userdata('empresa_id');


array_push($where, 'AND ' . db_prefix() . '_intranet_profile_types.deleted = 0');
array_push($where, 'AND ' . db_prefix() . '_intranet_profile_types.empresa_id = ' . $empresa_id);


$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, $additionalSelect, $GroupBy);

$output  = $result['output'];

$rResult = $result['rResult'];




foreach ($rResult as $aRow) {

    $row = [];

    $row[] = $aRow['titulo'];

    $options = '';
    if(has_permission_intranet('tipos_usuarios', '', 'edit')) { 
    $options .= '<a class="btn btn-info btn-xs mleft5" data-toggle="tooltip" onclick="add_type('.$aRow['id'].');"><i class="fa fa-pencil"></i></a>';
    }
    
    if(has_permission_intranet('tipos_usuarios', '', 'delete')) { 
    $options .= '<a class="btn btn-danger btn-xs mleft5 _delete" data-toggle="tooltip" href="'.base_url().'gestao_corporativa/Profile_type/delete/'.$aRow['id'].'"><i class="fa fa-trash"></i></a>';
    }
    $row[] = $options;

    $output['aaData'][] = $row;


}



