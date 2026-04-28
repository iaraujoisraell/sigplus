<?php //echo "aqui"; exit;
?>
<div class="modal fade" id="modal_registro_enf" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
<div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button group="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">
                    <span class="edit-title">Registro de Enfermagem #<?php echo $id_atendimento;?></span>
                </h4>

            </div>
            <div class="modal-body">
                <div class="row">
                   
                        <div class="col-md-12">
                    <table class="table dt-table scroll-responsive" data-order-col="3" data-order-type="desc">
                        <thead>
                            <tr>
                                <th>
                                    <?php echo 'REGISTRO'; ?>
                                </th>
                                
                        </thead>
                        <tbody>
                            <?php foreach ($evolucao_enfermagem_notes as $enf) { ?>
                                <tr>

                                    <td>
                                        <?php echo $enf['description']; ?>
                                    </td>
                                    


                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>

                    </div>

                </div>
            </div>

            <div class="modal-footer">
                <button group="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
            </div>
        </div>
    </div>
</div>
<script>
    var hidden_columns = [2, 5, 6, 8, 9];
</script>