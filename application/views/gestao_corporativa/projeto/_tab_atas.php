<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php
$pid = (int) $project->id;
$this->load->model('Ata_model');
$atas = $this->Ata_model->listar(['project_id' => $pid]);
?>

<style>
    .ptab-row{background:#fff;border:1px solid #e5e7eb;border-radius:10px;padding:12px 16px;margin-bottom:8px;display:grid;grid-template-columns:1fr auto;gap:12px;align-items:center;}
    .ptab-row:hover{border-color:#0a66c2;}
    .ptab-row .titulo a{color:#1f2937;font-weight:600;font-size:14px;text-decoration:none;}
    .ptab-row .titulo a:hover{color:#0a66c2;}
    .ptab-row .meta{font-size:12px;color:#64748b;margin-top:4px;display:flex;gap:12px;flex-wrap:wrap;}
    .ptab-row .meta i{color:#94a3b8;margin-right:3px;}
    .ptab-empty{text-align:center;padding:40px;color:#94a3b8;}
    .ptab-toolbar{display:flex;justify-content:space-between;align-items:center;margin:14px 0;}
    .status-pill{font-size:11px;font-weight:600;padding:2px 9px;border-radius:999px;}
    .status-aberta{background:#dbeafe;color:#1e40af;}
    .status-em_revisao{background:#fef3c7;color:#92400e;}
    .status-finalizada{background:#dcfce7;color:#166534;}
    .status-cancelada{background:#fee2e2;color:#991b1b;}
</style>

<div class="row" style="padding-top:10px;">
    <div class="col-md-12">
        <div class="ptab-toolbar">
            <h4 style="margin:0;">Atas vinculadas a este projeto (<?php echo count($atas); ?>)</h4>
            <a href="<?php echo base_url('gestao_corporativa/Ata/add?project_id=' . $pid); ?>" class="btn btn-info btn-sm"><i class="fa fa-plus"></i> Nova ata</a>
        </div>

        <?php if (empty($atas)): ?>
            <div class="ptab-empty"><i class="far fa-file-alt fa-2x" style="color:#cbd5e1;display:block;margin-bottom:6px;"></i> Nenhuma ata vinculada ainda.</div>
        <?php else: ?>
            <?php foreach ($atas as $a): ?>
                <div class="ptab-row">
                    <div>
                        <div class="titulo">
                            <a href="<?php echo base_url('gestao_corporativa/Ata/view/' . (int) $a['id']); ?>"><?php echo html_escape($a['titulo']); ?></a>
                            <span class="status-pill status-<?php echo $a['status']; ?>"><?php echo $this->Ata_model->get_status_label($a['status']); ?></span>
                        </div>
                        <div class="meta">
                            <?php if (!empty($a['data'])): ?><span><i class="fa fa-calendar"></i><?php echo date('d/m/Y', strtotime($a['data'])); ?></span><?php endif; ?>
                            <?php if (!empty($a['responsavel_nome'])): ?><span><i class="fa fa-user"></i><?php echo html_escape($a['responsavel_nome']); ?></span><?php endif; ?>
                            <?php if (!empty($a['local'])): ?><span><i class="fa fa-map-marker"></i><?php echo html_escape($a['local']); ?></span><?php endif; ?>
                        </div>
                    </div>
                    <a href="<?php echo base_url('gestao_corporativa/Ata/view/' . (int) $a['id']); ?>" class="btn btn-default btn-sm"><i class="fa fa-eye"></i> Ver</a>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
