<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [];
$where  = [];


if (has_permission('items', '', 'delete')) {
    $aColumns[] = '1';
}

$aColumns = array_merge($aColumns, [
    db_prefix() . 'fin_bancos.banco as banco',
    'agencia',
    db_prefix() . 'fin_bancos.numero_conta'
    ]);

$sIndexColumn = 'id';
$sTable       = db_prefix() . 'fin_bancos';


$join = [
   // 'LEFT JOIN ' . db_prefix() . 'fin_categories ON ' . db_prefix() . 'fin_categories.id = ' . db_prefix() . 'fin_plano_contas.categoria_id',
    ];
$additionalSelect = [
    'id',
    ];


// Fix for big queries. Some hosting have max_join_limit
if (count($custom_fields) > 4) {
    @$this->ci->db->query('SET SQL_BIG_SELECTS=1');
}


// FILTRO CONVÊNIOS
$empresa_id = $this->ci->session->userdata('empresa_id');


/* FILTRO CATEGORIAS
if ($this->ci->input->post('categorias')) {
    $categorias = $this->ci->input->post('categorias');
    if (is_array($categorias)) {
         array_push($where, ' AND '. db_prefix() . 'fin_plano_contas.categoria_id IN (' . implode(', ', $categorias) . ')');
    }
  
}

//FILTRO PROCEDIMENTOS

if ($this->ci->input->post('natureza')) {
    $natureza = $this->ci->input->post('natureza');
    if ($natureza) {
      array_push($where, ' AND '. db_prefix() . 'fin_plano_contas.natureza = '.$natureza);
    }
   
}*/

array_push($where, 'AND '.db_prefix() . 'fin_bancos.deleted = 0' );
array_push($where, ' AND '.db_prefix() . 'fin_bancos.empresa_id = '.$empresa_id );
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
        }else if ($i == '1') {
            $_data = '<a href="#" data-toggle="modal" data-target="#add_banco_modal" data-id="' . $aRow['id'] . '">' . $aRow['banco'] . '</a>';
            $_data .= '<div class="row-options">';

            if (has_permission('items', '', 'edit')) {
                $_data .= '<a href="#" data-toggle="modal" data-target="#add_banco_modal" data-id="' . $aRow['id'] . '">' . _l('edit') . '</a>';
            }

            if (has_permission('items', '', 'delete')) {
                $_data .= ' | <a href="' . admin_url('financeiro/delete_bancos/' . $aRow['id']) . '" class="text-danger _delete">' . _l('delete') . '</a>';
            }
            $_data .= '</div>';
            
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
