<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->lang->load('auth', $this->Settings->user_language);
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
        $this->load->model('auth_model');
        $this->load->model('atas_model');
        $this->load->library('ion_auth');
    }

    function index()
    {
         
        if (!$this->loggedIn) {
            redirect('login');
        } else {
            $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
            redirect($_SERVER['HTTP_REFERER']);
        }
        
         
    }
    
    function validaEmail($email) {
        $conta = "^[a-zA-Z0-9\._-]+@";
        $domino = "[a-zA-Z0-9\._-]+.";
        $extensao = "([a-zA-Z]{2,4})$";
        $pattern = $conta . $domino . $extensao;
        if (ereg($pattern, $email))
            return true;
        else
            return false;
    }

    function troca_perfil()
    {
         $perfil = $this->input->post('perfil_usuario');
         
         $usuario = $this->session->userdata('user_id');
         
         $data_perfil['group_id'] = $perfil;
         
         $this->site->updatePerfilAtualUsuario($usuario,$data_perfil);
         
         if ($perfil == 5) {
            redirect("welcome");
        } else {
            redirect("Login_Projetos/menu");
        }
    }
    
    
    function troca_perfil_dashboard($id)
    {
         $perfil = $this->input->post('perfil_usuario');
         
         $usuario = $this->session->userdata('user_id');
         
         $data_perfil['group_id'] = $perfil;
         
         $this->site->updatePerfilAtualUsuario($usuario,$data_perfil);
         
         if($perfil == 5){
             redirect("welcome");
         }else{
         redirect("Login_Projetos/menu/".$id);
         }
    }
    
    function create_user_new()
    {
      
        $nome = $this->input->post('nome');
        $sobrenome = $this->input->post('sobrenome');
        $email = $this->input->post('email');
        $senha = $this->input->post('senha');
        $confirmasenha = $this->input->post('confirmasenha');
 
            if (!isset($nome) || empty($nome)) {
                $valor = 0;
                $erro = 'Informe seu nome.';
            } else{
                $valor = 1;
            }
            if (!isset($sobrenome) || empty($sobrenome)) {
                $valor = 0;
                $erro = 'Informe seu sobrenome.';
            } else{
                $valor = 1;
            } 
            // EMAIL
            // Também verifica se não existe nenhum erro anterior
            if ((!isset($email) || !filter_var($email, FILTER_VALIDATE_EMAIL) ) && !$erro) {
                $valor = 0;
                $erro = 'Envie um email válido.';
            } else{
                $valor = 1;
            } 
            // SENHA
            if (!isset($senha) || empty($senha)) {
                $valor = 0;
                $erro = 'Informe sua senha.';
            } else {
                $valor = 1;
            }
            // CONFIRMASENHA
            if (!isset($confirmasenha) || empty($confirmasenha)) {
                $valor = 0;
                $erro = 'Confirme sua senha';
            }else{
                $valor = 1;
            }
       
       
        if($valor == 0){
              $this->load->view($this->theme . 'auth/create_user_new', $this->data);
        }
            /*
             * VALIDA O EMAIL
             */
        $conta = "^[a-zA-Z0-9\._-]+@";
        $domino = "[a-zA-Z0-9\._-]+.";
        $extensao = "([a-zA-Z]{2,4})$";
        $pattern = $conta . $domino . $extensao;
        
        
        
            if (!ereg($pattern, $email)){
                $erro = 'Informe um email válido.';
                
                $this->data['error'] = $erro;
                $this->session->set_flashdata('message', $this->ion_auth->messages());
                $this->load->view($this->theme . 'auth/create_user_new', $this->data);
                
            }else
                
                if($senha != $confirmasenha){
                $erro = 'As senhas devem ser iguais.';
                $this->data['error'] = $erro;
                $this->session->set_flashdata('message', $this->ion_auth->messages());
                $this->load->view($this->theme . 'auth/create_user_new', $this->data);
            }
            
            
            
            
      
           
        
        
       
        
        /*
         *  if ($this->form_validation->run() == true) {
            echo 'chegou aqui';
         exit;
            $user_dados = $user = $this->site->getUser($this->session->userdata('user_id'));
            $empresa = $user_dados->empresa_id;
            
            $username = strtolower($this->input->post('username'));
            $email = strtolower($this->input->post('email'));
            $password = $this->input->post('password');
            $notify = $this->input->post('notify');

            $additional_data = array(
                'first_name' => $this->input->post('first_name'),
                'last_name' => $this->input->post('last_name'),
                'company' => $this->input->post('company'),
                'phone' => $this->input->post('phone'),
                'gender' => $this->input->post('gender'),
                'group_id' => $this->input->post('group') ? $this->input->post('group') : '3',
                'biller_id' => $this->input->post('biller'),
                'warehouse_id' => $this->input->post('warehouse'),
                'view_right' => $this->input->post('view_right'),
                'edit_right' => $this->input->post('edit_right'),
                'allow_discount' => $this->input->post('allow_discount'),
                'empresa_id' => $empresa,
            );
            $active = $this->input->post('status');
        }else{
            echo $this->form_validation->run();
            echo '<br>';
            echo $nome;
            echo '<br>';
            echo 'chegou aqui';
        // exit;
        }
        
        if ($this->form_validation->run() == true && $this->ion_auth->register($username, $password, $email, $additional_data, $active, $notify)) {

            $this->data['message'] = 'TESTE MENSAGEM';
            $this->session->set_flashdata('message', $this->ion_auth->messages());
            
            echo 'chegou aqui 3';
            exit;
            redirect("auth/login");

        } else {
                 echo '<br>';
             echo 'chegou aqui 4';
            $this->data['error'] = 'TESTE MENSAGEM 1';
           $this->session->set_flashdata('message', $this->ion_auth->messages());

            $this->load->view($this->theme . 'auth/create_user_new', $this->data);
           // redirect("auth/create_user_new");
            
            
        }
         */

       

        
        }
    
    function users()
    {
        if ( ! $this->loggedIn) {
            redirect('login');
        }
        
        $usuario = $this->session->userdata('user_id');
                     $projetos = $this->site->getProjetoAtualByID_completo($usuario);
                     $cadastroUsuario = $this->site->getPerfilAtualByID($usuario);
                     $perfilAtualUsuario = $cadastroUsuario->group_id;
                     
        if ( $perfilAtualUsuario != '1') {
            $this->session->set_flashdata('warning', lang('access_denied'));
            redirect(isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'projetos');
        }

        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');

        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('users')));
        $meta = array('page_title' => lang('users'), 'bc' => $bc);
        $this->page_construct('auth/index', $meta, $this->data);
    }

    function getUsers()
    {
        
        $usuario = $this->session->userdata('user_id');
                     $projetos = $this->site->getProjetoAtualByID_completo($usuario);
                     $cadastroUsuario = $this->site->getPerfilAtualByID($usuario);
                     $perfilAtualUsuario = $cadastroUsuario->group_id;
                     
        if ( $perfilAtualUsuario != '1') {
            $this->session->set_flashdata('warning', lang('access_denied'));
            $this->sma->md();
        }

        $this->load->library('datatables');
        $this->datatables
            ->select(" id, first_name, last_name, username, email, company, award_points,  active")
            ->from("users")
            //->join('groups', 'users.group_id=groups.id', 'left')
            ->group_by('users.id')
            //->where('company_id', NULL)
            ->edit_column('active', '$1__$2', 'active, id')
            ->add_column("Actions", "<div class=\"text-center\"><a href='" . site_url('auth/edit_user/$1') . "' class='tip' title='" . lang("edit_user") . "'><i class=\"fa fa-edit\"></i></a></div>", "id");
        $this->db->order_by('id', 'desc');
        
        if ($perfilAtualUsuario != '1') {
            $this->datatables->unset_column('id');
        }
        echo $this->datatables->generate();
    }

    function getUserLogins($id = NULL)
    {
        if (!$this->ion_auth->in_group(array('super-admin', 'admin'))) {
            $this->session->set_flashdata('warning', lang("access_denied"));
            redirect('projetos');
        }
        $this->load->library('datatables');
        $this->datatables
            ->select("login, ip_address, time")
            ->from("user_logins")
            ->where('user_id', $id);

        echo $this->datatables->generate();
    }

    function delete_avatar($id = NULL, $avatar = NULL)
    {

        if (!$this->ion_auth->logged_in() || !$this->ion_auth->in_group('owner') && $id != $this->session->userdata('user_id')) {
            $this->session->set_flashdata('warning', lang("access_denied"));
            die("<script type='text/javascript'>setTimeout(function(){ window.top.location.href = '" . $_SERVER["HTTP_REFERER"] . "'; }, 0);</script>");
            redirect($_SERVER["HTTP_REFERER"]);
        } else {
            unlink('assets/uploads/avatars/' . $avatar);
            unlink('assets/uploads/avatars/thumbs/' . $avatar);
            if ($id == $this->session->userdata('user_id')) {
                $this->session->unset_userdata('avatar');
            }
            $this->db->update('users', array('avatar' => NULL), array('id' => $id));
            $this->session->set_flashdata('message', lang("avatar_deleted"));
          //  die("<script type='text/javascript'>setTimeout(function(){ window.top.location.href = '" . $_SERVER["HTTP_REFERER"] . "'; }, 0);</script>");
            redirect($_SERVER["HTTP_REFERER"]);
            redirect("welcome/profile/" . $id);
        }
        redirect("welcome/profile/" . $id);
    }

    
    function delete_capa($id = NULL, $avatar = NULL)
    {

        if (!$this->ion_auth->logged_in() || !$this->ion_auth->in_group('owner') && $id != $this->session->userdata('user_id')) {
            $this->session->set_flashdata('warning', lang("access_denied"));
            die("<script type='text/javascript'>setTimeout(function(){ window.top.location.href = '" . $_SERVER["HTTP_REFERER"] . "'; }, 0);</script>");
            redirect($_SERVER["HTTP_REFERER"]);
        } else {
            unlink('assets/uploads/avatars/' . $avatar);
           
            if ($id == $this->session->userdata('user_id')) {
                $this->session->unset_userdata('foto_capa');
            }
            $this->db->update('users', array('foto_capa' => NULL), array('id' => $id));
            $this->session->set_flashdata('message', lang("avatar_deleted"));
          //  die("<script type='text/javascript'>setTimeout(function(){ window.top.location.href = '" . $_SERVER["HTTP_REFERER"] . "'; }, 0);</script>");
            redirect($_SERVER["HTTP_REFERER"]);
            redirect("welcome/profile/" . $id);
        }
        redirect("welcome/profile/" . $id);
    }
    
    function profile($id = NULL)
    {
          $usuario = $this->session->userdata('user_id');
                     $projetos = $this->site->getProjetoAtualByID_completo($usuario);
                     $cadastroUsuario = $this->site->getPerfilAtualByID($usuario);
                     $perfilAtualUsuario = $cadastroUsuario->group_id;
                     
     
        if (!$this->ion_auth->logged_in()  && $id != $this->session->userdata('user_id')) {
            $this->session->set_flashdata('warning', lang("access_denied"));
            redirect($_SERVER["HTTP_REFERER"]);
        }
        if (!$id || empty($id)) {
            redirect('auth');
        }

        $this->data['title'] = lang('profile');

        $user = $this->ion_auth->user($id)->row();
        $groups = $this->ion_auth->groups()->result_array();
        $this->data['csrf'] = $this->_get_csrf_nonce();
        $this->data['user'] = $user;
        $this->data['groups'] = $groups;
        $this->data['users_perfil'] = $this->site->getPerfilUserByID_User($id);

        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $this->data['password'] = array(
            'name' => 'password',
            'id' => 'password',
            'class' => 'form-control',
            'type' => 'password',
            'value' => ''
        );
        $this->data['password_confirm'] = array(
            'name' => 'password_confirm',
            'id' => 'password_confirm',
            'class' => 'form-control',
            'type' => 'password',
            'value' => ''
        );
        $this->data['min_password_length'] = $this->config->item('min_password_length', 'ion_auth');
        $this->data['old_password'] = array(
            'name' => 'old',
            'id' => 'old',
            'class' => 'form-control',
            'type' => 'password',
        );
        $this->data['new_password'] = array(
            'name' => 'new',
            'id' => 'new',
            'type' => 'password',
            'class' => 'form-control',
            'pattern' => '^.{' . $this->data['min_password_length'] . '}.*$',
        );
        $this->data['new_password_confirm'] = array(
            'name' => 'new_confirm',
            'id' => 'new_confirm',
            'type' => 'password',
            'class' => 'form-control',
            'pattern' => '^.{' . $this->data['min_password_length'] . '}.*$',
        );
        $this->data['user_id'] = array(
            'name' => 'user_id',
            'id' => 'user_id',
            'type' => 'hidden',
            'value' => $user->id,
        );

        $this->data['id'] = $id;

        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('auth/users'), 'page' => lang('users')), array('link' => '#', 'page' => lang('profile')));
        $meta = array('page_title' => lang('profile'), 'bc' => $bc);
        $this->page_construct('auth/profile', $meta, $this->data);
    }

    function edit_profile($id = NULL)
    {

        if ($this->input->post('id')) {
            $id = $this->input->post('id');
        }
        $this->data['title'] = lang("edit_user");
        
         $usuario = $this->session->userdata('user_id');
                     $projetos = $this->site->getProjetoAtualByID_completo($usuario);
                     $cadastroUsuario = $this->site->getPerfilAtualByID($usuario);
                     $perfilAtualUsuario = $cadastroUsuario->group_id;
                     
      

        if ((!$this->loggedIn || $perfilAtualUsuario != '1') && $id != $this->session->userdata('user_id')) {
            $this->session->set_flashdata('warning', lang("access_denied"));
            redirect($_SERVER["HTTP_REFERER"]);
        }

        $user = $this->ion_auth->user($id)->row();

        
        $data = array(
                   
                    'phone' => $this->input->post('phone'),
                    
                );

        
        if ($this->form_validation->run() === TRUE && $this->ion_auth->update($user->id, $data)) {
            $this->session->set_flashdata('message', lang('user_updated'));
            redirect("auth/profile/" . $id);
        } else {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }
    }
    public function captcha_check($cap)
    {
        $expiration = time() - 300; // 5 minutes limit
        $this->db->delete('captcha', array('captcha_time <' => $expiration));

        $this->db->select('COUNT(*) AS count')
            ->where('word', $cap)
            ->where('ip_address', $this->input->ip_address())
            ->where('captcha_time >', $expiration);

        if ($this->db->count_all_results('captcha')) {
            return true;
        } else {
            $this->form_validation->set_message('captcha_check', lang('captcha_wrong'));
            return FALSE;
        }
    }


    function login_ad($m = NULL)
    {
        
        $_mat = $this->input->post('identity');
        $_pw = $this->input->post('password');
        
        
        if (empty($_mat) or empty($_pw)) {
            
            echo json_encode(array('error' => 'Parametros inválidos')); die();
            $this->session->set_flashdata('error', lang('Parametros inválidos'));
            // $this->load->view($this->theme . 'auth/login', $this->data);
            redirect('login');
        } else {
           
	$ldap_connection = ldap_connect('10.11.20.2');
	ldap_set_option($ldap_connection, LDAP_OPT_PROTOCOL_VERSION, 3) or die('Unable to set LDAP protocol version');
	ldap_set_option($ldap_connection, LDAP_OPT_REFERRALS, 0); 
	if (!@ldap_bind($ldap_connection, $_mat . '@unimedmanaus.local', $_pw)) {
		echo json_decode(array('error' => 'Usuário ou Senha inválida')); 
                $this->session->set_flashdata('error', lang('Usuário ou Senha inválida'));
                // $this->load->view($this->theme . 'auth/login', $this->data);
                
                /*
                 * SE NAO ENCONTRAR VERIFICA NO BD DO SIG
                 */
                
                if ($this->ion_auth->login_original($this->input->post('identity'), $this->input->post('password'), $remember)) {
                if ($this->Settings->mmode) {
                    if (!$this->ion_auth->in_group('owner')) {
                        $this->session->set_flashdata('error', lang('site_is_offline_plz_try_later'));
                        redirect('auth/logout');
                    }
                }
                if ($this->ion_auth->in_group('customer') || $this->ion_auth->in_group('supplier')) {
                    redirect('auth/logout/1');
                }
                $usuario = $this->session->userdata('user_id');
                $cadastroUsuario = $this->site->getPerfilAtualByID($usuario);
                $perfilAtualUsuario = $cadastroUsuario->group_id;
                $this->session->set_flashdata('message', $this->ion_auth->messages());
                
                if($perfilAtualUsuario==5){
                $referrer = $this->session->userdata('requested_page') ? $this->session->userdata('requested_page') : 'Welcome';    
                }else  if($perfilAtualUsuario != 5){
                $referrer = $this->session->userdata('requested_page') ? $this->session->userdata('requested_page') : 'Login_Projetos/menu';    
                }
                
              
                redirect($referrer);
                 
            } else {
                $this->session->set_flashdata('error', $this->ion_auth->errors());
                redirect('login');
            }
                
                
	} else {
            
		$ldap_base_dn = 'DC=unimedmanaus,DC=local';
		$search_filter='samaccountname=' . $_mat;
		
		$attributes = array();
		$attributes[] = 'givenname';
		$attributes[] = 'cn';
		$attributes[] = 'samaccountname';
		$attributes[] = 'sn';
		$attributes[] = 'mail';
		$attributes[] = 'description';
		$attributes[] = 'department';
		
		$result = @ldap_search($ldap_connection, $ldap_base_dn, $search_filter, $attributes);
		$entries = @ldap_get_entries($ldap_connection, $result);
		
		foreach ($entries as $_entrada) {
			if (empty($_entrada['cn'][0])) { continue; }
			
			$nome 	= @$_entrada['cn'][0];
			$cargo 	= @$_entrada['description'][0];
			$_resposta['setor'] 	= @$_entrada['department'][0];
			$matricula = @$_entrada['samaccountname'][0];
			$email 	= @$_entrada['mail'][0];
                        
			//echo json_encode($_resposta);
                       
		}
                  
                /*
                 * VERIFICA SE EXISTE O CADASTRO DO MESMO NO SIG
                 */
                $cadastroUsuario = $this->site->getUserbyemail($email);
                $id_usuario = $cadastroUsuario->id;
                $perfilAtualUsuario = $cadastroUsuario->group_id;
                
                 
                if($id_usuario){
                   
                    /*
                     * ATUALIZAÇÃO DE CADASTRO
                     * Se existir no sig, verifica se tem a matrícula cadastrada, se n tiver atualiza o cadastro
                     */
                    
                    if($cadastroUsuario->matricula){
                          
                        redirect('login/'.$cadastroUsuario->matricula);
                        /*
                         if($perfilAtualUsuario==5){
                             redirect('Welcome');
                           }else if($perfilAtualUsuario !=5){
                             // echo 'aqui '; exit;
                             
                             //echo 'aqui 2'; exit;
                           }
                         * 
                         */
                    }else{
                      
                        
                        $data = array('matricula' => $matricula, 'cargo' => $cargo, 'first_name' => $nome, 'last_name' => '');
                        $this->site->updateMatriculaUser($id_usuario, $data);
                        
                        $cadastroUsuario2 = $this->site->getUserbyemail($email);
                        redirect('login/'.$cadastroUsuario2->matricula);
                        
                    }
                       
                    
                   
                }else{
                    // echo 'aqui 2'; exit;
                    $this->session->set_flashdata('error', lang('Você ainda não tem cadastro, entre em contato com a TI.'));
                    // $this->load->view($this->theme . 'auth/login', $this->data);
                    
                    redirect('login');
                    
                    
                    //    $this->site->updateMatriculaUser($id_usuario, $data);
                    /*
                     * FAZ O CADASTRO
                     * 
                     * $data = array(  'matricula' => $matricula,
                                    'cargo' => $cargo, 
                                    'first_name' => $nome, 
                                    'active' => '0',
                                    'email' => $email);
                     */
                }
                
	}
    }
 
       
        //  $this->load->view($this->theme . 'auth/login', $this->data);
    }
    
    function login_old_ad($m = NULL)
    {
      
      //  exit;
        
        if ($m) {
            
            $remember = (bool)$this->input->post('remember');

            if ($this->ion_auth->login($m,  $this->input->post('password'), $remember)) {
              
                
                $usuario = $this->session->userdata('user_id');
                $cadastroUsuario = $this->site->getPerfilAtualByID($usuario);
                $perfilAtualUsuario = $cadastroUsuario->group_id;
                $perfisUsuario = $this->site->getPerfilUserByID_User($usuario);
                 foreach ($perfisUsuario as $perfil) {
                          
                 }
           
                
                if($perfilAtualUsuario==5){
                $referrer = $this->session->userdata('requested_page') ? $this->session->userdata('requested_page') : 'Welcome';    
                
                }else  if($perfilAtualUsuario != 5){
                    
                   /*
                    * verifica se tem acesso a mais de 1 sistema
                    */                    
                    $cadastroUsuario = $this->site->getPerfilAtualSistemasByID($usuario);
                    $quantidade = $cadastroUsuario->quantidade;
                    
                    if($quantidade > 1){
                        $referrer = $this->session->userdata('requested_page') ? $this->session->userdata('requested_page') : 'Login_Projetos/menu_sistemas'; 
                    }else{
                       $referrer = $this->session->userdata('requested_page') ? $this->session->userdata('requested_page') : 'Login_Projetos/menu';    
                    }
                }
                
              
                redirect($referrer);
                 
            } else {
                $this->session->set_flashdata('error', $this->ion_auth->errors());
                redirect('login');
            }
        } else {

            $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
            $this->data['message'] = $this->session->flashdata('message');
           

            $this->load->view($this->theme . 'auth/login', $this->data);
        }
    }

    function login($m = NULL)
    {
       
        
        
         if ($this->loggedIn) {
            $this->session->set_flashdata('error', $this->session->flashdata('error'));
            redirect('Welcome');
        
        }
       
        $this->data['title'] = lang('login');

        if ($this->Settings->captcha) {
            $this->form_validation->set_rules('captcha', lang('captcha'), 'required|callback_captcha_check');
        }
        
        if ($this->form_validation->run() == true) {

            $remember = (bool)$this->input->post('remember');
            
            if ($this->ion_auth->login($this->input->post('identity'), $this->input->post('password'), $remember)) {
                
                //QUANDO O SISTEMA ESTÁ EM MANUTENÇÃO
                if ($this->Settings->mmode) {
                    if (!$this->ion_auth->in_group('owner')) {
                        $this->session->set_flashdata('error', lang('site_is_offline_plz_try_later'));
                        redirect('auth/logout');
                    }
                }
                
                /*
                 * REALIZA O LOG
                 
                $ldata = array('user_id' => $user->id, 'ip_address' => $this->input->ip_address(), 'login' => $this->input->post('identity'));
                $this->db->insert('user_logins', $ldata);
                 */
               
                $this->session->set_flashdata('message', $this->ion_auth->messages());
                
               
                $referrer = $this->session->userdata('requested_page') ? $this->session->userdata('requested_page') : 'welcome';    
               
                redirect($referrer);
                 
            } else {
                $this->session->set_flashdata('error', $this->ion_auth->errors());
                redirect('login');
            }
        } else {

            $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
            $this->data['message'] = $this->session->flashdata('message');
            if ($this->Settings->captcha) {
                $this->load->helper('captcha');
                $vals = array(
                    'img_path' => './assets/captcha/',
                    'img_url' => site_url() . 'assets/captcha/',
                    'img_width' => 150,
                    'img_height' => 34,
                    'word_length' => 5,
                    'colors' => array('background' => array(255, 255, 255), 'border' => array(204, 204, 204), 'text' => array(102, 102, 102), 'grid' => array(204, 204, 204))
                );
                $cap = create_captcha($vals);
                $capdata = array(
                    'captcha_time' => $cap['time'],
                    'ip_address' => $this->input->ip_address(),
                    'word' => $cap['word']
                );

                $query = $this->db->insert_string('captcha', $capdata);
                $this->db->query($query);
                $this->data['image'] = $cap['image'];
                $this->data['captcha'] = array('name' => 'captcha',
                    'id' => 'captcha',
                    'type' => 'text',
                    'class' => 'form-control',
                    'required' => 'required',
                    'placeholder' => lang('type_captcha')
                );
            }

            $this->data['identity'] = array('name' => 'identity',
                'id' => 'identity',
                'type' => 'text',
                'class' => 'form-control',
                'placeholder' => lang('email'),
                'value' => $this->form_validation->set_value('identity'),
            );
            $this->data['password'] = array('name' => 'password',
                'id' => 'password',
                'type' => 'password',
                'class' => 'form-control',
                'required' => 'required',
                'placeholder' => lang('password'),
            );
            $this->data['allow_reg'] = $this->Settings->allow_reg;
            if ($m == 'db') {
                $this->data['message'] = lang('db_restored');
            } elseif ($m) {
                $this->data['error'] = lang('we_are_sorry_as_this_sction_is_still_under_development.');
            }

            $this->load->view($this->theme . 'auth/login', $this->data);
        }
    }
    
    function reload_captcha()
    {
        $this->load->helper('captcha');
        $vals = array(
            'img_path' => './assets/captcha/',
            'img_url' => site_url() . 'assets/captcha/',
            'img_width' => 150,
            'img_height' => 34,
            'word_length' => 5,
            'colors' => array('background' => array(255, 255, 255), 'border' => array(204, 204, 204), 'text' => array(102, 102, 102), 'grid' => array(204, 204, 204))
        );
        $cap = create_captcha($vals);
        $capdata = array(
            'captcha_time' => $cap['time'],
            'ip_address' => $this->input->ip_address(),
            'word' => $cap['word']
        );
        $query = $this->db->insert_string('captcha', $capdata);
        $this->db->query($query);
        //$this->data['image'] = $cap['image'];

        echo $cap['image'];
    }

    function logout($m = NULL)
    {

        $logout = $this->ion_auth->logout();
        $this->session->set_flashdata('message', $this->ion_auth->messages());

        redirect('login/' . $m);
    }

    function change_password()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('login');
        }
        $this->form_validation->set_rules('old_password', lang('old_password'), 'required');
        $this->form_validation->set_rules('new_password', lang('new_password'), 'required|min_length[8]|max_length[25]');
        $this->form_validation->set_rules('new_password_confirm', lang('confirm_password'), 'required|matches[new_password]');

        $user = $this->ion_auth->user()->row();

        if ($this->form_validation->run() == false) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('auth/profile/' . $user->id . '/#cpassword');
        } else {
            if (DEMO) {
                $this->session->set_flashdata('warning', lang('disabled_in_demo'));
                redirect($_SERVER["HTTP_REFERER"]);
            }

            $identity = $this->session->userdata($this->config->item('identity', 'ion_auth'));

            $change = $this->ion_auth->change_password($identity, $this->input->post('old_password'), $this->input->post('new_password'));

            if ($change) {
                $this->session->set_flashdata('message', $this->ion_auth->messages());
                $this->logout();
            } else {
                $this->session->set_flashdata('error', $this->ion_auth->errors());
                redirect('auth/profile/' . $user->id . '/#cpassword');
            }
        }
    }

    function forgot_password()
    {
        $this->form_validation->set_rules('forgot_email', lang('email_address'), 'required|valid_email');

        if ($this->form_validation->run() == false) {
            $error = validation_errors() ? validation_errors() : $this->session->flashdata('error');
            $this->session->set_flashdata('error', $error);
            redirect("login#forgot_password");
        } else {

            $identity = $this->ion_auth->where('email', strtolower($this->input->post('forgot_email')))->users()->row();
            if (empty($identity)) {
                $this->ion_auth->set_message('forgot_password_email_not_found');
                $this->session->set_flashdata('error', $this->ion_auth->messages());
                redirect("login#forgot_password");
            }

            $forgotten = $this->ion_auth->forgotten_password($identity->email);

            if ($forgotten) {
                $this->session->set_flashdata('message', $this->ion_auth->messages());
                redirect("login#forgot_password");
            } else {
                $this->session->set_flashdata('error', $this->ion_auth->errors());
                redirect("login#forgot_password");
            }
        }
    }

    public function reset_password($code = NULL)
    {
        if (!$code) {
            show_404();
        }

        $user = $this->ion_auth->forgotten_password_check($code);

        if ($user) {

            $this->form_validation->set_rules('new', lang('password'), 'required|min_length[8]|max_length[25]|matches[new_confirm]');
            $this->form_validation->set_rules('new_confirm', lang('confirm_password'), 'required');

            if ($this->form_validation->run() == false) {

                $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
                $this->data['message'] = $this->session->flashdata('message');
                $this->data['title'] = lang('reset_password');
                $this->data['min_password_length'] = $this->config->item('min_password_length', 'ion_auth');
                $this->data['new_password'] = array(
                    'name' => 'new',
                    'id' => 'new',
                    'type' => 'password',
                    'class' => 'form-control',
                    'pattern' => '(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}',
                    'data-bv-regexp-message' => lang('pasword_hint'),
                    'placeholder' => lang('new_password')
                );
                $this->data['new_password_confirm'] = array(
                    'name' => 'new_confirm',
                    'id' => 'new_confirm',
                    'type' => 'password',
                    'class' => 'form-control',
                    'data-bv-identical' => 'true',
                    'data-bv-identical-field' => 'new',
                    'data-bv-identical-message' => lang('pw_not_same'),
                    'placeholder' => lang('confirm_password')
                );
                $this->data['user_id'] = array(
                    'name' => 'user_id',
                    'id' => 'user_id',
                    'type' => 'hidden',
                    'value' => $user->id,
                );
                $this->data['csrf'] = $this->_get_csrf_nonce();
                $this->data['code'] = $code;
                $this->data['identity_label'] = $user->email;
                //render
                $this->load->view($this->theme . 'auth/reset_password', $this->data);
            } else {
                // do we have a valid request?
                if ($this->_valid_csrf_nonce() === FALSE || $user->id != $this->input->post('user_id')) {

                    //something fishy might be up
                    $this->ion_auth->clear_forgotten_password_code($code);
                    show_error(lang('error_csrf'));

                } else {
                    // finally change the password
                    $identity = $user->email;

                    $change = $this->ion_auth->reset_password($identity, $this->input->post('new'));

                    if ($change) {
                        //if the password was successfully changed
                        $this->session->set_flashdata('message', $this->ion_auth->messages());
                        //$this->logout();
                        redirect('login');
                    } else {
                        $this->session->set_flashdata('error', $this->ion_auth->errors());
                        redirect('auth/reset_password/' . $code);
                    }
                }
            }
        } else {
            //if the code is invalid then send them back to the forgot password page
            $this->session->set_flashdata('error', $this->ion_auth->errors());
            redirect("login#forgot_password");
        }
    }

    function activate($id, $code = false)
    {

        if ($code !== false) {
            $activation = $this->ion_auth->activate($id, $code);
        } else if ($this->Owner) {
            $activation = $this->ion_auth->activate($id);
        }

        if ($activation) {
            $this->session->set_flashdata('message', $this->ion_auth->messages());
            if ($this->Owner) {
                redirect($_SERVER["HTTP_REFERER"]);
            } else {
                redirect("auth/login");
            }
        } else {
            $this->session->set_flashdata('error', $this->ion_auth->errors());
            redirect("forgot_password");
        }
    }

    function deactivate($id = NULL)
    {
        $this->sma->checkPermissions('users', TRUE);
        $id = $this->config->item('use_mongodb', 'ion_auth') ? (string)$id : (int)$id;
        $this->form_validation->set_rules('confirm', lang("confirm"), 'required');

        if ($this->form_validation->run() == FALSE) {
            if ($this->input->post('deactivate')) {
                $this->session->set_flashdata('error', validation_errors());
                redirect($_SERVER["HTTP_REFERER"]);
            } else {
                $this->data['csrf'] = $this->_get_csrf_nonce();
                $this->data['user'] = $this->ion_auth->user($id)->row();
                $this->data['modal_js'] = $this->site->modal_js();
                $this->load->view($this->theme . 'auth/deactivate_user', $this->data);
            }
        } else {

            if ($this->input->post('confirm') == 'yes') {
                if ($id != $this->input->post('id')) {
                    show_error(lang('error_csrf'));
                }

                if ($this->ion_auth->logged_in() && $this->Owner) {
                    $this->ion_auth->deactivate($id);
                    $this->session->set_flashdata('message', $this->ion_auth->messages());
                }
            }

            redirect($_SERVER["HTTP_REFERER"]);
        }
    }

    function create_user()
    {
        $usuario = $this->session->userdata('user_id');
                     $projetos = $this->site->getProjetoAtualByID_completo($usuario);
                     $cadastroUsuario = $this->site->getPerfilAtualByID($usuario);
                     $perfilAtualUsuario = $cadastroUsuario->group_id;
                     
        if ($perfilAtualUsuario != '1') {
            $this->session->set_flashdata('warning', lang("access_denied"));
            redirect($_SERVER["HTTP_REFERER"]);
        }

        $this->data['title'] = "Create User";
        $this->form_validation->set_rules('username', lang("username"), 'trim|is_unique[users.username]');
        $this->form_validation->set_rules('email', lang("email"), 'trim|is_unique[users.email]');
        $this->form_validation->set_rules('status', lang("status"), 'trim|required');
        $this->form_validation->set_rules('group[]', lang("Perfil"), 'trim|required');

        if ($this->form_validation->run() == true) {

            $user_dados = $user = $this->site->getUser($this->session->userdata('user_id'));
            //$empresa = $user_dados->empresa_id;
            
            $username = strtolower($this->input->post('username'));
            $email = strtolower($this->input->post('email'));
            $password = $this->input->post('password');
            $notify = 1;
            
            $grupo_perfis = $this->input->post('group[]');
            $grupo_id = $grupo_perfis[0]; 
            
            
            $additional_data = array(
                'first_name' => $this->input->post('first_name'),
                'last_name' => $this->input->post('last_name'),
              //  'setor_id' => $this->input->post('setor'),
                'phone' => $this->input->post('phone'),
                'gender' => $this->input->post('gender'),
                'group_id' => $grupo_id
               // 'biller_id' => $this->input->post('biller')
               // 'warehouse_id' => $this->input->post('warehouse'),
               // 'view_right' => $this->input->post('view_right'),
               // 'edit_right' => $this->input->post('edit_right'),
               // 'award_points' => $this->input->post('award_points'),
               // 'gestor' => $this->input->post('gestor'),
               // 'allow_discount' => $this->input->post('allow_discount'),
                
                //'empresa_id' => $empresa,
            );
            $active = $this->input->post('status');
            $perfil_data = $this->input->post('group[]');
            $setor_data = $this->input->post('setor[]');
            // print_r($setor_data); exit;
            
        }
       // echo 'aqui'; exit;
      
        if ($this->form_validation->run() == true && $this->ion_auth->register($username, $password, $email, $additional_data, $perfil_data, $setor_data, $active, $notify = FALSE)) {

            $this->session->set_flashdata('message', $this->ion_auth->messages());
            redirect("auth/users");

        } else {

            $this->data['error'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('error')));
            $this->data['groups'] = $this->ion_auth->groups()->result_array();
            $this->data['setores'] = $this->ion_auth->setores()->result_array();
           // $this->data['billers'] = $this->site->getAllCompanies('biller');
           // $this->data['warehouses'] = $this->site->getAllWarehouses();
            $bc = array(array('link' => site_url('home'), 'page' => lang('home')), array('link' => site_url('auth/users'), 'page' => lang('users')), array('link' => '#', 'page' => lang('create_user')));
            $meta = array('page_title' => lang('users'), 'bc' => $bc);
            $this->page_construct('auth/create_user', $meta, $this->data);
        }
    }

    function edit_user($id = NULL)
    {
        
        if ($this->input->post('id')) {
            $id = $this->input->post('id');
        }
        $this->data['title'] = lang("edit_user");
       
         $usuario = $this->session->userdata('user_id');
                     $projetos = $this->site->getProjetoAtualByID_completo($usuario);
                     $cadastroUsuario = $this->site->getPerfilAtualByID($usuario);
                     $perfilAtualUsuario = $cadastroUsuario->group_id;
                    
                     
        if ((!$this->loggedIn || $perfilAtualUsuario != '1') && $id != $this->session->userdata('user_id')) {
            $this->session->set_flashdata('warning', lang("access_denied"));
            redirect($_SERVER["HTTP_REFERER"]);
        }

        $user = $this->ion_auth->user($id)->row();

      
      
            $this->form_validation->set_rules('email', lang("email"), 'trim');
       

        if ($this->form_validation->run() === TRUE) {
             
            if ($perfilAtualUsuario == '1') {
              
            $grupo_perfis = $this->input->post('group[]');
            $grupo_id = $grupo_perfis[0]; 
            
            
            $data = array(
                'username' => $this->input->post('username'),
                 'email' => $this->input->post('email'),
                'first_name' => $this->input->post('first_name'),
                'last_name' => $this->input->post('last_name'),
             //   'setor_id' => $this->input->post('company'),
                'phone' => $this->input->post('phone'),
                'gender' => $this->input->post('gender'),
                'group_id' => $grupo_id,
              //  'biller_id' => $this->input->post('biller'),
              //  'warehouse_id' => $this->input->post('warehouse'),
              //  'view_right' => $this->input->post('view_right'),
              //  'edit_right' => $this->input->post('edit_right'),
              //  'award_points' => $this->input->post('award_points'),
                'gestor' => $this->input->post('gestor'),
             //   'allow_discount' => $this->input->post('allow_discount'),
                'active' => $this->input->post('status'),
                //'empresa_id' => $empresa,
            );
             $active = $this->input->post('status');
             $perfil_data = $this->input->post('group[]');
              $setor_data = $this->input->post('setor[]');
             //print_r($perfil_data); exit;
            }  

            if ($perfilAtualUsuario == '1') {
                if ($this->input->post('password')) {
                   
                    $this->form_validation->set_rules('password', lang('edit_user_validation_password_label'), 'required|min_length[8]|max_length[25]|matches[password_confirm]');
                    $this->form_validation->set_rules('password_confirm', lang('edit_user_validation_password_confirm_label'), 'required');

                    $data['password'] = $this->input->post('password');
                }
            }
           $this->auth_model->update($id, $data, $perfil_data, $setor_data);

        }
        
        if ($this->form_validation->run() === TRUE ) {
            $this->session->set_flashdata('message', lang('user_updated'));
            redirect("auth/users");
        } else {
            $this->data['error'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('error')));
            $this->data['groups'] = $this->ion_auth->groups()->result_array();
            $this->data['setores'] = $this->ion_auth->setores()->result_array();
            $this->data['perfil_usuario'] = $this->ion_auth->perfil_usuario($id)->result_array();
            $this->data['setores_usuario'] = $this->auth_model->setor_usuario($id);
            $this->data['user'] = $this->ion_auth->user($id)->row();
           // $this->data['billers'] = $this->site->getAllCompanies('biller');
           // $this->data['warehouses'] = $this->site->getAllWarehouses();
            $bc = array(array('link' => site_url('home'), 'page' => lang('home')), array('link' => site_url('auth/users'), 'page' => lang('users')), array('link' => '#', 'page' => lang('create_user')));
            $meta = array('page_title' => lang('users'), 'bc' => $bc);
            $this->page_construct('auth/editar_user', $meta, $this->data);
        }
    }


    function _get_csrf_nonce()
    {
        $this->load->helper('string');
        $key = random_string('alnum', 8);
        $value = random_string('alnum', 20);
        $this->session->set_flashdata('csrfkey', $key);
        $this->session->set_flashdata('csrfvalue', $value);

        return array($key => $value);
    }

    function _valid_csrf_nonce()
    {
        if ($this->input->post($this->session->flashdata('csrfkey')) !== FALSE &&
            $this->input->post($this->session->flashdata('csrfkey')) == $this->session->flashdata('csrfvalue')
        ) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function _render_page($view, $data = null, $render = false)
    {

        $this->viewdata = (empty($data)) ? $this->data : $data;
        $view_html = $this->load->view('header', $this->viewdata, $render);
        $view_html .= $this->load->view($view, $this->viewdata, $render);
        $view_html = $this->load->view('footer', $this->viewdata, $render);

        if (!$render)
            return $view_html;
    }

    /**
     * @param null $id
     */
    function update_avatar($id = NULL)
    {
        if ($this->input->post('id')) {
            $id = $this->input->post('id');
        }
       
        if (!$this->ion_auth->logged_in() || !$this->Owner && $id != $this->session->userdata('user_id')) {
            $this->session->set_flashdata('warning', lang("access_denied"));
            redirect($_SERVER["HTTP_REFERER"]);
        }
           
        //validate form input
        $this->form_validation->set_rules('avatar', lang("avatar"), 'trim');
        

        if ($this->form_validation->run() == true) {

            if ($_FILES['avatar']['size'] > 0) {

                $this->load->library('upload');

                $config['upload_path'] = 'assets/uploads/avatars';
                $config['allowed_types'] = 'gif|jpg|png';
                //$config['max_size'] = '500';
                $config['max_width'] = $this->Settings->iwidth;
                $config['max_height'] = $this->Settings->iheight;
                $config['overwrite'] = FALSE;
                $config['encrypt_name'] = TRUE;
                $config['max_filename'] = 25;
             
                $this->upload->initialize($config);

                if (!$this->upload->do_upload('avatar')) {

                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect($_SERVER["HTTP_REFERER"]);
                }

                $photo = $this->upload->file_name;

                $this->load->helper('file');
                $this->load->library('image_lib');
                $config['image_library'] = 'gd2';
                $config['source_image'] = 'assets/uploads/avatars/' . $photo;
                $config['new_image'] = 'assets/uploads/avatars/thumbs/' . $photo;
                $config['maintain_ratio'] = TRUE;
                $config['width'] = 150;
                $config['height'] = 150;;

                $this->image_lib->clear();
                $this->image_lib->initialize($config);

                if (!$this->image_lib->resize()) {
                    echo $this->image_lib->display_errors();
                }
                $user = $this->ion_auth->user($id)->row();
            } else {
                $this->form_validation->set_rules('avatar', lang("avatar"), 'required');
            }
        }

        if ($this->form_validation->run() == true && $this->auth_model->updateAvatar($id, $photo)) {
            unlink('assets/uploads/avatars/' . $user->avatar);
            unlink('assets/uploads/avatars/thumbs/' . $user->avatar);
            $this->session->set_userdata('avatar', $photo);
            $this->session->set_flashdata('message', lang("avatar_updated"));
            redirect("welcome/profile/" . $id);
        } else {
            $this->session->set_flashdata('error', validation_errors());
            redirect("welcome/profile/" . $id);
        }
    }
    
       function update_foto_capa($id = NULL)
    {
        if ($this->input->post('id')) {
            $id = $this->input->post('id');
        }
       
        if (!$this->ion_auth->logged_in() || !$this->Owner && $id != $this->session->userdata('user_id')) {
            $this->session->set_flashdata('warning', lang("access_denied"));
            redirect($_SERVER["HTTP_REFERER"]);
        }
           
        //validate form input
        $this->form_validation->set_rules('capa', lang("avatar"), 'trim');
        

        if ($this->form_validation->run() == true) {
           
            if ($_FILES['capa']['size'] > 0) {
                 
                $this->load->library('upload');

                $config['upload_path'] = 'assets/uploads/avatars';
                $config['allowed_types'] = 'gif|jpg|png';
                //$config['max_size'] = '500';
               // $config['max_width'] = $this->Settings->iwidth;
               // $config['max_height'] = $this->Settings->iheight;
                $config['overwrite'] = FALSE;
                $config['encrypt_name'] = TRUE;
                $config['max_filename'] = 25;
             
                $this->upload->initialize($config);

                if (!$this->upload->do_upload('capa')) {

                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect($_SERVER["HTTP_REFERER"]);
                }

                $photo = $this->upload->file_name;

                

              
            } else {
                $this->form_validation->set_rules('avatar', lang("avatar"), 'required');
            }
        }

        if ($this->form_validation->run() == true && $this->auth_model->updateCapa($id, $photo)) {
            unlink('assets/uploads/avatars/' . $user->foto_capa);
            unlink('assets/uploads/avatars/thumbs/' . $user->foto_capa);
            $this->session->set_userdata('foto_capa', $photo);
            $this->session->set_flashdata('message', lang("avatar_updated"));
            redirect("welcome/profile/" . $id);
        } else {
            $this->session->set_flashdata('error', validation_errors());
            redirect("welcome/profile/" . $id);
        }
    }
   
    function register()
    {
        
       
        $this->data['title'] = "Register";
       
        
        $first_name = $this->input->post('first_name');
        $last_name = $this->input->post('last_name');
        $email = $this->input->post('email');
        $password = $this->input->post('password');
        $password_confirm = $this->input->post('password_confirm');
        $valor = 0;
            if (!isset($first_name) || empty($first_name)) {
                $valor += 0;
                $erro = 'Informe seu nome.';
            } else{
                $valor += 1;
            }
            if (!isset($last_name) || empty($last_name)) {
                $valor += 0;
                $erro = 'Informe seu sobrenome.';
            } else{
                $valor += 1;
            } 
            // EMAIL
            // Também verifica se não existe nenhum erro anterior
            if ((!isset($email) || !filter_var($email, FILTER_VALIDATE_EMAIL) ) && !$erro) {
                $valor += 0;
                $erro = 'Envie um email válido.';
            } else{
                $valor += 1;
            } 
            // SENHA
            if (!isset($password) || empty($password)) {
                $valor += 0;
                $erro = 'Informe sua senha.';
            } else {
                $valor += 1;
            }
            // CONFIRMASENHA
            if (!isset($password_confirm) || empty($password_confirm)) {
                $valor += 0;
                $erro = 'Confirme sua senha';
            }else{
                $valor += 1;
            }
        //validate form input
        //$this->form_validation->set_message('is_unique', 'An account already registered with this email');
        $this->form_validation->set_rules('first_name', lang('first_name'), 'required');
        $this->form_validation->set_rules('last_name', lang('last_name'), 'required');
        $this->form_validation->set_rules('email', lang('email_address'), 'required|valid_email|is_unique[users.email]');
       // $this->form_validation->set_rules('phone', lang('phone'), 'required');
      //  $this->form_validation->set_rules('company', lang('company'), 'required');
        $this->form_validation->set_rules('password', lang('password'), 'required|min_length[8]|max_length[25]|matches[password_confirm]');
        $this->form_validation->set_rules('password_confirm', lang('confirm_password'), 'required');
       // $this->form_validation->set_rules('captcha', 'captcha', 'required|callback_captcha_check');
       
      
        
        if ($valor == 5) {
            
            list($username, $domain) = explode("@", $this->input->post('email'));
            $email = strtolower($this->input->post('email'));
            $password = $this->input->post('password');
            
               /*
             * VALIDA O EMAIL
             */
        $conta = "^[a-zA-Z0-9\._-]+@";
        $domino = "[a-zA-Z0-9\._-]+.";
        $extensao = "([a-zA-Z]{2,4})$";
        $pattern = $conta . $domino . $extensao;
        
        
        
            if (!ereg($pattern, $email)){
                $erro = 'Informe um email válido.';
                
                $this->data['error'] = $erro;
                $this->session->set_flashdata('message', $this->ion_auth->messages());
                $this->load->view($this->theme . 'auth/create_user_new', $this->data);
                
            }else
                
                if($password != $password_confirm){
                $erro = 'As senhas devem ser iguais.';
                $this->data['error'] = $erro;
                $this->session->set_flashdata('message', $this->ion_auth->messages());
                $this->load->view($this->theme . 'auth/create_user_new', $this->data);
            }
            
        
            $additional_data = array(
                'first_name' => $this->input->post('first_name'),
                'last_name' => $this->input->post('last_name'),
               // 'company' => $this->input->post('company'),
               // 'phone' => $this->input->post('phone'),
            );
            
            
            
        }
        
        
        
        if ($valor == 5 && $this->ion_auth->register($email, $password, $email, $additional_data)) {
            
            $this->session->set_flashdata('message', $this->ion_auth->messages());
            redirect("login");
        } else {
             
          echo $this->data['error'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('error')));
           $this->data['groups'] = $this->ion_auth->groups()->result_array();
         
           //exit;
            
            $this->load->helper('captcha');
            $vals = array(
                'img_path' => './assets/captcha/',
                'img_url' => site_url() . 'assets/captcha/',
                'img_width' => 150,
                'img_height' => 34,
            );
            $cap = create_captcha($vals);
            $capdata = array(
                'captcha_time' => $cap['time'],
                'ip_address' => $this->input->ip_address(),
                'word' => $cap['word']
            );
            
            $query = $this->db->insert_string('captcha', $capdata);
            $this->db->query($query);
            $this->data['image'] = $cap['image'];
            $this->data['captcha'] = array('name' => 'captcha',
                'id' => 'captcha',
                'type' => 'text',
                'class' => 'form-control',
                'placeholder' => lang('type_captcha')
            );
            
            
            $this->data['first_name'] = array(
                'name' => 'first_name',
                'id' => 'first_name',
                'type' => 'text',
                'class' => 'form-control',
                'required' => 'required',
                'value' => $this->form_validation->set_value('first_name'),
            );
            $this->data['last_name'] = array(
                'name' => 'last_name',
                'id' => 'last_name',
                'type' => 'text',
                'required' => 'required',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('last_name'),
            );
            $this->data['email'] = array(
                'name' => 'email',
                'id' => 'email',
                'type' => 'text',
                'required' => 'required',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('email'),
            );
            $this->data['company'] = array(
                'name' => 'company',
                'id' => 'company',
                'type' => 'text',
                'required' => 'required',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('company'),
            );
            $this->data['phone'] = array(
                'name' => 'phone',
                'id' => 'phone',
                'type' => 'text',
                'required' => 'required',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('phone'),
            );
            $this->data['password'] = array(
                'name' => 'password',
                'id' => 'password',
                'type' => 'password',
                'required' => 'required',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('password'),
            );
            $this->data['password_confirm'] = array(
                'name' => 'password_confirm',
                'id' => 'password_confirm',
                'type' => 'password',
                'required' => 'required',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('password_confirm'),
            );
             $this->data['error'] = $this->data['error'];
             $this->session->set_flashdata('message', $this->ion_auth->messages());
            $this->load->view($this->theme . 'auth/create_user_new', $this->data);
        }
    }

    function user_actions()
    {
        if (!$this->Owner) {
            $this->session->set_flashdata('warning', lang('access_denied'));
            redirect($_SERVER["HTTP_REFERER"]);
        }

        $this->form_validation->set_rules('form_action', lang("form_action"), 'required');

        if ($this->form_validation->run() == true) {

            if (!empty($_POST['val'])) {
                if ($this->input->post('form_action') == 'delete') {
                    foreach ($_POST['val'] as $id) {
                        $this->auth_model->delete_user($id);
                    }
                    $this->session->set_flashdata('message', lang("users_deleted"));
                    redirect($_SERVER["HTTP_REFERER"]);
                }

                if ($this->input->post('form_action') == 'export_excel' || $this->input->post('form_action') == 'export_pdf') {

                    $this->load->library('excel');
                    $this->excel->setActiveSheetIndex(0);
                    $this->excel->getActiveSheet()->setTitle(lang('sales'));
                    $this->excel->getActiveSheet()->SetCellValue('A1', lang('first_name'));
                    $this->excel->getActiveSheet()->SetCellValue('B1', lang('last_name'));
                    $this->excel->getActiveSheet()->SetCellValue('C1', lang('email'));
                    $this->excel->getActiveSheet()->SetCellValue('D1', lang('company'));
                    $this->excel->getActiveSheet()->SetCellValue('E1', lang('group'));
                    $this->excel->getActiveSheet()->SetCellValue('F1', lang('status'));

                    $row = 2;
                    foreach ($_POST['val'] as $id) {
                        $user = $this->site->getUser($id);
                        $this->excel->getActiveSheet()->SetCellValue('A' . $row, $user->first_name);
                        $this->excel->getActiveSheet()->SetCellValue('B' . $row, $user->last_name);
                        $this->excel->getActiveSheet()->SetCellValue('C' . $row, $user->email);
                        $this->excel->getActiveSheet()->SetCellValue('D' . $row, $user->company);
                        $this->excel->getActiveSheet()->SetCellValue('E' . $row, $user->group);
                        $this->excel->getActiveSheet()->SetCellValue('F' . $row, $user->status);
                        $row++;
                    }

                    $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
                    $this->excel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    $filename = 'users_' . date('Y_m_d_H_i_s');
                    if ($this->input->post('form_action') == 'export_pdf') {
                        $styleArray = array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)));
                        $this->excel->getDefaultStyle()->applyFromArray($styleArray);
                        $this->excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
                        require_once(APPPATH . "third_party" . DIRECTORY_SEPARATOR . "MPDF" . DIRECTORY_SEPARATOR . "mpdf.php");
                        $rendererName = PHPExcel_Settings::PDF_RENDERER_MPDF;
                        $rendererLibrary = 'MPDF';
                        $rendererLibraryPath = APPPATH . 'third_party' . DIRECTORY_SEPARATOR . $rendererLibrary;
                        if (!PHPExcel_Settings::setPdfRenderer($rendererName, $rendererLibraryPath)) {
                            die('Please set the $rendererName: ' . $rendererName . ' and $rendererLibraryPath: ' . $rendererLibraryPath . ' values' .
                                PHP_EOL . ' as appropriate for your directory structure');
                        }

                        header('Content-Type: application/pdf');
                        header('Content-Disposition: attachment;filename="' . $filename . '.pdf"');
                        header('Cache-Control: max-age=0');

                        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'PDF');
                        return $objWriter->save('php://output');
                    }
                    if ($this->input->post('form_action') == 'export_excel') {
                        header('Content-Type: application/vnd.ms-excel');
                        header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
                        header('Cache-Control: max-age=0');

                        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
                        return $objWriter->save('php://output');
                    }

                    redirect($_SERVER["HTTP_REFERER"]);
                }
            } else {
                $this->session->set_flashdata('error', lang("no_user_selected"));
                redirect($_SERVER["HTTP_REFERER"]);
            }
        } else {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }
    }

    function delete($id = NULL)
    {
        if (DEMO) {
            $this->session->set_flashdata('warning', lang('disabled_in_demo'));
            redirect($_SERVER["HTTP_REFERER"]);
        }
        if ( $perfilAtualUsuario != '1') {
            $this->session->set_flashdata('warning', lang('access_denied'));
            redirect(isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'welcome');
        }

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        if ($this->auth_model->delete_user($id)) {
            //echo lang("user_deleted");
            $this->session->set_flashdata('message', 'user_deleted');
            redirect($_SERVER["HTTP_REFERER"]);
        }
    }

}
