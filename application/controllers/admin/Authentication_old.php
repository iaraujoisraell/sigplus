<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Authentication extends App_Controller {

    public function __construct() {
        parent::__construct();

        if ($this->app->is_db_upgrade_required()) {
            redirect(admin_url());
        }

        load_admin_language();
        $this->load->model('Authentication_model');
        $this->load->library('form_validation');

        $this->form_validation->set_message('required', _l('form_validation_required'));
        $this->form_validation->set_message('valid_email', _l('form_validation_valid_email'));
        $this->form_validation->set_message('matches', _l('form_validation_matches'));

        hooks()->do_action('admin_auth_init');
    }

    public function index() {

        $company = strstr($_SERVER['SERVER_NAME'], '.sigplus.app.br', true);

//        echo $company;
//        exit;
        if (!empty($company)) {
            redirect(base_url() . 'admin/authentication/admin_unimed?company=' . $company);
        } else {
            $this->valida();
        }
    }

    public function valida_login($user = '') {

        if (is_staff_logged_in()) {
            $this->logout_without_redirect();
        }
        //echo 'aqui'; exit;
        $user_id = $this->Authentication_model->valida_login($user);

        if ($user_id) {
            redirect(admin_url('authentication/admin_login/' . $user_id));
        } else {
            redirect(base_url() . 'gestao_corporativa/authentication');
        }
    }

    public function admin_login($user) {
        $data = $this->Authentication_model->login_login($user);

        if (is_array($data) && isset($data['memberinactive'])) {
            set_alert('danger', _l('admin_auth_inactive_account'));
            redirect(admin_url('authentication'));
        } elseif ($data == false) {
            set_alert('danger', _l('admin_auth_invalid_email_or_password'));
            redirect(admin_url('authentication'));
        }

        $this->load->model('announcements_model');
        $this->announcements_model->set_announcements_as_read_except_last_one(get_staff_user_id(), true);

        // is logged in
        maybe_redirect_to_previous_url();

        hooks()->do_action('after_staff_login');
        redirect(admin_url());

        //echo 'aqdui'; exit;
        // dados do usuario
        //$user = $this->staff_model->get($user_id);
        $data['user'] = $user;

        $data['title'] = _l('admin_auth_login_heading');
        $this->load->view('authentication/login_admin', $data);
    }

    public function valida() {

        if (is_staff_logged_in()) {
            $this->logout();
            redirect(admin_url());
        }

        $this->form_validation->set_rules('email', _l('admin_auth_login_email'), 'trim|required|valid_email');

        if ($this->input->post()) {

            if ($this->form_validation->run() !== false) {
                $email = $this->input->post('email');

                $user_id = $this->Authentication_model->valida($email);

                if ($user_id) {
                    //$this->load->model('Staff_model');
                    //$staff = $this->Staff_model->get($user_id);
                    // print_r($staff); exit;
                    redirect(admin_url('authentication/admin/' . $user_id));
                } else {
                    set_alert('danger', 'E-mail não localizado, procure o administrador do sistema da sua empresa.');
                    redirect(admin_url('authentication'));
                }
            }
        }

        $data['title'] = _l('admin_auth_login_heading');
        $this->load->view('authentication/index', $data);
    }

    public function admin($user_id) {


        if (is_staff_logged_in()) {
            redirect(admin_url());
        }
        $empresa_id = $this->session->userdata('empresa_id');
        $sql = "select caminho_saida from tblempresas where id = $empresa_id";

        $empresa = $this->db->query($sql)->row();

        if ($empresa->caminho_saida == '') {

            $this->form_validation->set_rules('password', _l('admin_auth_login_password'), 'required');
            $this->form_validation->set_rules('email', _l('admin_auth_login_email'), 'trim|required|valid_email');
            if (show_recaptcha()) {
                $this->form_validation->set_rules('g-recaptcha-response', 'Captcha', 'callback_recaptcha');
            }

            if ($this->input->post()) {
                if ($this->form_validation->run() !== false) {
                    $email = $this->input->post('email');
                    $password = $this->input->post('password', false);
                    $remember = $this->input->post('remember');

                    // print_r($this->input->post()); exit; 

                    if (!$remember) {
                        $remember = 0;
                    }
                    $data = $this->Authentication_model->login($email, $password, $remember, true);

                    if (is_array($data) && isset($data['memberinactive'])) {
                        set_alert('danger', _l('admin_auth_inactive_account'));
                        redirect(admin_url('authentication'));
                    } elseif (is_array($data) && isset($data['two_factor_auth'])) {
                        if ($data['user']->two_factor_auth_enabled == 1) {
                            $this->Authentication_model->set_two_factor_auth_code($data['user']->staffid);
                            $sent = send_mail_template('staff_two_factor_auth_key', $data['user']);

                            if (!$sent) {
                                set_alert('danger', _l('two_factor_auth_failed_to_send_code'));
                                redirect(admin_url('authentication'));
                            } else {
                                set_alert('success', _l('two_factor_auth_code_sent_successfully', $email));
                                redirect(admin_url('authentication/two_factor'));
                            }
                        } else {
                            set_alert('success', _l('enter_two_factor_auth_code_from_mobile'));
                            redirect(admin_url('authentication/two_factor/app'));
                        }
                    } elseif ($data == false) {
                        set_alert('danger', _l('admin_auth_invalid_email_or_password'));
                        redirect(admin_url('authentication'));
                    }

                    $this->load->model('announcements_model');
                    $this->announcements_model->set_announcements_as_read_except_last_one(get_staff_user_id(), true);

                    // is logged in
                    maybe_redirect_to_previous_url();

                    hooks()->do_action('after_staff_login');
                    redirect(admin_url());
                }
            }

            // dados do usuario
            $user = $this->staff_model->get($user_id);
            $data['user'] = $user;

            $data['title'] = _l('admin_auth_login_heading');
            $this->load->view('authentication/login_admin', $data);
        } else {
            redirect($empresa->caminho_saida);
        }
    }

    public function admin_unimed() {


        if (is_staff_logged_in()) {
            redirect(admin_url());
        }
        if ($this->input->post('email')) {

            //  if ($this->form_validation->run() !== false) {

            $login = $this->input->post('email');
            $senha = $this->input->post('password', false);
            $remember = $this->input->post('remember');

            // $login = $_POST['login'];
            // $senha = $_POST['senha'];



            $url = 'https://sistemaweb.unimedmanaus.com.br/sigplus/api/Login';
            $data = ['login' => $login, 'senha' => $senha];
            // print_R($data); exit;

            $ch = curl_init();

            curl_setopt_array($ch, array(
                CURLOPT_URL => 'https://sistemaweb.unimedmanaus.com.br/sigplus/api/Login',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => '{ "login": "' . $login . '", "senha": "' . $senha . '" }',
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json; charset=utf-8'
                ),
            ));

            $response = curl_exec($ch);
            //print_r($response); exit;
            curl_close($ch);
            //echo $response; exit;
            if ($response == true) {
                //echo 'aqui'; exit;
                redirect(admin_url('authentication/valida_login/' . $login));
                header('Location: ' . "https://sigplus.app.br/admin/");
            } else {
                redirect(admin_url('authentication/admin_unimed'));
            }



            // }
        }

        // dados do usuario
        //$user = $this->staff_model->get($user_id);
        //$data['user'] = $user;

        $dominio = 'unimedmanaus';
        $this->load->model('Company_model');
        $row = $this->Company_model->get_company($dominio);

        $this->load->model('Categorias_campos_model');
        $categorias = $this->Categorias_campos_model->get_categorias_without_ra('r.o', 4, 'and anonimo = 1');

        $data['registro_categorias'] = $categorias;

        $data['title'] = _l('admin_auth_login_heading');
        $this->load->view('authentication/login_unimed', $data);
    }

    public function two_factor($type = 'email') {
        $this->form_validation->set_rules('code', _l('two_factor_authentication_code'), 'required');

        if ($this->input->post()) {
            if ($this->form_validation->run() !== false) {
                $code = $this->input->post('code');
                $code = trim($code);
                if ($this->Authentication_model->is_two_factor_code_valid($code) && $type = 'email') {
                    $user = $this->Authentication_model->get_user_by_two_factor_auth_code($code);
                    $this->Authentication_model->clear_two_factor_auth_code($user->staffid);
                    $this->Authentication_model->two_factor_auth_login($user);

                    $this->load->model('announcements_model');
                    $this->announcements_model->set_announcements_as_read_except_last_one(get_staff_user_id(), true);

                    maybe_redirect_to_previous_url();

                    hooks()->do_action('after_staff_login');
                    redirect(admin_url());
                } elseif ($this->Authentication_model->is_google_two_factor_code_valid($code) && $type = 'app') {
                    $user = get_staff($this->session->userdata('tfa_staffid'));
                    $this->Authentication_model->two_factor_auth_login($user);
                    $this->load->model('announcements_model');
                    $this->announcements_model->set_announcements_as_read_except_last_one(get_staff_user_id(), true);

                    maybe_redirect_to_previous_url();

                    hooks()->do_action('after_staff_login');
                    redirect(admin_url());
                } else {
                    set_alert('danger', _l('two_factor_code_not_valid'));
                    redirect(admin_url('authentication/two_factor/' . $type));
                }
            }
        }
        $this->load->view('authentication/set_two_factor_auth_code');
    }

    public function forgot_password() {
        if (is_staff_logged_in()) {
            redirect(admin_url());
        }
        $this->form_validation->set_rules('email', _l('admin_auth_login_email'), 'trim|required|valid_email|callback_email_exists');
        if ($this->input->post()) {
            if ($this->form_validation->run() !== false) {
                $success = $this->Authentication_model->forgot_password($this->input->post('email'), true);
                if (is_array($success) && isset($success['memberinactive'])) {
                    set_alert('danger', _l('inactive_account'));
                    redirect(admin_url('authentication/forgot_password'));
                } elseif ($success == true) {
                    set_alert('success', _l('check_email_for_resetting_password'));
                    redirect(admin_url('authentication'));
                } else {
                    set_alert('danger', _l('error_setting_new_password_key'));
                    redirect(admin_url('authentication/forgot_password'));
                }
            }
        }
        $this->load->view('authentication/forgot_password');
    }

    public function reset_password($staff, $userid, $new_pass_key) {
        if (!$this->Authentication_model->can_reset_password($staff, $userid, $new_pass_key)) {
            set_alert('danger', _l('password_reset_key_expired'));
            redirect(admin_url('authentication'));
        }
        $this->form_validation->set_rules('password', _l('admin_auth_reset_password'), 'required');
        $this->form_validation->set_rules('passwordr', _l('admin_auth_reset_password_repeat'), 'required|matches[password]');
        if ($this->input->post()) {
            if ($this->form_validation->run() !== false) {
                hooks()->do_action('before_user_reset_password', [
                    'staff' => $staff,
                    'userid' => $userid,
                ]);
                $success = $this->Authentication_model->reset_password($staff, $userid, $new_pass_key, $this->input->post('passwordr', false));
                if (is_array($success) && $success['expired'] == true) {
                    set_alert('danger', _l('password_reset_key_expired'));
                } elseif ($success == true) {
                    hooks()->do_action('after_user_reset_password', [
                        'staff' => $staff,
                        'userid' => $userid,
                    ]);
                    set_alert('success', _l('password_reset_message'));
                } else {
                    set_alert('danger', _l('password_reset_message_fail'));
                }
                redirect(admin_url('authentication'));
            }
        }
        $this->load->view('authentication/reset_password');
    }

    public function set_password($staff, $userid, $new_pass_key) {
        if (!$this->Authentication_model->can_set_password($staff, $userid, $new_pass_key)) {
            set_alert('danger', _l('password_reset_key_expired'));
            if ($staff == 1) {
                redirect(admin_url('authentication'));
            } else {
                redirect(site_url('authentication'));
            }
        }
        $this->form_validation->set_rules('password', _l('admin_auth_set_password'), 'required');
        $this->form_validation->set_rules('passwordr', _l('admin_auth_set_password_repeat'), 'required|matches[password]');
        if ($this->input->post()) {
            if ($this->form_validation->run() !== false) {
                $success = $this->Authentication_model->set_password($staff, $userid, $new_pass_key, $this->input->post('passwordr', false));
                if (is_array($success) && $success['expired'] == true) {
                    set_alert('danger', _l('password_reset_key_expired'));
                } elseif ($success == true) {
                    set_alert('success', _l('password_reset_message'));
                } else {
                    set_alert('danger', _l('password_reset_message_fail'));
                }
                if ($staff == 1) {
                    redirect(admin_url('authentication'));
                } else {
                    redirect(site_url());
                }
            }
        }
        $this->load->view('authentication/set_password');
    }

    public function logout() {
        $caminho_saida = $this->Authentication_model->get_caminho_saida();
        $this->Authentication_model->logout(true, true);
        hooks()->do_action('after_user_logout');
        if ($caminho_saida) {
            redirect("$caminho_saida");
        }

        redirect(admin_url('authentication'));
    }

    public function logout_without_redirect_empresa() {
        $this->Authentication_model->logout();
        hooks()->do_action('after_user_logout');
        redirect(admin_url('authentication'));
    }

    public function logout_without_redirect() {
        $this->Authentication_model->logout();
        hooks()->do_action('after_user_logout');
        redirect(base_url('gestao_corporativa/authentication'));
    }

    public function email_exists($email) {
        $total_rows = total_rows(db_prefix() . 'staff', [
            'email' => $email,
        ]);
        if ($total_rows == 0) {
            $this->form_validation->set_message('email_exists', _l('auth_reset_pass_email_not_found'));

            return false;
        }

        return true;
    }

    public function recaptcha($str = '') {
        return do_recaptcha_validation($str);
    }

    public function get_qr() {
        if (!is_staff_logged_in()) {
            ajax_access_denied();
        }

        $company_name = preg_replace('/:/', '-', get_option('companyname'));

        if ($company_name == '') {
            // Colons is not allowed in the issuer name
            $company_name = rtrim(preg_replace('/^https?:\/\//', '', site_url()), '/') . ' - CRM';
        }

        $data = $this->authentication_model->get_qr($company_name);
        $this->load->view('admin/includes/google_two_factor', $data);
    }
}
