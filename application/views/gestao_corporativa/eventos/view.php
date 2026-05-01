<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head_intranet_novo(); ?>
<?php $this->load->view('gestao_corporativa/css_background'); ?>

<style>
    .ev-view{max-width:980px;margin:24px auto;padding:0 18px;}
    .ev-card{background:#fff;border:1px solid #e5e7eb;border-radius:10px;margin-bottom:14px;}
    .ev-card-body{padding:18px;}
    .ev-card-header{padding:14px 18px;border-bottom:1px solid #eef1f4;font-weight:600;color:#1f2937;font-size:14px;}
    .ev-title-row{display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:14px;}
    .ev-title-row h1{margin:0;font-size:22px;font-weight:700;color:#1f2937;display:flex;align-items:center;gap:10px;}
    .color-dot{display:inline-block;width:14px;height:14px;border-radius:50%;}
    .meta-row{display:flex;flex-wrap:wrap;gap:18px;color:#475569;font-size:13px;margin-top:10px;}
    .meta-row i{color:#94a3b8;margin-right:4px;}
    .vinc-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:8px;}
    .vinc-card{background:#f8fafc;border:1px solid #e5e7eb;border-radius:8px;padding:10px 12px;font-size:13px;text-decoration:none;color:#1f2937;display:flex;align-items:center;gap:8px;}
    .vinc-card i{color:#0a66c2;}
    .vinc-card:hover{border-color:#0a66c2;background:#eff6ff;color:#1f2937;text-decoration:none;}
</style>

<div class="content">
    <div class="ev-view">
        <ol class="breadcrumb" style="background:#fff;">
            <li><a href="<?php echo base_url('gestao_corporativa/intranet'); ?>"><i class="fa fa-home"></i> Home</a></li>
            <li><a href="<?php echo base_url('gestao_corporativa/Eventoplus'); ?>">Eventos</a></li>
            <li class="active"><?php echo html_escape($evento['title']); ?></li>
        </ol>

        <div class="ev-card">
            <div class="ev-card-body">
                <div class="ev-title-row">
                    <div>
                        <h1>
                            <span class="color-dot" style="background:<?php echo html_escape($evento['color'] ?? '#0a66c2'); ?>;"></span>
                            <?php echo html_escape($evento['title']); ?>
                        </h1>
                        <div class="meta-row">
                            <span><i class="fa fa-clock-o"></i><?php echo date('d/m/Y H:i', strtotime($evento['start'])); ?><?php if (!empty($evento['end'])): ?> – <?php echo date('d/m/Y H:i', strtotime($evento['end'])); ?><?php endif; ?></span>
                            <?php if (!empty($evento['onde'])): ?><span><i class="fa fa-map-marker"></i><?php echo html_escape($evento['onde']); ?></span><?php endif; ?>
                            <?php if (!empty($evento['criador_nome'])): ?><span><i class="fa fa-user"></i><?php echo html_escape($evento['criador_nome']); ?></span><?php endif; ?>
                            <span><i class="fa fa-eye"></i><?php echo !empty($evento['public']) ? 'Público' : 'Privado'; ?></span>
                        </div>
                    </div>
                    <div>
                        <?php if (!empty($pode_editar)): ?>
                            <a href="<?php echo base_url('gestao_corporativa/Eventoplus/edit/' . (int) $evento['id']); ?>" class="btn btn-default btn-sm"><i class="fa fa-pencil"></i> Editar</a>
                            <a href="<?php echo base_url('gestao_corporativa/Eventoplus/delete/' . (int) $evento['id']); ?>" onclick="return confirm('Excluir este evento?')" class="btn btn-default btn-sm" style="color:#dc2626;"><i class="fa fa-trash"></i> Excluir</a>
                        <?php endif; ?>
                    </div>
                </div>

                <?php if (!empty($evento['description'])): ?>
                    <div style="margin-top:16px;font-size:14px;line-height:1.6;color:#1f2937;"><?php echo $evento['description']; ?></div>
                <?php endif; ?>
            </div>
        </div>

        <?php if (!empty($evento['project_name']) || !empty($evento['ata_titulo']) || !empty($evento['plano_titulo']) || !empty($evento['grupo_titulo'])): ?>
            <div class="ev-card">
                <div class="ev-card-header">Vínculos</div>
                <div class="ev-card-body">
                    <div class="vinc-grid">
                        <?php if (!empty($evento['project_id'])): ?>
                            <a href="<?php echo admin_url('projects/view/' . (int) $evento['project_id']); ?>" class="vinc-card">
                                <i class="fa fa-folder-o"></i> <?php echo html_escape($evento['project_name']); ?>
                            </a>
                        <?php endif; ?>
                        <?php if (!empty($evento['ata_id'])): ?>
                            <a href="<?php echo base_url('gestao_corporativa/Ata/view/' . (int) $evento['ata_id']); ?>" class="vinc-card">
                                <i class="far fa-file-alt"></i> <?php echo html_escape($evento['ata_titulo']); ?>
                            </a>
                        <?php endif; ?>
                        <?php if (!empty($evento['plano_id'])): ?>
                            <a href="<?php echo base_url('gestao_corporativa/Plano_acao/view/' . (int) $evento['plano_id']); ?>" class="vinc-card">
                                <i class="fas fa-clipboard-list"></i> <?php echo html_escape($evento['plano_titulo']); ?>
                            </a>
                        <?php endif; ?>
                        <?php if (!empty($evento['grupo_id'])): ?>
                            <a href="<?php echo base_url('gestao_corporativa/Workgroup/view/' . (int) $evento['grupo_id']); ?>" class="vinc-card">
                                <i class="fas fa-users-cog"></i> <?php echo html_escape($evento['grupo_titulo']); ?>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php init_tail(); ?>
