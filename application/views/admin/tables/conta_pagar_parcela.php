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
    db_prefix() . 'fin_lancamentos_parcelas.valor_parcela as valor_parcela',
    db_prefix() . 'fin_lancamentos_parcelas.subtotal as subtotal',
    db_prefix() . 'fin_lancamentos_parcelas.desconto as desconto',
    db_prefix() . 'fin_lancamentos_parcelas.descricao as descricao',
    db_prefix() . 'fin_lancamentos_parcelas.data_vencimento as data_vencimento',
    db_prefix() . 'fin_lancamentos_parcelas.status as status'
    ]);

$sIndexColumn = 'id';
$sTable       = db_prefix() . 'fin_lancamentos_parcelas';

$join = [
    'INNER JOIN ' . db_prefix() . 'fin_lancamentos ON ' . db_prefix() . 'fin_lancamentos.id     = ' . db_prefix() . 'fin_lancamentos_parcelas.titulo_id',
    'INNER JOIN ' . db_prefix() . 'fin_plano_contas ON ' . db_prefix() . 'fin_plano_contas.id = ' . db_prefix() . 'fin_lancamentos.plano_conta_id',
    'INNER JOIN ' . db_prefix() . 'fin_categories ON ' . db_prefix() . 'fin_categories.id     = ' . db_prefix() . 'fin_plano_contas.categoria_id',
    'INNER JOIN ' . db_prefix() . 'fin_fornecedores ON ' . db_prefix() . 'fin_fornecedores.id = ' . db_prefix() . 'fin_lancamentos.cliente_fornecedor_id',
    'LEFT JOIN ' . db_prefix() . 'fin_bancos ON ' . db_prefix() . 'fin_bancos.id             = ' . db_prefix() . 'fin_lancamentos_parcelas.banco_id',
    'INNER JOIN ' . db_prefix() . 'fin_centro_custo ON ' . db_prefix() . 'fin_centro_custo.id = ' . db_prefix() . 'fin_lancamentos.centro_custo_id',
    'INNER JOIN ' . db_prefix() . 'fin_tipo_documento ON ' . db_prefix() . 'fin_tipo_documento.id = ' . db_prefix() . 'fin_lancamentos.tipo_documento',
    'LEFT JOIN ' . db_prefix() . 'fin_payment_modes ON ' . db_prefix() . 'fin_payment_modes.id = ' . db_prefix() . 'fin_lancamentos_parcelas.forma_pagamento',
    ];
$additionalSelect = [
    db_prefix() . 'fin_lancamentos_parcelas.id ',
    db_prefix() . 'fin_lancamentos.numero_documento ',
    db_prefix() . 'fin_lancamentos.complemento ',
    db_prefix() . 'fin_lancamentos.total_parcela as total_parcela',
    db_prefix() . 'fin_bancos.agencia as agencia',
    db_prefix() . 'fin_bancos.numero_conta as numero_conta',
    db_prefix() . 'fin_lancamentos.data_emissao',
    db_prefix() . 'fin_lancamentos_parcelas.parcela  ',
    db_prefix() . 'fin_lancamentos_parcelas.titulo_id  ',
    db_prefix() . 'fin_lancamentos_parcelas.data_pagamento',
    db_prefix() . 'fin_payment_modes.name as modo_pagamento',
    db_prefix() . 'fin_lancamentos_parcelas.numero_documento as numero_documento_parcela',
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
         array_push($where, ' AND '. db_prefix() . 'fin_categories.id IN (' . implode(', ', $categorias) . ')');
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


// FILTRO STATUS

if ($this->ci->input->post('status_parcela')) {
    $status_parcela = $this->ci->input->post('status_parcela');
    if($status_parcela == 2){
        $status_parcela = 0;
    }
    array_push($where, ' AND '. db_prefix() . 'fin_lancamentos_parcelas.status = '.$status_parcela );
}

// DATA VENCIMENTO
if ($this->ci->input->post('data_de')) {
    $data_de = $this->ci->input->post('data_de');
    
    
    if ($this->ci->input->post('data_ate')) {
        $data_ate = $this->ci->input->post('data_ate');
    }else{
        $data_ate = $data_de;
    }
   
    
    if ($data_de) {
    // array_push($where, ' AND '. db_prefix() . 'fin_lancamentos_parcelas.data_vencimento = '. "'$data_de' " );
    }
    
    if ($data_de && $data_ate) {
     array_push($where, ' AND '. db_prefix() . 'fin_lancamentos_parcelas.data_vencimento between '. "'$data_de' and '$data_ate'" );
    }  
}

// DATA PAGAMENTO
if ($this->ci->input->post('pagamento_de')) {
    $pagamento_de = $this->ci->input->post('pagamento_de');
    
    
    if ($this->ci->input->post('pagamento_ate')) {
        $pagamento_ate = $this->ci->input->post('pagamento_ate');
    }else{
        $pagamento_ate = $pagamento_de;
    }
   
    
    if ($pagamento_de) {
    // array_push($where, ' AND '. db_prefix() . 'fin_lancamentos_parcelas.data_vencimento = '. "'$data_de' " );
    }
    
    if ($pagamento_de && $pagamento_ate) {
     array_push($where, ' AND '. db_prefix() . 'fin_lancamentos_parcelas.data_pagamento between '. "'$pagamento_de' and '$pagamento_ate'" );
    }
   
}



array_push($where, ' AND '.db_prefix() . 'fin_plano_contas.deleted = 0' );
array_push($where, ' AND '.db_prefix() . 'fin_categories.deleted = 0' );
array_push($where, ' AND '.db_prefix() . 'fin_lancamentos_parcelas.deleted = 0' );
array_push($where, ' AND '.db_prefix() . 'fin_lancamentos.deleted = 0' );
array_push($where, ' AND '.db_prefix() . 'fin_lancamentos.empresa_id = '.$empresa_id );

$orderby = "order by tblfin_lancamentos_parcelas.valor_parcela desc";

//$where   = [];
$result  = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, $additionalSelect, '', '', $orderby);
$output  = $result['output'];
$rResult = $result['rResult'];
$soma_parcela = 0;
$soma_desconto = 0;
foreach ($rResult as $aRow) {
    $row = [];
   
    // ID
    if($aRow['numero_documento_parcela']){
        $num_doc = $aRow['numero_documento_parcela']; 
    }else{
        $num_doc = $aRow['numero_documento']; 
    }
    
    $numberOutput = $num_doc;    
    $numberOutput .= '<div class="row-options">';
    $numberOutput .= '  <a target="_blank" href="' . admin_url('financeiro/parcelas_conta_pagar/' . $aRow['titulo_id']) . '">' . 'Parcelas' . '</a>';
    if (has_permission('invoices', '', 'edit')) {
        if($aRow['status'] == 0){
      //  $numberOutput .= '| <a href="#" data-toggle="modal" data-target="#pagamento_modal" data-id="' . $aRow['id'] . '" class="text-success">' . 'Pagamento' . '</a>';        
        
        $numberOutput .= '| <a href="#" data-toggle="modal" data-target="#add_parcela_modal" data-id="' . $aRow['id'] . '">' . _l('edit') . '</a>';    
      //  $numberOutput .= ' | <a href="' . admin_url('invoices/invoice/' . $aRow['id']) . '">' . _l('edit') . '</a>';
        
        }
        
        if (has_permission('invoices', '', 'delete')) {
         if($aRow['status'] == 1){   
            $numberOutput .= ' | <a href="' . admin_url('financeiro/delete_pagamento_parcela/' . $aRow['id']) . '" class="text-danger _delete">' . 'Cancelar Pgto' . '</a>';
         }
        }
        
    }
   
    $numberOutput .= '</div>';
    
    $row[] = '<div class="checkbox"><input type="checkbox" value="' . $aRow['id'] . '"><label></label></div>';
    
    $row[] = $numberOutput;
    
    // PLANO DE CONTAS
    $row[] = $aRow['categoria'].' <br>'.$aRow['plano_contas'];
    //$info = render_tags($aRow['numero_documento']);
    $row[] = $aRow['complemento'].'<br>'.$aRow['descricao'];
    
    
    // FORNECEDOR
    $row[] = $aRow['fornecedor'];
    // BANCO   
   // $row[] = $aRow['banco'].'<br> ('.$aRow['agencia'].'['. $aRow['numero_conta'] .'])';
    // CENTRO CUSTO
    $row[] = $aRow['centro_custo'];
    
    //PARCELAS
    
    $row[] = $aRow['parcela'].'/'.$aRow['total_parcela']; 
    
    //$row[] =   app_format_money($aRow['subtotal'], 'R$');
    $row[] =   app_format_money($aRow['desconto'], 'R$');
    $soma_desconto += $aRow['desconto'];
    // valor parcela
    $row[] =   app_format_money($aRow['valor_parcela'], 'R$');
    
    $soma_parcela += $aRow['valor_parcela'];
    
    //$row[] = app_format_money($soma_parcela, 'R$');
    
    
   // $row[] = _d($aRow['data_emissao']);
    
    // DATA DE VENCIMENTO
    $row[] = _d($aRow['data_vencimento']);
    
    // // DATA PAGAMENTO
    $row[] = _d($aRow['data_pagamento']);
   
    // FORMA PAGAMENTO
    if($aRow['banco']){
        $dados_banco = $aRow['banco'].'<br> ('.$aRow['agencia'].'['. $aRow['numero_conta'] .'])';
    }
    $row[] = $aRow['modo_pagamento'].'<br>'.$dados_banco;
    
    // STATUS
    if($aRow['status'] == 1){
        $status = '<label class="label label-success">PAGO</label>';
    }else{
        
        if($aRow['data_vencimento'] < date('Y-m-d')){
            $status = '<label class="label label-danger">ATRASADO</label>';
        }else{
            $status = '<label class="label label-warning">NÃO PAGO</label>';
        }
        
        
    }
    $row[] = $status;
    
     $row['DT_RowClass'] = 'has-row-options';

    $row = hooks()->apply_filters('invoices_table_row_data', $row, $aRow);

    $output['aaData'][] = $row;
}

$row2[] = "";
$row2[] = "TOTAL";
$row2[] = "";
$row2[] = "";
$row2[] = "";
$row2[] = "";
$row2[] = "";
$row2[] = app_format_money($soma_desconto, ' R$ ');
$row2[] = app_format_money($soma_parcela, ' R$ ');
$row2[] = "";
$row2[] = "";
$row2[] = "";
$row2[] = "";
$output['aaData'][] = $row2;