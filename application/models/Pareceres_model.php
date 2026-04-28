<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pareceres_model extends App_Model
{
    protected $table = 'tblpareceres';
    protected $destTable = 'tblpareceres_destinatarios';

    public function __construct()
    {
        parent::__construct();
    }

    public function upsert_parecer($data, $tenant_id = null)
    {
        if ($tenant_id) $data['saas_tenant_id'] = $tenant_id;

        // Tenta localizar pelo nr_parecer + tenant
        $this->db->where('nr_parecer', $data['nr_parecer']);
        if ($tenant_id) $this->db->where('saas_tenant_id', $tenant_id);
        $exists = $this->db->get($this->table)->row_array();

        if ($exists) {
            $this->db->where('id', $exists['id'])->update($this->table, $data);
            return (int)$exists['id'];
        } else {
            $this->db->insert($this->table, $data);
            return (int)$this->db->insert_id();
        }
    }

    public function replace_destinatarios($parecer_id, $rows)
    {
        // remove antigos e insere de novo (fica idempotente)
        $this->db->where('parecer_id', $parecer_id)->delete($this->destTable);
        if (!empty($rows)) {
            foreach ($rows as $r) {
                $r['parecer_id'] = $parecer_id;
                $this->db->insert($this->destTable, $r);
            }
        }
    }

    public function get_pendentes_do_dia($data_sql, $tenant_id = null, $limit = 200)
    {
        $this->db->select('p.id as parecer_id, p.nr_parecer, p.nr_atendimento, p.nm_paciente_abrev, p.idade,
                        p.localizacao_leito, p.dt_pedido, p.ds_especialidade_origem, p.ds_especialidade_dest,
                        d.id as destinatario_id, d.nome as destinatario_nome, d.telefone, d.phone_normalized,
                        d.accept_token, d.send_status, d.sent_at');
        $this->db->from($this->table . ' p');
        $this->db->join($this->destTable . ' d', 'd.parecer_id = p.id', 'inner');

        $this->db->where('p.dt_pedido', $data_sql);
        if ($tenant_id) $this->db->where('p.saas_tenant_id', $tenant_id);

        // não enviados + tem telefone
        $this->db->group_start();
            $this->db->where('d.sent_at IS NULL', null, false);
            $this->db->or_where('d.send_status', 'pending');
        $this->db->group_end();

        $this->db->where('NULLIF(TRIM(d.telefone), \'\') IS NOT NULL', null, false);

        $this->db->order_by('p.id', 'asc')->order_by('d.ordem', 'asc')->limit($limit);
        return $this->db->get()->result_array();
    }

    public function set_envio_result($destinatario_id, $status, $responseJson = null, $phone_norm = null)
    {
        $data = [
            'send_status'        => $status,
            'send_response_json' => $responseJson,
            'sent_at'            => ($status === 'sent' ? date('Y-m-d H:i:s') : null),
        ];
        if ($phone_norm) $data['phone_normalized'] = $phone_norm;

        $this->db->where('id', $destinatario_id)->update($this->destTable, $data);
    }

    public function ensure_accept_token($destinatario_id)
    {
        $row = $this->db->select('accept_token')->where('id', $destinatario_id)->get($this->destTable)->row_array();
        if (!empty($row['accept_token'])) return $row['accept_token'];

        $token = bin2hex(random_bytes(16)); // 32 chars
        $this->db->where('id', $destinatario_id)->update($this->destTable, ['accept_token' => $token]);
        return $token;
    }

    public function marcar_aceite($parecer_id, $destinatario_id, $nome, $ip = null, $ua = null)
    {
        // marca no destinatário
        $this->db->where('id', $destinatario_id)->update($this->destTable, [
            'send_status'       => 'accepted',
            'accepted_at'       => date('Y-m-d H:i:s'),
            'accepted_ip'       => $ip,
            'accepted_user_agent'=> $ua,
        ]);

        // marca no parecer
        $this->db->where('id', $parecer_id)->update($this->table, [
            'accepted_at'                 => date('Y-m-d H:i:s'),
            'accepted_by_destinatario_id' => $destinatario_id,
            'accepted_by_nome'            => $nome,
        ]);
    }

    public function get_outros_destinatarios($parecer_id, $exceto_destinatario_id)
    {
        return $this->db->where('parecer_id', $parecer_id)
                        ->where('id <>', $exceto_destinatario_id)
                        ->get($this->destTable)->result_array();
    }

    public function get_parecer($parecer_id)
    {
        return $this->db->where('id', $parecer_id)->get($this->table)->row_array();
    }

    public function get_destinatario($destinatario_id)
    {
        return $this->db->where('id', $destinatario_id)->get($this->destTable)->row_array();
    }

}
