<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Authentication extends ClientsController
{

    public function __construct()
    {
        parent::__construct();
        hooks()->do_action('clients_authentication_constructor', $this);
    }

    public function index()
    {
        //echo 'jdsjdj'; exit;
        $this->login();

        //  redirect(admin_url('authentication'));
    }

    // Added for backward compatibilies
    public function admin()
    {

        redirect(admin_url('authentication'));
    }

    public function wifi_olds()
    {

        // echo 'jsj'; exit;
        $continue = isset($_GET['continue']) ? $_GET['continue'] : '';
        $ip = isset($_GET['ip']) ? $_GET['ip'] : '';
        $ap_mac = isset($_GET['ap_mac']) ? $_GET['ap_mac'] : '';
        $mac = isset($_GET['mac']) ? $_GET['mac'] : '';
        $radio = isset($_GET['radio']) ? $_GET['radio'] : '';
        $ssid = isset($_GET['ssid']) ? $_GET['ssid'] : '';
        $ts = isset($_GET['ts']) ? $_GET['ts'] : '';
        $redirect_uri = isset($_GET['redirect_uri']) ? $_GET['redirect_uri'] : '';
        $user_hash = isset($_GET['user_hash']) ? $_GET['user_hash'] : '';

        $redirect_url = "http://10.0.0.1:2061/cp/itbcaptive.cgi?ts=$ts&user_hash=$user_hash";
        header("Location: $redirect_url");
    }

    public function wifi()
    {

        $this->load->view('portal/wifi_2');
    }

    public function wifi_old()
    {

        // echo 'jsj'; exit;

        $this->load->view('portal/wifi');
    }

    public function login($id = '', $login_invalido = '')
    {
        
        
        if ($id == 18) {

            $this->login_paciente($id, $login_invalido);

            exit;
        }
        // echo 'jdjjd'; exit;00790843930154003
        // redirect(admin_url('authentication'));    

         echo "aqui"; exit;

        if (is_client_logged_in()) {
            //echo "aqui"; exit;
            redirect(site_url());
        }


        $this->form_validation->set_rules('password', _l('clients_login_password'), 'required');
        $this->form_validation->set_rules('email', _l('clients_login_email'), 'trim|required|valid_email');

        if (show_recaptcha_in_customers_area()) {
            $this->form_validation->set_rules('g-recaptcha-response', 'Captcha', 'callback_recaptcha');
        }
        if ($this->form_validation->run() !== false) {
            $this->load->model('Authentication_model');
            
            $success = $this->Authentication_model->login(
                $this->input->post('email'),
                $this->input->post('password', false),
                $this->input->post('remember'),
                false
            );
           
            // print_r($success); exit;

            if (is_array($success) && isset($success['memberinactive'])) {
                set_alert('danger', _l('inactive_account'));
                redirect(site_url('authentication/login'));
            } elseif ($success == false) {
                set_alert('danger', _l('client_invalid_username_or_password'));
                redirect(site_url('authentication/login'));
            }

            if ($this->input->post('language') && $this->input->post('language') != '') {
                set_contact_language($this->input->post('language'));
            }

            $this->load->model('announcements_model');
            $this->announcements_model->set_announcements_as_read_except_last_one(get_contact_user_id());

            hooks()->do_action('after_contact_login');
            //  echo "Login"; exit;  
            maybe_redirect_to_previous_url();
            redirect(site_url());
        }
        if (get_option('allow_registration') == 1) {

            $data['title'] = _l('clients_login_heading_register');
        } else {

            $data['title'] = _l('clients_login_heading_no_register');
        }
        $data['bodyclass'] = 'customers_login';
        if ($_GET['hash'] != '') {

            //echo $hash; exit;
            $data['hash'] = $_GET['hash'];
            if ($_GET['id']) {

                // echo "aqui"; exit;
                $sql = "SELECT * FROM tblclients  where indexador = '" . $_GET['id'] . "'";

                //  echo $sql; exit;

                $data['client'] = $this->db->query($sql)->row();
                // print_r($data['client']); exit;

                $data['msg'] = '<label >Token enviado para os seguintes destinos:</label>
                                <ul>';
                if ($data['client']->phonenumber) {
                    $data['msg'] .= ' <li style="text-align: left;">' . substr($data['client']->phonenumber, 0, 5) . '*****' . substr($data['client']->phonenumber, -1, 1) . '</li>';
                } else {
                    $data['msg'] .= ' <li style="text-align: left; color:red;">Celular não encontrado. Contate o Call Center</li>';
                }
                if ($data['client']->email2) {
                    $data['msg'] .= '<li style="text-align: left;">' . substr_replace($data['client']->email2, '********', 3, strpos($data['client']->email2, '@') - 3) . '</li> ';
                } else {
                    $data['msg'] .= ' <li style="text-align: left; color:red;">Email não encontrado. Contate o Call Center</li>';
                }

                $data['msg'] .= ' </ul>';
            }
        }

        if ($id) {
            $this->load->model('Categorias_campos_model');
            $data['category'] = $this->Categorias_campos_model->get_categoria($id, 'atendimento', array("portal" => "1"), true);
            //print_r($data['category']); exit;
        }

        
        //print_r($data); exit;
        //echo 'jsj'; exit;
        $this->data($data);
        $this->view('login');
        $this->layout();
    }

    public function login_cpf_boleto($cpf_email='', $titulo='')
    {
        //
       
        $data['cpf_codificado'] = $cpf_email;
        $data['titulo'] = $titulo;
        

        $this->form_validation->set_rules('cpf', 'Informe o CPF', 'required');
        if ($this->form_validation->run() !== false) {
            
            $cpf = $this->input->post('cpf');
            $cpf_codificado = base64_decode($cpf_email);


            $titulo_codificado = base64_decode($titulo);
            // print_r($success); exit;

            if ($cpf == $cpf_codificado) {
               //ECHO 'VAI P BOLETO'; EXIT;
                //set_alert('danger', _l('inactive_account'));
               redirect(site_url('portal/financeiro/visualizar_boleto_email/'.$cpf_email.'/'.$titulo));
            } else {
                $data['error'] = 1;
            }

         
         
          //  redirect(site_url());
        }
       
        $data['bodyclass'] = 'customers_login';
      
        $this->data($data);
        $this->view('login_boleto');
        $this->layout_boleto();
    }


    /*
    public function portal_paciente($id = '')
    {


        if ($id == 18) {

            $this->login_paciente($id);

            exit;
        }
        // echo 'jdjjd'; exit;00790843930154003
        // redirect(admin_url('authentication'));    

        // echo "aqui"; exit;

        if (is_client_logged_in()) {
            //echo "aqui"; exit;
            redirect(site_url());
        }


        $this->form_validation->set_rules('password', _l('clients_login_password'), 'required');
        $this->form_validation->set_rules('email', _l('clients_login_email'), 'trim|required|valid_email');

        if (show_recaptcha_in_customers_area()) {
            $this->form_validation->set_rules('g-recaptcha-response', 'Captcha', 'callback_recaptcha');
        }
        if ($this->form_validation->run() !== false) {
            $this->load->model('Authentication_model');

            $success = $this->Authentication_model->login(
                $this->input->post('email'),
                $this->input->post('password', false),
                $this->input->post('remember'),
                false
            );

            // print_r($success); exit;

            if (is_array($success) && isset($success['memberinactive'])) {
                set_alert('danger', _l('inactive_account'));
                redirect(site_url('authentication/login'));
            } elseif ($success == false) {
                set_alert('danger', _l('client_invalid_username_or_password'));
                redirect(site_url('authentication/login'));
            }

            if ($this->input->post('language') && $this->input->post('language') != '') {
                set_contact_language($this->input->post('language'));
            }

            $this->load->model('announcements_model');
            $this->announcements_model->set_announcements_as_read_except_last_one(get_contact_user_id());

            hooks()->do_action('after_contact_login');
            //  echo "Login"; exit;  
            maybe_redirect_to_previous_url();
            redirect(site_url());
        }
        if (get_option('allow_registration') == 1) {

            $data['title'] = _l('clients_login_heading_register');
        } else {

            $data['title'] = _l('clients_login_heading_no_register');
        }
        $data['bodyclass'] = 'customers_login';
        if ($_GET['hash'] != '') {

            //echo $hash; exit;
            $data['hash'] = $_GET['hash'];
            if ($_GET['id']) {

                // echo "aqui"; exit;
                $sql = "SELECT * FROM tblclients  where indexador = '" . $_GET['id'] . "'";

                //  echo $sql; exit;

                $data['client'] = $this->db->query($sql)->row();
                // print_r($data['client']); exit;

                $data['msg'] = '<label >Token enviado para os seguintes destinos:</label>
                                <ul>';
                if ($data['client']->phonenumber) {
                    $data['msg'] .= ' <li style="text-align: left;">' . substr($data['client']->phonenumber, 0, 5) . '*****' . substr($data['client']->phonenumber, -1, 1) . '</li>';
                } else {
                    $data['msg'] .= ' <li style="text-align: left; color:red;">Celular não encontrado. Contate o Call Center</li>';
                }
                if ($data['client']->email2) {
                    $data['msg'] .= '<li style="text-align: left;">' . substr_replace($data['client']->email2, '********', 3, strpos($data['client']->email2, '@') - 3) . '</li> ';
                } else {
                    $data['msg'] .= ' <li style="text-align: left; color:red;">Email não encontrado. Contate o Call Center</li>';
                }

                $data['msg'] .= ' </ul>';
            }
        }

        if ($id) {
            $this->load->model('Categorias_campos_model');
            $data['category'] = $this->Categorias_campos_model->get_categoria($id, 'atendimento', array("portal" => "1"), true);
            //print_r($data['category']); exit;
        }

        
        //print_r($data); exit;
        //echo 'jsj'; exit;
        $this->data($data);
        $this->view('login');
        $this->layout();
    }*/

    public function login_paciente($id, $login_invalido = '')
    {
        // echo "aqui";
        // $this->load->view('themes/perfex/login_paciente');
        $this->load->model('Categorias_campos_model');
        $data['category'] = $this->Categorias_campos_model->get_categoria($id, 'atendimento', array("portal" => "1"), true);
        $data['login_invalido'] = $login_invalido;
    
        $this->data($data);
        $this->view('login_paciente');
        $this->layout();
    }

    public function register()
    {
        //echo "aqui"; exit;
        if (get_option('allow_registration') != 1 || is_client_logged_in()) {
            redirect(site_url());
        }

        if (get_option('company_is_required') == 1) {
            $this->form_validation->set_rules('company', _l('client_company'), 'required');
        }

        if (is_gdpr() && get_option('gdpr_enable_terms_and_conditions') == 1) {
            $this->form_validation->set_rules(
                'accept_terms_and_conditions',
                _l('terms_and_conditions'),
                'required',
                ['required' => _l('terms_and_conditions_validation')]
            );
        }

        $this->form_validation->set_rules('firstname', _l('client_firstname'), 'required');
        $this->form_validation->set_rules('lastname', _l('client_lastname'), 'required');
        $this->form_validation->set_rules('email', _l('client_email'), 'trim|required|is_unique[' . db_prefix() . 'contacts.email]|valid_email');
        $this->form_validation->set_rules('password', _l('clients_register_password'), 'required');
        $this->form_validation->set_rules('passwordr', _l('clients_register_password_repeat'), 'required|matches[password]');

        if (show_recaptcha_in_customers_area()) {
            $this->form_validation->set_rules('g-recaptcha-response', 'Captcha', 'callback_recaptcha');
        }

        $custom_fields = get_custom_fields('customers', [
            'show_on_client_portal' => 1,
            'required' => 1,
        ]);

        $custom_fields_contacts = get_custom_fields('contacts', [
            'show_on_client_portal' => 1,
            'required' => 1,
        ]);

        foreach ($custom_fields as $field) {
            $field_name = 'custom_fields[' . $field['fieldto'] . '][' . $field['id'] . ']';
            if ($field['type'] == 'checkbox' || $field['type'] == 'multiselect') {
                $field_name .= '[]';
            }
            $this->form_validation->set_rules($field_name, $field['name'], 'required');
        }
        foreach ($custom_fields_contacts as $field) {
            $field_name = 'custom_fields[' . $field['fieldto'] . '][' . $field['id'] . ']';
            if ($field['type'] == 'checkbox' || $field['type'] == 'multiselect') {
                $field_name .= '[]';
            }
            $this->form_validation->set_rules($field_name, $field['name'], 'required');
        }
        if ($this->input->post()) {
            if ($this->form_validation->run() !== false) {
                $data = $this->input->post();

                define('CONTACT_REGISTERING', true);

                $clientid = $this->clients_model->add([
                    'billing_street' => $data['address'],
                    'billing_city' => $data['city'],
                    'billing_state' => $data['state'],
                    'billing_zip' => $data['zip'],
                    'billing_country' => is_numeric($data['country']) ? $data['country'] : 0,
                    'firstname' => $data['firstname'],
                    'lastname' => $data['lastname'],
                    'email' => $data['email'],
                    'contact_phonenumber' => $data['contact_phonenumber'],
                    'website' => $data['website'],
                    'title' => $data['title'],
                    'password' => $data['passwordr'],
                    'company' => $data['company'],
                    'vat' => isset($data['vat']) ? $data['vat'] : '',
                    'phonenumber' => $data['phonenumber'],
                    'country' => $data['country'],
                    'city' => $data['city'],
                    'address' => $data['address'],
                    'zip' => $data['zip'],
                    'state' => $data['state'],
                    'custom_fields' => isset($data['custom_fields']) && is_array($data['custom_fields']) ? $data['custom_fields'] : [],
                ], true);

                if ($clientid) {
                    hooks()->do_action('after_client_register', $clientid);

                    if (get_option('customers_register_require_confirmation') == '1') {
                        send_customer_registered_email_to_administrators($clientid);

                        $this->clients_model->require_confirmation($clientid);
                        set_alert('success', _l('customer_register_account_confirmation_approval_notice'));
                        redirect(site_url('authentication/login'));
                    }

                    $this->load->model('authentication_model');

                    $logged_in = $this->authentication_model->login(
                        $this->input->post('email'),
                        $this->input->post('password', false),
                        false,
                        false
                    );

                    $redUrl = site_url();

                    if ($logged_in) {
                        hooks()->do_action('after_client_register_logged_in', $clientid);
                        set_alert('success', _l('clients_successfully_registered'));
                    } else {
                        set_alert('warning', _l('clients_account_created_but_not_logged_in'));
                        $redUrl = site_url('authentication/login');
                    }

                    send_customer_registered_email_to_administrators($clientid);
                    redirect($redUrl);
                }
            }
        }

        $data['title'] = _l('clients_register_heading');
        $data['bodyclass'] = 'register';
        $this->data($data);
        $this->view('register');
        $this->layout();
    }

    public function forgot_password()
    {
        if (is_client_logged_in()) {
            redirect(site_url());
        }

        $this->form_validation->set_rules(
            'email',
            _l('customer_forgot_password_email'),
            'trim|required|valid_email|callback_contact_email_exists'
        );

        if ($this->input->post()) {
            if ($this->form_validation->run() !== false) {
                $this->load->model('Authentication_model');
                $success = $this->Authentication_model->forgot_password($this->input->post('email'));
                if (is_array($success) && isset($success['memberinactive'])) {
                    set_alert('danger', _l('inactive_account'));
                } elseif ($success == true) {
                    set_alert('success', _l('check_email_for_resetting_password'));
                } else {
                    set_alert('danger', _l('error_setting_new_password_key'));
                }
                redirect(site_url('authentication/forgot_password'));
            }
        }
        $data['title'] = _l('customer_forgot_password');
        $this->data($data);
        $this->view('forgot_password');

        $this->layout();
    }

    public function reset_password($staff, $userid, $new_pass_key)
    {
        $this->load->model('Authentication_model');
        if (!$this->Authentication_model->can_reset_password($staff, $userid, $new_pass_key)) {
            set_alert('danger', _l('password_reset_key_expired'));
            redirect(site_url('authentication/login'));
        }

        $this->form_validation->set_rules('password', _l('customer_reset_password'), 'required');
        $this->form_validation->set_rules('passwordr', _l('customer_reset_password_repeat'), 'required|matches[password]');
        if ($this->input->post()) {
            if ($this->form_validation->run() !== false) {
                hooks()->do_action('before_user_reset_password', [
                    'staff' => $staff,
                    'userid' => $userid,
                ]);
                $success = $this->Authentication_model->reset_password(
                    0,
                    $userid,
                    $new_pass_key,
                    $this->input->post('passwordr', false)
                );
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
                redirect(site_url('authentication/login'));
            }
        }
        $data['title'] = _l('admin_auth_reset_password_heading');
        $this->data($data);
        $this->view('reset_password');
        $this->layout();
    }

    public function logout()
    {
        $this->load->model('authentication_model');
        $this->authentication_model->logout(false, true);
        hooks()->do_action('after_client_logout');
        redirect(site_url('authentication/login'));
    }

    public function logout_portal_paciente()
    {
        $this->load->model('authentication_model');
        $this->authentication_model->logout(false, true);
        hooks()->do_action('after_client_logout');
        redirect(site_url('authentication/login/18'));
    }

    public function contact_email_exists($email = '')
    {
        $this->db->where('email', $email);
        $total_rows = $this->db->count_all_results(db_prefix() . 'contacts');

        if ($total_rows == 0) {
            $this->form_validation->set_message('contact_email_exists', _l('auth_reset_pass_email_not_found'));

            return false;
        }

        return true;
    }

    public function recaptcha($str = '')
    {
        return do_recaptcha_validation($str);
    }

    public function change_language($lang = '')
    {
        if (is_language_disabled()) {
            redirect(site_url());
        }

        set_contact_language($lang);

        if (isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER'])) {
            redirect($_SERVER['HTTP_REFERER']);
        } else {
            redirect(site_url());
        }
    }
}
