<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Projetos extends MY_Controller
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
        $this->load->model('calendar_model');
       $this->load->model('user_model');
        $this->digital_upload_path = 'assets/uploads/projetos';
        $this->upload_path = 'assets/uploads/projetos';
        $this->thumbs_path = 'assets/uploads/thumbs/projetos';
         $this->image_types = 'gif|jpg|jpeg|png|tif';
        $this->digital_file_types = 'zip|psd|ai|rar|pdf|doc|docx|xls|xlsx|ppt|pptx|gif|jpg|jpeg|png|tif|txt';
    }

    function index()
    {

      $this->sma->checkPermissions();
       
        $meta =  array('page_title' => lang('Projetos'), 'bc' => $bc);
        $this->page_construct('projetos/index', $meta, $this->data);

    }
    
     public function getProjetos($warehouse_id = null)
    {
        $this->sma->checkPermissions('index');

        if ((!$this->Owner || !$this->Admin) && !$warehouse_id) {
            $user = $this->site->getUser();
            $warehouse_id = $user->warehouse_id;
        }
        
        
        $selecionar =  anchor('Login_Projetos/projeto_ata/$1', '<i class="fa fa-exchange"></i> ' . lang('Selecionar'));
        $dashboard =  anchor('projetos/dashboard/$1', '<i class="fa fa-file-text-o"></i> ' . lang('Dashboard'));
        $edit_link = anchor('projetos/edit/$1', '<i class="fa fa-edit"></i> ' . lang('Editar Projeto'), 'class="sledit"');
        $tap_link = anchor('projetos/tap/$1', '<i class="fa fa-edit"></i> ' . lang('TAP'), 'class="sledit"');
        $cancela_link = "<a href='#' class='po' title='<b>" . lang("Cancelar Projeto") . "</b>' data-content=\"<p>"
        . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . site_url('projetos/cancelar/$1') . "'>"
        . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-ban\"></i> "
        . lang('Cancelar Projeto') . "</a>";
           
        $delete_link = "<a href='#' class='po' title='<b>" . lang("Apagar Projeto") . "</b>' data-content=\"<p>"
        . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . site_url('projetos/delete/$1') . "'>"
        . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i> "
        . lang('Apagar Projeto') . "</a>";
        $action = '<div class="text-center"><div class="btn-group text-left">'
        . '<button type="button" class="btn btn-default btn-xs btn-primary dropdown-toggle" data-toggle="dropdown">'
        . lang('actions') . ' <span class="caret"></span></button>
        <ul class="dropdown-menu pull-right" role="menu">
          
           
            <li>' . $edit_link . '</li>
            <li>' . $tap_link . '</li>    
            
          
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
        
        $this->load->library('datatables');
            $this->datatables
                ->select("projetos.id as id,projetos.projeto, projetos.dt_inicio, projetos.dt_final, projetos.dt_virada,gerente_area,gerente_edp, gerente_fornecedor, status, botao")
                    ->join('projetos', 'users_projetos.projeto = projetos.id', 'left')
                ->from('users_projetos');
                $this->db->where("users_projetos.users", $usuario);
                $this->db->order_by('projetos.id', 'desc');
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
    
    public function projeto_actions()
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
                        $this->projetos_model->deleteProjeto($id);
                    }
                    $this->session->set_flashdata('message', lang("Projeto(s) Pagado(s)!!"));
                    redirect('projetos');

                } elseif ($this->input->post('form_action') == 'combine') {

                    $html = $this->combine_pdf($_POST['val']);

                } elseif ($this->input->post('form_action') == 'export_excel' || $this->input->post('form_action') == 'export_pdf') {

                    $this->load->library('excel');
                    $this->excel->setActiveSheetIndex(0);
                    $this->excel->getActiveSheet()->setTitle(lang('Projetos'));
                    $this->excel->getActiveSheet()->SetCellValue('A1', lang('PROJETO'));
                    $this->excel->getActiveSheet()->SetCellValue('B1', lang('DT INÍCIO'));
                    $this->excel->getActiveSheet()->SetCellValue('C1', lang('DT FIM'));
                    $this->excel->getActiveSheet()->SetCellValue('D1', lang('DT VIRADA'));
                    $this->excel->getActiveSheet()->SetCellValue('E1', lang('GERENTE DA ÁREA'));
                    $this->excel->getActiveSheet()->SetCellValue('F1', lang('GERENTE EDP'));
                    $this->excel->getActiveSheet()->SetCellValue('G1', lang('GERENTE FORNECEDOR'));
                    $this->excel->getActiveSheet()->SetCellValue('H1', lang('FORNECEDOR'));
                    $this->excel->getActiveSheet()->SetCellValue('I1', lang('OBS'));
                    $this->excel->getActiveSheet()->SetCellValue('J1', lang('STATUS'));
                    $row = 2;
                    foreach ($_POST['val'] as $id) {
                        $projeto = $this->projetos_model->getProjetoByID($id);
                        $this->excel->getActiveSheet()->SetCellValue('A' . $row, $projeto->projeto);
                        $this->excel->getActiveSheet()->SetCellValue('B' . $row, $this->sma->hrld($projeto->dt_inicio));
                        $this->excel->getActiveSheet()->SetCellValue('C' . $row, $this->sma->hrld($projeto->dt_final));
                        $this->excel->getActiveSheet()->SetCellValue('D' . $row, $this->sma->hrld($projeto->dt_virada));
                        $this->excel->getActiveSheet()->SetCellValue('E' . $row, $projeto->gerente_area);
                        $this->excel->getActiveSheet()->SetCellValue('F' . $row, $projeto->gerente_edp);
                        $this->excel->getActiveSheet()->SetCellValue('G' . $row, lang($projeto->gerente_fornecedor));
                        $this->excel->getActiveSheet()->SetCellValue('H' . $row, $projeto->fornecedor);
                        $this->excel->getActiveSheet()->SetCellValue('I' . $row, $projeto->obs);
                        $this->excel->getActiveSheet()->SetCellValue('J' . $row, lang($projeto->status));
                        $row++;
                    }

                    $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
                    $this->excel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    $filename = 'Projetos_' . date('Y_m_d_H_i_s');
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
                $this->session->set_flashdata('error', lang("Selecione no mínimo 1 projeto"));
                redirect($_SERVER["HTTP_REFERER"]);
            }
        } else {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }
    }
    
    public function add()
    {
        $this->sma->checkPermissions();

        $this->form_validation->set_rules('nome_projeto', lang("Nome do Projeto"), 'required');
        $this->form_validation->set_rules('dateInicial', lang("Data Inicial"), 'required');
        $this->form_validation->set_rules('dateFim', lang("Data Final"), 'required');
        $this->form_validation->set_rules('gerenteArea', lang("Gerente da Área"), 'required');
      //  $this->form_validation->set_rules('status', lang("Status"), 'required');
        $date_cadastro = date('Y-m-d H:i:s');               
        
        if ($this->form_validation->run() == true) {
           
            $nome = $this->input->post('nome_projeto');
            $dataInicial = $this->sma->fld(trim($this->input->post('dateInicial'))); 
            $dataFinal = $this->sma->fld(trim($this->input->post('dateFim'))); 
            $dataVirada = $this->sma->fld(trim($this->input->post('dateVirada'))); 
            $gerenteArea = $this->input->post('gerenteArea');
            $gerenteEDP = $this->input->post('gerenteEDP');
          //  $gerenteFornecedor = $this->input->post('gerenteFornecedor');
            $cor = $this->input->post('cor');
            $status = 'ATIVO';
            $note = $this->input->post('note');
            $data_criacao = $date_cadastro;
            $usuario = $this->session->userdata('user_id');//
            //$this->site->getUser($this->session->userdata('user_id'));
            $data_projeto = array('projeto' => $nome,
                'dt_inicio' => $dataInicial,
                'dt_final' => $dataFinal,
                'dt_virada' => $dataVirada,
                'gerente_area' => $gerenteArea,
                'gerente_edp' => $gerenteEDP ,
              //  'gerente_fornecedor' => $gerenteFornecedor,
                'status' => $status,
                'botao' => $cor,
               // 'obs' => $note,
                'data_criacao' => $data_criacao,   
                'usuario' => $usuario
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
                $data_projeto['anexo'] = $photo;
            }
            
         //print_r($data_projeto); exit;
            
            $this->projetos_model->addProjetos($data_projeto);
            
            $this->session->set_flashdata('message', lang("Projeto Criado com Sucesso!!!"));
            redirect("projetos");
            
        } else {

        

            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['providers'] = $this->site->getAllCompanies('supplier');
            $this->data['users'] = $this->site->getAllUser();
            $bc = array(array('link' => base_url(), 'page' => lang('Projetos')), array('link' => site_url('projetos/add'), 'page' => lang('Novo Projeto')));
            $meta = array('page_title' => lang('Novo Projeto'), 'bc' => $bc);
            $this->page_construct('projetos/add', $meta, $this->data);
        }
    }
    
     public function edit($id = null)
    {
        $this->sma->checkPermissions();
      
       $this->form_validation->set_rules('nome_projeto', lang("Nome do Projeto"), 'required');
        $this->form_validation->set_rules('dateInicial', lang("Data Inicial"), 'required');
        $this->form_validation->set_rules('dateFim', lang("Data Final"), 'required');
        $this->form_validation->set_rules('gerenteArea', lang("Gerente da Área"), 'required');
      //  $this->form_validation->set_rules('status', lang("Status"), 'required');
        $date_cadastro = date('Y-m-d H:i:s');               
        
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        
          if ($this->form_validation->run() == true) {
           
            $nome = $this->input->post('nome_projeto');
            $dataInicial = $this->sma->fld(trim($this->input->post('dateInicial'))); 
            $dataFinal = $this->sma->fld(trim($this->input->post('dateFim'))); 
            $dataVirada = $this->sma->fld(trim($this->input->post('dateVirada'))); 
            $gerenteArea = $this->input->post('gerenteArea');
            $gerenteEDP = $this->input->post('gerenteEDP');
          //  $gerenteFornecedor = $this->input->post('gerenteFornecedor');
            $cor = $this->input->post('cor');
          //  $status = $this->input->post('status');
          //  $note = $this->input->post('note');
            $data_criacao = $date_cadastro;
            $usuario = $this->session->userdata('user_id');//
            $id_projeto = $this->input->post('id');
            
            //$this->site->getUser($this->session->userdata('user_id'));
            $data_projeto = array('projeto' => $nome,
                'dt_inicio' => $dataInicial,
                'dt_final' => $dataFinal,
                'dt_virada' => $dataVirada,
                'gerente_area' => $gerenteArea,
                'gerente_edp' => $gerenteEDP ,
           //     'gerente_fornecedor' => $gerenteFornecedor,
            //    'status' => $status,
                'botao' => $cor,
            //    'obs' => $note,
                'data_criacao' => $data_criacao,   
                'usuario' => $usuario
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
                $data_projeto['anexo'] = $photo;
            }
            
         //print_r($data_projeto); exit;
            
            $this->projetos_model->updateProjeto($id_projeto, $data_projeto);
            
            $this->session->set_flashdata('message', lang("Projeto Alterado com Sucesso!!!"));
            redirect("projetos");
            
        } else {

            $this->data['id'] = $id;
           
            $this->data['projeto'] = $this->projetos_model->getProjetoByID($id);
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['providers'] = $this->site->getAllCompanies('supplier');
            $this->data['users'] = $this->site->getAllUser();

            $bc = array(array('link' => base_url(), 'page' => lang('Projetos')), array('link' => '#', 'page' => lang('Editar Projeto')));
            $meta = array('page_title' => lang('edit_sale'), 'bc' => $bc);
            $this->page_construct('projetos/edit', $meta, $this->data);
            
           

            }
    }
 
    
     public function cancelar($id = null)
    {
        $this->sma->checkPermissions(null, true);
        
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        $date_cadastro = date('Y-m-d H:i:s');  
        $data = array(
                 'status' => 'CANCELADO',
                 'data_cancelamento' => $date_cadastro,
                 'usuario_cancelou' => $this->session->userdata('user_id')
              );
        
        if ($this->projetos_model->updateProjeto($id, $data)) {
            if ($this->input->is_ajax_request()) {
                echo lang("Projeto Cancelado!");die();
            }
            $this->session->set_flashdata('message', lang("Projeto Cancelado com Sucesso!!!"));
            redirect("projetos");
        }
    }
    
     public function delete($id = null)
    {
        $this->sma->checkPermissions(null, true);

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        if ($this->projetos_model->deleteProjeto($id)) {
            if ($this->input->is_ajax_request()) {
                echo lang("Projeto Apagado");die();
            }
            $this->session->set_flashdata('message', lang('Projeto Apagado'));
            redirect('projetos');
        }
    }
    
    public function dashboard($id = null)
    {
        $this->sma->checkPermissions();
      
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        
        /*
         * VERIFICA O PERFIL DO USUÁRIO
         */
        $usuario = $this->session->userdata('user_id');
        $projetos = $this->site->getProjetoAtualByID_completo($usuario);
        $perfil_atual = $projetos->group_id;
        $perfis_user = $this->site->getUserGroupAtual($perfil_atual);
        $id_perfil_atual = $perfis_user->id;
        
        
        /*
         * CONSULTAS PARA TODOS OS PERFIS
         */
        //Qtde de Atas DO PROJETO. SERVE PARA TODOS OS PERFIS
        $this->data['ata'] =  $this->projetos_model->getAtaByProjeto($id);
        /*
         * EVENTOS - TIMELINE
         */
        $this->data['eventos'] = $this->projetos_model->getAllEventosProjeto($id,'ordem','asc');
        //$eventos = $this->projetos_model->getAllEventosProjeto($id,'ordem','asc');
       // print_r($eventos);exit;
        /*
         * GRÁFICO PIE - PRESIDENCIA
         */
        $this->data['areas_projeto'] =  $this->projetos_model->getAreasByProjeto($id);
        //GRÁFICO PIE - total_acoes_areas
        $this->data['total_acoes_areas'] =  $this->projetos_model->getAcoesTodasSuperintendenciaByProjeto($id);
        /*
         * PROJETO
         */
        $this->data['projeto_selecionado'] = $id;
        
       
        
        
        /*
         * PERFIL EDP
         */
        if($id_perfil_atual == 1){
        //Qtde de pessoas na equipe
          $equipe = $this->projetos_model->getEquipeByProjeto($id);
         $this->data['equipe'] =  $equipe->responsavel;
       
         //Qtde de AÇÕES
         $total_acoes =  $this->projetos_model->getQtdeAcoesByProjeto($id);
        $this->data['total_acoes'] = $total_acoes->total_acoes;
        //Qtde de Ações concluídas
        $concluido = $this->projetos_model->getStatusAcoesByProjeto($id, 'CONCLUÍDO');
        $this->data['concluido'] =  $concluido->status;
        //Qtde de ações Pendentes
        $pendente = $this->projetos_model->getAcoesPendentesByProjeto($id, 'PENDENTE');
        $avalidacao = $this->projetos_model->getAcoesAguardandoValidacaoByProjeto($id, 'AGUARDANDO VALIDAÇÃO');
        $this->data['pendente'] =  $pendente->pendente + $avalidacao->avalidacao;
        $atrasadas = $this->projetos_model->getAcoesAtrasadasByProjeto($id, 'PENDENTE');
        //Qtde de Ações Atrasadas
        $this->data['atrasadas'] =  $atrasadas->atrasadas;
        
        /* 
         * PEGA AS ÁREAS QUE TEM AÇÕES
         */
        // SE FOR SUPERINTENDENTE
        $this->data['areas_usuario_projeto'] = $this->projetos_model->getAreasByProjeto($id);
        //SE FOR GESTOR
        
        
        //GRÁFICO AÇOES NA LINHA DO TEMPO
        $this->data['acoes_tempo'] =  $this->projetos_model->getAllitemStatusPlanosLinhaTempo($id);
        
        
        /*
         * PERFIL DE GESTOR
         */
        }else  if($id_perfil_atual == 2){
         /*
          * GESTOR
          */   
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
          */
         $qtde_equipe_superintendencia = $this->projetos_model->getEquipeByProjetoSuperintendencia($id_perfil_atual,$id, $id_superintendencia);
         $soma_qtde_equipe_superintendencia += $qtde_equipe_superintendencia->responsavel; 
         /*
          * QUANTIDADE DE AÇÕES POR SETOR E PROJETO
          */
         $quantidade_acoes_superintendencia = $this->projetos_model->getQtdeAcoesByProjetoSuperintendencia($id_perfil_atual,$id,$id_superintendencia);
         $soma_qtde_acoes_superintendencia += $quantidade_acoes_superintendencia->total_acoes;
         /*
          * QUANTIDADE DE AÇÕES CONCLUÍDAS
          */
         $qtde_acoes_concluida_superintendencia = $this->projetos_model->getAcoesByProjetoSuperintendenciaStatus($id_perfil_atual, $id, 'CONCLUÍDO',$id_superintendencia);
         $soma_qtde_acoes_concluidas_superintendencia += $qtde_acoes_concluida_superintendencia->quantidade;
         /*
          * QUANTIDADE DE AÇÕES PENDENTES
          */
         $qtde_acoes_pendentes_superintendencia = $this->projetos_model->getAcoesByProjetoSuperintendenciaStatus($id_perfil_atual,$id, 'PENDENTE',$id_superintendencia);
         $soma_qtde_acoes_pendentes_superintendencia += $qtde_acoes_pendentes_superintendencia->quantidade;
         /*
          * QUANTIDADE DE AÇÕES AGUARDANDO VALIDAÇÃO
          */
         $qtde_acoes_aguardando_validacao_superintendencia = $this->projetos_model->getAcoesByProjetoSuperintendenciaStatus($id_perfil_atual, $id, 'AGUARDANDO VALIDAÇÃO',$id_superintendencia);
         $soma_qtde_acoes_avalidacao_superintendencia += $qtde_acoes_aguardando_validacao_superintendencia->quantidade;
        
         /*
          * QUANTIDADE DE AÇÕES ATRASADAS
          */
         $qtde_acoes_atrasadas_superintendencia = $this->projetos_model->getAcoesByProjetoSuperintendenciaStatus($id_perfil_atual,$id, 'ATRASADO',$id_superintendencia);
         $soma_qtde_acoes_atrasadas_superintendencia += $qtde_acoes_atrasadas_superintendencia->quantidade;
         /*
          * AÇÕES NA LINHA DO TEMPO
          */
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
         */
        } if($id_perfil_atual == 3){
        
       /*
        * SUPERINTENDENCIAS LIGADA AO USUÁRIO
        */
         
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
          */
         $qtde_equipe_superintendencia = $this->projetos_model->getEquipeByProjetoSuperintendencia($id_perfil_atual,$id, $id_superintendencia);
         $soma_qtde_equipe_superintendencia += $qtde_equipe_superintendencia->responsavel; 
         /*
          * QUANTIDADE DE AÇÕES POR SUPERINTENDENCIA E PROJETO
          */
         $quantidade_acoes_superintendencia = $this->projetos_model->getQtdeAcoesByProjetoSuperintendencia($id_perfil_atual,$id,$id_superintendencia);
         $soma_qtde_acoes_superintendencia += $quantidade_acoes_superintendencia->total_acoes;
         /*
          * QUANTIDADE DE AÇÕES CONCLUÍDAS
          */
         $qtde_acoes_concluida_superintendencia = $this->projetos_model->getAcoesByProjetoSuperintendenciaStatus($id_perfil_atual, $id, 'CONCLUÍDO',$id_superintendencia);
         $soma_qtde_acoes_concluidas_superintendencia += $qtde_acoes_concluida_superintendencia->quantidade;
         /*
          * QUANTIDADE DE AÇÕES PENDENTES
          */
         $qtde_acoes_pendentes_superintendencia = $this->projetos_model->getAcoesByProjetoSuperintendenciaStatus($id_perfil_atual, $id, 'PENDENTE',$id_superintendencia);
         $soma_qtde_acoes_pendentes_superintendencia += $qtde_acoes_pendentes_superintendencia->quantidade;
         /*
          * QUANTIDADE DE AÇÕES AGUARDANDO VALIDAÇÃO
          */
         $qtde_acoes_aguardando_validacao_superintendencia = $this->projetos_model->getAcoesByProjetoSuperintendenciaStatus($id_perfil_atual, $id, 'AGUARDANDO VALIDAÇÃO',$id_superintendencia);
         $soma_qtde_acoes_avalidacao_superintendencia += $qtde_acoes_aguardando_validacao_superintendencia->quantidade;
         /*
          * QUANTIDADE DE AÇÕES ATRASADAS
          */
         $qtde_acoes_atrasadas_superintendencia = $this->projetos_model->getAcoesByProjetoSuperintendenciaStatus($id_perfil_atual, $id, 'ATRASADO',$id_superintendencia);
         $soma_qtde_acoes_atrasadas_superintendencia += $qtde_acoes_atrasadas_superintendencia->quantidade;
         /*
          * AÇÕES NA LINHA DO TEMPO
          */
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
        
        //data_atual
        $this->data['data_hoje'] = date('Y-m-d H:i:s');
        
        //$this->data['projetos'] = $this->atas_model->getAllProjetos();
        $this->load->view($this->theme . 'projetos/dashboard', $this->data);           
    }
    
     public function dashboard_pdf($id = null, $view = 1)
    {
        
          $this->sma->checkPermissions();
      
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        
        /*
         * VERIFICA O PERFIL DO USUÁRIO
         */
        $usuario = $this->session->userdata('user_id');
        $projetos = $this->site->getProjetoAtualByID_completo($usuario);
        $perfil_atual = $projetos->group_id;
        $perfis_user = $this->site->getUserGroupAtual($perfil_atual);
        $id_perfil_atual = $perfis_user->id;
        
        
        /*
         * CONSULTAS PARA TODOS OS PERFIS
         */
        //Qtde de Atas DO PROJETO. SERVE PARA TODOS OS PERFIS
        $this->data['ata'] =  $this->projetos_model->getAtaByProjeto($id);
        /*
         * EVENTOS - TIMELINE
         */
        $this->data['eventos']=$this->projetos_model->getAllEventosProjeto($id,'data_inicio','asc');
        /*
         * GRÁFICO PIE - PRESIDENCIA
         */
        $this->data['areas_projeto'] =  $this->projetos_model->getAreasByProjeto($id);
        //GRÁFICO PIE - total_acoes_areas
        $this->data['total_acoes_areas'] =  $this->projetos_model->getAcoesTodasSuperintendenciaByProjeto($id);
        /*
         * PROJETO
         */
        $this->data['projeto_selecionado'] = $id;
        
       
        
        
        /*
         * PERFIL EDP
         */
        if($id_perfil_atual == 1){
        //Qtde de pessoas na equipe
            $equipe = $this->projetos_model->getEquipeByProjeto($id);
         $this->data['equipe'] =  $equipe->responsavel;
       
         //Qtde de AÇÕES
         $total_acoes =  $this->projetos_model->getQtdeAcoesByProjeto($id);
        $this->data['total_acoes'] = $total_acoes->total_acoes;
        //Qtde de Ações concluídas
        $concluido = $this->projetos_model->getStatusAcoesByProjeto($id, 'CONCLUÍDO');
        $this->data['concluido'] =  $concluido->status;
        //Qtde de ações Pendentes
        $pendente = $this->projetos_model->getAcoesPendentesByProjeto($id, 'PENDENTE');
        $avalidacao = $this->projetos_model->getAcoesAguardandoValidacaoByProjeto($id, 'AGUARDANDO VALIDAÇÃO');
        $this->data['pendente'] =  $pendente->pendente + $avalidacao->avalidacao;
        $atrasadas = $this->projetos_model->getAcoesAtrasadasByProjeto($id, 'PENDENTE');
        //Qtde de Ações Atrasadas
        $this->data['atrasadas'] =  $atrasadas->atrasadas;
        
        /* 
         * PEGA AS ÁREAS QUE TEM AÇÕES
         */
        // SE FOR SUPERINTENDENTE
        $this->data['areas_usuario_projeto'] = $this->projetos_model->getAreasByProjeto($id);
        //SE FOR GESTOR
        
        
         //GRÁFICO AÇOES NA LINHA DO TEMPO
        $this->data['acoes_tempo'] =  $this->projetos_model->getAllitemStatusPlanosLinhaTempo($id);
        
        
        /*
         * PERFIL DE GESTOR
         */
        }else  if($id_perfil_atual == 2){
         /*
          * GESTOR
          */   
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
          */
         $qtde_equipe_superintendencia = $this->projetos_model->getEquipeByProjetoSuperintendencia($id_perfil_atual,$id, $id_superintendencia);
         $soma_qtde_equipe_superintendencia += $qtde_equipe_superintendencia->responsavel; 
         /*
          * QUANTIDADE DE AÇÕES POR SETOR E PROJETO
          */
         $quantidade_acoes_superintendencia = $this->projetos_model->getQtdeAcoesByProjetoSuperintendencia($id_perfil_atual,$id,$id_superintendencia);
         $soma_qtde_acoes_superintendencia += $quantidade_acoes_superintendencia->total_acoes;
         /*
          * QUANTIDADE DE AÇÕES CONCLUÍDAS
          */
         $qtde_acoes_concluida_superintendencia = $this->projetos_model->getAcoesByProjetoSuperintendenciaStatus($id_perfil_atual, $id, 'CONCLUÍDO',$id_superintendencia);
         $soma_qtde_acoes_concluidas_superintendencia += $qtde_acoes_concluida_superintendencia->quantidade;
         /*
          * QUANTIDADE DE AÇÕES PENDENTES
          */
         $qtde_acoes_pendentes_superintendencia = $this->projetos_model->getAcoesByProjetoSuperintendenciaStatus($id_perfil_atual,$id, 'PENDENTE',$id_superintendencia);
         $soma_qtde_acoes_pendentes_superintendencia += $qtde_acoes_pendentes_superintendencia->quantidade;
         /*
          * QUANTIDADE DE AÇÕES AGUARDANDO VALIDAÇÃO
          */
         $qtde_acoes_aguardando_validacao_superintendencia = $this->projetos_model->getAcoesByProjetoSuperintendenciaStatus($id_perfil_atual, $id, 'AGUARDANDO VALIDAÇÃO',$id_superintendencia);
         $soma_qtde_acoes_avalidacao_superintendencia += $qtde_acoes_aguardando_validacao_superintendencia->quantidade;
        
         /*
          * QUANTIDADE DE AÇÕES ATRASADAS
          */
         $qtde_acoes_atrasadas_superintendencia = $this->projetos_model->getAcoesByProjetoSuperintendenciaStatus($id_perfil_atual,$id, 'ATRASADO',$id_superintendencia);
         $soma_qtde_acoes_atrasadas_superintendencia += $qtde_acoes_atrasadas_superintendencia->quantidade;
         /*
          * AÇÕES NA LINHA DO TEMPO
          */
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
        
        $this->data['areas_usuario_projeto'] =  $this->projetos_model->getSetoresByUsuarioProjeto($id,$usuario);
        
        
        
        
        /*
         * SUPERINTENDENTE
         */
        } if($id_perfil_atual == 3){
        
       /*
        * SUPERINTENDENCIAS LIGADA AO USUÁRIO
        */
         
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
          */
         $qtde_equipe_superintendencia = $this->projetos_model->getEquipeByProjetoSuperintendencia($id_perfil_atual,$id, $id_superintendencia);
         $soma_qtde_equipe_superintendencia += $qtde_equipe_superintendencia->responsavel; 
         /*
          * QUANTIDADE DE AÇÕES POR SUPERINTENDENCIA E PROJETO
          */
         $quantidade_acoes_superintendencia = $this->projetos_model->getQtdeAcoesByProjetoSuperintendencia($id_perfil_atual,$id,$id_superintendencia);
         $soma_qtde_acoes_superintendencia += $quantidade_acoes_superintendencia->total_acoes;
         /*
          * QUANTIDADE DE AÇÕES CONCLUÍDAS
          */
         $qtde_acoes_concluida_superintendencia = $this->projetos_model->getAcoesByProjetoSuperintendenciaStatus($id_perfil_atual, $id, 'CONCLUÍDO',$id_superintendencia);
         $soma_qtde_acoes_concluidas_superintendencia += $qtde_acoes_concluida_superintendencia->quantidade;
         /*
          * QUANTIDADE DE AÇÕES PENDENTES
          */
         $qtde_acoes_pendentes_superintendencia = $this->projetos_model->getAcoesByProjetoSuperintendenciaStatus($id_perfil_atual, $id, 'PENDENTE',$id_superintendencia);
         $soma_qtde_acoes_pendentes_superintendencia += $qtde_acoes_pendentes_superintendencia->quantidade;
         /*
          * QUANTIDADE DE AÇÕES AGUARDANDO VALIDAÇÃO
          */
         $qtde_acoes_aguardando_validacao_superintendencia = $this->projetos_model->getAcoesByProjetoSuperintendenciaStatus($id_perfil_atual, $id, 'AGUARDANDO VALIDAÇÃO',$id_superintendencia);
         $soma_qtde_acoes_avalidacao_superintendencia += $qtde_acoes_aguardando_validacao_superintendencia->quantidade;
         /*
          * QUANTIDADE DE AÇÕES ATRASADAS
          */
         $qtde_acoes_atrasadas_superintendencia = $this->projetos_model->getAcoesByProjetoSuperintendenciaStatus($id_perfil_atual, $id, 'ATRASADO',$id_superintendencia);
         $soma_qtde_acoes_atrasadas_superintendencia += $qtde_acoes_atrasadas_superintendencia->quantidade;
         /*
          * AÇÕES NA LINHA DO TEMPO
          */
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
        
        //data_atual
        $this->data['data_hoje'] = date('Y-m-d H:i:s');
        
       
            $name = lang("STATUS_REPORT") . "_" . str_replace('/', '_', $id) . ".pdf";
            $html = $this->load->view($this->theme . 'projetos/dashboard_pdf', $this->data, true);

        if ($view) {
            $this->load->view($this->theme . 'projetos/dashboard_pdf', $this->data);
           // redirect("Projetos/dashboard/".$id);
        } else{
            
            $this->sma->generate_pdf($html, $name, false, $this->session->userdata('user_id'));
        }
    }
    
    public function manutencao_acao_concluidos_lista_setores($id = null)
    {
     
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
            }
        
            $this->data['setore_selecionado'] = $setor;  
            $this->data['users'] = $this->site->getAllUser();
            $this->data['macro'] = $this->atas_model->getAllMacroProcesso();
            $this->data['projetos'] = $this->atas_model->getAllProjetos();      
            $this->data['idplano'] = $id;
            $this->data['acoes'] = $this->atas_model->getPlanoByID($id);
            $this->load->view($this->theme . 'projetos/manutencao_acao_concluidos_lista_setores', $this->data);
         
    }
    
    
    /*******************************************
     * ************E V E N T O S***************
     *****************************************/
    
    function Eventos_index()
    {

      $this->sma->checkPermissions();
      $usuario = $this->session->userdata('user_id');
      $projetos_usuario = $this->site->getProjetoAtualByID_completo($usuario);
      
     // exit;
      $this->data['eventos']=$this->projetos_model->getAllEventosProjeto($projetos_usuario->projeto_atual,'ordem', 'asc');
      $meta =  array('page_title' => lang('Eventos'), 'bc' => $bc);
      $this->page_construct('projetos/eventos/index', $meta, $this->data);

    }
    
     function Eventos_index_form()
    {
       $usuario = $this->session->userdata('user_id');
       $projetos_usuario = $this->site->getProjetoAtualByID_completo($usuario);
       $eventos = $this->projetos_model->getAllEventosProjeto($projetos_usuario->projeto_atual,'ordem', 'asc');
     
       foreach ($eventos as $evento) {
       $ordem = $this->input->post('ordem'.$evento->id);
       $tipo = $this->input->post('tipo'.$evento->id);
       $nome_evento = $this->input->post('nome_evento'.$evento->id);
       $data_inicio = $this->input->post('data_inicio'.$evento->id);
       $data_fim = $this->input->post('data_fim'.$evento->id);
     // echo $data_inicio.'<br>'; 
       
        $data_evento = array('ordem' => $ordem,
                             'tipo' => $tipo,
                             'data_inicio' => $data_inicio,
                             'data_fim' => $data_fim,
                             'nome_evento' => $nome_evento);
        $this->projetos_model->updateEvento($evento->id,$data_evento);
       }
       
     //  exit;
       
       redirect("Projetos/Eventos_index");
       
     }
    
    public function add_evento()
    {
        $this->sma->checkPermissions();

        $this->form_validation->set_rules('nome_evento', lang("Nome do Projeto"), 'required');
        $this->form_validation->set_rules('dateInicial', lang("Data Inicial"), 'required');
        $this->form_validation->set_rules('dateFim', lang("Data Final"), 'required');
        $this->form_validation->set_rules('responsavel_tecnico', lang("Responsavel Técnico"), 'required');
        $this->form_validation->set_rules('responsavel_edp', lang("Responsavel EDP"), 'required');
        $this->form_validation->set_rules('responsavel_area', lang("Responsavel da Área"), 'required');
        $date_cadastro = date('Y-m-d H:i:s');               
        
        if ($this->form_validation->run() == true) {
           $dt_inicio_projeto = $this->input->post('data_inicio_projeto');
           $dt_fim_projeto = $this->input->post('data_fim_projeto');
           
            $projeto = $this->input->post('projeto');
            
            $nome_evento = $this->input->post('nome_evento');
           // $dataInicial = $this->sma->fld(trim($this->input->post('dateInicial'))); 
            //$dataFinal = $this->sma->fld(trim($this->input->post('dateFim'))); 
            $dataInicial = $this->input->post('dateInicial'); 
            $dataFinal = $this->input->post('dateFim'); 
            $responsavel_tecnico = $this->input->post('responsavel_tecnico');
            $responsavel_edp = $this->input->post('responsavel_edp');
            $responsavel_area = $this->input->post('responsavel_area');
            $setores = $this->input->post('setores');
            $modulos = $this->input->post('modulos');
            $note = $this->input->post('note');
            $tipo = $this->input->post('tipo');
            $data_criacao = $date_cadastro;
            $usuario = $this->session->userdata('user_id');//
            //$this->site->getUser($this->session->userdata('user_id'));
            $data_evento = array('projeto' => $projeto,
                'data_inicio' => $dataInicial,
                'data_fim' => $dataFinal,
                'nome_evento' => $nome_evento,
                'responsavel' => $responsavel_tecnico,
                'responsavel_edp' => $responsavel_edp ,
                'responsavel_area' => $responsavel_area,
                'tipo' => $tipo,
                'observacoes' => $note,
                'data_cadastro' => $data_criacao,   
                'usuario' => $usuario
              
            );
            
            /*
             * SE A DATA DE INÍCIO DO EVENTO FOR ANTES DO INÍCIO DO PROJETO
             */
           if($dt_inicio_projeto > $dataInicial){
               
            $this->session->set_flashdata('warning', lang("A data de início do Evento não pode iniciar antes do início do Projeto!"));
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
           
            $usuario = $this->session->userdata('user_id');
            $projetos_usuario = $this->site->getProjetoAtualByID_completo($usuario);
            $this->data['projetos'] = $this->atas_model->getProjetoByID($projetos_usuario->projeto_atual);
            
            $this->data['users'] = $this->site->getAllUser();
            $this->data['setores'] = $this->site->getAllsetores();
            $this->data['modulos'] = $this->site->getAllModulos($projetos_usuario->projeto_atual);
            $bc = array(array('link' => base_url(), 'page' => lang('Eventos')), array('link' => site_url('projetos/eventos/add'), 'page' => lang('Novo Evento')));
            $meta = array('page_title' => lang('Novo Projeto'), 'bc' => $bc);
            $this->page_construct('projetos/eventos/add', $meta, $this->data);
         
           }else 
               /*
             * SE A DATA DO FIM DO EVENTO FOR APÓS O TÉRMINO DO PROJETO
             */
               if($dt_fim_projeto < $dataFinal){
               $this->session->set_flashdata('warning', lang("A data do fim do Evento não pode terminar após o término do Projeto!"));
             
                $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
           
            $usuario = $this->session->userdata('user_id');
            $projetos_usuario = $this->site->getProjetoAtualByID_completo($usuario);
            $this->data['projetos'] = $this->atas_model->getProjetoByID($projetos_usuario->projeto_atual);
            
            $this->data['users'] = $this->site->getAllUser();
            $this->data['setores'] = $this->site->getAllsetores();
            $this->data['modulos'] = $this->site->getAllModulos($projetos_usuario->projeto_atual);
            
            $bc = array(array('link' => base_url(), 'page' => lang('Eventos')), array('link' => site_url('projetos/eventos/add'), 'page' => lang('Novo Evento')));
            $meta = array('page_title' => lang('Novo Projeto'), 'bc' => $bc);
            $this->page_construct('projetos/eventos/add', $meta, $this->data);
            
           }else{
               
        
            
            $this->projetos_model->addEventos($data_evento,$modulos,$setores);
            $this->session->set_flashdata('message', lang("Evento Criado com Sucesso!!!"));
            
             redirect("Projetos/Eventos_index");
               
           }
           
            
        } else {

            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
           
            $usuario = $this->session->userdata('user_id');
            $projetos_usuario = $this->site->getProjetoAtualByID_completo($usuario);
            $this->data['projetos'] = $this->atas_model->getProjetoByID($projetos_usuario->projeto_atual);
            
            $this->data['providers'] = $this->site->getAllCompanies('supplier');
            $this->data['users'] = $this->site->getAllUser();
            $this->data['setores'] = $this->site->getAllsetores();
            $this->data['modulos'] = $this->site->getAllModulos($projetos_usuario->projeto_atual);
            $bc = array(array('link' => base_url(), 'page' => lang('Eventos')), array('link' => site_url('projetos/eventos/add'), 'page' => lang('Novo Evento')));
            $meta = array('page_title' => lang('Novo Projeto'), 'bc' => $bc);
            $this->page_construct('projetos/eventos/add', $meta, $this->data);
        }
    }
    
    public function edit_evento($id = null)
    {
        $this->sma->checkPermissions();
      
       $this->form_validation->set_rules('nome_evento', lang("Nome do Projeto"), 'required');
        $this->form_validation->set_rules('dateInicial', lang("Data Inicial"), 'required');
        $this->form_validation->set_rules('dateFim', lang("Data Final"), 'required');
        $this->form_validation->set_rules('responsavel_tecnico', lang("Responsavel Técnico"), 'required');
        $this->form_validation->set_rules('responsavel_edp', lang("Responsavel EDP"), 'required');
        $this->form_validation->set_rules('responsavel_area', lang("Responsavel da Área"), 'required');
        $date_cadastro = date('Y-m-d H:i:s');                           
        
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        
          if ($this->form_validation->run() == true) {
            $id = $this->input->post('id');  
            $dt_inicio_projeto = $this->input->post('data_inicio_projeto');
            $dt_fim_projeto = $this->input->post('data_fim_projeto');
           
            $projeto = $this->input->post('projeto');
            $nome_evento = $this->input->post('nome_evento');
            $dataInicial = $this->input->post('dateInicial'); 
            $dataFinal = $this->input->post('dateFim'); 
            
           // $dataInicial = $this->input->post('dateInicial'); 
           // $dataFinal = $this->input->post('dateFim'); 
            $responsavel_tecnico = $this->input->post('responsavel_tecnico');
            $responsavel_edp = $this->input->post('responsavel_edp');
            $responsavel_area = $this->input->post('responsavel_area');
            $setores = $this->input->post('setores');
            $modulos = $this->input->post('modulos');
            $note = $this->input->post('note');
            $tipo = $this->input->post('tipo');
            $data_criacao = $date_cadastro;
            $usuario = $this->session->userdata('user_id');//
            //$this->site->getUser($this->session->userdata('user_id'));
            $data_evento = array('projeto' => $projeto,
                'data_inicio' => $dataInicial,
                'data_fim' => $dataFinal,
                'nome_evento' => $nome_evento,
                'responsavel' => $responsavel_tecnico,
                'responsavel_edp' => $responsavel_edp ,
                'responsavel_area' => $responsavel_area,
                'observacoes' => $note,
                'tipo' => $tipo,
                'data_ultima_alteracao' => $data_criacao,   
                'usuario_alteracao' => $usuario
                
            );
            
            
              /*
             * SE A DATA DE INÍCIO DO EVENTO FOR ANTES DO INÍCIO DO PROJETO
             */
           if($dt_inicio_projeto > $dataInicial){
               
            $this->session->set_flashdata('warning', lang("A data de início do Evento não pode iniciar antes do início do Projeto!"));
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
           
            $this->data['id'] = $id;
            $this->data['evento'] = $this->projetos_model->getEventoByID($id);
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $usuario = $this->session->userdata('user_id');
            $projetos_usuario = $this->site->getProjetoAtualByID_completo($usuario);
            $this->data['projetos'] = $this->atas_model->getProjetoByID($projetos_usuario->projeto_atual);
            $this->data['users'] = $this->site->getAllUser();
            $this->data['setores'] = $this->site->getAllsetores();
            $this->data['setores_eventos'] = $this->site->getAllsetoresEento($id);
            $this->data['modulos_eventos'] = $this->site->getAllModulosEento($id);
            $this->data['modulos'] = $this->site->getAllModulos($projetos_usuario->projeto_atual);

            $bc = array(array('link' => base_url(), 'page' => lang('Eventos')), array('link' => '#', 'page' => lang('Editar Evento')));
            $meta = array('page_title' => lang('Editar Evento'), 'bc' => $bc);
            $this->page_construct('projetos/eventos/edit', $meta, $this->data);
         
           }else 
               /*
             * SE A DATA DO FIM DO EVENTO FOR APÓS O TÉRMINO DO PROJETO
             */
               if($dt_fim_projeto < $dataFinal){
            $this->session->set_flashdata('warning', lang("A data do fim do Evento não pode terminar após o término do Projeto!"));
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
           
            $this->data['id'] = $id;
            $this->data['evento'] = $this->projetos_model->getEventoByID($id);
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $usuario = $this->session->userdata('user_id');
            $projetos_usuario = $this->site->getProjetoAtualByID_completo($usuario);
            $this->data['projetos'] = $this->atas_model->getProjetoByID($projetos_usuario->projeto_atual);
            $this->data['users'] = $this->site->getAllUser();
            $this->data['setores'] = $this->site->getAllsetores();
            $this->data['setores_eventos'] = $this->site->getAllsetoresEento($id);
            $this->data['modulos_eventos'] = $this->site->getAllModulosEento($id);
            $this->data['modulos'] = $this->site->getAllModulos($projetos_usuario->projeto_atual);

            $bc = array(array('link' => base_url(), 'page' => lang('Eventos')), array('link' => '#', 'page' => lang('Editar Evento')));
            $meta = array('page_title' => lang('Editar Evento'), 'bc' => $bc);
            $this->page_construct('projetos/eventos/edit', $meta, $this->data);
            
           }else{
               
             
            
            $this->projetos_model->updateEvento($id,$data_evento,$modulos,$setores);
            $this->session->set_flashdata('message', lang("Evento Alterado com Sucesso!!!"));
            
             redirect("Projetos/Eventos_index");
               
           }
           
            
        } else {

            $this->data['id'] = $id;
          
            $this->data['evento'] = $this->projetos_model->getEventoByID($id);
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $usuario = $this->session->userdata('user_id');
            $projetos_usuario = $this->site->getProjetoAtualByID_completo($usuario);
            $this->data['projetos'] = $this->atas_model->getProjetoByID($projetos_usuario->projeto_atual);
            $this->data['users'] = $this->site->getAllUser();
            $this->data['setores'] = $this->site->getAllsetores();
            $this->data['setores_eventos'] = $this->site->getAllsetoresEento($id);
            $this->data['modulos_eventos'] = $this->site->getAllModulosEento($id);
            $this->data['modulos'] = $this->site->getAllModulos($projetos_usuario->projeto_atual);

            $bc = array(array('link' => base_url(), 'page' => lang('Eventos')), array('link' => '#', 'page' => lang('Editar Evento')));
            $meta = array('page_title' => lang('Editar Evento'), 'bc' => $bc);
            $this->page_construct('projetos/eventos/edit', $meta, $this->data);
            
           

            }
    }
    
     public function delete_eventos($id = null)
    {
        $this->sma->checkPermissions(null, true);

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

       
        if ($this->projetos_model->deleteEvento($id)) {
           
            
            $this->session->set_flashdata('message', lang('Evento Apagado'));
            redirect('Projetos/Eventos_index');
        }else{
           $this->session->set_flashdata('message', lang('Verificar os cadastros ligados a este evento: Setores, Módulos e Ações'));
            redirect('Projetos/Eventos_index');
        }
    }
    
    
     /*******************************************
     * ************I T E N S DO E V E N T O S***************
     *****************************************/
    
    function Item_evento_index($id = null)
    {
      $this->sma->checkPermissions();

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
      $usuario = $this->session->userdata('user_id');
    
      $projetos_usuario = $this->site->getProjetoAtualByID_completo($usuario);
      $this->data['id'] = $id;
      $this->data['eventos']=$this->projetos_model->getAllItemEventosProjeto($id);
        
      $meta =  array('page_title' => lang('Eventos'), 'bc' => $bc);
      $this->page_construct('projetos/item_evento/index', $meta, $this->data);

    }
    
    function Item_eventos_index_form()
    {
     
       $id = $this->input->post('id');
       $eventos = $this->projetos_model->getAllItemEventosProjeto($id);
     
       foreach ($eventos as $evento) {
     
       $descricao = $this->input->post('descricao'.$evento->id);
       $data_inicio = $this->input->post('data_inicio'.$evento->id);
       $data_fim = $this->input->post('data_fim'.$evento->id);
       $horas = $this->input->post('horas'.$evento->id);
     // echo $data_inicio.'<br>'; 
       
        $data_evento = array('dt_inicio' => $data_inicio,
                             'dt_fim' => $data_fim,
                             'descricao' => $descricao,
                             'horas_previstas' => $horas);
       // print_r($data_evento);
       // echo '<br>';
        $this->projetos_model->updateItemEvento($evento->id,$data_evento);
       }
       
       //exit;
       
       redirect("Projetos/Item_evento_index/$id");
       
     }
    
    public function add_item_evento($id = null)
    {
        $this->sma->checkPermissions();
        
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        
       

          
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $evento = $this->input->post('evento');
            $usuario = $this->session->userdata('user_id');
            $projetos_usuario = $this->site->getProjetoAtualByID_completo($usuario);
            //$this->data['projetos'] = $this->atas_model->getProjetoByID($projetos_usuario->projeto_atual);
            $this->data['id'] = $id;
            $this->data['projetos'] =  $this->projetos_model->getEventoByID($id);
            $this->data['providers'] = $this->site->getAllCompanies('supplier');
            $this->data['users'] = $this->site->getAllUser();
            $this->data['setores'] = $this->site->getAllsetores();
            $this->data['modulos'] = $this->site->getAllModulos($projetos_usuario->projeto_atual);
            $bc = array(array('link' => base_url(), 'page' => lang('Eventos')), array('link' => site_url('projetos/eventos/add'), 'page' => lang('Novo Evento')));
            $meta = array('page_title' => lang('Novo Projeto'), 'bc' => $bc);
            //$this->page_construct('projetos/item_evento/add', $meta, $this->data);
            $this->load->view($this->theme . 'projetos/item_evento/add', $this->data);
        
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
    
    
    public function edit_item_evento($id = null, $evento = null)
    {
        $this->sma->checkPermissions();
      
      $this->form_validation->set_rules('descricao', lang("Descrição"), 'required');
        $this->form_validation->set_rules('dateInicial', lang("Data Inicial"), 'required');
        $this->form_validation->set_rules('dateFim', lang("Data Final"), 'required');
        $date_cadastro = date('Y-m-d H:i:s');                           
        
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        
          if ($this->form_validation->run() == true) {
            $id = $this->input->post('id');  
            
            $evento = $this->input->post('evento');
            $descricao = $this->input->post('descricao');
             $dataInicial = $this->input->post('dateInicial'); 
            $dataFinal = $this->input->post('dateFim'); 
            
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
               
             
         //   echo 'to aqui'.$evento; exit;
            
            $this->projetos_model->updateItemEvento($id,$data_evento);
            $this->session->set_flashdata('message', lang("Item do Evento Alterado com Sucesso!!!"));
            
             redirect("Projetos/Item_evento_index/".$evento);
               
           
           
            
        } else {

            $this->data['id'] = $id;
           // $this->data['evento'] = $evento;
            // $this->data['projetos'] = $this->projetos_model->getEventoByID($evento);
           // $this->data['evento'] = $this->atas_model->getItemEventoByID($id);
            
            $this->data['evento'] = $this->projetos_model->getItemEventoByID($id);
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $usuario = $this->session->userdata('user_id');
            $projetos_usuario = $this->site->getProjetoAtualByID_completo($usuario);
            $this->data['projetos'] =  $this->projetos_model->getEventoByID($evento);
            $this->data['users'] = $this->site->getAllUser();
            $this->data['setores'] = $this->site->getAllsetores();
            $this->data['setores_eventos'] = $this->site->getAllsetoresEento($id);
            $this->data['modulos_eventos'] = $this->site->getAllModulosEento($id);
            $this->data['modulos'] = $this->site->getAllModulos($projetos_usuario->projeto_atual);

            $bc = array(array('link' => base_url(), 'page' => lang('Eventos')), array('link' => '#', 'page' => lang('Editar Evento')));
            $meta = array('page_title' => lang('Editar Evento'), 'bc' => $bc);
            $this->page_construct('projetos/item_evento/edit', $meta, $this->data);
            
           

            }
    }
    
     public function delete_item_eventos($id = null, $evento)
    {
        $this->sma->checkPermissions(null, true);

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        
            $this->projetos_model->deleteItemEvento($id);
            
            $this->session->set_flashdata('message', lang('Item Evento Apagado'));
            redirect('Projetos/Item_evento_index/'.$evento);
        
    }
    
    
     public function replica_item_evento($evento = null)
    {
      $this->sma->checkPermissions();
      
        $this->form_validation->set_rules('evento', lang("evento"), 'required');
        
        $date_cadastro = date('Y-m-d H:i:s');                           
        
        if ($this->input->get('id')) {
            $evento = $this->input->get('id');
        }
        
          if ($this->form_validation->run() == true) {
           
            $evento_selecionado = $this->input->post('evento');
            
            
             $eventos = $this->input->post('eventos');
             foreach ($eventos as $evento) {
                
                
                          
                  $itens_evento = $this->projetos_model->getAllItemEventosProjeto($evento_selecionado);
                 foreach ($itens_evento as $item_evento) {
                   $descricao = $item_evento->descricao;
                   $data_inicio = $item_evento->dt_inicio;
                   $data_fim = $item_evento->dt_fim;
                   $hora_prevista = $item_evento->horas_previstas;
                   
                   
                     $data_evento = array(
                    'dt_inicio' => $data_inicio,
                    'dt_fim' => $data_fim,
                    'horas_previstas' => $hora_prevista,
                    'evento' => $evento,
                    'descricao' => $descricao
                );
                     
                     
                  $this->projetos_model->addItensventos($data_evento);   
              }
                      
             }
                
            $this->session->set_flashdata('message', lang("Item do Evento replicado com Sucesso!!!"));
            
             redirect("Projetos/Eventos_index/");
            
        } else {

            
            $this->data['evento'] = $evento;
      
            
           $this->load->view($this->theme . 'projetos/item_evento/replica', $this->data);

            }
    }
    
    /*****************************************************
     *********GESTÃO DE DOCUMENTAÇÃO DO PROJETO ************ 
     ******************************************************/
    
    function gestao_documentacao_index()
    {

      $this->sma->checkPermissions();
      $usuario = $this->session->userdata('user_id');
      $projetos_usuario = $this->site->getProjetoAtualByID_completo($usuario);
      
     // exit;
      $this->data['eventos']=$this->projetos_model->getAllDocumentacao($projetos_usuario->projeto_atual);
      $meta =  array('page_title' => lang('Marcos'), 'bc' => $bc);
      $this->page_construct('projetos/documentacao/index', $meta, $this->data);

    }
    
    public function add_documentacao()
    {
        $this->sma->checkPermissions();

        
        $date_cadastro = date('Y-m-d H:i:s');               
        
         $this->form_validation->set_rules('title', lang("title"), 'trim|required');
      
        if ($this->form_validation->run() == true) {
            $usuario = $this->session->userdata('user_id');
            $projetos_usuario = $this->site->getProjetoAtualByID_completo($usuario);
            
            $data = array(
                'nome_documento' => $this->input->post('title'),
                'status' => "RASCUNHO",
                'data_criacao' => $date_cadastro,
                'quem_criou' => $this->session->userdata('user_id'),
                'projeto' => $projetos_usuario->projeto_atual
                //'versao' => '00'
                );
            //print_r($data);
           // exit;
            if ($this->projetos_model->addDocumento($data)) {
                $res = array('error' => 0, 'msg' => lang('Documentação Criada'));
                 redirect("Projetos/gestao_documentacao_index");
            } else {
                $res = array('error' => 1, 'msg' => lang('action_failed'));
                $this->sma->send_json($res);
            }
            
        }else {

            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
           
            $usuario = $this->session->userdata('user_id');
            $projetos_usuario = $this->site->getProjetoAtualByID_completo($usuario);
            $this->data['projetos'] = $this->atas_model->getProjetoByID($projetos_usuario->projeto_atual);
            
             $this->load->view($this->theme . 'projetos/documentacao/add', $this->data);
        }
    }
    
    public function concluir_documentacao($id = null)
    {
        $this->sma->checkPermissions();
        $date_cadastro = date('Y-m-d H:i:s');               
        $this->form_validation->set_rules('elaborado', lang("Quem Elaborou"), 'trim|required');
      
        if ($this->form_validation->run() == true) {
            $usuario = $this->session->userdata('user_id');
            $projetos_usuario = $this->site->getProjetoAtualByID_completo($usuario);
            $id = $this->input->post('id');
            $data = array(
                'quem_elaborou' => $this->input->post('elaborado'),
                'status' => "EM APROVAÇÃO",
                'versao' => "00",
                'data_finalizacao' => $date_cadastro,
                'quem_assinou' => $this->input->post('aprovado'),
                'revisado_por' => $this->input->post('revisado')
              
               
                );
            
            
            if ($this->projetos_model->updateDocumentacao($id, $data)) {
                $res = array('error' => 0, 'msg' => lang('Documentação Criada'));
                 redirect("Projetos/gestao_documentacao_index");
            } else {
                $res = array('error' => 1, 'msg' => lang('action_failed'));
                $this->sma->send_json($res);
            }
            
        }else {

            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['id'] = $id;
            $this->data['documentacao'] = $this->projetos_model->getDocumentacaoByID($id);
            
            $usuario = $this->session->userdata('user_id');
            $projetos_usuario = $this->site->getProjetoAtualByID_completo($usuario);
            $this->data['projetos'] = $this->atas_model->getProjetoByID($projetos_usuario->projeto_atual);
            
            // $bc = array(array('link' => base_url('index.php/projetos/tap/'.$id), 'page' => lang('GESTÃO DE DOCUMENTAÇÃO')), array('link' => '#', 'page' => lang('Concluir Documentação')));
           // $meta = array('page_title' => lang('TAP'), 'bc' => $bc);
           // $this->page_construct('projetos/documentacao/concluir', $meta, $this->data);
             $this->load->view($this->theme . 'projetos/documentacao/concluir', $this->data);
        }
    }
    
    public function anexar_documentacao($id = null)
    {
        $this->sma->checkPermissions();
        $date_cadastro = date('Y-m-d H:i:s');               
        $this->form_validation->set_rules('id', lang("Documento"), 'trim|required');
     
        if ($this->form_validation->run() == true) {
            $usuario = $this->session->userdata('user_id');
            $projetos_usuario = $this->site->getProjetoAtualByID_completo($usuario);
            $id = $this->input->post('id');
            
            $data = array(
     
                'status' => "FINALIZADO"
              
               
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
                $data['anexo'] = $photo;
            }
     
            if ($this->projetos_model->updateDocumentacao($id, $data)) {
                $res = array('error' => 0, 'msg' => lang('Documentação Anexado'));
                 redirect("Projetos/gestao_documentacao_index");
            } else {
                $res = array('error' => 1, 'msg' => lang('action_failed'));
                $this->sma->send_json($res);
            }
            
        }else {

            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['id'] = $id;
            $this->data['documentacao'] = $this->projetos_model->getDocumentacaoByID($id);
            
            $usuario = $this->session->userdata('user_id');
            $projetos_usuario = $this->site->getProjetoAtualByID_completo($usuario);
            $this->data['projetos'] = $this->atas_model->getProjetoByID($projetos_usuario->projeto_atual);
            
            // $bc = array(array('link' => base_url('index.php/projetos/tap/'.$id), 'page' => lang('GESTÃO DE DOCUMENTAÇÃO')), array('link' => '#', 'page' => lang('Concluir Documentação')));
           // $meta = array('page_title' => lang('TAP'), 'bc' => $bc);
           // $this->page_construct('projetos/documentacao/concluir', $meta, $this->data);
             $this->load->view($this->theme . 'projetos/documentacao/anexar', $this->data);
        }
    }
    
   
    
    /*****************************************************
     *********SESSOES DE DOCUMENTAÇÃO DO PROJETO ************ 
     ******************************************************/
    
     public function tap($id = null)
    {
        $this->sma->checkPermissions();
      
       $this->form_validation->set_rules('nome_projeto', lang("Nome do Projeto"), 'required');
        $this->form_validation->set_rules('dateInicial', lang("Data Inicial"), 'required');
        $this->form_validation->set_rules('dateFim', lang("Data Final"), 'required');
        $this->form_validation->set_rules('gerenteArea', lang("Gerente da Área"), 'required');
      //  $this->form_validation->set_rules('status', lang("Status"), 'required');
        $date_cadastro = date('Y-m-d H:i:s');               
        
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        
          if ($this->form_validation->run() == true) {
           
            $nome = $this->input->post('nome_projeto');
            $dataInicial = $this->sma->fld(trim($this->input->post('dateInicial'))); 
            $dataFinal = $this->sma->fld(trim($this->input->post('dateFim'))); 
            $dataVirada = $this->sma->fld(trim($this->input->post('dateVirada'))); 
            $gerenteArea = $this->input->post('gerenteArea');
            $gerenteEDP = $this->input->post('gerenteEDP');
          //  $gerenteFornecedor = $this->input->post('gerenteFornecedor');
            $cor = $this->input->post('cor');
          //  $status = $this->input->post('status');
          //  $note = $this->input->post('note');
            $data_criacao = $date_cadastro;
            $usuario = $this->session->userdata('user_id');//
            $id_projeto = $this->input->post('id');
            
            //$this->site->getUser($this->session->userdata('user_id'));
            $data_projeto = array('projeto' => $nome,
                'dt_inicio' => $dataInicial,
                'dt_final' => $dataFinal,
                'dt_virada' => $dataVirada,
                'gerente_area' => $gerenteArea,
                'gerente_edp' => $gerenteEDP ,
           //     'gerente_fornecedor' => $gerenteFornecedor,
            //    'status' => $status,
                'botao' => $cor,
            //    'obs' => $note,
                'data_criacao' => $data_criacao,   
                'usuario' => $usuario
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
                $data_projeto['anexo'] = $photo;
            }
            
         //print_r($data_projeto); exit;
            
            $this->projetos_model->updateProjeto($id_projeto, $data_projeto);
            
            $this->session->set_flashdata('message', lang("Projeto Alterado com Sucesso!!!"));
            redirect("projetos");
            
        } else {

            $this->data['id'] = $id;
           
            $this->data['documentacao'] = $this->projetos_model->getDocumentacaoByID($id);
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['taps'] = $this->projetos_model->getTapByProjeto($id);
            $this->data['users'] = $this->site->getAllUser();

            $bc = array(array('link' => base_url(), 'page' => lang('Projetos')), array('link' => '#', 'page' => lang('Editar Documentação')));
            $meta = array('page_title' => lang('edit_sale'), 'bc' => $bc);
            $this->page_construct('projetos/tap', $meta, $this->data);
            
           

            }
    }
    
    public function add_tap_sessao($id = null)
    {
        $this->sma->checkPermissions();

        
        $date_cadastro = date('Y-m-d H:i:s');               
        
        $this->form_validation->set_rules('title', lang("title"), 'trim|required');
        //$this->form_validation->set_rules('start', lang("start"), 'required');

        if ($this->form_validation->run() == true) {
            $usuario = $this->session->userdata('user_id');
            $projetos_usuario = $this->site->getProjetoAtualByID_completo($usuario);
            $id = $this->input->post('id');
            $titulo = $this->input->post('title');
            $descricao = $this->input->post('descricao');
            $altura = $this->input->post('altura');
            $largura = $this->input->post('largura');
           
            $data = array(
                'titulo' => $titulo,
                'documentacao' => $id,
                'descricao' => $descricao,
                'user_id' => $this->session->userdata('user_id'),
                'data_registro' => $date_cadastro,
                 'altura' => $altura,
                'largura' => $largura
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
                $data['anexo'] = $photo;
            }
            
              $this->projetos_model->addTap($data);
              redirect("Projetos/tap/".$id);
            
        }else {

            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
           
            $usuario = $this->session->userdata('user_id');
            $projetos_usuario = $this->site->getProjetoAtualByID_completo($usuario);
            $this->data['projetos'] = $this->atas_model->getProjetoByID($projetos_usuario->projeto_atual);
           
           //  $this->data['projeto'] = $projetos_usuario->projeto_atual;
            $this->data['id'] = $id;
            $bc = array(array('link' => base_url('index.php/projetos/tap/'.$id), 'page' => lang('TAP')), array('link' => '#', 'page' => lang('Nova Sessão da TAP')));
            $meta = array('page_title' => lang('TAP'), 'bc' => $bc);
            $this->page_construct('projetos/tap/nova_sessao', $meta, $this->data);
        }
    }
    
    public function edit_tap_sessao($id = null, $documentacao = null)
    {
        $this->sma->checkPermissions();

        
        $date_cadastro = date('Y-m-d H:i:s');               
        
        $this->form_validation->set_rules('title', lang("title"), 'trim|required');
        //$this->form_validation->set_rules('start', lang("start"), 'required');

        if ($this->form_validation->run() == true) {
            $usuario = $this->session->userdata('user_id');
            $documentacao = $this->input->post('documentacao');
            $id = $this->input->post('id');
            $titulo = $this->input->post('title');
            $descricao = $this->input->post('descricao');
            $altura = $this->input->post('altura');
            $largura = $this->input->post('largura');
           
            $data = array(
                'titulo' => $titulo,
             //   'projeto' => $id,
                'descricao' => $descricao,
                'altura' => $altura,
                'largura' => $largura
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
                $data['anexo'] = $photo;
            }
            
              $this->projetos_model->updateTap($id, $data);
              redirect("Projetos/tap/".$documentacao);
            
        }else {

            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
           
            $usuario = $this->session->userdata('user_id');
            $projetos_usuario = $this->site->getProjetoAtualByID_completo($usuario);
             $this->data['documentacao'] = $documentacao;
            $this->data['tap'] = $this->projetos_model->getTapbyId($id);
            $this->data['id'] = $id;
            
            $bc = array(array('link' => base_url('index.php/projetos/tap/'.$id), 'page' => lang('TAP')), array('link' => '#', 'page' => lang('Editar Sessão')));
            $meta = array('page_title' => lang('TAP'), 'bc' => $bc);
            $this->page_construct('projetos/tap/edit_sessao', $meta, $this->data);
        }
    }
    
     public function delete_tap($id = null, $projeto = null)
    {
        $this->sma->checkPermissions(null, true);

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        if ($this->projetos_model->deleteTap($id)) {
            if ($this->input->is_ajax_request()) {
                echo lang("Sessão Apagada");die();
            }
            $this->session->set_flashdata('message', lang('Sessão Apagada'));
            redirect("Projetos/tap/".$projeto);
        }
    }
    
    public function ver_tap($id = null, $view = null)
    {
        $this->sma->checkPermissions();

        
          

            $this->data['id'] = $id;
           
            $this->data['documentacao'] = $this->projetos_model->getDocumentacaoByID($id);
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['taps'] = $this->projetos_model->getTapByProjeto($id);
            $this->data['users'] = $this->site->getAllUser();
            
            $usuario = $this->session->userdata('user_id');
            $projetos_usuario = $this->site->getProjetoAtualByID_completo($usuario);
            
            
            $documentacao = $this->projetos_model->getDocumentacaoByID($id);
            $projeto_doc = $documentacao->projeto;
            
            
           // $bc = array(array('link' => base_url(), 'page' => lang('Projetos')), array('link' => '#', 'page' => lang('Editar Projeto')));
           // $meta = array('page_title' => lang('edit_sale'), 'bc' => $bc);
            //$this->page_construct('projetos/tap', $meta, $this->data);
            $name = lang($documentacao->nome_documento) . "_" . str_replace('/', '_', $projetos_usuario->projeto) . ".pdf";
            $html = $this->load->view($this->theme . 'tap', $this->data, true);
           
            
            
            $dados_projeto = $this->projetos_model->getProjetoByID($projeto_doc);
            $logo_doc_top =  $dados_projeto->logo_doc_top;
            $logo_doc_bottom =  $dados_projeto->logo_doc_bottom;
            //echo $logo_doc_top;
            //exit;
            
        if ($view) {
           $this->load->view($this->theme . 'tap', $this->data);
        } else{
        
           /* 
            $data = array(
                'quem_elaborou' => $this->input->post('elaborado'),
                'status' => "FINALIZADO",
                'versao' => "00",
                'data_finalizacao' => $date_cadastro,
                'revisado_por' => $this->input->post('revisado')
              
               
                );
            
            
            $this->projetos_model->updateDocumentacao($id, $data);
            
            * 
            */
            $documentacao = $this->projetos_model->getDocumentacaoByID($id);
            $usuario = $this->session->userdata('user_id');
            $res_assinar = $this->site->geUserByID($usuario);
            $nome_emitiu = $res_assinar->first_name.' '.$res_assinar->last_name;
            
            $this->sma->generate_pdf($html, $name, false, null, null, null, null, null, $logo_doc_top, $logo_doc_bottom,$nome_emitiu,$documentacao);
        }

           
    }
    
    
    
    
    /*******************************************
     * ************MARCOS DO PROJETO***************
     *****************************************/
    
    function Marcos_eventos_index()
    {

      $this->sma->checkPermissions();
      $usuario = $this->session->userdata('user_id');
      $projetos_usuario = $this->site->getProjetoAtualByID_completo($usuario);
      
     // exit;
      $this->data['eventos']=$this->projetos_model->getAllMarcosProjetoByProjeto($projetos_usuario->projeto_atual,'start', 'asc');
      $meta =  array('page_title' => lang('Marcos'), 'bc' => $bc);
      $this->page_construct('projetos/marcos/index', $meta, $this->data);

    }
    
    public function add_marco()
    {
        $this->sma->checkPermissions();

        
        $date_cadastro = date('Y-m-d H:i:s');               
        
        $this->form_validation->set_rules('title', lang("title"), 'trim|required');
        $this->form_validation->set_rules('start', lang("start"), 'required');

        if ($this->form_validation->run() == true) {
            $usuario = $this->session->userdata('user_id');
            $projetos_usuario = $this->site->getProjetoAtualByID_completo($usuario);
            $data = array(
                'title' => $this->input->post('title'),
                'start' => $this->sma->fld($this->input->post('start')),
                'end' => $this->input->post('end') ? $this->sma->fld($this->input->post('end')) : NULL,
                'description' => $this->input->post('description'),
                'color' => $this->input->post('color') ? $this->input->post('color') : '#000000',
                'user_id' => $this->session->userdata('user_id'),
                'projeto' => $projetos_usuario->projeto_atual,
                'data_registro' => $date_cadastro
                );

           // print_r($data); exit;
            if ($this->calendar_model->addEvent($data)) {
                $res = array('error' => 0, 'msg' => lang('event_added'));
                 redirect("Projetos/Marcos_eventos_index");
            } else {
                $res = array('error' => 1, 'msg' => lang('action_failed'));
                $this->sma->send_json($res);
            }
            
        }else {

            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
           
            $usuario = $this->session->userdata('user_id');
            $projetos_usuario = $this->site->getProjetoAtualByID_completo($usuario);
            $this->data['projetos'] = $this->atas_model->getProjetoByID($projetos_usuario->projeto_atual);
            
             $this->load->view($this->theme . 'projetos/marcos/add_marco', $this->data);
        }
    }
    
    public function edit_marco($id = null)
    {
        $this->sma->checkPermissions();
      
       
        $date_cadastro = date('Y-m-d H:i:s');                           
        
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
       
         $this->form_validation->set_rules('title', lang("title"), 'trim|required');
        $this->form_validation->set_rules('start', lang("start"), 'required');

        if ($this->form_validation->run() == true) {
           
           
            $data = array(
                'title' => $this->input->post('title'),
                'start' => $this->sma->fld($this->input->post('start')),
                'end' => $this->input->post('end') ? $this->sma->fld($this->input->post('end')) : NULL,
                'description' => $this->input->post('description'),
                'color' => $this->input->post('color') ? $this->input->post('color') : '#000000',
                'user_id' => $this->session->userdata('user_id'),
               
                'data_registro' => $date_cadastro
                );

            $id = $this->input->post('id');
           $this->projetos_model->updateMarco($id, $data);
                
                 redirect("Projetos/Marcos_eventos_index");
           
            
        } else {

            $this->data['id'] = $id;
          
            $this->data['evento'] = $this->projetos_model->getMarcoByID($id);
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $usuario = $this->session->userdata('user_id');
            $projetos_usuario = $this->site->getProjetoAtualByID_completo($usuario);
            $this->data['projetos'] = $this->atas_model->getProjetoByID($projetos_usuario->projeto_atual);
            $this->data['users'] = $this->site->getAllUser();
            $this->data['setores'] = $this->site->getAllsetores();
            $this->data['setores_eventos'] = $this->site->getAllsetoresEento($id);
            $this->data['modulos_eventos'] = $this->site->getAllModulosEento($id);
            $this->data['modulos'] = $this->site->getAllModulos($projetos_usuario->projeto_atual);

            $bc = array(array('link' => base_url(), 'page' => lang('Eventos')), array('link' => '#', 'page' => lang('Editar Evento')));
            $meta = array('page_title' => lang('Editar Evento'), 'bc' => $bc);
           // $this->page_construct('projetos/marcos/edit_marco', $meta, $this->data);
            $this->load->view($this->theme . 'projetos/marcos/edit_marco', $this->data);
           

            }
    }
    
     public function delete_marco($id = null)
    {
        $this->sma->checkPermissions(null, true);

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

       
        if ($this->projetos_model->deleteMarco($id)) {
           
            
            $this->session->set_flashdata('message', lang('Evento Apagado'));
            redirect('Projetos/Marcos_eventos_index');
        }else{
           $this->session->set_flashdata('message', lang('Verificar os cadastros ligados a este evento: Setores, Módulos e Ações'));
            redirect('Projetos/Marcos_eventos_index');
        }
    }
    
     /*****************************************************
     *********GESTÃO DE DOCUMENTOS DO PROJETO ************ 
     ******************************************************/
    
    function gestao_documentos_index()
    {

      $this->sma->checkPermissions();
      $usuario = $this->session->userdata('user_id');
      $projetos_usuario = $this->site->getProjetoAtualByID_completo($usuario);
      
     // exit;
      $this->data['eventos']=$this->projetos_model->getAllDocumentos($projetos_usuario->projeto_atual);
      $meta =  array('page_title' => lang('Documentos'), 'bc' => $bc);
      $this->page_construct('projetos/documentos/index', $meta, $this->data);

    }
    
    public function add_documento()
    {
        $this->sma->checkPermissions();

        
        $date_cadastro = date('Y-m-d H:i:s');               
        
         $this->form_validation->set_rules('codigo', lang("Código"), 'trim|required');
         $this->form_validation->set_rules('grupo', lang("Grupo"), 'trim|required');
         $this->form_validation->set_rules('nome', lang("Nome Documento"), 'trim|required');
         $this->form_validation->set_rules('revisao', lang("Revisão"), 'trim|required');
         $this->form_validation->set_rules('data_revisao', lang("Data Revisão"), 'trim|required');
         $this->form_validation->set_rules('data_validade', lang("Data Validade"), 'trim|required');
         $this->form_validation->set_rules('status', lang("Status"), 'trim|required');
        if ($this->form_validation->run() == true) {
            
            
            $usuario = $this->session->userdata('user_id');
            $projetos_usuario = $this->site->getProjetoAtualByID_completo($usuario);
            
            $data = array(
                'nome_documento' => $this->input->post('nome'),
                'status' => $this->input->post('status'),
                'date_criaca' => $date_cadastro,
                'user' => $this->session->userdata('user_id'),
                'projeto' => $projetos_usuario->projeto_atual,
                'codigo_documento' => $this->input->post('codigo'),
                'grupo_documento' => $this->input->post('grupo'),
                'revisao' => $this->input->post('revisao'),
                'data_revisao' => $this->input->post('data_revisao'),
                'data_validade' => $this->input->post('data_validade')
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
                $data['anexo'] = $photo;
            }
            
           $usuarios = $this->input->post('responsavel');
           $setores = $this->input->post('setor');
         
          // print_r($setores);
           //echo 'aqui'; 
           // exit;
           $id_doc = $this->projetos_model->addDocumentos($data, $usuarios);
            $this->projetos_model->addDocumentosSetores($id_doc, $setores);
            
                $res = array('error' => 0, 'msg' => lang('Documento Criado'));
                 redirect("Projetos/gestao_documentos_index");
           
            
        }else {

            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['users'] = $this->atas_model->getAllUsersSetores();
            $this->data['setores'] = $this->atas_model->getAllSetor();
            $usuario = $this->session->userdata('user_id');
            $projetos_usuario = $this->site->getProjetoAtualByID_completo($usuario);
            $this->data['projetos'] = $this->atas_model->getProjetoByID($projetos_usuario->projeto_atual);
            
            //$this->data['eventos']=$this->projetos_model->getAllDocumentacao($projetos_usuario->projeto_atual);
            
            $meta =  array('page_title' => lang('Documentos'), 'bc' => $bc);
            $this->page_construct('projetos/documentos/add', $meta, $this->data);
            //$this->load->view($this->theme . 'projetos/documentos/add', $this->data);
        }
    }
    
    public function edit_documento($id = null)
    {
        $this->sma->checkPermissions();

        
        $date_cadastro = date('Y-m-d H:i:s');               
        
       $this->form_validation->set_rules('codigo', lang("Código"), 'trim|required');
         $this->form_validation->set_rules('grupo', lang("Grupo"), 'trim|required');
         $this->form_validation->set_rules('nome', lang("Nome Documento"), 'trim|required');
         $this->form_validation->set_rules('revisao', lang("Revisão"), 'trim|required');
         $this->form_validation->set_rules('data_revisao', lang("Data Revisão"), 'trim|required');
         $this->form_validation->set_rules('data_validade', lang("Data Validade"), 'trim|required');
         $this->form_validation->set_rules('status', lang("Status"), 'trim|required');
        //$this->form_validation->set_rules('start', lang("start"), 'required');

        if ($this->form_validation->run() == true) {
            
            $id = $this->input->post('id');
            $usuarios = $this->input->post('responsavel');
           $setores = $this->input->post('setor');
            
           $data = array(
                'nome_documento' => $this->input->post('nome'),
                'status' => $this->input->post('status'),
                'codigo_documento' => $this->input->post('codigo'),
                'grupo_documento' => $this->input->post('grupo'),
                'revisao' => $this->input->post('revisao'),
                'data_revisao' => $this->input->post('data_revisao'),
                'data_validade' => $this->input->post('data_validade')
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
                $data['anexo'] = $photo;
            }
            
              $this->projetos_model->updateDocumentoByIdp($id, $data, $usuarios, $setores);
              redirect("Projetos/gestao_documentos_index/");
            
        }else {

            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
           
            $usuario = $this->session->userdata('user_id');
            $projetos_usuario = $this->site->getProjetoAtualByID_completo($usuario);
           //  $this->data['documentacao'] = $documentacao;
            $this->data['documento'] = $this->projetos_model->getDocumentoById($id);
            $this->data['usuarios_setor'] = $this->projetos_model->getAllUserSetor($id);
            $this->data['documento_setor'] = $this->projetos_model->getAllDocumentoSetor($id);
            $this->data['setores'] = $this->atas_model->getAllSetor();
            $this->data['id'] = $id;
            $this->data['users'] = $this->atas_model->getAllUsersSetores();
            $bc = array(array('link' => base_url('index.php/projetos/documentos/'.$id), 'page' => lang('Documento')), array('link' => '#', 'page' => lang('Editar Documento')));
            $meta = array('page_title' => lang('DOCUMENTO'), 'bc' => $bc);
            $this->page_construct('projetos/documentos/edit', $meta, $this->data);
        }
    }
    
    public function anexar_documento($id = null)
    {
        $this->sma->checkPermissions();
        $date_cadastro = date('Y-m-d H:i:s');               
        $this->form_validation->set_rules('aprovado', lang("Quem Aprovou"), 'trim|required');
      
        if ($this->form_validation->run() == true) {
            $usuario = $this->session->userdata('user_id');
            $projetos_usuario = $this->site->getProjetoAtualByID_completo($usuario);
            $id = $this->input->post('id');
            $data = array(
                'quem_assinou' => $this->input->post('aprovado')
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
                $data['anexo'] = $photo;
            }
            
            if ($this->projetos_model->updateDocumentacao($id, $data)) {
                $res = array('error' => 0, 'msg' => lang('Documentação Anexado'));
                 redirect("Projetos/gestao_documentacao_index");
            } else {
                $res = array('error' => 1, 'msg' => lang('action_failed'));
                $this->sma->send_json($res);
            }
            
        }else {

            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['id'] = $id;
            $this->data['documentacao'] = $this->projetos_model->getDocumentacaoByID($id);
            
            $usuario = $this->session->userdata('user_id');
            $projetos_usuario = $this->site->getProjetoAtualByID_completo($usuario);
            $this->data['projetos'] = $this->atas_model->getProjetoByID($projetos_usuario->projeto_atual);
            
            // $bc = array(array('link' => base_url('index.php/projetos/tap/'.$id), 'page' => lang('GESTÃO DE DOCUMENTAÇÃO')), array('link' => '#', 'page' => lang('Concluir Documentação')));
           // $meta = array('page_title' => lang('TAP'), 'bc' => $bc);
           // $this->page_construct('projetos/documentacao/concluir', $meta, $this->data);
             $this->load->view($this->theme . 'projetos/documentacao/anexar', $this->data);
        }
    }
    
    public function atualiza_documentacaoOPS(){
        
        $documentacao = $this->projetos_model->getAllDocumentacao(13);
         foreach ($documentacao as $evento) {
             
             $id = $evento->id;
             $nome_documento = $evento->nome_documento;
             $versao = $evento->versao;
             $status = $evento->status;
             $data_finalizacao = $evento->data_finalizacao;
             $quem_elaborou = $evento->quem_elaborou;
             $revisado_por = $evento->revisado_por;
             $assinado_por = $evento->quem_assinou;
             $anexo = $evento->anexo;
          
             $data = array(
                'nome_documento' => $nome_documento,
                'status' => $status,
                'data_criacao' => date('Y-m-d H:i:s'),
                'quem_criou' => $this->session->userdata('user_id'),
                'projeto' => 1,
                'versao' => '00',
                 'quem_elaborou' => $quem_elaborou,
                 'revisado_por' => $revisado_por,
                 'quem_assinou' => $assinado_por
                );
             
             
             $id_documentacao = $this->projetos_model->addDocumento($data);
             
             $taps = $this->projetos_model->getTapByProjeto($id);
             
               foreach ($taps as $tap) {
                   
                   
                   $titulo = $tap->titulo;
                   $descricao = $tap->descricao;
                   $anexo = $tap->anexo;
                   $largura = $tap->largura;
                   $altura = $tap->altura;
                   
                    $data_sessao = array(
                    'titulo' => $titulo,
                    'documentacao' => $id_documentacao,
                    'descricao' => $descricao,
                    'anexo' => $anexo,
                    'user_id' => $this->session->userdata('user_id'),
                    'data_registro' => date('Y-m-d H:i:s'),
                     'altura' => $altura,
                    'largura' => $largura
                    );
                    
                   // print_r($data_sessao);
                    //echo '<br>';
                    $this->projetos_model->addTap($data_sessao);
               }
               //exit;
             
             
         }
    }
    
    
    
     /*****************************************************
     *********GESTÃO DE MELHORIAS DO PROJETO ************ 
     ******************************************************/
    
    function gestao_melhorias_index()
    {

      $this->sma->checkPermissions();
      $usuario = $this->session->userdata('user_id');
      $projetos_usuario = $this->site->getProjetoAtualByID_completo($usuario);
      
     // exit;
      $this->data['eventos']=$this->projetos_model->getAllMelhoria($projetos_usuario->projeto_atual);
      $meta =  array('page_title' => lang('Documentos'), 'bc' => $bc);
      $this->page_construct('projetos/melhorias/index', $meta, $this->data);

    }
    
    public function add_melhorias()
    {
        $this->sma->checkPermissions();
        
        $date_cadastro = date('Y-m-d H:i:s');                       
         $this->form_validation->set_rules('title', lang("Título"), 'trim|required');
         $this->form_validation->set_rules('mes', lang("Mês"), 'trim|required');
         $this->form_validation->set_rules('ano', lang("Ano"), 'trim|required');
         $this->form_validation->set_rules('description', lang("Drescrição"), 'trim|required');
       
        if ($this->form_validation->run() == true) {
                        
            $usuario = $this->session->userdata('user_id');
            $projetos_usuario = $this->site->getProjetoAtualByID_completo($usuario);
            
            $data = array(
                'titulo' => $this->input->post('title'),
                'data' => $date_cadastro,
                'user' => $this->session->userdata('user_id'),
                'projeto' => $projetos_usuario->projeto_atual,
                'melhoria' => $this->input->post('description'),
                'mes' => $this->input->post('mes'),
                'ano' => $this->input->post('ano')
                );
            
          
            $this->projetos_model->addMelhorias($data);
            
            $res = array('error' => 0, 'msg' => lang('Melhoria Criada'));
            redirect("Projetos/gestao_melhorias_index");
           
            
        }else {

            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['users'] = $this->atas_model->getAllUsersSetores();
            $this->data['setores'] = $this->atas_model->getAllSetor();
            $usuario = $this->session->userdata('user_id');
            $projetos_usuario = $this->site->getProjetoAtualByID_completo($usuario);
            $this->data['projetos'] = $this->atas_model->getProjetoByID($projetos_usuario->projeto_atual);
            
            //$this->data['eventos']=$this->projetos_model->getAllDocumentacao($projetos_usuario->projeto_atual);
            
            $meta =  array('page_title' => lang('Documentos'), 'bc' => $bc);
            //$this->page_construct('projetos/melhorias/add_melhoria', $meta, $this->data);
            $this->load->view($this->theme . 'projetos/melhorias/add_melhoria', $this->data);
        }
    }
    
    public function edit_melhorias($id = null)
    {
        $this->sma->checkPermissions();

        
        $date_cadastro = date('Y-m-d H:i:s');               
        
       $this->form_validation->set_rules('title', lang("Título"), 'trim|required');
         $this->form_validation->set_rules('mes', lang("Mês"), 'trim|required');
         $this->form_validation->set_rules('ano', lang("Ano"), 'trim|required');
         $this->form_validation->set_rules('description', lang("Drescrição"), 'trim|required');
      

        if ($this->form_validation->run() == true) {
            
           $id = $this->input->post('id');
            
                $data = array(
                'titulo' => $this->input->post('title'),
                'data' => $date_cadastro,
                'user' => $this->session->userdata('user_id'),
              //  'projeto' => $projetos_usuario->projeto_atual,
                'melhoria' => $this->input->post('description'),
                'mes' => $this->input->post('mes'),
                'ano' => $this->input->post('ano')
                );
            
          
            
              $this->projetos_model->updateMelhoriaById($id, $data);
              redirect("Projetos/gestao_melhorias_index/");
            
        }else {

            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
           
            $usuario = $this->session->userdata('user_id');
            $projetos_usuario = $this->site->getProjetoAtualByID_completo($usuario);
            
            $this->data['melhoria'] = $this->projetos_model->getMelhoriaById($id);
            
            $this->data['id'] = $id;
            // $bc = array(array('link' => base_url('index.php/projetos/documentos/'.$id), 'page' => lang('Documento')), array('link' => '#', 'page' => lang('Editar Documento')));
         //   $meta = array('page_title' => lang('DOCUMENTO'), 'bc' => $bc);
            //$this->page_construct('projetos/melhorias/edit', $meta, $this->data);
            $this->load->view($this->theme . 'projetos/melhorias/edit_marco', $this->data);
        }
    }
    
      public function delete_melhoria($id = null)
    {
        $this->sma->checkPermissions(null, true);

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

       
        if ($this->projetos_model->deleteMelhoria($id)) {
           
            
            $this->session->set_flashdata('message', lang('Melhoria Apagada'));
            redirect('Projetos/gestao_melhorias_index');
        }else{
           $this->session->set_flashdata('message', lang('Verificar os cadastros ligados a esta Melhoria'));
            redirect('Projetos/gestao_melhorias_index');
        }
    }
    
    
    
    public function nova_postagem($evento = null)
    {
      $this->sma->checkPermissions();
      
        $this->form_validation->set_rules('titulo', lang("titulo"), 'required');
        
        $date_cadastro = date('Y-m-d H:i:s');                           
        
       
          if ($this->form_validation->run() == true) {
           
           
            $titulo = $this->input->post('titulo');
            $descricao = $this->input->post('descricao');
            $tipo = $this->input->post('tipo');
            
            $usuario = $this->session->userdata('user_id');
            $projetos_usuario = $this->site->getProjetoAtualByID_completo($usuario);
            
            $data_post = array(
                'titulo' => $titulo,
                'tipo' => $tipo,
                'descricao' => $descricao,
                'user_id' => $this->session->userdata('user_id'),
                'data_postagem' => $date_cadastro,
                'projeto' => $projetos_usuario->projeto_atual
                );
            
//$projetos_usuario->projeto_atual,
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
                $data_post['anexo'] = $photo;
            }
            
              $this->projetos_model->addPostagem($data_post);   
              
                      
             
                
            $this->session->set_flashdata('message', lang("Postagem cadastrado com Sucesso!!!"));
            
             redirect("Login_Projetos/post_index");
            
        } else {

            
            
      
            
           $this->load->view($this->theme . 'projetos/post/novo', $this->data);

            }
    }
    
    
         public function delete_post($id = null)
    {
        $this->sma->checkPermissions(null, true);

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

       
        if ($this->projetos_model->deletePostagem($id)) {
           
            
            $this->session->set_flashdata('message', lang('Postagem Apagada'));
            redirect('Login_Projetos/post_index');
        }else{
           $this->session->set_flashdata('message', lang('Verificar os cadastros ligados a esta Postagem'));
            redirect('Login_Projetos/post_index');
        }
    }
    
    
    /*****************************************************
     *********GESTÃO DE REQUISIÇÃO DE HORAS ************ 
     ******************************************************/
    
    function requisicao_horas_index()
    {

      $this->sma->checkPermissions();
      $usuario = $this->session->userdata('user_id');
      $projetos_usuario = $this->site->getProjetoAtualByID_completo($usuario);
      
     // exit;
      $this->data['periodos'] = $this->projetos_model->getPeriodoCompetenciaHE();
      $meta =  array('page_title' => lang('Documentos'), 'bc' => $bc);
      $this->page_construct('projetos/requisicao_horas/index', $meta, $this->data);

    }
    
    
     function competencia_usuario($mes, $ano)
    {

      $this->sma->checkPermissions();
      $usuario = $this->session->userdata('user_id');
      $projetos_usuario = $this->site->getProjetoAtualByID_completo($usuario);
      
      $this->data['mes'] = $mes;
      $this->data['ano'] = $ano;
     
      $this->data['periodos'] = $this->projetos_model->getUsuarioPeriodoCompetenciaHE($mes, $ano);
      $meta =  array('page_title' => lang('Documentos'), 'bc' => $bc);
      $this->page_construct('projetos/requisicao_horas/periodo_usuario', $meta, $this->data);

    }
    
     function horarios_usuarios()
    {

      $this->sma->checkPermissions();
      $usuario = $this->session->userdata('user_id');
      $projetos_usuario = $this->site->getProjetoAtualByID_completo($usuario);
      
      $meta =  array('page_title' => lang('Documentos'), 'bc' => $bc);
      $this->page_construct('projetos/requisicao_horas/horario_usuarios', $meta, $this->data);

    }
    
    
     function detalhesPeriodoCompetencia($id_periodo)
    {
      $this->sma->checkPermissions();
    
       $this->data['competencia'] = $this->user_model->getPeriodoHEById($id_periodo);
       
        $this->data['lacamentos'] = $this->user_model->getDetalhesPeriodoHEByIdPeriodo($id_periodo);
        
        $this->data['ativo'] = 'requisicao';
        $this->data['menu'] = 'requisicao'; 
       // $this->data['footer'] = 'footer';
        $this->data['id_periodo'] = $id_periodo;
        
   
        
      $this->page_construct_novo_sem_menu('projetos/requisicao_horas/requisicaoHoras_detalhes_novo', $meta, $this->data);

    }
    
    public function AcaoDetalhes($id_acao)
    {
     
       
       $this->data['id'] = $id_periodo;   
        //    $id_descriptografado =  $this->encrypt($id_registro,'UNIMED');
        $usuario = $this->session->userdata('user_id');                     
        $this->data['pagina'] = 'usuarios/new/edit_requisica_hora';
        
        //$this->load->view($this->theme . 'projetos/documentacao/add', $this->data);
        $this->load->view($this->theme . 'projetos/requisicao_horas/detalhes_acao', $this->data);
           

           
    }
    
       public  function float_min($num) {
      $num = number_format($num,2);
      $num_temp = explode('.', $num);
      $num_temp[1] = $num-(number_format($num_temp[0],2));
      $saida = number_format(((($num_temp[1]) * 60 / 100)+$num_temp[0]),2);
      $saida = strtr($saida,'.',':');
      return $saida;
      // By Alexandre Quintal - alexandrequintal@yahoo.com.br
    } 
    
     public function conclui_envio_hora_extra($evento = null)
    {
      $this->sma->checkPermissions();
      
       $mes = $this->input->post('mes');
       $ano = $this->input->post('ano');
       $id_periodo = $this->input->post('id_periodo');
       $cpf = $this->input->post('cpf');
       
       $ano_tratado = substr($ano,2,2);
       
       $lancamentos_dados  = $this->user_model->getDetalhesPeriodoHEByIdPeriodo($id_periodo);
        foreach ($lancamentos_dados as $periodo) {
            
            $confirmacao = $this->input->post('opcao'.$periodo->id);
            if($confirmacao == 'SIM'){
                
                
                $hora_liberado =  $this->input->post('hora_liberado'.$periodo->id);
                $justificativa = substr($this->input->post('justificativa'.$periodo->id), 0, 50); 
                $credito = $this->input->post('credito'.$periodo->id);
               // $justificativa = $this->input->post('justificativa'.$periodo->id);
            
               // echo 'Justificativa : '.$justificativa.'<br>'; 
    
                $data_post = array(
                'confirmacao' => $confirmacao,
                'hora_aprovada' => $hora_liberado,
                'justificativa' => $justificativa    
              
                );
                   $this->projetos_model->updateStatusHESolicitacao($periodo->id,$data_post);   
                
               /*
                * CRÉDITO DECIMAL
                */ 
               $tempo_decimos_credito = $this->decimalHours($credito);
               $tempo_decimos_credito = round($tempo_decimos_credito, 2);
               $tempo_decimos_credito = number_format($tempo_decimos_credito, 2, '.', ''); 
            
               /*
                * HORA EXTRA LIBERADA
                */ 
               $tempo_decimos = $this->decimalHours($hora_liberado);
               $tempo_decimos = round($tempo_decimos, 2);
               $tempo_decimos = number_format($tempo_decimos, 2, '.', '');
               
               $diferenca_credito = $tempo_decimos_credito - $tempo_decimos;
               $diferenca_credito_hora = $this->float_min($diferenca_credito);
               
               $partes_hora = explode(":", $diferenca_credito_hora);
               $hora = $partes_hora[0];
               $min = $partes_hora[1];
               
               if(strlen($hora) == 1){
                   $hora = '0'.$partes_hora[0];
               }else{
                   $hora = $partes_hora[0];
               }
               
               $hora_dif_tratado = $hora.':'.$min;
               
               
               // TRATAR O DIA
               $dia_ponto = $periodo->dia;
                if(strlen($dia_ponto) == 1){
                   $dia_ponto_tratado = '0'.$dia_ponto;
               }else{
                   $dia_ponto_tratado = $dia_ponto;
               }
               
               $data_oracle = "$dia_ponto_tratado/$periodo->mes/$ano";
             
                 $this->projetos_model->updateDadosHoraExtraRH3($data_oracle,$tempo_decimos, $cpf, $periodo->dia, $tempo_decimos_credito, $justificativa, $hora_liberado, $hora_dif_tratado);   
                 $this->projetos_model->insertDadosHoraExtraRH3($data_oracle,$tempo_decimos, $cpf, $periodo->dia, $tempo_decimos_credito, $justificativa, $hora_liberado, $hora_dif_tratado);
              
                
            }else{
                if($periodo->hora_extra == 'SIM'){
                    $data_post = array(
                    'confirmacao' => $confirmacao,
                    'hora_extra' => 'NÃO',    
                    'hora_aprovada' => '00:00'

                    );
                   // print_r($data_post);
                       $this->projetos_model->updateStatusHESolicitacao($periodo->id,$data_post);  
                }
            }
        }

           
                $data_post2 = array(
                'status_verificacao' => 1
                );
            
                 $this->projetos_model->updateStatusPeriodoHE($id_periodo, $data_post2);   
              
                      
             
                
            $this->session->set_flashdata('message', lang("Concluídos com Sucesso!!!"));
            
             redirect("projetos/competencia_usuario/$mes/$ano");
            
        
    }
    
     public function decimalHours($time)
                {
                    $tempo = explode(":", $time);
                    return ($tempo[0] + ($tempo[1]/60) );
                }
    
}