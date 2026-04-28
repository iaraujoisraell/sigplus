<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [];
$where  = [];



$aColumns = array_merge($aColumns, [
    db_prefix() . 'doctors_visitas.codPaciente as codPaciente',
    db_prefix() . 'doctors_visitas.paciente as paciente',
    db_prefix() . 'doctors_visitas.idade as idade',
    db_prefix() . 'doctors_visitas.telefone as telefone',
    db_prefix() . 'doctors_visitas.convenio as convenio',
    db_prefix() . 'doctors_visitas.data_visita as data_visita',
    db_prefix() . 'doctors_visitas.medico as medico',
    db_prefix() . 'doctors_visitas.tipo_atendimento as tipo_atendimento',
    ]);

$sIndexColumn = 'id';
$sTable       = db_prefix() . 'doctors_visitas';

//$join = '';
$additionalSelect = [
     db_prefix() . 'doctors_visitas.id as id',
    ];


// Fix for big queries. Some hosting have max_join_limit
if (count($custom_fields) > 4) {
    @$this->ci->db->query('SET SQL_BIG_SELECTS=1');
}


// FILTRO CONVÊNIOS

//print_r($convenios); exit;
if ($this->ci->input->post('convenios')) {
    $convenios = $this->ci->input->post('convenios');
    if ($convenios) {
        $count_total_conv = count($convenios);
        $cont = 1;
        $_convenios = "";
        foreach ($convenios as $conv) {
            if($cont == $count_total_conv){
            $_convenios .= "'".$conv."'";
            }else{
            $_convenios .= "'".$conv."',";    
            }
            $cont++;
        }
       
       array_push($where, ' AND '. db_prefix() . 'doctors_visitas.convenio IN (' . $_convenios . ")");
    }
     
   // print_r($convenios); exit;
}

// FILTRO TIPO ATENDIMENTO
if ($this->ci->input->post('tipo_atendimento')) {
    $ctipo_atendimento = $this->ci->input->post('tipo_atendimento');
    
    $count_total_tipo = count($ctipo_atendimento);
        $cont = 1;
        $_tipos = "";
        foreach ($ctipo_atendimento as $tipo) {
            if($cont == $count_total_tipo){
            $_tipos .= "'".$tipo."'";
            }else{
            $_tipos .= "'".$tipo."',";    
            }
            $cont++;
        }
    
       // echo $_tipos; exit;
    
    if (is_array($ctipo_atendimento)) {
        array_push($where, ' AND '. db_prefix() . 'doctors_visitas.tipo_atendimento IN (' . $_tipos . ')');
    }
  
}

//FILTRO MEDICO
if ($this->ci->input->post('medico')) {
    $medico = $this->ci->input->post('medico');
    $count_total_med = count($medico);
        $cont = 1;
        $_medicos = "";
        foreach ($medico as $med) {
            if($cont == $count_total_med){
            $_medicos .= "'".$med."'";
            }else{
            $_medicos .= "'".$med."',";    
            }
            $cont++;
        }
       // echo $_medicos; exit;
    if (is_array($medico)) {
      array_push($where, ' AND '. db_prefix() . 'doctors_visitas.medico IN (' . $_medicos . ')');
    }  
}


// DATA VISITA
if ($this->ci->input->post('ano_visita')) {
    $ano_visita = $this->ci->input->post('ano_visita');
    $data_ate = $this->ci->input->post('data_ate');
    // print_r($procedimentos); exit;
    if ($ano_visita) {
       
      array_push($where, ' AND year(data_visita) = ' . $ano_visita );
    }  
}

$empresa_id = $this->ci->session->userdata('empresa_id');
array_push($where, ' AND '.db_prefix() . 'doctors_visitas.empresa_id = '.$empresa_id );

$orderBy = 'order by tbldoctors_visitas.data_visita asc';
$result  = data_tables_init($aColumns, $sIndexColumn, $sTable, '', $where, $additionalSelect, '', '', $orderBy);
$output  = $result['output'];
$rResult = $result['rResult'];
$cont_n = 0;
foreach ($rResult as $aRow) {
    $row = [];
    for ($i = 0; $i < count($aColumns); $i++) {
        if (strpos($aColumns[$i], 'as') !== false && !isset($aRow[$aColumns[$i]])) {
            $_data = $aRow[strafter($aColumns[$i], 'as ')];
        } else {
            $_data = $aRow[$aColumns[$i]];
        }
        
        
       
        
        /*
        if ($i == 8) {
            if($aRow['tipo'] == 1){
                $tipo_p = 'R$';
            }else{
                $tipo_p = '%';
            }
            
           $_data =  $tipo_p;
        }*/
        
        
        

        $row[]              = $_data;
        $row['DT_RowClass'] = 'has-row-options';
    }


    $output['aaData'][] = $row;
}
