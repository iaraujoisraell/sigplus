<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * 21/11/2022
 * @WannaLuiza
 * INTRANET - REGISTRO OCORRENCIA - Controller para cadastro de um registro anonimo 
 */
class Registro_ocorrencia extends ClientsController {

    /**
     * 21/11/2022
     * @WannaLuiza
     * Tela para adicionar um novo registro
     */
    public function index($hash) {
        //$this->load->model('Registro_ocorrencia_model');
        // $this->load->model('Categorias_campos_model');

        //echo "aqui"; exit;

        if ($this->input->post()) {

          
            $arquivos = $_FILES['attachments'];

            if ($arquivos) {

                $count = count($_FILES['attachments']['name']);

                $Destino = "./assets/intranet/arquivos/ro_arquivos/";

                $files = $_FILES["attachments"];

                $salvos = 0;

                for ($i = 0; $i < $count; $i++) {

                    $Nome = str_replace(' ', '_', $files["name"][$i]);

                    $Tmpname = $files["tmp_name"][$i];

                    $arquivo[$i] = str_replace(' ', '_', $files["name"][$i]);

                    $Caminho = $Destino . $Nome;

                    if (move_uploaded_file($Tmpname, $Caminho)) {

                        $salvos++;
                    }
                }
                $banco_arquivos_em_string = implode(",", $arquivo);
            }

            $categoria_id = $this->input->post('categoria_id');
            $sql_campos = "select * from tbl_intranet_categorias_campo "
                    . "where  deleted = 0 and categoria_id = $categoria_id ORDER BY ordem asc";
            //echo $sql_campos; exit;
            $campos = $this->db->query($sql_campos)->result_array();
            $data = str_replace("/", "-", $this->input->post('date'));
            $data = date('Y-m-d', strtotime($data));
            $info = [
                "subject" => $this->input->post('assunto'),
                "report" => $this->input->post('descricao'),
                "date" => $data,
                "priority" => $this->input->post('priority'),
                "categoria_id" => $this->input->post('categoria_id'),
                "empresa_id" => $this->input->post('empresa_id'),
                "anonimo" => '1',
                "arquivos" => $banco_arquivos_em_string,
                "status" => '1',
            ];
            $info['date_created'] = date('Y-m-d h:i:s');
            $salvos = 0;
            $categoria = $this->db->query("select * from tbl_intranet_categorias where id = $categoria_id")->row();

            //print_r($info); exit;
            if ($this->db->insert("tbl_intranet_registro_ocorrencia", $info)) {
                $campos_value['rel_id'] = $this->db->insert_id();
                $campos_value['rel_type'] = 'r.o';

                $campos_value['data_cadastro'] = date('Y-m-d h:i:s');
                $campos_value['categoria_id'] = $this->input->post('categoria_id');
                $campos_value['empresa_id'] = $this->input->post('empresa_id');

                foreach ($campos as $campo) {

                    $campos_value['campo_id'] = $campo['id'];
                    if ($campo['type'] == 'file') {
                        $value = $_FILES['campo_' . $campo['name']];
                        if (!file_exists("./assets/intranet/arquivos/ro_arquivos/campo_file/")) {
                            mkdir("./assets/intranet/arquivos/ro_arquivos/campo_file/", 0777, true);
                        }
                        $Destino = "./assets/intranet/arquivos/ro_arquivos/campo_file/";

                        $Nome = str_replace(' ', '_', $value["name"]);

                        $Tmpname = $value["tmp_name"];

                        $Caminho = $Destino . $Nome;

                        if (move_uploaded_file($Tmpname, $Caminho)) {
                            $value = $Nome;
                        } else {
                            $value = 'Erro ao salvar';
                        }
                    } else {
                        $value = $this->input->post('campo_' . $campo['name']);
                    }

                    if ($value) {
                        if (is_array($value)) {
                            $value = implode(', ', $value);
                        }
                    }


                    $campos_value['value'] = $value;
                    //print_r($campos_value); exit;
                    if ($this->db->insert("tbl_intranet_categorias_campo_values", $campos_value)) {
                        $salvos++;
                    }
                }
                //echo 'ksks'; exit;
                if ($salvos == count($campos)) {

                    $empresa_id = $categoria->empresa_id;

                    $form_get_option_logo = $this->db->query("SELECT * FROM tbloptions where name = 'company_logo' and empresa_id = $empresa_id")->row();

                    $form_get_option_nome_empresa = $this->db->query("SELECT * FROM tbloptions where name = 'companyname' and empresa_id = $empresa_id")->row();

                    $company_logo = $form_get_option_logo->value;

                    $company_name = $form_get_option_nome_empresa->value;

                    $this->successfully($company_logo, $company_name);
                } else {
                    $empresa_id = $categoria->empresa_id;

                    $form_get_option_logo = $this->db->query("SELECT * FROM tbloptions where name = 'company_logo' and empresa_id = $empresa_id")->row();

                    $form_get_option_nome_empresa = $this->db->query("SELECT * FROM tbloptions where name = 'companyname' and empresa_id = $empresa_id")->row();

                    $company_logo = $form_get_option_logo->value;

                    $company_name = $form_get_option_nome_empresa->value;
                    successfully($company_logo, $company_name);
                    $this->error($company_logo, $company_name);
                }
            }
        }


        $categoria = $this->db->query("select * from tbl_intranet_categorias where hash = '$hash'")->row();
        $categoria_id = $categoria->id;
        $empresa_id = $categoria->empresa_id;

        $form_get_option_logo = $this->db->query("SELECT * FROM tbloptions where name = 'company_logo' and empresa_id = $empresa_id")->row();

        $form_get_option_nome_empresa = $this->db->query("SELECT * FROM tbloptions where name = 'companyname' and empresa_id = $empresa_id")->row();

        $company_logo = $form_get_option_logo->value;

        $company_name = $form_get_option_nome_empresa->value;
        $data['hash'] = $hash;
        $data['categoria'] = $categoria;

        $data['campos'] = $this->db->query("select * from tbl_intranet_categorias_campo "
                        . "where  deleted = 0 and categoria_id = $categoria_id and preenchido_por = '0' ORDER BY ordem asc")->result_array();
        // $data['campos'] = $this->Categorias_campos_model->get_categoria_campos($categoria_id, '0');
        //print_r($data['campos']); exit;
        $data['company_logo'] = $company_logo;
        $data['company_name'] = $company_name;

        //$this->load->model('Staff_model');
        $data['setores'] = $this->db->query("select * from tbldepartments where empresa_id = $empresa_id and deleted = 0")->result_array();
        $data['staffs'] = $this->db->query("select * from tblstaff where empresa_id = $empresa_id and deleted = 0")->result_array();

        $this->load->view('gestao_corporativa/registro_ocorrencia/web_registro_anonimo', $data);
    }

    public function successfully($logo = '', $name = '') {

        $data['msg'] = 'Registro de Ocorrência Enviado!';
        $data['titulo'] = 'Obrigado!';
        $data['submsg'] = 'Este Registro é anônimo e sua identidade está segura.';
        $data['logo'] = $logo;
        $data['name'] = $name;
        $this->load->view('gestao_corporativa/registro_ocorrencia/web_msg_obg', $data);
    }

    public function error($logo, $name) {
        $data['msg'] = 'Registro não Cadastrado!';
        $data['titulo'] = 'Sinto Muito!';
        $data['submsg'] = 'Houve um problema ao finalizar essa ação.';
        $data['logo'] = $logo;
        $data['name'] = $name;
        $this->load->view('gestao_corporativa/registro_ocorrencia/web_msg_obg', $data);
    }

}
