<?php

defined('BASEPATH') or exit('No direct script access allowed');


$aColumns = [
    'userid as id',
    'company as nome',
    'vat as cpf',
    'phonenumber as contato',
    'dt_nascimento as nascimento',
    'numero_carteirinha as carteirinha',
];

$sIndexColumn = 'userid';
$sTable       = db_prefix().'clients';
$where        = [];

/*
 * FILTROS
 */
$join = [];
$where = [];


if ($this->ci->input->post('company')) {
    $value = $this->ci->input->post('company');
    array_push($where, 'AND '.db_prefix()."clients.company like '%$value%'");
}
if ($this->ci->input->post('nascimento')) {
    $value = $this->ci->input->post('nascimento');
    array_push($where, 'AND '.db_prefix()."clients.dt_nascimento = '$value'");
}

if ($this->ci->input->post('vat')) {
    $value = $this->ci->input->post('vat');
    array_push($where, 'AND '.db_prefix()."clients.vat like '%$value%'");
}

if ($this->ci->input->post('numero_carteirinha')) {
    $value = $this->ci->input->post('numero_carteirinha');
    array_push($where, 'AND '.db_prefix()."clients.numero_carteirinha like '%$value%'");
}

   array_push($where, ' AND '.db_prefix().'clients.deleted = 0 ');


$empresa_id = $this->ci->session->userdata('empresa_id');
array_push($where, ' AND '.db_prefix() . 'clients.empresa_id = '.$empresa_id );

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [
    db_prefix().'clients.zip as zip',
]);

$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];

    // User id
    $row[] = $aRow['id'];

    // Company
    $company  = $aRow['nome'];

    $url = admin_url('clients/client/' . $aRow['id']);


    $company = '<a href="' . $url . '">' . $company . '</a>';

    $company .= '<div class="row-options">';
    $company .= '<a onclick="'."selecionar_client('".$aRow['id']."'".');">Selecionar</a>';
    $company .= '</div>';

    $row[]  = $company;
    $row[]  = $aRow['cpf'];
    if($aRow['nascimento'] != '0000-00-00' && $aRow['nascimento'] != ''){
        $row[] = date("d/m/Y", strtotime($aRow['nascimento']));
    } else {
        $row[] = '';
    }
    // Primary contact phone
    $row[] = ($aRow['phonenumber'] ? '<a href="tel:' . $aRow['phonenumber'] . '">' . $aRow['phonenumber'] . '</a>' : '');
    $row[] = $aRow['carteirinha'];
    
    $row[] = '<a class="btn btn-success btn-icon" onclick="'."selecionar_client('".$aRow['id']."'".');" >Selecionar</a>';
    
    // Primary contact phone
    

    $output['aaData'][] = $row;
}
