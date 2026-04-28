<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Reports extends AdminController
{
    /**
     * Codeigniter Instance
     * Expenses detailed report filters use $ci
     * @var objectsales
     */
    private $ci;

    public function __construct()
    {
        parent::__construct();
        if (!has_permission('reports', '', 'view')) {
            access_denied('reports');
        }
        $this->ci = &get_instance();
        $this->load->model('reports_model');
        $this->load->model('Medicos_model');
        $this->load->model('Convenios_model');
        $this->load->model('Contas_financeiras_model');
        
    }

    /* No access on this url */
    public function index()
    {
        redirect(admin_url());
    }

    /* See knowledge base article reports*/
    public function knowledge_base_articles()
    {
        $this->load->model('knowledge_base_model');
        $data['groups'] = $this->knowledge_base_model->get_kbg();
        $data['title']  = _l('kb_reports');
        $this->load->view('admin/reports/knowledge_base_articles', $data);
    }

    /*
        public function tax_summary(){
           $this->load->model('taxes_model');
           $this->load->model('payments_model');
           $this->load->model('invoices_model');
           $data['taxes'] = $this->db->query("SELECT DISTINCT taxname,taxrate FROM ".db_prefix()."item_tax WHERE rel_type='invoice'")->result_array();
            $this->load->view('admin/reports/tax_summary',$data);
        }*/
    /* Repoert leads conversions */
    public function leads()
    {
        $type = 'leads';
        if ($this->input->get('type')) {
            $type                       = $type . '_' . $this->input->get('type');
            $data['leads_staff_report'] = json_encode($this->reports_model->leads_staff_report());
        }
        $this->load->model('leads_model');
        $data['statuses']               = $this->leads_model->get_status();
        $data['leads_this_week_report'] = json_encode($this->reports_model->leads_this_week_report());
        $data['leads_sources_report']   = json_encode($this->reports_model->leads_sources_report());
        $this->load->view('admin/reports/' . $type, $data);
    }

    /* Sales reportts */
    public function sales()
    {
        $data['mysqlVersion'] = $this->db->query('SELECT VERSION() as version')->row();
        $data['sqlMode']      = $this->db->query('SELECT @@sql_mode as mode')->row();

        if (is_using_multiple_currencies() || is_using_multiple_currencies(db_prefix() . 'creditnotes') || is_using_multiple_currencies(db_prefix() . 'estimates') || is_using_multiple_currencies(db_prefix() . 'proposals')) {
            $this->load->model('currencies_model');
            $data['currencies'] = $this->currencies_model->get();
        }
        $this->load->model('invoices_model');
        $this->load->model('Invoice_items_model');
        $this->load->model('estimates_model');
        $this->load->model('proposals_model');
        $this->load->model('credit_notes_model');

        $data['credit_notes_statuses'] = $this->credit_notes_model->get_statuses();
        $data['invoice_statuses']      = $this->invoices_model->get_statuses();
        $data['estimate_statuses']     = $this->estimates_model->get_statuses();
        $data['payments_years']        = $this->reports_model->get_distinct_payments_years();
        $data['estimates_sale_agents'] = $this->estimates_model->get_sale_agents();

        $data['invoices_sale_agents'] = $this->invoices_model->get_sale_agents();

        $data['proposals_sale_agents'] = $this->proposals_model->get_sale_agents();
        $data['proposals_statuses']    = $this->proposals_model->get_statuses();
        
        $data['medicos']               = $this->Medicos_model->get();
        $data['contas_financeiras']    = $this->Contas_financeiras_model->get();
        $data['convenios']             = $this->Convenios_model->get();
        $data['categorias']            = $this->Invoice_items_model->get_groups();
        $data['procedimentos']         = $this->Invoice_items_model->get_ativos();

        $data['invoice_taxes']     = $this->distinct_taxes('invoice');
        $data['estimate_taxes']    = $this->distinct_taxes('estimate');
        $data['proposal_taxes']    = $this->distinct_taxes('proposal');
        $data['credit_note_taxes'] = $this->distinct_taxes('credit_note');


        $data['title'] = _l('sales_reports');
        $this->load->view('admin/reports/sales', $data);
    }
    
    public function sales_agendamento()
    {
        $data['mysqlVersion'] = $this->db->query('SELECT VERSION() as version')->row();
        $data['sqlMode']      = $this->db->query('SELECT @@sql_mode as mode')->row();

        if (is_using_multiple_currencies() || is_using_multiple_currencies(db_prefix() . 'creditnotes') || is_using_multiple_currencies(db_prefix() . 'estimates') || is_using_multiple_currencies(db_prefix() . 'proposals')) {
            $this->load->model('currencies_model');
            $data['currencies'] = $this->currencies_model->get();
        }
        $this->load->model('invoices_model');
        $this->load->model('Invoice_items_model');
        $this->load->model('estimates_model');
        $this->load->model('proposals_model');
        $this->load->model('credit_notes_model');
        $this->load->model('Tipo_agendamento_model');

        
        $data['medicos']               = $this->Medicos_model->get();
        $data['contas_financeiras']    = $this->Contas_financeiras_model->get();
        $data['convenios']             = $this->Convenios_model->get();
        $data['categorias']            = $this->Invoice_items_model->get_groups();
        $data['procedimentos']         = $this->Invoice_items_model->get_ativos();
        $data['tipo']                  = $this->Tipo_agendamento_model->fetch_tipo_agendamento();
      


        $data['title'] = 'Rel. de Agendamentos';
        $this->load->view('admin/reports/sales_agendamento', $data);
    }
    
    public function sales_tesouraria()
    {
        $data['mysqlVersion'] = $this->db->query('SELECT VERSION() as version')->row();
        $data['sqlMode']      = $this->db->query('SELECT @@sql_mode as mode')->row();

        if (is_using_multiple_currencies() || is_using_multiple_currencies(db_prefix() . 'creditnotes') || is_using_multiple_currencies(db_prefix() . 'estimates') || is_using_multiple_currencies(db_prefix() . 'proposals')) {
            $this->load->model('currencies_model');
            $data['currencies'] = $this->currencies_model->get();
        }
        $this->load->model('invoices_model');
        $this->load->model('Invoice_items_model');
        $this->load->model('estimates_model');
        $this->load->model('proposals_model');
        $this->load->model('credit_notes_model');
        
        $this->load->model('Caixas_model');

        $data['credit_notes_statuses'] = $this->credit_notes_model->get_statuses();
        $data['invoice_statuses']      = $this->invoices_model->get_statuses();
        $data['estimate_statuses']     = $this->estimates_model->get_statuses();
        $data['payments_years']        = $this->reports_model->get_distinct_payments_years();
        $data['estimates_sale_agents'] = $this->estimates_model->get_sale_agents();

        $data['invoices_sale_agents'] = $this->invoices_model->get_sale_agents();

        $data['proposals_sale_agents'] = $this->proposals_model->get_sale_agents();
        $data['proposals_statuses']    = $this->proposals_model->get_statuses();
        
        $data['medicos']               = $this->Medicos_model->get();
        $data['contas_financeiras']    = $this->Contas_financeiras_model->get();
        $data['convenios']             = $this->Convenios_model->get();
        $data['categorias']            = $this->Invoice_items_model->get_groups();
        $data['procedimentos']         = $this->Invoice_items_model->get_ativos();
        $data['caixas']                = $this->Caixas_model->get();
        
        $data['invoice_taxes']     = $this->distinct_taxes('invoice');
        $data['estimate_taxes']    = $this->distinct_taxes('estimate');
        $data['proposal_taxes']    = $this->distinct_taxes('proposal');
        $data['credit_note_taxes'] = $this->distinct_taxes('credit_note');


        $data['title'] = 'Rel. de Tesouraria';
        $this->load->view('admin/reports/sales_tesouraria', $data);
    }
    
    public function sales_faturamento()
    {
       
        $data['mysqlVersion'] = $this->db->query('SELECT VERSION() as version')->row();
        $data['sqlMode']      = $this->db->query('SELECT @@sql_mode as mode')->row();

        if (is_using_multiple_currencies() || is_using_multiple_currencies(db_prefix() . 'creditnotes') || is_using_multiple_currencies(db_prefix() . 'estimates') || is_using_multiple_currencies(db_prefix() . 'proposals')) {
            $this->load->model('currencies_model');
            $data['currencies'] = $this->currencies_model->get();
        }
        
        $this->load->model('invoices_model');
        $this->load->model('Invoice_items_model');
        $this->load->model('estimates_model');
        $this->load->model('proposals_model');
        $this->load->model('credit_notes_model');

        $data['credit_notes_statuses'] = $this->credit_notes_model->get_statuses();
        $data['invoice_statuses']      = $this->invoices_model->get_statuses();
        $data['estimate_statuses']     = $this->estimates_model->get_statuses();
        $data['payments_years']        = $this->reports_model->get_distinct_payments_years();
        $data['estimates_sale_agents'] = $this->estimates_model->get_sale_agents();

        $data['invoices_sale_agents'] = $this->invoices_model->get_sale_agents();

        $data['proposals_sale_agents'] = $this->proposals_model->get_sale_agents();
        $data['proposals_statuses']    = $this->proposals_model->get_statuses();
        
        
        //$member = $this->staff_model->get(get_staff_user_id());
        //$medico_id = $member->medico_id;
       
        //if($medico_id){
         //   $medicos            = $this->Medicos_model->get($medico_id);
         //   $data['medicos']    = $medicos;           
        //}else{
           
        //}
        
         $data['medicos']               = $this->Medicos_model->get();
         
        $data['contas_financeiras']    = $this->Contas_financeiras_model->get();
        $data['convenios']             = $this->Convenios_model->get();
        $data['categorias']            = $this->Invoice_items_model->get_groups();
        $data['procedimentos']         = $this->Invoice_items_model->get_ativos();

        $data['invoice_taxes']     = $this->distinct_taxes('invoice');
        $data['estimate_taxes']    = $this->distinct_taxes('estimate');
        $data['proposal_taxes']    = $this->distinct_taxes('proposal');
        $data['credit_note_taxes'] = $this->distinct_taxes('credit_note');


        $data['title'] = 'Rel. Faturamento';
        $this->load->view('admin/reports/sales_faturamento', $data);
    }

    /* Customer report */
    public function customers_report()
    {
        if ($this->input->is_ajax_request()) {
            $this->load->model('currencies_model');
            $select = [
                get_sql_select_client_company(),
                '(SELECT COUNT(clientid) FROM ' . db_prefix() . 'invoices WHERE ' . db_prefix() . 'invoices.clientid = ' . db_prefix() . 'clients.userid AND status != 5)',
                '(SELECT SUM(subtotal) - SUM(discount_total) FROM ' . db_prefix() . 'invoices WHERE ' . db_prefix() . 'invoices.clientid = ' . db_prefix() . 'clients.userid AND status != 5)',
                '(SELECT SUM(total) FROM ' . db_prefix() . 'invoices WHERE ' . db_prefix() . 'invoices.clientid = ' . db_prefix() . 'clients.userid AND status != 5)',
            ];

            $custom_date_select = $this->get_where_report_period();
            if ($custom_date_select != '') {
                $i = 0;
                foreach ($select as $_select) {
                    if ($i !== 0) {
                        $_temp = substr($_select, 0, -1);
                        $_temp .= ' ' . $custom_date_select . ')';
                        $select[$i] = $_temp;
                    }
                    $i++;
                }
            }
            $by_currency = $this->input->post('report_currency');
            $currency    = $this->currencies_model->get_base_currency();
            if ($by_currency) {
                $i = 0;
                foreach ($select as $_select) {
                    if ($i !== 0) {
                        $_temp = substr($_select, 0, -1);
                        $_temp .= ' AND currency =' . $this->db->escape_str($by_currency) . ')';
                        $select[$i] = $_temp;
                    }
                    $i++;
                }
                $currency = $this->currencies_model->get($by_currency);
            }
            $aColumns     = $select;
            $sIndexColumn = 'userid';
            $sTable       = db_prefix() . 'clients';
            $where        = [];

            $result = data_tables_init($aColumns, $sIndexColumn, $sTable, [], $where, [
                'userid',
            ]);
            $output  = $result['output'];
            $rResult = $result['rResult'];
            $x       = 0;
            foreach ($rResult as $aRow) {
                $row = [];
                for ($i = 0; $i < count($aColumns); $i++) {
                    if (strpos($aColumns[$i], 'as') !== false && !isset($aRow[$aColumns[$i]])) {
                        $_data = $aRow[strafter($aColumns[$i], 'as ')];
                    } else {
                        $_data = $aRow[$aColumns[$i]];
                    }
                    if ($i == 0) {
                        $_data = '<a href="' . admin_url('clients/client/' . $aRow['userid']) . '" target="_blank">' . $aRow['company'] . '</a>';
                    } elseif ($aColumns[$i] == $select[2] || $aColumns[$i] == $select[3]) {
                        if ($_data == null) {
                            $_data = 0;
                        }
                        $_data = app_format_money($_data, $currency->name);
                    }
                    $row[] = $_data;
                }
                $output['aaData'][] = $row;
                $x++;
            }
            echo json_encode($output);
            die();
        }
    }
    
    public function faturamento_report()
    {
         if ($this->input->is_ajax_request()) {
            $this->load->model('currencies_model');
            $v = $this->db->query('SELECT VERSION() as version')->row();
            // 5.6 mysql version don't have the ANY_VALUE function implemented.

           /* if ($v && strpos($v->version, '5.7') !== false) {
                $aColumns = [
                        'ANY_VALUE('.db_prefix().'clients.company) as cliente',
                        'ANY_VALUE((SUM(' . db_prefix() . 'itemable.qty))) as quantity_sold',
                        'ANY_VALUE(SUM(rate*qty)) as rate',
                        'ANY_VALUE(AVG(rate*qty)) as avg_price',
                    ];
            } else { */
            $custom_date_select = $this->get_where_report_period(db_prefix() . 'invoicepaymentrecords.date');
            $custom_date_select_diferente = $this->get_where_report_period_diferente(db_prefix() . 'invoicepaymentrecords.date');
            
            $aColumns = [
                        'number',
                        db_prefix().'invoices.codigo_atendimento as codigo_atendimento',
                        db_prefix().'invoices.registro as registro',
                
                        db_prefix() . 'medicos.nome_profissional as medico',
                        db_prefix() . 'centrocusto.nome as centro_custo', 
                        db_prefix() . 'convenio.name as convenio',
                        get_sql_select_client_company(),
                        db_prefix().'items_groups.name as categoria',
                        db_prefix().'items.description as procedimento',
                        db_prefix().'invoices.date as data_faturamento',
                        db_prefix().'invoicepaymentrecords.date as data_pagamento',
                        db_prefix().'itemable.qty as quantity_sold',
                        db_prefix() . 'itemable.rate as valor_procedimento', // preço unitário
                        // TOTAL FATURADO
                        db_prefix() . 'itemable.rate * ' . db_prefix() . 'itemable.qty as rate_total', // total faturado
                        //DESCONTO FATURA
                        db_prefix() .'itemable.desconto_valor as desconto', // desconto fatura
                        // AJUSTES NA FATURA
                        "(adjustment / (select count(*) as total from tblitemable t where t.rel_id = tblinvoices.id and rel_type = 'invoice' and t.medicoid = tblmedicos.medicoid)) as ajuste", // ajustes da fatura
                        // CRÉDITOS FATURA
                        "((SELECT COALESCE(SUM(rate),0) * COALESCE(qty,1) FROM tblcredits
                            inner join tblitemable on tblitemable.rel_id = tblcredits.credit_id
                            WHERE tblcredits.invoice_id=tblinvoices.id and tblitemable.medicoid = tblmedicos.medicoid and tblitemable.rel_type = 'credit_note')/ (select count(*) as total from tblitemable t where t.rel_id = tblinvoices.id and rel_type = 'invoice' and t.medicoid = tblmedicos.medicoid)) as credits_applied", // CRÉDITO APLICADO
                        // VALOR ABERTO
                        "(SELECT total - (SELECT COALESCE(SUM(amount),0) FROM tblinvoicepaymentrecords WHERE invoiceid = tblinvoices.id) - (SELECT COALESCE(SUM(amount),0) FROM tblcredits WHERE tblcredits.invoice_id=tblinvoices.id)) as valor_aberto",
                        // VALOR FATURADO RECEBIDO
                         
                         "tblitemable.valor_tesouraria as valor_tesouraria", 
                
                        
                        "(SELECT COALESCE(SUM(amount),0)
                         FROM tblinvoicepaymentrecords
                        
                         WHERE  invoiceid = tblinvoices.id $custom_date_select_diferente ) as valor_pgto_antes",        
                                
                         "tblinvoices.datecreated as datecreated,
                        
                        tblitemable.valor_medico as valor_medico,
                        tblinvoices.addedfrom as addedfrom ",
                    
                        
                        
                    ];
           // }
            /*
             * ((tblitemable.rate * tblitemable.qty) 
                        - tblitemable.desconto_valor           
                        + (adjustment / (select count(*) as total from tblitemable t where t.rel_id = tblinvoices.id and rel_type = 'invoice' and t.medicoid = tblmedicos.medicoid)) 
      
                        - (SELECT COALESCE(SUM(tblitemable.rate),0) * COALESCE(tblitemable.qty,1) FROM tblcredits
                          inner join tblitemable on tblitemable.rel_id = tblcredits.credit_id WHERE tblcredits.invoice_id=tblinvoices.id and tblitemable.medicoid = tblmedicos.medicoid
                          and tblitemable.rel_type = 'credit_note')
                         / COALESCE((select count(*) as total from tblitemable t where t.rel_id = tblinvoices.id and rel_type = 'invoice' and t.medicoid = tblmedicos.medicoid))  

    
                        - (SELECT total   - (SELECT COALESCE(SUM(amount),0) FROM tblinvoicepaymentrecords 
                                left join tblconta_financeira on tblconta_financeira.id = tblinvoicepaymentrecords.conta_id
                                WHERE invoiceid = tblinvoices.id AND tblconta_financeira.nota_fiscal_propria = 0  $custom_date_select ) 
                        - (select COALESCE(SUM(tblitemable.rate),0) * COALESCE(tblitemable.qty,1)  from tblitemable where tblitemable.rel_id = tblinvoices.id and rel_type = 'invoice' and tblitemable.medicoid != tblmedicos.medicoid)
                        - (SELECT COALESCE(SUM(tblitemable.rate),0) * COALESCE(tblitemable.qty,1) FROM tblcredits inner join tblitemable on tblitemable.rel_id = tblcredits.credit_id WHERE tblcredits.invoice_id=tblinvoices.id and tblitemable.medicoid = tblmedicos.medicoid and rel_type = 'credit_note' )
                          )) as valor_faturado_produzido, 
             */
            $sIndexColumn = 'id';
            $sTable       = db_prefix() . 'invoices';
            $join         = [
                'JOIN ' . db_prefix() . 'clients ON ' . db_prefix() . 'clients.userid = ' . db_prefix() . 'invoices.clientid',
                'JOIN ' . db_prefix() . 'itemable ON ' . db_prefix() . 'itemable.rel_id = ' . db_prefix() . 'invoices.id AND '. db_prefix() . 'itemable.rel_type="invoice"',
                'JOIN ' . db_prefix() . 'items ON ' . db_prefix() . 'items.id = ' . db_prefix() . 'itemable.item_id',
                'JOIN ' . db_prefix() . 'items_groups ON ' . db_prefix() . 'items_groups.id = ' . db_prefix() . 'items.group_id',                
                'LEFT JOIN ' . db_prefix() . 'convenio ON ' . db_prefix() . 'convenio.id = ' . db_prefix() . 'invoices.convenio',
                'LEFT JOIN '. db_prefix() .'centrocusto ON ' . db_prefix() . 'centrocusto.ID = ' .db_prefix(). 'invoices.centrocustoid',
                'JOIN ' . db_prefix() . 'medicos ON ' . db_prefix() . 'medicos.medicoid = ' . db_prefix() . 'itemable.medicoid',
                'LEFT JOIN ' . db_prefix() . 'invoicepaymentrecords ON ' . db_prefix() . 'invoicepaymentrecords.invoiceid = ' . db_prefix() . 'invoices.id',
              //  'JOIN ' . db_prefix() . 'conta_financeira ON ' . db_prefix() . 'conta_financeira.id = ' . db_prefix() . 'invoicepaymentrecords.conta_id',

            ];
            
            
            $where = [' AND '.db_prefix() . 'invoices.status != 5',  ' AND '.db_prefix() . 'invoices.status IN (2)', 'AND tblinvoices.deleted = 0']; //,    fat pagas

            
            if ($custom_date_select != '') {
                array_push($where, $custom_date_select);
            }
            $by_currency = $this->input->post('report_currency');
            if ($by_currency) {
                $currency = $this->currencies_model->get($by_currency);
                array_push($where, 'AND currency=' . $this->db->escape_str($by_currency));
            } else {
                $currency = $this->currencies_model->get_base_currency();
            }

            if ($this->input->post('sale_agent_items')) {
                $agents  = $this->input->post('sale_agent_items');
                $_agents = [];
                if (is_array($agents)) {
                    foreach ($agents as $agent) {
                        if ($agent != '') {
                            array_push($_agents, $this->db->escape_str($agent));
                        }
                    }
                }
                if (count($_agents) > 0) {
                    array_push($where, 'AND sale_agent IN (' . implode(', ', $_agents) . ')');
                }
            }
            
            // FILTRO MÉDICO
            if ($this->input->post('medicos_faturamento')) {
                $medicos = $this->input->post('medicos_faturamento');
                $_medicos = [];
                if (is_array($medicos)) {
                    foreach ($medicos as $medico) {
                        if ($medico != '') {
                            array_push($_medicos, $this->db->escape_str($medico));
                        }
                    }
                }
                if (count($_medicos) > 0) {
                    array_push($where, ' AND '. db_prefix() . 'itemable.medicoid IN (' . implode(', ', $_medicos) . ')');
                }
            }
            
            // FILTRO CONVÊNIOS
            if ($this->input->post('convenios_faturamento')) {
                $convenios = $this->input->post('convenios_faturamento');
               
                $_convenios = [];
                if (is_array($convenios)) {
                    foreach ($convenios as $convenio) {
                        if ($convenio != '') {
                            array_push($_convenios, $this->db->escape_str($convenio));
                        }
                    }
                }
                if (count($_convenios) > 0) {
                    array_push($where, ' AND '. db_prefix() . 'invoices.convenio IN (' . implode(', ', $_convenios) . ')');
                }
            }
            
            // FILTRO CATEGORIAS
            if ($this->input->post('categorias_faturamento')) {
                $categorias = $this->input->post('categorias_faturamento');
               
                $_categorias = [];
                if (is_array($categorias)) {
                    foreach ($categorias as $categoriaa) {
                        if ($categoriaa != '') {
                            array_push($_categorias, $this->db->escape_str($categoriaa));
                        }
                    }
                }
                if (count($_categorias) > 0) {
                    array_push($where, ' AND '. db_prefix() . 'items_groups.id IN (' . implode(', ', $_categorias) . ')');
                }
            }
            
            //
            if ($this->input->post('procedimento_fatura')) {
                $procedimentos = $this->input->post('procedimento_fatura');
                // print_r($procedimentos); exit;
                $_procedimentos = [];
                if (is_array($procedimentos)) {
                    foreach ($procedimentos as $categoriaa) {
                        if ($categoriaa != '') {
                            array_push($_procedimentos, $this->db->escape_str($categoriaa));
                        }
                    }
                }
                if (count($_procedimentos) > 0) {
                    array_push($where, ' AND '. db_prefix() . 'items.id IN (' . implode(', ', $_procedimentos) . ')');
                }
            }
            
            $totalPaymentsColumnIndex = (17);

            $currency                          = $this->currencies_model->get_base_currency();
            $select[$totalPaymentsColumnIndex] = $select[$totalPaymentsColumnIndex] .= ' as amount_open';
        
            $GroupBy = ' GROUP BY tblinvoices.id, tblitemable.id';
            $orderBy = ' ORDER BY tblinvoicepaymentrecords.date DESC';
            $result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [],$GroupBy);
            //  $result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [], 'GROUP by ' . db_prefix() . 'invoicepaymentrecords.invoiceid');   EXEMPLO COM GROUP BY
            $output  = $result['output'];
            $rResult = $result['rResult'];

            $footer_data = [
                'total_amount' => 0,
                'total_qty'    => 0,
                'desconto'    => 0,
                'rate_total'    => 0,
               'total'           => 0,
                'subtotal'        => 0,
                'total_tax'       => 0,
                'discount_total'  => 0,
                'adjustment'      => 0,
                'applied_credits' => 0,
                'amount_open'     => 0,
                'valor_medico'     => 0,
                'valor_faturado_produzido'     => 0,
              
            ];

            foreach ($rResult as $aRow) {
                $row = [];
                
                $valor_pgto_antes = $aRow['valor_pgto_antes'];
                
                if($valor_pgto_antes > 0){
                    $info = "<i class='fa fa-exclamation-circle' ></i>";
                }else{
                    $info = "";
                }
                
                $_data_invoice = '<a href="' . admin_url('invoices/list_invoices/' . $aRow['number']) . '" target="_blank">' . format_invoice_number($aRow['number']) . '</a> '.' '." $info";
                $row[] = $_data_invoice;
                
                $registro = "";
                if($aRow['registro']){
                    $registro = $aRow['registro'];
                }else{
                    $registro = $aRow['codigo_atendimento'];
                }
                $row[] = $registro;
                
                $row[] = $aRow['medico'];
                $row[] = $aRow['centro_custo'];
                $row[] = $aRow['convenio'];
                $row[] = $aRow['company'];
                $row[] = $aRow['categoria'];
                $row[] = $aRow['procedimento'];
                $row[] = $aRow['data_faturamento'];
                $row[] = $aRow['data_pagamento'];
                $row[] = $aRow['quantity_sold'];
                $footer_data['total_qty'] += $aRow['quantity_sold']; // quantidade
                //$row[] = app_format_money($aRow['valor_procedimento'], $currency->name);
                $row[] = $aRow['valor_procedimento'];
                
                $footer_data['valor_procedimento'] += $aRow['valor_procedimento']; // valor procedimento
                
                //$row[] = app_format_money($aRow['desconto'], $currency->name); // créditos
                $row[] = $aRow['desconto']; // créditos
                $footer_data['desconto'] += $aRow['desconto'];  
                
                $fat_total = $aRow['rate_total'] - $aRow['desconto'];
                //$row[] = app_format_money($fat_total, $currency->name);
                $row[] = $fat_total;
                $footer_data['rate_total'] += $aRow['rate_total']; // desconto procedimento
               
                 
                
                //$row[] = app_format_money($aRow['valor_tesouraria'], $currency->name); // créditos
                $row[] = $aRow['valor_tesouraria']; // créditos
                $footer_data['valor_faturado_produzido'] += $aRow['valor_tesouraria'];  
                //$row[] = app_format_money($aRow['valor_medico'], $currency->name); // créditos
                $row[] = $aRow['valor_medico']; // créditos
                $footer_data['valor_medico'] += $aRow['valor_medico'];//valor_medico
                $usuario = get_staff_full_name($aRow['addedfrom']);
                $log = $usuario.' <br> '.$aRow['datecreated'];
                $row[] = $log; // créditos
                
               
                
                $output['aaData'][] = $row;
            }

           // $footer_data['total_amount'] = app_format_money($footer_data['total_amount'], $currency->name);
           // $footer_data['desconto_amount'] = app_format_money($footer_data['desconto_amount'], $currency->name);
           // $footer_data['amount_total_procedimento'] = app_format_money($footer_data['amount_total_procedimento'], $currency->name);

            foreach ($footer_data as $key => $total) {
                
                $footer_data[$key] = app_format_money($total, $currency->name);
            }
            
            $output['sums'] = $footer_data;
            echo json_encode($output);
            die();
        }
    }
    
    public function custo_report()
    {
         if ($this->input->is_ajax_request()) {
            $this->load->model('currencies_model');
            $this->load->model('nota_fiscal_model');
            $this->load->model('payments_model');
            $this->load->model('invoices_model');
            $this->load->model('medicos_model'); 
             $this->load->model('contas_financeiras_model');
            
            $v = $this->db->query('SELECT VERSION() as version')->row();
            // 5.6 mysql version don't have the ANY_VALUE function implemented.

           /* if ($v && strpos($v->version, '5.7') !== false) {
                $aColumns = [
                        'ANY_VALUE('.db_prefix().'clients.company) as cliente',
                        'ANY_VALUE((SUM(' . db_prefix() . 'itemable.qty))) as quantity_sold',
                        'ANY_VALUE(SUM(rate*qty)) as rate',
                        'ANY_VALUE(AVG(rate*qty)) as avg_price',
                    ];
            } else { */
            $custom_date_select = $this->get_where_report_period(db_prefix() . 'invoicepaymentrecords.date');
            $custom_date_select_diferente = $this->get_where_report_period_diferente(db_prefix() . 'invoicepaymentrecords.date');
            
            $aColumns = [
                        'number',
                        db_prefix() . 'medicos.nome_profissional as medico',
                        db_prefix() . 'centrocusto.nome as centro_custo', 
                        db_prefix() . 'convenio.name as convenio',
                        get_sql_select_client_company(),
                        db_prefix().'items_groups.name as categoria',
                        db_prefix().'items.description as procedimento',
                        db_prefix().'invoices.id as invoice_id',
                        db_prefix().'invoices.total as total',
                        db_prefix().'invoices.date as data_faturamento',
                        db_prefix().'invoicepaymentrecords.date as data_pagamento',
                        db_prefix().'itemable.qty as quantity_sold',
                        db_prefix() . 'itemable.rate as valor_procedimento', // preço unitário
                        // TOTAL FATURADO
                        db_prefix() . 'itemable.rate * ' . db_prefix() . 'itemable.qty as rate_total', // total faturado
                        //DESCONTO FATURA
                        db_prefix() .'itemable.desconto_valor as desconto', // desconto fatura
                        //VALOR C DESCONTO FATURA
                        db_prefix() .'itemable.rate_desconto as valor_c_desconto', // desconto fatura
              
                   
                        // VALOR FATURADO RECEBIDO
                         "tblitemable.valor_tesouraria as valor_tesouraria", 
                        "(SELECT COALESCE(SUM(amount),0)
                         FROM tblinvoicepaymentrecords
                         WHERE  invoiceid = tblinvoices.id $custom_date_select_diferente ) as valor_pgto_antes",        
                         "tblinvoices.datecreated as datecreated,
                        tblitemable.valor_medico as valor_medico,
                       
                        tblinvoicepaymentrecords.numero_nf as numero_nf_manual,
                        tblcaixas.name as caixa,
                        tblinvoices.addedfrom as addedfrom ",
                    
                        
                        
                    ];
           // }
            /*
             * ((tblitemable.rate * tblitemable.qty) 
                        - tblitemable.desconto_valor           
                        + (adjustment / (select count(*) as total from tblitemable t where t.rel_id = tblinvoices.id and rel_type = 'invoice' and t.medicoid = tblmedicos.medicoid)) 
      
                        - (SELECT COALESCE(SUM(tblitemable.rate),0) * COALESCE(tblitemable.qty,1) FROM tblcredits
                          inner join tblitemable on tblitemable.rel_id = tblcredits.credit_id WHERE tblcredits.invoice_id=tblinvoices.id and tblitemable.medicoid = tblmedicos.medicoid
                          and tblitemable.rel_type = 'credit_note')
                         / COALESCE((select count(*) as total from tblitemable t where t.rel_id = tblinvoices.id and rel_type = 'invoice' and t.medicoid = tblmedicos.medicoid))  

    
                        - (SELECT total   - (SELECT COALESCE(SUM(amount),0) FROM tblinvoicepaymentrecords 
                                left join tblconta_financeira on tblconta_financeira.id = tblinvoicepaymentrecords.conta_id
                                WHERE invoiceid = tblinvoices.id AND tblconta_financeira.nota_fiscal_propria = 0  $custom_date_select ) 
                        - (select COALESCE(SUM(tblitemable.rate),0) * COALESCE(tblitemable.qty,1)  from tblitemable where tblitemable.rel_id = tblinvoices.id and rel_type = 'invoice' and tblitemable.medicoid != tblmedicos.medicoid)
                        - (SELECT COALESCE(SUM(tblitemable.rate),0) * COALESCE(tblitemable.qty,1) FROM tblcredits inner join tblitemable on tblitemable.rel_id = tblcredits.credit_id WHERE tblcredits.invoice_id=tblinvoices.id and tblitemable.medicoid = tblmedicos.medicoid and rel_type = 'credit_note' )
                          )) as valor_faturado_produzido, 
             */
            $sIndexColumn = 'id';
            $sTable       = db_prefix() . 'invoices';
            $join         = [
                'JOIN ' . db_prefix() . 'clients ON ' . db_prefix() . 'clients.userid = ' . db_prefix() . 'invoices.clientid',
                'JOIN ' . db_prefix() . 'itemable ON ' . db_prefix() . 'itemable.rel_id = ' . db_prefix() . 'invoices.id AND '. db_prefix() . 'itemable.rel_type="invoice"',
                'JOIN ' . db_prefix() . 'items ON ' . db_prefix() . 'items.id = ' . db_prefix() . 'itemable.item_id',
                'JOIN ' . db_prefix() . 'items_groups ON ' . db_prefix() . 'items_groups.id = ' . db_prefix() . 'items.group_id',                
                'LEFT JOIN ' . db_prefix() . 'convenio ON ' . db_prefix() . 'convenio.id = ' . db_prefix() . 'invoices.convenio',
                'LEFT JOIN '. db_prefix() .'centrocusto ON ' . db_prefix() . 'centrocusto.ID = ' .db_prefix(). 'invoices.centrocustoid',
                'JOIN ' . db_prefix() . 'medicos ON ' . db_prefix() . 'medicos.medicoid = ' . db_prefix() . 'itemable.medicoid',
                'LEFT JOIN ' . db_prefix() . 'invoicepaymentrecords ON ' . db_prefix() . 'invoicepaymentrecords.invoiceid = ' . db_prefix() . 'invoices.id',
              //  'JOIN ' . db_prefix() . 'conta_financeira ON ' . db_prefix() . 'conta_financeira.id = ' . db_prefix() . 'invoicepaymentrecords.conta_id',
                'LEFT JOIN ' . db_prefix() . 'caixas_registros ON ' . db_prefix() . 'caixas_registros.id = ' . db_prefix() . 'invoicepaymentrecords.caixa_id',
                
                'LEFT JOIN ' . db_prefix() . 'caixas ON ' . db_prefix() . 'caixas.id = ' . db_prefix() . 'caixas_registros.caixa_id'

            ];
            
            
            $where = [' AND '.db_prefix() . 'invoices.status != 5',  ' AND '.db_prefix() . 'invoices.status IN (2, 3)', 'AND tblinvoices.deleted = 0']; //,    fat pagas

            
            if ($custom_date_select != '') {
                array_push($where, $custom_date_select);
            }
            $by_currency = $this->input->post('report_currency');
            if ($by_currency) {
                $currency = $this->currencies_model->get($by_currency);
                array_push($where, 'AND currency=' . $this->db->escape_str($by_currency));
            } else {
                $currency = $this->currencies_model->get_base_currency();
            }

            if ($this->input->post('sale_agent_items')) {
                $agents  = $this->input->post('sale_agent_items');
                $_agents = [];
                if (is_array($agents)) {
                    foreach ($agents as $agent) {
                        if ($agent != '') {
                            array_push($_agents, $this->db->escape_str($agent));
                        }
                    }
                }
                if (count($_agents) > 0) {
                    array_push($where, 'AND sale_agent IN (' . implode(', ', $_agents) . ')');
                }
            }
            
            // FILTRO MÉDICO
            if ($this->input->post('medicos_custo')) {
                $medicos = $this->input->post('medicos_custo');
                $_medicos = [];
                if (is_array($medicos)) {
                    foreach ($medicos as $medico) {
                        if ($medico != '') {
                            array_push($_medicos, $this->db->escape_str($medico));
                        }
                    }
                }
                if (count($_medicos) > 0) {
                    array_push($where, ' AND '. db_prefix() . 'itemable.medicoid IN (' . implode(', ', $_medicos) . ')');
                }
            }
            
            // FILTRO CONVÊNIOS
            if ($this->input->post('convenios_custo')) {
                $convenios = $this->input->post('convenios_custo');
               
                $_convenios = [];
                if (is_array($convenios)) {
                    foreach ($convenios as $convenio) {
                        if ($convenio != '') {
                            array_push($_convenios, $this->db->escape_str($convenio));
                        }
                    }
                }
                if (count($_convenios) > 0) {
                    array_push($where, ' AND '. db_prefix() . 'invoices.convenio IN (' . implode(', ', $_convenios) . ')');
                }
            }
            
            // FILTRO CATEGORIAS
            if ($this->input->post('categorias_custo')) {
                $categorias = $this->input->post('categorias_custo');
               
                $_categorias = [];
                if (is_array($categorias)) {
                    foreach ($categorias as $categoriaa) {
                        if ($categoriaa != '') {
                            array_push($_categorias, $this->db->escape_str($categoriaa));
                        }
                    }
                }
                if (count($_categorias) > 0) {
                    array_push($where, ' AND '. db_prefix() . 'items_groups.id IN (' . implode(', ', $_categorias) . ')');
                }
            }
            
            //
            if ($this->input->post('procedimento_custo')) {
                $procedimentos = $this->input->post('procedimento_custo');
                // print_r($procedimentos); exit;
                $_procedimentos = [];
                if (is_array($procedimentos)) {
                    foreach ($procedimentos as $categoriaa) {
                        if ($categoriaa != '') {
                            array_push($_procedimentos, $this->db->escape_str($categoriaa));
                        }
                    }
                }
                if (count($_procedimentos) > 0) {
                    array_push($where, ' AND '. db_prefix() . 'items.id IN (' . implode(', ', $_procedimentos) . ')');
                }
            }
            
            $totalPaymentsColumnIndex = (20);

           
            
            $currency                          = $this->currencies_model->get_base_currency();
            $select[$totalPaymentsColumnIndex] = $select[$totalPaymentsColumnIndex] .= ' as amount_open';
        
            $GroupBy = ' GROUP BY tblinvoices.id'; // , tblitemable.id
            $orderBy = ' ORDER BY tblinvoicepaymentrecords.date DESC';
            $result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [],$GroupBy);
            //  $result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [], 'GROUP by ' . db_prefix() . 'invoicepaymentrecords.invoiceid');   EXEMPLO COM GROUP BY
            $output  = $result['output'];
            $rResult = $result['rResult'];

            $footer_data = [
                'total_amount' => 0,
                'total_qty'    => 0,
                'desconto'    => 0,
                'rate_total'    => 0,
               'total'           => 0,
                'subtotal'        => 0,
                'total_tax'       => 0,
                'discount_total'  => 0,
                'adjustment'      => 0,
                'applied_credits' => 0,
                'amount_open'     => 0,
                'valor_medico'     => 0,
                'valor_faturado_produzido'     => 0,
              
            ];
            

            foreach ($rResult as $aRow) {
                $row = [];
                
                $valor_pgto_antes = $aRow['valor_pgto_antes'];
                
                if($valor_pgto_antes > 0){
                    $info = "<i class='fa fa-exclamation-circle' ></i>";
                }else{
                    $info = "";
                }
                
                $_data_invoice = '<a href="' . admin_url('invoices/list_invoices/' . $aRow['number']) . '" target="_blank">' . format_invoice_number($aRow['number']) . '</a> '.' '." $info";
                $row[] = $_data_invoice;
                $row[] = $aRow['data_faturamento'];
                $row[] = $aRow['medico'];
                $row[] = $aRow['centro_custo'];
                $row[] = $aRow['convenio'];
                $row[] = $aRow['company'];
                $row[] = $aRow['categoria'];
                
                
                $invoice_id = $aRow['invoice_id']; 
                $dados_invoices   = $this->invoices_model->get($invoice_id);  
                
                $table = '<table style="background-color: #ADD8E6" class="table " >';
                $table .= '<tr>';
                $table .= '<td> <font style = "font-size: 12px"> Médico</font> </td>';
                $table .= '<td> <font style = "font-size: 12px">Qtde</font> </td>';
                $table .= '<td> <font style = "font-size: 12px">Procedimento</font> </td>';
                $table .= '<td> <font style = "font-size: 12px">Valor</font> </td>';
                $table .= '</tr>';
                $soma_valor_procedimentos = 0;
                $procedimentos = $dados_invoices->items;
                foreach ($procedimentos as $proc) {
                    $procedimento = $proc['description'];
                    $qty = $proc['qty'];
                    $rate = $proc['rate'];
                    $rate_desconto = $proc['rate_desconto'];
                    $medicoid = $proc['medicoid'];
                    $soma_valor_procedimentos += $rate_desconto;
                    $dados_medico   = $this->medicos_model->get($medicoid); 
                    $nome_reduzido = $dados_medico->nome_reduzido;
                    $nome_profissional = $dados_medico->nome_profissional;
                    
                    $table .= '<tr>';
                    
                    
                    $table .= '<td title="'.$nome_profissional.'"> <font style = "font-size: 10px">  '.$nome_reduzido.' </font></td>';
                    $table .= '<td> <font style = "font-size: 10px">'.$qty.' </font></td>';
                    $table .= '<td> <font style = "font-size: 10px">'.$procedimento.'</font> </td>';
                    $table .= '<td> <font style = "font-size: 10px">'.app_format_money($rate_desconto, $currency->name).' </font></td>';
                    
                    
                    $table .= '</tr>';
                    
                }
                
                $table .= '<tr style="background-color: #87CEEB">';
                $table .= '<td> <font style = "font-size: 14px"> Total</font> </td>';
                $table .= '<td> <font style = "font-size: 10px"></font> </td>';
                $table .= '<td> <font style = "font-size: 10px"></font> </td>';
                $table .= '<td> <font style = "font-size: 14px">'.app_format_money($soma_valor_procedimentos, $currency->name).'</font> </td>';
                $table .= '</tr>';
                
                $table .= '</table>';
                
                $row[] = $table; //$aRow['procedimento'];
                
                $row[] = $aRow['data_pagamento'];
                //$row[] = $aRow['quantity_sold'];
                //$footer_data['total_qty'] += $aRow['quantity_sold']; // quantidade
                //$row[] = app_format_money($aRow['valor_procedimento'], $currency->name);
                //$row[] = $aRow['valor_procedimento'];
                
                //$footer_data['valor_procedimento'] += $aRow['valor_procedimento']; // valor procedimento
                
                //$row[] = app_format_money($aRow['desconto'], $currency->name); // créditos
               // $row[] = $aRow['desconto']; // créditos
               // $footer_data['desconto'] += $aRow['desconto'];  
                
              /*  if($aRow['total']){
                    $fat_total = $aRow['total'];
                }else{
                     $fat_total = $aRow['rate_total'] - $aRow['desconto'];
                }
                
               
                //$row[] = app_format_money($fat_total, $currency->name);
                $row[] = $fat_total;
                $footer_data['rate_total'] += $fat_total; // desconto procedimento
               */
               
                $table_pag = "";
                $table_pag .= '<table  style="background-color: #D3D3D3" class="table " >';
                $table_pag .= '<thead>';
                $table_pag .= '<tr>';
                $table_pag .= '<td> <font style = "font-size: 12px"> Data</font> </td>';
                $table_pag .= '<td> <font style = "font-size: 12px">Conta</font> </td>';
                $table_pag .= '<td> <font style = "font-size: 12px">Valor</font> </td>';
                $table_pag .= '<td> <font style = "font-size: 12px">Caixa</font> </td>';
                $table_pag .= '</tr>';
                $table_pag .= '</thead>';
                $pagamentos_fat   =  $this->invoices_model->get_pagamentos_agrupado_por_conta($invoice_id);    
                $soma_empresa = 0;
                $soma_medico = 0;
                $soma_pgto = 0;
                $table_pag .= '<tbody>';
                foreach ($pagamentos_fat as $proc) {
                    $conta_id = $proc['conta_id'];
                    $medico_id= $proc['medicoid'];
                    $amount = $proc['amount'];
                    $date= $proc['date'];
                    $caixa= $proc['caixa'];
                   
                    
                     $dados_medico   = $this->contas_financeiras_model->get($conta_id); 
                     $nome_reduzido = $dados_medico->nome;
                     $medico_id = $dados_medico->medico_id;
                     
                    if($medico_id){
                        $dados_medico   = $this->medicos_model->get($medico_id); 
                        $nome_reduzido = $dados_medico->nome_reduzido;
                    }
                    
                   // $nome_profissional = $dados_medico->nome_profissional;
                   
                    $table_pag .= '<tr>';
                    
                    $table_pag .= '<td> <font style = "font-size: 10px">'.$date.'</font> </td>';
                    $table_pag .= '<td > <font style = "font-size: 10px">  '.$nome_reduzido.' </font></td>';
                    $table_pag .= '<td> <font style = "font-size: 10px">'.app_format_money($amount, $currency->name).' </font></td>';
                    $table_pag .= '<td> <font style = "font-size: 10px">'.$caixa.'</font> </td>';
                    $table_pag .= '</tr>';
                    
                    if($conta_id == 1){
                        $soma_empresa += $amount;
                    }else{
                        $soma_medico += $amount;
                    }
                    
                    $soma_pgto += $amount;
                }
                $table_pag .= '</tbody>';
                $table_pag .= '<tfoot>';
                 $table_pag .= '<tr style="background-color: #A9A9A9">';
                    
                    $table_pag .= '<td> <font style = "font-size: 14px"><b>Total</b></font> </td>';
                    $table_pag .= '<td > <font style = "font-size: 10px">   </font></td>';
                    $table_pag .= '<td> <font style = "font-size: 14px"><b>'.app_format_money($soma_pgto, $currency->name).' </b></font></td>';
                    $table_pag .= '<td > <font style = "font-size: 10px">   </font></td>';
                    $table_pag .= '</tr>';
                $table_pag .= '</tfoot>';
                $table_pag .= '</table>';
               
                 $row[] = $table_pag;
               
                
                
                //$row[] = app_format_money($aRow['valor_tesouraria'], $currency->name); // créditos
                $row[] = app_format_money($soma_empresa, $currency->name); //$aRow['valor_tesouraria']; // créditos
                $footer_data['valor_faturado_produzido'] += $soma_empresa;  
                //$row[] = app_format_money($aRow['valor_medico'], $currency->name); // créditos
                $row[] = app_format_money($soma_medico, $currency->name);// $aRow['valor_medico']; // créditos
                $footer_data['valor_medico'] += $soma_medico;// $aRow['valor_medico'];//valor_medico
                
                
                 // retorna as notas fiscais
            //  '(select numero from tblnotafiscal n where n.invoice_id = invoicepaymentrecords.invoiceid and n.conta_id = tblinvoicepaymentrecords.conta_id  group by invoice_id) as numero_nf',
                
                
                $notas_fiscais   = $this->nota_fiscal_model->get_nota_fiscal_invoice($invoice_id);
                $dados_nota = "";
                $count_nota = 1;
                $total_nota = count($notas_fiscais);
                foreach ($notas_fiscais as $nota) {
                    $link = $nota['url'];
                    $numero_nota = '<a href='.$link.' target="blank">'. $nota['numero'].' </a>';  
                
                if($total_nota == $count_nota){
                    $dados_nota .= $numero_nota;
                }else{
                    $dados_nota .= $numero_nota.' <br>';
                }
                $count_nota ++;
                
                }
              //  print_r($notas_fiscais); exit;
                
                if($total_nota > 0){
                    $row[] = $dados_nota;
                }else if($aRow['numero_nf_manual']){
                    $row[] = $aRow['numero_nf_manual'];
                }else{
                     $row[] = "-";
                }
                 
               // $row[] = $aRow['caixa'];
                
                $usuario = get_staff_full_name($aRow['addedfrom']);
                $log = $usuario.' <br> '.$aRow['datecreated'];
                $row[] = $log; // créditos
                
               
                
                $output['aaData'][] = $row;
            }

           // $footer_data['total_amount'] = app_format_money($footer_data['total_amount'], $currency->name);
           // $footer_data['desconto_amount'] = app_format_money($footer_data['desconto_amount'], $currency->name);
           // $footer_data['amount_total_procedimento'] = app_format_money($footer_data['amount_total_procedimento'], $currency->name);

            foreach ($footer_data as $key => $total) {
                
                $footer_data[$key] = app_format_money($total, $currency->name);
            }
            
            $output['sums'] = $footer_data;
            echo json_encode($output);
            die();
        }
    }
    
    public function faturamento_medico_report()
    {
        if ($this->input->is_ajax_request()) {
            $this->load->model('currencies_model');
            $this->load->model('payment_modes_model');
            $payment_gateways = $this->payment_modes_model->get_payment_gateways(true);
            $select           = [
                db_prefix().'medicos.nome_profissional as medico',
                'SUM(tblitemable.qty) as quantity_sold',
                'sum(tblitemable.rate * tblitemable.qty) as total_faturado',
                'sum(tblitemable.desconto_valor) as desconto',
                'sum(tblitemable.valor_tesouraria) as valor_tesouraria',
                'sum(tblitemable.valor_medico) as valor_medico',
            ];
            $where = [
                'AND tblinvoices.status != 5',
            ];

            $custom_date_select = $this->get_where_report_period(db_prefix() . 'invoicepaymentrecords.date');
            if ($custom_date_select != '') {
                array_push($where, $custom_date_select);
            }

            $by_currency = $this->input->post('report_currency');
            if ($by_currency) {
                $currency = $this->currencies_model->get($by_currency);
                array_push($where, 'AND currency=' . $this->db->escape_str($by_currency));
            } else {
                $currency = $this->currencies_model->get_base_currency();
            }

            $aColumns     = $select;
            $sIndexColumn = 'id';
            $sTable       = db_prefix() . 'invoices';
            $join         = [
                'JOIN ' . db_prefix() . 'itemable ON ' . db_prefix() . 'itemable.rel_id = ' . db_prefix() . 'invoices.id AND tblitemable.rel_type="invoice" ',
                'JOIN tblitems ON tblitems.id = tblitemable.item_id',
                'JOIN tblitems_groups ON tblitems_groups.id = tblitems.group_id',
                'LEFT JOIN tblconvenio ON tblconvenio.id = tblinvoices.convenio',
                'LEFT JOIN tblcentrocusto ON tblcentrocusto.ID = tblinvoices.centrocustoid',
                'JOIN tblmedicos ON tblmedicos.medicoid = tblitemable.medicoid',
                'INNER JOIN tblinvoicepaymentrecords ON tblinvoicepaymentrecords.invoiceid = tblinvoices.id',
                'JOIN ' . db_prefix() . 'conta_financeira ON ' . db_prefix() . 'conta_financeira.id = ' . db_prefix() . 'invoicepaymentrecords.conta_id',
            ];
            $currency = $this->currencies_model->get_base_currency();
             // FILTRO MÉDICO
          
            
            if ($this->input->post('medicos_faturamento_medico')) {
                $medicos = $this->input->post('medicos_faturamento_medico');
                $_medicos = [];
                if (is_array($medicos)) {
                    foreach ($medicos as $medico) {
                        if ($medico != '') {
                            array_push($_medicos, $this->db->escape_str($medico));
                        }
                    }
                }
             //   echo 'ID : '.$medicos; exit;
                if (count($_medicos) > 0) {
                    array_push($where, ' AND '. db_prefix() . 'conta_financeira.id IN (' . implode(', ', $_medicos) . ') AND tblinvoices.deleted = 0');
                }
            }
            $GroupBy = 'GROUP BY tblmedicos.medicoid';
            $result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, NULL, $GroupBy);

            $output  = $result['output'];
            $rResult = $result['rResult'];

            $footer_data = [
                'total_qty'     => 0,
                'desconto'      => 0,
                'total_amount'  => 0,
                'valor_medico'  => 0,
                'valor_empresa' => 0,
              
            ];
            $footer_data['total_amount'] = 0;
            foreach ($rResult as $aRow) {
                $row = [];
                
                for ($i = 0; $i < count($aColumns); $i++) {
                    if (strpos($aColumns[$i], 'as') !== false && !isset($aRow[$aColumns[$i]])) {
                        $_data = $aRow[strafter($aColumns[$i], 'as ')];
                    } else {
                        $_data = $aRow[$aColumns[$i]];
                    }
                    
                   
                    
                    if ($i == 1) {
                        $footer_data['total_qty'] += $_data;
                    }else if ($i == 2) {
                        $footer_data['total_amount'] += $_data;
                        $_data = app_format_money($_data, $currency->name);
                    }else if ($i == 3) {
                        $footer_data['desconto'] += $_data;
                        $_data = app_format_money($_data, $currency->name);
                    }else if ($i == 4) {
                        $footer_data['valor_empresa'] += $_data;
                        $_data = app_format_money($_data, $currency->name);
                    }else if ($i == 5) {
                        $footer_data['valor_medico'] += $_data;
                        $_data = app_format_money($_data, $currency->name);
                    }
                    
                    
                    

                    $row[] = $_data;
                }
                $output['aaData'][] = $row;
            }

            $footer_data['total_amount'] = app_format_money($footer_data['total_amount'], $currency->name);
            $footer_data['total_qty'] = $footer_data['total_qty'];
            $footer_data['desconto'] = app_format_money($footer_data['desconto'], $currency->name);
            $footer_data['valor_empresa'] = app_format_money($footer_data['valor_empresa'], $currency->name);
            $footer_data['valor_medico'] = app_format_money($footer_data['valor_medico'], $currency->name);
            
            $output['sums']              = $footer_data;
            echo json_encode($output);
            die();
        }
    }

    public function agendamentos_realizados()
    {
        if ($this->input->is_ajax_request()) {
            $this->load->model('currencies_model');
            $this->load->model('payment_modes_model');
            $payment_gateways = $this->payment_modes_model->get_payment_gateways(true);
            $select           = [
                db_prefix() . 'invoicepaymentrecords.id',
                db_prefix() . 'invoicepaymentrecords.date',
                'invoiceid',
                'tblinvoices.status',
                get_sql_select_client_company(),
                db_prefix().'conta_financeira.nome',
                db_prefix() . 'convenio.name',
                'paymentmode',
                'transactionid',
                '(select numero from tblnotafiscal n where n.invoice_id = invoiceid group by invoice_id) as numero_nf',
                'note',
                'amount',
                
                db_prefix() . 'invoicepaymentrecords.daterecorded as daterecorded',
                db_prefix() . 'invoicepaymentrecords.userid as userid',
            ];
            $where = [
                'AND status != 5',
            ];

            $custom_date_select = $this->get_where_report_period(db_prefix() . 'invoicepaymentrecords.date');
            if ($custom_date_select != '') {
                array_push($where, $custom_date_select);
            }

            $by_currency = $this->input->post('report_currency');
            if ($by_currency) {
                $currency = $this->currencies_model->get($by_currency);
                array_push($where, 'AND currency=' . $this->db->escape_str($by_currency));
            } else {
                $currency = $this->currencies_model->get_base_currency();
            }

            $aColumns     = $select;
            $sIndexColumn = 'id';
            $sTable       = db_prefix() . 'invoicepaymentrecords';
            $join         = [
                'JOIN ' . db_prefix() . 'invoices ON ' . db_prefix() . 'invoices.id = ' . db_prefix() . 'invoicepaymentrecords.invoiceid',
                'JOIN ' . db_prefix() . 'conta_financeira ON ' . db_prefix() . 'conta_financeira.id = ' . db_prefix() . 'invoicepaymentrecords.conta_id',
                'LEFT JOIN ' . db_prefix() . 'clients ON ' . db_prefix() . 'clients.userid = ' . db_prefix() . 'invoices.clientid',
                'LEFT JOIN ' . db_prefix() . 'payment_modes ON ' . db_prefix() . 'payment_modes.id = ' . db_prefix() . 'invoicepaymentrecords.paymentmode',
                'LEFT JOIN ' . db_prefix() . 'convenio ON ' . db_prefix() . 'convenio.id = ' . db_prefix() . 'invoices.convenio',
            ];
            
             // FILTRO MÉDICO
          
            
            if ($this->input->post('medicos_pagamento_recebido')) {
                $medicos = $this->input->post('medicos_pagamento_recebido');
                $_medicos = [];
                if (is_array($medicos)) {
                    foreach ($medicos as $medico) {
                        if ($medico != '') {
                            array_push($_medicos, $this->db->escape_str($medico));
                        }
                    }
                }
             //   echo 'ID : '.$medicos; exit;
                if (count($_medicos) > 0) {
                    array_push($where, ' AND '. db_prefix() . 'conta_financeira.id IN (' . implode(', ', $_medicos) . ') AND tblinvoices.deleted = 0');
                }
            }
            
            // FILTRO CONVÊNIOS
            if ($this->input->post('convenios_pagamento')) {
                $convenios = $this->input->post('convenios_pagamento');
               
                $_convenios = [];
                if (is_array($convenios)) {
                    foreach ($convenios as $convenio) {
                        if ($convenio != '') {
                            array_push($_convenios, $this->db->escape_str($convenio));
                        }
                    }
                }
                if (count($_convenios) > 0) {
                    array_push($where, ' AND '. db_prefix() . 'invoices.convenio IN (' . implode(', ', $_convenios) . ')');
                }
            }

            $result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [
                'number',
                'clientid',
                db_prefix() . 'payment_modes.name',
                db_prefix() . 'payment_modes.id as paymentmodeid',
                'paymentmethod',
                'deleted_customer_name',
            ]);

            $output  = $result['output'];
            $rResult = $result['rResult'];

            $footer_data['total_amount'] = 0;
            foreach ($rResult as $aRow) {
                $row = [];
                
                for ($i = 0; $i < count($aColumns); $i++) {
                    if (strpos($aColumns[$i], 'as') !== false && !isset($aRow[$aColumns[$i]])) {
                        $_data = $aRow[strafter($aColumns[$i], 'as ')];
                    } else {
                        $_data = $aRow[$aColumns[$i]];
                    }
                    
                   
                    
                    if ($aColumns[$i] == 'paymentmode') {
                        $_data = $aRow['name'];
                        if (is_null($aRow['paymentmodeid'])) {
                            foreach ($payment_gateways as $gateway) {
                                if ($aRow['paymentmode'] == $gateway['id']) {
                                    $_data = $gateway['name'];
                                }
                            }
                        }
                        if (!empty($aRow['paymentmethod'])) {
                            $_data .= ' - ' . $aRow['paymentmethod'];
                        }
                    } elseif ($aColumns[$i] == db_prefix() . 'invoicepaymentrecords.id') {
                        $_data = '<a href="' . admin_url('payments/payment/' . $_data) . '" target="_blank">' . $_data . '</a>';
                    } elseif ($aColumns[$i] == db_prefix() . 'invoicepaymentrecords.date') {
                        $_data = _d($_data);
                    } elseif ($aColumns[$i] == 'invoiceid') {
                        $status_invoice = format_invoice_status($aRow[db_prefix() . 'invoices.status']);
                        $_data = '<a href="' . admin_url('invoices/list_invoices/' . $aRow[$aColumns[$i]]) . '" target="_blank">' . format_invoice_number($aRow['invoiceid']) . '</a>';
                    } elseif ($aColumns[$i] == 'tblinvoices.status') {
                        $status_invoice = format_invoice_status($aRow[db_prefix() . 'invoices.status']);
                        $_data =  $status_invoice;
                    } elseif ($i == 3) {
                        if (empty($aRow['deleted_customer_name'])) {
                            $_data = '<a href="' . admin_url('clients/client/' . $aRow['clientid']) . '" target="_blank">' . $aRow['company'] . '</a>';
                        } else {
                            $row[] = $aRow['deleted_customer_name'];
                        }
                    } elseif ($aColumns[$i] == 'amount') {
                        $footer_data['total_amount'] += $_data;
                        $_data = app_format_money($_data, $currency->name);
                    }elseif ($i == 12) {
                        $usuario = get_staff_full_name($aRow['userid']);
                        $log = $usuario.' <br> '.$aRow['daterecorded'];
                        $_data = $log;
                    }
                    
                    
                    

                    $row[] = $_data;
                }
                $output['aaData'][] = $row;
            }

            $footer_data['total_amount'] = app_format_money($footer_data['total_amount'], $currency->name);
            $output['sums']              = $footer_data;
            echo json_encode($output);
            die();
        }
    }
    
    // usado no relatório - agendamentos realizados
    public function agendamentos_report()
    {
        if ($this->input->is_ajax_request()) {
            $this->load->model('currencies_model');
            $this->load->model('payment_modes_model');
            $this->load->model('invoice_items_model');
            
            $aColumns = [
                'tblmedicos.nome_profissional as nome_profissional',
                'CONCAT(date, \' \', start_hour) as date',
                'company',
                'tblconvenio.name as convenio',
                'tblappointly_appointment_types.type as type',
                'firstname as creator_firstname',
                'lastname as creator_lastname',
                'procedimentos'
                
            ];


            $sIndexColumn = 'id';
            $sTable       = db_prefix() . 'appointly_appointments';

            $where  = [];

            if (!is_admin() && !is_staff_appointments_responsible()) {
             //   array_push($where, 'AND (' . db_prefix() . 'appointly_appointments.created_by=' . get_staff_user_id() . ') 
              //  OR ' . db_prefix() . 'appointly_appointments.id ');
            }
            
            $custom_date_select = $this->get_where_report_period(db_prefix() . 'appointly_appointments.date');
            if ($custom_date_select != '') {
                array_push($where, $custom_date_select);
            }


            if ($this->ci->input->post('custom_view')) {

                if ($this->ci->input->post('custom_view') == 'approved') {
                    $where[] = 'AND approved = "1" AND cancelled = "0"'; // AND deleted = "0" 
                }

                if ($this->ci->input->post('custom_view') == 'cancelled') {
                    $where[] = 'AND cancelled= "1"';
                }

                if ($this->ci->input->post('custom_view') == 'finished') {
                    $where[] = 'AND cancelled= "0" AND finished = "1" AND approved = "1"';
                }

                if ($this->ci->input->post('custom_view') == 'not_approved') {
                    $where[] = 'AND approved != "1"';
                }
            }

          
            // FILTRO MÉDICO
            if ($this->input->post('medicos_agendamento')) {
                $medicos = $this->input->post('medicos_agendamento');
                $_medicos = [];
                if (is_array($medicos)) {
                    foreach ($medicos as $medico) {
                        if ($medico != '') {
                            array_push($_medicos, $this->db->escape_str($medico));
                        }
                    }
                }
             //   echo 'ID : '.$medicos; exit;
                if (count($_medicos) > 0) {
                    array_push($where, ' AND '. db_prefix() . 'appointly_appointments.medico_id IN (' . implode(', ', $_medicos) . ')');
                }
            } 
            
            // FILTRO CONVÊNIOS
            if ($this->input->post('convenios_agendamento')) {
                $convenios = $this->input->post('convenios_agendamento');
               
                $_convenios = [];
                if (is_array($convenios)) {
                    foreach ($convenios as $convenio) {
                        if ($convenio != '') {
                            array_push($_convenios, $this->db->escape_str($convenio));
                        }
                    }
                }
                if (count($_convenios) > 0) {
                    array_push($where, ' AND '. db_prefix() . 'appointly_appointments.convenio IN (' . implode(', ', $_convenios) . ')');
                }
            }
            // TIPO AGENDAMENTO
            if ($this->input->post('tipo_agendamento')) {
                $tipo_agendamento = $this->input->post('tipo_agendamento');
                $_tipo = [];
                if (is_array($tipo_agendamento)) {
                    foreach ($tipo_agendamento as $tipo) {
                        if ($tipo != '') {
                            array_push($_tipo, $this->db->escape_str($tipo));
                        }
                    }
                }
             //   echo 'ID : '.$medicos; exit;
                if (count($_tipo) > 0) {
                    array_push($where, ' AND '. db_prefix() . 'appointly_appointments.type_id IN (' . implode(', ', $_tipo) . ')');
                }
            }
            
            $hoje = date('Y-m-d H:i');
            // STATUS AGENDAMENTO
            if ($this->input->post('status_agendamento')) {
                $status_agendamento = $this->input->post('status_agendamento');
                if ($status_agendamento == 'ATENDIDO') {
                  $where[] = ' AND '. db_prefix() . 'appointly_appointments.finished = 1';
                }else if ($status_agendamento == 'FALTOU') {
                  $where[] = ' AND !tblappointly_appointments.finished AND !tblappointly_appointments.cancelled AND !tblappointly_appointments.confirmar_chegada AND !tblappointly_appointments.inicio_atendimento AND tblappointly_appointments.date < '."'$hoje'";
                }else if ($status_agendamento == 'CANCELADO') {
                  $where[] = ' AND tblappointly_appointments.cancelled = 1 ';
                }else if ($status_agendamento == 'CONFIRMADO') {
                  $where[] = ' AND tblappointly_appointments.approved = 1 AND !tblappointly_appointments.inicio_atendimento  AND !tblappointly_appointments.finished AND !tblappointly_appointments.cancelled   ';
                }else if ($status_agendamento == 'EMESPERA') {
                  $where[] = ' AND tblappointly_appointments.confirmar_chegada = 1 AND !tblappointly_appointments.finished AND !tblappointly_appointments.cancelled AND !tblappointly_appointments.inicio_atendimento';
                }else if ($status_agendamento == 'EMATENDIMENTO') {
                  $where[] = ' AND tblappointly_appointments.inicio_atendimento = 1 AND !tblappointly_appointments.finished AND !tblappointly_appointments.cancelled';
                }
            }

            $where[] = "AND tblappointly_appointments.contact_id is not null";
            //print_r($view); exit;

            $join = [
                'LEFT JOIN ' . db_prefix() . 'staff ON ' . db_prefix() . 'staff.staffid = ' . db_prefix() . 'appointly_appointments.created_by',
                'LEFT JOIN ' . db_prefix() . 'clients ON ' . db_prefix() . 'clients.userid = ' . db_prefix() . 'appointly_appointments.contact_id',
                'LEFT JOIN ' . db_prefix() . 'convenio ON ' . db_prefix() . 'convenio.id = ' . db_prefix() . 'appointly_appointments.convenio',
                'LEFT JOIN ' . db_prefix() . 'medicos ON ' . db_prefix() . 'medicos.medicoid = ' . db_prefix() . 'appointly_appointments.medico_id',
                'LEFT JOIN ' . db_prefix() . 'appointly_appointment_types ON ' . db_prefix() . 'appointly_appointment_types.id = ' . db_prefix() . 'appointly_appointments.type_id',
            ];

            $additionalSelect = [
                'tblappointly_appointments.id as id',
                'finished',
                'approved',
                'created_by',
                'tblappointly_appointments.name',
                'tblappointly_appointments.confirmar_chegada',
                'tblappointly_appointments.inicio_atendimento',
                'tblappointly_appointments.data_chegada',
                'tblappointly_appointment_types.color',
                'tblconvenio.cor as convenio_cor',
                db_prefix() . 'appointly_appointments.email as contact_email',
                db_prefix() . 'appointly_appointments.phone',
                'tblappointly_appointments.medico_id',
                'cancelled',
                'bloqueio',
                'tblclients.phonenumber',
                'tblclients.phonenumber2',
                'contact_id',
                'google_calendar_link',
                'google_added_by_id',
                'feedback'
            ];
            $orderby = "ORDER BY tblmedicos.medicoid, tblappointly_appointments.date, tblappointly_appointments.START_HOUR ASC";
            $result = data_tables_init_order($aColumns, $sIndexColumn, $sTable, $join, $where, $additionalSelect,"", $orderby);

            $output  = $result['output'];
            $rResult = $result['rResult'];



            $footer_data['total_amount'] = 0;
            foreach ($rResult as $aRow) {
               
                
                $row = [];
                
                for ($i = 0; $i < count($aColumns); $i++) {
                  
                    
                    if ($i == 0) {
                        $row[] =  $aRow['nome_profissional'];
                    }
                    
                   
                    
                     // TIPO CONSULTA
                    if($aRow['color']){
                    $label_class_tipo = $aRow['color'];
                    }else{
                       $label_class_tipo = "info"; 
                    }
                    $type = $aRow['type']; 
                    $label_tipo = '<span data-color="$label_class_tipo"  class="label label-' . $label_class_tipo . '">' . $type . '</span>';

                     //$data_hora =  _d($aRow['date']); 
                     $data_hora =  date("d/m/Y H:i", strtotime($aRow['date'])); 
                    // STATUS
                    if($aRow['id']){
                        if ($aRow['company']) {
                            // CANCELADO
                            if ($aRow['cancelled'] && $aRow['finished'] == 0) {
                                $status = '<span class="label label-danger">' . strtoupper(_l('appointment_cancelled')) . '</span>';
                                $button_hora = '<span class="label label-danger">' . $data_hora . '</span>';
                                // FALTOU
                            } else if (!$aRow['finished'] && !$aRow['cancelled'] && $aRow['approved'] == 0 && date('Y-m-d H:i', strtotime($aRow['date'])) < date('Y-m-d H:i')) {
                                $button_hora = '<span class="label label-danger">' . $data_hora . '</span>';
                                $status = '<span class="label label-danger">' . strtoupper('faltou') . '</span>';
                                // CONFIRMADO
                            } else if (!$aRow['finished'] && !$aRow['cancelled'] && $aRow['approved'] == 1 && $aRow['confirmar_chegada'] == 0 ) {
                                $label_status = strtoupper('Confirmado') ;
                                $status = '<button class="label label-info mleft5" data-toggle="tooltip" data-id="' . $aRow['id'] . '" onclick="appointmentUpdateModal(this)">'.$label_status.'</button>';
                                $button_hora = '<span class="label label-primary btn-xs mleft5" data-toggle="tooltip" title="Agendamento" data-id="' . $aRow['id'] . '" >'.$data_hora.'</span>';
                                // AGENDADO
                            } else if (!$aRow['finished'] && !$aRow['cancelled'] && $aRow['approved'] == 0) {
                                $label_status = strtoupper('Agendado') ;
                                $status = '<button class="label label-warning mleft5" data-toggle="tooltip" data-id="' . $aRow['id'] . '" onclick="appointmentUpdateModal(this)">'.$label_status.'</button>';
                                $button_hora = '<span class="label label-primary btn-xs mleft5" data-toggle="tooltip" title="Agendamento" data-id="' . $aRow['id'] . '" >'.$data_hora.'</span>';

                                // FILA DE ESPERA
                            } else if ($aRow['confirmar_chegada'] == 1 && $aRow['finished']== 0 && $aRow['inicio_atendimento']== 0) {
                                $status = '<span class="label label-warning">' . strtoupper('EM ESPERA') . '</span>';
                                $button_hora = '<span class="label label-success">' . $data_hora . '</span>';
                                //EM ATENDIMENTO
                            } else if ($aRow['confirmar_chegada'] == 1 && $aRow['finished']== 0 && $aRow['inicio_atendimento']== 1) {
                                $status = '<span class="label label-primary">' . strtoupper('EM ATENDIMENTO') . '</span>';
                                $button_hora = '<span class="label label-success">' . $data_hora . '</span>';
                                //ATENDIDO
                            }else if ($aRow['finished'] == 1) {
                                $status = '<span class="label label-success">' . strtoupper('ATENDIDO') . '</span>';
                                $button_hora = '<span class="label label-success">' . $data_hora . '</span>';
                            }
                        }else{
                            if($aRow['bloqueio'] == 1){
                             $status = '<span class="label label-danger">' . strtoupper('Bloqueado') . '</span>';   
                             $button_hora = '<span class="label label-danger">' . $data_hora . '</span>';
                            }else{
                            $label_status = strtoupper('disponível') ;
                            $status = '<button class="label label-default mleft5" data-toggle="tooltip" data-id="' . $aRow['id'] . '" onclick="appointmentUpdateModal(this)">'.$label_status.'</button>';
                            $button_hora = '<span class="label label-primary btn-xs mleft5" data-toggle="tooltip" title="Agendamento" data-id="' . $aRow['id'] . '" >'.$data_hora.'</span>';
                            }

                        }
                    }


                   // HORA

                    //$row[] = '<span  ' . $tooltip . ' class="label label-' . $label_class . '">' . $data_hora . '</span>';
                    $row[] = $label_tipo;
                    $row[] = $button_hora;
                    $row[] = $status;

                    // PACIENTE
                     if ($aRow['company']) {
                        $company  = $aRow['company'];
                        $url = admin_url('clients/client/' . $aRow['contact_id']);
                        $company = '<a target="_blank" href="' . $url . '">' . $company . '</a>';
                     //   $company .= '<div class="row-options">';
                     //   $company .= '<a href="' . admin_url('clients/client/' . $aRow['contact_id'] . ($isPerson && $aRow['contact_id'] ? '?group=contacts' : '')) . '">' . _l('view') . '</a>';
                        $row[] = $company;
                   }else{
                       $row[] = "";
                   }
                    
                    $contato1 = $aRow['phonenumber'];
                    $contato2 = $aRow['phonenumber2'];
                    $row[] = $contato1.'<br>'.$contato2;
                     
                   // CONVENIO
                    if($aRow['convenio']){
                        $label_class_convenio = $aRow['convenio_cor'];
                        if(!$label_class_convenio){
                            $label_class_convenio = 'default';
                        }
                        $convenio = $aRow['convenio'];
                        $row[] = $convenio; // '<span   class="label label-' . $label_class_convenio . '">' . $convenio . '</span>';        
                    }else{
                        $row[] = "";
                    }

                    //procedimentos
                    $procedimentos = $aRow['procedimentos'];
                    $descricao_procedimento = "";
                    if($procedimentos){
                    $proc_agenda =  $this->invoice_items_model->get_agenda($procedimentos);
                    foreach ($proc_agenda as $proc) {
                        $descricao_procedimento .= '<span   >' .  $proc['description'] . '</span> <br>';
                    }
                   }
                   $row[] = $descricao_procedimento;

                    if ($aRow['creator_firstname']) {
                        $staff_fullname = $aRow['creator_firstname'] . ' ' . $aRow['creator_lastname'];

                        $row[] = '<a class="initiated_by" target="_blank" href="' . admin_url() . "profile/" . $aRow["created_by"] . '"><img src="' . staff_profile_image_url($aRow["created_by"], "small") . '" data-toggle="tooltip" data-title="' . $staff_fullname . '" class="staff-profile-image-small mright5" data-original-title="" title="' . $staff_fullname . '">' . $staff_fullname . '</a>';
                    }

                    //$row[] = $_data;
                }
                $output['aaData'][] = $row;
            }

            $footer_data['total_amount'] = app_format_money($footer_data['total_amount'], $currency->name);
            $output['sums']              = $footer_data;
            echo json_encode($output);
            die();
        }
    }
    
    // usado no relatório - agendamentos realizados
    public function agendamentos_resumo_report()
    {
        if ($this->input->is_ajax_request()) {
            $this->load->model('currencies_model');
            $this->load->model('payment_modes_model');
            $this->load->model('invoice_items_model');
            
            $aColumns = [
                'tblmedicos.nome_profissional as nome_profissional',
                'COUNT(*) AS quantidade',
                'tblconvenio.name as convenio',
                'tblappointly_appointment_types.type as type',
                'firstname as creator_firstname',
                'lastname as creator_lastname',
                'procedimentos'
                
            ];


            $sIndexColumn = 'id';
            $sTable       = db_prefix() . 'appointly_appointments';

            $where  = [];

            if (!is_admin() && !is_staff_appointments_responsible()) {
             //   array_push($where, 'AND (' . db_prefix() . 'appointly_appointments.created_by=' . get_staff_user_id() . ') 
              //  OR ' . db_prefix() . 'appointly_appointments.id ');
            }
            
            $custom_date_select = $this->get_where_report_period(db_prefix() . 'appointly_appointments.date');
            if ($custom_date_select != '') {
                array_push($where, $custom_date_select);
            }


            if ($this->ci->input->post('custom_view')) {

                if ($this->ci->input->post('custom_view') == 'approved') {
                    $where[] = 'AND approved = "1" AND cancelled = "0"'; // AND deleted = "0" 
                }

                if ($this->ci->input->post('custom_view') == 'cancelled') {
                    $where[] = 'AND cancelled= "1"';
                }

                if ($this->ci->input->post('custom_view') == 'finished') {
                    $where[] = 'AND cancelled= "0" AND finished = "1" AND approved = "1"';
                }

                if ($this->ci->input->post('custom_view') == 'not_approved') {
                    $where[] = 'AND approved != "1"';
                }
            }

             $groupby = "GROUP BY tblmedicos.medicoid, tblappointly_appointment_types.id ";
          
            // FILTRO MÉDICO
            if ($this->input->post('medicos_agendamento_resumo')) {
                $medicos = $this->input->post('medicos_agendamento_resumo');
                $_medicos = [];
                if (is_array($medicos)) {
                    foreach ($medicos as $medico) {
                        if ($medico != '') {
                            array_push($_medicos, $this->db->escape_str($medico));
                        }
                    }
                }
             //   echo 'ID : '.$medicos; exit;
                if (count($_medicos) > 0) {
                    array_push($where, ' AND '. db_prefix() . 'appointly_appointments.medico_id IN (' . implode(', ', $_medicos) . ')');
                }
            } 
            
            // FILTRO CONVÊNIOS
            if ($this->input->post('convenios_agendamento_resumo')) {
               $convenio_agendamento = $this->input->post('convenios_agendamento_resumo'); 
               $groupby .= ", tblappointly_appointments.convenio ";
            }
            
        
            
            // TIPO AGENDAMENTO
            if ($this->input->post('tipo_agendamento_lista')) {
                $tipo_agendamento_lista = $this->input->post('tipo_agendamento_lista');
                $_tipo = [];
                if (is_array($tipo_agendamento_lista)) {
                    foreach ($tipo_agendamento_lista as $tipo) {
                        if ($tipo != '') {
                            array_push($_tipo, $this->db->escape_str($tipo));
                        }
                    }
                }
             //   echo 'ID : '.$medicos; exit;
                if (count($_tipo) > 0) {
                    array_push($where, ' AND '. db_prefix() . 'appointly_appointments.type_id IN (' . implode(', ', $_tipo) . ')');
                }
            }
            
            $hoje = date('Y-m-d H:i');
            // STATUS AGENDAMENTO
            if ($this->input->post('status_agendamento_resumo')) {
                $status_agendamento = $this->input->post('status_agendamento_resumo');
                if ($status_agendamento == 'ATENDIDO') {
                  $where[] = ' AND '. db_prefix() . 'appointly_appointments.finished = 1';
                }else if ($status_agendamento == 'FALTOU') {
                  $where[] = ' AND !tblappointly_appointments.finished AND !tblappointly_appointments.cancelled AND !tblappointly_appointments.confirmar_chegada AND !tblappointly_appointments.inicio_atendimento AND tblappointly_appointments.date < '."'$hoje'";
                }else if ($status_agendamento == 'CANCELADO') {
                  $where[] = ' AND tblappointly_appointments.cancelled = 1 ';
                }else if ($status_agendamento == 'CONFIRMADO') {
                  $where[] = ' AND tblappointly_appointments.approved = 1 AND !tblappointly_appointments.inicio_atendimento  AND !tblappointly_appointments.finished AND !tblappointly_appointments.cancelled   ';
                }else if ($status_agendamento == 'EMESPERA') {
                  $where[] = ' AND tblappointly_appointments.confirmar_chegada = 1 AND !tblappointly_appointments.finished AND !tblappointly_appointments.cancelled AND !tblappointly_appointments.inicio_atendimento';
                }else if ($status_agendamento == 'EMATENDIMENTO') {
                  $where[] = ' AND tblappointly_appointments.inicio_atendimento = 1 AND !tblappointly_appointments.finished AND !tblappointly_appointments.cancelled';
                }
            }

            $where[] = "AND tblappointly_appointments.contact_id is not null";
            //print_r($view); exit;

            $join = [
                'LEFT JOIN ' . db_prefix() . 'staff ON ' . db_prefix() . 'staff.staffid = ' . db_prefix() . 'appointly_appointments.created_by',
                'LEFT JOIN ' . db_prefix() . 'clients ON ' . db_prefix() . 'clients.userid = ' . db_prefix() . 'appointly_appointments.contact_id',
                'LEFT JOIN ' . db_prefix() . 'convenio ON ' . db_prefix() . 'convenio.id = ' . db_prefix() . 'appointly_appointments.convenio',
                'LEFT JOIN ' . db_prefix() . 'medicos ON ' . db_prefix() . 'medicos.medicoid = ' . db_prefix() . 'appointly_appointments.medico_id',
                'LEFT JOIN ' . db_prefix() . 'appointly_appointment_types ON ' . db_prefix() . 'appointly_appointment_types.id = ' . db_prefix() . 'appointly_appointments.type_id',
            ];

            $additionalSelect = [
                'tblappointly_appointments.id as id',
                'finished',
                'approved',
                'created_by',
                'tblappointly_appointments.name',
                'tblappointly_appointments.confirmar_chegada',
                'tblappointly_appointments.inicio_atendimento',
                'tblappointly_appointments.data_chegada',
                'tblappointly_appointment_types.color',
                'tblconvenio.cor as convenio_cor',
                db_prefix() . 'appointly_appointments.email as contact_email',
                db_prefix() . 'appointly_appointments.phone',
                'tblappointly_appointments.medico_id',
                'cancelled',
                'bloqueio',
                'contact_id',
                'google_calendar_link',
                'google_added_by_id',
                'feedback'
            ];
           
            $orderby = "ORDER BY tblmedicos.medicoid, tblappointly_appointments.date, tblappointly_appointments.START_HOUR ASC";
            $result = data_tables_init_order($aColumns, $sIndexColumn, $sTable, $join, $where, $additionalSelect,$groupby, $orderby);

            $output  = $result['output'];
            $rResult = $result['rResult'];



            $footer_data['total_amount'] = 0;
            foreach ($rResult as $aRow) {
               
                
                $row = [];
                
                for ($i = 0; $i < count($aColumns); $i++) {
                  
                    
                    if ($i == 0) {
                        $row[] = $aRow['nome_profissional'];
                    }
                    
                     // TIPO CONSULTA
                    if($aRow['color']){
                    $label_class_tipo = $aRow['color'];
                    }else{
                       $label_class_tipo = "info"; 
                    }
                    $type = $aRow['type']; 
                    $label_tipo = '<span data-color="$label_class_tipo"  class="label label-' . $label_class_tipo . '">' . $type . '</span>';

                     //$data_hora =  _d($aRow['date']); 
                     $data_hora =  date("d/m/Y H:i", strtotime($aRow['date'])); 
                    // STATUS
                    if($aRow['id']){
                        if ($aRow['company']) {
                            // CANCELADO
                            if ($aRow['cancelled'] && $aRow['finished'] == 0) {
                                $status = '<span class="label label-danger">' . strtoupper(_l('appointment_cancelled')) . '</span>';
                                $button_hora = '<span class="label label-danger">' . $data_hora . '</span>';
                                // FALTOU
                            } else if (!$aRow['finished'] && !$aRow['cancelled'] && $aRow['approved'] == 0 && date('Y-m-d H:i', strtotime($aRow['date'])) < date('Y-m-d H:i')) {
                                $button_hora = '<span class="label label-danger">' . $data_hora . '</span>';
                                $status = '<span class="label label-danger">' . strtoupper('faltou') . '</span>';
                                // CONFIRMADO
                            } else if (!$aRow['finished'] && !$aRow['cancelled'] && $aRow['approved'] == 1 && $aRow['confirmar_chegada'] == 0 ) {
                                $label_status = strtoupper('Confirmado') ;
                                $status = '<button class="label label-info mleft5" data-toggle="tooltip" data-id="' . $aRow['id'] . '" onclick="appointmentUpdateModal(this)">'.$label_status.'</button>';
                                $button_hora = '<span class="label label-primary btn-xs mleft5" data-toggle="tooltip" title="Agendamento" data-id="' . $aRow['id'] . '" >'.$data_hora.'</span>';
                                // AGENDADO
                            } else if (!$aRow['finished'] && !$aRow['cancelled'] && $aRow['approved'] == 0) {
                                $label_status = strtoupper('Agendado') ;
                                $status = '<button class="label label-warning mleft5" data-toggle="tooltip" data-id="' . $aRow['id'] . '" onclick="appointmentUpdateModal(this)">'.$label_status.'</button>';
                                $button_hora = '<span class="label label-primary btn-xs mleft5" data-toggle="tooltip" title="Agendamento" data-id="' . $aRow['id'] . '" >'.$data_hora.'</span>';

                                // FILA DE ESPERA
                            } else if ($aRow['confirmar_chegada'] == 1 && $aRow['finished']== 0 && $aRow['inicio_atendimento']== 0) {
                                $status = '<span class="label label-warning">' . strtoupper('EM ESPERA') . '</span>';
                                $button_hora = '<span class="label label-success">' . $data_hora . '</span>';
                                //EM ATENDIMENTO
                            } else if ($aRow['confirmar_chegada'] == 1 && $aRow['finished']== 0 && $aRow['inicio_atendimento']== 1) {
                                $status = '<span class="label label-primary">' . strtoupper('EM ATENDIMENTO') . '</span>';
                                $button_hora = '<span class="label label-success">' . $data_hora . '</span>';
                                //ATENDIDO
                            }else if ($aRow['finished'] == 1) {
                                $status = '<span class="label label-success">' . strtoupper('ATENDIDO') . '</span>';
                                $button_hora = '<span class="label label-success">' . $data_hora . '</span>';
                            }
                        }else{
                            if($aRow['bloqueio'] == 1){
                             $status = '<span class="label label-danger">' . strtoupper('Bloqueado') . '</span>';   
                             $button_hora = '<span class="label label-danger">' . $data_hora . '</span>';
                            }else{
                            $label_status = strtoupper('disponível') ;
                            $status = '<button class="label label-default mleft5" data-toggle="tooltip" data-id="' . $aRow['id'] . '" onclick="appointmentUpdateModal(this)">'.$label_status.'</button>';
                            $button_hora = '<span class="label label-primary btn-xs mleft5" data-toggle="tooltip" title="Agendamento" data-id="' . $aRow['id'] . '" >'.$data_hora.'</span>';
                            }

                        }
                    }


                   // QUANTIDAE
                     $row[] = $aRow['quantidade'];
                   // TIPO
                     $row[] = $label_tipo;
                     
                   // CONVENIO
                    if($convenio_agendamento){
                    if($aRow['convenio']){
                        $label_class_convenio = $aRow['convenio_cor'];
                        if(!$label_class_convenio){
                            $label_class_convenio = 'default';
                        }
                        $convenio = $aRow['convenio'];
                        $row[] = $convenio; // '<span   class="label label-' . $label_class_convenio . '">' . $convenio . '</span>';        
                    }else{
                        $row[] = "";
                    }
                    }else{
                        $row[] = "TODOS";
                    }
                   // STATUS   
                   //  $row[] = $status;

                    /*
                    //procedimentos
                    $procedimentos = $aRow['procedimentos'];
                    $descricao_procedimento = "";
                    if($procedimentos){
                    $proc_agenda =  $this->invoice_items_model->get_agenda($procedimentos);
                    foreach ($proc_agenda as $proc) {
                        $descricao_procedimento .= '<span   >' .  $proc['description'] . '</span> <br>';
                    }
                   }
                   $row[] = $descricao_procedimento; */

                   

                    //$row[] = $_data;
                }
                $output['aaData'][] = $row;
            }

            $footer_data['total_amount'] = app_format_money($footer_data['total_amount'], $currency->name);
            $output['sums']              = $footer_data;
            echo json_encode($output);
            die();
        }
    }
    
    public function payments_received()
    {

        if ($this->input->is_ajax_request()) {

            $this->load->model('currencies_model');

            $this->load->model('payment_modes_model');
            
            $this->load->model('Caixas_model');
            
            

            $payment_gateways = $this->payment_modes_model->get_payment_gateways(true);

            $select           = [

                db_prefix() . 'invoicepaymentrecords.id',

                db_prefix() . 'invoicepaymentrecords.date',

                'invoiceid',

                'tblinvoices.status',

                get_sql_select_client_company(),

                db_prefix().'conta_financeira.nome',

                db_prefix() . 'convenio.name',

                'paymentmode',

                'transactionid',

                '(select numero from tblnotafiscal n where n.invoice_id = invoiceid and n.conta_id = tblinvoicepaymentrecords.conta_id  group by invoice_id) as numero_nf',
                //db_prefix() . 'invoicepaymentrecords.numero_nf',
                
                

                'tblinvoicepaymentrecords.note',

                'amount',
                
                db_prefix() . 'caixas.name as caixa',
                

                db_prefix() . 'invoicepaymentrecords.daterecorded as daterecorded',

                db_prefix() . 'invoicepaymentrecords.userid as userid',

            ];

            $where = [

                'AND tblinvoices.status != 5',

            ];



            $custom_date_select = $this->get_where_report_period(db_prefix() . 'invoicepaymentrecords.date');

            if ($custom_date_select != '') {

                array_push($where, $custom_date_select);

            }



            $by_currency = $this->input->post('report_currency');

            if ($by_currency) {

                $currency = $this->currencies_model->get($by_currency);

                array_push($where, 'AND currency=' . $this->db->escape_str($by_currency));

            } else {

                $currency = $this->currencies_model->get_base_currency();

            }



            $aColumns     = $select;

            $sIndexColumn = 'id';

            $sTable       = db_prefix() . 'invoicepaymentrecords';

            $join         = [

                'JOIN ' . db_prefix() . 'invoices ON ' . db_prefix() . 'invoices.id = ' . db_prefix() . 'invoicepaymentrecords.invoiceid',

                'JOIN ' . db_prefix() . 'conta_financeira ON ' . db_prefix() . 'conta_financeira.id = ' . db_prefix() . 'invoicepaymentrecords.conta_id',

                'LEFT JOIN ' . db_prefix() . 'clients ON ' . db_prefix() . 'clients.userid = ' . db_prefix() . 'invoices.clientid',

                'LEFT JOIN ' . db_prefix() . 'payment_modes ON ' . db_prefix() . 'payment_modes.id = ' . db_prefix() . 'invoicepaymentrecords.paymentmode',

                'LEFT JOIN ' . db_prefix() . 'convenio ON ' . db_prefix() . 'convenio.id = ' . db_prefix() . 'invoices.convenio',
                
                'LEFT JOIN ' . db_prefix() . 'caixas_registros ON ' . db_prefix() . 'caixas_registros.id = ' . db_prefix() . 'invoicepaymentrecords.caixa_id',
                
                'LEFT JOIN ' . db_prefix() . 'caixas ON ' . db_prefix() . 'caixas.id = ' . db_prefix() . 'caixas_registros.caixa_id'

            ];

            

             // FILTRO MÃ‰DICO

          

            

            if ($this->input->post('medicos_pagamento_recebido')) {

                $medicos = $this->input->post('medicos_pagamento_recebido');

                $_medicos = [];

                if (is_array($medicos)) {

                    foreach ($medicos as $medico) {

                        if ($medico != '') {

                            array_push($_medicos, $this->db->escape_str($medico));

                        }

                    }

                }

             //   echo 'ID : '.$medicos; exit;

                if (count($_medicos) > 0) {

                    array_push($where, ' AND '. db_prefix() . 'conta_financeira.id IN (' . implode(', ', $_medicos) . ') AND tblinvoices.deleted = 0');

                }

            }

            

            // FILTRO CONVÃŠNIOS

            if ($this->input->post('convenios_pagamento')) {

                $convenios = $this->input->post('convenios_pagamento');

               

                $_convenios = [];

                if (is_array($convenios)) {

                    foreach ($convenios as $convenio) {

                        if ($convenio != '') {

                            array_push($_convenios, $this->db->escape_str($convenio));

                        }

                    }

                }

                if (count($_convenios) > 0) {

                    array_push($where, ' AND '. db_prefix() . 'invoices.convenio IN (' . implode(', ', $_convenios) . ')');

                }

            }

            // FILTRO CAIXA

            if ($this->input->post('caixa_pagamento_recebido')) {

                $caixas = $this->input->post('caixa_pagamento_recebido');

               

                $_caixa = [];

                if (is_array($caixas)) {

                    foreach ($caixas as $caixa) {

                        if ($caixa != '') {

                            array_push($_caixa, $this->db->escape_str($caixa));

                        }

                    }

                }

                if (count($_caixa) > 0) {

                    array_push($where, ' AND '. db_prefix() . 'caixas_registros.caixa_id IN (' . implode(', ', $_caixa) . ')');

                }

            }
            
             array_push($where, ' AND '. db_prefix() . 'invoicepaymentrecords.deleted = 0');

            
            $result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [

                'number',

                'clientid',

                db_prefix() . 'payment_modes.name',

                db_prefix() . 'payment_modes.id as paymentmodeid',

                'paymentmethod',

                'deleted_customer_name',
                
                db_prefix() . 'invoicepaymentrecords.numero_nf as numero_nf_avulso',

            ]);



            $output  = $result['output'];

            $rResult = $result['rResult'];



            $footer_data['total_amount'] = 0;

            foreach ($rResult as $aRow) {

                $row = [];

                

                for ($i = 0; $i < count($aColumns); $i++) {

                    if (strpos($aColumns[$i], 'as') !== false && !isset($aRow[$aColumns[$i]])) {

                        $_data = $aRow[strafter($aColumns[$i], 'as ')];

                    } else {

                        $_data = $aRow[$aColumns[$i]];

                    }

       
                    if ($aColumns[$i] == 'paymentmode') {

                        $_data = $aRow['name'];

                        if (is_null($aRow['paymentmodeid'])) {

                            foreach ($payment_gateways as $gateway) {

                                if ($aRow['paymentmode'] == $gateway['id']) {

                                    $_data = $gateway['name'];

                                }

                            }

                        }

                        if (!empty($aRow['paymentmethod'])) {

                            $_data .= ' - ' . $aRow['paymentmethod'];

                        }

                    } elseif ($aColumns[$i] == db_prefix() . 'invoicepaymentrecords.id') {

                        $_data = '<a href="' . admin_url('payments/payment/' . $_data) . '" target="_blank">' . $_data . '</a>';

                    } elseif ($aColumns[$i] == db_prefix() . 'invoicepaymentrecords.date') {

                        $_data = _d($_data);

                    } elseif ($aColumns[$i] == 'invoiceid') {

                        $status_invoice = format_invoice_status($aRow[db_prefix() . 'invoices.status']);

                        $_data = '<a href="' . admin_url('invoices/list_invoices/' . $aRow[$aColumns[$i]]) . '" target="_blank">' . format_invoice_number($aRow['invoiceid']) . '</a>';

                    } elseif ($aColumns[$i] == 'tblinvoices.status') {

                        $status_invoice = format_invoice_status($aRow[db_prefix() . 'invoices.status']);

                        $_data =  $status_invoice;

                    } elseif ($i == 3) {

                        if (empty($aRow['deleted_customer_name'])) {

                            $_data = '<a href="' . admin_url('clients/client/' . $aRow['clientid']) . '" target="_blank">' . $aRow['company'] . '</a>';

                        } else {

                            $row[] = $aRow['deleted_customer_name'];

                        }

                    } elseif ($aColumns[$i] == 'amount') {

                        $footer_data['total_amount'] += $_data;

                        $_data = app_format_money($_data, $currency->name);

                    }else if ($aColumns[$i] == 'caixa') {

                         $row[] = $aRow['caixa'];

                    }
                    
                    
                    elseif ($i == 13) {

                        $usuario = get_staff_full_name($aRow['userid']);

                        $log = $usuario.' <br> '.$aRow['daterecorded'];

                        $_data = $log;

                    }elseif ($i == 9) {
                        if($aRow['numero_nf']){
                            
                         $_data =  $aRow['numero_nf'];   
                        }else{
                         $_data =  $aRow['numero_nf_avulso'];   
                        }
                         

                    }
                    

                    

                    

                    



                    $row[] = $_data;

                }

                $output['aaData'][] = $row;

            }



            $footer_data['total_amount'] = app_format_money($footer_data['total_amount'], $currency->name);

            $output['sums']              = $footer_data;

            echo json_encode($output);

            die();

        }

    }

        
    public function payments_received_forma_pagamento()
    {
        if ($this->input->is_ajax_request()) {
            $this->load->model('currencies_model');
            $this->load->model('payment_modes_model');
            $payment_gateways = $this->payment_modes_model->get_payment_gateways(true);
            $select           = [
                'count(*)',
                 db_prefix() . 'payment_modes.name as paymentmode',
              //  db_prefix() . 'invoicepaymentrecords.date',
                 'sum(amount) as amount',
                
              
            ];
            $where = [
                'AND status != 5',
            ];
            
            $custom_date_select = $this->get_where_report_period(db_prefix() . 'invoicepaymentrecords.date');
            if ($custom_date_select != '') {
                array_push($where, $custom_date_select);
            }

            $by_currency = $this->input->post('report_currency');
            if ($by_currency) {
                $currency = $this->currencies_model->get($by_currency);
                array_push($where, 'AND currency=' . $this->db->escape_str($by_currency));
            } else {
                $currency = $this->currencies_model->get_base_currency();
            }

            $aColumns     = $select;
            $sIndexColumn = 'id';
            $sTable       = db_prefix() . 'invoicepaymentrecords';
            $join         = [
                'JOIN ' . db_prefix() . 'invoices ON ' . db_prefix() . 'invoices.id = ' . db_prefix() . 'invoicepaymentrecords.invoiceid',
                'JOIN ' . db_prefix() . 'conta_financeira ON ' . db_prefix() . 'conta_financeira.id = ' . db_prefix() . 'invoicepaymentrecords.conta_id',
           //     'LEFT JOIN ' . db_prefix() . 'clients ON ' . db_prefix() . 'clients.userid = ' . db_prefix() . 'invoices.clientid',
                'LEFT JOIN ' . db_prefix() . 'payment_modes ON ' . db_prefix() . 'payment_modes.id = ' . db_prefix() . 'invoicepaymentrecords.paymentmode',
                'LEFT JOIN ' . db_prefix() . 'caixas_registros ON ' . db_prefix() . 'caixas_registros.id = ' . db_prefix() . 'invoicepaymentrecords.caixa_id',
            ];
            $GroupBy = 'GROUP BY tblpayment_modes.id';
            //$GroupBy = '';

            if ($this->input->post('medicos_resumo_forma_pagamento')) {
                $medicos = $this->input->post('medicos_resumo_forma_pagamento');
                $_medicos = [];
                if (is_array($medicos)) {
                    foreach ($medicos as $medico) {
                        if ($medico != '') {
                            array_push($_medicos, $this->db->escape_str($medico));
                        }
                    }
                }
             //   echo 'ID : '.$medicos; exit;
                if (count($_medicos) > 0) {
                    array_push($where, ' AND '. db_prefix() . 'conta_financeira.id IN (' . implode(', ', $_medicos) . ')  ');
                }
            }
            // FILTRO CAIXA

            if ($this->input->post('caixa_forma_pagamento')) {

                $caixas = $this->input->post('caixa_forma_pagamento');
                $_caixa = [];

                if (is_array($caixas)) {
                    foreach ($caixas as $caixa) {
                        if ($caixa != '') {
                            array_push($_caixa, $this->db->escape_str($caixa));
                        }
                    }
                }
                if (count($_caixa) > 0) {
                    array_push($where, ' AND '. db_prefix() . 'caixas_registros.caixa_id IN (' . implode(', ', $_caixa) . ')');
                }

            }
            
           array_push($where, ' AND '. db_prefix() . 'invoicepaymentrecords.deleted = 0');
            
            $result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, NULL, $GroupBy);

            $output  = $result['output'];
            $rResult = $result['rResult'];

            $footer_data['total_amount'] = 0;
          
            foreach ($rResult as $aRow) {
                $row = [];
                for ($i = 0; $i < count($aColumns); $i++) {
                    if (strpos($aColumns[$i], 'as') !== false && !isset($aRow[$aColumns[$i]])) {
                        $_data = $aRow[strafter($aColumns[$i], 'as ')];
                    } else {
                        $_data = $aRow[$aColumns[$i]];
                    }
                    
                    if ($i == 2) {
                       
                        $footer_data['total_amount'] += $_data;
                        $_data = app_format_money($_data, $currency->name);
                        
                    }

                    $row[] .= $_data;
                }
               
                $output['aaData'][] = $row;
            }
            
            $footer_data['total_amount'] = app_format_money($footer_data['total_amount'], $currency->name);
            $output['sums']              = $footer_data;
            echo json_encode($output);
            die();
        }
    }
    
    public function payments_received_conta_financeira()
    {
        if ($this->input->is_ajax_request()) {
            $this->load->model('currencies_model');
            $this->load->model('payment_modes_model');
            $payment_gateways = $this->payment_modes_model->get_payment_gateways(true);
            $select           = [
                db_prefix().'conta_financeira.nome',
                'sum(amount) as amount',
            ];
            $where = [
                'AND tblinvoices.status != 5',
            ];

            $custom_date_select = $this->get_where_report_period(db_prefix() . 'invoicepaymentrecords.date');
            if ($custom_date_select != '') {
                array_push($where, $custom_date_select);
            }

            $by_currency = $this->input->post('report_currency');
            if ($by_currency) {
                $currency = $this->currencies_model->get($by_currency);
                array_push($where, 'AND currency=' . $this->db->escape_str($by_currency));
            } else {
                $currency = $this->currencies_model->get_base_currency();
            }

            $aColumns     = $select;
            $sIndexColumn = 'id';
            $sTable       = db_prefix() . 'invoicepaymentrecords';
            $join         = [
                'JOIN ' . db_prefix() . 'invoices ON ' . db_prefix() . 'invoices.id = ' . db_prefix() . 'invoicepaymentrecords.invoiceid',
                'JOIN ' . db_prefix() . 'conta_financeira ON ' . db_prefix() . 'conta_financeira.id = ' . db_prefix() . 'invoicepaymentrecords.conta_id',
                'LEFT JOIN ' . db_prefix() . 'clients ON ' . db_prefix() . 'clients.userid = ' . db_prefix() . 'invoices.clientid',
                'LEFT JOIN ' . db_prefix() . 'payment_modes ON ' . db_prefix() . 'payment_modes.id = ' . db_prefix() . 'invoicepaymentrecords.paymentmode',
                'LEFT JOIN ' . db_prefix() . 'convenio ON ' . db_prefix() . 'convenio.id = ' . db_prefix() . 'invoices.convenio',
            ];
            $currency = $this->currencies_model->get_base_currency();
             // FILTRO MÉDICO
          
            
            if ($this->input->post('medicos_conta_financeira')) {
                $medicos = $this->input->post('medicos_conta_financeira');
                $_medicos = [];
                if (is_array($medicos)) {
                    foreach ($medicos as $medico) {
                        if ($medico != '') {
                            array_push($_medicos, $this->db->escape_str($medico));
                        }
                    }
                }
             //   echo 'ID : '.$medicos; exit;
                if (count($_medicos) > 0) {
                    array_push($where, ' AND '. db_prefix() . 'conta_financeira.id IN (' . implode(', ', $_medicos) . ') AND tblinvoices.deleted = 0');
                }
            }
            
             // FILTRO CONVÊNIOS
            if ($this->input->post('convenios_pagamento_conta')) {
                $convenios = $this->input->post('convenios_pagamento_conta');
             
                $_convenios = [];
                if (is_array($convenios)) {
                    foreach ($convenios as $convenio) {
                        if ($convenio != '') {
                            array_push($_convenios, $this->db->escape_str($convenio));
                        }
                    }
                }
                if (count($_convenios) > 0) {
                    array_push($where, ' AND '. db_prefix() . 'invoices.convenio IN (' . implode(', ', $_convenios) . ')');
                }
            }
            array_push($where, ' AND '. db_prefix() . 'invoicepaymentrecords.deleted = 0');
            $GroupBy = 'GROUP BY tblconta_financeira.id';
            $result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, NULL, $GroupBy);

            $output  = $result['output'];
            $rResult = $result['rResult'];

            $footer_data['total_amount'] = 0;
            foreach ($rResult as $aRow) {
                $row = [];
                
                for ($i = 0; $i < count($aColumns); $i++) {
                    if (strpos($aColumns[$i], 'as') !== false && !isset($aRow[$aColumns[$i]])) {
                        $_data = $aRow[strafter($aColumns[$i], 'as ')];
                    } else {
                        $_data = $aRow[$aColumns[$i]];
                    }
                    
                   
                    
                     if ($i == 1) {
                        $footer_data['total_amount'] += $_data;
                        $_data = app_format_money($_data, $currency->name);
                    }
                    
                    
                    

                    $row[] = $_data;
                }
                $output['aaData'][] = $row;
            }

            $footer_data['total_amount'] = app_format_money($footer_data['total_amount'], $currency->name);
            $output['sums']              = $footer_data;
            echo json_encode($output);
            die();
        }
    }

    public function proposals_report()
    {
        if ($this->input->is_ajax_request()) {
            $this->load->model('currencies_model');
            $this->load->model('proposals_model');

            $proposalsTaxes    = $this->distinct_taxes('proposal');
            $totalTaxesColumns = count($proposalsTaxes);

            $select = [
                'id',
                'subject',
                'proposal_to',
                'date',
                'open_till',
                'subtotal',
                'total',
                'total_tax',
                'discount_total',
                'adjustment',
                'status',
            ];

            $proposalsTaxesSelect = array_reverse($proposalsTaxes);

            foreach ($proposalsTaxesSelect as $key => $tax) {
                array_splice($select, 8, 0, '(
                    SELECT CASE
                    WHEN discount_percent != 0 AND discount_type = "before_tax" THEN ROUND(SUM((qty*rate/100*' . db_prefix() . 'item_tax.taxrate) - (qty*rate/100*' . db_prefix() . 'item_tax.taxrate * discount_percent/100)),' . get_decimal_places() . ')
                    WHEN discount_total != 0 AND discount_type = "before_tax" THEN ROUND(SUM((qty*rate/100*' . db_prefix() . 'item_tax.taxrate) - (qty*rate/100*' . db_prefix() . 'item_tax.taxrate * (discount_total/subtotal*100) / 100)),' . get_decimal_places() . ')
                    ELSE ROUND(SUM(qty*rate/100*' . db_prefix() . 'item_tax.taxrate),' . get_decimal_places() . ')
                    END
                    FROM ' . db_prefix() . 'itemable
                    INNER JOIN ' . db_prefix() . 'item_tax ON ' . db_prefix() . 'item_tax.itemid=' . db_prefix() . 'itemable.id
                    WHERE ' . db_prefix() . 'itemable.rel_type="proposal" AND taxname="' . $tax['taxname'] . '" AND taxrate="' . $tax['taxrate'] . '" AND ' . db_prefix() . 'itemable.rel_id=' . db_prefix() . 'proposals.id) as total_tax_single_' . $key);
            }

            $where              = [];
            $custom_date_select = $this->get_where_report_period();
            if ($custom_date_select != '') {
                array_push($where, $custom_date_select);
            }

            if ($this->input->post('proposal_status')) {
                $statuses  = $this->input->post('proposal_status');
                $_statuses = [];
                if (is_array($statuses)) {
                    foreach ($statuses as $status) {
                        if ($status != '') {
                            array_push($_statuses, $this->db->escape_str($status));
                        }
                    }
                }
                if (count($_statuses) > 0) {
                    array_push($where, 'AND status IN (' . implode(', ', $_statuses) . ')');
                }
            }

            if ($this->input->post('proposals_sale_agents')) {
                $agents  = $this->input->post('proposals_sale_agents');
                $_agents = [];
                if (is_array($agents)) {
                    foreach ($agents as $agent) {
                        if ($agent != '') {
                            array_push($_agents, $this->db->escape_str($agent));
                        }
                    }
                }
                if (count($_agents) > 0) {
                    array_push($where, 'AND assigned IN (' . implode(', ', $_agents) . ')');
                }
            }


            $by_currency = $this->input->post('report_currency');
            if ($by_currency) {
                $currency = $this->currencies_model->get($by_currency);
                array_push($where, 'AND currency=' . $this->db->escape_str($by_currency));
            } else {
                $currency = $this->currencies_model->get_base_currency();
            }

            $aColumns     = $select;
            $sIndexColumn = 'id';
            $sTable       = db_prefix() . 'proposals';
            $join         = [];

            $result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [
                'rel_id',
                'rel_type',
                'discount_percent',
            ]);

            $output  = $result['output'];
            $rResult = $result['rResult'];

            $footer_data = [
                'total'          => 0,
                'subtotal'       => 0,
                'total_tax'      => 0,
                'discount_total' => 0,
                'adjustment'     => 0,
            ];

            foreach ($proposalsTaxes as $key => $tax) {
                $footer_data['total_tax_single_' . $key] = 0;
            }

            foreach ($rResult as $aRow) {
                $row = [];

                $row[] = '<a href="' . admin_url('proposals/list_proposals/' . $aRow['id']) . '" target="_blank">' . format_proposal_number($aRow['id']) . '</a>';

                $row[] = '<a href="' . admin_url('proposals/list_proposals/' . $aRow['id']) . '" target="_blank">' . $aRow['subject'] . '</a>';

                if ($aRow['rel_type'] == 'lead') {
                    $row[] = '<a href="#" onclick="init_lead(' . $aRow['rel_id'] . ');return false;" target="_blank" data-toggle="tooltip" data-title="' . _l('lead') . '">' . $aRow['proposal_to'] . '</a>' . '<span class="hide">' . _l('lead') . '</span>';
                } elseif ($aRow['rel_type'] == 'customer') {
                    $row[] = '<a href="' . admin_url('clients/client/' . $aRow['rel_id']) . '" target="_blank" data-toggle="tooltip" data-title="' . _l('client') . '">' . $aRow['proposal_to'] . '</a>' . '<span class="hide">' . _l('client') . '</span>';
                } else {
                    $row[] = '';
                }

                $row[] = _d($aRow['date']);

                $row[] = _d($aRow['open_till']);

                $row[] = app_format_money($aRow['subtotal'], $currency->name);
                $footer_data['subtotal'] += $aRow['subtotal'];

                $row[] = app_format_money($aRow['total'], $currency->name);
                $footer_data['total'] += $aRow['total'];

                $row[] = app_format_money($aRow['total_tax'], $currency->name);
                $footer_data['total_tax'] += $aRow['total_tax'];

                $t = $totalTaxesColumns - 1;
                $i = 0;
                foreach ($proposalsTaxes as $tax) {
                    $row[] = app_format_money(($aRow['total_tax_single_' . $t] == null ? 0 : $aRow['total_tax_single_' . $t]), $currency->name);
                    $footer_data['total_tax_single_' . $i] += ($aRow['total_tax_single_' . $t] == null ? 0 : $aRow['total_tax_single_' . $t]);
                    $t--;
                    $i++;
                }

                $row[] = app_format_money($aRow['discount_total'], $currency->name);
                $footer_data['discount_total'] += $aRow['discount_total'];

                $row[] = app_format_money($aRow['adjustment'], $currency->name);
                $footer_data['adjustment'] += $aRow['adjustment'];

                $row[]              = format_proposal_status($aRow['status']);
                $output['aaData'][] = $row;
            }

            foreach ($footer_data as $key => $total) {
                $footer_data[$key] = app_format_money($total, $currency->name);
            }

            $output['sums'] = $footer_data;
            echo json_encode($output);
            die();
        }
    }

    public function estimates_report()
    {
        if ($this->input->is_ajax_request()) {
            $this->load->model('currencies_model');
            $this->load->model('estimates_model');

            $estimateTaxes     = $this->distinct_taxes('estimate');
            $totalTaxesColumns = count($estimateTaxes);

            $select = [
                'number',
                get_sql_select_client_company(),
                'invoiceid',
                'YEAR(date) as year',
                'date',
                'expirydate',
                'subtotal',
                'total',
                'total_tax',
                'discount_total',
                'adjustment',
                'reference_no',
                'status',
            ];

            $estimatesTaxesSelect = array_reverse($estimateTaxes);

            foreach ($estimatesTaxesSelect as $key => $tax) {
                array_splice($select, 9, 0, '(
                    SELECT CASE
                    WHEN discount_percent != 0 AND discount_type = "before_tax" THEN ROUND(SUM((qty*rate/100*' . db_prefix() . 'item_tax.taxrate) - (qty*rate/100*' . db_prefix() . 'item_tax.taxrate * discount_percent/100)),' . get_decimal_places() . ')
                    WHEN discount_total != 0 AND discount_type = "before_tax" THEN ROUND(SUM((qty*rate/100*' . db_prefix() . 'item_tax.taxrate) - (qty*rate/100*' . db_prefix() . 'item_tax.taxrate * (discount_total/subtotal*100) / 100)),' . get_decimal_places() . ')
                    ELSE ROUND(SUM(qty*rate/100*' . db_prefix() . 'item_tax.taxrate),' . get_decimal_places() . ')
                    END
                    FROM ' . db_prefix() . 'itemable
                    INNER JOIN ' . db_prefix() . 'item_tax ON ' . db_prefix() . 'item_tax.itemid=' . db_prefix() . 'itemable.id
                    WHERE ' . db_prefix() . 'itemable.rel_type="estimate" AND taxname="' . $tax['taxname'] . '" AND taxrate="' . $tax['taxrate'] . '" AND ' . db_prefix() . 'itemable.rel_id=' . db_prefix() . 'estimates.id) as total_tax_single_' . $key);
            }

            $where              = [];
            $custom_date_select = $this->get_where_report_period();
            if ($custom_date_select != '') {
                array_push($where, $custom_date_select);
            }

            if ($this->input->post('estimate_status')) {
                $statuses  = $this->input->post('estimate_status');
                $_statuses = [];
                if (is_array($statuses)) {
                    foreach ($statuses as $status) {
                        if ($status != '') {
                            array_push($_statuses, $this->db->escape_str($status));
                        }
                    }
                }
                if (count($_statuses) > 0) {
                    array_push($where, 'AND status IN (' . implode(', ', $_statuses) . ')');
                }
            }

            if ($this->input->post('sale_agent_estimates')) {
                $agents  = $this->input->post('sale_agent_estimates');
                $_agents = [];
                if (is_array($agents)) {
                    foreach ($agents as $agent) {
                        if ($agent != '') {
                            array_push($_agents, $this->db->escape_str($agent));
                        }
                    }
                }
                if (count($_agents) > 0) {
                    array_push($where, 'AND sale_agent IN (' . implode(', ', $_agents) . ')');
                }
            }

            $by_currency = $this->input->post('report_currency');
            if ($by_currency) {
                $currency = $this->currencies_model->get($by_currency);
                array_push($where, 'AND currency=' . $this->db->escape_str($by_currency));
            } else {
                $currency = $this->currencies_model->get_base_currency();
            }

            $aColumns     = $select;
            $sIndexColumn = 'id';
            $sTable       = db_prefix() . 'estimates';
            $join         = [
                'LEFT JOIN ' . db_prefix() . 'clients ON ' . db_prefix() . 'clients.userid = ' . db_prefix() . 'estimates.clientid',
            ];

            $result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [
                'userid',
                'clientid',
                db_prefix() . 'estimates.id',
                'discount_percent',
                'deleted_customer_name',
            ]);

            $output  = $result['output'];
            $rResult = $result['rResult'];

            $footer_data = [
                'total'          => 0,
                'subtotal'       => 0,
                'total_tax'      => 0,
                'discount_total' => 0,
                'adjustment'     => 0,
            ];

            foreach ($estimateTaxes as $key => $tax) {
                $footer_data['total_tax_single_' . $key] = 0;
            }

            foreach ($rResult as $aRow) {
                $row = [];

                $row[] = '<a href="' . admin_url('estimates/list_estimates/' . $aRow['id']) . '" target="_blank">' . format_estimate_number($aRow['id']) . '</a>';

                if (empty($aRow['deleted_customer_name'])) {
                    $row[] = '<a href="' . admin_url('clients/client/' . $aRow['userid']) . '" target="_blank">' . $aRow['company'] . '</a>';
                } else {
                    $row[] = $aRow['deleted_customer_name'];
                }

                if ($aRow['invoiceid'] === null) {
                    $row[] = '';
                } else {
                    $row[] = '<a href="' . admin_url('invoices/list_invoices/' . $aRow['invoiceid']) . '" target="_blank">' . format_invoice_number($aRow['invoiceid']) . '</a>';
                }

                $row[] = $aRow['year'];

                $row[] = _d($aRow['date']);

                $row[] = _d($aRow['expirydate']);

                $row[] = app_format_money($aRow['subtotal'], $currency->name);
                $footer_data['subtotal'] += $aRow['subtotal'];

                $row[] = app_format_money($aRow['total'], $currency->name);
                $footer_data['total'] += $aRow['total'];

                $row[] = app_format_money($aRow['total_tax'], $currency->name);
                $footer_data['total_tax'] += $aRow['total_tax'];

                $t = $totalTaxesColumns - 1;
                $i = 0;
                foreach ($estimateTaxes as $tax) {
                    $row[] = app_format_money(($aRow['total_tax_single_' . $t] == null ? 0 : $aRow['total_tax_single_' . $t]), $currency->name);
                    $footer_data['total_tax_single_' . $i] += ($aRow['total_tax_single_' . $t] == null ? 0 : $aRow['total_tax_single_' . $t]);
                    $t--;
                    $i++;
                }

                $row[] = app_format_money($aRow['discount_total'], $currency->name);
                $footer_data['discount_total'] += $aRow['discount_total'];

                $row[] = app_format_money($aRow['adjustment'], $currency->name);
                $footer_data['adjustment'] += $aRow['adjustment'];


                $row[] = $aRow['reference_no'];

                $row[] = format_estimate_status($aRow['status']);

                $output['aaData'][] = $row;
            }
            foreach ($footer_data as $key => $total) {
                $footer_data[$key] = app_format_money($total, $currency->name);
            }
            $output['sums'] = $footer_data;
            echo json_encode($output);
            die();
        }
    }

    private function get_where_report_period($field = 'date')
    {
        $months_report      = $this->input->post('report_months');
        $custom_date_select = '';
        if ($months_report != '') {
            if (is_numeric($months_report)) {
                // Last month
                if ($months_report == '1') {
                    $beginMonth = date('Y-m-01', strtotime('first day of last month'));
                    $endMonth   = date('Y-m-t', strtotime('last day of last month'));
                } else {
                    $months_report = (int) $months_report;
                    $months_report--;
                    $beginMonth = date('Y-m-01', strtotime("-$months_report MONTH"));
                    $endMonth   = date('Y-m-t');
                }

                $custom_date_select = 'AND (' . $field . ' BETWEEN "' . $beginMonth . '" AND "' . $endMonth . '")';
            } elseif ($months_report == 'this_month') {
                $custom_date_select = 'AND (' . $field . ' BETWEEN "' . date('Y-m-01') . '" AND "' . date('Y-m-t') . '")';
            } elseif ($months_report == 'today') {
                $custom_date_select = 'AND (' . $field . ' = "' . date('Y-m-d') . '")';
            } elseif ($months_report == 'this_year') {
                $custom_date_select = 'AND (' . $field . ' BETWEEN "' .
                date('Y-m-d', strtotime(date('Y-01-01'))) .
                '" AND "' .
                date('Y-m-d', strtotime(date('Y-12-31'))) . '")';
            } elseif ($months_report == 'last_year') {
                $custom_date_select = 'AND (' . $field . ' BETWEEN "' .
                date('Y-m-d', strtotime(date(date('Y', strtotime('last year')) . '-01-01'))) .
                '" AND "' .
                date('Y-m-d', strtotime(date(date('Y', strtotime('last year')) . '-12-31'))) . '")';
            } elseif ($months_report == 'custom') {
                $from_date = to_sql_date($this->input->post('report_from'));
                $to_date   = to_sql_date($this->input->post('report_to'));
                if ($from_date == $to_date) {
                    $custom_date_select = 'AND ' . $field . ' = "' . $this->db->escape_str($from_date) . '"';
                } else {
                    $custom_date_select = 'AND (' . $field . ' BETWEEN "' . $this->db->escape_str($from_date) . '" AND "' . $this->db->escape_str($to_date) . '")';
                }
            }
        }

        return $custom_date_select;
    }
    
    private function get_where_report_period_diferente($field = 'date')
    {
        $months_report      = $this->input->post('report_months');
        $custom_date_select = '';
        if ($months_report != '') {
            if (is_numeric($months_report)) {
                // Last month
                if ($months_report == '1') {
                    $beginMonth = date('Y-m-01', strtotime('first day of last month'));
                    $endMonth   = date('Y-m-t', strtotime('last day of last month'));
                } else {
                    $months_report = (int) $months_report;
                    $months_report--;
                    $beginMonth = date('Y-m-01', strtotime("-$months_report MONTH"));
                    $endMonth   = date('Y-m-t');
                }

                $custom_date_select = 'AND (' . $field . ' NOT BETWEEN "' . $beginMonth . '" AND "' . $endMonth . '")';
            } elseif ($months_report == 'this_month') {
                $custom_date_select = 'AND (' . $field . ' NOT BETWEEN "' . date('Y-m-01') . '" AND "' . date('Y-m-t') . '")';
            }elseif ($months_report == 'today') {
                $custom_date_select = 'AND (' . $field . ' != "' . date('Y-m-d') . '")';
            } elseif ($months_report == 'this_year') {
                $custom_date_select = 'AND (' . $field . ' NOT BETWEEN "' .
                date('Y-m-d', strtotime(date('Y-01-01'))) .
                '" AND "' .
                date('Y-m-d', strtotime(date('Y-12-31'))) . '")';
            } elseif ($months_report == 'last_year') {
                $custom_date_select = 'AND (' . $field . ' NOT BETWEEN "' .
                date('Y-m-d', strtotime(date(date('Y', strtotime('last year')) . '-01-01'))) .
                '" AND "' .
                date('Y-m-d', strtotime(date(date('Y', strtotime('last year')) . '-12-31'))) . '")';
            } elseif ($months_report == 'custom') {
                $from_date = to_sql_date($this->input->post('report_from'));
                $to_date   = to_sql_date($this->input->post('report_to'));
                if ($from_date == $to_date) {
                    $custom_date_select = 'AND ' . $field . ' != "' . $this->db->escape_str($from_date) . '"';
                } else {
                    $custom_date_select = 'AND (' . $field . ' NOT BETWEEN "' . $this->db->escape_str($from_date) . '" AND "' . $this->db->escape_str($to_date) . '")';
                }
            }
        }

        return $custom_date_select;
    }
    

    public function items()
    {
        if ($this->input->is_ajax_request()) {
            $this->load->model('currencies_model');
            $v = $this->db->query('SELECT VERSION() as version')->row();
            // 5.6 mysql version don't have the ANY_VALUE function implemented.

            if ($v && strpos($v->version, '5.7') !== false) {
                $aColumns = [
                        'ANY_VALUE(description) as description',
                        'ANY_VALUE((SUM(' . db_prefix() . 'itemable.qty))) as quantity_sold',
                        'ANY_VALUE(SUM(rate*qty)) as rate',
                        'ANY_VALUE(AVG(rate*qty)) as avg_price',
                    ];
            } else {
                $aColumns = [
                        'description as description',
                        '(SUM(' . db_prefix() . 'itemable.qty)) as quantity_sold',
                        'SUM(rate*qty) as rate',
                        'AVG(rate*qty) as avg_price',
                    ];
            }

            $sIndexColumn = 'id';
            $sTable       = db_prefix() . 'itemable';
            $join         = ['JOIN ' . db_prefix() . 'invoices ON ' . db_prefix() . 'invoices.id = ' . db_prefix() . 'itemable.rel_id'];

            $where = ['AND rel_type="invoice"', 'AND status != 5', 'AND status=2'];

            $custom_date_select = $this->get_where_report_period();
            if ($custom_date_select != '') {
                array_push($where, $custom_date_select);
            }
            $by_currency = $this->input->post('report_currency');
            if ($by_currency) {
                $currency = $this->currencies_model->get($by_currency);
                array_push($where, 'AND currency=' . $this->db->escape_str($by_currency));
            } else {
                $currency = $this->currencies_model->get_base_currency();
            }

            if ($this->input->post('sale_agent_items')) {
                $agents  = $this->input->post('sale_agent_items');
                $_agents = [];
                if (is_array($agents)) {
                    foreach ($agents as $agent) {
                        if ($agent != '') {
                            array_push($_agents, $this->db->escape_str($agent));
                        }
                    }
                }
                if (count($_agents) > 0) {
                    array_push($where, 'AND sale_agent IN (' . implode(', ', $_agents) . ')');
                }
            }

            $result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [], 'GROUP by description');

            $output  = $result['output'];
            $rResult = $result['rResult'];

            $footer_data = [
                'total_amount' => 0,
                'total_qty'    => 0,
            ];

            foreach ($rResult as $aRow) {
                $row = [];

                $row[] = $aRow['description'];
                $row[] = $aRow['quantity_sold'];
                $row[] = app_format_money($aRow['rate'], $currency->name);
                $row[] = app_format_money($aRow['avg_price'], $currency->name);
                $footer_data['total_amount'] += $aRow['rate'];
                $footer_data['total_qty'] += $aRow['quantity_sold'];
                $output['aaData'][] = $row;
            }

            $footer_data['total_amount'] = app_format_money($footer_data['total_amount'], $currency->name);

            $output['sums'] = $footer_data;
            echo json_encode($output);
            die();
        }
    }
    
    // PRODUÇÃO MÉDICA
     public function sales_items_medico()
    {
        if ($this->input->is_ajax_request()) {
            $this->load->model('currencies_model');
            $v = $this->db->query('SELECT VERSION() as version')->row();
            // 5.6 mysql version don't have the ANY_VALUE function implemented.

           /* if ($v && strpos($v->version, '5.7') !== false) {
                $aColumns = [
                        'ANY_VALUE('.db_prefix().'clients.company) as cliente',
                        'ANY_VALUE((SUM(' . db_prefix() . 'itemable.qty))) as quantity_sold',
                        'ANY_VALUE(SUM(rate*qty)) as rate',
                        'ANY_VALUE(AVG(rate*qty)) as avg_price',
                    ];
            } else { */
            $custom_date_select = $this->get_where_report_period(db_prefix() . 'invoicepaymentrecords.date');
            $custom_date_select_diferente = $this->get_where_report_period_diferente(db_prefix() . 'invoicepaymentrecords.date');
          
            $aColumns = [
                        'number',
                        db_prefix() . 'medicos.nome_profissional as medico',
                        db_prefix() . 'centrocusto.nome as centro_custo', 
                        db_prefix() . 'convenio.name as convenio',
                        get_sql_select_client_company(),
                        db_prefix().'items_groups.name as categoria',
                        db_prefix().'items.description as procedimento',
                        db_prefix().'invoices.date as data_faturamento',
                        db_prefix().'invoicepaymentrecords.date as data_pagamento',
                        db_prefix().'itemable.qty as quantity_sold',
                        db_prefix() . 'itemable.rate as valor_procedimento', // preço unitário
                                  
                        "tblinvoices.datecreated as datecreated,
                        
                        tblitemable.valor_medico as valor_medico,
                        tblinvoices.addedfrom as addedfrom ",
                    
                        
                        
                    ];
           // }
            /*
             * ((tblitemable.rate * tblitemable.qty) 
                        - tblitemable.desconto_valor           
                        + (adjustment / (select count(*) as total from tblitemable t where t.rel_id = tblinvoices.id and rel_type = 'invoice' and t.medicoid = tblmedicos.medicoid)) 
      
                        - (SELECT COALESCE(SUM(tblitemable.rate),0) * COALESCE(tblitemable.qty,1) FROM tblcredits
                          inner join tblitemable on tblitemable.rel_id = tblcredits.credit_id WHERE tblcredits.invoice_id=tblinvoices.id and tblitemable.medicoid = tblmedicos.medicoid
                          and tblitemable.rel_type = 'credit_note')
                         / COALESCE((select count(*) as total from tblitemable t where t.rel_id = tblinvoices.id and rel_type = 'invoice' and t.medicoid = tblmedicos.medicoid))  

    
                        - (SELECT total   - (SELECT COALESCE(SUM(amount),0) FROM tblinvoicepaymentrecords 
                                left join tblconta_financeira on tblconta_financeira.id = tblinvoicepaymentrecords.conta_id
                                WHERE invoiceid = tblinvoices.id AND tblconta_financeira.nota_fiscal_propria = 0  $custom_date_select ) 
                        - (select COALESCE(SUM(tblitemable.rate),0) * COALESCE(tblitemable.qty,1)  from tblitemable where tblitemable.rel_id = tblinvoices.id and rel_type = 'invoice' and tblitemable.medicoid != tblmedicos.medicoid)
                        - (SELECT COALESCE(SUM(tblitemable.rate),0) * COALESCE(tblitemable.qty,1) FROM tblcredits inner join tblitemable on tblitemable.rel_id = tblcredits.credit_id WHERE tblcredits.invoice_id=tblinvoices.id and tblitemable.medicoid = tblmedicos.medicoid and rel_type = 'credit_note' )
                          )) as valor_faturado_produzido, 
             */
            $sIndexColumn = 'id';
            $sTable       = db_prefix() . 'invoices';
            $join         = [
                'JOIN ' . db_prefix() . 'clients ON ' . db_prefix() . 'clients.userid = ' . db_prefix() . 'invoices.clientid',
                'JOIN ' . db_prefix() . 'itemable ON ' . db_prefix() . 'itemable.rel_id = ' . db_prefix() . 'invoices.id AND '. db_prefix() . 'itemable.rel_type="invoice"',
                'JOIN ' . db_prefix() . 'items ON ' . db_prefix() . 'items.id = ' . db_prefix() . 'itemable.item_id',
                'JOIN ' . db_prefix() . 'items_groups ON ' . db_prefix() . 'items_groups.id = ' . db_prefix() . 'items.group_id',                
                'LEFT JOIN ' . db_prefix() . 'convenio ON ' . db_prefix() . 'convenio.id = ' . db_prefix() . 'invoices.convenio',
                'LEFT JOIN '. db_prefix() .'centrocusto ON ' . db_prefix() . 'centrocusto.ID = ' .db_prefix(). 'invoices.centrocustoid',
                'JOIN ' . db_prefix() . 'medicos ON ' . db_prefix() . 'medicos.medicoid = ' . db_prefix() . 'itemable.medicoid',
                'LEFT JOIN ' . db_prefix() . 'invoicepaymentrecords ON ' . db_prefix() . 'invoicepaymentrecords.invoiceid = ' . db_prefix() . 'invoices.id',
              //  'JOIN ' . db_prefix() . 'conta_financeira ON ' . db_prefix() . 'conta_financeira.id = ' . db_prefix() . 'invoicepaymentrecords.conta_id',

            ];
            
            
            $where = [' AND '.db_prefix() . 'invoices.status != 5',  ' AND '.db_prefix() . 'invoices.status IN (2)', 'AND tblinvoices.deleted = 0']; //,    fat pagas

            
            if ($custom_date_select != '') {
                array_push($where, $custom_date_select);
            }
            $by_currency = $this->input->post('report_currency');
            if ($by_currency) {
                $currency = $this->currencies_model->get($by_currency);
                array_push($where, 'AND currency=' . $this->db->escape_str($by_currency));
            } else {
                $currency = $this->currencies_model->get_base_currency();
            }

            if ($this->input->post('sale_agent_items')) {
                $agents  = $this->input->post('sale_agent_items');
                $_agents = [];
                if (is_array($agents)) {
                    foreach ($agents as $agent) {
                        if ($agent != '') {
                            array_push($_agents, $this->db->escape_str($agent));
                        }
                    }
                }
                if (count($_agents) > 0) {
                    array_push($where, 'AND sale_agent IN (' . implode(', ', $_agents) . ')');
                }
            }
            
            // FILTRO MÉDICO
            if ($this->input->post('medicos_producao')) {
                $medicos = $this->input->post('medicos_producao');
                $_medicos = [];
                if (is_array($medicos)) {
                    foreach ($medicos as $medico) {
                        if ($medico != '') {
                            array_push($_medicos, $this->db->escape_str($medico));
                        }
                    }
                }
                if (count($_medicos) > 0) {
                    array_push($where, ' AND '. db_prefix() . 'itemable.medicoid IN (' . implode(', ', $_medicos) . ')');
                }
            }
            
            // FILTRO CONVÊNIOS
            if ($this->input->post('convenio_producao_medico')) {
                $convenios = $this->input->post('convenio_producao_medico');
               
                $_convenios = [];
                if (is_array($convenios)) {
                    foreach ($convenios as $convenio) {
                        if ($convenio != '') {
                            array_push($_convenios, $this->db->escape_str($convenio));
                        }
                    }
                }
                if (count($_convenios) > 0) {
                    array_push($where, ' AND '. db_prefix() . 'invoices.convenio IN (' . implode(', ', $_convenios) . ')');
                }
            }
            
            // FILTRO CATEGORIAS
            if ($this->input->post('categorias_producao_medica')) {
                $categorias = $this->input->post('categorias_producao_medica');
               
                $_categorias = [];
                if (is_array($categorias)) {
                    foreach ($categorias as $categoriaa) {
                        if ($categoriaa != '') {
                            array_push($_categorias, $this->db->escape_str($categoriaa));
                        }
                    }
                }
                if (count($_categorias) > 0) {
                    array_push($where, ' AND '. db_prefix() . 'items_groups.id IN (' . implode(', ', $_categorias) . ')');
                }
            }
            
            //
            if ($this->input->post('procedimento_fatura_producao')) {
                $procedimentos = $this->input->post('procedimento_fatura_producao');
                // print_r($procedimentos); exit;
                $_procedimentos = [];
                if (is_array($procedimentos)) {
                    foreach ($procedimentos as $categoriaa) {
                        if ($categoriaa != '') {
                            array_push($_procedimentos, $this->db->escape_str($categoriaa));
                        }
                    }
                }
                if (count($_procedimentos) > 0) {
                    array_push($where, ' AND '. db_prefix() . 'items.id IN (' . implode(', ', $_procedimentos) . ')');
                }
            }
            
            $totalPaymentsColumnIndex = (17);

            $currency                          = $this->currencies_model->get_base_currency();
            $select[$totalPaymentsColumnIndex] = $select[$totalPaymentsColumnIndex] .= ' as amount_open';
        
            $GroupBy = ' GROUP BY tblinvoices.id, tblitemable.id';
            $orderBy = ' ORDER BY tblinvoicepaymentrecords.date DESC';
            $result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [],$GroupBy);
            //  $result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [], 'GROUP by ' . db_prefix() . 'invoicepaymentrecords.invoiceid');   EXEMPLO COM GROUP BY
            $output  = $result['output'];
            $rResult = $result['rResult'];

            $footer_data = [
                'total_amount' => 0,
                'total_qty'    => 0,
                'desconto'    => 0,
                'rate_total'    => 0,
               'total'           => 0,
                'subtotal'        => 0,
                'total_tax'       => 0,
                'discount_total'  => 0,
                'adjustment'      => 0,
                'applied_credits' => 0,
                'amount_open'     => 0,
                'valor_medico'     => 0,
                'valor_faturado_produzido'     => 0,
              
            ];

            foreach ($rResult as $aRow) {
                $row = [];
                
                $valor_pgto_antes = $aRow['valor_pgto_antes'];
                
                if($valor_pgto_antes > 0){
                    $info = "<i class='fa fa-exclamation-circle' ></i>";
                }else{
                    $info = "";
                }
                
                $_data_invoice = '<a href="' . admin_url('invoices/list_invoices/' . $aRow['number']) . '" target="_blank">' . format_invoice_number($aRow['number']) . '</a> '.' '." $info";
                $row[] = $_data_invoice;
                $row[] = $aRow['medico'];
              //  $row[] = $aRow['centro_custo'];
                $row[] = $aRow['convenio'];
                $row[] = $aRow['company'];
                $row[] = $aRow['categoria'];
                $row[] = $aRow['quantity_sold'];
                $footer_data['total_qty'] += $aRow['quantity_sold']; // quantidade
                
                
                $row[] = $aRow['procedimento'];
                
                $row[] = $aRow['data_faturamento'];
                $row[] = $aRow['data_pagamento'];
                
              
                $row[] = app_format_money($aRow['valor_medico'], $currency->name); // créditos
                $footer_data['valor_medico'] += $aRow['valor_medico'];//valor_medico
                $usuario = get_staff_full_name($aRow['addedfrom']);
              //  $log = $usuario.' <br> '.$aRow['datecreated'];
               // $row[] = $log; // créditos
                
               
                
                $output['aaData'][] = $row;
            }

           // $footer_data['total_amount'] = app_format_money($footer_data['total_amount'], $currency->name);
           // $footer_data['desconto_amount'] = app_format_money($footer_data['desconto_amount'], $currency->name);
           // $footer_data['amount_total_procedimento'] = app_format_money($footer_data['amount_total_procedimento'], $currency->name);

            foreach ($footer_data as $key => $total) {
                
                $footer_data[$key] = app_format_money($total, $currency->name);
            }
            
            $output['sums'] = $footer_data;
            echo json_encode($output);
            die();
        }
    }

    public function credit_notes()
    {
        if ($this->input->is_ajax_request()) {
            $credit_note_taxes = $this->distinct_taxes('credit_note');
            $totalTaxesColumns = count($credit_note_taxes);

            $this->load->model('currencies_model');

            $select = [
                'number',
                'date',
                get_sql_select_client_company(),
                'reference_no',
                'subtotal',
                'total',
                'total_tax',
                'discount_total',
                'adjustment',
                '(SELECT ' . db_prefix() . 'creditnotes.total - (
                  (SELECT COALESCE(SUM(amount),0) FROM ' . db_prefix() . 'credits WHERE ' . db_prefix() . 'credits.credit_id=' . db_prefix() . 'creditnotes.id)
                  +
                  (SELECT COALESCE(SUM(amount),0) FROM ' . db_prefix() . 'creditnote_refunds WHERE ' . db_prefix() . 'creditnote_refunds.credit_note_id=' . db_prefix() . 'creditnotes.id)
                  )
                ) as remaining_amount',
                'status',
            ];

            $where = [];

            $credit_note_taxes_select = array_reverse($credit_note_taxes);

            foreach ($credit_note_taxes_select as $key => $tax) {
                array_splice($select, 5, 0, '(
                    SELECT CASE
                    WHEN discount_percent != 0 AND discount_type = "before_tax" THEN ROUND(SUM((qty*rate/100*' . db_prefix() . 'item_tax.taxrate) - (qty*rate/100*' . db_prefix() . 'item_tax.taxrate * discount_percent/100)),' . get_decimal_places() . ')
                    WHEN discount_total != 0 AND discount_type = "before_tax" THEN ROUND(SUM((qty*rate/100*' . db_prefix() . 'item_tax.taxrate) - (qty*rate/100*' . db_prefix() . 'item_tax.taxrate * (discount_total/subtotal*100) / 100)),' . get_decimal_places() . ')
                    ELSE ROUND(SUM(qty*rate/100*' . db_prefix() . 'item_tax.taxrate),' . get_decimal_places() . ')
                    END
                    FROM ' . db_prefix() . 'itemable
                    INNER JOIN ' . db_prefix() . 'item_tax ON ' . db_prefix() . 'item_tax.itemid=' . db_prefix() . 'itemable.id
                    WHERE ' . db_prefix() . 'itemable.rel_type="credit_note" AND taxname="' . $tax['taxname'] . '" AND taxrate="' . $tax['taxrate'] . '" AND ' . db_prefix() . 'itemable.rel_id=' . db_prefix() . 'creditnotes.id) as total_tax_single_' . $key);
            }

            $custom_date_select = $this->get_where_report_period();

            if ($custom_date_select != '') {
                array_push($where, $custom_date_select);
            }

            $by_currency = $this->input->post('report_currency');

            if ($by_currency) {
                $currency = $this->currencies_model->get($by_currency);
                array_push($where, 'AND currency=' . $this->db->escape_str($by_currency));
            } else {
                $currency = $this->currencies_model->get_base_currency();
            }

            if ($this->input->post('credit_note_status')) {
                $statuses  = $this->input->post('credit_note_status');
                $_statuses = [];
                if (is_array($statuses)) {
                    foreach ($statuses as $status) {
                        if ($status != '') {
                            array_push($_statuses, $this->db->escape_str($status));
                        }
                    }
                }
                if (count($_statuses) > 0) {
                    array_push($where, 'AND status IN (' . implode(', ', $_statuses) . ')');
                }
            }

            $aColumns     = $select;
            $sIndexColumn = 'id';
            $sTable       = db_prefix() . 'creditnotes';
            $join         = [
                'LEFT JOIN ' . db_prefix() . 'clients ON ' . db_prefix() . 'clients.userid = ' . db_prefix() . 'creditnotes.clientid',
            ];

            $result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [
                'userid',
                'clientid',
                db_prefix() . 'creditnotes.id',
                'discount_percent',
                'deleted_customer_name',
            ]);

            $output  = $result['output'];
            $rResult = $result['rResult'];

            $footer_data = [
                'total'            => 0,
                'subtotal'         => 0,
                'total_tax'        => 0,
                'discount_total'   => 0,
                'adjustment'       => 0,
                'remaining_amount' => 0,
            ];

            foreach ($credit_note_taxes as $key => $tax) {
                $footer_data['total_tax_single_' . $key] = 0;
            }
            foreach ($rResult as $aRow) {
                $row = [];

                $row[] = '<a href="' . admin_url('credit_notes/list_credit_notes/' . $aRow['id']) . '" target="_blank">' . format_credit_note_number($aRow['id']) . '</a>';

                $row[] = _d($aRow['date']);

                if (empty($aRow['deleted_customer_name'])) {
                    $row[] = '<a href="' . admin_url('clients/client/' . $aRow['clientid']) . '">' . $aRow['company'] . '</a>';
                } else {
                    $row[] = $aRow['deleted_customer_name'];
                }

                $row[] = $aRow['reference_no'];

                $row[] = app_format_money($aRow['subtotal'], $currency->name);
                $footer_data['subtotal'] += $aRow['subtotal'];

                $row[] = app_format_money($aRow['total'], $currency->name);
                $footer_data['total'] += $aRow['total'];

                $row[] = app_format_money($aRow['total_tax'], $currency->name);
                $footer_data['total_tax'] += $aRow['total_tax'];

                $t = $totalTaxesColumns - 1;
                $i = 0;
                foreach ($credit_note_taxes as $tax) {
                    $row[] = app_format_money(($aRow['total_tax_single_' . $t] == null ? 0 : $aRow['total_tax_single_' . $t]), $currency->name);
                    $footer_data['total_tax_single_' . $i] += ($aRow['total_tax_single_' . $t] == null ? 0 : $aRow['total_tax_single_' . $t]);
                    $t--;
                    $i++;
                }

                $row[] = app_format_money($aRow['discount_total'], $currency->name);
                $footer_data['discount_total'] += $aRow['discount_total'];

                $row[] = app_format_money($aRow['adjustment'], $currency->name);
                $footer_data['adjustment'] += $aRow['adjustment'];

                $row[] = app_format_money($aRow['remaining_amount'], $currency->name);
                $footer_data['remaining_amount'] += $aRow['remaining_amount'];

                $row[] = format_credit_note_status($aRow['status']);

                $output['aaData'][] = $row;
            }

            foreach ($footer_data as $key => $total) {
                $footer_data[$key] = app_format_money($total, $currency->name);
            }

            $output['sums'] = $footer_data;
            echo json_encode($output);
            die();
        }
    }

    public function invoices_report()
    {
        if ($this->input->is_ajax_request()) {
            $invoice_taxes     = $this->distinct_taxes('invoice');
            $totalTaxesColumns = count($invoice_taxes);

            $this->load->model('currencies_model');
            $this->load->model('invoices_model');

            $select = [
                'number',
                db_prefix() . 'medicos.nome_profissional as medico',
                db_prefix() . 'convenio.name as convenio_nome',
                get_sql_select_client_company(),
                'YEAR(date) as year',
                'date',
                'duedate',
                'subtotal',
                'total',
                'total_tax',
                'discount_total',
                'adjustment',
                '(SELECT COALESCE(SUM(amount),0) FROM ' . db_prefix() . 'credits WHERE ' . db_prefix() . 'credits.invoice_id=' . db_prefix() . 'invoices.id) as credits_applied',
                '(SELECT total - (SELECT COALESCE(SUM(amount),0) FROM ' . db_prefix() . 'invoicepaymentrecords WHERE invoiceid = ' . db_prefix() . 'invoices.id) - (SELECT COALESCE(SUM(amount),0) FROM ' . db_prefix() . 'credits WHERE ' . db_prefix() . 'credits.invoice_id=' . db_prefix() . 'invoices.id))',
                 db_prefix() .'invoices.status as status',
            ];

            $where = [
                'AND tblinvoices.status != 5',
            ];

            $invoiceTaxesSelect = array_reverse($invoice_taxes);

            foreach ($invoiceTaxesSelect as $key => $tax) {
                array_splice($select, 8, 0, '(
                    SELECT CASE
                    WHEN discount_percent != 0 AND discount_type = "before_tax" THEN ROUND(SUM((qty*rate/100*' . db_prefix() . 'item_tax.taxrate) - (qty*rate/100*' . db_prefix() . 'item_tax.taxrate * discount_percent/100)),' . get_decimal_places() . ')
                    WHEN discount_total != 0 AND discount_type = "before_tax" THEN ROUND(SUM((qty*rate/100*' . db_prefix() . 'item_tax.taxrate) - (qty*rate/100*' . db_prefix() . 'item_tax.taxrate * (discount_total/subtotal*100) / 100)),' . get_decimal_places() . ')
                    ELSE ROUND(SUM(qty*rate/100*' . db_prefix() . 'item_tax.taxrate),' . get_decimal_places() . ')
                    END
                    FROM ' . db_prefix() . 'itemable
                    INNER JOIN ' . db_prefix() . 'item_tax ON ' . db_prefix() . 'item_tax.itemid=' . db_prefix() . 'itemable.id
                    WHERE ' . db_prefix() . 'itemable.rel_type="invoice" AND taxname="' . $tax['taxname'] . '" AND taxrate="' . $tax['taxrate'] . '" AND ' . db_prefix() . 'itemable.rel_id=' . db_prefix() . 'invoices.id) as total_tax_single_' . $key);
            }

            $custom_date_select = $this->get_where_report_period();
            if ($custom_date_select != '') {
                array_push($where, $custom_date_select);
            }

            if ($this->input->post('sale_agent_invoices')) {
                $agents  = $this->input->post('sale_agent_invoices');
                $_agents = [];
                if (is_array($agents)) {
                    foreach ($agents as $agent) {
                        if ($agent != '') {
                            array_push($_agents, $this->db->escape_str($agent));
                        }
                    }
                }
                if (count($_agents) > 0) {
                    array_push($where, 'AND sale_agent IN (' . implode(', ', $_agents) . ')');
                }
            }

            $by_currency              = $this->input->post('report_currency');
            $totalPaymentsColumnIndex = (12 + $totalTaxesColumns - 1);

            if ($by_currency) {
                $_temp = substr($select[$totalPaymentsColumnIndex], 0, -2);
                $_temp .= ' AND currency =' . $by_currency . ')) as amount_open';
                $select[$totalPaymentsColumnIndex] = $_temp;

                $currency = $this->currencies_model->get($by_currency);
                array_push($where, 'AND currency=' . $this->db->escape_str($by_currency));
            } else {
                $currency                          = $this->currencies_model->get_base_currency();
                $select[$totalPaymentsColumnIndex] = $select[$totalPaymentsColumnIndex] .= ' as amount_open';
            }

            if ($this->input->post('invoice_status')) {
                $statuses  = $this->input->post('invoice_status');
                $_statuses = [];
                if (is_array($statuses)) {
                    foreach ($statuses as $status) {
                        if ($status != '') {
                            array_push($_statuses, $this->db->escape_str($status));
                        }
                    }
                }
                if (count($_statuses) > 0) {
                    array_push($where, 'AND tblinvoices.status IN (' . implode(', ', $_statuses) . ')');
                }
            }

            $aColumns     = $select;
            $sIndexColumn = 'id';
            $sTable       = db_prefix() . 'invoices';
            $join         = [
                'LEFT JOIN ' . db_prefix() . 'clients ON ' . db_prefix() . 'clients.userid = ' . db_prefix() . 'invoices.clientid',
                'LEFT JOIN ' . db_prefix() . 'medicos ON ' . db_prefix() . 'medicos.medicoid = ' . db_prefix() . 'invoices.medicoid',
                'LEFT JOIN ' . db_prefix() . 'convenio ON ' . db_prefix() . 'convenio.id = ' . db_prefix() . 'invoices.convenio',
            ];

            $result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [
                'userid',
                'clientid',
                db_prefix() . 'invoices.id',
                'discount_percent',
                'deleted_customer_name',
            ]);

            $output  = $result['output'];
            $rResult = $result['rResult'];

            $footer_data = [
                'total'           => 0,
                'subtotal'        => 0,
                'total_tax'       => 0,
                'discount_total'  => 0,
                'adjustment'      => 0,
                'applied_credits' => 0,
                'amount_open'     => 0,
            ];

            foreach ($invoice_taxes as $key => $tax) {
                $footer_data['total_tax_single_' . $key] = 0;
            }

            foreach ($rResult as $aRow) {
                $row = [];

                $row[] = '<a href="' . admin_url('invoices/list_invoices/' . $aRow['id']) . '" target="_blank">' . format_invoice_number($aRow['id']) . '</a>';

               
                $row[] = $aRow['convenio_nome'];
                
                $row[] = $aRow['medico'];
                
               
                
                 $row[] = '<a href="' . admin_url('clients/client/' . $aRow['userid']) . '" target="_blank">' . $aRow['company'] . '</a>';
                 
                //  $row[] = $aRow['year'];
                
                $row[] = _d($aRow['date']);

                $row[] = _d($aRow['duedate']);

                $row[] = app_format_money($aRow['subtotal'], $currency->name);
                $footer_data['subtotal'] += $aRow['subtotal'];

                $row[] = app_format_money($aRow['total'], $currency->name);
                $footer_data['total'] += $aRow['total'];

                $row[] = app_format_money($aRow['total_tax'], $currency->name);
                $footer_data['total_tax'] += $aRow['total_tax'];

                $t = $totalTaxesColumns - 1;
                $i = 0;
                foreach ($invoice_taxes as $tax) {
                    $row[] = app_format_money(($aRow['total_tax_single_' . $t] == null ? 0 : $aRow['total_tax_single_' . $t]), $currency->name);
                    $footer_data['total_tax_single_' . $i] += ($aRow['total_tax_single_' . $t] == null ? 0 : $aRow['total_tax_single_' . $t]);
                    $t--;
                    $i++;
                }

                $row[] = app_format_money($aRow['discount_total'], $currency->name);
                $footer_data['discount_total'] += $aRow['discount_total'];

                $row[] = app_format_money($aRow['adjustment'], $currency->name);
                $footer_data['adjustment'] += $aRow['adjustment'];

                $row[] = app_format_money($aRow['credits_applied'], $currency->name);
                $footer_data['applied_credits'] += $aRow['credits_applied'];

                $amountOpen = $aRow['amount_open'];
                $row[]      = app_format_money($amountOpen, $currency->name);
                $footer_data['amount_open'] += $amountOpen;

                $row[] = format_invoice_status($aRow['status']);

                $output['aaData'][] = $row;
            }

            foreach ($footer_data as $key => $total) {
                $footer_data[$key] = app_format_money($total, $currency->name);
            }

            $output['sums'] = $footer_data;
            echo json_encode($output);
            die();
        }
    }
    
    public function fluxo_caixa_report_resumo()
    {
        
        if ($this->input->is_ajax_request()) {
            $invoice_taxes     = $this->distinct_taxes('invoice');
            $totalTaxesColumns = count($invoice_taxes);
            
            $this->load->model('currencies_model');
            $this->load->model('invoices_model');
            $empresa_id = $this->session->userdata('empresa_id');
            
            $custom_date_select = $this->get_where_report_period();
            $entradas_date_select = $this->get_where_report_period("tblinvoicepaymentrecords.date");
            $repasses_date_select = $this->get_where_report_period("data_repasse");
            $despesas_date_select = $this->get_where_report_period("date");
            $fundo_date_select = $this->get_where_report_period("data_busca");
            
            $saldo_dinheiro = 0;
            $saldo_debito = 0;
            $saldo_credito = 0;
            $saldo_outros = 0;
            $saldo_total = 0;
            
            /*
             * SALDO
             */
            
            //FUNDO
            $fundo = $this->invoices_model->get_fundo_caixa_dinheiro($fundo_date_select );  
            if(!$fundo->valor){
                $dinheiro_fundo = 0;
            }else{
                $dinheiro_fundo = $fundo->valor;
            }
            
           // $dinheiro_fundo = 0;
            $debito_fundo = 0;
            $credito_fundo = 0;
            $outros_fundo = 0;
            $total_fundo = $dinheiro_fundo + $debito_fundo + $credito_fundo + $outros_fundo;
            
            $array_fundo = array(
             'tipo' =>  'Fundo (+)',   
             'dinheiro' => $dinheiro_fundo,
             'debito' => 0,
             'credito' => 0,
             'outros' => 0,
             'total' => $total_fundo
            );
            $rResult[] = $array_fundo;
            
            
            /*
             * ENTRADAS
             */
            //DINHEIRO
            $dinheiro = $this->invoices_model->get_fluxo_caixa_dinheiro($entradas_date_select);  
            if(!$dinheiro->valor){
                $entrada_dinheiro = 0;
            }else{
                $entrada_dinheiro = $dinheiro->valor;
            }
           //DÉBITO
            $debito = $this->invoices_model->get_fluxo_caixa_debito($entradas_date_select); 
            if(!$debito->valor){
                $entrada_debito = 0;
            }else{
                $entrada_debito = $debito->valor;
            }
           //CRÉDITO
            $credito = $this->invoices_model->get_fluxo_caixa_credito($entradas_date_select); 
            if(!$credito->valor){
                $entrada_credito = 0;
            }else{
                $entrada_credito = $credito->valor;
            }
            //OUTROS
            $outros = $this->invoices_model->get_fluxo_caixa_outros($entradas_date_select); 
            if(!$outros->valor){
                $entrada_outros = 0;
            }else{
                $entrada_outros = $outros->valor;
            }
            $total_entradas = $entrada_dinheiro + $entrada_debito + $entrada_credito + $entrada_outros;
            
            $array_entradas = array(
             'tipo' =>  'Entradas (+)',   
             'dinheiro' => $entrada_dinheiro,
             'debito' => $entrada_debito,
             'credito' => $entrada_credito,
             'outros' => $entrada_outros,
             'total' => $total_entradas    
             );
            $rResult[] = $array_entradas;
            
             /*
             * REPASSES
             */
            //DINHEIRO
            $repasse_dinheiro = $this->invoices_model->get_repasse_fluxo_caixa_dinheiro($repasses_date_select);  
            if(!$repasse_dinheiro->valor){
                $r_dinheiro = 0;
            }else{
                $r_dinheiro = $repasse_dinheiro->valor;
            }
           //DÉBITO
            $repasse_debito = $this->invoices_model->get_repasse_fluxo_caixa_debito($repasses_date_select); 
            if(!$repasse_debito->valor){
                $r_debito = 0;
            }else{
                $r_debito = $repasse_debito->valor;
            }
           //CRÉDITO
            $repasse_credito = $this->invoices_model->get_repasse_fluxo_caixa_credito($repasses_date_select); 
            if(!$repasse_credito->valor){
                $r_credito = 0;
            }else{
                $r_credito = $repasse_credito->valor;
            }
            //OUTROS
            $repasse_outros = $this->invoices_model->get_repasse_fluxo_caixa_outros($repasses_date_select); 
            if(!$repasse_outros->valor){
                $r_outros = 0;
            }else{
                $r_outros = $repasse_outros->valor;
            }
            $total_repasses = $r_dinheiro + $r_debito + $r_credito + $r_outros;
            
            $array_repasses = array(
             'tipo' =>  'Repasses (-)',   
             'dinheiro' => '-'.$r_dinheiro,
             'debito' => '-'.$r_debito,
             'credito' => '-'.$r_credito,
             'outros' => '-'.$r_outros,
             'total' => '-'.$total_repasses    
             );
            $rResult[] = $array_repasses;
            
            /*
             * DESPESAS
             */
            //DINHEIRO
            $despesa_dinheiro = $this->invoices_model->get_despesa_fluxo_caixa_dinheiro($despesas_date_select);  
            if(!$despesa_dinheiro->valor){
                $d_dinheiro = 0;
            }else{
                $d_dinheiro = $despesa_dinheiro->valor;
            }
           //DÉBITO
            $despesa_debito = $this->invoices_model->get_despesa_fluxo_caixa_debito($despesas_date_select); 
            if(!$despesa_debito->valor){
                $d_debito = 0;
            }else{
                $d_debito = $despesa_debito->valor;
            }
           //CRÉDITO
            $despesa_credito = $this->invoices_model->get_despesa_fluxo_caixa_credito($despesas_date_select); 
            if(!$despesa_credito->valor){
                $d_credito = 0;
            }else{
                $d_credito = $despesa_credito->valor;
            }
            //OUTROS
            $despesa_outros = $this->invoices_model->get_despesa_fluxo_caixa_outros($despesas_date_select); 
            if(!$despesa_outros->valor){
                $d_outros = 0;
            }else{
                $d_outros = $despesa_outros->valor;
            }
            $total_despesas = $d_dinheiro + $d_debito + $d_credito + $d_outros;
            
            $array_despesas = array(
             'tipo' =>  'Despesas (-)',   
             'dinheiro' => '-'.$d_dinheiro,
             'debito' => '-'.$d_debito,
             'credito' => '-'.$d_credito,
             'outros' => '-'.$d_outros,
             'total' => '-'.$total_despesas    
             );
            $rResult[] = $array_despesas;
            
            //print_r($rResult); exit;
            
            /*
             * SALDOS
             */
            
            $saldo_dinheiro = $dinheiro_fundo + $entrada_dinheiro - $r_dinheiro     - $d_dinheiro;
            $saldo_debito   = $debito_fundo   + $entrada_debito   - $r_debito       - $d_debito;
            $saldo_credito  = $credito_fundo  + $entrada_credito  - $r_credito      - $d_credito;
            $saldo_outros   = $outros_fundo   + $entrada_outros   - $r_outros     - $d_outros;
            $saldo_total    = $total_fundo    + $total_entradas   - $total_repasses - $total_despesas;
         
            
            //$output  = $result['output'];
            $output = [
                        'draw'                 => 0,
                        'iTotalRecords'        => count($rResult),
                        'iTotalDisplayRecords' => count($rResult),
                        'aaData'               => [],
                        ];
            //$rResult = $result['rResult'];

            $footer_data = [
                'saldo_dinheiro'  => $saldo_dinheiro,
                'saldo_debito'    => $saldo_debito,
                'saldo_credito'   => $saldo_credito,
                'saldo_outros'    => $saldo_outros,
                'saldo_total'     => $saldo_total,
            ];

           
            foreach ($rResult as $aRow) {
                $row = [];

                $row[] = $aRow['tipo'];
                
                $dinheiro = app_format_money($aRow['dinheiro'], 'R$');
                if($aRow['dinheiro'] > 0){
                    $row[] = "<label class='label label-success'> $dinheiro </label>";
                }else if($aRow['dinheiro'] < 0){
                    $row[] = "<label class='label label-danger'> $dinheiro </label>";
                }else{
                    $row[] = $dinheiro;
                }
                
                
                $debito = app_format_money($aRow['debito'], 'R$');
                if($aRow['debito'] > 0){
                    $row[] = "<label class='label label-success'> $debito </label>";
                }else if($aRow['debito'] < 0){
                    $row[] = "<label class='label label-danger'> $debito </label>";
                }else{
                    $row[] = $debito;
                }
             
                $credito = app_format_money($aRow['credito'], 'R$');
                if($aRow['credito'] > 0){
                    $row[] = "<label class='label label-success'> $credito </label>";
                }else if($aRow['credito'] < 0){
                    $row[] = "<label class='label label-danger'> $credito </label>";
                }else{
                    $row[] = $credito;
                }
                
                $outros = app_format_money($aRow['outros'], 'R$');
                if($aRow['outros'] > 0){
                    $row[] = "<label class='label label-success'> $outros </label>";
                }else if($aRow['outros'] < 0){
                    $row[] = "<label class='label label-danger'> $outros </label>";
                }else{
                    $row[] = $outros;
                }
              
                $total = app_format_money($aRow['total'], 'R$');
                if($aRow['total'] > 0){
                    $row[] = "<label class='label label-success'> $total </label>";
                }else if($aRow['total'] < 0){
                    $row[] = "<label class='label label-danger'> $total </label>";
                }else{
                    $row[] = $total;
                }
                
                $t = $totalTaxesColumns - 1;
                $i = 0;
               
              
                $output['aaData'][] = $row;
            }
           

            foreach ($footer_data as $key => $total) {
                $footer_data[$key] = app_format_money($total, 'R$');
            }

            $output['sums'] = $footer_data;
            echo json_encode($output);
            die();
        }
    }
    
    public function fluxo_caixa_report_detalhado()
    {
        
        if ($this->input->is_ajax_request()) {
            $invoice_taxes     = $this->distinct_taxes('invoice');
            $totalTaxesColumns = count($invoice_taxes);
            
            $this->load->model('currencies_model');
            $this->load->model('invoices_model');
            $empresa_id = $this->session->userdata('empresa_id');
            
            $custom_date_select = $this->get_where_report_period();
            $entradas_date_select = $this->get_where_report_period("tblinvoicepaymentrecords.date");
            $repasses_date_select = $this->get_where_report_period("data_repasse");
            $fundo_date_select = $this->get_where_report_period("data_busca");
            
            $sQuery = " SELECT 'Fundo de Caixa' as tipo, c.name as descricao, cf.valor as entrada, null as saida, 'Dinheiro' as forma_pagamento FROM tblcaixa_fundo cf
                        inner join tblcaixas c on c.id = cf.caixa_id
                        where cf.empresa = $empresa_id $fundo_date_select
                        
                        union 
                        
                        SELECT 'Entradas' as tipo, concat(`tblinvoicepaymentrecords`.`invoiceid`, ' (', tblconta_financeira.nome, ')'), amount as entrada, null as saida, tblpayment_modes.name
                        FROM tblinvoicepaymentrecords
                        JOIN tblinvoices ON tblinvoices.id = tblinvoicepaymentrecords.invoiceid
                        JOIN tblconta_financeira ON tblconta_financeira.id = tblinvoicepaymentrecords.conta_id
                        LEFT JOIN tblclients ON tblclients.userid = tblinvoices.clientid
                        LEFT JOIN tblpayment_modes ON tblpayment_modes.id = tblinvoicepaymentrecords.paymentmode
                        LEFT JOIN tblconvenio ON tblconvenio.id = tblinvoices.convenio
                        WHERE tblinvoices.status != 5 $entradas_date_select
                        AND tblinvoicepaymentrecords.empresa_id = $empresa_id    
                        AND tblinvoices.deleted = 0
                        AND tblinvoicepaymentrecords.deleted = 0


                        union

                        SELECT 'Repasses' as tipo, cf.nome, null,  cr.valor as saida, tblpayment_modes.name 
                        FROM tblcaixas_repasses cr
                        inner join tblconta_financeira cf on cf.id = cr.conta_id
                        LEFT JOIN tblpayment_modes ON tblpayment_modes.id = cr.forma_id
                        where cr.empresa_id = $empresa_id $repasses_date_select

                        union

                        SELECT 'Despesas' as tipo, e.expense_name, null,  e.amount as saida, tblpayment_modes.name
                        FROM tblexpenses e
                        LEFT JOIN tblpayment_modes ON tblpayment_modes.id = e.paymentmode
                        where empresa_id = $empresa_id $custom_date_select";

            //echo $sQuery; exit;
       
         
            $CI          = & get_instance();
            $rResult = $CI->db->query($sQuery)->result_array();
           // print_r($rResult); exit;
            
            //$output  = $result['output'];
            //$rResult = $result['rResult'];

            $footer_data = [
                'saldo'           => 0,
                'total_entradas'  => 0,
                'total_saidas'    => 0,
            ];

           
            $soma_entradas = 0;
            $soma_saidas = 0;
            $saldo = 0;
            foreach ($rResult as $aRow) {
                $row = [];

                $row[] = $aRow['tipo'];
                
                if($aRow['tipo'] == 'Entradas'){
                    $fatura = 'FAT-';
                }else{
                    $fatura = '';
                }
                
                $row[] = $fatura.$aRow['descricao'];
                
                
               // $row[] = _d($aRow['date']);

               // $row[] = _d($aRow['duedate']);

                $row[] = app_format_money($aRow['entrada'], 'R$');
                $footer_data['total_entradas'] += $aRow['entrada'];
                $soma_entradas += $aRow['entrada'];
                
                $row[] = app_format_money($aRow['saida'], 'R$');
                $footer_data['total_saidas'] += $aRow['saida'];
                $soma_saidas += $aRow['saida'];
                
                $row[] = $aRow['forma_pagamento'];
                
                $t = $totalTaxesColumns - 1;
                $i = 0;
               
              
                $output['aaData'][] = $row;
            }
            $saldo =  $soma_entradas - $soma_saidas;
            $footer_data['saldo'] = $saldo;

            foreach ($footer_data as $key => $total) {
                $footer_data[$key] = app_format_money($total, 'R$');
            }

            $output['sums'] = $footer_data;
            echo json_encode($output);
            die();
        }
    }
    
    
    public function nota_fiscal_report()
    {
        if ($this->input->is_ajax_request()) {
           
            $this->load->model('currencies_model');
            $this->load->model('Nota_fiscal_model');

            $select = [
                'numero',
                db_prefix() . 'notafiscal.url as url',
                db_prefix() . 'notafiscal.invoice_id as invoice_id',
                db_prefix() . 'clients.company as company',
                db_prefix() . 'clients.userid as userid',
                db_prefix() . 'notafiscal.cnpj_prestador as cnpj_prestador',
                db_prefix() . 'conta_financeira.nome as conta',
                db_prefix() . 'notafiscal.valor as valor',
                db_prefix() . 'notafiscal.data_emissao as data_emissao',
                db_prefix() . 'notafiscal.status as status',
            ];

            $where = [
                'AND tblnotafiscal.deleted = 0',
                
            ];

            $custom_date_select = $this->get_where_report_period(db_prefix() . 'notafiscal.data_emissao');
            if ($custom_date_select != '') {
                array_push($where, $custom_date_select);
            }
            
           /*
            if ($this->input->post('sale_agent_invoices')) {
                $agents  = $this->input->post('sale_agent_invoices');
                $_agents = [];
                if (is_array($agents)) {
                    foreach ($agents as $agent) {
                        if ($agent != '') {
                            array_push($_agents, $this->db->escape_str($agent));
                        }
                    }
                }
                if (count($_agents) > 0) {
                    array_push($where, 'AND sale_agent IN (' . implode(', ', $_agents) . ')');
                }
            }

           

            if ($this->input->post('invoice_status')) {
                $statuses  = $this->input->post('invoice_status');
                $_statuses = [];
                if (is_array($statuses)) {
                    foreach ($statuses as $status) {
                        if ($status != '') {
                            array_push($_statuses, $this->db->escape_str($status));
                        }
                    }
                }
                if (count($_statuses) > 0) {
                    array_push($where, 'AND tblinvoices.status IN (' . implode(', ', $_statuses) . ')');
                }
            }
            */

            $aColumns     = $select;
            $sIndexColumn = 'id';
            $sTable       = db_prefix() . 'notafiscal';
            $join         = [
                'LEFT JOIN ' . db_prefix() . 'invoices ON ' . db_prefix() . 'invoices.id = ' . db_prefix() . 'notafiscal.invoice_id',
                'LEFT JOIN ' . db_prefix() . 'clients ON ' . db_prefix() . 'clients.userid = ' . db_prefix() . 'invoices.clientid',                
                'LEFT JOIN ' . db_prefix() . 'conta_financeira ON ' . db_prefix() . 'conta_financeira.id = ' . db_prefix() . 'notafiscal.conta_id',
            ];

            $result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [
               
            ]);

            $output  = $result['output'];
            $rResult = $result['rResult'];

            $footer_data = [
            //    'total'           => 0,
             //   'subtotal'        => 0,
               
            ];

           

            foreach ($rResult as $aRow) {
                $row = [];

             //   

                $row[] = '<a href="' . $aRow['url'] . '" target="_blank">' . $aRow['numero'] . '</a>'; 
                
                $row[] = '<a href="' . admin_url('invoices/list_invoices/' . $aRow['invoice_id']) . '" target="_blank">' . format_invoice_number($aRow['invoice_id']) . '</a>';
                
                $row[] = '<a href="' . admin_url('clients/client/' . $aRow['userid']) . '" target="_blank">' . $aRow['company'] . '</a>';
                
                $row[] = $aRow['cnpj_prestador'];
                
                $row[] = $aRow['conta']; 
                
                $row[] = app_format_money($aRow['valor'], 'R$');
                
                $row[] = _d($aRow['data_emissao']);

                $row[] = $aRow['status'];
    

                

                $output['aaData'][] = $row;
            }

            foreach ($footer_data as $key => $total) {
                $footer_data[$key] = app_format_money($total, $currency->name);
            }

            $output['sums'] = $footer_data;
            echo json_encode($output);
            die();
        }
    }
    
    public function retorno_fluxo_caixa_report()
    {
        
      $this->load->model('invoices_model');
      $dinheiro = $this->invoices_model->get_fluxo_caixa_dinheiro();  
      $data['dinheiro'] = $dinheiro->valor;
      $debito = $this->invoices_model->get_fluxo_caixa_debito(); 
     
      $data['debito'] = $debito->valor;
     // $this->load->view('admin/reports/includes/sales_fluxo_caixa', $data);   
     
    }

    public function expenses($type = 'simple_report')
    {
        $this->load->model('currencies_model');
        $data['base_currency'] = $this->currencies_model->get_base_currency();
        $data['currencies']    = $this->currencies_model->get();

        $data['title'] = _l('expenses_report');
        if ($type != 'simple_report') {
            $this->load->model('expenses_model');
            $data['categories'] = $this->expenses_model->get_category();
            $data['years']      = $this->expenses_model->get_expenses_years();

            if ($this->input->is_ajax_request()) {
                $aColumns = [
                    db_prefix().'expenses.category',
                    'amount',
                    'expense_name',
                    'tax',
                    'tax2',
                    '(SELECT taxrate FROM ' . db_prefix() . 'taxes WHERE id=' . db_prefix() . 'expenses.tax)',
                    'amount as amount_with_tax',
                    'billable',
                    'date',
                    get_sql_select_client_company(),
                    'invoiceid',
                    'reference_no',
                    'paymentmode',
                ];
                $join = [
                    'LEFT JOIN ' . db_prefix() . 'clients ON ' . db_prefix() . 'clients.userid = ' . db_prefix() . 'expenses.clientid',
                    'LEFT JOIN ' . db_prefix() . 'expenses_categories ON ' . db_prefix() . 'expenses_categories.id = ' . db_prefix() . 'expenses.category',
                ];
                $where  = [];
                $filter = [];
                include_once(APPPATH . 'views/admin/tables/includes/expenses_filter.php');
                if (count($filter) > 0) {
                    array_push($where, 'AND (' . prepare_dt_filter($filter) . ')');
                }

                $by_currency = $this->input->post('currency');
                if ($by_currency) {
                    $currency = $this->currencies_model->get($by_currency);
                    array_push($where, 'AND currency=' . $this->db->escape_str($by_currency));
                } else {
                    $currency = $this->currencies_model->get_base_currency();
                }

                $sIndexColumn = 'id';
                $sTable       = db_prefix() . 'expenses';
                $result       = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [
                    db_prefix() . 'expenses_categories.name as category_name',
                    db_prefix() . 'expenses.id',
                    db_prefix() . 'expenses.clientid',
                    'currency',
                ]);
                $output  = $result['output'];
                $rResult = $result['rResult'];
                $this->load->model('currencies_model');
                $this->load->model('payment_modes_model');

                $footer_data = [
                    'tax_1'           => 0,
                    'tax_2'           => 0,
                    'amount'          => 0,
                    'total_tax'       => 0,
                    'amount_with_tax' => 0,
                ];

                foreach ($rResult as $aRow) {
                    $row = [];
                    for ($i = 0; $i < count($aColumns); $i++) {
                        if (strpos($aColumns[$i], 'as') !== false && !isset($aRow[$aColumns[$i]])) {
                            $_data = $aRow[strafter($aColumns[$i], 'as ')];
                        } else {
                            $_data = $aRow[$aColumns[$i]];
                        }
                        if ($aRow['tax'] != 0) {
                            $_tax = get_tax_by_id($aRow['tax']);
                        }
                        if ($aRow['tax2'] != 0) {
                            $_tax2 = get_tax_by_id($aRow['tax2']);
                        }
                        if ($aColumns[$i] == 'category') {
                            $_data = '<a href="' . admin_url('expenses/list_expenses/' . $aRow['id']) . '" target="_blank">' . $aRow['category_name'] . '</a>';
                        } elseif ($aColumns[$i] == 'expense_name') {
                            $_data = '<a href="' . admin_url('expenses/list_expenses/' . $aRow['id']) . '" target="_blank">' . $aRow['expense_name'] . '</a>';
                        } elseif ($aColumns[$i] == 'amount' || $i == 6) {
                            $total = $_data;
                            if ($i != 6) {
                                $footer_data['amount'] += $total;
                            } else {
                                if ($aRow['tax'] != 0 && $i == 6) {
                                    $total += ($total / 100 * $_tax->taxrate);
                                }
                                if ($aRow['tax2'] != 0 && $i == 6) {
                                    $total += ($aRow['amount'] / 100 * $_tax2->taxrate);
                                }
                                $footer_data['amount_with_tax'] += $total;
                            }

                            $_data = app_format_money($total, $currency->name);
                        } elseif ($i == 9) {
                            $_data = '<a href="' . admin_url('clients/client/' . $aRow['clientid']) . '">' . $aRow['company'] . '</a>';
                        } elseif ($aColumns[$i] == 'paymentmode') {
                            $_data = '';
                            if ($aRow['paymentmode'] != '0' && !empty($aRow['paymentmode'])) {
                                $payment_mode = $this->payment_modes_model->get($aRow['paymentmode'], [], false, true);
                                if ($payment_mode) {
                                    $_data = $payment_mode->name;
                                }
                            }
                        } elseif ($aColumns[$i] == 'date') {
                            $_data = _d($_data);
                        } elseif ($aColumns[$i] == 'tax') {
                            if ($aRow['tax'] != 0) {
                                $_data = $_tax->name . ' - ' . app_format_number($_tax->taxrate) . '%';
                            } else {
                                $_data = '';
                            }
                        } elseif ($aColumns[$i] == 'tax2') {
                            if ($aRow['tax2'] != 0) {
                                $_data = $_tax2->name . ' - ' . app_format_number($_tax2->taxrate) . '%';
                            } else {
                                $_data = '';
                            }
                        } elseif ($i == 5) {
                            if ($aRow['tax'] != 0 || $aRow['tax2'] != 0) {
                                if ($aRow['tax'] != 0) {
                                    $total = ($total / 100 * $_tax->taxrate);
                                    $footer_data['tax_1'] += $total;
                                }
                                if ($aRow['tax2'] != 0) {
                                    $total += ($aRow['amount'] / 100 * $_tax2->taxrate);
                                    $footer_data['tax_2'] += $total;
                                }
                                $_data = app_format_money($total, $currency->name);
                                $footer_data['total_tax'] += $total;
                            } else {
                                $_data = app_format_number(0);
                            }
                        } elseif ($aColumns[$i] == 'billable') {
                            if ($aRow['billable'] == 1) {
                                $_data = _l('expenses_list_billable');
                            } else {
                                $_data = _l('expense_not_billable');
                            }
                        } elseif ($aColumns[$i] == 'invoiceid') {
                            if ($_data) {
                                $_data = '<a href="' . admin_url('invoices/list_invoices/' . $_data) . '">' . format_invoice_number($_data) . '</a>';
                            } else {
                                $_data = '';
                            }
                        }
                        $row[] = $_data;
                    }
                    $output['aaData'][] = $row;
                }

                foreach ($footer_data as $key => $total) {
                    $footer_data[$key] = app_format_money($total, $currency->name);
                }

                $output['sums'] = $footer_data;
                echo json_encode($output);
                die;
            }
            $this->load->view('admin/reports/expenses_detailed', $data);
        } else {
            if (!$this->input->get('year')) {
                $data['current_year'] = date('Y');
            } else {
                $data['current_year'] = $this->input->get('year');
            }


            $data['export_not_supported'] = ($this->agent->browser() == 'Internet Explorer' || $this->agent->browser() == 'Spartan');

            $this->load->model('expenses_model');

            $data['chart_not_billable'] = json_encode($this->reports_model->get_stats_chart_data(_l('not_billable_expenses_by_categories'), [
                'billable' => 0,
            ], [
                'backgroundColor' => 'rgba(252,45,66,0.4)',
                'borderColor'     => '#fc2d42',
            ], $data['current_year']));

            $data['chart_billable'] = json_encode($this->reports_model->get_stats_chart_data(_l('billable_expenses_by_categories'), [
                'billable' => 1,
            ], [
                'backgroundColor' => 'rgba(37,155,35,0.2)',
                'borderColor'     => '#84c529',
            ], $data['current_year']));

            $data['expense_years'] = $this->expenses_model->get_expenses_years();

            if (count($data['expense_years']) > 0) {
                // Perhaps no expenses in new year?
                if (!in_array_multidimensional($data['expense_years'], 'year', date('Y'))) {
                    array_unshift($data['expense_years'], ['year' => date('Y')]);
                }
            }

            $data['categories'] = $this->expenses_model->get_category();

            $this->load->view('admin/reports/expenses', $data);
        }
    }

    public function expenses_vs_income($year = '')
    {
        $_expenses_years = [];
        $_years          = [];
        $this->load->model('expenses_model');
        $expenses_years = $this->expenses_model->get_expenses_years();
        $payments_years = $this->reports_model->get_distinct_payments_years();

        foreach ($expenses_years as $y) {
            array_push($_years, $y['year']);
        }
        foreach ($payments_years as $y) {
            array_push($_years, $y['year']);
        }

        $_years = array_map('unserialize', array_unique(array_map('serialize', $_years)));

        if (!in_array(date('Y'), $_years)) {
            $_years[] = date('Y');
        }

        rsort($_years, SORT_NUMERIC);
        $data['report_year'] = $year == '' ? date('Y') : $year;

        $data['years']                           = $_years;
        $data['chart_expenses_vs_income_values'] = json_encode($this->reports_model->get_expenses_vs_income_report($year));
        $data['base_currency']                   = get_base_currency();
        $data['title']                           = _l('als_expenses_vs_income');
        $this->load->view('admin/reports/expenses_vs_income', $data);
    }

    /* Total income report / ajax chart*/
    public function total_income_report()
    {
        echo json_encode($this->reports_model->total_income_report());
    }

    public function report_by_payment_modes()
    {
        echo json_encode($this->reports_model->report_by_payment_modes());
    }

    public function report_by_customer_groups()
    {
        echo json_encode($this->reports_model->report_by_customer_groups());
    }

    /* Leads conversion monthly report / ajax chart*/
    public function leads_monthly_report($month)
    {
        echo json_encode($this->reports_model->leads_monthly_report($month));
    }

    private function distinct_taxes($rel_type)
    {
        return $this->db->query('SELECT DISTINCT taxname,taxrate FROM ' . db_prefix() . "item_tax WHERE rel_type='" . $rel_type . "' ORDER BY taxname ASC")->result_array();
    }
}
