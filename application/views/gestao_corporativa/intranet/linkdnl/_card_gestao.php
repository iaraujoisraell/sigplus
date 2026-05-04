<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php
$empresa_id = (int) $this->session->userdata('empresa_id');
$me = (int) get_staff_user_id();

$cnt = function ($table, $extra_where = '') use ($empresa_id) {
    $CI = &get_instance();
    $CI->db->where('empresa_id', $empresa_id)->where('deleted', 0);
    if ($extra_where) $CI->db->where($extra_where, null, false);
    return (int) $CI->db->count_all_results($table);
};

$contagens = [
    'view_projects'    => (int) $this->db->where('empresa_id', $empresa_id)->where('deleted', 0)->count_all_results('tblprojects'),
    'view_atas'        => $cnt('tbl_atas'),
    'view_grupos'      => $cnt('tbl_grupos'),
    'view_tasks'       => (int) $this->db->where('empresa_id', $empresa_id)->where('deleted', 0)->where('status !=', 5)->count_all_results('tbltasks'),
    'view_eventos'     => $cnt('tbl_intranet_eventos'),
];

$gestao_modules = [
    ['perm' => 'view_projects', 'href' => base_url('admin/projects'),                'icon' => 'far fa-folder-open',  'label' => 'Projetos', 'cor' => '#0a66c2'],
    ['perm' => 'view_atas',     'href' => base_url('gestao_corporativa/Ata'),        'icon' => 'far fa-file-alt',     'label' => 'Atas',     'cor' => '#7c3aed'],
    ['perm' => 'view_grupos',   'href' => base_url('gestao_corporativa/Workgroup'),  'icon' => 'fas fa-users-cog',    'label' => 'Grupos',   'cor' => '#ea580c'],
    ['perm' => 'view_tasks',    'href' => base_url('gestao_corporativa/Acao'),       'icon' => 'fa fa-tasks',         'label' => 'Ações',    'cor' => '#dc2626'],
    ['perm' => 'view_eventos',  'href' => base_url('gestao_corporativa/Eventoplus'), 'icon' => 'far fa-calendar-alt', 'label' => 'Eventos',  'cor' => '#0891b2'],
];
?>
<style>
    .gestao-card{margin-bottom:14px;}
    .gestao-card .header{
        padding:12px 16px;border-bottom:1px solid #eef1f4;
        font-size:12px;font-weight:700;color:#475569;text-transform:uppercase;letter-spacing:.06em;
        display:flex;align-items:center;gap:8px;
    }
    .gestao-card .header i{color:#0a66c2;}
    .gestao-grid{
        display:grid;grid-template-columns:1fr 1fr;gap:8px;padding:12px;
    }
    .gestao-item{
        position:relative;
        min-height:64px;
        border-radius:8px;
        text-decoration:none !important;
        color:#475569 !important;
        background:#fff;
        border:1px solid #e5e7eb;
        border-left:3px solid var(--g-color, #94a3b8);
        display:flex;flex-direction:column;
        align-items:center;justify-content:center;
        padding:10px 8px 8px;
        transition:.18s ease;
    }
    .gestao-item:hover{
        transform:translateY(-1px);
        box-shadow:0 4px 10px rgba(0,0,0,.06);
        color:#1f2937 !important;
        border-color:var(--g-color, #cbd5e1);
        border-left-color:var(--g-color, #94a3b8);
    }
    .gestao-item i{
        font-size:20px;line-height:1;margin-bottom:6px;
        color:var(--g-color, #475569);
    }
    .gestao-item .lbl{
        font-size:12px;font-weight:600;line-height:1.15;text-align:center;color:#475569;
    }
    .gestao-item .qtd{
        position:absolute;top:4px;right:6px;
        background:var(--g-color, #94a3b8);color:#fff;
        font-size:10px;font-weight:700;line-height:1;
        padding:3px 6px;border-radius:8px;
        min-width:18px;text-align:center;
    }
    @media (max-width:480px){
        .gestao-grid{grid-template-columns:1fr 1fr 1fr;}
    }
</style>

<div class="ui-card mini-card gestao-card">
    <div class="header">
        <i class="fa fa-th-large"></i> Gestão
    </div>
    <div class="gestao-grid">
        <?php foreach ($gestao_modules as $m): ?>
            <?php if (!has_permission_intranet('modules', '', $m['perm']) && !is_admin()) continue; ?>
            <a href="<?php echo $m['href']; ?>" class="gestao-item" title="<?php echo $m['label']; ?>" style="--g-color: <?php echo $m['cor']; ?>;">
                <?php $q = (int) ($contagens[$m['perm']] ?? 0); if ($q > 0): ?>
                    <span class="qtd"><?php echo $q > 999 ? '999+' : $q; ?></span>
                <?php endif; ?>
                <i class="<?php echo $m['icon']; ?>"></i>
                <span class="lbl"><?php echo $m['label']; ?></span>
            </a>
        <?php endforeach; ?>
    </div>
</div>
