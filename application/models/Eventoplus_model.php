<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Eventoplus_model extends App_Model
{
    public function listar($filtros = [], $limit = null)
    {
        $empresa_id = (int) $this->session->userdata('empresa_id');
        $me = (int) get_staff_user_id();

        $this->db->select('e.eventid AS id, e.title, e.start, e.end, e.color, e.onde, e.description,
                           e.public, e.userid, e.project_id, e.ata_id, e.plano_id, e.grupo_id,
                           CONCAT_WS(\' \', s.firstname, s.lastname) AS criador_nome,
                           p.name AS project_name, a.titulo AS ata_titulo, pa.titulo AS plano_titulo, g.titulo AS grupo_titulo');
        $this->db->from('tblevents e');
        $this->db->join('tblstaff s', 's.staffid = e.userid', 'left');
        $this->db->join('tblprojects p', 'p.id = e.project_id', 'left');
        $this->db->join('tbl_atas a', 'a.id = e.ata_id', 'left');
        $this->db->join('tbl_planos_acao pa', 'pa.id = e.plano_id', 'left');
        $this->db->join('tbl_grupos g', 'g.id = e.grupo_id', 'left');

        $this->db->where('e.empresa_id', $empresa_id);
        $this->db->where('e.deleted', 0);

        if (!empty($filtros['meu'])) {
            $this->db->where('e.userid', $me);
        }
        if (!empty($filtros['futuros'])) {
            $this->db->where('DATE(e.start) >=', date('Y-m-d'));
        }
        if (!empty($filtros['busca'])) {
            $term = $this->db->escape_like_str($filtros['busca']);
            $this->db->where("(e.title LIKE '%{$term}%' OR e.description LIKE '%{$term}%' OR e.onde LIKE '%{$term}%')", null, false);
        }
        if (!empty($filtros['project_id'])) $this->db->where('e.project_id', (int) $filtros['project_id']);
        if (!empty($filtros['ata_id']))     $this->db->where('e.ata_id', (int) $filtros['ata_id']);
        if (!empty($filtros['plano_id']))   $this->db->where('e.plano_id', (int) $filtros['plano_id']);
        if (!empty($filtros['grupo_id']))   $this->db->where('e.grupo_id', (int) $filtros['grupo_id']);

        $this->db->order_by('e.start', 'desc');
        if ($limit) $this->db->limit($limit);

        return $this->db->get()->result_array();
    }

    public function count_meus_futuros()
    {
        $empresa_id = (int) $this->session->userdata('empresa_id');
        $me = (int) get_staff_user_id();
        $hoje = date('Y-m-d');
        return (int) $this->db->query("SELECT COUNT(*) AS n FROM tblevents
            WHERE userid = $me AND empresa_id = $empresa_id AND deleted = 0 AND DATE(start) >= '$hoje'")
            ->row()->n;
    }

    public function get($id)
    {
        $empresa_id = (int) $this->session->userdata('empresa_id');
        return $this->db->select('e.*, e.eventid AS id,
                                  CONCAT_WS(\' \', s.firstname, s.lastname) AS criador_nome,
                                  p.name AS project_name, a.titulo AS ata_titulo,
                                  pa.titulo AS plano_titulo, g.titulo AS grupo_titulo')
            ->from('tblevents e')
            ->join('tblstaff s', 's.staffid = e.userid', 'left')
            ->join('tblprojects p', 'p.id = e.project_id', 'left')
            ->join('tbl_atas a', 'a.id = e.ata_id', 'left')
            ->join('tbl_planos_acao pa', 'pa.id = e.plano_id', 'left')
            ->join('tbl_grupos g', 'g.id = e.grupo_id', 'left')
            ->where('e.eventid', $id)
            ->where('e.empresa_id', $empresa_id)
            ->where('e.deleted', 0)
            ->get()->row_array();
    }

    public function save($data, $id = null)
    {
        $empresa_id = (int) $this->session->userdata('empresa_id');
        $me = (int) get_staff_user_id();

        $start      = trim((string) ($data['start'] ?? ''));
        $start_time = trim((string) ($data['start_time'] ?? '00:00'));
        $end        = trim((string) ($data['end'] ?? ''));
        $end_time   = trim((string) ($data['end_time'] ?? '00:00'));

        $start_dt = $start ? ($start . ' ' . ($start_time ?: '00:00') . ':00') : date('Y-m-d H:i:s');
        $end_dt   = $end   ? ($end . ' ' . ($end_time ?: '00:00') . ':00') : null;

        $clean = [
            'title'       => trim((string) ($data['title'] ?? '')),
            'description' => $data['description'] ?? null,
            'onde'        => !empty($data['onde']) ? trim((string) $data['onde']) : null,
            'start'       => $start_dt,
            'end'         => $end_dt,
            'color'       => !empty($data['color']) ? $data['color'] : '#0a66c2',
            'public'      => !empty($data['public']) ? 1 : 0,
            'project_id'  => !empty($data['project_id']) ? (int) $data['project_id'] : null,
            'ata_id'      => !empty($data['ata_id']) ? (int) $data['ata_id'] : null,
            'plano_id'    => !empty($data['plano_id']) ? (int) $data['plano_id'] : null,
            'grupo_id'    => !empty($data['grupo_id']) ? (int) $data['grupo_id'] : null,
        ];

        if ($id) {
            $this->db->where('eventid', $id)->where('empresa_id', $empresa_id)->update('tblevents', $clean);
            return (int) $id;
        }

        $clean['userid']        = $me;
        $clean['empresa_id']    = $empresa_id;
        $clean['user_cadastro'] = $me;
        $clean['data_cadastro'] = date('Y-m-d H:i:s');
        $this->db->insert('tblevents', $clean);
        return (int) $this->db->insert_id();
    }

    public function delete($id)
    {
        $empresa_id = (int) $this->session->userdata('empresa_id');
        return $this->db->where('eventid', $id)->where('empresa_id', $empresa_id)
            ->update('tblevents', ['deleted' => 1]);
    }

    public function pode_editar($evento, $staff_id = null)
    {
        $staff_id = $staff_id ?? (int) get_staff_user_id();
        if ((int) ($evento['userid'] ?? 0) === $staff_id) return true;
        return is_admin() && has_permission_intranet('eventos', '', 'edit');
    }
}
