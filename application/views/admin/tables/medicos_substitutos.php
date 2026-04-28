<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [];
$where  = [];

if (has_permission('items', '', 'delete')) {
    $aColumns[] = '1';
}

$aColumns = array_merge($aColumns, [
    db_prefix() . 'unidades_hospitalares.razao_social as razao',
    db_prefix() . 'setores_medicos.nome as nome',
    'titular.nome_profissional as titular',
    'substituto.nome_profissional as substituto',
    db_prefix() . 'medico_substituto.data_validade as date',
    db_prefix() . 'medico_substituto.motivo as motivo',
    db_prefix() . 'medico_substituto.id as opcoes',
    
    ]);
$sIndexColumn = 'id';
$sTable       = db_prefix() . 'medico_substituto';



$join = [
    'LEFT JOIN ' . db_prefix() . 'medicos titular ON titular.medicoid = ' . db_prefix() . 'medico_substituto.id_titular',
     'LEFT JOIN ' . db_prefix() . 'medicos substituto ON substituto.medicoid = ' . db_prefix() . 'medico_substituto.id_substituto',
    'LEFT JOIN ' . db_prefix() . 'setores_medicos ON ' . db_prefix() . 'setores_medicos.id = ' . db_prefix() . 'medico_substituto.setor_id',
    'LEFT JOIN ' . db_prefix() . 'unidades_hospitalares on ' . db_prefix() . 'unidades_hospitalares.id       = ' . db_prefix() . 'medico_substituto.unidade_id',
    ];

$additionalSelect = [
    db_prefix() . 'medico_substituto.id as id',
    db_prefix() . 'medico_substituto.id_titular',
    db_prefix() . 'medico_substituto.id_substituto'
    ];

$empresa_id = $this->ci->session->userdata('empresa_id');

$orderby = " order by tblmedico_substituto.id asc";
array_push($where, 'AND '.db_prefix() . 'medico_substituto.deleted = 0' );
array_push($where, ' AND '.db_prefix() . 'medico_substituto.empresa_id = '.$empresa_id );

//$where   = [];
$result  = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, $additionalSelect, "", "", $orderby);
$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];
    
    
    for ($i = 0; $i < count($aColumns); $i++) {
        if (strpos($aColumns[$i], 'as') !== false && !isset($aRow[$aColumns[$i]])) {
            $_data = $aRow[strafter($aColumns[$i], 'as ')];
        } else {
            $_data = $aRow[$aColumns[$i]];
        }
       
        if ($aColumns[$i] == '1') {
            
             $item_id =  $aRow['id']; 
            $_data = "<label>$item_id</label>";  
           // $_data = '<div class="checkbox"><input type="checkbox" value="' . $aRow['id'] . '"><label></label></div>';
        } else {
            
        
            if (startsWith($aColumns[$i], 'ctable_') && is_date($_data)) {
                $_data = _d($_data);
            }
        }
        
        if($aRow['date'] == '' or $aRow['date'] == '0000-00-00'){
            $aRow['date'] = '';
        } else {
            $aRow['date'] = date("d-m-Y", strtotime($aRow['date']));
        }
        
        $aRow['opcoes'] = '<a type="button" data-toggle="modal" data-target="#edit_substituto' . $aRow['id'] . '" class="btn btn-xs btn-primary" style="margin-left: 2px;"><i class="fa fa-pencil"></i></a>'
                . '<a type="button" href="' . base_url('admin/Medico_substituto/delete?id=' . $aRow['id']) . '" class="btn btn-xs btn-danger _delete" style="margin-left: 2px;"><i class="fa fa-close"></i></a>';
         $aRow['substituto'] = '<a href="'. base_url('admin/medicos/medico/'. $aRow['id_substituto']) .'">' . $aRow['substituto'] . '</a>';
         $aRow['titular'] = '<a href="'. base_url('admin/medicos/medico/'. $aRow['id_titular']) .'">' . $aRow['titular'] . '</a>';
         $row[]              = $_data;
    }


    $output['aaData'][] = $row;
}
