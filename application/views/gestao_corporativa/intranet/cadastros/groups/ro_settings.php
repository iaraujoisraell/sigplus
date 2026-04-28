
<h4 class="customer-profile-group-heading"><?php echo 'Registro de Ocorrência - Configurações'; ?></h4>

<div class="mtop15">
    <div class="horizontal-scrollable-tabs">
        <div class="scroller arrow-left"><i class="fa fa-angle-left"></i></div>
        <div class="scroller arrow-right"><i class="fa fa-angle-right"></i></div>
        <div class="horizontal-tabs">
            <ul class="nav nav-tabs profile-tabs row customer-profile-tabs nav-tabs-horizontal" role="tablist">
                <li role="presentation" class="active">
                    <a href="#categorias" aria-controls="categorias" role="tab" data-toggle="tab">
                        Categorias
                    </a>
                </li>
                <li role="presentation" class="">
                    <a href="#atuantes" aria-controls="atuantes" role="tab" data-toggle="tab">
                        Atuantes
                        <span class="badge" style="background: red;"><?php //echo count($pendentes);        ?></span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <div class="tab-content mtop15">
        <div role="tabpanel" class="tab-pane active" id="categorias">
            <?php
            $data['rel_type'] = 'r.o';
            $this->load->view('gestao_corporativa/categorias_campos/admin_categoria_tab', $data);
            ?>
        </div>
        <div role="tabpane2" class="tab-pane" id="atuantes">
            <div class="row">
                <div class="col-md-12">
                    <?php if (has_permission_intranet('ro', '', 'create_atuante') || is_admin()) { ?>
                        <div id="atuante<?php echo $atuante->id; ?>">
                            <div class="col-md-12">
                                <div class="input-group">
                                    <input type="text" name="titulo_atuante" id="titulo_atuante" class="form-control" placeholder="<?php echo 'Nome'; ?>" required="required">
                                    <span class="input-group-btn">
                                        <button class="btn btn-info p7" onclick="add_atuante('');"><?php echo 'Add Atuante'; ?></button>
                                    </span>
                                </div>

                            </div>
                            <div class="col-md-12">
                                <div class="icheck-primary d-inline col-md-<?php echo $campo['tam_coluna']; ?>">

                                    
                                    <input type="checkbox" id="checkboxPrimary2" class="abas" name="abas" value="ishikawa">
                                    <label for="checkboxPrimary2">
                                        Ishikawa
                                    </label>
                                    <input type="checkbox" id="checkboxPrimary3" class="abas" name="abas" value="notas">
                                    <label for="checkboxPrimary3">
                                        Notas
                                    </label>
                                    <input type="checkbox" id="checkboxPrimary4" class="abas" name="abas" value="tarefas">
                                    <label for="checkboxPrimary4">
                                        Ações
                                    </label>
                                    <input type="checkbox" id="checkboxPrimary5" class="abas" name="abas" value="configuracao">
                                    <label for="checkboxPrimary5">
                                        Notificante
                                    </label>
                                    <input type="checkbox" id="checkboxPrimary6" class="abas" name="abas" value="classificacao">
                                    <label for="checkboxPrimary6">
                                        Classificação
                                    </label>
                                    <input type="checkbox" id="checkboxPrimary10" class="more_info" name="abas" value="more_info">
                                    <label for="checkboxPrimary10">
                                        Informações Adicionais
                                    </label>
                                    <input type="checkbox" id="checkboxPrimary9" class="abas" name="abas" value="email">
                                    <label for="checkboxPrimary9">
                                        Email
                                    </label>
                                    <input type="checkbox" id="checkboxPrimary8" class="abas" name="abas" value="sms">
                                    <label for="checkboxPrimary8">
                                        Sms
                                    </label>
                                    <input type="checkbox" id="checkboxPrimary7" class="abas" name="abas" value="files">
                                    <label for="checkboxPrimary7">
                                        Anexos
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12" id="caixa_de_atuantes" style="margin-top: 10px; margin-bottom: 10px;">

                        </div>


                        <hr />
                    <?php } ?>



                    <div class="row">
                        <div class="container-fluid">

                            <?php
                            $table_data = [];

                            $table_data = array_merge($table_data, array(
                                'Atuante',
                                'Permissões',
                                'Opções',
                                    //'Responsabilidade',
                            ));
                            render_datatable($table_data, 'atuantes');
                            ?>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

</div>
<div id="modal_wrapper"></div>




<?php init_tail(); ?>

<script>
    $(function () {
        var Params = {};
        initDataTable('.table-atuantes', '<?php echo base_url(); ?>' + 'gestao_corporativa/Registro_ocorrencia/table_atuantes', [0], [0]);
    });

    // ATUANTES 

    function reload_atuantes() {
        var Params = {};
        if ($.fn.DataTable.isDataTable('.table-atuantes')) {
            $('.table-atuantes').DataTable().destroy();
        }
        var tAPI = initDataTable('.table-atuantes', '<?php echo base_url(); ?>' + 'gestao_corporativa/Registro_ocorrencia/table_atuantes', [0], [0], Params, [1, 'desc']);
    }

    function delete_atuante(id) {
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('gestao_corporativa/Registro_ocorrencia/delete_atuante'); ?>",
            data: {
                id: id
            },
            success: function (data) {
                reload_atuantes();
            }
        });
    }

    function add_atuante(id) {

        var titulo = document.querySelector("#titulo_atuante" + id);
        var titulo = titulo.value;
        var vals = [];
        $("input[name='abas" + id + "']:checked").each(function () {
            vals.push($(this).prop('value'));
        });
        var valsst = vals.join(',');
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('gestao_corporativa/Registro_ocorrencia/add_atuante'); ?>",
            data: {
                id: id,
                titulo: titulo,
                abas: valsst
            },
            success: function (data) {
                reload_atuantes();
                document.getElementById('titulo_atuante').value = ''; // Limpa o campo
                if (id) {
                    document.getElementById('atuante' + id).remove();
                    document.getElementById('atuante').style.display = 'block';
                }
            }
        });
    }
    function edit_atuante_form(id) {
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('gestao_corporativa/Registro_ocorrencia/edit_atuante_form'); ?>",
            data: {
                id: id
            },
            success: function (data) {
                $('#caixa_de_atuantes').html(data);
                document.getElementById("atuante").style.display = 'none';
            }
        });
    }
</script>

