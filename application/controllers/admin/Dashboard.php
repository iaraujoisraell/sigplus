<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('dashboard_model');
        $this->load->model('Medicos_model');
        $this->load->model('Convenios_model');
        $this->load->model('Invoice_items_model');
        $this->load->model('Invoices_model');
        $this->load->model('Misc_model'); 
        $this->load->model('Financeiro_model'); 
        // 
    }

    /* This is admin dashboard view */
    public function index()
    {
        
        close_setup_menu();
         
       $this->load->model('Departments_model');
        $this->load->model('todo_model');
        $data['departments'] = $this->Departments_model->get();
        
       
      //  $hoje = date('Y-m-d');
     //   $this->db->where('staffid', get_staff_user_id());
      //  $this->db->update(db_prefix() . 'staff', ['data_agenda_atual' => "$hoje", 'medicoid_atual' => ""]);

        $data['todos'] = $this->todo_model->get_todo_items(0);
        // Only show last 5 finished todo items
        $this->todo_model->setTodosLimit(5);
        $data['todos_finished']            = $this->todo_model->get_todo_items(1);
        $data['upcoming_events_next_week'] = $this->dashboard_model->get_upcoming_events_next_week();
        $data['upcoming_events']           = $this->dashboard_model->get_upcoming_events();
        $data['title']                     = _l('dashboard_string');

        $this->load->model('contracts_model');
        $data['expiringContracts'] = $this->contracts_model->get_contracts_about_to_expire(get_staff_user_id());

        $this->load->model('currencies_model');
        $data['currencies']    = $this->currencies_model->get();
        $data['base_currency'] = $this->currencies_model->get_base_currency();
        $data['activity_log']  = $this->misc_model->get_activity_log();
        
        // Tickets charts
        $tickets_awaiting_reply_by_status     = ''; //$this->dashboard_model->tickets_awaiting_reply_by_status();
        $tickets_awaiting_reply_by_department = '';// $this->dashboard_model->tickets_awaiting_reply_by_department();
        
        $data['tickets_reply_by_status']              = json_encode($tickets_awaiting_reply_by_status);
       
        $data['tickets_awaiting_reply_by_department'] = json_encode($tickets_awaiting_reply_by_department);

        $data['tickets_reply_by_status_no_json']              = $tickets_awaiting_reply_by_status;
        $data['tickets_awaiting_reply_by_department_no_json'] = ''; // $tickets_awaiting_reply_by_department;
      
        $data['projects_status_stats'] = '';// json_encode($this->dashboard_model->projects_status_stats());
        
        $data['leads_status_stats']    = '';// json_encode($this->dashboard_model->leads_status_stats());
        $data['google_ids_calendars']  = '';// $this->misc_model->get_google_calendar_ids();
        $data['bodyclass']             = 'dashboard invoices-total-manual';
        $this->load->model('announcements_model');
        $data['staff_announcements']             = $this->announcements_model->get();
        $data['total_undismissed_announcements'] = $this->announcements_model->get_total_undismissed_announcements();
        
        $this->load->model('projects_model');
        $data['projects_activity'] = $this->projects_model->get_activity('', hooks()->apply_filters('projects_activity_dashboard_limit', 20));
        add_calendar_assets();
        $this->load->model('utilities_model');
        $this->load->model('estimates_model');
        $data['estimate_statuses'] = $this->estimates_model->get_statuses();
        
        $this->load->model('proposals_model');
        $data['proposal_statuses'] = $this->proposals_model->get_statuses();

        $wps_currency = 'undefined';
        if (is_using_multiple_currencies()) {
            $wps_currency = $data['base_currency']->id;
        }
        $data['weekly_payment_stats'] = json_encode($this->dashboard_model->get_weekly_payments_statistics($wps_currency));

        $data['dashboard'] = true;
        
        $data['user_dashboard_visibility'] = get_staff_meta(get_staff_user_id(), 'dashboard_widgets_visibility');

        if (!$data['user_dashboard_visibility']) {
            $data['user_dashboard_visibility'] = [];
        } else {
            $data['user_dashboard_visibility'] = unserialize($data['user_dashboard_visibility']);
        }
        $data['user_dashboard_visibility'] = json_encode($data['user_dashboard_visibility']);
          
        $member = $this->staff_model->get(get_staff_user_id());
        $medico_id = $member->medico_id;
        $data['medico_id'] = $medico_id;
        
        $data = hooks()->apply_filters('before_dashboard_render', $data);
        $this->load->view('admin/dashboard/dashboard', $data);
    }

    public function atualiza_ci(){
        
      $sql = "SELECT ci.id, ci.user_create, s.staffid, s.user, ci.matricula FROM tbl_intranet_ci ci
             inner join tblstaff s on s.user = ci.matricula
             where s.empresa_Id = 4 and ci.user_create = 0";

     //echo $sql; exit;
     $result = $this->db->query($sql)->result_array();
     
     foreach($result as $res){
      $id = $res['id'];
      $staffid = $res['staffid'];
      
      $data['user_create'] = $staffid;
      $this->db->where('id', $id);
      $this->db->update('tbl_intranet_ci', $data);

     }
    
   }

   public function atualiza_ci_send(){
        
      $sql = "SELECT ci.id, ci.staff_id, s.staffid, s.user, ci.matricula FROM tbl_intranet_ci_send ci
             inner join tblstaff s on s.user = ci.matricula
             where s.empresa_Id = 4 and ci.staff_id = 0";

     //echo $sql; exit;
     $result = $this->db->query($sql)->result_array();
     
     foreach($result as $res){
      $id = $res['id'];
      $staffid = $res['staffid'];
     
      $data['staff_id'] = $staffid;
      $this->db->where('id', $id);
      $this->db->update('tbl_intranet_ci_send', $data);

     }
    
   }

    public function menu()
    {
        
        close_setup_menu();
         
        $this->load->model('Departments_model');
        $this->load->model('todo_model');
        
        $data['departments'] = $this->Departments_model->get(); 
        $data['todos'] = $this->todo_model->get_todo_items(0);
        
        // Only show last 5 finished todo items
        $this->todo_model->setTodosLimit(5);
        $data['todos_finished']            = $this->todo_model->get_todo_items(1);
        $data['upcoming_events_next_week'] = $this->dashboard_model->get_upcoming_events_next_week();
        $data['upcoming_events']           = $this->dashboard_model->get_upcoming_events();
        $data['title']                     = _l('dashboard_string');
       
        $this->load->model('contracts_model');
        $data['expiringContracts'] = $this->contracts_model->get_contracts_about_to_expire(get_staff_user_id());
        
        $this->load->model('currencies_model');
        $data['currencies']    = $this->currencies_model->get();
        $data['base_currency'] = $this->currencies_model->get_base_currency();
        $data['activity_log']  = $this->misc_model->get_activity_log();

        
      
        $this->load->model('projects_model');
        $data['projects_activity'] = $this->projects_model->get_activity('', hooks()->apply_filters('projects_activity_dashboard_limit', 20));
        add_calendar_assets();
        $this->load->model('utilities_model');
        $this->load->model('estimates_model');
        $data['estimate_statuses'] = $this->estimates_model->get_statuses();

        $this->load->model('proposals_model');
        $data['proposal_statuses'] = $this->proposals_model->get_statuses();
       
        $wps_currency = 'undefined';
        if (is_using_multiple_currencies()) {
            $wps_currency = $data['base_currency']->id;
        }
        $data['weekly_payment_stats'] = json_encode($this->dashboard_model->get_weekly_payments_statistics($wps_currency));

        $data['dashboard'] = true;

        $data['user_dashboard_visibility'] = get_staff_meta(get_staff_user_id(), 'dashboard_widgets_visibility');

        if (!$data['user_dashboard_visibility']) {
            $data['user_dashboard_visibility'] = [];
        } else {
            $data['user_dashboard_visibility'] = unserialize($data['user_dashboard_visibility']);
        }
        $data['user_dashboard_visibility'] = json_encode($data['user_dashboard_visibility']);

        $data = hooks()->apply_filters('before_dashboard_render', $data);
        
        $member             = $this->staff_model->get(get_staff_user_id());
        $medico_id          = $member->medico_id;
        $data['medico_id']  = $medico_id;
        $intranet           = $member->intranet;
        
        $modulos = $this->dashboard_model->get_empresa_modulos();
        
        $data['modulos'] = $modulos;
        
     
            
            if(is_admin()){
                $this->load->view('admin/dashboard/menu', $data);
            }else if($medico_id){
                $this->load->view('admin/dashboard/menu_medico', $data);
            }else{
              $this->load->view('admin/dashboard/dashboard', $data);    
            }
        
       
        
    }
    
    //MENU FINANCEIRO
    public function menu_financeiro()
    {
      
        $data['invoices_years'] = $this->Financeiro_model->get_entradas_years();
            
            
            if (count($data['invoices_years']) > 0) {
                // Perhaps no expenses in new year?
                if (!in_array_multidimensional($data['invoices_years'], 'year', date('Y'))) {
                    array_unshift($data['invoices_years'], ['year' => date('Y')]);
                }
            }
        $this->load->view('admin/dashboard/menu_financeiro', $data);
    }


    public function home()
    {
      
        
      
        $data['user_dashboard_visibility'] = json_encode($data['user_dashboard_visibility']);

        $member = $this->staff_model->get(get_staff_user_id());
        $medico_id = $member->medico_id;
        $data['medico_id'] = $medico_id;
        
        $data = hooks()->apply_filters('before_dashboard_render', $data);
        $this->load->view('admin/dashboard/home', $data);
    }
    
    /***************************************************************************
     ************************ DASHBOARD TESOURARIA *****************************
     **************************************************************************/
    
    public function dashboard_gestao()
    {
        
        close_setup_menu();
        
        $data['medicos']   = $this->Medicos_model->get_conta_financeira();
        $data['convenios']   = $this->Convenios_model->get();
        $data['categorias']            = $this->Invoice_items_model->get_groups();
        $data['procedimentos']         = $this->Invoice_items_model->get_ativos();
       //echo 'aqui'; exit;
        //$data = hooks()->apply_filters('before_dashboard_render', $data);
        $this->load->view('admin/dashboard/dashboard_gestao', $data);
    }
    
    public function retorno_dashboard_gestao()
    {
        $medicos_faturamento = $this->input->post("medicos_faturamento");
        $convenios_faturamento = $this->input->post("convenios_faturamento");
        $categorias_faturamento = $this->input->post("categorias_faturamento");
        $procedimento_faturamento = $this->input->post("procedimento_faturamento");
        $data_de = $this->input->post("data_de");
        $data_ate = $this->input->post("data_ate");
        
       
        /*
         * MEDICOS
         */
        $where_medico = "";
        $total = count($medicos_faturamento);
        $cont = 1;
        foreach ($medicos_faturamento as $medico){
            if($cont == $total){
                $where_medico .= "$medico";
            }else{
                $where_medico .= "$medico,";
            }
         $cont++;   
        }
       
        $medicos = $this->Medicos_model->get_where_in($where_medico);
       
        /*
         * CONVENIOS
         */
        $where_convenio = "";
        $total_convenio = count($convenios_faturamento);
        $cont_conv = 1;
        foreach ($convenios_faturamento as $convenio){
            if($cont_conv == $total_convenio){
                $where_convenio .= "$convenio";
            }else{
                $where_convenio .= "$convenio,";
            }
         $cont_conv++;   
        }
       
        /*
         * CATEGORIAS
         */
        $where_categoria = "";
        $total_categoria = count($categorias_faturamento);
        $cont_cat = 1;
        foreach ($categorias_faturamento as $categoria){
             if($cont_cat == $total_categoria){
                $where_categoria .= "$categoria";
            }else{
                $where_categoria .= "$categoria,";
            }
         $cont_cat++;   
        }
         
       
        $data['medicos']   = $medicos;
        $data['convenios_faturamento']   = $where_convenio;
        $data['categorias_faturamento']   = $where_categoria;
        $data['procedimento_faturamento']   = $procedimento_faturamento;
        $data['medicos_faturamento']   = $where_medico;
        $data['data_de']   = $data_de;
        $data['data_ate']   = $data_ate;
        
        
        /*
         * GRÁFICO ENTRADAS X SAÍDAS
         */
        $_expenses_years = [];
        $_years          = [];
        $this->load->model('reports_model');
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
        $fatura_despesa                          = $this->reports_model->get_dashboard_expenses_vs_income_report($year);
        $data['chart_expenses_vs_income_values'] = $fatura_despesa;
        $data['base_currency']                   = get_base_currency();
        /*************************************************************************/
        
        /*
         * RECEITAS FATURAMENTO POR CONTA / MÉDICO
         */
        $recebimentos_contas = $this->reports_model->get_receita_conta_medica($where_medico, $where_convenio, $data_de, $data_ate);
        $data['recebimentos_contas']                           = $recebimentos_contas;
        //echo 'aqui1'; exit;
        /*
         * VALOR DE RECEBIMENTO NA TESOURARIA POR DIA
         */
        $recebimentos_tesouraria = $this->reports_model->get_recebimentos_tesouraria($where_medico, $where_convenio, $data_de, $data_ate);
        $data['recebimentos_tesouraria']        = $recebimentos_tesouraria;
        
        /*
         * VALOR DE RECEBIMENTO NA TESOURARIA POR FORMA DE PAGAMENTO
         */
        $recebimentos_formas_pagamento = $this->reports_model->get_recebimentos_tesouraria_forma_pagamento($where_medico, $where_convenio, $data_de, $data_ate);
        $data['recebimentos_formas_pagamento'] = $recebimentos_formas_pagamento;
        
       
        /*
         * PRODUÇÃO FATURADA POR CATEGORIA / MÉDICO
         */
        $producao_categoria = $this->reports_model->get_categorias_procedimentos($where_medico, $where_convenio, $where_categoria, $procedimento_faturamento,  $data_de, $data_ate);
        $data['producao_categoria'] = $producao_categoria;
        
        /*
         * PRODUÇÃO FATURADA POR CATEGORIA / MÉDICO
         */
        $producao_procedimentos = $this->reports_model->get_all_procedimentos($where_medico, $where_convenio, $where_categoria, $procedimento_faturamento,  $data_de, $data_ate);
        $data['producao_procedimentos'] = $producao_procedimentos;
        
        
        /*
         * RECEBIMENTO VISION
         */
       // $recebimentos_vision = $this->reports_model->get_recebimentos_tesouraria_forma_pagamento(1, $where_convenio, $data_de, $data_ate);
       // $data['recebimentos_vision'] = $recebimentos_vision;
        
        $this->load->view('admin/dashboard/retorno_dashboard_gestao', $data);
       
    }
    
    /**************************************************************************/
    
    
    
    /***************************************************************************
     ************************ DASHBOARD TESOURARIA *****************************
     **************************************************************************/
    
    public function dashboard_agendamento()
    {
        close_setup_menu();
        
        $data['medicos']   = $this->Medicos_model->get_conta_financeira();
        $data['convenios']   = $this->Convenios_model->get();
        $data['categorias']            = $this->Invoice_items_model->get_groups();
        $data['procedimentos']         = $this->Invoice_items_model->get_ativos();
        $data['title']                 = 'Indicadores Atendimento';
        //$data = hooks()->apply_filters('before_dashboard_render', $data);
        $this->load->view('admin/dashboard/indicadores/dashboard_agendamento', $data);
    }
    
    public function retorno_dashboard_agendamento()
    {
       // $medicos_faturamento = $this->input->post("medicos_faturamento");
       // $convenios_faturamento = $this->input->post("convenios_faturamento");
       // $categorias_faturamento = $this->input->post("categorias_faturamento");
       // $procedimento_faturamento = $this->input->post("procedimento_faturamento");
        $data_de = $this->input->post("data_de");
        $data_ate = $this->input->post("data_ate");
        
      // echo $data_de.'<Br>';
       //echo $data_ate.'<Br>';
       
       //exit;
        /*
         * MEDICOS
        
        $where_medico = "";
        $total = count($medicos_faturamento);
        $cont = 1;
        foreach ($medicos_faturamento as $medico){
            if($cont == $total){
                $where_medico .= "$medico";
            }else{
                $where_medico .= "$medico,";
            }
         $cont++;   
        }
       
        $medicos = $this->Medicos_model->get_where_in($where_medico);
       
        /*
         * CONVENIOS
         
        $where_convenio = "";
        $total_convenio = count($convenios_faturamento);
        $cont_conv = 1;
        foreach ($convenios_faturamento as $convenio){
            if($cont_conv == $total_convenio){
                $where_convenio .= "$convenio";
            }else{
                $where_convenio .= "$convenio,";
            }
         $cont_conv++;   
        }
       
        /*
         * CATEGORIAS
         
        $where_categoria = "";
        $total_categoria = count($categorias_faturamento);
        $cont_cat = 1;
        foreach ($categorias_faturamento as $categoria){
             if($cont_cat == $total_categoria){
                $where_categoria .= "$categoria";
            }else{
                $where_categoria .= "$categoria,";
            }
         $cont_cat++;   
        }
         */
       
      // $data['medicos']   = $medicos;
      //  $data['convenios_faturamento']   = $where_convenio;
      //  $data['categorias_faturamento']   = $where_categoria;
      //  $data['procedimento_faturamento']   = $procedimento_faturamento;
      //  $data['medicos_faturamento']   = $where_medico;
        $data['data_de']   = $data_de;
        $data['data_ate']   = $data_ate;
        
        /**********************************************************************/
        
        $this->load->model('Indicadores_model');
        
        /*
         * 1- TOTAL DE ATENDIMENTOS
         */
        $total_atendidos = $this->Indicadores_model->get_total_atendidos($data_de, $data_ate);
        $data['total_atendidos'] = $total_atendidos;
        
        /*
         * 2- TOTAL EM ATENDIMENTOS
         */
        $total_atendimento = $this->Indicadores_model->get_total_em_atendimentos($data_de, $data_ate);
        $data['total_atendimento'] = $total_atendimento;
        
        /*
         * 3- TOTAL DE FALTAS
         */
        $total_falta = $this->Indicadores_model->get_total_falta($data_de, $data_ate);
        $data['total_falta'] = $total_falta;
        
        
        /*
         * 4- TOTAL DE AGENDADOS
         */
        $total_agendados = $this->Indicadores_model->get_total_agendado($data_de, $data_ate);
        $data['total_agendados'] = $total_agendados;
        
        /*
         * 5- TOTAL DE CONFIRMADO
         */
        $total_confirmado = $this->Indicadores_model->get_total_confirmado($data_de, $data_ate);
        $data['total_confirmado'] = $total_confirmado;
        
        /*
         * 6- TOTAL DE CANCELADOS
         */
        $total_cancelado = $this->Indicadores_model->get_total_cancelado($data_de, $data_ate);
        $data['total_cancelado'] = $total_cancelado;
        
        
        // GRÁFICO PIZZA
        
        /*
         * 4- TOTAL POR TITPO DE ATENDIMENTO
         */
        $total_tipo_atendimento = $this->Indicadores_model->get_atendimento_tipo_atendimento($data_de, $data_ate);
        $data['total_tipo_atendimento'] = $total_tipo_atendimento;
        
        
        /*
         * 5- TOTAL POR TITPO DE SEXO
         */
        $total_tipo_sexo = $this->Indicadores_model->get_atendimento_tipo_sexo($data_de, $data_ate);
        $data['total_tipo_sexo'] = $total_tipo_sexo;
        
        // FIM GRÁFICO PIZZA
        
        
        // GRÁFICO BARRA
        
        // 6-TOTAL POR DATA
        $total_atendimento_data = $this->Indicadores_model->get_atendimento_por_dia($data_de, $data_ate);
        $data['total_atendimento_data'] = $total_atendimento_data;
        
        // 7-TOTAL POR CONVÊNIO
        $total_atendimento_convenio = $this->Indicadores_model->get_atendimento_por_convenio($data_de, $data_ate);
        $data['total_atendimento_convenio'] = $total_atendimento_convenio;
        
        // 8-TOTAL POR HORÁRIO
        $total_atendimento_horario = $this->Indicadores_model->get_atendimento_por_horario($data_de, $data_ate);
        $data['total_atendimento_horario'] = $total_atendimento_horario;
        
       
        // 9-TOTAL POR MÉDICO
        $total_atendimento_medico = $this->Indicadores_model->get_atendimento_por_medico($data_de, $data_ate);
        $data['total_atendimento_medico'] = $total_atendimento_medico;
       
         // 10-TOTAL POR ATENDENTE
        $total_agendamento_atendente = $this->Indicadores_model->get_agendamento_atendente($data_de, $data_ate);
        $data['total_agendamento_atendente'] = $total_agendamento_atendente;
         
        // FIM GRÁFICO BARRA
       
        
        
        
        $this->load->view('admin/dashboard/indicadores/retorno_dashboard_agendamento', $data);
       
    }
    
    /**************************************************************************/

    
    /***************************************************************************
     ************************ DASHBOARD TESOURARIA *****************************
     **************************************************************************/
    
    public function dashboard_emprestimo()
    {
        close_setup_menu();
        
        $data['title']                 = 'Indicadores Empréstimos';
        
        //$data = hooks()->apply_filters('before_dashboard_render', $data);
        $this->load->view('admin/dashboard/indicadores/emprestimos/dashboard_emprestimos', $data);
    }
    
    public function retorno_dashboard_emprestimo()
    {
        $data_de = $this->input->post("data_de");
        $data_ate = $this->input->post("data_ate");
        
       
        $data['data_de']   = $data_de;
        $data['data_ate']   = $data_ate;
        
        /**********************************************************************/
      
        $this->load->model('Indicadores_model');
        
        /*
         * 1- TOTAL DE ATENDIMENTOS
         */
        $total_emprestimo = $this->Indicadores_model->get_total_emprestimos();
        $data['total_emprestimo'] = $total_emprestimo;
        
        /*
         * 2- TOTAL EM ATENDIMENTOS
         */
        $total_parcelas_pagas = $this->Indicadores_model->get_total_emprestimos_pagos();
        $data['total_parcelas_pagas'] = $total_parcelas_pagas;
        
        /*
         * 3- TOTAL DE FALTAS
         */
        $total_parcelas_abertas = $this->Indicadores_model->get_total_emprestimos_abertos();
        $data['total_parcelas_abertas'] = $total_parcelas_abertas;
        
        // GRÁFICO PIZZA
        
        /*
         * 4- TOTAL POR TITPO DE ATENDIMENTO
         */
        //$total_tipo_atendimento = $this->Indicadores_model->get_atendimento_tipo_atendimento($data_de, $data_ate);
        //$data['total_tipo_atendimento'] = $total_tipo_atendimento;
        
        
        /*
         * 5- TOTAL POR TITPO DE SEXO
         */
        //$total_tipo_sexo = $this->Indicadores_model->get_atendimento_tipo_sexo($data_de, $data_ate);
        //$data['total_tipo_sexo'] = $total_tipo_sexo;
        
        // FIM GRÁFICO PIZZA
        
        
        // GRÁFICO BARRA
        
        // 6-TOTAL POR DATA
        $total_emprestimo_data = $this->Indicadores_model->get_vencimento_emprestimo_por_dia($data_de, $data_ate);
        $data['total_emprestimo_data'] = $total_emprestimo_data;
        
        $total_emprestimo_mes = $this->Indicadores_model->get_total_emprestimo_por_mes($data_de, $data_ate);
        $data['total_emprestimo_mes'] = $total_emprestimo_mes;
        
        // 7-TOTAL POR CONVÊNIO
        //$total_atendimento_convenio = $this->Indicadores_model->get_atendimento_por_convenio($data_de, $data_ate);
        //$data['total_atendimento_convenio'] = $total_atendimento_convenio;
        
        // 8-TOTAL POR HORÁRIO
        //$total_atendimento_horario = $this->Indicadores_model->get_atendimento_por_horario($data_de, $data_ate);
        //$data['total_atendimento_horario'] = $total_atendimento_horario;
        
       
        // 9-TOTAL VALOR LIBERADO POR EMPRÉSTIMO   - OK
        $total_liberado_emprestimo = $this->Indicadores_model->get_emprestimo_por_valor($data_de, $data_ate);
        $data['total_liberado_emprestimo'] = $total_liberado_emprestimo;
       
         // 10-TOTAL POR ATENDENTE
        //$total_agendamento_atendente = $this->Indicadores_model->get_agendamento_atendente($data_de, $data_ate);
        //$data['total_agendamento_atendente'] = $total_agendamento_atendente;
         
        // FIM GRÁFICO BARRA
       
        
        
        
        $this->load->view('admin/dashboard/indicadores/emprestimos/retorno_dashboard_emprestimos', $data);
       
    }
    
        /***************************************************************************
     ************************ DASHBOARD TESOURARIA *****************************
     **************************************************************************/
    
    public function dashboard_gestao_producao()
    {
        close_setup_menu();
        
        $data['medicos']   = $this->Medicos_model->get_conta_financeira();
        $data['convenios']   = $this->Convenios_model->get();
        $data['categorias']            = $this->Invoice_items_model->get_groups();
        $data['procedimentos']         = $this->Invoice_items_model->get_ativos();
        
        //$data = hooks()->apply_filters('before_dashboard_render', $data);
        $this->load->view('admin/dashboard/indicadores/producao/dashboard_producao', $data);
    }
    
    public function retorno_dashboard_gestao_producao()
    {
        $medicos_faturamento = $this->input->post("medicos_faturamento");
        $convenios_faturamento = $this->input->post("convenios_faturamento");
        $categorias_faturamento = $this->input->post("categorias_faturamento");
        $procedimento_faturamento = $this->input->post("procedimento_faturamento");
        $data_de = $this->input->post("data_de");
        $data_ate = $this->input->post("data_ate");
        
       
        /*
         * MEDICOS
         */
        $where_medico = "";
        $total = count($medicos_faturamento);
        $cont = 1;
        foreach ($medicos_faturamento as $medico){
            if($cont == $total){
                $where_medico .= "$medico";
            }else{
                $where_medico .= "$medico,";
            }
         $cont++;   
        }
       
        $medicos = $this->Medicos_model->get_where_in($where_medico);
       
        /*
         * CONVENIOS
         */
        $where_convenio = "";
        $total_convenio = count($convenios_faturamento);
        $cont_conv = 1;
        foreach ($convenios_faturamento as $convenio){
            if($cont_conv == $total_convenio){
                $where_convenio .= "$convenio";
            }else{
                $where_convenio .= "$convenio,";
            }
         $cont_conv++;   
        }
       
        /*
         * CATEGORIAS
         */
        $where_categoria = "";
        $total_categoria = count($categorias_faturamento);
        $cont_cat = 1;
        foreach ($categorias_faturamento as $categoria){
             if($cont_cat == $total_categoria){
                $where_categoria .= "$categoria";
            }else{
                $where_categoria .= "$categoria,";
            }
         $cont_cat++;   
        }
         
       
        $data['medicos']   = $medicos;
        $data['convenios_faturamento']   = $where_convenio;
        $data['categorias_faturamento']   = $where_categoria;
        $data['procedimento_faturamento']   = $procedimento_faturamento;
        $data['medicos_faturamento']   = $where_medico;
        $data['data_de']   = $data_de;
        $data['data_ate']   = $data_ate;
        
        
        /*
         * GRÁFICO ENTRADAS X SAÍDAS
         */
        $_expenses_years = [];
        $_years          = [];
        $this->load->model('reports_model');
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
        $fatura_despesa                          = $this->reports_model->get_dashboard_expenses_vs_income_report($year);
        $data['chart_expenses_vs_income_values'] = $fatura_despesa;
        $data['base_currency']                   = get_base_currency();
        /*************************************************************************/
        
        /*
         * RECEITAS FATURAMENTO POR CONTA / MÉDICO
         */
        $recebimentos_contas = $this->reports_model->get_receita_conta_medica($where_medico, $where_convenio, $data_de, $data_ate);
        $data['recebimentos_contas']                           = $recebimentos_contas;
        
        /*
         * VALOR DE RECEBIMENTO NA TESOURARIA POR DIA
         */
        $recebimentos_tesouraria = $this->reports_model->get_recebimentos_tesouraria($where_medico, $where_convenio, $data_de, $data_ate);
        $data['recebimentos_tesouraria']        = $recebimentos_tesouraria;
        
        /*
         * VALOR DE RECEBIMENTO NA TESOURARIA POR FORMA DE PAGAMENTO
         */
        $recebimentos_formas_pagamento = $this->reports_model->get_recebimentos_tesouraria_forma_pagamento($where_medico, $where_convenio, $data_de, $data_ate);
        $data['recebimentos_formas_pagamento'] = $recebimentos_formas_pagamento;
        
       
        /*
         * PRODUÇÃO FATURADA POR CATEGORIA / MÉDICO
         */
        $producao_categoria = $this->reports_model->get_categorias_procedimentos($where_medico, $where_convenio, $where_categoria, $procedimento_faturamento,  $data_de, $data_ate);
        $data['producao_categoria'] = $producao_categoria;
        
        /*
         * PRODUÇÃO FATURADA POR CATEGORIA / MÉDICO
         */
        $producao_procedimentos = $this->reports_model->get_all_procedimentos($where_medico, $where_convenio, $where_categoria, $procedimento_faturamento,  $data_de, $data_ate);
        $data['producao_procedimentos'] = $producao_procedimentos;
        
        
        /*
         * RECEBIMENTO VISION
         */
       // $recebimentos_vision = $this->reports_model->get_recebimentos_tesouraria_forma_pagamento(1, $where_convenio, $data_de, $data_ate);
       // $data['recebimentos_vision'] = $recebimentos_vision;
        
        $this->load->view('admin/dashboard/indicadores/producao/retorno_dashboard_producao', $data);
       
    }
    
    /**************************************************************************/

    /*
     * DASHBOARD PRODUÇÃO VISION / EXCEL
     */    
    
    public function dashboard_producao()
    {
        close_setup_menu();
        
        $data['medicos']   = $this->dashboard_model->get_medicos_producao();
        $data['convenios']  = $this->dashboard_model->get_convenios_producao();
       
       // $data['categorias']            = $this->Invoice_items_model->get_groups();
       // $data['procedimentos']         = $this->Invoice_items_model->get_ativos();
        
        //$data = hooks()->apply_filters('before_dashboard_render', $data);
        $this->load->view('admin/dashboard/dashboard_producao_convenio', $data);
    }
    
    public function retorno_dashboard_producao()
    {
         $meses_2020 = array();
        
        $medicos_faturamento = $this->input->post("medicos_faturamento");
        $convenios_faturamento = $this->input->post("convenios_faturamento");
        $competencia1 = $this->input->post("competencia1"); // 6/2020
        $competencia2 = $this->input->post("competencia2"); // 7/2020
        $competencia3 = $this->input->post("competencia3"); // 8/2020
        $competencia4 = $this->input->post("competencia4"); // 9/2020
        $competencia5 = $this->input->post("competencia5"); // 10/2020
        $competencia6 = $this->input->post("competencia6"); // 11/2020
        $competencia7 = $this->input->post("competencia7"); // 12/2020
        
        if($competencia1){
           $competencia1 = '202006';
            $meses_2020[] = "$competencia1"; 
        }
        if($competencia2){
           $competencia2 = '202007';
           $meses_2020[] = "$competencia2"; 
        }
        if($competencia3){
           $competencia3 = '202008';
           $meses_2020[] = "$competencia3"; 
        }
        if($competencia4){
           $competencia4 = '202009';
           $meses_2020[] = "$competencia4"; 
        }
        if($competencia5){
           $competencia5 = '202010';
           $meses_2020[] = "$competencia5"; 
        }
        if($competencia6){
           $competencia6 = '202011';
           $meses_2020[] = "$competencia6"; 
        }
        if($competencia7){
           $competencia7 = '202012';
           $meses_2020[] = "$competencia7"; 
        }
       
        $competencia8 = $this->input->post("competencia8"); // 6/2020
        $competencia9 = $this->input->post("competencia9"); // 7/2020
        $competencia10 = $this->input->post("competencia10"); // 8/2020
        $competencia11 = $this->input->post("competencia11"); // 9/2020
        $competencia12 = $this->input->post("competencia12"); // 10/2020
        $competencia13 = $this->input->post("competencia13"); // 11/2020
        $competencia14 = $this->input->post("competencia14"); // 11/2020
        $competencia15 = $this->input->post("competencia15"); // 11/2020
        
        $competencia16 = $this->input->post("competencia16"); // 11/2020
        $competencia17 = $this->input->post("competencia17"); // 11/2020
        $competencia18 = $this->input->post("competencia18"); // 11/2020
        $competencia19 = $this->input->post("competencia19"); // 11/2020
        
        if($competencia8){
           $competencia8 = '202101';
           $meses_2020[] = "$competencia8"; 
        }
        if($competencia9){
           $competencia9 = '202102';
           $meses_2020[] = "$competencia9"; 
        }
        if($competencia10){
           $competencia10 = '202103';
           $meses_2020[] = "$competencia10"; 
        }
        if($competencia11){
           $competencia11 = '202104';
           $meses_2020[] = "$competencia11"; 
        }
        if($competencia12){
           $competencia12 = '202105';
           $meses_2020[] = "$competencia12"; 
        }
        if($competencia13){
           $competencia13 = '202106';
           $meses_2020[] = "$competencia13"; 
        }
        if($competencia14){
           $competencia14 = '202107';
           $meses_2020[] = "$competencia14"; 
        }
        
        if($competencia15){
           $competencia15 = '202108';
           $meses_2020[] = "$competencia15"; 
        }
        
        if($competencia16){
           $competencia16 = '202109';
           $meses_2020[] = "$competencia16"; 
        }
        
        if($competencia17){
           $competencia17 = '202110';
           $meses_2020[] = "$competencia17"; 
        }
        
        if($competencia18){
           $competencia18 = '202111';
           $meses_2020[] = "$competencia18"; 
        }
        
        if($competencia19){
           $competencia19 = '202112';
           $meses_2020[] = "$competencia19"; 
        }
        
        $cont_m_2020 = 1;
        $count_m_2020 = count($meses_2020);
        $mes_2020 = "";
        foreach ($meses_2020 as $mes){
            
           if($cont_m_2020 == $count_m_2020){
                $mes_2020 .= "'".$mes."'";
                }else{
                $mes_2020 .= "'".$mes."',";    
                }
                $cont_m_2020++;
            
        }
        
      
        /*
         * 2022
         */
        $competencia20 = $this->input->post("competencia20"); // 6/2020
        $competencia21 = $this->input->post("competencia21"); // 7/2020
        $competencia22 = $this->input->post("competencia22"); // 8/2020
        $competencia23 = $this->input->post("competencia23"); // 9/2020
        $competencia24 = $this->input->post("competencia24"); // 10/2020
        $competencia25 = $this->input->post("competencia25"); // 11/2020
        $competencia26 = $this->input->post("competencia26"); // 11/2020
        $competencia27 = $this->input->post("competencia27"); // 11/2020
        
        $competencia28 = $this->input->post("competencia28"); // 11/2020
        $competencia29 = $this->input->post("competencia29"); // 11/2020
        $competencia30 = $this->input->post("competencia30"); // 11/2020
        $competencia31 = $this->input->post("competencia31"); // 11/2020
        
        
        if($competencia20){
           $competencia20 = '202201';
           $meses_2020[] = "$competencia20"; 
        }
        if($competencia21){
           $competencia21 = '202202';
           $meses_2020[] = "$competencia21"; 
        }
        if($competencia22){
           $competencia22 = '202203';
           $meses_2020[] = "$competencia22"; 
        }
        if($competencia23){
           $competencia23 = '202204';
           $meses_2020[] = "$competencia23"; 
        }
        if($competencia24){
           $competencia24 = '202205';
           $meses_2020[] = "$competencia24"; 
        }
        if($competencia25){
           $competencia25 = '202206';
           $meses_2020[] = "$competencia25"; 
        }
        if($competencia26){
           $competencia26 = '202207';
           $meses_2020[] = "$competencia26"; 
        }
        
        if($competencia27){
           $competencia27 = '202208';
           $meses_2020[] = "$competencia27"; 
        }
        
        if($competencia28){
           $competencia28 = '202209';
           $meses_2020[] = "$competencia28"; 
        }
        
        if($competencia29){
           $competencia29 = '202210';
           $meses_2020[] = "$competencia29"; 
        }
        
        if($competencia30){
           $competencia30 = '202211';
           $meses_2020[] = "$competencia30"; 
        }
        
        if($competencia31){
           $competencia31 = '202212';
           $meses_2020[] = "$competencia31"; 
        }
        
        $cont_m_2020 = 1;
        $count_m_2020 = count($meses_2020);
        $mes_2020 = "";
        foreach ($meses_2020 as $mes){
            
           if($cont_m_2020 == $count_m_2020){
                $mes_2020 .= "'".$mes."'";
                }else{
                $mes_2020 .= "'".$mes."',";    
                }
                $cont_m_2020++;
            
        }
        
        
          
        /*
         * MEDICOS
         */
        $where_medico = "";
        $total = count($medicos_faturamento);
        $cont = 1;
        foreach ($medicos_faturamento as $medico){
            if($cont == $total){
                $where_medico .= "$medico";
            }else{
                $where_medico .= "$medico,";
            }
         $cont++;   
        }
       
        //$medicos = $this->Medicos_model->get_where_in($where_medico);
       
        /*
         * CONVENIOS
         */
        $where_convenio = "";
        $total_convenio = count($convenios_faturamento);
        $cont_conv = 1;
        foreach ($convenios_faturamento as $convenio){
            if($cont_conv == $total_convenio){
                $where_convenio .= "$convenio";
            }else{
                $where_convenio .= "$convenio,";
            }
         $cont_conv++;   
        }
       
      
       
        $data['medicos']   = $where_medico;
        $data['convenios_faturamento']   = $where_convenio;
        
     
       
        
        
        /*
         * PRODUCAO TOTAL POR MES
         */
       $producao_total_mes_2022 = $this->dashboard_model->get_producao_total_mes($where_convenio, '2022');
       $data['producao_total_2022'] = $producao_total_mes_2022;
       
       $producao_total_mes = $this->dashboard_model->get_producao_total_mes($where_convenio, '2021');
       $data['producao_total'] = $producao_total_mes;
      
       /*
         * PRODUCAO TOTAL POR CONVENIO  - ok
         */
       $producao_resumo_convenio = $this->dashboard_model->get_resumo_producao_convenio($where_convenio, $mes_2020);
       $data['producao_resumo_convenio'] = $producao_resumo_convenio;
       
       /*
        * PRODUÇÃO DETALHES POR CONVENIO - TABELA  - ok
        */
       $producao_detalhes_resumo_convenio = $this->dashboard_model->get_resumo_detalhes_producao_convenio($where_convenio, $mes_2020);
       $data['producao_detalhes_resumo_convenio'] = $producao_detalhes_resumo_convenio;
       
        /*
         * PRODUCAO TOTAL POR CONVENIO POR MES 
         */
       
       $competencias = $this->dashboard_model->get_competencia_producao_convenio_mes();
       foreach ($competencias as $competencia){
           $mes = $competencia['mes'];
           $ano = $competencia['ano'];
          
          $producao_total_convenio = $this->dashboard_model->get_producao_convenio_mes($where_convenio, $ano, $mes);
          $data['producao_total_convenio'] = $producao_total_convenio;
       }
       
        /*************************************************************************/
       
        /*
         * PRODUCAO TOTAL POR MÉDICO
         */
      
       $producao_resumo_medico = $this->dashboard_model->get_resumo_producao_medico($where_medico, $where_convenio, $mes_2020);
       $data['producao_resumo_medico'] = $producao_resumo_medico;
       
       /*
       * PRODUÇÃO DETALHES POR MEDICO - TABELA
        */
       $producao_detalhes_resumo_medico = $this->dashboard_model->get_resumo_detalhes_producao_medico($where_medico, $where_convenio, $mes_2020);
       $data['producao_detalhes_resumo_medico'] = $producao_detalhes_resumo_medico;
        
        /*
         * RECEITAS FATURAMENTO POR CONTA / MÉDICO
         
        $recebimentos_contas = $this->reports_model->get_receita_conta_medica($where_medico, $where_convenio, $data_de, $data_ate);
        $data['recebimentos_contas']                           = $recebimentos_contas;
       
        /*
         * VALOR DE RECEBIMENTO NA TESOURARIA POR DIA
         
        $recebimentos_tesouraria = $this->reports_model->get_recebimentos_tesouraria($where_medico, $where_convenio, $data_de, $data_ate);
        $data['recebimentos_tesouraria']        = $recebimentos_tesouraria;
        
        /*
         * VALOR DE RECEBIMENTO NA TESOURARIA POR FORMA DE PAGAMENTO
         
        $recebimentos_formas_pagamento = $this->reports_model->get_recebimentos_tesouraria_forma_pagamento($where_medico, $where_convenio, $data_de, $data_ate);
        $data['recebimentos_formas_pagamento'] = $recebimentos_formas_pagamento;
        
        
        /*
         * PRODUÇÃO FATURADA POR CATEGORIA / MÉDICO
         
        $producao_categoria = $this->reports_model->get_categorias_procedimentos($where_medico, $where_convenio, $where_categoria, $procedimento_faturamento,  $data_de, $data_ate);
        $data['producao_categoria'] = $producao_categoria;
        
        /*
         * PRODUÇÃO FATURADA POR CATEGORIA / MÉDICO
         
        $producao_procedimentos = $this->reports_model->get_all_procedimentos($where_medico, $where_convenio, $where_categoria, $procedimento_faturamento,  $data_de, $data_ate);
        $data['producao_procedimentos'] = $producao_procedimentos;
        
        
        /*
         * RECEBIMENTO VISION
         */
       // $recebimentos_vision = $this->reports_model->get_recebimentos_tesouraria_forma_pagamento(1, $where_convenio, $data_de, $data_ate);
       // $data['recebimentos_vision'] = $recebimentos_vision;
        
        $this->load->view('admin/dashboard/retorno_dashboard_producao_convenio', $data);
       
    }

    
    
    
    /*
     * DASHBOARD PRODUÇÃO MÉDICO
     */
    public function dashboard_producao_medico($medico_id = "")
    {
        close_setup_menu();
        
        $data['medicos']   = $this->dashboard_model->get_medicos_producao_id($medico_id);
        $data['convenios']  = $this->dashboard_model->get_convenios_producao();
       
        $data['medico_id']  = $medico_id;
        
       // $data['categorias']            = $this->Invoice_items_model->get_groups();
       // $data['procedimentos']         = $this->Invoice_items_model->get_ativos();
        
        //$data = hooks()->apply_filters('before_dashboard_render', $data);
        $this->load->view('admin/dashboard/dashboard_producao_medico', $data);
    }
    
    public function retorno_dashboard_producao_medico()
    {
        $medico_id = $this->input->post("medico_id");
        $convenios_faturamento = $this->input->post("convenios_faturamento");
           $meses_2020 = array();
        
        $medicos_faturamento = $this->input->post("medicos_faturamento");
        $convenios_faturamento = $this->input->post("convenios_faturamento");
        $competencia1 = $this->input->post("competencia1"); // 6/2020
        $competencia2 = $this->input->post("competencia2"); // 7/2020
        $competencia3 = $this->input->post("competencia3"); // 8/2020
        $competencia4 = $this->input->post("competencia4"); // 9/2020
        $competencia5 = $this->input->post("competencia5"); // 10/2020
        $competencia6 = $this->input->post("competencia6"); // 11/2020
        $competencia7 = $this->input->post("competencia7"); // 12/2020
        
        if($competencia1){
           $competencia1 = '202006';
            $meses_2020[] = "$competencia1"; 
        }
        if($competencia2){
           $competencia2 = '202007';
           $meses_2020[] = "$competencia2"; 
        }
        if($competencia3){
           $competencia3 = '202008';
           $meses_2020[] = "$competencia3"; 
        }
        if($competencia4){
           $competencia4 = '202009';
           $meses_2020[] = "$competencia4"; 
        }
        if($competencia5){
           $competencia5 = '202010';
           $meses_2020[] = "$competencia5"; 
        }
        if($competencia6){
           $competencia6 = '202011';
           $meses_2020[] = "$competencia6"; 
        }
        if($competencia7){
           $competencia7 = '202012';
           $meses_2020[] = "$competencia7"; 
        }
       
        $competencia8 = $this->input->post("competencia8"); // 6/2020
        $competencia9 = $this->input->post("competencia9"); // 7/2020
        $competencia10 = $this->input->post("competencia10"); // 8/2020
        $competencia11 = $this->input->post("competencia11"); // 9/2020
        $competencia12 = $this->input->post("competencia12"); // 10/2020
        $competencia13 = $this->input->post("competencia13"); // 11/2020
        $competencia14 = $this->input->post("competencia14"); // 11/2020
        $competencia15 = $this->input->post("competencia15"); // 11/2020
        
        $competencia16 = $this->input->post("competencia16"); // 11/2020
        $competencia17 = $this->input->post("competencia17"); // 11/2020
        $competencia18 = $this->input->post("competencia18"); // 11/2020
        $competencia19 = $this->input->post("competencia19"); // 11/2020
        
        if($competencia8){
           $competencia8 = '202101';
           $meses_2020[] = "$competencia8"; 
        }
        if($competencia9){
           $competencia9 = '202102';
           $meses_2020[] = "$competencia9"; 
        }
        if($competencia10){
           $competencia10 = '202103';
           $meses_2020[] = "$competencia10"; 
        }
        if($competencia11){
           $competencia11 = '202104';
           $meses_2020[] = "$competencia11"; 
        }
        if($competencia12){
           $competencia12 = '202105';
           $meses_2020[] = "$competencia12"; 
        }
        if($competencia13){
           $competencia13 = '202106';
           $meses_2020[] = "$competencia13"; 
        }
        if($competencia14){
           $competencia14 = '202107';
           $meses_2020[] = "$competencia14"; 
        }
        
        if($competencia15){
           $competencia15 = '202108';
           $meses_2020[] = "$competencia15"; 
        }
        
        if($competencia16){
           $competencia16 = '202109';
           $meses_2020[] = "$competencia16"; 
        }
        
        if($competencia17){
           $competencia17 = '202110';
           $meses_2020[] = "$competencia17"; 
        }
        
        if($competencia18){
           $competencia18 = '202111';
           $meses_2020[] = "$competencia18"; 
        }
        
        if($competencia19){
           $competencia19 = '202112';
           $meses_2020[] = "$competencia19"; 
        }
        
        $cont_m_2020 = 1;
        $count_m_2020 = count($meses_2020);
        $mes_2020 = "";
        foreach ($meses_2020 as $mes){
            
           if($cont_m_2020 == $count_m_2020){
                $mes_2020 .= "'".$mes."'";
                }else{
                $mes_2020 .= "'".$mes."',";    
                }
                $cont_m_2020++;
            
        }

        /*
         * 2022
         */
        $competencia20 = $this->input->post("competencia20"); // 6/2020
        $competencia21 = $this->input->post("competencia21"); // 7/2020
        $competencia22 = $this->input->post("competencia22"); // 8/2020
        $competencia23 = $this->input->post("competencia23"); // 9/2020
        $competencia24 = $this->input->post("competencia24"); // 10/2020
        $competencia25 = $this->input->post("competencia25"); // 11/2020
        $competencia26 = $this->input->post("competencia26"); // 11/2020
        $competencia27 = $this->input->post("competencia27"); // 11/2020
        
        $competencia28 = $this->input->post("competencia28"); // 11/2020
        $competencia29 = $this->input->post("competencia29"); // 11/2020
        $competencia30 = $this->input->post("competencia30"); // 11/2020
        $competencia31 = $this->input->post("competencia31"); // 11/2020
        
        
        if($competencia20){
           $competencia20 = '202201';
           $meses_2020[] = "$competencia20"; 
        }
        if($competencia21){
           $competencia21 = '202202';
           $meses_2020[] = "$competencia21"; 
        }
        if($competencia22){
           $competencia22 = '202203';
           $meses_2020[] = "$competencia22"; 
        }
        if($competencia23){
           $competencia23 = '202204';
           $meses_2020[] = "$competencia23"; 
        }
        if($competencia24){
           $competencia24 = '202205';
           $meses_2020[] = "$competencia24"; 
        }
        if($competencia25){
           $competencia25 = '202206';
           $meses_2020[] = "$competencia25"; 
        }
        if($competencia26){
           $competencia26 = '202207';
           $meses_2020[] = "$competencia26"; 
        }
        
        if($competencia27){
           $competencia27 = '202208';
           $meses_2020[] = "$competencia27"; 
        }
        
        if($competencia28){
           $competencia28 = '202209';
           $meses_2020[] = "$competencia28"; 
        }
        
        if($competencia29){
           $competencia29 = '202210';
           $meses_2020[] = "$competencia29"; 
        }
        
        if($competencia30){
           $competencia30 = '202211';
           $meses_2020[] = "$competencia30"; 
        }
        
        if($competencia31){
           $competencia31 = '202212';
           $meses_2020[] = "$competencia31"; 
        }
        
        $cont_m_2020 = 1;
        $count_m_2020 = count($meses_2020);
        $mes_2020 = "";
        foreach ($meses_2020 as $mes){
            
           if($cont_m_2020 == $count_m_2020){
                $mes_2020 .= "'".$mes."'";
                }else{
                $mes_2020 .= "'".$mes."',";    
                }
                $cont_m_2020++;
            
        }
        
          
        /*
         * MEDICOS
         */
        
       
        //$medicos = $this->Medicos_model->get_where_in($where_medico);
       
        /*
         * CONVENIOS
         */
        $where_convenio = "";
        $total_convenio = count($convenios_faturamento);
        $cont_conv = 1;
        foreach ($convenios_faturamento as $convenio){
            if($cont_conv == $total_convenio){
                $where_convenio .= "$convenio";
            }else{
                $where_convenio .= "$convenio,";
            }
         $cont_conv++;   
        }
       
      
       
        $data['medicos']   = $medico_id;
        $data['convenios_faturamento']   = $where_convenio;
        
      
   
       
        
        
        /*
         * PRODUCAO TOTAL POR MES
         */
       $producao_total_mes = $this->dashboard_model->get_producao_medico_total_mes( $medico_id);
       $data['producao_total'] = $producao_total_mes;
      
       /*
         * PRODUCAO TOTAL POR CONVENIO
         */
       $producao_resumo_convenio = $this->dashboard_model->get_resumo_producao_medico_convenio($where_convenio, $mes_2020,  $medico_id);
       $data['producao_resumo_convenio'] = $producao_resumo_convenio;
       
       /*
        * PRODUÇÃO DETALHES POR CONVENIO - TABELA detalhes
        */
       //$producao_detalhes_resumo_convenio = $this->dashboard_model->get_resumo_detalhes_producao_convenio($where_convenio, $medico_id);
       //$data['producao_detalhes_resumo_convenio'] = $producao_detalhes_resumo_convenio;
       
       $producao_detalhes_resumo_convenio = $this->dashboard_model->get_resumo_detalhes_producao_convenio_medico_by_id($where_convenio, $mes_2020, $medico_id);
      // print_r($producao_resumo_medico); exit;
       $data['producao_detalhes_resumo_convenio'] = $producao_detalhes_resumo_convenio;
       
        /*
         * PRODUCAO TOTAL POR CONVENIO POR MES
         */
       
       $competencias = $this->dashboard_model->get_competencia_producao_convenio_mes();
       foreach ($competencias as $competencia){
           $mes = $competencia['mes'];
           $ano = $competencia['ano'];
          
          $producao_total_convenio = $this->dashboard_model->get_producao_convenio_mes($where_convenio, $ano, $mes);
          $data['producao_total_convenio'] = $producao_total_convenio;
       }
       
        /*************************************************************************/
       
        /*
         * PRODUCAO TOTAL POR MÉDICO
         */
      
       $producao_resumo_medico = $this->dashboard_model->get_resumo_producao_medico_por_mes_by_id($medico_id);
       $data['producao_resumo_medico'] = $producao_resumo_medico;
       
       /*
       * PRODUÇÃO DETALHES POR MEDICO - TABELA
        */
       $producao_detalhes_resumo_medico = $this->dashboard_model->get_resumo_detalhes_producao_medico_by_id($where_convenio, $mes_2020, $medico_id);
       $data['producao_detalhes_resumo_medico'] = $producao_detalhes_resumo_medico;
        
        
        
        
        $this->load->view('admin/dashboard/retorno_dashboard_producao_medico', $data);
       
    }
    
    /* Chart weekly payments statistics on home page / ajax */
    public function weekly_payments_statistics($currency)
    {
        if ($this->input->is_ajax_request()) {
            echo json_encode($this->dashboard_model->get_weekly_payments_statistics($currency));
            die();
        }
    }
}
