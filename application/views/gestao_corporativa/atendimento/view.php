<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(false); ?>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/menu/dist/css/AdminLTE.min.css">
<link rel="stylesheet" href="<?php echo base_url() ?>assets/menu/bower_components/Ionicons/css/ionicons.min.css">

<style>
    .color-palette {
        height: 35px;
        line-height: 35px;
        text-align: center;
    }

    .color-palette-set {
        margin-bottom: 15px;
    }

    .color-palette span {
        display: none;
        font-size: 12px;
    }

    .color-palette:hover span {
        display: block;
    }

    .color-palette-box h4 {
        position: absolute;
        top: 100%;
        left: 25px;
        margin-top: -40px;
        color: rgba(255, 255, 255, 0.8);
        font-size: 12px;
        display: block;
        z-index: 7;
    }
</style>

<style>
    body {
        font-family:'Open Sans';
        background:#f1f1f1;
    }
    h3 {
        margin-top: 7px;
        font-size: 18px;
    }

    .install-row.install-steps {
        margin-bottom:15px;
        box-shadow: 0px 0px 1px #d6d6d6;
    }

    .control-label {
        font-size:13px;
        font-weight:600;
    }
    .padding-10 {
        padding:10px;
    }
    .mbot15 {
        margin-bottom:15px;
    }
    .bg-default {
        background: #03a9f4;
        border:1px solid #03a9f4;
        color:#fff;
    }
    .bg-success {
        border: 1px solid #dff0d8;
    }
    .bg-not-passed {
        border:1px solid #f1f1f1;
        border-radius:2px;
    }
    .bg-not-passed {
        border-right:0px;
    }
    .bg-not-passed.finish {
        border-right:1px solid #f1f1f1 !important;
    }
    .bg-not-passed h5 {
        font-weight:normal;
        color:#6b6b6b;
    }
    .form-control {
        box-shadow:none;
    }
    .bold {
        font-weight:600;
    }
    .col-xs-5ths,
    .col-sm-5ths,
    .col-md-5ths,
    .col-lg-5ths {
        position: relative;
        min-height: 1px;
        padding-right: 15px;
        padding-left: 15px;
    }
    .col-xs-5ths {
        width: 20%;
        float: left;
    }
    b {
        font-weight:600;
    }
    .bootstrap-select .btn-default {
        background: #fff !important;
        border: 1px solid #d6d6d6 !important;
        box-shadow: none;
        color: #494949 !important;
        padding: 6px 12px;
    }
</style>

<style>
    /* Timeline */
    .timeline,
    .timeline-horizontal {
        list-style: none;
        padding: 5px;
        position: relative;
    }
    .timeline:before {
        top: 60px;
        bottom: 0;
        position: absolute;
        content: " ";
        width: 3px;
        background-color: #eeeeee;
        left: 50%;
        margin-left: -1.5px;
    }
    .timeline .timeline-item {
        margin-bottom: 20px;
        position: relative;
    }
    .timeline .timeline-item:before,
    .timeline .timeline-item:after {
        content: "";
        display: table;
    }
    .timeline .timeline-item:after {
        clear: both;
    }
    .timeline .timeline-item .timeline-badge {
        color: #fff;
        width: 54px;
        height: 54px;
        line-height: 52px;
        font-size: 22px;
        text-align: center;
        position: absolute;
        top: 18px;
        left: 50%;
        margin-left: -25px;
        background-color: #7c7c7c;
        border: 3px solid #ffffff;
        z-index: 100;
        border-top-right-radius: 50%;
        border-top-left-radius: 50%;
        border-bottom-right-radius: 50%;
        border-bottom-left-radius: 50%;
    }
    .timeline .timeline-item .timeline-badge i,
    .timeline .timeline-item .timeline-badge .fa,
    .timeline .timeline-item .timeline-badge .glyphicon {
        top: 2px;
        left: 0px;
    }
    .timeline .timeline-item .timeline-badge.primary {
        background-color: #1f9eba;
    }
    .timeline .timeline-item .timeline-badge.info {
        background-color: #5bc0de;
    }
    .timeline .timeline-item .timeline-badge.success {
        background-color: #59ba1f;
    }
    .timeline .timeline-item .timeline-badge.warning {
        background-color: #d1bd10;
    }
    .timeline .timeline-item .timeline-badge.danger {
        background-color: #ba1f1f;
    }
    .timeline .timeline-item .timeline-panel {
        position: relative;
        width: 46%;
        float: left;
        right: 16px;
        border: 1px solid #c0c0c0;
        background: #ffffff;
        border-radius: 2px;
        padding: 20px;
        -webkit-box-shadow: 0 1px 6px rgba(0, 0, 0, 0.175);
        box-shadow: 0 1px 6px rgba(0, 0, 0, 0.175);
    }
    .timeline .timeline-item .timeline-panel:before {
        position: absolute;
        top: 26px;
        right: -16px;
        display: inline-block;
        border-top: 16px solid transparent;
        border-left: 16px solid #c0c0c0;
        border-right: 0 solid #c0c0c0;
        border-bottom: 16px solid transparent;
        content: " ";
    }
    .timeline .timeline-item .timeline-panel .timeline-title {
        margin-top: 0;
        color: inherit;
    }
    .timeline .timeline-item .timeline-panel .timeline-body > p,
    .timeline .timeline-item .timeline-panel .timeline-body > ul {
        margin-bottom: 0;
    }
    .timeline .timeline-item .timeline-panel .timeline-body > p + p {
        margin-top: 5px;
    }
    .timeline .timeline-item:last-child:nth-child(even) {
        float: right;
    }
    .timeline .timeline-item:nth-child(even) .timeline-panel {
        float: right;
        left: 16px;
    }
    .timeline .timeline-item:nth-child(even) .timeline-panel:before {
        border-left-width: 0;
        border-right-width: 14px;
        left: -14px;
        right: auto;
    }
    .timeline-horizontal {
        list-style: none;
        position: relative;
        display: inline-block;
    }
    .timeline-horizontal:before {
        height: 3px;
        top: auto;
        bottom: 26px;
        left: 56px;
        right: 0;
        width: 100%;
        margin-bottom: 20px;
    }
    .timeline-horizontal .timeline-item {
        display: table-cell;
        height: 250px;
        min-width: 20px;
        max-width: 400px;
        float: none !important;
        padding-left: 0px;
        padding-right: 20px;
        vertical-align: bottom;
    }
    .timeline-horizontal .timeline-item .timeline-panel {
        top: auto;
        bottom: 64px;
        display: inline-block;
        float: none !important;
        left: 0 !important;
        right: 0 !important;
        width: 100%;
    }
    .timeline-horizontal .timeline-item .timeline-panel:before {
        top: auto;
        bottom: -16px;
        left: 28px !important;
        right: auto;
        border-right: 16px solid transparent !important;
        border-top: 16px solid #c0c0c0 !important;
        border-bottom: 0 solid #c0c0c0 !important;
        border-left: 16px solid transparent !important;
    }
    .timeline-horizontal .timeline-item:before,
    .timeline-horizontal .timeline-item:after {
        display: none;
    }
    .timeline-horizontal .timeline-item .timeline-badge {
        top: auto;
        bottom: 0px;
        left: 43px;
    }
</style>

<style>
.btn-pdf-ra{
    background: #1e88e5;
    border-color: #1e88e5;
    color: #fff !important;
    font-weight: 600;
    padding: 8px 14px;
    border-radius: 6px;
    box-shadow: 0 2px 8px rgba(30,136,229,.18);
}
.btn-pdf-ra:hover,
.btn-pdf-ra:focus{
    background: #1565c0;
    border-color: #1565c0;
    color: #fff !important;
}
</style>
<?php $this->load->view('gestao_corporativa/css_background'); ?>

<div class="content">

    <div class="row">
        <div class="col-md-12">

            <h2>Protocolo: <?php echo $info->protocolo; ?></h2>
            <div>
                <ol class="breadcrumb" style="background-color: white;">
                    <li><a href="<?= base_url('gestao_corporativa/intranet'); ?>"><i class="fa fa-home"></i> Home </a></li> 
                    <li ><a href="<?= base_url('gestao_corporativa/Atendimento'); ?>"><i class="fa fa-user"></i> Registro de Atendimento </a></li>
                    <li class="active"><a href="#"><i class="fa fa-user"></i> <?php echo $info->protocolo; ?> </a></li>
                </ol>
            </div>

        </div>

        <?php
        $data_encerramento = $info->data_encerramento;
        $data_hora_inicial = $info->date_created;

        $hora_start = date("H:i:s", strtotime($data_hora_inicial));

        $hora_inicial = $hora_start;
        if ($info->data_encerramento) {
            $hora_final = date("H:i:s", strtotime($data_encerramento));
        } else {
            $hora_final = date('H:i:s');
        }

        $i = 1;
        $tempo_total;
        $tempos = array($hora_final, $hora_inicial);
        foreach ($tempos as $tempo) {
            $segundos = 0;
            list($h, $m, $s) = explode(':', $tempo);
            $segundos += $h * 3600;
            $segundos += $m * 60;
            $segundos += $s;

            $tempo_total[$i] = $segundos;
            $i++;
        }
        $segundos = $tempo_total[1] - $tempo_total[2];
        $horas = floor($segundos / 3600);
        $segundos -= $horas * 3600;
        $minutos = str_pad((floor($segundos / 60)), 2, '0', STR_PAD_LEFT);
        $segundos -= $minutos * 60;
        $segundos = str_pad($segundos, 2, '0', STR_PAD_LEFT);

        //
        ?>

        <div class="col-md-12">
            <?php if (!$data_encerramento) { ?>
                <label style="font-size: 25px;" id="counter" class="label label-warning">00:00:00</label>
          <!--  <input type="button" value="Parar" onclick="para();"> <input type="button" value="Iniciar" onclick="inicia();"> <input type="button" value="Zerar" onclick="zera();"> -->
                <a  class="btn btn-primary pull-right" title = "" href="<?php echo site_url('gestao_corporativa/Atendimento/encerrar_atendimento/' . $info->id); ?> " <?php
                if ($permission == false) {
                    echo 'disabled style="pointer-events: none; cursor: default;"';
                }
                ?>>Encerrar Atendimento</a>
                <?php } else { ?>
                <label style="font-size: 25px;" id="counter" class="label label-success"><?php echo 'Tempo de Atendimento : ' . "$horas:$minutos:$segundos"; ?></label>
            <?php } ?>
        </div>

        <div class="col-md-12">
            <br>
        </div>


        <div class="col-md-12">

            <div class="panel_s">
                <div class="panel-heading">
                    Informações do Atendimento
                    <a target="_blank"
                        href="<?php echo base_url('gestao_corporativa/atendimento/pdf/' . $info->id); ?>"
                        class="btn btn-info btn-sm pull-right mleft10"
                        style="margin-top:-5px;">
                            <i class="fa fa-print"></i> Imprimir PDF Completo
                        </a>
                    <label class="pull-right">

                        <?php if ($info->canal_atendimento_id != 0) { ?>
                            Atendente : <?php echo $info->firstname . ' ' . $info->lastname; ?>
                        <?php } elseif ($info->canal_atendimento_id == 0 and $info->user_created != '') { ?>
                            ACESSO MASTER PORTAL : <?php echo get_staff_full_name($info->user_created); ?>
                        <?php } ?>

                    </label>
                </div>
                <div class="panel-body">
                    <?php
                    // print_r($info); exit;
                    $this->load->model('Clients_model');
                    $client = $this->Clients_model->get($info->client_id);
                    ?>
                    <div class="row">
                        <div class="col-md-<?php
                        if (is_array($info_client)) {
                            echo '8';
                        } else {
                            echo '12';
                        }
                        ?>">
                            <div class="row">
                                <div class="col-md-4">
                                    <label>#RA</label>
                                    <input type="text" name="protocolo" value="#<?php echo $info->sequencial; ?>" class="form-control" disabled="true">       
                                </div>
                                <div class="col-md-4">
                                    <label>Canal Atendimento</label>
                                    <input type="text" name="categoria" value="<?php
                                    if ($info->canal_atendimento_id == 0) {
                                        echo 'PORTAL DO CLIENTE';
                                    } else {
                                        echo $info->canal;
                                    }
                                    ?>" class="form-control" disabled="true">       
                                </div>
                                <div class="col-md-4">
                                    <label>Cliente</label>
                                    <input type="text" name="categoria" value="<?php echo $client->company; ?>" class="form-control" disabled="true">       
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-4">
                                    <label>Categoria</label>
                                    <input type="text" name="protocolo" value="<?php
                                    if ($info->categoria_id == 0) {
                                        echo 'PORTAL DO CLIENTE';
                                    } else {
                                        echo $info->titulo;
                                    }
                                    ?>" class="form-control" disabled="true">       
                                </div>
                                <div class="col-md-4">
                                    <label>E-mail</label>
                                    <input type="text" name="categoria" value="<?php echo $info->email; ?>" class="form-control" disabled="true">       
                                </div>
                                <div class="col-md-4">
                                    <label>Carteirinha</label>
                                    <input type="text" name="carteirinha" value="<?php echo $client->numero_carteirinha; ?>" class="form-control" disabled="true">       
                                </div>

                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-4">
                                    <label>Protocolo</label>
                                    <input type="text" name="categoria" value="<?php echo $info->protocolo; ?>" class="form-control" disabled="true">       
                                </div>
                                <div class="col-md-4">
                                    <label>Contato</label>
                                    <input type="text" name="protocolo" value="<?php echo $info->contato; ?>" class="form-control" disabled="true">       
                                </div>
                                <div class="col-md-4">
                                    <label>CPF</label>
                                    <input type="text" name="cpf" value="<?php echo $client->vat; ?>" class="form-control" disabled="true">       
                                </div>

                            </div>

                        </div>
                        <?php
                        if (is_array($info_client)) {
                            $client_email = $info_client['EMAIL'];
                            $client_sms = $info_client['TELEFONE'];
                            ?>
                            <div class="col-md-4" style="text-align: center;">

                                <p class=""> 
                                    <?php //print_r($info_client);    ?>

                                    <span class="bold">NOME/CARETEIRINHA: </span><?php echo $info_client['NOME_ABREV'] . ' - ' . $info_client['NUMERO_CARTEIRINHA']; ?> (</span> <?php echo $info_client['SITUACAO']; ?> )<br>
                                    <span class="bold">EMAIL/TELEFONE </span><?php echo $info_client['EMAIL'] . ' - ' . $info_client['TELEFONE']; ?> <br>
                                    <span class="bold">CONTRATANTE:</span> <?php echo $info_client['CONTRATANTE']; ?> - <?php echo $info_client['CPF_CONTRATANTE']; ?> ( <?php echo $info_client['TIPOCONTRATANTE']; ?> ) <br>
                                    <span class="bold">CONTRATO:</span> <?php echo $info_client['CONTRATO']; ?> - <?php echo $info_client['CONTRATACAO']; ?> (<?php echo $info_client['TITULAR']; ?>)<br>
                                    <span class="bold">CPF:</span> <?php echo $info_client['CPF']; ?><br>
                                    <span class="bold">DATA DE NASCIMENTO:</span> <?php echo $info_client['DATADENASCIMENTO']; ?><br>
                                    <span class="bold">DATAS DE ADESÃO E VALIDADE:</span> <?php echo $info_client['DATAADESAO']; ?> - <?php echo $info_client['VALIDADE']; ?><br>
                                    <span class="bold">PRODUTO/ABRANGENCIA:</span> <?php echo $info_client['PRODUTO']; ?> - <?php echo $info_client['ABRANGENCIA']; ?><br>
                                    <span class="bold">ACOMODAÇÃO:</span> <?php echo $info_client['ACOMODACAO']; ?><br>
                                    <span class="bold">REDE:</span> <?php echo $info_client['REDE']; ?><br>
                                    <span class="bold">CNS:</span> <?php echo $info_client['CNS']; ?><br>


                                </p>

                            </div>
                        <?php } ?>
                    </div>
                </div>

            </div>
            <?php
            if ($info->canal_atendimento_id != 0) {

                $this->load->model('Categorias_campos_model');
                $data['campos'] = $this->Categorias_campos_model->get_values($info->id, 'atendimento');
                if (count($data['campos']) > 0) {
                    ?>
                    <div class="panel_s">
                        <div class="panel-heading">
                            Outras Informações
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <?php
                                    $this->load->view('gestao_corporativa/categorias_campos/values_info', $data);
                                    ?>

                                </div>
                            </div>
                        </div>

                    </div>
                    <?php
                }
            }
            ?>
            <div class="panel_s">
                <div class="panel-body">
                    <div class="horizontal-scrollable-tabs">
                        <div class="scroller arrow-left"><i class="fa fa-angle-left"></i></div>
                        <div class="scroller arrow-right"><i class="fa fa-angle-right"></i></div>
                        <div class="horizontal-tabs">
                            <ul class="nav nav-tabs profile-tabs row customer-profile-tabs nav-tabs-horizontal" role="tablist">

                                <li role="presentation" class="active">
                                    <a href="#note_atendimento" aria-controls="note_atendimento" role="tab" data-toggle="tab">
                                        Notas
                                    </a>
                                </li>
                                <li role="presentation">
                                    <a href="#registros_ocorrencia" aria-controls="registros_ocorrencia" role="tab" data-toggle="tab">
                                        Registros de Ocorrencia
                                    </a>
                                </li>
                                <li role="presentation" class="">
                                    <a href="#solicitacoes" aria-controls="solicitacoes" role="tab" data-toggle="tab">
                                        Solicitações Rápidas
                                    </a>
                                </li>
                                <li role="presentation" class="">
                                    <a href="#workflow" aria-controls="workflow" role="tab" data-toggle="tab">
                                        Workflow
                                    </a>
                                </li>

                                <li role="presentation" class="">
                                    <a href="#aut" aria-controls="aut" role="tab" data-toggle="tab">
                                        Autosserviços
                                    </a>
                                </li>

                                <li role="presentation" class="">
                                    <a href="#email" aria-controls="email" role="tab" data-toggle="tab">
                                        E-mails
                                    </a>
                                </li>

                                <li role="presentation" class="">
                                    <a href="#sms" aria-controls="sms" role="tab" data-toggle="tab">
                                        SMS
                                    </a>
                                </li>

                            </ul>
                        </div>
                    </div>
                    <div class="tab-content mtop15">
                        <div role="tabpane1" class="tab-pane active" id="note_atendimento">

                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Transcrição do Atendimento
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-12 mtop10 mbot10">
                                            <button type="button" class="btn btn-info" id="btnGerarTranscricao">
                                                <i class="fa fa-file-text-o"></i> Transcrição
                                            </button>

                                            <button type="button" class="btn btn-default" id="btnCopiarTranscricao">
                                                <i class="fa fa-copy"></i> Copiar
                                            </button>

                                            <button type="button" class="btn btn-success" id="btnInserirTranscricao">
                                                <i class="fa fa-arrow-down"></i> Inserir na nota
                                            </button>
                                        </div>

                                        <div class="col-md-12">
                                            <textarea id="textoTranscricao" class="form-control" rows="10" placeholder="Clique em Transcrição para gerar o texto..."></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <?php $this->load->view('gestao_corporativa/notes/note', ["rel_type" => 'atendimento', 'rel_id' => $info->id]); ?>
                        </div>

                        <div role="tabpane2" class="tab-pane" id="registros_ocorrencia">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class=" _buttons">
                                        <a <?php
                                        if ($permission == false) {
                                            echo 'disabled style="pointer-events: none;
  cursor: default;"';
                                        }
                                        ?> target="_blank" class="btn btn-primary" title = "Add novo Registro de Ocorrências" href="<?php echo site_url('gestao_corporativa/registro_ocorrencia/add/' . $info->id); ?>">ADD Registro de Ocorrência</a>
                                    </div>
                                    <div class="mtop15 ">
                                        <table class="table dt-table scroll-responsive" data-order-col="2" data-order-type="desc">
                                            <thead>
                                                <tr>
                                                    <th >
                                                        <?php echo 'ID'; ?>
                                                    </th>
                                                    <th>
                                                        <?php echo 'Assunto'; ?>
                                                    </th>
                                                    <th>
                                                        <?php echo 'Data'; ?>
                                                    </th>
                                                    <th>
                                                        <?php echo 'Prioridade'; ?>
                                                    </th>
                                                    <th>
                                                        <?php echo 'Categoria'; ?>
                                                    </th>
                                                    <th>
                                                        <?php echo 'Setor Responsável'; ?>
                                                    </th>
                                                    <th>
                                                        <?php echo 'Campos Chaves'; ?>
                                                    </th>
                                                    <th>
                                                        <?php echo 'Status'; ?>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($ros_ra as $ro) { ?>
                                                    <tr>
                                                        <td >
                                                            <?php echo $ro['id']; ?>
                                                        </td>   
                                                        <td >
                                                            <?php
                                                            $titulo = '<a href="' . base_url('gestao_corporativa/Registro_ocorrencia/registro/' . base64_encode(openssl_encrypt($ro['id'], "aes-256-cbc", 'sigplus', 0, 'sigplus'))) . '" target="_blanck">' . strtoupper($ro['titulo']) . '</a>';
                                                            echo $titulo;
                                                            ?>
                                                        </td>
                                                        <td >
                                                            <?php echo $ro['date']; ?>
                                                        </td>
                                                        <td>
                                                            <?php echo $ro['prioridade']; ?>
                                                        </td>
                                                        <td>
                                                            <?php echo $ro['categoria']; ?>
                                                        </td>
                                                        <td>
                                                            <?php echo $ro['responsavel']; ?>
                                                        </td>
                                                        <td>
                                                            <?php
                                                            $this->load->model('Categorias_campos_model');

                                                            $chaves = $this->Categorias_campos_model->get_values($info->id, 'ro', false, true);
                                                            foreach ($chaves as $info) {
                                                                $nome_campo = $info['nome_campo'];
                                                                $campo = $info['tipo_campo'];
                                                                $value = $info['value'];

                                                                if ($campo == 'multiselect' || $campo == 'select') {
                                                                    $values = explode(',', $value);
                                                                    for ($i = 0; $i < count($values); $i++) {
                                                                        $row_ = $this->Categorias_campos_model->get_option($values[$i]);
                                                                        $values[$i] = $row_->option;
                                                                    }
                                                                    $value = implode(', ', $values);
                                                                } elseif ($campo == 'setores') {

                                                                    if ($value) {
                                                                        $value = get_departamento_nome($value);
                                                                    }
                                                                } elseif ($campo == 'funcionarios') {
                                                                    if ($value) {
                                                                        $value = get_staff_full_name($value);
                                                                    }
                                                                } elseif ($campo == 'file') {
                                                                    if ($value) {
                                                                        $value = '<a href="' . base_url() . 'assets/intranet/arquivos/ro_arquivos/campo_file/' . $value . '" target=black><i class="fa fa-file-o"></i> ' . $value . '</a>';
                                                                    }
                                                                }

                                                                $informacoes .= '' . $nome_campo . ': ' . $value . '<Br>';
                                                            }
                                                            ?>
                                                        </td>
                                                        <td>
                                                            <?php
                                                            $status = get_ro_status($ro['status']);
                                                            $status_label = '<span class="label inline-block" style="border:1px solid ' . $status['color'] . '; color:' . $status['color'] . '">' . ($status['label']) . '</span>';
                                                            echo $status_label;
                                                            ?>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>


                                </div>
                            </div>
                        </div>
                        <div role="tabpane3" class="tab-pane" id="solicitacoes">
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <div class=" _buttons">
                                        <a href="#" <?php
                                        if ($permission == false) {
                                            echo 'disabled style="pointer-events: none;
                                                cursor: default;"';
                                        }
                                        ?> class="btn btn-primary " onclick="slideToggle('.usernote'); return false;"><?php echo 'Add Registro Rápido'; ?></a>
                                    </div>
                                    <div class="mtop15">

                                        <div class="usernote hide">
                                            <?php echo form_open_multipart(base_url('gestao_corporativa/Atendimento/salvar_registro_rapido/' . $info->id), array('id' => 'new_ro_form')); ?>
                                            <input type="hidden" name="atendimento_id" value="<?php echo $info->id; ?>">
                                            <?php
                                            echo render_select('categoria_id', $categorias, array('id', 'titulo', 'name'), 'Categoria', [], array('required' => 'true'));
                                            ?>


                                            <div class="row">
                                                <div class="col-md-12" id="trocar">

                                                </div>
                                            </div>

                                            <button class="btn btn-info pull-right ">
                                                <?php echo _l('submit'); ?>
                                            </button>
                                            <?php echo form_close(); ?>
                                        </div>

                                        <div class="clearfix"></div>
                                        <div class="mtop15">
                                            <table class="table dt-table scroll-responsive" data-order-col="2" data-order-type="desc">
                                                <thead>
                                                    <tr>
                                                        <th >
                                                            <?php echo 'Descrição'; ?>
                                                        </th>
                                                        <th>
                                                            <?php echo 'Valor'; ?>
                                                        </th>

                                                        <th>
                                                            <?php echo _l('options'); ?>
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($registros_rapidos as $rr) { ?>
                                                        <tr>
                                                            <td >
                                                                <?php echo $rr['nome_campo']; ?>
                                                            </td>
                                                            <td >
                                                                <?php echo $rr['value']; ?>
                                                            </td>
                                                            <td >
                                                                <?php //echo $ro['date'];            ?>
                                                            </td>
                                                        </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>


                            </div>
                        </div>

                        <div role="tabpane4" class="tab-pane" id="workflow">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="buttons">
                                        <a target="_blank" <?php
                                        if ($permission == false) {
                                            //exit;
                                            echo 'disabled style="pointer-events: none;   cursor: default;"';
                                        }
                                        ?> class="btn btn-primary" title = "Add novo Workflow" href="<?php echo site_url('gestao_corporativa/workflow/add/' . $info->id); ?>">ADD Workflow</a>

                                    </div>


                                    <div class="mtop15">
                                        <?php
                                        foreach ($workflow_ra as $wf) {
                                            // print_r($wf);
                                            ?>
                                            <div class="panel_s ticket-reply-tools">

                                                <div class="panel-heading">
                                                    WORKFLOW #<?php echo $wf['id']; ?> 
                                                    <?php $status = get_ro_status($wf['status']); ?>
                                                    <div class="label mtop5 single-ticket-status-label" style="background: <?php echo $status['color']; ?>"><?php echo $status['label']; ?></div>
                                                    <?php echo '<a target="_blank" href="' . base_url() . 'gestao_corporativa/workflow/pdf/' . $wf['id'] . '" class="' . (is_mobile() ? ' ' : ' mleft15 ') . ' pull-right single-ticket-status-label"><i class="icon fa fa-print"></i> TRATATIVA INTERNA</a>'; ?>
                                                </div>
                                                <div class="panel-body">
                                                    <div class="row">

                                                        <div class="col-md-12 border-right">
                                                            <?php
                                                            $this->load->model('Workflow_model');
                                                            $andamento = $this->Workflow_model->get_fluxos_andamento($wf['id'], $wf['date_end_client']);
                                                            ?>
                                                            <div style="display:inline-block;width:100%;overflow-y:auto; text-align: center;">
                                                                <ul class="timeline timeline-horizontal">

                                                                    <li class="timeline-item">
                                                                        <div class="timeline-badge default"><i class="fa fa-file-text-o" aria-hidden="true"></i></div>
                                                                        <div class="timeline-panel">
                                                                            <div class="timeline-body">
                                                                                <h6 class="text-dark text-sm font-weight-bold mb-0">Solicitação</h6>
                                                                                <p class="text-secondary font-weight-normal text-xs mt-1 mb-0">Criada: <?php echo date("d/m/Y H:i:s", strtotime($wf['date_created'])); ?></p>
                                                                                <span class="badge badge-sm bg-gradient-light">Enviada</span>

                                                                            </div>
                                                                        </div>
                                                                    </li>
                                                                    <?php
                                                                    foreach ($andamento as $item) {
                                                                        ?>
                                                                        <li class="timeline-item">
                                                                            <?php if ($item['concluido'] == 1) { ?>
                                                                                <div class="timeline-badge success"><i class="fa fa-check-square-o" aria-hidden="true"></i></div>
                                                                            <?php } else { ?>
                                                                                <div class="timeline-badge info"><i class="fa fa-spinner" aria-hidden="true"></i></div>
                                                                            <?php } ?>
                                                                            <div class="timeline-panel">
                                                                                <div class="timeline-body">
                                                                                    <h6 class="text-dark text-sm font-weight-bold mb-0"><?php echo $item['setor_name']; ?></h6>
                                                                                    <?php if (!$item['data_assumido'] && !$item['data_concluido']) { ?>
                                                                                        <p class="text-secondary font-weight-normal text-xs mt-1 mb-0">Recebida: <?php echo date("d/m/Y H:i:s", strtotime($item['date_created'])); ?></p>
                                                                                    <?php } else { ?>
                                                                                        <p class="text-secondary font-weight-normal text-xs mt-1 mb-0">Assumida: <?php echo date("d/m/Y H:i:s", strtotime($item['data_assumido'])); ?></p>
                                                                                        <?php if ($item['data_concluido']) { ?>
                                                                                            <p class="text-secondary font-weight-normal text-xs mt-1 mb-0">Concluída: <?php echo date("d/m/Y H:i:s", strtotime($item['data_concluido'])); ?></p>
                                                                                        <?php } ?>
                                                                                    <?php } ?>
                                                                                    <?php if ($item['concluido'] == 1) { ?>
                                                                                        <span class="badge badge-sm bg-gradient-success">CONCLUÍDA</span>
                                                                                    <?php } else { ?>
                                                                                        <span class="badge badge-sm bg-gradient-info">EM ANDAMENTO</span>
                                                                                    <?php } ?>


                                                                                </div>
                                                                            </div>
                                                                        </li>
                                                                    <?php } ?>
                                                                    <?php if ($wf['status'] == 3) { ?>
                                                                        <li class="timeline-item">
                                                                            <div class="timeline-badge success"><i class="fa fa-check-square" aria-hidden="true"></i></div>
                                                                            <div class="timeline-panel">
                                                                                <div class="timeline-body">
                                                                                    <h6 class="text-dark text-sm font-weight-bold mb-0">Finalizado</h6>
                                                                                    <p class="text-secondary font-weight-normal text-xs mt-1 mb-0"><?php echo date("d/m/Y H:i:s", strtotime($wf['date_end'])); ?></p>

                                                                                </div>
                                                                            </div>
                                                                        </li>
                                                                    <?php } else { ?>
                                                                        <li class="timeline-item">
                                                                            <div class="timeline-badge warning"><i class="fa fa-clock-o" aria-hidden="true"></i></div>
                                                                            <div class="timeline-panel">
                                                                                <div class="timeline-body">
                                                                                    <h6 class="text-dark text-sm font-weight-bold mb-0">Prazo Estimado</h6>
                                                                                    <p class="text-secondary font-weight-normal text-xs mt-1 mb-0"><?php
                                                                                        if ($wf['date_prazo_client'] and $wf['date_prazo_client'] != '0000-00-00 00:00:00' and $wf['date_prazo_client'] != '1969-12-31') {
                                                                                            echo date("d/m/Y", strtotime($wf['date_prazo_client']));
                                                                                        } else {
                                                                                            echo 'Sem Prazo';
                                                                                        }
                                                                                        ?></p>
                                                                                    <span class="badge badge-sm bg-gradient-warning">EM PROGRESSO</span>
                                                                                </div>
                                                                            </div>
                                                                        </li>
                                                                    <?php } ?>


                                                                </ul>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="panel-footer">
                                                    <div class="row">

                                                        <div class="col-lg-4 col-md-6 col-12">
                                                            <h6 class="mb-3">Detalhes da Solicitação</h6>
                                                            <ul class="list-group">
                                                                <li class="list-group-item border-0 d-flex p-4 mb-2 bg-gray-100 border-radius-lg">
                                                                    <div class="d-flex flex-column">
                                                                        <h6 class="mb-3 text-sm">Solicitação #<?php echo $wf['id']; ?></h6>
                                                                        <span class="mb-2 text-xs">Tipo: <span class="text-dark font-weight-bold ms-2"><?php echo $wf['titulo']; ?></span></span>

                                                                        <span class="mb-2 text-xs">Data de Início: <span class="text-dark ms-2 font-weight-bold"><?php echo date("d/m/Y H:i:s", strtotime($wf['date_start'])); ?></span></span>
                                                                        <span class="text-xs">Data de Finalização: <span class="text-dark ms-2 font-weight-bold"><?php
                                                                                if ($wf['date_end']) {
                                                                                    echo date("d/m/Y H:i:s", strtotime($wf['date_end']));
                                                                                } else {
                                                                                    echo 'AGUARDANDO';
                                                                                }
                                                                                ?></span></span>
                                                                        <?php
                                                                        if ($wf['status'] == 4) {
                                                                            $this->load->model('Company_model');

                                                                            $cancel = $this->Company_model->get_cancel_workflow($wf['cancel_id']);
                                                                            // print_r($cancel);
                                                                            ?>
                                                                            <br>
                                                                            <span class="text-sm " style="color: red;">SOLICITAÇÃO CANCELADA!</span>
                                                                            <?php if ($cancel->cancellation) { ?>
                                                                                <strong class="text-xs text-dark font-weight-bold"><?php echo $cancel->cancellation; ?></strong>
                                                                            <?php } ?>
                                                                            <?php if ($wf['obs']) { ?>
                                                                                <span class="text-xs">Observações: <?php echo $wf['obs']; ?></span>
                                                                            <?php } ?>

                                                                        <?php } ?>
                                                                    </div>
                                                                </li>
                                                                <?php if ($wf['status'] != 4) { ?>

                                                                    <li class="list-group-item border-0 d-flex p-4 mb-2 bg-gray-100 border-radius-lg">
                                                                        <div class="d-flex flex-column">
                                                                            <h6 class="mb-3 text-sm">INFORMAÇÕES PARA O CLIENTE:</h6>
                                                                            <?php
                                                                            $exist = false;
                                                                            ?>
                                                                            <?php
                                                                            $values = $this->Categorias_campos_model->get_values($wf['id'], 'workflow', '', false, true);
                                                                            foreach ($values as $value) {
                                                                                $exist = true;
                                                                                ?>
                                                                                <span class="mb-2 text-xs"><?php echo $value['nome_campo']; ?>: <span class="text-dark font-weight-bold ms-2"><?php echo get_value('workflow', $value['value'], $value['tipo_campo']); ?></span></span>
                                                                            <?php } ?>

                                                                            <?php if ($exist == false) { ?>
                                                                                <span class="mb-2 text-xs">Sem dados...</span>
                                                                            <?php } ?>
                                                                        </div>
                                                                    </li>
                                                                <?php } ?>


                                                            </ul>
                                                        </div>
                                                        <div class="col-lg-8 col-12 ms-auto mt-5">
                                                            <?php
                                                            $campos = [];
                                                            $values_info['rel_type'] = 'workflow';
                                                            $values_info['campos'] = $this->Categorias_campos_model->get_values($wf['id'], 'workflow', true, false, false);
                                                            $this->load->view('gestao_corporativa/categorias_campos/values_info3', $values_info);
                                                            ?>


                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        <?php } ?>

                                        <!--<table class="table dt-table scroll-responsive" data-order-col="2" data-order-type="desc">
                                            <thead>
                                                <tr>
                                                    <th >
                                        <?php echo 'ID'; ?>
                                                    </th>
                                                    <th>
                                        <?php echo 'Categoria'; ?>
                                                    </th>
                                                    <th>
                                        <?php echo 'Campos Chaves'; ?>
                                                    </th>
                                                    <th>
                                        <?php echo 'Data Início'; ?>
                                                    </th>
                                                    <th>
                                        <?php echo 'Data Fim'; ?>
                                                    </th>
                                                    <th>
                                        <?php echo 'Status'; ?>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                        <?php foreach ($workflow_ra as $wf) { ?>
                                                                                                                <tr>
                                                                                                                    <td >
                                            <?php echo $wf['id']; ?>
                                                                                                                    </td>   
                                                                                                                    <td >
                                            <?php echo $wf['titulo']; ?>
                                                                                                                    </td>
                                                                                                                    <td >
                                            <?php
                                            $this->load->model('Categorias_campos_model');

                                            $chaves = $this->Categorias_campos_model->get_values($info->id, 'workflow', false, true);
                                            foreach ($chaves as $info) {
                                                $nome_campo = $info['nome_campo'];
                                                $campo = $info['tipo_campo'];
                                                $value = $info['value'];

                                                if ($campo == 'multiselect' || $campo == 'select') {
                                                    $values = explode(',', $value);
                                                    for ($i = 0; $i < count($values); $i++) {
                                                        $row_ = $this->Categorias_campos_model->get_option($values[$i]);
                                                        $values[$i] = $row_->option;
                                                    }
                                                    $value = implode(', ', $values);
                                                } elseif ($campo == 'setores') {

                                                    if ($value) {
                                                        $value = get_departamento_nome($value);
                                                    }
                                                } elseif ($campo == 'funcionarios') {
                                                    if ($value) {
                                                        $value = get_staff_full_name($value);
                                                    }
                                                } elseif ($campo == 'file') {
                                                    if ($value) {
                                                        $value = '<a href="' . base_url() . 'assets/intranet/arquivos/ro_arquivos/campo_file/' . $value . '" target=black><i class="fa fa-file-o"></i> ' . $value . '</a>';
                                                    }
                                                }

                                                $informacoes .= '' . $nome_campo . ': ' . $value . '<Br>';
                                            }
                                            ?>
                                                                                                                    </td>

                                                                                                                    <td >
                                            <?php echo $wf['date_start']; ?>
                                                                                                                    </td>
                                                                                                                    <td >
                                            <?php echo $wf['date_end']; ?>
                                                                                                                    </td>
                                                                                                                    <td>
                                            <?php echo $wf['status']; ?>
                                                                                                                    </td>


                                                                                                                </tr>
                                        <?php } ?>
                                            </tbody>
                                        </table>-->
                                        <div class="clearfix"></div>

                                    </div>

                                </div>
                            </div>
                        </div>
                        <div role="tabpane3" class="tab-pane" id="email">
                            <div class="row">
                                <?php
                                $data['rel_type'] = 'atendimento';
                                $data['rel_id'] = $info->id;
                                $data['url_retorno'] = 'gestao_corporativa/Atendimento/view';
                                $emails_ra = [];
                                if ($info->email) {
                                    //exit;
                                    array_push($emails_ra, $info->email);
                                }
                                $data['list_email'] = $emails_ra;
                                if ($permission == false) {

                                    $data['disabled'] = 'disabled';
                                } else {
                                    $data['disabled'] = '';
                                }

                                $this->load->view('gestao_corporativa/Email/index', $data);
                                ?>
                            </div>
                        </div>
                        <div role="sms" class="tab-pane" id="sms">
                            <div class="row">
                                <?php
                                $data['rel_type'] = 'atendimento';
                                $data['rel_id'] = $info->id;
                                $data['url_retorno'] = 'gestao_corporativa/Atendimento/view';
                                $data['list_email'] = [];
                                if ($permission == false) {
                                    $data['disabled'] = 'disabled';
                                } else {
                                    $data['disabled'] = '';
                                }
                                $data['client_number'] = $info->contato;
                                $this->load->view('gestao_corporativa/Sms/index', $data);
                                ?>
                            </div>
                        </div>
                        <div role="aut" class="tab-pane" id="aut">
                            <div class="row">
                                <div class="col-md-12">

                                    <div class="mtop15">
                                        <table class="table dt-table scroll-responsive" data-order-col="2" data-order-type="desc">
                                            <thead>
                                                <tr>
                                                    <th >
                                                        <?php echo 'ID'; ?>
                                                    </th>
                                                    <th>
                                                        <?php echo 'Autosserviço'; ?>
                                                    </th>
                                                    <th>
                                                        <?php echo 'Descrição'; ?>
                                                    </th>
                                                    <th>
                                                        <?php echo 'Data da Ação'; ?>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($auts as $aut) { ?>
                                                    <tr>
                                                        <td >
                                                            <?php echo $aut['id']; ?>
                                                        </td>   
                                                        <td >
                                                            <?php echo $aut['titulo']; ?>
                                                        </td>
                                                        <td >
                                                            <?php echo $aut['description']; ?>
                                                        </td>
                                                        <td >
                                                            <?php echo date("d/m/Y", strtotime($aut['date_created'])); ?>
                                                        </td>


                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                        <div class="clearfix"></div>

                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>



        </div>
    </div>

</div>

<?php init_tail(); ?>
<?php hooks()->do_action('new_ticket_admin_page_loaded'); ?>
<script>
    $(function () {
<?php if (!$data_encerramento) { ?>
            inicia();
<?php } ?>

        init_ajax_search('contact', '#contactid.ajax-search', {
            tickets_contacts: true,
            contact_userid: function () {
                // when ticket is directly linked to project only search project client id contacts
                var uid = $('select[data-auto-project="true"]').attr('data-project-userid');
                if (uid) {
                    return uid;
                } else {
                    return '';
                }
            }
        });

        validate_new_ticket_form();

<?php if (isset($project_id) || isset($contact)) { ?>
            $('body.ticket select[name="contactid"]').change();
<?php } ?>

<?php if (isset($project_id)) { ?>
            $('body').on('selected.cleared.ajax.bootstrap.select', 'select[data-auto-project="true"]', function (e) {
                $('input[name="userid"]').val('');
                $(this).parents('.projects-wrapper').addClass('hide');
                $(this).prop('disabled', false);
                $(this).removeAttr('data-auto-project');
                $('body.ticket select[name="contactid"]').change();
            });
<?php } ?>
    });




    $(document).on('change', "#categoria_id", function () {
        var select = document.getElementById("categoria_id");
        var opcaoValor = select.options[select.selectedIndex].value;
        if (opcaoValor != "") {
            $.ajax({
                type: "POST",
                url: "<?php echo base_url('gestao_corporativa/categorias_campos/retorno_categoria_campos?rel_type=ra_atendimento_rapido'); ?>",
                data: {
                    categoria_id: opcaoValor
                },
                success: function (data) {
                    $('#trocar').html(data);
                }
            });
        } else {
            alert('Selecione euma categoria!');
        }
    });


    //:$minutos:$segundos
    function formatatempo(segs) {
        min = <?php echo $minutos; ?>;
        hr = <?php echo $horas; ?>;
        /*
         if hr < 10 then hr = "0"&hr
         if min < 10 then min = "0"&min
         if segs < 10 then segs = "0"&segs
         */
        while (segs >= 60) {
            if (segs >= 60) {
                segs = segs - 60;
                min = min + 1;
            }
        }

        while (min >= 60) {
            if (min >= 60) {
                min = min - 60;
                hr = hr + 1;
            }
        }

        if (hr < 10) {
            hr = "0" + hr
        }
        if (min < 10) {
            min = "0" + min
        }
        if (segs < 10) {
            segs = "0" + segs
        }
        fin = hr + ":" + min + ":" + segs
        return fin;
    }
    var segundos = <?php echo $segundos; ?>; //inicio do cronometro
    function conta() {
        segundos++;
        document.getElementById("counter").innerHTML = formatatempo(segundos);
    }

    function inicia() {
        interval = setInterval("conta();", 1000);
    }

    function para() {
        clearInterval(interval);
    }

    function zera() {
        clearInterval(interval);
        segundos = 0;
        document.getElementById("counter").innerHTML = formatatempo(segundos);
    }


    $('#btnGerarTranscricao').on('click', function () {
        var $btn = $(this);
        $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Gerando...');

        $.ajax({
            url: '<?php echo base_url('gestao_corporativa/Atendimento/transcricao_nota/' . $info->id); ?>',
            type: 'GET',
            dataType: 'json',
            success: function (res) {
                if (res.success) {
                    $('#textoTranscricao').val(res.texto);
                } else {
                    alert('Não foi possível gerar a transcrição.');
                }
            },
            error: function () {
                alert('Erro ao gerar a transcrição.');
            },
            complete: function () {
                $btn.prop('disabled', false).html('<i class="fa fa-file-text-o"></i> Transcrição');
            }
        });
    });

    $('#btnCopiarTranscricao').on('click', function () {
        var texto = $('#textoTranscricao').val();

        if (!texto) {
            alert('Gere a transcrição primeiro.');
            return;
        }

        navigator.clipboard.writeText(texto).then(function () {
            alert('Texto copiado com sucesso.');
        });
    });

    $('#btnInserirTranscricao').on('click', function () {
        var texto = $('#textoTranscricao').val();

        if (!texto) {
            alert('Gere a transcrição primeiro.');
            return;
        }

        // abre a área de nova nota, se estiver fechada
        var $boxNota = $('#note_atendimento .usernote');
        if ($boxNota.hasClass('hide')) {
            slideToggle('#note_atendimento .usernote');
        }

        setTimeout(function () {
            var $campoNota = $('#note_atendimento textarea[name="note"]');

            if ($campoNota.length) {
                $campoNota.val(texto);
                $campoNota.trigger('input');
                $campoNota.trigger('change');
                $campoNota.focus();
            } else {
                alert('Campo da nota não encontrado.');
            }
        }, 200);
    });
</script>


</body>
</html>
