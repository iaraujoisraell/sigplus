<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Networking_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }
    

    /**********************************************************************
     ************************** HOME *************************** 
     **********************************************************************/
   
    /************ QTDE PROJETOS POR USUARIO***********************************/
    public function getQuantidadeProjetosEquipe(){
        $usuario = $this->session->userdata('user_id');
        $empresa = $this->session->userdata('empresa');
        
       $statement = "SELECT count(*) as quantidade_projetos FROM sig_projetos_equipes ep where user_responsavel = $usuario and empresa_id = $empresa";
      // echo $statement; exit;
        $q = $this->db->query($statement);
        
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
    /************ QTDE AÇÕES POR USUARIO E EMPRESA***********************************/
    public function getQuantidadeAcoesEmpresa(){
        $usuario = $this->session->userdata('user_id');
        $empresa = $this->session->userdata('empresa');
        
       $statement = "SELECT count(*) as quantidade_acao FROM sig_planos p where responsavel = $usuario and  empresa = $empresa and status not in ('ABERTO', 'CANCELADO')";
      // echo $statement; exit;
        $q = $this->db->query($statement);
        
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
  
    /************ QTDE TAREFAS POR USUARIO E EMPRESA***********************************/
    public function getQuantidadeTarefaUserEmpresa(){
        $usuario = $this->session->userdata('user_id');
        $empresa = $this->session->userdata('empresa');
        
       $statement = "SELECT count(*) as quantidade_tarefa FROM sig_tarefas p where user = $usuario and  empresa = $empresa";
      // echo $statement; exit;
        $q = $this->db->query($statement);
        
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
    
    /************ QTDE TAREFAS ABERTAS POR USUARIO E EMPRESA***********************************/
    public function getQuantidadeTarefaAbertasUserEmpresa(){
        $usuario = $this->session->userdata('user_id');
        $empresa = $this->session->userdata('empresa');
        
       $statement = "SELECT count(*) as quantidade_tarefa_aberta FROM sig_tarefas p where user = $usuario and  empresa = $empresa and status = 0";
      // echo $statement; exit;
        $q = $this->db->query($statement);
        
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
    
    /******************QUTDE AÇÕES POR USUARIO*****************************************/
    
    
    
     /************ QTDE AÇÕES PENDENTES POR USUARIO E EMPRESA***********************************/
    public function getQuantidadeAcoesPendenteUserEmpresa(){
        $usuario = $this->session->userdata('user_id');
        $empresa = $this->session->userdata('empresa');
        $dataHoje = date('Y-m-d');
        
       $statement = "SELECT count(*) as qtde_acoes_pendentes FROM sig_planos p where responsavel = $usuario and status = 'PENDENTE' and '$dataHoje' <= data_termino  and empresa = $empresa";
       //echo $statement; exit;
        $q = $this->db->query($statement);
        
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
     /************ QTDE AÇÕES AGUARDANDO VALIDAÇÃO POR USUARIO E EMPRESA***********************************/
    public function getQuantidadeAcoesAguardandoValidacaoUserEmpresa(){
        $usuario = $this->session->userdata('user_id');
        $empresa = $this->session->userdata('empresa');
        $dataHoje = date('Y-m-d');
        
       $statement = "SELECT count(*) as qtde_acoes_aguardando FROM sig_planos p where responsavel = $usuario and status = 'AGUARDANDO VALIDAÇÃO' and empresa = $empresa";
       //echo $statement; exit;
        $q = $this->db->query($statement);
        
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
      /************ QTDE AÇÕES ATRASADAS POR USUARIO E EMPRESA***********************************/
    public function getQuantidadeAcoesAtrasadasUserEmpresa(){
        $usuario = $this->session->userdata('user_id');
        $empresa = $this->session->userdata('empresa');
        $dataHoje = date('Y-m-d');
        
        $statement = "SELECT count(*) as qtde_acoes_atrasadas FROM sig_planos p where responsavel = $usuario and status = 'PENDENTE' and '$dataHoje' > data_termino  and empresa = $empresa";
       //echo $statement; exit;
        $q = $this->db->query($statement);
        
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
   /************ QTDE AÇÕES ATRASADAS POR USUARIO E EMPRESA***********************************/
    public function getQuantidadeAcoesConcluidasUserEmpresa(){
        $usuario = $this->session->userdata('user_id');
        $empresa = $this->session->userdata('empresa');
        $dataHoje = date('Y-m-d');
        
        $statement = "SELECT count(*) as qtde_acoes_concluidas FROM sig_planos p where responsavel = $usuario and status = 'CONCLUÍDO' and empresa = $empresa";
       //echo $statement; exit;
        $q = $this->db->query($statement);
        
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
    /*************FIM QTDE AÇÕES******************************************/
    
    
    /******P L A N O * * D E * * A Ç Õ E S*****************/ 
    
    /************ PLANO DE AÇÕES ABERTO *************************/
    public function getQuantidadePlanoAcaoAbertoByUser(){
        $usuario = $this->session->userdata('user_id');
        $empresa = $this->session->userdata('empresa');
        $dataHoje = date('Y-m-d');
        
        $statement = "SELECT count(*) as quantidade FROM sig_plano_acao where usuario = $usuario and empresa = $empresa and networking = 1 and (status = 0 or status is null)";
       //echo $statement; exit;
        $q = $this->db->query($statement);
        
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
   
    /************ PLANO DE AÇÕES FECHADO *************************/
    public function getQuantidadePlanoAcaoFechadoByUser(){
        $usuario = $this->session->userdata('user_id');
        $empresa = $this->session->userdata('empresa');
        $dataHoje = date('Y-m-d');
        
        $statement = "SELECT count(*) as quantidade FROM sig_plano_acao where usuario = $usuario and empresa = $empresa and networking = 1 and status = 1 ";
       //echo $statement; exit;
        $q = $this->db->query($statement);
        
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    /*********FIM PLANO DE AÇÃO****************************/
    
    
    /******************** M I N H A S * * A T A S *****************/ 
    
     /************ ATAS ABERTO *************************/
    public function getQuantidadeAtasAbertoByUser(){
        $usuario = $this->session->userdata('user_id');
        $empresa = $this->session->userdata('empresa');
        $dataHoje = date('Y-m-d');
        
        $statement = "SELECT count(*) as quantidade FROM sig_atas where usuario_criacao = $usuario and empresa = $empresa and networking = 1 and (status = 0 or status is null)";
       //echo $statement; exit;
        $q = $this->db->query($statement);
        
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
   
    /************ ATAS FECHADO *************************/
    public function getQuantidadeAtasFechadoByUser(){
        $usuario = $this->session->userdata('user_id');
        $empresa = $this->session->userdata('empresa');
        $dataHoje = date('Y-m-d');
        
       $statement = "SELECT count(*) as quantidade FROM sig_atas where usuario_criacao = $usuario and empresa = $empresa and networking = 1 and status = 1 ";
        //echo $statement; exit;
        $q = $this->db->query($statement);
        
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
    /********* F I M   M I N H A S   A T A S **********************/
    
    // ATAS VINCULADAS
    
    /**********************************************************
     ***** RETORNA A QTDE ATAS QUE O USUÁRIO TA VINCULADO ***********
     ********************************************************/
    public function getQuantidadeVinculoAtasByUser(){
        $usuario = $this->session->userdata('user_id');
        $empresa = $this->session->userdata('empresa');
        
       $statement = "SELECT count(*) as quantidade
                    FROM sig_ata_usuario s
                    inner join sig_atas a on a.id = s.atas_id
                    inner join sig_users_setor t on t.id = s.id_usuario
                    inner join sig_users u on u.id = t.users_id
                    where u.id = $usuario and u.empresa_id = $empresa ";
       
        $q = $this->db->query($statement);
        
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
    
     /**********************************************************
     ***** RETORNA A QTDE DE TAREFAS DO USUÁRIO ***********
     ********************************************************/
    
    // Tarefas Abertas
    public function getQuantidadeTarefasAbertasByUser(){
        $usuario = $this->session->userdata('user_id');
        $empresa = $this->session->userdata('empresa');
        
       $statement = "SELECT count(*) as quantidade
                    FROM sig_tarefas 
                    where user = $usuario and empresa = $empresa and status = 0 ";
        $q = $this->db->query($statement);
        
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
    // Tarefas Abertas
    public function getQuantidadeTarefasFechadaByUser(){
        $usuario = $this->session->userdata('user_id');
        $empresa = $this->session->userdata('empresa');
        
       $statement = "SELECT count(*) as quantidade
                    FROM sig_tarefas 
                    where user = $usuario and empresa = $empresa and status = 1 ";
        $q = $this->db->query($statement);
        
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
    
    /**************************************minha agenda**********************************/
    /************ MINHA AGENDA 7 DIAS***********************************/
    public function getMinhaAgendaByUserEmpresa(){
        $usuario = $this->session->userdata('user_id');
        $empresa = $this->session->userdata('empresa');
        $dataHoje = date('Y-m-d');
        
        $statement = "SELECT * FROM sig_invites i
                        inner join sig_users_setor us on us.id = i.user_destino
                        where us.users_id = $usuario and i.empresa = $empresa and confirmacao in (1,2)
                        and data_evento between CURDATE() and (CURDATE() + 7 ) order by data_evento asc";
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
    
    /************ MINHA AGENDA 7 DIAS***********************************/
    public function getMArcosProjetoByProjetoEmpresa(){
        $usuario = $this->session->userdata('user_id');
        $empresa = $this->session->userdata('empresa');
        $dataHoje = date('Y-m-d');
        $data_7_dias = date('Y-m-d', strtotime('+7 days')); 
       
        
        $statement = "-- EQUIPE DO PROJETO
            SELECT distinct m.id as marco, null as invite, null as feriado,   p.title as projeto, m.empresa_id,m.descricao,m.data_prevista, null as hora_inicio, null as hora_fim, null as tipo_invite
            FROM sig_projetos_marcos m
            inner join sig_projetos p on p.id = m.projetos_id
            inner join sig_projetos_equipes e on e.projeto_id = m.projetos_id
            where e.user_responsavel = $usuario and e.empresa_id = $empresa and data_prevista between '$dataHoje' and '$data_7_dias'

            UNION
            -- PARTES INTERESSADA
            SELECT distinct m.id as marco, null as invite, null as feriado,  p.title as projeto, m.empresa_id,m.descricao,m.data_prevista, null as hora_inicio, null as hora_fim, null as tipo_invite
            FROM sig_projetos_marcos m
            inner join sig_projetos p on p.id = m.projetos_id
            inner join sig_projetos_partes_interessadas i on i.projetos_id = m.projetos_id
            where i.usuario_interessado = $usuario and i.empresa_id = $empresa and data_prevista between '$dataHoje' and '$data_7_dias'

            UNION
            -- GERENTE DO PROJETO
            SELECT distinct m.id as marco, null as invite, null as feriado,  p.title as projeto, m.empresa_id,m.descricao,m.data_prevista, null as hora_inicio, null as hora_fim, null as tipo_invite
            FROM sig_projetos_marcos m
            inner join sig_projetos p on p.id = m.projetos_id
            inner join sig_users_setor us on us.id = p.gerente_area
            where us.users_id = $usuario and p.empresa_id = $empresa and data_prevista between '$dataHoje' and '$data_7_dias'

            UNION
            -- COORDENADOR DO PROJETO
            SELECT distinct m.id as marco, null as invite, null as feriado,  p.title as projeto, m.empresa_id,m.descricao,m.data_prevista, null as hora_inicio, null as hora_fim, null as tipo_invite
            FROM sig_projetos_marcos m
            inner join sig_projetos p on p.id = m.projetos_id
            inner join sig_users_setor us on us.id = p.edp_id
            where us.users_id = $usuario and p.empresa_id = $empresa and data_prevista between '$dataHoje' and '$data_7_dias'
            
            UNION
            -- convites
            SELECT null as marco, i.id as invite, null as feriado,  texto_detalhe, empresa, titulo, data_evento as data_prevista, hora_inicio, hora_fim, tipo as tipo_invite
            FROM sig_invites i
            inner join sig_users_setor us on us.id = i.user_destino
            where us.users_id = $usuario and i.empresa = $empresa and confirmacao in (1,2)
            and data_evento between '$dataHoje' and '$data_7_dias'
            
            UNION
            -- CONVITES
            SELECT NULL as marco, NULL as invite, f.id as feriado, NULL as projeto, NULL, f.descricao, f.data_feriado as data_prevista, null, null, null
            FROM sig_feriados f
            where data_feriado between '$dataHoje' and '$data_7_dias' and f.usuario = 2 and f.empresa = 5
            

            order by data_prevista asc";
      
        $q = $this->db->query($statement);
        
         if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
    
    /*******************F I M    H O M E**************************************************/
    
    
    /***************************************************************************
     ************************* MENU DIREITO ************************************
     **************************************************************************/
    
    /**************** RETORNA OS ANIVERSARIANTES DO MËS ***********/
    public function getAllAniversariantesMes(){
        $usuario = $this->session->userdata('user_id');
        $empresa = $this->session->userdata('empresa');
        
       $statement = "SELECT id, first_name, data_aniversario, gender FROM sig_users where empresa_id = $empresa and data_aniversario != '' and Month(data_aniversario) = Month(Now()) order by data_aniversario asc";
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
    
    //INSERT MENSAGEM ANIVERSARIANTE
     public function addPostagemAniversariante($data  = array())
    {  
     if ($this->db->insert('postagens', $data)) {
          $id = $this->db->insert_id();
         return $id;
        }
        return false;
    }
    
    /*********************************************************************/
    
       //INSERT POST
     public function addPostagemNetworking($data  = array())
    {  
     if ($this->db->insert('postagens', $data)) {
          $id = $this->db->insert_id();
         return $id;
        }
        return false;
    }
    
    
    
    
     /**************** RETORNA OS ANIVERSARIANTES DO MËS ***********/
    public function getAllColaboradoresByEmpresa(){
        $usuario = $this->session->userdata('user_id');
        $empresa = $this->session->userdata('empresa');
        
        $statement = "SELECT id, first_name, avatar, gender, data_aniversario, cargo  FROM sig_users where empresa_id = $empresa and active = 1 order by first_name asc";
        $q = $this->db->query($statement);
        
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
    
    
    /*********************FIM MENU DIREITO***************************************/
    
    
    
    //PROFILE
    /**************** RETORNA AS POSTAGENS DE UM USUÁRIO ***********/
    public function getAllPostProfileByUser(){
        $usuario = $this->session->userdata('user_id');
        $empresa = $this->session->userdata('empresa');
        
        $statement = "SELECT u.id as user_id, p.id as post_id, u.*, p.* 
                                FROM sig_postagens p
                                inner join sig_users u on u.id = p.user_de
                                where user_para = $usuario and p.status = 1 order by data_postagem desc";
        $q = $this->db->query($statement);
        
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
  /**************** RETORNA AS POSTAGENS DE UM USUÁRIO ***********/
    public function getPostagemById($id)
    {
        
        $this->db->select('*')
        ->order_by('id', 'DESC');
        $q = $this->db->get_where('postagens', array('id' => $id));
         
       if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
    
    /********************* PROFILE VISITANTE (quando eu visito o perfil de alguém) **************************************************/
    public function getAllPostProfileVisitanteByUser($usuario){
        $empresa = $this->session->userdata('empresa');
        
        $statement = "SELECT u.id as user_id, p.id as post_id, u.*, p.* 
                                FROM sig_postagens p
                                inner join sig_users u on u.id = p.user_de
                                where user_para = $usuario and p.status = 1 order by data_postagem desc";
        $q = $this->db->query($statement);
        
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
    
    
    /********************* MURAL NETWORKING **************************************************/
    public function getAllPostMuralNetworkingByEmpresa($setor_id){
        $empresa = $this->session->userdata('empresa');
        $usuario = $this->session->userdata('user_id');
      
        
        $statement = "  SELECT distinct id, descricao,mural, user_de, user_para, data_postagem, imagem1, imagem2, imagem3, imagem4, imagem5
                        FROM sig_postagens where mural = 1  and status = 1 and empresa = $empresa

                        union 

                        SELECT distinct p.id as id_post, descricao, mural, user_de, user_para, data_postagem, imagem1, imagem2, imagem3, imagem4, imagem5
                        FROM sig_postagens p
                        inner join sig_users u on u.id = p.user_de
                        inner join sig_users_setor us on us.users_id = u.id
                        inner join sig_setores s on s.id = us.setores_id
                        where status = 1 and us.setores_id = $setor_id and p.empresa = $empresa and tipo = 1 and (mural != 1 or mural is null)

                        order by data_postagem desc limit 30";
        $q = $this->db->query($statement);
        
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
    
    /********************* MURAL NETWORKING **************************************************/
    public function getLastPostInstitucionalByEmpresa(){
        $empresa = $this->session->userdata('empresa');
        $usuario = $this->session->userdata('user_id');
      
        
        $statement = "  SELECT distinct id, descricao,mural, user_de, user_para, data_postagem, imagem1, imagem2, imagem3, imagem4, imagem5
                        FROM sig_postagens where mural = 1  and status = 1 and empresa = $empresa
                        order by data_postagem desc limit 3";
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
    
    
    
    /************ QTDE PROJETOS POR USUARIO***********************************/
    public function getQuantidadeProjetosEquipeProfileVisitante($usuario){
        $empresa = $this->session->userdata('empresa');
        
       $statement = "SELECT count(*) as quantidade_projetos FROM sig_projetos_equipes ep where user_responsavel = $usuario and empresa_id = $empresa";
      // echo $statement; exit;
        $q = $this->db->query($statement);
        
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
    /************ QTDE AÇÕES POR USUARIO E EMPRESA***********************************/
    public function getQuantidadeAcoesEmpresaProfileVisitante($usuario){
        $empresa = $this->session->userdata('empresa');
        
       $statement = "SELECT count(*) as quantidade_acao FROM sig_planos p where responsavel = $usuario and  empresa = $empresa and status not in ('ABERTO', 'CANCELADO')";
      // echo $statement; exit;
        $q = $this->db->query($statement);
        
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
    
        /******************QUTDE AÇÕES POR USUARIO*****************************************/
    
    
    
     /************ QTDE AÇÕES PENDENTES POR USUARIO E EMPRESA***********************************/
    public function getQuantidadeAcoesPendenteUserEmpresaProfileVisitante($usuario){
        $empresa = $this->session->userdata('empresa');
        $dataHoje = date('Y-m-d');
        
       $statement = "SELECT count(*) as qtde_acoes_pendentes FROM sig_planos p where responsavel = $usuario and status = 'PENDENTE' and '$dataHoje' <= data_termino  and empresa = $empresa";
       //echo $statement; exit;
        $q = $this->db->query($statement);
        
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
     /************ QTDE AÇÕES AGUARDANDO VALIDAÇÃO POR USUARIO E EMPRESA***********************************/
    public function getQuantidadeAcoesAguardandoValidacaoUserEmpresaProfileVisitante($usuario){
        $empresa = $this->session->userdata('empresa');
        $dataHoje = date('Y-m-d');
        
       $statement = "SELECT count(*) as qtde_acoes_aguardando FROM sig_planos p where responsavel = $usuario and status = 'AGUARDANDO VALIDAÇÃO' and empresa = $empresa";
       //echo $statement; exit;
        $q = $this->db->query($statement);
        
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
      /************ QTDE AÇÕES ATRASADAS POR USUARIO E EMPRESA***********************************/
    public function getQuantidadeAcoesAtrasadasUserEmpresaProfileVisitante($usuario){
        $empresa = $this->session->userdata('empresa');
        $dataHoje = date('Y-m-d');
        
        $statement = "SELECT count(*) as qtde_acoes_atrasadas FROM sig_planos p where responsavel = $usuario and status = 'PENDENTE' and '$dataHoje' > data_termino  and empresa = $empresa";
       //echo $statement; exit;
        $q = $this->db->query($statement);
        
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
   /************ QTDE AÇÕES ATRASADAS POR USUARIO E EMPRESA***********************************/
    public function getQuantidadeAcoesConcluidasUserEmpresaProfileVisitante($usuario){
        $empresa = $this->session->userdata('empresa');
        $dataHoje = date('Y-m-d');
        
        $statement = "SELECT count(*) as qtde_acoes_concluidas FROM sig_planos p where responsavel = $usuario and status = 'CONCLUÍDO' and empresa = $empresa";
       //echo $statement; exit;
        $q = $this->db->query($statement);
        
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
    
    
    /*************FIM QTDE AÇÕES******************************************/
    
    
    
    /*************** FIM PROFILE *************************************/
    
    
    /**********************************************************
     ************************* TAREFAS ************************
     ********************************************************/
    public function getAllTarefas(){
        $usuario = $this->session->userdata('user_id');
        $empresa = $this->session->userdata('empresa');
        
        
        //echo $tabela_empresa;
        //$empresa_db = $this->load->database('provin_clientes', TRUE);
       $statement = "SELECT * FROM sig_tarefas where user = $usuario and empresa = $empresa order by id desc ";
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
    
    public function getLastTarefas(){
        $usuario = $this->session->userdata('user_id');
        $empresa = $this->session->userdata('empresa');
        
        
        //echo $tabela_empresa;
        //$empresa_db = $this->load->database('provin_clientes', TRUE);
       $statement = "SELECT * FROM sig_tarefas where user = $usuario and empresa = $empresa and status = 0 order by data_criacao desc limit 10 ";
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
    
    
    /*****************************************************************************************/
    
    
    /**********************************************************
     ************************* CONVITES ************************
     ********************************************************/
    public function getAllConvitesRecebidos(){
        $usuario = $this->session->userdata('user_id');
        $empresa = $this->session->userdata('empresa');
        
        
        //echo $tabela_empresa;
        //$empresa_db = $this->load->database('provin_clientes', TRUE);
       $statement = "SELECT i.id as id, user_origem, user_destino, confirmacao, data_evento, hora_inicio, hora_fim, tipo, titulo FROM sig_invites i"
               . " inner join sig_users_setor us on us.id = i.user_destino "
               . " where us.users_id = $usuario and i.empresa = $empresa  ";
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
    
     public function getConviteById($invite){
        $usuario = $this->session->userdata('user_id');
        $empresa = $this->session->userdata('empresa');
        
        
        //echo $tabela_empresa;
        //$empresa_db = $this->load->database('provin_clientes', TRUE);
       $statement = "SELECT i.id as id, user_origem, user_destino, confirmacao, data_evento, hora_inicio, hora_fim, tipo, titulo, texto_detalhe, i.obrigatorio as obrigatorio, ciente "
               . " FROM sig_invites i"
               . " inner join sig_users_setor us on us.id = i.user_destino "
               . " where i.id = $invite and us.users_id = $usuario and i.empresa = $empresa  ";
      // echo $statement; exit;
        $q = $this->db->query($statement);
        
       if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
     public function getRespostaConviteById($invite, $resposta){
        $usuario = $this->session->userdata('user_id');
        $empresa = $this->session->userdata('empresa');
        
        
        //echo $tabela_empresa;
        //$empresa_db = $this->load->database('provin_clientes', TRUE);
       $statement = "SELECT i.id as id, user_origem, user_destino, confirmacao, data_evento, hora_inicio, hora_fim, tipo, titulo, texto_detalhe, i.obrigatorio as obrigatorio, ciente "
               . " FROM sig_invites i"
               . " inner join sig_users_setor us on us.id = i.user_destino "
               . " where i.id = $invite and us.users_id = $usuario and i.empresa = $empresa  ";
       //echo $statement; exit;
        $q = $this->db->query($statement);
        
       if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
    //Salva a resposta do usuario referente ao convite
     public function updateRespostaConvite($id, $data  = array())
    {  
        
        if ($this->db->update('invites', $data, array('id' => $id))) {
            
         return true;
        }
        return false;
    }
    
    /********************************* F I M - C O N V I T E S*******************************************************************/
    
    
    /**********************************************************
     ************************* CONVITES ************************
     ********************************************************/
    public function getAllMensagensRecebidos(){
        $usuario = $this->session->userdata('user_id');
        $empresa = $this->session->userdata('empresa');
        
        
        //echo $tabela_empresa;
        //$empresa_db = $this->load->database('provin_clientes', TRUE);
       $statement = "SELECT * FROM sig_emails e"
               . " where e.id_to = $usuario and e.empresa = $empresa order by data_envio desc ";
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
    
    public function getAllMensagensNaoLidas(){
        $usuario = $this->session->userdata('user_id');
        $empresa = $this->session->userdata('empresa');
        
        
        //echo $tabela_empresa;
        //$empresa_db = $this->load->database('provin_clientes', TRUE);
       $statement = "SELECT * FROM sig_emails e"
               . " where e.id_to = $usuario and e.empresa = $empresa and lida = 0 order by data_envio desc ";
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
    
    public function getPlanoByIdAndUsuario($id)
    {
        $usuario = $this->session->userdata('user_id');
        $empresa = $this->session->userdata('empresa');
        $q = $this->db->get_where('planos', array('idplanos' => $id, 'responsavel' => $usuario, 'empresa' => $empresa), 1);
       
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
         
    }
    
    // quantidade de mensagens não lidas por usuário
     public function getQtdeMensagensNaoLidasByUsuario(){
        $usuario = $this->session->userdata('user_id');
        $empresa = $this->session->userdata('empresa');
        
        
        //echo $tabela_empresa;
        //$empresa_db = $this->load->database('provin_clientes', TRUE);
       $statement = "SELECT count(*) as quantidade "
               . " FROM sig_emails e"
               . " where e.lida = 0 and e.id_to = $usuario and e.empresa = $empresa  ";
      // echo $statement; exit;
        $q = $this->db->query($statement);
        
       if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
    // verifica se tem mensagem não lida por ação, USUARIO e empresa
     public function getQtdeMensagensNaoLidasByAcaoAndUsuario($ação){
        $usuario = $this->session->userdata('user_id');
        $empresa = $this->session->userdata('empresa');
        
        
        //echo $tabela_empresa;
        //$empresa_db = $this->load->database('provin_clientes', TRUE);
       $statement = "SELECT count(*) as quantidade "
               . " FROM sig_emails e"
               . " where e.lida = 0 and e.id_to = $usuario and e.empresa = $empresa and idplano = $ação  ";
      // echo $statement; exit;
        $q = $this->db->query($statement);
        
       if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
   
     
    //Salva a resposta do usuario referente ao convite
     public function updatestatusMensagem($id, $data  = array())
    {  
        
        if ($this->db->update('emails', $data, array('idplano' => $id))) {
            
         return true;
        }
        return false;
    }
    
    /********************************* F I M - M E N S A G E M ********************************************/
    
    
    
    /*********************************************************************************************************
     ************************* PLANO DE AÇÃO *****************************************************************
     *********************************************************************************************************/
    
    // RETORNA OS PLANOS DE AÇÃO DO USUÁRIO
    public function getAllPlanoAcaoByUser(){
        $usuario = $this->session->userdata('user_id');
        $empresa = $this->session->userdata('empresa');
        
        //echo $tabela_empresa;
        //$empresa_db = $this->load->database('provin_clientes', TRUE);
       $statement = "SELECT * FROM sig_plano_acao where networking = 1 and empresa = $empresa and usuario = $usuario ";
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
    
    //INSERT CATEGORIA DO PLANO DE AÇÃO
    public function addCategoria_PlanoAcao($data  = array())
    {  
     if ($this->db->insert('plano_acao_categoria', $data)) {
          $id = $this->db->insert_id();
         return $id;
        }
        return false;
    }
    
    public function getAllCategoriaPlanoAcaoByPlano($plano){
        $usuario = $this->session->userdata('user_id');
        $empresa = $this->session->userdata('empresa');
        
        //echo $tabela_empresa;
        //$empresa_db = $this->load->database('provin_clientes', TRUE);
       $statement = "SELECT * FROM sig_plano_acao_categoria where pa = $plano and empresa = $empresa and usuario = $usuario order by ordem asc";
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
    
    //Salva a resposta do usuario referente ao convite
     public function atualizaOrdemCategoriaPlano($id, $data  = array())
    {  
        
        if ($this->db->update('plano_acao_categoria', $data, array('id' => $id))) {
            
         return true;
        }
        return false;
    }
  
    
    /***********************************************************************************
     ***** RETORNA AS ATAS QUE O USUÁRIO CRIOU. A PARTIR DE UM PLANO DE AÇÃO ***********
     ***********************************************************************************/
    public function getAllMinhasAtasByUserAndPlanoAcao($planoAcao){
        $usuario = $this->session->userdata('user_id');
        $empresa = $this->session->userdata('empresa');
        
       $statement = "SELECT * FROM sig_atas "
                 . " WHERE usuario_criacao = $usuario and projetos IS NULL and networking = 1
                     and empresa = $empresa and plano_acao = $planoAcao order by id desc";
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
    
    
     /***********************************************************************************
     ***** RETORNA AS ATAS QUE O USUÁRIO CRIOU. A PARTIR DE UM PLANO DE AÇÃO ***********
     ***********************************************************************************/
    public function getAllCategoriasPlanoByIdPlanoAcao($planoAcao){
        $usuario = $this->session->userdata('user_id');
        $empresa = $this->session->userdata('empresa');
        
       $statement = "SELECT distinct c.id as id, c.descricao as categoria, c.ordem
                    FROM sig_planos p
                    inner join sig_plano_acao_categoria c on c.id = p.categoria_plano
                    where idplano = $planoAcao
                    order by c.ordem asc";
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
     *  RETORNA TODAS AS AÇÕES DE UMA CATEGORIA DE UM PLANO DE AÇÃO
     */
     public function getAllAcaoPlanoAcaoByCategoriaAndPlano($planoAcao, $categoria)
    {
          $statement = "SELECT p.idplanos as id, p.descricao as descricao, p.categoria_plano as categoria_plano, c.descricao as categoria, sequencial, p.status as status, u.first_name as responsavel,  s.nome as setor, data_entrega_demanda, data_termino, como, porque,onde,custo, p.anexo as anexo, valor_custo, horas_previstas, peso  FROM sig_planos p
            inner join sig_users u on u.id = p.responsavel
            inner join sig_setores s on s.id = p.setor
            inner join sig_plano_acao_categoria c on c.id = p.categoria_plano
            where p.idplano = $planoAcao and categoria_plano = $categoria order by c.ordem asc";
           
        $q = $this->db->query($statement);
        
      /*   $this->db->select('planos.*, users.*, setores.*')
            ->join('users', 'planos.responsavel = users.id', 'left')
             ->join('setores', 'planos.setor = setores.id', 'left')      
             
         ->order_by('idplanos', 'desc');
         $q = $this->db->get_where('planos', array('idplano' => $id));
       * 
       */
         
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
    
    
    /**************************************************************************
     ************** R E G I S T R O S * * A T I V I D A D E S ***************** 
     **************************************************************************/
     public function getRegistroAtividadesByUsuario($projeto_filtro, $data_inicio, $data_fim)
    {
        $usuario = $this->session->userdata('user_id');
        $empresa = $this->session->userdata('empresa');
        $statement = "SELECT j.id, r.id as id_rat, j.projeto as projeto, p.data_termino, p.sequencial, r.data_rat as data_rat, hora_inicio, hora_fim, tempo, valor, r.descricao as descricao_rat, p.idplanos, p.descricao as descricao_acao, p.status as status_acao
                    FROM sig_planos_rat r
                    inner join sig_planos p on p.idplanos = r.planoid
                    left join sig_projetos j on j.id = p.projeto
                    where r.usuario = $usuario and r.empresa = $empresa ";  
        
        if($projeto_filtro){
            $statement .= " and j.id = '$projeto_filtro'";
        }
            
            
        if($data_inicio && $data_fim){
            $statement .= " and r.data_rat between '$data_inicio' and '$data_fim' ";
        }else if($data_inicio){
             $statement .= " and r.data_rat >= '$data_inicio' ";
        }else if($data_fim){
             $statement .= " and r.data_rat <= '$data_fim' ";
        }
         
            
        $statement .= " order by r.data_rat asc";    
       
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
     
     
     /************ RETORNA A RAT POR ID***********************************/
    public function getRatByIdAndByEmpresa($id){
        $empresa = $this->session->userdata('empresa');
        $usuario = $this->session->userdata('user_id');
        $dataHoje = date('Y-m-d');
        
        $statement = "SELECT j.id, r.id as id_rat, j.projeto as projeto, p.sequencial, p.data_termino, r.data_rat as data_rat, hora_inicio, hora_fim, tempo, valor, r.descricao as descricao_rat,
                    p.idplanos, p.descricao as descricao_acao, p.status as status_acao
                    FROM sig_planos_rat r
                    inner join sig_planos p on p.idplanos = r.planoid
                    left join sig_projetos j on j.id = p.projeto
                    where r.id = $id and r.usuario = $usuario  and r.empresa = $empresa";
       //echo $statement; exit;
        $q = $this->db->query($statement);
        
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
     
    
    
     /*
     * altera a rat da ação por usuário e empresa
     */
     public function updateRat($rat, $data  = array())
    {  
        $empresa = $this->session->userdata('empresa');
        $usuario = $this->session->userdata('user_id'); 
        if ($this->db->update('planos_rat', $data, array('id' => $rat, 'empresa' => $empresa, 'usuario' => $usuario))) {
            
         return true;
        }
        return false;
    }
    
    
     /*
     * APAGA RAT
     */
    public function deleteRat($id)
    {
       // $sale_items = $this->resetSaleActions($id);
       $usuario = $this->session->userdata('user_id');  
       $empresa = $this->session->userdata('empresa');
       if ($id){
            $this->db->delete('planos_rat', array('id' => $id, 'empresa' => $empresa, 'usuario' => $usuario));
            return true;
        }
        return FALSE;
    }
    
     /************************************************************************/
     
     
      // RETORNA TODOS OS PROJETOS QUE O USUÁRIO TEM AÇÃO; NETWORK > MINHAS AÇÕES
     public function getAllProjetosUserById_User()
    {
       $usuario = $this->session->userdata('user_id');  
       $empresa = $this->session->userdata('empresa');
       $statement = "SELECT distinct j.id as id, j.projeto as projeto
                    FROM sig_planos p
                    left join sig_atas a on a.id = p.idatas
                    left join sig_projetos j on j.id = a.projetos
                    where responsavel = $usuario and p.empresa = $empresa and p.status != 'ABERTO' and p.idatas != ''

                    UNION

                    SELECT distinct pj.id as id, pj.projeto as projeto
                    FROM sig_planos p
                    left join sig_projetos pj on pj.id = p.projeto
                    where responsavel = $usuario and p.empresa = $empresa and p.status != 'ABERTO' and p.projeto != '' ";
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
    
    
    // TODAS AS AÇÕES DO USUÁRIOS > NETWORK
     public function getAllAcoesUserById_User($projeto, $status)
    {
       $usuario = $this->session->userdata('user_id');    
       $empresa = $this->session->userdata('empresa');
       $dataHoje = date('Y-m-d');
      // echo $status.'<br>';
       $statement = "SELECT * FROM sig_planos p
                     where responsavel = $usuario and status != 'ABERTO' and empresa = $empresa";
            if($projeto > 0){
                $statement .= " and projeto = '$projeto'";
            }
            
            if($status){
               
                if($status == "PENDENTE"){
                     $statement .= " and status = '$status' and '$dataHoje' <= data_termino ";
                }else if($status == "ATRASADO"){
                     $statement .= " and status = 'PENDENTE' and '$dataHoje' > data_termino ";
                }else if($status == "CONCLUÍDO"){
                     $statement .= " and status = 'CONCLUÍDO'";
                }else if($status == "AGUARDANDO VALIDAÇÃO"){
                     $statement .= " and status = 'AGUARDANDO VALIDAÇÃO'";
                }else if($status == "CANCELADO"){
                     $statement .= " and status = 'CANCELADO'";
                }
            }else{
              //   $statement .= " and status = 'PENDENTE' and data_termino >= '$dataHoje' ";
            }
            
        $statement .= " order by data_termino DESC ";
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
    
    
     // TODAS AS AÇÕES DO USUÁRIOS > NETWORK
     public function getAllAcoesUserById_UserSemFiltro($projeto)
    {
       $usuario = $this->session->userdata('user_id');    
       $empresa = $this->session->userdata('empresa');
       $dataHoje = date('Y-m-d');
      // echo $status.'<br>';
       $statement = "SELECT * FROM sig_planos p
                     where responsavel = $usuario and status != 'ABERTO' and empresa = $empresa";
            if($projeto > 0){
                $statement .= " and projeto = '$projeto'";
            }
            
           
            
        $statement .= " order by data_termino DESC ";
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
    
    
      public function getAllAcoesProjeto($id_acao, $project)
    {
       $empresa = $this->session->userdata('empresa');
       $usuario = $this->session->userdata('user_id');
    //   $usuario = $this->session->userdata('user_id');
    //  $projetos_usuario = $this->site->getProjetoAtualByID_completo($usuario);
       
       
       
       $statement = "SELECT idplanos, sequencial, idatas, p.data_entrega_demanda as dt_inicio, p.data_termino as dt_termino, p.descricao as descricao, i.descricao as item, nome_evento, nome_fase 
           FROM sig_planos p
                    inner join sig_item_evento i on i.id = p.eventos
                    inner join sig_eventos e on e.id = i.evento
                    inner join sig_fases_projeto f on f.id = e.fase_id
                    left join sig_atas a on a.id = p.idatas
                    where a.projetos = $project and a.empresa = $empresa and p.empresa = $empresa and idplanos not in($id_acao) and idplanos not in(select id_vinculo from sig_acao_vinculos where planos_idplanos = $id_acao) ";
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
    
    
    
}
