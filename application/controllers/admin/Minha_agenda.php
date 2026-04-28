<?php



defined('BASEPATH') or exit('No direct script access allowed');



class Minha_agenda extends AdminController {



    public function __construct() {

        parent::__construct();

        $this->load->model('announcements_model');

        $this->load->model('Unidades_hospitalares_model');

        $this->load->model('Medicos_model');

        $this->load->model('Horario_model');

        $this->load->model('Utilities_model');

        $this->load->model('Perfil_medico_model');

        $this->load->model('Relatorio_escala_model');

    }



    public function index() {

        $this->load->model('Perfil_medico_model');

        $medicoid = get_staff_user_id();



        $data['info'] = $this->Perfil_medico_model->get_info_pessoais($medicoid);

        $data['tabela'] = $this->Perfil_medico_model->get_tabela($medicoid);



        //$this->load->view('admin/aba_medico/head');

        $this->load->view('admin/aba_medico/principal', $data);

    }



    public function agenda() {

	

        $this->load->model('Medicos_model');

        $data['medicos'] = $this->Medicos_model->get();
	//print_r($data); exit;
        $this->load->view('admin/aba_medico/agenda', $data);
	
    }



    public function retorno_medicoid() {

	echo 'aqui'; exit;

        $medicoid = $this->input->post("medicoid");

        //echo $medicoid;exit;

        if ($medicoid) {

            $data['info'] = $this->Perfil_medico_model->get_info_pessoais($medicoid);

            // echo $medicoid; exit;

            $data['tabela'] = $this->Perfil_medico_model->get_tabela($medicoid);



            $this->load->view('admin/aba_medico/agenda_medico_id', $data);

        }

    }



    public function atualizar_perfil() {

        $this->load->model('Perfil_medico_model');

        $medicoid = get_staff_user_id();



        $data['info'] = $this->Perfil_medico_model->get_info_pessoais($medicoid);

        $data['tabela'] = $this->Perfil_medico_model->get_tabela($medicoid);

        $this->load->view('admin/aba_medico/atualizar_perfil', $data);

    }



    public function atualizar_agenda() {

        $this->load->model('Medicos_model');

        $data['medicos'] = $this->Medicos_model->get();

        $this->load->view('admin/aba_medico/atualizar_agenda', $data);

    }



    public function tarefas() {

        $this->load->view('admin/aba_medico/tarefas');

    }



    public function mensagens() {

        $this->load->view('admin/aba_medico/mensagens');

    }



}

