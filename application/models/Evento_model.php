<?php

defined('BASEPATH') or exit('No direct script access allowed');

    /**
     * @Wanna Luiza
     * 12/08/2022
     * Model - Eventos->Intranet
     */

    class Evento_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
    }
    
    public function get_datas($empresa_id = 0) {
        $sql = "SELECT * from tbl_intranet_eventos 
        where empresa_id = $empresa_id and deleted = 0";
        return $this->db->query($sql);
    }
    
    public function get_datas_my($empresa_id = 0) {
        $id = get_staff_user_id();
        $sql = "SELECT * from tbl_intranet_eventos 
        where empresa_id = $empresa_id and deleted = 0 and user_create = $id";
        return $this->db->query($sql);
    }
}
