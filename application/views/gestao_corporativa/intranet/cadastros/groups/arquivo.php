<h4 class="customer-profile-group-heading"><?php echo 'Arquivos'; ?></h4>

<div class="clearfix"></div>
<div class="mtop15">
    <div class="row">
        <div class="col-md-12">
            <?php hooks()->do_action('before_items_page_content'); ?>
            <?php if (has_permission('items', '', 'create') || is_admin()) { ?>
                <div class="_buttons">
                    <a href="<?php echo base_url('gestao_corporativa/intra/Arquivos'); ?>" class="btn btn-info pull-left" ><?php echo 'Add Arquivo'; ?></a>    

                    <a href="#" class="btn btn-info pull-left mleft5" onclick="mudar('tipos');"><?php echo 'Tipos'; ?></a>
                </div>

                <div class="clearfix"></div>
                <div class="panel_s" style="margin-top: 10px; display: none;" id="tipos">
                    <div class="panel-body">
                        <div class="_buttons">
                            <a data-toggle="modal" data-target="#add_tipo"" class="btn btn-info pull-left" ><?php echo 'Add Tipo de arquivo'; ?></a>    

                        </div>

                        <div class="clearfix"></div>
                        <hr class="hr-panel-heading" />
                        <?php
                        $table_data = [];

                        $table_data = array_merge($table_data, array(
                            'Título',
                            'Data de Cadastro',
                            'Campos',
                        ));
                        render_datatable($table_data, 'arquivo_tipos');
                        ?>
                    </div>
                </div>
                <hr class="hr-panel-heading" />
            <?php } ?>



            <?php
            $table_data = [];

            $table_data = array_merge($table_data, array(
                'Título',
                'Data',
                'Arquivo'
            ));
            render_datatable($table_data, 'arquivos');
            ?>
        </div>
    </div>
</div>


<?php init_tail(); ?>
<script>

    $(function () {

        var CustomersServerParams = {};
        var tAPI = initDataTable('.table-arquivos', '<?php echo base_url(); ?>' + 'gestao_corporativa/intra/intranet/table_arquivo', [0], [0], CustomersServerParams, [1, 'desc']);

        //filtraCategoria();



    });


    $(function () {

        var CustomersServerParams = {};
        var tAPI = initDataTable('.table-arquivo_tipos', '<?php echo base_url(); ?>' + 'gestao_corporativa/intra/intranet/table_arquivo_tipos', [0], [0], CustomersServerParams, [1, 'desc']);

        //filtraCategoria();



    });


</script>
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
<script>
    function mudar(el) {
        var display = document.getElementById(el).style.display;
        if (display == "none")
            document.getElementById(el).style.display = 'block';
        else
            document.getElementById(el).style.display = 'none';
    }
</script>
<div class="modal fade" id="add_tipo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">
                    <span class="edit-title text-white"><?php echo 'Cadastrar Tipo'; ?></span>
                </h4>
            </div>



            <?php echo form_open("gestao_corporativa/intra/Arquivos/add_tipo", array("id" => "ci-form", "enctype" => "multipart/form-data", "class" => "general-form", "role" => "form")); ?>

            <div class="modal-body">
                <div >
                    <label>Titulo:</label>
                    <input type="text" name="titulo" id="titulo" class="form-control px-1">
                </div>
                <br>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn bg-gradient-dark"><?php echo _l('submit'); ?></button>

            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>
<?php foreach ($tipos_arquivo as $tipo) { ?>
    <div class="modal fade" id="campos<?php echo $tipo['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">
                        <span class="edit-title text-white"><?php echo 'Campos'; ?></span>
                    </h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <a href="#" class="btn btn-info mtop15 mbot10" onclick="slideToggle('.usernote<?php echo $tipo['id']; ?>'); return false;"><?php echo 'ADD Campo' ?></a>
                            <div class="clearfix"></div>
                            <div class="usernote<?php echo $tipo['id']; ?> hide">
                                <?php echo form_open("gestao_corporativa/intra/Arquivos/save_campo"); ?>

                                <input type="hidden" name="tipo_id" value="<?php echo $tipo['id']; ?>" />
                                <div class="clearfix"></div>
                                <div class="form-group" app-field-wrapper="nome"><label for="name" class="control-label">Nome do Campo</label><input type="text" id="name" name="nome" class="form-control"  value=""></div>                           
                                <div class="select-placeholder form-group">
                                    <label for="type">Tipo</label>
                                    <select name="type" id="type" onchange="change(this.value)" class="selectpicker" data-width="100%" >
                                        <option value=""></option>
                                        <option value="text" id="text">Campo de texto</option>
                                        <option value="textarea" id="textarea" >Caixa de texto grande</option>
                                        <option value="number" id="number" >Número</option>
                                        <option value="select" id="select"  >Select (Apenas uma opção)</option>
                                        <option value="multiselect" id="multiselect">Multi Select (Mais de uma opção )</option>
                                        <option value="checkbox" id="checkbox">Checkbox (Check opção)</option>
                                        <option value="date" id="date">Data(dd-mm-yyyy)</option>
                                        <option value="datetime-local" id="datetime-local">Datetime (dd-mm-yyyy 00:00:00)</option>
                                        <option value="color" id="color">Cor</option>
                                    </select>
                                </div>
                                <script>

                                </script>

                                <div class="form-group" id="select" style="display: block;">
                                    <label>Opções do select(PREENCHA CASO SEJA SELECT OU SELECT MULTIPLE):</label> 
                                    <textarea class="form-control" rows="5" placeholder="opção1, opção2, opção3" name="options"> </textarea>
                                </div>
                                <div class="clearfix"></div>
                                <div class="form-group" app-field-wrapper="field_order"><label for="ordem" class="control-label">Ordem / Posição</label><input type="number" id="field_order" name="ordem" class="form-control"  value=""></div>                            
                                <div class="form-group">
                                    <label for="bs_column">Grade (Coluna Bootstrap) - Máximo são 12</label>
                                    <div class="input-group">
                                        <span class="input-group-addon">col-md-</span>
                                        <input type="number" max="12" class="form-control" name="tam_coluna" id="tam_coluna" value="12">
                                    </div>
                                </div>
                                <button class="btn btn-info pull-right mbot15">
                                    <?php echo _l('submit'); ?>
                                </button>
                                <?php echo form_close(); ?>
                            </div>
                            <div>

                            </div>




                        </div>
                    </div>
                    <div class="row">
                        <div class="container-fluid">

                            <table class="table dt-table table-items-groups" data-order-col="1" data-order-type="asc">
                                <thead>
                                    <tr>
                                        <th><?php echo 'Ordem'; ?></th>
                                        <th><?php echo 'Nome'; ?></th>
                                        <th><?php echo 'Tipo'; ?></th>
                                        <th><?php echo 'Tamanho'; ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($tipo['campos'] as $campo) { ?>
                                        <tr class="row-has-options" id="sair<?php echo $campo['id']; ?>" data-group-row-id="<?php echo $campo['id']; ?>">
                                            <td data-order="<?php echo $campo['ordem']; ?>"><?php
                                                echo $campo['ordem'];
                                                ?></td>
                                            <td data-order="<?php echo $campo['ordem']; ?>">
                                                <span class="group_name_plain_text" ><?php echo $campo['nome']; ?></span>

                                                <div class="row-options">
                                                    <a id="edit<?php echo $campo['id']; ?>" onClick="addClass('sair<?php echo $campo['id']; ?>', 'hide');
                                                            delClass('entrar<?php echo $campo['id']; ?>', 'hide');
                                                            addClass('edit<?php echo $campo['id']; ?>', 'hide');" class="edit-item-group">
                                                       <?php echo _l('edit'); ?>
                                                    </a> |
                                                    <a href="<?php echo base_url('gestao_corporativa/intra/Arquivos/delete_campo' . '?id=' . $campo['id']); ?>" class="edit-item-group text-danger">
                                                        <?php echo _l('delete'); ?>
                                                    </a>

                                                </div>

                                            </td>
                                            <td data-order="<?php echo $campo['id']; ?>"><?php
                                                echo $campo['type'];
                                                ?></td>
                                            <td data-order="<?php echo $campo['id']; ?>">
                                                <?php echo $campo['tam_coluna']; ?>
                                            </td>
                                        </tr>
                                        <tr class="row-has-options hide" id="entrar<?php echo $campo['id']; ?>" data-group-row-id="<?php echo $campo['id']; ?>">
                                            <?php echo form_open("gestao_corporativa/intra/Arquivos/save_campo"); ?>

                                    <input type="hidden" name="id" value="<?php echo $campo['id']; ?>" />
                                    <td data-order="<?php echo $campo['ordem']; ?>">
                                        <div class="group_edit" >

                                            <div class="input-group">
                                                <input type="number" class="form-control" name="ordem" value="<?php echo $campo['ordem']; ?>">

                                            </div>

                                        </div>
                                    </td>
                                    <td data-order="<?php echo $campo['ordem']; ?>">
                                        <div class="group_edit" >

                                            <div class="input-group">
                                                <input type="text" class="form-control" name="nome" value="<?php echo $campo['nome']; ?>">

                                            </div>

                                        </div>

                                    </td>
                                    <td data-order="<?php echo $campo['id']; ?>">
                                        <div class="group_edit" >

                                            <div class="input-group">
                                                <input type="text" class="form-control" name="tipo" value="<?php echo $campo['type']; ?>">

                                            </div>

                                        </div></td>
                                    <td data-order="<?php echo $campo['id']; ?>">
                                        <div class="group_edit" >

                                            <div class="input-group">
                                                <input type="text" class="form-control" name="tam_coluna" value="<?php echo $campo['tam_coluna']; ?>">
                                                <span class="input-group-btn">
                                                    <button class="btn btn-info p8 update-item-group" type="submit"><?php echo _l('submit'); ?></button>
                                                </span>
                                            </div>

                                        </div>
                                    </td>
                                    <?php echo form_close(); ?>
                                    </tr>
                                <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn bg-gradient-dark close"  data-dismiss="modal"> Fechar</button>

                </div>

            </div>
        </div>
    </div>
    <div class="modal fade" id="editar<?php echo $tipo['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">
                        <span class="edit-title text-white"><?php echo 'Editar Tipo'; ?></span>
                    </h4>
                </div>



                <?php echo form_open("gestao_corporativa/intra/Arquivos/add_tipo", array("id" => "ci-form", "enctype" => "multipart/form-data", "class" => "general-form", "role" => "form")); ?>

                <div class="modal-body">
                    <div >
                        <label>Titulo:</label>
                        <input type="text" name="titulo" id="titulo" class="form-control px-1" value="<?php echo $tipo['titulo']; ?>">
                        <input type="hidden" name="id" class="form-control px-1" value="<?php echo $tipo['id']; ?>">
                    </div>
                    <br>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn bg-gradient-dark"><?php echo _l('submit'); ?></button>

                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>

<?php } ?>



