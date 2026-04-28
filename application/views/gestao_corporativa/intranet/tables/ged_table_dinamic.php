<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [];
$where = [];

$aColumns = array_merge($aColumns, [
    db_prefix() . '_intranet_ged.subject as titulo',
    db_prefix() . '_intranet_categorias.titulo as categoria',
    
        ]);

$sIndexColumn = 'id';
$sTable = db_prefix() . '_intranet_ged';

$join = [
       'LEFT JOIN ' . db_prefix() . '_intranet_categorias ON ' . db_prefix() . '_intranet_categorias.id = ' . db_prefix() . '_intranet_ged.categoria_id',
];
$additionalSelect = [
    'tbl_intranet_ged.id as id',
    db_prefix() . '_intranet_categorias.id as categoria_id',
];
// FILTRO CONVÊNIOS
$staff = get_staff_user_id();
$empresa_id = $this->ci->session->userdata('empresa_id');
$categoria_id = $this->ci->input->post('categoria_id');

array_push($where, 'AND ' . db_prefix() . '_intranet_ged.deleted = 0');
array_push($where, ' AND ' . db_prefix() . '_intranet_ged.empresa_id = ' . $empresa_id);
if($categoria_id){
   
    array_push($where, ' AND ' . db_prefix() . '_intranet_ged.categoria_id = ' . $categoria_id);
}
//print_r($where); exit;
//$where   = [];
$orderby = " order by tbl_intranet_ged.id desc";

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, $additionalSelect, "", "", $orderby);
$output = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];
    $this->ci->load->model('Ged_model');
    
    $campos = $this->ci->Ged_model->get_values_doc($aRow['id']);
    
    $row[] = strtoupper($aRow['id']);
    $row[] = '<a href="' . base_url('gestao_corporativa/Ged/registro/' . $aRow['id']) . '" target="_blanck">' . strtoupper($aRow['titulo']) . '</a>';

    $row[] = strtoupper($aRow['categoria']);
    foreach ($campos as $campo){
        $row[] = strtoupper($campo['value']);
    }
    
    $row[] = 'Vazio';
    $row[] = 'Vazio';
    $row[] = 'Vazio';
    $row[] = 'Vazio';
    $row[] = 'Vazio';
    $row[] = 'Vazio';
    $row[] = 'Vazio';
    $row[] = 'Vazio';
    $row[] = 'Vazio';
  
    $output['aaData'][] = $row;
}
