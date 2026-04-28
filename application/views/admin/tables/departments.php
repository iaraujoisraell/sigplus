<?php

defined('BASEPATH') or exit('No direct script access allowed');
$where = [];

$aColumns = [
    'departmentid',
    'abreviado',
    'name',
    'email',
    'calendar_id',
];
$sIndexColumn = 'departmentid';
$sTable = db_prefix() . 'departments';

$empresa_id = $this->ci->session->userdata('empresa_id');
if ($this->ci->input->post('intranet')) {
    $intranet = '?intranet=intranet';
}

array_push($where, 'AND ' . db_prefix() . 'departments.deleted = 0');
array_push($where, ' AND ' . db_prefix() . 'departments.empresa_id = ' . $empresa_id);

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, [], $where, ['email', 'hidefromclient', 'host', 'encryption', 'password', 'abreviado', 'delete_after_import', 'imap_username', 'gestor_staff', 'setor_pai', 'aprovador']);
$output = $result['output'];
$rResult = $result['rResult'];
foreach ($rResult as $aRow) {
    $row = [];
    for ($i = 0; $i < count($aColumns); $i++) {
        $_data = $aRow[$aColumns[$i]];
        $ps = '';
        if (!empty($aRow['password'])) {
            $ps = $this->ci->encryption->decrypt($aRow['password']);
        }
        if ($aColumns[$i] == 'name') {
            $caminho = '';
            if (has_permission_intranet('departments', '', 'edit') or is_admin()) { 
                $caminho = 'onclick="edit_department(this,' . $aRow['departmentid'] . '); return false" data-name="' . $aRow['name'] . '" data-calendar-id="' . $aRow['calendar_id'] . '" data-email="' . $aRow['email'] . '" data-hide-from-client="' . $aRow['hidefromclient'] . '" data-host="' . $aRow['host'] . '" data-password="' . $ps . '" data-imap_username="' . $aRow['imap_username'] . '" data-encryption="' . $aRow['encryption'] . '" data-delete-after-import="' . $aRow['delete_after_import'] . '" data-abreviado="' . $aRow['abreviado'] . '" data-aprovador="' . $aRow['aprovador'] . '" data-setor_pai="' . $aRow['setor_pai'] . '" data-gestor_staff="' . $aRow['gestor_staff'] . '"';
            }
            $_data = '<a href="#" '.$caminho.'>' . $_data . '</a>';
        }
        $row[] = $_data;
    }
    $this->ci->load->model('Departments_model');
    $staffs = $this->ci->Departments_model->get_department_staffs($aRow['departmentid']);

    $options = '';
    $disabled = '';
    $msg = '';

    if (count($staffs) > 0) {
        $disabled = 'disabled';
        $caminho = '#';
        $msg = 'data-title="Departamento não pode ser deletado."';
    } else {
        $caminho = base_url().'admin/departments/delete/' . $aRow['departmentid'] . $intranet;
    }
    $options = '<a class="btn btn-info btn-icon" onclick="Colaboradores(' . "'" . $aRow['departmentid'] . "'" . ')"> ' . count($staffs) . ' <i class="fa fa-users"></i></a>';

    $options .= '<a class="btn btn-warning btn-icon" onclick="Log(' . "'" . $aRow['departmentid'] . "'" . ')"><i class="fa fa-list"></i></a>';

    if (is_admin() or has_permission_intranet('departments', '', 'edit')) { 
    $options .= icon_btn('departments/department/' . $aRow['departmentid'], 'pencil-square-o', 'btn-default', [
        'onclick' => 'edit_department(this,' . $aRow['departmentid'] . '); return false', 'data-name' => $aRow['name'], 'data-calendar-id' => $aRow['calendar_id'], 'data-email' => $aRow['email'], 'data-hide-from-client' => $aRow['hidefromclient'], 'data-host' => $aRow['host'], 'data-password' => $ps, 'data-encryption' => $aRow['encryption'], 'data-imap_username' => $aRow['imap_username'], 'data-delete-after-import' => $aRow['delete_after_import'], 'data-abreviado' => $aRow['abreviado'], 'data-aprovador' => $aRow['aprovador'], 'data-setor_pai' => $aRow['setor_pai'], 'data-gestor_staff' => $aRow['gestor_staff'],
    ]);
    }

    if (is_admin() or has_permission_intranet('departments', '', 'edit')) { 
    $options .= '<a data-toggle="tooltip" ' .$msg. ' class="btn btn-danger btn-icon remove" href="'. $caminho .'" '.$disabled.'><i class="fa fa-times"></i></a>';
    }

    $row[] = $options;

    $output['aaData'][] = $row;
}
