<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head_intranet_novo(); ?>
<?php $this->load->view('gestao_corporativa/css_background'); ?>
<input name="staff_id" type="hidden" value="<?php echo get_staff_user_id(); ?>">
<div class="content">
    <div class="row">
        <div class="col-md-12">
            <h5 style="font-weight: bold;">INTRANET - CONTROLE DOCUMENTAL CONTÍNUO</h5>
            <div>
                <ol class="breadcrumb" style="background-color: white;">
                    <li><a href="<?= base_url('gestao_corporativa/intranet'); ?>"><i class="fa fa-home"></i> Home </a></li>
                    <li><a href="<?= base_url('gestao_corporativa/Cdc/list_'); ?>"><i class="fa fa-backward"></i> Controle Documental Contínuo </a></li>
                </ol>
            </div>
            <?php foreach ($requests as $request) {
                if ($request['created'] == 0) {
                    $class = 'danger';
                } else {
                    $class = 'success';
                } ?>
                <div class="alert alert-<?php echo $class; ?> alert-dismissible ">
                    <div class="row">
                        <div class="col-md-12 _buttons">
                            <span> <i class="icon fa fa-exclamation-circle"></i> - Versionamento Solicitado: <?php echo $request['description'] ?></span>
                            <?php if ($request['created'] == 0) { ?>
                                <a class="btn btn-xs btn-danger pull-right" href="<?php echo base_url('gestao_corporativa/Cdc/index/' . $request['cdc_from']); ?>"><i class="fa fa-plus"></i>Criar Versão</a>
                            <?php } else { ?>
                                <a class="btn btn-xs btn-success pull-right" href="<?php echo base_url('gestao_corporativa/Cdc/see?id=' . $request['created']); ?>"><i class="fa fa-eye"></i>Acompanhar</a>
                            <?php } ?>

                        </div>
                    </div>

                </div>
            <?php } ?>
        </div>

        <div class="col-md-<?php if (count($responsavel) > 0) {
                                echo '6';
                            } else {
                                echo '12';
                            } ?>">
            <?php if (has_permission_intranet('gestao_documento', '', 'create') || is_admin()) { ?>
                <div class="panel_s mbot10">
                    <div class="panel-heading">
                        <p class="bold no-mbot" style="padding: 0px;">ADICIONAR CDC</p>
                    </div>
                    <div class="panel-body _buttons">

                        <a class="btn btn-info pull-left" href="<?php echo base_url('gestao_corporativa/cdc/index'); ?>"><i class="fa fa-plus"></i> <?php echo 'Novo Documento'; ?></a>


                    </div>
                </div>
            <?php } ?>
            <input type="hidden" value="<?php echo get_staff_user_id(); ?>" name="id_user">
            <div class="panel_s">
                <div class="panel-heading bold">
                    <p class="bold no-mbot" style="padding: 0px;">DOCUMENTOS RECEBIDOS E EM PROCESSO</p>
                </div>

                <div class="panel-body">
                    <div class="horizontal-scrollable-tabs">
                        <div class="scroller arrow-left"><i class="fa fa-angle-left"></i></div>
                        <div class="scroller arrow-right"><i class="fa fa-angle-right"></i></div>
                        <div class="horizontal-tabs">
                            <ul class="nav nav-tabs profile-tabs row customer-profile-tabs nav-tabs-horizontal" role="tablist">
                                <li role="presentation" class="active">
                                    <a href="#contact_info1" aria-controls="contact_info1" role="tab" data-toggle="tab">
                                        RECEBIDOS
                                    </a>
                                </li>

                                <?php
                                $this->load->model('Intranet_model');
                                $pendentes = $this->Intranet_model->doucmento_pendente_aprovacao();
                                ?>
                                <li role="presentation" class="">
                                    <a href="#contact_info2" aria-controls="contact_info2" role="tab" data-toggle="tab">
                                        EM PROCESSO (VINCULADOS A MIM)
                                        <!--<span class="badge" style="background: red;"><?php echo count($pendentes); ?></span>-->
                                    </a>
                                </li>
                                <?php ?>

                            </ul>
                        </div>
                    </div>
                    <div class="tab-content mtop15">

                        <div role="tabpanel" class="tab-pane active" id="contact_info1">
                            <div class="row">
                                <div class="col-md-12">

                                    <?php
                                    $table_data = [];

                                    $table_data = array_merge($table_data, array(
                                        '<strong class="bold">DOCUMENTOS</strong>',
                                        '<strong class="bold">TÍTULO</strong>',
                                        '<strong class="bold">CATEGORIA</strong>',
                                        '<strong class="bold">SETOR</strong>',
                                        '<strong class="bold">DATA CADASTRO</strong>',
                                    ));
                                    render_datatable($table_data, 'cdc');
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div role="tabpane2" class="tab-pane" id="contact_info2">
                            <div class="row">
                                <div class="col-md-12">

                                    <?php
                                    $table_data = [];

                                    $table_data = array_merge($table_data, array(
                                        '<strong class="bold">DOCUMENTOS</strong>',
                                        '<strong class="bold">TÍTULO</strong>',
                                        '<strong class="bold">RESPONSABILIDADE</strong>',
                                        '<strong class="bold">CATEGORIA</strong>',
                                        '<strong class="bold">SETOR</strong>',
                                        '<strong class="bold">DATA CADASTRO</strong>',
                                    ));
                                    render_datatable($table_data, 'cdc_');
                                    ?>
                                </div>
                            </div>
                        </div>



                    </div>

                </div>
            </div>
        </div>
        <div class="col-md-6 <?php if (count($responsavel) == 0) {
                                    echo 'hide';
                                } ?>">

            <input type="hidden" value="<?php echo get_staff_user_id(); ?>" name="id_user">
            <div class="panel_s">
                <div class="panel-heading">
                    <p class="bold no-mbot" style="padding: 0px;">GERECIAMENTO - SETOR RESPONSÁVEL</p>
                </div>

                <div class="panel-body">
                    <div class="horizontal-scrollable-tabs">
                        <div class="scroller arrow-left"><i class="fa fa-angle-left"></i></div>
                        <div class="scroller arrow-right"><i class="fa fa-angle-right"></i></div>
                        <div class="horizontal-tabs">
                            <ul class="nav nav-tabs profile-tabs row customer-profile-tabs nav-tabs-horizontal" role="tablist">

                                <?php
                                $i = 0;
                                foreach ($responsavel as $categoria) {
                                ?>
                                    <li role="presentation" class="<?php if ($i == 0) {
                                                                        echo 'active';
                                                                    } ?>">
                                        <a href="#responsavel<?php echo $categoria['id']; ?>" aria-controls="responsavel<?php echo $categoria['id']; ?>" role="tab" data-toggle="tab">
                                            <?php echo strtoupper($categoria['titulo']); ?>
                                        </a>
                                    </li>
                                <?php
                                    $i++;
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                    <div class="tab-content mtop15">


                        <?php
                        $i = 0;
                        foreach ($responsavel as $categoria) {
                        ?>
                            <div role="responsavel<?php echo $categoria['id']; ?>" class="tab-pane <?php if ($i == 0) {
                                                                                                        echo 'active';
                                                                                                    } ?>" id="responsavel<?php echo $categoria['id']; ?>">
                                <div class="row">
                                    <div class="alert alert-info alert-dismissible">
                                        <p> <i class="icon fa fa-info"></i> - Setor Responsável: <?php echo get_departamento_nome($categoria['responsavel']); ?></p>
                                    </div>
                                    <input type="hidden" id="categoria_id_<?php echo $categoria['id']; ?>" name="categoria_id_<?php echo $categoria['id']; ?>" value="<?php echo $categoria['id']; ?>">
                                    <div class="col-md-12">

                                        <?php
                                        $table_data = [];

                                        $table_data = array_merge($table_data, array(
                                            '<strong class="bold">DOCUMENTOS</strong>',
                                            '<strong class="bold">TÍTULO</strong>',
                                            '<strong class="bold">SETOR</strong>',
                                            '<strong class="bold">DATA CADASTRO</strong>'
                                        ));
                                        render_datatable($table_data, 'cdc__' . $categoria["id"]);
                                        ?>
                                    </div>
                                </div>
                            </div>
                        <?php $i++;
                        }
                        ?>


                    </div>

                </div>
            </div>
        </div>
    </div>
</div>



<?php init_tail(); ?>



<div id="modal_wrapper"></div>
<script>
    $(function() {
        var CustomersServerParams = {};
        var tAPI1 = initDataTable('.table-cdc', '<?php echo base_url(); ?>' + 'gestao_corporativa/Cdc/table', [0], [0], CustomersServerParams, <?php echo hooks()->apply_filters('customers_table_default_order', json_encode(array(1, 'asc'))); ?>);

        var CustomersServerParams = {};
        CustomersServerParams['loading'] = '[name="id_user"]';
        var tAPI2 = initDataTable('.table-cdc_', '<?php echo base_url(); ?>' + 'gestao_corporativa/Cdc/table', [0], [0], CustomersServerParams, <?php echo hooks()->apply_filters('customers_table_default_order', json_encode(array(1, 'asc'))); ?>);


        <?php foreach ($responsavel as $categoria) { ?>

            var CustomersServerParams = {};
            CustomersServerParams['categoria_id'] = '[name="categoria_id_<?php echo $categoria['id']; ?>"]';
            var tAPI = initDataTable('.table-cdc__<?php echo $categoria['id']; ?>', '<?php echo base_url(); ?>' + 'gestao_corporativa/Cdc/table', [0], [0], CustomersServerParams, <?php echo hooks()->apply_filters('customers_table_default_order', json_encode(array(1, 'asc'))); ?>);

        <?php } ?>
    });





    function revisar_pessoa(cdc_id) {


        $("#modal_wrapper").load("<?php echo base_url(); ?>" + 'gestao_corporativa/Cdc/retorna_pessoas_fluxo', {
            slug: 'revisar_pessoa',
            id: cdc_id
        }, function() {
            if ($('.modal-backdrop.fade').hasClass('in')) {
                $('.modal-backdrop.fade').remove();
            }
            if ($('#modalRevisarPessoa').is(':hidden')) {
                $('#modalRevisarPessoa').modal({
                    show: true
                });
            }
        });

       /* $.ajax({
            url: '<?php echo base_url(); ?>' + 'gestao_corporativa/Cdc/retorna_pessoas_fluxo', // rota para sua função PHP
            type: 'POST', // ou GET, se preferir
            data: {
                id: cdc_id
            },
            dataType: 'json', // espera retorno em JSON
            beforeSend: function() {
                // Aqui você pode exibir um loader, se quiser
                console.log('Carregando dados...');
            },
            success: function(resposta) {
                if (resposta.status === 'ok') {
                    // Preencher campos do modal
                    $('#campo_nome').text(resposta.dados.nome);
                    $('#campo_email').text(resposta.dados.email);
                    $('#campo_telefone').text(resposta.dados.telefone);
                    // ... preencha outros campos aqui

                    // Abrir modal depois de carregar os dados
                    $('#modalRevisarPessoa').modal('show');
                } else {
                    alert('Não foi possível carregar os dados.');
                }
            },
            error: function(xhr, status, error) {
                console.error(error);
                alert('Erro na requisição AJAX.');
            }
        });*/

    }

     $(document).ready(function() {
        init_selectpicker();
    });
</script>
</body>

</html>