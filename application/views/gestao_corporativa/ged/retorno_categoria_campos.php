

                    <?php
                    foreach ($campos as $campo) {
                        ?>
                        <?php if ($campo['type'] == 'text' || $campo['type'] == 'number' || $campo['type'] == 'date' || $campo['type'] == 'time' || $campo['type'] == 'color') { ?>
                            <div class="form-group">
                                <label for=""><?php echo $campo['nome']; ?></label>
                                <input name="campo_<?php echo $campo['name']; ?>" type="<?php echo $campo['type']; ?>" class="form-control" id="<?php echo $campo['nome']; ?>" placeholder="<?php echo $campo['nome']; ?>" 
                                       <?php if ($campo['obrigatorio'] = 1) { ?> required <?php } ?>>

                            </div>
                        <?php } elseif ($campo['type'] == 'textarea') { ?>
                            <div class="form-group">
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
                            <?php echo render_select('campo_' . $campo['name'], $setores, array('departmentid', 'name'), $campo['nome'], '', $obrigatorio, []); ?>
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
                            <?php echo render_select('campo_' . $campo['name'], $staffs, array('staffid', 'firstname', 'lastname'), $campo['nome'], '', $obrigatorio, []); ?>
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
                                    <?php echo render_select("$nome_campo", $options, array('id', 'option'), $campo['nome'], '', $if_multiple, []); ?>

                            <?php
                        }
                    }
                    ?>

    <script>

        $(document).ready(function () {
            init_selectpicker();
        });

    </script>
    <script>
    
    function filtro(){
        var CustomersServerParams = {};
    <?php foreach ($campos as $campo){?>
    CustomersServerParams['<?php echo $campo['name'].$campo['id'];?>'] = '[name="<?php echo $campo['name'];?>"]';
    <?php }?>
        alert(CustomersServerParams);
    initDataTable('.table-table', '<?php echo base_url(); ?>' + 'gestao_corporativa/Ged/table_categorias_dinamic', [0], [0], CustomersServerParams, [1, 'desc']);
    
    }
    
    </script>
