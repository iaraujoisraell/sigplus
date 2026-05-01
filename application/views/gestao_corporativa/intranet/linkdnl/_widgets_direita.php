<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php
$me = (int) get_staff_user_id();
$empresa_id = (int) $this->session->userdata('empresa_id');

// Setores do usuário (pra pegar passos atribuídos ao setor)
$this->load->model('Departments_model');
$meus_setores_ids = $this->Departments_model->get_staff_departments($me, true);
$setores_in = !empty($meus_setores_ids) ? implode(',', array_map('intval', $meus_setores_ids)) : '0';

// Passos do workflow pendentes pra mim ou pros meus setores
$sql_wf = "SELECT a.id AS andamento_id, a.workflow_id, a.data_prazo, a.atribuido_a,
        w.id AS wf_id, w.date_created AS wf_data,
        c.titulo AS categoria_nome,
        d.name AS setor_nome,
        ra.protocolo
    FROM tbl_intranet_workflow_fluxo_andamento a
    INNER JOIN tbl_intranet_workflow w ON w.id = a.workflow_id
    LEFT JOIN tbl_intranet_categorias c ON c.id = a.categoria_id
    LEFT JOIN tbldepartments d ON d.departmentid = a.department_id
    LEFT JOIN tbl_intranet_registro_atendimento ra ON ra.id = w.registro_atendimento_id
    WHERE a.empresa_id = $empresa_id AND a.deleted = 0 AND a.concluido = 0
      AND a.estornado = 0 AND w.deleted = 0 AND w.cancel_id = 0
      AND (a.atribuido_a = $me
           OR (a.atribuido_a = 0 AND a.department_id IN ($setores_in)))
    ORDER BY (a.data_prazo IS NULL), a.data_prazo ASC, a.id ASC
    LIMIT 5";
$meus_workflows = $this->db->query($sql_wf)->result_array();

$total_workflows = (int) $this->db->query("SELECT COUNT(DISTINCT a.workflow_id) AS n
    FROM tbl_intranet_workflow_fluxo_andamento a
    INNER JOIN tbl_intranet_workflow w ON w.id = a.workflow_id
    WHERE a.empresa_id = $empresa_id AND a.deleted = 0 AND a.concluido = 0 AND a.estornado = 0
      AND w.deleted = 0 AND w.cancel_id = 0
      AND (a.atribuido_a = $me
           OR (a.atribuido_a = 0 AND a.department_id IN ($setores_in)))")->row()->n;

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
<style>
    .pill-prazo{font-size:11px;font-weight:600;padding:1px 8px;border-radius:999px;}
    .pill-ok{background:#dcfce7;color:#166534;}
    .pill-alert{background:#fef3c7;color:#92400e;}
    .pill-late{background:#fee2e2;color:#991b1b;}
    .pill-soft{background:#f3f4f6;color:#475569;}
</style>
<div class="ui-card mini-card home-mini-list-card" style="margin-bottom:14px;">
    <div class="home-mini-list-header">
        <h4><i class="fas fa-project-diagram"></i> Pendentes pra mim/setor</h4>
        <div class="right">
            <?php if ($total_workflows > 0): ?><span class="count"><?php echo $total_workflows; ?></span><?php endif; ?>
        </div>
    </div>
    <div class="home-mini-list-body">
        <?php if (empty($meus_workflows)): ?>
            <div class="home-mini-empty">Nada pendente pra você</div>
        <?php else: ?>
            <?php $hoje = new DateTime(date('Y-m-d')); foreach ($meus_workflows as $w):
                $prazo_label = '';
                $prazo_class = 'pill-soft';
                if (!empty($w['data_prazo'])) {
                    $prazo = new DateTime($w['data_prazo']);
                    $diff = (int) $hoje->diff($prazo)->format('%r%a'); // dias com sinal
                    if ($diff < 0) {
                        $prazo_label = abs($diff) . 'd atrasado';
                        $prazo_class = 'pill-late';
                    } elseif ($diff === 0) {
                        $prazo_label = 'hoje';
                        $prazo_class = 'pill-alert';
                    } elseif ($diff <= 2) {
                        $prazo_label = 'em ' . $diff . 'd';
                        $prazo_class = 'pill-alert';
                    } else {
                        $prazo_label = 'em ' . $diff . 'd';
                        $prazo_class = 'pill-ok';
                    }
                } else {
                    $prazo_label = 'sem prazo';
                }
                $atribuicao = (int) $w['atribuido_a'] === $me ? 'Pra mim' : 'Setor';
            ?>
                <a href="<?php echo base_url('gestao_corporativa/Workflow/workflow/' . (int) $w['workflow_id']); ?>" class="home-mini-item">
                    <div class="titulo">
                        <?php echo !empty($w['protocolo']) ? '#' . html_escape($w['protocolo']) : 'WF #' . (int) $w['workflow_id']; ?>
                        <?php if (!empty($w['categoria_nome'])): ?> · <?php echo html_escape(mb_strimwidth($w['categoria_nome'], 0, 28, '…')); ?><?php endif; ?>
                    </div>
                    <div class="meta">
                        <span class="pill-prazo <?php echo $prazo_class; ?>"><i class="fa fa-clock-o"></i> <?php echo $prazo_label; ?></span>
                        <?php if ($atribuicao === 'Pra mim'): ?>
                            <span class="pill-prazo pill-ok"><i class="fa fa-user"></i> pra mim</span>
                        <?php elseif (!empty($w['setor_nome'])): ?>
                            <span class="pill-prazo pill-soft"><i class="fa fa-building-o"></i> <?php echo html_escape(mb_strimwidth($w['setor_nome'], 0, 18, '…')); ?></span>
                        <?php endif; ?>
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
