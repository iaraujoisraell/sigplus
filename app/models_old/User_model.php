<?php defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    
    /**************************************************/
    
      public function getAllEquipeByUser($user) {
        
         $this->db->select("projetos.id as id_projeto, projetos.projeto as projeto")
            ->join('users_setores', 'membros_equipe.usuario = users_setores.id', 'inner')
            ->join('equipes', 'membros_equipe.equipe = equipes.id', 'inner')
             ->join('projetos', 'equipes.projeto = projetos.id', 'inner');
         
        $q = $this->db->get_where('membros_equipe', array('users_setores.usuario' => $user, 'projetos.status' => 'ATIVO'));  
       
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
    
     public function getAllEquipeByUserDistinct($user) {
        
          $this->db->select("projetos.id as id_projeto, projetos.projeto as projeto")
            ->join('users_setores', 'membros_equipe.usuario = users_setores.id', 'inner')
            ->join('equipes', 'membros_equipe.equipe = equipes.id', 'inner')
             ->join('projetos', 'equipes.projeto = projetos.id', 'inner')
            //->group_by('projetos.id')     
            ->distinct();
         
        $q = $this->db->get_where('membros_equipe', array('users_setores.usuario' => $user, 'projetos.status' => 'ATIVO'));  
       
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
    public function getAllPostagemByEquipeDistinct($user) {
        
          $this->db->select("projetos.id as id_projeto, projetos.projeto as projeto, post.id as id_post, post.tipo as tipo, post.anexo as anexo, post.titulo as titulo, post.descricao as descricao, post.data_postagem as data_postagem, post.user_id as user_id ")
            ->join('users_setores', 'membros_equipe.usuario = users_setores.id', 'inner')
            ->join('equipes', 'membros_equipe.equipe = equipes.id', 'inner')
            ->join('projetos', 'equipes.projeto = projetos.id', 'inner')
            ->join('post', 'projetos.id = post.projeto', 'inner')      
            //->group_by('projetos.id')     
            ->distinct()
         ->order_by('post.data_postagem', 'DESC');
        $q = $this->db->get_where('membros_equipe', array('users_setores.usuario' => $user, 'projetos.status' => 'ATIVO'));  
       
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
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
     * GRUPO DO DOCUMENTO LIBERADO PARA O USUÁRIO
     */
     public function getAllGrupoDocumentoByUsuario($projeto, $usuario)
    {
        
         $this->db->select("distinct(grupo_documento) as grupo")
          ->join('documentos_usuarios', 'documentos.id = documentos_usuarios.documento', 'inner')        
          ->join('users_setores', 'documentos_usuarios.usuario_setor = users_setores.id', 'inner')         
          ->join('users', 'users_setores.usuario = users.id', 'inner');
          
         $q = $this->db->get_where('documentos', array('projeto' => $projeto, 'users.id' => $usuario));
        
        
       if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
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
    
    
    public function getAgendaProjetos($projeto)
    {
        $date_hoje = date('Y-m-d H:i:s');
        $prazo =  date('Y-m-d', strtotime("0 days",strtotime($date_hoje))); 
        
        $prazo_max =  date('Y-m-d', strtotime("+10 days",strtotime($date_hoje))); 
        
        $ordem = 'asc';
        $campo = 'start';
        
         $this->db->select('*')
         ->order_by($campo, $ordem);
      
      
         $q = $this->db->get_where('calendar', array('projeto' => $projeto, 'start >=' => $prazo, 'start <=' => $prazo_max));
        
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
     public function getPeriodoHEByUsuario($usuario)
    {
       
       $this->db->select("*")
       ->distinct()
       ->order_by('id', 'desc');
       $q = $this->db->get_where('periodo_he', array('user_id' => $usuario));  
       
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
     public function getPeriodoHEByAnoAndMes($mes, $ano)
    {
       
       $q = $this->db->get_where('periodo_he', array('mes' => $mes, 'ano' => $ano));  //'mes' => $mes, 'ano' => $ano
       
      if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
     public function getPeriodoHEById($id)
    {
       
      
       $q = $this->db->get_where('periodo_he', array('id' => $id));  
       
      if ($q->num_rows() > 0) {
            
            return $q->row();
        }
        return FALSE;
    }
    
    
      public function getPeriodo_detalhesHEById($id)
    {
       
      
       $q = $this->db->get_where('periodo_he_registros', array('id' => $id));  
       
      if ($q->num_rows() > 0) {
            
            return $q->row();
        }
        return FALSE;
    }
    
     public function getDetalhesPeriodoHEByIdPeriodo($id_periodo)
    {
         
           $this->db->select("*")
      // 
        ->order_by('mes', 'ASC')
        ->order_by('dia', 'ASC');        
       $q = $this->db->get_where('periodo_he_registros', array('id_periodo' => $id_periodo));  
       
      if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
     public function getProjetoResumoHoras($id_periodo)
    {
         
      $statement = "SELECT DISTINCT pj.id as id_projeto, pj.projeto as projeto
                    FROM  `sma_periodo_he_acoes` a
                    INNER JOIN  `sma_periodo_he_registros` r ON r.id = a.id_periodo_registro
                    INNER JOIN  `sma_planos` p ON p.idplanos = a.id_acao
                    INNER JOIN  `sma_item_evento` i ON i.id = p.eventos
                    INNER JOIN  `sma_eventos` e ON e.id = i.evento
                    INNER JOIN  `sma_projetos` pj ON pj.id = e.projeto
                    WHERE r.id_periodo = $id_periodo
                    AND a.`id_acao` IS NOT NULL 
                    AND a.id_acao !=0";
        $q = $this->db->query($statement);    
        
      if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
    public function getEventosProjetoResumoHoras($id_periodo, $id_projeto)
    {
         
      $statement = "SELECT DISTINCT  e.id as id_evento, e.nome_evento as nome_evento, e.tipo as tipo
                    FROM  `sma_periodo_he_acoes` a
                    INNER JOIN  `sma_periodo_he_registros` r ON r.id = a.id_periodo_registro
                    INNER JOIN  `sma_planos` p ON p.idplanos = a.id_acao
                    INNER JOIN  `sma_item_evento` i ON i.id = p.eventos
                    INNER JOIN  `sma_eventos` e ON e.id = i.evento
                    INNER JOIN  `sma_projetos` pj ON pj.id = e.projeto
                    WHERE pj.id = $id_projeto and r.id_periodo = $id_periodo
                    AND a.`id_acao` IS NOT NULL 
                    AND a.id_acao !=0";
        $q = $this->db->query($statement);    
        
      if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
    public function getItemEventosProjetoResumoHoras($id_periodo, $id_projeto, $id_evento)
    {
         
      $statement = "SELECT DISTINCT  i.id as id_item_evento, i.descricao as item_evento
                    FROM  `sma_periodo_he_acoes` a
                    INNER JOIN  `sma_periodo_he_registros` r ON r.id = a.id_periodo_registro
                    INNER JOIN  `sma_planos` p ON p.idplanos = a.id_acao
                    INNER JOIN  `sma_item_evento` i ON i.id = p.eventos
                    INNER JOIN  `sma_eventos` e ON e.id = i.evento
                    INNER JOIN  `sma_projetos` pj ON pj.id = e.projeto
                    WHERE pj.id = $id_projeto and r.id_periodo = $id_periodo and e.id = $id_evento
                    AND a.`id_acao` IS NOT NULL 
                    AND a.id_acao !=0";
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
    
     public function getItemEventosAcoesProjetoResumoHorasDistinct($id_periodo, $id_projeto, $id_evento, $id_evento_item)
    {
         
      $statement = "SELECT DISTINCT p.descricao as acao, p.idplanos as id_acao, i.id as id_item, a.id_periodo_registro as id_periodo_registro
                    FROM  `sma_periodo_he_acoes` a
                    INNER JOIN  `sma_periodo_he_registros` r ON r.id = a.id_periodo_registro
                    INNER JOIN  `sma_planos` p ON p.idplanos = a.id_acao
                    INNER JOIN  `sma_item_evento` i ON i.id = p.eventos
                    INNER JOIN  `sma_eventos` e ON e.id = i.evento
                    INNER JOIN  `sma_projetos` pj ON pj.id = e.projeto
                    WHERE pj.id = $id_projeto and r.id_periodo = $id_periodo and e.id = $id_evento and i.id = $id_evento_item 
                    AND a.`id_acao` IS NOT NULL 
                    AND a.id_acao !=0
                     ";
        $q = $this->db->query($statement);    
        
      if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
    public function getItemEventosAcoesProjetoResumoHoras($id_periodo,  $id_evento_item)
    {
         
      $statement = "SELECT DISTINCT p.descricao as acao, p.idplanos as id_acao
                    FROM  `sma_periodo_he_acoes` a
                    INNER JOIN  `sma_periodo_he_registros` r ON r.id = a.id_periodo_registro
                    INNER JOIN  `sma_planos` p ON p.idplanos = a.id_acao
                  
                    WHERE r.id_periodo = $id_periodo  and p.eventos = $id_evento_item 
                    AND a.`id_acao` IS NOT NULL 
                    AND a.id_acao !=0
                     ";
        $q = $this->db->query($statement);    
       // echo $statement;
      if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
    public function getItemEventosAcoesProjetoResumoHorasSaldo($id_periodo,  $id_acao)
    {
         
      $statement = "SELECT r.id as id_registro, r.saldo as saldo
                    FROM  `sma_periodo_he_acoes` a
                    INNER JOIN  `sma_periodo_he_registros` r ON r.id = a.id_periodo_registro
                    INNER JOIN  `sma_planos` p ON p.idplanos = a.id_acao
                  
                    WHERE r.id_periodo = $id_periodo  and p.idplanos = $id_acao 
                    AND a.`id_acao` IS NOT NULL 
                    AND a.id_acao !=0";
        $q = $this->db->query($statement);    
       // echo $statement;
      if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
    
     public function getQuantidadeAcoesProjetoResumoHorasSaldo($id_periodo)
    {
         
      $statement = "SELECT count(*) quantidade
                    FROM  `sma_periodo_he_acoes`                   
                    WHERE id_periodo_registro = $id_periodo ";
        $q = $this->db->query($statement);    
       // echo $statement;
      if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
    public function getAcoesByItemEventosAcoesProjetoResumoHoras($id_periodo,  $id_acao)
    {
         
      $statement = "SELECT pa.id_acao as id_acao, saldo
                    FROM `sma_periodo_he_acoes` pa
                    inner join sma_periodo_he_registros r on r.id = pa.id_periodo_registro
                    where pa.id_acao = $id_acao and `id_periodo_registro` = $id_periodo";
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
    
    
     public function getTempoAcoesByRegistros($hora,  $quantidade)
    {
         
      $statement = "SELECT SEC_TO_TIME( TIME_TO_SEC(  '$hora' ) / $quantidade ) as tempo";
    //  echo $statement; exit;
        $q = $this->db->query($statement);    
        
      if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
    public function get_total_registro_data($periodo, $mes, $dia) {
        
         $this->db->select('count(*) as quantidade')
         ->where('dia', $dia)
                ->where('mes', $mes);
         $q = $this->db->get_where('periodo_he_registros', array('id_periodo' => $periodo), 1);
        
         if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
    public function updateRegistrosHE($id, $data  = array())
    {  
     if ($this->db->update('periodo_he_registros', $data, array('id' => $id))) {
        return true;
        }
        return false;
    }
    
    public function deleteAcoesRegistrosHE($id, $data_acoes)
    {  
            
          $this->db->delete('periodo_he_acoes', array('id_periodo_registro' => $id));
          foreach ($data_acoes as $item) {
                    $data_ata_usuario = array('id_acao' => $item,
                                              'id_periodo_registro' => $id);      
                   print_r($data_ata_usuario);
                         if ($this->db->insert('periodo_he_acoes', $data_ata_usuario)) {
                                $id_status = $this->db->insert_id();
                              
                        }
                 }
                  
                 return true;
    }
    
      
     public function deleteRegistrosHE($id)
    {  
     if($id){
      $this->db->delete('periodo_he_registros', array('id' => $id));
        return true;
        }
        return false;
    }
    
    
    public function addRegistrosHE($data  = array())
    {  
     if ($this->db->insert('periodo_he_registros', $data)) {
           
         return true;
        }
        return false;
    }

    
     public function getAcoesPeriodoHEById($id)
    {
        
         
       $q = $this->db->get_where('periodo_he_acoes', array('id_periodo_registro' => $id));  
       
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
     public function getAllPlanosRequisicaoHora($id)
    {
       
         $q = $this->db->get_where('periodo_he_acoes', array('id_periodo_registro' => $id));
         
         
         
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
}
