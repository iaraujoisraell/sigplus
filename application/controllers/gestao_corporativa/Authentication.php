<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Authentication extends ClientsController {

    public function __construct() {
        parent::__construct();
    }

    public function index() {


        $dominio = 'unimedmanaus';

        $this->load->model('Company_model');

        $row = $this->Company_model->get_company($dominio);

        //print_r($row); exit;
        $data['titulo'] = $row->tittle;
        //print_r($row); exit;
        $data['subtitulo'] = $row->subtittle;
        $data['botao'] = $row->signin_button;
        $data['color'] = $row->color_default;
        $data['company_name'] = $row->company;

        $data['links'] = $row->links;

        $data['links'] = explode(';', $data['links']);
        if ($row->banners != '') {
            $data['banners'] = explode(',', $row->banners);

            if (count($data['banners']) > 0) {
                $data['bannerprincipal'] = $data['banners'][0];
            }
        } else {
            $data['banners'] = [];
            $data['bannerprincipal'] = '';
        }
        

        $data['logo'] = get_company_option($dominio, 'company_logo');

        $this->load->model('Categorias_campos_model');

        $data['registro_categorias'] = $this->Categorias_campos_model->get_categorias_without_ra('r.o', $row->empresa_id, 'and anonimo = 1');

        //print_r($data);
        //exit;

        $this->load->view('gestao_corporativa/authentication/index', $data);
    }

    public function login() {

        if ($_POST) {

            $login = $_POST['login'];
            $senha = $_POST['senha'];
          
           

            $url = 'https://sistemaweb.unimedmanaus.com.br/sigplus/api/Login';
            $data = ['login' => $login, 'senha' => $senha];
           // print_r('{ "login": "' . $login . '", "senha": "' . $senha . '" }'); exit;

            $ch = curl_init();

            curl_setopt_array($ch, array(
                CURLOPT_URL => 'https://sistemaweb.unimedmanaus.com.br/sigplus/api/Login',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => '{ "login": "' . $login . '", "senha": "' . $senha . '" }',
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json; charset=utf-8'
                ),
            ));

            $response = curl_exec($ch);
            //print_r($response); exit;
            curl_close($ch);
           //echo $response; exit;
            if ($response == true) {
                //echo 'aqui'; exit;
                header('Location: ' . "https://sigplus.app.br/admin/authentication/valida_login/$login");
            } else {

                header('Location: ' . "https://unimedmanaus.sigplus.app.br/login/");
            }



            //$card_id = $cofre_decode['card_id'];
        }
    }

}
