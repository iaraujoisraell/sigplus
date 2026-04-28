<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Medico_substituto extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('medicos_model');
        $this->load->model('unidades_hospitalares_model');
        
    }

    public function index() {
        close_setup_menu();

        $data['title']      = 'Medicos Substitutos';
        
        $data['medicos']      = $this->medicos_model->get();
        $data['substitutos']  = $this->medicos_model->get_substitutos();
        $data['unidades']  = $this->unidades_hospitalares_model->get();
        //print_r($data['medicos']); exit;
        //echo 'aqui'; exit;
        $this->load->view('admin/medico_substituto/manage', $data);
    }
    
    public function table()
    {
        $this->app->get_table_data('medicos_substitutos');
    }
    
    public function manage()
    {
            if ($this->input->post()) {
                $data = $this->input->post();
                $id = $this->input->post('id');
                    $success = $this->medicos_model->add_edit_substituto($data, $id);
                    if($success){
                        redirect('admin/medico_substituto');
                    }
        }
    }
    public function delete() {
        $id = $_GET['id'];
        $dados['deleted'] = 1;
        $this->db->where('id', $id);

        $this->db->update('tblmedico_substituto', $dados);
        redirect('admin/medico_substituto');
    }
    
    
    
}
