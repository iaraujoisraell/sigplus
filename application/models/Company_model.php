<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Company_model extends App_Model {

    /**
     * 27/03/2023
     * @WannaLuiza
     * Retorna informações sobre a empresa. Em tblptions ou em tblempresas
     */
    public function __construct() {
        //$this->empresa_id = $this->session->userdata('empresa_id');
        parent::__construct();
    }

    public function get_company($saas = '') {
        //echo $saas; exit;
        $this->db->select(db_prefix() . 'empresas.*, ' . db_prefix() . '_intranet_login.*');

        $this->db->from(db_prefix() . 'empresas');
        $this->db->join(db_prefix() . '_intranet_login', '' . db_prefix() . '_intranet_login.empresa_id = ' . db_prefix() . 'empresas.id', 'left');
        if($saas){
            $this->db->where(db_prefix() . 'empresas.saas_tenant_id', $saas);
        }
        
        $this->db->where(db_prefix() . 'empresas.deleted', 0);
        return $this->db->get()->row();
    }

    public function save_client_self($key = '', $result = true, $desc = '') {
        //echo $saas; exit;
        $this->db->select(db_prefix() . '_intranet_categorias.*');

        $this->db->from(db_prefix() . '_intranet_categorias');
        $this->db->where(db_prefix() . '_intranet_categorias.linked_to', $key);
        $this->db->where(db_prefix() . '_intranet_categorias.deleted', 0);
        $cats = $this->db->get()->result_array();

        $data = array(
            "rel_type" => "ra",
            "empresa_id" => $this->session->userdata('empresa_id'),
            "rel_id" => $this->session->userdata('atendimento_id'),
            "user_created" => get_client_user_id(),
            "date_created" => date('Y-m-d H:i:s'),
            "description" => $desc,
            "success" => $result
        );

        foreach ($cats as $categoria) {

            $data['categoria_id'] = $categoria['id'];
            //print_r($data); exit;
            $this->db->insert(db_prefix() . '_intranet_client_self', $data);
        }

        //print_r($data); exit;
    }

    public function get_ticket_date() {
        //echo 'skks'; exit;
        //echo 'select * from '.db_prefix() . '_intranet_portal_tickets where empresa_id = '.$this->session->userdata('empresa_id').' and deleted = 0 and active = 1'; exit;
        $dates = $this->db->query('select * from ' . db_prefix() . '_intranet_portal_tickets where empresa_id = ' . $this->session->userdata('empresa_id') . ' and deleted = 0 and active = 1')->result_array();
        //print_r($dates); exit;
        $array = [];
        foreach ($dates as $date) {
            array_push($array, $date['date_open']);
        }
        //print_r($array); exit;
        return $array;
    }

    public function get_cancel_workflow($id = '') {

        if (is_numeric($id)) {
            $this->db->where(db_prefix() . '_intranet_workflow_cancel.id', $id);
            return $this->db->get(db_prefix() . '_intranet_workflow_cancel')->row();
        } else {
            $this->db->where(db_prefix() . '_intranet_workflow_cancel.deleted', 0);
            $this->db->where(db_prefix() . '_intranet_workflow_cancel.active', 1);
            $this->db->where(db_prefix() . '_intranet_workflow_cancel.empresa_id', $this->session->userdata('empresa_id'));
            return $this->db->get(db_prefix() . '_intranet_workflow_cancel')->result_array();
        }
    }

    public function get_list($id = '') {

        if (is_numeric($id)) {
            $this->db->where(db_prefix() . '_intranet_lists.id', $id);
            return $this->db->get(db_prefix() . '_intranet_lists')->row();
        } else {
            $this->db->where(db_prefix() . '_intranet_lists.deleted', 0);
            $this->db->where(db_prefix() . '_intranet_lists.empresa_id', $this->session->userdata('empresa_id'));
            return $this->db->get(db_prefix() . '_intranet_lists')->result_array();
        }
    }

    public function get_list_option($id, $op_id = '') {

        if (is_numeric($op_id)) {
            $sql = "select * from tbl_intranet_list_options where deleted = 0 and id = '$op_id'";
           
            return $this->db->query($sql)->row();
        } elseif(is_numeric($id)){
            $sql = "select * from tbl_intranet_list_options where deleted = 0 and list_id = '$id'";
            //echo $sql; exit;
            return $this->db->query($sql)->result_array();
        }
    }

    public function get_mac($id = '') {

        if (is_numeric($id)) {
            $this->db->where(db_prefix() . '_intranet_macs.id', $id);
            return $this->db->get(db_prefix() . '_intranet_macs')->row();
        } else {
            $this->db->where(db_prefix() . '_intranet_macs.deleted', 0);
            $this->db->where(db_prefix() . '_intranet_macs.active', 1);
            $this->db->where(db_prefix() . '_intranet_macs.empresa_id', $this->session->userdata('empresa_id'));
            return $this->db->get(db_prefix() . '_intranet_macs')->result_array();
        }
    }

    public function get_pdf_view($rel_type = '', $rel_id = '') {
        $this->db->where(db_prefix() . '_intranet_pdf_view.rel_id',$rel_id);
        $this->db->where(db_prefix() . '_intranet_pdf_view.rel_type', $rel_type);
        return $this->db->get(db_prefix() . '_intranet_pdf_view')->result_array();
    }
    
    public function add_pdf_view($rel_type = '', $rel_id = '') {
        $data['user_created'] = get_staff_user_id();
        $data['date_created'] = date('Y-m-d H:i:s');
        $data['rel_type'] = $rel_type;
        $data['rel_id'] = $rel_id;
        $this->db->insert(db_prefix() . '_intranet_pdf_view', $data);
    }

    public function add_terceiros($data, $id)
    {
        $this->db->where('id', $id);
        $data['empresa_id'] = $this->session->userdata('empresa_id');
        if ($this->db->insert(db_prefix() . '_intranet_terceiros', $data)) {
            return $this->db->insert_id();
        } else {
            return false;
        }
    }
    public function get_terceiros($id = '')
    {
        $this->db->where(db_prefix() . '_intranet_terceiros.deleted', 0);
        $this->db->where(db_prefix() . '_intranet_terceiros.empresa_id', $this->session->userdata('empresa_id'));
        if (is_numeric($id)) {
            $this->db->where(db_prefix() . '_intranet_terceiros.id', $id);
            return $this->db->get(db_prefix() . '_intranet_terceiros')->row();
        } else {
            return $this->db->get(db_prefix() . '_intranet_terceiros')->result_array();
        }
    }

    public function update_terceiro($id, $data)
    {
        // print_r($data); exit;
        $this->db->where('id', $id);
        if ($this->db->update(db_prefix() . '_intranet_terceiros', $data)) {
            return true;
        } else {
            return false;
        }
    }
    public function delete_terceiro($id)
    {
        $row['deleted'] = 1;
        $this->db->where('id', $id);
        if ($this->db->update(db_prefix() . '_intranet_terceiros', $row)) {
            return true;
        } else {
            return false;
        }
    }

}
