<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php
$this->load->model('Ata_model');
$this->load->model('Plano_acao_model');
$this->load->model('Workgroup_model');

$minhas_atas   = $this->Ata_model->listar(['minha' => 1], 5);
$meus_planos   = $this->Plano_acao_model->listar(['minha' => 1], 5);
$meus_grupos   = $this->Workgroup_model->listar(['meu' => 1], 5);
$total_atas    = $this->Ata_model->count_minhas();
$total_planos  = $this->Plano_acao_model->count_meus();
$total_grupos  = $this->Workgroup_model->count_meus();
?>

<style>
    .home-mini-list-card{padding:0;overflow:hidden;}
    .home-mini-list-header{display:flex;align-items:center;justify-content:space-between;padding:14px 18px;border-bottom:1px solid #e9edf2;}
    .home-mini-list-header h4{margin:0;font-size:15px;font-weight:700;color:#1f2937;display:flex;align-items:center;gap:8px;}
    .home-mini-list-header h4 i{color:#0a66c2;}
    .home-mini-list-header .right{display:flex;align-items:center;gap:6px;}
    .home-mini-list-header .count{background:#0a66c2;color:#fff;font-size:11px;font-weight:600;padding:1px 8px;border-radius:999px;}
    .home-mini-list-header .add-btn{width:24px;height:24px;border-radius:50%;background:#eaf2fb;color:#0a66c2;display:inline-flex;align-items:center;justify-content:center;text-decoration:none;font-size:13px;transition:.15s;}
    .home-mini-list-header .add-btn:hover{background:#0a66c2;color:#fff;text-decoration:none;}
    .home-mini-list-body{padding:6px 0;}
    .home-mini-item{display:block;padding:8px 18px;text-decoration:none !important;color:#1f2937;border-bottom:1px solid #f3f4f6;transition:.15s;}
    .home-mini-item:last-child{border-bottom:0;}
    .home-mini-item:hover{background:#f8fafc;color:#0a66c2 !important;}
    .home-mini-item .titulo{font-size:13px;font-weight:600;line-height:1.35;}
    .home-mini-item .meta{font-size:11px;color:#94a3b8;margin-top:2px;display:flex;gap:8px;flex-wrap:wrap;}
    .home-mini-item .meta i{margin-right:2px;}
    .home-mini-item .pill{font-size:10px;padding:1px 6px;border-radius:999px;font-weight:600;}
    .pill-aberta,.pill-aberto{background:#dbeafe;color:#1e40af;}
    .pill-em_revisao{background:#fef3c7;color:#92400e;}
    .pill-finalizada,.pill-concluido{background:#dcfce7;color:#166534;}
    .pill-em_execucao{background:#fef9c3;color:#854d0e;}
    .pill-cancelada,.pill-cancelado{background:#fee2e2;color:#991b1b;}
    .pill-atrasado{background:#fecaca;color:#7f1d1d;}
    .pill-pausado{background:#fef3c7;color:#92400e;}
    .home-mini-empty{padding:18px;text-align:center;color:#94a3b8;font-size:12px;}
    .home-mini-footer{padding:8px 18px;text-align:center;border-top:1px solid #f3f4f6;}
    .home-mini-footer a{color:#0a66c2;font-weight:600;font-size:12px;text-decoration:none;}
</style>

<?php if (has_permission_intranet('modules', '', 'view_atas') || is_admin()): ?>
<div class="ui-card mini-card home-mini-list-card" style="margin-bottom:14px;">
    <div class="home-mini-list-header">
        <h4><i class="far fa-file-alt"></i> Minhas Atas</h4>
        <div class="right">
            <?php if ($total_atas > 0): ?><span class="count"><?php echo $total_atas; ?></span><?php endif; ?>
            <a href="<?php echo base_url('gestao_corporativa/Ata/add'); ?>" class="add-btn" title="Nova ata"><i class="fa fa-plus"></i></a>
        </div>
    </div>
    <div class="home-mini-list-body">
        <?php if (empty($minhas_atas)): ?>
            <div class="home-mini-empty">Nenhuma ata sua no momento</div>
        <?php else: ?>
            <?php foreach ($minhas_atas as $a): ?>
                <a href="<?php echo base_url('gestao_corporativa/Ata/view/' . (int) $a['id']); ?>" class="home-mini-item">
                    <div class="titulo"><?php echo html_escape(mb_strimwidth($a['titulo'], 0, 60, '…')); ?></div>
                    <div class="meta">
                        <span class="pill pill-<?php echo $a['status']; ?>"><?php echo $this->Ata_model->get_status_label($a['status']); ?></span>
                        <?php if (!empty($a['data'])): ?><span><i class="fa fa-calendar"></i><?php echo date('d/m', strtotime($a['data'])); ?></span><?php endif; ?>
                        <?php if (!empty($a['project_name'])): ?><span><i class="fa fa-folder-o"></i><?php echo html_escape(mb_strimwidth($a['project_name'], 0, 28, '…')); ?></span><?php endif; ?>
                    </div>
                </a>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <div class="home-mini-footer">
        <a href="<?php echo base_url('gestao_corporativa/Ata?minha=1'); ?>">Ver todas →</a>
    </div>
</div>
<?php endif; ?>

<?php if (has_permission_intranet('modules', '', 'view_grupos') || is_admin()): ?>
<div class="ui-card mini-card home-mini-list-card" style="margin-bottom:14px;">
    <div class="home-mini-list-header">
        <h4><i class="fas fa-users-cog"></i> Meus Grupos</h4>
        <div class="right">
            <?php if ($total_grupos > 0): ?><span class="count"><?php echo $total_grupos; ?></span><?php endif; ?>
            <a href="<?php echo base_url('gestao_corporativa/Workgroup/add'); ?>" class="add-btn" title="Novo grupo"><i class="fa fa-plus"></i></a>
        </div>
    </div>
    <div class="home-mini-list-body">
        <?php if (empty($meus_grupos)): ?>
            <div class="home-mini-empty">Você ainda não participa de grupos</div>
        <?php else: ?>
            <?php foreach ($meus_grupos as $g): ?>
                <a href="<?php echo base_url('gestao_corporativa/Workgroup/view/' . (int) $g['id']); ?>" class="home-mini-item">
                    <div class="titulo"><?php echo html_escape(mb_strimwidth($g['titulo'], 0, 60, '…')); ?></div>
                    <div class="meta">
                        <span class="pill pill-<?php echo $g['status']; ?>"><?php echo $this->Workgroup_model->get_status_label($g['status']); ?></span>
                        <?php if (!empty($g['lider_nome'])): ?><span><i class="fa fa-star"></i><?php echo html_escape(mb_strimwidth($g['lider_nome'], 0, 20, '…')); ?></span><?php endif; ?>
                    </div>
                </a>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <div class="home-mini-footer">
        <a href="<?php echo base_url('gestao_corporativa/Workgroup?meu=1'); ?>">Ver todos →</a>
    </div>
</div>
<?php endif; ?>

<?php if (has_permission_intranet('modules', '', 'view_planos_acao') || is_admin()): ?>
<div class="ui-card mini-card home-mini-list-card" style="margin-bottom:14px;">
    <div class="home-mini-list-header">
        <h4><i class="fas fa-clipboard-list"></i> Meus Planos de Ação</h4>
        <div class="right">
            <?php if ($total_planos > 0): ?><span class="count"><?php echo $total_planos; ?></span><?php endif; ?>
            <a href="<?php echo base_url('gestao_corporativa/Plano_acao/add'); ?>" class="add-btn" title="Novo plano"><i class="fa fa-plus"></i></a>
        </div>
    </div>
    <div class="home-mini-list-body">
        <?php if (empty($meus_planos)): ?>
            <div class="home-mini-empty">Nenhum plano seu no momento</div>
        <?php else: ?>
            <?php $hoje = date('Y-m-d'); foreach ($meus_planos as $p):
                $atrasado = !empty($p['dt_fim']) && $p['dt_fim'] < $hoje && in_array($p['status'], ['aberto', 'em_execucao'], true);
                $st = $atrasado ? 'atrasado' : $p['status'];
                $st_label = $atrasado ? 'Atrasado' : $this->Plano_acao_model->get_status_label($p['status']);
            ?>
                <a href="<?php echo base_url('gestao_corporativa/Plano_acao/view/' . (int) $p['id']); ?>" class="home-mini-item">
                    <div class="titulo"><?php echo html_escape(mb_strimwidth($p['titulo'], 0, 60, '…')); ?></div>
                    <div class="meta">
                        <span class="pill pill-<?php echo $st; ?>"><?php echo $st_label; ?></span>
                        <?php if (!empty($p['dt_fim'])): ?><span><i class="fa fa-clock-o"></i><?php echo date('d/m', strtotime($p['dt_fim'])); ?></span><?php endif; ?>
                        <?php if (!empty($p['project_name'])): ?><span><i class="fa fa-folder-o"></i><?php echo html_escape(mb_strimwidth($p['project_name'], 0, 28, '…')); ?></span><?php endif; ?>
                    </div>
                </a>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <div class="home-mini-footer">
        <a href="<?php echo base_url('gestao_corporativa/Plano_acao?minha=1'); ?>">Ver todos →</a>
    </div>
</div>
<?php endif; ?>
