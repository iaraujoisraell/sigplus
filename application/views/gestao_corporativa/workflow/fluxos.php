<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head_intranet_novo(); ?>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/lte/plugins/select2/css/select2.min.css">
<?php $this->load->view('gestao_corporativa/css_background'); ?>
<style>
   .conector {
    color: blue;
    font-weight: bold;
    margin: 0 5px;
}
</style>
<div class="content">
    <div class="row">
        <div class="col-md-12">
            <div>
                <ol class="breadcrumb" style="background-color: white;">
                    <li><a href="<?= base_url('gestao_corporativa/intranet'); ?>"><i class="fa fa-home"></i> Home </a></li> 
                    <li><a href="<?= base_url('gestao_corporativa/intranet_admin/index/?group=workflow_settings'); ?>"><i class="fa fa-backward"></i> Workflow - Configurações </a></li>
                    <li> <?php echo $tipo->titulo; ?> </li>
                    <li>Fluxos</li>
                </ol>
            </div>
        </div>
        <div class="col-md-12">
            <div class="alert alert-info alert-dismissible">
                <h5><i class="icon fa fa-info-circle"></i> CATEGORIA: <?php echo strtoupper($tipo->titulo); ?></h5>
                <p>Sequência de fluxos com prazos e objetivos.</p>


            </div>

        </div>
        <div class="clearfix"></div>
        <div class="col-lg-4 col-xs-12 col-md-12 total-column">
            <div class="panel_s">
                <div class="panel-body">
                    <h3 class="text-muted _total">
                        <?php echo $tipo->prazo; ?> DIAS           </h3>
                    <span class="text-info">PRAZO MÁXIMO</span>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-xs-12 col-md-12 total-column">
            <div class="panel_s">
                <div class="panel-body">
                    <h3 class="text-muted _total">
                        <?php echo get_departamento_nome($tipo->responsavel); ?></h3>
                    <span class="text-info">SETOR RESPONSÁVEL</span>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-xs-12 col-md-12 total-column">
            <div class="panel_s">
                <div class="panel-body">
                    <h3 class="text-muted _total">
                        <?php echo $tipo->prazo_cliente; ?> DIAS           </h3>
                    <span class="text-info">PRAZO MÁXIMO PARA O CLIENTE</span>
                </div>
            </div>
        </div>

        <div class="col-md-12" id="panel">
            <div class="panel_s" >

                <div class="panel-body">

                    <div class="row">
                        <div class="col-md-12">
                            <input type="hidden" id="categoria_id" value="<?php echo $tipo->id; ?>" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="container-fluid" id="trocar_table">

                            <?php
                            $table_data = [];

                            $table_data = array_merge($table_data, array(
                                'Ordem',
                                'Setor',
                                'Prazo',
                                'Objetivo',
                                'Prazo Corrido',
                                'Opções',
                            ));
                            render_datatable($table_data, 'fluxos');
                            ?>



                        </div>
                        <div class="container-fluid col-md-6" style="display: none;" id="space">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php init_tail(); ?>

<script>
    function conectarElementos() {
    var elementos = document.getElementsByClassName("label-2");

alert(elementos.length);
    // Iterar sobre os elementos e conectar visualmente
    for (var i = 0; i < elementos.length; i++) {
        if (i < elementos.length - 1) {
            elementos[i].innerHTML += " <span class='conector'>-></span> ";
        }
    }
}
</script>


<script>
    $(document).ready(function () {
        init_selectpicker();

    });

    $(function () {
        
        reload_fluxos();
        
    });

    function delete_fluxo(id) {
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('gestao_corporativa/Workflow/delete_fluxo'); ?>",
            data: {
                id: id, categoria_id: '<?php echo $tipo->id ?>'
            },
            success: function (data) {
                reload_fluxos('');
            }
        });
    }

    function reload_fluxos(curto) {

        if ($.fn.DataTable.isDataTable('.table-fluxos')) {
            $('.table-fluxos').DataTable().destroy();
        }
        var CustomersServerParams = {};
        //if (curto != '') {
        //CustomersServerParams['curto'] = '[id="categoria_id"]';
        //}
        CustomersServerParams['categoria_id'] = '[id="categoria_id"]';
        var tAPI = initDataTable('.table-fluxos', '<?php echo base_url(); ?>' + 'gestao_corporativa/Workflow/table_fluxos', [4], [4], CustomersServerParams, [0, 'asc'], -1);
        
        
    }


    function add_fluxo() {

        var div = document.getElementById("btn_salvar");
        div.innerHTML = 'Carregando...';
        document.getElementById("btn_salvar").disabled = true;

        var prazo = document.querySelector("#prazo");
        var prazo = prazo.value;
        var objetivo = document.querySelector("#objetivo");
        var objetivo = objetivo.value;

        var select = document.getElementById('setor');
        var setor = select.options[select.selectedIndex].value;
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('gestao_corporativa/Workflow/save_fluxo'); ?>",
            data: {
                prazo: prazo, objetivo: objetivo, setor: setor, categoria_id: '<?php echo $tipo->id ?>'
            },
            success: function (data) {
                reload_fluxos();
                slideToggle('.usernote<?php echo $tipo->id; ?>');
                $('#form').html(data);
                div.innerHTML = 'Salvar';
                document.getElementById("btn_salvar").disabled = false;

            }
        });
    }

    function mudar_coluna_tamanho() {

        //$('#trocar_table').html('<?php $this->load->view('gestao_corporativa/workflow/retorno_categoria_table', $data); ?>');
        //var CustomersServerParams = {};
        //CustomersServerParams['categoria_id'] = '[id="categoria_id"]';
        //CustomersServerParams['curto'] = '[id="categoria_id"]';
        //initDataTable('.table-fluxos', '<?php echo base_url(); ?>' + 'gestao_corporativa/Workflow/table_fluxos', [0], [0], CustomersServerParams, [1, 'desc']);
        var element = document.getElementById("trocar_table");
        element.classList.add("col-md-6");
        document.getElementById('space').style.display = 'block';

        scrollToElement("panel");
    }

    function fechar() {
        var element = document.getElementById("trocar_table");
        element.classList.add("col-md-12");
        element.classList.remove("col-md-6");
        document.getElementById('space').style.display = 'none';
    }

    function question(fluxo_id) {

        $("#space").load("<?php echo base_url(); ?>gestao_corporativa/Workflow/mudar_space", {
            slug: 'question',
            id: fluxo_id
        }, function () {
            mudar_coluna_tamanho();
        });
    }
    function edit(fluxo_id) {

        $("#space").load("<?php echo base_url(); ?>gestao_corporativa/Workflow/mudar_space", {
            slug: 'edit',
            id: fluxo_id
        }, function () {
            mudar_coluna_tamanho();
        });
    }
    function campos_personalizados(fluxo_id) {

        $("#space").load("<?php echo base_url(); ?>gestao_corporativa/Workflow/mudar_space", {
            slug: 'campos',
            id: fluxo_id
        }, function () {
            mudar_coluna_tamanho();
        });
    }
    function scrollToElement(elementId) {
        var element = document.getElementById(elementId);
        if (element) {
            window.scrollTo({
                top: element.offsetTop,
                behavior: "smooth" // Adiciona um efeito de rolagem suave
            });
        }
    }




</script>
</body>
</html>
