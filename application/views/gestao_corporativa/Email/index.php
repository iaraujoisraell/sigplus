

<?php if ($just_table == false) { ?>
    <div class="col-md-12">
        <div class=" _buttons">
            <a href="#" class="btn btn-primary " <?php echo $disabled; ?> onclick="slideToggle('.usernote'); return false;"><?php echo 'Novo E-mail'; ?></a>
        </div>
        <div class="mtop15">

            <?php if (!$disabled) { ?>
                <div class="usernote hide">
                    <?php echo form_open_multipart('gestao_corporativa/Comunicacao/add_email', array('id' => '')); ?>
                    <input type="hidden" value="<?php echo $rel_type; ?>" name="rel_type">
                    <input type="hidden" value="<?php echo $rel_id; ?>" name="rel_id">
                    <input type="hidden" value="<?php echo $url_retorno; ?>" name="url_retorno">
                    <div class="row">

                        <div class="col-md-12" >
                            <label>E-mail</label>
                            <input type="text" name="email" class="tagsinput" class="form-control" value="<?php echo $email; ?>" >
                        </div>
                        <div class="col-md-12" >
                            <label>Assunto</label>
                            <input type="text" name="assunto" class="form-control"  >
                        </div>
                        <div class="col-md-12" >
                            <?php echo render_textarea('texto', 'Texto', $value, array(), array(), 'mtop15'); ?>
                        </div>
                        <div class="col-md-12" >
                            <button class="btn btn-success p8-half" type="button" onclick="more_files();"><i class="fa fa-plus"></i> ADD ARQUIVO</button>
                            <div id="attachmentContainer" style='margin-top: 10px;'>



                            </div>
                        </div>

                    </div>

                    <button class="btn btn-info pull-right ">
                        <?php echo 'Enviar'; ?>
                    </button>
                    <?php echo form_close(); ?>
                </div>
            <?php } ?>
            <div class="clearfix"></div>
        <?php } ?>
        <div class="mtop15">
            <table class="table dt-table scroll-responsive" data-order-col="2" data-order-type="desc">
                <thead>
                    <tr>
                        <th >
                            <?php echo 'Data Registro'; ?>
                        </th>
                        <th >
                            <?php echo 'Assunto'; ?>
                        </th>
                        <th>
                            <?php echo 'Mensagem'; ?>
                        </th>
                        <th>
                            <?php echo 'Anexos'; ?>
                        </th>

                        <th>
                            <?php echo 'Email Destino'; ?>
                        </th>
                        <th>
                            <?php echo 'Situação'; ?>
                        </th>
                    </tr>
                </thead>
                <tbody>


                    <?php
                    $this->load->model('Comunicacao_model');
                    $emails = $this->Comunicacao_model->get_emails($rel_type, $rel_id, $client_id, $email);
                    foreach ($emails as $email) {

                        $enviado = $email['enviado'];
                        if (!$email['enviado']) {
                            $enviado = '<label class="label label-primary">AGUARDANDO</label>';
                        } else if ($email['enviado'] == 'S') {
                            $enviado = '<label class="label label-success">ENVIADO</label>';
                        } else if ($email['enviado'] == 'N') {
                            $enviado = '<label class="label label-danger">FALHA</label>';
                        }
                        ?>
                        <tr>
                            <td >
                                <?php echo _dt($email['data_registro']) . '<br>' . $email['firstname'] . ' ' . $email['lastname']; ?>
                            </td>
                            <td >
                                <?php echo $email['assunto']; ?>
                            </td>
                            <td >
                                <?php echo $email['mensagem']; ?>
                            </td>
                             <td >
                                <?php $attachments = explode(';', $email['attachments']);
                                     foreach ($attachments as $att){
                                         echo '<a href="'.base_url().'assets/intranet/arquivos/email_arquivos/'.$att.'" target="_BLANCK">'.$att.'</a></br>';
                                     }?>
                                 
                            </td>
                            <td >
                                <?php echo $email['email_destino']; ?>
                            </td>
                            <td >
                                <?php echo $enviado; ?>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        <?php if ($just_table == false) { ?>
        </div>
    </div>
<?php } ?>

<script>
    var attachmentCounter = 0;

    // Attach click event to the "Add Attachment" button
    function more_files() {
        attachmentCounter++;
        var newAttachment = `
                    <div class="attachment" id="attachment_${attachmentCounter}">
                        <div class="col-md-12 col-md-offset12 mbot15">
                            <div class="form-group">
                                <label for="attachment" class="control-label">Arquivo</label>
                                <div class="input-group">
                                    <input type="file" class="form-control" name="attachment_${attachmentCounter}">
                                    <span class="input-group-btn">
                                        <button class="btn btn-danger p8-half addAttachment" data-max="${attachmentCounter}" type="button" onclick="removeAttachment(${attachmentCounter})"><i class="fa fa-times"></i></button>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
        $("#attachmentContainer").append(newAttachment);
    }
    
    function removeAttachment(attachmentCounter) {
    var attachment = document.getElementById(`attachment_${attachmentCounter}`);
    if (attachment) {
        attachment.remove();
    }
}
</script>
