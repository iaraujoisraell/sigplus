<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php
$pid = (int) $project->id;
$this->load->model('Workgroup_model');
$grupos = $this->Workgroup_model->listar(['project_id' => $pid]);
?>

<style>
    .ptab-row{background:#fff;border:1px solid #e5e7eb;border-radius:10px;padding:12px 16px;margin-bottom:8px;display:grid;grid-template-columns:1fr auto;gap:12px;align-items:center;}
    .ptab-row .titulo a{color:#1f2937;font-weight:600;font-size:14px;text-decoration:none;}
    .ptab-row .titulo a:hover{color:#0a66c2;}
    .ptab-row .meta{font-size:12px;color:#64748b;margin-top:4px;display:flex;gap:12px;flex-wrap:wrap;}
    .ptab-empty{text-align:center;padding:40px;color:#94a3b8;}
    .ptab-toolbar{display:flex;justify-content:space-between;align-items:center;margin:14px 0;}
    .pill{font-size:11px;font-weight:600;padding:2px 9px;border-radius:999px;}
    .pill-ativo{background:#dbeafe;color:#1e40af;}
    .pill-pausado{background:#fef3c7;color:#92400e;}
    .pill-concluido{background:#dcfce7;color:#166534;}
    .pill-cancelado{background:#fee2e2;color:#991b1b;}
</style>

<div class="row" style="padding-top:10px;">
    <div class="col-md-12">
        <div class="ptab-toolbar">
            <h4 style="margin:0;">Grupos vinculados (<?php echo count($grupos); ?>)</h4>
            <a href="<?php echo base_url('gestao_corporativa/Workgroup/add?project_id=' . $pid); ?>" class="btn btn-info btn-sm"><i class="fa fa-plus"></i> Novo grupo</a>
        </div>

        <?php if (empty($grupos)): ?>
            <div class="ptab-empty"><i class="fas fa-users-cog fa-2x" style="color:#cbd5e1;display:block;margin-bottom:6px;"></i> Nenhum grupo vinculado.</div>
        <?php else: ?>
            <?php foreach ($grupos as $g): ?>
                <div class="ptab-row">
                    <div>
                        <div class="titulo">
                            <a href="<?php echo base_url('gestao_corporativa/Workgroup/view/' . (int) $g['id']); ?>"><?php echo html_escape($g['titulo']); ?></a>
                            <span class="pill pill-<?php echo $g['status']; ?>"><?php echo $this->Workgroup_model->get_status_label($g['status']); ?></span>
                        </div>
                        <div class="meta">
                            <?php if (!empty($g['lider_nome'])): ?><span><i class="fa fa-star"></i>Líder: <?php echo html_escape($g['lider_nome']); ?></span><?php endif; ?>
                            <?php if (!empty($g['objetivo'])): ?><span><i class="fa fa-bullseye"></i><?php echo html_escape(mb_strimwidth($g['objetivo'], 0, 80, '…')); ?></span><?php endif; ?>
                        </div>
                    </div>
                    <a href="<?php echo base_url('gestao_corporativa/Workgroup/view/' . (int) $g['id']); ?>" class="btn btn-default btn-sm"><i class="fa fa-eye"></i> Ver</a>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
