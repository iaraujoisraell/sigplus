<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [];
$where  = [];



$aColumns = array_merge($aColumns, [
    db_prefix() . 'fin_movimentacao_bancaria.data_movimento as data_movimento',
    db_prefix() . 'fin_movimentacao_bancaria.id as id',
    db_prefix() . 'fin_movimentacao_bancaria.valor as valor',
    db_prefix() . 'fin_movimentacao_bancaria.tipo as tipo',
    db_prefix() . 'fin_movimentacao_bancaria.descricao as descricao',
    db_prefix() . 'fin_movimentacao_bancaria.user_cadastro as user_cadastro',
    db_prefix() . 'fin_movimentacao_bancaria.data_cadastro as data_cadastro',
    db_prefix() . 'fin_bancos.banco as banco',
    ]);

$sIndexColumn = 'id';
$sTable       = db_prefix() . 'fin_movimentacao_bancaria';

$join = [
    'LEFT JOIN ' . db_prefix() . 'fin_bancos ON ' . db_prefix() . 'fin_bancos.id = ' . db_prefix() . 'fin_movimentacao_bancaria.banco_id',
    ];
$additionalSelect = [
   // db_prefix() . 'convenio.id as convenio_id',
    ];



// FILTRO CONVÊNIOS
$this->ci->load->model('Convenios_model');

//$convenios = $this->ci->input->post('convenios_procedimentos');
//

if ($this->ci->input->post('convenios_procedimentos')) {
    $convenios = $this->ci->input->post('convenios_procedimentos');
   
    $_convenios = "";
    if ($convenios) {
      //  array_push($where, ' AND '. db_prefix() . 'items.convenio_id IN (' . implode(', ', $convenios) . ')');
    }
    
    
   
}

// FILTRO CATEGORIAS
if ($this->ci->input->post('categorias_procedimentos')) {
    $categorias = $this->ci->input->post('categorias_procedimentos');
    if (is_array($categorias)) {
       //  array_push($where, ' AND '. db_prefix() . 'items.group_id IN (' . implode(', ', $categorias) . ')');
    }
  
}

//FILTRO PROCEDIMENTOS

if ($this->ci->input->post('procedimento_items')) {
    $procedimentos = $this->ci->input->post('procedimento_items');
    // print_r($procedimentos); exit;
    $_procedimentos = [];
    if (is_array($procedimentos)) {
     // array_push($where, ' AND '. db_prefix() . 'items.id IN (' . implode(', ', $procedimentos) . ')');
    }
   
}

$empresa_id = $this->ci->session->userdata('empresa_id');
array_push($where, ' AND '.db_prefix() . 'fin_movimentacao_bancaria.empresa_id = '.$empresa_id );
array_push($where, ' AND '.db_prefix() . 'fin_movimentacao_bancaria.deleted = 0' );
//$where   = [];
$result  = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, $additionalSelect);
$output  = $result['output'];
$rResult = $result['rResult'];
$soma_entradas = 0;
$soma_saidas = 0;

$footer_data = [
                'soma_entradas' => 0,
                'soma_saidas'    => 0,              
            ];

foreach ($rResult as $aRow) {
    $row = [];

    $numberOutput = '';

   $numberOutput = '<a href="' . admin_url('invoices/list_invoices/' . $aRow['id']) . '" onclick="init_invoice(' . $aRow['id'] . '); return false;">' . format_invoice_number($aRow['id']) . '</a>';
   
    $numberOutput .= '<div class="row-options">';

    if (has_permission('invoices', '', 'delete')) {
        $numberOutput .= ' | <a href="' . admin_url('invoices/delete/' . $aRow['id']) . '" class="text-danger _delete">' . _l('delete') . '</a>';
    }
    $numberOutput .= '</div>';

   // $row[] = $numberOutput;
    
    $row[] = _d($aRow['data_movimento']);
    $row[] = $aRow['banco'];
    $row[] = $aRow['descricao'];
    
    $valor = $aRow['valor'];
    if($aRow['tipo'] == 'S'){
        $row[] = '';
        $row[] = app_format_money($valor, ' R$ ');
        $soma_saidas += $valor;
       
    }else{
        $row[] = app_format_money($valor, ' R$ ');
        $row[] = '';
        $soma_entradas += $valor;
    }
    
    
    $tipo = '<label class="label label-success"> ENTRADA </label>';
    if($aRow['tipo'] == 'S'){
        $row[] = '<label class="label label-danger"> SAÍDA </label>';
    }else{
        $row[] = $tipo;
    }
   
    $usuario = get_staff_full_name($aRow['user_cadastro']);
    $data_log = _d($aRow['data_cadastro']);
           
    $row[] = "$data_log <br> $usuario"; 

   
    
    

    // Custom fields add values
    foreach ($customFieldsColumns as $customFieldColumn) {
        $row[] = (strpos($customFieldColumn, 'date_picker_') !== false ? _d($aRow[$customFieldColumn]) : $aRow[$customFieldColumn]);
    }

    $row['DT_RowClass'] = 'has-row-options';

    $row = hooks()->apply_filters('invoices_table_row_data', $row, $aRow);

    $output['aaData'][] = $row;
    
    
}




$row2[] = "TOTAL";
$row2[] = "";
$row2[] = "";
$row2[] = app_format_money($soma_entradas, ' R$ ');
$row2[] = app_format_money($soma_saidas, ' R$ ');
$row2[] = "";
$row2[] = "";
$output['aaData'][] = $row2;