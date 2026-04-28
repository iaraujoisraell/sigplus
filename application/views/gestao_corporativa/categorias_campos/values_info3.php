<?php
if ($categoria->rel_type) {
    $rel_type = $categoria->rel_type;
}
if ($rel_type) {
    $rel_type = $rel_type;
} else {
    $rel_type = 'ro';
}
if (!$col_md) {
    if (count($campos) == 1) {
        $col_md = '12';
    } else {
        $col_md = '6';
    }
}
foreach ($campos as $campo) {
    if ($campo['tipo_campo'] == 'separador') {
        echo '<hr size="20" width="100%" align="center" style="margin-bottom: 2px;"> <h6 style="padding-left: 25px; color:' . $campo['color'] . ';">' . strtoupper($campo['nome_campo']) . '</h6>  '
        . '<hr size="20" width="100%" align="center" style="margin-top: 2px;">';
    } else {
        ?>
        <p class="text-muted col-md-<?php if (!$col_md) {
            echo '6';
        } else {
            echo $col_md;
        } ?> text-<?php echo $color; ?>" style="margin-top: 5px;">
            <strong style="text-transform: uppercase;  font-weight: bold; color: <?php echo $campo['color']; ?>;"><?php echo $campo['nome_campo']; ?>: </strong>
        <?php echo get_value($rel_type, $campo['value'], $campo['tipo_campo']); ?>
        </p>
        <?php
    }
}
?>

