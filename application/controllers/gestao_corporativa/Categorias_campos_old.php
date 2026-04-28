<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Categorias_campos extends AdminController {

    public function __construct() {

        parent::__construct();
        $this->load->model('Categorias_campos_model');
        $this->load->model('Registro_ocorrencia_model');
        $this->load->model('Intranet_general_model');
    }

    /* List all staff roles */

    public function index() {
        
    }

    public function modal() {


        $data['rel_type'] = $this->input->post('type');
        $data['modal'] = true;
        if ($this->input->post('slug') === 'update_categoria') {

            if ($this->input->post('id')) {
                $data['categoria'] = $this->Categorias_campos_model->get_categoria($this->input->post('id'), $this->input->post('type'));
            }
            if ($this->input->post('type') === 'r.o' || $this->input->post('type') === 'workflow' || $this->input->post('type') === 'cdc') {
                $this->load->model('departments_model');

                $data['departments'] = $this->departments_model->get();
            }
            if ($this->input->post('type') === 'r.o') {
                $data['atuantes'] = $this->Registro_ocorrencia_model->get_atuantes();
            }

            if ($this->input->post('type') === 'workflow') {
                $this->load->model('Staff_model');
                $data['staffs'] = $this->Staff_model->get();
            }


            $this->load->view('gestao_corporativa/categorias_campos/edit_categoria', $data);
        } elseif ($this->input->post('slug') === 'campos_categoria') {

            $data['tipo'] = $this->Categorias_campos_model->get_categoria($this->input->post('id'), $this->input->post('type'));
            $data['in_out'] = $this->input->post('in_out');

            $data['campos'] = $this->Categorias_campos_model->get_categoria_campos_all($data['tipo']->id, $this->input->post('type'));

            if ($this->input->post('type') == 'cdc') {
                $data['categoria_atuantes'] = $this->Categorias_campos_model->get_categoria_fluxos($data['tipo']->id);
            } elseif ($this->input->post('type') == 'r.o') {
                $data['categoria_atuantes'] = $this->Registro_ocorrencia_model->get_categoria_atuantes($data['tipo']->atuantes);
            }
            $this->load->view('gestao_corporativa/categorias_campos/Campos', $data);
        } elseif ($this->input->post('slug') === 'doctos') {

            $data['tipo'] = $this->Categorias_campos_model->get_categoria($this->input->post('id'), $this->input->post('type'));

            $this->load->view('gestao_corporativa/categorias_campos/Doctos', $data);
        } elseif ($this->input->post('slug') === 'portal') {

            $data['tipo'] = $this->Categorias_campos_model->get_categoria($this->input->post('id'), $this->input->post('type'));
      

            $data['apis'] = $this->Categorias_campos_model->get_categorias('api');

            $this->load->view('gestao_corporativa/categorias_campos/Portal', $data);
        }
    }

    public function table_categorias() {

        $this->app->get_table_data_intranet('categorias');
    }
    
    
    public function edit() {

        $POST = $_POST;
        
        if(!$POST['p_master']){
            $POST['p_master'] = 0;
        }
        
        $this->db->where('id', $POST['id']);
        if($this->db->update(db_prefix().'_intranet_categorias', $POST)){
            echo  json_encode(array("alert" => "success", "message" => "Alterado com sucesso!"));
        } else {
            echo  json_encode(array("alert" => "danger", "message" => "Erro ao alterar!"));
        }
    }

    public function table_doctos() {

        $this->app->get_table_data_intranet('doctos');
    }

    public function delete_campo() {
        $id = $this->input->post('id');
        $dados['deleted'] = 1;

        $this->db->where('id', $id);
        if ($this->db->update('tbl_intranet_categorias_campo', $dados)) {
            echo json_encode(array("alert" => "success", "message" => 'Campo deletado com sucesso!'), true);
        } else {
            echo json_encode(array("alert" => "danger", "message" => 'Erro ao deletar campo'), true);
        }

        $this->db->where('id', $id);
        $campo = $this->db->get('tbl_intranet_categorias_campo')->row();
        $this->Intranet_general_model->add_log(["rel_type" => "category", "controller" => "Categorias_campos", "function" => "delete_campo", "action" => "Campo deletado ($id)", "rel_id" => $campo->categoria_id]);

        $categoria_id = $this->input->post('categoria_id');
        $preenchido_por = $this->input->post('preenchido_por');
        if ($campo->preenchido_por) {
            $preenchido_por = $campo->preenchido_por;
        }
        $rel_type = $this->input->post('rel_type');
        if ($preenchido_por != '') {
            $campos_atuais = $this->Categorias_campos_model->get_categoria_campos($categoria_id, $preenchido_por);
        } else {
            $campos_atuais = $this->Categorias_campos_model->get_categoria_campos_all($categoria_id, $rel_type);
        }


        $contador = 1;
        for ($i = 0; $i < count($campos_atuais); $i++) {


            $new_ordem['ordem'] = $contador;

            $this->db->where('id', $campos_atuais[$i]['id']);

            $this->db->update('tbl_intranet_categorias_campo', $new_ordem);
            $contador++;
        }
    }

    public function table_campos() {

        $this->app->get_table_data_intranet('campos');
    }

    public function table_values() {

        $this->app->get_table_data_intranet('values');
    }

    // JÁ COM ALERTS
    function add_tipo() {

        //print_r($post); exit;
        $info = array(
            "titulo" => $_POST['titulo'],
            "rel_type" => $_POST['rel_type'],
            "empresa_id" => $this->session->userdata('empresa_id'),
        );

        $FORM = $_POST;

        $_info = [];
        $rel_type = $this->input->post('rel_type');

        if ($rel_type == 'r.o' || $rel_type == 'workflow' || $rel_type == 'cdc' || $rel_type == 'atendimento' || $rel_type == 'api' || $rel_type == 'cdc') {

            if ($rel_type == 'api' || $rel_type == 'cdc') {
                $_info = array_merge($_info, ['description']);
            }

            if ($rel_type == 'r.o' || $rel_type == 'workflow' || $rel_type == 'autosservico') {
                $_info = array_merge($_info, ['orientacoes']);

                if ($rel_type == 'autosservico') {
                    $_info = array_merge($_info, ['linked_to']);
                }
            }

            if ($rel_type == 'r.o' || $rel_type == 'workflow' || $rel_type == 'atendimento') {
                $_info = array_merge($_info, ['portal']);
            }

            if ($rel_type == 'r.o' || $rel_type == 'workflow' || $rel_type == 'cdc') {
                $_info = array_merge($_info, ['responsavel']);

                if ($rel_type == 'r.o' || $rel_type == 'workflow') {
                    $_info = array_merge($_info, ['orientacoes_client', 'titulo_abreviado', 'ra']);
                }
            }
        }

        if ($rel_type == 'workflow') {
            $_info = array_merge($_info, ['staff_responsavel', 'prazo', 'prazo_cliente', 'aprovacao', 'confidential']);
        }

        if ($rel_type == 'api') {
            $_info = array_merge($_info, ['caminho']);
        }


        if ($rel_type == 'r.o') {
            $info['hash'] = md5($POST['responsavel'] . $info['titulo'] . date('Y-m-d H:i:s'));
            $_info = array_merge($_info, ['atuantes', 'anonimo', 'head_1', 'head_2', 'head_3']);
        }

        if ($rel_type == 'cdc') {
            $flows = $FORM['flows'];
            $_info = array_merge($_info, ['codigo']);

            $string = $FORM['codigo'];

// Substituir várias palavras ao mesmo tempo
            $palavras_para_substituir = array('[DIA]', '[MÊS]', '[ANO]', '[SEQUÊNCIAL]', '[TÍTULO CDC]', '[TÍTULO CATEGORIA]', '[DEPARTAMENTO(SÍGLA)]', '[VERSÃO]');
            $substituicoes = array('<<<d>>>', '<<<m>>>', '<<<Y>>>', '<<<sequencial>>>', '<<<titulo>>>', '<<<titulo_c>>>', '<<<name>>>', '<<<numero_versao>>>');
            $info['codigoKey'] = str_replace($palavras_para_substituir, $substituicoes, $string);
        }

        foreach ($_info as $__info) {
            $info[$__info] = $FORM[$__info];
        }




        //print_r($info); exit;

        if ($this->input->post('id')) {

            $info['data_ultima_alteracao'] = date('Y-m-d');
            $info['user_ultima_alteracao'] = get_staff_user_id();
            $this->db->where('id', $this->input->post('id'));
            if ($this->db->update("tbl_intranet_categorias", $info)) {
                if ($info['rel_type'] == 'cdc') {
                    $this->db->where('categoria_id', $this->input->post('id'));
                    $this->db->update("tbl_intranet_categorias_fluxo", array('deleted' => 1));
                    $count = 1;
                    $info_2['categoria_id'] = $this->input->post('id');
                    $info_2['empresa_id'] = $this->session->userdata('empresa_id');
                    $info_2['data_cadastro'] = date('Y-m-d');
                    $info_2['user_cadastro'] = get_staff_user_id();
                    foreach ($flows as $flow) {
                        $info_2['codigo_sequencial'] = $count;
                        $info_2['titulo'] = $flow;
                        $this->db->insert("tbl_intranet_categorias_fluxo", $info_2);
                        $count++;
                    }
                }
                $id = $this->input->post('id');
                echo json_encode(array("alert" => "success", "message" => 'Editado com sucesso!'), true);
                $this->Intranet_general_model->add_log(["rel_type" => "category", "controller" => "Categorias_campos", "function" => "add_tipo", "action" => "Categoria editada ($id)", "rel_id" => $id]);
            } else {
                echo json_encode(array("alert" => "danger", "message" => 'Erro ao Editar'), true);
            }
        } else {
            $info['data_cadastro'] = date('Y-m-d');
            $info['user_cadastro'] = get_staff_user_id();
            $info['data_ultima_alteracao'] = date('Y-m-d');
            $info['user_ultima_alteracao'] = get_staff_user_id();
            if ($this->db->insert("tbl_intranet_categorias", $info)) {
                $id = $this->db->insert_id();
                if ($info['rel_type'] == 'workflow') {

                    $info_2['codigo_sequencial'] = '1';
                    $info_2['categoria_id'] = $id;
                    $info_2['setor'] = $info['responsavel'];
                    $info_2['prazo'] = $info['prazo'];
                    $info_2['empresa_id'] = $this->session->userdata('empresa_id');
                    $info_2['data_cadastro'] = date('Y-m-d');
                    $info_2['user_cadastro'] = get_staff_user_id();

                    $this->db->insert("tbl_intranet_categorias_fluxo", $info_2);
                } elseif ($info['rel_type'] == 'cdc') {
                    $count = 1;
                    $info_2['categoria_id'] = $id;
                    $info_2['empresa_id'] = $this->session->userdata('empresa_id');
                    $info_2['data_cadastro'] = date('Y-m-d');
                    $info_2['user_cadastro'] = get_staff_user_id();
                    foreach ($flows as $flow) {
                        $info_2['codigo_sequencial'] = $count;
                        $info_2['titulo'] = $flow;
                        $this->db->insert("tbl_intranet_categorias_fluxo", $info_2);
                        $count++;
                    }
                }
                $this->Intranet_general_model->add_log(["rel_type" => "category", "controller" => "Categorias_campos", "function" => "add_tipo", "action" => "Categoria registrada ($id)", "rel_id" => $id]);
                echo json_encode(array("alert" => "success", "message" => 'Salvo com sucesso!'), true);
            } else {
                echo json_encode(array("alert" => "danger", "message" => 'Erro ao Salvar'), true);
            }
        }
    }

    public function delete_tipo() {
        $id = $this->input->post('id');
        $dados['deleted'] = 1;

        $this->db->where('id', $id);

        if ($this->db->update('tbl_intranet_categorias', $dados)) {
            $this->Intranet_general_model->add_log(["rel_type" => "category", "controller" => "Categorias_campos", "function" => "delete_tipo", "action" => "Categoria deletada ($id)", "rel_id" => $id]);
            echo json_encode(array("alert" => "success", "message" => 'Deletado com sucesso!'), true);
        } else {
            echo json_encode(array("alert" => "danger", "message" => 'Erro ao deletar'), true);
        }
    }

    public function duplicate_tipo() {
        $id = $this->input->post('id');
        $this->Intranet_general_model->add_log(["rel_type" => "category", "controller" => "Categorias_campos", "function" => "duplicate_tipo", "action" => "Categoria duplicada ($id)", "rel_id" => $id]);
        //$dados['deleted'] = 1;

        $this->db->where('id', $id);
        $categoria = $this->db->get('tbl_intranet_categorias')->result_array();
        $categoria = $categoria[0];

        if (isset($categoria)) {

            $id_old = $categoria['id'];
            unset($categoria['id']);
            $categoria['titulo'] = $categoria['titulo'] . ' - Copy';
            $categoria['user_cadastro'] = get_staff_user_id();
            $categoria['data_cadastro'] = date('Y-m-d H:i:s');
            $categoria['user_ultima_alteracao'] = get_staff_user_id();
            $categoria['data_ultima_alteracao'] = date('Y-m-d H:i:s');
            $categoria['is_a_copy'] = '1';
            //print_r($categoria); exit;
            if ($this->db->insert('tbl_intranet_categorias', $categoria)) {
                $id = $this->db->insert_id();
                $this->db->where('categoria_id', $id_old);
                $this->db->where('deleted', 0);
                $this->db->where('preenchido_por', 0);
                $campos = $this->db->get('tbl_intranet_categorias_campo')->result_array();
                foreach ($campos as $campo) {
                    $campo_id_old = $campo['id'];
                    unset($campo['id']);
                    $campo['user_cadastro'] = get_staff_user_id();
                    $campo['data_cadastro'] = date('Y-m-d H:i:s');
                    $campo['user_ultima_alteracao'] = get_staff_user_id();
                    $campo['data_ultima_alteracao'] = date('Y-m-d H:i:s');
                    $campo['categoria_id'] = $id;
                    if ($this->db->insert('tbl_intranet_categorias_campo', $campo)) {
                        $campo_id_novo = $this->db->insert_id();
                        $this->db->where('campo_id', $campo_id_old);
                        $this->db->where('deleted', 0);
                        $options = $this->db->get('tbl_intranet_categorias_campo_options')->result_array();
                        foreach ($options as $option) {
                            $option_id_old = $option['id'];
                            unset($option['id']);
                            $option['campo_id'] = $campo_id_novo;
                            $this->db->insert('tbl_intranet_categorias_campo_options', $option);
                        }
                    }
                }
                $this->db->where('categoria_id', $id_old);
                $this->db->where('deleted', 0);
                $this->db->order_by("codigo_sequencial", "asc");
                $fluxos = $this->db->get('tbl_intranet_categorias_fluxo')->result_array();

                if (isset($fluxos)) {
                    foreach ($fluxos as $fluxo) {
                        $fluxo['categoria_id'] = $id;
                        $fluxo_id_old = $fluxo['id'];
                        unset($fluxo['id']);

                        $id_fluxo_pai = $fluxo['vindo_de'];
                        $this->db->where('id', $id_fluxo_pai);
                        $this->db->where('deleted', 0);
                        $fluxo_pai = $this->db->get('tbl_intranet_categorias_fluxo')->row();
                        if (isset($fluxo_pai)) {
                            $this->db->where('codigo_sequencial', $fluxo_pai->codigo_sequencial);
                            $this->db->where('categoria_id', $id);
                            $this->db->where('deleted', 0);
                            $novo_fluxo_pai = $this->db->get('tbl_intranet_categorias_fluxo')->row();

                            $fluxo['vindo_de'] = $novo_fluxo_pai->id;
                        }

                        if ($this->db->insert('tbl_intranet_categorias_fluxo', $fluxo)) {
                            $id_fluxo_novo = $this->db->insert_id();
                            $this->db->where('categoria_id', $id_old);
                            $this->db->where('deleted', 0);
                            $this->db->where('preenchido_por', $fluxo_id_old);
                            $campos = $this->db->get('tbl_intranet_categorias_campo')->result_array();
                            foreach ($campos as $campo) {
                                $campo_id_old = $campo['id'];
                                unset($campo['id']);
                                $campo['user_cadastro'] = get_staff_user_id();
                                $campo['data_cadastro'] = date('Y-m-d H:i:s');
                                $campo['user_ultima_alteracao'] = get_staff_user_id();
                                $campo['data_ultima_alteracao'] = date('Y-m-d H:i:s');
                                $campo['categoria_id'] = $id;
                                $campo['preenchido_por'] = $id_fluxo_novo;
                                if ($this->db->insert('tbl_intranet_categorias_campo', $campo)) {
                                    $campo_id_novo = $this->db->insert_id();
                                    $this->db->where('campo_id', $campo_id_old);
                                    $this->db->where('deleted', 0);
                                    $options = $this->db->get('tbl_intranet_categorias_campo_options')->result_array();
                                    foreach ($options as $option) {
                                        $option_id_old = $option['id'];
                                        unset($option['id']);
                                        $option['campo_id'] = $campo_id_novo;
                                        $this->db->insert('tbl_intranet_categorias_campo_options', $option);
                                    }
                                }
                            }
                        }
                    }
                }
            } else {
                echo json_encode(array("alert" => "danger", "message" => 'Erro ao Duplicar'), true);
            }


            $this->db->where('id', $id);
            $categoria = $this->db->get('tbl_intranet_categorias')->result_array();
            $categoria = $categoria[0];
        }




        echo json_encode(array("alert" => "success", "message" => 'Duplicado com Sucesso'), true);
    }

    function trocar_form() {

        $categoria = $this->input->post('categoria_id');
        $rel_type = $this->input->post('rel_type');
        if ($categoria) {
            $data['tipo'] = $this->Categorias_campos_model->get_categoria($categoria, $rel_type);
            if ($data['tipo']->atuantes != '') {
                $data['categoria_atuantes'] = $this->Registro_ocorrencia_model->get_categoria_atuantes($data['tipo']->atuantes);
            }
        }
        if ($rel_type == 'cdc') {
            $data['categoria_atuantes'] = $this->Categorias_campos_model->get_categoria_fluxos($data['tipo']->id);
        }

        $this->load->model('Company_model');
        $data['lists'] = $this->Company_model->get_list();

        $data['rel_type'] = $rel_type;
        $data['rel_id'] = $this->input->post('rel_id');
        $data['retorno'] = $this->input->post('retorno');
        $this->load->view('gestao_corporativa/categorias_campos/add_campo_form', $data);
    }

    function edit_campo() {

        $id = $this->input->post('id');
        $categoria = $this->input->post('categoria_id');
        $data['tipo'] = $this->Categorias_campos_model->get_categoria($categoria, $this->input->post('rel_type'));
        if ($data['tipo']->atuantes != '') {
            $data['categoria_atuantes'] = $this->Registro_ocorrencia_model->get_categoria_atuantes($data['tipo']->atuantes);
        }
        if ($this->input->post('rel_type') == 'cdc') {
            $data['categoria_atuantes'] = $this->Categorias_campos_model->get_categoria_fluxos($data['tipo']->id);
        }
        $data['campo'] = $this->Categorias_campos_model->get_campo($id);
        $data['rel_type'] = $this->input->post('rel_type');
        $this->load->model('Company_model');
        $data['lists'] = $this->Company_model->get_list();

        $this->load->view('gestao_corporativa/categorias_campos/add_campo_form', $data);
    }

    /**
     * 22/11/2022
     * @WannaLuiza
     * Trocar ordem de campos pra cima
     */
    public function subir() {

        $ordem = $this->input->post('ordem');
        $categoria = $this->input->post('categoria_id');
        $this->Intranet_general_model->add_log(["rel_type" => "category", "controller" => "Categorias_campos", "function" => "subir", "action" => "Ordem de Campos trocados ($categoria)", "rel_id" => $categoria]);
        $preenchido_por = $this->input->post('preenchido_por');
        $rel_type = $this->input->post('rel_type');
        if ($preenchido_por != '') {
            $campos_atuais = $this->Categorias_campos_model->get_categoria_campos($categoria, $preenchido_por);
        } else {
            $campos_atuais = $this->Categorias_campos_model->get_categoria_campos_all($categoria, $rel_type);
        }

        //echo count($campos_atuais); exit;
        for ($i = 0; $i < count($campos_atuais); $i++) {
            if ($ordem != '1') {
                if ($campos_atuais[$i]['ordem'] == $ordem) {
                    if ($campos_atuais[$i - 1]['ordem'] != $campos_atuais[$i]['ordem']) {
                        $dados1['ordem'] = $campos_atuais[$i - 1]['ordem'];
                        $this->db->where('id', $campos_atuais[$i]['id']);
                        $this->db->update('tbl_intranet_categorias_campo', $dados1);
                        $dados2['ordem'] = $campos_atuais[$i]['ordem'];
                        $this->db->where('id', $campos_atuais[$i - 1]['id']);
                        $this->db->update('tbl_intranet_categorias_campo', $dados2);
                    } else {
                        for ($i = 0; $i < count($campos_atuais); $i++) {
                            $data['ordem'] = $i + 1;
                            $this->db->where('id', $campos_atuais[$i]['id']);
                            $this->db->update('tbl_intranet_categorias_campo', $data);
                        }
                    }
                }
            }
        }
    }

    /**
     * 22/11/2022
     * @WannaLuiza
     * Trocar ordem de campos pra baixo
     */
    public function descer() {
        $ordem = $this->input->post('ordem');
        $categoria = $this->input->post('categoria_id');
        $this->Intranet_general_model->add_log(["rel_type" => "category", "controller" => "Categorias_campos", "function" => "subir", "action" => "Ordem de Campos trocados ($categoria)", "rel_id" => $categoria]);
        $preenchido_por = $this->input->post('preenchido_por');
        $rel_type = $this->input->post('rel_type');
        if ($preenchido_por != '') {
            $campos_atuais = $this->Categorias_campos_model->get_categoria_campos($categoria, $preenchido_por);
        } else {
            $campos_atuais = $this->Categorias_campos_model->get_categoria_campos_all($categoria, $rel_type);
        }

        for ($i = 0; $i < count($campos_atuais); $i++) {
            if ($ordem != count($campos_atuais)) {
                if ($campos_atuais[$i]['ordem'] == $ordem) {
                    if ($campos_atuais[$i + 1]['ordem'] != $campos_atuais[$i]['ordem']) {
                        $dados1['ordem'] = $campos_atuais[$i + 1]['ordem'];
                        $this->db->where('id', $campos_atuais[$i]['id']);
                        $this->db->update('tbl_intranet_categorias_campo', $dados1);
                        $dados2['ordem'] = $campos_atuais[$i]['ordem'];
                        $this->db->where('id', $campos_atuais[$i + 1]['id']);
                        $this->db->update('tbl_intranet_categorias_campo', $dados2);
                    } else {

                        for ($i = 0; $i < count($campos_atuais); $i++) {
                            $data['ordem'] = $i + 1;
                            $this->db->where('id', $campos_atuais[$i]['id']);
                            $this->db->update('tbl_intranet_categorias_campo', $data);
                        }
                    }
                }
            }
        }
    }

    function save_campo() {



        $info = $this->input->post();
        $rel_type = $info['rel_type'];
        if ($info['options'] != '') {
            $options = $info['options'];
            unset($info['options']);
        }

        if ($info['preenchido_por'] == '') {
            $info['preenchido_por'] == 0;
        }


        $info['empresa_id'] = $this->session->userdata('empresa_id');
        $info['name'] = $this->tira_pontuacao_espaco_caractereespecial($info['nome']) . uniqid();
        if ($this->input->post('id')) {
            $info['data_ultima_alteracao'] = date('Y-m-d');
            $info['user_ultima_alteracao'] = get_staff_user_id();
            $this->db->where('id', $this->input->post('id'));
            if ($this->db->update("tbl_intranet_categorias_campo", $info)) {
                echo json_encode(array("alert" => "success", "message" => 'Campo editado com sucesso!'), true);
            } else {
                echo json_encode(array("alert" => "danger", "message" => 'Erro ao editar campo'), true);
            }
            $delete_options['deleted'] = 1;
            $this->db->where('campo_id', $this->input->post('id'));
            $this->db->update("tbl_intranet_categorias_campo_options", $delete_options);
            $id = $this->input->post('id');
        } else {
            if ($rel_type != 'internal_request_workflow' and $rel_type != 'external_request_workflow') {
                if ($info['preenchido_por'] != '') {
                    $campos_atuais = $this->Categorias_campos_model->get_categoria_campos($info['categoria_id'], $info['preenchido_por']);
                } else {
                    $campos_atuais = $this->Categorias_campos_model->get_categoria_campos_all($info['categoria_id'], $rel_type);
                }


                $info['ordem'] = (count($campos_atuais) + 1);
            }
            $info['data_cadastro'] = date('Y-m-d');
            $info['user_cadastro'] = get_staff_user_id();
            $info['data_ultima_alteracao'] = date('Y-m-d');
            $info['user_ultima_alteracao'] = get_staff_user_id();
            if ($this->db->insert("tbl_intranet_categorias_campo", $info)) {
                $id = $this->db->insert_id();
                echo json_encode(array("alert" => "success", "message" => 'Campo salvo com sucesso!'), true);
            } else {
                echo json_encode(array("alert" => "danger", "message" => 'Erro ao salvar campo'), true);
            }
        }

        if (isset($_POST['options'])) {
            $optionData = $_POST['options'];

            $info_options['campo_id'] = $id;
            $info_options['date_created'] = date('Y-m-d');
            $info_options['user_created'] = get_staff_user_id();
            $info_options['empresa_id'] = $this->session->userdata('empresa_id');
            $info_options['deleted'] = '0';

            // Iterar sobre os dados das opções enviadas e atualizar/inserir no banco de dados
            for ($i = 0; $i < count($optionData['id']); $i++) {
                $id = $optionData['id'][$i];
                $value = $optionData['value'][$i];
                $info_options['option'] = $value;

                // Verificar se o registro já existe
                $this->db->where('id', $id);
                $row = $this->db->get('tbl_intranet_categorias_campo_options')->row();

                if ($row) {
                    $this->db->where('id', $id);
                    $this->db->update("tbl_intranet_categorias_campo_options", $info_options);
                } else {
                    $this->db->insert("tbl_intranet_categorias_campo_options", $info_options);
                }
            }
        }



        $tipo = $this->Categorias_campos_model->get_categoria($info['categoria_id'], $rel_type);
        //$data['campos'] = $this->Categorias_campos_model->get_categoria_campos_all($data['tipo']->id, $rel_type);

        if ($tipo) {
            if ($tipo->atuantes != '') {
                $data['categoria_atuantes'] = $this->Registro_ocorrencia_model->get_categoria_atuantes($tipo->atuantes);
            }
        }
        $this->Intranet_general_model->add_log(["rel_type" => "category", "controller" => "Categorias_campos", "function" => "save_campo", "action" => "Campo Registrado ($id)", "rel_id" => $info['categoria_id']]);

        //$this->load->view('gestao_corporativa/categorias_campos/add_campo_form', $data);
        // echo json_encode(array("resposta1" => $resposta, "resposta2" => $resposta_2), true);
    }

    function tira_pontuacao_espaco_caractereespecial($string) {

        // matriz de entrada
        $what = array('ä', 'ã', 'à', 'á', 'â', 'ê', 'ë', 'è', 'é', 'ï', 'ì', 'í', 'ö', 'õ', 'ò', 'ó', 'ô', 'ü', 'ù', 'ú', 'û', 'À', 'Á', 'É', 'Í', 'Ó', 'Ú', 'ñ', 'Ñ', 'ç', 'Ç', ' ', '-', '(', ')', ',', ';', ':', '|', '!', '"', '#', '$', '%', '&', '/', '=', '?', '~', '^', '>', '<', 'ª', 'º', '.');

        // matriz de saída
        $by = array('a', 'a', 'a', 'a', 'a', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'A', 'A', 'E', 'I', 'O', 'U', 'n', 'n', 'c', 'C', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '');

        // devolver a string
        return str_replace($what, $by, $string);
    }

    /**
     * 18/10/2022
     * @WannaLuiza
     * Retorna os campos da categoria selecionado 
     */
    public function retorno_categoria_campos() {

        $categoria_id = $this->input->post("categoria_id");

        $categoria = $this->Categorias_campos_model->get_categoria($categoria_id);

        $rel_type = $categoria->rel_type;
        $data['type'] = $rel_type;
        $campos = $this->Categorias_campos_model->get_categoria_campos($categoria_id);
        //echo 'jsj'; exit;
        $doctos = $this->Categorias_campos_model->get_doctos($categoria_id, '', true);

        $data['id'] = $categoria_id;
        $data['campos'] = $campos;
        $data['categoria'] = $categoria;
        $data['doctos'] = $doctos;

        if ($rel_type == 'workflow') {
            if ($categoria->aprovacao == 1) {
                $this->load->view('gestao_corporativa/workflow/retorno_aprovacao', $data);
            }
        }

        if ($rel_type == 'cdc') {
            $flows = $this->Categorias_campos_model->get_categoria_fluxos($categoria_id);
            $this->load->model('Staff_model');
            $data['staffs'] = $this->Staff_model->get();
            $data['flows'] = $flows;
            $qtd_flows = count($flows);
            if ($qtd_flows > 0) {
                $this->load->view('gestao_corporativa/cdc/retorno_fluxos', $data);
            }
        }
        //print_r($campos); exit;
        if (count($campos) > 0) {
            $this->load->view('gestao_corporativa/categorias_campos/retorno_categoria', $data);
        }
    }

    public function add_docto() {


        if ($_POST['file']) {
            // print_r($this->input->post()); exit;
            $file = $this->input->post('file');

            $info = array(
                "categoria_id" => $_POST["cat_id"],
                "file" => $file['file']['file'],
                "caminho" => $file['file']['target_map'],
                "empresa_id" => $this->session->userdata('empresa_id'),
                "date_created" => date('Y-m-d H:i:s'),
                "user_created" => get_staff_user_id(),
            );
            if ($_POST["titulo"]) {
                $info['titulo'] = $_POST["titulo"];
            }
            if ($_POST["intranet"]) {
                $info['intranet'] = $_POST["intranet"];
            }
            if ($_POST["portal"]) {
                $info['portal'] = $_POST["portal"];
            }
            $info['active'] = 1;

            $this->db->where(db_prefix() . '_intranet_categorias_doctos.categoria_id', $_POST["cat_id"]);
            $this->db->update(db_prefix() . '_intranet_categorias_doctos', array('active' => 0));
            //print_r($info); exit;
            $this->db->insert(db_prefix() . '_intranet_categorias_doctos', $info);
            echo json_encode(array("alert" => "success", "message" => 'Arquivo Salvo com Sucesso!'), true);
            $this->Intranet_general_model->add_log(["rel_type" => "category", "controller" => "Categorias_campos", "function" => "add_docto", "action" => "Documento cadastrado na categoria (" . $_POST['cat_id'] . ")", "rel_id" => $_POST['cat_id']]);
        } else {
            echo json_encode(array("alert" => "danger", "message" => 'Erro ao Salvar.'), true);
        }
    }

    public function delete_docto() {

        if ($this->input->post('id')) {

            $id = $this->input->post('id');

            $dados['deleted'] = 1;

            $this->db->where('id', $id);

            $this->db->update(db_prefix() . '_intranet_categorias_doctos', $dados);

            echo json_encode(['alert' => 'success', 'message' => "Deletado com sucesso!"]);
        } else {
            echo json_encode(['alert' => 'danger', 'message' => 'Tente novamente.']);
        }
    }

    public function change_order_table() {

        $ordem_atualizada = $_POST['ordem'];

        foreach ($ordem_atualizada as $index => $id) {
            $data['ordem'] = $index;
            $this->db->where('id', $id);
            $this->db->update(db_prefix() . '_intranet_categorias', $data);
        }
        echo json_encode(['alert' => 'success', 'message' => "Ordem atualizada!"]);
        $this->Intranet_general_model->add_log(["rel_type" => "category", "controller" => "Categorias_campos", "function" => "change_order_table", "action" => "Ordem de categorias atualizada"]);
    }

    public function change_order_table_campos() {

        $ordem_atualizada = $_POST['ordem'];

        foreach ($ordem_atualizada as $index => $id) {
            $data['ordem'] = $index;
            $this->db->where('id', $id);
            $this->db->update(db_prefix() . '_intranet_categorias_campo', $data);
        }
        echo json_encode(['alert' => 'success', 'message' => "Ordem atualizada!"]);
    }

    public function update_active() {

        $id = $_POST['id'];

        $data['active'] = $_POST['active'];
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . '_intranet_categorias', $data);
        $this->Intranet_general_model->add_log(["rel_type" => "category", "controller" => "Categorias_campos", "function" => "update_active", "action" => "Categoria Ativada/Inativada", "rel_id" => $id]);

        echo json_encode(['alert' => 'success', 'message' => "Realizado com Sucesso!"]);
    }

    public function update_active_docto() {
        $id = $_POST['id'];

        $data['active'] = $_POST['active'];

        $this->db->where('categoria_id', $_POST['categoria_id']);
        $this->db->where('active', 1);
        $docto = $this->db->get(db_prefix() . '_intranet_categorias_doctos')->row();
        if ($docto->id != $_POST['id']) {
            $this->db->where('categoria_id', $_POST['categoria_id']);
            $this->db->update(db_prefix() . '_intranet_categorias_doctos', array('active' => 0));

            $this->db->where('id', $id);
            $this->db->update(db_prefix() . '_intranet_categorias_doctos', $data);
            echo json_encode(['alert' => 'success', 'message' => "Realizado com Sucesso!"]);
            $this->Intranet_general_model->add_log(["rel_type" => "category", "controller" => "Categorias_campos", "function" => "update_active", "action" => "Docto desativado ($id)", "rel_id" => $_POST['categoria_id']]);
        } else {
            echo json_encode(['alert' => 'danger', 'message' => "Um documento precisa estar ativo!"]);
        }
    }

    function create_campo() {

        $post = $this->input->post();
        $this->load->view('gestao_corporativa/categorias_campos/create_campo', $post);
    }

    public function get_campos_in_options_filter() {

        $categoria_id = $this->input->post("categoria_id");

        $data['categoria'] = $this->Categorias_campos_model->get_categoria($categoria_id);

        $data['campos'] = $this->Categorias_campos_model->get_categoria_campos($categoria_id);

        if ($this->input->post("rel_type") == 'r.o') {

            $departments = $this->Categorias_campos_model->get_categoria_atuantes_campos($categoria_id);

            $data['campos_'] = $departments;

            $this->load->view('gestao_corporativa/registro_ocorrencia/reports_filter', $data);
        } else {
            $departments = $this->Categorias_campos_model->get_categoria_fluxos($categoria_id, 'setor');

            for ($i = 0; $i < count($departments); $i++) {
                $departments[$i]['campos'] = $this->Categorias_campos_model->get_campos_for_department($categoria_id, $departments[$i]['setor']);
            }
            $data['departments'] = $departments;

            $this->load->view('gestao_corporativa/workflow/reports_filter', $data);
        }
    }

    public function get_campo($campo_id = '') {


        $this->db->where('id', $campo_id);
        return $this->db->get('tbl_intranet_categorias_campo')->row();
    }
}
