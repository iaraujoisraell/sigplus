<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return array
     * Used in home dashboard page
     * Return all upcoming events this week
     */
    public function get_upcoming_events()
    {
        $monday_this_week = date('Y-m-d', strtotime('monday this week'));
        $sunday_this_week = date('Y-m-d', strtotime('sunday this week'));

        $this->db->where("(start BETWEEN '$monday_this_week' and '$sunday_this_week')");
        $this->db->where('(userid = ' . get_staff_user_id() . ' OR public = 1)');
        $this->db->order_by('start', 'desc');
        $this->db->limit(6);

        return $this->db->get(db_prefix() . 'events')->result_array();
    }

    /**
     * @param  integer (optional) Limit upcoming events
     * @return integer
     * Used in home dashboard page
     * Return total upcoming events next week
     */
    public function get_upcoming_events_next_week()
    {
        $monday_this_week = date('Y-m-d', strtotime('monday next week'));
        $sunday_this_week = date('Y-m-d', strtotime('sunday next week'));
        $this->db->where("(start BETWEEN '$monday_this_week' and '$sunday_this_week')");
        $this->db->where('(userid = ' . get_staff_user_id() . ' OR public = 1)');

        return $this->db->count_all_results(db_prefix() . 'events');
    }

    /**
     * @param  mixed
     * @return array
     * Used in home dashboard page, currency passed from javascript (undefined or integer)
     * Displays weekly payment statistics (chart)
     */
    public function get_weekly_payments_statistics($currency)
    {
        $all_payments                 = [];
        $has_permission_payments_view = has_permission('payments', '', 'view');
        $this->db->select(db_prefix() . 'invoicepaymentrecords.id, amount,' . db_prefix() . 'invoicepaymentrecords.date');
        $this->db->from(db_prefix() . 'invoicepaymentrecords');
        $this->db->join(db_prefix() . 'invoices', '' . db_prefix() . 'invoices.id = ' . db_prefix() . 'invoicepaymentrecords.invoiceid');
        $this->db->where('YEARWEEK(' . db_prefix() . 'invoicepaymentrecords.date) = YEARWEEK(CURRENT_DATE)');
        $this->db->where('' . db_prefix() . 'invoices.status !=', 5);
        if ($currency != 'undefined') {
            $this->db->where('currency', $currency);
        }

        if (!$has_permission_payments_view) {
            $this->db->where('invoiceid IN (SELECT id FROM ' . db_prefix() . 'invoices WHERE addedfrom=' . get_staff_user_id() . ')');
        }

        // Current week
        $all_payments[] = $this->db->get()->result_array();
        $this->db->select(db_prefix() . 'invoicepaymentrecords.id, amount,' . db_prefix() . 'invoicepaymentrecords.date');
        $this->db->from(db_prefix() . 'invoicepaymentrecords');
        $this->db->join(db_prefix() . 'invoices', '' . db_prefix() . 'invoices.id = ' . db_prefix() . 'invoicepaymentrecords.invoiceid');
        $this->db->where('YEARWEEK(' . db_prefix() . 'invoicepaymentrecords.date) = YEARWEEK(CURRENT_DATE - INTERVAL 7 DAY) ');

        $this->db->where('' . db_prefix() . 'invoices.status !=', 5);
        if ($currency != 'undefined') {
            $this->db->where('currency', $currency);
        }
        // Last Week
        $all_payments[] = $this->db->get()->result_array();

        $chart = [
            'labels'   => get_weekdays(),
            'datasets' => [
                [
                    'label'           => _l('this_week_payments'),
                    'backgroundColor' => 'rgba(37,155,35,0.2)',
                    'borderColor'     => '#84c529',
                    'borderWidth'     => 1,
                    'tension'         => false,
                    'data'            => [
                        0,
                        0,
                        0,
                        0,
                        0,
                        0,
                        0,
                    ],
                ],
                [
                    'label'           => _l('last_week_payments'),
                    'backgroundColor' => 'rgba(197, 61, 169, 0.5)',
                    'borderColor'     => '#c53da9',
                    'borderWidth'     => 1,
                    'tension'         => false,
                    'data'            => [
                        0,
                        0,
                        0,
                        0,
                        0,
                        0,
                        0,
                    ],
                ],
            ],
        ];


        for ($i = 0; $i < count($all_payments); $i++) {
            foreach ($all_payments[$i] as $payment) {
                $payment_day = date('l', strtotime($payment['date']));
                $x           = 0;
                foreach (get_weekdays_original() as $day) {
                    if ($payment_day == $day) {
                        $chart['datasets'][$i]['data'][$x] += $payment['amount'];
                    }
                    $x++;
                }
            }
        }

        return $chart;
    }

    public function projects_status_stats()
    {
        $this->load->model('projects_model');
        $statuses = $this->projects_model->get_project_statuses();
        $colors   = get_system_favourite_colors();

        $chart = [
            'labels'   => [],
            'datasets' => [],
        ];

        $_data                         = [];
        $_data['data']                 = [];
        $_data['backgroundColor']      = [];
        $_data['hoverBackgroundColor'] = [];
        $_data['statusLink']           = [];


        $has_permission = has_permission_intranet('projects', '', 'view');
        $sql            = '';
        foreach ($statuses as $status) {
            $sql .= ' SELECT COUNT(*) as total';
            $sql .= ' FROM ' . db_prefix() . 'projects';
            $sql .= ' WHERE status=' . $status['id'];
            if (!$has_permission) {
                $sql .= ' AND id IN (SELECT project_id FROM ' . db_prefix() . 'project_members WHERE staff_id=' . get_staff_user_id() . ')';
            }
            $sql .= ' UNION ALL ';
            $sql = trim($sql);
        }

        $result = [];
        if ($sql != '') {
            // Remove the last UNION ALL
            $sql    = substr($sql, 0, -10);
            $result = $this->db->query($sql)->result();
        }

        foreach ($statuses as $key => $status) {
            array_push($_data['statusLink'], admin_url('projects?status=' . $status['id']));
            array_push($chart['labels'], $status['name']);
            array_push($_data['backgroundColor'], $status['color']);
            array_push($_data['hoverBackgroundColor'], adjust_color_brightness($status['color'], -20));
            array_push($_data['data'], $result[$key]->total);
        }

        $chart['datasets'][]           = $_data;
        $chart['datasets'][0]['label'] = _l('home_stats_by_project_status');

        return $chart;
    }

    public function leads_status_stats()
    {
        $chart = [
            'labels'   => [],
            'datasets' => [],
        ];

        $_data                         = [];
        $_data['data']                 = [];
        $_data['backgroundColor']      = [];
        $_data['hoverBackgroundColor'] = [];
        $_data['statusLink']           = [];

        $result = get_leads_summary();

        foreach ($result as $status) {
            if ($status['color'] == '') {
                $status['color'] = '#737373';
            }
            array_push($chart['labels'], $status['name']);
            array_push($_data['backgroundColor'], $status['color']);
            if (!isset($status['junk']) && !isset($status['lost'])) {
                array_push($_data['statusLink'], admin_url('leads?status=' . $status['id']));
            }
            array_push($_data['hoverBackgroundColor'], adjust_color_brightness($status['color'], -20));
            array_push($_data['data'], $status['total']);
        }

        $chart['datasets'][] = $_data;

        return $chart;
    }

    /**
     * Display total tickets awaiting reply by department (chart)
     * @return array
     */
    public function tickets_awaiting_reply_by_department()
    {
        $this->load->model('departments_model');
        $departments = $this->departments_model->get();
        
        $colors      = get_system_favourite_colors();
        $chart       = [
            'labels'   => [],
            'datasets' => [],
        ];
        
        $_data                         = [];
        $_data['data']                 = [];
        $_data['backgroundColor']      = [];
        $_data['hoverBackgroundColor'] = [];

        $i = 0;
        foreach ($departments as $department) {
            if (!is_admin()) {
                if (get_option('staff_access_only_assigned_departments') == 1) {
                     
                    $staff_deparments_ids = $this->departments_model->get_staff_departments(get_staff_user_id(), true);
                    $departments_ids      = [];
                    if (count($staff_deparments_ids) == 0) {
                        $departments = $this->departments_model->get();
                        foreach ($departments as $department) {
                            array_push($departments_ids, $department['departmentid']);
                        }
                    } else {
                        $departments_ids = $staff_deparments_ids;
                    }
                   
                    if (count($departments_ids) > 0) {
                        $this->db->where('department IN (SELECT departmentid FROM ' . db_prefix() . 'staff_departments WHERE departmentid IN (' . implode(',', $departments_ids) . ') AND staffid="' . get_staff_user_id() . '")');
                    }
                }
            }
            $this->db->where_in('status', [
                1,
                2,
                4,
            ]);
            
            $this->db->where('department', $department['departmentid']);
            $total = $this->db->count_all_results(db_prefix() . 'tickets');

            if ($total > 0) {
                $color = '#333';
                if (isset($colors[$i])) {
                    $color = $colors[$i];
                }
                array_push($chart['labels'], $department['name']);
                array_push($_data['backgroundColor'], $color);
                array_push($_data['hoverBackgroundColor'], adjust_color_brightness($color, -20));
                array_push($_data['data'], $total);
            }
            $i++;
        }

        $chart['datasets'][] = $_data;

        return $chart;
    }

    /**
     * Display total tickets awaiting reply by status (chart)
     * @return array
     */
    public function tickets_awaiting_reply_by_status()
    {
        $this->load->model('tickets_model');
        $statuses             = $this->tickets_model->get_ticket_status();
        $_statuses_with_reply = [
            1,
            2,
            4,
        ];
         
        $chart = [
            'labels'   => [],
            'datasets' => [],
        ];

        $_data                         = [];
        $_data['data']                 = [];
        $_data['backgroundColor']      = [];
        $_data['hoverBackgroundColor'] = [];
        $_data['statusLink']           = [];

        foreach ($statuses as $status) {
            if (in_array($status['ticketstatusid'], $_statuses_with_reply)) {
                if (!is_admin()) {
                    if (get_option('staff_access_only_assigned_departments') == 1) {
                       
                        $staff_deparments_ids = $this->Departments_model->get_staff_departments(get_staff_user_id(), true);
                        
                        $departments_ids      = [];
                        if (count($staff_deparments_ids) == 0) {
                            $departments = $this->Departments_model->get();
                            foreach ($departments as $department) {
                                array_push($departments_ids, $department['departmentid']);
                            }
                        } else {
                            $departments_ids = $staff_deparments_ids;
                        }
                        if (count($departments_ids) > 0) {
                            $this->db->where('department IN (SELECT departmentid FROM ' . db_prefix() . 'staff_departments WHERE departmentid IN (' . implode(',', $departments_ids) . ') AND staffid="' . get_staff_user_id() . '")');
                        }
                    }
                }

                $this->db->where('status', $status['ticketstatusid']);
                $total = $this->db->count_all_results(db_prefix() . 'tickets');
                if ($total > 0) {
                    array_push($chart['labels'], ticket_status_translate($status['ticketstatusid']));
                    array_push($_data['statusLink'], admin_url('tickets/index/' . $status['ticketstatusid']));
                    array_push($_data['backgroundColor'], $status['statuscolor']);
                    array_push($_data['hoverBackgroundColor'], adjust_color_brightness($status['statuscolor'], -20));
                    array_push($_data['data'], $total);
                }
            }
        }

        $chart['datasets'][] = $_data;

        return $chart;
    }
    
     /*
     * DASHBOARD PRODUÇÃO
     */
    
    public function get_convenios_producao()
    {
        $this->db->order_by('nome', 'asc');
        return $this->db->get('tblconvenio_producao')->result_array();
    }
    
    public function get_medicos_producao()
    {
        $this->db->order_by('nome', 'asc');
        return $this->db->get('tblmedico_producao')->result_array();
    }
    
    public function get_medicos_producao_id($medico_id = "")
    {
        $this->db->where('' . db_prefix() . 'medico_producao.id', $medico_id);
        return $this->db->get('tblmedico_producao')->row();
    }
    
    public function get_producao_total_mes($where_convenio, $ano)
    {
        $where = "";
        if($where_convenio){
            $where .= "and convenio in ($where_convenio)";
        }
        
           if($ano){
               $where .= " AND ano = $ano";
            // $where .= " AND competencia IN ($mes_2020) ";
        }
        
        $sql = "SELECT mes, ano, sum(valor_consulta + valor_exames + valor_cirurgia + valor_mat_med + valor_uco + valor_colirios + valor_lentes + dh_pro_saude) as valor_total

                FROM tblresumo_convenio
                where id > 0 $where
                group by ano, mes ";
       // echo $sql; exit;
         $producao_total = $this->db->query($sql)->result_array();
         
        return $producao_total;
    }
    
    public function get_producao_medico_total_mes( $medico_id = "")
    {
        
        $sql = "SELECT mes, ano, sum(valor_producao)  as valor_total
                FROM tblresumo_producao_medico
                where medico = $medico_id
                group by ano, mes ";
       // echo $sql; exit;
         $producao_total = $this->db->query($sql)->result_array();
         
        return $producao_total;
    }
    
    
     public function get_competencia_producao_convenio_mes()
    {
        $sql = "SELECT mes, ano
                FROM tblresumo_convenio
                group by ano, mes ";
       // echo $sql; exit;
         $producao_total = $this->db->query($sql)->result_array();
         
        return $producao_total;
    }
    
    public function get_producao_convenio_mes($where_convenio, $ano, $mes)
    {
        $where = "";
        if($where_convenio){
            $where .= " and convenio in ($where_convenio)";
        }
        
        if($ano){
         //   $where .= " and ano = $ano";
        }
        
        if($mes){
         //   $where .= " and mes = $mes";
        }
        
        $sql = "SELECT mes, ano, cp.nome as convenio_nome, sum(valor_consulta + valor_exames + valor_cirurgia + valor_mat_med + valor_uco + valor_colirios + valor_lentes + dh_pro_saude) as valor_total
                FROM tblresumo_convenio
                inner join tblconvenio_producao cp on cp.id = tblresumo_convenio.convenio
                where tblresumo_convenio.id > 0 $where
                group by ano, mes, convenio 
                order by valor_total desc ";
        
         $producao_total = $this->db->query($sql)->result_array();
         
        return $producao_total;
    }
    
    
    public function get_resumo_producao_convenio($where_convenio = null, $mes_2020 = null)
    {
       
        $where = "";
        if($where_convenio){
            $where .= " and convenio in ($where_convenio)";
        }
        
       if($mes_2020){
            $where .= " AND competencia IN ($mes_2020) ";
        }
        
       
        
        
        
        $sql = "SELECT cp.nome as convenio_nome, color, sum(valor_consulta + valor_exames + valor_cirurgia + valor_mat_med + valor_uco + valor_colirios + valor_lentes + dh_pro_saude) as valor_total
           FROM tblresumo_convenio
           inner join tblconvenio_producao cp on cp.id = tblresumo_convenio.convenio
           where tblresumo_convenio.id > 0 $where
           group by convenio
           order by valor_total desc ";
       // echo $sql; //exit;
        $producao_total = $this->db->query($sql)->result_array();
         
        return $producao_total;
    }
    
    public function get_resumo_producao_medico_convenio($where_convenio = null, $mes_2020 = null,  $medico_id = null)
    {
       
        $where = "";
        if($where_convenio){
            $where .= " and convenio in ($where_convenio)";
        }
        
       if($mes_2020){
            $where .= " AND competencia IN ($mes_2020) ";
        }
        
        
        
        $sql = "SELECT cp.nome as convenio_nome, color, sum(valor_consulta + valor_exames + valor_cirurgia)  as valor_total
                FROM tblresumo_producao_medico
                inner join tblconvenio_producao cp on cp.id = tblresumo_producao_medico.convenio
                where medico = $medico_id $where
                group by convenio
                order by valor_total desc ";
       // echo $sql; exit;
        $producao_total = $this->db->query($sql)->result_array();
         
        return $producao_total;
    }
    
    public function get_resumo_detalhes_producao_convenio($where_convenio = null, $mes_2020 = null)
    {
       
        $where = "";
        if($where_convenio){
            $where .= " and convenio in ($where_convenio)";
        }
        
        if($mes_2020){
            $where .= " AND competencia IN ($mes_2020) ";
        }
        
        $sql = "SELECT cp.nome as convenio_nome, color, qtde_consulta, valor_consulta, qtde_exames, valor_exames, qtde_cirurgia, valor_cirurgia, valor_mat_med, valor_uco, valor_colirios, valor_lentes, dh_pro_saude,
 sum(valor_consulta + valor_exames + valor_cirurgia + valor_mat_med + valor_uco + valor_colirios + valor_lentes + dh_pro_saude) as valor_total
       FROM tblresumo_convenio
       inner join tblconvenio_producao cp on cp.id = tblresumo_convenio.convenio
       where tblresumo_convenio.id > 0 $where
       
       group by
       
       convenio
       order by valor_total desc ";
       
         $producao_total = $this->db->query($sql)->result_array();
         
        return $producao_total;
    }
    
    
    /*******************************************/
    
    public function get_resumo_producao_medico($where_medico = null, $where_convenio = null, $mes_2020 =  null)
    {
      
        $where = "";
        if($where_convenio){
            $where .= " and convenio in ($where_convenio)";
         }
        
        if($where_medico){
            $where .= " and medico in ($where_medico)";
        }
        
        if($mes_2020){
            $where .= " AND competencia IN ($mes_2020) ";
        }
        
        $sql = "SELECT m.nome as medico, sum(pm.valor_producao) as valor_total, m.color
       FROM tblresumo_producao_medico pm
       inner join  tblmedico_producao m on m.id = pm.medico
       where pm.id > 0 $where
       group by pm.medico
       order by valor_total desc ";
      // echo $sql; exit;
         $producao_total = $this->db->query($sql)->result_array();
         
        return $producao_total;
    }
    
    public function get_resumo_producao_medico_por_mes_by_id($medico_id = null)
    {
      
        $where = "";
        //if($where_convenio){
        //    $where .= " and convenio in ($where_convenio)";
        // }
        
       
        
        $sql = "SELECT mes, ano, sum(pm.valor_producao) as valor_total
                FROM tblresumo_producao_medico pm

                where pm.medico = $medico_id
                group by ano, mes
                order by ano, mes asc ";
       //echo $sql; exit;
         $producao_total = $this->db->query($sql)->result_array();
         
        return $producao_total;
    }
    
    
    
     public function get_resumo_detalhes_producao_medico($where_medico = null, $where_convenio = null, $mes_2020 = null)
    {
       
        $where = "";
        if($where_convenio){
            $where .= " and convenio in ($where_convenio)";
         }
         
         if($where_medico){
            $where .= " and medico in ($where_medico)";
        }
        
       if($mes_2020){
            $where .= " AND pm.competencia IN ($mes_2020) ";
        }
        
        $sql = "SELECT   m.id as medico_id, m.nome as medico, m.color, sum(qtde_consulta) as qtde_consulta, sum(valor_consulta) as valor_consulta,
                         sum(qtde_exames) as qtde_exames, sum(valor_exames) as valor_exames,
                         sum(qtde_cirurgia) as qtde_cirurgia, sum(valor_cirurgia) as valor_cirurgia,
                         sum(pm.valor_producao) as valor_total, sum(valor_consulta + valor_exames + valor_cirurgia) as total_repasse
       FROM tblresumo_producao_medico pm
        inner join tblmedico_producao m on m.id = pm.medico
        where pm.id > 0 $where
       
       group by pm.medico
       order by valor_total desc ";
       //echo $sql; exit;
         $producao_total = $this->db->query($sql)->result_array();
         
        return $producao_total;
    }
    
     public function get_resumo_detalhes_producao_medico_by_id($where_medico = null, $mes_2020 = null, $medico_id = null)
    {
       
        $where = "";
         if($where_medico){
            $where .= " and medico in ($where_medico)";
        }
        
       if($mes_2020){
            $where .= " AND pm.competencia IN ($mes_2020) ";
        }
        
        $sql = "SELECT  pm.ano, pm.mes, sum(qtde_consulta) as qtde_consulta, sum(valor_consulta) as valor_consulta,
                         sum(qtde_exames) as qtde_exames, sum(valor_exames) as valor_exames,
                         sum(qtde_cirurgia) as qtde_cirurgia, sum(valor_cirurgia) as valor_cirurgia,
                         sum(pm.valor_producao) as valor_total, sum(valor_consulta + valor_exames + valor_cirurgia) as total_repasse
        FROM tblresumo_producao_medico pm
        inner join tblmedico_producao m on m.id = pm.medico
        where pm.medico = $medico_id $where
       
       group by pm.ano, pm.mes  ";
     
         $producao_total = $this->db->query($sql)->result_array();
         
        return $producao_total;
    }
    
    public function get_resumo_detalhes_producao_convenio_medico_by_id($where_medico = null,  $mes_2020 = null, $medico_id = null)
    {
       
        $where = "";
         if($where_medico){
            $where .= " and medico in ($where_medico)";
        }
        
        if($mes_2020){
            $where .= " AND pm.competencia IN ($mes_2020) ";
        }
        
        $sql = "SELECT  cp.nome as convenio_nome, sum(qtde_consulta) as qtde_consulta, sum(valor_consulta) as valor_consulta,
                         sum(qtde_exames) as qtde_exames, sum(valor_exames) as valor_exames,
                         sum(qtde_cirurgia) as qtde_cirurgia, sum(valor_cirurgia) as valor_cirurgia,
                         sum(pm.valor_producao) as valor_total, sum(valor_consulta + valor_exames + valor_cirurgia) as total_repasse
        FROM tblresumo_producao_medico pm
        inner join tblconvenio_producao cp on cp.id = pm.convenio
        where pm.medico = $medico_id $where
       
       group by pm.convenio
       order by convenio_nome asc";
     
         $producao_total = $this->db->query($sql)->result_array();
         
        return $producao_total;
    }
    
    
    
    public function get_empresa_modulos()
    {
      
        $empresa_id = $this->session->userdata('empresa_id');
        
        $sql = "SELECT *
                FROM tblempresas_modulos
                where empresa_id = $empresa_id ";
       //echo $sql; exit;
         $producao_total = $this->db->query($sql)->result_array();
         
        return $producao_total;
    }
    
}
