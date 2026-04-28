<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * 18/10/2022
 * @WannaLuiza
 * INTRANET - Model para R.O'S
 */
class Registro_ocorrencia_model extends App_Model {

    public function __construct() {
        parent::__construct();
        $this->empresa_id = $this->session->userdata('empresa_id');
    }

    /**
     * 18/10/2022
     * @WannaLuiza
     * Insere um r.o
     */
    public function add($data = null) {
        if ($data) {
            $data['user_created'] = get_staff_user_id();
            $data['date_created'] = date('Y-m-d H:i:s');
            $data['empresa_id'] = $this->session->userdata('empresa_id');

            $array = array_filter($data, function ($valor) {
                return !is_null($valor) && $valor !== '';
            });    
            $data = $array;

            if ($this->db->insert("tbl_intranet_registro_ocorrencia", $data)) {
              // echo "Salvou"; exit;
                $id_insert = $this->db->insert_id();

                if ($data['registro_atendimento_id']) {
                    $this->load->model('Atendimentos_model');
                    $atendimento = $this->Atendimentos_model->get_ra_by_id($data['registro_atendimento_id']);
                    //print_r($atendimento); exit;
                    $campos_sms = array(
                        "data_registro" => date('Y-m-d H:i:s'),
                        "usuario_registro" => get_staff_user_id(),
                        "phone_destino" => $atendimento->contato,
                        "assunto" => 'Novo Registro de Ocorrência. Protocolo: ' . $atendimento->protocolo,
                        "mensagem" => 'Novo Registro de Ocorrência. Protocolo: ' . $atendimento->protocolo,
                        "rel_type" => 'workflow',
                        "rel_id" => $id_insert,
                        "client_id" => $atendimento->client_id,
                        "empresa_id" => $this->session->userdata('empresa_id')
                    );
                    $campos_email = array(
                        "data_registro" => date('Y-m-d H:i:s'),
                        "usuario_registro" => get_staff_user_id(),
                        "email_destino" => $atendimento->email,
                        "assunto" => 'Novo Registro de Ocorrência. Protocolo: ' . $atendimento->protocolo,
                        "mensagem" => 'Novo Registro de Ocorrência. Protocolo: ' . $atendimento->protocolo,
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
              // echo "Não Salvou"; exit;
                return false;
            }
        }
    }

    /**
     * 07/11/2022
     * @WannaLuiza
     * Retorna todos os atuanhtes
     */
    public function get_atuantes() {
        $tbl_intranet_registro_ocorrencia_atuantes = 'tbl_intranet_registro_ocorrencia_atuantes';
        $sql = "SELECT * FROM $tbl_intranet_registro_ocorrencia_atuantes "
                . "WHERE  $tbl_intranet_registro_ocorrencia_atuantes.empresa_id = $this->empresa_id AND $tbl_intranet_registro_ocorrencia_atuantes.deleted = 0";

        return $this->db->query($sql)->result_array();
    }

    /**
     * 22/11/2022
     * @WannaLuiza
     * Retorna um atuante pelo id
     */
    public function get_atuante($id) {
        $tbl_intranet_registro_ocorrencia_atuantes = 'tbl_intranet_registro_ocorrencia_atuantes';
        $sql = "SELECT * FROM $tbl_intranet_registro_ocorrencia_atuantes "
                . "WHERE  $tbl_intranet_registro_ocorrencia_atuantes.empresa_id = $this->empresa_id AND $tbl_intranet_registro_ocorrencia_atuantes.deleted = 0 and $tbl_intranet_registro_ocorrencia_atuantes.id = $id";

        return $this->db->query($sql)->row();
    }

    /**
     * 07/09/2022(Não sei quANDo criei, mas essa foi a data que botei o comentário)
     * @WannaLuiza
     * Cadastra/edita um fluxo
     */
    public function add_atuantes($data = null, $id = '') {

        if ($data) {

           // print_r($data); exit;

            if ($id) {
                
                $data['user_ultima_alteracao'] = get_staff_user_id();
                $data['data_ultima_alteracao'] = date('Y-m-d');
                $this->db->where('id', $id);
                if ($this->db->update("tbl_intranet_registro_ocorrencia_atuantes", $data)) {
                    return $id;
                } else {
                    return false;
                }

            } else {

                $array = array_filter($data, function ($valor) {
                    return !is_null($valor) && $valor !== '';
                });    
                $data = $array;

                $data['user_cadastro'] = get_staff_user_id();
                $data['data_cadastro'] = date('Y-m-d');
                $data['user_ultima_alteracao'] = get_staff_user_id();
                $data['data_ultima_alteracao'] = date('Y-m-d');
                $data['empresa_id'] = $this->empresa_id;
                if ($this->db->insert("tbl_intranet_registro_ocorrencia_atuantes", $data)) {
                    $id_insert = $this->db->insert_id();
                    return $id_insert;
                } else {
                    return false;
                }

            }
        }
    }

    /**
     * 30/11/2022
     * @WannaLuiza
     * ALTERA O STATUS DO REGISTRO
     */
    public function change_ro_status($id, $status) {

        $this->db->where('id', $id);
        $this->db->update(db_prefix() . '_intranet_registro_ocorrencia', [
            'status' => $status,
        ]);

        if ($this->db->affected_rows() > 0) {
            $alert = 'success';
            $message = 'Status atualizado com sucesso!';
            hooks()->do_action('after_ticket_status_changed', [
                'id' => $id,
                'status' => $status,
            ]);
        } else {
            $alert = 'warning';
            $message = 'Erro ao atualizar status';
        }

        return [
            'alert' => $alert,
            'message' => $message,
        ];
    }

    /**
     * 30/11/2022
     * @WannaLuiza
     * ALTERA A VALIDADE DO REGISTRO
     */
    public function change_ro_validade($id, $validade) {

        $this->db->where('id', $id);
        $this->db->update(db_prefix() . '_intranet_registro_ocorrencia', [
            'validade' => $validade,
        ]);

        if ($this->db->affected_rows() > 0) {
            $alert = 'success';
            $message = 'Data de Validade Atualizada com sucesso';
        } else {
            $alert = 'warning';
            $message = 'Erro ao atualizar adata';
        }

        return [
            'alert' => $alert,
            'message' => $message,
        ];
    }

    /**
     * 18/10/2022
     * @WannaLuiza
     * Retorna os atuantes já preenchidos do registro
     */
    public function get_atuantes_preenchidos($id = 0) {
        $empresa_id = $this->session->userdata('empresa_id');
        $tbl_atuantes_for_ro = 'tbl_intranet_registro_ocorrencia_atuantes_por_registro';
        $sql = "SELECT atuante_id from $tbl_atuantes_for_ro
        where $tbl_atuantes_for_ro.empresa_id = $empresa_id and $tbl_atuantes_for_ro.deleted = 0 and $tbl_atuantes_for_ro.registro_id = $id";

        $array = $this->db->query($sql)->result_array();
        for ($i = 0; $i < count($array); $i++) {
            $array[$i] = $array[$i]['atuante_id'];
        }
        return $array;
    }

    /**
     * 09/11/2022
     * @WannaLuiza
     * Retorna os atuantes já preenchidos do registro
     */
    public function get_atuantes_preenchidos_all($id = 0) {
        $empresa_id = $this->session->userdata('empresa_id');
        $tbl_atuantes_for_ro = 'tbl_intranet_registro_ocorrencia_atuantes_por_registro';
        $tbl_atuantes = 'tbl_intranet_registro_ocorrencia_atuantes';
        $sql = "SELECT $tbl_atuantes.*, $tbl_atuantes_for_ro.id as id_atuante_staff, $tbl_atuantes_for_ro.staff_id, $tbl_atuantes_for_ro.prazo, $tbl_atuantes_for_ro.objetivo, $tbl_atuantes_for_ro.date_finalizado, $tbl_atuantes_for_ro.limitado, $tbl_atuantes_for_ro.id as id_atuante from $tbl_atuantes_for_ro
            left join $tbl_atuantes ON $tbl_atuantes.id = $tbl_atuantes_for_ro.atuante_id
        where $tbl_atuantes_for_ro.empresa_id = $empresa_id and $tbl_atuantes_for_ro.deleted = 0 and $tbl_atuantes_for_ro.registro_id = $id";
      //  echo $sql; exit;

        return $this->db->query($sql)->result_array();
    }

    /**
     * 26/10/2022
     * @WannaLuiza
     * Retorna todos os registros
     */
    public function get() {
        $empresa_id = $this->session->userdata('empresa_id');
        $tbl = 'tbl_intranet_registro_ocorrencia';
        $sql = "SELECT * from $tbl
        where $tbl.empresa_id = $empresa_id and $tbl.deleted = 0";

        return $this->db->query($sql)->result_array();
    }

    /**
     * 25/10/2022
     * @WannaLuiza
     * Retorna todas as notas de um registro específico
     */
    public function get_notes($id, $rel_type, $for = '') {
        $empresa_id = $this->session->userdata('empresa_id');
        $tbl_notes = 'tbl_intranet_registro_ocorrencia_notes';
        $tbl_staff = 'tblstaff';
        $tbl_atuantes_for = 'tbl_intranet_registro_ocorrencia_atuantes_por_registro';
        $tbl_atuantes = 'tbl_intranet_registro_ocorrencia_atuantes';
        
        if($for){
            $for = " AND ($tbl_notes.for_id = '$for' or  $tbl_notes.for_id = 'todos') ";
        }
        $sql = "SELECT $tbl_notes.*, $tbl_staff.firstname, $tbl_staff.lastname, $tbl_atuantes.titulo from $tbl_notes"
                . " LEFT JOIN $tbl_staff ON $tbl_staff.staffid = $tbl_notes.user_created"
                . " LEFT JOIN $tbl_atuantes_for ON $tbl_atuantes_for.id = $tbl_notes.for_id"
                . " LEFT JOIN $tbl_atuantes ON $tbl_atuantes.id = $tbl_atuantes_for.atuante_id"
                . " WHERE $tbl_staff.empresa_id = $empresa_id AND $tbl_notes.empresa_id = $empresa_id AND $tbl_notes.rel_type = '$rel_type' AND $tbl_notes.deleted = '0' AND $tbl_notes.registro_id = $id $for order by $tbl_notes.id desc";

        return $this->db->query($sql)->result_array();
    }

    /**
     * 26/10/2022
     * @WannaLuiza
     * Retorna todas as alterações de campos personalizados de um registro específico
     */
    public function get_changes($id) {
        $empresa_id = $this->session->userdata('empresa_id');
        $tbl_changes = 'tbl_intranet_registro_ocorrencia_changes';
        $tbl_staff = 'tblstaff';
        $tbl_campos = 'tbl_intranet_categorias_campo';
        $sql = "SELECT $tbl_campos.nome, $tbl_campos.type, $tbl_changes.*, $tbl_staff.firstname, $tbl_staff.lastname from $tbl_changes"
                . " LEFT JOIN $tbl_staff ON $tbl_staff.staffid = $tbl_changes.user_created"
                . " LEFT JOIN $tbl_campos ON $tbl_campos.id = $tbl_changes.campo_id"
                . " WHERE  $tbl_staff.empresa_id = $empresa_id AND $tbl_changes.empresa_id = $empresa_id AND $tbl_changes.registro_id = $id order by $tbl_changes.id desc";

        return $this->db->query($sql)->result_array();
    }

    /**
     * 07/11/2022
     * @WannaLuiza
     * Retorna os atuantes de uma categoria
     */
    public function get_categoria_atuantes($atuantes_string = '') {

        $empresa_id = $this->session->userdata('empresa_id');
        $tbl_atuantes = 'tbl_intranet_registro_ocorrencia_atuantes';
        $atuantes_array = explode(',', $atuantes_string);
        if (count($atuantes_array) > 1) {
            for ($i = 0; $i < count($atuantes_array); $i++) {

                if ($i == 0) {
                    $where = 'AND (' . $tbl_atuantes . '.id = ' . $atuantes_array[$i];
                } elseif ($i == (count($atuantes_array) - 1)) {
                    $where .= ' OR ' . $tbl_atuantes . '.id = ' . $atuantes_array[$i] . ')';
                } else {
                    $where .= ' OR ' . $tbl_atuantes . '.id = ' . $atuantes_array[$i];
                }
            }
        } else {
            $where = 'AND id = ' . $atuantes_array[0];
        }


        $sql = "SELECT $tbl_atuantes.* from $tbl_atuantes
        where $tbl_atuantes.empresa_id = $empresa_id and $tbl_atuantes.deleted = 0 $where ORDER BY $tbl_atuantes.id asc";
        //echo $sql; exit;
        return $this->db->query($sql)->result_array();
    }

    /**
     * 07/11/2022
     * @WannaLuiza
     * Retorna os status da categoria
     */

    /**
     * 07/11/2022
     * @WannaLuiza
     * Retorna as permissoes de abas do atuante
     */
    public function get_permissoes($ro_id) {

        $empresa_id = $this->session->userdata('empresa_id');
        $tbl_atuantes = 'tbl_intranet_registro_ocorrencia_atuantes_por_registro';
        $tbl_atuantes_all = 'tbl_intranet_registro_ocorrencia_atuantes';
        $user_id = get_staff_user_id();

        $sql = "SELECT $tbl_atuantes_all.abas from $tbl_atuantes
            INNER JOIN $tbl_atuantes_all on $tbl_atuantes_all.id = $tbl_atuantes.atuante_id
        where $tbl_atuantes.empresa_id = $empresa_id and $tbl_atuantes.deleted = 0 AND $tbl_atuantes.registro_id = $ro_id AND $tbl_atuantes.staff_id = $user_id";

        $funcoes = $this->db->query($sql)->result_array();

        for ($i = 0; $i < count($funcoes); $i++) {
            if ($i == 0) {
                $permissoes_string = $funcoes[$i]['abas'];
            } else {
                $permissoes_string .= ',' . $funcoes[$i]['abas'];
            }
        }

        $permissoes = explode(',', $permissoes_string);
        //print_r($permissoes); exit;
        return $permissoes;
    }

    public function get_atuantes_staff_id($ro_id, $atuante_id) {

        $empresa_id = $this->session->userdata('empresa_id');
        $tbl_atuantes = 'tbl_intranet_registro_ocorrencia_atuantes_por_registro';
        $tbl_atuantes_all = 'tbl_intranet_registro_ocorrencia_atuantes';

        $sql = "SELECT $tbl_atuantes.*, $tbl_atuantes_all.titulo from $tbl_atuantes
            left join $tbl_atuantes_all on $tbl_atuantes_all.id = $tbl_atuantes.atuante_id
        where $tbl_atuantes.empresa_id = $empresa_id and $tbl_atuantes.deleted = 0 AND $tbl_atuantes.registro_id = $ro_id AND $tbl_atuantes.atuante_id = $atuante_id ORDER BY $tbl_atuantes.id asc";
        //echo $sql; exit;
        return $this->db->query($sql)->row();
    }

    public function get_atuante_for_ro($atuante_for_id) {

        $empresa_id = $this->session->userdata('empresa_id');
        $tbl_atuantes = 'tbl_intranet_registro_ocorrencia_atuantes_por_registro';
        $tbl_atuantes_all = 'tbl_intranet_registro_ocorrencia_atuantes';

        $sql = "SELECT $tbl_atuantes.*, $tbl_atuantes_all.titulo from $tbl_atuantes
            left join $tbl_atuantes_all on $tbl_atuantes_all.id = $tbl_atuantes.atuante_id
        where $tbl_atuantes.empresa_id = $empresa_id and $tbl_atuantes.deleted = 0 AND  $tbl_atuantes.id = $atuante_for_id";
        //echo $sql; exit;
        return $this->db->query($sql)->row();
    }

    /**
     * 18/10/2022
     * @WannaLuiza
     * Retrna quais as responsabilidade do usuario logado
     */
    public function get_atuante_por_registro($ro_id) {

        //echo 'chegou aqui'; exit;
        $staff_id = get_staff_user_id();
        $empresa_id = $this->session->userdata('empresa_id');
        $tbl_atuantes = 'tbl_intranet_registro_ocorrencia_atuantes_por_registro';

        $sql = "SELECT $tbl_atuantes.* from $tbl_atuantes
        where $tbl_atuantes.empresa_id = $empresa_id and $tbl_atuantes.deleted = 0 AND $tbl_atuantes.registro_id = $ro_id AND $tbl_atuantes.staff_id = $staff_id ORDER BY $tbl_atuantes.id asc";
        //echo $sql; exit;
        return $this->db->query($sql)->result_array();
    }

    /**
     * 18/10/2022
     * @WannaLuiza
     * Retorna staff e departamento  
     */
    public function get_staff_department() {
        $staff_id = get_staff_user_id();
        $empresa_id = $this->session->userdata('empresa_id');
        $tblstaff = 'tblstaff';
        $departments = 'tbldepartments';
        $tblstaffdep = 'tblstaff_departments';
        $sql = "SELECT $departments.departmentid, $departments.name, $tblstaff.staffid from $tblstaff "
                . "LEFT JOIN $tblstaffdep ON $tblstaffdep.staffid = $tblstaff.staffid "
                . "LEFT JOIN $departments ON $departments.departmentid = $tblstaffdep.departmentid "
                . "where $departments.empresa_id = $empresa_id AND $tblstaff.empresa_id = $empresa_id and $tblstaff.staffid = $staff_id";

        return $this->db->query($sql)->result_array();
    }

    /**
     * 26/10/2022
     * @WannaLuiza
     * Retorna os registros recebidos pelo departamento
     */
    public function get_registros_recebidos_por_departamento() {
        $staff_id = get_staff_user_id();
        $empresa_id = $this->session->userdata('empresa_id');
        $tblstaffdep = 'tblstaff_departments';
        $tbl_ro = 'tbl_intranet_registro_ocorrencia';
        $tbl_categoria = 'tbl_intranet_categorias';
        $sql = "SELECT $tbl_ro.* from $tbl_ro "
                . "LEFT JOIN $tbl_categoria ON $tbl_categoria.id = $tbl_ro.categoria_id "
                . "LEFT JOIN $tblstaffdep ON $tblstaffdep.departmentid = $tbl_categoria.responsavel "
                . "WHERE $tbl_ro.empresa_id = $empresa_id AND $tbl_ro.deleted = 0 AND $tbl_categoria.empresa_id = $empresa_id and $tblstaffdep.staffid = $staff_id";

        //echo $sql; exit;
        return $this->db->query($sql)->result_array();
    }

    /**
     * 26/10/2022
     * @WannaLuiza
     * Retorna os registros recebidos pelo departamento
     */
    public function get_registros_recebidos_por_staffid() {
        $staff_id = get_staff_user_id();
        $empresa_id = $this->session->userdata('empresa_id');
        $tbl_atuantes_por_registro = 'tbl_intranet_registro_ocorrencia_atuantes_por_registro';
        $tbl_ro = 'tbl_intranet_registro_ocorrencia';
        $sql = "SELECT $tbl_ro.* from $tbl_atuantes_por_registro "
                . "LEFT JOIN $tbl_ro ON $tbl_ro.id = $tbl_atuantes_por_registro.registro_id "
                . "WHERE $tbl_ro.empresa_id = $empresa_id AND $tbl_ro.deleted = 0 AND $tbl_atuantes_por_registro.empresa_id = $empresa_id AND $tbl_atuantes_por_registro.deleted = 0 and $tbl_atuantes_por_registro.staff_id = $staff_id GROUP BY $tbl_ro.id";

        return $this->db->query($sql)->result_array();
    }

    /**
     * 19/10/2022
     * @WannaLuiza
     * Insere um valor para determinado campo de determinada categoria
     */
    public function add_campo_value($data = null) {

        $data['rel_type'] = 'r.o';
        $data['rel_id'] = $data['registro_id'];
        unset($data['registro_id']);
        //print_r($data); exit;
        if ($this->db->insert("tbl_intranet_categorias_campo_values", $data)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 19/10/2022
     * @WannaLuiza
     * Retorna um registro por id
     */
    public function get_ro_by_id($id = '') {

        $empresa_id = $this->session->userdata('empresa_id');
        $tbl_ro = 'tbl_intranet_registro_ocorrencia';
        $tbl_staff = 'tblstaff';
        $tbl_categoria = 'tbl_intranet_categorias';
        $tbl_status = 'tbl_intranet_registro_ocorrencia_status';
        $departments = 'tbldepartments';

        $sql = "SELECT $tbl_ro.*, $tbl_ro.status as status_ro, $departments.name, $tbl_categoria.head_1, $tbl_categoria.head_2, $tbl_categoria.head_3,$tbl_categoria.titulo,$tbl_ro.date_atribuido_a,$tbl_ro.date_end, $tbl_ro.validade, "
                . "$tbl_categoria.id as categoria_id, $tbl_categoria.statuses, $tbl_categoria.responsavel, $tbl_staff.firstname, $tbl_staff.lastname,  $tbl_status.id as rostatusid, "
                . "$tbl_status.statuscolor from $tbl_ro "
                . "LEFT JOIN $tbl_staff ON $tbl_staff.staffid = $tbl_ro.user_created "
                . "INNER JOIN $tbl_categoria ON $tbl_categoria.id = $tbl_ro.categoria_id "
                . "INNER JOIN $departments ON $departments.departmentid = $tbl_categoria.responsavel "
                . "LEFT JOIN $tbl_status ON $tbl_status.id = $tbl_ro.status "
                . "WHERE $tbl_ro.empresa_id = $empresa_id AND $tbl_ro.deleted = 0 AND $tbl_ro.id = $id"; //echo "$sql"; exit;

        return $this->db->query($sql)->row();
    }

    /**
     * 17/11/2022
     * @WannaLuiza
     * Retorna todos os status
     */
    public function get_ro_status($id = '') {
        $statuses = [];
        array_push($statuses, ['id' => '1', 'label' => 'ABERTO', 'color' => '#ff2d42']);
        array_push($statuses, ['id' => '2', 'label' => 'EM PROGRESSO', 'color' => '#03a9f4']);
        array_push($statuses, ['id' => '3', 'label' => 'FINALIZADO', 'color' => '#84c529']);
        array_push($statuses, ['id' => '4', 'label' => 'CANCELADO', 'color' => '#c0c0c0']);

        return $statuses;
    }

    /**
     * 21/11/2022
     * @WannaLuiza
     * Retorna uma categoria pelo hash
     */
    public function get_form_externo_by_hash($hash = '') {

        $this->db->where('hash', $hash);
        return $this->db->get(db_prefix() . '_intranet_categorias')->row();
    }

    /**
     * 21/11/2022
     * @WannaLuiza
     * Retorna a logo pela empresa
     */
    public function get_option_logo($empresa_id = null) {

        //$staff_id = get_staff_user_id();

        $sql = "SELECT * FROM tbloptions where name = 'company_logo' and empresa_id = $empresa_id";
        //echo $sql; exit;
        return $this->db->query($sql)->row();
    }

    public function get_option_nome_empresa($empresa_id = null) {

        //$staff_id = get_staff_user_id();

        $sql = "SELECT * FROM tbloptions where name = 'companyname' and empresa_id = $empresa_id";
        //echo $sql; exit;
        return $this->db->query($sql)->row();
    }

    /**
     * 19/10/2022
     * @WannaLuiza
     * Retorna os indexadores do ged
     */
    public function get_options($id = '') {

        $empresa_id = $this->session->userdata('empresa_id');
        $tbl_options = 'tbl_intranet_categorias_campo_options';

        $sql = "SELECT * from $tbl_options "
                . "WHERE $tbl_options.empresa_id = $empresa_id AND  $tbl_options.campo_id = $id AND  $tbl_options.deleted = 0  ORDER BY $tbl_options.id asc ";

        // echo $sql; exit;
        return $this->db->query($sql)->result_array();
    }

    /**
     * 26/10/2022
     * @WannaLuiza
     * Retorna the option
     */
    public function get_option($id) {
        $empresa_id = $this->session->userdata('empresa_id');
        $tbl_options = 'tbl_intranet_categorias_campo_options';

        $sql = "SELECT * from $tbl_options "
                . "WHERE $tbl_options.empresa_id = $empresa_id AND  $tbl_options.id = $id  ";

        // echo $sql; exit;
        return $this->db->query($sql)->row();
    }

    public function edit_ro($data, $id) {
        $this->db->where('id', $id);
        if ($this->db->update("tbl_intranet_registro_ocorrencia", $data)) {
            return true;
        } else {
            return false;
        }
    }

    public function get_coluna($coluna = '') {
        $this->db->select(db_prefix() . "_intranet_registro_ocorrencia.$coluna");
        $this->db->group_by($coluna);
        return $this->db->get(db_prefix() . '_intranet_registro_ocorrencia')->result_array();
    }

    public function get_count_ro($where = '') {

        $empresa_id = $this->session->userdata('empresa_id');
        $sql = "SELECT count(*) as quantidade from " . db_prefix() . "_intranet_registro_ocorrencia "
                . "WHERE  empresa_id = $empresa_id  and deleted = 0 $where";

        //echo $sql;
        $quantidade = $this->db->query($sql)->row();
        $quantidade = $quantidade->quantidade;
        return $quantidade;
    }

    public function atuante_in_ros($atuante_id) {
        $empresa_id = $this->session->userdata('empresa_id');
        $this->db->where('atuante_id', $atuante_id);
        $this->db->where('empresa_id', $empresa_id);

        return $this->db->get(db_prefix() . '_intranet_registro_ocorrencia_atuantes_por_registro')->result_array();
    }

    public function get_is_atuante($ro_id, $atuante_id) {
        $empresa_id = $this->session->userdata('empresa_id');
        $this->db->where('atuante_id', $atuante_id);
        $this->db->where('registro_id', $ro_id);
        $this->db->where('staff_id', get_staff_user_id());
        $this->db->where('empresa_id', $empresa_id);

        $result = $this->db->get(db_prefix() . '_intranet_registro_ocorrencia_atuantes_por_registro')->row();
        if (isset($result)) {
            return true;
        }
        return false;
    }

    public function get_ros_categoria_client($categoria, $client) {
        $empresa_id = $this->session->userdata('empresa_id');
        $tbl_ro = 'tbl_intranet_registro_ocorrencia';
        $tbl_ra = 'tbl_intranet_registro_atendimento';
        $tbl_cat = 'tbl_intranet_categorias';

        $sql = "SELECT $tbl_ro.*, $tbl_ra.protocolo, $tbl_ra.id as atendimento_id, $tbl_cat.titulo, $tbl_cat.responsavel  from $tbl_ro "
                . "LEFT JOIN $tbl_ra on $tbl_ra.id = $tbl_ro.registro_atendimento_id "
                . "LEFT JOIN $tbl_cat on $tbl_cat.id = $tbl_ro.categoria_id "
                . "WHERE $tbl_ro.empresa_id = $empresa_id and $tbl_ra.client_id = $client and $tbl_ro.deleted = 0 and $tbl_ra.deleted = 0 ";
        if ($categoria != '') {
            $sql .= "and $tbl_ro.categoria_id = $categoria ";
        }
        $sql .= "order by $tbl_ro.id desc ";

        //echo $sql; exit;
        return $this->db->query($sql)->result_array();
    }

    public function get_ro_by_category($id) {

        $sql = "SELECT count(*) as qtd FROM tbl_intranet_registro_ocorrencia
                INNER JOIN tbl_intranet_categorias on tbl_intranet_categorias.id = tbl_intranet_registro_ocorrencia.categoria_id
                where tbl_intranet_categorias.id = $id and tbl_intranet_registro_ocorrencia.deleted = 0";
        //echo $sql; exit;

        $resultado = $this->db->query($sql)->row();
        $resultado = $resultado->qtd;

        return $resultado;
    }

    public function get_data_fluxo_andamento($data) {

        $sql = "SELECT count(*) as qtd FROM tbl_intranet_registro_ocorrencia
                where  CAST(date_created AS DATE) = '$data' and tbl_intranet_registro_ocorrencia.deleted = 0";
//        /echo $sql; exit;

        $resultado = $this->db->query($sql)->row();
        $resultado = $resultado->qtd;

        return $resultado;
    }

    public function get_mes_fluxo_andamento($mes, $ano, $portal = false) {

        $sql = "SELECT count(*) as qtd FROM tbl_intranet_registro_ocorrencia
        LEFT JOIN tbl_intranet_categorias on tbl_intranet_categorias.id = tbl_intranet_registro_ocorrencia.categoria_id
                where  MONTH(date_created) = '$mes' AND YEAR(date_created) = '$ano' and tbl_intranet_registro_ocorrencia.deleted = 0";
        if ($portal == true) {
            $sql .= " and tbl_intranet_categorias.portal = 1";
        }
//echo $sql; exit;

        $resultado = $this->db->query($sql)->row();
        $resultado = $resultado->qtd;

        return $resultado;
    }

    public function get_departments_ros() {

        $sql = "SELECT COUNT(*) AS total, tbldepartments.name, tbldepartments.departmentid
        FROM tbl_intranet_registro_ocorrencia
        INNER JOIN tbl_intranet_categorias ON tbl_intranet_categorias.id = tbl_intranet_registro_ocorrencia.categoria_id
        INNER JOIN tbldepartments ON tbldepartments.departmentid = tbl_intranet_categorias.responsavel
        WHERE tbl_intranet_registro_ocorrencia.deleted = 0
        GROUP BY tbldepartments.departmentid";

//echo $sql; exit;

        return $this->db->query($sql)->result_array();
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

        $sql = "SELECT ";
                //. "(  GROUP_CONCAT(CONCAT(atuante_id, ':', staff_id, ';', department_id, ';', id,  ';', status, ';',date_finalizado) SEPARATOR ', ') FROM tbl_intranet_registro_ocorrencia_atuantes_por_registro WHERE registro_id = tbl_intranet_registro_ocorrencia.id) AS valores_concatenados, ";
        $sql .= "tbl_intranet_registro_ocorrencia.id, tbl_intranet_registro_ocorrencia.status, tbl_intranet_registro_atendimento.id AS registro_atendimento_id, tblclients.company, tblclients.dt_nascimento, tblclients.numero_carteirinha  $campos";
        if ($values) {
            $sql .= ",( SELECT GROUP_CONCAT( CONCAT('" . '"' . "',REPLACE(tbl_intranet_categorias_campo.nome, ' ', '') , tbl_intranet_categorias_campo.preenchido_por, '" . '"' . " : " . '"' . "' , "
                    . "tbl_intranet_categorias_campo_values.value, '" . '"' . "') SEPARATOR ', ' ) FROM tbl_intranet_categorias_campo_values
                        INNER JOIN tbl_intranet_categorias_campo ON tbl_intranet_categorias_campo.id = tbl_intranet_categorias_campo_values.campo_id
                        WHERE tbl_intranet_categorias_campo_values.rel_id = tbl_intranet_registro_ocorrencia.id AND tbl_intranet_categorias_campo.nome IN ($values) ) AS value";
        }
        if ($values2) {
            $sql .= ",( SELECT GROUP_CONCAT( CONCAT('" . '"' . "',REPLACE(tbl_intranet_categorias_campo.id, ' ', '') , '" . '"' . " : " . '"' . "' , tbl_intranet_categorias_campo_values.value, '" . '"' . "') SEPARATOR ', ')
        FROM tbl_intranet_categorias_campo_values
        INNER JOIN tbl_intranet_categorias_campo ON tbl_intranet_categorias_campo.id = tbl_intranet_categorias_campo_values.campo_id
        WHERE tbl_intranet_categorias_campo_values.rel_id = tbl_intranet_registro_ocorrencia.id AND tbl_intranet_categorias_campo.id IN ($values2)) AS value2";
        }
        $sql .= " FROM tbl_intranet_registro_ocorrencia
            LEFT JOIN tbl_intranet_registro_atendimento ON tbl_intranet_registro_atendimento.id = tbl_intranet_registro_ocorrencia.registro_atendimento_id
            LEFT JOIN tblclients ON tblclients.userid = tbl_intranet_registro_atendimento.client_id";
        $sql .= " WHERE tbl_intranet_registro_ocorrencia.deleted = 0 and tbl_intranet_registro_ocorrencia.categoria_id = $cat and tbl_intranet_registro_ocorrencia.status in($status) ";
        if ($start && $end) {
            $sql .= " and tbl_intranet_registro_ocorrencia.date_created BETWEEN '" . date("Y-m-d", strtotime(str_replace('/', '-', $start))) . " 00:00:00' AND '" . date("Y-m-d", strtotime(str_replace('/', '-', $end))) . " 23:59:59' ";
        }
        $sql .= "GROUP BY tbl_intranet_registro_ocorrencia.id, tbl_intranet_registro_ocorrencia.status, tbl_intranet_registro_atendimento.id, tblclients.company, tblclients.dt_nascimento, tblclients.numero_carteirinha $campos
";
        //echo $sql;
        //exit;

        $result = $this->db->query($sql)->result_array();
        //print_r($result); exit;
        return $result;
    }
}
