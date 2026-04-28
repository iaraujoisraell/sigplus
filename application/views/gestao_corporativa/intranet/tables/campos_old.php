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
    'ordem as ordem',
    'vinculo as vinculo',
    'minlength as minlength',
    'maxlength as maxlength'
];

$sIndexColumn = 'id';

$sTable = db_prefix() . '_intranet_categorias_campo';

$where = [];

$join = [];

$additionalSelect = [];
$rel_type = $this->ci->input->post('rel_type');

$vowels = array(".");
$rel_type_without_dot = str_replace($vowels, "", $rel_type);

if ($rel_type_without_dot == 'ra_atendimento_rapido') {
    $rel_type_without_dot = 'atendimento';
}
$empresa_id = $this->ci->session->userdata('empresa_id');

$categoria_id = $this->ci->input->post('categoria_id');
if ($rel_type == 'internal_request_workflow' || $rel_type == 'external_request_workflow') {
    array_push($where, "AND " . db_prefix() . "_intranet_categorias_campo.rel_type = '$rel_type'");
}
array_push($where, "AND " . db_prefix() . "_intranet_categorias_campo.categoria_id = $categoria_id");
array_push($where, "AND " . db_prefix() . "_intranet_categorias_campo.deleted = 0");
if ($rel_type == 'workflow') {
    if ($this->ci->input->post('preenchido_por')) {
        $preenchido_por = $this->ci->input->post('preenchido_por');
    } else {
        $preenchido_por = 0;
    }
    array_push($where, "AND " . db_prefix() . "_intranet_categorias_campo.preenchido_por = '$preenchido_por'");
}
if ($rel_type == 'api') {
    if ($this->ci->input->post('in_out')) {

        array_push($where, "AND " . db_prefix() . "_intranet_categorias_campo.in_out = '" . $this->ci->input->post('in_out') . "'");
    }
}
array_push($where, ' AND ' . db_prefix() . '_intranet_categorias_campo.empresa_id = ' . $empresa_id);

$orderby = " order by tbl_intranet_categorias_campo.ordem asc";
$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, $additionalSelect, $GroupBy, [], $orderby);

$output = $result['output'];

$rResult = $result['rResult'];
//print_r($rResult); exit;
foreach ($rResult as $aRow) {

    $row = [];

    if ($rel_type != 'internal_request_workflow' and $rel_type != 'external_request_workflow') {
        $row[] = '<div class="row"><div class="col-md-12"><span class="ticket-label label label-default inline-block w-100">' . $aRow['ordem'] . '</span></div>'
                . '<div class="col-md-6"><a class="btn btn-info btn-xs w-100"  style="margin: 0; " onclick="subir(' . $aRow['ordem'] . ');"><i class="fa fa-arrow-up"></i></a></div> '
                . '<div class="col-md-6"> <a class="btn btn-warning btn-xs w-100"  style="margin: 0;" onclick="descer(' . $aRow['ordem'] . ');"><i class="fa fa-arrow-down"></i></a></div></div>';
    }
    $row[] = $aRow['titulo'];

    //$permissions = explode(',', $aRow['atuantes']);
    //$permissoes = '';
    //foreach($permissions as $key){
    //    $permissoes .= '<span  class="label label-sm label-' . $label_class . '">' . $key . '</span>';
    //}
    //$row[] = $permissoes;
    if ($rel_type != 'api') {
        $type = $aRow['type'];
        if($type == 'text'){
            $type = 'Texto';
        } elseif($type == 'textarea') {
            $type = 'Caixa de texto';
        } elseif($type == 'number') {
            $type = 'Número';
        } elseif($type == 'select') {
            $type = 'Lista select';
        } elseif($type == 'multiselect') {
            $type = 'Lista multiselect';
        } elseif($type == 'date') {
            $type = 'Data';
        } elseif($type == 'time') {
            $type = 'Hora';
        } elseif($type == 'color') {
            $type = 'Cor';
        } elseif($type == 'separador') {
            $type = 'Barra';
        } elseif($type == 'setores') {
            $type = 'Setores';
        } elseif($type == 'funcionarios') {
            $type = 'Funcionários';
        } elseif($type == 'file') {
            $type = 'Arquivo';
        } elseif($type == 'list') {
            $type = 'Listas';
        }
        $row[] = $type;
        if ($rel_type != 'internal_request_workflow' and $rel_type != 'external_request_workflow') {
            $row[] = $aRow['tamanho'];
        }
    } else {
        $row[] = $aRow['vinculo'];
        $row[] = $aRow['maxlength'];
        $row[] = $aRow['minlength'];
    }

    if ($rel_type != 'internal_request_workflow' and $rel_type != 'external_request_workflow') {
        $row[] = date("d/m/Y", strtotime($aRow['date'])) . ' - ' . get_staff_full_name($aRow['user_cadastro']);
    }

    if ($rel_type == 'r.o') {
        $para = '';
        if ($aRow['para'] == 0) {
            $para = 'Notificante';
        } elseif ($aRow['para'] == 'setor_responsavel') {
            $para = 'Setor Responsável';
        } elseif ($aRow['para'] == 'classificacao') {
            $para = 'Aba de Classificação';
        } else {
            if ($aRow['para'] != '') {
                $this->ci->load->model('Registro_ocorrencia_model');
                if ($aRow['para'] != '') {
                    //$para =  $aRow['para'];
                    $atuante = $this->ci->Registro_ocorrencia_model->get_atuante($aRow['para']);
                    $para = $atuante->titulo;
                }
            }
        }

        $row[] = $para;
    }
    $options = '';

    $options .= '<a class="btn btn-info btn-xs mleft5" data-toggle="tooltip" onclick="edit_campo(' . $aRow['id'] . ');"><i class="fa fa-pencil"></i></a>';

    $options .= '<a class="btn btn-danger btn-xs mleft5 _delete" data-toggle="tooltip" onclick="delete_campo(' . $aRow['id'] . ');"><i class="fa fa-trash"></i></a>';
    if ($rel_type != 'internal_request_workflow' and $rel_type != 'external_request_workflow') {
        $row[] = $options;
    }
    $output['aaData'][] = $row;
}



