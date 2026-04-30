<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head_intranet_novo(); ?>
<?php $this->load->view('gestao_corporativa/css_background'); ?>

<style>
    .ata-view{max-width:980px;margin:24px auto;padding:0 18px;}
    .ata-card{background:#fff;border:1px solid #e5e7eb;border-radius:10px;margin-bottom:14px;}
    .ata-card-header{padding:14px 18px;border-bottom:1px solid #eef1f4;font-weight:600;color:#1f2937;font-size:14px;display:flex;justify-content:space-between;align-items:center;}
    .ata-card-body{padding:18px;}

    .ata-title-row{display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:14px;}
    .ata-title-row h1{margin:0;font-size:22px;font-weight:700;color:#1f2937;}
    .ata-title-row .actions a{margin-left:6px;}

    .meta-row{display:flex;flex-wrap:wrap;gap:18px;color:#475569;font-size:13px;margin-top:10px;}
    .meta-row i{color:#94a3b8;margin-right:4px;}

    .status-pill{display:inline-block;font-size:11px;font-weight:600;padding:3px 10px;border-radius:999px;}
    .status-aberta{background:#dbeafe;color:#1e40af;}
    .status-em_revisao{background:#fef3c7;color:#92400e;}
    .status-finalizada{background:#dcfce7;color:#166534;}
    .status-cancelada{background:#fee2e2;color:#991b1b;}

    .secao-content{font-size:14px;color:#1f2937;line-height:1.55;}
    .secao-content:empty::before{content:'(vazio)';color:#94a3b8;font-style:italic;}

    .participantes-grid{display:grid;grid-template-columns:repeat(auto-fill, minmax(180px, 1fr));gap:8px;}
    .participantes-grid .pp{background:#f8fafc;border:1px solid #e5e7eb;border-radius:8px;padding:8px 12px;font-size:13px;display:flex;align-items:center;gap:8px;}
    .participantes-grid .pp .badge{background:#e5e7eb;color:#475569;font-size:10px;padding:1px 6px;border-radius:4px;}

    .decisao-row{padding:10px 12px;border-radius:8px;background:#fafbfc;border:1px solid #e5e7eb;margin-bottom:6px;display:grid;grid-template-columns:1fr auto;gap:12px;align-items:center;}
    .decisao-row .desc{font-size:14px;color:#1f2937;}
    .decisao-row .extra{display:flex;gap:10px;font-size:12px;color:#64748b;margin-top:4px;flex-wrap:wrap;}
    .decisao-row .actions a{font-size:12px;color:#0a66c2;text-decoration:none;font-weight:600;}
    .task-link{background:#dcfce7;color:#166534;padding:2px 8px;border-radius:6px;font-size:11px;font-weight:600;}
</style>

<div class="content">
    <div class="ata-view">
        <ol class="breadcrumb" style="background:#fff;">
            <li><a href="<?php echo base_url('gestao_corporativa/intranet'); ?>"><i class="fa fa-home"></i> Home</a></li>
            <li><a href="<?php echo base_url('gestao_corporativa/Ata'); ?>">Atas</a></li>
            <li class="active"><?php echo html_escape($ata['titulo']); ?></li>
        </ol>

        <div class="ata-card">
            <div class="ata-card-body">
                <div class="ata-title-row">
                    <div>
                        <h1><?php echo html_escape($ata['titulo']); ?></h1>
                        <div class="meta-row">
                            <span class="status-pill status-<?php echo $ata['status']; ?>"><?php echo $this->Ata_model->get_status_label($ata['status']); ?></span>
                            <?php if (!empty($ata['data'])): ?><span><i class="fa fa-calendar"></i><?php echo date('d/m/Y', strtotime($ata['data'])); ?><?php if (!empty($ata['hora_inicio'])): ?> · <?php echo substr($ata['hora_inicio'], 0, 5); if (!empty($ata['hora_fim'])) echo '–' . substr($ata['hora_fim'], 0, 5); ?><?php endif; ?></span><?php endif; ?>
                            <?php if (!empty($ata['local'])): ?><span><i class="fa fa-map-marker"></i><?php echo html_escape($ata['local']); ?></span><?php endif; ?>
                            <?php if (!empty($ata['responsavel_nome'])): ?><span><i class="fa fa-user"></i><?php echo html_escape($ata['responsavel_nome']); ?></span><?php endif; ?>
                            <?php if (!empty($ata['project_name'])): ?><span><i class="fa fa-folder-o"></i><?php echo html_escape($ata['project_name']); ?></span><?php endif; ?>
                        </div>
                    </div>
                    <div class="actions">
                        <?php if (has_permission_intranet('atas', '', 'edit') || is_admin()): ?>
                            <a href="<?php echo base_url('gestao_corporativa/Ata/edit/' . (int) $ata['id']); ?>" class="btn btn-default btn-sm"><i class="fa fa-pencil"></i> Editar</a>
                        <?php endif; ?>
                        <a href="<?php echo base_url('gestao_corporativa/Plano_acao/add?ata_id=' . (int) $ata['id'] . ($ata['project_id'] ? '&project_id=' . (int) $ata['project_id'] : '')); ?>" class="btn btn-info btn-sm"><i class="fa fa-plus"></i> Plano de ação a partir desta ata</a>
                    </div>
                </div>
            </div>
        </div>

        <?php if (!empty($participantes)): ?>
            <div class="ata-card">
                <div class="ata-card-header">Participantes (<?php echo count($participantes); ?>)</div>
                <div class="ata-card-body">
                    <div class="participantes-grid">
                        <?php foreach ($participantes as $p): ?>
                            <div class="pp">
                                <i class="fa fa-user-o"></i>
                                <span><?php echo html_escape($p['tipo'] === 'interno' ? ($p['staff_nome'] ?? '—') : ($p['nome'] ?? '—')); ?></span>
                                <span class="badge"><?php echo $p['tipo'] === 'externo' ? 'Externo' : 'Interno'; ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <?php if (!empty($ata['pauta'])): ?>
            <div class="ata-card">
                <div class="ata-card-header">Pauta</div>
                <div class="ata-card-body"><div class="secao-content"><?php echo $ata['pauta']; ?></div></div>
            </div>
        <?php endif; ?>

        <?php if (!empty($ata['discussoes'])): ?>
            <div class="ata-card">
                <div class="ata-card-header">Discussões</div>
                <div class="ata-card-body"><div class="secao-content"><?php echo $ata['discussoes']; ?></div></div>
            </div>
        <?php endif; ?>

        <?php if (!empty($decisoes)): ?>
            <div class="ata-card">
                <div class="ata-card-header">Decisões / Encaminhamentos (<?php echo count($decisoes); ?>)</div>
                <div class="ata-card-body">
                    <?php foreach ($decisoes as $d): ?>
                        <div class="decisao-row">
                            <div>
                                <div class="desc"><?php echo nl2br(html_escape($d['descricao'])); ?></div>
                                <div class="extra">
                                    <?php if (!empty($d['responsavel_nome'])): ?><span><i class="fa fa-user"></i> <?php echo html_escape($d['responsavel_nome']); ?></span><?php endif; ?>
                                    <?php if (!empty($d['prazo'])): ?><span><i class="fa fa-clock-o"></i> Prazo: <?php echo date('d/m/Y', strtotime($d['prazo'])); ?></span><?php endif; ?>
                                </div>
                            </div>
                            <div class="actions">
                                <?php if (!empty($d['task_id'])): ?>
                                    <span class="task-link"><i class="fa fa-check"></i> Task #<?php echo (int) $d['task_id']; ?></span>
                                <?php elseif (has_permission_intranet('atas', '', 'edit') || is_admin()): ?>
                                    <a href="#" data-decisao="<?php echo (int) $d['id']; ?>" class="js-gerar-task"><i class="fa fa-tasks"></i> Gerar task</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <?php if (!empty($ata['observacoes'])): ?>
            <div class="ata-card">
                <div class="ata-card-header">Observações</div>
                <div class="ata-card-body"><div class="secao-content"><?php echo nl2br(html_escape($ata['observacoes'])); ?></div></div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php init_tail(); ?>
<script>
$(document).on('click', '.js-gerar-task', function (e) {
    e.preventDefault();
    var id = $(this).data('decisao');
    if (!confirm('Gerar task pra esta decisão?')) return;
    $.post('<?php echo base_url('gestao_corporativa/Ata/gerar_task_decisao'); ?>/' + id, {}, function (resp) {
        if (resp && resp.ok) location.reload();
        else alert('Falha ao gerar task.');
    }, 'json').fail(function () { alert('Erro de rede.'); });
});
</script>
