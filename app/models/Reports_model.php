<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Reports_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    /****************** Retorna os Emails não enviado ************************/
    public function getAllEmailsPendentes() {
        
        $statement = "SELECT * FROM sig_emails where enviado = 0";
        $q = $this->db->query($statement);
      
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
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
    
    /**************************************************/
    
     public function getAllEventosStatusReport($projeto,$data_fim) {
        
        $q = $this->db->get_where('eventos', array('projeto' => $projeto, 'data_inicio <' => $data_fim));  
       
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
    
    
      /*******************EVENTOS SALVA NO STATUS REPORT*******************************/
    
     public function getAllEventoStatusReportByStatusReport($status_report) {
        //  echo $status_report; exit;
          $this->db->select("status_report_eventos.id as id_sr,  eventos.nome_evento as nome_evento, eventos.data_inicio as data_inicio, eventos.data_fim as data_fim, status_report_eventos.status as status")
            
          //  ->join('planos', 'status_report_acoes.acao = planos.idplanos', 'left')   
            ->join('eventos', 'status_report_eventos.evento = eventos.id', 'left');
        $q = $this->db->get_where('status_report_eventos', array('status_report' => $status_report));  
       
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
    
    
    /*******************AÇÕES CONCLUÍDAS SALVA NO STATUS REPORT*******************************/
    
     public function getAllAcoesByEventoStatusReport($evento) {
         
          $this->db->select("planos.descricao as descricao, planos.data_termino as data_termino, planos.data_retorno_usuario as data_retorno_usuario, status_report_acoes.status as status, users.first_name as first_name, users.last_name as last_name") // 
            
           ->join('planos', 'status_report_acoes.acao = planos.idplanos', 'left')
            ->join('users', 'planos.responsavel = users.id', 'left');
        $q = $this->db->get_where('status_report_acoes', array('evento' => $evento,  'status_report_acoes.status' => '1'));  
       
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
     /*******************AÇÕES PENDENTES SALVA NO STATUS REPORT*******************************/
    
     public function getAllAcoesPendentesByEventoStatusReport($evento) {
         
          $this->db->select("planos.descricao as descricao, planos.data_termino as data_termino, planos.data_retorno_usuario as data_retorno_usuario, status_report_acoes.status as status, users.first_name as first_name, users.last_name as last_name") // 
            
           ->join('planos', 'status_report_acoes.acao = planos.idplanos', 'left')
            ->join('users', 'planos.responsavel = users.id', 'left');
        $q = $this->db->get_where('status_report_acoes', array('evento' => $evento,  'status_report_acoes.status' => '0'));  
       
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
    
     /*************RETORNA TODOS OS PROJETOS ATIVOS DE TODAS AS EMPRESAS *************************************/
    // USADO NA ROTINA DE SALVAR O DESEMPENHO DE USUÁRIOS E SETORES
    /*
     * 
     */
    
       public function getAllProjetos() {
        $empresa = $this->session->userdata('empresa');
        $statement = "select p.id as projeto_id, p.projeto as projeto, e.id as empresa_projeto "
                . " from sig_projetos p "
                . " inner join sig_empresa e on e.id = p.empresa_id"
                . " where p.status = 'ATIVO' and e.status = 1 ";
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
     * PEGA TODOS OS EVENTOS DE UM PROJETO PELA FASE
     */
     public function getAllEventosProjetoByFase($fase)
    {
     $usuario = $this->session->userdata('user_id');
        
        $q = $this->db->get_where('eventos', array('fase_id' => $fase));
       
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
    
    /*
     * QUANTIDADE DE AÇÕES POR ITEM
     */
     public function getQuantidadeAcaoByItemEvento($item_evento)
    {
        $statement = "SELECT count(*) as qtde_acoes, sum(peso) as quantidade from sig_planos
                    where eventos = '$item_evento' and status not in ('ABERTO', 'CANCELADO')";
       // echo $statement;
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
        $statement = "SELECT count(*) as qtde_acoes, sum(peso) as quantidade from sig_planos
                    where eventos = '$eventos' and status = 'CONCLUÍDO' ";
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
        $statement = "SELECT count(*) as qtde_acoes, sum(peso) as quantidade from sig_planos
                    where eventos = '$eventos' and status = 'PENDENTE' and data_termino > '$date_hoje'";
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
        $statement = "SELECT count(*) as qtde_acoes, sum(peso) as quantidade from sig_planos
                    where eventos = '$eventos' and status = 'AGUARDANDO VALIDAÇÃO'";
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
        $statement = "SELECT count(*) as qtde_acoes, sum(peso) as quantidade from sig_planos
                    where eventoS = '$eventos' and status = 'PENDENTE' and data_termino < '$date_hoje' ";
        $q = $this->db->query($statement); 
        
         
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
    
    // retorna o total de ações dos itens por evento do escopo
    public function getQtdeAcoesPorItemByEvento($evento) {
      
        $statement = "SELECT sum(concluido) as concluido, sum(pendente) as pendente, sum(atrasado) as atrasado, sum(nao_iniciado) as nao_iniciado FROM sig_item_evento where evento = $evento ";
       // echo $statement; 
        $q = $this->db->query($statement);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
    // retorna o total de ações dos itens por evento do escopo
    public function getQtdeAcoesPorEventoByFase($fase) {
      
        $statement = "SELECT sum(concluido) as concluido, sum(pendente) as pendente, sum(atrasado) as atrasado, sum(nao_iniciado) as nao_iniciado FROM sig_eventos where fase_id = $fase ";
        //echo $statement; exit;
        $q = $this->db->query($statement);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
    
    
    
    /****************************************************************************/
    
    // RETORNA O RESUMO/ DESEMPENHO DE AÇÕES DO PROJETO
    
     public function getAllAcoesProjetosByEmpresaByProjeto($empresa, $projeto) {
        //$empresa = $this->session->userdata('empresa');
        $statement = "SELECT empresa, projeto, sum(peso) as total,
                    ( select sum(peso) as concluidas from sig_planos p where  p.projeto = $projeto and p.empresa = $empresa and status = 'CONCLUÍDO' ) as concluidas,
                    ( select sum(peso) as concluidas from sig_planos p where  p.projeto = $projeto and p.empresa = $empresa and status = 'PENDENTE' and p.data_termino >= now()  ) as pendentes,
                    ( select sum(peso) as concluidas from sig_planos p where  p.projeto = $projeto and p.empresa = $empresa and status = 'AGUARDANDO VALIDAÇÃO') as a_validacao,
                    ( select sum(peso) as concluidas from sig_planos p where  p.projeto = $projeto and p.empresa = $empresa and status = 'PENDENTE' and p.data_termino < now() ) as atrasada
                     FROM sig_planos p
                     where p.projeto = $projeto and p.empresa = $empresa and status in ('CONCLUÍDO', 'PENDENTE', 'AGUARDANDO VALIDAÇÃO')";
       // echo $statement.'<br>'; 
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
     * FAZ O INSERT DO DESEMPENHO DAS AÇÕES DE UM PROJETO
     */
       public function add_desempenho_acoes($historico_acoes)
    {
          
            if ($this->db->insert('desempenho_acoes', $historico_acoes)) {
                $this->db->insert_id();
                 
                return true;
        }
          
        return false;
    }
    
    
    
    
      
    /*
     * retorna todos os usuarios com acoes atrasadas de um projeto. 
     * objtivo é enviar emails para esses usuários.
     */
     public function usuariosComAcoesAtrasadas($id)
    {
      
        $date_hoje = date('Y-m-d H:i:s');
        $this->db->select('distinct(responsavel) as responsavel, users.first_name, users.email, ultimo_aviso_email')
            ->join('users', 'planos.responsavel = users.id', 'left')
            ->order_by('users.first_name', 'asc');
         $q = $this->db->get_where('planos', array('planos.projeto' => $id,'planos.status' => 'PENDENTE','planos.data_termino <' => $date_hoje));
         
         
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
    
     /*
     * CONTA QUANTAS AÇÕES O USUÁRIO TEM ATRASADO POR PROJETO (de todas as empresas)
     */
     public function getContAcoesAtrasadasByUser($projeto,  $usuario)
    {
        
         $date_hoje = date('Y-m-d');
         
         $statement = "SELECT count(*) as quantidade
                     FROM sig_planos p
                     where p.projeto = $projeto and p.responsavel = $usuario and status = 'PENDENTE' and p.data_termino <  '$date_hoje' ";
        //echo $statement; exit;
        $q = $this->db->query($statement);
         
        
         
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
    /**************************************************************************/
    
    /*
     * PEGA TODOS OS PLANOS DE UM PROJETO e por usuario
     */
     public function getAllPlanosAtrasadosProjetobyUser($projeto,  $usuario)
    {
          $date_hoje = date('Y-m-d');
         
         $statement = "SELECT *
                     FROM sig_planos p
                     where p.projeto = $projeto and p.responsavel = $usuario and status = 'PENDENTE' and p.data_termino <  '$date_hoje' ";
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
    
    /**************************************************************************/
    
    
     /*
     * PEGA TODOS OS SETORES DE TODAS AS EMPRESAS QUE TEM AÇÃO EM PROJETO PARA GERAR O DESEMPENHO
     usado em Reports -> Resumo por setor
     */
    public function getAllSetores($empresa, $projeto)
    {
        $statement = "SELECT distinct s.id as setor_id, s.nome as nome_setor, p.empresa, p.projeto
                     FROM sig_setores s
                     inner join sig_planos p on p.setor = s.id
                     where p.projeto = $projeto and p.empresa = $empresa ";
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
    
    
    /*****************************************************************
     * PEGA TODOS OS PLANOS DE UM PROJETO e de um setor
     ****************************************************************/
     public function getAllAcoesByProjetoAndSetor($projeto, $setor)
    {
         $statement = "select * from sig_planos where setor = $setor and projeto = $projeto ";
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
    
    
    /********************************************************/
      public function getAllProjetosComGestores() {
          $this->db->select("projetos.id as id")
           ->join('users_gestor', 'projetos.id = users_gestor.projeto', 'inner')
         ->group_by('id');        
        $q = $this->db->get('projetos');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
    
    /***************************************************/
    
         public function addStatusReport($data)
    {
           // echo 'aqui'; exit;
            
            if ($this->db->insert('status_report', $data)) {
                $id_status = $this->db->insert_id();
               return $id_status;
        }
          
        return false;
    }

   
    /**************************************************/
    
    /*
     * PEGA UM STATUS REPORT PELO ID
     */
     public function getStatusReportByID($id)
    {
         
        $q = $this->db->get_where('status_report', array('id' => $id), 1);
     
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
         
    }
    
    /**************************************************/
    
    /*
     * VERIFICA SE TEM AÇÃO PENDENTE PARA UM DETERMINADO EVENTO
     */
     public function getAcaoPendenteByEventoID($id)
    {
          $this->db->select("count(idplanos) as quantidade");
        $q = $this->db->get_where('planos', array('eventos' => $id, 'status' => 'PENDENTE'), 1);
     
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
         
    }
    
    /***************************************************/
    //SALVA OS EVENTOS RELACIONADO COM O STATUS REPORT
         public function addEventoStatusReport($data)
    {
           // echo 'aqui'; exit;
            
            if ($this->db->insert('status_report_eventos', $data)) {
                $id_evento = $this->db->insert_id();
               return $id_evento;
               //return true;
        }
          
        return false;
    }
    
    /***************************************************/
    //SALVA AS AÇÕES DO STATUS REPORT
         public function addAcoesStatusReport($data)
    {
           // echo 'aqui'; exit;
            
            if ($this->db->insert('status_report_acoes', $data)) {
                
               return true;
        }
          
        return false;
    }
    
    
     /**************************************************/
    
     public function getAllAcoesByEvento($evento,$data_fim) {
          
          $this->db->select("*")
            ->join('users', 'planos.responsavel = users.id', 'left');
        
        $q = $this->db->get_where('planos', array('eventos' => $evento, 'data_termino  <' => $data_fim, 'status' => 'CONCLUÍDO'));  
       
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
    /**************************************************/
    
     public function getAllAcoesPendenteByEvento($evento,$data_fim) {
         
          $this->db->select("*")
            ->join('users', 'planos.responsavel = users.id', 'left');
        
        $q = $this->db->get_where('planos', array('eventos' => $evento, 'data_termino  <' => $data_fim, 'status !=' => 'CONCLUÍDO'));  
      
       
       
       
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
     /**************************************************/
    
    public function getAllStatusReportByProjeto($projeto) {
         
        
          $this->db->select("status_report.id as id, status_report.periodo_de as periodo_de, status_report.periodo_ate as periodo_ate, status_report.prazo,status_report.custo as custo, status_report.escopo as escopo, status_report.comunicacao as comunicacao, projetos.projeto as projeto")
            ->join('projetos', 'status_report.projeto = projetos.id', 'left')
          ->order_by('status_report.id', 'desc');
        $q = $this->db->get_where('status_report', array('status_report.projeto' => $projeto));  
   
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
    
    
         /**************************************************/
    
    public function getAllStatusReportByDataRegistro($data_registro) {
         
        
          $this->db->select("status_report.id as id, status_report.periodo_de as periodo_de, status_report.periodo_ate as periodo_ate, status_report.prazo,status_report.custo as custo, status_report.escopo as escopo, status_report.comunicacao as comunicacao, projetos.projeto as projeto")
            ->join('projetos', 'status_report.projeto = projetos.id', 'left')
          ->order_by('status_report.id', 'desc');
        $q = $this->db->get_where('status_report', array('status_report.data_registro' => $data_registro));  
   
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
         /**************************************************/
    
    public function getAllStatusReportByDataProjeto($projeto,$data_registro) {
         
        
          $this->db->select("count(id) as quantidade");
         //   ->join('projetos', 'status_report.projeto = projetos.id', 'left');
         
        $q = $this->db->get_where('status_report', array('status_report.projeto' => $projeto, 'status_report.data_registro' => $data_registro));  
   
       if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    // public function getmonthlyPurchases()
    // {
    //     $myQuery = "SELECT (CASE WHEN date_format( date, '%b' ) Is Null THEN 0 ELSE date_format( date, '%b' ) END) as month, SUM( COALESCE( total, 0 ) ) AS purchases FROM purchases WHERE date >= date_sub( now( ) , INTERVAL 12 MONTH ) GROUP BY date_format( date, '%b' ) ORDER BY date_format( date, '%m' ) ASC";
    //     $q = $this->db->query($myQuery);
    //     if ($q->num_rows() > 0) {
    //         foreach (($q->result()) as $row) {
    //             $data[] = $row;
    //         }
    //         return $data;
    //     }
    //     return FALSE;
    // }

    

    

   

    public function getExpenses($date, $warehouse_id = NULL, $year = NULL, $month = NULL)
    {
        $sdate = $date.' 00:00:00';
        $edate = $date.' 23:59:59';
        $this->db->select('SUM( COALESCE( amount, 0 ) ) AS total', FALSE);
        if ($date) {
            $this->db->where('date >=', $sdate)->where('date <=', $edate);
        } elseif ($month) {
            $this->load->helper('date');
            $last_day = days_in_month($month, $year);
            $this->db->where('date >=', $year.'-'.$month.'-01 00:00:00');
            $this->db->where('date <=', $year.'-'.$month.'-'.$last_day.' 23:59:59');
        }
        

        if ($warehouse_id) {
            $this->db->where('warehouse_id', $warehouse_id);
        }

        $q = $this->db->get('expenses');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }

  /*
   * MODELO DE QUERY
   */
    public function getDailyPurchases($year, $month, $warehouse_id = NULL)
    {
        $myQuery = "SELECT DATE_FORMAT( date,  '%e' ) AS date, SUM( COALESCE( product_tax, 0 ) ) AS tax1, SUM( COALESCE( order_tax, 0 ) ) AS tax2, SUM( COALESCE( grand_total, 0 ) ) AS total, SUM( COALESCE( total_discount, 0 ) ) AS discount, SUM( COALESCE( shipping, 0 ) ) AS shipping
            FROM " . $this->db->dbprefix('purchases') . " WHERE ";
        if ($warehouse_id) {
            $myQuery .= " warehouse_id = {$warehouse_id} AND ";
        }
        $myQuery .= " DATE_FORMAT( date,  '%Y-%m' ) =  '{$year}-{$month}'
            GROUP BY DATE_FORMAT( date,  '%e' )";
        $q = $this->db->query($myQuery, false);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
    
    /*
     * LISTA DE GESTORES POR PROJETO
     */
    public function getListGestoresProjetos($projeto)
    {
           $this->db->select("users_gestor.users as user_id")
           // ->join('users', 'users_gestor.users = users.id', 'left')
           // ->join('setores', 'users_gestor.setor = setores.id', 'left')
           // ->join('perfil_usuario', 'users_gestor.users = perfil_usuario.user_id ', 'left')     
            ->group_by('user_id');
         $q = $this->db->get_where('users_gestor', array('users_gestor.projeto' => $projeto));
       
       if ($q->num_rows() > 0) {
             foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
         
    }
    /*
     * SOBREAVISO
     */

     public function getListSobreaviso($escala)
    {
        $dia = date('d');
        $mes = date('m');
        $ano = date('Y');
        $hora = date('H:i:s'); 
         
        //$this->db->select("*")
        //$this->db->where('hora_inicio <=', $hora)
        //$this->db->where('hora_fim >=', $hora)        
        $q = $this->db->get_where('escala_detalhes', array('dia' => $dia, 'mes' => $mes, 'ano' => $ano, 'escala_id' => $escala, 'hora_inicio <=' => $hora, 'hora_fim >=' => $hora));
       
       if ($q->num_rows() > 0) {
             foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
         
    }
    
    
    
      public function getProjetoByID($id)
    {
        $q = $this->db->get_where('projetos', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
    
     /**********************ORDEM SERVIÇO****************************/
    
     public function getAllOrdemServicos() {
        
         // ADICIONE ISTO ABAIXO
        $glpi = $this->load->database('glpi', TRUE);
        $this->db2 = $db2;
       
        
       $glpi->select("glpi_tickets.id as id,glpi_tickets.*, glpi_tickets_users.users_id as users_id, glpi_tickets_users.type as type, glpi_users.name as matricula, glpi_useremails.email as email")
            ->join('glpi_tickets_users', 'glpi_tickets.id = glpi_tickets_users.tickets_id', 'inner')
            ->join('glpi_users', 'glpi_tickets_users.users_id = glpi_users.id', 'inner')
            ->join('glpi_useremails', 'glpi_users.id = glpi_useremails.users_id ', 'inner');     
            //->group_by('user_id');
        
         $q = $glpi->get_where('glpi_tickets', array('date >= ' => '2018-06-01', 'glpi_tickets_users.type ' => '2'));
         
       // $q = $glpi->get('glpi_tickets'); 
      //  $glpi->where('date >= 2018-08-01');
        
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
    /**********************ORDEM SERVIÇO DISTINTA****************************/
    
     public function getDistinctAllOrdemServicos() {
        
         // ADICIONE ISTO ABAIXO
        $glpi = $this->load->database('glpi', TRUE);
        $this->db2 = $db2;
       
        
       $glpi->select("glpi_tickets_users.users_id as id_user")
            ->join('glpi_tickets', 'glpi_tickets_users.tickets_id = glpi_tickets.id', 'inner')
              ->distinct();
        
         $q = $glpi->get_where('glpi_tickets_users', array('glpi_tickets.date >= ' => '2018-06-01', 'glpi_tickets_users.type ' => '1'));
         
       // $q = $glpi->get('glpi_tickets'); 
      //  $glpi->where('date >= 2018-08-01');
        
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
    /**********************ORDEM SERVIÇO SEM CHAMADO****************************/
     public function getAllOrdemServicosSemAceite() {
        
         // ADICIONE ISTO ABAIXO
        $glpi = $this->load->database('glpi', TRUE);
        $this->db2 = $db2;
       
        
       $glpi->select("*");
           // ->join('glpi_tickets_users', 'glpi_tickets.id = glpi_tickets_users.tickets_id', 'inner')
           // ->join('glpi_users', 'glpi_tickets_users.users_id = glpi_users.id', 'inner')
          //  ->join('glpi_useremails', 'glpi_users.id = glpi_useremails.users_id ', 'inner');     
            //->group_by('user_id');
        
         $q = $glpi->get_where('glpi_tickets', array('date >= ' => '2018-06-01', 'status' => '2'));
         
       // $q = $glpi->get('glpi_tickets'); 
      //  $glpi->where('date >= 2018-08-01');
       
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
    /**********************ORDEM SERVIÇO SEM CHAMADO****************************/
     public function getAllCategoriasAtivas() {
        
         // ADICIONE ISTO ABAIXO
        $glpi = $this->load->database('glpi', TRUE);
        $this->db2 = $db2;
       
        
       $glpi->select("*");
           // ->join('glpi_tickets_users', 'glpi_tickets.id = glpi_tickets_users.tickets_id', 'inner')
           // ->join('glpi_users', 'glpi_tickets_users.users_id = glpi_users.id', 'inner')
          //  ->join('glpi_useremails', 'glpi_users.id = glpi_useremails.users_id ', 'inner');     
            //->group_by('user_id');
        
         $q = $glpi->get_where('glpi_itilcategories', array('is_helpdeskvisible' => '1'));
         
       
        
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
    /**********************ORDEM SERVIÇO SEM CHAMADO****************************/
     public function getAllTicketByCategoriaAndStatus($categoria, $status) {
        
         // ADICIONE ISTO ABAIXO
        $glpi = $this->load->database('glpi', TRUE);
        $this->db2 = $db2;
       
        
       $glpi->select("count(*) as quantidade");
           // ->join('glpi_tickets_users', 'glpi_tickets.id = glpi_tickets_users.tickets_id', 'inner')
           // ->join('glpi_users', 'glpi_tickets_users.users_id = glpi_users.id', 'inner')
          //  ->join('glpi_useremails', 'glpi_users.id = glpi_useremails.users_id ', 'inner');     
            //->group_by('user_id');
        
         $q = $glpi->get_where('glpi_tickets', array('date >= ' => '2018-06-01', 'status' => $status, 'itilcategories_id' => $categoria));
         
       
        
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
    
      /**********************retorna o usuário que abriu o chamado****************************/
    
     public function getAllSolicitanteOrdemServicosSolucionado($ticket) {
        
         // ADICIONE ISTO ABAIXO
        $glpi = $this->load->database('glpi', TRUE);
        $this->db2 = $db2;
       
        
       $glpi->select("glpi_tickets_users.users_id as user_id, glpi_users.name as matricula, glpi_users.firstname as firstname, glpi_users.realname as realname, glpi_useremails.email as email")
            ->join('glpi_users', 'glpi_tickets_users.users_id = glpi_users.id', 'inner')
            ->join('glpi_useremails', 'glpi_users.id = glpi_useremails.users_id ', 'left');     
            //->group_by('user_id');
        
         $q = $glpi->get_where('glpi_tickets_users', array('tickets_id' => $ticket, 'glpi_tickets_users.type ' => '1'));
        
       // $q = $glpi->get('glpi_tickets'); 
      //  $glpi->where('date >= 2018-08-01');
        
         if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
    
     public function getTicketsByUser($id_user) {
        
         // ADICIONE ISTO ABAIXO
        $glpi = $this->load->database('glpi', TRUE);
        $this->db2 = $db2;
       
         $glpi->select("count(glpi_tickets.id) as quantidade")
              ->join('glpi_tickets_users', 'glpi_tickets.id = glpi_tickets_users.tickets_id', 'inner');
          
            //->group_by('user_id');
        
         $q = $glpi->get_where('glpi_tickets', array('glpi_tickets.date >=' => '2018-06-01', 'glpi_tickets_users.type' => '1', 'glpi_tickets_users.users_id' => $id_user));
        
        // $q = $glpi->get('glpi_tickets'); 
        //  $glpi->where('date >= 2018-08-01');
        
         if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
    public function getUseremailGlpiById($id_user) {
        
         // ADICIONE ISTO ABAIXO
        $glpi = $this->load->database('glpi', TRUE);
        $this->db2 = $db2;
       
        $glpi->select("*")
        ->join('glpi_users', 'glpi_useremails.users_id = glpi_users.id', 'inner');
       
        $q = $glpi->get_where('glpi_useremails', array('users_id' => $id_user));
        
          
         if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
      
     public function getPlanoByIdTicket($id, $responsavel)
    {
        
        $q = $this->db->get_where('planos', array('id_ticket' => $id, 'responsavel' => $responsavel), 1);
       
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
         
    }
    
     public function getEventoByIdGlpi($id)
    {
        
        $q = $this->db->get_where('item_evento', array('id_gpli' => $id), 1);
       
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
         
    }
    
    public function updatePlano($id, $data  = array())
    {  
        
        if ($this->db->update('planos', $data, array('idplanos' => $id))) {
            
                       
         return true;
        }
        return false;
    }
    
    public function updateUser($id, $data  = array())
    {  
        
        if ($this->db->update('users', $data, array('id' => $id))) {
            
                       
         return true;
        }
        return false;
    }
    
     public function getUserbyemail($email = NULL) {
       
        $q = $this->db->get_where('users', array('email' => $email), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
        public function addUser($data)
    {
            if ($this->db->insert('users', $data)) {
                 $id_ata = $this->db->insert_id();
                 
               return $id_ata;
        }
          
        return false;
    }
    
    
    /*
     * PERÍODO HORA EXTRA
     */
    
    public function getPeriodoByCompetencia($mes, $ano, $usuario) {
        
        
       $this->db->select('count(*) as quantidade');     
       $q = $this->db->get_where('periodo_he', array('mes' => $mes, 'ano' => $ano, 'user_id' => $usuario), 1);  
       
        
         if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
    
        public function addPeriodo_he($data)
    {
            if ($this->db->insert('periodo_he', $data)) {
               $id = $this->db->insert_id();
                 
               return $id;
        }
          
        return false;
    }

    
        public function addPeriodo_he_detalhes($data)
    {
            if ($this->db->insert('periodo_he_registros', $data)) {
                 $this->db->insert_id();
                 
               return true;
        }
          
        return false;
    }
    
    /*
     * 
     */
    public function updateDadosHoraUsuario($id, $data  = array())
    {  
        
        if ($this->db->update('periodo_he_registros', $data, array('id' => $id))) {
            
                       
         return true;
        }
        return false;
    }
    
    
     public function getCPFByUser($user) {
       
        $q = $this->db->get_where('user_horario', array('user' => $user), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
     /***************************************************/
    
         public function addAcaoJustificativaHora($data)
    {
           // echo 'aqui'; exit;
            
            if ($this->db->insert('periodo_he_acoes', $data)) {
                $id_status = $this->db->insert_id();
               return $id_status;
        }
          
        return false;
    }
    
    // Atualiza a quantidade de ações de acordo com o status no item do escopo
     public function updateAcoesItemEscopo($id, $data  = array())
    {  
        if ($this->db->update('item_evento', $data, array('id' => $id))) {
         return true;
        }
        return false;
    }
    
    // Atualiza a quantidade de ações de acordo com o status no evento do escopo
     public function updateAcoesEventoEscopo($id, $data  = array())
    {  
        if ($this->db->update('eventos', $data, array('id' => $id))) {
         return true;
        }
        return false;
    }
    
    // Atualiza a quantidade de ações de acordo com o status na fase do escopo
     public function updateAcoesFaseEscopo($id, $data  = array())
    {  
        if ($this->db->update('fases_projeto', $data, array('id' => $id))) {
         return true;
        }
        return false;
    }
    
}
