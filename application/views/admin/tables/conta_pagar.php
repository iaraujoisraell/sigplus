<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [];
$where  = [];


if (has_permission('items', '', 'delete')) {
    $aColumns[] = '1';
}

$aColumns = array_merge($aColumns, [
    db_prefix() . 'fin_categories.name as categoria',
    db_prefix() . 'fin_plano_contas.descricao as plano_contas',
    db_prefix() . 'fin_fornecedores.company as fornecedor',
    db_prefix() . 'fin_bancos.banco as banco',
    db_prefix() . 'fin_centro_custo.descricao as centro_custo',
    db_prefix() . 'fin_tipo_documento.name as tipo_documento',
    db_prefix() . 'fin_lancamentos.parcela as parcela',
    db_prefix() . 'fin_lancamentos.valor as valor',
    db_prefix() . 'fin_lancamentos.data_vencimento as data_vencimento',
    db_prefix() . 'fin_lancamentos.data_emissao as data_emissao',
    db_prefix() . 'fin_lancamentos.status as status',
    db_prefix() . 'fin_lancamentos.complemento as complemento'
    ]);

$sIndexColumn = 'id';
$sTable       = db_prefix() . 'fin_lancamentos';

$join = [
    'INNER JOIN ' . db_prefix() . 'fin_categories ON ' . db_prefix() . 'fin_categories.id     = ' . db_prefix() . 'fin_lancamentos.categoria_id',
    'INNER JOIN ' . db_prefix() . 'fin_plano_contas ON ' . db_prefix() . 'fin_plano_contas.id = ' . db_prefix() . 'fin_lancamentos.plano_conta_id',
    'INNER JOIN ' . db_prefix() . 'fin_fornecedores ON ' . db_prefix() . 'fin_fornecedores.id = ' . db_prefix() . 'fin_lancamentos.cliente_fornecedor_id',
    'LEFT JOIN ' . db_prefix() . 'fin_bancos ON ' . db_prefix() . 'fin_bancos.id             = ' . db_prefix() . 'fin_lancamentos.banco_id',
    'INNER JOIN ' . db_prefix() . 'fin_centro_custo ON ' . db_prefix() . 'fin_centro_custo.id = ' . db_prefix() . 'fin_lancamentos.centro_custo_id',
    'INNER JOIN ' . db_prefix() . 'fin_tipo_documento ON ' . db_prefix() . 'fin_tipo_documento.id = ' . db_prefix() . 'fin_lancamentos.tipo_documento',
    
    ];
$additionalSelect = [
    db_prefix() . 'fin_lancamentos.id ',
    db_prefix() . 'fin_lancamentos.numero_documento ',
    db_prefix() . 'fin_lancamentos.total_parcela as total_parcela',
    db_prefix() . 'fin_bancos.agencia as agencia',
    db_prefix() . 'fin_bancos.numero_conta as numero_conta',
   
    ];


// Fix for big queries. Some hosting have max_join_limit
if (count($custom_fields) > 5) {
    @$this->ci->db->query('SET SQL_BIG_SELECTS=1');
}


// FILTRO CONVÊNIOS
$empresa_id = $this->ci->session->userdata('empresa_id');


//FILTRO PLANO DE CONTA

if ($this->ci->input->post('plano_conta')) {
    $plano_conta = $this->ci->input->post('plano_conta');
    if (is_array($plano_conta)) {
      array_push($where, ' AND '. db_prefix() . 'fin_lancamentos.plano_conta_id in (' . implode(', ', $plano_conta) . ')');
    }
   
}

// FILTRO FORNECEDOR
$fornecedores = $this->ci->input->post('fornecedor');
//print_r($fornecedores); exit;
//echo $this->ci->input->post('fornecedor'); exit;
if ($this->ci->input->post('fornecedor')) {
    $fornecedores = $this->ci->input->post('fornecedor');
    if (is_array($fornecedores)) {
         array_push($where, ' AND '. db_prefix() . 'fin_fornecedores.id IN (' . implode(', ', $fornecedores) . ')');
    }
  
}

// FILTRO CATEGORIAS
//echo $this->ci->input->post('fornecedor'); exit;
if ($this->ci->input->post('categorias')) {
    $categorias = $this->ci->input->post('categorias');
    if (is_array($categorias)) {
         array_push($where, ' AND '. db_prefix() . 'fin_lancamentos.categoria_id IN (' . implode(', ', $categorias) . ')');
    }
}

// FILTRO CATEGORIAS
//echo $this->ci->input->post('fornecedor'); exit;
if ($this->ci->input->post('documento')) {
    $documento = $this->ci->input->post('documento');
    if (is_array($documento)) {
         array_push($where, ' AND '. db_prefix() . 'fin_lancamentos.numero_documento IN (' . implode(', ', $documento) . ')');
    }
}



array_push($where, ' AND '.db_prefix() . 'fin_lancamentos.deleted = 0' );
array_push($where, ' AND '.db_prefix() . 'fin_lancamentos.empresa_id = '.$empresa_id );
//$where   = [];
$result  = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, $additionalSelect);
$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];
   
    // ID
    $numberOutput = '';
    $numberOutput = '<a target="_blank" href="' . admin_url('financeiro/parcelas_conta_pagar/' . $aRow['id']) . '" >' . 'DOC#'.$aRow['id'] . '</a>';    
    $numberOutput .= '<div class="row-options">';
    if (has_permission('invoices', '', 'edit')) {
        if($aRow['status'] == 0){
       // $numberOutput .= '| <a href="#" data-toggle="modal" data-target="#add_pagamento_modal" data-id="' . $aRow['id'] . '" class="text-success">' . 'Pago' . '</a>';        
        
       // $numberOutput .= '| <a href="#" data-toggle="modal" data-target="#add_conta_pagar_modal" data-id="' . $aRow['id'] . '">' . _l('edit') . '</a>';    
        $numberOutput .= ' | <a href="' . admin_url('financeiro/editar_titulo_pagar/' . $aRow['id']) . '">' . _l('edit') . '</a>';
        
         if (has_permission('invoices', '', 'delete')) {
        $numberOutput .= ' | <a href="' . admin_url('financeiro/delete_conta_pagar/' . $aRow['id']) . '" class="text-danger _delete">' . _l('delete') . '</a>';
    }
    
        }
        
    }
   
    $numberOutput .= '</div>';
    $row[] = $numberOutput;
    
    // DESCRICAO
    $row[] = $aRow['complemento'];
    // PLANO DE CONTAS
    $row[] = $aRow['plano_contas'];
    // CATEGORIA
    $row[] = render_tags($aRow['categoria']);
    // FORNECEDOR
    $row[] = $aRow['fornecedor'];
    
    // CENTRO CUSTO
    $row[] = $aRow['centro_custo'];
    // DOCUMENTOS
    $row[] = $aRow['tipo_documento'].'<br>'.$aRow['numero_documento'];
    //PARCELAS
    $row[] = $aRow['total_parcela'];
    // DATA DE VENCIMENTO
    $row[] = _d($aRow['data_vencimento']);
    // VALOR
    $row[] = app_format_money($aRow['valor'], ' R$ ');
    // BANCO
    $row[] = $aRow['banco'].'<br> ('.$aRow['agencia'].'['. $aRow['numero_conta'] .'])';
    // DATA EMISSAO
    if($aRow['data_emissao'] != '0000-00-00'){
    $row[] = _d($aRow['data_emissao']);
    }else{
     $row[] = "";   
    }
    // STATUS
    if($aRow['status'] == 1){
        $status = '<label class="label label-success">CONCLUÍDO</label>';
    }else{
        $status = '<label class="label label-warning">EM ANDAMENTO</label>';
    }
    $row[] = $status;
    
     $row['DT_RowClass'] = 'has-row-options';

    $row = hooks()->apply_filters('invoices_table_row_data', $row, $aRow);

    $output['aaData'][] = $row;
}
