<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Projeto_fase_model extends App_Model
{
    private $statuses = ['planejada', 'em_execucao', 'concluida', 'atrasada', 'cancelada'];

    public function get_statuses() { return $this->statuses; }

    public function get_status_label($s)
    {
        return ['planejada' => 'Planejada', 'em_execucao' => 'Em execução', 'concluida' => 'Concluída', 'atrasada' => 'Atrasada', 'cancelada' => 'Cancelada'][$s] ?? ucfirst($s);
    }

    public function get_status_color($s)
    {
        return ['planejada' => '#94a3b8', 'em_execucao' => '#0a66c2', 'concluida' => '#16a34a', 'atrasada' => '#dc2626', 'cancelada' => '#737373'][$s] ?? '#94a3b8';
    }

    public function listar($project_id, $only_root = false)
    {
        $empresa_id = (int) $this->session->userdata('empresa_id');
        $project_id = (int) $project_id;
        $this->db->select('f.*, CONCAT_WS(\' \', s.firstname, s.lastname) AS responsavel_nome');
        $this->db->from('tbl_projeto_fases f');
        $this->db->join('tblstaff s', 's.staffid = f.responsavel_id', 'left');
        $this->db->where('f.project_id', $project_id);
        $this->db->where('f.deleted', 0);
        $this->db->where('f.empresa_id', $empresa_id);
        if ($only_root) $this->db->where('f.parent_id IS NULL', null, false);
        $this->db->order_by('f.codigo_sequencial', 'asc');
        return $this->db->get()->result_array();
    }

    public function tree_data($project_id)
    {
        $rows = $this->listar($project_id);

        $children_count = [];
        foreach ($rows as $r) {
            $pid = $r['parent_id'] ?: 0;
            $children_count[$pid] = ($children_count[$pid] ?? 0) + 1;
        }

        $nodes = [];
        foreach ($rows as $r) {
            $pct = (int) $r['percentual'];
            $color = $this->get_status_color($r['status']);
            $atrasada = false;
            if (!empty($r['dt_fim_prev']) && $r['dt_fim_prev'] < date('Y-m-d') && !in_array($r['status'], ['concluida', 'cancelada'], true)) {
                $atrasada = true;
                $color = '#dc2626';
            }

            $badges = '';
            $badges .= '<span class="fase-badge" style="background:' . $color . ';">' . $this->get_status_label($atrasada ? 'atrasada' : $r['status']) . '</span>';
            if (!empty($r['dt_fim_prev'])) {
                $badges .= '<span class="fase-prazo"><i class="fa fa-calendar"></i> ' . date('d/m/Y', strtotime($r['dt_fim_prev'])) . '</span>';
            }
            if (!empty($r['responsavel_nome'])) {
                $badges .= '<span class="fase-resp"><i class="fa fa-user"></i> ' . html_escape(mb_strimwidth($r['responsavel_nome'], 0, 22, '…')) . '</span>';
            }

            $text = '<strong>' . htmlspecialchars($r['codigo_sequencial'] . ' ' . $r['titulo'], ENT_QUOTES) . '</strong> '
                  . $badges
                  . '<span class="fase-progress"><span class="fase-progress-bar" style="width:' . $pct . '%;background:' . $color . ';"></span></span>';

            $nodes[] = [
                'id'     => 'f' . $r['id'],
                'parent' => $r['parent_id'] ? 'f' . $r['parent_id'] : '#',
                'text'   => $text,
                'data'   => [
                    'id' => (int) $r['id'],
                    'titulo' => $r['titulo'],
                    'descricao' => $r['descricao'],
                    'status' => $r['status'],
                    'dt_inicio_prev' => $r['dt_inicio_prev'],
                    'dt_fim_prev' => $r['dt_fim_prev'],
                    'percentual' => (int) $r['percentual'],
                    'responsavel_id' => (int) $r['responsavel_id'],
                ],
                'state'  => ['opened' => true],
            ];
        }
        return $nodes;
    }

    public function get($id)
    {
        $empresa_id = (int) $this->session->userdata('empresa_id');
        return $this->db->where('id', $id)->where('empresa_id', $empresa_id)->where('deleted', 0)
            ->get('tbl_projeto_fases')->row_array();
    }

    public function save($data, $id = null)
    {
        $empresa_id = (int) $this->session->userdata('empresa_id');
        $me = (int) get_staff_user_id();

        $clean = [
            'project_id'     => (int) $data['project_id'],
            'parent_id'      => !empty($data['parent_id']) ? (int) $data['parent_id'] : null,
            'titulo'         => trim((string) ($data['titulo'] ?? '')),
            'descricao'      => $data['descricao'] ?? null,
            'status'         => in_array($data['status'] ?? '', $this->statuses, true) ? $data['status'] : 'planejada',
            'dt_inicio_prev' => !empty($data['dt_inicio_prev']) ? $data['dt_inicio_prev'] : null,
            'dt_fim_prev'    => !empty($data['dt_fim_prev']) ? $data['dt_fim_prev'] : null,
            'dt_inicio_real' => !empty($data['dt_inicio_real']) ? $data['dt_inicio_real'] : null,
            'dt_fim_real'    => !empty($data['dt_fim_real']) ? $data['dt_fim_real'] : null,
            'percentual'     => isset($data['percentual']) ? max(0, min(100, (int) $data['percentual'])) : 0,
            'responsavel_id' => !empty($data['responsavel_id']) ? (int) $data['responsavel_id'] : null,
        ];

        if ($id) {
            $clean['user_update'] = $me;
            $clean['dt_updated']  = date('Y-m-d H:i:s');
            $this->db->where('id', $id)->where('empresa_id', $empresa_id)->update('tbl_projeto_fases', $clean);
            $this->_recompute_codigos($clean['project_id']);
            return (int) $id;
        }

        $clean['empresa_id']        = $empresa_id;
        $clean['user_create']       = $me;
        $clean['dt_created']        = date('Y-m-d H:i:s');
        $clean['codigo_sequencial'] = $this->_next_codigo($clean['project_id'], $clean['parent_id']);
        $this->db->insert('tbl_projeto_fases', $clean);
        return (int) $this->db->insert_id();
    }

    public function delete($id)
    {
        $empresa_id = (int) $this->session->userdata('empresa_id');
        $ids = $this->_collect_subtree_ids($id);
        if ($ids) {
            $this->db->where_in('id', $ids)->where('empresa_id', $empresa_id)
                ->update('tbl_projeto_fases', ['deleted' => 1]);
        }
        return count($ids);
    }

    public function reorder($id, $new_parent_id, $position)
    {
        $empresa_id = (int) $this->session->userdata('empresa_id');
        $fase = $this->get($id);
        if (!$fase) return false;
        $project_id = (int) $fase['project_id'];

        $sub_ids = $this->_collect_subtree_ids($id);
        if ($new_parent_id && in_array((int) $new_parent_id, $sub_ids, true)) {
            return false;
        }

        $this->db->trans_start();
        $this->db->where('id', $id)->where('empresa_id', $empresa_id)
            ->update('tbl_projeto_fases', ['parent_id' => $new_parent_id ?: null]);
        $this->_reorder_siblings($project_id, $new_parent_id ?: null, $id, $position);
        $this->_recompute_codigos($project_id);
        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    public function dashboard($project_id)
    {
        $empresa_id = (int) $this->session->userdata('empresa_id');
        $project_id = (int) $project_id;
        $totais = $this->db->query("SELECT
                COUNT(*) AS total,
                SUM(status='concluida') AS concluidas,
                SUM(status='em_execucao') AS em_execucao,
                SUM(status='planejada') AS planejadas,
                SUM(status='cancelada') AS canceladas,
                SUM(dt_fim_prev IS NOT NULL AND dt_fim_prev < CURDATE() AND status NOT IN ('concluida','cancelada')) AS atrasadas
            FROM tbl_projeto_fases
            WHERE project_id = $project_id AND empresa_id = $empresa_id AND deleted = 0")->row_array();

        $pct_avg = (float) $this->db->query("SELECT COALESCE(AVG(percentual),0) AS pct
            FROM tbl_projeto_fases WHERE project_id = $project_id AND empresa_id = $empresa_id AND deleted = 0")->row()->pct;

        $atas         = (int) $this->db->where(['project_id' => $project_id, 'empresa_id' => $empresa_id, 'deleted' => 0])->count_all_results('tbl_atas');
        $planos_total = (int) $this->db->where(['project_id' => $project_id, 'empresa_id' => $empresa_id, 'deleted' => 0])->count_all_results('tbl_planos_acao');
        $planos_ativos= (int) $this->db->where(['project_id' => $project_id, 'empresa_id' => $empresa_id, 'deleted' => 0])
            ->where_in('status', ['aberto', 'em_execucao'])->count_all_results('tbl_planos_acao');
        $tasks_abertas= (int) $this->db->query("SELECT COUNT(*) AS n FROM tbltasks
            WHERE rel_id = $project_id AND rel_type = 'project' AND deleted = 0 AND status != 5")->row()->n;
        $grupos       = (int) $this->db->where(['project_id' => $project_id, 'empresa_id' => $empresa_id, 'deleted' => 0])->count_all_results('tbl_grupos');

        return [
            'fases_total'    => (int) $totais['total'],
            'fases_concluidas' => (int) $totais['concluidas'],
            'fases_em_execucao' => (int) $totais['em_execucao'],
            'fases_atrasadas' => (int) $totais['atrasadas'],
            'pct_geral'      => round($pct_avg, 1),
            'atas'           => $atas,
            'planos_total'   => $planos_total,
            'planos_ativos'  => $planos_ativos,
            'tasks_abertas'  => $tasks_abertas,
            'grupos'         => $grupos,
        ];
    }

    /* ============ Helpers privados ============ */

    private function _next_codigo($project_id, $parent_id)
    {
        $empresa_id = (int) $this->session->userdata('empresa_id');
        if ($parent_id) {
            $parent = $this->db->select('codigo_sequencial')->where('id', $parent_id)
                ->get('tbl_projeto_fases')->row();
            $base = $parent ? $parent->codigo_sequencial : '1';
        } else {
            $base = '';
        }

        $this->db->where('project_id', $project_id)->where('empresa_id', $empresa_id)->where('deleted', 0);
        if ($parent_id) $this->db->where('parent_id', $parent_id);
        else            $this->db->where('parent_id IS NULL', null, false);
        $count = $this->db->count_all_results('tbl_projeto_fases');

        return $base === '' ? (string) ($count + 1) : $base . '.' . ($count + 1);
    }

    private function _collect_subtree_ids($id)
    {
        $ids = [(int) $id];
        $queue = [(int) $id];
        while ($queue) {
            $pid = array_shift($queue);
            $children = $this->db->select('id')->where('parent_id', $pid)->where('deleted', 0)
                ->get('tbl_projeto_fases')->result_array();
            foreach ($children as $c) {
                $ids[] = (int) $c['id'];
                $queue[] = (int) $c['id'];
            }
        }
        return $ids;
    }

    private function _reorder_siblings($project_id, $parent_id, $moved_id, $position)
    {
        $this->db->select('id')->where('project_id', $project_id)->where('deleted', 0)
            ->where('id !=', $moved_id);
        if ($parent_id) $this->db->where('parent_id', $parent_id);
        else            $this->db->where('parent_id IS NULL', null, false);
        $this->db->order_by('codigo_sequencial', 'asc');
        $siblings = $this->db->get('tbl_projeto_fases')->result_array();
        $ordered = array_column($siblings, 'id');
        $position = max(0, min($position, count($ordered)));
        array_splice($ordered, $position, 0, [$moved_id]);

        // só salva ordem para reuso interno; codigo_sequencial é recalculado depois
        foreach ($ordered as $idx => $sid) {
            $this->db->where('id', $sid)->update('tbl_projeto_fases', ['ordem' => $idx]);
        }
    }

    private function _recompute_codigos($project_id)
    {
        $rows = $this->db->select('id, parent_id, codigo_sequencial, ordem')
            ->where('project_id', $project_id)->where('deleted', 0)
            ->order_by('ordem', 'asc')->order_by('codigo_sequencial', 'asc')
            ->get('tbl_projeto_fases')->result_array();

        $by_parent = [];
        foreach ($rows as $r) {
            $pid = $r['parent_id'] ?: 0;
            $by_parent[$pid][] = $r;
        }

        $assign = function ($parent_id, $base) use (&$assign, &$by_parent) {
            if (empty($by_parent[$parent_id])) return;
            foreach ($by_parent[$parent_id] as $idx => $r) {
                $code = $base === '' ? (string) ($idx + 1) : $base . '.' . ($idx + 1);
                if ($r['codigo_sequencial'] !== $code) {
                    $this->db->where('id', $r['id'])->update('tbl_projeto_fases', ['codigo_sequencial' => $code]);
                }
                $assign($r['id'], $code);
            }
        };
        $assign(0, '');
    }
}
