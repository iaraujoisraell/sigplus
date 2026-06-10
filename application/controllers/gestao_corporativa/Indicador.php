<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Indicador extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        if (!is_logged_in()) redirect(base_url('admin/authentication'));
        $this->load->model('Indicador_model');
        $this->load->model('Staff_model');
        $this->load->model('projects_model');
        $this->load->model('Projeto_fase_model');
        $this->load->model('Departments_model');
    }

    public function index()
    {
        $filtros = [
            'setor_id'        => $this->input->get('setor_id'),
            'project_id'      => $this->input->get('project_id'),
            'fase_id'         => $this->input->get('fase_id'),
            'responsavel_id'  => $this->input->get('responsavel_id'),
            'status'          => $this->input->get('status'),
            'meu'             => $this->input->get('meu'),
            'criei'           => $this->input->get('criei'),
            'responsavel_meu' => $this->input->get('responsavel_meu'),
            'busca'           => $this->input->get('busca'),
        ];
        $data = [
            'title'        => 'Indicadores',
            'indicadores'  => $this->Indicador_model->listar($filtros),
            'filtros'      => $filtros,
            'projects'     => $this->_get_projects(),
            'departments'  => $this->Departments_model->get(),
            'staffs'       => $this->Staff_model->get(),
        ];
        $this->load->view('gestao_corporativa/indicadores/list', $data);
    }

    public function add()
    {
        $data = [
            'title'        => 'Novo indicador',
            'indicador'    => null,
            'projects'     => $this->_get_projects(),
            'departments'  => $this->Departments_model->get(),
            'staffs'       => $this->Staff_model->get(),
        ];
        $this->load->view('gestao_corporativa/indicadores/form', $data);
    }

    public function edit($id)
    {
        $ind = $this->Indicador_model->get($id);
        if (!$ind) show_404();
        if (!$this->Indicador_model->pode_editar($ind)) access_denied('Indicadores');

        $data = [
            'title'        => 'Editar indicador',
            'indicador'    => $ind,
            'projects'     => $this->_get_projects(),
            'departments'  => $this->Departments_model->get(),
            'staffs'       => $this->Staff_model->get(),
        ];
        $this->load->view('gestao_corporativa/indicadores/form', $data);
    }

    public function save()
    {
        $id = (int) $this->input->post('id');
        if ($id) {
            $ind = $this->Indicador_model->get($id);
            if (!$ind || !$this->Indicador_model->pode_editar($ind)) access_denied('Indicadores');
        }
        $new_id = $this->Indicador_model->save($this->input->post(), $id ?: null);
        set_alert('success', 'Indicador salvo.');
        redirect('gestao_corporativa/Indicador/view/' . $new_id);
    }

    public function view($id)
    {
        $ind = $this->Indicador_model->get($id);
        if (!$ind) show_404();
        $data = [
            'title'       => $ind['nome'],
            'indicador'   => $ind,
            'medicoes'    => $this->Indicador_model->get_medicoes($id),
            'pode_editar' => $this->Indicador_model->pode_editar($ind),
            'periodo_atual'=> $this->Indicador_model->periodo_atual($ind['periodicidade']),
        ];
        $this->load->view('gestao_corporativa/indicadores/view', $data);
    }

    public function delete($id)
    {
        $ind = $this->Indicador_model->get($id);
        if (!$ind) show_404();
        if (!$this->Indicador_model->pode_editar($ind)) access_denied('Indicadores');
        $this->Indicador_model->delete($id);
        set_alert('success', 'Indicador excluído.');
        redirect('gestao_corporativa/Indicador');
    }

    public function medicao_save()
    {
        $indicador_id = (int) $this->input->post('indicador_id');
        $ind = $this->Indicador_model->get($indicador_id);
        if (!$ind) { echo json_encode(['ok' => false]); return; }
        if (!$this->Indicador_model->pode_editar($ind)) { echo json_encode(['ok' => false]); return; }

        $ok = $this->Indicador_model->add_medicao([
            'indicador_id'       => $indicador_id,
            'periodo_referencia' => $this->input->post('periodo_referencia'),
            'valor'              => $this->input->post('valor'),
            'observacao'         => $this->input->post('observacao'),
        ]);
        if ($this->input->is_ajax_request()) {
            echo json_encode(['ok' => (bool) $ok]);
            return;
        }
        set_alert('success', 'Medição registrada.');
        redirect('gestao_corporativa/Indicador/view/' . $indicador_id);
    }

    public function medicao_delete($medicao_id)
    {
        $row = $this->db->where('id', (int) $medicao_id)
            ->get('tbl_indicadores_medicoes')->row();
        if (!$row) show_404();
        $ind = $this->Indicador_model->get($row->indicador_id);
        if (!$ind || !$this->Indicador_model->pode_editar($ind)) access_denied('Indicadores');
        $this->Indicador_model->delete_medicao($medicao_id);
        redirect('gestao_corporativa/Indicador/view/' . (int) $row->indicador_id);
    }

    /**
     * Seed de indicadores de exemplo pra demonstrar a tela.
     * Acesso: somente admin, e exige ?confirmar=1.
     * Idempotente: nao duplica (checa pelo codigo) e as medicoes sao
     * atualizadas por periodo. Pode rodar quantas vezes quiser.
     * REMOVER apos a demo.
     */
    public function exemplos()
    {
        if (!is_admin()) access_denied('Indicadores');

        if ($this->input->get('confirmar') != '1') {
            echo '<div style="font-family:sans-serif;max-width:560px;margin:40px auto;">';
            echo '<h3>Seed de indicadores de exemplo</h3>';
            echo '<p>Vai cadastrar <b>6 indicadores</b> de demonstração com <b>6 meses</b> de medições cada (jan→jun/2026), usando a empresa/usuário da sua sessão. É idempotente — não duplica se rodar de novo.</p>';
            echo '<p><a href="' . base_url('gestao_corporativa/Indicador/exemplos?confirmar=1') . '" style="background:#16a085;color:#fff;padding:8px 14px;border-radius:6px;text-decoration:none;">Confirmar e criar exemplos</a></p>';
            echo '</div>';
            return;
        }

        // pega um setor qualquer pra vincular (ou deixa nulo)
        $dep = $this->db->select('departmentid')->order_by('departmentid', 'asc')
            ->get('tbldepartments')->row();
        $setor_id = $dep ? (int) $dep->departmentid : null;

        $meses = ['2026-01', '2026-02', '2026-03', '2026-04', '2026-05', '2026-06'];

        $defs = [
            [
                'codigo' => 'IND-EX-SIN', 'nome' => 'Sinistralidade',
                'descricao' => 'Relação entre despesas assistenciais e receita de contraprestações.',
                'unidade' => '%', 'casas' => 1, 'meta' => 75, 'meta_tipo' => 'menor_melhor', 'tol' => 3, 'cor' => '#dc2626',
                'serie' => [77.5, 76.2, 78.1, 74.3, 75.8, 79.0], // último: crítico
            ],
            [
                'codigo' => 'IND-EX-NPS', 'nome' => 'Satisfação do Cliente (NPS)',
                'descricao' => 'Índice de recomendação dos beneficiários.',
                'unidade' => 'pts', 'casas' => 0, 'meta' => 80, 'meta_tipo' => 'maior_melhor', 'tol' => 5, 'cor' => '#0a66c2',
                'serie' => [71, 74, 77, 80, 82, 83], // último: atingido
            ],
            [
                'codigo' => 'IND-EX-TMA', 'nome' => 'Tempo Médio de Atendimento',
                'descricao' => 'Tempo médio de espera/atendimento no call center (minutos).',
                'unidade' => 'min', 'casas' => 1, 'meta' => 8, 'meta_tipo' => 'menor_melhor', 'tol' => 2, 'cor' => '#f59e0b',
                'serie' => [11.0, 10.2, 9.3, 8.7, 8.1, 8.3], // último: atenção
            ],
            [
                'codigo' => 'IND-EX-FCR', 'nome' => 'Resolução no 1º Contato',
                'descricao' => 'Percentual de solicitações resolvidas já no primeiro contato.',
                'unidade' => '%', 'casas' => 1, 'meta' => 90, 'meta_tipo' => 'maior_melhor', 'tol' => 5, 'cor' => '#16a34a',
                'serie' => [83.0, 86.5, 88.0, 90.2, 91.5, 92.1], // último: atingido
            ],
            [
                'codigo' => 'IND-EX-ABS', 'nome' => 'Absenteísmo',
                'descricao' => 'Percentual de faltas/ausências da equipe no período.',
                'unidade' => '%', 'casas' => 2, 'meta' => 3, 'meta_tipo' => 'menor_melhor', 'tol' => 1, 'cor' => '#7c3aed',
                'serie' => [5.0, 4.4, 3.7, 3.1, 3.6, 4.2], // último: crítico
            ],
            [
                'codigo' => 'IND-EX-GLO', 'nome' => 'Glosa Hospitalar',
                'descricao' => 'Percentual de valores glosados sobre o faturado.',
                'unidade' => '%', 'casas' => 2, 'meta' => 5, 'meta_tipo' => 'menor_melhor', 'tol' => 1.5, 'cor' => '#059669',
                'serie' => [8.0, 7.0, 6.1, 5.4, 5.0, 4.9], // último: atingido
            ],
        ];

        $empresa_id = (int) $this->session->userdata('empresa_id');
        $criados = 0; $pulados = 0; $medicoes = 0;

        foreach ($defs as $d) {
            $existe = $this->db->where('codigo', $d['codigo'])
                ->where('empresa_id', $empresa_id)->where('deleted', 0)
                ->get('tbl_indicadores')->row();

            if ($existe) {
                $ind_id = (int) $existe->id;
                $pulados++;
            } else {
                $ind_id = $this->Indicador_model->save([
                    'codigo'         => $d['codigo'],
                    'nome'           => $d['nome'],
                    'descricao'      => $d['descricao'],
                    'unidade'        => $d['unidade'],
                    'casas_decimais' => $d['casas'],
                    'meta'           => $d['meta'],
                    'meta_tipo'      => $d['meta_tipo'],
                    'tolerancia'     => $d['tol'],
                    'periodicidade'  => 'mensal',
                    'setor_id'       => $setor_id,
                    'responsavel_id' => get_staff_user_id(),
                    'cor'            => $d['cor'],
                    'status'         => 'ativo',
                ]);
                $criados++;
            }

            foreach ($d['serie'] as $i => $valor) {
                $this->Indicador_model->add_medicao([
                    'indicador_id'       => $ind_id,
                    'periodo_referencia' => $meses[$i],
                    'valor'              => $valor,
                    'observacao'         => 'Medição de exemplo (seed)',
                ]);
                $medicoes++;
            }
        }

        echo '<div style="font-family:sans-serif;max-width:560px;margin:40px auto;">';
        echo '<h3>Pronto!</h3>';
        echo "<p>Indicadores criados: <b>$criados</b> &middot; já existiam (pulados): <b>$pulados</b> &middot; medições registradas/atualizadas: <b>$medicoes</b>.</p>";
        echo '<p><a href="' . base_url('gestao_corporativa/Indicador') . '" style="background:#16a085;color:#fff;padding:8px 14px;border-radius:6px;text-decoration:none;">Ver indicadores</a></p>';
        echo '</div>';
    }

    private function _get_projects()
    {
        $empresa_id = (int) $this->session->userdata('empresa_id');
        return $this->db->select('id, name')
            ->from('tblprojects')->where('empresa_id', $empresa_id)->where('deleted', 0)
            ->order_by('name', 'asc')->get()->result_array();
    }
}
