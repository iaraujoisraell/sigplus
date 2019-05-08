<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Atendimento extends MY_Controller
{

    function __construct()
    {
        parent::__construct();

        if (!$this->loggedIn) {
            $this->session->set_userdata('requested_page', $this->uri->uri_string());
            redirect('login');
        }
        
        $this->load->model('db_model');
        $this->lang->load('auth', $this->Settings->user_language);
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
        $this->load->model('auth_model');
        $this->load->library('ion_auth');
        $this->load->model('atas_model');
        $this->load->model('projetos_model');
        $this->load->model('reports_model');
        $this->load->model('site');
        $this->digital_upload_path = 'assets/uploads/atas';
        $this->upload_path = 'assets/uploads/atas';
        $this->thumbs_path = 'assets/uploads/thumbs/atas';
        $this->image_types = 'gif|jpg|jpeg|png|tif';
        $this->digital_file_types = 'zip|psd|ai|rar|pdf|doc|docx|xls|xlsx|ppt|pptx|gif|jpg|jpeg|png|tif|txt';
    }

    public function index()
    {
        if ($this->Settings->version == '2.3') {
            $this->session->set_flashdata('warning', 'Please complete your update by synchronizing your database.');
            redirect('sync');
        }
        $this->sma->checkPermissions();
        
        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        $this->data['pagina'] = 'atendimento/index';
        $this->data['menu'] = 'dashboard';
       
        $this->load->view($this->theme . 'atendimento/main', $this->data);
        
    }

    public function prestadores() {
        $this->sma->checkPermissions();
        
        
        if ($this->Settings->version == '2.3') {
            $this->session->set_flashdata('warning', 'Please complete your update by synchronizing your database.');
            redirect('sync');
        }
        
        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        
        
          $this->form_validation->set_rules('beneficiario', lang("Beneficiário"), 'required');
        
                      
        
         /*
         * VALIDA SE O USUÁRIO TEM ACESSO A MAIS DE 1 SISTEMA
         */
          
        $usuario = $this->session->userdata('user_id');  
        
        $servicos = $this->input->post('servicos');
        $especialidades = trim($this->input->post('especialidades')); 
        $status = trim($this->input->post('status')); 
        
        
        
        $especialidades = $this->atas_model->getAllEspecialidadesSai();
        $this->data['especialidades'] = $especialidades;
        $servicos = $this->atas_model->getAllServicosSai();
        $this->data['servicos'] = $servicos;
        
        
        $prestadores = $this->atas_model->getAllPrestadoresSai();
        $this->data['prestadores'] = $prestadores;
        
        //$referrer = $this->session->userdata('requested_page') ? $this->session->userdata('requested_page') : 'Login_Projetos/menu_sistemas'; 
        $this->load->view($this->theme . 'atendimento/prestadores', $this->data);
       
         
      } 
      
    public function prestador_novo() {
        $this->sma->checkPermissions();
        
        
        if ($this->Settings->version == '2.3') {
            $this->session->set_flashdata('warning', 'Please complete your update by synchronizing your database.');
            redirect('sync');
        }
        
        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        
        
        $this->form_validation->set_rules('codigo', lang("Código"), 'required');
        $this->form_validation->set_rules('prestador', lang("Nome Prestador"), 'required');
                      
        
        if ($this->form_validation->run() == true) {
            
           $usuario = $this->session->userdata('user_id');
           $date_cadastro = date('Y-m-d H:i:s'); 
           
            $codigo = $this->input->post('codigo');
            $prestador = trim($this->input->post('prestador')); 
            $crm = trim($this->input->post('crm')); 
            $tipo_endereco = $this->input->post('tipo_endereco');
            $endereco = $this->input->post('endereco');
            $end_numero = $this->input->post('end_numero');
            $end_cep = $this->input->post('end_cep');
            $end_bairro = $this->input->post('bairro');
            $cidade = $this->input->post('cidade');
            $cooperado = $this->input->post('cooperado');
            
             $data_ata = array(
                'codigo' => $codigo,
                'nome' => $prestador,
                'ctp_numero_conselho' => $crm,
                'tipo_endereco' => $tipo_endereco,
                'endereco' => $endereco,
                'end_numero' => $end_numero,
                'end_cep' => $end_cep,
                'end_bairro' => $end_bairro,
                'end_municipio' => $cidade,
                'cooperado' => $cooperado
            ); 
           // ECHO $id.'<br>';
           //   print_r($data_ata); exit;
            
            $id_prestador =  $this->atas_model->add_prestador($data_ata);
            $this->session->set_flashdata('message', lang("Registro Criado com Sucesso!!!"));
            redirect("atendimento/prestador_editar/$id_prestador");
          } else {     
        
        $prestadores = $this->atas_model->getAllPrestadoresSai();
        $this->data['prestadores'] = $prestadores;
        
        $especialidades = $this->atas_model->getAllEspecialidadesSai();
        $this->data['especialidades'] = $especialidades;
        
         $servicos = $this->atas_model->getAllServicosSai();
        $this->data['servicos'] = $servicos;
        
        $this->data['menu'] = 'lista';  
        /*
         * VALIDA SE O USUÁRIO TEM ACESSO A MAIS DE 1 SISTEMA
         */
          
        $this->load->view($this->theme . 'atendimento/prestador_novo', $this->data);
        
          }
        
      }
      
    public function prestador_editar($id) {
       // $this->sma->checkPermissions();
        
        
        if ($this->Settings->version == '2.3') {
            $this->session->set_flashdata('warning', 'Please complete your update by synchronizing your database.');
            redirect('sync');
        }
        
        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
          
        $this->form_validation->set_rules('editar_cadastro', lang("Beneficiário"), 'required');
        
        if ($this->form_validation->run() == true) {
          
           $usuario = $this->session->userdata('user_id');
           $date_cadastro = date('Y-m-d H:i:s'); 
           
           $id = $this->input->post('id');
          
            $codigo = $this->input->post('codigo');
            $prestador = trim($this->input->post('prestador')); 
            $crm = trim($this->input->post('crm')); 
            $tipo_endereco = $this->input->post('tipo_endereco');
            $endereco = $this->input->post('endereco');
            $end_numero = $this->input->post('end_numero');
            $end_cep = $this->input->post('end_cep');
            $end_bairro = $this->input->post('end_bairro');
            $cidade = $this->input->post('end_cidade');
            $cooperado = $this->input->post('cooperado');
            
           
               $data_ata = array(
                'codigo' => $codigo,
                'nome' => $prestador,
                'ctp_numero_conselho' => $crm,
                'tipo_endereco' => $tipo_endereco,
                'endereco' => $endereco,
                'end_numero' => $end_numero,
                'end_cep' => $end_cep,
                'end_bairro' => $end_bairro,
                'end_municipio' => $cidade,
                'cooperado' => $cooperado
            ); 
           // ECHO $id.'<br>';
           //   print_r($data_ata); exit;
            
             $this->atas_model->updatePrestador($id, $data_ata);
             $this->session->set_flashdata('message', lang("Registro Criado com Sucesso!!!"));
            redirect("atendimento/prestadores");
          } else {     
        
            $prestadores = $this->atas_model->getAllPrestadoresSai();
            $this->data['prestadores'] = $prestadores;
            $especialidades = $this->atas_model->getAllEspecialidadesSai();
            $this->data['especialidades'] = $especialidades;
             $servicos = $this->atas_model->getAllServicosSai();
            $this->data['servicos'] = $servicos;
            $this->data['menu'] = 'lista';  
            
            $dados = $this->atas_model->getRegistroPrestadorByID($id);
            $this->data['dados'] = $dados;
            $this->data['id'] = $id;  
            /*
             * VALIDA SE O USUÁRIO TEM ACESSO A MAIS DE 1 SISTEMA
             */

            $this->load->view($this->theme . 'atendimento/prestador_editar', $this->data);
        
          }
        
      }
      
    public function especialidade_prestador_novo() {
       // $this->sma->checkPermissions();
        
        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        
        $this->form_validation->set_rules('id_prestador', lang("Especialidade"), 'required');
       
        if ($this->form_validation->run() == true) {
            
            $usuario = $this->session->userdata('user_id');
            $date_cadastro = date('Y-m-d'); 
           
            $id_prestador = $this->input->post('id_prestador');
            $codigo_prestador = $this->input->post('codigo');
           
            $especialidade = $this->input->post('especialidade_prestador');
            $especialidade_select = $this->input->post('especialidades');
            
            $valor = trim($this->input->post('valor')); 
          
            if($especialidade_select){
                $data_ata = array(
               'codigo' => $codigo_prestador,
               'especialidade' => $especialidade_select,
               'valor' => $valor,
               'data_registro' => $date_cadastro 
            );
            }else{
              $data_ata = array(
               'codigo' => $codigo_prestador,
               'especialidade' => $especialidade,
               'valor' => $valor,
               'data_registro' => $date_cadastro 
            );  
            }
            
            
            $this->atas_model->add_especialidade_prestador($data_ata);
            $this->session->set_flashdata('message', lang("Registro Criado com Sucesso!!!"));
            redirect("atendimento/prestador_editar/$id_prestador");
          } else {     
        
      
        
          }
        
      }  
      
    public function deletar_especialidade_prestador($id, $id_prestador) {
        
        if ($this->atas_model->deleteEspecialidadePrestador($id)) {

        $this->session->set_flashdata('message', lang('ATA Apagada com Sucesso!!!'));
        redirect('atendimento/prestador_editar/'.$id_prestador);
        }   
            
        
        
    }
      
    public function telefone_prestador_novo() {
        $this->sma->checkPermissions();
        
        
        if ($this->Settings->version == '2.3') {
            $this->session->set_flashdata('warning', 'Please complete your update by synchronizing your database.');
            redirect('sync');
        }
        
        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        
        
        $this->form_validation->set_rules('ddd', lang("DDD"), 'required');
        $this->form_validation->set_rules('telefone', lang("Telefone"), 'required');
        
        if ($this->form_validation->run() == true) {
            
           $usuario = $this->session->userdata('user_id');
           $date_cadastro = date('Y-m-d H:i:s'); 
           
           $id_prestador = $this->input->post('id_prestador');
           $codigo_prestador = $this->input->post('codigo');
           
            $tipo = $this->input->post('tipo');
            $ddd = trim($this->input->post('ddd')); 
            $telefone = trim($this->input->post('telefone')); 
            
            
              $data_ata = array(
                'prestador' => $codigo_prestador,
                'ddd' => $ddd,
                'telefone' => $telefone,
                'tipo' => $tipo 
            );
            
             $this->atas_model->add_telefone_prestador($data_ata);
             $this->session->set_flashdata('message', lang("Registro Criado com Sucesso!!!"));
            redirect("atendimento/prestador_editar/$id_prestador");
          } else {     
        
      
        
          }
        
      }  
      
    public function deletar_telefone_prestador($id, $id_prestador) {
        
        if ($this->atas_model->deleteTelefonePrestador($id)) {

        $this->session->set_flashdata('message', lang('ATA Apagada com Sucesso!!!'));
        redirect('atendimento/prestador_editar/'.$id_prestador);
        }   
            
        
        
    }
    
    public function servico_prestador_novo() {
       // $this->sma->checkPermissions();
        
        
       
        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        
        $this->form_validation->set_rules('servicos', lang("Serviço"), 'required');
       
        if ($this->form_validation->run() == true) {
            
            $usuario = $this->session->userdata('user_id');
            $date_cadastro = date('Y-m-d'); 
           
            $id_prestador = $this->input->post('id_prestador');
            $codigo_prestador = $this->input->post('codigo');
           
            $especialidade = $this->input->post('servicos');
            $valor = trim($this->input->post('valor')); 
          
            
            $data_ata = array(
               'codigo' => $codigo_prestador,
               'especialidade' => $especialidade,
               'valor' => $valor,
               'data_registro' => $date_cadastro 
            );
           
            $this->atas_model->add_servico_prestador($data_ata);
            $this->session->set_flashdata('message', lang("Registro Criado com Sucesso!!!"));
            redirect("atendimento/prestador_editar/$id_prestador");
          } else {     
        
      
        
          }
        
      }
   
    public function deletar_servico_prestador($id, $id_prestador) {
        
        if ($this->atas_model->deleteServicosPrestador($id)) {

        $this->session->set_flashdata('message', lang('Apagado com Sucesso!!!'));
        redirect('atendimento/prestador_editar/'.$id_prestador);
        }   
            
        
        
    }  
    
    public function servicos_novo() {
       // $this->sma->checkPermissions();
        
        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        
        $this->form_validation->set_rules('descricao_servico', lang("Serviço"), 'required');
       
        if ($this->form_validation->run() == true) {
            
            $usuario = $this->session->userdata('user_id');
            $date_cadastro = date('Y-m-d'); 
           
            $servico = $this->input->post('descricao_servico');
            
                $data_ata = array(
               'descricao' => $servico
            );
            
            $this->atas_model->add_servicosSai($data_ata);
            $this->session->set_flashdata('message', lang("Registro Criado com Sucesso!!!"));
            redirect("atendimento/servicos_ativos");
          } else {     
        
      
        
          }
        
      }
    
    public function deletar_servico($id) {
        
        if ($this->atas_model->deleteServicos($id)) {

        $this->session->set_flashdata('message', lang('Apagado com Sucesso!!!'));
        redirect('atendimento/servicos_ativos');
        }   
        
    }   
      
    public function especialidades() {
        $this->sma->checkPermissions();
        
        
        if ($this->Settings->version == '2.3') {
            $this->session->set_flashdata('warning', 'Please complete your update by synchronizing your database.');
            redirect('sync');
        }
        
        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        
         
        /*
         * VALIDA SE O USUÁRIO TEM ACESSO A MAIS DE 1 SISTEMA
         */
          
        
        
        //$referrer = $this->session->userdata('requested_page') ? $this->session->userdata('requested_page') : 'Login_Projetos/menu_sistemas'; 
        $this->load->view($this->theme . 'atendimento/especialidades', $this->data);
       
        
      }
    
    public function servicos_ativos() {
        $this->sma->checkPermissions();
        
        
        if ($this->Settings->version == '2.3') {
            $this->session->set_flashdata('warning', 'Please complete your update by synchronizing your database.');
            redirect('sync');
        }
        
        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        
        $servicos = $this->atas_model->getAllServicosSai();
        $this->data['servicos'] = $servicos;
          
        /*
         * VALIDA SE O USUÁRIO TEM ACESSO A MAIS DE 1 SISTEMA
         */
          
        $this->load->view($this->theme . 'atendimento/servicos', $this->data);
        
        
      }  
      
    public function lista_espera() {
        $this->sma->checkPermissions();
        
        
        if ($this->Settings->version == '2.3') {
            $this->session->set_flashdata('warning', 'Please complete your update by synchronizing your database.');
            redirect('sync');
        }
        
        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        
        $servicos = $this->input->post('servicos');
        $especialidades = trim($this->input->post('especialidades')); 
        $status = trim($this->input->post('status')); 
        
        
        $lista = $this->atas_model->getAllListaEspera($servicos, $especialidades, $status);
        $this->data['listas'] = $lista;
        $especialidades = $this->atas_model->getAllEspecialidadesSai();
        $this->data['especialidades'] = $especialidades;
         $servicos = $this->atas_model->getAllServicosSai();
        $this->data['servicos'] = $servicos;
        $this->data['menu'] = 'lista';  
        /*
         * VALIDA SE O USUÁRIO TEM ACESSO A MAIS DE 1 SISTEMA
         */
          
        $this->load->view($this->theme . 'atendimento/lista_espera', $this->data);
        
        
      }
      
    public function lista_espera_novo() {
        $this->sma->checkPermissions();
        
        
        if ($this->Settings->version == '2.3') {
            $this->session->set_flashdata('warning', 'Please complete your update by synchronizing your database.');
            redirect('sync');
        }
        
        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        
        
        $this->form_validation->set_rules('beneficiario', lang("Beneficiário"), 'required');
        
                      
        
        if ($this->form_validation->run() == true) {
            
           $usuario = $this->session->userdata('user_id');
           $date_cadastro = date('Y-m-d H:i:s'); 
           
            $beneficiario = $this->input->post('beneficiario');
            $cateirinha = trim($this->input->post('cateirinha')); 
            $protocolo = trim($this->input->post('protocolo')); 
            $servicos = $this->input->post('servicos');
            $especialidade = $this->input->post('especialidade');
            $prestador = $this->input->post('prestador');
            $status = $this->input->post('status');
            $observacoes = $this->input->post('observacoes');
            
              $data_ata = array(
                'beneficiario' => $beneficiario,
                'numero_carteira' => $cateirinha,
                'especialidade' => $especialidade,
                'servico' => $servicos,
                'prestador_selecionado' => $prestador,
                'status' => $status,
                'atendente_criacao' => $usuario,
                'data_criacao' => $date_cadastro,   
            //    'atendente_concluiu' => $avulsa,
            //    'data_conclusao' => $evento,
                'protocolo' => $protocolo,
                'observacao' => $observacoes
            );
            
            
             $this->atas_model->add_lista_espera($data_ata);
             $this->session->set_flashdata('message', lang("Registro Criado com Sucesso!!!"));
            redirect("atendimento/lista_espera");
          } else {     
        
        $prestadores = $this->atas_model->getAllPrestadoresSai();
        $this->data['prestadores'] = $prestadores;
        
        $especialidades = $this->atas_model->getAllEspecialidadesSai();
        $this->data['especialidades'] = $especialidades;
        
         $servicos = $this->atas_model->getAllServicosSai();
        $this->data['servicos'] = $servicos;
        
        $this->data['menu'] = 'lista';  
        /*
         * VALIDA SE O USUÁRIO TEM ACESSO A MAIS DE 1 SISTEMA
         */
          
        $this->load->view($this->theme . 'atendimento/lista_espera_novo', $this->data);
        
          }
        
      }
    
    public function lista_espera_editar($id) {
        $this->sma->checkPermissions();
        
        
        if ($this->Settings->version == '2.3') {
            $this->session->set_flashdata('warning', 'Please complete your update by synchronizing your database.');
            redirect('sync');
        }
        
        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        
          
        $this->form_validation->set_rules('beneficiario', lang("Beneficiário"), 'required');
        $this->form_validation->set_rules('status', lang("Status"), 'required');
        
        if ($this->form_validation->run() == true) {
            
           $usuario = $this->session->userdata('user_id');
           $date_cadastro = date('Y-m-d H:i:s'); 
           
           $id = $this->input->post('id');
          
            $beneficiario = $this->input->post('beneficiario');
            $cateirinha = trim($this->input->post('cateirinha')); 
            $protocolo = trim($this->input->post('protocolo')); 
            $servicos = $this->input->post('servicos');
            $especialidade = $this->input->post('especialidade');
            $prestador = $this->input->post('prestador');
            $status = $this->input->post('status');
            $observacoes = $this->input->post('observacoes');
            
            if($status != 0){
               $data_ata = array(
                'beneficiario' => $beneficiario,
                'numero_carteira' => $cateirinha,
                'especialidade' => $especialidade,
                'servico' => $servicos,
                'prestador_selecionado' => $prestador,
                'status' => $status,
                'atendente_concluiu' => $usuario,
                'data_conclusao' => $date_cadastro,
                'protocolo' => $protocolo,
                'observacao' => $observacoes
            ); 
            }else{
              $data_ata = array(
                'beneficiario' => $beneficiario,
                'numero_carteira' => $cateirinha,
                'especialidade' => $especialidade,
                'servico' => $servicos,
                'prestador_selecionado' => $prestador,
                'status' => $status,
                'protocolo' => $protocolo,
                'observacao' => $observacoes
            );  
            }
              
            
             $this->atas_model->updateLista($id, $data_ata);
             $this->session->set_flashdata('message', lang("Registro Criado com Sucesso!!!"));
            redirect("atendimento/lista_espera");
          } else {     
        
            $prestadores = $this->atas_model->getAllPrestadoresSai();
            $this->data['prestadores'] = $prestadores;
            $especialidades = $this->atas_model->getAllEspecialidadesSai();
            $this->data['especialidades'] = $especialidades;
             $servicos = $this->atas_model->getAllServicosSai();
            $this->data['servicos'] = $servicos;
            $this->data['menu'] = 'lista';  
            
            $dados = $this->atas_model->getRegistroListaByID($id);
            $this->data['dados'] = $dados;
            $this->data['id'] = $id;  
            /*
             * VALIDA SE O USUÁRIO TEM ACESSO A MAIS DE 1 SISTEMA
             */

            $this->load->view($this->theme . 'atendimento/lista_espera_editar', $this->data);
        
          }
        
      }
      
}
