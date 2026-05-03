<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head_intranet_novo(false); ?>
<?php $this->load->view('gestao_corporativa/css_background'); ?>

<style>
    .fm-page{max-width:1400px;margin:24px auto;padding:0 18px;}
    .fm-toolbar{display:flex;justify-content:space-between;align-items:center;margin-bottom:14px;gap:12px;}
    .fm-toolbar h2{margin:0;font-size:20px;color:#1f2937;}
    .fm-counter{background:#fff;border:1px solid #e5e7eb;border-radius:10px;padding:18px;margin-bottom:14px;display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:14px;}
    .fm-counter .item .lbl{font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.04em;}
    .fm-counter .item .val{font-size:24px;font-weight:700;color:#0a66c2;margin-top:2px;}

    .fm-tabela{background:#fff;border:1px solid #e5e7eb;border-radius:10px;overflow:auto;}
    .fm-tabela table{width:100%;border-collapse:collapse;font-size:13px;}
    .fm-tabela th,.fm-tabela td{padding:10px 12px;text-align:left;border-bottom:1px solid #eef1f4;vertical-align:top;}
    .fm-tabela th{background:#f8fafc;font-weight:600;color:#475569;font-size:11px;text-transform:uppercase;letter-spacing:.04em;position:sticky;top:0;z-index:1;}
    .fm-tabela tr:hover{background:#f8fafc;}
    .fm-empty{text-align:center;padding:48px;color:#94a3b8;background:#fff;border:1px dashed #e5e7eb;border-radius:10px;}
</style>

<div class="content">
    <div class="fm-page">
        <ol class="breadcrumb" style="background:#fff;">
            <li><a href="<?php echo base_url('gestao_corporativa/intranet'); ?>"><i class="fa fa-home"></i> Home</a></li>
            <li><a href="<?php echo base_url('gestao_corporativa/Formulario'); ?>">Formulários</a></li>
            <li><a href="<?php echo base_url('gestao_corporativa/Formulario/edit/' . (int) $form['id']); ?>"><?php echo html_escape($form['titulo']); ?></a></li>
            <li class="active">Respostas</li>
        </ol>

        <div class="fm-toolbar">
            <h2><i class="fa fa-table"></i> Respostas — <?php echo html_escape($form['titulo']); ?></h2>
            <div>
                <a href="<?php echo base_url('gestao_corporativa/Formulario/exportar_csv/' . (int) $form['id']); ?>" class="btn btn-success btn-sm"><i class="fa fa-file-excel"></i> Exportar CSV</a>
                <a href="<?php echo base_url('gestao_corporativa/Formulario/edit/' . (int) $form['id']); ?>" class="btn btn-default btn-sm"><i class="fa fa-pencil"></i> Editar formulário</a>
            </div>
        </div>

        <div class="fm-counter">
            <div class="item"><div class="lbl">Total de respostas</div><div class="val"><?php echo count($respostas); ?></div></div>
            <div class="item"><div class="lbl">Perguntas</div><div class="val"><?php echo count($perguntas); ?></div></div>
            <div class="item"><div class="lbl">Status do formulário</div><div class="val" style="color:<?php echo $form['status'] === 'publicado' ? '#16a34a' : '#94a3b8'; ?>;text-transform:uppercase;font-size:14px;"><?php echo $this->Formulario_model->get_status_label($form['status']); ?></div></div>
            <div class="item"><div class="lbl">Última resposta</div><div class="val" style="font-size:14px;color:#475569;"><?php echo !empty($respostas) ? date('d/m/Y H:i', strtotime($respostas[0]['data_cadastro'])) : '—'; ?></div></div>
        </div>

        <?php if (empty($respostas)): ?>
            <div class="fm-empty">
                <i class="fa fa-inbox fa-3x" style="color:#cbd5e1;"></i>
                <h4>Nenhuma resposta ainda.</h4>
                <p>Compartilhe o link público do formulário pra começar a receber respostas.</p>
                <?php if ($form['status'] === 'publicado'): ?>
                    <code style="background:#fff;padding:6px 10px;border:1px solid #e5e7eb;border-radius:6px;"><?php echo base_url('formularios/web/' . $form['form_key']); ?></code>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div class="fm-tabela">
                <table>
                    <thead>
                        <tr>
                            <th style="width:140px;">Data</th>
                            <th style="width:160px;">Quem respondeu</th>
                            <?php foreach ($perguntas as $p): ?>
                                <th><?php echo html_escape($p['title']); ?></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($respostas as $r): ?>
                            <tr>
                                <td><?php echo date('d/m/Y H:i', strtotime($r['data_cadastro'])); ?></td>
                                <td><?php echo html_escape($r['staff_nome'] ?: 'Anônimo (' . $r['ip'] . ')'); ?></td>
                                <?php foreach ($perguntas as $p): ?>
                                    <td><?php echo nl2br(html_escape($r['respostas'][(int) $p['id']] ?? '—')); ?></td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php init_tail(); ?>
</body>
</html>
