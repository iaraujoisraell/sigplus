<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Profile_type_intranet_model extends App_Model {

    /**
     * Add new employee role
     * @param mixed $data
     */
    public function add($data) {
        $permissions = [];
        if (isset($data['permissions'])) {
            $permissions = $data['permissions'];
        }

        $data['permissions'] = serialize($permissions);
        $data['empresa_id'] = $this->session->userdata('empresa_id');

        $this->db->insert(db_prefix() . '_intranet_profile_types', $data);
        $insert_id = $this->db->insert_id();

        if ($insert_id) {
            log_activity('New Role Intranet Added [ID: ' . $insert_id . '.' . $data['name'] . ']');

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
    public function update($data, $id) {
        $affectedRows = 0;
        $permissions = [];
        if (isset($data['permissions'])) {
            $permissions = $data['permissions'];
        }

        $data['permissions'] = serialize($permissions);

        $update_staff_permissions = false;
        if (isset($data['update_staff_permissions'])) {
            $update_staff_permissions = true;
            unset($data['update_staff_permissions']);
        }
        
        $permissions_old = $this->get($id);
        $permissions_old = $permissions_old->permissions;
       
        

        $this->db->where('id', $id);
        $this->db->update(db_prefix() . '_intranet_profile_types', $data);

        if ($this->db->affected_rows() > 0) {
            $affectedRows++;

            if ($update_staff_permissions == true) {
                $this->load->model('staff_model');

                $staff = $this->staff_model->get('', [
                    'intranet_profile_type_id' => $id,
                ]);

                foreach ($staff as $member) {
                    if ($this->staff_model->update_permissions_intranet($permissions, $member['staffid'], $permissions_old)) {
                        $affectedRows++;
                    }
                }
            }
        }



        if ($affectedRows > 0) {
            log_activity('Profile Type Updated [ID: ' . $id . ', Name: ' . $data['name'] . ']');

            return true;
        }

        return false;
    }

    /**
     * Get employee role by id
     * @param  mixed $id Optional role id
     * @return mixed     array if not id passed else object
     */
    public function get($id = '') {
        if (is_numeric($id)) {

            $role = $this->app_object_cache->get('intranet_profile_type-' . $id);

            if ($role) {
                return $role;
            }

            $this->db->where('id', $id);

            $role = $this->db->get(db_prefix() . '_intranet_profile_types')->row();
            $role->permissions = !empty($role->permissions) ? unserialize($role->permissions) : [];

            $this->app_object_cache->add('intranet_profile_type-' . $id, $role);

            return $role;
        }
        $this->db->where('deleted', 0);
        $this->db->where('empresa_id', $this->session->userdata('empresa_id'));

        return $this->db->get(db_prefix() . '_intranet_profile_types')->result_array();
    }

    /**
     * Delete employee role
     * @param  mixed $id role id
     * @return mixed
     */
    public function delete($id) {
        $current = $this->get($id);

        // Check first if role is used in table
        if (is_reference_in_table('intranet_profile_type_id', db_prefix() . 'staff', $id)) {
            return [
                'referenced' => true,
            ];
        }

        $affectedRows = 0;
        $this->db->where('id', $id);

        $this->db->delete(db_prefix() . '_intranet_profile_types');

        if ($this->db->affected_rows() > 0) {
            $affectedRows++;
        }

        if ($affectedRows > 0) {
            log_activity('Profile Type Deleted [ID: ' . $id);

            return true;
        }

        return false;
    }

    public function get_contact_permissions($id) {
        $this->db->where('userid', $id);

        return $this->db->get(db_prefix() . 'contact_permissions')->result_array();
    }

    public function get_role_staff($role_id) {
        $this->db->where('role', $role_id);

        return $this->db->get(db_prefix() . 'staff')->result_array();
    }
}
