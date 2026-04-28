<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(false); ?>
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
                    Relatório Financeiro de Saídas
                  </h1>
                  <ol class="breadcrumb">
                     <li><a href="<?php echo admin_url(''); ?>"><i class="fa fa-home"></i> Home </a></li>  
                    <li><a href="<?php echo admin_url('dashboard/menu_financeiro'); ?>"> Financeiro </a></li>
                    <li class="active"><a href="">Relatório de Saídas</a></li>
                  </ol>
                </section>
                <div class="panel_s">
                    <div class="panel-body">
                     
                        <?php if($export_not_supported){ ?>
                        <p class="text-danger">Exporting not support in IE. To export this data please try another browser</p>
                        <?php } ?>
                        <a href="#" onclick="make_expense_pdf_export(); return false;" class="btn btn-default pull-left mright10<?php if($export_not_supported){echo ' disabled';} ?>"><i class="fa fa-file-pdf-o"></i></a>
                        <a download="expenses-report-<?php echo $current_year; ?>.xls" class="btn btn-default pull-left mright10<?php if($export_not_supported){echo ' disabled';} ?>" href="#" onclick="return ExcellentExport.excel(this, 'expenses-report-table', 'Expenses Report <?php echo $current_year; ?>');"><i class="fa fa-file-excel-o"></i></a>
                        <?php if(count($expense_years) > 0 ){ ?>
                        <select class="selectpicker" name="expense_year" onchange="filter_expenses();" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                            <?php foreach($expense_years as $year) { ?>
                            <option value="<?php echo $year['year']; ?>"<?php if($year['year'] == $current_year){echo 'selected';} ?>>
                                <?php echo $year['year']; ?>
                            </option>
                            <?php } ?>
                        </select>
                        <?php } ?>
                        <?php
                        $_currency = $base_currency;
                        if(is_using_multiple_currencies(db_prefix().'expenses')){ ?>
                        <div data-toggle="tooltip" class="pull-left mright5" title="<?php echo _l('report_expenses_base_currency_select_explanation'); ?>">
                            <select class="selectpicker" name="currencies" onchange="filter_expenses();"  data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>" >
                                <?php foreach($currencies as $c) {
                                    $selected = '';
                                    if(!$this->input->get('currency')){
                                        if($c['id'] == $base_currency->id){
                                            $selected = 'selected';
                                            $_currency = $base_currency;
                                        }
                                    } else {
                                        if($this->input->get('currency') == $c['id']){
                                            $selected = 'selected';
                                            $_currency = get_currency($c['id']);
                                        }
                                    }
                                    ?>
                                    <option value="<?php echo $c['id']; ?>" <?php echo $selected; ?>>
                                        <?php echo $c['name']; ?>
                                    </option>
                                    <?php } ?>
                                </select>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="panel_s">
                        <div class="panel-body">
                           
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover expenses-report" id="expenses-report-table">
                                <?php
                                
                                $resumo_total_previsto_mes_Category_plano = array();
                                $resumo_total_realizado_mes_Category_plano = array();
                                $total_categoria = array();
                                $soma_valor_total_mes_categoria = 0;
                                foreach($categorias_despesas_financeira as $category_saida) {
                                    $categoria_id = $category_saida['categoria_id'];
                                    ?>
                                
                                    <thead>
                                        <tr>
                                            <th class="bold" style="width: 10%;"><?php echo $category_saida['categoria']; ?></th>
                                            <?php
                                            for ($m=1; $m<=12; $m++) {
                                                echo '  <th class="bold" colspan="2" ><center>' . _l(date('F', mktime(0,0,0,$m,1))) . '</center></th>';
                                            }
                                            ?>
                                            <th class="bold" colspan="2"> <center><?php echo _l('year'); ?> (<?php echo $current_year; ?></center></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $taxTotal = array();
                                        $saidasTotalPlanoMes = array();
                                        $totalNetByExpenseCategory = array();
                                        $total_previstoNetByExpenseCategory = array();
                                        // plano de contas
                                        
                                        $planos_conta =  $this->Financeiro_model->get_plano_conta_by_categoria($categoria_id);
                                        foreach($planos_conta as $plano) { ?>
                                        <tr>
                                            <td class="bold" style="width: 10%;"><?php echo $plano['descricao']; ?></td>
                                            <?php
                                            $total_expenses = array();
                                            $total_expenses_previsto = array();
                                            
                                            $soma_total_expenses_mes = array();
                                            $soma_total_previsto_expenses_mes = array();
                                            for ($m=1; $m<=12; $m++) {
                                                $acumula_total_expenses_previsto = 0;
                                                $acumula_total_expenses_realizado = 0;
                                            // Set the monthly total expenses array
                                               if(!isset($netMonthlyTotal[$m.$categoria_id])){
                                                    $netMonthlyTotal[$m.$categoria_id] = array();
                                                    $netMonthlyTotal_previsto[$m.$categoria_id] = array();
                                                }
                                                $net_reumoMonthlyTotal_previsto[] = array();
                                               $net_reumoMonthlyTotal[] = array();
                                                // Get lancamentos
                                                $plano_id = $plano['id']; 
                                                $empresa_id = $this->session->userdata('empresa_id');
                                                
                                                // retorna os pgamentos lançados/ previstos
                                                $sql_previsto = "SELECT p.id as id 
                                                        FROM tblfin_lancamentos_parcelas p
                                                        inner join tblfin_lancamentos l on l.id = p.titulo_id
                                                        where l.plano_conta_id = $plano_id
                                                        and MONTH(p.data_vencimento) = $m
                                                        and YEAR(p.data_vencimento) = $current_year
                                                        and p.deleted = 0
                                                        and l.deleted = 0 and p.empresa_id = $empresa_id ";
                                               
                                                $saidas_previsto = $this->db->query($sql_previsto)->result_array();  
                                                echo '<td style="background-color : orange; font-weight: bold;">';
                                                
                                                foreach($saidas_previsto as $saida_p){
                                                    $expense = $this->Financeiro_model->get_parcela($saida_p['id']);
                                                    $total_valor_saida = $expense->valor_parcela;
                                                    $acumula_total_expenses_previsto += $total_valor_saida;
                                                    
                                                    $total_expenses_previsto[] = $total_valor_saida;
                                                }
                                               
                                                
                                                $total_expenses_previsto = array_sum($total_expenses_previsto);
                                                
                                                // total provisto categoria / mes
                                                array_push($soma_total_previsto_expenses_mes[$m][$categoria_id],$acumula_total_expenses_previsto);
                                                // Add to total monthy expenses
                                                array_push($netMonthlyTotal_previsto[$m.$categoria_id],$acumula_total_expenses_previsto);
                                                if(!isset($total_previstoNetByExpenseCategory[$plano['id']])){
                                                    $total_previstoNetByExpenseCategory[$plano['id']] = array();
                                                }
                                                array_push($total_previstoNetByExpenseCategory[$plano['id']],$acumula_total_expenses_previsto);
                                                
                                               
                                                
                                                
                                                 if(count($planos_conta) <= 8){
                                                    echo '<span data-toggle="tooltip" style="font-size: 14px; width: 5%;">'. app_format_money($acumula_total_expenses_previsto, $_currency).'</span>';
                                                } else {
                                                   // show tooltip for the month if more the 8 categories found. becuase when listing down you wont be able to see the month
                                                    echo '<span data-toggle="tooltip" style="font-size: 14px; width: 5%;" title="'._l(date('F', mktime(0,0,0,$m,1))).'">'.app_format_money($acumula_total_expenses_previsto, $_currency) .'</span>';
                                                }
                                                echo '</td>';
                                                
                                                // retorna os lançamentos de pagamentos realizados
                                                $sql = "SELECT p.id as id 
                                                        FROM tblfin_lancamentos_parcelas p
                                                        inner join tblfin_lancamentos l on l.id = p.titulo_id
                                                        INNER JOIN tblfin_categories ON tblfin_categories.id = l.categoria_id
                                                        INNER JOIN tblfin_plano_contas ON tblfin_plano_contas.id = l.plano_conta_id
                                                        INNER JOIN tblfin_fornecedores ON tblfin_fornecedores.id = l.cliente_fornecedor_id
                                                        LEFT JOIN tblfin_bancos ON tblfin_bancos.id = p.banco_id
                                                        INNER JOIN tblfin_centro_custo ON tblfin_centro_custo.id = l.centro_custo_id
                                                        INNER JOIN tblfin_tipo_documento ON tblfin_tipo_documento.id = l.tipo_documento
                                                        LEFT JOIN tblfin_payment_modes ON tblfin_payment_modes.id = p.forma_pagamento
                                                        where l.plano_conta_id = $plano_id
                                                            and p.status = 1
                                                        and MONTH(p.data_pagamento) = $m
                                                        and YEAR(p.data_pagamento) = $current_year
                                                        and p.deleted = 0
                                                        AND tblfin_plano_contas.deleted = 0
                                                        AND tblfin_categories.deleted = 0

                                                        and l.deleted = 0 
                                                        and p.empresa_id = $empresa_id ";
                                                $saidas = $this->db->query($sql)->result_array();  
                                                echo '<td style="background-color : #00FF00;  font-weight: bold">';
                                                foreach($saidas as $saida){
                                                    $expense = $this->Financeiro_model->get_parcela($saida['id']);
                                                    $total = $expense->valor_parcela;
                                                    $acumula_total_expenses_realizado += $total;
                                                    $total_expenses[] = $total;
                                                }
                                                $total_expenses = array_sum($total_expenses);
                                                // total categoria / mes
                                                array_push($soma_total_expenses_mes[$m][$categoria_id],$acumula_total_expenses_realizado);
                                                // Add to total monthy expenses
                                                array_push($netMonthlyTotal[$m.$categoria_id],$acumula_total_expenses_realizado);
                                                
                                                if(!isset($totalNetByExpenseCategory[$plano['id']])){
                                                    $totalNetByExpenseCategory[$plano['id']] = array();
                                                }
                                                array_push($totalNetByExpenseCategory[$plano['id']],$acumula_total_expenses_realizado);
                                                
                                                // Output the total for this category
                                                if(count($planos_conta) <= 8){
                                                    echo '<span data-toggle="tooltip" style="font-size: 14px; width: 5%;">'.app_format_money($acumula_total_expenses_realizado, $_currency).'</span>';
                                                } else {
                                                   // show tooltip for the month if more the 8 categories found. becuase when listing down you wont be able to see the month
                                                    echo '<span data-toggle="tooltip" style="font-size: 14px; width: 5%;" title="'._l(date('F', mktime(0,0,0,$m,1))).'">'.app_format_money($acumula_total_expenses_realizado, $_currency) .'</span>';
                                                }
                                                echo '</td>';
                                                ?>
                                                <?php } // for mes ?>
                                                <td class="bg-odd">
                                                    <?php echo app_format_money(array_sum($total_previstoNetByExpenseCategory[$plano['id']]), $_currency); ?>
                                                </td>
                                                <td class="bg-odd">
                                                    <?php echo app_format_money(array_sum($totalNetByExpenseCategory[$plano['id']]), $_currency); ?>
                                                </td>
                                            </tr>
                                            <?php } // fim for plano de contas
                                            
                                            ?>
                                             
                                            <?php
                                            if(isset($netMonthlyTotal_previsto)) { 
                                                foreach($netMonthlyTotal_previsto as $month => $total){
                                                $total = array_sum($total);
                                                $netMonthlyTotal_previsto[$month] = $total;
                                              
                                               } 
                                            } 
                                            
                                            if(isset($netMonthlyTotal)) { ?>
                                            <?php foreach($netMonthlyTotal as $month => $total){
                                                $total = array_sum($total);
                                                $netMonthlyTotal[$month] = $total;
                                              
                                               } 
                                            } ?>

                                            <?php
                                                $total_previstoNetByExpenseCategorySum = 0;
                                                foreach($total_previstoNetByExpenseCategory as $totalCat) {
                                                    $total_previstoNetByExpenseCategorySum += array_sum($totalCat);
                                                }
                                            
                                                $totalNetByExpenseCategorySum = 0;
                                                foreach($totalNetByExpenseCategory as $totalCat) {
                                                    $totalNetByExpenseCategorySum += array_sum($totalCat);
                                                }
                                            ?>
                                                   
                                            <tr class="bg-odd">
                                                    <td class="bold text-info">
                                                        <?php echo _l('expenses_report_total'); ?>
                                                    </td>
                                                    <?php
                                                    
                                                    for ($m=1; $m<=12; $m++) {

                                                        // add ao resumo previsto
                                                        array_push($net_reumoMonthlyTotal_previsto[$m],$netMonthlyTotal_previsto[$m.$categoria_id]);
                                                        echo '<td class="bold">';
                                                        echo app_format_money($netMonthlyTotal_previsto[$m.$categoria_id], $_currency);
                                                        echo '</td>';

                                                        // add ao resumo realizado
                                                        array_push($net_reumoMonthlyTotal[$m],$netMonthlyTotal[$m.$categoria_id]);
                                                        echo '<td class="bold">';
                                                        echo app_format_money($netMonthlyTotal[$m.$categoria_id], $_currency);
                                                        echo '</td>';
                                                        
                                                         $resumo_total_previsto_mes_Category_plano[$m] += $netMonthlyTotal_previsto[$m.$categoria_id];
                                                        $resumo_total_realizado_mes_Category_plano[$m] += $netMonthlyTotal[$m.$categoria_id];
                                                        
                                                    }
                                                    
                                                    // total previsto por categoria
                                                    echo '<td class="bold bg-odd">';
                                                    echo app_format_money($total_previstoNetByExpenseCategorySum , $_currency);
                                                    echo '</td>';
                                                    
                                                    // total realizado por categoria
                                                    echo '<td class="bold bg-odd">';
                                                    echo app_format_money($totalNetByExpenseCategorySum , $_currency);
                                                    echo '</td>';
                                                    
                                                    ?>
                                                </tr>
                                                
                                                
                                            </tbody>
                                            
                                <?php } // fim categoria ?>
                                        <!-- RESUMO INICIO -->    
                                            <thead>
                                                <tr>
                                                    <th class="bold" style="width: 20%"><?php echo 'RESUMO'; ?></th>
                                                    <?php
                                                    for ($m=1; $m<=12; $m++) {
                                                        echo '  <th class="bold" colspan="2"><center>' . _l(date('F', mktime(0,0,0,$m,1))) . '</center></th>';
                                                    }
                                                    ?>
                                                    <th class="bold" colspan="2"><center> <?php echo _l('year'); ?> (<?php echo $current_year; ?>)</center></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                               
                                                foreach($netMonthlyTotal_previsto as $month => $total){
                                                    $total = array_sum($total);
                                                    $netMonthlyTotal_previsto[$month] = $total;
                                                } 
                                                foreach ($net_reumoMonthlyTotal as $month => $total_resumo) {
                                                    $total_resumo = array_sum($total_resumo);
                                                    $net_reumoMonthlyTotal[$month] = $total_resumo;
                                                } ?>
                                                <?php
                                                $total_previstoNetByExpenseCategorySum = 0;
                                                foreach($total_previstoNetByExpenseCategory as $totalCat1) {
                                                    $total_previstoNetByExpenseCategorySum += array_sum($totalCat1);
                                                }
                                                $totalNetByExpenseCategorySum = 0;
                                                foreach ($totalNetByExpenseCategory as $totalCat) {
                                                    $totalNetByExpenseCategorySum += array_sum($totalCat);
                                                }
                                                // echo app_format_money($totalNetByExpenseCategorySum, $_currency);
                                                ?>
                                                    <tr class="bg-odd">
                                                        <td class="bold text-info" style="width: 20%;">
                                                            <?php echo _l('expenses_report_total'); ?>
                                                        </td>
                                                        <?php
                                                            $soma_previsto_t_resumo = 0;
                                                            $soma_realziado_t_resumo = 0;
                                                            for ($m=1; $m<=12; $m++) {

                                                                // add ao resumo previsto
                                                                echo '<td style="font-size: 14px; width: 5%;" class="bold">';
                                                                echo app_format_money($resumo_total_previsto_mes_Category_plano[$m], $_currency);
                                                                echo '</td>';

                                                                echo '<td style="font-size: 14px; width: 5%;" class="bold">';
                                                                echo app_format_money($resumo_total_realizado_mes_Category_plano[$m], $_currency);
                                                                echo '</td>';
                                                                
                                                                $soma_previsto_t_resumo += $resumo_total_previsto_mes_Category_plano[$m];
                                                                $soma_realziado_t_resumo += $resumo_total_realizado_mes_Category_plano[$m];
                                                            }

                                                            // total previsto
                                                            echo '<td style="font-size: 14px; width: 5%;" class="bold bg-odd">';
                                                            echo app_format_money($soma_previsto_t_resumo , $_currency);
                                                            echo '</td>';

                                                            // total realizado
                                                            echo '<td style="font-size: 14px; width: 5%;" class="bold bg-odd">';
                                                            echo app_format_money($soma_realziado_t_resumo , $_currency);
                                                            echo '</td>';

                                                        ?>
                                                    </tr>            

                                            </tbody>
                                            <!-- RESUMO FIM --> 
                                            
                                </table>
                                
                                  
                                  
                            </div>
                                    <hr />
                                    <div class="row">
                                      <div class="col-md-12">
                                          <p class="text-muted mbot20"><?php echo 'Despesas por categorias'; ?></p>
                                      </div>
                                      
                                      <div class="col-md-12">
                                        <canvas id="expenses_chart_not_billable" height="390"></canvas>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
       
<?php init_tail(); ?>
<script src="<?php echo base_url('assets/plugins/excellentexport/excellentexport.min.js'); ?>"></script>
<script>
    new Chart($('#expenses_chart_not_billable'),{
        type:'bar',
        data: <?php echo $chart_not_billable; ?>,
        options:{
            responsive:true,
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
        }},
    });
    new Chart($('#expenses_chart_billable'),{
        type:'bar',
        data: <?php echo $chart_billable; ?>,
        options:{
            responsive:true,
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
        }},
    });
    function filter_expenses(){
        var parameters = new Array();
        var exclude_billable = ~~$('input[name="exclude_billable"]').prop('checked');
        var year = $('select[name="expense_year"]').val();
        var currency = ~~$('select[name="currencies"]').val();
        var location = window.location.href;
        location = location.split('?');
        if(exclude_billable){
            parameters['exclude_billable'] = exclude_billable;
        }
        parameters['year'] = year;
        parameters['currency'] = currency;
        window.location.href = buildUrl(location[0], parameters);
    }
    function make_expense_pdf_export() {
        var body = [];
        var export_headings = [];
        var export_widths = [];
        var export_data = [];
        var headings = $('#expenses-report-table th');
        var data_tbody = $('#expenses-report-table tbody tr')
        var width = 47;
// Prepare the pdf headings
$.each(headings, function(i) {
var heading = {};
heading.text = stripTags($(this).text().trim());
heading.fillColor = '#444A52';
heading.color = '#fff';
export_headings.push(heading);
if(i == 0){
    export_widths.push(80);
} else {
    export_widths.push(width);
}
});
body.push(export_headings);
// Categories total
$.each(data_tbody, function() {
var row = [];
$.each($(this).find('td'), function() {
    var data = $(this);
    row.push($(data).text());
});
body.push(row);
});


// Pdf definition
var docDefinition = {
pageOrientation: 'landscape',
pageMargins: [12, 12, 12, 12],
"alignment":"center",
content: [
{
    text: '<?php echo _l("expenses_report_for"); ?> <?php echo $current_year; ?>:',
    bold: true,
    fontSize: 25,
    margin: [0, 5]
},
{
    text:'<?php echo get_option("companyname"); ?>',
    margin: [2,5]
},
{
    table: {
        headerRows: 1,
        widths: export_widths,
        body: body
    },
}
],
defaultStyle: {
    alignment: 'justify',
    fontSize: 10,
}
};
// Open the pdf.
pdfMake.createPdf(docDefinition).open();
}

</script>
</body>
</html>
