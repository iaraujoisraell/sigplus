<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [];
$where  = [];


if (has_permission('items', '', 'delete')) {
    $aColumns[] = '1';
}

$aColumns = array_merge($aColumns, [
    db_prefix() . '_intranet_menus.nome_menu as titulo',
    db_prefix() . '_intranet_menus.urk as url',
    db_prefix() . '_intranet_menus.icon as icon',
    db_prefix() . '_intranet_menus.ordem as ordem',
    db_prefix() . '_intranet_menus.id as a'
    
    ]);
$sIndexColumn = 'id';

$sTable       = db_prefix() . '_intranet_menus';


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



array_push($where, 'AND '.db_prefix() . '_intranet_menus.deleted = 0' );
array_push($where, 'AND '.db_prefix() . '_intranet_menus.menu_pai = 0' );

array_push($where, ' AND '.db_prefix() . '_intranet_menus.empresa_id = '.$empresa_id );



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
            $_data = '<a href"' . $aRow['url'] .'" >' . $aRow['titulo'] . '</a>';
            
            
        } else {
        
            if (startsWith($aColumns[$i], 'ctable_') && is_date($_data)) {
                $_data = _d($_data);
            }
        }

        $row[]              = $_data;
        $row['DT_RowClass'] = 'has-row-options';
       
        setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
        date_default_timezone_set('America/Sao_Paulo');
        //$aRow['date'] = date("d-m-Y", strtotime($aRow['date']));
        $aRow['date'] = strftime('%A, %d de %B de %Y', strtotime($aRow['date']));
        $variavel = "slideToggle('.edit".$aRow['id']."'); return false";
        $onclick = 'onclick="'.$variavel.'"';
        $aRow['a'] = '<a class="btn btn-default btn-icon" '.$onclick.' ><i class="fa fa-pencil-square-o"></i></a>'
                . '<a class="btn btn-danger btn-icon _delete" href="'. base_url('gestao_corporativa/intra/Menus/delete_menu'.'?id='. $aRow['id']).'" ><i class="fa fa-remove"></i></a>'
                . '<a class="btn btn-default btn-icon" data-toggle="modal" data-target="#add_menu'.$aRow['id'].'" ><i class="fa fa-list"></i></a>';
               
    }


    $output['aaData'][] = $row;
}
