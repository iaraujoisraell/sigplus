<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [];
$where  = [];


if (has_permission('items', '', 'delete')) {
    $aColumns[] = '1';
}

$aColumns = array_merge($aColumns, [
    db_prefix() . 'escala_fixa.id as id',
    db_prefix() . 'unidades_hospitalares.fantasia as fantasia',
    db_prefix() . 'setores_medicos.nome as setor',
    db_prefix() . 'escala_fixa.dia_semana as dia',
    'm.nome_reduzido as nome_titular',
    'm_sub.nome_reduzido as substituto_nome',
    
    'hp.hora_inicio as hi',
    'hp.hora_fim as hf',
    'hp.plantao as plantao',
    
    ]);

$sIndexColumn = 'id';
$sTable       = db_prefix() . 'escala_fixa';

$join = [
    'INNER JOIN ' . db_prefix() . 'unidades_hospitalar_configuracao on ' . db_prefix() . 'unidades_hospitalar_configuracao.id  = ' . db_prefix() . 'escala_fixa.config_id',
    'INNER JOIN ' . db_prefix() . 'medicos m ON m.medicoid = ' . db_prefix() . 'escala_fixa.medico_id',
    'INNER JOIN ' . db_prefix() . 'setores_medicos ON ' . db_prefix() . 'setores_medicos.id = ' . db_prefix() . 'unidades_hospitalar_configuracao.setor_id',
    'INNER JOIN ' . db_prefix() . 'unidades_hospitalares on ' . db_prefix() . 'unidades_hospitalares.id       = ' . db_prefix() . 'setores_medicos.unidade_id',
    'INNER JOIN ' . db_prefix() . 'horario_plantao hp on hp.id = '.db_prefix() . 'unidades_hospitalar_configuracao.horario_id',
    'LEFT JOIN ' . db_prefix() . 'horario_plantao hpm ON hpm.id = ' . db_prefix() . 'escala_fixa.horario_id',
    'LEFT JOIN ' . db_prefix() . 'medico_substituto msubs ON msubs.id_titular = ' . db_prefix() . 'escala_fixa.id AND (msubs.id = (SELECT MAX(f1.id) FROM ' . db_prefix() . 'medico_substituto f1 WHERE f1.id_titular = ' . db_prefix() . 'escala_fixa.id))',
    'LEFT JOIN ' . db_prefix() . 'medicos m_sub on m_sub.medicoid = msubs.id_substituto'
    
   
    ];
$additionalSelect = [
    
    db_prefix() . 'escala_fixa.medico_id as titular_id',
 //   db_prefix() . 'medico_substituto.id_substituto as substituto_id',
    'hpm.hora_inicio as inicio',
    'hpm.hora_fim as fim',
    'hpm.plantao as plantao_medico',
    ];

//echo 'aqussi'; exit;
// Fix for big queries. Some hosting have max_join_limit
//if (count($custom_fields) > 5) {
    //@$this->ci->db->query('SET SQL_BIG_SELECTS=1');
//}


$empresa_id = $this->ci->session->userdata('empresa_id');


//FILTRO UNIDADE HOSPITALAR
if ($this->ci->input->post('unidade_id')) {
    $unidade_id = $this->ci->input->post('unidade_id');
      array_push($where, ' AND '. db_prefix() . 'unidades_hospitalares.id  = ' . $unidade_id );
}
//FILTRO SETOR
if ($this->ci->input->post('setor_id')) {
    $setor_id = $this->ci->input->post('setor_id');
      array_push($where, ' AND '. db_prefix() . 'setores_medicos.id  = ' . $setor_id );
}
//FILTRO HORÁRIO
if ($this->ci->input->post('horario_id')) {
    $horario_id = $this->ci->input->post('horario_id');
      array_push($where, ' AND '. db_prefix() . 'unidades_hospitalar_configuracao.horario_id  = ' . $horario_id );
}
//FILTRO DIA SEMANA
if ($this->ci->input->post('dia_semana')) {
    $dia_semana = $this->ci->input->post('dia_semana');
   
      array_push($where, ' AND '. db_prefix() . 'escala_fixa.dia_semana  = ' . "'$dia_semana'" );
}
//FILTRO MEDICO TITULAR
if ($this->ci->input->post('titular_id')) {
    $titular_id = $this->ci->input->post('titular_id');
   
      array_push($where, ' AND '. db_prefix() . 'escala_fixa.medico_id  = ' . "'$titular_id'" );
}

//FILTRO MEDICO ESCALADO
if ($this->ci->input->post('escalado_filtro_id')) {
    $escalado_filtro_id = $this->ci->input->post('escalado_filtro_id');
   
      array_push($where, ' AND '. db_prefix() . 'escala_fixa.medico_escalado_id  = ' . "'$escalado_filtro_id'" );
}

//FILTRO SUBSTITUTO
if ($this->ci->input->post('substituto_id')) {
    $substituto_id= $this->ci->input->post('substituto_id');
   
      array_push($where, ' AND msubs.id_substituto  = ' . "'$substituto_id'" );
}


if ($this->ci->input->post('ordernar_por')) {
    $ordernar_por = $this->ci->input->post('ordernar_por');
  
    if($ordernar_por == 1){
        $orderby = 'ORDER BY
                     tblunidades_hospitalares.id asc,
                     tblsetores_medicos.id asc,
                     tblescala_fixa.dia_id asc';
    }else if($ordernar_por == 2){
        $orderby = 'ORDER BY tblescala_fixa.dia_id asc';
    }
}

array_push($where, ' AND '.db_prefix() . 'escala_fixa.deleted = 0' );
array_push($where, ' AND '.db_prefix() . 'escala_fixa.empresa_id = '.$empresa_id );

//$orderby = " order by tblevents.dia asc, tblevents.titular_id asc";

//$where   = [];
$result  = data_tables_init_escala_fixa($aColumns, $sIndexColumn, $sTable, $join, $where, $additionalSelect, "", "", $orderby);
$output  = $result['output'];
$rResult = $result['rResult'];
$soma_plantao = 0;
foreach ($rResult as $aRow) {
    $row = [];

    $row[] = '<div class="checkbox">  <input type="checkbox" value="' . $aRow['id'] . '"><label></label></div>';
    $row[] = $aRow['fantasia']; 
    $row[] = $aRow['setor'];
    $row[] = $aRow['dia'];
    
    $row[] = $aRow['nome_titular'];
    
    
    $row[] = $aRow['substituto_nome']; // $nome_reduzido_subs;
    
    if($aRow['inicio']){
        $hora_inicio =  $aRow['inicio'];
    }else{
        $hora_inicio =  $aRow['hi'];
    }
    $row[] = $hora_inicio;
    
    
    $hora_fim = '';

    if($aRow['fim']){
        $hora_fim =  $aRow['fim'];
    }else{
        $hora_fim =  $aRow['hf'];
    }
    $row[] = $hora_fim;
    
    
    $plantao = '';
           
    if($aRow['plantao_medico']){
        $plantao =  $aRow['plantao_medico'];
    }else{
        $plantao =  $aRow['plantao'];
    }
    $soma_plantao += $plantao;
    $row[] = $plantao;
    
    //print_R($row); exit;

     $row['DT_RowClass'] = 'has-row-options';

    $row = hooks()->apply_filters('invoices_table_row_data', $row, $aRow);

    $output['aaData'][] = $row;
}

$row_soma['DT_RowClass'] = 'has-row-options';
$row_soma[] = "";
$row_soma[] = "";
$row_soma[] = "";
$row_soma[] = "";
$row_soma[] = "";
$row_soma[] = "";
$row_soma[] = "";
$row_soma[] = "";
$row_soma[] = $soma_plantao;
$output['aaData'][] = $row_soma;