<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Profile extends ClientsController {

    function __construct() {

        parent::__construct();

        $this->load->model('Clients_model');
    }

    public function index($true_false = '') {
        if ($true_false == 2) {
            $_d['msg_error'] = 'Erro ao atualizar dados.';
        } elseif ($true_false == 1) {
            $_d['msg_success'] = 'Dados atualizados com sucesso!';
        }

        $_d["tittle"] = 'Perfil';
        $_d["topo"] = 'Perfil';

        $id_client = get_client_user_id();
        if (!$id_client) {
            redirect(base_url('Authentication/logout'));
        }

        $_d["info_perfil"] = $this->Clients_model->get($id_client);
        
      

        $_d['protocolo'] = $this->session->userdata('protocolo');

        $ch = curl_init();

        curl_setopt_array($ch, array(
            CURLOPT_URL => 'http://sistemaweb.unimedmanaus.com.br/sigplus/api/Dados_Cadastrais',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{ "carteirinha": "'.$_d["info_perfil"]->numero_carteirinha.'"}',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json; charset=utf-8'
            ),
        ));

        $response = curl_exec($ch);
        curl_close($ch);
        $info = json_decode($response);
        //print_r($info); exit;

        $_d['info'] = $info->dados_cadastrais[0];
        $this->load->model('Atendimentos_model');
        //echo $this->session->userdata('atendimento_id'); exit;
        $_d['atendimento'] = $this->Atendimentos_model->get_ra_by_id($this->session->userdata('atendimento_id'));
        $_d['atendimentos'] = $this->Atendimentos_model->get_ra_by_id('', $id_client);
        $this->load->model('Workflow_model');
        $_d['workflows'] = $this->Workflow_model->get_workflow_by_client_id(get_client_user_id());
        $this->load->model('Categorias_campos_model');
        $this->load->model('Registro_ocorrencia_model');

        $registros = false;
        $categorias_registro_ocorrencia = $this->Categorias_campos_model->get_categorias_with_ra('r.o');
        for($i = 0; $i < count($categorias_registro_ocorrencia); $i++){
            $categorias_registro_ocorrencia[$i]['registros'] = $this->Registro_ocorrencia_model->get_ros_categoria_client($categorias_registro_ocorrencia[$i]['id'], $id_client);
            if(count($categorias_registro_ocorrencia[$i]['registros']) > 0){
                $registros = true;
            }
        }
         $_d['categorias'] = $categorias_registro_ocorrencia;
         $_d['registros'] = $registros;

        $this->load->view('portal/perfil/index.php', $_d);
    }

    public function edit($key = 'at_cadastral') {

        $data = $this->input->post();

        $celular = explode(' ', $data['celular']);
        $data['ddd'] = $celular[0];
        $data['celular'] = $celular[1];
        //print_r($data);exit;
        
        $id_client = get_client_user_id();
        if (!$id_client) {
            redirect(base_url('Authentication/logout'));
        }

        $_d["info_perfil"] = $this->Clients_model->get($id_client);

        $ch = curl_init();
        
        curl_setopt_array($ch, array(
            CURLOPT_URL => 'http://sistemaweb.unimedmanaus.com.br/sigplus/api/Dados_Cadastrais',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{ "carteirinha": "'.$_d["info_perfil"]->numero_carteirinha.'"}',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json; charset=utf-8'
            ),
        ));
         $response2 = curl_exec($ch);

        curl_setopt_array($ch, array(
            CURLOPT_URL => 'http://sistemaweb.unimedmanaus.com.br/sigplus/api/Atualiza_Dados',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{"ddd": "' . $data['ddd'] . '", "celular": "' . $data['celular'] . '", "bairro": "' . $data['bairro'] . '", "cod_pessoa": "' . $data['cod_pessoa'] . '", "email": "' . $data['email'] . '", "cep": "' . $data['cep'] . '", "logradouro": "' . $data['logradouro'] . '",
            "complemento": "' . $data['complemento'] . '", "numero": "' . $data['numero'] . '", "localidade": "' . $data['localidade'] . '", "uf": "' . $data['uf'] . '", "codigo_ibge": "' . $data['codigo_ibge'] . '"}',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json; charset=utf-8'
            ),
        ));

        $response = curl_exec($ch);
        //echo $response; exit;
        curl_close($ch);

   
        $true_false = json_decode($response);
        
        $data_old = json_decode(json_encode(json_decode($response2)->dados_cadastrais[0]), true);
        
        $array_old = ['NOME', 'CARTEIRINHA', 'DDD_CELULAR', 'CELULAR', 'EMAIL', 'CEP', 'LOGRADOURO', 'NUMERO', 'BAIRRO', 'LOCALIDADE', 'UF', 'CODIGO_IBGE'];
        $array_new = ['nome', 'carteirinha', 'ddd', 'celular', 'email', 'cep', 'logradouro', 'numero', 'bairro', 'localidade', 'uf', 'codigo_ibge'];
        $desc = '';
        
        for($i = 0; $i < count($array_old); $i++){
            if($data_old[$array_old[$i]] != $data[$array_new[$i]]){
                $desc .= '"'.$data_old[$array_old[$i]].'"'.'=>'.'"'.$data[$array_new[$i]].'"<br>';
            }
        }
        
        if($true_false->Success == 1){
            $result = true;
            
        } else {
            $result = false;
        }
        
        $this->load->model('Company_model');
        
        $this->Company_model->save_client_self($key, $result, $desc);
        
        redirect(base_url() . 'portal/profile/index/' . $true_false->Success);
    }

    function modal_form($id = 0) {
        $view_data['model_info'] = $this->Dashboards_model->get_one($id);
        $this->load->view("dashboards/custom_dashboards/modal_form", $view_data);
    }

    public function save_service($key = '') {
     
        if($key == ''){
            $key = $this->input->post('key');
        }

        $this->load->model('Company_model');
        
        $this->Company_model->save_client_self($key);
    }
    
    public function view($id) {



        
        $_d["tittle"] = 'ATENDIMENTO #'. strtoupper($id);

        $id_client = get_client_user_id();
        if (!$id_client) {
            redirect(base_url('Authentication/logout'));
        }
        $_d["info_perfil"] = $this->Clients_model->get($id_client);
        $_d['protocolo'] = $this->session->userdata('protocolo');

        $this->load->model('Atendimentos_model');
        
        $_d['atendimento'] = $this->Atendimentos_model->get_ra_by_id($this->session->userdata('atendimento_id'));
        $_d['at'] = $this->Atendimentos_model->get_ra_by_id($id);
        
        $workflow_ra = $this->Atendimentos_model->get_worklow_by_ra_id($id);
        $_d['workflow_ra'] = $workflow_ra;
        
        $ros_ra = $this->Atendimentos_model->get_ros_by_ra_id($id);
       
        $_d['ros_ra'] = $ros_ra;
        
        

        $this->load->view('portal/perfil/single.php', $_d);
    }
}

/* End of file dashboard.php */
/* Location: ./application/controllers/dashboard.php */