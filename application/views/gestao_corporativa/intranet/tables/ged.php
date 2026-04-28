<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [];
$where = [];

if (has_permission('items', '', 'delete')) {
    $aColumns[] = '1';
}


$aColumns = array_merge($aColumns, [
    db_prefix() . '_intranet_ged.subject as titulo',
    db_prefix() . '_intranet_categorias.titulo as categoria',
    
        ]);

if($this->ci->input->post('vinculado')) {
    //array_push($aColumns, db_prefix() . '_intranet_ged.status as hhh');
}
$sIndexColumn = 'id';
$sTable = db_prefix() . '_intranet_ged';

$join = [
       'LEFT JOIN ' . db_prefix() . '_intranet_categorias ON ' . db_prefix() . '_intranet_categorias.id = ' . db_prefix() . '_intranet_ged.categoria_id',
];
$additionalSelect = [
    'tbl_intranet_ged.id',
    
];

// Fix for big queries. Some hosting have max_join_limit
// FILTRO CONVÊNIOS
$staff = get_staff_user_id();
$empresa_id = $this->ci->session->userdata('empresa_id');
$this->ci->load->model('Registro_ocorrencia_model');
array_push($where, ' AND ' . db_prefix() . '_intranet_ged.user_created = ' . get_staff_user_id());

array_push($where, 'AND ' . db_prefix() . '_intranet_ged.deleted = 0');
array_push($where, ' AND ' . db_prefix() . '_intranet_ged.empresa_id = ' . $empresa_id);
//print_r($where); exit;
//$where   = [];
$orderby = " order by tbl_intranet_ged.id desc";

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, $additionalSelect, "", "", $orderby);
$output = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    
    
    
    
    $row = [];
    $row[] = strtoupper($aRow['id']);
    $row[] = '<a href="' . base_url('gestao_corporativa/Registro_ocorrencia/registro/' . $aRow['id']) . '" target="_blanck">' . strtoupper($aRow['titulo']) . '</a>';

    $row[] = strtoupper($aRow['categoria']);
  
    $output['aaData'][] = $row;
}
