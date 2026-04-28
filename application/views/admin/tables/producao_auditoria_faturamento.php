<?php
defined('BASEPATH') or exit('No direct script access allowed');
$aColumns = [];
$where  = [];


if (has_permission('items', '', 'delete')) {
    $aColumns[] = '1';
}

//                    t.description as procedimento, t.group_id as group_id, g.name as categoria, c.company as paciente,  a.valor_medico
$aColumns = array_merge($aColumns, [
       'number',
        db_prefix() . 'medicos.nome_profissional as medico',
        db_prefix() . 'centrocusto.nome as centro_custo', 
        db_prefix() . 'convenio.name as convenio',
        get_sql_select_client_company(),
        db_prefix().'items_groups.name as categoria',
        db_prefix().'items.description as procedimento',
        db_prefix().'invoices.date as data_faturamento',
        db_prefix().'invoicepaymentrecords.date as data_pagamento',
        db_prefix().'itemable.qty as quantity_sold',
        db_prefix() . 'itemable.rate as valor_procedimento', // preço unitário
        // TOTAL FATURADO
        db_prefix() . 'itemable.rate * ' . db_prefix() . 'itemable.qty as rate_total', // total faturado
        //DESCONTO FATURA
        db_prefix() .'itemable.desconto_valor as desconto', // desconto fatura
        // VALOR ABERTO
        "(SELECT total - (SELECT COALESCE(SUM(amount),0) FROM tblinvoicepaymentrecords WHERE invoiceid = tblinvoices.id) - (SELECT COALESCE(SUM(amount),0) FROM tblcredits WHERE tblcredits.invoice_id=tblinvoices.id)) as valor_aberto",
        // VALOR FATURADO RECEBIDO
         "tblitemable.valor_tesouraria as valor_tesouraria",
         'tblitemable.valor_medico as valor_medico',
         "tblinvoices.datecreated as datecreated,        
         tblinvoices.addedfrom as addedfrom ",
    ]);

$sIndexColumn = 'id';
$sTable       = db_prefix() . 'invoices';

$join = [    
    'JOIN ' . db_prefix() . 'clients ON ' . db_prefix() . 'clients.userid = ' . db_prefix() . 'invoices.clientid',
    'JOIN ' . db_prefix() . 'itemable ON ' . db_prefix() . 'itemable.rel_id = ' . db_prefix() . 'invoices.id AND '. db_prefix() . 'itemable.rel_type="invoice"',
    'JOIN ' . db_prefix() . 'items ON ' . db_prefix() . 'items.id = ' . db_prefix() . 'itemable.item_id',
    'JOIN ' . db_prefix() . 'items_groups ON ' . db_prefix() . 'items_groups.id = ' . db_prefix() . 'items.group_id',                
    'LEFT JOIN ' . db_prefix() . 'convenio ON ' . db_prefix() . 'convenio.id = ' . db_prefix() . 'invoices.convenio',
    'LEFT JOIN '. db_prefix() .'centrocusto ON ' . db_prefix() . 'centrocusto.ID = ' .db_prefix(). 'invoices.centrocustoid',
    'JOIN ' . db_prefix() . 'medicos ON ' . db_prefix() . 'medicos.medicoid = ' . db_prefix() . 'itemable.medicoid',
    'LEFT JOIN ' . db_prefix() . 'invoicepaymentrecords ON ' . db_prefix() . 'invoicepaymentrecords.invoiceid = ' . db_prefix() . 'invoices.id',
    ];
$additionalSelect = [
 //   db_prefix() . 'appointly_producao_medica.id as id',
 
    ];


array_push($where, ' AND '.db_prefix() . 'invoices.status != 5',  ' AND '.db_prefix() . 'invoices.status IN (2)', 'AND tblinvoices.deleted = 0');

if ($this->ci->input->post('medicos_faturamento')) {
    $conta_financeira = $this->ci->input->post('medicos_faturamento');
   //print_r($conta_financeira); exit;
    $_convenios = "";
    if ($conta_financeira) {
       array_push($where, ' AND '. db_prefix() . "itemable.medicoid =  $conta_financeira");
        //array_push($where, ' AND '. db_prefix() . 'itemable.medicoid IN (' . implode(', ', $_medicos) . ')');
       
    }  
}

if ($this->ci->input->post('convenios_producao_auditoria')) {
    $convenios_producao_auditoria = $this->ci->input->post('convenios_producao_auditoria');
   //print_r($conta_financeira); exit;
    if ($convenios_producao_auditoria) {
      array_push($where, ' AND '. db_prefix() . 'invoices.convenio IN (' . implode(', ', $convenios_producao_auditoria) . ')');  
      // array_push($where, ' AND '. db_prefix() . 'convenio.id IN (' . implode(', ', $convenios_producao_auditoria) . ')');
    }  
}


//if ($this->ci->input->post('status_producao_auditoria')) {
    $status_producao_auditoria = $this->ci->input->post('status_producao_auditoria');
    if($status_producao_auditoria){
      //  array_push($where, ' AND '. db_prefix() . 'appointly_producao_medica.repasse_id > 0  ');
    }
    
    //array_push($where, ' AND '. db_prefix() . 'appointly_producao_medica.status =  ' .$status_producao_auditoria. '  ');
     
//}


//FILTRO DATA REPASSE

if ($this->ci->input->post('data_de')) {
    $data_de = $this->ci->input->post('data_de');
    
    if ($this->ci->input->post('data_ate')) {
        $data_ate = $this->ci->input->post('data_ate');
    }else{
        $data_ate = $data_de;
    }
   
    $_procedimentos = [];
    if ($data_de && $data_ate) {
     array_push($where, ' AND '. db_prefix() . 'invoicepaymentrecords.date between '. "'$data_de' and '$data_ate'"  );
    }
   
}

//echo $data_de.'<br>';
//echo $data_ate.'<br>';
//exit;

// FILTRO CATEGORIAS



//,    fat pagas

$GroupBy = ' GROUP BY tblinvoices.id, tblitemable.id';
//$where   = [];
$result  = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, $additionalSelect, $GroupBy);
$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];
   
      
       //$item_id =  $aRow['id']; 
        $_data = '<div class="checkbox"><input type="checkbox" value="' . $item_id . '"><label></label></div>';
        $row[] = $_data;
        $_data_invoice = '<a href="' . admin_url('invoices/list_invoices/' . $aRow['number']) . '" target="_blank">' . format_invoice_number($aRow['number']) . '</a> '.' '." $info";
        $row[] = $_data_invoice;
        $row[] = $aRow['medico'];
        //$row[] = $aRow['centro_custo'];
        $row[] = $aRow['convenio'];
        $row[] = $aRow['company'];
        $row[] = $aRow['categoria'];
        $row[] = $aRow['procedimento'];
        //$row[] = $aRow['data_faturamento'];
        $row[] = $aRow['data_pagamento'];
        $row[] = $aRow['quantity_sold'];
        $row[] = $aRow['valor_procedimento'];
        $row[] = $aRow['desconto']; // créditos
        $fat_total = $aRow['rate_total'] - $aRow['desconto'];
        $row[] = $fat_total;
        $row[] = $aRow['valor_tesouraria']; // créditos
        $row[] = $aRow['valor_medico']; // créditos
        
        $row['DT_RowClass'] = 'has-row-options';
  


    $output['aaData'][] = $row;
}
