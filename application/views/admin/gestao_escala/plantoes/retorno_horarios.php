<label>Horários</label>
<select name="config_id" id="config_id" required="true" class="form-control">
    <option checked="true" value="">Selecione</option>
    <?php foreach ($horarios as $plano){
        
        ?>
     <option value="<?php echo $plano['id']; ?>" <?php echo $selectd ?>><?php echo $plano['hora_inicio'].' - '.$plano['hora_fim']; ?></option>   ;
    <?php } ?>
</select>
<br>