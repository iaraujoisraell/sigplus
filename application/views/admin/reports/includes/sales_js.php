<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<script>
 var salesChart;
 var groupsChart;
 var paymentMethodsChart;
 var customersTable;
 var report_from = $('input[name="report-from"]');
 var report_to = $('input[name="report-to"]');
 var report_customers = $('#customers-report');
 var report_customers_groups = $('#customers-group');
 var report_invoices = $('#invoices-report');
 var report_estimates = $('#estimates-report');
 var report_proposals = $('#proposals-reports');
 var report_items = $('#items-report');
 
 var report_credit_notes = $('#credit-notes');
 var report_faturamento = $('#faturamento-report');
 var report_custo = $('#custo-report');
 var report_faturamento_producao = $('#faturamento-producao-report');
 //var report_items_medico = $('#items-medico-report');
 var report_faturamento_medico = $('#faturamento-medico-report');
 var report_payments_received = $('#payments-received-report');
 var report_agendamentos = $('#agendamentos-received-report'); 
 var report_agendamentos_resumo = $('#agendamentos-resumo-report'); 

 var report_fluxo_caixa = $('#payments-fluxo_caixa-report');
  var report_nota = $('#nota-report');
 var report_payments_conta_financeira = $('#payments-conta_financeira-report');
 var report_payments_received_forma_pagamento = $('#payments-received-report_forma_pagamento');
 var date_range = $('#date-range');
 var report_from_choose = $('#report-time'); 
 var report_fluxo_caixa_financeira = $('#payments-fluxo_caixa-report');


 var fnServerParams = {
   "report_months": '[name="months-report"]',
   "report_from": '[name="report-from"]',
   "report_to": '[name="report-to"]',
   "report_currency": '[name="currency"]',
   "invoice_status": '[name="invoice_status"]',
   "estimate_status": '[name="estimate_status"]',
   "sale_agent_invoices": '[name="sale_agent_invoices"]',
   "sale_agent_items": '[name="sale_agent_items"]',
   "medicos": '[name="medicos"]',
   "medicos_faturamento": '[name="medicos_faturamento"]',
   "medicos_custo": '[name="medicos_custo"]',
   "medicos_producao": '[name="medicos_producao"]', // rel producao
   "medicos_agendamento": '[name="medicos_agendamento"]', // real de agendamento
   "medicos_agendamento_resumo": '[name="medicos_agendamento_resumo"]', // real de agendamento
   "medicos_faturamento_medico": '[name="medicos_faturamento_medico"]',
   "medicos_pagamento_recebido": '[name="medicos_pagamento_recebido"]', // filtro pagamentos recebidos
   "medicos_conta_financeira": '[name="medicos_conta_financeira"]', // filtro pagamento recebidos - conta financeira
   "medicos_resumo_forma_pagamento": '[name="medicos_resumo_forma_pagamento"]',
   "caixa_pagamento_recebido": '[name="caixa_pagamento_recebido"]',
   "caixa_forma_pagamento": '[name="caixa_forma_pagamento"]',
   "convenios": '[name="convenios"]',
   "convenio_producao_medico": '[name="convenio_producao_medico"]',
   "convenios_agendamento": '[name="convenios_agendamento"]', // rel de agendamentos 
   "convenios_agendamento_resumo": '[name="convenios_agendamento_resumo"]', // rel de agendamentos 
   "convenios_faturamento": '[name="convenios_faturamento"]',
   "convenios_custo": '[name="convenios_custo"]',
   "convenios_pagamento": '[name="convenios_pagamento"]', // rel de pagamentos
   "convenios_pagamento_conta": '[name="convenios_pagamento_conta"]', // rel de pagamentos por conta financeira
   "categorias": '[name="categorias"]',
   "categorias_faturamento": '[name="categorias_faturamento"]',
   "categorias_custo": '[name="categorias_custo"]',
   "categorias_producao_medica": '[name="categorias_producao_medica"]',
   "sale_agent_estimates": '[name="sale_agent_estimates"]',
   "proposals_sale_agents": '[name="proposals_sale_agents"]',
   "proposal_status": '[name="proposal_status"]',
   "credit_note_status": '[name="credit_note_status"]',
   "procedimento_fatura": '[name="procedimento_fatura"]',
   "procedimento_fatura_producao": '[name="procedimento_fatura_producao"]',
   "tipo_agendamento": '[name="tipo_agendamento"]', // rel de agendamentos
   "tipo_agendamento_resumo": '[name="tipo_agendamento_resumo"]', // rel de agendamentos
   "tipo_agendamento_lista": '[name="tipo_agendamento_lista"]', // rel de agendamentos   
   "status_agendamento": '[name="status_agendamento"]', // rel de agendamentos 
   "status_agendamento_resumo": '[name="status_agendamento_resumo"]', // rel de agendamentos 
 }
 $(function() {
   $('select[name="currency"],select[name="invoice_status"],\n\
      select[name="estimate_status"],select[name="sale_agent_invoices"],select[name="sale_agent_items"],\n\
      select[name="medicos"],\n\
      select[name="medicos_agendamento"],\n\
      select[name="medicos_producao"],\n\
      select[name="medicos_agendamento_resumo"],\n\
      select[name="medicos_faturamento"],\n\
      select[name="medicos_custo"],\n\
      select[name="medicos_faturamento_medico"],\n\
      select[name="medicos_resumo_forma_pagamento"],\n\
      select[name="medicos_pagamento_recebido"],\n\
      select[name="medicos_conta_financeira"],\n\
      select[name="convenios"],\n\
      select[name="caixa_pagamento_recebido"],\n\
      select[name="caixa_forma_pagamento"],\n\
      select[name="convenios_agendamento"],\n\
      select[name="convenio_producao_medico"],\n\
      select[name="convenios_agendamento_resumo"],\n\
      select[name="convenios_faturamento"],\n\
      select[name="convenios_custo"],\n\
      select[name="convenios_pagamento"],\n\
      select[name="convenios_pagamento_conta"],\n\
      select[name="categorias"], \n\
      select[name="categorias_faturamento"], \n\
      select[name="categorias_custo"], \n\
      select[name="categorias_producao_medica"], \n\
      select[name="sale_agent_estimates"],\n\
      select[name="payments_years"],select[name="proposals_sale_agents"],\n\
      select[name="procedimento_fatura"],\n\
      select[name="procedimento_custo"],\n\
      select[name="procedimento_fatura_producao"],\n\
      select[name="proposal_status"],\n\
      select[name="tipo_agendamento"],\n\
      select[name="tipo_agendamento_resumo"],\n\
      select[name="tipo_agendamento_lista"],\n\
      select[name="status_agendamento"],\n\
      select[name="status_agendamento_resumo"],\n\
      select[name="credit_note_status"]').on('change', function() {
     gen_reports();
   });

   report_from.on('change', function() {
     var val = $(this).val();
     var report_to_val = report_to.val();
     if (val != '') {
       report_to.attr('disabled', false);
       if (report_to_val != '') {
         gen_reports();
       }
     } else {
       report_to.attr('disabled', true);
     }
   });

   report_to.on('change', function() {
     var val = $(this).val();
     if (val != '') {
       gen_reports();
     }
   });

   $('select[name="months-report"]').on('change', function() {
     var val = $(this).val();
     report_to.attr('disabled', true);
     report_to.val('');
     report_from.val('');
     if (val == 'custom') {
       date_range.addClass('fadeIn').removeClass('hide');
       return;
     } else {
       if (!date_range.hasClass('hide')) {
         date_range.removeClass('fadeIn').addClass('hide');
       }
     }
     gen_reports();
   });

    $('.table-faturamento-report').on('draw.dt', function() {
     var paymentReceivedReportsTable = $(this).DataTable();
     var sums = paymentReceivedReportsTable.ajax.json().sums;
     $(this).find('tfoot').addClass('bold');
     $(this).find('tfoot td').eq(0).html("<?php echo _l('invoice_total'); ?> (<?php echo _l('per_page'); ?>)");
     $(this).find('tfoot td.total').html(sums.total_amount);
     
     $(this).find('tfoot td.qty').html(sums.total_qty);
      $(this).find('tfoot td.valor_procedimento').html(sums.valor_procedimento);
     $(this).find('tfoot td.rate_total').html(sums.rate_total);
     
    
    // table.find('tfoot td.total_tax').html(sums.total_tax);
    $(this).find('tfoot td.desconto').html(sums.desconto);
    $(this).find('tfoot td.ajuste').html(sums.ajuste);
    $(this).find('tfoot td.applied_credits').html(sums.credits_applied);
    $(this).find('tfoot td.valor_faturado_produzido').html(sums.valor_faturado_produzido);
    $(this).find('tfoot td.valor_medico').html(sums.valor_medico); 
     
   });
   
   
   $('.table-custo-report').on('draw.dt', function() {
     var paymentReceivedReportsTable = $(this).DataTable();
     var sums = paymentReceivedReportsTable.ajax.json().sums;
     $(this).find('tfoot').addClass('bold');
     $(this).find('tfoot td').eq(0).html("<?php echo _l('invoice_total'); ?> (<?php echo _l('per_page'); ?>)");
     $(this).find('tfoot td.total').html(sums.total_amount);
     
     $(this).find('tfoot td.qty').html(sums.total_qty);
      $(this).find('tfoot td.valor_procedimento').html(sums.valor_procedimento);
     $(this).find('tfoot td.rate_total').html(sums.rate_total);
     
    
    // table.find('tfoot td.total_tax').html(sums.total_tax);
    $(this).find('tfoot td.desconto').html(sums.desconto);
    $(this).find('tfoot td.ajuste').html(sums.ajuste);
    $(this).find('tfoot td.applied_credits').html(sums.credits_applied);
    $(this).find('tfoot td.valor_faturado_produzido').html(sums.valor_faturado_produzido);
    $(this).find('tfoot td.valor_medico').html(sums.valor_medico); 
     
   });

   
   $('.table-faturamento2-report').on('draw.dt', function() {
     var paymentReceivedReportsTable = $(this).DataTable();
     var sums = paymentReceivedReportsTable.ajax.json().sums;
     $(this).find('tfoot').addClass('bold');
     $(this).find('tfoot td').eq(0).html("<?php echo _l('invoice_total'); ?> (<?php echo _l('per_page'); ?>)");
    
     
    $(this).find('tfoot td.qty').html(sums.total_qty);
    $(this).find('tfoot td.total').html(sums.total_amount);
    $(this).find('tfoot td.desconto').html(sums.desconto);
    $(this).find('tfoot td.valor_empresa').html(sums.valor_empresa);
    $(this).find('tfoot td.valor_medico').html(sums.valor_medico); 
     
   });

    $('.table-fluxo_caixa-report').on('draw.dt', function() {
     var paymentReceivedReportsTable = $(this).DataTable();
     var sums = paymentReceivedReportsTable.ajax.json().sums;
     $(this).find('tfoot').addClass('bold');
   
   $(this).find('tfoot td.saldo_dinheiro').html(sums.saldo_dinheiro);
   $(this).find('tfoot td.saldo_debito').html(sums.saldo_debito);
    $(this).find('tfoot td.saldo_credito').html(sums.saldo_credito);
    $(this).find('tfoot td.saldo_outros').html(sums.saldo_outros);
    $(this).find('tfoot td.saldo_total').html(sums.saldo_total);
   });
   
   $('.table-fluxo_caixa_total-report').on('draw.dt', function() {
     var paymentReceivedReportsTable = $(this).DataTable();
     var sums = paymentReceivedReportsTable.ajax.json().sums;
     $(this).find('tfoot').addClass('bold');
   
   $(this).find('tfoot td.total_saidas').html(sums.total_saidas);
   $(this).find('tfoot td.total_entradas').html(sums.total_entradas);
   $(this).find('tfoot td.saldo').html(sums.saldo);
   });

   $('.table-payments-received-report').on('draw.dt', function() {
     var paymentReceivedReportsTable = $(this).DataTable();
     var sums = paymentReceivedReportsTable.ajax.json().sums;
     $(this).find('tfoot').addClass('bold');
     $(this).find('tfoot td').eq(0).html("<?php echo _l('invoice_total'); ?> (<?php echo _l('per_page'); ?>)");
     $(this).find('tfoot td.total').html(sums.total_amount);
   });
   
   $('.table-payments-received-report_forma_pagamento').on('draw.dt', function() {
     var paymentReceivedReportsTable = $(this).DataTable();
     var sums = paymentReceivedReportsTable.ajax.json().sums;
     $(this).find('tfoot').addClass('bold');
     $(this).find('tfoot td').eq(0).html("<?php echo _l('invoice_total'); ?> (<?php echo _l('per_page'); ?>)");
     $(this).find('tfoot td.total').html(sums.total_amount);
   });
   
    $('.table-payments-conta_financeira-report').on('draw.dt', function() {
     var paymentReceivedReportsTable = $(this).DataTable();
     var sums = paymentReceivedReportsTable.ajax.json().sums;
     $(this).find('tfoot').addClass('bold');
     $(this).find('tfoot td').eq(0).html("<?php echo _l('invoice_total'); ?> (<?php echo _l('per_page'); ?>)");
     $(this).find('tfoot td.total').html(sums.total_amount);
   });

   $('.table-proposals-report').on('draw.dt', function() {
     var proposalsReportTable = $(this).DataTable();
     var sums = proposalsReportTable.ajax.json().sums;
      add_common_footer_sums($(this), sums);
      <?php foreach($proposal_taxes as $key => $tax){ ?>
        $(this).find('tfoot td.total_tax_single_<?php echo $key; ?>').html(sums['total_tax_single_<?php echo $key; ?>']);
     <?php } ?>
   });

   $('.table-invoices-report').on('draw.dt', function() {
     var invoiceReportsTable = $(this).DataTable();
     var sums = invoiceReportsTable.ajax.json().sums;
     add_common_footer_sums($(this),sums);
     $(this).find('tfoot td.amount_open').html(sums.amount_open);
     $(this).find('tfoot td.applied_credits').html(sums.applied_credits);
     <?php foreach($invoice_taxes as $key => $tax){ ?>
        $(this).find('tfoot td.total_tax_single_<?php echo $key; ?>').html(sums['total_tax_single_<?php echo $key; ?>']);
     <?php } ?>
   });

    $('.table-credit-notes-report').on('draw.dt', function() {
       var creditNotesTable = $(this).DataTable();
       var sums = creditNotesTable.ajax.json().sums;
       add_common_footer_sums($(this),sums);
       $(this).find('tfoot td.remaining_amount').html(sums.remaining_amount);
       <?php foreach($credit_note_taxes as $key => $tax){ ?>
          $(this).find('tfoot td.total_tax_single_<?php echo $key; ?>').html(sums['total_tax_single_<?php echo $key; ?>']);
       <?php } ?>
   });

   $('.table-estimates-report').on('draw.dt', function() {
     var estimatesReportsTable = $(this).DataTable();
     var sums = estimatesReportsTable.ajax.json().sums;
     add_common_footer_sums($(this),sums);
     <?php foreach($estimate_taxes as $key => $tax){ ?>
        $(this).find('tfoot td.total_tax_single_<?php echo $key; ?>').html(sums['total_tax_single_<?php echo $key; ?>']);
     <?php } ?>
   });

   $('.table-items-report').on('draw.dt', function() {
     var itemsTable = $(this).DataTable();
     var sums = itemsTable.ajax.json().sums;
     $(this).find('tfoot').addClass('bold');
     $(this).find('tfoot td').eq(0).html("<?php echo _l('invoice_total'); ?> (<?php echo _l('per_page'); ?>)");
     $(this).find('tfoot td.amount').html(sums.total_amount);
     $(this).find('tfoot td.qty').html(sums.total_qty);
     
     
   });

    $('.table-items-medico-report').on('draw.dt', function() {
     var itemsTable = $(this).DataTable();
     var sums = itemsTable.ajax.json().sums;
     $(this).find('tfoot').addClass('bold');
     $(this).find('tfoot td').eq(0).html("<?php echo _l('invoice_total'); ?> (<?php echo _l('per_page'); ?>)");
    
     $(this).find('tfoot td.qty').html(sums.total_qty);
      $(this).find('tfoot td.valor_procedimento').html(sums.valor_procedimento);
     $(this).find('tfoot td.rate_total').html(sums.rate_total);
     
    
    // table.find('tfoot td.total_tax').html(sums.total_tax);
    $(this).find('tfoot td.desconto').html(sums.desconto);
    $(this).find('tfoot td.ajuste').html(sums.ajuste);
    $(this).find('tfoot td.applied_credits').html(sums.credits_applied);
    $(this).find('tfoot td.valor_aberto').html(sums.valor_aberto);
     $(this).find('tfoot td.valor_faturado_produzido').html(sums.valor_faturado_produzido);
   });
 });

  function add_common_footer_sums(table,sums) {
       table.find('tfoot').addClass('bold');
       table.find('tfoot td').eq(0).html("<?php echo _l('invoice_total'); ?> (<?php echo _l('per_page'); ?>)");
       table.find('tfoot td.subtotal').html(sums.subtotal);
       table.find('tfoot td.total').html(sums.total);
       table.find('tfoot td.total_tax').html(sums.total_tax);
       table.find('tfoot td.discount_total').html(sums.discount_total);
       table.find('tfoot td.adjustment').html(sums.adjustment);
  }

 function init_report(e, type) {
   var report_wrapper = $('#report');

   if (report_wrapper.hasClass('hide')) {
        report_wrapper.removeClass('hide');
   }
   
   $('head title').html($(e).text());
   $('.customers-group-gen').addClass('hide');
  
   report_credit_notes.addClass('hide');
   report_customers_groups.addClass('hide');
   report_customers.addClass('hide');
   report_invoices.addClass('hide');
   report_estimates.addClass('hide'); 
   report_faturamento.addClass('hide');
   report_custo.addClass('hide');
   report_faturamento_producao.addClass('hide');
   //report_items_medico.addClass('hide');
   report_faturamento_medico.addClass('hide');
   report_agendamentos.addClass('hide'); 
   report_agendamentos_resumo.addClass('hide'); 
   report_payments_received.addClass('hide');
   report_payments_received_forma_pagamento.addClass('hide');
   report_payments_conta_financeira.addClass('hide');
   report_fluxo_caixa.addClass('hide');
   report_nota.addClass('hide');
   report_items.addClass('hide');
   
   report_proposals.addClass('hide');

   $('#income-years').addClass('hide');
   $('.chart-income').addClass('hide');
   $('.chart-payment-modes').addClass('hide');


   report_from_choose.addClass('hide');

   $('select[name="months-report"]').selectpicker('val', 'today');
   // Clear custom date picker
       report_to.val('');
       report_from.val('');
       $('#currency').removeClass('hide');

       if (type != 'total-income' && type != 'payment-modes') {
         report_from_choose.removeClass('hide');
       }
       
       
       
       if (type == 'total-income') {
         $('.chart-income').removeClass('hide');
         $('#income-years').removeClass('hide');
         date_range.addClass('hide');
       } else if (type == 'customers-report') {
         report_customers.removeClass('hide');
       } else if (type == 'customers-group') {
         $('.customers-group-gen').removeClass('hide');
       } else if (type == 'invoices-report') {
         report_invoices.removeClass('hide');
       } else if (type == 'credit-notes') {
         report_credit_notes.removeClass('hide');
       } else if (type == 'payment-modes') {
         $('.chart-payment-modes').removeClass('hide');
         $('#income-years').removeClass('hide');
       } else if (type == 'faturamento-report') {
         report_faturamento.removeClass('hide');
       } else if (type == 'custo-report') {
         report_custo.removeClass('hide');
       } else if (type == 'faturamento-producao-report') {
         report_faturamento_producao.removeClass('hide');
       } else if (type == 'faturamento-medico-report') {
         report_faturamento_medico.removeClass('hide');
       } else if (type == 'agendamentos-realizados') {
         report_agendamentos.removeClass('hide');
       } else if (type == 'agendamentos-resumo') {
         report_agendamentos_resumo.removeClass('hide');
       } else if (type == 'payments-received') {
         report_payments_received.removeClass('hide');
       } else if (type == 'payments-received_forma_pagamento') {
         report_payments_received_forma_pagamento.removeClass('hide');
       } else if (type == 'payments-conta_financeira-received') {
         report_payments_conta_financeira.removeClass('hide');
       } else if (type == 'payments-fluxo_caixa-report') {
         report_fluxo_caixa.removeClass('hide');
       } else if (type == 'estimates-report') {
         report_estimates.removeClass('hide');
       } else if(type == 'proposals-report'){
        report_proposals.removeClass('hide');
      } else if(type == 'items-report'){
         report_items.removeClass('hide');
      } else if (type == 'nota-report') {
        report_nota.removeClass('hide');
     }
      gen_reports();
    }


   // Generate total income bar
   function total_income_bar_report() {
     if (typeof(salesChart) !== 'undefined') {
       salesChart.destroy();
     }
     var data = {};
     data.year = $('select[name="payments_years"]').val();
     var currency = $('#currency');
     if (currency.length > 0) {
       data.report_currency = $('select[name="currency"]').val();
     }
     $.post(admin_url + 'reports/total_income_report', data).done(function(response) {
       response = JSON.parse(response);
       salesChart = new Chart($('#chart-income'), {
         type: 'bar',
         data: response,
         options: {
           responsive: true,
           maintainAspectRatio:false,
           legend: {
            display: false,
          },
          scales: {
            yAxes: [{
              ticks: {
                beginAtZero: true,
              }
            }]
          },
        }
      })
     });
   }

   function report_by_payment_modes() {
     if (typeof(paymentMethodsChart) !== 'undefined') {
       paymentMethodsChart.destroy();
     }
     var data = {};
     data.year = $('select[name="payments_years"]').val();
     var currency = $('#currency');
     if (currency.length > 0) {
       data.report_currency = $('select[name="currency"]').val();
     }
     $.post(admin_url + 'reports/report_by_payment_modes', data).done(function(response) {
       response = JSON.parse(response);
       paymentMethodsChart = new Chart($('#chart-payment-modes'), {
         type: 'bar',
         data: response,
         options: {
           responsive: true,
           maintainAspectRatio:false,
           scales: {
            yAxes: [{
              ticks: {
                beginAtZero: true,
              }
            }]
          },
        }
      })
     });
   }
   // Generate customers report
   function customers_report() {
     if ($.fn.DataTable.isDataTable('.table-customers-report')) {
       $('.table-customers-report').DataTable().destroy();
     }
     initDataTable('.table-customers-report', admin_url + 'reports/customers_report', false, false, fnServerParams, [0, 'asc']);
   }

   function report_by_customer_groups() {
     if (typeof(groupsChart) !== 'undefined') {
       groupsChart.destroy();
     }
     var data = {};
     data.months_report = $('select[name="months-report"]').val();
     data.report_from = report_from.val();
     data.report_to = report_to.val();

     var currency = $('#currency');
     if (currency.length > 0) {
       data.report_currency = $('select[name="currency"]').val();
     }
     $.post(admin_url + 'reports/report_by_customer_groups', data).done(function(response) {
       response = JSON.parse(response);
       groupsChart = new Chart($('#customers-group-gen'), {
         type: 'line',
         data: response,
         options:{
          maintainAspectRatio:false,
          legend: {
            display: false,
          },
          scales: {
            yAxes: [{
              ticks: {
                beginAtZero: true,
              }
            }]
          }}
        });
     });
   }
   
   function invoices_report() {
     if ($.fn.DataTable.isDataTable('.table-invoices-report')) {
       $('.table-invoices-report').DataTable().destroy();
     }
     initDataTable('.table-invoices-report', admin_url + 'reports/invoices_report', false, false, fnServerParams, [
       [2, 'desc'],
       [0, 'desc']
       ]); //.column(2).visible(false, false).columns.adjust()
   }

   function credit_notes_report(){

     if ($.fn.DataTable.isDataTable('.table-credit-notes-report')) {
       $('.table-credit-notes-report').DataTable().destroy();
     }
     initDataTable('.table-credit-notes-report', admin_url + 'reports/credit_notes', false, false, fnServerParams,[1, 'desc']);

   }

   function estimates_report() {
     if ($.fn.DataTable.isDataTable('.table-estimates-report')) {
       $('.table-estimates-report').DataTable().destroy();
     }
     initDataTable('.table-estimates-report', admin_url + 'reports/estimates_report', false, false, fnServerParams, [
       [3, 'desc'],
       [0, 'desc']
       ]).column(3).visible(false, false).columns.adjust();
   }
   
   function faturamento_reports() {
     if ($.fn.DataTable.isDataTable('.table-faturamento-report')) {
       $('.table-faturamento-report').DataTable().destroy();
     }
     initDataTable('.table-faturamento-report', admin_url + 'reports/faturamento_report', false, false, fnServerParams, [1, 'desc']);
   }
   
   function custo_reports() {
     if ($.fn.DataTable.isDataTable('.table-custo-report')) {
       $('.table-custo-report').DataTable().destroy();
     }
     initDataTable('.table-custo-report', admin_url + 'reports/custo_report', false, false, fnServerParams, [1, 'desc']);
   }
   
   function faturamento_producao_reports() {
     if ($.fn.DataTable.isDataTable('.table-faturamento2-report')) {
       $('.table-faturamento2-report').DataTable().destroy();
     }
     
     initDataTable('.table-faturamento2-report', admin_url + 'reports/sales_items_medico', false, false, fnServerParams, [1, 'desc']);
   }
   
    function items_medico_report(){
    
   if ($.fn.DataTable.isDataTable('.table-items-medico-report')) {
     $('.table-items-medico-report').DataTable().destroy();
   }
   initDataTable('.table-items-medico-report', admin_url + 'reports/sales_items_medico', false, false, fnServerParams, [0, 'asc']);
 }
   
   function faturamento_medico_reports() {
     if ($.fn.DataTable.isDataTable('.table-faturamento-medico-report')) {
       $('.table-faturamento-medico-report').DataTable().destroy();
     }
     initDataTable('.table-faturamento-medico-report', admin_url + 'reports/faturamento_medico_report', false, false, fnServerParams, [1, 'desc']);
   }

    function agendamento_reports() {
     if ($.fn.DataTable.isDataTable('.table-agendamento-realizado-report')) {
       $('.table-agendamento-realizado-report').DataTable().destroy();
     }
     initDataTable('.table-agendamento-realizado-report', admin_url + 'reports/agendamentos_report', false, false, fnServerParams, [1, 'desc']);
   }
   
   function agendamento_resumo() {
     if ($.fn.DataTable.isDataTable('.table-agenda-resumo-report')) {
       $('.table-agenda-resumo-report').DataTable().destroy();
     }
     initDataTable('.table-agenda-resumo-report', admin_url + 'reports/agendamentos_resumo_report', false, false, fnServerParams, [1, 'desc']);
   }
   
   function payments_received_reports() {
     if ($.fn.DataTable.isDataTable('.table-payments-received-report')) {
       $('.table-payments-received-report').DataTable().destroy();
     }
     initDataTable('.table-payments-received-report', admin_url + 'reports/payments_received', false, false, fnServerParams, [1, 'desc']);
   }
   
   function payments_received_reports_forma_pagamento() {
     if ($.fn.DataTable.isDataTable('.table-payments-received-report_forma_pagamento')) {
       $('.table-payments-received-report_forma_pagamento').DataTable().destroy();
     }
     initDataTable('.table-payments-received-report_forma_pagamento', admin_url + 'reports/payments_received_forma_pagamento', false, false, fnServerParams, [1, 'desc']);
     
   }
   
   function payments_conta_financeira_reports() {
     if ($.fn.DataTable.isDataTable('.table-payments-conta_financeira-report')) {
       $('.table-payments-conta_financeira-report').DataTable().destroy();
     }
     initDataTable('.table-payments-conta_financeira-report', admin_url + 'reports/payments_received_conta_financeira', false, false, fnServerParams, [1, 'desc']);
   }
   
   
   function fluxo_caixa_reports_normal() {
     if ($.fn.DataTable.isDataTable('.table-fluxo_caixa-report')) {
       $('.table-fluxo_caixa-report').DataTable().destroy();
     }
      
     initDataTable('.table-fluxo_caixa-report', admin_url + 'reports/fluxo_caixa_report', false, false, fnServerParams, [1, 'desc']);
   }
   
   function fluxo_caixa_reports() {
     
     if ($.fn.DataTable.isDataTable('.table-fluxo_caixa-report')) {
       $('.table-fluxo_caixa-report').DataTable().destroy();
     }
      
     initDataTable('.table-fluxo_caixa-report', admin_url + 'reports/fluxo_caixa_report_resumo', false, false, fnServerParams, [1, 'desc']);
     
     
      if ($.fn.DataTable.isDataTable('.table-fluxo_caixa_total-report')) {
       $('.table-fluxo_caixa_total-report').DataTable().destroy();
     }
      
     initDataTable('.table-fluxo_caixa_total-report', admin_url + 'reports/fluxo_caixa_report_detalhado', false, false, fnServerParams, [1, 'desc']);
   }

/*
 * FIM TESOURARIA
 */

   function proposals_report(){
   if ($.fn.DataTable.isDataTable('.table-proposals-report')) {
     $('.table-proposals-report').DataTable().destroy();
   }

   initDataTable('.table-proposals-report', admin_url + 'reports/proposals_report', false, false, fnServerParams, [0, 'desc']);
 }

 function items_report(){
   if ($.fn.DataTable.isDataTable('.table-items-report')) {
     $('.table-items-report').DataTable().destroy();
   }
   initDataTable('.table-items-report', admin_url + 'reports/items', false, false, fnServerParams, [0, 'asc']);
 }
 
 /*
  * NOTAS FISCAIS
  */
    function notas_fiscais_reports() {
     
      if ($.fn.DataTable.isDataTable('.table-nota-report')) {
       $('.table-nota-report').DataTable().destroy();
     }
      
     initDataTable('.table-nota-report', admin_url + 'reports/nota_fiscal_report', false, false, fnServerParams, [1, 'desc']);
   }


   // Main generate report function
   function gen_reports() {
   
       
     if (!$('.chart-income').hasClass('hide')) {
       total_income_bar_report();
     } else if (!$('.chart-payment-modes').hasClass('hide')) {
       report_by_payment_modes();
     } else if (!report_customers.hasClass('hide')) {
            
       customers_report();
     } else if (!$('.customers-group-gen').hasClass('hide')) {
       report_by_customer_groups();
     } else if (!report_invoices.hasClass('hide')) {
       invoices_report();
     } else if (!report_faturamento.hasClass('hide')) { 
       faturamento_reports();
     } else if (!report_custo.hasClass('hide')) { 
       custo_reports();
     } else if (!report_faturamento_producao.hasClass('hide')) { 
       faturamento_producao_reports();
     } else if (!report_faturamento_medico.hasClass('hide')) {
       faturamento_medico_reports();
     } else if (!report_agendamentos.hasClass('hide')) {
       agendamento_reports();
     } else if (!report_agendamentos_resumo.hasClass('hide')) {
       agendamento_resumo();
     } else if (!report_payments_received.hasClass('hide')) {
       payments_received_reports();
     } else if (!report_payments_received_forma_pagamento.hasClass('hide')) {
       payments_received_reports_forma_pagamento();
     } else if (!report_payments_conta_financeira.hasClass('hide')) {
       payments_conta_financeira_reports();
     } else if (!report_fluxo_caixa.hasClass('hide')) {
       fluxo_caixa_reports();
     } else if(!report_nota.hasClass('hide')) {
      notas_fiscais_reports();
    } else if (!report_estimates.hasClass('hide')) {
       estimates_report();
     } else if(!report_proposals.hasClass('hide')){
      proposals_report();
    } else if(!report_items.hasClass('hide')) {
      items_report();
    } else if(!report_credit_notes.hasClass('hide')) {
      credit_notes_report();
    }
    
  }
</script>
