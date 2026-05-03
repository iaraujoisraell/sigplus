<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Indicador_model extends App_Model
{
    private $statuses        = ['ativo', 'pausado', 'arquivado'];
    private $meta_tipos      = ['maior_melhor', 'menor_melhor', 'igual'];
    private $periodicidades  = ['diaria', 'semanal', 'mensal', 'trimestral', 'semestral', 'anual'];

    public function get_statuses()       { return $this->statuses; }
    public function get_meta_tipos()     { return $this->meta_tipos; }
    public function get_periodicidades() { return $this->periodicidades; }

    public function get_status_label($s)
    {
        return ['ativo' => 'Ativo', 'pausado' => 'Pausado', 'arquivado' => 'Arquivado'][$s] ?? ucfirst($s);
    }

    public function get_meta_tipo_label($t)
    {
        return ['maior_melhor' => 'Maior é melhor', 'menor_melhor' => 'Menor é melhor', 'igual' => 'Atingir o valor'][$t] ?? $t;
    }

    public function get_periodicidade_label($p)
    {
        return ['diaria' => 'Diária', 'semanal' => 'Semanal', 'mensal' => 'Mensal', 'trimestral' => 'Trimestral', 'semestral' => 'Semestral', 'anual' => 'Anual'][$p] ?? $p;
    }

    /* ============ Listagem ============ */

    public function listar($filtros = [])
    {
        $empresa_id = (int) $this->session->userdata('empresa_id');
        $me = (int) get_staff_user_id();

        $this->db->select("i.*, dep.name AS setor_nome, p.name AS project_name,
                           CONCAT_WS(' ', s.firstname, s.lastname) AS responsavel_nome,
                           (SELECT m.valor FROM tbl_indicadores_medicoes m
                            WHERE m.indicador_id = i.id AND m.deleted = 0
                            ORDER BY m.periodo_referencia DESC, m.id DESC LIMIT 1) AS ultimo_valor,
                           (SELECT m.periodo_referencia FROM tbl_indicadores_medicoes m
                            WHERE m.indicador_id = i.id AND m.deleted = 0
                            ORDER BY m.periodo_referencia DESC, m.id DESC LIMIT 1) AS ultimo_periodo,
                           (SELECT COUNT(*) FROM tbl_indicadores_medicoes m
                            WHERE m.indicador_id = i.id AND m.deleted = 0) AS total_medicoes");
        $this->db->from('tbl_indicadores i');
        $this->db->join('tbldepartments dep', 'dep.departmentid = i.setor_id', 'left');
        $this->db->join('tblprojects p', 'p.id = i.project_id', 'left');
        $this->db->join('tblstaff s', 's.staffid = i.responsavel_id', 'left');
        $this->db->where('i.deleted', 0);
        $this->db->where('i.empresa_id', $empresa_id);

        if (!empty($filtros['setor_id']))      $this->db->where('i.setor_id',      (int) $filtros['setor_id']);
        if (!empty($filtros['project_id']))    $this->db->where('i.project_id',    (int) $filtros['project_id']);
        if (!empty($filtros['fase_id']))       $this->db->where('i.fase_id',       (int) $filtros['fase_id']);
        if (!empty($filtros['responsavel_id']))$this->db->where('i.responsavel_id',(int) $filtros['responsavel_id']);
        if (!empty($filtros['status']))           $this->db->where('i.status', $filtros['status']);
        if (!empty($filtros['meu'])) {
            $this->db->where("(i.user_create = $me OR i.responsavel_id = $me)", null, false);
        }
        if (!empty($filtros['criei']))            $this->db->where('i.user_create', $me);
        if (!empty($filtros['responsavel_meu']))  $this->db->where('i.responsavel_id', $me);
        if (!empty($filtros['busca'])) {
            $term = $this->db->escape_like_str($filtros['busca']);
            $this->db->where("(i.nome LIKE '%{$term}%' OR i.descricao LIKE '%{$term}%' OR i.codigo LIKE '%{$term}%')", null, false);
        }

        $this->db->order_by('i.status', 'asc')->order_by('i.nome', 'asc');
        $rows = $this->db->get()->result_array();

        // anexa avaliação contra meta
        foreach ($rows as &$r) {
            $r['avaliacao'] = $this->_avaliar($r);
        }
        return $rows;
    }

    public function get($id)
    {
        $empresa_id = (int) $this->session->userdata('empresa_id');
        $row = $this->db->select("i.*, dep.name AS setor_nome, p.name AS project_name,
                                  CONCAT_WS(' ', s.firstname, s.lastname) AS responsavel_nome")
            ->from('tbl_indicadores i')
            ->join('tbldepartments dep', 'dep.departmentid = i.setor_id', 'left')
            ->join('tblprojects p', 'p.id = i.project_id', 'left')
            ->join('tblstaff s', 's.staffid = i.responsavel_id', 'left')
            ->where('i.id', (int) $id)
            ->where('i.empresa_id', $empresa_id)
            ->where('i.deleted', 0)
            ->get()->row_array();
        return $row;
    }

    public function pode_editar($ind, $staff_id = null)
    {
        $staff_id = $staff_id ?? (int) get_staff_user_id();
        if ((int) ($ind['user_create'] ?? 0) === $staff_id) return true;
        if ((int) ($ind['responsavel_id'] ?? 0) === $staff_id) return true;
        return is_admin();
    }

    /* ============ CRUD ============ */

    public function save($data, $id = null)
    {
        $empresa_id = (int) $this->session->userdata('empresa_id');
        $me = (int) get_staff_user_id();

        $clean = [
            'codigo'         => trim((string) ($data['codigo'] ?? '')),
            'nome'           => trim((string) ($data['nome'] ?? 'Indicador sem nome')),
            'descricao'      => $data['descricao'] ?? null,
            'formula'        => $data['formula'] ?? null,
            'unidade'        => trim((string) ($data['unidade'] ?? '')),
            'casas_decimais' => isset($data['casas_decimais']) ? max(0, min(6, (int) $data['casas_decimais'])) : 2,
            'meta'           => isset($data['meta']) && $data['meta'] !== '' ? (float) str_replace(',', '.', $data['meta']) : null,
            'meta_tipo'      => in_array($data['meta_tipo'] ?? '', $this->meta_tipos, true) ? $data['meta_tipo'] : 'maior_melhor',
            'tolerancia'     => isset($data['tolerancia']) && $data['tolerancia'] !== '' ? (float) str_replace(',', '.', $data['tolerancia']) : null,
            'periodicidade'  => in_array($data['periodicidade'] ?? '', $this->periodicidades, true) ? $data['periodicidade'] : 'mensal',
            'setor_id'       => !empty($data['setor_id']) ? (int) $data['setor_id'] : null,
            'responsavel_id' => !empty($data['responsavel_id']) ? (int) $data['responsavel_id'] : null,
            'project_id'     => !empty($data['project_id']) ? (int) $data['project_id'] : null,
            'fase_id'        => !empty($data['fase_id']) ? (int) $data['fase_id'] : null,
            'cor'            => $data['cor'] ?? '#0a66c2',
            'status'         => in_array($data['status'] ?? '', $this->statuses, true) ? $data['status'] : 'ativo',
        ];

        if ($id) {
            $clean['user_update'] = $me;
            $clean['dt_updated']  = date('Y-m-d H:i:s');
            $this->db->where('id', $id)->where('empresa_id', $empresa_id)
                ->update('tbl_indicadores', $clean);
            return (int) $id;
        }

        $clean['empresa_id']  = $empresa_id;
        $clean['user_create'] = $me;
        $clean['dt_created']  = date('Y-m-d H:i:s');
        if (empty($clean['codigo'])) {
            $row = $this->db->select_max('id')->where('empresa_id', $empresa_id)
                ->get('tbl_indicadores')->row();
            $clean['codigo'] = 'IND-' . str_pad(((int) $row->id) + 1, 3, '0', STR_PAD_LEFT);
        }
        $this->db->insert('tbl_indicadores', $clean);
        return (int) $this->db->insert_id();
    }

    public function delete($id)
    {
        $empresa_id = (int) $this->session->userdata('empresa_id');
        return $this->db->where('id', $id)->where('empresa_id', $empresa_id)
            ->update('tbl_indicadores', ['deleted' => 1]);
    }

    /* ============ Medições ============ */

    public function get_medicoes($indicador_id, $limit = null)
    {
        $this->db->select("m.*, CONCAT_WS(' ', s.firstname, s.lastname) AS staff_nome")
            ->from('tbl_indicadores_medicoes m')
            ->join('tblstaff s', 's.staffid = m.user_coleta', 'left')
            ->where('m.indicador_id', (int) $indicador_id)
            ->where('m.deleted', 0)
            ->order_by('m.periodo_referencia', 'asc');
        if ($limit) $this->db->limit($limit);
        return $this->db->get()->result_array();
    }

    public function add_medicao($data)
    {
        $empresa_id = (int) $this->session->userdata('empresa_id');
        $me = (int) get_staff_user_id();
        $indicador_id = (int) ($data['indicador_id'] ?? 0);
        $periodo = trim((string) ($data['periodo_referencia'] ?? ''));
        $valor = (float) str_replace(',', '.', $data['valor'] ?? 0);
        if ($indicador_id <= 0 || $periodo === '') return false;

        // se já tem medição pra esse período, atualiza
        $existente = $this->db->where('indicador_id', $indicador_id)
            ->where('periodo_referencia', $periodo)
            ->where('deleted', 0)
            ->get('tbl_indicadores_medicoes')->row();

        $payload = [
            'indicador_id'       => $indicador_id,
            'periodo_referencia' => $periodo,
            'valor'              => $valor,
            'observacao'         => $data['observacao'] ?? null,
            'dt_coleta'          => date('Y-m-d H:i:s'),
            'user_coleta'        => $me,
            'empresa_id'         => $empresa_id,
        ];

        if ($existente) {
            $this->db->where('id', $existente->id)->update('tbl_indicadores_medicoes', $payload);
            return (int) $existente->id;
        }
        $this->db->insert('tbl_indicadores_medicoes', $payload);
        return (int) $this->db->insert_id();
    }

    public function delete_medicao($id)
    {
        return $this->db->where('id', (int) $id)
            ->update('tbl_indicadores_medicoes', ['deleted' => 1]);
    }

    /* ============ Avaliação contra meta ============ */

    public function _avaliar($ind)
    {
        $valor = $ind['ultimo_valor'] ?? null;
        $meta  = $ind['meta'] ?? null;
        if ($valor === null || $meta === null) {
            return ['status' => 'sem_dados', 'label' => 'Sem dados', 'cor' => '#94a3b8', 'pct' => null];
        }
        $valor = (float) $valor;
        $meta  = (float) $meta;
        $tol   = isset($ind['tolerancia']) && $ind['tolerancia'] !== null ? (float) $ind['tolerancia'] : 0;
        $tipo  = $ind['meta_tipo'] ?? 'maior_melhor';

        $status = 'atingido';
        if ($tipo === 'maior_melhor') {
            if ($valor >= $meta) $status = 'atingido';
            elseif ($valor >= ($meta - $tol)) $status = 'atencao';
            else $status = 'critico';
        } elseif ($tipo === 'menor_melhor') {
            if ($valor <= $meta) $status = 'atingido';
            elseif ($valor <= ($meta + $tol)) $status = 'atencao';
            else $status = 'critico';
        } else {
            $diff = abs($valor - $meta);
            if ($diff <= $tol) $status = 'atingido';
            elseif ($diff <= $tol * 2) $status = 'atencao';
            else $status = 'critico';
        }

        $cor = ['atingido' => '#16a34a', 'atencao' => '#f59e0b', 'critico' => '#dc2626'][$status];
        $label = ['atingido' => 'Atingido', 'atencao' => 'Atenção', 'critico' => 'Crítico'][$status];
        $pct = $meta != 0 ? round(($valor / $meta) * 100, 1) : null;

        return ['status' => $status, 'label' => $label, 'cor' => $cor, 'pct' => $pct];
    }

    /* ============ Próximo período de coleta ============ */

    public function periodo_atual($periodicidade)
    {
        $now = time();
        switch ($periodicidade) {
            case 'diaria':     return date('Y-m-d', $now);
            case 'semanal':    return date('o-\WW', $now);
            case 'mensal':     return date('Y-m', $now);
            case 'trimestral': return date('Y') . '-Q' . ceil((int) date('n', $now) / 3);
            case 'semestral':  return date('Y') . '-S' . ((int) date('n', $now) <= 6 ? 1 : 2);
            case 'anual':      return date('Y', $now);
        }
        return date('Y-m', $now);
    }

    public function format_periodo($periodicidade, $referencia)
    {
        if ($periodicidade === 'mensal' && preg_match('/^(\d{4})-(\d{2})$/', $referencia, $m)) {
            $meses = ['','Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'];
            return $meses[(int) $m[2]] . '/' . $m[1];
        }
        if ($periodicidade === 'diaria' && preg_match('/^\d{4}-\d{2}-\d{2}$/', $referencia)) {
            return date('d/m/Y', strtotime($referencia));
        }
        return $referencia;
    }
}
