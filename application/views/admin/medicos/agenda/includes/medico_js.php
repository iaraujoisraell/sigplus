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

 var report_items_medico = $('#items-medico-report');
 var medicos_parametrizacao_agenda = $('#medicos-parametrizacao-agenda');

 
 var date_range = $('#date-range');
 var report_from_choose = $('#report-time');
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
   "medicoid": '[name="medicoid"]',
   "medicos_faturamento": '[name="medicos_faturamento"]',
   "medicos_pagamento_recebido": '[name="medicos_pagamento_recebido"]',
   "medicos_resumo_forma_pagamento": '[name="medicos_resumo_forma_pagamento"]',
   "convenios": '[name="convenios"]',
   "convenios_faturamento": '[name="convenios_faturamento"]',
   "categorias": '[name="categorias"]',
   "categorias_faturamento": '[name="categorias_faturamento"]',
   "sale_agent_estimates": '[name="sale_agent_estimates"]',
   "proposals_sale_agents": '[name="proposals_sale_agents"]',
   "proposal_status": '[name="proposal_status"]',
   "credit_note_status": '[name="credit_note_status"]',
 }
 $(function() {
   $('select[name="currency"],select[name="invoice_status"],\n\
      select[name="estimate_status"],\n\
      select[name="medicoid"], \n\
      select[name="sale_agent_invoices"],select[name="sale_agent_items"],\n\
      select[name="medicos"],select[name="medicos_faturamento"],\n\
      select[name="medicos_resumo_forma_pagamento"],select[name="medicos_pagamento_recebido"],select[name="convenios"],select[name="convenios_faturamento"],\n\
      select[name="categorias"], select[name="categorias_faturamento"], select[name="sale_agent_estimates"],\n\
     select[name="payments_years"],select[name="proposals_sale_agents"],select[name="proposal_status"],select[name="credit_note_status"]').on('change', function() {
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

  
    $('.table-items-medico-report').on('draw.dt', function() {
     var itemsTable = $(this).DataTable();
    

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

 function init_agenda(e, type) {
   var report_wrapper = $('#report');

   if (report_wrapper.hasClass('hide')) {
        report_wrapper.removeClass('hide');
   }

   $('head title').html($(e).text());
   $('.customers-group-gen').addClass('hide');

   
   report_items_medico.addClass('hide');
   medicos_parametrizacao_agenda.addClass('hide');

   $('#income-years').addClass('hide');
   $('.chart-income').addClass('hide');
   $('.chart-payment-modes').addClass('hide');


   report_from_choose.addClass('hide');

   $('select[name="months-report"]').selectpicker('val', 'this_month');
   // Clear custom date picker
       report_to.val('');
       report_from.val('');
       $('#currency').removeClass('hide');

       if (type != 'total-income' && type != 'payment-modes') {
         report_from_choose.removeClass('hide');
       }

       if(type == 'items-medico-report'){
          report_items_medico.removeClass('hide');
       } else if(type == 'medicos-parametrizacao-agenda'){
          medicos_parametrizacao_agenda.removeClass('hide');
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
   
   
   function items_medico_report(){
   if ($.fn.DataTable.isDataTable('.table-items-medico-report')) {
     $('.table-items-medico-report').DataTable().destroy();
   }
   initDataTable('.table-items-medico-report', admin_url + 'reports/sales_items_medico', false, false, fnServerParams, [0, 'asc']);
 }
 
   
   
   function parametrizacao_agenda_medica(){
     
   if ($.fn.DataTable.isDataTable('.table-agenda-medico-report')) {
     $('.table-agenda-medico-report').DataTable().destroy();
   }
   initDataTable('.table-agenda-medico-report', admin_url + 'medicos/horario_agenda_medico', false, false, fnServerParams, [0, 'asc']);
   }

   // Main generate report function
   function gen_reports() {

    if(!report_items_medico.hasClass('hide')) {
      items_medico_report();
    } else if(!medicos_parametrizacao_agenda.hasClass('hide')) {
      parametrizacao_agenda_medica();
    }
  }
</script>
