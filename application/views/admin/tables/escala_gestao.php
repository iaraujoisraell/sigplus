<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [];
$where  = [];


if (has_permission('items', '', 'delete')) {
    $aColumns[] = '1';
}

$aColumns = array_merge($aColumns, [
    
    db_prefix() . 'events.dia_semana as dia_semana',
    db_prefix() . 'events.dia as dia',
    db_prefix() . 'events.mes as mes',
    db_prefix() . 'events.start as start',
    db_prefix() . 'events.end as end',
    db_prefix() . 'unidades_hospitalares.razao_social as unidade_hospitalar',
    db_prefix() . 'setores_medicos.nome as setor',
    'tit.nome_profissional as titular',
    'tit.nome_reduzido as titular_nome_reduzido',
    'esc.nome_profissional as escalado',
    'esc.nome_reduzido as escalado_nome_reduzido',
    'subs.nome_profissional as substitulo',
    'subs.nome_reduzido as substitulo_nome_reduzido',
    'plant.nome_profissional as plantonista',
    'plant.nome_reduzido as plantonista_nome_reduzido',
    'cred.nome_profissional as creditado',
    'cred.nome_reduzido as creditado_nome_reduzido',
    db_prefix() . 'events.status as status',
    db_prefix() . 'events.status_faturamento as status_faturamento',
    db_prefix() . 'events.quantidade as quantidade',
    db_prefix() . 'staff.firstname as firstname',
    db_prefix() . 'staff.lastname as lastname',
    ]);

$sIndexColumn = 'eventid';
$sTable       = db_prefix() . 'events';

$join = [
    'INNER JOIN ' . db_prefix() . 'medicos tit on tit.medicoid          = ' . db_prefix() . 'events.titular_id',
    'LEFT JOIN ' . db_prefix() . 'medicos esc on esc.medicoid          = ' . db_prefix() . 'events.medico_escalado_id',
    'LEFT JOIN ' . db_prefix() . 'medicos subs on subs.medicoid         = ' . db_prefix() . 'events.substituto',
    'LEFT JOIN ' . db_prefix() . 'medicos plant on plant.medicoid       ='  . db_prefix() . 'events.medico_plantonista_id',
    'LEFT JOIN ' . db_prefix() . 'medicos cred on cred.medicoid         ='  . db_prefix() . 'events.medico_creditado',
    'INNER JOIN ' . db_prefix() . 'unidades_hospitalares ON ' . db_prefix() . 'unidades_hospitalares.id             = ' . db_prefix() . 'events.unidade_id',
    'INNER JOIN ' . db_prefix() . 'setores_medicos ON '       . db_prefix() . 'setores_medicos.id                   = ' . db_prefix() . 'events.setor_id',
    'LEFT JOIN ' . db_prefix() . 'staff ON '                            . db_prefix() . 'staff.staffid              = ' . db_prefix() . 'events.user_log_checkin'
    
   
    ];
$additionalSelect = [
    db_prefix() . 'events.eventid as id',
    db_prefix() . 'events.unidade_id ',
    db_prefix() . 'events.setor_id ',
    db_prefix() . 'events.color ',
    db_prefix() . 'events.public',
    db_prefix() . 'events.data_log_checkin as data_log_checkin',
    db_prefix() . 'events.titular_id',
    db_prefix() . 'events.medico_escalado_id ',
    db_prefix() . 'events.substituto',
    db_prefix() . 'events.medico_plantonista_id',
    db_prefix() . 'events.medico_creditado',
    db_prefix() . 'events.troca_horario',
    db_prefix() . 'events.escalado_avulso',
    db_prefix() . 'events.troca_escalado',
    db_prefix() . 'events.troca_substituto',
    ];

//echo 'aqussi'; exit;
// Fix for big queries. Some hosting have max_join_limit
//if (count($custom_fields) > 5) {
    //@$this->ci->db->query('SET SQL_BIG_SELECTS=1');
//}


$empresa_id = $this->ci->session->userdata('empresa_id');

// FILTRO COMPETENCIA
if ($this->ci->input->post('competencia_id')) {
    $competencia_id = $this->ci->input->post('competencia_id');
         array_push($where, ' AND '. db_prefix() . 'events.competencia_id  = ' . $competencia_id );
}ELSE{
    $competencia_id  = 0;
    array_push($where, ' AND '. db_prefix() . 'events.competencia_id  = ' . $competencia_id );
}

//FILTRO UNIDADE HOSPITALAR
if ($this->ci->input->post('unidade_id')) {
    $unidade_id = $this->ci->input->post('unidade_id');
      array_push($where, ' AND '. db_prefix() . 'events.unidade_id  = ' . $unidade_id );
}
//FILTRO SETOR
if ($this->ci->input->post('setor_id')) {
    $setor_id = $this->ci->input->post('setor_id');
      array_push($where, ' AND '. db_prefix() . 'events.setor_id  = ' . $setor_id );
}
//FILTRO HORÁRIO
if ($this->ci->input->post('horario_id')) {
    $horario_id = $this->ci->input->post('horario_id');
      array_push($where, ' AND '. db_prefix() . 'events.horario_id  = ' . $horario_id );
}
//FILTRO DIA SEMANA
if ($this->ci->input->post('dia_semana')) {
    $dia_semana = $this->ci->input->post('dia_semana');
   
      array_push($where, ' AND '. db_prefix() . 'events.dia_semana  = ' . "'$dia_semana'" );
}
//FILTRO MEDICO TITULAR
if ($this->ci->input->post('titular_id')) {
    $titular_id = $this->ci->input->post('titular_id');
   
      array_push($where, ' AND '. db_prefix() . 'events.titular_id  = ' . "'$titular_id'" );
}

//FILTRO STATUS ESCALADO
if ($this->ci->input->post('status_escalado')) {
    $status_escalado = $this->ci->input->post('status_escalado');
    if($status_escalado == 1){
        // ocupado
        array_push($where, ' AND '. db_prefix() . 'events.medico_escalado_id > 0 ' );
    }else if($status_escalado == 2){
        // disponível
        array_push($where, ' AND '. db_prefix() . 'events.medico_escalado_id = ""' );
    }
      
}

//FILTRO MEDICO ESCALADO
if ($this->ci->input->post('escalado_filtro_id')) {
    $escalado_filtro_id = $this->ci->input->post('escalado_filtro_id');
   
      array_push($where, ' AND '. db_prefix() . 'events.medico_escalado_id  = ' . "'$escalado_filtro_id'" );
}

//FILTRO SUBSTITUTO
if ($this->ci->input->post('substituto_id')) {
    $substituto_id= $this->ci->input->post('substituto_id');
   
      array_push($where, ' AND '. db_prefix() . 'events.substituto  = ' . "'$substituto_id'" );
}

//FILTRO MEDICOS DUPLICADOS
if ($this->ci->input->post('med_duplicados')) {
    
    $med_duplicados = $this->ci->input->post('med_duplicados');
    $array_duplicados = explode("_",$med_duplicados);
    //print_r($array_duplicados); exit;
      /* array_push($where, ' AND '. db_prefix() . 'events.medico_escalado_id  = ' . $array_duplicados[0]
           . ' AND '. db_prefix(). 'events.dia  = ' . $array_duplicados[1]
           . ' AND '. db_prefix(). 'events.horario_id  = ' . $array_duplicados[2]);*/
    
     array_push($where, ' AND '. db_prefix() . 'events.medico_escalado_id  = ' . $array_duplicados[0]);
     array_push($where, ' AND '. db_prefix() . 'events.dia  = ' . $array_duplicados[1]);
     array_push($where, ' AND '. db_prefix() . 'events.horario_id  = ' . $array_duplicados[2]);
             
      
}


array_push($where, ' AND '.db_prefix() . 'events.deleted = 0' );
array_push($where, ' AND '.db_prefix() . 'events.empresa_id = '.$empresa_id );

$orderby = " order by tblevents.dia asc, tblevents.titular_id asc";

//$where   = [];
$result  = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, $additionalSelect, "", "", $orderby);
$output  = $result['output'];
$rResult = $result['rResult'];
$soma_parcela = 0;
foreach ($rResult as $aRow) {
    $row = [];
    
    $color = $aRow['color']; 
    $troca_horario = $aRow['troca_horario']; 
    $escalado_avulso = $aRow['escalado_avulso'];
    $troca_escalado = $aRow['troca_escalado'];
    $row[] = '<div class="checkbox"><input type="checkbox" value="' . $aRow['id'] . '"><label></label></div>';
    //DIA
    $row[] = $aRow['dia']; 
    //DIA SEMANA
    $row[] = $aRow['dia_semana'];
    //UNIDADE
    $row[] = $aRow['unidade_hospitalar'];
    //SETOR
    $row[] = $aRow['setor'];
    // START
    $row[] = substr($aRow['start'], 10, 6);
    // END
    $row[] = substr($aRow['end'], 10, 6);
    // QUANTIDADE PLANTAO
    $row[] = $aRow['quantidade'];
    // TITULAR
    $row[] = $aRow['titular_nome_reduzido'];
    //ESCALADO
    $escalado_nome = $aRow['escalado_nome_reduzido'];
    if($color){
        if($troca_horario){
            $texto = 'Troca de Horário';
        }
        if($escalado_avulso){
            $texto = 'Escalado Avulso';
        }
        if($troca_escalado){
            $texto = 'Troca de Escalado';
        }
        $escalado = "<label class='label' title='$texto' style='background-color: $color'> $escalado_nome </label>";
    }else{
        $escalado = $escalado_nome;
    }
    $row[] = $escalado; 
    //SUBSTITUTO
    
    if($aRow['substitulo_nome_reduzido']){
        $substituto_nome = $aRow['substitulo_nome_reduzido'];
        $troca_substituto = $aRow['troca_substituto'];
        if($troca_substituto){
            // rodizio
            $substituto = "<label class='label' title='$texto' style='background-color: #BDBDBD'> $substituto_nome </label>";
        }else{
            // fixo
            $substituto = "<label class='label' title='$texto' style='background-color: #FAAC58'> $substituto_nome </label>";
        }
    }else{
        $substituto = $aRow['substitulo_nome_reduzido'];
    }
            
    $row[] = $substituto; 
    //PLANTONISTA
    $row[] = $aRow['plantonista_nome_reduzido']; 
    //CREDITADO
    $row[] = $aRow['creditado_nome_reduzido']; 
    // STATUS PLANTAO
    $status = $aRow['status'];
    if($status == 1){
        $user = $aRow['firstname'].' '.$aRow['lastname'];
    $data_cadastro = $aRow['data_log_checkin'];
        $status_checkin = '<label class="label label-success"> REGISTRADO </label>'.$user.'<br>'.$data_cadastro;;
    }else{
        $status_checkin = '<label class="label label-warning"> ABERTO </label>';
    }
    $row[] = $status_checkin; 
    // STATUS FATURAMENTO
    $status_fat = $aRow['status_faturamento'];
    if($status_fat == 1){
        $status_faturamento = '<label class="label label-success"> FATURADO </label>';
    }else{
        $status_faturamento = '<label class="label label-warning"> AGUARDANDO </label>';
    }
    $row[] = $status_faturamento; 
    
    
   
    
   
     $row['DT_RowClass'] = 'has-row-options';

    $row = hooks()->apply_filters('invoices_table_row_data', $row, $aRow);

    $output['aaData'][] = $row;
}
