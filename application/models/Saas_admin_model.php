<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Modelo de super-admins do painel SaaS — autenticação isolada do staff Perfex.
 */
class Saas_admin_model extends App_Model
{
    public function autenticar($email, $senha)
    {
        $row = $this->db->where('email', trim($email))
            ->where('active', 1)
            ->get('tbl_saas_admins')->row_array();
        if (!$row) return false;
        if (!password_verify($senha, $row['password'])) return false;

        $this->db->where('id', $row['id'])->update('tbl_saas_admins', [
            'last_login' => date('Y-m-d H:i:s'),
            'last_ip'    => $this->input->ip_address(),
        ]);
        return $row;
    }

    public function get($id)
    {
        return $this->db->where('id', (int) $id)->get('tbl_saas_admins')->row_array();
    }

    public function listar()
    {
        return $this->db->order_by('nome', 'asc')->get('tbl_saas_admins')->result_array();
    }

    public function save($data, $id = null)
    {
        $clean = [
            'nome'   => trim((string) ($data['nome'] ?? '')),
            'email'  => trim((string) ($data['email'] ?? '')),
            'active' => !empty($data['active']) ? 1 : 0,
        ];
        if (!empty($data['password'])) {
            $clean['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        if ($id) {
            $this->db->where('id', $id)->update('tbl_saas_admins', $clean);
            return (int) $id;
        }
        if (empty($clean['password'])) return false;
        $clean['created_at'] = date('Y-m-d H:i:s');
        $this->db->insert('tbl_saas_admins', $clean);
        return (int) $this->db->insert_id();
    }
}
