<?php
$this->load->model('Departments_model');
$setores = $this->Departments_model->get();
?>
<style>
    .tooltip {
        position: relative;
        display: inline-block;
        border-bottom: 1px dotted black;
    }

    .tooltip .tooltiptext {
        visibility: hidden;
        width: 120px;
        background-color: #555;
        color: #fff;
        text-align: center;
        border-radius: 6px;
        padding: 5px 0;
        position: absolute;
        z-index: 1;
        bottom: 125%;
        left: 50%;
        margin-left: -60px;
        opacity: 0;
        transition: opacity 0.3s;
    }

    .tooltip .tooltiptext::after {
        content: "";
        position: absolute;
        top: 100%;
        left: 50%;
        margin-left: -5px;
        border-width: 5px;
        border-style: solid;
        border-color: #555 transparent transparent transparent;
    }

    .tooltip:hover .tooltiptext {
        visibility: visible;
        opacity: 1;
    }
</style>
<div class="row" style="border-left: 1px solid #e2e2e2; padding: 5px;">
    <ol class="breadcrumb" style="">
        <li><a href=""> <?php echo $fluxo->codigo_sequencial; ?> - <?php echo $fluxo->setor_name; ?> </a></li> 
    </ol>
    <?php if (!$fluxo->prazo) { ?>
        <div class="col-md-12" id="">
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h5><i class="icon fa fa-info-circle"></i> INFORMAÇÕES DO FLUXO INCOMPLETAS!</h5>
                Complete as informações de prazo e objetivo do fluxo clicando em editar.
            </div>
        </div>
    <?php } else { ?>
        <div class="col-md-12" id="">
            <div class="form-group">
                <label for="bs_column" class="control-label">Condição:</label>
                <textarea name="objetivo_new" id="objetivo_new" class="form-control " rows="4" onkeyup="save_condicao('<?php echo $fluxo->id; ?>');"><?php echo $fluxo->question; ?></textarea>
            </div>

        </div>

        <div class="caixadefilhos_alternativa col-md-12" id="caixadefilhos_alternativa" style="margin-top: 10px;">

            <div class="alert alert-danger alert-dismissible" style="display: none;" id="mensagem_card">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h5 id="mensagem"></h5>
            </div>
            <?php
            $this->load->model('Workflow_model');
            $fluxos_seguintes = $this->Workflow_model->get_fluxos_seguintes($fluxo->id);

            foreach ($fluxos_seguintes as $fluxo_seguinte) {
                $send['fluxo'] = $fluxo_seguinte;
                $send['setores'] = $setores;
                $this->load->view('gestao_corporativa/workflow/space/alternativa', $send);

           } ?>
        </div>
        <div class="col-md-12">
            <button type="button" id="add" class="btn btn-info w-100" onclick="add('<?php echo $fluxo->id; ?>')"><i class="fa fa-plus"></i> ADD ALTERNATIVA </button>
        </div>
    <?php } ?>
</div>
<script>

    $(document).ready(function () {
        init_selectpicker();
    });

    function add(fluxo_id) {
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('gestao_corporativa/workflow/add_fluxo_question_form/' . $fluxo->categoria_id); ?>",
            data: {
                fluxo_id: fluxo_id
            },
            success: function (data) {
                if (data == 'PRAZO EXCEDIDO') {
                    var card = document.getElementById("mensagem_card");
                    card.style.display = 'block';
                    var element = document.getElementById("mensagem");
                    element.innerHTML = '<i class="icon fa fa-info-circle"></i> PRAZO EXCEDIDO!';
                } else {
                    document.getElementById('add').disabled = true;
                    $(".caixadefilhos_alternativa").append(data);
                }
            }


        });
    }


    function edit_fluxo_change(fluxo_id) {
        $("#save" + fluxo_id).removeClass("hide");
        document.getElementById('pencil' + fluxo_id).classList.add("hide");
        $("#setor_" + fluxo_id).attr('disabled', false);

        $("#setor_" + fluxo_id).selectpicker('refresh');
        document.querySelector("#alternativa_" + fluxo_id).removeAttribute("disabled");
    }




    function delete_fluxo_seguinte(id) {
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('gestao_corporativa/Workflow/delete_fluxo'); ?>",
            data: {
                id: id
            },
            success: function (data) {
                document.getElementById('fluxo_' + id).remove();
                reload_fluxos();
            }
        });
    }
    function save_condicao(id) {
        var question = document.querySelector("#objetivo_new");

        var question = question.value;
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('gestao_corporativa/Workflow/edit_question'); ?>",
            data: {
                id: id, question: question
            },
            success: function (data) {
                document.getElementById("objetivo_disabled").innerHTML = question;
            }
        });
    }
    function edit_fluxo_seguinte(id) {
        var alternativa = document.querySelector("#alternativa_" + id);
        var alternativa = alternativa.value;

        var select = document.getElementById('setor_' + id);
        var setor = select.options[select.selectedIndex].value;

        $.ajax({
            type: "POST",
            url: "<?php echo base_url('gestao_corporativa/Workflow/edit_fluxo'); ?>",
            data: {
                id: id, setor: setor, alternativa: alternativa
            },
            success: function (data) {
                reload_fluxos(id);
                $("#pencil" + id).removeClass("hide");
                document.getElementById('save' + id).classList.add("hide");
                $("#setor_" + id).attr('disabled', true);

                $("#setor_" + id).selectpicker('refresh');
                $("#alternativa_" + id).attr('disabled', true);

            }
        });
    }

    function addClass(id, classe) {
        var elemento = document.getElementById(id);
        var classes = elemento.className.split(' ');
        var getIndex = classes.indexOf(classe);

        if (getIndex === -1) {
            classes.push(classe);
            elemento.className = classes.join(' ');
        }
    }

    function delClass(id, classe) {
        var elemento = document.getElementById(id);
        var classes = elemento.className.split(' ');
        var getIndex = classes.indexOf(classe);

        if (getIndex > -1) {
            classes.splice(getIndex, 1);
        }
        elemento.className = classes.join(' ');
    }

</script>


