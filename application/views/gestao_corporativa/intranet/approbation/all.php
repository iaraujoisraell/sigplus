<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head_intranet_novo(false); ?>
<?php $this->load->view('gestao_corporativa/css_background'); ?>
<div class="content">

    <div class="row">
        
        <div class="col-md-12">

            <h5 style="font-weight: bold;">INTRANET - APROVAÇÕES</h5>
            <div>
                <ol class="breadcrumb" style="background-color: white;">
                    <li><a href="<?= base_url('gestao_corporativa/intranet'); ?>"><i class="fa fa-home"></i> Home </a></li> 
                    <li class="active"><a href="#"><i class="fa fa-hand-paper-o"></i> Aprovações </a></li>
                </ol>

        </div>
            </div>
        <div class="col-md-12">
            <div class="alert alert-info alert-dismissible">
                <h5><i class="icon fa fa-info-circle"></i> CLIQUE NO PROCESSO PARA APROVAR/REPROVAR SOLICITAÇÕES!</h5>
                <p><strong style="font-weight: bold;">Usuário:</strong> <?php echo get_staff_full_name(); ?></p>
                <p><strong style="font-weight: bold;">Aprovador dos seguintes setores: <?php foreach($departments as $dep) { echo $dep['name'].', '; }?></strong> </p>

            </div>
        </div>
        <div class="col-md-12" id="diminuir">
            <div class="panel_s">
                <div class="panel-body">
                    <?php
                    $table_data = [];

                    $table_data = array_merge($table_data, array(
                        'ID',
                        'Referente a',
                        'Cadastro',
                        'Status'
                    ));
                    render_datatable($table_data, 'approbation');
                    ?>
                </div>
            </div>
        </div>
        <div class="container-fluid col-md-6" style="display: none;" id="space">

        </div>
    </div>
</div>
<?php init_tail(); ?>
<script>
    $(function () {
        var CustomersServerParams = {};
        initDataTable('.table-approbation', '<?php echo base_url(); ?>' + 'gestao_corporativa/Approbation/table');
    });

    function reload() {

        if ($.fn.DataTable.isDataTable('.table-approbation')) {
            $('.table-approbation').DataTable().destroy();
        }
        var CustomersServerParams = {};
        initDataTable('.table-approbation', '<?php echo base_url(); ?>' + 'gestao_corporativa/Approbation/table');
    }

    function escolha(approbation_id, slug, id) {

        $("#space").load("<?php echo base_url(); ?>gestao_corporativa/Approbation/mudar_space", {
            slug: slug,
            id: id,
            approbation_id: approbation_id
        }, function () {
            mudar_coluna_tamanho();
        });
    }

    function mudar_coluna_tamanho() {
        var element = document.getElementById("diminuir");
        element.classList.add("col-md-6");
        element.classList.remove("col-md-12");
        document.getElementById('space').style.display = 'block';
    }

    function fechar() {
        var element = document.getElementById("diminuir");
        element.classList.add("col-md-12");
        element.classList.remove("col-md-6");
        document.getElementById('space').style.display = 'none';
    }
</script>
</body>
</html>
