<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends ClientsController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Clients_model');
        $this->load->library('email');
        $this->load->library('session');
        $this->load->helper('url');
    }

    // =========================================================
    // CONFIRMAR E-MAIL (via LINK)
    // =========================================================
    public function confirmar_email($token = null)
    {
        if (!$token) {
            show_error('Token inválido.');
        }

        $client = $this->db->get_where('tblclients_portal', [
            'token_email' => $token
        ])->row();

        if (!$client) {
            show_error('Token inválido ou já utilizado.');
        }

        if (strtotime($client->token_email_expires) < time()) {
            show_error('Token expirado. Solicite novamente.');
        }

        // Marca como validado
        $this->db->where('id', $client->id)->update('tblclients_portal', [
            'email_validado' => 1,
            'token_email' => null
        ]);
        echo "
    <div style='text-align:center; font-family:Arial; margin-top:40px;'>
        <h3>Email confirmado com sucesso!</h3>
        <p>Você será redirecionado em 3 segundos...</p>

        <a href='" . base_url('authentication/login/18') . "' 
           style='padding:10px 20px; background:#007bff; color:white; border-radius:6px; text-decoration:none;'>
           Ir agora
        </a>
    </div>

    <script>
        setTimeout(function(){
            window.location = '" . base_url('authentication/login/18') . "';
        }, 3000);
    </script>
";
    }


    // =========================================================
    // VALIDAR SMS (usu�rio informa o c�digo)
    // =========================================================
    public function validar_sms()
    {
        //echo "aqui"; exit;
        $codigo = $this->input->post('codigo'); //echo "$codigo"; exit;
        $id_client = get_client_user_id();

        if (!$codigo) {
            echo 'Código inválido';
            return;
        }

        $client = $this->db->get_where('tblclients_portal', ['id' => $id_client])->row();

        if (!$client) {
            echo 'Erro ao localizar usuário';
            return;
        }

        if ($client->codigo_sms != $codigo) {
            echo 'Código incorreto';
            return;
        }

        if (strtotime($client->sms_expires_at) < time()) {
            echo  'Código expirado';
            return;
        }

        // Marca validado
        $this->db->where('id', $id_client)->update('tblclients_portal', [
            'telefone_validado' => 1,
            'codigo_sms' => null
        ]);

        echo "
    <div style='text-align:center; font-family:Arial; margin-top:40px;'>
        <h3>Telefone confirmado com sucesso!</h3>
        <p>Você será redirecionado em 3 segundos...</p>

        <a href='" . base_url('authentication/login/18') . "' 
           style='padding:10px 20px; background:#007bff; color:white; border-radius:6px; text-decoration:none;'>
           Ir agora
        </a>
    </div>

    <script>
        setTimeout(function(){
            window.location = '" . base_url('authentication/login/18') . "';
        }, 3000);
    </script>
";
        exit;
    }


    // =========================================================
    // REENVIAR E-MAIL DE CONFIRMA��O
    // =========================================================
    public function reenviar_email()
    {
        $id_client = get_client_user_id();
        $client = $this->db->get_where('tblclients', ['userid' => $id_client])->row();

        // Novo token
        $token = bin2hex(random_bytes(32));

        $this->db->where('userid', $id_client)->update('tblclients', [
            'token_email' => $token,
            'token_email_expires' => date("Y-m-d H:i:s", strtotime("+1 hour"))
        ]);

        $this->_enviar_email_confirmacao($client->email, $token);

        echo json_encode(['success' => true, 'msg' => 'E-mail reenviado com sucesso!']);
    }


    // =========================================================
    // REENVIAR SMS (novo c�digo)
    // =========================================================
    public function reenviar_sms()
    {
        $id_client = get_client_user_id();
        $client = $this->db->get_where('tblclients', ['userid' => $id_client])->row();

        // Novo c�digo
        $codigo = rand(100000, 999999);

        $this->db->where('userid', $id_client)->update('tblclients', [
            'codigo_sms' => $codigo,
            'sms_expires_at' => date("Y-m-d H:i:s", strtotime("+10 minutes"))
        ]);

        $this->_enviar_sms_confirmacao($client->phonenumber, $codigo);

        echo json_encode(['success' => true, 'msg' => 'SMS reenviado com sucesso!']);
    }


    // =========================================================
    // FUN��O PRIVADA - ENVIO DO E-MAIL
    // =========================================================
    private function _enviar_email_confirmacao($email, $token)
    {
        $link = base_url("auth/confirmar_email/$token");

        $mensagem = "
            <p>Ol�!</p>
            <p>Confirme seu e-mail clicando no link abaixo:</p>
            <p><a href='{$link}'>{$link}</a></p>
            <p>Este link expira em 1 hora.</p>
        ";

        $this->email->from('no-reply@sistema.com', 'Portal do Paciente');
        $this->email->to($email);
        $this->email->subject('Confirme seu e-mail');
        $this->email->message($mensagem);
        $this->email->send();
    }


    // =========================================================
    // FUN��O PRIVADA � ENVIO DO SMS
    // =========================================================
    private function _enviar_sms_confirmacao($telefone, $codigo)
    {
        // Aqui voc� usa sua fun��o existente
        enviar_sms($telefone, "Seu c�digo de confirma��o �: $codigo");

        // Se quiser, posso adaptar automaticamente ao seu m�todo real.
    }
}
