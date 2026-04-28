<label>Setores </label>
<select name="setor_substituto" id="setor_substituto" class="form-control">
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