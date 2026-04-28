<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [];
$where  = [];


if (has_permission('items', '', 'delete')) {
    $aColumns[] = '1';
}

$aColumns = array_merge($aColumns, [
    db_prefix() . 'fin_centro_custo_tipo.descricao as tipo_cc',
    db_prefix() . 'fin_centro_custo.descricao as descricao',
    db_prefix() .'fin_centro_custo.ativo as ativo'
    ]);

$sIndexColumn = 'id';
$sTable       = db_prefix() . 'fin_centro_custo';

$join = [
    'LEFT JOIN ' . db_prefix() . 'fin_centro_custo_tipo ON ' . db_prefix() . 'fin_centro_custo_tipo.id = ' . db_prefix() . 'fin_centro_custo.cc_tipo_id',
    ];
$additionalSelect = [
    db_prefix() . 'fin_centro_custo.id ',
    ];


// Fix for big queries. Some hosting have max_join_limit
if (count($custom_fields) > 4) {
    @$this->ci->db->query('SET SQL_BIG_SELECTS=1');
}

$empresa_id = $this->ci->session->userdata('empresa_id');
$tipo = $this->ci->input->post('tipo_cc');

// FILTRO TIPO CENTRO DE CUSTO
if ($this->ci->input->post('tipo_cc')) {
    $tipo = $this->ci->input->post('tipo_cc');
   
        array_push($where, ' AND '. db_prefix() . 'fin_centro_custo.cc_tipo_id ='. $tipo);
   
  
}


array_push($where, ' AND '.db_prefix() . 'fin_centro_custo.deleted = 0' );
array_push($where, ' AND '.db_prefix() . 'fin_centro_custo.empresa_id = '.$empresa_id );
//$where   = [];
$result  = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, $additionalSelect);
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
        }else if ($i == '2') {
            $_data = '<a href="#" data-toggle="modal" data-target="#add_centro_custo_modal" data-id="' . $aRow['id'] . '">' . $aRow['descricao'] . '</a>';
            $_data .= '<div class="row-options">';

            if (has_permission('items', '', 'edit')) {
                $_data .= '<a href="#" data-toggle="modal" data-target="#add_centro_custo_modal" data-id="' . $aRow['id'] . '">' . _l('edit') . '</a>';
            }

            if (has_permission('items', '', 'delete')) {
                $_data .= ' | <a href="' . admin_url('financeiro/delete_centro_custo/' . $aRow['id']) . '" class="text-danger _delete">' . _l('delete') . '</a>';
            }
            $_data .= '</div>';
            
        }elseif ($i == '3') {
            if($aRow['ativo'] == 1){
                $ativo = '<label class="label label-success">Ativo</label>';
            }else{
                $ativo = '<label class="label label-warning">Inativo</label>';
            }
            $_data = $ativo;
            
        } else {
        
            if (startsWith($aColumns[$i], 'ctable_') && is_date($_data)) {
                $_data = _d($_data);
            }
        }

        $row[]              = $_data;
        $row['DT_RowClass'] = 'has-row-options';
    }


    $output['aaData'][] = $row;
}
