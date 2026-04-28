<?php
header('Content-Type: text/html; charset=utf-8');
defined('BASEPATH') or exit('No direct script access allowed');

use Dompdf\Options;
use Dompdf\Dompdf; 

class Documentos extends AdminController {

    public function __construct() {
        parent::__construct();
        $this->load->model('Documento_model');
        $this->load->model('Departments_model');
        $this->load->model('Intranet_model');
        $this->load->model('Departments_model');
        $this->load->model('Comunicado_model');
        $this->load->model('Staff_model');
    }

    /*
     * 13/09/2022(data que coloquei o comentário)
     * @WannaLuiza
     * Tela de cadastro do documento
     */

    public function index() {



        $id_principal = $_GET['id'];
        if ($id_principal) {

            $view_data["documento"] = $this->Documento_model->get_documento($id_principal);
            $view_data["categoria"] = $this->Documento_model->get_categoria_by_id($view_data["documento"]->categoria_id);
            //print_r($view_data["categoria"]); exit;
            $view_data["fluxos"] = $this->Documento_model->get_fluxos_by_docid($id_principal);
            $view_data["ultima_versao"] = $this->Documento_model->get_ultima_versao($id_principal);
        }

        $staffs = $this->Staff_model->get();

        $view_data["staffs"] = $staffs;
        $view_data['departments'] = $this->Departments_model->get();

        $documento_categorias = $this->Documento_model->get_categorias();

        $documento_fluxos = $this->Documento_model->get_fluxos();

        $view_data["documento_fluxos"] = $documento_fluxos;
        $view_data["documento_categorias"] = $documento_categorias;

        $view_data["id_principal"] = $id_principal;
        $view_data["user"] = get_staff_user_id();

        $view_data["departments_staffs"] = $this->Intranet_model->get_departamentos_staffs_selecionados();

        $this->load->view('gestao_corporativa/intranet/documento/index.php', $view_data);
    }
    
    public function list_() {

        
        $documento_categorias = $this->Documento_model->get_categorias();

        
        $data['responsavel'] = $this->Documento_model->get_categorias_responsavel();

        $data["categorias_documento"] = $documento_categorias;
        $this->load->model('Staff_model');

        $data['staffs'] = $this->Staff_model->get();
        
        $this->load->view('gestao_corporativa/documento/list.php', $data);
    }

    /*
     * 13/09/2022(data que coloquei o comentário)
     * @WannaLuiza
     * Retorno dos fluxos elancados a categoria pelo id da mesma
     */

    public function retorno_fluxos_categoria() {

        $id = $this->input->post("id");

        $categoria = $this->Documento_model->get_categoria_by_id($id);

        $fluxo = $categoria->fluxos;
        $fluxos_separados = explode(',', $fluxo);
        for ($i = 0; $i < count($fluxos_separados); $i++) {
            $fluxos_separados[$i] = $this->Documento_model->get_fluxo_by_id($fluxos_separados[$i]);
        }
        $data['id'] = $id;
        $data['formato_codigo'] = $categoria->formato_codigo;
        $data['nome_categoria'] = $categoria->titulo;
        $data['fluxos'] = $fluxos_separados;
        $data['staffs'] = $this->Intranet_model->get_staff_all()->result();
        $this->load->view('gestao_corporativa/intranet/documento/retorno_categoria', $data);
    }

    /*
     * 13/09/2022(data que coloquei o comentário)
     * @WannaLuiza
     * Tela de visualização do fluxo de aprovação de um documento pelo id do mesmo
     */

    public function see() {


        $id = $_GET['id'];
        //echo $id; exit;
        $documento = $this->Documento_model->get_documento($id);

        $documento->user_cadastro = $this->Intranet_model->get_staff($documento->user_cadastro);
        //echo 'aqui'; exit;
        $processo_atual = $this->Documento_model->get_fluxo_atual($id);
        $emissor = $this->Documento_model->get_fluxo_emissor($id);
        $todos = $this->Documento_model->get_fluxos_by_docid($id);
        //print_r($todos); 
        //print_r($processo_atual); exit;
        $final = $this->Documento_model->get_fluxo_final($id);

        if ($processo_atual->staff_id == get_staff_user_id()) {

            $processo_atual->apto = 1;
            $proximo = $processo_atual->fluxo_sequencia + 1;
            $anterior = $processo_atual->fluxo_sequencia - 1;

            $anterior = $this->Documento_model->get_fluxo_by_sequencia($anterior, $documento->id);
//echo 'aqui'; exit;
            $proximo = $this->Documento_model->get_fluxo_by_sequencia($proximo, $documento->id);

            //print_r($proximo);
            //print_r($anterior); exit;
            $view_data['proximo'] = $proximo;
            $view_data['anterior'] = $anterior;
        }
        $staffs = $this->Documento_model->get_destinatarios($documento->id);
        //print_r($staffs); exit;
        if ($staffs) {
            for ($i = 0; $i < count($staffs); $i++) {
                $array[$i] = $staffs[$i]['staff_id'];
            }
        }

        $view_data['staffs'] = $array;
        $view_data['staffs_total'] = $staffs;
        $view_data['documento'] = $documento;
        $view_data['emissor'] = $emissor;
        $view_data['final'] = $final;
        $view_data['total'] = $todos;
        $view_data['title'] = $documento->codigo;
        $view_data['processo'] = $processo_atual;
        $view_data['obss'] = $this->Documento_model->get_obs($documento->id);

        $view_data['logs_documento'] = $this->Documento_model->get_log($documento->id);

        $departments_staffs = $this->Intranet_model->get_departamentos_staffs_selecionados();
       //print_r($departments_staffs); echo '<br><br><br>';
       // print_r($view_data['staffs']); 
        //exit;
        $view_data['departments_staffs'] = $departments_staffs;
        $this->load->view('gestao_corporativa/intranet/documento/see.php', $view_data);
    }

    /*
     * 13/09/2022(data que coloquei o comentário)
     * @WannaLuiza
     * Tela de visualização dos destinatários do documento(editando e excluindo), inclcuindo a visualização de quem está ciente e quem não está
     */

    public function destinatarios() {


        // ECHO 'AQUI'; EXIT;
        $id = $_GET['id'];
        $documento = $this->Documento_model->get_documento($id);
        //ECHO 'AQUI'; EXIT;
        $documento->user_cadastro = $this->Intranet_model->get_staff_all($documento->user_cadastro)->row();

        $staffs = $this->Documento_model->get_destinatarios($documento->id);
        if ($staffs) {
            $y = 0;
            $quantidade = count($staffs);
            for ($i = 0; $i < count($staffs); $i++) {
                $array[$i] = $staffs[$i]['staff_id'];
                if ($staffs[$i]['lido'] == 1) {
                    $y++;
                }
                $array_lido[$i] = $staffs[$i];
            }
            $porcentagem = ($y / $quantidade) * 100; // PORCENTAGEM
        }

        //print_r($array); exit;

        $view_data['porcentagem'] = $porcentagem;
        $view_data['staffs_cientes'] = $array_lido;
        $view_data['staffs'] = $array;
        $view_data['documento'] = $documento;
        $departments_staffs = $this->Intranet_model->get_departamentos_staffs_selecionados();
        $view_data['departments_staffs'] = $departments_staffs;
        $this->load->view('gestao_corporativa/intranet/documento/destinatarios.php', $view_data);
    }

    /*
     * 13/09/2022
     * @WannaLuiza
     * Histórico de versões do documento
     */

    public function historico_versao() {


        $id = $_GET['id'];
        //echo $id; exit;
        $documento = $this->Documento_model->get_documento($id);
        if ($documento->id_principal) {
            $view_data['versoes_obsoletas'] = $this->Documento_model->get_doc_with_versions($documento->id_principal);
        } else {
            $view_data['versoes_obsoletas'] = $this->Documento_model->get_doc_with_versions($id);
        }

        $primeiro_do_fluxo = $this->Documento_model->get_fluxo_by_sequencia('1', $documento->id);
        $primeiro_do_fluxo = $primeiro_do_fluxo->staff_id;
        // echo $primeiro_do_fluxo; echo get_staff_user_id(); exit;
        if ($primeiro_do_fluxo == get_staff_user_id() || $documento->responsavel == get_staff_user_id() ) {
            //echo 'aqui'; exit;
            $view_data['pode'] = 1;
        }
        $view_data['documento'] = $documento;

        $view_data['versao_atual'] = $this->Documento_model->get_ultima_versao($id);
        $view_data['historico'] = $this->Documento_model->get_history_changes($id);
        $view_data['historico_cadastro'] = $this->Documento_model->get_log_cadastro($id);

        $departments_staffs = $this->Intranet_model->get_departamentos_staffs_selecionados();
        $view_data['departments_staffs'] = $departments_staffs;
        $this->load->view('gestao_corporativa/intranet/documento/historico_versao.php', $view_data);
    }

    /*
     * 13/09/2022(data que coloquei o comentário)
     * @WannaLuiza
     * Função que salva o documento
     */

    public function salvar() {
        //$id = $this->input->post('id');
        $id_principal = $this->input->post('id_principal');
        $id_principal_doc = $this->Documento_model->get_documento($id_principal);

            $data['user_cadastro'] = get_staff_user_id();
            $data['data_cadastro'] = date('Y-m-d');
            $data['user_ultima_alteracao'] = get_staff_user_id();
            $data['data_ultima_alteracao'] = date('Y-m-d');
        
        //echo $this->input->post('pasta'); exit;
        $config['upload_path'] = './assets/intranet/img/documentos/'; //pastaaaaa

        if ($this->input->post('imediato') == 1) {
            if ($this->input->post('pasta')) {
                $config['upload_path'] = ("./media/Documentos/" . $this->input->post('pasta')); //pastaaaaa
            } else {
                $config['upload_path'] = './media/Documentos/'; //pastaaaaa
            }
        }
        //echo $config['upload_path']; exit;

        $config['allowed_types'] = '*'; //tipoooos, wanna
        //
        //Configurando
        $tipo = pathinfo($_FILES['arquivo']['name']);
        $this->load->library('upload', $config);
        $this->upload->initialize($config);
        if ($_FILES['arquivo']['name']) {
            $this->upload->do_upload('arquivo');
        }
        $caminho = ("/Documentos/" . $this->input->post('pasta'));

        //if (!$id) {

            $data['titulo'] = $this->input->post('titulo');
            $data['pasta_destino'] = $caminho;
            $data['categoria_id'] = $this->input->post('categoria');
            $data['descricao'] = $this->input->post('descricao_doc');
            $data['numero_versao'] = $this->input->post('numero_versao');
            $data['data_avaliacao'] = $this->input->post('data_avaliacao');
            $data['setor_id'] = $this->input->post('dep');

            if ($this->input->post('sequencial')) {
                $data['sequencial'] = $this->input->post('sequencial');
            } else {


                $ultimo = $this->Documento_model->get_ultimo();
                if ($ultimo) {
                    $ultimo = $ultimo->sequencial;
                    $data['sequencial'] = $ultimo + 1;
                } else {
                    $data['sequencial'] = 1;
                }
            }
            if ($id_principal) {
                $data['id_principal'] = $id_principal;
            }
        //} else {
            //$data['pdf_principal'] = 0;
        //}
        $data['conteudo'] = $_POST['descricao'];
        if ($_FILES['arquivo']['name']) {
            $data['file'] = str_replace(' ', '_', $_FILES['arquivo']['name']);
        }
        if($data['file'] == ''){
            $data['file'] = $id_principal_doc->file;
        }
        $data['empresa_id'] = $this->session->userdata('empresa_id');
        $data['status'] = 0;
        //$data['id'] = $id;
        if ($this->input->post('imediato') == 1) {

            if ($id_principal) {
                $atual['versao_atual'] = 0;
                $where = "id = $id_principal or id_principal = $id_principal";
                $this->db->where($where);
                $this->db->update('tbl_intranet_documento', $atual);
            }

            $data['versao_atual'] = 1;
            $data['publicado'] = 1;
            $data['data_publicacao'] = date('Y-m-d');
        }
        if ($this->input->post('pdf_principal') == 1) {
            $data['pdf_principal'] = 1;
        }
        if ($this->input->post('publico') == 1) {
            $data['publico'] = 1;
        }

        $formato_codigo = $this->input->post('formato_codigo');

        if ($this->input->post()) {
            if ($formato_codigo == '1') {
                $categoria = $this->Documento_model->get_categoria_by_id($this->input->post('categoria'));
                if ($this->input->post('dep')) {
                    $dep = $this->Departments_model->get($this->input->post('dep'));
                    $dep = $dep->abreviado;
                }

                $ano = substr(date('Y'), -2);
                $codigo = $categoria->titulo . ' N°' . $data['sequencial'] . '_' . $dep . '_0' . $data['numero_versao'] . '_' . $ano;
                $data['codigo'] = $codigo;
            }
//            /echo $data['codigo']; exit;
            //print_r($data); exit;
            $id = $this->Documento_model->add($data);
            //echo $id; exit;
            if ($id) {

               $log['documento_id'] = $id;
                //if ($this->input->post('id_principal')) {
                    //$//log['action'] = 'NOVA VERSÃO CADASTRADA';
               // } //elseif ($this->input->post('id')) {
                    //$log['action'] = 'DOCUMENTO EDITADO';
                    //$aprovacao['status'] = 1;
                    // $this->db->where('id', $this->input->post('id_editado'));
                    //$this->db->update('tbl_intranet_documento_aprovacao', $aprovacao);
                //} else {
                $log['action'] = 'DOCUMENTO CADASTRADO';
                //}

                //$log['desc_salvo'] = $this->input->post('descricao');
                //$log['file_salvo'] = str_replace(' ', '_', $_FILES['arquivo']['name']);
                $log['sim_nao'] = 3;

                $add_log = $this->Documento_model->add_log($log);
                if ($this->input->post('imediato') == 1) {
                    $log_pub['documento_id'] = $id;
                    $log_pub['desc_salvo'] = $this->input->post('descricao');
                    $log_pub['sim_nao'] = 4;
                    $log_pub['action'] = 'DOCUMENTO PUBLICADO IMEDIATAMENTE';
                    $add_log = $this->Documento_model->add_log($log_pub);
                }
                //LOG ok PARA SALVAR O DOCUMENTO ok
                //if (!$this->input->post('id')) {
                    $dados['user_cadastro'] = get_staff_user_id();
                    $dados['data_cadastro'] = date('Y-m-d');
                    $dados['user_ultima_alteracao'] = get_staff_user_id();
                    $dados['data_ultima_alteracao'] = date('Y-m-d');

                    $dados['doc_id'] = $id;
                    $fluxos = $this->input->post('processo');
                    //print_r($fluxos); exit;
                    $dados['staff_id'] = get_staff_user_id();
                    $dados['fluxo_nome'] = 'Criador';
                    $dados['fluxo_sequencia'] = 0;
                    $dados['status'] = 1;

                    $insert = $this->Documento_model->add_staff_fluxo($dados);

                    if ($fluxos) {
                        for ($i = 0; $i < count($fluxos); $i++) {
                            $campos = explode(':', $fluxos[$i]);
                            $dados['status'] = 0;
                            $dados['staff_id'] = $campos[1];
                            $dados['fluxo_id'] = $campos[0];
                            $dados['fluxo_nome'] = $campos[2];
                            $dados['fluxo_sequencia'] = $campos[3];
                            if ($this->input->post('imediato') == 1) {
                                $dados['status'] = 1;
                            }
                            $insert = $this->Documento_model->add_staff_fluxo($dados);
                        }
                    }
                    //SEND_STAFF_DOC
                    //cadastrar novos
                    $array['documento_id'] = $id;
                    $array['dt_send'] = date('Y-m-d');
                    $array['empresa_id'] = $this->session->userdata('empresa_id');
                    $array['staff_id'] = get_staff_user_id();
                    $array['lido'] = 1;
                    $array['dt_lido'] = date('Y-m-d');
                    $insert = $this->Documento_model->add_destinatario($array);
                   //print_r($this->input->post('for_staffs')); exit;
                    foreach ($this->input->post('for_staffs') as $send):
                        $array['lido'] = 0;
                        unset($array['dt_lido']);
                        //print_r($send); exit;
                        $send = explode('-', $send);
                        //print_r($send); exit;
                        $origem = $send[1];
                        $send = $send[0];
                        //echo $send; echo '<br>'; echo $origem; exit;
                        
                        if ($send != get_staff_user_id()) {
                            $array['staff_id'] = $send;
                            $array['origem'] = $origem;
                           // print_r($array); exit;

                            $insert = $this->Documento_model->add_destinatario($array);
                        }

                    endforeach;
                    //$historico['cadastro'] = 1;
                //}

                //SALAVR HISTÓRICO de alteração
                   $historico['user_created'] = get_staff_user_id();
                   $historico['date_created'] = date('Y-m-d H:i:s');
                    $historico['documento_id'] = $id;
                    if ($id_principal) {
                        $historico['documento_pai'] = $id_principal;
                    } else {
                       $historico['documento_pai'] = $id;
                   }
                    $historico['empresa_id'] = $this->session->userdata('empresa_id');
                    $insert = $this->Documento_model->add_historico($historico);
                


                if ($this->input->post('id')) {
                    redirect('gestao_corporativa/intra/Documentos/see?id=' . $id);
                } else {
                    redirect('gestao_corporativa/intra/documentos/list_');
                }
            }
        }
    }

    /*
     * 28/08/2022
     * Israel Araujo
     * retorna qdo o usuario da ciencia no documento
     */

    public function ciente($id) {
        $dados['lido'] = 1;
        $dados['dt_lido'] = date('Y-m-d H:i:s');
        $this->db->where('id', $id);
        $this->db->where('staff_id', get_staff_user_id());
        $this->db->update('tbl_intranet_documento_send', $dados);
        redirect('admin');
    }

    public function delete_categoria() {
        $id = $_GET['id'];
        $dados['deleted'] = 1;
        $this->db->where('id', $id);

        $this->db->update('tbl_intranet_documento_categoria', $dados);
        redirect('gestao_corporativa/intranet_admin/index/?group=documento_settings');
    }

    public function delete_fluxo() {

        $id = $_GET['id'];
        $dados['deleted'] = 1;
        $this->db->where('id', $id);

        $this->db->update('tbl_intranet_documento_fluxo', $dados);

        redirect('gestao_corporativa/intranet_admin/index/?group=documento_settings');
    }

    public function add_categoria() {
        if ($this->input->post()) {
            $id = $this->input->post('id');
            // print_r($this->input->post()); exit;
            $id = $this->Documento_model->add_categoria($this->input->post(), $id);

            if ($id) {
                redirect('gestao_corporativa/intranet_admin/index/?group=documento_settings');
            }
        }
    }

    public function edit_fluxo() {
        if ($this->input->post()) {
            $dados['titulo'] = $this->input->post('titulo');
            $this->db->where('id', $this->input->post('id'));

            $this->db->update('tbl_intranet_documento_fluxo', $dados);
            redirect('gestao_corporativa/intranet_admin/index/?group=documento_settings');
        }
    }

    public function add_fluxo() {

        $documento_fluxos = $this->Documento_model->get_fluxos();
        if ($documento_fluxos) {
            if ($this->input->post()) {
                $id = $this->input->post('id');
                $id = $this->Documento_model->add_fluxo($this->input->post(), $id);
                if ($id) {
                    redirect('gestao_corporativa/intranet_admin/index/?group=documento_settings');
                }
            }
        } else {
            $id = $this->Documento_model->add_fluxo($this->input->post(), $id);
            if ($id) {
                redirect('gestao_corporativa/intranet_admin/index/?group=documento_settings');
            }
        }
    }

    public function prosseguir_retroceder() {
        $id_doc = $this->input->post('id_doc');
        $msg = $this->input->post('description');
        $nome_staff = $this->input->post('nome_staff');
        $nome_fluxo = $this->input->post('nome_fluxo');

        if ($this->input->post('atual')) {
            //LOG ok PARA APROVAÇÃO
            $log['documento_id'] = $id_doc;
            $log['action'] = "APROVADO PELO(A) $nome_fluxo";
            $log['msg'] = "$msg";
            $log['sim_nao'] = 1;
            $add_log = $this->Documento_model->add_log($log);
            $id = $this->input->post('atual');
            $dados['status'] = 1;
            $dados['dt_aprovacao'] = date('Y-m-d H:i:s');
            $dados['ip'] = $_SERVER['REMOTE_ADDR'];
            $this->db->where('id', $id);
        }
        if ($this->input->post('anterior')) {
            $log['documento_id'] = $id_doc;
            $log['action'] = 'REPROVADO PELO(A) ' . $nome_fluxo . '.';
            $log['msg'] = "$msg";
            $log['sim_nao'] = 2;
            $add_log = $this->Documento_model->add_log($log);
            //LOG ok PARA RERPROVACAO
            $id = $this->input->post('anterior');
            $dados['status'] = 0;
            $dados['dt_aprovacao'] = '';
            $dados['ip'] = $_SERVER['REMOTE_ADDR'];
            $st = "doc_id = $id_doc and fluxo_sequencia != 0";
            $this->db->where($st);
        }

        $this->db->update('tbl_intranet_documento_aprovacao', $dados);
        redirect('gestao_corporativa/intra/Documentos/see?id=' . $id_doc);
    }

    public function sends_edit() {
        //print_r($this->input->post()); exit;

        $id_doc = $this->input->post('id_doc');
        //echo $id_doc.'<br>';
        $staffs = $this->input->post('for_staffs');
        $log['documento_id'] = $id_doc;
        $log['action'] = ('ALTERAÇÃO NOS DESTINATÁRIOS');
        $log['sim_nao'] = 4;
        $add_log = $this->Documento_model->add_log($log);
        //LOG ok PARA RERPROVACAO
        //echo $id_doc; exit;

        $destinatarios_atuais = $this->Documento_model->get_destinatarios($id_doc);
        for ($i = 0; $i < count($destinatarios_atuais); $i++) {
            $destinatarios_atuais[$i] = $destinatarios_atuais[$i]['staff_id'];
        }
        //print_r($staffs); exit;
        for ($i = 0; $i < count($staffs); $i++) {
            $separado = explode('-', $staffs[$i]);

            $destinatarios_editar[$i] = $separado[0];
            $dp_editar[$i] = $separado[1];
        }

        for ($i = 0; $i < count($destinatarios_editar); $i++) {
            //echo $destinatarios_editar[$i];      echo '<br>';      print_r($destinatarios_atuais);
            if (in_array($destinatarios_editar[$i], $destinatarios_atuais)) {
                //executar nnada
            } else {
                if ($dp_editar[$i]) {
                    $origem = $dp_editar[$i];
                } else {
                    $origem = 0;
                }
                $array['documento_id'] = $id_doc;
                $array['dt_send'] = date('Y-m-d');
                $array['staff_id'] = $destinatarios_editar[$i];
                $array['origem'] = $origem;
                $array['empresa_id'] = $this->session->userdata('empresa_id');

                $insert = $this->Documento_model->add_destinatario($array);
            }
        }

        for ($i = 0; $i < count($destinatarios_atuais); $i++) {
            //echo $destinatarios_editar[$i];      echo '<br>';      print_r($destinatarios_atuais);
            if (in_array($destinatarios_atuais[$i], $destinatarios_editar)) {
                //executar nnada
            } else {
                $dados['deleted'] = 1;
                $st = "documento_id = $id_doc and staff_id = $destinatarios_atuais[$i]";
                $this->db->where($st);
                $this->db->update('tbl_intranet_documento_send', $dados);
            }
        }
        if($this->input->post('see')){
            redirect('gestao_corporativa/intra/Documentos/see?id=' . $id_doc);
        } else {
            redirect('gestao_corporativa/intra/Documentos/destinatarios?id=' . $id_doc);
        }
        
    }

    public function publicar() {

        $id = $this->input->post('processo_id');
        $id_doc = $this->input->post('documento_id');
        $id_pai = $this->input->post('id_principal');
        $nome_fluxo = $this->input->post('nome_fluxo');
        $dados['status'] = 1;
        $this->db->where('id', $id);
        $this->db->update('tbl_intranet_documento_aprovacao', $dados);

        $id = $this->input->post('documento_id');
        $versao['versao_atual'] = 0;
        if ($id_pai) {
            $where = "id = '$id_pai' or id_principal = '$id_pai'";
            $this->db->where($where);
            $this->db->update('tbl_intranet_documento', $versao);
        }

        $data['publicado'] = 1;
        $data['versao_atual'] = 1;
        $data['data_publicacao'] = date('Y-m-d');
        $this->db->where('id', $id);
        $this->db->update('tbl_intranet_documento', $data);
        //LOG OK PARA PUBLICACAO

        $log['documento_id'] = $id_doc;
        $log['action'] = 'PUBLICADO PELO(A) ' . $nome_fluxo . '.';
        //$log['msg'] = "$msg - Acresentado por: $nome_staff";
        $add_log = $this->Documento_model->add_log($log);
        redirect('gestao_corporativa/intra/Documentos/see?id=' . $id);
    }

    public function retorno_comentarios() {

        $id = $this->input->post('id');
        if ($this->input->post()) {
            $dados['doc_id'] = $this->input->post("id");
            $dados['staff_id'] = get_staff_user_id();
            $dados['obs'] = $this->input->post("texto");
            $dados['data_created'] = date('Y-m-d H:i:s');
            $dados['user_created'] = get_staff_user_id();
            $dados['empresa_id'] = $this->session->userdata('empresa_id');
            $comentario_id = $this->Documento_model->add_obs($dados);
            //LOG ok PARA COMENTARIO ADICIONADO

            $log['documento_id'] = $this->input->post("id");
            $log['action'] = 'OBSERVAÇÃO ADICIONADA';
            $log['obs_id'] = $comentario_id;
            $add_log = $this->Documento_model->add_log($log);
            $data['obss'] = $this->Documento_model->get_obs($dados['doc_id']);
            $this->load->view('gestao_corporativa/intranet/documento/retorno_comentarios', $data);
        }
    }

    /*
     * 22/09/2022
     * @WannaLuiza
     * Tela de visualização do conteúdo do documento
     */

    public function visualizar_conteudo() {

        $id = $_GET['id'];
        $data['documento'] = $this->Documento_model->get_documento($id);
        $this->load->view('gestao_corporativa/intranet/documento/conteudo.php', $data);
    }

    /*
     * 19/09/2022
     * @WannaLuiza
     * DELETED = 1
     */

    public function delete_doc() {

        $id = $_GET['id'];
        $dados['deleted'] = 1;
        $this->db->where('id', $id);

        $this->db->update('tbl_intranet_documento', $dados);

        $this->db->where('documento_id', $id);

        $this->db->update('tbl_intranet_documento_send', $dados);

        redirect('gestao_corporativa/intranet_admin/index/?group=documento_settings');
    }

    /*
     * 27/09/2022
     * @WannaLuiza
     * Tela de cadastro de cabeçaho
     */

    public function cabecalho($id) {
        //echo 'aqui'; exit;
        //$data['available_merge_fields'] = $this->app_merge_fields->all_cabecalho_rodape();
        $data['categoria'] = $this->Documento_model->get_categoria_by_id($id);
        $data['tipo'] = 1;
        $this->load->view('gestao_corporativa/intranet/documento/cabecalho_rodape.php', $data);
    }

    /*
     * 27/09/2022
     * @WannaLuiza
     * Tela de cadastro de cabeçaho
     */

    public function rodape($id) {
        //echo 'aqui'; exit;
        //$data['available_merge_fields'] = $this->app_merge_fields->all_cabecalho_rodape();
        $data['categoria'] = $this->Documento_model->get_categoria_by_id($id);
        $data['tipo'] = 2;
        $this->load->view('gestao_corporativa/intranet/documento/cabecalho_rodape.php', $data);
    }

    public function pdf($id = '') {
        $data['documento'] = $id;
        //$this->load->view('gestao_corporativa/intranet/documento/pdf', $data);


        
        $this->load->library('Pdf');

        //print_r($data); exit;

        $html = $this->load->view('gestao_corporativa/intranet/documento/pdf.php', $data, true);

        $this->dompdf->loadHtml($html);

        

        

        $this->dompdf->render();

        $this->dompdf->stream("welcome.pdf", array("Attachment"=>0));

        /*$this->load->library('Pdf');
        
        $options = new Options();
        

        $options->set('defaultFont', 'Courier');
        $options->set('isRemoteEnabled', TRUE);
        $options->set('debugKeepTemp', TRUE);
        $options->set('isHtml5ParserEnabled', true);
        $dompdf = new Dompdf($options);
        //ECHO 'AQUI'; exit;
        $html = $this->load->view('gestao_corporativa/intranet/documento/pdf.php', $data, true);
        $dompdf->loadHtml($html);

        $dompdf->render();
        $dompdf->stream("pdf.pdf", array("Attachment" => 0));*/
    }

    /*
     * 27/09/2022
     * @WannaLuiza
     * Insere a coluna cabecalho ou rodape na tabela de categorias
     */

    public function add_cabecalho_rodape() {

        //print_r($this->input->post()); exit;
        $data = $this->input->post();
        $dados[$data["tipo"]] = $this->input->post('descricao', FALSE);
        //print_r($dados); exit;
        $this->db->where('id', $data['id']);

        $this->db->update('tbl_intranet_documento_categoria', $dados);
        redirect('gestao_corporativa/intranet_admin/index/?group=documento_settings');
    }
    
    public function editar_texto()
    {
        $data = $this->input->post();
        
        $editar['conteudo'] = $this->input->post('conteudo', FALSE);
        $this->db->where('id', $data['id']);

        $this->db->update('tbl_intranet_documento', $editar);
        
        
    }
    public function editar_objetivo()
    {
        $data = $this->input->post();
        
        $editar['descricao'] = $data['objetivo'];
        $this->db->where('id', $data['id']);

        $this->db->update('tbl_intranet_documento', $editar);
        
        
    }
    
    public function editar_all($id) {
        //echo 'aqui'; exit;
        //$data['available_merge_fields'] = $this->app_merge_fields->all_cabecalho_rodape();
         $documento = $this->Documento_model->get_documento($id);
        $data['doc'] = $documento;
        $this->load->view('gestao_corporativa/intranet/documento/editar_all.php', $data);
    }

}
