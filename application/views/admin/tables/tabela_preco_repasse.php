<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [];
$where  = [];



$aColumns = array_merge($aColumns, [
    db_prefix() . 'medicos_procedimentos_precos.item_id',
    db_prefix() . 'items.description as procedimento',
    
    db_prefix() . 'convenio.name as convenio',
    db_prefix() . 'medicos.nome_profissional as medico',
    db_prefix() . 'medicos_procedimentos_precos.data_inicio as data_inicio',
    db_prefix() . 'medicos_procedimentos_precos.data_fim as data_fim',
    
    db_prefix() . 'items.rate as rate',
    db_prefix() . 'medicos_procedimentos_precos.valor as valor',
  //  db_prefix() . 'medicos_procedimentos_precos.tipo as tipo',
    ]);

$sIndexColumn = 'id';
$sTable       = db_prefix() . 'medicos_procedimentos_precos';

$join = [
    'LEFT JOIN ' . db_prefix() . 'medicos           ON ' . db_prefix() . 'medicos.medicoid      = ' . db_prefix() . 'medicos_procedimentos_precos.medico_id',
    'LEFT JOIN ' . db_prefix() . 'items             ON ' . db_prefix() . 'items.id              = ' . db_prefix() . 'medicos_procedimentos_precos.item_id',
    'LEFT JOIN ' . db_prefix() . 'items_groups      ON ' . db_prefix() . 'items_groups.id       = ' . db_prefix() . 'items.group_id',
    'LEFT JOIN ' . db_prefix() . 'staff             ON ' . db_prefix() . 'staff.staffid         = ' . db_prefix() . 'medicos_procedimentos_precos.usuario_log',
    'LEFT JOIN ' . db_prefix() . 'convenio          ON ' . db_prefix() . 'convenio.id           = ' . db_prefix() . 'items.convenio_id',
    ];
$additionalSelect = [
    db_prefix() . 'items_groups.name as categoria',
    db_prefix() . 'medicos_procedimentos_precos.id',
    db_prefix() . 'medicos_procedimentos_precos.data_log',
    db_prefix() . 'staff.firstname',
    db_prefix() . 'items.group_id',
      db_prefix() . 'medicos_procedimentos_precos.tipo as tipo',
    db_prefix() . 'items.convenio_id',
    db_prefix() . 'items.id as item_id',
    db_prefix() . 'items.ativo',
    ];


// Fix for big queries. Some hosting have max_join_limit
if (count($custom_fields) > 4) {
    @$this->ci->db->query('SET SQL_BIG_SELECTS=1');
}


// FILTRO CONVÊNIOS
$this->ci->load->model('Convenios_model');

//print_r($convenios); exit;
if ($this->ci->input->post('convenios_procedimentos_repasse')) {
    $convenios = $this->ci->input->post('convenios_procedimentos_repasse');
   
    $_convenios = "";
    if ($convenios) {
       array_push($where, ' AND '. db_prefix() . 'items.convenio_id IN (' . implode(', ', $convenios) . ')');
    }
}

// FILTRO CATEGORIAS
if ($this->ci->input->post('categorias_procedimentos')) {
    $categorias = $this->ci->input->post('categorias_procedimentos');
    if (is_array($categorias)) {
        array_push($where, ' AND '. db_prefix() . 'items.group_id IN (' . implode(', ', $categorias) . ')');
    }
  
}

//FILTRO PROCEDIMENTOS
$procedimentos = $this->ci->input->post('procedimento_items');

if ($this->ci->input->post('procedimento_items')) {
    $procedimentos = $this->ci->input->post('procedimento_items');
    if (is_array($procedimentos)) {
      array_push($where, ' AND '. db_prefix() . 'items.id IN (' . implode(', ', $procedimentos) . ')');
    }  
}



//FILTRO MEDICOS
if ($this->ci->input->post('medicoid')) {
    $medicoid = $this->ci->input->post('medicoid');
    // print_r($procedimentos); exit;
    if (is_array($medicoid)) {
      array_push($where, ' AND '. db_prefix() . 'medicos_procedimentos_precos.medico_id IN (' . implode(', ', $medicoid) . ')');
    }  
}

$empresa_id = $this->ci->session->userdata('empresa_id');
array_push($where, ' AND '.db_prefix() . 'medicos_procedimentos_precos.empresa_id = '.$empresa_id );
array_push($where, ' AND '.db_prefix() . 'medicos.empresa_id = '.$empresa_id );
//print_r($where); exit;
 //array_push($where, ' AND '.db_prefix() . 'items.ativo = 1' );
//$where   = [];
$result  = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, $additionalSelect);
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
        
        
        if ($i == 1) {
            $procedimento = $aRow['procedimento'].' ['.$aRow['categoria'].']';
           $_data =  $procedimento;
        }
        
        if ($i == 4) {
           $_data =  _d($aRow['data_inicio']);
        }
        
        if ($i == 5) {
           $_data =  _d($aRow['data_fim']);
        }
        
        if ($i == 6) {
           $_data =  app_format_money($aRow['rate'], 'R$');
        }
        
        if ($i == 7) {
            if($aRow['tipo'] == 1){
                $valor_n = app_format_money($aRow['valor'], 'R$');
            }else{
                $valor_n = $aRow['valor']. ' % ';
            }
           $_data =  $valor_n;
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
