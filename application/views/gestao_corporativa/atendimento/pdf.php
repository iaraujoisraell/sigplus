<html>
<head>
    <title>RA#<?php echo $info->id; ?></title>
    <style>
        @page {
            margin: 120px 40px 70px 40px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #222;
        }

        header {
            position: fixed;
            top: -95px;
            left: 0;
            right: 0;
            height: 90px;
        }

        footer {
            position: fixed;
            bottom: -45px;
            left: 0;
            right: 0;
            height: 35px;
            color: #666;
            font-size: 10px;
            text-align: right;
        }

        .pagenum:before {
            content: counter(page);
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table td, .table th {
            border: 1px solid #bdbdbd;
            padding: 6px 8px;
            vertical-align: top;
        }

        .section-title {
            background: #0f9d58;
            color: #fff;
            padding: 7px 10px;
            font-weight: bold;
            margin-top: 14px;
            margin-bottom: 0;
            border: 1px solid #0b7d45;
        }

        .sub-title {
            font-weight: bold;
            font-size: 13px;
            margin: 10px 0 6px 0;
            color: #1565c0;
        }

        .muted {
            color: #666;
        }

        .zebra:nth-child(even) {
            background: #f8f9fa;
        }

        .badge {
            display: inline-block;
            padding: 2px 6px;
            font-size: 10px;
            border: 1px solid #bbb;
            border-radius: 3px;
        }

        .text-center {
            text-align: center;
        }

        .mb10 { margin-bottom: 10px; }
        .mt10 { margin-top: 10px; }

        ul.clean {
            margin: 6px 0 10px 18px;
            padding: 0;
        }

        ul.clean li {
            margin-bottom: 5px;
        }

        .box {
            border: 1px solid #d6d6d6;
            padding: 8px;
            margin-bottom: 10px;
            background: #fcfcfc;
        }
    </style>
</head>
<body>

<?php
$startTime = strtotime($info->date_created);
$endTime   = $info->data_encerramento ? strtotime($info->data_encerramento) : time();
$diff      = $endTime - $startTime;

$days    = floor($diff / 86400);
$hours   = floor(($diff % 86400) / 3600);
$minutes = floor(($diff % 3600) / 60);
$seconds = $diff % 60;

$tempo = '';
if ($days > 0) {
    $tempo .= $days . ' dia(s) ';
}
$tempo .= str_pad($hours, 2, '0', STR_PAD_LEFT) . ':'
       . str_pad($minutes, 2, '0', STR_PAD_LEFT) . ':'
       . str_pad($seconds, 2, '0', STR_PAD_LEFT);

$canal = ($info->canal_atendimento_id == 0) ? 'PORTAL DO CLIENTE' : $info->canal;
$categoria = ($info->categoria_id == 0) ? 'PORTAL DO CLIENTE' : $info->titulo;
?>

<header>
    <table class="table">
        <tr>
            <td rowspan="3" style="width: 26%; text-align:center;">
                <img style="height: 58px;" src="<?php echo $base64; ?>">
            </td>
            <td rowspan="3" style="width: 36%; text-align:center; font-size:18px; font-weight:bold;">
                REGISTRO DE ATENDIMENTO #<?php echo $info->id; ?>
            </td>
            <td style="width: 38%;"><strong>Protocolo:</strong> <?php echo $info->protocolo; ?></td>
        </tr>
        <tr>
            <td><strong>Data:</strong> <?php echo _dt($info->date_created); ?></td>
        </tr>
        <tr>
            <td><strong>Tempo de atendimento:</strong> <?php echo $tempo; ?></td>
        </tr>
    </table>
</header>

<footer>
    Relatório gerado em <?php echo date('d/m/Y H:i:s'); ?> por <?php echo get_staff_full_name(); ?>
    | Página <span class="pagenum"></span>
</footer>

<main>
    <table class="table mb10">
        <tr>
            <td><strong>Canal Atendimento:</strong> <?php echo $canal; ?></td>
            <td><strong>Categoria:</strong> <?php echo $categoria; ?></td>
        </tr>
        <tr>
            <td><strong>Contato:</strong> <?php echo $info->contato ? $info->contato : 'Sem informação'; ?></td>
            <td><strong>E-mail:</strong> <?php echo $info->email ? $info->email : 'Sem informação'; ?></td>
        </tr>
    </table>

    <?php if (is_array($info_client)) { ?>
        <div class="section-title">DADOS DO BENEFICIÁRIO</div>
        <table class="table">
            <tr class="zebra">
                <td style="width:40%;"><strong>Cliente / Carteirinha</strong></td>
                <td><?php echo $info_client['NOME_ABREV'] . ' - ' . $info_client['NUMERO_CARTEIRINHA']; ?></td>
            </tr>
            <tr class="zebra">
                <td><strong>E-mail / Telefone</strong></td>
                <td><?php echo $info_client['EMAIL'] ?: 'Sem informação'; ?> - <?php echo $info_client['TELEFONE'] ?: 'Sem informação'; ?></td>
            </tr>
            <tr class="zebra">
                <td><strong>Contratante</strong></td>
                <td><?php echo $info_client['CONTRATANTE'] ?: 'Sem informação'; ?> - <?php echo $info_client['CPF_CONTRATANTE'] ?: 'Sem informação'; ?></td>
            </tr>
            <tr class="zebra">
                <td><strong>CPF</strong></td>
                <td><?php echo $info_client['CPF'] ?: 'Sem informação'; ?></td>
            </tr>
            <tr class="zebra">
                <td><strong>Data de Nascimento</strong></td>
                <td><?php echo $info_client['DATADENASCIMENTO'] ?: 'Sem informação'; ?></td>
            </tr>
            <tr class="zebra">
                <td><strong>Titular / Situação</strong></td>
                <td><?php echo $info_client['TITULAR'] ?: 'Sem informação'; ?> - <?php echo $info_client['SITUACAO'] ?: 'Sem informação'; ?></td>
            </tr>
        </table>
    <?php } ?>
    

    <?php if (!empty($campos)) { ?>
        <div class="section-title">OUTRAS INFORMAÇÕES</div>
        <table class="table">
            <tbody>
                <?php foreach ($campos as $campo) { ?>
                    <?php if ($campo['tipo_campo'] != 'separador') { ?>
                        <tr class="zebra">
                            <td style="width:40%;"><strong><?php echo $campo['nome_campo']; ?></strong></td>
                            <td><?php echo get_value('atendimento', $campo['value'], $campo['tipo_campo'], false); ?></td>
                        </tr>
                    <?php } ?>
                <?php } ?>
            </tbody>
        </table>
    <?php } ?>

   <?php if (!empty($notes)) { ?>
    <div class="section-title">NOTAS</div>

    <?php foreach ($notes as $note) { ?>
        <?php
        $texto = '';
        $dataNota = '';
        $autor = '';

        if (is_array($note)) {
            $texto = !empty($note['description']) ? $note['description'] : (!empty($note['note']) ? $note['note'] : '');
            $dataNota = !empty($note['dateadded']) ? $note['dateadded'] : '';
            $autor = !empty($note['firstname']) ? $note['firstname'] . ' ' . ($note['lastname'] ?? '') : '';
        } else {
            $texto = !empty($note->description) ? $note->description : (!empty($note->note) ? $note->note : '');
            $dataNota = !empty($note->dateadded) ? $note->dateadded : '';
            $autor = !empty($note->firstname) ? $note->firstname . ' ' . ($note->lastname ?? '') : '';
        }
        ?>

        <div class="box">
            <div class="muted">
                <?php echo $dataNota ? _dt($dataNota) : ''; ?>
                <?php if ($autor) { ?>
                    - <?php echo trim($autor); ?>
                <?php } ?>
            </div>
            <div><?php echo nl2br($texto); ?></div>
        </div>
    <?php } ?>
<?php } ?>

    <?php if (!empty($registros_rapidos)) { ?>
        <div class="section-title">SOLICITAÇÕES RÁPIDAS</div>
        <table class="table">
            <thead>
                <tr>
                    <th style="width:45%;">Descrição</th>
                    <th>Valor</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($registros_rapidos as $rr) { ?>
                    <tr class="zebra">
                        <td><?php echo $rr['nome_campo']; ?></td>
                        <td><?php echo $rr['value']; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php } ?>

    <?php if (!empty($ros_ra)) { ?>
        <div class="section-title">REGISTROS DE OCORRÊNCIA</div>
        <?php foreach ($ros_ra as $ro) { ?>
            <div class="box">
                <div><strong>RO #<?php echo $ro['id']; ?></strong> - <?php echo $ro['titulo']; ?></div>
                <div class="muted">Data: <?php echo _dt($ro['date_created']); ?></div>
                <?php if (!empty($ro['categoria'])) { ?>
                    <div><strong>Categoria:</strong> <?php echo $ro['categoria']; ?></div>
                <?php } ?>
                <?php if (!empty($ro['responsavel'])) { ?>
                    <div><strong>Setor Responsável:</strong> <?php echo $ro['responsavel']; ?></div>
                <?php } ?>
                <?php if (!empty($ro['prioridade'])) { ?>
                    <div><strong>Prioridade:</strong> <?php echo $ro['prioridade']; ?></div>
                <?php } ?>
            </div>
        <?php } ?>
    <?php } ?>

    <?php if (!empty($workflow_ra)) { ?>
        <div class="section-title">WORKFLOWS</div>
        <?php foreach ($workflow_ra as $wf) { ?>
            <div class="box">
                <div><strong>WF #<?php echo $wf['id']; ?></strong> - <?php echo $wf['titulo']; ?></div>
                <div class="muted">Criado em: <?php echo _dt($wf['date_created']); ?></div>
                <div><strong>Início:</strong> <?php echo !empty($wf['date_start']) ? _dt($wf['date_start']) : 'Sem informação'; ?></div>
                <div><strong>Fim:</strong> <?php echo !empty($wf['date_end']) ? _dt($wf['date_end']) : 'Em andamento'; ?></div>
                <?php if (!empty($wf['date_prazo_client']) && $wf['date_prazo_client'] != '0000-00-00 00:00:00') { ?>
                    <div><strong>Prazo estimado:</strong> <?php echo _d($wf['date_prazo_client']); ?></div>
                <?php } ?>
                <?php if (!empty($wf['obs'])) { ?>
                    <div><strong>Observações:</strong> <?php echo $wf['obs']; ?></div>
                <?php } ?>
            </div>
        <?php } ?>
    <?php } ?>

    <?php if (!empty($auts)) { ?>
        <div class="section-title">AUTOSSERVIÇOS</div>
        <table class="table">
            <thead>
                <tr>
                    <th style="width:12%;">ID</th>
                    <th style="width:28%;">Autosserviço</th>
                    <th>Descrição</th>
                    <th style="width:18%;">Data</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($auts as $aut) { ?>
                    <tr class="zebra">
                        <td><?php echo $aut['id']; ?></td>
                        <td><?php echo $aut['titulo']; ?></td>
                        <td><?php echo $aut['description']; ?></td>
                        <td><?php echo _d($aut['date_created']); ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php } ?>
</main>
</body>
</html>