<?php

defined('BASEPATH') or exit('No direct script access allowed');
$aColumns = [];
$where = [];

$type = $this->ci->input->post('type');
$keys = [];
$keys[] = 'client';
if ($type == '2' || $type == '3' || $type == '4') {
    $keys[] = 'protocolo';
    $keys[] = 'id';
    $keys[] = 'carteirinha';
    if($type == '2' || $type == '3'){
        $keys[] = 'period';
    }
}


$filters = [];
foreach ($keys as $key) {
    $value = $this->ci->input->post($key);
    $filters[] = array("user_created" => get_staff_user_id(), "date_created" => date('Y-m-d'), "rel_type" => 'workflow', "tab" => $type, "filter" => $key, "value" => $value);
}
$filters[] = array("user_created" => get_staff_user_id(), "date_created" => date('Y-m-d'), "rel_type" => 'workflow', "tab" => 0, "filter" => 'categoria_id', "value" => is_array($this->ci->input->post('categoria_id')) ? json_encode($this->ci->input->post('categoria_id')) : $this->ci->input->post('categoria_id'));
$filters[] = array("user_created" => get_staff_user_id(), "date_created" => date('Y-m-d'), "rel_type" => 'workflow', "tab" => 0, "filter" => 'status', "value" => is_array($this->ci->input->post('status')) ? json_encode($this->ci->input->post('status')) : $this->ci->input->post('status'));
$filters[] = array("user_created" => get_staff_user_id(), "date_created" => date('Y-m-d'), "rel_type" => 'workflow', "tab" => 0, "filter" => 'departments', "value" => is_array($this->ci->input->post('departments')) ? json_encode($this->ci->input->post('departments')) : $this->ci->input->post('departments'));

$aColumns = array_merge($aColumns, [
    db_prefix() . '_intranet_workflow.id as id',
    db_prefix() . '_intranet_categorias.titulo as categoria',
    db_prefix() . 'clients.company as client_name',
    db_prefix() . "_intranet_workflow.date_created  as date",
    db_prefix() . '_intranet_workflow.status as status',
        ]);
if ($type == 2 && $type == 3) {
    array_push($aColumns, "CONCAT(staff_.firstname, ' ', staff_.lastname)   as name_atribuido");
}
if($type != 1){
    array_push($aColumns, db_prefix() . '_intranet_workflow_fluxo_andamento.concluido as concluido');
    array_push($aColumns, db_prefix() . '_intranet_categorias_fluxo.prazo as prazo_fluxo');
} else {
    array_push($aColumns, db_prefix() . 'departments.name as setor');
    array_push($aColumns, db_prefix() . '_intranet_categorias.prazo as prazo_dias');
}
$aColumns = array_merge($aColumns, [
    'tbl_intranet_registro_atendimento.protocolo as protocolo',
    db_prefix() . 'clients.numero_carteirinha as carteirinha',
        ]);
$_statuses = [];
$this->ci->load->model('Workflow_model');
$statuses = $this->ci->Workflow_model->get_status();

$filter = [];

$sIndexColumn = 'id';
$sTable = db_prefix() . '_intranet_workflow';

$join = [
    'LEFT JOIN ' . db_prefix() . '_intranet_categorias ON ' . db_prefix() . '_intranet_categorias.id = ' . db_prefix() . '_intranet_workflow.categoria_id',
    'LEFT JOIN ' . db_prefix() . '_intranet_workflow_cancel ON ' . db_prefix() . '_intranet_workflow_cancel.id = ' . db_prefix() . '_intranet_workflow.cancel_id',
    'LEFT JOIN ' . db_prefix() . '_intranet_registro_atendimento ON ' . db_prefix() . '_intranet_registro_atendimento.id = ' . db_prefix() . '_intranet_workflow.registro_atendimento_id',
    'LEFT JOIN ' . db_prefix() . 'clients ON ' . db_prefix() . 'clients.userid = ' . db_prefix() . '_intranet_registro_atendimento.client_id',
    'LEFT JOIN ' . db_prefix() . 'staff ON ' . db_prefix() . 'staff.staffid = ' . db_prefix() . '_intranet_workflow.user_created',
];
$additionalSelect = [
    //db_prefix() . '_intranet_workflow.date_created as date_created',
    db_prefix() . 'staff.firstname as staff_name',
    db_prefix() . '_intranet_workflow.user_created as user',
    db_prefix() . '_intranet_workflow.client_id as client_id',
    db_prefix() . '_intranet_workflow_cancel.cancellation as cancellation',
    db_prefix() . '_intranet_workflow.aguardando_aprovacao as aprovacao',
    'registro_atendimento_id as atendimento',
    db_prefix() . 'clients.userid as userid',
        //db_prefix() . '_intranet_workflow.date_prazo as date_prazo_end',
        //db_prefix() . '_intranet_workflow.date_prazo_client as date_prazo_end_client',
        //db_prefix() . '_intranet_categorias.prazo as dias_prazo',
        //db_prefix() . '_intranet_categorias.prazo_cliente as dias_prazo_client',
];

// Fix for big queries. Some hosting have max_join_limit
// FILTRO CONVÊNIOS
$staff = get_staff_user_id();
$empresa_id = $this->ci->session->userdata('empresa_id');
array_push($where, 'AND ' . db_prefix() . '_intranet_workflow.deleted = 0');
array_push($where, ' AND ' . db_prefix() . '_intranet_workflow.empresa_id = ' . $empresa_id);

$status = $this->ci->input->post('status');

if (is_array($status)) {
    $total_status = count($status);
} else {
    $total_status = 0;
}

$cont_status = 1;
$array_status = '';

foreach ($status as $__status) {

    if ($cont_status == $total_status) {
        $array_status .= $__status;
    } else {
        $array_status .= $__status . ',';
    }
    $cont_status++;
}

if ($total_status > 0) {
    array_push($where, 'AND tbl_intranet_workflow.status IN (' . $array_status . ')');
}
if ($this->ci->input->post('clientid')) {
    $clientid = $this->ci->input->post('clientid');
    //print_r($clientid); exit;
    $clientid = implode(',', $clientid);

    array_push($where, " AND tblclients.userid in ($clientid)");
}
// FILTRO DATA CRIAÇÃO DE / ATÉ
if ($this->ci->input->post('period') && $this->ci->input->post('period') != '-') {
    if ($this->ci->input->post('period') == 'd') {
        array_push($where, " AND tbl_intranet_workflow_fluxo_andamento.date_created between '" . date('Y-m-d') . " 00:00:01' and '" . date('Y-m-d') . " 23:59:59' ");
    } elseif ($this->ci->input->post('period') == 'm') {
        array_push($where, " AND tbl_intranet_workflow_fluxo_andamento.date_created between '" . date('Y-m-01') . " 00:00:01' and '" . date('Y-m-d') . " 23:59:59' ");
    } elseif ($this->ci->input->post('period') == 'Y') {
        array_push($where, " AND tbl_intranet_workflow_fluxo_andamento.date_created between '" . (date('Y') - 1) .'-' . date('m-d') . " 00:00:01' and '" . date('Y-m-d') . " 23:59:59' ");
    } else {
        array_push($where, " AND tbl_intranet_workflow_fluxo_andamento.date_created between '" . date('Y-m-d', strtotime("-7 days", time())) . " 00:00:01' and '" . date('Y-m-d') . " 23:59:59' ");
    }
}
// FILTRO RECEBIDO DIA/SEMANA/MES/TODOS
if ($this->ci->input->post('data_de') && $this->ci->input->post('data_ate')) {
    $data_de = $this->ci->input->post('data_de');
    $data_ate = $this->ci->input->post('data_ate');

    array_push($where, " AND tbl_intranet_workflow.date_created between '$data_de 00:00:01' and '$data_ate 23:59:59' ");
}
if ($this->ci->input->post('carteirinha')) {
    $carteirinha = $this->ci->input->post('carteirinha');

    array_push($where, " AND tblclients.numero_carteirinha like '%$carteirinha%'");
}
if ($this->ci->input->post('client')) {
    $client = $this->ci->input->post('client');

    array_push($where, " AND tblclients.company like '%$client%'");
}
if ($this->ci->input->post('protocolo')) {
    $protocolo = $this->ci->input->post('protocolo');

    array_push($where, " AND tbl_intranet_registro_atendimento.protocolo like '%$protocolo%'");
}
if ($this->ci->input->post('id')) {
    $id_ = $this->ci->input->post('id');

    array_push($where, " AND tbl_intranet_workflow.id = $id_");
}
if ($this->ci->input->post('categoria_id')) {
    $categoria_id = $this->ci->input->post('categoria_id');
    //print_r($clientid); exit;
    $categoria_id = implode(',', $categoria_id);

    array_push($where, " AND tbl_intranet_workflow.categoria_id in ($categoria_id)");
}
if ($type == 1) {
    array_push($where, ' AND ' . db_prefix() . '_intranet_workflow.user_created = ' . $staff);
    array_push($join, 'INNER JOIN ' . db_prefix() . 'departments ON ' . db_prefix() . 'departments.departmentid = ' . db_prefix() . '_intranet_categorias.responsavel');
}


if ($type == 3 || $type == 2 || $type == 4) {

    array_push($join, 'INNER JOIN ' . db_prefix() . '_intranet_workflow_fluxo_andamento ON ' . db_prefix() . '_intranet_workflow_fluxo_andamento.workflow_id = ' . db_prefix() . '_intranet_workflow.id');
    array_push($join, 'LEFT JOIN ' . db_prefix() . 'staff staff_ ON staff_.staffid = ' . db_prefix() . '_intranet_workflow_fluxo_andamento.atribuido_a');
    array_push($join, 'INNER JOIN ' . db_prefix() . '_intranet_categorias_fluxo ON ' . db_prefix() . '_intranet_categorias_fluxo.id = ' . db_prefix() . '_intranet_workflow_fluxo_andamento.fluxo_id');
    array_push($join, 'INNER JOIN ' . db_prefix() . 'departments as dep_setaff ON dep_setaff.departmentid = ' . db_prefix() . '_intranet_workflow_fluxo_andamento.department_id');
    array_push($join, 'INNER JOIN ' . db_prefix() . 'staff_departments on tblstaff_departments.departmentid = dep_setaff.departmentid');

    array_push($aColumns, 'dep_setaff.name as setor_turn_name');
    array_push($aColumns, db_prefix() . '_intranet_categorias_fluxo.objetivo as objetivo');
    array_push($aColumns, db_prefix() . '_intranet_workflow_fluxo_andamento.id as andamento_id');
    array_push($aColumns, db_prefix() . '_intranet_categorias.staff_responsavel as staff_responsavel');
    array_push($aColumns, db_prefix() . '_intranet_workflow_fluxo_andamento.atribuido_a as atribuido_a');
    
    
    array_push($aColumns, db_prefix() . '_intranet_workflow_fluxo_andamento.data_concluido as data_concluido');
    array_push($aColumns, db_prefix() . '_intranet_workflow_fluxo_andamento.data_prazo as data_prazo');
    array_push($aColumns, db_prefix() . '_intranet_workflow_fluxo_andamento.date_created as data_recebido');

    array_push($where, ' AND ' . db_prefix() . 'staff_departments.staffid = ' . $staff);
    array_push($where, ' AND ' . db_prefix() . '_intranet_workflow_fluxo_andamento.deleted  = 0');

    if ($this->ci->input->post('departments')) {
        $departments = $this->ci->input->post('departments');
        $departments = implode(',', $departments);

        array_push($where, " AND dep_setaff.departmentid in ($departments)");
    }

    if ($type == 3 || $type == 2) {
        array_push($aColumns, db_prefix() . '_intranet_workflow_fluxo_andamento.fluxo_sequencia as sequencia');
        if ($this->ci->input->post('atrasado')) {
            array_push($where, ' AND  ' . db_prefix() . '_intranet_workflow_fluxo_andamento.data_prazo < CURDATE()');
        } elseif ($this->ci->input->post('em_dia')) {
            array_push($where, ' AND  (' . db_prefix() . '_intranet_workflow_fluxo_andamento.data_prazo > CURDATE() or ' . db_prefix() . '_intranet_workflow_fluxo_andamento.data_prazo = CURDATE())');
        }
    }

    $GroupBy = " group by tbl_intranet_workflow_fluxo_andamento.id ";
    if ($type == 3) {
        array_push($where, ' AND ' . db_prefix() . '_intranet_workflow_fluxo_andamento.atribuido_a != ' . $staff);
        array_push($where, ' AND ' . db_prefix() . '_intranet_workflow.status != 3');
        array_push($where, ' AND ' . db_prefix() . '_intranet_workflow_fluxo_andamento.concluido != 1');
    } elseif ($type == 2) {
        array_push($where, ' AND ' . db_prefix() . '_intranet_workflow_fluxo_andamento.atribuido_a = ' . $staff);
        array_push($where, ' AND ' . db_prefix() . '_intranet_workflow_fluxo_andamento.concluido != 1');
    } else {
        array_push($aColumns, "MAX(" . db_prefix() . '_intranet_workflow_fluxo_andamento.fluxo_sequencia) as sequencia');

        if ($type == 4) {
            array_push($aColumns, "CONCAT(staff_.firstname, ' ', staff_.lastname)   as name_atribuido");
        }
        array_push($where, ' AND ' . db_prefix() . '_intranet_workflow_fluxo_andamento.concluido = 1');
        $GroupBy = " group by tbl_intranet_workflow.id ";
    }
}
// FILTRO
if ($this->ci->input->post('filter')) {
    $this->ci->load->model('Intranet_general_model');
    $this->ci->Intranet_general_model->update_filters($filters, 'workflow', $type);
}

//$qtd_filtro = count($filtro);

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, $additionalSelect, $GroupBy, "", $orderby, get_staff_user_id());
$output = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {


//    if (!$aRow['date_prazo_end'] || $aRow['date_prazo_end'] == '0000-00-00') {
//        if ($aRow['dias_prazo']) {
//            $edit_prazo['date_prazo'] = date('Y-m-d', strtotime("+" . $aRow['dias_prazo'] . " days", strtotime($aRow['date_created'])));
//        }
//    }
//    if (!$aRow['date_prazo_end_client'] || $aRow['date_prazo_end'] == '0000-00-00') {
//        if ($aRow['dias_prazo_client']) {
//            $edit_prazo['date_prazo_client'] = date('Y-m-d', strtotime("+" . $aRow['dias_prazo_client'] . " days", strtotime($aRow['date_created'])));
//        }
//    }
//    if ((!$aRow['date_prazo_end'] || $aRow['date_prazo_end'] == '0000-00-00') || (!$aRow['date_prazo_end_client'] || $aRow['date_prazo_end'] == '0000-00-00')) {
//        $this->ci->db->where('id', $aRow['id']);
//        $this->ci->db->update('tbl_intranet_workflow', $edit_prazo);
//    }


    $row = [];
    $chaves = $this->ci->Categorias_campos_model->get_values($aRow['id'], 'workflow', '0', true, false, true);
    $informacoes = "";
    foreach ($chaves as $info) {
        $informacoes .= '' . $info['nome_campo'] . ': ' . $info['value'] . '<br>';
    }

    $caminho = base_url('gestao_corporativa/Workflow/workflow/' . $aRow['id']);
    if ($aRow['aprovacao'] == 1 || $aRow['aprovacao'] == 3) {
        $caminho = '#';
    }

    $id = '<a href="' . $caminho . '" >#' . strtoupper($aRow['id']) . '</a>';
    if ($aRow['aprovacao'] == 1) {
        $id .= '<br><span class="label label-sm label-info">Aguardando Aprovação</span>';
    } elseif ($aRow['aprovacao'] == 2) {
        $id .= '<br><span class="label label-sm label-success">Aprovado</span>';
    } elseif ($aRow['aprovacao'] == 3) {
        $id .= '<br><span class="label label-sm label-danger">Reprovado</span>';
    }
    $row[] = $id;
    $categoria = strtoupper($aRow['categoria']);

    $row[] = $categoria;
    if ($aRow['userid']) {
        $client = '<a href="' . admin_url('clients/client/' . $aRow['userid']) . '?intranet=intranet" target="_blanck">' . $aRow['client_name'] . '</a>' . ' (' . $aRow['carteirinha'] . ')</a>';
        if ($aRow['atendimento'] != '' && $aRow['atendimento'] != 0) {
            $client .= '<p style="font-size : 11px">Protocolo: <a href="' . base_url('gestao_corporativa/atendimento/view/' . $aRow['atendimento']) . '" target="_blanck">' . $aRow['protocolo'] . '</a></p>';
        }
        $row[] = $client . $informacoes;
    } else {
        $row[] = 'Sem Cliente Vinculado<br>' . $informacoes;
    }



    $cadastro_user = '';
    if ($aRow['staff_name'] != '') {
        $cadastro_user = $aRow['staff_name'] . ' (Colaborador)';
    } elseif ($aRow['user'] == '' and $aRow['client_id'] != '') {
        $cadastro_user = $aRow['client_name'] . ' (Cliente)';
    }

    $row[] = _d($aRow['date']) . ' - ' . $cadastro_user;

    if ($aRow['status'] == 1) {
        $status = '<span class="label label-sm label-danger">ABERTO</span>';
    } elseif ($aRow['status'] == 2) {
        $status = '<span class="label label-sm label-info">EM ANDAMENTO</span>';
    } elseif ($aRow['status'] == 3) {
        $status = '<span class="label label-sm label-success">FINALIZADO</span>';
    } else {
        $status = '<span class="label label-sm label-default" data-toggle="tooltip" data-title="' . $aRow['cancellation'] . '">CANCELADO</span>';
    }

    $row[] = $status;

    if ($type == 1) {
        $row[] = strtoupper($aRow['setor']);
    }
    if ($type == 3 || $type == 2 || $type == 4) {
        if ($type != 4) {
            if ($aRow['atribuido_a'] != 0) {
                $assumido = format_members_by_ids_and_names($aRow['atribuido_a'], get_staff_full_name($aRow['atribuido_a']), true, 'staff-profile-image-small', true);

                $assumido .= '<span class="inline-block label" style="color: black;">';

                $assumido .= '';
                $assumido .= '<div class="dropdown inline-block mleft5 table-export-exclude">';
                $assumido .= '<a href="#" style="" class="dropdown-toggle text-dark" id="tableTaskStatus-' . $aRow['id'] . '" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                $assumido .= '<span data-toggle="tooltip" title="Opções"><i class="fa fa-caret-down" aria-hidden="true"></i></span>';
                $assumido .= '</a>';

                $assumido .= '<ul class="dropdown-menu dropdown-menu-right" aria-labelledby="tableTaskStatus-' . $aRow['id'] . '">';

                $assumido .= '<li>
                            <a href="#" onclick="Change_responsability(' . "'" . $aRow['andamento_id'] . "'" . ');">
                             Trocar responsável
                            </a>
                             </li>';
                $assumido .= '<li>
                            <a href="#" onclick="take_responsability(' . "'" . $aRow['andamento_id'] . "'" . ');">
                             Tirar responsabilidade
                            </a>
                             </li>';
                if ($aRow['atribuido_a'] != get_staff_user_id()) {
                    $assumido .= '<li>
                            <a href="' . base_url() . 'gestao_corporativa/Workflow/workflow/' . $aRow['id'] . '?change=true">
                             Assumir Workflow
                            </a>
                             </li>';
                }
                $assumido .= '</ul>';
                $assumido .= '</div>';

                $row[] = $assumido;
            } else {
                $outputStatus = '';
                $outputStatus .= '<span class="inline-block label" style="color: black;">';

                $outputStatus .= 'Aguardando';
                $outputStatus .= '<div class="dropdown inline-block mleft5 table-export-exclude">';
                $outputStatus .= '<a href="#" style="font-size:14px;vertical-align:middle;" class="dropdown-toggle text-dark" id="tableTaskStatus-' . $aRow['id'] . '" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                $outputStatus .= '<span data-toggle="tooltip" title="' . _l('ticket_single_change_status') . '"><i class="fa fa-caret-down" aria-hidden="true"></i></span>';
                $outputStatus .= '</a>';

                $outputStatus .= '<ul class="dropdown-menu dropdown-menu-right" aria-labelledby="tableTaskStatus-' . $aRow['id'] . '">';

                $outputStatus .= '<li>
                            <a href="' . base_url() . 'gestao_corporativa/Workflow/workflow/' . $aRow['id'] . '">
                             Assumir Workflow
                            </a>
                             </li>';
                $outputStatus .= '</ul>';
                $outputStatus .= '</div>';
                $row[] = $outputStatus;
            }
        }

        if ($aRow['concluido'] == 1) {
            if (strtotime($aRow['data_prazo']) > strtotime($aRow['data_concluido'])) {
                $info = 'Concluído';
                $classe = 'primary';
            } else {
                $info = 'Concluído com atraso';
                $classe = 'warning';
            }
        } else {
            if (strtotime(date('Y-m-d')) > strtotime($aRow['data_prazo'])) {
                $info = 'ATRASADO';
                $classe = 'danger';
            } else {
                $info = 'EM DIA';
                $classe = 'default';
            }
        }
        $atribuido = '';
        if ($aRow['name_atribuido']) {
            $atribuido = " (" . $aRow['name_atribuido'] . ")";
        }
        $status = '<p ><span class="label label-sm label-' . $classe . '">' . $info . "</span></p>";

        $row[] = $status . $aRow['sequencia'] . '° ' . $aRow['setor_turn_name'] . "$atribuido: " . $aRow['objetivo'];

        $responsability = '<p>Dias: ' . strtoupper($aRow['prazo_fluxo']) . '<br> Recebido:' . date("d/m/Y", strtotime($aRow['data_recebido'])) . '<br> Previsão: ' . _d($aRow['data_prazo']) . '<br> Concluido: ' . _d($aRow['data_concluido']);
        //$diferenca = strtotime(date('Y-m-d')) - strtotime($aRow['data_prazo']);
        //$dias = floor($diferenca / (60 * 60 * 24));
        $row[] = $responsability;
    }
    if ($type == 4) {
        $fluxo_atual = $this->ci->Workflow_model->get_fluxo_atual($aRow['id']);
        if ($fluxo_atual) {
            $fluxo_atual = $fluxo_atual->fluxo_sequencia . '° - ' . get_departamento_nome($fluxo_atual->department_id);
        }
        $row[] = $fluxo_atual;
    }
    if ($type == 1) {
        $row[] = strtoupper($aRow['prazo_dias']) . ' DIAS';
    }

    $output['aaData'][] = $row;
}
