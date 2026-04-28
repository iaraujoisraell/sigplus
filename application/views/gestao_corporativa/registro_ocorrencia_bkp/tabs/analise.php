<div role="tabpanel" class="tab-pane" id="analise">

    <?php echo form_open_multipart('', array('id' => 'ishikawa_form', 'onsubmit' => 'document.getElementById("ishikawa_button").disabled = true')); ?>
    <input type="hidden" name="rel_type" value="ro_analise">
    <input type="hidden" name="id" value="<?php echo $ro->id; ?>">
    <input type="hidden" name="type" value="ishikawa">
    <hr class="no-mtop" />
    <div class="col-md-12 row">
        <div class="col-md-9 row">
            <div class="form-group col-md-6">
                <label for="note_description">Método</label>
                <textarea class="form-control" name="method" rows="4" id="method" ><?php echo $ro->method; ?></textarea>
            </div>
            <div class="form-group col-md-6">
                <label for="note_description">Material / Matéria Prima</label>
                <textarea class="form-control" name="material" rows="4" id="material" ><?php echo $ro->material; ?></textarea>
            </div>
            <div class="form-group col-md-6">
                <label for="note_description">Mão de obra</label>
                <textarea class="form-control" name="labor" rows="4" id="labor" ><?php echo $ro->labor; ?></textarea>
            </div>
            <div class="form-group col-md-6">
                <label for="note_description">Máquina</label>
                <textarea class="form-control" name="machine" rows="4" id="machine" ><?php echo $ro->machine; ?></textarea>
            </div>
            <div class="form-group col-md-6">
                <label for="note_description">Medição</label>
                <textarea class="form-control" name="measurement" rows="4" id="measurement" ><?php echo $ro->measurement; ?></textarea>
            </div>
            <div class="form-group col-md-6">
                <label for="note_description">Meio Ambiente</label>
                <textarea class="form-control" name="environment" rows="4" id="environment" ><?php echo $ro->environment; ?></textarea>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="note_description">Problema / Efeito</label>
                <textarea class="form-control" name="problem" rows="20" id="problem" ><?php echo $ro->problem; ?></textarea>
            </div>

        </div>


        <div class="col-md-12 row" >
            <a class="btn btn-info pull-right" onclick="$('#log_analise').modal('show');" type="button">Atualizar ISHIKAWA</a>

            <div class="modal fade" id="log_analise" tabindex="-1" role="dialog" >

                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel">
                                <span class="edit-title text-white">Atualizar Ishikawa</span>
                            </h4>
                        </div>

                        <div class="modal-body">

                            <?php
                            echo render_select('members', $staff_departments, array('staffid', array('firstname', 'lastname')), 'Participantes', '', array('multiple' => 'true'));
                            ?>
                            <div class="form-group">
                                <label class="control-label">Descrição:</label>
                                <textarea class="form-control" rows="5" placeholder="Digite a descrição da atualização..." name="msg"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer" >

                            <button  class="btn btn-success" type="submit" id="ishikawa_button" >Atualizar</button>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<div class="col-md-12 row">

    <h4>HISTÓRICO</h4>
    <hr class="hr-panel-heading" />
    <?php
    $table_data = [];
    $table_data = array_merge($table_data, array(
        'Participantes',
        'Descrição',
        'Data'
    ));
    render_datatable($table_data, 'log');
    ?>
    <script>

        $(function () {
            reload_analise();
        });

        function reload_analise() {
            if ($.fn.DataTable.isDataTable('.table-log')) {
            $('.table-log').DataTable().destroy();
        }
            var Params = {};
            Params['rel_type'] = '[name="rel_type"]';
            Params['rel_id'] = '[name="id"]';
            initDataTable('.table-log', '<?php echo base_url(); ?>' + 'gestao_corporativa/Intranet_general/table_log', [0], [0], Params, [2, 'desc']);
        }

        $(document).ready(function () {
            $('#ishikawa_form').submit(function (e) {
                e.preventDefault();

                var formData = new FormData(this);


                $.ajax({
                    type: 'POST',
                    url: '<?php echo base_url("gestao_corporativa/Registro_ocorrencia/atualizar_ro"); ?>', // Use double quotes for PHP echo
                    data: formData,
                    processData: false, // Don't process the data
                    contentType: false, // Don't set contentType
                    success: function (response) {
                        
                        reload_analise();
                        alert_float('success', 'Campos Atualizados!');
                        $('#log_analise').modal('hide');
                        document.getElementById("ishikawa_button").disabled = false;
                    }
                });
            });
        });


        function atualizar_ishikawa() {
            //alert('chegou');
            //exit;
            var method = document.getElementById("method").value;
            var material = document.getElementById("material").value;
            var labor = document.getElementById("labor").value;
            var machine = document.getElementById("machine").value;
            var measurement = document.getElementById("measurement").value;
            var environment = document.getElementById("environment").value;
            var problem = document.getElementById("problem").value;
            $.ajax({
                type: "POST",
                url: "<?php echo base_url('gestao_corporativa/Registro_ocorrencia/atualizar_ro'); ?>",
                data: {
                    method: method,
                    material: material,
                    labor: labor,
                    machine: machine,
                    measurement: measurement,
                    environment: environment,
                    problem: problem,
                    type: 'ishikawa'
                },
                success: function (data) {
                    alert_float('success', 'Campos Atualizados!');
                }
            });
        }

    </script>
</div>
</div>