
<div class="row">

    <div class="panel_s">
        <div class="panel-heading">
            <a  class="label label-sm label-danger" style="background-color: red; color: white;" onclick="fechar();"><i class="fa fa-times"></i></a>
             Workflow #<?php echo $workflow->id; ?> 
            
        </div>
        <div class="panel-body">
            <p class="text-muted col-md-12 row" style="margin-top: 5px; text-transform: uppercase; text-align: center;">
                <strong style="font-weight: bold;">Status:</strong> 
                <?php
            if ($approbation->status == 0) {
                echo '<span id="status" class="label label-sm label-info">AGUARDANDO APROVAÇÃO</span>';
            } elseif ($approbation->status == 1) {
                echo '<span id="status" class="label label-sm label-success">APROVADO</span>';
            } else {
                echo '<span id="status" class="label label-sm label-danger">REPROVADO</span>';
            }
            ?>
            </p>
            <p class="text-muted col-md-12 row" style="margin-top: 5px; text-transform: uppercase; text-align: center;">
                <strong style="font-weight: bold;">Categoria:</strong> 
                <?php echo $workflow->titulo; ?>
            </p>
            <p class="text-muted col-md-12 row" style="margin-top: 5px; text-transform: uppercase; text-align: center;">
                <strong style="font-weight: bold;">Prazo:</strong> 
                <?php echo $categoria->prazo; ?> dias
            </p>

            <p class="text-muted col-md-12 row" style="margin-top: 5px; text-transform: uppercase; text-align: center;">
                <strong style="font-weight: bold;">Sertor Responsável:</strong> 
                <?php echo $workflow->name; ?>
            </p>
            <p class="text-muted col-md-12 row" style="margin-top: 5px; text-transform: uppercase; text-align: center;">
                <strong style="font-weight: bold;">Data de Cadastro:</strong> 
                <?php echo date("d/m/Y h:i:s", strtotime($workflow->date_created)); ?>
            </p>
            <hr class="hr-panel-heading" />
            <?php
            $this->load->model('Categorias_campos_model');
            $values_info['campos'] = $this->Categorias_campos_model->get_values($workflow->id, 'workflow', '0');
            $this->load->view('gestao_corporativa/categorias_campos/values_info', $values_info); ?>
                
            <div id="trocar">
                <hr class="hr-panel-heading col-md-12" />
                <?php if ($approbation->status == 0) { ?>

                    
                    <p class="text-muted col-md-6" style="margin-top: 5px; text-transform: uppercase;">
                        <a type="button" class="btn btn-danger w-100" onclick="none(false, 'motivo', 'ressalva')"><i class="fa fa-times"></i> REPROVAR </a>
                    </p>
                    <p class="text-muted col-md-6" style="margin-top: 5px; text-transform: uppercase;">
                        <a type="button" class="btn btn-success w-100" onclick="none(false, 'ressalva', 'motivo')"><i class="fa fa-check"></i> APROVAR </a>
                    </p>

                    <div class="text-muted col-md-12" style="display: none;" id="ressalvas">
                        <div class="form-group">
                            <label class="control-label" style="display: none;" id="label_ressalva">Ressalva</label>
                            <label class="control-label" style="display: none;" id="label_motivo">Motivo</label>
                            <textarea class="form-control" rows="3" placeholder="Motivo/Observações..." id="obs" required="required" ></textarea>
                        </div>
                        <div class="text-muted col-md-12" style="text-align: right;">
                            <a type="button" class="btn btn-danger" id="button_motivo" onclick="action(false)">CONFIRMAR</a>
                            <a type="button" class="btn btn-success" id="button_ressalva" onclick="action(true)">CONFIRMAR</a>
                            
                        </div>
                    </div>
                <?php } else { 
                    
                    if ($approbation->status == 1) {
                       $status_atual = 'WORKFLOW APROVADO!'; 
                       $color = 'success';
                    } else {
                        $status_atual = 'WORKFLOW REPROVADO!';
                        $color = 'danger';
                    }
                    ?>
                    
                    <div class="col-md-12">
                        <div class="alert alert-<?php echo $color;?> alert-dismissible">
                            <h5><i class="icon fa fa-info-circle"></i> <?php echo $status_atual;?></h5>
                            <p><strong style="font-weight: bold;">Data de Modificação:</strong> <?php echo date("d/m/Y h:i:s", strtotime($approbation->date_aprovacao)); ?></p>
                            <p><strong style="font-weight: bold;">Usuário:</strong> <?php echo get_staff_full_name($approbation->user_aprovacao); ?></p>
                            <p><strong style="font-weight: bold;">Motivo/Ressalva:</strong> <?php echo $approbation->obs;?></p>
                            
                           
                        </div>
                    </div>
                <?php }?>
            </div>

        </div>
    </div>
</div>
<script>

    function none(value, id_label = '', id_label_2 = '') {
        var element = document.getElementById("ressalvas");
        if(id_label != ''){
             var label = document.getElementById("label_"+id_label);
             var label_2 = document.getElementById("label_"+id_label_2);
             var button = document.getElementById("button_"+id_label);
             var button_2 = document.getElementById("button_"+id_label_2);
             label.style.display = 'block';
             label_2.style.display = 'none';
             button.style.display = 'block';
             button_2.style.display = 'none';
        }
       
        
        if (value == true) {
            element.style.display = 'none';
        } else {
            element.style.display = 'block';
        }
    }

    function action(action) {
        var obs = document.getElementById("obs").value;
        var slug = 'workflow';
        var approbation_id = '<?php echo $approbation->id; ?>';
        var id = '<?php echo $workflow->id; ?>';
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('gestao_corporativa/Approbation/action'); ?>",
            data: {
                obs: obs, slug: slug, approbation_id: approbation_id, action: action, id
            },
            success: function (data) {
                reload();
                var element = document.getElementById("status");
                $("#status").removeClass("label-info");
                if (action == true) {
                    $("#status").removeClass("label-danger");
                    element.classList.add("label-success");
                    element.innerHTML = 'APROVADO';
                    alert_float('success', 'WORKFLOW APROVADO!');
                } else {
                    $("#status").removeClass("label-success");
                    element.classList.add("label-danger");
                    element.innerHTML = 'REPROVADO';
                    alert_float('success', 'WORKFLOW REPROVADO!');
                }
                $('#trocar').html(data);

            },
            error: function (data) {
                alert('ERROR');


            }
        });

    }

</script>


