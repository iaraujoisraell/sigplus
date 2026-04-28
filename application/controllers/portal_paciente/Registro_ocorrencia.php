<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Registro_ocorrencia extends ClientsController {

    public function __construct() {
        parent::__construct();
        $this->load->model('Categorias_campos_model');
        $this->load->model('Staff_model');
        $this->load->model('Departments_model');
        $this->load->model('Registro_ocorrencia_model');
        $this->load->model('Clients_model');
        $this->load->model('Atendimentos_model');
        $this->load->model('Workflow_model');
    }

    public function index($categoria_id) {

        $id_client = get_client_user_id();
        if (!$id_client) {
            redirect(base_url('Authentication/logout'));
        }

        $_d["info_perfil"] = $this->Clients_model->get($id_client);
        $_d['categoria'] = $this->Categorias_campos_model->get_categoria($categoria_id);
        $_d['campos'] = $this->Categorias_campos_model->get_categoria_campos($categoria_id);

        $_d["tittle"] = $_d['categoria']->titulo;
        $_d["topo"] = $_d['categoria']->titulo;
        $_d["staffs"] = $this->Staff_model->get();
        
       

        $_d['protocolo'] = $this->session->userdata('protocolo'); 

        $_d['registros'] = $this->Registro_ocorrencia_model->get_ros_categoria_client($categoria_id, $id_client); 

        $this->load->model('Atendimentos_model');
        $_d['atendimento'] = $this->Atendimentos_model->get_ra_by_id($this->session->userdata('atendimento_id'));
   
       // echo "aqui"; exit;

        $this->load->view('portal/registro_ocorrencia/index.php', $_d);
    }

//    public function index_old($categoria_id) {
//
//        $id_client = get_client_user_id();
//        if (!$id_client) {
//            redirect(base_url('Authentication/logout'));
//        }
//
//        $_d["info_perfil"] = $this->Clients_model->get($id_client);
//        $_d['categoria'] = $this->Categorias_campos_model->get_categoria($categoria_id);
//        $_d['campos'] = $this->Categorias_campos_model->get_categoria_campos($categoria_id);
//        //print_r($_d['campos']); exit;
//
//        $_d["tittle"] = $_d['categoria']->titulo;
//        $_d["topo"] = $_d['categoria']->titulo;
//        $_d["staffs"] = $this->Staff_model->get();
//
//        $_d["setores"] = $this->Departments_model->get();
//
//        $_d['protocolo'] = $this->session->userdata('protocolo');
//
//        $_d['registros'] = $this->Registro_ocorrencia_model->get_ros_categoria_client($categoria_id, $id_client);
//
//        //$this->load->model('Clients_model');
//        //$_d["info_perfil"] = $this->Clients_model->get('1');
//
//        $_d['protocolo'] = $this->session->userdata('protocolo');
//
//        $this->load->model('Atendimentos_model');
//        $_d['atendimento'] = $this->Atendimentos_model->get_ra_by_id($this->session->userdata('atendimento_id'));
//        //echo 'aqui'; exit;
//        //echo $_d['protocolo']; exit;
//
//        $this->load->view('portal/registro_ocorrencia/index.php', $_d);
//    }

    public function add() {
        //print_r($this->input->post()); exit;
        $this->load->model('Categorias_campos_model');
        $this->load->model('Registro_ocorrencia_model');

        if ($this->input->post()) {
          //print_r($this->input->post()); exit;
            //echo $this->input->post('categoria_id'); exit;
            $campos = $this->Categorias_campos_model->get_categoria_campos_all($this->input->post('categoria_id'), 'r.o');
            //print_r($campos); exit;
            $info = [
                "subject" => $this->input->post('assunto'),
                "report" => $this->input->post('descricao'),
                "date" => $this->input->post('data_ocorrido'),
                "priority" => $this->input->post('priority'),
                "categoria_id" => $this->input->post('categoria_id'),
                "registro_atendimento_id" => $this->session->userdata('atendimento_id'),
                "arquivos" => $this->input->post('attachment'),
            ];
            $info['status'] = '1';

            //print_r($info); exit;
            $id = $this->Registro_ocorrencia_model->add($info);

            //echo $id; exit;
            //NOTIFICAÇÕES
            $action = 'ro_received';
            $action_info = get_actions($action);

            $this->load->model('Comunicacao_model');
            $this->load->model('Departments_model');
            $categoria = $this->Categorias_campos_model->get_categoria($this->input->post('categoria_id'));
            $staffs_department = $this->Departments_model->get_department_staffs($categoria->responsavel);
            $action_info['rel_type'] = 'r.o';
            $action_info['rel_id'] = $id;
            $action_info['link_sigplus'] = 'gestao_corporativa/Registro_ocorrencia/registro/' . openssl_encrypt($id, "aes-256-cbc", 'sigplus', 0, 'sigplus');
            foreach ($staffs_department as $staff) {
                $action_info['email_destino'] = $staff['email'];
                $action_info['staff_destino'] = $staff['staffid'];
                $action_info['phone_destino'] = $staff['phonenumber'];

                $this->Comunicacao_model->send_sigplus_email_sms($action, $action_info);
            }

            //echo 'aqui'; exit;

            foreach ($campos as $campo) {

                $campos_value = array(
                    "categoria_id" => $this->input->post('categoria_id'),
                    "registro_id" => $id,
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

//                /print_r($campos_value);
                $this->Registro_ocorrencia_model->add_campo_value($campos_value);
            }
            if ($id) {
                set_alert('success', _l('Registro Salvo!', $id));
                redirect(base_url('portal/registro_ocorrencia/index/' . $this->input->post('categoria_id')));
            }
        }
    }

    public function registro($id = '') {
   
       
         $id_client = get_client_user_id();
        if (!$id_client) {
            redirect(base_url('Authentication/logout'));
        }

      //  echo $id; exit;
        if($id == ''){
            $id = $_GET['id'];
        }
        
       // $id = hash('sha256', $id); 
                
        $registro = $this->Registro_ocorrencia_model->get_ro_by_id($id);  // echo "aqui"; exit;

        $_d["tittle"] = strtoupper($registro->subject);
        
        $_d['notes'] = $this->Registro_ocorrencia_model->get_notes($id, 'answer', 'solicitante');

        $_d["info_perfil"] = $this->Clients_model->get($id_client);
        //print_r($registro); exit;
        $_d["registro"] = $registro;

        $_d['protocolo'] = $this->session->userdata('protocolo');

        $this->load->model('Atendimentos_model');
        $_d['atendimento'] = $this->Atendimentos_model->get_ra_by_id($this->session->userdata('atendimento_id'));

        $this->load->view('portal/registro_ocorrencia/single.php', $_d);
    }

}
