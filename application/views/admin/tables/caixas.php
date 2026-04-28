<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
    db_prefix() . 'caixas.id as id',
    db_prefix() . 'caixas.name as name',
    db_prefix() . 'caixas.note as note',
    db_prefix() . 'caixas.aberto as aberto',
    db_prefix() . 'caixas.saldo as saldo',
    
    db_prefix() . 'caixas.name as category_name',

];
$join = [];


$where  = [];
$filter = [];


$sIndexColumn = 'id';
$sTable       = db_prefix() . 'caixas';

$aColumns = hooks()->apply_filters('expenses_table_sql_columns', $aColumns);

// Fix for big queries. Some hosting have max_join_limit
if (count($custom_fields) > 4) {
    @$this->ci->db->query('SET SQL_BIG_SELECTS=1');
}

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, []);
$output  = $result['output'];
$rResult = $result['rResult'];

$footer_data = ['total_caixa' => 0];

foreach ($rResult as $aRow) {
    $row = [];

    $row[] = $aRow['id'];

    $categoryOutput = '';
    $statusCaixa = '';
    $opcoes_caixa = '';

     if($aRow['aberto'] == 0){
         $statusCaixa .= ' <span class="label label-danger inline-block mtop4"> FECHADO </span>';
    }else{
        $statusCaixa .= ' <span class="label label-success inline-block mtop4"> ABERTO </span>';
    }
    
    $categoryOutput = '<a href="' . admin_url('caixas/list_registros_caixas/' . $aRow['id']) . '" >' . $aRow['name'] . '</a>';
    
    $categoryOutput .= '<div class="row-options">';
    $categoryOutput .= '<a href="' . admin_url('caixas/list_registros_caixas/' . $aRow['id']) . '" >' . _l('view') . '</a>';

    if (has_permission('expenses', '', 'edit')) {
      //  $categoryOutput .= ' | <a href="' . admin_url('caixas/expense/' . $aRow['id']) . '">' . _l('edit') . '</a>';
    }

    if (has_permission('expenses', '', 'delete')) {
     //   $categoryOutput .= ' | <a href="' . admin_url('expenses/delete/' . $aRow['id']) . '" class="text-danger _delete">' . _l('delete') . '</a>';
    }

    $categoryOutput .= '</div>';
    $row[] = $categoryOutput;

    
    $row[] = $aRow['note']; 

    $row[] = $statusCaixa; 
   // $row[] = '<a href="' . admin_url('expenses/list_expenses/' . $aRow['id']) . '" onclick="init_expense(' . $aRow['id'] . ');return false;">' . $aRow['expense_name'] . '</a>';


  

    //$member = $this->ci->staff_model->get(get_staff_user_id());
    //$caixa_atual = $member->caixa_id;
    $caixa_id = $aRow['id'];
    $this->ci->load->model('caixas_model');
   
    $retorna_caixa_id = $this->ci->caixas_model->get_ultimo_registro_caixa($caixa_id); 
    $ultimo_caixa_id = $retorna_caixa_id->id;
    
    $dados_caixa = $this->ci->caixas_model->get_caixa_registro_atual_por_caixa_id($ultimo_caixa_id); 
    $data_abertura = $dados_caixa->data_abertura;
    $usuario_abertura = $dados_caixa->usuario_abertura;
    
    $data_fechamento = $dados_caixa->data_fechamento;
    $usuario_fechamento= $dados_caixa->usuario_fechamento;
    
    if($data_fechamento){
        $exibe_data = $data_fechamento;
    }else if($data_abertura){
        $exibe_data = $data_abertura;
    }else{
        $exibe_data = "";
    }
    
    if($usuario_fechamento){
        $exibe_usuario = $usuario_fechamento;
    }else{
         $exibe_usuario = $usuario_abertura;
    }
    $member_caixa = $this->ci->staff_model->get($exibe_usuario);
    $operador = $member_caixa->firstname.' '.$member_caixa->lastname;
    
    
    $member = $this->ci->staff_model->get(get_staff_user_id());
    $caixa_atual = $member->caixa_id;
    //saldo
    
    //$saldo_caixa = $this->ci->caixas_model->get_qtde_saldo_caixa_atual_por_caixa_id($caixa_id); 
    //$quantidade = $saldo_caixa->quantidade;
    $saldo = $aRow['saldo'];
    $footer_data['total_caixa'] += $saldo;
    //$row[] = $quantidade;
    $saldo = app_format_money($saldo, 'R$');
    $row[] = "<span class='label label-default inline-block mtop4'> $saldo </span>";
    $row[] = _d($exibe_data);
    $row[] = $operador;
    
     if($aRow['aberto'] == 0){
        if (has_permission('tesouraria', '', 'open')) {
            if(!$caixa_atual){
            $opcoes_caixa .= ' <a href="' . admin_url('caixas/abrir_caixa/' . $aRow['id']) . '" class="btn btn-success">' . 'Abrir Caixa ' . '</a>';
           
            }
            $opcoes_caixa .= ' <a href="' . admin_url('caixas/transferir_caixa/' . $aRow['id']) . '" class="btn btn-warning">' . 'Transferência' . '</a>'; 
        }
     }else{
         
         if (has_permission('tesouraria', '', 'open')) {
            if($caixa_atual == $ultimo_caixa_id){ 
            $opcoes_caixa .= ' <a href="' . admin_url('caixas/caixa/' . $aRow['id']) . '" class="btn btn-danger">' . 'Fechar Caixa ' . '</a>';
            }
            $opcoes_caixa .= ' <a href="' . admin_url('caixas/transferir_caixa/' . $aRow['id']) . '" class="btn btn-warning">' . 'Transferência' . '</a>'; 
        }
         
     }
    
    $row[] = $opcoes_caixa;

    $output['aaData'][] = $row;
    $output['sums'] = $footer_data;
}
