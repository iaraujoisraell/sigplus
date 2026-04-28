<?php

defined('BASEPATH') or exit('No direct script access allowed');

$project_id = $this->ci->input->post('project_id');


$aColumns = [
    'number',
    'competencia',
    'total_faturado',
    'glosa_antes',
    'subtotal',
    'total_tax',
    'total',
    'glosa_pos',
    'valor_pago',
   // 'YEAR(tblfin_invoices.date) as year',
    'tblfin_invoices.date as date',
    'tblfin_invoices.data_pagamento as data_pagamento',
    db_prefix() .'fin_clients_financeiro.company as company',
    db_prefix() . 'projects.name as project_name',
  
    '(SELECT GROUP_CONCAT(name SEPARATOR ",") FROM ' . db_prefix() . 'taggables JOIN ' . db_prefix() . 'tags ON ' . db_prefix() . 'taggables.tag_id = ' . db_prefix() . 'tags.id WHERE rel_id = ' . db_prefix() . 'fin_invoices.id and rel_type="invoice" ORDER by tag_order ASC) as tags',
    'duedate',
    db_prefix() . 'fin_invoices.status',
    ];

$sIndexColumn = 'id';
$sTable       = db_prefix() . 'fin_invoices';

$join = [
    'LEFT JOIN ' . db_prefix() . 'fin_clients_financeiro ON ' . db_prefix() . 'fin_clients_financeiro.id = ' . db_prefix() . 'fin_invoices.clientid',
    'LEFT JOIN ' . db_prefix() . 'currencies ON ' . db_prefix() . 'currencies.id = ' . db_prefix() . 'fin_invoices.currency',
    'LEFT JOIN ' . db_prefix() . 'projects ON ' . db_prefix() . 'projects.id = ' . db_prefix() . 'fin_invoices.project_id'
];

$custom_fields = get_table_custom_fields('invoice');

foreach ($custom_fields as $key => $field) {
    $selectAs = (is_cf_date($field) ? 'date_picker_cvalue_' . $key : 'cvalue_' . $key);

    array_push($customFieldsColumns, $selectAs);
    array_push($aColumns, 'ctable_' . $key . '.value as ' . $selectAs);
    array_push($join, 'LEFT JOIN ' . db_prefix() . 'customfieldsvalues as ctable_' . $key . ' ON ' . db_prefix() . 'fin_invoices.id = ctable_' . $key . '.relid AND ctable_' . $key . '.fieldto="' . $field['fieldto'] . '" AND ctable_' . $key . '.fieldid=' . $field['id']);
}

$where  = [];
$filter = [];

// filtros

// FILTRO COMPETENCIA
if ($this->ci->input->post('competencia')) {
    $competencia = $this->ci->input->post('competencia');
    array_push($where, ' AND '. db_prefix() . 'fin_invoices.competencia =' ."'$competencia'" );
}

// CLIENTES/ CONVENIOS
if ($this->ci->input->post('clientes')) {
    $clientes = $this->ci->input->post('clientes');
    if (is_array($clientes)) {
         array_push($where, ' AND '. db_prefix() . 'fin_invoices.clientid IN (' . implode(', ', $clientes) . ')');
    }
  
}

// FILTRO SITUAÇÃO
if ($this->ci->input->post('situacao')) {
    $situacao = $this->ci->input->post('situacao');
    array_push($where, ' AND '. db_prefix() . 'fin_invoices.status = ' ."'$situacao'" );
}


// DATA VENCIMENTO
if ($this->ci->input->post('vencimento_de')) {
    $vencimento_de = $this->ci->input->post('vencimento_de');
    
    
    if ($this->ci->input->post('vencimento_ate')) {
        $vencimento_ate = $this->ci->input->post('vencimento_ate');
    }else{
        $vencimento_ate = $vencimento_de;
    }
   
 
    
    if ($vencimento_de && $vencimento_ate) {
     array_push($where, ' AND '. db_prefix() . 'fin_invoices.duedate between '. "'$vencimento_de' and '$vencimento_ate'" );
    }
   
}

// DATA ENVIO
if ($this->ci->input->post('envio_de')) {
    $envio_de = $this->ci->input->post('envio_de');
    
    
    if ($this->ci->input->post('envio_ate')) {
        $envio_ate = $this->ci->input->post('envio_ate');
    }else{
        $envio_ate = $envio_de;
    }
   
 
    
    if ($envio_de && $envio_ate) {
     array_push($where, ' AND '. db_prefix() . 'fin_invoices.date between '. "'$envio_de' and '$envio_ate'" );
    }
   
}


if (count($filter) > 0) {
    array_push($where, 'AND (' . prepare_dt_filter($filter) . ')');
}

if ($clientid != '') {
   // array_push($where, 'AND ' . db_prefix() . 'fin_invoices.clientid=' . $this->ci->db->escape_str($clientid));
}


if (!has_permission('invoices', '', 'view')) {
    $userWhere = 'AND ' . get_invoices_where_sql_for_staff(get_staff_user_id());
    array_push($where, $userWhere);
}

 array_push($where, ' AND ' . db_prefix() . 'fin_invoices.deleted = 0');
 
 // retorna somente os particular
 
$empresa_id = $this->ci->session->userdata('empresa_id');
array_push($where, ' AND '.db_prefix() . 'fin_invoices.empresa_id = '.$empresa_id );

$aColumns = hooks()->apply_filters('invoices_table_sql_columns', $aColumns);

// Fix for big queries. Some hosting have max_join_limit
if (count($custom_fields) > 5) {
    @$this->ci->db->query('SET SQL_BIG_SELECTS=1');
}

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [
    db_prefix() . 'fin_invoices.id',
    db_prefix() . 'fin_invoices.clientid',
    db_prefix(). 'currencies.name as currency_name',
    'project_id',
    'tblfin_invoices.hash',
    'tblfin_invoices.registro',
    'recurring',
    'deleted_customer_name',
    ]);
$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];

    $numberOutput = '';
    $numberOutput = '<a target="_blank" href="' . admin_url('financeiro_invoices/lista_pagamentos/' . $aRow['id']) . '" >' . 'DOC#'.$aRow['id'] . '</a>';    
    
    // If is from client area table
    if (is_numeric($clientid)) {
        $numberOutput = '<a href="' . admin_url('financeiro_invoices/list_invoices/' . $aRow['id']) . '" target="_blank">' . format_invoice_number($aRow['id']) . '</a>';
    } else {
        $numberOutput = '<a href="' . admin_url('financeiro_invoices/list_invoices/' . $aRow['id']) . '" >' . format_invoice_number($aRow['id']) . '</a>';
    }

   

    $numberOutput .= '<div class="row-options">';

    //$numberOutput .= '<a  href="' . admin_url('financeiro_invoices/lista_pagamentos/' . $aRow['id']) . '" class="label label-success"  target="_blank">Pagamentos</a>';
    if (has_permission('invoices', '', 'edit')) {
        if($aRow[db_prefix() . 'fin_invoices.status'] != 2){ 
        $numberOutput .= ' | <a href="' . admin_url('financeiro_invoices/invoice/' . $aRow['id']) . '">' . _l('edit') . '</a>';
        }
    }
    if (has_permission('invoices', '', 'delete')) {
        $numberOutput .= ' | <a href="' . admin_url('financeiro_invoices/delete/' . $aRow['id']) . '" class="text-danger _delete">' . _l('delete') . '</a>';
    }
    $numberOutput .= '</div>';

    $row[] = $numberOutput;
    
    $subtotal_antes_imposto = 0;
    if($aRow['glosa_antes']){
        $subtotal_antes_imposto = $aRow['subtotal'] - $aRow['glosa_antes'];
    }
    
    $sinconecta = '';
    $row[] = $aRow['company'];
    $row[] = $aRow['competencia'];
     $row[] = _d($aRow['date']);
    $row[] = _d($aRow['duedate']);
    $row[] = app_format_money($aRow['subtotal'], $aRow['currency_name']);
    $row[] = app_format_money($aRow['glosa_antes'], $aRow['currency_name']);
    $row[] = app_format_money($subtotal_antes_imposto, $aRow['currency_name']);
    $row[] = app_format_money($aRow['total_tax'], $aRow['currency_name']);
    $row[] = app_format_money($aRow['glosa_pos'], $aRow['currency_name']);
    $row[] = app_format_money($aRow['total'], $aRow['currency_name']);
    //$row[] = app_format_money($aRow['valor_pago'], $aRow['currency_name']);
    //$valor_aberto = $aRow['total'] - $aRow['valor_pago'];
    //$row[] = app_format_money($valor_aberto, $aRow['currency_name']);
    
    //$row[] = app_format_money($aRow['total_tax'], $aRow['currency_name']);

   // $row[] = $aRow['year'];

   
    

   

   // $row[] = _d($aRow['duedate']);

    $row[] = format_invoice_status($aRow[db_prefix() . 'fin_invoices.status']);
    
   
    // Custom fields add values
    foreach ($customFieldsColumns as $customFieldColumn) {
        $row[] = (strpos($customFieldColumn, 'date_picker_') !== false ? _d($aRow[$customFieldColumn]) : $aRow[$customFieldColumn]);
    }

    $row['DT_RowClass'] = 'has-row-options';

    $row = hooks()->apply_filters('invoices_table_row_data', $row, $aRow);

    $output['aaData'][] = $row;
}
