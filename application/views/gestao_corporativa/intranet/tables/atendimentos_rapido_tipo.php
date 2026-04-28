<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [];
$where = [];

if (has_permission('items', '', 'delete')) {
    $aColumns[] = '1';
}

$aColumns = array_merge($aColumns, [
    db_prefix() . '_intranet_categorias.titulo as titulo',
    db_prefix() . '_intranet_categorias.data_cadastro as date',
    db_prefix() . '_intranet_categorias.id as campos'
        ]);
$sIndexColumn = 'id';
$sTable = db_prefix() . '_intranet_categorias';

$join = [
        // 'LEFT JOIN ' . db_prefix() . 'fin_categories ON ' . db_prefix() . 'fin_categories.id = ' . db_prefix() . 'fin_plano_contas.categoria_id',
];

$additionalSelect = [
    'id',
    'user_cadastro as user',
];

$empresa_id = $this->ci->session->userdata('empresa_id');


array_push($where, 'AND ' . db_prefix() . '_intranet_categorias.deleted = 0');
array_push($where, "AND " . db_prefix() . "_intranet_categorias.rel_type = 'ra_atendimento_rapido'");
array_push($where, ' AND ' . db_prefix() . '_intranet_categorias.empresa_id = ' . $empresa_id);

//$where   = [];
$orderby = " order by id desc";

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, $additionalSelect, "", "", $orderby);
$output = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];
    
    
    
    //$_data .= ' <a data-toggle="modal" data-target="#editar' . $aRow['id'] . '">Editar</a>';
                
                //if($aRow['anonimo'] == 1){
                    //$_data .= ' | <a >Formulário Externo</a>';
                //}
                //$_data .= ' | <a href="' . base_url('gestao_corporativa/Registro_ocorrencia/delete_tipo' . '?id=' . $aRow['id']) . '" class="text-danger _delete">' . _l('delete') . '</a>';

    $row[] = strtoupper($aRow['titulo']);



    $row[] = date("d/m/Y", strtotime($aRow['date'])).' - ' . get_staff_full_name($aRow['user']);
    
    $this->ci->load->model('Registro_ocorrencia_model');
    
    $row[] = '<a class="btn btn-default btn-icon" data-toggle="modal" onclick="campos_ra('.$aRow['id'].');" ><i class="fa fa-list"></i> Visualizar</a>';
    $options = '';
    if (has_permission_intranet('registro_ocorrencia', '', 'edit_categoria') || is_admin()) {
    $options = '<a class="btn btn-info btn-xs mleft" onclick="Update_atendimento_rapido('.$aRow['id'].');"><i class="fa fa-pencil"></i></a>';
    }
    if (has_permission_intranet('registro_ocorrencia', '', 'delete_categoria') || is_admin()) {
    $options .= '<a class="btn btn-danger btn-xs mleft5 _delete" data-toggle="tooltip" onclick="delete_atendimento_rapido('.$aRow['id'].');"><i class="fa fa-trash"></i></a>';
    }
    $row[] = $options;

    $output['aaData'][] = $row;
}
