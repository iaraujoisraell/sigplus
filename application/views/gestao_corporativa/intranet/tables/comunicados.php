<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
    db_prefix() . '_intranet_ci.id as ci_id',
    db_prefix() . '_intranet_ci.codigo as codigo',
];

$sIndexColumn = 'id';
$sTable = db_prefix() . '_intranet_ci';
$where = [];

$staff_id   = (int) get_staff_user_id();
$empresa_id = (int) $this->ci->session->userdata('empresa_id');

$is_my = (bool) $this->ci->input->post('my');

if ($is_my) {
    $join = [
        'LEFT JOIN tblstaff staff_autor ON staff_autor.staffid = ' . db_prefix() . '_intranet_ci.user_create',
        'LEFT JOIN tbldepartments dept ON dept.departmentid = ' . db_prefix() . '_intranet_ci.setor_id',
    ];
    $additionalSelect = [
        db_prefix() . '_intranet_ci.titulo as titulo',
        db_prefix() . '_intranet_ci.dt_created as enviado',
        db_prefix() . '_intranet_ci.user_create as user_create',
        'CONCAT_WS(\' \', staff_autor.firstname, staff_autor.lastname) AS autor_nome',
        'dept.name AS setor_nome',
    ];
    array_push($where, 'AND ' . db_prefix() . '_intranet_ci.user_create = ' . $staff_id);
} else {
    $join = [
        'INNER JOIN ' . db_prefix() . '_intranet_ci_send ON ' . db_prefix() . '_intranet_ci_send.ci_id = ' . db_prefix() . '_intranet_ci.id',
        'LEFT JOIN tblstaff staff_autor ON staff_autor.staffid = ' . db_prefix() . '_intranet_ci.user_create',
        'LEFT JOIN tbldepartments dept ON dept.departmentid = ' . db_prefix() . '_intranet_ci.setor_id',
    ];

    $additionalSelect = [
        db_prefix() . '_intranet_ci.titulo as titulo',
        db_prefix() . '_intranet_ci_send.dt_send as enviado',
        db_prefix() . '_intranet_ci_send.dt_ciente as lido',
        db_prefix() . '_intranet_ci_send.dt_read as ciente',
        db_prefix() . '_intranet_ci_send.status as send_status',
        db_prefix() . '_intranet_ci.user_create as user_create',
        'CONCAT_WS(\' \', staff_autor.firstname, staff_autor.lastname) AS autor_nome',
        'dept.name AS setor_nome',
    ];

    array_push($where, 'AND ' . db_prefix() . '_intranet_ci_send.deleted = 0');
    array_push($where, 'AND ' . db_prefix() . '_intranet_ci_send.empresa_id = ' . $empresa_id);
    array_push($where, 'AND ' . db_prefix() . '_intranet_ci_send.staff_id = ' . $staff_id);

    $filtro_status = $this->ci->input->post('filtro_status');
    if ($filtro_status === '0' || $filtro_status === '1') {
        array_push($where, 'AND ' . db_prefix() . '_intranet_ci_send.status = ' . (int) $filtro_status);
    }
}

array_push($where, 'AND ' . db_prefix() . '_intranet_ci.deleted = 0');
array_push($where, 'AND ' . db_prefix() . '_intranet_ci.empresa_id = ' . $empresa_id);

$filtro_titulo = trim((string) $this->ci->input->post('filtro_titulo'));
if ($filtro_titulo !== '') {
    $titulo_escapado = $this->ci->db->escape_like_str($filtro_titulo);
    array_push($where, "AND " . db_prefix() . "_intranet_ci.titulo LIKE '%{$titulo_escapado}%'");
}

$filtro_dt_ini = trim((string) $this->ci->input->post('filtro_dt_ini'));
if ($filtro_dt_ini !== '' && preg_match('/^\d{4}-\d{2}-\d{2}$/', $filtro_dt_ini)) {
    array_push($where, "AND DATE(" . db_prefix() . "_intranet_ci.dt_created) >= '{$filtro_dt_ini}'");
}

$filtro_dt_fim = trim((string) $this->ci->input->post('filtro_dt_fim'));
if ($filtro_dt_fim !== '' && preg_match('/^\d{4}-\d{2}-\d{2}$/', $filtro_dt_fim)) {
    array_push($where, "AND DATE(" . db_prefix() . "_intranet_ci.dt_created) <= '{$filtro_dt_fim}'");
}

$orderby = " order by tbl_intranet_ci.id desc";

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, $additionalSelect, "", "", $orderby);

$output = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];

    if (!empty($aRow['codigo'])) {
        $row[] = '<a href="' . base_url() . 'gestao_corporativa/intra/Comunicado/visualizar_comunicado/?id=' . $aRow['ci_id'] . '" target="_blank">CI ' . html_escape($aRow['codigo']) . '</a>';
    } else {
        $row[] = '<a href="' . base_url() . 'gestao_corporativa/intra/Comunicado/visualizar_comunicado/?id=' . $aRow['ci_id'] . '" target="_blank">CI #' . (int) $aRow['ci_id'] . '</a>';
    }

    $row[] = strtoupper($aRow['titulo']);

    $autor = !empty($aRow['autor_nome']) ? $aRow['autor_nome'] : '—';
    $setor = !empty($aRow['setor_nome']) ? $aRow['setor_nome'] : '—';

    if ($is_my) {
        $row[] = $setor;
        $row[] = !empty($aRow['enviado']) ? date('d/m/Y H:i', strtotime($aRow['enviado'])) : '';

        $send = $this->ci->Comunicado_model->get_comunicado_send($aRow['ci_id']);
        $cientes_qtd = '0 de 0';
        if ($send) {
            $quantidade = count($send);
            $cientes = 0;
            foreach ($send as $staff) {
                if ((int) $staff['status'] === 1) $cientes++;
            }
            $cientes_qtd = "$cientes de $quantidade";
        }
        $row[] = $cientes_qtd;
    } else {
        $row[] = html_escape($autor);
        $row[] = html_escape($setor);
        $row[] = !empty($aRow['enviado']) ? date('d/m/Y H:i', strtotime($aRow['enviado'])) : '';

        $lido = !empty($aRow['lido']) && strtotime($aRow['lido']) ? date('d/m/Y H:i', strtotime($aRow['lido'])) : '';
        $ciente = !empty($aRow['ciente']) && strtotime($aRow['ciente']) ? date('d/m/Y H:i', strtotime($aRow['ciente'])) : '';

        if (empty($lido)) {
            $row[] = '<span class="label label-warning">Não lido</span>';
        } else {
            $row[] = $lido;
        }
        $row[] = $ciente;
    }

    $options = '';
    if ($is_my) {
        $options .= '<a type="button" href="' . base_url('gestao_corporativa/intra/comunicado/config?id=' . $aRow['ci_id']) . '" class="btn btn-xs btn-success mleft5" title="Destinatários"><i class="fa fa-users"></i></a>';
    }
    $options .= '<a type="button" href="' . base_url('gestao_corporativa/intra/comunicado/pdf/' . $aRow['ci_id']) . '" target="_blank" class="btn btn-xs btn-info mleft5" title="PDF"><i class="fa fa-file-pdf-o"></i></a>';

    if ($is_my && (has_permission_intranet('comunicado_interno', '', 'delete') || is_admin())) {
        $options .= '<a type="button" href="' . base_url('gestao_corporativa/intra/comunicado/delete_comunicado?id=' . $aRow['ci_id']) . '" onclick="return confirm(\'Excluir este comunicado?\');" class="btn btn-xs btn-danger mleft5" title="Excluir"><i class="fa fa-trash"></i></a>';
    }

    $row[] = $options;
    $output['aaData'][] = $row;
}
