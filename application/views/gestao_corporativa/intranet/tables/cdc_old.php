<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [];
$where = [];

$aColumns = array_merge($aColumns, [
    db_prefix() . '_intranet_cdc.codigo as codigo',
    db_prefix() . '_intranet_cdc.data_cadastro as data_cadastro',
    db_prefix() . '_intranet_cdc.titulo as titulo',
    db_prefix() . 'departments.name as name',
    db_prefix() . '_intranet_categorias.titulo as categoria',
        ]);
$sIndexColumn = 'id';
$sTable = db_prefix() . '_intranet_cdc';

$join = [
    'INNER JOIN ' . db_prefix() . '_intranet_categorias ON ' . db_prefix() . '_intranet_categorias.id = ' . db_prefix() . '_intranet_cdc.categoria_id',
    'INNER JOIN ' . db_prefix() . 'departments ON ' . db_prefix() . 'departments.departmentid = ' . db_prefix() . '_intranet_cdc.setor_id',
];

$additionalSelect = [
    db_prefix() . '_intranet_cdc.numero_versao as versao',
    db_prefix() . '_intranet_cdc.file as file',
    db_prefix() . '_intranet_cdc.data_publicacao as date',
    'tbl_intranet_cdc.id as id',
    'tbl_intranet_cdc.user_cadastro',
    'tbl_intranet_cdc.pdf_principal as pdf_principal',
    'tbl_intranet_cdc.sequencial as sequencial',
    'tbl_intranet_cdc.pasta_destino as pasta_destino',
    'tbl_intranet_cdc.file as doc',
    'tbldepartments.name as responsavel',
    'tbl_intranet_cdc.publicado as pub',
    'tbl_intranet_cdc.setor_id as dep',
];

$staff_id = get_staff_user_id();
if ($this->ci->input->post('loading')) {
    //array_push($join, 'LEFT JOIN ' . db_prefix() . '_intranet_send ON ' . db_prefix() . '_intranet_send.destino = ' . db_prefix() . '_intranet_categorias.responsavel');
    array_push($join, 'INNER  JOIN ' . db_prefix() . '_intranet_categorias_loading ON ' . db_prefix() . '_intranet_categorias_loading.rel_id = ' . db_prefix() . '_intranet_cdc.id');
    array_push($join, 'INNER JOIN ' . db_prefix() . '_intranet_categorias_fluxo ON ' . db_prefix() . '_intranet_categorias_fluxo.id = ' . db_prefix() . '_intranet_categorias_loading.fluxo_id');
    array_push($where, " AND tbl_intranet_categorias_loading.rel_type = 'cdc'");
    array_push($where, " AND tbl_intranet_categorias_loading.staff_id = " . get_staff_user_id());
    array_push($where, " AND tbl_intranet_categorias_loading.fluxo_sequencia != 0");
    array_push($aColumns, "tbl_intranet_categorias_fluxo.titulo as responsabilidade");
    array_push($additionalSelect, "tbl_intranet_categorias_loading.dt_aprovacao as dt_aprovacao");
    array_push($additionalSelect, "tbl_intranet_categorias_loading.status as status_");
    array_push($additionalSelect, "tbl_intranet_categorias_loading.id as processo_id");
    array_push($where, 'AND ' . db_prefix() . '_intranet_cdc.versao_atual = 1 or ' . db_prefix() . '_intranet_cdc.publicado = 0');
} elseif ($this->ci->input->post('categoria_id')) {
    array_push($where, 'AND ' . db_prefix() . '_intranet_cdc.categoria_id = ' . $this->ci->input->post('categoria_id'));
    array_push($where, 'AND ' . db_prefix() . '_intranet_cdc.versao_atual = 1 or ' . db_prefix() . '_intranet_cdc.publicado = 0');
} else {
    array_push($join, 'LEFT JOIN ' . db_prefix() . '_intranet_send ON ' . db_prefix() . '_intranet_send.rel_id = ' . db_prefix() . '_intranet_cdc.id');
    array_push($where, " AND tbl_intranet_send.rel_type = 'cdc'");
    array_push($where, " AND tbl_intranet_send.staff = 0");

    $this->ci->load->model('Departments_model');
    $deps = $this->ci->Departments_model->get_staff_departments('', true);
    //print_r($deps); exit;
    $deps = implode(',', $deps);
    if ($deps) {
        array_push($where, " AND tbl_intranet_send.destino in($deps)");
    }

    array_push($where, 'AND ' . db_prefix() . '_intranet_cdc.publicado = 1');
    array_push($where, 'AND ' . db_prefix() . '_intranet_cdc.versao_atual = 1');
    
    $group_by = 'group by id';
}

$empresa_id = $this->ci->session->userdata('empresa_id');
array_push($where, ' AND ' . db_prefix() . '_intranet_cdc.empresa_id = ' . $empresa_id);
array_push($where, 'AND ' . db_prefix() . '_intranet_cdc.deleted = 0');

//$where   = [];
//$orderby = " order by tbl_intranet_cdc.sequencial desc";

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, $additionalSelect, $group_by, "", $orderby);
$output = $result['output'];
$rResult = $result['rResult'];
//print_r($rResult);
foreach ($rResult as $aRow) {
    $row = [];

    $codigo = '';
    if ($this->ci->input->post('loading') || $this->ci->input->post('categoria_id')) {
        $codigo = '<a href="' . base_url('gestao_corporativa/cdc/see?id=' . $aRow['id']) . '">' . $aRow['codigo'] . '</a>';
    } else {

        $codigo = '<a  href="' . base_url('gestao_corporativa/Cdc/view_cdc?id=' . $aRow['id']) . '" target="_blank">' . $aRow['codigo'] . '</a>';
    }
    if ($aRow['pub'] == 1) {
        $codigo .= '<br> Publicado: ' . _d($aRow['date']);
    }
    $codigo .= '<br> Versão: ' . $aRow['versao'];
    if ($this->ci->input->post('categoria_id')) {
        $caminho = '';
        $btn = '';
        $btn = '<br>';
        $btn .= '<a type="button" href="' . base_url('gestao_corporativa/cdc/destinatarios/' . $aRow['id']) . '" class="btn btn-xs btn-success"  ><i class="fa fa-users"></i></a>';

        $disabled = '';
        if (!$aRow['doc']) {
            $disabled = 'disabled';
        }
        $btn .= '<button type="button" target="_blank" onclick="document.getElementById(' . "'form'" . ').submit();" class="btn btn-xs btn-primary" style="margin-left: 2px;" ' . $disabled . '><i class="fa fa-file-pdf-o"></i></button>';
        if ($aRow['pub'] == 1) {
           $btn .= '<a type="button" href="' . base_url('gestao_corporativa/cdc/historico_versao/' . $aRow['id']) . '" class="btn btn-xs btn-warning" style="margin-left: 2px;"><i class="fa fa-list"></i></a>';
        }

        $btn .= '<button class="btn btn-xs btn-default"><icon class="fa fa-user"></icon></button>';

        $btn .= form_open(base_url('gestao_corporativa/Intranet_general/file_'), array('id' => 'form', 'method' => 'post', 'target' => '_blank'))
                . '<input type="hidden" name="file" value="arquivos/cdc_arquivos/cdc/' . $aRow['doc'] . '">'
                . '<input type="hidden" name="name" value="' . $aRow['codigo'] . '">';
        if ($aRow['pub'] != 1) {
            $btn .= ' <input type="hidden" name="draft" value="1">';
        }
        $btn .= form_close();
        $codigo .= $btn;
    } elseif (!$this->ci->input->post('loading')) {
        $this->ci->load->model('Cdc_model');
        $result = $this->ci->Cdc_model->get_ciencia($aRow['id']);
        if ($result == true) {
            $msg = 'CIENTE';
            $alert = 'success';
        } else {
            $msg = 'AGUARDANDO CIÊNCIA';
            $alert = 'warning';
        }
        $codigo .= "<br><span class='label label-sm label-$alert'>$msg</span>";
    }
    $row[] = $codigo;

    $titulo = strtoupper($aRow['titulo']);
    if ($this->ci->input->post('categoria_id')) {
        $titulo .= '<br>';
        if ($aRow['pub'] == 1) {
            $titulo .= '<span class="label label-sm label-success">PUBLICADO</span>';
            if ($aRow['pub'] == 1) {
                $titulo .= '<br>' . _d($aRow['date']);
            }
        } else {
            $titulo .= '<span class="label label-sm label-info">AGUARDANDNO APROVAÇÃO</span>';
        }
    }
    $row[] = $titulo;

    if ($this->ci->input->post('loading')) {
        $responsability = strtoupper($aRow['responsabilidade']);

        $this->ci->load->model('Cdc_model');
        $processo_atual = $this->ci->Cdc_model->get_fluxo_atual($aRow['id']);
        $responsability .= '<br>';
        if ($aRow['status_'] == 1) {
            $responsability .= '<span class="label label-sm label-success">Avaliado</span><br>' . _d($aRow['dt_aprovacao']);
        } else if ($processo_atual->id == $aRow['processo_id']) {
            $responsability .= '<span class="label label-sm label-warning">Pendente</span>';
        } else {
            $responsability .= '<span class="label label-sm label-info">Aguardando Avaliação</span>';
        }

        $row[] = $responsability;
    }
    if (!$this->ci->input->post('categoria_id')) {
        $row[] = strtoupper($aRow['categoria']);
    }
    if ($aRow['name']) {
        $row[] = $aRow['name'];
    } else {
        $row[] = 'SETOR NÃO VINCULADO';
    }

     $row[] = _d($aRow['data_cadastro']);



    $output['aaData'][] = $row;
}
?>
