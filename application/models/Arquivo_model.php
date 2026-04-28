<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Arquivo_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get lead
     * @param  string $id Optional - leadid
     * @return mixed
     */
    
    public function add($data = null, $sends = null, $id = 0) {
        if ($data) {

            if ($id) {
                $this->db->where('id', $id);
                if ($this->db->update("tbl_intranet_arquivo", $data)) {
                    return $id;
                } else {
                    return false;
                }
            } else {
                if ($this->db->insert("tbl_intranet_arquivo", $data)) {
                    
                    $id_insert = $this->db->insert_id(); 
                    $array['arquivo_id'] = $id_insert;
                    $array['dt_send'] = date('Y-m-d');
                    foreach ($sends as $send):
                        $array['staff_id'] = $send;
                        $array['empresa_id'] = $this->session->userdata('empresa_id');
                        $this->db->insert("tbl_intranet_arquivo_send", $array);
                    endforeach;
                    return $id_insert;
                } else {
                    return false;
                }
            }
        }
    }

    public function send($data = null) {
        if ($data) {
            $array['ci_id'] = $data['id'];
            $array['dt_send'] = date('Y-m-d');
                for ($i = 0; $i < count($data['for_staffs']); $i++) {

                    $array['staff_id'] = $data['for_staffs'][$i];
                    $this->db->insert("tbl_intranet_ci_send", $array) ;
                }
                return $data['id'];
        }
    }

    public function get_arquivo_send($id = 0) {
        $sql = "SELECT * from tbl_intranet_arquivo_send

        where arquivo_id = $id and deleted = 0";
       return $this->db->query($sql);
    
    }
    
    
    public function get($empresa_id = 0) {
        
        $sql = "SELECT * from tbl_intranet_arquivo
        where empresa_id = $empresa_id and deleted = 0";
        
       return $this->db->query($sql);
    
    }
    
    public function get_tipos() {
        $empresa_id = $this->session->userdata('empresa_id');
        
        $sql = "SELECT * from tbl_intranet_arquivo_tipo
        where empresa_id = $empresa_id and deleted = 0";
        
       return $this->db->query($sql)->result_array();
    
    }
    public function get_tipo_campos($tipo_id = 0) {
        $empresa_id = $this->session->userdata('empresa_id');
        
        $sql = "SELECT * from tbl_intranet_arquivo_tipo_campo
        where empresa_id = $empresa_id and deleted = 0 and tipo_id = $tipo_id";
        
       return $this->db->query($sql)->result_array();
    
    }
    
    
    public function get_recebidos($empresa_id = 0, $staff_id = 0) {
        $sql = "SELECT * from tbl_intranet_arquivo_send

        where empresa_id = $empresa_id and deleted = 0 and staff_id = $staff_id limit 6";
       return $this->db->query($sql);
    
    }
    
    public function get_doc($id = 0) {
        $sql = "SELECT * from tbl_intranet_arquivo

        where id = $id";
       return $this->db->query($sql);
    
    }
    
}
