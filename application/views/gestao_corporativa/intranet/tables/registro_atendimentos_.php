<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [];
$where = [];
$keys = ['client', 'carteirinha', 'id', 'protocolo', 'categoria_id', 'canal_atendimento_id', 'period'];

$filters = [];
foreach ($keys as $key) {
    $value = $this->ci->input->post($key);
    $filters[] = array("user_created" => get_staff_user_id(), "date_created" => date('Y-m-d'), "rel_type" => 'ra', "tab" => 0, "filter" => $key, "value" => is_array($value) ? json_encode($value) : $value);
}


$aColumns = array_merge($aColumns, [
    db_prefix() . '_intranet_registro_atendimento.id as id',
    db_prefix() . '_intranet_registro_atendimento.protocolo as protocolo',
    db_prefix() . 'clients.numero_carteirinha as carteirinha',
        ]);
if (!$this->ci->input->post('portal')) {
    array_push($aColumns, db_prefix() . '_intranet_registro_atendimento_canais.canal as canal');
}
$aColumns = array_merge($aColumns, [
    db_prefix() . 'clients.company as company',
        ]);
if (!$this->ci->input->post('portal')) {
    array_push($aColumns, db_prefix() . '_intranet_categorias.titulo as categoria');
}
$aColumns = array_merge($aColumns, [
    db_prefix() . '_intranet_registro_atendimento.contato',
    db_prefix() . '_intranet_registro_atendimento.email',
    db_prefix() . '_intranet_registro_atendimento.date_created as date_created',
    db_prefix() . 'staff.firstname as firstname',
    db_prefix() . 'staff.lastname as lastname',
    db_prefix() . 'clients.userid as userid',
    
    db_prefix() . '_intranet_registro_atendimento.sequencial',
        ]);

$sIndexColumn = 'id';
$sTable = db_prefix() . '_intranet_registro_atendimento';

$join = [
    'LEFT JOIN ' . db_prefix() . 'staff ON ' . db_prefix() . 'staff.staffid = ' . db_prefix() . '_intranet_registro_atendimento.user_created',
    'LEFT JOIN ' . db_prefix() . 'clients ON ' . db_prefix() . 'clients.userid = ' . db_prefix() . '_intranet_registro_atendimento.client_id'
];

if (!$this->ci->input->post('portal')) {
    array_push($join, 'LEFT JOIN ' . db_prefix() . '_intranet_registro_atendimento_canais ON ' . db_prefix() . '_intranet_registro_atendimento_canais.id = ' . db_prefix() . '_intranet_registro_atendimento.canal_atendimento_id');
    array_push($join, 'LEFT JOIN ' . db_prefix() . '_intranet_categorias ON ' . db_prefix() . '_intranet_categorias.id = ' . db_prefix() . '_intranet_registro_atendimento.categoria_id');
}

$staff = get_staff_user_id();
$empresa_id = $this->ci->session->userdata('empresa_id');

if ($this->ci->input->post('meus_atendimentos')) {

    array_push($where, ' AND ' . db_prefix() . '_intranet_registro_atendimento.user_created = ' . get_staff_user_id());
}

if ($this->ci->input->post('portal')) {

    array_push($where, ' AND ' . db_prefix() . '_intranet_registro_atendimento.canal_atendimento_id = 0');
} else {
    array_push($where, ' AND ' . db_prefix() . '_intranet_registro_atendimento.canal_atendimento_id != 0');
}

if ($this->ci->input->post('client')) {
    $client = $this->ci->input->post('client');

    array_push($where, " AND tblclients.company like '%$client%'");
}

if ($this->ci->input->post('period') && $this->ci->input->post('period') != '-') {
    if ($this->ci->input->post('period') == 'd') {
        array_push($where, " AND tbl_intranet_registro_atendimento.date_created between '" . date('Y-m-d') . " 00:00:01' and '" . date('Y-m-d') . " 23:59:59' ");
    } elseif ($this->ci->input->post('period') == 'm') {
        array_push($where, " AND tbl_intranet_registro_atendimento.date_created between '" . date('Y-m-01') . " 00:00:01' and '" . date('Y-m-d') . " 23:59:59' ");
    } elseif ($this->ci->input->post('period') == 'Y') {
        array_push($where, " AND tbl_intranet_registro_atendimento.date_created between '" . (date('Y') - 1) .'-' . date('m-d') . " 00:00:01' and '" . date('Y-m-d') . " 23:59:59' ");
    } else {
        array_push($where, " AND tbl_intranet_registro_atendimento.date_created between '" . date('Y-m-d', strtotime("-7 days", time())) . " 00:00:01' and '" . date('Y-m-d') . " 23:59:59' ");
    }
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
$this->ci->load->model('Categorias_campos_model');
$this->ci->load->model('Atendimentos_model');
if ($this->ci->input->post('filter')) {
    $this->ci->load->model('Intranet_general_model');
    $this->ci->Intranet_general_model->update_filters($filters, 'ra', 0);
}

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where);
$output = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {

    $workflow = $this->ci->Atendimentos_model->get_worklow_by_ra_id($aRow['id']);
    $qtd_w = count($workflow);
    $ro = $this->ci->Atendimentos_model->get_ros_by_ra_id($aRow['id']);
    $qtd_r = count($ro);

    $rel_id = $aRow['id'];
    $row = [];
    $row[] = '#' . $rel_id;
    $protocolo = '<a   href="' . site_url('gestao_corporativa/Atendimento/view/' . $rel_id) . '">' . $aRow['protocolo'] . '</a>';
    $protocolo .= ' <p class="text-danger">';
    if ($qtd_r > 0) {
        $protocolo .= ' <i class="fa fa-exclamation-circle" aria-hidden="true"></i> REGISTROS (' . $qtd_r . ')';
    }
    if ($qtd_r > 0 and $qtd_w > 0) {
        $protocolo .= '<br>';
    }
    if ($qtd_w > 0) {
        $protocolo .= '<i class="fa fa-circle-o-notch" aria-hidden="true" ></i> WORKFLOW' . "'S" . ' (' . $qtd_w . ')</p>';
    }
    $protocolo .= '</p>';
    $row[] = $protocolo;

    $req = '';
    foreach ($ro as $r) {
        $req .= '<a   href="' . base_url('gestao_corporativa/Registro_ocorrencia/registro/' . base64_encode(openssl_encrypt($r['id'], "aes-256-cbc", 'sigplus', 0, 'sigplus'))) . '" >RO #' . $r['id'] . '</a><br>';
    }
    foreach ($workflow as $wf) {
        $req .= '<a   href="' . base_url() . 'gestao_corporativa/workflow/pdf/' . $wf['id'] . '">WF #' . $wf['id'] . '</a><br>';
    }

    $row[] = $req;

    if (!$this->ci->input->post('portal')) {
        $canal = strtoupper($aRow['canal']);
        $row[] = $canal;
    }



    $row[] = '<a href="' . admin_url('clients/client/' . $aRow['userid']) . '?intranet=intranet" >' . $aRow['company'] . ' (' . $aRow['carteirinha'] . ')</a>';

    if (!$this->ci->input->post('portal')) {
        $categoria = strtoupper($aRow['categoria']);
        $row[] = $categoria;
    }

    $row[] = strtoupper($aRow['contato']) . '<br>' . $aRow['email'];
    
    $chaves = $this->ci->Categorias_campos_model->get_values($aRow['id'], 'atendimento', '0', true, false, true);
    $informacoes = "<p>";
    foreach ($chaves as $info) {
        $informacoes .= '' . $info['nome_campo'] . ': ' . $info['value'] . '<br>';
    }

    $informacoes .= '</p>';
    $row[] = $informacoes;

    $opcoes = '';
    $opcoes .= '<a  class="btn btn-danger" title = "Add novo Registro de Ocorrências" href="' . site_url('gestao_corporativa/registro_ocorrencia/add/' . $rel_id) . '">+ R.O.</a>';

    //$row[] = $opcoes;

    $row[] = '<font style="font-size : 12px">' . $aRow['firstname'] . '<br> ' . _dt($aRow['date_created']) . ' </font>';

    $row['DT_RowClass'] = 'has-row-options';
    $output['aaData'][] = $row;
}
