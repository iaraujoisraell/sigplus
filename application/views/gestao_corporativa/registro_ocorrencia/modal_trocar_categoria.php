<div class="modal fade" id="trocar_categoria" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">

    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">
                    <span class="edit-title text-white">ALTERAR CATEGORIA<?php
                                                                            //   echo $atuante->titulo;
                                                                            ?></span>
                </h4>
            </div>

            <?php echo form_open(base_url("gestao_corporativa/Registro_ocorrencia/trocar_categoria")); ?>

            <div class="modal-body">


                <div class="col-md-12"><br>

                    <input type="hidden" name="ro_id" value="<?= $ro_id ?>">

                    <?php
                    echo render_select('categoria_id', $categorias, array('id', 'titulo', 'name'), 'Categoria', [], array('required' => 'true'));
                    ?>
                </div>
                <div class="col-md-12" id="trocar">

                </div>

            </div>
            <div class="modal-footer">

                <div id="minhaDiv" style="display: none;">

                    <button type="submit" class="btn btn-primary">
                        Salvar
                    </button>
                </div>

            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>
<script>
    $(document).on('change', "#categoria_id", function() {
        $('#trocar').html('<div style="text-align:center;"><i class="fa fa-spinner fa-spin"></i> Carregando Campos ...</div>');
        var select = document.getElementById("categoria_id");
        var opcaoValor = select.options[select.selectedIndex].value;
        if (opcaoValor != "") {
            $.ajax({
                type: "POST",
                url: "<?php echo base_url('gestao_corporativa/registro_ocorrencia/retorno_categoria_campos?rel_type=r.o'); ?>",
                data: {
                    categoria_id: opcaoValor
                },
                success: function(data) {
                    $('#trocar').html(data);
                    document.getElementById("minhaDiv").style.display = "block";
                }
            });
        } else {
            alert('Selecione euma categoria!');
        }
    });

    $(document).ready(function() {
        init_selectpicker();
    });
</script>