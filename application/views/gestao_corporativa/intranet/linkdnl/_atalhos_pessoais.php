<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php
$me = (int) get_staff_user_id();
$empresa_id = (int) $this->session->userdata('empresa_id');
$total_tasks_my = (int) $this->db->query("SELECT COUNT(DISTINCT t.id) AS n FROM tbltasks t
    INNER JOIN tbltask_assigned ta ON ta.taskid = t.id
    WHERE ta.staffid = $me AND t.empresa_id = $empresa_id AND t.deleted = 0 AND t.status != 5")->row()->n;
$total_todo = (int) $this->db->query("SELECT COUNT(*) AS n FROM tbltodos
    WHERE staffid = $me AND finished = 0")->row()->n;
$total_ci_pend = (int) $this->db->query("SELECT COUNT(*) AS n FROM tbl_intranet_ci_send
    WHERE staff_id = $me AND deleted = 0 AND status = 0 AND empresa_id = $empresa_id")->row()->n;
?>

<style>
    .atalhos-card{padding:0;overflow:hidden;}
    .atalhos-card .header{padding:14px 18px;border-bottom:1px solid #e9edf2;font-size:14px;font-weight:700;color:#1f2937;}
    .atalhos-card .header i{color:#0a66c2;margin-right:6px;}
    .atalhos-list a{display:flex;align-items:center;gap:12px;padding:12px 18px;border-bottom:1px solid #f3f4f6;color:#1d2226;text-decoration:none;font-size:14px;font-weight:500;transition:.15s;}
    .atalhos-list a:last-child{border-bottom:0;}
    .atalhos-list a:hover{background:#f8fafc;color:#0a66c2;text-decoration:none;}
    .atalhos-list a > i{width:18px;color:#475569;}
    .atalhos-list a:hover > i{color:#0a66c2;}
    .atalhos-list .badge-mini{margin-left:auto;background:#ef4444;color:#fff;font-size:10px;font-weight:700;min-width:18px;text-align:center;padding:1px 6px;border-radius:999px;}
</style>

<div class="ui-card mini-card atalhos-card" style="margin-bottom:14px;">
    <div class="header"><i class="fa fa-user-circle"></i> Atalhos pessoais</div>
    <div class="atalhos-list">
        <a href="<?php echo base_url('admin/tasks'); ?>">
            <i class="fa fa-bolt"></i> Minhas tarefas
            <?php if ($total_tasks_my > 0): ?><span class="badge-mini"><?php echo $total_tasks_my; ?></span><?php endif; ?>
        </a>
        <?php if (has_permission_intranet('menu_top_view', '', 'view_itens_task') || is_admin()): ?>
            <a href="<?php echo base_url('admin/todo'); ?>">
                <i class="fa fa-check-square"></i> Meus to-dos
                <?php if ($total_todo > 0): ?><span class="badge-mini"><?php echo $total_todo; ?></span><?php endif; ?>
            </a>
        <?php endif; ?>
        <a href="<?php echo base_url('gestao_corporativa/intranet/comunicados_pendentes'); ?>">
            <i class="fa fa-bullhorn"></i> Comunicados pendentes
            <?php if ($total_ci_pend > 0): ?><span class="badge-mini"><?php echo $total_ci_pend; ?></span><?php endif; ?>
        </a>
        <a href="<?php echo base_url('gestao_corporativa/intranet/meu_perfil'); ?>">
            <i class="fa fa-id-card-o"></i> Meu perfil
        </a>
        <?php if (has_permission_intranet('menu_top_view', '', 'view_contacts') || is_admin()): ?>
            <a href="<?php echo base_url('gestao_corporativa/intranet/contatos'); ?>">
                <i class="fa fa-address-book-o"></i> Contatos
            </a>
        <?php endif; ?>
    </div>
</div>
