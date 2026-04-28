<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Pubs extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('New_model');
        $this->load->model('Intranet_model');

        $this->load->library('upload');
        $this->load->helper(array('form', 'url'));
    }

    public function index()
    {
        $id = $_GET['id'];

        $aviso = $this->New_model->get_aviso($id)->row();
        $view_data["aviso"] = $aviso;
        $this->load->view('gestao_corporativa/intranet/pubs/new.php', $view_data);
    }

    public function novo()
    {
        $this->load->model('Categorias_campos_model');
        $_d['categories'] = $this->Categorias_campos_model->get('', 'atendimento', array('portal' => 1));

        $this->load->view('gestao_corporativa/intranet/pubs/new.php', $_d);
    }

    public function editar_aviso()
    {
        $id = $_GET['id'];

        $aviso = $this->New_model->get_aviso($id)->row();
        $view_data["aviso"] = $aviso;
        $this->load->view('gestao_corporativa/intranet/pubs/new.php', $view_data);
    }

    public function ver_aviso_antigo()
    {
        $id = $_GET['id'];

        $aviso = $this->New_model->get_aviso($id)->row();
        $staff = $this->Intranet_model->get_one($aviso->user_cadastro)->row();
        $aviso->staff = $staff;
        $view_data["aviso"] = $aviso;
        $this->load->view('gestao_corporativa/intranet/pubs/see.php', $view_data);
    }

    public function ver_aviso()
    {

        $id = $_GET['id'];
        //$staff = $this->Intranet_model->get_one($aviso->user_cadastro)->row();
        //$aviso->staff = $staff;
        $view_data["aviso"] = $this->New_model->get_aviso($id)->row();
        $this->load->view('gestao_corporativa/intranet/pubs/see.php', $view_data);
        //$this->load->view('gestao_corporativa/intranet/index.php', $view_data);
    }

    public function ver_todas()
    {

        $view_data["title"] = 'INTRANET - Lista de noticias';
        $view_data['noticias'] = $this->New_model->get_noticias();
        $this->load->view('gestao_corporativa/intranet/pubs/ver_todas.php', $view_data);
        //$this->load->view('gestao_corporativa/intranet/index.php', $view_data);
    }


    function add_aviso()
    {

        $id = $this->input->post('id');
        $config['upload_path'] = './assets/intranet/img/avisos/'; //pastaaaaa

        $config['allowed_types'] = '*'; //tipoooos

       
        //

        if ($_FILES['arquivo']['name']) {
            $foto = str_replace(' ', '_', $_FILES['arquivo']['name']);
        } else {
            $foto = $this->input->post('foto');
        }



        $this->load->library('upload', $config);
        $this->upload->initialize($config);
        if ($this->upload->do_upload('arquivo') or $this->input->post('foto') != '') {

            $info = array(
                "titulo" => $this->input->post('titulo'),
                "tipo" => $_POST['radio'],
                "categoria_id" => $_POST['categoria_id'],
                "foto" => $foto,
                "deleted" => 0,
                "ativo" => 0,
                "descricao" => $_POST['descricao'],
                "link" => $this->input->post('link'),
                "empresa_id" => $this->session->userdata('empresa_id'),
                "fim" => $this->input->post('fim')
            );
            if ($id) {
                $info["data_ultima_alteracao"] = date('Y-m-d');
                $info["user_ultima_alteracao"] = get_staff_user_id();
            } else {
                $info["data_ultima_alteracao"] = date('Y-m-d');
                $info["user_ultima_alteracao"] = get_staff_user_id();
                $info["data_cadastro"] = date('Y-m-d');
                $info["user_cadastro"] = get_staff_user_id();
            }
        }

      //   echo "aqui"; exit;
        $save = $this->New_model->save_new($info, $id);


        if ($save) {
            redirect('gestao_corporativa/intranet_admin/index');
        } 

    }

    public function deletar_aviso($id = 0)
    {
        $id = $_GET['id'];
        $this->db->where('id', $id);
        $dados['deleted'] = 1;
        $this->db->where('id', $id);
        $this->db->update('tbl_intranet_avisos', $dados);
        redirect('gestao_corporativa/intranet_admin/index');
    }

    /**************************************************************************
     *************************** FIM CONTA A PAGAR ********************************
     **************************************************************************/
}
