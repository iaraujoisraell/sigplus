<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [];
$where  = [];


//if (has_permission('items', '', 'delete')) {
    $aColumns[] = '1';
//}

$aColumns = array_merge($aColumns, [
    db_prefix() . '_intranet_documento.codigo as codigo',
    db_prefix() . '_intranet_documento_categoria.titulo as categoria',
    db_prefix() . '_intranet_documento.numero_versao as versao',
    db_prefix() . '_intranet_documento.id as situacao',
    db_prefix() . '_intranet_documento.data_cadastro as recebido_em'
    ]);
$sIndexColumn = 'id';
$sTable       = db_prefix() . '_intranet_documento';



$join = [
    'Left JOIN ' . db_prefix() . '_intranet_documento_categoria ON ' . db_prefix() . '_intranet_documento_categoria.id = ' . db_prefix() . '_intranet_documento.categoria_id',
    
    ];

$additionalSelect = [
    'tbl_intranet_documento.id',
    'tbl_intranet_documento.sequencial',
    ];


// Fix for big queries. Some hosting have max_join_limit



// FILTRO CONVÊNIOS
$empresa_id = $this->ci->session->userdata('empresa_id');

$id_usuario = get_staff_user_id();
array_push($where, 'AND '.db_prefix() . '_intranet_documento.deleted = 0' );
array_push($where, 'AND '.db_prefix() . '_intranet_documento.publicado = 0');
array_push($where, 'AND (tbl_intranet_documento.id in (select tbl_intranet_documento_aprovacao.doc_id from tbl_intranet_documento_aprovacao where tbl_intranet_documento_aprovacao.doc_id = tbl_intranet_documento.id and tbl_intranet_documento_aprovacao.staff_id = '.$id_usuario.') or tbl_intranet_documento_categoria.responsavel = '.$id_usuario.')');
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
             $item_id =  $aRow['sequencial']; 
            $_data = "<label>$item_id</label>";  
           // $_data = '<div class="checkbox"><input type="checkbox" value="' . $aRow['id'] . '"><label></label></div>';
        }else if ($i == '1') {
            $_data = '<a href="' . base_url('gestao_corporativa/intra/Documentos/see'.'?id='. $aRow['id']) . '">' . $aRow['codigo'] . '</a>';
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
        
        $this->ci->load->model('Documento_model');
        $this->ci->load->model('Intranet_model');
        
        $fluxo_atual = $this->ci->Documento_model->get_fluxo_atual($aRow['id']);
        if($fluxo_atual->staff_id == $id_usuario){
            $color = 'red';
        } else {
            $color = '#007bff';
        }
        //print_r($fluxo_atual); exit;
        $fluxo_anterior = $fluxo_atual->fluxo_sequencia - 1;
        $fluxo_anterior = $this->ci->Documento_model->get_fluxo_by_sequencia($fluxo_anterior, $aRow['id']);
        //print_r($fluxo_anterior); exit;
        if($fluxo_anterior->dt_aprovacao != '' && $fluxo_anterior->dt_aprovacao != '0000-00-00 00:00:00'){
            $aRow['recebido_em'] = date('d/m/Y H:i:s', strtotime ($fluxo_anterior->dt_aprovacao));
        } else {
            $aRow['recebido_em'] = date('d/m/Y', strtotime ($fluxo_atual->data_cadastro));
        }
        
        $aRow['situacao'] = '<span class="badge " style="
                        color: '.$color.';
                        background-color: transparent;
                        border-color: '.$color.';
                        border: 1px solid; text-transform: uppercase;" ><H7>'.$fluxo_atual->fluxo_nome.'('.$fluxo_atual->firstname.')</H7></span>';
        
    }


    $output['aaData'][] = $row;
}
