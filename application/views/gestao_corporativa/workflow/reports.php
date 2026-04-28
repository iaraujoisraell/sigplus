<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head_intranet_novo(); ?>
<?php $this->load->view('gestao_corporativa/css_background'); ?>
<div class="content">
    <div class="row">
        <div class="col-md-12">
            <h5 style="font-weight: bold;">INTRANET - WORKFLOW RELATÓRIOS</h5>
            <div>
                <ol class="breadcrumb" style="background-color: white;">
                    <li><a href="<?= base_url('gestao_corporativa/intranet'); ?>"><i class="fa fa-home"></i> Home </a></li> 
                    <li><a href="<?= base_url('gestao_corporativa/Workflow'); ?>"><i class="fa fa-backward"></i> Workflow </a></li>
                    <li><a href="<?= base_url('gestao_corporativa/Workflow/reports'); ?>"><i class="fa fa-backward"></i> Relatários </a></li>
                </ol>
            </div>
        </div>
        <div class="col-md-12">

            <div class="panel_s">
                <div class="panel-heading">
                    Gerador de relatórios                   
                </div>
                <div class="panel-body">
                    <?php echo form_open($this->uri->uri_string(), array('id' => 'report_form')); ?>
                    <div class="row">

                        <div class="col-md-12">
                            <div class="row"  id="filters">
                                <div class="col-md-3">
                                    <select id="categoria_id" name="categoria_id" class="selectpicker" required="true" data-width="100%" data-none-selected-text="CATEGORIA" data-live-search="true" onchange="atualizarBotoes();">
                                        <option value=""></option><!-- comment -->
                                        <?php foreach ($categorias as $cat) { ?>
                                            <option value="<?php echo $cat['id']; ?>"><?php echo $cat['titulo']; ?></option>
                                        <?php } ?>
                                    </select>

                                </div>

                                <div class="col-md-2">
                                    <select id="status" name="status[]" class="selectpicker" required="true" data-width="100%" data-none-selected-text="STATUS" data-live-search="true" onchange="atualizarBotoes();" multiple>
                                        <option value=""></option><!-- comment -->
                                        <?php foreach ($statuses as $sts) { ?>
                                            <option value="<?php echo $sts['id']; ?>"><?php echo $sts['label']; ?></option>
                                        <?php } ?>
                                    </select>

                                </div>
                                <div class="col-md-2">
                                    <select id="departmentid" name="departmentid" class="selectpicker"  data-width="100%" data-none-selected-text="CADASTRO(SETOR)" data-live-search="true" onchange="Change_deps(this.value);">
                                        <option value=""></option><!-- comment -->
                                        <option value="0">Via Portal</option>
                                        <?php foreach ($departments as $dep) { ?>
                                            <option value="<?php echo $dep['departmentid']; ?>"><?php echo $dep['name']; ?></option>
                                        <?php } ?>

                                    </select>

                                </div>
                                <div class="col-md-2">
                                    <select id="staffid" name="staffid" class="selectpicker"  data-width="100%" data-none-selected-text="CADASTRO(COLABORADOR)" data-live-search="true" disabled>
                                        <option value=""></option><!-- comment -->

                                    </select>

                                </div>
                                <div class="col-md-3">
                                    <div class="col-md-6">
                                        <div class="input-group date">
                                            <input type="text" class="form-control datepicker" name="start" id="start" placeholder="DE" onchange="atualizarBotoes();" onkeyup="atualizarBotoes();">
                                            <div class="input-group-addon">
                                                <i class="fa fa-calendar calendar-icon"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="input-group date">
                                            <input type="text" class="form-control datepicker"  name="end" id="end" placeholder="ATÉ" onchange="atualizarBotoes();" onkeyup="atualizarBotoes();">
                                            <div class="input-group-addon">
                                                <i class="fa fa-calendar calendar-icon"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="filters_" class="col-md-12">

                                </div>

                            </div>


                        </div>
                        <div class=" col-md-12 mtop10" style="text-align: center;">
                            <div id="button-container" class="col-md-12" >
                                <button class="btn btn-success" disabled id="button1" type="submit">
                                    GERAR RELATÓRIO
                                </button>

                            </div>
                            <!--<div class="col-md-6">
                                <button onclick="report_detail()" class="btn btn-warning" disabled id="button2" disabled>Relatório Detalhado</button>
                            </div>-->










                        </div>
                        <div class="col-md-12" style="margin-top: 20px; text-align: center;  display: none;  " id="spinner">
                            <div  class="spinner" style="margin-left: auto; margin-right: auto;"></div>
                        </div>
                    </div>
                    <?php echo form_close(); ?>
                </div>
            </div>

            <div id="trocar">
            </div>



            <div class="panel_s leads-overview">
                <div class="panel-body">
                    <div class="col-md-12">
                        <h4 class="no-margin">RESUMO DE WORKFLOW'S POR DEPARTAMENTO</h4>
                        <br>
                    </div>

                    <?php
                    foreach ($departments_workflow as $dep) {
                        //print_r($dep);
                        ?>
                        <div class="col-md-2 col-xs-6 border-right">
                            <h3 class="bold"><?php echo $dep['total']; ?></h3>
                            <span style="color:<?php echo $dep['color']; ?>" class=""><?php echo $dep['name']; ?></span>
                        </div>
                    <?php } ?>


                </div>
            </div>


            <div class="panel_s monthly-overview">

                <div class="panel-heading-bg">
                    ESTATÍSTICAS GERAIS               </div>
                <div class="panel-body" >

                    <div class="col-md-6" >
                        <canvas class="monthly chart" id="monthly" height="200"></canvas>
                    </div>
                    <div class="col-md-6 row" >
                        <div class="col-md-12" >
                            <canvas  id="rrr" height="200"></canvas>
                        </div>
                        <div class="col-md-12" >
                            <canvas class="anual chart" id="anual" height="200"></canvas>
                        </div>

                    </div>


                </div>
            </div>
            <div class="panel_s  cat-overview" >
                <div class="panel-heading-bg">
                    RESUMO DE WORKFLOW'S POR CATEGORIA                  
                </div>
                <div class="panel-body">
                    <canvas  height="200" id="leads-sources-report"></canvas>
                </div>
            </div>

        </div>
    </div>
</div>

<?php init_tail(); ?>
<script src="<?php echo base_url('assets/plugins/excellentexport/excellentexport.min.js'); ?>"></script>
<style>
    .spinner {
        border: 4px solid rgba(255, 255, 255, 0.3);
        border-radius: 50%;
        border-top: 4px solid #007bff;
        width: 30px;
        height: 30px;
        animation: spin 2s linear infinite;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }
        100% {
            transform: rotate(360deg);
        }
    }

</style>
<script>
                                                $(document).ready(function () {
                                                $('#report_form').submit(function (event) {
                                                // Previne o comportamento padrão de envio do formulário
                                                event.preventDefault();
                                                $('#trocar').html("");
                                                var spinner = document.getElementById("spinner");
                                                // Mostra o spinner
                                                spinner.style.display = "block";
                                                // Habilita o botão novamente
                                                document.getElementById("button1").disabled = true;
                                                // Serializa os dados do formulário
                                                var formData = $(this).serialize();
                                                // Envia os dados via AJAX
                                                var request = $.ajax({
                                                type: 'POST',
                                                        url: '<?php echo base_url('gestao_corporativa/workflow/report'); ?>',
                                                        data: formData,
                                                        timeout: 300000,
                                                        success: function (response) {
                                                        // Callback para sucesso
                                                        $('#trocar').html(response);
                                                        spinner.style.display = "none";
                                                        document.getElementById("button1").disabled = false;
                                                        // Adicione aqui qualquer manipulação de DOM ou feedback ao usuário
                                                        },
                                                        error: function (xhr, status, error) {
                                                        alert_float("danger", "Falha ao gerar: " + status + ";" + error + '. TENTE NOVAMENTE MAIS TARDE!');
                                                        spinner.style.display = "none";
                                                        document.getElementById("button1").disabled = false;
                                                        }
                                                });
                                                request.fail(function (jqXHR, textStatus) {
                                                if (textStatus === 'timeout') {
                                                alert_float("danger", "Tempo limite de requisição atingido. Por favor, tente novamente mais tarde.");
                                                }
                                                spinner.style.display = "none";
                                                document.getElementById("button1").disabled = false;
                                                });
                                                });
                                                });</script>
<script>
    $(document).ready(function () {
    init_selectpicker();
    });
    function report_general() {
    $('#trocar').html("");
    var spinner = document.getElementById("spinner");
    // Mostra o spinner
    spinner.style.display = "block";
    // Habilita o botão novamente
    document.getElementById("button1").disabled = true;
    var select = document.getElementById("categoria_id");
    var categoria_id = select.options[select.selectedIndex].value;
    var select = document.getElementById("current");
    var current = select.options[select.selectedIndex].value;
    var start = document.getElementById("start").value;
    var end = document.getElementById("end").value;
    var selectElement = document.getElementById("campos_cat");
// Obtenha os valores selecionados
    var campos = [];
    for (var i = 0; i < selectElement.options.length; i++) {
    if (selectElement.options[i].selected) {
    campos.push(selectElement.options[i].value);
    }
    }

    var selectElement = document.getElementById("status");
// Obtenha os valores selecionados
    var status = [];
    for (var i = 0; i < selectElement.options.length; i++) {
    if (selectElement.options[i].selected) {
    status.push(selectElement.options[i].value);
    }
    }


    var selects = document.querySelectorAll('select[name="campos"]');
    // Inicializa um array para armazenar os valores selecionados
    var campos_ = [];
    // Itera sobre os selects e obtém as opções selecionadas de cada um
    selects.forEach(function (select) {
    for (var i = 0; i < select.options.length; i++) {
    if (select.options[i].selected) {
    campos_.push(select.options[i].value);
    }
    }
    });
    var selectElement = document.getElementById("campos_system");
// Obtenha os valores selecionados
    var campos_system = [];
    for (var i = 0; i < selectElement.options.length; i++) {
    if (selectElement.options[i].selected) {
    campos_system.push(selectElement.options[i].value);
    }
    }




    $.ajax({
    type: "POST",
            url: "<?php echo base_url('gestao_corporativa/workflow/report'); ?>",
            data: {
            type: 'general', categoria_id: categoria_id, start: start, end: end, campos_cat: campos, status: status, campos: campos_, campos_system: campos_system, current: current
            },
            success: function (data) {
            $('#trocar').html(data);
            spinner.style.display = "none";
            document.getElementById("button1").disabled = false;
            },
            error: function (xhr, textStatus, errorThrown) {
            alert_float("danger", "AJAX request failed:" + textStatus + ";" + errorThrown);
            }
    });
    }
</script>
<script>



    // Obtenha os elementos HTML dos inputs e botões
    const categoria_id = document.getElementById("categoria_id");
    const status = document.getElementById("status");
    const date_start = document.getElementById("start");
    const date_end = document.getElementById("end");
    const button1 = document.getElementById("button1");
// Função para verificar se todos os inputs estão preenchidos

// Função para habilitar ou desabilitar os botões com base no estado dos inputs
    function atualizarBotoes() {
    if (categoria_id.value.trim() !== "" && status.value.trim() !== "" && date_start.value.trim() !== "" && date_end.value.trim() !== "") {
    button1.removeAttribute("disabled");
    } else {
    button1.setAttribute("disabled", "true");
    }
    }


    function filter_expenses() {
    var parameters = new Array();
    var exclude_billable = ~~$('input[name="exclude_billable"]').prop('checked');
    var year = $('select[name="expense_year"]').val();
    var currency = ~~$('select[name="currencies"]').val();
    var location = window.location.href;
    location = location.split('?');
    if (exclude_billable) {
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
    $.each(headings, function (i) {
    var heading = {};
    heading.text = stripTags($(this).text().trim());
    heading.fillColor = '#444A52';
    heading.color = '#fff';
    export_headings.push(heading);
    if (i == 0) {
    export_widths.push(80);
    } else {
    export_widths.push(width);
    }
    });
    body.push(export_headings);
// Categories total
    $.each(data_tbody, function () {
    var row = [];
    $.each($(this).find('td'), function () {
    var data = $(this);
    row.push($(data).text());
    });
    body.push(row);
    });
// Pdf definition
    var docDefinition = {
    pageOrientation: 'landscape',
            pageMargins: [12, 12, 12, 12],
            "alignment": "center",
            content: [
            {
            text: '<?php echo _l("expenses_report_for"); ?> <?php echo $current_year; ?>:',
                    bold: true,
                    fontSize: 25,
                    margin: [0, 5]
            },
            {
            text: '<?php echo get_option("companyname"); ?>',
                    margin: [2, 5]
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
<script>
    $(document).ready(function () {
    // Evento de mudança no select de categoria
    $("#categoria_id").change(function () {
    $('#filters_').html("");
    var categoriaSelecionada = $(this).val();
    $.ajax({
    type: "POST",
            url: "<?php echo base_url('gestao_corporativa/Categorias_campos/get_campos_in_options_filter'); ?>",
            data: {
            categoria_id: categoriaSelecionada
            },
            success: function (data) {
            $("#filters_").html(data);
            }
    });
    });
    });
    function Change_deps(value) {
    var select = document.getElementById('staffid'); // Selecionando o elemento select pelo ID
    if (value != 0) {

    $.ajax({
    type: "POST",
            url: "<?php echo base_url('admin/Departments/get_department_staffs/'); ?>" + value,
            data: {},
            success: function (data) {
            // Defina o atributo "disabled" como false para habilitar o campo
            $("#staffid").prop("disabled", false);
            select.innerHTML = '';
            var option = document.createElement('option');
            select.add(option);
            var staffs = JSON.parse(data); // Convertendo a string JSON em objeto JavaScript
            staffs.forEach(function (staff) { // Iterando sobre os funcionários e criando options
            var option = document.createElement('option');
            option.text = staff.firstname + ' ' + staff.lastname; // Supondo que o nome completo do funcionário seja armazenado em 'full_name'
            option.value = staff.staffid; // Supondo que o ID do funcionário seja armazenado em 'staffid'
            select.add(option); // Adicionando a opção ao select
            });
            // Atualize o seletor de escolha após habilitar o campo
            $('#staffid').selectpicker('refresh');
            }
    });
    } else {
    // Defina o atributo "disabled" como true para desabilitar o campo
    $("#staffid").prop("disabled", true);
    select.innerHTML = ''; // Limpando qualquer conteúdo anterior
    // Atualize o seletor de escolha após desabilitar o campo
    $('#staffid').selectpicker('refresh');
    }
    }


</script>
<script>



    new Chart($('#leads-sources-report'), {
    type: 'bar',
            data:{
            "labels":[
<?php
for ($i = 0; $i < count($categorias_workflow); $i++) {
    ?>
                "<?php echo $categorias_workflow[$i]['titulo'] ?>",
    <?php
}
?>
            ],
                    "datasets":[{"label":"Worflow's",
                            "backgroundColor":"rgba(67, 127, 180, 0.7)",
                            "borderColor":"#7cb342",
                            "data":[
<?php for ($i = 0; $i < count($categorias_workflow); $i++) { ?>


    <?php echo $categorias_workflow[$i]['total']; ?>,
<?php }
?>
                            ]}]},
            options: {
            responsive: true,
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
            },
    });
    var chart_data = {"labels":["Segunda-feira", "Ter\u00e7a-feira", "Quinta-feira", "Quarta-feira", "Sexta-feira", "S\u00e1bado", "Domingo"],
            "datasets":[{"label":"Worwflow's", "backgroundColor":"rgba(197, 61, 169, 0.5)", "borderColor":"#c53da9", "borderWidth":1,
                    "tension":false, "data":[
<?php echo $semana[0]; ?>,
<?php echo $semana[1]; ?>,
<?php echo $semana[2]; ?>,
<?php echo $semana[3]; ?>,
<?php echo $semana[4]; ?>,
<?php echo $semana[5]; ?>,
<?php echo $semana[6]; ?>
                    ]}]};
    // Weekly ticket openings statistics
    new Chart($('#rrr'), {
    type:'line',
            data:chart_data,
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
                    }
            }
    });
    new Chart($('#monthly'), {
    'type': 'bar',
            data:{
            "labels":[
<?php foreach ($mes['dias'] as $dia) { ?>
                "<?php echo $dia; ?>",
<?php } ?>



            ],
                    "datasets":[{"label":"Worflow's",
                            "backgroundColor":"rgba(124, 179, 66, 0.5)",
                            "borderColor":"#7cb342",
                            "data":[
<?php foreach ($mes['totals'] as $total) { ?>
    <?php echo $total; ?>,
<?php } ?>



                            ]}]},
            options: {
            responsive: true,
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
            },
    });
    new Chart($('#anual'), {
    type: 'bar',
            data: {"labels":["Janeiro", "Fevereiro", "Mar\u00e7o", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro"],
                    "datasets":[
                    {"label":"Workflow's interno", "backgroundColor":"#1acfe5", "borderColor":"#1acfe5", "borderWidth":1, "tension":false,
                            "data":[
<?php foreach ($meses['interno'] as $interno) { ?>
    <?php echo $interno; ?>,
<?php } ?>
                            ]},
                    {"label":"Workflow's  portal", "backgroundColor":"#e3b813", "borderColor":"#e3b813", "borderWidth":1, "tension":false,
                            "data":[
<?php foreach ($meses['portal'] as $portal) { ?>
    <?php echo $portal; ?>,
<?php } ?>
                            ]}]},
            options:{
            maintainAspectRatio:false,
                    scales: {
                    yAxes: [{
                    ticks: {

                    beginAtZero: true,
                    }
                    }]
                    }, }
    });



</script>
</body>
</html>
