<?php defined('BASEPATH') OR exit('No direct script access allowed');

class bi_menu extends MY_Controller
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
        $this->load->model('site');
        $this->digital_upload_path = 'assets/uploads/atas';
        $this->upload_path = 'assets/uploads/atas';
        $this->thumbs_path = 'assets/uploads/thumbs/atas';
        $this->image_types = 'gif|jpg|jpeg|png|tif';
        $this->digital_file_types = 'zip|psd|ai|rar|pdf|doc|docx|xls|xlsx|ppt|pptx|gif|jpg|jpeg|png|tif|txt';
    }

    public function index()
    {
        echo 'aqui'; exit;
        if ($this->Settings->version == '2.3') {
            $this->session->set_flashdata('warning', 'Please complete your update by synchronizing your database.');
            redirect('sync');
        }
        $this->sma->checkPermissions();
        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        
        //$lmsdate = date('Y-m-d', strtotime('first day of last month')) . ' 00:00:00';
        //$lmedate = date('Y-m-d', strtotime('last day of last month')) . ' 23:59:59';
       // $this->data['lmbs'] = $this->db_model->getBestSeller($lmsdate, $lmedate);
       // $bc = array(array('link' => '#', 'page' => lang('Projetos')));
        //$meta = array('page_title' => lang('projetos'), 'bc' => $bc);
        //$this->page_construct('selecionar_projetos', "", $this->data);
        
        $this->load->view($this->theme . 'bi/main', $this->data);
        
    }

   

    public function projeto_menu() {
        $this->sma->checkPermissions();
        
        if ($this->Settings->version == '2.3') {
            $this->session->set_flashdata('warning', 'Please complete your update by synchronizing your database.');
            redirect('sync');
        }

        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        
        $this->data['projetos'] = $this->atas_model->getAllProjetos();
        $bc = array(array('link' => '#', 'page' => lang('Selecione o Projeto')));
        $meta = array('page_title' => lang('Selecionar projetos'), 'bc' => $bc);
        //$this->page_construct('selecionar_projetos', $meta, $this->data);
        
        $this->load->view($this->theme . 'selecionar_projetos', $this->data);
    }    
    
     public function menu() {
          
        //$this->sma->checkPermissions();
        
        if ($this->Settings->version == '2.3') {
            $this->session->set_flashdata('warning', 'Please complete your update by synchronizing your database.');
            redirect('sync');
        }
        
        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        $this->data['projetos'] = $this->atas_model->getAllProjetos();
        
        
        
         $this->load->view($this->theme . 'menu', $this->data);
       
    } 
    
    
    
}
