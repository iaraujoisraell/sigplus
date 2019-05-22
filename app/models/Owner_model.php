<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Owner_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }
    

    /**********************************************************************
     ************************** E M P R E S A S *************************** 
     **********************************************************************/
   
    //INSERT
     public function addEmpresa($data  = array())
    {  
     if ($this->db->insert('empresa', $data)) {
          $id = $this->db->insert_id();
         return $id;
        }
        return false;
    }
    
     public function getAllEmpresas() {
        $this->db->select('*')
        ->order_by('razaoSocial', 'asc');
        $q = $this->db->get('empresa');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
    public function getEmpresaById($id)
    {
        $q = $this->db->get_where('empresa', array('id' => $id), 1);
     
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
         
    }
    
    public function getModuloEmpresaById($empresa, $modulo)
    {
        $q = $this->db->get_where('empresa_modulos', array( 'sig_empresa_id' => $empresa, 'sig_modulos_id' => $modulo), 1);
     
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
         
    }
    
    //INSERT
     public function addEmpresaModulo($data  = array())
    {  
     if ($this->db->insert('empresa_modulos', $data)) {
          $id = $this->db->insert_id();
         return $id;
        }
        return false;
    }
    
    
    
    //DELETE
     public function deleteEmpresaModulo($empresa)
    {  
     if($empresa){
      $this->db->delete('empresa_modulos', array('sig_empresa_id' => $empresa));
        return true;
        }
        return false;
    }
    
    
    /**********************************************************************
     ************************** Tabelas com níveis hierárquicos *************************** 
     **********************************************************************/
    
     public function getTablesHierarquicoRaiz($tabela, $raiz) {
        // $statement = 'SHOW TABLES';
        $empresa = $this->session->userdata('empresa');
        
        $statement = "select * from $tabela where id > 0 ";
        $statement .= " and empresa_id = $empresa";
        $statement .= " and raiz       = $raiz and pai = $raiz group by pai";
       
       // echo $statement; exit;
        $q = $this->db->query($statement);
       // $q = $this->db->get('SHOW TABLES');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
    public function getTablesHierarquicoPais($tabela, $pai) {
        // $statement = 'SHOW TABLES';
        $empresa = $this->session->userdata('empresa');
        
        $statement = "select * from $tabela where id > 0 ";
        $statement .= " and empresa_id = $empresa";
        $statement .= " and pai       = $pai ";
       
       // echo $statement; exit;
        $q = $this->db->query($statement);
       // $q = $this->db->get('SHOW TABLES');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
    
    /**********************************************************************
     ************************** M O D U L O S *************************** 
     **********************************************************************/
    //INSERT
     public function addModulo($data  = array())
    {  
     if ($this->db->insert('modulos', $data)) {
          $id = $this->db->insert_id();
         return $id;
        }
        return false;
    }
    
    public function getAllModulos() {
        $this->db->select('*')
        ->order_by('id', 'asc');
        $q = $this->db->get('modulos');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
    //Retorna todos os módulos da empresa
    public function getAllModulosByEmpresa($empresa) {
        $q = $this->db->get_where('empresa_modulos', array('sig_empresa_id' => $empresa));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
    public function getModuloById($id)
    {
        $q = $this->db->get_where('modulos', array('id' => $id), 1);
     
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
         
    }
    
    public function updateModulo($id, $data  = array())
    {  
     if ($this->db->update('modulos', $data, array('id' => $id))) {
        return true;
        }
        return false;
    }
    
    public function deleteModulos($id)
    {  
     if($id){
      $this->db->delete('modulos', array('id' => $id));
        return true;
        }
        return false;
    }
    /**********************************************************************
     ************************** LOG REGISTROS *************************** 
     **********************************************************************/
    
   //INSERT
     public function addLog($data  = array())
    {  
     if ($this->db->insert('logs', $data)) {
         
         return true;
        }
        return false;
    }
    
    
    
    /**********************************************************************************************
     *************************************** CADASTROS BÁSICOS ************************************
     **********************************************************************************************/
    
    public function getTablesCadastroBasico($tabela, $restrito, $sortable) {
        // $statement = 'SHOW TABLES';
        $empresa = $this->session->userdata('empresa');
        
        $statement = "select * from $tabela where id > 0 ";
        if($restrito == 1){
            $statement .= " and empresa_id = $empresa";
        }
        
        if($sortable == 1){
            $statement .= " and pai = '' ";
        }
        $statement .= " order by id asc";
        //echo $statement; exit;
        $q = $this->db->query($statement);
       // $q = $this->db->get('SHOW TABLES');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
//retorna os dados de um ID de uma determinada tabela
    public function getDadosTablesCadastroById($tabela, $id) {
        // $statement = 'SHOW TABLES';
        $statement = "select * from $tabela where id = $id";
        //echo $statement; exit;
        $q = $this->db->query($statement);
       // $q = $this->db->get('SHOW TABLES');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
     //INSERT
     public function addCadastro($tabela, $data  = array())
    {  
     if ($this->db->insert($tabela, $data)) {
          $id = $this->db->insert_id();
         return $id;
        }
        return false;
    }
    
     //UPDATE
     public function updateCadastro($id, $tabela, $data  = array())
    {  
     if ($this->db->update($tabela, $data, array('id' => $id))) {
        return true;
        }
        return false;
    }
    
    //DELETE
     public function deleteCadastro($id, $tabela)
    {  
     if($id){
      $this->db->delete($tabela, array('id' => $id));
        return true;
        }
        return false;
    }
    
    /************************************************
     **************** TABELAS ***********************
     ************************************************/
    
    public function getAllTables_BD() {
         $statement = 'SHOW TABLES';
      //  $statement = 'select * from sig_tabelas';
        //echo $statement; exit;
        $q = $this->db->query($statement);
       // $q = $this->db->get('SHOW TABLES');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
    public function getAllTables() {
        // $statement = 'SHOW TABLES';
        $statement = 'select * from sig_tabelas';
        //echo $statement; exit;
        $q = $this->db->query($statement);
       // $q = $this->db->get('SHOW TABLES');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
    public function getTablesCadastroBasico_db_empresa($tabela, $restrito, $sortable) {
        
        $usuario = $this->session->userdata('user_id');
        $users_dados = $this->site->geUserByID($usuario);
        $modulo_atual_id = $users_dados->modulo_atual;
        $projeto_atual_id = $users_dados->projeto_atual;
        $empresa = $users_dados->empresa_id;
        $empresa_dados = $this->owner_model->getEmpresaById($empresa);
        $tabela_empresa = $empresa_dados->tabela_cliente;
        
        $db_empresa = $this->load->database($tabela_empresa, TRUE); 
        
        $empresa = $this->session->userdata('empresa');
        
        $statement = "select * from $tabela where id > 0 ";
        if($restrito == 1){
            $statement .= " and empresa_id = $empresa";
        }
        
        if($sortable == 1){
            $statement .= " and pai = 0";
        }
       // echo $statement; exit;
        $q = $db_empresa->query($statement);
       // $q = $this->db->get('SHOW TABLES');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
    
    
    public function getTableById($id)
    {
        $q = $this->db->get_where('tabelas', array('id' => $id), 1);
     
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
         
    }
    //BUSCA PELO NOME DA TABELA
    public function getTableByTabela($tabela)
    {
        $q = $this->db->get_where('tabelas', array('tabela' => $tabela), 1);
     
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
         
    }
    
     public function addtable($data  = array())
    {  
     if ($this->db->insert('tabelas', $data)) {
         $id = $this->db->insert_id();
         return $id;
        }
        return false;
    }
    
    // LE AS COLUNAS DA TABELA DO BD
    public function getAllColumns_BD($tabela) {
         $statement = "show columns from $tabela";
      //  $statement = 'select * from sig_tabelas';
        //echo $statement; exit;
        $q = $this->db->query($statement);
       // $q = $this->db->get('SHOW TABLES');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
    
     public function getAllCamposTables($table) {
        $statement = "select * from sig_tabelas_campo where tabela_id = $table";
      // echo $statement; exit;
        $q = $this->db->query($statement);
       // $q = $this->db->get('SHOW TABLES');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
    public function getAllCamposTables_fk($table) {
        $statement = "select * from sig_tabelas_campo where tabela_id = $table and fk != ''";
       //echo $statement; exit;
        $q = $this->db->query($statement);
       // $q = $this->db->get('SHOW TABLES');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
     public function getCampoByCampo($campo, $tabela)
    {
        $q = $this->db->get_where('tabelas_campo', array('campo' => $campo, 'tabela_id' => $tabela), 1);
     
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
         
    }
    
    //retorna o campo pelo ID
     public function getCampoById($id)
    {
        $q = $this->db->get_where('tabelas_campo', array('id' => $id), 1);
     
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
         
    }
    
    /*
     * TODOS OS CAMPOS COM A LISTA HABILITADA
     */
     public function getAllCamposTablesLista($table) {
        $statement = "select * from sig_tabelas_campo where tabela_id = $table and lista = 1";
       //echo $statement; exit;
        $q = $this->db->query($statement);
       // $q = $this->db->get('SHOW TABLES');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
    
    /*
     * Retorna a tabela passada por parametro
     */
     public function getDadosTables($table, $sessao, $campo) {
        $empresa = $this->session->userdata('empresa');
        if($table == 'sig_users'){
        $statement = "select * from $table where active = 1 and empresa_id = $empresa ";    
        }else{
        $statement = "select * from $table  ";    
        }
        
        if($sessao){
            if($sessao == 'empresa'){
                 $empresa = $this->session->userdata('empresa');
                 $statement .= " where $campo = $empresa";
            }
        }
       //echo $statement; exit;
        $q = $this->db->query($statement);
       // $q = $this->db->get('SHOW TABLES');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    // usuários ativos sem setor
    public function getDadosTablesUsers() {
        $empresa = $this->session->userdata('empresa');
        $statement = "select * from sig_users "
                . " "
                . " where active = 1 and empresa_id = $empresa ";    
        
      
       //echo $statement; exit;
        $q = $this->db->query($statement);
       // $q = $this->db->get('SHOW TABLES');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
    //retorna o campo pelo ID
     public function getTableByNameAndId($table, $id)
    {
        $q = $this->db->get_where($table, array('id' => $id), 1);
     
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
         
    }
    
    /*
     * TODOS OS CAMPOS COM o CADASTRO HABILITADO
     */
     public function getAllCamposTablesCadastro($table) {
        $statement = "select * from sig_tabelas_campo where tabela_id = $table and cadastro = 1";
      // echo $statement; exit;
        $q = $this->db->query($statement);
       // $q = $this->db->get('SHOW TABLES');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
    public function addCampo($data  = array())
    {  
     if ($this->db->insert('tabelas_campo', $data)) {
         $id = $this->db->insert_id();
         return $id;
        }
        return false;
    }
    
    
    public function updateCampo($id, $data  = array())
    {  
     if ($this->db->update('tabelas_campo', $data, array('id' => $id))) {
        return true;
        }
        return false;
    }
    
    /***********************************************************************
     ***************************B O T Õ E S*********************************** 
     ************************************************************************/
    //RETORNA TODOS OS BOTOES CADASTRADOS
    public function getAllBotoes() {
        // $statement = 'SHOW TABLES';
        $statement = 'select * from sig_botoes';
        //echo $statement; exit;
        $q = $this->db->query($statement);
       // $q = $this->db->get('SHOW TABLES');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
     public function getBotaoCadastroById($id)
    {
        $q = $this->db->get_where('botoes', array('id' => $id), 1);
     
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
         
    }
    
    //RETORNA TODOS OS BOTOES CADASTRADOS
    public function getAllBotoesByTabela($tabela) {
        // $statement = 'SHOW TABLES';
        $statement = "select * from sig_tabelas_lista_botoes where tabela_id = $tabela";
        //echo $statement; exit;
        $q = $this->db->query($statement);
       // $q = $this->db->get('SHOW TABLES');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
     public function deleteBotaoTabela($tabela_id)
    {  
     if($tabela_id){
      $this->db->delete('tabelas_lista_botoes', array('tabela_id' => $tabela_id));
        return true;
        }
        return false;
    }
    
    //ADICIONA OS BOTOES SELECIONADOS PARA UMA TABELA
    public function addBotaoTabela($tabela_id,$data  = array())
    {  
     
        
     if ($this->db->insert('tabelas_lista_botoes', $data)) {
         $id = $this->db->insert_id();
         return $id;
        }
        return false;
    }
    
    public function getBotaoTabelaById($id, $tabela)
    {
        $q = $this->db->get_where('tabelas_lista_botoes', array('botao_id' => $id, 'tabela_id' => $tabela), 1);
     
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
         
    }

    /*
     * CAMPOS FK 
     */
    //DELTE
    public function deleteCamposTabelaFK($tabela_id)
    {  
     if($tabela_id){
      $this->db->delete('tabelas_campos_fk', array('tabela_id' => $tabela_id));
        return true;
        }
        return false;
    }
    
    //ADICIONA OS BOTOES SELECIONADOS PARA UMA TABELA
    public function addCamposTabelaFK($data  = array())
    {  
     
        
     if ($this->db->insert('tabelas_campos_fk', $data)) {
         $id = $this->db->insert_id();
         return $id;
        }
        return false;
    }
    
     public function getAllCamposFK($table) {
        $statement = "select * from sig_tabelas_campos_fk where tabela_id = $table";
       //echo $statement; exit;
        $q = $this->db->query($statement);
       // $q = $this->db->get('SHOW TABLES');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
    /*
     * retorna os campos escolhidos para serem exibidos no dropdown
     */
    public function getAllCamposDropdownFK($table_id, $table_fk) {
        $statement = "select * from sig_tabelas_campos_fk where tabela_id = $table_id and tabela_fk = $table_fk";
       //echo $statement; exit;
        $q = $this->db->query($statement);
       // $q = $this->db->get('SHOW TABLES');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
    /*
     * CONTROLE
     */
    
    public function getAllControle() {
        // $statement = 'SHOW TABLES';
        $statement = 'select * from sig_controle';
        //echo $statement; exit;
        $q = $this->db->query($statement);
       // $q = $this->db->get('SHOW TABLES');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
    //retorna o controle pelo ID
     public function getControleById( $id)
    {
        $q = $this->db->get_where('controle', array('id' => $id), 1);
     
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
         
    }
    
    /*
     * FUNCIONALIDADE
     */
    //RETORNA TODOS OS BOTOES CADASTRADOS
    public function getAllfuncionalidades() {
        // $statement = 'SHOW TABLES';
        $statement = 'select * from sig_funcionalidades';
        //echo $statement; exit;
        $q = $this->db->query($statement);
       // $q = $this->db->get('SHOW TABLES');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
    //retorna a função pelo ID
     public function getFuncaoById( $id)
    {
        $q = $this->db->get_where('funcionalidades', array('id' => $id), 1);
     
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
         
    }
    
    
    
    /*
     * REGISTRA O MÓDULO ATUAL
     */
    public function updateModuloAtual($id, $data)
    {  
       
     if ($this->db->update('users', $data, array('id' => $id))) {
        return true;
        }
        return false;
    }
    
    /***********************************************************************
     ***************************M E N U S*********************************** 
     ************************************************************************/
    
     public function getAllMenus() {
        $this->db->select('*')
        ->order_by('descricao', 'asc');
        $q = $this->db->get('menu');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
    //retorna o menu pelo ID
     public function getMenuById( $id)
    {
        $q = $this->db->get_where('menu', array('id' => $id), 1);
     
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
         
    }
    
    public function getMenusPaiByModulo($modulo)
    {
        $projetos = $this->projetos_model->getProjetoAtualByID_completo();
        $status_projeto = $projetos->status;
        
        $this->db->select('*')
        ->order_by('ordem', 'asc');
        
        if($modulo == 4){
            
        if($status_projeto == 'EM AGUARDO'){
        $q = $this->db->get_where('menu', array('modulos_id' => $modulo, 'pai' => 0, 'status_aguardando' => 1));    
        }else {
        $q = $this->db->get_where('menu', array('modulos_id' => $modulo, 'pai' => 0));    
        }
        
        }else{
        $q = $this->db->get_where('menu', array('modulos_id' => $modulo, 'pai' => 0));    
        }
        
     
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
         
    }
    
    public function getSubMenusByPai($pai)
    {
        $this->db->select('*')
        ->order_by('ordem', 'asc');
        $q = $this->db->get_where('menu', array('pai' => $pai));
     
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
         
    }
    
    /**********************************************************************
     ************************** M O D U L O S *************************** 
     **********************************************************************/
    //INSERT
     public function addSetor($data  = array())
    {  
     if ($this->db->insert('setores', $data)) {
          $id = $this->db->insert_id();
         return $id;
        }
        return false;
    }
    
   
    
    //Retorna todos os módulos da empresa
    public function getAllSetorByEmpresa() {
        $empresa = $this->session->userdata('empresa'); 
        $q = $this->db->get_where('setores', array('empresa_id' => $empresa));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
    public function getSetorById($id)
    {
        $q = $this->db->get_where('modulos', array('id' => $id), 1);
     
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
         
    }
    
    public function updateSetor($id, $data  = array())
    {  
     if ($this->db->update('modulos', $data, array('id' => $id))) {
        return true;
        }
        return false;
    }
    
    public function deleteSetor($id)
    {  
     if($id){
      $this->db->delete('modulos', array('id' => $id));
        return true;
        }
        return false;
    } 
    
    /************** FIM SETOR *******************************************/
    
    
    /******************************************* USER ***********/
     public function  getAllUsersByEmpresa()
    {
        $empresa = $this->session->userdata('empresa'); 
         $statement = "SELECT u.id as id, u.first_name, s.nome as setor, u.email, u.active, u.consultor, u.cargo, u.confirmou_email FROM sig_users u
                    left join sig_users_setor us on us.users_id = u.id
                    left join sig_setores s on s.id = us.setores_id
                    where u.empresa_id = $empresa
                    order by first_name asc";
       // echo $statement; exit;
        $q = $this->db->query($statement);
        
       // $q = $this->db->get_where('users', array('empresa_id' => $id));
     
         if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
         
    }
    
    
    
    
    
    /*********************************************************************
     * TABELAS DO PROJETO
     *********************************/
    
    public function getTableProjectById($id)
    {
        $q = $this->db->get_where('tabelas', array('id' => $id), 1);
     
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
         
    }
    
    
    /*
     * LABELS MENU
     */
    
     /*
     * ATUALIZA AS LABELS DO MENU
     */
    public function updateLabelMenu($id, $data)
    {  
       
     if ($this->db->update('menu', $data, array('id' => $id))) {
        return true;
        }
        return false;
    }
    
    //PROJETOS
     public function getQtdeProjetosByUser( )
    {
         $usuario = $this->session->userdata('user_id');
        $empresa = $this->session->userdata('empresa');
        $users_dados = $this->site->geUserByID($usuario);
        $modulo_atual_id = $users_dados->modulo_atual;
        $projeto_atual_id = $users_dados->projeto_atual; 
         $statement = "SELECT count(*) as ativo,
(SELECT count(*) FROM sig_projetos p inner join sig_users_projetos up on up.projeto = p. id where up.users = $usuario and status = 'CANCELADO' and empresa_id = $empresa)  cancelado,
(SELECT count(*) FROM sig_projetos p inner join sig_users_projetos up on up.projeto = p. id where up.users = $usuario and status = 'CONCLUIDO' and empresa_id = $empresa)  concluido,
(SELECT count(*) FROM sig_projetos p inner join sig_users_projetos up on up.projeto = p. id where up.users = $usuario and status = 'EM AGUARDO' and empresa_id = $empresa) aguardando
FROM sig_projetos p
inner join sig_users_projetos up on up.projeto = p. id
where up.users = $usuario and status = 'ATIVO' and empresa_id = $empresa";
       //echo $statement; exit;
        $q = $this->db->query($statement);
     
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
         
    }
    
     //ATAS
     public function getQtdeAtasByStatysAndProjeto( )
    {
         $usuario = $this->session->userdata('user_id');
        $empresa = $this->session->userdata('empresa');
        $users_dados = $this->site->geUserByID($usuario);
        $modulo_atual_id = $users_dados->modulo_atual;
        $projeto_atual_id = $users_dados->projeto_atual; 
        if(!$projeto_atual_id){
            $projeto_atual_id = 0;
        }
         $statement = "SELECT count(*) as aberto, (SELECT count(*) as aberto  FROM sig_atas where status = 1 and projetos = $projeto_atual_id and empresa = $empresa) as fechado
            FROM sig_atas where status = 0 and projetos = $projeto_atual_id and empresa = $empresa";
       
        $q = $this->db->query($statement);
     
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
         
    }
    
    //PLANO AÇÃO
     public function getQtdePlanoAcaoByProjeto( )
    {
         $usuario = $this->session->userdata('user_id');
        $empresa = $this->session->userdata('empresa');
        $users_dados = $this->site->geUserByID($usuario);
        $modulo_atual_id = $users_dados->modulo_atual;
        $projeto_atual_id = $users_dados->projeto_atual; 
        if(!$projeto_atual_id){
            $projeto_atual_id = 0;
        }
         $statement = "SELECT count(*) as total FROM sig_plano_acao where projeto = $projeto_atual_id and empresa = $empresa";
       //echo $statement; exit;
        $q = $this->db->query($statement);
     
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
         
    }
    
    
    //AÇÕES
     public function getQtdeAcoesByProjeto( )
    {
         $usuario = $this->session->userdata('user_id');
        $empresa = $this->session->userdata('empresa');
        $users_dados = $this->site->geUserByID($usuario);
        $modulo_atual_id = $users_dados->modulo_atual;
        $projeto_atual_id = $users_dados->projeto_atual; 
        if(!$projeto_atual_id){
            $projeto_atual_id = 0;
        }
         $statement = "SELECT count(*) as pendente,
(SELECT count(*) as concluido FROM sig_planos p where projeto = $projeto_atual_id and p.empresa = $empresa and status = 'CONCLUÍDO') as concluido,
(SELECT count(*) as concluido FROM sig_planos p where projeto = $projeto_atual_id and p.empresa = $empresa and status = 'AGUARDANDO_VALIDAÇÃO') as aguardando,
(SELECT count(*) as concluido FROM sig_planos p where projeto = $projeto_atual_id and p.empresa = $empresa and status = 'PENDENTE' AND data_termino < NOW()) as atrasado
FROM sig_planos p where projeto = $projeto_atual_id and p.empresa = $empresa and status = 'PENDENTE' and data_termino > NOW() ";
       //echo $statement; exit;
        $q = $this->db->query($statement);
     
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
         
    }
    
    
    
    //QUANTIDADE DE CONVITES - NETWORKING
     public function getQtdeConvitesByUser( )
    {
        $usuario = $this->session->userdata('user_id');
        $empresa = $this->session->userdata('empresa');
        $users_dados = $this->site->geUserByID($usuario);
  
        $statement = "SELECT count(*) as quantidade FROM sig_invites i
                        inner join sig_users_setor us on us.id = i.user_destino
                        where us.users_id = $usuario and i.empresa = $empresa and confirmacao = 3 and data_evento >= NOW()";
       //echo $statement; exit;
        $q = $this->db->query($statement);
     
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
         
    }
    
}
