
<?php
$this->load->model('Departments_model');
$setores = $this->Departments_model->get();
?>
<?php
if ($categoria->rel_type == 'workflow') {
    if ($categoria->orientacoes_client != '' || count($doctos) > 0) {
        ?>
        <div class="alert alert-success alert-dismissible text-white fade show" role="alert">
            <span class="alert-icon align-middle">
                <span class="material-icons text-md">
                    info
                </span>
            </span>
            <?php if ($categoria->orientacoes_client != '') { ?>
                <span class="alert-text"><?php echo $categoria->orientacoes_client; ?></span>
                <br> 

            <?php } ?>

            <?php foreach ($doctos as $doc) { ?>
                <span class="alert-text" ><?php echo $doc['titulo']; ?>: </span> <a href="<?php echo base_url() . $doc['caminho']; ?>" download="<?php echo $doc['file']; ?>"><?php echo $doc['file']; ?></a><br>
            <?php } ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php } ?>
<?php } ?>
<?php
foreach ($campos as $campo) {
    $ob = '';
    if ($campo['obrigatorio'] == 1) {
        $style = '<span style="color: red;">*</span>';
        $required = 'required="true"';
    } else {
        $style = "";
        $required = '';
    }
     $label_style = 'style = "color: '.$campo['color'].'"';
    ?>
    <?php
    if ($campo['type'] == 'text' || $campo['type'] == 'number' || $campo['type'] == 'date' || $campo['type'] == 'time' || $campo['type'] == 'color' || $campo['type'] == '') {

        $more = '';
        if ($campo['days_max']) {
            $more = ' max="' . date('Y-m-d', strtotime("+" . $campo['days_max'] . " days", strtotime(date('Y-m-d')))) . '"';
        }
        if ($campo['days_max']) {
            $more .= ' min="' . date('Y-m-d', strtotime("-" . $campo['days_min'] . " days", strtotime(date('Y-m-d')))) . '"';
        }
        ?>

        <div class="col-sm-<?php echo $campo['tam_coluna']; ?> mt-2">
            <?php if($campo['type']){?>
            <label for="exampleFormControlInput1" class="form-label" <?php echo $label_style;?>><?php echo $style; ?><?php echo $campo['nome']; ?></label>
            <?php }?>
            <div class="input-group input-group-outline ">
                <input <?php echo $required; ?> name="<?php echo $campo['name']; ?>" id="<?php echo $campo['name']; ?>"  class="multisteps-form__input form-control" type="<?php echo $campo['type']; ?>" placeholder="<?php echo strtoupper($campo['nome']); ?>" <?php echo $more; ?>/>
            </div>
        </div>
    <?php } elseif ($campo['type'] == 'textarea') { ?>
        <!--<div class="form-group col-md-<?php echo $campo['tam_coluna']; ?>">
            <label><?php if ($asterisk == true) { ?> <small class="req text-danger">* </small><?php } ?> <?php echo $campo['nome']; ?></label>
            <textarea class="form-control" rows="3" placeholder="<?php echo $campo['nome']; ?>..." name="campo_<?php echo $campo['name']; ?>" <?php if ($campo['obrigatorio'] == 1) { ?> required="required" <?php } ?>></textarea>
        </div>-->
        <div class="col-sm-<?php echo $campo['tam_coluna']; ?> mt-2">
            <label for="exampleFormControlInput1" <?php echo $label_style;?> class="form-label" ><?php echo $style; ?><?php echo $campo['nome']; ?></label>
            <div class="input-group input-group-outline ">
                <textarea  <?php echo $required; ?> class="form-control" rows="5" placeholder="<?php echo $campo['nome']; ?>..." spellcheck="false" name="<?php echo $campo['name']; ?>" id="<?php echo $campo['name']; ?>"></textarea>
            </div>
        </div>

    <?php } elseif ($campo['type'] == 'checkbox') { ?>
        <div class="col-sm-<?php echo $campo['tam_coluna']; ?> mt-2">
            <div class="form-check">
                <input  <?php echo $required; ?> class="form-check-input" type="checkbox" value="1" id="fcustomCheck1" name="<?php echo $campo['name']; ?>">
                <label <?php echo $label_style;?> class="custom-control-label" for="customCheck1"><?php echo $style; ?><?php echo $campo['nome']; ?></label>
            </div>
        </div>
    <?php } elseif ($campo['type'] == 'setores') { ?>
        <?php ?>
        <div class="col-sm-<?php echo $campo['tam_coluna']; ?> mt-2">
            <label <?php echo $label_style;?> for="exampleFormControlInput1" class="form-label"><?php echo $style; ?><?php echo $campo['nome']; ?></label>
            <div class="input-group input-group-outline mb-4">
                <select  <?php echo $required; ?>  class="form-control" id="<?php echo $campo['name']; ?>" name="<?php echo $campo['name']; ?>">
                    <option value="" selected disabled>Selecione</option>
                    <?php foreach ($setores as $dep) { ?>
                        <option value="<?php echo $dep['departmentid']; ?>"><?php echo $dep['name']; ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>
    <?php } elseif ($campo['type'] == 'funcionarios') { ?>
        <?php ?>
        <div class="col-sm-<?php echo $campo['tam_coluna']; ?> mt-2">
            <label <?php echo $label_style;?> for="exampleFormControlInput1" class="form-label"><?php echo $style; ?><?php echo $campo['nome']; ?></label>
            <div class="input-group input-group-outline mb-4">
                <select <?php echo $required; ?>  class="form-control" id="<?php echo $campo['name']; ?>" name="<?php echo $campo['name']; ?>">
                    <option value="" selected disabled>Selecione</option>
                    <?php foreach ($staffs as $staff) { ?>
                        <option value="<?php echo $staff['staffid']; ?>"><?php echo $staff['firstname'] . ' ' . $staff['lastname']; ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>
    <?php } elseif ($campo['type'] == 'separador') { ?>
        <div class="card-header p-3 pb-0 col-md-12">
            <h6 class="mb-0" <?php echo $label_style;?>><?php echo $campo['nome']; ?></h6>
        </div>
        <?php
    } elseif ($campo['type'] == 'select' || $campo['type'] == 'multiselect') {
        $this->load->model('Registro_ocorrencia_model');
        $options = $this->Registro_ocorrencia_model->get_options($campo['id']);
        $name = $campo['name'];
        $multiple = '';
        if ($campo['type'] == 'multiselect') {
            $name = $campo['name'] . '[]';
            $multiple = ' multiple=""';
        }
        ?>
        <div class="col-sm-<?php echo $campo['tam_coluna']; ?> mt-2">
            <label <?php echo $label_style;?> for="exampleFormControlInput1" class="form-label"><?php echo $style; ?><?php echo $campo['nome']; ?></label>
            <div class="input-group input-group-outline mb-4">
                <select <?php echo $required; ?>  class="form-control" id="<?php echo $name; ?>" name="<?php echo $name; ?>" <?php echo $multiple; ?>>
                    <option value="" selected disabled>Selecione</option>
                    <?php foreach ($options as $option) { ?>
                        <option value="<?php echo $option['id']; ?>"><?php echo $option['option']; ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>

    <?php } elseif ($campo['type'] == 'file') { ?>
        <div class="col-sm-<?php echo $campo['tam_coluna']; ?> mt-2">
            <label <?php echo $label_style;?> for="exampleFormControlInput1" class="form-label"><?php echo $style; ?><?php echo $campo['nome']; ?></label>
            <div class="input-group input-group-outline ">
                <input  data-target="assets/intranet/arquivos/<?php echo  str_replace('.', '', $categoria->rel_type); ?>_arquivos/campo_file/" data-name_value="<?php echo  str_replace('.', '', $categoria->rel_type); ?>-<?php echo $campo['name']; echo uniqid(); ?>" <?php echo $required; ?> type="file" extension="<?php echo str_replace(['.', ' '], '', get_option('ticket_attachments_file_extensions')); ?>" filesize="<?php echo file_upload_max_size(); ?>" class="form-control" name="<?php echo $campo['name']; ?>" accept="<?php echo get_ticket_form_accepted_mimes(); ?>">

            </div>
        </div>
        <?php
    } elseif ($campo['type'] == 'reais') {
        ?>

        <div class="col-sm-<?php echo $campo['tam_coluna']; ?> mt-2">
            <label <?php echo $label_style;?> for="exampleFormControlInput1" class="form-label" ><?php echo $style; ?><?php echo $campo['nome']; ?></label>
            <div class="input-group input-group-outline ">
                <input <?php echo $required; ?> 
                    name="<?php echo $campo['name']; ?>" id="<?php echo $campo['name']; ?>"  
                    class=" form-control" 
                    type="text" placeholder="0,00" />
            </div>
        </div>
        <script>
            const <?php echo $campo['name']; ?> = document.getElementById('<?php echo $campo['name']; ?>');

        <?php echo $campo['name']; ?>.oninput = function () {
                formatarValor(<?php echo $campo['name']; ?>);
            };
        </script>
        <?php
    } elseif ($campo['type'] == 'list') {
        //echo 'jdxswd'; exit;
        if ($campo['list_id']) {
            $this->load->model('Company_model');
            //echo $campo['list_id']; exit;
            $options_list = $this->Company_model->get_list_option($campo['list_id']);
            ?>
            <div class="col-sm-<?php echo $campo['tam_coluna']; ?> mt-2">
                <label <?php echo $label_style;?> for="exampleFormControlInput1" class="form-label"><?php echo $style; ?><?php echo $campo['nome']; ?></label>
                <div class="input-group input-group-outline mb-4">
                    <select <?php echo $required; ?>  class="form-control" id="<?php echo $campo['name']; ?>" name="<?php echo $campo['name']; ?>[]" multiple="">
                        <option value="" selected disabled>Selecione</option>
                        <?php foreach ($options_list as $option) { ?>
                            <option value="<?php echo $option['id']; ?>"><?php echo $option['option']; ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
        <?php } ?>
        <?php
    }
}
?>
<?php if ($categoria->rel_type == 'workflow') { ?>
    <div class="button-row d-flex mt-4">
        <button class="btn bg-gradient-success ms-auto mb-0" type="submit" id="disabled">Salvar</button>
    </div>
<?php }
?>

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