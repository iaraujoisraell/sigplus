<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [];
$where = [];

if (has_permission('items', '', 'delete')) {
    $aColumns[] = '1';
}


$aColumns = array_merge($aColumns, [
    db_prefix() . '_intranet_registro_atendimento.protocolo as protocolo',
    db_prefix() . 'clients.numero_carteirinha as carteirinha',
    db_prefix() . 'staff.firstname as firstname',
    db_prefix() . 'staff.lastname as lastname',
    db_prefix() . 'clients.company as company',
    db_prefix() . '_intranet_categorias.titulo as categoria',
    db_prefix() . '_intranet_registro_atendimento_canais.canal as canal',
    db_prefix() . 'clients.userid as userid',
    db_prefix() . '_intranet_registro_atendimento.date_created as date_created',
        ]);

if ($this->ci->input->post('vinculado')) {
    //array_push($aColumns, db_prefix() . '_intranet_ged.status as hhh');
}
$sIndexColumn = 'id';
$sTable = db_prefix() . '_intranet_registro_atendimento';

$join = [
    'LEFT JOIN ' . db_prefix() . '_intranet_registro_atendimento_canais ON ' . db_prefix() . '_intranet_registro_atendimento_canais.id = ' . db_prefix() . '_intranet_registro_atendimento.canal_atendimento_id',
    'LEFT JOIN ' . db_prefix() . '_intranet_categorias ON ' . db_prefix() . '_intranet_categorias.id = ' . db_prefix() . '_intranet_registro_atendimento.categoria_id',
    'LEFT JOIN ' . db_prefix() . 'staff ON ' . db_prefix() . 'staff.staffid = ' . db_prefix() . '_intranet_registro_atendimento.user_created',
    'LEFT JOIN ' . db_prefix() . 'clients ON ' . db_prefix() . 'clients.userid = ' . db_prefix() . '_intranet_registro_atendimento.client_id'
];
$additionalSelect = [
    'tbl_intranet_registro_atendimento.id',
    'tbl_intranet_registro_atendimento.sequencial',
    'tbl_intranet_registro_atendimento.contato',
    'tbl_intranet_registro_atendimento.email',
];

// Fix for big queries. Some hosting have max_join_limit
// FILTRO CONVÊNIOS
$staff = get_staff_user_id();
$empresa_id = $this->ci->session->userdata('empresa_id');
$this->ci->load->model('Registro_ocorrencia_model');
if ($this->ci->input->post('meus_atendimentos') || $this->ci->input->post('user_created')) {

    if ($this->ci->input->post('meus_atendimentos') && !$this->ci->input->post('user_created')) {
        array_push($where, ' AND ' . db_prefix() . '_intranet_registro_atendimento.user_created = ' . get_staff_user_id());
    } elseif (!$this->ci->input->post('meus_atendimentos') && $this->ci->input->post('user_created')) {
        $user_created = $this->ci->input->post('user_created');
        //print_r($clientid); exit;
        $user_created = implode(',', $user_created);

        array_push($where, " AND tbl_intranet_registro_atendimento.user_created in ($user_created)");
    } elseif ($this->ci->input->post('meus_atendimentos') && $this->ci->input->post('user_created')) {
        $user_created = $this->ci->input->post('user_created');
        //print_r($clientid); exit;
        $user_created = implode(',', $user_created);

        array_push($where, " AND (tbl_intranet_registro_atendimento.user_created = " . get_staff_user_id() . " or tbl_intranet_registro_atendimento.user_created in ($user_created))");
    }
}
if ($this->ci->input->post('portal')) {
  
    array_push($where, ' AND ' . db_prefix() . '_intranet_registro_atendimento.canal_atendimento_id = 0');
} else {
    array_push($where, ' AND ' . db_prefix() . '_intranet_registro_atendimento.canal_atendimento_id != 0');
}

if ($this->ci->input->post('clientid')) {
    $clientid = $this->ci->input->post('clientid');
    //print_r($clientid); exit;
    $clientid = implode(',', $clientid);

    array_push($where, " AND tblclients.userid in ($clientid)");
}


if ($this->ci->input->post('carteirinha')) {
    $carteirinha = $this->ci->input->post('carteirinha');

    array_push($where, " AND tblclients.numero_carteirinha like '%$carteirinha%'");
}

if ($this->ci->input->post('protocolo')) {
    $protocolo = $this->ci->input->post('protocolo');

    array_push($where, " AND tbl_intranet_registro_atendimento.protocolo like '%$protocolo%'");
}

if ($this->ci->input->post('id')) {
    $id_ = $this->ci->input->post('id');

    array_push($where, " AND tbl_intranet_registro_atendimento.id = $id_");
}

if ($this->ci->input->post('categoria_id')) {
    $categoria_id = $this->ci->input->post('categoria_id');
    //print_r($clientid); exit;
    $categoria_id = implode(',', $categoria_id);

    array_push($where, " AND tbl_intranet_registro_atendimento.categoria_id in ($categoria_id)");
}

if ($this->ci->input->post('canal_atendimento_id')) {
    $canal_atendimento_id = $this->ci->input->post('canal_atendimento_id');
    //print_r($clientid); exit;
    $canal_atendimento_id = implode(',', $canal_atendimento_id);

    array_push($where, " AND tbl_intranet_registro_atendimento.canal_atendimento_id in ($canal_atendimento_id)");
}

array_push($where, 'AND ' . db_prefix() . '_intranet_registro_atendimento.deleted = 0');
array_push($where, ' AND ' . db_prefix() . '_intranet_registro_atendimento.empresa_id = ' . $empresa_id);
//print_r($where); exit;
//$where   = [];
$orderby = " order by tbl_intranet_registro_atendimento.id desc";

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, $additionalSelect, "", "", $orderby);
$output = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {



    $rel_id = $aRow['id'];
    $row = [];
    $row[] = '#' . $rel_id;
    $protocolo = '<a   href="' . site_url('gestao_corporativa/Atendimento/view/' . $rel_id) . '">' . $aRow['protocolo'] . '</a>';
    $row[] = $protocolo;

    if (!$this->ci->input->post('portal')) {
        $canal = strtoupper($aRow['canal']);
        $row[] = $canal;
    }
    $row[] = '<a href="' . admin_url('clients/client/' . $aRow['userid']) . '?intranet=intranet" target="_blanck">' . $aRow['company'] . ' (' . $aRow['carteirinha'] . ')</a>';

    if (!$this->ci->input->post('portal')) {
        $categoria = strtoupper($aRow['categoria']);
        $row[] = $categoria;
    }

    $row[] = strtoupper($aRow['contato']) . '<Br>' . $aRow['email'];
    $this->ci->load->model('Categorias_campos_model');

    $chaves = $this->ci->Categorias_campos_model->get_values($rel_id, 'atendimento', '0', true);
    $informacoes = "<p>";
    foreach ($chaves as $info) {
        $nome_campo = $info['nome_campo'];
        $campo = $info['tipo_campo'];
        $value = $info['value'];

        if ($campo == 'multiselect' || $campo == 'select') {
            $values = explode(',', $value);
            for ($i = 0; $i < count($values); $i++) {
                $row_ = $this->ci->Categorias_campos_model->get_option($values[$i]);
                $values[$i] = $row_->option;
            }
            $value = implode(', ', $values);
        } elseif ($campo == 'setores') {

            if ($value) {
                $value = get_departamento_nome($value);
            }
        } elseif ($campo == 'funcionarios') {
            if ($value) {
                $value = get_staff_full_name($value);
            }
        } elseif ($campo == 'file') {
            if ($value) {
                $value = '<a href="' . base_url() . 'assets/intranet/arquivos/ro_arquivos/campo_file/' . $value . '" target=black><i class="fa fa-file-o"></i> ' . $value . '</a>';
            }
        }

        $informacoes .= $nome_campo . ': ' . $value;

        $informações .= '<br>';
    }

    $informacoes .= '</p>';
    $row[] = $informacoes;

    $opcoes = '';
    $opcoes .= '<a target="_blank" class="btn btn-danger" title = "Add novo Registro de Ocorrências" href="' . site_url('gestao_corporativa/registro_ocorrencia/add/' . $rel_id) . '">+ R.O.</a>';

    //$row[] = $opcoes;

    $row[] = '<font style="font-size : 12px">' . $aRow['firstname'] . ' ' . $aRow['lastname'] . ' <br> ' . _dt($aRow['date_created']) . ' </font>';

    $output['aaData'][] = $row;
}
