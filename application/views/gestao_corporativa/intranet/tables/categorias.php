<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [];
$where = [];

$aColumns = array_merge($aColumns, [
    db_prefix() . '_intranet_categorias.titulo as titulo',
    'responsavel as responsavel',
    db_prefix() . '_intranet_categorias.data_cadastro as date',
    db_prefix() . '_intranet_categorias.id as campos',
    db_prefix() . '_intranet_categorias.linked_to as linked_to',
    db_prefix() . '_intranet_categorias.orientacoes as orientacoes',
    db_prefix() . '_intranet_categorias.description as description'
        ]);
$sIndexColumn = 'id';
$sTable = db_prefix() . '_intranet_categorias';

$join = [
    'LEFT JOIN ' . db_prefix() . 'departments ON ' . db_prefix() . 'departments.departmentid = ' . db_prefix() . '_intranet_categorias.responsavel',
];

$additionalSelect = [
    'id',
    'hash',
    'anonimo',
    'atuantes as atuantes',
    'user_cadastro as user',
    'active as active',
    'name as name',
    'portal as portal'
];

$empresa_id = $this->ci->session->userdata('empresa_id');

array_push($where, 'AND ' . db_prefix() . '_intranet_categorias.deleted = 0');
if ($this->ci->input->post('rel_type')) {
    $rel_type = $this->ci->input->post('rel_type');
    $vowels = array(".");
    $tabela = str_replace($vowels, "", $rel_type);
    array_push($where, "AND " . db_prefix() . "_intranet_categorias.rel_type = '$rel_type'");
    $permission = $tabela;
    if ($permission == 'ra_atendimento_rapido') {
        $permission = 'atendimento';
    }
}

array_push($where, ' AND ' . db_prefix() . '_intranet_categorias.empresa_id = ' . $empresa_id);

//$where   = [];
$orderby = " order by ordem asc";

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


    $row[] = strtoupper($aRow['titulo']) . '<input type="hidden" value="' . $aRow['id'] . '" id="categoria_ordem">';

    if ($rel_type == 'atendimento' || $rel_type == 'ra_atendimento_rapido') {
        $row[] = date("d/m/Y", strtotime($aRow['date'])) . ' - ' . get_staff_full_name($aRow['user']);
    }
    if ($rel_type == 'r.o' || $rel_type == 'workflow' || $rel_type == 'cdc') {
        $row[] = $aRow['name'];
    }
    $disabled = '';
    $msg = '';
    if (!has_permission_intranet($permission . '_settings', '', 'edit_categoria')) {
        $disabled = 'disabled';
        $caminho = '#';
        $msg = 'data-title="Você não tem permissão para editar a categoria."';
    } else {
        $caminho = base_url('gestao_corporativa/Workflow/fluxos' . '?id=' . $aRow['id']);
    }

    if ($rel_type == 'workflow') {
        $row[] = '<a class="btn btn-default btn-icon" data-toggle="tooltip" ' . $disabled . ' ' . $msg . ' target="blanck" href="' . $caminho . '" ><i class="fa fa-list"></i> Fluxos</a>';
    }
    if ($rel_type == 'api') {
        $row[] = strtoupper($aRow['description']);
    }
    if ($rel_type == 'r.o') {
        $this->ci->load->model('Registro_ocorrencia_model');
        $atuantes = $this->ci->Registro_ocorrencia_model->get_categoria_atuantes($aRow['atuantes']);
        $permissoes = '';
        foreach ($atuantes as $atuante) {
            $permissoes .= '<span  class="label label-sm label-success">' . $atuante['titulo'] . '</span> ';
        }
        $row[] = $permissoes;
    }

    if (has_permission_intranet($permission . '_settings', '', 'edit_categoria')) {
        $caminho = ' data-toggle="modal" onclick="Campos' . $tabela . '(' . $aRow['id'] . ');" ';
    } else {
        $caminho = '';
    }

    if ($rel_type != 'api') {
        if ($rel_type != 'autosservico' && $rel_type != 'links' && $rel_type != 'links_destaque') {
            $row[] = '<a class="btn btn-default btn-icon" data-toggle="tooltip" ' . $msg . ' ' . $caminho . ' ' . $disabled . '><i class="fa fa-list"></i> Campos</a>';
        }
    } else {
        $row[] = '<a class="btn btn-default btn-icon" data-toggle="tooltip" data-toggle="modal" onclick="Campos' . $tabela . '(' . $aRow['id'] . ', 1);" ><i class="fa fa-list"></i> Entrada</a>'
                . '<a class="btn btn-default btn-icon" data-toggle="tooltip" data-toggle="modal" onclick="Campos' . $tabela . '(' . $aRow['id'] . ', 2);"><i class="fa fa-list"></i> Saída</a>';
    }
    if ($rel_type == 'autosservico') {
        $auto = $aRow['linked_to'];
        if ($auto == 'at_cadastral') {
            $auto = 'Atualização Cadastral';
        } elseif ($auto == 'menu_boletos') {
            $auto = 'Menu Boletos';
        } elseif ($auto == 'menu_irpf') {
            $auto = 'Menu IRPF';
        } elseif ($auto == 'gera_cooparticipacao') {
            $auto = 'Botão visualizar competência Cooparticipação';
        } elseif ($auto == 'gera_boleto') {
            $auto = 'Botão gerar boleto';
        } elseif ($auto == 'gera_historico_boleto') {
            $auto = 'Botão consultar Histórico de Pagamento';
        }
        $row[] = $auto;
        $row[] = $aRow['orientacoes'];
    }
    $checked = '';
    if ($aRow['active'] == 1) {
        $checked = 'checked';
    }

    $row[] = '<div class="onoffswitch">'
            . '<input type="checkbox" ' . $checked . ' class="onoffswitch-checkbox"  id="active_' . $aRow['id'] . '" onchange="update_active(' . "'" . $aRow['id'] . "'" . ')">
                <label class="onoffswitch-label" for="active_' . $aRow['id'] . '"></label>
                </div>';
    $options = '';
    if (has_permission_intranet($permission . '_settings', '', 'edit_categoria')) {
        $options = '<a class="btn btn-info btn-xs mleft"  data-toggle="tooltip" title="Editar Categoria"  onclick="Update_categoria' . $tabela . '(' . $aRow['id'] . ');"><i class="fa fa-pencil"></i></a>';
    }
    if ($rel_type == 'r.o') {
        if ($aRow['anonimo'] == 1) {
            $options .= ' <a class="btn btn-success btn-xs mleft"  data-toggle="tooltip" title="Link Externo"  target="_blank" href="' . base_url('Registro_ocorrencia/index/' . $aRow['hash']) . '"><i class="fa fa-link"></i></a>';
        }
    }
    if (has_permission_intranet($permission . '_settings', '', 'delete_categoria')) {
        $options .= '<a class="btn btn-danger btn-xs mleft5 _delete" data-toggle="tooltip" title="Deletar Categoria" onclick="delete_categoria' . $tabela . '(' . $aRow['id'] . ');"><i class="fa fa-trash"></i></a>';
    }
    if ($rel_type == 'workflow') {
        if (has_permission_intranet($permission . '_settings', '', 'duplicate_categoria')) {
            $options .= '<a class="btn btn-warning btn-xs mleft5" data-toggle="tooltip" title="Duplicar Categoria" onclick="duplicate_categoria' . $tabela . '(' . $aRow['id'] . ');"><i class="fa fa-clone"></i></a>';
        }
        $options .= '<a class="btn btn-default btn-xs mleft5" data-toggle="tooltip" title="Documentos" onclick="Doctos' . $tabela . '(' . $aRow['id'] . ');"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>';
    }
    if ($rel_type == 'cdc') {
        $options .= '<a class="btn btn-default btn-xs mleft5" data-toggle="tooltip" title="Matriz" onclick="Doctos' . $tabela . '(' . $aRow['id'] . ');"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>';
    }
    if ($rel_type == 'atendimento') {
        if ($aRow['portal'] == 1) {
        $options .= '<a class="btn btn-default btn-xs mleft5" data-toggle="tooltip" title="PORTAL" onclick="Portal' . $tabela . '(' . $aRow['id'] . ');"><i class="fa fa-user" aria-hidden="true"></i><i class="fa fa-globe" aria-hidden="true"></i></a>';
        }
    }
    $row[] = $options;

    $output['aaData'][] = $row;
}
