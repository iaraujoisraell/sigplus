<?php
$tabela = 'teste';
?>

<div class="modal fade" id="acess_type" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">

    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">
                    <span class="edit-title text-white"><?php
                        if ($acess->id != '') {
                            echo 'Editar: ' . $acess->name;
                        } else {
                            echo 'Adicionar Tipo de Acesso';
                        }
                        ?></span>
                </h4>
            </div>




            <?php //echo form_open("gestao_corporativa/registro_ocorrencia/add_tipo", array("id" => "ci-form", "enctype" => "multipart/form-data", "class" => "general-form", "role" => "form"));     ?>


            <div class="modal-body">
                <?php echo render_input("name", 'Nome', $acess->name, 'text', array('required' => 'true')); ?>

                <input type="hidden" name="id" value="<?php echo $acess->id; ?>">


                <div class="form-group">
                    <label class="control-label">Orientação</label>
                    <textarea class="form-control" rows="3" placeholder="" id="description"><?php echo $acess->description; ?></textarea>
                </div>
                <?php echo render_select("api_id", $apis, array('id', 'titulo'), 'API', '', array('required' => 'true'));?>

            </div>
            <div class="modal-footer" id="teste">

                <button onclick="save_acess();" class="btn bg-info" style="color: white;"><?php echo _l('submit'); ?></button>

            </div>
        </div>
    </div>
</div>
<script>

    function save_acess(id) {
        var name = document.getElementById("name").value;
        var obs = document.getElementById("description").value;
        var select = document.getElementById('api_id');
        var api_id = select.options[select.selectedIndex].value;
        

        $.ajax({
            type: "POST",
            url: "<?php echo base_url('gestao_corporativa/Company/acess_type'); ?>",
            data: {
                name: name, obs: obs, api_id: api_id,  id: id
            },
            success: function (data) {
                $('#acess_type').modal('hide');
                var obj = JSON.parse(data);
                alert_float(obj.alert, obj.message);
                reload();
            }
        });
    }
    
    $(document).ready(function () {
    init_selectpicker();
    });
    
    

</script>