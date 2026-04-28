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
                    Relatório Financeiro de Entradas
                  </h1>
                  <ol class="breadcrumb">
                    <li><a href="<?php echo admin_url(''); ?>"><i class="fa fa-home"></i> Home </a></li>  
                    <li><a href="<?php echo admin_url('dashboard/menu_financeiro'); ?>"> Financeiro </a></li>
                    <li class="active"><a href="">Relatório de Entradas</a></li>
                  </ol>
                </section>
                <div class="panel_s">
                    <div class="panel-body">
                     
                        <?php if($export_not_supported){ ?>
                        <p class="text-danger">Exporting not support in IE. To export this data please try another browser</p>
                        <?php } ?>
                        <a href="#" onclick="make_expense_pdf_export(); return false;" class="btn btn-default pull-left mright10<?php if($export_not_supported){echo ' disabled';} ?>"><i class="fa fa-file-pdf-o"></i></a>
                        <a download="expenses-report-<?php echo $current_year; ?>.xls" class="btn btn-default pull-left mright10<?php if($export_not_supported){echo ' disabled';} ?>" href="#" onclick="return ExcellentExport.excel(this, 'expenses-report-table', 'Expenses Report <?php echo $current_year; ?>');"><i class="fa fa-file-excel-o"></i></a>
                        <?php if(count($invoices_years) > 0 ){ ?>
                        <select class="selectpicker" name="expense_year" onchange="filter_expenses();" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                            <?php foreach($invoices_years as $year) { ?>
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
                                
                               
                                $total_categoria = array();
                                $soma_valor_total_mes_categoria = 0;
                                foreach($categorias_entradas_financeira as $category_saida) {
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
                                        
                                        $planos_conta =  $this->Financeiro_model->get_plano_conta_entradas_by_categoria($categoria_id);
                                        foreach($planos_conta as $plano) { ?>
                                        <tr>
                                            <td class="bold" style="width: 10%;"><?php echo $plano['descricao']; ?></td>
                                            <?php
                                            $total_expenses = array();
                                            $total_expenses_previsto = array();
                                            $soma_total_expenses_mes = array();
                                            $soma_total_previsto_expenses_mes = array();
                                            for ($m=1; $m<=12; $m++) {
                                            /*
                                             * PREVISTO
                                             */    
                                            // Set the monthly total expenses array
                                               if(!isset($netMonthlyTotal[$m.$categoria_id])){
                                                    $netMonthlyTotal[$m.$categoria_id] = array();
                                                    $netMonthlyTotal_previsto[$m.$categoria_id] = array();
                                                }
                                                $net_reumoMonthlyTotal_previsto[] = array();
                                                $net_reumoMonthlyTotal[] = array();
                                                $net_previsto_reumoMonthlyTotal[] = array();
                                                // Get lancamentos
                                                $plano_id = $plano['id']; 
                                                $empresa_id = $this->session->userdata('empresa_id');
                                                
                                                // retorna os pgamentos lançados/ previstos
                                                $sql_previsto = "SELECT i.id as id 
                                                        FROM tblfin_invoices i
                                                        where i.plano_conta_id = $plano_id
                                                        and MONTH(i.duedate) = $m
                                                        and YEAR(i.duedate) = $current_year
                                                        and i.deleted = 0 and i.empresa_id = $empresa_id ";
                                                $saidas_previsto = $this->db->query($sql_previsto)->result_array();  
                                                echo '<td style="background-color : orange; font-weight: bold;">';
                                                foreach($saidas_previsto as $saida_p){
                                                    $expense = $this->Financeiro_model->get_invoices($saida_p['id']);
                                                    $total_valor = $expense->total;
                                                    $total_expenses_previsto[] = $total_valor;
                                                }
                                                $total_expenses_previsto = array_sum($total_expenses_previsto);
                                                // total provisto categoria / mes
                                                array_push($soma_total_previsto_expenses_mes[$m][$categoria_id],$total_expenses_previsto);
                                                // Add to total monthy expenses
                                                array_push($netMonthlyTotal_previsto[$m.$categoria_id],$total_expenses_previsto);
                                                if(!isset($total_previstoNetByExpenseCategory[$plano['id']])){
                                                    $total_previstoNetByExpenseCategory[$plano['id']] = array();
                                                }
                                                array_push($total_previstoNetByExpenseCategory[$plano['id']],$total_expenses_previsto);
                                                
                                                 if(count($planos_conta) <= 8){
                                                    echo '<span data-toggle="tooltip" style="font-size: 14px; width: 5%;">'. app_format_money($total_expenses_previsto, $_currency).'</span>';
                                                } else {
                                                   // show tooltip for the month if more the 8 categories found. becuase when listing down you wont be able to see the month
                                                    echo '<span data-toggle="tooltip" style="font-size: 14px; width: 5%;" title="'._l(date('F', mktime(0,0,0,$m,1))).'">'.app_format_money($total_expenses_previsto, $_currency) .'</span>';
                                                }
                                                echo '</td>';
                                                /*
                                                 * FIM PREVISTO
                                                 * 
                                                 * 
                                                 * INICIO REALIZADO
                                                 */  
                                                // retorna os lançamentos de pagamentos realizados
                                                $sql = "SELECT i.id as id 
                                                        FROM tblfin_invoicepaymentrecords r
                                                        inner join tblfin_invoices i on i.id = r.invoiceid
                                                        where i.plano_conta_id = $plano_id
                                                          
                                                        and MONTH(i.duedate) = $m
                                                        and YEAR(i.duedate) = $current_year
                                                        and i.deleted = 0 and r.deleted = 0 and i.empresa_id = $empresa_id ";
                                                $saidas = $this->db->query($sql)->result_array();  
                                                echo '<td style="background-color : #00FF00;  font-weight: bold">';
                                                foreach($saidas as $saida){
                                                    $expense = $this->Financeiro_model->get_invoices($saida['id']);
                                                    $total = $expense->total;
                                                    $total_expenses[] = $total;
                                                }
                                                $total_expenses = array_sum($total_expenses);
                                                // total categoria / mes
                                                array_push($soma_total_expenses_mes[$m][$categoria_id],$total_expenses);
                                                // Add to total monthy expenses
                                                array_push($netMonthlyTotal[$m.$categoria_id],$total_expenses);
                                                
                                                if(!isset($totalNetByExpenseCategory[$plano['id']])){
                                                    $totalNetByExpenseCategory[$plano['id']] = array();
                                                }
                                                array_push($totalNetByExpenseCategory[$plano['id']],$total_expenses);
                                                
                                                // Output the total for this category
                                                if(count($planos_conta) <= 8){
                                                    echo '<span data-toggle="tooltip" style="font-size: 14px; width: 5%;">'.app_format_money($total_expenses, $_currency).'</span>';
                                                } else {
                                                   // show tooltip for the month if more the 8 categories found. becuase when listing down you wont be able to see the month
                                                    echo '<span data-toggle="tooltip" style="font-size: 14px; width: 5%;" title="'._l(date('F', mktime(0,0,0,$m,1))).'">'.app_format_money($total_expenses, $_currency) .'</span>';
                                                }
                                                echo '</td>';
                                                
                                                ?>
                                                <?php } // TOTAL for mes ?>
                                                <td class="bg-odd">
                                                    <?php echo app_format_money(array_sum($total_previstoNetByExpenseCategory[$plano['id']]), $_currency); ?>
                                                </td>
                                                <td class="bg-odd">
                                                    <?php echo app_format_money(array_sum($totalNetByExpenseCategory[$plano['id']]), $_currency); ?>
                                                </td>
                                            </tr>
                                            <?php } // fim for plano de contas
                                            /*
                                                 * FIM REALIZADO
                                                 */
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
                                </table>
                                
                                 <!-- RESUMO INICIO -->
                                <table class="table table-bordered table-hover expenses-report" id="expenses-report-table">
                                    <thead>
                                        <tr>
                                            <th class="bold" style="width: 10%"><?php echo 'RESUMO'; ?></th>
                                            <?php
                                            for ($m=1; $m<=12; $m++) {
                                                echo '  <th class="bold" colspan="2">' . _l(date('F', mktime(0,0,0,$m,1))) . '</th>';
                                            }
                                            ?>
                                            <th class="bold" colspan="2"> <?php echo _l('year'); ?> (<?php echo $current_year; ?>)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                            
                                            <?php
                                            foreach($net_previsto_reumoMonthlyTotal as $month => $total){
                                                $total = array_sum($total);
                                                $net_previsto_reumoMonthlyTotal[$month] = $total;
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
                                                <td class="bold text-info" style="width: 10%;">
                                                    <?php echo _l('expenses_report_total'); ?>
                                                </td>
                                                <?php
                                                
                                                    for ($m=1; $m<=12; $m++) {
                                                        
                                                        // add ao resumo previsto
                                                        echo '<td style="width: 5%;" class="bold">';
                                                        echo app_format_money($netMonthlyTotal_previsto[$m.$categoria_id], $_currency);
                                                        echo '</td>';
                                                        
                                                        echo '<td style="width: 5%;" class="bold">';
                                                        echo app_format_money($net_reumoMonthlyTotal[$m], $_currency);
                                                        echo '</td>';
                                                    }
                                                    
                                                    // total previsto
                                                    echo '<td style="width: 5%;" class="bold bg-odd">';
                                                    echo app_format_money($total_previstoNetByExpenseCategorySum , $_currency);
                                                    echo '</td>';
                                                    
                                                    // total realizado
                                                    echo '<td style="width: 5%;" class="bold bg-odd">';
                                                    echo app_format_money($totalNetByExpenseCategorySum , $_currency);
                                                    echo '</td>';
                                                
                                                ?>
                                            </tr>            
                                                
                                    </tbody>
                                </table>    
                                <!-- RESUMO FIM -->   
                            </div>
                                    
                            </div>
                        </div>
                    </div>
                </div>
            </div>
       
<?php init_tail(); ?>
<script src="<?php echo base_url('assets/plugins/excellentexport/excellentexport.min.js'); ?>"></script>
<script>
    
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
