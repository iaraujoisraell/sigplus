<?php

defined('BASEPATH') or exit('No direct script access allowed');

use Dompdf\Options;
use Dompdf\Dompdf;

class Workflow extends AdminController
{

    public function __construct()
    {

        parent::__construct();

        if (!is_logged_in()) {
            access_denied('Workflow');
            redirect($_SERVER['HTTP_REFERER']);
        }

        $this->load->model('Workflow_model');
        $this->load->model('Company_model');
        $this->load->model('Categorias_campos_model');
        $this->load->model('Intranet_general_model');
    }

    /* List all staff roles */

    public function index()
    {
        //echo $_SESSION['wf_carteirinha']; exit;
        close_setup_menu();

        $data['title'] = 'Workflow';

        //$data['tipos'] = $this->Registro_ocorrencia_model->get_categorias();
        $data['bodyclass'] = 'tickets-page';
        add_admin_tickets_js_assets();
        $this->load->model('Staff_model');
        $data['categorias'] = $this->Categorias_campos_model->get_categorias('workflow');
        $data['staffs'] = $this->Staff_model->get();
        $this->load->model('departments_model');
        $data['departments'] = $this->departments_model->get_staff_departments(false);

        $data['statuses'] = $this->Workflow_model->get_status();
        //print_r($data['statuses']); exit;
        $data['default_tickets_list_statuses'] = hooks()->apply_filters('default_tickets_list_statuses', [1, 2]);

        $filters_ = $this->Intranet_general_model->get_filters('workflow');

        $filters = [];
        foreach ($filters_ as $filter) {
            $tab = $filter['tab'];

            // Add the "filter" and "value" pairs to the tab's array
            $filters[$filter['filter']] = $filter['value'];
        }
        $data['filters'] = $filters;

        $this->load->view('gestao_corporativa/workflow/list', $data);
    }

    /**
     * Consulta info do paciente na API Tasy. Pula em dev (rede privada
     * Unimed inacessível) e usa timeout curto em prod pra não travar.
     */
    private function _buscar_info_tasy($carteirinha)
    {
        if (defined('ENVIRONMENT') && ENVIRONMENT === 'development') {
            return null;
        }
        $carteirinha = (string) $carteirinha;
        if ($carteirinha === '') return null;

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL            => 'http://189.2.65.2/sigplus/api/Informacoes_sistema_tasy',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => 2,
            CURLOPT_TIMEOUT        => 5,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => 'POST',
            CURLOPT_POSTFIELDS     => json_encode(['carteirinha' => $carteirinha]),
            CURLOPT_HTTPHEADER     => ['Content-Type: application/json; charset=utf-8'],
        ]);
        $response = curl_exec($ch);
        $err = curl_errno($ch);
        curl_close($ch);
        if ($err || !$response) return null;
        $info = json_decode($response, true);
        return is_array($info) ? $info : null;
    }

    public function workflow($id)
    {
        if (!$id) {
            redirect(admin_url('workflow/add'));
        }

        $this->Intranet_general_model->add_log(["rel_type" => "workflow", "controller" => "Workflow", "function" => "workflow", "action" => "Workflow ($id)", "rel_id" => $id]);
        if (!get_staff_user_id()) {
            access_denied('Workflow');
            redirect('admin/authentication/admin_unimed');
        }

        $workflow = $this->Workflow_model->get_workflow_by_id($id);
        if ($workflow->user_created == get_staff_user_id()) {
            $data['user_created'] = true;
            //exit;
        }
        $data['title'] = 'Workflow #' . $id;

        $this->load->model('Departments_model');
        $departments = $this->Departments_model->get_staff_departments(get_staff_user_id(), true);

        $fluxo_atual = $this->Workflow_model->get_fluxos_andamento_atual($id, '', 'desc');

        $fluxos = $this->Workflow_model->get_fluxos_andamento($id);



        foreach ($fluxos as $fluxo) {
            if (in_array($fluxo['department_id'], $departments)) {
                $data['in_department'] = true;
            }
        }

        //  var_dump($data['in_department']); exit;

        if ($fluxo_atual) {
            if (in_array($fluxo_atual->department_id, $departments)) {
                if ($fluxo_atual->atribuido_a == 0) {
                    $this->assumir($fluxo_atual->id, $id);
                }
            }
        }

        if ($workflow->status == 2) {
            $data['fluxo_atual'] = $fluxo_atual;
            //print_r($data['fluxo_atual']); exit;
            if ($data['fluxo_atual']->atribuido_a == get_staff_user_id()) {
                $data['atual'] = true;
                $data['alternativas'] = $this->Workflow_model->get_fluxos_seguintes($data['fluxo_atual']->fluxo_id);
            }
        }

        // print_r($workflow);exit;

        $data['workflow'] = $workflow;
        $data['categoria'] = $this->Categorias_campos_model->get_categoria($workflow->categoria_id);

        if (!$workflow->date_prazo || $workflow->date_prazo == '0000-00-00') {
            if ($data['categoria']->prazo) {
                $edit_prazo['date_prazo'] = date('Y-m-d', strtotime("+" . $data['categoria']->prazo . " days", strtotime($workflow->date_created)));
            }
            $this->db->where('id', $id);
            $this->db->update('tbl_intranet_workflow', $edit_prazo);
        }
        if (!$workflow->date_prazo_client || $workflow->date_prazo_client == '0000-00-00') {
            if ($data['categoria']->prazo_cliente) {
                $edit_prazo['date_prazo_client'] = date('Y-m-d', strtotime("+" . $data['categoria']->prazo_cliente . " days", strtotime($workflow->date_created)));
            }
            $this->db->where('id', $id);

            $this->db->update('tbl_intranet_workflow', $edit_prazo);
        }

        if ($data['categoria']->responsavel) {

            if (in_array($data['categoria']->responsavel, $departments)) {
                $data['department_responsable'] = true;
            }
        }
        if ($data['categoria']->staff_responsavel) {

            if ($data['categoria']->staff_responsavel == get_staff_user_id()) {
                $data['staff_responsable'] = true;
            }
        }


        if ($workflow->registro_atendimento_id) {
            $this->load->model('Atendimentos_model');
            $atentimento = $this->Atendimentos_model->get_ra_by_id($workflow->registro_atendimento_id);
            $data['atendimento'] = $atentimento;
            //print_r($atentimento); exit;
            if ($atentimento->client_id) {

                $this->load->model('Clients_model');
                $client = $this->Clients_model->get($atentimento->client_id);
                $data['info_client'] = $this->_buscar_info_tasy($client->numero_carteirinha ?? '');
            }
        }

        $data['client_contacts'] = $this->Workflow_model->get_client_contacts($workflow->id);
        $data['pdf_views'] = $this->Company_model->get_pdf_view('workflow', $workflow->id);
        $data['internal_requests'] = $this->Workflow_model->get_resquest_internal($workflow->id);
        foreach ($data['internal_requests'] as $request) {
            if (!$request['staffid']) {
                $this->db->where('id', $request['id']);
                $this->db->delete(db_prefix() . '_intranet_workflow_internal_request');
            }
        }
        $data['internal_requests'] = $this->Workflow_model->get_resquest_internal($workflow->id);
        $data['external_requests'] = $this->Workflow_model->get_resquest_external($workflow->id);

        $data['departments'] = $this->Departments_model->get();

        // defaults pra evitar undefined na view legada
        $data = array_merge([
            'in_department'         => false,
            'user_created'          => false,
            'atual'                 => false,
            'correcao'              => false,
            'alternativas'          => [],
            'department_responsable'=> false,
            'staff_responsable'     => false,
            'info_client'           => null,
            'fluxo_atual'           => null,
            'atendimento'           => null,
            'client_contacts'       => [],
            'pdf_views'             => [],
            'internal_requests'     => [],
            'external_requests'     => [],
        ], $data);

        $this->load->view('gestao_corporativa/workflow/single', $data);
    }

    public function salvar_tags()
    {
        if (!$this->input->is_ajax_request()) {
            echo json_encode([
                'success' => false,
                'message' => 'Requisição inválida.'
            ]);
            exit;
        }

        $workflow_id = (int) $this->input->post('workflow_id');
        $tags        = $this->input->post('tags');

        if ($workflow_id <= 0) {
            echo json_encode([
                'success' => false,
                'message' => 'Workflow inválido.'
            ]);
            exit;
        }

     
        handle_tags_save($tags, $workflow_id, 'workflow');

        echo json_encode([
            'success' => true,
            'message' => 'Tags salvas com sucesso.'
        ]);
        exit;
    }

    public function get_subcategorias_financeiro()
    {
        $setor_id = $this->input->post('setor_id');

        $sql = "SELECT * from tbl_intranet_categorias_fluxo where setor = $setor_id and deleted = 0 order by codigo_sequencial ";  //echo "$sql"; exit;

        $result = $this->db->query($sql)->result_array();


      //  print_r($result); exit;
                // opcional: validar setor
       /* if ($setor_id != 3) {
            echo json_encode([]);
            return;
        }*/

        // dados fictícios – substitua com o banco
        /*$dados = [
            ["id" => 1, "nome" => "Contas a Pagar"],
            ["id" => 2, "nome" => "Reembolso"],
            ["id" => 3, "nome" => "Cobrança"],
            ["id" => 4, "nome" => "Análise Financeira"],
        ]; */

        echo json_encode($result);
    }

    public function salvar_transferencia(){
        $dados = $this->input->post();
       // print_r($dados); exit;
        $id_fluxo = $dados['workflow_id'];

        $sql = "SELECT * from tbl_intranet_workflow_fluxo_andamento where workflow_id = $id_fluxo and deleted = 0 order by fluxo_sequencia desc"; 
        $ultimo_passo = $this->db->query($sql)->row_array();

        $id_ultimo = $ultimo_passo['id'];

        $dados_old['concluido'] = 1;
        $dados_old['data_concluido'] = date('Y-m-d H:i:s'); //print_r($dados_old); exit;

        $this->db->where('id', $id_ultimo);
        if($this->db->update('tbl_intranet_workflow_fluxo_andamento', $dados_old)){

            $dados_novo['workflow_id'] = $id_fluxo;
            $dados_novo['department_id'] = $dados['setor_destino'];
            $dados_novo['fluxo_id'] = $dados['subsetor'];
            $dados_novo['status'] = 1;
            $dados_novo['date_created'] = Date('Y-m-d H:i:s');
            $dados_novo['fluxo_sequencia'] = $ultimo_passo['fluxo_sequencia'] + 1;
            $dados_novo['categoria_id'] = $ultimo_passo['categoria_id'];
            $dados_novo['empresa_id'] = 4;
            $dados_novo['data_assumido'] = Date('Y-m-d H:i:s'); //print_r($dados_novo); exit;

            if($this->db->insert('tbl_intranet_workflow_fluxo_andamento', $dados_novo)){
                echo json_encode(array("status"=>"ok"));
            }
        }
         
    
    }


    public function assumir($fluxo_andamento_id, $workflow_id)
    {
        $dados['atribuido_a'] = get_staff_user_id();
        $dados['data_assumido'] = date('Y-m-d H:i:s');
        $dados['status'] = 1;
        $this->Intranet_general_model->add_log(["rel_type" => "workflow", "controller" => "Workflow", "function" => "assumir", "action" => "Workflow Assumimdo ($workflow_id)", "rel_id" => $workflow_id]);

        $this->db->where('id', $fluxo_andamento_id);

        if ($this->db->update('tbl_intranet_workflow_fluxo_andamento', $dados)) {
            $dados = [];
            $dados['user_start'] = get_staff_user_id();
            $dados['date_start'] = date('Y-m-d H:i:s');
            $dados['status'] = 2;

            $this->db->where('id', $workflow_id);
            if ($this->db->update('tbl_intranet_workflow', $dados)) {
                redirect(base_url() . 'gestao_corporativa/workflow/workflow/' . $workflow_id);
            }
        }
    }

    /**
     * 19/12/2022
     * @WannaLuiza
     * adiciona arquivo
     */
    public function add($registro_atendimento_id = '')
    {


        if ($this->input->post()) {
            // $info_ =$this->input->post();
            // print_r($info_);exit;
            //  echo "aqui"; exit;
            $categoria = $this->Categorias_campos_model->get_categoria($this->input->post('categoria_id'));
            //print_r($categoria); exit;
            $campos = $this->Categorias_campos_model->get_categoria_campos($this->input->post('categoria_id'));
            //print_r($this->input->post());
            //echo '<br>';
            //print_r($campos);
            //exit;
            $info = [
                "categoria_id" => $this->input->post('categoria_id'),
                "date_created" => date('Y-m-d H:i:s'),
                "user_created" => get_staff_user_id(),
                "status" => '1',
                "registro_atendimento_id" => $this->input->post('registro_atendimento_id'),
            ];
            if ($categoria->aprovacao == 1) {
                $info['aguardando_aprovacao'] = 1;
            } else {
                $info['date_start'] = date('Y-m-d H:i:s');
                $info['date_prazo'] = date('Y-m-d', strtotime("+" . $categoria->prazo . " days", strtotime(date('Y-m-d H:i:s'))));
                $info['date_prazo_client'] = date('Y-m-d', strtotime("+" . $categoria->prazo_cliente . " days", strtotime(date('Y-m-d H:i:s'))));
            }

            // print_r($info); exit;

            $id = $this->Workflow_model->add($info);

            //print_r($campos_sms); exit;


            if ($categoria->aprovacao == 1 and $this->input->post('aprovador')) {

                $departamentos = $this->Workflow_model->get_departamentos();

                $info_aprovacao = [
                    "rel_type" => 'workflow',
                    "rel_id" => $id,
                    "date_created" => date('Y-m-d H:i:s'),
                    "user_created" => get_staff_user_id(),
                    "departmentid_staffid" => $this->input->post('aprovador'),
                    "empresa_id" => $this->session->userdata('empresa_id')
                ];
                $this->db->insert("tbl_intranet_approbation", $info_aprovacao);
            } else {
                $fluxo = $this->Workflow_model->get_categoria_fluxo($this->input->post('categoria_id'));
                $fluxo_inicial = array(
                    "categoria_id" => $this->input->post('categoria_id'),
                    "fluxo_id" => $fluxo->id,
                    "workflow_id" => $id,
                    "fluxo_sequencia" => '1',
                    "department_id" => $fluxo->setor,
                    "date_created" => date('Y-m-d H:i:s'),
                    "date_received" => date('Y-m-d H:i:s'),
                    "user_created" => get_staff_user_id(),
                    "empresa_id" => $this->session->userdata('empresa_id'),
                    "data_prazo" => date('Y-m-d', strtotime("+" . $fluxo->prazo . " days", strtotime(date('Y-m-d H:i:s')))),
                );
                $this->Workflow_model->add_workflow_fluxo($fluxo_inicial);
            }


            foreach ($campos as $campo) {

                $campos_value = array(
                    "categoria_id" => $this->input->post('categoria_id'),
                    "rel_id" => $id,
                    "campo_id" => $campo['id'],
                    "data_cadastro" => date('Y-m-d H:i:s'),
                    "user_cadastro" => get_staff_user_id(),
                    "empresa_id" => $this->session->userdata('empresa_id')
                );

                $value = $this->input->post('campo_' . $campo['name']);

                if ($value) {
                    if (is_array($value)) {
                        $value = implode(', ', $value);
                    }
                }

                $campos_value['value'] = $value;
                $this->Workflow_model->add_campo_value($campos_value);
            }
            if ($id) {
                set_alert('success', _l('Salvo com Sucesso!', $id));
                if ($this->input->post('registro_atendimento_id') != '') {
                    redirect(base_url('gestao_corporativa/Atendimento/view/' . $this->input->post('registro_atendimento_id')));
                } else {
                    redirect(base_url('gestao_corporativa/workflow'));
                }
            }
        }
        $this->load->model('staff_model');
        $data['staffs'] = $this->staff_model->get();

        if ($registro_atendimento_id != '') {
            $data['categorias'] = $this->Categorias_campos_model->get_categorias_with_ra('workflow');
        } else {
            $data['categorias'] = $this->Categorias_campos_model->get_categorias_without_ra('workflow');
        }
        $data['bodyclass'] = 'ticket';
        $data['title'] = 'Novo Workflow';
        $this->load->model('Intranet_model');
        $departments_staffs = $this->Intranet_model->get_departamentos_staffs_selecionados();
        $data['departments_staffs'] = $departments_staffs;
        $data['registro_atendimento_id'] = $registro_atendimento_id;

        add_admin_tickets_js_assets();
        $this->load->view('gestao_corporativa/workflow/add', $data);
    }

    public function fluxos()
    {
        close_setup_menu();
        $data['title'] = 'Fluxos';
        $categoria_id = $_GET['id'];
        $data['tipo'] = $this->Categorias_campos_model->get_categoria($categoria_id);
        $this->load->model('departments_model');
        $data['departments'] = $this->departments_model->get();

        $this->load->view('gestao_corporativa/workflow/fluxos', $data);
    }

    public function tree_data($categoria_id)
    {
        $empresa_id = $this->session->userdata('empresa_id');
        $categoria_id = (int) $categoria_id;

        $sql = "SELECT f.id, f.vindo_de, f.codigo_sequencial, f.setor, f.prazo,
                       f.objetivo, f.contato_cliente, f.finaliza_cliente,
                       d.name AS setor_name
                FROM tbl_intranet_categorias_fluxo f
                LEFT JOIN tbldepartments d ON d.departmentid = f.setor
                WHERE f.categoria_id = ? AND f.empresa_id = ? AND f.deleted = 0
                ORDER BY f.codigo_sequencial";
        $rows = $this->db->query($sql, [$categoria_id, $empresa_id])->result_array();

        $children_count = [];
        foreach ($rows as $r) {
            $pid = $r['vindo_de'] ?: 0;
            $children_count[$pid] = ($children_count[$pid] ?? 0) + 1;
        }

        $nodes = [];
        foreach ($rows as $r) {
            $has_children = !empty($children_count[$r['id']]);
            $is_end = !$has_children;
            $badges = [];
            if ($r['contato_cliente']) $badges[] = '<span class="badge badge-info">Contato Cliente</span>';
            if ($r['finaliza_cliente']) $badges[] = '<span class="badge badge-success">Finaliza Cliente</span>';
            if ($is_end) $badges[] = '<span class="badge badge-danger">END</span>';

            $text = '<strong>' . htmlspecialchars($r['setor_name'] ?? '(sem setor)', ENT_QUOTES) . '</strong>'
                  . ' <span class="text-muted small">' . (int) $r['prazo'] . 'd</span>';
            if (!empty($r['objetivo'])) {
                $text .= ' &mdash; <span class="text-muted">' . htmlspecialchars(mb_substr($r['objetivo'], 0, 80), ENT_QUOTES) . '</span>';
            }
            if ($badges) $text .= ' ' . implode(' ', $badges);

            $nodes[] = [
                'id' => 'f' . $r['id'],
                'parent' => $r['vindo_de'] ? ('f' . $r['vindo_de']) : '#',
                'text' => $text,
                'data' => [
                    'id' => (int) $r['id'],
                    'codigo_sequencial' => $r['codigo_sequencial'],
                    'setor' => (int) $r['setor'],
                    'setor_name' => $r['setor_name'],
                    'prazo' => (int) $r['prazo'],
                    'objetivo' => $r['objetivo'],
                    'contato_cliente' => (int) $r['contato_cliente'],
                    'finaliza_cliente' => (int) $r['finaliza_cliente'],
                ],
                'state' => ['opened' => true],
            ];
        }

        header('Content-Type: application/json');
        echo json_encode($nodes);
    }

    public function node_save()
    {
        $empresa_id = $this->session->userdata('empresa_id');
        $id = (int) $this->input->post('id');
        $categoria_id = (int) $this->input->post('categoria_id');
        $parent_id = $this->input->post('parent_id') ? (int) $this->input->post('parent_id') : null;

        $info = [
            'setor' => (int) $this->input->post('setor') ?: null,
            'prazo' => (int) $this->input->post('prazo'),
            'objetivo' => $this->input->post('objetivo'),
            'contato_cliente' => $this->input->post('contato_cliente') ? 1 : 0,
            'finaliza_cliente' => $this->input->post('finaliza_cliente') ? 1 : 0,
            'data_ultima_alteracao' => date('Y-m-d'),
            'user_ultima_alteracao' => get_staff_user_id(),
        ];

        if ($id) {
            $this->db->where('id', $id);
            $this->db->where('empresa_id', $empresa_id);
            $this->db->update('tbl_intranet_categorias_fluxo', $info);
        } else {
            $info['categoria_id'] = $categoria_id;
            $info['empresa_id'] = $empresa_id;
            $info['vindo_de'] = $parent_id;
            $info['data_cadastro'] = date('Y-m-d');
            $info['user_cadastro'] = get_staff_user_id();
            $info['deleted'] = 0;
            $info['codigo_sequencial'] = $this->_compute_next_codigo($categoria_id, $parent_id, $empresa_id);
            $this->db->insert('tbl_intranet_categorias_fluxo', $info);
            $id = $this->db->insert_id();
        }

        header('Content-Type: application/json');
        echo json_encode(['ok' => true, 'id' => $id]);
    }

    public function node_delete()
    {
        $empresa_id = $this->session->userdata('empresa_id');
        $id = (int) $this->input->post('id');
        $categoria_id = (int) $this->input->post('categoria_id');

        $ids = $this->_collect_subtree_ids($id, $categoria_id, $empresa_id);
        if ($ids) {
            $this->db->where_in('id', $ids);
            $this->db->where('empresa_id', $empresa_id);
            $this->db->update('tbl_intranet_categorias_fluxo', ['deleted' => 1]);
        }

        header('Content-Type: application/json');
        echo json_encode(['ok' => true, 'deleted' => count($ids)]);
    }

    public function node_reorder()
    {
        $empresa_id = $this->session->userdata('empresa_id');
        $id = (int) $this->input->post('id');
        $categoria_id = (int) $this->input->post('categoria_id');
        $new_parent_id = $this->input->post('parent_id') ? (int) $this->input->post('parent_id') : null;
        $position = (int) $this->input->post('position');

        $sub_ids = $this->_collect_subtree_ids($id, $categoria_id, $empresa_id);
        if ($new_parent_id && in_array($new_parent_id, $sub_ids, true)) {
            header('Content-Type: application/json', true, 422);
            echo json_encode(['ok' => false, 'error' => 'Não é possível mover um nó para dentro dele mesmo.']);
            return;
        }

        $this->db->trans_start();

        $this->db->where('id', $id)->where('empresa_id', $empresa_id);
        $this->db->update('tbl_intranet_categorias_fluxo', ['vindo_de' => $new_parent_id]);

        $this->_reorder_siblings($categoria_id, $new_parent_id, $empresa_id, $id, $position);
        $this->_recompute_all_codigos($categoria_id, $empresa_id);

        $this->db->trans_complete();

        header('Content-Type: application/json');
        echo json_encode(['ok' => $this->db->trans_status()]);
    }

    private function _compute_next_codigo($categoria_id, $parent_id, $empresa_id)
    {
        if ($parent_id) {
            $parent = $this->db->select('codigo_sequencial')
                ->where('id', $parent_id)->where('empresa_id', $empresa_id)
                ->get('tbl_intranet_categorias_fluxo')->row();
            $base = $parent ? $parent->codigo_sequencial : '1';
        } else {
            $base = '';
        }

        $this->db->where('categoria_id', $categoria_id)
                 ->where('empresa_id', $empresa_id)
                 ->where('deleted', 0);
        if ($parent_id) {
            $this->db->where('vindo_de', $parent_id);
        } else {
            $this->db->where('vindo_de IS NULL', null, false);
        }
        $count = $this->db->count_all_results('tbl_intranet_categorias_fluxo');

        return $base === '' ? (string) ($count + 1) : $base . '.' . ($count + 1);
    }

    private function _collect_subtree_ids($id, $categoria_id, $empresa_id)
    {
        $ids = [$id];
        $queue = [$id];
        while ($queue) {
            $pid = array_shift($queue);
            $children = $this->db->select('id')
                ->where('vindo_de', $pid)
                ->where('categoria_id', $categoria_id)
                ->where('empresa_id', $empresa_id)
                ->where('deleted', 0)
                ->get('tbl_intranet_categorias_fluxo')->result_array();
            foreach ($children as $c) {
                $ids[] = (int) $c['id'];
                $queue[] = (int) $c['id'];
            }
        }
        return $ids;
    }

    private function _reorder_siblings($categoria_id, $parent_id, $empresa_id, $moved_id, $position)
    {
        $this->db->select('id, codigo_sequencial')
                 ->where('categoria_id', $categoria_id)
                 ->where('empresa_id', $empresa_id)
                 ->where('deleted', 0)
                 ->where('id !=', $moved_id);
        if ($parent_id) {
            $this->db->where('vindo_de', $parent_id);
        } else {
            $this->db->where('vindo_de IS NULL', null, false);
        }
        $this->db->order_by('codigo_sequencial', 'asc');
        $siblings = $this->db->get('tbl_intranet_categorias_fluxo')->result_array();

        $ordered_ids = array_column($siblings, 'id');
        $position = max(0, min($position, count($ordered_ids)));
        array_splice($ordered_ids, $position, 0, [$moved_id]);

        foreach ($ordered_ids as $idx => $sib_id) {
            $base = '';
            if ($parent_id) {
                $parent_row = $this->db->select('codigo_sequencial')
                    ->where('id', $parent_id)->get('tbl_intranet_categorias_fluxo')->row();
                $base = $parent_row->codigo_sequencial . '.';
            }
            $this->db->where('id', $sib_id)
                ->update('tbl_intranet_categorias_fluxo', ['codigo_sequencial' => $base . ($idx + 1)]);
        }
    }

    private function _recompute_all_codigos($categoria_id, $empresa_id)
    {
        $rows = $this->db->select('id, vindo_de, codigo_sequencial')
            ->where('categoria_id', $categoria_id)
            ->where('empresa_id', $empresa_id)
            ->where('deleted', 0)
            ->order_by('codigo_sequencial', 'asc')
            ->get('tbl_intranet_categorias_fluxo')->result_array();

        $by_parent = [];
        foreach ($rows as $r) {
            $pid = $r['vindo_de'] ?: 0;
            $by_parent[$pid][] = $r;
        }

        $assign = function ($parent_id, $base) use (&$assign, &$by_parent) {
            if (empty($by_parent[$parent_id])) return;
            foreach ($by_parent[$parent_id] as $idx => $r) {
                $code = $base === '' ? (string) ($idx + 1) : $base . '.' . ($idx + 1);
                if ($r['codigo_sequencial'] !== $code) {
                    $this->db->where('id', $r['id'])
                        ->update('tbl_intranet_categorias_fluxo', ['codigo_sequencial' => $code]);
                }
                $assign($r['id'], $code);
            }
        };
        $assign(0, '');
    }

    public function reports_setores()
    {
        // close_setup_menu();
        $data['title'] = 'Fluxos por Setores';

        $data['categorias'] = $this->Categorias_campos_model->get_categoria_workflow();
        //$data['staffs'] = $this->Staff_model->get();
        $this->load->model('departments_model');
        $data['departments'] = $this->departments_model->get();

        $this->load->view('gestao_corporativa/workflow/reports_setores', $data);
    }

    public function reports_setores_resumo()
    {
        // close_setup_menu();
        $data['title'] = 'Fluxos por Setores';

        $data['categorias'] = $this->Categorias_campos_model->get_categoria_workflow();
        //$data['staffs'] = $this->Staff_model->get();
        $this->load->model('departments_model');
        $data['departments'] = $this->departments_model->get();

        $this->load->view('gestao_corporativa/workflow/reports_setores_resumo', $data);
    }

    public function table_wf_setores_resumo()
    {

        $this->app->get_table_data_intranet('workflow_setores_resumo');
    }

    public function table_wf_setores()
    {

        $this->app->get_table_data_intranet('workflow_setores');
    }

    public function table()
    {

        $this->app->get_table_data_intranet('workflow');
    }

    public function table_fluxos()
    {

        $this->app->get_table_data_intranet('workflow_categoria_fluxos');
    }

    public function table_internal_request()
    {

        $this->app->get_table_data_intranet('internal_request');
    }

    /**
     * 22/11/2022
     * @WannaLuiza
     * Muda o espaÃ§o do lado em fluxos
     */
    public function mudar_space()
    {


        if ($this->input->post('slug') === 'question') {
            $data['fluxo'] = $this->Workflow_model->get_fluxo($this->input->post('id'));
            $this->load->view('gestao_corporativa/workflow/space/question', $data);
        } elseif ($this->input->post('slug') === 'campos') {
            $data['fluxo'] = $this->Workflow_model->get_fluxo($this->input->post('id'));
            $data['tipo'] = $this->Categorias_campos_model->get_categoria($data['fluxo']->categoria_id);
            $data['rel_type'] = 'workflow';
            $this->load->view('gestao_corporativa/categorias_campos/Campos', $data);
        } elseif ($this->input->post('slug') === 'edit') {
            $data['fluxo'] = $this->Workflow_model->get_fluxo($this->input->post('id'));
            $this->load->view('gestao_corporativa/workflow/space/edit', $data);
        }
    }

    /**
     * 19/12/2022
     * @WannaLuiza
     * Adiciona/Edita um fluxo. Vinculando a categoria
     */
    function save_fluxo_inicial($responsavel)
    {

        $info['empresa_id'] = $this->session->userdata('empresa_id');
        $info['data_cadastro'] = date('Y-m-d');
        $info['setor'] = $responsavel['id'];
        $info['codigo_sequencial'] = '1';
        $info['categoria_id'] = $responsavel['categoria_id'];
        $info['user_cadastro'] = get_staff_user_id();
        $info['data_ultima_alteracao'] = date('Y-m-d');
        $info['user_ultima_alteracao'] = get_staff_user_id();
        $this->db->insert("tbl_intranet_categorias_fluxo", $info);
        $id = $this->db->insert_id();
    }

    function edit_fluxo()
    {
        //$id = 89;
        //$empresa_id = $this->session->userdata('empresa_id');
        //$prazos_anteriores = $this->Workflow_model->get_prazo_corrido($id);
        //$possiveis_fluxos_seguintes = [];
        //$sql = "SELECT id, prazo FROM tbl_intranet_categorias_fluxo "
        //. "WHERE empresa_id = $empresa_id AND deleted = 0 AND vindo_de = $id";
        //$result = $this->db->query($sql)->result_array();
        //print_r($result); exit;
        //for($i = 0; $i < count($result); $i++){
        //$result[$i]['seguintes'] = $this->db->query("SELECT id, prazo FROM tbl_intranet_categorias_fluxo WHERE empresa_id = $empresa_id AND deleted = 0 AND vindo_de = $id")->result_array();
        //}
        //print_r($result); exit;
        //EXIT;
        //echo 'aqui'; exit;

        $id = $this->input->post('id');
        $info['setor'] = $this->input->post('setor');
        if ($this->input->post('prazo')) {
            $info['prazo'] = $this->input->post('prazo');
        }
        if ($this->input->post('alternativa')) {
            $info['alternativa'] = $this->input->post('alternativa');
        }
        if ($this->input->post('objetivo')) {
            $info['objetivo'] = $this->input->post('objetivo');
        }

        if ($this->input->post('contato_cliente')) {
            $info['contato_cliente'] = $this->input->post('contato_cliente');
        } elseif ($this->input->post('contato_cliente') == 0) {
            $info['contato_cliente'] = 0;
        }
        if ($this->input->post('template') == 1) {
            $info['template'] = 1;
        }

        if ($this->input->post('finaliza_cliente')) {
            $dados['finaliza_cliente'] = 0;
            $this->db->where('categoria_id', $this->input->post('categoria_id'));
            $this->db->like('codigo_sequencial', $this->input->post('codigo_sequencial'), 'after');
            $this->db->update("tbl_intranet_categorias_fluxo", $dados);
            $info['finaliza_cliente'] = $this->input->post('finaliza_cliente');
        } elseif ($this->input->post('finaliza_cliente') == 0) {
            $info['finaliza_cliente'] = 0;
        }
        $info['data_ultima_alteracao'] = date('Y-m-d');
        $info['user_ultima_alteracao'] = get_staff_user_id();
        //print_r($info); exit;
        $this->db->where('id', $id);
        $this->db->update("tbl_intranet_categorias_fluxo", $info);
    }

    /**
     * 19/12/2022
     * @WannaLuiza
     * Adiciona/Edita um fluxo. Vinculando a categoria
     */
    function add_fluxo_question($fluxo_id)
    {
        $data = $_POST;
        //print_r($data); exit;
        $fluxo_anterior = $this->Workflow_model->get_fluxo($fluxo_id);

        $categoria = $this->Categorias_campos_model->get_categoria($data['categoria_id']);

        $prazo_atual = $this->Workflow_model->prazo_atual($fluxo_id);

        if ($categoria->prazo <= $prazo_atual) {
            echo 'PRAZO EXCEDIDO';
            exit;
        }

        //echo 'aqui'; exit;
        if ($data['template']) {
            $info = $this->Categorias_campos_model->get_categoria_fluxo($categoria->id, $data['template'], true);

            unset($info['id']);
            unset($info['template']);
        } else {
            $info['data_cadastro'] = date('Y-m-d');
            $info['user_cadastro'] = get_staff_user_id();
        }
        //print_r($info); exit;
        $info['vindo_de'] = $fluxo_id;
        $info['codigo_sequencial'] = $this->Workflow_model->get_fluxo_sequencial($fluxo_anterior);
        $info['categoria_id'] = $data['categoria_id'];
        $info['setor'] = $data['setor'];
        $info['alternativa'] = $data['alternativa'];
        $info['prazo'] = $categoria->prazo - $prazo_atual;
        $info['empresa_id'] = $this->session->userdata('empresa_id');

        $info['data_ultima_alteracao'] = date('Y-m-d');
        $info['user_ultima_alteracao'] = get_staff_user_id();
        $this->db->insert("tbl_intranet_categorias_fluxo", $info);
        $id_fluxo_novo = $this->db->insert_id();

        if ($data['template']) {
            $this->db->where('deleted', 0);
            $this->db->where('preenchido_por', $data['template']);
            $campos = $this->db->get('tbl_intranet_categorias_campo')->result_array();
            foreach ($campos as $campo) {
                $campo_id_old = $campo['id'];
                unset($campo['id']);
                $campo['user_cadastro'] = get_staff_user_id();
                $campo['data_cadastro'] = date('Y-m-d H:i:s');
                $campo['user_ultima_alteracao'] = get_staff_user_id();
                $campo['data_ultima_alteracao'] = date('Y-m-d H:i:s');
                $campo['preenchido_por'] = $id_fluxo_novo;
                if ($this->db->insert('tbl_intranet_categorias_campo', $campo)) {
                    $campo_id_novo = $this->db->insert_id();
                    $this->db->where('campo_id', $campo_id_old);
                    $this->db->where('deleted', 0);
                    $options = $this->db->get('tbl_intranet_categorias_campo_options')->result_array();
                    foreach ($options as $option) {
                        $option_id_old = $option['id'];
                        unset($option['id']);
                        $option['campo_id'] = $campo_id_novo;
                        $this->db->insert('tbl_intranet_categorias_campo_options', $option);
                    }
                }
            }
        }
        $data['fluxo'] = $this->Categorias_campos_model->get_categoria_fluxo('', $id_fluxo_novo);

        $this->load->view('gestao_corporativa/workflow/space/alternativa', $data);
    }

    function add_fluxo_question_form($categoria_id)
    {
        $data['fluxo_id'] = $this->input->post('fluxo_id');

        $categoria = $this->Categorias_campos_model->get_categoria($categoria_id);

        $data['models'] = $this->Categorias_campos_model->get_categoria_fluxo($categoria_id, '', true);

        $prazo_atual = $this->Workflow_model->prazo_atual($this->input->post('fluxo_id'));

        if ($categoria->prazo <= $prazo_atual) {
            echo 'PRAZO EXCEDIDO';
            exit;
        }
        $data['categoria_id'] = $categoria->id;

        $this->load->view('gestao_corporativa/workflow/space/alternativa_form', $data);
    }

    /**
     * 03/12/2022
     * @WannaLuiza
     * Tira pontuaÃ§Ã£o
     */
    function tira_pontuacao_espaco_caractereespecial($string)
    {

        // matriz de entrada
        $what = array('Ã¤', 'Ã£', 'Ã ', 'Ã¡', 'Ã¢', 'Ãª', 'Ã«', 'Ã¨', 'Ã©', 'Ã¯', 'Ã¬', 'Ã­', 'Ã¶', 'Ãµ', 'Ã²', 'Ã³', 'Ã´', 'Ã¼', 'Ã¹', 'Ãº', 'Ã»', 'Ã€', 'Ã�', 'Ã‰', 'Ã�', 'Ã“', 'Ãš', 'Ã±', 'Ã‘', 'Ã§', 'Ã‡', ' ', '-', '(', ')', ',', ';', ':', '|', '!', '"', '#', '$', '%', '&', '/', '=', '?', '~', '^', '>', '<', 'Âª', 'Âº');

        // matriz de saÃ­da
        $by = array('a', 'a', 'a', 'a', 'a', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'A', 'A', 'E', 'I', 'O', 'U', 'n', 'n', 'c', 'C', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_');

        // devolver a string
        return str_replace($what, $by, $string);
    }

    public function delete_fluxo($id = '')
    {
        $redirect = true;
        if ($id == '') {
            $id = $this->input->post('id');
            $redirect = false;
        }
        $dados['deleted'] = 1;

        $this->db->where('id', $id);

        $this->db->update('tbl_intranet_categorias_fluxo', $dados);
        if ($redirect == true) {
            redirect(base_url() . 'gestao_corporativa/workflow/workflow/' . $_GET['workflow_id']);
        }
    }


    public function edit_question()
    {
        $id = $this->input->post('id');
        $question = $this->input->post('question');
        $dados['question'] = $question;

        $this->db->where('id', $id);

        $this->db->update('tbl_intranet_categorias_fluxo', $dados);
    }

    public function concluir_parte($id)
    {
        $dados['data_concluido'] = date('Y-m-d H:i:s');
        $dados['concluido'] = 1;
        if (!$this->input->post('alternativa')) {
            $dados['concluiu_fluxo'] = 1;
        }

        $this->db->where('id', $this->input->post('fluxo_andamento_id'));

        if ($this->db->update('tbl_intranet_workflow_fluxo_andamento', $dados)) {
            $campos = $this->Categorias_campos_model->get_categoria_campos($this->input->post('categoria_id'), $this->input->post('fluxo_id'));
            //print_r($campos); exit;
            foreach ($campos as $campo) {


                $campos_value = array(
                    "categoria_id" => $this->input->post('categoria_id'),
                    "rel_id" => $id,
                    "campo_id" => $campo['id'],
                    "data_cadastro" => date('Y-m-d H:i:s'),
                    "user_cadastro" => get_staff_user_id(),
                    "empresa_id" => $this->session->userdata('empresa_id')
                );

                if ($campo['type'] == 'file') {
                    $campo_name = 'campo_' . $campo['name']; // Substitua isso pelo nome real do seu campo de envio de arquivos

                    if (isset($_FILES[$campo_name]) && $_FILES[$campo_name]['error'] === UPLOAD_ERR_OK) {
                        $file = $_FILES[$campo_name];

                        // Extrai a extensão original do arquivo
                        $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);

                        // Gera um novo nome de arquivo único com a extensão original
                        $new_filename = 'WF' . $id . '_' . $campo['name'] . uniqid() . '.' . $file_extension;

                        // Verifica se o diretório de destino existe; se não existir, cria-o recursivamente
                        if (!file_exists("./assets/intranet/arquivos/workflow_arquivos/campo_file/")) {
                            mkdir("./assets/intranet/arquivos/workflow_arquivos/campo_file/", 0777, true);
                        }

                        // Define o caminho completo para o destino do arquivo enviado
                        $destination = "./assets/intranet/arquivos/workflow_arquivos/campo_file/" . $new_filename;

                        // Move o arquivo enviado para o destino com o novo nome de arquivo
                        if (move_uploaded_file($file["tmp_name"], $destination)) {
                            // Upload do arquivo bem-sucedido
                            $value = $new_filename;
                        } else {
                            // Erro ao mover o arquivo
                            $value = '';
                        }
                    } else {
                        // Erro durante o upload do arquivo
                        $value = '';
                    }
                } else {
                    $value = $this->input->post('campo_' . $campo['name']);
                }

                if ($value) {
                    if (is_array($value)) {
                        $value = implode(', ', $value);
                    }
                }

                $campos_value['value'] = $value;
                $this->Workflow_model->add_campo_value($campos_value);
            }

            if ($this->input->post('client_contact')) {
                $client_contact['workflow_id'] = $id;
                $client_contact['user_created'] = get_staff_user_id();
                $client_contact['date_created'] = date('Y-m-d H:i:s');
                $this->db->insert(db_prefix() . "_intranet_workflow_clientcontact", $client_contact);
            }

            if ($this->input->post('finaliza')) {
                $dados = [];
                $dados['date_end_client'] = date('Y-m-d H:i:s');
                $dados['user_end_client'] = get_staff_user_id();
                $workflow = $this->Workflow_model->get_workflow_by_id($id);
                if ($workflow->registro_atendimento_id) {
                    $this->load->model('Atendimentos_model');
                    $atendimento = $this->Atendimentos_model->get_ra_by_id($workflow->registro_atendimento_id);
                    //print_r($atendimento); exit;
                    $campos_sms = array(
                        "data_registro" => date('Y-m-d H:i:s'),
                        "usuario_registro" => get_staff_user_id(),
                        "phone_destino" => $atendimento->contato,
                        "assunto" => 'Solicitação #' . $id . ' Finalizada. Protocolo: ' . $atendimento->protocolo,
                        "mensagem" => 'Solicitação #' . $id . ' Finalizada. Protocolo: ' . $atendimento->protocolo,
                        "rel_type" => 'workflow',
                        "rel_id" => $id,
                        "client_id" => $atendimento->client_id,
                        "empresa_id" => $this->session->userdata('empresa_id')
                    );
                    //echo 'jsj'; exit;
                    $campos_email = array(
                        "data_registro" => date('Y-m-d H:i:s'),
                        "usuario_registro" => get_staff_user_id(),
                        "email_destino" => $atendimento->email,
                        "assunto" => 'Solicitação #' . $id . ' Finalizada. Protocolo: ' . $atendimento->protocolo,
                        "mensagem" => 'Solicitação #' . $id . ' Finalizada. Protocolo: ' . $atendimento->protocolo,
                        "rel_type" => 'workflow',
                        "rel_id" => $id,
                        "client_id" => $atendimento->client_id,
                        "empresa_id " => $this->session->userdata('empresa_id')
                    );

                    $this->load->model('Comunicacao_model');
                    $email = $this->Comunicacao_model->addEmail($campos_email);
                    $sms = $this->Comunicacao_model->addSms($campos_sms);
                }

                $this->db->where('id', $id);
                $this->db->update('tbl_intranet_workflow', $dados);
            }
            //exit;
            if ($this->input->post('alternativa')) {
                $fluxo = $this->Workflow_model->get_categoria_fluxo($this->input->post('categoria_id'), $this->input->post('alternativa'));
                // echo $this->input->post('fluxo_andamento_id'); exit;
                $fluxo_andamento_anterior = $this->Workflow_model->get_fluxo_andamento($this->input->post('fluxo_andamento_id'));
                $fluxo_seguinte = array(
                    "categoria_id" => $this->input->post('categoria_id'),
                    "fluxo_id" => $fluxo->id,
                    "workflow_id" => $id,
                    "fluxo_sequencia" => $fluxo_andamento_anterior->fluxo_sequencia + 1,
                    "department_id" => $fluxo->setor,
                    "date_created" => date('Y-m-d H:i:s'),
                    "user_created" => get_staff_user_id(),
                    "date_received" => date('Y-m-d H:i:s'),
                    "empresa_id" => $this->session->userdata('empresa_id'),
                    "data_prazo" => date('Y-m-d', strtotime("+" . $fluxo->prazo . " days", strtotime($fluxo_andamento_anterior->data_prazo))),
                );
                $this->Workflow_model->add_workflow_fluxo($fluxo_seguinte);
                //print_r($fluxo_andamento_anterior);
                if ($fluxo->setor == $fluxo_andamento_anterior->department_id) {

                    $same_department = true;
                }
            } else {

                $dados = [];
                $dados['date_end'] = date('Y-m-d H:i:s');
                $dados['user_end'] = get_staff_user_id();
                $dados['status'] = 3;

                $this->db->where('id', $id);
                $this->db->update('tbl_intranet_workflow', $dados);
            }

            if ($same_department == true) {
                redirect(base_url() . 'gestao_corporativa/workflow/workflow/' . $id);
            } else {
                redirect(base_url() . 'gestao_corporativa/workflow');
            }
        }
    }

    public function pdf($id)
    {

        $this->load->library('Pdf');
        $company_logo = get_option('company_logo');
        $data['company_name'] = get_option('companyname');

        $path = base_url() . 'uploads/company/' . $company_logo;
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data_ = file_get_contents($path);

        $data['base64'] = 'data:image/' . $type . ';base64,' . base64_encode($data_);

        if (!$id) {
            redirect(admin_url('workflow/add'));
        }

        $workflow = $this->Workflow_model->get_workflow_by_id($id);

        if ($workflow->user_created == get_staff_user_id()) {
            $data['user_created'] = true;
            //exit;
        }

        $this->load->model('Departments_model');
        $departments = $this->Departments_model->get_staff_departments(get_staff_user_id(), true);

        $fluxos = $this->Workflow_model->get_fluxos_andamento($id);

        for ($i = 0; $i < count($fluxos); $i++) {
            if (in_array($fluxos[$i]['department_id'], $departments)) {
                $data['in_department'] = true;
            }
            $fluxos[$i]['values'] = $this->Categorias_campos_model->get_values($workflow->id, 'workflow', $fluxos[$i]['fluxo_id']);
        }

        if (!$data['in_department'] && !$data['user_created']) {
            // echo 'NO PERMISSION'; QUANDO ERA PROÍBIDO PARA PESSOAS QUE NÃO FIZERAM PARTE DO FLUXO
            // exit;
        }

        $data['workflow'] = $workflow;
        if ($workflow->registro_atendimento_id) {
            $this->load->model('Atendimentos_model');
            $atentimento = $this->Atendimentos_model->get_ra_by_id($workflow->registro_atendimento_id);
            $data['atendimento'] = $atentimento;
            //print_r($atentimento); exit;
            if ($atentimento->client_id) {

                $this->load->model('Clients_model');
                $client = $this->Clients_model->get($atentimento->client_id);
                $data['info_client'] = $this->_buscar_info_tasy($client->numero_carteirinha ?? '');
            }
        }
        $data['fluxos'] = $fluxos;
        $data['campos'] = $this->Categorias_campos_model->get_values($workflow->id, 'workflow', '0');
        $data['categoria'] = $this->Categorias_campos_model->get_categoria($workflow->categoria_id);

        //

        if (!$workflow->date_prazo || $workflow->date_prazo == '0000-00-00') {
            if ($data['categoria']->prazo) {
                $edit_prazo['date_prazo'] = date('Y-m-d', strtotime("+" . $data['categoria']->prazo . " days", strtotime($workflow->date_created)));
            }
            $this->db->where('id', $id);
            $this->db->update('tbl_intranet_workflow', $edit_prazo);
        }
        if (!$workflow->date_prazo_client || $workflow->date_prazo_client == '0000-00-00') {
            if ($data['categoria']->prazo_cliente) {
                $edit_prazo['date_prazo_client'] = date('Y-m-d', strtotime("+" . $data['categoria']->prazo_cliente . " days", strtotime($workflow->date_created)));
            }
            $this->db->where('id', $id);

            $this->db->update('tbl_intranet_workflow', $edit_prazo);
        }

        $campos_internal = [];

        $internals = $this->Workflow_model->get_resquest_internal($workflow->id);
        $data['internals'] = $internals;

        $campos_external = [];

        $data['campos_external'] = $campos_external;

        $this->load->model('Comunicacao_model');
        $data['sms'] = $this->Comunicacao_model->get_sms('workflow', $id);
        $data['email'] = $this->Comunicacao_model->get_emails('workflow', $id);



        $html = $this->load->view('gestao_corporativa/workflow/pdf.php', $data, true);

        //CONTROLE DE VISUALIZAÇÕES DO PDF
        $this->load->model('Company_model');
        $this->Company_model->add_pdf_view('workflow', $id);

        $this->dompdf->loadHtml($html);
        $this->dompdf->setPaper('A4', 'landscape');
        $this->dompdf->render();

        $this->dompdf->stream('WF-' . $id . ".pdf", array("Attachment" => 0));
    }

    public function modal()
    {


        if ($this->input->post('slug') === 'cancel' || $this->input->post('slug') === 'finish') {
            $data['id'] = $this->input->post('id');
            $data['slug'] = $this->input->post('slug');
            $data['fluxo_andamento_id'] = $this->input->post('fluxo_andamento_id');
            //echo $data['fluxo_andamento_id']; exit;
            $this->load->view('gestao_corporativa/workflow/modal/cancel', $data);
        } elseif ($this->input->post('slug') === 'back') {
            $data['id'] = $this->input->post('id');
            $data['fluxo_andamento_id'] = $this->input->post('fluxo_andamento_id');
            $data['fluxo_andamento_id_old'] = $this->input->post('fluxo_andamento_id_old');

            $data['fluxo'] = $this->Workflow_model->get_fluxo_andamento($this->input->post('fluxo_andamento_id'));
            $data['fluxo_old'] = $this->Workflow_model->get_fluxo_andamento($this->input->post('fluxo_andamento_id_old'));

            //echo $data['fluxo_andamento_id']; exit;
            $this->load->view('gestao_corporativa/workflow/modal/back_progress', $data);
        } elseif ($this->input->post('slug') === 'internal_request') {
            $data['id'] = $this->input->post('id');

            $_data = [
                "workflow_id" => $data['id'],
            ];
            $data['request_id'] = $this->Workflow_model->add_resquest_internal($_data);
            $data['request'] = $this->Workflow_model->get_resquest_internal($data['id'], $data['request_id']);
            $this->load->model('Staff_model');
            $data['staffs'] = $this->Staff_model->get();
            $this->load->view('gestao_corporativa/workflow/modal/internal_request', $data);
        } elseif ($this->input->post('slug') === 'external_request') {
            $data['id'] = $this->input->post('id');
            $_data = [
                "workflow_id" => $data['id'],
            ];
            $data['request_id'] = $this->Workflow_model->add_resquest_external($_data);
            $data['request'] = $this->Workflow_model->get_resquest_external($data['id'], $data['request_id']);

            $this->load->view('gestao_corporativa/workflow/modal/external_request', $data);
        } elseif ($this->input->post('slug') === 'open_internal_request') {
            $data['request_id'] = $this->input->post('id');

            $data['request'] = $this->Workflow_model->get_resquest_internal($data['id'], $data['request_id']);

            $data['workflow'] = $this->Workflow_model->get_workflow_by_id($data['request']->workflow_id);

            $this->load->view('gestao_corporativa/workflow/modal/open_internal_request', $data);
        } elseif ($this->input->post('slug') === 'change_responsability') {
            $data['id'] = $this->input->post('id');

            $data['fluxo_andamento'] = $this->Workflow_model->get_fluxo_andamento($data['id']);

            $this->load->model('Staff_model');
            $data['staffs'] = $this->Staff_model->get();

            $this->load->view('gestao_corporativa/workflow/modal/change_responsability', $data);
        } elseif ($this->input->post('slug') === 'requests') {
            $this->load->view('gestao_corporativa/workflow/modal/requests', $data);
        } elseif ($this->input->post('slug') === 'view_backs') {
            $data['backs'] = $this->Workflow_model->get_workflow_back($this->input->post('fluxo_andamento_id'));
            $this->load->view('gestao_corporativa/workflow/modal/view_backs', $data);
        } elseif ($this->input->post('slug') === 'estornados') {
            $id = $this->input->post('id');

            $this->Intranet_general_model->add_log(["rel_type" => "workflow", "controller" => "Workflow", "function" => "workflow", "action" => "Workflow ($id)", "rel_id" => $id]);
            if (!get_staff_user_id()) {
                access_denied('Workflow');
                redirect('admin/authentication/admin_unimed');
            }

            $data['workflow'] = $this->Workflow_model->get_workflow_by_id($id);


            /* if ($workflow->user_created == get_staff_user_id()) {
                $data['user_created'] = true;
                //exit;
            }
            $data['title'] = 'Workflow #' . $id;
    
            $this->load->model('Departments_model');
            $departments = $this->Departments_model->get_staff_departments(get_staff_user_id(), true);
    
            $fluxo_atual = $this->Workflow_model->get_fluxos_andamento_atual($id, '', 'desc');
    
            $fluxos = $this->Workflow_model->get_fluxos_andamento($id);
    
    
    
            foreach ($fluxos as $fluxo) {
                if (in_array($fluxo['department_id'], $departments)) {
                    $data['in_department'] = true;
                }
            }
    
          //  var_dump($data['in_department']); exit;
    
            if ($fluxo_atual) {
                if (in_array($fluxo_atual->department_id, $departments)) {
                    if ($fluxo_atual->atribuido_a == 0) {
                        $this->assumir($fluxo_atual->id, $id);
                    }
                }
            }
    
            if ($workflow->status == 2) {
                $data['fluxo_atual'] = $fluxo_atual;
                //print_r($data['fluxo_atual']); exit;
                if ($data['fluxo_atual']->atribuido_a == get_staff_user_id()) {
                    $data['atual'] = true;
                    $data['alternativas'] = $this->Workflow_model->get_fluxos_seguintes($data['fluxo_atual']->fluxo_id);
                }
            }
    
            $data['workflow'] = $workflow;
            $data['categoria'] = $this->Categorias_campos_model->get_categoria($workflow->categoria_id);
    
            if (!$workflow->date_prazo || $workflow->date_prazo == '0000-00-00') {
                if ($data['categoria']->prazo) {
                    $edit_prazo['date_prazo'] = date('Y-m-d', strtotime("+" . $data['categoria']->prazo . " days", strtotime($workflow->date_created)));
                }
                $this->db->where('id', $id);
                $this->db->update('tbl_intranet_workflow', $edit_prazo);
            }
            if (!$workflow->date_prazo_client || $workflow->date_prazo_client == '0000-00-00') {
                if ($data['categoria']->prazo_cliente) {
                    $edit_prazo['date_prazo_client'] = date('Y-m-d', strtotime("+" . $data['categoria']->prazo_cliente . " days", strtotime($workflow->date_created)));
                }
                $this->db->where('id', $id);
    
                $this->db->update('tbl_intranet_workflow', $edit_prazo);
            }
    
            if ($data['categoria']->responsavel) {
    
                if (in_array($data['categoria']->responsavel, $departments)) {
                    $data['department_responsable'] = true;
                }
            }
            if ($data['categoria']->staff_responsavel) {
    
                if ($data['categoria']->staff_responsavel == get_staff_user_id()) {
                    $data['staff_responsable'] = true;
                }
            }
    
    
            if ($workflow->registro_atendimento_id) {
                $this->load->model('Atendimentos_model');
                $atentimento = $this->Atendimentos_model->get_ra_by_id($workflow->registro_atendimento_id);
                $data['atendimento'] = $atentimento;
                //print_r($atentimento); exit;
                if ($atentimento->client_id) {
    
                    $this->load->model('Clients_model');
                    $client = $this->Clients_model->get($atentimento->client_id);
                    $data['info_client'] = $this->_buscar_info_tasy($client->numero_carteirinha ?? '');
                }
            }
    
            $data['client_contacts'] = $this->Workflow_model->get_client_contacts($workflow->id);
            $data['pdf_views'] = $this->Company_model->get_pdf_view('workflow', $workflow->id);
            $data['internal_requests'] = $this->Workflow_model->get_resquest_internal($workflow->id);
            foreach ($data['internal_requests'] as $request) {
                if (!$request['staffid']) {
                    $this->db->where('id', $request['id']);
                    $this->db->delete(db_prefix() . '_intranet_workflow_internal_request');
                }
            }
            $data['internal_requests'] = $this->Workflow_model->get_resquest_internal($workflow->id);
            $data['external_requests'] = $this->Workflow_model->get_resquest_external($workflow->id);*/


            //  print_r($data); exit;
            $this->load->view('gestao_corporativa/workflow/modal/estornados', $data);
        }
    }

    public function cancel($id)
    {

        // echo "aqui"; exit;

        $dados['data_concluido'] = date('Y-m-d H:i:s');
        $dados['concluido'] = 1;
        if (!$this->input->post('alternativa')) {
            $dados['concluiu_fluxo'] = 1;
        }
        //  echo $_GET['fluxo_andamento_id']; exit;
        //   print_r($dados); exit;

        $this->db->where('id', $_GET['fluxo_andamento_id']);

        if ($this->db->update('tbl_intranet_workflow_fluxo_andamento', $dados)) {

            $dados = [];
            $dados['date_end'] = date('Y-m-d H:i:s');
            $dados['user_end'] = get_staff_user_id();
            if ($this->input->post('cancel_id')) {
                $dados['cancel_id'] = $this->input->post('cancel_id');
            }

            $dados['obs'] = $this->input->post('obs');
            if ($_GET['slug'] === 'cancel') {
                $dados['status'] = 4;
            } elseif ($_GET['slug'] === 'finish') {
                $dados['status'] = 3;
            } else {
                $dados['status'] = 0;
            }



            // print_r($dados); exit;


            $this->db->where('id', $id);
            $this->db->update('tbl_intranet_workflow', $dados);

            /* if($this->db->update('tbl_intranet_workflow', $dados)){
                echo "atualizou"; exit;
           }else{

            echo "não atualizou"; exit;
           }*/



            $workflow = $this->Workflow_model->get_workflow_by_id($id);
            if ($workflow->registro_atendimento_id) {
                $this->load->model('Atendimentos_model');
                $this->load->model('Comunicacao_model');
                $atentimento = $this->Atendimentos_model->get_ra_by_id($workflow->registro_atendimento_id);
                //print_r($atentimento); exit;
                if ($atentimento->client_id) {
                    if ($workflow->cancel_id) {
                        $this->load->model('Company_model');
                        $cancellation = $this->Company_model->get_cancel_workflow($workflow->cancel_id);
                    }

                    $this->load->model('Clients_model');
                    $client = $this->Clients_model->get($atentimento->client_id);

                    $action_info['rel_id'] = $workflow->id;
                    $action_info['rel_type'] = 'workflow';

                    if ($_GET['slug'] == 'cancel') {
                        $conteudo = 'Protocolo ' . $atentimento->protocolo . ': Sua solicitaçao foi cancelada. ';
                        $action_info['assunto'] = 'Solicitação Cancelada';
                    } else {
                        $conteudo = 'Protocolo ' . $atentimento->protocolo . ': Sua solicitaçao foi finalizada. ';
                        $action_info['assunto'] = 'Solicitação Finalizada';
                    }
                    if ($cancellation->cancellation) {
                        $conteudo .= 'Motivo: ' . $cancellation->cancellation;
                    }
                    $conteudo .= ' - Acesse unimedmanaus.sigplus.app.br ';
                    $action_info['conteudo_email'] = $conteudo;
                    $action_info['conteudo_sms'] = $conteudo;



                    $action_info['link_sigplus'] = 'unimedmanaus.sigplus.app.br';

                    //print_r($client); exit;
                    $action_info['email_destino'] = $client->email2;
                    $action_info['phone_destino'] = $client->phonenumber;
                    //print_r($action_info); exit;
                    $this->Comunicacao_model->send_sigplus_email_sms(true, $action_info);
                }
            }

            redirect(base_url() . 'gestao_corporativa/workflow/workflow/' . $id);
        }
    }

    public function back()
    {
        $dados['deleted'] = 1;

        $this->db->where('id', $_GET['fluxo_andamento_id']);

        if ($this->db->update('tbl_intranet_workflow_fluxo_andamento', $dados)) {
            $dados = [];
            $dados['data_concluido'] = '';
            $dados['concluido'] = 0;
            $dados['concluiu_fluxo'] = 0;
            $dados['date_received'] = date('Y-m-d H:i:s');

            $sql = "UPDATE tbl_intranet_workflow_fluxo_andamento set data_concluido = null, concluido = 0, concluiu_fluxo = 0,
            date_received = '" . date('Y-m-d H:i:s') . "'   where id = " .  $_GET['fluxo_andamento_id_old'];

            // echo $sql; exit;

            if ($this->db->query($sql)) {

                // echo "aqui"; exit;

                $id = $this->Workflow_model->add_workflow_back(array("workflow_id" => $_GET['id'], "workflow_andamento_id" => $_GET['fluxo_andamento_id_old'], "obs" => $this->input->post('obs')));
                //echo $id; exit;
                if ($id) {
                    $fluxo = $this->Workflow_model->get_fluxo_andamento($_GET['fluxo_andamento_id_old']);
                    $values = $this->Categorias_campos_model->get_values($_GET['id'], 'workflow', $fluxo->fluxo_id);

                    $dados = [];
                    $dados['rel_type'] = 'workflow_back';
                    $dados['rel_id'] = $id;
                    foreach ($values as $value) {
                        // print_r($dados); exit;
                        $this->db->where('id', $value['value_id']);
                        $this->db->update(db_prefix() . '_intranet_categorias_campo_values', $dados);
                    }
                    redirect(base_url() . 'gestao_corporativa/workflow/workflow/' . $_GET['id']);
                }
            }
        }
    }

    public function edit_internal_request($workflow_id = '')
    {
        if ($this->input->post('id')) {
            $data['description'] = $this->input->post('description');
            $data['staffid'] = $this->input->post('departmentid_staffid');
            $data['id'] = $this->input->post('id');
            $id = $this->Workflow_model->add_resquest_internal($data);
            redirect(base_url() . 'gestao_corporativa/workflow/workflow/' . $workflow_id);
        }
    }

    public function edit_external_request($workflow_id = '')
    {
        if ($this->input->post('id')) {
            $data['description'] = $this->input->post('description');
            $data['id'] = $this->input->post('id');
            $id = $this->Workflow_model->add_resquest_external($data);
            redirect(base_url() . 'gestao_corporativa/workflow/workflow/' . $workflow_id);
        }
    }

    public function delete_internal_request()
    {

        //echo $workflow_id; exit;
        if ($this->input->post('id')) {
            $this->db->where('id', $this->input->post('id'));
            $this->db->delete("tbl_intranet_workflow_internal_request");
            $this->db->where('categoria_id', $this->input->post('id'));
            $this->db->where('rel_type', 'internal_request_workflow');
            $this->db->delete("tbl_intranet_categorias_campo");
        }
    }

    public function delete_external_request()
    {

        //echo $workflow_id; exit;
        if ($this->input->post('id')) {
            $this->db->where('id', $this->input->post('id'));
            $this->db->delete("tbl_intranet_workflow_external_request");
            $this->db->where('categoria_id', $this->input->post('id'));
            $this->db->where('rel_type', 'external_request_workflow');
            $this->db->delete("tbl_intranet_categorias_campo");
        }
    }

    public function finish_internal_request($id)
    {
        $dados['date'] = date('Y-m-d H:i:s');
        $dados['status'] = 1;

        $this->db->where('id', $this->input->post('request_id'));

        if ($this->db->update('tbl_intranet_workflow_internal_request', $dados)) {
            $campos = $this->Categorias_campos_model->get_categoria_campos($this->input->post('request_id'), '', true, 'internal_request_workflow');
            //print_r($campos); exit;
            foreach ($campos as $campo) {


                $campos_value = array(
                    "categoria_id" => $this->input->post('request_id'),
                    "rel_id" => $this->input->post('request_id'),
                    "rel_type" => 'internal_request_workflow',
                    "campo_id" => $campo['id'],
                    "data_cadastro" => date('Y-m-d H:i:s'),
                    "user_cadastro" => get_staff_user_id(),
                    "empresa_id" => $this->session->userdata('empresa_id')
                );

                if ($campo['type'] == 'file') {
                    $campo_name = 'campo_' . $campo['name']; // Substitua isso pelo nome real do seu campo de envio de arquivos

                    if (isset($_FILES[$campo_name]) && $_FILES[$campo_name]['error'] === UPLOAD_ERR_OK) {
                        $file = $_FILES[$campo_name];

                        // Extrai a extensão original do arquivo
                        $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);

                        // Gera um novo nome de arquivo único com a extensão original
                        $new_filename = 'WF' . $id . '_' . $campo['name'] . uniqid() . '.' . $file_extension;

                        // Verifica se o diretório de destino existe; se não existir, cria-o recursivamente
                        if (!file_exists("./assets/intranet/arquivos/workflow_arquivos/campo_file/")) {
                            mkdir("./assets/intranet/arquivos/workflow_arquivos/campo_file/", 0777, true);
                        }

                        // Define o caminho completo para o destino do arquivo enviado
                        $destination = "./assets/intranet/arquivos/workflow_arquivos/campo_file/" . $new_filename;

                        // Move o arquivo enviado para o destino com o novo nome de arquivo
                        if (move_uploaded_file($file["tmp_name"], $destination)) {
                            // Upload do arquivo bem-sucedido
                            $value = $new_filename;
                        } else {
                            // Erro ao mover o arquivo
                            $value = '';
                        }
                    } else {
                        // Erro durante o upload do arquivo
                        $value = '';
                    }
                } else {
                    $value = $this->input->post('campo_' . $campo['name']);
                }

                if ($value) {
                    if (is_array($value)) {
                        $value = implode(', ', $value);
                    }
                }

                $campos_value['value'] = $value;
                //print_r($campos_value); exit;
                $this->Workflow_model->add_campo_value($campos_value);
            }
        }
        redirect(base_url() . 'gestao_corporativa/workflow');
    }

    public function take_responsability()
    {

        //echo $workflow_id; exit;
        if ($this->input->post('id')) {

            $id = $this->input->post('id');

            $data['atribuido_a'] = '0';
            $data['data_assumido'] = '';

            $sql = "UPDATE tbl_intranet_workflow_fluxo_andamento set data_assumido = null, atribuido_a = 0 where id = $id";

            if ($this->db->query($sql)) {
                echo json_encode(array("alert" => "success", "message" => 'O processo está em aberto.'), true);
            } else {
                echo json_encode(array("alert" => "danger", "message" => 'Erro ao retirar resposabilidade.'), true);
            }
        } else {
            echo json_encode(array("alert" => "danger", "message" => 'Tente novamente'), true);
        }
    }

    public function change_responsability()
    {

        //echo $workflow_id; exit;
        if ($this->input->post('id')) {
            $data['atribuido_a'] = $this->input->post('atribuido_a');
            $data['data_assumido'] = date('Y-m-d H:i:s');
            $this->db->where('id', $this->input->post('id'));
            $this->db->update("tbl_intranet_workflow_fluxo_andamento", $data);

            echo json_encode(array("alert" => "success", "message" => 'Mudança feita com sucesso.'), true);
        } else {
            echo json_encode(array("alert" => "danger", "message" => 'Tente novamente'), true);
        }
    }

    public function open($id)
    {

        $dados['date_end'] = '';
        $dados['user_end'] = '';
        $dados['status'] = 2;
        $dados['reopen'] = 1;


        $sql = "UPDATE tbl_intranet_workflow set date_end = null, user_end = 0, status = 2, reopen = 1 where id = " . $id;

        // $this->db->where('id', $id);
        //$this->db->update('tabela', $dados, array('id' => $id), array('NULL_EMPTY_STRING' => TRUE));
        // echo "aqui"; exit;
        // if ($this->db->update('tbl_intranet_workflow', $dados)) {
        if ($this->db->query($sql)) {
            // echo "aqui"; exit;
            $dados = [];
            $dados['data_concluido'] = 'null';
            $dados['concluido'] = 0;
            $dados['concluiu_fluxo'] = 0;

            $this->db->where('workflow_id', $id);
            $this->db->where('concluiu_fluxo', 1);

            $this->db->where('deleted', 0);
            $this->db->order_by('id', 'desc');
            $last = $this->db->get('tbl_intranet_workflow_fluxo_andamento')->row();

            // print_r($last);
            //exit;
            $sql = "UPDATE tbl_intranet_workflow_fluxo_andamento set data_concluido = null, concluido = 0, concluiu_fluxo = 0 where
                    workflow_id = $id and concluiu_fluxo = 1 and id = $last->id and deleted = 0 ";

            // echo $sql; exit;

            // $this->db->where('id', $last->id);

            // print_r($dados); exit;      

            if ($this->db->query($sql)) {

                $values = $this->Categorias_campos_model->get_values($id, 'workflow', $last->fluxo_id);

                //  print_r($values); exit;

                $dados = [];
                $dados['rel_type'] = 'workflow_open';
                $dados['rel_id'] = $id;

                foreach ($values as $value) {
                    // print_r($dados);
                    // exit;
                    $this->db->where('id', $value['value_id']);
                    $this->db->update(db_prefix() . '_intranet_categorias_campo_values', $dados,  array('NULL_EMPTY_STRING' => TRUE));
                }
                redirect(base_url() . 'gestao_corporativa/workflow/workflow/' . $id);
            }
        }
    }

    public function reports()
    {
        $data['title'] = 'Workflow - Relatórios';

        //$data['tipos'] = $this->Registro_ocorrencia_model->get_categorias();
        $data['bodyclass'] = 'tickets-page';
        add_admin_tickets_js_assets();
        $this->load->model('Staff_model');
        $data['categorias'] = $this->Categorias_campos_model->get_categorias('workflow');
        $data['staffs'] = $this->Staff_model->get();
        $this->load->model('departments_model');

        $data['statuses'] = $this->Workflow_model->get_status();
        //print_r($data['statuses']); exit;
        $data['default_tickets_list_statuses'] = hooks()->apply_filters('default_tickets_list_statuses', [1, 2]);

        $data['departments'] = $this->departments_model->get_staff_departments(false);
        $departments_workflow = $this->Workflow_model->get_departments();
        $cores = get_company_colors();
        $cores_i = 0;
        for ($i = 0; $i < count($departments_workflow); $i++) {
            $departments_workflow[$i]['color'] = $cores[$cores_i];
            $departments_workflow[$i]['total'] = $this->Workflow_model->get_departments_fluxo_andamento($departments_workflow[$i]['department_id']);

            $cores_i++;
            if ($cores_i > 22) {
                $cores_i = 0;
            }
        }
        //print_r($departments_workflow); exit;
        $data['departments_workflow'] = $departments_workflow;

        $categorias_workflow = $this->Workflow_model->get_categories();

        $cores_i = 0;
        for ($i = 0; $i < count($categorias_workflow); $i++) {
            $categorias_workflow[$i]['color'] = $cores[$cores_i];
            $categorias_workflow[$i]['total'] = $this->Workflow_model->get_categories_fluxo_andamento($categorias_workflow[$i]['categoria_id']);

            $cores_i++;
            if ($cores_i > 22) {
                $cores_i = 0;
            }
        }
        //print_r($categorias_workflow); exit;

        $data['categorias_workflow'] = $categorias_workflow;

        $semana = [];
        $hoje = new DateTime();  // Obtém a data atual
        // Define o dia da semana como segunda-feira (1)
        $hoje->modify('monday this week');

        // Loop para obter as datas da semana
        for ($i = 0; $i < 7; $i++) {
            $semana[] = $this->Workflow_model->get_data_fluxo_andamento($hoje->format('Y-m-d'));  // Formato: Ano-Mês-Dia
            $hoje->modify('+1 day');  // Avança para o próximo dia
        }
        //print_r($semana); exit;
        $data['semana'] = $semana;

        $ano_atual = date('Y');
        $mes_atual = date('m');

        $ano_atual = date('Y');
        $mes_atual = date('m');

        // Obter a quantidade de dias no mês atual
        $dias_no_mes = cal_days_in_month(CAL_GREGORIAN, $mes_atual, $ano_atual);

        // Imprimir os dias do mês atual no formato YYYY-mm-dd
        for ($dia = 1; $dia <= $dias_no_mes; $dia++) {
            $mes['dias'][] = sprintf('%04d-%02d-%02d', $ano_atual, $mes_atual, $dia);
            $mes['totals'][] = $this->Workflow_model->get_data_fluxo_andamento(sprintf('%04d-%02d-%02d', $ano_atual, $mes_atual, $dia));
        }

        $data['mes'] = $mes;
        //print_r($mes); exit;

        $ano_atual = date('Y');

        // Loop pelos meses do ano
        for ($mes = 1; $mes <= 12; $mes++) {
            // Formatar o mês com dois dígitos (01, 02, ..., 12)
            $mes_formatado = str_pad($mes, 2, '0', STR_PAD_LEFT);

            $meses['interno'][] = $this->Workflow_model->get_mes_fluxo_andamento($mes_formatado, $ano_atual);
            $meses['portal'][] = $this->Workflow_model->get_mes_fluxo_andamento($mes_formatado, $ano_atual, true);
        }
        $data['meses'] = $meses;

        $this->load->view('gestao_corporativa/workflow/reports', $data);
    }

    public function report()
    {
        //print_r($this->input->post()); exit;

        $this->Intranet_general_model->add_log(["rel_type" => "workflow", "controller" => "Workflow", "function" => "report", "action" => "Relatórios (Resultado de busca)"]);
        $categoria_id = $this->input->post('categoria_id');

        $campos_from_category = $this->Categorias_campos_model->get_categoria_campos($categoria_id, 0, false, '', '', '');

        $_campos_from_category = [];
        foreach ($campos_from_category as $c) {
            if ($this->input->post('campo_' . $c['name'])) {
                $_campos_from_category[] = array('id' => $c['id'], 'value' => $this->input->post('campo_' . $c['name']));
            }
        }

        $start = $this->input->post('start');
        $end = $this->input->post('end');
        $current = $this->input->post('current');

        $created_staffid = $this->input->post('staffid');
        $created_departmentid = $this->input->post('departmentid');

        $status = $this->input->post('status');
        $campo_wf = [];
        $campo_cat = [];

        $campos_system = $this->input->post('campos_system');
        foreach ($campos_system as $campo) {

            $_campo = explode(';', $campo);

            $__campo['column'] = $_campo[0];
            $__campo['label'] = $_campo[1];
            $campo_wf[] = $__campo;
            $campos[] = $__campo['column'];
        }

        $campos_cat = $this->input->post('campos_cat');

        foreach ($campos_cat as $campo) {

            $_campo = explode(';', $campo);

            $__campo['column'] = $_campo[0];
            $__campo['label'] = $_campo[1];
            $__campo['type'] = $_campo[2];
            $campo_cat[] = $__campo;
        }

        $campos_ = $this->input->post('campos');
        $campos_new = [];

        $deps = [];
        $departamentos = [];

        foreach ($campos_ as $campo) {

            $_campo = explode(';', $campo);

            $__campo['column'] = $_campo[0];
            $__campo['label'] = $_campo[1];
            $__campo['dep'] = $_campo[2];
            $__campo['type'] = $_campo[3];

            //$campos_new[] = $__campo;
            if ($__campo['dep']) {
                $campos_dep[] = $__campo;
                $campos_new[] = $__campo;
            }

            if (isset($departamentos[$__campo['dep']])) {
                // Se o departamento já existe, incrementa o contador de campos
                $departamentos[$__campo['dep']]['campos']++;
            } elseif ($__campo['dep']) {
                // Se o departamento não existe, adiciona-o ao array de departamentos e inicializa o contador de campos
                $departamentos[$__campo['dep']] = [
                    'campos' => 1,
                ];

                // Adiciona o departamento ao array $deps
                $deps[] = $__campo['dep'];
            }
        }
        //print_r($campos_new); exit;

        $deps_qtd = [];
        foreach ($deps as $dep) {
            $deps_qtd[] = array("dep" => $dep, "qtd" => $departamentos[$dep]['campos']);
        }

        $data['deps_qtd'] = $deps_qtd;
        $data['campo_wf'] = $campo_wf;
        $data['campos_'] = $campos_new;
        $data['campo_cat'] = $campo_cat;
        $data['categoria'] = $this->Categorias_campos_model->get_categoria($categoria_id);

        //echo 'jj'; exit;

        $data['workflows'] = $this->Workflow_model->get_report($categoria_id, $start, $end, $campos, $status, $campos_dep, $campo_cat, $current, $created_departmentid, $created_staffid, $_campos_from_category);

        $this->load->view('gestao_corporativa/workflow/reports_det', $data);
    }



    public function arruma_wf()
    {

        $sql = "SELECT a.id, a.workflow_id
            from tbl_intranet_workflow_fluxo_andamento as a 
            inner join tbl_intranet_workflow as b   on a.workflow_id = b.id
            where a.concluido = 0
            and a.deleted = 0 
            and a.date_created BETWEEN '2023-01-01 00:00:00' and '2024-12-31 23:59:59'  
            and a.concluiu_fluxo = 0
            and b.status = 2
            GROUP BY a.workflow_id 
            ORDER BY a.workflow_id desc";

        $wfs = $this->db->query($sql)->result_array();

        $conn = mysqli_connect('54.90.200.138', 'wwsigp_sig', 'Sigplus*2024', 'wwsigp_sigplus');

        $conn->set_charset("utf8");

        if (mysqli_connect_errno()) {
            echo ("Failed to connect to MySQL: " . mysqli_connect_error());
        }

        $i = 0;

        // print_r($wfs); exit;
        foreach ($wfs as $wf) {

            $sql = "SELECT a.id, a.workflow_id, a.fluxo_sequencia, a.data_assumido ,a.data_concluido, a.concluido,a.concluiu_fluxo, a.date_created
            from tbl_intranet_workflow_fluxo_andamento a where  workflow_id = " . $wf['workflow_id'] . " and deleted = 0";

            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    // print_r($row); exit;
                    $rows[$i] = $row;
                    $i++;
                }
            }
        }

        $j = 1;
        $nao_atualizadas = 0;
        $atualizadas = 0;

        foreach ($rows as $row) {

            $data['data_concluido'] = $row['data_concluido'];
            $data['concluido'] = $row['concluido'];
            $data['concluiu_fluxo'] = $row['concluiu_fluxo'];
            $data['ajuste_concluido'] = 1;

            $sql = "SELECT * from tbl_intranet_workflow_fluxo_andamento where id = " . $row['id'];
            $result = $this->db->query($sql)->row_array();

            if (
                $data['data_concluido'] == $result['data_concluido']
                &&  $data['concluido'] ==  $result['concluido']
                &&  $data['concluiu_fluxo'] ==  $result['concluiu_fluxo']
            ) {

                echo "<br> ------------------ $j -------------------- <br>";
                echo "Não Atualizou a linha: " . $row['id'];
                echo "<br> -------------------------------------- <br>";

                $nao_atualizadas++;
            } else {

                $array = array_filter($data, function ($valor) {
                    return !is_null($valor) && $valor !== '';
                });
                $data = $array;

                $this->db->where('id', $row['id']);
                if ($this->db->update('tbl_intranet_workflow_fluxo_andamento', $data)) {
                    echo "<br> ------------------ $j -------------------- <br>";
                    echo "Atualizou a linha: " . $row['id'];
                    echo "<br> -------------------------------------- <br>";
                }

                $atualizadas++;
            }



            $j++;
        }


        echo "Total de Linhas Atualizas: $atualizadas <br><br>";
        echo "Total de Linhas Não Atualizas: $nao_atualizadas <br><br>";
    }
}
