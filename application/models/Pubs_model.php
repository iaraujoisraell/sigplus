<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * 11/01/2023
 * @WannaLuiza
 * INTRANET - Model para Workflow
 */
class Pubs_model extends App_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->empresa_id = $this->session->userdata('empresa_id');
    }

    /**
     * 22/11/2025
     * @RamonSantos
     * Insere um valor para determinado campo de determinada categoria
     */

    public function get_pubs_portal_paciente()
    {
        $result['banner'] = $this->db->query("SELECT foto, link from tbl_intranet_avisos where categoria_id  = 18 and deleted = 0 and tipo = 1")->result_array();
        
        $result['popup'] = $this->db->query("SELECT foto, link from tbl_intranet_avisos where categoria_id  = 18 and deleted = 0 and tipo = 2")->result_array();
        
        $result['noticia'] = $this->db->query("SELECT foto, link from tbl_intranet_avisos where categoria_id  = 18 and deleted = 0 and tipo = 3")->result_array();

        return $result;
    }
}
