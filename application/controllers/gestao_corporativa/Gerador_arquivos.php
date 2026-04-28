<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Gerador_arquivos extends AdminController
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->model('New_model');
        $this->load->model('Intranet_model');
        $this->load->model('Comunicado_model');
        $this->load->model('Documento_model');
        //$this->load->model('Arquivo_model');
        $this->load->model('Staff_model');
        $this->load->model('Menu_model');
        $this->load->model('Evento_model');
        $this->load->model('Link_destaque_model');
        $this->load->model('Departments_model');
        $this->load->model('prchat_model');
        $this->load->model('tasks_model');
        $this->load->model('newsfeed_model');
        $this->load->model('Formulario_model');
        $this->load->model('Intranet_general_model');
        $this->load->model('Gerador_arquivos_model');
        $this->load->library('upload');
        $this->load->helper(array('form', 'url'));
    }

    public function index()
    {
        $data['title'] = 'Gerador de Arquivos';

        if ($this->input->post()) {
            // $data_de = $this->input->post('data_de');
            $data_de = date("d/m/Y", strtotime($this->input->post('data_de')));
            $data_ate = date("d/m/Y", strtotime($this->input->post('data_ate')));
            $id_tabela = $this->input->post('tabela');
            $regra_parcelamento = $this->input->post('regra_parcelamento');

            if ($id_tabela != '') {
                $info_tabela = $this->Gerador_arquivos_model->get_tabela($id_tabela);
            }
           // print_R($info_tabela); exit;
            $dados =  $this->api_excel($data_de, $data_ate, $info_tabela);  //print_r($dados); exit;
            //print_r($dados); exit;
            $dados = array_map('get_object_vars', $dados);


            //  echo $regra_parcelamento; exit;
            if ($regra_parcelamento == '1') {

                $result = $this->acha_docs_duplicados($dados);
                // print_r($result); exit;

                $nova_conta = [];
                //echo "aqui"; exit;
                for ($res = 0; $res < count($result); $res++) {
                    // print_r($result[1]); exit;
                    for ($j = 0; $j < count($result[$res]); $j++) {
                        //print_r($res); exit;
                        //echo count($res); exit;
                        //$a = $res;

                        $nova_conta[$res] = $dados[$result[$res][$j]];
                        // print_r($nova_conta); exit;
                        $nova_conta[$res]['DT_BASE_PARA_CALCULO'] = [];
                        $nova_conta[$res]['DT_BASE_PARA_CALCULO'][0] = $dados[$result[$res][$j]]['DT_BASE_PARA_CALCULO'];
                        $nova_conta[$res]['MONTANTE_EM_MOEDA_DOC'] = [];
                        $nova_conta[$res]['MONTANTE_EM_MOEDA_DOC'][0] = $dados[$result[$res][$j]]['MONTANTE_EM_MOEDA_DOC'];

                        $nova_conta[$res]['NOTAPARCELADA'] = 1;

                        unset($dados[$result[$res][$j]]);
                        //print_r($nova_conta); exit;
                        // echo (count($result[$res])); exit;
                        $count = count($result[$res]) + 1;
                        for ($l = 1; $l < count($result[$res]); $l++) {
                            // $m = 1;
                            $nova_conta[$res]['DT_BASE_PARA_CALCULO'][$l] = $dados[$result[$res][$l]]['DT_BASE_PARA_CALCULO'];
                            $nova_conta[$res]['MONTANTE_EM_MOEDA_DOC'][$l] = $dados[$result[$res][$l]]['MONTANTE_EM_MOEDA_DOC'];

                            unset($dados[$result[$res][$l]]);
                            //$m++;
                        }
                        break;
                    }
                    // print_r($nova_conta);
                    //exit;
                }

                $dados = array_values($dados);
                foreach ($nova_conta as $conta) {
                    $dados[] = $conta;
                }

                // print_r($dados);
                //  exit;

                //  print_r($nova_conta);
                //  exit;

            } else {

                $result = $this->acha_docs_duplicados($dados);
                // print_r($result);
                // exit;

                $nova_conta = [];
                //echo "aqui"; exit;
                for ($res = 0; $res < count($result); $res++) {
                    // print_r($result[1]); exit;
                    for ($j = 0; $j < count($result[$res]); $j++) {

                        $nova_conta[$res] = $dados[$result[$res][$j]];
                        // print_r($nova_conta); exit;
                        $nova_conta[$res]['DT_BASE_PARA_CALCULO'] = [];
                        $nova_conta[$res]['DT_BASE_PARA_CALCULO'][0] = $dados[$result[$res][$j]]['DT_BASE_PARA_CALCULO'];
                        $nova_conta[$res]['MONTANTE_EM_MOEDA_DOC'] = [];
                        $nova_conta[$res]['MONTANTE_EM_MOEDA_DOC'][0] = $dados[$result[$res][$j]]['MONTANTE_EM_MOEDA_DOC'];

                        $nova_conta[$res]['MONTANTE_IRF'] = [];
                        $nova_conta[$res]['MONTANTE_IRF'][0] = $dados[$result[$res][$j]]['MONTANTE_IRF'];
                        $nova_conta[$res]['COD_CATEGORIA_IRF'] = [];
                        $nova_conta[$res]['COD_CATEGORIA_IRF'][0] = $dados[$result[$res][$j]]['COD_CATEGORIA_IRF'];
                        $nova_conta[$res]['CODIGO_DE_IRF'] = [];
                        $nova_conta[$res]['CODIGO_DE_IRF'][0] = $dados[$result[$res][$j]]['CODIGO_DE_IRF'];

                        $nova_conta[$res]['IMPOSTOS'] = 1;

                        unset($dados[$result[$res][$j]]);
                        //  print_r($nova_conta); exit;
                        // echo (count($result[$res])); exit;
                        $count = count($result[$res]) + 1;
                        for ($l = 1; $l < count($result[$res]); $l++) {
                            // $m = 1;
                            $nova_conta[$res]['DT_BASE_PARA_CALCULO'][$l] = $dados[$result[$res][$l]]['DT_BASE_PARA_CALCULO'];
                            $nova_conta[$res]['MONTANTE_EM_MOEDA_DOC'][$l] = $dados[$result[$res][$l]]['MONTANTE_EM_MOEDA_DOC'];

                            $nova_conta[$res]['MONTANTE_IRF'][$l] = $dados[$result[$res][$l]]['MONTANTE_IRF'];
                            $nova_conta[$res]['COD_CATEGORIA_IRF'][$l] = $dados[$result[$res][$l]]['COD_CATEGORIA_IRF'];
                            $nova_conta[$res]['CODIGO_DE_IRF'][$l] = $dados[$result[$res][$l]]['CODIGO_DE_IRF'];

                            unset($dados[$result[$res][$l]]);
                            //$m++;
                        }
                        break;
                    }
                    // print_r($nova_conta);
                    //exit;
                }

                $dados = array_values($dados);
                foreach ($nova_conta as $conta) {
                    $dados[] = $conta;
                }
            }

            // print_r($dados); exit;


            for ($i = 0; $i < count($dados); $i++) {

                if ($dados[$i]['IMPOSTOS'] != 1) {
                    // print_r($dados[$i]); exit;

                    $montante_irf = explode(";", $dados[$i]['MONTANTE_IRF']); //print_r($montante_irf); exit;
                    $cod_categoria_irf = explode(";", $dados[$i]['COD_CATEGORIA_IRF']);
                    $cod_irf = explode(";", $dados[$i]['CODIGO_DE_IRF']); //print_r($cod_irf);

                    if ($info_tabela['api_imposto'] == '0') {
                        $dados[$i]['MONTANTE_IRF'] = "";
                        $dados[$i]['COD_CATEGORIA_IRF'] = "";
                        $dados[$i]['CODIGO_DE_IRF'] = "";
                        $dados[$i]['MONTANTE_BASE_IRF'] = "";
                    } else {
                        $dados[$i]['MONTANTE_IRF'] = $montante_irf;
                        $dados[$i]['COD_CATEGORIA_IRF'] = $cod_categoria_irf;
                        $dados[$i]['CODIGO_DE_IRF'] = $cod_irf;
                    }
                    //                 print_r($dados); exit;

                }
            }
        }

        $data['lista_tabelas'] = $this->Gerador_arquivos_model->get_tabelas();



        // print_r($dados); exit;

        $data['dados'] = $dados;
        $data['data_de'] = $this->input->post('data_de');
        $data['data_ate'] = $this->input->post('data_ate');
        $data['tabela'] = $tabela;

        $this->load->view('gestao_corporativa/gerador_arquivos/manage.php', $data);
    }

    public function index_bkp()
    {
        $data['title'] = 'Gerador de Arquivos';

        if ($this->input->post()) {
            // $data_de = $this->input->post('data_de');
            $data_de = date("d/m/Y", strtotime($this->input->post('data_de')));
            $data_ate = date("d/m/Y", strtotime($this->input->post('data_ate')));
            $id_tabela = $this->input->post('tabela');

            if ($id_tabela != '') {
                $info_tabela = $this->Gerador_arquivos_model->get_tabela($id_tabela);
            }

            $dados =  $this->api_excel($data_de, $data_ate, $info_tabela);

            /*   $dados = array_map('get_object_vars', $dados);
            print_r($dados); exit;
            $result = $this->acha_docs_duplicados($dados);
          // print_r($result); exit;         
            $nova_conta = [];*/

            for ($i = 0; $i < count($dados); $i++) {

                //echo "aqui"; exit;
                /* 
                foreach($result as $res){
                   // print_r($res); exit;
                  //echo count($res); exit;
                    for($j=0; $j<count($res); $j++){
                        $nova_conta[$j] = $dados[$res[$j]];
                       // print_r($nova_conta); exit;
                        $nova_conta[$j]['DT_BASE_PARA_CALCULO'] = [];
                        $nova_conta[$j]['DT_BASE_PARA_CALCULO'][0] = $dados[$res[$j]]['DT_BASE_PARA_CALCULO']; 
                        $nova_conta[$j]['MONTANTE_EM_MOEDA_DOC'] = [];
                        $nova_conta[$j]['MONTANTE_EM_MOEDA_DOC'][0] = $dados[$res[$j]]['MONTANTE_EM_MOEDA_DOC']; 
                       // print_r($nova_conta); exit;
                       for($l = 1; $l<count($res); $l++){
                        $m=1;
                        $nova_conta[$j]['DT_BASE_PARA_CALCULO'][$m] = $dados[$res[$l]]['DT_BASE_PARA_CALCULO'];
                        $nova_conta[$j]['MONTANTE_EM_MOEDA_DOC'][$m] = $dados[$res[$l]]['MONTANTE_EM_MOEDA_DOC']; 
                        $m ++; 
                       }
                       break;
                    }

                }

                
                print_r($nova_conta); exit; */

                // print_r($dados[$i]); exit;

                $montante_irf = explode(";", $dados[$i]->MONTANTE_IRF); //print_r($montante_irf); exit;
                $cod_categoria_irf = explode(";", $dados[$i]->COD_CATEGORIA_IRF);
                $cod_irf = explode(";", $dados[$i]->CODIGO_DE_IRF); //print_r($cod_irf);

                if ($info_tabela['api_imposto'] == '0') {
                    $dados[$i]->MONTANTE_IRF = "";
                    $dados[$i]->COD_CATEGORIA_IRF = "";
                    $dados[$i]->CODIGO_DE_IRF = "";
                    $dados[$i]->MONTANTE_BASE_IRF = "";
                } else {
                    $dados[$i]->MONTANTE_IRF = $montante_irf;
                    $dados[$i]->COD_CATEGORIA_IRF = $cod_categoria_irf;
                    $dados[$i]->CODIGO_DE_IRF = $cod_irf;
                }
                //                 print_r($dados); exit;

            }
        }

        $data['lista_tabelas'] = $this->Gerador_arquivos_model->get_tabelas();



        // print_r($dados); exit;

        $data['dados'] = $dados;
        $data['data_de'] = $this->input->post('data_de');
        $data['data_ate'] = $this->input->post('data_ate');
        $data['tabela'] = $tabela;

        $this->load->view('gestao_corporativa/gerador_arquivos/manage.php', $data);
    }

    function acha_docs_duplicados($dados)
    {

        //  print_r($dados); exit;
        $j = 0;
        foreach ($dados as $dado) {
            $array[$j] = $dado['TXT_CAB_DOC'];
            $j++;
        }


        $array = array_merge($array);
        //print_r($array); exit;          

        // Passo 1: Conta quantas vezes cada valor aparece
        $contagem = array_count_values($array);

        // Passo 2: Filtra os valores que aparecem mais de uma vez
        $valoresDuplicados = array_filter($contagem, function ($qtd) {
            return $qtd > 1;
        });

        // Passo 3: Pega as chaves (valores repetidos)
        $valoresDuplicados = array_keys($valoresDuplicados);

        // Passo 4: Associa as posições (índices) desses valores
        $listaIndices = [];

        // Para cada valor duplicado, encontra suas posições
        foreach ($valoresDuplicados as $valor) {
            $indices = array_keys($array, $valor); // Pega todos os índices onde o valor aparece
            $listaIndices[] = $indices; // Adiciona ao array final
        }

        return $listaIndices;
    }

    public function tabelas()
    {
        $data['lista_tabelas'] = $this->Gerador_arquivos_model->get_tabelas();
        $this->load->view('gestao_corporativa/gerador_arquivos/manage_tabela.php', $data);
    }

    public function api_excel($data_de, $data_ate, $info_tabela)
    {

        // echo "aqui"; exit;
        //  $data_de = '01/10/2024';
        // $data_ate = '07/10/2024';

        //print_r($info_tabela);

        //echo $data_de . '<br>' . $data_ate . '<br>'. $tabela; exit;

        $ch = curl_init();

        curl_setopt_array(
            $ch,
            array(
                CURLOPT_URL => 'http://189.2.65.2/sigplus/api/Excel',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 60,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => '{"api_imposto": "' . $info_tabela['api_imposto'] . '", "classe": "' . $info_tabela['classe'] . '", "data_de": "' . $data_de . '", "data_ate": "' . $data_ate . '", "conta_razao": "' . $info_tabela['conta_razao'] . '"}',
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json; charset=utf-8'
                ),
            )
        );



        $response = curl_exec($ch);

        $dados = json_decode($response);

        //print_r($dados); exit;

        return $dados;
    }


    public function modal()
    {

        // echo "aqui"; exit;
        if ($this->input->post('slug')  == 'add_tabela') {


            $this->load->view('gestao_corporativa/gerador_arquivos/add_tabela');
        } elseif ($this->input->post('slug') ==  'edit_tabela') {

            $id_tabela = $this->input->post('id_tabela');
            $data['tabela'] = $this->Gerador_arquivos_model->get_tabela($id_tabela);
            $this->load->view('gestao_corporativa/gerador_arquivos/edit_tabela', $data);
        }
    }


    public function salvar_tabela()
    {
        $info = $this->input->post();

        if ($this->input->post('id_tabela')) {

            $id_tabela = $this->input->post('id_tabela');

            if ($info['api_imposto'] == 'true') {
                $info['api_imposto'] = 1;
            } elseif ($info['api_imposto'] == 'false') {
                $info['api_imposto'] = 0;
            }


            $info['date_last_change'] = date('Y-m-d H:i:s');
            $info['user_last_change'] = get_staff_user_id();

            unset($info['id_tabela']);

            $this->db->where('id', $id_tabela);
            if ($this->db->update('tbl_intranet_gerador_arquivos_tabelas', $info)) {

                echo json_encode(array("alert" => "success", "message" => "Atualizado com sucesso !"));
            } else {

                echo json_encode(array("alert" => "danger", "message" => "Erro ao atualizar !"));
            }
        } else {

            if ($info['api_imposto'] == 'true') {
                $info['api_imposto'] = 1;
            } elseif ($info['api_imposto'] == 'false') {
                $info['api_imposto'] = 0;
            }


            $info['date_created'] = date('Y-m-d H:i:s');
            $info['user_created'] = get_staff_user_id();

            //  print_r($info);exit;


            if ($this->db->insert('tbl_intranet_gerador_arquivos_tabelas', $info)) {

                echo json_encode(array("alert" => "success", "message" => "Adicionado com sucesso !"));
            }
        }
    }

    public function delete_tabela()
    {
        $id_tabela = $this->input->post('id_tabela');

        if ($id_tabela) {
            $info['date_deleted'] = date('Y-m-d H:i:s');
            $info['user_deleted'] = get_staff_user_id();
            $info['deleted'] = 1;

            $this->db->where('id', $id_tabela);
            if ($this->db->update('tbl_intranet_gerador_arquivos_tabelas', $info)) {

                echo json_encode(array("alert" => "success", "message" => "Deletado com sucesso !"));
            } else {

                echo json_encode(array("alert" => "danger", "message" => "Erro ao deletar !"));
            }
        }
    }
}
