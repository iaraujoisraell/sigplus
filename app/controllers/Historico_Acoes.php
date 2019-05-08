<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Historico_Acoes extends MY_Controller
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
    }

    function index()
    {

      $this->sma->checkPermissions();

        

        $usuario = $this->session->userdata('user_id');   
        $users = $this->site->geUserByID($usuario);
        $bc = array(array('link' => '#', 'page' => lang('NOME : '.$users->first_name .' '. $users->last_name .' | SETOR : '.$users->company )));
        $this->data['users'] = $this->site->getAllUser();
        $meta = array('page_title' => lang('AÇÕES'), 'bc' => $bc);
        $this->page_construct('HistoricoAcoes/index', $meta, $this->data);

    }
    
     public function getPlanos($warehouse_id = null)
    {
        $this->sma->checkPermissions('index');

        if ((!$this->Owner || !$this->Admin) && !$warehouse_id) {
            $user = $this->site->getUser();
            $warehouse_id = $user->warehouse_id;
        }
        
        
        
       // $edit_link = anchor('planos/edit/$1', '<i class="fa fa-edit"></i> ' . lang('Editar Plano'), 'data-toggle="modal" data-target="#myModal"');
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
        
        $usuario = $this->session->userdata('user_id');
        $projetos_usuario = $this->site->getProjetoAtualByID_completo($usuario);
        $projetos_usuario->projeto_atual;
           
        $this->load->library('datatables');
            $this->datatables
                ->select("id, data, setor, superintendente, quantidade, status")
                ->from('historico_acoes')
                
                ->where('projeto =', $projetos_usuario->projeto_atual);
            
            if ($conta) {
                $this->datatables->where('users.id', $conta);
            }
            
            if ($start_date) {
                $this->datatables->where('data_termino BETWEEN "' . $start_date . '" and "' . $end_date . '"');
            }
             if ($status) {
                $this->datatables->where('planos.status', $status);
            }
            
            $this->db->order_by('id', 'desc');
         
            
        
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
    
     public function novoRegistro()
    {
        $this->sma->checkPermissions();
      
      
           
           
            $dataEntrega = $this->sma->fld(trim($this->input->post('dateEntrega'))); 
           
      
            $bc = array(array('link' => base_url(), 'page' => lang('Histórico de ações')), array('link' => '#', 'page' => lang('Novo Registro')));
           
            $meta = array('page_title' => lang('Novo Registro'), 'bc' => $bc);
            $this->page_construct('HistoricoAcoes/novoRegistro', $meta, $this->data);
                   
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
   
}