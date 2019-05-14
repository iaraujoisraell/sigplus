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
     
          if ( ! $this->loggedIn) {
            redirect('login');
        }
        
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
            
            //$this->data['campos'] = $this->owner_model->getAllCamposTablesLista($tabela);
            //$this->data['cadastrosHabilitados'] = $this->owner_model->getAllCamposTablesCadastro($tabela);
            
           // $this->data['botoes_menu'] = $this->owner_model->getAllBotoesByTabela($tabela);
            $this->page_construct_admin('admin/cadastros/usuarios/index', $meta, $this->data);
           // $this->page_construct_user('owner/empresas/index', $meta, $this->data);
         }
         
         
    }
    
   
    public function editar_usuario($usuario_selecionado)
    {
     
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        
        $this->form_validation->set_rules('id_Editar_fase', lang("id_cadastro"), 'required');
         
         if ($this->form_validation->run() == true) {
           
             
             $evento = $this->input->post('evento');
             $periodo_evento = $this->input->post('periodo_evento');
             $responsavel = $this->input->post('responsavel');
             $id_fase = $this->input->post('fase');
             
             $funcao = $this->input->post('funcao');
             $menu_id = $this->input->post('menu_id');
             
             $evento_periodo_de = substr($periodo_evento, 0, 10);
             $partes_data_inicio = explode("/", $evento_periodo_de);
             $ano = $partes_data_inicio[2];
             $mes = $partes_data_inicio[1];
             $dia = $partes_data_inicio[0];
             $data_tratado_de = $ano.'-'.$mes.'-'.$dia;
             
             $evento_periodo_ate = substr($periodo_evento, 13, 24);
             $partes_data_fim = explode("/", $evento_periodo_ate);
             $anof = $partes_data_fim[2];
             $mesf = $partes_data_fim[1];
             $diaf = $partes_data_fim[0];
             $data_tratado_ate = $anof.'-'.$mesf.'-'.$diaf;
             
             $tabela_id = 21;
             $tabela_nome = $this->input->post('tabela_nome');
            // echo $tabela_nome.'<br>';
             
            $data_evento = array(
                'data_inicio' => $data_tratado_de,
                'data_fim' => $data_tratado_ate,
                'nome_fase' => $evento,
                'responsavel_aprovacao' => $responsavel
            );
           
            $tabela_sig = 'fases_projeto';
            $this->owner_model->updateCadastro($id_fase, $tabela_sig, $data_evento);
              
            $date_hoje = date('Y-m-d H:i:s');    
            $usuario = $this->session->userdata('user_id');
            $empresa = $this->session->userdata('empresa');
            $ip = $_SERVER["REMOTE_ADDR"];

            $logdata = array('date' => date('Y-m-d H:i:s'), 
                'type' => 'UPDATE', 
                'description' => 'Alterou a fase de projeto Id: '.$id_fase,  
                'userid' => $this->session->userdata('user_id'), 
                'ip_address' => $_SERVER["REMOTE_ADDR"],
                'tabela' => $tabela_sig,
                'row' => $id_fase,
                'depois' => json_encode($data_evento), 
                'modulo' => 'project',
                'funcao' => 'project/editar_fases_projetos',  
                'empresa' => $this->session->userdata('empresa'));
           
               $this->owner_model->addLog($logdata);  
           // exit;
            
            $this->session->set_flashdata('message', lang("Cadastro atualizado com Sucesso!!!"));
            redirect("project/$funcao/$tabela_id/$menu_id");
            
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
            
            $this->page_construct_admin('admin/cadastros/usuarios/edit_usuario', $meta, $this->data);
            
         }
         
         
    }
    
}
