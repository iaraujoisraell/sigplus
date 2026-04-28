<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head_intranet_novo(); ?>
<style>
    body {
        font-family:'Open Sans';
        background:#f1f1f1;
    }
    h3 {
        margin-top: 7px;
        font-size: 18px;
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
            <div>
                <ol class="breadcrumb" style="background-color: white;">
                    <li><a href="<?= base_url('gestao_corporativa/intranet'); ?>"><i class="fa fa-home"></i> Home </a></li> 
                    <li><a href="<?= base_url('gestao_corporativa/Intranet/documentos'); ?>"><i class="fa fa-backward"></i> Documentos </a></li>
                </ol>
            </div>
        </div>
        <div class="col-md-12">
            <div class="panel_s">

                <div class="panel-body">
                    <div class="_buttons">
                              <?php if (has_permission_intranet('gestao_documento', '', 'create') || is_admin()) {?>
                                <a class="btn btn-info pull-left"  href="<?php echo base_url('gestao_corporativa/intra/Documentos/index'); ?>"><?php echo 'Add Documento'; ?></a>   
                                <?php }?>
                                <?php if (has_permission_intranet('gestao_documento', '', 'view_categoria') || is_admin()) {?>
                                <a href="#" class="btn btn-info pull-left mleft5" data-toggle="modal" data-target="#categorias_modal"><?php echo 'Categorias'; ?></a>
                                <?php }?>
                                <?php if (has_permission_intranet('gestao_documento', '', 'view_atuante_fluxo') || is_admin()) {?>
                                <a href="#" class="btn btn-info pull-left mleft5" data-toggle="modal" data-target="#fluxo_modal"><?php echo 'Fluxo'; ?></a>
                                <?php }?>

                            </div>
                            <div class="clearfix"></div>

                            <hr class="hr-panel-heading" />
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
                                foreach ($responsavel as $categoria){
                                ?>
                                <li role="presentation" class="">
                                    <a href="#responsavel<?php echo $categoria['id'];?>" aria-controls="responsavel<?php echo $categoria['id'];?>" role="tab" data-toggle="tab">
                                        <?php echo strtoupper($categoria['titulo']);?>
                                    </a>
                                </li>
                                <?php
                            }
                            }
                        
                        ?>
                                <?php
                        $this->load->model('Intranet_model');
                        $pendentes = $this->Intranet_model->doucmento_pendente_aprovacao();

                        if (is_array($pendentes)) {
                            $quantidade = count($pendentes);
                            if ($quantidade > 0) {
                                ?>
                                <li role="presentation" class="" >
                                    <a href="#contact_info2" aria-controls="contact_info2" role="tab" data-toggle="tab">
                                        EM ANDAMENTO
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
                <?php foreach ($responsavel as $categoria){?>
                <div role="responsavel<?php echo $categoria['id'];?>" class="tab-pane" id="responsavel<?php echo $categoria['id'];?>">
                        <div class="row">
                            <input type="hidden" id="categoria_id_<?php echo $categoria['id'];?>" name="categoria_id_<?php echo $categoria['id'];?>" value="<?php echo $categoria['id'];?>">
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
                                render_datatable($table_data, 'documentos_'.$categoria["id"]);
                                ?>
                            </div>
                        </div>
                    </div>
                <?php }?>
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
        <?php foreach ($responsavel as $categoria){?>
        $(function () {
            var CustomersServerParams = {};
            CustomersServerParams['categoria_id'] = '[name="categoria_id_<?php echo $categoria['id'];?>"]';
            initDataTable('.table-documentos_<?php echo $categoria["id"];?>', '<?php echo base_url(); ?>' + 'gestao_corporativa/intra/intranet/table_documento', [0], [0], CustomersServerParams, [1, 'desc']);
        });
        <?php } ?>
        $(function () {
            var CustomersServerParams = {};
            var tAPI = initDataTable('.table-documentos', '<?php echo base_url(); ?>' + 'gestao_corporativa/intra/intranet/table_documento', [0], [0], CustomersServerParams, [1, 'desc']);
        });

       

    </script>
    <?php if (has_permission_intranet('gestao_documento', '', 'view_categoria') || is_admin()) {?>
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
                    <?php if (has_permission_intranet('gestao_documento', '', 'create_categoria') || is_admin()) {?>
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

                                echo render_select('fluxos[]', $fluxos, array('id', 'titulo'), 'Fluxos', $selected, array('multiple' => true, 'data-actions-box' => true), array(), '', '', false);
                                ?>
                            </div>
                            <div class="col-md-6">
                                <?php
                                $selected = array();

                                echo render_select('responsavel', $staffs, array('staffid', 'firstname', 'lastname'), 'Responsável', $selected, array('data-actions-box' => true));
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
                    <?php }?>

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
                                                    <?php if (has_permission_intranet('gestao_documento', '', 'delete_categoria') || is_admin()) {?>
                                                    <a href="<?php echo base_url('gestao_corporativa/intra/Documentos/delete_categoria' . '?id=' . $categoria['id']); ?>" class="edit-item-group text-danger">
                                                        <?php echo _l('delete'); ?>
                                                    </a>
                                                    <?php }?>

                                                </div>

                                            </td>
                                            <td data-order="<?php echo $categoria['id']; ?>"><?php
                                                echo $categoria['fluxos'];
                                                ?></td>
                                            <td>
                                                <?php if (has_permission_intranet('gestao_documento', '', 'edit_categoria') || is_admin()) {?>
                                                <a type="button" href="<?php echo base_url('gestao_corporativa/intra/documentos/cabecalho/' . $categoria['id']); ?>" class="btn btn-xs btn-success"  ><i class="fa fa-hand-o-up"></i> Header</a>
                                                <a type="button" href="<?php echo base_url('gestao_corporativa/intra/documentos/rodape/' . $categoria['id']); ?>" class="btn btn-xs btn-info"  ><i class="fa fa-hand-o-down"> Footer</i></a>
                                                <?php }?>
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
    <?php }?>
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
    <?php if (has_permission_intranet('gestao_documento', '', 'view_atuante_fluxo') || is_admin()) {?>
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
                    <?php if (has_permission_intranet('gestao_documento', '', 'create_atuante_fluxo') || is_admin()) {?>
                    <?php echo form_open("gestao_corporativa/intra/Documentos/add_fluxo", array("id" => "ci-form", "enctype" => "multipart/form-data", "class" => "general-form", "role" => "form")); ?>
                    <div class="input-group">
                        <input type="text" name="titulo" id="categoria_name" class="form-control" placeholder="<?php echo 'Nome'; ?>" required>
                        <span class="input-group-btn">
                            <button class="btn btn-info p7" type="submit" id="new-categoria-insert"><?php echo 'Add Fluxo'; ?></button>
                        </span>
                    </div>
                    <?php echo form_close(); ?>
                    <hr />
                    <?php }?>



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
                                                    <?php if (has_permission_intranet('gestao_documento', '', 'edit_atuante_fluxo') || is_admin()) {?>
                                                    <a id="edit1<?php echo $group['id']; ?>" onClick="addClass('sair1<?php echo $group['id']; ?>', 'hide');
                                                            delClass('entrar1<?php echo $group['id']; ?>', 'hide');
                                                            addClass('edit1<?php echo $group['id']; ?>', 'hide');" class="edit-item-group">
                                                       <?php echo _l('edit'); ?>
                                                    </a> |
                                                    <?php }?>
                                                    <?php if (has_permission_intranet('gestao_documento', '', 'delete_atuante_fluxo') || is_admin()) {?>
                                                    <a href="<?php echo base_url('gestao_corporativa/intra/Documentos/delete_fluxo' . '?id=' . $group['id']); ?>" class="edit-item-group text-danger">
                                                        <?php echo _l('delete'); ?>
                                                    </a>
                                                    <?php }?>

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
    <?php }?>

</body>
</html>
