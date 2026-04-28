
<h4 class="customer-profile-group-heading"><?php echo 'Documentos - Configurações'; ?></h4>

<div class="mtop15">
    <div class="horizontal-scrollable-tabs">
        <div class="scroller arrow-left"><i class="fa fa-angle-left"></i></div>
        <div class="scroller arrow-right"><i class="fa fa-angle-right"></i></div>
        <div class="horizontal-tabs">
            <ul class="nav nav-tabs profile-tabs row customer-profile-tabs nav-tabs-horizontal" role="tablist">
                <li role="presentation" class="active">
                    <a href="#categorias" aria-controls="categorias" role="tab" data-toggle="tab">
                        Categorias
                    </a>
                </li>
                <li role="presentation" class="">
                    <a href="#atuantes" aria-controls="atuantes" role="tab" data-toggle="tab">
                        Fases
                        <span class="badge" style="background: red;"><?php //echo count($pendentes);        ?></span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <div class="tab-content mtop15">
        <div role="tabpanel" class="tab-pane active" id="categorias">

                <a href="#" class="btn btn-info" onclick="slideToggle('.usernote'); return false;">Nova Categoria</a>
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

            



            <div class="row">
                <hr />
                <div class="col-md-12">
                    

                    <table class="table dt-table table-items-groups" data-order-col="1" data-order-type="asc">
                        <thead>
                            <tr>
                                <th><?php echo 'ID'; ?></th>
                                <th><?php echo 'Nome da Categoria'; ?></th>
                                <th><?php echo 'Fluxo de aprovação'; ?></th>
                                <th><?php echo 'Cabeçalho e Rodapé'; ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($categorias_documento as $categoria) { ?>
                                <tr class="row-has-options" data-group-row-id="<?php echo $categoria['id']; ?>" id="primeira<?php echo $categoria['id']; ?>">
                                    <td data-order="<?php echo $categoria['id']; ?>">#<?php
                                        echo $categoria['id'];
                                        ?></td>
                                    <td data-order="<?php echo $categoria['titulo']; ?>">
                                        <span class="group_name_plain_text" id="sair<?php echo $categoria['id']; ?>"><?php echo $categoria['titulo']; ?></span>
                                        <div class="group_edit hide" id="entrar<?php echo $categoria['id']; ?>">



                                        </div>
                                        <div class="row-options">
                                            <!--<a id="edit<?php //echo $categoria['id'];          ?>" onClick="addClass('primeira<?php //echo $categoria['id'];          ?>', 'hide'); delClass('segunda<?php // echo $categoria['id'];          ?>', 'hide');" class="edit-item-group">
                                            <?php //echo _l('edit');    ?>
                                            </a>
                                            <?php //if (has_permission_intranet('gestao_documento', '', 'delete_categoria') || is_admin()) { ?>
                                                <a href="<?php echo base_url('gestao_corporativa/intra/Documentos/delete_categoria' . '?id=' . $categoria['id']); ?>" class="edit-item-group text-danger">
                                                    <?php echo _l('delete'); ?>
                                                </a>
                                            <?php //} ?>-->

                                        </div>

                                    </td>
                                    <td data-order="<?php echo $categoria['id']; ?>"><?php
                                        echo $categoria['fluxos'];
                                        ?></td>
                                    <td>
                                        <?php //if (has_permission_intranet('gestao_documento', '', 'edit_categoria') || is_admin()) { ?>
                                            <a type="button" href="<?php echo base_url('gestao_corporativa/intra/documentos/cabecalho/' . $categoria['id']); ?>" class="btn btn-xs btn-success"  ><i class="fa fa-hand-o-up"></i> Header</a>
                                            <a type="button" href="<?php echo base_url('gestao_corporativa/intra/documentos/rodape/' . $categoria['id']); ?>" class="btn btn-xs btn-info"  ><i class="fa fa-hand-o-down"> Footer</i></a>
                                        <?php //} ?>
                                    </td>
                                </tr>

                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>    
        </div>
        <div role="tabpane2" class="tab-pane" id="atuantes">
                    <?php //if (has_permission_intranet('gestao_documento', '', 'create_atuante_fluxo') || is_admin()) { ?>
                        <?php echo form_open("gestao_corporativa/intra/Documentos/add_fluxo", array("id" => "ci-form", "enctype" => "multipart/form-data", "class" => "general-form", "role" => "form")); ?>
                        <div class="input-group">
                            <input type="text" name="titulo" id="categoria_name" class="form-control" placeholder="<?php echo 'Nome'; ?>" required>
                            <span class="input-group-btn">
                                <button class="btn btn-info p7" type="submit" id="new-categoria-insert"><?php echo 'Nova Fase'; ?></button>
                            </span>
                        </div>
                        <?php echo form_close(); ?>
                        <hr />
                    <?php //} ?>



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
                                                    <?php //if (has_permission_intranet('gestao_documento', '', 'edit_atuante_fluxo') || is_admin()) { ?>
                                                        <a id="edit1<?php echo $group['id']; ?>" onClick="addClass('sair1<?php echo $group['id']; ?>', 'hide');
                                                                delClass('entrar1<?php echo $group['id']; ?>', 'hide');
                                                                addClass('edit1<?php echo $group['id']; ?>', 'hide');" class="edit-item-group">
                                                           <?php echo _l('edit'); ?>
                                                        </a> |
                                                    <?php //} ?>
                                                    <?php //if (has_permission_intranet('gestao_documento', '', 'delete_atuante_fluxo') || is_admin()) { ?>
                                                        <a href="<?php echo base_url('gestao_corporativa/intra/Documentos/delete_fluxo' . '?id=' . $group['id']); ?>" class="edit-item-group text-danger">
                                                            <?php echo _l('delete'); ?>
                                                        </a>
                                                    <?php //} ?>

                                                </div>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
        </div>
    </div>

</div>
<div id="modal_wrapper"></div>




<?php init_tail(); ?>
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


