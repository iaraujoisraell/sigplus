<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="modal fade" id="search_client_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button group="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">
                    <span class="edit-title">Encontrar Cliente</span>
                </h4>
            </div>
            <?php //echo form_open('admin/clients/group', array('id' => 'search-client-modal')); ?>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <?php echo render_input('company_nome', 'Nome', '', 'text', array('required' => 'true', 'placeholder' => 'ex: Nome Sobrenome..')); ?>   
                    </div>
                    <div class="col-md-6">
                        <?php echo render_input('vat_cpf', 'CPF', '', 'number', array('required' => 'true', 'placeholder' => 'ex: 00000000000')); ?>   
                    </div>

                    <div class="col-md-6">
                        <?php echo render_input('nascimento_data_client', 'Data de Nascimento', '', 'date', array('required' => 'true', 'placeholder' => 'ex: 00/00/0000')); ?>
                    </div>

                    <div class="col-md-6">
                        <?php echo render_input('carteirinha_client', 'Carteirinha', '', 'number', array('required' => 'true', 'placeholder' => 'ex: 0000000000000')); ?>
                    </div>
                    <div class="col-md-12" >
                        <a class="btn btn-primary" style="width: 100%;" onclick="reload();"> Filtrar Resultados<i class="fa fa-filter"></i></a>
                    </div>
                    <div class="col-md-12" id="table_div">
                        <?php
                        $table_data = [];

                        $table_data = array_merge($table_data, array(
                            'ID',
                            'NOME',
                            'CPF',
                            'NASCIMENTO',
                            'CONTATO',
                            'CARTEIRINHA',
                            'ADICIONAR',
                        ));
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        init_selectpicker();
    });

    $(function () {

        // var Params = {};
        // initDataTable('.table-clients', '<?php echo base_url(); ?>' + 'admin/clients/table_search', [0], [0], Params, [1, 'desc']);

    });

    function reload() {

        document.getElementById('table_div').innerHTML = '<hr><?php render_datatable($table_data, 'clients'); ?>';

        if ($.fn.DataTable.isDataTable('.table-clients')) {
            $('.table-clients').DataTable().destroy();
        }
        var Params = {};
        Params['company'] = '[name="company_nome"]';
        Params['vat'] = '[name="vat_cpf"]';
        Params['nascimento'] = '[name="nascimento_data_client"]';
        Params['numero_carteirinha'] = '[name="carteirinha_client"]';
        initDataTable('.table-clients', '<?php echo base_url(); ?>' + 'admin/clients/table_search', [0], [0], Params, [1, 'desc']);
    }

    function selecionar_client(client_id) {
        if (client_id != '') {
            $.ajax({
                type: "POST",
                url: "<?php echo base_url('gestao_corporativa/Atendimento/select_client'); ?>",
                data: {
                    client_id: client_id
                },
                success: function (data) {
                    var obj = JSON.parse(data);
                    var clients = $('select[name="client_id"]');
                    clients.prop('selectedIndex', 0);
                    clients.selectpicker('refresh');
                    clients.prepend('<option value="' + obj.userid + '" selected>' + obj.company + '</option>');
                    clients.selectpicker('refresh');
                    $('#search_client_modal').modal('hide');
                    document.querySelector("[name='contato']").value = obj.phonenumber;
                    document.querySelector("[name='email']").value = obj.email;
                }
            });
        } else {
            alert('Usuário Inválido.');
        }
    }
</script>
