<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [];
$where  = [];


//if (has_permission('items', '', 'delete')) {
    $aColumns[] = '1';
//}



$aColumns = array_merge($aColumns, [
    db_prefix() . '_intranet_categorias_fluxo.objetivo as etapa',
    db_prefix() . 'departments.name as setor',
    'count(*) as quantidade',
    db_prefix() . '_intranet_workflow_fluxo_andamento.data_prazo as data_prazo',
    db_prefix() . '_intranet_categorias.titulo as categoria',
    db_prefix() . '_intranet_workflow_fluxo_andamento.concluido as concluido',
    'concat(tblstaff.firstname, \'  \', tblstaff.lastname) as nome'

    
    ]);

$sIndexColumn = 'id';
$sTable       = db_prefix() . '_intranet_workflow_fluxo_andamento';

$join = ['LEFT JOIN ' . db_prefix() . '_intranet_categorias ON ' . db_prefix() . '_intranet_categorias.id = ' . db_prefix() . '_intranet_workflow_fluxo_andamento.categoria_id',
        'LEFT JOIN ' . db_prefix() . 'departments ON ' . db_prefix() . 'departments.departmentid = ' . db_prefix() . '_intranet_workflow_fluxo_andamento.department_id',
        'LEFT JOIN ' . db_prefix() . '_intranet_categorias_fluxo ON ' . db_prefix() . '_intranet_categorias_fluxo.id = ' . db_prefix() . '_intranet_workflow_fluxo_andamento.fluxo_id',
        'LEFT JOIN tblstaff on tblstaff.staffid = tbl_intranet_workflow_fluxo_andamento.atribuido_a '];
$additionalSelect = [
   db_prefix() . '_intranet_workflow_fluxo_andamento.id as id',
   db_prefix() . '_intranet_workflow_fluxo_andamento.department_id as department_id',
   db_prefix() . '_intranet_workflow_fluxo_andamento.categoria_id as categoria_id',
    ];



    $prazo_de = $this->ci->input->post('prazo_de');
    $prazo_ate = $this->ci->input->post('prazo_ate');

  

    if($prazo_de && $prazo_ate){
        $where[] = "AND tbl_intranet_workflow_fluxo_andamento.data_prazo between '$prazo_de' and '$prazo_ate' ";
    }


// FILTRO CONVÊNIOS
//$this->ci->load->model('Convenios_model');

    $status = $this->ci->input->post('status');

    $_status = "";
    if ($status == 1) {
       array_push($where, ' AND '. db_prefix() . '_intranet_workflow_fluxo_andamento.concluido = 1');
    }else if ($status == 2) {
       array_push($where, ' AND '. db_prefix() . '_intranet_workflow_fluxo_andamento.concluido = 0');
    }
    
 

// FILTRO CONVÊNIO
if ($this->ci->input->post('departments')) {
    $departments = $this->ci->input->post('departments');
    if ($departments) {
        array_push($where, ' AND '. db_prefix() . '_intranet_workflow_fluxo_andamento.department_id = '.$departments);
      // array_push($where, ' AND '. db_prefix() . '_intranet_workflow_fluxo_andamento.department_id IN (' . implode(', ', $departments) . ')');
    }
  
}



$categoria = $this->ci->input->post('categoria');

// FILTRO COMPETENCIA
if ($this->ci->input->post('categoria')) {
    $categoria = $this->ci->input->post('categoria');
    if ($categoria) {
        array_push($where, ' AND '. db_prefix() . '_intranet_categorias.id = '.$categoria);
        //array_push($where, ' AND '. db_prefix() . 'tabmm_items.tab_id IN (' . implode(', ', $convenio) . ')');
    }
  
}  

$groupby = " group by tbl_intranet_workflow_fluxo_andamento.department_id, tbl_intranet_workflow_fluxo_andamento.categoria_id, tbl_intranet_workflow_fluxo_andamento.data_prazo ";

$result  = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, $additionalSelect, $groupby);
$output  = $result['output'];
$rResult = $result['rResult'];

$soma_taxas = 0;
$soma_mat_med = 0;
$soma_honorarios = 0;
$soma_valor_total = 0;
$soma_qtde_guias = 0;

 
foreach ($rResult as $aRow) {
    $total_lote = 0;
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
        } 
       
       

        elseif ($i == '4') {
            $data_prazo =  $aRow['data_prazo']; 
            $_data = _d($data_prazo);
        }
        
        elseif ($i == '6') {
            $concluido =  $aRow['concluido']; 
            if($concluido == 1){
                $desc_status = "<label class='label label-success'> CONCLUÍDO </label>";
            }else{
                $desc_status = '<label class="label label-warning"> PENDENTE </label>';
            }

            $_data = $desc_status;
        }
        

        $row[]              = $_data;
        $row['DT_RowClass'] = 'has-row-options';
    }


    $output['aaData'][] = $row;
}

