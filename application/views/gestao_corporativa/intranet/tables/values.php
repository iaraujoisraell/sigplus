<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [];
$where  = [];

$aColumns = array_merge($aColumns, [
    db_prefix() . '_intranet_categorias_campo.nome as titulo',
    db_prefix() . '_intranet_categorias_campo.type as tipo',
    db_prefix() . '_intranet_categorias_campo.data_cadastro as date',
    db_prefix() . '_intranet_categorias_campo.user_cadastro as user',
    db_prefix() . '_intranet_categorias_campo_values.value as value',
    db_prefix() . '_intranet_categorias_campo.rel_type as rel_type',
    ]);
$sIndexColumn = 'id';
$sTable       = db_prefix() . '_intranet_categorias_campo_values';



$join = [
   'LEFT JOIN ' . db_prefix() . '_intranet_categorias_campo ON ' . db_prefix() . '_intranet_categorias_campo.id = ' . db_prefix() . '_intranet_categorias_campo_values.campo_id',
    ];
$additionalSelect = [
    'tbl_intranet_categorias_campo_values.id',
    ];

$empresa_id = $this->ci->session->userdata('empresa_id');

array_push($where, 'AND '.db_prefix() . '_intranet_categorias_campo_values.deleted = 0' );
array_push($where, 'AND '.db_prefix() . "_intranet_categorias_campo_values.rel_type = '".$this->ci->input->post('rel_type')."'" );
array_push($where, 'AND '.db_prefix() . "_intranet_categorias_campo_values.rel_id = ".$this->ci->input->post('rel_id')."" );
array_push($where, ' AND '.db_prefix() . '_intranet_categorias_campo_values.empresa_id = '.$empresa_id );

//$where   = [];
$result  = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, $additionalSelect);
$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    
    $row = [];
    
    
    $row[] = strtoupper($aRow['titulo']);
    $row[] = strtoupper($aRow['tipo']);
    $row[] = get_value($aRow['rel_type'], $aRow['value'], $aRow['tipo']);
    



    $row[] = _d($aRow['date']).' - ' . get_staff_full_name($aRow['user']);
    
    
    
    

    $output['aaData'][] = $row;
}
