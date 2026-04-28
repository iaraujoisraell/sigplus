<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Portal_clients_model extends App_Model {

    private $table = null;

    function __construct() {
        $this->table = 'tblclients';
        $this->table_assinatura = 'tblclients_assinatura';
        $this->table_user = 'tblcontacts';
        $this->table_item = 'tblitems';
        $this->table_invoice = 'tblinvoices';
        $this->table_payments = 'tblinvoicepaymentrecords';
        $this->table_nota_fiscal = 'tblnotafiscal';
        $this->table_card_credit = 'tblclients_cartao_credito';
        parent::__construct($this->table);
    }

    //Informações tblclients_assinatura por cpf(Function valida)
    function info_assinatura($cpf = 0) {
        $sql = "SELECT $this->table.*, a.*, $this->table.hash as hash_client
        FROM $this->table
        LEFT JOIN tblclients_assinatura a on a.client_id = $this->table.userid
        WHERE $this->table.deleted=0 and $this->table.active=1 AND $this->table.vat=$cpf order by $this->table.dependente asc";
        //echo $sql; exit;
        return $this->db->query($sql)->row();
    }

    //Informações tblclients_assinatura por id
    function info_assinatura_invoice_item($userid = 0) {
        $sql = "SELECT $this->table.company_id, $this->table.tipo_cliente, $this->table_item.description, $this->table_item.plano_id as tipo_com_sem, $this->table_item.rate, $this->table_item.ciclo,  $this->table_assinatura.*, $this->table_invoice.*, $this->table_invoice.id as id_fatura
        FROM $this->table
        LEFT JOIN $this->table_assinatura on $this->table_assinatura.client_id = $this->table.userid
        LEFT JOIN $this->table_invoice on $this->table_invoice.id = $this->table.fatura_id
        LEFT JOIN $this->table_item on $this->table_item.id = $this->table_invoice.item_id
        WHERE $this->table.deleted=0 and $this->table.active=1 AND $this->table.userid=$userid order by $this->table.dependente asc";
//echo $sql; exit;
        return $this->db->query($sql)->row();
    }

    //Informações tblclients_assinatura e tblclients junto por id
    function info_assinatura_id($userid = 0) {
        $sql = "SELECT $this->table.*, $this->table_assinatura.*, $this->table.hash as hash_client, $this->table.email as email_client, $this->table.datecreated as datecreated_client
        FROM $this->table
        LEFT JOIN $this->table_assinatura on $this->table_assinatura.client_id = $this->table.userid
        WHERE $this->table.deleted=0 and $this->table.active=1 AND $this->table.userid=$userid order by $this->table.dependente asc";
        //echo $sql; exit;
        return $this->db->query($sql)->row();
    }

    //Informações tblcontacts e tblclients por hash
    function info_contacts_hash($hash = 0) {
        $sql = "SELECT $this->table_user.*, $this->table.company, $this->table.hash as hash_client, $this->table.userid as userid_client
        FROM $this->table
        LEFT JOIN $this->table_user on $this->table_user.userid = $this->table.userid
        WHERE $this->table.deleted=0 AND $this->table.hash= '$hash'";
        //echo $sql; exit;
        return $this->db->query($sql)->row();
    }

    //Informações tblcontacts por id
    function registro_contact($client_id = 0) {
        $sql = "SELECT $this->table_user.*
        FROM $this->table_user
        WHERE $this->table_user.userid=$client_id";
        //echo $sql; exit;
        return $this->db->query($sql)->row();
    }

    function is_email_exists($email = '') {
        $sql = "SELECT $this->table_user.*
        FROM $this->table_user
        WHERE $this->table_user.email='$email'";
        //echo $sql; exit;
        return $this->db->query($sql)->row();
    }

    //Count dependentes/funcionários
    function count_dependentes_funcionarios($coluna = '', $userid = 0) {
        $sql = "SELECT count(*) as numero
        FROM $this->table
        WHERE $this->table.$coluna = $userid and $this->table.deleted = 0 and $this->table.active = 1";
        //echo $sql; exit;
        return $this->db->query($sql)->row();
    }

    //Lista dependentes/funcionários
    function lista_dependentes_funcionarios($coluna = '', $userid = 0) {
        $sql = "SELECT $this->table.*, $this->table.datecreated as data_cadastro, $this->table_user.*, $this->table.userid as userid_client, $this->table.email as email_client, $this->table.datecreated as datecreated_client
        FROM $this->table
            LEFT JOIN $this->table_user on $this->table_user.userid = $this->table.userid
        WHERE $this->table.$coluna = $userid and $this->table.deleted = 0 and $this->table.active = 1 ORDER BY company asc";
        //echo $sql; exit;
        return $this->db->query($sql)->result();
    }

    function get_pagamentos($client_id = 0) {

        //
        $sql = "SELECT $this->table_invoice.id, $this->table_invoice.parcela, $this->table_invoice.nex_recuring as data_vencimento, $this->table_payments.date,  $this->table_payments.amount, a.name,  $this->table_nota_fiscal.url "
                . "FROM $this->table_invoice 

        INNER JOIN $this->table_payments on $this->table_payments.invoiceid = $this->table_invoice.id
        INNER JOIN $this->table on $this->table.userid = $this->table_invoice.clientid
        LEFT JOIN $this->table_nota_fiscal on $this->table_nota_fiscal.invoice_id = $this->table_invoice.id
        LEFT JOIN tblpayment_modes a on a.id = $this->table_payments.paymentmode

        where $this->table.userid = $client_id and $this->table_invoice.status = 2 and $this->table_invoice.deleted = 0
        ORDER BY $this->table_invoice.datecreated DESC limit 5";
        ///echo $sql; exit;
        return $this->db->query($sql)->result();
    }

    function get_pagamentos_not($client_id = 0) {
        $hoje = date('Y-m-d');
        $sql = "SELECT $this->table_invoice.id, $this->table_invoice.status, $this->table_invoice.parcela, $this->table_invoice.valor_fatura, $this->table_invoice.nex_recuring as data_vencimento FROM $this->table_invoice
        where $this->table_invoice.clientid=$client_id and $this->table_invoice.status = 1 and $this->table_invoice.deleted = 0 
        ORDER BY $this->table_invoice.datecreated desc";
        //echo $sql; exit;
        return $this->db->query($sql)->result();
    }

    public function savecard($data = array()) {
        if ($data) {

            $blabla = $this->db->insert($this->table, $data);
            print_r($data);
            exit;
            if ($blabla) {
                return true;
            }
        }
    }

    function cards($client_id = 0) {
        $sql = "SELECT * from $this->table_card_credit where $this->table_card_credit.client_id = $client_id and $this->table_card_credit.deleted = 0";
        //echo $sql; exit;
        return $this->db->query($sql)->result();
    }

    function card($client_id = 0) {
        $sql = "SELECT * from $this->table_card_credit where $this->table_card_credit.client_id = $client_id and deleted = 0 and $this->table_card_credit.principal = 1";

        return $this->db->query($sql)->row();
    }

    function get_atual_proxima($client_id = 0, $id_fatura = 0) {
        $sql = "SELECT * FROM $this->table_invoice where clientid = $client_id and id >= $id_fatura limit 2";
        //echo $sql; exit;
        return $this->db->query($sql);
    }

    function get_pagamentos2($client_id = 0) {
        $clients_table = $this->db->dbprefix('clients');
        $payments_table = $this->db->dbprefix('invoice_payments');
        $notas_table = $this->db->dbprefix('nota_fiscal');
        $invoices_table = $this->db->dbprefix('invoices');

        $sql = "SELECT i.id, i.parcela, i.bill_date as data_vencimento, p.payment_date,  p.amount, i.forma_pagamento,  nf.url FROM invoices i

        INNER JOIN invoice_payments p on p.invoice_id = i.id
        INNER JOIN clients c on c.id = i.client_id
        INNER JOIN users u on u.client_id = c.id
        LEFT JOIN nota_fiscal nf on nf.invoice_id = i.id

        where u.id = $client_id and i.status = 'paid' and i.deleted = 0
        ORDER BY i.bill_date DESC ";
        return $this->db->query($sql);
    }

    function get_funcionarios($client_id = 0) {
        $clients_table = $this->db->dbprefix('clients');
        $sql = "SELECT $clients_table.*
        FROM $clients_table
        WHERE $clients_table.deleted=0 AND $clients_table.empresa_id=$client_id
        ORDER BY $clients_table.paciente ASC";

        return $this->db->query($sql);
    }

    public function save_dependente_funcionario($data = null, $id = 0) {
        if ($data) {
            if ($id) {
                $this->db->where('userid', $id);
                if ($this->db->update("tblclients", $data)) {
                    return $id;
                } else {
                    return false;
                }
            } else {
                // print_r($data); exit;
                if ($this->db->insert("tblclients", $data)) {
                    $id_insert = $this->db->insert_id();
                    //echo 'aqui'; exit;
                    return $id_insert;
                } else {
                    return false;
                }
            }
        }
    }
    
    public function save_card($data = null, $id = 0) {
        print_r($data); exit;
        if ($data) {
            if ($id) {
                $this->db->where('id', $id);
                if ($this->db->update("tblclients_cartao_credito", $data)) {
                    return $id;
                } else {
                    return false;
                }
            } else {
                // print_r($data); exit;
                if ($this->db->insert("tblclients_cartao_credito", $data)) {
                    $id_insert = $this->db->insert_id();
                    //echo 'aqui'; exit;
                    return $id_insert;
                } else {
                    return false;
                }
            }
        }
    }

    function insert_hash($data) {
        $this->db->where('userid', $data['userid']);
        $this->db->update('tblclients', $data);
    }

    function is_cpf_exists($cpf = 0) {
      $sql = "SELECT u.userid, c.company, c.nascimento, c.vat, u.email, u.password
      FROM tblclients c

      INNER JOIN tblcontacts u on u.userid = c.userid
      where c.vat='$cpf' and c.deleted = 0";
        //echo $sql; exit;
        return $this->db->query($sql)->row();
    }

    public function addClienteCartaoCredito($data) {
        
        //print_r($data); EXIT;
        $this->db->insert('tblclients_cartao_credito', $data);
        $cartao = $this->db->insert_id();
        return $cartao;
    }
    /* function get_client_user_id() {
      //echo 'aqui'; exit;
      $login_user_id = get_client_user_id();
      //echo $login_user_id; exit;
      return $login_user_id ? $login_user_id : false;
      }




      function get_valida_cnpj($cnpj = 0) {
      $clients_table = $this->db->dbprefix('clients');
      $sql = "SELECT *
      FROM $clients_table
      WHERE deleted=0 AND cnpj = $cnpj";
      return $this->db->query($sql);
      }

      function get_valida_user_id($user_id = 0) {
      $clients_table = $this->db->dbprefix('clients');
      $sql = "SELECT $clients_table.*
      FROM $clients_table
      WHERE $clients_table.deleted=0 AND $clients_table.id=$user_id ";

      return $this->db->query($sql);
      }

      function get_valida_user_id_user($user_id = 0) {
      $clients_table = $this->db->dbprefix('users');
      $sql = "SELECT $clients_table.*
      FROM $clients_table
      WHERE $clients_table.deleted=0 AND $clients_table.client_id=$user_id ";

      return $this->db->query($sql);
      }

      function get_details($options = array()) {
      $clients_table = $this->db->dbprefix('clients');
      $projects_table = $this->db->dbprefix('projects');
      $users_table = $this->db->dbprefix('users');
      $invoices_table = $this->db->dbprefix('invoices');
      $invoice_payments_table = $this->db->dbprefix('invoice_payments');
      $invoice_items_table = $this->db->dbprefix('invoice_items');
      $taxes_table = $this->db->dbprefix('taxes');
      $client_groups_table = $this->db->dbprefix('client_groups');

      $where = "";
      $id = get_array_value($options, "id");
      if ($id) {
      $where = " AND $clients_table.id=$id";
      }


      $group_id = get_array_value($options, "group_id");
      if ($group_id) {
      $where = " AND FIND_IN_SET('$group_id', $clients_table.group_ids)";
      }


      //prepare custom fild binding query

      $this->db->query('SET SQL_BIG_SELECTS=1');

      $sql = "SELECT $clients_table.*, CONCAT($users_table.first_name, ' ', $users_table.last_name) AS primary_contact, $users_table.id AS primary_contact_id, $users_table.image AS contact_avatar,  project_table.total_projects, IFNULL(invoice_details.invoice_value,0) AS invoice_value, IFNULL(invoice_details.payment_received,0) AS payment_received $select_custom_fieds,
      (SELECT GROUP_CONCAT($client_groups_table.title) FROM $client_groups_table WHERE FIND_IN_SET($client_groups_table.id, $clients_table.group_ids)) AS groups
      FROM $clients_table
      LEFT JOIN $users_table ON $users_table.client_id = $clients_table.id AND $users_table.deleted=0 AND $users_table.is_primary_contact=1
      LEFT JOIN (SELECT client_id, COUNT(id) AS total_projects FROM $projects_table WHERE deleted=0 GROUP BY client_id) AS project_table ON project_table.client_id= $clients_table.id
      LEFT JOIN (SELECT client_id, SUM(payments_table.payment_received) as payment_received, $invoice_value_calculation_query as invoice_value FROM $invoices_table
      LEFT JOIN (SELECT $taxes_table.* FROM $taxes_table) AS tax_table ON tax_table.id = $invoices_table.tax_id
      LEFT JOIN (SELECT $taxes_table.* FROM $taxes_table) AS tax_table2 ON tax_table2.id = $invoices_table.tax_id2
      LEFT JOIN (SELECT invoice_id, SUM(amount) AS payment_received FROM $invoice_payments_table WHERE deleted=0 GROUP BY invoice_id) AS payments_table ON payments_table.invoice_id=$invoices_table.id AND $invoices_table.deleted=0 AND $invoices_table.status='not_paid'
      LEFT JOIN (SELECT invoice_id, SUM(total) AS invoice_value FROM $invoice_items_table WHERE deleted=0 GROUP BY invoice_id) AS items_table ON items_table.invoice_id=$invoices_table.id AND $invoices_table.deleted=0 AND $invoices_table.status='not_paid'
      GROUP BY $invoices_table.client_id
      ) AS invoice_details ON invoice_details.client_id= $clients_table.id
      $join_custom_fieds
      WHERE $clients_table.deleted=0 $where";

      return $this->db->query($sql);
      }

      function editar($id) {
      $this->db->where('id', $id);
      return $this->db->get('clients')->result();
      }

      function insert_hash($data) {
      $this->db->where('userid', $data['id']);
      $this->db->update('tblclients', $data);
      }

      function get_dependentes($client_id = 0) {
      $clients_table = $this->db->dbprefix('clients');
      $sql = "SELECT $clients_table.*
      FROM $clients_table
      WHERE $clients_table.deleted=0 AND $clients_table.dependente=$client_id";

      return $this->db->query($sql);
      }

      function get_funcionarios($client_id = 0) {
      $clients_table = $this->db->dbprefix('clients');
      $sql = "SELECT $clients_table.*
      FROM $clients_table
      WHERE $clients_table.deleted=0 AND $clients_table.empresa_id=$client_id
      ORDER BY $clients_table.paciente ASC";

      return $this->db->query($sql);
      }

      function get_info_plano($client_id = 0) {
      $sql = "SELECT ii.total, cp.valor_fatura, c.id, cp.id as id_fatura, cp.plano_id, c.paciente, c.dt_cadastro, pv.title, pv.valor_sem_desconto, c.paciente, cp.item_id, cp.status, cp.next_recurring_date, cp.forma_pagamento, cp.bill_date
      FROM clients c
      INNER JOIN invoices cp on cp.id = c.fatura_id
      INNER JOIN items pv on pv.id = cp.item_id
      INNER JOIN invoice_items ii on ii.invoice_id = cp.id

      where c.id=$client_id and c.deleted = 0";
      return $this->db->query($sql);
      }

      function get_info_plano_for_client_id($client_id = 0) {
      $sql = "SELECT ii.total, c.id, cp.id as id_fatura, cp.plano_id, c.paciente, c.dt_cadastro, pv.title, pv.valor_sem_desconto, c.paciente, cp.item_id, cp.status, cp.next_recurring_date, cp.forma_pagamento, cp.bill_date
      FROM clients c
      INNER JOIN invoices cp on cp.id = c.fatura_id
      INNER JOIN items pv on pv.id = cp.item_id
      INNER JOIN invoice_items ii on ii.invoice_id = cp.id

      where c.id=$client_id and c.deleted = 0";
      return $this->db->query($sql);
      }

      function get_info_plano_funcionario($client_id = 0) {
      $sql = "SELECT ii.total, c.id, cp.id as id_fatura, cp.plano_id, c.paciente, c.dt_cadastro, pv.title, pv.valor_sem_desconto, c.paciente, cp.item_id, cp.status, cp.next_recurring_date, cp.forma_pagamento, cp.bill_date
      FROM clients c
      INNER JOIN invoices cp on cp.id = c.fatura_id
      INNER JOIN items pv on pv.id = cp.item_id
      INNER JOIN invoice_items ii on ii.invoice_id = cp.id

      where c.id=$client_id and c.deleted = 0";
      return $this->db->query($sql);
      }

      function get_info_plano_dependente($client_id = 0) {
      $sql = "SELECT c.id, cp.id as id_fatura, cp.plano_id, c.paciente, c.dt_cadastro, pv.title, pv.valor_sem_desconto,  cp.item_id, cp.status, cp.next_recurring_date, cp.forma_pagamento, cp.bill_date
      FROM clients c
      inner join clients t on t.id = c.dependente
      INNER JOIN invoices cp on cp.id = t.fatura_id
      INNER JOIN items pv on pv.id = cp.item_id

      where c.id=$client_id and c.deleted = 0";
      // echo $sql; exit;
      return $this->db->query($sql);
      }



      function get_details_one($id = 0) {
      $users_table = $this->db->dbprefix('clients');

      $sql = "SELECT * FROM clients
      where id=$id and deleted = 0";
      return $this->db->query($sql);
      } */
}
