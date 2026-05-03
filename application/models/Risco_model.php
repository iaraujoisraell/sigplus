<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Risco_model extends App_Model
{
    private $statuses = ['identificado', 'em_tratamento', 'controlado', 'mitigado', 'fechado'];
    private $tratamentos = ['evitar', 'mitigar', 'transferir', 'aceitar'];
    private $categorias = [
        'operacional' => 'Operacional',
        'estrategico' => 'Estratégico',
        'financeiro'  => 'Financeiro',
        'legal'       => 'Legal/Regulatório',
        'seguranca'   => 'Segurança do paciente',
        'tecnologico' => 'Tecnológico',
        'reputacional'=> 'Reputacional',
        'ambiental'   => 'Ambiental',
        'recursos'    => 'Pessoas/RH',
    ];

    public function get_statuses()    { return $this->statuses; }
    public function get_tratamentos() { return $this->tratamentos; }
    public function get_categorias()  { return $this->categorias; }

    public function get_status_label($s)
    {
        return [
            'identificado'  => 'Identificado',
            'em_tratamento' => 'Em tratamento',
            'controlado'    => 'Controlado',
            'mitigado'      => 'Mitigado',
            'fechado'       => 'Fechado',
        ][$s] ?? ucfirst($s);
    }

    public function get_tratamento_label($t)
    {
        return ['evitar' => 'Evitar', 'mitigar' => 'Mitigar', 'transferir' => 'Transferir', 'aceitar' => 'Aceitar'][$t] ?? $t;
    }

    public function get_categoria_label($c)
    {
        return $this->categorias[$c] ?? ucfirst($c);
    }

    /* ============ Avaliação probabilidade × impacto ============ */

    public function calc_severidade($prob, $imp)
    {
        return max(1, min(25, (int) $prob * (int) $imp));
    }

    public function calc_nivel($severidade)
    {
        $s = (int) $severidade;
        if ($s >= 16) return 'critico';
        if ($s >= 10) return 'alto';
        if ($s >= 5)  return 'moderado';
        return 'baixo';
    }

    public function get_nivel_label($n)
    {
        return ['baixo' => 'Baixo', 'moderado' => 'Moderado', 'alto' => 'Alto', 'critico' => 'Crítico'][$n] ?? ucfirst($n);
    }

    public function get_nivel_color($n)
    {
        return ['baixo' => '#16a34a', 'moderado' => '#f59e0b', 'alto' => '#ea580c', 'critico' => '#dc2626'][$n] ?? '#94a3b8';
    }

    public function get_prob_label($p)
    {
        return [1 => 'Muito baixa', 2 => 'Baixa', 3 => 'Média', 4 => 'Alta', 5 => 'Muito alta'][$p] ?? '—';
    }

    public function get_imp_label($i)
    {
        return [1 => 'Insignificante', 2 => 'Pequeno', 3 => 'Moderado', 4 => 'Grave', 5 => 'Catastrófico'][$i] ?? '—';
    }

    /* ============ Listagem ============ */

    public function listar($filtros = [])
    {
        $empresa_id = (int) $this->session->userdata('empresa_id');
        $me = (int) get_staff_user_id();

        $this->db->select("r.*, dep.name AS setor_nome, p.name AS project_name,
                           CONCAT_WS(' ', s.firstname, s.lastname) AS responsavel_nome,
                           CONCAT_WS(' ', sc.firstname, sc.lastname) AS criador_nome,
                           pa.titulo AS plano_titulo,
                           g.titulo AS grupo_titulo");
        $this->db->from('tbl_riscos r');
        $this->db->join('tbldepartments dep',  'dep.departmentid = r.setor_id',     'left');
        $this->db->join('tblprojects p',       'p.id = r.project_id',                'left');
        $this->db->join('tblstaff s',          's.staffid = r.responsavel_id',       'left');
        $this->db->join('tblstaff sc',         'sc.staffid = r.user_create',         'left');
        $this->db->join('tbl_planos_acao pa',  'pa.id = r.plano_id',                 'left');
        $this->db->join('tbl_grupos g',        'g.id = r.grupo_id',                  'left');
        $this->db->where('r.deleted', 0);
        $this->db->where('r.empresa_id', $empresa_id);

        if (!empty($filtros['categoria']))      $this->db->where('r.categoria', $filtros['categoria']);
        if (!empty($filtros['status']))         $this->db->where('r.status', $filtros['status']);
        if (!empty($filtros['nivel']))          $this->db->where('r.nivel', $filtros['nivel']);
        if (!empty($filtros['project_id']))     $this->db->where('r.project_id', (int) $filtros['project_id']);
        if (!empty($filtros['setor_id']))       $this->db->where('r.setor_id',   (int) $filtros['setor_id']);
        if (!empty($filtros['plano_id']))       $this->db->where('r.plano_id',   (int) $filtros['plano_id']);
        if (!empty($filtros['grupo_id']))       $this->db->where('r.grupo_id',   (int) $filtros['grupo_id']);
        if (!empty($filtros['criei']))          $this->db->where('r.user_create', $me);
        if (!empty($filtros['responsavel_meu']))$this->db->where('r.responsavel_id', $me);
        if (!empty($filtros['busca'])) {
            $term = $this->db->escape_like_str($filtros['busca']);
            $this->db->where("(r.titulo LIKE '%{$term}%' OR r.descricao LIKE '%{$term}%' OR r.codigo LIKE '%{$term}%')", null, false);
        }

        $this->db->order_by('r.severidade', 'desc')->order_by('r.id', 'desc');
        return $this->db->get()->result_array();
    }

    public function get($id)
    {
        $empresa_id = (int) $this->session->userdata('empresa_id');
        return $this->db->select("r.*, dep.name AS setor_nome, p.name AS project_name,
                                  CONCAT_WS(' ', s.firstname, s.lastname) AS responsavel_nome,
                                  pa.titulo AS plano_titulo, g.titulo AS grupo_titulo")
            ->from('tbl_riscos r')
            ->join('tbldepartments dep',  'dep.departmentid = r.setor_id',     'left')
            ->join('tblprojects p',       'p.id = r.project_id',                'left')
            ->join('tblstaff s',          's.staffid = r.responsavel_id',       'left')
            ->join('tbl_planos_acao pa',  'pa.id = r.plano_id',                 'left')
            ->join('tbl_grupos g',        'g.id = r.grupo_id',                  'left')
            ->where('r.id', (int) $id)
            ->where('r.empresa_id', $empresa_id)
            ->where('r.deleted', 0)
            ->get()->row_array();
    }

    public function pode_editar($r, $staff_id = null)
    {
        $staff_id = $staff_id ?? (int) get_staff_user_id();
        if ((int) ($r['user_create'] ?? 0) === $staff_id) return true;
        if ((int) ($r['responsavel_id'] ?? 0) === $staff_id) return true;
        return is_admin();
    }

    /* ============ CRUD ============ */

    public function save($data, $id = null)
    {
        $empresa_id = (int) $this->session->userdata('empresa_id');
        $me = (int) get_staff_user_id();

        $prob = max(1, min(5, (int) ($data['probabilidade'] ?? 1)));
        $imp  = max(1, min(5, (int) ($data['impacto'] ?? 1)));
        $sev  = $this->calc_severidade($prob, $imp);
        $nivel = $this->calc_nivel($sev);

        $clean = [
            'codigo'               => trim((string) ($data['codigo'] ?? '')),
            'titulo'               => trim((string) ($data['titulo'] ?? 'Risco sem título')),
            'descricao'            => $data['descricao'] ?? null,
            'categoria'            => isset($this->categorias[$data['categoria'] ?? '']) ? $data['categoria'] : 'operacional',
            'causa'                => $data['causa'] ?? null,
            'consequencia'         => $data['consequencia'] ?? null,
            'controles_existentes' => $data['controles_existentes'] ?? null,
            'probabilidade'        => $prob,
            'impacto'              => $imp,
            'severidade'           => $sev,
            'nivel'                => $nivel,
            'tipo_tratamento'      => in_array($data['tipo_tratamento'] ?? '', $this->tratamentos, true) ? $data['tipo_tratamento'] : 'mitigar',
            'plano_tratamento'     => $data['plano_tratamento'] ?? null,
            'status'               => in_array($data['status'] ?? '', $this->statuses, true) ? $data['status'] : 'identificado',
            'responsavel_id'       => !empty($data['responsavel_id']) ? (int) $data['responsavel_id'] : null,
            'setor_id'             => !empty($data['setor_id']) ? (int) $data['setor_id'] : null,
            'project_id'           => !empty($data['project_id']) ? (int) $data['project_id'] : null,
            'fase_id'              => !empty($data['fase_id']) ? (int) $data['fase_id'] : null,
            'plano_id'             => !empty($data['plano_id']) ? (int) $data['plano_id'] : null,
            'grupo_id'             => !empty($data['grupo_id']) ? (int) $data['grupo_id'] : null,
            'dt_identificacao'     => !empty($data['dt_identificacao']) ? $data['dt_identificacao'] : null,
            'dt_revisao'           => !empty($data['dt_revisao']) ? $data['dt_revisao'] : null,
        ];

        if ($id) {
            $atual = $this->db->select('probabilidade, impacto')->where('id', $id)->get('tbl_riscos')->row();
            $clean['user_update'] = $me;
            $clean['dt_updated']  = date('Y-m-d H:i:s');
            $this->db->where('id', $id)->where('empresa_id', $empresa_id)
                ->update('tbl_riscos', $clean);
            // se mudou probabilidade ou impacto, registra avaliação histórica
            if ($atual && ((int) $atual->probabilidade !== $prob || (int) $atual->impacto !== $imp)) {
                $this->add_avaliacao($id, $prob, $imp, 'Reavaliação');
            }
            return (int) $id;
        }

        $clean['empresa_id']       = $empresa_id;
        $clean['user_create']      = $me;
        $clean['dt_created']       = date('Y-m-d H:i:s');
        if (empty($clean['dt_identificacao'])) $clean['dt_identificacao'] = date('Y-m-d');
        if (empty($clean['codigo'])) {
            $row = $this->db->select_max('id')->where('empresa_id', $empresa_id)
                ->get('tbl_riscos')->row();
            $clean['codigo'] = 'RSK-' . str_pad(((int) $row->id) + 1, 3, '0', STR_PAD_LEFT);
        }
        $this->db->insert('tbl_riscos', $clean);
        $new_id = (int) $this->db->insert_id();
        // primeira avaliação
        $this->add_avaliacao($new_id, $prob, $imp, 'Identificação inicial');
        return $new_id;
    }

    public function delete($id)
    {
        $empresa_id = (int) $this->session->userdata('empresa_id');
        return $this->db->where('id', $id)->where('empresa_id', $empresa_id)
            ->update('tbl_riscos', ['deleted' => 1]);
    }

    /* ============ Histórico de avaliações ============ */

    public function add_avaliacao($risco_id, $prob, $imp, $observacao = '')
    {
        $sev = $this->calc_severidade($prob, $imp);
        return $this->db->insert('tbl_riscos_avaliacoes', [
            'risco_id'       => (int) $risco_id,
            'probabilidade'  => (int) $prob,
            'impacto'        => (int) $imp,
            'severidade'     => $sev,
            'observacao'     => trim((string) $observacao),
            'dt_avaliacao'   => date('Y-m-d H:i:s'),
            'user_avaliacao' => (int) get_staff_user_id(),
        ]);
    }

    public function get_avaliacoes($risco_id)
    {
        return $this->db->select("a.*, CONCAT_WS(' ', s.firstname, s.lastname) AS staff_nome")
            ->from('tbl_riscos_avaliacoes a')
            ->join('tblstaff s', 's.staffid = a.user_avaliacao', 'left')
            ->where('a.risco_id', (int) $risco_id)
            ->where('a.deleted', 0)
            ->order_by('a.dt_avaliacao', 'asc')
            ->get()->result_array();
    }

    /* ============ Matriz consolidada (5x5) ============ */

    public function matriz($filtros = [])
    {
        $rows = $this->listar($filtros);
        $matriz = [];
        for ($i = 1; $i <= 5; $i++) {
            for ($p = 1; $p <= 5; $p++) {
                $matriz[$i][$p] = [];
            }
        }
        foreach ($rows as $r) {
            $matriz[(int) $r['impacto']][(int) $r['probabilidade']][] = $r;
        }
        return $matriz;
    }
}
