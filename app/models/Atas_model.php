<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Atas_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    
        public function addAtas($data,$usuario_ata,$participantes)
    {
           // print_r($data); exit;
            
            if ($this->db->insert('atas', $data)) {
                 $id_ata = $this->db->insert_id();
                 
                // print_r($usuario_ata); exit;
                 
                  foreach ($usuario_ata as $item) {
                        $data_ata_usuario = array('id_ata' => $id_ata,
                            'id_usuario' => $item);      
                        
                        $this->db->insert('ata_usuario', $data_ata_usuario);
                 }
                 
                 
                  foreach ($participantes as $item_participante) {
                        $data_participante = array('id_ata' => $id_ata,
                            'id_participante' => $item_participante);      
                        
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
    
     public function getAtaUserByID_ATA($id)
    {
        $q = $this->db->get_where('ata_usuario', array('id_ata' => $id, 'id_usuario !=' => ""));
    
        if ($q->num_rows() > 0) {
            
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
            
          //  return $q->row();
            
            
        }
        return FALSE;
         
    }
    
    
     public function getAtaUserParticipante_ByID_ATA($id)
    {
      // echo $participante; 
       //  $this->db->select("*")
       //  ->order_by('idplanos', 'desc');
        $q = $this->db->get_where('ata_usuario', array('id_ata' => $id, 'id_participante !=' => ""));
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
            
               
                        $data_ata_usuario = array('id_ata' => $id, 'id_participante' => $participante);      
                        $this->db->insert('ata_usuario', $data_ata_usuario);
                  return true;
            }
            
         
            
        
        
        return false;
    }
    
         public function deleteParticipanteAta($participante)
    {  
        
     
            
            if($participante){
                   $this->db->delete('ata_usuario', array('id' => $participante));
            
               
                  //      $data_ata_usuario = array('id_ata' => $id, 'id_participante' => $participante);      
                  //      $this->db->insert('ata_usuario', $data_ata_usuario);
                  return true;
            }
            
         
            
        
        
        return false;
    }
    
     public function updateAta($id, $data  = array(), $participantes_ata= array(), $usuario_ata= array())
    {  
        
        if ($this->db->update('atas', $data, array('id' => $id))) {
            
            if($participantes_ata){
                 // $this->db->delete('ata_usuario', array('id_ata' => $id, 'id_participante !=' => ""));
            
                foreach ($participantes_ata as $item) {
                        $data_ata_usuario = array('id_ata' => $id, 'id_participante' => $item);      
                        $this->db->insert('ata_usuario', $data_ata_usuario);
                 }
            }
            
            if($usuario_ata){
                $this->db->delete('ata_usuario', array('id_ata' => $id, 'id_usuario !=' => ""));
            
                foreach ($usuario_ata as $item) {
                        $data_ata_usuario = array('id_ata' => $id, 'id_usuario' => $item);      
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
     public function updateParticipantesAta($id, $participantes= array())
    {  
        
             
            if($participantes){
                
                foreach ($participantes as $item) {
                    $this->db->delete('ata_usuario', array('id_ata' => $id, 'id_participante !=' => null));
                }
                
                foreach ($participantes as $item) {
                        $data_ata_usuario = array('id_ata' => $id, 'id_participante' => $item);      
                        $this->db->insert('ata_usuario', $data_ata_usuario);
                 }
                 
                 return true;
                 
            }
            
         
        
        return false;
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
     
    
     public function finalizaAta($id, $data  = array())
    {  
        
        if ($this->db->update('atas', $data, array('id' => $id))) {
            
         
            
            
         return true;
        }
        return false;
    }
    
    
     public function atualizaUser($id, $data  = array())
    {  
        
        if ($this->db->update('users', $data, array('id' => $id))) {
            
         
            
            
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
    
    
    public function getAllServicosSai()
    {
        $statement = "SELECT * from sma_sai_servicos order by descricao asc";
        $q = $this->db->query($statement);
        
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
     public function getAllPrestadoresSai()
    {
        $statement = "SELECT * from sma_sai_prestadores p ";
        $q = $this->db->query($statement);
        
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
     public function getAllPrestadoresSaiByEspecialidade($especialidade)
    {
        $statement = "SELECT p.id as id_prestador, p.*, e.* from sma_sai_prestadores p "
                . " INNER JOIN sma_sai_prestadores_especialidades e ON e.codigo = p.codigo where e.especialidade = '$especialidade' ";
       
        $q = $this->db->query($statement);
        
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
     public function getAllEspecialidadesSai()
    {
        $statement = "SELECT DISTINCT especialidade from sma_sai_prestadores_especialidades order by especialidade asc ";
        $q = $this->db->query($statement);
        
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
    
    
    public function getAllTelefonesPrestadoresSai($codigo)
    {
        $statement = "SELECT * from sma_sai_prestadores_telefone where prestador = $codigo ";
        $q = $this->db->query($statement);
        
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
      public function getAllEspecialidadesPrestadoresSai($codigo)
    {
        $statement = "SELECT * from sma_sai_prestadores_especialidades where codigo = $codigo ";
         $q = $this->db->query($statement);
        
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
      public function getAllServicosPrestadoresSai($codigo)
    {
        $statement = "SELECT * from sma_sai_prestadores_servicos where codigo = $codigo ";
         $q = $this->db->query($statement);
        
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
   public function getAllListaEspera($servicos, $especialidades, $status)
    {
        $statement = "SELECT * from sma_sai_lista_espera where beneficiario is not null ";
        
        if($servicos != ""){
            $statement .=" and servico = '$servicos'";
        }
        
        if($especialidades != ""){
            $statement .=" and especialidade = '$especialidades'";
        }
        
        if($status != ""){
            $statement .=" and status = $status";
        }
        
        $statement .=" order by data_criacao desc";
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
    
    
    public function getQtdePrestadoresSai()
    {
        $statement = "SELECT count(*) as qtde_prestadores from sma_sai_prestadores p ";
        $q = $this->db->query($statement);
        
       if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
    public function getQtdeEspecialidadesSai()
    {
        $statement = "SELECT count(distinct(especialidade)) as qtde_especialidade from sma_sai_prestadores_especialidades p ";
        $q = $this->db->query($statement);
        
       if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
    public function getQtdeServicosSai()
    {
        $statement = "SELECT count(*) as qtde_servicos from sma_sai_servicos ";
        $q = $this->db->query($statement);
        
       if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
    
    public function getQtdeListaEsperaSai()
    {
        $statement = "SELECT count(*) as qtde_espera from sma_sai_lista_espera where status = 0";
        $q = $this->db->query($statement);
        
       if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
       public function add_lista_espera($data_lista)
    {
            
            if ($this->db->insert('sai_lista_espera', $data_lista)) {
               $id_acao =  $this->db->insert_id();
                 
           
                 
                return true;
        }
          
        return false;
    }
    
        public function add_prestador($data_lista)
    {
            
            if ($this->db->insert('sai_prestadores', $data_lista)) {
               $id_acao =  $this->db->insert_id();
               return $id_acao;
        }
          
        return false;
    }
    
    public function getRegistroListaByID($id)
    {
          
        $q = $this->db->get_where('sai_lista_espera', array('id' => $id), 1);
     
        
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
         
    }
    
    public function getRegistroPrestadorByID($id)
    {
          
        $q = $this->db->get_where('sai_prestadores', array('id' => $id), 1);
     
        
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
         
    }
    
    public function updateLista($id, $data  = array())
    {  
        
        if ($this->db->update('sai_lista_espera', $data, array('id' => $id))) {
            
         return true;
        }
        return false;
    }
    
    
    public function updatePrestador($id, $data  = array())
    {  
        
        if ($this->db->update('sai_prestadores', $data, array('id' => $id))) {
            
         return true;
        }
        return false;
    }
    
     public function deleteTelefonePrestador($id)
    {
       // $sale_items = $this->resetSaleActions($id);
        if ($this->db->delete('sai_prestadores_telefone', array('id' => $id))){
            return true;
        }
        return FALSE;
    }
    
       public function add_telefone_prestador($data_lista)
    {
            
            if ($this->db->insert('sai_prestadores_telefone', $data_lista)) {
               $id_acao =  $this->db->insert_id();
                 
           
                 
                return true;
        }
          
        return false;
    }
    
    public function getAllEspecialidadesPrestadoresSaiByPrestador($codigo)
    {
        $statement = "SELECT * from sma_sai_prestadores_especialidades where codigo = '$codigo' ";
        
        $q = $this->db->query($statement);
        
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
    public function add_especialidade_prestador($data_lista)
    {
            
            if ($this->db->insert('sai_prestadores_especialidades', $data_lista)) {
               $id_acao =  $this->db->insert_id();
                 
           
                 
                return true;
        }
          
        return false;
    }
    
     public function deleteEspecialidadePrestador($id)
    {
       // $sale_items = $this->resetSaleActions($id);
        if ($this->db->delete('sai_prestadores_especialidades', array('id' => $id))){
            return true;
        }
        return FALSE;
    }
    
    public function add_servicosSai($data_lista)
    {
            
            if ($this->db->insert('sai_servicos', $data_lista)) {
               $id_acao =  $this->db->insert_id();
                 
           
                 
                return true;
        }
          
        return false;
    }
    
    public function getAllServicosPrestadoresSaiByPrestador($codigo)
    {
        $statement = "SELECT * from sma_sai_prestadores_servicos where codigo = '$codigo' ";
        
        $q = $this->db->query($statement);
        
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
    public function add_servico_prestador($data_lista)
    {
            
            if ($this->db->insert('sai_prestadores_servicos', $data_lista)) {
               $id_acao =  $this->db->insert_id();
                 
           
                 
                return true;
        }
          
        return false;
    }
    
    public function deleteServicosPrestador($id)
    {
       // $sale_items = $this->resetSaleActions($id);
        if ($this->db->delete('sai_prestadores_servicos', array('id' => $id))){
            return true;
        }
        return FALSE;
    }
    
    public function deleteServicos($id)
    {
       // $sale_items = $this->resetSaleActions($id);
        if ($this->db->delete('sai_servicos', array('id' => $id))){
            return true;
        }
        return FALSE;
    }
    
    /***********************************************************************************
     ************************* F I M * * A T E N D I M E N T O   S A I ****************
    ***************************************************************************************/

     public function getAllRegistroMigracao()
    {
        $statement = "SELECT * from sma_migracao_beneficiarios order by data_registro asc ";
        $q = $this->db->query($statement);
        
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
       public function add_Registro_Migracao($historico_acoes)
    {
          
            if ($this->db->insert('migracao_beneficiarios', $historico_acoes)) {
                $this->db->insert_id();
                 
                return true;
        }
          
        return false;
    }
    
    /*****************************************************************************
     ************************ F I M * * M I G R A Ç Ã O ************************* 
     ******************************************************************************/
    
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
    public function add_planoAcao($ata_plano,$data_vinculo,$avulsa,$responsavel)
    {
            
            if ($this->db->insert('planos', $ata_plano)) {
               $id_acao =  $this->db->insert_id();
                 
                
                // ADCIONA TODOS OS vinculos SELECIONADOS
               if($data_vinculo){
                  foreach ($data_vinculo as $item_vinculo) {
                        $vinculo= array('id_acao' => $id_acao,
                                        'id_vinculo' => $item_vinculo);      
                        $this->db->insert('acao_vinculos', $vinculo);
                 }
               }
               
               if($avulsa == 'SIM'){
                $this->ion_auth->emailAtaUsuario($responsavel, $id_acao);
             }
                 
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
     * PEGA TODOS OS PLANOS DE UM PROJETO , de um setor e por usuario
     */
     public function getAllitemPlanosProjetoSetorUser($id, $setor, $usuario)
    {
         $this->db->select('planos.idplanos, planos.data_termino as data_termino,eventos.nome_evento as eventos,item_evento.descricao as item, planos.data_retorno_usuario,planos.descricao as descricao, users.username,planos.status,setores.nome as setor, superintendencia.responsavel as superintendencia')
            ->join('users', 'planos.responsavel = users.id', 'left')
            ->join('setores', 'planos.setor = setores.id', 'left') 
            ->join('superintendencia', 'setores.superintendencia = superintendencia.id', 'left')     
            ->join('item_evento', 'planos.eventos = item_evento.id', 'left')    
            ->join('eventos', 'item_evento.evento = eventos.id', 'left')      
            ->join('atas', 'planos.idatas = atas.id', 'left')
         ->order_by('idplanos', 'DESC');
         $q = $this->db->get_where('planos', array('atas.projetos' => $id,'planos.setor' => $setor, 'users.id' => $usuario));
        
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
     public function getAllUserPlanosProjetoSetor($id, $setor)
    {
        // echo $setor; exit;
         $this->db->select('users.id as id,users.first_name as nome,users.last_name as sobrenome ')
            ->join('users', 'planos.responsavel = users.id', 'left')
            ->join('setores', 'planos.setor = setores.id', 'left') 
            ->join('superintendencia', 'setores.superintendencia = superintendencia.id', 'left')     
            ->join('atas', 'planos.idatas = atas.id', 'left')
            ->distinct()
         ->order_by('users.first_name', 'ASC');
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
     * PEGA TODOS OS PLANOS DE UM PROJETO e de um setor
     */
     public function getAllitemPlanosProjetoSetorCont($id, $setor)
    {
         
         $this->db->select('count(idplanos) as quantidade')
            ->join('users', 'planos.responsavel = users.id', 'left')
            ->join('setores', 'planos.setor = setores.id', 'left') 
            ->join('superintendencia', 'setores.superintendencia = superintendencia.id', 'left')     
            ->join('atas', 'planos.idatas = atas.id', 'left')
         ->order_by('idplanos', 'desc');
           $q = $this->db->get_where('planos', array('atas.projetos' => $id,'planos.setor' => $setor), 1);
     
        if ($q->num_rows() > 0) {
            return $q->row();
        }
       
        return FALSE;
    }
    
    /*
     * PEGA TODAS AS AÇÕES CONCLUÍDAS DE UM PROJETO e de um setor
     */
     public function getAllitemPlanosProjetoSetorContConcluido($id, $setor)
    {
         
         $this->db->select('count(idplanos) as quantidade')
            ->join('users', 'planos.responsavel = users.id', 'left')
            ->join('setores', 'planos.setor = setores.id', 'left') 
            ->join('superintendencia', 'setores.superintendencia = superintendencia.id', 'left')     
            ->join('atas', 'planos.idatas = atas.id', 'left')
         ->order_by('idplanos', 'desc');
           $q = $this->db->get_where('planos', array('atas.projetos' => $id,'planos.status' => 'CONCLUÍDO','planos.setor' => $setor), 1);
     
        if ($q->num_rows() > 0) {
            return $q->row();
        }
       
        return FALSE;
    }
    
    /*
     * PEGA TODAS AS AÇÕES PENDENTES DE UM PROJETO e de um setor
     */
     public function getAllitemPlanosProjetoSetorContPendente($status,$id, $setor)
    {
          $date_hoje = date('Y-m-d H:i:s');
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
        
           
           
           
        if ($q->num_rows() > 0) {
            return $q->row();
        }
       
        return FALSE;
    }
    
    /*
     * PEGA TODAS AS AÇÕES PENDENTES DE UM PROJETO e de um setor
     */
     public function getAllitemPlanosProjetoSetorContAguardandoValidacao($id, $setor)
    {
          $date_hoje = date('Y-m-d H:i:s');
         $this->db->select('count(idplanos) as quantidade')
            ->join('users', 'planos.responsavel = users.id', 'left')
            ->join('setores', 'planos.setor = setores.id', 'left') 
            ->join('superintendencia', 'setores.superintendencia = superintendencia.id', 'left')     
            ->join('atas', 'planos.idatas = atas.id', 'left')
         ->order_by('idplanos', 'desc');
           
         
         $q = $this->db->get_where('planos', array('atas.projetos' => $id,'planos.status' => 'AGUARDANDO VALIDAÇÃO','planos.setor' => $setor), 1);
     
            //  $q = $this->db->get_where('planos', array('atas.projetos' => $id, 'planos.status' => 'PENDENTE', 'users.setor_id' => $setor, 'planos.data_termino >' => $date_hoje), 1);
             
        
           
           
           
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
    public function getAllSetorArea($projeto, $id_area)
    {
        $this->db->select('setores.id as setor_id,setores.nome as setor, superintendencia.nome as superintendencia, superintendencia.id as id_area')
        ->join('superintendencia', 'setores.superintendencia = superintendencia.id', 'left')
        //->join('users', 'planos.responsavel = users.id', 'left')
        ->join('planos', 'setores.id = planos.setor', 'left')
        ->join('atas', 'planos.idatas = atas.id', 'left')        
       ->distinct()
        ->order_by('superintendencia.nome', 'asc');
        
        $q = $this->db->get_where('setores', array('superintendencia.id' => $id_area, 'atas.projetos' => $projeto));
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
    
    public function deletePlano($id)
    {
       // $sale_items = $this->resetSaleActions($id);
        if ($this->db->delete('planos', array('idplanos' => $id))){
           
            return true;
        }
        return FALSE;
    }
    
     public function getAllAcoesProjeto($id)
    {
       
        $this->db->select('planos.*')
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
    
     public function getAllAcoesVinculadas($id)
    {
        $this->db->select('*')
       ->join('planos', 'acao_vinculos.id_acao = planos.idplanos', 'left')
        ->order_by('idplanos', 'desc');
        $q = $this->db->get_where('acao_vinculos', array('id_acao' => $id));
        
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
    
     public function getAllHistoricoAcoes($idplano)
    {
        
         $this->db->select('users.id as user, observacao, plano, data_envio, username,anexo')
         ->join('users', 'usuario = users.id', 'left')
         ->order_by('historico_acao_usuario.id', 'asc');
         $q = $this->db->get_where('historico_acao_usuario', array('plano' => $idplano));
          
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
     public function getPlanoByID($id)
    {
        
        $q = $this->db->get_where('planos', array('idplanos' => $id), 1);
       
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
    
     public function updatePlano($id, $data  = array(),$data_vinculo= array())
    {  
       
        if ($this->db->update('planos', $data, array('idplanos' => $id))) {
            
            /*
             * ações vinculadas
             */
            $this->db->delete('acao_vinculos', array('id_acao' => $id));
           // ADCIONA TODOS OS MÓDULOS SELECIONADOS
                  foreach ($data_vinculo as $item_vinculo) {
                        $vinculo= array('id_acao' => $id,
                            'id_vinculo' => $item_vinculo);      
                        
                        $this->db->insert('acao_vinculos', $vinculo);
                 }
            
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
            
            if ($this->db->insert('historico_acao_usuario', $data_HistoricoPlano)) {
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
            
            if ($this->db->insert('users_setores', $data)) {
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
         $this->db->select('users_setores.id as id, setores.nome as setor,users.first_name as nome, users.last_name as last, users.id as user_id')
        ->join('users', 'users_setores.usuario = users.id', 'inner')
        ->join('setores', 'users_setores.setor = setores.id', 'inner')
        ->order_by('users.first_name', 'asc');         
        $q = $this->db->get('users_setores');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
     public function getUserSetorBYid($id)
    {
         $q = $this->db->get_where('users_setores', array('id' => $id), 1);
       
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
         $q = $this->db->get_where('users_setores', array('usuario' => $usuario, 'setor' => $setor), 1);
       
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
    
}
