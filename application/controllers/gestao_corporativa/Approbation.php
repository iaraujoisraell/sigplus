<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Approbation extends AdminController {

    public function __construct() {
        parent::__construct();
        $this->load->model('Approbation_model');
        $this->load->model('Categorias_campos_model');
    }

    /* Get all staff todo items */

    public function index() {
        $data['title'] = 'Aprovações Pendentes';
        $this->load->model('Departments_model');
        $data['departments'] = $this->Departments_model->get_staff_departments();
        $this->load->view('gestao_corporativa/intranet/approbation/all', $data);
    }

    public function table() {

        $this->app->get_table_data_intranet('approbation');
    }

    public function mudar_space() {

        $data['approbation'] = $this->Approbation_model->get_approbation_by_id($this->input->post('approbation_id'));

        if ($this->input->post('slug') == 'workflow') {
            $this->load->model('Workflow_model');
            $data['workflow'] = $this->Workflow_model->get_workflow_by_id($this->input->post('id'));

            $data['categoria'] = $this->Categorias_campos_model->get_categoria($data['workflow']->categoria_id);

            $this->load->view('gestao_corporativa/workflow/space_approbation', $data);
        }
    }

    public function action() {

        $info['action'] = $this->input->post('action');
        $info['id'] = $this->input->post('id');
        $info['obs'] = $this->input->post('obs');
        $info['approbation_id'] = $this->input->post('approbation_id');

        $slug = $this->input->post('slug');

        if ($slug == 'workflow') {
            $this->load->model('Workflow_model');
            $resposta = $this->Workflow_model->aprovar($info);

            if ($resposta == 1) {
                $msg = 'WORKFLOW APROVADO';
                $class = 'success';
            } elseif($resposta == 2){
                $msg = 'WORKFLOW REPROVADO';
                $class = 'danger';
            }
        }

        if($resposta != '') {
            
            $dados['obs'] = $info['obs'];
            $dados['status'] = $resposta;
            $dados['date_aprovacao'] = date('Y-m-d H:i:s');
            $dados['user_aprovacao'] = get_staff_user_id();
            $this->db->where('id', $info['approbation_id']);
            if ($this->db->update('tbl_intranet_approbation', $dados)) {
                $approbation = $this->Approbation_model->get_approbation_by_id($info['approbation_id']);
                echo '
                    <div class="col-md-12">
                        <div class="alert alert-' . $class . ' alert-dismissible">
                            <h5><i class="icon fa fa-info-circle"></i> ' . $msg . '!</h5>
                            <p><strong style="font-weight: bold;">Data:</strong> ' . date("d/m/Y H:i:s", strtotime($approbation->date_aprovacao)) . '</p>
                            <p><strong style="font-weight: bold;">Usuário:</strong> ' . get_staff_full_name($approbation->user_aprovacao) . '</p>
                            <p><strong style="font-weight: bold;">Motivo/Ressalva:</strong> ' . $approbation->obs . '</p>
                            
                        </div>
           </div>';
            }
        }
    }
    
    

}
