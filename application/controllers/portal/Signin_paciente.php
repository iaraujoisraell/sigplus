<?php

class Signin_paciente extends ClientsController
{

    function __construct()
    {

        parent::__construct();
        //$this->load->model("Portal_clients_model");
        $this->load->model("Clients_model");
    }

    function index($msg = 0)
    {


        $view_data["smg_success"] = '';
        $view_data["smg_erro"] = $msg;

        $this->load->view('portal/signin/index', $view_data);
    }

    function verificaIndexador($item)
    {
        return $item['vinculo'] === 'indexador';
    }

    function valida()
    {

        //  echo "aqui"; exit;
        $data = $this->input->post();
        $data['cpf'] = str_replace(array('.', '-'), '', $data['cpf']);

        $sql = "SELECT * FROM tblclients_portal
        where cpf = '" . $data['cpf'] . "' and deleted = 0 and active = 1"; // 

        $row = $this->db->query($sql)->row_array();
        //print_r($row);exit;

        if (is_array($row)) {

            // $categoria = $this->Categorias_campos_model->get_categoria($data['id'], 'atendimento', [], true);

            $client = $row;

            // print_r($client);exit;
            $n = rand(1, 999999);
            $token = str_pad($n, 6, 0, STR_PAD_LEFT);

            $hash = md5(date('Y-m-d') . $token . $client['id']);

            $info = [
                "userid" => $client['id'],
                "token" => $token,
                "hash" => $hash,
                "datecreatd" => date("Y-m-d H:i:s"),
                "datevalid" => date('Y-m-d H:i:s', strtotime("+1 days", strtotime(date("Y-m-d H:i:s")))),
                "telefone" => $client['telefone'],
                "email" => $client['email'],
                "categoria_id" => '9',
            ];

            $sql = "SELECT * FROM tblclient_token
            where userid = " . $client['id'] . " and utilizado = 0";
            //  print_r($info); exit;

            $row = $this->db->query($sql)->row();


            if (isset($row)) {
                // echo "token"; exit;
                $this->db->where('id', $row->id);
                if ($this->db->update("tblclient_token", $info)) {
                    $resultado = true;
                } else {
                    $resultado = false;
                }
            } else {
                // echo "token novo"; exit;
                if ($this->db->insert("tblclient_token", $info)) {
                    $resultado = true;
                } else {
                    $resultado = false;
                }
            }


            if ($resultado == true) {


                $sql = "SELECT * FROM tblcontacts  where userid = " . $client['id'] . " and deleted = 0";

                //echo $sql; exit;
                $contact = $this->db->query($sql)->row();
                //echo 'djd'; exit;
                //echo $company_name; exit;
                if ($contact == '') {
                    //echo 'entreou'; exit;
                    $contact = array(
                        "datecreated" => date('Y-m-d H:i:s'),
                        "title" => 'Cliente Portal - HOSPITAL',
                        "email" => $client['email'],
                        "userid" => $client['id'],
                    );

                    $this->db->insert("tblcontacts", $contact);
                    $contact = $this->db->query($sql)->row();
                }


                $this->load->model('Authentication_model');

                $portal_paciente = 1;
                $success = $this->Authentication_model->login_portal_paciente($data['cpf'], $data['password'], $hash, $portal_paciente);

                if ($success == true) {
                    $info_login['ultimo_login'] = date('Y-m-d H:i:s');
                    $this->db->where('id', $client['id']);
                    $this->db->update('tblclients_portal', $info_login);
                    redirect(base_url() . 'portal/Dashboard_portal_paciente/portal_paciente');
                    // echo $teste;exit;

                }else{
                    redirect(base_url() . 'authentication/login/18/1');
                }
            }
        }else{

           
          //  set_alert("danger", "Usuário e senha Incorretos !");
            redirect(base_url() . 'authentication/login/18/1');
        }
        //  print_r($data); exit;
        // echo "aqui"; exit;

        //  $this->load->model('Categorias_campos_model');
        // $campos = $this->Categorias_campos_model->get_categoria_campos($data['apicategoria_id'], '', true, 'api');
        //echo $data['type_login']; exit;


    }

    function add_cadastro()
    {
        $info = $this->input->post();

        unset($info['senha_conf']);
        $info['senha'] = hash('sha256', $info['senha']);
        $info['cpf'] = str_replace(array('.', '-'), '', $info['cpf']);

        $info['date_created'] = date('Y-m-d H:i:s');

        // print_r($info); exit;

        //VERIFICA SE JA EXISTE CADASTRO
        $sql = "SELECT * from tblclients_portal where cpf = '" . $info['cpf'] . "' and deleted = 0 and active = 1";
        $cadastro = $this->db->query($sql)->result_array();
        if ($cadastro) {
            echo 'CPF ja cadastrado !!<br><br> <button onclick="history.go(-1)"> VOLTAR </button> '; 
        } else {

            if ($this->db->insert('tblclients_portal', $info)) {
                set_alert("success", "Cadastro realizado com sucesso !");
                redirect(base_url('authentication/login/18'));
            } else {
              echo "Erro ao cadastrar, contate o setor de atendimento ";
                exit;
                set_alert("danger", "");
            }
        }


        // print_r($info);
    }

    function token($data, $client, $master)
    {
        /*var_dump($master);
        if($master == false){
            
            echo "false";
        }
        if($master == true){
            echo "tr ";
        }
        if($master === 2){
            echo "aqui";
        }
        exit;*/


        $categoria = $this->Categorias_campos_model->get_categoria($data['id'], 'atendimento', [], true);

        $n = rand(1, 999999);

        $token = str_pad($n, 6, 0, STR_PAD_LEFT);

        $hash = md5(date('Y-m-d') . $token . $client['userid']);

        $info = [
            "userid" => $client['userid'],
            "token" => $token,
            "hash" => $hash,
            "datecreatd" => date("Y-m-d H:i:s"),
            "datevalid" => date('Y-m-d H:i:s', strtotime("+1 days", strtotime(date("Y-m-d H:i:s")))),
            "telefone" => $client['phonenumber'],
            "email" => $client['email2'],
            "cateirinha" => $client['indexador'],
            "empresa_id" => $client['empresa_id'],
            "categoria_id" => $data['id'],
        ];

        $sql = "SELECT * FROM tblclient_token
        where userid = " . $client['userid'] . " and utilizado = 0";
        //print_r($info); exit;

        $row = $this->db->query($sql)->row();


        if (isset($row)) {
            $this->db->where('id', $row->id);
            if ($this->db->update("tblclient_token", $info)) {
                $resultado = true;
            } else {
                $resultado = false;
            }
        } else {
            if ($this->db->insert("tblclient_token", $info)) {
                $resultado = true;
            } else {
                $resultado = false;
            }
        }


        if ($resultado == true) {


            $sql = "SELECT * FROM tblcontacts  where userid = " . $client['userid'] . " and deleted = 0";

            //echo $sql; exit;
            $contact = $this->db->query($sql)->row();
            //echo 'djd'; exit;
            //echo $company_name; exit;
            if ($contact == '') {
                //echo 'entreou'; exit;
                $contact = array(
                    "datecreated" => date('Y-m-d H:i:s'),
                    "title" => 'Cliente Portal',
                    "email" => $client['email2'],
                    "userid" => $client['userid'],
                    "empresa_id" => $client['empresa_id']
                );

                $this->db->insert("tblcontacts", $contact);
                $contact = $this->db->query($sql)->row();
            }


            $company_name = get_company_option('', 'companyname', $client['empresa_id']);


            if ($master == false) {



                $msg = $company_name . ': Token ' . $token . ' gerado mediante solicitação de login no Portal ' . $categoria['titulo'] . '. Não compartilhe.';
                $campos_email = array(
                    "data_registro" => date('Y-m-d H:i:s'),
                    "usuario_registro" => get_staff_user_id(),
                    "email_destino" => $client['email2'],
                    "assunto" => 'TOKEN DE ACESSO AO PORTAL DO CLIENTE',
                    "mensagem" => $msg,
                    "rel_type" => 'client',
                    "rel_id" => $client['userid'],
                    "empresa_id " => $contact->empresa_id
                );

                $msg = $company_name . ': Token ' . $token . ' gerado mediante solicitacao de login no Portal ' . $categoria['titulo'] . '. Nao compartilhe.';
                $campos_sms = array(
                    "data_registro" => date('Y-m-d H:i:s'),
                    "usuario_registro" => get_staff_user_id(),
                    "phone_destino" => $client['phonenumber'],
                    "assunto" => 'TOKEN DE ACESSO AO PORTAL DO CLIENTE',
                    "mensagem" => $msg,
                    "rel_type" => 'client',
                    "rel_id" => $client['userid'],
                    "empresa_id " => $contact->empresa_id
                );
                //print_r($campos_sms); exit;
                $this->load->model('Comunicacao_model');

                $campos_sms['phone_destino'] = $this->Comunicacao_model->formatPhoneNumber($campos_sms['phone_destino']);

                $result = $this->Comunicacao_model->EnviaSMSTWW($campos_sms['phone_destino'], $campos_sms['mensagem'], $campos_sms['rel_type'], $campos_sms['empresa_id'], true);

                if ($result['id']) {
                    $campos_sms['status'] = 1;
                    $campos_sms['data_envio'] = date('Y-m-d H:i:s');
                } else {
                    $campos_sms['status'] = 2;
                }
                $campos_sms['response'] = json_encode($result);

                $this->db->insert("tbl_intranet_sms", $campos_sms);

                $email = $this->Comunicacao_model->addEmail($campos_email);
                //$sms = $this->Comunicacao_model->addSms($campos_sms);
            }

            if ($master === 2) {
                // echo "aqui"; exit;,
                redirect(base_url() . 'portal/signin/login_paciente/' . $categoria['id'] . '?id=' . $client['indexador'] . '&hash=' . $hash . '&master=' . $master);
                //redirect(base_url() . 'authentication/login/' . $categoria['id'] . '?id=' . $client['indexador'] . '&hash='.$hash.'&master=' . $master);
            } else { // if($master == false){
                redirect(base_url() . 'authentication/login/' . $categoria['id'] . '?id=' . $client['indexador'] . '&hash=' . $hash . '&master=' . $master);
            }
        }
    }

    function login_paciente($hash = '')
    {

        // echo "aqui";exit;
        // print_r($hash); exit;

        //$clientid_msg = explode('_', $client_id);
        //$client_id = $clientid_msg[0];
        //if ($clientid_msg[1]) { //se tiver mensagem
        //$view_data["msg"] = 'Senha incorreta!';
        //}
        //echo 'jdjjd'; exit;
        // redirect(base_url('portal/dashboard/index'));

        // redirect(base_url('portal/dashboard/index'));

        $hash = $_GET['hash'];

        $staff = false;
        if ($this->input->post('user')) {
            $staff = true;
            $master = '&master=1';
        }
        if (is_client_logged_in()) {
            redirect(base_url('portal/dashboard/index'));
        }

        // redirect(base_url('portal/dashboard/index'));

        $sql = "SELECT * FROM tblclient_token
          where hash = '" . $_GET['hash'] . "' and utilizado = 0";

        $row = $this->db->query($sql)->row_array();

        //  print_r($sql); exit;
        if (is_array($row)) {

            $this->load->model('Authentication_model');

            $success = $this->Authentication_model->login_hash(
                $hash,
                $row['token'],
                $row['userid'],
                $staff,
                $this->input->post('user'),
                $this->input->post('password')
            );

            if (is_array($success) && isset($success['memberinactive'])) {
                redirect(base_url('authentication/login/' . $row['categoria_id'] . '?id=' . $row['cateirinha'] . '&hash=' . $hash . $master . '&error=1'));
            } elseif ($success == false) {
                redirect(base_url('authentication/login/' . $row['categoria_id'] . '?id=' . $row['cateirinha'] . '&hash=' . $hash . $master . '&error=1'));
            }

            $this->load->model('announcements_model');
            $this->announcements_model->set_announcements_as_read_except_last_one(get_contact_user_id());

            //maybe_redirect_to_previous_url();
            redirect(base_url('portal/dashboard/portal_paciente/' . $_GET['master']));
        }
    }

    function acess_type()
    {

        $data = $this->input->post();
        $type = $this->db->query('select * from tbl_intranet_acess_type where id = ' . $data['acess_type'])->row();

        if ($type != '') {
            $this->load->model('Categorias_campos_model');
            $api = $this->Categorias_campos_model->get_categoria($type->api_id, 'api');
            if ($api != '') {
                if ($api->caminho != '') {
                    $campos = $this->Categorias_campos_model->get_categoria_campos_all($api->id, 'api', 1);

                    if (count($campos) > 0) {
                        foreach ($campos as $campo) {
                            $json[$campo['nome']] = $data[$campo['name']];
                        }

                        //                        print_r($json); 
                        //                        echo json_encode($json); 
                        //                        echo $api->caminho; exit;

                        $ch = curl_init();
                        curl_setopt_array($ch, array(
                            CURLOPT_URL => $api->caminho,
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_ENCODING => '',
                            CURLOPT_MAXREDIRS => 10,
                            CURLOPT_TIMEOUT => 0,
                            CURLOPT_FOLLOWLOCATION => true,
                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                            CURLOPT_CUSTOMREQUEST => 'POST',
                            CURLOPT_POSTFIELDS => json_encode($json),
                            CURLOPT_HTTPHEADER => array(
                                'Content-Type: application/json; charset=utf-8'
                            ),
                        ));
                        $response = curl_exec($ch);
                        curl_close($ch);
                        //echo $response; exit;
                        $info = json_decode($response, true);
                        if (is_array($info)) {
                            foreach ($campos as $campo) {
                                if ($campo['vinculo']) {
                                    //echo $campo['vinculo']; echo $data[$campo['name']];
                                    $this->db->where($campo['vinculo'], $data[$campo['name']]);
                                }
                            }

                            //EXIT;
                            $client = $this->db->get(db_prefix() . 'clients')->row();
                            if ($client != '') {
                                $campos_out = $this->Categorias_campos_model->get_categoria_campos_all($api->id, 'api', 2);
                                // ECHO 'JSJ';
                                //print_r($info); EXIT;

                                foreach ($campos_out as $campo) {
                                    if ($campo['vinculo']) {
                                        //echo $campo['vinculo']; echo $data[$campo['name']];
                                        //$this->db->where($campo['vinculo'], $data[$campo['name']]);
                                        $client_edit[$campo['vinculo']] = $info[$campo['nome']];
                                    }
                                }
                                //print_r($client_edit); exit;

                                foreach ($campos as $campo) {
                                    if ($campo['vinculo']) {
                                        //echo $campo['vinculo']; echo $data[$campo['name']];
                                        $this->db->where($campo['vinculo'], $data[$campo['name']]);
                                    }
                                }

                                if ($this->db->update("tblclients", $client_edit)) {
                                    $this->token_new($info, $client);
                                }
                            } else {
                                echo 'Cliente novo';
                            }
                        } else {
                            echo 'Resultado da api invalido';
                        }
                    } else {
                        echo 'Api sem campos de verificação';
                    }
                } else {
                    echo 'Api sem caminho configurado';
                }
            } else {
                echo 'Api não vinculada';
            }
        } else {
            echo 'Tipo de acesso não encontrado';
        }
    }

    /* function index() {
      $this->load->view('signin/error');

      } */

    function token_new($data, $client, $master = false)
    {
        $n = rand(1, 999999);

        $token = str_pad($n, 6, 0, STR_PAD_LEFT);

        $hash = md5(date('Y-m-d') . $token . $client->userid);

        $continue = isset($_GET['continue']) ? $_GET['continue'] : '';
        $ip = isset($_GET['ip']) ? $_GET['ip'] : '';
        $ap_mac = isset($_GET['ap_mac']) ? $_GET['ap_mac'] : '';
        $mac = isset($_GET['mac']) ? $_GET['mac'] : '';
        $radio = isset($_GET['radio']) ? $_GET['radio'] : '';
        $ssid = isset($_GET['ssid']) ? $_GET['ssid'] : '';
        $ts = isset($_GET['ts']) ? $_GET['ts'] : '';
        $redirect_uri = isset($_GET['redirect_uri']) ? $_GET['redirect_uri'] : '';
        $user_hash = isset($_GET['user_hash']) ? $_GET['user_hash'] : '';

        $info = [
            "userid" => $client->userid,
            "token" => $token,
            "hash" => $hash,
            "datecreatd" => date("Y-m-d H:i:s"),
            "datevalid" => date('Y-m-d H:i:s', strtotime("+1 days", strtotime(date("Y-m-d H:i:s")))),
            "empresa_id" => $client->empresa_id
        ];

        //echo 'jdjdjd'; exit;
        $sql = "SELECT * FROM tblclient_token
        where userid = " . $client->userid . " and utilizado = 0";
        //echo $sql; exit;

        $row = $this->db->query($sql)->row();

        if (isset($row)) {
            $this->db->where('id', $row->id);
            if ($this->db->update("tblclient_token", $info)) {
                $resultado = true;
            } else {
                $resultado = false;
            }
        } else {
            if ($this->db->insert("tblclient_token", $info)) {
                $resultado = true;
            } else {
                $resultado = false;
            }
        }



        if ($resultado == true) {

            $sql = "SELECT * FROM tblcontacts  where userid = " . $client->userid . " and deleted = 0";

            //echo $sql; exit;
            $contact = $this->db->query($sql)->row();
            //echo 'djd'; exit;
            //echo $company_name; exit;
            if (!is_array($contact)) {
                //echo 'entreou'; exit;
                $contact = array(
                    "datecreated" => date('Y-m-d H:i:s'),
                    "title" => 'Portal',
                    //"email" => $data->EMAIL,
                    "userid" => $client->userid,
                    "empresa_id" => $client->empresa_id
                );
                $this->db->insert("tblcontacts", $contact);
                $contact = $this->db->query($sql)->row();
            }
            // echo 'jsj'; exit;
            $company_name = get_company_option('', 'companyname', $contact->empresa_id);
            $msg = $company_name . ': Token ' . $token . ' gerado mediante solicitacao de login Wifi. Nao compartilhe para sua seguranca.';

            if ($master == false) {
                //echo 'jskj'; exit;
                $campos_email = array(
                    "data_registro" => date('Y-m-d H:i:s'),
                    "usuario_registro" => get_staff_user_id(),
                    "email_destino" => $client->email2,
                    "assunto" => 'TOKEN DE ACESSO',
                    "mensagem" => $msg,
                    "rel_type" => 'client',
                    "rel_id" => $client->userid,
                    "empresa_id " => $client->empresa_id
                );

                // print_r($campos_email); exit;
                $campos_sms = array(
                    "data_registro" => date('Y-m-d H:i:s'),
                    "usuario_registro" => get_staff_user_id(),
                    "phone_destino" => $client->phonenumber,
                    "assunto" => 'TOKEN DE ACESSO',
                    "mensagem" => $msg,
                    "rel_type" => 'client',
                    "rel_id" => $client->userid,
                    "empresa_id " => $client->empresa_id
                );
                $this->load->model('Comunicacao_model');

                $email = $this->Comunicacao_model->addEmail($campos_email);
                $sms = $this->Comunicacao_model->addSms($campos_sms);
            }
            redirect(base_url() . 'authentication/wifi/' . $hash . '?userid=' . $client->userid . '&master=' . $master . "&ts=$ts&user_hash=$user_hash");
        }
    }

    function login_new($hash = '')
    {
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

    function login($hash = '')
    {


        // echo "aqui"; exit;

        //$clientid_msg = explode('_', $client_id);
        //$client_id = $clientid_msg[0];
        //if ($clientid_msg[1]) { //se tiver mensagem
        //$view_data["msg"] = 'Senha incorreta!';
        //}
        //echo 'jdjjd'; exit;
        // redirect(base_url('portal/dashboard/index'));

        $staff = false;
        if ($this->input->post('user')) {
            $staff = true;
            $master = '&master=1';
        }
        if (is_client_logged_in()) {
            redirect(base_url('portal/dashboard/index'));
        }


        $sql = "SELECT * FROM tblclient_token
        where hash = '" . $hash . "' and utilizado = 0";

        $row = $this->db->query($sql)->row_array();

        //print_r($row); exit;
        if (is_array($row)) {

            $this->load->model('Authentication_model');

            $success = $this->Authentication_model->login_hash(
                $hash,
                $this->input->post('token'),
                $row['userid'],
                $staff,
                $this->input->post('user'),
                $this->input->post('password')
            );

            if (is_array($success) && isset($success['memberinactive'])) {
                redirect(base_url('authentication/login/' . $row['categoria_id'] . '?id=' . $row['cateirinha'] . '&hash=' . $hash . $master . '&error=1'));
            } elseif ($success == false) {
                redirect(base_url('authentication/login/' . $row['categoria_id'] . '?id=' . $row['cateirinha'] . '&hash=' . $hash . $master . '&error=1'));
            }

            $this->load->model('announcements_model');
            $this->announcements_model->set_announcements_as_read_except_last_one(get_contact_user_id());

            //maybe_redirect_to_previous_url();
            redirect(base_url('portal/dashboard/index'));
        }
    }




    function finish()
    {

        $this->db->where("id", $this->session->userdata('atendimento_id'));
        // print_r($result); exit;
        //echo '<br>';

        $info = [
            "data_encerramento" => date('Y-m-d H:i:s')
        ];

        if ($this->db->update("tbl_intranet_registro_atendimento", $info)) {

            redirect(base_url('authentication/logout_portal_paciente'));
        }
    }

    function check_recaptcha($recaptcha_post_data)
    {



        $response = $this->is_valid_recaptcha($recaptcha_post_data);

        if ($response === true) {

            return true;
        } else {

            $this->form_validation->set_message('check_recaptcha', lang("re_captcha_error-" . $response));

            return false;
        }
    }

    private function is_valid_recaptcha($recaptcha_post_data)
    {

        //load recaptcha lib

        require_once(APPPATH . "third_party/recaptcha/autoload.php");

        $recaptcha = new \ReCaptcha\ReCaptcha(get_setting("re_captcha_secret_key"));

        $resp = $recaptcha->verify($recaptcha_post_data, $_SERVER['REMOTE_ADDR']);

        if ($resp->isSuccess()) {

            return true;
        } else {



            $error = "";

            foreach ($resp->getErrorCodes() as $code) {

                $error = $code;
            }



            return $error;
        }
    }

    // check authentication

    function authenticate($email)
    {



        //don't check password if there is any error
        // $email = $this->input->post("email");

        $password = $this->input->post("password");

        if (!$this->Users_model->authenticate($email, $password)) {

            $this->form_validation->set_message('authenticate', lang("authentication_failed"));

            return false;
        }

        return true;
    }

    public function logout()
    {
        $this->load->model('authentication_model');
        $this->authentication_model->logout(false);
        hooks()->do_action('after_client_logout');
        redirect(base_url('portal/signin/index'));
    }

    //send an email to users mail with reset password link

    function send_reset_password_mail()
    {

        validate_submitted_data(array(
            "email" => "required|valid_email"
        ));

        //check if there reCaptcha is enabled
        //if reCaptcha is enabled, check the validation

        if (get_setting("re_captcha_secret_key")) {



            $response = $this->is_valid_recaptcha($this->input->post("g-recaptcha-response"));

            if ($response !== true) {



                if ($response) {

                    echo json_encode(array('success' => false, 'message' => lang("re_captcha_error-" . $response)));
                } else {

                    echo json_encode(array('success' => false, 'message' => lang("re_captcha_expired")));
                }



                return false;
            }
        }







        $email = $this->input->post("email");

        $existing_user = $this->Users_model->is_email_exists($email);

        //send reset password email if found account with this email

        if ($existing_user) {

            $email_template = $this->Email_templates_model->get_final_template("reset_password");

            $parser_data["ACCOUNT_HOLDER_NAME"] = $existing_user->first_name . " " . $existing_user->last_name;

            $parser_data["SIGNATURE"] = $email_template->signature;

            $parser_data["LOGO_URL"] = get_logo_url();

            $parser_data["SITE_URL"] = get_uri();

            $key = encode_id($this->encryption->encrypt($existing_user->email . '|' . (time() + (24 * 60 * 60))), "reset_password");

            $parser_data['RESET_PASSWORD_URL'] = get_uri("signin/new_password/" . $key);

            $message = $this->parser->parse_string($email_template->message, $parser_data, TRUE);

            if (send_app_mail($email, $email_template->subject, $message)) {

                echo json_encode(array('success' => true, 'message' => lang("reset_info_send")));
            } else {

                echo json_encode(array('success' => false, 'message' => lang('error_occurred')));
            }
        } else {

            echo json_encode(array("success" => false, 'message' => lang("no_acount_found_with_this_email")));

            return false;
        }
    }

    //show forgot password recovery form

    function request_reset_password()
    {

        $view_data["form_type"] = "request_reset_password";

        $this->load->view('signin/index', $view_data);
    }

    //when user clicks to reset password link from his/her email, redirect to this url

    function new_password($key)
    {

        $valid_key = $this->is_valid_reset_password_key($key);

        if ($valid_key) {

            $email = get_array_value($valid_key, "email");

            if ($this->Users_model->is_email_exists($email)) {

                $view_data["key"] = $key;

                $view_data["form_type"] = "new_password";

                $this->load->view('signin/index', $view_data);

                return false;
            }
        }



        //else show error

        $view_data["heading"] = "Invalid Request";

        $view_data["message"] = "The key has expaired or something went wrong!";

        $this->load->view("errors/html/error_general", $view_data);
    }

    //check valid key

    function redefinir_senha($msg = 0)
    {
        $view_data['smg_erro'] = $msg;

        $this->load->view('portal/signin/lost', $view_data);
    }

    function confirmar()
    {

        $email = $this->input->post("email");

        $cpf = $this->input->post('cpf');

        $cpf = str_replace(".", "", $cpf);

        $cpf = str_replace("-", "", $cpf);

        $data = $this->input->post("daten");

        $dados_user = $this->Portal_clients_model->is_cpf_exists($cpf);
        if ($dados_user) {

            $email_bd = $dados_user->email;

            $id = $dados_user->userid;

            if ($dados_user->vat == $cpf) {

                if (strtolower($email_bd) == strtolower($email)) {
                    if ($dados_user->nascimento == $data) {
                        $view_data["id"] = $id;

                        $this->load->view('portal/signin/lost', $view_data);
                    } else {
                        $msg = ('Data de nascimento incorreta');

                        $this->redefinir_senha($msg);
                    }
                } else {
                    $msg = ('Email incorreto');

                    $this->redefinir_senha($msg);
                }
            } else {
                $msg = ('Alguma informação incorreta');

                $this->redefinir_senha($msg);
            }
        } else {
            $msg = ('Usuário não encontrado');

            $this->redefinir_senha($msg);
        }
    }

    function redefinir_confirmed()
    {



        $senha1 = $this->input->post("senha1");

        $senha2 = $this->input->post("senha2");

        $id = $this->input->post("id");

        $senhac = app_hash_password($senha1);

        $dados['password'] = $senhac;

        $dados['senha_original'] = $senha1;

        if ($senha1 == $senha2) {

            $this->db->where('userid', $id);

            $deucerto = $this->db->update('tblcontacts', $dados);

            if ($deucerto) {

                $msg = 'senha';
                $this->index($msg);
            }
        } else {

            $view_data["id"] = $id;

            $view_data['smg_erro'] = 'As senhas não correspondem';

            $this->load->view('portal/signin/lost', $view_data);
        }
    }

    // cadastrar login



    function add_user_view($client_id = '')
    {

        //echo $client_id; exit;
        $details = $this->Portal_clients_model->info_contacts_hash($client_id);

        //print_r($details); exit;
        $view_data["info"] = $details;

        $this->load->view('portal/signin/add_user', $view_data);
    }

    function add_user()
    {



        $client_id = $this->input->post('id');

        $email = $this->input->post('email');

        $senha = $this->input->post('password');

        $senha2 = $this->input->post('password2');

        if ($client_id) {



            $resposta = $this->Portal_clients_model->is_email_exists($email);

            if ($senha == $senha2) {

                if (!$resposta) {

                    $data = array(
                        "userid" => "$client_id",
                        "email" => "$email",
                        "senha_original" => "$senha",
                        "password" => app_hash_password($senha),
                        "deleted" => 0,
                        //"status" => "active",
                        "datecreated" => date('Y-m-d H:i:s')
                    );

                    //print_r($data); exit;
                    $datac = array(
                        "email" => "$email"
                    );

                    $blabla = $this->db->insert('tblcontacts', $data);

                    //echo 'aqui'; exit;
                    $this->db->where('userid', $client_id);

                    $this->db->update('tblclients', $datac);

                    if ($blabla) {

                        $view_data["smg_erro"] = '';
                        $info_perfil = $this->Portal_clients_model->info_assinatura_id($client_id);

                        $view_data["smg_success"] = 'Login criado com sucesso!';

                        redirect('portal/signin/login/' . $info_perfil->hash_client);
                    } else {

                        $info_perfil = $this->Portal_clients_model->info_assinatura_id($client_id);

                        $view_data["info"] = $info_perfil;

                        $view_data["smg_erro"] = 'Algo falhou, tente novamente.';

                        $view_data["smg_success"] = '';

                        $this->load->view("portal/signin/add_user", $view_data);
                    }
                } else {

                    $info_perfil = $this->Portal_clients_model->info_assinatura_id($client_id);

                    $view_data["info"] = $info_perfil;

                    $view_data["smg_erro"] = 'Email já utilizado.';

                    $view_data["smg_success"] = '';

                    $this->load->view("portal/signin/add_user", $view_data);
                }
            } else {

                $info_perfil = $this->Portal_clients_model->info_assinatura_id($client_id);

                $view_data["info"] = $info_perfil;

                $view_data["smg_erro"] = 'As senhas devem ser iguais.';

                $view_data["smg_success"] = '';

                $this->load->view("portal/signin/add_user", $view_data);

                //exit;
            }
        } else {

            redirect('portal/signin');
        }
    }

    public function valida_cadastro(){
        $cpf = $this->input->post('cpf');
        $sql = "SELECT * from tblclients_portal where cpf = '" . $cpf . "' and deleted = 0 and active = 1";
        $cadastro = $this->db->query($sql)->result_array();

        if(is_array($cadastro)){
            echo json_encode(array('alert' => 'tem_cadastro'));
        } else{
            echo json_encode(array('alert' => 'nao_tem_cadastro'));
        }
    }
}
