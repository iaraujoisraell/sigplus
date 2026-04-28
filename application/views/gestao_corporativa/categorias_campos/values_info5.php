<?php
if($categoria->rel_type){
  $rel_type = $categoria->rel_type;  
} 
if($rel_type){
    $rel_type = $rel_type;
} else {
    $rel_type = 'ro';
}
foreach ($campos as $campo) {
    if ($campo['tipo_campo'] == 'separador') {
        echo '<hr size="20" width="100%" align="center" style="margin-bottom: 2px;"> <h6 style="padding-left: 25px; color:'.$campo['color'].';">' . strtoupper($campo['nome_campo']) . '</h6>  '
                . '<hr size="20" width="100%" align="center" style="margin-top: 2px;">';
    } else {
        ?>
<span class="staff_logged_time_text text-success" style=" color: <?php echo $campo['color'];?>;"><?php echo $campo['nome_campo']; ?>: </span><span class="staff_logged_time_text text-dark">
            <?php echo get_value($rel_type, $campo['value'], $campo['tipo_campo']);?></span><br>
        <?php
    }
}
?>

