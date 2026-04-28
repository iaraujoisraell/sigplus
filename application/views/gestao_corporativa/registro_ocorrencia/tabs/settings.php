<div role="tabpanel" class="tab-pane" id="settings">
    <hr class="no-mtop" />
    <div class="row">
        <div class="col-md-4">
            <div class="row">
                <div class="col-md-12">
                    <?php echo render_textarea('descricao', 'Relato Detalhado', $ro->report, array(), array(), '', 'tinymce'); ?>
                </div>
                <div class="col-md-12 text-center">
                    <hr />
                    <a href="#" class="btn btn-info" onclick="editar_ro();">
                        Editar Informaçãoes Principais
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-8">

            <?php
            $data['rel_type'] = 'r.o';
            $data['disabled'] = '';
            $data['rel_id'] = $ro->id;
            $data['preenchido_por'] = '0';
            $this->load->view('gestao_corporativa/categorias_campos/values_input', $data);
            ?>

            <div class="row">
                <div class="col-md-12 text-center">
                    <hr />
                    <a href="#" class="btn btn-info" onclick="editar_personalizados_0('0');">
                        Editar Infrmações Secundárias
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="row" id="lista">
        <?php
        $this->load->model('Registro_ocorrencia_model');
        $changes = $this->Registro_ocorrencia_model->get_changes($ro->id);
        if (count($changes) > 0) {
            ?>
            <div class="col-md-12">
                <hr />
                <h4>Lista de Alterações</h4>
            </div>
            <div class="col-md-12">

                <div class="mtop15" >
                    <table class="table  scroll-responsive" >
                        <thead>
                            <tr>
                                <th>
                                    <?php echo 'Data'; ?>
                                </th>
                                <th >
                                    <?php echo 'Usuário'; ?>
                                </th>
                                <th >
                                    <?php echo 'Campo Modificado'; ?>
                                </th>
                                <th >
                                    <?php echo 'Informação Antiga'; ?>
                                </th>
                                <th >
                                    <?php echo 'Informação Nova'; ?>
                                </th>



                            </tr>
                        </thead>
                        <tbody>

                            <?php foreach ($changes as $change) { ?>
                                <tr>
                                    <td >
                                        <label class='label label-info'><?php echo date("d/m/Y", strtotime($change['date_created'])); ?></label>

                                    </td>

                                    <td>
                                        <label class='label label-info'><?php echo $change['firstname'] . ' ' . $change['lastname']; ?></label>

                                    </td>

                                    <td>
                                        <?php echo $change['nome']; ?>
                                        <?php
                                        //$status = $note['finished'];
                                        //if ($status == 1) {
                                        //$label_status = "<label class='label label-success'>ATENDIDO</label>";
                                        //} else {
                                        //$label_status = "<label class='label label-warning'>AGENDADO</label>";
                                        //}
                                        //echo $label_status;
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        if ($change['old'] != '') {
                                            if ($change['type'] == 'multiselect' || $change['type'] == 'select') {
                                                $values = explode(',', $change['old']);
                                                $this->load->model('Registro_ocorrencia_model');
                                                for ($i = 0; $i < count($values); $i++) {
                                                    if ($values[$i] !== 's') {
                                                        $row = $this->Registro_ocorrencia_model->get_option($values[$i]);
                                                        $values[$i] = $row->option;
                                                    }
                                                }
                                                echo implode(', ', $values);
                                            } elseif ($change['type'] == 'setores') {
                                                if ($change['old']) {
                                                    echo get_departamento_nome($change['old']);
                                                }
                                            } elseif ($change['type'] == 'funcionarios') {
                                                if ($change['new']) {
                                                    echo get_staff_full_name($change['old']);
                                                }
                                            } else {
                                                echo $change['old'];
                                            }
                                        }
                                        ?>
                                    </td>

                                    <td>
                                        <?php
                                        if ($change['new'] != '') {
                                            if ($change['type'] == 'multiselect' || $change['type'] == 'select') {
                                                $values = explode(',', $change['new']);
                                                $this->load->model('Registro_ocorrencia_model');
                                                for ($i = 0; $i < count($values); $i++) {
                                                    for ($i = 0; $i < count($values); $i++) {
                                                        $row = $this->Registro_ocorrencia_model->get_option($values[$i]);
                                                        $values[$i] = $row->option;
                                                    }
                                                }
                                                echo implode(', ', $values);
                                            } elseif ($change['type'] == 'setores') {

                                                if ($change['new']) {
                                                    echo get_departamento_nome($change['new']);
                                                }
                                            } elseif ($change['type'] == 'funcionarios') {
                                                if ($change['new']) {
                                                    echo get_staff_full_name($change['new']);
                                                }
                                            } else {
                                                echo $change['new'];
                                            }
                                        }
                                        ?>
                                    </td>


                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>

            </div>
        <?php } ?>
    </div>
</div>
<script>

    function editar_ro() {

        $('#' + 'descricao').html(tinymce.get('descricao').getContent());
        var report = document.getElementById('descricao').value;

        $.ajax({
            type: "POST",
            url: "<?php echo base_url('gestao_corporativa/Registro_ocorrencia/atualizar_ro'); ?>",
            data: {
                report: report,
                id: '<?php echo $ro->id; ?>',
                type: 'desc'
            },
            success: function (data) {
                alert_float('success', 'Relato Detalhado Atualizado!');
                document.getElementById("desc_relato").innerHTML = '<strong class="text-muted" style="text-transform: uppercase;  font-weight: bold;">Descrição: </strong>' + data;

            }
        });
    }

    function editar_personalizados_0(por) {

<?php
$campos = $this->Categorias_campos_model->get_values($ro->id, 'r.o', '0');
foreach ($campos as $campo) {
    if ($campo['tipo_campo'] != 'separador' && $campo['tipo_campo'] != 'file') {
        if ($campo['tipo_campo'] == 'multiselect') {
            ?>
                    multiple = $('#<?php echo $campo['name_campo'] ?>').val();
            <?php echo $campo['name_campo'] ?> = multiple.join(',');
        <?php } else { ?>
                    var <?php echo $campo['name_campo'] ?> = document.getElementById('<?php echo $campo['name_campo'] ?>').value;
            <?php
        }
    }
}
?>

        $.ajax({
        type: "POST",
                url: "<?php echo base_url('gestao_corporativa/Registro_ocorrencia/editar_personalizados'); ?>",
                data: {
<?php
foreach ($campos as $campo) {
    if ($campo['tipo_campo'] != 'separador' && $campo['tipo_campo'] != 'file') {
        ?>
        <?php echo $campo['name_campo'] ?>: <?php echo $campo['name_campo']; ?>,
        <?php
    }
}
?>
                registro_id: '<?php echo $ro->id; ?>',
                        preenchido_por: por
                },
                success: function (data) {
                alert_float('success', 'Campos Atualizados!');
                        $('#lista').html(data);
                }
        });
    }


</script>

