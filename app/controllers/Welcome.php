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
        $this->load->model('site');
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
       
       
        
        
        $users = $this->site->geUserByID($usuario);
        $bc = array(array('link' => '#', 'page' => lang('NOME : '.$users->first_name .' '. $users->last_name .' | SETOR : '.$users->company)));
        $meta = array('page_title' => lang('Ações'), 'bc' => $bc);
        //$this->page_construct('usuarios/index', $meta, $this->data);
       
        redirect("welcome/home");
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
    
    public function manutencao_acao_disable($id = null)
    {
     
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
            }
        
            
            $this->data['users'] = $this->site->getAllUser();
            $this->data['macro'] = $this->atas_model->getAllMacroProcesso();
            $this->data['projetos'] = $this->atas_model->getAllProjetos();      
            $this->data['idplano'] = $id;
            $this->data['acoes'] = $this->atas_model->getPlanoByID($id);
            $this->load->view($this->theme . 'usuarios/acaoConcluida', $this->data);
         
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
            $this->load->view($this->theme . 'usuarios/retorno_view', $this->data);
         
            
         
    }
    
    public function getAcoes()
    {
        $this->sma->checkPermissions();
        echo 'to aqui';exit;
        
        
       // $edit_link = anchor('planos/edit/$1', '<i class="fa fa-edit"></i> ' . lang('Editar Plano'), 'data-toggle="modal" data-target="#myModal"');
        $edit_link = anchor('planos/edit/$1', '<i class="fa fa-edit"></i> ' . lang('Editar Plano de Ação'), 'class="sledit"');
      
        $action = '<div class="text-center"><div class="btn-group text-left">'
        . '<button type="button" class="btn btn-default btn-xs btn-primary dropdown-toggle" data-toggle="dropdown">'
        . lang('actions') . ' <span class="caret"></span></button>
        <ul class="dropdown-menu pull-right" role="menu">
            <li>' . $edit_link . '</li>           
            <li>' . $delete_link . '</li>
        </ul>
    </div></div>';
        $action = '<div class="text-center">'  . $edit_link . '</div>';
        
        
        
        $usuario = $this->session->userdata('user_id');
        //$projetos_usuario = $this->site->getProjetoAtualByID_completo($usuario);
        //$projetos_usuario->projeto_atual;
           
        $this->load->library('datatables');
            $this->datatables
                ->select("idplanos as id, idatas, descricao,  data_elaboracao, data_termino, status")
                ->from('planos')
                ->where('responsavel =', $usuario);
            $this->db->order_by('idplanos', 'desc');
         
            
        $this->datatables->add_column("Actions", $action, "id");
        echo $this->datatables->generate();
    }

   
    
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
    
    public function adcionar_acao($id = null, $id_ata_facilitador = null)
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
    
    public function adcionar_acao_form($id = null)
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
       // $this->page_construct('auth/profile', $meta, $this->data);
        $this->load->view($this->theme . 'auth/profile', $this->data);
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
        
       
       
         $dados_ata = $this->atas_model->getAtaByID($id_ata);
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
        $dados_ata = $this->atas_model->getAtaByID($id_ata);
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
   
    
        public function lista_rat()
    {
        if ($this->Settings->version == '2.3') {
            $this->session->set_flashdata('warning', 'Please complete your update by synchronizing your database.');
            redirect('sync');
        }

        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        $usuario = $this->session->userdata('user_id');                     
        $this->data['equipes'] = $this->atas_model->getMebrosEquipeByUsuario($usuario);
        
        $users = $this->site->geUserByID($usuario);
        
        
        //$this->page_construct('usuarios/index', $meta, $this->data);
       
        $this->load->view($this->theme . 'usuarios/rat/lista', $this->data);
    }
    
    
      function dateDiff( $dateStart, $dateEnd, $format = '%a' ) {

        $d1     =   new DateTime( $dateStart );

        $d2     =   new DateTime( $dateEnd );

        //Calcula a diferença entre as datas
        $diff   =   $d1->diff($d2, true);   

        //Formata no padrão esperado e retorna
        return $diff->format( $format );

    }
    
     public function abrir_rat($id = null)
    {
         
        
         
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
             //   'tipo_hora' => $tipo ,
                'tempo' => $usuario
            );
           
            
          //  print_r($data_ata); exit;
          
            $this->atas_model->add_rat($data_rat, $funcoes, $itens);
            
            $this->session->set_flashdata('message', lang("RAT Registrado com Sucesso!!!"));
            redirect("Welcome/abrir_rat/$id_mebro");
            
        } else {
     
           $date_cadastro = date('Y-m-d H:i:s');       
           // $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            
          
           
            $this->data['id'] = $id;
            $this->data['dados_equipe'] = $this->atas_model->getMebrosEquipeByIdEquipe($id); //
            
            $projeto_membro = $this->atas_model->getMebrosEquipeByIdMembro($id);
            $projeto_selecionado = $projeto_membro->projeto;
          
             $this->data['projeto'] = $projeto_selecionado;
            
           
            $this->load->view($this->theme . 'usuarios/rat/edit_rat', $this->data);
        }

            
    }
    
    public function deleteRat($rat = null, $id_membro = null)
    {
     
                  
            $this->atas_model->deleteRat($rat);
          
           
            redirect("welcome/abrir_rat/$id_membro");
    }
    
    
     public function update_rat($id = null,  $rat = null)
    {
         
        
         
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
            $tempo = gmdate('H:i:s', strtotime( $hora_termino) - strtotime( $hora_inicio  ) );
            
         
            
            $funcoes = $this->input->post('funcao');
            $itens = $this->input->post('eventos_item');
            
            $rat_id = $this->input->post('rat');
            
            $data_rat = array(
                'equipe' => $id_mebro,
                'data_registro' => $data_criacao,
                'hora_inicio' => $hora_inicio,
                'hora_fim' => $hora_termino,
                'descricao' => $conteudo,
                'data_rat' => $data_rat,
                'tempo' => $usuario
            );
           
            
          //  print_r($data_ata); exit;
          
            $this->atas_model->updateRat($rat_id, $data_rat, $funcoes, $itens);
            
            $this->session->set_flashdata('message', lang("RAT Registrado com Sucesso!!!"));
            redirect("Welcome/abrir_rat/$id_mebro");
            
        } else {
     
            $date_cadastro = date('Y-m-d H:i:s');       
            // $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            
           
            $this->data['id'] = $id;
            $this->data['dados_equipe'] = $this->atas_model->getMebrosEquipeByIdEquipe($id); //
            $this->data['rat'] = $rat;
            $projeto_membro = $this->atas_model->getMebrosEquipeByIdMembro($id);
            $projeto_selecionado = $projeto_membro->projeto;
            $this->data['projeto'] = $projeto_selecionado;
            $this->data['dados_rat'] = $this->atas_model->getRatById($rat); 
           
            $this->data['modulosRat'] = $this->atas_model->getModulosRatById($rat);
            $this->data['eventosRat'] = $this->atas_model->getEventosRatById($rat);
            
            $this->load->view($this->theme . 'usuarios/rat/update_rat', $this->data);
        }

            
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
    
     public function abrir_rat_pdf($view = 1)
    {
         
        
         
        $this->form_validation->set_rules('data_inicio', lang("Informe a Data de Início"), 'required');
        $this->form_validation->set_rules('data_fim', lang("Informe a Data de Término"), 'required');
       // $this->form_validation->set_rules('conteudo', lang("Informe o conteúdo"), 'required');
        
        $date_cadastro = date('Y-m-d H:i:s');       
     
        
          if ($this->form_validation->run() == true) {
           
            $data_rat = $this->input->post('data_registro'); 
            $hora_inicio = $this->input->post('hora_inicio');
            
          
            $usuario = $this->session->userdata('user_id');
           
             $date_cadastro = date('Y-m-d H:i:s');       
           // $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            
          
           
           // $this->data['id'] = $id;
            $this->data['dados_equipe'] = $this->atas_model->getMebrosEquipeByIdUsuario($usuario); //
            
            //$projeto_membro = $this->atas_model->getMebrosEquipeByIdMembro($id);
            //$projeto_selecionado = $projeto_membro->projeto;
          
             //$this->data['projeto'] = $projeto_selecionado;
             $data_inicio = $this->input->post('data_inicio'); 
          $data_fim = $this->input->post('data_fim');
          
          if($data_inicio){
            $this->data['data_inicio'] = $data_inicio;
              
          }
          
          if($data_fim){
              $this->data['data_fim'] = $data_fim;
              
          }
             
              $name = lang('Registro_atividades') . "_.pdf";
            $html = $this->load->view($this->theme . 'usuarios/rat/edit_rat_pdf', $this->data, true);
           
             // $dados_projeto = $this->projetos_model->getProjetoByID($projeto_doc);
            $logo_doc_top =  'cabecalho_unimed';//$dados_projeto->logo_doc_top;
           // $logo_doc_bottom =  $dados_projeto->logo_doc_bottom;
            
        if ($view) {
           $this->load->view($this->theme . 'usuarios/rat/edit_rat_pdf', $this->data);
        } else{
        
          
           // $documentacao = $this->projetos_model->getDocumentacaoByID($id);
            $usuario = $this->session->userdata('user_id');
            $res_assinar = $this->site->geUserByID($usuario);
            $nome_emitiu = $res_assinar->first_name.' '.$res_assinar->last_name;
            
            $this->sma->generate_pdf($html, $name, false, null, null, null, null, null, $logo_doc_top, null,$nome_emitiu,null);
        }
           
            
        }

            
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
    
    public function acoesPendentes()
    {
        if ($this->Settings->version == '2.3') {
            $this->session->set_flashdata('warning', 'Please complete your update by synchronizing your database.');
            redirect('sync');
        }

        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        
        $usuario = $this->session->userdata('user_id');                     
          $this->data['planos'] = $this->atas_model->getAllPlanosUser($usuario);
        
        $users = $this->site->geUserByID($usuario);
        $bc = array(array('link' => '#', 'page' => lang('NOME : '.$users->first_name .' '. $users->last_name .' | SETOR : '.$users->company )));
       
        $meta = array('page_title' => lang('Ações'), 'bc' => $bc);
        //$this->page_construct('usuarios/acoesConcluidas', $meta, $this->data);
        
         $this->data['pagina'] = 'usuarios/new/acoesPendentes';
        $this->data['ativo'] = 'pendentes';
        $this->data['menu'] = 'acoes'; //footer
        $this->data['footer'] = 'footer_acuracidade';
        
       
        
        $this->load->view($this->theme . 'usuarios/new/main', $this->data);
        
        //$this->load->view($this->theme . 'usuarios/new/acoesConcluidas', $this->data);

    }
    
        public function manutencao_acao_new($id = null)
    {
     
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
            }
        
                      
            $this->data['macro'] = $this->atas_model->getAllMacroProcesso();
            $this->data['projetos'] = $this->atas_model->getAllProjetos();      
            $this->data['idplano'] = $id;
            $this->data['acoes'] = $this->atas_model->getPlanoByID($id);
            $this->load->view($this->theme . 'usuarios/new/acaoPendente', $this->data);
      
    }
    
    public function editarAcao($acao) {
       // $this->sma->checkPermissions();
        
        if ($this->Settings->version == '2.3') {
            $this->session->set_flashdata('warning', 'Please complete your update by synchronizing your database.');
            redirect('sync');
        }

        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
      
        $mes = date('m');
        $ano = date('Y');
        
       
        
        $this->data['pagina'] = 'usuarios/new/acaoPendente';
        $this->data['ativo'] = 'acuracidade';
        $this->data['menu'] = 'estoque'; //footer
        $this->data['acao'] = $acao; //footer
        $this->data['footer'] = 'footer_acuracidade';
        
       //
        
        $this->load->view($this->theme . 'usuarios/new/main', $this->data);
    }
    
    public function retorno_new($id = null)
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
            $this->load->view($this->theme . 'usuarios/new/acaoRetorno', $this->data);
         
            
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
        $this->data['competencia'] = $this->user_model->getPeriodoHEById($id_descriptografado);
        $this->data['lacamentos'] = $this->user_model->getDetalhesPeriodoHEByIdPeriodo($id_descriptografado);
         
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
    
    public function visualizaImagemHome($id)
    {
        $date_cadastro = date('Y-m-d H:i:s');                           
        
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
       
        
       
        $this->data['id'] = $id;   
        
        //$this->load->view($this->theme . 'projetos/documentacao/add', $this->data);
        $this->load->view($this->theme . 'usuarios/new/visualizar_img_home', $this->data);
           

            
    }
    
    
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
    
}
