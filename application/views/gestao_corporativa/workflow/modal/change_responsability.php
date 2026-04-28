
<div class="modal fade" id="change_responsability" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">

    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">
                    <span class="edit-title text-white">Trocar Responsável</span>
                </h4>
            </div>

            <div class="modal-body">
                <?php

                echo render_select("atribuido_a", $staffs, array('staffid', 'firstname', 'lastname'), 'Selecione o novo responsável pelo objetivo', $fluxo_andamento->atribuido_a, array('required' => 'true'));

                ?>
            </div>
            <div class="modal-footer" id="">

                <button type="button" class="btn bg-info" style="color: white;" onclick="change_responsability('<?php echo $fluxo_andamento->id;?>');">Salvar alterações</button>

            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        init_selectpicker();
    });
</script>