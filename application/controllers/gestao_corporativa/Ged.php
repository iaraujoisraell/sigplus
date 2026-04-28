<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Ged extends AdminController {

    public function __construct() {

        parent::__construct();
        //$this->load->model('Ged_model');
        $this->load->model('Registro_ocorrencia_model');
        $this->load->model('Ged_model');
    }

    /* List all staff roles */

    public function index() {
        close_setup_menu();

        $data['title'] = 'GED';

        //$data['tipos'] = $this->Registro_ocorrencia_model->get_categorias();
        $data['bodyclass'] = 'tickets-page';
        add_admin_tickets_js_assets();
        $this->load->model('Staff_model');
        $data['categorias'] = $this->Ged_model->get_categorias();
        $data['staffs'] = $this->Staff_model->get();
        $this->load->view('gestao_corporativa/ged/list', $data);
    }

    public function table_categorias() {

        $this->app->get_table_data_intranet('ged_categorias');
    }
    
    public function table_categorias_dinamic() {

        $this->app->get_table_data_intranet('ged_table_dinamic');
    }

    public function table() {

        $this->app->get_table_data_intranet('ged');
    }

    /**
     * 19/12/2022
     * @WannaLuiza
     * Adiciona/Edita Tipo
     */
    function add_tipo() {

        $info = array(
            "titulo" => $this->input->post('titulo'),
            "empresa_id" => $this->session->userdata('empresa_id'),
            "rel_type" => 'ged',
        );

        if ($this->input->post('id')) {
            $info['data_ultima_alteracao'] = date('Y-m-d');
            $info['user_ultima_alteracao'] = get_staff_user_id();
            $this->db->where('id', $this->input->post('id'));
            if ($this->db->update("tbl_intranet_categorias", $info)) {
                
            }
        } else {
            $info['data_cadastro'] = date('Y-m-d');
            $info['user_cadastro'] = get_staff_user_id();
            $info['data_ultima_alteracao'] = date('Y-m-d');
            $info['user_ultima_alteracao'] = get_staff_user_id();
            if ($this->db->insert("tbl_intranet_categorias", $info)) {
                
            }
        }
    }

    public function table_campos() {

        $this->app->get_table_data_intranet('ged_categoria_campos');
    }

    /**
     * 22/11/2022
     * @WannaLuiza
     * Chama os modais
     */
    public function modal() {


        if ($this->input->post('slug') === 'update_categoria') {
            $this->load->model('departments_model');
            $data['categoria'] = $this->Ged_model->get_categoria($this->input->post('id'));
            $this->load->view('gestao_corporativa/ged/modal/edit_categoria', $data);
        } elseif ($this->input->post('slug') === 'campos_categoria') {

            $data['tipo'] = $this->Ged_model->get_categoria($this->input->post('id'));

            $data['campos'] = $this->Registro_ocorrencia_model->get_categoria_campos($data['tipo']->id);

            $this->load->view('gestao_corporativa/ged/modal/Campos', $data);
        }
    }

    /**
     * 18/10/2022
     * @WannaLuiza
     * Deleta categoria
     */
    public function delete_tipo() {
        $id = $this->input->post('id');
        $dados['deleted'] = 1;

        $this->db->where('id', $id);

        $this->db->update('tbl_intranet_categorias', $dados);
    }

    /**
     * 19/12/2022
     * @WannaLuiza
     * Adiciona/Edita um campo. Vinculando a categoria
     */
    function save_campo() {



        $info = $this->input->post();

        if ($info['options'] != '') {
            $options = $info['options'];
            unset($info['options']);
        }

        $campos_atuais = $this->Ged_model->get_categoria_campos($info['categoria_id']);

        $info['ordem'] = (count($campos_atuais) + 1);

        $info['empresa_id'] = $this->session->userdata('empresa_id');
        $info['name'] = $this->tira_pontuacao_espaco_caractereespecial($info['nome']);

        if ($this->input->post('id')) {
            $info['data_ultima_alteracao'] = date('Y-m-d');
            $info['user_ultima_alteracao'] = get_staff_user_id();
            $this->db->where('id', $this->input->post('id'));
            $this->db->update("tbl_intranet_categorias_campo", $info);
        } else {
            $info['data_cadastro'] = date('Y-m-d');
            $info['user_cadastro'] = get_staff_user_id();
            $info['data_ultima_alteracao'] = date('Y-m-d');
            $info['user_ultima_alteracao'] = get_staff_user_id();
            $this->db->insert("tbl_intranet_categorias_campo", $info);
            $id = $this->db->insert_id();
        }
        
        foreach ($options as $op){
            $info_options['campo_id'] = $id;
            $info_options['option'] = $op;
            $info_options['date_created'] = date('Y-m-d');
            $info_options['user_created'] = get_staff_user_id();
            $info_options['empresa_id'] = $this->session->userdata('empresa_id');
            $this->db->insert("tbl_intranet_categorias_campo_options", $info_options);
            
        }
        $data['tipo'] = $this->Ged_model->get_categoria($info['categoria_id']);
        $data['campos'] = $this->Ged_model->get_categoria_campos($data['tipo']->id);
        $this->load->view('gestao_corporativa/ged/modal/add_campo_form', $data);
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
     * 19/12/2022
     * @WannaLuiza
     * adiciona arquivo
     */
    public function add($userid = false) {

        if ($this->input->post()) {

            $arquivo = $_FILES['attachments'];

            if ($arquivo) {

                $Destino = "./assets/intranet/arquivos/ged_arquivos/";

                    $Nome = str_replace(' ', '_', $arquivo["name"]);

                    $Tmpname = $arquivo["tmp_name"];

                    $arquivo = str_replace(' ', '_', $arquivo["name"]);

                    $Caminho = $Destino . $Nome;

                    if (move_uploaded_file($Tmpname, $Caminho)) {

                        $salvos++;
                    }
                
                $banco_arquivos_em_string = $arquivo;
            }
            $campos = $this->Ged_model->get_categoria_campos($this->input->post('categoria_id'));
            $info = [
                "subject" => $this->input->post('assunto'),
                "categoria_id" => $this->input->post('categoria_id'),
                "arquivo" => $banco_arquivos_em_string,
            ];
            //print_r($info); exit;
            $id = $this->Ged_model->add($info);
            foreach ($campos as $campo) {

                $campos_value = array(
                    "categoria_id" => $this->input->post('categoria_id'),
                    "campo_id" => $campo['id'],
                    "rel_id" => $id,
                    "data_cadastro" => date('Y-m-d H:i:s'),
                    "user_cadastro" => get_staff_user_id(),
                    "empresa_id" => $this->session->userdata('empresa_id')
                );

                $value = $this->input->post('campo_' . $campo['name']);
                if ($value) {
                    if (is_array($value)) {
                        foreach ($value as $val){
                            $campos_value['value'] = $val;
                            $this->Ged_model->add_campo_value($campos_value);
                        }
                    } else {
                        $campos_value['value'] = $value;
                        $this->Ged_model->add_campo_value($campos_value);
                    }
                }
            }
            $departments = $this->input->post('departments');
            $info_permissions = array(
                    "rel_id" => $id,
                    "rel_type" => 'ged',
                    "empresa_id" => $this->session->userdata('empresa_id')
                );
            if(is_array($departments)){
                foreach($departments as $dep){
                    $info_permissions['staffid_departmentid'] = $dep;
                    $info_permissions['level'] = 1;
                    $this->Ged_model->send($info_permissions);
                }
            }
            $staffs = $this->input->post('staffs');
            if(is_array($staffs)){
                foreach($staffs as $staff){
                    $info_permissions['staffid_departmentid'] = $staff;
                    $info_permissions['level'] = 0;
                    $this->Ged_model->send($info_permissions);
                }
            }

            if ($id) {
                set_alert('success', _l('Arquivo Salvo!', $id));
                redirect(base_url('gestao_corporativa/ged'));
            }
        }
        $this->load->model('staff_model');
        $data['staffs'] = $this->staff_model->get();

        $data['categorias'] = $this->Ged_model->get_categorias();
        $data['bodyclass'] = 'ticket';
        $data['title'] = 'Novo Arquivo';
        $this->load->model('Intranet_model');
        $departments_staffs = $this->Intranet_model->get_departamentos_staffs_selecionados();
        $data['departments_staffs'] = $departments_staffs;

        add_admin_tickets_js_assets();
        $this->load->view('gestao_corporativa/ged/old', $data);
    }
    
    
    /**
     * 19/12/2022
     * @WannaLuiza
     * Retorna os campos da categoria selecionado 
     */
    public function retorno_categoria_campos() {
        $categoria_id = $this->input->post("categoria_id");
        $categoria = $this->Ged_model->get_categoria($categoria_id);
        $campos = $this->Ged_model->get_categoria_campos($categoria_id);
        
        $data['id'] = $categoria_id;
        $data['campos'] = $campos;
        $data['categoria'] = $categoria;
        if (count($campos) > 0) {
            $this->load->view('gestao_corporativa/ged/retorno_categoria', $data);
        }
    }
    
    /**
     * 19/12/2022
     * @WannaLuiza
     * Retorna os campos da categoria selecionado 
     */
    public function retorno_categoria() {
        $categoria_id = $this->input->post("categoria_id");
        $categoria = $this->Ged_model->get_categoria($categoria_id);
        $campos = $this->Ged_model->get_categoria_campos($categoria_id);
        
        $data['id'] = $categoria_id;
        $data['campos'] = $campos;
        $data['categoria'] = $categoria;
        if (count($campos) > 0) {
            $this->load->view('gestao_corporativa/ged/retorno_categoria_campos', $data);
        }
    }
    /**
     * 19/12/2022
     * @WannaLuiza
     * Retorna o <th> da tabela de acordo com a categoria
     */
    public function retorno_table_categorias() {
        $categoria_id = $this->input->post("categoria_id");
        $categoria = $this->Ged_model->get_categoria($categoria_id);
        $campos = $this->Ged_model->get_categoria_campos($categoria_id);
        
        $data['id'] = $categoria_id;
        $data['campos'] = $campos;
        $data['categoria'] = $categoria;
        //print_r($campos); exit;
        $this->load->view('gestao_corporativa/ged/retorno_categoria_table', $data);
    }

}
