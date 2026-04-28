<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Atendimento_Controle extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Comunicacao_model');
        $this->load->model('Registro_ocorrencia_model');
        $this->load->model('Atendimentos_model');
        $this->load->model('Categorias_campos_model');
        $this->load->model('Authentication_model');
    }

    public function index()
    {

        if (!is_staff_logged_in()) {
            redirect(base_url('gestao_corporativa/authentication'));
        }
      
        $data['title'] = 'Registro de Atendimento';

        $data['bodyclass'] = 'tickets-page';
       // add_admin_tickets_js_assets();
        $this->load->model('Staff_model');
        $data['categorias'] = $this->Categorias_campos_model->get_categorias('atendimento');
        $data['canais_atendimentos'] = $this->Atendimentos_model->get_canais_atendimento();
        $data['staffs'] = $this->Staff_model->get();
        $this->load->view('gestao_corporativa/atendimento/list', $data);
    }

    public function logout_without_redirect_empresa() {
        $this->Authentication_model->logout();
      //  hooks()->do_action('after_user_logout');
      //header('location: https://unimedmanaus.sigplus.online/login');
       // redirect();
    }

    public function view($atendimento_id = '') {
        $data['title'] = 'Atendimento #' . $atendimento_id;

        $registros_rapidos = $this->Atendimentos_model->get_values_by_categoria_id($atendimento_id);
        $data['registros_rapidos'] = $registros_rapidos;

        $categoria = $this->Categorias_campos_model->get_categorias('ra_atendimento_rapido');
        $data['categorias'] = $categoria;
        $ros_ra = $this->Atendimentos_model->get_ros_by_ra_id($atendimento_id);
        $auts = $this->Atendimentos_model->get_auts_by_ra_id($atendimento_id);
        $data['auts'] = $auts;
        $data['ros_ra'] = $ros_ra;

        $workflow_ra = $this->Atendimentos_model->get_worklow_by_ra_id($atendimento_id);
        $data['workflow_ra'] = $workflow_ra;
        $data['info'] = $this->Atendimentos_model->get_ra_by_id($atendimento_id);
        $data['atendimento_id'] = $atendimento_id;

        if ($data['info']->client_id) {
            $this->load->model('Clients_model');
            $client = $this->Clients_model->get($data['info']->client_id);
            //print_r($client); exit;
           
            $ch = curl_init();

            curl_setopt_array($ch, array(
                CURLOPT_URL => 'http://sistemaweb.unimedmanaus.com.br/sigplus/api/Informacoes_sistema_tasy',
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

        $permission = false;

        if ($data['info']->user_created == get_staff_user_id() && !$data['info']->data_encerramento) {
            $permission = true;
        }

        $data['permission'] = $permission;
        //print_r($data['info']); exit;

        $this->load->view('gestao_corporativa/atendimento/view', $data);
    }

    public function table_ro() {

        $this->app->get_table_data_intranet('registro_ocorrencia');
    }

    public function table() {

        $this->app->get_table_data_intranet('registro_atendimentos');
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
    public function add() {

        if ($this->input->post()) {
            //print_r($this->input->post()); exit;

            $arquivo = $_FILES['attachments'];

            if ($arquivo) {

                $Destino = "./assets/intranet/arquivos/atendimentos_arquivos/";

                $Nome = str_replace(' ', '_', $arquivo["name"]);

                $Tmpname = $arquivo["tmp_name"];

                $arquivo = str_replace(' ', '_', $arquivo["name"]);

                $Caminho = $Destino . $Nome;

                if (move_uploaded_file($Tmpname, $Caminho)) {

                    $salvos++;
                }

                $banco_arquivos_em_string = $arquivo;
            }

            $info = [
                //"subject" => $this->input->post('assunto'),
                "categoria_id" => $this->input->post('categoria_id'),
                "arquivo" => $banco_arquivos_em_string,
                "canal_atendimento_id" => $this->input->post('canal_atendimento_id'),
                "contato" => $this->input->post('contato'),
                "email" => $this->input->post('email'),
                "client_id" => $this->input->post('client_id')
            ];

            $id = $this->Atendimentos_model->add($info);

            $campos = $this->Categorias_campos_model->get_categoria_campos($this->input->post('categoria_id'));

            foreach ($campos as $campo) {

                $campos_value = array(
                    "categoria_id" => $this->input->post('categoria_id'),
                    "campo_id" => $campo['id'],
                    "rel_id" => $id,
                    "rel_type" => 'atendimento',
                    "data_cadastro" => date('Y-m-d H:i:s'),
                    "user_cadastro" => get_staff_user_id(),
                    "empresa_id" => $this->session->userdata('empresa_id')
                );

                if ($campo['type'] == 'file') {
                    $value = $_FILES['campo_' . $campo['name']];
                    if (!file_exists("./assets/intranet/arquivos/ra_arquivos/campo_file/")) {
                        mkdir("./assets/intranet/arquivos/ra_arquivos/campo_file/", 0777, true);
                    }
                    $Destino = "./assets/intranet/arquivos/ra_arquivos/campo_file/";

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
                        foreach ($value as $val) {
                            $campos_value['value'] = $val;
                            $this->Atendimentos_model->add_campo_value($campos_value);
                        }
                    } else {
                        $campos_value['value'] = $value;
                        $this->Atendimentos_model->add_campo_value($campos_value);
                    }
                }
            }

            if ($id) {
                set_alert('success', _l('Atendimento Salvo!', $id));
                redirect(base_url('gestao_corporativa/atendimento/view/' . $id));
            }
        }
        $this->load->model('departments_model');
        //$this->load->model('Clients_model');

        $data['canais_atendimentos'] = $this->Atendimentos_model->get_canais_atendimento();
        $data['categorias'] = $this->Categorias_campos_model->get_categorias('atendimento');
        //$data['clients'] = $this->Clients_model->get();
        $data['bodyclass'] = 'ticket';
        $data['title'] = 'Novo Atendimento';

        add_admin_tickets_js_assets();
        $this->load->view('gestao_corporativa/atendimento/add', $data);
    }

    public function encerrar_atendimento($atendimento_id = '') {
        $this->Atendimentos_model->encerrar_atendimento($atendimento_id);
        set_alert('success', _l('Atendimento Finalizado!', $atendimento_id));
        redirect(base_url('gestao_corporativa/atendimento/view/' . $atendimento_id));
    }

    public function select_client() {

        $this->load->model('Clients_model');
        $client = $this->Clients_model->get($this->input->post('client_id'));
        //print_r($client); echo 'jdjjd'; exit;

        echo json_encode([
            'company' => $client->company,
            'userid' => $client->userid,
            'phonenumber' => $client->phonenumber,
            'email' => $client->email2,
        ]);
    }

    public function import_client() {
        //print_r($this->input->post()); exit;
        //echo $this->input->post('numero_carteirinha'); exit;
        //echo "SELECT * FROM tblclients where deleted = 0 and numero_carteirinha = '". $this->input->post('numero_carteirinha')."'"; exit;
        $client = $this->db->query("SELECT * FROM tblclients where deleted = 0 and numero_carteirinha = '" . $this->input->post('numero_carteirinha') . "'")->row();
        $alert = 'danger';
        $msg = 'Este cliente já existe!';
        if (!$client) {
            $ch = curl_init();

            curl_setopt_array($ch, array(
                CURLOPT_URL => 'http://sistemaweb.unimedmanaus.com.br/sigplus/api/Tasy',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => '{ "carteirinha": "' . $this->input->post('numero_carteirinha') . '" }',
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json; charset=utf-8'
                ),
            ));

            $response = curl_exec($ch);
            if ($response) {
                $info = json_decode($response, true);
                $result = $info;
                //print_r($result); exit;
                $info_client = [
                    "company" => $result['NOME'],
                    "phonenumber" => $result['TELEFONE'],
                    "email2" => $result['EMAIL'],
                    "vat" => $result['CPF'],
                    "vat_pagador" => $result['CPF_PAGADOR'],
                    "cd_pessoa" => $result['CD_PESSOA'],
                    "address" => $result['ENDERECO'],
                    "endereco_numero" => $result['NUMERO'],
                    "endereco_bairro" => $result['BAIRRO'],
                    "zip" => $result['CEP'],
                    "city" => $result['MUNICIPIO'],
                    "state" => $result['ESTADO'],
                    "numero_carteirinha" => $result['CARTEIRINHA'],
                    "dt_nascimento" => date('Y-m-d', strtotime($result['DATNASCIMENTO'])),
                    "company_pagador" => $result['NOME_PAGADOR'],
                    "cd_pagador" => $result['CD_PAGADOR'],
                    "ie_tipo_segurado" => $result['IE_TIPO_SEGURADO'],
                ];
                if ($info['STATUS'] == true) {
                    $info_client['active'] = 1;
                } else {
                    $info_client['active'] = 0;
                }

                $info_client['datecreated'] = date('Y-m-d H:i:s');
                $info_client['data_ultima_alteracao'] = date('Y-m-d H:i:s');

                $this->db->insert("tblclients", $info_client);
                $this->load->model('Clients_model');
                $client = $this->Clients_model->get($this->db->insert_id());
                $alert = 'success';
                $msg = 'Carteirinha encontrada e importada!';
            } else {
                $alert = 'danger';
                $msg = 'Carteirinha não encontrada!';
            }
            curl_close($ch);
        }
        //print_r($client); exit;
        if($client){
            $result = true;
        }
        echo json_encode([
            'result' => $result,
            'alert' => $alert,
            'msg' => $msg,
            'company' => $client->company,
            'userid' => $client->userid,
            'phonenumber' => $client->phonenumber,
            'email' => $client->email2,
        ]);
    }

    /**
     * 19/12/2022
     * @WannaLuiza
     * Retorna os campos da categoria selecionado 
     */
    public function retorno_categoria_campos() {
        $categoria_id = $this->input->post("categoria_id");
        echo 'smnsd';
        exit;
        $categoria = $this->Categorias_campos_model->get_categoria($categoria_id, 'atendimento');

        $campos = $this->Atendimentos_model->get_categoria_campos($categoria_id);

        $data['id'] = $categoria_id;
        $data['campos'] = $campos;
        $data['categoria'] = $categoria;
        if (count($campos) > 0) {
            $this->load->view('gestao_corporativa/atendimento/retorno_categoria', $data);
        }
    }

    public function retorno_categoria_campos_ra() {
        $categoria_id = $this->input->post("categoria_id");
        $categoria = $this->Categorias_campos_model->get_categoria_atendimento_rapido($categoria_id, 'ra_atendimento_rapido');
        $campos = $this->Atendimentos_model->get_categoria_campos($categoria_id);

        $data['id'] = $categoria_id;
        $data['campos'] = $campos;
        $data['categoria'] = $categoria;
        if (count($campos) > 0) {
            $this->load->view('gestao_corporativa/atendimento/retorno_categoria', $data);
        }
    }

    /**
     * 19/12/2022
     * @WannaLuiza
     * Retorna os campos da categoria selecionado 
     */
    public function retorno_categoria() {
        $categoria_id = $this->input->post("categoria_id");
        $categoria = $this->Atendimentos_model->get_categoria($categoria_id);
        $campos = $this->Atendimentos_model->get_categoria_campos($categoria_id);

        $data['id'] = $categoria_id;
        $data['campos'] = $campos;
        $data['categoria'] = $categoria;
        if (count($campos) > 0) {
            $this->load->view('gestao_corporativa/atendimento/retorno_categoria_campos', $data);
        }
    }

    public function pdf($atendimento_id = '') {
        $registros_rapidos = $this->Atendimentos_model->get_values_by_categoria_id($atendimento_id);
        $data['registros_rapidos'] = $registros_rapidos;

        $data['ros_ra'] = $this->Atendimentos_model->get_ros_by_ra_id($atendimento_id);
        $data['auts'] = $this->Atendimentos_model->get_auts_by_ra_id($atendimento_id);

        $data['workflow_ra'] = $this->Atendimentos_model->get_worklow_by_ra_id($atendimento_id);
        $data['info'] = $this->Atendimentos_model->get_ra_by_id($atendimento_id);
        $data['atendimento_id'] = $atendimento_id;

        if ($data['info']->client_id) {
            $this->load->model('Clients_model');
            $client = $this->Clients_model->get($data['info']->client_id);
            //print_r($client); exit;
            $curl = curl_init();

            $ch = curl_init();

            curl_setopt_array($ch, array(
                CURLOPT_URL => 'http://sistemaweb.unimedmanaus.com.br/sigplus/api/Informacoes_sistema_tasy',
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

        $data['campos'] = $this->Categorias_campos_model->get_values($data['info']->id, 'atendimento');

        $this->load->library('Pdf');
        $company_logo = get_option('company_logo');
        $data['company_name'] = get_option('companyname');

        $path = base_url() . 'uploads/company/' . $company_logo;
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data_ = file_get_contents($path);

        $data['base64'] = 'data:image/' . $type . ';base64,' . base64_encode($data_);
        $html = $this->load->view('gestao_corporativa/atendimento/pdf.php', $data, true);

        $this->dompdf->loadHtml($html);
        $this->dompdf->render();

        $this->dompdf->stream("PROTOCOLO_#" . $data['info']->protocolo . ".pdf", array("Attachment" => 0));
    }
    
    public function resolucao_carol(){
        
        $categorias = $this->Categorias_campos_model->get_categorias('r.o');
        
        foreach ($categorias as $cat){
            print_r($cat);
            $rel_type['rel_type'] = 'r.o';
            $this->db->where('categoria_id', $cat['id']);
             $this->db->where('rel_type', 'workflow');
            $this->db->update('tbl_intranet_categorias_campo', $rel_type);
        }
        
        
        
    }
}
