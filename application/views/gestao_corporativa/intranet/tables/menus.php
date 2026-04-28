<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [];
$where = [];

$aColumns = array_merge($aColumns, [
    db_prefix() . '_intranet_menus.nome_menu as titulo',
    db_prefix() . '_intranet_menus.urk as url',
    db_prefix() . '_intranet_menus.icon as icon',
    db_prefix() . '_intranet_menus.ordem as ordem',
    db_prefix() . '_intranet_menus.data_cadastro as date',
    db_prefix() . '_intranet_menus.user_cadastro as user',
    db_prefix() . '_intranet_menus.id as a'
        ]);
$sIndexColumn = 'id';
$sTable = db_prefix() . '_intranet_menus';

$join = [];

$additionalSelect = ['id'];
$empresa_id = $this->ci->session->userdata('empresa_id');

array_push($where, 'AND ' . db_prefix() . '_intranet_menus.deleted = 0');
array_push($where, 'AND ' . db_prefix() . '_intranet_menus.menu_pai = 0');
array_push($where, ' AND ' . db_prefix() . '_intranet_menus.empresa_id = ' . $empresa_id);

//$where   = [];
$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, $additionalSelect);
$output = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];

    $row[] = strtoupper($aRow['ordem']);
    $row[] = strtoupper($aRow['titulo']);

    $row[] = $aRow['url'];
    $row[] = date("d/m/Y", strtotime($aRow['date'])) . ' - ' . get_staff_full_name($aRow['user']);
    $row[] = '<a class="btn btn-default btn-icon" data-toggle="modal" data-target="#add_menu' . $aRow['id'] . '" ><i class="fa fa-list"></i> SUBMENUS</a>';

    $option = '';
    $option .= '<a class="btn btn-success btn-xs " style="margin: 2px;" href="' . $aRow['url'] . '" target="_blank"><i class="fa fa-eye"></i></a>';
    if (has_permission_intranet('menus_paginas', '', 'edit') || is_admin()) {
        $option .= '<a class="btn btn-info btn-xs " style="margin: 2px;" data-toggle="tooltip" href="' . base_url('gestao_corporativa/intra/Menus/edit_menu' . '?id=' . $aRow['id']) . '"><i class="fa fa-pencil"></i></a>';
    }
    if (has_permission_intranet('menus_paginas', '', 'delete') || is_admin()) {
        $option .= '<a class="btn btn-danger btn-xs _delete" style="margin: 2px;"data-toggle="tooltip" href="' . base_url('gestao_corporativa/intra/Menus/delete_menu' . '?id=' . $aRow['id']) . '"><i class="fa fa-trash"></i></a>';
    }
    $row[] = $option;
    
    $output['aaData'][] = $row;
}
