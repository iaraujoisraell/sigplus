<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Modelo novo de Documentos (substitui Cdc_model + Documento_model legados).
 * Usa as tabelas:
 *  - tbl_intranet_documento                 (cabeçalho)
 *  - tbl_intranet_documento_categoria       (categorias)
 *  - tbl_intranet_documento_aprovacao       (aprovadores em sequência)
 *  - tbl_intranet_documento_send            (destinatários — ciência)
 *  - tbl_intranet_documento_obs             (comentários)
 */
class Documentos_model extends App_Model
{
    private $statuses = ['rascunho', 'em_aprovacao', 'aprovado', 'reprovado', 'publicado', 'arquivado'];

    public function get_statuses() { return $this->statuses; }

    public function get_status_label($s)
    {
        return [
            'rascunho'    => 'Rascunho',
            'em_aprovacao'=> 'Em aprovação',
            'aprovado'    => 'Aprovado',
            'reprovado'   => 'Reprovado',
            'publicado'   => 'Publicado',
            'arquivado'   => 'Arquivado',
        ][$s] ?? ucfirst($s);
    }

    public function get_status_color($s)
    {
        return [
            'rascunho'    => '#94a3b8',
            'em_aprovacao'=> '#f59e0b',
            'aprovado'    => '#16a34a',
            'reprovado'   => '#dc2626',
            'publicado'   => '#0a66c2',
            'arquivado'   => '#737373',
        ][$s] ?? '#94a3b8';
    }

    /* deriva slug humano a partir das colunas legadas (publicado, status) */
    private function _derive_status($row)
    {
        if (!empty($row['publicado'])) return 'publicado';
        $s = (int) ($row['status'] ?? 0);
        return [0 => 'em_aprovacao', 1 => 'rascunho', 2 => 'aprovado', 3 => 'reprovado'][$s] ?? 'rascunho';
    }

    /* ============ Listagem ============ */

    public function listar($filtros = [])
    {
        $empresa_id = (int) $this->session->userdata('empresa_id');
        $me = (int) get_staff_user_id();

        $this->db->select("d.*, c.titulo AS categoria_nome,
                           p.name AS project_name,
                           CONCAT_WS(' ', s.firstname, s.lastname) AS criador_nome,
                           CONCAT_WS(' ', sr.firstname, sr.lastname) AS responsavel_nome,
                           dep.name AS setor_nome,
                           (SELECT COUNT(*) FROM tbl_intranet_documento_send se WHERE se.documento_id = d.id AND se.deleted = 0) AS total_destinatarios,
                           (SELECT COUNT(*) FROM tbl_intranet_documento_send se WHERE se.documento_id = d.id AND se.deleted = 0 AND se.lido = 1) AS total_lidos");
        $this->db->from('tbl_intranet_documento d');
        $this->db->join('tbl_intranet_documento_categoria c', 'c.id = d.categoria_id', 'left');
        $this->db->join('tblprojects p', 'p.id = d.project_id', 'left');
        $this->db->join('tblstaff s', 's.staffid = d.user_cadastro', 'left');
        $this->db->join('tblstaff sr', 'sr.staffid = d.responsavel', 'left');
        $this->db->join('tbldepartments dep', 'dep.departmentid = d.setor_id', 'left');
        $this->db->where('d.deleted', 0);
        $this->db->where('d.empresa_id', $empresa_id);

        if (!empty($filtros['categoria_id'])) $this->db->where('d.categoria_id', (int) $filtros['categoria_id']);
        if (!empty($filtros['project_id']))   $this->db->where('d.project_id',   (int) $filtros['project_id']);
        if (!empty($filtros['fase_id']))      $this->db->where('d.fase_id',      (int) $filtros['fase_id']);
        if (!empty($filtros['ata_id']))       $this->db->where('d.ata_id',       (int) $filtros['ata_id']);
        if (!empty($filtros['plano_id']))     $this->db->where('d.plano_id',     (int) $filtros['plano_id']);
        if (!empty($filtros['grupo_id']))     $this->db->where('d.grupo_id',     (int) $filtros['grupo_id']);
        if (!empty($filtros['publicado']))       $this->db->where('d.publicado',    1);
        if (!empty($filtros['meu'])) {
            $this->db->where("(d.user_cadastro = $me OR d.responsavel = $me)", null, false);
        }
        if (!empty($filtros['criei']))           $this->db->where('d.user_cadastro', $me);
        if (!empty($filtros['responsavel_meu'])) $this->db->where('d.responsavel', $me);

        if (!empty($filtros['busca'])) {
            $term = $this->db->escape_like_str($filtros['busca']);
            $this->db->where("(d.titulo LIKE '%{$term}%' OR d.descricao LIKE '%{$term}%' OR d.codigo LIKE '%{$term}%')", null, false);
        }

        $this->db->order_by('d.id', 'desc');
        $rows = $this->db->get()->result_array();
        foreach ($rows as &$r) $r['status_slug'] = $this->_derive_status($r);
        return $rows;
    }

    public function get($id)
    {
        $empresa_id = (int) $this->session->userdata('empresa_id');
        $row = $this->db->select("d.*, c.titulo AS categoria_nome, p.name AS project_name,
                                  CONCAT_WS(' ', s.firstname, s.lastname) AS criador_nome,
                                  CONCAT_WS(' ', sr.firstname, sr.lastname) AS responsavel_nome,
                                  dep.name AS setor_nome")
            ->from('tbl_intranet_documento d')
            ->join('tbl_intranet_documento_categoria c', 'c.id = d.categoria_id', 'left')
            ->join('tblprojects p', 'p.id = d.project_id', 'left')
            ->join('tblstaff s', 's.staffid = d.user_cadastro', 'left')
            ->join('tblstaff sr', 'sr.staffid = d.responsavel', 'left')
            ->join('tbldepartments dep', 'dep.departmentid = d.setor_id', 'left')
            ->where('d.id', (int) $id)
            ->where('d.empresa_id', $empresa_id)
            ->where('d.deleted', 0)
            ->get()->row_array();
        if ($row) $row['status_slug'] = $this->_derive_status($row);
        return $row;
    }

    public function pode_editar($doc, $staff_id = null)
    {
        $staff_id = $staff_id ?? (int) get_staff_user_id();
        if ((int) ($doc['user_cadastro'] ?? 0) === $staff_id) return true;
        if ((int) ($doc['responsavel'] ?? 0) === $staff_id) return true;
        return is_admin();
    }

    public function pode_visualizar($doc, $staff_id = null)
    {
        $staff_id = $staff_id ?? (int) get_staff_user_id();
        if ($this->pode_editar($doc, $staff_id)) return true;
        if (!empty($doc['publico'])) return true;
        $count = $this->db->where('documento_id', (int) $doc['id'])
            ->where('staff_id', $staff_id)
            ->where('deleted', 0)
            ->count_all_results('tbl_intranet_documento_send');
        if ($count > 0) return true;
        $count = $this->db->where('doc_id', (int) $doc['id'])
            ->where('staff_id', $staff_id)
            ->where('deleted', 0)
            ->count_all_results('tbl_intranet_documento_aprovacao');
        return $count > 0;
    }

    /* ============ CRUD documento ============ */

    public function save($data, $id = null)
    {
        $empresa_id = (int) $this->session->userdata('empresa_id');
        $me = (int) get_staff_user_id();

        $clean = [
            'titulo'        => trim((string) ($data['titulo'] ?? 'Documento sem título')),
            'descricao'     => $data['descricao'] ?? null,
            'conteudo'      => $data['conteudo'] ?? null,
            'codigo'        => trim((string) ($data['codigo'] ?? '')),
            'categoria_id'  => !empty($data['categoria_id']) ? (int) $data['categoria_id'] : null,
            'setor_id'      => !empty($data['setor_id']) ? (int) $data['setor_id'] : null,
            'responsavel'   => !empty($data['responsavel']) ? (int) $data['responsavel'] : null,
            'project_id'    => !empty($data['project_id']) ? (int) $data['project_id'] : null,
            'fase_id'       => !empty($data['fase_id']) ? (int) $data['fase_id'] : null,
            'ata_id'        => !empty($data['ata_id']) ? (int) $data['ata_id'] : null,
            'plano_id'      => !empty($data['plano_id']) ? (int) $data['plano_id'] : null,
            'grupo_id'      => !empty($data['grupo_id']) ? (int) $data['grupo_id'] : null,
            'publico'       => !empty($data['publico']) ? 1 : 0,
            'numero_versao' => isset($data['numero_versao']) ? (int) $data['numero_versao'] : 1,
        ];
        if (array_key_exists('file', $data)) $clean['file'] = $data['file'];

        if ($id) {
            $clean['user_ultima_alteracao'] = $me;
            $clean['data_ultima_alteracao'] = date('Y-m-d');
            $clean['dt_updated']            = date('Y-m-d H:i:s');
            $this->db->where('id', $id)->where('empresa_id', $empresa_id)
                ->update('tbl_intranet_documento', $clean);
            return (int) $id;
        }

        $clean['empresa_id']    = $empresa_id;
        $clean['user_cadastro'] = $me;
        $clean['data_cadastro'] = date('Y-m-d');
        $clean['status']        = 1; // rascunho
        $clean['publicado']     = 0;
        if (empty($clean['codigo'])) {
            $row = $this->db->select_max('sequencial')
                ->where('empresa_id', $empresa_id)
                ->get('tbl_intranet_documento')->row();
            $next = ((int) ($row->sequencial ?? 0)) + 1;
            $clean['sequencial'] = $next;
            $clean['codigo']     = 'DOC-' . str_pad($next, 4, '0', STR_PAD_LEFT);
        }
        $this->db->insert('tbl_intranet_documento', $clean);
        return (int) $this->db->insert_id();
    }

    public function delete($id)
    {
        $empresa_id = (int) $this->session->userdata('empresa_id');
        return $this->db->where('id', $id)->where('empresa_id', $empresa_id)
            ->update('tbl_intranet_documento', ['deleted' => 1]);
    }

    public function publicar($id)
    {
        return $this->db->where('id', $id)->update('tbl_intranet_documento', [
            'publicado'       => 1,
            'data_publicacao' => date('Y-m-d'),
            'dt_updated'      => date('Y-m-d H:i:s'),
        ]);
    }

    public function arquivar($id)
    {
        return $this->db->where('id', $id)->update('tbl_intranet_documento', [
            'publicado'  => 0,
            'status'     => 0,
            'dt_updated' => date('Y-m-d H:i:s'),
        ]);
    }

    /* ============ Categorias ============ */

    public function get_categorias()
    {
        $empresa_id = (int) $this->session->userdata('empresa_id');
        return $this->db->where('deleted', 0)
            ->where('empresa_id', $empresa_id)
            ->order_by('titulo', 'asc')
            ->get('tbl_intranet_documento_categoria')->result_array();
    }

    /* ============ Aprovadores ============ */

    public function get_aprovadores($doc_id)
    {
        return $this->db->select("a.*, CONCAT_WS(' ', s.firstname, s.lastname) AS staff_nome, s.profile_image, s.cargo")
            ->from('tbl_intranet_documento_aprovacao a')
            ->join('tblstaff s', 's.staffid = a.staff_id', 'left')
            ->where('a.doc_id', (int) $doc_id)
            ->where('a.deleted', 0)
            ->order_by('a.fluxo_sequencia', 'asc')->order_by('a.id', 'asc')
            ->get()->result_array();
    }

    public function set_aprovadores($doc_id, $staff_ids)
    {
        $empresa_id = (int) $this->session->userdata('empresa_id');
        $me = (int) get_staff_user_id();
        $this->db->where('doc_id', (int) $doc_id)
            ->update('tbl_intranet_documento_aprovacao', ['deleted' => 1]);

        $seq = 1;
        foreach ((array) $staff_ids as $sid) {
            $sid = (int) $sid;
            if ($sid <= 0) continue;
            $this->db->insert('tbl_intranet_documento_aprovacao', [
                'doc_id'          => (int) $doc_id,
                'staff_id'        => $sid,
                'fluxo_id'        => 0,
                'fluxo_sequencia' => $seq,
                'fluxo_nome'      => 'Aprovador ' . $seq,
                'status'          => 0,
                'data_cadastro'   => date('Y-m-d'),
                'user_cadastro'   => $me,
                'empresa_id'      => $empresa_id,
                'deleted'         => 0,
            ]);
            $seq++;
        }
    }

    public function get_aprovador_atual($doc_id)
    {
        return $this->db->select("a.*, CONCAT_WS(' ', s.firstname, s.lastname) AS staff_nome")
            ->from('tbl_intranet_documento_aprovacao a')
            ->join('tblstaff s', 's.staffid = a.staff_id', 'left')
            ->where('a.doc_id', (int) $doc_id)
            ->where('a.status', 0)
            ->where('a.deleted', 0)
            ->order_by('a.fluxo_sequencia', 'asc')
            ->limit(1)->get()->row_array();
    }

    public function aprovar($doc_id, $staff_id, $observacao = '')
    {
        $apv = $this->get_aprovador_atual($doc_id);
        if (!$apv || (int) $apv['staff_id'] !== (int) $staff_id) return false;

        $this->db->where('id', $apv['id'])->update('tbl_intranet_documento_aprovacao', [
            'status'                => 1,
            'observacao'            => substr($observacao, 0, 45),
            'dt_aprovacao'          => date('Y-m-d H:i:s'),
            'data_ultima_alteracao' => date('Y-m-d'),
            'user_ultima_alteracao' => (int) $staff_id,
            'ip'                    => $this->input->ip_address(),
        ]);

        $pendentes = $this->db->where('doc_id', (int) $doc_id)
            ->where('deleted', 0)->where('status', 0)
            ->count_all_results('tbl_intranet_documento_aprovacao');

        if ($pendentes === 0) {
            $this->db->where('id', $doc_id)->update('tbl_intranet_documento', [
                'status'          => 2,
                'publicado'       => 1,
                'data_publicacao' => date('Y-m-d'),
                'dt_updated'      => date('Y-m-d H:i:s'),
            ]);
        }
        return true;
    }

    public function reprovar($doc_id, $staff_id, $observacao = '')
    {
        $apv = $this->get_aprovador_atual($doc_id);
        if (!$apv || (int) $apv['staff_id'] !== (int) $staff_id) return false;

        $this->db->where('id', $apv['id'])->update('tbl_intranet_documento_aprovacao', [
            'status'                => 2,
            'observacao'            => substr($observacao, 0, 45),
            'dt_aprovacao'          => date('Y-m-d H:i:s'),
            'data_ultima_alteracao' => date('Y-m-d'),
            'user_ultima_alteracao' => (int) $staff_id,
            'ip'                    => $this->input->ip_address(),
        ]);
        $this->db->where('id', $doc_id)->update('tbl_intranet_documento', [
            'status' => 3, 'dt_updated' => date('Y-m-d H:i:s'),
        ]);
        return true;
    }

    /* ============ Destinatários ============ */

    public function get_destinatarios($doc_id)
    {
        return $this->db->select("se.*, CONCAT_WS(' ', s.firstname, s.lastname) AS staff_nome, s.profile_image, s.cargo")
            ->from('tbl_intranet_documento_send se')
            ->join('tblstaff s', 's.staffid = se.staff_id', 'left')
            ->where('se.documento_id', (int) $doc_id)
            ->where('se.deleted', 0)
            ->order_by('se.lido', 'asc')->order_by('staff_nome', 'asc')
            ->get()->result_array();
    }

    public function set_destinatarios($doc_id, $staff_ids)
    {
        $empresa_id = (int) $this->session->userdata('empresa_id');
        $atuais = $this->db->select('id, staff_id')
            ->where('documento_id', (int) $doc_id)->where('deleted', 0)
            ->get('tbl_intranet_documento_send')->result_array();
        $atuais_map = [];
        foreach ($atuais as $a) $atuais_map[(int) $a['staff_id']] = (int) $a['id'];

        $manter = [];
        foreach ((array) $staff_ids as $sid) {
            $sid = (int) $sid;
            if ($sid <= 0) continue;
            $manter[] = $sid;
            if (!isset($atuais_map[$sid])) {
                $this->db->insert('tbl_intranet_documento_send', [
                    'documento_id' => (int) $doc_id,
                    'staff_id'     => $sid,
                    'dt_send'      => date('Y-m-d'),
                    'lido'         => 0,
                    'empresa_id'   => $empresa_id,
                    'deleted'      => 0,
                ]);
            }
        }
        foreach ($atuais_map as $sid => $rid) {
            if (!in_array($sid, $manter, true)) {
                $this->db->where('id', $rid)->update('tbl_intranet_documento_send', ['deleted' => 1]);
            }
        }
    }

    public function marcar_ciente($doc_id, $staff_id)
    {
        return $this->db->where('documento_id', (int) $doc_id)
            ->where('staff_id', (int) $staff_id)
            ->where('deleted', 0)
            ->update('tbl_intranet_documento_send', [
                'lido'    => 1,
                'dt_lido' => date('Y-m-d H:i:s'),
            ]);
    }

    /* ============ Observações ============ */

    public function get_observacoes($doc_id)
    {
        return $this->db->select("o.id, o.doc_id, o.staff_id, o.obs, o.data_created,
                                  CONCAT_WS(' ', s.firstname, s.lastname) AS staff_nome, s.profile_image")
            ->from('tbl_intranet_documento_obs o')
            ->join('tblstaff s', 's.staffid = o.user_created', 'left')
            ->where('o.doc_id', (int) $doc_id)
            ->order_by('o.id', 'desc')
            ->get()->result_array();
    }

    public function add_observacao($doc_id, $observacao)
    {
        $empresa_id = (int) $this->session->userdata('empresa_id');
        $me = (int) get_staff_user_id();
        $observacao = trim((string) $observacao);
        if ($observacao === '') return false;
        return $this->db->insert('tbl_intranet_documento_obs', [
            'doc_id'       => (int) $doc_id,
            'staff_id'     => $me,
            'obs'          => $observacao,
            'data_created' => date('Y-m-d H:i:s'),
            'user_created' => $me,
            'empresa_id'   => $empresa_id,
        ]);
    }
}
