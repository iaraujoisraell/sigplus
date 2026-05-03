<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo html_escape($form['titulo']); ?></title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,700&display=swap">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/lte/plugins/fontawesome-free/css/all.min.css">
    <style>
        *{box-sizing:border-box;}
        body{margin:0;padding:24px 12px;background:#f1f1f1;font-family:'Open Sans',sans-serif;color:#1f2937;}
        .pg{max-width:720px;margin:0 auto;}
        .header-card{background:linear-gradient(180deg,#0a66c2,#084b8e);border-radius:10px 10px 0 0;padding:30px;color:#fff;border-top:8px solid #0a66c2;}
        .header-card .logo{max-height:50px;margin-bottom:14px;}
        .header-card h1{margin:0 0 6px;font-size:26px;}
        .header-card .desc{font-size:14px;opacity:.9;line-height:1.5;}
        .header-card .empresa{font-size:12px;opacity:.8;margin-bottom:8px;text-transform:uppercase;letter-spacing:.06em;}

        .perg-card{background:#fff;border-radius:10px;padding:20px 24px;margin-top:12px;box-shadow:0 1px 3px rgba(0,0,0,.04);}
        .perg-card .lbl{font-size:15px;font-weight:600;color:#1f2937;margin-bottom:4px;}
        .perg-card .lbl .req{color:#dc2626;}
        .perg-card .desc{font-size:12px;color:#64748b;margin-bottom:10px;}
        .perg-card input[type=text],.perg-card input[type=email],.perg-card input[type=number],.perg-card input[type=date],.perg-card input[type=datetime-local],.perg-card textarea,.perg-card select{
            width:100%;border:0;border-bottom:1px solid #d0d5dd;padding:8px 0;font-size:14px;font-family:inherit;background:transparent;
        }
        .perg-card input:focus,.perg-card textarea:focus,.perg-card select:focus{outline:none;border-bottom-color:#0a66c2;border-bottom-width:2px;padding-bottom:7px;}
        .perg-card textarea{resize:vertical;min-height:60px;}
        .perg-card .opc{display:flex;align-items:center;gap:10px;padding:6px 0;cursor:pointer;font-size:14px;color:#475569;}
        .perg-card .opc:hover{color:#0a66c2;}
        .perg-card .opc input{margin:0;width:18px;height:18px;}
        .perg-card .scale{display:flex;justify-content:space-between;align-items:center;padding:8px 0;}
        .perg-card .scale label{display:flex;flex-direction:column;align-items:center;gap:4px;cursor:pointer;font-size:12px;color:#64748b;}
        .perg-card .scale label:hover{color:#0a66c2;}

        .footer{display:flex;justify-content:flex-end;margin-top:18px;padding:0 0 30px;}
        .btn-enviar{background:#0a66c2;color:#fff;border:0;padding:12px 28px;border-radius:6px;font-size:14px;font-weight:600;cursor:pointer;}
        .btn-enviar:hover{background:#084b8e;}

        .footer-info{text-align:center;font-size:11px;color:#94a3b8;padding:18px 0;}
        .footer-info a{color:#94a3b8;text-decoration:none;}
    </style>
</head>
<body>

<div class="pg">

    <div class="header-card">
        <?php if (!empty($company_logo)): ?>
            <img src="<?php echo base_url('uploads/company/' . $company_logo); ?>" class="logo" alt="">
        <?php endif; ?>
        <?php if (!empty($company_name)): ?>
            <div class="empresa"><?php echo html_escape($company_name); ?></div>
        <?php endif; ?>
        <h1><?php echo html_escape($form['titulo']); ?></h1>
        <?php if (!empty($form['descricao'])): ?>
            <div class="desc"><?php echo $form['descricao']; ?></div>
        <?php endif; ?>
    </div>

    <form method="post" action="<?php echo base_url('formularios/web/' . $form['form_key']); ?>">
        <input type="hidden" name="hash" value="<?php echo app_generate_hash(); ?>">
        <?php echo $this->security->get_csrf_token_name() ? '<input type="hidden" name="' . $this->security->get_csrf_token_name() . '" value="' . $this->security->get_csrf_hash() . '">' : ''; ?>

        <?php if (empty($perguntas)): ?>
            <div class="perg-card" style="text-align:center;color:#94a3b8;">
                Este formulário ainda não tem perguntas.
            </div>
        <?php else: ?>
            <?php foreach ($perguntas as $p):
                $cfg = $p['configuracao_arr'] ?? [];
                $ph = html_escape($cfg['placeholder'] ?? '');
                $req = !empty($p['required']);
                $name = 'r[' . (int) $p['id'] . ']';
            ?>
                <div class="perg-card">
                    <div class="lbl">
                        <?php echo html_escape($p['title']); ?>
                        <?php if ($req): ?><span class="req">*</span><?php endif; ?>
                    </div>
                    <?php if (!empty($p['descricao'])): ?>
                        <div class="desc"><?php echo html_escape($p['descricao']); ?></div>
                    <?php endif; ?>

                    <?php switch ($p['tipo']):
                        case 'textarea': ?>
                            <textarea name="<?php echo $name; ?>" rows="3" placeholder="<?php echo $ph; ?>" <?php echo $req ? 'required' : ''; ?>></textarea>
                            <?php break;

                        case 'number': ?>
                            <input type="number" name="<?php echo $name; ?>" placeholder="<?php echo $ph; ?>" <?php echo $req ? 'required' : ''; ?>>
                            <?php break;

                        case 'date': ?>
                            <input type="date" name="<?php echo $name; ?>" <?php echo $req ? 'required' : ''; ?>>
                            <?php break;

                        case 'datetime': ?>
                            <input type="datetime-local" name="<?php echo $name; ?>" <?php echo $req ? 'required' : ''; ?>>
                            <?php break;

                        case 'email': ?>
                            <input type="email" name="<?php echo $name; ?>" placeholder="<?php echo $ph; ?>" <?php echo $req ? 'required' : ''; ?>>
                            <?php break;

                        case 'radio': ?>
                            <?php foreach ($p['opcoes'] as $o): ?>
                                <label class="opc">
                                    <input type="radio" name="<?php echo $name; ?>" value="<?php echo html_escape($o['name']); ?>" <?php echo $req ? 'required' : ''; ?>>
                                    <?php echo html_escape($o['name']); ?>
                                </label>
                            <?php endforeach; ?>
                            <?php break;

                        case 'checkbox': ?>
                            <?php foreach ($p['opcoes'] as $o): ?>
                                <label class="opc">
                                    <input type="checkbox" name="r[<?php echo (int) $p['id']; ?>][]" value="<?php echo html_escape($o['name']); ?>">
                                    <?php echo html_escape($o['name']); ?>
                                </label>
                            <?php endforeach; ?>
                            <?php break;

                        case 'select': ?>
                            <select name="<?php echo $name; ?>" <?php echo $req ? 'required' : ''; ?>>
                                <option value="">— selecione —</option>
                                <?php foreach ($p['opcoes'] as $o): ?>
                                    <option value="<?php echo html_escape($o['name']); ?>"><?php echo html_escape($o['name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                            <?php break;

                        case 'scale': ?>
                            <div class="scale">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <label>
                                        <input type="radio" name="<?php echo $name; ?>" value="<?php echo $i; ?>" <?php echo $req && $i === 1 ? 'required' : ''; ?>>
                                        <span style="font-size:18px;font-weight:600;"><?php echo $i; ?></span>
                                    </label>
                                <?php endfor; ?>
                            </div>
                            <?php break;

                        default: ?>
                            <input type="text" name="<?php echo $name; ?>" placeholder="<?php echo $ph; ?>" <?php echo $req ? 'required' : ''; ?>>
                            <?php break;
                    endswitch; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

        <div class="footer">
            <button type="submit" class="btn-enviar"><i class="fa fa-paper-plane"></i> Enviar</button>
        </div>
    </form>

    <div class="footer-info">
        Nunca envie senhas pelo formulário.
    </div>
</div>

</body>
</html>
