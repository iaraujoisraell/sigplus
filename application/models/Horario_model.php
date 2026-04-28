<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Horario_model extends App_Model
{
    
    public function get_horario($id = '')
    {
     
       $empresa_id = $this->session->userdata('empresa_id');
       
       $sql = "";
       
       
       $sql = 'SELECT *
                FROM tblhorario_plantao
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
    
    /*
     * REtorna os horários permitidos para quebrar
     */
    public function get_horario_quebrar($id = '')
    {
     
       $empresa_id = $this->session->userdata('empresa_id');
       
       $sql = "";
       
       
       $sql = 'SELECT *
                FROM tblhorario_plantao
                where deleted = 0 and quebrar = 1 and empresa_id = '.$empresa_id;
       
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
    
    
    /**
     * Add new employee role
     * @param mixed $data
     */
    public function add_edit_horario($data)
    {
       
        $empresa_id = $this->session->userdata('empresa_id');
        $hoje = date('Y-m-d H:i:s');
        $id_registro = $data['id'];
       
        if($id_registro){
            $plantao = $data['plantao'];
            $plantao = str_replace(',','.', $plantao);
            $data['plantao']             = $plantao;
            $data['user_ultima_alteracao']   = get_staff_user_id();
            $data['data_ultima_alteracao']   = $hoje;
              unset($data['id']);
            //print_R($data); exit;
             $this->db->where('id', $id_registro);
            $this->db->update(db_prefix() . 'horario_plantao', $data);
            log_activity('HORÁRIO PLANTÃO updated [ ID : '.$id_registro.']', null, $data);
        
                
       
            hooks()->do_action('fin_lancamentos', $id_registro, $data);
            return true;
        }else{
            
            $plantao = $data['plantao'];
            $plantao = str_replace(',','.', $plantao);
            $data['plantao']             = $plantao;
            
            $data['empresa_id']             = $empresa_id;
            $data['user_cadastro']          = get_staff_user_id();
            $data['data_cadastro']          = $hoje;
            $data['user_ultima_alteracao']  = get_staff_user_id();
            $data['data_ultima_alteracao']  = $hoje;
            
            unset($data['id']);
           
            $this->db->insert(db_prefix() . 'horario_plantao', $data);
            $id_registro = $this->db->insert_id();
            
            
            log_activity('HORÁRIO PLANTÃO insert [ ID : '.$id_registro.']', null, $data);
        
            
       
            hooks()->do_action('fin_lancamentos', $id_registro, $data);
            return true;
            
        }
        
           return false;
    }
    
    
    public function delete($data, $id_registro)
    {
        
        $this->db->where('id', $id_registro);
        $this->db->update(db_prefix() . 'horario_plantao', $data);
        
        log_activity('HORÁRIO PLANTÃO deleted [ ID : '.$id_registro.']', null, $data);
        if ($this->db->affected_rows() > 0) {
            hooks()->do_action('Conta Pagar deletado', $id_registro, $data);
            return true;
        }
        return false;
    }
}
