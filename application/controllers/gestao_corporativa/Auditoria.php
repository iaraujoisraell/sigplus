<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Auditoria extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        if (!is_logged_in()) redirect(base_url('admin/authentication'));
        $this->load->model('Auditoria_model');
        $this->load->model('Staff_model');
        $this->load->model('projects_model');
        $this->load->model('Projeto_fase_model');
        $this->load->model('Departments_model');
        $this->load->model('Documentos_model');
        $this->load->model('Plano_acao_model');
        $this->load->model('Workgroup_model');
        $this->load->model('Formulario_model');
    }

    public function index()
    {
        $filtros = [
            'tipo'            => $this->input->get('tipo'),
            'status'          => $this->input->get('status'),
            'resultado'       => $this->input->get('resultado'),
            'setor_id'        => $this->input->get('setor_id'),
            'project_id'      => $this->input->get('project_id'),
            'plano_id'        => $this->input->get('plano_id'),
            'grupo_id'        => $this->input->get('grupo_id'),
            'documento_id'    => $this->input->get('documento_id'),
            'criei'           => $this->input->get('criei'),
            'responsavel_meu' => $this->input->get('responsavel_meu'),
            'busca'           => $this->input->get('busca'),
        ];
        $data = [
            'title'       => 'Auditorias',
            'auditorias'  => $this->Auditoria_model->listar($filtros),
            'filtros'     => $filtros,
            'projects'    => $this->_get_projects(),
            'departments' => $this->Departments_model->get(),
        ];
        $this->load->view('gestao_corporativa/auditorias/list', $data);
    }

    public function add()
    {
        $data = [
            'title'       => 'Nova auditoria',
            'auditoria'   => null,
            'auditados'   => [],
            'staffs'      => $this->Staff_model->get(),
            'projects'    => $this->_get_projects(),
            'departments' => $this->Departments_model->get(),
            'documentos'  => $this->Documentos_model->listar([]),
            'planos'      => $this->Plano_acao_model->listar([]),
            'grupos'      => $this->Workgroup_model->listar([]),
            'formularios' => $this->Formulario_model->listar([]),
            'preencher'   => [
                'project_id'              => $this->input->get('project_id'),
                'plano_id'                => $this->input->get('plano_id'),
                'grupo_id'                => $this->input->get('grupo_id'),
                'documento_referencia_id' => $this->input->get('documento_id'),
            ],
        ];
        $this->load->view('gestao_corporativa/auditorias/form', $data);
    }

    public function edit($id)
    {
        $a = $this->Auditoria_model->get($id);
        if (!$a) show_404();
        if (!$this->Auditoria_model->pode_editar($a)) access_denied('Auditorias');

        $data = [
            'title'       => 'Editar auditoria',
            'auditoria'   => $a,
            'auditados'   => $this->Auditoria_model->get_auditados($id),
            'staffs'      => $this->Staff_model->get(),
            'projects'    => $this->_get_projects(),
            'departments' => $this->Departments_model->get(),
            'documentos'  => $this->Documentos_model->listar([]),
            'planos'      => $this->Plano_acao_model->listar([]),
            'grupos'      => $this->Workgroup_model->listar([]),
            'formularios' => $this->Formulario_model->listar([]),
            'preencher'   => [],
        ];
        $this->load->view('gestao_corporativa/auditorias/form', $data);
    }

    public function view($id)
    {
        $a = $this->Auditoria_model->get($id);
        if (!$a) show_404();
        $data = [
            'title'       => $a['titulo'],
            'auditoria'   => $a,
            'auditados'   => $this->Auditoria_model->get_auditados($id),
            'achados'     => $this->Auditoria_model->get_achados($id),
            'pode_editar' => $this->Auditoria_model->pode_editar($a),
            'staffs'      => $this->Staff_model->get(),
            'planos'      => $this->Plano_acao_model->listar([]),
        ];
        $this->load->view('gestao_corporativa/auditorias/view', $data);
    }

    public function save()
    {
        $id = (int) $this->input->post('id');
        if ($id) {
            $a = $this->Auditoria_model->get($id);
            if (!$a || !$this->Auditoria_model->pode_editar($a)) access_denied('Auditorias');
        }
        $new_id = $this->Auditoria_model->save($this->input->post(), $id ?: null);
        if ($new_id) {
            $this->Auditoria_model->set_auditados($new_id, $this->input->post('auditados') ?: []);
        }
        set_alert('success', 'Auditoria salva.');
        redirect('gestao_corporativa/Auditoria/view/' . $new_id);
    }

    public function delete($id)
    {
        $a = $this->Auditoria_model->get($id);
        if (!$a) show_404();
        if (!$this->Auditoria_model->pode_editar($a)) access_denied('Auditorias');
        $this->Auditoria_model->delete($id);
        set_alert('success', 'Auditoria excluída.');
        redirect('gestao_corporativa/Auditoria');
    }

    /* ------- Achados (AJAX-ish) ------- */

    public function achado_save()
    {
        $auditoria_id = (int) $this->input->post('auditoria_id');
        $a = $this->Auditoria_model->get($auditoria_id);
        if (!$a || !$this->Auditoria_model->pode_editar($a)) access_denied('Auditorias');
        $id = (int) $this->input->post('id');
        $this->Auditoria_model->save_achado($this->input->post(), $id ?: null);
        redirect('gestao_corporativa/Auditoria/view/' . $auditoria_id);
    }

    public function achado_delete($id)
    {
        $row = $this->db->where('id', (int) $id)->get('tbl_auditorias_achados')->row();
        if (!$row) show_404();
        $a = $this->Auditoria_model->get($row->auditoria_id);
        if (!$a || !$this->Auditoria_model->pode_editar($a)) access_denied('Auditorias');
        $this->Auditoria_model->delete_achado($id);
        redirect('gestao_corporativa/Auditoria/view/' . (int) $row->auditoria_id);
    }

    public function achado_gerar_ocorrencia($id)
    {
        $row = $this->db->where('id', (int) $id)->get('tbl_auditorias_achados')->row();
        if (!$row) show_404();
        $a = $this->Auditoria_model->get($row->auditoria_id);
        if (!$a || !$this->Auditoria_model->pode_editar($a)) access_denied('Auditorias');
        $oc_id = $this->Auditoria_model->gerar_ocorrencia($id);
        if ($oc_id) set_alert('success', 'Ocorrência gerada (R.O #' . (int) $oc_id . ').');
        else        set_alert('warning', 'Não foi possível gerar ocorrência.');
        redirect('gestao_corporativa/Auditoria/view/' . (int) $row->auditoria_id);
    }

    /**
     * Seed de auditorias de exemplo pra demonstrar a tela.
     * Acesso: somente admin, e exige ?confirmar=1.
     * Idempotente: nao duplica (checa pelo codigo). REMOVER apos a demo.
     */
    public function exemplos()
    {
        if (!is_admin()) access_denied('Auditorias');

        if ($this->input->get('confirmar') != '1') {
            echo '<div style="font-family:sans-serif;max-width:560px;margin:40px auto;">';
            echo '<h3>Seed de auditorias de exemplo</h3>';
            echo '<p>Vai cadastrar <b>6 auditorias</b> de demonstração (planejada, em execução, encerrada, cancelada), com auditados vinculados e achados (NC maior/menor, observação, oportunidade) nas encerradas. É idempotente — não duplica se rodar de novo.</p>';
            echo '<p><a href="' . base_url('gestao_corporativa/Auditoria/exemplos?confirmar=1') . '" style="background:#16a085;color:#fff;padding:8px 14px;border-radius:6px;text-decoration:none;">Confirmar e criar exemplos</a></p>';
            echo '</div>';
            return;
        }

        // staff e setor pra vincular
        $staff_rows = $this->db->select('staffid')->from('tblstaff')
            ->where('active', 1)->order_by('staffid', 'asc')->limit(8)->get()->result_array();
        $staff_ids = array_map(function ($r) { return (int) $r['staffid']; }, $staff_rows);
        if (empty($staff_ids)) $staff_ids = [(int) get_staff_user_id()];
        $lider = (int) get_staff_user_id();
        $resp = $staff_ids[0];

        $dep_rows = $this->db->select('departmentid')->order_by('departmentid', 'asc')
            ->limit(6)->get('tbldepartments')->result_array();
        $setores = array_map(function ($r) { return (int) $r['departmentid']; }, $dep_rows);
        $setor = function ($i) use ($setores) { return $setores ? $setores[$i % count($setores)] : null; };

        $defs = [
            [
                'codigo' => 'AUD-EX-ISO9001', 'titulo' => 'Auditoria de Certificação ISO 9001:2015',
                'descricao' => 'Auditoria de manutenção do SGQ.', 'tipo' => 'certificacao',
                'escopo' => 'Processos de atendimento, faturamento e gestão de documentos.',
                'norma' => 'ISO 9001:2015', 'status' => 'encerrada', 'resultado' => 'parcial',
                'dt_planejada' => '2026-04-15', 'dt_realizada' => '2026-04-18', 'duracao' => 16,
                'setor_i' => 0, 'n_aud' => 4,
                'achados' => [
                    ['tipo' => 'nc_menor', 'descricao' => 'Registros de calibração de equipamentos sem evidência de periodicidade definida.', 'evidencia' => 'Planilha de calibração desatualizada (último registro 2025).', 'requisito' => 'ISO 9001 7.1.5', 'status' => 'em_tratamento', 'prazo' => '2026-06-30'],
                    ['tipo' => 'observacao', 'descricao' => 'Oportunidade de padronizar a identificação visual de documentos obsoletos.', 'evidencia' => 'Documentos sem carimbo de obsoleto no setor X.', 'requisito' => 'ISO 9001 7.5.3', 'status' => 'aberto', 'prazo' => '2026-07-15'],
                    ['tipo' => 'oportunidade', 'descricao' => 'Automatizar o controle de indicadores via painel digital.', 'evidencia' => 'Controle manual em planilha.', 'requisito' => '—', 'status' => 'aberto', 'prazo' => null],
                ],
            ],
            [
                'codigo' => 'AUD-EX-FAT', 'titulo' => 'Auditoria Interna de Faturamento (TISS)',
                'descricao' => 'Conformidade do faturamento com o padrão TISS.', 'tipo' => 'interna',
                'escopo' => 'Guias, lançamentos e prevenção de glosas.',
                'norma' => 'Padrão TISS / RN ANS', 'status' => 'encerrada', 'resultado' => 'nao_conforme',
                'dt_planejada' => '2026-05-05', 'dt_realizada' => '2026-05-07', 'duracao' => 8,
                'setor_i' => 1, 'n_aud' => 3,
                'achados' => [
                    ['tipo' => 'nc_maior', 'descricao' => 'Guias faturadas sem autorização prévia registrada em 12% da amostra.', 'evidencia' => 'Amostra de 50 guias, 6 sem autorização.', 'requisito' => 'RN ANS / TISS', 'status' => 'em_tratamento', 'prazo' => '2026-06-20'],
                    ['tipo' => 'nc_menor', 'descricao' => 'Divergência entre código TUSS lançado e procedimento descrito.', 'evidencia' => 'Guias #4521 e #4533.', 'requisito' => 'Tabela TUSS', 'status' => 'aberto', 'prazo' => '2026-06-25'],
                ],
            ],
            [
                'codigo' => 'AUD-EX-LGPD', 'titulo' => 'Auditoria de Segurança da Informação (LGPD)',
                'descricao' => 'Verificação de controles de proteção de dados.', 'tipo' => 'interna',
                'escopo' => 'Acesso a dados sensíveis de beneficiários.',
                'norma' => 'LGPD / ISO 27001', 'status' => 'em_execucao', 'resultado' => 'pendente',
                'dt_planejada' => '2026-06-08', 'dt_realizada' => null, 'duracao' => 12,
                'setor_i' => 2, 'n_aud' => 3,
                'achados' => [
                    ['tipo' => 'observacao', 'descricao' => 'Contas de usuários inativos ainda com acesso ao sistema.', 'evidencia' => '3 contas de ex-colaboradores ativas.', 'requisito' => 'LGPD Art. 46', 'status' => 'aberto', 'prazo' => '2026-06-30'],
                ],
            ],
            [
                'codigo' => 'AUD-EX-ANS', 'titulo' => 'Auditoria Externa ANS',
                'descricao' => 'Fiscalização regulatória da operadora.', 'tipo' => 'externa',
                'escopo' => 'Indicadores assistenciais e prazos de atendimento.',
                'norma' => 'RN ANS', 'status' => 'planejada', 'resultado' => 'pendente',
                'dt_planejada' => '2026-08-12', 'dt_realizada' => null, 'duracao' => null,
                'setor_i' => 3, 'n_aud' => 2, 'achados' => [],
            ],
            [
                'codigo' => 'AUD-EX-FUP', 'titulo' => 'Follow-up de Não Conformidades',
                'descricao' => 'Verificação de eficácia das ações corretivas.', 'tipo' => 'follow_up',
                'escopo' => 'Achados pendentes de auditorias anteriores.',
                'norma' => 'ISO 9001:2015', 'status' => 'encerrada', 'resultado' => 'conforme',
                'dt_planejada' => '2026-05-28', 'dt_realizada' => '2026-05-28', 'duracao' => 4,
                'setor_i' => 0, 'n_aud' => 2,
                'achados' => [
                    ['tipo' => 'conformidade', 'descricao' => 'Ação corretiva da NC de calibração implementada e eficaz.', 'evidencia' => 'Plano de calibração atualizado e em uso.', 'requisito' => 'ISO 9001 10.2', 'status' => 'fechado', 'prazo' => null],
                ],
            ],
            [
                'codigo' => 'AUD-EX-NR', 'titulo' => 'Auditoria de Segurança do Trabalho (NRs)',
                'descricao' => 'Conformidade com normas regulamentadoras.', 'tipo' => 'interna',
                'escopo' => 'NR-5, NR-6, NR-23.',
                'norma' => 'NRs MTE', 'status' => 'cancelada', 'resultado' => 'pendente',
                'dt_planejada' => '2026-03-20', 'dt_realizada' => null, 'duracao' => null,
                'setor_i' => 4, 'n_aud' => 0, 'achados' => [],
            ],
        ];

        $empresa_id = (int) $this->session->userdata('empresa_id');
        $criadas = 0; $puladas = 0; $auditados = 0; $achados = 0;

        foreach ($defs as $d) {
            $existe = $this->db->where('codigo', $d['codigo'])
                ->where('empresa_id', $empresa_id)->where('deleted', 0)
                ->get('tbl_auditorias')->row();
            if ($existe) { $puladas++; continue; }

            $aud_id = $this->Auditoria_model->save([
                'codigo'            => $d['codigo'],
                'titulo'            => $d['titulo'],
                'descricao'         => $d['descricao'],
                'tipo'              => $d['tipo'],
                'escopo'            => $d['escopo'],
                'norma_referencia'  => $d['norma'],
                'dt_planejada'      => $d['dt_planejada'],
                'dt_realizada'      => $d['dt_realizada'],
                'duracao_horas'     => $d['duracao'],
                'auditor_lider_id'  => $lider,
                'setor_auditado_id' => $setor($d['setor_i']),
                'status'            => $d['status'],
                'resultado'         => $d['resultado'],
            ]);
            $criadas++;

            $aud_part = array_slice($staff_ids, 0, (int) $d['n_aud']);
            if ($aud_part) {
                $this->Auditoria_model->set_auditados($aud_id, $aud_part);
                $auditados += count($aud_part);
            }

            foreach ($d['achados'] as $ac) {
                $this->Auditoria_model->save_achado([
                    'auditoria_id'     => $aud_id,
                    'tipo'             => $ac['tipo'],
                    'descricao'        => $ac['descricao'],
                    'evidencia'        => $ac['evidencia'],
                    'requisito'        => $ac['requisito'],
                    'status'           => $ac['status'],
                    'responsavel_id'   => $resp,
                    'prazo_tratamento' => $ac['prazo'],
                ]);
                $achados++;
            }
        }

        echo '<div style="font-family:sans-serif;max-width:560px;margin:40px auto;">';
        echo '<h3>Pronto!</h3>';
        echo "<p>Auditorias criadas: <b>$criadas</b> &middot; já existiam (puladas): <b>$puladas</b> &middot; auditados: <b>$auditados</b> &middot; achados: <b>$achados</b>.</p>";
        echo '<p><a href="' . base_url('gestao_corporativa/Auditoria') . '" style="background:#16a085;color:#fff;padding:8px 14px;border-radius:6px;text-decoration:none;">Ver auditorias</a></p>';
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
