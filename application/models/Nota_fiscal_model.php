<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Nota_fiscal_model extends App_Model
{
    
    /**
     * Get employee role by id
     * @param  mixed $id Optional role id
     * @return mixed     array if not id passed else object
     */
    public function get($id = '')
    {
        $this->db->order_by('id', 'asc');
        $this->db->where(db_prefix() . 'notafiscal.id', $id);
        return $this->db->get(db_prefix() . 'notafiscal')->result_array();
    }
    
    
    public function get_nota_fiscal_invoice($invoiceid)
    {
         $sql = "SELECT *
                FROM tblnotafiscal 
                where tblnotafiscal.invoice_id = $invoiceid and tblnotafiscal.deleted =  0 order by id desc";
       //  echo $sql; exit;
       $result = $this->db->query($sql)->result_array();
       return $result;
     
    }
    
    
    /**
     * Add new employee role
     * @param mixed $data
     */
    public function add($data)
    {
        
       

        $this->db->insert(db_prefix() . 'notafiscal', $data);
        $insert_id = $this->db->insert_id();

        if ($insert_id) {
            log_activity('New NFCe Added [ID: ' . $insert_id . '.' . $data['ref'] . ']');

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
       
      
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'notafiscal', $data);

        if ($this->db->affected_rows() > 0) {
            $affectedRows++;
        }

      

        if ($affectedRows > 0) {
            log_activity('Nota Fiscal Updated [ID: ' . $id . ', Name: ' . $data['status'] . ']');

            return true;
        }

        return false;
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
    
    
     public function cancela_nota_fiscal($id)
    {
        
        $sql = "SELECT tblnotafiscal.*, tblconta_financeira.token_homologacao, tblconta_financeira.token_producao, tblconta_financeira.token_senha, tblconta_financeira.emissao_producao  FROM tblnotafiscal
               inner join tblconta_financeira on tblconta_financeira.id = tblnotafiscal.conta_id
               where tblnotafiscal.id = $id and tblnotafiscal.deleted = 0 order by tblnotafiscal.id desc";
        $result_nfe = $this->db->query($sql)->row();
          
        
        /* Você deve definir isso globalmente para sua aplicação.
        Para ambiente de produção utilize e a variável abaixo:
         */
       
        // Substituir a variável, ref, pela sua identificação interna de nota.
        $ref                = $result_nfe->ref;
        $token_homologacao  = $result_nfe->token_homologacao;
        $token_producao     = $result_nfe->token_producao;
        $token_senha        = $result_nfe->token_senha;
        $emissao_producao   = $result_nfe->emissao_producao;
        
       
        if($emissao_producao == 1){
            $server = "https://api.focusnfe.com.br";
             $login = $token_producao; // PRODUÇÃO
        }else{
            $server = "https://homologacao.focusnfe.com.br";
            $login = $token_homologacao; // HOMOLOGAÇÃO
        }
        
        
        $justificativa = array ("justificativa" => "cancelamento de nota");
        // Inicia o processo de envio das informações usando o cURL.
       
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $server . "/v2/nfse/" . $ref);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($justificativa));
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, "$login:$token_senha");
        $body = curl_exec($ch);
        $result = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        // As próximas três linhas são um exemplo de como imprimir as informações de retorno da API.
        //print($result."\n");
        //print($body."\n\n");
        //print("");
        
        //curl_close($ch);
        //exit;
        $json2 = json_decode($body, true);
       // $status_sefaz = $json2['status_sefaz'];
       // $mensagem_sefaz = $json2['mensagem_sefaz'];
        $status = $json2['status'];
       // $caminho_xml_cancelamento = $json2['caminho_xml_cancelamento'];
        
      
        if($status == 'cancelado'){
      //      $data_atualiza_pagamento['status_sefaz'] = $status_sefaz;
      //      $data_atualiza_pagamento['mensagem_sefaz'] = $mensagem_sefaz;
      //      $data_atualiza_pagamento['caminho_xml_cancelamento'] = $caminho_xml_cancelamento;
            $data_atualiza_pagamento['status'] = $status;
            $data_atualiza_pagamento['data_cancelamento'] = date('Y-m-d H:i:s');
            $data_atualiza_pagamento['usuario_cancelamento'] = get_staff_user_id();
            $this->update($data_atualiza_pagamento, $id);
           
        }
        
        /* 
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'credits');
        if ($this->db->affected_rows() > 0) {
            $this->update_credit_note_status($credit_id);
            update_invoice_status($invoice_id);
        }
         * 
         */
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
