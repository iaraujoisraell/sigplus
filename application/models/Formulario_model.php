<?php

defined('BASEPATH') or exit('No direct script access allowed');

 /**
 * 07/09/2022(Não sei quANDo criei, mas essa foi a data que botei o comentário)
 * @WannaLuiza
 * Intranet - Model para gestão de documentos
 */
class Formulario_model extends App_Model {

    
    public function _construct() {
        $empresa_id = $this->session->userdata('empresa_id'); // Empresa do usuário conectado
        $staffid = get_staff_user_id(); //Usuário conectado
        //$this->load->vars(['variavel_global' => 'variabel global']);
        parent::__construct();
    }

    /**
     * 18/10/2022
     * @Israel Araujo
     * Retorna os formularios de um usuario
     */
    public function get_formulario_by_user_id() {

        $staff_id = get_staff_user_id();
        $empresa_id = $this->session->userdata('empresa_id');
        $sql = "SELECT f.*, s.firstname, s.lastname FROM 
                tbl_intranet_formularios f
                inner join tblstaff s on s.staffid = f.user_created
                WHERE f.deleted = 0 AND f.empresa_id = $empresa_id and f.user_created = $staff_id and form_pai = 0 "
                . " order by f.data_created desc";
        //echo $sql; exit;
        return $this->db->query($sql)->result();
    }
    
    /**
     * 08/11/2022
     * @Israel Araujo
     * Retorna as respostas dos formularios de um usuario
     */
    public function get_total_resposta_formulario_by_key($form_key = '') {
          $sql = "SELECT count(distinct(hash)) as total
                FROM tbl_intranet_formularios_respostas
                WHERE form_key = '$form_key'";
       // echo $sql; exit;
        return $this->db->query($sql)->row();
    }
    
    /**
     * 15/10/2022
     * @Israel Araujo
     * Retorna o formulario selecionado.
     */
    public function get_formulario_by_id($form_id = null) {

        //$staff_id = get_staff_user_id();
        $empresa_id = $this->session->userdata('empresa_id');
        $sql = "SELECT * FROM 
                tbl_intranet_formularios 
                WHERE id = $form_id AND deleted = 0 AND  empresa_id = $empresa_id";
        //echo $sql; exit;
        return $this->db->query($sql)->row();
    }
    
    public function get_form_pai($form_key = null) {

        //$staff_id = get_staff_user_id();
       $empresa_id = $this->session->userdata('empresa_id');
        $sql = "SELECT * FROM 
                tbl_intranet_formularios 
                WHERE form_key = '$form_key' AND deleted = 0 AND  empresa_id = $empresa_id and form_pai = 0";
        //echo $sql; exit;
        return $this->db->query($sql)->row();
    }
    
    public function get_form_pai_externo($form_key = null) {

        //$staff_id = get_staff_user_id();
       
        $sql = "SELECT * FROM 
                tbl_intranet_formularios 
                WHERE form_key = '$form_key' and form_pai = 0";
       // echo $sql; exit;
        return $this->db->query($sql)->row();
    }
    
    public function get_form_externo_by_hash($form_key = null) {

        //$staff_id = get_staff_user_id();
       
        $sql = "SELECT * FROM 
                tbl_intranet_formularios 
                WHERE form_key = '$form_key'";
        //echo $sql; exit;
        return $this->db->query($sql)->row();
    }
    
    public function get_form_externo_by_id($form_id = null) {

        //$staff_id = get_staff_user_id();
       
        $sql = "SELECT * FROM 
                tbl_intranet_formularios 
                WHERE id = '$form_id'";
        //echo $sql; exit;
        return $this->db->query($sql)->row();
    }
    
    public function get_resposta_sessao_hash($hash = null, $sessao = null) {

        //$staff_id = get_staff_user_id();
       
        $sql = "SELECT * FROM 
                tbl_intranet_formularios_respostas 
                WHERE hash = '$hash' and sessao = '$sessao'";
        // echo $sql; exit;
        return $this->db->query($sql)->result_array();
    }
    
    /*
     * retorna as respostas finais de um form para um hash
     */
    public function get_resposta_key_hash($hash = null, $key = null) {

        //$staff_id = get_staff_user_id();
       
        $sql = "SELECT * FROM 
                tbl_intranet_formularios_respostas 
                WHERE hash = '$hash' and form_key = '$key'";
        // echo $sql; exit;
        return $this->db->query($sql)->result_array();
    }
    
    /*
     * 07/11/22
     * retorna todas as respostas temporárias de um hash
     */
    public function get_resposta_temporarias_by_hash($hash = null) {

        //$staff_id = get_staff_user_id();
       
        $sql = "SELECT * FROM 
                tbl_intranet_formularios_respostas_temp 
                WHERE hash = '$hash' ";
        // echo $sql; exit;
        return $this->db->query($sql)->result_array();
    }
    
    
    public function get_ultima_sessao_hash($hash = null) {

        //$staff_id = get_staff_user_id();
       
        $sql = "SELECT distinct max(sessao) as sessao FROM 
                tbl_intranet_formularios_respostas_temp 
                WHERE hash = '$hash' ";
       // echo $sql; exit;
        return $this->db->query($sql)->row();
    }
    
    public function get_form_filhos($form_pai = null) {

        //$staff_id = get_staff_user_id();
       $empresa_id = $this->session->userdata('empresa_id');
        $sql = "SELECT * FROM 
                tbl_intranet_formularios 
                WHERE form_pai = '$form_pai' AND deleted = 0  ";
        //echo $sql; exit;
        return $this->db->query($sql)->result_array();
    }
    
     public function get_form_filhos_sessao_externo($form_pai = null, $sessao = null) {

        //$staff_id = get_staff_user_id();
        $sql = "SELECT * FROM 
                tbl_intranet_formularios 
                WHERE form_pai = '$form_pai' AND deleted = 0 and sessao = $sessao ";
        //echo $sql; exit;
        return $this->db->query($sql)->row();
    }
    
    public function get_option_logo($empresa_id = null) {

        //$staff_id = get_staff_user_id();
       
        $sql = "SELECT * FROM tbloptions where name = 'company_logo' and empresa_id = $empresa_id";
        //echo $sql; exit;
        return $this->db->query($sql)->row();
    }
    
    public function get_option_nome_empresa($empresa_id = null) {

        //$staff_id = get_staff_user_id();
       
        $sql = "SELECT * FROM tbloptions where name = 'companyname' and empresa_id = $empresa_id";
        //echo $sql; exit;
        return $this->db->query($sql)->row();
    }
    
    public function get_form_perguntas($form_key = null) {

        //$staff_id = get_staff_user_id();
       
        $sql = "SELECT * FROM 
                tbl_intranet_formularios_perguntas 
                WHERE form_id = '$form_key' AND deleted = 0";
        //echo $sql; exit;
        return $this->db->query($sql)->result();
    }
    
    /**
     * 15/10/2022
     * @Israel Araujo
     * Retorna o formulario selecionado.
     */
    public function get_perguntas_formulario_by_formid($form_id = null) {

        //$staff_id = get_staff_user_id();
        $empresa_id = $this->session->userdata('empresa_id');
        $sql = "SELECT * FROM 
                tbl_intranet_formularios_perguntas 
                WHERE form_id = $form_id AND deleted = 0 AND  empresa_id = $empresa_id";
        //echo $sql; exit;
        return $this->db->query($sql)->result_array();
    }

    /**
     * 07/09/2022(Não sei quANDo criei, mas essa foi a data que botei o comentário)
     * @WannaLuiza
     * Cadastra/edita o documento
     */
    public function add($data = null) {
        //print_r($data); exit;
        if ($this->db->insert("tbl_intranet_formularios", $data)) {
            $id_insert = $this->db->insert_id();

            return $id_insert;
        } else {
            return false;
        }
    }
    
    public function atualiza_form($data = null, $id = '') {
        if ($id) {                
                $this->db->where('id', $id);
                if ($this->db->update("tbl_intranet_formularios", $data)) {
                    return true;
                } else {
                    return false;
                }
            }
    }
    
    /**
     * 07/09/2022(Não sei quANDo criei, mas essa foi a data que botei o comentário)
     * @WannaLuiza
     * Cadastra/edita uma categoria
     */
    public function manage_pergunta($data = null, $id = '') {
        if ($data) {
            if ($id) {                
                $this->db->where('id', $id);
                if ($this->db->update("tbl_intranet_formularios_perguntas", $data)) {
                    return true;
                } else {
                    return false;
                }
            } else {

              
                if ($this->db->insert("tbl_intranet_formularios_perguntas", $data)) {
                    $id_insert = $this->db->insert_id();
                    return $id_insert;
                } else {
                    return false;
                }
            }
        }
    }
    
    
    
    public function delete_pergunta($data = null, $id = '') {
        if ($data) {
            if ($id) {                
                $this->db->where('id', $id);
                if ($this->db->update("tbl_intranet_formularios_perguntas", $data)) {
                    return true;
                } else {
                    return false;
                }
            }
        }
    }
    
    
     public function add_item_multiplaescolha($data = null) {
        //print_r($data); exit;
        if ($this->db->insert("tbl_intranet_formularios_items_multiescolha", $data)) {
            $id_insert = $this->db->insert_id();

            return true;
        } else {
            return false;
        }
    }
    
    public function get_item_multiplaescolha_by_pergunta_id($perg_id = null) {

        //$staff_id = get_staff_user_id();
       
        $sql = "SELECT * FROM 
                tbl_intranet_formularios_items_multiescolha 
                WHERE pergunta_id = $perg_id AND deleted = 0";
        //echo $sql; exit;
        return $this->db->query($sql)->result_array();
    }
    
    public function delete_item_multiplaescolha($data = null, $id = '') {
        if ($data) {
            if ($id) {                
                $this->db->where('id', $id);
                if ($this->db->update("tbl_intranet_formularios_items_multiescolha", $data)) {
                    return true;
                } else {
                    return false;
                }
            }
        }
    }
    
}
