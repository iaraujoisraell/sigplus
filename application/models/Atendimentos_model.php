<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * 19/12/2022
 * @WannaLuiza
 * INTRANET - Model para REGISTRO DE ATENDIMENTOS
 */
class Atendimentos_model extends App_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->empresa_id = $this->session->userdata('empresa_id');
    }

    public function get_ra_listagem($filtros = [])
    {
        $empresa_id = (int) $this->session->userdata('empresa_id');

        $this->db->select("
            ra.id,
            ra.protocolo,
            ra.client_id,
            ra.categoria_id,
            ra.canal_atendimento_id,
            ra.contato,
            ra.email,
            ra.date_created,
            c.userid,
            c.company,
            c.numero_carteirinha,
            s.firstname,
            s.lastname,
            canal.canal,
            cat.titulo AS categoria
        ");
        $this->db->from('tbl_intranet_registro_atendimento ra');
        $this->db->join('tblclients c', 'c.userid = ra.client_id', 'left');
        $this->db->join('tblstaff s', 's.staffid = ra.user_created', 'left');
        $this->db->join('tbl_intranet_registro_atendimento_canais canal', 'canal.id = ra.canal_atendimento_id', 'left');
        $this->db->join('tbl_intranet_categorias cat', 'cat.id = ra.categoria_id', 'left');

        $this->db->where('ra.deleted', 0);
        $this->db->where('ra.empresa_id', $empresa_id);

        if (!empty($filtros['meus_atendimentos'])) {
            $this->db->where('ra.user_created', get_staff_user_id());
        }

        if (!empty($filtros['portal'])) {
            $this->db->where('ra.canal_atendimento_id', 0);
        } else {
            $this->db->where('ra.canal_atendimento_id !=', 0);
        }

        if (!empty($filtros['client'])) {
            $this->db->like('c.company', $filtros['client']);
        }

        if (!empty($filtros['carteirinha'])) {
            $this->db->like('c.numero_carteirinha', $filtros['carteirinha']);
        }

        if (!empty($filtros['colaborador'])) {
            $termo = $this->db->escape_like_str($filtros['colaborador']);
            $this->db->where("CONCAT_WS(' ', s.firstname, s.lastname) LIKE '%{$termo}%'", null, false);
        }

        if (!empty($filtros['protocolo'])) {
            $this->db->like('ra.protocolo', $filtros['protocolo']);
        }

        if (!empty($filtros['id'])) {
            $idWf = (int) $filtros['id'];
            $this->db->where("ra.id IN (SELECT registro_atendimento_id FROM tbl_intranet_workflow WHERE id = {$idWf})", null, false);
        }

        if (!empty($filtros['categoria_id']) && is_array($filtros['categoria_id'])) {
            $categorias = [];
            foreach ($filtros['categoria_id'] as $cat) {
                if ($cat === '' || $cat === null) {
                    continue;
                }
                if ((int) $cat === 9) {
                    $categorias[] = 9;
                    $categorias[] = 0;
                } else {
                    $categorias[] = (int) $cat;
                }
            }

            $categorias = array_unique($categorias);
            if (!empty($categorias)) {
                $this->db->where_in('ra.categoria_id', $categorias);
            }
        }

        if (!empty($filtros['canal_atendimento_id']) && is_array($filtros['canal_atendimento_id'])) {
            $canais = [];
            foreach ($filtros['canal_atendimento_id'] as $canal) {
                if ($canal === '' || $canal === null) {
                    continue;
                }
                $canais[] = ((int) $canal === 10) ? 0 : (int) $canal;
            }

            $canais = array_unique($canais);
            if (!empty($canais)) {
                $this->db->where_in('ra.canal_atendimento_id', $canais);
            }
        }

        if (!empty($filtros['data_inicio'])) {
            $this->db->where('DATE(ra.date_created) >=', $filtros['data_inicio']);
        }

        if (!empty($filtros['data_fim'])) {
            $this->db->where('DATE(ra.date_created) <=', $filtros['data_fim']);
        }

        $this->db->order_by('ra.id', 'DESC');

        $rows = $this->db->get()->result_array();

        if (empty($rows)) {
            return [];
        }

        $ids = array_column($rows, 'id');

        $workflowCount = [];
        $roCount       = [];
        $workflowLista = [];
        $roLista       = [];
        $chavesLista   = [];

        $wf = $this->db
            ->select('id, registro_atendimento_id')
            ->from('tbl_intranet_workflow')
            ->where('deleted', 0)
            ->where('empresa_id', $empresa_id)
            ->where_in('registro_atendimento_id', $ids)
            ->order_by('id', 'desc')
            ->get()
            ->result_array();

        foreach ($wf as $item) {
            $raId = (int) $item['registro_atendimento_id'];
            if (!isset($workflowCount[$raId])) {
                $workflowCount[$raId] = 0;
                $workflowLista[$raId] = [];
            }
            $workflowCount[$raId]++;
            $workflowLista[$raId][] = $item;
        }

        $ros = $this->db
            ->select('id, registro_atendimento_id')
            ->from('tbl_intranet_registro_ocorrencia')
            ->where('deleted', 0)
            ->where('empresa_id', $empresa_id)
            ->where_in('registro_atendimento_id', $ids)
            ->order_by('id', 'desc')
            ->get()
            ->result_array();

            

        foreach ($ros as $item) {
            $raId = (int) $item['registro_atendimento_id'];
            if (!isset($roCount[$raId])) {
                $roCount[$raId] = 0;
                $roLista[$raId] = [];
            }
            $roCount[$raId]++;
            $roLista[$raId][] = $item;
        }

        $campos = $this->db
            ->select('v.rel_id, c.nome as nome_campo, v.value')
            ->from('tbl_intranet_categorias_campo_values v')
            ->join('tbl_intranet_categorias_campo c', 'c.id = v.campo_id', 'inner')
            ->where('v.deleted', 0)
            ->where('c.deleted', 0)
            ->where('v.rel_type', 'atendimento')
            ->where_in('v.rel_id', $ids)
            ->order_by('c.ordem', 'asc')
            ->get()
            ->result_array();

        foreach ($campos as $campo) {
            $relId = (int) $campo['rel_id'];
            if (!isset($chavesLista[$relId])) {
                $chavesLista[$relId] = [];
            }
            $chavesLista[$relId][] = $campo;
        }

        foreach ($rows as &$row) {
            $row['workflow_count'] = $workflowCount[$row['id']] ?? 0;
            $row['ro_count']       = $roCount[$row['id']] ?? 0;
            $row['workflow_itens'] = $workflowLista[$row['id']] ?? [];
            $row['ro_itens']       = $roLista[$row['id']] ?? [];
            $row['campos_itens']   = $chavesLista[$row['id']] ?? [];

            // 👉 NOVA COLUNA
            $carteirinha = $row['numero_carteirinha'] ?? '';
            $carteirinha = preg_replace('/\D+/', '', (string) $carteirinha);

            if ($carteirinha === '') {
                $row['origem_carteirinha'] = 'AVULSO';
            } elseif (substr($carteirinha, 0, 4) === '0079') {
                $row['origem_carteirinha'] = 'UNIMED MANAUS';
            } else {
                $row['origem_carteirinha'] = 'UNIMED INTERCÂMBIO';
            }
        }
        unset($row);

        return $rows;
    }

    public function montar_transcricao_nota($atendimento_id)
    {
        $this->load->model('Clients_model');
        $this->load->model('Categorias_campos_model');

        $info = $this->get_ra_by_id($atendimento_id);

        if (!$info) {
            return '';
        }

        $client = $this->Clients_model->get($info->client_id);
        $ros    = $this->get_ros_by_ra_id($atendimento_id);
        $wfs    = $this->get_worklow_by_ra_id($atendimento_id);
        $rapidos = $this->get_values_by_categoria_id($atendimento_id);
        $campos = $this->Categorias_campos_model->get_values($atendimento_id, 'atendimento');

        $nome         = $client ? trim($client->company) : 'NÃO INFORMADO';
        $carteirinha  = $client ? trim($client->numero_carteirinha) : 'NÃO INFORMADA';
        $canal        = ($info->canal_atendimento_id == 0) ? 'PORTAL DO CLIENTE' : strtoupper($info->canal);
        $dataContato  = !empty($info->date_created) ? date('d/m/Y \à\s H:i', strtotime($info->date_created)) : '-';
        $protocolo    = $info->protocolo;

        $texto = "O(A) beneficiário(a) {$nome}, carteirinha {$carteirinha}, entrou em contato pelo canal {$canal}, na data de {$dataContato}, referente ao protocolo {$protocolo}.";

        if (!empty($campos)) {
            $texto .= "\n\nOutras informações do atendimento:";
            foreach ($campos as $campo) {
                if (empty($campo['value'])) {
                    continue;
                }
                $texto .= "\n- " . $campo['nome_campo'] . ': ' . trim(strip_tags($campo['value']));
            }
        }

        if (!empty($rapidos)) {
            $texto .= "\n\nSolicitações rápidas registradas:";
            foreach ($rapidos as $item) {
                $valor = trim(strip_tags($item['value']));
                if ($valor === '') {
                    continue;
                }
                $texto .= "\n- " . $item['nome_campo'] . ': ' . $valor;
            }
        }

        if (!empty($wfs)) {
            $texto .= "\n\nWorkflows vinculados:";
            foreach ($wfs as $wf) {
                $status = get_ro_status($wf['status']);
                $statusLabel = is_array($status) && isset($status['label']) ? $status['label'] : $wf['status'];
                $texto .= "\n- WF #{$wf['id']} - {$wf['titulo']} - Status: {$statusLabel}";
            }
        }

        if (!empty($ros)) {
            $texto .= "\n\nRegistros de ocorrência vinculados:";
            foreach ($ros as $ro) {
                $status = get_ro_status($ro['status']);
                $statusLabel = is_array($status) && isset($status['label']) ? $status['label'] : $ro['status'];
                $assunto = trim(strip_tags($ro['titulo']));
                $texto .= "\n- RO #{$ro['id']} - {$assunto} - Status: {$statusLabel}";
            }
        }

        return $texto;
    }

    public function get_origem_carteirinha($carteirinha = '')
    {
        $carteirinha = preg_replace('/\D+/', '', (string) $carteirinha);

        if ($carteirinha === '') {
            return 'AVULSO';
        }

        if (substr($carteirinha, 0, 4) === '0079') {
            return 'UNIMED MANAUS';
        }

        return 'UNIMED INTERCÂMBIO';
    }

    public function get_canais_atendimento()
    {
        $empresa_id = $this->session->userdata('empresa_id');
        $tbl_canais_atendimento = 'tbl_intranet_registro_atendimento_canais';
        $sql = "SELECT $tbl_canais_atendimento.* from $tbl_canais_atendimento
        where $tbl_canais_atendimento.empresa_id = $empresa_id and $tbl_canais_atendimento.deleted = 0";

        return $this->db->query($sql)->result_array();
    }

    /**
     * 19/10/2022
     * @WannaLuiza
     * Insere um valor para determinado campo de determinada categoria
     */
    public function add_campo_value($data = null)
    {

        //$data['rel_type'] = 'ra_atendimento_rapido';
        unset($data['registro_id']);
        if ($this->db->insert("tbl_intranet_categorias_campo_values", $data)) {
            return true;
        } else {
            return false;
        }
    }

    public function get_sequancial_protocolo()
    {
        $empresa_id = $this->session->userdata('empresa_id');
        $tbl_categoria = 'tbl_intranet_registro_atendimento';

        $sql = "SELECT max(sequencial) as sequencial from $tbl_categoria
        where DATE_FORMAT(date_created, '%Y-%m-%d') = '" . date('Y-m-d') . "'";

        //echo $sql; exit;
        return $this->db->query($sql)->row();
    }

    //SEQUENCIAL DO PROTOCOLO PORTAL DO PACIENTE
    public function get_sequancial_protocolo_paciente()
    {
        $empresa_id = $this->session->userdata('empresa_id');
        $tbl_categoria = 'tbl_intranet_registro_atendimento';

        $sql = "SELECT max(sequencial_portal_paciente) as sequencial from $tbl_categoria
        where DATE_FORMAT(date_created, '%Y-%m-%d') = '" . date('Y-m-d') . "'";

        //echo $sql; exit;
        return $this->db->query($sql)->row();
    }

    /**
     * 18/10/2022
     * @WannaLuiza
     * Insere um r.o
     */
    public function add($data = null, $portal_paciente = '')
    {
        //echo $portal_paciente; exit;

        if ($data) {


            if ($portal_paciente == 1) {

                // GERA O NÚMERO DE PROTOCOLO

                $prot_ano = date('Y');
                $prot_mes = date('m');
                $prot_dia = date('d');
                $prot_hora = date('H');
                $prot_minuto = date('i');
                $prot_segundo = date('s');

                //IDENTIFICADOR PROTOCOLO PF
                $prot_fixo = '11';
                $completa_zeros = '';
                // sequencial protocolo
                $sequencial_atual = 0;
                $sequencial_atual = $this->get_sequancial_protocolo_paciente();
                if ($sequencial_atual->sequencial == '') {
                    $sequencial_atual->sequencial = 0;
                }
                $proximo_sequencial = $sequencial_atual->sequencial + 1;
                $data['sequencial_portal_paciente'] = $proximo_sequencial;
                // protocolo                
                $count_seq = strlen($proximo_sequencial);
                $qtde_zeros = 6 - $count_seq;

                for ($i = 0; $i <= $qtde_zeros; $i++) {
                    $completa_zeros .= 0;
                }

                $numero_protocolo = $prot_ano . $prot_mes . $prot_dia . $prot_hora . $prot_minuto . $prot_segundo
                    . $prot_fixo . $completa_zeros . $proximo_sequencial;
            } else {

                // GERA O NÚMERO DE PROTOCOLO
                $prot_fixo = '311961';
                $prot_ano = date('Y');
                $prot_mes = date('m');
                $prot_dia = date('d');
                $prot_seq = '5';
                $completa_zeros = '';

                // sequencial protocolo
                $sequencial_atual = 0;
                //echo 'kksk'; exit;
                $sequencial_atual = $this->get_sequancial_protocolo();
                if ($sequencial_atual->sequencial == '') {
                    $sequencial_atual->sequencial = 0;
                }

                $proximo_sequencial = $sequencial_atual->sequencial + 1;

                $data['sequencial'] = $proximo_sequencial;

                // protocolo
                //echo 'aqui'; exit;
                $count_seq = strlen($proximo_sequencial);

                $qtde_zeros = 6 - $count_seq;

                for ($i = 0; $i <= $qtde_zeros; $i++) {
                    $completa_zeros .= 0;
                }

                $numero_protocolo = $prot_fixo . $prot_ano . $prot_mes . $prot_dia . $prot_seq . $completa_zeros . $proximo_sequencial;
            }

          //  echo $numero_protocolo; exit;

            $data['protocolo'] = $numero_protocolo;

            if ($data['user_created'] == '') {
                $data['user_created'] = get_staff_user_id();
            }

            $data['date_created'] = date('Y-m-d H:i:s');
            $data['empresa_id'] = $this->session->userdata('empresa_id');

            $company_name = get_company_option('', 'companyname', $data['empresa_id']);
            $msg = $company_name . ': Protocolo ' . $data['protocolo'] . ' gerado mediante a abertura de atendimento.';

            //print_r($data); exit;

            if ($this->db->insert("tbl_intranet_registro_atendimento", $data)) {
                $id_insert = $this->db->insert_id();

                $campos_email = array(
                    "data_registro" => date('Y-m-d H:i:s'),
                    "usuario_registro" => get_staff_user_id(),
                    "email_destino" => $data['email'],
                    "assunto" => 'Protocolo Aberto',
                    "mensagem" => $msg,
                    "rel_type" => 'atendimento',
                    "rel_id" => $id_insert,
                    "empresa_id " => $this->session->userdata('empresa_id')
                );

                $campos_sms = array(
                    "data_registro" => date('Y-m-d H:i:s'),
                    "usuario_registro" => get_staff_user_id(),
                    "phone_destino" => preg_replace("/[^0-9]/", "", $data['contato']),
                    "assunto" => 'Protocolo Aberto',
                    "mensagem" => $msg,
                    "rel_type" => 'atendimento',
                    "rel_id" => $id_insert,
                    "empresa_id " => $this->session->userdata('empresa_id')
                );
                $this->load->model('Comunicacao_model');

                $email = $this->Comunicacao_model->addEmail($campos_email);
                $sms = $this->Comunicacao_model->addSms($campos_sms);

                return $id_insert;
            } else {
                return false;
            }
        }
    }

    public function encerrar_atendimento($id)
    {
        $data['data_encerramento'] = date('Y-m-d H:i:s');
        $this->db->where('id', $id);
        $this->db->update('tbl_intranet_registro_atendimento', $data);
    }

    /**
     * 19/10/2022
     * @WannaLuiza
     * Retorna os indexadores do ged
     */
    public function get_options($id = '')
    {

        $empresa_id = $this->session->userdata('empresa_id');
        $tbl_options = 'tbl_intranet_categorias_campo_options';

        $sql = "SELECT * from $tbl_options "
            . "WHERE $tbl_options.empresa_id = $empresa_id AND  $tbl_options.campo_id = $id  ORDER BY $tbl_options.id asc ";

        // echo $sql; exit;
        return $this->db->query($sql)->result_array();
    }

    public function get_values_by_categoria_id($id = '')
    {

        $empresa_id = $this->session->userdata('empresa_id');
        $tbl_values = 'tbl_intranet_categorias_campo_values';
        $tbl_campos = 'tbl_intranet_categorias_campo';

        $sql = "SELECT $tbl_campos.categoria_id as categoria, $tbl_campos.nome as nome_campo, $tbl_campos.id as id_campo, $tbl_campos.type as tipo_campo, $tbl_campos.name as name_campo, "
            . "$tbl_campos.options as opcoes_select, $tbl_campos.tam_coluna as tamanho, $tbl_values.value, $tbl_values.id as value_id from $tbl_values "
            . "INNER JOIN $tbl_campos ON $tbl_campos.id = $tbl_values.campo_id "
            . "WHERE $tbl_values.empresa_id = $empresa_id AND $tbl_values.deleted = 0 AND $tbl_campos.deleted = 0 and $tbl_values.rel_type = 'ra_atendimento_rapido' and rel_id = $id ORDER BY $tbl_campos.ordem asc ";

        return $this->db->query($sql)->result_array();
    }

    public function get_ra_by_id($id = '', $client_id = '')
    {

        $tbl_ra = 'tbl_intranet_registro_atendimento';
        $tbl_categoria = 'tbl_intranet_categorias';
        $tbl_atendimento_canais = 'tbl_intranet_registro_atendimento_canais';

        $sql = "SELECT $tbl_ra.*, $tbl_categoria.titulo, $tbl_categoria.id as categoria_id, $tbl_atendimento_canais.canal as canal, tblstaff.firstname, tblstaff.lastname  from $tbl_ra "
            . "LEFT JOIN $tbl_categoria ON $tbl_categoria.id = $tbl_ra.categoria_id "
            . "LEFT JOIN $tbl_atendimento_canais ON $tbl_atendimento_canais.id = $tbl_ra.canal_atendimento_id "
            . "LEFT JOIN tblstaff ON tblstaff.staffid = $tbl_ra.user_created "
            . "WHERE $tbl_ra.deleted = 0 ";

        if ($id != '') {
            $sql .= "AND $tbl_ra.id = $id";
            return $this->db->query($sql)->row();
        }
        if ($client_id != '') {
            $sql .= "AND $tbl_ra.client_id = $client_id order by $tbl_ra.id desc";
            //echo $sql; exit;
            return $this->db->query($sql)->result_array();
        }
        //echo $sql; exit;
    }

    public function get_ros_by_ra_id($id = '')
    {

        $empresa_id = $this->session->userdata('empresa_id');

        $sql = "SELECT tbl_intranet_registro_ocorrencia.date_created, tbl_intranet_registro_ocorrencia.subject as titulo, tbl_intranet_registro_ocorrencia.date as date, tbl_intranet_registro_ocorrencia.priority as prioridade,
                 tbl_intranet_categorias.titulo as categoria, tbldepartments.name as responsavel, tbl_intranet_registro_ocorrencia.status as status ,
                 tbl_intranet_registro_ocorrencia.id,priority as priority,registro_atendimento_id as atendimento,atribuido_a as atribuido_a,
                 tbl_intranet_registro_atendimento.protocolo as protocolo
                 FROM tbl_intranet_registro_ocorrencia
                 LEFT JOIN tbl_intranet_categorias ON tbl_intranet_categorias.id = tbl_intranet_registro_ocorrencia.categoria_id
                 LEFT JOIN tbldepartments ON tbldepartments.departmentid = tbl_intranet_categorias.responsavel
                 LEFT JOIN tbl_intranet_registro_atendimento ON tbl_intranet_registro_atendimento.id = tbl_intranet_registro_ocorrencia.registro_atendimento_id
                WHERE tbl_intranet_registro_ocorrencia.deleted = 0 AND tbl_intranet_registro_ocorrencia.empresa_id = $empresa_id
                and tbl_intranet_registro_ocorrencia.registro_atendimento_id = $id
                order by tbl_intranet_registro_ocorrencia.id desc";

        //echo $sql; exit;
        return $this->db->query($sql)->result_array();
    }

    public function get_auts_by_ra_id($id = '')
    {

        $empresa_id = $this->session->userdata('empresa_id');

        $sql = "SELECT tbl_intranet_client_self.*, tbl_intranet_categorias.titulo
                 FROM tbl_intranet_client_self
                 LEFT JOIN tbl_intranet_categorias ON tbl_intranet_categorias.id = tbl_intranet_client_self.categoria_id
                WHERE tbl_intranet_client_self.deleted = 0 AND tbl_intranet_client_self.empresa_id = $empresa_id
                and tbl_intranet_client_self.rel_id = $id
                and tbl_intranet_client_self.rel_type = 'ra'
                order by tbl_intranet_client_self.id desc";

        //echo $sql; exit;
        return $this->db->query($sql)->result_array();
    }

    public function get_worklow_by_ra_id($id = '')
    {

        $empresa_id = $this->session->userdata('empresa_id');

        $sql = "SELECT tbl_intranet_workflow.date_prazo_client, tbl_intranet_workflow.date_end_client,
            tbl_intranet_workflow.date_created, tbl_intranet_workflow.id, tbl_intranet_workflow.cancel_id, tbl_intranet_workflow.obs, tbl_intranet_categorias.titulo_abreviado, tbl_intranet_categorias.titulo, 
            tbl_intranet_workflow.date_start, tbl_intranet_workflow.date_end, tbl_intranet_workflow.status
                FROM tbl_intranet_workflow
                LEFT JOIN tbl_intranet_categorias ON tbl_intranet_categorias.id = tbl_intranet_workflow.categoria_id
                where tbl_intranet_workflow.deleted = 0  AND tbl_intranet_workflow.empresa_id = $empresa_id and tbl_intranet_workflow.registro_atendimento_id = $id ";

        //echo $sql; exit;
        return $this->db->query($sql)->result_array();
    }


    // FILTRO 
    public function get_filtro_ra_by_user()
    {

        // $empresa_id = $this->session->userdata('empresa_id');
        $data_hoje = date('Y-m-d');
        $user = get_staff_user_id();
        $sql = "SELECT *
                FROM tbl_intranet_filtro_ra
                where user = $user  AND data_busca = '$data_hoje' ";

        //echo $sql; exit;
        return $this->db->query($sql)->row();
    }
}
