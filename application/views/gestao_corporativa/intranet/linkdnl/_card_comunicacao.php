<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php
$empresa_id = (int) $this->session->userdata('empresa_id');

$cnt = function ($table, $extra_where = '') use ($empresa_id) {
    $CI = &get_instance();
    $CI->db->where('empresa_id', $empresa_id)->where('deleted', 0);
    if ($extra_where) $CI->db->where($extra_where, null, false);
    return (int) $CI->db->count_all_results($table);
};

$contagens = [
    'view_cis'       => $cnt('tbl_intranet_ci'),
    'view_ras'       => (int) $this->db->where('empresa_id', $empresa_id)->where('deleted', 0)
                            ->where('data_encerramento IS NULL', null, false)
                            ->count_all_results('tbl_intranet_registro_atendimento'),
    'view_ros'       => $cnt('tbl_intranet_registro_ocorrencia', 'status IN (1,2,4)'),
    'view_workflows' => 0,
];

$comunicacao_modules = [
    ['perm' => 'view_cis',       'href' => base_url('gestao_corporativa/intra/comunicado'),     'icon' => 'fas fa-bullhorn',        'label' => 'CI',          'cor' => '#f59e0b'],
    ['perm' => 'view_ras',       'href' => base_url('gestao_corporativa/Atendimento/index'),    'icon' => 'fas fa-headset',         'label' => 'Atendimentos','cor' => '#7c3aed'],
    ['perm' => 'view_ros',       'href' => base_url('gestao_corporativa/Registro_ocorrencia'), 'icon' => 'far fa-flag',            'label' => 'Ocorrências', 'cor' => '#dc2626'],
    ['perm' => 'view_workflows', 'href' => base_url('gestao_corporativa/Workflow/index'),       'icon' => 'fas fa-project-diagram', 'label' => 'Workflow',    'cor' => '#0891b2'],
];
?>
<style>
    .com-card{margin-bottom:14px;}
    .com-card .header{
        padding:12px 16px;border-bottom:1px solid #eef1f4;
        font-size:12px;font-weight:700;color:#475569;text-transform:uppercase;letter-spacing:.06em;
        display:flex;align-items:center;gap:8px;
    }
    .com-card .header i{color:#f59e0b;}
    .com-grid{
        display:grid;grid-template-columns:1fr 1fr;gap:8px;padding:12px;
    }
    .com-item{
        position:relative;
        min-height:64px;
        border-radius:8px;
        text-decoration:none !important;
        color:#475569 !important;
        background:#fff;
        border:1px solid #e5e7eb;
        border-left:3px solid var(--c-color, #94a3b8);
        display:flex;flex-direction:column;
        align-items:center;justify-content:center;
        padding:10px 8px 8px;
        transition:.18s ease;
    }
    .com-item:hover{
        transform:translateY(-1px);
        box-shadow:0 4px 10px rgba(0,0,0,.06);
        color:#1f2937 !important;
        border-color:var(--c-color, #cbd5e1);
        border-left-color:var(--c-color, #94a3b8);
    }
    .com-item i{
        font-size:20px;line-height:1;margin-bottom:6px;
        color:var(--c-color, #475569);
    }
    .com-item .lbl{font-size:12px;font-weight:600;line-height:1.15;text-align:center;color:#475569;}
    .com-item .qtd{
        position:absolute;top:4px;right:6px;
        background:var(--c-color, #94a3b8);color:#fff;
        font-size:10px;font-weight:700;line-height:1;
        padding:3px 6px;border-radius:8px;
        min-width:18px;text-align:center;
    }
    @media (max-width:480px){.com-grid{grid-template-columns:1fr 1fr 1fr;}}
</style>

<div class="ui-card mini-card com-card">
    <div class="header">
        <i class="fa fa-comments"></i> Comunicação &amp; Atendimento
    </div>
    <div class="com-grid">
        <?php foreach ($comunicacao_modules as $m): ?>
            <?php if (!has_permission_intranet('modules', '', $m['perm']) && !is_admin()) continue; ?>
            <a href="<?php echo $m['href']; ?>" class="com-item" title="<?php echo $m['label']; ?>" style="--c-color: <?php echo $m['cor']; ?>;">
                <?php $q = (int) ($contagens[$m['perm']] ?? 0); if ($q > 0): ?>
                    <span class="qtd"><?php echo $q > 999 ? '999+' : $q; ?></span>
                <?php endif; ?>
                <i class="<?php echo $m['icon']; ?>"></i>
                <span class="lbl"><?php echo $m['label']; ?></span>
            </a>
        <?php endforeach; ?>
    </div>
</div>
