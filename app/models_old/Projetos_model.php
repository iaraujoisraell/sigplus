<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Projetos_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        
        
    }
    
    /*
        $usuario = $this->session->userdata('user_id');
        $users_dados = $this->site->geUserByID($usuario);
        $modulo_atual_id = $users_dados->modulo_atual;
        //$empresa = $users_dados->empresa_id;
        //$empresa_dados = $this->owner_model->getEmpresaById($empresa);
        //$tabela_empresa = $empresa_dados->tabela_cliente;
      * 
      */
    
    
    //RETORNA O CLIENTE
     public function getClienteByIdAndEmpresa($id)
    {
        $empresa = $this->session->userdata('empresa');
        $q = $this->db->get_where('clientes', array('id' => $id, 'empresa_id' => $empresa), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    //RETORNA A CATEGORIA DO PROJETO
     public function getCategoriaByIdAndEmpresa($id)
    {
        $empresa = $this->session->userdata('empresa');
        $q = $this->db->get_where('categoria', array('id' => $id, 'empresa_id' => $empresa), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
    /*
     * CRIA UM NOVO PROJETO
     */
    public function addProjetos($data)
    {
            if ($this->db->insert('projetos', $data)) {
                $id_projeto = $this->db->insert_id();
                 
               return $id_projeto;
        }
          
        return false;
    }
    
     public function getProjetoByID($id)
    {
        $q = $this->db->get_where('projetos', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
     public function updateProjeto($id, $data  = array())
    {  
        if ($this->db->update('projetos', $data, array('id' => $id))) {
         return true;
        }
        return false;
    }

    
    
        public function deleteProjeto($id)
    {
       // $sale_items = $this->resetSaleActions($id);
        if ($this->db->delete('projetos', array('id' => $id))){
            
            return true;
        }
        return FALSE;
    }

    public function getAllProjetos() {
        $q = $this->db->get('projetos');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
      /*
     * RETORNAO ULTIMO NUMERO SEQUENCIAL POR EMPRESA
     */
     public function getSequencialPlanosAcao()
    {
    $empresa = $this->session->userdata('empresa');
        $statement = "SELECT max(sequencial)+1 as sequencial FROM sig_plano_acao where empresa = $empresa";
        //echo $statement; exit;
        $q = $this->db->query($statement);
         
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
    /*
     * PROJETOS QUE EU TENHO ACESSO por status
     */
     public function getAllProjetosAtivoAcesso($status) {
        $usuario = $this->session->userdata('user_id');
        $empresa = $this->session->userdata('empresa');
        $users_dados = $this->site->geUserByID($usuario);
        $modulo_atual_id = $users_dados->modulo_atual;
        $empresa = $users_dados->empresa_id;
        $empresa_dados = $this->owner_model->getEmpresaById($empresa);
        $tabela_empresa = $empresa_dados->tabela_cliente;
        //echo $tabela_empresa;
       //$empresa_db = $this->load->database('provin_clientes', TRUE);
       $statement = "SELECT p.id as id, p.projeto as nome_projeto, dt_inicio, dt_final,gerente_area, p.status as status FROM sig_projetos p 
           inner join sig_users_projetos up on up.projeto = p. id
                    where up.users = $usuario and p.status = '$status' and empresa_id = $empresa";
      // echo $statement; exit;
        $q = $this->db->query($statement);
        
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
    /*
     * PROJETOS QUE EU TENHO ACESSO
     */
     public function getAllProjetosLiberadoByser(){
        $usuario = $this->session->userdata('user_id');
         $empresa = $this->session->userdata('empresa');
        $users_dados = $this->site->geUserByID($usuario);
        $modulo_atual_id = $users_dados->modulo_atual;
        $empresa = $users_dados->empresa_id;
     
        //echo $tabela_empresa;
       //$empresa_db = $this->load->database('provin_clientes', TRUE);
       $statement = "SELECT p.projeto as projeto, p.* "
               . " FROM sig_projetos p 
                      inner join sig_users_projetos up on up.projeto = p.id
                    where up.users = $usuario and p.empresa_id = $empresa ";
     //  echo $statement; exit;
        $q = $this->db->query($statement);
        
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
    //REMOVE O ACESSO AO PROJETO
    public function deleteAcessoProjeto($id)
    {         
       // $sale_items = $this->resetSaleActions($id);
        if ($this->db->delete('users_projetos', array('id' => $id))){
            
            return true;
        }
        return FALSE;
    }
    
     //REMOVE O MARCO AO PROJETO
    public function deleteMarcosProjeto($id)
    {         
       // $sale_items = $this->resetSaleActions($id);
        if ($this->db->delete('projetos_marcos', array('id' => $id))){
            
            return true;
        }
        return FALSE;
    }
    
      //REMOVE O EQUIPE AO PROJETO
    public function deleteEquipeProjeto($id)
    {         
       // $sale_items = $this->resetSaleActions($id);
        if ($this->db->delete('projetos_equipes', array('id' => $id))){
            
            return true;
        }
        return FALSE;
    }
    
    //REMOVE O ARQUIVO DO PROJETO
    public function deleteArquivoProjeto($id)
    {         
       // $sale_items = $this->resetSaleActions($id);
        if ($this->db->delete('projetos_arquivos', array('id' => $id))){
            
            return true;
        }
        return FALSE;
    }
    
    /*
     * FASES DO PROJETOS SELECIONADO
     */
     public function getAllFasesProjetos(){
        $usuario = $this->session->userdata('user_id');
        $users_dados = $this->site->geUserByID($usuario);
        $modulo_atual_id = $users_dados->modulo_atual;
        $projeto_atual = $users_dados->projeto_atual;
     
        //echo $tabela_empresa;
       //$empresa_db = $this->load->database('provin_clientes', TRUE);
       $statement = "SELECT * FROM sig_fases_projeto p 
                    where id_projeto = $projeto_atual ";
       //echo $statement; exit;
        $q = $this->db->query($statement);
        
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
    public function getFaseByID($id)
    {
        $q = $this->db->get_where('fases_projeto', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
    public function updateProjetoUsuario($id, $data  = array())
    {  
        //echo $id;
        // print_r($data); exit;
        if ($this->db->update('users', $data, array('id' => $id))) {
         return true;
        }
        return false;
    }
    
    
    public function getAllProjetosUsers($id)
    {
        $q = $this->db->get_where('users_projetos', array('users' => $id));
        
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
    
     public function getProjetoAtualByID_completo()
    {
     
        $usuario = $this->session->userdata('user_id');
        $users_dados = $this->site->geUserByID($usuario);
        $modulo_atual_id = $users_dados->modulo_atual;
        $empresa = $users_dados->empresa_id;
        $empresa_dados = $this->owner_model->getEmpresaById($empresa);
        $tabela_empresa = $empresa_dados->tabela_cliente;
        //echo $tabela_empresa;
       //$empresa_db = $this->load->database('provin_clientes', TRUE);
       $statement = "SELECT p.id as id, p.projeto as nome_projeto, dt_inicio, dt_final, gerente_area, gerente_edp, analista,p.status as status 
           FROM sig_projetos p 
                    inner join sig_users u on u.projeto_atual = p. id
                    where u.id = $usuario";
      // echo $statement; exit;
        $q = $this->db->query($statement);
         
      
        
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
         
    }
    
    /*
     * CLIENTES
     */
     public function getAllClientesByEmpresa(){
        $usuario = $this->session->userdata('user_id');
        $empresa = $this->session->userdata('empresa');
        
       $statement = "SELECT * FROM sig_clientes where empresa_id = $empresa ";
      // echo $statement; exit;
        $q = $this->db->query($statement);
        
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
    /*
     * CATEGORIAS DE PROJETO
     */
     public function getAllCategoriaProjetoByEmpresa(){
        $usuario = $this->session->userdata('user_id');
        $empresa = $this->session->userdata('empresa');
        
       $statement = "SELECT * FROM sig_categoria where empresa_id = $empresa ";
      // echo $statement; exit;
        $q = $this->db->query($statement);
        
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
    /*
     * USUÁRIOS COM ACESSO AO PROJETO
     */
     public function getAllUsuarioAcessoByProjeto($projeto){
        $usuario = $this->session->userdata('user_id');
        $empresa = $this->session->userdata('empresa');
        
       $statement = "SELECT up.id as id_cadastro, up.criador as criador, u.* FROM sig_users_projetos up
        inner join sig_users u on u.id = up.users
        where up.projeto = $projeto
        order by first_name asc";
      // echo $statement; exit;
        $q = $this->db->query($statement);
        
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
    /*
     * EQUIPES DO PROJETO
     */
     public function getAllEquipesProjetoByEmpresaByProjeto($projeto){
        $usuario = $this->session->userdata('user_id');
        $empresa = $this->session->userdata('empresa');
        
       $statement = "select e.id as id_equipe, e.*, u.* from sig_projetos_equipes e "
               . " inner join sig_users u on u.id = e.user_responsavel "
               . " where e.empresa_id = $empresa and e.projeto_id = $projeto order by e.id asc";
       //echo $statement; exit;
        $q = $this->db->query($statement);
        
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
    /*
     * ARVUIOS DO PROJETO
     */
     public function getAllArquivosProjetoByEmpresaByProjeto($projeto){
        $usuario = $this->session->userdata('user_id');
        $empresa = $this->session->userdata('empresa');
        
       $statement = "SELECT *  FROM sig_projetos_arquivos e "
               . " where empresa_id = $empresa and projeto_id = $projeto order by id asc";
      // echo $statement; exit;
        $q = $this->db->query($statement);
        
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
    /*
     * MARCOS DO PROJETO
     */
     public function getAllMarcosProjetoByEmpresaByProjeto($projeto){
        $usuario = $this->session->userdata('user_id');
        $empresa = $this->session->userdata('empresa');
        
       $statement = "SELECT * FROM sig_projetos_marcos where empresa_id = $empresa and projetos_id = $projeto order by data_prevista asc";
      // echo $statement; exit;
        $q = $this->db->query($statement);
        
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
     /*
     * HISTÓRICOS DO PROJETO
     */
     public function getAllHistoricoProjetoByEmpresa($projeto){
        $usuario = $this->session->userdata('user_id');
        $empresa = $this->session->userdata('empresa');
        
       $statement = "SELECT * FROM sig_projetos_historico where projetos_id = $projeto and empresa_id = $empresa ";
      // echo $statement; exit;
        $q = $this->db->query($statement);
        
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    /*
     * PARTES INTERESSADAS DO PROJETO
     */
     public function getAllPArtesInteressadasProjetoByEmpresa($projeto){
        $usuario = $this->session->userdata('user_id');
        $empresa = $this->session->userdata('empresa');
        
       $statement = "SELECT * FROM sig_projetos_partes_interessadas where projetos_id = $projeto and empresa_id = $empresa ";
      // echo $statement; exit;
        $q = $this->db->query($statement);
        
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
    //REMOVE A PARTE INTERESSADA DO PROJETO
    public function removeParteInteressadaProjeto($id)
    {         
       // $sale_items = $this->resetSaleActions($id);
        if ($this->db->delete('projetos_partes_interessadas', array('id' => $id))){
            
            return true;
        }
        return FALSE;
    }
    
     /*
     * LOGS DO PROJETO
     */
     public function getAllLogProjetoByEmpresa($projeto){
        $usuario = $this->session->userdata('user_id');
        $empresa = $this->session->userdata('empresa');
        
       $statement = "SELECT * FROM sig_projetos_log where projetos_id = $projeto and empresa = $empresa order by id desc";
      // echo $statement; exit;
        $q = $this->db->query($statement);
        
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
    /**********************************************************
     ************************* A T A S ************************
     ********************************************************/
    public function getAllAtasByProjetoAtual(){
        $usuario = $this->session->userdata('user_id');
        $empresa = $this->session->userdata('empresa');
        $users_dados = $this->site->geUserByID($usuario);
        $modulo_atual_id = $users_dados->modulo_atual;
        $projeto_atual_id = $users_dados->projeto_atual;
        
        //echo $tabela_empresa;
        //$empresa_db = $this->load->database('provin_clientes', TRUE);
       $statement = "SELECT atas.id as id, atas.id as ata,  data_ata, pauta,  tipo, responsavel_elaboracao, atas.status, atas.anexo, atas.sequencia as sequencia "
               . " FROM sig_atas atas where projetos = $projeto_atual_id and empresa = $empresa ";
      // echo $statement; exit;
        $q = $this->db->query($statement);
        
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
     /**********************************************************
     ************************* PLANO DE AÇÃO ************************
     ********************************************************/
    public function getAllPlanoAcaoByProjetoAtual(){
        $usuario = $this->session->userdata('user_id');
        $empresa = $this->session->userdata('empresa');
        $users_dados = $this->site->geUserByID($usuario);
        $modulo_atual_id = $users_dados->modulo_atual;
        $projeto_atual_id = $users_dados->projeto_atual;
        
        //echo $tabela_empresa;
        //$empresa_db = $this->load->database('provin_clientes', TRUE);
       $statement = "SELECT * FROM sig_plano_acao where projeto = $projeto_atual_id and empresa = $empresa ";
      // echo $statement; exit;
        $q = $this->db->query($statement);
        
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
     /**********************************************************
     ************************* LISTA DE AÇÃO ************************
     ********************************************************/
    public function getAllAcoesByProjetoAtual(){
        $usuario = $this->session->userdata('user_id');
        $empresa = $this->session->userdata('empresa');
        $users_dados = $this->site->geUserByID($usuario);
        $modulo_atual_id = $users_dados->modulo_atual;
        $projeto_atual_id = $users_dados->projeto_atual;
        
        //echo $tabela_empresa;
        //$empresa_db = $this->load->database('provin_clientes', TRUE);
       $statement = "SELECT * FROM sig_planos p where projeto = $projeto_atual_id and p.empresa = $empresa and status IN('PENDENTE','CONCLUÍDO','CANECLADO');";
     //  echo $statement; exit;
        $q = $this->db->query($statement);
        
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
     
    /***************************************************************************
     **********************D A S H B O A R D ***********************************
     **************************************************************************/
    
    /*
     * QTDE EQUIPE por PROJETO
     */
     public function getQtdeEquipeByProjeto($id)
    {
        $empresa = $this->session->userdata('empresa');
        $statement = "SELECT count(distinct(responsavel)) as responsavel
                    FROM sig_planos
                    where projeto = $id and empresa = $empresa ";
        //echo $statement; exit;
        $q = $this->db->query($statement);
        
        
         
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
    /*
     * QTDE ATA por PROJETO
     */
    public function getAtaByProjeto($id)
    {
        $empresa = $this->session->userdata('empresa');
        $statement = "SELECT count(*) as ata
                    FROM sig_atas
                    where projetos = $id and empresa = $empresa ";
        $q = $this->db->query($statement);
        
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
    
    /*
     * QTDE AÇÕES POR PROJETOS
     */
     public function getQtdeAcoesByProjeto($id)
    {
        $empresa = $this->session->userdata('empresa');
        $statement = "SELECT count(*) as total_acoes
                    FROM sig_planos
                    where projeto = $id and empresa = $empresa and status not in ('ABERTO', 'CANCELADO')";
        $q = $this->db->query($statement);
         
        
         
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
    
    /*
     * QTDE AÇÕES CONCLUÍDAS
     */
     public function getQtdeAcoesConcluidasByProjeto($id)
    {
        $empresa = $this->session->userdata('empresa');
        $statement = "SELECT count(*) as quantidade_concluida
                    FROM sig_planos
                    where projeto = $id and empresa = $empresa and status = 'CONCLUÍDO'";
        $q = $this->db->query($statement);
         
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
    /*
     * QTDE AÇÕES PENDENTES 
     */
     public function getQtdeAcoesPendentesByProjeto($id)
    {
        $date_hoje = date('Y-m-d H:i:s');
        $empresa = $this->session->userdata('empresa');
        $statement = "SELECT count(*) as pendente
                    FROM sig_planos
                    where projeto = $id and empresa = $empresa and status = 'PENDENTE' and data_termino >= '$date_hoje'";
        //ECHO $statement; EXIT;
        $q = $this->db->query($statement);
        
              
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
    /*
     * QTDE AÇÕES AGUARDANDO VALIDAÇÃO
     */
    
     public function getAcoesAguardandoValidacaoByProjeto($id)
    {
        $date_hoje = date('Y-m-d H:i:s');
        $empresa = $this->session->userdata('empresa');
        $statement = "SELECT count(*) as aguardando_validacao
                    FROM sig_planos
                    where projeto = $id and empresa = $empresa and status = 'AGUARDANDO VALIDAÇÃO' ";
        //ECHO $statement; EXIT;
        $q = $this->db->query($statement);
         
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
    /* 
     * QTDE AÇÕES ATRASADAS
     */
    public function getQtdeAcoesAtrasadasByProjeto($id)
    {
        $date_hoje = date('Y-m-d H:i:s');
        $empresa = $this->session->userdata('empresa');
        $statement = "SELECT count(*) as atrasadas
                    FROM sig_planos
                    where projeto = $id and empresa = $empresa and status = 'PENDENTE' and data_termino < '$date_hoje'";
        //ECHO $statement; EXIT;
        $q = $this->db->query($statement);
        
           
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
    /*
     * PEGA TODOS OS EVENTOS DE UM PROJETO
     */
     public function getAllEventosProjeto($id)
    {
     
    
          $statement = "SELECT e.id as evento_id, f.id as fase_id, e.data_inicio as data_inicio, e.data_fim as data_fim, e.projeto as projeto, f.nome_fase as tipo, nome_evento FROM sig_eventos e
                        inner join sig_fases_projeto f on f.id = e.fase_id
                        where e.projeto = $id order by f.id asc ";
       //echo $statement; exit;
        $q = $this->db->query($statement);
    
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
    // Desempenho geral da equipe do projeto pelo status das ações
     public function getAllitemStatusPlanosLinhaTempo($projeto_atual)
    {

         $q = $this->db->get_where('historico_acoes', array('resumo' => 1,'projeto' => $projeto_atual));
         
        
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
    
    /*
     * PEGA TODOS OS TIPOS DE EVENTOS DE UM PROJETO
     */
     public function getAllFaseByProjeto()
    {
    $usuario = $this->session->userdata('user_id');
    $users_dados = $this->site->geUserByID($usuario);
    $projeto_atual = $users_dados->projeto_atual;
    
    $statement = "SELECT * from sig_fases_projeto where id_projeto = $projeto_atual order by ordem asc ";
    $q = $this->db->query($statement);
        
            
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
    
    /*
     * AS ÁREAS QUE TEM AÇÕES NO PROJETO, SUPERINTENDENCIA OU PRESTADORES
     */
    
     public function getAreasByProjeto()
    {
        $usuario = $this->session->userdata('user_id');
        $users_dados = $this->site->geUserByID($usuario);
        $projeto_atual = $users_dados->projeto_atual;
        
        $statement = "select distinct pai.id as id_pai, pai.nome as descricao from sig_planos p
                    inner join sig_users_setor us on us.users_id = p.responsavel
                    inner join sig_setores s      on s.id = us.setores_id
                    inner join sig_setores pai    on pai.id = s.pai
                    where projeto = $projeto_atual and pai.pai is null ";
        
        $q = $this->db->query($statement);
      
         
       
      if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
    
    /*
     * QTDE AÇÕES POR SUPERINTENDENCIA
     */
    
     public function getAcoesSetorPaiByProjeto($area)
    {
        $usuario = $this->session->userdata('user_id');
        $users_dados = $this->site->geUserByID($usuario);
        $projeto_atual = $users_dados->projeto_atual;
        
         $statement = "select count(*) as qtde from sig_planos p
                    inner join sig_users_setor us on us.users_id = p.responsavel
                    inner join sig_setores s      on s.id = us.setores_id
                    inner join sig_setores pai    on pai.id = s.pai
                    where pai.id = $area and projeto = $projeto_atual and status in ('CONCLUÍDO','PENDENTE', 'AGUARDANDO VALIDAÇÃO')  ";
         
        $q = $this->db->query($statement);
        
            if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
    
    /*
     * QTDE AÇÕES DE TODAS AS SUPERINTENDENCIA DE UM PROJETO
     */
    
     public function getTotalAcoesSetoresPaiByProjeto()
    {
        $usuario = $this->session->userdata('user_id');
        $users_dados = $this->site->geUserByID($usuario);
        $projeto_atual = $users_dados->projeto_atual;
        $statement = "select count(*) as qtde from sig_planos p
                    inner join sig_users_setor us on us.users_id = p.responsavel
                    inner join sig_setores s      on s.id = us.setores_id
                    inner join sig_setores pai    on pai.id = s.pai
                    where projeto = $projeto_atual and status in ('CONCLUÍDO','PENDENTE', 'AGUARDANDO VALIDAÇÃO') ";
        
        $q = $this->db->query($statement);
         
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
    
    /*
     * QTDE AÇÕES CONCLUIDAS DE UM SETOR PAI
     */
    
     public function getAcoesConcluidasSetorPaiByProjeto($area)
    {
        $usuario = $this->session->userdata('user_id');
        $users_dados = $this->site->geUserByID($usuario);
        $projeto_atual = $users_dados->projeto_atual; 
        $statement = "select count(*) as qtde from sig_planos p
                    inner join sig_users_setor us on us.users_id = p.responsavel
                    inner join sig_setores s      on s.id = us.setores_id
                    inner join sig_setores pai    on pai.id = s.pai
                    where pai.id = $area and projeto = $projeto_atual and p.status = 'CONCLUÍDO'  ";
        $q = $this->db->query($statement);
        
       
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
    /*
     * QTDE AÇÕES PENDENTES POR SUPERINTENDENCIA
     * 
     */
    
     public function getAcoesPendenteSetorPaiByProjeto($area, $tipo)
    {
        $date_hoje = date('Y-m-d H:i:s');
        $usuario = $this->session->userdata('user_id');
        $users_dados = $this->site->geUserByID($usuario);
        $projeto_atual = $users_dados->projeto_atual; 
        $statement = "select count(*) as qtde from sig_planos p
                    inner join sig_users_setor us on us.users_id = p.responsavel
                    inner join sig_setores s      on s.id = us.setores_id
                    inner join sig_setores pai    on pai.id = s.pai
                    where pai.id = $area and projeto = $projeto_atual and p.status = 'PENDENTE'  ";
        
        if($tipo == 1){
            //PENDENTE
            $statement .= " and data_termino >= '$date_hoje' ";
            
        }else if($tipo == 2){
            // ATRASADO
            $statement .= " and data_termino < '$date_hoje' ";
           
        }
      $q = $this->db->query($statement);
      
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
    
    /*
     * PEGA TODOS OS PLANOS DE UM PROJETO e de uma área
     */
     public function getAllitemPlanosProjetoArea($id, $area)
    {
         
         $this->db->select('planos.idplanos,planos.descricao, planos.data_termino,planos.data_retorno_usuario, users.username,planos.status,users.company,users.gestor,users.award_points,setores.nome as setor, superintendencia.responsavel as superintendencia')
            ->join('users', 'planos.responsavel = users.id', 'left')
            ->join('setores', 'planos.setor = setores.id', 'left') 
            ->join('superintendencia', 'setores.superintendencia = superintendencia.id', 'left')     
            ->join('atas', 'planos.idatas = atas.id', 'left')
         ->order_by('setores.id', 'asc');
         $q = $this->db->get_where('planos', array('atas.projetos' => $id,'superintendencia.id' => $area));
        
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
    
    /***************************************************************************
     ***************  F I M ** D A S H O B O A R D *****************************
     ***************************************************************************/
    
    
    
    
    
    
    /*
     * PERFIL SUPERINTENDENCIA E GESTOR
     */
  
    
    
    /*
     * VERIFICA A TABELA USER_SUPERINTENCIA PARA VERIFICAR QUAIS SUPERINTENDENCIA O USUÁRIO ESTÁ LIGADO
     */
    
    public function getSuperintenciaByUser($perfil,$projeto,$usuario)
    {
        if($perfil == 3){
         $q = $this->db->get_where('users_superintendencia', array('users' => $usuario,'projeto' => $projeto));
        }else if($perfil == 2){
         $q = $this->db->get_where('users_gestor', array('users' => $usuario,'projeto' => $projeto));
        }
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
      /*
     * QTDE EQUIPE por PROJETO E POR SUPERINTENDENCIA
     */
     public function getEquipeByProjetoSuperintendencia($perfil,$projeto,$superintendencia)
    {
         $this->db->select("COUNT( DISTINCT (responsavel)) as responsavel")
        ->join('atas', 'planos.idatas = atas.id', 'left')
        ->join('projetos', 'atas.projetos = projetos.id', 'left')
        ->join('users', 'planos.responsavel = users.id', 'left')
        ->join('setores', 'planos.setor = setores.id', 'left'); 
         if($perfil == 3){
        $q = $this->db->get_where('planos', array('projetos.id' => $projeto, 'setores.superintendencia' => $superintendencia), 1);
          }else if($perfil == 2){
         $q = $this->db->get_where('planos', array('projetos.id' => $projeto, 'setores.id' => $superintendencia), 1);
        
        }
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
    
     /*
     * QTDE AÇÕES POR PROJETO E POR SUPERINTENDENCIA
     */
     public function getQtdeAcoesByProjetoSuperintendencia($perfil,$projeto,$superintendencia)
    {
         $this->db->select("COUNT( idplanos) as total_acoes")
        ->join('atas', 'planos.idatas = atas.id', 'left')
        ->join('projetos', 'atas.projetos = projetos.id', 'left')
        ->join('users', 'planos.responsavel = users.id', 'left')
        ->join('setores', 'planos.setor = setores.id', 'left'); 
         
         if($perfil == 3){
         $q = $this->db->get_where('planos', array('projetos.id' => $projeto, 'setores.superintendencia' => $superintendencia, 'planos.status !=' => 'CANCELADO'), 1);
          }else if($perfil == 2){
          $q = $this->db->get_where('planos', array('projetos.id' => $projeto, 'setores.id' => $superintendencia, 'planos.status !=' => 'CANCELADO'), 1);
        
        }
        
       
         
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
    /*
     * QTDE AÇÕES CONCLUÍDAS, PENDENTES E ATRASADAS DE UM PROJETO E UMA SUPERINTENDENCIA
     */
     public function getAcoesByProjetoSuperintendenciaStatus($perfil,$id,$status,$superintendencia)
    {   
         $date_hoje = date('Y-m-d H:i:s');
          
         $this->db->select("COUNT( idplanos) as quantidade")
        ->join('atas', 'planos.idatas = atas.id', 'left')
        ->join('projetos', 'atas.projetos = projetos.id', 'left')
        ->join('users', 'planos.responsavel = users.id', 'left')
        ->join('setores', 'planos.setor = setores.id', 'left');         
        
        
         if($perfil == 3){
             
             if ($status == 'CONCLUÍDO') {
                $q = $this->db->get_where('planos', array('projetos.id' => $id, 'planos.status' => $status, 'setores.superintendencia' => $superintendencia), 1);
            } else if (($status == 'PENDENTE') || ($status == 'AGUARDANDO VALIDAÇÃO')) {
                $q = $this->db->get_where('planos', array('projetos.id' => $id, 'planos.status' => $status, 'setores.superintendencia' => $superintendencia, 'planos.data_termino >' => $date_hoje), 1);
            } else if ($status == 'ATRASADO') {
                $q = $this->db->get_where('planos', array('projetos.id' => $id, 'planos.status' => 'PENDENTE', 'setores.superintendencia' => $superintendencia, 'planos.data_termino <' => $date_hoje), 1);
            }
        }else if($perfil == 2){
             if ($status == 'CONCLUÍDO') {
                $q = $this->db->get_where('planos', array('projetos.id' => $id, 'planos.status' => $status, 'setores.id' => $superintendencia), 1);
            } else if (($status == 'PENDENTE') || ($status == 'AGUARDANDO VALIDAÇÃO')) {
                $q = $this->db->get_where('planos', array('projetos.id' => $id, 'planos.status' => $status, 'setores.id' => $superintendencia, 'planos.data_termino >' => $date_hoje), 1);
            } else if ($status == 'ATRASADO') {
                $q = $this->db->get_where('planos', array('projetos.id' => $id, 'planos.status' => 'PENDENTE', 'setores.id' => $superintendencia, 'planos.data_termino <' => $date_hoje), 1);
            }
       
        }
         
         
         
         
        
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
    /*
     * Status E QUANTIDADE das Açõs no tempo do projeto
     */
     public function getAllitemPlanosLinhaTempoSuperintendencia($perfil,$id,$id_superintendencia_data = array())
    {
        
         
         $this->db->select("projeto, data,sum(total_acoes) as total_acoes,  sum(total_atrasados) as total_atrasados , sum(total_concluido) as total_concluido, sum(total_pendentes) as total_pendentes, sum(total_fora_prazo) as total_fora_prazo")
        ->join('setores', 'historico_acoes.setor = setores.id', 'left');
         
       
         
         if($perfil == 3){
          foreach ($id_superintendencia_data as $item_id) {
            
          $this->db->or_where('setores.superintendencia =', $item_id);
        }
          }else if($perfil == 2){
           foreach ($id_superintendencia_data as $item_id) {
            
          $this->db->or_where('setores.id =', $item_id);
        }
        
        }
        
        
        
       
            $this->db->where('projeto =', $id);
          
         
         $this->db->group_by('data');
         $q = $this->db->get('historico_acoes');
         
        
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
    
    
    
    
    
    
    
    

    
    
     
    
    /*
     * PEGA AS ÁREAS  DO USUÁRIO
     */
     public function getAreasByUsuarioProjeto($projeto,$usuario)
    {
        $select =   array(
             ' superintendencia.id as id_superintendencia',
             'superintendencia.nome as superintendencia',
            'projetos.id as projeto'
);
       $this->db->select($select)
        ->distinct()
        //->join('atas',             'planos.idatas = atas.id', 'left')
        ->join('projetos',         'users_superintendencia.projeto = projetos.id', 'left')
       // ->join('users',            'planos.responsavel = users.id', 'left')
       // ->join('setores',          'users.setor_id = setores.id', 'left')         
       // ->join('prestadores',      'setores.prestador = prestadores.id', 'left')
        ->join('superintendencia', 'users_superintendencia.superintendencia = superintendencia.id', 'left')
        // ->join('users_superintendencia', 'superintendencia.id = users_superintendencia.superintendencia', 'left')
        ->order_by('id_superintendencia', 'asc');
       
     // $q = $this->db->get_where('planos', array('projetos.id' => $id));  
     
           $q = $this->db->get_where('users_superintendencia', array('users_superintendencia.users' => $usuario,'users_superintendencia.projeto' => $projeto));  
       
       
      
         
       
      if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
    /*
     * PEGA ÁREA E SETOR
     */
     public function getAreasSetorByUsuarioProjeto($projeto,$usuario)
    {
        $select =   array(
             ' superintendencia.id as id_superintendencia',
             'superintendencia.nome as superintendencia',
            'projetos.id as projeto',
            'planos.setor as setor_id',
            'setores.nome as setor'
);
       $this->db->select($select)
        ->distinct()
        ->join('atas',             'planos.idatas = atas.id', 'left')
        ->join('projetos',         'atas.projetos = projetos.id', 'left')
        ->join('users',            'planos.responsavel = users.id', 'left')
        ->join('setores',          'planos.setor = setores.id', 'left')         
       // ->join('prestadores',      'setores.prestador = prestadores.id', 'left')
        ->join('superintendencia', 'setores.superintendencia = superintendencia.id', 'left')
         ->join('users_superintendencia', 'superintendencia.id = users_superintendencia.superintendencia', 'left')
        ->order_by('setor_id', 'asc');
       
     // $q = $this->db->get_where('planos', array('projetos.id' => $id));  
     
           $q = $this->db->get_where('planos', array('users_superintendencia.users' => $usuario,'users_superintendencia.projeto' => $projeto));  
       
       
      
         
       
      if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
    
    /*
     * PEGA ÁREA E SETOR
     */
     public function getAreasSetorBySuperintendente($projeto,$usuario)
    {
        
        $select =   array(
             ' superintendencia.id as id_superintendencia',
             'superintendencia.nome as superintendencia',
            'users_superintendencia.projeto as projeto',
            'setores.id as setor_id',
            'setores.nome as setor'
);
       $this->db->select($select)
        ->distinct()
       // ->join('atas',             'planos.idatas = atas.id', 'left')
       // ->join('projetos',         'atas.projetos = projetos.id', 'left')
      //  ->join('users',            'planos.responsavel = users.id', 'left')
         ->join('superintendencia', 'users_superintendencia.superintendencia = superintendencia.id', 'left')       
        ->join('setores',          'users_superintendencia.superintendencia = setores.superintendencia', 'left')         
       // ->join('prestadores',      'setores.prestador = prestadores.id', 'left')
       
     //    ->join('users_superintendencia', 'superintendencia.id = users_superintendencia.superintendencia', 'left')
        ->order_by('setores.id', 'asc');
       
     // $q = $this->db->get_where('planos', array('projetos.id' => $id));  
     
           $q = $this->db->get_where('users_superintendencia', array('users_superintendencia.users' => $usuario,'users_superintendencia.projeto' => $projeto));  
       
        //echo 'aqui'.$q->num_rows(); exit;  
       
      if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
    
    
    /*
     * PEGA AS ÁREAS  DO USUÁRIO
     */
     public function getSetoresByUsuarioProjeto($projeto,$usuario)
    {
        $select =   array(
             ' superintendencia.id as id_superintendencia',
             'superintendencia.nome as superintendencia',
            
            'projetos.id as projeto'
);
       $this->db->select($select)
        ->distinct()
        ->join('atas',             'planos.idatas = atas.id', 'left')
        ->join('projetos',         'atas.projetos = projetos.id', 'left')
        ->join('users',            'planos.responsavel = users.id', 'left')
        ->join('setores',          'planos.setor = setores.id', 'left')         
       // ->join('prestadores',      'setores.prestador = prestadores.id', 'left')
         ->join('superintendencia', 'setores.superintendencia = superintendencia.id', 'left')
         ->join('users_gestor', 'setores.id = users_gestor.setor', 'left')
        ->order_by('setores.id', 'asc');
       
     // $q = $this->db->get_where('planos', array('projetos.id' => $id));  
     
           $q = $this->db->get_where('planos', array('users_gestor.users' => $usuario,'projetos.id' => $projeto));  
       
       
      
         
       
      if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
    
    
     public function getGestoresSetoresByUsuarioProjeto($projeto,$usuario)
    {
        $select =   array(
             ' superintendencia.id as id_superintendencia',
             'superintendencia.nome as superintendencia',
             'users_gestor.projeto as projeto'
);
       $this->db->select($select)
        ->distinct()
       // ->join('atas',             'planos.idatas = atas.id', 'left')
       // ->join('projetos',         'atas.projetos = projetos.id', 'left')
       // ->join('users',            'planos.responsavel = users.id', 'left')
        ->join('setores',          'users_gestor.setor = setores.id', 'left')         
       // ->join('prestadores',      'setores.prestador = prestadores.id', 'left')
         ->join('superintendencia', 'setores.superintendencia = superintendencia.id', 'left')
        // ->join('users_gestor', 'setores.id = users_gestor.setor', 'left')
        ->order_by('setores.id', 'asc');
     
     // $q = $this->db->get_where('planos', array('projetos.id' => $id));  
        $q = $this->db->get_where('users_gestor', array('users_gestor.users' => $usuario,'users_gestor.projeto' => $projeto));  
       
        
      
         
       
      if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
    /*
     * 
     * 
     * FIM PERFIL GESTOR E SUPERINTENDENCIA
     * 
     * 
     * PERFIL EDP
     */
    
    
    
     
    
    
    
    
    
    /*
     * retorna todos os usuarios com acoes atrasadas de um projeto. objtivo é enviar emails para esses usuários.
     */
     public function usuariosComAcoesAtrasadas($id)
    {
      
        $date_hoje = date('Y-m-d H:i:s');
        $this->db->select('distinct(responsavel) as responsavel, users.username, users.email, ultimo_aviso_email')
            ->join('users', 'planos.responsavel = users.id', 'left')
            ->join('atas', 'planos.idatas = atas.id', 'left')
            ->order_by('users.username', 'asc');
         $q = $this->db->get_where('planos', array('atas.projetos' => $id,'planos.status' => 'PENDENTE','planos.data_termino <' => $date_hoje));
         
         
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
    /*
     * ALTERA A DATA DO ÚLTIMO ENVIO
     */
    
      public function updateDataNotificacaoUsuario($id, $data  = array())
    {  
        if ($this->db->update('users', $data, array('id' => $id))) {
         return true;
        }
        return false;
    }
    
    /*******************************************************
     * ****************** EVENTOS
     *******************************************************/
    
       public function addEventos($data_evento, $data_modulos, $data_setores)
    {
         
            if ($this->db->insert('eventos', $data_evento)) {
                 $id_evento = $this->db->insert_id();
                 
                 // ADCIONA TODOS OS MÓDULOS SELECIONADOS
                 
                  foreach ($data_modulos as $item_modulo) {
                        $data_modulo = array('evento' => $id_evento,
                            'modulo' => $item_modulo);      
                        
                        $this->db->insert('modulo_evento', $data_modulo);
                 }
                 
                 // ADCIONA TODOS OS SETORES SELECIONADOS
                 foreach ($data_setores as $item_setor) {
                        $data_ata_usuario = array('setor' => $item_setor,
                            'evento' => $id_evento);      
                        
                        $this->db->insert('setor_evento', $data_ata_usuario);
                 }
                 
                 
               return true;
        }
          
        return false;
    }
    
   
     public function getDiferencaDiasProjeto($data_ini, $data_fim)
    {
       $statement = "SELECT DATEDIFF('$data_fim','$data_ini') as dias ";
       //echo $statement; exit;
        $q = $this->db->query($statement);
        
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
    /*
     * PEGA TODOS OS TIPOS DE EVENTOS DE UM PROJETO
     */
     public function getAllFasesProjeto()
    {
       $usuario = $this->session->userdata('user_id');
       $users_dados = $this->site->geUserByID($usuario);
       $projeto_atual = $users_dados->projeto_atual;
    
    
        $this->db->select('*')
        ->order_by('ordem', 'asc');
        $q = $this->db->get_where('fases_projeto', array('id_projeto' => $projeto_atual));
        
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
    
    
     /*
     * PEGA TODOS OS EVENTOS DE UM PROJETO PELA FASE
     */
     public function getAllEventosProjetoByFase($fase)
    {
     $usuario = $this->session->userdata('user_id');
     $users_dados = $this->site->geUserByID($usuario);
     $projeto_atual_id = $users_dados->projeto_atual;
        
        $this->db->select('*')
        ->order_by('ordem', 'asc');      
        $q = $this->db->get_where('eventos', array('fase_id' => $fase, 'projeto' => $projeto_atual_id));
       
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
    
    
     /*
     * PEGA TODOS OS EVENTOS  E ITENS DE EVENTO DE UM PROJETO
     */
     public function getAllEventosItemEventoByProjeto($projeto, $campo, $ordem)
    {
    
        $statement = "SELECT i.id as id, nome_fase, nome_evento, descricao, i.dt_inicio as dt_inicio, i.dt_fim as dt_fim
            FROM sig_fases_projeto f
        inner join sig_eventos e on e.fase_id = f.id
        inner join sig_item_evento i on i.evento = e.id where f.id_projeto = $projeto order by nome_fase asc";
       // echo $statement; exit;
        $q = $this->db->query($statement);
         
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
    /*
     * PEGA TODOS OS MODULOS DE UM EVENTOS DE UM PROJETO
     */
     public function getAllModulosProjeto($id)
    {
        
         $this->db->select('modulo_evento.id as id_modulo_evento, modulos.descricao as descricao')
            ->join('modulos', 'modulo_evento.modulo = modulos.id', 'left');
           // ->join('setores', 'users.setor_id = setores.id', 'left')  
           // ->join('superintendencia', 'setores.superintendencia = superintendencia.id', 'left')        
           // ->join('atas', 'planos.idatas = atas.id', 'left')
        // ->order_by($campo, $ordem);
        
         $q = $this->db->get_where('modulo_evento', array('modulo_evento.evento' => $id));
         
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
    /*
     * PEGA TODOS OS MODULOS DE UM EVENTOS DE UM PROJETO
     */
     public function getAllSetoresProjeto($id)
    {
        
         $this->db->select('setores.nome as descricao')
            ->join('setores', 'setor_evento.setor = setores.id', 'left');
           // ->join('setores', 'users.setor_id = setores.id', 'left')  
           // ->join('superintendencia', 'setores.superintendencia = superintendencia.id', 'left')        
           // ->join('atas', 'planos.idatas = atas.id', 'left')
        // ->order_by($campo, $ordem);
        
         $q = $this->db->get_where('setor_evento', array('setor_evento.evento' => $id));
         
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
    public function getEventoByID($id)
    {
        $q = $this->db->get_where('eventos', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
     public function updateEvento($id, $data  = array(), $modulos  = array(), $setores  = array())
    {  
         
          
        if ($this->db->update('eventos', $data, array('id' => $id))) {
           
            /*
             * MÓDULOS
             */
            $this->db->delete('modulo_evento', array('evento' => $id));
            foreach ($modulos as $modulo) {
                        $data_modulo_evento = array('evento' => $id, 'modulo' => $modulo);      
                        $this->db->insert('modulo_evento', $data_modulo_evento);
            }
            
            /*
             * SETORES
             */
            $this->db->delete('setor_evento', array('evento' => $id));
            foreach ($setores as $setor) {
                        $data_setor_evento = array('evento' => $id, 'setor' => $setor);      
                        $this->db->insert('setor_evento', $data_setor_evento);
            }
            
         return true;
        }
        return false;
    }
    
    public function deleteEvento($id)
    {         
       // $sale_items = $this->resetSaleActions($id);
        if ($this->db->delete('eventos', array('id' => $id))){
            
            return true;
        }
        return FALSE;
    }
    
    
    /*
     * PEGA TODOS OS SETORES POR SUPERINTENDENCIA
     usado em Ações -> Resumo por setor
     * 
     * SELECT distinct s.id, s.nome,su.nome FROM `sma_setores` s
inner join sma_superintendencia su on su.id = s.superintendencia
inner join sma_users u on u.setor_id = s.id
inner join sma_planos p on p.responsavel = u.id
inner join sma_atas a on a.id = p.idatas
 WHERE a.projetos = 1
order by su.nome asc
     */
    public function getAllSetorArea($id)
    {
        $this->db->select('setores.id as setor_id,setores.nome as setor, superintendencia.nome as superintendencia, superintendencia.id as id_area')
        ->join('superintendencia', 'setores.superintendencia = superintendencia.id', 'left')
        ->order_by('superintendencia.nome', 'asc');
        
        $q = $this->db->get_where('setores', array('id_area' => $id));
        
        
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
    
    /*
     * PEGA A QUANTIDADE DE AÇÕES POR EVENTO
     */
     public function getAcoesEventoByID($id)
    {
         $this->db->select('count(*) as qtde');
        $q = $this->db->get_where('planos', array('eventos' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
    /*
     * PEGA A QUANTIDADE DE EVENTO PRO PROJETO
     */
     public function getEventoByProjeto($id)
    {
         $this->db->select('count(*) as qtde');
        $q = $this->db->get_where('eventos', array('projeto' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
    
    /*
     * TABELA DE PERMISSÕES POR GRUPO DE PERFIL
     */
    
       public function getPermissoesByPerfil($id)
    {
        $q = $this->db->get_where('permissions', array('group_id' => $id), 1);
      
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
    
        /*******************************************************
     * ****************** POSTAGEM
     *******************************************************/
    
        
    /*
     * PEGA TODOS OS EVENTOS DE UM PROJETO
     */
     public function getAllPostagemByProjeto($id)
    {
        
         $this->db->select('*')
           // ->join('users', 'planos.responsavel = users.id', 'left')
           // ->join('setores', 'users.setor_id = setores.id', 'left')  
           // ->join('superintendencia', 'setores.superintendencia = superintendencia.id', 'left')        
           // ->join('atas', 'planos.idatas = atas.id', 'left')
         ->order_by('id', 'DESC');
        
         $q = $this->db->get_where('post', array('projeto' => $id));
         
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
    /*
     * PEGA TODOS OS EVENTOS DE UM PROJETO
     */
    public function getPostagemById($id)
    {
        
         $this->db->select('*')
           // ->join('users', 'planos.responsavel = users.id', 'left')
           // ->join('setores', 'users.setor_id = setores.id', 'left')  
           // ->join('superintendencia', 'setores.superintendencia = superintendencia.id', 'left')        
           // ->join('atas', 'planos.idatas = atas.id', 'left')
         ->order_by('id', 'DESC');
        
         $q = $this->db->get_where('post', array('id' => $id));
         
       if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
    /*
     * PEGA TODOS OS EVENTOS DE UM PROJETO
     */
     public function getAllPostagemByProjetoByTipo($id, $tipo)
    {
        
         $this->db->select('*')
           // ->join('users', 'planos.responsavel = users.id', 'left')
           // ->join('setores', 'users.setor_id = setores.id', 'left')  
           // ->join('superintendencia', 'setores.superintendencia = superintendencia.id', 'left')        
           // ->join('atas', 'planos.idatas = atas.id', 'left')
         ->order_by('id', 'DESC');
        
         $q = $this->db->get_where('post', array('projeto' => $id, 'tipo' => $tipo));
         
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
       public function addPostagem($data_evento)
    {
         
            if ($this->db->insert('post', $data_evento)) {
                 $id_evento = $this->db->insert_id();
              
                 
               return true;
        }
          
        return false;
    }
    
      public function deletePostagem($id)
    {         
       // $sale_items = $this->resetSaleActions($id);
        if ($this->db->delete('post', array('id' => $id))){
            
            return true;
        }
        return FALSE;
    }
    
    /****************************************************************************
     * ****************** I T E N S   D O    EVENTOS ****************************
     *****************************************************************************/
    
       public function addItensventos($data_evento)
    {
         
            if ($this->db->insert('item_evento', $data_evento)) {
                 $id_evento = $this->db->insert_id();
              
                 
               return true;
        }
          
        return false;
    }
    
    /*
     * PEGA TODOS OS EVENTOS DE UM PROJETO
     */
     public function getAllItemEventosProjeto($evento)
    {
         
         $this->db->select('*')
           // ->join('users', 'planos.responsavel = users.id', 'left')
           // ->join('setores', 'users.setor_id = setores.id', 'left')  
           // ->join('superintendencia', 'setores.superintendencia = superintendencia.id', 'left')        
           // ->join('atas', 'planos.idatas = atas.id', 'left')
         ->order_by('id', 'ASC');
        
         $q = $this->db->get_where('item_evento', array('evento' => $evento));
         
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
    
    public function getItemEventoByID($id)
    {
        $q = $this->db->get_where('item_evento', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
    public function getItemEventoByEvento($evento)
    {
        $statement = "SELECT count(*) as quantidade from sig_item_evento
                    where evento = '$evento'";
      // echo $statement; exit;
        $q = $this->db->query($statement);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
    // VERIFICA SE TEM AÇÃO VINCULADO AOS ITENS DE EVENTO DE UM EVENTO
     public function getIAcoestemEventoByEvento($evento)
    {
        $statement = "SELECT count(*) as quantidade from sig_item_evento i
                        inner join sig_planos p on p.eventos = i.id where evento = '$evento'";
      // echo $statement; exit;
        $q = $this->db->query($statement);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
     public function updateItemEvento($id, $data  = array())
    {  
         
          
    if ($this->db->update('item_evento', $data, array('id' => $id))) {
           
         return true;
        }
        return false;
    }
    
    public function deleteItemEvento($id)
    {         
       // $sale_items = $this->resetSaleActions($id);
        if ($this->db->delete('item_evento', array('id' => $id))){
            
            return true;
        }
        return FALSE;
    }
    
    public function deleteItemEventoByEvento($evento)
    {         
       // $sale_items = $this->resetSaleActions($id);
        if ($this->db->delete('item_evento', array('evento' => $evento))){
            
            return true;
        }
        return FALSE;
    }
    
    
    
    /*
     * QUANTIDADE DE AÇÕES POR ITEM
     */
     public function getQuantidadeAcaoByItemEvento($id)
    {
        $statement = "SELECT count(*) as quantidade from sig_planos
                    where eventoS = '$id' and status not in ('ABERTO', 'CANCELADO')";
        $q = $this->db->query($statement);
        
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
    /*
     * QTDE AÇÕES CONCLUÍDAS POR ITEM DE EVENTO
     */
     public function getAcoesConcluidasByPItemEvento($eventos)
    {
        $statement = "SELECT count(*) as quantidade from sig_planos
                    where eventoS = '$eventos' and status = 'CONCLUÍDO'";
        $q = $this->db->query($statement); 
         
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
     /*
     * QTDE AÇÕES PENDENTES POR ITEM
     */
    
     public function getAcoesPendentesByItemEvento($eventos)
    {
        $date_hoje = date('Y-m-d H:i:s');
        $statement = "SELECT count(*) as quantidade from sig_planos
                    where eventoS = '$eventos' and status = 'PENDENTE' and data_termino > '$date_hoje'";
        $q = $this->db->query($statement); 
      
          
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
     /*
     * QTDE AÇÕES PENDENTES 
     */
    
     public function getAcoesAguardandoValidacaoByItemEvento($eventos)
    {
        $statement = "SELECT count(*) as quantidade from sig_planos
                    where eventoS = '$eventos' and status = 'AGUARDANDO VALIDAÇÃO'";
        $q = $this->db->query($statement); 
         
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
     /* 
     * QTDE AÇÕES ATRASADAS
     */
    
     public function getAcoesAtrasadasByItemEvento($eventos)
    {
          
        $date_hoje = date('Y-m-d H:i:s');
        $statement = "SELECT count(*) as quantidade from sig_planos
                    where eventoS = '$eventos' and status = 'PENDENTE' and data_termino < '$date_hoje'";
        $q = $this->db->query($statement); 
        
         
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
    
    
    
   /******************************************************************************
    ***************** F I M  * * E S C O P O ************************************
    *****************************************************************************/
    
    
    
    /*
     * ADICIONA UM NOVO CONTRATO
     */
          public function addContrato($data)
    {
              //print_r($data); exit;
            if ($this->db->insert('contratos', $data)) {
               $id_contratos =  $this->db->insert_id();
                 
               return $id_contratos;
        }
          
        return false;
    }
    
    /*
     * EDITA UM CONTRATO
     */
       public function updateContrato($id, $data  = array())
    {  
        if ($this->db->update('contratos', $data, array('id' => $id))) {
         return true;
        }
        return false;
    }
    
     /*
     * RETORNA TODOS OS CONTRATOS DE UM PROJETO
     */
     public function getAllContratosByProjeto($projeto)
    {       
         
        $q = $this->db->get_where('contratos', array('projeto' => $projeto));
        
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
     /*
     * RETORNA O CONTRATO PELO ID
     */
     public function getContratoByID($id)
    {
         
        $q = $this->db->get_where('contratos', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
         
    }
    
       /*
     * VERIFICA O FORNECEDOR E O CLIENTE DO CONTRATO
     */
     public function getForncedorByID($id)
    {
        $q = $this->db->get_where('companies', array('id' => $id));
    
        if ($q->num_rows() > 0) {
            
           return $q->row();
            
            
        }
        return FALSE;
         
    }
    
    /*
     * ADICIONA TÍTULOS
     */
    
        public function addTitulos($data)
    {
         
            if ($this->db->insert('transactions', $data)) {
            $transaction_id = $this->db->insert_id();
                   
               return $transaction_id;

        }
         
        return false;
    }
    
    
    
     /*
     * RETORNA O TÍTULOS DO CONTRATO
     */
     public function getTitulosByContrato($id)
    {
        $this->db->select('*')
        //->join('superintendencia', 'setores.superintendencia = superintendencia.id', 'left')
        ->order_by('ano', 'desc')        
        ->order_by('id', 'desc');
        
        $q = $this->db->get_where('transactions', array('id_contrato' => $id));
     
        
          if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
         
    }
    
      /*
     * RETORNA O TÍTULOS DO CONTRATO
     */
     public function getTitulosAnterioresByParcela($id, $parcela)
    {
        $this->db->select('*')
       
        //->join('superintendencia', 'setores.superintendencia = superintendencia.id', 'left')
        ->order_by('id', 'desc')
       ->limit(12); 
       $this->db->where('id <', $parcela);
       $q = $this->db->get_where('transactions', array('id_contrato' => $id));
       
        
          if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
         
    }
    
    /*
     * PEGA TODOS OS MARCOS DE UM PROJETO 
     */
     public function getAllMarcosProjetoByProjeto($projeto)
    {
       $date_hoje = date('Y-m-d H:i:s');
      
         if(!$ordem){
             $ordem = 'asc';
         }
         
         if($campo == null){
             $campo = 'start';
         }
         
         $this->db->select('*')
           // ->join('users', 'planos.responsavel = users.id', 'left')
           // ->join('setores', 'users.setor_id = setores.id', 'left')  
           // ->join('superintendencia', 'setores.superintendencia = superintendencia.id', 'left')        
           // ->join('atas', 'planos.idatas = atas.id', 'left')
         
          // ->order_by('tipo', 'asc')      
           ->order_by($campo, $ordem);
      
       
         $q = $this->db->get_where('calendar', array('projeto' => $projeto));
        
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
    
     /*
     * PEGA TODOS OS MARCOS DE UM PROJETO 
     */
     public function getAllMarcosProjetoByProjetoNaoVencido($projeto)
    {
       $date_hoje = date('Y-m-d H:i:s');
      
         if(!$ordem){
             $ordem = 'asc';
         }
         
         if($campo == null){
             $campo = 'start';
         }
         
         $this->db->select('*')
           // ->join('users', 'planos.responsavel = users.id', 'left')
           // ->join('setores', 'users.setor_id = setores.id', 'left')  
           // ->join('superintendencia', 'setores.superintendencia = superintendencia.id', 'left')        
           // ->join('atas', 'planos.idatas = atas.id', 'left')
         
          // ->order_by('tipo', 'asc')      
           ->order_by($campo, $ordem);
      
       
         $q = $this->db->get_where('calendar', array('projeto' => $projeto, 'end >=' => $date_hoje));
        
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
        public function getMarcoByID($id)
    {
        $q = $this->db->get_where('calendar', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
         public function updateMarco($id, $data  = array())
    {  
     if ($this->db->update('calendar', $data, array('id' => $id))) {
           
         return true;
        }
        return false;
    }
    
    public function deleteMarco($id)
    {         
       // $sale_items = $this->resetSaleActions($id);
        if ($this->db->delete('calendar', array('id' => $id))){
            
            return true;
        }
        return FALSE;
    }
    
    
    
    /*
     * TREINAMENTOS COM ALGUMA AVALIAÇÃO
     */
       public function getAllTreinamentosProjeto()
    {
         $usuario = $this->session->userdata('user_id');
         $projetos_usuario = $this->site->getProjetoAtualByID_completo($usuario);
           
         $this->db->select('atas.id as id, atas.id as ata, projeto, data_ata, local, pauta, responsavel_elaboracao ')
         ->join('projetos', 'atas.projetos = projetos.id', 'left')
         ->order_by('id', 'desc');
         $q = $this->db->get_where('atas', array('atas.projetos' => $projetos_usuario->projeto_atual, 'atas.tipo' => 'TREINAMENTO'));
          
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
    /*
     * PEGA TODOS OS ITENS DE EVENTO, EVENTOS E FASE DE UM PROJETO
     */
     public function getAllFasesAndItemEventosProjeto($id)
    {
        
         $this->db->select('item_evento.id as id, item_evento.descricao as item, eventos.nome_evento as evento, eventos.tipo as fase')
            ->join('eventos', 'item_evento.evento = eventos.id', 'left');
           // ->join('setores', 'users.setor_id = setores.id', 'left')  
           // ->join('superintendencia', 'setores.superintendencia = superintendencia.id', 'left')        
           // ->join('atas', 'planos.idatas = atas.id', 'left')
         //->order_by('eventos.tipo', 'ASC');
        
         $q = $this->db->get_where('item_evento', array('eventos.projeto' => $id));
         
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
    
    /*
     * PEGA TODOS OS MÓDULOS DE UM PROJETO
     */
     public function getAllModulosByProjeto($id)
    {
      
        
       $q = $this->db->get_where('modulos', array('projeto' => $id));
         
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
    /*
     * PEGA TODAS AS FUNÇÕES DE UM MÓDULO
     */
     public function getAllFuncaoByModulo($id)
    {
      
        
       $q = $this->db->get_where('modulos_funcao', array('modulo' => $id));
         
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
    
    
    /*******************************************
     ********** DOCUMENTAÇÃO  ******************
     *******************************************/
    
        public function addDocumento($data)
    {
            if ($this->db->insert('documentacao', $data)) {
                $id =  $this->db->insert_id();
                 
               return $id;
        }
          
        return false;
    }
    
     public function getDocumentacaoByID($id)
    {
        $q = $this->db->get_where('documentacao', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
     public function updateDocumentacao($id, $data  = array())
    {  
        if ($this->db->update('documentacao', $data, array('id' => $id))) {
         return true;
        }
        return false;
    }

    
    
        public function deleteDocumentacao($id)
    {
       // $sale_items = $this->resetSaleActions($id);
        if ($this->db->delete('documentacao', array('id' => $id))){
            
            return true;
        }
        return FALSE;
    }

    public function getAllDocumentacao($id) {
        $this->db->select('*')
         ->order_by('nome_documento', 'ASC');
        
         $q = $this->db->get_where('documentacao', array('projeto' => $id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
    
    
    
    /*
     * T A P
     */
    
         public function addTap($data)
    {
            if ($this->db->insert('documentacao_sessao', $data)) {
                 $this->db->insert_id();
                 
               return true;
        }
          
        return false;
    }
    
     public function getTapByProjeto($id)
    {
       // $this->db->select('item_evento.id as id, item_evento.descricao as item, eventos.nome_evento as evento, eventos.tipo as fase')
        //    ->join('eventos', 'item_evento.evento = eventos.id', 'left');
        $q = $this->db->get_where('documentacao_sessao', array('documentacao' => $id));
          if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
       public function getTapbyId($id)
    {
        $q = $this->db->get_where('documentacao_sessao', array('id' => $id), 1);
         
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
     public function updateTap($id, $data  = array())
    {  
        if ($this->db->update('documentacao_sessao', $data, array('id' => $id))) {
         return true;
        }
        return false;
    }

    
    
        public function deleteTap($id)
    {
       // $sale_items = $this->resetSaleActions($id);
        if ($this->db->delete('documentacao_sessao', array('id' => $id))){
            
            return true;
        }
        return FALSE;
    }

    
    /*******************************************
     ********** DOCUMENTOS  ******************
     *******************************************/
    
        public function addDocumentos($data, $usuarios  = array())
    {
           
            if ($this->db->insert('documentos', $data)) {
                 $id = $this->db->insert_id();
                 
                 
                  if($usuarios){
               //  $this->db->delete('documentos_usuarios', array('documento' => $id));
                 
                  foreach ($usuarios as $usuario_doc) {
                        $vinculo= array('documento' => $id,
                                        'usuario_setor' => $usuario_doc);      
                        $this->db->insert('documentos_usuarios', $vinculo);
                 }
               }
               
              
               
                 
               return $id;
        }
          
        return false;
    }
    
    
     public function addDocumentosSetores($id, $setores = array())
    {
         
          foreach ($setores as $setores_doc) {
                $vinculo_setor = array('documento' => $id, 'setor' => $setores_doc);      
                
                $this->db->insert('documentos_setores', $vinculo_setor); 
               // $id_setor = $this->db->insert_id();
             //   print_r($vinculo_setor);
             //   echo $id_setor.'<br>';
                
               
        
           }
                
            //  echo 'aqui';
            //  exit;
              
               return true;
                 
     }
    
    
    public function getAllDocumentos($id) {
        $this->db->select('*')
         ->order_by('nome_documento', 'ASC');
        
         $q = $this->db->get_where('documentos', array('projeto' => $id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
    
    public function getDocumentoById($id)
    {
        $q = $this->db->get_where('documentos', array('id' => $id), 1);
         
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
       public function updateDocumentoByIdp($id, $data  = array(), $usuarios  = array() , $setores  = array())
    {  
        if ($this->db->update('documentos', $data, array('id' => $id))) {
            
          if($usuarios){
                 $this->db->delete('documentos_usuarios', array('documento' => $id));
                 
                  foreach ($usuarios as $usuario_doc) {
                        $vinculo= array('documento' => $id,
                                        'usuario_setor' => $usuario_doc);      
                        $this->db->insert('documentos_usuarios', $vinculo);
                 }
               }
               
               
           if($setores){
               $this->db->delete('documentos_setores', array('documento' => $id));
               
               foreach ($setores as $setores_doc) {
                $vinculo_setor = array('documento' => $id,
                                       'setor' => $setores_doc);      
                $this->db->insert('documentos_setores', $vinculo_setor); 
                
                 }    
        
           }
               
               
            
         return true;
        }
        return false;
    }
    
        public function getAllUserSetor($id) {
        
         $q = $this->db->get_where('documentos_usuarios', array('documento' => $id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
     public function getAllDocumentoSetor($id) {
       
         $q = $this->db->get_where('documentos_setores', array('documento' => $id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    /*
     * RETORNA O GRUPO DO DOCUMENTO
     */
     public function getAllGrupoDocumentoByProjeto($projeto)
    {
        
         $this->db->select("distinct(grupo_documento) as grupo");
         $q = $this->db->get_where('documentos', array('projeto' => $projeto));
        
        
       if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
    
    
     public function getAllDocumentosProjetoByGrupo($grupo)
    {
      
         
         $this->db->select('*')
           // ->join('users', 'planos.responsavel = users.id', 'left')
           // ->join('setores', 'users.setor_id = setores.id', 'left')  
           // ->join('superintendencia', 'setores.superintendencia = superintendencia.id', 'left')        
           // ->join('atas', 'planos.idatas = atas.id', 'left')
         
          // ->order_by('tipo', 'asc')      
           ->order_by('nome_documento', 'asc');
      
        
         $q = $this->db->get_where('documentos', array('grupo_documento' => $grupo));
        
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
     /*
     * PEGA TODOS OS MÓDULOS DE UM PROJETO
     */
     public function getAllAcoesbyItemEvento($id)
    {
      
       $this->db->select('*')
       ->order_by('status', 'asc');
        
       $q = $this->db->get_where('planos', array('eventos' => $id));
         
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    
      public function getEventoItemEventoByIdItemEvento($id)
    {
            $this->db->select('item_evento.id as id, item_evento.descricao as item, eventos.nome_evento as evento, eventos.tipo as fase')
            ->join('eventos', 'item_evento.evento = eventos.id', 'left');
        $q = $this->db->get_where('item_evento', array('item_evento.id' => $id), 1);
          if ($q->num_rows() > 0) {
           
             return $q->row();
        }
        return FALSE;
    }
    
      public function getUsuarioSetorById($id)
    {
          
         //   $this->db->select('users.first_name as nome, users.last_name as last, setores.nome as setor')
         //   ->join('users', 'users_setores.usuario = users.id', 'left')
         //   ->join('setores', 'users_setores.setor = setores.id', 'left');
        $q = $this->db->get_where('users', array('users.id' => $id), 1);
          if ($q->num_rows() > 0) {
           
             return $q->row();
        }
        return FALSE;
    }
    
    
    /*
     * 
     */
    /*
     * AS ÁREAS QUE TEM AÇÕES NO PROJETO, SUPERINTENDENCIA OU PRESTADORES
     */
    
     public function getFasesByProjeto($id)
    {
        $select =   array('tipo');
        $this->db->select($select)
        ->distinct()
       // ->join('prestadores',      'setores.prestador = prestadores.id', 'left')
       //  ->join('superintendencia', 'setores.superintendencia = superintendencia.id', 'left')
      ->order_by('data_inicio', 'asc');
      $q = $this->db->get_where('eventos', array('projeto' => $id));    
      
         
      if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
    
    
    /*
     * PEGA OS EVENTOS DISTINTOS DE UM PROJETO
     */
     public function getEventosByProjeto($id)
    {
          $this->db->select('*')
      //  $this->db->select($select)
        //->distinct()
       // ->join('prestadores',      'setores.prestador = prestadores.id', 'left')
       //  ->join('superintendencia', 'setores.superintendencia = superintendencia.id', 'left')
      ->order_by('tipo', 'asc');
      $q = $this->db->get_where('eventos', array('projeto' => $id));    
      
         
      if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
    
    
    /*
     * PEGA OS ITENS DE EVENTOS DISTINTOS DE UM PROJETO
     */
     public function getItemEventosByProjeto($id)
    {
          $this->db->select('*')
      //  $this->db->select($select)
        //->distinct()
       // ->join('prestadores',      'setores.prestador = prestadores.id', 'left')
       //  ->join('superintendencia', 'setores.superintendencia = superintendencia.id', 'left')
      ->order_by('descricao', 'asc');
      $q = $this->db->get_where('item_evento', array('evento' => $id));    
      
         
      if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
    
    /*
     *  MELHORIAS
     */
    
      public function addMelhorias($dados)
    {
                $this->db->insert('melhorias', $dados); 
         
               return true;
                 
     }
     
         public function getAllMelhoria($id) {
        $this->db->select('*')
         ->order_by('mes', 'ASC');
       //  ->order_by('ano', 'ASC');
         $q = $this->db->get_where('melhorias', array('projeto' => $id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
     public function getMelhoriaById($id)
    {
        $q = $this->db->get_where('melhorias', array('id' => $id), 1);
         
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
       public function updateMelhoriaById($id, $data  = array() )
    {  
        if ($this->db->update('melhorias', $data, array('id' => $id))) {
           return true;
        }
        return false;
    }
    
        public function deleteMelhoria($id)
    {         
       // $sale_items = $this->resetSaleActions($id);
        if ($this->db->delete('melhorias', array('id' => $id))){
            
            return true;
        }
        return FALSE;
    }
    
    
    /*
     * BI - EMPRÉSTIMO
     */
    
     
       public function emprestimo_entrada_fornecedor($id)
    {         
        $db_tasy = $this->load->database('tasy', TRUE);
        
        $query_pizza = "select distinct CD_PESSOA_JURIDICA,PJ.DS_RAZAO_SOCIAL, 
                  (select count(*) as emprestimo from emprestimo ep
                  where ep.cd_pessoa_juridica = e.cd_pessoa_juridica
                  and ep.dt_emprestimo between '01/06/2018' and '30/06/2018'
                  and ep.cd_local_estoque = 65
                  and ep.ie_tipo = 'E'
                  ) as quantidade_emprestimo,

                    (select sum(qt_emprestimo) as quantidade_emprestimo from emprestimo ep
                    inner join emprestimo_material em on em.nr_emprestimo = ep.nr_emprestimo
                    where ep.cd_pessoa_juridica = e.cd_pessoa_juridica
                    and ep.dt_emprestimo between '01/06/2018' and '30/06/2018'
                    and ep.cd_local_estoque = 65
                    and ep.ie_tipo = 'E'
                    ) as quantidade_material

                    from emprestimo e
                    inner join pessoa_juridica pj on pj.cd_cgc = e.cd_pessoa_juridica
                    where e.dt_emprestimo between '01/06/2018' and '30/06/2018'
                    and e.cd_local_estoque = 65
                    and e.ie_tipo = 'E'";
        
         $q = $oracle->get_where('emprestimo', array('cd_local_estoque' => 65, 'ie_tipo' => 'E'));
        //    $q = $db_tasy->query($query_pizza);    
           echo 'aqui'; exit;
      // $q = $this->db->query($myQuery, false);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
    
    
    /*
     * PEGA AS PERÍODO DE COMPETENCIA  DO USUÁRIO
     */
     public function getPeriodoCompetenciaHE()
    {
       
       $this->db->select("*")
       ->order_by('id', 'desc')
       ->distinct();
       $this->db->group_by('mes');
       $this->db->group_by('ano');
       $q = $this->db->get('periodo_he');  
       
      if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
    
     public function getUsuarioPeriodoCompetenciaHE($mes, $ano)
    {
       
       $this->db->select("periodo_he.id as id, periodo_he.user_id as user_id, mes, ano, de, ate, first_name as nome, last_name as sobrenome, status_verificacao")//
       ->join('users', 'periodo_he.user_id = users.id', 'inner')
       ->order_by('first_name', 'asc');
       //->distinct();
        
       $q = $this->db->get_where('periodo_he', array('mes' => $mes, 'ano' => $ano));  
       
      if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
       public function getUsuarioHorario($mes, $ano)
    {
       
       $this->db->select("user_horario.id as id,  first_name as nome, last_name as sobrenome")//
       ->join('users', 'user_horario.user = users.id', 'inner')
       ->order_by('first_name', 'asc');
       //->distinct();
        
       $q = $this->db->get_where('user_horario');  
       
      if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
     public function updateStatusHESolicitacao($id, $data  = array())
    {  
       
          
    if ($this->db->update('periodo_he_registros', $data, array('id' => $id))) {
           
         return true;
        }
        return false;
    }
    
    
    /*
     * SALVA NO RH3
     */
      public function updateDadosHoraExtraRH3($data, $hora_extra, $cpf, $dia, $hora_credito, $justificativa, $hora_extra_normal, $diferenca_credito_hora)
    {
    
          include 'conexao_rh3.php';
         
          
          
          if($diferenca_credito_hora == '00:00'){
            $hora_credito_rh3 = "";
           }else{
            $hora_credito_rh3 = "30/12/99 '||'$diferenca_credito_hora'||':00,000000";
           }
          
          
           if($hora_extra_normal == '00:00'){
             $hora_extra_rh3 = "";
           }else{
            $hora_extra_rh3 = "30/12/99 '||'$hora_extra_normal'||':00,000000"; 
           }       
          
           /*
            IF($data == '10/11/2018'){
          
            ECHO $data.'<br>';
            echo $hora_extra_normal.'<br>';
            echo $hora_extra_rh3;
            EXIT;  
          }
            * 
            */
          
          
          
          $query_update_espelho = "update  pt_espelhoponto ep
                                set ep.qtdhorasextras = '$hora_extra_rh3',
                                ep.bh_qtdcredito = '$hora_credito_rh3'
                                where ep.dataponto = '$data'
                                and ep.idpessoa in (select p.id from pessoas p  where p.datarescisao is null and p.cpf in '$cpf' and p.tipopessoa ='F') ";
          $update_Espelho = oci_parse($ora_conexao,$query_update_espelho);
                               oci_execute($update_Espelho, OCI_NO_AUTO_COMMIT);
             
                               
            
                               
                               
                               
              $r = oci_commit($ora_conexao);
            if (!$r) {
                $e = oci_error($ora_conexao);
                trigger_error(htmlentities($e['message']), E_USER_ERROR);
            }             
                               
      //    echo $query_update_espelho.'<br>'; 
     //     echo ' --------------------- <br>';                     
     //  echo 'Query Insert : '.$query_insert_justificativa .'<br>'; 
 

         
    }
    
     public function insertDadosHoraExtraRH3($data, $hora_extra, $cpf, $dia, $hora_credito, $justificativa, $hora_extra_normal, $diferenca_credito_hora)
    {
    
          include 'conexao_rh3.php';
         
       
// insert na tabela de justificativas

  $query_insert_justificativa = "insert into pt_justificativas j (IDPESSOA, 
                                 IDJUSTIFICATIVA, 
                                 DATAESPELHOPONTO, 
                                 DATACADASTRO, 
                                 IDUSUARIO, 
                                 IDPESSOAORIGEM, 
                                 OBSERVACAO, 
                                 QTDHORASABONOFALTA,
                                 QTDHORASABONOFALTACOEFICIENTE) 
                                 
VALUES (
        (select p.id from pessoas p  where p.datarescisao is null and p.cpf in '$cpf' and p.tipopessoa ='F'),
        '42',
       (select ep.dataponto from pt_espelhoponto ep where ep.dataponto = '$data' and ep.idpessoa in (select p.id from pessoas p  where p.datarescisao is null and p.cpf in '$cpf' and p.tipopessoa ='F')),
       (select to_char(sysdate,'DD/MM/YYYY') from dual),
       '981',
       (select p.id from pessoas p  where p.datarescisao is null and p.cpf in '$cpf' and p.tipopessoa ='F'),
       '$justificativa',
       (select  to_char(ep.qtdhorasextras,'DD/MM/YY HH24:MI:SS') from pt_espelhoponto ep where ep.dataponto = '$data' and ep.idpessoa in (select p.id from pessoas p  where p.datarescisao is null and p.cpf in '$cpf' and p.tipopessoa ='F')),
       (select  to_char(ep.qtdhorasextras,'DD/MM/YY HH24:MI:SS') from pt_espelhoponto ep where ep.dataponto = '$data' and ep.idpessoa in (select p.id from pessoas p  where p.datarescisao is null and p.cpf in '$cpf' and p.tipopessoa ='F')))";
  $insert_justificativa = oci_parse($ora_conexao,$query_insert_justificativa);
 // oci_bind_by_name($insert_justificativa, ':justificativa', $justificativa);
                               oci_execute($insert_justificativa);
  
            $r2 = oci_commit($ora_conexao);
            if (!$r2) {
                $e = oci_error($ora_conexao);
                trigger_error(htmlentities($e['message']), E_USER_ERROR);
            } 
            
         //   echo 'Query Insert : '.$query_insert_justificativa .'<br>'; 

       

    }
    
    
    
    
     public function updateStatusPeriodoHE($id, $data  = array())
    {  
      
          
    if ($this->db->update('periodo_he', $data, array('id' => $id))) {
           
         return true;
        }
        return false;
    }
    
}
