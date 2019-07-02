<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Site extends CI_Model
{

    public function __construct() {
        parent::__construct();
    }


    
    public function getPerfilUserByID_User($id)
    {
        $q = $this->db->get_where('perfil_usuario', array('user_id' => $id));
        
        if ($q->num_rows() > 0) {
            
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
            
          //  return $q->row();
            
            
        }
        return FALSE;
         
    }
    
    
    /****************** Muda o status do e-mail para enviado ************************/   
     public function updateStatusEmailEnviado($id, $data  = array())
    {  
        
        if ($this->db->update('emails', $data, array('id' => $id))) {
            
        return true;
        }
        return false;
    }
    
     /*
     * PEGA A QUANTIDADE DE PLANOS NÃO CONCLUÍDO DE UM USUÁRIO
     */
     public function getAllPlanosPendenteUser($id)
    {
       // echo '<br><br>  '.$id;exit;
         $this->db->select('count(idplanos) as quantidade');
          $this->db->where('status !=', 'CONCLUÍDO'); 
         $q = $this->db->get_where('planos', array('responsavel' => $id, 'status !=' => 'ABERTO'), 1);
         
        // echo 'akiii'.$q->num_rows();
         
       if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
     /*
     * PEGA A QUANTIDADE DE PLANOS NÃO CONCLUÍDO DE UM USUÁRIO
     */
     public function getAllPlanosAguardandoValidacao($id)
    {
        //echo '<br><br>  '.$id;
         $this->db->select('count(idplanos) as quantidade')
         // $this->db->where('status !=', 'CONCLUÍDO'); 
         ->join('atas', 'planos.idatas = atas.id', 'left');
         $q = $this->db->get_where('planos', array('planos.status' => 'AGUARDANDO VALIDAÇÃO', 'projetos' => $id, 'id_ticket' => NULL), 1);
         
       
       if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
     public function updatePerfilAtualUsuario($id, $data  = array())
    {  
         //print_r($data); exit;
        if ($this->db->update('users', $data, array('id' => $id))) {
         return true;
        }
        return false;
    }
    
    public function getProjetoAtualByID_completo($id)
    {
     
         $this->db->select("projetos.*, users.*")
            ->join('projetos', 'users.projeto_atual = projetos.id', 'left');
         
         $q = $this->db->get_where('users', array('users.id' => $id));
        
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
         
    }
    
   
    
     public function getListGestores($id)
    {
     
         $this->db->select("perfil_usuario.id as id,users.id as users, group_id as grupo, users.first_name as fname, users.last_name as lname")
            ->join('users', 'perfil_usuario.user_id = users.id', 'left')
                 
            ->group_by('users');
         $q = $this->db->get_where('perfil_usuario', array('grupo_id' => $id));
        
       if ($q->num_rows() > 0) {
             foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
         
    }
    
     
    
    
     public function getAllGestorProjeto($projeto)
    {
        
        $this->db->select("users.id as id_user,users.first_name as first_name, users.last_name as last_name, users_gestor.setor as setor_id, users_gestor.id as id, setores.nome as setor")
        ->join('users', 'users_gestor.users = users.id', 'inner')
        ->join('setores', 'users_gestor.setor = setores.id', 'inner')
        ->distinct();
        $q = $this->db->get_where('users_gestor', array('projeto' => $projeto));
         
       
        
        
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
     public function getAllSetoresVinculados($id,$projeto)
    {
        
        $this->db->select("users_gestor.setor as setor_id, users_gestor.id as id, setores.nome as setor, users_gestor.projeto as projeto")
            ->join('setores', 'users_gestor.setor = setores.id', 'left');
        // ->join('projetos', 'users_gestor.setor = setores.id', 'left');
        $q = $this->db->get_where('users_gestor', array('users' => $id,'projeto' => $projeto));
         
       
        
        
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
     public function getAllAreasVinculadas($id,$projeto)
    {
        
        $this->db->select("users_superintendencia.superintendencia as sup_id,  superintendencia.nome as superintendencia")
            ->join('superintendencia', 'users_superintendencia.superintendencia = superintendencia.id', 'left');
        $q = $this->db->get_where('users_superintendencia', array('users' => $id,'projeto' => $projeto));
         
       
        
        
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
    
    public function AtualizarsetoresVinculados($projeto_atual, $setor, $id_usuario){
        
        $this->db->delete('users_gestor', array('users' => $id_usuario, 'projeto' => $projeto_atual));
            
                foreach ($setor as $item) {
                        $data_perfil_usuario = array('users' => $id_usuario, 'setor' => $item, 'projeto' => $projeto_atual);      
                        
                        $this->db->insert('users_gestor', $data_perfil_usuario );
                 }
    }
    
    
    public function AtualizarAreasVinculados($projeto_atual, $setor, $id_usuario){
        
        $this->db->delete('users_superintendencia', array('users' => $id_usuario, 'projeto' => $projeto_atual));
            
                foreach ($setor as $item) {
                        $data_perfil_usuario = array('users' => $id_usuario, 'superintendencia' => $item, 'projeto' => $projeto_atual);      
                        
                        $this->db->insert('users_superintendencia', $data_perfil_usuario );
                 }
    }
    
     public function getPerfilAtualByID($id)
    {
     
         $q = $this->db->get_where('users', array('users.id' => $id));
        
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
         
    }
    
      public function getPerfilAtualSistemasByID($id)
    {
    
        $this->db->select("count(id) as quantidade");
        $q = $this->db->get_where('user_sistema', array('usuario' => $id));
        
       
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
         
    }
    
    
      public function getAllSistemasByID($id)
    {
    
        $this->db->select("*")
         ->join('sistemas', 'user_sistema.sistema = sistemas.id', 'left');
        $q = $this->db->get_where('user_sistema', array('usuario' => $id));
        
       
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
         
    }
    
    public function getContaPerfilusuarioByID($id)
    {
     
         $this->db->select("count(id) as quantidade");
         
         $q = $this->db->get_where('perfil_usuario', array('perfil_usuario.user_id' => $id));
        
        if ($q->num_rows() > 0) {
             foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
         
    }
    
    public function getUserGroupAtual($group_id = false) {
       
       
        $q = $this->db->get_where('groups', array('id' => $group_id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
    public function getPerfilusuarioByID($id)
    {
     
         $this->db->select("groups.*, perfil_usuario.*")
           ->join('groups', 'perfil_usuario.grupo_id = groups.id', 'left');
         
         $q = $this->db->get_where('perfil_usuario', array('perfil_usuario.user_id' => $id));
        
        if ($q->num_rows() > 0) {
             foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
         
    }
    
    public function getSoPerfilusuarioByID($id)
    {
     
        // $this->db->select("grupo_id");
          // ->join('groups', 'perfil_usuario.grupo_id = groups.id', 'left');
         
         $q = $this->db->get_where('perfil_usuario', array('user_id' => $id, 'grupo_id !=' => '5'));
                
       if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
         
    }
    
    
     public function getPerfilGestorByIDandProjeto($id, $projeto)
    {
         
         
           $this->db->select('count(id) as quantidade');
        $q = $this->db->get_where('users_gestor', array('users' => $id, 'projeto' => $projeto), 1);
     
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
        
         
    }
    
    public function getPerfilSuperintendenteByIDandProjeto($id, $projeto)
    {
         
         
           $this->db->select('count(id) as quantidade');
        $q = $this->db->get_where('users_superintendencia', array('users' => $id, 'projeto' => $projeto), 1);
     
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
        
         
    }
    
    /*
     * NOVOS
     */
    
     public function getAllAccount() {
        $q = $this->db->get('accounts');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
     public function getAllContratos() {
        $this->db->select('contratos.id as id, contratos.numero_contrato as contrato, companies.company as company')
        ->join('companies', 'contratos.fornecedor = companies.id', 'left');        
        $q = $this->db->get('contratos');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
    
   
    
     public function getAccount($id) {
        $q = $this->db->get_where('accounts', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }    
    
    //******************

    
    
    public function get_total_qty_alerts() {
        $this->db->where('quantity < alert_quantity', NULL, FALSE)->where('track_quantity', 1);
        return $this->db->count_all_results('products');
    }

    public function get_expiring_qty_alerts() {
        $date = date('Y-m-d', strtotime('+3 months'));
        $this->db->select('SUM(quantity_balance) as alert_num')
        ->where('expiry !=', NULL)->where('expiry !=', '0000-00-00')
        ->where('expiry <', $date);
        $q = $this->db->get('purchase_items');
        if ($q->num_rows() > 0) {
            $res = $q->row();
            return (INT) $res->alert_num;
        }
        return FALSE;
    }

    public function get_setting() {
        $q = $this->db->get('settings');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getDateFormat($id) {
        $q = $this->db->get_where('date_format', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    
    
    public function getAllCompanies($group_name) {
        $q = $this->db->get_where('companies', array('group_name' => $group_name));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getCompanyByID($id) {
        $q = $this->db->get_where('companies', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getCustomerGroupByID($id) {
        $q = $this->db->get_where('customer_groups', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getAllUser() {
             $this->db->order_by('username', 'asc');
        $q = $this->db->get_where('users', array('active' => 1));
           
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
     public function getAllUserSetor($setor) {
             $this->db->order_by('username', 'asc');
        $q = $this->db->get_where('users', array('active' => 1,'setor_id' => $setor));
           
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
    public function getAllsetores() {
       
        
        $this->db->select("setores.id as id_setor,setores.nome as setor, superintendencia.id as id_superintendencia, superintendencia.nome as superintendencia ,prestadores.id as id_prestador, prestadores.prestador as prestador ")
        ->join('prestadores', 'setores.prestador = prestadores.id', 'left')   
        ->join('superintendencia', 'setores.superintendencia = superintendencia.id', 'left');
         $this->db->order_by('setores.nome', 'asc');        
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
     * PEGA OS USUÁRIOS DA TABELA sma_users_listas_participantes PELO USUÁRIO E PROJETO
     */
    public function getAllUserListaParticipantes($users, $projeto) {
           //  $this->db->order_by('username', 'asc');
        $q = $this->db->get_where('sma_users_listas_participantes', array('users' => $users,'projeto' => $projeto));
           
       if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
    /*
     * SALVA NA TABELA USERS PARTICIPANTES
     */
        public function addListaUsersParticipantes($data)
    {
            if ($this->db->insert('users_listas_participantes', $data)) {
                 $id_ata = $this->db->insert_id();
                 
               return $id_ata;
        }
          
        return false;
    }
    
    
    /*
     *  UPDATE NA TABELA LISTA PARTICIPAÇÃO
     */
    
     public function updateUsuariosLista($users, $projeto, $data  = array())
    {  
       // print_r($data); exit;
        
        if ($this->db->update('users_listas_participantes', $data, array('users' => $users,'projeto' => $projeto))) {
            
            
         return true;
        }
        return false;
    }
    
    /*
     * PEGA AS ÁREAS QUE TE SETORES
     */
    public function getAllSuperintendencia() {
        
      $this->db->select("superintendencia.id as superintendencia_id, superintendencia.nome as superintendencia ")
        ->join('setores', 'superintendencia.id = setores.superintendencia', 'inner')
        ->join('users', 'setores.id = users.setor_id', 'inner')      
        ->group_by('superintendencia.id', 'asc');        
        //->order_by('nome', 'asc')
        
         $this->db->order_by('superintendencia.nome', 'asc');  
         $q = $this->db->get('superintendencia');
        //$q = $this->db->get_where('superintendencia', array('active' => 1));
           
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
    /*
     * PEGA AS ÁREAS QUE TE SETORES
     */
    public function getAllSuperintendenciaSetor($superintendencia) {
        
      $this->db->select("setores.id as id_setor, setores.nome as setor")
        ->join('users', 'setores.id = users.setor_id', 'inner')
        ->group_by('setores.id', 'asc');        
        $this->db->order_by('setores.nome', 'asc');  
        $q = $this->db->get_where('setores', array('setores.superintendencia' => $superintendencia));
           
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
    
     public function getAllsetoresListaUsuario() {
       
        
        $this->db->select("setores.id as id_setor,setores.nome as setor, superintendencia.id as id_superintendencia, superintendencia.nome as superintendencia ,prestadores.id as id_prestador, prestadores.prestador as prestador ")
        ->join('prestadores', 'setores.prestador = prestadores.id', 'left')   
        ->join('superintendencia', 'setores.superintendencia = superintendencia.id', 'left');
         $this->db->order_by('setores.nome', 'asc');        
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
     * PEGA TODOS OS SETORES DE UM EVENTO
     */
    public function getAllsetoresEento($id) {
       
        
      //  $this->db->select("setores.id as id_setor,setores.nome as setor, superintendencia.id as id_superintendencia, superintendencia.nome as superintendencia ,prestadores.id as id_prestador, prestadores.prestador as prestador ")
      //  ->join('prestadores', 'setores.prestador = prestadores.id', 'left')   
      //  ->join('superintendencia', 'setores.superintendencia = superintendencia.id', 'left');
      //   $this->db->order_by('setores.nome', 'asc');        
      //  $q = $this->db->get('setores');
       $q = $this->db->get_where('setor_evento', array('evento' => $id));     
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
    
    /*
     * PEGA TODOS OS SETORES DE UM EVENTO
     */
    public function getAllModulosEento($id) {
      //  $this->db->select("setores.id as id_setor,setores.nome as setor, superintendencia.id as id_superintendencia, superintendencia.nome as superintendencia ,prestadores.id as id_prestador, prestadores.prestador as prestador ")
      //  ->join('prestadores', 'setores.prestador = prestadores.id', 'left')   
      //  ->join('superintendencia', 'setores.superintendencia = superintendencia.id', 'left');
      //   $this->db->order_by('setores.nome', 'asc');        
      //  $q = $this->db->get('setores');
       $q = $this->db->get_where('modulo_evento', array('evento' => $id));     
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
    public function getAllModulos($id) {
             $this->db->order_by('descricao', 'asc');
        $q = $this->db->get_where('modulos', array('projeto' => $id));
           
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
     public function getAtaProjetoByID_ATA($id)
    {
      
         $this->db->select($this->db->dbprefix('atas') . ".id as id,  projetos.projeto as projetos, " ."data_ata, pauta, participantes,  tipo, responsavel_elaboracao, assinaturas, pendencias")
            ->join('projetos', 'atas.projetos = projetos.id', 'left');
         
         $q = $this->db->get_where('atas', array('atas.id' => $id));
    
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
         
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
    
    public function getAllProjetosStatusUsers($id, $status)
    {
        $this->db->select("projetos.id as projeto")
        ->join('projetos', 'users_projetos.projeto = projetos.id', 'inner');
        $q = $this->db->get_where('users_projetos', array('users' => $id, 'status' => $status));
        
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
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
    
    /*
     * PEGA TODOS OS PLANOS DE UMA ATA
     */
     public function getAllitemPlanos($id)
    {
         $this->db->select('planos.*, users.*')
            ->join('users', 'planos.responsavel = users.id', 'left');
         $q = $this->db->get_where('planos', array('idatas' => $id));
         
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
      public function getUserbyemail($email = NULL, $empresa) {
      
        $q = $this->db->get_where('users', array('email' => $email, 'empresa_id' => $empresa), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
      public function getUserbyMatricula($matricula) {
       
         $statement = "SELECT * from sma_users where username = '$matricula' or matricula = '$matricula'";
        $q = $this->db->query($statement);  
      //  $q = $this->db->get_where('users', array('username' => $matricula), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
    public function getUser($id_user) {
            $empresa = $this->session->userdata('empresa');
      
        $statement = "select * from sig_users where id = $id_user and empresa_id = $empresa ";    
      //  echo $statement; exit;
        $q = $this->db->query($statement);
     //   $q = $this->db->get_where('users', array('id' => $id_user, 'empresa_id' => $empresa), 1);
     
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
    // usado no envio de email
      public function geUserByIDSemEmpresa($id)
    {
        //$empresa = $this->session->userdata('empresa');
         $q = $this->db->get_where('users', array('id' => $id));
    
        if ($q->num_rows() > 0) {
            return $q->row();
            
        }
        return FALSE;
         
    }
    
      public function geUserByID($id)
    {
        $empresa = $this->session->userdata('empresa');
         $q = $this->db->get_where('users', array('id' => $id, 'empresa_id' => $empresa));
    
        if ($q->num_rows() > 0) {
            return $q->row();
            
        }
        return FALSE;
         
    }
    
     public function getUserSetorByUser($id_user)
    {
        $statement = "select us.id as id, u.id as user_id, u.first_name as first_name, u.avatar as avatar, u.gender as gender, us.setores_id as setor_id, s.nome as setor, u.empresa_id as empresa_id
                    from sig_users u
                    inner join sig_users_setor us on us.users_id = u.id
                    inner join sig_setores s on s.id = us.setores_id "
                 . " where us.users_id = $id_user";    
      // echo $statement; exit;
        $q = $this->db->query($statement);
        
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
    public function getNFe($id = NULL) {
        
        $q = $this->db->get_where('nfe', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

      public function getEmpresa($id = NULL) {
        
        $q = $this->db->get_where('empresa', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
    public function getProductByID($id) {
        $q = $this->db->get_where('products', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getAllCurrencies() {
        $q = $this->db->get('currencies');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getCurrencyByCode($code) {
        $q = $this->db->get_where('currencies', array('code' => $code), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getAllTaxRates() {
        $q = $this->db->get('tax_rates');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getTaxRateByID($id) {
        $q = $this->db->get_where('tax_rates', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getAllWarehouses() {
        $q = $this->db->get('warehouses');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
       
    
    public function getWarehouseByID($id) {
        $q = $this->db->get_where('warehouses', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
        public function getAllCategories_expencie($tipo) {
        $this->db->order_by('name');
        $q = $this->db->get_where('expense_categories', array('code' => $tipo));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

      public function getCategoryExpenseByID($id) {
        $q = $this->db->get_where('expense_categories', array('id' => $id), 1);
     
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getAllCategories() {
        $this->db->order_by('name');
        $q = $this->db->get('categories');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getCategoryByID($id) {
        $q = $this->db->get_where('categories', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getGiftCardByID($id) {
        $q = $this->db->get_where('gift_cards', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getGiftCardByNO($no) {
        $q = $this->db->get_where('gift_cards', array('card_no' => $no), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function updateInvoiceStatus() {
        $date = date('Y-m-d');
        $q = $this->db->get_where('invoices', array('status' => 'unpaid'));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                if ($row->due_date < $date) {
                    $this->db->update('invoices', array('status' => 'due'), array('id' => $row->id));
                }
            }
            $this->db->update('settings', array('update' => $date), array('setting_id' => '1'));
            return true;
        }
    }

    public function modal_js() {
        return '<script type="text/javascript">' . file_get_contents($this->data['assets'] . 'js/modal.js') . '</script>';
    }

    public function getReference($field) {
        $q = $this->db->get_where('order_ref', array('ref_id' => '1'), 1);
        if ($q->num_rows() > 0) {
            $ref = $q->row();
            switch ($field) {
                case 'so':
                    $prefix = $this->Settings->sales_prefix;
                    break;
                case 'pos':
                    $prefix = isset($this->Settings->sales_prefix) ? $this->Settings->sales_prefix . '/POS' : '';
                    break;
                case 'qu':
                    $prefix = $this->Settings->quote_prefix;
                    break;
                case 'po':
                    $prefix = $this->Settings->purchase_prefix;
                    break;
                case 'to':
                    $prefix = $this->Settings->transfer_prefix;
                    break;
                case 'do':
                    $prefix = $this->Settings->delivery_prefix;
                    break;
                case 'pay':
                    $prefix = $this->Settings->payment_prefix;
                    break;
                case 'ex':
                    $prefix = $this->Settings->expense_prefix;
                    break;
                case 're':
                    $prefix = $this->Settings->return_prefix;
                    break;
                case 'rep':
                    $prefix = $this->Settings->returnp_prefix;
                    break;
                default:
                    $prefix = '';
            }

            $ref_no = (!empty($prefix)) ? $prefix . '/' : '';

            if ($this->Settings->reference_format == 1) {
                $ref_no .= date('Y') . "/" . sprintf("%04s", $ref->{$field});
            } elseif ($this->Settings->reference_format == 2) {
                $ref_no .= date('Y') . "/" . date('m') . "/" . sprintf("%04s", $ref->{$field});
            } elseif ($this->Settings->reference_format == 3) {
                $ref_no .= sprintf("%04s", $ref->{$field});
            } else {
                $ref_no .= $this->getRandomReference();
            }

            return $ref_no;
        }
        return FALSE;
    }

    public function getRandomReference($len = 12) {
        $result = '';
        for ($i = 0; $i < $len; $i++) {
            $result .= mt_rand(0, 9);
        }

        if ($this->getSaleByReference($result)) {
            $this->getRandomReference();
        }

        return $result;
    }

    public function getSaleByReference($ref) {
        $this->db->like('reference_no', $ref, 'before');
        $q = $this->db->get('sales', 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function updateReference($field) {
        $q = $this->db->get_where('order_ref', array('ref_id' => '1'), 1);
        if ($q->num_rows() > 0) {
            $ref = $q->row();
            $this->db->update('order_ref', array($field => $ref->{$field} + 1), array('ref_id' => '1'));
            return TRUE;
        }
        return FALSE;
    }

    public function checkPermissions() {
        $q = $this->db->get_where('permissions', array('group_id' => $this->session->userdata('group_id')), 1);
        if ($q->num_rows() > 0) {
            return $q->result_array();
        }
        return FALSE;
    }

    public function getNotifications() {
        $date = date('Y-m-d H:i:s', time());
        $this->db->where("from_date <=", $date);
        $this->db->where("till_date >=", $date);
        if (!$this->Owner) {
            if ($this->Supplier) {
                $this->db->where('scope', 4);
            } elseif ($this->Customer) {
                $this->db->where('scope', 1)->or_where('scope', 3);
            } elseif (!$this->Customer && !$this->Supplier) {
                $this->db->where('scope', 2)->or_where('scope', 3);
            }
        }
        $q = $this->db->get("notifications");
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }

    public function getUpcomingEvents() {
        $dt = date('Y-m-d');
        $this->db->where('start >=', $dt)->order_by('start')->limit(5);
        if ($this->Settings->restrict_calendar) {
            $this->db->where('user_id', $this->session->userdata('user_id'));
        }

        $q = $this->db->get('calendar');

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getUserGroup($user_id = false) {
        if (!$user_id) {
            $user_id = $this->session->userdata('user_id');
        }
        $group_id = $this->getUserGroupID($user_id);
        $q = $this->db->get_where('groups', array('id' => $group_id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getUserGroupID($user_id = false) {
        $user = $this->getUser($user_id);
        return $user->group_id;
    }

    public function getWarehouseProductsVariants($option_id, $warehouse_id = NULL) {
        if ($warehouse_id) {
            $this->db->where('warehouse_id', $warehouse_id);
        }
        $q = $this->db->get_where('warehouses_products_variants', array('option_id' => $option_id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getPurchasedItem($where_clause) {
        $orderby = ($this->Settings->accounting_method == 1) ? 'asc' : 'desc';
        $this->db->order_by('date', $orderby);
        $this->db->order_by('purchase_id', $orderby);
        $q = $this->db->get_where('purchase_items', $where_clause);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function syncVariantQty($variant_id, $warehouse_id, $product_id = NULL) {
        $balance_qty = $this->getBalanceVariantQuantity($variant_id);
        $wh_balance_qty = $this->getBalanceVariantQuantity($variant_id, $warehouse_id);
        if ($this->db->update('product_variants', array('quantity' => $balance_qty), array('id' => $variant_id))) {
            if ($this->getWarehouseProductsVariants($variant_id, $warehouse_id)) {
                $this->db->update('warehouses_products_variants', array('quantity' => $wh_balance_qty), array('option_id' => $variant_id, 'warehouse_id' => $warehouse_id));
            } else {
                if($wh_balance_qty) {
                    $this->db->insert('warehouses_products_variants', array('quantity' => $wh_balance_qty, 'option_id' => $variant_id, 'warehouse_id' => $warehouse_id, 'product_id' => $product_id));
                }
            }
            return TRUE;
        }
        return FALSE;
    }

    public function getWarehouseProducts($product_id, $warehouse_id = NULL) {
        if ($warehouse_id) {
            $this->db->where('warehouse_id', $warehouse_id);
        }
        $q = $this->db->get_where('warehouses_products', array('product_id' => $product_id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function syncProductQty($product_id, $warehouse_id) {
        $balance_qty = $this->getBalanceQuantity($product_id);
        $wh_balance_qty = $this->getBalanceQuantity($product_id, $warehouse_id);
        if ($this->db->update('products', array('quantity' => $balance_qty), array('id' => $product_id))) {
            if ($this->getWarehouseProducts($product_id, $warehouse_id)) {
                $this->db->update('warehouses_products', array('quantity' => $wh_balance_qty), array('product_id' => $product_id, 'warehouse_id' => $warehouse_id));
            } else {
                if( ! $wh_balance_qty) { $wh_balance_qty = 0; }
                $this->db->insert('warehouses_products', array('quantity' => $wh_balance_qty, 'product_id' => $product_id, 'warehouse_id' => $warehouse_id));
            }
            return TRUE;
        }
        return FALSE;
    }

    public function getSaleByID($id) {
        $q = $this->db->get_where('sales', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getSalePayments($sale_id) {
        $q = $this->db->get_where('payments', array('sale_id' => $sale_id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function syncSalePayments($id) {
        $sale = $this->getSaleByID($id);
        $payments = $this->getSalePayments($id);
        $paid = 0;
        foreach ($payments as $payment) {
            $paid += $payment->amount;
        }

        $payment_status = $paid == 0 ? 'pending' : $sale->payment_status;
        if ($this->sma->formatDecimal($sale->grand_total) == $this->sma->formatDecimal($paid)) {
            $payment_status = 'paid';
        } elseif ($paid != 0) {
            $payment_status = 'partial';
        } elseif ($sale->due_date <= date('Y-m-d') && !$sale->sale_id) {
            $payment_status = 'due';
        }

        if ($this->db->update('sales', array('paid' => $paid, 'payment_status' => $payment_status), array('id' => $id))) {
            return true;
        }

        return FALSE;
    }

    public function getPurchaseByID($id) {
        $q = $this->db->get_where('purchases', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getPurchasePayments($purchase_id) {
        $q = $this->db->get_where('payments', array('purchase_id' => $purchase_id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function syncPurchasePayments($id) {
        $purchase = $this->getPurchaseByID($id);
        $payments = $this->getPurchasePayments($id);
        $paid = 0;
        foreach ($payments as $payment) {
            $paid += $payment->amount;
        }

        $payment_status = $paid <= 0 ? 'pending' : $purchase->payment_status;
        if ($this->sma->formatDecimal($purchase->grand_total) > $this->sma->formatDecimal($paid) && $paid > 0) {
            $payment_status = 'partial';
        } elseif ($this->sma->formatDecimal($purchase->grand_total) <= $this->sma->formatDecimal($paid)) {
            $payment_status = 'paid';
        }

        if ($this->db->update('purchases', array('paid' => $paid, 'payment_status' => $payment_status), array('id' => $id))) {
            return true;
        }

        return FALSE;
    }

    private function getBalanceQuantity($product_id, $warehouse_id = NULL) {
        $this->db->select('SUM(COALESCE(quantity_balance, 0)) as stock', False);
        $this->db->where('product_id', $product_id)->where('quantity_balance !=', 0);
        if ($warehouse_id) {
            $this->db->where('warehouse_id', $warehouse_id);
        }
        $q = $this->db->get('purchase_items');
        if ($q->num_rows() > 0) {
            $data = $q->row();
            return $data->stock;
        }
        return 0;
    }

    private function getBalanceVariantQuantity($variant_id, $warehouse_id = NULL) {
        $this->db->select('SUM(COALESCE(quantity_balance, 0)) as stock', False);
        $this->db->where('option_id', $variant_id)->where('quantity_balance !=', 0);
        if ($warehouse_id) {
            $this->db->where('warehouse_id', $warehouse_id);
        }
        $q = $this->db->get('purchase_items');
        if ($q->num_rows() > 0) {
            $data = $q->row();
            return $data->stock;
        }
        return 0;
    }

    public function calculateAVCost($product_id, $warehouse_id, $net_unit_price, $unit_price, $quantity, $product_name, $option_id, $item_quantity) {
        $real_item_qty = $quantity;
        $wp_details = $this->getWarehouseProduct($warehouse_id, $product_id);
        if ($pis = $this->getPurchasedItems($product_id, $warehouse_id, $option_id)) {
            $cost_row = array();
            $quantity = $item_quantity;
            $balance_qty = $quantity;
            $avg_net_unit_cost = $wp_details->avg_cost;
            $avg_unit_cost = $wp_details->avg_cost;
            foreach ($pis as $pi) {
                if (!empty($pi) && $pi->quantity > 0 && $balance_qty <= $quantity && $quantity > 0) {
                    if ($pi->quantity_balance >= $quantity && $quantity > 0) {
                        $balance_qty = $pi->quantity_balance - $quantity;
                        $cost_row = array('date' => date('Y-m-d'), 'product_id' => $product_id, 'sale_item_id' => 'sale_items.id', 'purchase_item_id' => $pi->id, 'quantity' => $real_item_qty, 'purchase_net_unit_cost' => $avg_net_unit_cost, 'purchase_unit_cost' => $avg_unit_cost, 'sale_net_unit_price' => $net_unit_price, 'sale_unit_price' => $unit_price, 'quantity_balance' => $balance_qty, 'inventory' => 1, 'option_id' => $option_id);
                        $quantity = 0;
                    } elseif ($quantity > 0) {
                        $quantity = $quantity - $pi->quantity_balance;
                        $balance_qty = $quantity;
                        $cost_row = array('date' => date('Y-m-d'), 'product_id' => $product_id, 'sale_item_id' => 'sale_items.id', 'purchase_item_id' => $pi->id, 'quantity' => $pi->quantity_balance, 'purchase_net_unit_cost' => $avg_net_unit_cost, 'purchase_unit_cost' => $avg_unit_cost, 'sale_net_unit_price' => $net_unit_price, 'sale_unit_price' => $unit_price, 'quantity_balance' => 0, 'inventory' => 1, 'option_id' => $option_id);
                    }
                }
                if (empty($cost_row)) {
                    break;
                }
                $cost[] = $cost_row;
                if ($quantity == 0) {
                    break;
                }
            }
        }
        if ($quantity > 0 && !$this->Settings->overselling) {
            $this->session->set_flashdata('error', sprintf(lang("quantity_out_of_stock_for_%s"), ($pi->product_name ? $pi->product_name : $product_name)));
            redirect($_SERVER["HTTP_REFERER"]);
        } elseif ($quantity > 0) {
            $cost[] = array('date' => date('Y-m-d'), 'product_id' => $product_id, 'sale_item_id' => 'sale_items.id', 'purchase_item_id' => NULL, 'quantity' => $real_item_qty, 'purchase_net_unit_cost' => $wp_details->avg_cost, 'purchase_unit_cost' => $wp_details->avg_cost, 'sale_net_unit_price' => $net_unit_price, 'sale_unit_price' => $unit_price, 'quantity_balance' => NULL, 'overselling' => 1, 'inventory' => 1);
            $cost[] = array('pi_overselling' => 1, 'product_id' => $product_id, 'quantity_balance' => (0 - $quantity), 'warehouse_id' => $warehouse_id, 'option_id' => $option_id);
        }
        return $cost;
    }

    public function calculateCost($product_id, $warehouse_id, $net_unit_price, $unit_price, $quantity, $product_name, $option_id, $item_quantity) {
        $pis = $this->getPurchasedItems($product_id, $warehouse_id, $option_id);
        $real_item_qty = $quantity;
        $quantity = $item_quantity;
        $balance_qty = $quantity;
        foreach ($pis as $pi) {
            if (!empty($pi) && $balance_qty <= $quantity && $quantity > 0) {
                $purchase_unit_cost = $pi->unit_cost ? $pi->unit_cost : ($pi->net_unit_cost + ($pi->item_tax / $pi->quantity));
                if ($pi->quantity_balance >= $quantity && $quantity > 0) {
                    $balance_qty = $pi->quantity_balance - $quantity;
                    $cost_row = array('date' => date('Y-m-d'), 'product_id' => $product_id, 'sale_item_id' => 'sale_items.id', 'purchase_item_id' => $pi->id, 'quantity' => $real_item_qty, 'purchase_net_unit_cost' => $pi->net_unit_cost, 'purchase_unit_cost' => $purchase_unit_cost, 'sale_net_unit_price' => $net_unit_price, 'sale_unit_price' => $unit_price, 'quantity_balance' => $balance_qty, 'inventory' => 1, 'option_id' => $option_id);
                    $quantity = 0;
                } elseif ($quantity > 0) {
                    $quantity = $quantity - $pi->quantity_balance;
                    $balance_qty = $quantity;
                    $cost_row = array('date' => date('Y-m-d'), 'product_id' => $product_id, 'sale_item_id' => 'sale_items.id', 'purchase_item_id' => $pi->id, 'quantity' => $pi->quantity_balance, 'purchase_net_unit_cost' => $pi->net_unit_cost, 'purchase_unit_cost' => $purchase_unit_cost, 'sale_net_unit_price' => $net_unit_price, 'sale_unit_price' => $unit_price, 'quantity_balance' => 0, 'inventory' => 1, 'option_id' => $option_id);
                }
            }
            $cost[] = $cost_row;
            if ($quantity == 0) {
                break;
            }
        }
        if ($quantity > 0) {
            $this->session->set_flashdata('error', sprintf(lang("quantity_out_of_stock_for_%s"), ($pi->product_name ? $pi->product_name : $product_name)));
            redirect($_SERVER["HTTP_REFERER"]);
        }
        return $cost;
    }

    public function getPurchasedItems($product_id, $warehouse_id, $option_id = NULL) {
        $orderby = ($this->Settings->accounting_method == 1) ? 'asc' : 'desc';
        $this->db->select('id, quantity, quantity_balance, net_unit_cost, unit_cost, item_tax');
        $this->db->where('product_id', $product_id)->where('warehouse_id', $warehouse_id)->where('quantity_balance !=', 0);
        if ($option_id) {
            $this->db->where('option_id', $option_id);
        }
        $this->db->group_by('id');
        $this->db->order_by('date', $orderby);
        $this->db->order_by('purchase_id', $orderby);
        $q = $this->db->get('purchase_items');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getProductComboItems($pid, $warehouse_id = NULL)
    {
        $this->db->select('products.id as id, combo_items.item_code as code, combo_items.quantity as qty, products.name as name, products.type as type, combo_items.unit_price as unit_price, warehouses_products.quantity as quantity')
            ->join('products', 'products.code=combo_items.item_code', 'left')
            ->join('warehouses_products', 'warehouses_products.product_id=products.id', 'left')
            ->group_by('combo_items.id');
        if($warehouse_id) {
            $this->db->where('warehouses_products.warehouse_id', $warehouse_id);
        }
        $q = $this->db->get_where('combo_items', array('combo_items.product_id' => $pid));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }
        return FALSE;
    }

    public function item_costing($item, $pi = NULL) {
        $item_quantity = $pi ? $item['aquantity'] : $item['quantity'];
        if (!isset($item['option_id']) || empty($item['option_id']) || $item['option_id'] == 'null') {
            $item['option_id'] = NULL;
        }

        if ($this->Settings->accounting_method != 2 && !$this->Settings->overselling) {

            if ($this->getProductByID($item['product_id'])) {
                if ($item['product_type'] == 'standard') {
                    $cost = $this->calculateCost($item['product_id'], $item['warehouse_id'], $item['net_unit_price'], $item['unit_price'], $item['quantity'], $item['product_name'], $item['option_id'], $item_quantity);
                } elseif ($item['product_type'] == 'combo') {
                    $combo_items = $this->getProductComboItems($item['product_id'], $item['warehouse_id']);
                    foreach ($combo_items as $combo_item) {
                        $pr = $this->getProductByCode($combo_item->code);
                        if ($pr->tax_rate) {
                            $pr_tax = $this->getTaxRateByID($pr->tax_rate);
                            if ($pr->tax_method) {
                                $item_tax = $this->sma->formatDecimal((($combo_item->unit_price) * $pr_tax->rate) / (100 + $pr_tax->rate));
                                $net_unit_price = $combo_item->unit_price - $item_tax;
                                $unit_price = $combo_item->unit_price;
                            } else {
                                $item_tax = $this->sma->formatDecimal((($combo_item->unit_price) * $pr_tax->rate) / 100);
                                $net_unit_price = $combo_item->unit_price;
                                $unit_price = $combo_item->unit_price + $item_tax;
                            }
                        } else {
                            $net_unit_price = $combo_item->unit_price;
                            $unit_price = $combo_item->unit_price;
                        }
                        if ($pr->type == 'standard') {
                            $cost = $this->calculateCost($pr->id, $item['warehouse_id'], $net_unit_price, $unit_price, ($combo_item->qty * $item['quantity']), $pr->name, NULL, $item_quantity);
                        } else {
                            $cost = array(array('date' => date('Y-m-d'), 'product_id' => $pr->id, 'sale_item_id' => 'sale_items.id', 'purchase_item_id' => NULL, 'quantity' => ($combo_item->qty * $item['quantity']), 'purchase_net_unit_cost' => 0, 'purchase_unit_cost' => 0, 'sale_net_unit_price' => $combo_item->unit_price, 'sale_unit_price' => $combo_item->unit_price, 'quantity_balance' => NULL, 'inventory' => NULL));
                        }
                    }
                } else {
                    $cost = array(array('date' => date('Y-m-d'), 'product_id' => $item['product_id'], 'sale_item_id' => 'sale_items.id', 'purchase_item_id' => NULL, 'quantity' => $item['quantity'], 'purchase_net_unit_cost' => 0, 'purchase_unit_cost' => 0, 'sale_net_unit_price' => $item['net_unit_price'], 'sale_unit_price' => $item['unit_price'], 'quantity_balance' => NULL, 'inventory' => NULL));
                }
            } elseif ($item['product_type'] == 'manual') {
                $cost = array(array('date' => date('Y-m-d'), 'product_id' => $item['product_id'], 'sale_item_id' => 'sale_items.id', 'purchase_item_id' => NULL, 'quantity' => $item['quantity'], 'purchase_net_unit_cost' => 0, 'purchase_unit_cost' => 0, 'sale_net_unit_price' => $item['net_unit_price'], 'sale_unit_price' => $item['unit_price'], 'quantity_balance' => NULL, 'inventory' => NULL));
            }

        } else {

            if ($this->getProductByID($item['product_id'])) {
                if ($item['product_type'] == 'standard') {
                    $cost = $this->calculateAVCost($item['product_id'], $item['warehouse_id'], $item['net_unit_price'], $item['unit_price'], $item['quantity'], $item['product_name'], $item['option_id'], $item_quantity);
                } elseif ($item['product_type'] == 'combo') {
                    $combo_items = $this->getProductComboItems($item['product_id'], $item['warehouse_id']);
                    foreach ($combo_items as $combo_item) {
                        $cost = $this->calculateAVCost($combo_item->id, $item['warehouse_id'], $item['net_unit_price'], $item['unit_price'], ($combo_item->qty * $item['quantity']), $item['product_name'], $item['option_id'], $item_quantity);
                    }
                } else {
                    $cost = array(array('date' => date('Y-m-d'), 'product_id' => $item['product_id'], 'sale_item_id' => 'sale_items.id', 'purchase_item_id' => NULL, 'quantity' => $item['quantity'], 'purchase_net_unit_cost' => 0, 'purchase_unit_cost' => 0, 'sale_net_unit_price' => $item['net_unit_price'], 'sale_unit_price' => $item['unit_price'], 'quantity_balance' => NULL, 'inventory' => NULL));
                }
            } elseif ($item['product_type'] == 'manual') {
                $cost = array(array('date' => date('Y-m-d'), 'product_id' => $item['product_id'], 'sale_item_id' => 'sale_items.id', 'purchase_item_id' => NULL, 'quantity' => $item['quantity'], 'purchase_net_unit_cost' => 0, 'purchase_unit_cost' => 0, 'sale_net_unit_price' => $item['net_unit_price'], 'sale_unit_price' => $item['unit_price'], 'quantity_balance' => NULL, 'inventory' => NULL));
            }

        }
        return $cost;
    }

    public function costing($items) {
        $citems = array();
        foreach ($items as $item) {
            $pr = $this->getProductByID($item['product_id']);
            if ($pr->type == 'standard') {
                if (isset($citems['p' . $item['product_id'] . 'o' . $item['option_id']])) {
                    $citems['p' . $item['product_id'] . 'o' . $item['option_id']]['aquantity'] += $item['quantity'];
                } else {
                    $citems['p' . $item['product_id'] . 'o' . $item['option_id']] = $item;
                    $citems['p' . $item['product_id'] . 'o' . $item['option_id']]['aquantity'] = $item['quantity'];
                }
            } elseif ($pr->type == 'combo') {
                $combo_items = $this->getProductComboItems($item['product_id'], $item['warehouse_id']);
                foreach ($combo_items as $combo_item) {
                    if ($combo_item->type == 'standard') {
                        if (isset($citems['p' . $combo_item->id . 'o' . $item['option_id']])) {
                            $citems['p' . $combo_item->id . 'o' . $item['option_id']]['aquantity'] += ($combo_item->qty*$item['quantity']);
                        } else {
                            $cpr = $this->getProductByID($combo_item->id);
                            if ($cpr->tax_rate) {
                                $cpr_tax = $this->getTaxRateByID($cpr->tax_rate);
                                if ($cpr->tax_method) {
                                    $item_tax = $this->sma->formatDecimal((($combo_item->unit_price) * $cpr_tax->rate) / (100 + $cpr_tax->rate));
                                    $net_unit_price = $combo_item->unit_price - $item_tax;
                                    $unit_price = $combo_item->unit_price;
                                } else {
                                    $item_tax = $this->sma->formatDecimal((($combo_item->unit_price) * $cpr_tax->rate) / 100);
                                    $net_unit_price = $combo_item->unit_price;
                                    $unit_price = $combo_item->unit_price + $item_tax;
                                }
                            } else {
                                $net_unit_price = $combo_item->unit_price;
                                $unit_price = $combo_item->unit_price;
                            }
                            $cproduct = array('product_id' => $combo_item->id, 'product_name' => $cpr->name, 'product_type' => $combo_item->type, 'quantity' => ($combo_item->qty*$item['quantity']), 'net_unit_price' => $net_unit_price, 'unit_price' => $unit_price, 'warehouse_id' => $item['warehouse_id'], 'item_tax' => $item_tax, 'tax_rate_id' => $cpr->tax_rate, 'tax' => ($cpr_tax->type == 1 ? $cpr_tax->rate.'%' : $cpr_tax->rate), 'option_id' => NULL);
                            $citems['p' . $combo_item->id . 'o' . $item['option_id']] = $cproduct;
                            $citems['p' . $combo_item->id . 'o' . $item['option_id']]['aquantity'] = ($combo_item->qty*$item['quantity']);
                        }
                    }
                }
            }
        }
        // $this->sma->print_arrays($combo_items, $citems);
        $cost = array();
        foreach ($citems as $item) {
            $item['aquantity'] = $citems['p' . $item['product_id'] . 'o' . $item['option_id']]['aquantity'];
            $cost[] = $this->item_costing($item, TRUE);
        }
        return $cost;
    }

    public function syncQuantity($sale_id = NULL, $purchase_id = NULL, $oitems = NULL, $product_id = NULL) {
        if ($sale_id) {

            $sale_items = $this->getAllSaleItems($sale_id);
            foreach ($sale_items as $item) {
                if ($item->product_type == 'standard') {
                    $this->syncProductQty($item->product_id, $item->warehouse_id);
                    if (isset($item->option_id) && !empty($item->option_id)) {
                        $this->syncVariantQty($item->option_id, $item->warehouse_id, $item->product_id);
                    }
                } elseif ($item->product_type == 'combo') {
                    $combo_items = $this->getProductComboItems($item->product_id, $item->warehouse_id);
                    foreach ($combo_items as $combo_item) {
                        if($combo_item->type == 'standard') {
                            $this->syncProductQty($combo_item->id, $item->warehouse_id);
                        }
                    }
                }
            }

        } elseif ($purchase_id) {

            $purchase_items = $this->getAllPurchaseItems($purchase_id);
            foreach ($purchase_items as $item) {
                $this->syncProductQty($item->product_id, $item->warehouse_id);
                if (isset($item->option_id) && !empty($item->option_id)) {
                    $this->syncVariantQty($item->option_id, $item->warehouse_id, $item->product_id);
                }
            }

        } elseif ($oitems) {

            foreach ($oitems as $item) {
                if (isset($item->product_type)) {
                    if ($item->product_type == 'standard') {
                        $this->syncProductQty($item->product_id, $item->warehouse_id);
                        if (isset($item->option_id) && !empty($item->option_id)) {
                            $this->syncVariantQty($item->option_id, $item->warehouse_id, $item->product_id);
                        }
                    } elseif ($item->product_type == 'combo') {
                        $combo_items = $this->getProductComboItems($item->product_id, $item->warehouse_id);
                        foreach ($combo_items as $combo_item) {
                            if($combo_item->type == 'standard') {
                                $this->syncProductQty($combo_item->id, $item->warehouse_id);
                            }
                        }
                    }
                } else {
                    $this->syncProductQty($item->product_id, $item->warehouse_id);
                    if (isset($item->option_id) && !empty($item->option_id)) {
                        $this->syncVariantQty($item->option_id, $item->warehouse_id, $item->product_id);
                    }
                }
            }

        } elseif ($product_id) {
            $warehouses = $this->getAllWarehouses();
            foreach ($warehouses as $warehouse) {
                $this->syncProductQty($product_id, $warehouse->id);
                if ($product_variants = $this->getProductVariants($product_id)) {
                    foreach ($product_variants as $pv) {
                        $this->syncVariantQty($pv->id, $warehouse->id, $product_id);
                    }
                }
            }
        }
    }

   
    
    
    
    
    
    
    

    /*
     * ALTERA O CADASTRO DO USUÁRIO
     */
    
      public function updateMatriculaUser($id, $data  = array()) {
      
        if ($this->db->update('users', $data, array('id' => $id))) {
           
            return TRUE;
        }
        return FALSE;
    }
    
}
