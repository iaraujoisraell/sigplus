<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Financeiro_report extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('invoices_model');
        $this->load->model('Financeiro_model');
        $this->load->model('credit_notes_model');
       // $this->load->model('Medicos_model');
       // $this->load->model('Convenios_model');
        $this->load->model('Clients_model');
        $this->load->model('payments_model');
      //  $this->load->model('Nota_fiscal_model');
       // $this->load->model('Centro_custo_model');
      //  $this->load->model('Contas_financeiras_model');
      //  $this->load->model('appointly_model', 'apm'); 
      //  $this->load->model('caixas_model');
        $this->load->model('reports_model');
     //   $this->load->model('Medicos_model');
     //   $this->load->model('Convenios_model');
     //   $this->load->model('Contas_financeiras_model');
        
    }

    /* Get all invoices in case user go on index page */
    public function index()
    {
     //   $this->expenses_vs_income();
    }

   
    /*
     * DASHBOARD
     */
    public function dashboard($year = '')
    {
        
        $_expenses_years = [];
        $_years          = [];
        $this->load->model('expenses_model');
        $expenses_years = $this->Financeiro_model->get_saidas_years();
        $payments_years = $this->Financeiro_model->get_entradas_years();

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
        $data['chart_expenses_vs_income_values'] = json_encode($this->Financeiro_model->get_expenses_vs_income_report($year));
        $data['chart_income_previsto_vs_realizado_values'] = json_encode($this->Financeiro_model->get_income_previstas_vs_realizada_report($year));
        $data['chart_expense_previsto_vs_realizado_values'] = json_encode($this->Financeiro_model->get_expense_previstas_vs_realizada_report($year));
        $data['total_despesas_realizadas']                  = $this->Financeiro_model->get_total_expenses_realizado($year);
        $data['total_despesas_previsto']                    = $this->Financeiro_model->get_total_expenses_previsto($year);
        $data['total_entradas_realizadas']                  = $this->Financeiro_model->get_total_entradas_por_ano($year);
        $data['total_entradas_prevista']                    = $this->Financeiro_model->get_total_entradas_prevista($year);
        $data['saldo']                                      = $this->Financeiro_model->get_saldo($year);
        $data['base_currency']                   = get_base_currency();
        $data['title']                           = _l('als_expenses_vs_income');
        $this->load->view('admin/financeiro/dashboard/index', $data);
    }
    
    // relatório financeiro de saídas
    public function saidas($type = 'simple_report')
    {
        $this->load->model('currencies_model');
        $data['base_currency'] = $this->currencies_model->get_base_currency();
        $data['currencies']    = $this->currencies_model->get();

        $data['title'] = _l('expenses_report');
       
            
            if (!$this->input->get('year')) {
                $data['current_year'] = date('Y');
            } else {
                $data['current_year'] = $this->input->get('year');
            }


            $data['export_not_supported'] = ($this->agent->browser() == 'Internet Explorer' || $this->agent->browser() == 'Spartan');

            $this->load->model('expenses_model');

            $data['chart_not_billable'] = json_encode($this->Financeiro_model->get_stats_chart_data(_l('not_billable_expenses_by_categories'), [
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

            //$data['expense_years'] = $this->expenses_model->get_expenses_years();
            $data['expense_years'] = $this->Financeiro_model->get_saidas_years();
            
            

            if (count($data['expense_years']) > 0) {
                // Perhaps no expenses in new year?
                if (!in_array_multidimensional($data['expense_years'], 'year', date('Y'))) {
                    array_unshift($data['expense_years'], ['year' => date('Y')]);
                }
            }

            //$data['categories'] = $this->expenses_model->get_category();
            $data['categorias_despesas_financeira'] = $this->Financeiro_model->get_categorias_contas_pagar();
            

            $this->load->view('admin/financeiro/reports/expenses', $data);
        
    }
    
    // relatório financeiro de entradas
    public function entradas()
    {
        $this->load->model('currencies_model');
        $data['base_currency'] = $this->currencies_model->get_base_currency();
        $data['currencies']    = $this->currencies_model->get();

        $data['title'] = 'Relatório de Entradas';
       
            
            if (!$this->input->get('year')) {
                $data['current_year'] = date('Y');
            } else {
                $data['current_year'] = $this->input->get('year');
            }

            $data['export_not_supported'] = ($this->agent->browser() == 'Internet Explorer' || $this->agent->browser() == 'Spartan');


            $data['chart_not_billable'] = json_encode($this->Financeiro_model->get_stats_chart_data(_l('not_billable_expenses_by_categories'), [
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

            $data['invoices_years'] = $this->Financeiro_model->get_entradas_years();
            
            

            if (count($data['invoices_years']) > 0) {
                // Perhaps no expenses in new year?
                if (!in_array_multidimensional($data['invoices_years'], 'year', date('Y'))) {
                    array_unshift($data['invoices_years'], ['year' => date('Y')]);
                }
            }

            //$data['categories'] = $this->expenses_model->get_category();
            $data['categorias_entradas_financeira'] = $this->Financeiro_model->get_categorias_invoices();
            

            $this->load->view('admin/financeiro/reports/entradas', $data);
        
    }
     
    
    public function fluxo_lancamentos($mes = '', $ano = '')
    {
        if(!$ano){
            $ano = date('Y');
        }
        
        $mes_nome = array('', 'Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro');
        $monthName = $mes_nome[$mes];
        $data['title'] = 'Fluxo de caixa - '.$monthName;
        $data['monthName'] =  $monthName;
            
            if (!$this->input->get('year')) {
                $data['current_year'] = date('Y');
            } else {
                $data['current_year'] = $this->input->get('year');
            }

            $data['mes'] = $mes;
            $data['ano'] = $ano;
            
            $data['export_not_supported'] = ($this->agent->browser() == 'Internet Explorer' || $this->agent->browser() == 'Spartan');


           
            $data['invoices_years'] = $this->Financeiro_model->get_entradas_years();
            if (count($data['invoices_years']) > 0) {
                // Perhaps no expenses in new year?
                if (!in_array_multidimensional($data['invoices_years'], 'year', date('Y'))) {
                    array_unshift($data['invoices_years'], ['year' => date('Y')]);
                }
            }
            
            
            //$data['categories'] = $this->expenses_model->get_category();
           // $data['categorias_entradas_financeira'] = $this->Financeiro_model->get_categorias_invoices($mes, $ano);
            
            // saidas
            $data['categorias'] = $this->Financeiro_model->get_categoria_saida_fluxo($mes, $ano);
            $data['total_categorias'] = $this->Financeiro_model->get_total_categoria_saida_fluxo($mes, $ano);
            
            // entradas
            $data['total_categorias_entradas'] = $this->Financeiro_model->get_total_categoria_entrada_fluxo($mes, $ano);
            $data['categorias_entradas'] = $this->Financeiro_model->get_categoria_entrada_fluxo($mes, $ano);
            
            $this->load->view('admin/financeiro/reports/fluxo', $data);
        
    }
    
}
