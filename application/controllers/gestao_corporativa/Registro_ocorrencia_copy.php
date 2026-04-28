<?php

defined('BASEPATH') or exit('No direct script access allowed');

use Dompdf\Options;
use Dompdf\Dompdf;

class Registro_ocorrencia extends AdminController {

    public function __construct() {
        parent::__construct();

        if (!is_logged_in()) {
            access_denied('Registro_ocorrencia');
            redirect($_SERVER['HTTP_REFERER']);
        }

        $this->load->model('Staff_model');
        $this->load->model('Registro_ocorrencia_model');
        $this->load->model('tickets_model');
        $this->load->model('Categorias_campos_model');
        $this->load->model('Intranet_general_model');
        // $this->load->driver('cache');
    }

    public function index() {


        $this->Intranet_general_model->add_log(["rel_type" => "r.o", "controller" => "Registro_ocorrencia", "function" => "index", "action" => "Registro de Ocorrência (Visão Geral)"]);
        //echo 'aqui'; exit;

        if (!get_staff_user_id()) {
            access_denied('R.O.');
            redirect($_SERVER['HTTP_REFERER']);
        }


        close_setup_menu();

        $data['title'] = 'Registros de Ocorrência';
        $this->load->model('departments_model');

        $data['departments'] = $this->departments_model->get_staff_departments();
        //$data['staffs'] = $this->Staff_model->get();
        //$data['tipos'] = $this->Registro_ocorrencia_model->get_categorias();
        $data['bodyclass'] = 'tickets-page';
        //$data['atuantes'] = $this->Registro_ocorrencia_model->get_atuantes();
        $data['statuses'] = $this->Registro_ocorrencia_model->get_ro_status();

        $data['atribuidos'] = $this->Registro_ocorrencia_model->get_coluna('atribuido_a');
        //print_r( $data['atribuidos']); EXIT;
        $data['notificantes'] = $this->Registro_ocorrencia_model->get_coluna('user_created');

        $data['categorias'] = $this->Categorias_campos_model->get_categorias('r.o');

        $data['week'] = date('Y-m-d', strtotime("-6 days", time()));

        $filters_ = $this->Intranet_general_model->get_filters('r.o');

        $filters = [];
        foreach ($filters_ as $filter) {
            $tab = $filter['tab'];

            // Add the "filter" and "value" pairs to the tab's array
            $filters[$tab][$filter['filter']] = $filter['value'];
        }
        $data['filters'] = $filters;
        $this->load->view('gestao_corporativa/registro_ocorrencia/list', $data);
    }

    /**
     * 03/12/2022
     * @WannaLuiza
     * Tira pontuação
     */
    function tira_pontuacao_espaco_caractereespecial($string) {

        // matriz de entrada
        $what = array('ä', 'ã', 'à', 'á', 'â', 'ê', 'ë', 'è', 'é', 'ï', 'ì', 'í', 'ö', 'õ', 'ò', 'ó', 'ô', 'ü', 'ù', 'ú', 'û', 'À', 'Á', 'É', 'Í', 'Ó', 'Ú', 'ñ', 'Ñ', 'ç', 'Ç', ' ', '-', '(', ')', ',', ';', ':', '|', '!', '"', '#', '$', '%', '&', '/', '=', '?', '~', '^', '>', '<', 'ª', 'º');

        // matriz de saída
        $by = array('a', 'a', 'a', 'a', 'a', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'A', 'A', 'E', 'I', 'O', 'U', 'n', 'n', 'c', 'C', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_');

        // devolver a string
        return str_replace($what, $by, $string);
    }

    /**
     * 18/10/2022
     * @WannaLuiza
     * Deleta Campo
     */
    public function delete_atuante() {

        if (!get_staff_user_id()) {
            access_denied('R.O.');
            redirect($_SERVER['HTTP_REFERER']);
        }



        $id = $this->input->post('id');

        $dados['deleted'] = 1;

        $this->db->where('id', $id);

        $this->Intranet_general_model->add_log(["rel_type" => "r.o", "controller" => "Registro_ocorrencia", "function" => "delete_atuante", "action" => "Atuante deletado ($id)"]);

        $this->db->update('tbl_intranet_registro_ocorrencia_atuantes', $dados);

        redirect('gestao_corporativa/registro_ocorrencia');
    }

    public function add($registro_atendimento_id = '') {

        if (!get_staff_user_id()) {
            access_denied('R.O.');
            redirect($_SERVER['HTTP_REFERER']);
        }

        //print_r($this->input->post()); exit;
        if ($this->input->post()) {

            //echo 'aqui'; exit;
            $campos = $this->Categorias_campos_model->get_categoria_campos_all($this->input->post('categoria_id'), 'r.o');

            $data = str_replace("/", "-", $this->input->post('data_ocorrido'));
            $data = date('Y-m-d', strtotime($data));

            $attachments = $this->input->post('attachments');

            if ($attachments) {
                if (is_array($attachments)) {
                    $attachments = implode(',', $attachments);
                }
            }
            //print_r($this->input->post()); exit;
            $info = [
                "subject" => $this->input->post('assunto'),
                "report" => $this->input->post('descricao'),
                "date" => $data,
                "priority" => $this->input->post('priority'),
                "categoria_id" => $this->input->post('categoria_id'),
                "registro_atendimento_id" => $this->input->post('registro_atendimento_id'),
              
            ];

            if($attachments){
                $info["arquivos"] =  $attachments;
            }

            $info['status'] = '1';

            $id = $this->Registro_ocorrencia_model->add($info);
            $this->Intranet_general_model->add_log(["rel_type" => "r.o", "controller" => "Registro_ocorrencia", "function" => "add", "action" => "Registro de ocorrência registrado ($id)"]);

            //NOTIFICAÇÕES
            $action = 'ro_received';

            $action_info = get_actions($action);

            $this->load->model('Comunicacao_model');
            $this->load->model('Departments_model');

            $categoria = $this->Categorias_campos_model->get_categoria($this->input->post('categoria_id'));

            $staffs_department = $this->Departments_model->get_department_staffs($categoria->responsavel);

            $action_info['rel_type'] = 'r.o';
            $action_info['rel_id'] = $id;
            $action_info['link_sigplus'] = 'gestao_corporativa/Registro_ocorrencia/registro/' . base64_encode(openssl_encrypt($id, "aes-256-cbc", 'sigplus', 0, 'sigplus'));
            foreach ($staffs_department as $staff) {
                $action_info['email_destino'] = $staff['email'];
                $action_info['staff_destino'] = $staff['staffid'];
                $action_info['phone_destino'] = $staff['phonenumber'];

                $this->Comunicacao_model->send_sigplus_email_sms($action, $action_info);
            }


            foreach ($campos as $campo) {

                $campos_value = array(
                    "categoria_id" => $this->input->post('categoria_id'),
                    "registro_id" => $id,
                    "campo_id" => $campo['id'],
                    "data_cadastro" => date('Y-m-d H:i:s'),
                    "user_cadastro" => get_staff_user_id(),
                    "empresa_id" => $this->session->userdata('empresa_id')
                );

                $value = $this->input->post('campo_' . str_replace('.', '_', $campo['name']));

                if ($value) {
                    if (is_array($value)) {
                        $value = implode(', ', $value);
                    }
                }

                $campos_value['value'] = $value;

                $this->Registro_ocorrencia_model->add_campo_value($campos_value);
                //print_r($campos_value);
            }
            if ($id) {
                set_alert('success', _l('Registro Salvo!', $id));
                redirect(base_url('gestao_corporativa/registro_ocorrencia/registro/' . base64_encode(openssl_encrypt($id, "aes-256-cbc", 'sigplus', 0, 'sigplus'))));
            }
        }

        $this->Intranet_general_model->add_log(["rel_type" => "r.o", "controller" => "Registro_ocorrencia", "function" => "add", "action" => "Registrar Ocorrência"]);
        $this->load->model('departments_model');

        $data['registro_atendimento_id'] = $registro_atendimento_id;
        $data['departments'] = $this->departments_model->get();
        $data['priorities'] = $this->tickets_model->get_priority();
        $data['categorias'] = $this->Categorias_campos_model->get_categorias_without_ra('r.o');

        if ($registro_atendimento_id) {
            $data['categorias'] = $this->Categorias_campos_model->get_categorias_with_ra('r.o');
        }

        $data['bodyclass'] = 'ticket';
        $data['title'] = 'Novo Registro';

        add_admin_tickets_js_assets();
        $this->load->view('gestao_corporativa/registro_ocorrencia/add', $data);
    }

    public function table() {

        $this->app->get_table_data_intranet('registro_ocorrencia');
    }

    public function table_atuantes() {

        $this->app->get_table_data_intranet('registro_ocorrencia_atuantes');
    }

    public function atualizar_ro() {


        $data = $this->input->post();
        $type = $this->input->post('type');
        unset($data['type']);
        unset($data['id']);
        unset($data['rel_type']);
        unset($data['members']);
        unset($data['msg']);

        $id = $this->input->post('id');
        $this->db->where('id', $id);
       

        if ($this->db->update('tbl_intranet_registro_ocorrencia', $data)) {
            $this->Intranet_general_model->add_log(["rel_type" => "r.o", "controller" => "Registro_ocorrencia", "function" => "atualizar_ro", "action" => "Registro de Ocorrência Atualizado (" . $id . ")"]);
            if ($type == 'ishikawa') {
                $this->Intranet_general_model->add_log(
                        [
                            "rel_id" => $this->input->post('id'),
                            "rel_type" => "ro_analise",
                            "controller" => "Registro_ocorrencia",
                            "function" => "atualizar_ro",
                            "action" => "Análise Ishikawa Atualizada (RO#" . $id . ")",
                            "msg" => $this->input->post('msg'),
                            "members" => (is_array($this->input->post('members'))) ? implode(',', $this->input->post('members')) : $this->input->post('members')
                        ]
                )
                ;

                $this->load->model('Comunicacao_model');
                $this->load->model('Staff_model');
                $action = 'ro_update_ishikawa';
                $action_info = get_actions($action);

                $registro = $this->Registro_ocorrencia_model->get_ro_by_id($id);
                $action_info['rel_id'] = $id;
                $action_info['rel_type'] = 'r.o';

                $replace_from = array("#change_reference", "#change_staff_full_name");
                $replace_to = array("#" . $id, get_staff_full_name());
                $action_info['link_sigplus'] = $action_info['link_sigplus'] . '/registro/' . base64_encode(openssl_encrypt($id, "aes-256-cbc", 'sigplus', 0, 'sigplus'));

                $action_info['conteudo_email'] = str_replace($replace_from, $replace_to, $action_info['conteudo_email']);

                $action_info['conteudo_sms'] = str_replace($replace_from, $replace_to, $action_info['conteudo_sms']);

                $action_info['assunto'] = str_replace($replace_from, $replace_to, $action_info['assunto']);

                $staff = $this->Staff_model->get($registro->atribuido_a);
                $action_info['email_destino'] = $staff->email;
                $action_info['staff_destino'] = $staff->staffid;
                $action_info['phone_destino'] = $staff->phonenumber;
                $this->Comunicacao_model->send_sigplus_email_sms($action, $action_info);
            } elseif ($type == 'desc') {
                echo $data['report'];
            }
        }
    }

    public function atualizar_etapa_2() {
        if (!get_staff_user_id()) {
            access_denied('R.O.');
            redirect($_SERVER['HTTP_REFERER']);
        }

        $data = $this->input->post();

        $this->Intranet_general_model->add_log(["rel_type" => "r.o", "controller" => "Registro_ocorrencia", "function" => "atualizar_etapa_2", "action" => "Atualizar atuantes de registro (" . $data['id'] . ")"]);

        $info = array(
            "registro_id" => $data['id'],
            "empresa_id" => $this->session->userdata('empresa_id')
        );
        $info['data_ultima_alteracao'] = date('Y-m-d');
        $info['user_ultima_alteracao'] = get_staff_user_id();
        unset($data['categoria_id']);
        //$categoria_atuantes = $this->Registro_ocorrencia_model->get_categoria_atuantes($categoria->atuantes);
        $atuantes_atuais = $this->Registro_ocorrencia_model->get_atuantes_preenchidos($data['id']);

        $info['atuante_id'] = $data['atuante_id'];
        $info['staff_id'] = $data['assigned'];
        $atuantes_changes[0] = $info['staff_id'];
        if ($info['staff_id'] != '') {



            if (in_array($data['atuante_id'], $atuantes_atuais)) {
                $anterior = $this->Registro_ocorrencia_model->get_atuantes_staff_id($data['id'], $data['atuante_id']);
                $atuantes_changes[1] = $anterior->staff_id;
                $this->db->where('registro_id', $data['id']);
                $this->db->where('atuante_id', $data['atuante_id']);
                $this->db->where('deleted', '0');
                $this->db->where('empresa_id', $this->session->userdata('empresa_id'));
                //print_r($info); //exit;
                $this->db->update('tbl_intranet_registro_ocorrencia_atuantes_por_registro', $info);
            } else {
                $info['data_cadastro'] = date('Y-m-d');
                $info['user_cadastro'] = get_staff_user_id();

                if ($this->db->insert('tbl_intranet_registro_ocorrencia_atuantes_por_registro', $info)) {
                    $view['atuante'] = $this->Registro_ocorrencia_model->get_atuantes_staff_id($data['id'], $data['atuante_id']);
                    $view['staff'] = $this->Staff_model->get();
                    $view['atuante_selecionado'] = $data['assigned'];
                    $this->load->view('gestao_corporativa/registro_ocorrencia/atuante_preenchido', $view);
                }
            }

            $this->load->model('Comunicacao_model');

            $action = 'ro_received_atuante';
            $action_info = get_actions($action);
            $id = $data['id'];
            $action_info['rel_id'] = $id;
            $action_info['rel_type'] = 'r.o';

            for ($i = 0; $i < count($atuantes_changes); $i++) {
                $staff = $this->Staff_model->get($atuantes_changes[$i]);
                $result = 'ADICIONADO';
                if ($i == 1) {
                    $result = 'REMOVIDO';
                }

                $replace_from = array("#change_reference", "#change_result");
                $replace_to = array("#" . $id, $result);
                $action_info['link_sigplus'] = $action_info['link_sigplus'] . '/registro/' . base64_encode(openssl_encrypt($id, "aes-256-cbc", 'sigplus', 0, 'sigplus'));

                $action_info['conteudo_email'] = str_replace($replace_from, $replace_to, $action_info['conteudo_email']);
                $action_info['conteudo_email'] = str_replace($replace_from, $replace_to, $action_info['conteudo_email']);

                $action_info['assunto'] = str_replace($replace_from, $replace_to, $action_info['assunto']);
                $action_info['conteudo_sms'] = str_replace($replace_from, $replace_to, $action_info['conteudo_sms']);
                $action_info['email_destino'] = $staff->email;
                $action_info['staff_destino'] = $staff->staffid;
                $action_info['phone_destino'] = $staff->phonenumber;

                $this->Comunicacao_model->send_sigplus_email_sms($action, $action_info);
            }
        }
    }

    public function add_note_ro() {
        if (!get_staff_user_id()) {
            access_denied('R.O.');
            redirect($_SERVER['HTTP_REFERER']);
        }

        $data = $this->input->post();
        $registro_id = $this->input->post('registro_id');
        $data['data_created'] = date('Y-m-d H:i:s');
        $data['user_created'] = get_staff_user_id();
        $data['empresa_id'] = $this->session->userdata('empresa_id');

        $view['ro'] = $this->Registro_ocorrencia_model->get_ro_by_id($registro_id);

        if ($this->db->insert('tbl_intranet_registro_ocorrencia_notes', $data)) {
            $this->Intranet_general_model->add_log(["rel_type" => "r.o", "controller" => "Registro_ocorrencia", "function" => "add_note_ro", "action" => "Nota Adicionada (Note-" . $this->db->insert_id() . ")", "rel_id" => $registro_id]);
            $this->load->model('Comunicacao_model');
            $this->load->model('Staff_model');
            if ($data['rel_type'] == 'note') {
                $action = 'ro_new_note';
            } else {
                $action = 'ro_new_answear';
            }
            $action_info = get_actions($action);
            $id = $registro_id;
            $registro = $this->Registro_ocorrencia_model->get_ro_by_id($id);
            $action_info['rel_id'] = $id;
            $action_info['rel_type'] = 'r.o';
            $action_info['link_sigplus'] = $action_info['link_sigplus'] . '/registro/' . base64_encode(openssl_encrypt($id, "aes-256-cbc", 'sigplus', 0, 'sigplus'));

            $replace_from = array("#change_reference", "#change_staff_full_name");
            $replace_to = array("#" . $id, get_staff_full_name());

            $action_info['conteudo_email'] = str_replace($replace_from, $replace_to, $action_info['conteudo_email']);

            $action_info['conteudo_sms'] = str_replace($replace_from, $replace_to, $action_info['conteudo_sms']);

            $action_info['assunto'] = str_replace($replace_from, $replace_to, $action_info['assunto']);

            $staff = $this->Staff_model->get($registro->atribuido_a);
            $action_info['email_destino'] = $staff->email;
            $action_info['staff_destino'] = $staff->staffid;
            $action_info['phone_destino'] = $staff->phonenumber;
            $this->Comunicacao_model->send_sigplus_email_sms($action, $action_info);
            if ($data['rel_type'] == 'answer') {

                if ($data['for_id'] != 'setor_responsavel' && $data['for_id'] != 'todos') {


                    if ($data['for_id'] == 'solicitante') {
                        if ($registro->user_created) {
                            $staff = $this->Staff_model->get($registro->user_created);
                        }
                    } else if ($data['for_id'] == 'todos') {
                        
                    } else {
                        $atuante_for_ro = $this->Registro_ocorrencia_model->get_atuante_for_ro($data['for_id']);
                        $staff = $this->Staff_model->get($atuante_for_ro->staff_id);
                    }

                    if ($staff) {
                        $action_info['email_destino'] = $staff->email;
                        $action_info['staff_destino'] = $staff->staffid;
                        $action_info['phone_destino'] = $staff->phonenumber;
                    }

                    $this->Comunicacao_model->send_sigplus_email_sms($action, $action_info);
                } elseif ($data['for_id'] != 'setor_responsavel' && $data['for_id'] == 'todos') {
                    $atuantes_vinculados = $this->Registro_ocorrencia_model->get_atuantes_preenchidos_all($id);
                    //print_r($atuantes_vinculados); exit;
                    foreach ($atuantes_vinculados as $atuante) {
                        $staff = $this->Staff_model->get($atuante['staff_id']);
                        $action_info['email_destino'] = $staff->email;
                        $action_info['staff_destino'] = $staff->staffid;
                        $action_info['phone_destino'] = $staff->phonenumber;
                        $this->Comunicacao_model->send_sigplus_email_sms($action, $action_info);
                    }
                }

                $view['notes'] = $this->Registro_ocorrencia_model->get_notes($registro_id, 'answer');

                $setor_responsavel = false;
                $this->load->model('Departments_model');
                $staff_department = $this->Departments_model->get_staff_departments(false, true);
                if (in_array($view['ro']->responsavel, $staff_department)) {
                    $setor_responsavel = true;
                }
                $view['setor_responsavel'] = $setor_responsavel;

                $this->load->view('gestao_corporativa/registro_ocorrencia/retorno_answers', $view);
            } elseif ($data['rel_type'] == 'note') {

                $view['notes'] = $this->Registro_ocorrencia_model->get_notes($registro_id, 'note');

                $this->load->view('gestao_corporativa/registro_ocorrencia/retorno_notes', $view);
            }
        } else {
            echo 'deu errado';
        }
    }

    public function edit_note_ro($id) {
        if (!get_staff_user_id()) {
            access_denied('R.O.');
            redirect($_SERVER['HTTP_REFERER']);
        }

        $data = $this->input->post();
        $data['date_last_change'] = date('Y-m-d H:i:s');
        $data['user_last_change'] = get_staff_user_id();
        $this->db->where('id', $data['note_id']);
        unset($data['note_id']);
        $this->db->update('tbl_intranet_registro_ocorrencia_notes', $data);
        redirect('gestao_corporativa/Registro_ocorrencia/registro/' . base64_encode(openssl_encrypt($id, "aes-256-cbc", 'sigplus', 0, 'sigplus')));
    }

    public function delete_note($id) {
        if (!get_staff_user_id()) {
            access_denied('R.O.');
            redirect($_SERVER['HTTP_REFERER']);
        }

        $id = explode('.', $id);
        $data['date_last_change'] = date('Y-m-d H:i:s');
        $data['user_last_change'] = get_staff_user_id();
        $data['deleted'] = '1';
        $this->db->where('id', $id[0]);
        $this->db->update('tbl_intranet_registro_ocorrencia_notes', $data);
        redirect('gestao_corporativa/Registro_ocorrencia/registro/' . base64_encode(openssl_encrypt($id[1], "aes-256-cbc", 'sigplus', 0, 'sigplus')));
    }

    public function editar_personalizados() {

        if (!get_staff_user_id()) {
            access_denied('R.O.');
            redirect($_SERVER['HTTP_REFERER']);
        }

        $this->Intranet_general_model->add_log(["rel_type" => "r.o", "controller" => "Registro_ocorrencia", "function" => "editar_personalizados", "action" => "Informações personalizadas do registro atualizadas", "rel_id" => $this->input->post('registro_id')]);
        $registro_id = $this->input->post('registro_id');
        $preenchido_por = $this->input->post('preenchido_por');
        //print_r($this->input->post()); exit;
        $campos = $this->Categorias_campos_model->get_values($registro_id, 'r.o', $preenchido_por);
        $data['date_created'] = date('Y-m-d');
        $data['registro_id'] = $registro_id;
        $data['user_created'] = get_staff_user_id();
        $data['empresa_id'] = $this->session->userdata('empresa_id');

        foreach ($campos as $campo) {
            if ($campo['tipo_campo'] != 'file') {
                $new = '';

                $new = $this->input->post('' . $campo['name_campo'] . '');

                if ($new) {
                    if (is_array($new)) {
                        $new = implode(',', $new);
                    }
                }


                if ($campo['value'] != $new) {
                    //echo strcmp($campo['value'], $new);
                    //echo $campo['value'] . ' - ' . $new . 'fim<br>';

                    $data['old'] = $campo['value'];
                    $data['new'] = $new;
                    $data['campo_id'] = $campo['id_campo'];                //print_r($data);

                    if ($this->db->insert('tbl_intranet_registro_ocorrencia_changes', $data)) {

                        $update['value'] = $new;
                        $this->db->where('id', $campo['value_id']);

                        $this->db->update('tbl_intranet_categorias_campo_values', $update);
                    }
                }
            }
        }
        $this->load->model('Registro_ocorrencia_model');
        $data['changes'] = $this->Registro_ocorrencia_model->get_changes($registro_id);
        $this->load->view('gestao_corporativa/registro_ocorrencia/retorno_changes', $data);
    }

    public function registro($id) {

        if (!get_staff_user_id()) {
            access_denied('R.O.');
            redirect($_SERVER['HTTP_REFERER']);
        }
        $this->Intranet_general_model->add_log(["rel_type" => "r.o", "controller" => "Registro_ocorrencia", "function" => "registro", "action" => "Viasualizar Registro de Ocorrência ($id)", "rel_id" => $id]);

        $id_old = $id;
        $id = openssl_decrypt(base64_decode($id), "aes-256-cbc", 'sigplus', 0, 'sigplus');
        if (!$id) {
            if (is_numeric($id_old)) {
                redirect(base_url('gestao_corporativa/Registro_ocorrencia/registro/' . base64_encode(openssl_encrypt($id_old, "aes-256-cbc", 'sigplus', 0, 'sigplus'))));
            }
            redirect(base_url('gestao_corporativa/Registro_ocorrencia/add'));
        }
        $ro = $this->Registro_ocorrencia_model->get_ro_by_id($id);

        if (($ro->status == 3 || $ro->status == 4) && !$_GET['view']) {
            redirect(base_url('gestao_corporativa/Registro_ocorrencia/pdf/' . base64_encode(openssl_encrypt($ro->id, "aes-256-cbc", 'sigplus', 0, 'sigplus'))));
        }
        $data['ro'] = $ro;
        $data['priorities'] = $this->tickets_model->get_priority();

        $data['bodyclass'] = 'top-tabs ticket single-ticket';
        $data['title'] = $ro->subject;

        //$this->load->model('knowledge_base_model');
        $this->load->model('departments_model');
        //$data['departments'] = $this->departments_model->get();
        $departments = $this->departments_model->get_staff_departments(false, true);
        // echo 'kndk'; exit;
        $data['staff_departments'] = $this->departments_model->get_department_staffs($departments);

        $data['colaboradores'] = $this->Staff_model->get();

        $whereStaff = [];
        if (get_option('access_tickets_to_none_staff_members') == 0) {
            $whereStaff['is_not_staff'] = 0;
        }
        $data['staff'] = $this->staff_model->get('', $whereStaff);
        add_admin_tickets_js_assets();
        if ($ro->registro_atendimento_id != '' && $ro->registro_atendimento_id != 0) {
            $this->load->model('Atendimentos_model');
            $data['atendimento'] = $this->Atendimentos_model->get_ra_by_id($ro->registro_atendimento_id);
            if ($data['atendimento']->client_id) {

                $this->load->model('Clients_model');
                $client = $this->Clients_model->get($data['atendimento']->client_id);
                //print_r($client); exit;


                $ch = curl_init();

                curl_setopt_array($ch, array(
                    CURLOPT_URL => 'http://189.2.65.2/sigplus/api/Informacoes_sistema_tasy',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => '{ "carteirinha": "' . $client->numero_carteirinha . '"
                                }',
                    CURLOPT_HTTPHEADER => array(
                        'Content-Type: application/json; charset=utf-8'
                    ),
                ));

                $response = curl_exec($ch);
                //echo $response; exit;
                $info = json_decode($response, true);

                $data['info_client'] = $info;
            }
        }
        $this->load->view('gestao_corporativa/registro_ocorrencia/single', $data);
    }

    /**
     * 07/11/2022
     * @WannaLuiza
     * Edita atuante isoladamente
     */
    public function edit_atuante() {
        if ($this->input->post()) {
            $dados['titulo'] = $this->input->post('titulo');
            $this->db->where('id', $this->input->post('id'));

            $this->db->update('tbl_intranet_registro_ocorrencia_atuantes', $dados);
            redirect('gestao_corporativa/Registro_ocorrencia');
        }
    }

    /**
     * 07/11/2022
     * @WannaLuiza
     * Registra atuante isoladamente
     */
    public function add_atuante() {

        if (!get_staff_user_id()) {
            access_denied('R.O.');
            redirect($_SERVER['HTTP_REFERER']);
        }

        $data = $this->input->post();
        $id = $this->input->post('id');

        $id = $this->Registro_ocorrencia_model->add_atuantes($data, $id);
    }

    public function edit_info_atuante() {
        $id = $this->input->post('id');
        $ro_id = $this->input->post('ro_id');

        $dados = $this->input->post();
        unset($dados['ro_id']);
        if ($dados['date_finalizado']) {
            $dados['date_finalizado'] = date('Y-m-d H:i:s');
        }

        $this->db->where('id', $id);

        $this->db->update('tbl_intranet_registro_ocorrencia_atuantes_por_registro', $dados);
        $atuante_for_ro = $this->Registro_ocorrencia_model->get_atuante_for_ro($id);
        if ($dados['date_finalizado']) {
            $this->load->model('Comunicacao_model');
            $this->load->model('Staff_model');

            $action = 'ro_finish_goal_atuante';
            $action_info = get_actions($action);
            $action_info['link_sigplus'] = $action_info['link_sigplus'] . '/registro/' . base64_encode(openssl_encrypt($id, "aes-256-cbc", 'sigplus', 0, 'sigplus'));
            $id = $ro_id;
            $registro = $this->Registro_ocorrencia_model->get_ro_by_id($id);
            $action_info['rel_id'] = $id;
            $action_info['rel_type'] = 'r.o';

            $replace_from = array("#change_reference", "#change_atuante");
            $replace_to = array("#" . $id, $atuante_for_ro->titulo);

            $action_info['conteudo_email'] = str_replace($replace_from, $replace_to, $action_info['conteudo_email']);

            $action_info['conteudo_sms'] = str_replace($replace_from, $replace_to, $action_info['conteudo_sms']);

            $action_info['assunto'] = str_replace($replace_from, $replace_to, $action_info['assunto']);

            $staff = $this->Staff_model->get($registro->atribuido_a);
            $action_info['email_destino'] = $staff->email;
            $action_info['staff_destino'] = $staff->staffid;
            $action_info['phone_destino'] = $staff->phonenumber;
            $this->Comunicacao_model->send_sigplus_email_sms($action, $action_info);
        } elseif ($dados['objetivo'] || $dados['prazo']) {

            $this->load->model('Comunicacao_model');
            $this->load->model('Staff_model');

            $action = 'ro_update_goal_atuante';
            $action_info = get_actions($action);

            $id = $ro_id;
            //echo $id; exit;
            $registro = $this->Registro_ocorrencia_model->get_ro_by_id($id);
            $action_info['rel_id'] = $id;
            $action_info['rel_type'] = 'r.o';

            $replace_from = array("#change_reference");
            $replace_to = array("#" . $id);

            $action_info['conteudo_email'] = str_replace($replace_from, $replace_to, $action_info['conteudo_email']);

            $action_info['conteudo_sms'] = str_replace($replace_from, $replace_to, $action_info['conteudo_sms']);

            $action_info['assunto'] = str_replace($replace_from, $replace_to, $action_info['assunto']);
            $action_info['link_sigplus'] = $action_info['link_sigplus'] . '/registro/' . base64_encode(openssl_encrypt($id, "aes-256-cbc", 'sigplus', 0, 'sigplus'));

            $staff = $this->Staff_model->get($atuante_for_ro->staff_id);
            $action_info['email_destino'] = $staff->email;
            $action_info['staff_destino'] = $staff->staffid;
            $action_info['phone_destino'] = $staff->phonenumber;
            $this->Comunicacao_model->send_sigplus_email_sms($action, $action_info);
        }
    }

    public function edit_atuante_form() {
        $id = $this->input->post('id');

        $data['atuante'] = $this->Registro_ocorrencia_model->get_atuante($id);

        $this->load->view('gestao_corporativa/registro_ocorrencia/retorno_atuante', $data);
    }

    public function assumir_registro($id) {
        if (!get_staff_user_id()) {
            access_denied('R.O.');
            redirect($_SERVER['HTTP_REFERER']);
        }

        $this->Intranet_general_model->add_log(["rel_type" => "r.o", "controller" => "Registro_ocorrencia", "function" => "assumir_registro", "action" => "Registro Assumido ($id)", "rel_id" => $id]);

        $data['atribuido_a'] = get_staff_user_id();
        $data['date_atribuido_a'] = date('Y-m-d H:i:s');
        $data['status'] = '2';

        $return = $this->Registro_ocorrencia_model->edit_ro($data, $id);
        if ($return == true) {
            redirect(base_url() . 'gestao_corporativa/registro_ocorrencia/registro/' . base64_encode(openssl_encrypt($id, "aes-256-cbc", 'sigplus', 0, 'sigplus')));
        } else {
            redirect(base_url() . 'gestao_corporativa/registro_ocorrencia/registro/' . base64_encode(openssl_encrypt($id, "aes-256-cbc", 'sigplus', 0, 'sigplus')) . '?ANS=ERROR');
        }
    }

    public function assumir_responsabilidade($id) {
        if (!get_staff_user_id()) {
            access_denied('R.O.');
            redirect($_SERVER['HTTP_REFERER']);
        }

        $data['staff_id'] = get_staff_user_id();
        $data['user_ultima_alteracao'] = get_staff_user_id();
        $data['data_ultima_alteracao'] = date('Y-m-d');

        $this->db->where('id', $id);
        $this->db->update('tbl_intranet_registro_ocorrencia_atuantes_por_registro', $data);

        $this->db->where('id', $id);
        $row = $this->db->get('tbl_intranet_registro_ocorrencia_atuantes_por_registro')->row();

        if ($row) {
            redirect(base_url() . 'gestao_corporativa/registro_ocorrencia/registro/' . base64_encode(openssl_encrypt($row->registro_id, "aes-256-cbc", 'sigplus', 0, 'sigplus')));
        } else {
            redirect(base_url() . 'gestao_corporativa/registro_ocorrencia/registro/' . base64_encode(openssl_encrypt($row->registro_id, "aes-256-cbc", 'sigplus', 0, 'sigplus')) . '?ANS=ERROR');
        }
    }

    public function cancelar_finalizar($id) {

        $data['date_end'] = date('Y-m-d H:i:s');
        $data['status'] = $_GET['end'];
        //print_r($data); exit;

        $return = $this->Registro_ocorrencia_model->edit_ro($data, $id);

        $this->Intranet_general_model->add_log(["rel_type" => "r.o", "controller" => "Registro_ocorrencia", "function" => "cancelar_finalizar", "action" => "Registro Cancelado/Finalizado ($id)", "rel_id" => $id]);
        if ($return == true) {

            $registro = $this->Registro_ocorrencia_model->get_ro_by_id($id);

            if ($registro->registro_atendimento_id) {
                $this->load->model('Atendimentos_model');
                $atendimento = $this->Atendimentos_model->get_ra_by_id($registro->registro_atendimento_id);
                //print_r($atendimento); exit;
                if ($data['status'] == 3) {
                    $title = 'Registro #' . $id . 'Finalizado. Protocolo: ' . $atendimento->protocolo;
                } else {
                    $title = 'Registro #' . $id . 'Cancelado. Protocolo: ' . $atendimento->protocolo;
                }
                $campos_sms = array(
                    "data_registro" => date('Y-m-d H:i:s'),
                    "usuario_registro" => get_staff_user_id(),
                    "phone_destino" => $atendimento->contato,
                    "assunto" => $title,
                    "mensagem" => $title,
                    "rel_type" => 'ro',
                    "rel_id" => $id,
                    "client_id" => $atendimento->client_id,
                    "empresa_id" => $this->session->userdata('empresa_id')
                );
                //echo 'jsj'; exit;
                $campos_email = array(
                    "data_registro" => date('Y-m-d H:i:s'),
                    "usuario_registro" => get_staff_user_id(),
                    "email_destino" => $atendimento->email,
                    "assunto" => $title,
                    "mensagem" => $title,
                    "rel_type" => 'ro',
                    "rel_id" => $id,
                    "client_id" => $atendimento->client_id,
                    "empresa_id " => $this->session->userdata('empresa_id')
                );

                $this->load->model('Comunicacao_model');
                $email = $this->Comunicacao_model->addEmail($campos_email);
                $sms = $this->Comunicacao_model->addSms($campos_sms);
            }

            //echo 'jsjjs'; exit;
            $staff = $this->Staff_model->get($registro->user_created);
            if (isset($staff)) {

                $this->load->model('Comunicacao_model');

                $action = 'ro_finish_cancel';
                $action_info = get_actions($action);
                $action_info['rel_id'] = $id;
                $action_info['rel_type'] = 'r.o';

                if ($data['status'] == 3) {
                    $status = 'FINALIZADO';
                } elseif ($data['status'] == 4) {
                    $status = 'CANCELADO';
                }

                $replace_from = array("#change_reference", "#change_status");
                $replace_to = array("#" . $id, $status);

                $action_info['conteudo_email'] = str_replace($replace_from, $replace_to, $action_info['conteudo_email']);

                $action_info['conteudo_sms'] = str_replace($replace_from, $replace_to, $action_info['conteudo_sms']);
                $action_info['link_sigplus'] = $action_info['link_sigplus'] . '/registro/' . base64_encode(openssl_encrypt($id, "aes-256-cbc", 'sigplus', 0, 'sigplus'));
                $action_info['email_destino'] = $staff->email;
                $action_info['staff_destino'] = $staff->staffid;
                $action_info['phone_destino'] = $staff->phonenumber;
                $action_info['assunto'] = str_replace($replace_from, $replace_to, $action_info['assunto']);

                $this->Comunicacao_model->send_sigplus_email_sms($action, $action_info);
            }
            redirect(base_url() . 'gestao_corporativa/registro_ocorrencia/registro/' . base64_encode(openssl_encrypt($id, "aes-256-cbc", 'sigplus', 0, 'sigplus')));
        } else {
            redirect(base_url() . 'gestao_corporativa/registro_ocorrencia/registro/' . base64_encode(openssl_encrypt($id, "aes-256-cbc", 'sigplus', 0, 'sigplus')) . '?ANS=ERROR');
        }
    }

    public function pdf($id) {

        $this->Intranet_general_model->add_log(["rel_type" => "r.o", "controller" => "Registro_ocorrencia", "function" => "pdf", "action" => "Visualizar PDF ($id)", "rel_id" => $id]);

        $id = openssl_decrypt(base64_decode($id), "aes-256-cbc", 'sigplus', 0, 'sigplus');
        $data['ro'] = $this->Registro_ocorrencia_model->get_ro_by_id($id);
        $data['ro_values_notificante'] = $this->Categorias_campos_model->get_values($id, 'r.o', '0');
        //$campos = $this->Categorias_campos_model->get_values($id, 'r.o', '0');
        if ($data['ro']->registro_atendimento_id != 0 && $data['ro']->registro_atendimento_id != '') {
            $this->load->model('Atendimentos_model');
            $data['atendimento'] = true;
            $data['ra'] = $this->Atendimentos_model->get_ra_by_id($data['ro']->registro_atendimento_id);
        }
        $atuantes = $this->Registro_ocorrencia_model->get_atuantes_preenchidos_all($id);
        for ($i = 0; $i < count($atuantes); $i++) {
            $atuantes[$i]['campos'] = $this->Categorias_campos_model->get_values($id, 'r.o', $atuantes[$i]['id']);
        }
        $data['atuantes'] = $atuantes;
        $data['answers'] = $this->Registro_ocorrencia_model->get_notes($id, 'answer');

        $this->load->library('Pdf');
        $company_logo = get_option('company_logo');
        $data['company_name'] = get_option('companyname');

        $path = base_url() . 'uploads/company/' . $company_logo;
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data_ = file_get_contents($path);

        $data['base64'] = 'data:image/' . $type . ';base64,' . base64_encode($data_);

        if ($data['ro']->registro_atendimento_id != '' && $data['ro']->registro_atendimento_id != 0) {
            $this->load->model('Atendimentos_model');
            $data['atendimento_info'] = $this->Atendimentos_model->get_ra_by_id($data['ro']->registro_atendimento_id);
            if ($data['atendimento_info']->client_id) {

                $this->load->model('Clients_model');
                $client = $this->Clients_model->get($data['atendimento_info']->client_id);
                //print_r($client); exit;


                $ch = curl_init();

                curl_setopt_array($ch, array(
                    CURLOPT_URL => 'http://189.2.65.2/sigplus/api/Informacoes_sistema_tasy',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => '{ "carteirinha": "' . $client->numero_carteirinha . '"
                                }',
                    CURLOPT_HTTPHEADER => array(
                        'Content-Type: application/json; charset=utf-8'
                    ),
                ));

                $response = curl_exec($ch);
                //echo $response; exit;
                $info = json_decode($response, true);

                $data['info_client'] = $info;
            }
        }

        $html = $this->load->view('gestao_corporativa/registro_ocorrencia/pdf.php', $data, true);

        $this->dompdf->loadHtml($html);
        $this->dompdf->render();

        $this->dompdf->stream("RO#$id.pdf", array("Attachment" => 0));
    }

    public function return_objetivo() {
        $roid = $this->input->post('ro_id');
        $atuanteid = $this->input->post('atuante_id');
        $data['atuante'] = $this->Registro_ocorrencia_model->get_atuantes_staff_id($roid, $atuanteid);
        $this->load->view('gestao_corporativa/registro_ocorrencia/modal_objetivo', $data);
    }

    public function modal() {
        if ($this->input->post('slug') == 'log_analise') {
            $data['id'] = $this->input->post('id');
            $this->load->view('gestao_corporativa/registro_ocorrencia/modal_log', $data);
        }
    }

    public function send_notifications_registros_atrasados() {
        $this->load->model('Staff_model');
        $this->load->model('Comunicacao_model');
        $tbl_ro = 'tbl_intranet_registro_ocorrencia';

        $DataAtual = date('Y-m-d');
        $sql = "SELECT * from $tbl_ro "
                . "WHERE validade > {$DataAtual} and atribuido_a is not null";
        //echo $sql; exit;
        $result = $this->db->query($sql)->result_array();
        //print_r($result); exit;

        $action = 'ro_late';
        $action_info = get_actions($action);
        $action_info['rel_type'] = 'r.o';

        foreach ($result as $registro) {
            $action_info['rel_id'] = $registro['id'];
            $action_info['link_sigplus'] = 'gestao_corporativa/Registro_ocorrencia/registro/' . base64_encode(openssl_encrypt($registro['id'], "aes-256-cbc", 'sigplus', 0, 'sigplus'));
            $action_info['conteudo_email'] = str_replace("#change_reference", "#" . $registro['id'], $action_info['conteudo_email']);
            $action_info['assunto'] = str_replace("#change_reference", "#" . $registro['id'], $action_info['assunto']);
            $action_info['conteudo_sms'] = str_replace("#change_reference", "#" . $registro['id'], $action_info['conteudo_sms']);
            $staff = $this->Staff_model->get($registro['atribuido_a']);
            $action_info['email_destino'] = $staff->email;
            $action_info['staff_destino'] = $staff->staffid;
            $action_info['phone_destino'] = $staff->phonenumber;
            $this->Comunicacao_model->send_sigplus_email_sms($action, $action_info);
        }
    }

    public function cria_campos($id) {

        $campos = $this->Categorias_campos_model->get_categoria_campos_all('16', 'r.o');
        //print_r($campos); exit;

        foreach ($campos as $campo) {

            $campos_value = array(
                "categoria_id" => 16,
                "registro_id" => $id,
                "campo_id" => $campo['id'],
                "data_cadastro" => date('Y-m-d H:i:s'),
                "user_cadastro" => get_staff_user_id(),
                "empresa_id" => $this->session->userdata('empresa_id')
            );

            $campos_value['value'] = '';

            //print_r($campos_value); exit;

            $this->Registro_ocorrencia_model->add_campo_value($campos_value);
        }
    }

    public function delete_atuante_por_ro($id) {

        if ($id) {

            $dados['deleted'] = 1;

            $this->db->where('id', $id);

            $this->db->update('tbl_intranet_registro_ocorrencia_atuantes_por_registro', $dados);
        }
        $ro_id = $_GET['id'];

        redirect(base_url() . 'gestao_corporativa/registro_ocorrencia/registro/' . base64_encode(openssl_encrypt($ro_id, "aes-256-cbc", 'sigplus', 0, 'sigplus')));
    }

    public function reports() {
        $data['title'] = 'Registro de Ocorrência - Relatórios';

        $this->Intranet_general_model->add_log(["rel_type" => "r.o", "controller" => "Registro_ocorrencia", "function" => "reports", "action" => "Relatórios Registro de Ocorrências (Visão Geral)"]);

        //$data['tipos'] = $this->Registro_ocorrencia_model->get_categorias();
        //$data['bodyclass'] = 'tickets-page';
        //add_admin_tickets_js_assets();
        //$this->load->model('Staff_model');
        $categorias = $this->Categorias_campos_model->get_categorias('r.o', true);
        //print_r($categorias); EXIT;
        //echo 'aqui'; exit;
        //$data['staffs'] = $this->Staff_model->get();
        //$this->load->model('departments_model');

        $this->load->model('Workflow_model');
        $data['statuses'] = $this->Workflow_model->get_status();
        //print_r($data['statuses']); exit;
        //$data['default_tickets_list_statuses'] = hooks()->apply_filters('default_tickets_list_statuses', [1, 2]);
        //$deps = $this->departments_model->get_staff_departments(false);
        $departments_workflow = $this->Registro_ocorrencia_model->get_departments_ros();

        $cores = get_company_colors();

        $cores_i = 0;
        //print_r($cores); exit;
        for ($i = 0; $i < count($departments_workflow); $i++) {
            $departments_workflow[$i]['color'] = $cores[$cores_i];
            $cores_i++;
            if ($cores_i > 22) {
                $cores_i = 0;
            }
        }
        //echo 'aa'; exit;

        $data['departments_workflow'] = $departments_workflow;

        $cores_i = 0;
        //print_r($cores); exit;
        for ($i = 0; $i < count($categorias); $i++) {
            $categorias[$i]['color'] = $cores[$cores_i];
            //echo $data['categorias'][$i]['id'];
            $categorias[$i]['total'] = $this->Registro_ocorrencia_model->get_ro_by_category($categorias[$i]['id']);
            //echo 'depois'; exit;

            $cores_i++;
            if ($cores_i > 22) {
                $cores_i = 0;
            }
        }

        $data['categorias_workflow'] = $categorias;

        $data['categorias'] = $categorias;

        //echo 'aqui'; exit;
        $semana = [];
        $hoje = new DateTime();  // Obtém a data atual
// Define o dia da semana como segunda-feira (1)
        $hoje->modify('monday this week');

// Loop para obter as datas da semana
        for ($i = 0; $i < 7; $i++) {
            //$semana[] = $this->Registro_ocorrencia_model->get_data_fluxo_andamento($hoje->format('Y-m-d'));  // Formato: Ano-Mês-Dia
            $hoje->modify('+1 day');  // Avança para o próximo dia
        }
        //print_r($semana); exit;
        // $data['semana'] = $semana;

        $ano_atual = date('Y');
        $mes_atual = date('m');

        $ano_atual = date('Y');
        $mes_atual = date('m');

// Obter a quantidade de dias no mês atual
        $dias_no_mes = cal_days_in_month(CAL_GREGORIAN, $mes_atual, $ano_atual);

// Imprimir os dias do mês atual no formato YYYY-mm-dd
        for ($dia = 1; $dia <= $dias_no_mes; $dia++) {
            $mes['dias'][] = sprintf('%04d-%02d-%02d', $ano_atual, $mes_atual, $dia);
            //  $mes['totals'][] = $this->Registro_ocorrencia_model->get_data_fluxo_andamento(sprintf('%04d-%02d-%02d', $ano_atual, $mes_atual, $dia));
        }

        // $data['mes'] = $mes;
        //print_r($mes); exit; 

        $ano_atual = date('Y');

// Loop pelos meses do ano
        for ($mes = 1; $mes <= 12; $mes++) {
            // Formatar o mês com dois dígitos (01, 02, ..., 12)
            $mes_formatado = str_pad($mes, 2, '0', STR_PAD_LEFT);

            $meses['interno'][] = $this->Registro_ocorrencia_model->get_mes_fluxo_andamento($mes_formatado, $ano_atual);
            $meses['portal'][] = $this->Registro_ocorrencia_model->get_mes_fluxo_andamento($mes_formatado, $ano_atual, true);
        }
        $data['meses'] = $meses;
        //echo 's'; exit;

        $this->load->view('gestao_corporativa/registro_ocorrencia/reports', $data);
    }

    public function report() {
        $this->Intranet_general_model->add_log(["rel_type" => "r.o", "controller" => "Registro_ocorrencia", "function" => "report", "action" => "Relatórios Registro de Ocorrências (resultado e busca)"]);
        $categoria_id = $this->input->post('categoria_id');

        $start = $this->input->post('start');
        $end = $this->input->post('end');
        $current = $this->input->post('current');

        $status = $this->input->post('status');
        $campo_wf = [];
        $campo_cat = [];
        //echo 'aqui'; exit;

        $campos_system = $this->input->post('campos_system');
        foreach ($campos_system as $campo) {

            $_campo = explode(';', $campo);

            $__campo['column'] = $_campo[0];
            $__campo['label'] = $_campo[1];
            $campo_wf[] = $__campo;
            $campos[] = $__campo['column'];
        }

        $campos_cat = $this->input->post('campos_cat');

        foreach ($campos_cat as $campo) {

            $_campo = explode(';', $campo);

            $__campo['column'] = $_campo[0];
            $__campo['label'] = $_campo[1];
            $__campo['type'] = $_campo[2];
            $campo_cat[] = $__campo;
        }

        $campos_ = $this->input->post('campos');
        $campos_new = [];

        $deps = [];
        $departamentos = [];

        foreach ($campos_ as $campo) {

            $_campo = explode(';', $campo);

            $__campo['column'] = $_campo[0];
            $__campo['label'] = $_campo[1];
            $__campo['dep'] = $_campo[2];
            $__campo['type'] = $_campo[3];

            //$campos_new[] = $__campo;
            if ($__campo['dep']) {
                $campos_dep[] = $__campo;
                $campos_new[] = $__campo;
            }

            if (isset($departamentos[$__campo['dep']])) {
                // Se o departamento já existe, incrementa o contador de campos
                $departamentos[$__campo['dep']]['campos']++;
            } elseif ($__campo['dep']) {
                // Se o departamento não existe, adiciona-o ao array de departamentos e inicializa o contador de campos
                $departamentos[$__campo['dep']] = [
                    'campos' => 1,
                ];

                // Adiciona o departamento ao array $deps
                $deps[] = $__campo['dep'];
            }
        }
        //print_r($campos_new); exit;

        $deps_qtd = [];
        foreach ($deps as $dep) {
            $deps_qtd[] = array("dep" => $dep, "qtd" => $departamentos[$dep]['campos']);
        }

        $data['deps_qtd'] = $deps_qtd;
        $data['campo_wf'] = $campo_wf;
        $data['campos_'] = $campos_new;
        $data['campo_cat'] = $campo_cat;
        $data['categoria'] = $this->Categorias_campos_model->get_categoria($categoria_id);

        //echo 'jj'; exit;

        $data['workflows'] = $this->Registro_ocorrencia_model->get_report($categoria_id, $start, $end, $campos, $status, $campos_dep, $campo_cat, $current);

        $this->load->view('gestao_corporativa/registro_ocorrencia/reports_det', $data);
    }
}
