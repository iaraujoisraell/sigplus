
<div id="atuante<?php echo $atuante->id; ?>">
    <div class="">
        <div class="input-group">
            <input type="text" name="titulo_atuante<?php echo $atuante->id; ?>" id="titulo_atuante<?php echo $atuante->id; ?>" class="form-control" value="<?php echo $atuante->titulo; ?>" required="required">
            <span class="input-group-btn">
                <button class="btn btn-success p7" onclick="add_atuante('<?php echo $atuante->id; ?>');"><?php echo 'Editar'; ?></button>
            </span>
            <span class="input-group-btn">
                <button class="btn btn-danger p7" onclick="document.getElementById('atuante' + '<?php echo $atuante->id; ?>').remove(); document.getElementById('atuante').style.display = 'block';"><?php echo 'Cancelar'; ?></button>
            </span>
        </div>

    </div>
    <div class="">
        <?php $abas = explode(',', $atuante->abas) ?>
        <div class="icheck-primary d-inline">


            <input type="checkbox" id="checkboxPrimary2" class="abas" name="abas<?php echo $atuante->id; ?>" value="ishikawa"
            <?php
            if (in_array('ishikawa', $abas)) {
                echo 'checked';
            }
            ?>>
            <label for="checkboxPrimary2">
                Ishikawa
            </label>
            <input type="checkbox" id="checkboxPrimary3" class="abas" name="abas<?php echo $atuante->id; ?>" value="notas"
            <?php
            if (in_array('notas', $abas)) {
                echo 'checked';
            }
            ?>>
            <label for="checkboxPrimary3">
                Notas
            </label>
            <input type="checkbox" id="checkboxPrimary4" class="abas" name="abas<?php echo $atuante->id; ?>" value="tarefas"
            <?php
            if (in_array('tarefas', $abas)) {
                echo 'checked';
            }
            ?>>
            <label for="checkboxPrimary4">
                Ações
            </label>
            <input type="checkbox" id="checkboxPrimary5" class="abas" name="abas<?php echo $atuante->id; ?>" value="configuracao"
            <?php
            if (in_array('configuracao', $abas)) {
                echo 'checked';
            }
            ?>>
            <label for="checkboxPrimary5">
                Notificante
            </label>
            <input type="checkbox" id="checkboxPrimary6" class="abas" name="abas<?php echo $atuante->id; ?>" value="classificacao"
            <?php
            if (in_array('classificacao', $abas)) {
                echo 'checked';
            }
            ?>>
            <label for="checkboxPrimary6">
                Classificação
            </label>
            <input type="checkbox" id="checkboxPrimary9" class="abas" name="abas<?php echo $atuante->id; ?>" value="email"
            <?php
            if (in_array('email', $abas)) {
                echo 'checked';
            }
            ?>>
            <label for="checkboxPrimary9">
                Email
            </label>
            <input type="checkbox" id="checkboxPrimary10" class="abas" name="abas<?php echo $atuante->id; ?>" value="more_info"
            <?php
            if (in_array('more_info', $abas)) {
                echo 'checked';
            }
            ?>>
            <label for="checkboxPrimary9">
                Informações Adicionais
            </label>
            <input type="checkbox" id="checkboxPrimary8" class="abas" name="abas<?php echo $atuante->id; ?>" value="sms"
            <?php
            if (in_array('sms', $abas)) {
                echo 'checked';
            }
            ?>>
            <label for="checkboxPrimary8">
                Sms
            </label>
            <input type="checkbox" id="checkboxPrimary7" class="abas" name="abas<?php echo $atuante->id; ?>" value="files"
                   <?php
                   if (in_array('files', $abas)) {
                       echo 'checked';
                   }
                   ?>>
            <label for="checkboxPrimary7">
                Anexos
            </label>
        </div>
    </div>
</div>