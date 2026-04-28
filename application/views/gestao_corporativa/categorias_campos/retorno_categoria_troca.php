<?php
//echo 'aqui'; exit;       
if ($categoria_id) {
    $this->load->model('Categorias_campos_model');
    $categoria = $this->Categorias_campos_model->get_categoria($categoria_id);
    $rel_type = $categoria->rel_type;
}
?>
<?php if ($just_campos != true) { ?>
    <div class="panel_s">
        <div class="panel-heading">
            Campos Obrigatórios da Categoria
        </div>
        <div class="panel-body">
            <?php if ($categoria->orientacoes_client || count($doctos) > 0) { ?>
                <div class="panel_s" style="margin-bottom: 10px;">
                    <div class="panel-heading">
                        ORIENTAÇÕES DA CATEGORIA
                    </div>
                    <div class="panel-body">
                        <?php if ($categoria->orientacoes_client) { ?>
                            <span class="bold "><?php echo strtoupper($categoria->orientacoes_client); ?></span>
                            <br><br>
                        <?php } ?>
                        <?php foreach ($doctos as $doc) { ?>
                            <span class="text-muted" style="margin-top: 5px;"><?php echo $doc['titulo']; ?>: </span> <a href="<?php echo base_url() . $doc['caminho']; ?>" download="<?php echo $doc['file']; ?>"><?php echo $doc['file']; ?></a><br>
                        <?php } ?>
                    </div>
                </div>
            <?php } ?>
            <div class="row">
                <div class="col-md-12 mbot20 before-ticket-message">
                <?php } ?>
                <div class="row">
                    <?php
                    foreach ($campos as $campo) {
                        if (!is_array($just_type) || in_array($campo['type'], $just_type)) {
                            $ob = '';
                            $asterisk = false;
                            if ($campo['obrigatorio'] == 1 and $without_required != true) {
                                $asterisk = true;
                            }
                            $label_style = 'style = "color: ' . $campo['color'] . '"';
                            $value = $campo['value'];
                            ?>
                            <?php
                            if ($campo['type'] == 'text' || $campo['type'] == 'number' || $campo['type'] == 'date' || $campo['type'] == 'time' || $campo['type'] == 'color') {
                                $more = '';
                                if ($campo['days_max']) {
                                    $more = ' max="' . date('Y-m-d', strtotime("+" . $campo['days_max'] . " days", strtotime(date('Y-m-d')))) . '"';
                                }
                                if ($campo['days_max']) {
                                    $more .= ' min="' . date('Y-m-d', strtotime("-" . $campo['days_min'] . " days", strtotime(date('Y-m-d')))) . '"';
                                }
                                ?>
                                <div class="form-group col-md-<?php echo $campo['tam_coluna']; ?>">

                                    <label for="" class="control-label" <?php echo $label_style; ?>><?php if ($asterisk == true) { ?> <small class="req text-danger">* </small> <?php } ?> <?php echo $campo['nome']; ?></label>
                                    <input name="campo_<?php echo $campo['name']; ?>" type="<?php echo $campo['type']; ?>" class="form-control" id="<?php echo $campo['nome']; ?>" placeholder="<?php echo $campo['nome']; ?>" 
                                           <?php if ($asterisk == true) { ?> required <?php } ?> <?php echo $more; ?> value="<?php echo $value; ?>">
                                </div>
                            <?php } elseif ($campo['type'] == 'textarea') { ?>
                                <div class="form-group col-md-<?php echo $campo['tam_coluna']; ?>">
                                    <label <?php echo $label_style; ?>><?php if ($asterisk == true) { ?> <small class="req text-danger">* </small><?php } ?> <?php echo $campo['nome']; ?></label>
                                    <textarea class="form-control" rows="3" placeholder="<?php echo $campo['nome']; ?>..." name="campo_<?php echo $campo['name']; ?>" <?php if ($asterisk == true) { ?> required="required" <?php } ?>><?php
                                        if ($value) {
                                            echo $value;
                                        } else {
                                            echo $campo['default_text'];
                                        }
                                        ?></textarea>
                                </div>
                            <?php } elseif ($campo['type'] == 'setores') { ?>
                                <?php
                                $this->load->model('Departments_model');
                                $setores = $this->Departments_model->get();
                                $nome = 'campo_' . $campo['name'];
                                $attrs = [];
                                if ($asterisk == true) {
                                    $attrs['required'] = 'required';
                                }
                                if ($campo['just_one'] == 0) {
                                    $attrs['multiple'] = true;
                                    $nome = 'campo_' . $campo['name'] . '[]';
                                }
                                ?>
                                <?php echo render_select($nome, $setores, array('departmentid', 'name'), $campo['nome'], $value, $attrs, [], '  col-md-' . $campo['tam_coluna'], '', true, $asterisk, $label_style); ?>
                            <?php } elseif ($campo['type'] == 'funcionarios') { ?>
                                <?php
                                $this->load->model('Staff_model');
                                $staffs = $this->Staff_model->get();
                                if ($asterisk == true) {
                                    $obrigatorio = array("required" => "required");
                                } else {
                                    $obrigatorio = [];
                                }
                                ?>
                                <?php echo render_select('campo_' . $campo['name'], $staffs, array('staffid', 'firstname', 'lastname'), $campo['nome'], $value, $obrigatorio, [], '  col-md-' . $campo['tam_coluna'], '', true, $asterisk, $label_style); ?>
                            <?php } elseif ($campo['type'] == 'separador') { ?>
                                <hr size="20" width="100%" align="center" style="margin-bottom: 2px;" > <h5 style="padding-left: 20px; color: <?php echo $campo['color']; ?>; font-weight: bold;"><?php echo strtoupper($campo['nome']); ?></h5>  <hr style="margin-top: 2px;" size="20" width="100%" align="center">
                            <?php } elseif ($campo['type'] == 'select' || $campo['type'] == 'multiselect') { ?>
                                <?php
                                if ($campo['type'] == 'multiselect') {
                                    //print_r($selected);
                                    if ($asterisk == true) {
                                        $if_multiple = array("multiple" => true, "required" => "required");
                                    } else {
                                        $if_multiple = array("multiple" => true);
                                    }
                                } else {
                                    if ($asterisk == true) {
                                        $if_multiple = array("required" => "required");
                                    } else {
                                        $if_multiple = [];
                                    }
                                }
                                $nome_campo = 'campo_' . $campo['name'];
                                if ($campo['type'] == 'multiselect') {
                                    $nome_campo .= '[]';
                                }

                                if (!method_exists($this->Categorias_campos_model, 'get_options')) {
                                    $this->load->model('Categorias_campos_model');
                                } 
                                //print_r($campo);
                                $options = $this->Categorias_campos_model->get_options($campo['id']);
                                ?>
                                <?php echo render_select("$nome_campo", $options, array('id', 'option'), $campo['nome'], $value, $if_multiple, [], '  col-md-' . $campo['tam_coluna'], '', true, $asterisk, $label_style); ?>
                            <?php } elseif ($campo['type'] == 'file') { ?>
                                <div class="form-group col-md-<?php echo $campo['tam_coluna']; ?>">
                                    <label for="campo_<?php echo $campo['name']; ?>" class="control-label" <?php echo $label_style; ?>><?php if ($asterisk == true) { ?> <small class="req text-danger">* </small> <?php } ?> <?php echo $campo['nome']; ?></label>
                                    <div class="input-group">
                                        <input data-name_value="<?php echo str_replace('.', '', $categoria->rel_type); ?>-<?php echo $campo['categoria_id']; ?>-<?php echo $campo['name']; ?>" data-target="assets/intranet/arquivos/<?php echo str_replace('.', '', $categoria->rel_type); ?>_arquivos/campo_file/" type="file" extension="<?php echo str_replace(['.', ' '], '', get_option('ticket_attachments_file_extensions')); ?>" filesize="<?php echo file_upload_max_size(); ?>" class="form-control" name="campo_<?php echo str_replace('.', '_', $campo['name']); ?>" accept="<?php echo get_ticket_form_accepted_mimes(); ?>"  <?php if ($asterisk == true) { ?> required="true" <?php } ?>>
                                        <span class="input-group-btn">
                                            <button class="btn btn-info  p8-half" type="button"><i class="fa fa-file"></i></button>
                                        </span>
                                    </div>
                                </div>
                                <?php
                            } elseif ($campo['type'] == 'list') {
                                //echo 'jdxswd'; exit;
                                if ($campo['list_id']) {
                                    $this->load->model('Company_model');
                                    $options_list = $this->Company_model->get_list_option($campo['list_id']);

                                    $nome = 'campo_' . $campo['name'];
                                    $attrs = [];
                                    if ($asterisk == true) {
                                        $attrs['required'] = 'required';
                                    }
                                    if ($campo['just_one'] == 0) {
                                        $attrs['multiple'] = true;
                                        $nome = 'campo_' . $campo['name'] . '[]';
                                    }
                                    echo render_select($nome, $options_list, array('id', 'option'), $campo['nome'], $value, $attrs, [], '  col-md-' . $campo['tam_coluna'], '', true, $asterisk, $label_style);
                                    ?>
                                <?php } ?>
                                <?php
                            } elseif ($campo['type'] == 'reais') {
                                ?>
                                <div class="form-group col-md-<?php echo $campo['tam_coluna']; ?>">

                                    <label for="" <?php echo $label_style; ?>><?php if ($asterisk == true) { ?> <small class="req text-danger">* </small> <?php } ?> <?php echo $campo['nome']; ?></label>
                                    <div class="input-group">
                                        <span class="input-group-addon">R$</span>
                                        <input name="campo_<?php echo $campo['name']; ?>" type="text" class="form-control" id="<?php echo $campo['nome']; ?>" placeholder="0,00" 
                                               <?php if ($asterisk == true) { ?> required <?php } ?> onkeyup="formatarValor(this)" value="<?php $value; ?>" >
                                    </div>
                                </div>
                                <?php
                            }
                        }
                    }
                    ?>
                </div>
                <?php if ($just_campos != true) { ?>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php /* if ($type == 'workflow' || $type == 'r.o') { ?>
    <div class="panel_s">
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="btn-group pull-right mleft4 " >
                        <button type="submit" class="btn btn-primary" id="disabled" >
                            Salvar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } */?>
<script>
    $(document).ready(function () {
        init_selectpicker();
    });
</script>
<script>
    function formatarValor(input) {
        let value = input.value.replace(/\D/g, '');
        value = value.replace(/^0+/, '');
        if (value.length > 0) {
            var centavos = value.substring(value.length - 2);
            if (centavos.length == '1') {
                var centavos = '0' + centavos;
            }
            if (value.length <= 2) {
                value = 0;
            } else {
                value = value.slice(0, -2);
                value = value.replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.');
            }

            input.value = value + ',' + centavos;
        } else {
            input.value = '';
        }
    }
</script>


