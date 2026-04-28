<div role="tabpanel" class="tab-pane" id="note_<?php echo $rel_type; ?>">

        

    <div class="row">
        <div class="col-md-12">
            <a href="#" class="btn btn-success mtop5 mbot5" onclick="slideToggle('.usernote'); return false;"><?php echo _l('new_note'); ?></a>

            <div class="clearfix"></div>
            <div class="usernote hide mtop5">
                <?php echo form_open('', ['id' => 'form' . $rel_type, 'enctype' => 'multipart/form-data']); ?>
                <?php echo render_textarea('note', 'note_description', '', array('rows' => 5)); ?>

                <button class="btn btn-warning p8-half" type="button" onclick="more_files_notes();"><i class="fa fa-plus"></i> VINCULAR ARQUIVO</button>
                <div id="attachmentContainer_notes" style='margin-top: 10px;'>



                </div>

                <input name="rel_type" value="<?php echo $rel_type; ?>" type="hidden">
                <input name="rel_id" value="<?php echo $rel_id; ?>" type="hidden">
                <button class="btn btn-info pull-right mbot15" type="button" onclick="submit_note('<?php echo $rel_type; ?>');">
                    <?php echo _l('submit'); ?>
                </button>
                <?php echo form_close(); ?>
            </div>
        </div>

    </div>

    
    <div class="row" id="refresh_notes_<?php echo $rel_type; ?>">
        <?php
        $this->load->model('Intranet_general_model');
        $notes = $this->Intranet_general_model->get_notes($rel_type, $rel_id); //print_r($notes);
        if (count($notes) > 0) {
            
            $this->load->view('gestao_corporativa/notes/load_again', array('notes' => $notes, 'rel_type' => $rel_type, 'rel_id' => $rel_id));
        } else { ?>

            <div class="col-md-12" style="margin-top: 5px;">
                <div class="alert alert-info alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h5><i class="icon fa fa-info-circle"></i> 0 NOTAS ADICIONADAS</h5>
                    Não existem notas adicionadas nesse processo.
                </div>
            </div>
        <?php } ?>
    </div>

</div>
<script>

    var attachmentCounter_notes = 0;

    // Attach click event to the "Add Attachment" button
    function more_files_notes() {
        attachmentCounter_notes++;
        var newAttachment = `
                    <div class="attachment_note" id="attachment_note_${attachmentCounter_notes}">
                        <div class="col-md-12 col-md-offset12 mbot15">
                            <div class="form-group">
                                <label for="attachment_note" class="control-label">Arquivo</label>
                                <div class="input-group">
                                    <input type="file" class="form-control" name="attachment_note_${attachmentCounter_notes}" id="attachment_note_${attachmentCounter_notes}">
                                    <span class="input-group-btn">
                                        <button class="btn btn-danger p8-half" data-max="${attachmentCounter_notes}" type="button" onclick="removeAttachment_note(${attachmentCounter_notes})"><i class="fa fa-times"></i></button>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
        $("#attachmentContainer_notes").append(newAttachment);
    }

    function removeAttachment_note(attachmentCounter) {
        var attachment = document.getElementById(`attachment_note_${attachmentCounter}`);
        if (attachment) {
            attachment.remove();
            attachmentCounter_notes--;
        }
    }

    function submit_note_error(rel_type) {
        var formData = $("#form" + rel_type).serialize(); // Serializa os dados do formulário

        var attachmentCount = attachmentCounter_notes;
        //alert(attachmentCount);
        for (var i = 1; i <= attachmentCount; i++) {
            var fileInput = document.querySelector('input[name="attachment_note_' + i + '"]');
            if (fileInput.files.length > 0) {
                formData.append('attachment_note[]', fileInput.files[0]);
            }
        }
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('gestao_corporativa/Intranet_general/add_note'); ?>", // URL para onde enviar os dados
            data: formData,
            success: function (data) {
                $('#refresh_notes_' + rel_type).html(data);
                document.getElementById('note').value = ''; // Limpa o campo
                alert_float('success', 'Nota adicionada!');
            },
            error: function (xhr, status, error) {
                alert(error);
            }
        });
    }

    function submit_note(rel_type) {
        var formData = new FormData($("#form" + rel_type)[0]); // Serializa os dados do formulário

        //var attachmentCount = attachmentCounter_notes;
        //alert(attachmentCount);
       // for (var i = 1; i <= attachmentCount; i++) {
         //   var fileInput = document.querySelector('input[name="attachment_note_' + i + '"]');
          // // if (fileInput.files.length > 0) {
//             /   formData.append('attachment_note[]', fileInput.files[0]);
            //}
       // }

        //formData.append('rel_type', rel_type);
        //formData.append('note', $('#note').val());
        //formData.append('rel_id', '<?php echo $rel_id; ?>');

        $.ajax({
            type: "POST",
            url: "<?php echo base_url('gestao_corporativa/Intranet_general/add_note'); ?>", // URL para onde enviar os dados
            data: formData,
            contentType: false, // Não defina o tipo de conteúdo
            processData: false, // Não processe os dados
            
            success: function (data) {
                slideToggle('.usernote');
                
                
                $('#refresh_notes_' + rel_type).html(data);

                $('#note').val(''); // Limpa o campo
                alert_float('success', 'Nota adicionada!');

                $("#attachmentContainer_notes").html('');
                attachmentCounter_notes = 0;
            },
            error: function (xhr, status, error) {
                alert(error);
            }
        });
    }
    function delete_note(id, rel_type, rel_id) {

        $.ajax({
            type: "POST",
            url: "<?php echo base_url('gestao_corporativa/Intranet_general/delete_note'); ?>", // URL para onde enviar os dados
            data: {
                id: id,
                rel_type: rel_type,
                rel_id: rel_id
            },
            success: function (data) {
                $('#refresh_notes_' + rel_type).html(data);
                alert_float('success', 'Nota Deletada!');
            }
        });
    }

    function update_note(id, rel_type, rel_id) {
        var formData = $("#form_note" + id).serialize(); // Serializa os dados do formulário

        $.ajax({
            type: "POST",
            url: "<?php echo base_url('gestao_corporativa/Intranet_general/update_note'); ?>", // URL para onde enviar os dados
            data: formData,
            success: function (data) {
                $('#refresh_notes_' + rel_type).html(data);
                document.getElementById('note').value = ''; // Limpa o campo
                alert_float('success', 'Nota editada!');
            }
        });
    }
</script>




