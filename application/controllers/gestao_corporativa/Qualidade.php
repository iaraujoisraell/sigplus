<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Qualidade extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        if (!is_logged_in()) redirect(base_url('admin/authentication'));
        $this->load->model('Indicador_model');
        $this->load->model('Documentos_model');
        $this->load->model('Plano_acao_model');
        $this->load->model('Risco_model');
        $this->load->model('Auditoria_model');
        $this->load->model('Treinamento_model');
        $this->load->model('Rac_model');
    }

    public function index()
    {
        $empresa_id = (int) $this->session->userdata('empresa_id');

        // ---- Indicadores ----
        $indicadores  = $this->Indicador_model->listar(['status' => 'ativo']);
        $ind_critico  = array_values(array_filter($indicadores, function ($i) { return ($i['avaliacao']['status'] ?? '') === 'critico'; }));
        $ind_atencao  = array_values(array_filter($indicadores, function ($i) { return ($i['avaliacao']['status'] ?? '') === 'atencao'; }));
        $ind_atingido = array_values(array_filter($indicadores, function ($i) { return ($i['avaliacao']['status'] ?? '') === 'atingido'; }));

        // ---- Riscos ----
        $riscos_total   = (int) $this->db->where('empresa_id', $empresa_id)->where('deleted', 0)
            ->where("status NOT IN ('fechado','mitigado')", null, false)
            ->count_all_results('tbl_riscos');
        $riscos_criticos = (int) $this->db->where('empresa_id', $empresa_id)->where('deleted', 0)
            ->where("status NOT IN ('fechado','mitigado')", null, false)
            ->where_in('nivel', ['alto', 'critico'])
            ->count_all_results('tbl_riscos');
        $riscos_revisao_vencida = (int) $this->db->where('empresa_id', $empresa_id)->where('deleted', 0)
            ->where("status NOT IN ('fechado','mitigado')", null, false)
            ->where('dt_revisao IS NOT NULL', null, false)
            ->where('dt_revisao <', date('Y-m-d'))
            ->count_all_results('tbl_riscos');
        $riscos_top = $this->db->select("r.id, r.codigo, r.titulo, r.severidade, r.nivel, r.status, r.categoria,
                                         CONCAT_WS(' ', s.firstname, s.lastname) AS responsavel_nome")
            ->from('tbl_riscos r')
            ->join('tblstaff s', 's.staffid = r.responsavel_id', 'left')
            ->where('r.empresa_id', $empresa_id)->where('r.deleted', 0)
            ->where("r.status NOT IN ('fechado','mitigado')", null, false)
            ->order_by('r.severidade', 'desc')->limit(6)
            ->get()->result_array();

        // ---- Auditorias / Achados ----
        $achados_ncs_abertas = (int) $this->db->query("
            SELECT COUNT(*) AS n FROM tbl_auditorias_achados ac
            INNER JOIN tbl_auditorias a ON a.id = ac.auditoria_id
            WHERE a.empresa_id = $empresa_id AND a.deleted = 0 AND ac.deleted = 0
              AND ac.tipo IN ('nc_maior','nc_menor') AND ac.status != 'fechado'")->row()->n;
        $aud_planejadas = (int) $this->db->where('empresa_id', $empresa_id)->where('deleted', 0)
            ->where('status', 'planejada')
            ->count_all_results('tbl_auditorias');
        $aud_em_execucao = (int) $this->db->where('empresa_id', $empresa_id)->where('deleted', 0)
            ->where('status', 'em_execucao')
            ->count_all_results('tbl_auditorias');
        $achados_recentes = $this->db->select("ac.id, ac.tipo, ac.descricao, ac.status, ac.prazo_tratamento,
                                               a.codigo AS aud_codigo, a.id AS aud_id, a.titulo AS aud_titulo,
                                               CONCAT_WS(' ', s.firstname, s.lastname) AS responsavel_nome")
            ->from('tbl_auditorias_achados ac')
            ->join('tbl_auditorias a', 'a.id = ac.auditoria_id')
            ->join('tblstaff s', 's.staffid = ac.responsavel_id', 'left')
            ->where('a.empresa_id', $empresa_id)->where('a.deleted', 0)->where('ac.deleted', 0)
            ->where_in('ac.tipo', ['nc_maior', 'nc_menor'])
            ->where('ac.status !=', 'fechado')
            ->order_by("FIELD(ac.tipo, 'nc_maior','nc_menor')", '', false)
            ->order_by('ac.prazo_tratamento', 'asc')->limit(6)
            ->get()->result_array();

        // ---- Treinamentos ----
        $trein_em_andamento = (int) $this->db->where('empresa_id', $empresa_id)->where('deleted', 0)
            ->where_in('status', ['planejado', 'inscricoes_abertas', 'em_andamento'])
            ->count_all_results('tbl_treinamentos');
        $trein_atrasados = (int) $this->db->where('empresa_id', $empresa_id)->where('deleted', 0)
            ->where_in('status', ['planejado', 'inscricoes_abertas', 'em_andamento'])
            ->where('dt_fim IS NOT NULL', null, false)
            ->where('dt_fim <', date('Y-m-d'))
            ->count_all_results('tbl_treinamentos');
        $trein_proximos = $this->db->select("t.id, t.codigo, t.titulo, t.status, t.dt_inicio, t.modalidade,
                                             CONCAT_WS(' ', s.firstname, s.lastname) AS instrutor_nome,
                                             (SELECT COUNT(*) FROM tbl_treinamentos_participantes pp WHERE pp.treinamento_id = t.id AND pp.deleted = 0) AS total_inscritos")
            ->from('tbl_treinamentos t')
            ->join('tblstaff s', 's.staffid = t.instrutor_staff_id', 'left')
            ->where('t.empresa_id', $empresa_id)->where('t.deleted', 0)
            ->where_in('t.status', ['planejado', 'inscricoes_abertas', 'em_andamento'])
            ->order_by('t.dt_inicio', 'asc')->limit(5)
            ->get()->result_array();

        // ---- Ocorrências ----
        $ocorr_total_abertas = (int) $this->db->where('empresa_id', $empresa_id)
            ->where('deleted', 0)->where_in('status', [1, 2, 4])
            ->count_all_results('tbl_intranet_registro_ocorrencia');
        $ocorr_atrasadas = (int) $this->db->where('empresa_id', $empresa_id)
            ->where('deleted', 0)->where_in('status', [1, 2, 4])
            ->where('validade IS NOT NULL', null, false)
            ->where('validade <', date('Y-m-d'))
            ->count_all_results('tbl_intranet_registro_ocorrencia');
        $ocorr_recentes = $this->db->select("o.id, o.subject, o.date, o.status, o.priority, o.validade,
                                             CONCAT_WS(' ', s.firstname, s.lastname) AS responsavel_nome,
                                             c.titulo AS categoria_nome")
            ->from('tbl_intranet_registro_ocorrencia o')
            ->join('tblstaff s', 's.staffid = o.atribuido_a', 'left')
            ->join('tbl_intranet_registro_ocorrencias_categorias c', 'c.id = o.categoria_id', 'left')
            ->where('o.empresa_id', $empresa_id)->where('o.deleted', 0)
            ->where_in('o.status', [1, 2, 4])
            ->order_by('o.date', 'desc')->limit(6)
            ->get()->result_array();

        // ---- Planos de Ação ----
        $plano_total_ativos = (int) $this->db->where('empresa_id', $empresa_id)
            ->where('deleted', 0)->where_in('status', ['aberto', 'em_execucao'])
            ->count_all_results('tbl_planos_acao');
        $plano_atrasados = (int) $this->db->where('empresa_id', $empresa_id)
            ->where('deleted', 0)->where_in('status', ['aberto', 'em_execucao'])
            ->where('dt_fim IS NOT NULL', null, false)
            ->where('dt_fim <', date('Y-m-d'))
            ->count_all_results('tbl_planos_acao');
        $planos_recentes = $this->db->select("pa.id, pa.titulo, pa.status, pa.dt_fim, pa.metodologia,
                                              CONCAT_WS(' ', s.firstname, s.lastname) AS responsavel_nome,
                                              p.name AS project_name")
            ->from('tbl_planos_acao pa')
            ->join('tblstaff s', 's.staffid = pa.responsavel_id', 'left')
            ->join('tblprojects p', 'p.id = pa.project_id', 'left')
            ->where('pa.empresa_id', $empresa_id)->where('pa.deleted', 0)
            ->where_in('pa.status', ['aberto', 'em_execucao'])
            ->order_by('pa.dt_fim', 'asc')->limit(6)
            ->get()->result_array();

        // ---- Documentos ----
        $doc_total_publicados = (int) $this->db->where('empresa_id', $empresa_id)
            ->where('deleted', 0)->where('publicado', 1)
            ->count_all_results('tbl_intranet_documento');
        $doc_baixa_cobertura = $this->db->select("d.id, d.codigo, d.titulo,
                                                  (SELECT COUNT(*) FROM tbl_intranet_documento_send se WHERE se.documento_id = d.id AND se.deleted = 0) AS total,
                                                  (SELECT COUNT(*) FROM tbl_intranet_documento_send se WHERE se.documento_id = d.id AND se.deleted = 0 AND se.lido = 1) AS lidos")
            ->from('tbl_intranet_documento d')
            ->where('d.empresa_id', $empresa_id)->where('d.deleted', 0)->where('d.publicado', 1)
            ->order_by('d.id', 'desc')->limit(20)
            ->get()->result_array();
        $doc_baixa_cobertura = array_map(function ($d) {
            $d['pct'] = $d['total'] > 0 ? round($d['lidos'] * 100 / $d['total']) : null;
            return $d;
        }, $doc_baixa_cobertura);
        $doc_baixa_cobertura = array_values(array_filter($doc_baixa_cobertura, function ($d) {
            return $d['total'] > 0 && $d['pct'] < 80;
        }));
        $doc_baixa_cobertura = array_slice($doc_baixa_cobertura, 0, 5);

        // ---- RAC: próxima planejada / última realizada ----
        $rac_proxima = $this->db->select("r.id, r.codigo, r.titulo, r.dt_realizacao, r.periodo_inicio, r.periodo_fim, r.status")
            ->from('tbl_rac r')
            ->where('r.empresa_id', $empresa_id)->where('r.deleted', 0)
            ->where_in('r.status', ['planejada', 'em_execucao'])
            ->order_by('r.dt_realizacao', 'asc')->limit(1)
            ->get()->row_array();
        $rac_ultima = $this->db->select("r.id, r.codigo, r.titulo, r.dt_realizacao")
            ->from('tbl_rac r')
            ->where('r.empresa_id', $empresa_id)->where('r.deleted', 0)
            ->where('r.status', 'concluida')
            ->order_by('r.dt_realizacao', 'desc')->limit(1)
            ->get()->row_array();

        // ---- Atendimentos ----
        $atend_abertos = (int) $this->db->where('empresa_id', $empresa_id)
            ->where('deleted', 0)
            ->where('data_encerramento IS NULL', null, false)
            ->count_all_results('tbl_intranet_registro_atendimento');

        $data = [
            'title'              => 'Dashboard de Qualidade',
            'ind_critico'        => $ind_critico,
            'ind_atencao'        => $ind_atencao,
            'ind_atingido'       => $ind_atingido,
            'riscos_total'       => $riscos_total,
            'riscos_criticos'    => $riscos_criticos,
            'riscos_revisao_vencida' => $riscos_revisao_vencida,
            'riscos_top'         => $riscos_top,
            'achados_ncs_abertas'=> $achados_ncs_abertas,
            'aud_planejadas'     => $aud_planejadas,
            'aud_em_execucao'    => $aud_em_execucao,
            'achados_recentes'   => $achados_recentes,
            'trein_em_andamento' => $trein_em_andamento,
            'trein_atrasados'    => $trein_atrasados,
            'trein_proximos'     => $trein_proximos,
            'ocorr_total_abertas'=> $ocorr_total_abertas,
            'ocorr_atrasadas'    => $ocorr_atrasadas,
            'ocorr_recentes'     => $ocorr_recentes,
            'plano_total_ativos' => $plano_total_ativos,
            'plano_atrasados'    => $plano_atrasados,
            'planos_recentes'    => $planos_recentes,
            'doc_total_publicados'=> $doc_total_publicados,
            'doc_baixa_cobertura'=> $doc_baixa_cobertura,
            'atend_abertos'      => $atend_abertos,
            'rac_proxima'        => $rac_proxima,
            'rac_ultima'         => $rac_ultima,
        ];
        $this->load->view('gestao_corporativa/qualidade/dashboard', $data);
    }
}
