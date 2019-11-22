<?php  defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends MY_Controller
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
        $this->load->model('atas_model');
        $this->load->model('user_model');
        $this->load->model('projetos_model');
        $this->load->model('owner_model');
        $this->load->model('networking_model');
        $this->load->model('site');
        $this->digital_upload_path = 'assets/uploads/historico_acoes/';
        $this->upload_path = 'assets/uploads/historico_acoes/';
        $this->thumbs_path = 'assets/uploads/thumbs/';
        $this->image_types = 'gif|jpg|jpeg|png|tif';
        $this->digital_file_types = 'zip|psd|ai|rar|pdf|doc|docx|xls|xlsx|ppt|pptx|gif|jpg|jpeg|png|tif|txt|xlt|xltx|webm|pcm|wav';
    }

    public function index()
    {
        
        if ($this->Settings->version == '2.3') {
            $this->session->set_flashdata('warning', 'Please complete your update by synchronizing your database.');
            redirect('sync');
        }
        
        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
       
        $usuario = $this->session->userdata('user_id');                     
        // $this->data['planos'] = $this->atas_model->getAllPlanosUser($usuario);
        $users = $this->site->geUserByID($usuario);
        $bc = array(array('link' => '#', 'page' => lang('NOME : '.$users->first_name .' '. $users->last_name .' | SETOR : '.$users->company)));
        $meta = array('page_title' => lang('Ações'), 'bc' => $bc);
        //$this->page_construct('usuarios/index', $meta, $this->data);
      
      
        // SALVA O MÓDULO ATUAL do usuário
         $data_modulo = array('modulo_atual' => 3, 'menu_atual' => 23);
         $this->owner_model->updateModuloAtual($usuario, $data_modulo);
        // registra o log de movimentação
        
        $date_hoje = date('Y-m-d H:i:s');    
        $usuario = $this->session->userdata('user_id');
        $empresa = $this->session->userdata('empresa');
        $ip = $_SERVER["REMOTE_ADDR"];

        $logdata = array('date' => date('Y-m-d H:i:s'), 
            'type' => 'ADMIN', 
            'description' => 'Acessou o menu HOME do Módulo NETWORKING - da empresa '.$empresa,  
            'userid' => $this->session->userdata('user_id'), 
            'ip_address' => $_SERVER["REMOTE_ADDR"],
            'tabela' => '',
            'row' => '',
            'depois' => '', 
            'modulo' => 'Networking',
            'funcao' => 'Welcome/index',  
            'empresa' => $this->session->userdata('empresa'));
        $this->owner_model->addLog($logdata);  
        
        $this->data['pagina'] = 'networking/home';
        $this->data['ativo'] = 'home';
        $this->data['menu'] = 'home'; //footer
        $this->data['home'] = '1';
        
       // $this->data['exibe_rodape'] = 'nao';
       
      $this->page_construct_networking_home('networking/home/index', $meta, $this->data);
    //    $this->load->view($this->theme . 'networking/home/index', $this->data);
    }
    
    /********* M U R A L  D E  P U B L I C A Ç Õ E S **************************/
    public function mural_networking($tabela,  $menu)
    {
          
       $this->form_validation->set_rules('id_cadastro_profile', lang("id_cadastro"), 'required');
        
       if ($this->form_validation->run() == true) {
            
            
            //$projetos = $this->projetos_model->getProjetoAtualByID_completo();
            //$id_projeto = $projetos->id;

            $date_hoje = date('Y-m-d H:i:s');    
            $usuario = $this->session->userdata('user_id');
            $empresa = $this->session->userdata('empresa');   
            
            $data_admissao = $this->input->post('data_admissao');
            $data_nascimento = $this->input->post('data_nascimento');
            $ramal = $this->input->post('ramal');
            $celular1 = $this->input->post('celular1');
            $celular_corportativo = $this->input->post('celular_corportativo');
            $linkedin = $this->input->post('linkedin');
            $inputSkills = $this->input->post('inputSkills');
            $formacaoAcademica = $this->input->post('formacaoAcademica');
            $mensagemPerfil = $this->input->post('mensagemPerfil');
           
            
            $data_profile = array(
                'phone' => $celular1,
                'corporativo' => $celular_corportativo,
                'data_aniversario' => $data_nascimento,
                'data_admissao' => $data_admissao,
                'ramal' => $ramal,
                'linkedin' => $linkedin,
                'habilidades' => $inputSkills,
                'formacao_academica' => $formacaoAcademica,
                'mensagem_perfil' => $mensagemPerfil
            );
           
            $this->owner_model->updateCadastro($usuario, 'users', $data_profile);
            

            $ip = $_SERVER["REMOTE_ADDR"];

            
            // exit;

            $this->session->set_flashdata('message', lang("Perfil Atualizado com Sucesso!!!"));
            redirect("welcome/profile");
        } else {
       
       $usuario = $this->session->userdata('user_id');
       $data_modulo = array('modulo_atual' => 3, 'menu_atual' => 74);
       $this->owner_model->updateModuloAtual($usuario, $data_modulo);
       
       $dados_user = $this->site->getUserSetorByUser($usuario);
       $setor_id = $dados_user->setor_id;
       $empresa = $dados_user->empresa_id;
       $this->data['empresa'] = $empresa;
       $this->data['posts_profile'] = $this->networking_model->getAllPostMuralNetworkingByEmpresa($setor_id);
       $this->page_construct_networking('networking/mural/index', $meta, $this->data);
        // $this->load->view($this->theme . 'networking/profile/index', $this->data);
        
        } 
    }
    
    public function visualizaImagemHome($id, $imagem)
    {
        
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
       
        $this->data['id'] = $id;
        $this->data['imagem'] = $imagem;
        
        //$this->load->view($this->theme . 'projetos/documentacao/add', $this->data);
        $this->load->view($this->theme . 'networking/home/visualizar_img_home', $this->data);
           

            
    }
    
    public function novaPostagem_mural() {


        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

         $this->form_validation->set_rules('nova_postagem', lang("id_cadastro"), 'required');
        
        if ($this->form_validation->run() == true) {
            
            
            //$projetos = $this->projetos_model->getProjetoAtualByID_completo();
            //$id_projeto = $projetos->id;

            $date_hoje = date('Y-m-d H:i:s');    
            $usuario = $this->session->userdata('user_id');
            $empresa = $this->session->userdata('empresa');   
            $texto = $this->input->post('texto');
            $tipo = 2;
            $ip = $_SERVER["REMOTE_ADDR"];

            //echo $assunto; exit;
            $data_postagem = array(
                'empresa' => $empresa,
                'user_de' => $usuario,
                'user_para' => $usuario,
                'tipo' => $tipo,
                'descricao' => $texto,
                'data_postagem' => $date_hoje,
                'status' => 1,
                'mural' => 1
            );
           
           // print_r($data_postagem); exit;
           
          // IMAGEM 1
            if ($_FILES['document1']['size'] > 0) {
                $this->load->library('upload');
                //$config['upload_path'] = $this->upload_path;
                $config['upload_path'] = 'assets/uploads/'.$empresa.'/posts/';
                $config['allowed_types'] = $this->digital_file_types;
                $config['max_size'] = $this->allowed_file_size;
                $config['overwrite'] = false;
                $config['encrypt_name'] = true;
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('document1')) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect($_SERVER["HTTP_REFERER"]);
                }
                $photo = $this->upload->file_name;
                $data_postagem['imagem1'] = $photo;
            }
            
            // IMAGEM 2
            if ($_FILES['document2']['size'] > 0) {
                $this->load->library('upload');
                //$config['upload_path'] = $this->upload_path;
                $config['upload_path'] = 'assets/uploads/'.$empresa.'/posts/';
                $config['allowed_types'] = $this->digital_file_types;
                $config['max_size'] = $this->allowed_file_size;
                $config['overwrite'] = false;
                $config['encrypt_name'] = true;
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('document2')) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect($_SERVER["HTTP_REFERER"]);
                }
                $photo2 = $this->upload->file_name;
                $data_postagem['imagem2'] = $photo2;
            }
            
            // IMAGEM 3
            if ($_FILES['document3']['size'] > 0) {
                $this->load->library('upload');
                //$config['upload_path'] = $this->upload_path;
                $config['upload_path'] = 'assets/uploads/'.$empresa.'/posts/';
                $config['allowed_types'] = $this->digital_file_types;
                $config['max_size'] = $this->allowed_file_size;
                $config['overwrite'] = false;
                $config['encrypt_name'] = true;
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('document3')) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect($_SERVER["HTTP_REFERER"]);
                }
                $photo3 = $this->upload->file_name;
                $data_postagem['imagem3'] = $photo3;
            }
            
            // IMAGEM 4
            if ($_FILES['document4']['size'] > 0) {
                $this->load->library('upload');
                //$config['upload_path'] = $this->upload_path;
                $config['upload_path'] = 'assets/uploads/'.$empresa.'/posts/';
                $config['allowed_types'] = $this->digital_file_types;
                $config['max_size'] = $this->allowed_file_size;
                $config['overwrite'] = false;
                $config['encrypt_name'] = true;
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('document4')) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect($_SERVER["HTTP_REFERER"]);
                }
                $photo4 = $this->upload->file_name;
                $data_postagem['imagem4'] = $photo4;
            }
            
            // IMAGEM 5
            if ($_FILES['document5']['size'] > 0) {
                $this->load->library('upload');
                //$config['upload_path'] = $this->upload_path;
                $config['upload_path'] = 'assets/uploads/'.$empresa.'/posts/';
                $config['allowed_types'] = $this->digital_file_types;
                $config['max_size'] = $this->allowed_file_size;
                $config['overwrite'] = false;
                $config['encrypt_name'] = true;
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('document5')) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect($_SERVER["HTTP_REFERER"]);
                }
                $photo5 = $this->upload->file_name;
                $data_postagem['imagem5'] = $photo5;
            }
            
            $id_cadastro =  $this->networking_model->addPostagemNetworking($data_postagem);
            
            $logdata = array('date' => date('Y-m-d H:i:s'),
                'type' => 'INSERT',
                'description' => 'Cadastro de uma nova publicação, ID: '.$id_cadastro,
                'userid' => $this->session->userdata('user_id'),
                'ip_address' => $_SERVER["REMOTE_ADDR"],
                'tabela' => 'sig_postagens',
                'row' => $id_cadastro,
                'depois' => json_encode($data_postagem),
                'modulo' => 'welcome',
                'funcao' => 'welcome/novaPostagem',
                'empresa' => $this->session->userdata('empresa'));

            $this->owner_model->addLog($logdata);
            // exit;

            $this->session->set_flashdata('message', lang("Cadastro realizado com Sucesso!!!"));
            redirect("welcome/mural_networking/0/74");
        } else {

        
          $usuario = $this->session->userdata('user_id');   
          $this->data['usuario'] = $usuario;
          $this->load->view($this->theme . 'networking/mural/novaPostagem', $this->data);  
          //  $this->page_construct_project('project/cadastro_basico_modelo/plano_acao/plano', $meta, $this->data);
        }
    }
    
    /*******************F I M * *  M U R A L **********************************/
    
    
    /************** P R O F I L E**************************************/
    public function profile()
    {
          
        
         $this->form_validation->set_rules('id_cadastro_profile', lang("id_cadastro"), 'required');
        
        if ($this->form_validation->run() == true) {
            
            
            //$projetos = $this->projetos_model->getProjetoAtualByID_completo();
            //$id_projeto = $projetos->id;

            $date_hoje = date('Y-m-d H:i:s');    
            $usuario = $this->session->userdata('user_id');
            $empresa = $this->session->userdata('empresa');   
            
            $data_admissao = $this->input->post('data_admissao');
            $data_nascimento = $this->input->post('data_nascimento');
            $ramal = $this->input->post('ramal');
            $celular1 = $this->input->post('celular1');
            $celular_corportativo = $this->input->post('celular_corportativo');
            $linkedin = $this->input->post('linkedin');
            $inputSkills = $this->input->post('inputSkills');
            $formacaoAcademica = $this->input->post('formacaoAcademica');
            $mensagemPerfil = $this->input->post('mensagemPerfil');
           
            
            $data_profile = array(
                'phone' => $celular1,
                'corporativo' => $celular_corportativo,
                'data_aniversario' => $data_nascimento,
                'data_admissao' => $data_admissao,
                'ramal' => $ramal,
                'linkedin' => $linkedin,
                'habilidades' => $inputSkills,
                'formacao_academica' => $formacaoAcademica,
                'mensagem_perfil' => $mensagemPerfil
            );
           
            $this->owner_model->updateCadastro($usuario, 'users', $data_profile);
            

            $ip = $_SERVER["REMOTE_ADDR"];

            
            // exit;

            $this->session->set_flashdata('message', lang("Perfil Atualizado com Sucesso!!!"));
            redirect("welcome/profile");
        } else {
        
        $usuario = $this->session->userdata('user_id');
        $this->data['title'] = lang('profile');
        $user = $this->ion_auth->user($usuario)->row();
        $this->data['user'] = $user;
  
        $this->data['id'] = $usuario;
       
        $this->page_construct_networking('networking/profile/index', $meta, $this->data);
       // $this->load->view($this->theme . 'networking/profile/index', $this->data);
        
        } 
    }
   
    public function novaPostagem() {


        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

         $this->form_validation->set_rules('nova_postagem', lang("id_cadastro"), 'required');
        
        if ($this->form_validation->run() == true) {
            
            
            //$projetos = $this->projetos_model->getProjetoAtualByID_completo();
            //$id_projeto = $projetos->id;

            $date_hoje = date('Y-m-d H:i:s');    
            $usuario = $this->session->userdata('user_id');
            $empresa = $this->session->userdata('empresa');   
            $texto = $this->input->post('texto');
            $tipo = 1;
            $ip = $_SERVER["REMOTE_ADDR"];

            //echo $assunto; exit;
            $data_postagem = array(
                'empresa' => $empresa,
                'user_de' => $usuario,
                'user_para' => $usuario,
                'tipo' => $tipo,
                'descricao' => $texto,
                'data_postagem' => $date_hoje,
                'status' => 1,
                'mural' => 0
            );
           
           // print_r($data_postagem); exit;
           
          // IMAGEM 1
            if ($_FILES['document1']['size'] > 0) {
                $this->load->library('upload');
                //$config['upload_path'] = $this->upload_path;
                $config['upload_path'] = 'assets/uploads/'.$empresa.'/posts/';
                $config['allowed_types'] = $this->digital_file_types;
                $config['max_size'] = $this->allowed_file_size;
                $config['overwrite'] = false;
                $config['encrypt_name'] = true;
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('document1')) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect($_SERVER["HTTP_REFERER"]);
                }
                $photo = $this->upload->file_name;
                $data_postagem['imagem1'] = $photo;
            }
            
            // IMAGEM 2
            if ($_FILES['document2']['size'] > 0) {
                $this->load->library('upload');
                //$config['upload_path'] = $this->upload_path;
                $config['upload_path'] = 'assets/uploads/'.$empresa.'/posts/';
                $config['allowed_types'] = $this->digital_file_types;
                $config['max_size'] = $this->allowed_file_size;
                $config['overwrite'] = false;
                $config['encrypt_name'] = true;
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('document2')) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect($_SERVER["HTTP_REFERER"]);
                }
                $photo2 = $this->upload->file_name;
                $data_postagem['imagem2'] = $photo2;
            }
            
            // IMAGEM 3
            if ($_FILES['document3']['size'] > 0) {
                $this->load->library('upload');
                //$config['upload_path'] = $this->upload_path;
                $config['upload_path'] = 'assets/uploads/'.$empresa.'/posts/';
                $config['allowed_types'] = $this->digital_file_types;
                $config['max_size'] = $this->allowed_file_size;
                $config['overwrite'] = false;
                $config['encrypt_name'] = true;
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('document3')) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect($_SERVER["HTTP_REFERER"]);
                }
                $photo3 = $this->upload->file_name;
                $data_postagem['imagem3'] = $photo3;
            }
            
            // IMAGEM 4
            if ($_FILES['document4']['size'] > 0) {
                $this->load->library('upload');
                //$config['upload_path'] = $this->upload_path;
                $config['upload_path'] = 'assets/uploads/'.$empresa.'/posts/';
                $config['allowed_types'] = $this->digital_file_types;
                $config['max_size'] = $this->allowed_file_size;
                $config['overwrite'] = false;
                $config['encrypt_name'] = true;
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('document4')) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect($_SERVER["HTTP_REFERER"]);
                }
                $photo4 = $this->upload->file_name;
                $data_postagem['imagem4'] = $photo4;
            }
            
            // IMAGEM 5
            if ($_FILES['document5']['size'] > 0) {
                $this->load->library('upload');
                //$config['upload_path'] = $this->upload_path;
                $config['upload_path'] = 'assets/uploads/'.$empresa.'/posts/';
                $config['allowed_types'] = $this->digital_file_types;
                $config['max_size'] = $this->allowed_file_size;
                $config['overwrite'] = false;
                $config['encrypt_name'] = true;
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('document5')) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect($_SERVER["HTTP_REFERER"]);
                }
                $photo5 = $this->upload->file_name;
                $data_postagem['imagem5'] = $photo5;
            }
            
            $id_cadastro =  $this->networking_model->addPostagemNetworking($data_postagem);
              
           
            
            
            
            
            $logdata = array('date' => date('Y-m-d H:i:s'),
                'type' => 'INSERT',
                'description' => 'Cadastro de uma nova publicação, ID: '.$id_cadastro,
                'userid' => $this->session->userdata('user_id'),
                'ip_address' => $_SERVER["REMOTE_ADDR"],
                'tabela' => 'sig_postagens',
                'row' => $id_cadastro,
                'depois' => json_encode($data_postagem),
                'modulo' => 'welcome',
                'funcao' => 'welcome/novaPostagem',
                'empresa' => $this->session->userdata('empresa'));

            $this->owner_model->addLog($logdata);
            // exit;

            $this->session->set_flashdata('message', lang("Cadastro realizado com Sucesso!!!"));
            redirect("welcome/profile");
        } else {

        
          $usuario = $this->session->userdata('user_id');                     
          $this->load->view($this->theme . 'networking/profile/novaPostagem', $this->data);  
          //  $this->page_construct_project('project/cadastro_basico_modelo/plano_acao/plano', $meta, $this->data);
        }
    }
    
    public function novaPostagem_visitante($usuario_profile) {


        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

         $this->form_validation->set_rules('nova_postagem', lang("id_cadastro"), 'required');
        
        if ($this->form_validation->run() == true) {
            
            
            //$projetos = $this->projetos_model->getProjetoAtualByID_completo();
            //$id_projeto = $projetos->id;

            $date_hoje = date('Y-m-d H:i:s');    
            $usuario_de = $this->session->userdata('user_id');
            $usuario_para = $this->input->post('usuario_profile');
            $empresa = $this->session->userdata('empresa');   
            $texto = $this->input->post('texto');
            $tipo = 2;
            $ip = $_SERVER["REMOTE_ADDR"];

            //echo $assunto; exit;
            $data_postagem = array(
                'empresa' => $empresa,
                'user_de' => $usuario_de,
                'user_para' => $usuario_para,
                'tipo' => $tipo,
                'descricao' => $texto,
                'data_postagem' => $date_hoje,
                'status' => 1,
                'mural' => 0
            );
           
            //print_r($data_postagem); exit;
           
          // IMAGEM 1
            if ($_FILES['document1']['size'] > 0) {
                $this->load->library('upload');
                //$config['upload_path'] = $this->upload_path;
                $config['upload_path'] = 'assets/uploads/'.$empresa.'/posts/';
                $config['allowed_types'] = $this->digital_file_types;
                $config['max_size'] = $this->allowed_file_size;
                $config['overwrite'] = false;
                $config['encrypt_name'] = true;
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('document1')) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect($_SERVER["HTTP_REFERER"]);
                }
                $photo = $this->upload->file_name;
                $data_postagem['imagem1'] = $photo;
            }
            
            // IMAGEM 2
            if ($_FILES['document2']['size'] > 0) {
                $this->load->library('upload');
                //$config['upload_path'] = $this->upload_path;
                $config['upload_path'] = 'assets/uploads/'.$empresa.'/posts/';
                $config['allowed_types'] = $this->digital_file_types;
                $config['max_size'] = $this->allowed_file_size;
                $config['overwrite'] = false;
                $config['encrypt_name'] = true;
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('document2')) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect($_SERVER["HTTP_REFERER"]);
                }
                $photo2 = $this->upload->file_name;
                $data_postagem['imagem2'] = $photo2;
            }
            
            // IMAGEM 3
            if ($_FILES['document3']['size'] > 0) {
                $this->load->library('upload');
                //$config['upload_path'] = $this->upload_path;
                $config['upload_path'] = 'assets/uploads/'.$empresa.'/posts/';
                $config['allowed_types'] = $this->digital_file_types;
                $config['max_size'] = $this->allowed_file_size;
                $config['overwrite'] = false;
                $config['encrypt_name'] = true;
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('document3')) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect($_SERVER["HTTP_REFERER"]);
                }
                $photo3 = $this->upload->file_name;
                $data_postagem['imagem3'] = $photo3;
            }
            
            // IMAGEM 4
            if ($_FILES['document4']['size'] > 0) {
                $this->load->library('upload');
                //$config['upload_path'] = $this->upload_path;
                $config['upload_path'] = 'assets/uploads/'.$empresa.'/posts/';
                $config['allowed_types'] = $this->digital_file_types;
                $config['max_size'] = $this->allowed_file_size;
                $config['overwrite'] = false;
                $config['encrypt_name'] = true;
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('document4')) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect($_SERVER["HTTP_REFERER"]);
                }
                $photo4 = $this->upload->file_name;
                $data_postagem['imagem4'] = $photo4;
            }
            
            // IMAGEM 5
            if ($_FILES['document5']['size'] > 0) {
                $this->load->library('upload');
                //$config['upload_path'] = $this->upload_path;
                $config['upload_path'] = 'assets/uploads/'.$empresa.'/posts/';
                $config['allowed_types'] = $this->digital_file_types;
                $config['max_size'] = $this->allowed_file_size;
                $config['overwrite'] = false;
                $config['encrypt_name'] = true;
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('document5')) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect($_SERVER["HTTP_REFERER"]);
                }
                $photo5 = $this->upload->file_name;
                $data_postagem['imagem5'] = $photo5;
            }
            
            $id_cadastro =  $this->networking_model->addPostagemNetworking($data_postagem);
              
            
            $logdata = array('date' => date('Y-m-d H:i:s'),
                'type' => 'INSERT',
                'description' => 'Cadastro de uma nova publicação, ID: '.$id_cadastro,
                'userid' => $this->session->userdata('user_id'),
                'ip_address' => $_SERVER["REMOTE_ADDR"],
                'tabela' => 'sig_postagens',
                'row' => $id_cadastro,
                'depois' => json_encode($data_postagem),
                'modulo' => 'welcome',
                'funcao' => 'welcome/novaPostagem_visitante',
                'empresa' => $this->session->userdata('empresa'));

            $this->owner_model->addLog($logdata);
            // exit;

            $this->session->set_flashdata('message', lang("Publicação realizada com Sucesso!!!"));
           // redirect("welcome/profile");
            echo "<script>history.go(-1)</script>";
        } else {
            $this->data['usuario_visitante'] = $usuario_profile;
            $this->load->view($this->theme . 'networking/profile_visitor/novaPostagem', $this->data);  
          //  $this->page_construct_project('project/cadastro_basico_modelo/plano_acao/plano', $meta, $this->data);
        }
    }
    
    public function postagemEquipeSuporte($usuario_para) {
        
        $empresa = $this->session->userdata('empresa');
        $dados_empresa = $this->owner_model->getEmpresaById($empresa);
        $empresa_id = $dados_empresa->id;
        $suporte = $dados_empresa->suporte;
        
        $date_hoje = date('Y-m-d H:i:s');    
        $texto = "Seja Bem Vindo ao SigPlus. Queremos que tenha uma ótima esperiência.";
        $tipo = 2;
        $ip = $_SERVER["REMOTE_ADDR"];

            //echo $assunto; exit;
            $data_postagem = array(
                'empresa' => $empresa,
                'user_de' => $suporte,
                'user_para' => $usuario_para,
                'tipo' => $tipo,
                'descricao' => $texto,
                'data_postagem' => $date_hoje,
                'status' => 1
            );
           
           // print_r($data_postagem); exit;
            $id_cadastro =  $this->networking_model->addPostagemNetworking($data_postagem);
        
    }
    
    public function profile_visitor($usuario_profile)
    {
          
        $this->form_validation->set_rules('id_cadastro_profile', lang("id_cadastro"), 'required');
        
        if ($this->form_validation->run() == true) {
            
            
            //$projetos = $this->projetos_model->getProjetoAtualByID_completo();
            //$id_projeto = $projetos->id;

            $date_hoje = date('Y-m-d H:i:s');    
            $usuario = $this->session->userdata('user_id');
            $empresa = $this->session->userdata('empresa');   
            
            $data_admissao = $this->input->post('data_admissao');
            $data_nascimento = $this->input->post('data_nascimento');
            $ramal = $this->input->post('ramal');
            $celular1 = $this->input->post('celular1');
            $celular_corportativo = $this->input->post('celular_corportativo');
            $linkedin = $this->input->post('linkedin');
            $inputSkills = $this->input->post('inputSkills');
            $formacaoAcademica = $this->input->post('formacaoAcademica');
            $mensagemPerfil = $this->input->post('mensagemPerfil');
           
            
            $data_profile = array(
                'phone' => $celular1,
                'corporativo' => $celular_corportativo,
                'data_aniversario' => $data_nascimento,
                'data_admissao' => $data_admissao,
                'ramal' => $ramal,
                'linkedin' => $linkedin,
                'habilidades' => $inputSkills,
                'formacao_academica' => $formacaoAcademica,
                'mensagem_perfil' => $mensagemPerfil
            );
           
            $this->owner_model->updateCadastro($usuario, 'users', $data_profile);
            

            $ip = $_SERVER["REMOTE_ADDR"];

            
            // exit;

            $this->session->set_flashdata('message', lang("Perfil Atualizado com Sucesso!!!"));
            redirect("welcome/profile");
        } else {
        
        $usuario = $this->session->userdata('user_id');
        $this->data['title'] = lang('profile');
        
        $user = $this->ion_auth->user($usuario_profile)->row();
        $this->data['user'] = $user;
        $this->data['usuario'] = $usuario_profile;
        $posts_profile = $this->networking_model->getAllPostProfileVisitanteByUser($usuario_profile);
        if($posts_profile){

        }else{
           
            $this->postagemEquipeSuporte($usuario_profile);
        }
       
        $this->page_construct_networking('networking/profile_visitor/index', $meta, $this->data);
        // $this->load->view($this->theme . 'networking/profile/index', $this->data);
        
        } 
    }
    
    function colaboradores()
    {
        $usuario = $this->session->userdata('user_id');
        $this->page_construct_networking('networking/colaboradores/index', $meta, $this->data);
    }
    
   
    /******************** MODAL MENSAGEM ANIVERSARIANTES DO MÊS ***********************/
    public function novaMensagemAniversariante($aniversariante) {


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
            $mensagem = $this->input->post('mensagem');
            $aniversariante = $this->input->post('aniversariante');

            
            $data_notificacao = array(
                'id_from' => $usuario,
                'id_to' => $aniversariante,
                'title' => "Você tem uma nova publicação no seu mural.",
                'text' => "Você foi parabenizado pelo seu Aniversário.",
                'lida' => 0,
                'data' => $date_hoje,
                'email' => 0,
                'empresa' => $empresa
            );
            $this->atas_model->add_notificacoes($data_notificacao);
              
          //CADASTRAR UMA NOVA CATEGORIA
            $data_postagm = array(
                'tipo' => 2,
                'descricao' => $mensagem,
                'user_de' => $usuario,
                'user_para' => $aniversariante,
                'data_postagem' => $date_hoje,
                'empresa' => $empresa,
                'mural' => 0,
                'status' => 1
            );
           $this->networking_model->addPostagemAniversariante($data_postagm);

          //  $date_hoje = date('Y-m-d H:i:s');
          //  $usuario = $this->session->userdata('user_id');
          //  $empresa = $this->session->userdata('empresa');
            $ip = $_SERVER["REMOTE_ADDR"];

            
            // exit;

            $this->session->set_flashdata('message', lang("Mensagem Enviada com Sucesso!!!"));
            redirect("welcome/index");
        } else {

        $this->data['aniversariante'] = $aniversariante;
        $usuario = $this->session->userdata('user_id');                     
        $this->load->view($this->theme . 'networking/home/mensagemAniversariante', $this->data);  
          //  $this->page_construct_project('project/cadastro_basico_modelo/plano_acao/plano', $meta, $this->data);
        }
    }
    
     /************************************************************************************************************
     *************************** CADASTROS BÁSICOS MODELO PADRÃO 1 **********************************************
     ************************************************************************************************************/
    
    
    public function novoCadastroBasico($tabela,  $menu, $funcao)
    {
        $date_cadastro = date('Y-m-d H:i:s');                           
        
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        
        $this->form_validation->set_rules('id_cadastro', lang("id_cadastro"), 'required');
         if ($this->form_validation->run() == true) {
           
             $tabela_id = $this->input->post('tabela_id');
             $tabela_nome = $this->input->post('tabela_nome');
             $funcao = $this->input->post('funcao');
             $menu_id = $this->input->post('menu_id');
             
            // echo $tabela_nome.'<br>';exit;
             $campos = $this->owner_model->getAllCamposTablesCadastro($tabela_id);
             $data_modulo = array();
              foreach ($campos as $habilitado) {
                    $campo_banco = $habilitado->campo;
                    $nome_campo = $habilitado->nome_campo;
                    $tipo_campo = $habilitado->tipo_campo;
                    $tipo_texto = $habilitado->tipo_texto;
                    $tamanho = $habilitado->tamanho;
                    $obrigatorio = $habilitado->obrigatorio;
                    $sessao = $habilitado->sessao;
                    
                    $campo_cadastro = $this->input->post($campo_banco);
                    $data_modulo[] = array(
                        $campo_banco => $campo_cadastro,
                    );
                    
                   
                    
              }
              $data_campos_cadastro = call_user_func_array('array_merge', $data_modulo);
              $tabela_sig = substr($tabela_nome, 4);
             // print_r($data_campos_cadastro);  exit;
            //  echo '<br>'.$tabela_sig;
              $id_cadastro =  $this->owner_model->addCadastro($tabela_sig, $data_campos_cadastro);
           //   echo '<br>'.$id_cadastro;
             
              
              
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
                'modulo' => 'networking',
                'funcao' => 'networking/cadastroBasicoModelo',  
                'empresa' => $this->session->userdata('empresa'));
           
               $this->owner_model->addLog($logdata);  
           // exit;
            
            $this->session->set_flashdata('message', lang("Cadastro realizado com Sucesso!!!"));
            redirect("welcome/$funcao/$tabela_id/$menu_id");
            
         }else{
        
            
           
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
       
        //$this->load->view($this->theme . 'projetos/documentacao/add', $this->data);
        $this->load->view($this->theme . 'networking/cadastro_basico_modelo/cadastroBasico', $this->data);
         }   

            
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
           // echo $funcao; exit;
            
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
            redirect("welcome/$funcao/$tabela_id/$menu_id");
            
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
            $this->load->view($this->theme . 'networking/cadastro_basico_modelo/editarCadastro', $this->data);
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
            if($funcao == 0){
                 echo "<script>history.go(-1)</script>";
            }else{
                redirect("welcome/$funcao/$tabela_id/$menu_id");
            }
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
           $this->load->view($this->theme . 'networking/cadastro_basico_modelo/deletarCadastro', $this->data);
         }
         
         
    }
    
    public function retorno($id = null)
    {
     
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        
        $this->form_validation->set_rules('porque', lang("Por quê"), 'required');
        $this->form_validation->set_rules('onde', lang("Onde"), 'required');
        $this->form_validation->set_rules('como', lang("Como"), 'required');
        
         if ($this->form_validation->run() == true) {
           
             
             
            $date_hoje = date('Y-m-d H:i:s');    
            $usuario = $this->session->userdata('user_id');  
            $idplano = $this->input->post('id');
            $porque = $this->input->post('porque');
            $onde = trim($this->input->post('onde')); 
            $como = trim($this->input->post('como')); 
            $macroprocesso = trim($this->input->post('macroprocesso')); 
            $observacao = trim($this->input->post('observacao')); 
            $ip = $_SERVER["REMOTE_ADDR"];
            
            $data_acao = array(
                'porque' => $porque,
                'onde' => $onde,
                'como' => $como,
                'macroprocesso' => $macroprocesso,
                'data_retorno_usuario' => $date_hoje,
                'status' => 'AGUARDANDO VALIDAÇÃO'
            );
            
            $data_historicoAcao = array(
                'data_envio' => $date_hoje,
                'usuario' => $usuario,
                'plano' => $idplano,
                'observacao' => $observacao,
                'ip' => $ip
            );
          
            $this->atas_model->updatePlano($idplano, $data_acao);
            $this->atas_model->add_historicoPlanoAcao( $data_historicoAcao);
            
            $this->session->set_flashdata('message', lang("Retorno da ação enviada com Sucesso!!!"));
            redirect("welcome");
            
         }else{
                  
            $this->data['idplano'] = $id;
            $this->data['acoes'] = $this->atas_model->getAllAcoes($id);
            $this->load->view($this->theme . 'usuarios/acaoRetorno', $this->data);
         
            
         }
    }
    /***********************************************************************************************************************/

    
     /***********************************************************************************************************************
      ************************************************* M I N H A S    A Ç Õ E S ********************************************
     ************************************************************************************************************************/
    public function minhasAcoes($projeto_filtro, $tipo)
    {
        // $this->sma->checkPermissions();
        if ($this->Settings->version == '2.3') {
            $this->session->set_flashdata('warning', 'Please complete your update by synchronizing your database.');
            redirect('sync');
        }
        $usuario = $this->session->userdata('user_id');     
        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));

                
         // SALVA O MÓDULO ATUAL do usuário
         $data_modulo = array('modulo_atual' => 3, 'menu_atual' => 69);
         $this->owner_model->updateModuloAtual($usuario, $data_modulo);

               
        $projeto_filtro = $this->input->post('projeto_filtro');
        $status_filtro = $this->input->post('status_filtro');
       
        
        if($tipo == 'CONCLUÍDO'){
            $status_filtro = 'CONCLUÍDO';
        }else if($tipo == 'PENDENTE'){
            $status_filtro = 'PENDENTE';
        }else if($tipo == 'ATRASADO'){
            $status_filtro = 'ATRASADO';
        }else if($tipo == 'AGUARDANDO VALIDAÇÃO'){
            $status_filtro = 'AGUARDANDO VALIDAÇÃO';
        }else if($tipo == 'CANCELADO'){
            $status_filtro = 'CANCELADO';
        }
        
        
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
        
       
       
        $this->data['planos'] = $this->networking_model->getAllAcoesUserById_User($projeto_filtro, $status_filtro);
        
        $this->data['planos_resumo'] = $this->networking_model->getAllAcoesUserById_UserSemFiltro($projeto_filtro);
        
        //$users = $this->site->geUserByID($usuario);
      
        
        $this->data['projeto_filtro'] = $projeto_filtro; //footer
        $this->data['status_filtro'] = $status_filtro;
        
       
        
        $this->page_construct_networking('networking/minhas_acoes/index', $meta, $this->data); 
      

    }
    
    // visualiza as ações em : Network > Minhas Ações
    public function dados_cadastrais_acao($id_acao = null, $projeto_filtro = null, $status_filtro = null)
    {
     $this->sma->checkPermissions();
     
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
            }
            $date_hoje = date('Y-m-d H:i:s');    
            $empresa = $this->session->userdata('empresa');            
           
            $ip = $_SERVER["REMOTE_ADDR"];
           // $this->data['acoes_vinculos'] =  $this->atas_model->getAllAcoesProjeto($projetos_usuario->projeto_atual, $id_acao);//$this->atas_model->getAllAcoes();
            
            $acao = $this->networking_model->getPlanoByIdAndUsuario($id_acao);
            $projeto = $acao->projeto;
           
            $acao_empresa = $acao->empresa;
            
            if($empresa != $acao_empresa){
                $this->session->set_flashdata('error', lang("Você não tem permissão para acessar esta ação!!!"));
                redirect("welcome/minhasAcoes");
            }
            
             
            $this->data['acoes_vinculadas'] = $this->atas_model->getAllAcoesVinculadasAta($id_acao);
            // echo $this->input->post('prazo') .'<br>';
            $this->data['acoes_arquivos'] = $this->atas_model->getAllArquivosByAcao($id_acao); 
            
            if($projeto){
            $this->data['eventos'] = $this->projetos_model->getAllEventosItemEventoByProjetoAcao($projeto);   
            }                                         
            
            $this->data['users'] = $this->atas_model->getAllUsersSetores(); 
            //$this->data['macro'] = $this->atas_model->getAllMacroProcesso();
            
           if($projeto){
            $this->data['acoes'] = $this->networking_model->getAllAcoesProjeto($id_acao, $projeto);
            
           }
            $this->data['idplano'] = $id_acao;
            
            $this->data['projeto_filtro'] = $projeto_filtro;
            $this->data['status_filtro'] = $status_filtro;
           
            
            //atualiza o status das mensagens para esta ação
            $mensagem_acao = $this->networking_model->getQtdeMensagensNaoLidasByAcaoAndUsuario($id_acao);
            $qtde_msg_acao = $mensagem_acao->quantidade;
            if($qtde_msg_acao > 0){
            // ATUALIZA O STATUS DA MENSAGEM
             $data_msg = array('lida' => 1, 'data_lida' => $date_hoje);
             $this->networking_model->updatestatusMensagem($id_acao, $data_msg);
            }
            $this->page_construct_networking('networking/minhas_acoes/editAcao', $meta, $this->data);
        
      
            
            
         
    }
    
    //Modal retorno da ação do usuário
    public function retorno_new($id = null)
    {
     
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        
        $this->form_validation->set_rules('idplano', lang("idplano"), 'required');
        $this->form_validation->set_rules('cadastrar', lang("cadastrar"), 'required');
        
         if ($this->form_validation->run() == true) {
             
            $date_hoje = date('Y-m-d H:i:s');    
            $usuario = $this->session->userdata('user_id');  
            $idplano = $this->input->post('idplano');
            $sequencial = $this->input->post('sequencial');
            $tipo = $this->input->post('tipo');
            $observacao = trim($this->input->post('observacao')); 
            $ip = $_SERVER["REMOTE_ADDR"];
            $envia_email = 1;
            
           
            // DADOS PARA ATUALIZAR A AÇÃO
            if($tipo == "CONCLUSÃO DA AÇÃO"){
                $data_acao = array(
                'data_retorno_usuario' => $date_hoje,
                'status' => 'AGUARDANDO VALIDAÇÃO'
                );
            }else{
                $data_acao = array(
                'data_retorno_usuario' => $date_hoje,
                'status' => 'AGUARDANDO VALIDAÇÃO'
                );
            }
            // HISTÓRICO DA AÇÃO
            $data_historicoAcao = array(
                'data_envio' => $date_hoje,
                'usuario' => $usuario,
                'idplanos' => $idplano,
                'observacao' => $tipo.' - '.$observacao,
                'ip' => $ip,
                'tipo' => 2
            );
            $this->atas_model->updatePlano($idplano, $data_acao);
            $this->atas_model->add_historicoPlanoAcao($data_historicoAcao);
           
            // DADOS DO USUÁRIO QUE ESTÁ ENVIANDO
            $users_dados = $this->site->geUserByID($usuario);
            $nome_usuario = $users_dados->first_name;
            
            //BUSCA QUEM É O RESPONSÁVEL PELA AÇÃO
            $acaoFase = $this->atas_model->getResponsavelFasePlanoByID($idplano);
            
            $id_projeto = $acaoFase->id_projeto; 
            $responsavel_pa = $acaoFase->responsavel_pa; 

            $valida_item =  $acaoFase->valida_item;
            $responsavel_item = $acaoFase->responsavel_item; 

            $valida_evento = $acaoFase->valida_evento; 
            $responsavel_evento = $acaoFase->responsavel_evento; 

            $valida_fase = $acaoFase->valida_fase; 
            $responsavel_fase = $acaoFase->responsavel_fase;

            $coordenador_projeto = $acaoFase->coordenador_projeto; 
            
            
            
            $title = "Retorno da Ação : $sequencial ";
            $texto_msg = "O usuário $nome_usuario enviou o retorno da ação: $sequencial. Referente a : $tipo ";
            
            
            // REGISTRA A NOTIFICAÇÃO
            
            
            if($id_projeto){
        
                if($valida_item == 1){
                    
                    // ENVIA NOTIFICAÇÃO - QDO A AÇÃO NÃO VEM DE PROJETO
                    $data_notificacao = array(
                        'id_from' => $usuario,
                        'id_to' => $responsavel_item,
                        'title' => "$title",
                        'text' => "$texto_msg",
                        'lida' => 0,
                        'data' => $date_hoje,
                        'email' => $envia_email,
                        'idplano' => $idplano,
                        'empresa' => $this->session->userdata('empresa')
                    );
                    $this->atas_model->add_notificacoes($data_notificacao);

                    //ENVIA E-MAIL - QDO A AÇÃO NÃO VEM DE PROJETO
                    $data_email = array(
                        'id_from' => $usuario,
                        'id_to' => $responsavel_item,
                        'title' => "$title",
                        'text' => "$texto_msg",
                        'lida' => 0,
                        'data' => $date_hoje,
                        'referencia' => "welcome > retorno_new",
                        'idplano' => $idplano,
                        'empresa' => $this->session->userdata('empresa'),    
                        'enviado' => 0    
                        );
                    $this->atas_model->add_email($data_email);
                    
                    $resonsavel_evento_item = 0;
                    
                }

                if($valida_evento == 1){

                        //if o responsvael pelo evento for = ao responsavel pelo item, nao envia
                        if($responsavel_item == $responsavel_evento) {
                            $resonsavel_evento_item = 1;
                            //ECHO 'já notificou <br>';
                          }else{
                              //envia NOTIFICACAO E EMAIL
                              $resonsavel_evento_item = 0;
                               // ENVIA NOTIFICAÇÃO - QDO A AÇÃO NÃO VEM DE PROJETO
                                $data_notificacao = array(
                                    'id_from' => $usuario,
                                    'id_to' => $responsavel_evento,
                                    'title' => "$title",
                                    'text' => "$texto_msg",
                                    'lida' => 0,
                                    'data' => $date_hoje,
                                    'email' => $envia_email,
                                    'idplano' => $idplano,
                                    'empresa' => $this->session->userdata('empresa')
                                );
                                $this->atas_model->add_notificacoes($data_notificacao);

                                //ENVIA E-MAIL - QDO A AÇÃO NÃO VEM DE PROJETO
                                $data_email = array(
                                    'id_from' => $usuario,
                                    'id_to' => $responsavel_evento,
                                    'title' => "$title",
                                    'text' => "$texto_msg",
                                    'lida' => 0,
                                    'data' => $date_hoje,
                                    'referencia' => "welcome > retorno_new",
                                    'idplano' => $idplano,
                                    'empresa' => $this->session->userdata('empresa'),    
                                    'enviado' => 0
                                    );
                                $this->atas_model->add_email($data_email);
                    
                          
                          }

                }else{
                    //NÃO VALIDA EVENTO
                }

                 // VALIDA A FASE
                if($valida_fase == 1){

                    //se os responsaveis do item e do evento forem os mesmos, verifica se o da fase é o mesmo do evento
                    if($resonsavel_evento_item == 1){

                        if($responsavel_fase == $responsavel_evento) {
                            $resonsavel_fase_evento = 1; // se for o mesmo
                            //echo 'é o mesmo responsável da fase. ja notificou <br>';
                        }else{
                            $resonsavel_fase_evento = 0;// se o responsável for diferente

                            // verifica se o da fase é o mesmo do item
                            if($responsavel_fase == $responsavel_item) {
                                //se for o mesmo ele já foi notificado

                            }else{
                                // envia notificação
                                
                                
                                 // ENVIA NOTIFICAÇÃO - QDO A AÇÃO NÃO VEM DE PROJETO
                                $data_notificacao = array(
                                    'id_from' => $usuario,
                                    'id_to' => $responsavel_fase,
                                    'title' => "$title",
                                    'text' => "$texto_msg",
                                    'lida' => 0,
                                    'data' => $date_hoje,
                                    'email' => $envia_email,
                                    'idplano' => $idplano,
                                    'empresa' => $this->session->userdata('empresa')
                                );
                                $this->atas_model->add_notificacoes($data_notificacao);

                                //ENVIA E-MAIL - QDO A AÇÃO NÃO VEM DE PROJETO
                                $data_email = array(
                                    'id_from' => $usuario,
                                    'id_to' => $responsavel_fase,
                                    'title' => "$title",
                                    'text' => "$texto_msg",
                                    'lida' => 0,
                                    'data' => $date_hoje,
                                    'referencia' => "welcome > retorno_new",
                                    'idplano' => $idplano,
                                    'empresa' => $this->session->userdata('empresa'),    
                                    'enviado' => 0  
                                    );
                                $this->atas_model->add_email($data_email);
                               
                            }

                        }
                    }else{
                        // se os responsáveis do item e evento forem diferentes

                        if($responsavel_fase == $responsavel_evento) {
                            $resonsavel_fase_evento = 1; // se for o mesmo
                          //  echo 'é o mesmo responsável do evento. ja notificou <br>';
                        }else{
                            $resonsavel_fase_evento = 0;// se o responsável for diferente


                            // verifica se o da fase é o mesmo do item
                            if($responsavel_fase == $responsavel_item) {
                                //se for o mesmo ele já foi notificado

                            }else{
                                // envia notificação
                                
                                // ENVIA NOTIFICAÇÃO - QDO A AÇÃO NÃO VEM DE PROJETO
                                $data_notificacao = array(
                                    'id_from' => $usuario,
                                    'id_to' => $responsavel_fase,
                                    'title' => "$title",
                                    'text' => "$texto_msg",
                                    'lida' => 0,
                                    'data' => $date_hoje,
                                    'email' => $envia_email,
                                    'idplano' => $idplano,
                                    'empresa' => $this->session->userdata('empresa')
                                );
                                $this->atas_model->add_notificacoes($data_notificacao);

                                //ENVIA E-MAIL - QDO A AÇÃO NÃO VEM DE PROJETO
                                $data_email = array(
                                    'id_from' => $usuario,
                                    'id_to' => $responsavel_fase,
                                    'title' => "$title",
                                    'text' => "$texto_msg",
                                    'lida' => 0,
                                    'data' => $date_hoje,
                                    'referencia' => "welcome > retorno_new",
                                    'idplano' => $idplano,
                                    'empresa' => $this->session->userdata('empresa'),    
                                    'enviado' => 0  
                                    );
                                $this->atas_model->add_email($data_email);
                                
                                
                            }

                        }




                    }


                }else{
                    // NÃO VALIDA A FASE
                    //echo 'não valida fase'; exit;
                }

                // NOTIFICA O COORDENADOR DO PROJETO
                if($coordenador_projeto != $responsavel_item){
                    
                    if($coordenador_projeto != $responsavel_evento){
                        
                        if($coordenador_projeto != $responsavel_evento){
                            // NOTIFICA O COORDENADOR
                            
                            
                            $data_notificacao = array(
                                'id_from' => $usuario,
                                'id_to' => $coordenador_projeto,
                                'title' => "Retorno de Ação",
                                'text' => "O usuário $nome_usuario enviou o retorno da ação: $sequencial. Referente a : $tipo",
                                'lida' => 0,
                                'data' => $date_hoje,
                                'email' => $envia_email,
                                'idplano' => $idplano,
                                'empresa' => $this->session->userdata('empresa')
                            );
                            $this->atas_model->add_notificacoes($data_notificacao);

                            //cadastro para envio de email
                            if($envia_email == 1){

                                $data_email = array(
                                'id_from' => $usuario,
                                'id_to' => $coordenador_projeto,
                                'title' => "Retorno de Ação",
                                'text' => "O usuário $nome_usuario enviou o retorno da ação: $sequencial. Referente a : $tipo",
                                'lida' => 0,
                                'data' => $date_hoje,
                                'referencia' => "welcome > retorno_new",
                                'idplano' => $idplano,
                                'empresa' => $this->session->userdata('empresa'),    
                                'enviado' => 0      
                                );
                                $this->atas_model->add_email($data_email);

                                 //Email
                            //$this->ion_auth->retornoUsuario($idplano);
                            }
                            
                            
                        }else{
                            // JA NOTIFICOU
                        }
                        
                    }else{
                        //JA NOTIFICOU
                    }
                    
                
                }else{
                    // JA NOTIFICOU
                }

            }else{
                // ENVIA NOTIFICAÇÃO - QDO A AÇÃO NÃO VEM DE PROJETO
                
                $data_notificacao = array(
                    'id_from' => $usuario,
                    'id_to' => $responsavel_pa,
                    'title' => "$title",
                    'text' => "$texto_msg",
                    'lida' => 0,
                    'data' => $date_hoje,
                    'email' => $envia_email,
                    'idplano' => $idplano,
                    'empresa' => $this->session->userdata('empresa')
                );
                $this->atas_model->add_notificacoes($data_notificacao);
                
                //ENVIA E-MAIL - QDO A AÇÃO NÃO VEM DE PROJETO
                $data_email = array(
                    'id_from' => $usuario,
                    'id_to' => $responsavel_pa,
                    'title' => "Retorno de Ação",
                    'text' => "O usuário $nome_usuario enviou o retorno da ação: $sequencial. Referente a : $tipo",
                    'lida' => 0,
                    'data' => $date_hoje,
                    'referencia' => "welcome > retorno_new",
                    'idplano' => $idplano,
                    'empresa' => $this->session->userdata('empresa'),    
                    'enviado' => 0
                );
                $this->atas_model->add_email($data_email);
            
            
            }

           
            
            $logdata = array('date' => date('Y-m-d H:i:s'), 
            'type' => 'RETORNO DE AÇÃO', 
            'description' => "O usuário $nome_usuario enviou o retorno da ação: $sequencial. Referente a : $tipo",  
            'userid' => $this->session->userdata('user_id'), 
            'ip_address' => $_SERVER["REMOTE_ADDR"],
            'tabela' => '',
            'row' => '',
            'depois' => '', 
            'modulo' => 'Network',
            'funcao' => 'Welcome/retorno_new',  
            'empresa' => $this->session->userdata('empresa'));
            $this->owner_model->addLog($logdata);  
            
            $this->session->set_flashdata('message', lang("Retorno da ação enviada com Sucesso!!!"));
            redirect("welcome/minhasAcoes");
            
         }else{
            $this->data['idplano'] = $id;
            $this->data['acoes'] = $this->atas_model->getAllAcoes($id);
            $this->load->view($this->theme . 'networking/acoes/acaoRetorno', $this->data);
            
         }
    }
    
    public function atualizaStatusAtividade($idplano, $status)
    {
    
             
            $date_hoje = date('Y-m-d H:i:s');    
            $usuario = $this->session->userdata('user_id');  
            $tipo = $this->input->post('tipo');
            $observacao = trim($this->input->post('observacao')); 
            $ip = $_SERVER["REMOTE_ADDR"];
            
            
             $data_acao = array(
             'status_tipo' => $status
            );
           
            
         
            
            $data_UsuarioLog = array(
                'date' => $date_hoje,
                'type' => 'UPDATE',
                'description' => 'O Usuário'.$usuario.'Começou a fazer a atividade'.$idplano. ' ',
                'userid' => $usuario,
                'depois' => 'Status = FAZENDO',
                'tabela' => 'PLANOS',
                'ip' => $ip
            );
          
            
            
            $this->atas_model->updatePlano($idplano, $data_acao, null);
            $this->atas_model->add_log($data_UsuarioLog);
          
            
              // exit;
            $this->session->set_flashdata('message', lang("Retorno da ação enviada com Sucesso!!!"));
            redirect("Welcome/controleAtividades");
     }
     
     // quando a ação não está pendente. Campos desabilitados
     public function consultar_acao($id_acao = null, $projeto = null, $status = null)
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
            
          //  $this->data['projetos'] = $this->atas_model->getAllProjetos();      
           // $this->data['ata'] = $id;
           
            $this->data['acoes'] = $this->atas_model->getAllAcoesProjeto($projetos_usuario->projeto_atual, $id_acao);
            
            $this->data['idplano'] = $id_acao;
            
            $this->data['projeto_filtro'] = $projeto;
            $this->data['status_filtro'] = $status;
            
            //$this->data['acoes'] = $this->atas_model->getPlanoByID($id); 
           //  $this->data['acoes'] = $this->atas_model->getAllAcoesProjeto($projetos_usuario->projeto_atual);
           // $this->load->view($this->theme . 'Atas/editar_acao', $this->data); //manutencao_acao_av_pendente
            $this->page_construct_networking('networking/minhas_acoes/consultarAcao', $meta, $this->data);
        
      
            
            
         
    }
    
    public function visualizar_acao_disabled($id_acao = null)
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
           
            $this->data['acoes'] = $this->atas_model->getAllAcoesProjeto($projetos_usuario->projeto_atual, $id_acao);
            
            $this->data['idplano'] = $id_acao;
            //$this->data['acoes'] = $this->atas_model->getPlanoByID($id); 
           //  $this->data['acoes'] = $this->atas_model->getAllAcoesProjeto($projetos_usuario->projeto_atual);
           // $this->load->view($this->theme . 'Atas/editar_acao', $this->data); //manutencao_acao_av_pendente
            $this->page_construct_ata('project/cadastro_basico_modelo/plano_acao/editAcao', $meta, $this->data);
         
    }
    
    
    /*************************************************** FIM MINHAS AÇÕES ************************************************/

    
    /**********************************************************************************************************************
     ******************************************* A T A S   V I N C U L A D A S ******************************************* 
     **********************************************************************************************************************/
    public function atasVinculadas($tabela, $menu)
    {
     
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
             
            
            $menu = 87;
            $this->data['tabela_nome'] = "Atas";
            $this->data['tabela_id'] = $tabela;
            $this->data['menu_id'] = $menu;
            $this->data['titulo'] = "Atas Vinculadas";
            $this->data['descricao_titulo'] = "Atas onde fui vinculado";
            $this->data['menu'] = "cadastro";
            $this->data['submenu'] = "modulo";
            
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
                    'description' => "Acessou o menu $menu do Módulo NETWORKING",  
                    'userid' => $this->session->userdata('user_id'), 
                    'ip_address' => $_SERVER["REMOTE_ADDR"],
                    'tabela' => '',
                    'row' => '',
                    'depois' => '', 
                    'modulo' => 'Networking',
                    'funcao' => 'welcome/minhasAtas',  
                    'empresa' => $this->session->userdata('empresa'));
                    $this->owner_model->addLog($logdata); 
            
            //$this->data['modulos'] = $this->owner_model->getTablesCadastroBasico($tabela);
            $this->data['atas'] = $this->atas_model->getAllVinculoAtasByUser();
            //$this->data['campos'] = $this->owner_model->getAllCamposTablesLista($tabela);
            //$this->data['cadastrosHabilitados'] = $this->owner_model->getAllCamposTablesCadastro($tabela);
            
           // $this->data['botoes_menu'] = $this->owner_model->getAllBotoesByTabela($tabela);
            $this->page_construct_networking('networking/ata/vinculadas/index', $meta, $this->data);
           // $this->page_construct_user('owner/empresas/index', $meta, $this->data);
         
         
         
    }
    
    public function plano_acao_vinculada($id = null)
    {
        $this->sma->checkPermissions();
        
        /*
         * VERIFICA SE A ATA PERTENCE AO PROJETO QUE O USUÁRIO ESTÁ LOGADO
         */
         $usuario = $this->session->userdata('user_id');
         $projetos_usuario = $this->site->getProjetoAtualByID_completo($usuario);
         $projeto_atual = $projetos_usuario->projeto_atual;
         //echo $id; exit;                                        
        $ata = $this->atas_model->getAtaSemProjetoByIDByProjeto($id, $projeto_atual);
        $quantidade_ata = $ata->quantidade;
       
        if($quantidade_ata == 0){
             $this->session->set_flashdata('message', lang("A ATA QUE ESTÁ TENTANDO ACESSAR NÃO PERTENCE A ESTE PROJETO"));
            redirect("Atas");
        }else if($quantidade_ata == 1){
            
        
        $this->form_validation->set_rules('descricao', lang("Descrição"), 'required');
        $this->form_validation->set_rules('periodo_acao', lang("Data Início e Término"), 'required');
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
            //$status = trim($this->input->post('status_plano')); 
            $date_cadastro = date('Y-m-d H:i:s');  
            $usuario = $this->session->userdata('user_id');
            $empresa = $this->session->userdata('empresa');
           $users_dados = $this->site->geUserByID($usuario);
            $modulo_atual_id = $users_dados->modulo_atual;
            $projeto_atual_id = $users_dados->projeto_atual; 
            
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
                         
            
           
            $cont_r = 0;
            foreach ($responsaveis as $responsavel) {
             $cont_r++;   
            }
            if($cont_r == 0){
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
                'idatas' => $id_ata,
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
                'peso' => $peso,
                'projeto' => $projeto_atual_id
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
            
                        
            $id_acao = $this->atas_model->add_planoAcao($data_plano,$acao_vinculo,$tipo_vinculo,$id_responsavel);
            
             /***********************************************************************************************
            ********************** L O G     A Ç Ã O ****************************************************** 
            ***********************************************************************************************/
           $data_log = array(
                'idplano' => $id_acao,
                'data_registro' => $date_cadastro,
                'usuario' => $usuario,
                'depois' => "Ação Criada",
                'empresa' => $empresa
              );
            $this->atas_model->add_logPlano($data_log);
           
           
           
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
            
            }

            
            
            
            $this->session->set_flashdata('message', lang("Ação Cadastrada com Sucesso!!!"));
            redirect("project/manutencao_acao_pendente/".$id_acao);
            
        } else {

           
            $this->data['id'] = $id;
           
            
            $ata = $this->atas_model->getAtaSemProjetoByID($id);
             
            $this->data['users_ata'] = $this->atas_model->getAtaUserByID_ATA($id);
            $this->data['ata'] = $this->atas_model->getAtaSemProjetoByID($id);
            //$this->data['ataAtual'] = $this->atas_model->getAtaSemProjetoByID($id);
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            
            $this->data['users'] = $this->atas_model->getAllUsersSetores(); //$this->atas_model->getAllUsersSetores();
            
            
            $this->data['planosContinuo'] = $this->atas_model->getAllitemPlanosAtaContinua($ata->evento); //getAllitemPlanosAtaContinua
            $this->data['acoes'] = $this->atas_model->getAllAcoes($id);
            $this->data['planos'] = $this->atas_model->getAllitemPlanos($id);
            
            $usuario = $this->session->userdata('user_id');
            $projetos_usuario = $this->site->getProjetoAtualByID_completo($usuario);
            $this->data['eventos'] = $this->projetos_model->getAllEventosItemEventoByProjeto($projetos_usuario->projeto_atual);         
            
           // $this->data['participantes'] = $this->atas_model->getAllUserListaParticipantesByProjeto($projetos_usuario->projeto_atual);
           // $this->data['participantes_usuarios_ata'] = $this->atas_model->getAllUserListaVinculoAtaByProjeto($projetos_usuario->projeto_atual);
           
           // $this->data['participantes_cadastrados_ata'] = $this->atas_model->getAtaUserParticipante_ByID_ATA($id);
            /*
             * SELECIONA OS TTIPO DE PESQUISAS DE SATISFAÇÃO
             */
           // $this->data['avaliacoes'] = $this->atas_model->getAllPesquisa();   
            
           // $bc = array(array('link' => base_url(), 'page' => lang('Atas')), array('link' => '#', 'page' => lang('Plano de Ação')));
            // $this->page_construct('Atas/plano_acao', $meta, $this->data);
            $this->page_construct_project('networking/ata/vinculadas/ata', $meta, $this->data);
            
          }
        }
    }
    /***************************************** FIM DAS ATAS VINCULADAS *****************************************************/
    
    
    /**********************************************************************************************************************
     ******************************************** M I N H A S     A T A S  ************************************************ 
     **********************************************************************************************************************/
    
    public function minhasAtas($tabela, $menu)
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
             
            $menu = 86;
            $this->data['tabela_nome'] = $tabela_nome;
            $this->data['tabela_id'] = $tabela;
            $this->data['menu_id'] = $menu;
            $this->data['titulo'] = "Minhas Atas";
            $this->data['descricao_titulo'] = "Lista de Atas";
            $this->data['menu'] = "cadastro";
            $this->data['submenu'] = "modulo";
            
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
                    'type' => 'NETWORKING', 
                    'description' => "Acessou o menu $menu do Módulo NETWORKING",  
                    'userid' => $this->session->userdata('user_id'), 
                    'ip_address' => $_SERVER["REMOTE_ADDR"],
                    'tabela' => '',
                    'row' => '',
                    'depois' => '', 
                    'modulo' => 'networking',
                    'funcao' => 'networking/minhasAtas',  
                    'empresa' => $this->session->userdata('empresa'));
                    $this->owner_model->addLog($logdata); 
            
            $this->data['atas'] = $this->atas_model->getAllMinhasAtasByUser();
            
           // $this->data['botoes_menu'] = $this->owner_model->getAllBotoesByTabela($tabela);
            $this->page_construct_networking('networking/ata/minhas_atas/index', $meta, $this->data);
           // $this->page_construct_user('owner/empresas/index', $meta, $this->data);
         }
         
         
    }
    
    public function novaAta($tabela, $menu, $id_plano, $tipo_ata) {


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
            
            $tabela_id = $this->input->post('tabela_id');
            $tabela_nome = $this->input->post('tabela_nome');
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
           // $usuario_ata = $this->input->post('usuarios_vinculo');
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
            
            //$this->site->getUser($this->session->userdata('user_id'));
             if($tipo_ata == 2){
                 $data_ata = array(
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
                'networking' => 1,
                'plano_acao' => $id_plano     
            );
             }else{
                 $data_ata = array(
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
                'networking' => 1
            );
             }
             
            
           // print_r($data_ata); exit;
          
            
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
            
             $id_ata = $this->atas_model->addAtas($data_ata,$participantes);
             
            
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
                'modulo' => 'welcome',
                'funcao' => 'welcome/novaAta',
                'empresa' => $this->session->userdata('empresa'));

            $this->owner_model->addLog($logdata);
            // exit;

            $this->session->set_flashdata('message', lang("Cadastro realizado com Sucesso!!!"));
            if($tipo_ata == 2){
                redirect("welcome/plano_acao_detalhes/$id_plano");
            }else{
                redirect("welcome/minhasAtas/$tabela_id/$menu_id");
            }
            
        } else {

            $tabela_cadastro = $this->owner_model->getTableById($tabela);
            $tabela_nome = $tabela_cadastro->tabela;
            $this->data['tabela_nome'] = $tabela_nome;
            $this->data['titulo'] = $tabela_cadastro->titulo;
            $this->data['tabela_id'] = $tabela;
            $this->data['menu_id'] = $menu;
            $this->data['funcao'] = $funcao;
            $this->data['fase'] = $fase;
            $this->data['tipo_ata'] = $tipo_ata;
            $this->data['id_plano'] = $id_plano;
            $this->data['campos'] = $this->owner_model->getAllCamposTablesLista($tabela);
            $this->data['cadastrosHabilitados'] = $this->owner_model->getAllCamposTablesCadastro($tabela);
            
            $usuario = $this->session->userdata('user_id');
            $projetos_usuario = $this->site->getProjetoAtualByID_completo($usuario);
            $this->data['eventos'] = $this->projetos_model->getAllEventosItemEventoByProjeto($projetos_usuario->projeto_atual);   
            //$this->data['users'] = $this->owner_model->getDadosTablesUsers(); //
            $this->data['users'] = $this->atas_model->getAllUsersSetores(); 
            //$this->load->view($this->theme . 'projetos/documentacao/add', $this->data);

            $this->page_construct_networking('networking/ata/minhas_atas/cadastro', $meta, $this->data);
        }
    }
    /* O EDITAR DA ATA*/
    public function plano_acao($id = null)
    {
        $this->sma->checkPermissions();
        
        /*
         * VERIFICA SE A ATA PERTENCE AO PROJETO QUE O USUÁRIO ESTÁ LOGADO
         */
         $usuario = $this->session->userdata('user_id');
       
         
        $this->form_validation->set_rules('descricao',   lang("Descrição"), 'required');
        $this->form_validation->set_rules('data_inicio', lang("Data Início"), 'required');
        $this->form_validation->set_rules('data_termino',lang("Data Término"), 'required');
        
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        
       // echo 'aqui'. $this->input->post('id'); exit;
          if ($this->form_validation->run() == true) {
           
             
            $id_registro = $this->input->post('id');  
            
            $tipo = $this->input->post('tipo');
            $categoria = $this->input->post('categoria');
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
            //$status = trim($this->input->post('status_plano')); 
            $date_cadastro = date('Y-m-d H:i:s');  
            $usuario = $this->session->userdata('user_id');
            $empresa = $this->session->userdata('empresa');
            $users_dados = $this->site->geUserByID($usuario);
            $modulo_atual_id = $users_dados->modulo_atual;
            $projeto_atual_id = $users_dados->projeto_atual; 
            
            //PERÍODO
            //$periodo_acao = $this->input->post('periodo_acao');
            $dataInicio = $this->input->post('data_inicio');
            $dataTermino = $this->input->post('data_termino');
            
            /*
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
            // exit;
            $dataInicio = $data_tratado_de; 
            $dataTermino = $data_tratado_ate;
             * 
             */
            
            $horas_previstas = $this->input->post('horas_previstas');
                         
             $date_hoje = date('Y-m-d H:i:s');
            $usuario = $this->session->userdata('user_id');
            $empresa = $this->session->userdata('empresa');
           
           
            $cont_r = 0;
            foreach ($responsaveis as $responsavel) {
             $cont_r++;   
            }
            if($cont_r == 0){
            $this->session->set_flashdata('error', lang("Selecione um responsável pela ação!!!"));
            echo "<script>history.go(-1)</script>";
            exit;
            }
            
            /*
             * APLICA A REGRA AS AÇÕES COM VINCULOS
             */
            $acao_vinculo = $this->input->post('acoes_vinculo');
            $tipo_vinculo = $this->input->post('tipo_vinculo');
            
            
         
           
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
           //    plano de ação
             if($tipo == 2){
                 
                 $dados_plano_acao = $this->atas_model->getPlanoAcaoByID($id_registro);
                 $status_plano = $dados_plano_acao->status;
                 
                 if($status_plano == 1){
                     $status_acao = "PENDENTE";
                 }else{
                     $status_acao = "ABERTO";
                 }
                 
                 $data_plano = array(
                'idplano' => $id_registro,
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
                'status_tipo' => 1,
                'sequencial' => $sequencia,
                'empresa' => $empresa,
                'peso' => $peso,
                'categoria_plano' => $categoria,
                'tipo_origem' => $tipo);
                 
                 
             }else{
             
                 $data_plano = array(
                'idatas' => $id_registro,
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

                'status_tipo' => 1,
                'sequencial' => $sequencia,
                'empresa' => $empresa,
                'peso' => $peso

                );  
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
                $data_plano['anexo'] = $photo;
            }
            
                        
            $id_acao = $this->atas_model->add_planoAcao($data_plano,$acao_vinculo,$tipo_vinculo,$id_responsavel);
            
            //plano de ação
             if($tipo == 2){
                 $dados_plano_acao = $this->atas_model->getPlanoAcaoByID($id_registro);
                 $status_plano = $dados_plano_acao->status;
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
                        'title' => "Nova Ação",
                        'text' => "Parabéns $nome_usuario, você recebeu uma nova ação. Acessar o SigPlus para mais detalhes.",
                        'lida' => 0,
                        'data' => $date_hoje,
                        'referencia' => "Networking > Finalização de Ata",
                        'idplano' => $id_acao,
                        'empresa' => $empresa,
                        'enviado' => 0);
                        $this->atas_model->add_email($data_email);
                        
                  }       
             }
            
             /***********************************************************************************************
            ********************** L O G     A Ç Ã O ****************************************************** 
            ***********************************************************************************************/
           $data_log = array(
                'idplano' => $id_acao,
                'data_registro' => $date_cadastro,
                'usuario' => $usuario,
                'depois' => "Ação Criada",
                'empresa' => $empresa
              );
            $this->atas_model->add_logPlano($data_log);
           
           
           
           
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
            
            }

            
            $this->session->set_flashdata('message', lang("Ação Cadastrada com Sucesso!!!"));
            
            //PLANO DE AÇAO
            if($tipo == 2){
               redirect("welcome/manutencao_acao_pendente/".$id_acao);
            }else{
                redirect("welcome/manutencao_acao_pendente/".$id_acao);
            }

            
            
        } else {

           
            $this->data['id'] = $id;
           
            
            $ata = $this->atas_model->getAtaSemProjetoByID($id);
             
            $this->data['users_ata'] = $this->atas_model->getAtaUserByID_ATA($id);
            $this->data['ata'] = $this->atas_model->getAtaSemProjetoByID($id);
            //$this->data['ataAtual'] = $this->atas_model->getAtaSemProjetoByID($id);
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            
            $this->data['users'] = $this->atas_model->getAllUsersSetores(); //$this->atas_model->getAllUsersSetores();
            
            
            $this->data['planosContinuo'] = $this->atas_model->getAllitemPlanosAtaContinua($ata->evento); //getAllitemPlanosAtaContinua
            $this->data['acoes'] = $this->atas_model->getAllAcoes($id);
            $this->data['planos'] = $this->atas_model->getAllitemPlanos($id);
            
            $usuario = $this->session->userdata('user_id');
            $projetos_usuario = $this->site->getProjetoAtualByID_completo($usuario);
            $this->data['eventos'] = $this->projetos_model->getAllEventosItemEventoByProjeto($projetos_usuario->projeto_atual);         
            
           
            $this->page_construct_networking('networking/ata/minhas_atas/ata', $meta, $this->data);
            
          }
        
    }
    
    /* REPLICAR AÇÃO */
    public function replicar_acao($id_acao = null)
    {
     
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
            }
            $date_hoje = date('Y-m-d H:i:s');    
            $usuario = $this->session->userdata('user_id');
            $projetos_usuario = $this->site->getProjetoAtualByID_completo($usuario);
                        
            $ip = $_SERVER["REMOTE_ADDR"];
                                                            
            $this->data['users'] = $this->atas_model->getAllUsersSetores(); 
            
            $this->data['projetos'] = $this->atas_model->getAllProjetos();      
           // $this->data['ata'] = $id;
            $this->data['avulsa'] = $avulsa;
            
            $this->data['idplano'] = $id_acao;
           // $this->load->view($this->theme . 'Atas/editar_acao', $this->data); //manutencao_acao_av_pendente
            $this->page_construct_networking('networking/ata/minhas_atas/replicar', $meta, $this->data);
            
         
    }
    
    public function replicar_acao_form(){
        $this->form_validation->set_rules('descricao', lang("Descrição"), 'required');
        $this->form_validation->set_rules('periodo_acao', lang("Data Início e Término"), 'required');
       
       // echo 'aqui'. $this->input->post('id'); exit;
          if ($this->form_validation->run() == true) {
          
            //$idata = $this->input->post('id');   
            $id_ata = $this->input->post('idatas');  
            
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
              
             $dataInicio = $data_tratado_de; 
             $dataTermino = $data_tratado_ate;
            // fim período
             
             $horas_previstas = $this->input->post('horas_previstas');
                         
           
            
           
            if(!$responsaveis){
            $this->session->set_flashdata('error', lang("Selecione um responsável pela ação!!!"));
            echo "<script>history.go(-1)</script>";
            exit;
            }
            
             
                
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
            $envia_email = 1;
             
             $data_plano = array(
                'idatas' => $id_ata, 
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
                'status_tipo' => 1,
                'sequencial' => $sequencia,
                'empresa' => $empresa,
                'peso' => $peso
             
            );  
           $idplano = $this->atas_model->add_planoAcao($data_plano,"","",$id_responsavel);
            
           
             /***********************************************************************************************
            ********************** L O G     A Ç Ã O ****************************************************** 
            ***********************************************************************************************/
           $data_log = array(
                'idplano' => $idplano,
                'data_registro' => $date_cadastro,
                'usuario' => $usuario,
                'depois' => "Ação Criada",
                'empresa' => $empresa
              );
            $this->atas_model->add_logPlano($data_log);
           
           /*
            // REGISTRA A NOTIFICAÇÃO
            $users_dados = $this->site->geUserByID($usuario);
            $nome_usuario = $users_dados->first_name;
         
            $data_notificacao = array(
                'id_from' => $usuario,
                'id_to' => $id_responsavel,
                'title' => "Nova Ação - $sequencia",
                'text' => "Parabéns, Você recebeu uma nova ação, com o ID: $sequencia. Enviada pelo o usuário $nome_usuario.",
                'lida' => 0,
                'data' => $date_cadastro,
                'email' => $envia_email,
                'idplano' => $idplano
            );
            $this->atas_model->add_notificacoes($data_notificacao);
            
            //cadastro para envio de email
            if($envia_email == 1){
                $data_email = array(
                'id_from' => $usuario,
                'id_to' => $id_responsavel,
                'title' => "Retorno de Ação",
                'text' => "Parabéns! você recebeu uma nova ação.",
                'lida' => 0,
                'data' => $date_hoje,
                'referencia' => "Networking > ata > Replicar ação",
                'idplano' => $idplano
                );
                $this->atas_model->add_email($data_email);
                
                 //Email
            //$this->ion_auth->retornoUsuario($idplano);
            }
            * 
            */
           
             /******************* LOG ******************************/
            $date_hoje = date('Y-m-d H:i:s');
            $usuario = $this->session->userdata('user_id');
            $empresa = $this->session->userdata('empresa');
            $ip = $_SERVER["REMOTE_ADDR"];

            $logdata = array('date' => date('Y-m-d H:i:s'),
                'type' => 'INSERT',
                'description' => 'Cadastro de uma nova Ação, ID: '.$idplano,
                'userid' => $this->session->userdata('user_id'),
                'ip_address' => $_SERVER["REMOTE_ADDR"],
                'tabela' => 'sig_planos',
                'row' => $idplano,
                'depois' => json_encode($data_plano),
                'modulo' => 'welcome',
                'funcao' => 'welcome/replicar_acao_form.',
                'empresa' => $this->session->userdata('empresa'));

            $this->owner_model->addLog($logdata);
            
            
            $this->session->set_flashdata('message', lang("Ação Cadastrada com Sucesso!!!"));
            redirect("welcome/plano_acao/".$id_ata);
            
        }
    }
    
    /********************** CADASTRO COMPLETO DA AÇÃO ***************************************************/
    public function manutencao_acao_pendente($id_acao = null)
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

       // $this->data['projetos'] = $this->atas_model->getAllProjetos();      
       // $this->data['ata'] = $id;

        $this->data['acoes'] = $this->atas_model->getAllAcoesProjeto($projetos_usuario->projeto_atual, $id_acao);

        $this->data['idplano'] = $id_acao;

        $this->page_construct_networking('networking/ata/minhas_atas/editAcao', $meta, $this->data);
            
         
    }
    
    
    /* FORM CADASTRO COMPLETO AÇÃO */
    public function manutencao_acao_pendente_form($id = null)
    {
              
            $date_hoje = date('Y-m-d H:i:s');    
            $usuario = $this->session->userdata('user_id');  
            $ip = $_SERVER["REMOTE_ADDR"];
            
            $idata = $this->input->post('idatas');
            
            
            $idplano = $this->input->post('id');  
            $evento = $this->input->post('evento'); 
            
            $tipo = $this->input->post('tipo');
            $categoria = $this->input->post('categoria');
            
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
            
            $peso = $this->input->post('peso');
            //$status = trim($this->input->post('status_plano')); 
            $date_cadastro = date('Y-m-d H:i:s');  
            $usuario = $this->session->userdata('user_id');
            $empresa = $this->session->userdata('empresa');
           
            //PERÍODO
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
             
           
            $dataInicio = $this->input->post('data_inicio');
            $dataTermino = $this->input->post('data_termino');
            
            $horas_previstas = $this->input->post('horas_previstas');
                         
            
            if(!$responsavel){
            $this->session->set_flashdata('error', lang("Selecione um responsável pela ação!!!"));
            echo "<script>history.go(-1)</script>";
            exit;
            }
            
          
            
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
            $peso_original = $dados_acao->peso;
            $categoria_original = $dados_acao->categoria_plano;
            
            //Dados
            $tipo_origem = $dados_acao->tipo_origem;
            //$peso_original = $dados_acao->peso;
            
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
                'peso' => $peso_original,
                'categoria_plano' => $categoria_original
            );
          
            
           /********************** L O G  da   A Ç Ã O ******************************************************/
             
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
                    'descricao' => "O cadastro (ONDE) a ação será feita foi alterado",
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
                    'descricao' => "O cadastro (COMO) a ação será feita foi alterado",
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
                    'descricao' => "O cadastro (POR QUÊ) a ação será feita foi alterado",
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
            
             /**********************************************************************/
             
                
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
                'peso' => $peso,
                'categoria_plano' => $categoria
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
                
                if($tipo_origem == 2){
                    redirect("welcome/plano_acao_detalhes/$idata");
                }else{
                echo "<script>history.go(-2)</script>";
                    exit;
                }
            }
         
    }    
    /*********************************** N O V A    A Ç Ã O P A R A   A T A  E  P L A N O  D E  A Ç Ã O*************************************************************/
    public function adcionar_acao($id = null, $tipo )
    {
     
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
            }
        
                        
            $date_hoje = date('Y-m-d H:i:s');    
            $usuario = $this->session->userdata('user_id');  
            $ip = $_SERVER["REMOTE_ADDR"];
            
           // $descricao = $this->input->post('descricao') .'<br>';
            
           
            $usuario = $this->session->userdata('user_id');
            //$projetos_usuario = $this->site->getProjetoAtualByID_completo($usuario);
            //$this->data['eventos'] = $this->projetos_model->getAllEventosItemEventoByProjeto($projetos_usuario->projeto_atual);   
                                                            
            $this->data['users'] = $this->atas_model->getAllUsersSetores(); 
            //$this->data['macro'] = $this->atas_model->getAllMacroProcesso();
            
           // $this->data['projetos'] = $this->atas_model->getAllProjetos();      
            $this->data['registro'] = $id;
            $this->data['tipo'] = $tipo; // 1 - ATA; 2 - PLANO DE AÇÃO;
           // $this->data['acoes'] = $this->atas_model->getAllAcoesVinculoCadastro($projetos_usuario->projeto_atual);
            //$this->data['acoes'] = $this->atas_model->getPlanoByID($id);
            
            $participantes = $this->input->post('participantes');
     
            foreach ($participantes as $participante) {
               $participantes_usuario[] = $participante;
            }
            
            $this->data['participantes_usuarios'] = $participantes_usuario;
            //$this->data['participantes_lista'] = "$nomes_participantes";
           
            $this->page_construct_networking_nova_acao('networking/ata/minhas_atas/novaAcao', $meta, $this->data);
            // $this->load->view($this->theme . 'Atas/adicionar_acao', $this->data);
         
    }
    
    public function deleteParticipante($id, $id_ata, $tipo)
    {
        $this->sma->checkPermissions(null, true);

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
       
             if ($this->atas_model->deleteParticipanteAta($id, $id_ata)) {
                 if ($this->input->is_ajax_request()) {
                echo lang("Participante Apagado");
                die();
            } else {
                
            }
            $this->session->set_flashdata('message', lang('Participante Apagado com Sucesso!!!'));
            redirect("welcome/plano_acao/".$id_ata);
            }   
        
    }
    
    public function edit($id = null)
    {
        $this->sma->checkPermissions();
      
       
       // $this->form_validation->set_rules('projeto', lang("Projeto"), 'required');
        $this->form_validation->set_rules('dateAta', lang("Data da ATA"), 'required');
        $this->form_validation->set_rules('tipo', lang("Tipo da ATA"), 'required');
        $this->form_validation->set_rules('pauta', lang("Pauta"), 'required');
       // $this->form_validation->set_rules('participantes', lang("Participantes"), 'required');
        $this->form_validation->set_rules('nome_elaboracao', lang("Elaboração a Pauta"), 'required');
       // $this->form_validation->set_rules('pendencias', lang("Pendências"), 'required');
        
        $date_cadastro = date('Y-m-d H:i:s');       
        
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
         // echo 'aqui'; exit;
          if ($this->form_validation->run() == true) {
           
          //  $projeto = $this->input->post('projeto');
             $usuario_responsavel = $this->session->userdata('user_id');
             
            $dateAta = $this->input->post('dateAta'); 
            $hora_inicio= $this->input->post('hora_inicio'); 
            $hora_termino = $this->input->post('hora_fim'); 
            $tipo = trim($this->input->post('tipo')); 
            $pauta = trim($this->input->post('pauta'));
            $assunto = trim($this->input->post('assunto')); 
            $participantes = $this->input->post('participantes');
            $nome_elaboracao = $this->input->post('nome_elaboracao');
            $local = $this->input->post('local');
           // $usuario_ata = $this->input->post('usuario_ata');
            $note = $this->input->post('note');
            $data_criacao = $date_cadastro;
            $usuario = $this->session->userdata('user_id');
            $id_ata = $this->input->post('id');
            
            //$participar = $this->input->post('participar');
            $vinculo = $this->input->post('vinculo');
            
            $participantes_cadastrados_ata = $this->atas_model->getAtaUserParticipante_ByID_ATA($id_ata);
            foreach ($participantes_cadastrados_ata as $participante_cadastrados) {
                $usuariop = $participante_cadastrados->id;
                
                //ATUALIZA CADASTRO DE PARTICIPANTES
                 if (isset($_POST[$usuariop.'participante'])) {
                    //var_dump("selecionado'");
                    $this->atas_model->updateParticipantesAta($id_ata, $usuariop, 1);
                } else {
                    //var_dump("não escolheu ");
                    $this->atas_model->updateParticipantesAta($id_ata, $usuariop, 0);
                }
                
                //ATUALIZA CADASTRO DE VÍNCULOS
                 if (isset($_POST[$usuariop.'vinculo'])) {
                    //var_dump("selecionado'");
                    $this->atas_model->updateVinculoAta($id_ata, $usuariop, 1);
                } else {
                    //var_dump("não escolheu ");
                    $this->atas_model->updateVinculoAta($id_ata, $usuariop, 0);
                }
                
                
            }
            
            
            $data_ata = array(
               
                'data_ata' => $dateAta,
                'hora_inicio' => $hora_inicio,
                'hora_termino' => $hora_termino,
                'tipo' => $tipo,
                'assunto' => $assunto,
                'pauta' => $pauta,
                'responsavel_elaboracao' => $nome_elaboracao,
                'local' => $local,
                'obs' => $note,
                'data_criacao' => $data_criacao
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
                $data_ata['anexo'] = $photo;
            }
            
           // $participantes_cadastrados_ata = $this->atas_model->getAtaUserParticipante_ByID_ATA($id_ata);
           
            
            
            $this->atas_model->updateAta($id_ata, $data_ata, $participantes);
            
            
            
            $this->session->set_flashdata('message', lang("ATA Alterada com Sucesso!!!"));
            redirect("welcome/plano_acao/".$id_ata);
            
        } else {

            redirect("welcome/minhasAtas");
          
            
           

            }
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
     
    public function deletar_acao($id_acao, $id_plano_acao)
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
            
            $this->data['projetos'] = $this->atas_model->getAllProjetos();      
           // $this->data['ata'] = $id;
            $this->data['avulsa'] = $avulsa;
            $this->data['acoes'] = $this->atas_model->getAllAcoesProjeto($projetos_usuario->projeto_atual, $id_acao);
            
            $this->data['idplano'] = $id_acao;
            //$this->data['acoes'] = $this->atas_model->getPlanoByID($id); 
           //  $this->data['acoes'] = $this->atas_model->getAllAcoesProjeto($projetos_usuario->projeto_atual);
           // $this->load->view($this->theme . 'Atas/editar_acao', $this->data); //manutencao_acao_av_pendente
            $this->page_construct_networking('networking/ata/minhas_atas/excluirAcao', $meta, $this->data);
            
         
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
            
            $this->session->set_flashdata('message', lang('Registro Apagado com Sucesso!!!'));
           // redirect('welcome/plano_acao/'.$id_ata);
            echo "<script>history.go(-2)</script>";
        }
    } 
    
    public function edit_discussao($id = null)
    {
        $this->sma->checkPermissions();
      
      
        $this->form_validation->set_rules('discussao', lang("Discussao"), 'required');
        $date_cadastro = date('Y-m-d H:i:s');       
        $usuario = $this->session->userdata('user_id');
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        
        
          if ($this->form_validation->run() == true) {
           
            $discussao = $this->input->post('discussao');
            $id_ata = $this->input->post('id');
            $ip = $_SERVER["REMOTE_ADDR"];
            $data_ata = array(
               
                'discussao' => $discussao
            );
            $this->atas_model->updateAta($id_ata, $data_ata);
           
            /*
             * INSERT O AUDIO
             */
             $tamanhoMaximo = 1000000000;
            // Extensões aceitas
            $extensoes = array(".wav", ".pcm", ".webm");
            // Caminho para onde o arquivo será enviado
            $caminho = "assets/uploads/atas/audios/";
             
            // Substituir arquivo já existente (true = sim; false = nao)
            $substituir = false;
            
         
            // Informações do arquivo enviado
            $nomeArquivo = $_FILES["document"]["name"];
            $tamanhoArquivo = $_FILES["document"]["size"];
            $nomeTemporario = $_FILES["document"]["tmp_name"];

          //  echo "$caminho/".$nomeArquivo.'<br>'; exit;
            // Verifica se o arquivo foi colocado no campo
            if (!empty($nomeArquivo)) {
                //echo "$caminho/".$nomeArquivo.'<br>';
                    $erro = false;

                    // Verifica se o tamanho do arquivo é maior que o permitido
                    if ($tamanhoArquivo > $tamanhoMaximo) {
                            $erro = "O arquivo " . $nomeArquivo . " não deve ultrapassar " . $tamanhoMaximo. " bytes";
                    } 
                    // Verifica se a extensão está entre as aceitas
                    elseif (!in_array(strrchr($nomeArquivo, "."), $extensoes)) {
                            $erro = "A extensão do arquivo <b>" . $nomeArquivo . "</b> não é válida";
                    } 

                    // Se não houver erro
                    if (!$erro) {
                            // Move o arquivo para o caminho definido
                            move_uploaded_file($nomeTemporario, ($caminho . $nomeArquivo));
                            move_uploaded_file("$caminho/".$nomeArquivo) ;
                            // Mensagem de sucesso
                            $this->session->set_flashdata('message', lang("O arquivo $nomeArquivo foi enviado com sucesso.!!!"));
                           // echo "O arquivo <b>".$nomeArquivo."</b> foi enviado com sucesso. <br />";
                             $data_audio = array(
                                'atasid' => $id_ata,
                                'data_registro' => $date_cadastro,
                                'usuario' => $usuario,
                                'arquivo' => $nomeArquivo,
                                'ip' => $ip
                            );
                            $this->atas_model->AdicionarAudioAta($data_audio);
                    
                    } 
                    // Se houver erro
                    else {
                            // Mensagem de erro
                          //  echo $erro . "<br />"; 
                            $this->session->set_flashdata('error', lang("$erro"));
                              redirect("welcome/edit_discussao/".$id_ata);
                    }
                  //  echo '<br> processou';
                  //  exit;
                    
                   
            }
           
           
           
            $this->session->set_flashdata('message', lang("Registro atualizado com Sucesso!!!"));
            redirect("welcome/edit_discussao/".$id_ata);
            
        } else {

          
            $this->data['id'] = $id;
           
            $this->data['ata'] = $this->atas_model->getAtaSemProjetoByID($id);
            
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['users'] = $this->site->getAllUser();
            $this->data['projetos'] = $this->atas_model->getAllProjetos();
            $this->data['users_ata'] = $this->atas_model->getAtaUserByID_ATA($id);
            $bc = array(array('link' => site_url('atas'), 'page' => lang('Atas')), array('link' => site_url('atas/edit'), 'page' => lang('Editar ATA')));
            $meta = array('page_title' => lang('Editar ATA'), 'bc' => $bc);
            $this->page_construct_networking('networking/ata/minhas_atas/discussao', $meta, $this->data);
            
           

            }
    }
    
    public function finalizaAta($id_ata = null)
    {
        $this->sma->checkPermissions();
      
        $date_finalizacao = date('Y-m-d H:i:s');       
        $envia_email = 1;
        
        
          if ($id_ata) {
           
            $status = 1;
            
            $usuario = $this->session->userdata('user_id');
            $empresa = $this->session->userdata('empresa');
            
            /********************** FINALIZA A ATA  ***************************/
            $data_ataFinalizacao = array(
                'status' => $status,
                'data_finalizacao' => $date_finalizacao,
                'usuario_finalizacao' => $usuario
            );
            /******************************************************************/
            
             if($this->atas_model->finalizaAta($id_ata, $data_ataFinalizacao)){
               
                
                // ENVIA EMAIL E NOTIFIAÇÕES
                $date_hoje = date('Y-m-d H:i:s');
                $usuario = $this->session->userdata('user_id');
                $empresa = $this->session->userdata('empresa');
               
                
                $data_plano = array(
                    'status' => 'PENDENTE'
                );
              
                if($this->atas_model->updatePlanoAta($id_ata, $data_plano)){
                        
                    $planos_ata = $this->atas_model->getAllPlanoAbertosByAtaID($id_ata);
                    foreach ($planos_ata as $plano_ata) {
                
                    $id_acao = $plano_ata->idplanos;
                    $id_responsavel = $plano_ata->responsavel;
                    
                     // REGISTRA A NOTIFICAÇÃO
                    $users_dados = $this->site->geUserByID($id_responsavel);
                    $nome_usuario = $users_dados->first_name;

                    $data_notificacao = array(
                        'id_from' => $usuario,
                        'id_to' => $id_responsavel,
                        'title' => "Nova Ação",
                        'text' => "Parabéns $nome_usuario, Você recebeu uma nova ação.  ",
                        'lida' => 0,
                        'data' => $date_finalizacao,
                        'email' => $envia_email,
                        'idplano' => $id_acao,
                        'empresa' => $this->session->userdata('empresa')
                    );
                    $this->atas_model->add_notificacoes($data_notificacao);

                    //cadastro para envio de email
                    
                        $data_email = array(
                        'id_from' => $usuario,
                        'id_to' => $id_responsavel,
                        'title' => "Nova Ação",
                        'text' => "Parabéns $nome_usuario, você recebeu uma nova ação. Acessar o SigPlus para mais detalhes.",
                        'lida' => 0,
                        'data' => $date_finalizacao,
                        'referencia' => "welcome > finalizaAta",
                        'idplano' => $id_acao,
                        'empresa' => $empresa,
                        'enviado' => 0);
                        $this->atas_model->add_email($data_email);

                
                    }
                
                }
             }   
            
            
            
            //print_r($data_ata); exit;
            
           
            
               
           
       
            
            $this->session->set_flashdata('message', lang("ATA Finalizada com Sucesso!!!"));
            redirect("welcome/plano_acao/$id_ata");
            
        } else {

           
            redirect("welcome/plano_acao/$id_ata");
            
           

            }
    }
    
    public function pdf_ata($id = null, $view = null)
    {
        
        $this->sma->checkPermissions();
        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        $usuario = $this->session->userdata('username');
        $empresa = $this->session->userdata('empresa');
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
       
            $this->data['id'] = $id;
            $this->data['ata'] = $this->atas_model->getAtaSemProjetoByID($id);  // $this->atas_model->getAtaProjetoByID_ATA($id);
            $empresa_dados = $this->owner_model->getEmpresaById($empresa);
            $cabecalho_empresa_ata = $empresa_dados->cabecalho_ata;
            $redape_empresa_ata = $empresa_dados->rodape_ata;
            
            $cadastro_usuario =  $this->site->getUser($usuario);
            $nome = $cadastro_usuario->first_name;
            //$this->data['users'] = $this->site->getAllUser();
           // $this->data['projetos'] = $this->atas_model->getAllProjetos();
            $this->data['planos'] = $this->atas_model->getAllitemPlanos($id);

            $name = lang("ATA") . "_" . str_replace('/', '_', $id) . ".pdf";
            $html = $this->load->view($this->theme . 'networking/ata/minhas_atas/pdf_ata', $this->data, true);

            
            $projetos_usuario = $this->site->getProjetoAtualByID_completo($usuario);
            
         //   $dados_projeto = $this->projetos_model->getProjetoByID($projetos_usuario->projeto_atual);
          //  $logo_doc_top =  $dados_projeto->logo_ata_top;
          //  $logo_doc_bottom =  $dados_projeto->logo_ata_bottom;
            
        if ($view) {
            $this->load->view($this->theme . 'networking/ata/minhas_atas/pdf_ata', $this->data);
        } else{
            
            $this->sma->generate_pdf_ata($html, $name, false, $usuario, null, null, null, null, $cabecalho_empresa_ata, $redape_empresa_ata, $nome);
        }
    }
    
    public function convite_ata($id = null)
    {
        $this->sma->checkPermissions();
      
      
        $this->form_validation->set_rules('descricao_texto', lang("Texto do Convite"), 'required');
        
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        
        
          if ($this->form_validation->run() == true) {
           
            $date_cadastro = date('Y-m-d H:i:s');       
            $usuario = $this->session->userdata('user_id'); 
            $empresa = $this->session->userdata('empresa'); 
            $descricao_texto = $this->input->post('descricao_texto');
            $id_ata = $this->input->post('id');
            $ip = $_SERVER["REMOTE_ADDR"];
            
            $dados_ata  = $this->atas_model->getAtaSemProjetoByID($id_ata);
            $data_reuniao = $dados_ata->data_ata;
            $data_hora_inicio = $dados_ata->hora_inicio;
            $data_hora_fim = $dados_ata->hora_termino;
            $titulo = $dados_ata->assunto;
            $local = $dados_ata->local;
            
                 
            $cont_qte = 0;
            $participantes_cadastrados_ata = $this->atas_model->getAtaUserParticipante_ByID_ATA($id_ata);
            foreach ($participantes_cadastrados_ata as $participante_cadastrados) {
                 $usuario_destino = $participante_cadastrados->id;
                 $participante = $participante_cadastrados->participante;
                 $vinculo = $participante_cadastrados->vinculo;
                    
                    
                 if (isset($_POST[$usuario_destino.'convidar'])) {
                     
                    
                    if($participante == 1){
                        $obrigatorio = 1;
                    }else {
                        $obrigatorio = 0;
                        
                         if($vinculo == 1){
                           $ciente = 1;
                         }else {
                           $ciente = 0;
                         }       
                        
                    }

                                                
                    // ADICIONA O INVITE
                    $data_invite = array(
                        'user_origem' => $usuario, 
                          'user_destino' => $usuario_destino, 
                          'ciente' => $ciente, 
                          'data_evento' => $data_reuniao, 
                          'hora_inicio' => $data_hora_inicio, 
                          'hora_fim' => $data_hora_fim,
                          'titulo' => $titulo,
                          'local' => $local,
                          'confirmacao' => 3,
                          'data_criacao' => $date_cadastro,
                          'ip' => $ip,
                          'obrigatorio' => $obrigatorio,
                          'empresa' => $empresa,
                          'ata' => $id_ata,
                          'status' => 1,
                          'tipo' => 'REUNIÃO',
                          'texto_detalhe' => $descricao_texto);  
                    $this->atas_model->addInviteAta($data_invite);
        
                    //ADICIONA NOTIFICAÇÃO
                    
                    $data_email = array(
                        'id_from' => $usuario,
                        'id_to' => $usuario_destino,
                        'title' => "Convite de Reunião",
                        'text' => "$descricao_texto",
                        'lida' => 0,
                        'data' => $date_cadastro,
                        'referencia' => "welcome > convite_ata",
                        'empresa' => $empresa,
                        'enviado' => 0,
                        'convite' => 1);
                        $this->atas_model->add_email($data_email);
                    
                    
                     $cont_qte++;
                } else {
                    //var_dump("não escolheu ");
                    
                   // $this->atas_model->updateParticipantesAta($id_ata, $usuario, 0);
                }
                
                
               
            }
            
            
            if($cont_qte > 0){
            $this->session->set_flashdata('message', lang("Convite Enviado com Sucesso!!!"));
            redirect("welcome/convite_ata/".$id_ata);
            }else{
                $this->session->set_flashdata('error', lang("Selecione pelo menos 1 participante!!!"));
            redirect("welcome/convite_ata/".$id_ata);
            }
            
        } else {

          
            $this->data['id'] = $id;
           
            $this->data['ata'] = $this->atas_model->getAtaSemProjetoByID($id);
            
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
           // $this->data['users'] = $this->atas_model->getAllUsersSetores(); 
           // $this->data['projetos'] = $this->atas_model->getAllProjetos();
           // $this->data['users_ata'] = $this->atas_model->getConvitesAtaUserByID_ATA($id);
            $bc = array(array('link' => site_url('atas'), 'page' => lang('Atas')), array('link' => site_url('atas/edit'), 'page' => lang('Editar ATA')));
            $meta = array('page_title' => lang('Editar ATA'), 'bc' => $bc);
            $this->page_construct_networking('networking/ata/minhas_atas/convite', $meta, $this->data);
            
           

            }
    }
    
    /***************************************** FIM MINHAS ATAS *****************************************************/
    
    
    
    /************************************************************************************************************
     ****************************************** PLANO DE AÇÃO **********************************************
     ************************************************************************************************************/
    public function plano_acao_networking($tabela, $menu)
    {
     
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        
        $this->form_validation->set_rules('id_cadastro', lang("id_cadastro"), 'required');
         
         if ($this->form_validation->run() == true) {
           
             
            $id_pa = $this->input->post('id');  
            $assunto = $this->input->post('assunto'); 
            $data_pa = $this->input->post('data_pa');
            $data_ate = $this->input->post('data_ate');
            $responsavel = $this->input->post('responsavel');
            $responsavel_aprovacao = $this->input->post('responsavel_aprovacao');
            $objetivos = $this->input->post('objetivos');
            
            $data_plano = array(
                'assunto' => $assunto,
                'objetivos' => $objetivos,
                'responsavel' => $responsavel,
                'responsavel_aprovacao' => $responsavel_aprovacao,
                'data_pa' => $data_pa,
                'data_termino_previsto' => $data_ate
            );
            //  print_r($data_plano); exit;
             $this->atas_model->updatePlanoAcao($id_pa, $data_plano);
              
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
                'modulo' => 'welcome',
                'funcao' => 'welcome/plano_acao_networking',  
                'empresa' => $this->session->userdata('empresa'));
           
               $this->owner_model->addLog($logdata);  
           // exit;
            
            $this->session->set_flashdata('message', lang("Cadastro atualizado com Sucesso!!!"));
            redirect("welcome/plano_acao_detalhes/$id_pa");
            
         }else{
             
            $tabela_cadastro = $this->owner_model->getTableById(89);
            $tabela_nome = $tabela_cadastro->tabela;
            $menu = 89;
            $this->data['tabela_nome'] = $tabela_nome;
            $this->data['tabela_id'] = $tabela;
            $this->data['menu_id'] = $menu;
            $this->data['titulo'] = $tabela_cadastro->titulo;
            $this->data['descricao_titulo'] = $tabela_cadastro->descricao;
            $this->data['menu'] = "cadastro";
            $this->data['submenu'] = "modulo";
            
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
            $this->data['planos_acao'] = $this->networking_model->getAllPlanoAcaoByUser();
            //$this->data['campos'] = $this->owner_model->getAllCamposTablesLista($tabela);
            //$this->data['cadastrosHabilitados'] = $this->owner_model->getAllCamposTablesCadastro($tabela);
            
           // $this->data['botoes_menu'] = $this->owner_model->getAllBotoesByTabela($tabela);
            $this->page_construct_networking('networking/plano_acao/index', $meta, $this->data);
           // $this->page_construct_user('owner/empresas/index', $meta, $this->data);
         }
         
         
    }
    
    public function novoPlano($tabela, $menu) {


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
            $objetivos = $this->input->post('objetivos');
            $responsavel = $this->input->post('responsavel');
            $responsavel_aprovacao = $this->input->post('responsavel_aprovacao');
            $periodo_evento = $this->input->post('periodo_evento');
            
            $evento_periodo_de = substr($periodo_evento, 0, 10);
             $partes_data_inicio = explode("/", $evento_periodo_de);
             $ano_de = $partes_data_inicio[2];
             $mes = $partes_data_inicio[1];
             $dia = $partes_data_inicio[0];
             $data_plano_de = $ano_de.'-'.$mes.'-'.$dia;
            
             $evento_periodo_ate = substr($periodo_evento, 13, 24);
             $partes_data_fim = explode("/", $evento_periodo_ate);
             $anof = $partes_data_fim[2];
             $mesf = $partes_data_fim[1];
             $diaf = $partes_data_fim[0];
             $data_plano_ate = $anof.'-'.$mesf.'-'.$diaf;
             
            
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
                'objetivos' => $objetivos,
                'responsavel' => $responsavel,
                'responsavel_aprovacao' => $responsavel_aprovacao,
                'data_pa' => $data_plano_de,
                'sequencial' => $sequencia,
                'data_termino_previsto' => $data_plano_ate,
                'networking' => 1,
                'status' => 0
            );
          //  print_r($data_modulo); exit;
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
            $usuario = $this->session->userdata('user_id');
            $empresa = $this->session->userdata('empresa');
            $ip = $_SERVER["REMOTE_ADDR"];

            $logdata = array('date' => date('Y-m-d H:i:s'),
                'type' => 'INSERT',
                'description' => 'Cadastro de um novo Plano de Ação, ID: '.$id_cadastro,
                'userid' => $this->session->userdata('user_id'),
                'ip_address' => $_SERVER["REMOTE_ADDR"],
                'tabela' => 'sig_plano_acao',
                'row' => $id_cadastro,
                'depois' => json_encode($data_modulo),
                'modulo' => 'welcome',
                'funcao' => 'welcome/novoPlano',
                'empresa' => $this->session->userdata('empresa'));

            $this->owner_model->addLog($logdata);
            // exit;

            $this->session->set_flashdata('message', lang("Cadastro realizado com Sucesso!!!"));
            redirect("welcome/plano_acao_networking/0/89");
        } else {

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
          $this->load->view($this->theme . 'networking/plano_acao/novoPlano', $this->data);  
          //  $this->page_construct_project('project/cadastro_basico_modelo/plano_acao/plano', $meta, $this->data);
        }
    }
        
    public function plano_acao_detalhes($id_pa)
    {
     
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        
         $this->form_validation->set_rules('descricao', lang("Descrição"), 'required');
        $this->form_validation->set_rules('periodo_acao', lang("Data Início e Término"), 'required');
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
            
            //$status = trim($this->input->post('status_plano')); 
            $date_cadastro = date('Y-m-d H:i:s');  
            $usuario = $this->session->userdata('user_id');
            $empresa = $this->session->userdata('empresa');
             
            $users_dados = $this->site->geUserByID($usuario);
            $modulo_atual_id = $users_dados->modulo_atual;
            $projeto_atual_id = $users_dados->projeto_atual; 
            
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
                         
            
           
            $cont_r = 0;
            foreach ($responsaveis as $responsavel) {
             $cont_r++;   
            }
            if($cont_r == 0){
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
                'status' => 'PENDENTE',
                'data_elaboracao' => $date_cadastro,   
                'responsavel_elaboracao' => $usuario,
                'eventos' => $evento,
                'status_tipo' => 1,
                'sequencial' => $sequencia,
                'empresa' => $empresa,
                'peso' => $peso,
                'projeto' => $projeto_atual_id
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
            
                        
           $id_acao = $this->atas_model->add_planoAcao($data_plano,$acao_vinculo,$tipo_vinculo,$id_responsavel);
           
           
           
           
           
           
           /***********************************************************************************************
            ********************** L O G     A Ç Ã O ****************************************************** 
            ***********************************************************************************************/
           $data_log = array(
                'idplano' => $id_acao,
                'data_registro' => $date_cadastro,
                'usuario' => $usuario,
                'descricao' => "Ação Criada",
                'empresa' => $empresa
              );
            $this->atas_model->add_logPlano($data_log);
           
           
           
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
           
            
            }

            
            
            
            $this->session->set_flashdata('message', lang("Ação Cadastrada com Sucesso!!!"));
            //redirect("project/manutencao_acao_pendente/".$id_acao);
             echo "<script>history.go(-1)</script>";
                exit;
                
        }else{
             
                $menu = 89;
                // SALVA O MENU ATUAL do usuário
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
            
                    
                   
            $this->data['plano_acao'] = $this->atas_model->getPlanoAcaoByID($id_pa);
            $this->data['planos'] = $this->atas_model->getAllAcaoPlanoAcaoById($id_pa);
            $this->data['categorias'] = $this->networking_model->getAllCategoriaPlanoAcaoByPlano($id_pa);
            $this->data['atas'] = $this->networking_model->getAllMinhasAtasByUserAndPlanoAcao($id_pa);
            
            $this->page_construct_networking_pa('networking/plano_acao/plano', $meta, $this->data);
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
                'modulo' => 'welcome',
                'funcao' => 'welcome/novaCategoriaPlano',
                'empresa' => $this->session->userdata('empresa'));

            $this->owner_model->addLog($logdata);
            // exit;

            $this->session->set_flashdata('message', lang("Cadastro realizado com Sucesso!!!"));
            redirect("welcome/plano_acao_detalhes/$plano_id");
        } else {

        $this->data['plano_id'] = $id_pa;
        $usuario = $this->session->userdata('user_id');                     
        $this->load->view($this->theme . 'networking/plano_acao/novaCategoria', $this->data);  
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
    
    public function finalizaPlano($id_plano = null)
    {
        $this->sma->checkPermissions();
      
        $date_finalizacao = date('Y-m-d H:i:s');       
        $envia_email = 1;
        
          if ($id_plano) {
           
            $status = 1;
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
            //print_r($data_plano); exit;
            
            $this->atas_model->updateAcoesPlanoAcao($id_plano, $data_plano);
            //print_r($data_ata); exit;
            //finaliza o plano de ação
            $this->atas_model->updatePlanoAcao($id_plano, $data_ataFinalizacao);
            
            
            $planos_ata = $this->atas_model->getAllPlanoAbertosByPlanoAcaoID($id_plano);
            foreach ($planos_ata as $plano_ata) {
                $id_acao = $plano_ata->idplanos;
                $id_responsavel = $plano_ata->responsavel;
                //  $id_acao = $usuario->sequencia;
               
                // REGISTRA A NOTIFICAÇÃO
                    $users_dados = $this->site->geUserByID($id_responsavel);
                    $nome_usuario = $users_dados->first_name;

                    $data_notificacao = array(
                        'id_from' => $usuario,
                        'id_to' => $id_responsavel,
                        'title' => "Nova Ação",
                        'text' => "Parabéns $nome_usuario, Você recebeu uma nova ação.  ",
                        'lida' => 0,
                        'data' => $date_finalizacao,
                        'email' => $envia_email,
                        'idplano' => $id_acao,
                        'empresa' => $this->session->userdata('empresa')
                    );
                    $this->atas_model->add_notificacoes($data_notificacao);

                    //cadastro para envio de email
                    $data_email = array(
                        'id_from' => $usuario,
                        'id_to' => $id_responsavel,
                        'title' => "Nova Ação",
                        'text' => "Parabéns $nome_usuario, você recebeu uma nova ação. Acessar o SigPlus para mais detalhes.",
                        'lida' => 0,
                        'data' => $date_finalizacao,
                        'referencia' => "welcome > finalizaPlano",
                        'idplano' => $id_acao,
                        'empresa' => $empresa,
                        'enviado' => 0    );
                    $this->atas_model->add_email($data_email);

                  
               //   $this->ion_auth->emailAtaUsuario($id_usuario, $id_acao);
            }
            
               
           
       
            
            $this->session->set_flashdata('message', lang("Plano Fechado com Sucesso!!!"));
            redirect("welcome/plano_acao_detalhes/$id_plano");
            
        } else {

           
            redirect("welcome/plano_acao_detalhes/$id_plano");
            
           

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
            $html = $this->load->view($this->theme . 'networking/plano_acao/pdf_plano', $this->data, true);

            
          
            
        if ($view) {
            $this->load->view($this->theme . 'networking/plano_acao/pdf_plano', $this->data);
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
    
    /******************   F I M  *  P L A N O  D E  A Ç Ã O  ********************************/




    
    /***********************************************************************************
    ********************************** T A R E F A S **********************************
    ***********************************************************************************/
    public function novaTarefa($tabela,  $menu, $funcao)
    {
        $date_cadastro = date('Y-m-d H:i:s');                           
        
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        
        $this->form_validation->set_rules('id_cadastro', lang("id_cadastro"), 'required');
         if ($this->form_validation->run() == true) {
           
             $funcao = $this->input->post('funcao'); 
           
             $date_hoje = date('Y-m-d H:i:s');    
            $usuario = $this->session->userdata('user_id');
            $empresa = $this->session->userdata('empresa');   
            $descricao = $this->input->post('descricao');
            $data_inicio = $this->input->post('data_inicio');
            $data_termino = $this->input->post('data_termino');
           
                    $data_modulo = array(
                       'descricao' => $descricao,
                       'status' => 0,
                       'data_criacao' => $date_hoje,
                       'user' => $usuario,
                       'empresa' => $empresa,
                       'data_inicio' => $data_inicio,
                       'data_termino' => $data_termino
                       
                    );
                    
            $id_cadastro =  $this->owner_model->addCadastro('tarefas', $data_modulo);
              
            
            $ip = $_SERVER["REMOTE_ADDR"];

            $logdata = array('date' => date('Y-m-d H:i:s'), 
                'type' => 'INSERT', 
                'description' => 'Cadastro de uma nova tarefa ',  
                'userid' => $this->session->userdata('user_id'), 
                'ip_address' => $_SERVER["REMOTE_ADDR"],
                'tabela' => 'sig_tarefa',
                'row' => $id_cadastro,
                'depois' => json_encode($data_modulo), 
                'modulo' => 'networking',
                'funcao' => 'networking/tarefas',  
                'empresa' => $this->session->userdata('empresa'));
           
               $this->owner_model->addLog($logdata);  
           // exit;
            
            $this->session->set_flashdata('message', lang("Cadastro realizado com Sucesso!!!"));
            redirect("welcome/$funcao/90/72");
            
         }else{
        
           
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
       
        //$this->load->view($this->theme . 'projetos/documentacao/add', $this->data);
        $this->load->view($this->theme . 'networking/cadastro_basico_modelo/tarefa', $this->data);
         }   

            
    }
    
    public function tarefas($tabela, $menu, $funcao)
    {
     
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        
        $this->form_validation->set_rules('id_cadastro', lang("id_cadastro"), 'required');
         
         if ($this->form_validation->run() == true) {
           
            $funcao = $this->input->post('funcao'); 
           
            $date_hoje = date('Y-m-d H:i:s');    
            $usuario = $this->session->userdata('user_id');
            $empresa = $this->session->userdata('empresa');   
            $descricao = $this->input->post('descricao');
            $data_inicio = $this->input->post('data_inicio');
            $data_termino = $this->input->post('data_termino');
           
                $data_modulo = array(
                   'descricao' => $descricao,
                   'status' => 0,
                   'data_criacao' => $date_hoje,
                   'user' => $usuario,
                   'empresa' => $empresa,
                   'data_inicio' => $data_inicio,
                   'data_termino' => $data_termino

                );
                    
            $id_cadastro =  $this->owner_model->addCadastro('tarefas', $data_modulo);
              
            
            $ip = $_SERVER["REMOTE_ADDR"];

            $logdata = array('date' => date('Y-m-d H:i:s'), 
                'type' => 'INSERT', 
                'description' => 'Cadastro de uma nova tarefa ',  
                'userid' => $this->session->userdata('user_id'), 
                'ip_address' => $_SERVER["REMOTE_ADDR"],
                'tabela' => 'sig_tarefa',
                'row' => $id_cadastro,
                'depois' => json_encode($data_modulo), 
                'modulo' => 'networking',
                'funcao' => 'networking/tarefas',  
                'empresa' => $this->session->userdata('empresa'));
           
               $this->owner_model->addLog($logdata);  
           // exit;
            
            $this->session->set_flashdata('message', lang("Cadastro realizado com Sucesso!!!"));
            redirect("welcome/$funcao/90/72");
            
         }else{
             
            $tabela_cadastro = $this->owner_model->getTableById($tabela);
            $tabela_nome = $tabela_cadastro->tabela;
            $menu = 72;
            $this->data['tabela_nome'] = $tabela_nome;
            $this->data['tabela_id'] = $tabela;
            $this->data['menu_id'] = $menu;
            $this->data['titulo'] = $tabela_cadastro->titulo;
            $this->data['descricao_titulo'] = $tabela_cadastro->descricao;
            $this->data['menu'] = "cadastro";
            $this->data['submenu'] = "modulo";
            
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
                    'description' => "Acessou o menu $menu do Módulo NETWORKING",  
                    'userid' => $this->session->userdata('user_id'), 
                    'ip_address' => $_SERVER["REMOTE_ADDR"],
                    'tabela' => 'SIG_TAREFAS',
                    'row' => '',
                    'depois' => '', 
                    'modulo' => 'networking',
                    'funcao' => 'networking/tarefas',  
                    'empresa' => $this->session->userdata('empresa'));
                    $this->owner_model->addLog($logdata); 
            
            //$this->data['modulos'] = $this->owner_model->getTablesCadastroBasico($tabela);
            $this->data['tarefas'] = $this->networking_model->getAllTarefas();
            //$this->data['campos'] = $this->owner_model->getAllCamposTablesLista($tabela);
            //$this->data['cadastrosHabilitados'] = $this->owner_model->getAllCamposTablesCadastro($tabela);
            
           // $this->data['botoes_menu'] = $this->owner_model->getAllBotoesByTabela($tabela);
            $this->page_construct_networking('networking/tarefas/index', $meta, $this->data);
           // $this->page_construct_user('owner/empresas/index', $meta, $this->data);
         }
         
         
    }
    
    public function tarefas_home($tabela, $menu)
    {
     
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        
        $this->form_validation->set_rules('id_cadastro', lang("id_cadastro"), 'required');
         
         if ($this->form_validation->run() == true) {
           
             $date_hoje = date('Y-m-d H:i:s');    
            $usuario = $this->session->userdata('user_id');
            $empresa = $this->session->userdata('empresa');   
             $descricao = $this->input->post('descricao');
          
                    $data_modulo = array(
                       'descricao' => $descricao,
                       'status' => 0,
                       'data_criacao' => $date_hoje,
                       'user' => $usuario,
                       'empresa' => $empresa
                       
                    );
                    
            $id_cadastro =  $this->owner_model->addCadastro('tarefas', $data_modulo);
              
            
            $ip = $_SERVER["REMOTE_ADDR"];

            $logdata = array('date' => date('Y-m-d H:i:s'), 
                'type' => 'INSERT', 
                'description' => 'Cadastro de uma nova tarefa ',  
                'userid' => $this->session->userdata('user_id'), 
                'ip_address' => $_SERVER["REMOTE_ADDR"],
                'tabela' => 'sig_tarefa',
                'row' => $id_cadastro,
                'depois' => json_encode($data_modulo), 
                'modulo' => 'networking',
                'funcao' => 'networking/tarefas',  
                'empresa' => $this->session->userdata('empresa'));
           
               $this->owner_model->addLog($logdata);  
           // exit;
            
            $this->session->set_flashdata('message', lang("Cadastro realizado com Sucesso!!!"));
            redirect("welcome");
            
         }else{
             
            $tabela_cadastro = $this->owner_model->getTableById($tabela);
            $tabela_nome = $tabela_cadastro->tabela;
            $menu = 72;
            $this->data['tabela_nome'] = $tabela_nome;
            $this->data['tabela_id'] = $tabela;
            $this->data['menu_id'] = $menu;
            $this->data['titulo'] = $tabela_cadastro->titulo;
            $this->data['descricao_titulo'] = $tabela_cadastro->descricao;
            $this->data['menu'] = "cadastro";
            $this->data['submenu'] = "modulo";
            
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
                    'description' => "Acessou o menu $menu do Módulo NETWORKING",  
                    'userid' => $this->session->userdata('user_id'), 
                    'ip_address' => $_SERVER["REMOTE_ADDR"],
                    'tabela' => 'SIG_TAREFAS',
                    'row' => '',
                    'depois' => '', 
                    'modulo' => 'networking',
                    'funcao' => 'networking/tarefas',  
                    'empresa' => $this->session->userdata('empresa'));
                    $this->owner_model->addLog($logdata); 
            
            //$this->data['modulos'] = $this->owner_model->getTablesCadastroBasico($tabela);
            $this->data['tarefas'] = $this->networking_model->getAllTarefas();
            //$this->data['campos'] = $this->owner_model->getAllCamposTablesLista($tabela);
            //$this->data['cadastrosHabilitados'] = $this->owner_model->getAllCamposTablesCadastro($tabela);
            
           // $this->data['botoes_menu'] = $this->owner_model->getAllBotoesByTabela($tabela);
            $this->page_construct_project('networking/tarefas/index', $meta, $this->data);
           // $this->page_construct_user('owner/empresas/index', $meta, $this->data);
         }
         
         
    }
    
    public function ConcluirTarefas($tabela_id, $cadastro_id, $menu, $funcao)
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
             
             $date_hoje = date('Y-m-d H:i:s');    
            $usuario = $this->session->userdata('user_id');
            $empresa = $this->session->userdata('empresa');   
             $descricao = $this->input->post('descricao');
           // echo $descricao; exit;
                    $data_modulo = array(
                       
                       'status' => 1
                       
                    );
              
               $tabela_sig = 'tarefas';     
              $this->owner_model->updateCadastro($id_cadastro, $tabela_sig, $data_modulo);       
           
              
            
            $ip = $_SERVER["REMOTE_ADDR"];

            $logdata = array('date' => date('Y-m-d H:i:s'), 
                'type' => 'INSERT', 
                'description' => 'Conclusão de tarefa ',  
                'userid' => $this->session->userdata('user_id'), 
                'ip_address' => $_SERVER["REMOTE_ADDR"],
                'tabela' => 'sig_tarefa',
                'row' => $id_cadastro,
                'depois' => json_encode($data_modulo), 
                'modulo' => 'networking',
                'funcao' => 'networking/ConcluirTarefas',  
                'empresa' => $this->session->userdata('empresa'));
           
               $this->owner_model->addLog($logdata);  
           // exit;
            
            $this->session->set_flashdata('message', lang("Cadastro realizado com Sucesso!!!"));
            redirect("welcome/$funcao/90/72");
            
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

                // registra o log de movimentação

                $date_hoje = date('Y-m-d H:i:s');    
                $usuario = $this->session->userdata('user_id');
                $empresa = $this->session->userdata('empresa');
                $ip = $_SERVER["REMOTE_ADDR"];

                $logdata = array('date' => date('Y-m-d H:i:s'), 
                    'type' => 'ACESSO', 
                    'description' => "Acessou o menu $menu do Módulo NETWORKING",  
                    'userid' => $this->session->userdata('user_id'), 
                    'ip_address' => $_SERVER["REMOTE_ADDR"],
                    'tabela' => 'SIG_TAREFAS',
                    'row' => '',
                    'depois' => '', 
                    'modulo' => 'networking',
                    'funcao' => 'networking/tarefas',  
                    'empresa' => $this->session->userdata('empresa'));
                    $this->owner_model->addLog($logdata); 
               
            
           // $this->data['botoes_menu'] = $this->owner_model->getAllBotoesByTabela($tabela);
            $this->load->view($this->theme . 'networking/cadastro_basico_modelo/concluir_tarefa', $this->data);
           // $this->page_construct_user('owner/empresas/index', $meta, $this->data);
         }
         
         
    }
    
    
    /******************   F I M  *  T A R E F A S   ********************************/
    
    
    /***************************************************************************************
     ************************* C A L E N D A R I O *****************************************
     ***************************************************************************************/
    public function novoCompromisso()
    {
        $date_cadastro = date('Y-m-d H:i:s');                           
        
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        
        $this->form_validation->set_rules('id_cadastro', lang("id_cadastro"), 'required');
         if ($this->form_validation->run() == true) {
           
            
            $date_hoje = date('Y-m-d H:i:s');    
            $usuario = $this->session->userdata('user_id');
            $empresa = $this->session->userdata('empresa');   
            $descricao = $this->input->post('descricao');
            $tipo = $this->input->post('tipo');
            $data_inicio = $this->input->post('data_de');
            $local = $this->input->post('local');
            $hora_inicio = $this->input->post('hora_inicio');
            $hora_termino = $this->input->post('hora_termino');
            
            $dados_user =  $this->atas_model->getUserSetorByUserID($usuario);
            $user_destino = $dados_user->id;
            
            $data_modulo = array(
               'user_origem' => $usuario,
               'user_destino' => $user_destino,
               'confirmacao' => 1,
               'data_criacao' => $date_hoje, 
               'data_confirmacao' => $date_hoje, 
               'ip' => $_SERVER["REMOTE_ADDR"], 
               'obrigatorio' => 1,  
               'empresa' => $empresa,
               'status' => 1,
                
               'titulo' => $descricao,
               'tipo' => $tipo, 
               'data_evento' => $data_inicio,
               'hora_inicio' => $hora_inicio,
               'hora_fim' => $hora_termino, 
               'local' => $local  
            );
           // print_r($data_modulo); exit;        
            $id_cadastro =  $this->owner_model->addCadastro('invites', $data_modulo);
              
            
            $ip = $_SERVER["REMOTE_ADDR"];

            $logdata = array('date' => date('Y-m-d H:i:s'), 
                'type' => 'INSERT', 
                'description' => 'Cadastro de um novo compromisso',  
                'userid' => $this->session->userdata('user_id'), 
                'ip_address' => $_SERVER["REMOTE_ADDR"],
                'tabela' => 'sig_invites',
                'row' => $id_cadastro,
                'depois' => json_encode($data_modulo), 
                'modulo' => 'networking',
                'funcao' => 'networking/novoCompromisso',  
                'empresa' => $this->session->userdata('empresa'));
           
               $this->owner_model->addLog($logdata);  
           // exit;
            
            $this->session->set_flashdata('message', lang("Cadastro realizado com Sucesso!!!"));
            redirect("welcome/index");
            
         }else{
        
           
                   
       
        //$this->load->view($this->theme . 'projetos/documentacao/add', $this->data);
        $this->load->view($this->theme . 'networking/cadastro_basico_modelo/compromisso', $this->data);
         }   

            
    }
    
    public function calendario($tabela, $menu)
    {
          
       $this->sma->checkPermissions(); 
        if ($this->Settings->version == '2.3') {
            $this->session->set_flashdata('warning', 'Please complete your update by synchronizing your database.');
            redirect('sync');
        }

        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        
        $usuario = $this->session->userdata('user_id');                     
        
        // SALVA O MÓDULO ATUAL do usuário
         $data_modulo = array('modulo_atual' => 3, 'menu_atual' => $menu);
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
       $pagina_home = 'networking/calendario/index';
      
       
       
       $this->page_construct_project_calendario_welcome($pagina_home, $meta, $this->data);
       
    }
    /******************   F I M  *  C A L E N D A R I O   ********************************/
   
    
    /***************************************************************************************
     ************************* C O N V I D A D O S *****************************************
     ***************************************************************************************/
    public function convites($tabela, $menu, $funcao)
    {
     
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        
        $this->form_validation->set_rules('id_cadastro', lang("id_cadastro"), 'required');
         
         if ($this->form_validation->run() == true) {
           
            $funcao = $this->input->post('funcao'); 
           
            $date_hoje = date('Y-m-d H:i:s');    
            $usuario = $this->session->userdata('user_id');
            $empresa = $this->session->userdata('empresa');   
            $descricao = $this->input->post('descricao');
            $data_inicio = $this->input->post('data_inicio');
            $data_termino = $this->input->post('data_termino');
           
                $data_modulo = array(
                   'descricao' => $descricao,
                   'status' => 0,
                   'data_criacao' => $date_hoje,
                   'user' => $usuario,
                   'empresa' => $empresa,
                   'data_inicio' => $data_inicio,
                   'data_termino' => $data_termino

                );
                    
            $id_cadastro =  $this->owner_model->addCadastro('tarefas', $data_modulo);
              
            
            $ip = $_SERVER["REMOTE_ADDR"];

            $logdata = array('date' => date('Y-m-d H:i:s'), 
                'type' => 'INSERT', 
                'description' => 'Cadastro de uma nova tarefa ',  
                'userid' => $this->session->userdata('user_id'), 
                'ip_address' => $_SERVER["REMOTE_ADDR"],
                'tabela' => 'sig_tarefa',
                'row' => $id_cadastro,
                'depois' => json_encode($data_modulo), 
                'modulo' => 'networking',
                'funcao' => 'networking/tarefas',  
                'empresa' => $this->session->userdata('empresa'));
           
               $this->owner_model->addLog($logdata);  
           // exit;
            
            $this->session->set_flashdata('message', lang("Cadastro realizado com Sucesso!!!"));
            redirect("welcome/$funcao/90/72");
            
         }else{
             
            $tabela_cadastro = $this->owner_model->getTableById($tabela);
            $tabela_nome = $tabela_cadastro->tabela;
            $menu = 88;
            $this->data['tabela_nome'] = $tabela_nome;
            $this->data['tabela_id'] = $tabela;
            $this->data['menu_id'] = $menu;
            $this->data['titulo'] = $tabela_cadastro->titulo;
            $this->data['descricao_titulo'] = $tabela_cadastro->descricao;
            $this->data['menu'] = "cadastro";
            $this->data['submenu'] = "modulo";
            
                // SALVA O MÓDULO ATUAL do usuário
                 $usuario = $this->session->userdata('user_id');    
                 $data_modulo = array('modulo_atual' => 3, 'menu_atual' => $menu);
                 $this->owner_model->updateModuloAtual($usuario, $data_modulo);

                // registra o log de movimentação

                $date_hoje = date('Y-m-d H:i:s');    
                $usuario = $this->session->userdata('user_id');
                $empresa = $this->session->userdata('empresa');
                $ip = $_SERVER["REMOTE_ADDR"];

                $logdata = array('date' => date('Y-m-d H:i:s'), 
                    'type' => 'ACESSO', 
                    'description' => "Acessou o menu $menu do Módulo NETWORKING",  
                    'userid' => $this->session->userdata('user_id'), 
                    'ip_address' => $_SERVER["REMOTE_ADDR"],
                    'tabela' => 'SIG_TAREFAS',
                    'row' => '',
                    'depois' => '', 
                    'modulo' => 'networking',
                    'funcao' => 'networking/tarefas',  
                    'empresa' => $this->session->userdata('empresa'));
                    $this->owner_model->addLog($logdata); 
            
            //$this->data['modulos'] = $this->owner_model->getTablesCadastroBasico($tabela);
            $this->data['convites'] = $this->networking_model->getAllConvitesRecebidos();
            //$this->data['campos'] = $this->owner_model->getAllCamposTablesLista($tabela);
            //$this->data['cadastrosHabilitados'] = $this->owner_model->getAllCamposTablesCadastro($tabela);
            
           // $this->data['botoes_menu'] = $this->owner_model->getAllBotoesByTabela($tabela);
            $this->page_construct_networking('networking/convites/index', $meta, $this->data);
           // $this->page_construct_user('owner/empresas/index', $meta, $this->data);
         }
         
         
    }
    
    //quando o usuario abre para ver os detalhes 
    public function abrirConvite($cadastro_id)
    {
     
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        
        $this->data['convite'] = $this->networking_model->getConviteById($cadastro_id);
        $this->load->view($this->theme . 'networking/convites/editar', $this->data);
    }
    
    //Resposta do usuario
    public function responderConvite($convite, $resposta)
    {
     
        $date_hoje = date('Y-m-d H:i:s');    
        $data_invite = array('confirmacao' => $resposta, 'data_confirmacao' => $date_hoje);
        $this->networking_model->updateRespostaConvite($convite, $data_invite);
                 
      redirect("welcome/convites/107/88");
    }
    
    /******************   F I M  *  C O N V I D A D O S   ********************************/
    
    /***************************************************************************************
     ************************* M E N S A G E N S *****************************************
     ***************************************************************************************/
    public function mensagens($tabela, $menu)
    {
     
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        
        $this->form_validation->set_rules('id_cadastro', lang("id_cadastro"), 'required');
         
         if ($this->form_validation->run() == true) {
           
            $funcao = $this->input->post('funcao'); 
           
            $date_hoje = date('Y-m-d H:i:s');    
            $usuario = $this->session->userdata('user_id');
            $empresa = $this->session->userdata('empresa');   
            $descricao = $this->input->post('descricao');
            $data_inicio = $this->input->post('data_inicio');
            $data_termino = $this->input->post('data_termino');
           
                $data_modulo = array(
                   'descricao' => $descricao,
                   'status' => 0,
                   'data_criacao' => $date_hoje,
                   'user' => $usuario,
                   'empresa' => $empresa,
                   'data_inicio' => $data_inicio,
                   'data_termino' => $data_termino

                );
                    
            $id_cadastro =  $this->owner_model->addCadastro('tarefas', $data_modulo);
              
            
            $ip = $_SERVER["REMOTE_ADDR"];

            $logdata = array('date' => date('Y-m-d H:i:s'), 
                'type' => 'INSERT', 
                'description' => 'Cadastro de uma nova tarefa ',  
                'userid' => $this->session->userdata('user_id'), 
                'ip_address' => $_SERVER["REMOTE_ADDR"],
                'tabela' => 'sig_tarefa',
                'row' => $id_cadastro,
                'depois' => json_encode($data_modulo), 
                'modulo' => 'networking',
                'funcao' => 'networking/tarefas',  
                'empresa' => $this->session->userdata('empresa'));
           
               $this->owner_model->addLog($logdata);  
           // exit;
            
            $this->session->set_flashdata('message', lang("Cadastro realizado com Sucesso!!!"));
            redirect("welcome/$funcao/90/72");
            
         }else{
             
           
            
                // SALVA O MÓDULO ATUAL do usuário
                 $usuario = $this->session->userdata('user_id');    
                 $data_modulo = array('modulo_atual' => 3, 'menu_atual' => $menu);
                 $this->owner_model->updateModuloAtual($usuario, $data_modulo);

                // registra o log de movimentação

                $date_hoje = date('Y-m-d H:i:s');    
                $usuario = $this->session->userdata('user_id');
                $empresa = $this->session->userdata('empresa');
                $ip = $_SERVER["REMOTE_ADDR"];

                $logdata = array('date' => date('Y-m-d H:i:s'), 
                    'type' => 'ACESSO', 
                    'description' => "Acessou o menu $menu do Módulo NETWORKING",  
                    'userid' => $this->session->userdata('user_id'), 
                    'ip_address' => $_SERVER["REMOTE_ADDR"],
                    'tabela' => 'SIG_TAREFAS',
                    'row' => '',
                    'depois' => '', 
                    'modulo' => 'networking',
                    'funcao' => 'networking/tarefas',  
                    'empresa' => $this->session->userdata('empresa'));
                    $this->owner_model->addLog($logdata); 
            
            //$this->data['modulos'] = $this->owner_model->getTablesCadastroBasico($tabela);
            $this->data['mensagens'] = $this->networking_model->getAllMensagensRecebidos();
            //$this->data['campos'] = $this->owner_model->getAllCamposTablesLista($tabela);
            //$this->data['cadastrosHabilitados'] = $this->owner_model->getAllCamposTablesCadastro($tabela);
            
           // $this->data['botoes_menu'] = $this->owner_model->getAllBotoesByTabela($tabela);
            $this->page_construct_networking('networking/mensagens/index', $meta, $this->data);
           // $this->page_construct_user('owner/empresas/index', $meta, $this->data);
         }
         
         
    }
    
    
    //Resposta do usuario
    public function responderMensagem($convite, $resposta)
    {
     
        $date_hoje = date('Y-m-d H:i:s');    
        $data_invite = array('confirmacao' => $resposta, 'data_confirmacao' => $date_hoje);
        $this->networking_model->updateRespostaConvite($convite, $data_invite);
                 
      redirect("welcome/convites/107/88");
    }
    
    /******************   F I M  *  M E N S A G E N S   ********************************/
    
    
    
    /**************************************************************************
     ************************* R A T ' S **************************************
     **************************************************************************/
    
    public function registro_atividade($tabela, $menu)
    {
        // SALVA O MÓDULO ATUAL do usuário
        $usuario = $this->session->userdata('user_id');    
        $data_modulo = array('menu_atual' => $menu);
        $this->owner_model->updateModuloAtual($usuario, $data_modulo);
         
        $this->sma->checkPermissions(); 
        
        if ($this->Settings->version == '2.3') {
            $this->session->set_flashdata('warning', 'Please complete your update by synchronizing your database.');
            redirect('sync');
        }
        
        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        $usuario = $this->session->userdata('user_id');                     
        
        
        $filtro = $this->input->post('filtro');
        
        if($filtro == 1){
        $projeto_filtro = $this->input->post('projeto_filtro');
        $data_inicio = $this->input->post('data_de');
        $data_fim = $this->input->post('data_ate');   
        
        $sql_rat  = $this->networking_model->getRegistroAtividadesByUsuario($projeto_filtro,$data_inicio,$data_fim);
        $this->data['rats'] = $sql_rat;
        $this->data['projeto_filtro'] = $projeto_filtro;
        $this->data['data_inicio'] = $data_inicio;
        $this->data['data_fim'] = $data_fim;
        
        }else{
            
        $sql_rat  = $this->networking_model->getRegistroAtividadesByUsuario();  
        $this->data['rats'] = $sql_rat;    
        }
        
       
        
       
        
        $this->page_construct_networking('networking/rat/index', $meta, $this->data);
      //  $this->load->view($this->theme . 'usuarios/rat/lista', $this->data);
    }
    
    
    public function registro_atividade_pdf($view = null)
    {
        $this->form_validation->set_rules('gerar_pdf', lang("id_cadastro"), 'required');
         
         if ($this->form_validation->run() == true) {
          
            $usuario = $this->session->userdata('user_id');
            $empresa = $this->session->userdata('empresa');
        
            $detalhes = $this->input->post('detalhes');
            $sql_rat = $this->input->post('registros');
            $valores_pdf = $this->input->post('valores_pdf');
            
            //echo $valores_pdf; exit;
            /* 
              foreach ($sql_rat as $rat) {
                                            
               $projeto = $rat->projeto;
               $data_rat = $rat->data_rat;
               
               echo $data_rat.'<br>';
              }
             * 
             */                              
             
            
             
             $this->data['detalhes'] = $detalhes;
             $this->data['sql_rat'] = $sql_rat;
             $this->data['valores_pdf'] = $valores_pdf;
             
            //$this->data['plano_acao'] = $this->atas_model->getPlanoAcaoByID($id);  // $this->atas_model->getAtaProjetoByID_ATA($id);
            $empresa_dados = $this->owner_model->getEmpresaById($empresa);
            $logo_empresa = $empresa_dados->logo_empresa;
            $redape_empresa_ata = $empresa_dados->rodape_ata;
            
            $cadastro_usuario =  $this->site->getUser($usuario);
            $nome = $cadastro_usuario->first_name;
            //echo $usuario; exit;
            //$this->data['users'] = $this->site->getAllUser();
           // $this->data['projetos'] = $this->atas_model->getAllProjetos();
            

            $name = lang("Registro_de_Atividades") . "_" . str_replace('/', '_', $nome) . ".pdf";
            $html = $this->load->view($this->theme . 'networking/rat/rat_pdf', $this->data, true);

         
          
            
        if ($view) {
            $this->load->view($this->theme . 'networking/rat/rat_pdf', $this->data);
        } else{
            $this->sma->generate_pdf_registro_atividade($html, $name, false, $usuario, null, null, null, null, $logo_empresa, $redape_empresa_ata, $nome, $empresa);
            //redirect("Welcome/registro_atividade/0/92");
        }
        
           
         }else{
        echo "<script>history.go(-1)</script>";
                exit;
        
        
           
         }
            
    }
    
    
    public function editar_rat($rat_id)
    {
         
        
         
        $this->form_validation->set_rules('editar_rat', lang("Informe a Hora de Início"), 'required');
        $this->form_validation->set_rules('rat_id', lang("Informe a Hora de Término"), 'required');
        $this->form_validation->set_rules('detalhes', lang("Informe o conteúdo"), 'required');
         
          if ($this->form_validation->run() == true) {
           
            $date_cadastro = date('Y-m-d H:i:s'); 
            $data_registro = $this->input->post('data_registro'); 
            $hora_inicio = $this->input->post('hora_inicio');
            $hora_termino = $this->input->post('hora_termino');
            $conteudo = $this->input->post('detalhes');
            $valor2 = $this->input->post('valor');
            $valor = str_replace(',', '.', $valor2);
            $usuario = $this->session->userdata('user_id');
           
            $tempo = gmdate('H:i:s', strtotime( $hora_termino) - strtotime( $hora_inicio  ) );
            
            $rat_id = $this->input->post('rat_id');
           // $rat_id = $this->input->post('rat_id');
            
            $data_rat = array(
                //'data_registro' => $date_cadastro,
                'hora_inicio' => $hora_inicio,
                'hora_fim' => $hora_termino,
                'descricao' => $conteudo,
                'valor' => $valor,
                'data_rat' => $data_registro,
                'tempo' => $tempo
            );
           

          
            $this->atas_model->updateRat($rat_id, $data_rat);
            
            $this->session->set_flashdata('message', lang("Alteração Registrado com Sucesso!!!"));
            redirect("Welcome/registro_atividade/0/92");
            
        } else {
     
            $date_cadastro = date('Y-m-d H:i:s');       
            // $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            
          
            $rat = $this->networking_model->getRatByIdAndByEmpresa($rat_id);
            $this->data['rat'] = $rat;
            $this->data['rat_id'] = $rat_id;
                      
            $this->load->view($this->theme . 'networking/rat/editar_rat', $this->data);
        }

            
    }
    
    public function deletar_rat($rat_id)
    {
         
        
         
        $this->form_validation->set_rules('deletar_rat', lang("Informe"), 'required');
        $this->form_validation->set_rules('rat_id', lang("Informe "), 'required');
        $this->form_validation->set_rules('detalhes', lang("Informe o conteúdo"), 'required');
         
          if ($this->form_validation->run() == true) {
           
            $date_cadastro = date('Y-m-d H:i:s'); 
            $data_registro = $this->input->post('data_registro'); 
            $hora_inicio = $this->input->post('hora_inicio');
            $hora_termino = $this->input->post('hora_termino');
            $conteudo = $this->input->post('detalhes');
            $valor2 = $this->input->post('valor');
            $valor = str_replace(',', '.', $valor2);
            $usuario = $this->session->userdata('user_id');
           
            $tempo = gmdate('H:i:s', strtotime( $hora_termino) - strtotime( $hora_inicio  ) );
            
            $rat_id = $this->input->post('rat_id');
           // $rat_id = $this->input->post('rat_id');
           
            $this->networking_model->deleteRat($rat_id);
            
            $this->session->set_flashdata('message', lang("Registro Apagado com Sucesso!!!"));
            redirect("Welcome/registro_atividade/0/92");
            
        } else {
     
            $date_cadastro = date('Y-m-d H:i:s');       
            // $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            
          
            $rat = $this->networking_model->getRatByIdAndByEmpresa($rat_id);
            $this->data['rat'] = $rat;
            $this->data['rat_id'] = $rat_id;
                      
            $this->load->view($this->theme . 'networking/rat/deletar_rat', $this->data);
        }

            
    }
    
    function dateDiff( $dateStart, $dateEnd, $format = '%a' ) {

        $d1     =   new DateTime( $dateStart );
        $d2     =   new DateTime( $dateEnd );
        //Calcula a diferença entre as datas
        $diff   =   $d1->diff($d2, true);   

        //Formata no padrão esperado e retorna
        return $diff->format( $format );

    }
    
     
    
    
    
     public function add_rat($id = null)
    {
         
        
         
      
     
            $date_cadastro = date('Y-m-d H:i:s');       
            // $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            
           
            
            $this->data['id'] = $id;
            $this->data['dados_equipe'] = $this->atas_model->getMebrosEquipeByIdEquipe($id); //
            
            $projeto_membro = $this->atas_model->getMebrosEquipeByIdMembro($id);
            $projeto_selecionado = $projeto_membro->projeto;
          
             $this->data['projeto'] = $projeto_selecionado;

             $this->load->view($this->theme . 'usuarios/rat/add_rat', $this->data);
    }
    
    
    
    
    /************ F I M ** R A T S ********************************************/
    
    
    
    /***************************************************************************************
     ************************* T R E I N A M E N T O S **************************************
     ***************************************************************************************/
    
    public function treinamentos()
    {
        if ($this->Settings->version == '2.3') {
            $this->session->set_flashdata('warning', 'Please complete your update by synchronizing your database.');
            redirect('sync');
        }

        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        $usuario = $this->session->userdata('user_id');                     
        $this->data['treinamentos'] = $this->atas_model->getTreinamentoFacilitadorByUser($usuario);
        
        $users = $this->site->geUserByID($usuario);
        
        
        //$this->page_construct('usuarios/index', $meta, $this->data);
       
        $this->load->view($this->theme . 'usuarios/treinamentos', $this->data);
    }
    
    public function abrir_treinamento($id = null)
    {
         // $this->sma->checkPermissions();
      
        $date_cadastro = date('Y-m-d H:i:s');       
        
            $facilitador_user = $this->atas_model->getFacilitadorByID($id);
            $ata = $facilitador_user->ata;
          
            $this->data['id'] = $ata;
            $this->data['id_ata_facilitador'] = $id;
            $this->data['facilitador_usuario'] = $facilitador_user->usuario;
           // $this->data['treinamento'] = $this->atas_model->getTreinamentoByATA($id);
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
           $this->data['planos'] = $this->atas_model->getAllitemPlanos($ata); //
            $this->data['projetos'] = $this->atas_model->getAllProjetos();
            $this->data['users_ata'] = $this->atas_model->getAtaUserByID_ATA($ata);
            $usuario = $this->session->userdata('user_id');  
            $this->data['treinamentos'] = $this->atas_model->getTreinamentoFacilitadorByATA($usuario, $ata);
          
            $this->load->view($this->theme . 'usuarios/edit_treinamento', $this->data);
           

            
    }
    
    public function add_treinamento($id = null)
    {
       // $this->sma->checkPermissions();
      
        
        $this->form_validation->set_rules('hora_inicio', lang("Informe a Hora de Início"), 'required');
        $this->form_validation->set_rules('hora_termino', lang("Informe a Hora de Término"), 'required');
        $this->form_validation->set_rules('conteudo', lang("Informe o conteúdo"), 'required');
        
        $date_cadastro = date('Y-m-d H:i:s');       
        
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        
          if ($this->form_validation->run() == true) {
           
         
            $hora_inicio = $this->input->post('hora_inicio');
            $hora_termino = $this->input->post('hora_termino');
            $conteudo = $this->input->post('conteudo');
            $data_criacao = $date_cadastro;
            $usuario = $this->session->userdata('user_id');
            $id_ata = $this->input->post('id_ata');
            
           $id_ata_facilitador =  $this->input->post('id_ata_facilitador');
          
            
            
            $data_ata = array(
                'ata' => $id_ata,
                'data_registro' => $data_criacao,
                'hora_inicio' => $hora_inicio,
                'hora_termino' => $hora_termino,
                'descricao' => $conteudo ,
                'usuario' => $usuario
            );
           
            
          //  print_r($data_ata); exit;
          
            $this->atas_model->add_item_treinamento($data_ata);
            
            $this->session->set_flashdata('message', lang("Treinamento Registrado com Sucesso!!!"));
            redirect("Welcome/abrir_treinamento/$id_ata_facilitador");
            
        } else {

            redirect("Welcome/abrir_treinamento/$id_ata_facilitador");
            
           

            }
    }
    
    public function addParticipanteTreinamento()
    {
             
            $date_hoje = date('Y-m-d H:i:s');    
            $usuario = $this->session->userdata('user_id');  
     
            
            $id_ata_facilitador =  $this->input->post('id_ata_facilitador');
            $id_ata = $this->input->post('id_ata'); 
            $participante = $this->input->post('participante'); 
        
            
            
           if($participante != 0){
                $this->atas_model->addParticipanteAta($id_ata, $participante);
           }
      
            
           
          
    
           // exit;
            $this->session->set_flashdata('message', lang("Retorno da ação enviada com Sucesso!!!"));
            redirect("welcome/abrir_treinamento/$id_ata_facilitador");
          
            
        
    }
    
    public function deleteParticipanteTreinamento($participante, $id_ata_facilitador)
    {
             
            $date_hoje = date('Y-m-d H:i:s');    
            $usuario = $this->session->userdata('user_id');  
     
        
       
            
            
           if($participante != 0){
                $this->atas_model->deleteParticipanteAta($participante);
           }
      
            
           
          
    
           // exit;
          //  $this->session->set_flashdata('message', lang("Retorno da ação enviada com Sucesso!!!"));
            redirect("welcome/abrir_treinamento/$id_ata_facilitador");
          
            
        
    }
    
    /************************************************************************************
     ******************************** FIM TREINAMENTO ***********************************
     ************************************************************************************/
    
    public function retornoForm()
    {
     
             
            $date_hoje = date('Y-m-d H:i:s');    
            $usuario = $this->session->userdata('user_id');  
            $idplano = $this->input->post('id');
            $tipo = $this->input->post('tipo');
            $observacao = trim($this->input->post('observacao')); 
            $ip = $_SERVER["REMOTE_ADDR"];
            
            
            
            $data_acao = array(
             'data_retorno_usuario' => $date_hoje,
             'status' => 'AGUARDANDO VALIDAÇÃO'
            );
           
            $data_historicoAcao = array(
                'data_envio' => $date_hoje,
                'usuario' => $usuario,
                'plano' => $idplano,
                'observacao' => $tipo.' - '.$observacao,
                'status' => 'AGUARDANDO VALIDAÇÃO',
                'ip' => $ip
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
                $data_historicoAcao['anexo'] = $photo;
            }
            
            $data_UsuarioLog = array(
                'date' => $date_hoje,
                'type' => 'UPDATE',
                'description' => 'Envio de Retorno da Ação '.$idplano. '. Mudou a ação para o status AGUARDANDO VALIDAÇÃO',
                'userid' => $usuario,
                'depois' => 'Status = AGUARDANDO VALIDAÇÃO',
                'tabela' => 'PLANOS',
                'ip' => $ip
            );
          
            
            $this->atas_model->updatePlano($idplano, $data_acao);
            $this->atas_model->add_historicoPlanoAcao( $data_historicoAcao);
            $this->atas_model->add_log($data_UsuarioLog);
          
            
             
            
            //Email
            $this->ion_auth->retornoUsuario($idplano);
           // exit;
            $this->session->set_flashdata('message', lang("Retorno da ação enviada com Sucesso!!!"));
            redirect("welcome");
          
            
        
    }
    
    public function retornoFormNew()
    {
     
             
            $date_hoje = date('Y-m-d H:i:s');    
            $usuario = $this->session->userdata('user_id');  
            $idplano = $this->input->post('id');
            $tipo = $this->input->post('tipo');
            $observacao = trim($this->input->post('observacao')); 
            $ip = $_SERVER["REMOTE_ADDR"];
            
            
            
            $data_acao = array(
             'data_retorno_usuario' => $date_hoje,
             'status' => 'AGUARDANDO VALIDAÇÃO'
            );
           
            $data_historicoAcao = array(
                'data_envio' => $date_hoje,
                'usuario' => $usuario,
                'plano' => $idplano,
                'observacao' => $tipo.' - '.$observacao,
                'status' => 'AGUARDANDO VALIDAÇÃO',
                'ip' => $ip
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
                $data_historicoAcao['anexo'] = $photo;
            }
            
            $data_UsuarioLog = array(
                'date' => $date_hoje,
                'type' => 'UPDATE',
                'description' => 'Envio de Retorno da Ação '.$idplano. '. Mudou a ação para o status AGUARDANDO VALIDAÇÃO',
                'userid' => $usuario,
                'depois' => 'Status = AGUARDANDO VALIDAÇÃO',
                'tabela' => 'PLANOS',
                'ip' => $ip
            );
          
            
            $this->atas_model->updatePlano($idplano, $data_acao);
            $this->atas_model->add_historicoPlanoAcao( $data_historicoAcao);
            $this->atas_model->add_log($data_UsuarioLog);
          
            
             
            
            //Email
            $this->ion_auth->retornoUsuario($idplano);
           // exit;
            $this->session->set_flashdata('message', lang("Retorno da ação enviada com Sucesso!!!"));
            redirect("welcome");
          
            
        
    }
    
    public function email_retorno_acao($id = null)
    {
        
        $date_hoje = date('Y-m-d H:i:s');    
        $idplano = $this->input->post('id');
            
        $acao =  $this->atas_model->getPlanoByID($id);                    
        $usuario = $this->session->userdata('user_id');   
        $users = $this->site->geUserByID($usuario);   
        
        
            /*
             * ENVIAR EMAIL
             */
            $email = 'alice.cabral@unimedmanaus.com.br';
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
            $this->sma->send_email($email, $subject, $message);
            
             if ($this->sma->send_email($email, $subject, $message)) {
                    $this->set_message('Retorno Enviado com Sucesso!');
                   
                }
                
    }
    
    public function retorno_view($id = null)
    {
     
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        
                  
            $this->data['idplano'] = $id;
            $this->data['acoes'] = $this->atas_model->getAllAcoes($id);
            $this->load->view($this->theme . 'networking/acoes/retorno_view', $this->data);
         
            
         
    }
    

    
    
   
    /**************************************************************************
     ******************** T R E I N A M E N T O S *****************************
     **************************************************************************/
    
    
    public function adcionar_acao_treinamento($id = null, $id_ata_facilitador = null)
    {
     
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
            }
        
                        
            $date_hoje = date('Y-m-d H:i:s');    
            $usuario = $this->session->userdata('user_id');  
            $ip = $_SERVER["REMOTE_ADDR"];
            
            $descricao = $this->input->post('descricao') .'<br>';
          //  echo 'aqui'.$descricao;
           // exit;
           
            $usuario = $this->session->userdata('user_id');
            $projetos_usuario = $this->site->getProjetoAtualByID_completo($usuario);
           
            $this->data['eventos'] = $this->projetos_model->getAllEventosItemEventoByProjeto($projetos_usuario->projeto_atual);
            $this->data['users'] = $this->atas_model->getAllUsersSetores();
            $this->data['macro'] = $this->atas_model->getAllMacroProcesso();
            $this->data['projetos'] = $this->atas_model->getAllProjetos();      
            $this->data['ata'] = $id;
            $this->data['ata_facilitador'] = $id_ata_facilitador;
            $this->data['avulsa'] = 'NÃO';
            $this->data['acoes'] = $this->atas_model->getAllAcoesProjeto($projetos_usuario->projeto_atual);
            //$this->data['acoes'] = $this->atas_model->getPlanoByID($id);
            
            $participantes = $this->input->post('participantes');
     
            foreach ($participantes as $participante) {
               $participantes_usuario[] = $participante;
            }
            
            $this->data['participantes_usuarios'] = $participantes_usuario;
            //$this->data['participantes_lista'] = "$nomes_participantes";
            
            
            $bc = array(array('link' => base_url(), 'page' => lang('Plano de Ação')), array('link' => '#', 'page' => lang('Nova Ação')));
            $meta = array('page_title' => lang('Atas'), 'bc' => $bc);
            $this->page_construct('usuarios/adicionar_acao', $meta, $this->data);
            // $this->load->view($this->theme . 'Atas/adicionar_acao', $this->data);
        // $this->load->view($this->theme . 'usuarios/adicionar_acao', $this->data);
    }
    
    public function adcionar_acao_treinamento_form($id = null)
    {
     
        $id_ata_facilitador =  $this->input->post('id_ata_facilitador');
        $id_ata = $this->input->post('id');  
            $descricao = $this->input->post('descricao');
            //$dataEntrega = $this->sma->fld(trim($this->input->post('dateEntrega'))); 
            $dataEntrega = $this->sma->fld(trim($this->input->post('dateEntrega'))); //$this->input->post('dateEntrega');
            
            //$status = trim($this->input->post('status_plano')); 
            $date_cadastro = date('Y-m-d H:i:s');  
            $usuario = $this->session->userdata('user_id');
            $dataEntregaDemanda = $this->sma->fld(trim($this->input->post('dateEntregaDemanda'))); //$this->input->post('dateEntregaDemanda'); 
            $evento = $this->input->post('evento'); 
            $responsaveis = $this->input->post('responsavel'); 
            $acao_vinculo = $this->input->post('acoes_vinculo');
            $custo = trim($this->input->post('custo')); 
            $consultoria = trim($this->input->post('consultoria')); 
            $acaoconsultoria = trim($this->input->post('acaoconsultoria')); 
            $observacao = trim($this->input->post('observacao'));
            $avulsa = 'NÃO';
           
            
             foreach ($responsaveis as $responsavel) {
               
                 $dados_responsavel = $this->atas_model->getUserSetorBYid($responsavel);
                 $setor_responsavel = $dados_responsavel->setor;
                 $id_responsavel = $dados_responsavel->usuario;
              

             
            $data_plano = array(
                'idatas' => $id_ata,
                'descricao' => $descricao,
                'data_termino' => $dataEntrega,
                'responsavel' => $id_responsavel,
                'setor' => $setor_responsavel,  
                'status' => 'ABERTO',
                'data_elaboracao' => $date_cadastro,   
                'responsavel_elaboracao' => $usuario,
                'data_entrega_demanda' => $dataEntregaDemanda,
                'custo' => $custo,
                'consultoria' => $consultoria,
                'acaoconsultoria' => $acaoconsultoria,   
                'observacao' => $observacao,
                'eventos' => $evento
            );
           
           
            $this->atas_model->add_planoAcao($data_plano,$acao_vinculo,$avulsa,$id_responsavel);
            
            }

            
            
            
            $this->session->set_flashdata('message', lang("Ação Cadastrada com Sucesso!!!"));
             redirect("welcome/abrir_treinamento/$id_ata_facilitador");
    }
    
    
    public function informacoes_treinamento_form($id = null)
    {
     
        $id_ata_facilitador =  $this->input->post('id_ata_facilitador');
        $id_ata = $this->input->post('id_ata');  
        
            $anotacoes = $this->input->post('anotacoes');
            $testes = $this->input->post('presenca');
            $observacoes = $this->input->post('observacoes');
             
            $date_cadastro = date('Y-m-d H:i:s');  
            $usuario = $this->session->userdata('user_id');
          
           
            
            $participantes_cadastrados_ata = $this->atas_model->getAtaUserParticipante_ByID_ATA($id_ata);
            $cont = 0;
            foreach ($participantes_cadastrados_ata as $participante_cadastrados) {
               $id = $participante_cadastrados->id;
               
               
               $data_informacoes = array(
                'presenca_confirmada' => $testes[$cont],
                'observacao_facilitador' => $observacoes[$cont]
                );
               
               $this->atas_model->updateInformacoesParticipantesTreinamento($id, $data_informacoes);
               
              $cont++;
              
            
            }
            
            $data_informacoes_treinamento = array(
                'observacao' => $anotacoes
                );
            
            $this->atas_model->updateInformacoesFacilitadorTreinamento($usuario,$id_ata, $data_informacoes_treinamento);
          
            
                
            
            $this->session->set_flashdata('message', lang("Ação Cadastrada com Sucesso!!!"));
            redirect("welcome/abrir_treinamento/$id_ata_facilitador");
    }
    
    public function fechar_treinamento($id_ata_facilitador = null)
    {
     
       
       
        $id_ata = $id_ata_facilitador;  
        
      

        $date_cadastro = date('Y-m-d H:i:s');  
        $usuario = $this->session->userdata('user_id');
          
            

            
            $data_informacoes_treinamento = array(
                'status' => 1,
                'data_fechamento' => $date_cadastro
                );
            
            $this->atas_model->updateInformacoesFacilitadorTreinamentoByID($id_ata, $data_informacoes_treinamento);
          
            
                
            
           
            redirect("welcome/abrir_treinamento/$id_ata_facilitador");
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
            $this->load->view($this->theme . 'usuarios/acaoPendente', $this->data);
      
    }
    
    public function manutencao_acao_form($id = null)
    {
            
            
            $date_hoje = date('Y-m-d H:i:s');    
            $usuario = $this->session->userdata('user_id');  
            $ip = $_SERVER["REMOTE_ADDR"];
            
            $idplano = $this->input->post('id');
            $porque = $this->input->post('porque');
            $onde = trim($this->input->post('onde')); 
            $como = trim($this->input->post('como')); 
            $observacao = trim($this->input->post('observacao')); 
            $macroprocesso = trim($this->input->post('macroprocesso')); 
            
            $data_acao = array(
                'porque' => $porque,
                'onde' => $onde,
                'como' => $como,
                
                'macroprocesso' => $macroprocesso
            );
            
          // print_r($data_acao); exit;
                       
            $this->atas_model->updatePlano($idplano, $data_acao);
            
            $this->session->set_flashdata('message', lang("Ação Atualizada com Sucesso!!!"));
            redirect("welcome");
         }
            
    public function manutencao_acao_vinculo($id = null)
    {
     
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
            }
        
            
            $this->data['users'] = $this->site->getAllUser();
            $this->data['macro'] = $this->atas_model->getAllMacroProcesso();
            $this->data['projetos'] = $this->atas_model->getAllProjetos();      
            $this->data['idplano'] = $id;
            $this->data['acoes'] = $this->atas_model->getPlanoByID($id);
            $this->load->view($this->theme . 'usuarios/acaoVinculo', $this->data);
         
    }
    
   
     
    
     public function pdf($id = null, $view = null)
    {
          
       // $this->sma->checkPermissions();

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        $usuario = $this->session->userdata('username');
        
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
     
       
       
         //ECHO 'TO AQUI'; exit;
            $this->data['id'] = $id;
           
            $this->data['ata'] = $this->site->getAtaProjetoByID_ATA($id);
            
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['users'] = $this->site->getAllUser();
            $this->data['projetos'] = $this->site->getAllProjetos();
            $this->data['planos'] = $this->site->getAllitemPlanos($id);

            $name = lang("ATA") . "_" . str_replace('/', '_', $id) . ".pdf";
            $html = $this->load->view($this->theme . 'usuarios/pdf', $this->data, true);

        if ($view) {
            $this->load->view($this->theme . 'usuarios/pdf', $this->data);
        } else{
            $this->sma->generate_pdf($html, $name, false, 'Usuário:'.$usuario);
        }
    }
    
    /*
     * PESQUISA DE SATISFAÇÃO
     */
    
    function encrypt($str, $key)
    {

        for ($return = $str, $x = 0, $y = 0; $x < strlen($return); $x++)
        {
            $return{$x} = chr(ord($return{$x}) ^ ord($key{$y}));
            $y = ($y >= (strlen($key) - 1)) ? 0 : ++$y;
        }

        return $return;
    }
    
        
        
     public function pesquisa_reacao($id = null)
    {
       
        
       $id_descriptografado_participante = $id;// $this->encrypt($id,'PRATA');
       
      
       
      
        
         if($id){
             
          
        
         $participantes = $this->atas_model->participante_treinamento_ataByid($id_descriptografado_participante);
         $id_ata = $participantes->id_ata;
         $id_participante_usuario = $participantes->id_participante;
         $status_avaliacao = $participantes->avaliacao;
        
       
       
         $dados_ata = $this->atas_model->getAtaSemProjetoByID($id_ata);
         $tipo = $dados_ata->tipo;
         $tipo_ava_reacao = $dados_ata->avaliacao_reacao;
           
         
             if($tipo == 'TREINAMENTO'){
                 
                 if($status_avaliacao == 1){
                      ?>
               <script>
                   alert('AVALIAÇÃO JÁ PREENCHIDA!');
                    window.close();
                </script>
        <?php    
                     
                 }else{
                  
                   
                      
                 $this->data['id_usuario'] =  $id_participante_usuario;
                 $this->data['id_ata'] =  $id_ata;
                 $this->data['id_participante'] =  $id_descriptografado_participante;
                 
                 $this->data['pesquisa'] = $this->atas_model->getPesquisaByID($tipo_ava_reacao);
                 $this->data['grupo_perguntas'] = $this->atas_model->getGrupoByIDPesquisa($tipo_ava_reacao);
                 //$this->page_construct('usuarios/ver_pesquisa_reacao', $meta, $this->data);
                  $this->load->view($this->theme . 'usuarios/ver_pesquisa_reacao', $this->data);
                 }

             }
         
         
           
            }else{
                echo 'O Administrador do sistema foi informado, sobre esta ação;';
            }
            
      
        
    } 
    
    
    public function informacoes_avaliacao_form()
    {
     
        $id_participante =  $this->input->post('id_participante');
     
        $id_ata =  $this->input->post('id_ata');
       
        $observacao_sugestao = $this->input->post('observacao_sugestao');
       
        
        $date_cadastro = date('Y-m-d H:i:s');  
        $dados_ata = $this->atas_model->getAtaSemProjetoByID($id_ata);
        $tipo_ava_reacao = $dados_ata->avaliacao_reacao;
        
         $grupo_perguntas = $this->atas_model->getGrupoByIDPesquisa($tipo_ava_reacao);
         foreach ($grupo_perguntas as $grupo) {
                 
                  // echo $grupo->nome.'<br>';
                   
                   $perguntas = $this->atas_model->getAllPerguntasByGrupo($grupo->id);
                    foreach ($perguntas as $pergunta) {
                        
                        $id_resposta_pergunta_formulario =  $this->input->post($pergunta->id);
                        //  echo $pergunta->id.' - ';
                      
                        
                        $dados_respostas_avaliacao = array(
                            'pergunta' => $pergunta->id,
                            'resposta' => $id_resposta_pergunta_formulario,
                            'participante' => $id_participante,
                            'data_cadastro' => $date_cadastro,
                            'ip' => $_SERVER['REMOTE_ADDR']
                        );
                       
                        $this->atas_model->add_resposta_usuario($dados_respostas_avaliacao);
                    }
             
         }
        
            
             $data_sugestao = array(
                'sugestao' => $observacao_sugestao,
                'avaliacao' => 1
            );
            
          // print_r($data_acao); exit;
                       
            $this->atas_model->updateInformacoesParticipante($id_participante, $data_sugestao);
            
          
            
                
           ?>
               <script>
                   alert('AVALIAÇÃO ENVIADA COM SUCESSO!');
                    window.close();
                </script>
        <?php    
    }
   
    
    
    
     public function home() {
       // $this->sma->checkPermissions();
        
        if ($this->Settings->version == '2.3') {
            $this->session->set_flashdata('warning', 'Please complete your update by synchronizing your database.');
            redirect('sync');
        }

        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
      
        $mes = date('m');
        $ano = date('Y');
        
       
        
        $this->data['pagina'] = 'usuarios/new/home';
        $this->data['ativo'] = 'home';
        $this->data['menu'] = 'home'; //footer
        $this->data['footer'] = 'footer_acuracidade';
        $this->data['home'] = '1';
        
       // $this->data['exibe_rodape'] = 'nao';
       
         $this->page_construct_user('usuarios/new/home', $meta, $this->data);
       // $this->load->view($this->theme . 'usuarios/new/main', $this->data);
    }
    
   
    
    public function controleAtividades() {
       // $this->sma->checkPermissions();
        
        if ($this->Settings->version == '2.3') {
            $this->session->set_flashdata('warning', 'Please complete your update by synchronizing your database.');
            redirect('sync');
        }

        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
      
        $mes = date('m');
        $ano = date('Y');
        
       
        
        $this->data['pagina'] = 'usuarios/new/index';
        $this->data['ativo'] = 'acuracidade';
        $this->data['menu'] = 'estoque'; //footer
        $this->data['footer'] = 'footer_acuracidade';
        
       
        
        $this->load->view($this->theme . 'usuarios/new/main', $this->data);
    }
    
     public function acoesConcluidas()
    {
        if ($this->Settings->version == '2.3') {
            $this->session->set_flashdata('warning', 'Please complete your update by synchronizing your database.');
            redirect('sync');
        }

        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        
        $usuario = $this->session->userdata('user_id');                     
        $this->data['planos'] = $this->atas_model->getAllPlanosConcluidoUser($usuario);
        
        $users = $this->site->geUserByID($usuario);
        $bc = array(array('link' => '#', 'page' => lang('NOME : '.$users->first_name .' '. $users->last_name .' | SETOR : '.$users->company )));
       
        $meta = array('page_title' => lang('Ações'), 'bc' => $bc);
        //$this->page_construct('usuarios/acoesConcluidas', $meta, $this->data);
        
         $this->data['pagina'] = 'usuarios/new/acoesConcluidas';
        $this->data['ativo'] = 'concluidas';
        $this->data['menu'] = 'acoes'; //footer
        $this->data['footer'] = 'footer_acuracidade';
        
       
        
        $this->load->view($this->theme . 'usuarios/new/main', $this->data);
        
        //$this->load->view($this->theme . 'usuarios/new/acoesConcluidas', $this->data);

    }
    
   
     
    
    /**********************************************************************
     *************************** REQUISIÇÃO DE HORAS **********************
     **********************************************************************/ 
    public function requisicaoHoras()
    {
        if ($this->Settings->version == '2.3') {
            $this->session->set_flashdata('warning', 'Please complete your update by synchronizing your database.');
            redirect('sync');
        }

        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        
        $usuario = $this->session->userdata('user_id');                     
        $this->data['periodos'] = $this->user_model->getPeriodoHEByUsuario($usuario);
        
         
        $this->data['pagina'] = 'usuarios/new/requisicaoHoras';
        $this->data['ativo'] = 'requisicao';
        $this->data['menu'] = 'requisicao'; 
        $this->data['footer'] = 'footer';
         
        $this->load->view($this->theme . 'usuarios/new/main', $this->data);
        
        //$this->load->view($this->theme . 'usuarios/new/acoesConcluidas', $this->data);

    }
    
    public function requisicaoHorasDetalhes($id_periodo)
    {
        if ($this->Settings->version == '2.3') {
            $this->session->set_flashdata('warning', 'Please complete your update by synchronizing your database.');
            redirect('sync');
        }

        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        
        $resultado = str_replace('_', '=' , base64_decode($id_periodo));
        $id = substr($resultado,0, -18);
        $chave = substr($resultado, -18);
        
       
        $id_descriptografado = $id;
        
        $usuario = $this->session->userdata('user_id');                     
     //   $this->data['competencia'] = $this->user_model->getPeriodoHEById($id_descriptografado);
     //   $this->data['lacamentos'] = $this->user_model->getDetalhesPeriodoHEByIdPeriodo($id_descriptografado);
         
        $this->data['pagina'] = 'usuarios/new/requisicaoHoras_detalhes_teste';
        $this->data['ativo'] = 'requisicao';
        $this->data['menu'] = 'requisicao'; 
       // $this->data['footer'] = 'footer';
        $this->data['id_periodo'] = $id_descriptografado;
        
      
        //$this->page_construct('usuarios/new/requisicaoHoras_detalhes_teste', $meta, $this->data);
        //$this->load->view($this->theme . 'usuarios/new/main', $this->data);
        $this->page_construct_user('usuarios/new/requisicaoHoras_detalhes_novo', $meta, $this->data);
        //$this->load->view($this->theme . 'usuarios/new/acoesConcluidas', $this->data);

    }
    
    public function verAcaoRequisicaoHoras($id_periodo)
    {
        $date_cadastro = date('Y-m-d H:i:s');                           
        
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
       
       
        $this->data['id'] = $id_periodo;   
        //    $id_descriptografado =  $this->encrypt($id_registro,'UNIMED');
        $usuario = $this->session->userdata('user_id');                     
        
        //$this->load->view($this->theme . 'projetos/documentacao/add', $this->data);
        $this->load->view($this->theme . 'usuarios/new/verAcaoPonto', $this->data);
           

            
    }
    
    public function editarRequisicaoHorasDetalhes($id_periodo)
    {
        $date_cadastro = date('Y-m-d H:i:s');                           
        
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
       
         $this->form_validation->set_rules('title', lang("title"), 'trim|required');
        // $this->form_validation->set_rules('acoes_vinculo', lang("Ação"), 'required');
        $this->form_validation->set_rules('hora_extra', lang("hora_extra"), 'required');

        
        if ($this->form_validation->run() == true) {
        
            
           $id = $this->input->post('id');
           $id_periodo = $this->input->post('periodo');
           $id_criptografado =  str_replace('=', '_' , base64_encode($id_periodo.'5M4N46543213321877'));
           
           
           $acoes = $this->input->post('acoes_vinculo');
           
            $data = array(
                'descricao' => $this->input->post('title'),
                'hora_extra' => $this->input->post('hora_extra'),
                'data_registro' => $date_cadastro
                );
            
            $this->user_model->updateRegistrosHE($id, $data);
            $this->user_model->deleteAcoesRegistrosHE($id, $acoes);
           
            redirect("welcome/requisicaoHorasDetalhes/".$id_criptografado);
           
            
        } else {
       
            $this->data['id'] = $id_periodo;   
        //    $id_descriptografado =  $this->encrypt($id_registro,'UNIMED');
        $usuario = $this->session->userdata('user_id');                     
        $this->data['pagina'] = 'usuarios/new/edit_requisica_hora';
        
        //$this->load->view($this->theme . 'projetos/documentacao/add', $this->data);
        $this->load->view($this->theme . 'usuarios/new/edit_requisica_hora', $this->data);
           

            }
    }
    
    public function editarRequisicaoHorasDetalhes_nova($id_periodo)
    {
        $date_cadastro = date('Y-m-d H:i:s');                           
        
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
       
         $this->form_validation->set_rules('title', lang("title"), 'trim|required');
         $this->form_validation->set_rules('acoes_vinculo', lang("Ação"), 'required');
        $this->form_validation->set_rules('hora_extra', lang("hora_extra"), 'required');

        if ($this->form_validation->run() == true) {
           
           $id = $this->input->post('id');
           $id_periodo = $this->input->post('periodo');
           $id_criptografado =  str_replace('=', '_' , base64_encode($id_periodo.'5M4N46543213321877'));
           
            $tipo = $this->input->post('tipo');
           if($tipo == 'Crédito'){
            $tipo_acao = $this->input->post('acoes_vinculo');
           }else{
               $tipo_acao = $this->input->post('acoes_vinculo_debito');
           }
            $data = array(
                'descricao' => $this->input->post('title'),
                'id_acao' => $tipo_acao,
                'hora_extra' => $this->input->post('hora_extra'),
                'data_registro' => $date_cadastro
                );
            
           //print_r($data);exit;
            /*
             * VALIDA SE A REQUISIÇÃO É DO USUÁRIO LOGADO
             */
            
            
            $this->user_model->updateRegistrosHE($id, $data);
           
            redirect("welcome/requisicaoHorasDetalhes/".$id_criptografado);
           
            
        } else {
       
            $this->data['id'] = $id_periodo;   
        //    $id_descriptografado =  $this->encrypt($id_registro,'UNIMED');
        $usuario = $this->session->userdata('user_id');                     
        $this->data['pagina'] = 'usuarios/new/edit_requisica_hora';
        
        //$this->load->view($this->theme . 'projetos/documentacao/add', $this->data);
        $this->load->view($this->theme . 'usuarios/new/edit_requisica_hora_editar', $this->data);
           

            }
    }
        
    public function novaRequisicaoHorasDetalhes($id_periodo)
    {
        $date_cadastro = date('Y-m-d H:i:s');                           
        
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
       
         $this->form_validation->set_rules('title', lang("title"), 'trim|required');
         $this->form_validation->set_rules('acoes_vinculo', lang("Ação"), 'required');
         $this->form_validation->set_rules('start', lang("start"), 'required');
         $this->form_validation->set_rules('end', lang("end"), 'required');
         $this->form_validation->set_rules('tipo', lang("tipo"), 'required');
        $this->form_validation->set_rules('hora_extra', lang("hora_extra"), 'required');

        if ($this->form_validation->run() == true) {
           
           $id = $this->input->post('id');
           $id_periodo = $this->input->post('periodo');
           $id_criptografado =  str_replace('=', '_' , base64_encode($id_periodo.'5M4N46543213321877'));
           
           $tipo = $this->input->post('tipo');
           if($tipo == 'Crédito'){
            $tipo_acao = $this->input->post('acoes_vinculo');
           }else{
               $tipo_acao = $this->input->post('acoes_vinculo_debito');
           }
           //echo $tipo.$tipo_acao; exit;
            $data = array(
                'id_periodo' => $id_periodo,
                'dia' => $this->input->post('dia'),
                'mes' => $this->input->post('mes'),
                'descricao' => $this->input->post('title'),
                'hora_inicio' => $this->input->post('start'),
                'hora_fim_confirmado' => $this->input->post('end'),
                'id_acao' => $tipo_acao,
                'tipo_registro' => $this->input->post('tipo'),
                'hora_extra' => $this->input->post('hora_extra'),
                'data_registro' => $date_cadastro
                );
            
            /*
             * VALIDA SE A REQUISIÇÃO É DO USUÁRIO LOGADO
             */
            
            
            $this->user_model->addRegistrosHE($data);
           
            redirect("welcome/requisicaoHorasDetalhes/".$id_criptografado);
           
            
        } else {
       
        $this->data['id'] = $id_periodo;   
        
        //$this->load->view($this->theme . 'projetos/documentacao/add', $this->data);
        $this->load->view($this->theme . 'usuarios/new/edit_requisica_hora_nova', $this->data);
           

            }
    }
    
    public function cancelarRequisicaoHorasDetalhes($id)
    {
        $date_cadastro = date('Y-m-d H:i:s');                           
        
       $data_periodo = $this->user_model->getPeriodo_detalhesHEById($id);
       $id_periodo = $data_periodo->id_periodo;

          
           $id_criptografado =  str_replace('=', '_' , base64_encode($id_periodo.'5M4N46543213321877'));
           
            $data = array(
                'descricao' => "",
                'hora_inicio' => "",
                'hora_fim_confirmado' => "",
                'id_acao' => "",
                'tipo_registro' => "",
                'hora_extra' => "",
                'data_registro' => $date_cadastro
                );
            
           
            /*
             * VALIDA SE A REQUISIÇÃO É DO USUÁRIO LOGADO
             */
            
            
            $this->user_model->updateRegistrosHE($id, $data);
           
            redirect("welcome/requisicaoHorasDetalhes/".$id_criptografado);
           
            
         
    }
    
    public function apagarRequisicaoHorasDetalhes($id)
    {
        $date_cadastro = date('Y-m-d H:i:s');                           
        
       $data_periodo = $this->user_model->getPeriodo_detalhesHEById($id);
       $id_periodo = $data_periodo->id_periodo;

          
           $id_criptografado =  str_replace('=', '_' , base64_encode($id_periodo.'5M4N46543213321877'));
              /*
             * VALIDA SE A REQUISIÇÃO É DO USUÁRIO LOGADO
             */
            
            
            $this->user_model->deleteRegistrosHE($id);
           
            redirect("welcome/requisicaoHorasDetalhes/".$id_criptografado);
           
            
         
    }
    
   /************************************************************
    ****************FIM REQUISIÇÃO DE HORAS *******************
    ************************************************************/ 
    
    
    
    
   
    
    
    /*
     * ARQVUIVOS
     * EM: 24/09/2018
     * ISRAEL ARAUJO
     */
     public function arquivosProjeto()
    {
        if ($this->Settings->version == '2.3') {
            $this->session->set_flashdata('warning', 'Please complete your update by synchronizing your database.');
            redirect('sync');
        }

        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        
        $usuario = $this->session->userdata('user_id');                     
        $this->data['periodos'] = $this->user_model->getPeriodoHEByUsuario($usuario);
        
         
        $this->data['pagina'] = 'usuarios/new/arquivos';
        $this->data['ativo'] = 'requisicao';
        $this->data['menu'] = 'requisicao'; 
        $this->data['footer'] = 'footer';
         
        $this->load->view($this->theme . 'usuarios/new/main', $this->data);
        
        //$this->load->view($this->theme . 'usuarios/new/acoesConcluidas', $this->data);

    }
    
    /*
     * DOCUMENTOS
     * EM: 24/09/2018
     * ISRAEL ARAUJO
     */
     public function documentosProjeto()
    {
        if ($this->Settings->version == '2.3') {
            $this->session->set_flashdata('warning', 'Please complete your update by synchronizing your database.');
            redirect('sync');
        }

        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        
        $usuario = $this->session->userdata('user_id');                     
        $this->data['periodos'] = $this->user_model->getPeriodoHEByUsuario($usuario);
        
         
        $this->data['pagina'] = 'usuarios/new/documentos';
        $this->data['ativo'] = 'requisicao';
        $this->data['menu'] = 'requisicao'; 
        $this->data['footer'] = 'footer';
         
        $this->load->view($this->theme . 'usuarios/new/main', $this->data);
        
        //$this->load->view($this->theme . 'usuarios/new/acoesConcluidas', $this->data);

    }
    
    /*
     * MÓDULOS
     */
     public function modulos($empresa)
    {
        if ($this->Settings->version == '2.3') {
            $this->session->set_flashdata('warning', 'Please complete your update by synchronizing your database.');
            redirect('sync');
        }

        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        
        $usuario = $this->session->userdata('user_id');                     
     //   $this->data['periodos'] = $this->user_model->getPeriodoHEByUsuario($usuario);
        
         
        $this->data['pagina'] = 'networking/modulos';
        $this->data['ativo'] = 'requisicao';
        $this->data['menu'] = 'requisicao'; 
        $this->data['footer'] = 'footer';
         
       // $this->load->view($this->theme . 'usuarios/new/main', $this->data);
         $this->page_construct_networking('networking/modulos', $meta, $this->data);
        //$this->load->view($this->theme . 'usuarios/new/acoesConcluidas', $this->data);

    }
    
}
