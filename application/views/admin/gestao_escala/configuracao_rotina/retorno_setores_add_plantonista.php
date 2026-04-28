<label>Setores</label>
<select name="setor_id_add_plantonista" id="setor_id_add_plantonista" onchange="add_plantonista_horarios(this.value);" required="true" class="form-control">
    <option checked="true" value="">Selecione</option>
    <?php foreach ($setores as $plano){
        $selectd = "";
        if($plano['id'] == $plano_id){
            $selectd = 'selected';
        }
        ?>
     <option value="<?php echo $plano['id']; ?>" <?php echo $selectd ?>><?php echo $plano['nome']; ?></option>   ;
    <?php } ?>
</select>
<br>