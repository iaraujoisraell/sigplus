<?php
foreach ($campos as $campo) {
    if($campo['tipo_campo'] != 'separador'){
    $label_style = 'style = "color: '.$campo['color'].'"';
    ?>

        <span class="mb-2 text-sm font-weight-bold" <?php echo $label_style;?>>
            <?php echo $campo['nome_campo']; ?>:
        </span>
        <span class="text-dark text-sm ">
            <?php echo get_value($rel_type, $campo['value'], $campo['tipo_campo']);?>

        </span><br>
    
    <?php
}
}
?>




