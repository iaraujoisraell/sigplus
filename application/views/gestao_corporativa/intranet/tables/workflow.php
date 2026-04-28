<?php

defined('BASEPATH') or exit('No direct script access allowed');
$this->ci->load->model('departments_model');
$my_departments = $this->ci->departments_model->get_staff_departments(false, true);

$aColumns = [];
$where = [];
$additionalSelect = [];
$join = [];

$type = $this->ci->input->post('type');
$post = $_POST;

$keys = [];

$keys[] = 'type';
$keys[] = 'categoria_id';
$keys[] = 'status';
$keys[] = 'departments';
$keys[] = 'table';
$keys[] = 'workflow_id';
$keys[] = 'protocolo';
$keys[] = 'client';
$keys[] = 'carteirinha';
$keys[] = 'phase';
$keys[] = 'goal';
$keys[] = 'concluido';
$keys[] = 'date_type';
$keys[] = 'period';
$keys[] = 'de';
$keys[] = 'ate';

$keys[] = 'id';

$filters = [];
foreach ($keys as $key) {
    $value = $this->ci->input->post($key);
    if ($value) {
        $filters[] = array("tab" => $type, "user_created" => get_staff_user_id(), "date_created" => date('Y-m-d'), "rel_type" => 'workflow', "filter" => $key, "value" => is_array($value) ? json_encode($value) : $value);
    }
}
$saved = [];

$by_cat = array_filter($post, function ($key) {
    return strpos($key, "campo_") === 0;
}, ARRAY_FILTER_USE_KEY);

foreach ($by_cat as $key => $value) {
    // Verifica se o índice do formulário começa com "campo_"
    if (strpos($key, "campo_") === 0 && $value != '') {
        if (is_array($value)) {
            $value = implode(',', $value);
        }
        $saved[] = array("id" => substr($key, strlen("campo_")), "value" => $value);
        //$filters[] = array("user_created" => get_staff_user_id(), "date_created" => date('Y-m-d'), "rel_type" => 'workflow', "tab" => 'cat', "filter" => $key, "value" => $value);
    }
}

$_statuses = [];
$this->ci->load->model('Workflow_model');
$statuses = $this->ci->Workflow_model->get_status();

$sIndexColumn = 'id';
$sTable = db_prefix() . '_intranet_workflow';

array_push($aColumns, db_prefix() . '_intranet_workflow.id as id');
if ($post['settings_date'] || $type == '0') {
    if ($post['date_type'] == '2' && $type != '0') {
        array_push($aColumns, db_prefix() . "_intranet_workflow_fluxo_andamento.date_received  as date");
    } else {
        array_push($aColumns, db_prefix() . "_intranet_workflow.date_created  as date");
    }
}
if ($post['settings_status'] || $type == 0) {
    array_push($aColumns, db_prefix() . '_intranet_workflow.status as status');
}

if ($post['settings_categoria_id']) {
    array_push($aColumns, db_prefix() . '_intranet_categorias.titulo as categoria');
}
if ($post['settings_client']) {
    array_push($aColumns, db_prefix() . 'clients.company as client_name');
}
if ($post['settings_staff_responsible']) {

    array_push($aColumns, db_prefix() . '_intranet_workflow_fluxo_andamento.atribuido_a as atribuido_a');
    //array_push($aColumns, "CONCAT(staff_.firstname, ' ', staff_.lastname)   as name_atribuido");
}
if ($post['settings_current_goal']) {
    array_push($aColumns, 'dep_setaff.name as setor_turn_name');
}
if ($post['settings_goal_dates'] || $post['settings_current_goal']) {
    array_push($aColumns, db_prefix() . '_intranet_workflow_fluxo_andamento.concluido as concluido');
}
if ($post['settings_time_user_created']) {
    array_push($aColumns, db_prefix() . '_intranet_workflow.date_prazo_client as dias_prazo_client');
}

if ($post['settings_categoria_id'] || $post['categoria_id']) {
    array_push($join, 'INNER JOIN ' . db_prefix() . '_intranet_categorias ON ' . db_prefix() . '_intranet_categorias.id = ' . db_prefix() . '_intranet_workflow.categoria_id');
}
if ($post['settings_client'] || $post['protocolo'] || $post['client'] || $post['carteirinha']) {
    array_push($join, 'LEFT JOIN ' . db_prefix() . '_intranet_registro_atendimento ON ' . db_prefix() . '_intranet_registro_atendimento.id = ' . db_prefix() . '_intranet_workflow.registro_atendimento_id');
    if ($post['settings_client'] || $post['client'] || $post['carteirinha']) {
        array_push($join, 'LEFT JOIN ' . db_prefix() . 'clients ON ' . db_prefix() . 'clients.userid = ' . db_prefix() . '_intranet_registro_atendimento.client_id');
    }
}
foreach ($saved as $_saved) {
    try {

        $array = explode(',', $_saved['value']);
        $numerics = array_filter($array, 'is_numeric');
        if (count($numerics) === count($array)) {
            $search = "in(" . $_saved['value'] . ")";
        }
    } catch (Exception $e) {
        $search = "like '%" . $_saved['value'] . "%'";
    }
    $join[] = 'INNER JOIN ' . db_prefix() . '_intranet_categorias_campo_values v' . $_saved['id'] . ' ON v' . $_saved['id'] . '.rel_id = ' . db_prefix() . '_intranet_workflow.id '
            . 'AND v' . $_saved['id'] . '.campo_id = ' . $_saved['id'] . ' AND v' . $_saved['id'] . ".value $search";
}

if ($post['settings_date'] || $type == 0) {
    array_push($additionalSelect, db_prefix() . '_intranet_workflow.user_created as user');
}
if ($post['settings_client']) {
    array_push($additionalSelect, 'registro_atendimento_id as atendimento');
    array_push($additionalSelect, db_prefix() . 'clients.userid as userid');
    array_push($additionalSelect, db_prefix() . '_intranet_registro_atendimento.protocolo as protocolo');
    array_push($additionalSelect, db_prefix() . 'clients.numero_carteirinha as carteirinha');
}

array_push($additionalSelect, db_prefix() . '_intranet_workflow.aguardando_aprovacao as aprovacao');
// Fix for big queries. Some hosting have max_join_limit
// FILTRO CONVÊNIOS
$staff = get_staff_user_id();
$empresa_id = $this->ci->session->userdata('empresa_id');
array_push($where, 'AND ' . db_prefix() . '_intranet_workflow.deleted = 0');
array_push($where, ' AND ' . db_prefix() . '_intranet_workflow.empresa_id = ' . $empresa_id);

$status = $this->ci->input->post('status');

if (is_array($status)) {
    $total_status = count($status);
    $status_ = implode(',', $status);
} else {
    $total_status = 0;
}

if ($total_status > 0) {
    array_push($where, 'AND tbl_intranet_workflow.status IN (' . $status_ . ')');
}

// FILTRO DATA CRIAÇÃO DE / ATÉ

if ($post['date_type'] == '2') {
    $param_between = db_prefix() . '_intranet_workflow_fluxo_andamento.date_received';
} else {
    $param_between = db_prefix() . '_intranet_workflow.date_created';
}
if ($post['period'] && $post['period'] != '-') {
    if ($post['period'] == 'd') {
        array_push($where, " AND $param_between between '" . date('Y-m-d') . " 00:00:01' and '" . date('Y-m-d') . " 23:59:59' ");
    } elseif ($post['period'] == 'm') {
        array_push($where, " AND $param_between between '" . date('Y-m-01') . " 00:00:01' and '" . date('Y-m-d') . " 23:59:59' ");
    } elseif ($post['period'] == 'Y') {
        array_push($where, " AND $param_between between '" . (date('Y') - 1) . '-' . date('m-d') . " 00:00:01' and '" . date('Y-m-d') . " 23:59:59' ");
    } else {
        array_push($where, " AND $param_between between '" . date('Y-m-d', strtotime("-7 days", time())) . " 00:00:01' and '" . date('Y-m-d') . " 23:59:59' ");
    }
}

// FILTRO RECEBIDO DIA/SEMANA/MES/TODOS
if ($post['de'] || $post['ate']) {
    if ($post['de'] && $post['ate']) {
        $data_de = $post['de'];
        $data_ate = $post['ate'];

        array_push($where, " AND $param_between BETWEEN '$data_de 00:00:01' AND '$data_ate 23:59:59' ");
    } elseif ($post['de'] && !$post['ate']) {
        $data_de = $post['de'];

        array_push($where, " AND $param_between >= '$data_de 00:00:01' ");
    } elseif (!$post['de'] && $post['ate']) {
        $data_ate = $post['ate'];

        array_push($where, " AND $param_between <= '$data_ate 23:59:59' ");
    }
}
$by = $post['carteirinha'];
if ($by) {

    array_push($where, " AND tblclients.numero_carteirinha like '%$by%'");
}
$by = $post['client'];
if ($by) {
    array_push($where, " AND tblclients.company like '%$by%'");
}
$by = $post['protocolo'];
if ($by) {
    array_push($where, " AND tbl_intranet_registro_atendimento.protocolo like '%$by%'");
}
$by = $post['workflow_id'];
if ($by) {
    array_push($where, " AND tbl_intranet_workflow.id = $by");
}
$by = $post['categoria_id'];
if ($by) {
    if (is_array($by)) {
        $by = implode(',', $by);
    }
    array_push($where, " AND tbl_intranet_workflow.categoria_id in ($by)");
}
$by = $post['goal'];
if ($by) {
    array_push($where, " AND tbl_intranet_categorias_fluxo.objetivo like '%$by%'");
}
$by = $post['phase'];
if ($by) {
    array_push($where, " AND tbl_intranet_workflow_fluxo_andamento.fluxo_sequencia like '$by'");
}

if ($type == 1) {
    array_push($where, ' AND ' . db_prefix() . '_intranet_workflow.user_created = ' . $staff);
}
if ($type == 0) {
    array_push($where, ' AND ' . db_prefix() . '_intranet_workflow.id = ' . $post['id']);
}


if (($post['settings_current_goal'] || $post['departments'] != '' || $post['phase'] || $post['goal'] || ($type == 2 || $type == 3))) {
    if ($post['concluido'] == '0') {
        $inner_left = 'INNER';
    } else {
        $inner_left = 'LEFT';
    }
    //if ($post['concluido'] == '1' && (in_array('3', $status) or in_array('4', $status))) {
    // $concluido = '';
    //} else {
    $concluido = 'and (concluido = 0 or concluiu_fluxo) ';
    //}

    array_push($join, "$inner_left JOIN " . db_prefix() . '_intranet_workflow_fluxo_andamento ON ' . db_prefix() . '_intranet_workflow_fluxo_andamento.workflow_id = ' . db_prefix() . '_intranet_workflow.id and ' . db_prefix() . '_intranet_workflow_fluxo_andamento.deleted = 0 ' . $concluido);
//    array_push($where, ' AND ' . db_prefix() . '_intranet_workflow_fluxo_andamento.fluxo_sequencia = (
//    SELECT MAX(fluxo_sequencia)
//    FROM tbl_intranet_workflow_fluxo_andamento
//    WHERE workflow_id = ' . db_prefix() . '_intranet_workflow.id
//)');
}

if (($post['goal'] || $post['phase']) || $post['departments'] != '' || ($post['settings_current_goal'] && ($type == 2 || $type == 3))) {
    array_push($join, 'INNER JOIN ' . db_prefix() . '_intranet_categorias_fluxo ON ' . db_prefix() . '_intranet_categorias_fluxo.id = ' . db_prefix() . '_intranet_workflow_fluxo_andamento.fluxo_id');
}
if (($type == 2 || $type == 3) || $post['departments'] != '') {
    $my_departments = implode(',', $my_departments);
    if ($post['concluido'] == 1 && $type == 3) {
        array_push($join, 'INNER JOIN ' . db_prefix() . 'departments as dep_setaff ON dep_setaff.departmentid = ' . db_prefix() . "_intranet_workflow_fluxo_andamento.department_id and dep_setaff.departmentid");
        array_push($where, ' AND ' . db_prefix() . "_intranet_workflow.id in (SELECT workflow_id from tbl_intranet_workflow_fluxo_andamento where workflow_id = tbl_intranet_workflow.id and tbl_intranet_workflow_fluxo_andamento.department_id in ($my_departments))");
        $GroupBy = " group by tbl_intranet_workflow.id ";
    } else {
        if ($type != 1) {
            array_push($join, 'INNER JOIN ' . db_prefix() . 'departments as dep_setaff ON dep_setaff.departmentid = ' . db_prefix() . "_intranet_workflow_fluxo_andamento.department_id and dep_setaff.departmentid in ($my_departments)");
        } else {
            array_push($join, 'INNER JOIN ' . db_prefix() . 'departments as dep_setaff ON dep_setaff.departmentid = ' . db_prefix() . "_intranet_workflow_fluxo_andamento.department_id");
        }
    }
}

if ($post['settings_current_goal'] && ($type == 2 || $type == 3)) {
    array_push($additionalSelect, db_prefix() . '_intranet_categorias_fluxo.objetivo as objetivo');
}
if ($post['settings_staff_responsible']) {
    array_push($additionalSelect, db_prefix() . '_intranet_workflow_fluxo_andamento.id as andamento_id');
}

if ($post['settings_goal_dates']) {
    array_push($additionalSelect, db_prefix() . '_intranet_workflow_fluxo_andamento.data_concluido as data_concluido');
    array_push($additionalSelect, db_prefix() . '_intranet_workflow_fluxo_andamento.data_prazo as data_prazo');
    array_push($additionalSelect, db_prefix() . '_intranet_workflow_fluxo_andamento.date_created as data_recebido');
}


if ($type == 3) {
    array_push($where, ' AND ' . db_prefix() . '_intranet_workflow_fluxo_andamento.deleted  != 1');
}
if ($post['departments']) {
    $departments = $post['departments'];
    $departments = implode(',', $departments);
    if ($departments) {
        array_push($where, " AND dep_setaff.departmentid in ($departments)");
    }
}

//$GroupBy = " group by tbl_intranet_workflow_fluxo_andamento.id ";
if ($type == 2) {
    array_push($where, ' AND ' . db_prefix() . '_intranet_workflow_fluxo_andamento.atribuido_a = ' . $staff);
    array_push($where, ' AND ' . db_prefix() . '_intranet_workflow_fluxo_andamento.concluido = 0');
}
if (($post['settings_current_goal']) && ($type == 2 || $type == 3)) {
    array_push($aColumns, db_prefix() . '_intranet_workflow_fluxo_andamento.fluxo_sequencia as sequencia');
}



$this->ci->load->model('Intranet_general_model');
$this->ci->Intranet_general_model->update_filters($filters, 'workflow', $type);

//$qtd_filtro = count($filtro);

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, $additionalSelect, $GroupBy, "", "", get_staff_user_id());
$output = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {

    $row = [];

    $caminho = base_url('gestao_corporativa/Workflow/workflow/' . $aRow['id']);
    if ($aRow['aprovacao'] == 1 || $aRow['aprovacao'] == 3) {
        $caminho = '#';
    } elseif ($type == 0) {
        $caminho = base_url('gestao_corporativa/Workflow/pdf/' . $aRow['id']);
    }

    $id = '<a target="_blank" href="' . $caminho . '" >#' . strtoupper($aRow['id']) . '</a>';
    if ($aRow['aprovacao'] == 1) {
        $id .= '<br><span class="label label-sm label-info">Aguardando Aprovação</span>';
    } elseif ($aRow['aprovacao'] == 2) {
        $id .= '<br><span class="label label-sm label-success">Aprovado</span>';
    } elseif ($aRow['aprovacao'] == 3) {
        $id .= '<br><span class="label label-sm label-danger">Reprovado</span>';
    }
    $row[] = $id;

    $cadastro_user = '';
    if ($aRow['user'] != '') {
        $cadastro_user = '(Colaborador(a):'.strtolower(get_staff_full_name($aRow['user'])).')';
    } elseif ($aRow['user'] == '' and $aRow['userid'] != '') {
        $cadastro_user = '(Cliente)';
    }

    $row[] = '<p>'._d($aRow['date']) . '</br><span>' . $cadastro_user.'</span></p>';

    if ($aRow['status'] == 1) {
        $status = '<span class="label label-sm label-danger">ABERTO</span>';
    } elseif ($aRow['status'] == 2) {
        $status = '<span class="label label-sm label-info">EM ANDAMENTO</span>';
    } elseif ($aRow['status'] == 3) {
        $status = '<span class="label label-sm label-success">FINALIZADO</span>';
    } else {
        $status = '<span class="label label-sm label-default">CANCELADO</span>';
    }
    $row[] = $status;

    if ($post['settings_categoria_id']) {
       /* $chaves = $this->ci->Categorias_campos_model->get_values($aRow['id'], 'workflow', '0', true, false, true);
        $informacoes = "";
        foreach ($chaves as $info) {
            $informacoes .= '' . $info['nome_campo'] . ': ' . $info['value'] . '<br>';
        }*/
        $categoria = strtoupper($aRow['categoria']) ;
        
        //. '<br>' . $informacoes;

      //  print_r($categoria); exit;

        $row[] = $categoria;
    }

    
    if ($post['settings_campos_chaves_id']) {
         $chaves = $this->ci->Categorias_campos_model->get_values($aRow['id'], 'workflow', '0', true, false, true);
         $informacoes_campos_chave = "";
         foreach ($chaves as $info) {
             $informacoes_campos_chave .= '' . $info['nome_campo'] . ': ' . $info['value'] . '<br>';
         }
         $campos_chave = strtoupper($informacoes_campos_chave) ;
         
         //. '<br>' . $informacoes;
 
       //  print_r($categoria); exit;
 
         $row[] = $campos_chave;
     }



    if ($post['settings_client']) {
        if ($aRow['userid']) {
            $client = '<a href="' . admin_url('clients/client/' . $aRow['userid']) . '?intranet=intranet" target="_blanck">' . $aRow['client_name'] . '</a>' . ' (' . $aRow['carteirinha'] . ')</a>';
            if ($aRow['atendimento'] != '' && $aRow['atendimento'] != 0) {
                $client .= '<p style="font-size : 11px">Protocolo: <a href="' . base_url('gestao_corporativa/atendimento/view/' . $aRow['atendimento']) . '" target="_blanck">' . $aRow['protocolo'] . '</a></p>';
            }
            $row[] = $client;
        } else {
            $row[] = 'Sem Cliente Vinculado<br>';
        }
    }


    if ($post['settings_staff_responsible']) {

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
    }
    if ($post['settings_current_goal']) {
        $current_goal = '';
        if (!in_array($aRow['status'], ['3', '4'])) {
            if ($type != '1' && $type != '0') {
                if ($aRow['concluido'] == 1) {
                    $info = 'Concluído';
                    $classe = 'primary';
                } else {
                    if (strtotime(date('Y-m-d')) > strtotime($aRow['data_prazo'])) {
                        $info = 'ATRASADO';
                        $classe = 'danger';
                    } else {
                        $info = 'EM DIA';
                        $classe = 'default';
                    }
                }
                $current_goal .= '<p ><span class="label label-sm label-' . $classe . '">' . $info . "</span></p>";

                $current_goal .= $aRow['sequencia'] . '° ';
            }

            $current_goal .= $aRow['setor_turn_name'];
            if ($type != '1' && $type != '0') {
                $atribuido = '';
                if ($aRow['name_atribuido']) {
                    $atribuido = " (" . $aRow['name_atribuido'] . ")";
                }
                $current_goal .= "$atribuido: " . $aRow['objetivo'];
            }
        }

        $row[] = $current_goal;
    }

    if ($post['settings_goal_dates']) {
        $responsability = '<p>Recebido:' . date("d/m/Y", strtotime($aRow['data_recebido'])) . '<br> Previsão: ' . _d($aRow['data_prazo']) . '<br> Concluido: ' . _d($aRow['data_concluido']);
        //$diferenca = strtotime(date('Y-m-d')) - strtotime($aRow['data_prazo']);
        //$dias = floor($diferenca / (60 * 60 * 24));
        $row[] = $responsability;
    }

    if ($post['settings_time_user_created']) {
        $row[] = _d($aRow['dias_prazo_client']);
    }

    $output['aaData'][] = $row;
}
