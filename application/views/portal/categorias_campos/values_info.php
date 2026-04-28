<?php
foreach ($campos as $campo) {
    $label_style = 'style = "color: '.$campo['color'].'"';
    ?>

    <div class="d-flex justify-content-between">
        <span class="mb-2 text-sm" <?php echo $label_style;?>>
            <?php echo $campo['nome_campo']; ?>:
        </span>
        <span class="text-dark text-sm font-weight-bold ms-2">
            <?php echo get_value($rel_type, $campo['value'], $campo['tipo_campo']);?>

        </span>
    </div>
    <?php
}
?>




