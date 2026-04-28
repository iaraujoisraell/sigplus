<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Profile_type extends AdminController {

    public function __construct() {

        parent::__construct();

        $this->load->model('Intranet_model');

        $this->load->model('Comunicado_model');

        $this->load->model('Departments_model');
        $this->load->model('Staff_model');
        $this->load->model('Intranet_general_model');
        
    }
    /* List all staff roles */
    public function index()
    {
        if (!has_permission('roles', '', 'view')) {
            access_denied('roles');
        }
        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data('roles');
        }
        $data['title'] = _l('all_roles');
        $this->Intranet_general_model->add_log(["rel_type" => "profile_type", "controller" => "Profile_type", "function" => "index", "action" => "Tipos de Usuários (Visão Geral)"]);
        $this->load->view('gestao_corporativa/profile_type/manage', $data);
    }

    /* Add new role or edit existing one */
    public function profile_type($id = '')
    {
       $this->load->model('roles_model');
       $this->load->model('Profile_type_intranet_model');
       $title = _l('add_new', _l('role_lowercase'));
        
        $data['title'] = $title;
         $this->Intranet_general_model->add_log(["rel_type" => "profile_type", "controller" => "Profile_type", "function" => "profile_type", "action" => "Visualizar tipo de usuário($id)", "rel_id" => $id]);
        $this->load->view('gestao_corporativa/profile_type/role', $data);
    }
    public function profile_type_add($id = '')
    {
        $this->load->model('Profile_type_intranet_model');
         if ($id == '') {
        
                $id = $this->Profile_type_intranet_model->add($this->input->post());
                if ($id) {
                    set_alert('success', _l('added_successfully', _l('role')));
                    $this->Intranet_general_model->add_log(["rel_type" => "profile_type", "controller" => "Profile_type", "function" => "profile_type_add", "action" => "Tipo de usuário cadastrado ($id)", "rel_id" => $id]);
                    redirect('gestao_corporativa/intranet_admin/index/?group=profile_type');
                }
            } else {
                
                $success = $this->Profile_type_intranet_model->update($this->input->post(), $id);
                if ($success) {
                    set_alert('success', _l('updated_successfully', _l('role')));
                }
                $this->Intranet_general_model->add_log(["rel_type" => "profile_type", "controller" => "Profile_type", "function" => "profile_type_add", "action" => "Tipo de usuário atualizado ($id)", "rel_id" => $id]);
                redirect('gestao_corporativa/intranet_admin/index/?group=profile_type');
            }

    }
    /* Delete role from database */
    public function delete($id)
    {
        $this->load->model('Profile_type_intranet_model');
        if (!$id) {
            redirect('gestao_corporativa/intranet_admin/index/?group=profile_type');
        }
        $response = $this->Profile_type_intranet_model->delete($id);
        if (is_array($response) && isset($response['referenced'])) {
            set_alert('warning', _l('is_referenced', _l('role_lowercase')));
        } elseif ($response == true) {
            set_alert('success', _l('deleted', _l('role')));
            $this->Intranet_general_model->add_log(["rel_type" => "profile_type", "controller" => "Profile_type", "function" => "delete", "action" => "Tipo de usuário deletado ($id)", "rel_id" => $id]);
        } else {
            set_alert('warning', _l('problem_deleting', _l('role_lowercase')));
        }
        redirect('gestao_corporativa/intranet_admin/index/?group=profile_type');
    }
    
    
    
    /**
     * 05/12/2022
     * @WannaLuiza
     * Chama os modais
     */
    public function modal() {


        if ($this->input->post('slug') === 'add_type') {

            $data = $this->input->post();
            $this->load->model('Profile_type_intranet_model');
            $data['role'] = $this->Profile_type_intranet_model->get($data['id']);
            $this->load->view('gestao_corporativa/profile_type/add_type', $data);
        
    }
    }
}
