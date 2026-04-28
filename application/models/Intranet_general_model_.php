<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Intranet_general_model extends App_Model {

    /**
     * 27/03/2023
     * @WannaLuiza
     * Retorna informações gerais com rel_type and rel_id
     */
    public function __construct() {
        //$this->empresa_id = $this->session->userdata('empresa_id');
        parent::__construct();
    }

    public function get_notes($rel_type = '', $rel_id = '') {
        $this->db->where(db_prefix() . '_intranet_notes.rel_id', $rel_id);
        $this->db->where(db_prefix() . '_intranet_notes.rel_type', $rel_type);
        $this->db->where(db_prefix() . '_intranet_notes.deleted', 0);
        $this->db->order_by('id', 'desc');
        return $this->db->get(db_prefix() . '_intranet_notes')->result_array();
    }

    public function get_files($rel_type = '', $rel_id = '') {
        $this->db->where(db_prefix() . '_intranet_files.rel_id', $rel_id);
        $this->db->where(db_prefix() . '_intranet_files.rel_type', $rel_type);
        $this->db->where(db_prefix() . '_intranet_files.deleted', 0);
        return $this->db->get(db_prefix() . '_intranet_files')->result_array();
    }

    public function add_obs($data = null) {

        if ($data['obs']) {
            //print_r($data); exit;
            if ($this->db->insert("tbl_intranet_obs", $data)) {
                return $this->db->insert_id();
            }
        }
    }

    public function get_obs($id = 0, $rel_type = '') {
        $tbl_obs = 'tbl_intranet_obs';
        $sql = "SELECT $tbl_obs.* FROM $tbl_obs "
                . "WHERE  $tbl_obs.rel_id = $id AND $tbl_obs.rel_type = '$rel_type'  ORDER BY $tbl_obs.id ASC";
        //echo $sql; exit;
        return $this->db->query($sql)->result_array();
    }

    public function get_send($id = 0, $rel_type = '', $staff = 0) {
        $tbl_send = 'tbl_intranet_send';
        $sql = "SELECT $tbl_send.* FROM $tbl_send "
                . "WHERE  $tbl_send.rel_id = $id AND $tbl_send.rel_type = '$rel_type' AND $tbl_send.staff = '$staff'  ORDER BY $tbl_send.id ASC";
        //echo $sql; exit;
        return $this->db->query($sql)->result_array();
    }

    public function add_log($dados = null) {

        $dados['date_created'] = date('Y-m-d H:i:s');
        $dados['user_created'] = get_staff_user_id();
        $dados['ip'] = $_SERVER['REMOTE_ADDR'];
        $dados['empresa_id'] = $this->session->userdata('empresa_id');
        $dados['general'] = true;
        
        //print_r($dados); 

        return $this->db->insert("tbl_intranet_log", $dados);
    }

    public function get_logs($user_created = '') {
        if ($user_created) {
            //echo $user_created; exit;
            $this->db->where(db_prefix() . '_intranet_log.user_created', $user_created);
            $this->db->where(db_prefix() . '_intranet_log.general', true);
        }

        $this->db->where(db_prefix() . '_intranet_log.empresa_id', $this->session->userdata('empresa_id'));
        $this->db->order_by('id', 'desc');
        $logs = $this->db->get(db_prefix() . '_intranet_log')->result_array();
        //echo 'sjjs'; exit;
        return hooks()->apply_filters('get_logs', $logs, ['rel_id' => $user_created]);
    }

    public function update_filters($filters, $rel_type, $tab = '') {

       // echo $rel_type; echo $tab;
        $this->db->where('rel_type', $rel_type);
        $this->db->where('tab', $tab)->or_where('tab', 0);
        $this->db->where('user_created', get_staff_user_id());
        $this->db->delete(db_prefix() . '_intranet_filters');
      

        $this->db->insert_batch(db_prefix() . '_intranet_filters', $filters);
    }
    
    public function get_filters($rel_type) {
      
        $this->db->where('rel_type', $rel_type);
        $this->db->where('user_created', get_staff_user_id());
         $this->db->order_by('tab', 'asc');
        return $this->db->get(db_prefix() . '_intranet_filters')->result_array();
    }
    
    public function add_campo_value($data = null) {
        //print_r($data); exit;
        if ($this->db->insert("tbl_intranet_categorias_campo_values", $data)) {
            return true;
        } else {
            return false;
        }
    }
}
