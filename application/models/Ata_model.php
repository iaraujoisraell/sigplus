<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Ata_model extends App_Model
{
    private $statuses = ['aberta', 'em_revisao', 'finalizada', 'cancelada'];

    public function get_statuses() { return $this->statuses; }

    public function get_status_label($status)
    {
        $labels = [
            'aberta' => 'Aberta',
            'em_revisao' => 'Em revisão',
            'finalizada' => 'Finalizada',
            'cancelada' => 'Cancelada',
        ];
        return $labels[$status] ?? ucfirst($status);
    }

    public function listar($filtros = [], $limit = null, $offset = null)
    {
        $empresa_id = (int) $this->session->userdata('empresa_id');
        $this->db->select('a.*, p.name AS project_name, CONCAT_WS(\' \', s.firstname, s.lastname) AS responsavel_nome');
        $this->db->from('tbl_atas a');
        $this->db->join('tblprojects p', 'p.id = a.project_id', 'left');
        $this->db->join('tblstaff s', 's.staffid = a.responsavel_id', 'left');
        $this->db->where('a.deleted', 0);
        $this->db->where('a.empresa_id', $empresa_id);

        if (!empty($filtros['project_id'])) $this->db->where('a.project_id', (int) $filtros['project_id']);
        if (!empty($filtros['status']))     $this->db->where('a.status', $filtros['status']);
        if (!empty($filtros['minha']))      $this->_where_minha('a');
        if (!empty($filtros['busca'])) {
            $term = $this->db->escape_like_str($filtros['busca']);
            $this->db->where("(a.titulo LIKE '%{$term}%' OR a.local LIKE '%{$term}%')", null, false);
        }

        $this->db->order_by('a.data', 'desc');
        $this->db->order_by('a.id', 'desc');
        if ($limit) $this->db->limit($limit, $offset ?: 0);

        return $this->db->get()->result_array();
    }

    public function count_minhas()
    {
        $empresa_id = (int) $this->session->userdata('empresa_id');
        $me = (int) get_staff_user_id();
        return (int) $this->db->query("SELECT COUNT(DISTINCT a.id) AS n
            FROM tbl_atas a
            LEFT JOIN tbl_atas_participantes p ON p.ata_id = a.id AND p.deleted = 0
            WHERE a.deleted = 0 AND a.empresa_id = $empresa_id
              AND (a.responsavel_id = $me OR a.user_create = $me OR p.staff_id = $me)")
            ->row()->n;
    }

    private function _where_minha($alias)
    {
        $me = (int) get_staff_user_id();
        $this->db->where("({$alias}.responsavel_id = $me OR {$alias}.user_create = $me OR EXISTS (
            SELECT 1 FROM tbl_atas_participantes pp WHERE pp.ata_id = {$alias}.id AND pp.staff_id = $me AND pp.deleted = 0
        ))", null, false);
    }

    public function get($id)
    {
        $empresa_id = (int) $this->session->userdata('empresa_id');
        return $this->db->select('a.*, p.name AS project_name, CONCAT_WS(\' \', s.firstname, s.lastname) AS responsavel_nome')
            ->from('tbl_atas a')
            ->join('tblprojects p', 'p.id = a.project_id', 'left')
            ->join('tblstaff s', 's.staffid = a.responsavel_id', 'left')
            ->where('a.id', $id)
            ->where('a.deleted', 0)
            ->where('a.empresa_id', $empresa_id)
            ->get()->row_array();
    }

    public function get_participantes($ata_id)
    {
        return $this->db->select('p.*, CONCAT_WS(\' \', s.firstname, s.lastname) AS staff_nome, s.email AS staff_email')
            ->from('tbl_atas_participantes p')
            ->join('tblstaff s', 's.staffid = p.staff_id', 'left')
            ->where('p.ata_id', $ata_id)
            ->where('p.deleted', 0)
            ->order_by('p.id', 'asc')
            ->get()->result_array();
    }

    public function get_decisoes($ata_id)
    {
        return $this->db->select('d.*, CONCAT_WS(\' \', s.firstname, s.lastname) AS responsavel_nome, t.name AS task_name, t.status AS task_status')
            ->from('tbl_atas_decisoes d')
            ->join('tblstaff s', 's.staffid = d.responsavel_id', 'left')
            ->join('tbltasks t', 't.id = d.task_id', 'left')
            ->where('d.ata_id', $ata_id)
            ->where('d.deleted', 0)
            ->order_by('d.posicao', 'asc')
            ->order_by('d.id', 'asc')
            ->get()->result_array();
    }

    public function save($data, $id = null)
    {
        $empresa_id = (int) $this->session->userdata('empresa_id');
        $me = (int) get_staff_user_id();

        $clean = [
            'project_id'     => !empty($data['project_id']) ? (int) $data['project_id'] : null,
            'titulo'         => trim((string) ($data['titulo'] ?? '')),
            'data'           => !empty($data['data']) ? $data['data'] : null,
            'hora_inicio'    => !empty($data['hora_inicio']) ? $data['hora_inicio'] : null,
            'hora_fim'       => !empty($data['hora_fim']) ? $data['hora_fim'] : null,
            'local'          => !empty($data['local']) ? trim((string) $data['local']) : null,
            'responsavel_id' => !empty($data['responsavel_id']) ? (int) $data['responsavel_id'] : null,
            'pauta'          => $data['pauta'] ?? null,
            'discussoes'     => $data['discussoes'] ?? null,
            'observacoes'    => $data['observacoes'] ?? null,
            'status'         => in_array($data['status'] ?? '', $this->statuses, true) ? $data['status'] : 'aberta',
        ];

        if ($id) {
            $clean['user_update'] = $me;
            $clean['dt_updated']  = date('Y-m-d H:i:s');
            $this->db->where('id', $id)->where('empresa_id', $empresa_id)->update('tbl_atas', $clean);
            return (int) $id;
        }

        $clean['empresa_id']  = $empresa_id;
        $clean['user_create'] = $me;
        $clean['dt_created']  = date('Y-m-d H:i:s');
        $this->db->insert('tbl_atas', $clean);
        return (int) $this->db->insert_id();
    }

    public function save_participantes($ata_id, $list)
    {
        $this->db->where('ata_id', $ata_id)->update('tbl_atas_participantes', ['deleted' => 1]);
        if (empty($list) || !is_array($list)) return;

        foreach ($list as $p) {
            $row = [
                'ata_id'      => (int) $ata_id,
                'tipo'        => ($p['tipo'] ?? 'interno') === 'externo' ? 'externo' : 'interno',
                'staff_id'    => !empty($p['staff_id']) ? (int) $p['staff_id'] : null,
                'nome'        => !empty($p['nome']) ? trim((string) $p['nome']) : null,
                'email'       => !empty($p['email']) ? trim((string) $p['email']) : null,
                'organizacao' => !empty($p['organizacao']) ? trim((string) $p['organizacao']) : null,
                'presente'    => isset($p['presente']) ? (int) $p['presente'] : null,
                'deleted'     => 0,
            ];
            if ($row['tipo'] === 'interno' && !$row['staff_id']) continue;
            if ($row['tipo'] === 'externo' && !$row['nome']) continue;
            $this->db->insert('tbl_atas_participantes', $row);
        }
    }

    public function save_decisoes($ata_id, $list, $project_id = null, $criar_tasks = false)
    {
        $existentes = $this->db->select('id, task_id')->from('tbl_atas_decisoes')
            ->where('ata_id', $ata_id)->where('deleted', 0)->get()->result_array();
        $manter = [];

        if (!empty($list) && is_array($list)) {
            foreach ($list as $idx => $d) {
                $descricao = trim((string) ($d['descricao'] ?? ''));
                if ($descricao === '') continue;

                $row = [
                    'ata_id'         => (int) $ata_id,
                    'descricao'      => $descricao,
                    'responsavel_id' => !empty($d['responsavel_id']) ? (int) $d['responsavel_id'] : null,
                    'prazo'          => !empty($d['prazo']) ? $d['prazo'] : null,
                    'status'         => $d['status'] ?? 'aberta',
                    'posicao'        => $idx,
                    'deleted'        => 0,
                ];

                $id = !empty($d['id']) ? (int) $d['id'] : 0;
                if ($id) {
                    $this->db->where('id', $id)->update('tbl_atas_decisoes', $row);
                    $manter[] = $id;
                } else {
                    $this->db->insert('tbl_atas_decisoes', $row);
                    $new_id = (int) $this->db->insert_id();
                    $manter[] = $new_id;

                    if ($criar_tasks && !empty($d['gerar_task'])) {
                        $task_id = $this->_criar_task_de_decisao($new_id, $row, $project_id, $ata_id);
                        if ($task_id) {
                            $this->db->where('id', $new_id)->update('tbl_atas_decisoes', ['task_id' => $task_id]);
                        }
                    }
                }
            }
        }

        // Soft delete dos que sumiram
        foreach ($existentes as $e) {
            if (!in_array((int) $e['id'], $manter, true)) {
                $this->db->where('id', $e['id'])->update('tbl_atas_decisoes', ['deleted' => 1]);
            }
        }
    }

    private function _criar_task_de_decisao($decisao_id, $row, $project_id, $ata_id)
    {
        $name = mb_substr($row['descricao'], 0, 250);
        $task = [
            'name'        => $name,
            'description' => $row['descricao'],
            'priority'    => 3,
            'startdate'   => date('Y-m-d'),
            'duedate'     => $row['prazo'] ?: null,
            'dateadded'   => date('Y-m-d H:i:s'),
            'addedfrom'   => get_staff_user_id(),
            'status'      => 1,
            'rel_id'      => $project_id ?: null,
            'rel_type'    => $project_id ? 'project' : null,
            'ataid'       => (int) $ata_id,
            'empresa_id'  => (int) $this->session->userdata('empresa_id'),
        ];
        $this->db->insert('tbltasks', $task);
        $task_id = (int) $this->db->insert_id();

        if ($task_id && !empty($row['responsavel_id'])) {
            $this->db->insert('tbltask_assigned', [
                'taskid' => $task_id,
                'staffid' => (int) $row['responsavel_id'],
                'assigned_from' => get_staff_user_id(),
            ]);
        }
        return $task_id;
    }

    public function gerar_task_decisao($decisao_id)
    {
        $d = $this->db->where('id', $decisao_id)->where('deleted', 0)->get('tbl_atas_decisoes')->row_array();
        if (!$d || $d['task_id']) return $d['task_id'] ?? null;
        $ata = $this->get($d['ata_id']);
        if (!$ata) return null;
        $task_id = $this->_criar_task_de_decisao($decisao_id, $d, $ata['project_id'] ?? null, $d['ata_id']);
        if ($task_id) {
            $this->db->where('id', $decisao_id)->update('tbl_atas_decisoes', ['task_id' => $task_id]);
        }
        return $task_id;
    }

    public function delete($id)
    {
        $empresa_id = (int) $this->session->userdata('empresa_id');
        return $this->db->where('id', $id)->where('empresa_id', $empresa_id)->update('tbl_atas', ['deleted' => 1]);
    }
}
