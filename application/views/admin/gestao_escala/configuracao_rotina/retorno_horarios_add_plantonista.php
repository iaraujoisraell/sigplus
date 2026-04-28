<label>Horário Original</label>
<select name="horario_id_add_plantonista" id="horario_id_add_plantonista" required="true"  class="form-control">
    <option checked="true" value="">Selecione</option>
    <?php foreach ($horarios as $plano){
        
        ?>
     <option value="<?php echo $plano['id_horario']; ?>" <?php echo $selectd ?>><?php echo $plano['hora_inicio'].' - '.$plano['hora_fim']; ?></option>   ;
    <?php } ?>
</select>
<br>
<label>Horário Executado</label>
<select name="horario_executado_id_add_plantonista" id="horario_executado_id_add_plantonista" required="true"  class="form-control">
    <option checked="true" value="">Selecione</option>
    <?php foreach ($horarios as $plano){
        
        ?>
     <option value="<?php echo $plano['id_horario']; ?>" <?php echo $selectd ?>><?php echo $plano['hora_inicio'].' - '.$plano['hora_fim']; ?></option>   ;
    <?php } ?>
</select>