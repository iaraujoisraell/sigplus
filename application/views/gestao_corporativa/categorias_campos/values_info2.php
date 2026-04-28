<?php
if($categoria->rel_type){
  $rel_type = $categoria->rel_type;  
} 
if($rel_type){
    $rel_type = $rel_type;
} else {
    $rel_type = 'ro';
}
if (count($campos) > 0) {
    $quantidade = count($campos);
    if (!$valor % 2 != 0) {
        $quantidade++;
    }
    $dividido_por_dois = $quantidade / 2;
    $campos = array_chunk($campos, $dividido_por_dois);
    ?>
    <div class="row">
        <div class="col-md-6">
            <table class="table no-margin project-overview-table">
                <tbody>
                    <?php
                    foreach ($campos[0] as $campo) {
                        ?>
                        <?php
                        if ($campo['tipo_campo'] != 'separador') {
                            ?>
                            <tr class="project-overview-id">
                                <td class="bold" style="color: <?php echo $campo['color'];?>;"><?php echo $campo['nome_campo']; ?>:</td>
                                <td>
                                    <?php echo get_value($rel_type, $campo['value'], $campo['tipo_campo']);?>
                                </td>
                            </tr>
                            <?php
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <div class="col-md-6">
            <table class="table no-margin project-overview-table">
                <tbody>
                    <?php
                    foreach ($campos[1] as $campo) {
                        ?>
                        <?php
                        if ($campo['tipo_campo'] != 'separador') {
                            ?>
                            <tr class="project-overview-id">
                                <td class="bold" style="color: <?php echo $campo['color'];?>;"><?php echo $campo['nome_campo']; ?>:</td>
                                <td>
                                    <?php echo get_value($rel_type, $campo['value'], $campo['tipo_campo']);?>
                                </td>
                            </tr>
            <?php
        }
    }
    ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php
}?>