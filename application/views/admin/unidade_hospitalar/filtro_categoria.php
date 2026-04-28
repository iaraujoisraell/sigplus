

<select onchange="procedimentos_table_reload()" class="selectpicker"
       data-toggle="<?php echo $this->input->get('allowed_payment_modes'); ?>"
       name="categorias_procedimentos"
       id="categorias_procedimentos"
       data-actions-box="true"
       multiple="true"
       data-width="100%"
       data-title="<?php echo _l('dropdown_non_selected_tex'); ?>">
           <?php
           
           foreach ($categorias as $categoria) {
              $selected = ' selected';
               ?>
             <option  value="<?php echo $categoria['id']; ?>" <?php echo $selected; ?>><?php echo $categoria['name']; ?></option>

    <?php } ?>
</select>




