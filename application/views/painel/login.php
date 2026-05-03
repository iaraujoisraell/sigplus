<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login · Painel SaaS</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/lte/plugins/fontawesome-free/css/all.min.css">
    <style>
        body{margin:0;font-family:'Inter',sans-serif;background:linear-gradient(135deg,#0f172a,#1e3a8a);min-height:100vh;display:flex;align-items:center;justify-content:center;padding:20px;}
        .card{background:#fff;border-radius:14px;padding:36px 32px;width:100%;max-width:380px;box-shadow:0 20px 60px rgba(0,0,0,.3);}
        .brand{text-align:center;margin-bottom:22px;}
        .brand i{font-size:36px;color:#0a66c2;display:block;margin-bottom:6px;}
        .brand h1{margin:0;font-size:18px;color:#0f172a;font-weight:700;}
        .brand small{color:#64748b;font-size:12px;font-weight:500;}
        label{font-size:11px;font-weight:700;color:#475569;text-transform:uppercase;letter-spacing:.04em;display:block;margin-bottom:4px;margin-top:14px;}
        input{width:100%;border:1px solid #d0d5dd;border-radius:8px;padding:10px 12px;font-size:14px;font-family:inherit;}
        input:focus{outline:none;border-color:#0a66c2;box-shadow:0 0 0 3px rgba(10,102,194,.15);}
        button{width:100%;background:#0a66c2;color:#fff;border:0;border-radius:8px;padding:11px;font-size:14px;font-weight:600;cursor:pointer;margin-top:18px;}
        button:hover{background:#084b8e;}
        .erro{background:#fee2e2;color:#991b1b;padding:10px 14px;border-radius:8px;margin-bottom:14px;font-size:13px;border-left:3px solid #dc2626;}
        .footer{text-align:center;font-size:11px;color:#94a3b8;margin-top:18px;}
    </style>
</head>
<body>
    <div class="card">
        <div class="brand">
            <i class="fa fa-cubes"></i>
            <h1>Painel SaaS</h1>
            <small>gestão multiempresa · sigplus</small>
        </div>

        <?php if (!empty($erro)): ?>
            <div class="erro"><?php echo $erro; ?></div>
        <?php endif; ?>

        <?php echo form_open('painel/login'); ?>
            <label>E-mail</label>
            <input type="email" name="email" required autofocus value="<?php echo html_escape($this->input->post('email')); ?>">
            <label>Senha</label>
            <input type="password" name="password" required>
            <button type="submit"><i class="fa fa-sign-in-alt"></i> Entrar</button>
        <?php echo form_close(); ?>

        <div class="footer">© <?php echo date('Y'); ?> sigplus</div>
    </div>
</body>
</html>
