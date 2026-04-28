<?php

defined('BASEPATH') or exit('No direct script access allowed');
$full = "CONCAT ( firstname, ' ', lastname ) as full_name";
$aColumns = [
    'id',
    'user_created as user',
    'date_created as date',
    'action as action',
    'ip as ip',
    $full,
    'tbldepartments_log.staffid as staffid',
];
$sIndexColumn = 'id';
$sTable = db_prefix() . 'departments_log';
$where = [];
$empresa_id = $this->ci->session->userdata('empresa_id');
$departmentid = $this->ci->input->post('departmentid');
array_push($where, ' AND ' . db_prefix() . 'departments_log.empresa_id = ' . $empresa_id);
array_push($where, ' AND ' . db_prefix() . 'departments_log.departmentid = ' . $departmentid);
$result = data_tables_init($aColumns, $sIndexColumn, $sTable, [' LEFT JOIN tblstaff on tblstaff.staffid = tbldepartments_log.staffid'], $where, [], '', [], ' order by tbldepartments_log.id desc');

$output = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];

    $_data = '<a href="#">' . staff_profile_image($aRow['staffid'], [
               'staff-profile-image-small',
     ]) . '</a>';
    $_data .= ' <a href="#"> ' . $aRow['full_name']  . '</a>';

    $row[] = $_data;
    $action = '';
    if($aRow['action'] == 'DELETE STAFF'){
        $row[] = '<p style="color: red;">'. $aRow['action']. '<i class="fa fa-times"></i></p>';
    } else{
        $row[] = '<p>'. $aRow['action']. '</p>';
    }

    

    $row[] = date("d/m/Y h:i:s", strtotime($aRow['date'])).' - ' . get_staff_full_name($aRow['user']);

   $row[] = $aRow['ip'];

    $row['DT_RowClass'] = 'has-row-options';
    $output['aaData'][] = $row;
}
