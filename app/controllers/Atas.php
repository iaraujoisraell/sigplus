<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Atas extends MY_Controller
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
        $this->page_construct('Atas/index', $meta, $this->data);

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
             
           
         $this->load->library('datatables');
                //(select count(status)from sma_planos where idatas = id and status = 'PENDENTE') as acoes_pendentes
         
            $this->datatables
                ->select("atas.id as id, atas.id as ata,  data_ata, pauta,  tipo, responsavel_elaboracao,   "
                        . "atas.status, atas.anexo ")
                ->from('atas')
                ->join('projetos', 'atas.projetos = projetos.id', 'left') 
                ->where('atas.projetos =', $projetos_usuario->projeto_atual);
               //$this->db->get_where('atas.projetos', array('code' => $tipo));
               $this->db->order_by('id', 'desc');
         
             
            
               
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
            $id_ata = $this->input->post('id');
            
            $data_ata = array(
               
                'discussao' => $discussao
            );
           
         
            $this->atas_model->updateAta($id_ata, $data_ata);
            
            $this->session->set_flashdata('message', lang("ATA Alterada com Sucesso!!!"));
            redirect("Atas/plano_acao/".$id_ata);
            
        } else {

          
            $this->data['id'] = $id;
           
            $this->data['ata'] = $this->atas_model->getAtaByID($id);
            
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['users'] = $this->site->getAllUser();
            $this->data['projetos'] = $this->atas_model->getAllProjetos();
            $this->data['users_ata'] = $this->atas_model->getAtaUserByID_ATA($id);
            $bc = array(array('link' => site_url('atas'), 'page' => lang('Atas')), array('link' => site_url('atas/edit'), 'page' => lang('Editar ATA')));
            $meta = array('page_title' => lang('Editar ATA'), 'bc' => $bc);
            $this->page_construct('Atas/edit_discussao', $meta, $this->data);
            
           

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
        $this->form_validation->set_rules('assinaturas', lang("Assinaturas"), 'required');
       // $this->form_validation->set_rules('pendencias', lang("Pendências"), 'required');
        
        $date_cadastro = date('Y-m-d H:i:s');       
        
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
         // echo 'aqui'; exit;
          if ($this->form_validation->run() == true) {
           
          //  $projeto = $this->input->post('projeto');
             $usuario_responsavel = $this->session->userdata('user_id');
             
            $dateAta = $this->sma->fld(trim($this->input->post('dateAta'))); 
            $dateTermino = $this->sma->fld(trim($this->input->post('dateTermino'))); 
            $tipo = trim($this->input->post('tipo')); 
            $pauta = trim($this->input->post('pauta')); 
            $participantes = $this->input->post('participantes');
            $nome_elaboracao = $this->input->post('nome_elaboracao');
            $assinaturas = $this->input->post('assinaturas');
            $local = $this->input->post('local');
            $usuario_ata = $this->input->post('usuario_ata');
            $note = $this->input->post('note');
            $data_criacao = $date_cadastro;
            $usuario = $this->session->userdata('user_id');
            $id_ata = $this->input->post('id');
            $convocacao = $this->input->post('gera_convocacao');
           
            $texto_convocacao = $this->input->post('texto_convocacao');
            $cancelamento_convocacao = $this->input->post('cancelamento_convocacao');
            $cancelamento_texto = $this->input->post('texto_cancelamento');
            /*
             * TREINAMENTO
             */
            $facilitadores = $this->input->post('facilitador');
            $reacao = $this->input->post('reacao');
            $aprendizagem = $this->input->post('aprendizagem');
            $desempenho = $this->input->post('desempenho');
            
            
           // echo 'aqui : '.$convocacao; exit;
            
            if($cancelamento_convocacao == 'SIM'){
               $convocacao = 'NÃO';
            }
            
             if($tipo == 'AVULSA'){
                $avulsa = 'SIM';
            }else{
                $avulsa = 'NÃO';
            }
            
             if($tipo == "REUNIÃO CONTÍNUA"){
                $evento = $this->input->post('evento');
            }
          
            
            $data_ata = array(
               
                'data_ata' => $dateAta,
                'tipo' => $tipo,
                'pauta' => $pauta,
                'convocacao' => $convocacao,
                'responsavel_elaboracao' => $nome_elaboracao,
                'assinaturas' => $assinaturas,
                'local' => $local,
                'obs' => $note,
                'data_criacao' => $data_criacao,
                'avulsa' => $avulsa,
                'evento' => $evento,
                'usuario_criacao' => $usuario,
                'avaliacao_reacao' => $reacao,
                'avaliacao_aprendizagem' => $aprendizagem,
                'avaliacao_desempenho' => $desempenho,
                'data_termino' => $dateTermino
            );
            //print_r($data_projeto); exit;
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
            
            if($texto_convocacao){
                $data_ata['texto_convocacao'] = $texto_convocacao;
            }
            $this->atas_model->updateAta($id_ata, $data_ata, $participantes,$usuario_ata);
            
            $ata_selecionada = $this->atas_model->getAtaByID($id_ata);
                if($ata_selecionada->convocacao == 'SIM'){
                    
                    //LISTA DE PARTICIPANTES ATUALIZADA
                    $participantes_cadastrados_ata = $this->atas_model->getAtaUserParticipante_ByID_ATA($id_ata);
                       foreach ($participantes_cadastrados_ata as $participantes) {
                        $id_participante = $participantes->id_participante;
                        $ata = $participantes->id_ata;
                      
                        //verifica se o participante já ta no histórico de convocacao pelo ID da ATA e o ID do USUARIO
                        $verifica_convocado = $this->atas_model->getConvocadoByUsuarioEAta($ata, $id_participante);
                        
                        if($verifica_convocado){
                            
                        }else{
                            $data_historico_convocacao = array(
                                'ata' => $ata,
                                'usuario' => $id_participante,
                                'data_convocacao' => $date_cadastro,
                                'status' => "",
                                'responsavel' => $usuario_responsavel,
                                'texto' => $ata_selecionada->texto_convocacao,
                                'tipo' => "Convocação"
                            );
                             $id_convocacao = $this->atas_model->addHistorico_convocacao($data_historico_convocacao);
                             $this->ion_auth->emailAtaConvocacao($id_participante, $id_ata, $id_convocacao);
                           // echo '---'.$id_participante.'Não está no histórico <br>'; 
                        }
                         
                       }
                      
                }
                
                
                if($cancelamento_convocacao == 'SIM'){
                       foreach ($participantes as $participante) {
                        $id_usuario = $participante;
                        $ata = $id_ata;
                        
                        $data_historico_convocacao = array(
                            'ata' => $ata,
                            'usuario' => $id_usuario,
                            'data_convocacao' => $date_cadastro,
                            'status' => "",
                            'responsavel' => $usuario_responsavel,
                            'texto' => $cancelamento_texto,
                            'tipo' => "Cancelamento"
                            
                        );
                       // print_r($data_historico_convocacao); exit;
                        $id_convocacao = $this->atas_model->addHistorico_convocacao($data_historico_convocacao);
                        
                        
                         $this->ion_auth->emailCancelaConvocacao($id_usuario, $id_ata, $id_convocacao);
                       }
                }
                
                
                
                
                /*
                 * SE FOR TIPO TREINAMENTO - SALVA OS FACILITADORES
                 */
                if($tipo == 'TREINAMENTO'){
                    $this->atas_model->deletaFacilitadores_ByID_ATA($id_ata);
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
            
            
            
            $this->session->set_flashdata('message', lang("ATA Alterada com Sucesso!!!"));
            redirect("Atas/plano_acao/".$id_ata);
            
        } else {

           
            $this->data['id'] = $id;
           
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

    
    public function finalizaAta($id = null,$avulsa = null)
    {
        $this->sma->checkPermissions();
      
        $date_finalizacao = date('Y-m-d H:i:s');       
        
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        
          if ($id) {
           
            $status = 1;
            $id_ata = $id;
            $usuario = $this->session->userdata('user_id');
            
            
            $data_ataFinalizacao = array(
                'status' => $status,
                'data_finalizacao' => $date_finalizacao,
                'usuario_finalizacao' => $usuario
            );
            
             $data_plano = array(
                
                'status' => 'PENDENTE'
            );
            //print_r($data_plano); exit;
            
            $this->atas_model->updatePlanoAta($id_ata, $data_plano);
            //print_r($data_ata); exit;
            
            $this->atas_model->finalizaAta($id_ata, $data_ataFinalizacao);
            
           $usuarios = $this->atas_model->getPlanoByAtaID_distinct($id_ata);
           
            if($avulsa != 'SIM'){
                
                foreach ($usuarios as $usuario) {
                   $id_usuario = $usuario->responsavel;
                   $id_acao = $usuario->idplanos;
                   $this->ion_auth->emailAtaUsuario($id_usuario, $id_acao);

               }
               
                $dados_ata = $this->atas_model->getAtaByID($id_ata);
                $tipo = $dados_ata->tipo;
                $tipo_ava_reacao = $dados_ata->avaliacao_reacao;
         
         
             if($tipo == 'TREINAMENTO'){
                 if($tipo_ava_reacao == 1){
                     
                     $participantes_cadastrados_ata = $this->atas_model->getAtaUserParticipante_ByID_ATA($id_ata);
                        foreach ($participantes_cadastrados_ata as $participante_cadastrados) {
                           
                            $id_participante =  $participante_cadastrados->id;//$this->encrypt($participante_cadastrados->id,'PRATA');
                            //echo 'aqui : '.$id_participante;
                           // exit;
                            /*
                            $data_plano = array(
                            'idatas' => $id_ata,
                            'descricao' => 'Preenhcer o formulário de avaliação ',
                            'data_termino' => $date_finalizacao,
                            'responsavel' => $id_participante,
                            'setor' => $setor_responsavel,     
                            'status' => 'PENDENTE',
                            'data_elaboracao' => $date_finalizacao,   
                            'responsavel_elaboracao' => $usuario,
                            'data_entrega_demanda' => $date_finalizacao,
                          //  'custo' => $custo,
                          //  'consultoria' => $consultoria,
                          //  'acaoconsultoria' => $acaoconsultoria,   
                            'observacao' => 'http://sig.unimedmanaus.com.br/index.php/welcome/pesquisa_reacao/'.$id_participante,
                            'eventos' => $evento
                            );
                            
                             *  $this->atas_model->add_planoAcao($data_plano,$acao_vinculo,$avulsa,$id_responsavel);
                             */
                            $this->ion_auth->emailAvaliacaoReacaoTreinamento($id_participante, $participante_cadastrados->id_participante);
                            
                        }
                         
                     
                 }
             }
               
            
            }
            $this->session->set_flashdata('message', lang("ATA Finalizada com Sucesso!!!"));
            redirect("Atas");
            
        } else {

           
            redirect("Atas");
            
           

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
     
    public function plano_acao($id = null)
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
        $this->form_validation->set_rules('dateEntrega', lang("Data da Entrega"), 'required');
        $this->form_validation->set_rules('dateEntregaDemanda', lang("Data da Entrega da Demanda"), 'required');
       // $this->form_validation->set_rules('responsavel', lang("Responsável"), 'required');
       
      
        
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        
        //echo 'aqui'. $this->input->post('id'); exit;
          if ($this->form_validation->run() == true) {
           
            $id_ata = $this->input->post('id');  
            $descricao = $this->input->post('descricao');
            //$dataEntrega = $this->sma->fld(trim($this->input->post('dateEntrega'))); 
            $dataEntrega = $this->input->post('dateEntrega');// $this->sma->fld(trim()); //$this->input->post('dateEntrega');
            
            //$status = trim($this->input->post('status_plano')); 
            $date_cadastro = date('Y-m-d H:i:s');  
            $usuario = $this->session->userdata('user_id');
            $dataEntregaDemanda = $this->input->post('dateEntregaDemanda'); // $this->sma->fld(trim()); //$this->input->post('dateEntregaDemanda'); 
            $evento = $this->input->post('evento'); 
            $responsaveis = $this->input->post('responsavel'); 
            $acao_vinculo = $this->input->post('acoes_vinculo');
            $custo = trim($this->input->post('custo')); 
            $consultoria = trim($this->input->post('consultoria')); 
            $acaoconsultoria = trim($this->input->post('acaoconsultoria')); 
            $observacao = trim($this->input->post('observacao'));
            $avulsa = trim($this->input->post('avulsa'));
           
            
             foreach ($responsaveis as $responsavel) {
               
                 $dados_responsavel = $this->atas_model->getUserSetorBYid($responsavel);
                 $setor_responsavel = $dados_responsavel->setor;
                 $id_responsavel = $dados_responsavel->usuario;
              

             if($avulsa == 'SIM'){
                 $data_plano = array(
                'idatas' => $id_ata,
                'descricao' => $descricao,
                'data_termino' => $dataEntrega,
                'responsavel' => $id_responsavel,
                'setor' => $setor_responsavel,     
                'status' => 'PENDENTE',
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
              //  
            }else{
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
                'eventos' => $evento,
                'status_tipo' => 1
            );
            //print_r($data_plano); exit;
            
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
            
            $this->atas_model->add_planoAcao($data_plano,$acao_vinculo,$avulsa,$id_responsavel);
            
            }

            
            
            
            $this->session->set_flashdata('message', lang("Ação Cadastrada com Sucesso!!!"));
            redirect("Atas/plano_acao/".$id_ata);
            
        } else {

           
            $this->data['id'] = $id;
           
            if($id){
            
            $this->data['id'] = $id;
         
            $ata = $this->atas_model->getAtaByID($id);
            
            $this->data['users_ata'] = $this->atas_model->getAtaUserByID_ATA($id);
            $this->data['ata'] = $this->atas_model->getAtaByID($id);
            //$this->data['ataAtual'] = $this->atas_model->getAtaByID($id);
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['users'] = $this->site->getAllUser(); //$this->atas_model->getAllUsersSetores();
            $this->data['projetos'] = $this->atas_model->getAllProjetos();
            $this->data['planos'] = $this->atas_model->getAllitemPlanos($id); //
            $this->data['planosContinuo'] = $this->atas_model->getAllitemPlanosAtaContinua($ata->evento); //getAllitemPlanosAtaContinua
            $this->data['acoes'] = $this->atas_model->getAllAcoes($id);
            
            $this->data['eventos'] = $this->projetos_model->getAllEventosItemEventoByProjeto($projetos_usuario->projeto_atual, 'ordem', 'asc');        
            
            $this->data['participantes'] = $this->atas_model->getAllUserListaParticipantesByProjeto($projetos_usuario->projeto_atual);
            $this->data['participantes_usuarios_ata'] = $this->atas_model->getAllUserListaVinculoAtaByProjeto($projetos_usuario->projeto_atual);
           
           // $this->data['participantes_cadastrados_ata'] = $this->atas_model->getAtaUserParticipante_ByID_ATA($id);
            /*
             * SELECIONA OS TTIPO DE PESQUISAS DE SATISFAÇÃO
             */
            $this->data['avaliacoes'] = $this->atas_model->getAllPesquisa();   
            
           // $bc = array(array('link' => base_url(), 'page' => lang('Atas')), array('link' => '#', 'page' => lang('Plano de Ação')));
            $meta = array('page_title' => lang('edit_sale'), 'bc' => $bc);
            $this->page_construct('Atas/plano_acao', $meta, $this->data);
            }else{
                redirect("Atas");
            }
          }
        }
    }
    
    public function deletePlano($id = null,$id_ata = null)
    {
        $this->sma->checkPermissions(null, true);

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
      //  echo 'PLANO :'.$id. '<br> ATA :'.$id_ata ; exit;
        if ($this->atas_model->deletePlano($id)) {
            if ($this->input->is_ajax_request()) {
                echo lang("Plano Apagado com Sucesso!");die();
            }
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
    
    public function manutencao_acao_pendente($id = null)
    {
     
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
            }
            $date_hoje = date('Y-m-d H:i:s');    
            $usuario = $this->session->userdata('user_id');
            $projetos_usuario = $this->site->getProjetoAtualByID_completo($usuario);
                        
            
            $ip = $_SERVER["REMOTE_ADDR"];
            $this->data['acoes_vinculos'] =  $this->atas_model->getAllAcoesProjeto($projetos_usuario->projeto_atual);//$this->atas_model->getAllAcoes();
            $this->data['acoes_vinculadas'] = $this->atas_model->getAllAcoesVinculadas($id);
            // echo $this->input->post('prazo') .'<br>';
            
            
            $this->data['eventos'] = $this->projetos_model->getAllEventosItemEventoByProjeto($projetos_usuario->projeto_atual);
            $this->data['users'] = $this->atas_model->getAllUsersSetores();
            $this->data['macro'] = $this->atas_model->getAllMacroProcesso();
            $this->data['projetos'] = $this->atas_model->getAllProjetos();      
            
            
            //$this->data['eventos'] = $this->projetos_model->getAllEventosProjeto($projetos_usuario->projeto_atual);        
           //  $this->data['users'] = $this->site->getAllUser();
            $this->data['idplano'] = $id;
            //$this->data['acoes'] = $this->atas_model->getPlanoByID($id); 
             $this->data['acoes'] = $this->atas_model->getAllAcoesProjeto($projetos_usuario->projeto_atual);
           // $this->load->view($this->theme . 'Atas/editar_acao', $this->data); //manutencao_acao_av_pendente
            $this->page_construct('Atas/editar_acao', $meta, $this->data);
        
      
            
            
         
    }
    
    public function manutencao_acao_pendente_form($id = null)
    {
              
            $date_hoje = date('Y-m-d H:i:s');    
            $usuario = $this->session->userdata('user_id');  
            $ip = $_SERVER["REMOTE_ADDR"];
            
            $idata = $this->input->post('idatas');
            
            $descricao = $this->input->post('descricao');
            $idplano = $this->input->post('id');
            $prazo = $this->input->post('prazo'); // $this->sma->fld(trim()); 
            $prazo_demanda = $this->input->post('dateEntregaDemanda'); // $this->sma->fld(trim()); 
            $custo = $this->input->post('custo');
            //$porque = $this->input->post('porque');
            //$onde = trim($this->input->post('onde')); 
            //$como = trim($this->input->post('como')); 
            $observacao = trim($this->input->post('observacao')); 
            //$macroprocesso = trim($this->input->post('macroprocesso')); 
            $responsavel = $this->input->post('responsavel');
            $acao_vinculo = $this->input->post('acoes_vinculo');
            $evento = trim($this->input->post('evento')); 
            $consultoria = trim($this->input->post('consultoria')); 
            $acaoconsultoria = $this->input->post('acaoconsultoria');
            
           // echo 'id: '.$responsavel; exit;
            $dados_responsavel = $this->atas_model->getUserSetorBYid($responsavel);
            $setor_responsavel = $dados_responsavel->setor;
            $id_responsavel = $dados_responsavel->usuario;
            
            $data_acao = array(
                'data_termino' => $prazo,
                'data_entrega_demanda' => $prazo_demanda,
                'custo' => $custo,
                'descricao' => $descricao,
                'observacao' => $observacao,
                'responsavel' => $id_responsavel,
                'setor' => $setor_responsavel,  
                //'macroprocesso' => $macroprocesso,
                'eventos' => $evento,
                'consultoria' => $consultoria,
                'acaoconsultoria' => $acaoconsultoria
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
                $data_acao['anexo'] = $photo;
            }
        // print_r($data_acao); exit;
                       
            $this->atas_model->updatePlano($idplano, $data_acao,$acao_vinculo);
            
            $this->session->set_flashdata('message', lang("Ação Atualizada com Sucesso!!!"));
            redirect("Atas/plano_acao/".$idata);
            
       
      
            
            
         
    }
    
    public function adcionar_acao($id = null,$avulsa = null)
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
            $this->data['avulsa'] = $avulsa;
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
            $this->page_construct('Atas/adicionar_acao', $meta, $this->data);
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
    
    public function deleteParticipante($id = null)
    {
        $this->sma->checkPermissions(null, true);

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
       
       
             if ($this->atas_model->deleteParticipante($id)) {
                 if ($this->input->is_ajax_request()) {
                echo lang("Participante Apagado");
                die();
            } else {
                
            }
            $this->session->set_flashdata('message', lang('Participante Apagado com Sucesso!!!'));
            redirect('index_participantes');
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