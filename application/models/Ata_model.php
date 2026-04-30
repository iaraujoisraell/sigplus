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

        $this->_where_visivel('a');

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

    /** Aplica filtro de visibilidade: só atas que o user pode ver */
    private function _where_visivel($alias)
    {
        if (is_admin()) return;
        $me = (int) get_staff_user_id();
        $this->db->where("({$alias}.visibilidade = 'publica'
            OR {$alias}.responsavel_id = $me
            OR {$alias}.user_create = $me
            OR EXISTS (
                SELECT 1 FROM tbl_atas_participantes pp
                WHERE pp.ata_id = {$alias}.id AND pp.staff_id = $me AND pp.deleted = 0
            ))", null, false);
    }

    public function pode_visualizar($ata, $staff_id = null)
    {
        if (is_admin()) return true;
        $staff_id = $staff_id ?? (int) get_staff_user_id();
        if ((int) ($ata['responsavel_id'] ?? 0) === $staff_id) return true;
        if ((int) ($ata['user_create'] ?? 0) === $staff_id) return true;
        if (($ata['visibilidade'] ?? 'publica') === 'publica') return true;

        $count = $this->db->where('ata_id', (int) $ata['id'])
            ->where('staff_id', $staff_id)
            ->where('deleted', 0)
            ->count_all_results('tbl_atas_participantes');
        return $count > 0;
    }

    public function pode_editar($ata, $staff_id = null)
    {
        if (is_admin()) return true;
        if (!has_permission_intranet('atas', '', 'edit')) return false;
        $staff_id = $staff_id ?? (int) get_staff_user_id();
        return (int) ($ata['responsavel_id'] ?? 0) === $staff_id
            || (int) ($ata['user_create'] ?? 0) === $staff_id;
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
            'visibilidade'   => in_array($data['visibilidade'] ?? '', ['publica', 'restrita'], true) ? $data['visibilidade'] : 'publica',
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

    /**
     * Aceita 3 grupos:
     *  - participantes (staff internos presentes)
     *  - convidados   (externos com nome/email/org)
     *  - visualizadores (staff internos que podem ver mas não estiveram)
     */
    public function save_pessoas($ata_id, $participantes = [], $convidados = [], $visualizadores = [])
    {
        $this->db->where('ata_id', $ata_id)->update('tbl_atas_participantes', ['deleted' => 1]);

        $insert = function ($tipo, $row) use ($ata_id) {
            $row['ata_id']  = (int) $ata_id;
            $row['tipo']    = $tipo;
            $row['deleted'] = 0;
            $this->db->insert('tbl_atas_participantes', $row);
        };

        foreach ((array) $participantes as $p) {
            $sid = !empty($p['staff_id']) ? (int) $p['staff_id'] : 0;
            if ($sid <= 0) continue;
            $insert('participante', [
                'staff_id' => $sid,
                'presente' => isset($p['presente']) ? (int) $p['presente'] : 1,
            ]);
        }

        foreach ((array) $convidados as $c) {
            $nome = !empty($c['nome']) ? trim((string) $c['nome']) : '';
            if ($nome === '') continue;
            $insert('convidado', [
                'staff_id'    => null,
                'nome'        => $nome,
                'email'       => !empty($c['email']) ? trim((string) $c['email']) : null,
                'organizacao' => !empty($c['organizacao']) ? trim((string) $c['organizacao']) : null,
            ]);
        }

        foreach ((array) $visualizadores as $v) {
            $sid = !empty($v['staff_id']) ? (int) $v['staff_id'] : 0;
            if ($sid <= 0) continue;
            $insert('visualizador', ['staff_id' => $sid]);
        }
    }

    public function get_pessoas($ata_id)
    {
        $rows = $this->db->select('p.*, CONCAT_WS(\' \', s.firstname, s.lastname) AS staff_nome, s.email AS staff_email, s.cargo AS staff_cargo')
            ->from('tbl_atas_participantes p')
            ->join('tblstaff s', 's.staffid = p.staff_id', 'left')
            ->where('p.ata_id', $ata_id)
            ->where('p.deleted', 0)
            ->order_by('p.id', 'asc')
            ->get()->result_array();

        $result = ['participantes' => [], 'convidados' => [], 'visualizadores' => []];
        foreach ($rows as $r) {
            $key = $r['tipo'] === 'convidado' ? 'convidados'
                 : ($r['tipo'] === 'visualizador' ? 'visualizadores' : 'participantes');
            $result[$key][] = $r;
        }
        return $result;
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
