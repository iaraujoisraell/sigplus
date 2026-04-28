<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [];
$where  = [];


if (has_permission('items', '', 'delete')) {
   // $aColumns[] = '1';
}

$aColumns = array_merge($aColumns, [
    
    db_prefix() . 'competencias_plantoes.mes as mes',
    db_prefix() . 'competencias_plantoes.ano as ano',
    db_prefix() . 'unidades_hospitalares.razao_social as razao_social',
    db_prefix() . 'setores_medicos.nome as setor',
    db_prefix() . 'horario_plantao.hora_inicio as hora_inicio',
    db_prefix() . 'horario_plantao.hora_fim as hora_fim',
    db_prefix() . 'horario_plantao.plantao as plantao',
    db_prefix() . 'staff.firstname as firstname',
    db_prefix() . 'staff.lastname as lastname',
    ]);

$sIndexColumn = 'id';
$sTable       = db_prefix() . 'competencias_plantoes_setores';

$join = [
    'INNER JOIN ' . db_prefix() . 'competencias_plantoes ON '            . db_prefix() . 'competencias_plantoes.id     = '  . db_prefix() . 'competencias_plantoes_setores.competencia_id',
    'INNER JOIN ' . db_prefix() . 'unidades_hospitalares ON '            . db_prefix() . 'unidades_hospitalares.id     = '  . db_prefix() . 'competencias_plantoes_setores.unidade_id',
    'INNER JOIN ' . db_prefix() . 'setores_medicos ON '                  . db_prefix() . 'setores_medicos.id = '            . db_prefix() . 'competencias_plantoes_setores.setor_id',
    'INNER JOIN ' . db_prefix() . 'unidades_hospitalar_configuracao ON ' . db_prefix() . 'unidades_hospitalar_configuracao.id = ' . db_prefix() . 'competencias_plantoes_setores.config_id',
    'INNER JOIN ' . db_prefix() . 'horario_plantao ON '                  . db_prefix() . 'horario_plantao.id             = ' . db_prefix() . 'unidades_hospitalar_configuracao.horario_id',
    'INNER JOIN ' . db_prefix() . 'staff ON '                            . db_prefix() . 'staff.staffid             = ' . db_prefix() . 'competencias_plantoes_setores.user_cadastro',
    ];
$additionalSelect = [
    db_prefix() . 'competencias_plantoes_setores.id ',
    db_prefix() . 'competencias_plantoes_setores.competencia_id ',
    db_prefix() . 'competencias_plantoes_setores.unidade_id ',
    db_prefix() . 'competencias_plantoes_setores.setor_id',
    db_prefix() . 'unidades_hospitalar_configuracao.horario_id',
    db_prefix() . 'competencias_plantoes_setores.user_cadastro as user_cadastro ',
    db_prefix() . 'competencias_plantoes_setores.data_cadastro as data_cadastro ',
    ];


// Fix for big queries. Some hosting have max_join_limit
if (count($custom_fields) > 5) {
    @$this->ci->db->query('SET SQL_BIG_SELECTS=1');
}


// FILTRO CONVÊNIOS
$empresa_id = $this->ci->session->userdata('empresa_id');


//COMPETENCIA
if ($this->ci->input->post('competencia_id')) {
    $competencia_id = $this->ci->input->post('competencia_id');
         array_push($where, ' AND '. db_prefix() . 'competencias_plantoes_setores.competencia_id  = ' . $competencia_id );
}

//FILTRO UNIDADE HOSPITALAR
if ($this->ci->input->post('unidade_id')) {
    $unidade_id = $this->ci->input->post('unidade_id');
      array_push($where, ' AND '. db_prefix() . 'competencias_plantoes_setores.unidade_id  = ' . $unidade_id );
}
//FILTRO SETOR
if ($this->ci->input->post('setor_id')) {
    $setor_id = $this->ci->input->post('setor_id');
    //  array_push($where, ' AND '. db_prefix() . 'competencias_plantoes_setores.setor_id  = ' . $setor_id );
}
//FILTRO HORÁRIO
if ($this->ci->input->post('horario_id')) {
    $horario_id = $this->ci->input->post('horario_id');
    //  array_push($where, ' AND '. db_prefix() . 'unidades_hospitalar_configuracao.horario_id  = ' . $horario_id );
}


array_push($where, ' AND '.db_prefix() . 'competencias_plantoes_setores.deleted = 0' );
array_push($where, ' AND '.db_prefix() . 'competencias_plantoes_setores.empresa_id = '.$empresa_id );

$orderby = "";

//$where   = [];
$result  = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, $additionalSelect);
$output  = $result['output'];
$rResult = $result['rResult'];
$soma_parcela = 0;
foreach ($rResult as $aRow) {
    $row = [];
  
    //$info = render_tags($aRow['numero_documento']);
    $row[] = $aRow['mes'].'/'.$aRow['ano'];
    
    // PLANO DE CONTAS
    $row[] = $aRow['razao_social'];
    // FORNECEDOR
    $row[] = $aRow['setor'];
    // BANCO   
   // $row[] = $aRow['banco'].'<br> ('.$aRow['agencia'].'['. $aRow['numero_conta'] .'])';
    // CENTRO CUSTO
    $row[] = $aRow['hora_inicio'];
    
    //PARCELAS
    
    $row[] = $aRow['hora_fim']; 
    
    
    $row[] = $aRow['plantao']; 
    
    //print_r($row); exit;
    // STATUS
    $user = $aRow['firstname'].' '.$aRow['lastname'];
    $data_cadastro = $aRow['data_cadastro'];
    
    $row[] = $user.'<br>'.$data_cadastro;
    
    $unidade_id = $aRow['unidade_id'];
    $calendario = "<a class='btn btn-primary' target='_blank' href=".admin_url('gestao_plantao/calendar/'.$unidade_id)."> <i class='fa fa-calendar'></i> Visualizar </a>";
    $row[] = $calendario;
     $row['DT_RowClass'] = 'has-row-options';

    $row = hooks()->apply_filters('invoices_table_row_data', $row, $aRow);

    $output['aaData'][] = $row;
}
