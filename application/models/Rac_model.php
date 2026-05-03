<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Análise Crítica pela Direção (RAC) — exigência ISO 9001 9.3 / ONA.
 * Reunião periódica que avalia o desempenho do SGQ no período.
 */
class Rac_model extends App_Model
{
    private $statuses    = ['planejada', 'em_execucao', 'concluida', 'cancelada'];
    private $modalidades = ['presencial', 'online', 'hibrida'];
    private $papeis      = ['presidencia', 'membro', 'secretario', 'convidado'];

    public function get_statuses()    { return $this->statuses; }
    public function get_modalidades() { return $this->modalidades; }
    public function get_papeis()      { return $this->papeis; }

    public function get_status_label($s)
    {
        return ['planejada' => 'Planejada', 'em_execucao' => 'Em execução', 'concluida' => 'Concluída', 'cancelada' => 'Cancelada'][$s] ?? ucfirst($s);
    }

    public function get_status_color($s)
    {
        return ['planejada' => '#94a3b8', 'em_execucao' => '#0a66c2', 'concluida' => '#16a34a', 'cancelada' => '#737373'][$s] ?? '#94a3b8';
    }

    public function get_modalidade_label($m)
    {
        return ['presencial' => 'Presencial', 'online' => 'Online', 'hibrida' => 'Híbrida'][$m] ?? $m;
    }

    public function get_papel_label($p)
    {
        return ['presidencia' => 'Presidência', 'membro' => 'Membro', 'secretario' => 'Secretário(a)', 'convidado' => 'Convidado'][$p] ?? $p;
    }

    /* ============ Listagem ============ */

    public function listar($filtros = [])
    {
        $empresa_id = (int) $this->session->userdata('empresa_id');
        $me = (int) get_staff_user_id();

        $this->db->select("r.*, p.name AS project_name,
                           CONCAT_WS(' ', s.firstname, s.lastname) AS presidida_nome,
                           CONCAT_WS(' ', sc.firstname, sc.lastname) AS criador_nome,
                           (SELECT COUNT(*) FROM tbl_rac_participantes pp WHERE pp.rac_id = r.id AND pp.deleted = 0) AS total_participantes,
                           (SELECT COUNT(*) FROM tbl_rac_participantes pp WHERE pp.rac_id = r.id AND pp.deleted = 0 AND pp.presente = 1) AS total_presentes");
        $this->db->from('tbl_rac r');
        $this->db->join('tblprojects p',  'p.id = r.project_id',           'left');
        $this->db->join('tblstaff s',     's.staffid = r.presidida_por_id','left');
        $this->db->join('tblstaff sc',    'sc.staffid = r.user_create',    'left');
        $this->db->where('r.deleted', 0);
        $this->db->where('r.empresa_id', $empresa_id);

        if (!empty($filtros['status']))         $this->db->where('r.status', $filtros['status']);
        if (!empty($filtros['project_id']))     $this->db->where('r.project_id', (int) $filtros['project_id']);
        if (!empty($filtros['ano']))            $this->db->where('YEAR(r.periodo_fim)', (int) $filtros['ano']);
        if (!empty($filtros['criei']))          $this->db->where('r.user_create', $me);
        if (!empty($filtros['responsavel_meu']))$this->db->where('r.presidida_por_id', $me);
        if (!empty($filtros['busca'])) {
            $term = $this->db->escape_like_str($filtros['busca']);
            $this->db->where("(r.titulo LIKE '%{$term}%' OR r.codigo LIKE '%{$term}%')", null, false);
        }

        $this->db->order_by('r.periodo_fim', 'desc')->order_by('r.id', 'desc');
        return $this->db->get()->result_array();
    }

    public function get($id)
    {
        $empresa_id = (int) $this->session->userdata('empresa_id');
        return $this->db->select("r.*, p.name AS project_name,
                                  CONCAT_WS(' ', s.firstname, s.lastname) AS presidida_nome,
                                  CONCAT_WS(' ', sec.firstname, sec.lastname) AS secretario_nome")
            ->from('tbl_rac r')
            ->join('tblprojects p', 'p.id = r.project_id', 'left')
            ->join('tblstaff s',    's.staffid = r.presidida_por_id', 'left')
            ->join('tblstaff sec',  'sec.staffid = r.secretario_id', 'left')
            ->where('r.id', (int) $id)
            ->where('r.empresa_id', $empresa_id)
            ->where('r.deleted', 0)
            ->get()->row_array();
    }

    public function pode_editar($r, $staff_id = null)
    {
        $staff_id = $staff_id ?? (int) get_staff_user_id();
        if ((int) ($r['user_create'] ?? 0) === $staff_id) return true;
        if ((int) ($r['presidida_por_id'] ?? 0) === $staff_id) return true;
        if ((int) ($r['secretario_id'] ?? 0) === $staff_id) return true;
        return is_admin();
    }

    /* ============ CRUD ============ */

    public function save($data, $id = null)
    {
        $empresa_id = (int) $this->session->userdata('empresa_id');
        $me = (int) get_staff_user_id();

        $clean = [
            'codigo'                => trim((string) ($data['codigo'] ?? '')),
            'titulo'                => trim((string) ($data['titulo'] ?? 'RAC sem título')),
            'periodo_inicio'        => !empty($data['periodo_inicio']) ? $data['periodo_inicio'] : date('Y-01-01'),
            'periodo_fim'           => !empty($data['periodo_fim']) ? $data['periodo_fim'] : date('Y-12-31'),
            'dt_realizacao'         => !empty($data['dt_realizacao']) ? $data['dt_realizacao'] : null,
            'modalidade'            => in_array($data['modalidade'] ?? '', $this->modalidades, true) ? $data['modalidade'] : 'presencial',
            'local'                 => $data['local'] ?? null,
            'presidida_por_id'      => !empty($data['presidida_por_id']) ? (int) $data['presidida_por_id'] : null,
            'secretario_id'         => !empty($data['secretario_id']) ? (int) $data['secretario_id'] : null,
            'escopo'                => $data['escopo'] ?? null,
            'analise_indicadores'   => $data['analise_indicadores'] ?? null,
            'analise_riscos'        => $data['analise_riscos'] ?? null,
            'analise_auditorias'    => $data['analise_auditorias'] ?? null,
            'analise_ocorrencias'   => $data['analise_ocorrencias'] ?? null,
            'analise_satisfacao'    => $data['analise_satisfacao'] ?? null,
            'analise_oportunidades' => $data['analise_oportunidades'] ?? null,
            'decisoes_estrategicas' => $data['decisoes_estrategicas'] ?? null,
            'recursos_necessarios'  => $data['recursos_necessarios'] ?? null,
            'proxima_rac'           => !empty($data['proxima_rac']) ? $data['proxima_rac'] : null,
            'status'                => in_array($data['status'] ?? '', $this->statuses, true) ? $data['status'] : 'planejada',
            'project_id'            => !empty($data['project_id']) ? (int) $data['project_id'] : null,
        ];

        if ($id) {
            $clean['user_update'] = $me;
            $clean['dt_updated']  = date('Y-m-d H:i:s');
            $this->db->where('id', $id)->where('empresa_id', $empresa_id)
                ->update('tbl_rac', $clean);
            return (int) $id;
        }

        $clean['empresa_id']  = $empresa_id;
        $clean['user_create'] = $me;
        $clean['dt_created']  = date('Y-m-d H:i:s');
        if (empty($clean['codigo'])) {
            $row = $this->db->select_max('id')->where('empresa_id', $empresa_id)
                ->get('tbl_rac')->row();
            $clean['codigo'] = 'RAC-' . date('Y', strtotime($clean['periodo_fim'])) . '-' . str_pad(((int) $row->id) + 1, 2, '0', STR_PAD_LEFT);
        }
        $this->db->insert('tbl_rac', $clean);
        return (int) $this->db->insert_id();
    }

    public function delete($id)
    {
        $empresa_id = (int) $this->session->userdata('empresa_id');
        return $this->db->where('id', $id)->where('empresa_id', $empresa_id)
            ->update('tbl_rac', ['deleted' => 1]);
    }

    /* ============ Participantes ============ */

    public function get_participantes($rac_id)
    {
        return $this->db->select("p.*, CONCAT_WS(' ', s.firstname, s.lastname) AS staff_nome, s.cargo")
            ->from('tbl_rac_participantes p')
            ->join('tblstaff s', 's.staffid = p.staff_id', 'left')
            ->where('p.rac_id', (int) $rac_id)
            ->where('p.deleted', 0)
            ->order_by("FIELD(p.papel, 'presidencia','secretario','membro','convidado')", '', false)
            ->order_by('staff_nome', 'asc')
            ->get()->result_array();
    }

    public function set_participantes($rac_id, $staff_ids)
    {
        $atuais = $this->db->select('id, staff_id')
            ->where('rac_id', (int) $rac_id)->where('deleted', 0)
            ->get('tbl_rac_participantes')->result_array();
        $atuais_map = [];
        foreach ($atuais as $a) $atuais_map[(int) $a['staff_id']] = (int) $a['id'];

        $manter = [];
        foreach ((array) $staff_ids as $sid) {
            $sid = (int) $sid;
            if ($sid <= 0) continue;
            $manter[] = $sid;
            if (!isset($atuais_map[$sid])) {
                $this->db->insert('tbl_rac_participantes', [
                    'rac_id'   => (int) $rac_id,
                    'staff_id' => $sid,
                    'papel'    => 'membro',
                    'presente' => 0,
                ]);
            }
        }
        foreach ($atuais_map as $sid => $rid) {
            if (!in_array($sid, $manter, true)) {
                $this->db->where('id', $rid)->update('tbl_rac_participantes', ['deleted' => 1]);
            }
        }
    }

    /* ============ Snapshot do período (KPIs/riscos/auditorias/etc) ============ */

    public function snapshot($periodo_inicio, $periodo_fim, $project_id = null)
    {
        $empresa_id = (int) $this->session->userdata('empresa_id');
        $pi = $periodo_inicio;
        $pf = $periodo_fim;
        $proj_filter = $project_id ? " AND project_id = " . (int) $project_id : '';
        $proj_filter_a = $project_id ? " AND a.project_id = " . (int) $project_id : '';

        $out = [];

        // Indicadores: pega ativos com último valor + status
        $this->load->model('Indicador_model');
        $filtros_ind = ['status' => 'ativo'];
        if ($project_id) $filtros_ind['project_id'] = $project_id;
        $indicadores = $this->Indicador_model->listar($filtros_ind);
        $out['indicadores_total'] = count($indicadores);
        $out['indicadores_critico'] = 0;
        $out['indicadores_atencao'] = 0;
        $out['indicadores_atingido'] = 0;
        foreach ($indicadores as $i) {
            $st = $i['avaliacao']['status'] ?? '';
            if ($st === 'critico') $out['indicadores_critico']++;
            elseif ($st === 'atencao') $out['indicadores_atencao']++;
            elseif ($st === 'atingido') $out['indicadores_atingido']++;
        }
        $out['indicadores'] = $indicadores;

        // Riscos
        $out['riscos_total'] = (int) $this->db->where('empresa_id', $empresa_id)->where('deleted', 0)
            ->where("status NOT IN ('fechado')", null, false)
            ->where_in('nivel', ['baixo', 'moderado', 'alto', 'critico'])
            ->count_all_results('tbl_riscos');
        $out['riscos_criticos'] = (int) $this->db->where('empresa_id', $empresa_id)->where('deleted', 0)
            ->where("status NOT IN ('fechado')", null, false)
            ->where_in('nivel', ['alto', 'critico'])
            ->count_all_results('tbl_riscos');
        $out['riscos_top'] = $this->db->select('id, codigo, titulo, severidade, nivel')
            ->where('empresa_id', $empresa_id)->where('deleted', 0)
            ->where("status NOT IN ('fechado','mitigado')", null, false)
            ->order_by('severidade', 'desc')->limit(5)
            ->get('tbl_riscos')->result_array();

        // Auditorias do período
        $out['auditorias_total'] = (int) $this->db->where('empresa_id', $empresa_id)->where('deleted', 0)
            ->where('dt_realizada >=', $pi)->where('dt_realizada <=', $pf)
            ->count_all_results('tbl_auditorias');
        $out['auditorias_ncs'] = (int) $this->db->query("
            SELECT COUNT(*) AS n FROM tbl_auditorias_achados ac
            INNER JOIN tbl_auditorias a ON a.id = ac.auditoria_id
            WHERE a.empresa_id = $empresa_id AND a.deleted = 0 AND ac.deleted = 0
              AND ac.tipo IN ('nc_maior','nc_menor')
              AND a.dt_realizada BETWEEN '$pi' AND '$pf'")->row()->n;
        $out['auditorias_ncs_abertas'] = (int) $this->db->query("
            SELECT COUNT(*) AS n FROM tbl_auditorias_achados ac
            INNER JOIN tbl_auditorias a ON a.id = ac.auditoria_id
            WHERE a.empresa_id = $empresa_id AND a.deleted = 0 AND ac.deleted = 0
              AND ac.tipo IN ('nc_maior','nc_menor') AND ac.status != 'fechado'
              AND a.dt_realizada BETWEEN '$pi' AND '$pf'")->row()->n;

        // Ocorrências do período
        $out['ocorrencias_total'] = (int) $this->db->where('empresa_id', $empresa_id)->where('deleted', 0)
            ->where('date >=', $pi)->where('date <=', $pf)
            ->count_all_results('tbl_intranet_registro_ocorrencia');
        $out['ocorrencias_abertas'] = (int) $this->db->where('empresa_id', $empresa_id)->where('deleted', 0)
            ->where('date >=', $pi)->where('date <=', $pf)
            ->where_in('status', [1, 2, 4])
            ->count_all_results('tbl_intranet_registro_ocorrencia');

        // Planos de ação
        $out['planos_total'] = (int) $this->db->where('empresa_id', $empresa_id)->where('deleted', 0)
            ->where_in('status', ['aberto', 'em_execucao'])
            ->count_all_results('tbl_planos_acao');
        $out['planos_atrasados'] = (int) $this->db->where('empresa_id', $empresa_id)->where('deleted', 0)
            ->where_in('status', ['aberto', 'em_execucao'])
            ->where('dt_fim IS NOT NULL', null, false)
            ->where('dt_fim <', date('Y-m-d'))
            ->count_all_results('tbl_planos_acao');

        // Treinamentos do período
        $out['treinamentos_realizados'] = (int) $this->db->where('empresa_id', $empresa_id)->where('deleted', 0)
            ->where('status', 'concluido')
            ->where('dt_inicio >=', $pi)->where('dt_inicio <=', $pf . ' 23:59:59')
            ->count_all_results('tbl_treinamentos');

        // Documentos publicados no período
        $out['docs_publicados'] = (int) $this->db->where('empresa_id', $empresa_id)->where('deleted', 0)
            ->where('publicado', 1)
            ->where('data_publicacao >=', $pi)->where('data_publicacao <=', $pf)
            ->count_all_results('tbl_intranet_documento');

        return $out;
    }
}
