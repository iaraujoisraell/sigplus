<?php

defined('BASEPATH') or exit('No direct script access allowed');

$project_id = $this->ci->input->post('project_id');


$aColumns = [
    'tblappointly_appointments.id as id',
    'company',
    'tblconvenio.name as convenio',
    'tblappointly_appointment_types.type as type',
    'date',
    'tblmedicos.nome_profissional as nome_profissional',

    ];

$sIndexColumn = 'id';
$sTable       = db_prefix() . 'appointly_appointments';

$join = [
    'LEFT JOIN ' . db_prefix() . 'staff ON ' . db_prefix() . 'staff.staffid = ' . db_prefix() . 'appointly_appointments.created_by',
    'LEFT JOIN ' . db_prefix() . 'clients ON ' . db_prefix() . 'clients.userid = ' . db_prefix() . 'appointly_appointments.contact_id',
    'LEFT JOIN ' . db_prefix() . 'convenio ON ' . db_prefix() . 'convenio.id = ' . db_prefix() . 'appointly_appointments.convenio',
    'LEFT JOIN ' . db_prefix() . 'medicos ON ' . db_prefix() . 'medicos.medicoid = ' . db_prefix() . 'appointly_appointments.medico_id',
    'LEFT JOIN ' . db_prefix() . 'appointly_appointment_types ON ' . db_prefix() . 'appointly_appointment_types.id = ' . db_prefix() . 'appointly_appointments.type_id',
];

$additionalSelect = [
    'approved',
    'created_by',
    'tblappointly_appointments.name',
    'tblappointly_appointments.confirmar_chegada',
    'tblappointly_appointments.inicio_atendimento',
    'tblappointly_appointments.data_chegada',
    'tblappointly_appointment_types.color',
    'tblconvenio.cor as convenio_cor',
    db_prefix() . 'appointly_appointments.email as contact_email',
    db_prefix() . 'appointly_appointments.phone',
    'tblappointly_appointments.medico_id',
    'cancelled',
    'bloqueio',
    'finished',
    'contact_id',
    'google_calendar_link',
    'google_added_by_id',
    'feedback'
];

$custom_fields = get_table_custom_fields('invoice');

foreach ($custom_fields as $key => $field) {
    $selectAs = (is_cf_date($field) ? 'date_picker_cvalue_' . $key : 'cvalue_' . $key);

    array_push($customFieldsColumns, $selectAs);
    array_push($aColumns, 'ctable_' . $key . '.value as ' . $selectAs);
    array_push($join, 'LEFT JOIN ' . db_prefix() . 'customfieldsvalues as ctable_' . $key . ' ON ' . db_prefix() . 'invoices.id = ctable_' . $key . '.relid AND ctable_' . $key . '.fieldto="' . $field['fieldto'] . '" AND ctable_' . $key . '.fieldid=' . $field['id']);
}

$where  = [];
$filter = [];



$member = $this->ci->staff_model->get(get_staff_user_id());
$paciente_atual = $member->paciente_atual;

array_push($where, 'AND ' . db_prefix() . 'appointly_appointments.contact_id = "'.$paciente_atual.'" ');
array_push($where, 'AND ' . db_prefix() . 'appointly_appointments.inicio_atendimento = "1" ');

$aColumns = hooks()->apply_filters('invoices_table_sql_columns', $aColumns);

// Fix for big queries. Some hosting have max_join_limit
if (count($custom_fields) > 4) {
    @$this->ci->db->query('SET SQL_BIG_SELECTS=1');
}
$order = "ORDER BY date DESC";
$result = data_tables_init_order($aColumns, $sIndexColumn, $sTable, $join, $where, $additionalSelect, "", $order );
$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];

    $numberOutput = '';

    // If is from client area table
    $numberOutput = '<a href="' . admin_url('appointments/abrir_atendimento/' . $aRow['id']) . '" onclick="init_atendimento(' . $aRow['id'] . '); return false;">' . _d($aRow['date']) . '</a>';
    $row[] = $numberOutput;
    // paciente
    $row[] = $aRow['nome_profissional'];
    
    //$row[] = '<a href="' . admin_url('projects/view/' . $aRow['project_id']) . '">' . $aRow['project_name'] . '</a>';
    $label_class_tipo = $aRow['color'];
    $type = $aRow['type']; 
    $label_tipo = '<span data-color="$label_class_tipo"  class="label label-' . $label_class_tipo . '">' . $type . '</span>';
    $row[] = $label_tipo;
    
    $row[] = $label_tipo;
   
    
    $row[] = $aRow['convenio'];

  
    /*
     * STATUS
     */
    //  CANCELADO
    if ($aRow['cancelled'] && $aRow['finished'] == 0) {
        $row[] = '<span class="label label-danger">' . strtoupper(_l('appointment_cancelled')) . '</span>';
        // FALTOU
    } else if (!$aRow['finished'] && !$aRow['cancelled'] && $aRow['approved'] == 0 && date('Y-m-d H:i', strtotime($aRow['date'])) < date('Y-m-d H:i')) {
        $row[] = '<span class="label label-danger">' . strtoupper(_l('appointment_missed_label')) . '</span>';
        // CONFIRMAdO
    } else if (!$aRow['finished'] && !$aRow['cancelled'] && $aRow['approved'] == 1 && $aRow['confirmar_chegada'] == 0 ) {
        $label_status = strtoupper('Confirmado') ;
        $row[] = '<button class="label label-info mleft5" data-toggle="tooltip" data-id="' . $aRow['id'] . '" onclick="appointmentUpdateModal(this)">'.$label_status.'</button>';
        // PENNDENTE
    } else if (!$aRow['finished'] && !$aRow['cancelled'] && $aRow['approved'] == 0) {
        $label_status = strtoupper('AGENDADO') ;
        $row[] = '<button class="label label-warning mleft5" data-toggle="tooltip" data-id="' . $aRow['id'] . '" onclick="appointmentUpdateModal(this)">'.$label_status.'</button>';
        // FILA DE ESPERA
    } else if ($aRow['confirmar_chegada'] == 1 && $aRow['finished']== 0 && $aRow['inicio_atendimento']== 0) {
        $row[] = '<span class="label label-warning">' . strtoupper('EM ESPERA') . '</span>';
       
      // em atendimento
    }else if ($aRow['confirmar_chegada'] == 1 && $aRow['finished']== 0 && $aRow['inicio_atendimento']== 1) {
                $row[] = '<span class="label label-info">' . strtoupper('EM ATENDIMENTO') . '</span>';
                //ATENDIDO
    }else if ($aRow['finished'] == 1) {
        $row[] = '<span class="label label-success">' . strtoupper('ATENDIDO') . '</span>';
    }
    
    
    

    $row[] = render_tags($aRow['tags']);

    $row[] = _d($aRow['duedate']);

    /*
     * STATUS
     */
          
    
    $row[] = format_invoice_status($aRow[db_prefix() . 'invoices.status']);



    $row['DT_RowClass'] = 'has-row-options';

    $row = hooks()->apply_filters('invoices_table_row_data', $row, $aRow);

    $output['aaData'][] = $row;
}
