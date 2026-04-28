<label>Horários</label>
<select name="horario_id" id="horario_id" required="true" onchange="procedimentos_table_reload();" class="form-control">
    <option checked="true" value="">Selecione</option>
    <?php foreach ($horarios as $plano){
        
        ?>
     <option value="<?php echo $plano['id_horario']; ?>" <?php echo $selectd ?>><?php echo $plano['hora_inicio'].' - '.$plano['hora_fim']; ?></option>   ;
    <?php } ?>
</select>
<br>