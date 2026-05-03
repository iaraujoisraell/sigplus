<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Formulario_model extends App_Model
{
    private $statuses = ['rascunho', 'publicado', 'encerrado', 'arquivado'];

    private $tipos = [
        'text'         => 'Resposta curta',
        'textarea'     => 'Parágrafo',
        'number'       => 'Número',
        'date'         => 'Data',
        'datetime'     => 'Data e hora',
        'email'        => 'E-mail',
        'radio'        => 'Múltipla escolha (uma opção)',
        'checkbox'     => 'Caixas de seleção (várias)',
        'select'       => 'Lista suspensa',
        'scale'        => 'Escala (1 a 5)',
    ];

    public function get_statuses() { return $this->statuses; }
    public function get_tipos()    { return $this->tipos; }

    public function get_status_label($s)
    {
        return ['rascunho' => 'Rascunho', 'publicado' => 'Publicado', 'encerrado' => 'Encerrado', 'arquivado' => 'Arquivado'][$s] ?? ucfirst($s);
    }

    public function get_tipo_label($t)
    {
        return $this->tipos[$t] ?? $t;
    }

    public function tipo_tem_opcoes($tipo)
    {
        return in_array($tipo, ['radio', 'checkbox', 'select'], true);
    }

    /* ============ Listagem / leitura ============ */

    public function listar($filtros = [])
    {
        $empresa_id = (int) $this->session->userdata('empresa_id');
        $me = (int) get_staff_user_id();

        $this->db->select("f.*, CONCAT_WS(' ', s.firstname, s.lastname) AS criador_nome,
                           p.name AS project_name,
                           (SELECT COUNT(DISTINCT hash) FROM tbl_intranet_formularios_respostas r WHERE r.form_id = f.id) AS total_respostas,
                           (SELECT COUNT(*) FROM tbl_intranet_formularios_perguntas pq WHERE pq.form_id = f.id AND pq.deleted = 0) AS total_perguntas");
        $this->db->from('tbl_intranet_formularios f');
        $this->db->join('tblstaff s', 's.staffid = f.user_created', 'left');
        $this->db->join('tblprojects p', 'p.id = f.project_id', 'left');
        $this->db->where('f.deleted', 0);
        $this->db->where('f.empresa_id', $empresa_id);
        $this->db->where('f.form_pai', 0);

        if (!empty($filtros['project_id'])) $this->db->where('f.project_id', (int) $filtros['project_id']);
        if (!empty($filtros['fase_id']))    $this->db->where('f.fase_id', (int) $filtros['fase_id']);
        if (!empty($filtros['ata_id']))     $this->db->where('f.ata_id', (int) $filtros['ata_id']);
        if (!empty($filtros['plano_id']))   $this->db->where('f.plano_id', (int) $filtros['plano_id']);
        if (!empty($filtros['grupo_id']))   $this->db->where('f.grupo_id', (int) $filtros['grupo_id']);
        if (!empty($filtros['status']))     $this->db->where('f.status', $filtros['status']);
        if (!empty($filtros['meu']))        $this->db->where('f.user_created', $me);

        if (!empty($filtros['busca'])) {
            $term = $this->db->escape_like_str($filtros['busca']);
            $this->db->where("(f.titulo LIKE '%{$term}%' OR f.descricao LIKE '%{$term}%')", null, false);
        }

        $this->db->order_by('f.id', 'desc');
        return $this->db->get()->result_array();
    }

    public function get($id)
    {
        $empresa_id = (int) $this->session->userdata('empresa_id');
        return $this->db->select('f.*, p.name AS project_name')
            ->from('tbl_intranet_formularios f')
            ->join('tblprojects p', 'p.id = f.project_id', 'left')
            ->where('f.id', (int) $id)
            ->where('f.empresa_id', $empresa_id)
            ->where('f.deleted', 0)
            ->get()->row_array();
    }

    public function get_by_key($key)
    {
        return $this->db->where('form_key', $key)
            ->where('deleted', 0)
            ->get('tbl_intranet_formularios')->row_array();
    }

    public function get_perguntas($form_id)
    {
        $perguntas = $this->db->where('form_id', (int) $form_id)
            ->where('deleted', 0)
            ->order_by('pagina', 'asc')->order_by('ordem', 'asc')->order_by('id', 'asc')
            ->get('tbl_intranet_formularios_perguntas')->result_array();

        if (!$perguntas) return [];

        $ids = array_column($perguntas, 'id');
        $opcoes = $this->db->where_in('pergunta_id', $ids)
            ->where('deleted', 0)
            ->order_by('ordem', 'asc')->order_by('id', 'asc')
            ->get('tbl_intranet_formularios_items_multiescolha')->result_array();

        $opcoes_por_perg = [];
        foreach ($opcoes as $o) {
            $opcoes_por_perg[$o['pergunta_id']][] = $o;
        }

        foreach ($perguntas as &$p) {
            $p['opcoes'] = $opcoes_por_perg[$p['id']] ?? [];
            $p['configuracao_arr'] = !empty($p['configuracao']) ? json_decode($p['configuracao'], true) : [];
        }
        return $perguntas;
    }

    public function pode_editar($form, $staff_id = null)
    {
        $staff_id = $staff_id ?? (int) get_staff_user_id();
        if ((int) ($form['user_created'] ?? 0) === $staff_id) return true;
        return is_admin();
    }

    /* ============ CRUD formulário ============ */

    public function save($data, $id = null)
    {
        $empresa_id = (int) $this->session->userdata('empresa_id');
        $me = (int) get_staff_user_id();

        $clean = [
            'titulo'             => trim((string) ($data['titulo'] ?? 'Formulário sem título')),
            'descricao'          => $data['descricao'] ?? null,
            'success_submit_msg' => $data['success_submit_msg'] ?? null,
            'project_id'         => !empty($data['project_id']) ? (int) $data['project_id'] : null,
            'fase_id'            => !empty($data['fase_id']) ? (int) $data['fase_id'] : null,
            'ata_id'             => !empty($data['ata_id']) ? (int) $data['ata_id'] : null,
            'plano_id'           => !empty($data['plano_id']) ? (int) $data['plano_id'] : null,
            'grupo_id'           => !empty($data['grupo_id']) ? (int) $data['grupo_id'] : null,
            'status'             => in_array($data['status'] ?? '', $this->statuses, true) ? $data['status'] : 'rascunho',
            'visibilidade'       => in_array($data['visibilidade'] ?? '', ['publica', 'restrita'], true) ? $data['visibilidade'] : 'publica',
        ];

        if ($id) {
            $clean['user_update'] = $me;
            $clean['dt_updated']  = date('Y-m-d H:i:s');
            if ($clean['status'] === 'publicado') {
                $atual = $this->db->where('id', $id)->get('tbl_intranet_formularios')->row_array();
                if ($atual && empty($atual['dt_publicado'])) $clean['dt_publicado'] = date('Y-m-d H:i:s');
            }
            $this->db->where('id', $id)->where('empresa_id', $empresa_id)
                ->update('tbl_intranet_formularios', $clean);
            return (int) $id;
        }

        $clean['empresa_id']   = $empresa_id;
        $clean['user_created'] = $me;
        $clean['data_created'] = date('Y-m-d');
        $clean['form_key']     = app_generate_hash();
        $clean['form_pai']     = 0;
        $this->db->insert('tbl_intranet_formularios', $clean);
        return (int) $this->db->insert_id();
    }

    public function delete($id)
    {
        $empresa_id = (int) $this->session->userdata('empresa_id');
        return $this->db->where('id', $id)->where('empresa_id', $empresa_id)
            ->update('tbl_intranet_formularios', ['deleted' => 1]);
    }

    public function duplicar($id)
    {
        $form = $this->get($id);
        if (!$form) return false;

        $novo = $form;
        unset($novo['id'], $novo['project_name']);
        $novo['titulo']       = $form['titulo'] . ' (cópia)';
        $novo['form_key']     = app_generate_hash();
        $novo['status']       = 'rascunho';
        $novo['data_created'] = date('Y-m-d');
        $novo['dt_updated']   = null;
        $novo['dt_publicado'] = null;
        $novo['user_created'] = (int) get_staff_user_id();
        $this->db->insert('tbl_intranet_formularios', $novo);
        $new_id = (int) $this->db->insert_id();

        $perguntas = $this->get_perguntas($id);
        foreach ($perguntas as $p) {
            $opcoes = $p['opcoes'];
            unset($p['id'], $p['opcoes'], $p['configuracao_arr']);
            $p['form_id']      = $new_id;
            $p['data_created'] = date('Y-m-d');
            $p['user_created'] = (int) get_staff_user_id();
            $this->db->insert('tbl_intranet_formularios_perguntas', $p);
            $new_perg_id = (int) $this->db->insert_id();

            foreach ($opcoes as $o) {
                unset($o['id']);
                $o['pergunta_id']  = $new_perg_id;
                $o['datecreated']  = date('Y-m-d H:i:s');
                $o['user_created'] = (int) get_staff_user_id();
                $this->db->insert('tbl_intranet_formularios_items_multiescolha', $o);
            }
        }
        return $new_id;
    }

    /* ============ CRUD perguntas ============ */

    public function save_pergunta($data, $id = null)
    {
        $empresa_id = (int) $this->session->userdata('empresa_id');
        $me = (int) get_staff_user_id();

        $tipo = isset($data['tipo']) && array_key_exists($data['tipo'], $this->tipos) ? $data['tipo'] : 'text';

        $clean = [
            'title'        => trim((string) ($data['title'] ?? 'Pergunta sem título')),
            'descricao'    => $data['descricao'] ?? null,
            'tipo'         => $tipo,
            'required'     => !empty($data['required']) ? 1 : 0,
            'pagina'       => isset($data['pagina']) ? max(1, (int) $data['pagina']) : 1,
            'configuracao' => !empty($data['configuracao']) ? (is_array($data['configuracao']) ? json_encode($data['configuracao']) : $data['configuracao']) : null,
        ];

        if ($id) {
            $this->db->where('id', $id)->where('empresa_id', $empresa_id)
                ->update('tbl_intranet_formularios_perguntas', $clean);
            return (int) $id;
        }

        $form_id = (int) ($data['form_id'] ?? 0);
        if ($form_id <= 0) return false;

        $max_ordem = (int) $this->db->select_max('ordem')->where('form_id', $form_id)
            ->where('deleted', 0)->get('tbl_intranet_formularios_perguntas')->row()->ordem;

        $clean['form_id']      = $form_id;
        $clean['empresa_id']   = $empresa_id;
        $clean['user_created'] = $me;
        $clean['data_created'] = date('Y-m-d');
        $clean['form_key']     = app_generate_hash();
        $clean['ordem']        = $max_ordem + 1;
        $clean['name']         = $this->_slugify($clean['title']);

        $this->db->insert('tbl_intranet_formularios_perguntas', $clean);
        return (int) $this->db->insert_id();
    }

    public function delete_pergunta($id)
    {
        $empresa_id = (int) $this->session->userdata('empresa_id');
        return $this->db->where('id', $id)->where('empresa_id', $empresa_id)
            ->update('tbl_intranet_formularios_perguntas', ['deleted' => 1]);
    }

    public function reorder_perguntas($form_id, $ordem)
    {
        $empresa_id = (int) $this->session->userdata('empresa_id');
        foreach ((array) $ordem as $idx => $pid) {
            $this->db->where('id', (int) $pid)->where('form_id', (int) $form_id)
                ->where('empresa_id', $empresa_id)
                ->update('tbl_intranet_formularios_perguntas', ['ordem' => $idx + 1]);
        }
        return true;
    }

    /* ============ CRUD opções ============ */

    public function add_opcao($pergunta_id, $name)
    {
        $empresa_id = (int) $this->session->userdata('empresa_id');
        $me = (int) get_staff_user_id();
        $name = trim((string) $name);
        if ($name === '') return false;

        $max_ordem = (int) $this->db->select_max('ordem')->where('pergunta_id', (int) $pergunta_id)
            ->where('deleted', 0)->get('tbl_intranet_formularios_items_multiescolha')->row()->ordem;

        $this->db->insert('tbl_intranet_formularios_items_multiescolha', [
            'pergunta_id'  => (int) $pergunta_id,
            'name'         => $name,
            'ordem'        => $max_ordem + 1,
            'empresa_id'   => $empresa_id,
            'datecreated'  => date('Y-m-d H:i:s'),
            'user_created' => $me,
            'deleted'      => 0,
        ]);
        return (int) $this->db->insert_id();
    }

    public function update_opcao($id, $name)
    {
        $name = trim((string) $name);
        if ($name === '') return false;
        return $this->db->where('id', (int) $id)
            ->update('tbl_intranet_formularios_items_multiescolha', ['name' => $name]);
    }

    public function delete_opcao($id)
    {
        return $this->db->where('id', (int) $id)
            ->update('tbl_intranet_formularios_items_multiescolha', ['deleted' => 1]);
    }

    /* ============ Respostas ============ */

    public function salvar_respostas($form_id, $hash, $respostas, $staff_id = null)
    {
        $form = $this->get($form_id);
        if (!$form) return false;

        $perguntas = $this->get_perguntas($form_id);
        $perg_map = [];
        foreach ($perguntas as $p) $perg_map[$p['id']] = $p;

        $ip = $this->input->ip_address();
        $now = date('Y-m-d H:i:s');
        $empresa_id = (int) $form['empresa_id'];

        foreach ((array) $respostas as $perg_id => $valor) {
            if (!isset($perg_map[$perg_id])) continue;
            $perg = $perg_map[$perg_id];

            // checkbox vem como array
            if (is_array($valor)) $valor = implode('|', array_map('trim', $valor));
            $valor = (string) $valor;

            if ($perg['required'] && trim($valor) === '') continue;

            $this->db->insert('tbl_intranet_formularios_respostas', [
                'form_id'       => (int) $form_id,
                'form_key'      => $form['form_key'],
                'pergunta_id'   => (int) $perg_id,
                'pergunta'      => $perg['title'],
                'value'         => $valor,
                'data_cadastro' => $now,
                'ip'            => $ip,
                'sessao'        => 1,
                'total_sessao'  => 1,
                'hash'          => $hash,
                'staff_id'      => $staff_id ? (int) $staff_id : null,
                'empresa_id'    => $empresa_id,
            ]);
        }
        return true;
    }

    public function get_respostas($form_id)
    {
        return $this->db->select("r.*, CONCAT_WS(' ', s.firstname, s.lastname) AS staff_nome")
            ->from('tbl_intranet_formularios_respostas r')
            ->join('tblstaff s', 's.staffid = r.staff_id', 'left')
            ->where('r.form_id', (int) $form_id)
            ->order_by('r.hash', 'asc')->order_by('r.id', 'asc')
            ->get()->result_array();
    }

    public function get_respostas_agrupadas($form_id)
    {
        $rows = $this->get_respostas($form_id);
        $grupos = [];
        foreach ($rows as $r) {
            $h = $r['hash'];
            if (!isset($grupos[$h])) {
                $grupos[$h] = [
                    'hash'          => $h,
                    'data_cadastro' => $r['data_cadastro'],
                    'staff_nome'    => $r['staff_nome'],
                    'staff_id'      => $r['staff_id'],
                    'ip'            => $r['ip'],
                    'respostas'     => [],
                ];
            }
            $grupos[$h]['respostas'][(int) $r['pergunta_id']] = $r['value'];
        }
        usort($grupos, function ($a, $b) { return strcmp($b['data_cadastro'], $a['data_cadastro']); });
        return $grupos;
    }

    public function count_respostas($form_id)
    {
        return (int) $this->db->select("COUNT(DISTINCT hash) AS n", false)
            ->where('form_id', (int) $form_id)
            ->get('tbl_intranet_formularios_respostas')->row()->n;
    }

    /* ============ Compat: API antiga ============ */

    public function get_formulario_by_user_id()
    {
        return $this->listar(['meu' => 1]);
    }

    public function get_total_resposta_formulario_by_key($form_key)
    {
        $row = $this->db->select('COUNT(DISTINCT hash) AS total', false)
            ->where('form_key', $form_key)
            ->get('tbl_intranet_formularios_respostas')->row();
        return $row ?: (object) ['total' => 0];
    }

    /* ============ Opções da empresa (logo/nome) — usadas na tela pública ============ */

    public function get_option_logo($empresa_id)
    {
        return $this->db->where('name', 'company_logo')
            ->where('empresa_id', (int) $empresa_id)
            ->get('tbloptions')->row();
    }

    public function get_option_nome_empresa($empresa_id)
    {
        return $this->db->where('name', 'companyname')
            ->where('empresa_id', (int) $empresa_id)
            ->get('tbloptions')->row();
    }

    /* ============ Helpers ============ */

    private function _slugify($txt)
    {
        $txt = preg_replace('/[áàãâä]/u', 'a', $txt);
        $txt = preg_replace('/[éèêë]/u', 'e', $txt);
        $txt = preg_replace('/[íìîï]/u', 'i', $txt);
        $txt = preg_replace('/[óòõôö]/u', 'o', $txt);
        $txt = preg_replace('/[úùûü]/u', 'u', $txt);
        $txt = preg_replace('/[ç]/u', 'c', $txt);
        $txt = preg_replace('/[ÁÀÃÂÄ]/u', 'A', $txt);
        $txt = preg_replace('/[ÉÈÊË]/u', 'E', $txt);
        $txt = preg_replace('/[ÍÌÎÏ]/u', 'I', $txt);
        $txt = preg_replace('/[ÓÒÕÔÖ]/u', 'O', $txt);
        $txt = preg_replace('/[ÚÙÛÜ]/u', 'U', $txt);
        $txt = preg_replace('/[Ç]/u', 'C', $txt);
        $txt = strtolower(trim($txt));
        $txt = preg_replace('/[^a-z0-9]+/', '_', $txt);
        return trim($txt, '_');
    }
}
