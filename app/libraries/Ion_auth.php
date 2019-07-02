<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Ion_auth
{

    protected $status;
    public $_extra_where = array();
    public $_extra_set = array();
    public $_cache_user_in_group;

    public function __construct()
    {
        $this->load->config('ion_auth', TRUE);

        // Load IonAuth MongoDB model if it's set to use MongoDB,
        $this->lang->load('auth', $this->Settings->user_language);
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
        $this->load->model('auth_model');
        $this->load->model('projetos_model');
        $this->load->model('owner_model');
        $this->load->model('atas_model');
        $this->load->model('reports_model');
        $this->load->model('site');
      
        

        $this->_cache_user_in_group = &$this->auth_model->_cache_user_in_group;

        //auto-login the user if they are remembered
        if (!$this->logged_in() && get_cookie('identity') && get_cookie('remember_code')) {
            if ($this->auth_model->login_remembered_user()) {
                redirect($this->session->userdata('requested_page') ? $this->session->userdata('requested_page') : 'welcome');
            }
        }

        $this->auth_model->trigger_events('library_constructor');
    }

    public function __call($method, $arguments)
    {
        if (!method_exists($this->auth_model, $method)) {
            throw new Exception('Undefined method Ion_auth::' . $method . '() called');
        }

        return call_user_func_array(array($this->auth_model, $method), $arguments);
    }

    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function forgotten_password($identity)
    {    //changed $email to $identity
        if ($this->auth_model->forgotten_password($identity)) {   //changed
            // Get user information
            $user = $this->where($this->config->item('identity', 'ion_auth'), $identity)->where('active', 1)->users()->row();  //changed to get_user_by_identity from email

            if ($user) {
                $data = array(
                    'identity' => $user->{$this->config->item('identity', 'ion_auth')},
                    'forgotten_password_code' => $user->forgotten_password_code
                );

                if (!$this->config->item('use_ci_email', 'ion_auth')) {
                    $this->set_message('forgot_password_successful');
                    return $data;
                } else {

                    $this->load->library('parser');
                    $parse_data = array(
                        'user_name' => $user->first_name . ' ' . $user->last_name,
                        'email' => $user->email,
                        'reset_password_link' => anchor('auth/reset_password/' . $user->forgotten_password_code, lang('reset_password')),
                        'site_link' => base_url(),
                        'site_name' => $this->Settings->site_name,
                        'logo' => '<img src="' . base_url() . 'assets/uploads/logos/' . $this->Settings->logo . '" alt="' . $this->Settings->site_name . '"/>'
                    );
                    $msg = file_get_contents('./themes/' . $this->theme . 'email_templates/forgot_password.html');
                    $message = $this->parser->parse_string($msg, $parse_data);
                    $message = $message . "<br>" . lang('reset_password_link_alt') . "<br>" . site_url('auth/reset_password/' . $user->forgotten_password_code);

                    $subject = lang('email_forgotten_password_subject') . ' - ' . $this->Settings->site_name;
                    if ($this->sma->send_email($user->email, $subject, $message)) {
                        $this->set_message('forgot_password_successful');
                        return TRUE;
                    } else {
                        $this->set_error('sending_email_failed');
                        return FALSE;
                    }
                }
            } else {
                $this->set_error('forgot_password_unsuccessful');
                return FALSE;
            }
        } else {
            $this->set_error('forgot_password_unsuccessful');
            return FALSE;
        }
    }

    public function forgotten_password_complete($code)
    {
        $this->auth_model->trigger_events('pre_password_change');

        $identity = $this->config->item('identity', 'ion_auth');
        $profile = $this->where('forgotten_password_code', $code)->users()->row(); //pass the code to profile

        if (!$profile) {
            $this->auth_model->trigger_events(array('post_password_change', 'password_change_unsuccessful'));
            $this->set_error('password_change_unsuccessful');
            return FALSE;
        }

        $new_password = $this->auth_model->forgotten_password_complete($code, $profile->salt);

        if ($new_password) {
            $data = array(
                'identity' => $profile->{$identity},
                'new_password' => $new_password
            );
            if (!$this->config->item('use_ci_email', 'ion_auth')) {
                $this->set_message('password_change_successful');
                $this->auth_model->trigger_events(array('post_password_change', 'password_change_successful'));
                return $data;
            } else {

                $this->load->library('parser');
                $parse_data = array(
                    'client_name' => $profile->first_name . ' ' . $profile->last_name,
                    'email' => $profile->email,
                    'password' => $password,
                    'site_link' => base_url(),
                    'site_name' => $this->Settings->site_name,
                    'logo' => '<img src="' . base_url() . 'assets/uploads/logos/' . $this->Settings->logo . '" alt="' . $this->Settings->site_name . '"/>'
                    );
                $msg = file_get_contents('./themes/' . $this->theme . 'email_templates/new_password.html');
                $message = $this->parser->parse_string($msg, $parse_data);
                $subject = lang('email_new_password_subject') . ' - ' . $this->Settings->site_name;

                if ($this->sma->send_email($profile->email, $subject, $message)) {
                    $this->set_message('password_change_successful');
                    $this->auth_model->trigger_events(array('post_password_change', 'password_change_successful'));
                    return TRUE;
                } else {
                    $this->set_error('password_change_unsuccessful');
                    $this->auth_model->trigger_events(array('post_password_change', 'password_change_unsuccessful'));
                    return FALSE;
                }
            }
        }

        $this->auth_model->trigger_events(array('post_password_change', 'password_change_unsuccessful'));
        return FALSE;
    }

    public function forgotten_password_check($code)
    {
        $profile = $this->where('forgotten_password_code', $code)->users()->row(); //pass the code to profile

        if (!is_object($profile)) {
            $this->set_error('password_change_unsuccessful');
            return FALSE;
        } else {
            if ($this->config->item('forgot_password_expiration', 'ion_auth') > 0) {
                //Make sure it isn't expired
                $expiration = $this->config->item('forgot_password_expiration', 'ion_auth');
                if (time() - $profile->forgotten_password_time > $expiration) {
                    //it has expired
                    $this->clear_forgotten_password_code($code);
                    $this->set_error('password_change_unsuccessful');
                    return FALSE;
                }
            }
            return $profile;
        }
    }

    public function register($password, $email, $additional_data = array(),$setor_data, $notify)
    { //need to test email activation
    
        //$this->auth_model->trigger_events('pre_account_creation');

        //$email_activation = $this->config->item('email_activation', 'ion_auth');
           
            $id = $this->auth_model->register($password, $email,   $additional_data, $setor_data, $notify);

            if (!$id) {
                $this->set_error('Esta conta não pode ser criada.');
                return FALSE;
            }

            $deactivate = $this->auth_model->deactivate($id);
            
            if (!$deactivate) {
                $this->set_error('deactivate_unsuccessful');
                $this->auth_model->trigger_events(array('post_account_creation', 'post_account_creation_unsuccessful'));
                return FALSE;
            }

            $activation_code = $this->auth_model->activation_code;
            $identity = $this->config->item('identity', 'ion_auth');
            $user = $this->auth_model->user($id)->row();

            $data = array(
                'identity' => $user->{$identity},
                'id' => $user->id,
                'email' => $email,
                'activation' => $activation_code,
            );
            if (!$this->config->item('use_ci_email', 'ion_auth')) {
                $this->auth_model->trigger_events(array('post_account_creation', 'post_account_creation_successful', 'activation_email_successful'));
                $this->set_message('activation_email_successful');
                return $data;
            } else {
               
                 
                
                   if ($notify) {
                      
                     $this->load->library('parser');
       // $dados = $this->site->getUserbyemail($email, $empresa);
       // $id_usuario = $dados->id;
       
         $parse_data = array(
            'client_name' => $additional_data['first_name'],
            'site_link' => site_url(),
        //    'confirma_email' => site_url('auth/activate/'.$id),
            'activation_link' => anchor('auth/activate/' . $data['id'] . '/' . $data['activation'], lang('Ativar E-mail')),
            'site_name' => $this->Settings->site_name,
            'email' => $email,
            'password' => $password,
            'logo' => '<img src="' . base_url() . 'assets/uploads/logos/' . $this->Settings->logo . '" alt="' . $this->Settings->site_name . '"/>'
        );
         //print_r($parse_data); exit;
        $msg = file_get_contents('./themes/' . $this->theme . 'email_templates/credentials.php');
        $message = $this->parser->parse_string($msg, $parse_data);
        $subject = $this->lang->line('Detalhes conta') . ' - ' . $this->Settings->site_name;
       // $this->sma->send_email($email, $subject, $message);
        $from = 'noreply@sigplus.online';
        $from_name = "SigPlus";
        
        
        $this->sma->send_email_credencial($email, $subject, $message, $from, $from_name,null,$cc);  
                    
        
                   }
        
                  /*     
                    $this->load->library('parser');
                    $parse_data = array(
                        'client_name' => $additional_data['first_name'],
                        'site_link' => site_url(),
                        'confirma_email' => site_url('auth/activate/'.$id),
                        'activation_link' => anchor('auth/activate/' . $data['id'] . '/' . $data['activation'], lang('email_activate_link')),
                        'site_name' => $this->Settings->site_name,
                        'email' => $email,
                        'password' => $password,
                        'logo' => '<img src="' . base_url() . 'assets/uploads/logos/' . $this->Settings->logo . '" alt="' . $this->Settings->site_name . '"/>'
                    );
                    
                    $msg = file_get_contents('./themes/' . $this->theme . 'email_templates/credentials.php');
                    $message = $this->parser->parse_string($msg, $parse_data);
                    $subject = $this->lang->line('new_user_created') . ' - ' . $this->Settings->site_name;
                   // $this->sma->send_email($email, $subject, $message);
                    $from = 'noreply@sigplus.online';
                    $from_name = "SigPlus";
                    $this->sma->send_email_credencial($email, $subject, $message, $from, $from_name,null,$cc);
                   
                }
                
                
               
                 * 
                 * 
                 * 
                 * 
                $this->load->library('parser');
                $parse_data = array(
                    'client_name' => $additional_data['first_name'] . ' ' . $additional_data['last_name'],
                    'site_link' => 'http://unimedwebteste/gerenciamentodeprojetos/',
                    'site_name' => $this->Settings->site_name,
                    'email' => $email,
                    'activation_link' => anchor('auth/activate/' . $data['id'] . '/' . $data['activation'], lang('email_activate_link')),
                    'logo' => '<img src="' . base_url() . 'assets/uploads/logos/' . $this->Settings->logo . '" alt="' . $this->Settings->site_name . '"/>'
                );

                $msg = file_get_contents('./themes/' . $this->theme . 'email_templates/activate_email.html');
                $message = $this->parser->parse_string($msg, $parse_data);
                $subject = $this->lang->line('email_activation_subject') . ' - ' . $this->Settings->site_name;
              //  $this->sma->send_email($email, $subject, $message);
                 $from = 'noreply@sigplus.online';
                    $from_name = "SigPlus";
                    $this->sma->send_email($email, $subject, $message, $from, $from_name,null,$cc);

                if ($this->sma->send_email($email, $subject, $message, $from, $from_name,null,$cc)) {
                    $this->auth_model->trigger_events(array('post_account_creation', 'post_account_creation_successful', 'activation_email_successful'));
                    $this->set_message('activation_email_successful');
                    return $id;
                }
                 * 
                 */
            }

            $this->auth_model->trigger_events(array('post_account_creation', 'post_account_creation_unsuccessful', 'activation_email_unsuccessful'));
            $this->set_error('activation_email_unsuccessful');
            return FALSE;
        
    }

    
    /**************************************************************************
     ***************** CONTROLE DE ENVIO DE EMAILS ****************************
     *************************************************************************/
    
    /*
     * ENVIA EMAIL DE NOVA ATIVIDADE QUANDO FINALIZA A ATA (ATA)
     */
    public function enviaEmailComAcao($titulo, $texto,  $usuario_destino, $id_acao, $email_id){
      
        $acao =  $this->atas_model->getPlanoByIdAndEmpresa($id_acao);  
        $empresa_acao = $acao->empresa;
        $projeto_acao = $acao->projeto;
        $sequencial_acao = $acao->sequencial;
        
        
         
        $empresa_dados = $this->owner_model->getEmpresaById($empresa_acao);
        $nome_empresa = $empresa_dados->razaoSocial;
       
        $dados_projeto =  $this->projetos_model->getProjetoByIdAndByEmpresa($projeto_acao, $empresa_acao);
        $projeto_acao = $dados_projeto->projeto;
       
        $users_destino = $this->site->geUserByIDSemEmpresa($usuario_destino);   
        $email = $users_destino->email;
        
        $nome_usuario = $users_destino->first_name;
        
        
        $this->load->library('parser');
        $parse_data = array(
           'usuario' =>  $nome_usuario,
           'site_link' => site_url(),
           'acao' => $id_acao,
           'projeto' => $projeto_acao, 
           'titulo' => $titulo,
           'texto' => $texto,
           'empresa' => $nome_empresa,
           'logo' => '<img src="' . base_url() . 'assets/uploads/logos/' . $this->Settings->logo . '" alt="' . $this->Settings->site_name . '"/>'
        );
        
        $msg = file_get_contents('./themes/' . $this->theme . 'email_templates/sig_envio_usuario_com_acao.php');
        $message = $this->parser->parse_string($msg, $parse_data);
        $subject = $this->lang->line('SIGPLUS - '.$titulo);
      

        $from = 'noreply@sigplus.online';
        $from_name = "SigPlus";
        
        
        //$this->sma->send_email_credencial($email, $subject, $message, $from, $from_name,null,$cc);  
        //$this->sma->send_email_credencial($email, $subject, $message, $from, $from_name,null,$cc);
        $this->sma->send_email_credencial($email, $subject, $message, $from, $from_name,null,$cc);
            
    }
    
    
    // ENVIA UM EMAIL DE LEMBRETE PARA OS USUÁRIOS COM AÇÕES ATRASADAS
     public function emailUsuarioAcoesAtrasadas($id_usuario, $projeto, $nome_projeto){
       
        
        //$acao =  $this->atas_model->getPlanoByID($id_acao);  
        // $usuario = $acao->responsavel;   
        $users = $this->site->geUserByIDSemEmpresa($id_usuario);   
        $email = $users->email;
        $empresa_user = $users->empresa_id;
        $nome_usuario = $users->first_name;
        
        
        
        $empresa_dados = $this->owner_model->getEmpresaById($empresa_user);
        $nome_empresa = $empresa_dados->razaoSocial;
            
        
            /*
             * QUANTIDADE DE AÇÕES ATRASADAS
             */
             $qtde_acoes_users = $this->reports_model->getContAcoesAtrasadasByUser($projeto, $id_usuario);   
             $quantidade_acao = $qtde_acoes_users->quantidade;
            
            if($quantidade_acao > 1){
                $texto = 'Ações';
            }else{
                $texto = 'Ação';
            }
            
            $titulo = "Ações atrasadas ";
            
            /*
             * LISTA AS AÇÕES ATRASADAS
             */
             $acoes_users = $this->reports_model->getAllPlanosAtrasadosProjetobyUser($projeto, $id_usuario);   
             foreach ($acoes_users as $acao_user) {
                $dados_acao_usuario .= 'Ação : '.$acao_user->idplanos . ' - ' .$acao_user->descricao . '. Seu Prazo Venceu em : '. date("d/m/Y", strtotime($acao_user->data_termino)).' <br><br>' ;
              
             }
           
            
            $this->load->library('parser');
                $parse_data = array(
                   'usuario' =>  $nome_usuario,
                   'site_link' => site_url(),
                   'qtde_acoes' => $quantidade_acao, 
                   'acao' => $dados_acao_usuario,
                   'projeto' => $nome_projeto, 
                   'titulo' => $titulo,
                   'texto' => $texto,
                   'status' => 'ATRASADO',
                   'empresa' => $nome_empresa,
                   'logo' => '<img src="' . base_url() . 'assets/uploads/logos/' . $this->Settings->logo . '" alt="' . $this->Settings->site_name . '"/>'
                );
                
            $msg = file_get_contents('./themes/' . $this->theme . 'email_templates/sig_envio_usuario_acao_atrasada.php');
            $message = $this->parser->parse_string($msg, $parse_data);
            $subject = $this->lang->line('Ações Atrasadas');
          
            
            $from = 'noreply@sigplus.online';
            $from_name = "Sigplus.online";
        
        

        if($this->sma->send_email_credencial($email, $subject, $message, $from, $from_name,null,$cc)){
            return true;
        }
            

            
    }
    
    
    
    public function retornoUsuario($id = null){
       
        
        
        $acao =  $this->atas_model->getPlanoByID($id);                    
        $usuario = $this->session->userdata('user_id');   
        $users = $this->site->geUserByID($usuario);   
        /*
         * Projeto Atual PELA ATA DA AÇÃO
         */
        $ata_acao = $this->atas_model->getAtaProjetoByID_ATARetornoUsuario($acao->idatas);
        $projeto_id = $ata_acao->projeto_id;
        $id_edp = $ata_acao->edp;
        
      //  $projetos_usuario = $this->site->getProjetoAtualByID_completo($usuario);
        //$projeto_atual = $projetos_usuario->projeto_atual;
        //$edp_projeto_atual = $projetos_usuario->edp_id;
        $usuario_edp_responsavel_projeto = $this->site->geUserByID($id_edp);  
        $email_resp_edp = $usuario_edp_responsavel_projeto->email;
            /*
             * ENVIAR EMAIL
             */
        
           // $email = 'israel.araujo@unimedmanaus.com.br'; 
            
           $email = $email_resp_edp;//'alice.cabral@unimedmanaus.com.br';
            $this->load->library('parser');
                $parse_data = array(
                   'usuario' =>  $users->first_name .' '. $users->last_name,
                   'site_link' => site_url(),
                   'acao' => $id,
                   'logo' => '<img src="' . base_url() . 'assets/uploads/logos/' . $this->Settings->logo . '" alt="' . $this->Settings->site_name . '"/>'
                );
                
            $msg = file_get_contents('./themes/' . $this->theme . 'email_templates/retorno_usuario.html');
            $message = $this->parser->parse_string($msg, $parse_data);
            $subject = $this->lang->line('Retorno de ação') . ' - ' . $id;
           // $this->sma->send_email($email, $subject, $message);
           // redirect("welcome");
             if ($this->sma->send_email($email, $subject, $message)) {
                    return true;
                }
            
    }

    public function emailEdpUsuario($id = null){
       
        $acao =  $this->atas_model->getPlanoByID($id);  
        
        $usuario = $acao->responsavel;   
        $users = $this->site->geUserByID($usuario);   
        
            /*
             * ENVIAR EMAIL
             */
        
            //$email = 'israel.araujo@unimedmanaus.com.br';
            $email = $users->email;
            $this->load->library('parser');
                $parse_data = array(
                   'usuario' =>  $users->first_name .' '. $users->last_name,
                   'site_link' => site_url(),
                   'acao' => $id,
                   'status' => $acao->status,
                   'logo' => '<img src="' . base_url() . 'assets/uploads/logos/' . $this->Settings->logo . '" alt="' . $this->Settings->site_name . '"/>'
                );
                
            $msg = file_get_contents('./themes/' . $this->theme . 'email_templates/envio_usuario.php');
            $message = $this->parser->parse_string($msg, $parse_data);
            $subject = $this->lang->line('Resposta da ação') . ' - ' . $id;
           // $this->sma->send_email($email, $subject, $message);
           // redirect("welcome");
             if ($this->sma->send_email($email, $subject, $message)) {
                    return true;
                }
            
    }
    /*
     * ENVIA EMAIL DE CONVOCAÇÃO PARA REUNIÃO OU TREINAMENTO (ATA)
     */
    public function emailAtaConvocacao($id_usuario, $id_ata, $convocacao_id){
       
        $ata =  $this->atas_model->getAtaByID($id_ata);  
        $projeto =  $this->projetos_model->getProjetoByID($ata->projetos);  
        $tipo = $ata->tipo;
        if($tipo == "AVULSA"){
            $tipo = "REUNIÃO";
        }
        $texto = $ata->texto_convocacao;
        $pauta = $ata->pauta;
        $local = $ata->local;
        $responsavel = $ata->responsavel_elaboracao;
        $data_reuniao = $this->sma->hrld($ata->data_ata);
        //DADOS DO USUÁRIO
        $users = $this->site->geUserByID($id_usuario);   
        
       // $id_convocacao2 = base64_encode($convocacao_id);   
        //$ata_id = base64_encode($id_ata);
        
            /*
             * ENVIAR EMAIL
             */
        
            //$email = 'israel.araujo@unimedmanaus.coop.br';
            $email = $users->email;
            
            
            
            
            $this->load->library('parser');
                $parse_data = array(
                   'usuario' =>  $users->first_name .' '. $users->last_name,
                   'site_link' => site_url(),
                   'link_sim' => site_url('Reports/linkConfirmaConvocacao/'."$convocacao_id"),
                   'link_nao' => site_url('Reports/linkNaoConfirmaConvocacao/'."$convocacao_id"),
                   'tipo' => $tipo,
                   'local' => $local,
                   'responsavel' => $responsavel,
                   'projeto' => $projeto->projeto,
                   'texto' => $texto,
                   'pauta' => $pauta,
                    'data' => $data_reuniao,
                   'logo' => '<img src="' . base_url() . 'assets/uploads/logos/' . $this->Settings->logo . '" alt="' . $this->Settings->site_name . '"/>'
                );
                
            $msg = file_get_contents('./themes/' . $this->theme . 'email_templates/envio_convocacao_reuniao.php');
            $message = $this->parser->parse_string($msg, $parse_data);
            $subject = $this->lang->line('Aviso de Convocação');
          
            
             if ($this->sma->send_email($email, $subject, $message)) {
                    return true;
                }
            
    }
    
    /*
     * ENVIA EMAIL DE CANCELAMENTO DE CONVOCAÇÃO PARA REUNIÃO OU TREINAMENTO (ATA)
     */
    public function emailCancelaConvocacao($id_usuario, $id_ata, $convocacao_id){
       
        $ata =  $this->atas_model->getAtaByID($id_ata);  
        $projeto =  $this->projetos_model->getProjetoByID($ata->projetos);  
        $tipo = $ata->tipo;
        if($tipo == "AVULSA"){
            $tipo = "REUNIÃO";
        }
        $texto = $ata->texto_convocacao;
        $pauta = $ata->pauta;
        $local = $ata->local;
        $responsavel = $ata->responsavel_elaboracao;
        $data_reuniao = $this->sma->hrld($ata->data_ata);
        //DADOS DO USUÁRIO
        $users = $this->site->geUserByID($id_usuario);   
        
       // $id_convocacao2 = base64_encode($convocacao_id);   
        //$ata_id = base64_encode($id_ata);
        
            /*
             * ENVIAR EMAIL
             */
        
            //$email = 'israel.araujo@unimedmanaus.coop.br';
            $email = $users->email;
            
            
            
            
            $this->load->library('parser');
                $parse_data = array(
                   'usuario' =>  $users->first_name .' '. $users->last_name,
                   'site_link' => site_url(),
                   'link_sim' => site_url('Reports/linkConfirmaConvocacao/'."$convocacao_id"),
                   'link_nao' => site_url('Reports/linkNaoConfirmaConvocacao/'."$convocacao_id"),
                   'tipo' => $tipo,
                   'local' => $local,
                   'responsavel' => $responsavel,
                   'projeto' => $projeto->projeto,
                   'texto' => $texto,
                   'pauta' => $pauta,
                    'data' => $data_reuniao,
                   'logo' => '<img src="' . base_url() . 'assets/uploads/logos/' . $this->Settings->logo . '" alt="' . $this->Settings->site_name . '"/>'
                );
                
            $msg = file_get_contents('./themes/' . $this->theme . 'email_templates/envio_cancela_convocacao_reuniao.php');
            $message = $this->parser->parse_string($msg, $parse_data);
            $subject = $this->lang->line('Aviso de Convocação');
          
            
             if ($this->sma->send_email($email, $subject, $message)) {
                    return true;
                }
            
    }
    
    /*
     * ENVIA EMAIL DE CONVOCAÇÃO PARA REUNIÃO OU TREINAMENTO (ATA)
     */    
    public function enviaInviteHotmail( $convocacao_id){
       
        $ata =  $this->atas_model->getAtaByID($id_ata);  
        $projeto =  $this->projetos_model->getProjetoByID($ata->projetos);  
        $tipo = $ata->tipo;
        $texto = $ata->texto_convocacao;
        $pauta = $ata->pauta;
        $data_reuniao = $this->sma->hrld($ata->data_ata);
        //DADOS DO USUÁRIO
        $users = $this->site->geUserByID($id_usuario);   
        
       // $id_convocacao2 = base64_encode($convocacao_id);   
        //$ata_id = base64_encode($id_ata);
        
            /*
             * ENVIAR EMAIL
             */
        
            //$email = 'israel.araujo@unimedmanaus.coop.br';
            $email = $users->email;
            
            
            
            
            $this->load->library('parser');
                $parse_data = array(
                   'usuario' =>  $users->first_name .' '. $users->last_name,
                   'site_link' => site_url(),
                   'link_sim' => site_url('Reports/linkConfirmaConvocacao/'."$convocacao_id"),
                   'link_nao' => site_url('Reports/linkNaoConfirmaConvocacao/'."$convocacao_id"),
                   'tipo' => $tipo,
                   'projeto' => $projeto->projeto,
                   'texto' => $texto,
                   'pauta' => $pauta,
                    'data' => $data_reuniao,
                   'logo' => '<img src="' . base_url() . 'assets/uploads/logos/' . $this->Settings->logo . '" alt="' . $this->Settings->site_name . '"/>'
                );
                
            $msg = file_get_contents('./themes/' . $this->theme . 'email_templates/envio_convocacao_reuniao.php');
            $message = $this->parser->parse_string($msg, $parse_data);
            $subject = $this->lang->line('Aviso de Convocação');
          
            
             if ($this->sma->send_email($email, $subject, $message)) {
                    return true;
                }
            
    }
    
    
    
      function encrypt($str, $key)
        {
           
            for ($return = $str, $x = 0, $y = 0; $x < strlen($return); $x++)
            {
                $return{$x} = chr(ord($return{$x}) ^ ord($key{$y}));
                $y = ($y >= (strlen($key) - 1)) ? 0 : ++$y;
            }

            return $return;
        }
    
    /*
     * ENVIA EMAIL DE AVALIAÇÃO DE RAAÇÃO PARA PARTICIPANTES DE TREINAMENTO
     */
    public function emailAvaliacaoReacaoTreinamento($id_participante, $id_usuario){
       
       
        $users = $this->site->geUserByID($id_usuario);   
        
            /*
             * ENVIAR EMAIL
             */
        
            //$email = 'alice.silva@unimedmanaus.com.br';
            $email = $users->email;
            
           
            $this->load->library('parser');
                $parse_data = array(
                   'usuario' =>  $users->first_name .' '. $users->last_name,
                   'site_link' => 'http://sig.unimedmanaus.com.br/index.php/welcome/pesquisa_reacao/'.$id_participante,
                   'logo' => '<img src="' . base_url() . 'assets/uploads/logos/' . $this->Settings->logo . '" alt="' . $this->Settings->site_name . '"/>'
                );
                
            $msg = file_get_contents('./themes/' . $this->theme . 'email_templates/envio_avaliacao_reacao.php');
            $message = $this->parser->parse_string($msg, $parse_data);
            $subject = $this->lang->line('Pesquisa - Avaliação de Reação de Treinamento');
          
            
             if ($this->sma->send_email($email, $subject, $message)) {
                    return true;
            }
            
    }
    
   
    
     /*
     * ENVIA EMAIL DE NOTIFICAÇÃO PARA OS GESTORES DOS PROJETOS
     */
    public function emailAcosGestores($usuario, $projeto, $perfil){
       
           
           // exit;
            $users = $this->site->geUserByID($usuario);  
            //$email = 'israel.araujo@unimedmanaus.coop.br';
            $email = $users->email;
           // $cc = "gabriel.rando@unimedmanaus.coop.br";
             
            
             //NOME DO PROJETO
             $projetos_usuario = $this->site->getProjetoAtualByID_completo($usuario);
          
             
                $msg = file_get_contents('http://managerprojetos.unimedmanaus.com.br/index.php/Reports/dashboard_projetos_gestor/'.$projeto.'/'.$usuario.'/'.$perfil); //$msg_email; 
                $message = $this->parser->parse_string($msg,"");
                $subject = $this->lang->line('Relatório de Status do Gestor');
          
               
            
             if ($this->sma->send_email($email, $subject, $message,null, null,null,$cc)) {
                   return true;
                }
            
    }
    /*
     * ENVIA EMAIL DE NOTIFICAÇÃO DE GERAÇÃO DO STATUS REPORT
     */
    public function emailControleSobreaviso($email,$texto, $acao, $obs, $tipo){
       
            /*
             * ENVIAR EMAIL
             */
        
            //$email = 'israel.araujo@unimedmanaus.coop.br'; //'iaraujo.israel@gmail.com'.//'';
          //  $cc = 'gabriel.rando@unimedmanaus.coop.br';//'alice.cabral@unimedmanaus.coop.br';
           // $email = $users->email;
            
            $this->load->library('parser');
                $parse_data = array(
                  'logo' => '<img src="' . base_url() . 'assets/uploads/logos/' . $this->Settings->logo . '" alt="' . $this->Settings->site_name . '"/>',  
                    'texto'=> $texto,
                    'acao'=> $acao,
                    'obs'=> $obs
                 );
                
            $msg = file_get_contents('./themes/' . $this->theme . 'email_templates/envio_controle_sobreaviso.php');
            $message = $this->parser->parse_string($msg, $parse_data);
            $subject = $this->lang->line('CHAMADO DE SOBREAVISO -'.$tipo.'!!!');
          
            //
             if ($this->sma->send_email($email, $subject, $message,null, null,null,$cc)) {
                    return true;
                }
            
    }
    
    public function reenviarCredencialNovoUsuario($id, $nova_senha){
        
        $this->load->library('parser');
        $dados = $this->site->getUser($id);
        $id_usuario = $dados->id;
        $nome = $dados->first_name;
        $code = $dados->activation_code;
        $email = $dados->email;
        
       // echo $nova_senha; exit;
        // MUDA A SENHA
        
        /*
          $data = array(
                'password' => $nova_senha
            );
            $this->db->update($this->tables['users'], $data, array('id' => $id_usuario));
         * 
         */
        $this->ion_auth->reset_password($email, $nova_senha);
            
        $parse_data = array(
            'client_name' => $nome,
            'site_link' => site_url(),
        //    'confirma_email' => site_url('auth/activate/'.$id),
            'activation_link' => anchor('auth/activate/' . $id_usuario . '/' . $code, lang('Ativar E-mail')),
            'site_name' => $this->Settings->site_name,
            'email' => $email,
            'password' => $nova_senha,
            'logo' => '<img src="' . base_url() . 'assets/uploads/logos/' . $this->Settings->logo . '" alt="' . $this->Settings->site_name . '"/>'
        );
        // print_r($parse_data); exit;
        
        $msg = file_get_contents('./themes/' . $this->theme . 'email_templates/credentials.php');
        $message = $this->parser->parse_string($msg, $parse_data);
        $subject = $this->lang->line('Detalhes conta') . ' - ' . $this->Settings->site_name;
       // $this->sma->send_email($email, $subject, $message);
        $from = 'noreply@sigplus.online';
        $from_name = "SigPlus";
        $this->sma->send_email_credencial($email, $subject, $message, $from, $from_name,null,$cc);  
        
         
        
    }
    
    
    /*
     * ENVIA EMAIL DE NOTIFICAÇÃO DE GERAÇÃO DO STATUS REPORT
     */
    public function emailControleServidor($data_email,$texto){
       
            /*
             * ENVIAR EMAIL
             */
            $from = 'noreply@sigplus.online';
            $from_name = "SigPlus";
            $email = 'iaraujo.israel@gmail.com';
           //  $cc = 'gabriel.rando@unimedmanaus.coop.br';//'alice.cabral@unimedmanaus.coop.br';
           // $email = $users->email;
            
            $this->load->library('parser');
                $parse_data = array(
                  'data' => $data_email,
                  'logo' => '<img src="' . base_url() . 'assets/uploads/logos/' . $this->Settings->logo . '" alt="' . $this->Settings->site_name . '"/>',  
                    'texto'=> $texto
                 );
                
            $msg = file_get_contents('./themes/' . $this->theme . 'email_templates/envio_controle.php');
            $message = $this->parser->parse_string($msg, $parse_data);
            $subject = $this->lang->line('Aviso de Controle do Servidor');
          
            //
             if ($this->sma->send_email($email, $subject, $message, $from, $from_name,null,$cc)) {
                    return true;
                }
            
    }
    
    /*
     * ENVIA EMAIL DE NOTIFICAÇÃO DE GERAÇÃO DO STATUS REPORT
     */
    public function emailAvisoGerouStatuRepor(){
       
            /*
             * ENVIAR EMAIL
             */
        
            $email = 'israel.araujo@unimedmanaus.coop.br'; //'iaraujo.israel@gmail.com'.//'';
            $cc = 'iaraujo.israel@gmail.com';//'alice.cabral@unimedmanaus.coop.br';
           // $email = $users->email;
            
            $this->load->library('parser');
                $parse_data = array(
                  
                   'logo' => '<img src="' . base_url() . 'assets/uploads/logos/' . $this->Settings->logo . '" alt="' . $this->Settings->site_name . '"/>'
                );
                
            $msg = file_get_contents('./themes/' . $this->theme . 'email_templates/envio_gerou_status_report.php');
            $message = $this->parser->parse_string($msg, $parse_data);
            $subject = $this->lang->line('Aviso de Status Report Gerado');
          
            //
             if ($this->sma->send_email($email, $subject, $message,null, null,null,$cc)) {
                    return true;
                }
            
    }
    
   
    
    public function logout()
    {
        $this->auth_model->trigger_events('logout');

        if ($this->Settings->mmode) {
            if (!$this->ion_auth->in_group('owner')) {
                $this->set_message('site_is_offline_plz_try_later');
            } else {
                $this->set_message('logout_successful');
            }
        }

        $identity = $this->config->item('identity', 'ion_auth');
        $this->session->unset_userdata(array($identity => '', 'id' => '', 'user_id' => ''));

        //delete the remember me cookies if they exist
        if (get_cookie('identity')) {
            delete_cookie('identity');
        }
        if (get_cookie('remember_code')) {
            delete_cookie('remember_code');
        }

        //Destroy the session
        $this->session->sess_destroy();

        return TRUE;
    }


    public function logged_in()
    {
        $this->auth_model->trigger_events('logged_in');

        return (bool)$this->session->userdata('identity');
    }

    public function get_user_id()
    {
        $user_id = $this->session->userdata('user_id');
        if (!empty($user_id)) {
            return $user_id;
        }
        return null;
    }

    public function in_group($check_group, $id = false)
    {
        $this->auth_model->trigger_events('in_group');

        $id || $id = $this->session->userdata('user_id');

        $group = $this->getUserGroup($id);

        if ($group->name === $check_group) {
            return TRUE;
        }

        return FALSE;
    }

    public function getUserGroup($user_id = false)
    {
        $user_id || $user_id = $this->session->userdata('user_id');

        $group_id = $this->getUserGroupID($user_id);
        return $this->ion_auth->group($group_id)->row();

    }

    public function getUserGroupID($user_id = false)
    {
        $user_id || $user_id = $this->session->userdata('user_id');

        $user = $this->ion_auth->user($user_id)->row();
        return $user->group_id;
    }


}
