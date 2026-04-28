


<div class="col-md-12">
    <div class=" _buttons">
        <a href="#" class="btn btn-primary " onclick="slideToggle('.usernote'); return false;"><?php echo 'Novo Sms'; ?></a>
    </div>
    <div class="mtop15">

        <div class="usernote hide">
            <?php echo form_open('gestao_corporativa/Comunicacao/add_sms', array('id' => '')); ?>
            <input type="hidden" value="<?php echo $rel_type; ?>" name="rel_type">
            <input type="hidden" value="<?php echo $rel_id; ?>" name="rel_id">
            <input type="hidden" value="<?php echo $url_retorno; ?>" name="url_retorno">
            <div class="row">

                
                <div class="col-md-12" >
                    <label>Telefone Destino(somente número)</label>
                    <input type="numer" name="phone_destino" class="form-control" maxlength="11" minlength="11">
                </div>
                <div class="col-md-12" >
                    <?php echo render_textarea('texto', 'Mensagem', $value, array(), array(), 'mtop15'); ?>
                </div>
            </div>

            <button class="btn btn-info pull-right ">
                <?php echo 'Enviar'; ?>
            </button>
            <?php echo form_close(); ?>
        </div>

        <div class="clearfix"></div>
        <div class="mtop15">
            <table class="table dt-table scroll-responsive" data-order-col="2" data-order-type="desc">
                <thead>
                    <tr>
                        <th >
                            <?php echo 'Data Registro'; ?>
                        </th>
                        <th>
                            <?php echo 'Mensagem'; ?>
                        </th>
                        <th>
                            <?php echo 'Telefone Destino'; ?>
                        </th>
                        <th>
                            <?php echo 'Situação'; ?>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    
                    
                    <?php
                    $this->load->model('Comunicacao_model');
                    $emails = $this->Comunicacao_model->get_sms($rel_type, $rel_id);
                    foreach ($emails as $email) {

                        $enviado = $email['status'];
                        if ($email['status'] == 0) {
                            $enviado = '<label class="label label-primary">AGUARDANDO</label>';
                        } else if ($email['status'] == 1) {
                            $enviado = '<label class="label label-success">ENVIADO</label>';
                        } else if ($email['status'] == 2) {
                            $enviado = '<label class="label label-danger">FALHA</label>';
                        }
                        ?>
                        <tr>
                            <td >
    <?php echo _dt($email['data_registro']) . '<br>' . $email['firstname'] . ' ' . $email['lastname']; ?>
                            </td>
                            <td >
    <?php echo $email['mensagem']; ?>
                            </td>
                            <td >
    <?php echo $email['phone_destino']; ?>
                            </td>
                            <td >
    <?php echo $enviado; ?>
                            </td>
                        </tr>
<?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
