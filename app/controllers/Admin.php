<?php  defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends MY_Controller
{
  
    function __construct()
    {
        
        parent::__construct();

        if (!$this->loggedIn) {
            $this->session->set_userdata('requested_page', $this->uri->uri_string());
            redirect('login');
        }
        
       $this->lang->load('auth', $this->Settings->user_language);
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
        $this->load->model('auth_model');
        $this->load->library('ion_auth');
        $this->load->model('owner_model');
        $this->load->model('atas_model');
        $this->load->model('networking_model');
        $this->digital_upload_path = 'assets/uploads/historico_acoes/';
        $this->upload_path = 'assets/uploads/historico_acoes/';
        $this->thumbs_path = 'assets/uploads/thumbs/';
        $this->image_types = 'gif|jpg|jpeg|png|tif';
        $this->digital_file_types = 'zip|psd|ai|rar|pdf|doc|docx|xls|xlsx|ppt|pptx|gif|jpg|jpeg|png|tif|txt|xlt|xltx';
    }

    public function index()
    {
          
        if ($this->Settings->version == '2.3') {
            $this->session->set_flashdata('warning', 'Please complete your update by synchronizing your database.');
            redirect('sync');
        }

        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        
        $usuario = $this->session->userdata('user_id');                     
        $this->data['planos'] = $this->atas_model->getAllPlanosUser($usuario);
       
        
       
        // SALVA O MÓDULO ATUAL do usuário
         $data_modulo = array('modulo_atual' => 2, 'menu_atual' => 22);
         $this->owner_model->updateModuloAtual($usuario, $data_modulo);
                    
        // registra o log de movimentação
         
       $users_dados = $this->site->geUserByID($usuario);
         $modulo_atual = $users_dados->modulo_atual;
         $menu_atual = $users_dados->modulo_atual;
         $nome = $users_dados->first_name;
         
         $modulo_atual_id = $users_dados->modulo_atual;
         $empresa = $users_dados->empresa_id;

         $modulo = $this->owner_model->getModuloById($modulo_atual_id);
         $nome_modulo = $modulo->descricao;
         $cor_modulo = $modulo->cor;

         $empresa = $this->owner_model->getEmpresaById($empresa);
         $nome_empresa = $empresa->razaoSocial;
 
        // registra o log de movimentação
         $tipo = "ADMIN";
         $texto = "O usuário $nome, da empresa $nome_empresa acessou o módulo $nome_modulo";
         $tabela = "";
         $row = "";
         $depois = "";
         $modulo = "Admin";
         $funcao = "Admin/index";
         $this->registraLog($tipo, $texto, $tabela, $row, $depois, $modulo, $funcao);

         
         $users_dados = $this->site->geUserByID($usuario);
         
         $modulo_atual = $users_dados->modulo_atual;
         $menu_atual = $users_dados->modulo_atual;
         
        
         
        $this->data['pagina'] = 'owner/index';
        $this->data['ativo'] = 'home';
        $this->data['menu'] = 'home'; //footer
        $this->data['home'] = '1';
        
       $modulo_dados = $this->owner_model->getModuloById($modulo_atual);
       $controle = $modulo_dados->controle;
       $pagina_home = $modulo_dados->home;
      
          
         $this->page_construct_admin($pagina_home, $meta, $this->data);
       
    }
    
    /*
     * FUNÇÃO LOG
     */
    public function registraLog($tipo, $texto, $tabela, $row, $depois, $modulo, $funcao){
        
        $date_hoje = date('Y-m-d H:i:s');    
        $usuario = $this->session->userdata('user_id');
        $empresa = $this->session->userdata('empresa');
        $ip = $_SERVER["REMOTE_ADDR"];

        $logdata = array('date' => date('Y-m-d H:i:s'), 
            'type' => $tipo, 
            'description' => $texto,  
            'userid' => $this->session->userdata('user_id'), 
            'ip_address' => $_SERVER["REMOTE_ADDR"],
            'tabela' => $tabela,
            'row' => $row,
            'depois' => $depois, 
            'modulo' => $modulo,
            'funcao' => $funcao,  
            'empresa' => $this->session->userdata('empresa'));
        $this->owner_model->addLog($logdata);                 
    }
   
    
    
    /************************************************************************************************************
     ******************************************* CADASTROS SETORES **********************************************
     ************************************************************************************************************/
   
    public function cadastrarSetor($tabela_id,$cadastro_id, $menu)
    {
        $date_cadastro = date('Y-m-d H:i:s');                           
        
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
       
        
        $this->form_validation->set_rules('id_cadastro', lang("id_cadastro"), 'required');
         
         if ($this->form_validation->run() == true) {
             
             $pai= $this->input->post('pai');
             $raiz = $this->input->post('raiz');
             $subsetor = $this->input->post('subsetor');
             $tabela_id = $this->input->post('tabela_id');
             $menu_id = $this->input->post('menu_id');
             
             
               

                     $logdata = array(
                         'pai' => $pai,
                         'raiz' => $raiz,
                         'nome' => $subsetor,
                         'empresa_id' => $this->session->userdata('empresa'));
                     
                     $id_tabela =  $this->owner_model->addSetor($logdata);  
                     
                     $usuario = $this->session->userdata('user_id');
                     // registra o log de movimentação
                         $users_dados = $this->site->geUserByID($usuario);
                         $modulo_atual = $users_dados->modulo_atual;
                         $menu_atual = $users_dados->modulo_atual;
                         $nome = $users_dados->first_name;

                         $modulo_atual_id = $users_dados->modulo_atual;
                         $empresa = $users_dados->empresa_id;

                         $modulo = $this->owner_model->getModuloById($modulo_atual_id);
                         $nome_modulo = $modulo->descricao;
                         $cor_modulo = $modulo->cor;

                         $empresa = $this->owner_model->getEmpresaById($empresa);
                         $nome_empresa = $empresa->razaoSocial;
                 
                 
                        // registra o log de movimentação
                         $tipo = "ADMIN - INSERT";
                         $texto = "O usuário $nome, da empresa $nome_empresa cadastrou o setor de Id : $id_tabela, da tabela Id: $tabela_id";
                         $tabela_log = "sig_setores";
                         $row = "$id_tabela";
                         $depois = json_encode($logdata);
                         $modulo = "Admin";
                         $funcao = "Admin/cadastrarSetor";
                         $this->registraLog($tipo, $texto, $tabela_log, $row, $depois, $modulo, $funcao);

                       
                        
                    
            $this->session->set_flashdata('message', lang("Dados enviado com Sucesso!!!"));
            redirect("owner/cadastro/$tabela_id/$menu_id");
            
         }else{
       
           
        
        $tabela_cadastro = $this->owner_model->getTableById($tabela_id);
            $tabela_nome = $tabela_cadastro->tabela;
            
            $this->data['tabela_nome'] = $tabela_nome;
            $this->data['tabela_id'] = $tabela_id;
            $this->data['titulo'] = $tabela_cadastro->titulo;
            $this->data['descricao_titulo'] = $tabela_cadastro->descricao;
            $this->data['menu_id'] = $menu;
            $this->data['menu'] = "cadastro";
            $this->data['submenu'] = "modulo";
            $this->data['cadastro_id'] = $cadastro_id;
            $this->data['setor'] = $this->owner_model->getDadosTablesCadastroById($tabela_nome, $cadastro_id);
            // $this->data['pai'] = $pai;
       // $this->data['raiz'] = $raiz;
        
        //    $id_descriptografado =  $this->encrypt($id_registro,'UNIMED');
        $usuario = $this->session->userdata('user_id');                     
        $this->load->view($this->theme . 'admin/cadastros/setores/cadastrarSetor', $this->data);
           
         }
            
    }
    
    
    /************************************************************************************************************
     ******************************************* CADASTROS USUÁRIOS *********************************************
     ************************************************************************************************************/
    
    
    public function usuarios($tabela, $menu)
    {
     
         $this->sma->checkPermissions();
        
        $this->form_validation->set_rules('id_cadastro', lang("id_cadastro"), 'required');
         
         if ($this->form_validation->run() == true) {
           
             $tabela_id = $this->input->post('tabela_id');
             $tabela_nome = $this->input->post('tabela_nome');
            // echo $tabela_nome.'<br>';
             $campos = $this->owner_model->getAllCamposTablesCadastro($tabela_id);
             $data_modulo = array();
              foreach ($campos as $habilitado) {
                    $campo_banco = $habilitado->campo;
                    $nome_campo = $habilitado->nome_campo;
                    $tipo_campo = $habilitado->tipo_campo;
                    $tipo_texto = $habilitado->tipo_texto;
                    $tamanho = $habilitado->tamanho;
                    $obrigatorio = $habilitado->obrigatorio;
                    
                    $campo_cadastro = $this->input->post($campo_banco);
                    
                    $data_modulo[] = array(
                        $campo_banco => $campo_cadastro,
                       
                    );
                    
              }
              $data_campos_cadastro = call_user_func_array('array_merge', $data_modulo);
              $tabela_sig = substr($tabela_nome, 4);
           //   print_r($data_campos_cadastro); exit;
              $id_cadastro =  $this->owner_model->addCadastro($tabela_sig, $data_campos_cadastro);
              
            $date_hoje = date('Y-m-d H:i:s');    
            $usuario = $this->session->userdata('user_id');
            $empresa = $this->session->userdata('empresa');
            $ip = $_SERVER["REMOTE_ADDR"];

            $logdata = array('date' => date('Y-m-d H:i:s'), 
                'type' => 'INSERT', 
                'description' => 'Cadastro de um novo '.$tabela,  
                'userid' => $this->session->userdata('user_id'), 
                'ip_address' => $_SERVER["REMOTE_ADDR"],
                'tabela' => $tabela,
                'row' => $id_cadastro,
                'depois' => json_encode($data_campos_cadastro), 
                'modulo' => 'owner',
                'funcao' => 'owner/cadastroBasicoModelo',  
                'empresa' => $this->session->userdata('empresa'));
           
               $this->owner_model->addLog($logdata);  
           // exit;
            
            $this->session->set_flashdata('message', lang("Cadastro realizado com Sucesso!!!"));
            redirect("owner/cadastro/$tabela_id");
            
         }else{
             
            $tabela_cadastro = $this->owner_model->getTableById(3);
            $tabela_nome = $tabela_cadastro->tabela;
            $menu = 27;
        
            
                // SALVA O MÓDULO ATUAL do usuário
                 $usuario = $this->session->userdata('user_id');    
                 $data_modulo = array('menu_atual' => $menu);
                 $this->owner_model->updateModuloAtual($usuario, $data_modulo);

                // registra o log de movimentação

                $date_hoje = date('Y-m-d H:i:s');    
                $usuario = $this->session->userdata('user_id');
                $empresa = $this->session->userdata('empresa');
                $ip = $_SERVER["REMOTE_ADDR"];

                 $logdata = array('date' => date('Y-m-d H:i:s'), 
                    'type' => 'ACESSO', 
                    'description' => "Acessou o menu $menu do Módulo ADMIN",  
                    'userid' => $this->session->userdata('user_id'), 
                    'ip_address' => $_SERVER["REMOTE_ADDR"],
                    'tabela' => '',
                    'row' => '',
                    'depois' => '', 
                    'modulo' => 'admin',
                    'funcao' => 'admin/usuarios',  
                    'empresa' => $this->session->userdata('empresa'));
                    $this->owner_model->addLog($logdata); 
            
            $this->data['usuarios'] = $this->owner_model->getAllUsersByEmpresa();
            
            $this->page_construct_admin('admin/cadastros/usuarios/index', $meta, $this->data);
         }
    }
   
    function gerar_senha($tamanho, $maiusculas, $minusculas, $numeros, $simbolos){
      $ma = "ABCDEFGHIJKLMNOPQRSTUVYXWZ"; // $ma contem as letras maiúsculas
      $mi = "abcdefghijklmnopqrstuvyxwz"; // $mi contem as letras minusculas
      $nu = "0123456789"; // $nu contem os números
      $si = "!@#$%¨&*()_+="; // $si contem os símbolos

      if ($maiusculas){
            // se $maiusculas for "true", a variável $ma é embaralhada e adicionada para a variável $senha
            $senha .= str_shuffle($ma);
      }

        if ($minusculas){
            // se $minusculas for "true", a variável $mi é embaralhada e adicionada para a variável $senha
            $senha .= str_shuffle($mi);
        }

        if ($numeros){
            // se $numeros for "true", a variável $nu é embaralhada e adicionada para a variável $senha
            $senha .= str_shuffle($nu);
        }

        if ($simbolos){
            // se $simbolos for "true", a variável $si é embaralhada e adicionada para a variável $senha
            $senha .= str_shuffle($si);
        }

        // retorna a senha embaralhada com "str_shuffle" com o tamanho definido pela variável $tamanho
        return substr(str_shuffle($senha),0,$tamanho);
    }
    
    public function novo_usuario()
    {
     
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        
        $this->form_validation->set_rules('novo_usuario', lang("id_cadastro"), 'required');
         $this->form_validation->set_rules('nome', lang("Nome"), 'required');
        //$this->form_validation->set_rules('email', lang("email"), 'trim|is_unique[users.email]');
        
         if ($this->form_validation->run() == true) {
           
             $nome = $this->input->post('nome');
             $email = $this->input->post('email');
             $consultor = $this->input->post('consultor');
             $cargo = $this->input->post('cargo');
             $setor = $this->input->post('setor');
             $genero = $this->input->post('genero');
             $data_admissao = $this->input->post('data_admissao');
             $data_nascimento = $this->input->post('data_nascimento');
             $ramal = $this->input->post('ramal');
             $celular1 = $this->input->post('celular1');
             $celular_corportativo = $this->input->post('celular_corportativo');
             $networking = 1;// $this->input->post('networking');
             $project = $this->input->post('project');
             $admin = $this->input->post('admin');
             $administrador = $this->input->post('administrador');
             $criar_projetos = $this->input->post('criar_projetos');
             $publicacoes_institucionais = $this->input->post('publicacoes_institucionais');
             $password_provisorio = $this->gerar_senha(8, true, true, true, false);
             
             // GRUPO
             // ADMINISTRADOR = 1
             // CONSULTOR = 2
             // FUNCINIONARIO = 5
             if($administrador == 1){
                 $grupo = 1;
             }else{
                 if($consultor == 0){
                  $grupo = 5;   
                 }else{
                  $grupo = 2;
                 }
             }
             
           if (!isset($nome) || empty($nome)) {
            $valor = 0;
            $erro = 'Informe seu nome.';
            echo "<script>alert('Informe seu nome.')</script>";
                echo "<script>history.go(-1)</script>"; exit;
            } else{
                $valor = 1;
            }

            // EMAIL
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
                 echo "<script>history.go(-1)</script>";
            }
            $empresa = $this->session->userdata('empresa');
            $dados = $this->site->getUserbyemail($email, $empresa);
            $email_cadastrado = $dados->email;

            // Também verifica se não existe nenhum erro anterior
            if ($email_cadastrado) {
                $valor = 0;
                $erro = 'Este E-mail já existe.';
                $this->data['error'] = $erro;
                $this->session->set_flashdata('error', $this->ion_auth->messages());
                $this->session->set_flashdata('error', lang("Este E-mail já existe!"));
                $this->session->set_flashdata('error', $erro);
                echo "<script>alert('Este E-mail já existe.')</script>";
                echo "<script>history.go(-1)</script>"; exit;
            }
               
             
             
            $data_user = array(
                'first_name' => $nome,
                'email' => $email,
                'username' => $email,
                'consultor' => $consultor,
                'cargo' => $cargo,
                'gender' => $genero,
                'data_admissao' => $data_admissao,
                'data_aniversario' => $data_nascimento,
                'ramal' => $ramal,
                'corporativo' => $celular_corportativo,
                'phone' => $celular1,
                'networking' => $networking,
                'project' => $project,
                'admin' => $admin,
                'administrador' => $administrador,
                'publicacoes_institucionais' => $publicacoes_institucionais,
                'criar_projetos' => $criar_projetos,
                'active' => 0,
                'confirmou_email' => 0,
                'mudar_senha' => 1,
                'group_id' => 1,
                'empresa_id' => $this->session->userdata('empresa')
          //      'password' => $password_provisorio
            );
            
            //print_r($data_user); exit;
           
           $id_usuario =  $this->ion_auth->register($password_provisorio, $email,   $data_user, $setor, $notify = TRUE);
            
           // $tabela_sig = 'fases_projeto';
           // $this->owner_model->updateCadastro($id_fase, $tabela_sig, $data_evento);
           
           // $this->auth_model->update($id_usuario, $data_user, $setor);
            
            $date_hoje = date('Y-m-d H:i:s');    
            $usuario = $this->session->userdata('user_id');
            $empresa = $this->session->userdata('empresa');
            $ip = $_SERVER["REMOTE_ADDR"];

            $logdata = array('date' => date('Y-m-d H:i:s'), 
                'type' => 'UPDATE', 
                'description' => 'Alterou o Usuário Id: '.$id_usuario,  
                'userid' => $this->session->userdata('user_id'), 
                'ip_address' => $_SERVER["REMOTE_ADDR"],
                'tabela' => 'sig_users',
                'row' => $id_usuario,
                'depois' => json_encode($data_user), 
                'modulo' => 'admin',
                'funcao' => 'admin/editar_usuario',  
                'empresa' => $this->session->userdata('empresa'));
           
               $this->owner_model->addLog($logdata);  
           // exit;
            $tabela = 55;
            $id_menu = 27;
            $this->session->set_flashdata('message', lang("Cadastro realizado com Sucesso!!!"));
            redirect("admin/usuarios/$tabela/$id_menu");
            
         }else{
             
          
            $this->data['submenu'] = "modulo";
            
                // registra o log de movimentação

                $date_hoje = date('Y-m-d H:i:s');    
                $usuario = $this->session->userdata('user_id');
                $empresa = $this->session->userdata('empresa');
                $ip = $_SERVER["REMOTE_ADDR"];

                $logdata = array('date' => date('Y-m-d H:i:s'), 
                    'type' => 'ACESSO', 
                    'description' => "Acessou o menu: Editar Fase do projeto",  
                    'userid' => $this->session->userdata('user_id'), 
                    'ip_address' => $_SERVER["REMOTE_ADDR"],
                    'tabela' => '',
                    'row' => '',
                    'depois' => '', 
                    'modulo' => 'owner',
                    'funcao' => 'owner/cadastro',  
                    'empresa' => $this->session->userdata('empresa'));
                    $this->owner_model->addLog($logdata); 
            
                //$this->data['modulos'] = $this->owner_model->getTablesCadastroBasico($tabela);
               // $dados = $this->site->getUser($usuario_selecionado);
              //  $this->data['dados'] = $dados;
              //  $this->data['usuario_id'] = $usuario_selecionado;    
            
            $this->page_construct_admin('admin/cadastros/usuarios/novo_usuario', $meta, $this->data);
            
         }
         
         
    }
    
     public function novo_usuario_modal()
    {
     
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        
        $this->form_validation->set_rules('novo_usuario', lang("id_cadastro"), 'required');
         $this->form_validation->set_rules('nome', lang("Nome"), 'required');
        //$this->form_validation->set_rules('email', lang("email"), 'trim|is_unique[users.email]');
        
         if ($this->form_validation->run() == true) {
           
             $nome = $this->input->post('nome');
             $email = $this->input->post('email');
             $consultor = $this->input->post('consultor');
             $cargo = $this->input->post('cargo');
             $setor = $this->input->post('setor');
             $genero = $this->input->post('genero');
             $data_admissao = $this->input->post('data_admissao');
             $data_nascimento = $this->input->post('data_nascimento');
             $ramal = $this->input->post('ramal');
             $celular1 = $this->input->post('celular1');
             $celular_corportativo = $this->input->post('celular_corportativo');
             $networking = 1;// $this->input->post('networking');
             $project = $this->input->post('project');
             $admin = $this->input->post('admin');
             $administrador = $this->input->post('administrador');
             $criar_projetos = $this->input->post('criar_projetos');
             $publicacoes_institucionais = $this->input->post('publicacoes_institucionais');
             $password_provisorio = $this->gerar_senha(8, true, true, true, false);
             
           if (!isset($nome) || empty($nome)) {
            $valor = 0;
            $erro = 'Informe seu nome.';
            echo "<script>alert('Informe seu nome.')</script>";
                echo "<script>history.go(-1)</script>"; exit;
            } else{
                $valor = 1;
            }

            // EMAIL
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
                 echo "<script>history.go(-1)</script>";
            }
            $empresa = $this->session->userdata('empresa');
            $dados = $this->site->getUserbyemail($email, $empresa);
            $email_cadastrado = $dados->email;

            // Também verifica se não existe nenhum erro anterior
            if ($email_cadastrado) {
                $valor = 0;
                $erro = 'Este E-mail já existe.';
                $this->data['error'] = $erro;
                $this->session->set_flashdata('error', $this->ion_auth->messages());
                $this->session->set_flashdata('error', lang("Este E-mail já existe!"));
                $this->session->set_flashdata('error', $erro);
                echo "<script>alert('Este E-mail já existe.')</script>";
                echo "<script>history.go(-1)</script>"; exit;
            }
               
             
             
            $data_user = array(
                'first_name' => $nome,
                'email' => $email,
                'username' => $email,
                'consultor' => $consultor,
                'cargo' => $cargo,
                'gender' => $genero,
                'data_admissao' => $data_admissao,
                'data_aniversario' => $data_nascimento,
                'ramal' => $ramal,
                'corporativo' => $celular_corportativo,
                'phone' => $celular1,
                'networking' => $networking,
                'project' => $project,
                'admin' => $admin,
                'administrador' => $administrador,
                'publicacoes_institucionais' => $publicacoes_institucionais,
                'criar_projetos' => $criar_projetos,
                'active' => 0,
                'confirmou_email' => 0,
                'mudar_senha' => 1,
                'empresa_id' => $this->session->userdata('empresa')
          //      'password' => $password_provisorio
            );
            
            //print_r($data_user); exit;
           
           $id_usuario =  $this->ion_auth->register($password_provisorio, $email,   $data_user, $setor, $notify = TRUE);
            
           // $tabela_sig = 'fases_projeto';
           // $this->owner_model->updateCadastro($id_fase, $tabela_sig, $data_evento);
           
           // $this->auth_model->update($id_usuario, $data_user, $setor);
            
            $date_hoje = date('Y-m-d H:i:s');    
            $usuario = $this->session->userdata('user_id');
            $empresa = $this->session->userdata('empresa');
            $ip = $_SERVER["REMOTE_ADDR"];

            $logdata = array('date' => date('Y-m-d H:i:s'), 
                'type' => 'UPDATE', 
                'description' => 'Alterou o Usuário Id: '.$id_usuario,  
                'userid' => $this->session->userdata('user_id'), 
                'ip_address' => $_SERVER["REMOTE_ADDR"],
                'tabela' => 'sig_users',
                'row' => $id_usuario,
                'depois' => json_encode($data_user), 
                'modulo' => 'admin',
                'funcao' => 'admin/editar_usuario',  
                'empresa' => $this->session->userdata('empresa'));
           
               $this->owner_model->addLog($logdata);  
           // exit;
            $tabela = 55;
            $id_menu = 27;
            $this->session->set_flashdata('message', lang("Cadastro realizado com Sucesso!!!"));
           // redirect("project/cadastro/$tabela_id/$menu_id");
             echo "<script>history.go(-1)</script>";
                exit;
          //  redirect("admin/usuarios/$tabela/$id_menu");
            
         }else{
             
          
            $this->data['submenu'] = "modulo";
            
                // registra o log de movimentação

                $date_hoje = date('Y-m-d H:i:s');    
                $usuario = $this->session->userdata('user_id');
                $empresa = $this->session->userdata('empresa');
                $ip = $_SERVER["REMOTE_ADDR"];

                $logdata = array('date' => date('Y-m-d H:i:s'), 
                    'type' => 'ACESSO', 
                    'description' => "Acessou o menu: Editar Fase do projeto",  
                    'userid' => $this->session->userdata('user_id'), 
                    'ip_address' => $_SERVER["REMOTE_ADDR"],
                    'tabela' => '',
                    'row' => '',
                    'depois' => '', 
                    'modulo' => 'owner',
                    'funcao' => 'owner/cadastro',  
                    'empresa' => $this->session->userdata('empresa'));
                    $this->owner_model->addLog($logdata); 
            
                //$this->data['modulos'] = $this->owner_model->getTablesCadastroBasico($tabela);
               // $dados = $this->site->getUser($usuario_selecionado);
              //  $this->data['dados'] = $dados;
              //  $this->data['usuario_id'] = $usuario_selecionado;    
            
           // $this->page_construct_admin('admin/cadastros/usuarios/novo_usuario', $meta, $this->data);
              $this->load->view($this->theme . 'admin/cadastros/usuarios/novo_usuario_modal', $this->data);
         }
         
         
    }
   
    public function editar_usuario($usuario_selecionado)
    {
     
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        
        $this->form_validation->set_rules('id_cadastro', lang("id_cadastro"), 'required');
        $this->form_validation->set_rules('usuario_id', lang("Usuário"), 'required');
        //$this->form_validation->set_rules('email', lang("email"), 'trim|is_unique[users.email]');
        
         if ($this->form_validation->run() == true) {
           
             //echo 'aqui'; exit;
             $id_usuario = $this->input->post('usuario_id');
             
             $nome = $this->input->post('nome');
             $email = $this->input->post('email');
             $consultor = $this->input->post('consultor');
             $cargo = $this->input->post('cargo');
             $setor = $this->input->post('setor');
             $genero = $this->input->post('genero');
             $data_admissao = $this->input->post('data_admissao');
             $data_nascimento = $this->input->post('data_nascimento');
             $ramal = $this->input->post('ramal');
             $celular1 = $this->input->post('celular1');
             $celular_corportativo = $this->input->post('celular_corportativo');
             $networking = $this->input->post('networking');
             $project = $this->input->post('project');
             $admin = $this->input->post('admin');
             $administrador = $this->input->post('administrador');
             $criar_projetos = $this->input->post('criar_projetos');
             $publicacoes_institucionais = $this->input->post('publicacoes_institucionais');
             
              // GRUPO
             // ADMINISTRADOR = 1
             // CONSULTOR = 2
             // FUNCINIONARIO = 5
             if($administrador == 1){
                 $grupo = 1;
             }else{
                 if($consultor == 0){
                  $grupo = 5;   
                 }else{
                  $grupo = 2;
                 }
             }
             
            $data_user = array(
                'first_name' => $nome,
                'email' => $email,
                'consultor' => $consultor,
                'cargo' => $cargo,
                'gender' => $genero,
                'data_admissao' => $data_admissao,
                'data_aniversario' => $data_nascimento,
                'ramal' => $ramal,
                'corporativo' => $celular_corportativo,
                'phone' => $celular1,
                'networking' => $networking,
                'projetct' => $project,
                'admin' => $admin,
                'administrador' => $administrador,
                'publicacoes_institucionais' => $publicacoes_institucionais,
                'criar_projetos' => $criar_projetos
            );
            
            //print_r($data_user); exit;
           
           // $tabela_sig = 'fases_projeto';
           // $this->owner_model->updateCadastro($id_fase, $tabela_sig, $data_evento);
           
            $this->auth_model->update($id_usuario, $data_user, $setor);
            
            $date_hoje = date('Y-m-d H:i:s');    
            $usuario = $this->session->userdata('user_id');
            $empresa = $this->session->userdata('empresa');
            $ip = $_SERVER["REMOTE_ADDR"];

            $logdata = array('date' => date('Y-m-d H:i:s'), 
                'type' => 'UPDATE', 
                'description' => 'Alterou o Usuário Id: '.$id_usuario,  
                'userid' => $this->session->userdata('user_id'), 
                'ip_address' => $_SERVER["REMOTE_ADDR"],
                'tabela' => 'sig_users',
                'row' => $id_usuario,
                'depois' => json_encode($data_user), 
                'modulo' => 'admin',
                'funcao' => 'admin/editar_usuario',  
                'empresa' => $this->session->userdata('empresa'));
           
               $this->owner_model->addLog($logdata);  
           // exit;
            $tabela = 55;
            $id_menu = 27;
            $this->session->set_flashdata('message', lang("Cadastro atualizado com Sucesso!!!"));
            redirect("admin/usuarios/$tabela/$id_menu");
            
         }else{
             
          
            $this->data['submenu'] = "modulo";
            
               

                // registra o log de movimentação

                $date_hoje = date('Y-m-d H:i:s');    
                $usuario = $this->session->userdata('user_id');
                $empresa = $this->session->userdata('empresa');
                $ip = $_SERVER["REMOTE_ADDR"];

                $logdata = array('date' => date('Y-m-d H:i:s'), 
                    'type' => 'ACESSO', 
                    'description' => "Acessou o menu: Editar Fase do projeto",  
                    'userid' => $this->session->userdata('user_id'), 
                    'ip_address' => $_SERVER["REMOTE_ADDR"],
                    'tabela' => '',
                    'row' => '',
                    'depois' => '', 
                    'modulo' => 'owner',
                    'funcao' => 'owner/cadastro',  
                    'empresa' => $this->session->userdata('empresa'));
                    $this->owner_model->addLog($logdata); 
            
                //$this->data['modulos'] = $this->owner_model->getTablesCadastroBasico($tabela);
                $dados = $this->site->getUser($usuario_selecionado);
                $this->data['dados'] = $dados;
                $this->data['usuario_id'] = $usuario_selecionado;    
            
            $this->page_construct_admin('admin/cadastros/usuarios/edit_usuario', $meta, $this->data);
            
         }
         
         
    }
    
    public function reenviaEmailCredenciais($id_usuario)
    {
      //  $this->sma->checkPermissions();
           
        
        $date_hoje = date('Y-m-d H:i:s');
        $date_2 = date('Y-m-d');
        
        $password_provisorio = $this->gerar_senha(8, true, true, true, false);
        
        $this->ion_auth->reenviarCredencialNovoUsuario($id_usuario, $password_provisorio);
        $tabela = 55;
        $id_menu = 27;
        $this->session->set_flashdata('message', lang("E-mail Enviado com Sucesso!."));
        redirect("admin/usuarios/$tabela/$id_menu");
                   
    }
    
    
     public function alterarSenhaUsuario($id_usuario)
    {
        
        $this->form_validation->set_rules('id_usuario', lang("id_cadastro"), 'required');
        $this->form_validation->set_rules('nova_Senha', lang("Nova Senha"), 'required');
        $this->form_validation->set_rules('confirmar_senha', lang("Confirmar Senha"), 'required');
         
        if ($this->form_validation->run() == true) {
          
             $nova_senha = $this->input->post('nova_Senha');
             $confirmar_senha = $this->input->post('confirmar_senha');
             $usuario_id = $this->input->post('id_usuario');
            
            
             
             if($nova_senha == $confirmar_senha){
                 
                $dados = $this->site->getUser($usuario_id);
                $email = $dados->email;
             
            
             $this->ion_auth->reset_password($email, $nova_senha);
             }
             
            
            
             
             
           
              
            $date_hoje = date('Y-m-d H:i:s');    
            $usuario = $this->session->userdata('user_id');
            $empresa = $this->session->userdata('empresa');
            $ip = $_SERVER["REMOTE_ADDR"];

            $logdata = array('date' => date('Y-m-d H:i:s'), 
                'type' => 'INSERT', 
                'description' => 'Cadastro de uma nova Fase',  
                'userid' => $this->session->userdata('user_id'), 
                'ip_address' => $_SERVER["REMOTE_ADDR"],
                'tabela' => 'sig_fases_projeto',
                'row' => $id_cadastro,
                'depois' => json_encode($data_evento), 
                'modulo' => 'project',
                'funcao' => 'project/novoCadastroFase',  
                'empresa' => $this->session->userdata('empresa'));
           
               $this->owner_model->addLog($logdata);  
           // exit;
            $tabela_id = 55;
            $menu_id = 27;
            $this->session->set_flashdata('message', lang("Cadastro realizado com Sucesso!!!"));
            redirect("admin/usuarios/$tabela_id/$menu_id");
            
         }else{
        
        $date_cadastro = date('Y-m-d H:i:s');                           
        
       
        $dados = $this->site->getUser($id_usuario);
        $this->data['dados'] = $dados;
       
        //$this->load->view($this->theme . 'projetos/documentacao/add', $this->data);
        $this->load->view($this->theme . 'admin/cadastros/usuarios/alterar_senha', $this->data);
           
         }
            
    }
    
    
    
}
