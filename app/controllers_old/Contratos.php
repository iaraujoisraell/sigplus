<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Contratos extends MY_Controller
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
        $this->load->model('sales_model');
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
        
        //$this->data['users'] = $this->site->getAllUser();
        
        $usuario = $this->session->userdata('user_id');
        $projetos_usuario = $this->site->getProjetoAtualByID_completo($usuario);
        $this->data['contratos'] = $this->projetos_model->getAllContratosByProjeto($projetos_usuario->projeto_atual);
       
        
        $this->load->view($this->theme . 'contratos/index', $this->data);

    }
    
     public function getContratos($warehouse_id = null)
    {
        $this->sma->checkPermissions('index');

        if ((!$this->Owner || !$this->Admin) && !$warehouse_id) {
            $user = $this->site->getUser();
            $warehouse_id = $user->warehouse_id;
        }
        
        
        $selecionar =  anchor('Login_Projetos/projeto_ata/$1', '<i class="fa fa-exchange"></i> ' . lang('Selecionar'));
        $dashboard =  anchor('projetos/dashboard/$1', '<i class="fa fa-file-text-o"></i> ' . lang('Dashboard'));
        $edit_link = anchor('projetos/edit/$1', '<i class="fa fa-edit"></i> ' . lang('Editar Projeto'), 'class="sledit"');
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
          
            <li>' . $dashboard . '</li>
            <li>' . $selecionar . '</li>
            <li>' . $edit_link . '</li>
            <li>' . $cancela_link . '</li>
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
                ->select("id,projeto, dt_inicio, dt_final, dt_virada,gerente_area,gerente_edp, gerente_fornecedor, status, botao")
                ->from('projetos');
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
                        $this->excel->getActiveSheet()->SetCellValue('A' . $row, $plano->idatas);
                        $this->excel->getActiveSheet()->SetCellValue('B' . $row, $plano->idplanos);
                        $this->excel->getActiveSheet()->SetCellValue('C' . $row, $plano->descricao);
                        $this->excel->getActiveSheet()->SetCellValue('D' . $row, $plano->responsavel);
                        $this->excel->getActiveSheet()->SetCellValue('E' . $row, $plano->porque);
                        $this->excel->getActiveSheet()->SetCellValue('F' . $row, $this->sma->hrld($plano->data_termino));
                        $this->excel->getActiveSheet()->SetCellValue('G' . $row, $plano->onde);
                        $this->excel->getActiveSheet()->SetCellValue('H' . $row, $plano->como);
                        $this->excel->getActiveSheet()->SetCellValue('I' . $row, $plano->custo);
                        $this->excel->getActiveSheet()->SetCellValue('J' . $row, $plano->obs);
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
    
    public function add()
    {
        $this->sma->checkPermissions();

        $this->form_validation->set_rules('numero_contrato', lang("Número do Contrato"), 'required');
        $this->form_validation->set_rules('valor_original', lang("Valor Original"), 'required');
        $this->form_validation->set_rules('parcelas', lang("Número de Parcelas"), 'required');
        $this->form_validation->set_rules('dt_inicio_vigencia', lang("Data do Início da Vigência"), 'required');
        $this->form_validation->set_rules('quem_assinou', lang("Quem Assinou"), 'required');
                      
        
        if ($this->form_validation->run() == true) {
           
            //DADOS DO CONTRATO
            $projeto = $this->input->post('projeto');
            $numero_contrato = $this->input->post('numero_contrato');
            $tipo = $this->input->post('tipo');
            $fornecedor = $this->input->post('fornecedor');
            $empresa = $this->input->post('empresa');
            //INFORMAÇÕES DE CONTRATO
            //$dt_inicio_vigencia = $this->sma->fld(trim($this->input->post('dt_inicio_vigencia')));
            $dt_inicio_vigencia = $this->input->post('dt_inicio_vigencia');
            $vigencia_minima = $this->input->post('vigencia_minima');
            $valor_original_tratamento = str_replace(',', '.', str_replace('.', '', $this->input->post('valor_original'))); 
            $valor_original = $this->sma->formatDecimal($valor_original_tratamento);
            
            $quem_assinou = $this->input->post('quem_assinou');
            $status = $this->input->post('status');
            $seguimento = $this->input->post('seguimento');
            $note = $this->input->post('note');
            //REAJUSTE
            $reajuste = $this->input->post('reajuste');
            $mes_reajuste = $this->input->post('mes_reajuste');
            //RENOVAÇÃO
            $renovacao = $this->input->post('tipo_renovacao');
            $periodicidade = $this->input->post('periodicidade');
            $tempo_limite = $this->input->post('tempo_limite');
            //PAGAMENTO
            //$dt_primeiro_pagamento = $this->sma->fld(trim($this->input->post('dt_primeiro_pagamento'))); 
            $dt_primeiro_pagamento = $this->input->post('dt_primeiro_pagamento'); 
            //$valor_primeiro_pgto = $this->sma->formatDecimal('valor_primeiro_pgto');
            $valor_primeiro_pgto_tratamento = str_replace(',', '.', str_replace('.', '', $this->input->post('valor_primeiro_pgto')));
            $valor_primeiro_pgto = $this->sma->formatDecimal($valor_primeiro_pgto_tratamento);
            
            $valor_atual_tratamento = str_replace(',', '.', str_replace('.', '', $this->input->post('valor_parcela')));
            $valor_atual = $this->sma->formatDecimal($valor_atual_tratamento);
            $dia = $this->input->post('dia');
            if($dia == 0){
                $dia = 1;
            }
            $tempo_recebimento = $this->input->post('tempo_recebimento');
            $limite_corte = $this->input->post('tempo_corte');
            $numero_parcelas = $this->input->post('parcelas');
            if($numero_parcelas == 0){
                $numero_parcelas = 1;
            }
            
            $usuario = $this->session->userdata('user_id');
            $date_cadastro = date('Y-m-d H:i:s'); 
           
            $data_projeto = array(
                //DADOS
                'projeto' => $projeto,
                'numero_contrato' => $numero_contrato,
                'tipo' => $tipo,
                'fornecedor' => $fornecedor,
                'cliente' => $empresa,
                //INFORMAÇÕES
                'inicio_vigencia' => $dt_inicio_vigencia,
                'vigencia_minima' => $vigencia_minima,
                'valor_original' => $valor_original,  
                'quem_assinou' => $quem_assinou,
                'status' => $status,
                'seguimento' => $seguimento,
                'observacao' => $note,
                // REAJUSTE
                'indice_reajuste' => $reajuste,
                'mes_reajuste' => $mes_reajuste,
                //RENOVAÇÃO
                'tipo_renovacao' => $renovacao,
                'periodicidade' => $periodicidade,
                'tempo_limite_renovacao' => $tempo_limite,
                
                //PAGAMENTO
                'data_primeiro_pgto' => $dt_primeiro_pagamento,
                'valor_atual' => $valor_atual,
                'dia_vencimento' => $dia,
                'tempo_recebimento_nf' => $tempo_recebimento,  
                'limite_corte' => $limite_corte,  
                'parcelas' => $numero_parcelas,  
                
                'data_cadastro' => $date_cadastro,
                'usuario_cadastro' => $usuario
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
            
            /*
             * SALVA O CONTRATO
             */
            $id_contrato = $this->projetos_model->addContrato($data_projeto);
            /*
             * DADOS DA 1A PARCELA
             */
            $data = array(
                'type' => 'Despesa',
                'category' => 'CONTRATOS',
                'amount' => $this->sma->formatDecimal($valor_primeiro_pgto),
                'payer' => $fornecedor,
               // 'payerid' => $provider_id,
               // 'method' => $pagament_form ,
               // 'ref' => 'Contrato : '.$numero_contrato.' - '.$note,
                'status' => 'ABERTO',
                'description' => 'Contrato : '.$numero_contrato.' - '.$note,
                'date' => $dt_primeiro_pagamento,
                //'date_pagamento' => $data_baixa,
                //'dr' => $this->sma->formatDecimal($valor_baixado),
                'cr' => '0',   
                'parcela' => $vigencia_minima,
                //'id_st' => '',
                'parcela_atual' => '1',
                'usuario_id' => $this->session->userdata('username'),
                'note' => $note,
                'id_contrato' => $id_contrato,
                'empresa_id' => $empresa
            );
            /*
             * SALVA A 1A PARCELA
             */           
            $id_parcela_primeira = $this->projetos_model->addTitulos($data);
            
            /*
             * SE TIVER MAIS DE 1 PARCELA
             */
          
            if($numero_parcelas > 1){
            $data_pagamento_original = $this->sma->hrld($this->input->post('dt_primeiro_pagamento'));
            $partes = explode("/", $data_pagamento_original);
            $dia = $partes[0];
            $mes = $partes[1];
            $ano2 = $partes[2];
            $partes_ano = explode(" ", $ano2);
            $ano = $partes_ano[0];
            $hora = $partes_ano[1];
            $date_apagar = $ano.'-'.$mes.'-'.$dia;
            $contador = 2;
            $qtde = $vigencia_minima ;
            
            while ($contador <= $qtde) {
                
        
                   if($mes == '12') {
                        $mes = '1';
                        $ano = $ano + '1';
                        //echo $ano;
                    } else {
                        $mes = $mes + '1';
                       // echo $mes;
                    }
                   if(($dia == '31')&&($mes == '04')){
                    $date_apagar = $ano . '-' . $mes . '-' . '30';
                    }else if(($dia == '31')&&($mes == '06')){
                        $date_apagar = $ano . '-' . $mes . '-' . '30';
                    }else if(($dia == '31')&&($mes == '09')){
                        $date_apagar = $ano . '-' . $mes . '-' . '30';
                    }else if(($dia == '31')&&($mes == '11')){
                        $date_apagar = $ano . '-' . $mes . '-' . '30';
                    }else if (($mes == '02') && ($dia >= '29') && ($ano > '2016')) {
                        $date_apagar = $ano . '-' . $mes . '-' . '28';
                    } else if (($mes == '02') && ($dia >= '30') && ($ano == '2016')) {
                        $date_apagar = $ano . '-' . $mes . '-' . '29';
                    } else {
                        $date_apagar = $ano . '-' . $mes . '-' . $dia;                   
                    }   
                    
                     
                $data[$contador] = array(
                'type' => 'Despesa',
                'category' => 'CONTRATOS',
                'amount' => $this->sma->formatDecimal($valor_atual),
                'payer' => $fornecedor,
                'status' => 'ABERTO',
                'description' => 'Contrato : '.$numero_contrato.' - '.$note,
                'date' => $date_apagar.' '.$hora,//$dt_primeiro_pagamento,
                'cr' => '0',   
                'parcela' => $numero_parcelas,
                'id_st' => $id_parcela_primeira,
                'parcela_atual' => $contador,
                'usuario_id' => $this->session->userdata('username'),
                'note' => $note,
                'id_contrato' => $id_contrato,
                'empresa_id' => $empresa
                   
             );
               
         
                 $this->projetos_model->addTitulos($data[$contador]);
                 $contador++;
            }
        }
          
            $this->session->set_flashdata('message', lang("Projeto Criado com Sucesso!!!"));
            redirect("Contratos");
            
        } else {
            $usuario = $this->session->userdata('user_id');
            $projetos_usuario = $this->site->getProjetoAtualByID_completo($usuario);
           
            $this->data['projetos'] = $this->atas_model->getProjetoByID($projetos_usuario->projeto_atual);
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['providers'] = $this->site->getAllCompanies('supplier');
            $this->data['customers'] = $this->site->getAllCompanies('customer');
             $this->data['users'] = $this->atas_model->getAllUsersSetores();
            $bc = array(array('link' => base_url(), 'page' => lang('Contratos')), array('link' => site_url('contratos/add'), 'page' => lang('Novo Contrato')));
            $meta = array('page_title' => lang('Novo Contrato'), 'bc' => $bc);
            //$this->page_construct('contratos/add', $meta, $this->data);
            $this->load->view($this->theme . 'contratos/add', $this->data);
        }
    }
    
    public function edit($id = null)
    {
        $this->sma->checkPermissions();

        $this->form_validation->set_rules('numero_contrato', lang("Número do Contrato"), 'required');
        $this->form_validation->set_rules('valor_original', lang("Valor Original"), 'required');
        $this->form_validation->set_rules('parcelas', lang("Número de Parcelas"), 'required');
        $this->form_validation->set_rules('dt_inicio_vigencia', lang("Data do Início da Vigência"), 'required');
        $this->form_validation->set_rules('quem_assinou', lang("Quem Assinou"), 'required');
         
        
         if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        
       
        if ($this->form_validation->run() == true) {
         
            $contrato = $this->input->post('id_contrato');
           
            //DADOS DO CONTRATO
            $projeto = $this->input->post('projeto');
            $numero_contrato = $this->input->post('numero_contrato');
            $tipo = $this->input->post('tipo');
            $fornecedor = $this->input->post('fornecedor');
            $empresa = $this->input->post('empresa');
            //INFORMAÇÕES DE CONTRATO
            $dt_inicio_vigencia = $this->sma->fld(trim($this->input->post('dt_inicio_vigencia')));
            //$dt_inicio_vigencia = $this->input->post('dt_inicio_vigencia');
            $vigencia_minima = $this->input->post('vigencia_minima');
            $valor_original_tratamento = str_replace(',', '.', str_replace('.', '', $this->input->post('valor_original'))); 
            $valor_original = $this->sma->formatDecimal($valor_original_tratamento);
            
            $quem_assinou = $this->input->post('quem_assinou');
            $status = $this->input->post('status');
            $seguimento = $this->input->post('seguimento');
            $note = $this->input->post('note');
            $obs_titulo = $this->input->post('obs_titulo');
            //obs_titulo
            //REAJUSTE
            $reajuste = $this->input->post('reajuste');
            $mes_reajuste = $this->input->post('mes_reajuste');
            //RENOVAÇÃO
            $renovacao = $this->input->post('tipo_renovacao');
            $periodicidade = $this->input->post('periodicidade');
            $tempo_limite = $this->input->post('tempo_limite');
            //PAGAMENTO
            $dt_primeiro_pagamento = $this->sma->fld(trim($this->input->post('dt_primeiro_pagamento'))); 
            //$dt_primeiro_pagamento = $this->input->post('dt_primeiro_pagamento'); 
            //$valor_primeiro_pgto = $this->sma->formatDecimal('valor_primeiro_pgto');
            $valor_primeiro_pgto_tratamento = str_replace(',', '.', str_replace('.', '', $this->input->post('valor_primeiro_pgto')));
            $valor_primeiro_pgto = $this->sma->formatDecimal($valor_primeiro_pgto_tratamento);
            
            $valor_atual_tratamento = str_replace(',', '.', str_replace('.', '', $this->input->post('valor_parcela')));
            $valor_atual = $this->sma->formatDecimal($valor_atual_tratamento);
            $dia = $this->input->post('dia');
            if($dia == 0){
                $dia = 1;
            }
            $tempo_recebimento = $this->input->post('tempo_recebimento');
            $limite_corte = $this->input->post('tempo_corte');
            $numero_parcelas = $this->input->post('parcelas');
            if($numero_parcelas == 0){
                $numero_parcelas = 1;
            }
            
            $usuario = $this->session->userdata('user_id');
            $date_cadastro = date('Y-m-d H:i:s'); 
           
            $data_projeto = array(
                //DADOS
               // 'projeto' => $projeto,
                'numero_contrato' => $numero_contrato,
                'tipo' => $tipo,
                'fornecedor' => $fornecedor,
                'cliente' => $empresa,
                //INFORMAÇÕES
                'inicio_vigencia' => $dt_inicio_vigencia,
                'vigencia_minima' => $vigencia_minima,
                'valor_original' => $valor_original,  
                'quem_assinou' => $quem_assinou,
                'status' => $status,
                'seguimento' => $seguimento,
                'observacao' => $note,
                'obs_titulo' => $obs_titulo,
                // REAJUSTE
                'indice_reajuste' => $reajuste,
                'mes_reajuste' => $mes_reajuste,
                //RENOVAÇÃO
                'tipo_renovacao' => $renovacao,
                'periodicidade' => $periodicidade,
                'tempo_limite_renovacao' => $tempo_limite,
                
                //PAGAMENTO
                'data_primeiro_pgto' => $dt_primeiro_pagamento,
                'valor_atual' => $valor_atual,
                'dia_vencimento' => $dia,
                'tempo_recebimento_nf' => $tempo_recebimento,  
                'limite_corte' => $limite_corte,  
                'parcelas' => $numero_parcelas,  
                
                'data_cadastro' => $date_cadastro,
                'usuario_cadastro' => $usuario
            );
            
          //  print_r($data_projeto); exit;
            
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
            
            /*
             * SALVA O CONTRATO
             */
            $id_contrato = $this->projetos_model->updateContrato($contrato, $data_projeto);
           
          
            $this->session->set_flashdata('message', lang("Contrato Editado com Sucesso!!!"));
            redirect("Contratos");
            
        } else {
        
            $usuario = $this->session->userdata('user_id');
            $projetos_usuario = $this->site->getProjetoAtualByID_completo($usuario);
           
            $id_contrato = $id;
           
            $this->data['contrato'] = $this->projetos_model->getContratoByID($id_contrato);
            
            $this->data['projetos'] = $this->atas_model->getProjetoByID($projetos_usuario->projeto_atual);
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['providers'] = $this->site->getAllCompanies('supplier');
            $this->data['customers'] = $this->site->getAllCompanies('customer');
            $this->data['users'] = $this->site->getAllUser();
            $bc = array(array('link' => base_url(), 'page' => lang('Contratos')), array('link' => site_url('contratos/add'), 'page' => lang('Novo Contrato')));
            $meta = array('page_title' => lang('Novo Contrato'), 'bc' => $bc);
            //$this->page_construct('contratos/add', $meta, $this->data);
            $this->load->view($this->theme . 'contratos/edit', $this->data);
        }
    }
    
     public function ListaNovoRegistro()
    {
        $this->sma->checkPermissions();
           
        $usuario = $this->session->userdata('user_id');
        $projetos_usuario = $this->site->getProjetoAtualByID_completo($usuario);
        
        
        
            $dataEntrega = $this->sma->fld(trim($this->input->post('dateAta'))); 
            
            $this->data['dataEscolhida'] = $dataEntrega;
            $this->data['projetos_usuario'] = $projetos_usuario->projeto_atual;
            //$this->data['planos'] = $this->atas_model->getAllitemPlanosProjeto(1);
            $this->data['setores'] = $this->atas_model->getAllSetor();
            
           
            $bc = array(array('link' => base_url(), 'page' => lang('Histórico de ações')), array('link' => '#', 'page' => lang('Lista de Ações')));
            $meta = array('page_title' => lang('Novo Registro'), 'bc' => $bc);
            $this->page_construct('HistoricoAcoes/lista_acao', $meta, $this->data);
                   
    }
 
    public function atualizaUsuariosSetores(){
         $usuarios = $this->site->getAllUser();
         $cont = 1;
         foreach ($usuarios as $usuario) {
          //   echo $cont++.'-'. $usuario->id.' - '.$usuario->setor_id.'<br>';
             
            // $plano_usuario = $this->atas_model->getAllPlanosUserAtualizacao($usuario->id);
            //  $id_plano      = $plano_usuario->idplanos;
            // echo 'idplano: '.$id_plano.'<br>';
             
           //  $data_insert = array('setor' => $usuario->setor_id);
             
            //   $this->atas_model->updatePlanoAtualizacao($usuario->id,$data_insert);
         }
       //  echo 'ACABOU!';
    }
   
     public function imprimir_pdf($id = null,$parcela = null, $view = null)
    {
        
        $this->sma->checkPermissions();

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        $usuario = $this->session->userdata('username');
        
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
      
        
            $this->data['id'] = $id;
           //echo $parcela; exit;
            $this->data['contrato'] = $this->projetos_model->getContratoByID($id);
            $this->data['parcela'] = $this->sales_model->getContaPagarByID($parcela);
            $this->data['customers'] = $this->site->getAllCompanies('customer');
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['users'] = $this->site->getAllUser();
           
          

            $name = lang("Contrato") . "_" . str_replace('/', '_', $id) . ".pdf";
            $html = $this->load->view($this->theme . 'contratos/imprimir_pdf', $this->data, true);

            $usuario = $this->session->userdata('user_id');
            $projetos_usuario = $this->site->getProjetoAtualByID_completo($usuario);
            
            $dados_projeto = $this->projetos_model->getProjetoByID($projetos_usuario->projeto_atual);
            $logo_doc_top =  $dados_projeto->logo_ata_top;
            $logo_doc_bottom =  $dados_projeto->logo_ata_bottom;
            
        if ($view) {
            $this->load->view($this->theme . 'contratos/imprimir_pdf', $this->data);
        } else{
            
            $this->sma->generate_pdf($html, $name, false, $usuario, null, null, null, null, $logo_doc_top, $logo_doc_bottom);
        }
    }
}