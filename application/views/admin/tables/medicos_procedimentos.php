<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [];
$where  = [];


//if (has_permission('items', '', 'delete')) {
    $aColumns[] = '1';
//}

$aColumns = array_merge($aColumns, [
    db_prefix() . 'convenio.name as convenio',
    db_prefix() . 'items_groups.name as categoria',
    db_prefix() .'items.description as description',
    db_prefix() . 'items.rate as rate',
    db_prefix() . 'medicos_procedimentos.valor as valor',
    db_prefix() . 'medicos_procedimentos.tipo as tipo',
    
    db_prefix() . 'items.ativo as ativo'
    
    
    ]);

$sIndexColumn = 'id';
$sTable       = db_prefix() . 'medicos_procedimentos';

$join = [
    'INNER JOIN ' . db_prefix() . 'items ON ' . db_prefix() . 'items.id = ' . db_prefix() . 'medicos_procedimentos.item_id',
    'INNER JOIN ' . db_prefix() . 'items_groups ON ' . db_prefix() . 'items_groups.id = ' . db_prefix() . 'items.group_id',
    'INNER JOIN ' . db_prefix() . 'convenio ON ' . db_prefix() . 'convenio.id = ' . db_prefix() . 'items.convenio_id',
    ];

$additionalSelect = [
    db_prefix() . 'items.id as items_id',
    db_prefix() . 'medicos_procedimentos.medicoid ',
    db_prefix() . 'medicos_procedimentos.id as mp_id',
   
    ];


// Fix for big queries. Some hosting have max_join_limit
if (count($custom_fields) > 4) {
    @$this->ci->db->query('SET SQL_BIG_SELECTS=1');
}


// FILTRO CONVÊNIOS
$empresa_id = $this->ci->session->userdata('empresa_id');

if ($this->ci->input->post('convenios_procedimentos')) {
    $convenios = $this->ci->input->post('convenios_procedimentos');
   
    $_convenios = "";
    if ($convenios) {
        array_push($where, ' AND '. db_prefix() . 'items.convenio_id IN (' . implode(', ', $convenios) . ')');
    }
    
    
   
}

// FILTRO CATEGORIAS
if ($this->ci->input->post('categorias')) {
    $categorias = $this->ci->input->post('categorias');
    if (is_array($categorias)) {
         array_push($where, ' AND '. db_prefix() . 'items.group_id IN (' . implode(', ', $categorias) . ')');
    }
  
}

//FILTRO PROCEDIMENTOS

if ($this->ci->input->post('natureza')) {
    $natureza = $this->ci->input->post('natureza');
    if ($natureza) {
      //array_push($where, ' AND '. db_prefix() . 'fin_plano_contas.natureza = '.$natureza);
    }
   
}
$medico_id = $this->ci->input->post('medicoid');

//$this->db->where(db_prefix().'items.ativo', 1);

array_push($where, ' AND '.db_prefix() . 'items.ativo = 1' );
array_push($where, ' AND '.db_prefix() . 'medicos_procedimentos.deleted = 0' );
array_push($where, ' AND '.db_prefix() . 'medicos_procedimentos.medicoid = '.$medico_id );
//$where   = [];
$result  = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, $additionalSelect);
$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];
    
    $row[] = $aRow['mp_id'];
    $row[] = $aRow['convenio'];
    $row[] = $aRow['categoria'];
    
    
            //$_data = '<a href="#" data-toggle="modal" data-target="#plano_conta_modal" data-id="' . $aRow['id'] . '">' . $aRow['description'] . '</a>';
             $_data = '<a href="#" >' . $aRow['description'] . '</a>';
            $_data .= '<div class="row-options">';

            if (has_permission('items', '', 'edit')) {
             //   $_data .= '<a href="#" data-toggle="modal" data-target="#plano_conta_modal" data-id="' . $aRow['id'] . '">' . _l('edit') . '</a>';
            }
            
            //if (has_permission('items', '', 'delete')) {
                $_data .= ' | <a href="' . admin_url('misc/delete_repasse/' . $aRow['mp_id']) . '" class="text-danger _delete">' . _l('delete') . '</a>';
            //}
            $_data .= '</div>';
     $row[] = $_data;  
     
    $row[] = app_format_money($aRow['rate'],' R$ ');
    $row[] = $aRow['valor'];
    
    
            if($aRow['tipo'] == 1){
                    $tipo = 'R$';
                    $valor_repasse = $aRow['valor'];
                }else if($aRow['tipo'] == 2){
                    $tipo = '%';
                    $valor_repasse = ($aRow['valor'] / 100) * $aRow['rate'];
                }
                
                
    $row[] = $tipo;
    $row[] = app_format_money($valor_repasse,' R$ ');
    
    
            if($aRow['ativo'] == 1){
                $ativo = '<label class="label label-success">Ativo</label>';
            }else{
                $ativo = '<label class="label label-warning">Inativo</label>';
            }
           
    $row[] = $ativo;          
        

        $row['DT_RowClass'] = 'has-row-options';

    $row = hooks()->apply_filters('expenses_table_row_data', $row, $aRow);

    $output['aaData'][] = $row;
}
