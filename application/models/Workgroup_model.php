<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Workgroup_model extends App_Model
{
    private $statuses = ['ativo', 'pausado', 'concluido', 'cancelado'];
    private $papeis   = ['lider', 'membro', 'observador'];

    public function get_statuses() { return $this->statuses; }
    public function get_papeis()   { return $this->papeis; }

    public function get_status_label($s)
    {
        return ['ativo' => 'Ativo', 'pausado' => 'Pausado', 'concluido' => 'Concluído', 'cancelado' => 'Cancelado'][$s] ?? ucfirst($s);
    }

    public function listar($filtros = [], $limit = null)
    {
        $empresa_id = (int) $this->session->userdata('empresa_id');
        $me = (int) get_staff_user_id();

        $this->db->select('g.*, p.name AS project_name,
                           CONCAT_WS(\' \', s.firstname, s.lastname) AS lider_nome');
        $this->db->from('tbl_grupos g');
        $this->db->join('tblprojects p', 'p.id = g.project_id', 'left');
        $this->db->join('tblstaff s', 's.staffid = g.lider_id', 'left');
        $this->db->where('g.deleted', 0);
        $this->db->where('g.empresa_id', $empresa_id);

        if (!empty($filtros['status']))     $this->db->where('g.status', $filtros['status']);
        if (!empty($filtros['project_id'])) $this->db->where('g.project_id', (int) $filtros['project_id']);

        if (!empty($filtros['meu'])) {
            $this->db->where("(g.lider_id = $me OR g.user_create = $me OR EXISTS (
                SELECT 1 FROM tbl_grupos_membros m
                WHERE m.grupo_id = g.id AND m.staff_id = $me AND m.deleted = 0
            ))", null, false);
        }

        if (!empty($filtros['busca'])) {
            $term = $this->db->escape_like_str($filtros['busca']);
            $this->db->where("(g.titulo LIKE '%{$term}%' OR g.objetivo LIKE '%{$term}%')", null, false);
        }

        $this->db->order_by('g.id', 'desc');
        if ($limit) $this->db->limit($limit);

        return $this->db->get()->result_array();
    }

    public function count_meus()
    {
        $empresa_id = (int) $this->session->userdata('empresa_id');
        $me = (int) get_staff_user_id();
        return (int) $this->db->query("SELECT COUNT(DISTINCT g.id) AS n
            FROM tbl_grupos g
            LEFT JOIN tbl_grupos_membros m ON m.grupo_id = g.id AND m.staff_id = $me AND m.deleted = 0
            WHERE g.deleted = 0 AND g.empresa_id = $empresa_id
              AND (g.lider_id = $me OR g.user_create = $me OR m.id IS NOT NULL)")
            ->row()->n;
    }

    public function get($id)
    {
        $empresa_id = (int) $this->session->userdata('empresa_id');
        return $this->db->select('g.*, p.name AS project_name,
                                  CONCAT_WS(\' \', s.firstname, s.lastname) AS lider_nome')
            ->from('tbl_grupos g')
            ->join('tblprojects p', 'p.id = g.project_id', 'left')
            ->join('tblstaff s', 's.staffid = g.lider_id', 'left')
            ->where('g.id', $id)
            ->where('g.deleted', 0)
            ->where('g.empresa_id', $empresa_id)
            ->get()->row_array();
    }

    public function get_membros($grupo_id)
    {
        return $this->db->select('m.*, CONCAT_WS(\' \', s.firstname, s.lastname) AS staff_nome,
                                  s.email AS staff_email, s.cargo AS staff_cargo, s.profile_image')
            ->from('tbl_grupos_membros m')
            ->join('tblstaff s', 's.staffid = m.staff_id', 'left')
            ->where('m.grupo_id', $grupo_id)
            ->where('m.deleted', 0)
            ->order_by("FIELD(m.papel, 'lider', 'membro', 'observador')", '', false)
            ->order_by('staff_nome', 'asc')
            ->get()->result_array();
    }

    public function pode_visualizar($grupo, $staff_id = null)
    {
        $staff_id = $staff_id ?? (int) get_staff_user_id();
        if ((int) ($grupo['lider_id'] ?? 0) === $staff_id) return true;
        if ((int) ($grupo['user_create'] ?? 0) === $staff_id) return true;
        $count = $this->db->where('grupo_id', (int) $grupo['id'])
            ->where('staff_id', $staff_id)
            ->where('deleted', 0)
            ->count_all_results('tbl_grupos_membros');
        return $count > 0;
    }

    public function pode_editar($grupo, $staff_id = null)
    {
        $staff_id = $staff_id ?? (int) get_staff_user_id();
        return (int) ($grupo['lider_id'] ?? 0) === $staff_id
            || (int) ($grupo['user_create'] ?? 0) === $staff_id
            || (is_admin() && has_permission_intranet('grupos', '', 'edit'));
    }

    public function save($data, $id = null)
    {
        $empresa_id = (int) $this->session->userdata('empresa_id');
        $me = (int) get_staff_user_id();

        $clean = [
            'project_id'      => !empty($data['project_id']) ? (int) $data['project_id'] : null,
            'titulo'          => trim((string) ($data['titulo'] ?? '')),
            'descricao'       => $data['descricao'] ?? null,
            'objetivo'        => $data['objetivo'] ?? null,
            'lider_id'        => !empty($data['lider_id']) ? (int) $data['lider_id'] : $me,
            'dt_inicio'       => !empty($data['dt_inicio']) ? $data['dt_inicio'] : null,
            'dt_fim_prevista' => !empty($data['dt_fim_prevista']) ? $data['dt_fim_prevista'] : null,
            'status'          => in_array($data['status'] ?? '', $this->statuses, true) ? $data['status'] : 'ativo',
        ];

        if ($clean['status'] === 'concluido' && empty($data['dt_concluido'])) {
            $clean['dt_concluido'] = date('Y-m-d H:i:s');
        }

        if ($id) {
            $clean['user_update'] = $me;
            $clean['dt_updated']  = date('Y-m-d H:i:s');
            $this->db->where('id', $id)->where('empresa_id', $empresa_id)->update('tbl_grupos', $clean);
            return (int) $id;
        }

        $clean['empresa_id']  = $empresa_id;
        $clean['user_create'] = $me;
        $clean['dt_created']  = date('Y-m-d H:i:s');
        $this->db->insert('tbl_grupos', $clean);
        $new_id = (int) $this->db->insert_id();

        if ($new_id && $clean['lider_id']) {
            $this->_add_membro($new_id, $clean['lider_id'], 'lider');
        }
        return $new_id;
    }

    public function save_membros($grupo_id, $list)
    {
        $atual = $this->db->select('staff_id, id')->where('grupo_id', $grupo_id)->where('deleted', 0)
            ->get('tbl_grupos_membros')->result_array();
        $atual_map = [];
        foreach ($atual as $a) $atual_map[(int) $a['staff_id']] = (int) $a['id'];

        $manter = [];
        $novos  = [];
        foreach ((array) $list as $m) {
            $sid = !empty($m['staff_id']) ? (int) $m['staff_id'] : 0;
            $papel = in_array($m['papel'] ?? '', $this->papeis, true) ? $m['papel'] : 'membro';
            if ($sid <= 0) continue;
            $manter[] = $sid;

            if (isset($atual_map[$sid])) {
                $this->db->where('id', $atual_map[$sid])->update('tbl_grupos_membros', ['papel' => $papel, 'deleted' => 0]);
            } else {
                $this->_add_membro($grupo_id, $sid, $papel);
                $novos[] = ['staff_id' => $sid, 'papel' => $papel];
            }
        }

        foreach ($atual_map as $sid => $rid) {
            if (!in_array($sid, $manter, true)) {
                $this->db->where('id', $rid)->update('tbl_grupos_membros', ['deleted' => 1]);
            }
        }

        if (!empty($novos)) {
            $this->_notificar_novos_membros($grupo_id, $novos);
        }
    }

    private function _notificar_novos_membros($grupo_id, $novos)
    {
        $grupo = $this->get($grupo_id);
        if (!$grupo) return;
        $this->load->model('Comunicacao_model');
        $this->load->model('Staff_model');

        $link = base_url('gestao_corporativa/Workgroup/view/' . (int) $grupo_id);
        $autor = trim((string) get_staff_full_name(get_staff_user_id()));
        $objetivo = !empty($grupo['objetivo']) ? trim($grupo['objetivo']) : '(sem objetivo definido)';

        foreach ($novos as $n) {
            $staff = $this->Staff_model->get($n['staff_id']);
            if (!$staff || empty($staff->email)) continue;

            $mensagem  = "<p>Olá " . html_escape($staff->firstname) . ",</p>";
            $mensagem .= "<p><strong>" . html_escape($autor) . "</strong> adicionou você ao grupo <strong>" . html_escape($grupo['titulo']) . "</strong> como <strong>" . html_escape($n['papel']) . "</strong>.</p>";
            $mensagem .= "<p><em>Objetivo:</em> " . html_escape($objetivo) . "</p>";
            $mensagem .= '<p><a href="' . $link . '">Acessar grupo</a></p>';

            $this->Comunicacao_model->addEmail([
                'data_registro'    => date('Y-m-d H:i:s'),
                'usuario_registro' => get_staff_user_id(),
                'email_destino'    => $staff->email,
                'staff_id'         => (int) $staff->staffid,
                'assunto'          => 'Você foi adicionado ao grupo: ' . $grupo['titulo'],
                'mensagem'         => $mensagem,
                'rel_type'         => 'grupo',
                'rel_id'           => (int) $grupo_id,
                'empresa_id'       => (int) $this->session->userdata('empresa_id'),
            ]);
        }

        $nomes = array_map(function ($n) {
            $s = $this->Staff_model->get($n['staff_id']);
            return $s ? trim($s->firstname . ' ' . $s->lastname) : '#' . $n['staff_id'];
        }, $novos);
        $this->add_post($grupo_id, '<i class="fa fa-user-plus"></i> ' . html_escape($autor) . ' adicionou: ' . html_escape(implode(', ', $nomes)), 'sistema');
    }

    public function sair($grupo_id, $staff_id = null)
    {
        $staff_id = $staff_id ?? (int) get_staff_user_id();
        $grupo = $this->get($grupo_id);
        if (!$grupo) return false;

        // Líder e criador não podem sair (precisariam transferir antes)
        if ((int) $grupo['lider_id'] === $staff_id || (int) $grupo['user_create'] === $staff_id) {
            return false;
        }

        $this->db->where('grupo_id', $grupo_id)
            ->where('staff_id', $staff_id)
            ->where('deleted', 0)
            ->update('tbl_grupos_membros', ['deleted' => 1]);

        $nome = trim((string) get_staff_full_name($staff_id));
        $this->add_post($grupo_id, '<i class="fa fa-sign-out"></i> ' . html_escape($nome) . ' saiu do grupo.', 'sistema', $staff_id);
        return true;
    }

    /* ---------------- Posts / Discussão ---------------- */

    public function get_posts($grupo_id)
    {
        return $this->db->select('p.*, CONCAT_WS(\' \', s.firstname, s.lastname) AS autor_nome,
                                  s.profile_image, s.cargo AS autor_cargo')
            ->from('tbl_grupos_posts p')
            ->join('tblstaff s', 's.staffid = p.autor_id', 'left')
            ->where('p.grupo_id', $grupo_id)
            ->where('p.deleted', 0)
            ->order_by('p.fixado', 'desc')
            ->order_by('p.dt_created', 'desc')
            ->get()->result_array();
    }

    public function add_post($grupo_id, $conteudo, $tipo = 'mensagem', $autor_id = null)
    {
        $tipo = in_array($tipo, ['mensagem', 'anotacao', 'sistema'], true) ? $tipo : 'mensagem';
        $autor_id = $autor_id ?? (int) get_staff_user_id();
        $conteudo = trim((string) $conteudo);
        if ($conteudo === '') return false;

        $this->db->insert('tbl_grupos_posts', [
            'grupo_id'   => (int) $grupo_id,
            'autor_id'   => $autor_id,
            'tipo'       => $tipo,
            'conteudo'   => $conteudo,
            'fixado'     => 0,
            'dt_created' => date('Y-m-d H:i:s'),
            'deleted'    => 0,
        ]);
        return (int) $this->db->insert_id();
    }

    public function delete_post($post_id, $staff_id = null)
    {
        $staff_id = $staff_id ?? (int) get_staff_user_id();
        $post = $this->db->where('id', $post_id)->get('tbl_grupos_posts')->row_array();
        if (!$post) return false;

        $grupo = $this->get($post['grupo_id']);
        $is_owner_post = (int) $post['autor_id'] === $staff_id;
        $is_owner_grupo = $grupo && ((int) $grupo['lider_id'] === $staff_id || (int) $grupo['user_create'] === $staff_id);

        if (!$is_owner_post && !$is_owner_grupo && !is_admin()) return false;

        $this->db->where('id', $post_id)->update('tbl_grupos_posts', ['deleted' => 1]);
        return true;
    }

    public function toggle_fixado_post($post_id, $staff_id = null)
    {
        $staff_id = $staff_id ?? (int) get_staff_user_id();
        $post = $this->db->where('id', $post_id)->get('tbl_grupos_posts')->row_array();
        if (!$post) return false;

        $grupo = $this->get($post['grupo_id']);
        if (!$grupo || !$this->pode_editar($grupo, $staff_id)) return false;

        $this->db->where('id', $post_id)->update('tbl_grupos_posts', [
            'fixado' => $post['fixado'] ? 0 : 1,
        ]);
        return true;
    }

    private function _add_membro($grupo_id, $staff_id, $papel)
    {
        $existing = $this->db->where(['grupo_id' => $grupo_id, 'staff_id' => $staff_id])
            ->get('tbl_grupos_membros')->row();
        if ($existing) {
            $this->db->where('id', $existing->id)->update('tbl_grupos_membros', [
                'papel' => $papel, 'deleted' => 0,
            ]);
        } else {
            $this->db->insert('tbl_grupos_membros', [
                'grupo_id' => (int) $grupo_id,
                'staff_id' => (int) $staff_id,
                'papel'    => $papel,
                'dt_added' => date('Y-m-d H:i:s'),
                'deleted'  => 0,
            ]);
        }
    }

    public function delete($id)
    {
        $empresa_id = (int) $this->session->userdata('empresa_id');
        return $this->db->where('id', $id)->where('empresa_id', $empresa_id)
            ->update('tbl_grupos', ['deleted' => 1]);
    }

    public function get_atas_do_grupo($grupo_id)
    {
        return $this->db->select('a.id, a.titulo, a.data, a.status, a.responsavel_id,
                                  CONCAT_WS(\' \', s.firstname, s.lastname) AS responsavel_nome')
            ->from('tbl_atas a')
            ->join('tblstaff s', 's.staffid = a.responsavel_id', 'left')
            ->where('a.grupo_id', $grupo_id)
            ->where('a.deleted', 0)
            ->order_by('a.data', 'desc')->order_by('a.id', 'desc')
            ->get()->result_array();
    }

    public function get_planos_do_grupo($grupo_id)
    {
        return $this->db->select('pa.id, pa.titulo, pa.status, pa.dt_fim, pa.metodologia, pa.responsavel_id,
                                  CONCAT_WS(\' \', s.firstname, s.lastname) AS responsavel_nome')
            ->from('tbl_planos_acao pa')
            ->join('tblstaff s', 's.staffid = pa.responsavel_id', 'left')
            ->where('pa.grupo_id', $grupo_id)
            ->where('pa.deleted', 0)
            ->order_by('pa.id', 'desc')
            ->get()->result_array();
    }

    public function get_tasks_do_grupo($grupo_id)
    {
        $grupo_id = (int) $grupo_id;
        $sql = "SELECT t.id, t.name, t.status, t.duedate, t.priority,
                    (SELECT CONCAT_WS(' ', s.firstname, s.lastname)
                     FROM tbltask_assigned ta
                     INNER JOIN tblstaff s ON s.staffid = ta.staffid
                     WHERE ta.taskid = t.id
                     LIMIT 1) AS staff_nome
                FROM tbltasks t
                WHERE t.grupoid = $grupo_id AND t.deleted = 0
                ORDER BY t.id DESC";
        return $this->db->query($sql)->result_array();
    }
}
