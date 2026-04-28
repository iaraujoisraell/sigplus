<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Workflow extends ClientsController {

    function __construct() {

        parent::__construct();

        $this->load->model('Clients_model');
        $this->load->model('Categorias_campos_model');
        $this->load->model('Workflow_model');
        $this->load->model('Staff_model');
        $this->load->model('Departments_model');
    }

    public function index() {



        $_d["tittle"] = 'Solicitações';
        $_d["topo"] = 'Solicitações';

        $id_client = get_client_user_id();
        if (!$id_client) {
            redirect(base_url('Authentication/logout'));
        }

        $_d["info_perfil"] = $this->Clients_model->get($id_client);

        $_d['protocolo'] = $this->session->userdata('protocolo');

        $_d['categorias'] = $this->Categorias_campos_model->get_categorias_with_ra('workflow', 'portal');
        $this->load->model('Atendimentos_model');
        $_d['atendimento'] = $this->Atendimentos_model->get_ra_by_id($this->session->userdata('atendimento_id'));
        $_d['workflows'] = $this->Workflow_model->get_workflow_by_client_id(get_client_user_id());
        //print_r($_d['workflows']); EXIT;


        $this->load->view('portal/workflow/index.php', $_d);
    }

    public function campos() {


        $categoria_id = $_GET["categoria_id"];

        $categoria = $this->Categorias_campos_model->get_categoria($categoria_id);

        $campos = $this->Categorias_campos_model->get_categoria_campos($categoria_id);
        $data["doctos"] = $this->Categorias_campos_model->get_doctos($categoria_id, '', false, true);

        $data["staffs"] = $this->Staff_model->get();

        $data["setores"] = $this->Departments_model->get();

        $data['id'] = $categoria_id;
        $data['campos'] = $campos;
        $data['categoria'] = $categoria;
        if (count($campos) > 0) {
            $this->load->view('portal/categorias_campos/campos', $data);
        }
    }

    /**
     * 11/04/2023
     * @WannaLuiza
     * adiciona arquivo
     */
    public function add() {


        $categoria = $this->Categorias_campos_model->get_categoria($this->input->post('categoria_id'));
        $campos = $this->Categorias_campos_model->get_categoria_campos($this->input->post('categoria_id'));
        $info = [
            "categoria_id" => $this->input->post('categoria_id'),
            "date_created" => date('Y-m-d H:i:s'),
            "client_id" => get_client_user_id(),
            "status" => '1',
            "portal" => '1',
            "registro_atendimento_id" => $this->session->userdata('atendimento_id')
        ];
        $info['date_start'] = date('Y-m-d H:i:s');
        $info['date_prazo'] = date('Y-m-d', strtotime("+" . $categoria->prazo . " days", strtotime(date('Y-m-d H:i:s'))));
        $info['date_prazo_client'] = date('Y-m-d', strtotime("+" . $categoria->prazo_cliente . " days", strtotime(date('Y-m-d H:i:s'))));

        //print_r($info); exit;
        $id = $this->Workflow_model->add($info);
        //echo $id; exit;

        $fluxo = $this->Workflow_model->get_categoria_fluxo($this->input->post('categoria_id'));
        $fluxo_inicial = array(
            "categoria_id" => $this->input->post('categoria_id'),
            "fluxo_id" => $fluxo->id,
            "workflow_id" => $id,
            "fluxo_sequencia" => '1',
            "department_id" => $fluxo->setor,
            "date_created" => date('Y-m-d H:i:s'),
            "date_received" => date('Y-m-d H:i:s'),
            "client_id" => get_client_user_id(),
            "empresa_id" => $this->session->userdata('empresa_id'),
            "data_prazo" => date('Y-m-d', strtotime("+" . $fluxo->prazo . " days", strtotime(date('Y-m-d H:i:s')))),
        );
        $this->Workflow_model->add_workflow_fluxo($fluxo_inicial);

        foreach ($campos as $campo) {

            $campos_value = array(
                "categoria_id" => $this->input->post('categoria_id'),
                "rel_id" => $id,
                "campo_id" => $campo['id'],
                "data_cadastro" => date('Y-m-d H:i:s'),
                "user_cadastro" => get_staff_user_id(),
                "empresa_id" => $this->session->userdata('empresa_id')
            );

            $value = $this->input->post($campo['name']);

            if ($value) {
                if (is_array($value)) {
                    $value = implode(', ', $value);
                }
            }

            $campos_value['value'] = $value;
            $this->Workflow_model->add_campo_value($campos_value);
        }
        if ($id) {
            redirect(base_url('portal/workflow/index'));
        }
    }

    public function workflow($id) {



        $workflow = $this->Workflow_model->get_workflow_by_id($id);
        $_d["tittle"] = 'SOLICITAÇÃO ' . strtoupper($workflow->titulo);

        $id_client = get_client_user_id();
        if (!$id_client) {
            redirect(base_url('Authentication/logout'));
        }

        $_d["info_perfil"] = $this->Clients_model->get($id_client);
        $_d["workflow"] = $workflow;

        $_d['protocolo'] = $this->session->userdata('protocolo');

        $this->load->model('Atendimentos_model');
        $_d['atendimento'] = $this->Atendimentos_model->get_ra_by_id($this->session->userdata('atendimento_id'));

        $_d['requests'] = $this->Workflow_model->get_resquest_external($workflow->id);

        $this->load->view('portal/workflow/single.php', $_d);
    }

    public function finish_external_request($id = '') {
        $dados['date'] = date('Y-m-d H:i:s');
        $dados['status'] = 1;

        $this->db->where('id', $this->input->post('request_id'));

        if ($this->db->update('tbl_intranet_workflow_external_request', $dados)) {
            $campos = $this->Categorias_campos_model->get_categoria_campos($this->input->post('request_id'), '', true, 'external_request_workflow');
            //print_r($campos); exit;
            foreach ($campos as $campo) {


                $campos_value = array(
                    "categoria_id" => $this->input->post('request_id'),
                    "rel_id" => $this->input->post('request_id'),
                    "rel_type" => 'external_request_workflow',
                    "campo_id" => $campo['id'],
                    "data_cadastro" => date('Y-m-d H:i:s'),
                    "user_cadastro" => get_staff_user_id(),
                    "empresa_id" => $this->session->userdata('empresa_id')
                );

                if ($campo['type'] == 'file') {
                    $campo_name = $campo['name']; // Substitua isso pelo nome real do seu campo de envio de arquivos

                    if (isset($_FILES[$campo_name]) && $_FILES[$campo_name]['error'] === UPLOAD_ERR_OK) {
                        $file = $_FILES[$campo_name];

                        // Extrai a extensão original do arquivo
                        $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);

                        // Gera um novo nome de arquivo único com a extensão original
                        $new_filename = 'WF' . $id . '_' . $campo['name'] . uniqid() . '.' . $file_extension;

                        // Verifica se o diretório de destino existe; se não existir, cria-o recursivamente
                        if (!file_exists("./assets/intranet/arquivos/workflow_arquivos/campo_file/")) {
                            mkdir("./assets/intranet/arquivos/workflow_arquivos/campo_file/", 0777, true);
                        }

                        // Define o caminho completo para o destino do arquivo enviado
                        $destination = "./assets/intranet/arquivos/workflow_arquivos/campo_file/" . $new_filename;

                        // Move o arquivo enviado para o destino com o novo nome de arquivo
                        if (move_uploaded_file($file["tmp_name"], $destination)) {
                            // Upload do arquivo bem-sucedido
                            $value = $new_filename;
                        } else {
                            // Erro ao mover o arquivo
                            $value = '';
                        }
                    } else {
                        // Erro durante o upload do arquivo
                        $value = '';
                    }
                } else {
                    $value = $this->input->post($campo['name']);
                }

                if ($value) {
                    if (is_array($value)) {
                        $value = implode(', ', $value);
                    }
                }

                $campos_value['value'] = $value;
                //print_r($campos_value); exit;
                $this->Workflow_model->add_campo_value($campos_value);
            }
        }
        redirect(base_url() . 'portal/workflow/workflow/' . $id);
    }
}
