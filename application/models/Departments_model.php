<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Departments_model extends App_Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     * @param  integer ID (optional)
     * @param  boolean (optional)
     * @return mixed
     * Get department object based on passed id if not passed id return array of all departments
     * Second parameter is to check if the request is coming from clientarea, so if any departments are hidden from client to exclude
     */
    public function get($id = '', $clientarea = '') {

        $empresa_id = $this->session->userdata('empresa_id');
        $this->db->where('empresa_id', $empresa_id);
        $this->db->where('deleted', '0');
        $this->db->order_by('name', 'asc');
        if ($clientarea == true) {
            $this->db->where('hidefromclient', 0);
        }
        if (is_numeric($id)) {

            $this->db->where('departmentid', $id);

            return $this->db->get(db_prefix() . 'departments')->row();
        }

        $departments = $this->app_object_cache->get('departments');

        if (!$departments && !is_array($departments)) {
            $this->db->where('empresa_id', $empresa_id);
            $departments = $this->db->get(db_prefix() . 'departments')->result_array();

            $this->app_object_cache->add('departments', $departments);
        }

        return $departments;
    }

    public function get_novos_setores() {
        $sql = "SELECT * FROM tmp_setores where sigla != ''";
        //echo $sql; exit;

        $result = $this->db->query($sql)->result_array();
        foreach ($result as $aRow) {
            $setor = $aRow['setor'];
            $sigla = $aRow['sigla'];
            $descricao = $aRow['descricao'];
            //echo $setor .'<br>';
            //echo $sigla.'<br>';
            //echo $descricao ;

            $data['name'] = $descricao;
            $data['abreviado'] = $sigla;
            $data['empresa_id'] = 4;
            $data['deleted'] = 0;
            $this->db->insert('tbldepartments', $data);
        }
        echo 'ok';
    }

    /**
     * @param array $_POST data
     * @return integer
     * Add new department
     */
    public function add($data) {
        if (isset($data['hidefromclient'])) {
            $data['hidefromclient'] = 1;
        } else {
            $data['hidefromclient'] = 0;
        }

        if (!empty($data['password'])) {
            $data['password'] = $this->encryption->encrypt($data['password']);
        }

        if (!isset($data['encryption'])) {
            $data['encryption'] = '';
        }

        if (!isset($data['delete_after_import'])) {
            $data['delete_after_import'] = 0;
        } else {
            $data['delete_after_import'] = 1;
        }

        $empresa_id = $this->session->userdata('empresa_id');
        $data['empresa_id'] = $empresa_id;

        $data = hooks()->apply_filters('before_department_added', $data);
        $this->db->insert(db_prefix() . 'departments', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            hooks()->do_action('after_department_added', $insert_id);
            log_activity('New Department Added [' . $data['name'] . ', ID: ' . $insert_id . ']');
        }

        return $insert_id;
    }

    /**
     * @param  array $_POST data
     * @param  integer ID
     * @return boolean
     * Update department to database
     */
    public function update($data, $id) {
        $dep_original = $this->get($id);
        if (!$dep_original) {
            return false;
        }


        if (!isset($data['encryption'])) {
            $data['encryption'] = '';
        }

        if (!isset($data['delete_after_import'])) {
            $data['delete_after_import'] = 0;
        } else {
            $data['delete_after_import'] = 1;
        }

        if ($data['email'] == '') {
            $data['email'] = null;
        }
        if (isset($data['hidefromclient'])) {
            $data['hidefromclient'] = 1;
        } else {
            $data['hidefromclient'] = 0;
        }
        // Check if not empty $data['password']
        // Get original
        // Decrypt original
        // Compare with $data['password']
        // If equal unset
        // If not encrypt and save
        if (!empty($data['password'])) {
            $or_decrypted = $this->encryption->decrypt($dep_original->password);
            if ($or_decrypted == $data['password']) {
                unset($data['password']);
            } else {
                $data['password'] = $this->encryption->encrypt($data['password']);
            }
        }

        $data = hooks()->apply_filters('before_department_updated', $data, $id);

        $this->db->where('departmentid', $id);
        $this->db->update(db_prefix() . 'departments', $data);
        if ($this->db->affected_rows() > 0) {
            log_activity('Department Updated [Name: ' . $data['name'] . ', ID: ' . $id . ']');

            return true;
        }

        return false;
    }

    /**
     * @param  integer ID
     * @return mixed
     * Delete department from database, if used return array with key referenced
     */
    public function delete($id) {
        $current = $this->get($id);

        if (is_reference_in_table('department', db_prefix() . 'tickets', $id)) {
            return [
                'referenced' => true,
            ];
        }

        hooks()->do_action('before_delete_department', $id);
        $data['deleted'] = 1;
        $this->db->where('departmentid', $id);
        $this->db->update(db_prefix() . 'departments', $data);

        //$this->db->delete(db_prefix() . 'departments');
        if ($this->db->affected_rows() > 0) {
            log_activity('Department Deleted [ID: ' . $id . ']');

            return true;
        }

        return false;
    }

    /**
     * @param  integer ID (option)
     * @param  boolean (optional)
     * @return mixed
     * Get departments where staff belongs
     * If $onlyids passed return only departmentsID (simple array) if not returns array of all departments
     */
    public function get_staff_departments($userid = false, $onlyids = false) {
        $empresa_id = $this->session->userdata('empresa_id');
        if ($userid == false) {
            $userid = get_staff_user_id();
        }
        if ($onlyids == false) {
            $this->db->select();
        } else {
            $this->db->select(db_prefix() . 'staff_departments.departmentid');
        }
        $this->db->from(db_prefix() . 'staff_departments');
        $this->db->join(db_prefix() . 'departments', db_prefix() . 'staff_departments.departmentid = ' . db_prefix() . 'departments.departmentid', 'left');
        $this->db->where('staffid', $userid);
        $this->db->where('tbldepartments.empresa_id', $empresa_id);
        $this->db->where('deleted', 0);
        //$this->db->group_by('staffid');
        $departments = $this->db->get()->result_array();
        if ($onlyids == true) {
            $departmentsid = [];
            foreach ($departments as $department) {
                array_push($departmentsid, $department['departmentid']);
            }

            return $departmentsid;
        }

        return $departments;
    }

    public function get_department_staffs($departmentid = '') {
        $empresa_id = $this->session->userdata('empresa_id');

        $this->db->select(db_prefix() . 'staff.*');

        $this->db->from(db_prefix() . 'staff');
        $this->db->join(db_prefix() . 'staff_departments', db_prefix() . 'staff_departments.staffid = ' . db_prefix() . 'staff.staffid', 'inner');
        if(is_array($departmentid)){
            $departmentid = implode(',', $departmentid);
            $this->db->where(db_prefix() . 'staff_departments.departmentid in('. $departmentid.')');
            $this->db->group_by(db_prefix() . 'staff.staffid');
        } else {
            $this->db->where(db_prefix() . 'staff_departments.departmentid', $departmentid);
        }
        
        $this->db->where(db_prefix() . 'staff.empresa_id', $empresa_id);
        $this->db->where(db_prefix() . 'staff.deleted', 0);
        //$this->db->group_by(db_prefix() . 'staff.taffid');
        $staffs = $this->db->get()->result_array();

        return $staffs;
    }

    public function insert_log_change($staffid, $departmentid, $action) {
        $empresa_id = $this->session->userdata('empresa_id');
        $this->db->insert(db_prefix() . 'departments_log', [
            'staffid' => $staffid,
            'departmentid' => $departmentid,
            'action' => $action,
            'user_created' => get_staff_user_id(),
            'date_created' => date('Y-m-d h:i:s'),
            'empresa_id' => $empresa_id,
            'ip' => $_SERVER['REMOTE_ADDR'],
        ]);
    }

}
