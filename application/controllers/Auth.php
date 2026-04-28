<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->helper(['url', 'form']);
        $this->load->library(['session', 'email']);
        $this->load->helper('form');
    }

    // P�gina inicial de login (opcional)
    public function login()
    {
        $this->load->view('auth/login');
    }

    // Exibe o formul�rio "Esqueci minha senha"
    public function esqueci_senha()
    {
        $this->load->view('auth/esqueci_senha');
    }

    // Recebe o e-mail e envia o link
    public function enviar_link_redefinicao()
    {
        $cpf = preg_replace('/[^0-9]/', '', $this->input->post('cpf'));

        if (!$cpf) {
            $this->session->set_flashdata('erro', 'Informe um CPF válido.');
            redirect('auth/esqueci_senha');
        }

        // Busca usuário pelo CPF
        $usuario = $this->db->get_where('tblclients_portal', ['cpf' => $cpf])->row();

        if (!$usuario) {
            $this->session->set_flashdata('erro', 'CPF não encontrado.');
            redirect('auth/esqueci_senha');
        }

        if (empty($usuario->email)) {
            $this->session->set_flashdata('erro', 'Usuário não possui e-mail cadastrado.');
            redirect('auth/esqueci_senha');
        }

        // Gera token único
        $token = bin2hex(random_bytes(32));
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

        // Remove tokens antigos
        $this->db->delete('tblclients_portal_password_resets', ['email' => $usuario->email]);

        // Salva novo token
        $this->db->insert('tblclients_portal_password_resets', [
            'email' => $usuario->email,
            'token' => $token,
            'expires_at' => $expires
        ]);

        // Monta o link de redefinição
        $link = base_url("auth/resetar_senha?token=" . $token);

        $mensagem = "
            <p>Olá, {$usuario->nome_completo}</p>
            <p>Recebemos uma solicitação para redefinir sua senha.</p>
            <p>Clique no link abaixo para criar uma nova senha:</p>
            <p><a href='$link'>$link</a></p>
            <p>Este link é válido por 1 hora.</p>
        ";

        // 🔹 Configuração manual (ignora o email.php, útil para testar)
        $config = [
            'protocol'  => 'smtp',
            'smtp_host' => 'smtp.office365.com',
            'smtp_user' => 'webmaster@unimedmanaus.coop.br',
            'smtp_pass' => 'W3b@Un!m3d',
            'smtp_port' => 587,
            'smtp_crypto' => 'tls',
            'mailtype'  => 'html',
            'charset'   => 'utf-8',
            'newline'   => "\r\n",
            'crlf'      => "\r\n",
            'wordwrap'  => TRUE,
        ];

        // Carrega com a configuração local
        $this->email->initialize($config);

        $this->email->from('webmaster@unimedmanaus.coop.br', 'Unimed Manaus');
        $this->email->to($usuario->email);
        $this->email->subject('Redefinição de senha - Unimed Manaus');
        $this->email->message($mensagem);

        if ($this->email->send()) {
            $this->session->set_flashdata('sucesso', 'Um link foi enviado para o e-mail cadastrado.');
        } else {
            $erro = $this->email->print_debugger(['headers']);
            $this->session->set_flashdata('erro', 'Erro ao enviar o e-mail:<br><pre>' . $erro . '</pre>');
        }

        redirect('auth/esqueci_senha');
    }



    // P�gina acessada pelo link
    public function resetar_senha()
    {
        $token = $this->input->get('token');

        $registro = $this->db
            ->where('token', $token)
            ->where('expires_at >=', date('Y-m-d H:i:s'))
            ->get('tblclients_portal_password_resets')
            ->row();

        if (!$registro) {
            show_error('Link inv�lido ou expirado.', 403);
            return;
        }

        $data['token'] = $token;
        $this->load->view('auth/resetar_senha', $data);
    }

    // Salva a nova senha
    public function salvar_nova_senha()
    {
        $token = $this->input->post('token');
        $senha = $this->input->post('senha'); 

        if (!$token || !$senha) {
            show_error('Requisição inválida.');
        }

        $registro = $this->db->get_where('tblclients_portal_password_resets', ['token' => $token])->row();

        if (!$registro) {
            show_error('Token inválido.');
        }

        $email = $registro->email;

        $this->db->where('email', $email)
            ->update('tblclients_portal', [
                'senha' => hash('sha256', $senha)
            ]);

        // Remove o token
        $this->db->where('token', $token)->delete('password_resets');

        $this->session->set_flashdata('sucesso', 'Senha redefinida com sucesso!');
        redirect('authentication/login/18');
    }
}
