<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

use Dompdf\Options;
use Dompdf\Dompdf;

class Financeiro extends ClientsController {

    function __construct() {



        parent::__construct();

        $this->load->model("clients_model");
    }

    public function index($msg = '') {

       // echo "$msg"; exit;

        $this->load->model('Company_model');

        $this->Company_model->save_client_self('menu_boletos');

        $view_data["tittle"] = 'Boletos ';

        $view_data["topo"] = 'Boletos';

        $view_data["type"] = 1;
        
        $view_data["master"] = $msg;

        $id_client = get_client_user_id();

        $dados_cliente = $this->clients_model->get($id_client);
        if($dados_cliente->vat_pagador){
            $cpf = $dados_cliente->vat_pagador;
        } else {
            $cpf = $dados_cliente->vat;
        }
        
        // echo ; exit;
        // CHAMA A API SMS		   
        $ch = curl_init();

        curl_setopt_array($ch, array(
            CURLOPT_URL => 'http://sistemaweb.unimedmanaus.com.br/sigplus/api/Lista_boletos_abertos',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{ "cpf": "' . $cpf . '" }',
            //CURLOPT_POSTFIELDS => '{ "cpf": "02700034287" }',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json; charset=utf-8'
            ),
        ));
        $resultado = curl_exec($ch);
        $result = json_decode($resultado, true);
        //print_r($result); exit;
        if (isset($result['falha'])) {
            $falha = true;
        }


//        foreach ($result as $pagamento) {
//            $periodo = $pagamento['PERIODO'];
//            $mes = substr($periodo, 0, 2);
//            $ano = substr($periodo, 3, 4);
//
//            $mes_Atual = date('m');
//            $ano_Atual = date('Y');
//
//            if ($mes <= $mes_Atual && $ano <= $ano_Atual) {
//                $view_data["exist"] = true;
//            }
//        }

        $view_data["exist"] = true;
        if ($falha == true) {
            $view_data["exist"] = false;
        } else {
            $view_data["result"] = $result;
        }
        $view_data["info_perfil"] = $dados_cliente; // $this->Clients_model->get($id_client);
        $view_data['protocolo'] = $this->session->userdata('protocolo');

        $view_data["id_client"] = $id_client;
       // echo $cpf; exit;
        $view_data["cpf"] = $cpf;

        $this->load->view('portal/financeiro/index.php', $view_data);
    }

    public function visualizar_boleto() {
        
      
        // echo 'aqui : '. $this->input->post('cpf'); exit;
        // CHAMA A API SMS		   

        $view_data["cpf"] =  $this->input->post('cpf');
        $view_data["boleto"] = $this->input->post('boleto');

        $this->load->view('portal/financeiro/boleto.php', $view_data);

    }

    public function visualizar_boleto_email($cpf, $boleto) {
        
      
        // echo 'aqui : '. $this->input->post('cpf'); exit;
        // CHAMA A API SMS		   
        $cpf = base64_decode($cpf);
        $boleto = base64_decode($boleto);

        $view_data["cpf"] =  $cpf;
        $view_data["boleto"] = $boleto;

        $this->load->view('portal/dompdf/boleto.php', $view_data);

    }
    

    



    public function history() {

        $_d["tittle"] = 'Histórico de Pagamentos';

        $_d["topo"] = 'Histórico de Pagamentos';
        
        $ch = curl_init();

        $client = $this->clients_model->get(get_client_user_id());
        //echo $client->vat; exit;
        curl_setopt_array($ch, array(
            CURLOPT_URL => 'http://sistemaweb.unimedmanaus.com.br/sigplus/api/Lista_boletos_abertos/nada_consta',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{ "cpf": "' . $client->vat_pagador . '" }',
            //CURLOPT_POSTFIELDS => '{ "cpf": "02700034287" }',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json; charset=utf-8'
            ),
        ));
        $resultado = curl_exec($ch);
        
        
        $result = json_decode($resultado, true);
       // print_r($result); exit;
        if($result == true){
            $_d['permission_nothing'] = true;
        } else {
           $_d['permission_nothing'] = false;
        }
        

        $id_client = get_client_user_id();
        if (!$id_client) {
            redirect(base_url('Authentication/logout'));
        }

        $this->load->model('Clients_model');
        $_d["info_perfil"] = $this->Clients_model->get($id_client);

        $_d['protocolo'] = $this->session->userdata('protocolo');

        $this->load->model('Atendimentos_model');
        $_d['atendimento'] = $this->Atendimentos_model->get_ra_by_id($this->session->userdata('atendimento_id'));

        $this->load->view('portal/financeiro/history.php', $_d);
    }

    public function search_history() {

        $de = $this->input->post('de');
        $ate = $this->input->post('ate');

        $dados = [];
        if ($de == "" || $ate == "") {
            $dados['error'] = true;
        } else {

            $this->load->model('Clients_model');
            $info_perfil = $this->Clients_model->get(get_client_user_id());

            $ch = curl_init();
            $de = date("d/m/Y", strtotime($de));
            $ate = date("d/m/Y", strtotime($ate));

            curl_setopt_array($ch, array(
                CURLOPT_URL => 'http://sistemaweb.unimedmanaus.com.br/sigplus/api/Historico_Mensalidades',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                //CURLOPT_POSTFIELDS => '{ "carteirinha": "00796030209758008", "dt_inicial" : "01/02/2021", "dt_final" : "31/12/2021"}',
                CURLOPT_POSTFIELDS => '{ "carteirinha": "' . $info_perfil->numero_carteirinha . '", "dt_inicial" : "' . $de . '", "dt_final" : "' . $ate . '"}',
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json; charset=utf-8'
                ),
            ));

            //00796030209758008
            //01/02/2021
            //31/12/2021
            $response = curl_exec($ch);

            curl_close($ch);

            $info = json_decode($response);
            //print_r($info); exit;

            if (isset($info)) {
                $titular = [];
                foreach ($info as $dado) {
                    if ($dado->dependencia == 'Titular') {
                        array_push($titular, $dado);
                    }
                }

                for ($i = 0; $i < count($titular); $i++) {
                    $titular[$i]->det = [];
                    foreach ($info as $dado) {
                        if ($titular[$i]->nr_titulo == $dado->nr_titulo) {
                            //print_r($dado); exit;
                            array_push($titular[$i]->det, $dado);
                        }
                    }
                    //print_r($titular[$i]->det); exit;
                }
                $dados['info'] = $titular;

                $dados['de'] = $de;
                $dados['ate'] = $ate;
                $dados['carteirinha'] = $info_perfil->numero_carteirinha;
            } else {
                $dados['error'] = true;
            }

            $this->load->model('Company_model');

            $desc = "Busca de " . $de . " - " . $ate;

            $this->Company_model->save_client_self('gera_historico_boleto', true, $desc);
        }

        $this->load->view('portal/financeiro/load_again.php', $dados);
    }

    public function history_pdf() {

        $de = $_GET['de'];
        $ate = $_GET['ate'];
        $carteirinha = $_GET['carteirinha'];

        $ch = curl_init();
        $de = date("d/m/Y", strtotime($de));
        $ate = date("d/m/Y", strtotime($ate));

        curl_setopt_array($ch, array(
            CURLOPT_URL => 'http://sistemaweb.unimedmanaus.com.br/sigplus/api/Historico_Mensalidades',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            //CURLOPT_POSTFIELDS => '{ "carteirinha": "00796030209758008", "dt_inicial" : "01/02/2021", "dt_final" : "31/12/2021"}',
            CURLOPT_POSTFIELDS => '{ "carteirinha": "' . $carteirinha . '", "dt_inicial" : "' . $de . '", "dt_final" : "' . $ate . '"}',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json; charset=utf-8'
            ),
        ));

        //00796030209758008
        //01/02/2021
        //31/12/2021
        $response = curl_exec($ch);

        curl_close($ch);

        $info = json_decode($response);
        $titular = [];
        foreach ($info as $dado) {
            if ($dado->dependencia == 'Titular') {
                array_push($titular, $dado);
            }
        }

        for ($i = 0; $i < count($titular); $i++) {
            $titular[$i]->det = [];
            foreach ($info as $dado) {
                if ($titular[$i]->nr_titulo == $dado->nr_titulo) {
                    //print_r($dado); exit;
                    array_push($titular[$i]->det, $dado);
                }
            }
            //print_r($titular[$i]->det); exit;
        }
        $data['info'] = $titular;

        $data['de'] = $de;
        $data['ate'] = $ate;
        $this->load->model('Clients_model');
        $info_perfil = $this->Clients_model->get(get_client_user_id());
        $data['carteirinha'] = $info_perfil->numero_carteirinha;
        $data['company'] = $info_perfil->company;

        $id_client = get_client_user_id();
        if (!$id_client) {
            redirect(base_url('Authentication/logout'));
        }


        $this->load->library('Pdf');
        $company_logo = get_option('company_logo');
        $data['company_name'] = get_option('companyname');

        $path = base_url() . 'uploads/company/' . $company_logo;
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data_ = file_get_contents($path);

        $data['base64'] = 'data:image/' . $type . ';base64,' . base64_encode($data_);

        $html = $this->load->view('portal/financeiro/history_pdf.php', $data, true);

        $this->dompdf->loadHtml($html);
        $this->dompdf->render();

        $this->load->model('Company_model');

        $desc = "Relatório de " . $de . " - " . $ate;

        $this->Company_model->save_client_self('gera_historico_boleto_pdf', true, $desc);

        $this->dompdf->stream("History-(" . $de . "-" . $ate . ").pdf", array("Attachment" => 0));
    }

    public function annualstatement_pdf() {

        $id_client = get_client_user_id();
        if (!$id_client) {
            redirect(base_url('Authentication/logout'));
        }

        $ano = date('Y');

        $ch = curl_init();
        // echo $ano; exit;
        
        $this->load->model('Clients_model');
         $info_perfil = $this->Clients_model->get(get_client_user_id());
        if($info_perfil->cd_pagador){
            $cd = $info_perfil->cd_pagador;
        } else {
            $cd = $info_perfil->cd_pessoa;
        }
        
        curl_setopt_array($ch, array(
            CURLOPT_URL => 'http://sistemaweb.unimedmanaus.com.br/sigplus/api/Relatorio_Quitacao',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{ "cd_pagador": "' . $cd . '", "ano_referencia" : "' . $ano . '"}',
            //CURLOPT_POSTFIELDS => '{ "cod_pessoa": "33502471215", "ano_referencia" : "' . $ano . '"}',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json; charset=utf-8'
            ),
        ));

        $response = curl_exec($ch);
        //echo $response; exit;

        curl_close($ch);                                           

        $info = json_decode($response, true);

        if (is_array($info)) {
            $data['info'] = $info;
        } else {
            $data['error'] = $info;
        }

        $this->load->library('Pdf');
        $company_logo = get_option('company_logo');
        $data['company_name'] = get_option('companyname');
        $data['company_full_name'] = get_option('company_full_name');

        $path = base_url() . 'uploads/company/' . $company_logo;
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data_ = file_get_contents($path);

        $data['base64'] = 'data:image/' . $type . ';base64,' . base64_encode($data_);

       // print_r($data); exit;
        $html = $this->load->view('portal/financeiro/annualstatement_pdf.php', $data, true);

        $this->dompdf->loadHtml($html);
        $this->dompdf->render();

        //$this->load->model('Company_model');
        //$desc = "Relatório de " . $de . " - " . $ate;
        //$this->Company_model->save_client_self('gera_historico_boleto_pdf', true, $desc);

        $this->dompdf->stream("DECLARACAO_QUITACAO.pdf", array("Attachment" => 0));
    }
    
    public function anything_pdf() {

        $id_client = get_client_user_id();
        if (!$id_client) {
            redirect(base_url('Authentication/logout'));
        }
        

 

        $this->load->library('Pdf');
        $company_logo = get_option('company_logo');
        $data['company_name'] = get_option('companyname');

        $path = base_url() . 'uploads/company/' . $company_logo;
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data_ = file_get_contents($path);

        $data['base64'] = 'data:image/' . $type . ';base64,' . base64_encode($data_);

       // print_r($data); exit;
   
        $html = $this->load->view('portal/financeiro/anything_pdf.php', $data, true);

        $this->dompdf->loadHtml($html);
        $this->dompdf->render();

        //$this->load->model('Company_model');
        //$desc = "Relatório de " . $de . " - " . $ate;
        //$this->Company_model->save_client_self('gera_historico_boleto_pdf', true, $desc);

        $this->dompdf->stream("DECLARACAO_NADA_CONSTA.pdf", array("Attachment" => 0));
    }

}

/* End of file dashboard.php */

