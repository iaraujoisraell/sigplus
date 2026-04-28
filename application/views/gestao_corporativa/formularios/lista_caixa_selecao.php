<div class="col-sm-12">
    <div class="icheck-primary d-inline">
        
        <label for="radioPrimary3">
            <input type="text" name="item_selecao_<?php echo $perg_id; ?>" placeholder="Descrição do item" id="item_selecao_<?php echo $perg_id; ?>" >
            <a onclick="add_item_caixa_selecao(<?php echo $perg_id; ?>)" class=" btn btn-success"><i class="fa fa-plus"></i></a>
        </label>
    </div>
    <!-- radio -->
   
        <?php foreach ($itens as $item){ ?>
         <div class="form-group clearfix">
        <div class="icheck-primary d-inline">
            <input type="checkbox"  checked>
            <label for="radioPrimary3">
                <?php echo $item['name']; ?>
                <button class="btn btn-warning" onclick="delete_item_caixa_selecao(<?php echo $item['id']; ?>, <?php echo $item['pergunta_id']; ?>)"><i class="fa fa-trash"></i></button>
            </label>
        </div>
         </div>    
        <?php } ?>
    
    
</div>