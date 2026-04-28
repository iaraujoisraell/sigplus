<?php //echo "aqui"; exit;
?>
<div class="modal fade" id="modal_registro_medico" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button group="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">
                    <span class="edit-title">Registro Medico #<?php echo $id_atendimento;?></span>
                </h4>

            </div>
            <div class="modal-body">
                <div class="row">

                    <!--<div class="col-md-6">
                            <?php echo render_input('autorizacao', 'Autorização', $trans->cep); ?>
                        </div>
                        <div class="col-md-6">
                            <?php echo render_input('senha', 'Senha', $trans->cep); ?>
                        </div>
                        <div class="col-md-6"> <?php
                                                $sql = "SELECT * FROM tblconvenio where id = '" . $convenio_id . "'";
                                                $conv = $this->db->query($sql)->row_array();
                                                if ($conv['num_guia'] == '1') {
                                                    echo render_input('num_guia_cnu', 'Numero Guia CNU', $trans->cep);
                                                } else { ?>
                                <input type="hidden" id="num_guia_cnu" name="num_guia_cnu">
                            <?php }
                            ?>

                        </div>



                        <div class="col-md-6">
                            <?php ?>
                        </div>
                        <div class="col-md-6">
                            <?php echo render_input('data_autorizacao', 'Data Autorização', $trans->cep, 'date'); ?>
                        </div>
                        <div class="col-md-6">
                            <?php echo render_input('validade', 'Validade Autorização', $trans->cep, 'date'); ?>
                        </div> -->

                        <div class="col-md-12">
                    <table class="table dt-table scroll-responsive" data-order-col="3" data-order-type="desc">
                        <thead>
                            <tr>
                                <th>
                                    <?php echo 'ATRIBUTO'; ?>
                                </th>
                                <th>
                                    <?php echo 'VALOR'; ?>
                                </th>

                        </thead>
                        <tbody>
                            <?php foreach ($user_historico_detalhes as $historico) { ?>
                                <tr>

                                    <td>
                                        <?php echo $historico['name']; ?>
                                    </td>
                                    <td>
                                        <?php echo $historico['value']; ?>
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
<?php //init_tail(); ?>