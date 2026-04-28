<?php

defined('BASEPATH') or exit('No direct script access allowed');

use Sonata\GoogleAuthenticator\GoogleAuthenticator;

class Authentication_model extends App_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_autologin');
        $this->autologin();
    }

    public function valida($email)
    {
        $table = db_prefix() . 'staff';
        $this->db->where('email', $email);
        $user = $this->db->get($table)->row();

        if ($user) {
            // $user_data['empresa_id'] = $user->empresa_id;
            //$this->session->set_userdata($user_data);

            $this->session->set_userdata('empresa_id', $user->empresa_id);

            return $user->staffid;
        } else {
            return false;
        }
    }

    public function valida_login($user = '')
    {
        $table = db_prefix() . 'staff';
        $this->db->where('user', $user);
        $user = $this->db->get($table)->row();

        if ($user) {

            $this->session->set_userdata('empresa_id', $user->empresa_id);

            return $user->staffid;
        } else {
            return false;
        }
    }

    /**
     * @param  string Email address for login
     * @param  string User Password
     * @param  boolean Set cookies for user if remember me is checked
     * @param  boolean Is Staff Or Client
     * @return boolean if not redirect url found, if found redirect to the url
     */
    public function login($email, $password, $remember, $staff)
    {
        if ((!empty($email)) and (!empty($password))) {
            $table = db_prefix() . 'contacts';
            $_id = 'id';
            if ($staff == true) {
                $table = db_prefix() . 'staff';
                $_id = 'staffid';
            }
            $this->db->where('email', $email);
            $user = $this->db->get($table)->row();
            //print_r($user); exit;
            if ($user) {
                // Email is okey lets check the password now
                if (!app_hasher()->CheckPassword($password, $user->password)) {
                    hooks()->do_action('failed_login_attempt', [
                        'user' => $user,
                        'is_staff_member' => $staff,
                    ]);

                    log_activity('Failed Login Attempt [Email: ' . $email . ', Is Staff Member: ' . ($staff == true ? 'Yes' : 'No') . ', IP: ' . $this->input->ip_address() . ']');

                    // Password failed, return
                    return false;
                }
            } else {
                hooks()->do_action('non_existent_user_login_attempt', [
                    'email' => $email,
                    'is_staff_member' => $staff,
                ]);

                log_activity('Non Existing User Tried to Login [Email: ' . $email . ', Is Staff Member: ' . ($staff == true ? 'Yes' : 'No') . ', IP: ' . $this->input->ip_address() . ']');

                return false;
            }


            if ($user->active == 0) {
                hooks()->do_action('inactive_user_login_attempt', [
                    'user' => $user,
                    'is_staff_member' => $staff,
                ]);
                log_activity('Inactive User Tried to Login [Email: ' . $email . ', Is Staff Member: ' . ($staff == true ? 'Yes' : 'No') . ', IP: ' . $this->input->ip_address() . ']');

                return [
                    'memberinactive' => true,
                ];
            }

            $twoFactorAuth = false;
            if ($staff == true) {
                $twoFactorAuth = $user->two_factor_auth_enabled == 0 ? false : true;

                if (!$twoFactorAuth) {
                    hooks()->do_action('before_staff_login', [
                        'email' => $email,
                        'userid' => $user->$_id,
                    ]);

                    $user_data = [
                        'staff_user_id' => $user->$_id,
                        'staff_logged_in' => true,
                    ];
                } else {
                    $user_data = [];
                    $user_data['tfa_staffid'] = $user->staffid;
                    if ($remember) {
                        $user_data['tfa_remember'] = true;
                    }
                }
            } else {
                hooks()->do_action('before_client_login', [
                    'email' => $email,
                    'userid' => $user->userid,
                    'contact_user_id' => $user->$_id,
                ]);

                $user_data = [
                    'client_user_id' => $user->userid,
                    'contact_user_id' => $user->$_id,
                    'client_logged_in' => true,
                ];
            }

            $user_data['empresa_id'] = $user->empresa_id;
            $this->session->set_userdata($user_data);

            if (!$twoFactorAuth) {


                if ($remember) {
                    $this->create_autologin($user->$_id, $staff);
                }

                $this->update_login_info($user->$_id, $staff);
            } else {
                return ['two_factor_auth' => true, 'user' => $user];
            }

            return true;
        }

        return false;
    }

    public function login_portal_paciente($cpf, $password, $hash, $portal_paciente = '')
    {

        //   echo $cpf . ' - ' . $password;exit;
        
        $this->db->where('hash', $hash);
        $token_row = $this->db->get('tblclient_token')->row();

       // print_r($token_row); exit;

        $password = hash('sha256', $password);

       // echo "$password"; exit;


        if ((!empty($cpf)) and (!empty($password))) {
            //   echo "aqui"; exit;
           // $table = db_prefix() . 'clients';
            $_id = 'contact_id';

            $sql = "SELECT a.*, (SELECT id FROM tblcontacts where userid = a.id order by id desc limit 1) as contact_id FROM tblclients_portal a
            where a.cpf = '$cpf' and a.deleted = 0 and a.active = 1";

          //  $this->db->where('vat', $cpf);
            $user = $this->db->query($sql)->row();
          //  print_r($user); exit;
            if ($user) {
                // echo "aqui"; exit;
                // Email is okey lets check the password now
                // if (!app_hasher()->CheckPassword($password, $user->password)) {
                if ($password != $user->senha) {
                    // echo "senha invalida"; exit;
                    hooks()->do_action('failed_login_attempt', [
                        'user' => $user,
                        'is_staff_member' => $staff,
                    ]);

                    log_activity('Failed Login Attempt [Email: ' . $email . ', Is Staff Member: ' . ($staff == true ? 'Yes' : 'No') . ', IP: ' . $this->input->ip_address() . ']');

                    // Password failed, return
                    return false;
                }
            } else {
                hooks()->do_action('non_existent_user_login_attempt', [
                    'email' => $email,
                    'is_staff_member' => $staff,
                ]);

                log_activity('Non Existing User Tried to Login [Email: ' . $email . ', Is Staff Member: ' . ($staff == true ? 'Yes' : 'No') . ', IP: ' . $this->input->ip_address() . ']');

                return false;
            }



            if ($staff == true) {
                hooks()->do_action('before_client_login', [
                    'email' => $user->email,
                    'userid' => $user->id,
                    'contact_user_id' => $user->$_id,
                ]);

                $user_data = [
                    'client_user_id' => $user->userid,
                    'contact_user_id' => $user->$_id,
                    'client_logged_in' => true,
                    'staff_user_id' => $staff_row->staffid,
                ];
            } else {
                hooks()->do_action('before_client_login', [
                    'email' => $user->email,
                    'userid' => $user->userid,
                    'contact_user_id' => $user->$_id,
                ]);

                $user_data = [
                    'client_user_id' => $user->userid,
                    'contact_user_id' => $user->$_id,
                    'client_logged_in' => true,
                ];
            }

            $info = [
                //"subject" => $this->input->post('assunto'),
                "categoria_id" => 0,
                "canal_atendimento_id" => 0,
                "contato" => $user->telefone,
                "email" => $user->email,
                "client_id" => $user->id,
                "empresa_id" => $user->empresa_id,
            ];

            /*   if ($staff == true) {
                $info['user_created'] = $staff_row->staffid;
                $data_new_protocolo['staffid'] = $staff_row->staffid;
            }*/
            //print_r($info); exit;


            //  $user_data['categoria_id'] = $user->categoria_id;
            $this->load->model('Atendimentos_model');
            $id = $this->Atendimentos_model->add($info, $portal_paciente);
            $atendimento = $this->Atendimentos_model->get_ra_by_id($id);
            // print_r($atendimento); exit;

            $data_new_protocolo['protocolo'] = $atendimento->protocolo;
            $data_new_protocolo['atendimento_id'] = $atendimento->id;
            $data_new_protocolo['utilizado'] = 1;


            $this->db->where('id', $token_row->id);
            //print_r($data_new_protocolo); exit;
            $this->db->update("tblclient_token", $data_new_protocolo);
            //echo $token_row->id; exit;
            $this->db->where('id', $token_row->id);
            $token_row = $this->db->get('tblclient_token')->row();

           // print_r($token_row); exit;

           //  print_r($user); exit;

            $user_data = [
                'client_user_id' => $user->id,
                'contact_user_id' => $user->contact_id,
                'client_logged_in' => 1,
                'portal_paciente' => 1,               
            ];

            //  [client_user_id] => 380212 [contact_user_id] => 34 [client_logged_in] => 1 [empresa_id] => 4 [categoria_id] => 9 [protocolo] => 3119612024121650000362 [atendimento_id] => 153499 [cd_pessoa] => 5188473 [carteirinha] => 00790843920050000 [coparticipation] => 1 [pf] => 1
            $user_data['empresa_id'] = $user->empresa_id;

            $user_data['protocolo'] = $token_row->protocolo;
            $user_data['atendimento_id'] = $token_row->atendimento_id;
           // $this->load->model("Clients_model");
           // $user = $this->Clients_model->get($user->userid);
         //   $user_data['cd_pessoa'] = $user->cd_pessoa;
            $user_data['carteirinha'] = $user->carteirinha;
          //  $user_data['coparticipation'] = false;
           /// if ($user->coparticipation == 1) {
           //     $user_data['coparticipation'] = true;
         //   }
            $user_data['pf'] = true;
          //  if ($user->type == 2) {
          //      $user_data['pf'] = false;
          //  }

           // print_r($user_data); exit;
            $this->session->set_userdata($user_data);

            return true;
        }
    }

    public function login_hash($hash, $token, $userid, $staff = false, $staff_user = '', $staff_password = '')
    {
        // echo " $hash   <br> $token <br> $userid <br> $staff <br> $staff_user <br> $staff_password"; exit;
        //echo $token; exit;
        if (((!empty($hash)) and (!empty($token))) || ((!empty($hash)) and (!empty($staff_user)))) {

            $table = db_prefix() . 'contacts';
            $_id = 'id';
            $this->db->where('userid', $userid);
            $user = $this->db->get($table)->row();

            // print_r($user); exit;

            $this->db->where('hash', $hash);
            $token_row = $this->db->get('tblclient_token')->row();

            if ($user) {
                // Email is okey lets check the password now
                if (($token != $token_row->token) and $staff == false) {
                    
                    hooks()->do_action('failed_login_attempt', [
                        'user' => $user,
                        'is_staff_member' => $staff,
                    ]);

                    log_activity('Failed Login Attempt [Email: ' . $user->email . ', Is Staff Member: ' . ($staff == true ? 'Yes' : 'No') . ', IP: ' . $this->input->ip_address() . ']');

                    // Password failed, return
                    return false;
                }
            } else {
                hooks()->do_action('non_existent_user_login_attempt', [
                    'email' => $user->email,
                    'is_staff_member' => $staff,
                ]);

                log_activity('Non Existing User Tried to Login [Email: ' . $user->email . ', Is Staff Member: ' . ($staff == true ? 'Yes' : 'No') . ', IP: ' . $this->input->ip_address() . ']');

                return false;
            }

        

            if ($user->active == 0) {
                hooks()->do_action('inactive_user_login_attempt', [
                    'user' => $user,
                    'is_staff_member' => $staff,
                ]);
                log_activity('Inactive User Tried to Login [Email: ' . $user->email . ', Is Staff Member: ' . ($staff == true ? 'Yes' : 'No') . ', IP: ' . $this->input->ip_address() . ']');

                return [
                    'memberinactive' => true,
                ];
            }

            
            if ($staff == true) {
                // echo 'jsjj'; exit;
                $this->db->where('user', $staff_user);
                $this->db->where('master_portal', 1);
                $staff_row = $this->db->get('tblstaff')->row();
                if ($staff_row) {

                    $ch = curl_init();

                    curl_setopt_array($ch, array(
                        CURLOPT_URL => 'http://189.2.65.2/sigplus/api/Login',
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => '',
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => 'POST',
                        CURLOPT_POSTFIELDS => '{ "login": "' . $staff_user . '", 
                                "senha": "' . $staff_password . '"
                                }',
                        CURLOPT_HTTPHEADER => array(
                            'Content-Type: application/json; charset=utf-8'
                        ),
                    ));

                    $response = curl_exec($ch);
                    $result_ad = json_decode($response);

                    //print_r($result_ad); exit;
                    if (!$result_ad == 1) {
                        log_activity('Failed Login Attempt [Email: ' . $user->email . ', Is Staff Member: ' . ($staff == true ? 'Yes' : 'No') . ', IP: ' . $this->input->ip_address() . ']');
                        return false;
                    }

                    //                    if (!app_hasher()->CheckPassword($staff_password, $staff_row->password)) {;;;;;;;;;;;;;;;;;;;;;;
                    //                        log_activity('Failed Login Attempt [Email: ' . $user->email . ', Is Staff Member: ' . ($staff == true ? 'Yes' : 'No') . ', IP: ' . $this->input->ip_address() . ']');
                    //                        return false;
                    //                    }
                } else {
                    log_activity('Non Existing User Tried to Login [Email: ' . $user->email . ', Is Staff Member: ' . ($staff == true ? 'Yes' : 'No') . ', IP: ' . $this->input->ip_address() . ']');
                    return false;
                }
            } 
          
            if ($staff == true) {
                hooks()->do_action('before_client_login', [
                    'email' => $user->email,
                    'userid' => $user->userid,
                    'contact_user_id' => $user->$_id,
                ]);

                $user_data = [
                    'client_user_id' => $user->userid,
                    'contact_user_id' => $user->$_id,
                    'client_logged_in' => true,
                    'staff_user_id' => $staff_row->staffid,
                ];
            } else {
                hooks()->do_action('before_client_login', [
                    'email' => $user->email,
                    'userid' => $user->userid,
                    'contact_user_id' => $user->$_id,
                ]);

                $user_data = [
                    'client_user_id' => $user->userid,
                    'contact_user_id' => $user->$_id,
                    'client_logged_in' => true,
                ];
            }

            $info = [
                //"subject" => $this->input->post('assunto'),
                "categoria_id" => 0,
                "canal_atendimento_id" => 0,
                "contato" => $token_row->telefone,
                "email" => $token_row->email,
                "client_id" => $token_row->userid,
                "empresa_id" => $token_row->empresa_id,
            ];

            if ($staff == true) {
                $info['user_created'] = $staff_row->staffid;
                $data_new_protocolo['staffid'] = $staff_row->staffid;
            }
            //print_r($info); exit;

            $user_data['empresa_id'] = $token_row->empresa_id;
            $user_data['categoria_id'] = $token_row->categoria_id;
            $this->session->set_userdata($user_data);

            $this->load->model('Atendimentos_model');
            $id = $this->Atendimentos_model->add($info);
            $atendimento = $this->Atendimentos_model->get_ra_by_id($id);
            // print_r($atendimento); exit;

            $data_new_protocolo['protocolo'] = $atendimento->protocolo;
            $data_new_protocolo['atendimento_id'] = $atendimento->id;
            $data_new_protocolo['utilizado'] = 1;


            $this->db->where('id', $token_row->id);
            //print_r($data_new_protocolo); exit;
            $this->db->update("tblclient_token", $data_new_protocolo);
            //echo $token_row->id; exit;
            $this->db->where('id', $token_row->id);
            $token_row = $this->db->get('tblclient_token')->row();

            $user_data['protocolo'] = $token_row->protocolo;
            $user_data['atendimento_id'] = $token_row->atendimento_id;
            $this->load->model("Clients_model");
            $user = $this->Clients_model->get($user->userid);
            $user_data['cd_pessoa'] = $user->cd_pessoa;
            $user_data['carteirinha'] = $user->numero_carteirinha;
            $user_data['coparticipation'] = false;
            if ($user->coparticipation == 1) {
                $user_data['coparticipation'] = true;
            }
            $user_data['pf'] = true;
            if ($user->type == 2) {
                $user_data['pf'] = false;
            }

            // print_r($user_data); exit;

            $this->session->set_userdata($user_data);

            return true;
        }
        return false;
    }

    public function login_login($usuario)
    {
        //echo $usuario; exit;
        if ((!empty($usuario))) {
            $staff = true;
            $table = db_prefix() . 'staff';

            $this->db->where('staffid', $usuario);
            $user = $this->db->get($table)->row();

            if ($user) {
            } else {
                hooks()->do_action('non_existent_user_login_attempt', [
                    'usuario' => $usuario,
                    'is_staff_member' => true,
                ]);

                log_activity('Non Existing User Tried to Login [Usuario: ' . $usuario . ', Is Staff Member: ' . ($staff == true ? 'Yes' : 'No') . ', IP: ' . $this->input->ip_address() . ']');

                return false;
            }


            if ($user->active == 0) {
                hooks()->do_action('inactive_user_login_attempt', [
                    'usuario' => $usuario,
                    'is_staff_member' => true,
                ]);
                log_activity('Inactive User Tried to Login [Usuario: ' . $usuario . ', Is Staff Member: ' . ($staff == true ? 'Yes' : 'No') . ', IP: ' . $this->input->ip_address() . ']');

                return [
                    'memberinactive' => true,
                ];
            }


            $twoFactorAuth = $user->two_factor_auth_enabled == 0 ? false : true;

            if (!$twoFactorAuth) {

                hooks()->do_action('before_staff_login', [
                    'email' => $user->email,
                    'userid' => $user->staffid,
                ]);

                $user_data = [
                    'staff_user_id' => $user->staffid,
                    'staff_logged_in' => true,
                ];
            } else {
                $user_data = [];
                $user_data['tfa_staffid'] = $user->staffid;
                if ($remember) {
                    $user_data['tfa_remember'] = true;
                }
            }

            $user_data['empresa_id'] = $user->empresa_id;
            $this->session->set_userdata($user_data);

            if (!$twoFactorAuth) {
                $this->update_login_info($user->staffid, $staff);
            } else {

                return ['two_factor_auth' => true, 'user' => $user];
            }

            return true;
        }

        return false;
    }

    /**
     * @param  boolean If Client or Staff
     * @return none
     */
    public function logout($staff = true, $out = false)
    {
        if ($out == true) {
            $this->delete_autologin($staff);

            if (is_client_logged_in()) {
                hooks()->do_action('before_contact_logout', get_client_user_id());

                $this->session->unset_userdata('client_user_id');
                $this->session->unset_userdata('client_logged_in');
            } else {
                hooks()->do_action('before_staff_logout', get_staff_user_id());

                $this->session->unset_userdata('staff_user_id');
                $this->session->unset_userdata('staff_logged_in');
            }

            $this->session->sess_destroy();
        }
    }

    /**
     * @param  integer ID to create autologin
     * @param  boolean Is Client or Staff
     * @return boolean
     */
    private function create_autologin($user_id, $staff)
    {
        $this->load->helper('cookie');
        $key = substr(md5(uniqid(rand() . get_cookie($this->config->item('sess_cookie_name')))), 0, 16);
        $this->user_autologin->delete($user_id, $key, $staff);
        if ($this->user_autologin->set($user_id, md5($key), $staff)) {
            set_cookie([
                'name' => 'autologin',
                'value' => serialize([
                    'user_id' => $user_id,
                    'key' => $key,
                ]),
                'expire' => 60 * 60 * 24 * 31 * 2, // 2 months
            ]);

            return true;
        }

        return false;
    }

    /**
     * @param  boolean Is Client or Staff
     * @return none
     */
    private function delete_autologin($staff)
    {
        $this->load->helper('cookie');
        if ($cookie = get_cookie('autologin', true)) {
            $data = unserialize($cookie);
            $this->user_autologin->delete($data['user_id'], md5($data['key']), $staff);
            delete_cookie('autologin', 'aal');
        }
    }

    /**
     * @return boolean
     * Check if autologin found
     */
    public function autologin()
    {
        if (!is_logged_in()) {
            $this->load->helper('cookie');
            if ($cookie = get_cookie('autologin', true)) {
                $data = unserialize($cookie);
                if (isset($data['key']) and isset($data['user_id'])) {
                    if (!is_null($user = $this->user_autologin->get($data['user_id'], md5($data['key'])))) {
                        // Login user
                        if ($user->staff == 1) {
                            $user_data = [
                                'staff_user_id' => $user->id,
                                'staff_logged_in' => true,
                            ];
                        } else {
                            // Get the customer id
                            $this->db->select('userid');
                            $this->db->where('id', $user->id);
                            $contact = $this->db->get(db_prefix() . 'contacts')->row();

                            $user_data = [
                                'client_user_id' => $contact->userid,
                                'contact_user_id' => $user->id,
                                'client_logged_in' => true,
                            ];
                        }
                        $this->session->set_userdata($user_data);
                        // Renew users cookie to prevent it from expiring
                        set_cookie([
                            'name' => 'autologin',
                            'value' => $cookie,
                            'expire' => 60 * 60 * 24 * 31 * 2, // 2 months
                        ]);
                        $this->update_login_info($user->id, $user->staff);

                        return true;
                    }
                }
            }
        }

        return false;
    }

    /**
     * @param  integer ID
     * @param  boolean Is Client or Staff
     * @return none
     * Update login info on autologin
     */
    private function update_login_info($user_id, $staff)
    {
        $table = db_prefix() . 'contacts';
        $_id = 'id';
        if ($staff == true) {
            $table = db_prefix() . 'staff';
            $_id = 'staffid';
        }
        $this->db->set('last_ip', $this->input->ip_address());
        $this->db->set('last_login', date('Y-m-d H:i:s'));
        $this->db->where($_id, $user_id);
        $this->db->update($table);
    }

    /**
     * Send set password email for contacts
     * @param string $email
     */
    public function set_password_email($email)
    {
        $this->db->where('email', $email);
        $user = $this->db->get(db_prefix() . 'contacts')->row();

        if ($user) {
            if ($user->active == 0) {
                return [
                    'memberinactive' => true,
                ];
            }

            $new_pass_key = app_generate_hash();
            $this->db->where('id', $user->id);
            $this->db->update(db_prefix() . 'contacts', [
                'new_pass_key' => $new_pass_key,
                'new_pass_key_requested' => date('Y-m-d H:i:s'),
            ]);
            if ($this->db->affected_rows() > 0) {
                $data['new_pass_key'] = $new_pass_key;
                $data['userid'] = $user->id;
                $data['email'] = $email;

                $sent = send_mail_template('customer_contact_set_password', $user, $data);

                if ($sent) {
                    hooks()->do_action('set_password_email_sent', ['is_staff_member' => false, 'user' => $user]);

                    return true;
                }

                return false;
            }

            return false;
        }

        return false;
    }

    /**
     * @param  string Email from the user
     * @param  Is Client or Staff
     * @return boolean
     * Generate new password key for the user to reset the password.
     */
    public function forgot_password($email, $staff = false)
    {
        $table = db_prefix() . 'contacts';
        $_id = 'id';
        if ($staff == true) {
            $table = db_prefix() . 'staff';
            $_id = 'staffid';
        }
        $this->db->where('email', $email);
        $user = $this->db->get($table)->row();

        if ($user) {
            if ($user->active == 0) {
                return [
                    'memberinactive' => true,
                ];
            }

            $new_pass_key = app_generate_hash();
            $this->db->where($_id, $user->$_id);
            $this->db->update($table, [
                'new_pass_key' => $new_pass_key,
                'new_pass_key_requested' => date('Y-m-d H:i:s'),
            ]);

            if ($this->db->affected_rows() > 0) {
                $data['new_pass_key'] = $new_pass_key;
                $data['staff'] = $staff;
                $data['userid'] = $user->$_id;
                $merge_fields = [];

                if ($staff == false) {
                    $sent = send_mail_template('customer_contact_forgot_password', $user->email, $user->userid, $user->$_id, $data);
                } else {
                    $sent = send_mail_template('staff_forgot_password', $user->email, $user->$_id, $data);
                }

                if ($sent) {
                    hooks()->do_action('forgot_password_email_sent', ['is_staff_member' => $staff, 'user' => $user]);

                    return true;
                }

                return false;
            }

            return false;
        }

        return false;
    }

    /**
     * Update user password from forgot password feature or set password
     * @param boolean $staff        is staff or contact
     * @param mixed $userid
     * @param string $new_pass_key the password generate key
     * @param string $password     new password
     */
    public function set_password($staff, $userid, $new_pass_key, $password)
    {
        if (!$this->can_set_password($staff, $userid, $new_pass_key)) {
            return [
                'expired' => true,
            ];
        }

        $password = app_hash_password($password);
        $table = db_prefix() . 'contacts';
        $_id = 'id';
        if ($staff == true) {
            $table = db_prefix() . 'staff';
            $_id = 'staffid';
        }
        $this->db->where($_id, $userid);
        $this->db->where('new_pass_key', $new_pass_key);
        $this->db->update($table, [
            'password' => $password,
        ]);
        if ($this->db->affected_rows() > 0) {
            log_activity('User Set Password [User ID: ' . $userid . ', Is Staff Member: ' . ($staff == true ? 'Yes' : 'No') . ', IP: ' . $this->input->ip_address() . ']');
            $this->db->set('new_pass_key', null);
            $this->db->set('new_pass_key_requested', null);
            $this->db->set('last_password_change', date('Y-m-d H:i:s'));
            $this->db->where($_id, $userid);
            $this->db->where('new_pass_key', $new_pass_key);
            $this->db->update($table);

            return true;
        }

        return null;
    }

    /**
     * @param  boolean Is Client or Staff
     * @param  integer ID
     * @param  string
     * @param  string
     * @return boolean
     * User reset password after successful validation of the key
     */
    public function reset_password($staff, $userid, $new_pass_key, $password)
    {
        if (!$this->can_reset_password($staff, $userid, $new_pass_key)) {
            return [
                'expired' => true,
            ];
        }
        $password = app_hash_password($password);
        $table = db_prefix() . 'contacts';
        $_id = 'id';
        if ($staff == true) {
            $table = db_prefix() . 'staff';
            $_id = 'staffid';
        }

        $this->db->where($_id, $userid);
        $this->db->where('new_pass_key', $new_pass_key);
        $this->db->update($table, [
            'password' => $password,
        ]);
        if ($this->db->affected_rows() > 0) {
            log_activity('User Reseted Password [User ID: ' . $userid . ', Is Staff Member: ' . ($staff == true ? 'Yes' : 'No') . ', IP: ' . $this->input->ip_address() . ']');
            $this->db->set('new_pass_key', null);
            $this->db->set('new_pass_key_requested', null);
            $this->db->set('last_password_change', date('Y-m-d H:i:s'));
            $this->db->where($_id, $userid);
            $this->db->where('new_pass_key', $new_pass_key);
            $this->db->update($table);
            $this->db->where($_id, $userid);
            $user = $this->db->get($table)->row();

            $merge_fields = [];
            if ($staff == false) {
                $sent = send_mail_template('customer_contact_password_resetted', $user->email, $user->userid, $user->$_id);
            } else {
                $sent = send_mail_template('staff_password_resetted', $user->email, $user->$_id);
            }

            if ($sent) {
                return true;
            }
        }

        return null;
    }

    /**
     * @param  integer Is Client or Staff
     * @param  integer ID
     * @param  string Password reset key
     * @return boolean
     * Check if the key is not expired or not exists in database
     */
    public function can_reset_password($staff, $userid, $new_pass_key)
    {
        $table = db_prefix() . 'contacts';
        $_id = 'id';
        if ($staff == true) {
            $table = db_prefix() . 'staff';
            $_id = 'staffid';
        }

        $this->db->where($_id, $userid);
        $this->db->where('new_pass_key', $new_pass_key);
        $user = $this->db->get($table)->row();

        if ($user) {
            $timestamp_now_minus_1_hour = time() - (60 * 60);
            $new_pass_key_requested = strtotime($user->new_pass_key_requested);
            if ($timestamp_now_minus_1_hour > $new_pass_key_requested) {
                return false;
            }

            return true;
        }

        return false;
    }

    /**
     * @param  integer Is Client or Staff
     * @param  integer ID
     * @param  string Password reset key
     * @return boolean
     * Check if the key is not expired or not exists in database
     */
    public function can_set_password($staff, $userid, $new_pass_key)
    {
        $table = db_prefix() . 'contacts';
        $_id = 'id';
        if ($staff == true) {
            $table = db_prefix() . 'staff';
            $_id = 'staffid';
        }
        $this->db->where($_id, $userid);
        $this->db->where('new_pass_key', $new_pass_key);
        $user = $this->db->get($table)->row();
        if ($user) {
            $timestamp_now_minus_48_hour = time() - (3600 * 48);
            $new_pass_key_requested = strtotime($user->new_pass_key_requested);
            if ($timestamp_now_minus_48_hour > $new_pass_key_requested) {
                return false;
            }

            return true;
        }

        return false;
    }

    /**
     * Get user from database by 2 factor authentication code
     * @param  string $code authentication code to search for
     * @return object
     */
    public function get_user_by_two_factor_auth_code($code)
    {
        $this->db->where('two_factor_auth_code', $code);

        return $this->db->get(db_prefix() . 'staff')->row();
    }

    /**
     * Login user via two factor authentication
     * @param  object $user user object
     * @return boolean
     */
    public function two_factor_auth_login($user)
    {
        hooks()->do_action('before_staff_login', [
            'email' => $user->email,
            'userid' => $user->staffid,
        ]);

        $this->session->set_userdata(
            [
                'staff_user_id' => $user->staffid,
                'staff_logged_in' => true,
            ]
        );

        $remember = null;
        if ($this->session->has_userdata('tfa_remember')) {
            $remember = true;
            $this->session->unset_userdata('tfa_remember');
        }

        if ($remember) {
            $this->create_autologin($user->staffid, true);
        }

        $this->update_login_info($user->staffid, true);

        return true;
    }

    /**
     * Check if 2 factor authentication code is valid for usage
     * @param  string  $code auth code
     * @return boolean
     */
    public function is_two_factor_code_valid($code)
    {
        $this->db->select('two_factor_auth_code_requested');
        $this->db->where('two_factor_auth_code', $code);
        $user = $this->db->get(db_prefix() . 'staff')->row();

        // Code not exists because no user is found
        if (!$user) {
            return false;
        }

        $timestamp_minus_1_hour = time() - (60 * 60);
        $new_code_key_requested = strtotime($user->two_factor_auth_code_requested);
        // The code is older then 1 hour and its not valid
        if ($timestamp_minus_1_hour > $new_code_key_requested) {
            return false;
        }
        // Code is valid
        return true;
    }

    /**
     * Clears 2 factor authentication code in database
     * @param  mixed $id
     * @return boolean
     */
    public function clear_two_factor_auth_code($id)
    {
        $this->db->where('staffid', $id);
        $this->db->update(db_prefix() . 'staff', [
            'two_factor_auth_code' => null,
        ]);

        return true;
    }

    /**
     * Set 2 factor authentication code for staff member
     * @param mixed $id staff id
     */
    public function set_two_factor_auth_code($id)
    {
        $code = generate_two_factor_auth_key();
        $code .= $id;

        $this->db->where('staffid', $id);
        $this->db->update(db_prefix() . 'staff', [
            'two_factor_auth_code' => $code,
            'two_factor_auth_code_requested' => date('Y-m-d H:i:s'),
        ]);

        return $code;
    }

    public function get_qr($System_name)
    {
        $staff = get_staff(get_staff_user_id());
        $g = new GoogleAuthenticator();
        $secret = $g->generateSecret();
        $username = urlencode($staff->email);
        $url = \Sonata\GoogleAuthenticator\GoogleQrUrl::generate($username, $secret, $System_name);

        return ['qrURL' => $url, 'secret' => $secret];
    }

    public function set_google_two_factor($secret)
    {
        $id = get_staff_user_id();
        $secret = $this->encrypt($secret);

        $this->db->where('staffid', $id);
        $success = $this->db->update(db_prefix() . 'staff', [
            'two_factor_auth_enabled' => 2,
            'google_auth_secret' => $secret,
        ]);

        if ($success) {
            return true;
        }

        return false;
    }

    public function is_google_two_factor_code_valid($code, $secret = null)
    {
        $g = new GoogleAuthenticator();

        if (!is_null($secret)) {
            return $g->checkCode($secret, $code);
        }

        $staffid = $this->session->userdata('tfa_staffid');

        $this->db->select('google_auth_secret')
            ->where('staffid', $staffid);

        if ($staff = $this->db->get('staff')->row()) {
            return $g->checkCode(
                $this->decrypt($staff->google_auth_secret),
                $code
            );
        }

        return false;
    }

    public function encrypt($string)
    {
        $this->load->library('encryption');

        return $this->encryption->encrypt($string);
    }

    public function decrypt($string)
    {
        $this->load->library('encryption');

        return $this->encryption->decrypt($string);
    }

    public function get_caminho_saida()
    {
        $table = db_prefix() . 'empresas';
        if ($this->session->userdata('empresa_id')) {
            $this->db->where('id', $this->session->userdata('empresa_id'));
        } else {
            return false;
        }

        $this->db->where('ad', '1');
        $empresa = $this->db->get($table)->row();
        return $empresa->caminho_saida;
    }
}
