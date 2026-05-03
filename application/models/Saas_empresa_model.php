<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Gestão de empresas (tenants) do SaaS.
 * Trabalha sobre tblempresas + tbloptions + tblstaff.
 */
class Saas_empresa_model extends App_Model
{
    public function listar($filtros = [])
    {
        $this->db->select("e.id, e.company, e.cnpj, e.email, e.fone, e.active, e.dt_cadastro, e.deleted,
                           (SELECT COUNT(*) FROM tblstaff s WHERE s.empresa_id = e.id AND s.active = 1) AS total_staff,
                           (SELECT COUNT(*) FROM tblstaff s WHERE s.empresa_id = e.id AND s.admin = 1) AS total_admins,
                           (SELECT COUNT(*) FROM tblprojects p WHERE p.empresa_id = e.id AND p.deleted = 0) AS total_projects,
                           (SELECT COUNT(*) FROM tbl_atas a WHERE a.empresa_id = e.id AND a.deleted = 0) AS total_atas,
                           (SELECT COUNT(*) FROM tbl_intranet_documento d WHERE d.empresa_id = e.id AND d.deleted = 0) AS total_docs");
        $this->db->from('tblempresas e');

        if (empty($filtros['mostrar_excluidas'])) $this->db->where('(e.deleted IS NULL OR e.deleted = 0)', null, false);
        if (!empty($filtros['busca'])) {
            $term = $this->db->escape_like_str($filtros['busca']);
            $this->db->where("(e.company LIKE '%{$term}%' OR e.cnpj LIKE '%{$term}%' OR e.email LIKE '%{$term}%')", null, false);
        }
        if (isset($filtros['active']) && $filtros['active'] !== '') {
            $this->db->where('e.active', (int) $filtros['active']);
        }
        $this->db->order_by('e.id', 'asc');
        return $this->db->get()->result_array();
    }

    public function get($id)
    {
        return $this->db->where('id', (int) $id)->get('tblempresas')->row_array();
    }

    public function save($data, $id = null)
    {
        $clean = [
            'company'             => trim((string) ($data['company'] ?? '')),
            'company_full_name'   => $data['company_full_name'] ?? null,
            'cnpj'                => $data['cnpj'] ?? null,
            'email'               => $data['email'] ?? null,
            'fone'                => $data['fone'] ?? null,
            'fone2'               => $data['fone2'] ?? null,
            'website'             => $data['website'] ?? null,
            'address'             => $data['address'] ?? null,
            'endereco_numero'     => $data['endereco_numero'] ?? null,
            'endereco_compemento' => $data['endereco_compemento'] ?? null,
            'endereco_bairro'     => $data['endereco_bairro'] ?? null,
            'city'                => $data['city'] ?? null,
            'state'               => $data['state'] ?? null,
            'zip'                 => $data['zip'] ?? null,
            'country'             => !empty($data['country']) ? (int) $data['country'] : 32,
            'default_language'    => $data['default_language'] ?? 'portuguese_br',
            'active'              => !empty($data['active']) ? 1 : 0,
        ];

        if ($id) {
            $this->db->where('id', $id)->update('tblempresas', $clean);
            return (int) $id;
        }

        $clean['datecreated'] = date('Y-m-d H:i:s');
        $clean['dt_cadastro'] = date('Y-m-d');
        $clean['deleted']     = 0;
        $clean['registration_confirmed'] = 1;
        $clean['saas_tenant_id'] = 'app';
        $this->db->insert('tblempresas', $clean);
        $new_id = (int) $this->db->insert_id();

        if ($new_id) {
            $this->_seed_options($new_id, $clean['company']);
        }
        return $new_id;
    }

    public function delete($id)
    {
        return $this->db->where('id', (int) $id)->update('tblempresas', ['deleted' => 1]);
    }

    public function ativar($id, $active = true)
    {
        return $this->db->where('id', (int) $id)->update('tblempresas', ['active' => $active ? 1 : 0]);
    }

    /* ============ Opções padrão pra empresa nova ============ */

    private function _seed_options($empresa_id, $company)
    {
        // Cria options básicas: companyname, company_logo (vazio), nome_reduzido
        $opts = [
            ['name' => 'companyname',    'value' => $company,                'autoload' => 1],
            ['name' => 'company_logo',   'value' => '',                      'autoload' => 1],
            ['name' => 'nome_reduzido',  'value' => mb_strimwidth($company, 0, 20, ''), 'autoload' => 1],
        ];
        foreach ($opts as $opt) {
            // verifica se option global existe; se não, cria
            $option = $this->db->where('name', $opt['name'])->where('empresa_id', $empresa_id)
                ->get('tbloptions')->row();
            if (!$option) {
                $this->db->insert('tbloptions', [
                    'name'       => $opt['name'],
                    'value'      => $opt['value'],
                    'autoload'   => $opt['autoload'],
                    'empresa_id' => (int) $empresa_id,
                ]);
            }
        }
    }

    /* ============ Staff de uma empresa ============ */

    public function listar_staff($empresa_id)
    {
        return $this->db->select('staffid, firstname, lastname, email, admin, active, last_login')
            ->from('tblstaff')
            ->where('empresa_id', (int) $empresa_id)
            ->order_by('admin', 'desc')->order_by('firstname', 'asc')
            ->get()->result_array();
    }

    public function criar_staff($empresa_id, $dados)
    {
        $email = trim((string) ($dados['email'] ?? ''));
        if (!$email) return ['ok' => false, 'erro' => 'E-mail obrigatório'];

        $existe = $this->db->where('email', $email)->get('tblstaff')->row();
        if ($existe) return ['ok' => false, 'erro' => 'Já existe um staff com esse e-mail'];

        $senha = $dados['password'] ?? bin2hex(random_bytes(6));
        $hash = password_hash($senha, PASSWORD_DEFAULT);

        $this->db->insert('tblstaff', [
            'firstname'        => trim((string) ($dados['firstname'] ?? 'Admin')),
            'lastname'         => trim((string) ($dados['lastname'] ?? '')),
            'email'            => $email,
            'password'         => $hash,
            'datecreated'      => date('Y-m-d H:i:s'),
            'admin'            => !empty($dados['admin']) ? 1 : 0,
            'active'           => 1,
            'empresa_id'       => (int) $empresa_id,
            'is_not_staff'     => 0,
            'default_language' => 'portuguese_br',
        ]);
        $staffid = (int) $this->db->insert_id();

        return ['ok' => true, 'staffid' => $staffid, 'senha' => $senha];
    }

    public function reset_senha_staff($staffid, $nova_senha = null)
    {
        $senha = $nova_senha ?: bin2hex(random_bytes(6));
        $hash = password_hash($senha, PASSWORD_DEFAULT);
        $this->db->where('staffid', (int) $staffid)
            ->update('tblstaff', ['password' => $hash]);
        return $senha;
    }

    public function toggle_staff_active($staffid)
    {
        $row = $this->db->where('staffid', $staffid)->get('tblstaff')->row();
        if (!$row) return false;
        $this->db->where('staffid', $staffid)->update('tblstaff', ['active' => $row->active ? 0 : 1]);
        return true;
    }
}
