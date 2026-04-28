<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [];
$where = [];

$aColumns = array_merge($aColumns, [
    db_prefix() . '_intranet_files.file as titulo',
    db_prefix() . '_intranet_files.url as url',
    db_prefix() . '_intranet_files.date_created as date',
    db_prefix() . '_intranet_files.user_created as user',
    db_prefix() . '_intranet_files.id as a'
        ]);
$sIndexColumn = 'id';
$sTable = db_prefix() . '_intranet_files';

$join = [];

$additionalSelect = ['id'];
$empresa_id = $this->ci->session->userdata('empresa_id');

array_push($where, 'AND ' . db_prefix() . '_intranet_files.deleted = 0');
array_push($where, ' AND ' . db_prefix() . '_intranet_files.empresa_id = ' . $empresa_id);
array_push($where, ' AND ' . db_prefix() . '_intranet_files.rel_type = "' . $this->ci->input->post('rel_type').'"');
array_push($where, ' AND ' . db_prefix() . '_intranet_files.rel_id = ' . $this->ci->input->post('rel_id'));


//$where   = [];
$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, $additionalSelect);
$output = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];

    $row[] = strtoupper($aRow['titulo']);

    

    $option = '';
    $option .= '<a class="btn btn-info btn-xs " style="margin: 2px;" href="' . base_url($aRow['url']) . '" target="_blank"><i class="fa fa-eye"></i></a>';
   
        $option .= '<a class="btn btn-danger btn-xs _delete" style="margin: 2px;"data-toggle="tooltip" href="' . base_url('gestao_corporativa/intra/Menus/delete_menu' . '?id=' . $aRow['id']) . '"><i class="fa fa-trash"></i></a>';

       
    $row[] = $option;
     $row[] = _d($aRow['date']) . ' - ' . get_staff_full_name($aRow['user']);
    $output['aaData'][] = $row;
}
