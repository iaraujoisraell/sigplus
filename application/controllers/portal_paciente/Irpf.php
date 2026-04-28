<?php

use Dompdf\Options;
use Dompdf\Dompdf;

class Irpf extends ClientsController {

    function __construct() {

        parent::__construct();
        $this->load->model('Clients_model');
    }

    public function index() {

        $_d["tittle"] = 'Irpf';
        $_d["topo"] = 'Irpf';

        $id_client = get_client_user_id();
        if (!$id_client) {
            redirect(base_url('Authentication/logout'));
        }

        $_d["info_perfil"] = $this->Clients_model->get($id_client);
        
        if($_d["info_perfil"]->vat_pagador){
            $cpf = $_d["info_perfil"]->vat_pagador;
        } else {
            $cpf = $_d["info_perfil"]->vat;
        }
        

        $_d['protocolo'] = $this->session->userdata('protocolo');
        $this->load->model('Atendimentos_model');
        $_d['atendimento'] = $this->Atendimentos_model->get_ra_by_id($this->session->userdata('atendimento_id'));

        $ch = curl_init();
        $ano = date('Y');
        $ano_anterior = $ano - 1;
        $anos = [];

        for ($i = 2020; $i <= $ano_anterior; $i++) {
            array_push($anos, $i);
        }
        //print_r($anos); exit;

        curl_setopt_array($ch, array(
            CURLOPT_URL => 'http://sistemaweb.unimedmanaus.com.br/sigplus/api/Relatorio_irpf',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{ "cpf": "' . $cpf . '", "ano_base": "' . $ano_anterior . '" }',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json; charset=utf-8'
            ),
        ));

        //02700034287
        $response = curl_exec($ch);

        curl_close($ch);
        //echo $response; exit;
        $info = json_decode($response);

        if ($info->parte01->falha) {
            $_d['error'] = true;
        } else {
            $_d['ano'] = $ano_anterior;
            $_d['anos'] = $anos;

            $_d['info_principal'] = $info->parte01->dados_parte01[0];
            $_d['info_meses'] = $info->parte02->dados_parte02;
            $_d['info_total'] = $info->parte03->dados_parte03;
            $_d['info_total_gastos'] = $info->parte04->dados_parte04[0];
            $this->load->model('Company_model');
            $this->Company_model->save_client_self('menu_irpf', true, 'Busca Realizada');
        }

        $this->load->view('portal/irpf/index.php', $_d);
    }

    public function relatorio_pdf($ano) {

        $id_client = get_client_user_id();
        if (!$id_client) {
            redirect(base_url('Authentication/logout'));
        }

        $_d["info_perfil"] = $this->Clients_model->get($id_client);
        if($_d["info_perfil"]->vat_pagador){
            $cpf = $_d["info_perfil"]->vat_pagador;
        } else {
            $cpf = $_d["info_perfil"]->vat;
        }
        //echo $ano; exit;
        $ch = curl_init();

        
        curl_setopt_array($ch, array(
            CURLOPT_URL => 'http://sistemaweb.unimedmanaus.com.br/sigplus/api/Relatorio_irpf',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{ "cpf": "'.$cpf.'", "ano_base": "'.$ano.'" }',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json; charset=utf-8'
            ),
        ));

        $response = curl_exec($ch);
        curl_close($ch);
        //echo $response; exit;
        $info = json_decode($response);
        $_d['ano'] = $ano;
        $_d['info_principal'] = $info->parte01->dados_parte01[0];
        $_d['info_meses'] = $info->parte02->dados_parte02;
        $_d['info_total'] = $info->parte03->dados_parte03;
        $_d['info_total_gastos'] = $info->parte04->dados_parte04[0];

        $this->load->library('Pdf');
        $company_logo = get_option('company_logo');
        $data['company_name'] = get_option('companyname');

        $path = base_url() . 'uploads/company/' . $company_logo;
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data_ = file_get_contents($path);

        $_d['base64'] = 'data:image/' . $type . ';base64,' . base64_encode($data_);

        $html = $this->load->view('portal/irpf/pdf.php', $_d, true);

        $this->dompdf->loadHtml($html);
        $this->dompdf->render();

        $this->dompdf->stream("IRPF.pdf", array("Attachment" => 0));
    }

    public function load_again() {


        $cpf = $this->input->post('cpf');
        $ano = $this->input->post('ano');

        $id_client = get_client_user_id();

        if (!$id_client) {
            redirect(base_url('Authentication/logout'));
        }
        $_d["info_perfil"] = $this->Clients_model->get($id_client);

        
        if($_d["info_perfil"]->vat_pagador){
            $cpf = $_d["info_perfil"]->vat_pagador;
        } else {
            $cpf = $_d["info_perfil"]->vat;
        }
        $ch = curl_init();
        $ano_atual = date('Y');
        $ano_anterior = $ano_atual - 1;
        $anos = [];

        for ($i = 2020; $i <= $ano_anterior; $i++) {
            array_push($anos, $i);
        }
        //print_r($anos); exit;

        curl_setopt_array($ch, array(
            CURLOPT_URL => 'http://sistemaweb.unimedmanaus.com.br/sigplus/api/Relatorio_irpf',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{ "cpf": "'.$cpf.'", "ano_base": "' . $ano . '" }',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json; charset=utf-8'
            ),
        ));

        $response = curl_exec($ch);

        curl_close($ch);
        //echo $response; exit;
        $info = json_decode($response);

         $_d['ano'] = $ano;
            $_d['anos'] = $anos;
        if ($info->parte01->falha || $info->parte02->falha) {
            
            $_d['error'] = true;
        } else {
           

            $_d['info_principal'] = $info->parte01->dados_parte01[0];
            $_d['info_meses'] = $info->parte02->dados_parte02;
            $_d['info_total'] = $info->parte03->dados_parte03;
            $_d['info_total_gastos'] = $info->parte04->dados_parte04[0];
        }

        $this->load->view('portal/irpf/load_again.php', $_d);
    }

}

/* End of file dashboard.php */
/* Location: ./application/controllers/dashboard.php */