<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [];
$where  = [];


//if (has_permission('items', '', 'delete')) {
    $aColumns[] = '1';
//}

$aColumns = array_merge($aColumns, [
    db_prefix() . 'convenio.name as convenio_name',
     db_prefix() . 'items.id',
    'description',
    
 //   db_prefix() . 'items_groups.id',
    'codigo_tuss',
    db_prefix() . 'items_groups.name',
    
    
    
    db_prefix() . 'items.rate as rate',
    db_prefix() . 'items.valor2 as valor2',
    //db_prefix() . 'items.convenio_id as convenio_id',
    'unit',
    
    db_prefix() .'items.ativo',
    ]);

$sIndexColumn = 'id';
$sTable       = db_prefix() . 'items';

$join = [
    'LEFT JOIN ' . db_prefix() . 'taxes t1 ON t1.id = ' . db_prefix() . 'items.tax',
    'LEFT JOIN ' . db_prefix() . 'taxes t2 ON t2.id = ' . db_prefix() . 'items.tax2',
    'LEFT JOIN ' . db_prefix() . 'items_groups ON ' . db_prefix() . 'items_groups.id = ' . db_prefix() . 'items.group_id',
    'LEFT JOIN ' . db_prefix() . 'convenio ON ' . db_prefix() . 'convenio.id = ' . db_prefix() . 'items.convenio_id',
    ];
$additionalSelect = [
    db_prefix() . 'convenio.id as convenio_id',
    db_prefix() . 'items.id',
    'long_description',
    't1.name as taxname_1',
    't2.name as taxname_2',
    db_prefix() .'items.monocular',
    db_prefix() .'items.valor_uco',
    'group_id',
    db_prefix() .'items.ativo',
    ];

$custom_fields = get_custom_fields('items');

foreach ($custom_fields as $key => $field) {
    $selectAs = (is_cf_date($field) ? 'date_picker_cvalue_' . $key : 'cvalue_' . $key);

    array_push($customFieldsColumns, $selectAs);
    array_push($aColumns, 'ctable_' . $key . '.value as ' . $selectAs);
    array_push($join, 'LEFT JOIN ' . db_prefix() . 'customfieldsvalues as ctable_' . $key . ' ON ' . db_prefix() . 'items.id = ctable_' . $key . '.relid AND ctable_' . $key . '.fieldto="items_pr" AND ctable_' . $key . '.fieldid=' . $field['id']);
}

// Fix for big queries. Some hosting have max_join_limit
if (count($custom_fields) > 4) {
    @$this->ci->db->query('SET SQL_BIG_SELECTS=1');
}


// FILTRO CONVÊNIOS
$this->ci->load->model('Convenios_model');

//$convenios = $this->ci->input->post('convenios_procedimentos');
//

if ($this->ci->input->post('convenios_procedimentos')) {
    $convenios = $this->ci->input->post('convenios_procedimentos');
   
    $_convenios = "";
    if ($convenios) {
        array_push($where, ' AND '. db_prefix() . 'items.convenio_id IN (' . implode(', ', $convenios) . ')');
    }
    
    
   
}

// FILTRO CATEGORIAS
if ($this->ci->input->post('categorias_procedimentos')) {
    $categorias = $this->ci->input->post('categorias_procedimentos');
    if (is_array($categorias)) {
         array_push($where, ' AND '. db_prefix() . 'items.group_id IN (' . implode(', ', $categorias) . ')');
    }
  
}

//FILTRO PROCEDIMENTOS

if ($this->ci->input->post('procedimento_items')) {
    $procedimentos = $this->ci->input->post('procedimento_items');
    // print_r($procedimentos); exit;
    $_procedimentos = [];
    if (is_array($procedimentos)) {
      array_push($where, ' AND '. db_prefix() . 'items.id IN (' . implode(', ', $procedimentos) . ')');
    }
   
}

$empresa_id = $this->ci->session->userdata('empresa_id');
array_push($where, ' AND '.db_prefix() . 'items.empresa_id = '.$empresa_id );

 array_push($where, ' AND '.db_prefix() . 'items.ativo = 1' );
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
        } elseif ($aColumns[$i] == 'description') {
            $_data = '<a href="#" data-toggle="modal" data-target="#sales_item_modal" data-id="' . $aRow['id'] . '">' . $_data . '</a>'.'<br>'.$aRow['long_description'];
            $_data .= '<div class="row-options">';

            if (has_permission('items', '', 'edit')) {
                $_data .= '<a href="#" data-toggle="modal" data-target="#sales_item_modal" data-id="' . $aRow['id'] . '">' . _l('edit') . '</a>';
            }

            if (has_permission('items', '', 'delete')) {
                $_data .= ' | <a href="' . admin_url('invoice_items/delete/' . $aRow['id']) . '" class="text-danger _delete">' . _l('delete') . '</a>';
            }
            $_data .= '</div>';
        } else if ($aColumns[$i] == db_prefix().'items.ativo') {
            // Toggle active/inactive customer
            
            $_data = '<div class="onoffswitch" data-toggle="tooltip" data-title="' . _l('customer_active_inactive_help') . '">
            <input type="checkbox"' . ($aRow['deleted'] == 1 ? ' disabled' : '') . ' data-switch-url="' . admin_url() . 'invoice_items/change_item_status" name="onoffswitch" class="onoffswitch-checkbox" id="' . $aRow['id'] . '" data-id="' . $aRow['id'] . '" ' . ($aRow[db_prefix().'items.ativo'] == 1 ? 'checked' : '') . '>
            <label class="onoffswitch-label" for="' . $aRow['id'] . '"></label>
            </div>';

            // For exporting
            $_data .= '<span class="hide">' . ($aRow[db_prefix().'items.ativo'] == 1 ? _l('is_active_export') : _l('is_not_active_export')) . '</span>';
             

            //$row[] = $toggleActive;
           // $_data = '<div class="checkbox"><input type="checkbox" value="' . $aRow['id'] . '"><label></label></div>';
        }else if ($i == '6') {  
            $rate = $aRow['rate'];
            $rate_v = app_format_money($rate, ' R$ ');
            $_data = "$rate";
            
        }else if ($i == '7') {  
            
            //$rate = $aRow['valor2'];
            $rate_v = app_format_money($aRow['valor2'], ' R$ ');
            $_data = $rate_v;
            /*
            $convenio_id = $aRow['convenio_id'];
            $convenio    = $this->ci->Convenios_model->get($convenio_id);
            $particular = $convenio->particular;
            $deflator_porcentagem = $convenio->deflator_porcentagem;
            $deflator_uco = $convenio->deflator_uco;
            $deflator_honorario = $convenio->deflator_honorario;
            $deflator_porcentagem = $deflator_porcentagem/100;
            
            // calcular 2a via se tiver
            if($particular == 0){
               $rate = $aRow['rate'];
               $monocular = $aRow['monocular'];
               $valor_uco_item = $aRow['valor_uco'];
               
               if($monocular == 1){
                   
                   if($deflator_uco == 1){
                       $vl_deflator =  ($deflator_porcentagem * $valor_uco_item);
                       $vl_2v_total = $rate - $vl_deflator;
                   }else{
                       $vl_deflator = $rate;
                       $vl_2v_total =  $vl_deflator;
                   }
                   
                  
                  
                  
                 
                 $vl_2v_total = substr("$vl_2v_total", 0, 5);
                 $vl_2v_total2 = app_format_money($vl_2v_total, ' R$ ');
                 $_data = "$vl_2v_total"; 
                   
               }else{
                    $_data = "";
               }
               
                
            }else{
                $_data = ""; 
            }
            
            */
            
            
           
            
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
