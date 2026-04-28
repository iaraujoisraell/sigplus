<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Repasses_model extends App_Model
{
    
      public function __construct()
    {
        parent::__construct();
        
    //  $this->load->model('appointly_model');
    //   $this->load->model('invoices_model');

      
    }
    
    /**
     * Add new employee role
     * @param mixed $data
     */
    public function add($data)
    {
        $empresa_id = $this->session->userdata('empresa_id');
        unset($data['saldo']);
        $data['data_log']      = date('Y-m-d H:i:s');
        $data['usuario_log']   = get_staff_user_id();
        $data['status']       = 1;
        $data['empresa_id'] = $empresa_id;
       
        $valor = $data['valor'];
        $valor = str_replace(',','.', $valor);
        $data['valor']       = $valor;
       
        
         //print_r($data);
        //exit;
        $data = hooks()->apply_filters('new_repasse', $data);

        $this->db->insert(db_prefix() . 'caixas_repasses', $data);
        $insert_id = $this->db->insert_id();
        
        if ($insert_id) {
            hooks()->do_action('repasse_created', $insert_id, $data);

            return $insert_id;
        }

        return false;
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
        

        $this->db->where('medicoid', $id);
        $this->db->update(db_prefix() . 'medicos', $data);

        if ($this->db->affected_rows() > 0) {
            $affectedRows++;
        }

        

        if ($affectedRows > 0) {
            log_activity('Medico Updated [ID: ' . $id . ', Name: ' . $data['nome_profissional'] . ']');

            return true;
        }

        return false;
    }
    
    public function get_caixas_transferencia($id = '')
    {
         $sql = "SELECT *
                FROM tblcaixas r
                where r.id != $id";
       //echo $sql; exit;
       $result = $this->db->query($sql)->result_array();
        return $result;
    }

    /**
     * Get employee role by id
     * @param  mixed $id Optional role id
     * @return mixed     array if not id passed else object
     */
    public function get($id = '')
    {
        if (is_numeric($id)) {
            $this->db->where(db_prefix() . 'caixas.id', $id);
            $medico = $this->db->get(db_prefix() . 'caixas_repasses')->row();
            return $medico;
        }else{

         $sql = "SELECT r.*, f.nome as conta, c.name as caixa, pm.name as forma
            FROM tblcaixas_repasses r
            inner join tblconta_financeira f on f.id = r.conta_id
            inner join tblcaixas c on c.id = r.caixa_id
            inner join tblpayment_modes pm on pm.id = r.forma_id";
        //echo $sql; exit;
        $result = $this->db->query($sql)->result_array();
        return $result;
        
        }
    }
    
    // retorna as despesas registradas pelo caixa atual
    public function get_repasses_caixa_registro_atual($id = '')
    {
        $empresa_id = $this->session->userdata('empresa_id');
        $sql = "SELECT sum(valor) as valor, count(*) as quantidade FROM tblcaixas_repasses where registro_id = $id and empresa_id = $empresa_id ";
       
       $result = $this->db->query($sql)->row();
        return $result;
    }
    
    
    
    

    /**
     * Delete employee role
     * @param  mixed $id role id
     * @return mixed
     */
    public function delete($id)
    {
        $current = $this->get($id);

        // Check first if role is used in table
        if (is_reference_in_table('role', db_prefix() . 'staff', $id)) {
            return [
                'referenced' => true,
            ];
        }

        $affectedRows = 0;
        $this->db->where('roleid', $id);
        $this->db->delete(db_prefix() . 'roles');

        if ($this->db->affected_rows() > 0) {
            $affectedRows++;
        }

        if ($affectedRows > 0) {
            log_activity('Role Deleted [ID: ' . $id);

            return true;
        }

        return false;
    }

    public function get_contact_permissions($id)
    {
        $this->db->where('userid', $id);

        return $this->db->get(db_prefix() . 'contact_permissions')->result_array();
    }

    public function get_role_staff($role_id)
    {
        $this->db->where('role', $role_id);

        return $this->db->get(db_prefix() . 'staff')->result_array();
    }
}
