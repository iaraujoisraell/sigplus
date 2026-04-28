<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Gerador_arquivos_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_tabelas(){

        $sql = "SELECT * from tbl_intranet_gerador_arquivos_tabelas where deleted = 0";

        return $this->db->query($sql)->result_array();
    }

    public function get_tabela($id_tabela){
        $sql = "SELECT * from tbl_intranet_gerador_arquivos_tabelas where id = $id_tabela"; //echo $sql; exit;

        return $this->db->query($sql)->row_array();

    }
    
}
