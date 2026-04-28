<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * 07/09/2022(Não sei quANDo criei, mas essa foi a data que botei o comentário)
 * @WannaLuiza
 * Intranet - Model para gestão de documentos
 */
class Cdc_model extends App_Model {

    public function __construct() {
        $this->empresa_id = $this->session->userdata('empresa_id'); // Empresa do usuário conectado
        $this->staffid = get_staff_user_id(); //Usuário conectado
        //$this->load->vars(['variavel_global' => 'variabel global']);
        parent::__construct();
    }

    /**
     * 07/09/2022(Não sei quANDo criei, mas essa foi a data que botei o comentário)
     * @WannaLuiza
     * Cadastra/edita o documento
     */
    public function add($data = null) {

        unset($data['data_avaliacao']);
        if ($data['id']) {

            $this->db->where('id', $data['id']);
            $this->db->update("tbl_intranet_cdc", $data);
            return $data['id'];
        }
        unset($data['id']);

        //print_r($data); exit;
        if ($this->db->insert("tbl_intranet_cdc", $data)) {
            //echo 'aaqui'; exit;
            $id_insert = $this->db->insert_id();

            return $id_insert;
        } else {
            return false;
        }
    }

    /**
     * 07/09/2022(Não sei quANDo criei, mas essa foi a data que botei o comentário)
     * @WannaLuiza
     * Insere um staff(apenas um, tem que vir do controller linha por linha) no fluxo de aprovação
     */
    public function add_staff_fluxo($dados = null) {
        if ($dados) {
            if ($this->db->insert("tbl_intranet_categorias_loading", $dados)) {
                $id_insert = $this->db->insert_id();
                return $id_insert;
            } else {
                return false;
            }
        }
    }

    /**
     * 07/09/2022(Não sei quANDo criei, mas essa foi a data que botei o comentário)
     * @WannaLuiza
     * Insere um staff(apenas um, tem que vir do controller linha por linha) nos destinatários
     */
    public function add_destinatario($dados = null) {
        if ($dados) {
            if ($this->db->insert("tbl_intranet_send", $dados)) {
                $id_insert = $this->db->insert_id();
                return $id_insert;
            } else {
                return false;
            }
        }
    }

    /**
     * 07/09/2022(Não sei quANDo criei, mas essa foi a data que botei o comentário)
     * @WannaLuiza
     * Insere uma uma observação para o documento
     */

    /**
     * 07/09/2022(Não sei quANDo criei, mas essa foi a data que botei o comentário)
     * @WannaLuiza
     * SALVANDO O LOG DO DOCUMENTO
     */
    public function add_log($dados = null) {
        //$dados['staff_id'] = $this->staffid;
        $dados['rel_type'] = 'cdc';
        $dados['date_created'] = date('Y-m-d H:i:s');
        $dados['user_created'] = $this->staffid;
        $dados['ip'] = $_SERVER['REMOTE_ADDR'];
        $dados['empresa_id'] = $this->empresa_id;

        return $this->db->insert("tbl_intranet_log", $dados);
    }

    /**
     * 15/09/2022
     * @WannaLuiza
     * Insere um histórico de atualização do documento
     */
    public function add_historico($dados = null) {
        if ($dados) {
            if ($this->db->insert("tbl_intranet_documento_historico_versao", $dados)) {
                $id_insert = $this->db->insert_id();
                return $id_insert;
            } else {
                return false;
            }
        }
    }

    /* ------------------------------------------ CONSULTA ------------------------------------------------- */

    /**
     * 28/08/2022
     * @ISRAEL ARAUJO
     * Retorna o Documento informado pelo ID(Somente os que o usuario conectado está vinculado)
     */
    public function get_documento_by_id($id = 0) {
        $staff = get_staff_user_id();
        $empresa_id = $this->session->userdata('empresa_id');
        $sql = "SELECT d.*, s.lido, s.id as send_id
            FROM tbl_intranet_documento d
                INNER JOIN tbl_intranet_send s ON s.documento_id = d.id
                WHERE s.id = $id AND d.empresa_id = $empresa_id AND s.staff_id = $staff AND d.deleted = 0 and s.deleted = 0";

        return $this->db->query($sql)->row();
    }

    /**
     * 07/09/2022(Não sei quANDo criei, mas essa foi a data que botei o comentário)
     * @WannaLuiza
     * Retorna todos os documentos da empresa conectada
     */
    public function get() {
        $tbl_documento = 'tbl_intranet_cdc';
        $sql = "SELECT * FROM $tbl_documento "
                . "WHERE  $tbl_documento.empresa_id = $this->empresa_id AND $tbl_documento.deleted = 0";
        return $this->db->query($sql)->result_array();
    }

    /**
     * 07/09/2022(Não sei quANDo criei, mas essa foi a data que botei o comentário)
     * @WannaLuiza
     * Retorna um documento específico pelo id(independe se o usuarrio está no fluxo ou está nos destinatários)
     */
    public function get_documento($id = 0, $if_read = false) {
        $tbl_documento = 'tbl_intranet_cdc';
        $tbl_categoria = 'tbl_intranet_categorias';
        $sql = "SELECT $tbl_documento.*, $tbl_categoria.titulo as nome_categoria, $tbl_categoria.responsavel as responsavel ";
        if ($if_read == true) {
            $sql .= ", tbl_intranet_send.dt_read ";
        }
        $sql .= "FROM $tbl_documento "
                . "LEFT JOIN $tbl_categoria ON $tbl_categoria.id = $tbl_documento.categoria_id ";
        if ($if_read == true) {
            $sql .= "LEFT JOIN tbl_intranet_send on tbl_intranet_send.rel_type = 'cdc' and tbl_intranet_send.rel_id = $tbl_documento.id and tbl_intranet_send.destino = " . get_staff_user_id() . " and "
                    . "tbl_intranet_send.staff = 1 and tbl_intranet_send.status = 1 ";
        }

        $sql .= "WHERE  $tbl_documento.id = $id AND $tbl_documento.deleted = 0 AND $tbl_documento.empresa_id = $this->empresa_id";
        //echo $sql; exit;
        return $this->db->query($sql)->row();
    }

    /**
     * 07/09/2022(Não sei quANDo criei, mas essa foi a data que botei o comentário)
     * @WannaLuiza
     * Retorna todas as observações de um documento específico 
     */
    public function get_obs($id = 0) {
        $tbl_obs = 'tbl_intranet_documento_obs';
        $tbl_staff = 'tblstaff';
        $sql = "SELECT $tbl_obs.*,$tbl_staff.firstname, $tbl_staff.lastname FROM $tbl_obs "
                . "LEFT JOIN $tbl_staff ON $tbl_staff.staffid = $tbl_obs.staff_id "
                . "WHERE  $tbl_obs.doc_id = $id AND $tbl_staff.empresa_id = $this->empresa_id AND $tbl_obs.empresa_id = $this->empresa_id ORDER BY $tbl_obs.id ASC";
        //echo $sql; exit;
        return $this->db->query($sql)->result_array();
    }

    /**
     * 07/09/2022(Não sei quANDo criei, mas essa foi a data que botei o comentário)
     * @WannaLuiza
     * Retorna O LOG COMPLETO DO DOCUMENTO
     */
    public function get_log($id = 0) {
        $tbl_log = 'tbl_intranet_log';
        $tbl_staff = 'tblstaff';
        $tbl_documento = 'tbl_intranet_cdc';
        $sql = "SELECT $tbl_log.*, $tbl_documento.*, $tbl_staff.firstname, $tbl_staff.lastname FROM $tbl_log "
                . "LEFT JOIN $tbl_documento ON $tbl_documento.id = $tbl_log.rel_id  "
                . "LEFT JOIN $tbl_staff ON $tbl_staff.staffid = $tbl_log.user_created "
                . "WHERE  $tbl_log.rel_id = $id AND $tbl_log.empresa_id = $this->empresa_id ORDER BY $tbl_log.id ASC";
        //echo $sql; exit;
        return $this->db->query($sql)->result_array();
    }

    /**
     * 07/09/2022(Não sei quANDo criei, mas essa foi a data que botei o comentário)
     * @WannaLuiza
     * Retorna o ultimo documento
     * OBS: Criei essa função pra quANDo a sequencia for automática, daí aqui ele mostra qual o ultimo(sem ser pelo id).
     */
    public function get_ultimo($departmentid) {
        $tbl_documento = 'tbl_intranet_cdc';
        $tbl_dep = 'tbldepartments';
        $sql = "SELECT * FROM $tbl_documento "
                . " INNER JOIN $tbl_dep on $tbl_dep.departmentid = $tbl_documento.setor_id "
                . " WHERE $tbl_dep.abreviado in (SELECT abreviado from $tbl_dep where $tbl_dep.departmentid = $departmentid) "
                . " AND $tbl_documento.publicado = 1 AND $tbl_documento.empresa_id = $this->empresa_id AND $tbl_documento.deleted = 0 "
                . " ORDER BY $tbl_documento.sequence_view DESC LIMIT 1";

        //echo $sql; exit;
        $row = $this->db->query($sql)->row();
        if ($row == '') {
            return '001';
        } else {
            return str_pad($row->sequence_view + 1, 3, '0', STR_PAD_LEFT);
        }
    }

    public function get_ultimo_all() {
        $tbl_documento = 'tbl_intranet_cdc';
        $sql = "SELECT * FROM $tbl_documento "
                . " WHERE $tbl_documento.publicado = 1 AND $tbl_documento.empresa_id = $this->empresa_id AND $tbl_documento.deleted = 0 "
                . " ORDER BY $tbl_documento.sequencial DESC LIMIT 1";

        return $this->db->query($sql)->row();
    }

    /**
     * 022/09/2022
     * @WannaLuiza
     * Retorna o primeiro log do documento(O de cadastro, o objetivo e quem publicou)
     */
    public function get_log_cadastro($id = 0) {
        $tbl_documento = 'tbl_intranet_log';
        $tbl_doc = 'tbl_intranet_cdc';
        $tbl_staff = 'tblstaff';
        $sql = "SELECT $tbl_documento.*, $tbl_doc.descricao as objetivo, $tbl_staff.firstname, $tbl_staff.lastname FROM $tbl_documento "
                . "LEFT JOIN $tbl_doc ON $tbl_doc.id = $tbl_documento.documento_id "
                . "LEFT JOIN $tbl_staff ON $tbl_staff.staffid = $tbl_documento.user_created  "
                . "WHERE $tbl_documento.empresa_id = $this->empresa_id AND $tbl_documento.documento_id = $id ORDER BY $tbl_documento.id desc limit 1";
        //echo $sql; exit;
        return $this->db->query($sql)->row();
    }

    /* ----------------- Consulta de categoria */

    /**
     * 07/09/2022(Não sei quANDo criei, mas essa foi a data que botei o comentário)
     * @WannaLuiza
     * Retorna todas as categorias da empresa conectada
     */
    public function get_categorias($withpermission = true) {
        $tbl_categoria = 'tbl_intranet_categorias';
        $tbl_deps = 'tbldepartments';
        $sql = "SELECT * FROM $tbl_categoria "
                . "INNER JOIN $tbl_deps on $tbl_deps.departmentid =  $tbl_categoria.responsavel "
                . "WHERE  $tbl_categoria.empresa_id = $this->empresa_id AND $tbl_categoria.deleted = 0 AND $tbl_categoria.rel_type = 'cdc' ";
        if ($withpermission == true) {
            $sql .= "AND $tbl_deps.departmentid in(SELECT departmentid from tblstaff_departments where tblstaff_departments.staffid = " . get_staff_user_id() . ")";
        }
        
        //echo $sql; exit;

        return $this->db->query($sql)->result_array();
    }

    /* ----------------- Consulta de fluxo de aprovação */

    /**
     * 07/09/2022(Não sei quANDo criei, mas essa foi a data que botei o comentário)
     * @WannaLuiza
     * Retorna o processo atual do documento
     */
    public function get_fluxo_atual($id = 0) {
        $tbl_documento = 'tbl_intranet_cdc';
        $tbl_fluxo_aprovacao = 'tbl_intranet_categorias_loading';
        $tbl_staff = 'tblstaff';
        $sql = "SELECT $tbl_fluxo_aprovacao.*, $tbl_staff.firstname, $tbl_staff.lastname FROM $tbl_documento "
                . "LEFT JOIN $tbl_fluxo_aprovacao ON $tbl_fluxo_aprovacao.rel_id = $tbl_documento.id "
                . "LEFT JOIN $tbl_staff ON $tbl_staff.staffid = $tbl_fluxo_aprovacao.staff_id "
                . "WHERE  $tbl_documento.id = $id AND $tbl_documento.deleted = 0 AND $tbl_documento.empresa_id = $this->empresa_id AND $tbl_fluxo_aprovacao.deleted = 0 AND $tbl_fluxo_aprovacao.status = 0 "
                . "ORDER BY $tbl_fluxo_aprovacao.fluxo_sequencia ASC";
//        echo $sql;
//        exit;
        return $this->db->query($sql)->row();
    }

    /**
     * 07/09/2022(Não sei quANDo criei, mas essa foi a data que botei o comentário)
     * @WannaLuiza
     * Retorna todos os fluxos do documento(independente se já foi aprovado ou não)
     */
    public function get_fluxos_by_docid($id = 0) {
        $tbl_documento = 'tbl_intranet_cdc';
        $tbl_fluxo_aprovacao = 'tbl_intranet_categorias_loading';
        $tbl_fluxo = 'tbl_intranet_categorias_fluxo';
        $tbl_staff = 'tblstaff';
        $tbl_staff_dep = 'tblstaff_departments';
        $tbl_dep = 'tbldepartments';
        $sql = "SELECT $tbl_fluxo_aprovacao.* FROM $tbl_documento "
                . "LEFT JOIN $tbl_fluxo_aprovacao ON $tbl_fluxo_aprovacao.rel_id = $tbl_documento.id "
                . "LEFT JOIN $tbl_fluxo ON $tbl_fluxo.id = $tbl_fluxo_aprovacao.fluxo_id "
                . "WHERE  $tbl_documento.id = $id AND $tbl_documento.deleted = 0 AND $tbl_documento.empresa_id = $this->empresa_id AND $tbl_fluxo_aprovacao.deleted = 0 AND $tbl_fluxo_aprovacao.fluxo_sequencia != 0 "
                . "ORDER BY $tbl_fluxo_aprovacao.fluxo_sequencia ASC";
        //echo $sql; exit;
        return $this->db->query($sql)->result_array();
    }

    /**
     * 07/09/2022(Não sei quANDo criei, mas essa foi a data que botei o comentário)
     * @WannaLuiza
     * Retorna o primeiro do fluxo de aprovação de um documento específico
     */
    public function get_fluxo_emissor($id = 0) {

        $tbl_documento = 'tbl_intranet_cdc';
        $tbl_fluxo_aprovacao = 'tbl_intranet_categorias_loading';
        $tbl_staff = 'tblstaff';
        $sql = "SELECT $tbl_fluxo_aprovacao.*, $tbl_staff.firstname, $tbl_staff.lastname FROM $tbl_documento "
                . "LEFT JOIN $tbl_fluxo_aprovacao ON $tbl_fluxo_aprovacao.rel_id = $tbl_documento.id "
                . "LEFT JOIN $tbl_staff  ON $tbl_staff.staffid = $tbl_fluxo_aprovacao.staff_id "
                . "WHERE  $tbl_documento.id = $id AND $tbl_documento.deleted = 0 AND $tbl_documento.empresa_id = $this->empresa_id AND $tbl_fluxo_aprovacao.deleted = 0 AND $tbl_fluxo_aprovacao.fluxo_sequencia != 0 "
                . "ORDER BY $tbl_fluxo_aprovacao.fluxo_sequencia ASC";
        //echo $sql;
        //exit;
        return $this->db->query($sql)->row();
    }

    /**
     * 07/09/2022(Não sei quANDo criei, mas essa foi a data que botei o comentário)
     * @WannaLuiza
     * Retorna o ultimo do fluxo de aprovação de um documento específico
     */
    public function get_fluxo_final($id = 0) {
        $tbl_documento = 'tbl_intranet_cdc';
        $tbl_fluxo_aprovacao = 'tbl_intranet_categorias_loading';
        $tbl_staff = 'tblstaff';
        $sql = "SELECT $tbl_fluxo_aprovacao.*, $tbl_staff.firstname, $tbl_staff.lastname FROM $tbl_documento "
                . "LEFT JOIN $tbl_fluxo_aprovacao ON $tbl_fluxo_aprovacao.rel_id = $tbl_documento.id "
                . "LEFT JOIN $tbl_staff  ON $tbl_staff.staffid = $tbl_fluxo_aprovacao.staff_id "
                . "WHERE  $tbl_documento.id = $id AND $tbl_documento.deleted = 0 AND $tbl_documento.empresa_id = $this->empresa_id AND $tbl_fluxo_aprovacao.deleted = 0 AND $tbl_fluxo_aprovacao.fluxo_sequencia != 0 "
                . "ORDER BY $tbl_fluxo_aprovacao.fluxo_sequencia DESC";

        return $this->db->query($sql)->row();
    }

    /**
     * 07/09/2022(Não sei quANDo criei, mas essa foi a data que botei o comentário)
     * @WannaLuiza
     * Retorna um fluxo pela sequencia de determinado documento
     */
    public function get_fluxo_by_sequencia($sequencia = 0, $doc_id = 0) {
        $tbl_fluxo_aprovacao = 'tbl_intranet_categorias_loading';
        $tbl_staff = 'tblstaff';
        $sql = "SELECT $tbl_fluxo_aprovacao.* FROM $tbl_fluxo_aprovacao "
                . "WHERE  $tbl_fluxo_aprovacao.fluxo_sequencia = $sequencia AND $tbl_fluxo_aprovacao.deleted = 0 AND $tbl_fluxo_aprovacao.rel_id = $doc_id AND $tbl_fluxo_aprovacao.fluxo_sequencia != 0";
        //echo $sql;
        //exit;
        return $this->db->query($sql)->row();
    }

    /* ----------------- Consulta de versoes */

    /**
     * 07/09/2022(Não sei quANDo criei, mas essa foi a data que botei o comentário)
     * @WannaLuiza
     * Retorna o documento pelo id e suas versões(agora só pega as versoes obsoletas)
     */
    public function get_versions($id = 0) {
        $tbl_documento = 'tbl_intranet_cdc';
        $sql = "SELECT $tbl_documento.*, tbl_intranet_categorias.titulo as cat FROM $tbl_documento "
                . "LEFT JOIN tbl_intranet_categorias on tbl_intranet_categorias.id = $tbl_documento.categoria_id "
                . "WHERE  $tbl_documento.publicado = 1 AND $tbl_documento.empresa_id = $this->empresa_id AND $tbl_documento.deleted = 0 AND $tbl_documento.versao_atual != '1' AND ($tbl_documento.id_principal = $id or $tbl_documento.id = $id) ORDER BY $tbl_documento.id desc";
        //echo $sql; exit;
        return $this->db->query($sql)->result_array();
    }

    /**
     * 07/09/2022(Não sei quANDo criei, mas essa foi a data que botei o comentário)
     * @WannaLuiza
     * Retorna o documento pelo id e suas versões
     */
    public function get_history_changes($id = 0) {
        $tbl_documento = 'tbl_intranet_documento_historico_versao';
        $tbl_doc = 'tbl_intranet_cdc';
        $tbl_staff = 'tblstaff';
        $sql = "SELECT $tbl_documento.*, $tbl_staff.firstname, $tbl_staff.lastname, $tbl_doc.numero_versao FROM $tbl_documento "
                . "LEFT JOIN $tbl_staff on $tbl_staff.staffid = $tbl_documento.user_created "
                . "LEFT JOIN $tbl_doc on $tbl_doc.id = $tbl_documento.documento_id "
                . "WHERE  $tbl_documento.empresa_id = $this->empresa_id AND $tbl_documento.documento_id = $id or $tbl_documento.documento_pai = $id ORDER BY $tbl_documento.id asc";
        //echo $sql; exit;
        return $this->db->query($sql)->result_array();
    }

    public function get_cdc_department() {


        $this->db->select(db_prefix() . '_intranet_cdc.*');

        $this->db->from(db_prefix() . '_intranet_cdc');
        $this->db->join(db_prefix() . '_intranet_send ', db_prefix() . '_intranet_send.rel_id = ' . db_prefix() . '_intranet_cdc.id', 'inner');
        $this->db->join(db_prefix() . 'departments ', db_prefix() . 'departments.departmentid = ' . db_prefix() . '_intranet_send.destino', 'inner');
        $this->db->join(db_prefix() . 'staff_departments', db_prefix() . 'staff_departments.departmentid = ' . db_prefix() . 'departments.departmentid', 'inner');
        $this->db->join(db_prefix() . 'staff', db_prefix() . 'staff.staffid = ' . db_prefix() . 'staff_departments.staffid', 'inner');
        //$this->db->join(db_prefix() . '_intranet_send AS send_staff', 'send_staff.destino = ' . db_prefix() . 'staff.staffid', 'left'); // Use 'AS' para dar um apelido à tabela

        $this->db->where(db_prefix() . '_intranet_send.rel_type', 'cdc');
        $this->db->where(db_prefix() . 'staff.staffid', get_staff_user_id());
        $this->db->where(db_prefix() . "_intranet_cdc.id not in(Select rel_id from tbl_intranet_send  where staff = 1 and  rel_type = 'cdc' and status = 1 and destino = " . get_staff_user_id() . ")");

        $this->db->where(db_prefix() . '_intranet_send.staff', 0);
        $this->db->where(db_prefix() . '_intranet_cdc.publicado', 1);
        $this->db->where(db_prefix() . '_intranet_cdc.versao_atual', 1);
        $this->db->where(db_prefix() . '_intranet_cdc.required', 1);
        $this->db->where(db_prefix() . '_intranet_cdc.deleted', 0);
        $this->db->group_by(db_prefix() . '_intranet_cdc.id');
        $docs = $this->db->get()->result_array();
        //echo $this->db->last_query();
        //print_r($docs); exit;

        return $docs;
    }

    /**
     * 28/08/2022
     * @iSRAEL aRAUJO
     * Retorna os Documentos nao lidos para o usuario conectado
     */
    public function get_documentos_nao_lido() {

        $empresa_id = $this->session->userdata('empresa_id');
        $staff_id = get_staff_user_id();
        $sql = "SELECT d.*, s.id as send_id FROM tbl_intranet_send s
                INNER JOIN tbl_intranet_documento d ON d.id = s.documento_id
                WHERE s.staff_id = $staff_id AND d.deleted = 0 and s.deleted = 0 AND s.lido = 0 AND d.empresa_id = $empresa_id";

        return $this->db->query($sql)->result_array();
    }

    public function get_ciencia($id) {

        $this->db->where(db_prefix() . '_intranet_send.rel_type', 'cdc');
        $this->db->where(db_prefix() . '_intranet_send.rel_id', $id);
        $this->db->where(db_prefix() . '_intranet_send.staff', 1);
        $this->db->where(db_prefix() . '_intranet_send.status', 1);
        $this->db->where(db_prefix() . '_intranet_send.deleted', 0);
        $this->db->where(db_prefix() . '_intranet_send.destino', get_staff_user_id());

        $result = $this->db->get(db_prefix() . '_intranet_send')->result_array();
        if (count($result) > 0) {
            return true;
        }
        return false;
    }

    public function search_cdc_by_title_code($value = '', $without_id = '') {

        $table = 'tbl_intranet_cdc';
        $sql = "SELECT * FROM  $table ";
        if ($value == '') {
            $sql .= " WHERE (titulo = '' OR codigo = '')";
        } else {
            $sql .= " WHERE (titulo LIKE '%$value%' OR codigo like '%$value%')";
        }
        if ($without_id) {
            $sql .= " AND id != $without_id AND id NOT IN(SELECT linked from tbl_intranet_links where linker = $without_id AND rel_type_from = 'cdc' AND rel_type_from = 'cdc')";
        }
        $sql .= ' AND deleted  = 0 AND publicado = 1';
        //echo $sql;
        return $this->db->query($sql)->result_array();
    }

    public function insert_link($data) {

        if ($data != null) {
            $data['rel_type_from'] = 'cdc';
            $data['rel_type_to'] = 'cdc';
            $data['date_created'] = date('Y-m-d H:i:s');
            $data['user_created'] = get_staff_user_id();
            $data['empresa_id'] = $this->session->userdata('empresa_id');
            $this->db->insert(db_prefix() . '_intranet_links', $data);
            return true;
        }
        return false;
    }

    public function return_links($linker, $rel_type_from, $rel_type_to = '') {


//        $this->db->select("*, " . db_prefix() . "_intranet_links.id as link_id, GROUP_CONCAT((SELECT id FROM " . db_prefix() . "_intranet_request WHERE rel_type_from = 'links' AND linker = " . db_prefix() . "_intranet_links.id)) as secondary_results");
//
//        $this->db->from(db_prefix() . '_intranet_links');
//
//        if ($rel_type_to) {
//            $this->db->join(db_prefix() . '_intranet_' . $rel_type_to, db_prefix() . '_intranet_' . $rel_type_to . '.id = ' . db_prefix() . '_intranet_links.linked', 'inner');
//            $this->db->where(db_prefix() . '_intranet_links.rel_type_to', $rel_type_to);
//        }
//
//        $this->db->where(db_prefix() . '_intranet_links.empresa_id', $this->session->userdata('empresa_id'));
//        $this->db->where(db_prefix() . '_intranet_links.linker', $linker);
//        $this->db->where(db_prefix() . '_intranet_links.rel_type_from', $rel_type_from);
//
//        return $this->db->get()->result_array();

        $this->db->select("*, " . db_prefix() . "_intranet_links.id as link_id");

        $this->db->from(db_prefix() . '_intranet_links');

        if ($rel_type_to) {
            $this->db->join(db_prefix() . '_intranet_' . $rel_type_to, db_prefix() . '_intranet_' . $rel_type_to . '.id = ' . db_prefix() . '_intranet_links.linked', 'inner');

            $this->db->where(db_prefix() . '_intranet_links.rel_type_to', $rel_type_to);
        }

        $this->db->where(db_prefix() . '_intranet_links.empresa_id', $this->session->userdata('empresa_id'));
        $this->db->where(db_prefix() . '_intranet_links.linker', $linker);
        $this->db->where(db_prefix() . '_intranet_links.rel_type_from', $rel_type_from);

// Subquery to get secondary_results
        $subquery = "(SELECT id FROM " . db_prefix() . "_intranet_request WHERE rel_type_from = 'links' AND linker = " . db_prefix() . "_intranet_links.id)";
        $this->db->select($subquery . ' as has', false);

        return $this->db->get()->result_array();
    }

    public function insert_request($data) {

        if ($data != null) {
            $data['date_created'] = date('Y-m-d H:i:s');
            $data['user_created'] = get_staff_user_id();
            $data['empresa_id'] = $this->session->userdata('empresa_id');
            $this->db->insert(db_prefix() . '_intranet_request', $data);

            return $this->db->insert_id();
        }
        return false;
    }

    public function get_requests_for_departmentid() {

        $sql = "SELECT tbl_intranet_request.*, tbl_intranet_links.linked as cdc_from
            FROM tbl_intranet_request
            inner join tbl_intranet_links on tbl_intranet_links.id = tbl_intranet_request.linker
            inner join tbldepartments on tbldepartments.departmentid = tbl_intranet_request.responsible_response
           inner join tblstaff_departments on tblstaff_departments.departmentid = tbldepartments.departmentid
           where tbl_intranet_request.status = 0 and tbl_intranet_request.rel_type_to = 'cdc' and tblstaff_departments.staffid =" . get_staff_user_id();

        return $this->db->query($sql)->result_array();
    }
}
