<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Doctors_model extends App_Model
{
   

    
    
    public function get_convenios()
    {
             $sql = 'SELECT distinct(convenio) as convenio FROM tbldoctors_visitas order by convenio asc';
                     
            $result = $this->db->query($sql)->result_array();

            return $result;
      
    }
    
        public function get_medicos()
    {
             $sql = 'SELECT distinct(medico) as medico FROM tbldoctors_visitas order by medico asc';
                     
            $result = $this->db->query($sql)->result_array();

            return $result;
      
    }
    
        public function get_tipo_atendimento()
    {
             $sql = 'SELECT distinct(tipo_atendimento) as tipo_atendimento FROM tbldoctors_visitas order by tipo_atendimento asc';
                     
            $result = $this->db->query($sql)->result_array();

            return $result;
      
    }
    
        public function get_ano_data_visita()
    {
             $sql = 'SELECT distinct(year(data_visita)) as ano_data_visita FROM tbldoctors_visitas order by ano_data_visita desc';
                     
            $result = $this->db->query($sql)->result_array();

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
