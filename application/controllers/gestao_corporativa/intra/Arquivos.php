<?php


header('Content-Type: text/html; charset=utf-8');
defined('BASEPATH') or exit('No direct script access allowed');

class Arquivos extends AdminController {

    public function __construct() {
        parent::__construct();
        $this->load->model('Intranet_model');
        $this->load->model('Arquivo_model');
        $this->load->model('Staff_model');
        $this->load->model('Departments_model');
        $this->load->model('Comunicado_model');
    }

    /* List all leads */

    public function index() {

        $tipos_arquivo = $this->Arquivo_model->get_tipos();
        $data['tipos'] = $tipos_arquivo;
        $data['departments_staffs'] = $this->Intranet_model->get_departamentos_staffs_selecionados();
        $data['staff'] = $this->Staff_model->get();
        $this->load->view('gestao_corporativa/intranet/arquivos/index.php', $data);
    }

    function salvar() {

        //print_r($this->input->post());
        //exit;
        $config['upload_path'] = './assets/intranet/img/arquivos/'; //pastaaaaa

        $config['allowed_types'] = '*'; //tipoooos, wanna
        //
        //Configurando
        $tipo = pathinfo($_FILES['arquivo']['name']);
        $this->load->library('upload', $config);
        $this->upload->initialize($config);
        if (!$this->upload->do_upload('arquivo')) {
            $error = array('error' => $this->upload->display_errors());

            print_r($error);
        } else {
            $info = array(
                "titulo" => $this->input->post('titulo'),
                "deleted" => 0,
                "file" => str_replace(' ', '_', $_FILES['arquivo']['name']),
                "descricao" => $this->input->post('descricao'),
                "dt_created" => date('Y-m-d H:i:s'),
                "tipo" => $tipo['extension'],
                "tipo_id" => $this->input->post('tipo_id'),
                "user_create" => get_staff_user_id(),
                "user_create" => get_staff_user_id(),
                "empresa_id" => $this->session->userdata('empresa_id')
            );

            $sends = $this->input->post('for_staffs');

            $save = $this->Arquivo_model->add($info, $sends, $id);

            $campos = $this->input->post('campos');
            $values = $this->input->post('values');
            if($this->input->post('campo_multiple')){
                $campos_value = array(
                        "value" => implode(', ', $this->input->post('values_multiple')),
                        "tipo_id" => $this->input->post('tipo_id'),
                        "campo_id" => $this->input->post('campo_multiple'),
                        "arquivo_id" => $save,
                        "data_cadastro" => date('Y-m-d H:i:s'),
                        "user_cadastro" => get_staff_user_id(),
                        "empresa_id" => $this->session->userdata('empresa_id')
                    );
                //print_r($campos_value); exit;
                $insert = $this->Arquivo_model->add_campo_value($campos_value);
            }
            //print_r($campos);            print_r($values); exit;
            if ($values) {
                for ($i = 0; $i < count($values); $i++) {
                    $campos_value = array(
                        "value" => $values[$i],
                        "tipo_id" => $this->input->post('tipo_id'),
                        "campo_id" => $campos[$i],
                        "arquivo_id" => $save,
                        "data_cadastro" => date('Y-m-d H:i:s'),
                        "user_cadastro" => get_staff_user_id(),
                        "empresa_id" => $this->session->userdata('empresa_id')
                    );
                    //print_r($campos_value);
                    $this->Arquivo_model->add_campo_value($campos_value);
                    //echo 'aqui'; exit;
                }
                //exit;
            }

            if ($save) {
                redirect('gestao_corporativa/intranet_admin/index/?group=arquivos');
            }
        }
    }

    public function delete_arquivo() {
        $id = $_GET['id'];
        $dados['deleted'] = 1;
        $this->db->where('id', $id);

        $this->db->update('tbl_intranet_arquivo', $dados);

        $this->db->where('arquivo_id', $id);

        $this->db->update('tbl_intranet_arquivo_send', $dados);

        redirect('gestao_corporativa/intranet_admin/index/?group=arquivos');
    }

    /**
     * 30/08/2022
     * @WannaLuiza
     * Chama a view para gestão de arquivos a partir da tela inicial
     */
    public function gestao_arquivos() {

        //Content
        $view_data["content"] = 'gestao_arquivos';
        // layout
        $view_data["title"] = 'INTRANET - Arquivos';
        $view_data["layout"] = 'sidebar-collapse'; //layout-top-nav
        $view_data["exibe_menu_esquerdo"] = 1;
        $view_data["exibe_menu_topo"] = 1;
        $view_data["caminho"] = "media/$staff_id";

        //STAFF_ID
        $staff_id = get_staff_user_id();
        if (file_exists("media/$staff_id")) {
            
        } else {
            mkdir("media/$staff_id");
        }
        $view_data['staff_id'] = $staff_id;
        $view_data["caminho"] = "media/$staff_id";
        //View na raiz de intranet
        $this->load->view('gestao_corporativa/intranet/index.php', $view_data);
    }

    public function gestao_arquivos1() {

        $view_data["caminho"] = "media/$staff_id";
        $view_data['staff_id'] = $staff_id;
        $view_data["caminho"] = "media/$staff_id";
        //View na raiz de intranet
        $this->load->view('gestao_corporativa/intranet/arquivos/index.php', $view_data);
    }

    /**
     * 30/08/2022
     * @WannaLuiza
     * Adicionar pasta em media
     */
    public function add_pasta() {
        $titulo_pasta = $this->input->post('titulo');
        $caminho = $this->input->post('caminho');
        $staff_id = get_staff_user_id();
        if (file_exists("media/$staff_id")) {
            
        } else {
            mkdir("media/$staff_id");
        }
        if (file_exists("$caminho/$titulo_pasta")) {
            $view_data['msg'] = "O arquivo verificado existe";
        } else {
            mkdir("$caminho/$titulo_pasta");
        }

        $view_data['staff_id'] = $staff_id;
        $view_data['caminho'] = $caminho;
        $this->load->view('gestao_corporativa/intranet/arquivos/retorno_pastas.php', $view_data);
    }

    /**
     * 30/08/2022
     * @WannaLuiza
     * Adicionar arquivos em media
     */
    public function add_arquivo() {

        echo 'aqui';
        exit;
    }

    /**
     * 31/08/2022
     * @WannaLuiza
     * Mudança de caminho a cada vez que entra em uma pasta
     */
    public function mudar_pasta() {
        $staff_id = get_staff_user_id();

        $caminho = $this->input->post('caminho');

        $caminho_sequinte = $this->input->post('next');
        $caminho .= '/' . $caminho_sequinte;
        $view_data['staff_id'] = $staff_id;
        $view_data['caminho'] = $caminho;
        $this->load->view('gestao_corporativa/intranet/arquivos/retorno_pastas.php', $view_data);
    }

    /**
     * 31/08/2022
     * @WannaLuiza
     * Deleta todos os arquivos que estiverem 'checked' no input checkbox
     *  A FUNÇÃO RECEBE OS NOMES DOS ARQUIVOS
     */
    public function deletar_arquivos_checkbox() {

        $checkboxes = $_POST['checkeds']; // array
        $staff_id = get_staff_user_id(); // staff_id;
        $caminho = $_POST['caminho']; // caminho

        $count = count($checkboxes);
        for ($i = 0; $i < $count; $i++) {
            $files = array_diff(scandir("$caminho/$checkboxes[$i]"), array('.', '..'));
            //print_r($files); exit;
            foreach ($files as $file) {
                rmdir("$caminho/$checkboxes[$i]/$file");
            }
            if (rmdir("$caminho/$checkboxes[$i]")) {
                $view_data['msg'] = 'Pasta deletada com sucesso.';
            } else {
                $view_data['msg'] = 'Falha ao deletar.';
            }
        }
        $view_data['staff_id'] = $staff_id;
        $view_data['caminho'] = $caminho;
        $this->load->view('gestao_corporativa/intranet/arquivos/retorno_pastas.php', $view_data);
    }

    /**
     * 02/09/2022
     * @WannaLuiza
     * Adiciona/Edita Tipo
     */
    function add_tipo() {


        $info = array(
            "titulo" => $this->input->post('titulo'),
            "empresa_id" => $this->session->userdata('empresa_id')
        );
        if ($this->input->post('id')) {
            $info['data_ultima_alteracao'] = date('Y-m-d');
            $info['user_ultima_alteracao'] = get_staff_user_id();
            $this->db->where('id', $this->input->post('id'));
            if ($this->db->update("tbl_intranet_arquivo_tipo", $info)) {
                redirect('gestao_corporativa/intranet_admin/index/?group=arquivos');
            }
        } else {
            $info['data_cadastro'] = date('Y-m-d');
            $info['user_cadastro'] = get_staff_user_id();
            $info['data_ultima_alteracao'] = date('Y-m-d');
            $info['user_ultima_alteracao'] = get_staff_user_id();
            if ($this->db->insert("tbl_intranet_arquivo_tipo", $info)) {
                redirect('gestao_corporativa/intranet_admin/index/?group=arquivos');
            }
        }
    }

    /**
     * 02/09/2022
     * @WannaLuiza
     * Adiciona/Edita um campo. Vinculando ao tipo 
     *  A FUNÇÃO RECEBE OS NOMES DOS ARQUIVOS
     */
    function save_campo() {



        $info = $this->input->post();
        //print_r($info); exit;
        $info['empresa_id'] = $this->session->userdata('empresa_id');
        if ($this->input->post('id')) {
            $info['data_ultima_alteracao'] = date('Y-m-d');
            $info['user_ultima_alteracao'] = get_staff_user_id();
            $this->db->where('id', $this->input->post('id'));
            if ($this->db->update("tbl_intranet_arquivo_tipo_campo", $info)) {
                redirect('gestao_corporativa/intranet_admin/index/?group=arquivos');
            }
        } else {
            $info['data_cadastro'] = date('Y-m-d');
            $info['user_cadastro'] = get_staff_user_id();
            $info['data_ultima_alteracao'] = date('Y-m-d');
            $info['user_ultima_alteracao'] = get_staff_user_id();
            if ($this->db->insert("tbl_intranet_arquivo_tipo_campo", $info)) {

                redirect('gestao_corporativa/intranet_admin/index/?group=arquivos');
            }
        }
    }

    /**
     * 02/09/2022
     * @WannaLuiza
     * Deleta Campo
     */
    public function delete_campo() {
        $id = $_GET['id'];
        $dados['deleted'] = 1;

        $this->db->where('id', $id);

        $this->db->update('tbl_intranet_arquivo_tipo_campo', $dados);

        redirect('gestao_corporativa/intranet_admin/index/?group=arquivos');
    }

    /**
     * 02/09/2022
     * @WannaLuiza
     * Deleta tipo e respectivos campos do mesmo
     */
    public function delete_tipo() {
        $id = $_GET['id'];
        $dados['deleted'] = 1;

        $this->db->where('id', $id);

        $this->db->update('tbl_intranet_arquivo_tipo', $dados);

        $this->db->where('tipo_id', $id);

        $this->db->update('tbl_intranet_arquivo_tipo_campo', $dados);

        redirect('gestao_corporativa/intranet_admin/index/?group=arquivos');
    }

    /**
     * 02/09/2022
     * @WannaLuiza
     * Retorna os campos do tipo selecionado 
     */
    public function retorno_tipo_campos() {
        $tipo_id = $this->input->post("tipo_id");
        $tipo = $this->Arquivo_model->get_tipo($tipo_id);
        $campos = $this->Arquivo_model->get_tipo_campos($tipo_id);
        $data['id'] = $tipo_id;
        $data['campos'] = $campos;
        $data['tipo'] = $campos;
        //print_r($campos); exit;
        $this->load->view('gestao_corporativa/intranet/arquivos/retorno_tipo_campos', $data);
    }

}
