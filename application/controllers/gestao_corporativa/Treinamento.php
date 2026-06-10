<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Treinamento extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        if (!is_logged_in()) redirect(base_url('admin/authentication'));
        $this->load->model('Treinamento_model');
        $this->load->model('Staff_model');
        $this->load->model('projects_model');
        $this->load->model('Projeto_fase_model');
        $this->load->model('Documentos_model');
        $this->load->model('Plano_acao_model');
        $this->load->model('Workgroup_model');
    }

    public function index()
    {
        $filtros = [
            'project_id'      => $this->input->get('project_id'),
            'plano_id'        => $this->input->get('plano_id'),
            'grupo_id'        => $this->input->get('grupo_id'),
            'documento_id'    => $this->input->get('documento_id'),
            'status'          => $this->input->get('status'),
            'modalidade'      => $this->input->get('modalidade'),
            'criei'           => $this->input->get('criei'),
            'responsavel_meu' => $this->input->get('responsavel_meu'),
            'inscrito_eu'     => $this->input->get('inscrito_eu'),
            'busca'           => $this->input->get('busca'),
        ];
        $data = [
            'title'        => 'Treinamentos',
            'treinamentos' => $this->Treinamento_model->listar($filtros),
            'filtros'      => $filtros,
            'projects'     => $this->_get_projects(),
        ];
        $this->load->view('gestao_corporativa/treinamentos/list', $data);
    }

    public function add()
    {
        $data = [
            'title'         => 'Novo treinamento',
            'treinamento'   => null,
            'participantes' => [],
            'staffs'        => $this->Staff_model->get(),
            'projects'      => $this->_get_projects(),
            'documentos'    => $this->Documentos_model->listar(['publicado' => 1]),
            'planos'        => $this->Plano_acao_model->listar([]),
            'grupos'        => $this->Workgroup_model->listar([]),
            'preencher'     => [
                'project_id'  => $this->input->get('project_id'),
                'plano_id'    => $this->input->get('plano_id'),
                'grupo_id'    => $this->input->get('grupo_id'),
                'documento_id'=> $this->input->get('documento_id'),
            ],
        ];
        $this->load->view('gestao_corporativa/treinamentos/form', $data);
    }

    public function edit($id)
    {
        $t = $this->Treinamento_model->get($id);
        if (!$t) show_404();
        if (!$this->Treinamento_model->pode_editar($t)) access_denied('Treinamentos');

        $data = [
            'title'         => 'Editar treinamento',
            'treinamento'   => $t,
            'participantes' => $this->Treinamento_model->get_participantes($id),
            'staffs'        => $this->Staff_model->get(),
            'projects'      => $this->_get_projects(),
            'documentos'    => $this->Documentos_model->listar([]),
            'planos'        => $this->Plano_acao_model->listar([]),
            'grupos'        => $this->Workgroup_model->listar([]),
            'preencher'     => [],
        ];
        $this->load->view('gestao_corporativa/treinamentos/form', $data);
    }

    public function view($id)
    {
        $t = $this->Treinamento_model->get($id);
        if (!$t) show_404();
        $data = [
            'title'         => $t['titulo'],
            'treinamento'   => $t,
            'participantes' => $this->Treinamento_model->get_participantes($id),
            'pode_editar'   => $this->Treinamento_model->pode_editar($t),
        ];
        $this->load->view('gestao_corporativa/treinamentos/view', $data);
    }

    public function save()
    {
        $id = (int) $this->input->post('id');
        if ($id) {
            $t = $this->Treinamento_model->get($id);
            if (!$t || !$this->Treinamento_model->pode_editar($t)) access_denied('Treinamentos');
        }
        $new_id = $this->Treinamento_model->save($this->input->post(), $id ?: null);
        if ($new_id) {
            $this->Treinamento_model->set_participantes($new_id, $this->input->post('participantes') ?: []);
        }
        set_alert('success', 'Treinamento salvo.');
        redirect('gestao_corporativa/Treinamento/view/' . $new_id);
    }

    public function delete($id)
    {
        $t = $this->Treinamento_model->get($id);
        if (!$t) show_404();
        if (!$this->Treinamento_model->pode_editar($t)) access_denied('Treinamentos');
        $this->Treinamento_model->delete($id);
        set_alert('success', 'Treinamento excluído.');
        redirect('gestao_corporativa/Treinamento');
    }

    public function presenca($participante_id)
    {
        $row = $this->db->where('id', (int) $participante_id)->get('tbl_treinamentos_participantes')->row();
        if (!$row) show_404();
        $t = $this->Treinamento_model->get($row->treinamento_id);
        if (!$t || !$this->Treinamento_model->pode_editar($t)) access_denied('Treinamentos');
        $presente = $this->input->get('presente') === '1' || $this->input->get('presente') === null && (int) $row->status_inscricao !== 'presente';
        $this->Treinamento_model->marcar_presenca($participante_id, $presente);
        if ($this->input->is_ajax_request()) { echo json_encode(['ok' => true]); return; }
        redirect('gestao_corporativa/Treinamento/view/' . (int) $row->treinamento_id);
    }

    public function nota($participante_id)
    {
        $row = $this->db->where('id', (int) $participante_id)->get('tbl_treinamentos_participantes')->row();
        if (!$row) show_404();
        $t = $this->Treinamento_model->get($row->treinamento_id);
        if (!$t || !$this->Treinamento_model->pode_editar($t)) access_denied('Treinamentos');
        $nota = $this->input->post('nota');
        $aprovado = null;
        if ($t['nota_minima'] !== null && $nota !== '') {
            $aprovado = ((float) str_replace(',', '.', $nota)) >= (float) $t['nota_minima'];
        }
        $this->Treinamento_model->lancar_nota($participante_id, $nota, $aprovado);
        redirect('gestao_corporativa/Treinamento/view/' . (int) $row->treinamento_id);
    }

    /**
     * Seed de treinamentos de exemplo pra demonstrar a tela.
     * Acesso: somente admin, e exige ?confirmar=1.
     * Idempotente: nao duplica (checa pelo codigo). REMOVER apos a demo.
     */
    public function exemplos()
    {
        if (!is_admin()) access_denied('Treinamentos');

        if ($this->input->get('confirmar') != '1') {
            echo '<div style="font-family:sans-serif;max-width:560px;margin:40px auto;">';
            echo '<h3>Seed de treinamentos de exemplo</h3>';
            echo '<p>Vai cadastrar <b>6 treinamentos</b> de demonstração (planejados, em andamento e concluídos), com participantes inscritos e, nos concluídos, presença e notas lançadas. É idempotente — não duplica se rodar de novo.</p>';
            echo '<p><a href="' . base_url('gestao_corporativa/Treinamento/exemplos?confirmar=1') . '" style="background:#16a085;color:#fff;padding:8px 14px;border-radius:6px;text-decoration:none;">Confirmar e criar exemplos</a></p>';
            echo '</div>';
            return;
        }

        // staff disponiveis pra usar como participantes
        $staff_rows = $this->db->select('staffid')->from('tblstaff')
            ->where('active', 1)->order_by('staffid', 'asc')->limit(8)->get()->result_array();
        $staff_ids = array_map(function ($r) { return (int) $r['staffid']; }, $staff_rows);
        if (empty($staff_ids)) $staff_ids = [(int) get_staff_user_id()];
        $instrutor = (int) get_staff_user_id();

        $defs = [
            [
                'codigo' => 'TRN-EX-INTEG', 'titulo' => 'Integração de Novos Colaboradores',
                'descricao' => 'Boas-vindas, cultura organizacional, normas internas e benefícios.',
                'conteudo' => "1. Histórico e missão\n2. Código de conduta\n3. Benefícios e RH\n4. Segurança do trabalho",
                'modalidade' => 'presencial', 'carga' => 8, 'local' => 'Auditório - Sede',
                'dt_inicio' => '2026-03-10 08:00:00', 'dt_fim' => '2026-03-10 17:00:00',
                'status' => 'concluido', 'nota_minima' => 7, 'certificado' => 1,
                'n_part' => 6, 'avaliar' => true,
            ],
            [
                'codigo' => 'TRN-EX-LGPD', 'titulo' => 'LGPD na Saúde',
                'descricao' => 'Tratamento de dados sensíveis de pacientes e responsabilidades da operadora.',
                'conteudo' => "1. Conceitos da LGPD\n2. Dados sensíveis em saúde\n3. Consentimento\n4. Incidentes e ANPD",
                'modalidade' => 'ead', 'carga' => 4, 'local' => 'Plataforma EAD',
                'dt_inicio' => '2026-04-01 00:00:00', 'dt_fim' => '2026-04-30 23:59:00',
                'status' => 'concluido', 'nota_minima' => 6, 'certificado' => 1,
                'n_part' => 5, 'avaliar' => true,
            ],
            [
                'codigo' => 'TRN-EX-SEGPAC', 'titulo' => 'Segurança do Paciente',
                'descricao' => 'Protocolos de segurança e metas internacionais (OMS).',
                'conteudo' => "1. 6 metas internacionais\n2. Identificação do paciente\n3. Higienização das mãos\n4. Notificação de eventos",
                'modalidade' => 'presencial', 'carga' => 6, 'local' => 'Sala de Treinamento 2',
                'dt_inicio' => '2026-06-05 13:00:00', 'dt_fim' => '2026-06-20 17:00:00',
                'status' => 'em_andamento', 'nota_minima' => 7, 'certificado' => 1,
                'n_part' => 5, 'avaliar' => false,
            ],
            [
                'codigo' => 'TRN-EX-HUMAN', 'titulo' => 'Atendimento Humanizado',
                'descricao' => 'Comunicação empática e acolhimento do beneficiário.',
                'conteudo' => "1. Escuta ativa\n2. Comunicação não violenta\n3. Gestão de conflitos",
                'modalidade' => 'online', 'carga' => 3, 'local' => 'Microsoft Teams',
                'dt_inicio' => '2026-07-08 19:00:00', 'dt_fim' => '2026-07-08 22:00:00',
                'status' => 'inscricoes_abertas', 'nota_minima' => null, 'certificado' => 0,
                'n_part' => 4, 'avaliar' => false,
            ],
            [
                'codigo' => 'TRN-EX-TISS', 'titulo' => 'Faturamento e Glosas (TISS)',
                'descricao' => 'Padrão TISS, regras de faturamento e prevenção de glosas.',
                'conteudo' => "1. Padrão TISS\n2. Tabelas TUSS\n3. Principais causas de glosa\n4. Recurso de glosa",
                'modalidade' => 'autoinstrutivo', 'carga' => 5, 'local' => 'Plataforma EAD',
                'dt_inicio' => '2026-08-01 00:00:00', 'dt_fim' => '2026-08-31 23:59:00',
                'status' => 'planejado', 'nota_minima' => 7, 'certificado' => 1,
                'n_part' => 0, 'avaliar' => false,
            ],
            [
                'codigo' => 'TRN-EX-BRIG', 'titulo' => 'Brigada de Incêndio',
                'descricao' => 'Prevenção e combate a princípios de incêndio e evacuação.',
                'conteudo' => "1. Teoria do fogo\n2. Extintores\n3. Rota de fuga\n4. Simulado prático",
                'modalidade' => 'presencial', 'carga' => 8, 'local' => 'Pátio externo',
                'dt_inicio' => '2026-05-15 08:00:00', 'dt_fim' => '2026-05-15 17:00:00',
                'status' => 'concluido', 'nota_minima' => 7, 'certificado' => 1,
                'n_part' => 6, 'avaliar' => true,
            ],
        ];

        // notas e presencas ciclicas pra gerar variedade (aprovado/reprovado/ausente)
        $notas_ciclo     = [9.0, 8.5, 7.0, 6.0, 8.0, 5.5];
        $presenca_ciclo  = [true, true, true, false, true, true];

        $empresa_id = (int) $this->session->userdata('empresa_id');
        $criados = 0; $pulados = 0; $participantes = 0;

        foreach ($defs as $d) {
            $existe = $this->db->where('codigo', $d['codigo'])
                ->where('empresa_id', $empresa_id)->where('deleted', 0)
                ->get('tbl_treinamentos')->row();
            if ($existe) { $pulados++; continue; }

            $trn_id = $this->Treinamento_model->save([
                'codigo'                => $d['codigo'],
                'titulo'                => $d['titulo'],
                'descricao'             => $d['descricao'],
                'conteudo_programatico' => $d['conteudo'],
                'modalidade'            => $d['modalidade'],
                'carga_horaria'         => $d['carga'],
                'dt_inicio'             => $d['dt_inicio'],
                'dt_fim'                => $d['dt_fim'],
                'local'                 => $d['local'],
                'instrutor_staff_id'    => $instrutor,
                'status'                => $d['status'],
                'nota_minima'           => $d['nota_minima'],
                'emite_certificado'     => $d['certificado'],
            ]);
            $criados++;

            // inscreve os N primeiros staffs
            $part_ids = array_slice($staff_ids, 0, (int) $d['n_part']);
            if ($part_ids) {
                $this->Treinamento_model->set_participantes($trn_id, $part_ids);
                $participantes += count($part_ids);
            }

            // turmas concluidas: lanca presenca e notas
            if (!empty($d['avaliar'])) {
                $rows = $this->Treinamento_model->get_participantes($trn_id);
                foreach (array_values($rows) as $i => $p) {
                    $presente = $presenca_ciclo[$i % count($presenca_ciclo)];
                    $this->Treinamento_model->marcar_presenca($p['id'], $presente);
                    if ($presente) {
                        $nota = $notas_ciclo[$i % count($notas_ciclo)];
                        $aprovado = ($d['nota_minima'] !== null) ? ($nota >= (float) $d['nota_minima']) : null;
                        $this->Treinamento_model->lancar_nota($p['id'], $nota, $aprovado);
                    }
                }
            }
        }

        echo '<div style="font-family:sans-serif;max-width:560px;margin:40px auto;">';
        echo '<h3>Pronto!</h3>';
        echo "<p>Treinamentos criados: <b>$criados</b> &middot; já existiam (pulados): <b>$pulados</b> &middot; participantes inscritos: <b>$participantes</b>.</p>";
        echo '<p><a href="' . base_url('gestao_corporativa/Treinamento') . '" style="background:#16a085;color:#fff;padding:8px 14px;border-radius:6px;text-decoration:none;">Ver treinamentos</a></p>';
        echo '</div>';
    }

    private function _get_projects()
    {
        $empresa_id = (int) $this->session->userdata('empresa_id');
        return $this->db->select('id, name')->from('tblprojects')
            ->where('empresa_id', $empresa_id)->where('deleted', 0)
            ->order_by('name', 'asc')->get()->result_array();
    }
}
