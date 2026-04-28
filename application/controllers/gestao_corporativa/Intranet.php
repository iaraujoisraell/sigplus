<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Intranet extends AdminController {

    public function __construct() {
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
        $this->load->library('upload');
        $this->load->helper(array('form', 'url'));
    }

    public function index() {
        

        $this->Intranet_general_model->add_log(["rel_type" => "intranet", "controller" => "Intranet", "function" => "index", "action" => "Home Intranet"]);
        if ($this->session->userdata('empresa_id') == 2) {
            $view_data['pendente'] = $this->Intranet_model->doucmento_pendente_aprovacao();
        }
        //VERIFICA SE TEM PENDÊNCIAS
        $this->verifica_pendencias_obrigatorias();
        
        //select individual 
        $banners = $this->New_model->get_banner();
        
        //$popups = $this->New_model->get_popup($this->session->userdata('empresa_id'))->result();

        $noticias = $this->New_model->get_noticia();
       
        //select eventos para todos
        $date = $this->New_model->get_datas($this->session->userdata('empresa_id'))->result();
//echo 'hdw'; exit;
        //select eventos criados pelo usuário
        //select todos links
        //$links = $this->Link_model->get_recebidos(get_staff_user_id())->result();
        //for ($i = 0; $i < count($links); $i++) {
        //$links[$i] = $this->Link_model->get_link($links[$i]->link_id);
        // }
        //select aniversariantes
        $bdays = $this->Intranet_model->get_bday()->result();
       
        $staffs = $this->Staff_model->get();
        
        for ($i = 0; $i < count($bdays); $i++) {


           // setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
           // date_default_timezone_set('America/Sao_Paulo');
            $hoje = date('Y-m-d');
            $hoje = substr($hoje, 5, 5);

            $data = substr($bdays[$i]->data_nascimento, 5, 5);
            if ($data == $hoje) {
                $bdays[$i]->hoje = 'ok';
                $variavelaleatoria = 'ok';
            }
        }

        // CATEGORIAS DOS LINKS DE DESTAQUES
        //$view_data['categorias_destaques'] = $this->Link_destaque_model->get_categorias_links_by_user_id()->result();

        $view_data['staff'] = $staffs;
        $view_data['departments'] = $this->Departments_model->get();

        $view_data["popup"] = $popups;
        $view_data["banner"] = $banners;

        //print_r($noticias); exit;
        $view_data["noticia"] = $noticias;
        $view_data["w"] = $variavelaleatoria;

        $view_data["date"] = $date;
        $view_data["staffs"] = $staffs;

        // $view_data["arquivos_recebidos"] = $arquivos_recebidos;
        $view_data["links"] = $links;
        $view_data["bdays"] = $bdays;

        /*
         * CARDS
         */
        // COMUNICADOS INTERNOS
        $comunicados_recebidos = $this->Comunicado_model->get_recebidos();
        $view_data["comunicados_recebidos"] = $comunicados_recebidos;

        // GESTÃO DE DOCUMENTOS

        $documentos_recebidos = $this->Documento_model->get_documentos_recebidos();
        $view_data["documentos_recebidos"] = $documentos_recebidos;

        // TAREFAS
        $tarefas = $this->tasks_model->get_user_tasks_assigned();
        $view_data["tarefas"] = $tarefas;

        // FORMULARIOS  
        $forms = $this->Formulario_model->get_formulario_by_user_id();
        $view_data["forms"] = $forms;

        // FIM CARD

        $msg_n_lidas = $this->prchat_model->getUnread();
        $render_msg = $msg_n_lidas['sender_id_5'];
        $cont_msg_n_lidas = $render_msg['count_messages'];
        $view_data["cont_msg_n_lidas"] = $cont_msg_n_lidas;
        $view_data["title"] = 'INTRANET';
        $view_data["content"] = 'home';

        // layout

        $view_data["layout"] = 'sidebar-collapse';
        $view_data["exibe_menu_esquerdo"] = 1;
        $view_data["exibe_menu_topo"] = 1;

        $posts = $this->newsfeed_model->load_newsfeed($this->input->post('page'), $__post_id);
        $view_data["posts"] = $posts;
        $this->load->view('gestao_corporativa/intranet/index.php', $view_data);
        //$_SESSION['intranet'] = true;
    }


   public function teste_view()
    {
        $this->Intranet_general_model->add_log([
            "rel_type"   => "intranet",
            "controller" => "Intranet",
            "function"   => "teste_view",
            "action"     => "Home Intranet Novo Layout"
        ]);

        $view_data = [];
        $variavelaleatoria = '';
        $popups = [];
        $links  = [];
        $__post_id = null;

        if ($this->session->userdata('empresa_id') == 2) {
            $view_data['pendente'] = $this->Intranet_model->doucmento_pendente_aprovacao();
        }

        $this->verifica_pendencias_obrigatorias();

        $banners  = $this->New_model->get_banner();
        $noticias = $this->New_model->get_noticia();
        $date     = $this->New_model->get_datas($this->session->userdata('empresa_id'))->result();
        $bdays    = $this->Intranet_model->get_bday()->result();
        $staffs   = $this->Staff_model->get();

        for ($i = 0; $i < count($bdays); $i++) {
            $hoje = date('m-d');
            $data = substr($bdays[$i]->data_nascimento, 5, 5);

            if ($data == $hoje) {
                $bdays[$i]->hoje = 'ok';
                $variavelaleatoria = 'ok';
            }
        }

        $view_data['staff']       = $staffs;
        $view_data['staffs']      = $staffs;
        $view_data['departments'] = $this->Departments_model->get();

        $view_data['popup']   = $popups;
        $view_data['banner']  = $banners;
        $view_data['noticia'] = $noticias;
        $view_data['w']       = $variavelaleatoria;
        $view_data['date']    = $date;
        $view_data['links']   = $links;
        $view_data['bdays']   = $bdays;

        $view_data['comunicados_recebidos'] = $this->Comunicado_model->get_recebidos();
        $view_data['documentos_recebidos']  = $this->Documento_model->get_documentos_recebidos();
        $view_data['tarefas']               = $this->tasks_model->get_user_tasks_assigned();
        $view_data['forms']                 = $this->Formulario_model->get_formulario_by_user_id();

        $msg_n_lidas = $this->prchat_model->getUnread();
        $render_msg = isset($msg_n_lidas['sender_id_5']) ? $msg_n_lidas['sender_id_5'] : ['count_messages' => 0];
        $view_data['cont_msg_n_lidas'] = isset($render_msg['count_messages']) ? (int)$render_msg['count_messages'] : 0;

        $view_data['title'] = 'INTRANET';
        $view_data['layout'] = 'sidebar-collapse';
        $view_data['exibe_menu_esquerdo'] = 1;
        $view_data['exibe_menu_topo'] = 1;

        $posts = $this->newsfeed_model->load_newsfeed($this->input->post('page'), $__post_id);
        $view_data['posts'] = $posts;

        $this->load->view('gestao_corporativa/intranet/linkdnl/index.php', $view_data);
    }
    /*
     * 28/08/2022
     * @israel Araujo
     * Funcao q junta todas as funções q precisam ser validadas
     */

    public function verifica_pendencias_obrigatorias() {
        // comunicados pendentes
        $this->verifica_comunicado_pendente();

        // documentos pendentes
        if ($this->session->userdata('empresa_id') == 2) {
            $this->verifica_documentos_pendente();
        } else {
            $this->verifica_cdc_pendente();
        }
    }

    // COMUNICADOS PENDENTES
    public function verifica_comunicado_pendente() {
        $comunicados_pendentes = $this->Comunicado_model->get_comunicado_nao_lido();
        for ($comunic = 0; $comunic < count($comunicados_pendentes); $comunic++) {
            if ($comunicados_pendentes[$comunic]->status == 0) {
                redirect('gestao_corporativa/intranet/comunicados_pendentes');
                exit;
            }

            $comunicados_pendentes[$comunic] = $this->Comunicado_model->get_comunicado($comunicados_pendentes[$comunic]->ci_id)->row();
            $comunicados_pendentes[$comunic]->user = $this->Intranet_model->get_one($comunicados_pendentes[$comunic]->user_create)->row();
        }
    }

    // DOCUMENTOS PENDENTES
    public function verifica_documentos_pendente() {

        $documentos_pendentes = $this->Documento_model->get_documentos_nao_lido();

        if (count($documentos_pendentes) > 0) {

            redirect('gestao_corporativa/intranet/documentos_pendentes');
        }
    }

    public function verifica_cdc_pendente() {

        $this->load->model('Cdc_model');
        $cdc_pendentes = $this->Cdc_model->get_cdc_department();

        if (count($cdc_pendentes) > 0) {

            redirect('gestao_corporativa/intranet/cdc_pendentes');
        }
    }

    /*     * ************************************************************************* */

    public function contatos() {


//VERIFICA SE TEM COMUNICADO INTERNO

        $this->verifica_comunicado_pendente();

        $view_data['departamentos'] = $this->Intranet_model->get_departamentos();

        $view_data['staffs'] = $this->Staff_model->get();
        //print_r($view_data['staffs']); exit;

        $view_data["title"] = 'INTRANET - Contatos';

        $view_data["content"] = 'contatos';

// layout

        $view_data["layout"] = 'sidebar-collapse';

        $view_data["exibe_menu_esquerdo"] = 1;

        $view_data["exibe_menu_topo"] = 1;

//sidebar-collapse (menu encolhido)
// layout-fixed menu normal

        $this->load->view('gestao_corporativa/intranet/index.php', $view_data);

//$this->load->view('gestao_corporativa/intranet/index.php', $view_data);
    }

    public function meu_perfil() {

//echo 'oie';exit;

        $staff_id = get_staff_user_id();

        $view_data['staff'] = $this->Intranet_model->get_staff($staff_id);

        if(is_numeric($view_data['staff']->terceiro_id) && $view_data['staff']->terceiro_id > 0){
            $view_data['make_assignature'] = true;
        }

        $view_data['projetos'] = array();

        $view_data["title"] = 'INTRANET - Perfil';

        $view_data["content"] = 'profile';

// layout

        $view_data["layout"] = 'sidebar-collapse';

        $view_data["exibe_menu_esquerdo"] = 1;

        $view_data["exibe_menu_topo"] = 1;

//sidebar-collapse (menu encolhido)
// layout-fixed menu normal
        //print_r($view_data['staff']); exit;
        //$cpf = $view_data['staff']->vat;
        $cpf = $view_data['staff']->cpf;

        // echo ; exit;
        // CHAMA A API SMS		   
        $ch = curl_init();

        curl_setopt_array($ch, array(
            CURLOPT_URL => 'http://sistemaweb.unimedmanaus.com.br/sigplus/api/Dados_rh3',
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
        curl_close($ch);
        $view_data['info'] = $result;
        //print_r($result); exit;
        $this->load->view('gestao_corporativa/intranet/index.php', $view_data);

// $this->load->view('gestao_corporativa/intranet/profile_2');
    }

    public function recebe_imagem($type = '') {




        //$file = $_FILES['imageUpload']['name'];
        //echo $file;
        //exit;

        $pathName = './uploads/staff_profile_images/' . get_staff_user_id();

        if (!file_exists($pathName)) {
            mkdir($pathName); //aqui ele irá criar a pasta
        }

        if ($type == 'bg') {
            $nome_arquivo = "bg_" . get_staff_first_name() . get_staff_user_id(); //pasta
            $name = 'background';
        }

        if ($type == 'profile') {
            $nome_arquivo = "small_" . get_staff_first_name() . get_staff_user_id(); //pasta
            $name = 'imageUpload';
        }
        //echo $nome_arquivo; exit;



        if (isset($_FILES[$name]) && $_FILES[$name]['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES[$name];

            // Extrai a extensão original do arquivo
            $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);

            // Gera um novo nome de arquivo único com a extensão original
            $new_filename = $nome_arquivo . '.' . $file_extension;

            // Define o caminho completo para o destino do arquivo enviado
            $destination = $pathName . '/' . $new_filename;
            //echo $destination; exit;
            // Move o arquivo enviado para o destino com o novo nome de arquivo
            if (move_uploaded_file($file["tmp_name"], $destination)) {
                // Upload do arquivo bem-sucedido
                $destination = $new_filename;
            }
        }

        if ($type == 'bg') {
            $dados['background_image'] = get_staff_first_name() . get_staff_user_id() . '.' . $file_extension;
        }

        if ($type == 'profile') {
            $dados['profile_image'] = get_staff_first_name() . get_staff_user_id() . '.' . $file_extension;
        }
        //print_r($dados); exit;
        $this->db->where('staffid', get_staff_user_id());

        if ($this->db->update('tblstaff', $dados)) {

            redirect('gestao_corporativa/intranet/meu_perfil');
        }
    }

    public function salvar_configuracoes() {



        $staffid = $this->input->post('staffid');

        $data['formacao_academica'] = $this->input->post('formacao_acad');

        $data['descricao'] = $this->input->post('descricao');

        $data['phonenumber'] = $this->input->post('num_pessoal');

        $data['num_comercial'] = $this->input->post('num_comercial');

        $data['num_ramal'] = $this->input->post('num_ramal');

        $data['facebook'] = $this->input->post('facebook');

        $data['instagram'] = $this->input->post('instagram');

        $data['linkedin'] = $this->input->post('linkedin');

        $retorno = $this->Intranet_model->update_perfil($data, $staffid);

        if ($retorno) {

            redirect('gestao_corporativa/intranet/meu_perfil');

            exit;
        }
    }

    public function retorno_staffs() {

        $texto = $this->input->post('texto');

        $data['staffs'] = $this->Intranet_model->retorno_staff($texto);

        $this->load->view('gestao_corporativa/intranet/retorno_staff.php', $data);
    }

    public function retorno_departamentos() {
        $id = $this->input->post('id');
        $data['staffs'] = $this->Intranet_model->retorno_departamentos($id);
        //print_r($data['departamentos']); exit;
        $this->load->view('gestao_corporativa/intranet/retorno_staff.php', $data);
    }

    /*     * ************************C I ******************************************* */

    /*
     * 24/08/2022
     * ISRAEL
     * COMUNICADOS INTERNOS PENDENTES
     */

    public function comunicados_pendentes() {

        $comunicados_recebidos = $this->Comunicado_model->get_comunicado_nao_lido();
        //print_r($comunicados_recebidos); exit;
        $view_data["content"] = 'comunicado/comunicados_pendentes';
        // layout
        $view_data["layout"] = 'sidebar-collapse'; //layout-top-nav
        $view_data["exibe_menu_esquerdo"] = 0;
        $view_data["exibe_menu_topo"] = 0;
        $view_data["comunicados"] = $comunicados_recebidos;
        $view_data["without_permission"] = true;
        $this->load->view('gestao_corporativa/intranet/index.php', $view_data);
    }

    public function cdc_pendentes() {

        $this->load->model('Cdc_model');
        $comunicados_recebidos = $this->Cdc_model->get_cdc_department();
        //print_r($comunicados_recebidos); exit;
        $view_data["content"] = 'cdc/cdc_pendentes';
        // layout
        $view_data["layout"] = 'sidebar-collapse'; //layout-top-nav
        $view_data["exibe_menu_esquerdo"] = 0;
        $view_data["exibe_menu_topo"] = 0;
        $view_data["comunicados"] = $comunicados_recebidos;
        $view_data["without_permission"] = true;
        $this->load->view('gestao_corporativa/intranet/index.php', $view_data);
    }

    /*     * ******************** FIM COMUNICADOS******************************* */

    /*     * ************************************************************************
     * ***************** D O C U M E N T O S ********************************** 
     * ************************************************************************* */


    /* 28/08/2022
     * @Israel Araujo
     * lista os comunicados internos já lidos
     */

    public function documentos() {


//VERIFICA SE TEM PENDÊNCIAS

        $this->verifica_pendencias_obrigatorias();

        close_setup_menu();

        $this->load->model('Documento_model');

        $documento_categorias = $this->Documento_model->get_categorias();

//

        if ($documento_categorias) {

            for ($y = 0; $y < count($documento_categorias); $y++) {

                $fluxos_titulo = [];

                $fluxos = explode(',', $documento_categorias[$y]['fluxos']);

                for ($i = 0; $i < count($fluxos); $i++) {


                    $fluxos[$i] = $this->Documento_model->get_fluxo_by_id($fluxos[$i]);

//print_r($fluxos[$i]);


                    $fluxos_titulo[$i] = $fluxos[$i]->titulo;

                    $fluxos[$i] = json_decode(json_encode($fluxos[$i]), true);

                    $fluxos[$i] = $fluxos[$i]['id'];
                }

                $documento_categorias[$y]['fluxos'] = implode(', ', $fluxos_titulo);

                $documento_categorias[$y]['fluxos_array'] = $fluxos;
            }
        }



        $documento_fluxos = $this->Documento_model->get_fluxos();
        $data['responsavel'] = $this->Documento_model->get_categorias_responsavel();

        $data["categorias_documento"] = $documento_categorias;

        $data["documento_fluxos"] = $documento_fluxos;

        $data["fluxos"] = $documento_fluxos;

        $fluxos = $documento_fluxos;

        for ($i = 0; $i < count($fluxos); $i++) {


            $data["fluxos"][$i] = json_decode(json_encode($fluxos[$i]), true);
        }


        $this->load->model('Staff_model');

        $data['staffs'] = $this->Staff_model->get();

        $this->load->view('gestao_corporativa/intranet/documento/documentos.php', $data);
    }

    /*
     * 28/08/2022
     * @Israel Araujo
     * Lista os Documentos pendentes de Ciência
     */

    public function documentos_pendentes() {

        $documentos_recebidos = $this->Documento_model->get_documentos_nao_lido();
        //print_r($comunicados_recebidos); exit;
        $view_data["content"] = 'documento/lista_documentos_pendentes';
        // layout
        $view_data["layout"] = 'sidebar-collapse'; //layout-top-nav
        $view_data["exibe_menu_esquerdo"] = 0;
        $view_data["exibe_menu_topo"] = 0;
        $view_data["documentos"] = $documentos_recebidos;
        $this->load->view('gestao_corporativa/intranet/index.php', $view_data);
    }

    public function visualizar_documento($id = '') {
        //VERIFICA SE TEM COMUNICADO INTERNO
        // $this->verifica_comunicado_pendente();
        //$id = $_GET['id'];

        $documento = $this->Documento_model->get_documento_by_id($id);

        $staff_id = $documento->user_cadastro;
        $staff = $this->Intranet_model->get_one($staff_id)->row();

        $view_data["content"] = 'documento/visualizar_documento';
        // layout
        $view_data["layout"] = 'sidebar-collapse';
        $view_data["exibe_menu_esquerdo"] = 0;
        $view_data["exibe_menu_topo"] = 0;

        $view_data["documento"] = $documento;
        $view_data["staff"] = $staff;
        $this->load->view('gestao_corporativa/intranet/index.php', $view_data);
    }

    public function assinatura_step_do() {
        if (empty($_POST['matricula']) or empty($_POST['nome']) or empty($_POST['cargo']) or empty($_POST['telefone']) or empty($_POST['email'])) {
            echo "<b style='color:red'>Não foi possível gerar a assinatura. Todos os campos devem ser preenchidos.</b>";
        } else {
            //echo 'aqui'; exit;
            echo $this->cria_assinatura_do_funcionario(strtolower($_POST['matricula']), $_POST['nome'], $_POST['cargo'], $_POST['setor'], $_POST['email'], $_POST['telefone'], $_POST['site']);
        }
    }

    public function ucwords_improved($s, $e = array()) {
        return join(' ',
                array_map(
                        create_function(
                                '$s',
                                'return (!in_array($s, ' . var_export($e, true) . ')) ? ucfirst($s) : $s;'
                        ),
                        explode(
                                ' ',
                                strtolower($s)
                        )
                )
        );
    }

    public function customErrorHandler($errno, $errstr, $errfile, $errline) {
        // Registra os erros em uma variável interna
        $this->addCustomError("Erro [$errno]: $errstr in $errfile on line $errline");
    }

    public function addCustomError($error) {
        // Adiciona os erros à lista de erros
        $this->customErrors[] = $error;
    }

    public function cria_assinatura_do_funcionario($_matricula, $_nome, $_cargo, $_setor, $_email, $_telefone, $_site) {


        //$_nome = $this->ucwords_improved($_nome, array("da", "de", "e", "das", "dos", "do", "em"));
        //$_cargo = ucwords_improved($_cargo, array("da", "de", "e", "das", "dos", "do", "em"));
        //$_setor = ucwords_improved($_setor, array("da", "de", "e", "das", "dos", "do", "em"));
        //set_error_handler([$this, 'customErrorHandler']);

        $_matricula = str_replace(" ", "", $_matricula);

        $im = @imagecreatefromjpeg("assets/intranet/assinatura_support/nova_assinatura.jpg") or die("Cannot Initialize new GD image stream");

        $text_color = imagecolorallocate($im, 255, 255, 255);
        $tamanho = 20;
        $fonte = "assets/intranet/assinatura_support/UnimedSans-Bold.ttf";

        $angulo = 0;
        $x = 50;
        $y = 110;
        imagettftext($im, $tamanho, $angulo, $x, $y, $text_color, $fonte, $_nome);

        $fonte = "assets/intranet/assinatura_support/UnimedSans-Regular.ttf";
        $y = 150;
        imagettftext($im, $tamanho, $angulo, $x, $y, $text_color, $fonte, $_cargo);
        $error = error_get_last();

        if (empty($_setor)) {
            $y_email = 185;
            $y_site = 220;
            $y_r = 255;
            $y_tel = 290;
        } else {
            $y_email = 220;
            $y_site = 255;
            $y_r = 290;
            $y_tel = 290;

            $y = 185;
            imagettftext($im, $tamanho, $angulo, $x, $y, $text_color, $fonte, $_setor);
        }

        //$y = 146; //127;
        imagettftext($im, $tamanho, $angulo, $x, $y_email, $text_color, $fonte, strtolower($_email));

        //$y = 159; //140;
        imagettftext($im, $tamanho, $angulo, $x, $y_site, $text_color, $fonte, $_site);

        //$y = 173; //160;
        $text_color = imagecolorallocate($im, 186, 208, 48);
        imagettftext($im, $tamanho, $angulo, $x, $y_r, $text_color, $fonte, "t");

        //$y = 173; //160;
        $x = 70;
        $text_color = imagecolorallocate($im, 255, 255, 255);
        imagettftext($im, $tamanho, $angulo, $x, $y_tel, $text_color, $fonte, $_telefone);

        $_nome_arquivo = 'assets/intranet/assinatura_support/ass_nova/' . $_matricula . '.jpg';
        if (is_file('assets/intranet/assinatura_support/ass_nova/' . $_matricula . '.jpg')) {
            rename("/var/www/html/unimanaus/ass_nova/" . $$_nome_arquivo, $_nome_arquivo);
            $update = true;
        }


        $result = true;
        if (!imagejpeg($im, $_nome_arquivo, 100)) {
            $result = false;
        }




        imagedestroy($im);
        //restore_error_handler();
        // Exibe os erros
        //echo '<h1>Erros:</h1>';
        //echo '<ul>';
        //foreach ($this->customErrors as $error) {
        //echo '<li>' . $error . '</li>';
        //}
        //echo '</ul>';
        //echo 'skad';
        //exit;
        //$_nome_arquivo = "http://www.unimedmanaus.com.br/ass_nova/" . $_matricula . ".jpg";
        //echo 'aquif';
        //exit;
        if ($result == true) {
            $alert = "success";
            if ($update == true) {
                $msg = 'Atualizado com sucesso!';
            } else {
                $msg = 'Assinatura criada com sucesso! Siga o passso a passo.';
            }
        } else {
            $alert = "error";
            $msg = 'ERRO! Entre em contato pelo ramal 2165';
        }

        $_retorno = "<script> toastr.$alert('$msg');</script>";
        if ($result == true) {
            $timestamp = time();

            $_retorno .= "<div class='col-md-12' style='text-align: center;'> "
                    . '<button class="btn btn-success" data-clipboard-action="copy" data-clipboard-target="#copycurrent">Clique aqui para selecionar e copiar as imagens de sua assinatura e siga os passos logo abaixo.</button><br/>'
                    . "<div>"
                    . "<p> </br>1. No Outlook, clique no ícone 'Configurações' no canto superior direito:<br/> 2. Digite 'assinatura' no campo de busca e clique em 'Assinatura de email':<br/> 3. Cole as imagens geradas por essa ferramenta na caixa de assinatura (Substituir as imagens se tiver) e clique em 'OK':<br/> 4. Teste a assinatura clicando em 'Novo', para criar um novo email e verificar se consta a nova assinatura no fim do email:<br/> </p>"
                    . "</div>"
                    . '<div id="copycurrent" >'
                    . '<img alt="Assinatura"  style="max-width: 50%;" src="' . base_url('assets/intranet/assinatura_support/ass_nova/' . $_matricula . '.jpg') . '?' . rand(0, 99999) . '"><br/>'
                    //. '<img alt="Assinatura"  style="height: 250px; max-width: 100%;" src="' . base_url('assets/intranet/assinatura_support/ass_nova/' . $_matricula . '.jpg') . '?' . rand(0, 99999) . '"><br/>'
                    . '</div>'
                    . '</div>';
            if ($update == false) {
                $_retorno .= "<script> toastr.warning('Siga os passos.');</script>";
            }
            //$_retorno .= '<img alt="MKT - Unimed Manaus" width="604px" height="180px" border="0" hspace="0" src="http://www.unimedmanaus.com.br/asspub.php" vspace="15"></div><br/><br/><br/>';
        }


        return $_retorno;
    }

    // Função execute10s
    public function execute5s_for_10d() {
        // Tempo atual
        $start_time = time();

        // Tempo de execução (em segundos)
        $execution_time =  240 * 3600;
        // Loop para executar a função por 10 minutos
        while (time() - $start_time < $execution_time) {
            $this->execute10s(); // Chama a função
            sleep(2); // Pausa a execução do script por 10 segundos
        }
    }

    // Função execute10s
    private function execute10s() {
        // Adiciona os erros à lista de erros
        $sql = "SHOW STATUS LIKE 'Threads_connected'";

        // Supondo que $this->db seja o objeto de conexão com o banco de dados
        $value = $this->db->query($sql)->row_array();
        //echo $value['Value'];

        $data = array(
            'date_created' => date('Y-m-d H:i:s'),
            'connections' => $value['Value']
        );

        // Insere os dados na tabela tbl__connections
        $this->db->insert('tbl__connections', $data);
    }

}
