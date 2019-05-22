<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Atas_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    
    public function addAtas($data,$participantes)
    {
           // print_r($data); exit;
            
            if ($this->db->insert('atas', $data)) {
                 $id_ata = $this->db->insert_id();
                
                // print_r($usuario_ata); exit;
                
                  foreach ($participantes as $item_participante) {
                        $data_participante = array('atas_id' => $id_ata,
                                                   'participante' => 1,
                                             'id_usuario' => $item_participante);      
                        
                        $this->db->insert('ata_usuario', $data_participante);
                 }
                 
               return $id_ata;
        }
          
        return false;
    }
    
    
    
    public function add_Atas_usuario($ata_usuario)
    {
             
            if ($this->db->insert('ata_usuario', $ata_usuario)) {
                $this->db->insert_id();
                 
                return true;
        }
          
        return false;
    }
    
    public function addHistorico_convocacao($data)
    {
           // print_r($data); exit;
            
            if ($this->db->insert('historico_convocacoes', $data)) {
               
                $id_hc = $this->db->insert_id();
                 
               return $id_hc;
        }
          
        return false;
    }
    
     public function getAtaByIDByProjeto($id, $projeto)
    {
         $this->db->select('count(id) as quantidade');
        $q = $this->db->get_where('atas', array('id' => $id, 'projetos' => $projeto), 1);
     
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
         
    }
    
     public function getAtaByID($id)
    {
        $q = $this->db->get_where('atas', array('id' => $id), 1);
     
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
         
    }
    
    public function getPlanoAcaoByID($id)
    {
        $q = $this->db->get_where('plano_acao', array('id' => $id), 1);
     
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
         
    }
    
     public function getAtaProjetoByID_ATARetornoUsuario($id)
    {
     
         $this->db->select("projetos.id as projeto_id, edp_id as edp")
            ->join('projetos', 'atas.projetos = projetos.id', 'left');
         
         $q = $this->db->get_where('atas', array('atas.id' => $id));
         
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
         
    }
    
       public function getFacilitador_Treinamento($id)
    {
     
         //$this->db->select("projetos.id as projeto_id, edp_id as edp")
         //   ->join('projetos', 'atas.projetos = projetos.id', 'left');
         
         $q = $this->db->get_where('atas_facilitadores', array('ata' => $id));
         
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
         
    }
    
    
     public function getParticipante_Treinamento($id)
    {
         $this->db->select("count(id) as quantidade");
         //   ->join('projetos', 'atas.projetos = projetos.id', 'left');
         
         $q = $this->db->get_where('ata_usuario', array('id_ata' => $id, 'presenca_confirmada' => 'SIM'));
         
       //  ECHO $q->num_rows(); EXIT;
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
         
    }
    
    public function getAtaProjetoByID_ATA($id)
    {
     
         $this->db->select($this->db->dbprefix('atas') . ".id as id,  projetos.projeto as projetos, " ."data_ata, pauta, participantes,  tipo, responsavel_elaboracao, assinaturas, pendencias, atas.local as local, atas.responsavel_elaboracao as responsavel_elaboracao, atas.discussao as discussao, atas.obs as obs")
            ->join('projetos', 'atas.projetos = projetos.id', 'left');
         
         $q = $this->db->get_where('atas', array('atas.id' => $id));
         
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
         
    }
    
    /*
     * VERIFICA SE O USUÁRIO ESTÁ VINCULADO A ATA, PELO ID DA ATA E O ID DO USER
     */
     public function getAtaUserByAtaUser($ata, $user)
    {
        $q = $this->db->get_where('ata_usuario', array('id_ata' => $ata, 'id_usuario' => $user));
    
        if ($q->num_rows() > 0) {
            
           return $q->row();
            
            
        }
        return FALSE;
         
    }
    
    /*
     * 
     */
     /**********************************************************
     ***** RETORNA AS ATAS QUE O USUÁRIO TA VINCULADO ***********
     ********************************************************/
    public function getAllVinculoAtasByUser(){
        $usuario = $this->session->userdata('user_id');
        $empresa = $this->session->userdata('empresa');
      //  $users_dados = $this->site->geUserByID($usuario);
      //  $modulo_atual_id = $users_dados->modulo_atual;
      //  $projeto_atual_id = $users_dados->projeto_atual;
        
       $statement = "SELECT a.id as id, a.id as ata, data_ata, pauta, tipo, responsavel_elaboracao, a.status, a.anexo, a.sequencia as sequencia
                    FROM sig_ata_usuario s
                    inner join sig_atas a on a.id = s.atas_id
                    inner join sig_users_setor t on t.id = s.id_usuario
                    inner join sig_users u on u.id = t.users_id
                    where u.id = $usuario and u.empresa_id = $empresa order by a.id desc";
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
     ***** RETORNA AS ATAS QUE O USUÁRIO CRIOU. NÃO TEM PROJETO ***********
     ********************************************************/
    public function getAllMinhasAtasByUser(){
        $usuario = $this->session->userdata('user_id');
        $empresa = $this->session->userdata('empresa');
      //  $users_dados = $this->site->geUserByID($usuario);
      //  $modulo_atual_id = $users_dados->modulo_atual;
      //  $projeto_atual_id = $users_dados->projeto_atual;
        
       $statement = "SELECT * FROM sig_atas "
                 . " WHERE usuario_criacao = $usuario and projetos IS NULL and networking = 1
                     and empresa = $empresa order by id desc";
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
    
    
     public function getAtaUserByID_ATA($id)
    {
         $statement = "select us.id as id, first_name as nome, s.nome as setor
        from sig_ata_usuario au
        inner join sig_users_setor us on us.id = au.id_usuario
        inner join sig_users u on u.id = us.users_id
        inner join sig_setores s on s.id = us.setores_id
        where au.atas_id = $id
        order by nome";    
       //echo $statement; exit;
        $q = $this->db->query($statement);
     //   $q = $this->db->get_where('ata_usuario', array('atas_id' => $id, 'id_usuario !=' => ""));
    
        if ($q->num_rows() > 0) {
            
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
            
          //  return $q->row();
            
            
        }
        return FALSE;
         
    }
    
    //usuário adicionados como participantes na ata e ainda não confirmado - usado na tela de convite
     public function getConvitesAtaUserByID_ATA($id)
    {
         $statement = "select us.id as id, first_name as nome, s.nome as setor, i.ciente as ciente, i.confirmacao as confirmacao, au.participante as participante, au.vinculo as vinculo
                    from sig_ata_usuario au
                    inner join sig_users_setor us on us.id = au.id_usuario
                    inner join sig_atas a on a.id = au.atas_id
                    inner join sig_users u on u.id = us.users_id
                    inner join sig_setores s on s.id = us.setores_id
                    left join sig_invites i on i.ata = au.atas_id
                    where au.atas_id = $id and a.status != 2 and i.confirmacao IS NULL and ciente is NULL
                    order by nome";    
       
        $q = $this->db->query($statement);
     //   $q = $this->db->get_where('ata_usuario', array('atas_id' => $id, 'id_usuario !=' => ""));
    
        if ($q->num_rows() > 0) {
            
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
            
          //  return $q->row();
            
            
        }
        return FALSE;
         
    }
    
    /****************************************************************************
     **************************** I N V I T E S ********************************* 
     ***************************************************************************/
    
    //LISTA OS INVITES DE UMA ATA
    public function getAllInvitesByAta($ata){
        $usuario = $this->session->userdata('user_id');
        $empresa = $this->session->userdata('empresa');
       $statement = "SELECT i.id as id, ciente, u.first_name as nome, s.nome as setor, i.data_criacao as data_criacao,  i.data_confirmacao as data_confirmacao, data_evento, hora_inicio, hora_fim, titulo, local, confirmacao,obrigatorio, i.status as status FROM sig_invites i
                    inner join sig_users_setor us on us.id = i.user_destino
                    inner join sig_users u on u.id = us.users_id
                    inner join sig_setores s on s.id = us.setores_id
                    where i.empresa = $empresa and i.ata = $ata ";
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
     * ADICIONA INVITE
     */
    public function addInviteAta($data_ata_usuario)
    {  
            if($this->db->insert('invites', $data_ata_usuario)){
                  
                  return true;
            }
            
      
    }
    
    
     public function getAtaUserParticipante_ByID_ATA($id)
    {
      
          $statement = "select distinct us.id as id, first_name as nome, s.nome as setor, au.participante as participante, au.vinculo as vinculo, confirmacao
                        from sig_ata_usuario au
                        inner join sig_users_setor us on us.id = au.id_usuario
                        inner join sig_users u on u.id = us.users_id
                        inner join sig_setores s on s.id = us.setores_id
                        left join sig_invites i on i.user_destino = au.id_usuario and i.ata = $id
                        where au.atas_id = $id
                        order by nome";    
       
        $q = $this->db->query($statement);
         
       // $q = $this->db->get_where('ata_usuario', array('atas_id' => $id, 'id_participante !=' => ""));
       // echo 'aquiii'.  $q->num_rows(); exit;
        if ($q->num_rows() > 0) {
            
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
            
          //  return $q->row();
            
            
        }
        return FALSE;
         
    }
    
    
    
    
    public function getAtaUserNameByID_ATA($id)
    {
     
         $this->db->select('users.*')
            ->join('users', 'ata_usuario.id_usuario = users.id', 'left');
         //$q = $this->db->get('ata_usuario');
         $q = $this->db->get_where('ata_usuario', array('id_ata' => $id));
    
        if ($q->num_rows() > 0) {
            
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
            
          //  return $q->row();
            
            
        }
        return FALSE;
         
    }
    
     public function addParticipanteAta($id, $participante)
    {  
        
     
            
            if($participante){
                  // $this->db->delete('ata_usuario', array('id_ata' => $id, 'id_participante !=' => ""));
            
               
                        $data_ata_usuario = array('id_ata' => $id, 'id_usuario' => $participante, 'participante' => 1);      
                        $this->db->insert('ata_usuario', $data_ata_usuario);
                  return true;
            }
            
         
            
        
        
        return false;
    }
    
         public function deleteParticipanteAta($participante, $ata)
    {  
           
            $this->db->delete('ata_usuario', array('id_usuario' => $participante, 'atas_id' => $ata));
            return true;
           
             /*
             
            if($tipo == 1){
                   $this->db->delete('ata_usuario', array('id_participante' => $participante, 'atas_id' => $ata));
               
                  //      $data_ata_usuario = array('id_ata' => $id, 'id_participante' => $participante);      
                  //      $this->db->insert('ata_usuario', $data_ata_usuario);
                  return true;
            }else if($tipo == 2){
                   $this->db->delete('ata_usuario', array('id_usuario' => $participante, 'atas_id' => $ata));
               
                  //      $data_ata_usuario = array('id_ata' => $id, 'id_participante' => $participante);      
                  //      $this->db->insert('ata_usuario', $data_ata_usuario);
                  return true;
            }
              * 
              */
            
         
            
        
        
        return false;
    }
    
     public function updateAta($id, $data  = array(), $participantes_ata= array())
    {  
        
        if ($this->db->update('atas', $data, array('id' => $id))) {
            
            if($participantes_ata){
                  //$this->db->delete('ata_usuario', array('id_usuario' => $item, 'atas_id ' => $id));
            
                foreach ($participantes_ata as $item) {
                        $data_ata_usuario = array('atas_id' => $id, 'participante' => 1, 'id_usuario' => $item);      
                        $this->db->insert('ata_usuario', $data_ata_usuario);
                 }
            }
            
           
            
         return true;
        }
        return false;
    }
    
    /*
     * ATUALIZA NO PLANO DE AÇÃO OS PARTICIPANTES
     */
     public function updateParticipantesAta($id, $participante,  $valor)
    {  
        if ($valor == 1) {
            $data_participante = array('participante' => 1);
        } else if ($valor == 0) {
            $data_participante = array('participante' => 0);
        }

             if ($this->db->update('ata_usuario', $data_participante, array('atas_id' => $id, 'id_usuario' => $participante))) {
                 return true;
            }
       

       
    }
    
     public function updateVinculoAta($id, $participante, $valor)
    {  
         //print_r($participar); exit;
       
        if($valor == 1){
            $data_vinculo = array('vinculo' => 1);       
        }else if($valor == 0){
            $data_vinculo = array('vinculo' => 0);       
        }
        
       if ($this->db->update('ata_usuario', $data_vinculo, array('atas_id' => $id, 'id_usuario' => $participante))) {
                 return true;
            }
                

        
    }
    
    /*
     * ATUALIZA NO PLANO DE AÇÃO OS USUÁRIOS VINCULADOS
     */
     public function updateUsuariosVInvuladoAta($id, $usuarios = array())
    {  
       // print_r($usuarios); exit;
             
            if($usuarios){
                
                foreach ($usuarios as $item) {
                    $this->db->delete('ata_usuario', array('id_ata' => $id, 'id_usuario !=' => null));
                }
                
                foreach ($usuarios as $item) {
                        $data_ata_usuario = array('id_ata' => $id, 'id_usuario' => $item);      
                        $this->db->insert('ata_usuario', $data_ata_usuario);
                 }
                 
                 return true;
                 
            }
            
         
        
        return false;
    }
     
    // FINALIZA A ATA
    public function finalizaAta($id, $data  = array())
    {  
        
        if ($this->db->update('atas', $data, array('id' => $id))) {
            
         return true;
        }
        return false;
    }
    
    // FINALIZA O PLANO DE AÇÃO
     public function updatePlanoAcao($id, $data  = array())
    {  
        
        if ($this->db->update('plano_acao', $data, array('id' => $id))) {
            
         return true;
        }
        return false;
    }
    
    public function deleteAta($id)
    {
       // $sale_items = $this->resetSaleActions($id);
        if ($this->db->delete('atas', array('id' => $id))){
            $this->db->delete('ata_usuario', array('id_ata' => $id));
            return true;
        }
        return FALSE;
    }

    /*
     * ADICIONAR VINCULO
     */
    
     public function AdicionarAudioAta($data_audio= array())
    {  
         /*
         * ações vinculadas
         */
           
            if ($this->db->insert('atas_audios', $data_audio)) {
                $this->db->insert_id();
                 return true;
            }
           
            
            
         
        return false;
    }
    
    
    public function getAllProjetos()
    {
        $q = $this->db->get('projetos');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
    
    
    public function getProjetoByID($id)
    {
          
        $q = $this->db->get_where('projetos', array('id' => $id), 1);
     
        
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
         
    }

    /*
     * PLANOS E AÇÕES
     */
       public function add_planoAcao($ata_plano,$vinculo,$tipo,$responsavel)
    {
          //  print_r($ata_plano); exit;
            if ($this->db->insert('planos', $ata_plano)) {
               $id_acao =  $this->db->insert_id();
              
              // echo $id_acao;
                // ADCIONA TODOS OS vinculos SELECIONADOS
               if($vinculo){
                     $data_vinculo= array('planos_idplanos' => $id_acao,
                            'id_vinculo' => $vinculo,
                            'tipo' => $tipo); 
                     
                  $this->db->insert('acao_vinculos', $data_vinculo);
                  
                 
             
                }
               
               if($avulsa == 'SIM'){
               // $this->ion_auth->emailAtaUsuario($responsavel, $id_acao);
             }
                 
                return $id_acao;
        }
          
        return false;
    }
    
     /*
     * PLANOS E AÇÕES
     */
       public function add_logPlano($ata_plano)
    {
          //  print_r($ata_plano); exit;
            if ($this->db->insert('plano_log', $ata_plano)) {
               $this->db->insert_id();
              
         
                 
                return true;
        }
          
        return false;
    }
    
    /*
     * ALTERA TODAS AS AÇÕES DE UMA ATA
     */
    
     public function updatePlanoAta($id, $data  = array())
    {  
        
        if ($this->db->update('planos', $data, array('idatas' => $id))) {
            
         return true;
        }
        return false;
    }
    
    /*
     * ALTERA TODAS AS AÇÕES DE UM PLANO DE AÇÃO
     */
    
     public function updateAcoesPlanoAcao($id, $data  = array())
    {  
        
        if ($this->db->update('planos', $data, array('idplano' => $id))) {
            
         return true;
        }
        return false;
    }
    
    
     public function getStatusAllPlanosBYAta($id)
    {
          $this->db->select('count(status) as status_pendencias');
         $q = $this->db->get_where('planos', array('idatas' => $id, 'status' => 'PENDENTE'), 1);
       
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
      public function updatePendenciasAllAta($id, $data  = array())
    {  
        
        if ($this->db->update('atas', $data, array('id' => $id))) {
            
         return true;
        }
        return false;
    }
    
    
    /*
     * PEGA TODOS OS PLANOS DE UM PROJETO
     */
     public function getAllitemPlanosProjeto($id)
    {
         
         $this->db->select('planos.idplanos, planos.data_termino,planos.data_retorno_usuario, users.username,planos.status,users.company,users.gestor,users.award_points,setores.nome as setor, superintendencia.responsavel as superintendencia, planos.status as status')
            ->join('users', 'planos.responsavel = users.id', 'left')
            ->join('setores', 'planos.setor = setores.id', 'left')  
            ->join('superintendencia', 'setores.superintendencia = superintendencia.id', 'left')        
            ->join('atas', 'planos.idatas = atas.id', 'left')
         ->order_by('idplanos', 'desc');
        
         $q = $this->db->get_where('planos', array('atas.projetos' => $id));
         
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
    /*
     * PEGA TODOS OS PLANOS DE UM PROJETO e de um setor
     */
     public function getAllitemPlanosProjetoSetor($id, $setor)
    {
         
         $this->db->select('planos.idplanos, planos.data_termino,planos.data_retorno_usuario, users.username,planos.status,users.company,users.gestor,users.award_points,setores.nome as setor, superintendencia.responsavel as superintendencia')
            ->join('users', 'planos.responsavel = users.id', 'left')
            ->join('setores', 'planos.setor = setores.id', 'left') 
            ->join('superintendencia', 'setores.superintendencia = superintendencia.id', 'left')     
            ->join('atas', 'planos.idatas = atas.id', 'left')
         ->order_by('idplanos', 'desc');
         $q = $this->db->get_where('planos', array('atas.projetos' => $id,'planos.setor' => $setor));
        
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
    
     /*
     * PEGA TODAS AS AÇÕES DE UM PROJETO , de um setor e por usuario
     */
     public function getAllitemPlanosProjetoSetorUser($setor, $usuario_acoes)
    {
        $empresa = $this->session->userdata('empresa'); 
        $usuario = $this->session->userdata('user_id');
        $users_dados = $this->site->geUserByID($usuario);
        $projeto_atual = $users_dados->projeto_atual;
        
        $statement = "SELECT p.idplanos, p.data_termino as data_termino,p.data_retorno_usuario,p.descricao as descricao, u.first_name, p.status, s.nome as setor
                    from sig_planos p
                    inner join sig_users u on u.id = p.responsavel
                    inner join sig_users_setor us on us.users_id = u.id
                    inner join sig_setores s on s.id = us.setores_id
                    where us.setores_id = $setor and p.projeto = $projeto_atual and p.empresa = $empresa and u.id = $usuario_acoes ";
       // echo $statement; exit;
        $q = $this->db->query($statement);
        
        /*
         $this->db->select('planos.idplanos, planos.data_termino as data_termino,eventos.nome_evento as eventos,item_evento.descricao as item, planos.data_retorno_usuario,planos.descricao as descricao, users.username,planos.status,setores.nome as setor, superintendencia.responsavel as superintendencia')
            ->join('users', 'planos.responsavel = users.id', 'left')
            ->join('setores', 'planos.setor = setores.id', 'left') 
            ->join('superintendencia', 'setores.superintendencia = superintendencia.id', 'left')     
            ->join('item_evento', 'planos.eventos = item_evento.id', 'left')    
            ->join('eventos', 'item_evento.evento = eventos.id', 'left')      
            ->join('atas', 'planos.idatas = atas.id', 'left')
         ->order_by('idplanos', 'DESC');
         $q = $this->db->get_where('planos', array('atas.projetos' => $id,'planos.setor' => $setor, 'users.id' => $usuario));
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
    
    /*
     * PEGA TODOS OS USUÁRIOS COM AÇÕES DE UM PROJETO e de um setor
     */
     public function getAllUserPlanosProjetoSetor($setor)
    {
        $empresa = $this->session->userdata('empresa'); 
        $usuario = $this->session->userdata('user_id');
        $users_dados = $this->site->geUserByID($usuario);
        $projeto_atual = $users_dados->projeto_atual;
        
        $statement = "SELECT distinct  u.id as id,u.first_name as nome
                     from sig_planos p
                     inner join sig_users u on u.id = p.responsavel
                     inner join sig_users_setor us on us.users_id = u.id
                     where us.setores_id = $setor and p.projeto = $projeto_atual and p.empresa = $empresa ";
        //echo $statement; exit;
        $q = $this->db->query($statement);
       
        /*
         $this->db->select('users.id as id,users.first_name as nome,users.last_name as sobrenome ')
            ->join('users', 'planos.responsavel = users.id', 'left')
            ->join('setores', 'planos.setor = setores.id', 'left') 
            ->join('superintendencia', 'setores.superintendencia = superintendencia.id', 'left')     
            ->join('atas', 'planos.idatas = atas.id', 'left')
            ->distinct()
         ->order_by('users.first_name', 'ASC');
         $q = $this->db->get_where('planos', array('atas.projetos' => $id,'planos.setor' => $setor));
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
    
    /*
     * PEGA TODOS OS PLANOS DE UM PROJETO e de um setor
     */
     public function getAllitemPlanosProjetoSetorCont($setor)
    {
        $empresa = $this->session->userdata('empresa'); 
        $usuario = $this->session->userdata('user_id');
        $users_dados = $this->site->geUserByID($usuario);
        $projeto_atual = $users_dados->projeto_atual;
        $statement = "SELECT count(*) as quantidade from sig_planos p
                     inner join sig_users_setor us on us.users_id = p.responsavel
                     where us.setores_id = $setor and p.projeto = $projeto_atual and p.empresa = $empresa";
        //echo $statement; exit;
        $q = $this->db->query($statement);
         /*
         $this->db->select('count(idplanos) as quantidade')
            ->join('users', 'planos.responsavel = users.id', 'left')
            ->join('setores', 'planos.setor = setores.id', 'left') 
            ->join('superintendencia', 'setores.superintendencia = superintendencia.id', 'left')     
            ->join('atas', 'planos.idatas = atas.id', 'left')
         ->order_by('idplanos', 'desc');
           $q = $this->db->get_where('planos', array('atas.projetos' => $id,'planos.setor' => $setor), 1);
          * 
          */
     
        if ($q->num_rows() > 0) {
            return $q->row();
        }
       
        return FALSE;
    }
    
    /*
     * PEGA TODAS AS AÇÕES CONCLUÍDAS DE UM PROJETO e de um setor
     */
     public function getAllitemPlanosProjetoSetorContConcluido($setor)
    {
        $empresa = $this->session->userdata('empresa'); 
        $usuario = $this->session->userdata('user_id');
        $users_dados = $this->site->geUserByID($usuario);
        $projeto_atual = $users_dados->projeto_atual;
        
        $statement = "SELECT count(*) as quantidade from sig_planos p
                     inner join sig_users_setor us on us.users_id = p.responsavel
                     where us.setores_id = $setor and p.projeto = $projeto_atual and p.empresa = $empresa and p.status = 'CONCLUÍDO'";
        //echo $statement; exit;
        $q = $this->db->query($statement);
         /*
         $this->db->select('count(idplanos) as quantidade')
            ->join('users', 'planos.responsavel = users.id', 'left')
            ->join('setores', 'planos.setor = setores.id', 'left') 
            ->join('superintendencia', 'setores.superintendencia = superintendencia.id', 'left')     
            ->join('atas', 'planos.idatas = atas.id', 'left')
         ->order_by('idplanos', 'desc');
           $q = $this->db->get_where('planos', array('atas.projetos' => $id,'planos.status' => 'CONCLUÍDO','planos.setor' => $setor), 1);
          * 
          */
     
        if ($q->num_rows() > 0) {
            return $q->row();
        }
       
        return FALSE;
    }
    
    /*
     * PEGA TODAS AS AÇÕES PENDENTES DE UM PROJETO e de um setor
     */
     public function getAllitemPlanosProjetoSetorContPendente($status, $setor)
    {
        $date_hoje = date('Y-m-d');
        $empresa = $this->session->userdata('empresa'); 
        $usuario = $this->session->userdata('user_id');
        $users_dados = $this->site->geUserByID($usuario);
        $projeto_atual = $users_dados->projeto_atual;
        
        $statement = "SELECT count(*) as quantidade from sig_planos p
                     inner join sig_users_setor us on us.users_id = p.responsavel
                     where us.setores_id = $setor and p.projeto = $projeto_atual and p.empresa = $empresa and p.status = 'PENDENTE'";
        
        if ($status == 'PENDENTE') {
            $statement .= " and p.data_termino >= '$date_hoje' ";
        }else
            if ($status == 'ATRASADO') {
            $statement .= " and p.data_termino < '$date_hoje' ";    
        }
        //echo $statement; exit;
        $q = $this->db->query($statement); 
       /*
         $this->db->select('count(idplanos) as quantidade')
            ->join('users', 'planos.responsavel = users.id', 'left')
            ->join('setores', 'planos.setor = setores.id', 'left') 
            ->join('superintendencia', 'setores.superintendencia = superintendencia.id', 'left')     
            ->join('atas', 'planos.idatas = atas.id', 'left')
         ->order_by('idplanos', 'desc');
           //$q = $this->db->get_where('planos', array('atas.projetos' => $id,'planos.status' => 'CONCLUÍDO','users.setor_id' => $setor), 1);
     
            if ($status == 'PENDENTE') {
                $q = $this->db->get_where('planos', array('atas.projetos' => $id, 'planos.status' => 'PENDENTE', 'planos.setor' => $setor, 'planos.data_termino >' => $date_hoje), 1);
              }else
                if ($status == 'ATRASADO') {
                $q = $this->db->get_where('planos', array('atas.projetos' => $id, 'planos.status' => 'PENDENTE', 'planos.setor' => $setor, 'planos.data_termino <' => $date_hoje), 1);
            }
        * 
        */
        
           
           
           
        if ($q->num_rows() > 0) {
            return $q->row();
        }
       
        return FALSE;
    }
    
    /*
     * PEGA TODAS AS AÇÕES PENDENTES DE UM PROJETO e de um setor
     */
     public function getAllitemPlanosProjetoSetorContAguardandoValidacao($setor)
    {
        $empresa = $this->session->userdata('empresa'); 
        $usuario = $this->session->userdata('user_id');
        $users_dados = $this->site->geUserByID($usuario);
        $projeto_atual = $users_dados->projeto_atual;
        
        $statement = "SELECT count(*) as quantidade from sig_planos p
                     inner join sig_users_setor us on us.users_id = p.responsavel
                     where us.setores_id = $setor and p.projeto = $projeto_atual and p.empresa = $empresa and p.status = 'AGUARDANDO VALIDAÇÃO'";
       // echo $statement; exit;
        $q = $this->db->query($statement);
        
        /*
         $this->db->select('count(idplanos) as quantidade')
            ->join('users', 'planos.responsavel = users.id', 'left')
            ->join('setores', 'planos.setor = setores.id', 'left') 
            ->join('superintendencia', 'setores.superintendencia = superintendencia.id', 'left')     
            ->join('atas', 'planos.idatas = atas.id', 'left')
         ->order_by('idplanos', 'desc');
           
         
         $q = $this->db->get_where('planos', array('atas.projetos' => $id,'planos.status' => 'AGUARDANDO VALIDAÇÃO','planos.setor' => $setor), 1);
         * 
         */
     
        if ($q->num_rows() > 0) {
            return $q->row();
        }
       
        return FALSE;
    }
    
    
    /*
     * HISTÓRICO DE AÇÕES
     */
       public function add_Historico_Acoes($historico_acoes)
    {
          
            if ($this->db->insert('historico_acoes', $historico_acoes)) {
                $this->db->insert_id();
                 
                return true;
        }
          
        return false;
    }
    
    
    /*
     * PEGA TODOS OS SETORES 
     usado em Ações -> Resumo por setor
     */
    public function getAllSetor()
    {
        $this->db->select('setores.id as setor_id,setores.nome as setor, superintendencia.nome as superintendencia ')
        ->join('superintendencia', 'setores.superintendencia = superintendencia.id', 'left')
        ->order_by('superintendencia.nome', 'asc');
        $q = $this->db->get('setores');
        
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
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
        
        $empresa = $this->session->userdata('empresa');
        $usuario = $this->session->userdata('user_id');
        $users_dados = $this->site->geUserByID($usuario);
        $projeto_atual = $users_dados->projeto_atual;
        $statement = "SELECT distinct s.id as id, s.nome as nome
                    from sig_planos p
                    inner join sig_users u on u.id = p.responsavel
                    inner join sig_users_setor us on us.users_id = u.id
                    inner join sig_setores s on s.id = us.setores_id
                    where s.pai = $id and p.projeto = $projeto_atual and s.empresa_id = $empresa order by nome asc";
        //echo $statement; exit;
        $q = $this->db->query($statement);
        /*
        $this->db->select('setores.id as setor_id,setores.nome as setor, superintendencia.nome as superintendencia, superintendencia.id as id_area')
        ->join('superintendencia', 'setores.superintendencia = superintendencia.id', 'left')
        //->join('users', 'planos.responsavel = users.id', 'left')
        ->join('planos', 'setores.id = planos.setor', 'left')
        ->join('atas', 'planos.idatas = atas.id', 'left')        
       ->distinct()
        ->order_by('superintendencia.nome', 'asc');
        
        $q = $this->db->get_where('setores', array('superintendencia.id' => $id_area, 'atas.projetos' => $projeto));
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
    
    
    
     public function getAllSetorAreaUsuario($projeto, $usuario,$id_area)
    {
        $this->db->select('setores.id as setor_id,setores.nome as setor, superintendencia.nome as superintendencia, superintendencia.id as id_area') 
         ->join('setores', 'users_gestor.setor = setores.id', 'left')
         ->join('superintendencia', 'setores.superintendencia = superintendencia.id', 'left')       
                
                
         ->order_by('setores.nome', 'asc');
         $q = $this->db->get_where('users_gestor', array('users_gestor.users' => $usuario, 'users_gestor.projeto' => $projeto,'superintendencia.id' => $id_area));
         
       
       
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
    /*
     * PEGA TODOS OS PLANOS DE UMA ATA
     */
     public function getAllitemPlanos($id)
    {
         $this->db->select('planos.*, users.*, setores.*')
            ->join('users', 'planos.responsavel = users.id', 'left')
             ->join('setores', 'planos.setor = setores.id', 'left')      
             
         ->order_by('idplanos', 'desc');
         $q = $this->db->get_where('planos', array('idatas' => $id));
         
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
    
    /*
     * PEGA TODOS OS PLANOS DE UM PLANO DE AÇÃO
     */
     public function getAllAcaoPlanoAcaoById($id)
    {
          $statement = "SELECT p.idplanos as id, p.descricao as descricao, p.categoria_plano as categoria_plano, c.descricao as categoria, sequencial, p.status as status, u.first_name as responsavel,  s.nome as setor, data_entrega_demanda, data_termino, como, porque,onde,custo, p.anexo as anexo, valor_custo, horas_previstas, peso  FROM sig_planos p
            inner join sig_users u on u.id = p.responsavel
            inner join sig_setores s on s.id = p.setor
            inner join sig_plano_acao_categoria c on c.id = p.categoria_plano
            where p.idplano = $id order by c.ordem asc";
          // echo $statement; exit;
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
    /*
     * PEGA TODOS OS PLANOS DE UMA ATA
     */
     public function getAllitemEventoByID($id)
    {
         
         $this->db->select('item_evento.id as id, item_evento.descricao as item, eventos.nome_evento as evento')
         ->join('eventos', 'item_evento.evento = eventos.id', 'left');      
         $q = $this->db->get_where('item_evento', array('item_evento.id' => $id));
         
         if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
    /*
     * PEGA TODOS OS PLANOS PENDENTE DE UMA ATA CONTÍNUA PELO EVENTO
     */
     public function getAllitemPlanosAtaContinua($evento)
    {
         $this->db->select('planos.*, users.*, setores.*')
            ->join('users', 'planos.responsavel = users.id', 'left')
            ->join('setores', 'planos.setor = setores.id', 'left')     
            ->join('atas', 'planos.idatas = atas.id', 'left')
         ->order_by('idplanos', 'desc');
         $q = $this->db->get_where('planos', array('atas.evento' => $evento,'planos.status' => 'PENDENTE'));
         
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
    // TODAS AS AÇÕES DO USUÁRIOS > NETWORK
     public function getAllAcoesUserById_User($usuario, $projeto, $status)
    {
       $empresa = $this->session->userdata('empresa');
       $dataHoje = date('Y-m-d H:i:s');

       $statement = "SELECT * FROM sig_planos p
                     where responsavel = $usuario and status != 'ABERTO' and empresa = $empresa";
            if($projeto){
                $statement .= " and projeto = '$projeto'";
            }
            
            if($status){
               
                if($status == "PENDENTE"){
                     $statement .= " and status = '$status' and '$dataHoje' <= data_termino ";
                }else if($status == "ATRASADO"){
                     $statement .= " and status = 'PENDENTE' and '$dataHoje' > data_termino ";
                }else{
                     $statement .= " and status = '$status'";
                }
            }
        $statement .= " order by idplanos desc ";
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
    
    
    // RETORNA TODOS OS PROJETOS QUE O USUÁRIO TEM AÇÃO; NETWORK > MINHAS AÇÕES
     public function getAllProjetosUserById_User($usuario)
    {
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
    
    /*
     * PEGA TODOS OS PLANOS NÃO CONCLUÍDO DE UM USUÁRIO
     */
     public function getAllPlanosUser($id)
    {
         $this->db->select('planos.*');
         $this->db->where('status !=', 'CONCLUÍDO')
        
          ->order_by('idplanos', 'desc');
         $q = $this->db->get_where('planos', array('responsavel' => $id,'status !=' => 'ABERTO','status !=' => 'CANCELADO'));
         
         
         
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
    /*
     * PEGA TODOS OS PLANOS PENDENTES E QUE ESTÁ FAZENDO
     */
     public function getAllPlanosAndamentoUser($id)
    {
         $this->db->select('planos.*');
        // $this->db->where('status', 'PENDENTE');
         $this->db->limit(10)
        //->limit(10);       
          ->order_by('data_termino', 'asc');
         $q = $this->db->get_where('planos', array('responsavel' => $id,'status' => 'PENDENTE'));
         
         
         
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
    /*
     * PEGA A QUANTIDADE DE PLANOS NÃO CONCLUÍDO DE UM USUÁRIO
     */
     public function getAllPlanosPendenteUser($id)
    {
         $this->db->select('count(idplanos) as quantidade');
          $this->db->where('status !=', 'CONCLUÍDO'); 
         $q = $this->db->get_where('planos', array('responsavel' => $id), 1);
         
         
       if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
     /*
     * PEGA TODOS OS PLANOS CONCLUÍDO DE UM USUÁRIO
     */
     public function getAllPlanosConcluidoUser($id)
    {
         $this->db->select('planos.*');
          $this->db->where('status =', 'CONCLUÍDO')
                  ->order_by('idplanos', 'desc');
         $q = $this->db->get_where('planos', array('responsavel' => $id));
         
         
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    // $this->db->delete('acao_vinculos', array('id_acao' => $id));
    
    /*
     * APAGA O A AÇÃO VÍNCULO DA AÇÃO
     */
     public function deleteVinculo($id)
    {
        
       // $sale_items = $this->resetSaleActions($id);
        
        if ($this->db->delete('acao_vinculos', array('id' => $id))){
           
            return true;
        }
        return FALSE;
    }
    
    /*
     * APAGA O ARQUIVO DA AÇÃO
     */
     public function deleteArquivoAcao($id)
    {
        
       // $sale_items = $this->resetSaleActions($id);
        
        if ($this->db->delete('plano_arquivo', array('id' => $id))){
           
            return true;
        }
        return FALSE;
    }
    
    public function deletePlano($id)
    {
        
       // $sale_items = $this->resetSaleActions($id);
        $this->db->delete('acao_vinculos', array('planos_idplanos' => $id));
        if ($this->db->delete('planos', array('idplanos' => $id))){
           
            return true;
        }
        return FALSE;
    }
    
     public function getAllAcoesProjeto($id, $id_acao)
    {
       $empresa = $this->session->userdata('empresa');
       $statement = "SELECT idplanos, sequencial, idatas, p.data_entrega_demanda as dt_inicio, p.data_termino as dt_termino, p.descricao as descricao, i.descricao as item, nome_evento, nome_fase FROM sig_planos p
                    inner join sig_item_evento i on i.id = p.eventos
                    inner join sig_eventos e on e.id = i.evento
                    inner join sig_fases_projeto f on f.id = e.fase_id
                    left join sig_atas a on a.id = p.idatas
                    where a.projetos = $id and a.empresa = $empresa and p.empresa = $empresa and idplanos not in($id_acao) and idplanos not in(select id_vinculo from sig_acao_vinculos where planos_idplanos = $id_acao) ";
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
    
    
    // todas as ações de um projeto da empresa
     public function getAllAcoesVinculoCadastro($id)
    {
          $empresa = $this->session->userdata('empresa');
       $statement = "SELECT idplanos, sequencial, idatas, p.data_entrega_demanda as dt_inicio, p.data_termino as dt_termino, p.descricao as descricao, i.descricao as item, nome_evento, nome_fase FROM sig_planos p
                    inner join sig_item_evento i on i.id = p.eventos
                    inner join sig_eventos e on e.id = i.evento
                    inner join sig_fases_projeto f on f.id = e.fase_id
                    inner join sig_atas a on a.id = p.idatas
                    where a.projetos = $id and a.empresa = $empresa and p.empresa = $empresa ";
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
    
     public function getAllAcoes($id)
    {
        
        $this->db->select('planos.*')
        
                  ->order_by('idplanos', 'desc');
         if($id){
            $q = $this->db->get_where('planos', array('idplanos' => $id));
         }else{
            $q = $this->db->get_where('planos'); 
         }
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
     public function getAllAcoesVinculadasAta($id)
    {
       //  echo $id; exit;
         
        $q = $this->db->get_where('acao_vinculos', array('planos_idplanos' => $id));
        
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
    /*
     * RETORNA OS ARQUIVOS CADASTRADO PARA ESSA AÇÃO
     */
    public function getAllArquivosByAcao($id)
    {
       //  echo $id; exit;
         
        $q = $this->db->get_where('plano_arquivo', array('plano_id' => $id));
        
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
     public function getAllAcoesVinculadas($id)
    {
         
        $this->db->select('*')
       ->join('planos', 'acao_vinculos.planos_idplanos = planos.idplanos', 'left')
        ->order_by('planos.idplanos', 'desc');
        $q = $this->db->get_where('acao_vinculos', array('planos_idplanos' => $id));
        
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
     public function getAllAcoesPendentes($id)
    {
        
        // $this->db->select('planos.*, users.*')
         //   ->join('users', 'planos.responsavel = users.id', 'left');
         $q = $this->db->get_where('planos', array('idplanos' => $id , 'status' => 'PENDENTE' , 'status' => 'AGUARDANDO VALIDAÇÃO'));
         
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
     public function getAllAtas()
    {
         $usuario = $this->session->userdata('user_id');
         $projetos_usuario = $this->site->getProjetoAtualByID_completo($usuario);
           
         $this->db->select('atas.id as id, atas.id as ata, projeto, atas.status as status,  data_ata, pauta, participantes,  tipo, responsavel_elaboracao, assinaturas, pendencias, atas.anexo')
         ->join('projetos', 'atas.projetos = projetos.id', 'left')
         ->order_by('id', 'desc');
         $q = $this->db->get_where('atas', array('atas.projetos' => $projetos_usuario->projeto_atual));
          
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
     public function getAllAtasByIdProjeto($idprojeto)
    {
         $q = $this->db->get_where('atas', array('projetos' => $idprojeto, 'status' => 1));
          
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
     public function getAllAtasResumido()
    {
         $usuario = $this->session->userdata('user_id');
         $projetos_usuario = $this->site->getProjetoAtualByID_completo($usuario);
           
         $this->db->select('atas.id as id, atas.id as ata, projeto, atas.status as status,  data_ata, pauta, participantes,  tipo, responsavel_elaboracao, assinaturas, pendencias, atas.anexo')
         ->join('projetos', 'atas.projetos = projetos.id', 'left')
         ->order_by('id', 'desc');
         $q = $this->db->get_where('atas', array('atas.projetos' => $projetos_usuario->projeto_atual));
          
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
    //RETORNA TODOS OS HISTÓRICOS DE UMA AÇÃO
    public function getAllHistoricoAcoes($idplano)
    {
        
         $this->db->select('users.id as user, observacao, idplanos, tipo, data_envio, username, first_name,anexo')
         ->join('users', 'usuario = users.id', 'left')
         ->order_by('sig_planos_historico.id', 'desc');
         $q = $this->db->get_where('planos_historico', array('idplanos' => $idplano));
          
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
    //RETORNA TODAS AS RATS DE UMA AÇÃO
    public function getAllRatsAcoes($idplano)
    {
        
          $statement = "SELECT * from sig_planos_rat
                    where planoid = $idplano order by id asc";
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
    
     public function getCountAllHistoricoAcoes($idplano)
    {
        $statement = "SELECT count(*) as total from sig_planos_historico
                    where idplanos = $idplano ";
       //echo $statement; exit;
        $q = $this->db->query($statement);
      
         if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
     public function getPlanoByID($id)
    {
         $empresa = $this->session->userdata('empresa');
        $q = $this->db->get_where('planos', array('idplanos' => $id, 'empresa' => $empresa), 1);
       
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
         
    }
    
    // USADO QUANDO ENVIA UMA NOTIFICAÇÃO DA AÇÃO
    public function getResponsavelFasePlanoByID($id_acao)
    {
        $empresa = $this->session->userdata('empresa');
       $statement = "SELECT idplanos, responsavel_aprovacao
                    FROM sig_planos p
                    inner join sig_item_evento i on i.id = p.eventos
                    inner join sig_eventos e on e.id = i.evento
                    inner join sig_fases_projeto f on f.id = e.fase_id
                    where p.empresa = $empresa and idplanos = $id_acao ";
      // echo $statement; exit;
        $q = $this->db->query($statement);
       
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
         
    }
    
    /*
     * RETORNAO ULTIMO NUMERO SEQUENCIAL POR EMPRESA
     */
     public function getSequencialPlanosEmpresa()
    {
    $empresa = $this->session->userdata('empresa');
        $statement = "SELECT max(sequencial)+1 as sequencial FROM sig_planos where empresa = $empresa";
        //echo $statement; exit;
        $q = $this->db->query($statement);
         
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
    
          /*
     * RETORNAO ULTIMO NUMERO SEQUENCIAL POR EMPRESA
     */
     public function getSequencialAta()
    {
    $empresa = $this->session->userdata('empresa');
        $statement = "SELECT max(sequencia)+1 as sequencial FROM sig_atas where empresa = $empresa";
        //echo $statement; exit;
        $q = $this->db->query($statement);
         
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
    /*
     * PEGA TODOS OS USUÁRIOS DISTINTOS DA ATA
     */
     public function getPlanoByAtaID_distinct($id)
    {
         
        $this->db->select("distinct(responsavel) as responsavel");
            
         $q = $this->db->get_where('planos', array('idatas' => $id));
         
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
         
    }
    
     public function updatePlano($id, $data  = array(),$data_vinculo= array(), $id_vinculo)
    {  
      // print_r($data_vinculo); exit;
        if ($this->db->update('planos', $data, array('idplanos' => $id))) {
            
            /*
             * ações vinculadas
             */
            if($id_vinculo){
                if ($this->db->insert('acao_vinculos', $data_vinculo)) {
                    $this->db->insert_id();
                }
            }
            
            
          //  $this->db->delete('acao_vinculos', array('id_acao' => $id));
           // ADCIONA TODOS OS MÓDULOS SELECIONADOS
                 // foreach ($data_vinculo as $item_vinculo) {
                      
                        
                       
                // }
            
         return true;
        }
        return false;
    }
    
    
    /*
     * ADICIONAR VINCULO
     */
    
     public function AdicionarVinculoAcao($data_vinculo= array())
    {  
         /*
         * ações vinculadas
         */
           
            if ($this->db->insert('acao_vinculos', $data_vinculo)) {
                $this->db->insert_id();
                 return true;
            }
           
            
            
         
        return false;
    }
    
    
    /*
     * ADICIONAR ARQUIVO A AÇÃO
     */
    
     public function AdicionarArquivoAcao($data_vinculo= array())
    {  
         /*
         * ações com arquivo
         */
           
            if ($this->db->insert('plano_arquivo', $data_vinculo)) {
                $this->db->insert_id();
                 return true;
            }
           
            
            
         
        return false;
    }
    
    public function getPlanoByID_completo($id)
    {
     
         $this->db->select("idatas, idplanos, descricao, data_termino, status, planos.observacao as observacao, users.username as responsavel, users.company as setor, users.award_points as superintendente")
            ->join('users', 'planos.responsavel= users.id', 'left');
         
         $q = $this->db->get_where('planos', array('idplanos' => $id));
    
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
         
    }
    
     public function updateProjetoUsuario($id, $data  = array())
    {  
         //print_r($data); exit;
        if ($this->db->update('users', $data, array('id' => $id))) {
         return true;
        }
        return false;
    }
    
     public function getPlanoByAtaID($id)
    {
        $this->db->select("count(idplanos) as totalplanos");
            
         $q = $this->db->get_where('planos', array('idatas' => $id));
    
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
         
    }
    
     public function getPlanoConcluidoByAtaID($id, $situacao)
    {
        $this->db->select("count(idplanos) as totalConcluidos");
         $this->db->where('status', $situacao);   
         $q = $this->db->get_where('planos', array('idatas' => $id));
    
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
         
    }
    
     public function getAllPlano_aguardandoValidacao($id)
    {
     
         $this->db->select("planos.*, users.*")
            ->join('users', 'planos.responsavel= users.id', 'left');
         
         $q = $this->db->get_where('planos', array('planos.status' => 'AGUARDANDO VALIDAÇÃO'));
    
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
         
    }
    
    public function geUserByID($id)
    {
     
         $q = $this->db->get_where('users', array('id' => $id));
    
        if ($q->num_rows() > 0) {
            
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
            
          //  return $q->row();
            
            
        }
        return FALSE;
         
    }
    
    public function add_historicoPlanoAcao($data_HistoricoPlano)
    {
            //print_r($data_HistoricoPlano); exit;
            
            if ($this->db->insert('planos_historico', $data_HistoricoPlano)) {
                $this->db->insert_id();
                 
                return true;
        }
          
        return false;
    }
    
    
    /*
     * REGISTRA AS NOTIFICAÇÕES
     */
    public function add_email($data_email)
    {
            //print_r($data_HistoricoPlano); exit;
            
            if ($this->db->insert('emails', $data_email)) {
                $this->db->insert_id();
                 
                return true;
        }
          
        return false;
    }
    
    /*
     * REGISTRA OS EMAILS
     */
    public function add_notificacoes($data_notificacoes)
    {
            //print_r($data_HistoricoPlano); exit;
            
            if ($this->db->insert('notifications', $data_notificacoes)) {
                $this->db->insert_id();
                 
                return true;
        }
          
        return false;
    }
    
    /*
     * REGISTRA O LOG DOS USUÁRIOS
     */
      public function add_log($data_log)
    {
            
            if ($this->db->insert('sma_logs', $data_log)) {
                $this->db->insert_id();
                 
                return true;
        }
          
        return false;
    }
    
       /*
     * PEGA TODOS OS PLANOS NÃO CONCLUÍDO DE UM USUÁRIO
     */
     public function getAllMacroProcesso()
    {
        //
        $this->db->select('macroprocessos_item.id as id, item ')
        ->join('macroprocessos_item', 'macroprocessos.id= macroprocessos_item.macroprocesso', 'left');
        $q = $this->db->get('macroprocessos');
        //  $this->db->where('status !=', 'CONCLUÍDO'); 
        //  $q = $this->db->get_where('planos', array('responsavel' => $id));
         
          
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
    
    public function getUserSetorByUserID($id)
    {
        $q = $this->db->get_where('users_setor', array('users_id' => $id), 1);
     
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
         
    }
    
    public function getSetorByID($id)
    {
        $q = $this->db->get_where('setores', array('id' => $id), 1);
     
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
         
    }
    
    
    /*
     * PESQUISA DE SATISFAÇÃO
     */
    
    /*
     * RETORNA TODOS OS CADASTROS DE PESQUISA
     */
    
    public function getAllPesquisa($id)
    {
         $q = $this->db->get('pesquisa_satisfacao');
        if ($q->num_rows() > 0) {
            
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
            
          //  return $q->row();
            
            
        }
        return FALSE;
         
    }
    
    
        public function addPesquisaSatisfacao($data)
    {
           // echo 'aqui'; exit;
            
            if ($this->db->insert('pesquisa_satisfacao', $data)) {
                 $id_ata = $this->db->insert_id();
                
            
                 
               return $id_ata;
        }
    }
    
    /*
     * PEGA UMA PESQUISA DE SATISFAÇÃO PELO ID
     */
     public function getPesquisaByID($id)
    {
         
        $q = $this->db->get_where('pesquisa_satisfacao', array('id' => $id), 1);
     
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
         
    }
    
    /*
     * GRUPO DE PERGUNTAS DE UMA PESQUISA ESPECÍFICA
     */
    
    public function getGrupoByIDPesquisa($id)
    {
     
         $q = $this->db->get_where('grupo_perguntas', array('pesquisa_satisfacao' => $id));
    
        if ($q->num_rows() > 0) {
            
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
            
          //  return $q->row();
            
            
        }
        return FALSE;
         
    }
    
    /*
     * PESQUISA DE SATISFAÇÃO
     */
    
        public function addPerguntaPesquisaSatisfacao($data)
    {
           // echo 'aqui'; exit;
            
            if ($this->db->insert('perguntas', $data)) {
                 $id_ata = $this->db->insert_id();
                
            
                 
               return $id_ata;
        }
    }
    
        /*
     * PEGA TODOS AS PERGUNTAS E GRUPO DE UMA PESQUISA
     */
     public function getAllPerguntas($id)
    {
        //
        $this->db->select('perguntas.id as id, perguntas.pergunta as pergunta, grupo_perguntas.nome as grupo ')
        ->join('grupo_perguntas', 'perguntas.grupo_pergunta = grupo_perguntas.id', 'left');
        $q = $this->db->get_where('perguntas', array('grupo_perguntas.pesquisa_satisfacao' => $id));
        //  $this->db->where('status !=', 'CONCLUÍDO'); 
        //  $q = $this->db->get_where('planos', array('responsavel' => $id));
         
          
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
    /*
     * PEGA UMA PERGUNTA DE SATISFAÇÃO PELO ID
     */
     public function getPerguntaByID($id)
    {
        $q = $this->db->get_where('perguntas', array('id' => $id), 1);
     
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
         
    }
    
    public function updatePergunta($id, $data  = array())
    {  
        
        if ($this->db->update('perguntas', $data, array('id' => $id))) {
            
         return true;
        }
        return false;
    }
    
    /*
     * APAGA A PERGUNTA
     */
    public function deletePergunta($id)
    {
       // $sale_items = $this->resetSaleActions($id);
        if ($this->db->delete('perguntas', array('id' => $id))){
           
            return true;
        }
        return FALSE;
    }
    
    
       /*
     * PEGA TODOS AS PERGUNTAS DE UM GRUPO DE PERGUNTA
     */
     public function getAllPerguntasByGrupo($id)
    {
        //
        $this->db->select('perguntas.id as id, perguntas.pergunta as pergunta, grupo_perguntas.nome as grupo ')
        ->join('grupo_perguntas', 'perguntas.grupo_pergunta = grupo_perguntas.id', 'left');
        $q = $this->db->get_where('perguntas', array('perguntas.grupo_pergunta' => $id));
        //  $this->db->where('status !=', 'CONCLUÍDO'); 
        //  $q = $this->db->get_where('planos', array('responsavel' => $id));
         
          
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
    
     /*
     * PEGA TODOS AS PERGUNTAS DE UM GRUPO DE PERGUNTA
     */
     public function getAllRespostaByPergunta($id)
    {
        $q = $this->db->get_where('respostas_perguntas', array('respostas_perguntas.pergunta' => $id));
        //  $this->db->where('status !=', 'CONCLUÍDO'); 
        //  $q = $this->db->get_where('planos', array('responsavel' => $id));
         
          
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
    /*
     ********************************* PARTICIPANTES *********************************
     */
    
        public function addParticipantes($data)
    {
           // print_r($data); exit;
            
            if ($this->db->insert('participantes', $data)) {
             //  $this->db->insert_id();
                 
               return true;
        }
          
        return false;
    }
    
     public function getParticipantesByID($id)
    {
        // echo 'akii'.$id; exit;
        $q = $this->db->get_where('participantes', array('id' => $id), 1);
     
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
         
    }
    
    public function getAllParticipantesByProjeto($id)
    {
     
         $this->db->select('*')
        ->order_by('nome', 'asc');
         $q = $this->db->get_where('participantes', array('projeto' => $id));
    
        if ($q->num_rows() > 0) {
            
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
            
          //  return $q->row();
            
            
        }
        return FALSE;
         
    }
    
    /*
     * LISTA TODAS PESSOAS CADASTRADAS COMO PARTICIPANTES DA ATA 
     */
    public function getAllUserListaParticipantesByProjeto($id)
    {
     
         $this->db->select('users.id as id_user, users.first_name as fname, users.last_name as lname, setores.nome as setor')
         ->join('users', 'users_listas_participantes.users = users.id', 'inner')
         ->join('setores', 'users.setor_id = setores.id', 'inner')        
        ->order_by('users.first_name', 'asc');
         $q = $this->db->get_where('users_listas_participantes', array('projeto' => $id, 'participante_atas'  => 1));
        
        
        if ($q->num_rows() > 0) {
            
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
            
          //  return $q->row();
            
            
        }
        return FALSE;
         
    }
    
    /*
     * LISTA DE USUÁRIO PARA VINCULAR A ATA
     */
    public function getAllUserListaVinculoAtaByProjeto($id)
    {
  
    
         $this->db->select('users.id as id_user, users.first_name as fname, users.last_name as lname, setores.nome as setor')
         ->join('users', 'users_listas_participantes.users = users.id', 'inner')
         ->join('setores', 'users.setor_id = setores.id', 'inner')        
        ->order_by('users.first_name', 'asc');
         $q = $this->db->get_where('users_listas_participantes', array('projeto' => $id, 'usuario_ata'  => 1));
       
         
        if ($q->num_rows() > 0) {
            
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
            
          //  return $q->row();
            
            
        }
        return FALSE;
         
    }
    
    /*
     * LISTA DE CONVOCADOS PARA UMA REUNIÃO
     */
     public function listaConvocados($id)
    {
         $this->db->select('*')
        ->join('users', 'historico_convocacoes.usuario = users.id', 'inner')       
        ->order_by('users.first_name', 'asc')
        ->order_by('historico_convocacoes.id', 'desc') ;
         $q = $this->db->get_where('historico_convocacoes', array('ata' => $id));
         
        //  echo '--------'.$q->num_rows();exit
        if ($q->num_rows() > 0) {
            
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
            
          //  return $q->row();
            
            
        }
        return FALSE;
         
    }
    
    /*
     * LISTA DE CONVOCADOS PARA UMA REUNIÃO
     */
     public function getConvocadoByUsuarioEAta($ata, $usuario)
    {
     
         $q = $this->db->get_where('historico_convocacoes', array('ata' => $ata, 'usuario' => $usuario));
         
        //  echo '--------'.$q->num_rows();exit
        if ($q->num_rows() > 0) {
         
            
           return $q->row();
            
            
        }
        return FALSE;
         
    }
    
    /*
     * LISTA DE CONVOCADOS PARA UMA REUNIÃO
     */
     public function getConvocadoByUsuarioAta($id_historico)
    {
     
         $q = $this->db->get_where('historico_convocacoes', array('id' => $id_historico));
         
        //  echo '--------'.$q->num_rows();exit
        if ($q->num_rows() > 0) {
         
            
           return $q->row();
            
            
        }
        return FALSE;
         
    }
    
    /*
     * LISTA DE CONVOCADOS PARA UMA REUNIÃO
     */
     public function listaConvocadosByUsuarioAta($usuario, $ata)
    {
        // echo 'to aqui'.$ata; exit;
         $this->db->select('*')
         ->order_by('id', 'desc');        
         $q = $this->db->get_where('historico_convocacoes', array('usuario' => $usuario,'ata' => $ata));
         
       //  echo '--------'.$q->num_rows();exit
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
         
    }
    
    /*
     * TODO PARTICIPANTE QUE ESTÁ VINCULADO A UM USUÁRIO
     */
     public function getAllParticipantesUsuario($id)
    {
     
         $this->db->select('*')
        ->join('users', 'participantes.usuario = users.id', 'inner')         
        ->order_by('nome', 'asc');
         $q = $this->db->get_where('participantes', array('projeto' => $id));
    
        if ($q->num_rows() > 0) {
            
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
            
          //  return $q->row();
            
            
        }
        return FALSE;
         
    }
    
     /*
     * Pega um participante específico
     */
    public function getParticipanteByID($id)
    {
        $q = $this->db->get_where('participantes', array('id' => $id), 1);
     
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
         
    }
    
    public function updateParticipantes($id, $data  = array())
    {  
       
        if ($this->db->update('participantes', $data, array('id' => $id))) {
                        
            
         return true;
        }
        return false;
    }
    
    /*
     * Delete Participantes
     */
    
     public function deleteParticipante($id)
    {
       // $sale_items = $this->resetSaleActions($id);
        if ($this->db->delete('participantes', array('id' => $id))){
           
            return true;
        }
        return FALSE;
    }
    
    
    /*
     * REGISTRA QUANDO O USUÁRIO CONFIRMA A PARTICIPAÇÃO DE UMA REUNIÃO OU TREINAMENTO
     */
     public function updateStatusConvocado($id, $data  = array())
    {  
       
        if ($this->db->update('historico_convocacoes', $data, array('id' => $id))) {
                        
            
         return true;
        }
        return false;
    }
    
    /*
     * T R E I N A M E N T O S
     */
    
     public function getTreinamentoByATA($id)
    {
         
        $this->db->select('*');
       // ->join('atas', 'treinamentos.ata = atas.id', 'inner')
      //  ->join('projetos', 'atas.projetos = projetos.id', 'inner');
        $q = $this->db->get_where('treinamentos', array('ata' => $id), 1);
   //  echo $q->num_rows(); exit;
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
         
    }
    
    /*
     * 
     */
    
      public function addUsuarioSetor($data)
    {
           // print_r($data); exit;
            
            if ($this->db->insert('users_setor', $data)) {
                 $id_ata = $this->db->insert_id();
                 
                 
               return $id_ata;
        }
          
        return false;
    }
    
    
    /*
     * USUÁRIO - SETOR
     */
    public function getAllUsersSetores()
    {
        $empresa = $this->session->userdata('empresa');
        $statement = "select us.id as id, u.id as id_user, first_name as nome, s.nome as setor 
                    from sig_users u
                    inner join sig_users_setor us on us.users_id = u.id
                    inner join sig_setores s on s.id = us.setores_id "
                 . " where active = 1 and u.empresa_id = $empresa order by first_name ";    
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
     * USUÁRIO - SETOR BY ID
     */
    public function getAllUsersSetoresById($usuario)
    {
        $empresa = $this->session->userdata('empresa');
        $statement = "select us.id as id, u.id as id_user, first_name as nome, s.nome as setor 
                    from sig_users u
                    inner join sig_users_setor us on us.users_id = u.id
                    inner join sig_setores s on s.id = us.setores_id "
                 . " where active = 1 and u.empresa_id = $empresa and us.id = $usuario order by first_name ";    
       //echo $statement; exit;
        $q = $this->db->query($statement);
        
       if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
     public function getUserSetorByUserSetor($id_user)
    {
        $statement = "select us.id as id, first_name as nome, s.nome as setor from sig_users u
                    inner join sig_users_setor us on us.users_id = u.id
                    inner join sig_setores s on s.id = us.setores_id "
                 . " where us.id = $id_user";    
       //echo $statement; exit;
        $q = $this->db->query($statement);
        
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
    
    
     public function getUserSetorBYid($id)
    {
         $q = $this->db->get_where('users_setor', array('id' => $id), 1);
       
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
    
    public function updatePlanoAtualizacao($id, $data  = array())
    {  
        
        if ($this->db->update('planos', $data, array('responsavel' => $id))) {
           
            
         return true;
        }
        return false;
    }
    
    public function getAllPlanosUserAtualizacao($id)
    {
        
         $q = $this->db->get_where('planos', array('responsavel' => $id));
         
         
         
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
    
     public function getUserSetorByUsuarioAndSetor($usuario, $setor)
    {
         $q = $this->db->get_where('users_setor', array('users_id' => $usuario, 'setores_id' => $setor), 1);
       
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
    
    /*
     * ADICIONA OS FACILITADORES
     */
    public function add_facilitador_ata($data)
    {
           // print_r($data); exit;
            
            if ($this->db->insert('atas_facilitadores', $data)) {
               
                $id_hc = $this->db->insert_id();
                 
               return $id_hc;
        }
          
        return false;
    }
    
    /*
     * PEGA OS FACILITADORES ADD NA ATA
     */
     public function getAtaFacilitadores_ByID_ATA($id)
    {
      // echo $participante; 
        $q = $this->db->get_where('atas_facilitadores', array('ata' => $id));
       // echo 'aquiii'.  $q->num_rows(); exit;
        if ($q->num_rows() > 0) {
            
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
            
          //  return $q->row();
            
            
        }
        return FALSE;
         
    }
    /*
     * DELETA OS FACILITADORES DA ATA
     */
     public function deletaFacilitadores_ByID_ATA($id)
    {
        
       $this->db->delete('atas_facilitadores', array('ata' => $id));
         
    }
    //
    
    
    
    /*
     * VERIFICA SE O USUÁRIO É UM FACILITADOR DE UM TREINAMENTO. SE FOR EXIBE.
     */
     public function getAtaUserFacilitadorByUser( $user)
    {
        $q = $this->db->get_where('atas_facilitadores', array('usuario' => $user));
        
        
         if ($q->num_rows() > 0) {
           return $q->row();
         }
        return FALSE;
         
    }
    
    
    /*
     * RETORNA TODOS OS MEUS TREINAMENTOS SE O USUÁRIO É UM FACILITADOR DE UM TREINAMENTO. SE FOR EXIBE.
     */
     public function getTreinamentoFacilitadorByUser( $user)
    {
        $q = $this->db->get_where('atas_facilitadores', array('usuario' => $user));
        
        
         if ($q->num_rows() > 0) {
            
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
            
          //  return $q->row();
            
            
        }
        return FALSE;
         
    }
    
    
        /*
     * PEGO O FACILITADOR PELO ID.
     */
     public function getFacilitadorByID( $user)
    {
        $q = $this->db->get_where('atas_facilitadores', array('id' => $user));
        
        
         if ($q->num_rows() > 0) {
           return $q->row();
         }
        return FALSE;
         
    }
    
    
    
    /*
     * TREINAMENTOS / ITENS TREINAMENTOS
     */
    
          public function add_item_treinamento($dados_treinamento)
    {
             
            if ($this->db->insert('treinamentos', $dados_treinamento)) {
                $this->db->insert_id();
                 
                return true;
        }
          
        return false;
    }
    
    
    /*
     * RETORNA TODOS OS MEUS TREINAMENTOS SE O USUÁRIO É UM FACILITADOR DE UM TREINAMENTO. SE FOR EXIBE.
     */
     public function getTreinamentoFacilitadorByATA($user, $ata)
    {
        
        $q = $this->db->get_where('treinamentos', array('usuario' => $user, 'ata' => $ata));
        
       
         if ($q->num_rows() > 0) {
            
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
            
          //  return $q->row();
            
            
        }
        return FALSE;
         
    }
    
    /*
     * RETORNA TODOS OS MEUS TREINAMENTOS DE UM TREINAMENTO(ATA). 
     */
     public function getTreinamentosByATA($ata)
    {
        
        $q = $this->db->get_where('treinamentos', array('ata' => $ata));
        
       
         if ($q->num_rows() > 0) {
            
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
            
          //  return $q->row();
            
            
        }
        return FALSE;
         
    }
    
    
    /*
     * PEGA AS ATAS COM CONVOCAÇÃO QUE NÃO SAO AVULSAS
     */
     public function getAtasComConvocacao()
    {
        $q = $this->db->get_where('atas', array('convocacao' => 'SIM', 'status' => 0, 'avulsa' => 'NÃO'));
         
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
    /*
     * LISTA DE CONVOCADOS PARA UMA REUNIÃO E AINDA NÃO CONFIRMARAM
     */
     public function listaConvocadosNaoConfirmados($id)
    {
         $this->db->select('historico_convocacoes.id as id_hc, users.id as user_id')
        ->join('users', 'historico_convocacoes.usuario = users.id', 'inner')       
        ->order_by('users.first_name', 'asc')
        ->order_by('historico_convocacoes.id', 'desc') ;
         $q = $this->db->get_where('historico_convocacoes', array('ata' => $id, 'historico_convocacoes.status' => 0));
         
        //  echo '--------'.$q->num_rows();exit
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
     */
       public function updateInformacoesParticipantesTreinamento($id, $data  = array())
    {  
        
        if ($this->db->update('ata_usuario', $data, array('id' => $id))) {
           
            
         return true;
        }
        return false;
    }
    
       public function updateInformacoesFacilitadorTreinamento($usuario, $ata, $data  = array())
    {  
        
        if ($this->db->update('atas_facilitadores', $data, array('usuario' => $usuario, 'ata' => $ata))) {
           
            
         return true;
        }
        return false;
    }
    
    
      public function updateInformacoesFacilitadorTreinamentoByID($id, $data  = array())
    {  
        
        if ($this->db->update('atas_facilitadores', $data, array('id' => $id))) {
           
            
         return true;
        }
        return false;
    }
    
    public function getAtaFacilitadorByUserAta($usuario, $ata)
    {
        $q = $this->db->get_where('atas_facilitadores', array('ata' => $ata, 'usuario' => $usuario));
        if ($q->num_rows() > 0) {
         
            return $q->row();
        }
        return FALSE;
         
    }
    
    
    /*
     * LISTA DOS PARTICIPANTES DE UMA ATA
     */
    
     public function participante_treinamento_ataByid($id)
    {
        // echo $participante; 
        $q = $this->db->get_where('ata_usuario', array('id' => $id));
       // echo 'aquiii'.  $q->num_rows(); exit;
        if ($q->num_rows() > 0) {
            
            return $q->row();
            
            
        }
        return FALSE;
         
    }
    
     /*
     * SALVA A RESPOSTA DAS AVALIAÇÃO DOS PARTICIPANTES DOS TREINAMENTOS
     */
    
          public function add_resposta_usuario($dados_treinamento)
    {
             
            if ($this->db->insert('resposta_usuario', $dados_treinamento)) {
                $this->db->insert_id();
                 
                return true;
        }
          
        return false;
    }
    /*
     *  SALVA AS SUGESTOS E DIZ QUA ELE JÁ PREENCHEU A AVALIAÇÃO
     */
       public function updateInformacoesParticipante($participante,$data  = array())
    {  
        
        if ($this->db->update('ata_usuario', $data, array('id' => $participante))) {
           
            
         return true;
        }
        return false;
    }
    
    
     /*
     * PEGA TODOS AS RESPOSTAS DE AVALIAÇÃO DO PARTICIPANTE DE TREINAMENTO
     */
     public function getAllRespostasUsuariosByParticipanteAndPergunta($participante, $resposta)
    {
        $this->db->select('count(*) as quantidade');   
        $q = $this->db->get_where('resposta_usuario', array('resposta' => $resposta, 'participante' => $participante));
        //  $this->db->where('status !=', 'CONCLUÍDO'); 
        //  $q = $this->db->get_where('planos', array('responsavel' => $id));
         
          
        if ($q->num_rows() > 0) {
           
            return $q->row();
        }
        return FALSE;
    }
    
    
     public function getAtaUserParticipantePresente_ByID_ATA($id)
    {
      // echo $participante; 
        $q = $this->db->get_where('ata_usuario', array('id_ata' => $id,  'avaliacao' => 1));
       // echo 'aquiii'.  $q->num_rows(); exit;
        if ($q->num_rows() > 0) {
            
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
            
          //  return $q->row();
            
            
        }
        return FALSE;
         
    }
    
    
        
    /*
     * CADASTRO DE EQUIPES
     */
    
        public function addEquipeProjeto($data)
    {
           // echo 'aqui'; exit;
            
            if ($this->db->insert('equipes', $data)) {
                 $id_ata = $this->db->insert_id();
                
            
                 
               return $id_ata;
        }
    }
    
    /*
     * PEGA UMA EQUIPE PELO ID
     */
     public function getEquipeByID($id)
    {
         
        $q = $this->db->get_where('equipes', array('id' => $id), 1);
     
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
         
    }
    
    
        /*
     * PEGA TODOS OS PAPEIS E FUNÇÕES 
     */
    public function getAllPapeisResponsabilidades()
    {
        $this->db->select('*')
         ->order_by('papel', 'asc');
        $q = $this->db->get('papeis_responsabilidades');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
    
        /*
     * CADASTRO DE EQUIPES
     */
    
        public function addMebroEquipeProjeto($data)
    {
           // echo 'aqui'; exit;
            
            if ($this->db->insert('membros_equipe', $data)) {
                 $id_ata = $this->db->insert_id();
                
            
                 
               return $id_ata;
        }
    }
    
    
      public function getAllEquipesMembrosDistinct($id) {
        
          $this->db->select("users_setores.usuario as user_id")
          ->join('membros_equipe', 'equipes.id = membros_equipe.equipe', 'inner')
          ->join('users_setores', 'membros_equipe.usuario = users_setores.id', 'inner')        
          //->group_by('projetos.id')     
          ->distinct();
         
        $q = $this->db->get_where('equipes', array('equipes.projeto' => $id));  
       
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
     public function getMembroEquipeDistinct($id, $usuario) {
        
          $this->db->select("users_setores.usuario as user_id")
          ->join('membros_equipe', 'equipes.id = membros_equipe.equipe', 'inner')
          ->join('users_setores', 'membros_equipe.usuario = users_setores.id', 'inner')        
          //->group_by('projetos.id')     
          ->distinct();
         
        $q = $this->db->get_where('equipes', array('equipes.projeto' => $id, 'users_setores.usuario' => $usuario));  
       
        if ($q->num_rows() > 0) {
            
            return $q->num_rows();
        }
        return FALSE;
    }
    
      /*
     * PEGA TODAS AS EQUIPE DE UM PROJETO
     */
     public function getEquipeByProjeto($id)
    {
         
        $q = $this->db->get_where('equipes', array('projeto' => $id));
     
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
         
    }
    
    
    
     /*
     * PEGA TODOS OS MEMBROS DE UMA EQUIPE
     */
     public function getMebrosEquipeByEquipe($id)
    {
         $this->db->select('membros_equipe.id as id, users_setores.id as id_usuario_setor, users.id as id_usuario, users.first_name as name, users.last_name as last, setores.nome as setor, papeis_responsabilidades.papel as papel, papeis_responsabilidades.descricao as descricao')
        ->join('users_setores', 'membros_equipe.usuario = users_setores.id', 'inner')           
        ->join('users', 'users_setores.usuario = users.id', 'inner')       
        ->join('setores', 'users_setores.setor = setores.id', 'inner')
        ->join('papeis_responsabilidades', 'membros_equipe.papel = papeis_responsabilidades.id', 'inner')         
        ->order_by('users.first_name', 'asc');
        $q = $this->db->get_where('membros_equipe', array('equipe' => $id));
     
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
         
    }
    
     /*
     * PEGA TODOS OS MEMBROS DE UMA EQUIPE
     */
     public function getMebrosEquipeByidMembroEquipe($id)
    {
         $this->db->select('membros_equipe.id as id, users_setores.id as id_usuario_setor, users.id as id_usuario, users.first_name as name, users.last_name as last, setores.nome as setor, papeis_responsabilidades.papel as papel, papeis_responsabilidades.descricao as descricao')
        ->join('users_setores', 'membros_equipe.usuario = users_setores.id', 'inner')           
        ->join('users', 'users_setores.usuario = users.id', 'inner')       
        ->join('setores', 'users_setores.setor = setores.id', 'inner')
        ->join('papeis_responsabilidades', 'membros_equipe.papel = papeis_responsabilidades.id', 'inner')         
        ->order_by('users.first_name', 'asc');
        $q = $this->db->get_where('membros_equipe', array('membros_equipe.id ' => $id), 1);
     
        if ($q->num_rows() > 0) {
           
            return $q->row();
        }
        return FALSE;
         
    }
    
    /*
     * APAGA O MEMBRO DE UMA EQUIPE
     */
    public function deleteMebroEquipe($id)
    {
       // $sale_items = $this->resetSaleActions($id);
        if ($this->db->delete('membros_equipe', array('id' => $id))){
           
            return true;
        }
        return FALSE;
    }
    
     public function updateEquipe($id, $data  = array())
    {  
        
        if ($this->db->update('equipes', $data, array('id' => $id))) {
            
         return true;
        }
        return false;
    }
    
    
    
     /*
     * PEGA TODOS OS PROJETOS QUE EU SOU MEMBRO DE UMA EQUIPE
     */
     public function getMebrosEquipeByUsuario($id)
    {
         $this->db->select('membros_equipe.id as id, users_setores.id as id_usuario_setor, users.id as id_usuario, users.first_name as name, users.last_name as last, setores.nome as setor, papeis_responsabilidades.papel as papel, papeis_responsabilidades.descricao as descricao, equipes.nome as equipe, projetos.projeto as projeto')
        ->join('users_setores', 'membros_equipe.usuario = users_setores.id', 'inner')           
        ->join('users', 'users_setores.usuario = users.id', 'inner')       
        ->join('setores', 'users_setores.setor = setores.id', 'inner')
        ->join('equipes', 'membros_equipe.equipe = equipes.id', 'inner')      
        ->join('projetos', 'equipes.projeto = projetos.id', 'inner')           
        ->join('papeis_responsabilidades', 'membros_equipe.papel = papeis_responsabilidades.id', 'inner')         
        ->order_by('users.first_name', 'asc');
        $q = $this->db->get_where('membros_equipe', array('users_setores.usuario' => $id));
     
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
         
    }
    
    
    
     /*
     * PEGA O PROJETOS QUE EU SELECIONEI A EQUIPE
     */
     public function getMebrosEquipeByIdEquipe($id)
    {
         $this->db->select('membros_equipe.id as id, users_setores.id as id_usuario_setor, users.id as id_usuario, users.first_name as name, users.last_name as last, setores.nome as setor, papeis_responsabilidades.papel as papel, papeis_responsabilidades.descricao as descricao, equipes.nome as equipe, projetos.projeto as projeto')
        ->join('users_setores', 'membros_equipe.usuario = users_setores.id', 'inner')           
        ->join('users', 'users_setores.usuario = users.id', 'inner')       
        ->join('setores', 'users_setores.setor = setores.id', 'inner')
        ->join('equipes', 'membros_equipe.equipe = equipes.id', 'inner')      
        ->join('projetos', 'equipes.projeto = projetos.id', 'inner')           
        ->join('papeis_responsabilidades', 'membros_equipe.papel = papeis_responsabilidades.id', 'inner')         
        ->order_by('users.first_name', 'asc');
        $q = $this->db->get_where('membros_equipe', array('membros_equipe.id' => $id));
     
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
         
    }
    
    
    /*
     * PEGA TODAS AS EQUIPES Q EU FAÇO PARTE
     */
     public function getMebrosEquipeByIdUsuario($id)
    {
         $this->db->select('membros_equipe.id as id, users_setores.id as id_usuario_setor')
        ->join('users_setores', 'membros_equipe.usuario = users_setores.id', 'inner');     
          
         $q = $this->db->get_where('membros_equipe', array('users_setores.usuario' => $id));
         
    
          if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
         
    }
    
    
    /*
     * PEGA A FASE E EVENTO DE UM PROJETO
     */
     public function getMebrosEquipeByIdMembro($id)
    {
         $this->db->select('membros_equipe.id as id, equipes.nome as equipe, equipes.projeto as projeto')
         ->join('equipes', 'membros_equipe.equipe = equipes.id', 'inner');      
         $q = $this->db->get_where('membros_equipe', array('membros_equipe.id' => $id), 1);
     
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
         
    }
    
    
     /*
     * PEGA A FUNÇÃO E MÓDULO DO PROJETO
     */
     public function getModulosFuncaoByProjeto($id)
    {
         $this->db->select('modulos_funcao.id as id, modulos.descricao as modulo, modulos_funcao.funcao as funcao')
         ->join('modulos_funcao', 'modulos.id = modulos_funcao.modulo', 'inner')
         ->order_by('modulos_funcao.id', 'asc');        
         $q = $this->db->get_where('modulos', array('modulos.projeto' => $id));
     
         if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
         
    }
    
    
    
     /*
     *  add rats
     */
    
          public function add_rat($dados_rat, $funcoes, $itens)
    {
             
            if ($this->db->insert('rats', $dados_rat)) {
                //
               $id_rat = $this->db->insert_id();
               
              
                 foreach ($itens as $item) {
                    
                        $data_ata_usuario = array(
                            'rat' => $id_rat,
                            'item' => $item);      
                        
                        $this->db->insert('rats_item', $data_ata_usuario);
                 }
                 
                  foreach ($funcoes as $funcao) {
                        $data_rat_funcao = array(
                            'rat' => $id_rat,
                            'funcao' => $funcao);      
                        
                        $this->db->insert('rats_funcao', $data_rat_funcao);
                 }
               
                 
                return true;
        }
          
        return false;
    }
    
    
    /*
     * PEGA AS RATS DE UM MEMBRO DE UMA EQUIPE
     */
     public function getRaByAcao($id)
    {
        // echo 'aqui'. $data_inicio; exit;
         $this->db->select('*')
         ->order_by('id', 'asc');
         $q = $this->db->get_where('planos_rat', array('planoid' => $id));    
        
          if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
         
    }
    
    /*
     * PEGA OS LOG DE UMA AÇÃO
     */
     public function getLogByAcao($id)
    {
        // echo 'aqui'. $data_inicio; exit;
         $this->db->select('*')
         ->order_by('id', 'asc');
         $q = $this->db->get_where('plano_log', array('idplano' => $id));    
        
          if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
         
    }
    /*
     * PEGA AS RATS DE UM MEMBRO DE UMA EQUIPE
     */
     public function getRatMebrosEquipeByIdMembro($id, $data_inicio, $data_fim)
    {
        // echo 'aqui'. $data_inicio; exit;
         $this->db->select('*')
         ->order_by('rats.data_registro', 'asc');
        // ->join('equipes', 'membros_equipe.equipe = equipes.id', 'inner');      
         
         if(($data_inicio)&&($data_fim)){
         $q = $this->db->get_where('rats', array('equipe' => $id, 'data_rat >=' => $data_inicio, 'data_rat <=' => $data_fim));
         }else{
         $q = $this->db->get_where('rats', array('equipe' => $id));    
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
     * PEGA AS RATS DE UM MEMBRO DE UMA EQUIPE
     */
     public function getResumoRatMebrosEquipeByIdMembro($id, $data_inicio, $data_fim)
    {
       //  echo $id; exit;
        // echo 'aqui'. $data_inicio; exit;
         $this->db->select('SUM(hora_fim - hora_inicio) as resumo');
       //  ->order_by('rats.data_registro', 'asc');
        // ->join('equipes', 'membros_equipe.equipe = equipes.id', 'inner');      
         $q = $this->db->get_where('rats', array('equipe' => $id, 'data_rat >=' => $data_inicio, 'data_rat <=' => $data_fim), 1);
        
         if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
         
    }
    
     /*
     * PEGA OS EVENTOS/ITENS DE UMA RAT
     */
     public function getEventoItemByRat($id)
    {
         $this->db->select('rats_item.id as id, item_evento.descricao as item, eventos.nome_evento as evento')
         ->join('item_evento', 'rats_item.item = item_evento.id', 'inner')
         ->join('eventos', 'item_evento.evento = eventos.id', 'inner')        
         ->order_by('id', 'asc');
         
         
         $q = $this->db->get_where('rats_item', array('rat' => $id));

          if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
         
    }
    
    
    /*
     * PEGA OS MODULOS/FUNCAO DE UMA RAT
     */
     public function getModulosFuncaoByRat($id)
    {
         $this->db->select('rats_funcao.id as id, modulos_funcao.funcao as funcao, modulos.descricao as modulos')
         ->join('modulos_funcao', 'rats_funcao.funcao = modulos_funcao.id', 'inner')
         ->join('modulos', 'modulos_funcao.modulo = modulos.id', 'inner')        
         ->order_by('id', 'asc');
         
         
         $q = $this->db->get_where('rats_funcao', array('rat' => $id));

          if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
         
    }
    
     /*
     * APAGA RAT
     */
    public function deleteRat($id)
    {
       // $sale_items = $this->resetSaleActions($id);
        if ($id){
           
            $this->db->delete('rats_item', array('rat' => $id));
            $this->db->delete('rats_funcao', array('rat' => $id));
            $this->db->delete('rats', array('id' => $id));
            return true;
        }
        return FALSE;
    }
    
    public function getRatById($id)
    {
          $q = $this->db->get_where('rats', array('id' => $id), 1);
     
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
         
    }
    
    public function getModulosRatById($id)
    {
          $q = $this->db->get_where('rats_funcao', array('rat' => $id));
     
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
         
    }
    
    public function getEventosRatById($id)
    {
          $q = $this->db->get_where('rats_item', array('rat' => $id));
     
         if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
         
    }
    
    
     public function updateRat($rat, $data  = array(),  $funcoes, $itens)
    {  
     
        if ($this->db->update('rats', $data, array('id' => $rat))) {
            
          
                $this->db->delete('rats_funcao', array('rat' => $rat));
                $this->db->delete('rats_item', array('rat' => $rat));
            
               
                   foreach ($itens as $item) {
                    
                        $data_ata_usuario = array(
                            'rat' => $rat,
                            'item' => $item);      
                       
                        $this->db->insert('rats_item', $data_ata_usuario);
                 }
                 
                  foreach ($funcoes as $funcao) {
                        $data_rat_funcao = array(
                            'rat' => $rat,
                            'funcao' => $funcao);      
                        
                        $this->db->insert('rats_funcao', $data_rat_funcao);
                 }
           
            
         return true;
        }
        return false;
    }
    
    
      public function getDadosContratos($id)
    {
     
         $this->db->select("projetos.id as projeto_id, edp_id as edp")
            ->join('projetos', 'atas.projetos = projetos.id', 'left');
         
         $q = $this->db->get_where('atas', array('atas.id' => $id));
         
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
         
    }
    
      public function getPeriodoHEByID($id)
    {
        $q = $this->db->get_where('periodo_he', array('id' => $id), 1);
     
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
         
    }
    
      public function getUserTIByUser($id)
    {
        $q = $this->db->get_where('user_horario', array('user' => $id), 1);
     
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
         
    }
    
    /*
     * PESOS DE AÇÕES - ATA/ PLANO AÇÃO
     */
    
    
    
     /*
     * TOTAL DE AÇÕES DE UMA ATA
     */
     public function getTotalAcoesByAta($idata)
    {
        $empresa = $this->session->userdata('empresa');
        $statement = "SELECT count(*) as total_acoes FROM sig_planos where idatas = $idata and empresa = $empresa ";
        //echo $statement; exit;
        $q = $this->db->query($statement);
         
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
     /*
     * RETORNAO O TOTAL DO PESO DAS AÇÕES DE UMA ATA
     */
     public function getTotalPesoAcoesByAta($idata)
    {
        $empresa = $this->session->userdata('empresa');
        $statement = "SELECT sum(peso) as total_peso FROM sig_planos where idatas = $idata and empresa = $empresa and status != 'ABERTO'";
        //echo $statement; exit;
        $q = $this->db->query($statement);
         
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
     /*
     * RETORNAO O TOTAL DO PESO DAS AÇÕES ATRASADAS DE UMA ATA
     */
     public function getTotalPesoAcoesAtrasadasByAta($idata)
    {
        $empresa = $this->session->userdata('empresa');
        $statement = "SELECT sum(peso) as atrasado_peso FROM sig_planos where idatas = $idata and empresa = $empresa and data_termino < NOW() and status IN ('PENDENTE','AGUARDANDO VALIDAÇÃO')";
        //echo $statement; exit;
        $q = $this->db->query($statement);
         
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
     /*
     * RETORNAO O TOTAL DO PESO DAS AÇÕES PENDENTE DE UMA ATA
     */
     public function getTotalPesoAcoesPendentesByAta($idata)
    {
        $empresa = $this->session->userdata('empresa');
        $statement = "SELECT sum(peso) as pendente_peso FROM sig_planos where idatas = $idata and empresa = $empresa and data_termino >= NOW() and status IN ('PENDENTE','AGUARDANDO VALIDAÇÃO')";
        //echo $statement; exit;
        $q = $this->db->query($statement);
         
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
    /*
     * RETORNAO O TOTAL DO PESO DAS AÇÕES PENDENTE DE UMA ATA
     */
     public function getTotalPesoAcoesConcluidoByAta($idata)
    {
        $empresa = $this->session->userdata('empresa');
        $statement = "SELECT sum(peso) as conclusao_peso FROM sig_planos where idatas = $idata and empresa = $empresa and status = 'CONCLUÍDO'";
        //echo $statement; exit;
        $q = $this->db->query($statement);
         
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
    /*
     * -------------------------------------- plano de ação ----------------------------------------------
     */
    
    /*
     * TOTAL DE AÇÕES DE UM PLANO DE AÇÃO
     */
     public function getTotalAcoesByPlanoAcao($idata)
    {
        $empresa = $this->session->userdata('empresa');
        $statement = "SELECT count(*) as total_acoes FROM sig_planos where idplano = $idata and empresa = $empresa ";
        //echo $statement; exit;
        $q = $this->db->query($statement);
         
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
     /*
     * RETORNAO O TOTAL DO PESO DAS AÇÕES DE UMA ATA
     */
     public function getTotalPesoAcoesByPlanoAcao($idata)
    {
        $empresa = $this->session->userdata('empresa');
        $statement = "SELECT sum(peso) as total_peso FROM sig_planos where idplano = $idata and empresa = $empresa and status != 'ABERTO'";
        //echo $statement; exit;
        $q = $this->db->query($statement);
         
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
     /*
     * RETORNAO O TOTAL DO PESO DAS AÇÕES ATRASADAS DE UMA ATA
     */
     public function getTotalPesoAcoesAtrasadasByPlanoAcao($idata)
    {
        $empresa = $this->session->userdata('empresa');
        $statement = "SELECT sum(peso) as atrasado_peso FROM sig_planos where idplano = $idata and empresa = $empresa and data_termino < NOW() and status IN ('PENDENTE','AGUARDANDO VALIDAÇÃO')";
        //echo $statement; exit;
        $q = $this->db->query($statement);
         
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
     /*
     * RETORNAO O TOTAL DO PESO DAS AÇÕES PENDENTE DE UMA ATA
     */
     public function getTotalPesoAcoesPendentesByPlanoAcao($idata)
    {
        $empresa = $this->session->userdata('empresa');
        $statement = "SELECT sum(peso) as pendente_peso FROM sig_planos where idplano = $idata and empresa = $empresa and data_termino >= NOW() and status in ('PENDENTE', 'AGUARDANDO VALIDAÇÃO')";
        //echo $statement; exit;
        $q = $this->db->query($statement);
         
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
    /*
     * RETORNAO O TOTAL DO PESO DAS AÇÕES PENDENTE DE UMA ATA
     */
     public function getTotalPesoAcoesConcluidoByPlanoAcao($idata)
    { 
        $empresa = $this->session->userdata('empresa');
        $statement = "SELECT sum(peso) as conclusao_peso FROM sig_planos where idplano = $idata and empresa = $empresa and status = 'CONCLUÍDO'";
        //echo $statement; exit;
        $q = $this->db->query($statement);
         
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
    
    /********************************************************************************************************
     *************************** GRÁFICO DE GANTT ******************************************************** 
     *****************************************************************************************************/
    //FASES PROJETO
     public function getAllFasePlano($id, $tipo)
    {
       $empresa = $this->session->userdata('empresa');
       $statement = "SELECT distinct e.fase_id as fase_id, f.nome_fase, f.data_inicio as inicio, f.data_fim as fim, f.status  FROM sig_planos p
                    inner join sig_item_evento i on i.id = p.eventos
                    inner join sig_eventos e on e.id = i.evento
                    inner join sig_fases_projeto f on f.id = e.fase_id  where  p.empresa = $empresa";
       if($tipo == 1){
           $statement .= " and idatas = $id";
       }else if($tipo == 2){
           $statement .= " and idplano = $id ";
       }
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
    //EVENTOS PROJETO DE UMA FASE - GANTT
    public function getAllEventosPlano($id, $tipo, $fase)
    {
       $empresa = $this->session->userdata('empresa');
       $statement = "SELECT distinct i.evento as evento, e.data_inicio as inicio, e.data_fim as fim, e.nome_evento as nome_evento, e.fase_id  
                    FROM sig_planos p
                    inner join sig_item_evento i on i.id = p.eventos
                    inner join sig_eventos e on e.id = i.evento
                    where  p.empresa = $empresa  and fase_id = $fase";
       if($tipo == 1){
           $statement .= " and idatas = $id";
       }else if($tipo == 2){
           $statement .= " and idplano = $id ";
       }
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
    
    //RETORNA TODOS OS ITENS DE EVENTOS DE UM EVENTO DO PROJETO, PODENDO SER ATA OU P.A.
    public function getAllItensEventosPlano($id, $tipo, $evento)
    {
       $empresa = $this->session->userdata('empresa');
       $statement = "SELECT distinct p.eventos as item_id, i.descricao as item, i.dt_inicio as inicio, i.dt_fim as fim, i.evento as evento  FROM sig_planos p
                    inner join sig_item_evento i on i.id = p.eventos
                    where  p.empresa = $empresa and evento = $evento";
       if($tipo == 1){
           $statement .= " and idatas = $id";
       }else if($tipo == 2){
           $statement .= " and idplano = $id ";
       }
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
    
    //RETORNA TODAS AS AÇÕES DE UM ITEM DE EVENTOS DO PROJETO
    public function getAllPlanosItensEventosPlano($id, $tipo, $item_evento)
    {
       $empresa = $this->session->userdata('empresa');
       $statement = "SELECT *  FROM sig_planos p where  p.empresa = $empresa and eventos = $item_evento ";
       
       if($tipo == 1){
           $statement .= " and idatas = $id";
       }else if($tipo == 2){
           $statement .= " and idplano = $id ";
       }
       //$statement .= " limit 8 ";
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
    
    
     //FASES PROJETO
     public function getAllFaseProjeto()
    {
       $empresa = $this->session->userdata('empresa');
       $usuario = $this->session->userdata('user_id');
       $projetos_usuario = $this->site->getProjetoAtualByID_completo($usuario);
           $statement = "SELECT distinct f.id as fase_id, f.nome_fase, f.data_inicio as inicio, f.data_fim as fim, f.status
                    FROM sig_fases_projeto f
                    where f.id_projeto = $projetos_usuario->projeto_atual";
       
        $q = $this->db->query($statement);
        
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    //EVENTOS PROJETO DE UMA FASE - GANTT
    public function getAllEventosProjeto($fase)
    {
       $empresa = $this->session->userdata('empresa');
       
            $statement = "SELECT id, e.data_inicio as inicio, e.data_fim as fim, e.nome_evento as nome_evento, e.fase_id  
                    FROM sig_eventos e
                    where fase_id = $fase";
     
        $q = $this->db->query($statement);
        
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
    
     //EVENTOS PROJETO DE UMA FASE - GANTT
    public function getAllItensEventosProjeto($fase)
    {
       $empresa = $this->session->userdata('empresa');
       
            $statement = "SELECT id, i.dt_inicio as inicio, i.dt_fim as fim, i.descricao as item  
                    FROM sig_item_evento i
                    where evento = $fase";
     
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
