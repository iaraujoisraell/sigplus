<label>Setores</label>

    <?php foreach ($setores as $plano){
        
        ?>
        <div class="checkbox">
            <input type="checkbox" value="<?php echo $plano['id']; ?>" name="unidade_hospitalar<?php echo $plano['id']; ?>" id="unidade_hospitalar<?php echo $plano['id']; ?>"<?php if($this->input->post('unidade_hospitalar'.$plano['id'])){echo ' checked';} ?>>
            <label for="cf_tasks"><?php echo $plano['nome']; ?></label>
        </div>
    
     
    <?php } ?>

<br>