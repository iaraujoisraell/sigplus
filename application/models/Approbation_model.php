<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * 24/01/2023
 * @WannaLuiza
 * INTRANET - Model para APROVAÇÕES
 */
class Approbation_model extends App_Model {

    public function __construct() {
        parent::__construct();
        $this->empresa_id = $this->session->userdata('empresa_id');
    }

    public function get_approbation_by_id($id = '') {

        $empresa_id = $this->session->userdata('empresa_id');
        $tbl_app = 'tbl_intranet_approbation';

        $sql = "SELECT $tbl_app.* from $tbl_app "
                . "WHERE $tbl_app.empresa_id = $empresa_id AND $tbl_app.deleted = 0 AND $tbl_app.id = $id";

        return $this->db->query($sql)->row();
    }

    public function get_approbations_by_user_id($id = '') {

        $empresa_id = $this->session->userdata('empresa_id');
        $tbl_app = 'tbl_intranet_approbation';
        $staff_id = get_staff_user_id();

        $sql = "SELECT count(*) as total from $tbl_app "
                . "INNER JOIN " . db_prefix() . "departments ON " . db_prefix() . 'departments.departmentid = ' . db_prefix() . '_intranet_approbation.departmentid_staffid '
                . 'INNER JOIN ' . db_prefix() . "staff ON " . db_prefix() . "staff.staffid = " . db_prefix() . "departments.aprovador "
                . "WHERE $tbl_app.empresa_id = $empresa_id AND $tbl_app.deleted = 0 AND $tbl_app.status = 0 AND " . db_prefix() . "staff.staffid = $staff_id";

        $result = $this->db->query($sql)->row();
        return $result->total;
    }

    public function add_approbation($data = []) {

        $data['date_created'] = date('Y-m-d H:i:s');
        $data['user_created'] = get_staff_user_id();
        $data['empresa_id'] = $this->session->userdata('empresa_id');
        if ($data['id']) {
            $this->db->where("id", $data['id']);
            $this->db->update("tbl_intranet_approbation", $data);
            return $data['id'];
        } else {
            $this->db->insert("tbl_intranet_approbation", $data);
            return $this->db->insert_id();
        }


        
    }

}
