<?php

defined('BASEPATH') or exit('No direct script access allowed');
include_once APPPATH . 'rotina_email/templates/templateemail.php';
include_once APPPATH . 'rotina_email/tarefas_agendadas/phpmailer/PHPMailerAutoload.php';

class Comunicacao extends AdminController {

    public function __construct() {

        parent::__construct();
        //require_once APPPATH . '/libraries/TWW/TWWAutoload.php';
        $this->load->model('Comunicacao_model');
    }

    /* List all staff roles */

    public function add_email($data = []) {
        //print_r($this->input->post());
        // exit;
        if ($this->input->post()) {
            $email = $this->input->post('email');
            $emails = explode(',', $email);
            $type = $this->input->post('rel_type');
            $rel_id = $this->input->post('rel_id');
            $url_retorno = $this->input->post('url_retorno');
            foreach ($_FILES as $fileKey => $file_one) {
                if (isset($file_one['error']) && $file_one['error'] === UPLOAD_ERR_OK) {
                    $file = $file_one['name'];

                    // Extrai a extensão original do arquivo
                    $file_extension = pathinfo($file, PATHINFO_EXTENSION);

                    // Gera um novo nome de arquivo único com a extensão original
                    $new_filename = $this->input->post('rel_type') . $this->input->post('rel_id') . '_' . uniqid() . '.' . $file_extension;

                    // Verifica se o diretório de destino existe; se não existir, cria-o recursivamente
                    if (!file_exists("./assets/intranet/arquivos/email_arquivos/")) {
                        mkdir("./assets/intranet/arquivos/email_arquivos/", 0777, true);
                    }

                    // Define o caminho completo para o destino do arquivo enviado
                    $destination = "./assets/intranet/arquivos/email_arquivos/" . $new_filename;

                    // Move o arquivo enviado para o destino com o novo nome de arquivo
                    if (move_uploaded_file($file_one["tmp_name"], $destination)) {
                        // Upload do arquivo bem-sucedido
                        $banco_arquivos_em_string[] = $new_filename;
                    }
                }
            }


//            print_r($banco_arquivos_em_string);
//            exit;
            $banco_arquivos_em_string = implode(';', $banco_arquivos_em_string);
            // echo $banco_arquivos_em_string; exit;

            foreach ($emails as $email) {

                $campos_email = array(
                    "data_registro" => date('Y-m-d H:i:s'),
                    "usuario_registro" => get_staff_user_id(),
                    "email_destino" => $email,
                    "assunto" => $this->input->post('assunto'),
                    "mensagem" => $this->input->post('texto'),
                    "rel_type" => $type,
                    "rel_id" => $rel_id,
                    "attachments" => $banco_arquivos_em_string,
                    "empresa_id" => $this->session->userdata('empresa_id')
                );
                //print_r($campos_email); exit;
                $id = $this->Comunicacao_model->addEmail($campos_email);

//  echo '<br> URL :'.$url_retorno; exit;
            }
// exit;
            if ($id) {
                set_alert('success', 'E-mail na fila de envio!!');
            } else {
                set_alert('danger', 'Falha ao registrar o envio!!');
            }

            $ch = curl_init();

            curl_setopt_array($ch, array(
                CURLOPT_URL => 'http://162.214.50.86/rotina_email/unimed_manaus.php',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json; charset=utf-8'
                ),
            ));

            curl_close($ch);

            redirect(base_url("$url_retorno/" . $rel_id));
        }
    }

    public function add_sms() {

       // echo "aqui"; exit;

        if ($this->input->post()) {
            $phone_destino = $this->input->post('phone_destino');
            $type = $this->input->post('rel_type');
            $rel_id = $this->input->post('rel_id');
            $url_retorno = $this->input->post('url_retorno');

            $campos_sms = array(
                "data_registro" => date('Y-m-d H:i:s'),
                "usuario_registro" => get_staff_user_id(),
                "phone_destino" => $phone_destino,
                "assunto" => $this->input->post('texto'),
                "mensagem" => $this->input->post('texto'),
                "rel_type" => $type,
                "rel_id" => $rel_id,
                "empresa_id" => $this->session->userdata('empresa_id')
            );

            //print_r($campos_sms); exit;
            $id = $this->Comunicacao_model->addSms($campos_sms);

            if ($id) {
                set_alert('success', 'Sms na fila de envio!!');
            }

            redirect(base_url("$url_retorno/" . $rel_id));
        }
    }

    /**
     * Send new notifications to new assigned contact
     * @param [array] $appointment
     * @return void
     */
    public function sms() {
        $this->load->model('registro_ocorrencia_model');
        $this->registro_ocorrencia_model->get_registros_atrasados();
    }

    public function lido() {

        $id = $this->input->post('id');

        $dados['isread'] = 1;

        $this->db->where('id', $id);
        $this->db->update('tblnotifications', $dados);
    }

    public function lido_all() {

        $dados['isread'] = 1;
        $this->db->update('tblnotifications', $dados);
    }

    public function send_sms($id) {

        if ($id) {
            //print_r($campos_sms); exit;
            $result = $this->Comunicacao_model->get_sms_($id);
            $result = $this->Comunicacao_model->send_sms($result);

            if ($result) {
                set_alert('warning', 'Tentativa realizada');
            }

            // Pega a URL da página anterior
            $url_anterior = $this->agent->referrer();

            // Redireciona de volta para a página anterior
            redirect($url_anterior);
        }
    }
}
