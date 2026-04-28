
<h4 class="customer-profile-group-heading"><?php echo 'Personalização da Tela de Login'; ?></h4>
<div class="clearfix"></div>
<div class="mtop15">
    <div class="row">
        <div class="col-md-12">
            <div class="horizontal-scrollable-tabs">
                <div class="scroller arrow-left"><i class="fa fa-angle-left"></i></div>
                <div class="scroller arrow-right"><i class="fa fa-angle-right"></i></div>
                <div class="horizontal-tabs">
                    <ul class="nav nav-tabs profile-tabs row customer-profile-tabs nav-tabs-horizontal" role="tablist">
                        <li role="presentation" class="active">
                            <a href="#contact_info1" aria-controls="contact_info1" role="tab" data-toggle="tab">
                                Publicados
                            </a>
                        </li>
                        <?php
                        $this->load->model('Intranet_model');
                        $pendentes = $this->Intranet_model->doucmento_pendente_aprovacao();

                        if (is_array($pendentes)) {
                            $quantidade = count($pendentes);
                            if ($quantidade > 0) {
                                ?>
                                <li role="presentation" class="">
                                    <a href="#contact_info2" aria-controls="contact_info2" role="tab" data-toggle="tab">
                                        Em andamento
                                        <span class="badge" style="background: red;"><?php echo count($pendentes); ?></span>
                                    </a>
                                </li>
                                <?php
                            }
                        }
                        ?>
                    </ul>
                </div>
            </div>
            <div class="tab-content mtop15">
                <div role="tabpanel" class="tab-pane active" id="contact_info1">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="_buttons">
                                <a class="btn btn-info pull-left"  href="<?php echo base_url('gestao_corporativa/intra/Documentos/index'); ?>"><?php echo 'Add Documento'; ?></a>   
                                <a href="#" class="btn btn-info pull-left mleft5" data-toggle="modal" data-target="#categorias_modal"><?php echo 'Categorias'; ?></a>
                                <a href="#" class="btn btn-info pull-left mleft5" data-toggle="modal" data-target="#fluxo_modal"><?php echo 'Fluxo'; ?></a>

                            </div>
                            <div class="clearfix"></div>

                            <hr class="hr-panel-heading" />
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
                if ($quantidade > 0) {
                    ?>
                    <div role="tabpane2" class="tab-pane" id="contact_info2">
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
                <?php }?>
                </div>

            </div>
        </div>
    </div>

    <?php init_tail(); ?>
    <script>

        $(function () {

            var CustomersServerParams = {};
            $.each($('._hidden_inputs._filters input'), function () {
                CustomersServerParams[$(this).attr('name')] = '[name="' + $(this).attr('name') + '"]';
            });
            CustomersServerParams['categorias'] = '[name="categorias"]';
            CustomersServerParams['natureza'] = '[name="natureza"]';
            var tAPI = initDataTable('.table-documentos_andamento', '<?php echo base_url(); ?>' + 'gestao_corporativa/intra/intranet/table_documento_andamento', [0], [0], CustomersServerParams, [1, 'desc']);

            //filtraCategoria();



        });

        function procedimentos_table_reload() {
            //alert('aqui');
            var CustomersServerParams = {};
            //  $.each($('._hidden_inputs._filters input'),function(){
            //     CustomersServerParams[$(this).attr('name')] = '[name="'+$(this).attr('name')+'"]';
            // });
            CustomersServerParams['categorias'] = '[name="categorias"]';
            CustomersServerParams['natureza'] = '[name="natureza"]';
            if ($.fn.DataTable.isDataTable('.table-publicacoes')) {
                $('.table-publicacoes').DataTable().destroy();
            }
            var tAPI = initDataTable('.table-publicacoes', '<?php echo base_url(); ?>' + 'gestao_corporativa/intra/intranet/table_documento_andamento', [0], [0], CustomersServerParams, [1, 'desc']);
            // tAPI.ajax.reload();

            // filtraCategoria();
        }

    </script>
    <script>

        $(function () {

            var CustomersServerParams = {};
            $.each($('._hidden_inputs._filters input'), function () {
                CustomersServerParams[$(this).attr('name')] = '[name="' + $(this).attr('name') + '"]';
            });
            CustomersServerParams['categorias'] = '[name="categorias"]';
            CustomersServerParams['natureza'] = '[name="natureza"]';
            var tAPI = initDataTable('.table-documentos', '<?php echo base_url(); ?>' + 'gestao_corporativa/intra/intranet/table_documento', [0], [0], CustomersServerParams, [1, 'desc']);

            //filtraCategoria();



        });

        function procedimentos_table_reload() {
            //alert('aqui');
            var CustomersServerParams = {};
            //  $.each($('._hidden_inputs._filters input'),function(){
            //     CustomersServerParams[$(this).attr('name')] = '[name="'+$(this).attr('name')+'"]';
            // });
            CustomersServerParams['categorias'] = '[name="categorias"]';
            CustomersServerParams['natureza'] = '[name="natureza"]';
            if ($.fn.DataTable.isDataTable('.table-publicacoes')) {
                $('.table-publicacoes').DataTable().destroy();
            }
            var tAPI = initDataTable('.table-publicacoes', '<?php echo base_url(); ?>' + 'gestao_corporativa/intra/intranet/table_documento', [0], [0], CustomersServerParams, [1, 'desc']);
            // tAPI.ajax.reload();

            // filtraCategoria();
        }

    </script>
    <div class="modal fade" id="categorias_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">
                        <?php echo 'Categorias'; ?>
                    </h4>
                </div>
                <div class="modal-body">
                    <a href="#" class="btn btn-info mtop15 mbot10" onclick="slideToggle('.usernote'); return false;">Nova Categoria</a>
                    <div class="usernote hide">
                        <?php echo form_open("gestao_corporativa/intra/Documentos/add_categoria", array("id" => "ci-form", "enctype" => "multipart/form-data", "class" => "general-form", "role" => "form")); ?>
                        <div class="row" style="margin-top: 10px; margin-bottom: 10px;">

                            <div class="col-md-6">
                                <label>Nome</label>
                                <input type="text" name="titulo" id="categoria_name" class="form-control" placeholder="<?php echo 'Categoria'; ?>" required="">
                            </div>
                            <div class="col-md-6">
                                <?php
                                $selected = array();

                                echo render_select('fluxos[]', $fluxos, array('id', 'titulo'), 'fluxos', $selected, array('multiple' => true, 'data-actions-box' => true), array(), '', '', false);
                                ?>
                            </div>

                            <div class="col-md-6"><label>Formato do código:</label>

                                <div class="icheck-primary d-inline">
                                    <input type="checkbox" id="checkboxPrimary1" value="1" name="formato_codigo">
                                    <label for="checkboxPrimary1">
                                        NomeCategoria Nº Sequencial_Setor_Versão_Ano
                                    </label>
                                </div>
                            </div>



                        </div>

                        <div class="row " style="margin-top: 10px;">

                            <div class="col-md-12">
                                <button class="btn btn-info pull-right mbot15">
                                    <?php echo _l('submit'); ?>
                                </button>
                            </div>
                        </div>

                        <?php echo form_close(); ?>

                    </div>

                    <hr />



                    <div class="row">
                        <div class="container-fluid">

                            <table class="table dt-table table-items-groups" data-order-col="1" data-order-type="asc">
                                <thead>
                                    <tr>
                                        <th><?php echo 'Sequência'; ?></th>
                                        <th><?php echo 'Nome da Categoria'; ?></th>
                                        <th><?php echo 'Fluxo de aprovação'; ?></th>
                                        <th><?php echo 'Cabeçalho e Rodapé'; ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($categorias_documento as $categoria) { ?>
                                        <tr class="row-has-options" data-group-row-id="<?php echo $categoria['id']; ?>" id="primeira<?php echo $categoria['id']; ?>">
                                            <td data-order="<?php echo $categoria['id']; ?>"><?php
                                                echo $categoria['id'];
                                                ?></td>
                                            <td data-order="<?php echo $categoria['titulo']; ?>">
                                                <span class="group_name_plain_text" id="sair<?php echo $categoria['id']; ?>"><?php echo $categoria['titulo']; ?></span>
                                                <div class="group_edit hide" id="entrar<?php echo $categoria['id']; ?>">



                                                </div>
                                                <div class="row-options">
                                                    <!--<a id="edit<?php //echo $categoria['id'];       ?>" onClick="addClass('primeira<?php //echo $categoria['id'];       ?>', 'hide'); delClass('segunda<?php // echo $categoria['id'];       ?>', 'hide');" class="edit-item-group">
                                                    <?php //echo _l('edit');   ?>
                                                    </a>-->
                                                    <a href="<?php echo base_url('gestao_corporativa/intra/Documentos/delete_categoria' . '?id=' . $categoria['id']); ?>" class="edit-item-group text-danger">
                                                        <?php echo _l('delete'); ?>
                                                    </a>

                                                </div>

                                            </td>
                                            <td data-order="<?php echo $categoria['id']; ?>"><?php
                                                echo $categoria['fluxos'];
                                                ?></td>
                                            <td>
                                                <a type="button" href="<?php echo base_url('gestao_corporativa/intra/documentos/cabecalho/' . $categoria['id']); ?>" class="btn btn-xs btn-success"  ><i class="fa fa-hand-o-up"></i> Header</a>
                                                <a type="button" href="<?php echo base_url('gestao_corporativa/intra/documentos/rodape/' . $categoria['id']); ?>" class="btn btn-xs btn-info"  ><i class="fa fa-hand-o-down"> Footer</i></a>

                                            </td>
                                        </tr>

                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                </div>
            </div>
        </div>
    </div>
    <script>

        function addClass(id, classe) {
            var elemento = document.getElementById(id);
            var classes = elemento.className.split(' ');
            var getIndex = classes.indexOf(classe);

            if (getIndex === -1) {
                classes.push(classe);
                elemento.className = classes.join(' ');
            }
        }

        function delClass(id, classe) {
            var elemento = document.getElementById(id);
            var classes = elemento.className.split(' ');
            var getIndex = classes.indexOf(classe);

            if (getIndex > -1) {
                classes.splice(getIndex, 1);
            }
            elemento.className = classes.join(' ');
        }

    </script>
    <div class="modal fade" id="fluxo_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">
                        <?php echo 'Fluxo de Aprovação'; ?>
                    </h4>
                </div>
                <div class="modal-body">
                    <?php echo form_open("gestao_corporativa/intra/Documentos/add_fluxo", array("id" => "ci-form", "enctype" => "multipart/form-data", "class" => "general-form", "role" => "form")); ?>
                    <div class="input-group">
                        <input type="text" name="titulo" id="categoria_name" class="form-control" placeholder="<?php echo 'Nome'; ?>" required>
                        <span class="input-group-btn">
                            <button class="btn btn-info p7" type="submit" id="new-categoria-insert"><?php echo 'Add Fluxo'; ?></button>
                        </span>
                    </div>
                    <?php echo form_close(); ?>
                    <hr />



                    <div class="row">
                        <div class="container-fluid">

                            <table class="table dt-table table-items-groups" data-order-col="1" data-order-type="asc">
                                <thead>
                                    <tr>
                                        <th><?php echo _l('id'); ?></th>
                                        <th><?php echo 'Nome da Fase'; ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($documento_fluxos as $group) { ?>
                                        <tr class="row-has-options" data-group-row-id="<?php echo $group['id']; ?>">
                                            <td data-order="<?php echo $group['id']; ?>"><?php
                                                echo $group['id'];
                                                ?></td>
                                            <td data-order="<?php echo $group['titulo']; ?>">
                                                <span class="group_name_plain_text" id="sair1<?php echo $group['id']; ?>"><?php echo $group['titulo']; ?></span>
                                                <div class="group_edit hide" id="entrar1<?php echo $group['id']; ?>">
                                                    <?php echo form_open("gestao_corporativa/intra/Documentos/edit_fluxo", array("id" => " cate-form", "enctype" => "multipart/form-data", "class" => "general-form", "role" => "form")); ?>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" name="titulo" value="<?php echo $group['titulo']; ?>">
                                                        <input type="hidden" class="form-control" name="id" value="<?php echo $group['id']; ?>">
                                                        <span class="input-group-btn">
                                                            <button class="btn btn-info p8 update-item-group" type="submit"><?php echo _l('submit'); ?></button>
                                                        </span>
                                                    </div>
                                                    <?php echo form_close(); ?>
                                                </div>
                                                <div class="row-options">
                                                    <a id="edit1<?php echo $group['id']; ?>" onClick="addClass('sair1<?php echo $group['id']; ?>', 'hide');
                                                            delClass('entrar1<?php echo $group['id']; ?>', 'hide');
                                                            addClass('edit1<?php echo $group['id']; ?>', 'hide');" class="edit-item-group">
                                                       <?php echo _l('edit'); ?>
                                                    </a> |
                                                    <a href="<?php echo base_url('gestao_corporativa/intra/Documentos/delete_fluxo' . '?id=' . $group['id']); ?>" class="edit-item-group text-danger">
                                                        <?php echo _l('delete'); ?>
                                                    </a>

                                                </div>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                </div>
            </div>
        </div>
    </div>
    <?php foreach ($documentos_com_versoes as $doc): ?>
        <div class="modal fade" id="versao<?php echo $doc['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">
                            <span class="edit-title text-white"><?php echo 'Lista de versões:'; ?></span>
                        </h4>
                    </div>


                    <div class="modal-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th style="width: 10px">#</th>
                                    <th>Título</th>
                                    <th>Alteração</th>
                                    <th>Data</th>
                                    <th>n° da versão</th>
                                    <th style="width: 40px">Ver</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($doc['versoes'] as $versao): ?>
                                    <tr>
                                        <td><h3><?php echo $versao['id']; ?></h3></td>
                                        <td><h3><?php echo $versao['titulo']; ?></h3></td>
                                        <td><h3><?php echo $versao['alteracao']; ?></h3></td>
                                        <td>
                                            <h3><?php echo $versao['data_cadastro']; ?></h3>
                                        </td>
                                        <td>
                                            <h3><?php echo $versao['numero_versao']; ?></h3>
                                        </td>
                                        <td><a type="button" href="<?php echo base_url('gestao_corporativa/intra/Documentos/see' . '?id=' . $versao['id']) ?>" class="btn btn-primary btn-block"><i class="fa fa-eye"></i></a></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="visualizacoes<?php echo $doc['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">
                            <span class="edit-title text-white"><?php
                                ECHO $doc['id'];
                                echo 'Visualização/Cientes';
                                ?></span>
                        </h4>
                    </div>

                    <div class="modal-body">
                        <ol class="breadcrumb">
                            <li class=""><strong>CIENTES </strong></li>
                        </ol>

                        <ul class="list-inline">
                            <?php
                            foreach ($doc['destinatarios'] as $destinatario) {
                                if ($destinatario['lido'] == '1') {
                                    ?>
                                    <li class="list-inline-item">
                                        <img alt="Avatar" class="table-avatar" src="<?php echo base_url(); ?>assets/lte/dist/img/avatar.png" data-toggle="tooltip" data-placement="bottom" title="<?php echo $destinatario['firstname'] . ' ' . $destinario['lastname']; ?>">
                                    </li>
                                    <?php
                                }
                            }
                            ?>
                        </ul>

                        <ol class="breadcrumb" style="margin-top: 10px;">
                            <li class=""><strong>NÃO CIENTES </strong></li>
                        </ol>
                        <ul class="list-inline">
                            <ul class="list-inline">
                                <?php
                                foreach ($doc['destinatarios'] as $destinatario) {
                                    if ($destinatario['lido'] == 0) {
                                        ?>
                                        <li class="list-inline-item">
                                            <img alt="Avatar" class="table-avatar" src="<?php echo base_url(); ?>assets/lte/dist/img/avatar.png">
                                        </li>
                                        <?php
                                    }
                                }
                                ?>
                            </ul>
                            </li>
                        </ul>
                    </div>
                    <div class="modal-footer">

                        <a class="btn btn-info pull-right mbot15" href="<?php echo base_url('gestao_corporativa/intra/documentos/destinatarios?id=' . $doc['id']); ?>">
                            EDITAR DESTINATÁRIOS
                        </a>

                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<style>
    .list-inline {
        margin-bottom: 0;
    }
    .list-inline {
        padding-left: 0;
        list-style: none;
    }

    .list-inline-item {
        display: inline-block;
    }

    .list-inline-item:not(:last-child) {
        margin-right: 0.5rem;
    }
    img.table-avatar,
    .table-avatar img {
        border-radius: 50%;
        display: inline;
        width: 5.5rem;
    }
</style>