<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Appointments extends ClientsController {

    function __construct() {

        parent::__construct();

        $this->load->model('Clients_model');
        $this->load->model('Categorias_campos_model');
        $this->load->model('Workflow_model');
        $this->load->model('Staff_model');
        $this->load->model('Departments_model');
        $this->load->model('Atendimentos_model');
    }

    public function index() {



        $_d["tittle"] = 'Agendamentos';

        $id_client = get_client_user_id();
        if (!$id_client) {
            redirect(base_url('Authentication/logout'));
        }

        $_d['protocolo'] = $this->session->userdata('protocolo');
        $_d["info_perfil"] = $this->Clients_model->get($id_client);
        $_d['atendimento'] = $this->Atendimentos_model->get_ra_by_id($this->session->userdata('atendimento_id'));

        $ch = curl_init();

        curl_setopt_array($ch, array(
            CURLOPT_URL => 'http://189.2.65.2/sigplus/api/Retorna_Especialidades',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{ "cod_pessoa": "' . $this->session->userdata('cd_pessoa') . '"
                                }',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json; charset=utf-8'
            ),
        ));

        $especialidades = curl_exec($ch);

        $especialidades = json_decode($especialidades);

        curl_setopt_array($ch, array(
            CURLOPT_URL => 'http://189.2.65.2/sigplus/api/Retorna_Agendamentos',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{ "cod_pessoa": "' . $this->session->userdata('cd_pessoa') . '"
                                }',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json; charset=utf-8'
            ),
        ));

        $my_appointments = curl_exec($ch);
        //echo $my_appointments; exit;

        $my_appointments = json_decode($my_appointments, true);
//print_r($my_appointments); exit;
        curl_close($ch);
        $_d['especialidades'] = $especialidades;
//        /print_r($especialidades); exit;

        if (is_array($my_appointments)) {
            $_d['my_appointments'] = $my_appointments;
        } else {
            $_d['my_appointments'] = [];
        }



        $_d['workflows'] = [];

        if ($_SESSION['consulta']) {
            $_d['consulta'] = $_SESSION['consulta'];
            unset($_SESSION['consulta']);
        }

        $this->load->view('portal/appointments/index.php', $_d);
    }

    /**
     * 11/04/2023
     * @WannaLuiza
     * Get vagas da especialidade
     */
    public function get_especialidade_vagas() {


        $cd_especialidade = $_GET["cd_especialidade"];

        $ch = curl_init();

        curl_setopt_array($ch, array(
            CURLOPT_URL => 'http://189.2.65.2/sigplus/api/Retorna_Vagas_Especialidade',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{ "cod_especialidade": "' . $cd_especialidade . '"
                                }',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json; charset=utf-8'
            ),
        ));

        $vagas = curl_exec($ch);
        curl_close($ch);

        $data['vagas'] = json_decode($vagas);
        //print_r($vagas); exit;


        $this->load->view('portal/appointments/table_vagas', $data);
    }

    /**
     * 11/04/2023
     * @WannaLuiza
     * Get vagas da especialidade
     */
    public function get_vagas() {

        //echo 'jsj'; exit;

        $cod_agenda = $_POST["cod_agenda"];

        // echo $data; EXIT;

        $ch = curl_init();

        curl_setopt_array($ch, array(
            CURLOPT_URL => 'http://189.2.65.2/sigplus/api/Retorna_Datas_Disponiveis',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{ "cod_agenda": "' . $cod_agenda . '"
                                }',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json; charset=utf-8'
            ),
        ));

        $det = curl_exec($ch);
        //echo $det; exit;
        curl_close($ch);

        $data['dias'] = json_decode($det);
        $data['cod_agenda'] = $cod_agenda;
        //print_r($det); exit;


        $this->load->view('portal/appointments/table_all_vagas', $data);
    }

    /**
     * 11/04/2023
     * @WannaLuiza
     * Get vagas da especialidade
     */
    public function get_vaga_det() {

        //echo 'jsj'; exit;

        $cod_agenda = $_POST["cod_agenda"];
        $date = $_POST["date"];

        // echo $data; EXIT;

        $ch = curl_init();

        curl_setopt_array($ch, array(
            CURLOPT_URL => 'http://189.2.65.2/sigplus/api/Retorna_detalhes_vagas',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{ "cod_agenda": "' . $cod_agenda . '"
                                }',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json; charset=utf-8'
            ),
        ));

        $det = curl_exec($ch);
        curl_setopt_array($ch, array(
            CURLOPT_URL => 'http://189.2.65.2/sigplus/api/Retorna_Horarios_Disponiveis',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{ "cod_agenda": "' . $cod_agenda . '", "data_agenda": "' . $date . '", "cod_pessoa": "' . $this->session->userdata('cd_pessoa') . '" }',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json; charset=utf-8'
            ),
        ));
        $horarios = curl_exec($ch);

        //echo $horarios; exit;

        $horarios = json_decode($horarios, true);
        //print_r($horarios);

        if (count($horarios) > 0) {
            $data['horario'] = $horarios[0]['HR_INICIO_CHEGADA'];
        }


        //echo $det; exit;
        curl_close($ch);

        $data['det'] = json_decode($det, true);
        $data['date'] = $date;

        //print_r($det); exit;


        $this->load->view('portal/appointments/det', $data);
    }

    /**
     * 13/06/2023
     * @WannaLuiza
     * Agendar
     */
    public function schedule($cod_agenda) {


        $date = $_GET["date"];
        $especialidade = $_GET["especialidade"];
        //echo $date; echo $cod_agenda; exit;

        $id_client = get_client_user_id();
        if (!$id_client) {
            redirect(base_url('Authentication/logout'));
        }

        $info = $this->Clients_model->get($id_client);
        // print_r($info_perfil); exit;

        if ($date) {
            $ch = curl_init();

            curl_setopt_array($ch, array(
                CURLOPT_URL => 'http://189.2.65.2/sigplus/api/Retorna_Horarios_Disponiveis',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => '{ "cod_agenda": "' . $cod_agenda . '", "data_agenda": "' . $date . '", "cod_pessoa": "' . $this->session->userdata('cd_pessoa') . '" }',
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json; charset=utf-8'
                ),
            ));

            $horarios = curl_exec($ch);

            //echo $horarios; exit;

            $horarios = json_decode($horarios, true);
            //print_r($horarios);

            if (count($horarios) > 0) {
                $horario = $horarios[0];
                //echo $this->session->userdata('cd_pessoa').' '.$especialidade.' '.$cod_agenda. ' '.$info->phonenumber;
                //print_r($horario); exit;

                curl_setopt_array($ch, array(
                    CURLOPT_URL => 'http://189.2.65.2/sigplus/api/Marca_Consulta',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => '{ "cod_pessoa": "' . $this->session->userdata('cd_pessoa') . '",'
                    . ' "nr_sequencia": "' . $horario['NR_SEQUENCIA'] . '",'
                    . ' "telefone": "' . $info->phonenumber . '",'
                    . ' "cod_especialidade": "' . $especialidade . '" }',
                    CURLOPT_HTTPHEADER => array(
                        'Content-Type: application/json; charset=utf-8'
                    ),
                ));

                $consulta = curl_exec($ch);
                //echo $consulta; exit;
                curl_close($ch);
                $consulta = json_decode($consulta, true);
                //print_r($consulta); exit;

                $_SESSION['consulta'] = $consulta;

                redirect('portal/appointments');
                //header("Location: ".base_url()."portal/Appointments/index");
            }
        }



        //curl_close($ch);
    }

    /**
     * 13/06/2023
     * @WannaLuiza
     * Confirma ou cancela agendamento de acordo como parametro $option
     */
    public function confirm_cancel() {

        //echo 'jsj'; exit;

        $sequencia = $_GET["sequencia"];
        $option = $_GET["option"];
        //echo $sequencia. ' '. $option; exit;
        // echo $data; EXIT;

        if ($sequencia and $option) {
            $ch = curl_init();

            curl_setopt_array($ch, array(
                CURLOPT_URL => 'http://189.2.65.2/sigplus/api/Confirma_Cancela_Consulta',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => '{ "sequencia": "' . $sequencia . '", "option": "' . $option . '" }',
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json; charset=utf-8'
                ),
            ));

            $consulta = curl_exec($ch);
            curl_close($ch);

            $consulta = json_decode($consulta, true);
            $_SESSION['consulta'] = $consulta;
            redirect('portal/appointments/index');

            //$this->load->view('portal/appointments/det', $data);
        }
    }

    public function get_appointment() {

        $sequencia = $_GET["sequencia"];

        if ($sequencia) {
            $ch = curl_init();

            curl_setopt_array($ch, array(
                CURLOPT_URL => 'http://189.2.65.2/sigplus/api/Detalhes_Agendamento',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => '{ "sequencia": "' . $sequencia . '" }',
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json; charset=utf-8'
                ),
            ));

            $appointment = curl_exec($ch);
            curl_close($ch);

            //echo $appointment; exit;
            $appointment = json_decode($appointment, true);

            if (is_array($appointment)) {
                $_d['appointment'] = $appointment;
            } else {
                echo $appointment;
                exit;
            }
            $this->load->view('portal/appointments/appointment', $_d);
        }
    }

}
