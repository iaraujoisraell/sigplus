<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head_intranet_novo(); ?>
<?php $this->load->view('gestao_corporativa/css_background'); ?>
<input name="staff_id" type="hidden" value="<?php echo get_staff_user_id(); ?>">
<div class="content">
    <div class="row">
        <div class="col-md-12">
            <h5 style="font-weight: bold;">INTRANET - GESTÃO/ELABORAÇÃO DE DOCUMENTOS</h5>
            <div>
                <ol class="breadcrumb" style="background-color: white;">
                    <li><a href="<?= base_url('gestao_corporativa/intranet'); ?>"><i class="fa fa-home"></i> Home </a></li> 
                    <li><a href="<?= base_url('gestao_corporativa/Documentos/list_'); ?>"><i class="fa fa-backward"></i> Gestão/Elaboração de Documentos </a></li>
                </ol>
            </div>
        </div>
        <div class="col-md-12">
            <?php if (has_permission_intranet('gestao_documento', '', 'create') || is_admin()) { ?>
                <div class="panel_s mbot10">
                    <div class="panel-body _buttons">

                        <a class="btn btn-info pull-left"  href="<?php echo base_url('gestao_corporativa/intra/Documentos/index'); ?>"><i class="fa fa-plus"></i> <?php echo 'Novo Documento'; ?></a>   


                    </div>
                </div>
            <?php } ?>
            <input type="hidden" value="<?php echo get_staff_user_id(); ?>" name="id_user">
            <div class="panel_s">

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
                                $quantidade = count($responsavel);
                                if ($quantidade > 0) {
                                    foreach ($responsavel as $categoria) {
                                        ?>
                                        <li role="presentation" class="">
                                            <a href="#responsavel<?php echo $categoria['id']; ?>" aria-controls="responsavel<?php echo $categoria['id']; ?>" role="tab" data-toggle="tab">
                                                <?php echo strtoupper($categoria['titulo']); ?>
                                            </a>
                                        </li>
                                        <?php
                                    }
                                }
                                ?>
                                <?php
                                $this->load->model('Intranet_model');
                                $pendentes = $this->Intranet_model->doucmento_pendente_aprovacao();
                                ?>
                                <li role="presentation" class="" >
                                    <a href="#contact_info2" aria-controls="contact_info2" role="tab" data-toggle="tab">
                                        EM ANDAMENTO
                                        <span class="badge" style="background: red;"><?php echo count($pendentes); ?></span>
                                    </a>
                                </li>
<?php
?>
                            </ul>
                        </div>
                    </div><div class="tab-content mtop15">

                        <div role="tabpanel" class="tab-pane active" id="contact_info1">
                            <div class="row">
                                <div class="col-md-12">

                                        <?php
                                        $table_data = [];

                                        $table_data[] = '<span class="hide"> - </span><label>#</label>';

                                        $table_data = array_merge($table_data, array(
                                            'Código',
                                            'Título',
                                            'Publicação',
                                            'Categoria',
                                            'Setor',
                                            'Versão Atual',
                                            'Opções'
                                                ));
                                        render_datatable($table_data, 'documentos');
                                        ?>
                                </div>
                            </div>
                        </div>
<?php
//if ($quantidade > 0) {
    ?>
                            <div role="tabpane2" class="tab-pane active" id="contact_info2">
                                <div class="row">
                                    <div class="col-md-12">

                                        <?php
                                        $table_data = [];

                                        $table_data[] = '<span class="hide"> - </span><label>#</label>';

                                        $table_data = array_merge($table_data, array(
                                            'Código',
                                            'Categoria',
                                            'Versão',
                                            'Situação',
                                            'Recebido em'
                                        ));
                                        render_datatable($table_data, 'documentos_andamento');
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <?php //} ?>
                            <?php foreach ($responsavel as $categoria) { ?>
                            <div role="responsavel<?php echo $categoria['id']; ?>" class="tab-pane" id="responsavel<?php echo $categoria['id']; ?>">
                                <div class="row">
                                    <input type="hidden" id="categoria_id_<?php echo $categoria['id']; ?>" name="categoria_id_<?php echo $categoria['id']; ?>" value="<?php echo $categoria['id']; ?>">
                                    <div class="col-md-12">

                                    <?php
                                    $table_data = [];

                                    $table_data[] = '<span class="hide"> - </span><label>#</label>';

                                    $table_data = array_merge($table_data, array(
                                        'Código',
                                        'Título',
                                        'Publicação',
                                        'Categoria',
                                        'Setor',
                                        'Versão Atual',
                                        'Opções'
                                    ));
                                    render_datatable($table_data, 'documentos_' . $categoria["id"]);
                                    ?>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<?php init_tail(); ?>



<div id="modal_wrapper"></div>
<script>

    $(function () {
        var CustomersServerParams = {};
        initDataTable('.table-documentos_andamento', '<?php echo base_url(); ?>' + 'gestao_corporativa/intra/intranet/table_documento_andamento', [0], [0], CustomersServerParams, [1, 'desc']);
    });
<?php foreach ($responsavel as $categoria) { ?>
        $(function () {
            var CustomersServerParams = {};
            CustomersServerParams['categoria_id'] = '[name="categoria_id_<?php echo $categoria['id']; ?>"]';
            initDataTable('.table-documentos_<?php echo $categoria["id"]; ?>', '<?php echo base_url(); ?>' + 'gestao_corporativa/intra/intranet/table_documento', [0], [0], CustomersServerParams, [1, 'desc']);
        });
<?php } ?>
    $(function () {
        var CustomersServerParams = {};
        var tAPI = initDataTable('.table-documentos', '<?php echo base_url(); ?>' + 'gestao_corporativa/intra/intranet/table_documento', [0], [0], CustomersServerParams, [1, 'desc']);
    });



</script>
</body>
</html>
