<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Financeiro_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
    }
    
    
     /********************************************************
     ************* CONTAS A PAGAR  ******************
     *****************************************************/
    public function add_edit_conta_pagar($data_titulo)
    {
       
        $empresa_id = $this->session->userdata('empresa_id');
        $hoje = date('Y-m-d H:i:s');
        $id_registro = $data_titulo['id'];
        
        if($data_titulo['id']){
            $data_titulo['user_ultima_alteracao']   = get_staff_user_id();
            $data_titulo['data_ultima_alteracao']   = $hoje;
            $valor = $data_titulo['valor'];
            $valor = str_replace(',','.', $valor);
            $data_titulo['valor'] = $valor;
            
            
            if($data_titulo['data_pagamento']){
                $data_titulo['status'] = 1;
            }
            
            
        }else{
            if(!$data_titulo['parcela']){
                $total_parcelas = 1;
            }else{
                $total_parcelas = $data_titulo['parcela'];
            }
            
            $data_titulo['tipo'] = 1;
            $data_titulo['total_parcela'] = $total_parcelas;
            $data_titulo['user_cadastro']   = get_staff_user_id();
            $data_titulo['empresa_id']   = $empresa_id;
            $data_titulo['data_cadastro']   = $hoje;
            $data_titulo['user_ultima_alteracao']   = get_staff_user_id();
            $data_titulo['data_ultima_alteracao']   = $hoje;
            
            
            $valor = $data_titulo['valor'];
            $valor = str_replace('.','', $valor);
            $valor = str_replace(',','.', $valor);
            $data_titulo['valor'] = $valor;
        }
        
       
       
        $inicio_parcela = $data_titulo['inicio_parcela'];
        if(!$inicio_parcela){
            $inicio_parcela = 1;
        }
       // unset($data_titulo['inicio_parcela']);
        unset($data_titulo['id']);
                
       // print_r($data_titulo); exit;
           
        if(!$id_registro){
            $date_primeira_parce = $data_titulo['data_vencimento'];
            $primeira_parcela_data_br = date('d/m/Y', strtotime($date_primeira_parce));
            
            /*
             * registra o título
             */
            
            $this->db->insert(db_prefix() . 'fin_lancamentos', $data_titulo);
            $insert_id = $this->db->insert_id();
            //echo $insert_id; exit;
            log_activity('TITULO CONTA PAGAR created [ ID : '.$insert_id.']', null, $data_titulo);
            
            //echo 'aqui'; exit;
            // parcelas
           
            $this->geraParcelas_conta_pagar($total_parcelas, $primeira_parcela_data_br, $data_titulo, $insert_id, $inicio_parcela);
            
             return $insert_id;
            
        }else{
            
            
            $this->db->where('id', $id_registro);
            $this->db->update(db_prefix() . 'fin_lancamentos', $data_titulo);
            log_activity('CONTA PAGAR updated [ ID : '.$id_registro.']', null, $data_titulo);
        }
        
                
       
            hooks()->do_action('fin_lancamentos', $id_registro, $data_titulo);
            return true;
        

        return false;
    }
    
    
    function geraParcelas_conta_pagar($nParcelas, $dataPrimeiraParcela = null, $data= null, $insert_id, $inicio_parcela){
      if($dataPrimeiraParcela != null){
        $dataPrimeiraParcela = explode( "/",$dataPrimeiraParcela);
            $dia = $dataPrimeiraParcela[0];
            $mes = $dataPrimeiraParcela[1];
            $ano = $dataPrimeiraParcela[2];
      } else {
        $dia = date("d");
        $mes = date("m");
        $ano = date("Y");
      }
      $data_p_parc = "$ano-$mes-$dia";
      
      $data_parcela = array();
     
      // CALCULA VALOR DA PARCELA
      $valor_parcela_contrato = $data['valor_parcela_contrato'];
      $valor_titulo  = $data['valor'];
      if($valor_parcela_contrato == 1){
          $data_parcela['valor_parcela'] = $valor_titulo;
          $data_parcela['subtotal'] = $valor_titulo;
      }else{
      $total_parcelas = $data['total_parcela'];
      $valor_parcela = $valor_titulo / $total_parcelas;
      $valor_parcela = substr("$valor_parcela", 0, 8);
      $data_parcela['valor_parcela'] = $valor_parcela;
      $data_parcela['subtotal'] = $valor_parcela;
      }
      $data_parcela['titulo_id'] = $insert_id;
      
      $data_parcela['empresa_id'] = $data['empresa_id'];
      $hoje = date('Y-m-d H:i:s');
      $data_parcela['user_cadastro']   = get_staff_user_id();
      $data_parcela['data_cadastro']   = $hoje;
      $data_parcela['user_ultima_alteracao']   = get_staff_user_id();
      $data_parcela['data_ultima_alteracao']   = $hoje;
      
      $t_inicio = $inicio_parcela -1;
      $total_parcelas = $nParcelas - $t_inicio;
      //echo $total_parcelas; exit;
      $cont_1 = 1;
      for($x = 0; $x < $nParcelas; $x++){
           
          $data_prox = date("d/m/Y",strtotime("+".$x." month",mktime(0, 0, 0,$mes,$dia,$ano)));
         // ECHO $data_prox.'<BR>';
          $data_banco = implode('-', array_reverse(explode('/', "$data_prox")));
         
         if($cont_1 >= $inicio_parcela){ 
          $data_parcela['parcela']   = $inicio_parcela++;
          $data_parcela['data_vencimento']   = $data_banco;
          //print_R($data_parcela);
          //echo '<br>';
          $this->db->insert(db_prefix() . 'fin_lancamentos_parcelas', $data_parcela);
          $insert_id = $this->db->insert_id();
         }
        
          
          log_activity('PARCELA CONTA PAGAR created [ ID : '.$insert_id.']', null, $data_parcela);
          $cont_1++;
      }
      //exit;
     
    }
 

    
    public function delete_conta_pagar($data, $id_registro)
    {
        
        $this->db->where('id', $id_registro);
        $this->db->update(db_prefix() . 'fin_lancamentos', $data);
        
        $this->db->where('titulo_id', $id_registro);
        $this->db->update(db_prefix() . 'fin_lancamentos_parcelas', $data);
        
        log_activity('CONTA PAGAR deleted [Name: ' . $data['numero_documento'] . '][ ID : '.$id_registro.']', null, $data);
        if ($this->db->affected_rows() > 0) {
            hooks()->do_action('Conta Pagar deletado', $id_registro, $data);
            return true;
        }
        return false;
    }
    
    
    public function get_documento_conta_pagar()
    {
     
       $empresa_id = $this->session->userdata('empresa_id');
       
       $sql = "";
       
       
        $sql = 'SELECT distinct(numero_documento) as numero_documento
                FROM tblfin_lancamentos 
                where deleted = 0 and empresa_id = '.$empresa_id;
       
        $sql .=  " order by numero_documento asc";
      
        $result = $this->db->query($sql)->result_array();
       
       
       
       return $result;
      
    }
    
    
    public function get_conta_pagar($id = '')
    {
     
       $empresa_id = $this->session->userdata('empresa_id');
       
       $sql = "";
       
       
       $sql = 'SELECT *
                FROM tblfin_lancamentos 
                where deleted = 0 and empresa_id = '.$empresa_id;
       
       if($id){
           $sql .= " and id = $id";
           
       }
      //echo $sql; exit;
       
       if (is_numeric($id)) {
           $result = $this->db->query($sql)->row();
        }else{
            $result = $this->db->query($sql)->result_array();
        }
       
       
       return $result;
      
    }
    
    /*
     * PARCELAS
     */
    public function get_parcelas_conta_pagar($conta_id = '', $id = '')
    {
     
       $empresa_id = $this->session->userdata('empresa_id');
       
       $sql = "";
       
       
       $sql = 'SELECT p.*, pm.name as modo_pagamento, b.banco as banco, b.agencia, b.numero_conta
                FROM tblfin_lancamentos_parcelas p
                left join tblfin_payment_modes pm on pm.id = p.forma_pagamento
                left join tblfin_bancos b on b.id = p.banco_id
                where p.deleted = 0 and p.empresa_id = '.$empresa_id;
       
       
       if($conta_id){
            $sql .= " and p.titulo_id = $conta_id";
       }
       
       if($id){
           $sql .= " and p.id = $id";
           
       }
     // echo $sql; exit;
       
       if (is_numeric($id)) {
           $result = $this->db->query($sql)->row();
        }else{
            $result = $this->db->query($sql)->result_array();
        }
       
       
       return $result;
      
    }
    
    
    public function edit_parcela($data_titulo)
    {
       
        $hoje = date('Y-m-d H:i:s');
        $id_registro = $data_titulo['id'];
        //echo $id_registro.'<br>';
        if($data_titulo['id']){
            $data_titulo['user_ultima_alteracao']   = get_staff_user_id();
            $data_titulo['data_ultima_alteracao']   = $hoje;
            unset($data_titulo['id']);
            
        }
             $valor = $data_titulo['valor_parcela'];   
            $valor = str_replace(',','.', $valor);
            $data_titulo['valor_parcela'] = $valor;
            
           // print_r($data_titulo); exit;
            $this->db->where('id', $id_registro);
            $this->db->update(db_prefix() . 'fin_lancamentos_parcelas', $data_titulo);
    
           // exit;
            log_activity('CONTA PAGAR PARCELA updated [ ID : '.$id_registro.']', null, $data_titulo);
        
                
       
            hooks()->do_action('fin_lancamentos', $id_registro, $data_titulo);
            return true;
        

        return false;
    }     
    
    
    public function edit_parcela_pagamento($data_titulo, $id_titulo)
    {
       
        $hoje = date('Y-m-d H:i:s');
       // echo $id_titulo.'<br>';
        if($id_titulo){
            unset($data_titulo['ids']);
            $expense = $this->get_parcela($id_titulo);  
            $status = $expense->status;
            
            if($status == 0){
            $data_titulo['user_ultima_alteracao']   = get_staff_user_id();
            $data_titulo['data_ultima_alteracao']   = $hoje;
            $desconto = $data_titulo['desconto'];
            $desconto = str_replace('.','', $desconto);
            $desconto = str_replace(',','.', $desconto);
            $data_titulo['desconto'] = $desconto;
            
            if($desconto > 0){
             
             $valor_parcela = $expense->valor_parcela;
             $total = $valor_parcela - $desconto;
             $data_titulo['valor_parcela'] = $total;
            
            }
            
            $data_titulo['status'] = 1;
            
            //print_r($data_titulo); exit;
            
            $this->db->where('id', $id_titulo);
            $this->db->update(db_prefix() . 'fin_lancamentos_parcelas', $data_titulo);
            
            }
        }

                
        
    
           // exit;
            log_activity('CONTA PAGAR PARCELA updated [ ID : '.$id_titulo.']', null, $data_titulo);
        
                
       
            hooks()->do_action('fin_lancamentos', $id_registro, $data_titulo);
            return true;
        

       
    } 
    
    public function cancela_parcela_pagamento($id_titulo)
    {
       
        $hoje = date('Y-m-d H:i:s');
        if($id_titulo){
            
            
            $data_titulo['user_ultima_alteracao']   = get_staff_user_id();
            $data_titulo['data_ultima_alteracao']   = $hoje;
           
            $data_titulo['desconto'] = '';
            $data_titulo['data_pagamento'] = '';
            $data_titulo['forma_pagamento'] = 'NULL';
            $data_titulo['desconto'] = '';
            $data_titulo['status'] = 0;
            
            
            
            $this->db->where('id', $id_titulo);
            $this->db->update(db_prefix() . 'fin_lancamentos_parcelas', $data_titulo);
            
            
        }

                
        
    
           // exit;
            log_activity('CONTA PAGAR PARCELA updated [ ID : '.$id_titulo.']', null, $data_titulo);
        
                
       
            hooks()->do_action('fin_lancamentos', $id_registro, $data_titulo);
            return true;
        

       
    } 
            
    public function add_edit_conta_pagar_parcela($data_titulo)
    {
       
        $empresa_id = $this->session->userdata('empresa_id');
        $hoje = date('Y-m-d H:i:s');
        $id_registro = $data_titulo['id'];
        
        if($data_titulo['id']){
            $data_titulo['user_ultima_alteracao']   = get_staff_user_id();
            $data_titulo['data_ultima_alteracao']   = $hoje;
            $data_titulo['status'] = 1;
            
            
        }
        
        unset($data_titulo['id']);
        unset($data_titulo['parcela']);
        unset($data_titulo['valor_parcela']);
        unset($data_titulo['data_vencimento']);
                
       //print_r($data_titulo); exit;
            
            $this->db->where('id', $id_registro);
            $this->db->update(db_prefix() . 'fin_lancamentos_parcelas', $data_titulo);
            log_activity('CONTA PAGAR PARCELA updated [ ID : '.$id_registro.']', null, $data_titulo);
        
                
       
            hooks()->do_action('fin_lancamentos', $id_registro, $data_titulo);
            return true;
        

        return false;
    }
    
    
    public function add_baixa_parcela($id_registro)
    {
       
        $empresa_id = $this->session->userdata('empresa_id');
        $hoje = date('Y-m-d H:i:s');
     
           
            $data_titulo['user_ultima_alteracao']   = get_staff_user_id();
            $data_titulo['data_ultima_alteracao']   = $hoje;
            
            $data_titulo['status'] = 1;
                
  
            
            $this->db->where('id', $id_registro);
            $this->db->update(db_prefix() . 'fin_lancamentos_parcelas', $data_titulo);
            log_activity('CONTA PAGAR PARCELA updated [ ID : '.$id_registro.']', null, $data_titulo);
        
                
       
            hooks()->do_action('fin_lancamentos', $id_registro, $data_titulo);
            return true;
        

     
    }
    
    public function delete_conta_pagar_parcela($data, $id_registro)
    {
        
        $this->db->where('id', $id_registro);
        $this->db->update(db_prefix() . 'fin_lancamentos_parcelas', $data);
        
        log_activity('CONTA PAGAR PARCELA deleted [Name: ' . $data['numero_documento'] . '][ ID : '.$id_registro.']', null, $data);
        if ($this->db->affected_rows() > 0) {
            hooks()->do_action('Conta Pagar deletado', $id_registro, $data);
            return true;
        }
        return false;
    }
    
    // retorna a parcela a pagar y id
    public function get_parcela($id = '')
    {
       $empresa_id = $this->session->userdata('empresa_id');
       $sql = "";
       $sql = 'SELECT *
                FROM tblfin_lancamentos_parcelas  
                where deleted = 0 and empresa_id = '.$empresa_id;
       
       if($id){
           $sql .= " and id = $id";
       }
      //echo $sql; exit;
       
       if (is_numeric($id)) {
           $result = $this->db->query($sql)->row();
        }else{
            $result = $this->db->query($sql)->result_array();
        }
       
       
       return $result;
      
    }
    
    /*******************************************************************
     ******************* FIM PLANO DE CONTAS  **************************
     *******************************************************************/
    
    
    
   
   /*
    * PLANO DE CONTAS
    */
    
    //RETORNA OS PLANOS DE CONTAS por categoria
    public function get_plano_contas_by_categoria($categoria_id = '')
    {
     
       $empresa_id = $this->session->userdata('empresa_id');
       $sql = "";
       $sql = 'SELECT *
                FROM tblfin_plano_contas 
                where deleted = 0 and empresa_id = '.$empresa_id;
       
       if($categoria_id){
           $sql .= " and categoria_id = $categoria_id";
           
       }
      
       
        $result = $this->db->query($sql)->result_array();
       
       return $result;
      
    }
    
    //RETORNA OS PLANOS DE CONTAS
     public function get_plano_contas($id = '')
    {
     
       $empresa_id = $this->session->userdata('empresa_id');
       
       $sql = "";
       
       
       $sql = 'SELECT pc.*, c.name as categoria
                FROM tblfin_plano_contas pc
                inner join tblfin_categories c on c.id = pc.categoria_id
                where pc.deleted = 0 and pc.empresa_id = '.$empresa_id;
       
       if($id){
           $sql .= " and pc.id = $id";
           
       }
      
       
       if (is_numeric($id)) {
           $result = $this->db->query($sql)->row();
        }else{
            $result = $this->db->query($sql)->result_array();
        }
       
      
       return $result;
      
    }
    
     /*
     * PLANO DE CONTAS
     */
    public function add_edit_plano_conta($data)
    {
        $empresa_id = $this->session->userdata('empresa_id');
        $hoje = date('Y-m-d H:i:s');
        $id_registro = $data['id'];
        
        if($data['id']){
            $data['user_ultima_alteracao']   = get_staff_user_id();
            $data['data_ultima_alteracao']   = $hoje;
        }else{
            $data['user_cadastro']   = get_staff_user_id();
            $data['empresa_id']   = $empresa_id;
            $data['data_cadastro']   = $hoje;
            $data['user_ultima_alteracao']   = get_staff_user_id();
            $data['data_ultima_alteracao']   = $hoje;
        }
        unset($data['id']);
                
        //   print_r($data); exit;
        if(!$id_registro){
            $this->db->insert(db_prefix() . 'fin_plano_contas', $data);
            $insert_id = $this->db->insert_id();
            log_activity('PLANO DE CONTA created [Name: ' . $data['descricao'] . '][ ID : '.$insert_id.']', null, $data);
        }else{
            $this->db->where('id', $id_registro);
            $this->db->update(db_prefix() . 'fin_plano_contas', $data);
            log_activity('PLANO DE CONTA updated [Name: ' . $data['descricao'] . '][ ID : '.$id_registro.']', null, $data);
        }
        
                
        if ($insert_id) {
            hooks()->do_action('tblfin_plano_contas', $insert_id, $data);
            return $insert_id;
        }

        return false;
    }
    
    
    public function delete_plano_conta($data, $id_registro)
    {
        
        $this->db->where('id', $id_registro);
        $this->db->update(db_prefix() . 'fin_plano_contas', $data);
        log_activity('PLANO DE CONTA deleted [Name: ' . $data['descricao'] . '][ ID : '.$id_registro.']', null, $data);
        if ($this->db->affected_rows() > 0) {
            hooks()->do_action('Plano Conta deletado', $id_registro, $data);
            return true;
        }
        return false;
    }
    
    /*
     * FIM PLANO DE CONTAS
     */
    
    
    /*
     * MOVIMENTAÇÃO BANCÁRIA
     */
    
    public function add_movimento_bancario($data)
    {
        $empresa_id = $this->session->userdata('empresa_id');
        $hoje = date('Y-m-d H:i:s');
        $id_registro = $data['id'];
        
        $valor = $data['valor'];
        $valor = str_replace('.','', $valor);
        $valor = str_replace(',','.', $valor);
        $data['valor'] = $valor;
        
        if($data['id']){
            $data['user_ultima_alteracao']   = get_staff_user_id();
            $data['data_ultima_alteracao']   = $hoje;
        }else{
            $data['user_cadastro']   = get_staff_user_id();
            $data['empresa_id']   = $empresa_id;
            $data['data_cadastro']   = $hoje;
            $data['user_ultima_alteracao']   = get_staff_user_id();
            $data['data_ultima_alteracao']   = $hoje;
        }
        unset($data['id']);
        
        if(!$id_registro){
            $this->db->insert(db_prefix() . 'fin_movimentacao_bancaria', $data);
            $insert_id = $this->db->insert_id();
           // log_activity('PLANO DE CONTA created [Name: ' . $data['descricao'] . '][ ID : '.$insert_id.']', null, $data);
        }else{
            $this->db->where('id', $id_registro);
            $this->db->update(db_prefix() . 'fin_movimentacao_bancaria', $data);
          //  log_activity('PLANO DE CONTA updated [Name: ' . $data['descricao'] . '][ ID : '.$id_registro.']', null, $data);
        }
        
                
        if ($insert_id) {
          //  hooks()->do_action('tblfin_plano_contas', $insert_id, $data);
            return $insert_id;
        }

        return false;
    }
    
    public function add_transferencia_bancario($data)
    {
        $empresa_id = $this->session->userdata('empresa_id');
        $hoje = date('Y-m-d H:i:s');
        $id_registro = $data['id'];
        
        $banco_origem = $data['banco_origem'];
        unset($data['banco_origem']);
        
        $banco_destino = $data['banco_destino'];
        unset($data['banco_destino']);
        
        
        
        
        
        
        
        $valor = $data['valor'];
            $valor = str_replace('.','', $valor);
            $valor = str_replace(',','.', $valor);
        
        if($banco_origem){
            
            $data['valor'] = $valor;
        
            $data['user_cadastro']   = get_staff_user_id();
            $data['empresa_id']   = $empresa_id;
            $data['data_cadastro']   = $hoje;
            $data['user_ultima_alteracao']   = get_staff_user_id();
            $data['data_ultima_alteracao']   = $hoje;
            
            $data['banco_id'] = $banco_origem;
            $data['tipo'] = 'S';
            unset($data['id']);
           
            $this->db->insert(db_prefix() . 'fin_movimentacao_bancaria', $data);
        }
        
       
        if($banco_destino){
            
            $data['valor'] = $valor;
        
            $data['user_cadastro']   = get_staff_user_id();
            $data['empresa_id']   = $empresa_id;
            $data['data_cadastro']   = $hoje;
            $data['user_ultima_alteracao']   = get_staff_user_id();
            $data['data_ultima_alteracao']   = $hoje;
            $data['banco_id'] = $banco_destino;
            $data['tipo'] = 'E';
             unset($data['id']);
             
            $this->db->insert(db_prefix() . 'fin_movimentacao_bancaria', $data);
        }
        
             return TRUE;
        
    }
    
    public function delete_movimento($data, $id_registro)
    {
        
        $this->db->where('id', $id_registro);
        $this->db->update(db_prefix() . 'fin_plano_contas', $data);
        log_activity('PLANO DE CONTA deleted [Name: ' . $data['descricao'] . '][ ID : '.$id_registro.']', null, $data);
        if ($this->db->affected_rows() > 0) {
            hooks()->do_action('Plano Conta deletado', $id_registro, $data);
            return true;
        }
        return false;
    }
    
    
    /*
     * FIM MOVIMENTAÇÃO BANCÁRIA
     */
    
    
    
    
    /*
     * CATEGORIAS PLANO DE CONTA
     */
    
    /** RETORNA AS CATEGORIAS*/
    public function get_categorias($natureza = "")
    {
         
        $where ="";
        if($natureza == 1){
            $where .= " and natureza = 1";
        }else if($natureza == 2){
            $where .= " and natureza = 2";
        }
       $empresa_id = $this->session->userdata('empresa_id');
       $sql = 'SELECT * FROM tblfin_categories
                where deleted = 0 and empresa_id = '.$empresa_id.' '. $where;
       
       $sql .=  " order by name asc";
       
       //echo $sql; exit;
       $result = $this->db->query($sql)->result_array();
       return $result;
      
    }
    
    public function add_categoria($data)
    {
        $empresa_id = $this->session->userdata('empresa_id');
        $hoje = date('Y-m-d H:i:s');
        $data['user_cadastro']   = get_staff_user_id();
        $data['empresa_id']   = $empresa_id;
        $data['data_cadastro']   = $hoje;
        $data['user_ultima_alteracao']   = get_staff_user_id();
        $data['data_ultima_alteracao']   = $hoje;
            
        $this->db->insert(db_prefix() . 'fin_categories', $data);
        $insert_id = $this->db->insert_id();
        log_activity('CATEGORIA PLANO DE CONTA Created [Name: ' . $data['name'] . '][ ID : '.$insert_id.']', null, $data);
     

        return $this->db->insert_id();
    }

    public function edit_categoria($data, $id)
    {
       
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'fin_categories', $data);
        if ($this->db->affected_rows() > 0) {
            log_activity('CATEGORIA PLANO DE CONTA updated [Name: ' . $data['name'] . '][ ID : '.$id.']', null, $data);

            return true;
        }

        return false;
    }
    
    /*
     * FIM CATEGORIA PLANO DE CONTA
     */
    
    
    
    /*
     * FORNECEDORES
     */
    
    public function get_fornecedores($id = '')
    {
     
       $empresa_id = $this->session->userdata('empresa_id');
       
       $sql = "";
       
       
       $sql = 'SELECT *
                FROM tblfin_fornecedores 
                where deleted = 0 and empresa_id = '.$empresa_id;
       
       if($id){
           $sql .= " and id = $id";
           
       }
      
       $sql .=  " order by company asc";
       if (is_numeric($id)) {
           $result = $this->db->query($sql)->row();
        }else{
            $result = $this->db->query($sql)->result_array();
        }
       
       
       return $result;
      
    }
    
    public function add_edit_fornecedores($data)
    {
        $empresa_id = $this->session->userdata('empresa_id');
        $hoje = date('Y-m-d H:i:s');
        $id_registro = $data['id'];
        
        if($data['id']){
            $data['user_ultima_alteracao']   = get_staff_user_id();
            $data['data_ultima_alteracao']   = $hoje;
        }else{
            $data['user_cadastro']   = get_staff_user_id();
            $data['empresa_id']   = $empresa_id;
            $data['data_cadastro']   = $hoje;
            $data['user_ultima_alteracao']   = get_staff_user_id();
            $data['data_ultima_alteracao']   = $hoje;
        }
        unset($data['id']);
        
        
           //   print_r($data); exit;
        if(!$id_registro){
            $this->db->insert(db_prefix() . 'fin_fornecedores', $data);
            $insert_id = $this->db->insert_id();
            log_activity('FORNECEDORES created [Name: ' . $data['company'] . '][ ID : '.$insert_id.']', null, $data);
        }else{
            
            $this->db->where('id', $id_registro);
            $this->db->update(db_prefix() . 'fin_fornecedores', $data);
            log_activity('FORNECEDORES updated [Name: ' . $data['company'] . '][ ID : '.$id_registro.']', null, $data);
        }
        
        
        
        if ($insert_id) {
            hooks()->do_action('fin_fornecedores', $insert_id, $data);

            return $insert_id;
        }

        return false;
    }
    
    
    public function delete_fornecedores($data, $id_registro)
    {
        
        $this->db->where('id', $id_registro);
        $this->db->update(db_prefix() . 'fin_fornecedores', $data);
        log_activity('FORNECEDORES deleted [Name: ' . $data['company'] . '][ ID : '.$id_registro.']', null, $data);
        
        if ($this->db->affected_rows() > 0) {
            hooks()->do_action('Fornecedor deletado', $id_registro, $data);
            return true;
        }
        return false;
    }
    
    /*
     * FIM FORNECEDORES
     */
    
    
    /*
     * CLIENTES
     */
    
     public function get_clientes($id = '')
    {
     
       $empresa_id = $this->session->userdata('empresa_id');
       
       $sql = "";
       
       $sql = 'SELECT *
                FROM tblfin_clients_financeiro 
                where deleted = 0 and empresa_id = '.$empresa_id;
       
       if($id){
           $sql .= " and id = $id";
           
       }
      //echo $sql; exit;
       
       if (is_numeric($id)) {
           $result = $this->db->query($sql)->row();
        }else{
            $result = $this->db->query($sql)->result_array();
        }
       
       
       return $result;
      
    }
    
    public function add_edit_clientes($data)
    {
        $empresa_id = $this->session->userdata('empresa_id');
        $hoje = date('Y-m-d H:i:s');
        $id_registro = $data['id'];
        
        if($data['id']){
            $data['user_ultima_alteracao']   = get_staff_user_id();
            $data['data_ultima_alteracao']   = $hoje;
        }else{
            $data['addedfrom']   = get_staff_user_id();
            $data['empresa_id']   = $empresa_id;
            $data['datecreated']   = $hoje;
            $data['user_ultima_alteracao']   = get_staff_user_id();
            $data['data_ultima_alteracao']   = $hoje;
            
            
            $cpf = $data['vat'];
            $cpf = str_replace('.', '', $cpf);
            $cpf = str_replace(',', '', $cpf);
            $cpf = str_replace('-', '', $cpf);
            $cpf = str_replace('/', '', $cpf);

            $data['vat'] = $cpf;

            // empresa

            $data['empresa_id'] = $empresa_id;
            
        }
        unset($data['id']);
        
         
        if(!$id_registro){
            
            $this->db->insert(db_prefix() . 'fin_clients_financeiro', $data);
            $insert_id = $this->db->insert_id();
            log_activity('CLIENTE FINANCEIRO Created [Name: ' . $data['company'] . '][ ID : '.$insert_id.']', null, $data);
            
        }else{
            
            $this->db->where('id', $id_registro);
            $this->db->update(db_prefix() . 'fin_clients_financeiro', $data);
            log_activity('CLIENTE FINANCEIRO Updated [Name: ' . $data['company'] . '] [ ID : '.$id_registro.']', null, $data);
        }
        
        
        
        if ($insert_id) {
            hooks()->do_action('tblclients', $insert_id, $data);

            return $insert_id;
        }

        return false;
    }
    
    public function delete_clientes($data, $id_registro)
    {
        
        $this->db->where('id', $id_registro);
        $this->db->update(db_prefix() . 'fin_clients_financeiro', $data);
        log_activity('CLIENTE FINANCEIRO deleted [Name: ' . $data['company'] . '][ ID : '.$id_registro.']', null, $data);
        
        if ($this->db->affected_rows() > 0) {
            hooks()->do_action('Cliente deletado', $id_registro, $data);
            return true;
        }
        return false;
    }
    
    /*
     * FIM CLIENTES
     */
    
    
    /*
     * CENTRO DE CUSTO
     */
     
    public function get_centro_custo($id = '')
    {
     
       $empresa_id = $this->session->userdata('empresa_id');
       
       $sql = "";
       
       
       $sql = 'SELECT cc.*, t.descricao as tipo
                FROM tblfin_centro_custo cc
                LEFT join tblfin_centro_custo_tipo t on t.id = cc.cc_tipo_id
                where cc.deleted = 0 and cc.empresa_id = '.$empresa_id;
       
       if($id){
           $sql .= " and id = $id";
           
       }
      $sql .=  " order by descricao asc";
       //echo $sql; exit;
       if (is_numeric($id)) {
           $result = $this->db->query($sql)->row();
        }else{
            $result = $this->db->query($sql)->result_array();
        }
       
       
       return $result;
      
    }
    
    public function add_edit_centro_custo($data)
    {
        $empresa_id = $this->session->userdata('empresa_id');
        $hoje = date('Y-m-d H:i:s');
        $id_registro = $data['id'];
        
        if($data['id']){
            $data['user_ultima_alteracao']   = get_staff_user_id();
            $data['data_ultima_alteracao']   = $hoje;
        }else{
            $data['user_cadastro']   = get_staff_user_id();
            $data['empresa_id']   = $empresa_id;
            $data['data_cadastro']   = $hoje;
            $data['user_ultima_alteracao']   = get_staff_user_id();
            $data['data_ultima_alteracao']   = $hoje;
        }
        unset($data['id']);
        
        
            //  print_r($data); exit;
        if(!$id_registro){
            $this->db->insert(db_prefix() . 'fin_centro_custo', $data);
            $insert_id = $this->db->insert_id();
            log_activity('CENTRO DE CUSTO Created [Name: ' . $data['descricao'] . '][ ID : '.$insert_id.']', null, $data);
        }else{
            $this->db->where('id', $id_registro);
            $insert_id = $this->db->update(db_prefix() . 'fin_centro_custo', $data);
            log_activity('CENTRO DE CUSTO Updated [Name: ' . $data['descricao'] . '][ ID : '.$insert_id.']', null, $data);
        }
        
        
        
        if ($insert_id) {
            hooks()->do_action('fin_centro_custo', $insert_id, $data);

            return $insert_id;
        }

        return false;
    }
    
    
    public function delete_centro_custo($data, $id_registro)
    {
        
        $this->db->where('id', $id_registro);
        $this->db->update(db_prefix() . 'fin_centro_custo', $data);
        log_activity('CENTRO DE CUSTO deleted [Name: ' . $data['descricao'] . '][ ID : '.$id_registro.']', null, $data);
        
        if ($this->db->affected_rows() > 0) {
            hooks()->do_action('Centro de Custo deletado', $id_registro, $data);
            return true;
        }
        return false;
    }
    
    /*
     * FIM CENTRO DE CUSTO
     */
    
    /*
     * TIPO CENTRO DE CUSTO
     */
     public function get_tipo_centro_custo()
    {   
       $empresa_id = $this->session->userdata('empresa_id');
       $sql = 'SELECT * FROM tblfin_centro_custo_tipo
                where deleted = 0 and empresa_id = '.$empresa_id;
       //echo $sql; exit;
       $result = $this->db->query($sql)->result_array();
       return $result;
    }
    
    
    public function add_tipo_centro_custo($data)
    {
        $empresa_id = $this->session->userdata('empresa_id');
        $hoje = date('Y-m-d H:i:s');
        $data['user_cadastro']   = get_staff_user_id();
        $data['empresa_id']   = $empresa_id;
        $data['data_cadastro']   = $hoje;
        $data['user_ultima_alteracao']   = get_staff_user_id();
        $data['data_ultima_alteracao']   = $hoje;
        
        $this->db->insert(db_prefix() . 'fin_centro_custo_tipo', $data);
        $insert_id = $this->db->insert_id();
        log_activity('TIPO CENTRO DE CUSTO Created [Name: ' . $data['name'] . '][ ID : '.$insert_id.']', null, $data);
     

        return $this->db->insert_id();
    }

    public function edit_tipo_centro_custo($data, $id)
    {
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'fin_centro_custo_tipo', $data);
        if ($this->db->affected_rows() > 0) {
            log_activity('TIPO CENTRO DE CUSTO Updated [Name: ' . $data['descricao'] . '][ ID : '.$id.']', null, $data);
            return true;
        }

        return false;
    }
    
    public function delete_tipo_centro_custo($data, $id_registro)
    {
        
        $this->db->where('id', $id_registro);
        $this->db->update(db_prefix() . 'fin_centro_custo_tipo', $data);
        log_activity('TIPO CENTRO DE CUSTO deleted [Name: ' . $data['descricao'] . '][ ID : '.$id_registro.']', null, $data);
        
        if ($this->db->affected_rows() > 0) {
            hooks()->do_action('Tipo de Centro de custo deletado', $id_registro, $data);
            return true;
        }
        return false;
    }
    /*
     * FIM TIPO CENTRO DE CUSTO
     */
    
    
    /*
     * BANCOS
     */
    
    public function get_bancos($id = '')
    {
     
       $empresa_id = $this->session->userdata('empresa_id');
       
       $sql = "";
       
       
       $sql = 'SELECT *
                FROM tblfin_bancos 
                where deleted = 0 and empresa_id = '.$empresa_id;
       
       if($id){
           $sql .= " and id = $id";
           
       }
      
       
       if (is_numeric($id)) {
           $result = $this->db->query($sql)->row();
        }else{
            $result = $this->db->query($sql)->result_array();
        }
       
       
       return $result;
      
    }
    
    public function add_edit_bancos($data)
    {
        $empresa_id = $this->session->userdata('empresa_id');
        $hoje = date('Y-m-d H:i:s');
        $id_registro = $data['id'];
        
        if($data['id']){
            $data['user_ultima_alteracao']   = get_staff_user_id();
            $data['data_ultima_alteracao']   = $hoje;
        }else{
            $data['user_cadastro']   = get_staff_user_id();
            $data['empresa_id']   = $empresa_id;
            $data['data_cadastro']   = $hoje;
            $data['user_ultima_alteracao']   = get_staff_user_id();
            $data['data_ultima_alteracao']   = $hoje;
        }
        unset($data['id']);
        
        
           //   print_r($data); exit;
        if(!$id_registro){
            $this->db->insert(db_prefix() . 'fin_bancos', $data);
            $insert_id = $this->db->insert_id();
            log_activity('BANCO Created [Name: ' . $data['descricao'] . '][ ID : '.$insert_id.']', null, $data);
        }else{
            
            $this->db->where('id', $id_registro);
            $insert_id = $this->db->update(db_prefix() . 'fin_bancos', $data);
            log_activity('BANCO updated [Name: ' . $data['descricao'] . '][ ID : '.$insert_id.']', null, $data);
        }
        
        
        
        if ($insert_id) {
            hooks()->do_action('fin_bancos', $insert_id, $data);

            return $insert_id;
        }

        return false;
    }
    
    
    public function delete_bancos($data, $id_registro)
    {
        
        $this->db->where('id', $id_registro);
        $this->db->update(db_prefix() . 'fin_bancos', $data);
        log_activity('BANCO deleted [Name: ' . $data['banco'] . '][ ID : '.$id_registro.']', null, $data);
        
        if ($this->db->affected_rows() > 0) {
            hooks()->do_action('Banco deletado', $id_registro, $data);
            return true;
        }
        return false;
    }
    
    /*
     * FIM BANCOS
     */
    
    
    /*
     * FORMA PAGAMENTO
     */
    
    public function get_forma_pagamento($id = '')
    {
     
       $empresa_id = $this->session->userdata('empresa_id');
       
       $sql = "";
       
       
       $sql = 'SELECT *
                FROM tblfin_payment_modes
                where deleted = 0 and empresa_id = '.$empresa_id;
       
       if($id){
           $sql .= " and id = $id";
           
       }
     // echo $sql; exit;
       
       if (is_numeric($id)) {
           $result = $this->db->query($sql)->row();
        }else{
            $result = $this->db->query($sql)->result_array();
        }
       
       
       return $result;
      
    }
    
    public function add_edit_forma_pagamento($data)
    {
        $empresa_id = $this->session->userdata('empresa_id');
        $hoje = date('Y-m-d H:i:s');
        $id_registro = $data['id'];
        
        if($data['id']){
            $data['user_ultima_alteracao']   = get_staff_user_id();
            $data['data_ultima_alteracao']   = $hoje;
        }else{
            $data['user_cadastro']   = get_staff_user_id();
            $data['empresa_id']   = $empresa_id;
            $data['data_cadastro']   = $hoje;
            $data['user_ultima_alteracao']   = get_staff_user_id();
            $data['data_ultima_alteracao']   = $hoje;
        }
        unset($data['id']);
        
        
           //   print_r($data); exit;
        if(!$id_registro){
            $this->db->insert(db_prefix() . 'fin_payment_modes', $data);
            $insert_id = $this->db->insert_id();
            log_activity('FORMA PAGAMENTO FINANCEIRO Created [Name: ' . $data['name'] . '][ ID : '.$insert_id.']', null, $data);
        }else{
            
            $this->db->where('id', $id_registro);
            $insert_id = $this->db->update(db_prefix() . 'fin_payment_modes', $data);
            log_activity('FORMA PAGAMENTO FINANCEIRO updated [Name: ' . $data['name'] . '][ ID : '.$insert_id.']', null, $data);
        }
        
        
        
        if ($insert_id) {
            hooks()->do_action('fin_bancos', $insert_id, $data);

            return $insert_id;
        }

        return false;
    }
    
    
    public function delete_forma_pagamento($data, $id_registro)
    {
        
        $this->db->where('id', $id_registro);
        $this->db->update(db_prefix() . 'fin_payment_modes', $data);
        log_activity('FORMA PAGAMENTO FINANCEIRO deleted [Name: ' . $data['name'] . '][ ID : '.$id_registro.']', null, $data);
        
        if ($this->db->affected_rows() > 0) {
            hooks()->do_action('Forma Pagamento deletado', $id_registro, $data);
            return true;
        }
        return false;
    }
    
    /*
     * FIM FORMA PAGAMENTO
     */
    
     /*
     * TIPOS DE DOCUMENTOS
     */
     
    public function get_tipos_documentos($id = '')
    {
     
       $empresa_id = $this->session->userdata('empresa_id');
       
       $sql = "";
       
       
       $sql = 'SELECT *
                FROM tblfin_tipo_documento
                where deleted = 0 and empresa_id = '.$empresa_id;
       
       if($id){
           $sql .= " and id = $id";
           
       }
      
       
       if (is_numeric($id)) {
           $result = $this->db->query($sql)->row();
        }else{
            $result = $this->db->query($sql)->result_array();
        }
       
       
       return $result;
      
    }
    
    /*
     * FIM TIPOS DE DOCUMENTOS
     */
    
    
   /********************************************************
     ************* CONTAS A RECEBER  ******************
     *****************************************************/
    public function get_invoices($id = '', $where = [])
    {
        
        $this->db->select('*, ' . db_prefix() . 'currencies.id as currencyid, ' . db_prefix() . 'fin_invoices.id as id, ' . db_prefix() . 'currencies.name as currency_name, '. db_prefix() .'fin_invoices.status as status');
        $this->db->from(db_prefix() . 'fin_invoices');
        $this->db->join(db_prefix() . 'currencies', '' . db_prefix() . 'currencies.id = ' . db_prefix() . 'fin_invoices.currency', 'left');
        $this->db->where('tblfin_invoices.deleted', 0);
        $this->db->where($where);
         
        if (is_numeric($id)) {
            
            $this->db->where(db_prefix() . 'fin_invoices' . '.id', $id);
            $invoice = $this->db->get()->row();
           
            if ($invoice) {
                $invoice->total_left_to_pay = get_fin_invoice_total_left_to_pay($invoice->id, $invoice->total);
                 
                $invoice->items       = get_fin_items_by_type('invoice', $id);
                 
                $client          = $this->Financeiro_model->get_clientes($invoice->clientid);
                $invoice->client = $client;
               
                
                $invoice->payments = $this->get_invoice_payments($id);
                
                $invoice->glosas = $this->get_invoice_glosas($id);
              
                
                //$this->load->model('email_schedule_model');
                //$invoice->scheduled_email = $this->email_schedule_model->get($id, 'invoice');
            }
            
           
           $this->db->order_by('number,YEAR(date)', 'desc');
          
            $result = $invoice;
             
        }else{
            $this->db->order_by('number,YEAR(date)', 'desc');
        
        $result = $this->db->get()->result_array();
        }
        
                
        return $result;
    }
    
    public function get_conta_receber($id = '')
    {
     
        $empresa_id = $this->session->userdata('empresa_id');
       
        $this->db->select('*');
        $this->db->from(db_prefix() . 'fin_invoices');
        $this->db->where('tblfin_invoices.deleted', 0);
        $this->db->where('tblfin_invoices.empresa_id', $empresa_id); 
        if (is_numeric($id)) {
            $this->db->where(db_prefix() . 'fin_invoices' . '.id', $id);
            $invoice = $this->db->get()->row(); 
            if ($invoice) {
                $invoice->total_left_to_pay = get_fin_invoice_total_left_to_pay($invoice->id, $invoice->total);
                
                          
                $result = $invoice;
            } 
        }else{
            $this->db->order_by('number,YEAR(date)', 'desc');
        
        $result = $this->db->get()->result_array();
        }
        
        return $result;  
    }
    
    public function get_invoice_glosas($invoiceid)
    {
        $empresa_id = $this->session->userdata('empresa_id');
        $this->db->order_by(db_prefix() . 'fin_glosas.id', 'asc');
        $this->db->where('fin_glosas.deleted', 0);
        $this->db->where('fin_glosas.empresa_id', $empresa_id);
        $this->db->where('invoiceid', $invoiceid);
      
        $payments = $this->db->get(db_prefix() . 'fin_glosas')->result_array();
       
        // Since version 1.0.1
       
       
        return $payments;
    }
    
    public function add_invoices($data, $insert_id = '')
    {
       
        $data['prefix'] = get_option('invoice_prefix');

        $data['number_format'] = get_option('invoice_number_format');

        $data['datecreated'] = date('Y-m-d H:i:s');

        $save_and_send = isset($data['save_and_send']);

        $data['addedfrom'] = !DEFINED('CRON') ? get_staff_user_id() : 0;

        $data['cancel_overdue_reminders'] = isset($data['cancel_overdue_reminders']) ? 1 : 0;

        $data['allowed_payment_modes'] = isset($data['allowed_payment_modes']) ? serialize($data['allowed_payment_modes']) : serialize([]);

        $billed_tasks = isset($data['billed_tasks']) ? array_map('unserialize', array_unique(array_map('serialize', $data['billed_tasks']))) : [];

        $billed_expenses = isset($data['billed_expenses']) ? array_map('unserialize', array_unique(array_map('serialize', $data['billed_expenses']))) : [];

        $cancel_merged_invoices = isset($data['cancel_merged_invoices']);

        $tags = isset($data['tags']) ? $data['tags'] : '';

        
       
        if (isset($data['save_as_draft'])) {
            $data['status'] = self::STATUS_DRAFT;
            unset($data['save_as_draft']);
        } elseif (isset($data['save_and_send_later'])) {
            $data['status'] = self::STATUS_DRAFT;
            unset($data['save_and_send_later']);
        }

        if (isset($data['custom_fields'])) {
            $custom_fields = $data['custom_fields'];
            unset($data['custom_fields']);
        }

        $data['hash'] = app_generate_hash();
        
       // empresa
       $empresa_id = $this->session->userdata('empresa_id');
       $data['empresa_id'] = $empresa_id;
       
        $hook = hooks()->apply_filters('before_invoice_added', [
            'data'  => $data,
        ]);
        
        
        $total_faturado = $data['subtotal'];
        //$total_faturado = str_replace('.','', $total_faturado);
        $total_faturado = str_replace(',','.', $total_faturado);
        $data['subtotal'] = $total_faturado;
        
        
        
        $total_tax = $data['total_tax'];
        //$total_tax = str_replace('.','', $total_tax);
        $total_tax = str_replace(',','.', $total_tax);
        $data['total_tax'] = $total_tax;
       if(!$total_tax){
           $total_tax = 0;
       } 
       
        
        
        $data['currency'] = 1;
        $data['sale_agent'] = get_staff_user_id();
        
        
       $client_id = $data['client_id'];
       unset($data['client_id']);
       $data['clientid'] = $client_id;
       
        $data_exec = $data['date'];
        unset($data['date']);
        $data_n = str_replace("/", "-", $data_exec);
        $new_data_Execuxao = date('Y-m-d', strtotime($data_n));
        $data['date'] = $new_data_Execuxao;
       
        
        $duedate_exec = $data['duedate'];
        unset($data['duedate']);
        $data_duedate = str_replace("/", "-", $duedate_exec);
        $new_duedate = date('Y-m-d', strtotime($data_duedate));
        $data['duedate'] = $new_duedate;
        
        $desconto = $data['desconto'];
        //$total_faturado = str_replace('.','', $total_faturado);
        $desconto = str_replace(',','.', $desconto);
        unset($data['desconto']);
        $data['discount_total'] = $desconto;
                
        //print_r($data); exit;
        if($insert_id){
           // update
            unset($data['merge_current_invoice']);
            unset($data['isedit']);
            unset($data['hash']);
            unset($data['number']);
            
            $this->db->where('id', $insert_id);
            $this->db->update(db_prefix() . 'fin_invoices', $data);
        
            update_fin_invoice_status($insert_id);
             //print_r($data); exit;
        }else{
            
            
            $this->db->insert(db_prefix() . 'fin_invoices', $data);
            $insert_id =  $this->db->insert_id();
            
            $this->db->where('id', $insert_id);
            $this->db->update(db_prefix() . 'fin_invoices', ['number' => $insert_id]);
            
            update_fin_invoice_status($insert_id);
        }
        
        
         
        if ($insert_id) {
             
      
           
            return $insert_id;
        }

        return false;
    }
    
    public function is_draft($id)
    {
        return total_rows('tblfin_invoices', ['id' => $id, 'status' => self::STATUS_DRAFT]) === 1;
    }
    
    public function increment_next_number()
    {
        // Update next invoice number in settings
        $this->db->where('name', 'next_invoice_number');
        $this->db->set('value', 'value+1', false);
        $this->db->update(db_prefix() . 'options');
    }
    
    private function map_shipping_columns($data, $expense = false)
    {
        if (!isset($data['include_shipping'])) {
            foreach ($this->shipping_fields as $_s_field) {
                if (isset($data[$_s_field])) {
                    $data[$_s_field] = null;
                }
            }
            $data['show_shipping_on_invoice'] = 1;
            $data['include_shipping']         = 0;
        } else {
            // We dont need to overwrite to 1 unless its coming from the main function add
            if (!DEFINED('CRON') && $expense == false) {
                $data['include_shipping'] = 1;
                // set by default for the next time to be checked
                if (isset($data['show_shipping_on_invoice']) && ($data['show_shipping_on_invoice'] == 1 || $data['show_shipping_on_invoice'] == 'on')) {
                    $data['show_shipping_on_invoice'] = 1;
                } else {
                    $data['show_shipping_on_invoice'] = 0;
                }
            }
            // else its just like they are passed
        }

        return $data;
    }
    
      public function log_invoice_activity($id, $description = '', $client = false, $additional_data = '')
    {
        $empresa_id = $this->session->userdata('empresa_id');
        
        if (DEFINED('CRON')) {
            $staffid   = '[CRON]';
            $full_name = '[CRON]';
        } elseif (defined('STRIPE_SUBSCRIPTION_INVOICE')) {
            $staffid   = null;
            $full_name = '[Stripe]';
        } elseif ($client == true) {
            $staffid   = null;
            $full_name = '';
        } else {
            $staffid   = get_staff_user_id();
            $full_name = get_staff_full_name(get_staff_user_id());
        }
        $this->db->insert(db_prefix() . 'sales_activity', [
            'description'     => $description,
            'date'            => date('Y-m-d H:i:s'),
            'rel_id'          => $id,
            'rel_type'        => 'invoice',
            'staffid'         => $staffid,
            'full_name'       => $full_name,
            'additional_data' => $additional_data,
            'ip'              => $this->getUserIP(),
            'empresa_id'      => $empresa_id,
        ]);
    }
    
    public function get_invoice_payments($invoiceid)
    {
        
        $this->db->select('tblfin_invoicepaymentrecords.*, tblfin_payment_modes.name');
        $this->db->join(db_prefix() . 'fin_payment_modes', db_prefix() . 'fin_payment_modes.id = ' . db_prefix() . 'fin_invoicepaymentrecords.paymentmode', 'inner');
        $this->db->order_by(db_prefix() . 'fin_invoicepaymentrecords.id', 'asc');
        $this->db->where('tblfin_invoicepaymentrecords.deleted', 0);
        $this->db->where('invoiceid', $invoiceid);
      
        $payments = $this->db->get(db_prefix() . 'fin_invoicepaymentrecords')->result_array();
       
        // Since version 1.0.1
       
       
        return $payments;
    }
    
    
    
    public function get_modos_payments()
    {
        $empresa_id = $this->session->userdata('empresa_id');
        
        $this->db->order_by(db_prefix() . 'fin_payment_modes.name', 'asc');
        $this->db->where('empresa_id', $empresa_id);
        $this->db->where('deleted', 0);
      
        $payments = $this->db->get(db_prefix() . 'fin_payment_modes')->result_array();
       
        // Since version 1.0.1
       
       
        return $payments;
    }

    public function update_status_invoice ($id, $status){
        
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'fin_invoices', ['status' => $status,]);
        return true;
    }

    /**
     * Update employee role
     * @param  array $data role data
     * @param  mixed $id   role id
     * @return boolean
     */
    public function update($data, $id)
    {
        $affectedRows = 0;
        $permissions  = [];
        if (isset($data['permissions'])) {
            $permissions = $data['permissions'];
        }

        $data['permissions'] = serialize($permissions);

        $update_staff_permissions = false;
        if (isset($data['update_staff_permissions'])) {
            $update_staff_permissions = true;
            unset($data['update_staff_permissions']);
        }

        $this->db->where('roleid', $id);
        $this->db->update(db_prefix() . 'roles', $data);

        if ($this->db->affected_rows() > 0) {
            $affectedRows++;
        }

        if ($update_staff_permissions == true) {
            $this->load->model('staff_model');

            $staff = $this->staff_model->get('', [
                'role' => $id,
            ]);

            foreach ($staff as $member) {
                if ($this->staff_model->update_permissions($permissions, $member['staffid'])) {
                    $affectedRows++;
                }
            }
        }

        if ($affectedRows > 0) {
            log_activity('Role Updated [ID: ' . $id . ', Name: ' . $data['name'] . ']');

            return true;
        }

        return false;
    }

  

    /**
     * Delete employee role
     * @param  mixed $id role id
     * @return mixed
     */
    public function delete($id)
    {
       

        $affectedRows = 0;
        $data['deleted'] = 1;
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'fin_invoices', $data);

        if ($this->db->affected_rows() > 0) {
            $affectedRows++;
        }

        if ($affectedRows > 0) {
            log_activity('Role Deleted [ID: ' . $id);

            return true;
        }

        return false;
    }

    
    public function get_pagamentos_invoice($id = '')
    {
     
       $empresa_id = $this->session->userdata('empresa_id');
       
       $sql = "";
       
       
       $sql = 'SELECT *
                FROM tblfin_invoicepaymentrecords
                where deleted = 0 and empresa_id = '.$empresa_id;
       
       if($id){
           $sql .= " and invoiceid = $id";
           
       }
     
      // echo $sql; exit;
       if (is_numeric($id)) {
           $result = $this->db->query($sql)->result_array();
        }else{
            $result = $this->db->query($sql)->result_array();
        }
       
       
       return $result;
      
    }
    
    public function get_glosas_invoice($invoiceid = '', $tipo = '')
    {
       $empresa_id = $this->session->userdata('empresa_id');
       $sql = "";
       $sql = 'SELECT *
                FROM tblfin_glosas
                where deleted = 0 and empresa_id = '.$empresa_id;
       if($invoiceid){
           $sql .= " and invoiceid = $invoiceid";
       }
       
       if($tipo == 1){
           $sql .= " and tipo = 'antes'";
       }
       
       if($tipo == 2){
           $sql .= " and tipo = 'depois'";
           
           // echo $sql; exit;
       }
      
      
           $result = $this->db->query($sql)->result_array();
       
       
       
       return $result;
      
    }
    
    
    /*
     * REPORTS
     */
    
    
    /*
     * SAÍDAS
     */
    
    // retorna as categorias que estão em uma conta a pagar do ano atual
    public function get_categorias_contas_pagar()
    {
       $empresa_id = $this->session->userdata('empresa_id');
       $sql = "";
       $ano_atual = date('Y');
        $sql = "SELECT id as categoria_id, name as categoria
                FROM tblfin_categories 
                where deleted = 0 and empresa_id = $empresa_id  order by categoria asc";
        //echo $sql; exit;
        $result = $this->db->query($sql)->result_array();
       return $result;
    }
    
    // retorna os anos que tem conta a pagar lançado
    public function get_saidas_years()
    {
        $empresa_id = $this->session->userdata('empresa_id');
        return $this->db->query('SELECT DISTINCT(YEAR(data_vencimento)) as year FROM ' . db_prefix() . 'fin_lancamentos_parcelas where empresa_id = '.$empresa_id.' and deleted = 0 ORDER by year DESC')->result_array();
    }
    
    // retorna o valor total das parcelas
    public function get_valor_saidas_years($categoria, $ano)
    {
        $empresa_id = $this->session->userdata('empresa_id');
        $sql = 'SELECT sum(valor_parcela) as valor '
                . ' FROM ' . db_prefix() . 'fin_lancamentos_parcelas p'
                . ' inner join tblfin_lancamentos l on l.id = p.titulo_id'
                . ' where p.status = 1 and p.empresa_id = '.$empresa_id.' and p.deleted = 0 and l.categoria_id = '.$categoria.' and YEAR(p.data_pagamento) = '.$ano;
        
        return $this->db->query($sql)->row();
    }
    
    // retorna os planos de conta que estão em uma conta a pagar do ano atual
    public function get_plano_conta_by_categoria($categoria_id)
    {
       $empresa_id = $this->session->userdata('empresa_id');
       $ano_atual = date('Y');
       $sql = "SELECT id, descricao "
               . " FROM tblfin_plano_contas  "
               . " where categoria_id = $categoria_id
                and deleted = 0 and empresa_id = $empresa_id order by descricao asc";
       //echo $sql; exit;
        $result = $this->db->query($sql)->result_array();
       return $result;
    }
    
    
    public function get_stats_chart_data($label, $where, $dataset_options, $year)
    {
        
        $chart = [
            'labels'   => [],
            'datasets' => [
                [
                    'label'       => $label,
                    'borderWidth' => 1,
                    'tension'     => false,
                    'data'        => [],
                ],
            ],
        ];

        foreach ($dataset_options as $key => $val) {
            $chart['datasets'][0][$key] = $val;
        }
       
        $categories = $this->get_categorias_contas_pagar();
        
        foreach ($categories as $category) {
           
            $categoria   = $category['categoria_id'];
            
            if (count($where) > 0) {
                foreach ($where as $key => $val) {
                    $_where[$key] = $this->db->escape_str($val);
                }
            }
            unset($_where['billable']);
            $valor = $this->get_valor_saidas_years($categoria, $year);
            $result =  $valor->valor; 
            array_push($chart['labels'], $category['categoria']);
            array_push($chart['datasets'][0]['data'], $result);
        }

        return $chart;
    }
    
    /*
     * FIM RELATÓRIO DE SAÍDAS
     */
    
    /*
     * RELATÓRIO DE ENTRADAS
     */
    public function get_entradas_years()
    {
        $empresa_id = $this->session->userdata('empresa_id');
        return $this->db->query('SELECT DISTINCT(YEAR(duedate)) as year FROM ' . db_prefix() . 'fin_invoices where empresa_id = '.$empresa_id.' and deleted = 0 ORDER by year DESC')->result_array();
    }
    
    // retorna as categorias que estão em uma conta a receber do ano atual
    public function get_categorias_invoices()
    {
       $empresa_id = $this->session->userdata('empresa_id');
       $sql = "";
       $ano_atual = date('Y');
        $sql = "SELECT distinct(categoria_id), c.name as categoria
                FROM tblfin_invoices i
                inner join tblfin_categories c on c.id = i.categoria_id
                where i.deleted = 0 and i.empresa_id = $empresa_id  and YEAR(i.duedate) = $ano_atual order by categoria asc";
        //echo $sql; exit;
        $result = $this->db->query($sql)->result_array();
       return $result;
    }
    
    // retorna os planos de conta que estão em uma conta a receber do ano atual
    public function get_plano_conta_entradas_by_categoria($categoria_id)
    {
       $empresa_id = $this->session->userdata('empresa_id');
       $ano_atual = date('Y');
       $sql = "SELECT distinct (pc.id), pc.descricao "
               . "FROM tblfin_invoices i "
               . " inner join tblfin_plano_contas pc on pc.id = i.plano_conta_id "
               . " where pc.categoria_id = $categoria_id
                and i.deleted = 0 and i.empresa_id = $empresa_id and YEAR(i.duedate) = $ano_atual order by pc.descricao asc";
       //echo $sql; exit;
        $result = $this->db->query($sql)->result_array();
       return $result;
    }
    
    /*
     * FIM REPORTS
     */
    
    /*
     * DASHBOARD
     */
    // 1 - TOTAL DE DESPESAS X RECEITAS
    public function get_expenses_vs_income_report($year = '')
    {
        $empresa_id = $this->session->userdata('empresa_id');
        $months_labels  = [];
        $total_expenses = [];
        $total_income   = [];
        $i              = 0;
        if (!is_numeric($year)) {
            $year = date('Y');
        }
        for ($m = 1; $m <= 12; $m++) {
            array_push($months_labels, _l(date('F', mktime(0, 0, 0, $m, 1))));
            $this->db->select('id')->from(db_prefix() . 'fin_lancamentos_parcelas')
                    ->where('MONTH(data_pagamento)', $m)
                    ->where('YEAR(data_pagamento)', $year)
                    ->where('status', 1)
                    ->where('deleted', 0)
                    ->where('empresa_id', $empresa_id);
            $expenses = $this->db->get()->result_array();
            if (!isset($total_expenses[$i])) {
                $total_expenses[$i] = [];
            }
            if (count($expenses) > 0) {
                foreach ($expenses as $expense) {
                    $expense = $this->get_parcela($expense['id']);
                     $total   = $expense->valor_parcela;
                    $total_expenses[$i][] = $total;
                }
            } else {
                $total_expenses[$i][] = 0;
            }
            $total_expenses[$i] = array_sum($total_expenses[$i]);
            
            // Calculate the income
            $this->db->select('amount');
            $this->db->from(db_prefix() . 'fin_invoicepaymentrecords');
            $this->db->join(db_prefix() . 'fin_invoices', '' . db_prefix() . 'fin_invoices.id = ' . db_prefix() . 'fin_invoicepaymentrecords.invoiceid');
            $this->db->where('MONTH(' . db_prefix() . 'fin_invoicepaymentrecords.date)', $m);
            $this->db->where('YEAR(' . db_prefix() . 'fin_invoicepaymentrecords.date)', $year);
            $this->db->where(db_prefix() . 'fin_invoicepaymentrecords.deleted', 0);
            $this->db->where(db_prefix() .'fin_invoicepaymentrecords.empresa_id', $empresa_id);
            $payments = $this->db->get()->result_array();
            if (!isset($total_income[$m])) {
                $total_income[$i] = [];
            }
            if (count($payments) > 0) {
                foreach ($payments as $payment) {
                    $total_income[$i][] = $payment['amount'];
                }
            } else {
                $total_income[$i][] = 0;
            }
            $total_income[$i] = array_sum($total_income[$i]);
            $i++;
        }
        $chart = [
            'labels'   => $months_labels,
            'datasets' => [
                [
                    'label'           => 'Entradas Realizadas',
                    'backgroundColor' => 'rgba(37,155,35,0.2)',
                    'borderColor'     => '#84c529',
                    'borderWidth'     => 1,
                    'tension'         => false,
                    'data'            => $total_income,
                ],
                [
                    'label'           => 'Saídas Realizadas',
                    'backgroundColor' => 'rgba(252,45,66,0.4)',
                    'borderColor'     => '#fc2d42',
                    'borderWidth'     => 1,
                    'tension'         => false,
                    'data'            => $total_expenses,
                ],
            ],
        ];

        return $chart;
    }
    
    // 2 - TOTAL DE ENTRADAS PREVISTAS X REALIZADAS
    public function get_income_previstas_vs_realizada_report($year = '')
    {
        $empresa_id = $this->session->userdata('empresa_id');
        $months_labels  = [];
        $total_expenses = [];
        $total_income   = [];
        $i              = 0;
        if (!is_numeric($year)) {
            $year = date('Y');
        }
        for ($m = 1; $m <= 12; $m++) {
            array_push($months_labels, _l(date('F', mktime(0, 0, 0, $m, 1))));
            $this->db->select('total');
            $this->db->from(db_prefix() . 'fin_invoices');
            $this->db->where('MONTH(duedate)', $m);
            $this->db->where('YEAR(duedate)', $year);
            $this->db->where('deleted', 0);
            $this->db->where('empresa_id', $empresa_id);
            $expenses = $this->db->get()->result_array();
           
            if (!isset($total_expenses[$i])) {
                $total_expenses[$i] = [];
            }
            if (count($expenses) > 0) {
                foreach ($expenses as $expense) {
                  $total_expenses[$i][] = $expense['total'];
                }
            } else {
                $total_expenses[$i][] = 0;
            }
            $total_expenses[$i] = array_sum($total_expenses[$i]);
            
            // Calculate the income
            $this->db->select('amount');
            $this->db->from(db_prefix() . 'fin_invoicepaymentrecords');
            $this->db->join(db_prefix() . 'fin_invoices', '' . db_prefix() . 'fin_invoices.id = ' . db_prefix() . 'fin_invoicepaymentrecords.invoiceid');
            $this->db->where('MONTH(' . db_prefix() . 'fin_invoicepaymentrecords.date)', $m);
            $this->db->where('YEAR(' . db_prefix() . 'fin_invoicepaymentrecords.date)', $year);
            $this->db->where('tblfin_invoicepaymentrecords.deleted', 0);
            $this->db->where('tblfin_invoicepaymentrecords.empresa_id', $empresa_id);
          
            $payments = $this->db->get()->result_array();
            if (!isset($total_income[$m])) {
                $total_income[$i] = [];
            }
            if (count($payments) > 0) {
                foreach ($payments as $payment) {
                    $total_income[$i][] = $payment['amount'];
                }
            } else {
                $total_income[$i][] = 0;
            }
            $total_income[$i] = array_sum($total_income[$i]);
            $i++;
        }
        $chart = [
            'labels'   => $months_labels,
            'datasets' => [
                [
                    'label'           => 'Realizadas',
                    'backgroundColor' => 'rgba(37,155,35,0.2)',
                    'borderColor'     => '#84c529',
                    'borderWidth'     => 1,
                    'tension'         => false,
                    'data'            => $total_income,
                ],
                [
                    'label'           => 'Previstas',
                    'backgroundColor' => 'rgba(112,219,219)',
                    'borderColor'     => '#70DBDB',
                    'borderWidth'     => 1,
                    'tension'         => false,
                    'data'            => $total_expenses,
                ],
            ],
        ];

        return $chart;
    }
    
    // 3 - TOTAL DE SAIDAS PREVISTAS X REALIZADAS
    public function get_expense_previstas_vs_realizada_report($year = '')
    {
        $empresa_id = $this->session->userdata('empresa_id');
        $months_labels  = [];
        $total_expenses = [];
        $total_income   = [];
        $i              = 0;
        if (!is_numeric($year)) {
            $year = date('Y');
        }
        for ($m = 1; $m <= 12; $m++) {
            array_push($months_labels, _l(date('F', mktime(0, 0, 0, $m, 1))));
            $this->db->select('id')->from(db_prefix() . 'fin_lancamentos_parcelas')
                    ->where('MONTH(data_vencimento)', $m)
                    ->where('YEAR(data_vencimento)', $year)
                    ->where('deleted', 0)
                    ->where('empresa_id', $empresa_id);
            $expenses = $this->db->get()->result_array();
            if (!isset($total_expenses[$i])) {
                $total_expenses[$i] = [];
            }
            if (count($expenses) > 0) {
                foreach ($expenses as $expense) {
                    $expense = $this->get_parcela($expense['id']);
                     $total   = $expense->valor_parcela;
                    $total_expenses[$i][] = $total;
                }
            } else {
                $total_expenses[$i][] = 0;
            }
            $total_expenses[$i] = array_sum($total_expenses[$i]);
            
            // saidas realizadas
            $this->db->select('id')->from(db_prefix() . 'fin_lancamentos_parcelas')
                    ->where('MONTH(data_pagamento)', $m)
                    ->where('YEAR(data_pagamento)', $year)
                    ->where('status', 1)
                    ->where('deleted', 0)
                    ->where('empresa_id', $empresa_id);
            $payments = $this->db->get()->result_array();
            if (!isset($total_income[$m])) {
                $total_income[$i] = [];
            }
            if (count($payments) > 0) {
                foreach ($payments as $payment) {
                    $expense = $this->get_parcela($payment['id']);
                    $total   = $expense->valor_parcela;
                    $total_income[$i][] = $total;
                }
            } else {
                $total_income[$i][] = 0;
            }
            $total_income[$i] = array_sum($total_income[$i]);
            $i++;
        }
        $chart = [
            'labels'   => $months_labels,
            'datasets' => [
                [
                    'label'           => 'Realizadas',
                    'backgroundColor' => 'rgba(37,155,35,0.2)',
                    'borderColor'     => '#84c529',
                    'borderWidth'     => 1,
                    'tension'         => false,
                    'data'            => $total_income,
                ],
                [
                    'label'           => 'Previstas',
                    'backgroundColor' => 'rgba(207,181,59)',
                    'borderColor'     => '#CFB53B',
                    'borderWidth'     => 1,
                    'tension'         => false,
                    'data'            => $total_expenses,
                ],
            ],
        ];

        return $chart;
    }
    
    // 4 - TOTAL DE DESPESAS REALIZADAS - GRÁFICO PIE
    public function get_total_expenses_realizado($year = '')
    {
        $empresa_id = $this->session->userdata('empresa_id');
        $months_labels  = [];
        $total_expenses = [];
        $total_income   = [];
        $soma_total_saidas = 0;
        $i              = 0;
        if (!is_numeric($year)) {
            $year = date('Y');
        }
       // for ($m = 1; $m <= 12; $m++) {
            array_push($months_labels, _l(date('F', mktime(0, 0, 0, $m, 1))));
            $this->db->select('id')->from(db_prefix() . 'fin_lancamentos_parcelas')
                  //  ->where('MONTH(data_vencimento)', $m)
                    ->where('YEAR(data_pagamento)', $year)
                    ->where('status', 1)
                    ->where('deleted', 0)
                    ->where('empresa_id', $empresa_id);
            $expenses = $this->db->get()->result_array();
            if (!isset($total_expenses[$i])) {
                $total_expenses[$i] = [];
            }
            if (count($expenses) > 0) {
                foreach ($expenses as $expense) {
                    $expense = $this->get_parcela($expense['id']);
                    $total   = $expense->valor_parcela;
                    $soma_total_saidas += $total;
                    $total_expenses[$i][] = $total;
                }
            } else {
                $total_expenses[$i][] = 0;
            }
            $total_expenses[$i] = array_sum($total_expenses[$i]);
            
            $i++;
        //}
        
           

        return $soma_total_saidas;
    }
    
    // 5 - TOTAL DE DESPESAS PREVISTAS - GRÁFICO PIE
    public function get_total_expenses_previsto($year = '')
    {
        $empresa_id = $this->session->userdata('empresa_id');
        $months_labels  = [];
        $total_expenses = [];
        $total_income   = [];
        $soma_total_saidas = 0;
        $i              = 0;
        if (!is_numeric($year)) {
            $year = date('Y');
        }
       // for ($m = 1; $m <= 12; $m++) {
            array_push($months_labels, _l(date('F', mktime(0, 0, 0, $m, 1))));
            $this->db->select('id')->from(db_prefix() . 'fin_lancamentos_parcelas')
                  //  ->where('MONTH(data_vencimento)', $m)
                    ->where('YEAR(data_vencimento)', $year)
                    //->where('status', 1)
                    ->where('deleted', 0)
                    ->where('empresa_id', $empresa_id);
            $expenses = $this->db->get()->result_array();
            if (!isset($total_expenses[$i])) {
                $total_expenses[$i] = [];
            }
            if (count($expenses) > 0) {
                foreach ($expenses as $expense) {
                    $expense = $this->get_parcela($expense['id']);
                    $total   = $expense->valor_parcela;
                    $soma_total_saidas += $total;
                    $total_expenses[$i][] = $total;
                }
            } else {
                $total_expenses[$i][] = 0;
            }
            $total_expenses[$i] = array_sum($total_expenses[$i]);
            
            $i++;
        //}
        
           

        return $soma_total_saidas;
    }
    
    // 6 - TOTAL DE ENTRADAS REALIZADAS - GRÁFICO PIE
    public function get_total_entradas_por_ano($year = '')
    {
        $empresa_id = $this->session->userdata('empresa_id');
        $months_labels  = [];
        $total_income = [];
         $soma_total_entradas = 0;
        $i              = 0;
        if (!is_numeric($year)) {
            $year = date('Y');
        }
       // for ($m = 1; $m <= 12; $m++) {
            array_push($months_labels, _l(date('F', mktime(0, 0, 0, $m, 1))));
            $this->db->select('amount');
            $this->db->from(db_prefix() . 'fin_invoicepaymentrecords');
            $this->db->join(db_prefix() . 'fin_invoices', '' . db_prefix() . 'fin_invoices.id = ' . db_prefix() . 'fin_invoicepaymentrecords.invoiceid');
           // $this->db->where('MONTH(' . db_prefix() . 'fin_invoicepaymentrecords.date)', $m);
            $this->db->where('YEAR(' . db_prefix() . 'fin_invoicepaymentrecords.date)', $year);
            $this->db->where(db_prefix() . 'fin_invoicepaymentrecords.deleted', 0);
            $this->db->where(db_prefix() .'fin_invoicepaymentrecords.empresa_id', $empresa_id);
            $payments = $this->db->get()->result_array();
            if (!isset($total_income[$i])) {
                $total_income[$i] = [];
            }
            if (count($payments) > 0) {
                foreach ($payments as $payment) {
                    $total_income[$i][] = $payment['amount'];
                    $soma_total_entradas += $payment['amount'];
                }
            } else {
                $total_income[$i][] = 0;
            }
            $total_income[$i] = array_sum($total_income[$i]);
            
            $i++;
        //}
        
           
          
        return $soma_total_entradas;
    }
    
    // 7 - TOTAL DE ENTRADAS PREVISTA - GRÁFICO PIE
    public function get_total_entradas_prevista($year = '')
    {
        $empresa_id = $this->session->userdata('empresa_id');
        $months_labels  = [];
        $total_income = [];
         $soma_total_entradas = 0;
        $i              = 0;
        if (!is_numeric($year)) {
            $year = date('Y');
        }
       // for ($m = 1; $m <= 12; $m++) {
            array_push($months_labels, _l(date('F', mktime(0, 0, 0, $m, 1))));
            $this->db->select('total');
            $this->db->from(db_prefix() . 'fin_invoices');
           // $this->db->where('MONTH(' . db_prefix() . 'fin_invoices.duedate)', $m);
            $this->db->where('YEAR(' . db_prefix() . 'fin_invoices.duedate)', $year);
            $this->db->where(db_prefix() . 'fin_invoices.deleted', 0);
            $this->db->where(db_prefix() .'fin_invoices.empresa_id', $empresa_id);
            $payments = $this->db->get()->result_array();
            if (!isset($total_income[$i])) {
                $total_income[$i] = [];
            }
            if (count($payments) > 0) {
                foreach ($payments as $payment) {
                    $total_income[$i][] = $payment['total'];
                    $soma_total_entradas += $payment['total'];
                }
            } else {
                $total_income[$i][] = 0;
            }
            $total_income[$i] = array_sum($total_income[$i]);
            
            $i++;
        //}
        
           
          
        return $soma_total_entradas;
    }
    
    // 8 - TOTAL SALDO - DASHBAORD
    public function get_saldo($year = '')
    {
        $empresa_id = $this->session->userdata('empresa_id');
        $saldo = 0;
        $soma_total_saidas = 0;
        $soma_total_entradas = 0;
        if (!is_numeric($year)) {
            $year = date('Y');
        }
        // SAIDAS
            $this->db->select('valor_parcela');
            $this->db->from(db_prefix() . 'fin_lancamentos_parcelas');
          
           
            $this->db->where('YEAR(data_pagamento)', $year);
            $this->db->where('deleted', 0);
            $this->db->where('status', 1);
            $this->db->where('empresa_id', $empresa_id);
            $expenses = $this->db->get()->result_array();
            
            if (count($expenses) > 0) {
                foreach ($expenses as $expense) {
                    $total   = $expense['valor_parcela'];
                    $soma_total_saidas += $total;
                }
            }
            
           
               
            
           
        // ENTRADAS
            $this->db->select('amount');
            $this->db->from(db_prefix() . 'fin_invoicepaymentrecords');
            $this->db->join(db_prefix() . 'fin_invoices', '' . db_prefix() . 'fin_invoices.id = ' . db_prefix() . 'fin_invoicepaymentrecords.invoiceid');
           
            $this->db->where('YEAR(' . db_prefix() . 'fin_invoicepaymentrecords.date)', $year);
            $this->db->where(db_prefix() . 'fin_invoicepaymentrecords.deleted', 0);
            $this->db->where(db_prefix() .'fin_invoicepaymentrecords.empresa_id', $empresa_id);
            $payments = $this->db->get()->result_array();
            
            if (count($payments) > 0) {
                foreach ($payments as $payment) {
                    $soma_total_entradas += $payment['amount'];
                }
            } 
            $saldo = $soma_total_entradas - $soma_total_saidas;
             if($m){
               //  echo $soma_total_saidas.'<Br>';
                // echo $soma_total_entradas.'<Br>';
                // echo $saldo; exit;
             }
        return $saldo;
    }
    
    // 8 - TOTAL SALDO - DASHBAORD
    public function get_saldo_mes($year = '', $m = '')
    {
        $empresa_id = $this->session->userdata('empresa_id');
        $saldo = 0;
        $soma_total_saidas = 0;
        $soma_total_entradas = 0;
        if (!is_numeric($year)) {
            $year = date('Y');
        }
        
        $where_mes = "";
        $cont = 1;
        for ($x=1; $x<=$m; $x++) {
            if($cont == $m){
                $where_mes .= "'$x'";
            }else{
                $where_mes .= "'$x',";
            }
         $cont++;   
        }
      
        // SAIDAS
        $sql_saida = "select sum(valor_parcela) as valor_parcela "
               . " FROM tblfin_lancamentos_parcelas "
               . " where month(data_pagamento) in ($where_mes)
                 and deleted = 0 
                 and status = 1 
                 and empresa_id = $empresa_id "
              . " and YEAR(data_pagamento) = $year";
       // echo $sql_saida.'<br>';
        $result = $this->db->query($sql_saida)->row();
        $soma_total_saidas = $result->valor_parcela;
       //  echo 'saida : '.$soma_total_saidas.'<Br>';  
                
            
           
        // ENTRADAS
         $sql = " select sum(amount) as amount "
               . " FROM tblfin_invoicepaymentrecords r"
               . " INNER JOIN tblfin_invoices i ON i.ID = r.invoiceid"
               . " where month(r.date) in ($where_mes)
                   and r.deleted = 0 
                   and r.empresa_id = $empresa_id "
               . " and YEAR(r.date) = $year";
            /*   
            $this->db->select('amount');
            $this->db->from(db_prefix() . 'fin_invoicepaymentrecords');
            $this->db->join(db_prefix() . 'fin_invoices', '' . db_prefix() . 'fin_invoices.id = ' . db_prefix() . 'fin_invoicepaymentrecords.invoiceid');
            $this->db->where_in('MONTH(' . db_prefix() . 'fin_invoicepaymentrecords.date)', $where_mes);
            $this->db->where('YEAR(' . db_prefix() . 'fin_invoicepaymentrecords.date)', $year);
            $this->db->where(db_prefix() . 'fin_invoicepaymentrecords.deleted', 0);
            $this->db->where(db_prefix() .'fin_invoicepaymentrecords.empresa_id', $empresa_id);
             * 
             */
            $payments = $this->db->query($sql)->row();
            $soma_total_entradas = $payments->amount;
             
            $saldo = $soma_total_entradas - $soma_total_saidas;
           // echo 'Entrada : '.$soma_total_entradas.'<Br>';
           // echo $saldo;
           // echo '<br><br>';
        return $saldo;
    }
    
    
    /************************************************************************
     **************************** SALDO ANTERIOS ******************************
     *************************************************************************/
    
     public function get_saldo_anterior($mes, $ano)
    {
       $empresa_id = $this->session->userdata('empresa_id');
       if($mes == 1){
           $mes = 12;
           $ano = $ano - 1;
       }
       
       $sql = "SELECT 'SALDO ANTERIOR' as saldo_anterior, SUM(i.total)  -
                (SELECT SUM(p.valor_parcela)
                from tblfin_lancamentos_parcelas p
                inner join tblfin_lancamentos l on l.id = p.titulo_id
                inner join tblfin_plano_contas pc on pc.id = l.plano_conta_id
                inner join tblfin_categories ct on ct.id = pc.categoria_id
                where p.deleted = 0 and p.empresa_id = $empresa_id and l.deleted = 0 and month(data_pagamento) = $mes and year(data_pagamento) = $ano) as valor

                from tblfin_invoices i
                inner join tblfin_categories ct on ct.id = i.categoria_id
                where i.deleted = 0 and month(duedate) = $mes and year(duedate) = $ano and i.empresa_id = $empresa_id";
      
        $result = $this->db->query($sql)->row();
       return $result;
    }
    
    
     public function get_saldo_inicial($mes, $ano)
    {
        
       $empresa_id = $this->session->userdata('empresa_id');
       $ano_atual = date('Y');
       $sql = "SELECT  m.descricao, m.tipo, b.banco, m.valor FROM tblfin_movimentacao_bancaria m
                inner join tblfin_bancos b on b.id = m.banco_id
            where m.deleted = 0 and m.empresa_id = $empresa_id and month(data_movimento) = $mes and year(data_movimento) = $ano ";
      
        $result = $this->db->query($sql)->result_array();
       return $result;
    }
    
    
    /*
     * FIM SALDO ANTERIOR
     */
    
    
    /*
     * FLUXO ENTRADAS
     */
    // RETORNA O VALOR TOTAL DE TODAS AS CATEGORIAS DE UM MES E UM ANO
    public function get_total_categoria_entrada_fluxo($mes, $ano)
    {
       $empresa_id = $this->session->userdata('empresa_id');
       $ano_atual = date('Y');
       $sql = "SELECT SUM(i.total) as valor
            from tblfin_invoices i
            inner join tblfin_invoicepaymentrecords r on r.invoiceid = i.id
            inner join tblfin_categories ct on ct.id = i.categoria_id
            where i.deleted = 0 and r.deleted = 0 and month(r.date) = $mes and year(r.date) = $ano and i.empresa_id = $empresa_id";
       //echo $sql; exit;
        $result = $this->db->query($sql)->row();
       return $result;
    }
    
    // RETORNA O VALOR TOTAL POR CATEGORIAS
    public function get_categoria_entrada_fluxo($mes, $ano)
    {
       $empresa_id = $this->session->userdata('empresa_id');
       $ano_atual = date('Y');
       $sql = "SELECT ct.id, ct.name as categoria, SUM(i.total) as valor
            from tblfin_invoices i
            inner join tblfin_invoicepaymentrecords r on r.invoiceid = i.id
            inner join tblfin_categories ct on ct.id = i.categoria_id
            where i.deleted = 0 and r.deleted = 0 and month(r.date) = $mes and year(r.date) = $ano and i.empresa_id = $empresa_id
            group by ct.id order by ct.name";
       //echo $sql; exit;
        $result = $this->db->query($sql)->result_array();
       return $result;
    }
    
    // RETORNA OS PLANOS DE CONAS
    public function get_plano_conta_entrada_fluxo_by_categoria($plano_id, $mes, $ano)
    {
       $empresa_id = $this->session->userdata('empresa_id');
       $ano_atual = date('Y');
       $sql = "SELECT pc.id, pc.descricao as plano, SUM(i.total) as valor
                from tblfin_invoices i
                inner join tblfin_plano_contas pc on pc.id = i.plano_conta_id
                inner join tblfin_invoicepaymentrecords r on r.invoiceid = i.id
                where i.deleted = 0 and r.deleted = 0 and i.empresa_id = $empresa_id and month(r.date) = $mes and year(r.date) = $ano and pc.categoria_id = $plano_id
                group by pc.id order by pc.descricao asc";
       
        $result = $this->db->query($sql)->result_array();
       return $result;
    }
    
    // retorna as faturas
    public function get_lancamentos_entradas_fluxo_by_plano_id($plano_id, $mes, $ano)
    {
       $empresa_id = $this->session->userdata('empresa_id');
       $ano_atual = date('Y');
       $sql = "SELECT i.id, i.duedate, r.date, c.company, i.adminnote as descricao,  i.total as valor
                from tblfin_invoicepaymentrecords r
                inner join tblfin_invoices i on i.id = r.invoiceid
                inner join tblfin_clients_financeiro c on c.id = i.clientid
                where i.deleted = 0 and r.deleted = 0 and i.empresa_id = $empresa_id and month(r.date) = $mes and year(r.date) = $ano and i.plano_conta_id = $plano_id
                order by r.date asc";
               // echo $sql; exit;
        $result = $this->db->query($sql)->result_array();
       return $result;
    }
    
    /*
     * FIM FLUXO ENTRADAS
     */
    
    /*
     * FLUXO SAIDAS
     */
    
    // RETORNA O VALOR TOTAL DE TODAS AS CATEGORIAS DE UM MES E UM ANO
    public function get_total_categoria_saida_fluxo($mes, $ano)
    {
       $empresa_id = $this->session->userdata('empresa_id');
       $ano_atual = date('Y');
       $sql = "SELECT  SUM(p.valor_parcela) as valor from tblfin_lancamentos_parcelas p
                inner join tblfin_lancamentos l on l.id = p.titulo_id
                inner join tblfin_plano_contas pc on pc.id = l.plano_conta_id
                inner join tblfin_categories ct on ct.id = pc.categoria_id
                where p.deleted = 0 and month(data_pagamento) = $mes and year(data_pagamento) = $ano and p.empresa_id = $empresa_id";
       //echo $sql; exit;
        $result = $this->db->query($sql)->row();
       return $result;
    }
    
    // RETORNA O VALOR TOTAL POR CATEGORIAS
    public function get_categoria_saida_fluxo($mes, $ano)
    {
        
       $empresa_id = $this->session->userdata('empresa_id');
       $ano_atual = date('Y');
       $sql = "SELECT ct.id as id, ct.name as categoria, SUM(p.valor_parcela) as valor from tblfin_lancamentos_parcelas p
                inner join tblfin_lancamentos l on l.id = p.titulo_id
                inner join tblfin_plano_contas pc on pc.id = l.plano_conta_id
                inner join tblfin_categories ct on ct.id = pc.categoria_id
                where p.deleted = 0 and month(data_pagamento) = $mes and year(data_pagamento) = $ano and p.empresa_id = $empresa_id
                group by ct.id order by ct.name";
       //echo $sql; exit;
        $result = $this->db->query($sql)->result_array();
       return $result;
    }
    
    // RETORNA OS PLANOS DE CONAS
    public function get_plano_conta_saida_fluxo_by_categoria($categoria_id, $mes, $ano)
    {
       $empresa_id = $this->session->userdata('empresa_id');
       $ano_atual = date('Y');
       $sql = "SELECT pc.id, pc.descricao as plano, SUM(p.valor_parcela) as valor
                from tblfin_lancamentos_parcelas p
                inner join tblfin_lancamentos l on l.id = p.titulo_id
                inner join tblfin_plano_contas pc on pc.id = l.plano_conta_id
                where p.deleted = 0 and month(data_pagamento) = $mes and year(data_pagamento) = $ano and p.empresa_id = $empresa_id and pc.categoria_id = $categoria_id
                group by pc.id order by pc.descricao asc";
       //echo $sql; exit;
        $result = $this->db->query($sql)->result_array();
       return $result;
    }
    
    
    // RETORNA OS PLANOS DE CONAS
    public function get_lancamentos_saida_fluxo_by_plano_id($plano_id, $mes, $ano)
    {
       $empresa_id = $this->session->userdata('empresa_id');
       $ano_atual = date('Y');
       $sql = "SELECT p.data_pagamento, l.complemento, p.valor_parcela from tblfin_lancamentos_parcelas p
                inner join tblfin_lancamentos l on l.id = p.titulo_id
                where p.deleted = 0 and l.deleted = 0 and p.empresa_id = $empresa_id and month(data_pagamento) = $mes and year(data_pagamento) = $ano and l.plano_conta_id = $plano_id
                order by p.data_pagamento asc";
       //echo $sql;// exit;
        $result = $this->db->query($sql)->result_array();
       return $result;
    }
    
    /*
     * FIM FLUXO SAIDAS
     */
}
