<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Login_Projetos extends MY_Controller
{

    function __construct()
    {
        parent::__construct();

        if (!$this->loggedIn) {
            $this->session->set_userdata('requested_page', $this->uri->uri_string());
            redirect('login');
        }
        
        $this->load->model('db_model');
        $this->lang->load('auth', $this->Settings->user_language);
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
        $this->load->model('auth_model');
        $this->load->library('ion_auth');
        $this->load->model('atas_model');
        $this->load->model('projetos_model');
        $this->load->model('reports_model');
        $this->load->model('site');
        $this->digital_upload_path = 'assets/uploads/atas';
        $this->upload_path = 'assets/uploads/atas';
        $this->thumbs_path = 'assets/uploads/thumbs/atas';
        $this->image_types = 'gif|jpg|jpeg|png|tif';
        $this->digital_file_types = 'zip|psd|ai|rar|pdf|doc|docx|xls|xlsx|ppt|pptx|gif|jpg|jpeg|png|tif|txt';
    }

    public function index()
    {
        if ($this->Settings->version == '2.3') {
            $this->session->set_flashdata('warning', 'Please complete your update by synchronizing your database.');
            redirect('sync');
        }
        $this->sma->checkPermissions();
        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        
        //$lmsdate = date('Y-m-d', strtotime('first day of last month')) . ' 00:00:00';
        //$lmedate = date('Y-m-d', strtotime('last day of last month')) . ' 23:59:59';
       // $this->data['lmbs'] = $this->db_model->getBestSeller($lmsdate, $lmedate);
       // $bc = array(array('link' => '#', 'page' => lang('Projetos')));
        //$meta = array('page_title' => lang('projetos'), 'bc' => $bc);
        //$this->page_construct('selecionar_projetos', "", $this->data);
        
        $this->load->view($this->theme . 'selecionar_projetos', $this->data);
        
    }

    function set_data($ud, $value)
    {
        $this->session->set_userdata($ud, $value);
        echo true;
    }

    function hideNotification($id = NULL)
    {
        $this->session->set_userdata('hidden' . $id, 1);
        echo true;
    }

    public function projeto_ata($id = null)
    {
        $this->sma->checkPermissions();
      
        
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        $data_projeto['projeto_atual'] = $id;
        $usuario = $this->session->userdata('user_id');
        $this->atas_model->updateProjetoUsuario($usuario,$data_projeto);
        
           
        redirect("Login_Projetos/menu");
            
    }

    public function projeto_menu() {
        $this->sma->checkPermissions();
        
        if ($this->Settings->version == '2.3') {
            $this->session->set_flashdata('warning', 'Please complete your update by synchronizing your database.');
            redirect('sync');
        }

        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        
        $this->data['projetos'] = $this->atas_model->getAllProjetos();
        $bc = array(array('link' => '#', 'page' => lang('Selecione o Projeto')));
        $meta = array('page_title' => lang('Selecionar projetos'), 'bc' => $bc);
        //$this->page_construct('selecionar_projetos', $meta, $this->data);
        
        $this->load->view($this->theme . 'selecionar_projetos', $this->data);
    }    
    
     public function menu() {
          
        //$this->sma->checkPermissions();
        
        if ($this->Settings->version == '2.3') {
            $this->session->set_flashdata('warning', 'Please complete your update by synchronizing your database.');
            redirect('sync');
        }
        
        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        $this->data['projetos'] = $this->atas_model->getAllProjetos();
        
        
        
         $this->load->view($this->theme . 'menu', $this->data);
       
    } 
    
         public function menu_teste() {
        $this->sma->checkPermissions();
        
        if ($this->Settings->version == '2.3') {
            $this->session->set_flashdata('warning', 'Please complete your update by synchronizing your database.');
            redirect('sync');
        }
        
        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        
        $this->data['projetos'] = $this->atas_model->getAllProjetos();
       // $bc = array(array('link' => '#', 'page' => lang('Menu')));
       // $meta = array('page_title' => lang('Menu'), 'bc' => $bc);
       // $this->page_construct('menu', $meta, $this->data);
         $this->load->view($this->theme . 'menu_teste', $this->data);
        //$this->load->view($this->theme . 'menu', $this->data);
    } 
    
    public function menu_projetos() {
        $this->sma->checkPermissions();
        
        if ($this->Settings->version == '2.3') {
            $this->session->set_flashdata('warning', 'Please complete your update by synchronizing your database.');
            redirect('sync');
        }
        
        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        
        $this->data['projetos'] = $this->atas_model->getAllProjetos();
       // $bc = array(array('link' => '#', 'page' => lang('Menu')));
       // $meta = array('page_title' => lang('Menu'), 'bc' => $bc);
       // $this->page_construct('menu', $meta, $this->data);
         $this->load->view($this->theme . 'menu_projetos', $this->data);
        //$this->load->view($this->theme . 'menu', $this->data);
    } 
    
    
    public function menu_sistemas() {
        $this->sma->checkPermissions();
        
        
        if ($this->Settings->version == '2.3') {
            $this->session->set_flashdata('warning', 'Please complete your update by synchronizing your database.');
            redirect('sync');
        }
        
        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        
        $this->data['projetos'] = $this->atas_model->getAllProjetos();
          
        /*
         * VALIDA SE O USUÁRIO TEM ACESSO A MAIS DE 1 SISTEMA
         */
          
        $usuario = $this->session->userdata('user_id');  
        $cadastroUsuario = $this->site->getPerfilAtualSistemasByID($usuario);
        $quantidade = $cadastroUsuario->quantidade;

        if($quantidade > 1){
            //$referrer = $this->session->userdata('requested_page') ? $this->session->userdata('requested_page') : 'Login_Projetos/menu_sistemas'; 
            $this->load->view($this->theme . 'menu_sistemas', $this->data);
        }else{
           $referrer = $this->session->userdata('requested_page') ? $this->session->userdata('requested_page') : 'Login_Projetos/menu';    
        }
        
      
       
        //echo 'aqui'; exit;
        
         
        //$this->load->view($this->theme . 'menu', $this->data);
    } 
    
     public function escopo($id = null) {
        $this->sma->checkPermissions();
        
        if ($this->Settings->version == '2.3') {
            $this->session->set_flashdata('warning', 'Please complete your update by synchronizing your database.');
            redirect('sync');
        }
        
        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        
        $this->data['tipos'] = $this->projetos_model->getAllTipoEventosProjeto($id,'ordem','asc');
       // $bc = array(array('link' => '#', 'page' => lang('Menu')));
       // $meta = array('page_title' => lang('Menu'), 'bc' => $bc);
       // $this->page_construct('menu', $meta, $this->data);
         $this->load->view($this->theme . 'escopo', $this->data);
        //$this->load->view($this->theme . 'menu', $this->data);
    } 
    
       public function escopo_slider($id = null) {
        $this->sma->checkPermissions();
        
        if ($this->Settings->version == '2.3') {
            $this->session->set_flashdata('warning', 'Please complete your update by synchronizing your database.');
            redirect('sync');
        }
        
        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        
        
       // $bc = array(array('link' => '#', 'page' => lang('Menu')));
       // $meta = array('page_title' => lang('Menu'), 'bc' => $bc);
       // $this->page_construct('menu', $meta, $this->data);
         $this->load->view($this->theme . 'escopo_slider', $this->data);
        //$this->load->view($this->theme . 'menu', $this->data);
    } 
    
    
    public function escopo_projeto($id = null) {
        $this->sma->checkPermissions();
        
        if ($this->Settings->version == '2.3') {
            $this->session->set_flashdata('warning', 'Please complete your update by synchronizing your database.');
            redirect('sync');
        }
        
        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        
        
       // $bc = array(array('link' => '#', 'page' => lang('Menu')));
       // $meta = array('page_title' => lang('Menu'), 'bc' => $bc);
       // $this->page_construct('menu', $meta, $this->data);
         $this->load->view($this->theme . 'escopo_total', $this->data);
        //$this->load->view($this->theme . 'menu', $this->data);
    }
    
    public function escopo_resumido($id = null) {
        $this->sma->checkPermissions();
        
        if ($this->Settings->version == '2.3') {
            $this->session->set_flashdata('warning', 'Please complete your update by synchronizing your database.');
            redirect('sync');
        }
        
        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        
        $this->data['tipos'] = $this->projetos_model->getAllTipoEventosProjeto($id,'ordem','asc');
       // $bc = array(array('link' => '#', 'page' => lang('Menu')));
       // $meta = array('page_title' => lang('Menu'), 'bc' => $bc);
       // $this->page_construct('menu', $meta, $this->data);
         $this->load->view($this->theme . 'escopo_resumo', $this->data);
        //$this->load->view($this->theme . 'menu', $this->data);
    } 
    
    
    public function eap($id = null) {
        $this->sma->checkPermissions();
        
        if ($this->Settings->version == '2.3') {
            $this->session->set_flashdata('warning', 'Please complete your update by synchronizing your database.');
            redirect('sync');
        }
        
        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        
        $this->data['tipos'] = $this->projetos_model->getAllTipoEventosProjeto($id,'ordem','asc');
       // $bc = array(array('link' => '#', 'page' => lang('Menu')));
       // $meta = array('page_title' => lang('Menu'), 'bc' => $bc);
       // $this->page_construct('menu', $meta, $this->data);
         $this->load->view($this->theme . 'eap', $this->data);
        //$this->load->view($this->theme . 'menu', $this->data);
    }
    
     public function eap_pdf($id = null, $view = 1)
    {
        
        $this->sma->checkPermissions();

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
       $this->data['tipos'] = $this->projetos_model->getAllTipoEventosProjeto($id,'ordem','asc');

            $name = lang("eap_escopo") . "_" . str_replace('/', '_', $id) . ".pdf";
            $html = $this->load->view($this->theme . 'eap_pdf', $this->data, true);

        if ($view) {
            $this->load->view($this->theme . 'eap_pdf', $this->data);
        } else{
            
           //$this->sma->generate_pdf($html, $name, false, $usuario);
        }
    }
    
    public function eap_evento($id = null) {
        $this->sma->checkPermissions();
        
        if ($this->Settings->version == '2.3') {
            $this->session->set_flashdata('warning', 'Please complete your update by synchronizing your database.');
            redirect('sync');
        }
        
        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        
        //$this->data['id'] = $id
        $this->data['evento'] = $this->projetos_model->getEventoByID($id);
       // $bc = array(array('link' => '#', 'page' => lang('Menu')));
       // $meta = array('page_title' => lang('Menu'), 'bc' => $bc);
       // $this->page_construct('menu', $meta, $this->data);
         $this->load->view($this->theme . 'eap_evento', $this->data);
        //$this->load->view($this->theme . 'menu', $this->data);
    }
    
    
    public function eap_tipo($id = null) {
        $this->sma->checkPermissions();
        
        if ($this->Settings->version == '2.3') {
            $this->session->set_flashdata('warning', 'Please complete your update by synchronizing your database.');
            redirect('sync');
        }
        
        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        
         $usuario = $this->session->userdata('user_id');
         $projetos = $this->site->getProjetoAtualByID_completo($usuario);
         $id_projeto = $projetos->projeto_atual;
         
         $tipo_tratado =  urldecode($id); 
         $this->data['tipo'] = $tipo_tratado;
        $this->data['eventos'] = $this->projetos_model->getAllEventosProjetoByTipo($tipo_tratado, $id_projeto, 'ordem','asc');
       // $bc = array(array('link' => '#', 'page' => lang('Menu')));
       // $meta = array('page_title' => lang('Menu'), 'bc' => $bc);
       // $this->page_construct('menu', $meta, $this->data);
         $this->load->view($this->theme . 'eap_tipo_evento', $this->data);
        //$this->load->view($this->theme . 'menu', $this->data);
    }
    
    
     public function controleAtividades() {
        $this->sma->checkPermissions();
        
        if ($this->Settings->version == '2.3') {
            $this->session->set_flashdata('warning', 'Please complete your update by synchronizing your database.');
            redirect('sync');
        }

        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
      
        $mes = date('m');
        $ano = date('Y');
        
        
        $id_usuario = $this->input->post('responsavel'); 
        //echo $id_usuario; exit;
       
        $this->data['users'] = $this->atas_model->getAllUsersSetores();
        $this->data['pagina'] = 'usuarios/new/todas_acoes';
        $this->data['ativo'] = 'acuracidade';
        $this->data['menu'] = 'estoque'; //footer
        $this->data['footer'] = 'footer_acuracidade';
        $this->data['usuario_selecionado'] = $id_usuario;
       
        
        $this->load->view($this->theme . 'usuarios/new/main_edp', $this->data);
    }
    
    public function menu_desenvolvimento() {
      //  $this->sma->checkPermissions();
        
        if ($this->Settings->version == '2.3') {
            $this->session->set_flashdata('warning', 'Please complete your update by synchronizing your database.');
            redirect('sync');
        }
        
        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        
        $this->data['projetos'] = $this->atas_model->getAllProjetos();
        $bc = array(array('link' => '#', 'page' => lang('Menu')));
        $meta = array('page_title' => lang('Menu'), 'bc' => $bc);
        //$this->page_construct('menu_desenvolvimento', $meta, $this->data);
        
        $this->load->view($this->theme . 'menu', $this->data);
    } 
    
    public function menu_desenvolvimento2() {
      //  $this->sma->checkPermissions();
        
        if ($this->Settings->version == '2.3') {
            $this->session->set_flashdata('warning', 'Please complete your update by synchronizing your database.');
            redirect('sync');
        }
        
        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        
        $this->data['projetos'] = $this->atas_model->getAllProjetos();
        $bc = array(array('link' => '#', 'page' => lang('Menu')));
        $meta = array('page_title' => lang('Menu'), 'bc' => $bc);
        //$this->page_construct('menu_desenvolvimento', $meta, $this->data);
        
        $this->load->view($this->theme . 'menu_desenvolvimento2', $this->data);
    }
    
    
    /*
     * CALENDÁRIO DE MARCOS
     */
        public function marcos_projeto($id = null) {
        $this->sma->checkPermissions();
        
        if ($this->Settings->version == '2.3') {
            $this->session->set_flashdata('warning', 'Please complete your update by synchronizing your database.');
            redirect('sync');
        }
        
        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        
        $this->data['tipos'] = $this->projetos_model->getAllTipoEventosProjeto($id,'ordem','asc');
       // $bc = array(array('link' => '#', 'page' => lang('Menu')));
       // $meta = array('page_title' => lang('Menu'), 'bc' => $bc);
       // $this->page_construct('menu', $meta, $this->data);
         $this->load->view($this->theme . 'marcos_projeto', $this->data);
        //$this->load->view($this->theme . 'menu', $this->data);
    }
    
    
    /*
     * TREINAMENTOS
     */
        public function treinamentos_projeto($id = null) {
        $this->sma->checkPermissions();
        
        if ($this->Settings->version == '2.3') {
            $this->session->set_flashdata('warning', 'Please complete your update by synchronizing your database.');
            redirect('sync');
        }
        
        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        
        $this->data['treinamentos'] = $this->projetos_model->getAllTreinamentosProjeto($id);
       // $bc = array(array('link' => '#', 'page' => lang('Menu')));
       // $meta = array('page_title' => lang('Menu'), 'bc' => $bc);
       // $this->page_construct('menu', $meta, $this->data);
         $this->load->view($this->theme . 'treinamentos', $this->data);
        //$this->load->view($this->theme . 'menu', $this->data);
    }
    
    public function ver_dados_treinamento($id = null)
    {
        
         
       $id_descriptografado_participante = $id;// $this->encrypt($id,'PRATA');
       
         $participantes = $this->atas_model->participante_treinamento_ataByid($id_descriptografado_participante);
         $id_ata = $participantes->id_ata;
         $id_participante_usuario = $participantes->id_participante;
         $status_avaliacao = $participantes->avaliacao;
        
       
         
         $dados_ata = $this->atas_model->getAtaByID($id_descriptografado_participante);
         $tipo = $dados_ata->tipo;
         $tipo_ava_reacao = $dados_ata->avaliacao_reacao;
         
               
                 $this->data['id_usuario'] =  $id_participante_usuario;
                 $this->data['id_ata'] =  $id_descriptografado_participante;
                
                 
                 $this->data['pesquisa'] = $this->atas_model->getPesquisaByID($tipo_ava_reacao);
                 $this->data['grupo_perguntas'] = $this->atas_model->getGrupoByIDPesquisa($tipo_ava_reacao);
                 //$this->page_construct('usuarios/ver_pesquisa_reacao', $meta, $this->data);
                  $this->load->view($this->theme . 'ver_treinamento', $this->data);
            
        
    } 
    
    /*
     *   CADASTRO DE EQUIPES
     */
    
    
        public function migracao($id = null) {
        $this->sma->checkPermissions();
        
        if ($this->Settings->version == '2.3') {
            $this->session->set_flashdata('warning', 'Please complete your update by synchronizing your database.');
            redirect('sync');
        }
        
        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        
        $usuario = $this->session->userdata('user_id');
        $projetos_usuario = $this->site->getProjetoAtualByID_completo($usuario);
        $this->data['equipes'] = $this->atas_model->getEquipeByProjeto($projetos_usuario->projeto_atual);
        
         $this->load->view($this->theme . 'migracao', $this->data);
        //$this->load->view($this->theme . 'menu', $this->data);
    }
    
    
     public function inconsistencias() {
        $this->sma->checkPermissions();
        
        if ($this->Settings->version == '2.3') {
            $this->session->set_flashdata('warning', 'Please complete your update by synchronizing your database.');
            redirect('sync');
        }
        
        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        
        
      //  $db2 = $this->load->database('STAGE', TRUE);
       // $this->db2 = $db2;
        
      //  echo 'aqui ok'; exit;
        $usuario = $this->session->userdata('user_id');
        $projetos_usuario = $this->site->getProjetoAtualByID_completo($usuario);
        //$this->data['equipes'] = $this->atas_model->getEquipeByProjeto($projetos_usuario->projeto_atual);
        
         $this->load->view($this->theme . 'migracao_inconsistencias', $this->data);
        //$this->load->view($this->theme . 'menu', $this->data);
    }
    
    
    /*
     * REAJUSTE DOS CADASTROS
     */
      public function reajuste_cadastro($id = null) {
        $this->sma->checkPermissions();
        
        if ($this->Settings->version == '2.3') {
            $this->session->set_flashdata('warning', 'Please complete your update by synchronizing your database.');
            redirect('sync');
        }
        
        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        
        $usuario = $this->session->userdata('user_id');
        $projetos_usuario = $this->site->getProjetoAtualByID_completo($usuario);
        $this->data['equipes'] = $this->atas_model->getEquipeByProjeto($projetos_usuario->projeto_atual);
        
         $this->load->view($this->theme . 'cadastro_estoque', $this->data);
        //$this->load->view($this->theme . 'menu', $this->data);
    }
    
    /*
     *   CADASTRO DE EQUIPES
     */
    
    
        public function equipe($id = null) {
        $this->sma->checkPermissions();
        
        if ($this->Settings->version == '2.3') {
            $this->session->set_flashdata('warning', 'Please complete your update by synchronizing your database.');
            redirect('sync');
        }
        
        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        
        $usuario = $this->session->userdata('user_id');
        $projetos_usuario = $this->site->getProjetoAtualByID_completo($usuario);
        $this->data['equipes'] = $this->atas_model->getEquipeByProjeto($projetos_usuario->projeto_atual);
        
         $this->load->view($this->theme . 'equipe', $this->data);
        //$this->load->view($this->theme . 'menu', $this->data);
    } 
    
    public function abrir_rat($id = null)
    {
         
        $this->sma->checkPermissions();
        
        if ($this->Settings->version == '2.3') {
            $this->session->set_flashdata('warning', 'Please complete your update by synchronizing your database.');
            redirect('sync');
        }
         
        $this->form_validation->set_rules('hora_inicio', lang("Informe a Hora de Início"), 'required');
        $this->form_validation->set_rules('hora_termino', lang("Informe a Hora de Término"), 'required');
        $this->form_validation->set_rules('conteudo', lang("Informe o conteúdo"), 'required');
        
        $date_cadastro = date('Y-m-d H:i:s');       
        
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        
          if ($this->form_validation->run() == true) {
           
            $data_rat = $this->input->post('data_registro'); 
            $hora_inicio = $this->input->post('hora_inicio');
            $hora_termino = $this->input->post('hora_termino');
            $conteudo = $this->input->post('conteudo');
            $data_criacao = $date_cadastro;
            $usuario = $this->session->userdata('user_id');
            $id_mebro = $this->input->post('id_membro');
            $tipo = $this->input->post('tipo');
            
            $tempo = gmdate('H:i:s', strtotime( $hora_termino) - strtotime( $hora_inicio  ) );
            
         
            
            $funcoes = $this->input->post('funcao');
            $itens = $this->input->post('eventos_item');
            
            
            
            $data_rat = array(
                'equipe' => $id_mebro,
                'data_registro' => $data_criacao,
                'hora_inicio' => $hora_inicio,
                'hora_fim' => $hora_termino,
                'descricao' => $conteudo,
                'data_rat' => $data_rat,
                'tipo_hora' => $tipo ,
                'tempo' => $usuario
            );
           
            
          //  print_r($data_ata); exit;
          
            $this->atas_model->add_rat($data_rat, $funcoes, $itens);
            
            $this->session->set_flashdata('message', lang("RAT Registrado com Sucesso!!!"));
            redirect("Welcome/abrir_rat/$id_mebro");
            
        } else {
     
           $date_cadastro = date('Y-m-d H:i:s');       
           // $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            
          $data_inicio = $this->input->post('data_inicio'); 
          $data_fim = $this->input->post('data_fim');
          
          if($data_inicio){
            $this->data['data_inicio'] = $data_inicio;
              
          }
          
          if($data_fim){
              $this->data['data_fim'] = $data_fim;
              
          }
          
            $this->data['id'] = $id;
            $this->data['dados_equipe'] = $this->atas_model->getMebrosEquipeByIdEquipe($id); //
            
            $projeto_membro = $this->atas_model->getMebrosEquipeByIdMembro($id);
            $projeto_selecionado = $projeto_membro->projeto;
          
             $this->data['projeto'] = $projeto_selecionado;
            
           
            $this->load->view($this->theme . 'equipe_rat', $this->data);
        }

            
    }
    
    /*
     * GERENCIAMENTO DE POSTAGENS - PORTAL DO USUÁRIO
     */
      public function post_index()
    {
        $this->sma->checkPermissions();

        if (!$this->loggedIn) {
            redirect('login');
        } else {
            $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
      //      redirect($_SERVER['HTTP_REFERER']);
        }
        
        
             $bc = array(array('link' => '#', 'page' => lang('NOME : '.$users->first_name .' '. $users->last_name .' | SETOR : '.$users->company )));
       
        $meta = array('page_title' => lang('Pesquisa de Satisfação'), 'bc' => $bc);
        $this->page_construct('projetos/post/index', $meta, $this->data);
    }

     public function modulos($id = null) {
        $this->sma->checkPermissions();
        
        if ($this->Settings->version == '2.3') {
            $this->session->set_flashdata('warning', 'Please complete your update by synchronizing your database.');
            redirect('sync');
        }
        
        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        
        $usuario = $this->session->userdata('user_id');
        $projetos = $this->site->getProjetoAtualByID_completo($usuario);
        
        $this->data['modulos'] = $this->projetos_model->getAllModulosByProjeto($projetos->projeto_atual);
       // $bc = array(array('link' => '#', 'page' => lang('Menu')));
       // $meta = array('page_title' => lang('Menu'), 'bc' => $bc);
       // $this->page_construct('menu', $meta, $this->data);
         $this->load->view($this->theme . 'modulos', $this->data);
        //$this->load->view($this->theme . 'menu', $this->data);
    }
    
    
     public function documentos($id = null) {
        $this->sma->checkPermissions();
        
        if ($this->Settings->version == '2.3') {
            $this->session->set_flashdata('warning', 'Please complete your update by synchronizing your database.');
            redirect('sync');
        }
        
        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        
        $usuario = $this->session->userdata('user_id');
        $projetos = $this->site->getProjetoAtualByID_completo($usuario);
        
        $this->data['modulos'] = $this->projetos_model->getAllModulosByProjeto($projetos->projeto_atual);
       // $bc = array(array('link' => '#', 'page' => lang('Menu')));
       // $meta = array('page_title' => lang('Menu'), 'bc' => $bc);
       // $this->page_construct('menu', $meta, $this->data);
         $this->load->view($this->theme . 'documentos', $this->data);
        //$this->load->view($this->theme . 'menu', $this->data);
    }
    
     public function eventos_acoes($id = null) {
        $this->sma->checkPermissions();
        
        if ($this->Settings->version == '2.3') {
            $this->session->set_flashdata('warning', 'Please complete your update by synchronizing your database.');
            redirect('sync');
        }
        
        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        
        
       // echo $id.'aqui'; exit;
        
        $usuario = $this->session->userdata('user_id');
        $projetos = $this->site->getProjetoAtualByID_completo($usuario);
        
        $this->data['planos'] = $this->projetos_model->getAllAcoesbyItemEvento($id);
        
        $this->data['id'] = $id;
        
         $this->load->view($this->theme . 'acoes_eventos', $this->data);

     }
     
      public function manutencao_acao($id = null)
    {
     
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
            }
        
                      
            $this->data['macro'] = $this->atas_model->getAllMacroProcesso();
            $this->data['projetos'] = $this->atas_model->getAllProjetos();      
            $this->data['idplano'] = $id;
            $this->data['acoes'] = $this->atas_model->getPlanoByID($id);
            $this->load->view($this->theme . 'acaoVisualiza', $this->data);
      
    }
    
    
    
    
    
       public function dashboard_helpdesk($id = null)
    {
           $this->sma->checkPermissions();
     
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
            }
        
                      
           // $this->data['macro'] = $this->atas_model->getAllMacroProcesso();
           // $this->data['projetos'] = $this->atas_model->getAllProjetos();      
            $this->data['categorias'] = $this->reports_model->getAllCategoriasAtivas() ;
            $this->data['tickets_semchamado'] = $this->reports_model->getAllOrdemServicosSemAceite();
            $this->data['tickets'] = $this->reports_model->getAllOrdemServicos();
            $this->load->view($this->theme . 'dashboard_helpdesk', $this->data);
      
    }
    
    
    /*
     * FUNÇÕES DO SISTEMA DE BI
     */
    
    
     public function bi_main() {
        $this->sma->checkPermissions();
        
        if ($this->Settings->version == '2.3') {
            $this->session->set_flashdata('warning', 'Please complete your update by synchronizing your database.');
            redirect('sync');
        }

        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        
        $this->data['projetos'] = $this->atas_model->getAllProjetos();
        $bc = array(array('link' => '#', 'page' => lang('Selecione o Projeto')));
        $meta = array('page_title' => lang('Selecionar projetos'), 'bc' => $bc);
        //$this->page_construct('selecionar_projetos', $meta, $this->data);
        $this->data['ativo'] = 'hospitalar';
        $this->data['pagina'] = 'bi/paginas/dashboard_hospitalar';
        $this->data['menu'] = 'dashboard';
        $this->data['footer'] = 'footer';
        $this->load->view($this->theme . 'bi/main', $this->data);
    }
    
    public function bi_emprestimo() {
        $this->sma->checkPermissions();
        
        if ($this->Settings->version == '2.3') {
            $this->session->set_flashdata('warning', 'Please complete your update by synchronizing your database.');
            redirect('sync');
        }

        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        
        $this->data['projetos'] = $this->atas_model->getAllProjetos();
        $bc = array(array('link' => '#', 'page' => lang('Selecione o Projeto')));
        $meta = array('page_title' => lang('Selecionar projetos'), 'bc' => $bc);
        //$this->page_construct('selecionar_projetos', $meta, $this->data);
        
        $query_saldo_devedor_hmu = "select   SUM(a.qt_material) QUANTIDADE, SUM(a.qt_material * to_char(SD.VL_CUSTO_MEDIO, 'FM999G999G990D90')) AS CUSTO_TOTAL
                                    from     emprestimo_material a
                                    inner join emprestimo e on e.nr_emprestimo = a.nr_emprestimo
                                    INNER JOIN SALDO_ESTOQUE SD ON SD.CD_MATERIAL = A.CD_MATERIAL
                                    where    TASY.obter_situacao_emprestimo_mat(a.nr_emprestimo, a.nr_sequencia) in('P','A')
                                             and e.cd_local_estoque = 65
                                             and e.dt_emprestimo >= '01/06/2018'
                                             AND SD.DT_MESANO_REFERENCIA = (SELECT MAX(DT_MESANO_REFERENCIA) FROM SALDO_ESTOQUE S WHERE S.CD_LOCAL_ESTOQUE = E.CD_LOCAL_ESTOQUE AND S.CD_MATERIAL = A.CD_MATERIAL) 
                                             AND SD.CD_LOCAL_ESTOQUE = 65
                                             -- AND NOT EXISTS (SELECT CD_MATERIAL FROM SALDO_ESTOQUE SD WHERE SD.DT_MESANO_REFERENCIA = '01/07/2018' AND SD.CD_MATERIAL = A.CD_MATERIAL AND SD.CD_LOCAL_ESTOQUE = 65 )
                                             and e.ie_tipo = 'E'
                                    order by a.nr_sequencia";
        
        $query_saldo_devedor_hupl = "select   SUM(a.qt_material) QUANTIDADE, SUM(a.qt_material * to_char(SD.VL_CUSTO_MEDIO, 'FM999G999G990D90')) AS CUSTO_TOTAL
                                    from     emprestimo_material a
                                    inner join emprestimo e on e.nr_emprestimo = a.nr_emprestimo
                                    INNER JOIN SALDO_ESTOQUE SD ON SD.CD_MATERIAL = A.CD_MATERIAL
                                    where    TASY.obter_situacao_emprestimo_mat(a.nr_emprestimo, a.nr_sequencia) in('P','A')
                                             and e.cd_local_estoque = 19
                                             and e.dt_emprestimo >= '01/06/2018'
                                             AND SD.DT_MESANO_REFERENCIA = (SELECT MAX(DT_MESANO_REFERENCIA) FROM SALDO_ESTOQUE S WHERE S.CD_LOCAL_ESTOQUE = E.CD_LOCAL_ESTOQUE AND S.CD_MATERIAL = A.CD_MATERIAL) 
                                             AND SD.CD_LOCAL_ESTOQUE = 19
                                             -- AND NOT EXISTS (SELECT CD_MATERIAL FROM SALDO_ESTOQUE SD WHERE SD.DT_MESANO_REFERENCIA = '01/07/2018' AND SD.CD_MATERIAL = A.CD_MATERIAL AND SD.CD_LOCAL_ESTOQUE = 65 )
                                             and e.ie_tipo = 'E'
                                    order by a.nr_sequencia";
        
         $query_saldo_receber_hmu = "select   SUM(a.qt_material) QUANTIDADE, SUM(a.qt_material * to_char(SD.VL_CUSTO_MEDIO, 'FM999G999G990D90')) AS CUSTO_TOTAL
                                    from     emprestimo_material a
                                    inner join emprestimo e on e.nr_emprestimo = a.nr_emprestimo
                                    INNER JOIN SALDO_ESTOQUE SD ON SD.CD_MATERIAL = A.CD_MATERIAL
                                    where    TASY.obter_situacao_emprestimo_mat(a.nr_emprestimo, a.nr_sequencia) in('P','A')
                                             and e.cd_local_estoque = 65
                                             and e.dt_emprestimo >= '01/06/2018'
                                             AND SD.DT_MESANO_REFERENCIA = (SELECT MAX(DT_MESANO_REFERENCIA) FROM SALDO_ESTOQUE S WHERE S.CD_LOCAL_ESTOQUE = E.CD_LOCAL_ESTOQUE AND S.CD_MATERIAL = A.CD_MATERIAL) 
                                             AND SD.CD_LOCAL_ESTOQUE = 65
                                             -- AND NOT EXISTS (SELECT CD_MATERIAL FROM SALDO_ESTOQUE SD WHERE SD.DT_MESANO_REFERENCIA = '01/07/2018' AND SD.CD_MATERIAL = A.CD_MATERIAL AND SD.CD_LOCAL_ESTOQUE = 65 )
                                             and e.ie_tipo = 'S'
                                    order by a.nr_sequencia";
         
          $query_saldo_receber_hupl = "select   SUM(a.qt_material) QUANTIDADE, SUM(a.qt_material * to_char(SD.VL_CUSTO_MEDIO, 'FM999G999G990D90')) AS CUSTO_TOTAL
                                    from     emprestimo_material a
                                    inner join emprestimo e on e.nr_emprestimo = a.nr_emprestimo
                                    INNER JOIN SALDO_ESTOQUE SD ON SD.CD_MATERIAL = A.CD_MATERIAL
                                    where    TASY.obter_situacao_emprestimo_mat(a.nr_emprestimo, a.nr_sequencia) in('P','A')
                                             and e.cd_local_estoque = 19
                                             and e.dt_emprestimo >= '01/06/2018'
                                             AND SD.DT_MESANO_REFERENCIA = (SELECT MAX(DT_MESANO_REFERENCIA) FROM SALDO_ESTOQUE S WHERE S.CD_LOCAL_ESTOQUE = E.CD_LOCAL_ESTOQUE AND S.CD_MATERIAL = A.CD_MATERIAL) 
                                             AND SD.CD_LOCAL_ESTOQUE = 19
                                             -- AND NOT EXISTS (SELECT CD_MATERIAL FROM SALDO_ESTOQUE SD WHERE SD.DT_MESANO_REFERENCIA = '01/07/2018' AND SD.CD_MATERIAL = A.CD_MATERIAL AND SD.CD_LOCAL_ESTOQUE = 65 )
                                             and e.ie_tipo = 'S'
                                    order by a.nr_sequencia";
          
          
          
        
        $query_entrada_fornecedor_hmu = "select distinct CD_PESSOA_JURIDICA,PJ.DS_RAZAO_SOCIAL, 
                  (select count(*) as emprestimo from emprestimo ep
                  where ep.cd_pessoa_juridica = e.cd_pessoa_juridica
                  and ep.dt_emprestimo between '01/08/2018' and '30/08/2018'
                  and ep.cd_local_estoque = 65
                  and ep.ie_tipo = 'E'
                  ) as quantidade_emprestimo,

                    (select sum(qt_emprestimo) as quantidade_emprestimo from emprestimo ep
                    inner join emprestimo_material em on em.nr_emprestimo = ep.nr_emprestimo
                    where ep.cd_pessoa_juridica = e.cd_pessoa_juridica
                    and ep.dt_emprestimo between '01/06/2018' and '30/06/2018'
                    and ep.cd_local_estoque = 65
                    and ep.ie_tipo = 'E'
                    ) as quantidade_material

                    from emprestimo e
                    inner join pessoa_juridica pj on pj.cd_cgc = e.cd_pessoa_juridica
                    where e.dt_emprestimo between '01/06/2018' and '30/06/2018'
                    and e.cd_local_estoque = 65
                    and e.ie_tipo = 'E'";
        
       // echo '<br>';
     //   print_r($emprestimo_entrada);
        
      
       
       // $this->data['entrada_fornecedor'] = $this->projetos_model->emprestimo_entrada_fornecedor();
       
        
        $this->data['pagina'] = 'bi/paginas/emprestimo';
        $this->data['ativo'] = 'emprestimo';
        $this->data['menu'] = 'estoque'; //footer
        $this->data['footer'] = 'footer_emprestimo';
        
        $this->data['query_entrada_fornecedor_hmu'] = $query_entrada_fornecedor_hmu;
        $this->data['query_saldo_devedor_hmu'] = $query_saldo_devedor_hmu;
        $this->data['query_saldo_devedor_hupl'] = $query_saldo_devedor_hupl;
        $this->data['query_saldo_receber_hmu'] = $query_saldo_receber_hmu;
        $this->data['query_saldo_receber_hupl'] = $query_saldo_receber_hupl;
        
        $this->load->view($this->theme . 'bi/main', $this->data);
    }
    
    
    /*
     * ACURACIDADE
     */
    
     public function bi_acuracidade() {
        $this->sma->checkPermissions();
        
        if ($this->Settings->version == '2.3') {
            $this->session->set_flashdata('warning', 'Please complete your update by synchronizing your database.');
            redirect('sync');
        }

        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
      
        $mes = date('m');
        $ano = date('Y');
        
        $query_acuracidade_hmu = "select nvl(substr(a.DS_LOCAL_ESTOQUE,1,255), 'Não Informado') ds_base, 
                                TASY.DIVIDIR(NVL(SUM(QT_OCOR_INVENT_OK),0)*100, 
                                NVL(SUM(QT_OCOR_INVENT),0)) nr_acum , a.CD_LOCAL_ESTOQUE cd_base 
                                from TASY.EIS_INVENT_MATERIAL_V a
                                where 1 = 1 
                                AND CD_ESTABELECIMENTO = 1
                                and DS_LOCAL_ESTOQUE is not null 
                                and DT_MESANO_REFERENCIA 
                                between TO_DATE('01/$mes/$ano','dd/mm/yyyy') and TO_DATE('30/$mes/$ano','dd/mm/yyyy')
                                group by nvl(substr(a.DS_LOCAL_ESTOQUE,1,255), 'Não Informado')
                                 , a.CD_LOCAL_ESTOQUE
                                 order by 1  Asc ";
        
        $this->data['pagina'] = 'bi/paginas/acuracidade';
        $this->data['ativo'] = 'acuracidade';
        $this->data['menu'] = 'estoque'; //footer
        $this->data['footer'] = 'footer_acuracidade';
        
        $this->data['query_acuracidade_hmu'] = $query_acuracidade_hmu;
    
        
        $this->load->view($this->theme . 'bi/main', $this->data);
    }
    
     /*
     * SALDO DE ESTOQUE 
     */
    
     public function bi_saldo_estoque() {
        $this->sma->checkPermissions();
        
        if ($this->Settings->version == '2.3') {
            $this->session->set_flashdata('warning', 'Please complete your update by synchronizing your database.');
            redirect('sync');
        }

        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
      
         $mes_atual = date('m');
      $ano_atual = date('Y');
      $ano_anterior = $ano_atual - 1;
        
        $query_saldo_estoque_hmu = "select 0 ie_valor, 0 cd_base, nvl(substr(a.DS_LOCAL_ESTOQUE,1,255), 'Não Informado')  ds_base, 
        to_char(a.CD_LOCAL_ESTOQUE) nm_drill, 
         sum( VL_ESTOQUE) VL_ESTOQUE,
         sum( VL_ESTOQUE_MEDIO) VL_ESTOQUE_MEDIO,
         sum( VL_CONSUMO) VL_CONSUMO,
        TASY.DIVIDIR(SUM(VL_CONSUMO),SUM(VL_ESTOQUE_MEDIO)) VL_3,
         sum( VL_EXCEDENTE) VL_EXCEDENTE,
        ((TASY.dividir(sum(VL_ESTOQUE), sum(VL_CONSUMO))) * 30) VL_5,
        TASY.dividir(((SUM(VL_ESTOQUE) - SUM(VL_CONSUMO)) * 100), SUM(VL_ESTOQUE)) VL_6,
        to_char( TRUNC(TASY.DIVIDIR(30,TASY.DIVIDIR(SUM(VL_CONSUMO),SUM(VL_ESTOQUE_MEDIO)))), '0' ) VL_7,
         1 from TASY.eis_saldo_estoque_v a
         where 1 = 1 
        AND CD_ESTABELECIMENTO = a.CD_ESTABELECIMENTO
          and a.CD_LOCAL_ESTOQUE not in (1)
         and DS_LOCAL_ESTOQUE is not null 
         and DT_REFERENCIA between TO_DATE('01/$mes_atual/$ano_atual','dd/mm/yyyy') and TO_DATE('31/$mes_atual/$ano_atual 23:59:59','dd/mm/yyyy hh24:mi:ss')
         group by 1, a.DS_LOCAL_ESTOQUE
        ,to_char(a.CD_LOCAL_ESTOQUE)
         union select 1 ie_valor, 0 cd_base, 'Total' ds_base, 
        ' ' nm_drill, 
         sum( VL_ESTOQUE) VL_ESTOQUE,
         sum( VL_ESTOQUE_MEDIO) VL_ESTOQUE_MEDIO,

         sum( VL_CONSUMO) VL_CONSUMO,

        TASY.DIVIDIR(SUM(VL_CONSUMO),SUM(VL_ESTOQUE_MEDIO)) VL_3,
        sum( VL_EXCEDENTE) VL_EXCEDENTE,
        ((TASY.dividir(sum(VL_ESTOQUE), sum(VL_CONSUMO))) * 30) VL_5,
        TASY.dividir(((SUM(VL_ESTOQUE) - SUM(VL_CONSUMO)) * 100), SUM(VL_ESTOQUE)) VL_6,
        to_char( TRUNC(TASY.DIVIDIR(30,TASY.DIVIDIR(SUM(VL_CONSUMO),SUM(VL_ESTOQUE_MEDIO)))), '0' ) VL_7,
         1 from TASY.eis_saldo_estoque_v a
         where 1 = 1 
        AND CD_ESTABELECIMENTO = a.CD_ESTABELECIMENTO
      and a.CD_LOCAL_ESTOQUE not in (1)
         and DS_LOCAL_ESTOQUE is not null 
         and DT_REFERENCIA between TO_DATE('01/$mes_atual/2018','dd/mm/yyyy') and TO_DATE('31/$mes_atual/2018 23:59:59','dd/mm/yyyy hh24:mi:ss')
         group by 1 
         order by 1, 5  Desc
 ";
        
       
       
       
        
        $this->data['pagina'] = 'bi/paginas/saldo_estoque';
        $this->data['ativo'] = 'saldo_estoque';
        $this->data['menu'] = 'estoque'; //footer
        $this->data['footer'] = 'footer_saldo_estoque';
        
        $this->data['query_saldo_estoque_hmu'] = $query_saldo_estoque_hmu;
    
        
        $this->load->view($this->theme . 'bi/main', $this->data);
    }
    
    
    
}
