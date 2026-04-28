<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Contas_financeiras_model extends App_Model
{
    /**
     * Add new employee role
     * @param mixed $data
     */
    public function add($data)
    {
        $data['datecreated'] = date('Y-m-d H:i:s');
       
        $this->db->insert(db_prefix() . 'medicos', $data);
        $insert_id = $this->db->insert_id();

        if ($insert_id) {
            log_activity('New medico Added [ID: ' . $insert_id . '.' . $data['nome_profissional'] . ']');

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

    /**
     * Get employee role by id
     * @param  mixed $id Optional role id
     * @return mixed     array if not id passed else object
     */
    public function get($id = '')
    {
        if (is_numeric($id)) {
            $this->db->where(db_prefix() . 'conta_financeira.id', $id);
            $medico = $this->db->get(db_prefix() . 'conta_financeira')->row();
            return $medico;
        }else{

        $this->db->order_by('nome', 'asc');
        return $this->db->get(db_prefix() . 'conta_financeira')->result_array();
        
        }
    }

    
    public function get_medico_id($id = '')
    {
        if (is_numeric($id)) {
            $this->db->where(db_prefix() . 'conta_financeira.medico_id', $id);
            $medico = $this->db->get(db_prefix() . 'conta_financeira')->row();
            return $medico;
        }
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
