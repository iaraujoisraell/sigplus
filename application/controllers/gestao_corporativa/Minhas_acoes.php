<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Minhas_acoes extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        if (!is_logged_in()) redirect(base_url('admin/authentication'));
    }

    public function index()
    {
        $me = (int) get_staff_user_id();
        $empresa_id = (int) $this->session->userdata('empresa_id');

        // ---- Tasks do Perfex atribuídas a mim, abertas (status != 5 = complete) ----
        $tasks = $this->db->select("t.id, t.name, t.duedate, t.priority, t.status, t.rel_id, t.rel_type,
                                    p.name AS project_name, t.fase_id")
            ->from('tbltasks t')
            ->join('tblproject_members pm', '1=1', 'left')
            ->join('tblprojects p', 'p.id = t.rel_id AND t.rel_type = "project"', 'left')
            ->join('tbltask_assigned ta', 'ta.taskid = t.id', 'inner')
            ->where('ta.staffid', $me)
            ->where('t.deleted', 0)
            ->where('t.status !=', 5)
            ->order_by('t.duedate', 'asc')
            ->get()->result_array();

        // ---- Decisões de atas atribuídas a mim, abertas ----
        $decisoes = $this->db->select("d.id, d.ata_id, d.descricao, d.prazo, d.status, d.task_id,
                                       a.titulo AS ata_titulo")
            ->from('tbl_atas_decisoes d')
            ->join('tbl_atas a', 'a.id = d.ata_id', 'inner')
            ->where('d.responsavel_id', $me)
            ->where('d.deleted', 0)
            ->where('a.deleted', 0)
            ->where('a.empresa_id', $empresa_id)
            ->where_not_in('d.status', ['concluida', 'cancelada'])
            ->order_by('d.prazo', 'asc')
            ->get()->result_array();

        // ---- Itens 5W2H atribuídos a mim, abertos ----
        $itens5w2h = $this->db->select("w.id, w.plano_id, w.what, w.why, w.where AS local, w.`when` AS prazo,
                                        w.how, w.status, w.task_id,
                                        pa.titulo AS plano_titulo")
            ->from('tbl_planos_acao_5w2h w')
            ->join('tbl_planos_acao pa', 'pa.id = w.plano_id', 'inner')
            ->where('w.who_id', $me)
            ->where('w.deleted', 0)
            ->where('pa.deleted', 0)
            ->where('pa.empresa_id', $empresa_id)
            ->where_not_in('w.status', ['concluido', 'cancelado'])
            ->order_by('w.`when`', 'asc')
            ->get()->result_array();

        // ---- Aprovações de documento pendentes pra mim ----
        $aprovacoes_doc = $this->db->select("a.id AS apv_id, a.doc_id, a.fluxo_sequencia,
                                             d.titulo, d.codigo, d.id AS doc_id_alias")
            ->from('tbl_intranet_documento_aprovacao a')
            ->join('tbl_intranet_documento d', 'd.id = a.doc_id', 'inner')
            ->where('a.staff_id', $me)
            ->where('a.status', 0)
            ->where('a.deleted', 0)
            ->where('d.deleted', 0)
            ->where('d.empresa_id', $empresa_id)
            ->order_by('a.id', 'desc')
            ->get()->result_array();

        $data = [
            'title'          => 'Minhas Ações',
            'tasks'          => $tasks,
            'decisoes'       => $decisoes,
            'itens5w2h'      => $itens5w2h,
            'aprovacoes_doc' => $aprovacoes_doc,
        ];
        $this->load->view('gestao_corporativa/minhas_acoes/index', $data);
    }
}
