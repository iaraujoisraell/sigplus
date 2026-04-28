<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [];
$where  = [];

$aColumns = array_merge($aColumns, [
    db_prefix() . '_intranet_avisos.titulo as titulo',
    db_prefix() . '_intranet_avisos.data_cadastro as date',
    db_prefix() . '_intranet_avisos.fim as fim',
    db_prefix() . '_intranet_avisos.foto as imagem',
    db_prefix() . '_intranet_avisos.user_cadastro as user',
    db_prefix() . '_intranet_avisos.tipo as tipo'
    ]);
$sIndexColumn = 'id';
$sTable       = db_prefix() . '_intranet_avisos';



$join = ['LEFT JOIN '.db_prefix().'_intranet_categorias on '.db_prefix().'_intranet_categorias.id = '.db_prefix().'_intranet_avisos.categoria_id'];

$additionalSelect = [
    db_prefix() . '_intranet_avisos.id',
    db_prefix() . '_intranet_categorias.p_title',
    ];

$empresa_id = $this->ci->session->userdata('empresa_id');


array_push($where, 'AND '.db_prefix() . '_intranet_avisos.deleted = 0' );
array_push($where, ' AND '.db_prefix() . '_intranet_avisos.empresa_id = '.$empresa_id );

$orderby = " order by ".db_prefix() . '_intranet_avisos.id desc';

$result  = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, $additionalSelect, "", "", $orderby);
$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];
    
    
    $row[] = '<a href="#" data-id="' . $aRow['id'] . '"><img height="50" src="'. base_url().'assets/intranet/img/avisos/'. $aRow['imagem'] .'" > ' . $aRow['titulo'] . '</a>';
    $row[] = ($aRow['p_title']) ? $aRow['p_title'] : 'INTRANET';


    $row[] = date("d/m/Y", strtotime($aRow['date'])).' - ' . get_staff_full_name($aRow['user']);
    
    $row[] = $aRow['fim'] = date("d/m/Y", strtotime($aRow['fim']));
    
    $row[] = $aRow['imagem'];
    if($aRow['tipo']){
        if($aRow['tipo'] == '1'){
            $tipo = 'BANNER';
        } elseif ($aRow['tipo'] == '3') {
            $tipo = 'NOTÍCIA';
        } elseif ($aRow['tipo'] == '2') {
            $tipo = 'POP-UP';
        }
        } else {
            $tipo = 'Não selcionado';
    }
    $row[] = $tipo;
    $option = '';
    $option .= '<a class="btn btn-success btn-xs " style="margin: 2px;" data-toggle="tooltip" href="' . base_url('gestao_corporativa/intra/Pubs/ver_aviso/'.'?id=' . $aRow['id']) . '"><i class="fa fa-eye"></i></a>';
    if (has_permission_intranet('publicacoes', '', 'edit')) { 
    $option .= '<a class="btn btn-info btn-xs " style="margin: 2px;" data-toggle="tooltip" href="' . base_url('gestao_corporativa/intra/Pubs/'.'?id=' . $aRow['id']) . '"><i class="fa fa-pencil"></i></a>';
    }
    if (has_permission_intranet('publicacoes', '', 'delete')) {
    $option .= '<a class="btn btn-danger btn-xs _delete" style="margin: 2px;"data-toggle="tooltip" href="' . base_url('gestao_corporativa/intra/Pubs/deletar_aviso/'.'?id='. $aRow['id']) . '"><i class="fa fa-trash"></i></a>';
    }
    $row[] = $option;

    $output['aaData'][] = $row;
}
