

<div class="modal fade" id="edit_categoria" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">

    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">
                    <span class="edit-title text-white"><?php echo 'Editar: ' . $categoria->titulo; ?></span>
                </h4>
            </div>




            <?php //echo form_open("gestao_corporativa/registro_ocorrencia/add_tipo", array("id" => "ci-form", "enctype" => "multipart/form-data", "class" => "general-form", "role" => "form")); ?>


            <div class="modal-body">
                <?php echo render_input("titulo$categoria->id", 'Titulo', $categoria->titulo, 'text', array('required' => 'true')); ?>
                <input type="hidden" name="id" value="<?php echo $categoria->id; ?>">
            </div>
            <div class="modal-footer" id="teste">
                <button onclick="add_categoria<?php echo $categoria->id ?>();" class="btn bg-info" style="color: white;"><?php echo _l('submit'); ?></button>

            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {



        init_selectpicker();


    });
    function add_categoria<?php echo $categoria->id ?>() {
        var titulo = document.querySelector("#titulo<?php echo $categoria->id ?>");
        var titulo = titulo.value;
        
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('gestao_corporativa/Ged/add_tipo'); ?>",
            data: {
                titulo: titulo, id: '<?php echo $categoria->id?>'
            },
            success: function (data) {
                $('#edit_categoria').modal('hide');
                reload_categoria();
            }
        });
    }
</script>