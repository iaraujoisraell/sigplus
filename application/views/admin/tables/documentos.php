<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [];
$where  = [];


if (has_permission('items', '', 'delete')) {
    $aColumns[] = '1';
}

$aColumns = array_merge($aColumns, [
    db_prefix() . '_intranet_documento.titulo as titulo',
    db_prefix() . '_intranet_documento.data_cadastro as date',
    db_prefix() . '_intranet_documento.file as file',
    db_prefix() . '_intranet_documento.version as versao'
    ]);
$sIndexColumn = 'id';
$sTable       = db_prefix() . '_intranet_documento';



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

array_push($where, 'AND '.db_prefix() . '_intranet_documento.deleted = 0' );
array_push($where, 'AND '.db_prefix() . '_intranet_documento.publicado = 1' );
array_push($where, ' AND '.db_prefix() . '_intranet_documento.empresa_id = '.$empresa_id );

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
            $_data = '<a href="#" data-toggle="modal" data-target="#add_banco_modal" data-id="' . $aRow['id'] . '">' . $aRow['titulo'] . '</a>';
            $_data .= '<div class="row-options">';

           
            if (has_permission('items', '', 'see')) {
                $_data .= ' | <a href="' . base_url('gestao_corporativa/intra/Documentos/see'.'?id='. $aRow['id']) . '" class="">Ver</a>';
            }

            if (has_permission('items', '', 'delete')) {
                $_data .= ' | <a href="' . base_url('gestao_corporativa/intra/Documentos/see'.'?id='. $aRow['id']) . '" class="text-danger _delete">' . _l('delete') . '</a>';
            }
            
            $_data .= '</div>';
            
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
        //$enviados = $this->ci->Documento_model->get_documento_send($aRow['id'])->result();
        //$enviados = $this->CI->Documento_model->get_documento_send($aRow['id'])->result();
        //if($enviados){
            //$aRow['para'] = count($enviados). ' Pessoas';
        //$lido = 0;
        //foreach ($enviados as $envios) {
            //if ($envios->lido == 1) {
              // $lido = $lido + 1;
          //  }
        //}
        //$q = 100 / count($enviados);
        //$media = $lido * $q;
        //$porcentagem = round($media, -1);
        //$aRow['lido'] = $lido.' de '.count($enviados);
            
        //} else {
        //$aRow['lido'] = '';
        //$aRow['para'] = '0 Pessoas';
    //}
        
        
    }


    $output['aaData'][] = $row;
}
