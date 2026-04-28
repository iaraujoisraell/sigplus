<?php

defined('BASEPATH') or exit('No direct script access allowed');

class New_model extends App_Model
{

    private $contact_columns;

    public function __construct()
    {
        parent::__construct();

        $this->contact_columns = hooks()->apply_filters('contact_columns', ['firstname', 'lastname', 'email', 'phonenumber', 'title', 'password', 'send_set_password_email', 'donotsendwelcomeemail', 'permissions', 'direction', 'invoice_emails', 'estimate_emails', 'credit_note_emails', 'contract_emails', 'task_emails', 'project_emails', 'ticket_emails', 'is_primary']);

        $this->load->model(['client_vault_entries_model', 'client_groups_model', 'statement_model']);
    }

    public function save_new($info = array(), $id = 0)
    {

        //- print_r($id); exit;

        $array = array_filter($info, function ($valor) {
            return !is_null($valor) && $valor !== '';
        });

        $info = $array;


        if ($id) {
            $this->db->where('id', $id);
            return $this->db->update("tbl_intranet_avisos", $info);
        } else {
             // print_r($info); exit;
            return $this->db->insert('tbl_intranet_avisos', $info);
        }
    }

    public function get_news()
    {
        $empresa_id = $this->session->userdata('empresa_id');
        $sql = "SELECT * from tbl_intranet_avisos 
        where empresa_id = $empresa_id and deleted = 0 limit 2";
        return $this->db->query($sql);
    }

    public function get_datas($empresa_id = 0)
    {
        $sql = "SELECT * from tbl_intranet_eventos 
        where empresa_id = $empresa_id and deleted = 0";
        return $this->db->query($sql);
    }

    public function get_datas_my($empresa_id = 0)
    {
        $id = get_staff_user_id();
        $sql = "SELECT * from tbl_intranet_eventos 
        where empresa_id = $empresa_id and deleted = 0 and user_create = $id";
        return $this->db->query($sql);
    }

    public function get_banner($categoria_id = '')
    {
        $empresa_id = $this->session->userdata('empresa_id');
        $categoria_id = ($categoria_id) ? $categoria_id : 0;
        $hoje = date('Y-m-d');
        $sql = "SELECT * from tbl_intranet_avisos 
        where empresa_id = $empresa_id AND categoria_id = $categoria_id AND tipo = 1 AND deleted = 0 AND (fim >= '$hoje')";
        // echo $sql; exit;
        return $this->db->query($sql)->result();
    }

    public function get_popup($empresa_id)
    {
        $sql = "SELECT * from tbl_intranet_avisos 
        where empresa_id = $empresa_id and tipo = 2 and deleted = 0 and fim >= '2022-07-06' and fim != '0000-00-00'";
        return $this->db->query($sql);
    }

    public function get_noticia($categoria_id = '')
    {
        $empresa_id = $this->session->userdata('empresa_id');
        $categoria_id = ($categoria_id) ? $categoria_id : 0;
        $hoje = date('Y-m-d');
        $sql = "SELECT * from tbl_intranet_avisos 
        where empresa_id = $empresa_id and tipo = 3 AND categoria_id = $categoria_id and deleted = 0 and (fim >= '$hoje' ) ORDER BY id desc limit 3 "; // or fim = '0000-00-00'
        //ECHO $sql; exit;
        $result = $this->db->query($sql)->result_array();
        return $result;
    }

    public function get_noticias()
    {
        $empresa_id = $this->session->userdata('empresa_id');
        $sql = "SELECT avisos.*, staff.firstname, staff.lastname from tbl_intranet_avisos avisos
            LEFT JOIN tblstaff staff on staff.staffid = avisos.user_cadastro
        where avisos.empresa_id = $empresa_id and avisos.tipo = 3 and avisos.deleted = 0 ORDER BY avisos.id asc";
        //ECHO $sql; exit;
        $result = $this->db->query($sql)->result_array();
        return $result;
    }

    public function get_aviso($id = 0)
    {
        $empresa_id = $this->session->userdata('empresa_id');
        $sql = "SELECT avisos.*, staff.firstname, staff.lastname from tbl_intranet_avisos avisos
            LEFT JOIN tblstaff staff on staff.staffid = avisos.user_cadastro
        where avisos.id=$id and avisos.deleted = 0 and avisos.empresa_id = $empresa_id";
        //echo $sql; exit;
        return $this->db->query($sql);
    }
}
