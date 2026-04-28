<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [];
$where = [];
$join = [];

$aColumns = array_merge($aColumns, [
    db_prefix() . '_intranet_log.members as members',
    db_prefix() . '_intranet_log.msg as msg',
    db_prefix() . '_intranet_log.date_created as date_created',
    db_prefix() . '_intranet_log.user_created as user_created'
        ]);
$sIndexColumn = 'id';
$sTable = db_prefix() . '_intranet_log';

$additionalSelect = ['id'];

array_push($where, ' AND ' . db_prefix() . '_intranet_log.empresa_id = ' . $this->ci->session->userdata('empresa_id'));
array_push($where, ' AND ' . db_prefix() . '_intranet_log.rel_id = ' . $this->ci->input->post('rel_id'));
array_push($where, 'AND ' . db_prefix() . "_intranet_log.rel_type = '" . $this->ci->input->post('rel_type') . "'");

//$where   = [];
$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, $additionalSelect);
$output = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];
    $members = explode(',', $aRow['members']);

    $OutputMembers = '';
    if (is_array($members)) {
        foreach ($members as $member) {
            $OutputMembers .=  get_staff_full_name($member) . '</br>';
        }
    }

    $row[] = $OutputMembers;
    $row[] = $aRow['msg'];
    $row[] = _d($aRow['date_created']). ' - ' .get_staff_full_name($aRow['user_created']);

    $output['aaData'][] = $row;
}
