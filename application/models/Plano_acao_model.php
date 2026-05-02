<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Plano_acao_model extends App_Model
{
    private $statuses = ['aberto', 'em_execucao', 'concluido', 'cancelado', 'atrasado'];
    private $metodologias = ['5w2h', 'ishikawa', 'ambos'];

    public function get_statuses() { return $this->statuses; }
    public function get_metodologias() { return $this->metodologias; }

    public function get_status_label($status)
    {
        $labels = [
            'aberto' => 'Aberto',
            'em_execucao' => 'Em execução',
            'concluido' => 'Concluído',
            'cancelado' => 'Cancelado',
            'atrasado' => 'Atrasado',
        ];
        return $labels[$status] ?? ucfirst($status);
    }

    public function listar($filtros = [], $limit = null)
    {
        $empresa_id = (int) $this->session->userdata('empresa_id');
        $this->db->select('pa.*, p.name AS project_name, a.titulo AS ata_titulo,
                           CONCAT_WS(\' \', s.firstname, s.lastname) AS responsavel_nome');
        $this->db->from('tbl_planos_acao pa');
        $this->db->join('tblprojects p', 'p.id = pa.project_id', 'left');
        $this->db->join('tbl_atas a', 'a.id = pa.ata_id', 'left');
        $this->db->join('tblstaff s', 's.staffid = pa.responsavel_id', 'left');
        $this->db->where('pa.deleted', 0);
        $this->db->where('pa.empresa_id', $empresa_id);

        if (!empty($filtros['project_id'])) $this->db->where('pa.project_id', (int) $filtros['project_id']);
        if (!empty($filtros['ata_id']))     $this->db->where('pa.ata_id', (int) $filtros['ata_id']);
        if (!empty($filtros['status']))     $this->db->where('pa.status', $filtros['status']);
        if (!empty($filtros['minha'])) {
            $me = (int) get_staff_user_id();
            $this->db->where("(pa.responsavel_id = $me OR pa.user_create = $me)", null, false);
        }
        if (!empty($filtros['busca'])) {
            $term = $this->db->escape_like_str($filtros['busca']);
            $this->db->where("(pa.titulo LIKE '%{$term}%' OR pa.descricao LIKE '%{$term}%')", null, false);
        }

        $this->db->order_by('pa.id', 'desc');
        if ($limit) $this->db->limit($limit);

        return $this->db->get()->result_array();
    }

    public function count_meus()
    {
        $empresa_id = (int) $this->session->userdata('empresa_id');
        $me = (int) get_staff_user_id();
        return (int) $this->db->query("SELECT COUNT(*) AS n FROM tbl_planos_acao
            WHERE deleted = 0 AND empresa_id = $empresa_id
            AND (responsavel_id = $me OR user_create = $me)")->row()->n;
    }

    public function get($id)
    {
        $empresa_id = (int) $this->session->userdata('empresa_id');
        return $this->db->select('pa.*, p.name AS project_name, a.titulo AS ata_titulo,
                                  CONCAT_WS(\' \', s.firstname, s.lastname) AS responsavel_nome')
            ->from('tbl_planos_acao pa')
            ->join('tblprojects p', 'p.id = pa.project_id', 'left')
            ->join('tbl_atas a', 'a.id = pa.ata_id', 'left')
            ->join('tblstaff s', 's.staffid = pa.responsavel_id', 'left')
            ->where('pa.id', $id)
            ->where('pa.deleted', 0)
            ->where('pa.empresa_id', $empresa_id)
            ->get()->row_array();
    }

    public function get_5w2h($plano_id)
    {
        return $this->db->select('w.*, CONCAT_WS(\' \', s.firstname, s.lastname) AS who_nome,
                                  t.name AS task_name, t.status AS task_status')
            ->from('tbl_planos_acao_5w2h w')
            ->join('tblstaff s', 's.staffid = w.who_id', 'left')
            ->join('tbltasks t', 't.id = w.task_id', 'left')
            ->where('w.plano_id', $plano_id)
            ->where('w.deleted', 0)
            ->order_by('w.posicao', 'asc')
            ->order_by('w.id', 'asc')
            ->get()->result_array();
    }

    public function get_ishikawa($plano_id)
    {
        return $this->db->where('plano_id', $plano_id)
            ->get('tbl_planos_acao_ishikawa')->row_array();
    }

    public function save($data, $id = null)
    {
        $empresa_id = (int) $this->session->userdata('empresa_id');
        $me = (int) get_staff_user_id();

        $clean = [
            'project_id'     => !empty($data['project_id']) ? (int) $data['project_id'] : null,
            'grupo_id'       => !empty($data['grupo_id']) ? (int) $data['grupo_id'] : null,
            'fase_id'        => !empty($data['fase_id']) ? (int) $data['fase_id'] : null,
            'ata_id'         => !empty($data['ata_id']) ? (int) $data['ata_id'] : null,
            'titulo'         => trim((string) ($data['titulo'] ?? '')),
            'descricao'      => $data['descricao'] ?? null,
            'responsavel_id' => !empty($data['responsavel_id']) ? (int) $data['responsavel_id'] : null,
            'dt_inicio'      => !empty($data['dt_inicio']) ? $data['dt_inicio'] : null,
            'dt_fim'         => !empty($data['dt_fim']) ? $data['dt_fim'] : null,
            'status'         => in_array($data['status'] ?? '', $this->statuses, true) ? $data['status'] : 'aberto',
            'metodologia'    => in_array($data['metodologia'] ?? '', $this->metodologias, true) ? $data['metodologia'] : '5w2h',
        ];

        if ($id) {
            $clean['user_update'] = $me;
            $clean['dt_updated']  = date('Y-m-d H:i:s');
            $this->db->where('id', $id)->where('empresa_id', $empresa_id)->update('tbl_planos_acao', $clean);
            return (int) $id;
        }

        $clean['empresa_id']  = $empresa_id;
        $clean['user_create'] = $me;
        $clean['dt_created']  = date('Y-m-d H:i:s');
        $this->db->insert('tbl_planos_acao', $clean);
        return (int) $this->db->insert_id();
    }

    public function save_5w2h($plano_id, $list, $project_id = null, $criar_tasks = false)
    {
        $existentes = $this->db->select('id, task_id')->from('tbl_planos_acao_5w2h')
            ->where('plano_id', $plano_id)->where('deleted', 0)->get()->result_array();
        $manter = [];

        if (!empty($list) && is_array($list)) {
            foreach ($list as $idx => $w) {
                $what = trim((string) ($w['what'] ?? ''));
                if ($what === '') continue;

                $row = [
                    'plano_id' => (int) $plano_id,
                    'what'     => $what,
                    'why'      => $w['why'] ?? null,
                    'where'    => !empty($w['where']) ? trim((string) $w['where']) : null,
                    'when'     => !empty($w['when']) ? $w['when'] : null,
                    'who_id'   => !empty($w['who_id']) ? (int) $w['who_id'] : null,
                    'how'      => $w['how'] ?? null,
                    'how_much' => isset($w['how_much']) && $w['how_much'] !== '' ? (float) str_replace(',', '.', $w['how_much']) : null,
                    'status'   => $w['status'] ?? 'aberto',
                    'posicao'  => $idx,
                    'deleted'  => 0,
                ];

                $id = !empty($w['id']) ? (int) $w['id'] : 0;
                if ($id) {
                    $this->db->where('id', $id)->update('tbl_planos_acao_5w2h', $row);
                    $manter[] = $id;
                } else {
                    $this->db->insert('tbl_planos_acao_5w2h', $row);
                    $new_id = (int) $this->db->insert_id();
                    $manter[] = $new_id;

                    if ($criar_tasks && !empty($w['gerar_task'])) {
                        $task_id = $this->_criar_task_de_5w2h($new_id, $row, $project_id, $plano_id);
                        if ($task_id) {
                            $this->db->where('id', $new_id)->update('tbl_planos_acao_5w2h', ['task_id' => $task_id]);
                        }
                    }
                }
            }
        }

        foreach ($existentes as $e) {
            if (!in_array((int) $e['id'], $manter, true)) {
                $this->db->where('id', $e['id'])->update('tbl_planos_acao_5w2h', ['deleted' => 1]);
            }
        }
    }

    private function _criar_task_de_5w2h($item_id, $row, $project_id, $plano_id)
    {
        $plano = $this->db->select('grupo_id')->where('id', $plano_id)->get('tbl_planos_acao')->row();
        $grupo_id = $plano && !empty($plano->grupo_id) ? (int) $plano->grupo_id : null;

        $name = mb_substr($row['what'], 0, 250);
        $description = $row['why'] ?? '';

        $task = [
            'name'         => $name,
            'description'  => $description,
            'priority'     => 3,
            'startdate'    => date('Y-m-d'),
            'duedate'      => $row['when'] ?? null,
            'dateadded'    => date('Y-m-d H:i:s'),
            'addedfrom'    => get_staff_user_id(),
            'status'       => 1,
            'rel_id'       => $project_id ?: null,
            'rel_type'     => $project_id ? 'project' : null,
            'planoacaoid'  => (int) $plano_id,
            'grupoid'      => $grupo_id,
            'porque'       => $row['why'] ?? null,
            'onde'         => $row['where'] ?? null,
            'como'         => $row['how'] ?? null,
            'custo'        => $row['how_much'] !== null ? (string) $row['how_much'] : null,
            'empresa_id'   => (int) $this->session->userdata('empresa_id'),
        ];
        $this->db->insert('tbltasks', $task);
        $task_id = (int) $this->db->insert_id();

        if ($task_id && !empty($row['who_id'])) {
            $this->db->insert('tbltask_assigned', [
                'taskid' => $task_id,
                'staffid' => (int) $row['who_id'],
                'assigned_from' => get_staff_user_id(),
            ]);
        }
        return $task_id;
    }

    public function gerar_task_5w2h($item_id)
    {
        $w = $this->db->where('id', $item_id)->where('deleted', 0)->get('tbl_planos_acao_5w2h')->row_array();
        if (!$w || $w['task_id']) return $w['task_id'] ?? null;
        $plano = $this->get($w['plano_id']);
        if (!$plano) return null;
        $task_id = $this->_criar_task_de_5w2h($item_id, $w, $plano['project_id'] ?? null, $w['plano_id']);
        if ($task_id) {
            $this->db->where('id', $item_id)->update('tbl_planos_acao_5w2h', ['task_id' => $task_id]);
        }
        return $task_id;
    }

    public function save_ishikawa($plano_id, $data)
    {
        $existing = $this->db->where('plano_id', $plano_id)->get('tbl_planos_acao_ishikawa')->row_array();
        $row = [
            'plano_id'       => (int) $plano_id,
            'problema'       => $data['problema'] ?? null,
            'maquina'        => $data['maquina'] ?? null,
            'metodo'         => $data['metodo'] ?? null,
            'mao_obra'       => $data['mao_obra'] ?? null,
            'material'       => $data['material'] ?? null,
            'meio_ambiente'  => $data['meio_ambiente'] ?? null,
            'medida'         => $data['medida'] ?? null,
            'causa_raiz'     => $data['causa_raiz'] ?? null,
            'acao_corretiva' => $data['acao_corretiva'] ?? null,
        ];
        if ($existing) {
            $this->db->where('id', $existing['id'])->update('tbl_planos_acao_ishikawa', $row);
        } else {
            $this->db->insert('tbl_planos_acao_ishikawa', $row);
        }
    }

    public function delete($id)
    {
        $empresa_id = (int) $this->session->userdata('empresa_id');
        return $this->db->where('id', $id)->where('empresa_id', $empresa_id)->update('tbl_planos_acao', ['deleted' => 1]);
    }

    public function dashboard_projeto($project_id)
    {
        $empresa_id = (int) $this->session->userdata('empresa_id');
        $project_id = (int) $project_id;
        return [
            'total_atas' => (int) $this->db->where(['project_id' => $project_id, 'empresa_id' => $empresa_id, 'deleted' => 0])
                ->count_all_results('tbl_atas'),
            'planos_em_andamento' => (int) $this->db->where(['project_id' => $project_id, 'empresa_id' => $empresa_id, 'deleted' => 0])
                ->where_in('status', ['aberto', 'em_execucao'])
                ->count_all_results('tbl_planos_acao'),
            'tasks_vinculadas' => (int) $this->db->query("SELECT COUNT(*) AS n FROM tbltasks
                WHERE empresa_id = $empresa_id AND deleted = 0
                  AND (rel_id = $project_id AND rel_type = 'project' AND (ataid > 0 OR planoacaoid > 0))")->row()->n,
        ];
    }
}
