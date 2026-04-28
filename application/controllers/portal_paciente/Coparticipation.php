<?php

use Dompdf\Options;
use Dompdf\Dompdf;

class Coparticipation extends ClientsController {

    function __construct() {

        parent::__construct();
        $this->load->model('Clients_model');
    }

    public function index() {

        $_d["tittle"] = 'Coparticipação';
        $_d["topo"] = 'Coparticipação';

        $id_client = get_client_user_id();
        if (!$id_client) {
            redirect(base_url('Authentication/logout'));
        }

        $_d["info_perfil"] = $this->Clients_model->get($id_client);

        $_d['protocolo'] = $this->session->userdata('protocolo');
        $this->load->model('Atendimentos_model');
        $_d['atendimento'] = $this->Atendimentos_model->get_ra_by_id($this->session->userdata('atendimento_id'));

        $competencias = [];

        $meses = ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'];
        $ano_anterior = date('Y') - 1;

        foreach ($meses as $mes) {
            $competencias[] = array("value" => "$mes/$ano_anterior", "label" => "$ano_anterior/$mes");
        }
        foreach ($meses as $mes) {
            if($mes < date('m') || ($mes == date('m') && date('d') > 12)) {
                $competencias[] = array("value" => "$mes/".date('Y'), "label" => date('Y')."/$mes");
            }
        }

        $_d['competencias'] = $competencias;
        $this->load->view('portal/coparticipation/index.php', $_d);
    }

    public function search() {


        $comp = $this->input->post('comp');
        //echo $comp; exit;

        $ch = curl_init();

        curl_setopt_array($ch, array(
            CURLOPT_URL => 'http://sistemaweb.unimedmanaus.com.br/sigplus/api/Coparticipacao/Relatorio_coparticipacao',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{ "carteirinha": "' . $this->session->userdata('carteirinha') . '", "dt_referencia": "' . $comp . '" }',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json; charset=utf-8'
            ),
        ));

        $response = curl_exec($ch);

        //echo $response; exit;

        $all = json_decode($response, true);
        //print_r($info_c); exit;

        if (!$all['falha']) {
            $info = [];
            $indexador = 0;
            $info[$indexador] = [];
            $info_c = $all['array'];
            if(count($info_c) == 0){
                $_d['error'] = true;
            }

            for ($i = 0; $i < count($info_c); $i++) {
                if ($i == 0) {
                    $atual = $info_c[$i]['CARTEIRINHA'];
                }
                if ($i > 0) {
                    if ($info_c[$i]['CARTEIRINHA'] != $atual) {

                        $indexador++;
                        $info[$indexador] = [];
                        $atual = $info_c[$i]['CARTEIRINHA'];
                    }
                }

                array_push($info[$indexador], $info_c[$i]);
            }
            // print_r($info); exit;
            $_d['info'] = $info;
            $_d['principal'] = $all['info'];
            $_d['total'] = $all['total'];
        } else {
            $_d['error'] = true;
        }

        curl_close($ch);
        $this->load->view('portal/coparticipation/load_again.php', $_d);
    }

    public function pdf() {

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

        $data['competencia'] = $_GET['comp'];

        $comp = $_GET['comp'];
        //echo $comp; exit;

        $ch = curl_init();

        curl_setopt_array($ch, array(
            CURLOPT_URL => 'http://sistemaweb.unimedmanaus.com.br/sigplus/api/Coparticipacao/Relatorio_coparticipacao',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{ "carteirinha": "' . $this->session->userdata('carteirinha') . '", "dt_referencia": "' . $comp . '" }',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json; charset=utf-8'
            ),
        ));

        $response = curl_exec($ch);

        //echo $response; exit;

        $all = json_decode($response, true);
        //print_r($info_c); exit;


        $info = [];
        $indexador = 0;
        $info[$indexador] = [];
        $info_c = $all['array'];

        for ($i = 0; $i < count($info_c); $i++) {
            if ($i == 0) {
                $atual = $info_c[$i]['CARTEIRINHA'];
            }
            if ($i > 0) {
                if ($info_c[$i]['CARTEIRINHA'] != $atual) {

                    $indexador++;
                    $info[$indexador] = [];
                    $atual = $info_c[$i]['CARTEIRINHA'];
                }
            }

            array_push($info[$indexador], $info_c[$i]);
        }
        // print_r($info); exit;
        $data['info'] = $info;
        $data['principal'] = $all['info'];
        $data['total'] = $all['total'];

        $html = $this->load->view('portal/coparticipation/pdf.php', $data, true);

        $this->dompdf->loadHtml($html);
        $this->dompdf->render();

        $this->dompdf->stream("RELATORIO_COPARTICIPACAO.pdf", array("Attachment" => 0));
    }

}

/* End of file dashboard.php */
/* Location: ./application/controllers/dashboard.php */