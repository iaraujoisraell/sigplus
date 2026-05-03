<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Obrigado — <?php echo html_escape($form['titulo']); ?></title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,700&display=swap">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/lte/plugins/fontawesome-free/css/all.min.css">
    <style>
        body{margin:0;padding:60px 12px;background:#f1f1f1;font-family:'Open Sans',sans-serif;color:#1f2937;}
        .pg{max-width:560px;margin:0 auto;background:#fff;border-radius:10px;padding:40px 32px;text-align:center;box-shadow:0 1px 3px rgba(0,0,0,.06);border-top:8px solid #16a34a;}
        .pg .icon{font-size:48px;color:#16a34a;margin-bottom:14px;}
        .pg h1{margin:0 0 8px;font-size:22px;color:#1f2937;}
        .pg .msg{font-size:14px;color:#475569;line-height:1.5;}
        .pg .actions{margin-top:24px;}
        .pg .actions a{color:#0a66c2;text-decoration:none;font-size:13px;font-weight:600;}
    </style>
</head>
<body>
<div class="pg">
    <div class="icon"><i class="fa fa-check-circle"></i></div>
    <h1><?php echo html_escape($form['titulo']); ?></h1>
    <div class="msg">
        <?php if (!empty($form['success_submit_msg'])): ?>
            <?php echo $form['success_submit_msg']; ?>
        <?php else: ?>
            Sua resposta foi registrada. Obrigado!
        <?php endif; ?>
    </div>
    <div class="actions">
        <a href="<?php echo base_url('formularios/web/' . $form['form_key']); ?>"><i class="fa fa-redo"></i> Enviar outra resposta</a>
    </div>
</div>
</body>
</html>
