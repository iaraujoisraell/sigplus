<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Formulário indisponível</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,700&display=swap">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/lte/plugins/fontawesome-free/css/all.min.css">
    <style>
        body{margin:0;padding:60px 12px;background:#f1f1f1;font-family:'Open Sans',sans-serif;color:#1f2937;}
        .pg{max-width:560px;margin:0 auto;background:#fff;border-radius:10px;padding:40px 32px;text-align:center;box-shadow:0 1px 3px rgba(0,0,0,.06);border-top:8px solid #94a3b8;}
        .pg .icon{font-size:48px;color:#94a3b8;margin-bottom:14px;}
        .pg h1{margin:0 0 8px;font-size:20px;color:#1f2937;}
        .pg .msg{font-size:14px;color:#475569;line-height:1.5;}
    </style>
</head>
<body>
<div class="pg">
    <div class="icon"><i class="fa fa-lock"></i></div>
    <h1>Formulário indisponível</h1>
    <div class="msg">
        <?php if ($motivo === 'rascunho'): ?>
            Este formulário ainda não foi publicado.
        <?php elseif ($motivo === 'encerrado'): ?>
            Este formulário foi encerrado e não está mais aceitando respostas.
        <?php elseif ($motivo === 'arquivado'): ?>
            Este formulário foi arquivado.
        <?php else: ?>
            Este formulário não está disponível no momento.
        <?php endif; ?>
    </div>
</div>
</body>
</html>
