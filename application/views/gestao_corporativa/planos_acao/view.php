<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head_intranet_novo(); ?>
<?php $this->load->view('gestao_corporativa/css_background'); ?>

<style>
    .pa-view{max-width:1100px;margin:24px auto;padding:0 18px;}
    .pa-card{background:#fff;border:1px solid #e5e7eb;border-radius:10px;margin-bottom:14px;}
    .pa-card-header{padding:14px 18px;border-bottom:1px solid #eef1f4;font-weight:600;color:#1f2937;font-size:14px;display:flex;justify-content:space-between;align-items:center;}
    .pa-card-body{padding:18px;}
    .pa-title-row{display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:14px;}
    .pa-title-row h1{margin:0;font-size:22px;font-weight:700;color:#1f2937;}
    .meta-row{display:flex;flex-wrap:wrap;gap:18px;color:#475569;font-size:13px;margin-top:10px;}
    .meta-row i{color:#94a3b8;margin-right:4px;}

    .status-pill{display:inline-block;font-size:11px;font-weight:600;padding:3px 10px;border-radius:999px;}
    .status-aberto{background:#dbeafe;color:#1e40af;}
    .status-em_execucao{background:#fef9c3;color:#854d0e;}
    .status-concluido{background:#dcfce7;color:#166534;}
    .status-cancelado{background:#fee2e2;color:#991b1b;}
    .status-atrasado{background:#fecaca;color:#7f1d1d;}

    .w-table{width:100%;border-collapse:collapse;font-size:13px;}
    .w-table th{background:#f8fafc;color:#475569;font-weight:600;padding:8px 10px;border-bottom:1px solid #e5e7eb;text-align:left;font-size:11px;text-transform:uppercase;}
    .w-table td{padding:10px;border-bottom:1px solid #eef1f4;vertical-align:top;}
    .w-table tr.is-concluido{background:#f0fdf4;}
    .w-table tr.is-cancelado{background:#fef2f2;color:#94a3b8;text-decoration:line-through;}
    .task-link{background:#dcfce7;color:#166534;padding:2px 8px;border-radius:6px;font-size:11px;font-weight:600;}
    .gerar-link{font-size:12px;color:#0a66c2;text-decoration:none;font-weight:600;}

    .ishikawa-grid{display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-top:14px;}
    .ishikawa-grid .m-card{background:#f8fafc;border:1px solid #e5e7eb;border-radius:8px;padding:12px;}
    .ishikawa-grid .m-card .label{font-size:11px;font-weight:700;color:#6366f1;text-transform:uppercase;letter-spacing:.04em;margin-bottom:6px;}
    .ishikawa-grid .m-card .content{font-size:13px;color:#1f2937;white-space:pre-line;}
    .ishikawa-grid .m-card .content:empty::before{content:'(não preenchido)';color:#94a3b8;font-style:italic;}
    @media (max-width:768px){.ishikawa-grid{grid-template-columns:1fr;}}

    .problema-card{background:#fef3c7;border:1px solid #fde68a;border-radius:8px;padding:14px;}
    .problema-card .label{font-size:11px;font-weight:700;color:#92400e;text-transform:uppercase;margin-bottom:6px;}
    .causa-raiz-card{background:#fee2e2;border:1px solid #fecaca;border-radius:8px;padding:14px;margin-top:14px;}
    .causa-raiz-card .label{font-size:11px;font-weight:700;color:#991b1b;text-transform:uppercase;margin-bottom:6px;}
    .acao-card{background:#dcfce7;border:1px solid #bbf7d0;border-radius:8px;padding:14px;margin-top:8px;}
    .acao-card .label{font-size:11px;font-weight:700;color:#166534;text-transform:uppercase;margin-bottom:6px;}
</style>

<div class="content">
    <div class="pa-view">
        <ol class="breadcrumb" style="background:#fff;">
            <li><a href="<?php echo base_url('gestao_corporativa/intranet'); ?>"><i class="fa fa-home"></i> Home</a></li>
            <li><a href="<?php echo base_url('gestao_corporativa/Plano_acao'); ?>">Planos de Ação</a></li>
            <li class="active"><?php echo html_escape($plano['titulo']); ?></li>
        </ol>

        <div class="pa-card">
            <div class="pa-card-body">
                <div class="pa-title-row">
                    <div>
                        <h1><?php echo html_escape($plano['titulo']); ?></h1>
                        <div class="meta-row">
                            <span class="status-pill status-<?php echo $plano['status']; ?>"><?php echo $this->Plano_acao_model->get_status_label($plano['status']); ?></span>
                            <span style="background:#eef2ff;color:#4338ca;font-size:11px;font-weight:600;padding:3px 8px;border-radius:6px;"><?php echo strtoupper($plano['metodologia']); ?></span>
                            <?php if (!empty($plano['responsavel_nome'])): ?><span><i class="fa fa-user"></i><?php echo html_escape($plano['responsavel_nome']); ?></span><?php endif; ?>
                            <?php if (!empty($plano['project_name'])): ?><span><i class="fa fa-folder-o"></i><?php echo html_escape($plano['project_name']); ?></span><?php endif; ?>
                            <?php if (!empty($plano['ata_titulo'])): ?><span><i class="fa fa-file-text-o"></i>Ata: <?php echo html_escape($plano['ata_titulo']); ?></span><?php endif; ?>
                            <?php if (!empty($plano['dt_inicio']) || !empty($plano['dt_fim'])): ?>
                                <span><i class="fa fa-calendar"></i>
                                    <?php echo !empty($plano['dt_inicio']) ? date('d/m/Y', strtotime($plano['dt_inicio'])) : '—'; ?>
                                    →
                                    <?php echo !empty($plano['dt_fim']) ? date('d/m/Y', strtotime($plano['dt_fim'])) : '—'; ?>
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div>
                        <?php if (has_permission_intranet('planos_acao', '', 'edit') || is_admin()): ?>
                            <a href="<?php echo base_url('gestao_corporativa/Plano_acao/edit/' . (int) $plano['id']); ?>" class="btn btn-default btn-sm"><i class="fa fa-pencil"></i> Editar</a>
                        <?php endif; ?>
                    </div>
                </div>

                <?php if (!empty($plano['descricao'])): ?>
                    <div style="margin-top:16px;font-size:14px;line-height:1.6;color:#1f2937;"><?php echo $plano['descricao']; ?></div>
                <?php endif; ?>
            </div>
        </div>

        <?php if (!empty($itens_5w2h)): ?>
            <div class="pa-card">
                <div class="pa-card-header">5W2H — <?php echo count($itens_5w2h); ?> itens</div>
                <div class="pa-card-body" style="overflow-x:auto;">
                    <table class="w-table">
                        <thead>
                            <tr>
                                <th>What</th>
                                <th>Why</th>
                                <th>Where</th>
                                <th>When</th>
                                <th>Who</th>
                                <th>How</th>
                                <th>$$</th>
                                <th>Status</th>
                                <th>Task</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($itens_5w2h as $w): ?>
                                <tr class="is-<?php echo $w['status']; ?>">
                                    <td><?php echo nl2br(html_escape($w['what'])); ?></td>
                                    <td><?php echo nl2br(html_escape($w['why'] ?? '')); ?></td>
                                    <td><?php echo html_escape($w['where'] ?? ''); ?></td>
                                    <td><?php echo !empty($w['when']) ? date('d/m/Y', strtotime($w['when'])) : ''; ?></td>
                                    <td><?php echo html_escape($w['who_nome'] ?? ''); ?></td>
                                    <td><?php echo nl2br(html_escape($w['how'] ?? '')); ?></td>
                                    <td><?php echo $w['how_much'] !== null ? 'R$ ' . number_format($w['how_much'], 2, ',', '.') : ''; ?></td>
                                    <td><span style="font-size:11px;font-weight:600;padding:2px 8px;border-radius:6px;background:#f3f4f6;color:#475569;"><?php echo ucfirst(str_replace('_', ' ', $w['status'])); ?></span></td>
                                    <td>
                                        <?php if (!empty($w['task_id'])): ?>
                                            <span class="task-link">#<?php echo (int) $w['task_id']; ?></span>
                                        <?php elseif (has_permission_intranet('planos_acao', '', 'edit') || is_admin()): ?>
                                            <a href="#" class="gerar-link js-gerar-task" data-id="<?php echo (int) $w['id']; ?>"><i class="fa fa-tasks"></i> Gerar</a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>

        <?php if (!empty($ishikawa)): ?>
            <div class="pa-card">
                <div class="pa-card-header">Análise Ishikawa (6M)</div>
                <div class="pa-card-body">
                    <?php if (!empty($ishikawa['problema'])): ?>
                        <div class="problema-card">
                            <div class="label">🎯 Problema</div>
                            <div><?php echo nl2br(html_escape($ishikawa['problema'])); ?></div>
                        </div>
                    <?php endif; ?>

                    <div class="ishikawa-grid">
                        <?php
                        $ms = [
                            'maquina' => ['🔧', 'Máquina'], 'metodo' => ['📋', 'Método'],
                            'mao_obra' => ['👥', 'Mão de obra'], 'material' => ['📦', 'Material'],
                            'meio_ambiente' => ['🌱', 'Meio ambiente'], 'medida' => ['📐', 'Medida'],
                        ];
                        foreach ($ms as $k => [$icon, $name]):
                        ?>
                            <div class="m-card">
                                <div class="label"><?php echo $icon . ' ' . $name; ?></div>
                                <div class="content"><?php echo html_escape($ishikawa[$k] ?? ''); ?></div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <?php if (!empty($ishikawa['causa_raiz'])): ?>
                        <div class="causa-raiz-card">
                            <div class="label">🔥 Causa raiz</div>
                            <div><?php echo nl2br(html_escape($ishikawa['causa_raiz'])); ?></div>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($ishikawa['acao_corretiva'])): ?>
                        <div class="acao-card">
                            <div class="label">✅ Ação corretiva</div>
                            <div><?php echo nl2br(html_escape($ishikawa['acao_corretiva'])); ?></div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>

        <?php $this->load->view('gestao_corporativa/formularios/_lista_vinculada', [
            'vinculo_tipo' => 'plano',
            'vinculo_id'   => (int) $plano['id'],
        ]); ?>

        <?php $this->load->view('gestao_corporativa/documentos/_lista_vinculada', [
            'vinculo_tipo' => 'plano',
            'vinculo_id'   => (int) $plano['id'],
        ]); ?>

        <?php $this->load->view('gestao_corporativa/treinamentos/_lista_vinculada', [
            'vinculo_tipo' => 'plano',
            'vinculo_id'   => (int) $plano['id'],
        ]); ?>

        <?php $this->load->view('gestao_corporativa/riscos/_lista_vinculada', [
            'vinculo_tipo' => 'plano',
            'vinculo_id'   => (int) $plano['id'],
        ]); ?>

        <?php $this->load->view('gestao_corporativa/auditorias/_lista_vinculada', [
            'vinculo_tipo' => 'plano',
            'vinculo_id'   => (int) $plano['id'],
        ]); ?>
    </div>
</div>

<?php init_tail(); ?>
<script>
$(document).on('click', '.js-gerar-task', function (e) {
    e.preventDefault();
    var id = $(this).data('id');
    if (!confirm('Gerar task pra este item?')) return;
    $.post('<?php echo base_url('gestao_corporativa/Plano_acao/gerar_task_5w2h'); ?>/' + id, {}, function (resp) {
        if (resp && resp.ok) location.reload();
        else alert('Falha ao gerar task.');
    }, 'json');
});
</script>
