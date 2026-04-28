<div class="modal fade" id="add_tabela" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">

    <div class="modal-dialog" role="document">
        <?php //echo form_open("gestao_corporativa/Workflow/cancel/" . $id . '?fluxo_andamento_id=' . $fluxo_andamento_id, array("id" => "workflow-form")); 
        ?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">
                    <span class="edit-title text-white">ADICIONAR NOVA TABELA</span>
                </h4>
            </div>

            <div class="modal-body">

                <div class="row">

                    <div class="col-md-12">
                        <label for="razao" style="font-size: 15px;">Descrição:</label>
                        <input type="text" name="descricao" id="descricao" class="form-control">
                    </div><br>
                    <div class="col-md-6">
                        <label for="razao" style="font-size: 15px;">Conta Razao:</label>
                        <input type="text" name="razao" id="razao" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label for="razao" style="font-size: 15px;">Número de Ordem:</label>
                        <input type="text" name="ordem" id="ordem" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label for="classe" style="font-size: 15px;">Classe:</label>
                        <input type="text" name="classe" id="classe" class="form-control">
                    </div>
                    
                    <div class="col-md-6"><br>
                        <label for="razao" style="font-size: 15px;">API com imposto:</label>
                        <input type="checkbox" name="imposto" id="imposto">
                    </div>
                </div>

            </div>

            <div class="modal-footer"> 

                <button class="btn btn-info" onclick="salvar_tabela()"> Salvar</button>

            </div>

        </div>
        <?php //echo form_close(); 
        ?>
    </div>
</div>

<script>
    function salvar_tabela(){
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('gestao_corporativa/Gerador_arquivos/salvar_tabela'); ?>",
            data: {
                descricao: document.getElementById('descricao').value,
                conta_razao: document.getElementById('razao').value,
                numero_ordem: document.getElementById('ordem').value,
                classe: document.getElementById('classe').value,
                api_imposto: document.getElementById('imposto').checked,
            },
            success: function(data) {
                var obj = JSON.parse(data);
                alert_float(obj.alert, obj.message);
                window.location.reload();
            }
        });
    }
</script>