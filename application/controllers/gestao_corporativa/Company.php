<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Company extends ClientsController
{

    public function __construct()
    {

        parent::__construct();
        $this->load->model('Company_model');
        $this->load->model('Intranet_general_model');
    }

    /* List all staff roles */

    public function index()
    {
        exit;
    }

    public function update_notifications()
    {
        //print_r($this->input->post()); exit;
        $empresa_id = $this->session->userdata('empresa_id');
        $active = $this->input->post('active');
        $feature = $this->input->post('feature');
        $capability = $this->input->post('capability');
        if ($active == 1) {
            $this->db->where('empresa_id', $empresa_id);
            $this->db->where('feature', $feature);
            $this->db->where('capability', $capability);
            $exist = $this->db->get(db_prefix() . 'empresas_notifications')->row();
            if (!isset($exist)) {
                $data = [
                    'empresa_id' => $empresa_id,
                    'feature' => $feature,
                    'capability' => $capability,
                ];
                $this->db->insert(db_prefix() . 'empresas_notifications', $data);
            }
            echo json_encode(['alert' => 'success', 'message' => "[$feature][$capability] " . 'Ativado']);
        } else {
            $this->db->where('empresa_id', $empresa_id);
            $this->db->where('feature', $feature);
            $this->db->where('capability', $capability);
            $this->db->delete(db_prefix() . 'empresas_notifications');
            echo json_encode(['alert' => 'warning', 'message' => "[$feature][$capability] " . 'Desativado']);
        }
    }

    public function table_dates_ticket()
    {

        $this->app->get_table_data_intranet('dates_ticket');
    }

    public function table_cancel()
    {

        $this->app->get_table_data_intranet('cancel_workflow');
    }

    public function table_macs()
    {

        $this->app->get_table_data_intranet('macs');
    }

    public function table_assignature()
    {

        $this->app->get_table_data_intranet('macs');
    }

    public function table_assignature_others()
    {

        $this->app->get_table_data_intranet('macs');
    }

    public function table_list()
    {

        $this->app->get_table_data_intranet('list');
    }

    public function table_list_options()
    {

        $this->app->get_table_data_intranet('list_options');
    }

    public function add_date_ticket()
    {


        if ($this->input->post('date')) {


            $sql = "INSERT INTO tbl_intranet_portal_tickets (date_open,user_create,dt_created,empresa_id) VALUES('" . $this->input->post('date') . "', '" . get_staff_user_id() . "', '" . date('Y-m-d H:i:s') . "', '" . $this->session->userdata('empresa_id') . "')";
            //echo $sql; exit;


            $this->db->query($sql);

            echo json_encode(['alert' => 'success', 'message' => "Salvo com sucesso!"]);
        } else {
            echo json_encode(['alert' => 'danger', 'message' => 'Tente novamente.']);
        }
    }

    public function add_cancel()
    {


        if ($this->input->post('cancellation')) {


            $sql = "INSERT INTO tbl_intranet_workflow_cancel (cancellation,user_create,dt_created,empresa_id) VALUES('" . $this->input->post('cancellation') . "', '" . get_staff_user_id() . "', '" . date('Y-m-d H:i:s') . "', '" . $this->session->userdata('empresa_id') . "')";

            if ($this->db->query($sql)) {
                echo json_encode(['alert' => 'success', 'message' => "Salvo com sucesso!"]);
            }
        } else {
            echo json_encode(['alert' => 'danger', 'message' => 'Tente novamente.']);
        }
    }

    public function add_list_option()
    {


        if ($this->input->post('option')) {
            $data = $this->input->post();
            $data['user_created'] = get_staff_user_id();
            $data['date_created'] = date('Y-m-d H:i:s');
            $data['empresa_id'] = $this->session->userdata('empresa_id');
            if ($this->db->insert(db_prefix() . "_intranet_list_options", $data)) {
                echo json_encode(['alert' => 'success', 'message' => "Salvo com sucesso!"]);
            }
        } else {
            echo json_encode(['alert' => 'danger', 'message' => 'Tente novamente.']);
        }
    }

    public function add_mac()
    {


        if ($this->input->post('mac')) {
            $data = $this->input->post();
            $data['user_created'] = get_staff_user_id();
            $data['date_created'] = date('Y-m-d H:i:s');
            $data['empresa_id'] = $this->session->userdata('empresa_id');
            $timestamp = strtotime($data['date_limit']);
            $data['date_limit'] = date('Y-m-d H:i:s', $timestamp);
            if ($this->db->insert(db_prefix() . "_intranet_macs", $data)) {
                echo json_encode(['alert' => 'success', 'message' => "Salvo com sucesso!"]);
            }
        } else {
            echo json_encode(['alert' => 'danger', 'message' => 'Tente novamente.']);
        }
    }

    public function add_tww_info()
    {

        if ($this->input->post('twwuser') || $this->input->post('twwpassword')) {
            $infos = ['twwuser', 'twwpassword'];
            $data = $this->input->post();
            $empresa_id = $this->session->userdata('empresa_id');
            foreach ($infos as $info) {
                $name = $info;
                $value = $this->input->post($info);

                // Verifica se já existe um registro com o mesmo nome
                $existing_option = $this->db->get_where(db_prefix() . "options", ['name' => $name, 'empresa_id' => $empresa_id])->row_array();

                if ($existing_option) {
                    // Se existir, atualize-o
                    $this->db->where(['id' => $existing_option['id']]);
                    $this->db->update(db_prefix() . "options", ['value' => $value]);
                } else {
                    // Caso contrário, insira um novo registro
                    $this->db->insert(db_prefix() . "options", ['name' => $name, 'value' => $value, 'empresa_id' => $empresa_id, 'saas_tenant_id' => strstr($_SERVER['HTTP_HOST'], '.sigplus.app.br', true)]);
                }
            }

            echo json_encode(['alert' => 'success', 'message' => "Salvo com sucesso!"]);
        } else {
            echo json_encode(['alert' => 'danger', 'message' => 'Tente novamente.']);
        }
    }

    public function add_list()
    {


        if ($this->input->post('list')) {


            $sql = "INSERT INTO tbl_intranet_lists (list,user_created,date_created,empresa_id) VALUES('" . $this->input->post('list') . "', '" . get_staff_user_id() . "', '" . date('Y-m-d H:i:s') . "', '" . $this->session->userdata('empresa_id') . "')";

            if ($this->db->query($sql)) {
                echo json_encode(['alert' => 'success', 'message' => "Salvo com sucesso!"]);
            }
        } else {
            echo json_encode(['alert' => 'danger', 'message' => 'Tente novamente.']);
        }
    }

    public function delete_date_ticket()
    {

        if ($this->input->post('id')) {

            $id = $this->input->post('id');

            $dados['deleted'] = 1;

            $this->db->where('id', $id);

            $this->db->update('tbl_intranet_portal_tickets', $dados);

            echo json_encode(['alert' => 'success', 'message' => "Deletado com sucesso!"]);
        } else {
            echo json_encode(['alert' => 'danger', 'message' => 'Tente novamente.']);
        }
    }

    public function delete_cancel()
    {

        if ($this->input->post('id')) {

            $id = $this->input->post('id');

            $dados['deleted'] = 1;

            $this->db->where('id', $id);

            $this->db->update('tbl_intranet_workflow_cancel', $dados);

            echo json_encode(['alert' => 'success', 'message' => "Deletado com sucesso!"]);
        } else {
            echo json_encode(['alert' => 'danger', 'message' => 'Tente novamente.']);
        }
    }

    public function delete_list()
    {

        if ($this->input->post('id')) {

            $id = $this->input->post('id');

            $dados['deleted'] = 1;

            $this->db->where('id', $id);

            $this->db->update('tbl_intranet_lists', $dados);

            echo json_encode(['alert' => 'success', 'message' => "Deletado com sucesso!"]);
        } else {
            echo json_encode(['alert' => 'danger', 'message' => 'Tente novamente.']);
        }
    }

    public function delete_mac()
    {

        if ($this->input->post('id')) {

            $id = $this->input->post('id');

            $dados['deleted'] = 1;

            $this->db->where('id', $id);

            $this->db->update('tbl_intranet_macs', $dados);

            echo json_encode(['alert' => 'success', 'message' => "Deletado com sucesso!"]);
        } else {
            echo json_encode(['alert' => 'danger', 'message' => 'Tente novamente.']);
        }
    }

    public function delete_list_option()
    {

        if ($this->input->post('id')) {

            $id = $this->input->post('id');

            $dados['deleted'] = 1;

            $this->db->where('id', $id);

            $this->db->update('tbl_intranet_list_options', $dados);

            echo json_encode(['alert' => 'success', 'message' => "Deletado com sucesso!"]);
        } else {
            echo json_encode(['alert' => 'danger', 'message' => 'Tente novamente.']);
        }
    }

    public function date_active()
    {

        if ($this->input->post()) {

            $data = $this->input->post();
            if ($data['active'] != 1) {
                $data['active'] = 0;
            }


            $this->db->where('id', $data['id']);

            $this->db->update('tbl_intranet_portal_tickets', $data);

            echo json_encode(['alert' => 'success', 'message' => "Salvo com sucesso!"]);
        } else {
            echo json_encode(['alert' => 'danger', 'message' => 'Tente novamente.']);
        }
    }

    public function cancel_active()
    {

        if ($this->input->post()) {

            $data = $this->input->post();
            if ($data['active'] != 1) {
                $data['active'] = 0;
            }


            $this->db->where('id', $data['id']);

            $this->db->update('tbl_intranet_workflow_cancel', $data);

            echo json_encode(['alert' => 'success', 'message' => "Salvo com sucesso!"]);
        } else {
            echo json_encode(['alert' => 'danger', 'message' => 'Tente novamente.']);
        }
    }

    public function mac_active()
    {

        if ($this->input->post()) {

            $data = $this->input->post();
            if ($data['active'] != 1) {
                $data['active'] = 0;
            }


            $this->db->where('id', $data['id']);

            $this->db->update('tbl_intranet_macs', $data);

            echo json_encode(['alert' => 'success', 'message' => "Salvo com sucesso!"]);
        } else {
            echo json_encode(['alert' => 'danger', 'message' => 'Tente novamente.']);
        }
    }

    public function table_api()
    {

        $this->app->get_table_data_intranet('api');
    }

    public function table_acess_type()
    {

        $this->app->get_table_data_intranet('acess_type');
    }

    public function api()
    {

        if ($this->input->post('name')) {
            $id = $this->input->post('id');
            $info = $this->input->post();
            $info['user_created'] = get_staff_user_id();
            $info['date_created'] = date('Y-m-d H:i:s');
            $info['empresa_id'] = $this->session->userdata('empresa_id');
            //$info['saas_tenant_id'] = 'real';
            unset($info['id']);
            //print_r($info); exit;
            if ($id == '') {
                if ($this->db->insert("tbl_intranet_api", $info)) {
                    //ECHO 'JSJ'; EXIT;
                    echo json_encode(array("alert" => "success", "message" => "Salvo com Sucesso!"));
                } else {
                    //ECHO 'JSJ'; EXIT;
                    echo json_encode(array("alert" => "danger", "message" => "Erro ao Salvar"));
                }
            } else {
                $this->db->where('id', $id);

                if ($this->db->update('tbl_intranet_api', $info)) {
                    echo json_encode(array("alert" => "success", "message" => "Salvo com Sucesso!"));
                } else {
                    echo json_encode(array("alert" => "danger", "message" => "Erro ao Salvar"));
                }
            }

            exit;
        }

        $this->load->view('gestao_corporativa/intranet/cadastros/groups/api', $data);
    }

    public function edit_notify()
    {
        $data['key'] = $this->input->post('key');
        $this->load->view('gestao_corporativa/intranet/cadastros/groups/edit_notify', $data);
    }

    public function acess_type()
    {

        if ($this->input->post('name')) {
            $id = $this->input->post('id');
            $info = $this->input->post();
            $info['user_created'] = get_staff_user_id();
            $info['date_created'] = date('Y-m-d H:i:s');
            $info['empresa_id'] = $this->session->userdata('empresa_id');
            //$info['saas_tenant_id'] = 'real';
            unset($info['id']);
            //print_r($info); exit;
            if ($id == '') {
                if ($this->db->insert("tbl_intranet_acess_type", $info)) {
                    //ECHO 'JSJ'; EXIT;
                    echo json_encode(array("alert" => "success", "message" => "Salvo com Sucesso!"));
                } else {
                    //ECHO 'JSJ'; EXIT;
                    echo json_encode(array("alert" => "danger", "message" => "Erro ao Salvar"));
                }
            } else {
                $this->db->where('id', $id);

                if ($this->db->update('tbl_intranet_acess_type', $info)) {
                    echo json_encode(array("alert" => "success", "message" => "Salvo com Sucesso!"));
                } else {
                    echo json_encode(array("alert" => "danger", "message" => "Erro ao Salvar"));
                }
            }

            exit;
        }

        $this->load->model('Categorias_campos_model');

        $data['apis'] = $this->Categorias_campos_model->get_categorias_without_ra('api');
        //print_r($data['apis']); exit;
        $this->load->view('gestao_corporativa/intranet/cadastros/groups/acess_type', $data);
    }

    public function save_info_notify()
    {

        if ($this->input->post()) {

            $info = $this->input->post();
            $info['user_update'] = get_staff_user_id();
            $info['date_update'] = date('Y-m-d H:i:s');
            $info['empresa_id'] = $this->session->userdata('empresa_id');

            //echo "select * from tbl_intranet_info_notify where key = '".$info['key_']."'"; exit;
            $notify = $this->db->query("select * from tbl_intranet_info_notify where key_ = '" . $info['key_'] . "'")->row();
            //print_r($notify); exit;
            if ($notify == '') {
                if ($this->db->insert("tbl_intranet_info_notify", $info)) {
                    //ECHO 'JSJ'; EXIT;
                    echo json_encode(array("alert" => "success", "message" => "Salvo com Sucesso!"));
                } else {
                    //ECHO 'JSJ'; EXIT;
                    echo json_encode(array("alert" => "danger", "message" => "Erro ao Salvar"));
                }
            } else {
                $this->db->where('key_', $info['key_']);

                if ($this->db->update('tbl_intranet_info_notify', $info)) {
                    echo json_encode(array("alert" => "success", "message" => "Salvo com Sucesso!"));
                } else {
                    echo json_encode(array("alert" => "danger", "message" => "Erro ao Salvar"));
                }
            }
            redirect(base_url('gestao_corporativa/intranet_admin/index/?group=company'));
        }
    }

    public function select_acess_type()
    {


        //echo 'select * from tbl_intranet_acess_type where id = ' . $this->input->post('id'); exit;
        $type = $this->db->query('select * from tbl_intranet_acess_type where id = ' . $this->input->post('id'))->row();

        $this->load->model('Categorias_campos_model');
        //echo $type->api_id; exit;
        $api = $this->Categorias_campos_model->get_categoria($type->api_id, 'api');

        $campos = $this->Categorias_campos_model->get_categoria_campos_all($api->id, 'api', 1);

        //print_r($campos); exit;
        echo '<p class="mb-4 text-sm mx-auto">' . $type->obs . '</p>';
        foreach ($campos as $campo) {
            echo '<div class="input-group input-group-static mb-1">
                    <label>' . $campo['nome'] . '</label>
                    <input name="' . $campo['name'] . '" type="text" class="form-control" placeholder="Informe ' . $campo['nome'] . '">
                  </div>';
        }
    }

    public function campos_api()
    {

        $data['type'] = $this->input->post('type');
        $data['id'] = $this->input->post('id');

        $this->load->view('gestao_corporativa/intranet/cadastros/groups/api_campos', $data);
    }

    public function add_campos_in()
    {

        $data['type'] = $this->input->post('type');
        $data['api_id'] = $this->input->post('id');
        $data['empresa_id'] = $this->session->userdata('empresa_id');

        $this->db->where("api_id", $data['api_id']);
        $this->db->delete("tbl_intranet_api_campos", $data);

        $campos = $this->input->post('campos');
        //$campos = explode(',', $this->input->post('options'));


        foreach ($campos as $campo) {
            $data['name'] = $campo;
            $this->db->insert("tbl_intranet_api_campos", $data);
        }

        echo json_encode(array("alert" => "success", "message" => "Salvo com Sucesso!"));
    }

    public function modal()
    {

        if ($this->input->post('slug') === 'list') {
            $data['id'] = $this->input->post('id');
            $data['list'] = $this->Company_model->get_list($data['id']);
            $this->load->view('gestao_corporativa/intranet/cadastros/groups/list_modal', $data);
        }

        if ($this->input->post('slug') === 'add_terceiro') {
            $this->load->model('staff_model');
            $data['staffs'] = $this->staff_model->get();
            $data['terceiro'] = $this->Company_model->get_terceiros($this->input->post('id'));
            $this->load->view('gestao_corporativa/terceiros/add_terceiro', $data);
        }
        if ($this->input->post('slug') === 'view_terceiros') {
            $data['id'] = $this->input->post('id');
            $this->load->view('gestao_corporativa/terceiros/view_terceiros', $data);
        }
    }

    public function add_assignature()
    {

        $data_file['url'] = $this->input->post('target') . $this->input->post('file');
        $data_file['file'] = $this->input->post('file');
        $data_file['description'] = $this->input->post('title');
        $data_file['rel_type'] = $this->input->post('rel_type');
        $data_file['date_created'] = date('Y-m-d H:i:s');
        $data_file['user_created'] = get_staff_user_id();
        $data_file['empresa_id'] = $this->session->userdata('empresa_id');

        $this->db->insert('tbl_intranet_files', $data_file);
        echo json_encode(array("alert" => "success", "message" => "Salvo com Sucesso!"));
    }

    public function add_terceiros($id = '')
    {
        $data['responsavel'] = $this->input->post('responsavel');
        $data['company'] = $this->input->post('name');
        $data['razao'] = $this->input->post('razao');
        $data['cnpj'] = $this->input->post('cnpj');
        $data['email'] = $this->input->post('email');
        $data['fone'] = $this->input->post('fone');
        $data['cor'] = $this->input->post('cor');
        $data['empresa_id'] = $this->session->userdata('empresa_id');



        if ($id != '') {
            $response = $this->Company_model->update_terceiro($id, $data);
            if ($response == true) {
                echo json_encode(array("alert" => "success", "message" => "Editado com Sucesso!"));
            } else {
                echo json_encode(array("alert" => "danger", "message" => "Falha!"));
            }
        } else {
            if ($this->db->insert('tbl_intranet_terceiros', $data)) {
                echo json_encode(array("alert" => "success", "message" => "Salvo com Sucesso!"));
            } else {
                echo json_encode(array("alert" => "danger", "message" => "Falha!"));
            }
        }
        redirect('gestao_corporativa/intranet_admin/index/?group=terceiros');
    }

    public function delete_terceiro($id)
    {
        if (!$id) {
            redirect('gestao_corporativa/intranet_admin/index/?group=terceiros');
        }
        $response = $this->Company_model->delete_terceiro($id);

        if ($response == true) {
            ///dfeu certo0
        } else {
            //deu errado
        }
        redirect('gestao_corporativa/intranet_admin/index/?group=terceiros');
    }
}
