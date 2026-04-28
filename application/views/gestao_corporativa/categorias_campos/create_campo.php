  
<div class="form-group col-md-12">

    <label for="" class="control-label"><?php echo $nome; ?></label>
    <?php if ($type == 'textarea') { ?>
        <textarea class="form-control" rows="3" placeholder="<?php echo $nome; ?>..." name="value" required="required" ></textarea>
    <?php } elseif ($type == 'file') { ?>

        <div class="input-group">
            <input type="file"  class="form-control" name="value" data-target="assets/intranet/arquivos/<?php echo  str_replace('.', '', $rel_type); ?>_arquivos/more/<?php echo $rel_id;?>/" data-name_value="RO-NEWATTACHMENT<?php echo uniqid(); ?>">
            <span class="input-group-btn">
                <button class="btn btn-success p8-half" data-max="1" type="button"><i class="fa fa-file"></i></button>
            </span>
        </div>
        <div id="preview"></div>

    <?php } elseif ($type == 'reais') { ?>
        <input name="value" type="text" class="form-control" id="value" placeholder="<?php echo $nome; ?>" required onkeyup="formatarValor(this);">
    <?php } else { ?>
        <input name="value" type="<?php echo $type; ?>" class="form-control" id="value" placeholder="<?php echo $nome; ?>" required>
    <?php } ?>

</div>
