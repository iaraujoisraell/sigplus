<?php

defined('BASEPATH') or exit('No direct script access allowed');

 /**
 * 07/09/2022(Não sei quANDo criei, mas essa foi a data que botei o comentário)
 * @WannaLuiza
 * Intranet - Model para gestão de documentos
 */
class Documento_model extends App_Model {

    
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
            $this->db->update("tbl_intranet_documento", $data);
            return $data['id'];
            
        }
        unset($data['id']);
        
        //print_r($data); exit;
        if ($this->db->insert("tbl_intranet_documento", $data)) {
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
     * Cadastra/edita uma categoria
     */
    public function add_categoria($data = null, $id = 0) {
        if ($data) {

            if($data['fluxos']){
                $data['fluxos'] = implode(',', $data['fluxos']);
            } // TornANDo o id de fluxos uma string
            
            
            $data['empresa_id'] = $this->empresa_id;
            if ($id) {
                $this->db->where('id', $id);
                $data2['fluxos'] = 0;
                $this->db->update("tbl_intranet_documento_categoria", $data2);

                $this->db->where('id', $id);
                
                if ($this->db->update("tbl_intranet_documento_categoria", $data)) {
                    return $id;
                } else {
                    return false;
                }
            } else {

                $data['user_cadastro'] = $this->staffid;
                $data['data_cadastro'] = date('Y-m-d');
                $data['user_ultima_alteracao'] = $this->staffid;
                $data['data_ultima_alteracao'] = date('Y-m-d');
                if ($this->db->insert("tbl_intranet_documento_categoria", $data)) {
                    $id_insert = $this->db->insert_id();
                    return $id_insert;
                } else {
                    return false;
                }
            }
        }
    }
    
    /**
     * 07/09/2022(Não sei quANDo criei, mas essa foi a data que botei o comentário)
     * @WannaLuiza
     * Cadastra/edita um fluxo
     */
    public function add_fluxo($data = null, $id = 0) {

        if ($data) {

            $data['empresa_id'] = $this->empresa_id;
            if ($id) {
                $data['user_ultima_alteracao'] = get_staff_user_id();
                $data['data_ultima_aletaracao'] = date('Y-m-d');
                $this->db->where('id', $id);
                if ($this->db->update("tbl_intranet_documento_fluxo", $data)) {
                    return $id;
                } else {
                    return false;
                }
            } else {


                $data['user_cadastro'] = get_staff_user_id();
                $data['data_cadastro'] = date('Y-m-d');
                $data['user_ultima_alteracao'] = get_staff_user_id();
                $data['data_ultima_alteracao'] = date('Y-m-d');
                if ($this->db->insert("tbl_intranet_documento_fluxo", $data)) {
                    $id_insert = $this->db->insert_id();
                    return $id_insert;
                } else {
                    return false;
                }
            }
        }
    }
    
    /**
     * 07/09/2022(Não sei quANDo criei, mas essa foi a data que botei o comentário)
     * @WannaLuiza
     * Insere um staff(apenas um, tem que vir do controller linha por linha) no fluxo de aprovação
     */
    public function add_staff_fluxo($dados = null) {
        if ($dados) {
            if ($this->db->insert("tbl_intranet_documento_aprovacao", $dados)) {
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
            if ($this->db->insert("tbl_intranet_documento_send", $dados)) {
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
    public function add_obs($data = null) {
        
        if($data['obs']){
        if ($this->db->insert("tbl_intranet_documento_obs", $data)) {
            return $this->db->insert_id();
        }
        }
    }
    
    /**
     * 07/09/2022(Não sei quANDo criei, mas essa foi a data que botei o comentário)
     * @WannaLuiza
     * SALVANDO O LOG DO DOCUMENTO
     */
     public function add_log($dados = null) {
        $dados['staff_id'] = $this->staffid;
        $dados['date_created'] = date('Y-m-d h:i:s');
        $dados['user_created'] = $this->staffid;
        $dados['ip'] = $_SERVER['REMOTE_ADDR'];
        $dados['empresa_id'] = $this->empresa_id;
        
        return $this->db->insert("tbl_intranet_documento_log", $dados);
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
    
    /* ------------------------------------------ CONSULTA -------------------------------------------------*/
    
    /* ----------------- Consulta geral*/
    
    /**
     * 26/08/2022
     * @Israel Araujo
     * Retorna os documentos recebidos para o usuario vinculado
     */
    public function get_documentos_recebidos() {

        $staff_id = get_staff_user_id();
        $empresa_id = $this->session->userdata('empresa_id');
        $sql = "SELECT d.*, s.id as send_id, s.dt_lido, c.titulo as categoria FROM tbl_intranet_documento_send s
                INNER JOIN tbl_intranet_documento d ON d.id = s.documento_id
                INNER JOIN tbl_intranet_documento_categoria c ON c.id =  d.categoria_id
                WHERE s.staff_id = $staff_id AND d.deleted = 0 AND s.deleted = 0 AND s.lido = 1 AND d.empresa_id = $empresa_id and d.publicado = 1 ORDER BY sequencial ASC";

        //echo $sql; exit;
        return $this->db->query($sql)->result_array();
    }
    
    
    /**
     * 28/08/2022
     * @iSRAEL aRAUJO
     * Retorna os Documentos nao lidos para o usuario conectado
     */
    public function get_documentos_nao_lido() {

        $empresa_id = $this->session->userdata('empresa_id');
        $staff_id = get_staff_user_id();
        $sql = "SELECT d.*, s.id as send_id FROM tbl_intranet_documento_send s
                INNER JOIN tbl_intranet_documento d ON d.id = s.documento_id
                WHERE s.staff_id = $staff_id AND d.deleted = 0 and s.deleted = 0 AND s.lido = 0 AND d.empresa_id = $empresa_id";

        return $this->db->query($sql)->result_array();
    }
    
    /**
     * 28/08/2022
     * @ISRAEL ARAUJO
     * Retorna o Documento informado pelo ID(Somente os que o usuario conectado está vinculado)
     */
    public function get_documento_by_id($id = 0) {
        $staff =  get_staff_user_id();
        $empresa_id = $this->session->userdata('empresa_id');
        $sql = "SELECT d.*, s.lido, s.id as send_id
            FROM tbl_intranet_documento d
                INNER JOIN tbl_intranet_documento_send s ON s.documento_id = d.id
                WHERE s.id = $id AND d.empresa_id = $empresa_id AND s.staff_id = $staff AND d.deleted = 0 and s.deleted = 0";
       
        return $this->db->query($sql)->row();
    }
    
    /**
     * 07/09/2022(Não sei quANDo criei, mas essa foi a data que botei o comentário)
     * @WannaLuiza
     * Retorna todos os documentos da empresa conectada
     */
    public function get() {
        $tbl_documento = 'tbl_intranet_documento';
        $sql = "SELECT * FROM $tbl_documento "
                . "WHERE  $tbl_documento.empresa_id = $this->empresa_id AND $tbl_documento.deleted = 0";
        return $this->db->query($sql)->result_array();
    }
    
    /**
     * 07/09/2022(Não sei quANDo criei, mas essa foi a data que botei o comentário)
     * @WannaLuiza
     * Retorna um documento específico pelo id(independe se o usuarrio está no fluxo ou está nos destinatários)
     */
    public function get_documento($id = 0) {
        $tbl_documento = 'tbl_intranet_documento';
        $tbl_categoria = 'tbl_intranet_documento_categoria';
        $sql = "SELECT $tbl_documento.*, $tbl_categoria.titulo as nome_categoria, $tbl_categoria.cabecalho, $tbl_categoria.rodape FROM $tbl_documento "
                . "LEFT JOIN $tbl_categoria ON $tbl_categoria.id = $tbl_documento.categoria_id "
                . "WHERE  $tbl_documento.id = $id AND $tbl_documento.deleted = 0 AND $tbl_documento.empresa_id = $this->empresa_id";
       
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
        $tbl_log = 'tbl_intranet_documento_log';
        $tbl_staff = 'tblstaff';
        $tbl_documento = 'tbl_intranet_documento';
        $sql = "SELECT $tbl_log.*, $tbl_documento.*, $tbl_staff.firstname, $tbl_staff.lastname FROM $tbl_log "
                . "LEFT JOIN $tbl_documento ON $tbl_documento.id = $tbl_log.documento_id  "
                    ."LEFT JOIN $tbl_staff ON $tbl_staff.staffid = $tbl_log.user_created "
                . "WHERE  $tbl_log.documento_id = $id AND $tbl_log.empresa_id = $this->empresa_id ORDER BY $tbl_log.id ASC";
        //echo $sql; exit;
        return $this->db->query($sql)->result_array();
    }
    
    /**
     * 07/09/2022(Não sei quANDo criei, mas essa foi a data que botei o comentário)
     * @WannaLuiza
     * Retorna os destinatarios pelo id do documento
     */
    public function get_destinatarios($id = 0) {
        $tbl_documento_send = 'tbl_intranet_documento_send';
        $tbl_staff = 'tblstaff';
        $tbl_staff_dep = 'tblstaff_departments';
        $tbl_departments = 'tbldepartments';
                
        $sql = "SELECT $tbl_documento_send.*, $tbl_staff.firstname, $tbl_staff.lastname, $tbl_departments.name FROM $tbl_documento_send "
                . "LEFT JOIN $tbl_staff ON $tbl_staff.staffid = $tbl_documento_send.staff_id "
                . "LEFT JOIN $tbl_staff_dep ON $tbl_staff_dep.staffid = $tbl_staff.staffid "
                . "LEFT JOIN $tbl_departments ON $tbl_departments.departmentid = $tbl_staff_dep.departmentid "
                . "WHERE $tbl_documento_send.documento_id = $id AND $tbl_documento_send.empresa_id = $this->empresa_id AND $tbl_documento_send.deleted = 0";
        //echo $sql; exit;
        return $this->db->query($sql)->result_array();
    }
    
    /**
     * 07/09/2022(Não sei quANDo criei, mas essa foi a data que botei o comentário)
     * @WannaLuiza
     * Retorna o ultimo documento
     * OBS: Criei essa função pra quANDo a sequencia for automática, daí aqui ele mostra qual o ultimo(sem ser pelo id).
     */
    public function get_ultimo()
    {
        $tbl_documento = 'tbl_intranet_documento';
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
    public function get_log_cadastro($id = 0)
    {
        $tbl_documento = 'tbl_intranet_documento_log';
        $tbl_doc = 'tbl_intranet_documento';
        $tbl_staff = 'tblstaff';
        $sql = "SELECT $tbl_documento.*, $tbl_doc.descricao as objetivo, $tbl_staff.firstname, $tbl_staff.lastname FROM $tbl_documento "
                . "LEFT JOIN $tbl_doc ON $tbl_doc.id = $tbl_documento.documento_id "
                . "LEFT JOIN $tbl_staff ON $tbl_staff.staffid = $tbl_documento.user_created  "
                . "WHERE $tbl_documento.empresa_id = $this->empresa_id AND $tbl_documento.documento_id = $id ORDER BY $tbl_documento.id desc limit 1";
        //echo $sql; exit;
        return $this->db->query($sql)->row();
    }
    
    /* ----------------- Consulta de categoria*/
    
    /**
     * 07/09/2022(Não sei quANDo criei, mas essa foi a data que botei o comentário)
     * @WannaLuiza
     * Retorna todas as categorias da empresa conectada
     */
    public function get_categorias() {
        $tbl_categoria = 'tbl_intranet_documento_categoria';
        $sql = "SELECT * FROM $tbl_categoria "
                . "WHERE  $tbl_categoria.empresa_id = $this->empresa_id AND $tbl_categoria.deleted = 0";

        return $this->db->query($sql)->result_array();
    }
    
    /**
     * 12/12/2022
     * @WannaLuiza
     * Retorna todas as categorias da empresa conectada que o user é responsavel
     */
    public function get_categorias_responsavel() {
        $tbl_categoria = 'tbl_intranet_documento_categoria';
        $staff =  get_staff_user_id();
        $sql = "SELECT * FROM $tbl_categoria "
                . "WHERE  $tbl_categoria.empresa_id = $this->empresa_id AND $tbl_categoria.deleted = 0 AND responsavel = $staff";
        //echo $sql; exit;
        return $this->db->query($sql)->result_array();
    }
    
    /**
     * 07/09/2022(Não sei quANDo criei, mas essa foi a data que botei o comentário)
     * @WannaLuiza
     * Retorna uma categoria de documentos específica pelo id
     */
    public function get_categoria_by_id($id = '') {
        $tbl_categoria = 'tbl_intranet_documento_categoria';
        $sql = "SELECT * FROM $tbl_categoria "
                . "WHERE  $tbl_categoria.id = $id AND $tbl_categoria.empresa_id = $this->empresa_id AND deleted = 0";

        return $this->db->query($sql)->row();
    }
    
    /* ----------------- Consulta de fluxo*/
    
    /**
     * 07/09/2022(Não sei quANDo criei, mas essa foi a data que botei o comentário)
     * @WannaLuiza
     * Retorna todos os fluxos da empresa conectada
     */
    public function get_fluxos() {
        $tbl_fluxo = 'tbl_intranet_documento_fluxo';
        $sql = "SELECT * FROM $tbl_fluxo "
                . "WHERE  $tbl_fluxo.empresa_id = $this->empresa_id AND $tbl_fluxo.deleted = 0";

        return $this->db->query($sql)->result_array();
    }
    
    /**
     * 07/09/2022(Não sei quANDo criei, mas essa foi a data que botei o comentário)
     * @WannaLuiza
     * Retorna um fluxo específico pelo id
     */
    public function get_fluxo_by_id($id = 0) {
        $tbl_fluxo = 'tbl_intranet_documento_fluxo';
        $sql = "SELECT * FROM $tbl_fluxo "
                . "WHERE  $tbl_fluxo.id = $id AND $tbl_fluxo.deleted = 0 AND $tbl_fluxo.empresa_id = $this->empresa_id";

       // echo $sql; exit;
        return $this->db->query($sql)->row();
    }
    
    /* ----------------- Consulta de fluxo de aprovação*/
    
    /**
     * 07/09/2022(Não sei quANDo criei, mas essa foi a data que botei o comentário)
     * @WannaLuiza
     * Retorna o processo atual do documento
     */
    public function get_fluxo_atual($id = 0) {
        $tbl_documento = 'tbl_intranet_documento';
        $tbl_fluxo_aprovacao = 'tbl_intranet_documento_aprovacao';
        $tbl_staff = 'tblstaff';
        $sql = "SELECT $tbl_fluxo_aprovacao.*, $tbl_staff.firstname, $tbl_staff.lastname FROM $tbl_documento "
                . "LEFT JOIN $tbl_fluxo_aprovacao ON $tbl_fluxo_aprovacao.doc_id = $tbl_documento.id "
                . "LEFT JOIN $tbl_staff ON $tbl_staff.staffid = $tbl_fluxo_aprovacao.staff_id "
                . "WHERE  $tbl_documento.id = $id AND $tbl_documento.deleted = 0 AND $tbl_documento.empresa_id = $this->empresa_id AND $tbl_fluxo_aprovacao.deleted = 0 AND $tbl_fluxo_aprovacao.status = 0 "
                . "ORDER BY $tbl_fluxo_aprovacao.fluxo_sequencia ASC";
        //echo $sql;
        //exit;
        return $this->db->query($sql)->row();
    }
    
    
    /**
     * 07/09/2022(Não sei quANDo criei, mas essa foi a data que botei o comentário)
     * @WannaLuiza
     * Retorna todos os fluxos do documento(independente se já foi aprovado ou não)
     */
    public function get_fluxos_by_docid($id = 0) {
        $tbl_documento = 'tbl_intranet_documento';
        $tbl_fluxo_aprovacao = 'tbl_intranet_documento_aprovacao';
        $tbl_fluxo= 'tbl_intranet_documento_fluxo';
        $tbl_staff = 'tblstaff';
        $tbl_staff_dep = 'tblstaff_departments';
        $tbl_dep = 'tbldepartments';
        $sql = "SELECT $tbl_fluxo_aprovacao.*, $tbl_staff.firstname, $tbl_staff.lastname, $tbl_dep.name FROM $tbl_documento "
                . "LEFT JOIN $tbl_fluxo_aprovacao ON $tbl_fluxo_aprovacao.doc_id = $tbl_documento.id "
                . "LEFT JOIN $tbl_fluxo ON $tbl_fluxo.id = $tbl_fluxo_aprovacao.fluxo_id "
                . "LEFT JOIN $tbl_staff  ON $tbl_staff.staffid = $tbl_fluxo_aprovacao.staff_id "
                . "LEFT JOIN $tbl_staff_dep ON $tbl_staff_dep.staffid = $tbl_staff.staffid "
                . "LEFT JOIN $tbl_dep  ON $tbl_dep.departmentid = $tbl_staff_dep.departmentid "
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
        
        $tbl_documento = 'tbl_intranet_documento';
        $tbl_fluxo_aprovacao = 'tbl_intranet_documento_aprovacao';
        $tbl_staff = 'tblstaff';
        $sql = "SELECT $tbl_fluxo_aprovacao.*, $tbl_staff.firstname, $tbl_staff.lastname FROM $tbl_documento "
                . "LEFT JOIN $tbl_fluxo_aprovacao ON $tbl_fluxo_aprovacao.doc_id = $tbl_documento.id "
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
        $tbl_documento = 'tbl_intranet_documento';
        $tbl_fluxo_aprovacao = 'tbl_intranet_documento_aprovacao';
        $tbl_staff = 'tblstaff';
        $sql = "SELECT $tbl_fluxo_aprovacao.*, $tbl_staff.firstname, $tbl_staff.lastname FROM $tbl_documento "
                . "LEFT JOIN $tbl_fluxo_aprovacao ON $tbl_fluxo_aprovacao.doc_id = $tbl_documento.id "
                . "LEFT JOIN $tbl_staff  ON $tbl_staff.staffid = $tbl_fluxo_aprovacao.staff_id "
                . "WHERE  $tbl_documento.id = $id AND $tbl_documento.deleted = 0 AND $tbl_documento.empresa_id = $this->empresa_id AND $tbl_fluxo_aprovacao.deleted = 0 AND $tbl_fluxo_aprovacao.fluxo_sequencia != 0 "
                . "ORDER BY $tbl_fluxo_aprovacao.fluxo_sequencia DESC";
        //echo $sql;
        //exit;
        return $this->db->query($sql)->row();
    }

    /**
     * 07/09/2022(Não sei quANDo criei, mas essa foi a data que botei o comentário)
     * @WannaLuiza
     * Retorna um fluxo pela sequencia de determinado documento
     */
    public function get_fluxo_by_sequencia($sequencia = 0, $doc_id = 0) {
        $tbl_fluxo_aprovacao = 'tbl_intranet_documento_aprovacao';
        $tbl_staff = 'tblstaff';
        $sql = "SELECT $tbl_fluxo_aprovacao.*, $tbl_staff.* FROM $tbl_fluxo_aprovacao "
                . "LEFT JOIN $tbl_staff ON $tbl_staff.staffid = $tbl_fluxo_aprovacao.staff_id "
                . "WHERE  $tbl_fluxo_aprovacao.fluxo_sequencia = $sequencia AND $tbl_fluxo_aprovacao.deleted = 0 AND $tbl_fluxo_aprovacao.doc_id = $doc_id AND $tbl_fluxo_aprovacao.fluxo_sequencia != 0";
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
    public function get_doc_with_versions($id = 0)
    {
        $tbl_documento = 'tbl_intranet_documento';
        $sql = "SELECT * FROM $tbl_documento "
                . "WHERE  $tbl_documento.publicado = 1 AND $tbl_documento.empresa_id = $this->empresa_id AND $tbl_documento.deleted = 0 AND $tbl_documento.versao_atual != '1' AND ($tbl_documento.id_principal = $id or $tbl_documento.id = $id) ORDER BY $tbl_documento.id desc";
        //echo $sql; exit;
        return $this->db->query($sql)->result_array();
    }
    
    /**
     * 07/09/2022(Não sei quANDo criei, mas essa foi a data que botei o comentário)
     * @WannaLuiza
     * Retorna a ultima versão do documento
     */
    public function get_ultima_versao($id = 0)
    {
        $tbl_documento = 'tbl_intranet_documento';
        $sql = "SELECT * FROM $tbl_documento "
                . "WHERE  $tbl_documento.publicado = 1 AND $tbl_documento.empresa_id = $this->empresa_id AND $tbl_documento.deleted = 0 AND ($tbl_documento.id_principal = $id or $tbl_documento.id = $id) ORDER BY id desc limit 1";
        //echo $sql; exit;
        return $this->db->query($sql)->row();
    }
    

    /**
     * 07/09/2022(Não sei quANDo criei, mas essa foi a data que botei o comentário)
     * @WannaLuiza
     * Retorna o documento pelo id e suas versões
     */
    public function get_history_changes($id = 0)
    {
        $tbl_documento = 'tbl_intranet_documento_historico_versao';
        $tbl_doc = 'tbl_intranet_documento';
        $tbl_staff = 'tblstaff';
        $sql = "SELECT $tbl_documento.*, $tbl_staff.firstname, $tbl_staff.lastname, $tbl_doc.numero_versao FROM $tbl_documento "
                . "LEFT JOIN $tbl_staff on $tbl_staff.staffid = $tbl_documento.user_created "
                . "LEFT JOIN $tbl_doc on $tbl_doc.id = $tbl_documento.documento_id "
                . "WHERE  $tbl_documento.empresa_id = $this->empresa_id AND $tbl_documento.documento_id = $id or $tbl_documento.documento_pai = $id ORDER BY $tbl_documento.id asc";
        //echo $sql; exit;
        return $this->db->query($sql)->result_array();
    }
    
    /**
     * 29/09/2022
     * @WannaLuiza
     * Retorna o conteudo do documento pelo id
     */
    public function get_conteudo($id = 0)
    {
        $tbl_documento = 'tbl_intranet_documento';
        $tbl_documento_categoria = 'tbl_intranet_documento_categoria';
        $sql = "SELECT $tbl_documento.titulo, $tbl_documento.descricao, $tbl_documento.codigo, $tbl_documento.conteudo, $tbl_documento.data_publicacao, $tbl_documento_categoria.cabecalho, $tbl_documento_categoria.rodape FROM $tbl_documento "
                . "LEFT JOIN $tbl_documento_categoria on $tbl_documento_categoria.id = $tbl_documento.categoria_id "
                . "WHERE $tbl_documento.empresa_id = $this->empresa_id AND $tbl_documento.deleted = 0 and $tbl_documento.id = $id";
        //echo $sql; exit;
        return $this->db->query($sql)->row();
    }

}
