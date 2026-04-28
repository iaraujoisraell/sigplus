<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(false); ?>


<?php
//init_head_intranet_novo();
$this->load->model('Categorias_campos_model');
$this->load->model('Registro_ocorrencia_model');
?>
<?php $this->load->view('gestao_corporativa/css_background'); ?>
<?php
if (is_array($info_client)) {
    $client_email = $info_client['EMAIL'];
    $client_sms = $info_client['TELEFONE'];
}
?>
<?php init_tail(); ?>
<div class="content">
    <div class="row">
        <div class="col-md-12">
            <div>
                <ol class="breadcrumb" style="background-color: white;">
                    <li><a href="<?= base_url('gestao_corporativa/intranet'); ?>"><i class="fa fa-home"></i> Home </a></li>
                    <li><a href="<?= base_url('gestao_corporativa/Registro_ocorrencia'); ?>"><i class="fa fa-backward"></i> Registro de Ocorrência </a></li>
                    <li class=""><?php echo $ro->subject; ?> </li>
                </ol>
            </div>
        </div>
        <div class="col-md-12">

            <?php

            $this->load->model('registro_ocorrencia_model');

            // permissões se for atuante
            $permissions = $this->registro_ocorrencia_model->get_permissoes($ro->id);

            //identifica setor responsavel
            $setor_responsavel = false;
            $staff_department = $this->registro_ocorrencia_model->get_staff_department();
            foreach ($staff_department as $dep) {
                if ($dep['departmentid'] == $ro->responsavel) {
                    $setor_responsavel = true;
                    break;
                }
            }

            // identifica se o usuario assumiu o registro 

            if ($ro->atribuido_a) {
                $ro_assumido = true;
                if ($ro->atribuido_a == get_staff_user_id()) {
                    $ro_usuario_assmiu = true;
                }
            }
            ?>
            <?php if ($ro_assumido == true && $ro_usuario_assmiu == true && ($ro->status != 3 && $ro->status != 4)) { ?>
                <div class="alert alert-info alert-dismissible " style="padding-bottom: 10px;">
                    <i class="icon fa fa-info-circle"></i> Responsável por cancelar ou finalizar o processo.
                    <a class="btn btn-success btn-xs" href="<?php echo base_url('gestao_corporativa/registro_ocorrencia/cancelar_finalizar/' . $ro->id . '?end=3'); ?>" style="float: right; margin-right: 0px;"> <i class="icon fa fa-check" style="text-align: center;"></i> Finalizar</a>
                    <a class="btn btn-danger btn-xs" href="<?php echo base_url('gestao_corporativa/registro_ocorrencia/cancelar_finalizar/' . $ro->id . '?end=4'); ?>" style="float: right; margin-right: 10px;"> <i class="icon fa fa-times" style="text-align: center;"></i> CANCELAR</a>


                </div>
            <?php } ?>
            <div class="panel_s">
                <div class="panel-body">

                    <div class="horizontal-scrollable-tabs">
                        <div class="scroller arrow-left"><i class="fa fa-angle-left"></i></div>
                        <div class="scroller arrow-right"><i class="fa fa-angle-right"></i></div>
                        <div class="horizontal-tabs">
                            <ul class="nav nav-tabs no-margin nav-tabs-horizontal" role="tablist">


                                <?php
                                $atuais = $this->registro_ocorrencia_model->get_atuantes_preenchidos_all($ro->id);
                                $atuante_limitado = false;
                                foreach ($atuais as $atuante_atual) {
                                    if ($atuante_atual['staff_id'] == get_staff_user_id() or $setor_responsavel == true) {
                                        if ($atuante_atual['limitado'] == 1) {
                                            $atuante_limitado = true;
                                        }
                                ?>
                                        <li role="presentation" class="">
                                            <a href="#aba_<?php echo $atuante_atual['id']; ?>" aria-controls="aba_<?php echo $atuante_atual['id']; ?>" role="tab" data-toggle="tab">
                                                <?php echo $atuante_atual['titulo']; ?>
                                            </a>
                                        </li>
                                <?php
                                    }
                                }
                                ?>


                                <?php if ($setor_responsavel == true or $atuante_limitado == false) { ?>


                                    <li role="presentation" class="active">

                                        <a href="#overview" aria-controls="overview" role="tab" data-toggle="tab">
                                            Visão Geral
                                        </a>
                                    </li>
                                    <?php if ($ro_assumido == true) { ?>

                                        <?php
                                        if ($setor_responsavel == true) {
                                        ?>
                                            <li role="presentation" class="">
                                                <a href="#responsavel" aria-controls="responsavel" role="tab" data-toggle="tab">
                                                    Gerenciador
                                                </a>
                                            </li>
                                        <?php } ?>
                                        <?php if (in_array('configuracao', $permissions) or $setor_responsavel == true) { ?>
                                            <li role="presentation" class="">
                                                <a href="#settings" aria-controls="settings" role="tab" data-toggle="tab">
                                                    Notificante
                                                </a>
                                            </li>
                                        <?php } ?>
                                        <?php if (in_array('ishikawa', $permissions) or $setor_responsavel == true) { ?>
                                            <li role="presentation" class="">
                                                <a href="#analise" aria-controls="analise" role="tab" data-toggle="tab">
                                                    Análise(ISHIKAWA)
                                                </a>
                                            </li>
                                        <?php } ?>
                                        <?php if (in_array('tarefas', $permissions) or $setor_responsavel == true) { ?>
                                            <li role="presentation">
                                                <a href="#tasks" onclick="init_rel_tasks_table(<?php echo $ro->id; ?>, 'record'); return false;" aria-controls="tasks" role="tab" data-toggle="tab">
                                                    <?php echo _l('tasks'); ?>
                                                </a>
                                            </li>
                                        <?php } ?>
                                        <?php if (in_array('files', $permissions) or $setor_responsavel == true) { ?>
                                            <li role="presentation" class="">
                                                <a href="#files" aria-controls="files" role="tab" data-toggle="tab">
                                                    Anexos
                                                </a>
                                            </li>
                                        <?php } ?>

                                        <li role="presentation" class="">
                                            <a href="#addreply" aria-controls="addreply" role="tab" data-toggle="tab">
                                                Respostas
                                            </a>
                                        </li>







                                        <?php if (in_array('notas', $permissions) or $setor_responsavel == true) { ?>
                                            <li role="presentation">
                                                <a href="#note" aria-controls="note" role="tab" data-toggle="tab">
                                                    Notas
                                                </a>
                                            </li>
                                        <?php } ?>



                                        <?php if (in_array('email', $permissions) or $setor_responsavel == true) { ?>
                                            <li role="presentation" class="">
                                                <a href="#email" aria-controls="email" role="tab" data-toggle="tab">
                                                    E-mails
                                                </a>
                                            </li>
                                        <?php } ?>
                                        <?php if (in_array('sms', $permissions) or $setor_responsavel == true) { ?>
                                            <li role="presentation" class="">
                                                <a href="#sms" aria-controls="sms" role="tab" data-toggle="tab">
                                                    SMS
                                                </a>
                                            </li>
                                        <?php } ?>
                                        <?php if (in_array('classificacao', $permissions) or $setor_responsavel == true) { ?>
                                            <li role="presentation">
                                                <a href="#classificacao" aria-controls="classificacao" role="tab" data-toggle="tab">
                                                    Classificação
                                                </a>
                                            </li>
                                        <?php } ?>

                                        <?php if ((in_array('more_info', $permissions) or $ro_usuario_assmiu == true or $setor_responsavel == true && ($ro->status != 3 && $ro->status != 4))) { //$ro_usuario_assmiu == true && ($ro->status != 3 && $ro->status != 4)
                                        ?>
                                            <li role="presentation" class="">
                                                <a href="#more_info" aria-controls="more_info" role="tab" data-toggle="tab">
                                                    Inf. Adicionais
                                                </a>
                                            </li>
                                        <?php } ?>
                                    <?php } ?>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel_s">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-3">
                            <h3 class="mtop4 mbot20 pull-left">
                                <span id="ticket_subject">
                                    #<?php echo $ro->id; ?> - <?php echo $ro->subject; ?>
                                </span>


                            </h3>

                            <?php
                            $status = get_ro_status($ro->status);
                            echo '<div class="label mtop5 mbot15' . (is_mobile() ? ' ' : ' mleft15 ') . 'p8 pull-left single-ticket-status-label" style="background:' . $status['color'] . '">' . $status['label'] . '</div>';
                            ?>
                            <div class="clearfix"></div>
                        </div>
                        <div class="col-md-<?php
                                            if ($setor_responsavel == false) {
                                                echo '8';
                                            } else {
                                                echo '5';
                                            }
                                            ?>">

                            <?php if (!empty($ro->priority)) { ?>
                                <span class="ticket-label label label-default inline-block">
                                    <?php echo _l('ticket_single_priority', ticket_priority_translate($ro->priority)); ?>
                                </span>
                            <?php } ?>
                            <span class="ticket-label label label-default inline-block">
                                <?php
                                if ($ro->anonimo == 1) {
                                    $solicitante = 'ANÔNIMO';
                                } elseif ($ro->user_created) {
                                    $solicitante = get_staff_full_name($ro->user_created);
                                } else {
                                    $solicitante = 'Cliente via Portal';
                                }
                                ?>
                                <?php echo 'Notificante: ' . $solicitante; ?>
                            </span>
                            <?php if (!empty($ro->name)) { ?>
                                <span class="ticket-label label label-default inline-block">
                                    <?php echo 'Responsável: ' . strtoupper($ro->name); ?>
                                </span>
                            <?php } ?>
                            <?php if (!empty($ro->atribuido_a)) { ?>
                                <span class="ticket-label label label-default inline-block">
                                    <?php echo 'Assumido:' . get_staff_full_name($ro->atribuido_a); ?>
                                </span>
                            <?php } ?>


                            <?php foreach ($atuais as $atuante_atual) { ?>

                                <span class="ticket-label label label-info inline-block">
                                    <?php echo $atuante_atual['titulo']; ?>: <?php echo get_staff_full_name($atuante_atual['staff_id']);
                                                                                ?>
                                </span>
                            <?php } ?>

                        </div>
                        <?php if ($setor_responsavel == true) { ?>

                            <div class="col-md-4 ">
                                <div class="row">
                                    <?php if ($ro_assumido == true) { ?>

                                        <?php
                                        if ($ro_usuario_assmiu == false) {
                                            $validade_disabled = 'disabled';
                                        }
                                        ?>
                                        <div class="col-md-5 " style="display:flex; flex-direction: column; justify-content: center;">
                                            <label for="validade" style="padding: .5rem 1rem;" class="control-label">Validade:</label>
                                        </div>
                                        <div class="col-md-7 ">
                                            <input type="date" <?php echo $validade_disabled; ?> id="validade" name="validade" class="form-control" value="<?php echo $ro->validade; ?>" onchange="mudar_validade();">
                                        </div>


                                    <?php } else { ?>
                                        <a class="btn btn-success" data-toggle="tooltip" href="<?php echo base_url("gestao_corporativa/registro_ocorrencia/assumir_registro/$ro->id"); ?>"><i class="fa fa-hand-pointer-o"></i> ASSUMIR REGISTRO <i class="fa fa-hand-pointer-o"></i></a>
                                    <?php } ?>

                                    <?php //if(get_staff_user_id() == '1871'){?>
                                    <button class="btn btn-warning" onclick="trocar_categoria(<?= $ro->id;?>);">TROCAR CATEGORIA</button>
                                    <?php //} ?>
                                </div>

                            </div>
                        <?php } ?>
                    </div>
                    <div class="tab-content">



                        <?php
                        foreach ($atuais as $atuante_atual) {
                            if ($atuante_atual['staff_id'] == get_staff_user_id() or $setor_responsavel == true) {
                                $pass_to_aba['atuacao'] = $atuante_atual;
                                if ($atuante_limitado == true and $setor_responsavel == false) {
                                    $pass_to_aba['limitado'] = 1;
                                }
                        ?>
                                <?php $this->load->view('gestao_corporativa/registro_ocorrencia/tabs/atuante', $pass_to_aba); ?>
                        <?php
                            }
                        }
                        ?>

                        <?php if ($atuante_limitado == false or $setor_responsavel == true) { ?>
                            <?php $this->load->view('gestao_corporativa/registro_ocorrencia/tabs/overview'); ?>



                            <?php if ($ro_assumido == true) { ?>
                                <?php if ((in_array('more_info', $permissions) or $ro_usuario_assmiu == true or $setor_responsavel == true && ($ro->status != 3 && $ro->status != 4))) { ?>
                                    <div role="tabpane3" class="tab-pane" id="more_info">
                                        <?php $this->load->view('gestao_corporativa/categorias_campos/Campos', ["modal" => false, "rel_type" => 'ro_more', "rel_id" => $ro->id]); ?>
                                    </div>

                                <?php } ?>


                                <?php if ($setor_responsavel == true) { ?>
                                    <?php $this->load->view('gestao_corporativa/registro_ocorrencia/tabs/responsavel'); ?>
                                <?php } ?>
                                <?php if (in_array('configuracao', $permissions) or $setor_responsavel == true) { ?>
                                    <?php $this->load->view('gestao_corporativa/registro_ocorrencia/tabs/settings'); ?>
                                <?php } ?>
                                <?php if (in_array('ishikawa', $permissions) or $setor_responsavel == true) { ?>
                                    <?php $this->load->view('gestao_corporativa/registro_ocorrencia/tabs/analise'); ?>
                                <?php } ?>
                                <?php if (in_array('tarefas', $permissions) or $setor_responsavel == true) { ?>
                                    <?php $this->load->view('gestao_corporativa/registro_ocorrencia/tabs/tasks'); ?>
                                <?php } ?>
                                <?php if (in_array('files', $permissions) or $setor_responsavel == true) { ?>
                                    <?php $this->load->view('gestao_corporativa/registro_ocorrencia/tabs/files'); ?>
                                <?php } ?>

                                <?php $this->load->view('gestao_corporativa/registro_ocorrencia/tabs/addreply'); ?>

                                <?php if (in_array('notas', $permissions) or $setor_responsavel == true) { ?>
                                    <?php $this->load->view('gestao_corporativa/registro_ocorrencia/tabs/note'); ?>
                                <?php } ?>

                                <?php if (in_array('email', $permissions) or $setor_responsavel == true) { ?>
                                    <div role="tabpane3" class="tab-pane" id="email">
                                        <div class="row">
                                            <?php
                                            $data['rel_type'] = 'r.o';
                                            $data['rel_id'] = $ro->id;
                                            $data['url_retorno'] = 'gestao_corporativa/Registro_ocorrencia/registro/';

                                            $data['email'] = $client_email;
                                            $this->load->view('gestao_corporativa/Email/index', $data);
                                            ?>
                                        </div>
                                    </div>
                                <?php } ?>
                                <?php if (in_array('sms', $permissions) or $setor_responsavel == true) { ?>

                                    <div role="tabpane56" class="tab-pane" id="sms">
                                        <div class="row">
                                            <?php
                                            $data['rel_type'] = 'r.o';
                                            $data['rel_id'] = $ro->id;
                                            $data['url_retorno'] = 'gestao_corporativa/Registro_ocorrencia/registro/';
                                            $data['list_email'] = [];
                                            $data['client_number'] = $client_sms;
                                            $this->load->view('gestao_corporativa/Sms/index', $data);
                                            ?>
                                        </div>
                                    </div>
                                <?php } ?>
                                <?php if (in_array('classificacao', $permissions) or $setor_responsavel == true) { ?>
                                    <?php $this->load->view('gestao_corporativa/registro_ocorrencia/tabs/classificacao'); ?>
                                <?php } ?>



                            <?php } ?>
                        <?php } ?>

                    </div>
                </div>
            </div>
        </div>


        <?php
        if ($ro->registro_atendimento_id != '' && $ro->registro_atendimento_id != 0) {
        ?>
            <div class="col-md-12 " style="margin-top: 20px;">

                <div class="panel_s">
                    <div class="panel-heading">
                        Registro de atendimento Vinculado
                    </div>
                    <div class="panel-body">
                        <div class="col-md-3 border-right ticket-submitter-info ticket-submitter-info">
                            <p class="text-muted" style="margin-top: 15px;">
                                <strong style="text-transform: uppercase;  font-weight: bold;">Protocolo:</strong> <?php echo $atendimento->protocolo; ?>
                            </p>
                            <p class="text-muted" style="margin-top: 15px;">
                                <strong style="text-transform: uppercase;  font-weight: bold;">DATA:</strong> <?php echo date('d/m/Y H:i:s', strtotime($atendimento->date_created)); ?>
                            </p>
                            <?php if ($atendimento->titulo) { ?>
                                <p class="text-muted" style="margin-top: 15px;">
                                    <strong style="text-transform: uppercase;  font-weight: bold;">Categoria:</strong> <?php echo $atendimento->titulo; ?>
                                </p>
                            <?php } elseif ($atendimento->categoria_id == 0) { ?>
                                <p class="text-muted" style="margin-top: 15px;">
                                    <strong style="text-transform: uppercase;  font-weight: bold;">Categoria:</strong> PORTAL DO CLIENTE
                                </p>

                            <?php } ?>
                            <?php if (is_array($info_client)) { ?>
                                <p class="text-muted" style="margin-top: 15px;">
                                    <strong style="text-transform: uppercase;  font-weight: bold;">INFO TASY:</strong> SIM
                                </p>
                            <?php } ?>
                        </div>

                        <?php
                        if (is_array($info_client)) {
                            $client_email = $info_client['EMAIL'];
                            $client_sms = $info_client['TELEFONE'];
                        ?>
                            <div class="col-md-5 before-ticket-message" style="text-align: left;">


                                <p class="">
                                    <?php //print_r($info_client);   
                                    ?>

                                    <span class="bold">NOME/CARTEIRINHA: </span><?php echo $info_client['NOME_ABREV'] . ' - ' . $info_client['NUMERO_CARTEIRINHA']; ?> <br>
                                    <span class="bold">EMAIL/TELEFONE </span><?php echo $info_client['EMAIL'] . ' - ' . $info_client['TELEFONE']; ?> <br>
                                    <span class="bold">CONTRATANTE:</span> <?php echo $info_client['CONTRATANTE']; ?> - <?php echo $info_client['CPF_CONTRATANTE']; ?> ( <?php echo $info_client['TIPOCONTRATANTE']; ?> ) <br>
                                    <span class="bold">CONTRATO:</span> <?php echo $info_client['CONTRATO']; ?> - <?php echo $info_client['CONTRATACAO']; ?><br>
                                    <span class="bold">CPF:</span> <?php echo $info_client['CPF']; ?><br>
                                    <span class="bold">DATA DE NASCIMENTO:</span> <?php echo $info_client['DATADENASCIMENTO']; ?><br>
                                    <span class="bold">DATA DE ADESÃO:</span> <?php echo $info_client['DATAADESAO']; ?><br>
                                    <span class="bold">DATA DE VALIDADE:</span> <?php echo $info_client['VALIDADE']; ?><br>
                                    <span class="bold">PRODUTO/ABRANGENCIA:</span> <?php echo $info_client['PRODUTO']; ?> - <?php echo $info_client['ABRANGENCIA']; ?><br>
                                    <span class="bold">ACOMODAÇÃO:</span> <?php echo $info_client['ACOMODACAO']; ?><br>
                                    <span class="bold">TITULAR:</span> <?php echo $info_client['TITULAR']; ?><br>
                                    <span class="bold">REDE:</span> <?php echo $info_client['REDE']; ?><br>
                                    <span class="bold">CNS:</span> <?php echo $info_client['CNS']; ?><br>
                                    <span class="bold">SITUAÇÃO:</span> <?php echo $info_client['SITUACAO']; ?><br>


                                </p>



                            </div>
                        <?php } ?>
                        <?php
                        $values_info['campos'] = $this->Categorias_campos_model->get_values($ro->registro_atendimento_id, 'atendimento', '0', true);
                        if (count($values_info['campos']) > 0) {
                        ?>
                            <div class="col-md-4">
                                <?php
                                $this->load->view('gestao_corporativa/categorias_campos/values_info', $values_info);
                                ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>

            </div>
        <?php
        }
        ?>

        <div id="modal_wrapper"></div>


        <?php echo form_hidden('ro_id', $ro->id); ?>
        <div class="_filters _hidden_inputs hidden tickets_filters hide">
            <?php echo form_hidden('filters_ro_id', $ro->ticketid); ?>
            <?php echo form_hidden('filters_userid', $ticket->user_created); ?>
            <?php echo AdminTicketsTableStructure(); ?>
        </div>
    </div>

</div>

<script>
    <?php if ($_GET['ANS'] == 'ERROR') { ?>
        $(function() {
            alert_float('danger', 'A OPERAÇÃO FALHOU');
        });
    <?php } ?>


    function mudar_validade() {

        var validade = document.getElementById("validade").value;
        var ro_id = $('input[name="ro_id"]').val();
        if (ro_id === '') {
            ro_id = '0';
        }
        requestGetJSON('Intranet/change_validade_ajax_registro/' + ro_id + '/' + validade).done(function(response) {
            alert_float(response.alert, response.message);
        });
    }

    function atualizar_etapa_2(assigned, atuante_id) {

        $.ajax({
            type: "POST",
            url: "<?php echo base_url('gestao_corporativa/Registro_ocorrencia/atualizar_etapa_2'); ?>",
            data: {
                assigned: assigned,
                atuante_id: atuante_id,
                id: '<?php echo $ro->id; ?>',
                categoria_id: '<?php echo $ro->categoria_id; ?>'
            },
            success: function(data) {
                if (data != '') {
                    //alert(data);
                    $('#trocar_form_atuante' + atuante_id).html(data);
                }
                alert_float('success', 'Atuante Atualizado!');
            }
        });
    }



    function add_note() {
        //alert('chegou');
        //exit;
        var note_description_ro = document.getElementById("note_description_ro").value;
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('gestao_corporativa/Registro_ocorrencia/add_note_ro'); ?>",
            data: {
                note: note_description_ro,
                registro_id: '<?php echo $ro->id; ?>',
                rel_type: 'note'
            },
            success: function(data) {
                $('#refresh_notes').html(data);
                document.getElementById('note_description_ro').value = ''; // Limpa o campo
                alert_float('success', 'Nota adicionada!');
            }
        });
    }
</script>


<script>
    function limitar(id, limitado) {
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('gestao_corporativa/Registro_ocorrencia/edit_info_atuante'); ?>",
            data: {
                limitado: limitado,
                id: id,
                ro_id: '<?php echo $ro->id; ?>'
            },
            success: function(data) {
                if (limitado == 1) {
                    var data = 'Atuante limitado!'
                } else {
                    var data = 'Atuante não limitado!'
                }
                alert_float('success', data);
            }
        });
    }

    function edit_prazo(id, prazo) {
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('gestao_corporativa/Registro_ocorrencia/edit_info_atuante'); ?>",
            data: {
                prazo: prazo,
                id: id,
                ro_id: '<?php echo $ro->id; ?>'
            },
            success: function(data) {
                alert_float('success', 'Prazo editado!');
            }
        });
    }

    function edit_department(id, dep) {
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('gestao_corporativa/Registro_ocorrencia/edit_info_atuante'); ?>",
            data: {
                department_id: dep,
                id: id,
                ro_id: '<?php echo $ro->id; ?>'
            },
            success: function(data) {
                alert_float('success', 'Departamento do atuante editado!');
            }
        });
    }

    function finalizar_atuante(id) {
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('gestao_corporativa/Registro_ocorrencia/edit_info_atuante'); ?>",
            data: {
                date_finalizado: '<?php echo date('Y-m-d'); ?>',
                id: id,
                ro_id: '<?php echo $ro->id; ?>'
            },
            success: function(data) {
                alert_float('success', 'FINALIZADO!');
                window.location.href = '';
            }
        });
    }

    function trocar_categoria(ro_id){
        
        $("#modal_wrapper").load("<?php echo base_url('gestao_corporativa/Registro_ocorrencia/modal'); ?>", {
            slug: 'trocar_categoria',
            ro_id: ro_id,
        
        }, function() {
            if ($('.modal-backdrop.fade').hasClass('in')) {
                $('.modal-backdrop.fade').remove();
            }
            if ($('#trocar_categoria').is(':hidden')) {
                $('#trocar_categoria').modal({
                    show: true
                });
            }
        });
    }
</script>


</body>

</html>