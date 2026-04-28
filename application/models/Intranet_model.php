<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Intranet_model extends App_Model {

    public function __construct() {
        $this->empresa_id = $this->session->userdata('empresa_id');
        parent::__construct();
    }

    /**
     * Get lead
     * @param  string $id Optional - leadid
     * @return mixed
     */
    public function get_staffs() {
        $table_staff = 'tblstaff';
        $empresa_id = $this->session->userdata('empresa_id');
        $table_department_staff = 'tblstaff_departments';
        $table_department = 'tbldepartments';
        $sql = "SELECT $table_staff.*, $table_department.* FROM $table_staff "
                . "LEFT JOIN $table_department_staff ON $table_department_staff.staffid = $table_staff.staffid "
                . "LEFT JOIN $table_department ON $table_department.departmentid = $table_department_staff.departmentid "
                . "WHERE $table_staff.empresa_id = $empresa_id and $table_staff.deleted = 0";
        // echo $sql; exit;
        return $this->db->query($sql)->result_array();
    }

    public function retorno_staff($texto = '') {
        $table_staff = 'tblstaff';
        $empresa_id = $this->session->userdata('empresa_id');
        $table_department_staff = 'tblstaff_departments';
        $table_department = 'tbldepartments';
        $sql = "SELECT $table_staff.*, $table_department.*, $table_staff.email as email FROM $table_staff "
                . "LEFT JOIN $table_department_staff ON $table_department_staff.staffid = $table_staff.staffid "
                . "LEFT JOIN $table_department ON $table_department.departmentid = $table_department_staff.departmentid "
                . "WHERE $table_staff.empresa_id = $empresa_id and $table_staff.deleted = 0 "
                . "and ($table_staff.firstname like '%$texto%' OR $table_staff.lastname like '%$texto%' or $table_department.name like '%$texto%')  group by $table_staff.staffid";
        //echo $sql; exit;
        return $this->db->query($sql)->result_array();
    }

    public function retorno_departamentos($id = 0) {
        $table_staff = 'tblstaff';
        $empresa_id = $this->session->userdata('empresa_id');
        $table_department_staff = 'tblstaff_departments';
        $table_department = 'tbldepartments';
        $sql = "SELECT $table_staff.*, $table_staff.email as email FROM $table_department_staff "
                . "LEFT JOIN $table_department ON $table_department.departmentid = $table_department_staff.departmentid "
                . "LEFT JOIN $table_staff ON $table_staff.staffid = $table_department_staff.staffid "
                . "WHERE $table_staff.empresa_id = $empresa_id and $table_staff.deleted = 0 and $table_department_staff.departmentid = $id group by $table_staff.staffid";
        //echo $sql; exit;
        return $this->db->query($sql)->result_array();
    }

    public function get_departamentos() {
        $empresa_id = $this->session->userdata('empresa_id');
        $table_department = 'tbldepartments';
        $sql = "SELECT $table_department.* FROM $table_department "
                . "WHERE $table_department.empresa_id = $empresa_id";
        //echo $sql; exit;
        return $this->db->query($sql)->result_array();
    }

    /* Criado por Larissa em 02/09/2022 
     */

    public function get_staffs_projects($staff_id = 0) {
        $table_staff = 'tblstaff';
        $empresa_id = $this->session->userdata('empresa_id');
        $table_projects = 'tblprojects';
        $table_projects_staffs = 'tblproject_members';
        $sql = "SELECT $table_staff.*, $table_projects.*, $table_projects_staffs.* FROM $table_staff "
                . "LEFT JOIN $table_projects_staffs ON $table_projects_staffs.staff_id = $table_staff.staffid "
                . "LEFT JOIN $table_projects ON $table_projects.id = $table_projects_staffs.project_id "
                . "WHERE $table_staff.empresa_id = $empresa_id and $table_staff.deleted = 0 and $table_staff.staffid = $staff_id";
        //echo $sql; exit;

        return $this->db->query($sql)->result_array();
    }

    public function get_staff($staff_id = 0) {
        $table_staff = 'tblstaff';
        $empresa_id = $this->session->userdata('empresa_id');
        $table_department_staff = 'tblstaff_departments';
        $table_department = 'tbldepartments';
        $table_empresas = 'tblempresas';
        $sql = "SELECT $table_staff.*, $table_department.*, $table_department.name as setor, $table_empresas.company as empresa, $table_staff.email as email FROM $table_staff "
                . "LEFT JOIN $table_department_staff ON $table_department_staff.staffid = $table_staff.staffid "
                . "LEFT JOIN $table_department ON $table_department.departmentid = $table_department_staff.departmentid "
                . "LEFT JOIN $table_empresas ON $table_empresas.id = $table_staff.empresa_id "
                . "WHERE $table_staff.empresa_id = $empresa_id and $table_staff.deleted = 0 and $table_staff.staffid = $staff_id";
        //echo $sql; exit;

        return $this->db->query($sql)->row();
    }

    public function update_perfil($data = null, $staffid = 0) {


        $this->db->where('staffid', $staffid);

        //print_r($data); exit;
        if ($this->db->update("tblstaff", $data)) {
            return $staffid;
        } else {
            echo 'deu errado';
            exit;
            return false;
        }
    }

    public function get_bday() {
        $empresa_id = $this->session->userdata('empresa_id');
        $hoje =  date('m-d', strtotime('monday this week'));
        $data_especifica =  date('Y-m-d', strtotime('monday this week'));
// Calcula a data de uma semana a partir de hoje
        $uma_semana_depois = date('m-d', strtotime("$data_especifica +7 days"));

        $sql = "SELECT * FROM tblstaff 
        WHERE empresa_id = $empresa_id 
        AND DATE_FORMAT(data_nascimento, '%m-%d') BETWEEN '$hoje' AND '$uma_semana_depois' 
        AND deleted = 0 
        AND active = 1
        ORDER BY DATE_FORMAT(data_nascimento, '%m-%d')";
//echo $sql; exit;
        return $this->db->query($sql);
    }

    public function get_staff_all() {
        $empresa_id = $this->session->userdata('empresa_id');
        $sql = "SELECT s.*, d.name from tblstaff s "
                . "LEFT JOIN tblstaff_departments sd on sd.staffid = s.staffid "
                . "LEFT JOIN tbldepartments d on d.departmentid = sd.departmentid "
                . "where s.deleted = 0 and s.empresa_id = $empresa_id and s.active = 1 "
                . "ORDER BY s.firstname asc";
        //echo $sql; exit;
        return $this->db->query($sql);
    }

    public function get_one($id) {
        $empresa_id = $this->session->userdata('empresa_id');
        $sql = "SELECT s.*, d.name, d.departmentid from tblstaff s "
                . "LEFT JOIN tblstaff_departments sd on sd.staffid = s.staffid "
                . "LEFT JOIN tbldepartments d on d.departmentid = sd.departmentid "
                . "where s.staffid = $id and s.empresa_id = $empresa_id and s.deleted = 0";
        //echo $sql; exit;
        return $this->db->query($sql);
    }

    public function get_not($ci_id) {
        $empresa_id = $this->session->userdata('empresa_id');
        $user = get_staff_user_id();
        $sql = "SELECT * FROM tblstaff where staffid not in (select staff_id from tbl_intranet_ci_send where ci_id = $ci_id) and staffid != $user and empresa_id = $empresa_id and deleted = 0";

        return $this->db->query($sql);
    }

    public function get_departamentos_staffs_selecionados() {
        $sql = "SELECT sd.staffdepartmentid, s.staffid, s.firstname, s.lastname, d.departmentid, d.name as name, sd.staffdepartmentid as origem from tblstaff_departments sd
              left join tblstaff s on s.staffid = sd.staffid
              left join tbldepartments d on d.departmentid = sd.departmentid
              where s.deleted = 0 and s.active = 1 and s.empresa_id = $this->empresa_id and d.empresa_id = $this->empresa_id and d.deleted = 0 
              union
              SELECT gs.id as grupo_id, s.staffid, s.firstname, s.lastname, g.id, g.nome as name, gs.origem from tbl_intranet_grupo_staff gs
              left join tblstaff s on s.staffid = gs.staff_id
              left join tbl_intranet_grupo g on g.id = gs.grupo_id
              where s.deleted = 0 and s.active = 1 and s.empresa_id = $this->empresa_id and g.deleted = 0 and g.empresa_id = $this->empresa_id ORDER BY name, firstname asc";
//       / echo $sql; exit;
        return $this->db->query($sql)->result_array();
    }

    /**
     * 20/10/2022
     * @WannaLuiza
     * Retorna um possivel documento pendente de aprovacao para a home
     */
    public function doucmento_pendente_aprovacao() {
        $user = get_staff_user_id();
        $empresa_id = $this->session->userdata('empresa_id');

        $sql = "SELECT d.* FROM tbl_intranet_documento d
        INNER JOIN tbl_intranet_documento_aprovacao a ON a.doc_id = d.id
        WHERE a.staff_id = $user AND d.deleted = 0 AND d.publicado = 0
        AND d.empresa_id = $empresa_id AND a.deleted = 0 AND a.status = 0
            
        AND a.id = (SELECT ap.id
        FROM tbl_intranet_documento dp
        LEFT JOIN tbl_intranet_documento_aprovacao  ap ON ap.doc_id = dp.id
        WHERE dp.id = d.id
        AND dp.deleted = 0 AND dp.empresa_id = $empresa_id AND ap.deleted = 0 AND ap.status = 0
        ORDER BY ap.fluxo_sequencia ASC Limit 1)
        
        GROUP BY a.doc_id
        ORDER BY a.fluxo_sequencia ASC";

        return $this->db->query($sql)->result_array();
    }

    public function get_notification() {
        $user = get_staff_user_id();

        $empresa_id = $this->session->userdata('empresa_id');

        $this->db->select(db_prefix() . 'notifications.*, tblstaff.firstname, tblstaff.lastname');

        $this->db->from(db_prefix() . 'notifications');
        $this->db->join(db_prefix() . 'staff', db_prefix() . 'staff.staffid = ' . db_prefix() . 'notifications.fromuserid', 'left');
        $this->db->where(db_prefix() . 'notifications.touserid', $user);
        $this->db->where(db_prefix() . 'staff.empresa_id', $empresa_id);
        $this->db->where(db_prefix() . 'staff.deleted', 0);
        $this->db->order_by(db_prefix() . 'notifications.date', 'desc');
        return $this->db->get()->result_array();
    }

}
