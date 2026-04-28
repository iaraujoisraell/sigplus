<!-- Carrega o nome dos médicos duplicados no próprio filto -->
<label>Medicos duplicados</label>
<?php //print_r($duplicados); exit;?>
<select name="med_duplicados" id="med_duplicados" onchange="procedimentos_table_reload();" class="form-control">
    <option checked="true" value="">Selecione</option>
    <?php 
    foreach ($medicos as $med) {
         $hora_format_st = date('H:i', strtotime($med['hora_inicio']));
         $hora_format_end = date('H:i', strtotime($med['hora_fim']));
         $data_format = date('d/m/Y', strtotime($med['start']));
        ?>
            
            <option value="<?php echo $med['medico_escalado_id'].'_'.$med['dia'].'_'.$med['horario_id']; ?>">
                <?php echo $med['escalado'].' ( '.$data_format.' '.$hora_format_st.' - '.$hora_format_end.' ) '; ?></option>
    <?php } ?>
</select>
<br>