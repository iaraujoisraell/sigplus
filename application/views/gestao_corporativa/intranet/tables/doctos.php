<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [];
$where = [];

$aColumns = array_merge($aColumns, [
    db_prefix() . '_intranet_categorias_doctos.titulo as titulo',
    db_prefix() . '_intranet_categorias_doctos.date_created as dt_created',
    db_prefix() . '_intranet_categorias_doctos.user_created as user_created',
    db_prefix() . '_intranet_categorias_doctos.file as file',
    db_prefix() . '_intranet_categorias_doctos.caminho as caminho',
    db_prefix() . '_intranet_categorias_doctos.portal as portal',
    db_prefix() . '_intranet_categorias_doctos.intranet as intranet',
    db_prefix() . '_intranet_categorias_doctos.active as active'
    
        ]);
$sIndexColumn = 'id';
$sTable = db_prefix() . '_intranet_categorias_doctos';

$join = [];

$additionalSelect = ['id'];

// FILTRO CONVÊNIOS
$empresa_id = $this->ci->session->userdata('empresa_id');

array_push($where, 'AND ' . db_prefix() . '_intranet_categorias_doctos.deleted = 0');
array_push($where, ' AND ' . db_prefix() . '_intranet_categorias_doctos.empresa_id = ' . $empresa_id);
array_push($where, ' AND ' . db_prefix() . '_intranet_categorias_doctos.categoria_id = ' . $this->ci->input->post('categoria_id'));

//$where   = [];
$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, $additionalSelect);

$output = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];

    $row[] = $aRow['titulo'];
    
    $row[] = '<a href="'.base_url('assets/intranet/arquivos/categorias_arquivos/').$aRow['file'].'" target="_blank">'.$aRow['file'].'</a>';
    $row[] = date("d/m/Y H:i:s", strtotime($aRow['dt_created'])) . ' - ' . get_staff_full_name($aRow['user_created']);
    if($this->ci->input->post('rel_type') == 'workflow'){
    $portal = $aRow['portal'];
    if($portal == 1){
        $row[] = 'Disponível';
    } else {
        $row[] = 'Indisponível';
    }
    
    $intranet = $aRow['intranet'];
    if($intranet == 1){
        $row[] = 'Disponível';
    } else {
        $row[] = 'Indisponível';
    }
    

    
    
    $row[] = '<button class="btn btn-danger btn-xs " style="margin: 2px;" data-toggle="tooltip" onclick="delete_docto(' . "'" . $aRow['id'] . "'" . ')"><i class="fa fa-trash"></i></button>';
    }
    $checked = '';
    if ($aRow['active'] == 1) {
        $checked = 'checked';
    }
     $row[] = '<div class="onoffswitch">'
            . '<input type="checkbox" ' . $checked . ' class="onoffswitch-checkbox"  id="active_docto' . $aRow['id'] . '" onchange="update_active_docto(' . "'" . $aRow['id'] . "'" . ')">
                <label class="onoffswitch-label" for="active_docto' . $aRow['id'] . '"></label>
                </div>';
     

    $output['aaData'][] = $row;
}
