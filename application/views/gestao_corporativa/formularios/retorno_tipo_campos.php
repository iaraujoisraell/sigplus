
<div class="col-md-12" style="margin-top: 10px;">
    <div class="row">
        <?php
        foreach ($campos as $campo) {
            ?>
            <?php if ($campo['type'] == 'text' || $campo['type'] == 'number' || $campo['type'] == 'date' || $campo['type'] == 'datetime-local' || $campo['type'] == 'color') { ?>
                <div class="form-group col-md-<?php echo $campo['tam_coluna']; ?>">
                    <label for="exampleInputEmail1"><?php echo $campo['nome']; ?></label>
                    <input name="values[]" type="<?php echo $campo['type']; ?>" class="form-control" id="<?php echo $campo['nome']; ?>" placeholder="<?php echo $campo['nome']; ?>" required>

                </div>
            <?php } elseif ($campo['type'] == 'textarea') { ?>
                <div class="form-group col-md-<?php echo $campo['tam_coluna']; ?>">
                    <label><?php echo $campo['nome']; ?></label>
                    <textarea class="form-control" rows="5" placeholder="<?php echo $campo['nome']; ?> ..." name="values[]" required> </textarea>
                </div>

            <?php } elseif ($campo['type'] == 'checkbox') { ?>
                <div class="icheck-primary d-inline col-md-<?php echo $campo['tam_coluna']; ?>">
                    <input type="checkbox" id="checkboxPrimary1" name="values[]" required>
                    <label for="checkboxPrimary1">
                        <?php echo $campo['nome']; ?>
                    </label>
                </div>
            <?php } elseif ($campo['type'] == 'select' || $campo['type'] == 'multiselect') { ?>
                <div class="form-group col-md-<?php echo $campo['tam_coluna']; ?>">
                    <label><?php echo $campo['nome']; ?></label>
                    <?php
                    $options = explode(',', $campo['options']);
                    ?>
                    <select class="form-control" name="<?php if ($campo['type'] == 'multiselect') {
                echo 'values_multiple[]';
            } else {
                echo 'values[]';
            } ?>" <?php if ($campo['type'] == 'multiselect') {
                    echo 'multiple';
                } ?> required>
                        <option value="" disabled>Selecione</option>
                <?php foreach ($options as $op): ?>
                            <option value="<?php echo $op; ?>"><?php echo $op; ?></option>
                <?php endforeach; ?>
                    </select>
                </div>

    <?php }


    if($campo['type'] == 'multiselect') {
        ?>

        <input name="campo_multiple" type="hidden" class="form-control" value="<?php echo $campo['id']; ?>">
    <?php } else {// ||  || $campo['tipo'] == 'datetime' || $campo['tipo'] == 'color' 
        ?>
                <input name="campos[]" type="hidden" class="form-control" value="<?php echo $campo['id']; ?>">
        <?php } }?>
    </div>
</div>