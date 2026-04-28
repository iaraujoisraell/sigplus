<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [];
$where  = [];


if (has_permission('items', '', 'delete')) {
    $aColumns[] = '1';
}

$aColumns = array_merge($aColumns, [
    db_prefix() . '_intranet_avisos.titulo as titulo',
    db_prefix() . '_intranet_avisos.data_cadastro as date',
    db_prefix() . '_intranet_avisos.fim as fim',
    db_prefix() . '_intranet_avisos.foto as imagem',
    db_prefix() . '_intranet_avisos.tipo as tipo'
    ]);
$sIndexColumn = 'id';
$sTable       = db_prefix() . '_intranet_avisos';



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

array_push($where, 'AND '.db_prefix() . '_intranet_avisos.deleted = 0' );
array_push($where, ' AND '.db_prefix() . '_intranet_avisos.empresa_id = '.$empresa_id );

$orderby = " order by id desc";

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
        }else if ($i == '1') {
            $_data = '<a href="#" data-id="' . $aRow['id'] . '"><img height="50" src="'. base_url().'assets/intranet/img/avisos/'. $aRow['imagem'] .'" > ' . $aRow['titulo'] . '</a>';
            $_data .= '<div class="row-options">';

            if (has_permission('items', '', 'edit')) {
                $_data .= '<a href="' . base_url('gestao_corporativa/intra/Pubs/'.'?id=' . $aRow['id']) . '">' . _l('edit') . '</a>';
            }
            
            if (has_permission('items', '', 'see')) {
                $_data .= ' | <a href="' . base_url('gestao_corporativa/intra/Pubs/ver_aviso/'.'?id=' . $aRow['id']) . '" target="_blank">Ver</a>';
            }

            if (has_permission('items', '', 'delete')) {
                $_data .= ' | <a href="' . base_url('gestao_corporativa/intra/Pubs/deletar_aviso/'.'?id='. $aRow['id']) . '" class="text-danger _delete">' . _l('delete') . '</a>';
            }
            
            $_data .= '</div>';
            
        } else {
        
            if (startsWith($aColumns[$i], 'ctable_') && is_date($_data)) {
                $_data = _d($_data);
            }
        }

        $row[]              = $_data;
        
        $row['DT_RowClass'] = 'has-row-options';
        if($aRow['tipo'] == 1){
            $aRow['tipo'] = 'BANNER';
        } elseif ($aRow['tipo'] == '3') {
            $aRow['tipo'] = 'NOTÍCIA';
        } elseif ($aRow['tipo'] == '2') {
            $aRow['tipo'] = 'POP-UP';
        }
        
        $aRow['date'] = date("d-m-Y", strtotime($aRow['date']));
        $aRow['fim'] = date("d-m-Y", strtotime($aRow['fim']));
    }


    $output['aaData'][] = $row;
}
