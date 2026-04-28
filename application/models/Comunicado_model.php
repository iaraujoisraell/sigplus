<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * 24/08/2022
 * @WannaLuiza
 * INTRANET - MODEL DE COMUNICADOS INTERNOS
 */
class Comunicado_model extends App_Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     * 24/08/2022
     * @WannaLuiza
     * Insert de Comunicado(tbl_intranet_ci)
     */
    public function add($data = null, $id = 0, $files = null) {

        if ($data) {

            $data['dt_created'] = date('Y-m-d H:i:s');

            $data['docs'] = $files;

            $data['user_create'] = get_staff_user_id();

            $data['empresa_id'] = $this->session->userdata('empresa_id');

            if ($id) {



                $this->db->where('id', $id);

                if ($this->db->update("tbl_intranet_ci", $data)) {

                    return $id;
                } else {

                    return false;
                }
            } else {

                unset($data['id']);

                unset($data['file']);

                unset($data['foto']);
                //echo $files; exit;
                //print_r($data); exit;
                
                unset($data['attachments']);

                if ($this->db->insert("tbl_intranet_ci", $data)) {

                    $id_insert = $this->db->insert_id();

                    return $id_insert;
                } else {

                    return false;
                }
            }
        }
    }

    /**
     * 24/08/2022
     * @WannaLuiza
     * Insert de Destinatários do Comunicado(tbl_intranet_ci_send)
     */
    public function send($data = null, $id = '') {
        if ($data) {
            $this->load->model('Comunicacao_model');
            $this->load->model('Staff_model');
    
            $action = 'ci_received';
            $action_info = get_actions($action);
            
            $ci = $this->get_comunicado($id)->row();
            $action_info['rel_id'] = $ci->id;
            $action_info['rel_type'] = 'ci';
    
            $replace_from = array("#change_reference");
            $replace_to = array("#" . $ci->codigo);
    
            $action_info['conteudo_email'] = str_replace($replace_from, $replace_to, $action_info['conteudo_email']);
            $action_info['conteudo_sms'] = str_replace($replace_from, $replace_to, $action_info['conteudo_sms']);
            $action_info['assunto'] = str_replace($replace_from, $replace_to, $action_info['assunto']);
            $action_info['link_sigplus'] = $action_info['link_sigplus'] . '/visualizar_comunicado?id=' . $ci->id;
    
            $insert_data = $data['for_staffs'];
    
            $array['ci_id'] = $data['id'];
            $array['dt_send'] = date('Y-m-d H:i:s');
            $array['empresa_id'] = $this->session->userdata('empresa_id');
    
            for ($i = 0; $i < count($insert_data); $i++) {
                $info = explode('-', $data['for_staffs'][$i]);
                $array['staff_id'] = $info[0];
                $array['origem'] = $info[1];
                
                // Verifica se já existe uma entrada com o mesmo ci_id, staff_id e empresa_id
                $this->db->where('ci_id', $array['ci_id']);
                $this->db->where('staff_id', $array['staff_id']);
                $this->db->where('empresa_id', $array['empresa_id']);
                $exists = $this->db->get('tbl_intranet_ci_send')->num_rows();
    
                if ($exists == 0) {
                    if ($this->db->insert("tbl_intranet_ci_send", $array)) {
                        $staff = $this->Staff_model->get($array['staff_id']);
                        $action_info['email_destino'] = $staff->email;
                        $action_info['staff_destino'] = $staff->staffid;
                        $action_info['phone_destino'] = $staff->phonenumber;
                        $this->Comunicacao_model->send_sigplus_email_sms($action, $action_info);
                    }
                }
            }
            return $data['id'];
        }
    }
    

    /**
     * 24/08/2022
     * @WannaLuiza
     * Insert de Destinatários do Comunicado(tbl_intranet_ci_send)
     */
    public function cc($data = null, $id = 0) {

        $this->load->model('Comunicacao_model');
        $this->load->model('Staff_model');

        $action = 'ci_received';
        $action_info = get_actions($action);

        $ci = $this->get_comunicado($id)->row();
        $action_info['rel_id'] = $ci->id;
        $action_info['rel_type'] = 'ci';

        $replace_from = array("#change_reference");
        $replace_to = array("#" . $ci->codigo);

        $action_info['conteudo_email'] = str_replace($replace_from, $replace_to, $action_info['conteudo_email']);

        $action_info['conteudo_sms'] = str_replace($replace_from, $replace_to, $action_info['conteudo_sms']);

        $action_info['assunto'] = str_replace($replace_from, $replace_to, $action_info['assunto']);

        $array['ci_id'] = $id;
        $array['dt_send'] = date('Y-m-d');
        $array['empresa_id'] = $this->session->userdata('empresa_id');
        $array['cc'] = 1;
        if (is_array($data)) {
            for ($i = 0; $i < count($data); $i++) {

                $array['staff_id'] = $data[$i];
                if ($this->db->insert("tbl_intranet_ci_send", $array)) {
                    $staff = $this->Staff_model->get($array['staff_id']);
                    $action_info['email_destino'] = $staff->email;
                    $action_info['staff_destino'] = $staff->staffid;
                    $action_info['phone_destino'] = $staff->phonenumber;
                    $this->Comunicacao_model->send_sigplus_email_sms($action, $action_info);
                }
            }
        } else {
            $array['staff_id'] = $data;

            if ($this->db->insert("tbl_intranet_ci_send", $array)) {
                $staff = $this->Staff_model->get($array['staff_id']);
                $action_info['email_destino'] = $staff->email;
                $action_info['staff_destino'] = $staff->staffid;
                $action_info['phone_destino'] = $staff->phonenumber;
                $this->Comunicacao_model->send_sigplus_email_sms($action, $action_info);
            }
        }
    }

    /*
     * 28/08/2022
     * @Israel Araujo
     * Salva na tabela que enviará email ao staff
     */

    public function send_email($dados_email) {

        $this->db->insert("tbl_intranet_emails", $dados_email);
    }

    /**
     * 24/08/2022
     * @WannaLuiza
     * Retorna os comunicados recebidos para o usuario conectado
     */
    public function get_recebidos() {

        $staff_id = get_staff_user_id();

        $sql = "SELECT * from tbl_intranet_ci_send

        where deleted = 0 and staff_id = $staff_id";

        return $this->db->query($sql)->result_array();
    }

    /**
     * 24/08/2022
     * @WannaLuiza
     * Retorna os comunicados nao lidos para o usuario conectado
     */
    public function get_comunicado_nao_lido() {

        $empresa_id = $this->session->userdata('empresa_id');
        $staff_id = get_staff_user_id();
        $sql = "SELECT c.*, c.id as ci, s.id as send_id from tbl_intranet_ci_send s
        left join tbl_intranet_ci c on c.id = s.ci_id
        where s.deleted = 0 and c.empresa_id = $empresa_id and s.staff_id = $staff_id and s.status = 0";

        return $this->db->query($sql)->result_array();
    }

    /*
     * data: 24/08/22
     * @israel
     * retorna os comunicados lidos
     */

    public function get_comunicado_lido() {
        $staff_id = get_staff_user_id();
        $empresa_id = $this->session->userdata('empresa_id');
        $sql = "SELECT c.*, c.id as ci, t.firstname, t.lastname from tbl_intranet_ci_send s
        left join tbl_intranet_ci c on c.id = s.ci_id
        inner join tblstaff t on t.staffid = c.user_create
        where s.staff_id =! c.user_create and s.deleted = 0 and c.empresa_id = $empresa_id and s.staff_id = $staff_id and s.status = 1 and c.deleted = 0 order by c.id desc";
        //echo $sql; exit;
        return $this->db->query($sql)->result_array();
    }

    /*
     * 31/10/2022
     * @WannaLuiza
     * retorna os comunicados criados pelo usuário logado 
     */

    public function get_comunicado_my() {
        $staff_id = get_staff_user_id();
        $empresa_id = $this->session->userdata('empresa_id');
        $sql = "SELECT c.*, c.id as ci, t.firstname, t.lastname from tbl_intranet_ci c
        inner join tblstaff t on t.staffid = c.user_create
        where c.user_create = $staff_id and c.deleted = 0 and c.empresa_id = $empresa_id order by c.id desc";
        //echo $sql; exit;
        return $this->db->query($sql)->result_array();
    }

    /**
     * 24/08/2022
     * @WannaLuiza
     * Retorna Todos os Comunicados por empresa
     */
    public function get() {

        $empresa_id = $this->session->userdata('empresa_id');
        $sql = "SELECT * from tbl_intranet_ci
        where empresa_id = $empresa_id and deleted = 0";

        return $this->db->query($sql)->result_array();
    }

    /**
     * 24/08/2022
     * @WannaLuiza
     * Retorna todos os envios do comunicado
     */
    public function get_comunicado_send($id = 0, $cc = 0) {

        $empresa_id = $this->session->userdata('empresa_id');
        $sql = "SELECT cis.*, s.firstname, s.lastname from tbl_intranet_ci_send cis "
                . "LEFT JOIN tblstaff s on s.staffid = cis.staff_id "
                . "where cis.ci_id = $id and cis.deleted = 0 and cis.empresa_id = $empresa_id and cis.cc = $cc";
//echo $sql; 
        return $this->db->query($sql)->result_array();
    }

    /**
     * 24/08/2022
     * @WannaLuiza
     * Retorna todos os envios do comunicado
     */
    public function get_send($id = '') {

        $empresa_id = $this->session->userdata('empresa_id');
        $sql = "SELECT * from tbl_intranet_ci_send "
                . "where id = $id and deleted = 0 and empresa_id = $empresa_id";
//echo $sql; 
        return $this->db->query($sql)->row();
    }

    /**
     * 24/08/2022
     * @WannaLuiza
     * Retorna todos os envios do comunicado
     */
    public function get_comunicado_cc($id = 0) {

        $empresa_id = $this->session->userdata('empresa_id');
        $sql = "SELECT cis.*, s.firstname, s.lastname, d.name from tbl_intranet_ci_send cis "
                . "LEFT JOIN tblstaff s on s.staffid = cis.staff_id "
                . "LEFT JOIN tblstaff_departments sd on sd.staffid = s.staffid "
                . "LEFT JOIN tbldepartments d on d.departmentid = sd.departmentid "
                . "where cis.ci_id = $id and cis.deleted = 0 and cis.empresa_id = $empresa_id and cis.cc = 1";
//echo $sql; exit;
        return $this->db->query($sql)->result_array();
    }

    /**
     * 24/08/2022
     * @WannaLuiza
     * Retorna um comunicado especifico por id
     */
    public function get_comunicado($id = 0) {
        $empresa_id = $this->session->userdata('empresa_id');
        $sql = "SELECT * from tbl_intranet_ci
        where id = $id and empresa_id = $empresa_id and deleted = 0";
        return $this->db->query($sql);
    }

    /**
     * 24/08/2022
     * @WannaLuiza
     * Retorna as repostas de um comunicado que as exige pra dar ciente
     */
    public function get_comunicado_send_repostas($id = 0) {

        $empresa_id = $this->session->userdata('empresa_id');
        $sql = "SELECT cis.*, s.firstname, s.lastname from tbl_intranet_ci_send cis "
                . "LEFT JOIN tblstaff s on s.staffid = cis.staff_id "
                . "where cis.ci_id = $id and cis.deleted = 0 and cis.empresa_id = $empresa_id and cis.retorno != '' group by cis.staff_id order by cis.dt_ciente asc";
//echo $sql; exit;
        return $this->db->query($sql)->result_array();
    }

    /**
     * 06/09/2022
     * @WannaLuiza
     * Insert de destinatários do documento
     */
    public function send_staffs($dados = null) {
        // print_r($dados); exit;
        if ($dados) {
            if ($this->db->insert("tbl_intranet_ci_send", $dados)) {
                $id_insert = $this->db->insert_id();
                return $id_insert;
            } else {
                return false;
            }
        }
    }

    /**
     * 22/11/2022(Não sei quANDo criei, mas essa foi a data que botei o comentário)
     * @WannaLuiza
     * Retorna o ultimo comunicado
     * OBS: Criei essa função pra quANDo a sequencia for automática, daí aqui ele mostra qual o ultimo(sem ser pelo id).
     */
    public function get_sequencial($setor_id = '') {
        $empresa_id = $this->session->userdata('empresa_id');
        $tbl_ci = 'tbl_intranet_ci';
        $sql = "SELECT * FROM $tbl_ci "
                . " WHERE $tbl_ci.empresa_id = $empresa_id AND $tbl_ci.deleted = 0  and setor_id = $setor_id";

        $sql .= " ORDER BY $tbl_ci.sequencial DESC LIMIT 1";

        //echo $sql; exit;
        $ultimo = $this->db->query($sql)->row();
        if (isset($ultimo)) {
            if ($ultimo->sequencial == '') {
                $sequencial = 1;
            } else {
                $sequencial = $ultimo->sequencial + 1;
            }
        } else {
            $sequencial = 1;
        }
        return $sequencial;
    }

}
