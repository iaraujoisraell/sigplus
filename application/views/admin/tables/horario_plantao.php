<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [];
$where  = [];


if (has_permission('items', '', 'delete')) {
   // $aColumns[] = '1';
}

$aColumns = array_merge($aColumns, [
//    db_prefix() . 'horario_plantao.id ',
    db_prefix() . 'horario_plantao.hora_inicio as hora_inicio',
    db_prefix() . 'horario_plantao.hora_fim',
    db_prefix() . 'horario_plantao.plantao',
    db_prefix() . 'horario_plantao.ativo as ativo'
]);

$sIndexColumn = 'id';
$sTable       = db_prefix() . 'horario_plantao';

$join = [
   // 'LEFT JOIN ' . db_prefix() . 'fin_categories ON ' . db_prefix() . 'fin_categories.id = ' . db_prefix() . 'fin_plano_contas.categoria_id',
    ];
$additionalSelect = [
    db_prefix() . 'horario_plantao.id as id',
   
    ];


// Fix for big queries. Some hosting have max_join_limit
if (count($custom_fields) > 4) {
    @$this->ci->db->query('SET SQL_BIG_SELECTS=1');
}


// FILTRO CONVÊNIOS
$empresa_id = $this->ci->session->userdata('empresa_id');
array_push($where, ' AND '.db_prefix() . 'horario_plantao.deleted = 0' );
array_push($where, ' AND '.db_prefix() . 'horario_plantao.empresa_id = '.$empresa_id );
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
       
        if ($i == '0') {
            $_data = '<a href="#" data-toggle="modal" data-target="#add_horario_plantao_modal" data-id="' . $aRow['id'] . '">' . $aRow['hora_inicio'] . '</a>';
            $_data .= '<div class="row-options">';

            if (has_permission('items', '', 'edit')) {
                $_data .= '<a href="#" data-toggle="modal" data-target="#add_horario_plantao_modal" data-id="' . $aRow['id'] . '">' . _l('edit') . '</a>';
            }

            if (has_permission('items', '', 'delete')) {
                $_data .= ' | <a href="' . admin_url('horario_plantao/delete/' . $aRow['id']) . '" class="text-danger _delete">' . _l('delete') . '</a>';
            }
            $_data .= '</div>';
            
        }else if ($i == '3') {
            if($aRow['ativo'] == 1){
                $_data = '<label class="label label-success">ATIVO</label>';
            }else{
                $_data = '<label class="label label-danger">INATIVO</label>';
            }
            
           
            
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
