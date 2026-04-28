<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [];
$where  = [];


if (has_permission('items', '', 'delete')) {
    $aColumns[] = '1';
}

$aColumns = array_merge($aColumns, [
    db_prefix() . '_intranet_link.nome as titulo',
    db_prefix() . '_intranet_link.dt_created as date'
    ]);
$sIndexColumn = 'id';
$sTable       = db_prefix() . '_intranet_link';



$join = [
   // 'LEFT JOIN ' . db_prefix() . 'fin_categories ON ' . db_prefix() . 'fin_categories.id = ' . db_prefix() . 'fin_plano_contas.categoria_id',
    ];

$additionalSelect = [
    'id',
    ];


// Fix for big queries. Some hosting have max_join_limit



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

array_push($where, 'AND '.db_prefix() . '_intranet_link.deleted = 0' );
array_push($where, ' AND '.db_prefix() . '_intranet_link.empresa_id = '.$empresa_id );

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
       $link = $this->ci->Link_model->get_link($aRow['id'])->row();
       $url = $link->url;
        if ($aColumns[$i] == '1') {
             $item_id =  $aRow['id']; 
            $_data = "<label>$item_id</label>";  
           // $_data = '<div class="checkbox"><input type="checkbox" value="' . $aRow['id'] . '"><label></label></div>';
        }else if ($i == '1') {
            $_data = '<a href="#" data-toggle="modal" data-target="#add_banco_modal" data-id="' . $aRow['id'] . '">' . $aRow['titulo'] . '</a>';
            $_data .= '<div class="row-options">';

            if (has_permission('items', '', 'edit')) {
                $_data .= '<a data-toggle="modal" data-target="#edit_link'.$aRow['id'].'">' . _l('edit') . '</a>';
            }
            
            if (has_permission('items', '', 'see')) {
                $_data .= ' | <a href="'.$url.'" target="_blank">Ver</a>';
            }

            if (has_permission('items', '', 'delete')) {
                $_data .= ' | <a href="' . base_url('gestao_corporativa/intra/Links/delete_link'.'?id='. $aRow['id']) . '" class="text-danger _delete">' . _l('delete') . '</a>';
            }
            
            $_data .= '</div>';
            
        } else {
        
            if (startsWith($aColumns[$i], 'ctable_') && is_date($_data)) {
                $_data = _d($_data);
            }
        }

        $row[]              = $_data;
        $row['DT_RowClass'] = 'has-row-options';
        
        $aRow['date'] = date("d-m-Y", strtotime($aRow['date']));
        
    }


    $output['aaData'][] = $row;
}
