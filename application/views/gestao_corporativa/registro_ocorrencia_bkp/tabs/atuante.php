<div role="tabpanel" class="tab-pane <?php
if ($limitado == 1) {
    echo 'active';
}
?>" id="aba_<?php echo $atuacao['id']; ?>">
    <hr class="no-mtop" />
    <div class="row">
        <?php
        $disable = '';
        $label_button = 'FINALIZAR OBJETIVO';
        $classe = 'warning';
        $date = date('Y-m-d');
        $objetivo = 'Objetivo não cadastrado';
        $info = 'Sem Prazo';
        $prazo = '00/00/0000';
        if ($atuacao['prazo']) {
            $prazo = date("d/m/Y", strtotime($atuacao['prazo']));
            if ($date <= $atuacao['prazo']) {
                $classe = 'info';
                $info = 'EM DIA';
            } else {
                $classe = 'danger';
                $info = 'EM ATRASO';
            }
        }
        if ($atuacao['date_finalizado'] != '') {
            $info = 'Finalizado: ' . date("d/m/Y", strtotime($atuacao['date_finalizado']));
            $disable = 'disabled';
            $label_button = 'FINALIZADO';
        }
        if ($atuacao['objetivo']) {
            $objetivo = $atuacao['objetivo'];
        }
        ?>
        <div class="col-md-12">
            <div class="alert alert-<?php echo $classe; ?> alert-dismissible">
                <button type="button" <?php echo $disable; ?> onclick="finalizar_atuante('<?php echo $atuacao['id_atuante_staff']; ?>');" class="btn btn-<?php echo $classe; ?>"  style="float: right;"><?php echo $label_button; ?></button>
                <h5><i class="icon fa fa-info-circle"></i> <?php echo strtoupper($atuacao['titulo']); ?> <span class="ticket-label label label-<?php echo $classe; ?> inline-block">Prazo: <?php echo $prazo; ?></span>
                    <span class="ticket-label label label-<?php echo $classe; ?> inline-block"><?php echo $info; ?></span></h5>
                Objetivo: <?php echo $objetivo; ?>
                <p></p>
            </div>
        </div>



        <?php
        $this->load->model('Categorias_campos_model');

        $campos = $this->Categorias_campos_model->get_values($ro->id, 'r.o', $atuacao['id']);

        if (count($campos) > 0) {
            ?>
            <div class="col-md-12 " style="margin-top: 20px;">

                <div class="panel_s">
                    <div class="panel-heading">
                        Campos Obrigatórios da Categoria
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12 mbot20 before-ticket-message">
                                <?php
                                $data['rel_type'] = 'r.o';
                                if ($atuacao['date_finalizado'] != '') {
                                    $data['disabled'] = 'disabled';
                                } else {
                                    $data['disabled'] = '';
                                }

                                $data['rel_id'] = $ro->id;
                                $data['preenchido_por'] = $atuacao['id'];
                                $this->load->view('gestao_corporativa/categorias_campos/values_input', $data);
                                ?>
                                <?php if ($atuacao['date_finalizado'] == '') { ?>
                                    <div class="row" id="lista3">
                                        <div class="col-md-12 text-center">
                                            <hr />
                                            <a href="#" class="btn btn-info" onclick="editar_personalizados<?php echo $atuacao['id']; ?>('<?php echo $atuacao['id']; ?>');">
                                                <?php echo _l('submit'); ?>
                                            </a>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        <?php } else { ?>
            <div class="col-md-12">
                <div class="alert alert-warning alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h5><i class="icon fa fa-info-circle"></i> Campos Personalizados!</h5>
                    Não existem campos obrigatórios para você.
                </div>
            </div>
        <?php } ?>

    </div>
</div>
<script>

    function editar_personalizados<?php echo $atuacao['id']; ?>(por) {
<?php
foreach ($campos as $campo) {
    if ($campo['tipo_campo'] != 'separador' && $campo['tipo_campo'] != 'file') {
        if ($campo['tipo_campo'] == 'multiselect') {
            ?>
                    multiple = $('#<?php echo $campo['name_campo'] ?>').val();
            <?php echo $campo['name_campo'] ?> = multiple.join(',');
        <?php } else { ?>
                    var <?php echo $campo['name_campo'] ?> = document.getElementById('<?php echo $campo['name_campo'] ?>').value;
            <?php
        }
    }
}
?>

        $.ajax({
        type: "POST",
                url: "<?php echo base_url('gestao_corporativa/Registro_ocorrencia/editar_personalizados'); ?>",
                data: {
<?php
foreach ($campos as $campo) {
    if ($campo['tipo_campo'] != 'separador' && $campo['tipo_campo'] != 'file') {
        ?>
        <?php echo $campo['name_campo'] ?>: <?php echo $campo['name_campo']; ?>,
        <?php
    }
}
?>
                registro_id: '<?php echo $ro->id; ?>',
                        preenchido_por: por
                },
                success: function (data) {
                alert_float('success', 'Campos Atualizados!');
                        $('#lista').html(data);
                }
        });
    }
</script>