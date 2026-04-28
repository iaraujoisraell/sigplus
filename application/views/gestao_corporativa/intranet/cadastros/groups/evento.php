<h4 class="customer-profile-group-heading"><?php echo 'Eventos'; ?></h4>
<div class="mtop15">
                    <?php if (has_permission_intranet('eventos', '', 'create') || is_admin()) { ?>
                        <div class="_buttons">
                            <a data-toggle="modal" data-target="#add_data" class="btn btn-info pull-left" ><?php echo 'Add Evento'; ?></a>    

                        </div>
                        <div class="clearfix"></div>
                        <hr class="hr-panel-heading" />
                    <?php } ?>


                    <?php
                    $table_data = [];
                    $table_data = array_merge($table_data, array(
                        'Título',
                        'Inicio',
                        'Cor',
                        'Cadastro',
                        'Ações',
                    ));
                    render_datatable($table_data, 'eventos');
                    ?>
</div>


<?php init_tail(); ?>
<script>

    $(function () {
        var CustomersServerParams = {};
        var tAPI = initDataTable('.table-eventos', '<?php echo base_url();?>' + 'gestao_corporativa/intra/intranet/table_eventos', [0], [0], CustomersServerParams, [1, 'desc']);
    });

</script>


<div class="modal fade" id="add_data" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">
                    <span class="edit-title text-white"><?php echo 'Cadastrar Evento'; ?></span>
                </h4>
            </div>



            <?php echo form_open("gestao_corporativa/Intranet/add_data", array("id" => "ci-form", "enctype" => "multipart/form-data", "class" => "general-form", "role" => "form")); ?>

            <div class="modal-body">
                <div >
                    <label>Titulo:</label>
                    <input type="text" name="titulo" id="titulo" class="form-control px-1">
                </div>
                <br>
                <div>
                    <label>Inicio:</label>
                    <input type="date" name="inicio" id="inicio" class="form-control">
                </div>
                <br>
                <div >
                    <label>Cor</label>
                    <input type="color" name="color" id="color" class="form-control p-2">
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn bg-gradient-dark"><?php echo _l('submit'); ?></button>

            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>