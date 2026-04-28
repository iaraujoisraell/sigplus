<div role="tabpanel" class="tab-pane" id="classificacao">
    <hr class="no-mtop" />
    <div class="row">
        <div class="col-md-12 " style="margin-top: 20px;">
            <div class="row">
                <?php
                $campos = $this->Categorias_campos_model->get_values($ro->id, 'r.o', 'classificacao');
                if (count($campos) > 0) {
                    ?>
                    <div class="col-md-12 " style="margin-top: 20px;">

                        <div class="panel_s">
                            <div class="panel-heading">
                                Campos para Classificação
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-12 mbot20 before-ticket-message">
                                        <?php
                                        $data['rel_type'] = 'r.o';
                                        $data['disabled'] = '';
                                        $data['rel_id'] = $ro->id;
                                        $data['preenchido_por'] = 'classificacao';
                                        $this->load->view('gestao_corporativa/categorias_campos/values_input', $data);
                                        ?>
                                        <div class="row" id="lista3">
                                            <div class="col-md-12 text-center">
                                                <hr />
                                                <a href="#" class="btn btn-info" onclick="editar_personalizados_classificacao('classificacao');">
                                                    <?php echo _l('submit'); ?>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>
                <?php } else { ?>
                    <div class="col-md-12">
                        <div class="alert alert-warning alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <h5><i class="icon fa fa-info-circle"></i> Campos Personalizados!</h5>
                            Não existem campos para Classificação.
                        </div>
                    </div>

                <?php } ?>

            </div>
        </div>
    </div>

</div>

<script>

    function editar_personalizados_classificacao(por) {
<?php ?>

<?php
foreach ($campos as $campo) {
    if ($campo['tipo_campo'] != 'separador' && $campo['tipo_campo'] != 'file') {
        if ($campo['tipo_campo'] == 'multiselect') {
            ?>
                    multiple = $('#<?php echo $campo['name_campo'] ?>').val();
            <?php echo $campo['name_campo'] ?> = multiple.join(',');
        <?php } else { ?>
                    var <?php echo $campo['name_campo'] ?> = document.getElementById('<?php echo $campo['name_campo'] ?>').value;
            <?php
        }
    }
}
?>

        $.ajax({
        type: "POST",
                url: "<?php echo base_url('gestao_corporativa/Registro_ocorrencia/editar_personalizados'); ?>",
                data: {
<?php
foreach ($campos as $campo) {
    if ($campo['tipo_campo'] != 'separador' && $campo['tipo_campo'] != 'file') {
        ?>
        <?php echo $campo['name_campo'] ?>: <?php echo $campo['name_campo']; ?>,
        <?php
    }
}
?>
                registro_id: '<?php echo $ro->id; ?>',
                        preenchido_por: por
                },
                success: function (data) {
                alert_float('success', 'Campos Atualizados!');
                        $('#lista').html(data);
                }
        });
    }
</script>
