<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * 24/02/2023
 * @IsraelAraujo
 * INTRANET - MODEL COMUNICAÇÃO COM O CLIENTE
 */
class Comunicacao_model extends App_Model {

    public function __construct() {

        parent::__construct();
    }

    public function addEmail($data = null) {


        if ($data['email_destino'] != '' && $data['mensagem'] != '' && $data['assunto'] != '') {

            $this->db->insert("tbl_intranet_emails", $data);
            $id_insert = $this->db->insert_id();
            return $id_insert;
        }
    }

    public function addSms($data = null) {

        if ($data['phone_destino'] != '' && $data['mensagem'] != '' && $data['assunto'] != '') {
        

            $data['phone_destino'] = $this->formatPhoneNumber($data['phone_destino']);

           // print_r($data); exit;
            $result = $this->EnviaSMSTWW($data['phone_destino'], $data['mensagem'], $data['rel_type']);

            if ($result['id']) {
                $data['status'] = 1;
                $data['data_envio'] = date('Y-m-d H:i:s');
            } else {
                $data['status'] = 2;
            }
            $data['response'] = json_encode($result);
            //$data['phone_destino'] = preg_replace("/[^0-9]/", "", $data['phone_destino']);
            $this->db->insert("tbl_intranet_sms", $data);
            $id_insert = $this->db->insert_id();
            return $id_insert;
        }
    }

    public function get_emails($rel_type = '', $rel_id = '', $client_id = '', $email = '') {

        $empresa_id = $this->session->userdata('empresa_id');
        $sql = "SELECT e.* FROM tbl_intranet_emails e where e.rel_type = '$rel_type' and e.deleted = 0 and e.rel_id = $rel_id and e.empresa_id = $empresa_id
            union 
            SELECT e2.* FROM tbl_intranet_emails e2
            inner join tbl_intranet_registro_ocorrencia ro on ro.id = e2.rel_id
            inner join tbl_intranet_registro_atendimento ra on ra.id = ro.registro_atendimento_id
            where e2.rel_type = 'r.o' and e2.deleted = 0 and ra.id = $rel_id and e2.empresa_id = $empresa_id
            union
            SELECT e3.* FROM tbl_intranet_emails e3
            inner join tbl_intranet_workflow wf on wf.id = e3.rel_id
            inner join tbl_intranet_registro_atendimento ra2 on ra2.id = wf.registro_atendimento_id 
            where e3.rel_type = 'workflow' and e3.deleted = 0 and ra2.id = $rel_id and e3.empresa_id = $empresa_id";

        if ($client_id != '') {
            $sql = "SELECT e.* FROM tbl_intranet_emails e where ((e.rel_type = '$rel_type'  and e.rel_id = '$rel_id') or client_id = '$client_id' or email_destino = '$email') and e.deleted = 0 and e.empresa_id = $empresa_id";
        }
        $sql .= " order by id asc";
        return $this->db->query($sql)->result_array();
    }

    public function get_sms($rel_type = '', $rel_id = '', $client_id = '', $phonenumber = '') {

        $empresa_id = $this->session->userdata('empresa_id');

        $sql = "SELECT e.* FROM tbl_intranet_sms e where e.rel_type = '$rel_type' and e.deleted = 0 and e.rel_id = $rel_id and e.empresa_id = $empresa_id
            union 
            SELECT e2.* FROM tbl_intranet_sms e2
            inner join tbl_intranet_registro_ocorrencia ro on ro.id = e2.rel_id
            inner join tbl_intranet_registro_atendimento ra on ra.id = ro.registro_atendimento_id
            where e2.rel_type = 'r.o' and e2.deleted = 0 and ra.id = $rel_id and e2.empresa_id = $empresa_id
            union
            SELECT e3.* FROM tbl_intranet_sms e3
            inner join tbl_intranet_workflow wf on wf.id = e3.rel_id
            inner join tbl_intranet_registro_atendimento ra2 on ra2.id = wf.registro_atendimento_id 
            where e3.rel_type = 'workflow' and e3.deleted = 0 and ra2.id = $rel_id and e3.empresa_id = $empresa_id";
        if ($client_id != '') {
            $sql = "SELECT e.* FROM tbl_intranet_sms e where ((e.rel_type = '$rel_type'  and e.rel_id = '$rel_id') or client_id = '$client_id' or phone_destino = '$phonenumber') and e.deleted = 0 and e.empresa_id = $empresa_id";
        }
        $sql .= " order by id desc";
        // echo $sql; 
        return $this->db->query($sql)->result_array();
    }

    public function get_notifications_permissions() {

        $empresa_id = $this->session->userdata('empresa_id');
        $sql = "SELECT feature, capability FROM tblempresas_notifications
                where empresa_id = $empresa_id order by feature";

        $result = $this->db->query($sql)->result_array();
        return $result;
    }

    public function get_notification_permission($feature, $capability) {

        $empresa_id = $this->session->userdata('empresa_id');
        $sql = "SELECT feature, capability FROM tblempresas_notifications
                where empresa_id = $empresa_id AND feature = $feature AND capability = $capability order by feature";

        $result = $this->db->query($sql)->row();
        return $result;
    }

    public function send_sigplus_email_sms($action, $data) {

       // print_r($data); exit;

        $empresa_id = $this->session->userdata('empresa_id');
        if (has_notification_action('sms', $action) || $action == true) {
            if ($data['phone_destino'] != '') {
                $campos_sms = array(
                    "data_registro" => date('Y-m-d H:i:s'),
                    "usuario_registro" => get_staff_user_id(),
                    "phone_destino" => $data['phone_destino'],
                    "assunto" => $data['assunto'],
                    "mensagem" => $data['conteudo_sms'],
                    "staff_id" => $data['staff_destino'],
                    "rel_type" => $data['rel_type'],
                    "rel_id" => $data['rel_id'],
                    "empresa_id" => $this->session->userdata('empresa_id')
                );
                $this->addSms($campos_sms);
            }
        }
        //echo 'jdj'; exit;
        if (has_notification_action('email', $action) || $action == true) {

            if ($data['email_destino'] != '') {
                $campos_email = array(
                    "data_registro" => date('Y-m-d H:i:s'),
                    "usuario_registro" => get_staff_user_id(),
                    "email_destino" => $data['email_destino'],
                    "assunto" => $data['assunto'],
                    "mensagem" => $data['conteudo_email'],
                    "staff_id" => $data['staff_destino'],
                    "rel_type" => $data['rel_type'],
                    "rel_id" => $data['rel_id'],
                    "empresa_id" => $this->session->userdata('empresa_id')
                );
                //print_r($campos_email); exit;
                $this->addEmail($campos_email);
            }
        }
        //echo 'jdj'; exit;
        if (has_notification_action('sigplus', $action)) {
            $values = [];
            $values['description'] = $data['assunto'];
            $values['touserid'] = $data['staff_destino'];
            $values['link'] = base_url() . $data['link_sigplus'];

//print_r($values); exit;
            add_notification($values);
        }
        //echo 'kak';

        return true;
    }

    public function EnviaSMSTWW($numero, $mensagem, $referencia ='', $empresa_id = '') {

        //echo $empresa_id; exit;
        
        if (!$empresa_id) {
            $empresa_id = $this->session->userdata('empresa_id');
        }

        // Dados de autenticação da API da Wavy
        $username = get_company_option('', 'twwuser', $empresa_id);
       // $authenticationToken = get_company_option('', 'twwpassword', $empresa_id);

      //  $username = 'UNIMEDAM';
        $authenticationToken = '07kf14phRAffp8gL7f7r-seWco81T73Hb_SYsPR_';

      // echo $authenticationToken . "<br>". $username  . "<br>"; 
        // URL para enviar o SMS
        $url = "https://api-messaging.wavy.global/v1/send-sms";

        // Dados a serem enviados no corpo da solicitação POST em formato JSON
        $data = array(
            "destination" => $numero,
            "messageText" => $mensagem
        );

       // echo $empresa_id; exit;
        $ch = curl_init($url);

        // Configurar as opções da solicitação cURL
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'authenticationtoken:07kf14phRAffp8gL7f7r-seWco81T73Hb_SYsPR_', 'username:SIGUNIMED',
            "Content-Type: application/json"
                )
        );

        $response = curl_exec($ch);
        
        if (curl_errno($response)) {
         //   echo 'Error :' . curl_error($response);
        }else{
         //     print_R($response);
        }

        //echo  $response; exit;
        curl_close($ch);
        return json_decode($response, true);
    }

    

    function OLD_SEND($numero, $mensagem, $referencia, $empresa_id = '', $format = false) {
        if (!$empresa_id) {
            $empresa_id = $this->session->userdata('empresa_id');
        }
        if($format == true){
            $numero = $this->formatPhoneNumber($numero);
        }

        $twwuser = get_company_option('', 'twwuser', $empresa_id);
        $twwpassword = get_company_option('', 'twwpassword', $empresa_id);
        //return $twwuser;
        if (file_exists(APPPATH . '/libraries/TWW/TWWAutoload.php')) {

            $wsdl = array(
                TWWWsdlClass::WSDL_URL => TWW_WSDL_URL,
                TWWWsdlClass::WSDL_CACHE_WSDL => WSDL_CACHE_NONE,
                TWWWsdlClass::WSDL_TRACE => true
            );

            if ($twwuser !== '') {
                $wsdl[TWWWsdlClass::WSDL_LOGIN] = $twwuser;
            }

            if ($twwpassword !== '') {
                $wsdl[TWWWsdlClass::WSDL_PASSWD] = $twwpassword;
            }

            try {
                $tWWServiceEnvia = new TWWServiceEnvia($wsdl);
                $resultado = $tWWServiceEnvia->EnviaSMS(new TWWStructEnviaSMS($twwuser, $twwpassword, $referencia, '55' . $numero, $mensagem));
                return $resultado->EnviaSMSResult->EnviaSMSResult;
            } catch (Exception $e) {
                // Lidar com a exceção
                echo 'Erro: ' . $e->getMessage();
                return false;
            }
        }
        return false;
    }

    function formatPhoneNumber($phoneNumber) {
        // Remover todos os caracteres que não são dígitos
        $phoneNumber = preg_replace('/\D/', '', $phoneNumber);

        if (strlen($phoneNumber) == 9 || strlen($phoneNumber) == 8) {
            $phoneNumber = '92' . $phoneNumber;
        } elseif (strlen($phoneNumber) == 13) {
            $phoneNumber = substr($phoneNumber, 2);
        } elseif (strlen($phoneNumber) == 14) {
            $phoneNumber = substr($phoneNumber, 3);
        }

        // Adicionar o código do país (+55) caso não esteja incluído
        if (strlen($phoneNumber) == 11) {
            $phoneNumber = '55' . $phoneNumber;
        }

        // Formatar o número de telefone
        $formattedPhoneNumber = $phoneNumber;

        return $formattedPhoneNumber;
    }

    function get_sms_($id) {
        // Remover todos os caracteres que não são dígitos
        $this->db->where('id', $id);
        return $this->db->get(db_prefix() . '_intranet_sms')->row_array();
    }

    function send_sms($sms) {

        $result = $this->EnviaSMSTWW($sms['phone_destino'], $sms['mensagem'], $sms['rel_type']);
      
        if ($result['id']) {
            $data['status'] = 1;
            $data['data_envio'] = date('Y-m-d H:i:s');
        } else {
            $data['status'] = 2;
        }
        $data['response'] = json_encode($result);
       
        $this->db->where('id', $sms['id']);
        if ($this->db->update(db_prefix() . '_intranet_sms', $sms)) {
            return true;
        } else {
            return false;
        }
    }


    function send_sms_boleto($sms) {

        $result = $this->EnviaSMSTWW($sms['phone_destino'], $sms['mensagem'], $sms['rel_type']);
      
      $data['status'] = 1;
       $data['data_envio'] = date('Y-m-d H:i:s');
       
        $this->db->where('id', $sms['id']);
        if ($this->db->update(db_prefix() . '_intranet_sms', $data)) {
            return true;
        } else {
            return false;
        }

       
    }
}
