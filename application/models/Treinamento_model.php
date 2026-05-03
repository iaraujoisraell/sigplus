<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Treinamento_model extends App_Model
{
    private $statuses    = ['planejado', 'inscricoes_abertas', 'em_andamento', 'concluido', 'cancelado'];
    private $modalidades = ['presencial', 'online', 'ead', 'autoinstrutivo'];
    private $status_part = ['inscrito', 'presente', 'ausente', 'cancelado'];

    public function get_statuses()    { return $this->statuses; }
    public function get_modalidades() { return $this->modalidades; }

    public function get_status_label($s)
    {
        return [
            'planejado'           => 'Planejado',
            'inscricoes_abertas'  => 'Inscrições abertas',
            'em_andamento'        => 'Em andamento',
            'concluido'           => 'Concluído',
            'cancelado'           => 'Cancelado',
        ][$s] ?? ucfirst($s);
    }

    public function get_status_color($s)
    {
        return [
            'planejado'           => '#94a3b8',
            'inscricoes_abertas'  => '#0a66c2',
            'em_andamento'        => '#f59e0b',
            'concluido'           => '#16a34a',
            'cancelado'           => '#737373',
        ][$s] ?? '#94a3b8';
    }

    public function get_modalidade_label($m)
    {
        return ['presencial' => 'Presencial', 'online' => 'Online (ao vivo)', 'ead' => 'EAD', 'autoinstrutivo' => 'Autoinstrutivo'][$m] ?? $m;
    }

    /* ============ Listagem ============ */

    public function listar($filtros = [])
    {
        $empresa_id = (int) $this->session->userdata('empresa_id');
        $me = (int) get_staff_user_id();

        $this->db->select("t.*, p.name AS project_name,
                           CONCAT_WS(' ', s.firstname, s.lastname) AS instrutor_nome,
                           CONCAT_WS(' ', sc.firstname, sc.lastname) AS criador_nome,
                           d.titulo AS documento_titulo, d.codigo AS documento_codigo,
                           pa.titulo AS plano_titulo,
                           g.titulo AS grupo_titulo,
                           (SELECT COUNT(*) FROM tbl_treinamentos_participantes pp WHERE pp.treinamento_id = t.id AND pp.deleted = 0) AS total_inscritos,
                           (SELECT COUNT(*) FROM tbl_treinamentos_participantes pp WHERE pp.treinamento_id = t.id AND pp.deleted = 0 AND pp.status_inscricao = 'presente') AS total_presentes,
                           (SELECT COUNT(*) FROM tbl_treinamentos_participantes pp WHERE pp.treinamento_id = t.id AND pp.deleted = 0 AND pp.aprovado = 1) AS total_aprovados");
        $this->db->from('tbl_treinamentos t');
        $this->db->join('tblprojects p',                'p.id = t.project_id',         'left');
        $this->db->join('tblstaff s',                   's.staffid = t.instrutor_staff_id', 'left');
        $this->db->join('tblstaff sc',                  'sc.staffid = t.user_create',  'left');
        $this->db->join('tbl_intranet_documento d',     'd.id = t.documento_id',       'left');
        $this->db->join('tbl_planos_acao pa',           'pa.id = t.plano_id',          'left');
        $this->db->join('tbl_grupos g',                 'g.id = t.grupo_id',           'left');
        $this->db->where('t.deleted', 0);
        $this->db->where('t.empresa_id', $empresa_id);

        if (!empty($filtros['project_id']))      $this->db->where('t.project_id', (int) $filtros['project_id']);
        if (!empty($filtros['plano_id']))        $this->db->where('t.plano_id',   (int) $filtros['plano_id']);
        if (!empty($filtros['grupo_id']))        $this->db->where('t.grupo_id',   (int) $filtros['grupo_id']);
        if (!empty($filtros['documento_id']))    $this->db->where('t.documento_id',(int) $filtros['documento_id']);
        if (!empty($filtros['status']))          $this->db->where('t.status', $filtros['status']);
        if (!empty($filtros['modalidade']))      $this->db->where('t.modalidade', $filtros['modalidade']);
        if (!empty($filtros['criei']))           $this->db->where('t.user_create', $me);
        if (!empty($filtros['responsavel_meu'])) $this->db->where('t.instrutor_staff_id', $me);
        if (!empty($filtros['inscrito_eu'])) {
            $this->db->where("EXISTS (SELECT 1 FROM tbl_treinamentos_participantes pp
                WHERE pp.treinamento_id = t.id AND pp.staff_id = $me AND pp.deleted = 0)", null, false);
        }
        if (!empty($filtros['busca'])) {
            $term = $this->db->escape_like_str($filtros['busca']);
            $this->db->where("(t.titulo LIKE '%{$term}%' OR t.descricao LIKE '%{$term}%' OR t.codigo LIKE '%{$term}%')", null, false);
        }

        $this->db->order_by('t.dt_inicio', 'desc')->order_by('t.id', 'desc');
        return $this->db->get()->result_array();
    }

    public function get($id)
    {
        $empresa_id = (int) $this->session->userdata('empresa_id');
        return $this->db->select("t.*, p.name AS project_name,
                                  CONCAT_WS(' ', s.firstname, s.lastname) AS instrutor_nome,
                                  d.titulo AS documento_titulo, d.codigo AS documento_codigo,
                                  pa.titulo AS plano_titulo,
                                  g.titulo AS grupo_titulo")
            ->from('tbl_treinamentos t')
            ->join('tblprojects p',            'p.id = t.project_id',          'left')
            ->join('tblstaff s',               's.staffid = t.instrutor_staff_id', 'left')
            ->join('tbl_intranet_documento d', 'd.id = t.documento_id',        'left')
            ->join('tbl_planos_acao pa',       'pa.id = t.plano_id',           'left')
            ->join('tbl_grupos g',             'g.id = t.grupo_id',            'left')
            ->where('t.id', (int) $id)
            ->where('t.empresa_id', $empresa_id)
            ->where('t.deleted', 0)
            ->get()->row_array();
    }

    public function pode_editar($t, $staff_id = null)
    {
        $staff_id = $staff_id ?? (int) get_staff_user_id();
        if ((int) ($t['user_create'] ?? 0) === $staff_id) return true;
        if ((int) ($t['instrutor_staff_id'] ?? 0) === $staff_id) return true;
        return is_admin();
    }

    /* ============ CRUD ============ */

    public function save($data, $id = null)
    {
        $empresa_id = (int) $this->session->userdata('empresa_id');
        $me = (int) get_staff_user_id();

        $clean = [
            'codigo'                => trim((string) ($data['codigo'] ?? '')),
            'titulo'                => trim((string) ($data['titulo'] ?? 'Treinamento sem título')),
            'descricao'             => $data['descricao'] ?? null,
            'conteudo_programatico' => $data['conteudo_programatico'] ?? null,
            'modalidade'            => in_array($data['modalidade'] ?? '', $this->modalidades, true) ? $data['modalidade'] : 'presencial',
            'carga_horaria'         => isset($data['carga_horaria']) && $data['carga_horaria'] !== '' ? (float) str_replace(',', '.', $data['carga_horaria']) : null,
            'dt_inicio'             => !empty($data['dt_inicio']) ? $data['dt_inicio'] : null,
            'dt_fim'                => !empty($data['dt_fim']) ? $data['dt_fim'] : null,
            'local'                 => $data['local'] ?? null,
            'instrutor_staff_id'    => !empty($data['instrutor_staff_id']) ? (int) $data['instrutor_staff_id'] : null,
            'instrutor_externo'     => $data['instrutor_externo'] ?? null,
            'project_id'            => !empty($data['project_id']) ? (int) $data['project_id'] : null,
            'fase_id'               => !empty($data['fase_id']) ? (int) $data['fase_id'] : null,
            'plano_id'              => !empty($data['plano_id']) ? (int) $data['plano_id'] : null,
            'grupo_id'              => !empty($data['grupo_id']) ? (int) $data['grupo_id'] : null,
            'documento_id'          => !empty($data['documento_id']) ? (int) $data['documento_id'] : null,
            'status'                => in_array($data['status'] ?? '', $this->statuses, true) ? $data['status'] : 'planejado',
            'nota_minima'           => isset($data['nota_minima']) && $data['nota_minima'] !== '' ? (float) str_replace(',', '.', $data['nota_minima']) : null,
            'emite_certificado'     => !empty($data['emite_certificado']) ? 1 : 0,
        ];

        if ($id) {
            $clean['user_update'] = $me;
            $clean['dt_updated']  = date('Y-m-d H:i:s');
            $this->db->where('id', $id)->where('empresa_id', $empresa_id)
                ->update('tbl_treinamentos', $clean);
            return (int) $id;
        }

        $clean['empresa_id']  = $empresa_id;
        $clean['user_create'] = $me;
        $clean['dt_created']  = date('Y-m-d H:i:s');
        if (empty($clean['codigo'])) {
            $row = $this->db->select_max('id')->where('empresa_id', $empresa_id)
                ->get('tbl_treinamentos')->row();
            $clean['codigo'] = 'TRN-' . str_pad(((int) $row->id) + 1, 4, '0', STR_PAD_LEFT);
        }
        $this->db->insert('tbl_treinamentos', $clean);
        return (int) $this->db->insert_id();
    }

    public function delete($id)
    {
        $empresa_id = (int) $this->session->userdata('empresa_id');
        return $this->db->where('id', $id)->where('empresa_id', $empresa_id)
            ->update('tbl_treinamentos', ['deleted' => 1]);
    }

    /* ============ Participantes ============ */

    public function get_participantes($treinamento_id)
    {
        return $this->db->select("p.*, CONCAT_WS(' ', s.firstname, s.lastname) AS staff_nome, s.profile_image, s.cargo")
            ->from('tbl_treinamentos_participantes p')
            ->join('tblstaff s', 's.staffid = p.staff_id', 'left')
            ->where('p.treinamento_id', (int) $treinamento_id)
            ->where('p.deleted', 0)
            ->order_by('staff_nome', 'asc')
            ->get()->result_array();
    }

    public function set_participantes($treinamento_id, $staff_ids)
    {
        $atuais = $this->db->select('id, staff_id')
            ->where('treinamento_id', (int) $treinamento_id)->where('deleted', 0)
            ->get('tbl_treinamentos_participantes')->result_array();
        $atuais_map = [];
        foreach ($atuais as $a) $atuais_map[(int) $a['staff_id']] = (int) $a['id'];

        $manter = [];
        foreach ((array) $staff_ids as $sid) {
            $sid = (int) $sid;
            if ($sid <= 0) continue;
            $manter[] = $sid;
            if (!isset($atuais_map[$sid])) {
                $this->db->insert('tbl_treinamentos_participantes', [
                    'treinamento_id'   => (int) $treinamento_id,
                    'staff_id'         => $sid,
                    'status_inscricao' => 'inscrito',
                    'dt_inscricao'     => date('Y-m-d H:i:s'),
                ]);
            }
        }
        foreach ($atuais_map as $sid => $rid) {
            if (!in_array($sid, $manter, true)) {
                $this->db->where('id', $rid)->update('tbl_treinamentos_participantes', ['deleted' => 1]);
            }
        }
    }

    public function marcar_presenca($participante_id, $presente = true)
    {
        return $this->db->where('id', (int) $participante_id)
            ->update('tbl_treinamentos_participantes', [
                'status_inscricao' => $presente ? 'presente' : 'ausente',
                'dt_presenca'      => $presente ? date('Y-m-d H:i:s') : null,
            ]);
    }

    public function lancar_nota($participante_id, $nota, $aprovado = null)
    {
        $payload = ['nota' => (float) str_replace(',', '.', $nota)];
        if ($aprovado !== null) $payload['aprovado'] = $aprovado ? 1 : 0;
        return $this->db->where('id', (int) $participante_id)
            ->update('tbl_treinamentos_participantes', $payload);
    }
}
