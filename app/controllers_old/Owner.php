<?php  defined('BASEPATH') OR exit('No direct script access allowed');

class Owner extends MY_Controller
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
       $this->load->model('networking_model');
        $this->digital_upload_path = 'assets/uploads/historico_acoes/';
        $this->upload_path = 'assets/uploads/historico_acoes/';
        $this->thumbs_path = 'assets/uploads/thumbs/';
        $this->image_types = 'gif|jpg|jpeg|png|tif';
        $this->digital_file_types = 'zip|psd|ai|rar|pdf|doc|docx|xls|xlsx|ppt|pptx|gif|jpg|jpeg|png|tif|txt|xlt|xltx';
    }

    public function index()
    {
        $this->sma->checkPermissions();
        //1 - verifica se o usuário está acessando a sua empresa.
        //2 - verifica se o mesmo tem permissao para acessar a funcao solicitada
        // 3 - verifica se o menu pertence ao módulo atual, senao redireciona ele para a home do atual
        
        if ($this->Settings->version == '2.3') {
            $this->session->set_flashdata('warning', 'Please complete your update by synchronizing your database.');
            redirect('sync');
        }

        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        
        $usuario = $this->session->userdata('user_id');                     
       // $this->data['planos'] = $this->atas_model->getAllPlanosUser($usuario);
       
       
        // SALVA O MÓDULO ATUAL do usuário
         $data_modulo = array('modulo_atual' => 1, 'menu_atual' => 1);
         $this->owner_model->updateModuloAtual($usuario, $data_modulo);
         
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
         $tipo = "OWNER";
         $texto = "O usuário $nome, da empresa $nome_empresa acessou o módulo $nome_modulo";
         $tabela = "";
         $row = "";
         $depois = "";
         $modulo = "owner";
         $funcao = "owner/index";
         $this->registraLog($tipo, $texto, $tabela, $row, $depois, $modulo, $funcao);
        
         
        $modulo_dados = $this->owner_model->getModuloById($modulo_atual);
        $controle = $modulo_dados->controle;
        $pagina_home = $modulo_dados->home;
       
        $this->page_construct_owner($pagina_home, $meta, $this->data);
      
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
    
    
    /*
     * EMPRESA
     */
    public function empresas($id = null)
    {
     $this->sma->checkPermissions();
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        
        $this->form_validation->set_rules('tipo', lang("Tipo"), 'required');
        $this->form_validation->set_rules('company', lang("Empresa"), 'required');
        $this->form_validation->set_rules('name', lang("Nome do Contato"), 'required');
        
         if ($this->form_validation->run() == true) {
           
             
             
            $date_hoje = date('Y-m-d H:i:s');    
            $usuario = $this->session->userdata('user_id');
            $empresa = $this->session->userdata('empresa');
            $ip = $_SERVER["REMOTE_ADDR"];
            
            $tipo = $this->input->post('tipo');
            $company = $this->input->post('company');
            $fantazia = $this->input->post('fantazia');
            $name = trim($this->input->post('name')); 
            $cnpj = trim($this->input->post('cnpj')); 
            $ie = trim($this->input->post('ie')); 
            $im = trim($this->input->post('im')); 
            
            $email = $this->input->post('email');
            $phones = $this->input->post('phone');
            $address = trim($this->input->post('address')); 
            $city = trim($this->input->post('city')); 
            $state = trim($this->input->post('state')); 
            $postal_code = trim($this->input->post('postal_code'));
            $status = trim($this->input->post('status'));
            
            $data_empresa = array(
                'tipo' => $tipo,
                'razaoSocial' => $company,
                'fantazia' => $fantazia,
                'responsavel' => $name,
                'cnpj' => $cnpj,
                'inscricaoEstadual' => $ie,
                'inscricaoMunicipal' => $im,
                'emailResponsavel' => $email,
                'endereco' => $address,
                'cidade' => $city,
                'uf' => $state,
                'cep' => $postal_code,
                'telefone' => $phones,
                'data_cadastro' => $date_hoje,
                'status' => $status
            );
           
            $id_empresa =  $this->owner_model->addEmpresa( $data_empresa);
            
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
             $tipo = "OWNER - INSERT";
             $texto = "O usuário $nome, da empresa $nome_empresa Cadastrou uma nova Empresa, ID : $id_empresa";
             $tabela = "sig_empresa";
             $row = $id_empresa;
             $depois = json_encode($data_empresa);
             $modulo = "owner";
             $funcao = "owner/empresas";
             $this->registraLog($tipo, $texto, $tabela, $row, $depois, $modulo, $funcao);
        
            
            $this->session->set_flashdata('message', lang("Empresa Cadastradacom Sucesso!!!"));
            redirect("owner/empresas");
            
         }else{
             
        // SALVA O MENU ATUAL do usuário
         $usuario = $this->session->userdata('user_id');    
         $data_modulo = array('menu_atual' => 2);
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
         $tipo = "OWNER";
         $texto = "O usuário $nome, da empresa $nome_empresa acessou o Cadastro de Empresas";
         $tabela = "sig_empresa";
         $row = "";
         $depois = "";
         $modulo = "owner";
         $funcao = "owner/empresas";
         $this->registraLog($tipo, $texto, $tabela, $row, $depois, $modulo, $funcao);
         
             
            $this->data['titulo'] = "Empresas";
            $this->data['descricao_titulo'] = "Lista de Cadastro";      
            $this->data['empresas'] = $this->owner_model->getAllEmpresas();
            $this->page_construct_owner('owner/empresas/index', $meta, $this->data);
           // $this->page_construct_user('owner/empresas/index', $meta, $this->data);
         }
    }
    
    public function editarEmpresaModulo($id_empresa)
    {
        $this->sma->checkPermissions();
        $date_cadastro = date('Y-m-d H:i:s');                           
        
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
       
         $this->form_validation->set_rules('id_cadastro', lang("id_cadastro"), 'required');
         $this->form_validation->set_rules('empresa_id', lang("empresa_id"), 'required');
         if ($this->form_validation->run() == true) {
           
             $empresa_id = $this->input->post('empresa_id');
            // echo $empresa_id.'<br>';
            $this->owner_model->deleteEmpresaModulo($empresa_id);
            
            $modulos = $this->owner_model->getAllModulos();
               
            foreach ($modulos as $empresa) {
                    $modulo_id = $empresa->id;
                    $empresa_modulo = $this->input->post('modulo'.$modulo_id);
                    
                    echo $empresa_modulo.'<br>';
                    
                    
                    $data_modulo = array(
                        'sig_empresa_id' => $empresa_id,
                        'sig_modulos_id' => $empresa_modulo
                    );
                     
                    $id_cadastro =  $this->owner_model->addEmpresaModulo($data_modulo);
              
                   
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
                     $tipo = "OWNER - INSERT";
                     $texto = "O usuário $nome, da empresa $nome_empresa atualizou os módulos de acesso da Empresa ID: $empresa_id";
                     $tabela = "sig_empresa";
                     $row = $id_cadastro;
                     $depois = json_encode($data_modulo);
                     $modulo = "owner";
                     $funcao = "owner/editarEmpresaModulo";
                     $this->registraLog($tipo, $texto, $tabela, $row, $depois, $modulo, $funcao);
                


              }
              
            //exit;
            
            $this->session->set_flashdata('message', lang("Alteração realizada com Sucesso!!!"));
            redirect("owner/empresas");
            
         }else{
       
        $this->data['id'] = $id_empresa;   
        //    $id_descriptografado =  $this->encrypt($id_registro,'UNIMED');
        $usuario = $this->session->userdata('user_id');                     
        
        //$this->load->view($this->theme . 'projetos/documentacao/add', $this->data);
        $this->load->view($this->theme . 'owner/empresas/editarModulo', $this->data);
           
         }
            
    }
    
    
    /************************************************************************************************************
     *************************** CADASTROS BÁSICOS MODELO PADRÃO 1 **********************************************
     ************************************************************************************************************/
  
    
    public function cadastro($tabela, $menu)
    {
     $this->sma->checkPermissions();
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        
        $this->form_validation->set_rules('id_cadastro', lang("id_cadastro"), 'required');
        // echo 'aqui'.$id_cadastro;exit;
         if ($this->form_validation->run() == true) {
           
             $tabela_id = $this->input->post('tabela_id');
             $tabela_nome = $this->input->post('tabela_nome');
             $menu_id = $this->input->post('menu_id');
             $id_cadastro = $this->input->post('id_cadastro');
            // echo 'aqui'.$menu_id; exit;
             
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
           
            //   $menu_dados = $this->owner_model->getMenuById($menu);
            // $restrito = $menu_dados->restrito;
             
              $id_cadastro =  $this->owner_model->addCadastro($tabela_sig, $data_campos_cadastro);
              
                $usuario = $this->session->userdata('user_id');
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
                 $tipo = "OWNER INSERT";
                 $texto = "O usuário $nome, da empresa $nome_empresa realizou um novo cadastro na tabela ID: $tabela_id";
                 $tabela_log = "$tabela_id";
                 $row = "$id_cadastro";
                 $depois = "json_encode($data_campos_cadastro)";
                 $modulo = "owner";
                 $funcao = "owner/cadastroBasicoModelo";
                 $this->registraLog($tipo, $texto, $tabela_log, $row, $depois, $modulo, $funcao);

           
            
            $this->session->set_flashdata('message', lang("Cadastro realizado com Sucesso!!!"));
            redirect("owner/cadastro/$tabela_id/$menu_id");
            
         }else{
             
            $tabela_cadastro = $this->owner_model->getTableById($tabela);
            $tabela_nome = $tabela_cadastro->tabela;
            //echo 'aqui'.$tabela_nome; exit;
            $menu_dados = $this->owner_model->getMenuById($menu);
             $restrito = $menu_dados->restrito;
             
            
            $this->data['tabela_nome'] = $tabela_nome;
            $this->data['tabela_id'] = $tabela;
            
            $this->data['titulo'] = $tabela_cadastro->titulo;
            $this->data['descricao_titulo'] = $tabela_cadastro->descricao;
            $this->data['menu'] = "cadastro";
            $this->data['submenu'] = "modulo";
            
                // SALVA O MÓDULO ATUAL do usuário
                 $usuario = $this->session->userdata('user_id');    
                 $data_modulo = array('menu_atual' => $menu);
                 $this->owner_model->updateModuloAtual($usuario, $data_modulo);

                // registra o log de movimentação
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
                 $tipo = "OWNER";
                 $texto = "O usuário $nome, da empresa $nome_empresa acessou o Cadastro, Menu ID: $menu_atual";
                 $tabela_log = "$tabela";
                 $row = "";
                 $depois = "";
                 $modulo = "owner";
                 $funcao = "owner/cadastro";
                 $this->registraLog($tipo, $texto, $tabela_log, $row, $depois, $modulo, $funcao);
              
      
            $this->data['menu_id'] = $menu;
            //$this->data['modulos'] = $this->owner_model->getTablesCadastroBasico($tabela);
            $this->data['cadastros'] = $this->owner_model->getTablesCadastroBasico($tabela_nome, $restrito);
            $this->data['campos'] = $this->owner_model->getAllCamposTablesLista($tabela);
            $this->data['cadastrosHabilitados'] = $this->owner_model->getAllCamposTablesCadastro($tabela);
            $this->data['botoes_menu'] = $this->owner_model->getAllBotoesByTabela($tabela);
            $this->page_construct_owner('owner/cadastro_basico_modelo/index', $meta, $this->data);
           // $this->page_construct_owner_sortable('owner/cadastro_basico_modelo/sortable', $meta, $this->data);
           // $this->page_construct_user('owner/empresas/index', $meta, $this->data);
         }
         
         
    }
    
    public function cadastro_sortable($tabela, $menu)
    {
     $this->sma->checkPermissions();
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        
        $this->form_validation->set_rules('id_cadastro', lang("id_cadastro"), 'required');
        // echo 'aqui'.$id_cadastro;exit;
         if ($this->form_validation->run() == true) {
           
             $tabela_id = $this->input->post('tabela_id');
             $tabela_nome = $this->input->post('tabela_nome');
             $menu_id = $this->input->post('menu_id');
             $id_cadastro = $this->input->post('id_cadastro');
            // echo 'aqui'.$menu_id; exit;
             
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
           
            //   $menu_dados = $this->owner_model->getMenuById($menu);
            // $restrito = $menu_dados->restrito;
             
              $id_cadastro =  $this->owner_model->addCadastro($tabela_sig, $data_campos_cadastro);
              
                $usuario = $this->session->userdata('user_id');
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
                 $tipo = "OWNER INSERT";
                 $texto = "O usuário $nome, da empresa $nome_empresa realizou um novo cadastro na tabela ID: $tabela_id";
                 $tabela_log = "$tabela_id";
                 $row = "$id_cadastro";
                 $depois = "json_encode($data_campos_cadastro)";
                 $modulo = "owner";
                 $funcao = "owner/cadastroBasicoModelo";
                 $this->registraLog($tipo, $texto, $tabela_log, $row, $depois, $modulo, $funcao);

           
            
            $this->session->set_flashdata('message', lang("Cadastro realizado com Sucesso!!!"));
            redirect("owner/cadastro/$tabela_id/$menu_id");
            
         }else{
             
            $tabela_cadastro = $this->owner_model->getTableById($tabela);
            $tabela_nome = $tabela_cadastro->tabela;
            //echo 'aqui'.$menu; exit;
            $menu_dados = $this->owner_model->getMenuById($menu);
             $restrito = $menu_dados->restrito;
             
            
            $this->data['tabela_nome'] = $tabela_nome;
            $this->data['tabela_id'] = $tabela;
            
            $this->data['titulo'] = $tabela_cadastro->titulo;
            $this->data['descricao_titulo'] = $tabela_cadastro->descricao;
            $this->data['menu'] = "cadastro";
            $this->data['submenu'] = "modulo";
            
                // SALVA O MÓDULO ATUAL do usuário
                 $usuario = $this->session->userdata('user_id');    
                 $data_modulo = array('menu_atual' => $menu);
                 $this->owner_model->updateModuloAtual($usuario, $data_modulo);

                // registra o log de movimentação
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
                 $tipo = "OWNER";
                 $texto = "O usuário $nome, da empresa $nome_empresa acessou o Cadastro, Menu ID: $menu_atual";
                 $tabela_log = "$tabela";
                 $row = "";
                 $depois = "";
                 $modulo = "owner";
                 $funcao = "owner/cadastro";
                 $this->registraLog($tipo, $texto, $tabela_log, $row, $depois, $modulo, $funcao);
              
      
            $this->data['menu_id'] = $menu;
            $this->data['tabela_nome'] = $tabela_nome;
            //$this->data['modulos'] = $this->owner_model->getTablesCadastroBasico($tabela);
            $this->data['cadastros'] = $this->owner_model->getTablesCadastroBasico($tabela_nome, $restrito, 1);
            $this->data['campos'] = $this->owner_model->getAllCamposTablesLista($tabela);
            $this->data['cadastrosHabilitados'] = $this->owner_model->getAllCamposTablesCadastro($tabela);
            $this->data['botoes_menu'] = $this->owner_model->getAllBotoesByTabela($tabela);
           // $this->page_construct_owner('owner/cadastro_basico_modelo/sortable', $meta, $this->data);
            $this->page_construct_owner_sortable('owner/cadastro_basico_modelo/sortable', $meta, $this->data);
           // $this->page_construct_user('owner/empresas/index', $meta, $this->data);
         }
         
         
    }
    
    public function novoCadastroBasico($tabela, $menu)
    {
        $this->sma->checkPermissions();
        $date_cadastro = date('Y-m-d H:i:s');                           
        
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        $tabela_cadastro = $this->owner_model->getTableById($tabela);
        $tabela_nome = $tabela_cadastro->tabela;
       $this->data['tabela_nome'] = $tabela_nome;
        $this->data['titulo'] = $tabela_cadastro->titulo;
        $this->data['tabela_id'] = $tabela;   
        $this->data['campos'] = $this->owner_model->getAllCamposTablesLista($tabela);
        $this->data['cadastrosHabilitados'] = $this->owner_model->getAllCamposTablesCadastro($tabela);
        $usuario = $this->session->userdata('user_id');                     
        $this->data['menu_id'] = $menu;
        $this->data['cadastro_id'] = $cadastro_id;
        
        //$this->load->view($this->theme . 'projetos/documentacao/add', $this->data);
        $this->load->view($this->theme . 'owner/cadastro_basico_modelo/cadastroBasico', $this->data);
           

            
    }
    
    public function editarCadastro($tabela_id,$cadastro_id, $menu)
    {
     $this->sma->checkPermissions();
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        
        $this->form_validation->set_rules('id_cadastro', lang("id_cadastro"), 'required');
         
         if ($this->form_validation->run() == true) {
           
             $tabela_id = $this->input->post('tabela_id');
             $tabela_nome = $this->input->post('tabela_nome');
             $menu_id = $this->input->post('menu_id');
             $id_cadastro = $this->input->post('id_cadastro');
             
            
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
             // print_r($data_campos_cadastro);
              $tabela_sig = substr($tabela_nome, 4);
            // echo $tabela_sig; exit;
              $this->owner_model->updateCadastro($id_cadastro, $tabela_sig, $data_campos_cadastro);
              
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
                 $tipo = "OWNER - UPDATE";
                 $texto = "O usuário $nome, da empresa $nome_empresa atualizou o Cadastro Id : $id_cadastro, da tabela Id: $tabela_id";
                 $tabela_log = "$tabela_id";
                 $row = "$id_cadastro";
                 $depois = json_encode($data_campos_cadastro);
                 $modulo = "owner";
                 $funcao = "owner/editarCadastro";
                 $this->registraLog($tipo, $texto, $tabela_log, $row, $depois, $modulo, $funcao);
            
            $this->session->set_flashdata('message', lang("Alteração realizada com Sucesso!!!"));
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
            
            $this->data['dados_tabela'] = $this->owner_model->getDadosTablesCadastroById($tabela_nome, $cadastro_id);
            //$this->data['cadastros'] = $this->owner_model->getTablesCadastroBasico($tabela_nome);
            //$this->data['campos'] = $this->owner_model->getAllCamposTablesLista($tabela_id);
            $this->data['cadastrosHabilitados'] = $this->owner_model->getAllCamposTablesCadastro($tabela_id);
           // $this->page_construct_owner('owner/cadastro_basico_modelo/editarCadastro','header_empresa', $meta, $this->data);
            $this->load->view($this->theme . 'owner/cadastro_basico_modelo/editarCadastro', $this->data);
         }
         
         
    }
    
    public function deletarCadastro($tabela_id,$cadastro_id, $menu)
    {
     $this->sma->checkPermissions();
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        
        $this->form_validation->set_rules('id_cadastro', lang("id_cadastro"), 'required');
         
         if ($this->form_validation->run() == true) {
           
             $tabela_id = $this->input->post('tabela_id');
             $tabela = $this->input->post('tabela');
             $id_cadastro = $this->input->post('id_cadastro');
             $menu_id = $this->input->post('menu_id');
             
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
              $tabela_sig = substr($tabela, 4);
              
              $this->owner_model->deleteCadastro($id_cadastro, $tabela_sig);
              
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
                 $tipo = "OWNER - DELETE";
                 $texto = "O usuário $nome, da empresa $nome_empresa deletou o Cadastro Id : $id_cadastro, da tabela Id: $tabela_id";
                 $tabela_log = "$tabela_id";
                 $row = "$id_cadastro";
                 $depois = json_encode($data_campos_cadastro);
                 $modulo = "owner";
                 $funcao = "owner/deletarCadastro";
                 $this->registraLog($tipo, $texto, $tabela_log, $row, $depois, $modulo, $funcao);

            
           // exit;
            
            $this->session->set_flashdata('message', lang("Cadastro deletado com Sucesso!!!"));
            redirect("owner/cadastro/$tabela_id/$menu_id");
            
         }else{
             
            $tabela_cadastro = $this->owner_model->getTableById($tabela_id);
            $tabela_nome = $tabela_cadastro->tabela;
            
            $this->data['tabela'] = $tabela_nome;
            $this->data['tabela_id'] = $tabela_id;
            $this->data['titulo'] = $tabela_cadastro->titulo;
            $this->data['descricao_titulo'] = $tabela_cadastro->descricao;
            $this->data['menu_id'] = $menu;
            $this->data['menu'] = "cadastro";
            $this->data['submenu'] = "modulo";
            $this->data['cadastro_id'] = $cadastro_id;
            
            $this->data['dados_tabela'] = $this->owner_model->getDadosTablesCadastroById($tabela_nome, $cadastro_id);
            //$this->data['cadastros'] = $this->owner_model->getTablesCadastroBasico($tabela_nome);
            //$this->data['campos'] = $this->owner_model->getAllCamposTablesLista($tabela_id);
            $this->data['cadastrosHabilitados'] = $this->owner_model->getAllCamposTablesCadastro($tabela_id);
          //  $this->page_construct_owner('owner/cadastro_basico_modelo/deletarCadastro','header_empresa', $meta, $this->data);
           $this->load->view($this->theme . 'owner/cadastro_basico_modelo/deletarCadastro', $this->data);
         }
         
         
    }
    
    
    /************************************************************************************************************
     *************************** CADASTROS BÁSICOS MÓDULO PROJECT PADRÃO 1 **********************************************
     ************************************************************************************************************/
  
    
    public function cadastro_project($tabela, $menu)
    {
     $this->sma->checkPermissions();
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        
        $this->form_validation->set_rules('id_cadastro', lang("id_cadastro"), 'required');
        // echo 'aqui'.$id_cadastro;exit;
         if ($this->form_validation->run() == true) {
           
             $tabela_id = $this->input->post('tabela_id');
             $tabela_nome = $this->input->post('tabela_nome');
             $menu_id = $this->input->post('menu_id');
             $id_cadastro = $this->input->post('id_cadastro');
            // echo 'aqui'.$menu_id; exit;
             
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
           
            //   $menu_dados = $this->owner_model->getMenuById($menu);
            // $restrito = $menu_dados->restrito;
             
              $id_cadastro =  $this->owner_model->addCadastro($tabela_sig, $data_campos_cadastro);
              
                $usuario = $this->session->userdata('user_id');
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
                 $tipo = "OWNER INSERT";
                 $texto = "O usuário $nome, da empresa $nome_empresa realizou um novo cadastro na tabela ID: $tabela_id";
                 $tabela_log = "$tabela_id";
                 $row = "$id_cadastro";
                 $depois = "json_encode($data_campos_cadastro)";
                 $modulo = "owner";
                 $funcao = "owner/cadastroBasicoModelo";
                 $this->registraLog($tipo, $texto, $tabela_log, $row, $depois, $modulo, $funcao);

           
            
            $this->session->set_flashdata('message', lang("Cadastro realizado com Sucesso!!!"));
            redirect("owner/cadastro/$tabela_id/$menu_id");
            
         }else{
             
            $tabela_cadastro = $this->owner_model->getTableById($tabela);
            $tabela_nome = $tabela_cadastro->tabela;
            //echo 'aqui'.$menu; exit;
            $menu_dados = $this->owner_model->getMenuById($menu);
             $restrito = $menu_dados->restrito;
             
            
            $this->data['tabela_nome'] = $tabela_nome;
            $this->data['tabela_id'] = $tabela;
            
            $this->data['titulo'] = $tabela_cadastro->titulo;
            $this->data['descricao_titulo'] = $tabela_cadastro->descricao;
            $this->data['menu'] = "cadastro";
            $this->data['submenu'] = "modulo";
            
                // SALVA O MÓDULO ATUAL do usuário
                 $usuario = $this->session->userdata('user_id');    
                 $data_modulo = array('menu_atual' => $menu);
                 $this->owner_model->updateModuloAtual($usuario, $data_modulo);

                // registra o log de movimentação
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
                 $tipo = "OWNER";
                 $texto = "O usuário $nome, da empresa $nome_empresa acessou o Cadastro, Menu ID: $menu_atual";
                 $tabela_log = "$tabela";
                 $row = "";
                 $depois = "";
                 $modulo = "owner";
                 $funcao = "owner/cadastro";
                 $this->registraLog($tipo, $texto, $tabela_log, $row, $depois, $modulo, $funcao);
              
      
            $this->data['menu_id'] = $menu;
            //$this->data['modulos'] = $this->owner_model->getTablesCadastroBasico($tabela);
            $this->data['cadastros'] = $this->owner_model->getTablesCadastroBasico($tabela_nome, $restrito);
            $this->data['campos'] = $this->owner_model->getAllCamposTablesLista($tabela);
            $this->data['cadastrosHabilitados'] = $this->owner_model->getAllCamposTablesCadastro($tabela);
            $this->data['botoes_menu'] = $this->owner_model->getAllBotoesByTabela($tabela);
            $this->page_construct_owner('owner/cadastro_basico_modelo/index', $meta, $this->data);
           // $this->page_construct_owner_sortable('owner/cadastro_basico_modelo/sortable', $meta, $this->data);
           // $this->page_construct_user('owner/empresas/index', $meta, $this->data);
         }
         
         
    }
    
    
    /****************************************************
     ****************** TABELAS **************************
     **************************************************/
     public function tabelas($id = null)
    {
     $this->sma->checkPermissions();
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        
        $this->form_validation->set_rules('descricao', lang("Descrição"), 'required');
         
         if ($this->form_validation->run() == true) {
           
             
             
            $date_hoje = date('Y-m-d H:i:s');    
            $usuario = $this->session->userdata('user_id');
            $empresa = $this->session->userdata('empresa');
            $ip = $_SERVER["REMOTE_ADDR"];
            
            $descricao = $this->input->post('descricao');
            $icon = $this->input->post('icon');
            
            
            $data_empresa = array(
                'descricao' => $descricao,
                'icon' => $icon
            );
           
            $id_modulo =  $this->owner_model->addModulo( $data_empresa);
            
            $logdata = array('date' => date('Y-m-d H:i:s'), 
                'type' => 'INSERT', 
                'description' => 'Cadastro de um novo Módulo',  
                'userid' => $this->session->userdata('user_id'), 
                'ip_address' => $_SERVER["REMOTE_ADDR"],
                'tabela' => 'sig_modulo',
                'row' => $id_modulo,
                'depois' => json_encode($data_empresa), 
                'modulo' => 'owner',
                'funcao' => 'owner/modulos',  
                'empresa' => $this->session->userdata('empresa'));
           
               $this->owner_model->addLog($logdata);  
           // exit;
            
            $this->session->set_flashdata('message', lang("Cadastro realizado com Sucesso!!!"));
            redirect("owner/modulos");
            
         }else{
             
             // SALVA O MÓDULO ATUAL do usuário
                 $usuario = $this->session->userdata('user_id');    
                 $data_modulo = array('menu_atual' => 20);
                 $this->owner_model->updateModuloAtual($usuario, $data_modulo);

                // registra o log de movimentação

                $date_hoje = date('Y-m-d H:i:s');    
                $usuario = $this->session->userdata('user_id');
                $empresa = $this->session->userdata('empresa');
                $ip = $_SERVER["REMOTE_ADDR"];

                $logdata = array('date' => date('Y-m-d H:i:s'), 
                    'type' => 'ACESSO', 
                    'description' => "Acessou o menu Tabelas BD do Módulo OWNER",  
                    'userid' => $this->session->userdata('user_id'), 
                    'ip_address' => $_SERVER["REMOTE_ADDR"],
                    'tabela' => '',
                    'row' => '',
                    'depois' => '', 
                    'modulo' => 'owner',
                    'funcao' => 'owner/tabelas',  
                    'empresa' => $this->session->userdata('empresa'));
                    $this->owner_model->addLog($logdata); 
             
            $this->data['titulo'] = "Tabelas";
            $this->data['descricao_titulo'] = "Lista de Tabelas";
            $this->data['menu'] = "cadastro";
            $this->data['submenu'] = "tabela";
            $this->data['tabelas'] = $this->owner_model->getAllTables();
            $this->page_construct_owner('owner/tabelas/index', $meta, $this->data);
           // $this->page_construct_user('owner/empresas/index', $meta, $this->data);
         }
         
         
    }
    /*
     * CAMPOS DA TABELA
     */
    public function editarCampos($tabela_id, $id, $menu)
    {
        $this->sma->checkPermissions();
        $date_cadastro = date('Y-m-d H:i:s');                           
        
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
       
        $id_tabela = $this->input->post('id_tabela');
        
        $this->form_validation->set_rules('id_tabela', lang("id_tabela"), 'required');
         
         if ($this->form_validation->run() == true) {
           
            $menu_id = $this->input->post('menu_id');
            $tabela_id = $this->input->post('tabela_id');
            
            $campos = $this->owner_model->getAllCamposTables($id_tabela);
            foreach ($campos as $campo) {
                $id_campo = $campo->id;
                $campo_sig = $this->input->post('campo_sig'.$campo->id);
                $tipo_campo = $this->input->post('tipo_campo'.$campo->id);
                $tipo_texto = $this->input->post('tipo_texto'.$campo->id);
                $tamanho = $this->input->post('tamanho'.$campo->id);
                $obrigatorio = $this->input->post('obrigatorio'.$campo->id);
                if(!$obrigatorio){
                    $obrigatorio = 0;
                }
                $cadastro = $this->input->post('cadastro'.$campo->id);
                if(!$cadastro){
                    $cadastro = 0;
                }
                $main = $this->input->post('main'.$campo->id);
                if(!$main){
                    $main = 0;
                }
                
                $width = $this->input->post('width'.$campo->id);
                if(!$width){
                    $width = '';
                }
                
                $fk = $this->input->post('fk'.$campo->id);
                $drop = $this->input->post('campo_drop'.$campo->id); //campo_dropdown
                
                $sessao = $this->input->post('sessao'.$campo->id); 
                //$obrigatorio = $this->input->post('obrigatorio'.$campo->id);
                
              //  echo $campo_sig.'-'.$tipo_campo.'-'.$tipo_texto.'-'.$tamanho.'-'.$obrigatorio.'-'.$cadastro.'-'.$main. '<br>';
                $data_campo = array(
                    'nome_campo' => $campo_sig,
                    'tipo_campo' => $tipo_campo,
                    'tipo_texto' => $tipo_texto,
                    'tamanho' => $tamanho,
                    'obrigatorio' => $obrigatorio,    
                    'cadastro' => $cadastro,
                    'lista' => $main,
                    'width' => $width,
                    'fk' => $fk,
                    'campo_dropdown' => $drop,
                    'sessao' => $sessao
                );
             $this->owner_model->updateCampo($id_campo,  $data_campo);
                
             
              
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
                 $tipo = "OWNER - UPDATE";
                 $texto = "O usuário $nome, da empresa $nome_empresa atualizou o Cadastro Id : $id_campo, da tabela Id: $id_tabela";
                 $tabela_log = "sig_tabelas_campo";
                 $row = "$id_campo";
                 $depois = json_encode($data_campo);
                 $modulo = "owner";
                 $funcao = "owner/editarCampos";
                 $this->registraLog($tipo, $texto, $tabela_log, $row, $depois, $modulo, $funcao);
             
            }
           
           
           // exit;
            
            $this->session->set_flashdata('message', lang("Dados enviado com Sucesso!!!"));
            //redirect("owner/tabelas");
            redirect("owner/cadastro/$tabela_id/$menu_id");
         }else{
       
        $this->data['tabela_id'] = $tabela_id;     
        $this->data['id'] = $id;
        $this->data['menu_id'] = $menu;
        //    $id_descriptografado =  $this->encrypt($id_registro,'UNIMED');
        $usuario = $this->session->userdata('user_id');                     
        $this->data['campos'] = $this->owner_model->getAllCamposTables($id);
        //$this->load->view($this->theme . 'projetos/documentacao/add', $this->data);
        $this->load->view($this->theme . 'owner/tabelas/editarCampos', $this->data);
           
         }
            
    }
    
    public function editarCamposFK($tabela_id, $id, $menu)
    {
        $this->sma->checkPermissions();
        $date_cadastro = date('Y-m-d H:i:s');                           
        
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
       
        $id_tabela = $this->input->post('id_tabela');
        
        $this->form_validation->set_rules('id_tabela', lang("id_tabela"), 'required');
         
         if ($this->form_validation->run() == true) {
            
            $this->owner_model->deleteCamposTabelaFK($id_tabela);
            
            $menu_id = $this->input->post('menu_id');
            $tabela_id = $this->input->post('tabela_id');
            
            $campos = $this->owner_model->getAllCamposTables_fk($id_tabela);
            foreach ($campos as $campo_fk) {
                $id_tabela_fk = $this->input->post('tabela_fk'.$campo_fk->id); 
            
            $campos_tab = $this->input->post('fk_campos'.$campo_fk->id); //campos selecionados
            foreach ($campos_tab as $campo) {
                
                $data_campo = array(
                    'campo_id' => $campo,
                    'tabela_id' => $id_tabela,
                    'tabela_fk' => $id_tabela_fk
                );
             //   print_r($data_campo); echo '<br>';
             $id_row =  $this->owner_model->addCamposTabelaFK($data_campo);
              
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
                 $tipo = "OWNER - UPDATE";
                 $texto = "O usuário $nome, da empresa $nome_empresa atualizou o Cadastro de Campos ID: $id_row,  FK relacionados ao cadastro de tabela Id :  $id_tabela";
                 $tabela_log = "sig_tabelas_campo";
                 $row = "$id_row";
                 $depois = json_encode($data_campo);
                 $modulo = "owner";
                 $funcao = "owner/editarCamposFK";
                 $this->registraLog($tipo, $texto, $tabela_log, $row, $depois, $modulo, $funcao);
             
            
             
            }
           
          } 
           // exit;
            
            $this->session->set_flashdata('message', lang("Dados enviado com Sucesso!!!"));
            redirect("owner/cadastro/$tabela_id/$menu_id");
            
         }else{
       
         $this->data['tabela_id'] = $tabela_id;     
        $this->data['id'] = $id;
        $this->data['menu_id'] = $menu;
        //    $id_descriptografado =  $this->encrypt($id_registro,'UNIMED');
        $usuario = $this->session->userdata('user_id');                     
        $this->data['campos'] = $this->owner_model->getAllCamposTables_fk($id);
        //$this->load->view($this->theme . 'projetos/documentacao/add', $this->data);
        $this->load->view($this->theme . 'owner/tabelas/editarFK', $this->data);
           
         }
            
    }
    
    /*
     * Verifica as tabelas que tem no BD e importa para o SIG
     */
    public function atualizaTabelas()
    {
        $this->sma->checkPermissions();
        $date_cadastro = date('Y-m-d H:i:s');                           
        
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
       
        
        $this->form_validation->set_rules('importar', lang("importar"), 'required');
         
         if ($this->form_validation->run() == true) {
           
             $tabelas_bd = $this->owner_model->getAllTables_BD();
             foreach ($tabelas_bd as $table_bd) {
                         
                         
                $verifica_tabela = $this->owner_model->getTableByTabela($table_bd->Tables_in_sig_plus);
                $tabela_no_sig = $verifica_tabela->tabela;
                $tabela_id_sig = $verifica_tabela->id;
                
                if(!$tabela_no_sig){

                     $logdata = array('tabela' => $table_bd->Tables_in_sig_plus);
                     $id_tabela =  $this->owner_model->addtable($logdata);  
                     

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
                 $tipo = "OWNER - INSERT";
                 $texto = "O usuário $nome, da empresa $nome_empresa Adicinou a tabela ID: $id_tabela, através de atualização";
                 $tabela_log = "sig_tabelas_campo";
                 $row = "$id_tabela";
                 $depois = json_encode($logdata);
                 $modulo = "owner";
                 $funcao = "owner/atualizaTabelas";
                 $this->registraLog($tipo, $texto, $tabela_log, $row, $depois, $modulo, $funcao);

                }else{
                      $id_tabela = $verifica_tabela->id;
                }       
                        /*
                         * FAZ O INSERT DOS CAMPOS DA TABELA
                         */
                           $verifica_all_campos = $this->owner_model->getAllColumns_BD($table_bd->Tables_in_sig_plus);
                           foreach ($verifica_all_campos as $campo) {
                               $campo_nome = $campo->Field;
                               $campo_tipo = $campo->Type;

                               //VERIFICA SE EXISTE NO SIG O CAMPO DO BD
                               $verifica_campos = $this->owner_model->getCampoByCampo($campo_nome, $tabela_id_sig);
                               $campo_no_sig = $verifica_campos->campo;

                               //SE RETORNAR VAZIO, SALVA
                                if(!$campo_no_sig){
                                  $Campo_data = array(
                                   'tabela_id' => $id_tabela,
                                   'campo' => $campo_nome,
                                   'tipo' => $campo_tipo);
                            //  echo $table_bd->Tables_in_sig_plus.'<br>';
                            //   print_r($Campo_data);
                             //  exit;

                                $id_campo =  $this->owner_model->addCampo($Campo_data);  

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
                                         $tipo = "OWNER - INSERT";
                                         $texto = "O usuário $nome, da empresa $nome_empresa Adicinou o campo, $campo_nome com o  ID: $id_campo, através de atualização";
                                         $tabela_log = "sig_tabelas_campo";
                                         $row = "$id_campo";
                                         $depois = json_encode($logdata);
                                         $modulo = "owner";
                                         $funcao = "owner/atualizaTabelas";
                                         $this->registraLog($tipo, $texto, $tabela_log, $row, $depois, $modulo, $funcao);
                                

                             }
                           }
                        
            }
                
                    
             
            
            $this->session->set_flashdata('message', lang("Dados enviado com Sucesso!!!"));
            redirect("owner/tabelas");
            
         }else{
       
        $this->data['id'] = $id;   
        //    $id_descriptografado =  $this->encrypt($id_registro,'UNIMED');
        $usuario = $this->session->userdata('user_id');                     
       // $this->data['tabelas'] = $this->owner_model->getAllTables();
        $this->data['tabelas_bd'] = $this->owner_model->getAllTables_BD();
        //$this->load->view($this->theme . 'projetos/documentacao/add', $this->data);
        $this->load->view($this->theme . 'owner/tabelas/atualizaTabela', $this->data);
           
         }
            
    }
    
    /*
     * CAMPOS DA TABELA
     */
    public function editarBotoes($tabela_id, $id, $menu)
    {
        $this->sma->checkPermissions();
        $date_cadastro = date('Y-m-d H:i:s');                           
        
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
       
        $id_tabela = $this->input->post('id_tabela');
        
        $this->form_validation->set_rules('id_tabela', lang("id_tabela"), 'required');
         
         if ($this->form_validation->run() == true) {
           
          $this->owner_model->deleteBotaoTabela($id_tabela);
            
           $menu_id = $this->input->post('menu_id');
           $tabela_id = $this->input->post('tabela_id');
            
            $botoes = $this->owner_model->getAllBotoes();
            foreach ($botoes as $botao) {
                $id_botao = $botao->id;
                $botao_sig = $this->input->post('botao'.$id_botao);
                $controle = $this->input->post('controle'.$id_botao);
                $funcao = $this->input->post('funcao'.$id_botao);
                
                if($botao_sig){
                
                $data_botao = array(
                    'tabela_id' => $id_tabela,
                    'botao_id' => $botao_sig,
                    'controle' => $controle,
                    'funcao' => $funcao
                );
             //   print_r($data_botao);
             //   echo '<br>';
                
           $id =  $this->owner_model->addBotaoTabela($id_tabela,  $data_botao);
                
             
              
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
             $tipo = "OWNER - UPDATE";
             $texto = "O usuário $nome, da empresa $nome_empresa Adicinou botões (link listagem) da tabela Id: $id_tabela";
             $tabela_log = "sig_tabelas_lista_botoes";
             $row = "$id";
             $depois = json_encode($data_botao);
             $modulo = "owner";
             $funcao = "owner/editarBotoes";
             $this->registraLog($tipo, $texto, $tabela_log, $row, $depois, $modulo, $funcao);
             
           
                }
            }
             
            
            $this->session->set_flashdata('message', lang("Dados enviado com Sucesso!!!"));
           redirect("owner/cadastro/$tabela_id/$menu_id");
            
         }else{
       
         $this->data['tabela_id'] = $tabela_id;     
        $this->data['id'] = $id;
        $this->data['menu_id'] = $menu; 
        //    $id_descriptografado =  $this->encrypt($id_registro,'UNIMED');
        $usuario = $this->session->userdata('user_id');                     
        $this->data['botoes'] = $this->owner_model->getAllBotoes();
        //$this->load->view($this->theme . 'projetos/documentacao/add', $this->data);
        $this->load->view($this->theme . 'owner/tabelas/editarBotoes', $this->data);
           
         }
            
    }
    
    
}
