<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php
$me = (int) get_staff_user_id();
$empresa_id = (int) $this->session->userdata('empresa_id');

$meus_workflows = $this->db->query("SELECT w.id, w.status, w.date_prazo,
        c.titulo AS categoria_nome,
        ra.protocolo AS protocolo
    FROM tbl_intranet_workflow w
    LEFT JOIN tbl_intranet_categorias c ON c.id = w.categoria_id
    LEFT JOIN tbl_intranet_registro_atendimento ra ON ra.id = w.registro_atendimento_id
    WHERE w.empresa_id = $empresa_id AND w.deleted = 0 AND w.cancel_id = 0
      AND (w.user_created = $me OR w.user_start = $me)
    ORDER BY w.id DESC LIMIT 5")->result_array();

$total_workflows = (int) $this->db->query("SELECT COUNT(*) AS n FROM tbl_intranet_workflow
    WHERE empresa_id = $empresa_id AND deleted = 0 AND cancel_id = 0
      AND (user_created = $me OR user_start = $me)")->row()->n;

$meus_atendimentos = $this->db->query("SELECT ra.id, ra.protocolo, ra.date_created,
        c.company AS cliente_nome, cat.titulo AS categoria_nome
    FROM tbl_intranet_registro_atendimento ra
    LEFT JOIN tblclients c ON c.userid = ra.client_id
    LEFT JOIN tbl_intranet_categorias cat ON cat.id = ra.categoria_id
    WHERE ra.empresa_id = $empresa_id AND ra.deleted = 0
      AND ra.user_created = $me
    ORDER BY ra.id DESC LIMIT 5")->result_array();

$total_atendimentos = (int) $this->db->query("SELECT COUNT(*) AS n FROM tbl_intranet_registro_atendimento
    WHERE empresa_id = $empresa_id AND deleted = 0 AND user_created = $me")->row()->n;

$hoje = date('Y-m-d');
$meus_eventos = $this->db->query("SELECT e.eventid AS id, e.title, e.start, e.color, e.onde
    FROM tblevents e
    WHERE e.userid = $me AND e.deleted = 0 AND DATE(e.start) >= '$hoje'
    ORDER BY e.start ASC LIMIT 5")->result_array();
$total_eventos = (int) $this->db->query("SELECT COUNT(*) AS n FROM tblevents
    WHERE userid = $me AND empresa_id = $empresa_id AND deleted = 0 AND DATE(start) >= '$hoje'")->row()->n;
?>

<style>
    .home-mini-list-card{padding:0;overflow:hidden;}
    .home-mini-list-header{display:flex;align-items:center;justify-content:space-between;padding:14px 18px;border-bottom:1px solid #e9edf2;}
    .home-mini-list-header h4{margin:0;font-size:15px;font-weight:700;color:#1f2937;display:flex;align-items:center;gap:8px;}
    .home-mini-list-header h4 i{color:#0a66c2;}
    .home-mini-list-header .right{display:flex;align-items:center;gap:6px;}
    .home-mini-list-header .count{background:#0a66c2;color:#fff;font-size:11px;font-weight:600;padding:1px 8px;border-radius:999px;}
    .home-mini-list-header .add-btn{width:24px;height:24px;border-radius:50%;background:#eaf2fb;color:#0a66c2;display:inline-flex;align-items:center;justify-content:center;text-decoration:none;font-size:13px;}
    .home-mini-list-header .add-btn:hover{background:#0a66c2;color:#fff;}
    .home-mini-list-body{padding:6px 0;}
    .home-mini-item{display:block;padding:8px 18px;text-decoration:none !important;color:#1f2937;border-bottom:1px solid #f3f4f6;}
    .home-mini-item:last-child{border-bottom:0;}
    .home-mini-item:hover{background:#f8fafc;color:#0a66c2 !important;}
    .home-mini-item .titulo{font-size:13px;font-weight:600;line-height:1.35;}
    .home-mini-item .meta{font-size:11px;color:#94a3b8;margin-top:2px;display:flex;gap:8px;flex-wrap:wrap;}
    .home-mini-item .meta i{margin-right:2px;}
    .home-mini-empty{padding:18px;text-align:center;color:#94a3b8;font-size:12px;}
    .home-mini-footer{padding:8px 18px;text-align:center;border-top:1px solid #f3f4f6;}
    .home-mini-footer a{color:#0a66c2;font-weight:600;font-size:12px;text-decoration:none;}
    .ev-dot{display:inline-block;width:8px;height:8px;border-radius:50%;margin-right:6px;vertical-align:middle;}
</style>

<?php if (has_permission_intranet('modules', '', 'view_workflows') || is_admin()): ?>
<div class="ui-card mini-card home-mini-list-card" style="margin-bottom:14px;">
    <div class="home-mini-list-header">
        <h4><i class="fas fa-project-diagram"></i> Meus Workflows</h4>
        <div class="right">
            <?php if ($total_workflows > 0): ?><span class="count"><?php echo $total_workflows; ?></span><?php endif; ?>
        </div>
    </div>
    <div class="home-mini-list-body">
        <?php if (empty($meus_workflows)): ?>
            <div class="home-mini-empty">Nenhum workflow seu</div>
        <?php else: ?>
            <?php foreach ($meus_workflows as $w): ?>
                <a href="<?php echo base_url('gestao_corporativa/Workflow/single?workflow_id=' . (int) $w['id']); ?>" class="home-mini-item">
                    <div class="titulo">
                        <?php echo !empty($w['protocolo']) ? '#' . html_escape($w['protocolo']) : '#' . (int) $w['id']; ?>
                        <?php if (!empty($w['categoria_nome'])): ?>· <?php echo html_escape(mb_strimwidth($w['categoria_nome'], 0, 32, '…')); ?><?php endif; ?>
                    </div>
                    <div class="meta">
                        <?php if (!empty($w['date_prazo'])): ?><span><i class="fa fa-clock-o"></i><?php echo date('d/m', strtotime($w['date_prazo'])); ?></span><?php endif; ?>
                    </div>
                </a>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <div class="home-mini-footer">
        <a href="<?php echo base_url('gestao_corporativa/Workflow/index'); ?>">Ver todos →</a>
    </div>
</div>
<?php endif; ?>

<?php if (has_permission_intranet('modules', '', 'view_ras') || is_admin()): ?>
<div class="ui-card mini-card home-mini-list-card" style="margin-bottom:14px;">
    <div class="home-mini-list-header">
        <h4><i class="fas fa-headset"></i> Meus Atendimentos</h4>
        <div class="right">
            <?php if ($total_atendimentos > 0): ?><span class="count"><?php echo $total_atendimentos; ?></span><?php endif; ?>
            <a href="<?php echo base_url('gestao_corporativa/Atendimento/add'); ?>" class="add-btn" title="Novo atendimento"><i class="fa fa-plus"></i></a>
        </div>
    </div>
    <div class="home-mini-list-body">
        <?php if (empty($meus_atendimentos)): ?>
            <div class="home-mini-empty">Nenhum atendimento seu</div>
        <?php else: ?>
            <?php foreach ($meus_atendimentos as $a): ?>
                <a href="<?php echo base_url('gestao_corporativa/Atendimento/view/' . (int) $a['id']); ?>" class="home-mini-item">
                    <div class="titulo">
                        <?php echo !empty($a['protocolo']) ? '#' . html_escape($a['protocolo']) : '#' . (int) $a['id']; ?>
                        <?php if (!empty($a['cliente_nome'])): ?> · <?php echo html_escape(mb_strimwidth($a['cliente_nome'], 0, 28, '…')); ?><?php endif; ?>
                    </div>
                    <div class="meta">
                        <?php if (!empty($a['categoria_nome'])): ?><span><i class="fa fa-tag"></i><?php echo html_escape(mb_strimwidth($a['categoria_nome'], 0, 24, '…')); ?></span><?php endif; ?>
                        <?php if (!empty($a['date_created'])): ?><span><i class="fa fa-clock-o"></i><?php echo date('d/m', strtotime($a['date_created'])); ?></span><?php endif; ?>
                    </div>
                </a>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <div class="home-mini-footer">
        <a href="<?php echo base_url('gestao_corporativa/Atendimento/index'); ?>">Ver todos →</a>
    </div>
</div>
<?php endif; ?>

<div class="ui-card mini-card home-mini-list-card" style="margin-bottom:14px;">
    <div class="home-mini-list-header">
        <h4><i class="far fa-calendar-alt"></i> Meus Eventos</h4>
        <div class="right">
            <?php if ($total_eventos > 0): ?><span class="count"><?php echo $total_eventos; ?></span><?php endif; ?>
            <a href="<?php echo base_url('gestao_corporativa/Eventoplus/add'); ?>" class="add-btn" title="Novo evento"><i class="fa fa-plus"></i></a>
        </div>
    </div>
    <div class="home-mini-list-body">
        <?php if (empty($meus_eventos)): ?>
            <div class="home-mini-empty">Nenhum evento próximo</div>
        <?php else: ?>
            <?php foreach ($meus_eventos as $e): ?>
                <a href="<?php echo base_url('gestao_corporativa/Eventoplus/view/' . (int) $e['id']); ?>" class="home-mini-item">
                    <div class="titulo">
                        <span class="ev-dot" style="background:<?php echo html_escape($e['color'] ?? '#0a66c2'); ?>;"></span>
                        <?php echo html_escape(mb_strimwidth($e['title'], 0, 60, '…')); ?>
                    </div>
                    <div class="meta">
                        <?php if (!empty($e['start'])): ?><span><i class="fa fa-clock-o"></i><?php echo date('d/m H:i', strtotime($e['start'])); ?></span><?php endif; ?>
                        <?php if (!empty($e['onde'])): ?><span><i class="fa fa-map-marker"></i><?php echo html_escape(mb_strimwidth($e['onde'], 0, 22, '…')); ?></span><?php endif; ?>
                    </div>
                </a>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <div class="home-mini-footer">
        <a href="<?php echo base_url('gestao_corporativa/Eventoplus?meu=1'); ?>">Ver todos →</a>
    </div>
</div>
