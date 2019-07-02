<?php  defined('BASEPATH') OR exit('No direct script access allowed');

class Project extends MY_Controller
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
        $this->load->model('projetos_model');
        $this->load->model('networking_model');
        $this->load->model('projetos_model');
        $this->load->model('atas_model');
        $this->load->model('site');
        
        $this->digital_upload_path = 'assets/uploads/historico_acoes/';
        $this->upload_path = 'assets/uploads/historico_acoes/';
        $this->thumbs_path = 'assets/uploads/thumbs/';
        $this->image_types = 'gif|jpg|jpeg|png|tif';
        $this->digital_file_types = 'zip|psd|ai|rar|pdf|doc|docx|xls|xlsx|ppt|pptx|gif|jpg|jpeg|png|tif|txt|xlt|xltx';
    }
    
     public function verificarProjeto($menu)
    {
        $usuario = $this->session->userdata('user_id'); 
        $empresa = $this->session->userdata('empresa');
        $data_modulo = array('modulo_atual' => 4, 'menu_atual' => $menu);
        $this->owner_model->updateModuloAtual($usuario, $data_modulo); 
        $this->sma->checkPermissions();
      //echo 'aqui'.$id; exit;
        
        $users_dados = $this->site->geUserByID($usuario);
        $modulo_atual = $users_dados->modulo_atual;
        $menu_atual = $users_dados->menu_atual;
        $projeto_atual = $users_dados->projeto_atual;
        
        $this->data['menu_id'] = $menu;
       
        
        if($projeto_atual){
            
            $projeto_cadastro = $this->projetos_model->getProjetoByID($projeto_atual);
            $empresa_cadastro_projeto = $projeto_cadastro->empresa_id;
            if($empresa_cadastro_projeto == $empresa){
                redirect("project/homeProjeto");
            }else{
                redirect("project/index");
            } 
            
         
        }else{
         redirect("project/index");
        }
    }    
    
    public function index()
    {
        $this->sma->checkPermissions();
        
        if ($this->Settings->version == '2.3') {
            $this->session->set_flashdata('warning', 'Please complete your update by synchronizing your database.');
            redirect('sync');
        }
        
        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        
        $usuario = $this->session->userdata('user_id');                     
       // $this->data['planos'] = $this->atas_model->getAllPlanosUser($usuario);
       
        // SALVA O MÓDULO ATUAL do usuário
         $data_modulo = array('modulo_atual' => 4, 'menu_atual' => 84);
         $this->owner_model->updateModuloAtual($usuario, $data_modulo);
                    
        // registra o log de movimentação
         
        $date_hoje = date('Y-m-d H:i:s');    
        $usuario = $this->session->userdata('user_id');
        $empresa = $this->session->userdata('empresa');
        $ip = $_SERVER["REMOTE_ADDR"];

        $logdata = array('date' => date('Y-m-d H:i:s'), 
            'type' => 'PROJECT', 
            'description' => 'Acessou o menu PORTIFÓLIO do Módulo PROJECT - da empresa '.$empresa,  
            'userid' => $this->session->userdata('user_id'), 
            'ip_address' => $_SERVER["REMOTE_ADDR"],
            'tabela' => '',
            'row' => '',
            'depois' => '', 
            'modulo' => 'Project',
            'funcao' => 'Project/index',  
            'empresa' => $this->session->userdata('empresa'));
        $this->owner_model->addLog($logdata);  
         
         $users_dados = $this->site->geUserByID($usuario);
         $modulo_atual = $users_dados->modulo_atual;
         $menu_atual = $users_dados->menu_atual;
         $projeto_atual = $users_dados->projeto_atual;
         $criar_projetos = $users_dados->criar_projetos;
        
        $this->data['criar_projetos'] = $criar_projetos;
         
       $modulo_dados = $this->owner_model->getModuloById($modulo_atual);
       $controle = $modulo_dados->controle;
       $pagina_home = 'project/index';
      
       
        
       $this->page_construct_project_collapse($pagina_home, $meta, $this->data);
       
    }
    
     /*****************************************************************************************************************
     **************************************D A S H B O A R D ********************************************************** 
     ******************************************************************************************************************/
     public function dashboard()
    {
     //   $this->sma->checkPermissions();
      
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        
        /*
         * VERIFICA O PERFIL DO USUÁRIO
         */
        $usuario = $this->session->userdata('user_id');
        $projetos = $this->site->getProjetoAtualByID_completo($usuario);
        $id = $projetos->projeto_atual;
        $perfil_atual = $projetos->group_id;
        $perfis_user = $this->site->getUserGroupAtual($perfil_atual);
        $id_perfil_atual = $perfis_user->id;
        
        
        
        /*
         * CONSULTAS PARA TODOS OS PERFIS
         */
        
        //Qtde de pessoas na equipe
        $equipe = $this->projetos_model->getQtdeEquipeByProjeto($id);
        $this->data['equipe'] =  $equipe->responsavel;
        
        //Qtde de Atas DO PROJETO. SERVE PARA TODOS OS PERFIS
        $this->data['ata'] =  $this->projetos_model->getAtaByProjeto($id);
        
        //QTDE TOTAL de AÇÕES POR PROJETOS
        $total_acoes =  $this->projetos_model->getQtdeAcoesByProjeto($id);
        $this->data['total_acoes'] = $total_acoes->total_acoes;
        
        //Qtde de Ações concluídas
        $concluido = $this->projetos_model->getQtdeAcoesConcluidasByProjeto($id);
        $this->data['concluido'] =  $concluido->quantidade_concluida;
      
        //Qtde de ações Pendentes
        $pendente = $this->projetos_model->getQtdeAcoesPendentesByProjeto($id);
        $avalidacao = $this->projetos_model->getAcoesAguardandoValidacaoByProjeto($id);
        $this->data['pendente'] =  $pendente->pendente + $avalidacao->aguardando_validacao;
        
        //Qtde de Ações Atrasadas
        $atrasadas = $this->projetos_model->getQtdeAcoesAtrasadasByProjeto($id);
        $this->data['atrasadas'] =  $atrasadas->atrasadas;
        
        /*
         * EVENTOS - TIMELINE
         */
        $this->data['eventos'] = $this->projetos_model->getAllEventosProjeto($id);
       
        //GRÁFICO AÇOES NA LINHA DO TEMPO
        $desempenhos = $this->projetos_model->getAllitemStatusPlanosLinhaTempo($id);
        $this->data['acoes_tempo'] =  $desempenhos;
        
        /*
         * GRÁFICO PIE - PRESIDENCIA
        */
        $this->data['areas_projeto'] =  $this->projetos_model->getAreasByProjeto();
    
        //GRÁFICO PIE - total_acoes_areas
       //  $this->data['total_acoes_areas'] =  $this->projetos_model->getTotalAcoesSetoresPaiByProjeto();
        /* 
         * PEGA AS ÁREAS QUE TEM AÇÕES
         */
       // $this->data['areas_usuario_projeto'] = $this->projetos_model->getAreasByProjeto();
      
        
        
        /*
         * PERFIL EDP
         
        if($id_perfil_atual == 1){
        
       
        //SE FOR GESTOR
        
        /*
         * PERFIL DE GESTOR
         
        }else  if($id_perfil_atual == 2){
         /*
          * GESTOR
            
         $soma_qtde_equipe_superintendencia = 0;
         $soma_qtde_acoes_superintendencia = 0;
         $soma_qtde_acoes_concluidas_superintendencia = 0;
         $soma_qtde_acoes_pendentes_superintendencia = 0;
         $soma_qtde_acoes_avalidacao_superintendencia = 0;
         $soma_qtde_acoes_atrasadas_superintendencia = 0;
         $cont_acoes_tempo = 1;
         $user_superintendencias = $this->projetos_model->getSuperintenciaByUser($id_perfil_atual,$id,$usuario);
       
         foreach ($user_superintendencias as $user_superintendencia) {
         $id_superintendencia =  $user_superintendencia->setor;   
         
         /*
          * EQUIPE POR SETOR E PROJETO
         
         $qtde_equipe_superintendencia = $this->projetos_model->getEquipeByProjetoSuperintendencia($id_perfil_atual,$id, $id_superintendencia);
         $soma_qtde_equipe_superintendencia += $qtde_equipe_superintendencia->responsavel; 
         /*
          * QUANTIDADE DE AÇÕES POR SETOR E PROJETO
          
         $quantidade_acoes_superintendencia = $this->projetos_model->getQtdeAcoesByProjetoSuperintendencia($id_perfil_atual,$id,$id_superintendencia);
         $soma_qtde_acoes_superintendencia += $quantidade_acoes_superintendencia->total_acoes;
         /*
          * QUANTIDADE DE AÇÕES CONCLUÍDAS
          
         $qtde_acoes_concluida_superintendencia = $this->projetos_model->getAcoesByProjetoSuperintendenciaStatus($id_perfil_atual, $id, 'CONCLUÍDO',$id_superintendencia);
         $soma_qtde_acoes_concluidas_superintendencia += $qtde_acoes_concluida_superintendencia->quantidade;
         /*
          * QUANTIDADE DE AÇÕES PENDENTES
          
         $qtde_acoes_pendentes_superintendencia = $this->projetos_model->getAcoesByProjetoSuperintendenciaStatus($id_perfil_atual,$id, 'PENDENTE',$id_superintendencia);
         $soma_qtde_acoes_pendentes_superintendencia += $qtde_acoes_pendentes_superintendencia->quantidade;
         /*
          * QUANTIDADE DE AÇÕES AGUARDANDO VALIDAÇÃO
          
         $qtde_acoes_aguardando_validacao_superintendencia = $this->projetos_model->getAcoesByProjetoSuperintendenciaStatus($id_perfil_atual, $id, 'AGUARDANDO VALIDAÇÃO',$id_superintendencia);
         $soma_qtde_acoes_avalidacao_superintendencia += $qtde_acoes_aguardando_validacao_superintendencia->quantidade;
        
         /*
          * QUANTIDADE DE AÇÕES ATRASADAS
          
         $qtde_acoes_atrasadas_superintendencia = $this->projetos_model->getAcoesByProjetoSuperintendenciaStatus($id_perfil_atual,$id, 'ATRASADO',$id_superintendencia);
         $soma_qtde_acoes_atrasadas_superintendencia += $qtde_acoes_atrasadas_superintendencia->quantidade;
         /*
          * AÇÕES NA LINHA DO TEMPO
          
         $id_superintendencia_data[$cont_acoes_tempo++] = $id_superintendencia;
         
        //$soma_qtde_acoes_tempo += $qtde_acoes_tempo;
         
        }
        
        //Qtde de pessoas na equipe
         $this->data['equipe'] =  $soma_qtde_equipe_superintendencia;
        //Qtde de Ações
        $this->data['total_acoes'] =  $soma_qtde_acoes_superintendencia;
        //Qtde de Ações concluídas
        $this->data['concluido'] =  $soma_qtde_acoes_concluidas_superintendencia;
        //Qtde de ações Pendentes
        $this->data['pendente'] =  ($soma_qtde_acoes_pendentes_superintendencia + $soma_qtde_acoes_avalidacao_superintendencia);
           //Qtde de Ações Atrasadas
        $this->data['atrasadas'] =  $soma_qtde_acoes_atrasadas_superintendencia;
        
         //GRÁFICO AÇOES NA LINHA DO TEMPO
       // print_r($id_superintendencia_data);exit;
        $qtde_acoes_tempo = $this->projetos_model->getAllitemPlanosLinhaTempoSuperintendencia($id_perfil_atual,$id,$id_superintendencia_data);
        $this->data['acoes_tempo'] =   $qtde_acoes_tempo;
        
        $this->data['areas_usuario_projeto'] =  $this->projetos_model->getGestoresSetoresByUsuarioProjeto($id,$usuario);
        
        
        
        
        /*
         * SUPERINTENDENTE
         
        } if($id_perfil_atual == 3){
        
       /*
        * SUPERINTENDENCIAS LIGADA AO USUÁRIO
        
         
         $soma_qtde_equipe_superintendencia = 0;
         $soma_qtde_acoes_superintendencia = 0;
         $soma_qtde_acoes_concluidas_superintendencia = 0;
         $soma_qtde_acoes_pendentes_superintendencia = 0;
         $soma_qtde_acoes_avalidacao_superintendencia = 0;
         $soma_qtde_acoes_atrasadas_superintendencia = 0;
         $cont_acoes_tempo = 1;
         $user_superintendencias = $this->projetos_model->getSuperintenciaByUser($id_perfil_atual,$id,$usuario);
         
         foreach ($user_superintendencias as $user_superintendencia) {
         $id_superintendencia =  $user_superintendencia->superintendencia;   
         
         /*
          * EQUIPE POR SUPERINTENDENCIA E PROJETO
          
         $qtde_equipe_superintendencia = $this->projetos_model->getEquipeByProjetoSuperintendencia($id_perfil_atual,$id, $id_superintendencia);
         $soma_qtde_equipe_superintendencia += $qtde_equipe_superintendencia->responsavel; 
         /*
          * QUANTIDADE DE AÇÕES POR SUPERINTENDENCIA E PROJETO
          
         $quantidade_acoes_superintendencia = $this->projetos_model->getQtdeAcoesByProjetoSuperintendencia($id_perfil_atual,$id,$id_superintendencia);
         $soma_qtde_acoes_superintendencia += $quantidade_acoes_superintendencia->total_acoes;
         /*
          * QUANTIDADE DE AÇÕES CONCLUÍDAS
          
         $qtde_acoes_concluida_superintendencia = $this->projetos_model->getAcoesByProjetoSuperintendenciaStatus($id_perfil_atual, $id, 'CONCLUÍDO',$id_superintendencia);
         $soma_qtde_acoes_concluidas_superintendencia += $qtde_acoes_concluida_superintendencia->quantidade;
         /*
          * QUANTIDADE DE AÇÕES PENDENTES
          
         $qtde_acoes_pendentes_superintendencia = $this->projetos_model->getAcoesByProjetoSuperintendenciaStatus($id_perfil_atual, $id, 'PENDENTE',$id_superintendencia);
         $soma_qtde_acoes_pendentes_superintendencia += $qtde_acoes_pendentes_superintendencia->quantidade;
         /*
          * QUANTIDADE DE AÇÕES AGUARDANDO VALIDAÇÃO
          
         $qtde_acoes_aguardando_validacao_superintendencia = $this->projetos_model->getAcoesByProjetoSuperintendenciaStatus($id_perfil_atual, $id, 'AGUARDANDO VALIDAÇÃO',$id_superintendencia);
         $soma_qtde_acoes_avalidacao_superintendencia += $qtde_acoes_aguardando_validacao_superintendencia->quantidade;
         /*
          * QUANTIDADE DE AÇÕES ATRASADAS
          
         $qtde_acoes_atrasadas_superintendencia = $this->projetos_model->getAcoesByProjetoSuperintendenciaStatus($id_perfil_atual, $id, 'ATRASADO',$id_superintendencia);
         $soma_qtde_acoes_atrasadas_superintendencia += $qtde_acoes_atrasadas_superintendencia->quantidade;
         /*
          * AÇÕES NA LINHA DO TEMPO
          
         $id_superintendencia_data[$cont_acoes_tempo++] = $id_superintendencia;
         
        //$soma_qtde_acoes_tempo += $qtde_acoes_tempo;
         
        }
        
        //Qtde de pessoas na equipe
         $this->data['equipe'] =  $soma_qtde_equipe_superintendencia;
        //Qtde de Ações
        $this->data['total_acoes'] =  $soma_qtde_acoes_superintendencia;
        //Qtde de Ações concluídas
        $this->data['concluido'] =  $soma_qtde_acoes_concluidas_superintendencia;
        //Qtde de ações Pendentes
        $this->data['pendente'] =  ($soma_qtde_acoes_pendentes_superintendencia + $soma_qtde_acoes_avalidacao_superintendencia);
           //Qtde de Ações Atrasadas
        $this->data['atrasadas'] =  $soma_qtde_acoes_atrasadas_superintendencia;
        
        //GRÁFICO AÇOES NA LINHA DO TEMPO
        // print_r($id_superintendencia_data);exit;
        $qtde_acoes_tempo = $this->projetos_model->getAllitemPlanosLinhaTempoSuperintendencia($id_perfil_atual,$id,$id_superintendencia_data);
        $this->data['acoes_tempo'] =   $qtde_acoes_tempo;
        
        $this->data['areas_usuario_projeto'] =  $this->projetos_model->getAreasByUsuarioProjeto($id,$usuario);
        
        
        }
        
        */
        //data_atual
        $this->data['data_hoje'] = date('Y-m-d H:i:s');
        
        //$this->data['projetos'] = $this->atas_model->getAllProjetos();
         $this->load->view($this->theme . 'project/dashboard/index', $this->data);       
    }
    
    public function dashboard_setor($menu)
    {
        $this->sma->checkPermissions();
      
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        
        
        /*
         * VERIFICA O PERFIL DO USUÁRIO
         */
        $usuario = $this->session->userdata('user_id');
        $users_dados = $this->site->geUserByID($usuario);
         $modulo_atual = $users_dados->modulo_atual;
         $menu_atual = $users_dados->menu_atual;
         $projeto_atual = $users_dados->projeto_atual;
       
          // SALVA O MÓDULO ATUAL do usuário
         $data_modulo = array('modulo_atual' => 4, 'menu_atual' => $menu);
         $this->owner_model->updateModuloAtual($usuario, $data_modulo);
         
        $this->data['areas_projeto'] =  $this->projetos_model->getAreasByProjeto();      
        //data_atual
        $this->data['data_hoje'] = date('Y-m-d H:i:s');
        

       $pagina_home = 'project/dashboard/dashboard_setores';
      
       
       $this->page_construct_project($pagina_home, $meta, $this->data);
    }
    
    public function calendario($menu)
    {
          
       $this->sma->checkPermissions(); 
        if ($this->Settings->version == '2.3') {
            $this->session->set_flashdata('warning', 'Please complete your update by synchronizing your database.');
            redirect('sync');
        }

        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        
        $usuario = $this->session->userdata('user_id');                     
        
        // SALVA O MÓDULO ATUAL do usuário
         $data_modulo = array('modulo_atual' => 4, 'menu_atual' => 60);
         $this->owner_model->updateModuloAtual($usuario, $data_modulo);
                    
        // registra o log de movimentação
         
        $date_hoje = date('Y-m-d H:i:s');    
        $usuario = $this->session->userdata('user_id');
        $empresa = $this->session->userdata('empresa');
        $ip = $_SERVER["REMOTE_ADDR"];

        $logdata = array('date' => date('Y-m-d H:i:s'), 
            'type' => 'PROJECT', 
            'description' => 'Acessou o menu CALENDÁRIO do Módulo PROJECT - da empresa '.$empresa,  
            'userid' => $this->session->userdata('user_id'), 
            'ip_address' => $_SERVER["REMOTE_ADDR"],
            'tabela' => '',
            'row' => '',
            'depois' => '', 
            'modulo' => 'Project',
            'funcao' => 'Project/calendario',  
            'empresa' => $this->session->userdata('empresa'));
        $this->owner_model->addLog($logdata);  
         
         $users_dados = $this->site->geUserByID($usuario);
         $modulo_atual = $users_dados->modulo_atual;
         $menu_atual = $users_dados->menu_atual;
         $projeto_atual = $users_dados->projeto_atual;
         
        
         
       $modulo_dados = $this->owner_model->getModuloById($modulo_atual);
       $controle = $modulo_dados->controle;
       $pagina_home = 'project/calendario';
        
      
       
       
       $this->page_construct_project_calendario($pagina_home, $meta, $this->data);
       
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
     * SELECIONA UM PROJETO
     */
    public function selecionarProjeto($id = null)
    {
        $this->sma->checkPermissions();
       //echo 'aqui'.$id; exit;
        
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        $data_projeto['projeto_atual'] = $id;
        $usuario = $this->session->userdata('user_id');
        $this->projetos_model->updateProjetoUsuario($usuario,$data_projeto);
        
           
        redirect("project/homeProjeto");
            
    }
    
     /*
     * SELECIONA UM PROJETO
     */
    public function ativarProjeto($id = null)
    {
        $this->sma->checkPermissions();
      //echo 'aqui'.$id; exit;
        
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        
        $data_projeto = array('status' => 'ATIVO');
        $this->projetos_model->updateProjeto($id, $data_projeto); 
        
        $data_projeto['projeto_atual'] = $id;
        $usuario = $this->session->userdata('user_id');
        $this->projetos_model->updateProjetoUsuario($usuario,$data_projeto);
        
        
       /***********************************************************************************************
        ********************** L O G    PROJETO ****************************************************** 
        ***********************************************************************************************/
            $date_hoje = date('Y-m-d H:i:s');
            $usuario = $this->session->userdata('user_id');
            $empresa = $this->session->userdata('empresa');
            $ip = $_SERVER["REMOTE_ADDR"];
            $usuario_dados = $this->site->geUserByID($usuario);
            $nome = $usuario_dados->first_name;
            
           $data_log_projeto = array(
                'projetos_id' => $id,
                'data_registro' => $date_hoje,
                'usuario' => $usuario,
                'descricao' => "O usuário $nome,  Ativou o Projeto.",
                'antes' => "EM AGUARDO",
                'depois' => "ATIVO",
                'empresa' => $empresa
              );
           $this->owner_model->addCadastro('projetos_log', $data_log_projeto);
           
            

            $logdata = array('date' => date('Y-m-d H:i:s'),
                'type' => 'UPDATE',
                'description' => 'O usuário '.$nome.', ativou o projeto id: '.$id,
                'userid' => $this->session->userdata('user_id'),
                'ip_address' => $_SERVER["REMOTE_ADDR"],
                'tabela' => 'sig_projetos',
                'row' => $id,
                'antes' => "status = EM AGUARDO",
                'depois' => json_encode($data_projeto),
                'modulo' => 'project',
                'funcao' => 'project/ativarProjeto',
                'empresa' => $this->session->userdata('empresa'));

            $this->owner_model->addLog($logdata);
           
        
        
        $this->session->set_flashdata('message', lang("Projeto Ativado com Sucesso!!!")); 
        echo "<script>history.go(-1)</script>";
       // redirect("project/homeProjeto");
            
    }
    
    
     public function homeProjeto()
    {
         $this->sma->checkPermissions();
          
        if ($this->Settings->version == '2.3') {
            $this->session->set_flashdata('warning', 'Please complete your update by synchronizing your database.');
            redirect('sync');
        }

        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        
        $usuario = $this->session->userdata('user_id');                     
      //  $this->data['planos'] = $this->atas_model->getAllPlanosUser($usuario);
       
        
       
        // SALVA O MÓDULO ATUAL do usuário
         $data_modulo = array('modulo_atual' => 4, 'menu_atual' => 24);
         $this->owner_model->updateModuloAtual($usuario, $data_modulo);
                    
        // registra o log de movimentação
         
        $date_hoje = date('Y-m-d H:i:s');    
        $usuario = $this->session->userdata('user_id');
        $empresa = $this->session->userdata('empresa');
        $ip = $_SERVER["REMOTE_ADDR"];

        $logdata = array('date' => date('Y-m-d H:i:s'), 
            'type' => 'PROJECT', 
            'description' => 'Acessou o menu HOME do Módulo PROJECT - da empresa '.$empresa,  
            'userid' => $this->session->userdata('user_id'), 
            'ip_address' => $_SERVER["REMOTE_ADDR"],
            'tabela' => '',
            'row' => '',
            'depois' => '', 
            'modulo' => 'Project',
            'funcao' => 'Project/$pagina_home',  
            'empresa' => $this->session->userdata('empresa'));
        $this->owner_model->addLog($logdata);  
         
         $users_dados = $this->site->geUserByID($usuario);
         $modulo_atual = $users_dados->modulo_atual;
         $menu_atual = $users_dados->menu_atual;
         $projeto_atual = $users_dados->projeto_atual;
         
        
         /*********** D A S H B O A R D******************/
         /*
         * VERIFICA O PERFIL DO USUÁRIO
         */
        $usuario = $this->session->userdata('user_id');
        $projetos = $this->site->getProjetoAtualByID_completo($usuario);
        $id = $projetos->projeto_atual;
        $perfil_atual = $projetos->group_id;
        $perfis_user = $this->site->getUserGroupAtual($perfil_atual);
        $id_perfil_atual = $perfis_user->id;
        
        
        
        /*
         * CONSULTAS PARA TODOS OS PERFIS
         */
        
        //Qtde de pessoas na equipe
        $equipe = $this->projetos_model->getQtdeEquipeByProjeto($id);
        $this->data['equipe'] =  $equipe->responsavel;
        
        //Qtde de Atas DO PROJETO. SERVE PARA TODOS OS PERFIS
        $this->data['ata'] =  $this->projetos_model->getAtaByProjeto($id);
        
        //QTDE TOTAL de AÇÕES POR PROJETOS
        $total_acoes =  $this->projetos_model->getQtdeAcoesByProjeto($id);
        $this->data['total_acoes'] = $total_acoes->total_acoes;
        
        //Qtde de Ações concluídas
        $concluido = $this->projetos_model->getQtdeAcoesConcluidasByProjeto($id);
        $this->data['concluido'] =  $concluido->quantidade_concluida;
      
        //Qtde de ações Pendentes
        $pendente = $this->projetos_model->getQtdeAcoesPendentesByProjeto($id);
        $avalidacao = $this->projetos_model->getAcoesAguardandoValidacaoByProjeto($id);
        $this->data['pendente'] =  $pendente->pendente + $avalidacao->aguardando_validacao;
        
        //Qtde de Ações Atrasadas
        $atrasadas = $this->projetos_model->getQtdeAcoesAtrasadasByProjeto($id);
        $this->data['atrasadas'] =  $atrasadas->atrasadas;
        
        /*
         * EVENTOS - TIMELINE
         */
        $this->data['eventos'] = $this->projetos_model->getAllEventosProjeto($id);
       
        //GRÁFICO AÇOES NA LINHA DO TEMPO
        $desempenhos = $this->projetos_model->getAllitemStatusPlanosLinhaTempo();
        $this->data['acoes_tempo'] =  $desempenhos;
        
        /*
         * GRÁFICO PIE - PRESIDENCIA
        */
        $this->data['areas_projeto'] =  $this->projetos_model->getAreasByProjeto();
        
        /*********F I M    D A S H B O A R D***************************/
         
       $modulo_dados = $this->owner_model->getModuloById($modulo_atual);
       $controle = $modulo_dados->controle;
       $pagina_home = 'project/home';
      
       
       $this->page_construct_project_home($pagina_home, $meta, $this->data);
       //$this->page_construct_project_collapse($pagina_home, $meta, $this->data);
       
    }
    

    
    
    
    /************************************************************************************************************
     ****************************************** PROJETOS **********************************************
     ************************************************************************************************************/
   
    
     public function novoProjeto()
    {
         $this->sma->checkPermissions();                     
        
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        
         $this->form_validation->set_rules('projeto', lang("Projeto"), 'required');
         $this->form_validation->set_rules('data_inicio', lang("Data Início"), 'required');
         $this->form_validation->set_rules('data_termino', lang("Data Término"), 'required');
         $this->form_validation->set_rules('gerente', lang("Gerente"), 'required');
         
         if ($this->form_validation->run() == true) {
           
             $projeto = $this->input->post('projeto');
             $cliente = $this->input->post('cliente');
             $categoria = $this->input->post('categoria');
             $data_inicio = $this->input->post('data_inicio');
             $data_termino = $this->input->post('data_termino');
             $gerente = $this->input->post('gerente');
             $coordenador = $this->input->post('coordenador');
             
             $justificativa = $this->input->post('justificativa');
             $objetivo = $this->input->post('objetivo');
             $descricao = $this->input->post('descricao');
             $Premissas= $this->input->post('Premissas');
             $restricoes= $this->input->post('restricoes');
             $beneficios= $this->input->post('beneficios');
             $status = 'EM AGUARDO';
             $usuario = $this->session->userdata('user_id');
             $date_cadastro = date('Y-m-d H:i:s');     
             $empresa = $this->session->userdata('empresa');
             //echo $projeto; exit;
            // echo $tabela_nome.'<br>';exit;
             
            
             //$this->site->getUser($this->session->userdata('user_id'));
            $data_projeto = array(
                'projeto' => $projeto,
                'cliente' => $cliente,
                'categoria' => $categoria,
                'dt_inicio' => $data_inicio,
                'dt_final' => $data_termino,
                'gerente_area' => $gerente,
                'edp_id' => $coordenador,
                
                'data_criacao' => $date_cadastro,
                'usuario' => $usuario,
                'status' => $status,
                'empresa_id' => $empresa,
                
                'justificativa' => $justificativa,   
                'objetivo' => $objetivo,
                'descricao' => $descricao,
                'Premissas' => $Premissas,
                'restricoes' => $restricoes,
                'beneficios' => $beneficios
            );
          
             $id_cadastro = $this->projetos_model->addProjetos($data_projeto);
             
             
            /*
             * ADICIONA O USUÁRIO AO PROJETO
             */  
            $data_acesso = array(
               'users' => $usuario,
               'projeto' => $id_cadastro,
                'criador' => 1
            );
            $this->owner_model->addCadastro('users_projetos', $data_acesso);
          
            //MUDA A ABA DO CADASTRO
            $data_projeto = array(
                'aba' => 1
            );
            $this->projetos_model->updateProjeto($id_cadastro, $data_projeto);       
            
            //MUDA O PROJETO ATUAL
            $data_projeto_atual['projeto_atual'] = $id_cadastro;
            $usuario = $this->session->userdata('user_id');
            $this->projetos_model->updateProjetoUsuario($usuario,$data_projeto_atual);
            
            $date_hoje = date('Y-m-d H:i:s');
            $usuario = $this->session->userdata('user_id');
            $empresa = $this->session->userdata('empresa');
            $ip = $_SERVER["REMOTE_ADDR"];
            $usuario_dados = $this->site->geUserByID($usuario);
            $nome = $usuario_dados->first_name;
            
           /*********** LOG PROJETOS  **************/
           $data_log_projeto = array(
                'projetos_id' => $id_cadastro,
                'data_registro' => $date_hoje,
                'usuario' => $usuario,
                'descricao' => "O usuário $nome,  Criou o Projeto.",
                'antes' => "",
                'depois' => "",
                'empresa' => $empresa
              );
           $this->owner_model->addCadastro('projetos_log', $data_log_projeto);
           //*********************************************************************************
           /************************* LOG SIG  ***********************************************/
            $logdata = array('date' => date('Y-m-d H:i:s'), 
                'type' => 'INSERT', 
                'description' => '"O usuário '.$nome. ',  Criou o Projeto id: '.$id_cadastro,  
                'userid' => $this->session->userdata('user_id'), 
                'ip_address' => $_SERVER["REMOTE_ADDR"],
                'tabela' => "sig_projetos",
                'row' => $id_cadastro,
                'depois' => json_encode($data_projeto), 
                'modulo' => 'project',
                'funcao' => 'project/novoProjeto',  
                'empresa' => $this->session->userdata('empresa'));
           
               $this->owner_model->addLog($logdata);  
           // exit;
            
            $this->session->set_flashdata('message', lang("Cadastro realizado com Sucesso!!!"));
            redirect("project/editarProjeto/42/85");
            
         }else{
        
        $this->data['categorias'] = $this->projetos_model->getAllCategoriaProjetoByEmpresa();     
        $this->data['clientes'] = $this->projetos_model->getAllClientesByEmpresa();   
        $this->data['users'] = $this->atas_model->getAllUsersSetores(); 
         $usuario = $this->session->userdata('user_id');                     
      
        //$this->load->view($this->theme . 'projetos/documentacao/add', $this->data);
      
         $this->page_construct_project_collapse('project/projetos/novoProjeto', $meta, $this->data);   
    }
            
    }
    
    
     public function editarProjeto($projeto_id)
    {
        $this->sma->checkPermissions();                      
        
        $data_projeto['projeto_atual'] = $projeto_id;
        $usuario = $this->session->userdata('user_id');
        $this->projetos_model->updateProjetoUsuario($usuario,$data_projeto);
        
         $this->form_validation->set_rules('projeto', lang("Projeto"), 'required');
         $this->form_validation->set_rules('data_inicio', lang("Data Início"), 'required');
         $this->form_validation->set_rules('data_termino', lang("Data Término"), 'required');
         $this->form_validation->set_rules('gerente', lang("Gerente"), 'required');
         
         if ($this->form_validation->run() == true) {
           
             $id_projeto = $this->input->post('idprojeto');
             $nome_projeto = $this->input->post('projeto');
             $cliente = $this->input->post('cliente');
             $categoria = $this->input->post('categoria');
             $data_inicio = $this->input->post('data_inicio');
             $data_termino = $this->input->post('data_termino');
             $gerente = $this->input->post('gerente');
             $coordenador = $this->input->post('coordenador');
             
             $justificativa = $this->input->post('justificativa');
             $objetivo = $this->input->post('objetivo');
             $descricao = $this->input->post('descricao');
             $Premissas= $this->input->post('premissas');
             $restricoes= $this->input->post('restricoes');
             $beneficios= $this->input->post('beneficios');
             $status = 'EM AGUARDO';
             $usuario = $this->session->userdata('user_id');
             $date_cadastro = date('Y-m-d H:i:s');     
             
            
            $date_hoje = date('Y-m-d H:i:s');
            $usuario = $this->session->userdata('user_id');
            $empresa = $this->session->userdata('empresa');
            $ip = $_SERVER["REMOTE_ADDR"];
            $usuario_dados = $this->site->geUserByID($usuario);
            $nome = $usuario_dados->first_name;
            
            
           /************************************ LOG PROJETOS  ******************************************
            *********************************************************************************************/
           //PEGA OS DADOS ATUAL DO CADASTRO, ANTES DE ATUALIZAR.
           $projeto_atual = $this->projetos_model->getProjetoByID($id_projeto);
           $status_atual = $projeto_atual->status;
           $nome_projeto_atual = $projeto_atual->projeto;
           $cliente_atual = $projeto_atual->cliente;
           $categoria_atual = $projeto_atual->categoria;
           $dt_inicio_atual = $projeto_atual->dt_inicio;
           $dt_final_atual = $projeto_atual->dt_final;
           $gerente_atual = $projeto_atual->gerente_area;
           $edp_atual = $projeto_atual->edp_id;
           $justificativa_atual = $projeto_atual->justificativa;
           $objetivo_atual = $projeto_atual->objetivo;
           $descricao_atual = $projeto_atual->descricao;
           $Premissas_atual = $projeto_atual->premissas;
           $restricoes_atual = $projeto_atual->restricoes;
           $beneficios_atual = $projeto_atual->beneficios;
           
            $data_atual_projeto = array(
                'projeto' => $nome_projeto_atual,
                'cliente' => $cliente_atual,
                'categoria' => $categoria_atual,
                'dt_inicio' => $dt_inicio_atual,
                'dt_final' => $dt_final_atual,
                'gerente_area' => $gerente_atual,
                'edp_id' => $edp_atual,
                'data_criacao' => $date_cadastro,
                'usuario' => $usuario,
                'justificativa' => $justificativa_atual,   
                'objetivo' => $objetivo_atual,
                'descricao' => $descricao_atual,
                'premissas' => $Premissas_atual,
                'restricoes' => $restricoes_atual,
                'beneficios' => $beneficios_atual
            );
           
           //SE O PROJETO ESTA ATIVO, COMPARA QUAL OS CAMPOS FORAM ATUALIZADOS E GRAVA NO LOG DO PROJETO//
           if($status_atual == "ATIVO"){
               
               //NOME DO PROJETO
               if($nome_projeto != $nome_projeto_atual){
                $data_log_projeto = array(
                  'projetos_id' => $id_projeto,
                  'data_registro' => $date_hoje,
                  'usuario' => $usuario,
                  'descricao' => "O usuário $nome,  Atualizou o nome do projeto",
                  'antes' => "$nome_projeto_atual",
                  'depois' => "$nome_projeto",
                  'empresa' => $empresa
                );
                $this->owner_model->addCadastro('projetos_log', $data_log_projeto);  
               }
               
               //CLIENTE DO PROJETO
               if($cliente != $cliente_atual){
                $dados_clientes       = $this->projetos_model->getClienteByIdAndEmpresa($cliente);
                $dados_clientes_atual = $this->projetos_model->getClienteByIdAndEmpresa($cliente_atual);
                $nome_cliente_atual = $dados_clientes_atual->name;   
                $nome_cliente = $dados_clientes->name;
                $data_log_cliente_projeto = array(
                  'projetos_id' => $id_projeto,
                  'data_registro' => $date_hoje,
                  'usuario' => $usuario,
                  'descricao' => "O usuário $nome,  Atualizou o <b> cliente </b> do projeto para $nome_cliente.",
                  'antes' => "$nome_cliente_atual",
                  'depois' => "$nome_cliente",
                  'empresa' => $empresa
                );
                $this->owner_model->addCadastro('projetos_log', $data_log_cliente_projeto);  
               }
               
               //CATEGORIA DO PROJETO
               if($categoria != $categoria_atual){
                $dados_categoria       = $this->projetos_model->getCategoriaByIdAndEmpresa($categoria);
                $dados_categoria_atual = $this->projetos_model->getCategoriaByIdAndEmpresa($categoria_atual);
                $nome_categoria_atual = $dados_categoria_atual->descricao;   
                $nome_categoria = $dados_categoria->descricao;   
                $data_log_cliente_projeto = array(
                  'projetos_id' => $id_projeto,
                  'data_registro' => $date_hoje,
                  'usuario' => $usuario,
                  'descricao' => "O usuário $nome,  Atualizou a <b> Categoria </b> do projeto para $nome_categoria",
                  'antes' => "$nome_categoria_atual",
                  'depois' => "$nome_categoria",
                  'empresa' => $empresa
                );
                $this->owner_model->addCadastro('projetos_log', $data_log_cliente_projeto);  
               }
               
               //DATA INICIO DO PROJETO
               if($data_inicio != $dt_inicio_atual){
                $data_log_cliente_projeto = array(
                  'projetos_id' => $id_projeto,
                  'data_registro' => $date_hoje,
                  'usuario' => $usuario,
                  'descricao' => "O usuário $nome,  Atualizou a <b> Data de Início </b> do projeto para ".date('d/m/Y ', strtotime($data_inicio)),
                  'antes' => date('d/m/Y ', strtotime($dt_inicio_atual)),
                  'depois' => date('d/m/Y', strtotime($data_inicio)),
                  'empresa' => $empresa
                );
                $this->owner_model->addCadastro('projetos_log', $data_log_cliente_projeto);  
               }
               
               //DATA FINAL DO PROJETO
               if($data_termino != $dt_final_atual){
                $data_log_cliente_projeto = array(
                  'projetos_id' => $id_projeto,
                  'data_registro' => $date_hoje,
                  'usuario' => $usuario,
                  'descricao' => "O usuário $nome,  Atualizou a <b> Data de Término </b> do projeto para ".date('d/m/Y ', strtotime($data_termino)),
                  'antes' => date('d/m/Y ', strtotime($dt_final_atual)),
                  'depois' => date('d/m/Y ', strtotime($data_termino)),
                  'empresa' => $empresa
                );
                $this->owner_model->addCadastro('projetos_log', $data_log_cliente_projeto);  
               }
               
               //GERENTE DO PROJETO
               if($gerente != $gerente_atual){
                $dados_gerente       = $this->atas_model->getAllUsersSetoresById($gerente);
                $dados_gerente_atual = $this->atas_model->getAllUsersSetoresById($gerente_atual);
                $nome_gerente_atual = $dados_gerente_atual->nome;   
                $nome_gerente       = $dados_gerente->nome;   
                $data_log_gerente_projeto = array(
                  'projetos_id' => $id_projeto,
                  'data_registro' => $date_hoje,
                  'usuario' => $usuario,
                  'descricao' => "O usuário $nome,  Atualizou o <b> gerente </b> do projeto para $nome_gerente",
                  'antes' => "$nome_gerente_atual",
                  'depois' => "$nome_gerente",
                  'empresa' => $empresa
                );
                $this->owner_model->addCadastro('projetos_log', $data_log_gerente_projeto);  
               }
               
               //COORDENADOR DO PROJETO
               if($coordenador != $edp_atual){
                $dados_coordenador       = $this->atas_model->getAllUsersSetoresById($coordenador);
                $dados_coordenador_atual = $this->atas_model->getAllUsersSetoresById($edp_atual);
                $nome_coordenador_atual = $dados_coordenador_atual->nome;   
                $nome_coordenador       = $dados_coordenador->nome;   
                $data_log_coordenador_projeto = array(
                  'projetos_id' => $id_projeto,
                  'data_registro' => $date_hoje,
                  'usuario' => $usuario,
                  'descricao' => "O usuário $nome,  Atualizou o <b> Coordenador </b> do projeto para $nome_coordenador",
                  'antes' => "$nome_coordenador_atual",
                  'depois' => "$nome_coordenador",
                  'empresa' => $empresa
                );
                $this->owner_model->addCadastro('projetos_log', $data_log_coordenador_projeto);  
               }
               
               //JUSTIFICATIVA DO PROJETO
               if($justificativa != $justificativa_atual){
                $data_log_justificativa_projeto = array(
                  'projetos_id' => $id_projeto,
                  'data_registro' => $date_hoje,
                  'usuario' => $usuario,
                  'descricao' => "O usuário $nome,  Atualizou a <b> Justificativa </b> do projeto para $justificativa",
                  'antes' => "$justificativa_atual",
                  'depois' => "$justificativa",
                  'empresa' => $empresa
                );
                $this->owner_model->addCadastro('projetos_log', $data_log_justificativa_projeto);  
               }
               
               //OBJETIVO DO PROJETO
               if($objetivo != $objetivo_atual){
                $data_log_objetivo_projeto = array(
                  'projetos_id' => $id_projeto,
                  'data_registro' => $date_hoje,
                  'usuario' => $usuario,
                  'descricao' => "O usuário $nome,  Atualizou O <b> Objetivo </b> do projeto para $objetivo",
                  'antes' => "$objetivo_atual",
                  'depois' => "$objetivo",
                  'empresa' => $empresa
                );
                $this->owner_model->addCadastro('projetos_log', $data_log_objetivo_projeto);  
               }
               
               //DESCRICAO DO PROJETO
               if($descricao != $descricao_atual){
                $data_log_descricao_projeto = array(
                  'projetos_id' => $id_projeto,
                  'data_registro' => $date_hoje,
                  'usuario' => $usuario,
                  'descricao' => "O usuário $nome,  Atualizou a <b> Descrição </b> do projeto para $descricao",
                  'antes' => "$descricao_atual",
                  'depois' => "$descricao",
                  'empresa' => $empresa
                );
                $this->owner_model->addCadastro('projetos_log', $data_log_descricao_projeto);  
               }
               
               //PREMISSAS DO PROJETO
               if($Premissas != $Premissas_atual){
                $data_log_premissa_projeto = array(
                  'projetos_id' => $id_projeto,
                  'data_registro' => $date_hoje,
                  'usuario' => $usuario,
                  'descricao' => "O usuário $nome,  Atualizou as <b> Premissas </b> do projeto para $Premissas",
                  'antes' => "$Premissas_atual",
                  'depois' => "$Premissas",
                  'empresa' => $empresa
                );
                $this->owner_model->addCadastro('projetos_log', $data_log_premissa_projeto);  
               }
               
               //RESTRICOES DO PROJETO
               if($restricoes != $restricoes_atual){
                $data_log_restricao_projeto = array(
                  'projetos_id' => $id_projeto,
                  'data_registro' => $date_hoje,
                  'usuario' => $usuario,
                  'descricao' => "O usuário $nome,  Atualizou as <b> Restriçoes </b> do projeto para $restricoes",
                  'antes' => "$restricoes_atual",
                  'depois' => "$restricoes",
                  'empresa' => $empresa
                );
                $this->owner_model->addCadastro('projetos_log', $data_log_restricao_projeto);  
               }
               
               //BENEFÍCIOS DO PROJETO
               if($beneficios != $beneficios_atual){
                $data_log_beneficios_projeto = array(
                  'projetos_id' => $id_projeto,
                  'data_registro' => $date_hoje,
                  'usuario' => $usuario,
                  'descricao' => "O usuário $nome,  Atualizou os <b> Benefícios </b> do projeto para $beneficios",
                  'antes' => "$beneficios_atual",
                  'depois' => "$beneficios",
                  'empresa' => $empresa
                );
                $this->owner_model->addCadastro('projetos_log', $data_log_beneficios_projeto);  
               }
            /*
           * FIM LOG PROJETO
           */   
           }
           
          
           
           
           
           /*
            * FAZ A ATUALIZAÇAO NO BANCO DE DADOS
            */
            // dados enviado do formulario 
            $data_projeto = array(
                'projeto' => $nome_projeto,
                'cliente' => $cliente,
                'categoria' => $categoria,
                'dt_inicio' => $data_inicio,
                'dt_final' => $data_termino,
                'gerente_area' => $gerente,
                'edp_id' => $coordenador,
                'data_criacao' => $date_cadastro,
                'usuario' => $usuario,
                'justificativa' => $justificativa,   
                'objetivo' => $objetivo,
                'descricao' => $descricao,
                'premissas' => $Premissas,
                'restricoes' => $restricoes,
                'beneficios' => $beneficios,
                'aba' => 1
            );
          $this->projetos_model->updateProjeto($id_projeto, $data_projeto);
         /*************************************************************************************/    
             
          
           //*********************************************************************************
           /************************* LOG SIG  ***********************************************
            SALVA O REGISTRO DE ANTES E DEPOIS DA ATUALIZAÇAO*/
            $logdata = array('date' => date('Y-m-d H:i:s'), 
                'type' => 'UPDATE', 
                'description' => 'O usuário '.$nome. ',Atualizou o Cadastro do projeto '.$id_projeto,  
                'userid' => $this->session->userdata('user_id'), 
                'ip_address' => $_SERVER["REMOTE_ADDR"],
                'tabela' => 'sig_projetos',
                'row' => $id_projeto,
                'antes' => json_encode($data_atual_projeto), 
                'depois' => json_encode($data_projeto), 
                'modulo' => 'project',
                'funcao' => 'project/editarProjeto',  
                'empresa' => $this->session->userdata('empresa'));
           
               $this->owner_model->addLog($logdata);  
           // exit;
            
             $data_projeto = array(
                'aba' => 1
            );
            $this->projetos_model->updateProjeto($id_projeto, $data_projeto);    
               
            $this->session->set_flashdata('message', lang("Cadastro atualizado com Sucesso!!!"));
            redirect("project/index");
            // echo "<script>history.go(-1)</script>";
         }else{
        
        $usuario = $this->session->userdata('user_id');
        $users_dados = $this->site->geUserByID($usuario);
        $id_projeto = $users_dados->projeto_atual;
        //echo $id_projeto; exit;
        //   $this->data['acoes_arquivos'] = $this->atas_model->getAllArquivosByAcao($id_acao); 
        $this->data['id'] = $id_projeto;
        $this->data['projeto'] = $this->projetos_model->getProjetoByID($id_projeto);     
        $this->data['categorias'] = $this->projetos_model->getAllCategoriaProjetoByEmpresa();     
        $this->data['clientes'] = $this->projetos_model->getAllClientesByEmpresa();   
        $this->data['users'] = $this->atas_model->getAllUsersSetores(); 
        
         /*
          * ACESSO AO PROJETO
          */
         $this->data['acessos'] = $this->projetos_model->getAllUsuarioAcessoByProjeto($id_projeto);  
         /*
          * EQUIPES DO PROJETO
          */
          
         $this->data['equipes'] = $this->projetos_model->getAllEquipesProjetoByEmpresaByProjeto($id_projeto);           
         /*
          * MARCOS DO PROJETO
          */
         $this->data['marcos'] = $this->projetos_model->getAllMarcosProjetoByEmpresaByProjeto($id_projeto);  
         /*
          * ARQUIVOS DO PROJETO
          */
         $this->data['arquivos'] = $this->projetos_model->getAllArquivosProjetoByEmpresaByProjeto($id_projeto);  
         /*
          * HISTÓRICOS DO PROJETO
          */
         $this->data['historicos'] = $this->projetos_model->getAllHistoricoProjetoByEmpresa($id_projeto);  
         /*
          * PARTES INTERESSADAS DO PROJETO
          */
         $this->data['partes'] = $this->projetos_model->getAllPArtesInteressadasProjetoByEmpresa($id_projeto);  
        
         /*
          * LOGS DO PROJETO
          */
         $this->data['logs'] = $this->projetos_model->getAllLogProjetoByEmpresa($id_projeto);  
         
         // SALVA O MÓDULO ATUAL do usuário
         $data_modulo = array('modulo_atual' => 4, 'menu_atual' => 85);
         $this->owner_model->updateModuloAtual($usuario, $data_modulo);
        
         /*
          * SALVA O LOG
          */
         $date_hoje = date('Y-m-d H:i:s');
            $usuario = $this->session->userdata('user_id');
            $empresa = $this->session->userdata('empresa');
            $ip = $_SERVER["REMOTE_ADDR"];
            $usuario_dados = $this->site->geUserByID($usuario);
            $nome = $usuario_dados->first_name;
            $logdata = array('date' => date('Y-m-d H:i:s'), 
                'type' => 'PROJECT', 
                'description' => 'O usuário '.$nome. ', Acessou o Cadastro do projeto '.$id_projeto,  
                'userid' => $this->session->userdata('user_id'), 
                'ip_address' => $_SERVER["REMOTE_ADDR"],
                'tabela' => 'sig_projetos',
                'row' => $id_projeto,
                'depois' => '', 
                'modulo' => 'project',
                'funcao' => 'project/editarProjeto',  
                'empresa' => $this->session->userdata('empresa'));
           
               $this->owner_model->addLog($logdata);  
         
         //$this->page_construct_project('project/projetos/editProjeto', $meta, $this->data);     
               $this->page_construct_project_collapse('project/projetos/editProjeto', $meta, $this->data);
    }
            
    }
    
    /*
     * MARCOS DO PROJETO
     */
     public function adicionarMArcoProjeto()
    {
        $this->sma->checkPermissions();                      
        
         $data_prevista = $this->input->post('data_prevista');
         $descricao = $this->input->post('descricao');
         $id_projeto = $this->input->post('idprojeto');
         $empresa = $this->session->userdata('empresa');    
         
            $data_projeto = array(
                'aba' => 3
            );
            $this->projetos_model->updateProjeto($id_projeto, $data_projeto); 
         
            /*
             * ADICIONA O USUÁRIO AO PROJETO
             */  
                $data_marco = array(
                   'descricao' => $descricao,
                   'data_prevista' => $data_prevista,
                   'projetos_id' => $id_projeto,
                   'empresa_id' => $empresa
                );
                $this->owner_model->addCadastro('projetos_marcos', $data_marco);     
             
         $this->session->set_flashdata('message', lang("Cadastro realizado com Sucesso!!!"));
          redirect("project/editarProjeto/$id_projeto");
      //  echo "<script>history.go(-1)</script>";       
    }   
    
    public function removerMarcoProjeto($id_cadastro)
    {
        $this->sma->checkPermissions();                      
        
         $this->projetos_model->deleteMarcosProjeto($id_cadastro); 
         
               
         $this->session->set_flashdata('message', lang("Cadastro removido com Sucesso!!!"));
            //redirect("project/index");
        echo "<script>history.go(-1)</script>";       
    }
    // FIM MARCOS DO PROJETO
    
    /*
     * EQUIPE DO PROJETO
     */
     public function adicionarEquipeProjeto()
    {
        $this->sma->checkPermissions();                      
        
         $date_hoje = date('Y-m-d H:i:s');    
         $usuario = $this->session->userdata('user_id');
         $funcao = $this->input->post('funcao');
         $usuario_equipe = $this->input->post('usuario_responsavel_equipe');
         $papel_responsabilidade = $this->input->post('papel_responsabilidade');
         $id_projeto = $this->input->post('idprojeto');
         $empresa = $this->session->userdata('empresa');    
         
         //MUDA A ABA SELECIONADA
        $data_projeto = array(
            'aba' => 2
        );
        $this->projetos_model->updateProjeto($id_projeto, $data_projeto); 

        /*
         * ADICIONA O USUÁRIO AO PROJETO
         */  
            $data_marco = array(
               'funcao' => $funcao,
               'user_responsavel' => $usuario_equipe,
               'descricao' => $papel_responsabilidade,
               'projeto_id' => $id_projeto,
               'empresa_id' => $empresa,
               'usuario_cadastro' => $usuario,
               'data_cadastro' => $date_hoje
            );
            $this->owner_model->addCadastro('projetos_equipes', $data_marco);     
        
                
       /*
        * SALVA O LOG
        */
        $date_hoje = date('Y-m-d H:i:s');
        $usuario = $this->session->userdata('user_id');
        $empresa = $this->session->userdata('empresa');
        $ip = $_SERVER["REMOTE_ADDR"];
        $usuario_dados = $this->site->geUserByID($usuario);
        $nome = $usuario_dados->first_name;
        $logdata = array('date' => date('Y-m-d H:i:s'), 
            'type' => 'PROJECT', 
            'description' => 'O usuário '.$nome. ', Cadastrou um membro a equipe do projeto '.$id_projeto,  
            'userid' => $this->session->userdata('user_id'), 
            'ip_address' => $_SERVER["REMOTE_ADDR"],
            'tabela' => 'sig_projetos_equipes',
            'row' => $id_projeto,
            'depois' => json_encode($data_marco), 
            'modulo' => 'project',
            'funcao' => 'project/adicionarEquipeProjeto',  
            'empresa' => $this->session->userdata('empresa'));
        $this->owner_model->addLog($logdata);          
                
                
             
         $this->session->set_flashdata('message', lang("Cadastro realizado com Sucesso!!!"));
         redirect("project/editarProjeto/$id_projeto");
       // echo "<script>history.go(-1)</script>";       
    }   
    
     public function removerEquipeProjeto($id_cadastro)
    {
        $this->sma->checkPermissions();                      
        
         $this->projetos_model->deleteEquipeProjeto($id_cadastro); 
         
       /*
        * SALVA O LOG
        */
        $date_hoje = date('Y-m-d H:i:s');
        $usuario = $this->session->userdata('user_id');
        $empresa = $this->session->userdata('empresa');
        $ip = $_SERVER["REMOTE_ADDR"];
        $usuario_dados = $this->site->geUserByID($usuario);
        $nome = $usuario_dados->first_name;
        $projeto_atual = $usuario_dados->projeto_atual;
        $logdata = array('date' => date('Y-m-d H:i:s'), 
            'type' => 'DELETE', 
            'description' => 'O usuário '.$nome. ', removeu o cadastro de membro da equipe do projeto '.$projeto_atual,  
            'userid' => $this->session->userdata('user_id'), 
            'ip_address' => $_SERVER["REMOTE_ADDR"],
            'tabela' => 'sig_projetos_equipes',
            'row' => $id_cadastro,
            'depois' => '', 
            'modulo' => 'project',
            'funcao' => 'project/removerEquipeProjeto',  
            'empresa' => $this->session->userdata('empresa'));
        $this->owner_model->addLog($logdata);          
        
               
         $this->session->set_flashdata('message', lang("Cadastro removido com Sucesso!!!"));
            //redirect("project/index");
        echo "<script>history.go(-1)</script>";       
    }
    /* FIM EQUIPE DO PROJETO */
    
    /*
     * ACESSO PROJETO
     */
     public function adicionarAcessoProjeto()
    {
        $this->sma->checkPermissions();                      
        
         $id_projeto = $this->input->post('idprojeto');
         $usuario = $this->input->post('usuario_acesso');
             
         
           //$this->site->getUser($this->session->userdata('user_id'));
            $data_projeto = array(
                'aba' => 6
            );
          
            $this->projetos_model->updateProjeto($id_projeto, $data_projeto); 
         
         /*
             * ADICIONA O USUÁRIO AO PROJETO
             */  
                $data_acesso = array(
                   'users' => $usuario,
                   'projeto' => $id_projeto,
                   'criador' => 0
                );
                $this->owner_model->addCadastro('users_projetos', $data_acesso);     
             
                
                
         $this->session->set_flashdata('message', lang("Cadastro realizado com Sucesso!!!"));
          redirect("project/editarProjeto/$id_projeto");
        //echo "<script>history.go(-1)</script>";       
    }   
    
     public function removerAcessoProjeto($id_cadastro)
    {
        $this->sma->checkPermissions();                      
        
         $this->projetos_model->deleteAcessoProjeto($id_cadastro); 
         
               
         $this->session->set_flashdata('message', lang("Arquivo removido com Sucesso!!!"));
            //redirect("project/index");
        echo "<script>history.go(-1)</script>";       
    }
    // FIM ACESSO PROJETO
    
    /*
     * ARQUIVOS PROJETOS
     */
     public function adiciona_arquivos_projetos()
    {
        $ip = $_SERVER["REMOTE_ADDR"];
        $date_hoje = date('Y-m-d H:i:s');    
        $usuario = $this->session->userdata('user_id');
        $empresa = $this->session->userdata('empresa');    
        
        $id_projeto = $this->input->post('idprojeto');
        
         //MUDA A ABA SELECIONADA
            $data_projeto = array(
                'aba' => 4
            );
            $this->projetos_model->updateProjeto($id_projeto, $data_projeto);   
            
        $descricao_arquivo = $this->input->post('descricao_arquivo');
        if(!$descricao_arquivo){
            $this->session->set_flashdata('error', lang("Informe a Descrição do Arquivo."));
            echo "<script>history.go(-1)</script>";
            exit;
        }
        
        
         $data_arquivos = array(
                   'projeto_id' => $id_projeto,
                   'descricao' => $descricao_arquivo, 
                   //'anexo' => $funcao,
                   'empresa_id' => $empresa,
                   'usuario_cadastro' => $usuario,
                   'data_cadastro' => $date_hoje,
                   'ip' => $ip
                );
               
         
         if ($_FILES['document']['size'] > 0) {
            $this->load->library('upload');
            $config['upload_path'] = 'assets/uploads/projetos/arquivos/';
            $config['allowed_types'] = $this->digital_file_types;
            $config['max_size'] = $this->allowed_file_size;
            $config['overwrite'] = false;
            $config['encrypt_name'] = true;
            $this->upload->initialize($config);
            if (!$this->upload->do_upload('document')) {
                $error = $this->upload->display_errors();
                $this->session->set_flashdata('error', $error);
                redirect($_SERVER["HTTP_REFERER"]);
            }
            $photo = $this->upload->file_name;
            $data_arquivos['anexo'] = $photo;
        }else{
            $this->session->set_flashdata('error', lang("Selecione um arquivo."));
            echo "<script>history.go(-1)</script>";
            exit;
        }
       //print_r($data_arquivos);exit;
        
        //  $this->atas_model->AdicionarArquivoAcao($data_arquivo);
        $this->owner_model->addCadastro('projetos_arquivos', $data_arquivos);     
        
        $this->session->set_flashdata('message', lang("Arquivo Cadastrado com Sucesso!!!"));
         redirect("project/editarProjeto/$id_projeto");
        //echo "<script>history.go(-1)</script>";
         exit;
        
            
     }
     
    public function remove_arquivo_projeto($id_cadastro)
    {
         $this->projetos_model->deleteArquivoProjeto($id_cadastro); 
            
             $this->session->set_flashdata('message', lang("Arquivo apagado com Sucesso!!!"));   
             echo "<script>history.go(-1)</script>";
                exit; 
           
    } 
    // FIM ARQUIVOS PROJETO
    
    /*
     * HISTORICO PROJETOS
     */
     public function adiciona_historico_projetos()
    {
        $ip = $_SERVER["REMOTE_ADDR"];
        $date_hoje = date('Y-m-d H:i:s');    
        $usuario = $this->session->userdata('user_id');
        $empresa = $this->session->userdata('empresa');    
        
        $id_projeto = $this->input->post('idprojeto');
        
         //MUDA A ABA SELECIONADA
            $data_projeto = array(
                'aba' => 7
            );
            $this->projetos_model->updateProjeto($id_projeto, $data_projeto);   
            
        $observacao_historico = $this->input->post('observacao_historico');
        $assunto = $this->input->post('titulo');
        
        if(!$observacao_historico){
            $this->session->set_flashdata('error', lang("Informe o Histórico"));
            echo "<script>history.go(-1)</script>";
            exit;
        }
        
        
         $data_historico = array(
                   'projetos_id' => $id_projeto,
                   'historico' => $observacao_historico, 
                   'titulo' => $assunto,
                   'empresa_id' => $empresa,
                   'usuario' => $usuario,
                   'data_envio' => $date_hoje,
                   'ip' => $ip
                );
        
       //print_r($data_historico);exit;
        
        //  $this->atas_model->AdicionarArquivoAcao($data_arquivo);
        $this->owner_model->addCadastro('projetos_historico', $data_historico);     
        
        $this->session->set_flashdata('message', lang("Registro Cadastrado com Sucesso!!!"));
         redirect("project/editarProjeto/$id_projeto");
        //echo "<script>history.go(-1)</script>";
         exit;
        
            
     }
    // FIM HISTORICO PROJETO
   
    /*
     * PARTE INTERESSADA PROJETO
     */
     public function adicionarParteInteressadaProjeto()
    {
        $this->sma->checkPermissions();                      
        
        $ip = $_SERVER["REMOTE_ADDR"];
        $date_hoje = date('Y-m-d H:i:s');    
        $usuario = $this->session->userdata('user_id');
        $empresa = $this->session->userdata('empresa');    

        $id_projeto = $this->input->post('idprojeto');
        $usuario_pi = $this->input->post('usuario_interessado');
        $descricao_pi = $this->input->post('descricao');
        $nivel_engajamento = $this->input->post('tipo_engajamento');
        $status_report = $this->input->post('status_report');
        $estrategia = $this->input->post('estrategia_observacao');

        //$this->site->getUser($this->session->userdata('user_id'));
        $data_projeto = array(
            'aba' => 5
        );
        $this->projetos_model->updateProjeto($id_projeto, $data_projeto); 
         
         /*
         * ADICIONA O USUÁRIO AO PROJETO
         */  
           $data_parte_interessada = array(
               'projetos_id' => $id_projeto,

               'usuario_interessado' => $usuario_pi, 
               'descricao' => $descricao_pi,
               'tipo_engajamento' => $nivel_engajamento,
               'status_report' => $status_report,
               'estrategia_observacao' => $estrategia,

               'empresa_id' => $empresa,
               'usuario' => $usuario,
               'data_envio' => $date_hoje,
               'ip' => $ip
            );
           //print_r($data_parte_interessada); exit;
            $this->owner_model->addCadastro('projetos_partes_interessadas`', $data_parte_interessada);     
             
         $this->session->set_flashdata('message', lang("Cadastro realizado com Sucesso!!!"));
          redirect("project/editarProjeto/$id_projeto");
        //echo "<script>history.go(-1)</script>";       
    }   
    
     public function removerParteInteressadaProjeto($id_cadastro)
    {
        $this->sma->checkPermissions();                      
        
         $this->projetos_model->removeParteInteressadaProjeto($id_cadastro); 
         
               
         $this->session->set_flashdata('message', lang("Parte Interessada removida com Sucesso!!!"));
            //redirect("project/index");
        echo "<script>history.go(-1)</script>";       
    }
    // FIM PARTE INTERESSADA PROJETO 
     
    
    /********************************************************* ALTERAÇÃO DE ESCOPO E CRONOGRAMA***********************************/
     public function alterarDataInicial()
    {
        $date_cadastro = date('Y-m-d H:i:s');                           
        
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
       
        $usuario = $this->session->userdata('user_id');                     
        $users_dados = $this->site->geUserByID($usuario);
        $modulo_atual = $users_dados->modulo_atual;
        $menu_atual = $users_dados->modulo_atual;
        $nome = $users_dados->first_name;

        $modulo_atual_id = $users_dados->modulo_atual;
        $empresa = $users_dados->empresa_id;

         $empresa = $this->owner_model->getEmpresaById($empresa);
         $nome_empresa = $empresa->razaoSocial;
        // registra o log de movimentação
         $tipo = "PROJECT";
         $texto = "O usuário $nome, da empresa $nome_empresa acessou a tela de  alterar data inicial do projeto, Menu ID: $menu";
         $tabela_log = "$tabela_nome";
         $row = "";
         $depois = "";
         $modulo = "project";
         $funcao = "project/novoCadastroBasico";
         $this->registraLog($tipo, $texto, $tabela_log, $row, $depois, $modulo, $funcao);
        
        //$this->load->view($this->theme . 'projetos/documentacao/add', $this->data);
        $this->load->view($this->theme . 'project/projetos/editarDataInicio', $this->data);
           

            
    }
    
    
     
    /*************************************************************************************************************
     ************************************ FIM CADASTRO PROJETO ****************************************************
     ************************************************************************************************************/
    
    /************************************************************************************************************
     ****************************************** CADASTROS ATAS **********************************************
     ************************************************************************************************************/
    public function atas($menu)
    {
     
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
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
                'description' => 'Cadastro de uma nova ATA',  
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
            
                // SALVA O MÓDULO ATUAL do usuário
                 $usuario = $this->session->userdata('user_id');    
                 $data_modulo = array('modulo_atual' => 4, 'menu_atual' => $menu);
                 $this->owner_model->updateModuloAtual($usuario, $data_modulo);

                // registra o log de movimentação

                $date_hoje = date('Y-m-d H:i:s');    
                $usuario = $this->session->userdata('user_id');
                $empresa = $this->session->userdata('empresa');
                $ip = $_SERVER["REMOTE_ADDR"];

                $logdata = array('date' => date('Y-m-d H:i:s'), 
                    'type' => 'ACESSO', 
                    'description' => "Acessou o menu $menu do Módulo OWNER",  
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
            $this->data['atas'] = $this->projetos_model->getAllAtasByProjetoAtual();
            //$this->data['campos'] = $this->owner_model->getAllCamposTablesLista($tabela);
            //$this->data['cadastrosHabilitados'] = $this->owner_model->getAllCamposTablesCadastro($tabela);
            
           // $this->data['botoes_menu'] = $this->owner_model->getAllBotoesByTabela($tabela);
            $this->page_construct_project('project/cadastro_basico_modelo/atas/index', $meta, $this->data);
           // $this->page_construct_user('owner/empresas/index', $meta, $this->data);
         }
         
         
    }
    
    public function novaAta($id_plano, $tipo_ata) {


        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        $this->form_validation->set_rules('id_cadastro', lang("id_cadastro"), 'required');
        $this->form_validation->set_rules('dateAta', lang("Data da ATA"), 'required');
        $this->form_validation->set_rules('hora_inicio', lang("Hora de Início"), 'required');
        $this->form_validation->set_rules('hora_fim', lang("Hora Término"), 'required');
        $this->form_validation->set_rules('local', lang("Local da ATA"), 'required');
        $this->form_validation->set_rules('tipo', lang("Tipo da ATA"), 'required');
        $this->form_validation->set_rules('pauta', lang("Pauta"), 'required');
        // $this->form_validation->set_rules('participantes', lang("Participantes"), 'required');
        $this->form_validation->set_rules('nome_elaboracao', lang("Elaboração a Pauta"), 'required');
        
        if ($this->form_validation->run() == true) {
            
            $funcao = $this->input->post('funcao');
            $menu_id = $this->input->post('menu_id');
            $status = 'ATIVO';
            $usuario = $this->session->userdata('user_id');
            $empresa = $this->session->userdata('empresa');
            $date_cadastro = date('Y-m-d H:i:s');

             
            $projeto = $this->input->post('projeto');
            $dateAta = $this->input->post('dateAta'); 
            $hora_inicio= $this->input->post('hora_inicio'); 
            $hora_termino = $this->input->post('hora_fim'); 
            $tipo = $this->input->post('tipo'); 
            $pauta = $this->input->post('pauta'); 
            $assunto = trim($this->input->post('assunto')); 
            //$convocacao = $this->input->post('convocacao');
            $texto_convocacao = $this->input->post('texto_convocacao');
            $nome_elaboracao = $this->input->post('nome_elaboracao');
            $local = $this->input->post('local');
            $usuario_ata = $this->input->post('usuarios_vinculo');
            $participantes = $this->input->post('participantes');
            $note = $this->input->post('note');
             
            $tipo_ata = $this->input->post('tipo_ata');
            $id_plano = $this->input->post('idplano');
            
           
            
            /*
             * TREINAMENTO
             */
            //$facilitadores = $this->input->post('facilitador');
            //$reacao = $this->input->post('reacao');
            //$aprendizagem = $this->input->post('aprendizagem');
            //$desempenho = $this->input->post('desempenho');
            
            
            if($tipo == "REUNIÃO CONTÍNUA"){
                $evento = $this->input->post('evento');
            }
         
            $data_criacao = $date_cadastro;
            
            
            if($tipo == 'AVULSA'){
                $avulsa = 'SIM';
            }else{
                $avulsa = 'NÃO';
            }
            
            $dados_sequencial = $this->atas_model->getSequencialAta();
             $valor_sequencial = $dados_sequencial->sequencial;
            
             if($valor_sequencial == null){
                 $sequencia = 1;
             }else{
                 $sequencia = $valor_sequencial;
             }
            
             
              if($tipo_ata == 2){
                 $data_ata = array(
                 'projetos' => $projeto,    
                'data_ata' => $dateAta,
                'hora_inicio' => $hora_inicio,
                'hora_termino' => $hora_termino,
                'tipo' => $tipo,
                'pauta' => $pauta,
                'assunto' => $assunto,
                //'participantes' => $participantes ,
                'responsavel_elaboracao' => $nome_elaboracao,
                'local' => $local,
                'obs' => $note,
                'data_criacao' => $data_criacao,   
                'usuario_criacao' => $usuario,
                'avulsa' => $avulsa,
                'evento' => $evento,
                //'convocacao' => $convocacao,
                'texto_convocacao' => $texto_convocacao,
                'status' => 0,
                'empresa' => $empresa,
                'sequencia' => $sequencia,
                'networking' => 0,
                'plano_acao' => $id_plano     
            );
             }else{
            //$this->site->getUser($this->session->userdata('user_id'));
            $data_ata = array(
                'projetos' => $projeto,
                'data_ata' => $dateAta,
                'hora_inicio' => $hora_inicio,
                'hora_termino' => $hora_termino,
                'tipo' => $tipo,
                'pauta' => $pauta,
                'assunto' => $assunto,
                //'participantes' => $participantes ,
                'responsavel_elaboracao' => $nome_elaboracao,
                'local' => $local,
                'obs' => $note,
                'data_criacao' => $data_criacao,   
                'usuario_criacao' => $usuario,
                'avulsa' => $avulsa,
                'evento' => $evento,
                //'convocacao' => $convocacao,
                'texto_convocacao' => $texto_convocacao,
                'status' => 0,
                'networking' => 0,
                'empresa' => $empresa,
                'sequencia' => $sequencia
            );
           // print_r($data_ata); exit;
             }
            
            if ($_FILES['document']['size'] > 0) {
                $this->load->library('upload');
                $config['upload_path'] = $this->upload_path;
                $config['allowed_types'] = $this->digital_file_types;
                $config['max_size'] = $this->allowed_file_size;
                $config['overwrite'] = false;
                $config['encrypt_name'] = true;
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('document')) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect($_SERVER["HTTP_REFERER"]);
                }
                $photo = $this->upload->file_name;
                $data_ata['anexo'] = $photo;
            }
            
            
           // exit;
            
             $id_ata = $this->atas_model->addAtas($data_ata, $usuario_ata, $participantes);
             
            
                 if($convocacao == 'SIM'){
                       foreach ($participantes as $participante) {
                        $id_usuario = $participante;
                        $ata = $id_ata;
                        
                        $data_historico_convocacao = array(
                            'ata' => $ata,
                            'usuario' => $id_usuario,
                            'data_convocacao' => $date_cadastro,
                            'status' => "",
                            'responsavel' => $usuario,
                            'texto' => $texto_convocacao,
                            'tipo' => "Convocação de Reunião"
                        );
                        $id_convocacao = $this->atas_model->addHistorico_convocacao($data_historico_convocacao);
                       // $this->ion_auth->emailAtaConvocacao($participante, $id_ata, $id_convocacao);
                       }
                }
             
                
                /*
                 * SE FOR TIPO TREINAMENTO - SALVA OS FACILITADORES
                 
                if($tipo == 'TREINAMENTO'){
                    
                     foreach ($facilitadores as $facilitador) {
                        $id_usuario = $facilitador;
                        $ata = $id_ata;
                        
                        $data_facilitadores = array(
                            'ata' => $ata,
                            'usuario' => $id_usuario
                        );
                        
                        $this->atas_model->add_facilitador_ata($data_facilitadores);
                     }       
                    
                    
                }
                */
            
            

            $date_hoje = date('Y-m-d H:i:s');
            $usuario = $this->session->userdata('user_id');
            $empresa = $this->session->userdata('empresa');
            $ip = $_SERVER["REMOTE_ADDR"];

            $logdata = array('date' => date('Y-m-d H:i:s'),
                'type' => 'INSERT',
                'description' => 'Cadastro de uma nova ATA, ID: '.$id_ata,
                'userid' => $this->session->userdata('user_id'),
                'ip_address' => $_SERVER["REMOTE_ADDR"],
                'tabela' => 'sig_atas',
                'row' => $id_ata,
                'depois' => json_encode($data_ata),
                'modulo' => 'project',
                'funcao' => 'project/novaAta',
                'empresa' => $this->session->userdata('empresa'));

            $this->owner_model->addLog($logdata);
            // exit;

            $this->session->set_flashdata('message', lang("Cadastro realizado com Sucesso!!!"));
              if($tipo_ata == 2){
                redirect("atas/plano_acao/$id_ata/$tipo_ata");
            }else{
                redirect("atas/plano_acao/$id_ata");
            }
           // redirect("project/atas/$tabela_id/$menu_id");
        } else {

           $this->data['tipo_ata'] = $tipo_ata;
            $this->data['id_plano'] = $id_plano;
            
            $usuario = $this->session->userdata('user_id');
            $projetos_usuario = $this->site->getProjetoAtualByID_completo($usuario);
            $this->data['eventos'] = $this->projetos_model->getAllEventosItemEventoByProjeto($projetos_usuario->projeto_atual);   
            //$this->data['users'] = $this->owner_model->getDadosTablesUsers(); //
            $this->data['users'] = $this->atas_model->getAllUsersSetores(); 
            //$this->load->view($this->theme . 'projetos/documentacao/add', $this->data);

            $this->page_construct_project('project/cadastro_basico_modelo/atas/cadastro', $meta, $this->data);
        }
    }

    
    /************************************************************************************************************
     ****************************************** PLANO DE AÇÃO **********************************************
     ************************************************************************************************************/
    public function plano_acao($menu)
    {
      $this->sma->checkPermissions();
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        
        $this->form_validation->set_rules('id_cadastro', lang("id_cadastro"), 'required');
         
         if ($this->form_validation->run() == true) {
           
             $tabela_id = $this->input->post('tabela_id');
             $tabela_nome = $this->input->post('tabela_nome');
             $id_pa = $this->input->post('id');
             
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
            
              
              $id_cadastro =  $this->owner_model->updateCadastro($id_pa, $tabela_sig, $data_campos_cadastro);
              
            $date_hoje = date('Y-m-d H:i:s');    
            $usuario = $this->session->userdata('user_id');
            $empresa = $this->session->userdata('empresa');
            $ip = $_SERVER["REMOTE_ADDR"];

            $logdata = array('date' => date('Y-m-d H:i:s'), 
                'type' => 'UPDATE', 
                'description' => 'Update Plano de Ação ID:  '.$id_pa,  
                'userid' => $this->session->userdata('user_id'), 
                'ip_address' => $_SERVER["REMOTE_ADDR"],
                'tabela' => $tabela_nome,
                'row' => $id_pa,
                'depois' => json_encode($data_campos_cadastro), 
                'modulo' => 'project',
                'funcao' => 'project/plano_acao',  
                'empresa' => $this->session->userdata('empresa'));
           
               $this->owner_model->addLog($logdata);  
           // exit;
            
            $this->session->set_flashdata('message', lang("Cadastro atualizado com Sucesso!!!"));
            redirect("project/plano_acao_detalhes/$tabela_id/55/$id_pa");
            
         }else{
             
            // SALVA O MÓDULO ATUAL do usuário
            $usuario = $this->session->userdata('user_id');    
            $data_modulo = array('modulo_atual' => 4, 'menu_atual' => $menu);
            $this->owner_model->updateModuloAtual($usuario, $data_modulo);

            // registra o log de movimentação
            $date_hoje = date('Y-m-d H:i:s');    
            $usuario = $this->session->userdata('user_id');
            $empresa = $this->session->userdata('empresa');
            $ip = $_SERVER["REMOTE_ADDR"];

            $logdata = array('date' => date('Y-m-d H:i:s'), 
                'type' => 'ACESSO', 
                'description' => "Acessou o menu $menu do Módulo OWNER",  
                'userid' => $this->session->userdata('user_id'), 
                'ip_address' => $_SERVER["REMOTE_ADDR"],
                'tabela' => '',
                'row' => '',
                'depois' => '', 
                'modulo' => 'owner',
                'funcao' => 'owner/cadastro',  
                'empresa' => $this->session->userdata('empresa'));
                $this->owner_model->addLog($logdata); 
            
            $this->data['planos_acao'] = $this->projetos_model->getAllPlanoAcaoByProjetoAtual();
           
            $this->page_construct_project('project/cadastro_basico_modelo/plano_acao/index', $meta, $this->data);
           // $this->page_construct_user('owner/empresas/index', $meta, $this->data);
         }
         
         
    }
    
    public function novoPlano() {

         $this->sma->checkPermissions();
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

         $this->form_validation->set_rules('id_cadastro', lang("id_cadastro"), 'required');
        
        if ($this->form_validation->run() == true) {
            
            
            $projetos = $this->projetos_model->getProjetoAtualByID_completo();
            $id_projeto = $projetos->id;

            $date_hoje = date('Y-m-d H:i:s');    
            $usuario = $this->session->userdata('user_id');
            $empresa = $this->session->userdata('empresa');   
            $assunto = $this->input->post('assunto');
            $objetivos = $this->input->post('objetivos');
            $responsavel = $this->input->post('responsavel');
            $responsavel_aprovacao = $this->input->post('responsavel_aprovacao');
            $data_pa = $this->input->post('periodo_de');
            $periodo_ate = $this->input->post('periodo_ate');
            
            $dados_sequencial = $this->projetos_model->getSequencialPlanosAcao();
            $valor_sequencial = $dados_sequencial->sequencial;
            
             if($valor_sequencial == null){
                 $sequencia = 1;
             }else{
                 $sequencia = $valor_sequencial;
             }
           
            //echo $assunto; exit;
            $data_modulo = array(
                'assunto' => $assunto,
                'empresa' => $empresa,
                'usuario' => $usuario,
                'projeto' => $id_projeto,
                'objetivos' => $objetivos,
                'responsavel' => $responsavel,
                'responsavel_aprovacao' => $responsavel_aprovacao,
                'data_pa' => $data_pa,
                'sequencial' => $sequencia,
                'data_termino_previsto' => $periodo_ate,
                'networking' => 0,
                'status' => 0
            );
            /*
             $data_modulo = array(
                'assunto' => $assunto,
                'empresa' => $empresa,
                'usuario' => $usuario,
                'objetivos' => $objetivos,
                'responsavel' => $responsavel,
                'responsavel_aprovacao' => $responsavel_aprovacao,
                'data_pa' => $data_pa,
                'sequencial' => $sequencia,
                'data_termino_previsto' => $periodo_ate,
                'networking' => 1,
                'status' => 0
            );
             * 
             */
            
           // print_r($data_modulo); exit;
            $id_cadastro =  $this->owner_model->addCadastro('plano_acao', $data_modulo);
              
            //CADASTRAR UMA NOVA CATEGORIA
            $data_categoria = array(
                'pa' => $id_cadastro,
                'empresa' => $empresa,
                'usuario' => $usuario,
                'descricao' => 'Geral'
            );
           $this->networking_model->addCategoria_PlanoAcao($data_categoria);

            $date_hoje = date('Y-m-d H:i:s');
            $ip = $_SERVER["REMOTE_ADDR"];

            $logdata = array('date' => date('Y-m-d H:i:s'),
                'type' => 'INSERT',
                'description' => 'Cadastro de um novo Plano de Ação, ID: '.$id_cadastro,
                'userid' => $this->session->userdata('user_id'),
                'ip_address' => $_SERVER["REMOTE_ADDR"],
                'tabela' => 'sig_plano_acao',
                'row' => $id_cadastro,
                'depois' => json_encode($data_modulo),
                'modulo' => 'project',
                'funcao' => 'project/novoPlano',
                'empresa' => $this->session->userdata('empresa'));

            $this->owner_model->addLog($logdata);
            // exit;

            $this->session->set_flashdata('message', lang("Cadastro realizado com Sucesso!!!"));
            redirect("project/plano_acao_detalhes/$id_cadastro");
        } else {

          
          $this->load->view($this->theme . 'project/cadastro_basico_modelo/plano_acao/novoPlano', $this->data);  
          //  $this->page_construct_project('project/cadastro_basico_modelo/plano_acao/plano', $meta, $this->data);
        }
    }
    
    
    public function plano_acao_detalhes($id_pa)
    {
     
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        
         $this->form_validation->set_rules('descricao', lang("Descrição"), 'required');
        $this->form_validation->set_rules('data_inicio', lang("Data Início e Término"), 'required');
        $this->form_validation->set_rules('evento', lang("Item do Evento"), 'required');
       // $this->form_validation->set_rules('responsavel', lang("Responsável"), 'required');
       
      
        
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        
       // echo 'aqui'. $this->input->post('id'); exit;
          if ($this->form_validation->run() == true) {
           
             
            $id_ata = $this->input->post('id');  
            $evento = $this->input->post('evento'); 
            
            $descricao = $this->input->post('descricao');
            $onde = $this->input->post('onde');
            $porque = $this->input->post('porque');
            $como = $this->input->post('como');
            $valor_custo = $this->input->post('valor_custo');
            if($valor_custo){
            $valor_custo = str_replace(',', '.', str_replace('.', '', $valor_custo));
            }
            $custo_descricao = trim($this->input->post('custo'));
            //$dataEntrega = $this->sma->fld(trim($this->input->post('dateEntrega'))); 
            //$this->input->post('dateEntrega');
            $responsaveis = $this->input->post('responsavel');
            $peso = $this->input->post('peso');
            
            $categoria_plano = $this->input->post('categoria');
            
            //$status = trim($this->input->post('status_plano')); 
            $date_cadastro = date('Y-m-d H:i:s');  
            $usuario = $this->session->userdata('user_id');
            $empresa = $this->session->userdata('empresa');
             
            $users_dados = $this->site->geUserByID($usuario);
            $modulo_atual_id = $users_dados->modulo_atual;
            $projeto_atual_id = $users_dados->projeto_atual; 
            
            //PERÍODO
            $data_inicio = $this->input->post('data_inicio');
            $data_termino = $this->input->post('data_termino');
            
            /*
            $periodo_acao = $this->input->post('periodo_acao');
           
            $evento_periodo_de = substr($periodo_acao, 0, 10);
             $partes_data_inicio = explode("/", $evento_periodo_de);
             $ano_de = $partes_data_inicio[2];
             $mes = $partes_data_inicio[1];
             $dia = $partes_data_inicio[0];
             $data_tratado_de = $ano_de.'-'.$mes.'-'.$dia;
            
             $evento_periodo_ate = substr($periodo_acao, 13, 24);
             $partes_data_fim = explode("/", $evento_periodo_ate);
             $anof = $partes_data_fim[2];
             $mesf = $partes_data_fim[1];
             $diaf = $partes_data_fim[0];
             $data_tratado_ate = $anof.'-'.$mesf.'-'.$diaf;
             * 
             */
             
             
             /*
              * VERIFICA SE A DATA DA AÇÃO ESTA DENTRO DO ITEM DE EVENTO SELECIONADO
              */
             $dados_item = $this->projetos_model->getItemEventoByID($evento);
             $inicio_fase = $dados_item->dt_inicio;
             $fim_fase = $dados_item->dt_fim;
             
           
            
             if($data_inicio < $inicio_fase){
                  $rData = explode("-", $inicio_fase);
                  $rData = $rData[2].'/'.$rData[1].'/'.$rData[0];
                 $this->session->set_flashdata('error', lang("A data de Início da ação, é menor que o início do Item do Evento selecionado! A data Início, não pode ser menor que :  $rData"));
                 echo "<script>alert('A data de Início da ação, é menor que o início do Item do Evento selecionado!')</script>";
                echo "<script>history.go(-1)</script>";
                exit;
                // echo 'A data de início é menor que a esperada';
             }else if($data_termino > $fim_fase){
                 $rData = explode("-", $fim_fase);
                  $rData = $rData[2].'/'.$rData[1].'/'.$rData[0];
                 $this->session->set_flashdata('error', lang("A data de Término da ação, é maior que o término do Item do Evento selecionado! A data Término, não pode ser maior que :  $rData"));
                 echo "<script>alert('A data de Término da ação, é maior que o término do Item do Evento selecionado!')</script>";
                 echo "<script>history.go(-1)</script>";
                exit;
                 // echo 'A data de Término é maior que a esperada : '.$data_tratado_ate .'>'. $fim_fase;
             }
            
           // exit;
            $dataInicio = $data_inicio; 
            $dataTermino = $data_termino;
            $horas_previstas = $this->input->post('horas_previstas');
                         
            
           
            $cont_r = 0;
            foreach ($responsaveis as $responsavel) {
             $cont_r++;   
            }
            if($cont_r == 0){
            $this->session->set_flashdata('error', lang("Selecione um responsável pela ação!!!"));
            echo "<script>alert('Selecione um responsável pela ação!')</script>";
            echo "<script>history.go(-1)</script>";
            exit;
            }
            
            /*
             * APLICA A REGRA AS AÇÕES COM VINCULOS
             */
            $acao_vinculo = $this->input->post('acoes_vinculo');
            $tipo_vinculo = $this->input->post('tipo_vinculo');
            
            
           
           
             if($acao_vinculo){
                 if(!$tipo_vinculo){
                    $this->session->set_flashdata('error', lang("Selecione o Tipo de Vínculo!!!"));
                    echo "<script>alert('Selecione o Tipo de Vínculo!')</script>";
                    echo "<script>history.go(-1)</script>";
                    exit;
                 }else{
                      //le as ações vinculadas selecionadas
                     
                         $dados_acao = $this->atas_model->getPlanoByID($acao_vinculo);
                         $inicio = $dados_acao->data_entrega_demanda;
                         $fim_v = $dados_acao->data_termino;   
                         
                         if($tipo_vinculo == 'II'){
                            if($dataInicio != $inicio){
                                $this->session->set_flashdata('error', lang("Para manter o vínculo da ação, a data de início da ação deve iniciar na mesma data de início da ação vinculada!!"));
                                echo "<script>alert('Para manter o vínculo da ação, a data de início da ação deve iniciar na mesma data de início da ação vinculada!')</script>";    
                                echo "<script>history.go(-1)</script>";
                                    exit;
                             }
                         }else if($tipo_vinculo == 'IF'){
                             
                             if($dataInicio < $fim_v){
                                $this->session->set_flashdata('error', lang("Para manter o vínculo da ação, a data de início da ação deve iniciar após a data de término da ação vinculada!!"));
                                echo "<script>alert('Para manter o vínculo da ação, a data de início da ação deve iniciar após a data de término da ação vinculada!')</script>";    
                                    echo "<script>history.go(-1)</script>";
                                    exit;
                             }
                         
                         }
                         
                        
                 }
            }
            //FIM VINCULO
            
            $dados_plano_acao = $this->atas_model->getPlanoAcaoByID($id_ata);
            $status_plano = $dados_plano_acao->status;
         
            if($status_plano == 1){
                $status_acao = 'PENDENTE';
                
            }else{
                $status_acao = 'ABERTO';
                
            }
            
            
             foreach ($responsaveis as $responsavel) {
             
                
             $dados_responsavel = $this->atas_model->getUserSetorBYid($responsavel);
             $setor_responsavel = $dados_responsavel->setores_id;
             $id_responsavel = $dados_responsavel->users_id;
                
             $dados_sequencial = $this->atas_model->getSequencialPlanosEmpresa();
             $valor_sequencial = $dados_sequencial->sequencial;
            
             if($valor_sequencial == null){
                 $sequencia = 1;
             }else{
                 $sequencia = $valor_sequencial;
             }
           
             
             $data_plano = array(
                'idplano' => $id_ata,
                'descricao' => $descricao,
                'onde' => $onde,
                'como' => $como,
                'porque' => $porque,
                'descricao' => $descricao,
                'custo' => $custo_descricao,
                'valor_custo' => $valor_custo,
                'data_entrega_demanda' => $dataInicio, 
                'data_termino' => $dataTermino,
                'horas_previstas' => $horas_previstas,
                'responsavel' => $id_responsavel,
                'setor' => $setor_responsavel,  
                'status' => $status_acao,
                'data_elaboracao' => $date_cadastro,   
                'responsavel_elaboracao' => $usuario,
                'eventos' => $evento,
                'status_tipo' => 1,
                'categoria_plano' => $categoria_plano,
                'sequencial' => $sequencia,
                'empresa' => $empresa,
                'peso' => $peso,
                'projeto' => $projeto_atual_id
           );  
            
          //print_r($data_plano); exit;
            
             if ($_FILES['document']['size'] > 0) {
                $this->load->library('upload');
                $config['upload_path'] = $this->upload_path;
                $config['allowed_types'] = $this->digital_file_types;
                $config['max_size'] = $this->allowed_file_size;
                $config['overwrite'] = false;
                $config['encrypt_name'] = true;
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('document')) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect($_SERVER["HTTP_REFERER"]);
                }
                $photo = $this->upload->file_name;
                $data_plano['anexo'] = $photo;
            }
            
                        
           $id_acao = $this->atas_model->add_planoAcao($data_plano,$acao_vinculo,$tipo_vinculo,$id_responsavel);
           
           
           
           
           /***********************************************************************************************
            ********************** L O G     A Ç Ã O ****************************************************** 
            ***********************************************************************************************/
           $data_log = array(
                'idplano' => $id_acao,
                'data_registro' => $date_cadastro,
                'usuario' => $usuario,
                'descricao' => "Ação Criada com sucesso",
                'empresa' => $empresa
              );
            $this->atas_model->add_logPlano($data_log);
           
           // LOG GERAL
            $date_hoje = date('Y-m-d H:i:s');
            $usuario = $this->session->userdata('user_id');
            $empresa = $this->session->userdata('empresa');
            $ip = $_SERVER["REMOTE_ADDR"];

            $logdata = array('date' => date('Y-m-d H:i:s'),
                'type' => 'INSERT',
                'description' => 'Cadastro de uma nova Ação, ID: '.$id_acao,
                'userid' => $this->session->userdata('user_id'),
                'ip_address' => $_SERVER["REMOTE_ADDR"],
                'tabela' => 'sig_planos',
                'row' => $id_acao,
                'depois' => json_encode($data_plano),
                'modulo' => 'project',
                'funcao' => 'project/plano_acao_detalhes',
                'empresa' => $this->session->userdata('empresa'));
            $this->owner_model->addLog($logdata);
           
            
                
            if($status_plano == 1){
                 
                 $envia_email = 1;
                 // REGISTRA A NOTIFICAÇÃO
                    $users_dados = $this->site->geUserByID($id_responsavel);
                    $nome_usuario = $users_dados->first_name;

                    $data_notificacao = array(
                        'id_from' => $usuario,
                        'id_to' => $id_responsavel,
                        'title' => "Nova Ação",
                        'text' => "Parabéns $nome_usuario, Você recebeu uma nova ação.  ",
                        'lida' => 0,
                        'data' => $date_hoje,
                        'email' => $envia_email,
                        'idplano' => $id_acao
                    );
                    $this->atas_model->add_notificacoes($data_notificacao);

                    
                    //cadastro para envio de email
                       $data_email = array(
                        'id_from' => $usuario,
                        'id_to' => $id_responsavel,
                        'title' => "Nova Atividade",
                        'text' => "Parabéns $nome_usuario, você recebeu uma nova ação. Acessar o SigPlus para mais detalhes.",
                        'lida' => 0,
                        'data' => $date_hoje,
                        'referencia' => "Project > plano_acao_detalhes",
                        'idplano' => $id_acao,
                        'empresa' => $empresa,
                        'enviado' => 0  );
                        $this->atas_model->add_email($data_email);
                        
                  } 
            
            }

           
            
            
            $this->session->set_flashdata('message', lang("Ação Cadastrada com Sucesso!!!"));
            redirect("project/plano_acao_detalhes/".$id_ata);
             //echo "<script>history.go(-2)</script>";
                
        }else{
             
             
                // registra o log de movimentação

                $date_hoje = date('Y-m-d H:i:s');    
                $usuario = $this->session->userdata('user_id');
                $empresa = $this->session->userdata('empresa');
                $ip = $_SERVER["REMOTE_ADDR"];

                $logdata = array('date' => date('Y-m-d H:i:s'), 
                    'type' => 'ACESSO', 
                    'description' => "Acessou o detalhe do plano de ação do menu $menu do Módulo PROJECT",  
                    'userid' => $this->session->userdata('user_id'), 
                    'ip_address' => $_SERVER["REMOTE_ADDR"],
                    'tabela' => 'sig_plano_acao',
                    'row' => '',
                    'depois' => '', 
                    'modulo' => 'owner',
                    'funcao' => 'owner/cadastro',  
                    'empresa' => $this->session->userdata('empresa'));
                    $this->owner_model->addLog($logdata); 
            
                    
        
            $this->data['categorias'] = $this->atas_model->getAllCategoriaPlanoAcaoByPlano($id_pa);
            $this->data['atas'] = $this->atas_model->getAllMinhasAtasByPlanoAcao($id_pa);
            
            $this->data['plano_acao'] = $this->atas_model->getPlanoAcaoByID($id_pa);
            $this->data['planos'] = $this->atas_model->getAllAcaoPlanoAcaoById($id_pa);
            
            
            $this->page_construct_project('project/cadastro_basico_modelo/plano_acao/plano', $meta, $this->data);
           // $this->page_construct_user('owner/empresas/index', $meta, $this->data);
         }
         
         
    }
    
    public function novaCategoriaPlano($id_pa) {


        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

         $this->form_validation->set_rules('id_cadastro', lang("id_cadastro"), 'required');
        
        if ($this->form_validation->run() == true) {
            
            
            //$projetos = $this->projetos_model->getProjetoAtualByID_completo();
            //$id_projeto = $projetos->id;

            $date_hoje = date('Y-m-d H:i:s');    
            $usuario = $this->session->userdata('user_id');
            $empresa = $this->session->userdata('empresa');   
            $assunto = $this->input->post('assunto');
            $plano_id = $this->input->post('plano_id');
           
              
            //CADASTRAR UMA NOVA CATEGORIA
            $data_categoria = array(
                'pa' => $plano_id,
                'empresa' => $empresa,
                'usuario' => $usuario,
                'descricao' => $assunto
            );
          $id_cadastro = $this->networking_model->addCategoria_PlanoAcao($data_categoria);

            $date_hoje = date('Y-m-d H:i:s');
            $usuario = $this->session->userdata('user_id');
            $empresa = $this->session->userdata('empresa');
            $ip = $_SERVER["REMOTE_ADDR"];

            $logdata = array('date' => date('Y-m-d H:i:s'),
                'type' => 'INSERT',
                'description' => 'Cadastro de uma nova Categoria, ID: '.$id_cadastro,
                'userid' => $this->session->userdata('user_id'),
                'ip_address' => $_SERVER["REMOTE_ADDR"],
                'tabela' => 'sig_plano_acao_categoria',
                'row' => $id_cadastro,
                'depois' => json_encode($data_categoria),
                'modulo' => 'project',
                'funcao' => 'project/novaCategoriaPlano',
                'empresa' => $this->session->userdata('empresa'));

            $this->owner_model->addLog($logdata);
            // exit;

            $this->session->set_flashdata('message', lang("Cadastro realizado com Sucesso!!!"));
           // redirect("project/plano_acao_detalhes/$plano_id");
            echo "<script>history.go(-1)</script>";
        } else {

        $this->data['plano_id'] = $id_pa;
        $usuario = $this->session->userdata('user_id');                     
        $this->load->view($this->theme . 'project/cadastro_basico_modelo/plano_acao/novaCategoria', $this->data);  
          //  $this->page_construct_project('project/cadastro_basico_modelo/plano_acao/plano', $meta, $this->data);
        }
    }
    
    public function atualizarOrdemCategoriaPlano($id_pa) {
        
       $plano_acao = $this->input->post('plano_acao');
       //echo $plano_acao.'<br>';
        if (isset($_POST['ordem_categoria'])) {
            $ordem_categoria = $_POST['ordem_categoria'];
           // print_r($ordem_categoria);
            $ordem = 0;
            foreach ($ordem_categoria as $categoria) {
            //    echo ' ordem '.$ordem.' - '. $categoria.'<br>';
                
            //ATUALIZA ORDEM CATEGORIA
            $data_categoria = array('ordem' => $ordem);
            $this->networking_model->atualizaOrdemCategoriaPlano($categoria, $data_categoria);
                
                $ordem++;
            }
            
        }
        $this->session->set_flashdata('message', lang("Ordem Atualizada!!!"));
        echo "<script>history.go(-1)</script>";
        exit;
        
         
    }
    
    public function finalizaPlano($id = null)
    {
        $this->sma->checkPermissions();
      
        $date_finalizacao = date('Y-m-d H:i:s');       
        $envia_email = 1;
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        
        //echo $id; exit;
          if ($id) {
           
            $status = 1;
            $id_plano = $id;
            $usuario = $this->session->userdata('user_id');
            $empresa = $this->session->userdata('empresa');
            
            $data_ataFinalizacao = array(
                'status' => $status,
                'data_finalizacao' => $date_finalizacao,
                'usuario_finalizacao' => $usuario
            );
            
            
             $data_plano = array(
                
                'status' => 'PENDENTE'
            );
            
           
            //finaliza o plano de ação
            if($this->atas_model->updatePlanoAcao($id_plano, $data_ataFinalizacao)){
               
               $planos_ata = $this->atas_model->getAllPlanoAbertosByPlanoAcaoID($id_plano); // $this->atas_model->getPlanoByAtaID_distinct($id_ata);
               //
                // ENVIA EMAIL E NOTIFIAÇÕES
                $date_hoje = date('Y-m-d H:i:s');
                $usuario = $this->session->userdata('user_id');
                $empresa = $this->session->userdata('empresa');
               //PRINT_R($planos_ata); EXIT;
                
                foreach ($planos_ata as $plano_ata) {
            
                $id_usuario = $plano_ata->responsavel;
                $id_acao = $plano_ata->idplanos;
                $id_responsavel = $plano_ata->responsavel;
                //   $this->ion_auth->emailAtaUsuario($id_usuario, $id_acao);
              
                /***********************************************************************************************
                ********************** L O G     A Ç Ã O ****************************************************** 
                ***********************************************************************************************/
               $data_log = array(
                    'idplano' => $id_acao,
                    'data_registro' => $date_hoje,
                    'usuario' => $usuario,
                    'descricao' => "Ação Criada com sucesso",
                    'empresa' => $empresa
                  );
                $this->atas_model->add_logPlano($data_log);

               // LOG GERAL
                
                $ip = $_SERVER["REMOTE_ADDR"];

                $logdata = array('date' => date('Y-m-d H:i:s'),
                    'type' => 'UPDATE',
                    'description' => 'Cadastro de uma nova Ação, ID: '.$id_acao,
                    'userid' => $this->session->userdata('user_id'),
                    'ip_address' => $_SERVER["REMOTE_ADDR"],
                    'tabela' => 'sig_planos',
                    'row' => $id_acao,
                    'depois' => json_encode($data_plano),
                    'modulo' => 'project',
                    'funcao' => 'project/plano_acao_detalhes',
                    'empresa' => $this->session->userdata('empresa'));
                $this->owner_model->addLog($logdata);
           
                 
                 $envia_email = 1;
                 // REGISTRA A NOTIFICAÇÃO
                    $users_dados = $this->site->geUserByID($id_responsavel);
                    $nome_usuario = $users_dados->first_name;

                    $data_notificacao = array(
                        'id_from' => $usuario,
                        'id_to' => $id_responsavel,
                        'title' => "Nova Ação",
                        'text' => "Parabéns $nome_usuario, Você recebeu uma nova ação.  ",
                        'lida' => 0,
                        'data' => $date_hoje,
                        'email' => $envia_email,
                        'idplano' => $id_acao
                    );
                    
                    $this->atas_model->add_notificacoes($data_notificacao);

                    
                    //cadastro para envio de email
                       $data_email = array(
                        'id_from' => $usuario,
                        'id_to' => $id_responsavel,
                        'title' => "Nova Ação",
                        'text' => "Parabéns $nome_usuario, você recebeu uma nova ação. Acessar o SigPlus para mais detalhes.",
                        'lida' => 0,
                        'data' => $date_hoje,
                        'referencia' => "Project > finalizaPlano",
                        'idplano' => $id_acao,
                        'empresa' => $empresa,
                        'enviado' => 0);
                        
                        $this->atas_model->add_email($data_email);
                    

               }
               //finaliza AS AÇÕES
                $this->atas_model->updateAcoesPlanoAcao($id_plano, $data_plano);
               
               
           }
            
            
            
               
           
       
            
            $this->session->set_flashdata('message', lang("O Plano de ação foi ativado com Sucesso!!!"));
            redirect("project/plano_acao_detalhes/$id_plano");
            
        } else {

           
            redirect("project/plano_acao_detalhes/$id_plano");
            
           

            }
    }
    
     public function pdf_plano_acao($id = null, $view = null)
    {
        
        $this->sma->checkPermissions();
        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        $usuario = $this->session->userdata('usuario');
        $empresa = $this->session->userdata('empresa');
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
       
            $this->data['id'] = $id;
            $this->data['plano_acao'] = $this->atas_model->getPlanoAcaoByID($id);  // $this->atas_model->getAtaProjetoByID_ATA($id);
            $empresa_dados = $this->owner_model->getEmpresaById($empresa);
            $logo_empresa = $empresa_dados->logo_empresa;
            $redape_empresa_ata = $empresa_dados->rodape_ata;
            
            $cadastro_usuario =  $this->site->getUser($usuario);
            $nome = $cadastro_usuario->first_name;
            
            //$this->data['users'] = $this->site->getAllUser();
           // $this->data['projetos'] = $this->atas_model->getAllProjetos();
            

            $name = lang("PlanoAção") . "_" . str_replace('/', '_', $id) . ".pdf";
            $html = $this->load->view($this->theme . 'project/cadastro_basico_modelo/plano_acao/pdf_plano', $this->data, true);

            
          
            
        if ($view) {
            $this->load->view($this->theme . 'project/cadastro_basico_modelo/plano_acao/pdf_plano', $this->data);
        } else{
            
            $this->sma->generate_pdf_plano_acao($html, $name, false, $usuario, null, null, null, null, $logo_empresa, $redape_empresa_ata, $nome);
        }
    }
    
    /*
     * GANTT
     */
    public function ganttPlanoAcao($id_pa)
    {
       $this->sma->checkPermissions();

        $date_cadastro = date('Y-m-d H:i:s');                           
        
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        
        $this->data['idplano'] = $id_pa;
        $this->data['planos'] = $this->atas_model->getAllAcaoPlanoAcaoById($id_pa); 
       
        $usuario = $this->session->userdata('user_id');                     
       
        //$this->load->view($this->theme . 'projetos/documentacao/add', $this->data);
        $this->load->view($this->theme . 'networking/plano_acao/gantt', $this->data);
           

            
    }
    
    public function ganttPlanoAcao2($id_pa)
    {
       $this->sma->checkPermissions();

        $date_cadastro = date('Y-m-d H:i:s');                           
        
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
       
        $this->data['idplano'] = $id_pa;
        $this->data['planos'] = $this->atas_model->getAllAcaoPlanoAcaoById($id_pa); 
       
        $usuario = $this->session->userdata('user_id');                     
       
        //$this->load->view($this->theme . 'projetos/documentacao/add', $this->data);
        $this->load->view($this->theme . 'project/cadastro_basico_modelo/plano_acao/gantt', $this->data);
           

            
    }
    
    /******************************************************************/
    
    
    
    /*
     * AÇÕES DO PROJECT > PLANO DE AÇÃO
     */
    public function adcionar_acao_plano_acao($id = null)
    {
     
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
            }
        
                       
            $date_hoje = date('Y-m-d H:i:s');    
            $usuario = $this->session->userdata('user_id');  
            $ip = $_SERVER["REMOTE_ADDR"];
            
           // $descricao = $this->input->post('descricao') .'<br>';
            
           
            $usuario = $this->session->userdata('user_id');
            $projetos_usuario = $this->site->getProjetoAtualByID_completo($usuario);
            $this->data['eventos'] = $this->projetos_model->getAllEventosItemEventoByProjeto();   
                                                            
            $this->data['users'] = $this->atas_model->getAllUsersSetores(); 
            //$this->data['macro'] = $this->atas_model->getAllMacroProcesso();
          
           // $this->data['projetos'] = $this->atas_model->getAllProjetos();      
            $this->data['ata'] = $id;
            $this->data['plano_acao'] = $this->atas_model->getPlanoAcaoByID($id);
            //$this->data['avulsa'] = $avulsa;
            
            $this->data['acoes'] = $this->atas_model->getAllAcoesVinculoCadastro($projetos_usuario->projeto_atual);
            //$this->data['acoes'] = $this->atas_model->getPlanoByID($id);
            
            $participantes = $this->input->post('participantes');
     
            foreach ($participantes as $participante) {
               $participantes_usuario[] = $participante;
            }
            
            $this->data['participantes_usuarios'] = $participantes_usuario;
            //$this->data['participantes_lista'] = "$nomes_participantes";
           
            $this->page_construct_project('project/cadastro_basico_modelo/plano_acao/novaAcao', $meta, $this->data);
             //$this->load->view($this->theme . 'project/cadastro_basico_modelo/plano_acao/novaAcao', $this->data);
         
    }
    
    public function replicar_acao($id_acao, $id_plano_acao)
    {
     //echo $id_acao
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
            }
            $date_hoje = date('Y-m-d H:i:s');    
            $usuario = $this->session->userdata('user_id');
            $projetos_usuario = $this->site->getProjetoAtualByID_completo($usuario);
                        
            
            $ip = $_SERVER["REMOTE_ADDR"];
            $this->data['acoes_vinculadas'] = $this->atas_model->getAllAcoesVinculadasAta($id_acao);
            $this->data['eventos'] = $this->projetos_model->getAllEventosItemEventoByProjeto($projetos_usuario->projeto_atual);   
            $this->data['users'] = $this->atas_model->getAllUsersSetores(); 
            $this->data['projetos'] = $this->atas_model->getAllProjetos();      
            $this->data['acoes'] = $this->atas_model->getAllAcoesProjeto($id_acao);
            $this->data['idplano'] = $id_acao;
      
            $this->page_construct_ata('project/cadastro_basico_modelo/plano_acao/replicar', $meta, $this->data);
        
    }
    
    public function replicar_acao_form(){
      
       
        
        
        $this->form_validation->set_rules('descricao', lang("Descrição"), 'required');
        $this->form_validation->set_rules('periodo_acao', lang("Data Início e Término"), 'required');
        $this->form_validation->set_rules('evento', lang("Item do Evento"), 'required');
       // $this->form_validation->set_rules('responsavel', lang("Responsável"), 'required');
       
    
       
       // echo 'aqui'. $this->input->post('id'); exit;
          if ($this->form_validation->run() == true) {
          
            //$idata = $this->input->post('id');   
            $id_ata = $this->input->post('idatas');  
            $evento = $this->input->post('evento'); 
            
            $descricao = $this->input->post('descricao');
            $onde = $this->input->post('onde');
            $porque = $this->input->post('porque');
            $como = $this->input->post('como');
            $valor_custo = $this->input->post('valor_custo');
            if($valor_custo){
            $valor_custo = str_replace(',', '.', str_replace('.', '', $valor_custo));
            }
            $custo_descricao = trim($this->input->post('custo'));
            //$dataEntrega = $this->sma->fld(trim($this->input->post('dateEntrega'))); 
            //$this->input->post('dateEntrega');
            $responsaveis = $this->input->post('responsavel');
            
            //$status = trim($this->input->post('status_plano')); 
            $date_cadastro = date('Y-m-d H:i:s');  
            $usuario = $this->session->userdata('user_id');
            $empresa = $this->session->userdata('empresa');
           
            //PERÍODO
            $periodo_acao = $this->input->post('periodo_acao');
           
            $evento_periodo_de = substr($periodo_acao, 0, 10);
             $partes_data_inicio = explode("/", $evento_periodo_de);
             $ano_de = $partes_data_inicio[2];
             $mes = $partes_data_inicio[1];
             $dia = $partes_data_inicio[0];
             $data_tratado_de = $ano_de.'-'.$mes.'-'.$dia;
            
             $evento_periodo_ate = substr($periodo_acao, 13, 24);
             $partes_data_fim = explode("/", $evento_periodo_ate);
             $anof = $partes_data_fim[2];
             $mesf = $partes_data_fim[1];
             $diaf = $partes_data_fim[0];
             $data_tratado_ate = $anof.'-'.$mesf.'-'.$diaf;
              
             
             /*
              * VERIFICA SE A DATA DA AÇÃO ESTA DENTRO DO ITEM DE EVENTO SELECIONADO
              */
             $dados_item = $this->projetos_model->getItemEventoByID($evento);
             $inicio_fase = $dados_item->dt_inicio;
             $fim_fase = $dados_item->dt_fim;
             
             if($data_tratado_de < $inicio_fase){
                  $rData = explode("-", $inicio_fase);
                  $rData = $rData[2].'/'.$rData[1].'/'.$rData[0];
                 $this->session->set_flashdata('error', lang("A data de Início da ação, é menor que o início do Item do Evento selecionado! A data Início, não pode ser menor que :  $rData"));
            
                echo "<script>history.go(-1)</script>";
                exit;
                // echo 'A data de início é menor que a esperada';
             }else if($data_tratado_ate > $fim_fase){
                 $rData = explode("-", $fim_fase);
                  $rData = $rData[2].'/'.$rData[1].'/'.$rData[0];
                 $this->session->set_flashdata('error', lang("A data de Término da ação, é maior que o término do Item do Evento selecionado! A data Término, não pode ser maior que :  $rData"));
                echo "<script>history.go(-1)</script>";
                exit;
                 // echo 'A data de Término é maior que a esperada : '.$data_tratado_ate .'>'. $fim_fase;
             }
            
           // exit;
            $dataInicio = $data_tratado_de; 
            $dataTermino = $data_tratado_ate;
            $horas_previstas = $this->input->post('horas_previstas');
                         
           
            
           
            if(!$responsaveis){
            $this->session->set_flashdata('error', lang("Selecione um responsável pela ação!!!"));
            echo "<script>history.go(-1)</script>";
            exit;
            }
            
            
            /*
             * APLICA A REGRA AS AÇÕES COM VINCULOS
             */
            $acao_vinculo = $this->input->post('acoes_vinculo');
            $tipo_vinculo = $this->input->post('tipo_vinculo');
            
            
            $cont_v = 0;
            foreach ($acao_vinculo as $vinculo) {
             $cont_v++;   
            }
           
             if($cont_v > 0){
                 if(!$tipo_vinculo){
                    $this->session->set_flashdata('error', lang("Selecione o Tipo de Vínculo!!!"));
                    echo "<script>history.go(-1)</script>";
                    exit;
                 }else{
                      //le as ações vinculadas selecionadas
                      foreach ($acao_vinculo as $vinculo) {
                         $dados_acao = $this->atas_model->getPlanoByID($vinculo);
                         $inicio = $dados_acao->data_entrega_demanda;
                         $fim_v = $dados_acao->data_termino;   
                         
                         if($tipo_vinculo == 'II'){
                            if($dataInicio != $inicio){
                                $this->session->set_flashdata('error', lang("Para manter o vínculo da ação, a data de início da ação deve iniciar na mesma data de início da ação vinculada!!"));
                                    echo "<script>history.go(-1)</script>";
                                    exit;
                             }
                         }else if($tipo_vinculo == 'IF'){
                             
                             if($dataInicio < $fim_v){
                                $this->session->set_flashdata('error', lang("Para manter o vínculo da ação, a data de início da ação deve iniciar após a data de término da ação vinculada!!"));
                                    echo "<script>history.go(-1)</script>";
                                    exit;
                             }
                         
                         }
                         
                        }
                 }
            }
            //FIM VINCULO
           
          
             
                
             $dados_responsavel = $this->atas_model->getUserSetorBYid($responsaveis);
             $setor_responsavel = $dados_responsavel->setores_id;
             $id_responsavel = $dados_responsavel->users_id;
                
             $dados_sequencial = $this->atas_model->getSequencialPlanosEmpresa();
             $valor_sequencial = $dados_sequencial->sequencial;
            
             if($valor_sequencial == null){
                 $sequencia = 1;
             }else{
                 $sequencia = $valor_sequencial;
             }
           
             
             $data_plano = array(
                'idplano' => $id_ata,
                'descricao' => $descricao,
                'onde' => $onde,
                'como' => $como,
                'porque' => $porque,
                'descricao' => $descricao,
                'custo' => $custo_descricao,
                'valor_custo' => $valor_custo,
                'data_entrega_demanda' => $dataInicio, 
                'data_termino' => $dataTermino,
                'horas_previstas' => $horas_previstas,
                'responsavel' => $id_responsavel,
                'setor' => $setor_responsavel,  
                'status' => 'ABERTO',
                'data_elaboracao' => $date_cadastro,   
                'responsavel_elaboracao' => $usuario,
                'eventos' => $evento,
                'status_tipo' => 1,
                'sequencial' => $sequencia,
                'empresa' => $empresa,
                 'tipo_vinculo' => $tipo_vinculo
            );  
            
          // print_r($data_plano); exit;
            
           
            
             if ($_FILES['document']['size'] > 0) {
                $this->load->library('upload');
                $config['upload_path'] = $this->upload_path;
                $config['allowed_types'] = $this->digital_file_types;
                $config['max_size'] = $this->allowed_file_size;
                $config['overwrite'] = false;
                $config['encrypt_name'] = true;
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('document')) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect($_SERVER["HTTP_REFERER"]);
                }
                $photo = $this->upload->file_name;
                $data_plano['anexo'] = $photo;
            }
            
            $this->atas_model->add_planoAcao($data_plano,$acao_vinculo,$avulsa,$id_responsavel);
            
           
//echo $id_ata; exit;
             
            
            
            $this->session->set_flashdata('message', lang("Ação Cadastrada com Sucesso!!!"));
            redirect("project/plano_acao_detalhes/".$id_ata);
            
        }
    }
    
    //EDITAR AÇÃO - VIEW     o retorno é quando se tem a opção de retornar para o usuário o status da ação, permitindo concluir a ação
    public function manutencao_acao_pendente($id_acao = null, $retorno = null)
    {
     
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
            }
            $date_hoje = date('Y-m-d H:i:s');    
            $usuario = $this->session->userdata('user_id');
            $projetos_usuario = $this->site->getProjetoAtualByID_completo($usuario);
                        
          
            $ip = $_SERVER["REMOTE_ADDR"];
            
            $this->data['acoes_vinculadas'] = $this->atas_model->getAllAcoesVinculadasAta($id_acao);
            $this->data['acoes_arquivos'] = $this->atas_model->getAllArquivosByAcao($id_acao); 
            
            $this->data['eventos'] = $this->projetos_model->getAllEventosItemEventoByProjeto($projetos_usuario->projeto_atual);   
            $this->data['users'] = $this->atas_model->getAllUsersSetores(); 
            
           // $this->data['projetos'] = $this->atas_model->getAllProjetos();      
            $this->data['acoes'] = $this->atas_model->getAllAcoesProjeto($id_acao);
            $this->data['idplano'] = $id_acao;
            
            if(!$retorno){
                $retorno = 0;
            }
            $this->data['retorno'] = $retorno;
           //echo 'aqui'; exit;
            $this->page_construct_ata('project/cadastro_basico_modelo/plano_acao/editAcao', $meta, $this->data);
         
    }
       
    // EDITAR AÇÃO FORM VIEW
    public function manutencao_acao_pendente_form($id = null)
    {
              
            $date_hoje = date('Y-m-d H:i:s');    
            $usuario = $this->session->userdata('user_id');  
            $ip = $_SERVER["REMOTE_ADDR"];
            
            $idata = $this->input->post('idatas');
            
            $idplano = $this->input->post('id');  
            $evento = $this->input->post('evento'); 
            
            $descricao = $this->input->post('descricao');
            if(!$descricao){
                $this->session->set_flashdata('error', lang("Informe a Descrição"));
            
                echo "<script>history.go(-1)</script>";
                exit;
            }
            $onde = $this->input->post('onde');
            $porque = $this->input->post('porque');
            $como = $this->input->post('como');
            $valor_custo = $this->input->post('valor_custo');
             if($valor_custo){
            $valor_custo = str_replace(',', '.', str_replace('.', '', $valor_custo));
            }
            
            $custo_descricao = trim($this->input->post('custo'));
            //$dataEntrega = $this->sma->fld(trim($this->input->post('dateEntrega'))); 
            //$this->input->post('dateEntrega');
            $responsavel = $this->input->post('responsavel');
            $categoria_plano = $this->input->post('categoria');
            $peso = $this->input->post('peso');
            //$status = trim($this->input->post('status_plano')); 
            $date_cadastro = date('Y-m-d H:i:s');  
            $usuario = $this->session->userdata('user_id');
            $empresa = $this->session->userdata('empresa');
           
            //PERÍODO
            //PERÍODO
            $data_inicio = $this->input->post('data_inicio');
            $data_termino = $this->input->post('data_termino');
            /*
            $periodo_acao = $this->input->post('periodo_acao');
           
             $evento_periodo_de = substr($periodo_acao, 0, 10);
             $partes_data_inicio = explode("/", $evento_periodo_de);
             $ano_de = $partes_data_inicio[2];
             $mes = $partes_data_inicio[1];
             $dia = $partes_data_inicio[0];
             $data_tratado_de = $ano_de.'-'.$mes.'-'.$dia;
            
             $evento_periodo_ate = substr($periodo_acao, 13, 24);
             $partes_data_fim = explode("/", $evento_periodo_ate);
             $anof = $partes_data_fim[2];
             $mesf = $partes_data_fim[1];
             $diaf = $partes_data_fim[0];
             $data_tratado_ate = $anof.'-'.$mesf.'-'.$diaf;
             * 
             */
             
             
             /*
              * VERIFICA SE A DATA DA AÇÃO ESTA DENTRO DO ITEM DE EVENTO SELECIONADO
              */
             $dados_item = $this->projetos_model->getItemEventoByID($evento);
             $inicio_fase = $dados_item->dt_inicio;
             $fim_fase = $dados_item->dt_fim;
             
           
            
             if($data_inicio < $inicio_fase){
                 $rData = explode("-", $inicio_fase);
                 $rData = $rData[2].'/'.$rData[1].'/'.$rData[0];
                 $this->session->set_flashdata('error', lang("A data de Início da ação, é menor que o início do Item do Evento selecionado! A data Início, não pode ser menor que :  $rData"));
            
                echo "<script>history.go(-1)</script>";
                exit;
                // echo 'A data de início é menor que a esperada';
             }else if($data_termino > $fim_fase){
                 $rData = explode("-", $fim_fase);
                  $rData = $rData[2].'/'.$rData[1].'/'.$rData[0];
                 $this->session->set_flashdata('error', lang("A data de Término da ação, é maior que o término do Item do Evento selecionado! A data Término, não pode ser maior que :  $rData"));
                echo "<script>history.go(-1)</script>";
                exit;
                 // echo 'A data de Término é maior que a esperada : '.$data_tratado_ate .'>'. $fim_fase;
             }
            
           // exit;
            $dataInicio = $data_inicio; 
            $dataTermino = $data_termino;
            $horas_previstas = $this->input->post('horas_previstas');
                         
            
            if(!$responsavel){
            $this->session->set_flashdata('error', lang("Selecione um responsável pela ação!!!"));
            echo "<script>history.go(-1)</script>";
            exit;
            }
            
            /*
             * APLICA A REGRA AS AÇÕES COM VINCULOS
             */
            $acao_vinculo = $this->input->post('acoes_vinculo');
            $tipo_vinculo = $this->input->post('tipo_vinculo');
            
           
             if($acao_vinculo){
                 if(!$tipo_vinculo){
                    $this->session->set_flashdata('error', lang("Selecione o Tipo de Vínculo!!!"));
                    echo "<script>history.go(-1)</script>";
                    exit;
                 }else{
                      //le as ações vinculadas selecionadas
                    
                         $dados_acao = $this->atas_model->getPlanoByID($acao_vinculo);
                         $inicio = $dados_acao->data_entrega_demanda;
                         $fim_v = $dados_acao->data_termino;   
                         
                         if($tipo_vinculo == 'II'){
                            if($dataInicio != $inicio){
                                $this->session->set_flashdata('error', lang("Para manter o vínculo da ação, a data de início da ação deve iniciar na mesma data de início da ação vinculada!!"));
                                    echo "<script>history.go(-1)</script>";
                                    exit;
                             }
                         }else if($tipo_vinculo == 'IF'){
                             
                             if($dataInicio < $fim_v){
                                $this->session->set_flashdata('error', lang("Para manter o vínculo da ação, a data de início da ação deve iniciar após a data de término da ação vinculada!!"));
                                    echo "<script>history.go(-1)</script>";
                                    exit;
                             }
                         
                         }
                         
                       
                 }
            }
            //FIM VINCULO
            
           $dados_responsavel = $this->atas_model->getUserSetorBYid($responsavel);
             $setor_responsavel = $dados_responsavel->setores_id;
             $id_responsavel = $dados_responsavel->users_id;
            $dados_user = $this->site->getUser($id_responsavel);
            $nome_responsavel = $dados_user->first_name; 
             
             /***************************************************
             ********************* registra os logs da ação
             ****************************************************/
            $dados_acao = $this->atas_model->getPlanoByID($idplano);
            $decricao_original = $dados_acao->descricao;
            $onde_original = $dados_acao->onde;
            $como_original = $dados_acao->como;
            $porque_original = $dados_acao->porque;
            $custo_original = $dados_acao->custo;
            $valor_custo_original = $dados_acao->valor_custo;
            $data_inicio_original = $dados_acao->data_entrega_demanda;
            $data_termino_original = $dados_acao->data_termino;
            $horas_original = $dados_acao->horas_previstas;
            $responsavel_original = $dados_acao->responsavel;
            $setor_original = $dados_acao->setor;
            $evento_original = $dados_acao->eventos;
            $categoria_plano_original = $dados_acao->categoria_plano;
            $peso_original = $dados_acao->peso;
            
            $dados_user_original = $this->site->getUser($responsavel_original);
            $nome_original = $dados_user_original->first_name;
            
            $data_plano_original = array(
                'descricao' => $decricao_original,
                'onde' => $onde_original,
                'como' => $como_original,
                'porque' => $porque_original,
                'custo' => $custo_original,
                'valor_custo' => $valor_custo_original,
                'data_entrega_demanda' => $data_inicio_original, 
                'data_termino' => $data_termino_original,
                'horas_previstas' => $horas_original,
                'responsavel' => $responsavel_original,
                'setor' => $setor_original,  
                'eventos' => $evento_original,
                'categoria_plano' => $categoria_plano_original,
                'peso' => $peso_original
            );
          
              /***********************************************************************************************
            ********************** L O G     A Ç Ã O ****************************************************** 
            ***********************************************************************************************/
            
            //1 - DESCRIÇÃO
            if($decricao_original != $descricao){
               $data_log11 = array(
                    'idplano' => $idplano,
                    'data_registro' => $date_cadastro,
                    'usuario' => $usuario,
                    'descricao' => "A descrição da ação foi alterada",
                    'antes' => "$decricao_original",
                    'depois' => "$descricao",
                    'empresa' => $empresa
                  );
                $this->atas_model->add_logPlano($data_log11);
            } //2 - ONDE
            if($decricao_original != $descricao){
               $data_log1 = array(
                    'idplano' => $idplano,
                    'data_registro' => $date_cadastro,
                    'usuario' => $usuario,
                    'descricao' => "O campo (ONDE) da ação foi alterado",
                    'antes' => "$onde_original",
                    'depois' => "$onde",
                    'empresa' => $empresa
                  );
                $this->atas_model->add_logPlano($data_log1);
            } //3 - COMO
            if($decricao_original != $descricao){
               $data_log2 = array(
                    'idplano' => $idplano,
                    'data_registro' => $date_cadastro,
                    'usuario' => $usuario,
                    'descricao' => "O campo (COMO) da ação foi alterado",
                    'antes' => "$como_original",
                    'depois' => "$como",
                    'empresa' => $empresa
                  );
                $this->atas_model->add_logPlano($data_log2);
            } //4 - PORQUE
            if($porque_original != $porque){
               $data_log3 = array(
                    'idplano' => $idplano,
                    'data_registro' => $date_cadastro,
                    'usuario' => $usuario,
                    'descricao' => "O campo (POR QUÊ) da ação foi alterado",
                    'antes' => "$porque_original",
                    'depois' => "$porque",
                    'empresa' => $empresa
                  );
                $this->atas_model->add_logPlano($data_log3);
            } //5 - CUSTO
            if($custo_original != $custo_descricao){
               $data_log4 = array(
                    'idplano' => $idplano,
                    'data_registro' => $date_cadastro,
                    'usuario' => $usuario,
                    'descricao' => "A descrição do Custo da ação foi alterado",
                    'antes' => "$custo_original",
                    'depois' => "$custo_descricao",
                    'empresa' => $empresa
                  );
                $this->atas_model->add_logPlano($data_log4);
            } //6 - VALOR DO CUSTO
            if($valor_custo_original != $valor_custo){
               $data_log5 = array(
                    'idplano' => $idplano,
                    'data_registro' => $date_cadastro,
                    'usuario' => $usuario,
                    'descricao' => "O valor do Custo da ação foi alterado",
                    'antes' => "$valor_custo_original",
                    'depois' => "$valor_custo",
                    'empresa' => $empresa
                  );
                $this->atas_model->add_logPlano($data_log5);
            } //7 - DATA INICIO
            if($data_inicio_original != $dataInicio){
               $data_log6 = array(
                    'idplano' => $idplano,
                    'data_registro' => $date_cadastro,
                    'usuario' => $usuario,
                    'descricao' => "A data de Início da ação foi alterado",
                    'antes' => "$data_inicio_original",
                    'depois' => "$dataInicio",
                    'empresa' => $empresa
                  );
                $this->atas_model->add_logPlano($data_log6);
            } //8 - DATA TERMINO
            if($data_termino_original != $dataTermino){
               $data_log7 = array(
                    'idplano' => $idplano,
                    'data_registro' => $date_cadastro,
                    'usuario' => $usuario,
                    'descricao' => "A data de Término da ação foi alterado",
                    'antes' => "$data_termino_original",
                    'depois' => "$dataTermino",
                    'empresa' => $empresa
                  );
                $this->atas_model->add_logPlano($data_log7);
            } //9 - RESPONSÁVEL
            if($responsavel_original != $id_responsavel){
               $data_log8 = array(
                    'idplano' => $idplano,
                    'data_registro' => $date_cadastro,
                    'usuario' => $usuario,
                    'descricao' => "O Responsável da ação foi alterado",
                    'antes' => "$nome_original",
                    'depois' => "$nome_responsavel",
                    'empresa' => $empresa
                  );
                $this->atas_model->add_logPlano($data_log8);
            } //10 - EVENTO
            if($evento_original != $evento){
               $data_log9 = array(
                    'idplano' => $idplano,
                    'data_registro' => $date_cadastro,
                    'usuario' => $usuario,
                    'descricao' => "O item de evento da ação foi alterado",
                    'antes' => "$evento_original",
                    'depois' => "$evento",
                    'empresa' => $empresa
                  );
                $this->atas_model->add_logPlano($data_log9);
            } //11 - PESO
            if($peso_original != $peso){
               $data_log10 = array(
                    'idplano' => $idplano,
                    'data_registro' => $date_cadastro,
                    'usuario' => $usuario,
                    'descricao' => "O peso da ação foi alterado",
                    'antes' => "$peso_original",
                    'depois' => "$peso",
                    'empresa' => $empresa
                  );
                $this->atas_model->add_logPlano($data_log10);
            }
            
            
            
              
             
                
             $dados_sequencial = $this->atas_model->getSequencialPlanosEmpresa();
             $valor_sequencial = $dados_sequencial->sequencial;
            
             if($valor_sequencial == null){
                 $sequencia = 1;
             }else{
                 $sequencia = $valor_sequencial;
             }
           
             
             $data_plano = array(
                'descricao' => $descricao,
                'onde' => $onde,
                'como' => $como,
                'porque' => $porque,
                'custo' => $custo_descricao,
                'valor_custo' => $valor_custo,
                'data_entrega_demanda' => $dataInicio, 
                'data_termino' => $dataTermino,
                'horas_previstas' => $horas_previstas,
                'responsavel' => $id_responsavel,
                'setor' => $setor_responsavel,  
                'data_elaboracao' => $date_cadastro,   
                'responsavel_elaboracao' => $usuario,
                'eventos' => $evento,
                'categoria_plano' => $categoria_plano,
                'peso' => $peso
            );  
          
            
           
            
             if ($_FILES['document']['size'] > 0) {
                $this->load->library('upload');
                $config['upload_path'] = $this->upload_path;
                $config['allowed_types'] = $this->digital_file_types;
                $config['max_size'] = $this->allowed_file_size;
                $config['overwrite'] = false;
                $config['encrypt_name'] = true;
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('document')) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect($_SERVER["HTTP_REFERER"]);
                }
                $photo = $this->upload->file_name;
                $data_plano['anexo'] = $photo;
            }
            
            
            $data_vinculo = array(
                
                'planos_idplanos' => $idplano,
                'id_vinculo' => $acao_vinculo,
                'tipo' => $tipo_vinculo
            );
            
            
            $this->atas_model->updatePlano($idplano, $data_plano,$data_vinculo, $acao_vinculo);
            
           
             $date_hoje = date('Y-m-d H:i:s');
            $usuario = $this->session->userdata('user_id');
            $empresa = $this->session->userdata('empresa');
            $ip = $_SERVER["REMOTE_ADDR"];

            $logdata = array('date' => date('Y-m-d H:i:s'),
                'type' => 'UPDATE',
                'description' => 'Alteração no Cadastro da ação, ID: '.$idplano,
                'userid' => $this->session->userdata('user_id'),
                'ip_address' => $_SERVER["REMOTE_ADDR"],
                'tabela' => 'sig_planos',
                'row' => $idplano,
                'antes' => json_encode($data_plano_original),
                'depois' => json_encode($data_plano),
                'modulo' => 'project',
                'funcao' => 'project/manutencao_acao_pendente_form',
                'empresa' => $this->session->userdata('empresa'));

            $this->owner_model->addLog($logdata);
            
            
            
            
            if($acao_vinculo){
             $this->session->set_flashdata('message', lang("Ação Vinculada com Sucesso!!!"));   
              echo "<script>history.go(-1)</script>";
                exit;  
            }else{
                $this->session->set_flashdata('message', lang("Ação Atualizada com Sucesso!!!"));
              echo "<script>history.go(-2)</script>";
                exit;
            }
         
    }
    
   public function consultar_acao($id_acao = null)
    {
     
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
            }
            $date_hoje = date('Y-m-d H:i:s');    
            $usuario = $this->session->userdata('user_id');
            $projetos_usuario = $this->site->getProjetoAtualByID_completo($usuario);
                        
           
            $ip = $_SERVER["REMOTE_ADDR"];
           // $this->data['acoes_vinculos'] =  $this->atas_model->getAllAcoesProjeto($projetos_usuario->projeto_atual, $id_acao);//$this->atas_model->getAllAcoes();
            
            $this->data['acoes_vinculadas'] = $this->atas_model->getAllAcoesVinculadasAta($id_acao);
            // echo $this->input->post('prazo') .'<br>';
            $this->data['acoes_arquivos'] = $this->atas_model->getAllArquivosByAcao($id_acao); 
            
           $this->data['eventos'] = $this->projetos_model->getAllEventosItemEventoByProjeto($projetos_usuario->projeto_atual);   
                                                            
            $this->data['users'] = $this->atas_model->getAllUsersSetores(); 
            //$this->data['macro'] = $this->atas_model->getAllMacroProcesso();
            
            $this->data['projetos'] = $this->atas_model->getAllProjetos();      
           // $this->data['ata'] = $id;
           
            $this->data['acoes'] = $this->atas_model->getAllAcoesProjeto( $id_acao);
            
            $this->data['idplano'] = $id_acao;
            //$this->data['acoes'] = $this->atas_model->getPlanoByID($id); 
           //  $this->data['acoes'] = $this->atas_model->getAllAcoesProjeto($projetos_usuario->projeto_atual);
           // $this->load->view($this->theme . 'Atas/editar_acao', $this->data); //manutencao_acao_av_pendente
            $this->page_construct_networking('project/acoes/consultarAcao', $meta, $this->data);
        
      
            
            
         
    }
    /*
     * PLANO DE AÇÃO
     */
    public function manutencao_acao_vinculo_pa($id = null)
    {
        $date_hoje = date('Y-m-d H:i:s');    
        $usuario = $this->session->userdata('user_id');  
        $ip = $_SERVER["REMOTE_ADDR"];
        
        
        $idata = $this->input->post('idatas');
        $idplano = $this->input->post('id');  
            
        $acao_vinculo = $this->input->post('acoes_vinculo');
        $tipo_vinculo = $this->input->post('tipo_vinculo');
        
         if($acao_vinculo){
             if(!$tipo_vinculo){
                $this->session->set_flashdata('error', lang("Selecione o Tipo de Vínculo!!!"));
                echo "<script>history.go(-1)</script>";
                exit;
             }else{
                  //le as ações vinculadas selecionadas
                    //AÇÃO VÍNCULO
                     $dados_acao_vinculo = $this->atas_model->getPlanoByID($acao_vinculo);
                     $inicio_av = $dados_acao_vinculo->data_entrega_demanda;
                     $fim_av = $dados_acao_vinculo->data_termino;   

                     //AÇÃO REFERENCIA
                     $dados_acao = $this->atas_model->getPlanoByID($idplano);
                     $inicio_ar = $dados_acao->data_entrega_demanda;
                     $fim_ar = $dados_acao->data_termino; 
                     
                     if($tipo_vinculo == 'II'){
                        if($inicio_ar != $inicio_av){
                            $this->session->set_flashdata('error', lang("Para manter o vínculo da ação, a data de início da ação deve iniciar na mesma data de início da ação vinculada!!"));
                                echo "<script>history.go(-1)</script>";
                                exit;
                         }
                     }else if($tipo_vinculo == 'IF'){

                         if($inicio_ar < $fim_av){
                            $this->session->set_flashdata('error', lang("Para manter o vínculo da ação, a data de início da ação deve iniciar após a data de término da ação vinculada!!"));
                                echo "<script>history.go(-1)</script>";
                                exit;
                         }

                     }


             }
             
             //FIM VINCULO
             $data_vinculo = array(

                'planos_idplanos' => $idplano,
                'id_vinculo' => $acao_vinculo,
                'tipo' => $tipo_vinculo
            );

             $this->atas_model->AdicionarVinculoAcao($data_vinculo);
             
             $this->session->set_flashdata('message', lang("Ação Vinculada com Sucesso!!!"));
            echo "<script>history.go(-1)</script>";
                exit;
        }else{
            $this->session->set_flashdata('error', lang("Selecione a ação para vincular!!!"));
             echo "<script>history.go(-1)</script>";
                exit;
        }
        
            
     }
    
    public function remove_vinculo_acao($id = null, $id_acao)
    {
         $this->atas_model->deleteVinculo($id);
            
             $this->session->set_flashdata('message', lang("Vinculo apagado com Sucesso!!!"));   
             echo "<script>history.go(-1)</script>";
                exit; 
           
     }
     
    public function manutencao_acao_arquivos($id = null)
    {
        $date_hoje = date('Y-m-d H:i:s');    
        $usuario = $this->session->userdata('user_id');  
        $ip = $_SERVER["REMOTE_ADDR"];
        
        
        $idata = $this->input->post('idatas');
        $idplano = $this->input->post('id');  
            
        $descricao_arquivo = $this->input->post('descricao_arquivo');
        if(!$descricao_arquivo){
            $this->session->set_flashdata('error', lang("Informe a Descrição do Arquivo."));
            echo "<script>history.go(-1)</script>";
            exit;
        }
         $data_arquivo = array(
                
                'plano_id' => $idplano,
                'descricao' => $descricao_arquivo
            );
        
         
         
         if ($_FILES['document']['size'] > 0) {
            $this->load->library('upload');
            $config['upload_path'] = 'assets/uploads/planos/arquivos/';
            $config['allowed_types'] = $this->digital_file_types;
            $config['max_size'] = $this->allowed_file_size;
            $config['overwrite'] = false;
            $config['encrypt_name'] = true;
            $this->upload->initialize($config);
            if (!$this->upload->do_upload('document')) {
                $error = $this->upload->display_errors();
                $this->session->set_flashdata('error', $error);
                redirect($_SERVER["HTTP_REFERER"]);
            }
            $photo = $this->upload->file_name;
            $data_arquivo['anexo'] = $photo;
        }else{
            $this->session->set_flashdata('error', lang("Selecione um arquivo."));
            echo "<script>history.go(-1)</script>";
            exit;
        }
       
        
        
        $this->atas_model->AdicionarArquivoAcao($data_arquivo);
           
         $this->session->set_flashdata('message', lang("Arquivo Cadastrado com Sucesso!!!"));
            echo "<script>history.go(-1)</script>";
                exit;
        
            
     }
     
    public function remove_arquivo_acao($id, $id_acao)
    {
         $this->atas_model->deleteArquivoAcao($id);
            
             $this->session->set_flashdata('message', lang("Arquivo apagado com Sucesso!!!"));   
             echo "<script>history.go(-1)</script>";
                exit; 
           
    } 
    
    /*
     * ATA
     */
    public function manutencao_acao_vinculo_ata($id = null)
    {
        $date_hoje = date('Y-m-d H:i:s');    
        $usuario = $this->session->userdata('user_id');  
        $ip = $_SERVER["REMOTE_ADDR"];
        
        
        $idata = $this->input->post('idatas');
        $idplano = $this->input->post('id');  
            
        $acao_vinculo = $this->input->post('acoes_vinculo');
        $tipo_vinculo = $this->input->post('tipo_vinculo');
        
         if($acao_vinculo){
             if(!$tipo_vinculo){
                $this->session->set_flashdata('error', lang("Selecione o Tipo de Vínculo!!!"));
                echo "<script>history.go(-1)</script>";
                exit;
             }else{
                  //le as ações vinculadas selecionadas
                    //AÇÃO VÍNCULO
                     $dados_acao_vinculo = $this->atas_model->getPlanoByID($acao_vinculo);
                     $inicio_av = $dados_acao_vinculo->data_entrega_demanda;
                     $fim_av = $dados_acao_vinculo->data_termino;   

                     //AÇÃO REFERENCIA
                     $dados_acao = $this->atas_model->getPlanoByID($idplano);
                     $inicio_ar = $dados_acao->data_entrega_demanda;
                     $fim_ar = $dados_acao->data_termino; 
                     
                     if($tipo_vinculo == 'II'){
                        if($inicio_ar != $inicio_av){
                            $this->session->set_flashdata('error', lang("Para manter o vínculo da ação, a data de início da ação deve iniciar na mesma data de início da ação vinculada!!"));
                                echo "<script>history.go(-1)</script>";
                                exit;
                         }
                     }else if($tipo_vinculo == 'IF'){

                         if($inicio_ar < $fim_av){
                            $this->session->set_flashdata('error', lang("Para manter o vínculo da ação, a data de início da ação deve iniciar após a data de término da ação vinculada!!"));
                                echo "<script>history.go(-1)</script>";
                                exit;
                         }

                     }


             }
             
             //FIM VINCULO
             $data_vinculo = array(

                'planos_idplanos' => $idplano,
                'id_vinculo' => $acao_vinculo,
                'tipo' => $tipo_vinculo
            );

             $this->atas_model->AdicionarVinculoAcao($data_vinculo);
             
             $this->session->set_flashdata('message', lang("Ação Vinculada com Sucesso!!!"));
            echo "<script>history.go(-1)</script>";
                exit;
        }else{
            $this->session->set_flashdata('error', lang("Selecione a ação para vincular!!!"));
             echo "<script>history.go(-1)</script>";
                exit;
        }
        
            
     }
    
    public function remove_vinculo_acao_ata($id = null, $id_acao)
    {
         $this->atas_model->deleteVinculo($id);
            
             $this->session->set_flashdata('message', lang("Vinculo apagado com Sucesso!!!"));   
             echo "<script>history.go(-1)</script>";
                exit; 
           
     }
     
    public function manutencao_acao_arquivos_ata($id = null)
    {
        $date_hoje = date('Y-m-d H:i:s');    
        $usuario = $this->session->userdata('user_id');  
        $ip = $_SERVER["REMOTE_ADDR"];
        
        
        $idata = $this->input->post('idatas');
        $idplano = $this->input->post('id');  
            
        $descricao_arquivo = $this->input->post('descricao_arquivo');
        if(!$descricao_arquivo){
            $this->session->set_flashdata('error', lang("Informe a Descrição do Arquivo."));
            echo "<script>history.go(-1)</script>";
            exit;
        }
         $data_arquivo = array(
                
                'plano_id' => $idplano,
                'descricao' => $descricao_arquivo
            );
        
         
         
         if ($_FILES['document']['size'] > 0) {
            $this->load->library('upload');
            $config['upload_path'] = 'assets/uploads/planos/arquivos/';
            $config['allowed_types'] = $this->digital_file_types;
            $config['max_size'] = $this->allowed_file_size;
            $config['overwrite'] = false;
            $config['encrypt_name'] = true;
            $this->upload->initialize($config);
            if (!$this->upload->do_upload('document')) {
                $error = $this->upload->display_errors();
                $this->session->set_flashdata('error', $error);
                redirect($_SERVER["HTTP_REFERER"]);
            }
            $photo = $this->upload->file_name;
            $data_arquivo['anexo'] = $photo;
        }else{
            $this->session->set_flashdata('error', lang("Selecione um arquivo."));
            echo "<script>history.go(-1)</script>";
            exit;
        }
       
        
        
        $this->atas_model->AdicionarArquivoAcao($data_arquivo);
           
         $this->session->set_flashdata('message', lang("Arquivo Cadastrado com Sucesso!!!"));
            echo "<script>history.go(-1)</script>";
                exit;
        
            
     }
     
    public function remove_arquivo_acao_ata($id, $id_acao)
    {
         $this->atas_model->deleteArquivoAcao($id);
            
             $this->session->set_flashdata('message', lang("Arquivo apagado com Sucesso!!!"));   
             echo "<script>history.go(-1)</script>";
                exit; 
           
    } 
     
    public function visualiza_acao($id_acao,  $origem)
    {
     
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
            }
            $date_hoje = date('Y-m-d H:i:s');    
            $usuario = $this->session->userdata('user_id');
            $projetos_usuario = $this->site->getProjetoAtualByID_completo($usuario);
                        
           
            $ip = $_SERVER["REMOTE_ADDR"];
          //  $this->data['acoes_vinculos'] =  $this->atas_model->getAllAcoesProjeto($projetos_usuario->projeto_atual);//$this->atas_model->getAllAcoes();
            
            $this->data['acoes_vinculadas'] = $this->atas_model->getAllAcoesVinculadasAta($id_acao);
            // echo $this->input->post('prazo') .'<br>';
             
            
           $this->data['eventos'] = $this->projetos_model->getAllEventosItemEventoByProjeto($projetos_usuario->projeto_atual);   
                                                            
            $this->data['users'] = $this->atas_model->getAllUsersSetores(); 
            //$this->data['macro'] = $this->atas_model->getAllMacroProcesso();
            
           // $this->data['projetos'] = $this->atas_model->getAllProjetos();      
           // $this->data['ata'] = $id;
            $this->data['origem'] = $origem;
            
            $this->data['idplano'] = $id_acao;
            $acao = $this->atas_model->getPlanoByID($id_acao);
           
            $this->data['acao'] = $acao;
            
            //ATA
            if($origem == 2){
                $id_ata = $acao->idatas;
                $this->data['plano_acao'] = $id_ata;
            }else if($origem == 1){
                // Plano Ação
                $id_plano_acao = $acao->idplano;
                $this->data['plano_acao'] = $id_plano_acao;
                
            }
           
            //$this->data['acoes'] = $this->atas_model->getPlanoByID($id); 
           //  $this->data['acoes'] = $this->atas_model->getAllAcoesProjeto($projetos_usuario->projeto_atual);
           // $this->load->view($this->theme . 'Atas/editar_acao', $this->data); //manutencao_acao_av_pendente
            $this->page_construct_project('project/cadastro_basico_modelo/plano_acao/excluirAcao', $meta, $this->data);
        
         
    }
    
    /*
     * FUNÇÃO PARA CANCELAR AÇÃO
     */
    public function cancelarAcao()
    {
        $this->sma->checkPermissions();

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
         $idacao = $this->input->post('id'); 
         $id_ata = $this->input->post('idatas');
         $origem = $this->input->post('origem');
         $plano_acao = $this->input->post('plano_acao');
         $date_hoje = date('Y-m-d H:i:s');    
         $usuario = $this->session->userdata('user_id');
         $empresa = $this->session->userdata('empresa');
         
        // echo $plano_acao; exit;
        
         $data_acao = array('status' => 'CANCELADO', 'responsavel_ultima_alteracao' => $usuario, 'data_ultima_alteracao' => $date_hoje);
         //$this->owner_model->updateModuloAtual($usuario, $data_modulo);
         
        if ($this->atas_model->updatePlano($idacao, $data_acao, null, null)) {
            
            
            /***********************************************************************************************
            ********************** L O G     A Ç Ã O ****************************************************** 
            ***********************************************************************************************/
           $data_log = array(
                'idplano' => $idacao,
                'data_registro' => $date_hoje,
                'usuario' => $usuario,
                'descricao' => "Ação Cancelada a partir da lista de ações, módulo Project.",
                'antes' => "PENDENTE",
                'depois' => "CANCELADO",
                'empresa' => $empresa
              );
            $this->atas_model->add_logPlano($data_log);
           
           
            /******************************************************************/
            
            $date_hoje = date('Y-m-d H:i:s');
            $usuario = $this->session->userdata('user_id');
            $empresa = $this->session->userdata('empresa');
            $ip = $_SERVER["REMOTE_ADDR"];

            $logdata = array('date' => date('Y-m-d H:i:s'),
                'type' => 'UPDATE',
                'description' => 'Cancelamento da Ação, ID: '.$idacao,
                'userid' => $this->session->userdata('user_id'),
                'ip_address' => $_SERVER["REMOTE_ADDR"],
                'tabela' => 'sig_planos',
                'row' => $idacao,
                'depois' => json_encode($data_acao),
                'modulo' => 'project',
                'funcao' => 'project/cancelarAcao',
                'empresa' => $this->session->userdata('empresa'));
            $this->owner_model->addLog($logdata);
           
            /******************************************************************/
            // REGISTRA A NOTIFICAÇÃO
                    $dados_acao = $this->atas_model->getPlanoByID($idacao);
                    $responsavel_acao = $dados_acao->responsavel;
                    
                    $users_dados = $this->site->geUserByID($responsavel_acao);
                    $nome_usuario = $users_dados->first_name;

                    $data_notificacao = array(
                        'id_from' => $usuario,
                        'id_to' => $responsavel_acao,
                        'title' => "Acão Cancelada",
                        'text' => "A ação $idacao foi cancelada.  ",
                        'lida' => 0,
                        'data' => $date_hoje,
                        'email' => 1,
                        'idplano' => $idacao
                    );
                    $this->atas_model->add_notificacoes($data_notificacao);
            
            /******************************************************************/
            
            $this->session->set_flashdata('message', lang('Ação Cancelada com Sucesso!!!'));
            
            if($origem == 2){
                redirect('Atas/plano_acao/'.$plano_acao.'/2');
            }else if($origem == 1){
                redirect('project/plano_acao_detalhes/'.$plano_acao);
            }else if($origem == 3){
                redirect('project/lista_acoes/51');
            }
            
        }
    }
    
    public function deletePlanoForm()
    {
        $this->sma->checkPermissions();

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
         $idacao = $this->input->post('id'); 
         $id_ata = $this->input->post('idatas'); 
      //  echo 'PLANO :'.$id. '<br> ATA :'.$id_ata ; exit;
        if ($this->atas_model->deletePlano($idacao)) {
            
            $this->session->set_flashdata('message', lang('Plano Apagado com Sucesso!!!'));
            redirect('project/plano_acao_detalhes/'.$id_ata);
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
            
            // echo $menu_id; exit;
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
                 $tipo = "INSERT";
                 $texto = "O usuário $nome, da empresa $nome_empresa realizou um novo cadastro na tabela ID: $tabela_id";
                 $tabela_log = "$tabela_id";
                 $row = "$id_cadastro";
                 $depois = "json_encode($data_campos_cadastro)";
                 $modulo = "project";
                 $funcao = "project/novoCadastroBasico";
                 $this->registraLog($tipo, $texto, $tabela_log, $row, $depois, $modulo, $funcao);


            
            $this->session->set_flashdata('message', lang("Cadastro realizado com Sucesso!!!"));
           // redirect("project/cadastro/$tabela_id/$menu_id");
             echo "<script>history.go(-1)</script>";
                exit;
            
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
                 $data_modulo = array('modulo_atual' => 4, 'menu_atual' => $menu);
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
                 $tipo = "PROJECT";
                 $texto = "O usuário $nome, da empresa $nome_empresa acessou o Cadastro, Menu ID: $menu_atual";
                 $tabela_log = "$tabela";
                 $row = "";
                 $depois = "";
                 $modulo = "project";
                 $funcao = "project/cadastro";
                 $this->registraLog($tipo, $texto, $tabela_log, $row, $depois, $modulo, $funcao);
              
      
            $this->data['menu_id'] = $menu;
            //$this->data['modulos'] = $this->owner_model->getTablesCadastroBasico($tabela);
            $this->data['cadastros'] = $this->owner_model->getTablesCadastroBasico($tabela_nome, $restrito);
            $this->data['campos'] = $this->owner_model->getAllCamposTablesLista($tabela);
            $this->data['cadastrosHabilitados'] = $this->owner_model->getAllCamposTablesCadastro($tabela);
            $this->data['botoes_menu'] = $this->owner_model->getAllBotoesByTabela($tabela);
              $this->page_construct_project('project/cadastro_basico_modelo/index_lista', $meta, $this->data);
         
           // $this->page_construct_owner_sortable('owner/cadastro_basico_modelo/sortable', $meta, $this->data);
           // $this->page_construct_user('owner/empresas/index', $meta, $this->data);
         }
         
         
    }
   
    
    public function novoCadastroBasico($tabela, $menu, $funcao)
    {
        $date_cadastro = date('Y-m-d H:i:s');                            
        
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        $tabela_cadastro = $this->owner_model->getTableById($tabela);
        $tabela_nome = $tabela_cadastro->tabela;
        $this->data['tabela_nome'] = $tabela_nome;
        $this->data['titulo'] = $tabela_cadastro->titulo;
        $this->data['tabela_id'] = $tabela;   
        $this->data['menu_id'] = $menu;
        $this->data['funcao'] = $funcao;
        $this->data['fase'] = $fase;
        $this->data['campos'] = $this->owner_model->getAllCamposTablesLista($tabela);
        $this->data['cadastrosHabilitados'] = $this->owner_model->getAllCamposTablesCadastro($tabela);
        
        $usuario = $this->session->userdata('user_id');                     
        $users_dados = $this->site->geUserByID($usuario);
        $modulo_atual = $users_dados->modulo_atual;
        $menu_atual = $users_dados->modulo_atual;
        $nome = $users_dados->first_name;

        $modulo_atual_id = $users_dados->modulo_atual;
        $empresa = $users_dados->empresa_id;

         $empresa = $this->owner_model->getEmpresaById($empresa);
         $nome_empresa = $empresa->razaoSocial;
        // registra o log de movimentação
         $tipo = "PROJECT";
         $texto = "O usuário $nome, da empresa $nome_empresa acessou a tela de  Cadastro, Menu ID: $menu";
         $tabela_log = "$tabela_nome";
         $row = "";
         $depois = "";
         $modulo = "project";
         $funcao = "project/novoCadastroBasico";
         $this->registraLog($tipo, $texto, $tabela_log, $row, $depois, $modulo, $funcao);
        
        //$this->load->view($this->theme . 'projetos/documentacao/add', $this->data);
        $this->load->view($this->theme . 'project/cadastro_basico_modelo/cadastroBasico', $this->data);
           

            
    }
    
    public function editarCadastro($tabela_id,$cadastro_id, $menu, $funcao)
    {
     
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        
        $this->form_validation->set_rules('id_cadastro', lang("id_cadastro"), 'required');
         
         if ($this->form_validation->run() == true) {
           
             $tabela_id = $this->input->post('tabela_id');
             $tabela_nome = $this->input->post('tabela_nome');
             $menu_id = $this->input->post('menu_id');
             $id_cadastro = $this->input->post('id_cadastro');
             $funcao = $this->input->post('funcao');
            
            
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
              //print_r($data_campos_cadastro); exit;
              $tabela_sig = substr($tabela_nome, 4);
            // echo $tabela_sig; exit;
              $this->owner_model->updateCadastro($id_cadastro, $tabela_sig, $data_campos_cadastro);
              
            $date_hoje = date('Y-m-d H:i:s');    
            $usuario = $this->session->userdata('user_id');
            $empresa = $this->session->userdata('empresa');
            $ip = $_SERVER["REMOTE_ADDR"];

            $logdata = array('date' => date('Y-m-d H:i:s'), 
                'type' => 'UPDATE', 
                'description' => 'Update Cadastro '.$id_cadastro.' da tabela '.$tabela,  
                'userid' => $this->session->userdata('user_id'), 
                'ip_address' => $_SERVER["REMOTE_ADDR"],
                'tabela' => $tabela,
                'row' => $id_cadastro,
                'depois' => json_encode($data_campos_cadastro), 
                'modulo' => 'owner',
                'funcao' => 'owner/editarCadastro',  
                'empresa' => $this->session->userdata('empresa'));
            
            
               $this->owner_model->addLog($logdata);  
           // exit;
            
            $this->session->set_flashdata('message', lang("Alteração realizada com Sucesso!!!"));
            redirect("project/cadastro/$menu_id");
            
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
            $this->data['funcao'] = $funcao;
            
            $this->data['dados_tabela'] = $this->owner_model->getDadosTablesCadastroById($tabela_nome, $cadastro_id);
            //$this->data['cadastros'] = $this->owner_model->getTablesCadastroBasico($tabela_nome);
            //$this->data['campos'] = $this->owner_model->getAllCamposTablesLista($tabela_id);
            $this->data['cadastrosHabilitados'] = $this->owner_model->getAllCamposTablesCadastro($tabela_id);
           // $this->page_construct_owner('owner/cadastro_basico_modelo/editarCadastro','header_empresa', $meta, $this->data);
            $this->load->view($this->theme . 'project/cadastro_basico_modelo/editarCadastro', $this->data);
         }
         
         
    }
    
    public function deletarCadastro($tabela_id,$cadastro_id, $menu, $funcao)
    {
     
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        
        $this->form_validation->set_rules('id_cadastro', lang("id_cadastro"), 'required');
         
         if ($this->form_validation->run() == true) {
           
             $tabela_id = $this->input->post('tabela_id');
             $tabela = $this->input->post('tabela');
             $id_cadastro = $this->input->post('id_cadastro');
             $menu_id = $this->input->post('menu_id');
             $funcao = $this->input->post('funcao');
             
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
              
            $date_hoje = date('Y-m-d H:i:s');    
            $usuario = $this->session->userdata('user_id');
            $empresa = $this->session->userdata('empresa');
            $ip = $_SERVER["REMOTE_ADDR"];

            $logdata = array('date' => date('Y-m-d H:i:s'), 
                'type' => 'DELETE', 
                'description' => 'Apagou o Cadastro '.$id_cadastro.' da tabela '.$tabela,  
                'userid' => $this->session->userdata('user_id'), 
                'ip_address' => $_SERVER["REMOTE_ADDR"],
                'tabela' => $tabela,
                'row' => $id_cadastro,
                'depois' => json_encode($data_campos_cadastro), 
                'modulo' => 'owner',
                'funcao' => 'owner/deletarCadastro',  
                'empresa' => $this->session->userdata('empresa'));
            
            
               $this->owner_model->addLog($logdata);  
           // exit;
            
            $this->session->set_flashdata('message', lang("Cadastro deletado com Sucesso!!!"));
            redirect("project/fases_projetos/$menu_id");
            
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
            $this->data['funcao'] = $funcao;
            
            $this->data['dados_tabela'] = $this->owner_model->getDadosTablesCadastroById($tabela_nome, $cadastro_id);
            //$this->data['cadastros'] = $this->owner_model->getTablesCadastroBasico($tabela_nome);
            //$this->data['campos'] = $this->owner_model->getAllCamposTablesLista($tabela_id);
            $this->data['cadastrosHabilitados'] = $this->owner_model->getAllCamposTablesCadastro($tabela_id);
          //  $this->page_construct_owner('owner/cadastro_basico_modelo/deletarCadastro','header_empresa', $meta, $this->data);
           $this->load->view($this->theme . 'project/cadastro_basico_modelo/deletarCadastro', $this->data);
         }
         
         
    }
    
    
    /************************************************************************************************************
     *************************** CADASTROS DE FASES E EVENTOS E ITENS **********************************************
     ************************************************************************************************************/
    public function fases_projetos($menu, $maximizado)
    {
     
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
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
             
             // SALVA O MENU ATUAL do usuário
             $usuario = $this->session->userdata('user_id');    
             $data_modulo = array('modulo_atual' => 4, 'menu_atual' => $menu);
             $this->owner_model->updateModuloAtual($usuario, $data_modulo);  
             // registra o log de movimentação

                $date_hoje = date('Y-m-d H:i:s');    
                $usuario = $this->session->userdata('user_id');
                $empresa = $this->session->userdata('empresa');
                $ip = $_SERVER["REMOTE_ADDR"];

                $logdata = array('date' => date('Y-m-d H:i:s'), 
                    'type' => 'ACESSO', 
                    'description' => "Acessou o menu $menu do Módulo OWNER",  
                    'userid' => $this->session->userdata('user_id'), 
                    'ip_address' => $_SERVER["REMOTE_ADDR"],
                    'tabela' => '',
                    'row' => '',
                    'depois' => '', 
                    'modulo' => 'owner',
                    'funcao' => 'owner/cadastro',  
                    'empresa' => $this->session->userdata('empresa'));
                    $this->owner_model->addLog($logdata); 
            
             if($maximizado == 2){
                 $maximizado = 2;
             } else{
                 $maximizado = 1;
             }    
            $usuario = $this->session->userdata('user_id');
            $empresa = $this->session->userdata('empresa');
            
            $this->data['empresa'] = $empresa;
            $this->data['usuario'] = $usuario;
            $this->data['maximizado'] = $maximizado;
            $this->data['menu'] = $menu;
           // $this->data['tabela_id'] = $tabela;
            $this->data['projetos'] = $this->projetos_model->getProjetoAtualByID_completo();
            $this->data['cadastros'] = $this->projetos_model->getAllFasesProjetos();
              
            $this->page_construct_project('project/escopo/faseProjeto/index', $meta, $this->data);
           // $this->page_construct_user('owner/empresas/index', $meta, $this->data);
         }
         
         
    }
    
    public function novoCadastroFase()
    {
        
         $this->form_validation->set_rules('id_cadastroEvento', lang("id_cadastro"), 'required');
         
         if ($this->form_validation->run() == true) {
          
             $evento = $this->input->post('evento');
             $periodo_evento = $this->input->post('periodo_evento');
             $responsavel = $this->input->post('responsavel');
             $id_fase = $this->input->post('projeto_id');
             $validar_acao = $this->input->post('validar_acoes');
             
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
             
             
             
             $usuario = $this->session->userdata('user_id');
             $users_dados = $this->site->geUserByID($usuario);
             $projeto_atual_id = $users_dados->projeto_atual;
            
            $data_evento = array(
                'data_inicio' => $data_tratado_de,
                'data_fim' => $data_tratado_ate,
                'nome_fase' => $evento,
                'id_projeto' => $projeto_atual_id,
                'responsavel_aprovacao' => $responsavel,
                'validar_acoes_fase' => $validar_acao
            );
          //  print_r($data_evento); exit;
            $tabela_sig = 'fases_projeto';
            $id_cadastro =  $this->owner_model->addCadastro($tabela_sig, $data_evento);
              
              
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
            $tabela_id = 21;
            $menu_id = 63;
            $this->session->set_flashdata('message', lang("Cadastro realizado com Sucesso!!!"));
           // redirect("project/fases_projetos/$tabela_id/$menu_id");
            echo "<script>history.go(-2)</script>";
         }else{
        
        $date_cadastro = date('Y-m-d H:i:s');                           
        
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
       
        //$this->load->view($this->theme . 'projetos/documentacao/add', $this->data);
       // $this->page_construct_project_fases('project/escopo/faseProjeto/novaFase', $meta, $this->data);
        $this->load->view($this->theme . 'project/escopo/faseProjeto/cadastro', $this->data);
           
         }
            
    }
    
    public function editar_fases_projetos($fase)
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
             $validar_acao = $this->input->post('validar_acoes');
             
              
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
                'responsavel_aprovacao' => $responsavel,
                'validar_acoes_fase' => $validar_acao
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
           // redirect("project/$funcao/$tabela_id/$menu_id");
             echo "<script>history.go(-2)</script>";
         }else{
             
            $tabela_cadastro = $this->owner_model->getTableById($tabela);
            $tabela_nome = $tabela_cadastro->tabela;
            

            $this->data['fase'] = $fase;
           
            
               

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
            

            
            $this->load->view($this->theme . 'project/escopo/faseProjeto/editar', $this->data);
         }
         
         
    }
    
    public function organizarFases()
    {
        
         $this->form_validation->set_rules('ordernar_fase', lang("id_cadastro"), 'required');
         
         if ($this->form_validation->run() == true) {
         
            $ordem_fase = $this->input->post('ordem_fase');
            $codigo_fase = $this->input->post('codigo_fase');
            
         
             $ordem = 1;
            foreach ($codigo_fase as $codigo) {
                
                
                $data_evento = array(
                'ordem' => $ordem
                );
                
                 $tabela_sig = 'fases_projeto';
                $this->owner_model->updateCadastro($codigo, $tabela_sig, $data_evento);
                
                $date_hoje = date('Y-m-d H:i:s');    
                $usuario = $this->session->userdata('user_id');
                $empresa = $this->session->userdata('empresa');
                $ip = $_SERVER["REMOTE_ADDR"];

                $logdata = array('date' => date('Y-m-d H:i:s'), 
                    'type' => 'UPDATE', 
                    'description' => 'ordenou o cadastro de Fase',  
                    'userid' => $this->session->userdata('user_id'), 
                    'ip_address' => $_SERVER["REMOTE_ADDR"],
                    'tabela' => 'sig_fases_projeto',
                    'row' => $codigo,
                    'depois' => json_encode($data_evento), 
                    'modulo' => 'project',
                    'funcao' => 'project/organizarFases',  
                    'empresa' => $this->session->userdata('empresa'));

                   $this->owner_model->addLog($logdata);  
                
                $ordem++;   
            }
            
            
           // exit;
           
            $this->session->set_flashdata('message', lang("Cadastro atualizado com Sucesso!!!"));
           // redirect("project/fases_projetos/$tabela_id/$menu_id");
            echo "<script>history.go(-2)</script>";
         }else{
        
            $date_cadastro = date('Y-m-d H:i:s');                           
        
         $this->data['fases'] = $this->projetos_model->getAllFasesProjetos();
       
        $this->page_construct_project_ordena('project/escopo/faseProjeto/organizar_fase', $meta, $this->data);
       // $this->load->view($this->theme . 'project/escopo/faseProjeto/organizar_fase', $this->data);
           
         }
            
    }
    
    public function novoCadastroEvento($fase)
    {
        
         $this->form_validation->set_rules('id_cadastroEvento', lang("id_cadastro"), 'required');
         
         if ($this->form_validation->run() == true) {
           
             $evento = $this->input->post('evento');
             $periodo_evento = $this->input->post('periodo_evento');
             $responsavel = $this->input->post('responsavel');
             $id_fase = $this->input->post('fase');
             $validar_acao = $this->input->post('validar_acoes');
             
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
             
            
             
             $usuario = $this->session->userdata('user_id');
             $users_dados = $this->site->geUserByID($usuario);
             $projeto_atual_id = $users_dados->projeto_atual;
            
            $data_evento = array(
                'data_inicio' => $data_tratado_de,
                'data_fim' => $data_tratado_ate,
                'projeto' => $projeto_atual_id,
                'fase_id' => $id_fase,
                'responsavel' => $responsavel,
                'data_cadastro' => date('Y-m-d H:i:s'),
                'usuario' => $this->session->userdata('user_id'),
                'nome_evento' => $evento,
                'validar_acoes_evento' => $validar_acao
            );
             //print_r($data_evento); exit;
            $tabela_sig = 'eventos';
            $id_cadastro =  $this->owner_model->addCadastro($tabela_sig, $data_evento);
              
              
            $date_hoje = date('Y-m-d H:i:s');    
            $empresa = $this->session->userdata('empresa');
            $ip = $_SERVER["REMOTE_ADDR"];

            $logdata = array('date' => date('Y-m-d H:i:s'), 
                'type' => 'INSERT', 
                'description' => 'Cadastro de um novo evento',  
                'userid' => $this->session->userdata('user_id'), 
                'ip_address' => $_SERVER["REMOTE_ADDR"],
                'tabela' => 'sig_eventos',
                'row' => $id_cadastro,
                'depois' => json_encode($data_evento), 
                'modulo' => 'project',
                'funcao' => 'project/novoCadastroEvento',  
                'empresa' => $this->session->userdata('empresa'));
           
               $this->owner_model->addLog($logdata);  
           // exit;
            
            $this->session->set_flashdata('message', lang("Cadastro realizado com Sucesso!!!"));
           // redirect("project/$funcao/$tabela_id/$menu_id");
            echo "<script>history.go(-2)</script>";
         }else{
        
        $date_cadastro = date('Y-m-d H:i:s');                           
        
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
       
        $this->data['fase'] = $fase;
        $usuario = $this->session->userdata('user_id');                     
       
        //$this->load->view($this->theme . 'projetos/documentacao/add', $this->data);
        $this->load->view($this->theme . 'project/escopo/eventos/cadastro', $this->data);
           
         }
            
    }
    
    public function editar_evento_projetos($evento)
    {
     
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        
        $this->form_validation->set_rules('id_cadastroEvento', lang("id_cadastro"), 'required');
         
         if ($this->form_validation->run() == true) {
           
             
             $evento = $this->input->post('evento');
             $periodo_evento = $this->input->post('periodo_evento');
             $responsavel = $this->input->post('responsavel');
             $evento_id = $this->input->post('evento_id');
             $validar_acao = $this->input->post('validar_acoes');
             
             
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
                'nome_evento' => $evento,
                'responsavel' => $responsavel,
                 'validar_acoes_evento' => $validar_acao
            );
           
            $tabela_sig = 'eventos';
            $this->owner_model->updateCadastro($evento_id, $tabela_sig, $data_evento);
              
            $date_hoje = date('Y-m-d H:i:s');    
            $usuario = $this->session->userdata('user_id');
            $empresa = $this->session->userdata('empresa');
            $ip = $_SERVER["REMOTE_ADDR"];

            $logdata = array('date' => date('Y-m-d H:i:s'), 
                'type' => 'UPDATE', 
                'description' => 'Alterou o Evento de projeto Id: '.$evento_id,  
                'userid' => $this->session->userdata('user_id'), 
                'ip_address' => $_SERVER["REMOTE_ADDR"],
                'tabela' => $tabela_sig,
                'row' => $evento_id,
                'depois' => json_encode($data_evento), 
                'modulo' => 'project',
                'funcao' => 'project/editar_evento_projetos',  
                'empresa' => $this->session->userdata('empresa'));
           
               $this->owner_model->addLog($logdata);  
           // exit;
            
            $this->session->set_flashdata('message', lang("Cadastro atualizado com Sucesso!!!"));
          //  redirect("project/$funcao/$tabela_id/$menu_id");
            echo "<script>history.go(-2)</script>"; 
         }else{
             
             $this->data['evento'] = $evento;
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
           
            
            $this->load->view($this->theme . 'project/escopo/eventos/editar', $this->data);
         }
         
         
    }
    
    public function excluir_evento_projetos($evento)
    {
     
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        
        $this->form_validation->set_rules('id_cadastroEvento', lang("id_cadastro"), 'required');
         
         if ($this->form_validation->run() == true) {
           
             
             $evento = $this->input->post('evento');
             $periodo_evento = $this->input->post('periodo_evento');
             $responsavel = $this->input->post('responsavel');
             $evento_id = $this->input->post('evento_id');
             
             
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
            
             $itemevento = $this->input->post('itemevento');
             $acoesitem = $this->input->post('acoesitem');
             
             if($acoesitem == 0){
                 
                 if($itemevento > 0){
                     $this->projetos_model->deleteItemEventoByEvento($evento_id);
                     $this->owner_model->deleteCadastro($evento_id, 'eventos');
                 
                     
                 }else{
                     $this->owner_model->deleteCadastro($evento_id, 'eventos');
                 }
                 
                 
             }else if($acoesitem > 0){
                /*
                 * PRECISA DESENVOLVER ESTA PARTE
                 */    

                // inativa acoes ligada ao item
                 
                // inativa os itens ligado ao evento
                 $data_evento = array(
                'data_inicio' => $data_tratado_de,
                'data_fim' => $data_tratado_ate,
                'nome_evento' => $evento,
                'responsavel' => $responsavel
            );
           
            $tabela_sig = 'eventos';
                 // inativa o evento
              //   $this->owner_model->updateCadastro($evento_id, $tabela_sig, $data_evento);
             }
             
           
            
              
            $date_hoje = date('Y-m-d H:i:s');    
            $usuario = $this->session->userdata('user_id');
            $empresa = $this->session->userdata('empresa');
            $ip = $_SERVER["REMOTE_ADDR"];

            $logdata = array('date' => date('Y-m-d H:i:s'), 
                'type' => 'DELETE', 
                'description' => 'Deletou o Evento de projeto Id: '.$evento_id,  
                'userid' => $this->session->userdata('user_id'), 
                'ip_address' => $_SERVER["REMOTE_ADDR"],
                'tabela' => $tabela_sig,
                'row' => $evento_id,
                'depois' => json_encode($data_evento), 
                'modulo' => 'project',
                'funcao' => 'project/excluir_evento_projetos',  
                'empresa' => $this->session->userdata('empresa'));
           
               $this->owner_model->addLog($logdata);  
           // exit;
            
            $this->session->set_flashdata('message', lang("Cadastro apagado com Sucesso!!!"));
             echo "<script>history.go(-1)</script>"; 
            
         }else{
             
            $this->data['evento'] = $evento;
            $this->data['menu'] = "cadastro";
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
            
            $dadosItens  = $this->projetos_model->getItemEventoByEvento($evento);
            $quantidade_item =  $dadosItens->quantidade;
            
            //VERIFICA SE TEM ACAO
            $dadosItensAcao  = $this->projetos_model->getIAcoestemEventoByEvento($evento);
            $quantidade_item_acao = $dadosItensAcao->quantidade;
            
            $this->data['itemevento'] = $quantidade_item;
            $this->data['acoesitem'] = $quantidade_item_acao;
            
            if($quantidade_item_acao > 0){
                $this->load->view($this->theme . 'project/escopo/eventos/excluir_aviso_acao', $this->data);
            }else{
                if($quantidade_item > 0){
                $this->load->view($this->theme . 'project/escopo/eventos/excluir_aviso', $this->data);    
                }else if($quantidade_item == 0){
                $this->load->view($this->theme . 'project/escopo/eventos/excluir', $this->data);
                }
            }    
         }
         
         
    }
    
    public function organizarEventos($fase)
    {
        
        $this->form_validation->set_rules('ordernar_evento', lang("id_cadastro"), 'required');
         
        if ($this->form_validation->run() == true) {
         
            $ordem_fase = $this->input->post('ordem_fase');
            $codigo_fase = $this->input->post('codigo_fase');
            
           
         
             $ordem = 1;
            foreach ($codigo_fase as $codigo) {
                
                
                $data_evento = array(
                'ordem' => $ordem
                );
                
                $tabela_sig = 'eventos';
                $this->owner_model->updateCadastro($codigo, $tabela_sig, $data_evento);
                
                $date_hoje = date('Y-m-d H:i:s');    
                $usuario = $this->session->userdata('user_id');
                $empresa = $this->session->userdata('empresa');
                $ip = $_SERVER["REMOTE_ADDR"];

                $logdata = array('date' => date('Y-m-d H:i:s'), 
                    'type' => 'UPDATE', 
                    'description' => 'ordenou o cadastro de eventos',  
                    'userid' => $this->session->userdata('user_id'), 
                    'ip_address' => $_SERVER["REMOTE_ADDR"],
                    'tabela' => 'sig_eventos',
                    'row' => $codigo,
                    'depois' => json_encode($data_evento), 
                    'modulo' => 'project',
                    'funcao' => 'project/organizarEventos',  
                    'empresa' => $this->session->userdata('empresa'));

                   $this->owner_model->addLog($logdata);  
                
                $ordem++;   
            }
            
            
           // exit;
           
            $this->session->set_flashdata('message', lang("Cadastro atualizado com Sucesso!!!"));
           // redirect("project/fases_projetos/$tabela_id/$menu_id");
            echo "<script>history.go(-2)</script>";
         }else{
        
         $date_cadastro = date('Y-m-d H:i:s');                           
        
         $this->data['eventos'] = $this->projetos_model->getAllEventosProjetoByFase($fase);
         $this->data['fase_id'] = $fase;
       
         $this->page_construct_project_ordena('project/escopo/eventos/organizar_evento', $meta, $this->data);
         // $this->load->view($this->theme . 'project/escopo/faseProjeto/organizar_fase', $this->data);
           
        }
            
    }
    
    public function novoItemEvento($evento)
    {
        
         $this->form_validation->set_rules('id_itemEvento', lang("id_cadastro"), 'required');
         
         if ($this->form_validation->run() == true) {
           
             
             $evento = $this->input->post('evento');
             $periodo_evento = $this->input->post('periodo_evento');
             $horas_previstas = $this->input->post('horas_previstas');
             $id_fase = $this->input->post('fase');
             $validar_acao = $this->input->post('validar_acoes');
             $responsavel = $this->input->post('responsavel');
             
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
             
            $empresa = $this->session->userdata('empresa');
            $data_evento = array(
                'dt_inicio' => $data_tratado_de,
                'dt_fim' => $data_tratado_ate,
                'evento' => $id_fase,
                'descricao' => $evento,
                'horas_previstas' => $horas_previstas,
                'validar_acoes_item' => $validar_acao,
                'responsavel' => $responsavel,
                'empresa' => $empresa
            );
             
            $tabela_sig = 'item_evento';
            $id_cadastro =  $this->owner_model->addCadastro($tabela_sig, $data_evento);
              
              
            $date_hoje = date('Y-m-d H:i:s');    
            $usuario = $this->session->userdata('user_id');
            $empresa = $this->session->userdata('empresa');
            $ip = $_SERVER["REMOTE_ADDR"];

            $logdata = array('date' => date('Y-m-d H:i:s'), 
                'type' => 'INSERT', 
                'description' => 'Cadastro de um novo Item de Evento',  
                'userid' => $usuario, 
                'ip_address' => $_SERVER["REMOTE_ADDR"],
                'tabela' => 'sig_item_evento',
                'row' => $id_cadastro,
                'depois' => json_encode($data_evento), 
                'modulo' => 'project',
                'funcao' => 'project/novoItemEvento',  
                'empresa' => $empresa);
           
               $this->owner_model->addLog($logdata);  
           // exit;
            
            $this->session->set_flashdata('message', lang("Cadastro realizado com Sucesso!!!"));
            //redirect("project/$funcao/$tabela_id/$menu_id");
            echo "<script>history.go(-2)</script>"; 
         }else{
        
      
        $this->data['fase'] = $evento;
       
        //$this->load->view($this->theme . 'projetos/documentacao/add', $this->data);
        $this->load->view($this->theme . 'project/escopo/item_evento/cadastro', $this->data);
           
         }
            
    }
    
    public function editar_item_evento_projetos($item_id)
    {
     
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        
        $this->form_validation->set_rules('id_cadastroEvento', lang("id_cadastro"), 'required');
         
         if ($this->form_validation->run() == true) {
           
             
             $evento = $this->input->post('evento');
             $periodo_evento = $this->input->post('periodo_evento');
             $item_id = $this->input->post('item_id');
             $horas_previstas = $this->input->post('horas_previstas');
             $validar_acao = $this->input->post('validar_acoes');
             $responsavel = $this->input->post('responsavel');
             
             
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
             
             
            $data_evento = array(
                'dt_inicio' => $data_tratado_de,
                'dt_fim' => $data_tratado_ate,
                'descricao' => $evento,
                'horas_previstas' => $horas_previstas,
                'validar_acoes_item' => $validar_acao,
                'responsavel' => $responsavel
            );
           
            $tabela_sig = 'item_evento';
            $this->owner_model->updateCadastro($item_id, $tabela_sig, $data_evento);
              
            $date_hoje = date('Y-m-d H:i:s');    
            $usuario = $this->session->userdata('user_id');
            $empresa = $this->session->userdata('empresa');
            $ip = $_SERVER["REMOTE_ADDR"];

            $logdata = array('date' => date('Y-m-d H:i:s'), 
                'type' => 'UPDATE', 
                'description' => 'Alterou o Item de Evento Id: '.$item_id,  
                'userid' => $this->session->userdata('user_id'), 
                'ip_address' => $_SERVER["REMOTE_ADDR"],
                'tabela' => $tabela_sig,
                'row' => $item_id,
                'depois' => json_encode($data_evento), 
                'modulo' => 'project',
                'funcao' => 'project/editar_item_evento_projetos',  
                'empresa' => $this->session->userdata('empresa'));
           
               $this->owner_model->addLog($logdata);  
           // exit;
            
            $this->session->set_flashdata('message', lang("Cadastro atualizado com Sucesso!!!"));
            //redirect("project/$funcao/$tabela_id/$menu_id");
            echo "<script>history.go(-2)</script>";
            
         }else{
             
          
            $this->data['item'] = $item_id;
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
            
            $this->load->view($this->theme . 'project/escopo/item_evento/editar', $this->data);
         }
         
         
    }
    
    public function organizarItemEventos($id_evento)
    {
        
        $this->form_validation->set_rules('ordernar_item', lang("id_cadastro"), 'required');
         
        if ($this->form_validation->run() == true) {
         
            $ordem_fase = $this->input->post('ordem_fase');
            $codigo_fase = $this->input->post('codigo_fase');
            
             $ordem = 1;
            foreach ($codigo_fase as $codigo) {
                
                
                $data_evento = array(
                'ordem' => $ordem
                );
                
                $tabela_sig = 'item_evento';
                $this->owner_model->updateCadastro($codigo, $tabela_sig, $data_evento);
                
                $date_hoje = date('Y-m-d H:i:s');    
                $usuario = $this->session->userdata('user_id');
                $empresa = $this->session->userdata('empresa');
                $ip = $_SERVER["REMOTE_ADDR"];

                $logdata = array('date' => date('Y-m-d H:i:s'), 
                    'type' => 'UPDATE', 
                    'description' => 'ordenou o cadastro de item_eventos',  
                    'userid' => $this->session->userdata('user_id'), 
                    'ip_address' => $_SERVER["REMOTE_ADDR"],
                    'tabela' => 'sig_item_evento',
                    'row' => $codigo,
                    'depois' => json_encode($data_evento), 
                    'modulo' => 'project',
                    'funcao' => 'project/organizarItemEventos',  
                    'empresa' => $this->session->userdata('empresa'));

                   $this->owner_model->addLog($logdata);  
                
                $ordem++;   
            }
            
            
           // exit;
           
            $this->session->set_flashdata('message', lang("Cadastro atualizado com Sucesso!!!"));
           // redirect("project/fases_projetos/$tabela_id/$menu_id");
            echo "<script>history.go(-2)</script>";
         }else{
        
         $date_cadastro = date('Y-m-d H:i:s');                           
        
         $this->data['eventos_itens'] = $this->projetos_model->getAllItemEventosProjeto($id_evento);
         $this->data['evento_id'] = $id_evento;
       
         $this->page_construct_project_ordena('project/escopo/item_evento/organizar_item', $meta, $this->data);
         // $this->load->view($this->theme . 'project/escopo/faseProjeto/organizar_fase', $this->data);
           
        }
            
    }
    
    /************************************************************************************************************
     *************************** ESCOPO DO PROJETO **********************************************
     ************************************************************************************************************/
    
    
     public function escopo($id = null) {
        $this->sma->checkPermissions();
        
       
        if ($this->Settings->version == '2.3') {
            $this->session->set_flashdata('warning', 'Please complete your update by synchronizing your database.');
            redirect('sync');
        }
        
        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        
        $this->data['tipos'] = $this->projetos_model->getAllFasesProjeto();
     
       
       // $this->page_construct_project('project/escopo/escopo/escopo', $meta, $this->data);
        
        $this->load->view($this->theme . 'project/escopo/escopo/escopo', $this->data);
        //$this->load->view($this->theme . 'menu', $this->data);
    }
    
    
    /*****************************************************************************************************************
     ******************************************E A P ***************************************************************** 
     *****************************************************************************************************************/
     public function eap() {
        $this->sma->checkPermissions();
        
      
        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        
        $usuario = $this->session->userdata('user_id');
        $projetos = $this->projetos_model->getProjetoAtualByID_completo();
        $id_projeto = $projetos->id;
        
        $this->data['tipos'] = $this->projetos_model->getAllFasesProjeto();
       // $bc = array(array('link' => '#', 'page' => lang('Menu')));
       // $meta = array('page_title' => lang('Menu'), 'bc' => $bc);
       // $this->page_construct('menu', $meta, $this->data);
        
         //$this->page_construct_project('project/escopo/eap/eap', $meta, $this->data);
         $this->load->view($this->theme . 'project/escopo/eap/eap', $this->data);
        //$this->load->view($this->theme . 'menu', $this->data);
    }
    
    
    
    
    /************************************************************************************************************
     ****************************************** LISTA DE AÇÕES **********************************************
     ************************************************************************************************************/
    public function lista_acoes($menu)
    {
        $this->sma->checkPermissions();

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        
              
            // SALVA O MÓDULO ATUAL do usuário
             $usuario = $this->session->userdata('user_id');    
             $data_modulo = array('modulo_atual' => 4, 'menu_atual' => $menu);
             $this->owner_model->updateModuloAtual($usuario, $data_modulo);

            // registra o log de movimentação

            $date_hoje = date('Y-m-d H:i:s');    
            $usuario = $this->session->userdata('user_id');
            $empresa = $this->session->userdata('empresa');
            $ip = $_SERVER["REMOTE_ADDR"];

            $logdata = array('date' => date('Y-m-d H:i:s'), 
                'type' => 'ACESSO', 
                'description' => "Acessou o menu $menu do Módulo OWNER",  
                'userid' => $this->session->userdata('user_id'), 
                'ip_address' => $_SERVER["REMOTE_ADDR"],
                'tabela' => '',
                'row' => '',
                'depois' => '', 
                'modulo' => 'owner',
                'funcao' => 'owner/cadastro',  
                'empresa' => $this->session->userdata('empresa'));
                $this->owner_model->addLog($logdata); 
            
            
            $responsavel_filtro = $this->input->post('responsavel_filtro');
            $status_filtro = $this->input->post('status_filtro');

            if($tipo == 1){
                $status_filtro = 'CONCLUÍDO';
            }else if($tipo == 2){
                $status_filtro = 'PENDENTE';
            }else if($tipo == 3){
                $status_filtro = 'ATRASADO';
            }else if($tipo == 4){
                $status_filtro = 'AGUARDANDO VALIDAÇÃO';
            }else if($tipo == 5){
                $status_filtro = 'CANCELADO';
            }

            $tipo = 1;
            $this->data['tipo'] = $tipo;
            $this->data['responsavel_filtro'] = $responsavel_filtro; //footer
            $this->data['status_filtro'] = $status_filtro;  
            $this->data['menu'] = $menu;
            $this->data['retorno'] = 0;
            $this->data['responsaveis'] = $this->atas_model->getAllUsersSetores();         
            $this->data['planos'] = $this->projetos_model->getAllAcoesByProjetoAtual($tipo,$responsavel_filtro, $status_filtro);
            

            $this->page_construct_project('project/acoes/index', $meta, $this->data);
           // $this->page_construct_user('owner/empresas/index', $meta, $this->data);
         
         
         
    }
    
    
    
    /************************************************************************************************************
     ******************************* AÇÕES AGUARDANDO VALIDAÇÃO**********************************************
     ************************************************************************************************************/
    public function acoes_aguardando_validacao($menu)
    {
             $this->sma->checkPermissions();

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
               
            // SALVA O MÓDULO ATUAL do usuário
            $usuario = $this->session->userdata('user_id');    
            $data_modulo = array('modulo_atual' => 4, 'menu_atual' => $menu);
            $this->owner_model->updateModuloAtual($usuario, $data_modulo);

            // registra o log de movimentação

            $date_hoje = date('Y-m-d H:i:s');    
            $usuario = $this->session->userdata('user_id');
            $empresa = $this->session->userdata('empresa');
            $ip = $_SERVER["REMOTE_ADDR"];

            $logdata = array('date' => date('Y-m-d H:i:s'), 
                'type' => 'ACESSO', 
                'description' => "Acessou o menu $menu do Módulo PROJECT",  
                'userid' => $this->session->userdata('user_id'), 
                'ip_address' => $_SERVER["REMOTE_ADDR"],
                'tabela' => '',
                'row' => '',
                'depois' => '', 
                'modulo' => 'project',
                'funcao' => 'project/acoes_aguardando_validacao',  
                'empresa' => $this->session->userdata('empresa'));
                $this->owner_model->addLog($logdata); 
            
            $responsavel_filtro = $this->input->post('responsavel_filtro');
            $status_filtro = $this->input->post('status_filtro');

            if($tipo == 1){
                $status_filtro = 'CONCLUÍDO';
            }else if($tipo == 2){
                $status_filtro = 'PENDENTE';
            }else if($tipo == 3){
                $status_filtro = 'ATRASADO';
            }else if($tipo == 4){
                $status_filtro = 'AGUARDANDO VALIDAÇÃO';
            }else if($tipo == 5){
                $status_filtro = 'CANCELADO';
            }

            $tipo = 2;
            $this->data['tipo'] = $tipo;
            $this->data['retorno'] = 1;
            $this->data['responsavel_filtro'] = $responsavel_filtro; //footer
            $this->data['status_filtro'] = $status_filtro;  
            $this->data['menu'] = $menu;  
            $this->data['responsaveis'] = $this->atas_model->getAllUsersSetores();         
            $this->data['planos'] = $this->projetos_model->getAllAcoesByProjetoAtual($tipo,$responsavel_filtro, $status_filtro);


            $this->page_construct_project('project/acoes/index', $meta, $this->data);
         
         
         
    }
    
    
    public function retorno_acao($id = null)
    {
     
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        
        $this->form_validation->set_rules('idplano', lang("idplano"), 'required');
        $this->form_validation->set_rules('validar', lang("cadastrar"), 'required');
        
         if ($this->form_validation->run() == true) {
             
            $date_hoje = date('Y-m-d H:i:s');    
            $usuario = $this->session->userdata('user_id');  
            $idplano = $this->input->post('idplano');
            $sequencial = $this->input->post('sequencial');
            $ip = $_SERVER["REMOTE_ADDR"];
            $envia_email = 1;
            
            $status = $this->input->post('status');
            $observacao = trim($this->input->post('observacao')); 
            
            $acao = $this->atas_model->getPlanoByID($idplano);
            $responsavel = $acao->responsavel;  //RESPONSÁVEL ATUAL
            $setor_responsavel_atual = $acao->setor;
            
            
            
           
            // HISTÓRICO DA AÇÃO
            $data_historicoAcao = array(
                'data_envio' => $date_hoje,
                'usuario' => $usuario,
                'idplanos' => $idplano,
                'observacao' => 'Resposta '.' - '.$observacao,
                'ip' => $ip,
                'tipo' => 1
            );
            $this->atas_model->add_historicoPlanoAcao($data_historicoAcao);
           
            // DADOS DO USUÁRIO QUE ESTÁ ENVIANDO
            $users_dados = $this->site->geUserByID($usuario);
            $nome_usuario = $users_dados->first_name;
            
            
            
            $title = "Retorno de Ação ";
            if($status == 'CONCLUÍDO'){
                $texto_msg = "A ação $sequencial foi CONCLUÍDA com sucesso, pelo usuário $nome_usuario, Parabéns! ";
                
                $data_acao = array(
                'status' => $status,
                'andamento' => 100
                );
                
            }else{
                $texto_msg = "A ação $sequencial foi respondida pelo usuário $nome_usuario e continua PENDENTE. Verifique as observações na Aba comunicação. ";
                
                $data_acao = array(
                'status' => $status
                );
            }
            
            if($observacao){
                $texto_msg .= "<br> Mensagem: $observacao";
            }
            
            
            $this->atas_model->updatePlano($idplano, $data_acao);
            
            $empresa = $this->session->userdata('empresa');
             // ENVIA NOTIFICAÇÃO - QDO A AÇÃO NÃO VEM DE PROJETO
            $data_notificacao = array(
                'id_from' => $usuario,
                'id_to' => $responsavel,
                'title' => "$title",
                'text' => "$texto_msg",
                'lida' => 0,
                'data' => $date_hoje,
                'email' => $envia_email,
                'idplano' => $idplano,
                'empresa' => $empresa
            );
            $this->atas_model->add_notificacoes($data_notificacao);

            //ENVIA E-MAIL - QDO A AÇÃO NÃO VEM DE PROJETO
            $data_email = array(
                'id_from' => $usuario,
                'id_to' => $responsavel,
                'title' => "$title",
                'text' => "$texto_msg",
                'lida' => 0,
                'data' => $date_hoje,
                'referencia' => "project > retorno_acao",
                'idplano' => $idplano,
                'empresa' => $empresa,
                'enviado' => 0 
                );
            $this->atas_model->add_email($data_email);

            
           
            
            $logdata = array('date' => date('Y-m-d H:i:s'), 
            'type' => 'RETORNO DE AÇÃO', 
            'description' => "O EDP $nome_usuario enviou o retorno da ação: $sequencial. ",  
            'userid' => $this->session->userdata('user_id'), 
            'ip_address' => $_SERVER["REMOTE_ADDR"],
            'tabela' => '',
            'row' => '',
            'depois' => '', 
            'modulo' => 'Project',
            'funcao' => 'Project/retorno_acao',  
            'empresa' => $this->session->userdata('empresa'));
            $this->owner_model->addLog($logdata);  
            
            $this->session->set_flashdata('message', lang("Retorno da ação enviada com Sucesso!!!"));
            redirect("project/acoes_aguardando_validacao/78");
            
         }else{
            $this->data['idplano'] = $id;
            $this->data['acoes'] = $this->atas_model->getAllAcoes($id);
            $this->load->view($this->theme . 'project/acoes/acaoRetorno', $this->data);
            
         }
    }
    
    /*
     * GANTT
     */
    public function ganttPlano($tipo, $idplano)
    {
                $this->sma->checkPermissions();

        $date_cadastro = date('Y-m-d H:i:s');                           
        
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        
        $this->data['tipo'] = $tipo;
        $this->data['idplano'] = $idplano;
        
        if($tipo == 1){
          //ATAS
             $this->data['fases'] = $this->atas_model->getAllFasePlano($idplano, $tipo);
        }else if($tipo == 2){
            //PLANO DE AÇÃO
            $this->data['fases'] = $this->atas_model->getAllFasePlano($idplano, $tipo);
           
        }
        $usuario = $this->session->userdata('user_id');                     
       
        //$this->load->view($this->theme . 'projetos/documentacao/add', $this->data);
        $this->load->view($this->theme . 'project/cadastro_basico_modelo/plano_acao/gantt', $this->data);
           

            
    }
    
    public function ganttProjeto( $menu)
    {
        $this->sma->checkPermissions();
        
        $date_cadastro = date('Y-m-d H:i:s');                           
        
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        
        $tipo = 3;
        $idplano = "0";
        
    
        $this->data['fases'] = $this->atas_model->getAllFaseProjeto();
          
        
        $usuario = $this->session->userdata('user_id');                     
       
        //$this->load->view($this->theme . 'projetos/documentacao/add', $this->data);
        $this->load->view($this->theme . 'project/tempo/gantt/gantt_projeto', $this->data);
        //$this->page_construct_project('project/escopo/eap/eap', $meta, $this->data);   

            
    }
    
    
    /*
     * RELATÓRIOS
     */
    
     public function status_report()
    {
        $this->sma->checkPermissions();
        $data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        
        // $this->load->library("phpmailer_library");
        //$objMail = $this->phpmailer_library->load();
        
        $usuario = $this->session->userdata('user_id');
        $projetos_usuario = $this->site->getProjetoAtualByID_completo($usuario);
        $this->data['status_report'] = $this->reports_model->getAllStatusReportByProjeto($projetos_usuario->projeto_atual);
        
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('reports')));
        $meta = array('page_title' => lang('reports'), 'bc' => $bc);
        $this->page_construct_project('project/reports/status_report/index', $meta, $this->data);

    }
    
}
