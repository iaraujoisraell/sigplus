<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
    db_prefix() . 'caixas.id as id',
    db_prefix() . 'caixas.name as name',
    db_prefix() . 'caixas.note as note',
    db_prefix() . 'caixas.aberto as aberto_fechado',
    db_prefix() . 'caixas.ativo as ativo',
];

$join = [
//    'LEFT JOIN ' . db_prefix() . 'clients ON ' . db_prefix() . 'clients.userid = ' . db_prefix() . 'expenses.clientid',
//    'JOIN ' . db_prefix() . 'expenses_categories ON ' . db_prefix() . 'expenses_categories.id = ' . db_prefix() . 'expenses.category',
//    'LEFT JOIN ' . db_prefix() . 'projects ON ' . db_prefix() . 'projects.id = ' . db_prefix() . 'expenses.project_id',
//    'LEFT JOIN ' . db_prefix() . 'files ON ' . db_prefix() . 'files.rel_id = ' . db_prefix() . 'expenses.id AND rel_type="expense"',
//    'LEFT JOIN ' . db_prefix() . 'currencies ON ' . db_prefix() . 'currencies.id = ' . db_prefix() . 'expenses.currency',
];


$where  = [];
$filter = [];

if (!has_permission('expenses', '', 'view')) {
//    array_push($where, 'AND ' . db_prefix() . 'expenses.addedfrom=' . get_staff_user_id());
}

$sIndexColumn = 'id';
$sTable       = db_prefix() . 'caixas';

$aColumns = hooks()->apply_filters('expenses_table_sql_columns', $aColumns);

// Fix for big queries. Some hosting have max_join_limit
if (count($custom_fields) > 4) {
    @$this->ci->db->query('SET SQL_BIG_SELECTS=1');
}
$orderby = "order by id asc";
$result = data_tables_init_order($aColumns, $sIndexColumn, $sTable, $join, $where, [], "", $orderby);
$output  = $result['output'];
$rResult = $result['rResult'];

$this->ci->load->model('payment_modes_model');

foreach ($rResult as $aRow) {
    $row = [];

    $row[] = $aRow['id'];

    $categoryOutput = '';

    if (is_numeric($clientid)) {
        $categoryOutput = '<a href="' . admin_url('expenses/list_expenses/' . $aRow['id']) . '">' . $aRow['category_name'] . '</a>';
    } else {
        $categoryOutput = '<a href="' . admin_url('expenses/list_expenses/' . $aRow['id']) . '" onclick="init_expense(' . $aRow['id'] . ');return false;">' . $aRow['category_name'] . '</a>';
    }

    if ($aRow['recurring'] == 1) {
        $categoryOutput .= '<br /><span class="label label-primary inline-block mtop4"> ' . _l('expense_recurring_indicator') . '</span>';
    }

    if ($aRow['billable'] == 1) {
        if ($aRow['invoiceid'] == null) {
            $categoryOutput .= ' <p class="text-danger">' . _l('expense_list_unbilled') . '</p>';
        } else {
            if (total_rows(db_prefix() . 'invoices', [
                'id' => $aRow['invoiceid'],
                'status' => 2,
                ]) > 0) {
                $categoryOutput .= ' <p class="text-success">' . _l('expense_list_billed') . '</p>';
            } else {
                $categoryOutput .= ' <p class="text-success">' . _l('expense_list_invoice') . '</p>';
            }
        }
    }

    $categoryOutput .= '<div class="row-options">';


    $categoryOutput .= '<a href="' . admin_url('expenses/list_expenses/' . $aRow['id']) . '" onclick="init_expense(' . $aRow['id'] . ');return false;">' . _l('view') . '</a>';

    if (has_permission('expenses', '', 'edit')) {
        $categoryOutput .= ' | <a href="' . admin_url('expenses/expense/' . $aRow['id']) . '">' . _l('edit') . '</a>';
    }

    if (has_permission('expenses', '', 'delete')) {
        $categoryOutput .= ' | <a href="' . admin_url('expenses/delete/' . $aRow['id']) . '" class="text-danger _delete">' . _l('delete') . '</a>';
    }

    $categoryOutput .= '</div>';
  
    $row[] = app_format_money($total, $aRow['currency_name']);

    $row[] = '<a href="' . admin_url('expenses/list_expenses/' . $aRow['id']) . '" onclick="init_expense(' . $aRow['id'] . ');return false;">' . $aRow['expense_name'] . '</a>';

  

    // Custom fields add values
    foreach ($customFieldsColumns as $customFieldColumn) {
        $row[] = (strpos($customFieldColumn, 'date_picker_') !== false ? _d($aRow[$customFieldColumn]) : $aRow[$customFieldColumn]);
    }

    $row['DT_RowClass'] = 'has-row-options';

    $row = hooks()->apply_filters('expenses_table_row_data', $row, $aRow);

    $output['aaData'][] = $row;
}
