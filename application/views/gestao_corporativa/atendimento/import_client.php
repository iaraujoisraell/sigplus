<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="modal fade" id="import_client_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button group="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">
                    <span class="edit-title">Importar Cliente(Tasy)</span>
                </h4>

            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <?php echo render_input('numero_carteirinha', 'Carteirinha', '', 'number', array('required' => 'true', 'onkeyup' => 'reload();')); ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                <button  class="btn btn-info" id="import_client" onclick="document.getElementById('import_client').disabled = true; enviar(); ">Importar</button>

            </div>
        </div>
    </div>
</div>
<script>
    function enviar() {


        var numero_carteirinha = document.getElementById("numero_carteirinha").value;

        if (numero_carteirinha != '') {
            $.ajax({
                type: "POST",
                url: "<?php echo base_url('gestao_corporativa/Atendimento/import_client'); ?>",
                data: {
                    numero_carteirinha: numero_carteirinha
                },
                success: function (data) {
                    //alert('yes'); exit;
                    var obj = JSON.parse(data);
                    alert_float(obj.alert, obj.msg);
                    document.getElementById('import_client').disabled = false;
                    if (obj.result == true){
                        var clients = $('select[name="client_id"]');
                        clients.prop('selectedIndex', 0);
                        clients.selectpicker('refresh');
                        clients.prepend('<option value="' + obj.userid + '" selected>' + obj.company + '</option>');
                        clients.selectpicker('refresh');
                        
                        $('#import_client_modal').modal('hide');
                        document.querySelector("[name='contato']").value = obj.phonenumber;
                        document.querySelector("[local='email']").value = obj.email;
                    }
                }
            });
        } else {
            alert('INFORMAÇÕES OBRIGATÓRIAS');
        }
    }



</script>
