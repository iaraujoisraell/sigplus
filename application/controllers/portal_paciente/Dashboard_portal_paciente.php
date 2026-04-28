<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

use Dompdf\Options;
use Dompdf\Dompdf;

class Dashboard_portal_paciente extends ClientsController {

    function __construct() {

        parent::__construct();

        $this->load->model("Portal_clients_model");
    }

    public function index($master = '') {
       // echo "aqui"; exit;

       
        $id_client = get_client_user_id();

        //echo $id_client; exit;

        if (!$id_client or $this->session->userdata('portal_paciente') == 1) {
            redirect(base_url('Authentication/logout'));
        }
        //echo $id_client; exit;
        $this->load->model('Clients_model');
        $view_data["tittle"] = 'Home';
        $view_data["topo"] = 'Home';
        $view_data["master"] = $master;

        $view_data["info_perfil"] = $this->Clients_model->get($id_client);
        $view_data['protocolo'] = $this->session->userdata('protocolo');

        $this->load->model('Atendimentos_model');
        //echo $this->session->userdata('atendimento_id'); exit;
        $view_data['atendimento'] = $this->Atendimentos_model->get_ra_by_id($this->session->userdata('atendimento_id'));

        $this->load->view('portal/dashboard/index.php', $view_data);
    }

    public function portal_paciente() {
        // echo "aqui"; exit;
         $id_client = get_client_user_id();
 
       //  echo $id_client; exit; 
         if (!$id_client or $this->session->userdata('portal_paciente') != 1 or !$this->session->userdata('portal_paciente')) {
             redirect(base_url('Authentication/logout'));
         }
         
         //echo $id_client; exit;
         $this->load->model('Clients_model');
         $view_data["tittle"] = 'Home';
         $view_data["topo"] = 'Home';
         $view_data["master"] = $master;
 
         $view_data["info_perfil"] = $this->Clients_model->get_paciente_portal($id_client);

         $view_data['protocolo'] = $this->session->userdata('protocolo');
 
         $this->load->model('Atendimentos_model');
         //echo $this->session->userdata('atendimento_id'); exit;
         $view_data['atendimento'] = $this->Atendimentos_model->get_ra_by_id($this->session->userdata('atendimento_id'));
 
         $this->load->view('portal/dashboard/portal_paciente.php', $view_data);
     }
 

    public function old() {
        $id_client = get_client_user_id();

        if (!$id_client) {
            redirect(base_url('Authentication/logout'));
        }
        //echo $id_client; exit;
        $this->load->model('Clients_model');
        $view_data["tittle"] = 'Home';
        $view_data["topo"] = 'Home';

        $view_data["info_perfil"] = $this->Clients_model->get($id_client);
        $view_data['protocolo'] = $this->session->userdata('protocolo');

        $this->load->model('Atendimentos_model');
        //echo $this->session->userdata('atendimento_id'); exit;
        $view_data['atendimento'] = $this->Atendimentos_model->get_ra_by_id($this->session->userdata('atendimento_id'));

        $this->load->view('portal/dashboard/index_1.php', $view_data);
    }

    public function single($id) {
        $id_client = get_client_user_id();

        if (!$id_client) {
            redirect(base_url('Authentication/logout'));
        }
        //echo $id_client; exit;
        $this->load->model('Clients_model');
        $view_data["tittle"] = 'Noticia';
        $view_data["topo"] = 'Noticia';
        if ($id == '1') {
            $view_data['img'] = 'noticia_md_1.jpeg';
            $view_data['titulo'] = 'Comunicado Importante';
            $view_data['desc'] = 'Comunicado importante para acesso.';
        } elseif ($id == '2') {
            $view_data['img'] = 'noticia_md_2.jpg';
            $view_data['titulo'] = 'Anúncios Patrocinados';
            $view_data['desc'] = 'Acesse os anúncios patrocinados.';
        } elseif ($id == '3') {
            $view_data['img'] = 'noticia_md_3.jpg';
            $view_data['titulo'] = 'Fora de Manaus';
            $view_data['desc'] = 'Atendimento fora de Manaus.';
        } elseif ($id == '4') {
            $view_data['img'] = 'noticia_md_4.jpeg';
            $view_data['titulo'] = 'Boletos';
            $view_data['desc'] = 'Acesse seu boletos no Portal do Cliente';
        }
        $view_data['imgs'] = ['noticia_md_1.jpeg', 'noticia_md_2.jpg', 'noticia_md_3.jpg', 'noticia_md_4.jpeg'];

        $view_data["info_perfil"] = $this->Clients_model->get($id_client);
        $view_data['protocolo'] = $this->session->userdata('protocolo');

        $this->load->model('Atendimentos_model');
        //echo $this->session->userdata('atendimento_id'); exit;
        $view_data['atendimento'] = $this->Atendimentos_model->get_ra_by_id($this->session->userdata('atendimento_id'));

        $this->load->view('portal/dashboard/single.php', $view_data);
    }

    public function docs() {
        $id_client = get_client_user_id();

        if (!$id_client) {
            redirect(base_url('Authentication/logout'));
        }
        //echo $id_client; exit;
        $this->load->model('Clients_model');
        $view_data["tittle"] = 'DOCUMENTOS';
        $view_data["topo"] = 'DOCUMENTOS';

        $view_data["info_perfil"] = $this->Clients_model->get($id_client);
        $view_data['protocolo'] = $this->session->userdata('protocolo');
        $this->load->model('Atendimentos_model');
        //echo $this->session->userdata('atendimento_id'); exit;
        $view_data['atendimento'] = $this->Atendimentos_model->get_ra_by_id($this->session->userdata('atendimento_id'));

        $this->load->view('portal/dashboard/docs.php', $view_data);
    }

    public function financial() {
        $id_client = get_client_user_id();

        if (!$id_client) {
            redirect(base_url('Authentication/logout'));
        }
        //echo $id_client; exit;
        $this->load->model('Clients_model');
        $view_data["tittle"] = 'FICHA FINANCEIRA';
        $view_data["topo"] = 'FICHA FINANCEIRA';

        $view_data["info_perfil"] = $this->Clients_model->get($id_client);
        $view_data['protocolo'] = $this->session->userdata('protocolo');
        $this->load->model('Atendimentos_model');
        //echo $this->session->userdata('atendimento_id'); exit; 00171506200

        $ch = curl_init();

        curl_setopt_array($ch, array(
            CURLOPT_URL => 'http://sistemaweb.unimedmanaus.com.br/sigplus/api/Cooperado/financial',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{ "cpf": "' . $view_data["info_perfil"]->vat . '"}',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json; charset=utf-8'
            ),
        ));

        $response = curl_exec($ch);

        curl_close($ch);
        $view_data['financial'] = json_decode($response, true);
        //print_r($view_data['financial']['general']); exit;

        $view_data['years'] = ['JANEIRO', 'FEVEREIRO', 'MARÇO', 'ABRIL', 'MAIO', 'JUNHO', 'JULHO', 'AGOSTO', 'SETEMBRO', 'OUTUBRO', 'NOVEMBRO', 'DEZEMBRO'];

        $view_data['atendimento'] = $this->Atendimentos_model->get_ra_by_id($this->session->userdata('atendimento_id'));

        $this->load->view('portal/dashboard/financial.php', $view_data);
    }

    public function financial_pdf() {

        if (!get_client_user_id()) {
            redirect(base_url('Authentication/logout'));
        }
        $this->load->model('Clients_model');
        $client = $this->Clients_model->get(get_client_user_id());

        $this->load->library('Pdf');
        $company_logo = get_option('company_logo');
        $data['company_name'] = get_option('companyname');

        $path = base_url() . 'uploads/company/' . $company_logo;
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data_ = file_get_contents($path);

        $data['base64'] = 'data:image/' . $type . ';base64,' . base64_encode($data_);

        $ch = curl_init();

        curl_setopt_array($ch, array(
            CURLOPT_URL => 'http://sistemaweb.unimedmanaus.com.br/sigplus/api/Cooperado/financial',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{ "cpf": "'.$client->vat.'"}', 
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json; charset=utf-8'
            ),
        ));

        $response = curl_exec($ch);

        curl_close($ch);
        $data['financial'] = json_decode($response, true);
        //print_r($view_data['financial']['general']); exit;

        $data['years'] = ['JANEIRO', 'FEVEREIRO', 'MARÇO', 'ABRIL', 'MAIO', 'JUNHO', 'JULHO', 'AGOSTO', 'SETEMBRO', 'OUTUBRO', 'NOVEMBRO', 'DEZEMBRO'];

        $html = $this->load->view('portal/dashboard/financial_pdf.php', $data, true);

        $this->dompdf->loadHtml($html);
        // Definir a orientação para paisagem
        $this->dompdf->setPaper('A4', 'landscape');

        $this->dompdf->render();

        $this->dompdf->stream("FINANCIAL.pdf", array("Attachment" => 0));
    }
}
