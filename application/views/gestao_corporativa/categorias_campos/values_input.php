<div class="row">


    <?php
    $this->load->model('Departments_model');
    $this->load->model('Staff_model');
    $this->load->model('Categorias_campos_model');
    
    $campos = $this->Categorias_campos_model->get_values($rel_id, $rel_type, $preenchido_por);
    
    //$setores = $this->Departments_model->get();
    

    foreach ($campos as $campo) {
        $asterisk = '';
        if ($campo['obrigatorio'] == 1) {
            $asterisk = true;
            $obrigatorio = 'required';
        } else {
            $obrigatorio = '';
        }
        ?>
        <?php if ($campo['tipo_campo'] == 'text' || $campo['tipo_campo'] == 'number' || $campo['tipo_campo'] == 'date' || $campo['tipo_campo'] == 'time' || $campo['tipo_campo'] == 'color') { ?>
            <?php echo render_input($campo['name_campo'], $campo['nome_campo'], $campo['value'], $campo['tipo_campo'],[], [], ' col-md-' . $campo['tamanho'], '', $asterisk); ?>
        <?php } elseif ($campo['tipo_campo'] == 'textarea') { ?>
            <?php echo render_textarea($campo['name_campo'], $campo['nome_campo'], $campo['value'], array($disabled => $disabled), [], ' col-md-' . $campo['tamanho'], '', $asterisk) ?>
        <?php } elseif ($campo['tipo_campo'] == 'separador') { ?>
            <hr size="20" width="100%" align="center" > <h5 style="padding-left: 15px;"><strong><?php echo strtoupper($campo['nome_campo']); ?></strong></h5>  <hr size="20" width="100%" align="center">
        <?php } elseif ($campo['tipo_campo'] == 'setores') { ?>
            <?php
            ?>
            <?php echo render_select($campo['name_campo'], $setores, array('departmentid', 'name'), $campo['nome_campo'], $campo['value'], array($disabled => $disabled, $obrigatorio => $obrigatorio), [], '  col-md-' . $campo['tamanho'], '', true, $asterisk); ?>
        <?php } elseif ($campo['tipo_campo'] == 'funcionarios') { ?>
            <?php
            
            $obrigatorio_staff = array($obrigatorio => true, $disabled => true);
            
            ?>
            <?php echo render_select($campo['name_campo'], $colaboradores, array('staffid', 'firstname', 'lastname'), $campo['nome_campo'], $campo['value'], array($disabled => true, $obrigatorio => true), [], '  col-md-' . $campo['tamanho'], '', true, $asterisk); ?>
        <?php } elseif ($campo['tipo_campo'] == 'checkbox') { ?>
            <div class="icheck-primary d-inline col-md-<?php echo $campo['tamanho']; ?>">
                <input type="checkbox" id="<?php echo $campo['name_campo']; ?>" <?php echo $disabled; ?> name="campo_<?php echo $campo['name_campo']; ?>"
                <?php
                if ($campo['value'] == 'on') {
                    echo 'checked';
                }
                ?>>
                <label for="checkboxPrimary1">
                    <?php echo $campo['nome_campo']; ?>
                </label>
            </div>
            <?php
        } elseif ($campo['tipo_campo'] == 'select' || $campo['tipo_campo'] == 'multiselect') {
            if ($campo['tipo_campo'] == 'multiselect') {

                $selected = explode(',', $campo['value']);
                //print_r($selected);
                $if_multiple = array("multiple" => true, $disabled => true, $obrigatorio => true);
            } else {
                $selected = $campo['value'];
                $if_multiple = array($disabled => true, $obrigatorio => true);
            }
            $nome_campo = $campo['name_campo'];

            $this->load->model('Registro_ocorrencia_model');

            $options = $this->Registro_ocorrencia_model->get_options($campo['id_campo']);
            ?>
            <?php echo render_select("$nome_campo", $options, array('id', 'option'), $campo['nome_campo'], $selected, $if_multiple, [], '  col-md-' . $campo['tamanho'], '', true, $asterisk); ?>
            <?php
        } elseif ($campo['tipo_campo'] == 'file') {
            
            $more = '<span class="input-group-btn">
                                                    <button class="btn btn-success  p8-half" data-max="1" type="button"><i class="fa fa-file"></i></button>
                                                </span>';
          
                echo '<div class="col-md-'.$campo['tamanho'].'">'
                        . '<div class="form-group w-100">
                                            <label for="attachment" class="control-label">'.$campo['nome_campo'].'</label>
                                            <div class="input-group w-100">
                                                <input type="file"  class="form-control" name="'.$campo['name_campo'].'">
                                                
                                            </div>
                                        </div>
                                        </div>';
            
        }
    }
    ?>
</div>