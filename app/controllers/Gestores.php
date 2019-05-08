<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Gestores extends MY_Controller
{
    
        function __construct()
    {
        parent::__construct();
        $this->lang->load('auth', $this->Settings->user_language);
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
        
        $this->load->library('ion_auth');
        
        $this->load->model('projetos_model');
        $this->load->model('atas_model');
        $this->load->model('site');
        $this->digital_upload_path = 'assets/uploads/projetos';
        $this->upload_path = 'assets/uploads/projetos';
        $this->thumbs_path = 'assets/uploads/thumbs/projetos';
         $this->image_types = 'gif|jpg|jpeg|png|tif';
        $this->digital_file_types = 'zip|psd|ai|rar|pdf|doc|docx|xls|xlsx|ppt|pptx|gif|jpg|jpeg|png|tif|txt';
    }

    function index()
    {

      $this->sma->checkPermissions();

        

       $this->data['users'] = $this->site->getListGestores(2);
        $meta =  array('page_title' => lang('Gestores'), 'bc' => $bc);
        $this->page_construct('gestores/index', $meta, $this->data);

    }
    
    
   
  
    
     public function edit($id = null)
    {
        $this->sma->checkPermissions();
      
       $this->form_validation->set_rules('id', lang("Usuario"), 'required');
                 
        
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        
          if ($this->form_validation->run() == true) {
           
            $id_usuario = $this->input->post('id');
            $setor = $this->input->post('setor');
           
            
            $usuario = $this->session->userdata('user_id');
            $projetos = $this->site->getProjetoAtualByID_completo($usuario);
            $cadastroUsuario = $this->site->getPerfilAtualByID($usuario);
            $projeto_atual = $cadastroUsuario->projeto_atual;
            
            $this->site->AtualizarsetoresVinculados($projeto_atual, $setor, $id_usuario);
        
            
            $this->session->set_flashdata('message', lang("Gestor Atualizado com Sucesso!!!"));
            redirect("Gestores/index");
            
        } else {

            $this->data['id'] = $id;
           
            $this->data['projeto'] = $this->projetos_model->getProjetoByID($id);
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
          //  $this->data['providers'] = $this->site->getAllCompanies('supplier');
            $this->data['setores'] = $this->ion_auth->setores()->result_array();
            
            $usuario = $this->session->userdata('user_id');
            $projetos = $this->site->getProjetoAtualByID_completo($usuario);
            $cadastroUsuario = $this->site->getPerfilAtualByID($usuario);
            $projeto_atual = $cadastroUsuario->projeto_atual;
           
            $this->data['setores_vinculados'] = $this->site->getAllSetoresVinculados($id,$projeto_atual);
            
             //
            $bc = array(array('link' => base_url('index.php/gestores'), 'page' => lang('Gestores')), array('link' => '#', 'page' => lang('Vincular Setor ao Gestor')));
            
            
             $meta =  array('page_title' => lang('Eventos'), 'bc' => $bc);
      $this->page_construct('gestores/edit', $meta, $this->data);
           //$this->load->view($this->theme . 'gestores/edit', $this->data);

            }
    }
 
   
    
   
    
     
    
    
    /*******************************************
     * ************S U P E R I N T E D E N T E S***************
     *****************************************/
    
    public function superintendentes()
    {

      $this->sma->checkPermissions();

        

       $this->data['users'] = $this->site->getListGestores(3);
        $meta =  array('page_title' => lang('Superintendentes'), 'bc' => $bc);
        $this->page_construct('superintendentes/index', $meta, $this->data);

    }
    
     public function edit_superintendente($id = null)
    {
        $this->sma->checkPermissions();
      
       $this->form_validation->set_rules('id', lang("Usuario"), 'required');
                 
        
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        
          if ($this->form_validation->run() == true) {
           
            $id_usuario = $this->input->post('id');
            $setor = $this->input->post('setor');
           
            
            $usuario = $this->session->userdata('user_id');
            $projetos = $this->site->getProjetoAtualByID_completo($usuario);
            $cadastroUsuario = $this->site->getPerfilAtualByID($usuario);
            $projeto_atual = $cadastroUsuario->projeto_atual;
            
            $this->site->AtualizarAreasVinculados($projeto_atual, $setor, $id_usuario);
        
            
            $this->session->set_flashdata('message', lang("Cadastro Atualizado com Sucesso!!!"));
            redirect("Gestores/superintendentes");
            
        } else {

            $this->data['id'] = $id;
           
            $this->data['projeto'] = $this->projetos_model->getProjetoByID($id);
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
          //  $this->data['providers'] = $this->site->getAllCompanies('supplier');
            $this->data['setores'] = $this->ion_auth->superintendencia()->result_array();
            
            $usuario = $this->session->userdata('user_id');
            $projetos = $this->site->getProjetoAtualByID_completo($usuario);
            $cadastroUsuario = $this->site->getPerfilAtualByID($usuario);
            $projeto_atual = $cadastroUsuario->projeto_atual;
           
            $this->data['setores_vinculados'] = $this->site->getAllAreasVinculadas($id,$projeto_atual);
            
             //
            $bc = array(array('link' => base_url(), 'page' => lang('Gestores')), array('link' => '#', 'page' => lang('Vincular Setor ao Gestor')));
            
            
             $meta =  array('page_title' => lang('Eventos'), 'bc' => $bc);
            $this->page_construct('superintendentes/edit', $meta, $this->data);
           //$this->load->view($this->theme . 'gestores/edit', $this->data);

            }
    }
    
    
    
    /**************************************************
     ************* USUÁRIOS LISTAS     ****************
     **************************************************/
    
    public function usuarios_listas()
    {
      $this->sma->checkPermissions();

       $usuario = $this->session->userdata('user_id');
       $projetos = $this->site->getProjetoAtualByID_completo($usuario);
       $cadastroUsuario = $this->site->getPerfilAtualByID($usuario);
       $projeto_atual = $cadastroUsuario->projeto_atual;
  
      $superintendencias = $this->site->getAllSuperintendencia();
        foreach ($superintendencias as $area) {
            $setores = $this->site->getAllSuperintendenciaSetor($area->superintendencia_id);
            foreach ($setores as $setor) {
                $setor_selecionado = $setor->id_setor;
                
                $users = $this->site->getAllUserSetor($setor_selecionado);
                foreach ($users as $plano) {
                    
                    $usuario = $plano->id;
                   
                    /*
                     * Ele pesquisa se já tem esse usuário na tabela. se nao tiver ele faz o insert
                     */
                    $cont_encontrou = 0;
                    $users_lista_participantes = $this->site->getAllUserListaParticipantes($usuario,$projeto_atual);
                   
                    foreach ($users_lista_participantes as $user_lista) {
                        
                        $users_lista = $users_lista_participantes->users;
                       // echo $users_lista;
                        if($usuario == $users_lista ){
                            $cont_encontrou = 1;
                           // echo 'encontrou'; exit;
                        }
                        
                    }
                    
                    if($cont_encontrou == 0){
                        //NÃO ENCONTROU
                        //FAZ O INSERT
                        $data_ata = array(
                            'projeto' => $projeto_atual,
                            'users' => $usuario
                        );
                        $this->site->addListaUsersParticipantes($data_ata);
                        
                    }
                    
                    
                }
                
            }
           
        }
    
        //getAllUser()
      $this->data['superintendencias'] = $this->site->getAllSuperintendencia();   
      // $this->data['users'] = $this->site->getAllUserSetor(3);
        $meta =  array('page_title' => lang('Usuários'), 'bc' => $bc);
        //$this->page_construct('usuarios_lista/index', $meta, $this->data);
        $this->load->view($this->theme . 'usuarios_lista/index', $this->data);

    }
    
    public function edit_usuario_lista_form($id = null){
        
        $usuario = $this->session->userdata('user_id');
       $projetos = $this->site->getProjetoAtualByID_completo($usuario);
       $cadastroUsuario = $this->site->getPerfilAtualByID($usuario);
       $projeto_atual = $cadastroUsuario->projeto_atual;                           
        
        $superintendencias = $this->site->getAllSuperintendencia(); 
          foreach ($superintendencias as $area) {
               $setores = $this->site->getAllSuperintendenciaSetor($area->superintendencia_id);  
                    foreach ($setores as $setor) {
                         $setor_selecionado = $setor->id_setor;

                            if($setor_selecionado){
                                
                                /*
                                 * AQUI LIMPA TODOS OS CAMPOS DA TABELA PARA NULO
                                 */
                               
                                
                                $users = $this->site->getAllUserSetor($setor_selecionado);
                                foreach ($users as $plano) {
                    
                                    $usuario = $plano->id;
                                    $users_lista_participantes = $this->site->getAllUserListaParticipantes($usuario,$projeto_atual);
                                    foreach ($users_lista_participantes as $user_lista) {
                                        $users_lista = $users_lista_participantes->users;
                                        $data_lista = array(
                                            'participante_atas' => null,
                                            'usuario_ata' => null,
                                            'treinamentos' => null
                                        );
                                        $this->site->updateUsuariosLista($users_lista,$projeto_atual,$data_lista);

                                    }
                                }
                                
                                /*
                                 * COLUNA PARTICIPAÇÃO ATA
                                 */
                                $participacao = $this->input->post('participacao'.$setor_selecionado );
                                foreach ($participacao as $participacao_ata) {
                                  if($participacao_ata != 0) {
                                    
                                     $data_participantes = array(
                                    'participante_atas' => 1
                                    );
                                  /*
                                   * EDITA O CAMPO PARTICIPAÇÃO ATA PARA CADA USUÁRIO SELECIONADO
                                   */
                                    $this->site->updateUsuariosLista($participacao_ata,$projeto_atual,$data_participantes);
                                    
                                  }  
                                }
                                
                                /*
                                 * COLUNA VÍNCULO USUÁRIO
                                 */
                                
                                 $usuarios_vinculos = $this->input->post('usuarios'.$setor_selecionado );
                                foreach ($usuarios_vinculos as $vinculo_usuario) {
                                  if($vinculo_usuario != 0) {
                                    
                                     $data_usuarios = array(
                                    'usuario_ata' => 1
                                    );
                                  /*
                                   * EDITA O CAMPO PARTICIPAÇÃO ATA PARA CADA USUÁRIO SELECIONADO
                                   */
                                    $this->site->updateUsuariosLista($vinculo_usuario,$projeto_atual,$data_usuarios);
                                    
                                  }  
                                }
                                
                                
                                /*
                                 * COLUNA TREINAMENTOS
                                 */
                                
                                 $treinamentos_usuarios = $this->input->post('treinamentos'.$setor_selecionado );
                                foreach ($treinamentos_usuarios as $lista_treinamento) {
                                  if($lista_treinamento != 0) {
                                   
                                    $data_treinamento = array(
                                    'treinamentos' => 1
                                    );
                                  /*
                                   * EDITA O CAMPO PARTICIPAÇÃO ATA PARA CADA USUÁRIO SELECIONADO
                                   */
                                    $this->site->updateUsuariosLista($lista_treinamento,$projeto_atual,$data_treinamento);
                                    
                                  }  
                                }
                                
                    }     
                           
                    }
            }
           
         
         redirect("Login_Projetos/menu");
         
    }
}