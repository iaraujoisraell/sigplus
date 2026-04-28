<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head_intranet_novo(); ?>
<?php $this->load->view('gestao_corporativa/css_background'); ?>
<div class="content">

    <div class="row">

        <div class="col-md-12">
            <div>
                <ol class="breadcrumb">
                    <li><a href="<?= base_url('gestao_corporativa/intranet'); ?>"><i class="fa fa-home"></i> Home </a></li> 
                    <li><a href="<?= base_url('gestao_corporativa/Cdc/list_'); ?>"><i class="fa fa-backward"></i> Controle Documental </a></li> 
                    <?php if ($id) { ?>
                        <li class=""> Nova Versão </li>
                    <?php } else { ?>
                        <li class=""> Novo </li>
                    <?php } ?>
                </ol>
            </div>
        </div>

        <?php if ($version == true) { ?>

            <div class="col-md-5">
                <div class="panel_s">
                    <div class="panel-heading">
                        <?php echo $cdc->codigo; ?> - Versão: <?php echo $cdc->numero_versao; ?>
                    </div>
                    <div class="panel-body ">
                        <div class="row">

                            <div class="col-md-4">

                                <div class="form-group">
                                    <label for="" class="control-label">TITULO:</label>
                                    <input type="text" class="form-control" name="titulo_old" required="required" value="<?php echo $cdc->titulo; ?>" disabled>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <?php
                                echo render_select('categoria_id_old', $categorias, array('id', 'titulo'), 'CATEGORIA', $cdc->categoria_id, array('disabled' => true));
                                ?>
                            </div>

                            <div class="col-md-4">



                                <?php
                                echo render_select('departmentid_old', $departments, array('departmentid', 'name'), 'DEPARTAMENTO', $cdc->setor_id, array('disabled' => true));
                                ?>


                            </div>
                            <div class="col-md-4">



                                <div class="form-group ">
                                    <label for="numero_versao"  class="control-label">VERSÃO</label>
                                    <input type="text" class="form-control" name="numero_versao_old" id="numero_versao_old" required="required" value="<?php echo $cdc->numero_versao; ?>" disabled>


                                </div>


                            </div>
                            <div class="col-md-4">



                                <div class="form-group ">
                                    <label for="sequencial"  class="control-label">SEQUÊNCIAL</label>
                                    <input type="text" id="sequence_view_old" class="form-control" name="sequence_view_old" required="required"  minlength="3" maxlength="3" disabled="true" value="<?php echo $cdc->sequence_view; ?>">


                                </div>


                            </div>
                            <div class="col-md-4">


                                <div class="form-group">
                                    <label for="bs_column" class="control-label">VALIDADE</label>
                                    <div class="input-group">

                                        <input type="number" class="form-control" id="validity_old" name="validity_old" value="<?php echo $cdc->validity; ?>" disabled>
                                        <span class="input-group-addon">MESES</span>
                                    </div>
                                </div>


                            </div>
                            <div class="col-md-12">
                                <?php echo render_textarea('descricao_doc_old', 'OBJETIVO', $cdc->descricao, array('disabled' => true), array(), '', ''); ?>
                            </div>
                            <?php ?>
                            <div class="col-md-12">
                                <div class="form-group clearfix">
                                    <div class="checkbox checkbox-primary">
                                        <input type="checkbox" name="required_old" id="required_old" checked value="1" disabled <?php
                                        if ($cdc->required == 1) {
                                            echo 'checked';
                                        }
                                        ?>>
                                        <label for="required">CIÊNCIA OBRIGATÓRIA</label>
                                    </div>
                                </div>
                            </div>



                        </div>
                    </div>


                </div>
                <?php echo form_close(); ?>
            </div>

        <?php } ?>
        <?php echo form_open_multipart('gestao_corporativa/cdc/salvar', array('id' => 'myForm')); ?>
        <div class="col-md-<?php
        if ($version == true) {
            echo '7';
        } else {
            echo '12';
        }
        ?>">
            <div class="panel_s">
                <div class="panel-heading">
                    NOVO DOCUMENTO - CDC
                </div>
                <div class="panel-body ">
                    <div class="row">
                        <div class="col-md-12">

                            <div class="row">
                                <div class="col-md-6 col-sm-12">
                                    <div class="select-notification-settings">

                                        <div class="radio radio-primary radio-inline">
                                            <input type="radio" name="immediately" value="0" id="immediately_false" checked onclick="document.getElementById('immediately_div').style.display = 'none';
                                                    $('.flow_').prop('disabled', false);
                                                    $('.selectpicker').selectpicker('refresh');
                                                    $('.file').prop('disabled', true);
                                                    $('.dt').prop('disabled', true);">
                                            <label for="immediately_false">PERCORRER FLUXO DE APROVAÇÃO</label>
                                        </div>

                                          <?php if (has_permission('cdc', '', 'publicacao_imediata') || is_admin()) { ?>

                                        <div class="radio radio-primary radio-inline">
                                            <input type="radio" name="immediately" id="immediately_true" value="1" onclick="document.getElementById('immediately_div').style.display = 'block';
                                                    $('.flow_').prop('disabled', true);
                                                    $('.selectpicker').selectpicker('refresh');
                                                    $('.file').prop('disabled', false);
                                                    $('.dt').prop('disabled', false);">
                                            <label for="immediately_true">PUBLICAR IMEDIATAMENTE</label>
                                        </div>

                                        <?php } ?>

                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12" style="display: none;" id="immediately_div">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="file" class="control-label">DOCUMENTO:</label>
                                                <div class="input-group">
                                                    <input data-name_value="cdc-file-automatically" type="file" required="true" disabled= "true" class="form-control file" name="file" id="file" data-target="assets/intranet/arquivos/cdc_arquivos/cdc/" accept=".pdf, .PDF">
                                                    <span class="input-group-btn">
                                                        <button class="btn btn-info p8-half"  type="button"><i class="fa fa-file"></i></button>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <?php echo render_date_input('data_publicacao', 'DATA PUBLICAÇÃO:', '', ['required' => true, 'disabled' => true], [], '', 'dt'); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr />

                        </div>



                        <?php
                        if ($id) {
                            $versao = $cdc->numero_versao;
                            $value_numero_versao = sprintf('%02d', intval($versao) + 1);
                            $seq = $cdc->sequence_view;
                            $seq_ = sprintf('%03d', intval($seq) + 1);
                        } else {
                            $value_numero_versao = '01';
                        }
                        ?>


                        <div class="col-md-4">



                            <div class="form-group">
                                <label for="" class="control-label">TITULO</label>
                                <input type="text" class="form-control" name="titulo" required="required" value="<?php echo $value_titulo; ?>">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <?php
                            $value = '';
                            $attr = array('required' => 'true');
                            if ($version == true) {
                                $value = $cdc->categoria_id;
                                $attr = array('disabled' => true);
                                echo "<input type='hidden' id='categoria_id' name='categoria_id' value='$value'>";
                                echo "<input type='hidden' id='id' name='id' value='".$id."'>";
                            }
                            echo render_select('categoria_id', $categorias, array('id', 'titulo'), 'CATEGORIA', $value, $attr);
                            ?>

                        </div>

                        <div class="col-md-2">



                            <?php
                            $value = '';
                            $attr = array('required' => 'true', 'onchange' => 'get_sequence(this.value);');
                            if ($version == true) {
                                $value = $cdc->setor_id;
                                $attr = array('disabled' => true);
                                echo "<input type='hidden' id='department' name='department' value='$value'>";
                            }
                            echo render_select('department', $departments, array('departmentid', 'name'), 'DEPARTAMENTO', $value, $attr);
                            ?>


                        </div>
                        <div class="col-md-1">



                            <div class="form-group ">
                                <label for="numero_versao"  class="control-label">VERSÃO</label>
                                <input type="text" class="form-control" name="numero_versao" id="numero_versao" required="required" value="<?php echo $value_numero_versao; ?>" minlength="2" maxlength="2">


                            </div>


                        </div>
                        <div class="col-md-1">



                            <div class="form-group ">
                                <label for="sequencial"  class="control-label">SEQUÊNCIAL</label>
                                <input type="text" id="sequence_view" class="form-control" name="sequence_view" required="required"  minlength="3" maxlength="3" value="<?php echo $seq_ ?>">


                            </div>


                        </div>
                        <div class="col-md-2">


                            <div class="form-group">
                                <label for="bs_column" class="control-label">VALIDADE</label>
                                <div class="input-group">

                                    <input type="number" class="form-control" id="validity" name="validity">
                                    <span class="input-group-addon">MESES</span>
                                </div>
                            </div>


                        </div>
                        <div class="col-md-12">
                            <?php echo render_textarea('descricao_doc', 'INFORMAÇÕES DO DOCUMENTO', '', array(), array(), '', ''); ?>
                        </div>

                        <div class="col-md-12" id="trocar">
                            <?php
                            if ($id) {
                                $return['flows'] = $this->Categorias_campos_model->get_categoria_fluxos($cdc->categoria_id);
                                $this->load->view('gestao_corporativa/cdc/retorno_fluxos', $return);

                                $this->load->model('Categorias_campos_model');
                                $campos = $this->Categorias_campos_model->get_categoria_campos($cdc->categoria_id);
                                $data['campos'] = $campos;
                                $data['categoria_id'] = $cdc->categoria_id;
                                $data['just_campos'] = true; 

                              //  print_r($data); exit;
                                $this->load->view('gestao_corporativa/categorias_campos/retorno_categoria', $data);
                            }
                            ?>

                        </div>
                        <div class="col-md-12">
                            <div class="form-group clearfix">
                                <div class="checkbox checkbox-primary">
                                    <input type="checkbox" name="required" id="required" checked value="1">
                                    <label for="required">CIÊNCIA OBRIGATÓRIA</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <i class="fa fa-question-circle" data-toggle="tooltip" data-title="ESTE LINKS ESTARÁ DISPONÍVEL PARA OS SEGUINTES DEPARTAMENTOS"></i> <label  class="control-label">DISPONÍVEL PARA SETORES:</label>
                            <div class="select-notification-settings">
                                <div class="radio radio-primary radio-inline">
                                    <input type="radio" id="no"  onclick="toggleCheckboxes(false)">
                                    <label for="no">NENHUM</label>
                                </div>
                                <div class="radio radio-primary radio-inline">
                                    <input type="radio" id="all"  onclick="toggleCheckboxes(true)">
                                    <label for="all">TODOS</label>
                                </div>
                            </div>
                            <br>

                            <?php
                            foreach ($departments as $department) {
                                $checked = '';
                                if (is_array($destinos)) {
                                    if (in_array($department['departmentid'], $destinos)) {
                                        $checked = 'checked';
                                    }
                                }
                                ?>
                                <div class="checkbox checkbox-primary col-md-3">

                                    <input type="checkbox" id="dep_<?php echo $department['departmentid']; ?>" name="departments[]" value="<?php echo $department['departmentid']; ?>" <?php echo $checked; ?> onclick="updateRadioButtons()">
                                    <label for="dep_<?php echo $department['departmentid']; ?>"><?php echo $department['name']; ?></label>
                                </div>
                            <?php } ?>
                        </div>



                    </div>
                </div>

                <div class="panel_s">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">



                                <div class="btn-group pull-right mleft4 " >
                                    <button type="submit" class="btn btn-primary" >
                                        Salvar
                                    </button>

                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
        <?php echo form_close(); ?>
    </div>
    <?php init_tail(); ?>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
                                        function toggleCheckboxes(selectAll) {
                                            $('input[name="departments[]"]').prop('checked', selectAll);
                                        }
                                        function updateRadioButtons() {
                                            var allChecked = true;
                                            var noneChecked = true;

                                            $('input[name="departments[]"]').each(function () {
                                                if ($(this).prop('checked')) {
                                                    noneChecked = false;
                                                } else {
                                                    allChecked = false;
                                                }
                                            });

                                            $('#all').prop('checked', allChecked);
                                            $('#no').prop('checked', noneChecked);
                                        }
    </script>

    <script>
        function get_sequence(value) {

            // Faz a requisição AJAX
            $.ajax({
                url: '<?php echo base_url('gestao_corporativa/cdc/get_sequence_by_departmentid'); ?>',
                type: 'POST', // ou 'GET' dependendo da sua implementação no CodeIgniter
                data: {departmentid: value}, // Parâmetros que você deseja enviar
                success: function (data) {
                    document.getElementById('sequence_view').disabled = false;
                    document.getElementById('sequence_view').value = data;
                },
                error: function (error) {
                    alert('Erro na requisição AJAX:', error);
                }
            });
        }
    </script>

    <script language="JavaScript">

        $(document).on('change', "#categoria_id", function () {
            var select = document.getElementById("categoria_id");
            var opcaoValor = select.options[select.selectedIndex].value;
            if (opcaoValor != "") {

                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url('gestao_corporativa/categorias_campos/retorno_categoria_campos?rel_type=cdc'); ?>",
                    data: {
                        categoria_id: opcaoValor
                    },
                    success: function (data) {
                        $('#trocar').html(data);
                        if (document.getElementById('immediately_true').checked == true) {

                            $('.flow_').prop('disabled', true);
                            $('.selectpicker').selectpicker('refresh');
                        }
                    }
                });

            } else {
                alert('Selecione euma categoria!');
            }
        });


        $(document).ready(function () {
            $('#myForm').submit(function (e) {
                e.preventDefault(); // Prevent the default form submission
                var uploadForm = $('#myForm');
                var formData = new FormData($('#myForm')[0]);
                // Inicialize um objeto que irá conter o mapeamento de campo de entrada para destino
                var fieldToTargetMap = {};
                var fieldValueMap = {};

                // Percorra cada campo de entrada de arquivo
                uploadForm.find('input[type="file"]').each(function () {
                    var field = $(this);
                    var fieldName = field.attr('name');
                    var target = field.data('target');
                    var value = field.data('name_value');
                    fieldToTargetMap[fieldName] = target;
                    fieldValueMap[fieldName] = value;
                });

                // Adicione o mapeamento ao FormData
                formData.append('fieldToTargetMap', JSON.stringify(fieldToTargetMap));
                formData.append('fieldValueMap', JSON.stringify(fieldValueMap));
                formData.append('target', '0');
                $.ajax({
                    type: 'POST',
                    url: '<?php echo base_url('controle_upload/All.php'); ?>', // Substitua com o URL do seu script PHP
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        var obj = JSON.parse(response);
                        $('form input[type="file"]').each(function () {
                            var fileInput = $(this);
                            if (fileInput.length > 0) {
                                var inputName = fileInput.attr('name');
                                fileInput.attr('type', 'text');
                                fileInput.val(obj[inputName]);

                            }
                        });


                        // Condition is satisfied, submit the form
                        $('#myForm').unbind('submit').submit();

                    }
                });
            });
        });

    </script>

    <script src="<?php echo base_url(); ?>assets/intranet/ckeditor/ckeditor.js"></script>
    <script>
        CKEDITOR.replace('descricao_doc');
    </script>
    <script>
        CKEDITOR.replace('descricao_doc_old');
    </script>



</body>
</html>
