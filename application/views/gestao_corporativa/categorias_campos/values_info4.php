<?php
if($categoria->rel_type){
  $rel_type = $categoria->rel_type;  
} 
if($rel_type){
    $rel_type = $rel_type;
} else {
    $rel_type = 'ro';
}
if(count($campos) == 1){
    $col = '12';
} else {
     $col = '6';
}
foreach ($campos as $campo) {
    ?>
    <?php
    if ($campo['tipo_campo'] == 'separador') {
       echo '<hr size="20" width="100%" align="center" style="margin-bottom: 2px;"> <h6 style="padding-left: 25px; color:'.$campo['color'].';">' . strtoupper($campo['nome_campo']) . '</h6>  '
                . '<hr size="20" width="100%" align="center" style="margin-top: 2px;">';
    } else {
        ?>
        <p class="text-muted col-md-<?php echo $col;?>" style="">
            <strong style="text-transform: uppercase;  font-weight: bold; color: <?php echo $campo['color'];?>;"><?php echo $campo['nome_campo']; ?>: </strong>
                <?php echo get_value($rel_type, $campo['value'], $campo['tipo_campo']);?>
        </p>
    <?php }
} ?>

