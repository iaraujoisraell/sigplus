
<div class="modal fade" id="edit_objetivo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">

    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">
                    <span class="edit-title text-white">Objetivo para <?php
                        echo $atuante->titulo;
                        ?></span>
                </h4>
            </div>

            <div class="modal-body">

                
                    <div class="form-group">
                        <label class="control-label">Objetivo:</label>
                        <textarea class="form-control" rows="5" placeholder="OBJETIVO DO ATUANTE" id="objetivo<?php echo $atuante->id;?>"><?php echo $atuante->objetivo; ?></textarea>
                    </div>
            </div>
            <div class="modal-footer" id="teste">

                <button onclick="edit_objetivo<?php echo $atuante->id; ?>();" class="btn bg-info" style="color: white;">Editar Objetivo</button>

            </div>
        </div>
    </div>
</div>
<script>

function edit_objetivo<?php echo $atuante->id; ?>() {
    var objetivo = document.getElementById("objetivo<?php echo $atuante->id;?>").value;
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('gestao_corporativa/Registro_ocorrencia/edit_info_atuante'); ?>",
            data: {
                objetivo: objetivo, id: '<?php echo $atuante->id; ?>', ro_id: '<?php echo $atuante->registro_id;?>'
            },
            success: function (data) {
                $('#edit_objetivo').modal('hide');
                alert_float('success', 'Objetivo Editado!');
            }
        });
    }

</script>