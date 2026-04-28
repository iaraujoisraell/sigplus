<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
    'titulo as titulo',
    'id as id',
    'user_cadastro as user_cadastro',
    'data_cadastro as date',
    'abas as abas',
];

$sIndexColumn = 'id';

$sTable = db_prefix() . '_intranet_registro_ocorrencia_atuantes';

$where = [];

$join = [];

$additionalSelect = [];

$empresa_id = $this->ci->session->userdata('empresa_id');

array_push($where, 'AND ' . db_prefix() . '_intranet_registro_ocorrencia_atuantes.deleted = 0');
array_push($where, 'AND ' . db_prefix() . '_intranet_registro_ocorrencia_atuantes.empresa_id = ' . $empresa_id);

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, $additionalSelect, $GroupBy);

$output = $result['output'];

$rResult = $result['rResult'];

foreach ($rResult as $aRow) {

    $row = [];

    $row[] = $aRow['titulo'];

    $permissions = explode(',', $aRow['abas']);
    $permissoes = '';
    foreach ($permissions as $key) {
        $permissoes .= '<span  class="label label-sm label-info">' . $key . '</span>';
    }
    $row[] = $permissoes;
    $del = '';
    if (has_permission_intranet('ro_settings', '', 'delete_atuante') || is_admin()) {
        $del .= '<a class="btn btn-info btn-xs mleft5" data-toggle="tooltip" onclick="edit_atuante_form(' . $aRow['id'] . ');"><i class="fa fa-pencil"></i></a>';
    }
    if (has_permission_intranet('ro_settings', '', 'edit_atuante') || is_admin()) {
        $disabled = '';
        $msg = '';
        $this->ci->load->model('Registro_ocorrencia_model');
        $ros = $this->ci->Registro_ocorrencia_model->atuante_in_ros($aRow['id']);
        if (count($ros) > 0) {
            $disabled = 'disabled';
            $caminho = '';
            $msg = 'data-title="Atuante vinculado a algum R.O."';
        } else {
            $caminho = 'onclick="delete_atuante(' . $aRow['id'] . ');"';
        }
        $del .= '<a class="btn btn-danger btn-xs mleft5 _deleted" ' . $msg . ' ' . $disabled . ' data-toggle="tooltip" ' . $caminho . '><i class="fa fa-trash"></i></a>';
    }
    $row[] = $del;

    $output['aaData'][] = $row;
}



