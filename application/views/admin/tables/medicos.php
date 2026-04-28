<?php

defined('BASEPATH') or exit('No direct script access allowed');

$hasPermissionDelete = has_permission('customers', '', 'delete');

$custom_fields = get_table_custom_fields('customers');
$this->ci->db->query("SET sql_mode = ''");



$aColumns = [
    '1',
    db_prefix().'medicos.medicoid as medicoid',
    'nome_profissional',
    'cns',
    'codigo_registro',
    db_prefix().'medicos.cpf as cpf',
    db_prefix().'medicos.celular as phonenumber',
    db_prefix().'medicos.email as email',
    db_prefix().'medicos.repasse as repasse',
    db_prefix().'medicos.active',
    db_prefix().'medicos.datecreated as datecreated',
];

$sIndexColumn = 'medicoid';
$sTable       = db_prefix().'medicos';
$where        = [];

// Add blank where all filter can be stored
$filter = [];

$empresa_id = $this->ci->session->userdata('empresa_id');
array_push($where, ' AND '.db_prefix() . 'medicos.empresa_id = '.$empresa_id );

$aColumns = hooks()->apply_filters('customers_table_sql_columns', $aColumns);

// Fix for big queries. Some hosting have max_join_limit
if (count($custom_fields) > 4) {
    @$this->ci->db->query('SET SQL_BIG_SELECTS=1');
}

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where);

$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];

    // Bulk actions
 //   $row[] = '<div class="checkbox"><input type="checkbox" value="' . $aRow['medicoid'] . '"><label></label></div>';
    // User id
    $row[] = $aRow['medicoid'];

    // Company
    $company  = $aRow['nome_profissional'];
    $isPerson = false;

    
    if ($company == '') {
        $company  = _l('no_company_view_profile');
        $isPerson = true;
    }

    $url = admin_url('medicos/medico/' . $aRow['medicoid']);

   
    $company = '<a href="' . $url . '">' . $company . '</a>';

    $company .= '<div class="row-options">';
    $company .= '<a href="' . admin_url('medicos/medico/' . $aRow['medicoid'] . ($isPerson && $aRow['contact_id'] ? '?group=profile_medico' : '')) . '">' . _l('view') . '</a>';

    if (!$isPerson) {
        $company .= ' | <a href="' . admin_url('medicos/medico/' . $aRow['medicoid'] . '?group=contacts') . '">' . _l('customer_contacts') . '</a>';
    }
    if ($hasPermissionDelete) {
     //   $company .= ' | <a href="' . admin_url('medicos/delete/' . $aRow['medicoid']) . '" class="text-danger _delete">' . _l('delete') . '</a>';
    }

    $company .= '</div>';
   
    $row[] = $company;

    // cns
    $row[] = $aRow['cns'];

    // crm
    $row[] = $aRow['codigo_registro'];
    
    // cpf
    $row[] = $aRow['cpf'];
    
    // contact phone
    $row[] = ($aRow['phonenumber'] ? '<a href="tel:' . $aRow['phonenumber'] . '">' . $aRow['phonenumber'] . '</a>' : '');
    
    // Primary contact email
    $row[] = ($aRow['email'] ? '<a href="mailto:' . $aRow['email'] . '">' . $aRow['email'] . '</a>' : '');

    // Repasse
    if($aRow['repasse'] == 1){
        $row[] = '<p class="label label-success"> SIM </p>';
    }else{
        $row[] = '<p class="label label-danger"> NÃO </p>';
    }
    
    
    // Toggle active/inactive customer
    $toggleActive = '<div class="onoffswitch" data-toggle="tooltip" data-title="' . _l('customer_active_inactive_help') . '">
    <input type="checkbox" data-switch-url="' . admin_url() . 'medicos/change_client_status" name="onoffswitch" class="onoffswitch-checkbox" id="' . $aRow['medicoid'] . '" data-id="' . $aRow['medicoid'] . '" ' . ($aRow[db_prefix().'medicos.active'] == 1 ? 'checked' : '') . '>
    <label class="onoffswitch-label" for="' . $aRow['medicoid'] . '"></label>
    </div>';

    // For exporting
    $toggleActive .= '<span class="hide">' . ($aRow[db_prefix().'medicos.active'] == 1 ? _l('is_active_export') : _l('is_not_active_export')) . '</span>';

    $row[] = $toggleActive;
    
    
    
    //$row[] = _dt($aRow['datecreated']);

   
    $row['DT_RowClass'] = 'has-row-options';

    

    $row = hooks()->apply_filters('customers_table_row_data', $row, $aRow);

    $output['aaData'][] = $row;
}
