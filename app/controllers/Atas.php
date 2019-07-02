<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Atas extends MY_Controller
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
        $this->load->model('projetos_model');
        $this->load->model('networking_model');
        $this->load->model('owner_model');
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
        
        $usuario = $this->session->userdata('user_id');   
        $users = $this->site->geUserByID($usuario);
        /*
         * ATUALIZA SE A ATA TA OU NÃO COM PENDÊNCIAS
         */
        $atas  = $this->atas_model->getAllAtas();
        
        
          
             foreach ($atas as $ata) {
             $planos  = $this->atas_model->getStatusAllPlanosBYAta($ata->id);
               if($planos->status_pendencias > 0){
                   $pendencias = 'COM PENDENCIAS';
               }  else{
                   $pendencias = 'SEM PENDENCIAS';
               }
               
                $data_pendencias = array(
              'pendencias' => $pendencias
            );
              $this->atas_model->updatePendenciasAllAta($ata->id, $data_pendencias);
            }
        
        $bc = array(array('link' => '#', 'page' => lang('NOME : '.$users->first_name .' '. $users->last_name .' | SETOR : '.$users->company )));
       
        $meta = array('page_title' => lang('Atas'), 'bc' => $bc);
        $this->page_construct('project/sig/Atas/index', $meta, $this->data);

    }
    
     public function AtasResumido()
    {
     $this->sma->checkPermissions();
     
        $this->data['atas'] = $this->atas_model->getAllAtasResumido();
        
        $usuario = $this->session->userdata('user_id');   
        $users = $this->site->geUserByID($usuario);
        $bc = array(array('link' => '#', 'page' => lang('NOME : '.$users->first_name .' '. $users->last_name .' | SETOR : '.$users->company )));
   
        $meta = array('page_title' => lang('Atas'), 'bc' => $bc);
        $this->page_construct('Atas/resumido', $meta, $this->data);
        
    }
    
     public function manutencao_acao_av($id = null)
    {
     
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
            }
            $usuario = $this->session->userdata('user_id');
            $projetos_usuario = $this->site->getProjetoAtualByID_completo($usuario);
            
             $this->data['users'] = $this->site->getAllUser();
            $this->data['macro'] = $this->atas_model->getAllMacroProcesso();
            $this->data['projetos'] = $this->atas_model->getAllProjetos();      
            $this->data['idplano'] = $id;
            //$this->data['acoes'] = $this->atas_model->getPlanoByID($id);
            $this->data['acoes'] = $this->atas_model->getAllAcoesProjeto($projetos_usuario->projeto_atual);
            $this->load->view($this->theme . 'Atas/manutencao_acao_av', $this->data);
         
    }
    
     public function getAtas($warehouse_id = null)
    {
        $this->sma->checkPermissions('index');

        if ((!$this->Owner || !$this->Admin) && !$warehouse_id) {
            $user = $this->site->getUser();
            $warehouse_id = $user->warehouse_id;
        }
        
        
        
       
        $plano_acao = anchor('Atas/plano_acao/$1', '<i class="fa fa-list"></i> ' . lang('Plano de Ação'), 'class="sledit"');  
        $usuarios_vinculados = anchor('Atas/usuarios_vinculados/$1', '<i class="fa fa-users"></i> ' . lang('Usuários Vinculados'), 'data-toggle="modal" data-target="#myModal"');
        $edit_link = anchor('Atas/edit_discussao/$1', '<i class="fa fa-edit"></i> ' . lang('Discussão da ATA'), 'class="sledit"');
        $pdf_link = anchor('Atas/pdf/$1', '<i class="fa fa-download"></i> ' . lang('Ata em pdf'));
        $delete_link = "<a href='#' class='po' title='<b>" . lang("Apagar ATA") . "</b>' data-content=\"<p>"
        . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . site_url('Atas/delete/$1') . "'>"
        . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i> "
        . lang('Apagar ATA') . "</a>";
        
          
        
        $action = '<div class="text-center"><div class="btn-group text-left">'
        . '<button type="button" class="btn btn-default btn-xs btn-primary dropdown-toggle" data-toggle="dropdown">'
        . lang('actions') . ' <span class="caret"></span></button>
        <ul class="dropdown-menu pull-right" role="menu">
          
            
            <li>' . $plano_acao . '</li>    
            <li>' . $edit_link . '</li>
            <li>' . $pdf_link . '</li>
            
        
           
           
        </ul>
    </div></div>';
        
        
       
          
           $usuario = $this->session->userdata('user_id');
           $projetos_usuario = $this->site->getProjetoAtualByID_completo($usuario);
           $projetos_usuario->projeto_atual;
             
           
               $users_dados = $this->site->geUserByID($usuario);
                $modulo_atual_id = $users_dados->modulo_atual;
                $empresa = $users_dados->empresa_id;
                $empresa_dados = $this->owner_model->getEmpresaById($empresa);
                $tabela_empresa = $empresa_dados->tabela_cliente;
                //echo $tabela_empresa; exit;
                $db_empresa = $this->load->database('sig_plus_unimed', TRUE); 
           
           
         $this->load->library('datatables');
                //(select count(status)from sma_planos where idatas = id and status = 'PENDENTE') as acoes_pendentes
         
            $this->datatables
                ->select("atas.id as id, atas.id as ata,  data_ata, pauta,  tipo, responsavel_elaboracao, atas.status, atas.anexo ")
                ->from('atas')
                ->join('projetos', 'atas.projetos = projetos.id', 'left') 
                ->where('atas.projetos',13 );
               //$this->db->get_where('atas.projetos', array('code' => $tipo));
               $db_empresa->order_by('id', 'desc');
         
             
            
               
      $this->datatables->add_column("Actions", $action, "id");
        
        
        echo $this->datatables->generate();
    }
    
    public function ata_actions()
    {
        if (!$this->Owner && !$this->GP['bulk_actions']) {
            $this->session->set_flashdata('warning', lang('access_denied'));
            redirect($_SERVER["HTTP_REFERER"]);
        }

        $this->form_validation->set_rules('form_action', lang("form_action"), 'required');

        if ($this->form_validation->run() == true) {

            if (!empty($_POST['val'])) {
                if ($this->input->post('form_action') == 'delete') {
                          $this->sma->checkPermissions('delete');
                 
                   
                    foreach ($_POST['val'] as $id) {
                        $ataAtual = $this->atas_model->getAtaByID($id);
                        $statusAta = $ataAtual->status;
                         if($statusAta != 1){
                             $this->atas_model->deleteAta($id);
                          }
                        
                    }
                    $this->session->set_flashdata('message', lang("as Atas que não estão finalizadas foram apagada(s) com Sucesso!!"));
                    
                    
                    
                    redirect('Atas');

                } elseif ($this->input->post('form_action') == 'combine') {

                    $html = $this->combine_pdf($_POST['val']);

                } elseif ($this->input->post('form_action') == 'export_excel' || $this->input->post('form_action') == 'export_pdf') {

                    $this->load->library('excel');
                    $this->excel->setActiveSheetIndex(0);
                    $this->excel->getActiveSheet()->setTitle(lang('ATAS'));
                    $this->excel->getActiveSheet()->SetCellValue('A1', lang('PROJETO'));
                    $this->excel->getActiveSheet()->SetCellValue('B1', lang('DT ATA'));
                    $this->excel->getActiveSheet()->SetCellValue('C1', lang('PAUTA'));
                    $this->excel->getActiveSheet()->SetCellValue('D1', lang('PARTICIPANTES'));
                    $this->excel->getActiveSheet()->SetCellValue('E1', lang('TIPO'));
                    $this->excel->getActiveSheet()->SetCellValue('F1', lang('RESPONSÁVEL'));
                    $this->excel->getActiveSheet()->SetCellValue('G1', lang('ASSINATURAS'));
                    $this->excel->getActiveSheet()->SetCellValue('H1', lang('PENDENCIAS'));
                    
                    
                    $row = 2;
                    foreach ($_POST['val'] as $id) {
                        $ata = $this->atas_model->getAtaProjetoByID_ATA($id);
                        $this->excel->getActiveSheet()->SetCellValue('A' . $row, 'ATA '.$ata->id.'-'.$ata->projetos);
                        $this->excel->getActiveSheet()->SetCellValue('B' . $row, $this->sma->hrld($ata->data_ata));
                        $this->excel->getActiveSheet()->SetCellValue('C' . $row, $ata->pauta);
                        $this->excel->getActiveSheet()->SetCellValue('D' . $row, $ata->participantes);
                        $this->excel->getActiveSheet()->SetCellValue('E' . $row, $ata->tipo);
                        $this->excel->getActiveSheet()->SetCellValue('F' . $row, $ata->responsavel_elaboracao);
                        $this->excel->getActiveSheet()->SetCellValue('G' . $row, $ata->assinaturas);
                        $this->excel->getActiveSheet()->SetCellValue('H' . $row, $ata->pendencias);
                     
                        
                        $row++;
                    }

                    $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
                    $this->excel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    $filename = 'Atas_' . date('Y_m_d_H_i_s');
                    if ($this->input->post('form_action') == 'export_pdf') {
                        $styleArray = array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)));
                        $this->excel->getDefaultStyle()->applyFromArray($styleArray);
                        $this->excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
                        require_once APPPATH . "third_party" . DIRECTORY_SEPARATOR . "MPDF" . DIRECTORY_SEPARATOR . "mpdf.php";
                        $rendererName = PHPExcel_Settings::PDF_RENDERER_MPDF;
                        $rendererLibrary = 'MPDF';
                        $rendererLibraryPath = APPPATH . 'third_party' . DIRECTORY_SEPARATOR . $rendererLibrary;
                        if (!PHPExcel_Settings::setPdfRenderer($rendererName, $rendererLibraryPath)) {
                            die('Please set the $rendererName: ' . $rendererName . ' and $rendererLibraryPath: ' . $rendererLibraryPath . ' values' .
                                PHP_EOL . ' as appropriate for your directory structure');
                        }

                        header('Content-Type: application/pdf');
                        header('Content-Disposition: attachment;filename="' . $filename . '.pdf"');
                        header('Cache-Control: max-age=0');

                        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'PDF');
                        return $objWriter->save('php://output');
                    }
                    if ($this->input->post('form_action') == 'export_excel') {
                        header('Content-Type: application/vnd.ms-excel');
                        header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
                        header('Cache-Control: max-age=0');

                        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
                        return $objWriter->save('php://output');
                    }

                    redirect($_SERVER["HTTP_REFERER"]);
                }
            } else {
                $this->session->set_flashdata('error', lang("Selecione no mínimo 1 ATA"));
                redirect($_SERVER["HTTP_REFERER"]);
            }
        } else {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }
    }
    
    public function usuarios_vinculados($id = null)
    {
     $this->sma->checkPermissions();
     
        //echo 'AKIII'.$id; exit;
        
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
            
        }
        $this->data['ata'] = $this->atas_model->getAtaByID($id);
        $this->data['usuarios'] = $this->atas_model->getAtaUserNameByID_ATA($id);
        
            $this->load->view($this->theme . 'atas/usuarios_vinculados', $this->data);
        
    }
    
    public function add()
    {
        $this->sma->checkPermissions();

        //$this->form_validation->set_rules('projeto', lang("Projeto"), 'required');
        $this->form_validation->set_rules('dateAta', lang("Data da ATA"), 'required');
        $this->form_validation->set_rules('tipo', lang("Tipo da ATA"), 'required');
        $this->form_validation->set_rules('pauta', lang("Pauta"), 'required');
       // $this->form_validation->set_rules('participantes', lang("Participantes"), 'required');
        $this->form_validation->set_rules('nome_elaboracao', lang("Elaboração a Pauta"), 'required');
        $this->form_validation->set_rules('assinaturas', lang("Assinaturas"), 'required');
        //$this->form_validation->set_rules('pendencias', lang("Pendências"), 'required');
        
        $date_cadastro = date('Y-m-d H:i:s');               
        
        if ($this->form_validation->run() == true) {
           
            $projeto = $this->input->post('projeto');
            $dateAta = $this->sma->fld(trim($this->input->post('dateAta'))); 
            $dateTermino = $this->sma->fld(trim($this->input->post('dateTermino')));
            $tipo = trim($this->input->post('tipo')); 
            $pauta = trim($this->input->post('pauta')); 
            $convocacao = $this->input->post('convocacao');
            $texto_convocacao = $this->input->post('texto_convocacao');
            $nome_elaboracao = $this->input->post('nome_elaboracao');
            $assinaturas = $this->input->post('assinaturas');
            $local = $this->input->post('local');
            $usuario_ata = $this->input->post('usuarios_vinculo');
            $participantes = $this->input->post('participantes');
            $note = $this->input->post('note');
             
            
            /*
             * TREINAMENTO
             */
            $facilitadores = $this->input->post('facilitador');
            $reacao = $this->input->post('reacao');
            $aprendizagem = $this->input->post('aprendizagem');
            $desempenho = $this->input->post('desempenho');
            
          
            
            
            if($tipo == "REUNIÃO CONTÍNUA"){
                $evento = $this->input->post('evento');
            }
         
            $data_criacao = $date_cadastro;
            $usuario = $this->session->userdata('user_id');//
            
            if($tipo == 'AVULSA'){
                $avulsa = 'SIM';
            }else{
                $avulsa = 'NÃO';
            }
            //$this->site->getUser($this->session->userdata('user_id'));
            $data_ata = array(
                'projetos' => $projeto,
                'data_ata' => $dateAta,
                'tipo' => $tipo,
                'pauta' => $pauta,
                //'participantes' => $participantes ,
                'responsavel_elaboracao' => $nome_elaboracao,
                'assinaturas' => $assinaturas,
                'local' => $local,
                'obs' => $note,
                'data_criacao' => $data_criacao,   
                'usuario_criacao' => $usuario,
                'avulsa' => $avulsa,
                'evento' => $evento,
                'convocacao' => $convocacao,
                'texto_convocacao' => $texto_convocacao,
                'status' => 0,
                'avaliacao_reacao' => $reacao,
                'avaliacao_aprendizagem' => $aprendizagem,
                'avaliacao_desempenho' => $desempenho,
                'data_termino' => $dateTermino
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
            
            
           // exit;
            
             $id_ata = $this->atas_model->addAtas($data_ata,$usuario_ata,$participantes);
             
            if($id_ata){
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
                            'tipo' => "Convocação"
                        );
                        $id_convocacao = $this->atas_model->addHistorico_convocacao($data_historico_convocacao);
                        $this->ion_auth->emailAtaConvocacao($participante, $id_ata, $id_convocacao);
                       }
                }
             
                
                /*
                 * SE FOR TIPO TREINAMENTO - SALVA OS FACILITADORES
                 */
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
                
                
            $this->session->set_flashdata('message', lang("ATA Criada com Sucesso!!!"));
            redirect("Atas/plano_acao/".$id_ata);
            }else{
                redirect("Atas");
            }
        } else {

           $usuario = $this->session->userdata('user_id');
           $projetos_usuario = $this->site->getProjetoAtualByID_completo($usuario);
           
           $this->data['eventos'] = $this->projetos_model->getAllEventosItemEventoByProjeto($projetos_usuario->projeto_atual, 'ordem', 'asc');   
           // MODEL PARTICIPANTES SELECIONADOS
           $participantes = $this->input->post('participantes');
           // MODEL USUÁRIOS SELECIONADO
           $usuarios_vinculo = $this->input->post('usuarios_vinculos');
          
            $this->data['usuarios_vinculos'] = $usuarios_vinculo;
            $this->data['participantes_lista'] = $participantes;
            
            $dateAta = $this->input->post('dateAta'); 
            $this->data['dateAta'] = $this->sma->hrld($date_cadastro);
            
            $tipo = trim($this->input->post('tipo')); 
            $this->data['tipo'] = $tipo;
            
            /*
             * SELECIONA OS TTIPO DE PESQUISAS DE SATISFAÇÃO
             */
            $this->data['avaliacoes'] = $this->atas_model->getAllPesquisa();   
            
            $pauta = trim($_POST['pauta']); 
            $this->data['pauta'] = "$pauta";
           // $participantes = $this->input->post('participantes');
           // $nome_elaboracao = $this->input->post('nome_elaboracao');
           // $assinaturas = $this->input->post('assinaturas');
            $local = $this->input->post('local');
            $this->data['local'] = $local;
            $this->data['participantes'] = $this->atas_model->getAllUserListaParticipantesByProjeto($projetos_usuario->projeto_atual);
            $this->data['participantes_usuarios_ata'] = $this->atas_model->getAllUserListaVinculoAtaByProjeto($projetos_usuario->projeto_atual);
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['projetos'] = $this->atas_model->getProjetoByID($projetos_usuario->projeto_atual);
            $this->data['users'] = $this->site->getAllUser();
            $bc = array(array('link' => base_url(), 'page' => lang('ATAS')), array('link' => site_url('atas/add'), 'page' => lang('Nova Ata')));
            $meta = array('page_title' => lang('Nova Ata'), 'bc' => $bc);
            $this->page_construct('Atas/add', $meta, $this->data);
        }
    }
    
    public function reenviar_convocacao($participante = null, $ata = null)
    {
        $this->sma->checkPermissions();
        
       // echo 'to aqui'; exit;
        $date_cadastro = date('Y-m-d H:i:s');  
        $data_historico_convocacao = array(
                            'ata' => $ata,
                            'usuario' => $participante,
                            'data_convocacao' => $date_cadastro,
                            'status' => 0
        );
        $id_convocacao = $this->atas_model->addHistorico_convocacao($data_historico_convocacao);
        
        $this->ion_auth->emailAtaConvocacao($participante, $ata, $id_convocacao);
        
        $this->session->set_flashdata('message', lang("Convocação enviada com Sucesso!!!"));
            
            redirect("Atas/plano_acao/".$ata);
    }
    
    public function edit_discussao($id = null)
    {
        $this->sma->checkPermissions();
      
      
        $this->form_validation->set_rules('discussao', lang("Discussao"), 'required');
        $date_cadastro = date('Y-m-d H:i:s');       
        
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        
          if ($this->form_validation->run() == true) {
           
            $discussao = $this->input->post('discussao');
            $titulo_discussao = $this->input->post('titulo_discussao');
            $id_ata = $this->input->post('id');
            
            $data_ata = array(
               
                'discussao' => $discussao,
                'titulo_discussao' => $titulo_discussao
            );
           
         
            $this->atas_model->updateAta($id_ata, $data_ata);
            
            $this->session->set_flashdata('message', lang("ATA Alterada com Sucesso!!!"));
            redirect("Atas/plano_acao/".$id_ata);
            
        } else {

          
            $this->data['id'] = $id;
           
            $this->data['ata'] = $this->atas_model->getAtaByID($id);
            
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['users'] = $this->site->getAllUser();
           
            
            $this->data['users_ata'] = $this->atas_model->getAtaUserByID_ATA($id);
          
            $bc = array(array('link' => site_url('atas'), 'page' => lang('Atas')), array('link' => site_url('atas/edit'), 'page' => lang('Editar ATA')));
            $meta = array('page_title' => lang('Editar ATA'), 'bc' => $bc);
            $this->page_construct_ata('project/cadastro_basico_modelo/atas/discussao', $meta, $this->data);
            
           

            }
    }
    
    public function edit($id = null,  $tipo_ata)
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
            $usuario_ata = $this->input->post('usuario_ata');
            $note = $this->input->post('note');
            $data_criacao = $date_cadastro;
            $usuario = $this->session->userdata('user_id');
            $id_ata = $this->input->post('id');
            
            $tipo_ata = $this->input->post('tipo_ata');
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
            
            
             if($tipo == "REUNIÃO CONTÍNUA"){
                $evento = $this->input->post('evento');
            }
          
            
            $data_ata = array(
               
                'data_ata' => $dateAta,
                'hora_inicio' => $hora_inicio,
                'hora_termino' => $hora_termino,
                'tipo' => $tipo,
                'pauta' => $pauta,
                'assunto' => $assunto,
                'responsavel_elaboracao' => $nome_elaboracao,
                'local' => $local,
                'obs' => $note,
                'data_criacao' => $data_criacao,
                'evento' => $evento
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
            
         //   if($texto_convocacao){
         //       $data_ata['texto_convocacao'] = $texto_convocacao;
         //   }
            $this->atas_model->updateAta($id_ata, $data_ata, $participantes);
            
            
            
            $this->session->set_flashdata('message', lang("ATA Alterada com Sucesso!!!"));
            redirect("Atas/plano_acao/".$id_ata.'/'.$tipo_ata);
            
        } else {

           
            $this->data['id'] = $id;
            $this->data['tipo_ata'] = $tipo_ata;
            $this->data['ata'] = $this->atas_model->getAtaByID($id);
            
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['users'] = $this->site->getAllUser();
            $this->data['projetos'] = $this->atas_model->getAllProjetos();
            $this->data['users_ata'] = $this->atas_model->getAtaUserByID_ATA($id);
            $bc = array(array('link' => site_url('atas'), 'page' => lang('Atas')), array('link' => site_url('atas/edit'), 'page' => lang('Editar ATA')));
            $meta = array('page_title' => lang('Editar ATA'), 'bc' => $bc);
            $this->page_construct('Atas/plano_acao/'.$id_ata, $meta, $this->data);
            
           

            }
    }
 
     public function delete($id = null)
    {
        $this->sma->checkPermissions(null, true);

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        $ataAtual = $this->atas_model->getAtaByID($id);
        $statusAta = $ataAtual->status;
        
        if($statusAta == 1){
           
               
                 $this->session->set_flashdata('message', lang('A ATA não pode ser apagada, pois está finalizada!!!'));
            redirect('Atas');     
            
           
        }else if($statusAta != 1){
             if ($this->atas_model->deleteAta($id)) {
            if ($this->input->is_ajax_request()) {
                echo lang("ATA Apagada");die();
            }else{
                
            }
            $this->session->set_flashdata('message', lang('ATA Apagada com Sucesso!!!'));
            redirect('Atas');
            }   
            
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

   public function pdf_ata($id = null, $view = null)
    {
        
        //$this->sma->checkPermissions();
        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        $usuario = $this->session->userdata('username');
        $empresa = $this->session->userdata('empresa');
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
       
            $this->data['id'] = $id;
            $this->data['ata'] = $this->atas_model->getAtaByID($id);  // $this->atas_model->getAtaProjetoByID_ATA($id);
            $dados_ata = $this->atas_model->getAtaByID($id);
            $empresa_ata = $dados_ata->empresa;
            $assunto = $dados_ata->assunto;
            //echo $empresa_ata;exit;
            $this->data['projeto'] = $this->atas_model->getProjetoByID($dados_ata->projetos);
            
            $empresa_dados = $this->owner_model->getEmpresaById($empresa);
            $cabecalho_empresa_ata = $empresa_dados->cabecalho_ata;
            $redape_empresa_ata = $empresa_dados->rodape_ata;
            //echo $cabecalho_empresa_ata; exit;
            $cadastro_usuario =  $this->atas_model->getUserBySessao();
            $nome_emitiu = $cadastro_usuario->first_name;
            $logo_consultor = $cadastro_usuario->logo_consultor;
            //$this->data['users'] = $this->site->getAllUser();
           // $this->data['projetos'] = $this->atas_model->getAllProjetos();
            $this->data['planos'] = $this->atas_model->getAllitemPlanos($id);

            $name = lang("ATA") . "_" . str_replace('/', '_', $dados_ata->sequencia) . ".pdf";
            $html = $this->load->view($this->theme . 'project/cadastro_basico_modelo/atas/pdf_ata', $this->data, true);

            
            $projetos_usuario = $this->site->getProjetoAtualByID_completo($usuario);
            
         //   $dados_projeto = $this->projetos_model->getProjetoByID($projetos_usuario->projeto_atual);
          //  $logo_doc_top =  $dados_projeto->logo_ata_top;
          //  $logo_doc_bottom =  $dados_projeto->logo_ata_bottom;
            
        if ($view) {
            $this->load->view($this->theme . 'project/cadastro_basico_modelo/atas/pdf_ata', $this->data);
        } else {
            $this->sma->generate_pdf_ata($html, $name, 'S', $usuario, null, null, null, null, $cabecalho_empresa_ata, $redape_empresa_ata, $nome_emitiu, $empresa_ata, $assunto, $logo_consultor);
        }
    }
    
    public function finalizaAta($id_ata = null, $tipo_ata)
    {
        $this->sma->checkPermissions();
      
        
        $date_finalizacao = date('Y-m-d H:i:s');       
        $usuario = $this->session->userdata('user_id');
        $empresa = $this->session->userdata('empresa');
        
        if ($this->input->get('id')) {
            $id_ata = $this->input->get('id');
        }
       
          if ($id_ata) {
           
            $status = 1;
            
            
            
            $data_plano = array(
                'status' => 'PENDENTE'
            );
            //print_r($data_plano); exit;
            
           if($this->atas_model->updatePlanoAta($id_ata, $data_plano)){
               
               $planos_ata = $this->atas_model->getAllPlanoAbertosByAtaID($id_ata); // $this->atas_model->getPlanoByAtaID_distinct($id_ata);
                // ENVIA EMAIL E NOTIFIAÇÕES
                $date_hoje = date('Y-m-d H:i:s');
                $usuario = $this->session->userdata('user_id');
                $empresa = $this->session->userdata('empresa');
               
                
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
                        'referencia' => "Atas > finalizaAta",
                        'idplano' => $id_acao,
                        'empresa' => $empresa,
                        'enviado' => 0);
                        
                        $this->atas_model->add_email($data_email);
                       
                    
                   
                   

               }
               
               
           }
            
           
           
            $data_ataFinalizacao = array(
                'status' => $status,
                'data_finalizacao' => $date_finalizacao,
                'usuario_finalizacao' => $usuario
            );
            
            $this->atas_model->finalizaAta($id_ata, $data_ataFinalizacao);
               
             
            $this->session->set_flashdata('message', lang("ATA Finalizada com Sucesso!!!"));
            
            
            if($tipo_ata == 2){
                $ata  = $this->atas_model->getAtaByID($id_ata);
                $id_pa_acao = $ata->plano_acao;
                
                redirect("project/plano_acao_detalhes/$id_pa_acao");
            }else{
                redirect("project/atas/0/54");
            }
        } else {

           
            //redirect("Atas");
            echo "<script>history.go(-1)</script>";
           

            }
    }
    
     public function edit_participantes($id = null)
    {
           $this->sma->checkPermissions();
           
           $participantes = $this->input->post('participantes');
         // print_r($participantes); exit;
           $this->atas_model->updateParticipantesAta($id,  $participantes);
           $this->session->set_flashdata('message', lang("ATA Alterada com Sucesso!!!"));
           redirect("Atas/plano_acao/".$id);
           
     }
     
      public function edit_usuarios_vinculados($id = null)
    {
           $this->sma->checkPermissions();
           
           $participantes = $this->input->post('usuarios_vinculos');
         //  echo $id.'<br>';
          //print_r($participantes); exit;
           $this->atas_model->updateUsuariosVInvuladoAta($id,  $participantes);
           $this->session->set_flashdata('message', lang("Usuários Vinculados Alterada com Sucesso!!!"));
           redirect("Atas/plano_acao/".$id);
           
     }
     
      function exibirData($data){
	$rData = explode("-", $data);
	$rData = $rData[2].'/'.$rData[1].'/'.$rData[0];
	return $rData;
   }
     
   
    
     public function deletar_acao($id_acao = null)
    {
     
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
            }
            $date_hoje = date('Y-m-d H:i:s');    
            $usuario = $this->session->userdata('user_id');
            $projetos_usuario = $this->site->getProjetoAtualByID_completo($usuario);
                        
           
            $ip = $_SERVER["REMOTE_ADDR"];
            $this->data['acoes_vinculos'] =  $this->atas_model->getAllAcoesProjeto($projetos_usuario->projeto_atual);//$this->atas_model->getAllAcoes();
            
            $this->data['acoes_vinculadas'] = $this->atas_model->getAllAcoesVinculadasAta($id_acao);
            // echo $this->input->post('prazo') .'<br>';
             
            
           $this->data['eventos'] = $this->projetos_model->getAllEventosItemEventoByProjeto($projetos_usuario->projeto_atual);   
                                                            
            $this->data['users'] = $this->atas_model->getAllUsersSetores(); 
            //$this->data['macro'] = $this->atas_model->getAllMacroProcesso();
            
            $this->data['projetos'] = $this->atas_model->getAllProjetos();      
           // $this->data['ata'] = $id;
            $this->data['avulsa'] = $avulsa;
            $this->data['acoes'] = $this->atas_model->getAllAcoesProjeto($projetos_usuario->projeto_atual);
            
            $this->data['idplano'] = $id_acao;
            //$this->data['acoes'] = $this->atas_model->getPlanoByID($id); 
           //  $this->data['acoes'] = $this->atas_model->getAllAcoesProjeto($projetos_usuario->projeto_atual);
           // $this->load->view($this->theme . 'Atas/editar_acao', $this->data); //manutencao_acao_av_pendente
            $this->page_construct_ata('project/cadastro_basico_modelo/atas/excluirAcao', $meta, $this->data);
        
      
            
            
         
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
            redirect('atas/plano_acao/'.$id_ata);
        }
    }
    
    public function duplicarPlano($id = null,$id_ata = null)
    {
        $this->sma->checkPermissions(null, true);

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        
        $acao = $this->atas_model->getPlanoByID($id);
        
            $descricao = $acao->descricao;
            $dataEntrega = $acao->data_termino;
            $dataEntregaDemanda = $acao->data_entrega_demanda; 
            $responsavel = $acao->responsavel; 
            $evento = $acao->eventos; 
            $setor = $acao->setor; 
            
            $custo = $acao->custo; 
            $consultoria = $acao->consultoria; 
            $acaoconsultoria = $acao->acaoconsultoria; 
            $observacao = $acao->observacao;
            $status = $acao->status;
            
            $date_cadastro = date('Y-m-d H:i:s');  
            $usuario = $this->session->userdata('user_id');
            
            
            $acao_vinculo = null;
            $avulsa = null;
           
                 $data_plano = array(
                'idatas' => $id_ata,
                'descricao' => $descricao,
                'data_termino' => $dataEntrega,
                'responsavel' => $responsavel,
                'setor' => $setor,     
                'status' => $status,
                'data_elaboracao' => $date_cadastro,   
                'responsavel_elaboracao' => $usuario,
                'data_entrega_demanda' => $dataEntregaDemanda,
                'custo' => $custo,
                'consultoria' => $consultoria,
                'acaoconsultoria' => $acaoconsultoria,   
                'observacao' => $observacao,
                'eventos' => $evento,
                'status_tipo' => 1     
            );
              
         
            $this->atas_model->add_planoAcao($data_plano,$acao_vinculo,$avulsa,$responsavel);
        
            $this->session->set_flashdata('message', lang('Ação Duplicada com Sucesso!!!'));
            redirect('atas/plano_acao/'.$id_ata);
        
    }
    
     public function pdf($id = null, $view = null)
    {
        
        $this->sma->checkPermissions();

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        $usuario = $this->session->userdata('username');
        
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
      
       
        
            $this->data['id'] = $id;
           
            $this->data['ata'] = $this->atas_model->getAtaProjetoByID_ATA($id);
            
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['users'] = $this->site->getAllUser();
            $this->data['projetos'] = $this->atas_model->getAllProjetos();
            $this->data['planos'] = $this->atas_model->getAllitemPlanos($id);

            $name = lang("ATA") . "_" . str_replace('/', '_', $id) . ".pdf";
            $html = $this->load->view($this->theme . 'Atas/pdf', $this->data, true);

            $usuario = $this->session->userdata('user_id');
            $projetos_usuario = $this->site->getProjetoAtualByID_completo($usuario);
            
            $dados_projeto = $this->projetos_model->getProjetoByID($projetos_usuario->projeto_atual);
            $logo_doc_top =  $dados_projeto->logo_ata_top;
            $logo_doc_bottom =  $dados_projeto->logo_ata_bottom;
            
        if ($view) {
            $this->load->view($this->theme . 'Atas/pdf', $this->data);
        } else{
            
            $this->sma->generate_pdf($html, $name, false, $usuario, null, null, null, null, $logo_doc_top, $logo_doc_bottom);
        }
    }
    
     public function retorno_concluido($id = null)
    {
     
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        
      

                  
            $this->data['idplano'] = $id;
            $this->data['acoes'] = $this->atas_model->getPlanoByID($id);
            $this->load->view($this->theme . 'atas/retorno_view', $this->data);
         
            
         
    }
    
    public function plano_acao($id,  $tipo_ata)
    {
        $this->sma->checkPermissions();
        
        /*
         * VERIFICA SE A ATA PERTENCE AO PROJETO QUE O USUÁRIO ESTÁ LOGADO
         */
         $usuario = $this->session->userdata('user_id');
         $projetos_usuario = $this->site->getProjetoAtualByID_completo($usuario);
         $projeto_atual = $projetos_usuario->projeto_atual;
         //echo $id; exit;                                        
        $ata = $this->atas_model->getAtaByIDByProjeto($id, $projeto_atual);
        $quantidade_ata = $ata->quantidade;
       
        if($quantidade_ata == 0){
             $this->session->set_flashdata('message', lang("A ATA QUE ESTÁ TENTANDO ACESSAR NÃO PERTENCE A ESTE PROJETO"));
            redirect("Atas");
        }else if($quantidade_ata == 1){
            
        
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
            $categoria_plano = $this->input->post('categoria');
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
            $idplano = $this->input->post('plano_acao');
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
                'idplano' => $idplano,
                'categoria_plano' => $categoria_plano,
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
            //redirect("project/manutencao_acao_pendente/".$id_acao);
            
            if($idplano){
                redirect("atas/plano_acao/".$id_ata.'/2');
            }else{
                echo "<script>history.go(-2)</script>";
            }
            
            
        } else {

           
            $this->data['id'] = $id;
           $this->data['tipo_ata'] = $tipo_ata;
            
            $ata = $this->atas_model->getAtaByID($id);
            
            $this->data['users_ata'] = $this->atas_model->getAtaUserByID_ATA($id);
            $this->data['ata'] = $this->atas_model->getAtaByID($id);
            //$this->data['ataAtual'] = $this->atas_model->getAtaByID($id);
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            
            $this->data['users'] = $this->atas_model->getAllUsersSetores(); //$this->atas_model->getAllUsersSetores();
            
            
            $this->data['planosContinuo'] = $this->atas_model->getAllitemPlanosAtaContinua($ata->evento); //getAllitemPlanosAtaContinua
            //$this->data['acoes'] = $this->atas_model->getAllAcoes($id);
            $this->data['planos'] = $this->atas_model->getAllitemPlanos($id);
            
            $usuario = $this->session->userdata('user_id');
            $projetos_usuario = $this->site->getProjetoAtualByID_completo($usuario);
            $this->data['eventos'] = $this->projetos_model->getAllEventosItemEventoByProjeto($projetos_usuario->projeto_atual);   
             
                   
            $this->page_construct_project('project/cadastro_basico_modelo/atas/ata', $meta, $this->data);
            
          }
        }
    }
    
    public function replicar_acao($id_acao = null)
    {
     
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
            }
            $date_hoje = date('Y-m-d H:i:s');    
            $usuario = $this->session->userdata('user_id');
            $projetos_usuario = $this->site->getProjetoAtualByID_completo($usuario);
                        
           
            $ip = $_SERVER["REMOTE_ADDR"];
            $this->data['acoes_vinculos'] =  $this->atas_model->getAllAcoesProjeto($projetos_usuario->projeto_atual);//$this->atas_model->getAllAcoes();
            
            $this->data['acoes_vinculadas'] = $this->atas_model->getAllAcoesVinculadasAta($id_acao);
            // echo $this->input->post('prazo') .'<br>';
             
            
           $this->data['eventos'] = $this->projetos_model->getAllEventosItemEventoByProjeto($projetos_usuario->projeto_atual);   
                                                            
            $this->data['users'] = $this->atas_model->getAllUsersSetores(); 
            //$this->data['macro'] = $this->atas_model->getAllMacroProcesso();
            
            $this->data['projetos'] = $this->atas_model->getAllProjetos();      
           // $this->data['ata'] = $id;
            $this->data['avulsa'] = $avulsa;
            $this->data['acoes'] = $this->atas_model->getAllAcoesProjeto($projetos_usuario->projeto_atual);
            
            $this->data['idplano'] = $id_acao;
            //$this->data['acoes'] = $this->atas_model->getPlanoByID($id); 
           //  $this->data['acoes'] = $this->atas_model->getAllAcoesProjeto($projetos_usuario->projeto_atual);
           // $this->load->view($this->theme . 'Atas/editar_acao', $this->data); //manutencao_acao_av_pendente
            $this->page_construct_ata('project/cadastro_basico_modelo/atas/replicar', $meta, $this->data);
        
      
            
            
         
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
            redirect("Atas/plano_acao/".$id_ata);
            
        }
    }
    
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
            
            $this->data['projetos'] = $this->atas_model->getAllProjetos();      
           // $this->data['ata'] = $id;
           
            $this->data['acoes'] = $this->atas_model->getAllAcoesProjeto($projetos_usuario->projeto_atual, $id_acao);
            
            $this->data['idplano'] = $id_acao;
            //$this->data['acoes'] = $this->atas_model->getPlanoByID($id); 
           //  $this->data['acoes'] = $this->atas_model->getAllAcoesProjeto($projetos_usuario->projeto_atual);
           // $this->load->view($this->theme . 'Atas/editar_acao', $this->data); //manutencao_acao_av_pendente
            $this->page_construct_ata('project/cadastro_basico_modelo/atas/editAcao', $meta, $this->data);
        
     
            
            
         
    }
    
    public function manutencao_acao_pendente_form($id = null)
    {
              
            $date_hoje = date('Y-m-d H:i:s');    
            $usuario = $this->session->userdata('user_id');  
            $ip = $_SERVER["REMOTE_ADDR"];
            
            $idata = $this->input->post('idatas');
            
            $idplano = $this->input->post('id');  
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
            $responsavel = $this->input->post('responsavel');
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
                'descricao' => $descricao,
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
                'peso' => $peso
            );  
          //  print_r($data_plano); exit;
            
           
            
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
            
                       
            
            
            
            if($acao_vinculo){
             $this->session->set_flashdata('message', lang("Ação Vinculada com Sucesso!!!"));   
             redirect("Atas/manutencao_acao_pendente/".$idplano);   
            }else{
                $this->session->set_flashdata('message', lang("Ação Atualizada com Sucesso!!!"));
             redirect("Atas/plano_acao/".$idata);
            }
       
      
            
            
         
    }
    
     public function remove_vinculo_acao($id = null, $id_acao)
    {
         $this->atas_model->deleteVinculo($id);
            
             $this->session->set_flashdata('message', lang("Vinculo apagado com Sucesso!!!"));   
             redirect("Atas/manutencao_acao_pendente/".$id_acao);   
           
       
     }
    
    public function adcionar_acao($id = null,$avulsa = null)
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
            $this->data['eventos'] = $this->projetos_model->getAllEventosItemEventoByProjeto($projetos_usuario->projeto_atual);   
                                                         
            $this->data['users'] = $this->atas_model->getAllUsersSetores(); 
            $this->data['ata'] = $id;
            
            $ata = $this->atas_model->getAtaByID($id);
            $this->data['plano_acao'] = $ata->plano_acao;
            
            $this->data['acoes'] = $this->atas_model->getAllAcoesVinculoCadastro($projetos_usuario->projeto_atual);
            //$this->data['acoes'] = $this->atas_model->getPlanoByID($id);
            
            $participantes = $this->input->post('participantes');
     
            foreach ($participantes as $participante) {
               $participantes_usuario[] = $participante;
            }
            
            $this->data['participantes_usuarios'] = $participantes_usuario;
            //$this->data['participantes_lista'] = "$nomes_participantes";
           
            $this->page_construct_ata('project/cadastro_basico_modelo/atas/novaAcao', $meta, $this->data);
            // $this->load->view($this->theme . 'Atas/adicionar_acao', $this->data);
         
    }
    
    
    
    
    /*
     * ***************** PARTICIPANTES *****************************************
     */
    
    public function index_participantes()
    {

      $this->sma->checkPermissions();
        
        $usuario = $this->session->userdata('user_id');   
        $users = $this->site->geUserByID($usuario);
        /*
         * ATUALIZA SE A ATA TA OU NÃO COM PENDÊNCIAS
         */
        $atas  = $this->atas_model->getAllAtas();
        
        
          
             foreach ($atas as $ata) {
             $planos  = $this->atas_model->getStatusAllPlanosBYAta($ata->id);
               if($planos->status_pendencias > 0){
                   $pendencias = 'COM PENDENCIAS';
               }  else{
                   $pendencias = 'SEM PENDENCIAS';
               }
               
                $data_pendencias = array(
              'pendencias' => $pendencias
            );
              $this->atas_model->updatePendenciasAllAta($ata->id, $data_pendencias);
            }
        
      //  $bc = array(array('link' => '#', 'page' => lang('NOME : '.$users->first_name .' '. $users->last_name .' | SETOR : '.$users->company )));
       
        $meta = array('page_title' => lang('Atas'), 'bc' => $bc);
        $this->page_construct('Atas/participantes/index', $meta, $this->data);

    }
    
    public function add_participante($id = null)
    {
     $this->sma->checkPermissions();
     
        //echo 'AKIII'.$id; exit;
        
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
            
        }
        
        $this->form_validation->set_rules('nome', lang("Nome"), 'required');
        $this->form_validation->set_rules('setor', lang("Setor"), 'required');
        
        
        $date_cadastro = date('Y-m-d H:i:s');               
        
        if ($this->form_validation->run() == true) {
           
            $projeto = $this->input->post('projeto');
            $nome = $this->input->post('nome');
            $setor = trim($this->input->post('setor')); 
            $cargo = trim($this->input->post('cargo')); 
            $email = $this->input->post('email');
            $usuario = $this->input->post('usuario');
                        
            $data_ata = array(
                'projeto' => $projeto,
                'nome' => $nome,
                'setor' => $setor,
                'funcao' => $cargo,
                'email' => $email,
                'usuario' => $usuario
            );
           
             
            $this->atas_model->addParticipantes($data_ata);
            $this->session->set_flashdata('message', lang("Participante Cadastrado com Sucesso!!!"));
            
            redirect("Atas/index_participantes");
            
        } else {
           $usuario = $this->session->userdata('user_id');
           $projetos_usuario = $this->site->getProjetoAtualByID_completo($usuario);
           $this->data['users'] = $this->site->getAllUser();
            $this->data['projeto'] = $projetos_usuario->projeto_atual;
            //$this->data['usuarios'] = $this->atas_model->getAtaUserNameByID_ATA($id);
       
            $this->load->view($this->theme . 'Atas/participantes/add_participantes', $this->data);
        }
        
    }
    
    public function edit_participante($id = null)
    {
     $this->sma->checkPermissions();
     
        //echo 'AKIII'.$id; exit;
        
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
            
        }
        
        $this->form_validation->set_rules('nome', lang("Nome"), 'required');
        $this->form_validation->set_rules('setor', lang("Setor"), 'required');
        
        
        $date_cadastro = date('Y-m-d H:i:s');               
        
        if ($this->form_validation->run() == true) {
           $id_participante = $this->input->post('id_participante');
            $projeto = $this->input->post('projeto');
            $nome = $this->input->post('nome');
            $setor = trim($this->input->post('setor')); 
            $cargo = trim($this->input->post('cargo')); 
            $email = $this->input->post('email');
             $usuario = $this->input->post('usuario');           
            $data_ata = array(
                'projeto' => $projeto,
                'nome' => $nome,
                'setor' => $setor,
                'funcao' => $cargo,
                'email' => $email,
                'usuario' => $usuario
            );
           
             
            $this->atas_model->updateParticipantes($id_participante, $data_ata);
            $this->session->set_flashdata('message', lang("Participante Alterado com Sucesso!!!"));
            
            redirect("Atas/index_participantes");
            
        } else {
           $usuario = $this->session->userdata('user_id');
           $projetos_usuario = $this->site->getProjetoAtualByID_completo($usuario);
           
            $this->data['projeto'] = $projetos_usuario->projeto_atual;
            $this->data['participante'] = $this->atas_model->getParticipanteByID($id);
            $this->data['users'] = $this->site->getAllUser();
            $this->load->view($this->theme . 'Atas/participantes/edit_participantes', $this->data);
        }
    }
    
    public function lista_participante($participantes_lista = null)
    {
     $this->sma->checkPermissions();
     
        //echo 'AKIII'.$id; exit;
        
        if ($this->input->get('id')) {
            $id = $this->input->get('id');           
        }
        
        $this->form_validation->set_rules('nome', lang("Nome"), 'required');
        $this->form_validation->set_rules('setor', lang("Setor"), 'required');
        
        
        $date_cadastro = date('Y-m-d H:i:s');               
        
        if ($this->form_validation->run() == true) {
           
            $projeto = $this->input->post('projeto');
            $nome = $this->input->post('nome');
            $setor = trim($this->input->post('setor')); 
            $cargo = trim($this->input->post('cargo')); 
            $email = $this->input->post('email');
                        
            $data_ata = array(
                'projeto' => $projeto,
                'nome' => $nome,
                'setor' => $setor,
                'funcao' => $cargo,
                'email' => $email
            );
           
             
            $this->atas_model->addParticipantes($data_ata);
            $this->session->set_flashdata('message', lang("Participante Cadastrado com Sucesso!!!"));
            
            redirect("Atas/index_participantes");
            
        } else {
           $usuario = $this->session->userdata('user_id');
           $projetos_usuario = $this->site->getProjetoAtualByID_completo($usuario);
          
           $this->data['projeto'] = $projetos_usuario->projeto_atual;
            $this->data['participantes'] = $this->atas_model->getAllUserListaParticipantesByProjeto($projetos_usuario->projeto_atual);
       
          
         
            
            $this->load->view($this->theme . 'Atas/lista_participantes', $this->data);
        }       
    }
    
    public function lista_participante_plano_acao($id)
    {
     $this->sma->checkPermissions();
     
        //echo 'AKIII'.$id; exit;
        
        if ($this->input->get('id')) {
            $id = $this->input->get('id');           
        }
        
       
           $usuario = $this->session->userdata('user_id');
           $projetos_usuario = $this->site->getProjetoAtualByID_completo($usuario);
          
           $this->data['projeto'] = $projetos_usuario->projeto_atual;
           $this->data['ata'] = $id;
            $this->data['participantes'] = $this->atas_model->getAllUserListaParticipantesByProjeto($projetos_usuario->projeto_atual);
       
          
         
            
            $this->load->view($this->theme . 'Atas/lista_participantes_plano_acao', $this->data);
               
    }
    
    public function lista_convocados($id)
    {
     $this->sma->checkPermissions();
     
        //echo 'AKIII'.$id; exit;
        
        if ($this->input->get('id')) {
            $id = $this->input->get('id');           
        }
        
       
           $usuario = $this->session->userdata('user_id');
           $projetos_usuario = $this->site->getProjetoAtualByID_completo($usuario);
          
           $this->data['projeto'] = $projetos_usuario->projeto_atual;
           $this->data['ata'] = $id;
            $this->data['participantes'] = $this->atas_model->listaConvocados($id);
       
          
         
            
            $this->load->view($this->theme . 'Atas/lista_convocados', $this->data);
               
    }
    
    public function lista_participante_usuario($ata = null, $avulsa = null)
    {
     $this->sma->checkPermissions();
     
        //echo 'AKIII'.$id; exit;
        
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
            
        }
        
        $this->form_validation->set_rules('nome', lang("Nome"), 'required');
        $this->form_validation->set_rules('setor', lang("Setor"), 'required');
        
        
        $date_cadastro = date('Y-m-d H:i:s');               
        
        if ($this->form_validation->run() == true) {
           
            $projeto = $this->input->post('projeto');
            $nome = $this->input->post('nome');
            $setor = trim($this->input->post('setor')); 
            $cargo = trim($this->input->post('cargo')); 
            $email = $this->input->post('email');
                        
            $data_ata = array(
                'projeto' => $projeto,
                'nome' => $nome,
                'setor' => $setor,
                'funcao' => $cargo,
                'email' => $email
            );
           
             
            $this->atas_model->addParticipantes($data_ata);
            $this->session->set_flashdata('message', lang("Participante Cadastrado com Sucesso!!!"));
            
            redirect("Atas/index_participantes");
            
        } else {
           $usuario = $this->session->userdata('user_id');
           $projetos_usuario = $this->site->getProjetoAtualByID_completo($usuario);
           
           $this->data['ata'] = $ata;
           $this->data['avulsa'] = $avulsa;
            $this->data['projeto'] = $projetos_usuario->projeto_atual;
            $this->data['participantes'] = $this->atas_model->getAllParticipantesUsuario($projetos_usuario->projeto_atual);
       
            $this->load->view($this->theme . 'Atas/lista_participantes_usuarios', $this->data);
        }

        
       
        
    }
    
    public function deleteParticipante($id, $id_ata, $tipo)
    {
        $this->sma->checkPermissions(null, true);

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
       //echo $id.' - '.$id_ata; exit;
       
             if ($this->atas_model->deleteParticipanteAta($id, $id_ata, $tipo)) {
                 if ($this->input->is_ajax_request()) {
                echo lang("Participante Apagado");
                die();
            } else {
                
            }
            $this->session->set_flashdata('message', lang('Participante Apagado com Sucesso!!!'));
            redirect("Atas/plano_acao/".$id_ata);
            }   
        
    }
    
    
     public function getParticipantes($warehouse_id = null)
    {
        $this->sma->checkPermissions('index');

        if ((!$this->Owner || !$this->Admin) && !$warehouse_id) {
            $user = $this->site->getUser();
            $warehouse_id = $user->warehouse_id;
        }
        
       
       // $plano_acao = anchor('Atas/plano_acao/$1', '<i class="fa fa-list"></i> ' . lang('Plano de Ação'), 'class="sledit"');  
      //  $usuarios_vinculados = anchor('Atas/usuarios_vinculados/$1', '<i class="fa fa-users"></i> ' . lang('Usuários Vinculados'), 'data-toggle="modal" data-target="#myModal"');
          $edit_link = anchor('Atas/edit_participante/$1', '<i class="fa fa-edit"></i> ' . lang('Editar Participante'),  'class="sledit"  data-toggle="modal" data-target="#myModal"');
       // $pdf_link = anchor('Atas/pdf/$1', '<i class="fa fa-download"></i> ' . lang('pdf'));
        $delete_link = "<a href='#' class='po' title='<b>" . lang("Apagar Participante") . "</b>' data-content=\"<p>"
        . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . site_url('Atas/deleteParticipante/$1') . "'>"
        . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i> "
        . lang('Apagar') . "</a>";
        
          
        
        $action = '<div class="text-center"><div class="btn-group text-left">'
        . '<button type="button" class="btn btn-default btn-xs btn-primary dropdown-toggle" data-toggle="dropdown">'
        . lang('actions') . ' <span class="caret"></span></button>
        <ul class="dropdown-menu pull-right" role="menu">
          
            <li>' . $edit_link . '</li>
            <li>' . $delete_link . '</li>
           
           
        </ul>
    </div></div>';
        
        
       
          
           $usuario = $this->session->userdata('user_id');
           $projetos_usuario = $this->site->getProjetoAtualByID_completo($usuario);
           $projetos_usuario->projeto_atual;
             
           
         $this->load->library('datatables');
                //(select count(status)from sma_planos where idatas = id and status = 'PENDENTE') as acoes_pendentes
         
            $this->datatables
                ->select("id, nome, funcao, setor, email")
                ->from('participantes')
               // ->join('projetos', 'atas.projetos = projetos.id', 'left') 
                ->where('projeto =', $projetos_usuario->projeto_atual);
               //$this->db->get_where('atas.projetos', array('code' => $tipo));
               $this->db->order_by('nome', 'asc');
         
             
            
               
      $this->datatables->add_column("Actions", $action, "id");
        
        
        echo $this->datatables->generate();
    }
    
    /*
     * USUÁRIOS VINCULADOS A ATA - USADO NO PLANO DE ACAO
     */
    public function lista_usuarios_vinculados_plano_acao($id = null)
    {
     $this->sma->checkPermissions();
     
        //echo 'AKIII'.$id; exit;
        
        if ($this->input->get('id')) {
            $id = $this->input->get('id');           
        }
        
       
        
        $date_cadastro = date('Y-m-d H:i:s');               
        
       
           $usuario = $this->session->userdata('user_id');
           $projetos_usuario = $this->site->getProjetoAtualByID_completo($usuario);
           
          $this->data['ata'] = $id;
            $this->data['projeto'] = $projetos_usuario->projeto_atual;
            $this->data['participantes'] = $this->atas_model->getAllUserListaVinculoAtaByProjeto($projetos_usuario->projeto_atual);
       
            $this->load->view($this->theme . 'Atas/lista_usuarios_vinculados_plano_acao', $this->data);
               
    }
    
    /*
     * USUÁRIOS VINCULADOS A ATA
     */
    public function lista_usuarios_vinculados($id = null)
    {
     $this->sma->checkPermissions();
     
        //echo 'AKIII'.$id; exit;
        
        if ($this->input->get('id')) {
            $id = $this->input->get('id');           
        }
        
        $this->form_validation->set_rules('nome', lang("Nome"), 'required');
        $this->form_validation->set_rules('setor', lang("Setor"), 'required');
        
        
        $date_cadastro = date('Y-m-d H:i:s');               
        
        if ($this->form_validation->run() == true) {
           
            $projeto = $this->input->post('projeto');
            $nome = $this->input->post('nome');
            $setor = trim($this->input->post('setor')); 
            $cargo = trim($this->input->post('cargo')); 
            $email = $this->input->post('email');
                        
            $data_ata = array(
                'projeto' => $projeto,
                'nome' => $nome,
                'setor' => $setor,
                'funcao' => $cargo,
                'email' => $email
            );
           
             
            $this->atas_model->addParticipantes($data_ata);
            $this->session->set_flashdata('message', lang("Participante Cadastrado com Sucesso!!!"));
            
            redirect("Atas/index_participantes");
            
        } else {
           $usuario = $this->session->userdata('user_id');
           $projetos_usuario = $this->site->getProjetoAtualByID_completo($usuario);
           
          
            $this->data['projeto'] = $projetos_usuario->projeto_atual;
            $this->data['participantes'] = $this->atas_model->getAllUserListaVinculoAtaByProjeto($projetos_usuario->projeto_atual);
       
            $this->load->view($this->theme . 'Atas/lista_usuarios_vinculados', $this->data);
        }       
    }
    
     /*
     * ***************** TREINAMENTOS *****************************************
     */
    
    public function index_treinamentos()
    {

      $this->sma->checkPermissions();
        
        $usuario = $this->session->userdata('user_id');   
        $users = $this->site->geUserByID($usuario);
        /*
         * ATUALIZA SE A ATA TA OU NÃO COM PENDÊNCIAS
         */
        $atas  = $this->atas_model->getAllAtas();
        
        
          
             foreach ($atas as $ata) {
             $planos  = $this->atas_model->getStatusAllPlanosBYAta($ata->id);
               if($planos->status_pendencias > 0){
                   $pendencias = 'COM PENDENCIAS';
               }  else{
                   $pendencias = 'SEM PENDENCIAS';
               }
               
                $data_pendencias = array(
              'pendencias' => $pendencias
            );
              $this->atas_model->updatePendenciasAllAta($ata->id, $data_pendencias);
            }
        
      //  $bc = array(array('link' => '#', 'page' => lang('NOME : '.$users->first_name .' '. $users->last_name .' | SETOR : '.$users->company )));
       
        $meta = array('page_title' => lang('Atas'), 'bc' => $bc);
        $this->page_construct('Atas/participantes/index', $meta, $this->data);

    }
    
   
    
}