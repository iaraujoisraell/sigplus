
<label>Plano de Contas</label>
<select name="plano_conta_id" id="plano_conta_id" required="true" class="form-control">
    <option checked="true" value="">Selecione</option>
    <?php foreach ($planos_conta as $plano){
        $selectd = "";
        if($plano['id'] == $plano_id){
            $selectd = 'selected';
        }
        ?>
     <option value="<?php echo $plano['id']; ?>" <?php echo $selectd ?>><?php echo $plano['descricao']; ?></option>   ;
    <?php } ?>
</select>
<br>