<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Planos extends MY_Controller
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
            $this->digital_upload_path = 'assets/uploads/historico_acoes/';
        $this->upload_path = 'assets/uploads/historico_acoes/';
        $this->thumbs_path = 'assets/uploads/thumbs/';
        $this->image_types = 'gif|jpg|jpeg|png|tif';
        $this->digital_file_types = 'zip|psd|ai|rar|pdf|doc|docx|xls|xlsx|ppt|pptx|gif|jpg|jpeg|png|tif|txt';
    }

    function index()
    {

      $this->sma->checkPermissions();

         if ($this->input->post('evento')) {
            $evento = $this->input->post('evento');
        } else {
            $evento = NULL;
        }
        
     
        $usuario = $this->session->userdata('user_id');   
        $users = $this->site->geUserByID($usuario);
        $bc = array(array('link' => '#', 'page' => lang('NOME : '.$users->first_name .' '. $users->last_name .' | SETOR : '.$users->company )));
        $this->data['users'] = $this->site->getAllUser();
        $this->data['evento_selecionado'] = $evento;
        $meta = array('page_title' => lang('AÇÕES'), 'bc' => $bc);
        $this->page_construct('Planos/index', $meta, $this->data);

    }
    
     public function getPlanos($warehouse_id = null)
    {
        $this->sma->checkPermissions('index');

        if ((!$this->Owner || !$this->Admin) && !$warehouse_id) {
            $user = $this->site->getUser();
            $warehouse_id = $user->warehouse_id;
        }
        
        
        
         $edit_link = anchor('Planos/manutencao_acao_disable_index/$1', '<i class="fa fa-eye"></i> ' . lang('Ver Ação'), 'data-toggle="modal" data-target="#myModal" class="sledit"');
        
        $action = '<div class="text-center"><div class="btn-group text-left">'
        . '<button type="button" class="btn btn-default btn-xs btn-primary dropdown-toggle" data-toggle="dropdown">'
        . lang('Opções') . ' <span class="caret"></span></button>
        <ul class="dropdown-menu pull-right" role="menu">
          
            
            <li>' . $edit_link . '</li>           
                
            
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
      
        if ($this->input->get('status')) {
            $status = $this->input->get('status');
            //echo 'akiiiiiiiiii'.$status; exit;
        } else {
            $status = NULL;
           // echo 'akiiiiiiiiii 2'.$status; exit;
        }
        if ($this->input->get('ata')) {
            $ata = $this->input->get('ata');
        } else {
            $ata = NULL;
        }
        
        if ($this->input->get('tipo')) {
            $tipo = $this->input->get('tipo');
        } else {
            $tipo = NULL;
        }
        
        if ($this->input->get('evento')) {
            $evento = $this->input->get('evento');
        } else {
            $evento = NULL;
        }
        
        if ($this->input->get('item')) {
            $item = $this->input->get('item');
        } else {
            $item = NULL;
        }
        
        $usuario = $this->session->userdata('user_id');
        $projetos_usuario = $this->site->getProjetoAtualByID_completo($usuario);
        $projetos_usuario->projeto_atual;
           
        $this->load->library('datatables');
            $this->datatables
                ->select("idplanos as id, atas.id as id_ata, idplanos as id_planos,  item_evento.descricao as item_evento, planos.descricao, planos.observacao, users.first_name, setores.nome, planos.data_termino,  planos.status, planos.justificativa") // 
                ->from('planos')
                ->join('atas', 'planos.idatas = atas.id', 'left') 
                ->join('setores', 'planos.setor = setores.id', 'left') 
                ->join('users', 'planos.responsavel = users.id', 'left')
                ->join('item_evento', 'planos.eventos = item_evento.id', 'left')      
                ->join('eventos', 'item_evento.evento = eventos.id', 'left') 
                    
                ->where('atas.projetos =', $projetos_usuario->projeto_atual);
            
            if ($conta) {
                $this->datatables->where('users.id', $conta);
            }
            
            if ($start_date) {
                $this->datatables->where('planos.data_termino BETWEEN "' . $start_date . '" and "' . $end_date . '"');
            }
            
             if ($status) {
                $this->datatables->where('planos.status', $status);
            }
            if ($ata) {
                $this->datatables->where('planos.idatas', $ata);
            }
            if ($tipo) {
                $this->datatables->where('eventos.tipo', $tipo);
            }
             if ($evento) {
                $this->datatables->where('eventos.id', $evento);
                
                if ($item) {
                    $this->datatables->where('item_evento.id', $item);
                }
                
            }
            
            $this->db->order_by('idplanos', 'desc');
         
            
        $this->datatables->add_column("Actions", $action, "id");
        echo $this->datatables->generate();
    }
    
    public function plano_actions()
    {
     

        $this->form_validation->set_rules('form_action', lang("form_action"), 'required');

        if ($this->form_validation->run() == true) {

            if (!empty($_POST['val'])) {
                if ($this->input->post('form_action') == 'delete') {

                    $this->sma->checkPermissions('delete');
                    foreach ($_POST['val'] as $id) {
                        $this->atas_model->deletePlano($id);
                    }
                    $this->session->set_flashdata('message', lang("Plano(s) Pagada com Sucesso!!"));
                    redirect('planos');

                } elseif ($this->input->post('form_action') == 'combine') {

                    $html = $this->combine_pdf($_POST['val']);

                } elseif ($this->input->post('form_action') == 'export_excel' || $this->input->post('form_action') == 'export_pdf') {

                    $this->load->library('excel');
                    $this->excel->setActiveSheetIndex(0);
                    $this->excel->getActiveSheet()->setTitle(lang('PLANOS DE AÇÃO'));
                    $this->excel->getActiveSheet()->SetCellValue('A1', lang('ATA'));
                    $this->excel->getActiveSheet()->SetCellValue('B1', lang('ID'));
                    $this->excel->getActiveSheet()->SetCellValue('C1', lang('O QUE'));
                    $this->excel->getActiveSheet()->SetCellValue('D1', lang('QUEM'));
                    $this->excel->getActiveSheet()->SetCellValue('E1', lang('POR QUE'));
                    $this->excel->getActiveSheet()->SetCellValue('F1', lang('QUANDO'));
                    $this->excel->getActiveSheet()->SetCellValue('G1', lang('ONDE'));
                    $this->excel->getActiveSheet()->SetCellValue('H1', lang('COMO'));
                    $this->excel->getActiveSheet()->SetCellValue('I1', lang('CUSTO'));
                    $this->excel->getActiveSheet()->SetCellValue('J1', lang('OBS'));
                    $this->excel->getActiveSheet()->SetCellValue('L1', lang('STATUS'));
                    
                    
                    
                    $row = 2;
                    foreach ($_POST['val'] as $id) {
                        $plano = $this->atas_model->getPlanoByID_completo($id);
                        
                        $observacao = str_replace('<p>', '', $plano->observacao);
                        $observacao2 = str_replace('</p>', ',', $observacao);
                        $observacao3 = str_replace('<br>', '.', $observacao2);
                        
                        $this->excel->getActiveSheet()->SetCellValue('A' . $row, $plano->idatas);
                        $this->excel->getActiveSheet()->SetCellValue('B' . $row, $plano->idplanos);
                        $this->excel->getActiveSheet()->SetCellValue('C' . $row, $plano->descricao);
                        $this->excel->getActiveSheet()->SetCellValue('D' . $row, $plano->responsavel);
                        $this->excel->getActiveSheet()->SetCellValue('E' . $row, $plano->porque);
                        $this->excel->getActiveSheet()->SetCellValue('F' . $row, $this->sma->hrld($plano->data_termino));
                        $this->excel->getActiveSheet()->SetCellValue('G' . $row, $plano->onde);
                        $this->excel->getActiveSheet()->SetCellValue('H' . $row, $plano->como);
                        $this->excel->getActiveSheet()->SetCellValue('I' . $row, $plano->custo);
                        $this->excel->getActiveSheet()->SetCellValue('J' . $row, $observacao3 );
                        $this->excel->getActiveSheet()->SetCellValue('L' . $row, $plano->status);
                        
                     
                        
                        $row++;
                    }

                    $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
                    $this->excel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    $filename = 'Planos_de_Ação_' . date('Y_m_d_H_i_s');
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
                $this->session->set_flashdata('error', lang("Selecione no mínimo 1 Plano de Ação"));
                redirect($_SERVER["HTTP_REFERER"]);
            }
        } else {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }
    }
    
     public function edit($id = null)
    {
        $this->sma->checkPermissions();
      
       $this->form_validation->set_rules('descricao', lang("Descrição"), 'required');
        $this->form_validation->set_rules('dateEntrega', lang("Data da Entrega"), 'required');
        $this->form_validation->set_rules('responsavel', lang("Responsável"), 'required');
        $this->form_validation->set_rules('status_plano', lang("Status"), 'required');
        
         
        
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        
          if ($this->form_validation->run() == true) {
           
           $id_plano =  $this->input->post('idplano');
            $descricao = $this->input->post('descricao');
            $dataEntrega = $this->sma->fld(trim($this->input->post('dateEntrega'))); 
            $responsavel = trim($this->input->post('responsavel')); 
            $status = trim($this->input->post('status_plano')); 
            
            $data_plano = array(
                'descricao' => $descricao,
                'data_termino' => $dataEntrega,
                'responsavel' => $responsavel,
                'status' => $status
            );
            
            
        // print_r($data_plano); exit;
            
            $this->atas_model->updatePlano($id_plano, $data_plano);
            
            $this->session->set_flashdata('message', lang("Plano de ação Alterado com Sucesso!!!"));
            redirect("planos");
            
        } else {

           
            $this->data['id'] = $id;
           
            $this->data['ata'] = $this->atas_model->getAtaByID($id);
            
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['users'] = $this->site->getAllUser();
            $this->data['plano'] = $this->atas_model->getPlanoByID($id);
            //$this->data['projetos'] = $this->atas_model->getAllProjetos();
            //$this->data['users_ata'] = $this->atas_model->getAtaUserByID_ATA($id);
            $bc = array(array('link' => base_url(), 'page' => lang('Planos')), array('link' => '#', 'page' => lang('Plano de Ação')));
           
            $meta = array('page_title' => lang('edit_sale'), 'bc' => $bc);
            $this->page_construct('planos/edit', $meta, $this->data);
            
           

            }
    }
 
    public function deletePlano($id = null)
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
            redirect('planos/');
        }
    }
    
     public function getPlanosPendentes($warehouse_id = null)
    {
        $this->sma->checkPermissions('index');

        if ((!$this->Owner || !$this->Admin) && !$warehouse_id) {
            $user = $this->site->getUser();
            $warehouse_id = $user->warehouse_id;
        }
        
        
        $cancela_link = anchor('planos/cancela_acao/$1', '<i class="fa fa-edit"></i> ' . lang('Alterar Status'), 'data-toggle="modal" data-target="#myModal"');
       
         $edit_link = anchor('Planos/manutencao_acao_pendente/$1', '<i class="fa fa-eye"></i> ' . lang('Ver Ação'), 'data-toggle="modal" data-target="#myModal" class="sledit"');
       //$edit_link = anchor('planos/edit/$1', '<i class="fa fa-edit"></i> ' . lang('Confirmar'), 'class="sledit"');
       
        $action = '<div class="text-center"><div class="btn-group text-left">'
        . '<button type="button" class="btn btn-default btn-xs btn-primary dropdown-toggle" data-toggle="dropdown">'
        . lang('actions') . ' <span class="caret"></span></button>
        <ul class="dropdown-menu pull-right" role="menu">
          
            
            <li>' . $edit_link . '</li>           
            <li>' . $cancela_link . '</li>        
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
        
        $usuario = $this->session->userdata('user_id');
        $projetos_usuario = $this->site->getProjetoAtualByID_completo($usuario);
        $projetos_usuario->projeto_atual;
           
        $this->load->library('datatables');
            $this->datatables
                ->select("idplanos as id, atas.id as id_ata, idplanos as id_planos, descricao, users.first_name,setores.nome, planos.data_termino,  planos.status")
                ->from('planos')
                ->join('atas', 'planos.idatas = atas.id', 'left')   
                ->join('setores', 'planos.setor = setores.id', 'left') 
                ->join('users', 'planos.responsavel = users.id', 'left')
                    
                ->where('atas.projetos =', $projetos_usuario->projeto_atual)
                ->where('planos.status =', 'PENDENTE');
            
              if ($conta) {
                $this->datatables->where('users.id', $conta);
            }
            if ($start_date) {
                $this->datatables->where('data_termino BETWEEN "' . $start_date . '" and "' . $end_date . '"');
            }
            
            $this->db->order_by('idplanos', 'desc');
                     
        $this->datatables->add_column("Actions", $action, "id");
        echo $this->datatables->generate();
    }
    
    public function planosPendentes()
    {
     $this->sma->checkPermissions();
     
        $this->data['atas'] = $this->atas_model->getAllAtas();
        
        $usuario = $this->session->userdata('user_id');   
        $users = $this->site->geUserByID($usuario);
        $bc = array(array('link' => '#', 'page' => lang('NOME : '.$users->first_name .' '. $users->last_name .' | SETOR : '.$users->company )));
        $this->data['users'] = $this->site->getAllUser();
        $meta = array('page_title' => lang('Ações'), 'bc' => $bc);
        $this->page_construct('Planos/planosPendentes', $meta, $this->data);
        
    }
    
    public function lista_acao_setor($setor = NULL)
    {
        $this->sma->checkPermissions();
        $usuario = $this->session->userdata('user_id');
        $projetos_usuario = $this->site->getProjetoAtualByID_completo($usuario);
        
        $this->data['dataEscolhida'] = date('Y-m-d H:i:s');
        $this->data['projetos_usuario'] = $projetos_usuario->projeto_atual;
        
        $this->data['setores'] = $this->atas_model->getAllSetor();   
        
        
        if($setor){
    
        $this->data['setore_selecionado'] = $setor;   
        $this->data['setor_id'] = $this->atas_model->getSetorByID($setor);
        $this->data['planos'] = $this->atas_model->getAllitemPlanosProjetoSetor($projetos_usuario->projeto_atual,$setor);
        
        }else{
         $this->data['planos'] = $this->atas_model->getAllitemPlanosProjeto($projetos_usuario->projeto_atual);
        }
        
        
        $users = $this->site->geUserByID($usuario);
        $bc = array(array('link' => '#', 'page' => lang('NOME : '.$users->first_name .' '. $users->last_name .' | SETOR : '.$users->company )));
        $this->data['users'] = $this->site->getAllUser();
        $meta = array('page_title' => lang('Ações'), 'bc' => $bc);
        $this->page_construct('Planos/lista_acao_setor', $meta, $this->data);
        
    }
    
     public function planosAguardandoValidacao()
    {
     $this->sma->checkPermissions();
     
        $this->data['atas'] = $this->atas_model->getAllAtas();
        
        $usuario = $this->session->userdata('user_id');   
        $users = $this->site->geUserByID($usuario);
        $this->data['users'] = $this->site->getAllUser();
        $bc = array(array('link' => '#', 'page' => lang('NOME : '.$users->first_name .' '. $users->last_name .' | SETOR : '.$users->company .' | GESTOR DIRETO : '.$users->gestor .' | SUPERINTENDENTE : '.$users->award_points)));
   
        $meta = array('page_title' => lang('Ações'), 'bc' => $bc);
        $this->page_construct('Planos/aguardando_validacao', $meta, $this->data);
        
    }
    
     public function getPlanosAguardandoValidacao($warehouse_id = null)
    {
        $this->sma->checkPermissions('index');

        if ((!$this->Owner || !$this->Admin) && !$warehouse_id) {
            $user = $this->site->getUser();
            $warehouse_id = $user->warehouse_id;
        }
        
        $edit_link = anchor('planos/retorno/$1', '<i class="fa fa-edit"></i> ' . lang('Retorno'), 'data-toggle="modal" data-target="#myModal"');
        $ver_link = anchor('planos/manutencao_acao_av/$1', '<i class="fa fa-refresh"></i> ' . lang('Ver Ação'), 'data-toggle="modal" data-target="#myModal"');
        
        
        $action = '<div class="text-center"><div class="btn-group text-left">'
        . '<button type="button" class="btn btn-default btn-xs btn-primary dropdown-toggle" data-toggle="dropdown">'
        . lang('actions') . ' <span class="caret"></span></button>
        <ul class="dropdown-menu pull-right" role="menu">
          
            <li>' . $ver_link . '</li> 
            <li>' . $edit_link . '</li>           
          
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
           // echo $start_date; exit;
        } else {
            $start_date = NULL;
           
        }
        
         if ($start_date) {
            $start_date = $this->sma->fld($start_date);
            $end_date = $this->sma->fld($end_date);
            
          //  echo $start_date; exit;
        }
        /*
        if ($this->input->get('end_date')) {
            $end_date = $this->input->get('end_date');
        } else {
            $end_date = NULL;
        }
         * 
         */
        
       
         
        if ($this->input->get('status')) {
            $status = $this->input->get('status');
            //echo 'akiiiiiiiiii'.$status; exit;
        } else {
            $status = NULL;
           // echo 'akiiiiiiiiii 2'.$status; exit;
        }
        if ($this->input->get('ata')) {
            $ata = $this->input->get('ata');
        } else {
            $ata = NULL;
        }
        
        if ($this->input->get('tipo')) {
            $tipo = $this->input->get('tipo');
        } else {
            $tipo = NULL;
        }
        
        if ($this->input->get('evento')) {
            $evento = $this->input->get('evento');
        } else {
            $evento = NULL;
        }
        
        if ($this->input->get('item')) {
            $item = $this->input->get('item');
        } else {
            $item = NULL;
        }
        
        $usuario = $this->session->userdata('user_id');
        $projetos_usuario = $this->site->getProjetoAtualByID_completo($usuario);
        $projetos_usuario->projeto_atual;
           
        $this->load->library('datatables');
            $this->datatables
                ->select("idplanos as id, atas.id as id_ata, idplanos as id_planos, item_evento.descricao as item_evento, planos.descricao, planos.observacao, users.first_name, setores.nome, planos.data_termino,  planos.status")
                ->from('planos')
                ->join('atas', 'planos.idatas = atas.id', 'left')   
                ->join('setores', 'planos.setor = setores.id', 'left') 
                ->join('users', 'planos.responsavel = users.id', 'left')
                       ->join('item_evento', 'planos.eventos = item_evento.id', 'left')      
                ->join('eventos', 'item_evento.evento = eventos.id', 'left') 
                ->where('atas.projetos =', $projetos_usuario->projeto_atual)
                ->where('planos.id_ticket', NULL)    
                ->where('planos.status =', 'AGUARDANDO VALIDAÇÃO');
            
            
             if ($conta) {
                $this->datatables->where('users.id', $conta);
            }
            
            if ($start_date) {
                $this->datatables->where('planos.data_termino BETWEEN "' . $start_date . '" and "' . $end_date . '"');
            }
             if ($status) {
                $this->datatables->where('planos.status', $status);
            }
            
             if ($ata) {
                $this->datatables->where('planos.idatas', $ata);
            }
            
             if ($tipo) {
                $this->datatables->where('eventos.tipo', $tipo);
            }
            
             if ($evento) {
                $this->datatables->where('eventos.id', $evento);
                
                if ($item) {
                    $this->datatables->where('item_evento.id', $item);
                }
                
            }
            
            
            $this->db->order_by('idplanos', 'desc');
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
    
    public function manutencao_acao_pendente($id = null)
    {
      $this->sma->checkPermissions();
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
            }
        
            $somacampo = 0;
            
           
            $custo = $this->input->post('custo');
            $porque = $this->input->post('porque');
            $onde = trim($this->input->post('onde')); 
            $como = trim($this->input->post('como')); 
            $observacao = trim($this->input->post('observacao')); 
            $macroprocesso = trim($this->input->post('macroprocesso')); 
            
             if($custo){
                $somacampo++;
            }
            if($porque){
                $somacampo++;
            }
            if($onde){
                $somacampo++;
            }
            if($porque){
                $somacampo++;
            }
            if($observacao){
                $somacampo++;
            }
            if($macroprocesso){
                $somacampo++;
            }
            
         if ($somacampo > 0) {
            
            $date_hoje = date('Y-m-d H:i:s');    
            $usuario = $this->session->userdata('user_id');  
            $ip = $_SERVER["REMOTE_ADDR"];
            
            // echo $this->input->post('prazo') .'<br>';
            
            $idplano = $this->input->post('id');
           //$dados_plano = $this->atas_model->getPlanoByID($idplano);
            
            if($prazo){
            $prazo = $this->input->post('prazo'); 
            }
            
            $custo = $this->input->post('custo');
            $porque = $this->input->post('porque');
            $onde = trim($this->input->post('onde')); 
            $como = trim($this->input->post('como')); 
            $observacao = trim($this->input->post('observacao')); 
            $macroprocesso = trim($this->input->post('macroprocesso')); 
           // $responsavel = $this->input->post('responsavel');
            
            
            $acao = $this->atas_model->getPlanoByID($idplano);
            $responsavel = $acao->responsavel;  //RESPONSÁVEL ATUAL
            $setor_responsavel_atual = $acao->setor;
           
            //$responsavel_setor = $acao->setor;
            
            $responsavel_selecionado = $this->input->post('responsavel');
            $dados_responsavel = $this->atas_model->getUserSetorBYid($responsavel_selecionado);
            $setor_responsavel_novo = $dados_responsavel->setor;
            $id_responsavel_novo = $dados_responsavel->usuario; // SELECIONADO
            
            
            
            
            
            
            
     
           if($prazo){
            $data_acao = array(
                'data_termino' => $prazo,
                'custo' => $custo,
                'porque' => $porque,
                'onde' => $onde,
                'como' => $como,
                'observacao' => $observacao,
              //  'responsavel' => $responsavel,
                'macroprocesso' => $macroprocesso
            );
           }else{
                $data_acao = array(
                'custo' => $custo,
                'porque' => $porque,
                'onde' => $onde,
                'como' => $como,
                'observacao' => $observacao,
              //  'responsavel' => $responsavel,
                'macroprocesso' => $macroprocesso
            );
           }
         // print_r($data_acao); exit;
                    
         
           
           /*
             * VERIFICA SE MUDOU O RESPONSÁVEL
             */
              if($responsavel != $id_responsavel_novo){
               
                $data_acao['responsavel'] = $id_responsavel_novo;
                $data_acao['setor'] = $setor_responsavel_novo;
                
                
             /*
             * CRIA O HISTÓRICO DA ALTERAÇÃO DO USUÁRIO
             */
                
                $dados_usuario_atual = $this->site->getUser($responsavel);
                $nome_atual = $dados_usuario_atual->first_name;
                $snome_atual = $dados_usuario_atual->last_name;
                
                $dados_usuario_setor = $this->atas_model->getSetorByID($setor_responsavel_atual);
                $setor_atual = $dados_usuario_setor->nome;
                
                $dados_resp_atual = $nome_atual.' '.$snome_atual.' - '.$setor_atual.'<br>';
                
                $dados_usuario_novo = $this->site->getUser($id_responsavel_novo);
                $nome_novo = $dados_usuario_novo->first_name;
                $snome_novo = $dados_usuario_novo->last_name;
                
                $dados_usuario_setor_novo = $this->atas_model->getSetorByID($setor_responsavel_novo);
                $setor_novo = $dados_usuario_setor_novo->nome;
                $dados_resp_novo =  $nome_novo.' '.$snome_novo.' - '.$setor_novo.'<br>';
                
                $data_historico_alteracao_responsavel = array(
                'data_envio' => $date_hoje,
                'usuario' => $usuario,
                'plano' => $idplano,
                'observacao' => 'Responsável alterado de: '.$dados_resp_atual.' Para : '.$dados_resp_novo,
                'status' => $acao->status,
                'ip' => $ip
            );
                $this->atas_model->add_historicoPlanoAcao( $data_historico_alteracao_responsavel);
                
                
               // $this->ion_auth->emailEdpUsuario($idplano);
            }
           
           
           
           
            $this->atas_model->updatePlano($idplano, $data_acao);
            
            
             
            
            
            
            $this->session->set_flashdata('message', lang("Ação Atualizada com Sucesso!!!"));
            redirect("Planos/planosPendentes");
            
         }else{       
              $this->data['users'] = $this->atas_model->getAllUsersSetores();   
            $this->data['macro'] = $this->atas_model->getAllMacroProcesso();
            $this->data['projetos'] = $this->atas_model->getAllProjetos();      
            $this->data['idplano'] = $id;
            $this->data['acoes'] = $this->atas_model->getPlanoByID($id);
            
            $this->load->view($this->theme . 'Planos/manutencao_acao_av_pendente', $this->data);
          
           //$meta = array('page_title' => lang('Ações'), 'bc' => $bc);
          //  $this->page_construct('Planos/manutencao_acao_av_pendente', $meta, $this->data);
         
        }
      
            
            
         
    }
    
    public function manutencao_acao_pendente_form($id = null)
    {
      $this->sma->checkPermissions();
              
            $date_hoje = date('Y-m-d H:i:s');    
            $usuario = $this->session->userdata('user_id');  
            $ip = $_SERVER["REMOTE_ADDR"];
            
            // echo $this->input->post('prazo') .'<br>';
            
            $idplano = $this->input->post('id');
           
           // $prazo = $this->sma->fld(trim($this->input->post('prazo'))); 
            
            $prazo_atual = $this->sma->hrld($this->input->post('prazo_atual'));
            $prazo_atual_data = substr($prazo_atual, 0, 10);
            $nova_data_historico = trim($this->input->post('prazo_novo'));//formatada para o histórico
            $prazo_novo = $this->sma->fld(trim($this->input->post('prazo_novo')));
            $prazo_novo_data = substr($prazo_novo, 0, 10);
            
            $custo = $this->input->post('custo');
            $porque = $this->input->post('porque');
            $onde = trim($this->input->post('onde')); 
            $como = trim($this->input->post('como')); 
            $observacao = trim($this->input->post('observacao')); 
            $macroprocesso = trim($this->input->post('macroprocesso')); 
           // $responsavel = $this->input->post('responsavel');
            
            
            $acao = $this->atas_model->getPlanoByID($idplano);
            $responsavel = $acao->responsavel;  //RESPONSÁVEL ATUAL
            $setor_responsavel_atual = $acao->setor;
           
            $status = $this->input->post('status');
            //$responsavel_setor = $acao->setor;
            
            $responsavel_selecionado = $this->input->post('responsavel');
            $dados_responsavel = $this->atas_model->getUserSetorBYid($responsavel_selecionado);
            $setor_responsavel_novo = $dados_responsavel->setor;
            $id_responsavel_novo = $dados_responsavel->usuario; // SELECIONADO
            
           
            
            
       
                $data_acao = array(
                'custo' => $custo,
                'porque' => $porque,
                'onde' => $onde,
                'como' => $como,
                'observacao' => $observacao,
                'responsavel' => $responsavel,
                'macroprocesso' => $macroprocesso
            );
          
           
            if($prazo_novo_data != $prazo_atual_data){
                 $date_hoje = date('Y-m-d H:i:s');  
               
                 /*
                 if($prazo_novo < $date_hoje){
                    $this->session->set_flashdata('message', lang("O PRAZO DA AÇÃO NÃO PODE SER MENOR QUE A DATA ATUAL!!!"));
                    redirect("Planos/planosAguardandoValidacao");
                   }
           
                  * 
                  */
                   
                $data_acao['data_termino'] = $prazo_novo;
               
                
            /*
             * CAMPOS DO HISTÓRICO DA ALTERAÇÃO DA DATA
             */
                $data_historico_alteracao_prazo = array(
                'data_envio' => $date_hoje,
                'usuario' => $usuario,
                'plano' => $idplano,
                'observacao' => 'Prazo alterado de: '.$prazo_atual.' Para : '.$nova_data_historico,
                'status' => $status,
                'ip' => $ip
            );
                $this->atas_model->add_historicoPlanoAcao( $data_historico_alteracao_prazo);
            //echo 'data diferente';
            }
            
         // print_r($data_acao); exit;
                       
               if($responsavel != $id_responsavel_novo){
               
                    
                   
                $data_acao['responsavel'] = $id_responsavel_novo;
                $data_acao['setor'] = $setor_responsavel_novo;
                
             
                
               
             /*
             * CRIA O HISTÓRICO DA ALTERAÇÃO DO USUÁRIO
             */
                
                $dados_usuario_atual = $this->site->getUser($responsavel);
                $nome_atual = $dados_usuario_atual->first_name;
                $snome_atual = $dados_usuario_atual->last_name;
                
                $dados_usuario_setor = $this->atas_model->getSetorByID($setor_responsavel_atual);
                $setor_atual = $dados_usuario_setor->nome;
                
                $dados_resp_atual = $nome_atual.' '.$snome_atual.' - '.$setor_atual.'<br>';
                
                $dados_usuario_novo = $this->site->getUser($id_responsavel_novo);
                $nome_novo = $dados_usuario_novo->first_name;
                $snome_novo = $dados_usuario_novo->last_name;
                
                $dados_usuario_setor_novo = $this->atas_model->getSetorByID($setor_responsavel_novo);
                $setor_novo = $dados_usuario_setor_novo->nome;
                $dados_resp_novo =  $nome_novo.' '.$snome_novo.' - '.$setor_novo.'<br>';
                
                $data_historico_alteracao_responsavel = array(
                'data_envio' => $date_hoje,
                'usuario' => $usuario,
                'plano' => $idplano,
                'observacao' => 'Responsável alterado de: '.$dados_resp_atual.' Para : '.$dados_resp_novo,
                'status' => $acao->status,
                'ip' => $ip
            );
                $this->atas_model->add_historicoPlanoAcao( $data_historico_alteracao_responsavel);
                
                
               // $this->ion_auth->emailEdpUsuario($idplano);
            }
           
            $this->atas_model->updatePlano($idplano, $data_acao);
            
            $this->session->set_flashdata('message', lang("Ação Atualizada com Sucesso!!!"));
            redirect("Planos/planosPendentes");
            
       
      
            
            
         
    }
    
    public function manutencao_acao_av($id = null)
    {
      $this->sma->checkPermissions();
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
            }
              
           
             $this->data['users'] = $this->site->getAllUser();
            $this->data['macro'] = $this->atas_model->getAllMacroProcesso();
            $this->data['projetos'] = $this->atas_model->getAllProjetos();      
            $this->data['idplano'] = $id;
            $this->data['acoes'] = $this->atas_model->getPlanoByID($id);
            $this->load->view($this->theme . 'Planos/manutencao_acao_av', $this->data);
         
        
    }
    
    
    public function manutencao_acao_av_form($id = null)
    {
      $this->sma->checkPermissions();
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
            }
        
           
            
            $date_hoje = date('Y-m-d H:i:s');    
            $usuario = $this->session->userdata('user_id');  
            $ip = $_SERVER["REMOTE_ADDR"];
            
            // echo $this->input->post('prazo') .'<br>';
            
            $idplano = $this->input->post('id');
         
            $prazo = $this->sma->fld(trim($this->input->post('prazo'))); 
            
            $custo = $this->input->post('custo');
            $porque = $this->input->post('porque');
            $onde = trim($this->input->post('onde')); 
            $como = trim($this->input->post('como')); 
            $observacao = trim($this->input->post('observacao')); 
            $macroprocesso = trim($this->input->post('macroprocesso')); 
            $responsavel = $this->input->post('responsavel');
            $descricao = $this->input->post('descricao');
            
           if($prazo){
            $data_acao = array(
                'data_termino' => $prazo,
                'custo' => $custo,
                'porque' => $porque,
                'onde' => $onde,
                'como' => $como,
                'observacao' => $observacao,
                'responsavel' => $responsavel,
                'macroprocesso' => $macroprocesso,
                'descricao' => $descricao
            );
           }else{
                $data_acao = array(
                'custo' => $custo,
                'porque' => $porque,
                'onde' => $onde,
                'como' => $como,
                'observacao' => $observacao,
                'responsavel' => $responsavel,
                'macroprocesso' => $macroprocesso,
                'descricao' => $descricao
            );
           }
         // print_r($data_acao); exit;
                       
            $this->atas_model->updatePlano($idplano, $data_acao);
            
            $this->session->set_flashdata('message', lang("Ação Atualizada com Sucesso!!!"));
            redirect("Planos/planosAguardandoValidacao");
            
      
      
            
            
         
    }
    
     public function manutencao_acao_disable_index($id = null)
    {
      $this->sma->checkPermissions();
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
            }
            $this->data['acoes_vinculos'] = $this->atas_model->getAllAcoes();
            $this->data['acoes_vinculadas'] = $this->atas_model->getAllAcoesVinculadas($id);
            // echo $this->input->post('prazo') .'<br>';
            $usuario = $this->session->userdata('user_id');
            $projetos_usuario = $this->site->getProjetoAtualByID_completo($usuario);
            $this->data['eventos'] = $this->projetos_model->getAllEventosProjeto($projetos_usuario->projeto_atual);        
            $this->data['users'] = $this->site->getAllUser();
            $this->data['macro'] = $this->atas_model->getAllMacroProcesso();
            $this->data['projetos'] = $this->atas_model->getAllProjetos();      
            $this->data['idplano'] = $id;
            $this->data['acoes'] = $this->atas_model->getPlanoByID($id);
            $this->load->view($this->theme . 'Planos/manutencao_acao_vinculo', $this->data);
         
    }
    
     public function manutencao_acao_disable($id = null)
    {
      $this->sma->checkPermissions();
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
            }
        
            
            $this->data['users'] = $this->site->getAllUser();
            $this->data['macro'] = $this->atas_model->getAllMacroProcesso();
            $this->data['projetos'] = $this->atas_model->getAllProjetos();      
            $this->data['idplano'] = $id;
            $this->data['acoes'] = $this->atas_model->getPlanoByID($id);
            $this->load->view($this->theme . 'Planos/manutencao_acao_concluidos', $this->data);
         
    }
    
    public function manutencao_acao_concluidos_lista_setores($id = null,$setor = NULL)
    {
      $this->sma->checkPermissions();
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
            }
        
            $this->data['setore_selecionado'] = $setor;  
            $this->data['users'] = $this->site->getAllUser();
            $this->data['macro'] = $this->atas_model->getAllMacroProcesso();
            $this->data['projetos'] = $this->atas_model->getAllProjetos();      
            $this->data['idplano'] = $id;
            $this->data['acoes'] = $this->atas_model->getPlanoByID($id);
            $this->load->view($this->theme . 'Planos/manutencao_acao_concluidos_lista_setores', $this->data);
         
    }
    
    public function getPlanosConcluidos($warehouse_id = null)
    {
        $this->sma->checkPermissions('index');

        if ((!$this->Owner || !$this->Admin) && !$warehouse_id) {
            $user = $this->site->getUser();
            $warehouse_id = $user->warehouse_id;
        }
        
        
        
        $edit_link = anchor('planos/manutencao_acao_disable/$1', '<i class="fa  fa-eye"></i> ' . lang('Ver Ação'), 'data-toggle="modal" data-target="#myModal"');
        //$edit_link = anchor('planos/edit/$1', '<i class="fa fa-edit"></i> ' . lang('Confirmar'), 'class="sledit" data-toggle="modal" data-target="#myModal"');   
       
        $action = '<div class="text-center"><div class="btn-group text-left">'
        . '<button type="button" class="btn btn-default btn-xs btn-primary dropdown-toggle" data-toggle="dropdown">'
        . lang('actions') . ' <span class="caret"></span></button>
        <ul class="dropdown-menu pull-right" role="menu">
          
            
            <li>' . $edit_link . '</li>           
          
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
        
        $usuario = $this->session->userdata('user_id');
        $projetos_usuario = $this->site->getProjetoAtualByID_completo($usuario);
        $projetos_usuario->projeto_atual;
           
        $this->load->library('datatables');
            $this->datatables
                ->select("idplanos as id, atas.id as id_ata,idplanos as id_planos,  descricao, users.first_name, setores.nome, planos.data_termino,data_retorno_usuario, planos.status")
                ->from('planos')
                ->join('atas', 'planos.idatas = atas.id', 'left')   
                ->join('setores', 'planos.setor = setores.id', 'left')      
                ->join('users', 'planos.responsavel = users.id', 'left')
                ->where('atas.projetos =', $projetos_usuario->projeto_atual)
                ->where('planos.status =', 'CONCLUÍDO');
            
             if ($conta) {
                $this->datatables->where('users.id', $conta);
            }
            if ($start_date) {
                $this->datatables->where('data_termino BETWEEN "' . $start_date . '" and "' . $end_date . '"');
            }
            
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
    
    /*
     * AGURDANDO VALIDAÇÃO
     */
     public function retorno($id = null)
    {
     
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        
      
        
        $this->form_validation->set_rules('status', lang("Status"), 'required');
        
         if ($this->form_validation->run() == true) {
           
             
             
            $date_hoje = date('Y-m-d H:i:s');    
            $usuario = $this->session->userdata('user_id');  
            $idplano = $this->input->post('id');
            $status = $this->input->post('status');
            $prazo_atual = $this->sma->hrld($this->input->post('prazo_atual'));
            $prazo_atual_data = substr($prazo_atual, 0, 10);
            $nova_data_historico = trim($this->input->post('prazo_novo'));//formatada para o histórico
            $prazo_novo = $this->sma->fld(trim($this->input->post('prazo_novo')));
            $prazo_novo_data = substr($prazo_novo, 0, 10);
             
            
            
            $acao = $this->atas_model->getPlanoByID($idplano);
            $responsavel = $acao->responsavel;  //RESPONSÁVEL ATUAL
            $setor_responsavel_atual = $acao->setor;
           
            //$responsavel_setor = $acao->setor;
            
            $responsavel_selecionado = $this->input->post('responsavel');
          
            $dados_responsavel = $this->atas_model->getUserSetorBYid($responsavel_selecionado);
            $setor_responsavel_novo = $dados_responsavel->setor;
            $id_responsavel_novo = $dados_responsavel->usuario; // SELECIONADO
            $note = $this->input->post('note');  
            $observacao = trim($this->input->post('observacao')); 
            $ip = $_SERVER["REMOTE_ADDR"];
            
            /*
             * CAMPOS DA AÇÃO
             */
            $data_acao = array(
                'status' => $status,
                'justificativa' => $note
            );
            
            
            /*
             * SE TIVER DATA, COLOCA O CAMPO NO ARRAY E SALVA O HISTÓRICO DA ALTERAÇÃO
             */
            
            if($prazo_novo_data != $prazo_atual_data){
                 $date_hoje = date('Y-m-d H:i:s');  
               
                 /*
                 if($prazo_novo < $date_hoje){
                    $this->session->set_flashdata('message', lang("O PRAZO DA AÇÃO NÃO PODE SER MENOR QUE A DATA ATUAL!!!"));
                    redirect("Planos/planosAguardandoValidacao");
                   }
           
                  * 
                  */
                   
                $data_acao['data_termino'] = $prazo_novo;
               
                
            /*
             * CAMPOS DO HISTÓRICO DA ALTERAÇÃO DA DATA
             */
                $data_historico_alteracao_prazo = array(
                'data_envio' => $date_hoje,
                'usuario' => $usuario,
                'plano' => $idplano,
                'observacao' => 'Prazo alterado de: '.$prazo_atual.' Para : '.$nova_data_historico,
                'status' => $status,
                'ip' => $ip
            );
                $this->atas_model->add_historicoPlanoAcao( $data_historico_alteracao_prazo);
            //echo 'data diferente';
            }
            
            
            /*
             * VERIFICA SE MUDOU O RESPONSÁVEL
             */
              if($responsavel != $id_responsavel_novo){
               
                $data_acao['responsavel'] = $id_responsavel_novo;
                $data_acao['setor'] = $setor_responsavel_novo;
                
                
             /*
             * CRIA O HISTÓRICO DA ALTERAÇÃO DO USUÁRIO
             */
                
                $dados_usuario_atual = $this->site->getUser($responsavel);
                $nome_atual = $dados_usuario_atual->first_name;
                $snome_atual = $dados_usuario_atual->last_name;
                
                $dados_usuario_setor = $this->atas_model->getSetorByID($setor_responsavel_atual);
                $setor_atual = $dados_usuario_setor->nome;
                
                $dados_resp_atual = $nome_atual.' '.$snome_atual.' - '.$setor_atual.'<br>';
                
                $dados_usuario_novo = $this->site->getUser($id_responsavel_novo);
                $nome_novo = $dados_usuario_novo->first_name;
                $snome_novo = $dados_usuario_novo->last_name;
                
                $dados_usuario_setor_novo = $this->atas_model->getSetorByID($setor_responsavel_novo);
                $setor_novo = $dados_usuario_setor_novo->nome;
                $dados_resp_novo =  $nome_novo.' '.$snome_novo.' - '.$setor_novo.'<br>';
                
                $data_historico_alteracao_responsavel = array(
                'data_envio' => $date_hoje,
                'usuario' => $usuario,
                'plano' => $idplano,
                'observacao' => 'Responsável alterado de: '.$dados_resp_atual.' Para : '.$dados_resp_novo,
                'status' => $status,
                'ip' => $ip
            );
                $this->atas_model->add_historicoPlanoAcao( $data_historico_alteracao_responsavel);
            }
            
            
            
             //echo 'em manutenção'; exit;
            $data_historicoAcao = array(
                'data_envio' => $date_hoje,
                'usuario' => $usuario,
                'plano' => $idplano,
                'observacao' => $observacao,
                'status' => $status,
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
          
            $this->atas_model->updatePlano($idplano, $data_acao);
            $this->atas_model->add_historicoPlanoAcao( $data_historicoAcao);
            
            $this->ion_auth->emailEdpUsuario($idplano);
            
            $this->session->set_flashdata('message', lang("Retorno da ação enviada com Sucesso!!!"));
            redirect("Planos/planosAguardandoValidacao");
            
         }else{
            $this->data['users'] = $this->atas_model->getAllUsersSetores();      
            $this->data['idplano'] = $id;
            $this->data['acoes'] = $this->atas_model->getPlanoByID($id);
            $this->load->view($this->theme . 'Planos/retorno', $this->data);
         
            
         }
    }
    
    
    public function planosConcluidos()
    {
     $this->sma->checkPermissions();
     
        //$this->data['atas'] = $this->atas_model->getAllAtas();
        
        $usuario = $this->session->userdata('user_id');   
        $users = $this->site->geUserByID($usuario);
        $bc = array(array('link' => '#', 'page' => lang('NOME : '.$users->first_name .' '. $users->last_name .' | SETOR : '.$users->company)));
        $this->data['users'] = $this->site->getAllUser();
        $meta = array('page_title' => lang('Ações'), 'bc' => $bc);
        $this->page_construct('Planos/planosConcluidos', $meta, $this->data);
        
    }
    
    public function retorno_concluido($id = null)
    {
     
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        
      
        
            $this->data['projetos'] = $this->atas_model->getAllProjetos();      
            $this->data['idplano'] = $id;
            $this->data['acoes'] = $this->atas_model->getPlanoByID($id);
            $this->load->view($this->theme . 'Planos/manutencao_acao', $this->data);
         
            
         
    }
    
    
    public function cancela_acao($id = null)
    {
        $this->sma->checkPermissions();
        
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            
        $this->data['id'] = $id;
        $this->load->view($this->theme . 'Planos/cancela_acao', $this->data);
        
    }
    
      public function mudarStatuaAcao()
    {
          $this->sma->checkPermissions();
   
        $idplano = $this->input->post('id_acao');  
        $status = $this->input->post('status');
        $note = $this->input->post('note');  
         
        $data_acao = array(
                'status' => $status,
                'justificativa' => $note
            );
        
        
        $this->atas_model->updatePlano($idplano, $data_acao, null);
      
        redirect("Planos/planosPendentes");
        
    }
    
    public function add_item_evento_form()
    {
    
           $dataInicial = $this->input->post('dateInicial'); 
           $dataFinal = $this->input->post('dateFim'); 
           $evento = $this->input->post('evento');
           $descricao = $this->input->post('descricao');
           $note = $this->input->post('note');
           $horas = $this->input->post('horas');
            
            
            
              $data_evento = array(
                'dt_inicio' => $dataInicial,
                'dt_fim' => $dataFinal,
                'horas_previstas' => $horas,
                'evento' => $evento,
                'descricao' => $descricao,
                'observacoes' => $note
               
            );
            
           $this->projetos_model->addItensventos($data_evento);
            $this->session->set_flashdata('message', lang("Itém do Evento Criado com Sucesso!!!"));
            
             redirect("Projetos/Item_evento_index/$evento");
               
           
           
            
       
     }
}