
<?php

use Dompdf\Options;
use Dompdf\Dompdf;

defined('BASEPATH') or exit('No direct script access allowed');

class Comunicado extends AdminController {

    public function __construct() {

        parent::__construct();
        
        if (!is_logged_in()) {
            access_denied('CI');
            redirect($_SERVER['HTTP_REFERER']);
        }
        

        $this->load->model('Intranet_model');

        $this->load->model('Comunicado_model');

        $this->load->model('Departments_model');
        $this->load->model('Staff_model');
    }

    /* List all leads */

    public function index() {
        $view_data = [];
        $this->load->view('gestao_corporativa/comunicado_interno/list.php', $view_data);
    }

    public function add() {
        if (!has_permission_intranet('comunicado_interno', '', 'create') && !is_admin()) {
            access_denied('Comunicado');
        }

        $view_data = [];
        $view_data['registro_atendimento_id'] = $this->input->get('registro_atendimento_id') ?: '';
        add_admin_tickets_js_assets();

        $this->load->view('gestao_corporativa/intranet/comunicado/new_c.php', $view_data);
    }

    public function see_all() {

        $contador_lido = 0;

        $contador_not_lido = 0;

        $comunicados_recebidos = $this->Comunicado_model->get_recebidos();

        $view_data["comunicados_recebidos"] = $comunicados_recebidos;

        for ($comunic = 0; $comunic < count($comunicados_recebidos); $comunic++) {

            if ($comunicados_recebidos[$comunic]->status == 1) {

                $lidos[$contador_lido] = $this->Comunicado_model->get_comunicado($comunicados_recebidos[$comunic]->ci_id)->row();

                $lidos[$contador_lido]->user = $this->Intranet_model->get_one($lidos[$contador_lido]->user_create)->row();

                $contador_lido++;
            } else {

                $not_lidos[$contador_not_lido] = $this->Comunicado_model->get_comunicado($comunicados_recebidos[$comunic]->ci_id)->row();

                $not_lidos[$contador_not_lido]->user = $this->Intranet_model->get_one($not_lidos[$contador_not_lido]->user_create)->row();

                $contador_not_lido++;
            }
        }

        $view_data["lidos"] = $lidos;

        $view_data["nao_lidos"] = $not_lidos;

        $this->load->view('gestao_corporativa/intranet/comunicado/see_all.php', $view_data);
    }

    public function config() {



        $id = $_GET['id'];

        $ci = $this->Comunicado_model->get_comunicado($id)->row();

        $send = $this->Comunicado_model->get_comunicado_send($id);

        if ($send) {
            $y = 0;
            $quantidade = count($send);
            for ($i = 0; $i < count($send); $i++) {
                $array[$i]['id'] = $send[$i]['staff_id'];
                $array[$i]['origem'] = $send[$i]['origem'];
                if ($send[$i]['status'] == 1) {
                    $y++;
                }
                $array_lido[$i] = $send[$i];
            }
            //print_r($array); exit;   
        }
        $porcentagem = ($y / $quantidade) * 100; // PORCENTAGEM
        $view_data['total'] = $quantidade;
        $view_data['cientes'] = $y;

        $view_data['staffs'] = $array;
        $view_data['porcentagem'] = number_format($porcentagem, 1, '.', '');;
        $view_data['staffs_cientes'] = $array_lido;
        $view_data['css'] = $this->Comunicado_model->get_comunicado_cc($id);
        $view_data['ci'] = $ci;
        $departments_staffs = $this->Intranet_model->get_departamentos_staffs_selecionados();
        $view_data['departments_staffs'] = $departments_staffs;

        $this->load->view('gestao_corporativa/intranet/comunicado/destinatarios.php', $view_data);
    }

    public function add_comunicado($id = '') {


        

        if ($this->input->post()) {
            
//            $arquivos = $_FILES['attachments'];
//            print_r($arquivos);
//
//            if ($arquivos) {
//                $count = count($_FILES['attachments']['name']);
//
//                $Destino = "./assets/intranet/img/ci/";
//
//                $files = $_FILES["attachments"];
//
//                $salvos = 0;
//
//                for ($i = 0; $i < $count; $i++) {
//
//                    $Nome = str_replace(' ', '_', $files["name"][$i]);
//
//                    echo $Nome;
//                    $Tmpname = $files["tmp_name"][$i];
//
//                    $arquivo[$i] = $Nome;
//                   // echo $arquivo; 
//
//                    $Caminho = $Destino . $Nome;
//
//                    if (move_uploaded_file($Tmpname, $Caminho)) {
//
//                        $salvos++;
//                    }
//                }
//                $banco_arquivos_em_string = implode(",", $arquivo);
//            }

            
            $arquivo = $_FILES['attachments'];

            if ($arquivo) {

                $Destino = "./assets/intranet/img/ci/";

                    $Nome = str_replace(' ', '_', $arquivo["name"]);

                    $Tmpname = $arquivo["tmp_name"];

                    $arquivo = str_replace(' ', '_', $arquivo["name"]);

                    $Caminho = $Destino . $Nome;

                    if (move_uploaded_file($Tmpname, $Caminho)) {

                        $salvos++;
                         $banco_arquivos_em_string = $arquivo;
                    }
                
               
            }


        $banco_file = $banco_arquivos_em_string;
        //echo $banco_file; exit;
            $data = $this->input->post();

            $data['descricao'] = $_POST['descricao'];
            $data['docs'] = $banco_file;
            $data['sequencial'] = $this->Comunicado_model->get_sequencial($data['setor_id']);
            $department = $this->Departments_model->get($data['setor_id']);
            $data['codigo'] = strtoupper($department->abreviado) . " #" . $data['sequencial'];

            //print_r($data); exit;
            $id = $this->input->post('id');
            unset($data['csrf_token_name']);

            $id = $this->Comunicado_model->add($data, $id, $banco_file);
//echo $id; exit;
            $departments_staffs = $this->Intranet_model->get_departamentos_staffs_selecionados();
//echo 'jsj'; exit;
            //}

            $view_data['id'] = $id;

            $view_data['departments_staffs'] = $departments_staffs;
            //echo 'jsjjs';

            $this->load->view('gestao_corporativa/intranet/comunicado/new_c2.php', $view_data);
        }
    }

    public function delete_doc_ci() {

        $id = $_GET['id'];

        $pieces = explode("/", $id);

        $id = $pieces[0];

        $doc_excluir = $pieces[1];

        $ci = $this->Comunicado_model->get_comunicado($id)->row();

        $docs_atual = $ci->docs;

        $contador = 0;

        $docs = explode(",", $docs_atual);

        for ($i = 0; $i < count($docs); $i++) {



            $novo[$contador] = $docs[$i];

            if ($docs[$i] == $doc_excluir) {

                $contador--;

                unset($novo[$contador]);
            }

            $contador++;
        }

        $docs = implode(",", $novo);

        $dados['docs'] = $docs;

        $this->db->where('id', $id);

        if ($this->db->update('tbl_intranet_ci', $dados)) {

            $this->retornar($id);
        }
    }

    public function delete_comunicado() {

        $id = $_GET['id'];

        $dados['deleted'] = 1;

        $this->db->where('id', $id);

        $this->db->update('tbl_intranet_ci', $dados);

        $this->db->where('ci_id', $id);

        $this->db->update('tbl_intranet_ci_send', $dados);

        redirect('gestao_corporativa/intranet_admin/index/?group=comunicado_interno');
    }

    public function delete_send() {

        $ids = explode('.', $_GET['ids']);
        $dados['deleted'] = 1;

        $this->db->where('id', $ids[0]);

        $this->db->update('tbl_intranet_ci_send', $dados);

        redirect('gestao_corporativa/intra/comunicado/config?id=' . $ids[1]);
    }

    public function send_comunicado() {

        $data = $this->input->post();
        $id = $this->input->post('id');
        //echo $id; exit;

        if ($this->input->post('cc')) {
            $ccs = $this->input->post('cc');
            unset($data['cc']);
        }
        if ($ccs) {
            $this->Comunicado_model->cc($ccs, $id);
        }
        $insert = $this->Comunicado_model->send($data, $id);

        redirect('gestao_corporativa/Intranet');
    }

    public function ciente() {
        $id = $_GET['id'];

        $dados['status'] = 1;
        if ($this->input->post('retorno')) {
            $dados['retorno'] = $this->input->post('retorno');
        }

        $dados['dt_read'] = date('Y-m-d H:i:s');
        $this->db->where('id', $id);
        $this->db->where('staff_id', get_staff_user_id());
        
        if ($this->db->update('tbl_intranet_ci_send', $dados)) {
            $this->load->model('Comunicacao_model');
            $this->load->model('Staff_model');
            $action = 'ci_aware';
            $action_info = get_actions($action);
            $send = $this->Comunicado_model->get_send($id);
            

            $ci = $this->Comunicado_model->get_comunicado($send->ci_id)->row();
            $action_info['rel_id'] = $ci->id;
            $action_info['rel_type'] = 'ci';

            $replace_from = array("#change_reference", "#change_staff_full_name");
            $replace_to = array("#" . $ci->codigo, get_staff_full_name());
            $action_info['link_sigplus'] = $action_info['link_sigplus'].'/config?id='.$id;

            $action_info['conteudo_email'] = str_replace($replace_from, $replace_to, $action_info['conteudo_email']);

            $action_info['conteudo_sms'] = str_replace($replace_from, $replace_to, $action_info['conteudo_sms']);

            $action_info['assunto'] = str_replace($replace_from, $replace_to, $action_info['assunto']);
            $staff = $this->Staff_model->get($ci->user_create);
            $action_info['email_destino'] = $staff->email;
            $action_info['staff_destino'] = $staff->staffid;
            $action_info['phone_destino'] = $staff->phonenumber;
            $this->Comunicacao_model->send_sigplus_email_sms($action, $action_info);
        }
        //echo 'aqui'; exit;
        redirect('admin');
    }

    public function sends_edit() {
        $id = $this->input->post('id_ci');
        //echo $id_doc.'<br>';

        $destinatarios_atuais = $this->Comunicado_model->get_comunicado_send($id);
        $staffs = $this->input->post('for_staffs');
//        /print_r($destinatarios_atuais); exit;
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
                $array['ci_id'] = $id;
                $array['dt_send'] = date('Y-m-d H:i:s');
                $array['staff_id'] = $destinatarios_editar[$i];
                $array['origem'] = $origem;
                $array['empresa_id'] = $this->session->userdata('empresa_id');

                $insert = $this->Comunicado_model->send_staffs($array);
            }
        }

        for ($i = 0; $i < count($destinatarios_atuais); $i++) {
            //echo $destinatarios_editar[$i];      echo '<br>';      print_r($destinatarios_atuais);
            if (in_array($destinatarios_atuais[$i], $destinatarios_editar)) {
                //executar nnada
            } else {
                $dados['deleted'] = 1;
                $st = "ci_id = $id and staff_id = $destinatarios_atuais[$i]";
                $this->db->where($st);
                $this->db->update('tbl_intranet_ci_send', $dados);
            }
        }
        redirect('gestao_corporativa/intra/Comunicado/config?id=' . $id);
    }

    public function visualizar_comunicado() {
        //VERIFICA SE TEM COMUNICADO INTERNO
        // $this->verifica_comunicado_pendente();
        $id = $_GET['id'];
        $send_id = $_GET['send_id'];

        $ci = $this->Comunicado_model->get_comunicado($id)->row();

        $staff = $this->Intranet_model->get_one($ci->user_create)->row();
        $view_data["title"] = $ci->titulo;
        if ($send_id != '') {

            $data['dt_ciente'] = date('Y-m-d H:i:s');
            $this->db->where("id", $send_id);
            $this->db->update("tbl_intranet_ci_send", $data);
            $view_data["content"] = 'comunicado/visualizar_comunicado';
            $view_data["without_permission"] = true;
            $view_data["send_staff"] = $this->Comunicado_model->get_send($send_id);
        } else {
            $view_data["content"] = 'comunicado/visualizar_comunicado_lido';
        }


        $view_data["layout"] = 'sidebar-collapse';

        $view_data["exibe_menu_esquerdo"] = 1;

        $view_data["exibe_menu_topo"] = 1;

        $view_data["ci"] = $ci;
        $view_data["staff"] = $staff;

        $this->load->view('gestao_corporativa/intranet/index.php', $view_data);
    }

    public function visualizar_comunicado_pendente($id = '') {
        //VERIFICA SE TEM COMUNICADO INTERNO
        // $this->verifica_comunicado_pendente();
        //$id = $_GET['id'];

        $ci = $this->Comunicado_model->get_comunicado($id)->row();

        $staff = $this->Intranet_model->get_one($ci->user_create)->row();

        $view_data["content"] = 'comunicado/visualizar_comunicado';
        // layout
        $view_data["layout"] = 'sidebar-collapse'; //layout-top-nav
        $view_data["exibe_menu_esquerdo"] = 0;
        $view_data["exibe_menu_topo"] = 0;

        $view_data["ci"] = $ci;
        $view_data["staff"] = $staff;
        $this->load->view('gestao_corporativa/intranet/index.php', $view_data);
    }

    public function pdf($id) {
        //$campos = $this->Categorias_campos_model->get_values($id, 'r.o', '0');

        $this->load->library('Pdf');
        $company_logo = get_option('company_logo');
        $data['company_name'] = get_option('companyname');

        $path = base_url() . 'uploads/company/' . $company_logo;
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data_ = file_get_contents($path);

        $data['base64'] = 'data:image/' . $type . ';base64,' . base64_encode($data_);

        $data['ci'] = $this->Comunicado_model->get_comunicado($id)->row();
        //$image_path = 'https://www.unimedmanaus.com.br/ass_nova/uni11599.jpg';
        //$image_data = file_get_contents($image_path);
        //$base64_image = base64_encode($image_data);
        //echo $base64_image; exit;

        $doc = new DOMDocument();
        $doc->loadHTML($data['ci']->descricao);
        $imageTags = $doc->getElementsByTagName('img');

        $srcs = [];
        $new_srcs = [];

        foreach($imageTags as $tag) {
           $srcs[] = $tag->getAttribute('src');
           $path = parse_url($tag->getAttribute('src'));
           $path = '';
           
           $type = pathinfo($path, PATHINFO_EXTENSION);
           
           $a = file_get_contents($path);
           //echo $data_; exit;
           $new_srcs[] = 'data:image/' . $type . ';base64,' . base64_encode($a);
        }
        //print_r($srcs);
        //print_r($new_srcs);
        //exit;

        //$data['ci']->descricao = str_replace($srcs, $new_srcs, $phrase);

        $html = $this->load->view('gestao_corporativa/comunicado_interno/pdf.php', $data, true);

        $this->dompdf->loadHtml($html);
        $this->dompdf->render();

        $this->dompdf->stream('CI ' . $data['ci']->codigo . ".pdf", array("Attachment" => 0));
    }

}
