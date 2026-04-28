<?php
defined('BASEPATH') or exit('No direct script access allowed');
$aColumns = [];
$where  = [];


//if (has_permission('faturamento_menu', '', 'view')) {
    $aColumns[] = '1';
//}

//                    t.description as procedimento, t.group_id as group_id, g.name as categoria, c.company as paciente,  a.valor_medico
$aColumns = array_merge($aColumns, [
    db_prefix() . 'medicos.nome_profissional as nome_profissional',
    db_prefix() . 'appointly_appointments.date as data_atendimento',
     db_prefix() . 'appointly_appointments.start_hour as start_hour',
    db_prefix() . 'clients.company as paciente',
    db_prefix() . 'convenio.name as convenio',
    db_prefix() . 'appointly_appointments.guia_entrega as status',
    ]);

$sIndexColumn = 'id';
$sTable       = db_prefix() . 'appointly_appointments';

$join = [    
    'INNER JOIN ' . db_prefix() . 'medicos                  ON ' . db_prefix() . 'medicos.medicoid       = ' . db_prefix() . 'appointly_appointments.medico_id',
//    'INNER JOIN ' . db_prefix() . 'items                    ON ' . db_prefix() . 'items.id                  = ' . db_prefix() . 'appointly_producao_medica.item_id',
//    'INNER JOIN ' . db_prefix() . 'items_groups             ON ' . db_prefix() . 'items_groups.id           = ' . db_prefix() . 'items.group_id',
    'INNER JOIN ' . db_prefix() . 'convenio                 ON ' . db_prefix() . 'convenio.id               = ' . db_prefix() . 'appointly_appointments.convenio',
//    'INNER JOIN ' . db_prefix() . 'appointly_appointments   ON ' . db_prefix() . 'appointly_appointments.id = ' . db_prefix() . 'appointly_producao_medica.agenda_id',
    'INNER JOIN ' . db_prefix() . 'clients                  ON ' . db_prefix() . 'clients.userid            = ' . db_prefix() . 'appointly_appointments.contact_id',
    
    ];
$additionalSelect = [
    db_prefix() . 'appointly_appointments.id as id',
   
    ];

$custom_fields = get_custom_fields('items');
 
foreach ($custom_fields as $key => $field) {
    $selectAs = (is_cf_date($field) ? 'date_picker_cvalue_' . $key : 'cvalue_' . $key);

    array_push($customFieldsColumns, $selectAs);
    array_push($aColumns, 'ctable_' . $key . '.value as ' . $selectAs);
    array_push($join, 'LEFT JOIN ' . db_prefix() . 'customfieldsvalues as ctable_' . $key . ' ON ' . db_prefix() . 'items.id = ctable_' . $key . '.relid AND ctable_' . $key . '.fieldto="items_pr" AND ctable_' . $key . '.fieldid=' . $field['id']);
}

// Fix for big queries. Some hosting have max_join_limit
if (count($custom_fields) > 4) {
    @$this->ci->db->query('SET SQL_BIG_SELECTS=1');
}


//if ($this->ci->input->post('conta_financeira')) {
    $conta_financeira = $this->ci->input->post('conta_financeira');
    
   //print_r($conta_financeira); exit;
    $_convenios = "";
    if (!$conta_financeira) {
        $conta_financeira = 0;
    }
       //array_push($where, ' AND '. db_prefix() . 'appointly_producao_medica.medico_id IN (' . implode(', ', $conta_financeira) . ')');
       array_push($where, ' AND '. db_prefix() . 'appointly_appointments.medico_id = '.$conta_financeira.' ');
     
//}

if ($this->ci->input->post('convenios_producao_auditoria')) {
    $convenios_producao_auditoria = $this->ci->input->post('convenios_producao_auditoria');
   //print_r($conta_financeira); exit;
    if ($convenios_producao_auditoria) {
       array_push($where, ' AND '. db_prefix() . 'convenio.id IN (' . implode(', ', $convenios_producao_auditoria) . ')');
    }  
}


//if ($this->ci->input->post('status_producao_auditoria')) {
    $status_producao_auditoria = $this->ci->input->post('status_producao_auditoria');
    if($status_producao_auditoria){
        array_push($where, ' AND '. db_prefix() . 'appointly_appointments.guia_entrega =  ' .$status_producao_auditoria. '  ');
    }else{
        array_push($where, ' AND '. db_prefix() . 'appointly_appointments.guia_entrega =  ' .$status_producao_auditoria. '  ');
    }
    
     
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
     array_push($where, ' AND '. db_prefix() . 'appointly_appointments.date between '. "'$data_de' and '$data_ate' "  );
    }
   
}

//echo $data_de.'<br>';
//echo $data_ate.'<br>';
//exit;

// FILTRO CATEGORIAS





array_push($where, ' AND '.db_prefix() . 'appointly_appointments.deleted = 0' );
//$where   = [];
$result  = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, $additionalSelect);
$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];
    for ($i = 0; $i < count($aColumns); $i++) {
        if (strpos($aColumns[$i], 'as') !== false && !isset($aRow[$aColumns[$i]])) {
            $_data = $aRow[strafter($aColumns[$i], 'as ')];
        } else {
            $_data = $aRow[$aColumns[$i]];
        }
        
        if ($aColumns[$i] == '1') {
            $item_id =  $aRow['id']; 
            $_data = '<div class="checkbox"><input type="checkbox" value="' . $item_id . '"><label></label></div>';
           // $_data = "<label>-</label>";  
           // $_data = '<div class="checkbox"><input type="checkbox" value="' . $aRow['id'] . '"><label></label></div>';
        } elseif ($i == '2') {
           
            $data_repasse = _d($aRow['data_atendimento']);
            $_data = $data_repasse;
        } elseif ($i == '6') {
            $status = $aRow['status'];
            if($status == 0){
                $n_status = "PENDENTE";
            }ELSE{
                $n_status = "<label class='label label-success'>CONFIRMADO</label> ";
            }
            
            
            $_data = "$n_status";
        } else {
        
            if (startsWith($aColumns[$i], 'ctable_') && is_date($_data)) {
                $_data = _d($_data);
            }
        }

        $row[]              = $_data;
        $row['DT_RowClass'] = 'has-row-options';
    }


    $output['aaData'][] = $row;
}
