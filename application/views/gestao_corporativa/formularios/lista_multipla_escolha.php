<div class="col-sm-12">
    <div class="icheck-primary d-inline">
        
        <label for="radioPrimary3">
            <input type="text" name="item_name_<?php echo $perg_id; ?>" placeholder="Descrição do item" id="item_name_<?php echo $perg_id; ?>" >
            <a onclick="add_item_multipla_escolha(<?php echo $perg_id; ?>)" class=" btn btn-success"><i class="fa fa-plus"></i></a>
        </label>
    </div>
    <!-- radio -->
   
        <?php foreach ($itens as $item){ ?>
         <div class="form-group clearfix">
        <div class="icheck-primary d-inline">
            <input type="radio"  checked>
            <label for="radioPrimary3">
                <?php echo $item['name']; ?>
                <button class="btn btn-warning" onclick="delete_item_multipla_escolha(<?php echo $item['id']; ?>, <?php echo $item['pergunta_id']; ?>)"><i class="fa fa-trash"></i></button>
            </label>
        </div>
         </div>    
        <?php } ?>
    
    
</div>