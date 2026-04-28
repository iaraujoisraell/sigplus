<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * 19/12/2022
 * @WannaLuiza
 * INTRANET - Model para GESTÃO ELETRÔNICA DE DOCUMENTOS
 */
class Ged_model extends App_Model {

    public function __construct() {
        parent::__construct();
        $this->empresa_id = $this->session->userdata('empresa_id');
    }
    
    

    /**
     * 19/12/2022
     * @WannaLuiza
     * Retorna todas as categorias da empresa conectada 
     */
    public function get_categorias() {
        $empresa_id = $this->session->userdata('empresa_id');
        $tbl_ro_categorias = 'tbl_intranet_categorias';
        $sql = "SELECT $tbl_ro_categorias.* from $tbl_ro_categorias
        where $tbl_ro_categorias.empresa_id = $empresa_id and $tbl_ro_categorias.rel_type = 'ged' and $tbl_ro_categorias.deleted = 0";

        return $this->db->query($sql)->result_array();
    }

    /**
     * 19/12/2022
     * @WannaLuiza
     * Retorna uma categoria
     */
    public function get_categoria($id = 0) {
        $empresa_id = $this->session->userdata('empresa_id');
        $tbl_categoria = 'tbl_intranet_categorias';
        $sql = "SELECT * from $tbl_categoria
        where $tbl_categoria.empresa_id = $empresa_id and $tbl_categoria.deleted = 0 and $tbl_categoria.id = $id  and $tbl_categoria.rel_type = 'ged' ";

        return $this->db->query($sql)->row();
    }
    /**
     * 19/12/2022
     * @WannaLuiza
     * Retorna os campos de um determinado tipo(pela empresa_conectada) - NECESSÁRIO ID DO TIPO PARA RETORNAR OS CAMPOS DO MESMO 
     */
    public function get_categoria_campos($tipo_id = 0) {
        $empresa_id = $this->session->userdata('empresa_id');
        $tbl_campos = 'tbl_intranet_categorias_campo';
        $sql = "SELECT $tbl_campos.* from $tbl_campos
        where $tbl_campos.empresa_id = $empresa_id and $tbl_campos.deleted = 0 and $tbl_campos.categoria_id = $tipo_id ORDER BY $tbl_campos.ordem asc";

        return $this->db->query($sql)->result_array();
    }
    
    /**
     * 19/10/2022
     * @WannaLuiza
     * Insere um valor para determinado campo de determinada categoria
     */
    public function add_campo_value($data = null) {
       
        $data['rel_type'] = 'ged';
        unset($data['registro_id']);
        if ($this->db->insert("tbl_intranet_categorias_campo_values", $data)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 19/10/2022
     * @WannaLuiza
     * Retorna os indexadores do ged
     */
    public function get_values_doc($id = '') {

        $empresa_id = $this->session->userdata('empresa_id');
        $tbl_values = 'tbl_intranet_categorias_campo_values';
        $tbl_campos = 'tbl_intranet_categorias_campo';

        $sql = "SELECT $tbl_campos.nome as nome_campo, $tbl_campos.id as id_campo, $tbl_campos.type as tipo_campo, $tbl_values.value as value from $tbl_values "
                . "INNER JOIN $tbl_campos ON $tbl_campos.id = $tbl_values.campo_id "
                . "WHERE $tbl_values.empresa_id = $empresa_id AND $tbl_values.deleted = 0 AND $tbl_campos.deleted = 0 AND $tbl_values.rel_id = $id and $tbl_values.rel_type = 'ged' ORDER BY $tbl_campos.ordem asc ";

        //echo $sql; exit;
        return $this->db->query($sql)->result_array();
    }
    
    /**
     * 18/10/2022
     * @WannaLuiza
     * Insere um r.o
     */
    public function add($data = null) {
        if ($data) {
            $data['user_created'] = get_staff_user_id();
            $data['date_created'] = date('Y-m-d h:i:s');
            $data['empresa_id'] = $this->session->userdata('empresa_id');
            if ($this->db->insert("tbl_intranet_ged", $data)) {
                $id_insert = $this->db->insert_id();
                return $id_insert;
            } else {
                return false;
            }
        }
    }
    
    /**
     * 19/10/2022
     * @WannaLuiza
     * Retorna os indexadores do ged
     */
    public function get_options($id = '') {

        $empresa_id = $this->session->userdata('empresa_id');
        $tbl_options = 'tbl_intranet_categorias_campo_options';

        $sql = "SELECT * from $tbl_options "
                . "WHERE $tbl_options.empresa_id = $empresa_id AND  $tbl_options.campo_id = $id  ORDER BY $tbl_options.id asc ";

       // echo $sql; exit;
        return $this->db->query($sql)->result_array();
    }
}
