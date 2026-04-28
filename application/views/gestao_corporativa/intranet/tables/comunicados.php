<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
    db_prefix() . '_intranet_ci.id as ci_id',
    db_prefix() . '_intranet_ci.codigo as codigo',
];

$sIndexColumn = 'id';
$sTable = db_prefix() . '_intranet_ci';
$where = [];

$staff_id = get_staff_user_id();
$empresa_id = $this->ci->session->userdata('empresa_id');
if ($this->ci->input->post('my')) {
    $join = [];
    $additionalSelect = [
        db_prefix() . '_intranet_ci.titulo as titulo',
        db_prefix() . '_intranet_ci.dt_created as enviado',
    ];
    array_push($where, 'AND ' . db_prefix() . '_intranet_ci.user_create = ' . $staff_id);
} else {
    $join = ['INNER JOIN ' . db_prefix() . '_intranet_ci_send ON ' . db_prefix() . '_intranet_ci_send.ci_id = ' . db_prefix() . '_intranet_ci.id'];

    $additionalSelect = [
        db_prefix() . '_intranet_ci.titulo as titulo',
        db_prefix() . '_intranet_ci_send.dt_send as enviado',
        db_prefix() . '_intranet_ci_send.dt_ciente as lido',
        db_prefix() . '_intranet_ci_send.dt_read as ciente'
    ];

    array_push($where, 'AND ' . db_prefix() . '_intranet_ci_send.deleted = 0');
    array_push($where, 'AND ' . db_prefix() . '_intranet_ci_send.empresa_id = ' . $empresa_id);
    array_push($where, 'AND ' . db_prefix() . '_intranet_ci_send.staff_id = ' . $staff_id);
}






array_push($where, 'AND ' . db_prefix() . '_intranet_ci.deleted = 0');
array_push($where, 'AND ' . db_prefix() . '_intranet_ci.empresa_id = ' . $empresa_id);



$orderby = " order by tbl_intranet_ci.id desc";

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, $additionalSelect, "", "", $orderby);

$output = $result['output'];

$rResult = $result['rResult'];

foreach ($rResult as $aRow) {

    $row = [];

    if($aRow['codigo']){
        $row[] = '<a href="' . base_url() . 'gestao_corporativa/intra/Comunicado/visualizar_comunicado/?id=' . $aRow['ci_id'] . '" target="_black">CI '.$aRow['codigo'].'</a>';
    } else {
        $row[] = '<a href="' . base_url() . 'gestao_corporativa/intra/Comunicado/visualizar_comunicado/?id=' . $aRow['ci_id'] . '" target="_black">CI #'.$aRow['ci_id'].'</a>';
    }
    
    $row[] = strtoupper($aRow['titulo']);
    $row[] = date("d/m/Y H:i:s", strtotime($aRow['enviado']));
    if ($this->ci->input->post('my')) {
        $send = $this->ci->Comunicado_model->get_comunicado_send($aRow['ci_id']);
        
        
        //print_r($send); exit;
        if ($send) {

            
            $quantidade = count($send);
            $cientes = 0;

            foreach ($send as $staff) {
                if ($staff['status'] == 1) {
                    $cientes++;
                }
            }

            $cientes_qtd = "$cientes de $quantidade";
            //echo 'aqui'; exit;
        }
        $row[] = $cientes_qtd;
        
    } else {
        if(strtotime($aRow['lido'])){
           $row[] = date("d/m/Y H:i:s", strtotime($aRow['lido'])); 
        } else {
            $row[] = ''; 
        }
        if(strtotime($aRow['ciente'])){
           $row[] = date("d/m/Y H:i:s", strtotime($aRow['ciente'])); 
        } else {
            $row[] = ''; 
        }
        
        
    }
    

    $options = '';

    if ($this->ci->input->post('my')) {
       $options .= '<a type="button" href="' . base_url('gestao_corporativa/intra/comunicado/config?id=' . $aRow['ci_id']) . '" class="btn btn-xs btn-success mleft5"><i class="fa fa-users"></i></a>'; 
       
    }
    $options .= '<a type="button" href="' . base_url('gestao_corporativa/intra/comunicado/pdf/' . $aRow['ci_id']) . '" target="_blanck" class="btn btn-xs btn-info mleft5"><i class="fa fa-file-pdf-o"></i></a>'; 
    

    $row[] = $options;
    $output['aaData'][] = $row;
} 




