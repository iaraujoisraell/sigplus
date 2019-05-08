<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Atividades extends MY_Controller
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
        
        $this->data['pagina'] = 'sigUser/atividades';
        $this->data['ativo'] = 'hospitalar';
        $this->data['menu'] = 'dashboard'; //footer
        $this->data['footer'] = 'footer';
        $this->load->view($this->theme . 'sigUser/main', $this->data);
        /*
        $this->data['query_entrada_fornecedor_hmu'] = $query_entrada_fornecedor_hmu;
        $this->data['query_saldo_devedor_hmu'] = $query_saldo_devedor_hmu;
        $this->data['query_saldo_devedor_hupl'] = $query_saldo_devedor_hupl;
        $this->data['query_saldo_receber_hmu'] = $query_saldo_receber_hmu;
        $this->data['query_saldo_receber_hupl'] = $query_saldo_receber_hupl;
         * 
         */
        
        
        
       // $this->load->view($this->theme . 'usuarios/index', $this->data);
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
    
    
}
