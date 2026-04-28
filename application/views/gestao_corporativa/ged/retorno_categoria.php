<div class="panel_s">
    <div class="panel-heading">
        Campos Obrigatórios da Categoria
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-12 mbot20 before-ticket-message">
                <div class="row">

                    <?php
                    foreach ($campos as $campo) {
                        ?>
                        <?php if ($campo['type'] == 'text' || $campo['type'] == 'number' || $campo['type'] == 'date' || $campo['type'] == 'time' || $campo['type'] == 'color') { ?>
                            <div class="form-group col-md-<?php echo $campo['tam_coluna']; ?>">
                                <label for=""><?php echo $campo['nome']; ?></label>
                                <input name="campo_<?php echo $campo['name']; ?>" type="<?php echo $campo['type']; ?>" class="form-control" id="<?php echo $campo['nome']; ?>" placeholder="<?php echo $campo['nome']; ?>" 
                                       <?php if ($campo['obrigatorio'] = 1) { ?> required <?php } ?>>

                            </div>
                        <?php } elseif ($campo['type'] == 'textarea') { ?>
                            <div class="form-group col-md-<?php echo $campo['tam_coluna']; ?>">
                                <label><?php echo $campo['nome']; ?></label>
                                <textarea class="form-control" rows="3" placeholder="<?php echo $campo['nome']; ?>..." name="campo_<?php echo $campo['name']; ?>" <?php if ($campo['obrigatorio'] == 1) { ?> required="required" <?php } ?>></textarea>
                            </div>

                        <?php } elseif ($campo['type'] == 'setores') { ?>
                            <?php
                            $this->load->model('Departments_model');
                            $setores = $this->Departments_model->get();
                            if ($campo['obrigatorio'] == 1) {
                                $obrigatorio = array("required" => "required");
                            } else {
                                $obrigatorio = [];
                            }
                            ?>
                            <?php echo render_select('campo_' . $campo['name'], $setores, array('departmentid', 'name'), $campo['nome'], '', $obrigatorio, [], '  col-md-' . $campo['tam_coluna']); ?>
                        <?php } elseif ($campo['type'] == 'funcionarios') { ?>
                            <?php
                            $this->load->model('Staff_model');
                            $staffs = $this->Staff_model->get();
                            if ($campo['obrigatorio'] == 1) {
                                $obrigatorio = array("required" => "required");
                            } else {
                                $obrigatorio = [];
                            }
                            ?>
                            <?php echo render_select('campo_' . $campo['name'], $staffs, array('staffid', 'firstname', 'lastname'), $campo['nome'], '', $obrigatorio, [], '  col-md-' . $campo['tam_coluna']); ?>
                        <?php } elseif ($campo['type'] == 'separador') { ?>
                            <hr size="20" width="100%" align="center" > <h5 style="padding-left: 15px;"><strong><?php echo strtoupper($campo['nome']); ?></strong></h5>  <hr size="20" width="100%" align="center">
                                <?php } elseif ($campo['type'] == 'select' || $campo['type'] == 'multiselect') { ?>
                                    <?php
                                    

                                    if ($campo['type'] == 'multiselect') {
                                        //print_r($selected);
                                        if ($campo['obrigatorio'] == 1) {
                                            $if_multiple = array("multiple" => true, "required" => "required");
                                        } else {
                                            $if_multiple = array("multiple" => true);
                                        }
                                    } else {
                                        if ($campo['obrigatorio'] == 1) {
                                            $if_multiple = array("required" => "required");
                                        } else {
                                            $if_multiple = [];
                                        }
                                    }
                                    $nome_campo = 'campo_' . $campo['name'];
                                    if ($campo['type'] == 'multiselect') {
                                        $nome_campo .= '[]';
                                    }
                                    $this->load->model('Ged_model');
                                    $options = $this->Ged_model->get_options($campo['id']);
                                    ?>
                                    <?php echo render_select("$nome_campo", $options, array('id', 'option'), $campo['nome'], '', $if_multiple, [], '  col-md-' . $campo['tam_coluna']); ?>

                            <?php
                        }
                    }
                    ?>

                </div>
            </div>
        </div>

    </div>
    <script>

        $(document).ready(function () {
            init_selectpicker();
        });

    </script>
</div>
