<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * 11/01/2023
 * @WannaLuiza
 * INTRANET - Model para Workflow
 */
class Workflow_model extends App_Model {

    public function __construct() {
        parent::__construct();
        $this->empresa_id = $this->session->userdata('empresa_id');
    }

    /**
     * 19/10/2022
     * @WannaLuiza
     * Insere um valor para determinado campo de determinada categoria
     */
    public function add_campo_value($data = null) {

        if (!$data['rel_type']) {
            $data['rel_type'] = 'workflow';
        }

        unset($data['registro_id']);
        if ($this->db->insert("tbl_intranet_categorias_campo_values", $data)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 18/10/2022
     * @WannaLuiza
     * Insere um workflows
     */
    public function add($data = null) {
        if ($data) {
            $data['empresa_id'] = $this->session->userdata('empresa_id');
            if ($this->db->insert("tbl_intranet_workflow", $data)) {
                $id_insert = $this->db->insert_id();

                if ($data['registro_atendimento_id']) {
                    $this->load->model('Atendimentos_model');
                    $atendimento = $this->Atendimentos_model->get_ra_by_id($data['registro_atendimento_id']);
                    //print_r($atendimento); exit;
                    $campos_sms = array(
                        "data_registro" => date('Y-m-d H:i:s'),
                        "usuario_registro" => get_staff_user_id(),
                        "phone_destino" => $atendimento->contato,
                        "assunto" => 'Nova Solicitação. Protocolo: ' . $atendimento->protocolo,
                        "mensagem" => 'Nova Solicitação. Protocolo: ' . $atendimento->protocolo,
                        "rel_type" => 'workflow',
                        "rel_id" => $id_insert,
                        "client_id" => $atendimento->client_id,
                        "empresa_id" => $this->session->userdata('empresa_id')
                    );
                    //echo 'jsj'; exit;
                    $campos_email = array(
                        "data_registro" => date('Y-m-d H:i:s'),
                        "usuario_registro" => get_staff_user_id(),
                        "email_destino" => $atendimento->email,
                        "assunto" => 'Nova Solicitação. Protocolo: ' . $atendimento->protocolo,
                        "mensagem" => 'Nova Solicitação. Protocolo: ' . $atendimento->protocolo,
                        "rel_type" => 'workflow',
                        "rel_id" => $id_insert,
                        "client_id" => $atendimento->client_id,
                        "empresa_id " => $this->session->userdata('empresa_id')
                    );

                    $this->load->model('Comunicacao_model');
                    $email = $this->Comunicacao_model->addEmail($campos_email);
                    $sms = $this->Comunicacao_model->addSms($campos_sms);
                }

                return $id_insert;
            } else {
                return false;
            }
        }
    }

    public function get_fluxo_sequencial($fluxo) {
        $empresa_id = $this->session->userdata('empresa_id');
        $tbl_fluxo = 'tbl_intranet_categorias_fluxo';
        $fluxo_id = $fluxo->id;
        $categoria_id = $fluxo->categoria_id;

        $sql = "SELECT * from $tbl_fluxo "
                . "WHERE $tbl_fluxo.empresa_id = $empresa_id AND  $tbl_fluxo.vindo_de = '$fluxo_id' and $tbl_fluxo.deleted = 0 and categoria_id = $categoria_id";

        $resultado = $this->db->query($sql)->result_array();
        if (count($resultado) == 0) {
            $codigo_sequencial = $fluxo->codigo_sequencial . '.1';
        } else {
            $quantidade = count($resultado);
            $ultimo_cod_sequencial = $resultado[$quantidade - 1]['codigo_sequencial'];
            $sufixo = substr($ultimo_cod_sequencial, -1);
            $proximo_numero = $sufixo + 1;
            $prefixo = rtrim($ultimo_cod_sequencial, $sufixo);
            $codigo_sequencial = $prefixo . $proximo_numero;
        }
        return $codigo_sequencial;
    }

    public function prazo_atual($fluxo) {
        $empresa_id = $this->session->userdata('empresa_id');
        $tbl_fluxo = 'tbl_intranet_categorias_fluxo';
        $fluxo_id = $fluxo;

        $sql = "SELECT * from $tbl_fluxo "
                . "WHERE $tbl_fluxo.empresa_id = $empresa_id AND  $tbl_fluxo.id = '$fluxo_id' and $tbl_fluxo.deleted = 0";

        $row = $this->db->query($sql)->row();
        $prazo = $row->prazo;
        $vindo_de = $row->vindo_de;
        while ($vindo_de != '') {
            $sql = "SELECT * from $tbl_fluxo "
                    . "WHERE $tbl_fluxo.empresa_id = $empresa_id AND  $tbl_fluxo.id = '$vindo_de' and $tbl_fluxo.deleted = 0";

            $row = $this->db->query($sql)->row();
            $prazo = $row->prazo + $prazo;
            $vindo_de = $row->vindo_de;
        }
        return $prazo;
    }

    public function get_fluxos_seguintes($id = 0) {
        $empresa_id = $this->session->userdata('empresa_id');
        $tbl_categoria_fluxo = 'tbl_intranet_categorias_fluxo';
        $tbl_departments = 'tbldepartments';
        $sql = "SELECT $tbl_categoria_fluxo.*, $tbl_departments.name as setor_name from $tbl_categoria_fluxo
            left join $tbl_departments on $tbl_departments.departmentid = $tbl_categoria_fluxo.setor
        where $tbl_categoria_fluxo.empresa_id = $empresa_id and $tbl_categoria_fluxo.deleted = 0 and $tbl_categoria_fluxo.vindo_de = $id ";
        //echo $sql; exit;

        return $this->db->query($sql)->result_array();
    }

    public function get_prazo_corrido($id = 0) {
        $empresa_id = $this->session->userdata('empresa_id');
        $tbl_categoria_fluxo = 'tbl_intranet_categorias_fluxo';

        $sql = "SELECT * from $tbl_categoria_fluxo
        where $tbl_categoria_fluxo.empresa_id = $empresa_id and $tbl_categoria_fluxo.deleted = 0 and $tbl_categoria_fluxo.id = $id ";

        $row = $this->db->query($sql)->row();
        $prazo = 0;
        while ($row->vindo_de != '') {
            $prazo = $prazo + $row->prazo;
            $sql = "SELECT * from $tbl_categoria_fluxo
        where $tbl_categoria_fluxo.empresa_id = $empresa_id and $tbl_categoria_fluxo.deleted = 0 and $tbl_categoria_fluxo.id = " . $row->vindo_de;

            $row = $this->db->query($sql)->row();
        }

        $sql = "SELECT * from $tbl_categoria_fluxo
        where $tbl_categoria_fluxo.empresa_is = $empresa_id and $tbl_categoria_fluxo.deleted = 0 and $tbl_categoria_fluxo.id = " . $row->vindo_de;
        $prazo = $prazo + $row->prazo;
        //echo $sql; exit;

        return $prazo;
    }

    /**
     * 19/12/2022
     * @WannaLuiza
     * Retorna um fluxo
     */
    public function get_fluxo($id = 0) {
        $empresa_id = $this->session->userdata('empresa_id');
        $tbl_categoria_fluxo = 'tbl_intranet_categorias_fluxo';
        $tbl_departments = 'tbldepartments';
        $sql = "SELECT $tbl_categoria_fluxo.*, $tbl_departments.name as setor_name from $tbl_categoria_fluxo
            left join $tbl_departments on $tbl_departments.departmentid = $tbl_categoria_fluxo.setor
        where $tbl_categoria_fluxo.empresa_id = $empresa_id and $tbl_categoria_fluxo.deleted = 0 and $tbl_categoria_fluxo.id = $id ";

        return $this->db->query($sql)->row();
    }

    /**
     * 19/12/2022
     * @WannaLuiza
     * Retorna os campos de um determinado tipo(pela empresa_conectada) - NECESSÁRIO ID DO TIPO PARA RETORNAR OS CAMPOS DO MESMO 
     */
    public function get_categoria_fluxo($tipo_id = 0, $fluxo_id = '') {
        $empresa_id = $this->session->userdata('empresa_id');
        $tbl_fluxos = 'tbl_intranet_categorias_fluxo';
        $sql = "SELECT $tbl_fluxos.* from $tbl_fluxos
        where $tbl_fluxos.empresa_id = $empresa_id and $tbl_fluxos.deleted = 0 and $tbl_fluxos.categoria_id = $tipo_id ";
        if (!$fluxo_id) {
            $sql .= "and $tbl_fluxos.vindo_de is null";
        } else {
            $sql .= "and $tbl_fluxos.id = $fluxo_id";
        }

        return $this->db->query($sql)->row();
    }

    /**
     * 19/10/2022
     * @WannaLuiza
     * Insere um valor para determinado campo de determinada categoria
     */
    public function add_workflow_fluxo($data = null) {
        if ($this->db->insert("tbl_intranet_workflow_fluxo_andamento", $data)) {
            return true;
        } else {
            return false;
        }
    }

    public function get_fluxos_andamento($id = 0, $date_end = '') {
        $empresa_id = $this->session->userdata('empresa_id');
        $tbl_workflow_fluxo = 'tbl_intranet_workflow_fluxo_andamento';
        $tbl_departments = 'tbldepartments';
        $tbl_fluxo = 'tbl_intranet_categorias_fluxo';
        $sql = "SELECT $tbl_workflow_fluxo.*, $tbl_departments.name as setor_name,  $tbl_fluxo.question, $tbl_fluxo.objetivo from $tbl_workflow_fluxo
            left join $tbl_departments on $tbl_departments.departmentid = $tbl_workflow_fluxo.department_id
                left join $tbl_fluxo on $tbl_fluxo.id = $tbl_workflow_fluxo.fluxo_id
        where $tbl_workflow_fluxo.empresa_id = $empresa_id and $tbl_workflow_fluxo.deleted = 0 and $tbl_workflow_fluxo.workflow_id = $id ";
        if ($date_end) {
            $sql .= " and $tbl_workflow_fluxo.data_concluido <= '$date_end' ";
        }

        $sql .= " order by $tbl_workflow_fluxo.fluxo_sequencia asc";
        //echo $sql; exit;

        return $this->db->query($sql)->result_array();
    }

    public function get_fluxos_andamento_atual($id = 0, $status = '', $orderby = 'asc') {
        $empresa_id = $this->session->userdata('empresa_id');
        $tbl_workflow_fluxo = 'tbl_intranet_workflow_fluxo_andamento';
        $tbl_fluxo = 'tbl_intranet_categorias_fluxo';
        $sql = "SELECT $tbl_workflow_fluxo.*, $tbl_fluxo.question, $tbl_fluxo.objetivo, $tbl_fluxo.contato_cliente, $tbl_fluxo.finaliza_cliente, $tbl_fluxo.codigo_sequencial from $tbl_workflow_fluxo
            left join $tbl_fluxo on $tbl_fluxo.id = $tbl_workflow_fluxo.fluxo_id
        where $tbl_workflow_fluxo.empresa_id = $empresa_id and $tbl_workflow_fluxo.deleted = 0 and $tbl_workflow_fluxo.concluiu_fluxo = 0  $status"
                . "and $tbl_workflow_fluxo.workflow_id = $id order by $tbl_workflow_fluxo.fluxo_sequencia $orderby";
//       /echo $sql; exit;

        return $this->db->query($sql)->row();
    }

    public function get_fluxo_atual($id = 0) {
        $tbl_workflow_fluxo = 'tbl_intranet_workflow_fluxo_andamento';
        $sql = "SELECT $tbl_workflow_fluxo.*  from $tbl_workflow_fluxo
        where  $tbl_workflow_fluxo.deleted = 0 and $tbl_workflow_fluxo.concluido = 0 "
                . "and $tbl_workflow_fluxo.workflow_id = $id ";
//       /echo $sql; exit;

        $row = $this->db->query($sql)->row();
        return ($row) ? $row : '';
    }

    public function get_fluxo_andamento($id = '') {
        $empresa_id = $this->session->userdata('empresa_id');
        $tbl_workflow_fluxo = 'tbl_intranet_workflow_fluxo_andamento';
        $tbl_fluxo = 'tbl_intranet_categorias_fluxo';
        $tbl_departments = 'tbldepartments';
        $sql = "SELECT $tbl_workflow_fluxo.*, $tbl_fluxo.question, $tbl_departments.name from $tbl_workflow_fluxo
            left join $tbl_fluxo on $tbl_fluxo.id = $tbl_workflow_fluxo.fluxo_id
            left join $tbl_departments on $tbl_departments.departmentid = $tbl_fluxo.setor
        where $tbl_workflow_fluxo.empresa_id = $empresa_id and $tbl_workflow_fluxo.deleted = 0 and $tbl_workflow_fluxo.id = $id ";
        //echo $sql; exit;

        return $this->db->query($sql)->row();
    }

    public function get_workflow_by_id($id = '') {

        $empresa_id = $this->session->userdata('empresa_id');
        $tbl_workflow = 'tbl_intranet_workflow';
        $tbl_categoria = 'tbl_intranet_categorias';
        $departments = 'tbldepartments';

        $sql = "SELECT $tbl_workflow.*, $departments.name, $tbl_categoria.titulo from $tbl_workflow "
                . "LEFT JOIN $tbl_categoria ON $tbl_categoria.id = $tbl_workflow.categoria_id "
                . "LEFT JOIN $departments ON $departments.departmentid = $tbl_categoria.responsavel "
                . "WHERE $tbl_workflow.empresa_id = $empresa_id AND $tbl_workflow.deleted = 0 AND $tbl_workflow.id = $id";

        return $this->db->query($sql)->row();
    }

    public function get_departamentos() {

        $empresa_id = $this->session->userdata('empresa_id');
        $staff_id = get_staff_user_id();
        $tbl_staffdepartment = 'tblstaff_departments';
        $tbl_department = 'tbldepartments';
        $tbl_staff = 'tblstaff';

        $sql = "SELECT $tbl_department.*, $tbl_staff.lastname, $tbl_staff.firstname from $tbl_department "
                . "INNER JOIN $tbl_staffdepartment ON $tbl_staffdepartment.departmentid = $tbl_department.departmentid "
                . "INNER JOIN $tbl_staff ON $tbl_staff.staffid = $tbl_department.aprovador "
                . "WHERE $tbl_department.empresa_id = $empresa_id AND  $tbl_department.deleted = 0 AND $tbl_staffdepartment.staffid = $staff_id "
                . "AND $tbl_staff.empresa_id = $empresa_id AND  $tbl_staff.deleted = 0";

        //echo $sql; exit;
        return $this->db->query($sql)->result_array();
    }

    public function aprovar($data = null) {
        $action = $data['action'];
        $dados_workflow['id'] = $data['id'];
        if ($action == 'true') {
            $dados_workflow['aguardando_aprovacao'] = 2;
        } else {
            $dados_workflow['aguardando_aprovacao'] = 3;
        }


        if ($action == 'true') {

            $this->db->where('id', $dados_workflow['id']);

            $workflow = $this->db->get('tbl_intranet_workflow')->row();
            $this->load->model('Categorias_campos_model');
            $categoria = $this->Categorias_campos_model->get_categoria($workflow->categoria_id);
            $dados_workflow['date_start'] = date('Y-m-d H:i:s');
            $dados_workflow['date_prazo'] = date('Y-m-d', strtotime("+" . $categoria->prazo_client . " days", strtotime(date('Y-m-d H:i:s'))));
            $dados_workflow['date_prazo_client'] = date('Y-m-d', strtotime("+" . $categoria->prazo_client . " days", strtotime(date('Y-m-d H:i:s'))));
            $dados_workflow['user_start'] = get_staff_user_id();

            $this->db->where('id', $workflow->id);
            if ($this->db->update('tbl_intranet_workflow', $dados_workflow)) {

                $fluxo = $this->get_categoria_fluxo($workflow->categoria_id);
                $fluxo_inicial = array(
                    "categoria_id" => $workflow->categoria_id,
                    "fluxo_id" => $fluxo->id,
                    "workflow_id" => $dados_workflow['id'],
                    "fluxo_sequencia" => '1',
                    "department_id" => $fluxo->setor,
                    "date_created" => date('Y-m-d H:i:s'),
                    "data_prazo" => date('Y-m-d', strtotime("+" . $fluxo->prazo . " days", strtotime(date('Y-m-d H:i:s')))),
                    "user_created" => get_staff_user_id(),
                    "empresa_id" => $this->session->userdata('empresa_id')
                );
                //print_r($fluxo_inicial); exit;
                $this->add_workflow_fluxo($fluxo_inicial);
                return 1;
            }
        } elseif ($action == 'false') {
            $this->db->where('id', $dados_workflow['id']);
            if ($this->db->update('tbl_intranet_workflow', $dados_workflow)) {
                return 2;
            }
        }
        return '';
    }

    public function get_workflow_by_client_id($client_id) {
        $tbl_workflow = 'tbl_intranet_workflow';
        $tbl_ra = 'tbl_intranet_registro_atendimento';
        $tbl_categorias = 'tbl_intranet_categorias';

        $sql = "SELECT $tbl_workflow.*, $tbl_categorias.titulo, $tbl_categorias.titulo_abreviado, $tbl_ra.protocolo, $tbl_ra.id as atendimento_id from $tbl_workflow "
                . "LEFT JOIN $tbl_ra ON $tbl_workflow.registro_atendimento_id = $tbl_ra.id "
                . "INNER JOIN $tbl_categorias ON $tbl_workflow.categoria_id = $tbl_categorias.id "
                . "WHERE ($tbl_workflow.client_id = $client_id or $tbl_ra.client_id = $client_id) and $tbl_workflow.deleted = 0 "
                . "ORDER BY $tbl_workflow.id desc";
//echo $sql; exit;
        return $this->db->query($sql)->result_array();
    }

    public function if_last_finish_client($id) {

        $empresa_id = $this->session->userdata('empresa_id');
        $tbl_categoria_fluxo = 'tbl_intranet_categorias_fluxo';

        $sql = "SELECT * from $tbl_categoria_fluxo
        where $tbl_categoria_fluxo.empresa_id = $empresa_id and $tbl_categoria_fluxo.deleted = 0 and $tbl_categoria_fluxo.id = $id ";

        //echo $sql; exit;
        $row = $this->db->query($sql)->row();

        while ($row->vindo_de != '') {

            $sql = "SELECT * from $tbl_categoria_fluxo "
                    . "where $tbl_categoria_fluxo.empresa_id = $empresa_id and $tbl_categoria_fluxo.deleted = 0 and $tbl_categoria_fluxo.id = " . $row->vindo_de;

            $row = $this->db->query($sql)->row();
            if ($row->finaliza_cliente == 1) {
                return true;
            }
        }

        if ($row->vindo_de != '') {
            $sql = "SELECT * from $tbl_categoria_fluxo "
                    . "where $tbl_categoria_fluxo.empresa_is = $empresa_id and $tbl_categoria_fluxo.deleted = 0 and $tbl_categoria_fluxo.id = " . $row->vindo_de;

            $row = $this->db->query($sql)->row();

            if ($row->finaliza_cliente == 1) {
                return true;
            }
        }


        return false;
    }

    public function get_count($where = '') {

        $empresa_id = $this->session->userdata('empresa_id');
        $sql = "SELECT count(*) as quantidade from " . db_prefix() . "_intranet_workflow "
                . "WHERE  empresa_id = $empresa_id  and deleted = 0 $where";

        //echo $sql;
        $quantidade = $this->db->query($sql)->row();
        $quantidade = $quantidade->quantidade;
        return $quantidade;
    }

    public function get_count_setor($sql = '') {

        $empresa_id = $this->session->userdata('empresa_id');

        //echo $sql;
        $quantidade = $this->db->query($sql)->result_array();
        $quantidade = count($quantidade);
        return $quantidade;
    }

    public function get_status($id = '') {
        $statuses = [];
        array_push($statuses, ['id' => '1', 'label' => 'ABERTO', 'color' => '#ff2d42']);
        array_push($statuses, ['id' => '2', 'label' => 'EM ANDAMENTO', 'color' => '#03a9f4']);
        array_push($statuses, ['id' => '3', 'label' => 'FINALIZADO', 'color' => '#84c529']);
        array_push($statuses, ['id' => '4', 'label' => 'CANCELADO', 'color' => '#c0c0c0']);

        return $statuses;
    }

    public function get_status_setor() {
        $statuses = [];
        array_push($statuses, ['id' => '3', 'label' => 'TODOS', 'color' => '#84c529', 'where' => '  (data_prazo > CURDATE() or data_prazo = CURDATE() OR data_prazo < CURDATE())']);
        array_push($statuses, ['id' => '2', 'label' => 'EM DIA', 'color' => '#03a9f4', 'where' => '  (data_prazo > CURDATE() or data_prazo = CURDATE())']);
        array_push($statuses, ['id' => '1', 'label' => 'ATRASADO', 'color' => '#ff2d42', 'where' => '  data_prazo < CURDATE()']);

        return $statuses;
    }

    public function get_client_contacts($workflow_id = '', $id = '') {

        if (is_numeric($id)) {
            $this->db->where(db_prefix() . '_intranet_workflow_clientcontact.id', $id);
            return $this->db->get(db_prefix() . '_intranet_workflow_clientcontact')->row();
        } else {
            $this->db->where(db_prefix() . '_intranet_workflow_clientcontact.workflow_id', $workflow_id);

            return $this->db->get(db_prefix() . '_intranet_workflow_clientcontact')->result_array();
        }
    }

    public function add_workflow_back($data = null) {

        $data['date_created'] = date('Y-m-d H:i:s');
        $data['user_created'] = get_staff_user_id();
        //print_r($data); exit;

        if ($this->db->insert(db_prefix() . "_intranet_workflow_back", $data)) {
            return $this->db->insert_id();
        } else {
            return false;
        }
    }

    public function get_workflow_back($fluxo_andamento_id, $id = '') {

        if (is_numeric($id)) {
            $this->db->where(db_prefix() . '_intranet_workflow_back.id', $id);
            return $this->db->get(db_prefix() . '_intranet_workflow_back')->row();
        } else {
            $this->db->where(db_prefix() . '_intranet_workflow_back.workflow_andamento_id', $fluxo_andamento_id);

            return $this->db->get(db_prefix() . '_intranet_workflow_back')->result_array();
        }
    }

    public function add_resquest_internal($data = []) {

        $data['date_created'] = date('Y-m-d H:i:s');
        $data['user_created'] = get_staff_user_id();
        $data['empresa_id'] = $this->session->userdata('empresa_id');
        if ($data['id']) {
            $this->db->where("id", $data['id']);
            $this->db->update("tbl_intranet_workflow_internal_request", $data);
            return $data['id'];
        } else {
            //print_r($data); exit;
            $this->db->insert("tbl_intranet_workflow_internal_request", $data);
            return $this->db->insert_id();
        }
    }

    public function add_resquest_external($data = []) {

        $data['date_created'] = date('Y-m-d H:i:s');
        $data['user_created'] = get_staff_user_id();
        $data['empresa_id'] = $this->session->userdata('empresa_id');
        if ($data['id']) {
            $this->db->where("id", $data['id']);
            if ($this->db->update("tbl_intranet_workflow_external_request", $data)) {
                $this->db->where('id', $data['id']);
                $request = $this->db->get("tbl_intranet_workflow_external_request")->row();
                $workflow = $this->get_workflow_by_id($request->workflow_id);
                if ($workflow->registro_atendimento_id) {
                    $this->load->model('Atendimentos_model');
                    $atendimento = $this->Atendimentos_model->get_ra_by_id($workflow->registro_atendimento_id);
                    //print_r($atendimento); exit;
                    $campos_sms = array(
                        "data_registro" => date('Y-m-d H:i:s'),
                        "usuario_registro" => get_staff_user_id(),
                        "phone_destino" => $atendimento->contato,
                        "assunto" => 'Solicitação #' . $workflow->id . ': Precisamos de mais informações para esta solicitação. Protocolo: ' . $atendimento->protocolo,
                        "mensagem" => 'Solicitação #' . $workflow->id . ': Precisamos de mais informações para esta solicitação. Protocolo: ' . $atendimento->protocolo,
                        "rel_type" => 'workflow',
                        "rel_id" => $workflow->id,
                        "client_id" => $atendimento->client_id,
                        "empresa_id" => $this->session->userdata('empresa_id')
                    );
                    //echo 'jsj'; exit;
                    $campos_email = array(
                        "data_registro" => date('Y-m-d H:i:s'),
                        "usuario_registro" => get_staff_user_id(),
                        "email_destino" => $atendimento->email,
                        "assunto" => 'Solicitação #' . $workflow->id . ': Precisamos de mais informações para esta solicitação. Protocolo: ' . $atendimento->protocolo,
                        "mensagem" => 'Solicitação #' . $workflow->id . ': Precisamos de mais informações para esta solicitação. Protocolo: ' . $atendimento->protocolo,
                        "rel_type" => 'workflow',
                        "rel_id" => $workflow->id,
                        "client_id" => $atendimento->client_id,
                        "empresa_id " => $this->session->userdata('empresa_id')
                    );

                    $this->load->model('Comunicacao_model');
                    $email = $this->Comunicacao_model->addEmail($campos_email);
                    $sms = $this->Comunicacao_model->addSms($campos_sms);
                }
            }
        } else {
            //print_r($data); exit;
            $this->db->insert("tbl_intranet_workflow_external_request", $data);
            return $this->db->insert_id();
        }
    }

    public function get_resquest_internal($workflow_id, $id = '') {

        //echo $workflow_id; exit;
        if (is_numeric($id)) {
            $this->db->where(db_prefix() . '_intranet_workflow_internal_request.id', $id);
            return $this->db->get(db_prefix() . '_intranet_workflow_internal_request')->row();
        } else {
            $this->db->where(db_prefix() . '_intranet_workflow_internal_request.workflow_id', $workflow_id);

            return $this->db->get(db_prefix() . '_intranet_workflow_internal_request')->result_array();
        }
    }

    public function get_resquest_external($workflow_id, $id = '') {


        if (is_numeric($id)) {
            //echo $workflow_id; exit;
            $this->db->where(db_prefix() . '_intranet_workflow_external_request.id', $id);
            return $this->db->get(db_prefix() . '_intranet_workflow_external_request')->row();
        } else {

            $this->db->where(db_prefix() . '_intranet_workflow_external_request.workflow_id', $workflow_id);

            return $this->db->get(db_prefix() . '_intranet_workflow_external_request')->result_array();
        }
    }

    public function get_departments() {
        $empresa_id = $this->session->userdata('empresa_id');

        $sql = "SELECT department_id, name from tbl_intranet_workflow_fluxo_andamento 
            LEFT JOIN tbldepartments on tbldepartments.departmentid = department_id
            INNER JOIN tbl_intranet_workflow on tbl_intranet_workflow.id = tbl_intranet_workflow_fluxo_andamento.workflow_id
            where tbldepartments.empresa_id = $empresa_id and concluido = 0 and tbl_intranet_workflow.deleted = 0 and tbl_intranet_workflow.cancel_id = 0
            and tbl_intranet_workflow_fluxo_andamento.deleted = 0 and tbl_intranet_workflow.status in(1,2)
            GROUP BY department_id  ";
        //echo $sql; exit;

        $resultado = $this->db->query($sql)->result_array();

        return $resultado;
    }

    public function get_departments_fluxo_andamento($id) {

        $sql = "SELECT count(*) as qtd FROM tbl_intranet_workflow_fluxo_andamento
                INNER JOIN tbl_intranet_workflow on tbl_intranet_workflow.id = tbl_intranet_workflow_fluxo_andamento.workflow_id
                where department_id = $id and concluido = 0 and tbl_intranet_workflow.deleted = 0 and tbl_intranet_workflow.cancel_id = 0
                and tbl_intranet_workflow_fluxo_andamento.deleted = 0 and tbl_intranet_workflow.status in(1,2)";
        //echo $sql; exit;

        $resultado = $this->db->query($sql)->row();
        $resultado = $resultado->qtd;

        return $resultado;
    }

    public function get_categories() {
        $empresa_id = $this->session->userdata('empresa_id');

        $sql = "SELECT categoria_id, tbl_intranet_categorias.titulo as titulo from tbl_intranet_workflow 
            LEFT JOIN tbl_intranet_categorias on tbl_intranet_categorias.id = tbl_intranet_workflow.categoria_id
            where tbl_intranet_categorias.empresa_id = $empresa_id and  tbl_intranet_categorias.deleted = 0 and  tbl_intranet_workflow.deleted = 0 and tbl_intranet_workflow.cancel_id = 0
             
            GROUP BY categoria_id  ";
        //echo $sql; exit;

        $resultado = $this->db->query($sql)->result_array();

        return $resultado;
    }

    public function get_categories_fluxo_andamento($id) {

        $sql = "SELECT count(*) as qtd FROM tbl_intranet_workflow
                where categoria_id = $id and tbl_intranet_workflow.deleted = 0 and tbl_intranet_workflow.cancel_id = 0";
//        /echo $sql; exit;

        $resultado = $this->db->query($sql)->row();
        $resultado = $resultado->qtd;

        return $resultado;
    }

    public function get_data_fluxo_andamento($data) {

        $sql = "SELECT count(*) as qtd FROM tbl_intranet_workflow
                where  CAST(date_created AS DATE) = '$data' and tbl_intranet_workflow.deleted = 0 and tbl_intranet_workflow.cancel_id = 0";
//        /echo $sql; exit;

        $resultado = $this->db->query($sql)->row();
        $resultado = $resultado->qtd;

        return $resultado;
    }

    public function get_mes_fluxo_andamento($mes, $ano, $portal = false) {

        $sql = "SELECT count(*) as qtd FROM tbl_intranet_workflow
                where  MONTH(date_created) = '$mes' AND YEAR(date_created) = '$ano' and tbl_intranet_workflow.deleted = 0 and tbl_intranet_workflow.cancel_id = 0";
        if ($portal == true) {
            $sql .= " and tbl_intranet_workflow.portal = 1";
        }
//        /echo $sql; exit;

        $resultado = $this->db->query($sql)->row();
        $resultado = $resultado->qtd;

        return $resultado;
    }

    public function get_report_old($cat = '', $start = '', $end = '', $campos = [], $status = [], $campos_) {
        //print_r($campos); 
        //print_r($campos_); exit;

        $status = implode(',', $status);

        if ($campos) {

            $campos = implode(',', $campos);
            $campos = ',' . $campos;
        }
        //echo $start; exit;
        $tbl_wf = 'tbl_intranet_workflow';
        $tbl_ra = 'tbl_intranet_registro_atendimento';
        $tbl_client = 'tblclients';

        $sql = "SELECT $tbl_wf.id, $tbl_wf.status, $tbl_ra.id as registro_atendimento_id, $tbl_client.company, $tbl_client.numero_carteirinha $campos ";

        $array = [];
        foreach ($campos_ as $campo) {
            $label = preg_replace('/[^A-Za-z0-9\-]/', '', $campo['label']);

            $array[] = "'" . $campo['label'] . "'";
        }
        $values = implode(',', $array);
        $sql .= ", (SELECT GROUP_CONCAT(CONCAT('\"', tbl_intranet_categorias_campo.name, '\": \"', tbl_intranet_categorias_campo_values.value, '\"') SEPARATOR ', ')
FROM tbl_intranet_categorias_campo
INNER JOIN tbl_intranet_categorias_campo_values ON tbl_intranet_categorias_campo.id = tbl_intranet_categorias_campo_values.campo_id
LEFT JOIN tbl_intranet_categorias_fluxo ON tbl_intranet_categorias_fluxo.id = tbl_intranet_categorias_campo.preenchido_por
WHERE tbl_intranet_categorias_campo_values.rel_id = tbl_intranet_workflow.id AND tbl_intranet_categorias_campo.nome IN ($values)) AS value";

        $sql .= " FROM $tbl_wf "
                . "LEFT JOIN $tbl_ra on $tbl_ra.id = $tbl_wf.registro_atendimento_id "
                . "LEFT JOIN $tbl_client on $tbl_client.userid = $tbl_ra.client_id "
                . "LEFT JOIN tbl_intranet_categorias_campo_values valor ON $tbl_wf.categoria_id = valor.categoria_id "
                . "LEFT JOIN tbl_intranet_categorias_campo campo ON valor.campo_id = campo.id ";

        $sql .= " WHERE $tbl_wf.deleted = 0 and $tbl_wf.categoria_id = $cat and $tbl_wf.status in($status) "
                . "and $tbl_wf.date_created BETWEEN '" . date("Y-m-d", strtotime(str_replace('/', '-', $start))) . " 00:00:00' AND '" . date("Y-m-d", strtotime(str_replace('/', '-', $end))) . " 23:59:59' "
        ;
        echo $sql;
        exit;

        $result = $this->db->query($sql)->result_array();
        //print_r($result); exit;
        return $result;
    }

    public function get_report($cat = '', $start = '', $end = '', $campos = [], $status = [], $campos_ = null, $campos__ = null, $current = '') {
        //print_r($campos); 
        //print_r($campos_); exit;

        $status = implode(',', $status);

        if ($campos) {

            $campos = implode(',', $campos);
            $campos = ',' . $campos;
        }

        $array = [];
        foreach ($campos_ as $campo) {
            $label = preg_replace('/[^A-Za-z0-9\-]/', '', $campo['label']);

            $array[] = "'" . $campo['label'] . "'";
        }

        foreach ($campos__ as $campo) {
            $label = preg_replace('/[^A-Za-z0-9\-]/', '', $campo['label']);

            $array2[] = "'" . $campo['column'] . "'";
        }
        $values = implode(',', $array);
        $values2 = implode(',', $array2);

        $sql = "SELECT
    tbl_intranet_workflow.id,
    tbl_intranet_workflow.status,
    tbl_intranet_workflow.user_created,
    tbl_intranet_workflow.date_created,
    tbl_intranet_registro_atendimento.id AS registro_atendimento_id,
    tblclients.company,
    tblclients.dt_nascimento,
    tblclients.numero_carteirinha,
    tbl_intranet_workflow_fluxo_andamento.department_id,
                tbl_intranet_workflow_fluxo_andamento.atribuido_a,
                tbl_intranet_workflow_fluxo_andamento.fluxo_sequencia $campos";
        if ($values) {
            $sql .= ",(
        SELECT GROUP_CONCAT(
            CONCAT('" . '***' . "',REPLACE(tbl_intranet_categorias_campo.nome, ' ', '') , tbl_intranet_categorias_fluxo.setor, '" . '***' . " : " . '***' . "' , tbl_intranet_categorias_campo_values.value, '" . '***' . "')
            SEPARATOR ', '
        )
        FROM tbl_intranet_categorias_campo_values
        INNER JOIN tbl_intranet_categorias_campo ON tbl_intranet_categorias_campo.id = tbl_intranet_categorias_campo_values.campo_id
        LEFT JOIN tbl_intranet_categorias_fluxo on tbl_intranet_categorias_fluxo.id = tbl_intranet_categorias_campo.preenchido_por

        WHERE tbl_intranet_categorias_campo_values.rel_id = tbl_intranet_workflow.id
        AND tbl_intranet_categorias_campo.nome IN ($values)
    ) AS value";
        }
        if ($values2) {
            $sql .= ",(
        SELECT GROUP_CONCAT(
            CONCAT('" . '***' . "',REPLACE(tbl_intranet_categorias_campo.id, ' ', '') , '" . '***' . " : " . '***' . "' , tbl_intranet_categorias_campo_values.value, '" . '***' . "')
            SEPARATOR ', '
        )
        FROM tbl_intranet_categorias_campo_values
        INNER JOIN tbl_intranet_categorias_campo ON tbl_intranet_categorias_campo.id = tbl_intranet_categorias_campo_values.campo_id

        WHERE tbl_intranet_categorias_campo_values.rel_id = tbl_intranet_workflow.id
        AND tbl_intranet_categorias_campo.id IN ($values2)
    ) AS value2";
        }
        $sql .= " FROM tbl_intranet_workflow
LEFT JOIN tbl_intranet_registro_atendimento ON tbl_intranet_registro_atendimento.id = tbl_intranet_workflow.registro_atendimento_id
LEFT JOIN tblclients ON tblclients.userid = tbl_intranet_registro_atendimento.client_id
LEFT JOIN tbl_intranet_workflow_fluxo_andamento on tbl_intranet_workflow_fluxo_andamento.workflow_id = tbl_intranet_workflow.id and tbl_intranet_workflow_fluxo_andamento.concluido = 0";
        $sql .= " WHERE tbl_intranet_workflow.deleted = 0 and tbl_intranet_workflow.categoria_id = $cat and tbl_intranet_workflow.status in($status) ";
        if ($start && $end) {
            $sql .= " and tbl_intranet_workflow.date_created BETWEEN '" . date("Y-m-d", strtotime(str_replace('/', '-', $start))) . " 00:00:00' AND '" . date("Y-m-d", strtotime(str_replace('/', '-', $end))) . " 23:59:59' ";
        }
        if ($current) {
            $sql .= " and tbl_intranet_workflow_fluxo_andamento.department_id = $current ";
        }
        $sql .= "GROUP BY
    tbl_intranet_workflow.id,
    tbl_intranet_workflow.status,
    tbl_intranet_workflow.user_created,
    tbl_intranet_workflow.date_created,
    tbl_intranet_registro_atendimento.id,
    tblclients.company,
    tblclients.dt_nascimento,
    tblclients.numero_carteirinha,
    tbl_intranet_workflow_fluxo_andamento.department_id,
                tbl_intranet_workflow_fluxo_andamento.atribuido_a,
                tbl_intranet_workflow_fluxo_andamento.fluxo_sequencia
    $campos
";
       //  echo $sql;
       // exit;

        $result = $this->db->query($sql)->result_array();
        //print_r($result); exit;
        return $result;
    }

    // FILTRO 
    public function get_filtro_wf_by_user() {

        // $empresa_id = $this->session->userdata('empresa_id');
        $data_hoje = date('Y-m-d');
        $user = get_staff_user_id();
        $sql = "SELECT *
                 FROM tbl_intranet_filtro_wf
                 where user = $user  ";

        //echo $sql; exit;
        return $this->db->query($sql)->row();
    }
}
