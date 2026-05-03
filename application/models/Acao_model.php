<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Modelo "Ações" — wrapper amigável sobre tbltasks (Perfex) com filtros do sigplus.
 * Não substitui Tasks_model do Perfex. Edição/criação avançada continua em /admin/tasks.
 */
class Acao_model extends App_Model
{
    /* status do Perfex: 1=Not started, 2=Awaiting feedback, 3=Testing, 4=In progress, 5=Complete */
    public function get_statuses()
    {
        return [
            1 => ['label' => 'Não iniciada',     'cor' => '#94a3b8'],
            4 => ['label' => 'Em andamento',     'cor' => '#0a66c2'],
            3 => ['label' => 'Em teste',         'cor' => '#7c3aed'],
            2 => ['label' => 'Aguardando feedback','cor' => '#f59e0b'],
            5 => ['label' => 'Concluída',        'cor' => '#16a34a'],
        ];
    }

    public function get_status_label($s)
    {
        $list = $this->get_statuses();
        return $list[$s]['label'] ?? '—';
    }

    public function get_status_color($s)
    {
        $list = $this->get_statuses();
        return $list[$s]['cor'] ?? '#94a3b8';
    }

    public function get_priorities()
    {
        return [
            1 => ['label' => 'Baixa',   'cor' => '#94a3b8'],
            2 => ['label' => 'Média',   'cor' => '#0a66c2'],
            3 => ['label' => 'Alta',    'cor' => '#f59e0b'],
            4 => ['label' => 'Urgente', 'cor' => '#dc2626'],
        ];
    }

    public function get_priority_label($p)
    {
        $list = $this->get_priorities();
        return $list[$p]['label'] ?? '—';
    }

    public function get_priority_color($p)
    {
        $list = $this->get_priorities();
        return $list[$p]['cor'] ?? '#94a3b8';
    }

    /* ============ Listagem ============ */

    public function listar($filtros = [])
    {
        $empresa_id = (int) $this->session->userdata('empresa_id');
        $me = (int) get_staff_user_id();

        $this->db->select("t.id, t.name, t.description, t.priority, t.status, t.duedate, t.startdate,
                           t.dateadded, t.addedfrom, t.rel_id, t.rel_type, t.ataid, t.fase_id,
                           t.grupoid, t.planoid,
                           CONCAT_WS(' ', sc.firstname, sc.lastname) AS criador_nome,
                           p.name AS project_name,
                           a.titulo AS ata_titulo,
                           pa.titulo AS plano_titulo,
                           g.titulo AS grupo_titulo,
                           (SELECT GROUP_CONCAT(CONCAT_WS(' ', s.firstname, s.lastname) SEPARATOR ', ')
                            FROM tbltask_assigned ta
                            JOIN tblstaff s ON s.staffid = ta.staffid
                            WHERE ta.taskid = t.id) AS assigned_nomes,
                           (SELECT GROUP_CONCAT(ta.staffid) FROM tbltask_assigned ta WHERE ta.taskid = t.id) AS assigned_ids");
        $this->db->from('tbltasks t');
        $this->db->join('tblstaff sc',           'sc.staffid = t.addedfrom', 'left');
        $this->db->join('tblprojects p',         'p.id = t.rel_id AND t.rel_type = "project"', 'left');
        $this->db->join('tbl_atas a',            'a.id = t.ataid', 'left');
        $this->db->join('tbl_planos_acao pa',    'pa.id = t.planoid', 'left');
        $this->db->join('tbl_grupos g',          'g.id = t.grupoid', 'left');
        $this->db->where('t.deleted', 0);
        $this->db->where('t.empresa_id', $empresa_id);

        if (!empty($filtros['project_id'])) {
            $pid = (int) $filtros['project_id'];
            $this->db->where("(t.rel_id = $pid AND t.rel_type = 'project')", null, false);
        }
        if (!empty($filtros['status']))  $this->db->where('t.status',   (int) $filtros['status']);
        if (!empty($filtros['priority']))$this->db->where('t.priority', (int) $filtros['priority']);
        if (!empty($filtros['atrasadas'])) {
            $this->db->where('t.duedate IS NOT NULL', null, false);
            $this->db->where('t.duedate <', date('Y-m-d'));
            $this->db->where('t.status !=', 5);
        }
        if (!empty($filtros['criei']))           $this->db->where('t.addedfrom', $me);
        if (!empty($filtros['atribuida_eu'])) {
            $this->db->where("EXISTS (SELECT 1 FROM tbltask_assigned ta WHERE ta.taskid = t.id AND ta.staffid = $me)", null, false);
        }
        if (!empty($filtros['envolve_eu'])) {
            $this->db->where("(t.addedfrom = $me OR EXISTS (SELECT 1 FROM tbltask_assigned ta WHERE ta.taskid = t.id AND ta.staffid = $me))", null, false);
        }
        if (!empty($filtros['busca'])) {
            $term = $this->db->escape_like_str($filtros['busca']);
            $this->db->where("(t.name LIKE '%{$term}%' OR t.description LIKE '%{$term}%')", null, false);
        }
        if (empty($filtros['concluidas'])) {
            $this->db->where('t.status !=', 5);
        }

        $this->db->order_by('t.duedate', 'asc')->order_by('t.id', 'desc');
        return $this->db->get()->result_array();
    }

    public function get($id)
    {
        $empresa_id = (int) $this->session->userdata('empresa_id');
        return $this->db->where('id', (int) $id)
            ->where('empresa_id', $empresa_id)
            ->where('deleted', 0)
            ->get('tbltasks')->row_array();
    }

    public function pode_editar($t, $staff_id = null)
    {
        $staff_id = $staff_id ?? (int) get_staff_user_id();
        if ((int) ($t['addedfrom'] ?? 0) === $staff_id) return true;
        // atribuído pode editar
        $count = $this->db->where('taskid', (int) $t['id'])->where('staffid', $staff_id)
            ->count_all_results('tbltask_assigned');
        if ($count > 0) return true;
        return is_admin();
    }

    /* ============ CRUD básico (delegated pra editor avançado do Perfex se necessário) ============ */

    public function save($data, $id = null)
    {
        $empresa_id = (int) $this->session->userdata('empresa_id');
        $me = (int) get_staff_user_id();

        $project_id = !empty($data['project_id']) ? (int) $data['project_id'] : null;
        $clean = [
            'name'        => trim((string) ($data['name'] ?? 'Ação sem título')),
            'description' => $data['description'] ?? '',
            'priority'    => isset($data['priority']) ? (int) $data['priority'] : 2,
            'status'      => isset($data['status']) ? (int) $data['status'] : 1,
            'startdate'   => !empty($data['startdate']) ? $data['startdate'] : date('Y-m-d'),
            'duedate'     => !empty($data['duedate']) ? $data['duedate'] : null,
            'rel_id'      => $project_id,
            'rel_type'    => $project_id ? 'project' : null,
            'ataid'       => !empty($data['ataid']) ? (int) $data['ataid'] : null,
            'planoid'     => !empty($data['planoid']) ? (int) $data['planoid'] : null,
            'grupoid'     => !empty($data['grupoid']) ? (int) $data['grupoid'] : null,
            'fase_id'     => !empty($data['fase_id']) ? (int) $data['fase_id'] : null,
        ];

        if ($id) {
            $this->db->where('id', $id)->where('empresa_id', $empresa_id)->update('tbltasks', $clean);
            $task_id = (int) $id;
        } else {
            $clean['empresa_id']             = $empresa_id;
            $clean['addedfrom']              = $me;
            $clean['dateadded']              = date('Y-m-d H:i:s');
            $clean['is_added_from_contact']  = 0;
            $this->db->insert('tbltasks', $clean);
            $task_id = (int) $this->db->insert_id();
        }

        // assigned (suporta múltiplos)
        if (isset($data['assigned'])) {
            $assigned = (array) $data['assigned'];
            $this->db->where('taskid', $task_id)->delete('tbltask_assigned');
            foreach ($assigned as $sid) {
                $sid = (int) $sid;
                if ($sid <= 0) continue;
                $this->db->insert('tbltask_assigned', [
                    'taskid'      => $task_id,
                    'staffid'     => $sid,
                    'assigned_from' => $me,
                ]);
            }
        }
        return $task_id;
    }

    public function marcar_concluida($id, $concluida = true)
    {
        $payload = ['status' => $concluida ? 5 : 4];
        if ($concluida) $payload['datefinished'] = date('Y-m-d H:i:s');
        else            $payload['datefinished'] = null;
        return $this->db->where('id', (int) $id)->update('tbltasks', $payload);
    }
}
