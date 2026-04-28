<div role="tabpanel" class="tab-pane" id="responsavel">
    <hr class="no-mtop" />
    <div class="row">

        <?php
        $this->load->model('Registro_ocorrencia_model');
        $this->load->model('Categorias_campos_model');

        $categoria = $this->Categorias_campos_model->get_categoria($ro->categoria_id, 'r.o');

        $categoria_atuantes = $this->Registro_ocorrencia_model->get_categoria_atuantes($categoria->atuantes);
        $atuantes_atuais = $this->Registro_ocorrencia_model->get_atuantes_preenchidos($ro->id);

        foreach ($categoria_atuantes as $atuante):
            ?>

            <div id="trocar_form_atuante<?php echo $atuante['id']; ?>" style="width: 100%;">
                <div class="form-group select-placeholder col-md-<?php
                if (in_array($atuante['id'], $atuantes_atuais)) {
                    $atuante_por_registro = $this->Registro_ocorrencia_model->get_atuantes_staff_id($ro->id, $atuante['id']);
                    echo '3';
                } else {
                    echo '12';
                }
                ?>" >
                    <label for="assigned" class="control-label"> 
                        <?php echo $atuante['titulo']; ?>:
                    </label>
                    <select name="assigned" data-live-search="true" id="assigned" onchange="out(this.value, '<?php echo $atuante_por_registro->id;?>'); atualizar_etapa_2(this.value, '<?php echo $atuante['id']; ?>')" class="form-control selectpicker" data-none-selected-text="atribuido_a">
                        <?php
                        if ($atuante['staff_id'] == 0 || $atuante['staff_id'] == '') {
                            $selected = 'selected';
                        }
                        ?>
                        <?php if (in_array($atuante['id'], $atuantes_atuais)) { ?>
                            <option <?php echo $selected; ?> value='' >Limpar atuante (DELETAR)</option>
                        <?php } else { ?>
                            <option <?php echo $selected; ?> disabled >Selecionar</option>
                        <?php } ?>
                        <?php
                        foreach ($staff as $member) {

                            if ($member['active'] == 0) {
                                continue;
                            }
                            $select = '';
                            if (in_array($atuante['id'], $atuantes_atuais)) {

                                
                                if ($atuante_por_registro->staff_id == $member['staffid']) {
                                    $select = 'selected';
                                }
                            }
                            ?>
                            <option value="<?php echo $member['staffid']; ?>" <?php echo $select; ?>>
                                <?php echo $member['firstname'] . ' ' . $member['lastname']; ?>
                            </option>
                        <?php } ?>
                    </select>

                </div>
                <?php
                if (in_array($atuante['id'], $atuantes_atuais)) {
                    $this->load->model('Departments_model');
                    $deps = $this->Departments_model->get_staff_departments($atuante_por_registro->staff_id);

                    echo render_select('dep', $deps, array('departmentid', 'name'), 'Departamento', $atuante_por_registro->department_id, ['onchange' => "edit_department('".$atuante_por_registro->id."', this.value)"], [], 'col-md-3');
                    ?>

                <?php } ?>

    <?php if (in_array($atuante['id'], $atuantes_atuais)) { ?>

                    <div class="form-group col-md-2" app-field-wrapper="prazo">
                        <label for="prazo" class="control-label">Data Prazo:</label>
                        <input type="date" id="prazo" name="prazo" class="form-control"  value="<?php echo $atuante_por_registro->prazo; ?>" onchange="edit_prazo('<?php echo $atuante_por_registro->id; ?>', this.value);">
                    </div>
                <?php } ?>

    <?php if (in_array($atuante['id'], $atuantes_atuais)) { ?>
                    <div class="form-group col-md-2" style="text-align: center;">
                        <label  class="control-label">Atuante Limitado:</label>
                        <div class="icheck-primary d-inline">
                            <input type="radio" id="atuante__<?php echo $atuante_por_registro->id; ?>" name="atuante__<?php echo $atuante_por_registro->id; ?>" <?php
                            if ($atuante_por_registro->limitado == 1) {
                                echo 'checked';
                            }
                            ?> onclick="limitar('<?php echo $atuante_por_registro->id; ?>', '1');">
                            <label for="atuante__<?php echo $atuante_por_registro->id; ?>" >
                                Sim
                            </label>
                            <input style="margin-left: 5px;" type="radio" id="atuante__<?php echo $atuante_por_registro->id; ?>" name="atuante__<?php echo $atuante_por_registro->id; ?>" <?php
                            if ($atuante_por_registro->limitado == 0) {
                                echo 'checked';
                            }
                            ?> onclick="limitar('<?php echo $atuante_por_registro->id; ?>', '0');">
                            <label for="atuante__<?php echo $atuante_por_registro->id; ?>">
                                Não
                            </label>
                        </div>
                    </div>



                <?php } ?>
    <?php if (in_array($atuante['id'], $atuantes_atuais)) { ?>

                    <div class=" form-group col-md-2" style="align-items: center;">
                        <label for="objetivo" class="control-label">Objetivo:</label>
                        <a href="#" class="btn btn-info w-100 h-100" onclick="return_objetivo('<?php echo $atuante['id']; ?>');"><i class="fa fa-pencil"></i></a>
                    </div>

    <?php } ?>
                <div class="col-md-12">
                    <hr /> 
                </div>

            </div>

<?php endforeach; ?>
        <hr />
        <div class="col-md-12 " style="margin-top: 20px;">
            <div class="row">
                <?php
                $campos = $this->Categorias_campos_model->get_values($ro->id, 'r.o', 'setor_responsavel');
                if (count($campos) > 0) {
                    ?>
                    <div class="col-md-12 " style="margin-top: 20px;">

                        <div class="panel_s">
                            <div class="panel-heading">
                                Campos Obrigatórios da Categoria
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-12 mbot20 before-ticket-message">
                                        <?php
                                        $data['rel_type'] = 'r.o';
                                        $data['disabled'] = '';
                                        $data['rel_id'] = $ro->id;
                                        $data['preenchido_por'] = 'setor_responsavel';
                                        $this->load->view('gestao_corporativa/categorias_campos/values_input', $data);
                                        ?>
                                        <div class="row" id="lista3">
                                            <div class="col-md-12 text-center">
                                                <hr />
                                                <a href="#" class="btn btn-info" onclick="editar_personalizados_setor('setor_responsavel');">
    <?php echo _l('submit'); ?>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>
<?php } else { ?>
                    <div class="col-md-12">
                        <div class="alert alert-warning alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <h5><i class="icon fa fa-info-circle"></i> Campos Personalizados!</h5>
                            Não existem campos obrigatórios para você.
                        </div>
                    </div>

<?php } ?>

            </div>
        </div>
    </div>

</div>
<div id="modal_wrapper"></div>

<script>

    function editar_personalizados_setor(por) {
<?php ?>

<?php
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
    function return_objetivo(el) {
        $("#modal_wrapper").load("<?php echo base_url(); ?>gestao_corporativa/Registro_ocorrencia/return_objetivo", {
            atuante_id: el,
            ro_id: '<?php echo $ro->id; ?>',
        }, function () {
            if ($('.modal-backdrop.fade').hasClass('in')) {
                $('.modal-backdrop.fade').remove();
            }
            if ($('#edit_objetivo').is(':hidden')) {
                $('#edit_objetivo').modal({
                    show: true
                });
            }
        });
    }

</script>

<script>
    function out(value, id) {
        
        if (value == '') {
            //alert(id);
            window.location.href = '<?php echo base_url();?>gestao_corporativa/Registro_ocorrencia/delete_atuante_por_ro/' + id + '?id=<?php echo $ro->id;?>';
        }
    }
</script>
