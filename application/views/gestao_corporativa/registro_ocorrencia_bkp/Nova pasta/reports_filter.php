
<div class="col-md-6">
    <br>
    <select id="current" name="current" class="selectpicker" required="true" data-width="100%" data-none-selected-text="Setor Atual" data-live-search="true">
        <option value=""></option><!-- comment -->
       <?php foreach ($departments as $department) {?>
        <option value="<?php echo $department['setor'];?>"><?php echo get_departamento_nome($department['setor']);?></option>
        
        <?php }?>
    </select>

</div>
<div class="col-md-6">
    <br>
    <select id="campos_system" name="campos" class="selectpicker" required="true" data-width="100%" data-none-selected-text="Informações do Sistema" data-live-search="true" multiple>
        <option value=""></option><!-- comment -->
        <option value="tbl_intranet_workflow.date_created;Data">Data de Cadastro</option>
        <option value="tbl_intranet_workflow.date_prazo;Prazo Interno">Prazo Interno</option>
        <option value="tbl_intranet_workflow.date_prazo_client;Prazo Cliente">Prazo do Cliente</option>
        <option value="tbl_intranet_workflow.date_end;Data Finalização">Data de Finalização</option>
        <option value="tbl_intranet_workflow.date_end;Data Finalização Cliente">Data de Finalização Cliente</option>
    </select>

</div>
<div class="col-md-12">
    <br>
    <select id="campos_cat" name="campos_cat" class="selectpicker" required="true" data-width="100%" data-none-selected-text="Informações Formulário Inicial" data-live-search="true" multiple>
        <option value=""></option><!-- comment -->
        <?php
        foreach ($campos as $camp) {
            if ($camp['type'] != 'separador') {
                echo "<option value='" . $camp['id'] . ";" . $camp['nome'] . ";" . $camp['type'] ."'>" . $camp['nome'] . "</option>";
            }
        }
        ?>

    </select>

</div>
<div class="col-md-12">
    <br>
    <select id="campos" name="campos" class="selectpicker" required="true" data-width="100%" data-none-selected-text="Informações do Processo" data-live-search="true" multiple>
        <option value=""></option><!-- comment -->
        <?php foreach ($departments as $dep) { ?>

            <?php
            foreach ($dep['campos'] as $camp) {
                if ($camp['type'] != 'separador') {
                    echo "<option title='' value='" . $camp['id'] . ";" . $camp['nome'] . ";" . $dep['setor'] . ";" . $camp['type'] ."' >[" . get_departamento_nome($dep['setor']) . "] ".$camp['nome']  . "</option>";
                }
            }
            ?>


        <?php } ?>
    </select>

</div>

<script>
    $(document).ready(function () {
        init_selectpicker();
    });

    function report_general() {
        $('#trocar').html("");

        var spinner = document.getElementById("spinner");



        // Mostra o spinner
        spinner.style.display = "block";


        // Habilita o botão novamente
        document.getElementById("button1").disabled = true;


        var select = document.getElementById("categoria_id");
        var categoria_id = select.options[select.selectedIndex].value;
        
        var select = document.getElementById("current");
        var current = select.options[select.selectedIndex].value;
        
        var start = document.getElementById("date_start").value;
        var end = document.getElementById("date_end").value;
        var selectElement = document.getElementById("campos_cat");
// Obtenha os valores selecionados
        var campos = [];
        for (var i = 0; i < selectElement.options.length; i++) {
            if (selectElement.options[i].selected) {
                campos.push(selectElement.options[i].value);
            }
        }

        var selectElement = document.getElementById("status");
// Obtenha os valores selecionados
        var status = [];
        for (var i = 0; i < selectElement.options.length; i++) {
            if (selectElement.options[i].selected) {
                status.push(selectElement.options[i].value);
            }
        }


        var selects = document.querySelectorAll('select[name="campos"]');

        // Inicializa um array para armazenar os valores selecionados
        var campos_ = [];

        // Itera sobre os selects e obtém as opções selecionadas de cada um
        selects.forEach(function (select) {
            for (var i = 0; i < select.options.length; i++) {
                if (select.options[i].selected) {
                    campos_.push(select.options[i].value);
                }
            }
        });
        var selectElement = document.getElementById("campos_system");
// Obtenha os valores selecionados
        var campos_system = [];
        for (var i = 0; i < selectElement.options.length; i++) {
            if (selectElement.options[i].selected) {
                campos_system.push(selectElement.options[i].value);
            }
        }
        



        $.ajax({
            type: "POST",
            url: "<?php echo base_url('gestao_corporativa/workflow/report'); ?>",
            data: {
                type: 'general', categoria_id: categoria_id, start: start, end: end, campos_cat: campos, status: status, campos: campos_, campos_system: campos_system, current: current
            },
            success: function (data) {
                $('#trocar').html(data);
                spinner.style.display = "none";
                document.getElementById("button1").disabled = false;
            },
            error: function (xhr, textStatus, errorThrown) {
                alert_float("danger", "AJAX request failed:" + textStatus + ";" + errorThrown);
            }
        });
    }
</script>