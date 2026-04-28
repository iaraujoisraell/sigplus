<?php

defined('BASEPATH') or exit('No direct script access allowed');
$aColumns = [
    'nome as titulo',
    'id as id',
    'user_cadastro as user_cadastro',
    'data_cadastro as date',
    'options as options',
    'preenchido_por as para',
    'tam_coluna as tamanho',
    'type as type',
    'ordem as ordem'
];

$sIndexColumn = 'id';

$sTable = db_prefix() . '_intranet_categorias_campo';

$where = [];

$join = [];

$additionalSelect = [];

$empresa_id = $this->ci->session->userdata('empresa_id');

$categoria_id = $this->ci->input->post('categoria_id');
array_push($where, 'AND ' . db_prefix() . '_intranet_categorias_campo.deleted = 0');
array_push($where, "AND " . db_prefix() . "_intranet_categorias_campo.categoria_id = $categoria_id");
array_push($where, ' AND ' . db_prefix() . '_intranet_categorias_campo.empresa_id = ' . $empresa_id);

$orderby = " order by tbl_intranet_categorias_campo.ordem asc";
$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, $additionalSelect, $GroupBy, [], $orderby);

$output = $result['output'];

$rResult = $result['rResult'];
//print_r($rResult); exit;
foreach ($rResult as $aRow) {

    $row = [];

    $row[] = '<a class="btn btn-info btn-xs mleft2 " data-toggle="tooltip" onclick="subir(' . $aRow['ordem'] . ');"><i class="fa fa-arrow-up"></i></a> '
            . $aRow['ordem']
            . ' <a class="btn btn-warning btn-xs mleft2 " data-toggle="tooltip" onclick="descer(' . $aRow['ordem'] . ');"><i class="fa fa-arrow-down"></i></a>';
    $row[] = $aRow['titulo'];

    //$permissions = explode(',', $aRow['atuantes']);
    //$permissoes = '';
    //foreach($permissions as $key){
    //    $permissoes .= '<span  class="label label-sm label-' . $label_class . '">' . $key . '</span>';
    //}
    //$row[] = $permissoes;
    $row[] = $aRow['type'];
    $row[] = $aRow['tamanho'];
    $row[] = date("d/m/Y", strtotime($aRow['date'])) . ' - ' . get_staff_full_name($aRow['user_cadastro']);

    $row[] = '<a class="btn btn-danger btn-xs mleft5 _delete" data-toggle="tooltip" onclick="delete_campo(' . $aRow['id'] . ');"><i class="fa fa-trash"></i></a>';

    $output['aaData'][] = $row;
}



