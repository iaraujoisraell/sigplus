<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Caixas_model extends App_Model
{
    /**
     * Add new employee role
     * @param mixed $data
     */
    public function add($data)
    {
        $data['datecreated'] = date('Y-m-d H:i:s');
       
        $this->db->insert(db_prefix() . 'medicos', $data);
        $insert_id = $this->db->insert_id();

        if ($insert_id) {
            log_activity('New medico Added [ID: ' . $insert_id . '.' . $data['nome_profissional'] . ']');

            return $insert_id;
        }

        return false;
    }

    /**
     * Update employee role
     * @param  array $data role data
     * @param  mixed $id   role id
     * @return boolean
     */
    public function update($data, $id)
    {
        $affectedRows = 0;
        

        $this->db->where('medicoid', $id);
        $this->db->update(db_prefix() . 'medicos', $data);

        if ($this->db->affected_rows() > 0) {
            $affectedRows++;
        }

        

        if ($affectedRows > 0) {
            log_activity('Medico Updated [ID: ' . $id . ', Name: ' . $data['nome_profissional'] . ']');

            return true;
        }

        return false;
    }
    
    public function get_caixas_transferencia($id = '')
    {
         $sql = "SELECT *
                FROM tblcaixas r
                where r.id != $id";
       //echo $sql; exit;
         
       $result = $this->db->query($sql)->result_array();
        return $result;
    }

    /**
     * Get employee role by id
     * @param  mixed $id Optional role id
     * @return mixed     array if not id passed else object
     */
    public function get($id = '')
    {
        if (is_numeric($id)) {
            $this->db->where(db_prefix() . 'caixas.id', $id);
            $medico = $this->db->get(db_prefix() . 'caixas')->row();
            return $medico;
        }else{

        $this->db->order_by('id', 'asc');
        return $this->db->get(db_prefix() . 'caixas')->result_array();
        
        }
    }
    // dados do caixa que está aberto e vinculado ao usuário atualmente
    public function get_caixa_registro_atual($id = '')
    {
         $sql = "SELECT r.id as id, r.data_abertura, r.usuario_abertura, r.data_fechamento, r.usuario_fechamento, c.name as caixa, r.entrada_caixa, c.id as id_caixa, r.saldo as saldo
                FROM tblcaixas_registros r
                inner join tblcaixas c on c.id = r.caixa_id
                where r.id = $id";
       //echo $sql; exit;
       $result = $this->db->query($sql)->row();
        return $result;
    }
    
    // retorna todos os registros de um caixa
    public function get_caixa_registro_by_id($id = '')
    {
         $sql = "SELECT *
                FROM tblcaixas_registros 
                where caixa_id = $id order by id desc";
       //echo $sql; exit;
       $result = $this->db->query($sql)->result_array();
        return $result;
    }
    
    //MOVIMENTAÇÕES DE UM REGISTRO
    public function get_movimentacoes_by_registro_id($id = '')
    {
         $sql = "SELECT *
                FROM tblcaixas_movimentacao 
                where caixa_registro_id = $id";
    
       $result = $this->db->query($sql)->result_array();
        return $result;
    }
    
    // retorna as despesas registradas pelo caixa atual
    public function get_despesas_caixa_registro_atual($id = '')
    {
        $empresa_id = $this->session->userdata('empresa_id');
        $sql = "SELECT sum(amount) as valor, count(*) as quantidade FROM tblexpenses where caixa_id = $id and empresa_id = $empresa_id ";
       //echo $sql; exit;
       $result = $this->db->query($sql)->row();
        return $result;
    }
    
    // RETORNA O ÚLTIMO REGISTRO DO CAIXA
    public function get_ultimo_registro_caixa($id = '')
    {
         $sql = "SELECT max(id) as id
                FROM tblcaixas_registros 
                where caixa_id = $id";
       //echo $sql; exit;
       $result = $this->db->query($sql)->row();
        return $result;
    }
    
    public function get_saldo_caixa($id = '')
    {
         $sql = "SELECT saldo 
                FROM tblcaixas 
                where id = $id";
       //echo $sql; exit;
       $result = $this->db->query($sql)->row();
        return $result;
    }
    
    public function get_caixa_registro_atual_por_caixa_id($id = '')
    {
         $sql = "SELECT r.id as id, r.caixa_id,  r.data_abertura, r.usuario_abertura, r.data_fechamento, r.usuario_fechamento, c.name as caixa
                FROM tblcaixas_registros r
                inner join tblcaixas c on c.id = r.caixa_id
                where r.id = $id";
      
       $result = $this->db->query($sql)->row();
        return $result;
    }
    
    public function get_qtde_saldo_caixa_atual_por_caixa_id($id = '')
    {
         $sql = "SELECT count(*) as quantidade, sum(p.amount) as valor FROM tblinvoicepaymentrecords p
                inner join tblcaixas_registros r on r.id = p.caixa_id
                inner join tblcaixas c on c.id = r.caixa_id
                where c.id= $id and r.data_fechamento is null and c.aberto = 1 and p.deleted = 0";
       //echo $sql; exit;
       $result = $this->db->query($sql)->row();
        return $result;
    }
    
    /*
     * QUANTIDADE DE PAGAMENTOS RECEBIDOS PELO CAIXA
     */
    public function get_caixa_quantidade_pagamento($id = '')
    {
        $empresa_id = $this->session->userdata('empresa_id');
         $sql = "SELECT count(*) as quantidade, sum(amount) as valor, t.id as tipo_id, t.name as forma_pagamento FROM tblinvoicepaymentrecords r "
                 . " inner join tblpayment_modes m on m.id = r.paymentmode
                     inner join tblpayment_modes_tipo t on t.id = m.tipoid"
                 . " where caixa_id = $id and empresa_id = $empresa_id and r.deleted = 0"
                 . "  group by t.id";
       //echo $sql; exit;
       $result = $this->db->query($sql)->result_array();
        return $result;
    }
    
    
    /*
     * RETORNA OS PAGAMENTOS RECEBIDOS PELO REGISTRO DO CAIXA
     */
    public function get_pagamentos_by_registro_caixa($id = '')
    {
        $empresa_id = $this->session->userdata('empresa_id');
         $sql = " SELECT r.id, r.invoiceid, c.company as cliente, pm.name as modo_pagamento, r.amount as valor, r.amount as saida, cf.nome as conta_financeira, r.date as data_pagamento, r.daterecorded as log, stf.firstname as nome, stf.lastname as sobrenome
                FROM tblinvoicepaymentrecords r
                inner join tblpayment_modes pm on pm.id = r.paymentmode
                inner join tblinvoices i on i.id = r.invoiceid
                inner join tblclients c on c.userid = i.clientid
                inner join tblconta_financeira cf on cf.id = r.conta_id
                inner join tblstaff stf on stf.staffid = r.userid
   
                where r.deleted = 0 and r.caixa_id = $id and r.empresa_id = $empresa_id 
                
                UNION
                SELECT null, null, null, null, valor, null, referencia as conta_financeira, data_registro as data_pagamento,null,null,null
                FROM tblcaixas_movimentacao where caixa_registro_id = $id and empresa_id = $empresa_id and tipo = 1 and referencia != 'PAGAMENTOS RECEBIDOS'
                   
                UNION
                SELECT null, null, null, null, valor,null, referencia as conta_financeira, data_registro as data_pagamento, null,null,null
                FROM tblcaixas_movimentacao where caixa_registro_id = $id and empresa_id = $empresa_id and tipo = 2
                
                UNION
                SELECT null, null, null, null, null, amount as valor_saida, expense_name, date as data_pagamento,null,null, null FROM tblexpenses where caixa_id = $id and empresa_id = $empresa_id 
                
                UNION
                SELECT null, null, null, null,null,valor as valor_repasse, f.nome as conta, data_repasse,null, null,null
                FROM tblcaixas_repasses
                inner join tblconta_financeira f on f.id = tblcaixas_repasses.conta_id
                where registro_id = $id and tblcaixas_repasses.empresa_id = $empresa_id";
         
       //echo $sql; exit;
       $result = $this->db->query($sql)->result_array();
        return $result;
    }
    
    

    /**
     * Delete employee role
     * @param  mixed $id role id
     * @return mixed
     */
    public function delete($id)
    {
        $current = $this->get($id);

        // Check first if role is used in table
        if (is_reference_in_table('role', db_prefix() . 'staff', $id)) {
            return [
                'referenced' => true,
            ];
        }

        $affectedRows = 0;
        $this->db->where('roleid', $id);
        $this->db->delete(db_prefix() . 'roles');

        if ($this->db->affected_rows() > 0) {
            $affectedRows++;
        }

        if ($affectedRows > 0) {
            log_activity('Role Deleted [ID: ' . $id);

            return true;
        }

        return false;
    }

    public function get_contact_permissions($id)
    {
        $this->db->where('userid', $id);

        return $this->db->get(db_prefix() . 'contact_permissions')->result_array();
    }

    public function get_role_staff($role_id)
    {
        $this->db->where('role', $role_id);

        return $this->db->get(db_prefix() . 'staff')->result_array();
    }
}
