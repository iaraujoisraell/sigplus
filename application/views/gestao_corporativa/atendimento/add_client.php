<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="modal fade" id="add_client_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button group="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">
                    <span class="edit-title">Novo Cliente</span>
                </h4>

            </div>
            <div class="modal-body">
                <div class="row">
                    <?php $this->load->view('admin/clients/groups/profile', array("atendimento" => true)); ?>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                <button type="button" onclick="enviar();" class="btn btn-info"><?php echo _l('submit'); ?></button>

            </div>
        </div>
    </div>
</div>
<script>
    function enviar() {

        var company = document.getElementById("company").value;
        var vat = document.getElementsByName("vat")[0].value;
        var dt_nascimento = document.getElementById("dt_nascimento").value;
        var numero_carteirinha = document.getElementById("numero_carteirinha").value;
        var phonenumber = document.getElementById("phonenumber").value;
        var email2 = document.getElementById("email2").value;
        var address = document.getElementById("address").value;
        var city = document.getElementById("city").value;
        var state = document.getElementById("state").value;
        var zip = document.getElementById("zip").value;

        var select = document.getElementById('country');
        var country = select.options[select.selectedIndex].value;
        if (company != '' && vat != '' && dt_nascimento != '' && phonenumber != '') {
            $.ajax({
                type: "POST",
                url: "<?php echo base_url('admin/Clients/client'); ?>",
                data: {
                    company: company, vat: vat, dt_nascimento: dt_nascimento, numero_carteirinha: numero_carteirinha, phonenumber: phonenumber, email2: email2,
                    address: address, city: city, zip: zip, state: state, country: country, outside_clients: 'outside_clients'
                },
                success: function (data) {
                    var obj = JSON.parse(data);
                    alert_float(obj.alert, obj.message);
                    var clients = $('select[name="client_id"]');
                    clients.prop('selectedIndex', 0);
                    clients.selectpicker('refresh');
                    clients.prepend('<option value="' + obj.userid + '" selected>' + obj.company + '</option>');
                    clients.selectpicker('refresh');
                    $('#add_client_modal').modal('hide');
                    document.querySelector("[name='contato']").value = obj.phonenumber;
                    document.querySelector("[local='email']").value = obj.email;
                }
            });
        } else {
            alert('INFORMAÇÕES OBRIGATÓRIAS');
        }
    }



</script>
