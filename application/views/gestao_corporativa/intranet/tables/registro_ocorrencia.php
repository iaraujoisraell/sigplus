<?php

defined('BASEPATH') or exit('No direct script access allowed');

$where = [];

$type = $this->ci->input->post('type');

$keys = ['id', 'subject', 'protocolo', 'client', 'carteirinha'];
if ($type == '2') {
    $this->ci->load->model('Registro_ocorrencia_model');
    $this->ci->load->model('Tasks_model');
    $keys[] = 'atribuido_a';
} else {
    $keys[] = 'period';
}




$filters = [];
foreach ($keys as $key) {
    $value = $this->ci->input->post($key);
    $filters[] = array("user_created" => get_staff_user_id(), "date_created" => date('Y-m-d'), "rel_type" => 'r.o', "tab" => $type, "filter" => $key, "value" => is_array($value) ? json_encode($value) : $value);
}
$filters[] = array("user_created" => get_staff_user_id(), "date_created" => date('Y-m-d'), "rel_type" => 'r.o', "tab" => 0, "filter" => 'user_created', "value" => is_array($this->ci->input->post('user_created')) ? json_encode($this->ci->input->post('user_created')) : $this->ci->input->post('user_created'));
$filters[] = array("user_created" => get_staff_user_id(), "date_created" => date('Y-m-d'), "rel_type" => 'r.o', "tab" => 0, "filter" => 'categoria_id', "value" => is_array($this->ci->input->post('categoria_id')) ? json_encode($this->ci->input->post('categoria_id')) : $this->ci->input->post('categoria_id'));
$filters[] = array("user_created" => get_staff_user_id(), "date_created" => date('Y-m-d'), "rel_type" => 'r.o', "tab" => 0, "filter" => 'status', "value" => is_array($this->ci->input->post('status')) ? json_encode($this->ci->input->post('status')) : $this->ci->input->post('status'));
$filters[] = array("user_created" => get_staff_user_id(), "date_created" => date('Y-m-d'), "rel_type" => 'r.o', "tab" => 0, "filter" => 'departments', "value" => is_array($this->ci->input->post('departments')) ? json_encode($this->ci->input->post('departments')) : $this->ci->input->post('departments'));

$aColumns = [
    db_prefix() . '_intranet_registro_ocorrencia.id as id',
    db_prefix() . '_intranet_registro_ocorrencia.subject as titulo',
    db_prefix() . '_intranet_categorias.titulo as categoria',
    db_prefix() . '_intranet_registro_ocorrencia.date_created as date_created',
];

$typeColumns = [
    1 => [
        db_prefix() . '_intranet_registro_ocorrencia.validade as date',
        db_prefix() . '_intranet_registro_ocorrencia.status as status'
    ],
    2 => [
        db_prefix() . '_intranet_registro_ocorrencia.validade as date',
        db_prefix() . '_intranet_categorias.responsavel',
        db_prefix() . '_intranet_registro_ocorrencia.method as method',
        'atribuido_a as atribuido_a',
        db_prefix() . '_intranet_registro_ocorrencia.status as status'
    ],
    3 => [
        db_prefix() . '_intranet_registro_ocorrencia.validade as date',
        db_prefix() . '_intranet_registro_ocorrencia_atuantes.titulo as responsability',
        db_prefix() . '_intranet_registro_ocorrencia_atuantes_por_registro.date_finalizado as responsability_date',
        db_prefix() . '_intranet_registro_ocorrencia.status as status'
    ],
    4 => [
        db_prefix() . '_intranet_registro_ocorrencia.validade as date',
        db_prefix() . '_intranet_registro_ocorrencia_atuantes.titulo as responsability',
        db_prefix() . '_intranet_registro_ocorrencia_atuantes_por_registro.date_finalizado as responsability_date',
        db_prefix() . '_intranet_registro_ocorrencia.status as status'
    ]
];

$staff = get_staff_user_id();
$empresa_id = $this->ci->session->userdata('empresa_id');

$additionalSelect = [
    db_prefix() . 'departments.name as responsavel',
    db_prefix() . '_intranet_registro_atendimento.protocolo as protocolo',
    db_prefix() . 'clients.numero_carteirinha as carteirinha',
    db_prefix() . 'clients.company as client_name',
    db_prefix() . '_intranet_registro_ocorrencia.user_created as user_created',
    db_prefix() . 'clients.userid as userid',
    'registro_atendimento_id as atendimento',
   /* "(
    SELECT GROUP_CONCAT(
        CONCAT(
            '{\"nome\": \"', tbl_intranet_categorias_campo.nome, '\", ',
            '\"value\": \"', tbl_intranet_categorias_campo_values.value, '\", ',
            '\"type\": \"', tbl_intranet_categorias_campo.type, '\"}'
        )
        SEPARATOR ', '
    )
    FROM tbl_intranet_categorias_campo_values
    INNER JOIN tbl_intranet_categorias_campo ON tbl_intranet_categorias_campo.id = tbl_intranet_categorias_campo_values.campo_id
    WHERE  tbl_intranet_categorias_campo_values.empresa_id = $empresa_id
        AND tbl_intranet_categorias_campo_values.deleted = 0
        AND tbl_intranet_categorias_campo_values.rel_id = tbl_intranet_registro_ocorrencia.id
        AND tbl_intranet_categorias_campo_values.rel_type = 'r.o'
        AND tbl_intranet_categorias_campo.chave = 1
) AS custom_values"*/
];
$aColumns = array_merge($aColumns, $typeColumns[$type]);

$join = [
    'LEFT JOIN ' . db_prefix() . '_intranet_categorias ON ' . db_prefix() . '_intranet_categorias.id = ' . db_prefix() . '_intranet_registro_ocorrencia.categoria_id',
    'LEFT JOIN ' . db_prefix() . 'departments ON ' . db_prefix() . 'departments.departmentid = ' . db_prefix() . '_intranet_categorias.responsavel',
    'LEFT JOIN ' . db_prefix() . '_intranet_registro_atendimento ON ' . db_prefix() . '_intranet_registro_atendimento.id = ' . db_prefix() . '_intranet_registro_ocorrencia.registro_atendimento_id',
    'LEFT JOIN ' . db_prefix() . 'clients ON ' . db_prefix() . 'clients.userid = ' . db_prefix() . '_intranet_registro_atendimento.client_id'
];

array_push($where, 'AND ' . db_prefix() . '_intranet_registro_ocorrencia.deleted = 0');
array_push($where, ' AND ' . db_prefix() . '_intranet_registro_ocorrencia.empresa_id = ' . $empresa_id);

$sIndexColumn = 'id';
$sTable = db_prefix() . '_intranet_registro_ocorrencia';

if ($type == 2) {
    $additionalSelect = array_merge($additionalSelect, [
        db_prefix() . '_intranet_registro_ocorrencia.material as material',
        db_prefix() . '_intranet_registro_ocorrencia.labor as labor',
        db_prefix() . '_intranet_registro_ocorrencia.machine as machine',
        db_prefix() . '_intranet_registro_ocorrencia.measurement as measurement',
        db_prefix() . '_intranet_registro_ocorrencia.environment as environment',
        db_prefix() . '_intranet_registro_ocorrencia.problem as problem',
    ]);
    array_push($join, 'INNER JOIN ' . db_prefix() . 'staff_departments ON ' . db_prefix() . 'staff_departments.departmentid = ' . db_prefix() . '_intranet_categorias.responsavel');
    array_push($where, " AND " . db_prefix() . "staff_departments.staffid = $staff");
} elseif ($type == 3 || $type == 4) {


    array_push($additionalSelect, db_prefix() . '_intranet_registro_ocorrencia_atuantes_por_registro.prazo as prazo');

    $join = array_merge($join, [
        'INNER JOIN ' . db_prefix() . '_intranet_registro_ocorrencia_atuantes_por_registro ON ' . db_prefix() . '_intranet_registro_ocorrencia_atuantes_por_registro.registro_id = ' . db_prefix() . '_intranet_registro_ocorrencia.id',
        'INNER JOIN ' . db_prefix() . '_intranet_registro_ocorrencia_atuantes ON ' . db_prefix() . '_intranet_registro_ocorrencia_atuantes.id = ' . db_prefix() . '_intranet_registro_ocorrencia_atuantes_por_registro.atuante_id'
    ]);

    if ($type == 4) {
        $additionalSelect = array_merge($additionalSelect, [
            db_prefix() . '_intranet_registro_ocorrencia_atuantes_por_registro.staff_id as responsable',
            db_prefix() . '_intranet_registro_ocorrencia_atuantes_por_registro.id as responsable_id',
        ]);
        array_push($join, 'INNER JOIN ' . db_prefix() . 'staff_departments ON ' . db_prefix() . 'staff_departments.departmentid = ' . db_prefix() . '_intranet_registro_ocorrencia_atuantes_por_registro.department_id and ' . db_prefix() . 'staff_departments.staffid =' . $staff);
    }

    array_push($where, " AND  " . db_prefix() . "_intranet_registro_ocorrencia_atuantes_por_registro.deleted = 0");

    if ($type == 4) {
        //array_push($where, " AND staff_departments.staffid  = " . get_staff_user_id());
    } else {
        array_push($where, " AND " . db_prefix() . "_intranet_registro_ocorrencia_atuantes_por_registro.staff_id = $staff");
    }
} else {
    array_push($where, ' AND ' . db_prefix() . '_intranet_registro_ocorrencia.user_created = ' . get_staff_user_id());
}



$status = $this->ci->input->post('status');

if (is_array($status)) {
    $total_status = count($status);
    $status = implode(',', $status);
} else {
    $total_status = 0;
}

if ($total_status > 0) {
    array_push($where, 'AND tbl_intranet_registro_ocorrencia.status IN (' . $status . ')');
}

if ($this->ci->input->post('client')) {
    $clientid = $this->ci->input->post('client');

    $where[] = 'AND ' . db_prefix() . 'clients.company like ' . "'%$clientid%' ";
}

if ($this->ci->input->post('id')) {
    $id = $this->ci->input->post('id');

    $where[] = 'AND ' . db_prefix() . '_intranet_registro_ocorrencia.id like ' . "'%$id%' ";
}

if ($this->ci->input->post('subject')) {
    $subject = $this->ci->input->post('subject');

    $where[] = 'AND ' . db_prefix() . '_intranet_registro_ocorrencia.subject like ' . "'%$subject%' ";
}


if ($this->ci->input->post('user_created')) {
    $notificantes = $this->ci->input->post('user_created');
    $notificantes = implode(',', $notificantes);
    array_push($where, " AND tbl_intranet_registro_ocorrencia.user_created IN($notificantes)");
}

if ($this->ci->input->post('departments')) {
    $departments = $this->ci->input->post('departments');
    $departments = implode(',', $departments);
    array_push($where, " AND tbl_intranet_categorias.responsavel IN($departments)");
}

if ($this->ci->input->post('atribuido_a')) {
    $atribuidos = $this->ci->input->post('atribuido_a');
    $atribuidos = implode(',', $atribuidos);
    array_push($where, " AND tbl_intranet_registro_ocorrencia.atribuido_a IN($atribuidos)");
}

if ($this->ci->input->post('categoria_id')) {
    $categoria_id = $this->ci->input->post('categoria_id');
    $categoria_id = implode(',', $categoria_id);
    array_push($where, " AND tbl_intranet_categorias.id IN($categoria_id)");
}
if ($this->ci->input->post('protocolo')) {
    $prot = $this->ci->input->post('protocolo');
    $where[] = 'AND ' . db_prefix() . '_intranet_registro_atendimento.protocolo like ' . "'%$prot%' ";
}
if ($this->ci->input->post('carteirinha')) {
    $cart = $this->ci->input->post('carteirinha');
    $where[] = 'AND ' . db_prefix() . 'clients.numero_carteirinha like ' . "'%$cart%' ";
}
if ($this->ci->input->post('period') && $this->ci->input->post('period') != '-') {
    if ($type == 1 || $type == 2) {
        $column = db_prefix() . '_intranet_registro_ocorrencia.date_created';
    } else {
        $column = db_prefix() . '_intranet_registro_ocorrencia_atuantes_por_registro.data_cadastro';
    }
    if ($this->ci->input->post('period') == 'd') {
        array_push($where, " AND $column between '" . date('Y-m-d') . " 00:00:01' and '" . date('Y-m-d') . " 23:59:59'");
    } elseif ($this->ci->input->post('period') == 'm') {
        array_push($where, " AND $column between '" . date('Y-m-d', strtotime('last month')) . " 00:00:01' and '" . date('Y-m-d') . " 23:59:59' ");
    } elseif ($this->ci->input->post('period') == 'Y') {
        array_push($where, " AND $column between '" . (date('Y') - 1) . '-' . date('m-d') . " 00:00:01' and '" . date('Y-m-d') . " 23:59:59' ");
    } else {
        array_push($where, " AND $column between '" . date('Y-m-d', strtotime("-7 days", time())) . " 00:00:01' and '" . date('Y-m-d') . " 23:59:59' ");
    }
}


if ($this->ci->input->post('filter')) {
    $this->ci->load->model('Intranet_general_model');
    $this->ci->Intranet_general_model->update_filters($filters, 'r.o', $type);
}

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, $additionalSelect, '', '', '', get_staff_user_id());
$output = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {

    $row = [];

   // $view = base_url('gestao_corporativa/Registro_ocorrencia/registro/' . base64_encode(openssl_encrypt($aRow['id'], "aes-256-cbc", 'sigplus', 0, 'sigplus')));

   // if ($type != 4) {
        $view = base_url('gestao_corporativa/Registro_ocorrencia/registro/' . base64_encode(openssl_encrypt($aRow['id'], "aes-256-cbc", 'sigplus', 0, 'sigplus')));
   // }
    $row[] = '<a href="' . $view . '" target="_blank" >#' . strtoupper($aRow['id']) . '</a>';
    $titulo = '' . strtoupper($aRow['titulo']);
    if ($aRow['atendimento'] != '' && $aRow['atendimento'] != 0) {
        $titulo .= '<br>Protocolo: <a href="' . base_url('gestao_corporativa/atendimento/view/' . $aRow['atendimento']) . '" >' . $aRow['protocolo'] . '</a>';
        $titulo .= '<br>Cliente: <a href="' . admin_url('clients/client/' . $aRow['userid']) . '?intranet=intranet" >' . $aRow['client_name'] . ' (' . $aRow['carteirinha'] . ')</a>';
    }

    if ($type != 1) {
        //$this->ci->load->model('Categorias_campos_model');

        //$chaves = $this->ci->Categorias_campos_model->get_values($aRow['id'], 'r.o', '0', '', true, true);
        $array_json = json_decode('[' . $aRow['custom_values'] . ']', true);
        $informacoes = "<br>";
       foreach ($array_json as $item) {
            $informacoes .=  $item['nome'].": ". get_value('r.o', $item['value'], $item['type'], true).'<br>';
        }

        $titulo .= $informacoes;
    }
    $row[] = $titulo;
    $row[] = strtoupper($aRow['categoria']) . ' (' . $aRow['responsavel'] . ')';
    $data = date("d/m/Y", strtotime($aRow['date_created'])) . ' - ';
    if ($aRow['date_created']) {
        $data .= get_staff_full_name($aRow['user_created']);
    } else {
        $data .= 'ANÔNIMO';
    }

    
    $row[] = $data;

    if ($aRow['date']) {
        $validade = date("d/m/Y", strtotime($aRow['date']));
    } else {
        $validade = '';
    }

    // $row[] = $validade;
    if ($type == 2) {
       
        $atuantes = $this->ci->Registro_ocorrencia_model->get_atuantes_preenchidos_all($aRow['id']);
        
        $outputAtuantes = '';
        if (count($atuantes) > 0) {
            $outputAtuantes .= '<p style="font-size : 12px; color: black;">';

            foreach ($atuantes as $atuante) {
                if ($atuante['date_finalizado']) {
                    $sts = '<span style="color: #6dec3d;">FINALIZADO</span>';
                } else {
                    $sts = '<span style="color: #ec0000;">PENDENTE</span>';
                }
              
                $outputAtuantes .= '' . str_replace(' ', '_', $atuante['titulo']) . ':' . get_staff_first_name($atuante['staff_id']) . '(' . $sts . ')<br>';
            }

            $outputAtuantes .= '</p>';
        } else {
            $outputAtuantes = '';
        }
        $row[] = $outputAtuantes;
    }
    //ok tambem
    $row[] = $validade;
    if ($type == 2) {

        if ($aRow['method'] && $aRow['material'] && $aRow['labor'] && $aRow['machine'] && $aRow['measurement'] && $aRow['environment'] && $aRow['problem']) {
            $ishikawa_color = '#73f456';
            $ishikawa_icon = 'fa fa-check';
            $ishikawa_label = 'ISHIKAWA PREENCHIDO';
        } elseif (!$aRow['method'] && !$aRow['material'] && !$aRow['labor'] && !$aRow['machine'] && !$aRow['measurement'] && !$aRow['environment'] && !$aRow['problem']) {
            $ishikawa_color = '#f46b56';
            $ishikawa_icon = 'fa fa-times';
            $ishikawa_label = 'ISHIKAWA NÃO PREENCHIDO';
        } else {
            $ishikawa_color = '#f4cd56';
            $ishikawa_icon = 'fa fa-spinner';
            $ishikawa_label = 'ISHIKAWA EM ANDAMENTO';
        }


        
        $tasks = $this->ci->Tasks_model->get_all($aRow['id'], "record");
        $outputTasks = '<br><p style="font-size : 12px; color: black;">';
        foreach($tasks as $task){
            $status_          = get_task_status_by_id($task['status']);
            $outputTasks .= '<span style=" color:' . $status_['color'] . '">#'.$task['id']. ' - ' . $status_['name'].'</span><br>';//print_r($task);
        }
        $outputTasks .= '</p>';
        $row[] = '<span class="label inline-block" style=" color:' . $ishikawa_color . '"><i class="icon ' . $ishikawa_icon . '"></i> ' . $ishikawa_label . '</span>'.$outputTasks;
        
        
    }
    //$row[] = strtoupper($aRow['responsavel']);
    if ($type == 2) {
        $outputStatus = '';
        
        if($aRow['atribuido_a'] != 0 and $aRow['atribuido_a'] != ''){
            $names = format_members_by_ids_and_names($aRow['atribuido_a'], get_staff_full_name($aRow['atribuido_a']));
        } else {
            $names = '';
        }

        $outputStatus .= '<div style="text-align: center;"> ' . $names . '';

        $outputStatus .= '<span class="inline-block label" style="color: black;">';

        $outputStatus .= '<i class="icon fa fa-spinner"></i>';
        $outputStatus .= '<div class="dropdown inline-block mleft5 table-export-exclude">';
        $outputStatus .= '<a href="#" style="font-size:14px;vertical-align:middle;" class="dropdown-toggle text-dark" id="tableTaskStatus-' . $aRow['id'] . '" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
        $outputStatus .= '<span data-toggle="tooltip" title="' . _l('ticket_single_change_status') . '"><i class="fa fa-caret-down" aria-hidden="true"></i></span>';
        $outputStatus .= '</a>';

        $outputStatus .= '<ul class="dropdown-menu dropdown-menu-right" aria-labelledby="tableTaskStatus-' . $aRow['id'] . '">';

        $outputStatus .= '<li>
                            <a href="' . base_url("gestao_corporativa/registro_ocorrencia/assumir_registro/") . $aRow['id'] . '">
                             Assumir Registro
                            </a>
                             </li>';
        $outputStatus .= '</ul>';
        $outputStatus .= '</div></div>';
        $row[] = $outputStatus;
    }
   //aqui ok
    if ($type == 3 || $type == 4) {
        if ($aRow['responsability_date']) {
            $ishikawa_color = '#099e1d';
            $ishikawa_icon = 'fa fa-check';
            $ishikawa_label = '';
            $permission = false;
        } else {
            $ishikawa_color = '#9e0909';
            $ishikawa_icon = 'fa fa-times';
            $ishikawa_label = 'NÃO RESPONDIDO';
            $permission = true;
        }

        $responsability = $aRow['responsability'];

        if ($permission == true) {
            $outputStatus = '';

            $outputStatus .= '<div style="text-align: center;"> ' . format_members_by_ids_and_names($aRow['responsable'], get_staff_full_name($aRow['responsable'])) . '';

            $outputStatus .= '<span class="inline-block label" style="color: black;">';

            $outputStatus .= '<i class="icon fa fa-exchange"></i>';
            $outputStatus .= '<div class="dropdown inline-block mleft5 table-export-exclude">';
            $outputStatus .= '<a href="#" style="font-size:14px;vertical-align:middle;" class="dropdown-toggle text-dark" id="tableTaskStatus-' . $aRow['id'] . '" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
            $outputStatus .= '<span data-toggle="tooltip" title="' . _l('ticket_single_change_status') . '"><i class="fa fa-caret-down" aria-hidden="true"></i></span>';
            $outputStatus .= '</a>';

            $outputStatus .= '<ul class="dropdown-menu dropdown-menu-right" aria-labelledby="tableTaskStatus-' . $aRow['responsable_id'] . '">';

            $outputStatus .= '<li>
                            <a href="' . base_url("gestao_corporativa/registro_ocorrencia/assumir_responsabilidade/") . $aRow['responsable_id'] . '">
                             Assumir Responsabilidade
                            </a>
                             </li>';
            $outputStatus .= '</ul>';
            $outputStatus .= '</div></div>';
            $responsability .= $outputStatus;
        }

        $row[] = $responsability;
        $prazo =  _($aRow['prazo']) ? 'Prazo:' . _d($aRow['prazo']) . '<br>' : '';
        $row[] = $prazo . '<span class="label inline-block" style=" color:' . $ishikawa_color . '"> <i class="icon ' . $ishikawa_icon . '"></i> ' . $ishikawa_label . _d($aRow['responsability_date']) . '</span>';
        
    }
    $status = get_ro_status($aRow['status']);
    $row[] = '<span class="label inline-block" style="border:1px solid ' . $status['color'] . '; color:' . $status['color'] . '">' . ($status['label']) . '</span>';

    $output['aaData'][] = $row;
}
