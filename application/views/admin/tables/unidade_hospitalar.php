<?php

defined('BASEPATH') or exit('No direct script access allowed');

$hasPermissionDelete = has_permission('customers', '', 'delete');

$custom_fields = get_table_custom_fields('customers');
$this->ci->db->query("SET sql_mode = ''");

$aColumns = [
    '1',
    db_prefix().'unidades_hospitalares.razao_social as razao_social',
    'fantasia',
    'telefone',
    'situacao',
];

$sIndexColumn = 'id';
$sTable       = db_prefix().'unidades_hospitalares ';
$join = [];
$where        = [];
$additionalSelect = [
    'id',
    ];

// Add blank where all filter can be stored
$filter = [];



$aColumns = hooks()->apply_filters('customers_table_sql_columns', $aColumns);

// Fix for big queries. Some hosting have max_join_limit
if (count($custom_fields) > 4) {
    @$this->ci->db->query('SET SQL_BIG_SELECTS=1');
}
array_push($where, ' AND '.db_prefix() . 'unidades_hospitalares.deleted = 0' );
$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, $additionalSelect);

$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];

    // Bulk actions
 //   $row[] = '<div class="checkbox"><input type="checkbox" value="' . $aRow['medicoid'] . '"><label></label></div>';
    // User id
   
             $item_id =  $aRow['id']; 
            $_data = "<label>$item_id</label>";  
             $row[] = $_data;
           // $_data = '<div class="checkbox"><input type="checkbox" value="' . $aRow['id'] . '"><label></label></div>';
       
    

    // Company
    $razao_social  = $aRow['razao_social'];
    $isPerson = false;

    
    if ($razao_social == '') {
        $razao_social  = _l('no_company_view_profile');
        $isPerson = true;
    }

    $url = admin_url('unidades_hospitalares/unidades_hospitalares/' . $aRow['id']);

   
    $razao_social = '<a href="' . $url . '">' . $razao_social . '</a>';

    $razao_social .= '<div class="row-options">';
     if (has_permission('items', '', 'edit')) {
                $razao_social .= '  <a href="' . admin_url('unidades_hospitalares/unidades_hospitalares/' . $aRow['id']) . '" class="text-success ">' . _l('edit') . '</a>';
              
            }

            if (has_permission('items', '', 'delete')) {
                $razao_social .= ' | <a href="' . admin_url('unidades_hospitalares/delete/' . $aRow['id']) . '" class="text-danger _delete">' . _l('delete') . '</a>';
            }
    

   

    $razao_social .= '</div>';
   
    $row[] = $razao_social;

    // cns
    $row[] = $aRow['fantasia'];

    // crm
    $row[] = $aRow['telefone'];
    
    // cpf
    $row[] = $aRow['situacao'];
    
   
    /* Repasse
    if($aRow['repasse'] == 1){
        $row[] = '<p class="label label-success"> SIM </p>';
    }else{
        $row[] = '<p class="label label-danger"> NÃO </p>';
    }*/
    
    
   
    
    
    //$row[] = _dt($aRow['datecreated']);

   
    $row['DT_RowClass'] = 'has-row-options';

    

    $row = hooks()->apply_filters('customers_table_row_data', $row, $aRow);

    $output['aaData'][] = $row;
}
