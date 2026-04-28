<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Sonata\GoogleAuthenticator\GoogleAuthenticator;
class Api_sigplus_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
    }

   
    public function get_login($email = '', $senha = '')
    {
        //$senha =  md5($senha);
        $sql = "SELECT s.staffid, s.hash, s.firstname, s.lastname, s.email
               FROM tblstaff s
               where s.email = '$email' and s.senha_app = '$senha' and s.deleted = 0 and s.active = 1";
       
       try {
           $result = $this->db->query($sql)->row();
           if($result){
               /*
               $dados = array(
                            'hash' => $result->hash,
                            'firstname' => $result->nome_profissional,
                            'lastname' => $result->nome_reduzido,
                            'email' => $result->email
                           );

              // $result = json_encode(array('success'=>true, 'result'=>$dados));*/
               return $result;

               }
        } catch (Exception $e) {
            echo 'ERRO: ',  $e->getMessage(), "\n";
        }
       
      
      
    }
    
    
    /*
     * RETORNA A EMPRESA DE UM USUARIO
     */
    public function get_staff_by_hash($hash = '')
    {
       
        $sql = "SELECT *
               FROM tblstaff 
               where staffid = '$hash' and deleted = 0";
        
       $result = $this->db->query($sql)->row();
      
       return $result;
      
    }
    
    /*
     * RETORNA OS DADOS DE UM MÉDICO BY CPF
     */
    public function get_medico_by_cpf($cpf = '', $empresa_id = '')
    {
       
        $sql = "SELECT *
               FROM tblmedicos 
               where cpf = '$cpf' and empresa_id = $empresa_id and deleted = 0";
        
       $result = $this->db->query($sql)->row();
      
       return $result;
      
    }
    
    /*
     * RETORNA A LISTA DE MÉDICOS ATIVOS DA EMPRESA
     */
    public function get_lista_medicos($empresa_id = '')
    {
       
       $sql = "SELECT nome_profissional, nome_reduzido, tipo_registro, codigo_registro, cpf FROM tblmedicos where empresa_id = $empresa_id and deleted = 0 and status = 'ATIVO'";
       $result = $this->db->query($sql)->result_array();
       
        if(count($result) > 0){
            foreach ($result as $res){
               $dados[] = array(
                            'nome_profissional' => $res['nome_profissional'],
                            'nome_reduzido'     => $res['nome_reduzido'],
                            'tipo_registro'     => $res['tipo_registro'],
                            'codigo_registro'   => $res['codigo_registro'],
                            'cpf'               => $res['cpf']
                           );
            }
               $result = json_encode(array('success'=>true, 'result'=>$dados));
               echo $result;
       
       }else{
           $result = json_encode(array('success'=>false));
       }
      
      
    }
    
    public function get_lista_medicos_by_cpf($empresa_id = '', $medico_cpf = '')
    {
       
       $sql = "SELECT * FROM tblmedicos where empresa_id = $empresa_id and deleted = 0 and status = 'ATIVO' and cpf = '$medico_cpf'";
       $result = $this->db->query($sql)->result_array();
       
        if(count($result) > 0){
            foreach ($result as $res){
               $dados[] = array(
                            'nome_profissional' => $res['nome_profissional'],
                            'nome_reduzido'     => $res['nome_reduzido'],
                            'tipo_registro'     => $res['tipo_registro'],
                            'codigo_registro'   => $res['codigo_registro'],
                            'cpf'               => $res['cpf'],
                            'cep'               => $res['cep'],
                            'endereco'          => $res['endereco'],
                            'numero'            => $res['numero'],
                            'bairro'            => $res['bairro'],
                            'complemento'       => $res['complemento'],
                            'cidade'            => $res['cidade'],
                            'uf'                => $res['uf']
                           );
            }
               $result = json_encode(array('success'=>true, 'result'=>$dados));
               echo $result;
       
       }else{
           $result = json_encode(array('success'=>false));
       }
      
      
    }
    
    /*
     * RETORNA A LISTA DE LOCAL DE TRABALHO DOS MÉDICOS ATIVOS DA EMPRESA
     */
    public function get_lista_medicos_local_trabalho($empresa_id = '')
    {
       
       $sql = "SELECT nome_profissional, nome_reduzido, tipo_registro, codigo_registro, cpf FROM tblmedicos where empresa_id = $empresa_id and deleted = 0 and status = 'ATIVO'";
       $result = $this->db->query($sql)->result_array();
       
        if(count($result) > 0){
            foreach ($result as $res){
               $dados[] = array(
                            'nome_profissional' => $res['nome_profissional'],
                            'nome_reduzido'     => $res['nome_reduzido'],
                            'tipo_registro'     => $res['tipo_registro'],
                            'codigo_registro'   => $res['codigo_registro'],
                            'cpf'               => $res['cpf']
                           );
            }
               $result = json_encode(array('success'=>true, 'result'=>$dados));
               echo $result;
       
       }else{
           $result = json_encode(array('success'=>false));
       }
      
      
    }
    
    /*
     * RETORNA A LISTA DE MÉDICOS ATIVOS DA EMPRESA
     */
    public function get_lista_unidades_hospitalares($empresa_id = '')
    {
       
       $sql = "SELECT id, razao_social as hospital, fantasia as nome_reduzido, endereco, bairro, cidade, uf, cnpj, telefone, latitude, longitude FROM tblunidades_hospitalares where empresa_id = $empresa_id and deleted = 0 and situacao = 'ativa'";
       $result = $this->db->query($sql)->result_array();
       
        if(count($result) > 0){
            foreach ($result as $res){
               $dados[] = array(
                            'id' => $res['id'],
                            'hospital'          => $res['hospital'],
                            'nome_reduzido'     => $res['nome_reduzido'],
                            'endereco'          => $res['endereco'],
                            'bairro'            => $res['bairro'],
                            'cidade'            => $res['cidade'],
                            'uf'                => $res['uf'],
                            'cnpj'              => $res['cnpj'],
                            'telefone'          => $res['telefone'],
                            'latitude'          => $res['latitude'],
                            'longitude'         => $res['longitude']
                           );
            }
               $result = json_encode(array('success'=>true, 'result'=>$dados));
               echo $result;
       
       }else{
           $result = json_encode(array('success'=>false));
       }
      
      
    }
    
    
    /*
     * RETORNA A ESCALA DO MÉDICO
     */
    
    public function get_escala_medico($empresa_id = '', $medico_cpf = '', $mes = '', $ano = '')
    {
       
       $sql = "SELECT e.eventid, e.start, e.end, dia_semana, e.status,
                e.titular_id, tit.nome_reduzido as titular_nome,
                e.medico_escalado_id, esc.nome_reduzido as escalado_nome,
                e.medico_plantonista_id, pla.nome_reduzido as plantonista_nome,
                e.medico_creditado, cred.nome_reduzido as creditado_nome,
                uh.razao_social as unidade, sm.nome as setor, e.mes, e.ano,
                uh.latitude, uh.longitude

                FROM tblevents e
                inner join tblmedicos tit on tit.medicoid = e.titular_id
                inner join tblmedicos esc on esc.medicoid = e.medico_escalado_id
                left join tblmedicos pla on pla.medicoid = e.medico_plantonista_id
                left join tblmedicos cred on cred.medicoid = e.medico_creditado
                inner join tblunidades_hospitalares uh on uh.id = e.unidade_id
                inner join tblsetores_medicos sm on sm.id = e.setor_id
                where e.empresa_id = $empresa_id and esc.cpf = $medico_cpf and e.deleted = 0 and e.mes = $mes and e.ano = $ano";
      
       $result = $this->db->query($sql)->result_array();
       
        if(count($result) > 0){
            foreach ($result as $res){
                $n_status = "";
                $status = $res['status'];
                if($status == 1){
                    $n_status = 'PRESENCA REGISTRADA';
                }else if($status == 0){
                    $n_status = 'PRESENCA NÃO REGISTRADA';
                }
               $dados[] = array(
                            'plantaoid'     => $res['eventid'],
                            'start'         => $res['start'],
                            'end'           => $res['end'],
                            'dia_semana'    => $res['dia_semana'],
                            'status'        => $n_status,
                            'titular'       => $res['titular_nome'],
                            'escalado'      => $res['escalado_nome'],  
                            'plantonista'   => $res['plantonista_nome'],
                            'creditado'     => $res['creditado_nome'],
                            'unidade'       => $res['unidade'],
                            'setor'         => $res['setor'],
                            'mes'           => $res['mes'],
                            'ano'           => $res['ano'],
                            'latitude'      => $res['latitude'],
                            'longitude'     => $res['longitude']
                           );
            }
               $result = json_encode(array('success'=>true, 'result'=>$dados));
               echo $result;
       
       }else{
           $result = json_encode(array('success'=>false));
       }
      
      
    }
    
    /*
     * RETORNA A ESCALA DETALHADA
     */
    
    public function get_escala_detalhes($empresa_id = '', $plantaoid = '')
    {
       
       $sql = "SELECT e.eventid, e.start, e.end, dia_semana, e.status,e.quantidade,
                e.titular_id, tit.nome_reduzido as titular_nome,
                e.medico_escalado_id, esc.nome_reduzido as escalado_nome,
                e.medico_plantonista_id, pla.nome_reduzido as plantonista_nome,
                e.medico_creditado, cred.nome_reduzido as creditado_nome,
                uh.razao_social as unidade, uh.endereco as endereco, uh.bairro as bairro, uh.cep as cep,
                uh.cnpj, sm.nome as setor, e.mes, e.ano,
                uh.latitude, uh.longitude

                FROM tblevents e
                inner join tblmedicos tit on tit.medicoid = e.titular_id
                inner join tblmedicos esc on esc.medicoid = e.medico_escalado_id
                left join tblmedicos pla on pla.medicoid = e.medico_plantonista_id
                left join tblmedicos cred on cred.medicoid = e.medico_creditado
                inner join tblunidades_hospitalares uh on uh.id = e.unidade_id
                inner join tblsetores_medicos sm on sm.id = e.setor_id
                where e.empresa_id = $empresa_id and e.eventid = $plantaoid";
      
       $result = $this->db->query($sql)->result_array();
       
        if(count($result) > 0){
            foreach ($result as $res){
                $n_status = "";
                $status = $res['status'];
                if($status == 1){
                    $n_status = 'PRESENCA REGISTRADA';
                }else if($status == 0){
                    $n_status = 'PRESENCA NÃO REGISTRADA';
                }
               $dados[] = array(
                            'plantaoid'     => $res['eventid'],
                            'start'         => $res['start'],
                            'end'           => $res['end'],
                            'dia_semana'    => $res['dia_semana'],
                            'status'        => $n_status,
                            'quantidade'    => $res['quantidade'],
                            'titular'       => $res['titular_nome'],
                            'escalado'      => $res['escalado_nome'],  
                            'plantonista'   => $res['plantonista_nome'],
                            'creditado'     => $res['creditado_nome'],
                            'unidade'       => $res['unidade'],
                            'endereco'      => $res['endereco'],
                            'bairro'        => $res['bairro'],
                            'cep'           => $res['cep'],
                            'cnpj'          => $res['cnpj'],
                            'setor'         => $res['setor'],
                            'mes'           => $res['mes'],
                            'ano'           => $res['ano'],
                            'latitude'      => $res['latitude'],
                            'longitude'     => $res['longitude']
                           );
            }
               $result = json_encode(array('success'=>true, 'result'=>$dados));
               echo $result;
       
       }else{
           $result = json_encode(array('success'=>false));
       }
      
      
    }
    
    /*
     * RETORNA OS ESCALADOS DE UMA UNIDADE HOSPITALAR PARA O DIA INFORMADO
     */
    
    public function get_escalados_unidade_hospitalar($empresa_id = '', $unidade = '', $dia = '', $mes = '', $ano = '')
    {
       
       $sql = "SELECT e.eventid, e.start, e.end, e.quantidade,
                e.medico_escalado_id, esc.nome_reduzido as escalado_nome

                FROM tblevents e
                inner join tblmedicos esc on esc.medicoid = e.medico_escalado_id
                where e.empresa_id = $empresa_id and e.unidade_id = $unidade and e.deleted = 0 and e.dia = $dia and e.mes = $mes and e.ano = $ano";
      
       $result = $this->db->query($sql)->result_array();
       
        if(count($result) > 0){
            foreach ($result as $res){
                
               $dados[] = array(
                            'eventid'         => $res['eventid'],
                            'start'         => $res['start'],
                            'end'           => $res['end'],
                            'escalado'      => $res['escalado_nome'],
                            'quantidade'      => $res['quantidade']
                           
                           );
            }
               $result = json_encode(array('success'=>true, 'result'=>$dados));
               echo $result;
       
       }else{
           $result = json_encode(array('success'=>false));
       }
      
      
    }
    
    /*
     * REGISTRA CHECKIN
     */
    public function registra_checkin($data)
    {
       
        $this->db->insert(db_prefix() . 'events_checkin', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
           return $insert_id;
        }else{
            return false;
        }

        
    }
    
    /*
     * ATUALIZA STATUS PLANTAO
     */
    public function atualiza_status_plantao($data, $id)
    {
       
        $this->db->where('eventid', $id);
        $this->db->update(db_prefix() . 'events', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        }

            return false;

        
    }
    
    
    
    /*
     * VALIDA CÓDIGO TOKEN
     */
    public function get_staff_token($token = '')
    {
        $hoje = date('Y-m-d h:i:s');
        $sql = "SELECT *
               FROM tblstaff 
               where token = '$token' and data_validade <= '$hoje'";
        
       $result = $this->db->query($sql)->row();
      
       if(count($result) > 0){
           $result = json_encode(array('success'=>true));
            echo $result;
       }else{
            $result = json_encode(array('success'=>false));
            echo $result;
       }
       
      
    }
    
    
    
    /*
     * ATUALIZA SENHA BY TOKEN
     */
    public function atualiza_senha_by_token_staff($data, $token)
    {
       
        $this->db->where('token', $token);
        $this->db->update(db_prefix() . 'staff', $data);
        if ($this->db->affected_rows() > 0) {
            $result = json_encode(array('success'=>true));
            echo $result;
        }

           $result = json_encode(array('success'=>false));
            echo $result;

        
    }
    
}
