<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Cadastros extends MY_Controller
{
    
        function __construct()
    {
           parent::__construct();
        $this->lang->load('auth', $this->Settings->user_language);
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
        $this->load->model('auth_model');
        $this->load->library('ion_auth');
        $this->load->model('atas_model');
        $this->load->model('projetos_model');
        $this->load->model('site');
        $this->digital_upload_path = 'assets/uploads/atas';
        $this->upload_path = 'assets/uploads/atas';
        $this->thumbs_path = 'assets/uploads/thumbs/atas';
        $this->image_types = 'gif|jpg|jpeg|png|tif';
        $this->digital_file_types = 'zip|psd|ai|rar|pdf|doc|docx|xls|xlsx|ppt|pptx|gif|jpg|jpeg|png|tif|txt';
    }

    function index()
    {
        $this->sma->checkPermissions();

        if (!$this->loggedIn) {
            redirect('login');
        } else {
            $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
            redirect($_SERVER['HTTP_REFERER']);
        }
        
        
             $bc = array(array('link' => '#', 'page' => lang('NOME : '.$users->first_name .' '. $users->last_name .' | SETOR : '.$users->company )));
       
        $meta = array('page_title' => lang('Pesquisa de Satisfação'), 'bc' => $bc);
        $this->page_construct('pesquisa_satisfacao/index', $meta, $this->data);
    }
    
    public function pesquisa_satisfacao()
    {
     $this->sma->checkPermissions();
     
        $this->data['atas'] = $this->atas_model->getAllAtasResumido();
        
        $usuario = $this->session->userdata('user_id');   
        $users = $this->site->geUserByID($usuario);
        
        $meta = array('page_title' => lang('Atas'), 'bc' => $bc);
        $this->page_construct('cadastros/pesquisa_satisfacao/index', $meta, $this->data);
        
    }
    
   public function getPesquisaSatisfacao($warehouse_id = null)
    {
        $this->sma->checkPermissions('index');

        if ((!$this->Owner || !$this->Admin) && !$warehouse_id) {
            $user = $this->site->getUser();
            $warehouse_id = $user->warehouse_id;
        }
        
        
        $ver =  anchor('Cadastros/visualizar_pesquisa_satisfacao/$1', '<i class="fa fa-eye"></i> ' . lang('Visualizar'));
        $edit_link = anchor('Cadastros/pesquisa_satisfacao_add_pergunta/$1', '<i class="fa fa-edit"></i> ' . lang('Editar Pesquisa'), 'class="sledit"');
       
           
        $delete_link = "<a href='#' class='po' title='<b>" . lang("Apagar Pesquisa") . "</b>' data-content=\"<p>"
        . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . site_url('projetos/delete/$1') . "'>"
        . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i> "
        . lang('Apagar Pesquisa') . "</a>";
        $action = '<div class="text-center"><div class="btn-group text-left">'
        . '<button type="button" class="btn btn-default btn-xs btn-primary dropdown-toggle" data-toggle="dropdown">'
        . lang('actions') . ' <span class="caret"></span></button>
        <ul class="dropdown-menu pull-right" role="menu">
          
            <li>' . $ver . '</li>
            <li>' . $edit_link . '</li>
           
            <li>' . $delete_link . '</li>
        </ul>
    </div></div>';
        //$action = '<div class="text-center">' . $detail_link . ' ' . $edit_link . ' ' . $email_link . ' ' . $delete_link . '</div>';
        
         if ($this->input->get('user')) {
            $conta = $this->input->get('user');
        } else {
            $conta = NULL;
        }
        if ($this->input->get('start_date')) {
            $start_date = $this->input->get('start_date');
        } else {
            $start_date = NULL;
        }
        if ($this->input->get('end_date')) {
            $end_date = $this->input->get('end_date');
        } else {
            $end_date = NULL;
        }
        if ($start_date) {
            $start_date = $this->sma->fld($start_date);
            $end_date = $this->sma->fld($end_date);
        }
        
        
        $this->load->library('datatables');
            $this->datatables
                ->select("id,titulo, responsavel")
                ->from('pesquisa_satisfacao');
                $this->db->order_by('id', 'desc');
         /*
            if ($conta) {
                $this->datatables->where('transactions.account_id', $conta);
            }
            if ($start_date) {
                $this->datatables->where('date BETWEEN "' . $start_date . '" and "' . $end_date . '"');
            }
          * 
          */
            
        $this->datatables->add_column("Actions", $action, "id");
        echo $this->datatables->generate();
    }
    
  
    public function pesquisa_satisfacao_add()
    {
        $this->sma->checkPermissions();

        //$this->form_validation->set_rules('projeto', lang("Projeto"), 'required');
        $this->form_validation->set_rules('nome', lang("Nome da Pesquisa"), 'required');
        $this->form_validation->set_rules('responsavel', lang("Responsavel"), 'required');
       
        
        $date_cadastro = date('Y-m-d H:i:s');               
        
        if ($this->form_validation->run() == true) {
           
            $nome = $this->input->post('nome');
            $responsavel = $this->input->post('responsavel');
            $data_criacao = $date_cadastro;
            $usuario = $this->session->userdata('user_id');//
            //$this->site->getUser($this->session->userdata('user_id'));
            $data_pesquisa_satisfacao = array(
                'titulo' => $nome,
                'data_criacao' => $data_criacao,   
                'usuario' => $usuario,
                'responsavel' => $responsavel
                
            );
            
             
            $id_pesq_satisfacao = $this->atas_model->addPesquisaSatisfacao($data_pesquisa_satisfacao);
            $this->session->set_flashdata('message', lang("Pesquisa Criada com Sucesso!!!"));
            
             redirect('Cadastros/pesquisa_satisfacao_add_pergunta/'.$id_pesq_satisfacao);
             
            
            //redirect("Atas");
            
        } else {

           $usuario = $this->session->userdata('user_id');
           $projetos_usuario = $this->site->getProjetoAtualByID_completo($usuario);
           

            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['projetos'] = $this->atas_model->getProjetoByID($projetos_usuario->projeto_atual);
            $this->data['users'] = $this->site->getAllUser();
            $bc = array(array('link' => base_url(), 'page' => lang('Pesquisa de Satisfação')), array('link' => site_url('Cadastros/pesquisa_satisfacao_add'), 'page' => lang('Nova Pesquisa')));
            $meta = array('page_title' => lang('Nova Pesquisa'), 'bc' => $bc);
            $this->page_construct('cadastros/pesquisa_satisfacao/add', $meta, $this->data);
        }
    }  
    
    public function pesquisa_satisfacao_add_pergunta($id = null)
    {
        $this->sma->checkPermissions();

        //$this->form_validation->set_rules('projeto', lang("Projeto"), 'required'); 
        $this->form_validation->set_rules('grupo', lang("Grupo da Pergunta"), 'required');
        $this->form_validation->set_rules('pergunta', lang("Pergunta"), 'required');
       
        
        $date_cadastro = date('Y-m-d H:i:s');               
        
        if ($this->form_validation->run() == true) {
           
            $pergunta = $this->input->post('pergunta');
            $grupo = $this->input->post('grupo');
            $id = $this->input->post('id');
            
            $data_pergunta = array(
                'grupo_pergunta' => $grupo,
                'pergunta' => $pergunta   
               
            );
             
            $this->atas_model->addPerguntaPesquisaSatisfacao($data_pergunta);
            $this->session->set_flashdata('message', lang("Pergunta Criada com Sucesso!!!"));
            
            redirect("Cadastros/pesquisa_satisfacao_add_pergunta/".$id);
            
        } else {

             $this->data['id'] = $id;
           
            
            if($id){
            
            $this->data['pesquisa'] = $this->atas_model->getPesquisaByID($id);
            $this->data['grupo_perguntas'] = $this->atas_model->getGrupoByIDPesquisa($id);
            $this->data['perguntas'] = $this->atas_model->getAllPerguntas($id);
            
            $bc = array(array('link' => base_url(), 'page' => lang('Pesquisa de Satisfação')), array('link' => '#', 'page' => lang('Editar Pesquisa')));
            $meta = array('page_title' => lang('Editar Pesquisa'), 'bc' => $bc);
              $this->page_construct('cadastros/pesquisa_satisfacao/add_perguntas', $meta, $this->data);
              
            }else{
                redirect("cadastros/pesquisa_satisfacao_add");
            }
            
      
        }
    }  
    
     public function edit_pergunta($id_pergunta, $id_pesquisa)
    {
     $this->sma->checkPermissions();
     
        //echo 'AKIII'.$id; exit;
        
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
            
        }
        
        $this->form_validation->set_rules('grupoPergunta', lang("Grupo da Pergunta"), 'required');
        $this->form_validation->set_rules('pergunta', lang("Pergunta"), 'required');
        
        if ($this->form_validation->run() == true) {
           
            $pergunta = $this->input->post('pergunta');
            $grupo = $this->input->post('grupoPergunta');
            $id_pergunta = $this->input->post('id_pergunta');
            $id_pesquisa = $this->input->post('id_pesquisa');
             
            
           
            $data_pergunta = array(
                'grupo_pergunta' => $grupo,
                'pergunta' => $pergunta   
               
            );
             
            $this->atas_model->updatePergunta($id_pergunta,$data_pergunta);
            $this->session->set_flashdata('message', lang("Pergunta Alterada com Sucesso!!!"));
            
            redirect("Cadastros/pesquisa_satisfacao_add_pergunta/".$id_pesquisa);
            
        } else {
        
             $this->data['pesquisa'] = $this->atas_model->getPesquisaByID($id_pesquisa);
             $this->data['pergunta'] = $this->atas_model->getPerguntaByID($id_pergunta);
             $this->data['grupo_perguntas'] = $this->atas_model->getGrupoByIDPesquisa($id_pesquisa);
        
            $this->load->view($this->theme . 'cadastros/pesquisa_satisfacao/edit_pergunta', $this->data);
        }
    }
    
    public function delete_pergunta($id_pergunta, $id_pesquisa)
    {
     $this->sma->checkPermissions();
     
           
             
            $this->atas_model->deletePergunta($id_pergunta);
            $this->session->set_flashdata('message', lang("Pergunta Apagada com Sucesso!!!"));
            
            redirect("Cadastros/pesquisa_satisfacao_add_pergunta/".$id_pesquisa);
    }
   
    public function visualizar_pesquisa_satisfacao($id = null)
    {
        $this->sma->checkPermissions();

          
            if($id){
            
            $this->data['pesquisa'] = $this->atas_model->getPesquisaByID($id);
            $this->data['grupo_perguntas'] = $this->atas_model->getGrupoByIDPesquisa($id);
           // $this->data['perguntas'] = $this->atas_model->getAllPerguntas($id);
            
            $bc = array(array('link' => base_url(), 'page' => lang('Pesquisa de Satisfação')), array('link' => '#', 'page' => lang('Visualizar Pesquisa')));
            $meta = array('page_title' => lang('Editar Pesquisa'), 'bc' => $bc);
              $this->page_construct('cadastros/pesquisa_satisfacao/ver', $meta, $this->data);
              
            }else{
                redirect("cadastros/pesquisa_satisfacao_add");
            }
            
      
        
    } 
    
    
    
    
    /*
     *   EQUIPE
     */
    
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
        $bc = array(array('link' => '#', 'page' => lang('Menu')));
        $meta = array('page_title' => lang('Equipe'), 'bc' => $bc);
        $this->page_construct('cadastros/equipe_projeto/index', $meta, $this->data);
        // $this->load->view($this->theme . 'escopo', $this->data);
        //$this->load->view($this->theme . 'menu', $this->data);
    } 
    
    
    
     public function add_equipe($id = null)
    {
        $this->sma->checkPermissions();

        //$this->form_validation->set_rules('projeto', lang("Projeto"), 'required'); 
       // $this->form_validation->set_rules('grupo', lang("Grupo da Pergunta"), 'required');
        $this->form_validation->set_rules('nome', lang("nome da equipe"), 'required');
       
        
        $date_cadastro = date('Y-m-d H:i:s');               
        
        if ($this->form_validation->run() == true) {
           
           
            
            $usuario = $this->session->userdata('user_id');
            $projetos_usuario = $this->site->getProjetoAtualByID_completo($usuario);
            
            $nome = $this->input->post('nome');
           
            
            $data_pergunta = array(
                'nome' => $nome,
                'projeto' => $projetos_usuario->projeto_atual   
               
            );
             
           $id_equipe = $this->atas_model->addEquipeProjeto($data_pergunta);
            $this->session->set_flashdata('message', lang("Equipe Criada com Sucesso!!!"));
            
            redirect("Cadastros/add_equipe_mebros/".$id_equipe);
            
        } else {

             $this->data['id'] = $id;
           
             
            
            $this->data['pesquisa'] = $this->atas_model->getPesquisaByID($id);
            $this->data['grupo_perguntas'] = $this->atas_model->getGrupoByIDPesquisa($id);
            $this->data['perguntas'] = $this->atas_model->getAllPerguntas($id);
            
            $bc = array(array('link' => base_url(), 'page' => lang('Pesquisa de Satisfação')), array('link' => '#', 'page' => lang('Editar Pesquisa')));
            $meta = array('page_title' => lang('Editar Pesquisa'), 'bc' => $bc);
            $this->page_construct('cadastros/equipe_projeto/add', $meta, $this->data);
           
            
      
        }
    }  
    
     public function edit_equipe($id_pergunta, $id_pesquisa)
    {
     $this->sma->checkPermissions();
     
        //echo 'AKIII'.$id; exit;
        
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
            
        }
        
        $this->form_validation->set_rules('grupoPergunta', lang("Grupo da Pergunta"), 'required');
        $this->form_validation->set_rules('pergunta', lang("Pergunta"), 'required');
        
        if ($this->form_validation->run() == true) {
           
            $pergunta = $this->input->post('pergunta');
            $grupo = $this->input->post('grupoPergunta');
            $id_pergunta = $this->input->post('id_pergunta');
            $id_pesquisa = $this->input->post('id_pesquisa');
             
            
           
            $data_pergunta = array(
                'grupo_pergunta' => $grupo,
                'pergunta' => $pergunta   
               
            );
             
            $this->atas_model->updatePergunta($id_pergunta,$data_pergunta);
            $this->session->set_flashdata('message', lang("Pergunta Alterada com Sucesso!!!"));
            
            redirect("Cadastros/pesquisa_satisfacao_add_pergunta/".$id_pesquisa);
            
        } else {
        
             $this->data['pesquisa'] = $this->atas_model->getPesquisaByID($id_pesquisa);
             $this->data['pergunta'] = $this->atas_model->getPerguntaByID($id_pergunta);
             $this->data['grupo_perguntas'] = $this->atas_model->getGrupoByIDPesquisa($id_pesquisa);
        
            $this->load->view($this->theme . 'cadastros/pesquisa_satisfacao/edit_pergunta', $this->data);
        }
    }
    
    public function delete_equipe($id_pergunta, $id_pesquisa)
    {
     $this->sma->checkPermissions();
     
           
             
            $this->atas_model->deletePergunta($id_pergunta);
            $this->session->set_flashdata('message', lang("Pergunta Apagada com Sucesso!!!"));
            
            redirect("Cadastros/pesquisa_satisfacao_add_pergunta/".$id_pesquisa);
    }
    
    
    public function add_equipe_mebros($id = null)
    {
        $this->sma->checkPermissions();

        //$this->form_validation->set_rules('projeto', lang("Projeto"), 'required'); 
       // $this->form_validation->set_rules('grupo', lang("Grupo da Pergunta"), 'required');
        $this->form_validation->set_rules('papel', lang("papel do Menbro"), 'required');
       
        
        $date_cadastro = date('Y-m-d H:i:s');               
        
        if ($this->form_validation->run() == true) {
           
            
            $usuario = $this->session->userdata('user_id');
            $projetos_usuario = $this->site->getProjetoAtualByID_completo($usuario);
            $nome = $this->input->post('nome');
           
            $data_equipe = array(
                'nome' => $nome,
                'projeto' => $projetos_usuario->projeto_atual   
               
            );
             $this->atas_model->updateEquipe($id,$data_equipe);
            
            $responsavel = $this->input->post('responsavel');
            $papel = $this->input->post('papel');
            $equipe = $this->input->post('id');
            
            
            if($papel){
            
               
             foreach ($responsavel as $responsa) {
                 
              $data_mebro = array(
              'usuario' => $responsa,
              'equipe' => $equipe,
              'papel' => $papel
            );
              
              if($responsa){
             
                $this->atas_model->addMebroEquipeProjeto($data_mebro);
              }
            }
            
            }
             
          
             
           // $this->atas_model->addPerguntaPesquisaSatisfacao($data_pergunta);
            $this->session->set_flashdata('message', lang("Equipe Criada com Sucesso!!!"));
            
            redirect("Cadastros/add_equipe_mebros/".$equipe);
            
        } else {

             $this->data['id'] = $id;
           
             
            
            $this->data['equipe'] = $this->atas_model->getEquipeByID($id);
            
           
            
            $this->data['users'] = $this->atas_model->getAllUsersSetores();
            $this->data['papeis'] = $this->atas_model->getAllPapeisResponsabilidades();
            
            $bc = array(array('link' => base_url(), 'page' => lang('Pesquisa de Satisfação')), array('link' => '#', 'page' => lang('Editar Pesquisa')));
            $meta = array('page_title' => lang('Editar Pesquisa'), 'bc' => $bc);
              $this->page_construct('cadastros/equipe_projeto/add_mebro', $meta, $this->data);
           
            
      
        }
    }
    
      public function delete_membro_equipe($id)
    {
     $this->sma->checkPermissions();
     
           
             
            $this->atas_model->deleteMebroEquipe($id);
            $this->session->set_flashdata('message', lang("Pergunta Apagada com Sucesso!!!"));
            
            redirect("Cadastros/equipe");
    }
    
    
    
    /*
     * CADATRO DE PERÍODO DE HORA EXTRA
     */
    
     public function periodo_hora_extra($id = null) {
            
        $this->sma->checkPermissions();
        
        if ($this->Settings->version == '2.3') {
            $this->session->set_flashdata('warning', 'Please complete your update by synchronizing your database.');
            redirect('sync');
        }
        
        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        
        
        $usuario = $this->session->userdata('user_id');
        $projetos_usuario = $this->site->getProjetoAtualByID_completo($usuario);
        $this->data['equipes'] = $this->atas_model->getEquipeByProjeto($projetos_usuario->projeto_atual);
        $bc = array(array('link' => '#', 'page' => lang('Menu')));
        $meta = array('page_title' => lang('Equipe'), 'bc' => $bc);
        $this->page_construct('cadastros/equipe_projeto/index', $meta, $this->data);
        // $this->load->view($this->theme . 'escopo', $this->data);
        //$this->load->view($this->theme . 'menu', $this->data);
    } 
    
    
    
      
    
   
}