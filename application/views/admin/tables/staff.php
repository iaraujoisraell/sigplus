<?php

defined('BASEPATH') or exit('No direct script access allowed');

// Defina a cor desejada para a borda
//$color = '#d3d3d3'; // Você pode alterar essa cor dinamicamente conforme necessário

$has_permission_delete = has_permission('staff', '', 'delete');
if (!$has_permission_delete) {
    $has_permission_delete = has_permission_intranet('staff', '', 'delete');
}

$custom_fields = get_custom_fields('staff', [
    'show_on_table' => 1,
]);
$aColumns = [
    db_prefix() . 'staff.email',
    db_prefix() . 'roles.name as funcao',
    'last_login',
    'active',
    'cargo',
    db_prefix() . '_intranet_terceiros.cor as cor',
];
$sIndexColumn = 'staffid';
$sTable = db_prefix() . 'staff';
$join = [
    'LEFT JOIN ' . db_prefix() . 'roles ON ' . db_prefix() . 'roles.roleid = ' . db_prefix() . 'staff.role',
    'LEFT JOIN ' . db_prefix() . '_intranet_terceiros ON ' . db_prefix() . '_intranet_terceiros.id = ' . db_prefix() . 'staff.terceiro_id'
];
$i = 0;
foreach ($custom_fields as $field) {
    $select_as = 'cvalue_' . $i;
    if ($field['type'] == 'date_picker' || $field['type'] == 'date_picker_time') {
        $select_as = 'date_picker_cvalue_' . $i;
    }
    array_push($aColumns, 'ctable_' . $i . '.value as ' . $select_as);
    array_push($join, 'LEFT JOIN ' . db_prefix() . 'customfieldsvalues as ctable_' . $i . ' ON ' . db_prefix() . 'staff.staffid = ctable_' . $i . '.relid AND ctable_' . $i . '.fieldto="' . $field['fieldto'] . '" AND ctable_' . $i . '.fieldid=' . $field['id']);
    $i++;
}
if ($this->ci->input->post('intranet')) {
    $intranet = '?intranet=intranet';
}

$where = [];
// Fix for big queries. Some hosting have max_join_limit

if ($this->ci->input->post('departmentid')) {
    array_push($join, 'INNER JOIN ' . db_prefix() . 'staff_departments ON ' . db_prefix() . 'staff_departments.staffid = ' . db_prefix() . 'staff.staffid');
    array_push($where, ' AND ' . db_prefix() . 'staff_departments.departmentid = ' . $this->ci->input->post('departmentid'));
}

if ($this->ci->input->post('terceiro_id')) {
    array_push($where, ' AND ' . db_prefix() . 'staff.terceiro_id = ' . $this->ci->input->post('terceiro_id'));
}
if (count($custom_fields) > 4) {
    @$this->ci->db->query('SET SQL_BIG_SELECTS=1');
}
$empresa_id = $this->ci->session->userdata('empresa_id');

array_push($where, ' AND ' . db_prefix() . 'staff.deleted = 0');
array_push($where, ' AND ' . db_prefix() . 'staff.empresa_id = ' . $empresa_id);

$full = "CONCAT ( firstname, ' ', lastname ) as full_name";
$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [
    'profile_image',
    $full,
    db_prefix() . 'staff.staffid',
    db_prefix() . 'staff.empresa_id',
], '', '', ' order by tblstaff.firstname asc');

$output = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];

    if ($aRow['cor']) {
        //$this->load->model('Company_model');
        //$row_terceiro = $this->Company_model->get_terceiros($aRow['terceiro_id']);
        $color = $aRow['cor'];
    } else {
        $color = "#d3d3d3";
    }

    //$_data = '<a href="' . admin_url('staff/profile/' . $aRow['staffid'] . $intranet) . '">
    // <div style="border: 3px solid ' . $color . '; border-radius: 50%; display: inline-block;">
    // ' . staff_profile_image($aRow['staffid'], [
    //    'staff-profile-image-small'
    //]) . '
    //  </div>
    // </a>';
    $_data = '<a href="' . admin_url('staff/profile/' . $aRow['staffid'] . $intranet) . '">' . staff_profile_image(
    $aRow['staffid'], 
    ['staff-profile-image-small'], 
    '', 
    [], 
    'border: 3px solid ' . $color . '; border-radius: 50%; display: inline-block;') . '</a>';

    if (has_permission_intranet('staff', '', 'edit') || has_permission('staff', '', 'edit') || is_admin()) {
        $_data .= ' <a href="' . admin_url('staff/member/' . $aRow['staffid'] . $intranet) . '"> ' . $aRow['full_name'] . '</a>';
    } else {
        $_data .= ' <a href="#"> ' . $aRow['full_name'] . '</a>';
    }

    $_data .= '<div class="row-options">';

    if (has_permission_intranet('staff', '', 'edit') || has_permission('staff', '', 'edit') || is_admin()) {
        $_data .= '<a href="' . admin_url('staff/member/' . $aRow['staffid'] . $intranet) . '">' . _l('view') . '</a>';
    }

    if (($has_permission_delete && ($has_permission_delete && !is_admin($aRow['staffid']))) || is_admin()) {
        if ($has_permission_delete && $output['iTotalRecords'] > 1 && $aRow['staffid'] != get_staff_user_id()) {
            $_data .= ' | <a href="#" onclick="delete_staff_member(' . $aRow['staffid'] . '); return false;" class="text-danger">' . _l('delete') . '</a>';
        }
    }

    $_data .= '</div>';
    $row[] = $_data;

    $row[] = '<a href="mailto:' . $aRow['email'] . '">' . $aRow['email'] . '</a>';

    $row[] = $aRow['cargo'];

    $_data = '';
    if ($aRow['last_login'] != null) {
        $_data = '<span class="text-has-action is-date" data-toggle="tooltip" data-title="' . _dt($aRow['last_login']) . '">' . time_ago($aRow['last_login']) . '</span>';
    } else {
        $_data = 'Never';
    }
    $row[] = $_data;

    $_data = '';
    $checked = '';
    if ($aRow['active'] == 1) {
        $checked = 'checked';
    }
    $_data = '<div class="onoffswitch">
                <input type="checkbox" ' . (($aRow['staffid'] == get_staff_user_id() || (is_admin($aRow['staffid']) || !has_permission_intranet('staff', '', 'edit')) && !is_admin()) ? 'disabled' : '') . ' data-switch-url="' . admin_url() . 'staff/change_staff_status" name="onoffswitch" class="onoffswitch-checkbox" id="c_' . $aRow['staffid'] . '" data-id="' . $aRow['staffid'] . '" ' . $checked . '>
                <label class="onoffswitch-label" for="c_' . $aRow['staffid'] . '"></label>
            </div>';

    // For exporting
    $_data .= '<span class="hide">' . ($checked == 'checked' ? _l('is_active_export') : _l('is_not_active_export')) . '</span>';
    $row[] = $_data;

    $row['DT_RowClass'] = 'has-row-options';
    $output['aaData'][] = $row;
}
