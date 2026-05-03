<?php

require_once APPPATH . '/libraries/fpdi/autoload.php';
if (!class_exists('TCPDF')) {
    $_tcpdf_paths = [
        APPPATH . 'libraries/tcpdf/tcpdf.php',
        APPPATH . 'vendor/tecnickcom/tcpdf/tcpdf.php',
    ];
    foreach ($_tcpdf_paths as $_p) {
        if (is_file($_p)) { require_once $_p; break; }
    }
}

use setasign\Fpdi\Tcpdf\Fpdi;
use Dompdf\Options;
use Dompdf\Dompdf;

class Cdc extends AdminController
{

    public function __construct()
    {
        parent::__construct();

        if (!is_logged_in()) {
            access_denied('CDC');
            redirect($_SERVER['HTTP_REFERER']);
        }
        $this->load->model('Cdc_model');
        $this->load->model('Intranet_model');
        $this->load->model('Intranet_general_model');


        $this->load->model('Categorias_campos_model');
    }

    /*
     * 13/09/2022(data que coloquei o comentário)
     * @WannaLuiza
     * Tela de cadastro do documento
     */

    public function index($id = '')
    {

        $view_data["title"] = 'NOVO CDC';

        $this->Intranet_general_model->add_log(["rel_type" => "cdc", "controller" => "Cdc", "function" => "index", "action" => "Registrar Cdc"]);
        if ($id) {

            $view_data["cdc"] = $this->Cdc_model->get_documento($id);
            $view_data["version"] = true;
            //$view_data["fluxos"] = $this->Cdc_model->get_fluxos_by_docid($id);
            //$view_data["ultima_versao"] = $this->Cdc_model->get_ultima_versao($id);
        }

        $this->load->model('Staff_model');
        $staffs = $this->Staff_model->get();

        $view_data["staffs"] = $staffs;
        $this->load->model('Departments_model');
        $view_data['departments'] = $this->Departments_model->get();

        $view_data["id"] = $id;
        $view_data["user"] = get_staff_user_id();

        // $view_data['categorias'] = $this->Cdc_model->get_categorias(false);
        $view_data['categorias'] = $this->Cdc_model->get_categorias_regra(false);

        //   print_r($view_data['categorias_regra']); exit;

        $this->load->view('gestao_corporativa/cdc/index.php', $view_data);
    }

    public function list_()
    {

        $this->Intranet_general_model->add_log(["rel_type" => "cdc", "controller" => "Cdc", "function" => "list_", "action" => "Cdc (Visão Geral)"]);
        $data['responsavel'] = $this->Cdc_model->get_categorias();

        //$data["categorias_documento"] = $documento_categorias;
        $this->load->model('Staff_model');

        $data['staffs'] = $this->Staff_model->get();

        $data['requests'] = $this->Cdc_model->get_requests_for_departmentid();
        $this->load->view('gestao_corporativa/cdc/list.php', $data);
    }

    /*
     * 13/09/2022(data que coloquei o comentário)
     * @WannaLuiza
     * Tela de visualização do fluxo de aprovação de um documento pelo id do mesmo
     */

    public function see()
    {


        $id = $_GET['id'];
        $this->Intranet_general_model->add_log(["rel_type" => "cdc", "controller" => "Cdc", "function" => "see", "action" => "Visualizar Cdc ($id)", "rel_id" => $id]);
        //echo $id; exit;
        $documento = $this->Cdc_model->get_documento($id);
        if ($documento->origin) {
            $view_data['cdc_old'] = $this->Cdc_model->get_documento($documento->origin);
            $view_data['version'] = true;
        }

        $documento->user_cadastro = $this->Intranet_model->get_staff($documento->user_cadastro);
        $processo_atual = $this->Cdc_model->get_fluxo_atual($id);
        $todos = $this->Cdc_model->get_fluxos_by_docid($id);
        $final = $this->Cdc_model->get_fluxo_final($id);

        if ($processo_atual->fluxo_sequencia == $final->fluxo_sequencia && $final->staff_id) {
            $view_data['last'] = true;
        }
        if ($processo_atual->fluxo_sequencia == '1') {
            $view_data['first'] = true;
            //echo 'sjjs'; 
            $view_data['matriz'] = $this->Categorias_campos_model->get_doctos($documento->categoria_id, '', false, false, true);
            // echo 'sjjs'; exit;
        }
        if ($processo_atual->staff_id == get_staff_user_id()) {
            $view_data['current'] = true;
        }


        $view_data['documento'] = $documento;
        $view_data['total'] = $todos;
        $view_data['title'] = $documento->codigo;
        $view_data['processo'] = $processo_atual;
        $view_data['obss'] = $this->Intranet_general_model->get_obs($documento->id, 'cdc');
        $view_data['logs_documento'] = $this->Cdc_model->get_log($documento->id);
        $view_data['links'] = $this->Cdc_model->return_links($documento->id, 'cdc', 'cdc');
        $this->load->view('gestao_corporativa/cdc/see.php', $view_data);
    }

    /*
     * 13/09/2022(data que coloquei o comentário)
     * @WannaLuiza
     * Tela de visualização dos destinatários do documento(editando e excluindo), inclcuindo a visualização de quem está ciente e quem não está
     */

    public function destinatarios($id)
    {

        $view_data['title'] = 'CDC - Destinatários';
        $this->Intranet_general_model->add_log(["rel_type" => "cdc", "controller" => "Cdc", "function" => "destinatarios", "action" => "Visualizar destinatários Cdc ($id)", "rel_id" => $id]);

        $this->load->model('Intranet_general_model');

        $this->load->model('Departments_model');

        $view_data['viewed'] = $this->Intranet_general_model->get_send($id, 'cdc', true);

        $destiny = $this->Intranet_general_model->get_send($id, 'cdc');

        for (
            $i = 0;
            $i < count($destiny);
            $i++
        ) {
            $destiny[$i] = $destiny[$i]['destino'];
        }
        $view_data['destiny'] = $destiny;
        //print_r($view_data['destiny']); exit;

        $view_data['cdc'] = $this->Cdc_model->get_documento($id);

        $view_data['departments'] = $this->Departments_model->get();

        //print_r($view_data); exit;

        $this->load->view('gestao_corporativa/cdc/destinatarios.php', $view_data);
    }

    /*
     * 13/09/2022
     * @WannaLuiza
     * Histórico de versões do documento
     */

    public function historico_versao($id)
    {

        $this->Intranet_general_model->add_log(["rel_type" => "cdc", "controller" => "Cdc", "function" => "historico_versao", "action" => "Visualizar histórico de versões Cdc ($id)", "rel_id" => $id]);
        $documento = $this->Cdc_model->get_documento($id);

        if ($documento->versao_atual == 0) {
            $_d['permission'] = false;
        }

        if ($documento->id_principal) {
            $_d['olds'] = $this->Cdc_model->get_versions($documento->id_principal);
        }


        $_d['cdc'] = $documento;

        $_d['title'] = 'Histórico de versões';
        $this->load->view('gestao_corporativa/cdc/historico_versao.php', $_d);
    }

    /*
     * 13/09/2022(data que coloquei o comentário)
     * @WannaLuiza
     * Função que salva o documento
     */

    public function salvar()
    {

       // print_r($this->input->post()); exit;
        //exit;
        $id_ = $this->input->post('id');
        if ($id_) {
            $data['origin'] = $id_;
            $data['id_principal'] = $id_;
            $cdc_original = $this->Cdc_model->get_documento($id_);
            if ($cdc_original->id_principal != 0) {
                $data['id_principal'] = $cdc_original->id_principal;
            }
        }

        $data['user_cadastro'] = get_staff_user_id();
        $data['data_cadastro'] = date('Y-m-d');
        $data['user_ultima_alteracao'] = get_staff_user_id();
        $data['data_ultima_alteracao'] = date('Y-m-d');

        $data['titulo'] = $this->input->post('titulo');
        $data['validity'] = $this->input->post('validity');
        $data['sequence_view'] = $this->input->post('sequence_view');
        if (!$this->input->post('required')) {
            $data['required'] = 0;
        }
        $data['categoria_id'] = $this->input->post('categoria_id');
        $data['descricao'] = $this->input->post('descricao_doc');
        $data['numero_versao'] = $this->input->post('numero_versao');
        $data['setor_id'] = $this->input->post('department');

        if ($this->input->post('immediately') == 1) {
            $dataPublicacaoStr = $this->input->post('data_publicacao');
            list($dia, $mes, $ano) = explode('/', $dataPublicacaoStr);
            $data['data_publicacao'] = "$ano-$mes-$dia";

            $data['file'] = $this->input->post('file');
        }

        $ultimo = $this->Cdc_model->get_ultimo_all();

        if ($ultimo) {
            $ultimo = $ultimo->sequencial;
            $data['sequencial'] = $ultimo + 1;
        } else {
            $data['sequencial'] = 1;
        }

        $data['empresa_id'] = $this->session->userdata('empresa_id');
        $data['status'] = 0;

        $this->load->model('Departments_model');

        $departamento = $this->Departments_model->get($data['setor_id']); //print_r($departamento); exit;

        $categoria = $this->Categorias_campos_model->get_categoria($this->input->post('categoria_id'), 'cdc'); //print_r($categoria); exit;
        $string = $categoria->codigoKey; 
        $palavras_para_substituir = array('<<<d>>>', '<<<m>>>', '<<<Y>>>', '<<<sequencial>>>', '<<<titulo>>>', '<<<titulo_c>>>', '<<<name>>>', '<<<numero_versao>>>', '<<<desc_categoria>>>'); 
        $substituicoes = array(date('d'), date('m'), date('Y'), $data['sequence_view'], $data['titulo'], $categoria->titulo, $departamento->abreviado, $data['numero_versao'], $categoria->description); 
        $data['codigo'] = str_replace($palavras_para_substituir, $substituicoes, $string);

       // echo $data['codigo']; exit;

        $id = $this->Cdc_model->add($data);
        $this->Intranet_general_model->add_log(["rel_type" => "cdc", "controller" => "Cdc", "function" => "salvar", "action" => "Cdc registado ($id)", "rel_id" => $id]);
        $campos = $this->Categorias_campos_model->get_categoria_campos($data['categoria_id']);
        // print_r($campos); exit;
        if ($id) {

            if ($data['origin']) {
                $request['created'] = $id;
                $this->db->where('linked', $data['origin']);
                $this->db->where('rel_type_to', 'cdc');
                $this->db->update('tbl_intranet_request', $request);
            }

            foreach ($campos as $campo) {

                $campos_value = array(
                    "categoria_id" => $data['categoria_id'],
                    "rel_id" => $id,
                    "campo_id" => $campo['id'],
                    "data_cadastro" => date('Y-m-d H:i:s'),
                    "user_cadastro" => get_staff_user_id(),
                    "empresa_id" => $this->session->userdata('empresa_id'),
                    "rel_type" => 'cdc',
                );

                $value = $this->input->post('campo_' . $campo['name']);

                if ($value) {
                    if (is_array($value)) {
                        $value = implode(', ', $value);
                    }
                }
                //ECHO $value; EXIT;
                $campos_value['value'] = $value;

                $this->load->model('Intranet_general_model');
                $this->Intranet_general_model->add_campo_value($campos_value);
            }

            if ($this->input->post('immediately') == 1) {

                $this->publicar($id);
            }

            $log['rel_id'] = $id;

            $log['action'] = 'DOCUMENTO CADASTRADO';

            $log['desc_salvo'] = $this->input->post('descricao');
            $log['file_salvo'] = str_replace(' ', '_', $_FILES['arquivo']['name']);
            $log['sim_nao'] = 3;

            $add_log = $this->Cdc_model->add_log($log);
            if ($this->input->post('immediately') == 1) {
                $log_pub['rel_id'] = $id;
                $log_pub['sim_nao'] = 4;
                $log_pub['action'] = 'DOCUMENTO PUBLICADO IMEDIATAMENTE';
                $add_log = $this->Cdc_model->add_log($log_pub);
            }
            $dados['user_cadastro'] = get_staff_user_id();
            $dados['data_cadastro'] = date('Y-m-d');
            $dados['user_ultima_alteracao'] = get_staff_user_id();
            $dados['data_ultima_alteracao'] = date('Y-m-d');

            $dados['rel_id'] = $id;
            $dados['rel_type'] = 'cdc';
            $dados['staff_id'] = get_staff_user_id();
            $dados['fluxo_nome'] = 'Criador';
            $dados['fluxo_sequencia'] = 0;
            $dados['status'] = 1;

            $insert = $this->Cdc_model->add_staff_fluxo($dados);
            if ($this->input->post('immediately') != 1) {

                $fluxos = $this->Categorias_campos_model->get_categoria_fluxos($this->input->post('categoria_id'));
                foreach ($fluxos as $f) {

                    $flow_staff = $this->input->post('flow_' . $f['id']);
                    if($flow_staff == ''){
                        $flow_staff = 0;
                    }

                    $dados['status'] = 0;
                    $dados['staff_id'] = $flow_staff;
                    $dados['fluxo_id'] = $f['id'];
                    $dados['fluxo_nome'] = $f['titulo'];
                    $dados['fluxo_sequencia'] = $f['codigo_sequencial'];
                    $insert = $this->Cdc_model->add_staff_fluxo($dados);
                }
            }

            $sends = $this->input->post('departments');

            $array['rel_id'] = $id;
            $array['dt_send'] = date('Y-m-d');
            $array['rel_type'] = 'cdc';

            if ($sends) {

                for (
                    $i = 0;
                    $i < count($sends);
                    $i++
                ) {
                    $array['destino'] = $sends[$i];
                    $this->db->insert("tbl_intranet_send", $array);
                }
            }
            if ($this->input->post('id')) {
                redirect('gestao_corporativa/cdc/see?id=' . $id);
            } else {
                redirect('gestao_corporativa/cdc/list_');
            }
        }
    }

    public function prosseguir_retroceder($id = '')
    {
      //  print_r($this->input->post());
        // exit;
        if (!$id) {
            $id = $_GET['id'];
        }

        $action = $_POST['action'];

        if ($_POST['file']) {
            $data_edit['file'] = $_POST['file'];
            $data_edit['doc'] = $_POST['file_'];
            $data_edit['pdf_principal'] = 1;
            $this->db->where('id', $id);
            $this->db->update('tbl_intranet_cdc', $data_edit);
        }
        $msg = $this->input->post('msg');
        $processo_atual = $this->Cdc_model->get_fluxo_atual($id);
        $documento = $this->Cdc_model->get_documento($id);
        $nome_fluxo = $processo_atual->fluxo_nome;

        $log['rel_id'] = $id;
        $log['rel_type'] = 'cdc';
        //echo $action; exit;
        if ($action == true) {
            //LOG ok PARA APROVAÇÃO
            $log['action'] = "APROVADO ($nome_fluxo)";
            $log['msg'] = "$msg";
            $log['sim_nao'] = 1;
            $add_log = $this->Cdc_model->add_log($log);
            $id_p = $processo_atual->id;
            $dados['status'] = 1;
            $dados['dt_aprovacao'] = date('Y-m-d H:i:s');
            $dados['ip'] = $_SERVER['REMOTE_ADDR'];
            // print_r($processo_atual); exit;

            $campos = $this->Categorias_campos_model->get_categoria_campos($documento->categoria_id, $processo_atual->fluxo_id);
            //print_r($campos); exit;
            foreach ($campos as $campo) {
                $this->db->where('campo_id', $campo['id']);
                $this->db->where('rel_id', $id);
                $this->db->where('rel_type', 'cdc');
                $this->db->delete('tbl_intranet_categorias_campo_values');

                $campos_value = array(
                    "categoria_id" => $documento->categoria_id,
                    "rel_id" => $id,
                    "campo_id" => $campo['id'],
                    "data_cadastro" => date('Y-m-d H:i:s'),
                    "user_cadastro" => get_staff_user_id(),
                    "empresa_id" => $this->session->userdata('empresa_id'),
                    "rel_type" => 'cdc',
                );

                $value = $this->input->post('campo_' . $campo['name']);

                if ($value) {
                    if (is_array($value)) {
                        $value = implode(', ', $value);
                    }
                }

                $campos_value['value'] = $value;
                $this->load->model('Intranet_general_model');
                $this->Intranet_general_model->add_campo_value($campos_value);


            }
        } else {
            $log['action'] = 'REPROVADO (' . $nome_fluxo . ')';
            $log['msg'] = "$msg";
            $log['sim_nao'] = 2;
            $add_log = $this->Cdc_model->add_log($log);
            //LOG ok PARA RERPROVACAO
            $before = $this->Cdc_model->get_fluxo_by_sequencia($processo_atual->fluxo_sequencia - 1, $id);
            $id_p = $before->id;
            $dados['status'] = 0;
           // $dados['dt_aprovacao'] = 'null';
        }

      //  print_r($dados); exit;

        $this->db->where('id', $id_p);
        $this->db->update('tbl_intranet_categorias_loading', $dados);
        $this->Intranet_general_model->add_log(["rel_type" => "cdc", "controller" => "Cdc", "function" => "prosseguir_retroceder", "action" => "Progresso Cdc ($id)", "rel_id" => $id]);
        redirect('gestao_corporativa/cdc/see?id=' . $id);
    }

    public function edit_send($id)
    {
        $this->db->where('rel_id', $id);
        $this->db->where('staff', '0');
        $this->db->where('rel_type', 'cdc');
        $this->db->delete('tbl_intranet_send');

        $array['rel_id'] = $id;
        $array['dt_send'] = date('Y-m-d');
        $array['rel_type'] = 'cdc';

        $deps = $this->input->post('departments');

        foreach ($deps as $dep) {
            $array['destino'] = $dep;
            //print_r($array); exit;
            $this->db->insert("tbl_intranet_send", $array);
        }
        redirect('gestao_corporativa/cdc/destinatarios/' . $id);
    }

    public function retorno_comentarios()
    {

        $id = $this->input->post('id');
        if ($this->input->post()) {
            $dados['rel_id'] = $this->input->post("id");
            $dados['rel_type'] = 'cdc';
            $dados['staff_id'] = get_staff_user_id();
            $dados['obs'] = $this->input->post("texto");
            $dados['data_created'] = date('Y-m-d H:i:s');
            $dados['user_created'] = get_staff_user_id();
            $dados['empresa_id'] = $this->session->userdata('empresa_id');
            $comentario_id = $this->Intranet_general_model->add_obs($dados);
            $this->Intranet_general_model->add_log(["rel_type" => "cdc", "controller" => "Cdc", "function" => "retorno_comentarios", "action" => "Comentário adicionado Cdc ($id)", "rel_id" => $id]);
            //LOG ok PARA COMENTARIO ADICIONADO


            $log['rel_id'] = $this->input->post("id");
            $log['rel_type'] = 'cdc';
            $log['action'] = 'OBSERVAÇÃO ADICIONADA';
            $log['obs_id'] = $comentario_id;
            $add_log = $this->Cdc_model->add_log($log);
            //echo 'snnd'; exit;

            $data['obss'] = $this->Intranet_general_model->get_obs($dados['rel_id'], 'cdc');
            $this->load->view('gestao_corporativa/cdc/retorno_comentarios', $data);
        }
    }

    public function editar_objetivo()
    {
        $data = $this->input->post();

        $editar['descricao'] = $data['objetivo'];
        $this->db->where('id', $data['id']);

        $id = $data['id'];
        $this->db->update('tbl_intranet_cdc', $editar);
        $this->Intranet_general_model->add_log(["rel_type" => "cdc", "controller" => "Cdc", "function" => "editar_objetivo", "action" => "Objetivo editado Cdc ($id)", "rel_id" => $id]);
    }

    public function save_draft()
    {
        //echo $file_; exit;
        $data = $this->input->post();
       // print_r($data); exit;
        if ($data['target']) {

            $data_file['url'] = $data['target']['target_map'];
            $data_file['file'] = $data['target']['file'];
            //echo $data_file['file']; exit;
            $data_file['rel_type'] = 'cdc';
            $data_file['rel_id'] = $data['rel_id'];
            $data_file['description'] = $data['description'];
            $data_file['date_created'] = date('Y-m-d H:i:s');
            $data_file['user_created'] = get_staff_user_id();
            $data_file['empresa_id'] = $this->session->userdata('empresa_id');
            //print_r($data_file); exit;
            $id = $data['rel_id'];
            $this->db->insert('tbl_intranet_files', $data_file);
            $this->Intranet_general_model->add_log(["rel_type" => "cdc", "controller" => "Cdc", "function" => "save_draft", "action" => "Rascunho salvo Cdc ($id)", "rel_id" => $id]);
            echo json_encode(['alert' => 'success', 'msg' => 'SALVO!']);
        } else {
            echo json_encode(['alert' => 'danger', 'msg' => 'ERRO!']);
        }
    }

    public function table()
    {
        $this->app->get_table_data_intranet('cdc');
    }

    public function view_cdc()
    {
        //VERIFICA SE TEM COMUNICADO INTERNO
        // $this->verifica_comunicado_pendente();


        $id = $_GET['id'];
        $this->Intranet_general_model->add_log(["rel_type" => "cdc", "controller" => "Cdc", "function" => "view_cdc", "action" => "View Cdc ($id)", "rel_id" => $id]);

        $ci = $this->Cdc_model->get_documento($id, true);

        $this->load->model('Staff_model');
        $staff = $this->Staff_model->get($ci->user_cadastro);
        $view_data["title"] = $ci->titulo;

        $view_data["content"] = 'cdc/visualizar_comunicado';
        $view_data["without_permission"] = true;

        $view_data["layout"] = 'sidebar-collapse';

        $view_data["exibe_menu_esquerdo"] = 1;

        $view_data["exibe_menu_topo"] = 1;

        $view_data["ci"] = $ci;
        $view_data["staff"] = $staff;

        $this->load->view('gestao_corporativa/intranet/index.php', $view_data);
    }

    public function ciente($id)
    {
        //VERIFICA SE TEM COMUNICADO INTER
        $this->Intranet_general_model->add_log(["rel_type" => "cdc", "controller" => "Cdc", "function" => "ciente", "action" => "Ciente Cdc ($id)", "rel_id" => $id]);
        $array['rel_id'] = $id;
        $array['rel_type'] = 'cdc';

        $array['dt_send'] = date('Y-m-d');
        $array['dt_read'] = date('Y-m-d H:i:s');
        $array['status'] = 1;

        $array['staff'] = 1;

        $array['destino'] = get_staff_user_id();

        $this->db->insert("tbl_intranet_send", $array);
        redirect('gestao_corporativa/intranet');
    }

    public function generic_watermark_test()
    {
        // Caminho para o PDF existente
        $pdfPath = 'assets/intranet/arquivos/cdc_arquivos/cdc/teste_cdc.pdf';

        // Inicializar o FPDI
        $tcpdi = new \setasign\Fpdi\Tcpdf\Fpdi();
        $tcpdi->setPrintHeader(false);
        $tcpdi->setPrintFooter(false);

        // Adicionar uma página
        $tcpdi->AddPage();

        // Importar o PDF existente
        $pageCount = $tcpdi->setSourceFile($pdfPath);
        //echo 'a'; exit;
        $tplIdx = $tcpdi->importPage(1);
        $tcpdi->useTemplate($tplIdx);

        // Adicionar marca d'água com TCPDI
        $tcpdi->SetFont('helvetica', 'B', 50);
        $tcpdi->SetTextColor(255, 192, 203);
        $tcpdi->SetAlpha(0.5);
        $tcpdi->Text(50, 100, 'CAROL');
        $tcpdi->SetAlpha(1);

        // Saída do PDF com marca d'água para o navegador
        $tcpdi->Output();
    }

    public function get_sequence_by_departmentid()
    {
        $departmentid = $this->input->post('departmentid');

        $ultimo = $this->Cdc_model->get_ultimo($departmentid);

        // Retorna uma resposta (pode ser um JSON, HTML, etc.)
        echo $ultimo;
    }

    public function publicar($id = '')
    {
        //print_r($this->input->post()); exit;
        $this->Intranet_general_model->add_log(["rel_type" => "cdc", "controller" => "Cdc", "function" => "publicar", "action" => "Publicar Cdc ($id)", "rel_id" => $id]);
        if ($id) {
            $request = true;
            $id_doc = $id;
        } else {
            $request = false;
            $id_doc = $_GET['id'];
        }

        $documento = $this->Cdc_model->get_documento($id_doc);
        //print_r($documento); exit;
        if ($request == false) {

            //CREATE_PDF
            $file_head = $this->create_pdf($id_doc);

            if ($file_head) {

                $path = 'assets/intranet/arquivos/cdc_arquivos/';
                $response = $this->merge_pdf($path . 'cdc/' . $documento->file, $path . 'head/' . $file_head, $documento->codigo);

                if ($response) {
                    //UPDATE CDC
                    $data['file'] = $response;
                    $data['original_file'] = $response;

                    //MOVE ORIGIN
                    $sourcePath = $path . 'cdc/' . $documento->file;
                    $destinationPath = $path . 'origin/' . $documento->file;
                    if (!is_dir(FCPATH . $path . 'origin/')) {
                        mkdir(FCPATH . $path . 'origin/', 0777, true);
                    }
                    rename($sourcePath, $destinationPath);

                    //DELETE HEAD
                    unlink($path . 'head/' . $file_head);
                }
            }
        }


        //RESPONSE REQUESTS
        if ($documento->origin) {
            $request_['status'] = 1;
            $this->db->where('linked', $documento->origin);
            $this->db->where('rel_type_to', 'cdc');
            // print_r($request_); exit;
            $this->db->update('tbl_intranet_request', $request_);

            $update['linked'] = $documento->id;
            $this->db->where('linked', $documento->origin);
            $this->db->where('rel_type_to', 'cdc');
            $this->db->where('rel_type_from', 'cdc');
            $this->db->update('tbl_intranet_links', $update);
        }

        //UPDATE OLD VERSIONS

        if ($documento->id_principal != 0) {
            $versao['versao_atual'] = 0;
            $where = "id_principal = '" . $documento->id_principal . "' or (id = '" . $documento->id_principal . "' and id_principal = 0)";
            $this->db->where($where);
            $this->db->update('tbl_intranet_cdc', $versao);
        }


        //UPDATE CDC
        $data['publicado'] = 1;
        $data['versao_atual'] = 1;

        if ($request == false) {

            $data['data_publicacao'] = date('Y-m-d');
            //UPDATE PROCESS
            $processo_atual = $this->Cdc_model->get_fluxo_atual($id_doc);
            $id = $processo_atual->id;
            $id_pai = $documento->id_principal;
            $nome_fluxo = $processo_atual->fluxo_nome;
            $dados['status'] = 1;
            $dados['dt_aprovacao'] = date('Y-m-d H:i:s');
            $this->db->where('id', $id);
            $this->db->update('tbl_intranet_categorias_loading', $dados);

            $campos = $this->Categorias_campos_model->get_categoria_campos($documento->categoria_id, $processo_atual->fluxo_id);
            //print_r($campos); exit;
            foreach ($campos as $campo) {

                $campos_value = array(
                    "categoria_id" => $documento->categoria_id,
                    "rel_id" => $id_doc,
                    "campo_id" => $campo['id'],
                    "data_cadastro" => date('Y-m-d H:i:s'),
                    "user_cadastro" => get_staff_user_id(),
                    "empresa_id" => $this->session->userdata('empresa_id'),
                    "rel_type" => 'cdc',
                );

                $value = $this->input->post('campo_' . $campo['name']);

                if ($value) {
                    if (is_array($value)) {
                        $value = implode(', ', $value);
                    }
                }

                $campos_value['value'] = $value;

                $this->load->model('Intranet_general_model');
                $this->Intranet_general_model->add_campo_value($campos_value);
            }



            //LOG OK PARA PUBLICACAO
            $log['rel_id'] = $id_doc;
            $log['rel_type'] = 'cdc';
            $log['sim_nao'] = 3;
            $log['action'] = 'DOCUMENTO PUBLICADO (' . $nome_fluxo . ').';
            $this->Cdc_model->add_log($log);
        }

        $this->db->where('id', $id_doc);
        //print_r($data); exit;
        $this->db->update('tbl_intranet_cdc', $data);
        if ($request == false) {
            redirect('gestao_corporativa/cdc/see?id=' . $id_doc);
        }
    }

    public function merge_pdf($file, $head, $codigo)
    {

        if (file_exists($file) && file_exists($head)) {
            $pdf = new FPDI();

            try {

                $pdf->SetMargins(0, 5, 0, 0);
                $pdf->SetAutoPageBreak(false);
                $pdf->AddPage();

                // Import pages from the first PDF file
                $pageCount = $pdf->setSourceFile($head);
                for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                    $tplIdx = $pdf->importPage($pageNo);
                    $pdf->useTemplate($tplIdx, 0, 0);
                }

                // Import pages from the second PDF file
                $pageCount = $pdf->setSourceFile($file);
                for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                    $tplIdx = $pdf->importPage($pageNo);
                    $pdf->AddPage();
                    $pdf->useTemplate($tplIdx, 0, 0);
                }
                $file_name = $codigo . '_' . uniqid() . '.pdf';

                // Output the merged PDF file
                $pdf->Output(FCPATH . 'assets/intranet/arquivos/cdc_arquivos/cdc/' . $file_name, 'F');
                return $file_name;
            } catch (Exception $e) {
                return false;
            }
        }
    }

    public function create_pdf($id)
    {

        $data["cdc"] = $this->Cdc_model->get_documento($id);
        if ($data["cdc"]->id_principal != 0) {
            $id_ = $data["cdc"]->id_principal;
            $data["versions"] = $this->Cdc_model->get_versions($id_);
        }
        $data["categoria"] = $this->Categorias_campos_model->get_categoria($data["cdc"]->categoria_id, 'cdc');
        $data['campos'] = $this->Categorias_campos_model->get_values($data['cdc']->id, 'cdc');
        $data['cdcs'] = $this->Cdc_model->return_links($data["cdc"]->id, 'cdc', 'cdc');

        $data['todos'] = $this->Cdc_model->get_fluxos_by_docid($id);

       // echo "<pre>";
      // print_r($data); exit;

       


        $data['company_name'] = get_option('companyname');
        $data['base64'] = 'data:image/' . pathinfo(base_url() . 'uploads/company/' . get_option('company_logo'), PATHINFO_EXTENSION) . ';base64,' . base64_encode(file_get_contents(base_url() . 'uploads/company/' . get_option('company_logo')));

        $this->load->library('Pdf');
        $html = $this->load->view('gestao_corporativa/cdc/pdf.php', $data, true); // print_r($html); exit;
        $this->dompdf->loadHtml($html);
        $this->dompdf->render();
        //$this->dompdf->stream("output.pdf", array("Attachment" => 0));
        //exit;

        // Verifica se o diretório existe
        if (!is_dir(FCPATH . 'assets/intranet/arquivos/cdc_arquivos/head/')) {
            // Se não existir, você pode tentar criar o diretório
            if (!mkdir(FCPATH . 'assets/intranet/arquivos/cdc_arquivos/head/', 0777, true)) {
                // Se não puder criar o diretório, pode lançar um erro ou lidar com isso de alguma outra maneira
                return false;
            }
        }
        $file_name = 'HEAD_CDC_' . $data['cdc']->codigo . '_' . uniqid() . '.pdf';

        if (file_put_contents(FCPATH . 'assets/intranet/arquivos/cdc_arquivos/head/' . $file_name, $this->dompdf->output())) {
            return $file_name;
        } else {
            return false;
        }
    }

    public function search()
    {
        $data = $this->input->post();

        $value = $data['value'];
        $data['cdcs'] = $this->Cdc_model->search_cdc_by_title_code($value, $data['linker']);
        $this->load->view('gestao_corporativa/cdc/search_cdc', $data);
    }

    public function select_link()
    {
        $data = $this->input->post();

        $this->Cdc_model->insert_link($data);

        $data['links'] = $this->Cdc_model->return_links($data['linker'], 'cdc', 'cdc');

        $this->load->view('gestao_corporativa/cdc/table_links', $data);
    }

    public function modal()
    {


        $data['rel_type'] = $this->input->post('type');
        if ($this->input->post('slug') === 'add_version') {
            $id = $this->input->post('id');
            $data["link_id"] = $this->input->post('el');
            $data["cdc_"] = $this->input->post('cdc');
            //echo $id; exit;
            $data["cdc"] = $this->Cdc_model->get_documento($id);
            $data["categoria"] = $this->Categorias_campos_model->get_categoria($data["cdc"]->categoria_id, 'cdc');
            $this->load->view('gestao_corporativa/cdc/add_request_version', $data);
        } elseif ($this->input->post('slug') === 'view_request') {
            $this->db->where('id', $this->input->post('id'));
            $data['request'] = $this->db->get('tbl_intranet_request')->row();

            $this->load->view('gestao_corporativa/cdc/view_request', $data);
        }
    }

    public function add_request()
    {
        $data = $this->input->post();

        $request['responsible_response'] = $data['departmentid'];
        $request['linker'] = $data['linker'];
        $request['linked'] = $data['linked'];
        $request['rel_type_from'] = 'links';
        $request['rel_type_to'] = 'cdc';
        $request['description'] = $data['description'];
        $request = $this->Cdc_model->insert_request($request);
        $data['links'] = $this->Cdc_model->return_links($data['cdc'], 'cdc', 'cdc');

        $this->load->view('gestao_corporativa/cdc/table_links', $data);
    }

    public function delete_link()
    {
        $data = $this->input->post();

        $this->db->where('id', $data['link']);
        $this->db->delete(db_prefix() . '_intranet_links');
        //print_r($data);
        $data['links'] = $this->Cdc_model->return_links($data['cdc'], 'cdc', 'cdc');

        $this->load->view('gestao_corporativa/cdc/table_links', $data);
    }

    public function retorna_pessoas_fluxo()
    {

        $id_cdc = $this->input->post('id');
        $info_cdc = $this->Cdc_model->get_cdc($id_cdc);

        $categoria_id = $info_cdc['categoria_id'];
        $data['flows'] = $this->Categorias_campos_model->get_categoria_fluxos($categoria_id); //print_r($data['flows']); exit;

        $data['flows_cdc'] = $this->Cdc_model->get_fluxos_cdc($id_cdc);

        $this->load->model('Staff_model');
        $data['staffs'] = $this->Staff_model->get();

        $this->load->view('gestao_corporativa/cdc/modal_pessoas_fluxos', $data);
    }

    public function edita_pessoas_cdc()
    {

        $info = $this->input->post();

        if ($info['existe_pessoas'] == '1') {


            foreach ($info as $campo => $valor) {
                // Verifica se o nome do campo começa com "flow_id_"
                if (strpos($campo, 'flow_id_') === 0) {

                    // Extrai o ID (tudo depois de "flow_id_")
                    $id_linha = str_replace('flow_id_', '', $campo);

                    // Valor que você quer salvar na coluna
                    $novo_valor = $valor;

                    // Faz o update no banco
                    $this->db->where('id', $id_linha);
                    $this->db->update('tbl_intranet_categorias_loading', [
                        'staff_id' => $novo_valor,
                        'user_ultima_alteracao' => get_staff_user_id(),
                        'data_ultima_alteracao' => date('Y-m-d')
                    ]);
                }
            }

            set_alert('success', 'Alterações salvas com sucesso!'); 

            redirect(base_url("gestao_corporativa/Cdc/list_"));
        }

        /*echo "<pre>";
        print_r($info);
        exit;*/
    }
}
