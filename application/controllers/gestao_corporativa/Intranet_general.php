<?php
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/

defined('BASEPATH') or exit('No direct script access allowed');
require_once APPPATH . '/libraries/fpdi/autoload.php';
require_once APPPATH . '/libraries/tcpdf/tcpdf.php';

use setasign\Fpdi\Tcpdf\Fpdi;

class Intranet_general extends AdminController
{

    public function __construct()
    {

        parent::__construct();
        $this->load->model('Intranet_general_model');
        //$this->load->model('Registro_ocorrencia_model');
        //$this->load->model('Atendimentos_model');
        //$this->load->model('Categorias_campos_model');
        //$this->load->driver('cache');
    }

    public function index() {}

    public function add_note()
    {
        $data = $this->input->post();
        //print_r($_FILES['attachment_note']); exit;
        //print_r($_FILES); exit;


        $data['data_created'] = date('Y-m-d H:i:s');
        $data['user_created'] = get_staff_user_id();
        $data['empresa_id'] = $this->session->userdata('empresa_id');

        if ($this->db->insert('tbl_intranet_notes', $data)) {

            $id = $this->db->insert_id();
            $view['notes'] = $this->Intranet_general_model->get_notes($data['rel_type'], $data['rel_id']);
            $view['rel_type'] = $data['rel_type'];
            $view['rel_id'] = $data['rel_id'];

            foreach ($_FILES as $fileKey => $file_one) {
                if (isset($file_one['error']) && $file_one['error'] === UPLOAD_ERR_OK) {
                    $file = $file_one['name'];

                    // Extrai a extensão original do arquivo
                    $file_extension = pathinfo($file, PATHINFO_EXTENSION);

                    // Gera um novo nome de arquivo único com a extensão original
                    $new_filename = 'note' . $id . date('Y_m_d') . '_' . uniqid() . '.' . $file_extension;

                    // Verifica se o diretório de destino existe; se não existir, cria-o recursivamente
                    if (!file_exists("./assets/intranet/arquivos/files/")) {
                        mkdir("./assets/intranet/arquivos/files/", 0777, true);
                    }

                    // Define o caminho completo para o destino do arquivo enviado
                    $destination = "./assets/intranet/arquivos/files/" . $new_filename;

                    // Move o arquivo enviado para o destino com o novo nome de arquivo
                    if (move_uploaded_file($file_one["tmp_name"], $destination)) {
                        // Upload do arquivo bem-sucedido
                        $note_files[] = $new_filename;
                    }
                }
            }
            // print_r($note_files); exit;
            foreach ($note_files as $file_) {
                //echo $file_; exit;
                $data_file['url'] = base_url("/assets/intranet/arquivos/files/" . $file_);
                $data_file['file'] = $file_;
                //echo $data_file['file']; exit;
                $data_file['rel_type'] = 'note';
                $data_file['rel_id'] = $id;
                $data_file['date_created'] = date('Y-m-d H:i:s');
                $data_file['user_created'] = get_staff_user_id();
                $data_file['empresa_id'] = $this->session->userdata('empresa_id');
                //print_r($data_file); exit;
                $this->db->insert('tbl_intranet_files', $data_file);
            }

            $this->load->view('gestao_corporativa/notes/load_again', $view);
        }
    }

    public function delete_note()
    {

        $data_post = $this->input->post();
        //print_r($data_post); exit;
        $data['date_last_change'] = date('Y-m-d H:i:s');
        $data['user_last_change'] = get_staff_user_id();
        $data['deleted'] = '1';
        $this->db->where('id', $data_post['id']);
        $this->db->update('tbl_intranet_notes', $data);
        $view['notes'] = $this->Intranet_general_model->get_notes($data_post['rel_type'], $data_post['rel_id']);
        $view['rel_type'] = $data_post['rel_type'];
        $view['rel_id'] = $data_post['rel_id'];

        $this->load->view('gestao_corporativa/notes/load_again', $view);
    }

    public function update_note()
    {

        $data = $this->input->post();
        //print_r($data_post); exit;
        $data['date_last_change'] = date('Y-m-d H:i:s');
        $data['user_last_change'] = get_staff_user_id();
        $this->db->where('id', $data['id']);
        $this->db->update('tbl_intranet_notes', $data);
        $view['notes'] = $this->Intranet_general_model->get_notes($data['rel_type'], $data['rel_id']);
        $view['rel_type'] = $data['rel_type'];
        $view['rel_id'] = $data['rel_id'];

        $this->load->view('gestao_corporativa/notes/load_again', $view);
    }

    public function add_more_info()
    {
        $post = $this->input->post();

        $campo['empresa_id'] = $this->session->userdata('empresa_id');
        $campo['nome'] = $post['nome'];
        $campo['type'] = $post['type'];
        $campo['categoria_id'] = $post['rel_id'];
        $campo['rel_type'] = $post['rel_type'];
        //echo  $post['portal'];
        $campo['name'] = tira_pontuacao_espaco_caractereespecial($post['nome']) . uniqid();

        $campo['data_cadastro'] = date('Y-m-d');
        $campo['user_cadastro'] = get_staff_user_id();
        $campo['data_ultima_alteracao'] = date('Y-m-d');
        $campo['user_ultima_alteracao'] = get_staff_user_id();

        if ($this->db->insert("tbl_intranet_categorias_campo", $campo)) {
            // echo 'jjd'; exit;
            $id = $this->db->insert_id();
            $campos_value = array(
                "rel_id" => $post['rel_id'],
                "rel_type" => 'ro_more',
                "campo_id" => $id,
                "data_cadastro" => date('Y-m-d H:i:s'),
                "user_cadastro" => get_staff_user_id(),
                "empresa_id" => $this->session->userdata('empresa_id')
            );




            $campos_value['value'] = $post['value'];
            //print_r($campos_value); exit;
            if ($this->db->insert("tbl_intranet_categorias_campo_values", $campos_value)) {
                redirect($post['retorno']);
            }
        }
    }

    public function add_info_session()
    {
        //print_r($this->input->post());
        if ($this->input->post()) {
            // $_SESSION[$this->input->post('fixed_filter')] = $this->input->post('value');
            //$this->cache->file->save($this->input->post('fixed_filter'), $this->input->post('value'), 7200);
        }
        // $_SESSION['consulta'];
    }

    public function save_uploads()
    {
        $post = $this->input->files();
        // print_r($post);
        // exit;
        $campo['empresa_id'] = $this->session->userdata('empresa_id');
        $campo['nome'] = $post['nome'];
        $campo['type'] = $post['type'];
        $campo['categoria_id'] = $post['rel_id'];
        $campo['rel_type'] = $post['rel_type'];
        $campo['portal'] = $post['portal'];
        $campo['name'] = tira_pontuacao_espaco_caractereespecial($post['nome']) . uniqid();

        $campo['data_cadastro'] = date('Y-m-d');
        $campo['user_cadastro'] = get_staff_user_id();
        $campo['data_ultima_alteracao'] = date('Y-m-d');
        $campo['user_ultima_alteracao'] = get_staff_user_id();

        if ($this->db->insert("tbl_intranet_categorias_campo", $campo)) {
            $id = $this->db->insert_id();
            $campos_value = array(
                "rel_id" => $post['rel_id'],
                "rel_type" => 'ro_more',
                "campo_id" => $id,
                "data_cadastro" => date('Y-m-d H:i:s'),
                "user_cadastro" => get_staff_user_id(),
                "empresa_id" => $this->session->userdata('empresa_id')
            );

            if (isset($_FILES['value']) && $_FILES['value']['error'] === UPLOAD_ERR_OK) {
                $file = $_FILES['attachment'];

                // Extrai a extensão original do arquivo
                $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);

                // Gera um novo nome de arquivo único com a extensão original
                $new_filename = 'RO' . '_arquivo_' . uniqid() . '.' . $file_extension;

                // Define o caminho completo para o destino do arquivo enviado
                $destination = "./assets/intranet/arquivos/ro_arquivos/campo_file" . $new_filename;

                // Move o arquivo enviado para o destino com o novo nome de arquivo
                if (move_uploaded_file($file["tmp_name"], $destination)) {
                    // Upload do arquivo bem-sucedido
                    $value = $new_filename;
                } else {
                    // Erro ao mover o arquivo
                    $value = '';
                }
            } else {
                // Erro durante o upload do arquivo
                $value = '';
            }


            $campos_value['value'] = $value;
            //print_r($campos_value); exit;
            if ($this->db->insert("tbl_intranet_categorias_campo_values", $campos_value)) {
                redirect($post['retorno']);
            }
        }
    }

    public function teste()
    {
        // Crie uma matriz de dados fictícios
        $data = [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'age' => 30,
        ];

        // Carregue a biblioteca 'format' para gerar o XML
        $this->load->helper('xml');

        // Configurar o cabeçalho da resposta para XML
        $this->response->setHeader('Content-Type', 'application/xml');

        // Converter a matriz de dados em XML
        $xml = array_to_xml($data);

        // Exibir o XML gerado
        echo $xml;
    }

    public function table_files()
    {
        $this->app->get_table_data_intranet('files');
    }

    public function table_log()
    {
        $this->app->get_table_data_intranet('log');
    }

    public function file_()
    {

      // print_r($_POST); exit;

        $info = $_POST;

        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

        if($info['publicado'] == 1){
            $publicado = $info['publicado'];
        }else{
            $publicado = null;
        }     
       // $publicado = 1;
        // Caminho para o PDF existente
        $pdfPath = 'assets/intranet/' . $_POST['file']; //var_dump($pdfPath); exit;

        // Caminho para o PDF existente
        $pdfPath = 'assets/intranet/' . $_POST['file'];
        //  var_dump(realpath($pdfPath)); exit;

        //  echo $pdfPath; exit;
        // Inicializar o FPDI
        $tcpdi = new \setasign\Fpdi\Tcpdf\Fpdi(); //print_r($tcpdi); exit;
        $tcpdi->setPrintHeader(false);
        $tcpdi->setPrintFooter(false);

        // Adicionar uma página
        try {
            $pageCount = $tcpdi->setSourceFile($pdfPath);
        } catch (\setasign\Fpdi\PdfParser\CrossReference\CrossReferenceException $e) {
            exit("Erro: Este PDF está criptografado e não pode ser processado.");
        } catch (Exception $e) {
            exit("Erro ao processar PDF: " . $e->getMessage());
        }

       
        for ($pageNumber = 1; $pageNumber <= $pageCount; $pageNumber++) {
            $tcpdi->AddPage();

            // Importar a página do PDF existente
            $tplIdx = $tcpdi->importPage($pageNumber);
            $tcpdi->useTemplate($tplIdx);

            // Adicionar marca d'água ("Rascunho" no centro, texto na diagonal)
            // Adicionar marca d'água com TCPDI
            
           if (!$publicado) {
                $tcpdi->SetFont('helvetica', 'B', 50);
                $tcpdi->SetTextColor(255, 192, 203);
                $tcpdi->SetAlpha(0.5);
                $tcpdi->Text(50, 100, 'RASCUNHO');
                $tcpdi->SetAlpha(1);
           }
          //


            // Adicionar nome no canto superior esquerdo
            $tcpdi->SetFont('helvetica', 'I', 10);
            $tcpdi->SetTextColor(128, 128, 128); // Cinza
            $tcpdi->Text(0, 0, 'Documento gerado por ' . get_staff_full_name() . ' em ' . date('d/m/Y H:i:s'));
        }

        // Saída do PDF com marca d'água para o navegador
        $tcpdi->Output($_POST['name'] . '.pdf');
    }
}
