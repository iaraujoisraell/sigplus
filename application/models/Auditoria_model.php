<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Auditoria_model extends App_Model
{
    private $statuses   = ['planejada', 'em_execucao', 'encerrada', 'cancelada'];
    private $tipos      = ['interna', 'externa', 'certificacao', 'follow_up'];
    private $resultados = ['pendente', 'conforme', 'parcial', 'nao_conforme'];
    private $tipos_achado = ['nc_maior', 'nc_menor', 'observacao', 'oportunidade', 'conformidade'];
    private $status_achado = ['aberto', 'em_tratamento', 'fechado'];

    public function get_statuses()    { return $this->statuses; }
    public function get_tipos()       { return $this->tipos; }
    public function get_resultados()  { return $this->resultados; }
    public function get_tipos_achado(){ return $this->tipos_achado; }
    public function get_status_achado(){ return $this->status_achado; }

    public function get_status_label($s)
    {
        return ['planejada'=>'Planejada','em_execucao'=>'Em execução','encerrada'=>'Encerrada','cancelada'=>'Cancelada'][$s] ?? ucfirst($s);
    }
    public function get_status_color($s)
    {
        return ['planejada'=>'#94a3b8','em_execucao'=>'#0a66c2','encerrada'=>'#16a34a','cancelada'=>'#737373'][$s] ?? '#94a3b8';
    }
    public function get_tipo_label($t)
    {
        return ['interna'=>'Interna','externa'=>'Externa','certificacao'=>'Certificação','follow_up'=>'Follow-up'][$t] ?? $t;
    }
    public function get_resultado_label($r)
    {
        return ['pendente'=>'Pendente','conforme'=>'Conforme','parcial'=>'Conforme com ressalvas','nao_conforme'=>'Não conforme'][$r] ?? $r;
    }
    public function get_resultado_color($r)
    {
        return ['pendente'=>'#94a3b8','conforme'=>'#16a34a','parcial'=>'#f59e0b','nao_conforme'=>'#dc2626'][$r] ?? '#94a3b8';
    }
    public function get_tipo_achado_label($t)
    {
        return ['nc_maior'=>'NC Maior','nc_menor'=>'NC Menor','observacao'=>'Observação','oportunidade'=>'Oportunidade de melhoria','conformidade'=>'Conformidade'][$t] ?? $t;
    }
    public function get_tipo_achado_color($t)
    {
        return ['nc_maior'=>'#dc2626','nc_menor'=>'#ea580c','observacao'=>'#f59e0b','oportunidade'=>'#0a66c2','conformidade'=>'#16a34a'][$t] ?? '#94a3b8';
    }
    public function get_status_achado_label($s)
    {
        return ['aberto'=>'Aberto','em_tratamento'=>'Em tratamento','fechado'=>'Fechado'][$s] ?? $s;
    }

    /* ============ Listagem ============ */

    public function listar($filtros = [])
    {
        $empresa_id = (int) $this->session->userdata('empresa_id');
        $me = (int) get_staff_user_id();

        $this->db->select("a.*, dep.name AS setor_nome, p.name AS project_name,
                           CONCAT_WS(' ', s.firstname, s.lastname) AS auditor_nome,
                           CONCAT_WS(' ', sc.firstname, sc.lastname) AS criador_nome,
                           d.titulo AS doc_titulo, d.codigo AS doc_codigo,
                           f.titulo AS form_titulo,
                           (SELECT COUNT(*) FROM tbl_auditorias_achados ac WHERE ac.auditoria_id = a.id AND ac.deleted = 0) AS total_achados,
                           (SELECT COUNT(*) FROM tbl_auditorias_achados ac WHERE ac.auditoria_id = a.id AND ac.deleted = 0 AND ac.tipo IN ('nc_maior','nc_menor')) AS total_ncs,
                           (SELECT COUNT(*) FROM tbl_auditorias_achados ac WHERE ac.auditoria_id = a.id AND ac.deleted = 0 AND ac.status = 'fechado') AS total_fechados");
        $this->db->from('tbl_auditorias a');
        $this->db->join('tbldepartments dep',  'dep.departmentid = a.setor_auditado_id', 'left');
        $this->db->join('tblprojects p',       'p.id = a.project_id',                    'left');
        $this->db->join('tblstaff s',          's.staffid = a.auditor_lider_id',         'left');
        $this->db->join('tblstaff sc',         'sc.staffid = a.user_create',             'left');
        $this->db->join('tbl_intranet_documento d', 'd.id = a.documento_referencia_id', 'left');
        $this->db->join('tbl_intranet_formularios f','f.id = a.formulario_id',          'left');
        $this->db->where('a.deleted', 0);
        $this->db->where('a.empresa_id', $empresa_id);

        if (!empty($filtros['tipo']))           $this->db->where('a.tipo', $filtros['tipo']);
        if (!empty($filtros['status']))         $this->db->where('a.status', $filtros['status']);
        if (!empty($filtros['resultado']))      $this->db->where('a.resultado', $filtros['resultado']);
        if (!empty($filtros['setor_id']))       $this->db->where('a.setor_auditado_id', (int) $filtros['setor_id']);
        if (!empty($filtros['project_id']))     $this->db->where('a.project_id', (int) $filtros['project_id']);
        if (!empty($filtros['plano_id']))       $this->db->where('a.plano_id', (int) $filtros['plano_id']);
        if (!empty($filtros['grupo_id']))       $this->db->where('a.grupo_id', (int) $filtros['grupo_id']);
        if (!empty($filtros['documento_id']))   $this->db->where('a.documento_referencia_id', (int) $filtros['documento_id']);
        if (!empty($filtros['criei']))          $this->db->where('a.user_create', $me);
        if (!empty($filtros['responsavel_meu']))$this->db->where('a.auditor_lider_id', $me);
        if (!empty($filtros['busca'])) {
            $term = $this->db->escape_like_str($filtros['busca']);
            $this->db->where("(a.titulo LIKE '%{$term}%' OR a.escopo LIKE '%{$term}%' OR a.codigo LIKE '%{$term}%')", null, false);
        }

        $this->db->order_by('a.dt_planejada', 'desc')->order_by('a.id', 'desc');
        return $this->db->get()->result_array();
    }

    public function get($id)
    {
        $empresa_id = (int) $this->session->userdata('empresa_id');
        return $this->db->select("a.*, dep.name AS setor_nome, p.name AS project_name,
                                  CONCAT_WS(' ', s.firstname, s.lastname) AS auditor_nome,
                                  d.titulo AS doc_titulo, d.codigo AS doc_codigo,
                                  f.titulo AS form_titulo")
            ->from('tbl_auditorias a')
            ->join('tbldepartments dep',  'dep.departmentid = a.setor_auditado_id', 'left')
            ->join('tblprojects p',       'p.id = a.project_id',                    'left')
            ->join('tblstaff s',          's.staffid = a.auditor_lider_id',         'left')
            ->join('tbl_intranet_documento d', 'd.id = a.documento_referencia_id', 'left')
            ->join('tbl_intranet_formularios f','f.id = a.formulario_id',          'left')
            ->where('a.id', (int) $id)
            ->where('a.empresa_id', $empresa_id)
            ->where('a.deleted', 0)
            ->get()->row_array();
    }

    public function pode_editar($a, $staff_id = null)
    {
        $staff_id = $staff_id ?? (int) get_staff_user_id();
        if ((int) ($a['user_create'] ?? 0) === $staff_id) return true;
        if ((int) ($a['auditor_lider_id'] ?? 0) === $staff_id) return true;
        return is_admin();
    }

    /* ============ CRUD ============ */

    public function save($data, $id = null)
    {
        $empresa_id = (int) $this->session->userdata('empresa_id');
        $me = (int) get_staff_user_id();

        $clean = [
            'codigo'                  => trim((string) ($data['codigo'] ?? '')),
            'titulo'                  => trim((string) ($data['titulo'] ?? 'Auditoria sem título')),
            'descricao'               => $data['descricao'] ?? null,
            'tipo'                    => in_array($data['tipo'] ?? '', $this->tipos, true) ? $data['tipo'] : 'interna',
            'escopo'                  => $data['escopo'] ?? null,
            'norma_referencia'        => $data['norma_referencia'] ?? null,
            'dt_planejada'            => !empty($data['dt_planejada']) ? $data['dt_planejada'] : null,
            'dt_realizada'            => !empty($data['dt_realizada']) ? $data['dt_realizada'] : null,
            'duracao_horas'           => isset($data['duracao_horas']) && $data['duracao_horas'] !== '' ? (float) str_replace(',', '.', $data['duracao_horas']) : null,
            'auditor_lider_id'        => !empty($data['auditor_lider_id']) ? (int) $data['auditor_lider_id'] : null,
            'setor_auditado_id'       => !empty($data['setor_auditado_id']) ? (int) $data['setor_auditado_id'] : null,
            'documento_referencia_id' => !empty($data['documento_referencia_id']) ? (int) $data['documento_referencia_id'] : null,
            'formulario_id'           => !empty($data['formulario_id']) ? (int) $data['formulario_id'] : null,
            'status'                  => in_array($data['status'] ?? '', $this->statuses, true) ? $data['status'] : 'planejada',
            'resultado'               => in_array($data['resultado'] ?? '', $this->resultados, true) ? $data['resultado'] : 'pendente',
            'project_id'              => !empty($data['project_id']) ? (int) $data['project_id'] : null,
            'fase_id'                 => !empty($data['fase_id']) ? (int) $data['fase_id'] : null,
            'plano_id'                => !empty($data['plano_id']) ? (int) $data['plano_id'] : null,
            'grupo_id'                => !empty($data['grupo_id']) ? (int) $data['grupo_id'] : null,
        ];

        if ($id) {
            $clean['user_update'] = $me;
            $clean['dt_updated']  = date('Y-m-d H:i:s');
            $this->db->where('id', $id)->where('empresa_id', $empresa_id)
                ->update('tbl_auditorias', $clean);
            return (int) $id;
        }

        $clean['empresa_id']  = $empresa_id;
        $clean['user_create'] = $me;
        $clean['dt_created']  = date('Y-m-d H:i:s');
        if (empty($clean['codigo'])) {
            $row = $this->db->select_max('id')->where('empresa_id', $empresa_id)
                ->get('tbl_auditorias')->row();
            $clean['codigo'] = 'AUD-' . str_pad(((int) $row->id) + 1, 4, '0', STR_PAD_LEFT);
        }
        $this->db->insert('tbl_auditorias', $clean);
        return (int) $this->db->insert_id();
    }

    public function delete($id)
    {
        $empresa_id = (int) $this->session->userdata('empresa_id');
        return $this->db->where('id', $id)->where('empresa_id', $empresa_id)
            ->update('tbl_auditorias', ['deleted' => 1]);
    }

    /* ============ Auditados ============ */

    public function get_auditados($auditoria_id)
    {
        return $this->db->select("au.*, CONCAT_WS(' ', s.firstname, s.lastname) AS staff_nome, s.cargo")
            ->from('tbl_auditorias_auditados au')
            ->join('tblstaff s', 's.staffid = au.staff_id', 'left')
            ->where('au.auditoria_id', (int) $auditoria_id)
            ->where('au.deleted', 0)
            ->order_by('staff_nome', 'asc')
            ->get()->result_array();
    }

    public function set_auditados($auditoria_id, $staff_ids)
    {
        $atuais = $this->db->select('id, staff_id')
            ->where('auditoria_id', (int) $auditoria_id)->where('deleted', 0)
            ->get('tbl_auditorias_auditados')->result_array();
        $atuais_map = [];
        foreach ($atuais as $a) $atuais_map[(int) $a['staff_id']] = (int) $a['id'];

        $manter = [];
        foreach ((array) $staff_ids as $sid) {
            $sid = (int) $sid;
            if ($sid <= 0) continue;
            $manter[] = $sid;
            if (!isset($atuais_map[$sid])) {
                $this->db->insert('tbl_auditorias_auditados', [
                    'auditoria_id' => (int) $auditoria_id,
                    'staff_id'     => $sid,
                    'papel'        => 'auditado',
                ]);
            }
        }
        foreach ($atuais_map as $sid => $rid) {
            if (!in_array($sid, $manter, true)) {
                $this->db->where('id', $rid)->update('tbl_auditorias_auditados', ['deleted' => 1]);
            }
        }
    }

    /* ============ Achados ============ */

    public function get_achados($auditoria_id)
    {
        return $this->db->select("ac.*, CONCAT_WS(' ', s.firstname, s.lastname) AS responsavel_nome,
                                  pa.titulo AS plano_titulo, oc.subject AS ocorrencia_titulo")
            ->from('tbl_auditorias_achados ac')
            ->join('tblstaff s', 's.staffid = ac.responsavel_id', 'left')
            ->join('tbl_planos_acao pa', 'pa.id = ac.plano_id', 'left')
            ->join('tbl_intranet_registro_ocorrencia oc', 'oc.id = ac.ocorrencia_id', 'left')
            ->where('ac.auditoria_id', (int) $auditoria_id)
            ->where('ac.deleted', 0)
            ->order_by("FIELD(ac.tipo, 'nc_maior','nc_menor','observacao','oportunidade','conformidade')", '', false)
            ->order_by('ac.id', 'asc')
            ->get()->result_array();
    }

    public function save_achado($data, $id = null)
    {
        $me = (int) get_staff_user_id();
        $clean = [
            'tipo'             => in_array($data['tipo'] ?? '', $this->tipos_achado, true) ? $data['tipo'] : 'observacao',
            'descricao'        => trim((string) ($data['descricao'] ?? '')),
            'evidencia'        => $data['evidencia'] ?? null,
            'requisito'        => $data['requisito'] ?? null,
            'status'           => in_array($data['status'] ?? '', $this->status_achado, true) ? $data['status'] : 'aberto',
            'responsavel_id'   => !empty($data['responsavel_id']) ? (int) $data['responsavel_id'] : null,
            'plano_id'         => !empty($data['plano_id']) ? (int) $data['plano_id'] : null,
            'ocorrencia_id'    => !empty($data['ocorrencia_id']) ? (int) $data['ocorrencia_id'] : null,
            'prazo_tratamento' => !empty($data['prazo_tratamento']) ? $data['prazo_tratamento'] : null,
        ];
        if ($clean['descricao'] === '') return false;

        if ($id) {
            if ($clean['status'] === 'fechado') $clean['dt_fechamento'] = date('Y-m-d H:i:s');
            $this->db->where('id', $id)->update('tbl_auditorias_achados', $clean);
            return (int) $id;
        }
        $clean['auditoria_id'] = (int) ($data['auditoria_id'] ?? 0);
        if ($clean['auditoria_id'] <= 0) return false;
        $clean['dt_achado']    = date('Y-m-d H:i:s');
        $clean['user_create']  = $me;
        $this->db->insert('tbl_auditorias_achados', $clean);
        return (int) $this->db->insert_id();
    }

    public function delete_achado($id)
    {
        return $this->db->where('id', (int) $id)
            ->update('tbl_auditorias_achados', ['deleted' => 1]);
    }

    /* gera Ocorrência (R.O) automaticamente a partir de um achado NC */
    public function gerar_ocorrencia($achado_id)
    {
        $achado = $this->db->where('id', (int) $achado_id)->get('tbl_auditorias_achados')->row_array();
        if (!$achado || $achado['ocorrencia_id']) return $achado['ocorrencia_id'] ?? false;
        $auditoria = $this->get($achado['auditoria_id']);
        if (!$auditoria) return false;

        $empresa_id = (int) $this->session->userdata('empresa_id');
        $me = (int) get_staff_user_id();

        $payload = [
            'subject'       => '[Auditoria ' . $auditoria['codigo'] . '] ' . mb_strimwidth($achado['descricao'], 0, 200, '…'),
            'report'        => $achado['descricao'] . "\n\nEvidência: " . ($achado['evidencia'] ?? '—') . "\nRequisito: " . ($achado['requisito'] ?? '—'),
            'date'          => date('Y-m-d'),
            'priority'      => $achado['tipo'] === 'nc_maior' ? 4 : ($achado['tipo'] === 'nc_menor' ? 3 : 2),
            'status'        => 1,
            'user_created'  => $me,
            'date_created'  => date('Y-m-d H:i:s'),
            'empresa_id'    => $empresa_id,
            'atribuido_a'   => $achado['responsavel_id'],
            'date_atribuido_a' => $achado['responsavel_id'] ? date('Y-m-d H:i:s') : null,
            'validade'      => $achado['prazo_tratamento'],
        ];
        $this->db->insert('tbl_intranet_registro_ocorrencia', $payload);
        $oc_id = (int) $this->db->insert_id();
        $this->db->where('id', $achado_id)->update('tbl_auditorias_achados', [
            'ocorrencia_id' => $oc_id,
            'status'        => 'em_tratamento',
        ]);
        return $oc_id;
    }
}
