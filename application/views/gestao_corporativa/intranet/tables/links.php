<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [];
$where  = [];


$aColumns = array_merge($aColumns, [
    db_prefix() . '_intranet_link.nome as titulo',
    db_prefix() . '_intranet_link.dt_created as date',
    db_prefix() . '_intranet_link.user_create as user',
   db_prefix() . '_intranet_categorias.titulo as categoria',
    ]);
$sIndexColumn = 'id';
$sTable       = db_prefix() . '_intranet_link';



$join = [
    'INNER JOIN ' . db_prefix() . '_intranet_categorias ON ' . db_prefix() . '_intranet_categorias.id = ' . db_prefix() . '_intranet_link.categoria_id',
    
    ];

$additionalSelect = [
    'tbl_intranet_link.id',
    'tbl_intranet_link.url',
    ];

$empresa_id = $this->ci->session->userdata('empresa_id');


array_push($where, 'AND '.db_prefix() . '_intranet_link.deleted = 0' );
array_push($where, ' AND '.db_prefix() . '_intranet_link.empresa_id = '.$empresa_id );

//$where   = [];
$result  = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, $additionalSelect);
$output  = $result['output'];
$rResult = $result['rResult'];
foreach ($rResult as $aRow) {
    
    $row = [];
    
    
    $row[] = strtoupper($aRow['titulo']);



    $row[] = date("d/m/Y", strtotime($aRow['date'])).' - ' . get_staff_full_name($aRow['user']);
    
    $row[] = $aRow['url'];
    $row[] = strtoupper($aRow['categoria']);
    
    $option = '';
    $option .= '<a class="btn btn-success btn-xs " style="margin: 2px;" data-toggle="tooltip" href="'.$aRow['url'].'" target="_blank"><i class="fa fa-eye"></i></a>';
    if (has_permission_intranet('lisks_externos', '', 'edit')) { 
    $option .= '<a class="btn btn-info btn-xs " style="margin: 2px;" data-toggle="tooltip" href="'. base_url('gestao_corporativa/intra/Links/index'.'?id='. $aRow['id']) .'"><i class="fa fa-pencil"></i></a>';
    }
    if (has_permission_intranet('lisks_externos', '', 'delete')) {
    $option .= '<a class="btn btn-danger btn-xs _delete" style="margin: 2px;"data-toggle="tooltip" href="' . base_url('gestao_corporativa/intra/Links/delete_link'.'?id='. $aRow['id']) . '"><i class="fa fa-trash"></i></a>';
    }
    $row[] = $option;
    
    

    $output['aaData'][] = $row;
}
