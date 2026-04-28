<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(false); ?>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/menu/dist/css/AdminLTE.min.css">

<style>
 body {
 font-family:'Open Sans';
 background:#f1f1f1;
 }
 h3 {
 margin-top: 7px;
 font-size: 16px;
 }

 .install-row.install-steps {
 margin-bottom:15px;
 box-shadow: 0px 0px 1px #d6d6d6;
 }

 .control-label {
 font-size:13px;
 font-weight:600;
 }
 .padding-10 {
 padding:10px;
 }
 .mbot15 {
 margin-bottom:15px;
 }
 .bg-default {
 background: #03a9f4;
 border:1px solid #03a9f4;
 color:#fff;
 }
 .bg-success {
 border: 1px solid #dff0d8;
 }
 .bg-not-passed {
 border:1px solid #f1f1f1;
 border-radius:2px;
 }
 .bg-not-passed {
 border-right:0px;
 }
 .bg-not-passed.finish {
 border-right:1px solid #f1f1f1 !important;
 }
 .bg-not-passed h5 {
 font-weight:normal;
 color:#6b6b6b;
 }
 .form-control {
 box-shadow:none;
 }
 .bold {
 font-weight:600;
 }
 .col-xs-5ths,
 .col-sm-5ths,
 .col-md-5ths,
 .col-lg-5ths {
 position: relative;
 min-height: 1px;
 padding-right: 15px;
 padding-left: 15px;
 }
 .col-xs-5ths {
 width: 20%;
 float: left;
 }
 b {
 font-weight:600;
 }
 .bootstrap-select .btn-default {
 background: #fff !important;
 border: 1px solid #d6d6d6 !important;
 box-shadow: none;
 color: #494949 !important;
 padding: 6px 12px;
 }
</style>


<div class="content">
    <div class="row">
        <div class="col-md-12">
            <section class="content-header">
              <h1>
                Indicadores / Dashboard de Gestão Financeira
              </h1>
              <ol class="breadcrumb">
                <li><a href="<?php echo admin_url(''); ?>"><i class="fa fa-home"></i> Home </a></li>  
                <li><a href="<?php echo admin_url('dashboard/menu_financeiro'); ?>"> Financeiro </a></li>
                <li class="active">Indicadores/ Dashboard</li>
              </ol>
            </section>
            <Br>
            <div class="panel_s">
                <div class="panel-body">

                    <?php if (count($years) > 1 || (count($years) == 1 && $years[0] != date('Y'))) {?>
                    <select class="selectpicker" name="expense_year" onchange="change_expense_report_year(this.value);" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                        <?php foreach ($years as $year) { ?>
                        <option value="<?php echo $year; ?>"<?php if ($year == $report_year) {
                            echo ' selected';} ?>>
                            <?php echo $year; ?>
                        </option>
                        <?php
                        } ?>
                    </select>
                    <hr />
                    <?php
                    } ?>


                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-lg-4 col-xs-6">
                              <!-- small box -->
                              <div class="small-box bg-green">
                                <div class="inner">
                                  <h3><?php echo app_format_money($total_entradas_realizadas, ' R$ '); ?><sup style="font-size: 20px"></sup></h3>
                                  <p>ENTRADAS</p>
                                </div>
                                <div class="icon">
                                  <i class="fa fa-arrow-circle-left"></i>
                                </div>
                               </div>
                            </div>

                            <div class="col-lg-4 col-xs-6">
                              <!-- small box -->
                              <div class="small-box bg-red">
                                <div class="inner">
                                  <h3><?php echo app_format_money($total_despesas_realizadas, ' R$ '); ?><sup style="font-size: 20px"></sup></h3>
                                  <p>DESPESAS</p>
                                </div>
                                <div class="icon">
                                  <i class="fa fa-arrow-circle-right"></i>
                                </div>
                               </div>
                            </div>

                            <div class="col-lg-4 col-xs-6">
                              <!-- small box -->
                              <div class="small-box bg-default">
                                <div class="inner">
                                  <h3><?php echo app_format_money($saldo, ' R$ '); ?><sup style="font-size: 20px"></sup></h3>
                                  <p>SALDO</p>
                                </div>
                                <div class="icon">
                                  <i class="fa fa-arrow-circle-right"></i>
                                </div>
                              </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="row">
                            <?php for ($m=1; $m<=12; $m++) { ?>
                            <div class="col-lg-1 col-xs-6">
                              <?php $total = $this->Financeiro_model->get_saldo_mes($report_year, $m); ?>
                              <div style="background-color: gray; color: #ffffff;" class="small-box bg-secondary">
                                <div class="inner">
                                  <h3><sup style="font-size: 10px"><?php echo app_format_money($total, ' R$ '); ?></sup></h3>
                                  <p><?php echo _l(date('F', mktime(0,0,0,$m,1))); ?></p>
                                </div>
                                <div class="icon">

                                </div>
                               </div>
                            </div>
                            <?php } ?>

                        </div>
                    </div>

                    <hr />

                    <div class="col-md-12">
                        <div class="col-md-8">
                            <p class="text-danger bold">
                            <h2>Total de Despesas e Receitas</h2>
                            </p>
                            <div class="relative" style="max-height:300px;">

                                <canvas class="chart" height="300" id="report-expense-vs-income"></canvas>

                            </div>
                        </div>
                        <div class="col-md-4">
                            <div id="piechart" style="width: 500px; height: 400px;"></div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="col-md-4">
                            <p class="text-danger bold">
                            <h2>Receitas Previsto X Realizado</h2>
                            </p>
                            <div class="relative" style="max-height:300px;">
                                <canvas class="chart" height="300" id="report-income-previsto-vs-realizado"></canvas>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div id="piechart_entradas_previsto_realizado" style="width: 300px; height: 400px;"></div>
                        </div>

                        <div class="col-md-4">
                            <p class="text-danger bold">
                            <h2>Despesas Previsto X Realizado</h2>
                            </p>
                            <div class="relative" style="max-height:300px;">
                                <canvas class="chart" height="300" id="report-expense-previsto-vs-realizado"></canvas>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div id="piechart_saidas_previsto_realizado" style="width: 300px; height: 400px;"></div>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>
</div>

<?php init_tail(); ?>

<!-- GRAFICO PIE  TOTAL DE ENTRADAS X SAÍDAS -->
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
  google.charts.load('current', {'packages':['corechart']});
  google.charts.setOnLoadCallback(drawChart);

  function drawChart() {

    var data = google.visualization.arrayToDataTable([
      ['Task', 'Hours per Day'],
      ['',''],
      ['Saídas',      <?php echo $total_despesas_realizadas; ?>], // vermelho
      ['',''], 
      ['Entradas',     <?php echo $total_entradas_realizadas; ?>] // verde
      
    ]);

    var options = {
      title: 'Receitas x Despesas'
    };

    var chart = new google.visualization.PieChart(document.getElementById('piechart'));

    chart.draw(data, options);
  }
</script>
    
<!-- ENTRADA PREVISTO X REALIZADO PIE-->
<script type="text/javascript">
  google.charts.load('current', {'packages':['corechart']});
  google.charts.setOnLoadCallback(drawChart);

  function drawChart() {

    var data = google.visualization.arrayToDataTable([
      ['Task', 'Hours per Day'],
      ['Previsto',      <?php echo $total_entradas_prevista; ?>],  // azul
      ['',''],
      ['',''],
      ['Realizado',     <?php echo $total_entradas_realizadas; ?>]
       
    ]);

    var options = {
      title: 'Receitas Realizado x Previsto'
    };

    var chart = new google.visualization.PieChart(document.getElementById('piechart_entradas_previsto_realizado'));

    chart.draw(data, options);
  }
</script>

<!-- SAIDA PREVISTO X REALIZADO PIE-->
<script type="text/javascript">
  google.charts.load('current', {'packages':['corechart']});
  google.charts.setOnLoadCallback(drawChart);

  function drawChart() {

    var data = google.visualization.arrayToDataTable([
      ['Task', 'Hours per Day'],
      ['',''],
      ['',''],
      ['Previsto',      <?php echo $total_despesas_previsto; ?>],
      ['Realizado',     <?php echo $total_despesas_realizadas; ?>]
      
    ]);

    var options = {
      title: 'Saída Realizado x Previsto'
    };

    var chart = new google.visualization.PieChart(document.getElementById('piechart_saidas_previsto_realizado'));

    chart.draw(data, options);
  }
</script>

<!-- TOTAL DE ENTRADAS X SAÍDAS -->    
<script>
    $(function(){
        init_currency(<?php echo $base_currency->id; ?>);

        chartExpenseVsIncome = new Chart($('#report-expense-vs-income'),{
            type: 'bar',
            data: <?php echo $chart_expenses_vs_income_values; ?>,
            options:{
                maintainAspectRatio:false,
                 tooltips: {
                    callbacks: {
                        label: function(tooltipItem, data) {
                            return format_money(tooltipItem.yLabel)
                        }
                    }
                },
                scales: {
                yAxes: [{
                  ticks: {
                    callback: function (value) {
                        return format_money(value)
                    },
                    beginAtZero: true,
                }
            }]
        },}
    });
    });
    function change_expense_report_year(year){
        window.location.href = admin_url+'reports/expenses_vs_income/'+year;
    }
</script>

<!-- ENTRADAS PREVISTO X REALIZADO -->    
<script>
    $(function(){
        init_currency(<?php echo $base_currency->id; ?>);

        chartExpenseVsIncome = new Chart($('#report-income-previsto-vs-realizado'),{
            type: 'bar',
            data: <?php echo $chart_income_previsto_vs_realizado_values; ?>,
            options:{
                maintainAspectRatio:false,
                 tooltips: {
                    callbacks: {
                        label: function(tooltipItem, data) {
                            return format_money(tooltipItem.yLabel)
                        }
                    }
                },
                scales: {
                yAxes: [{
                  ticks: {
                    callback: function (value) {
                        return format_money(value)
                    },
                    beginAtZero: true,
                }
            }]
        },}
    });
    });
    function change_expense_report_year(year){
        window.location.href = admin_url+'reports/expenses_vs_income/'+year;
    }
</script>

<!-- SAIDAS PREVISTO X REALIZADO -->    
<script>
    $(function(){
        init_currency(<?php echo $base_currency->id; ?>);

        chartExpenseVsIncome = new Chart($('#report-expense-previsto-vs-realizado'),{
            type: 'bar',
            data: <?php echo $chart_expense_previsto_vs_realizado_values; ?>,
            options:{
                maintainAspectRatio:false,
                 tooltips: {
                    callbacks: {
                        label: function(tooltipItem, data) {
                            return format_money(tooltipItem.yLabel)
                        }
                    }
                },
                scales: {
                yAxes: [{
                  ticks: {
                    callback: function (value) {
                        return format_money(value)
                    },
                    beginAtZero: true,
                }
            }]
        },}
    });
    });
    function change_expense_report_year(year){
        window.location.href = admin_url+'reports/expenses_vs_income/'+year;
    }
</script>
</body>
</html>
