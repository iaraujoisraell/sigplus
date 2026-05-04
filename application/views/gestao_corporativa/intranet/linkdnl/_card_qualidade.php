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
    'view_docs'         => $cnt('tbl_intranet_documento', 'publicado = 1'),
    'view_planos_acao'  => $cnt('tbl_planos_acao', "status IN ('aberto','em_execucao')"),
    'view_forms'        => $cnt('tbl_intranet_formularios', 'form_pai = 0'),
    'view_indicadores'  => $cnt('tbl_indicadores', "status = 'ativo'"),
    'view_treinamentos' => $cnt('tbl_treinamentos', "status IN ('planejado','inscricoes_abertas','em_andamento')"),
    'view_riscos'       => $cnt('tbl_riscos', "status NOT IN ('fechado','mitigado')"),
    'view_auditorias'   => $cnt('tbl_auditorias', "status IN ('planejada','em_execucao')"),
    'view_rac'          => $cnt('tbl_rac', "status IN ('planejada','em_execucao')"),
];

$qualidade_modules = [
    ['perm' => 'view_docs',        'href' => base_url('gestao_corporativa/Documento'),   'icon' => 'far fa-folder-open',     'label' => 'Documentos',  'cor' => '#0d9488'],
    ['perm' => 'view_planos_acao', 'href' => base_url('gestao_corporativa/Plano_acao'),  'icon' => 'fas fa-clipboard-list',  'label' => 'Plano de Ação','cor' => '#16a34a'],
    ['perm' => 'view_forms',       'href' => base_url('gestao_corporativa/Formulario'),  'icon' => 'fa fa-list-alt',         'label' => 'Formulários', 'cor' => '#0a66c2'],
    ['perm' => 'view_indicadores', 'href' => base_url('gestao_corporativa/Indicador'),   'icon' => 'fa fa-chart-line',       'label' => 'Indicadores', 'cor' => '#0891b2'],
    ['perm' => 'view_treinamentos','href' => base_url('gestao_corporativa/Treinamento'), 'icon' => 'fa fa-graduation-cap',   'label' => 'Treinamentos','cor' => '#ea580c'],
    ['perm' => 'view_riscos',      'href' => base_url('gestao_corporativa/Risco'),       'icon' => 'fa fa-shield-alt',       'label' => 'Riscos',      'cor' => '#dc2626'],
    ['perm' => 'view_auditorias',  'href' => base_url('gestao_corporativa/Auditoria'),   'icon' => 'fa fa-clipboard-check',  'label' => 'Auditorias',  'cor' => '#7c3aed'],
    ['perm' => 'view_rac',         'href' => base_url('gestao_corporativa/Rac'),         'icon' => 'fa fa-balance-scale',    'label' => 'RAC',         'cor' => '#312e81'],
];
?>
<style>
    .qualidade-card{margin-bottom:14px;}
    .qualidade-card .header{
        padding:12px 16px;border-bottom:1px solid #eef1f4;
        font-size:12px;font-weight:700;color:#475569;text-transform:uppercase;letter-spacing:.06em;
        display:flex;align-items:center;gap:8px;
    }
    .qualidade-card .header i{color:#0d9488;}
    .qualidade-grid{
        display:grid;grid-template-columns:1fr 1fr;gap:8px;padding:12px;
    }
    .qualidade-item{
        position:relative;
        min-height:64px;
        border-radius:8px;
        text-decoration:none !important;
        color:#475569 !important;
        background:#fff;
        border:1px solid #e5e7eb;
        border-left:3px solid var(--q-color, #94a3b8);
        display:flex;flex-direction:column;
        align-items:center;justify-content:center;
        padding:10px 8px 8px;
        transition:.18s ease;
    }
    .qualidade-item:hover{
        transform:translateY(-1px);
        box-shadow:0 4px 10px rgba(0,0,0,.06);
        color:#1f2937 !important;
        border-color:var(--q-color, #cbd5e1);
        border-left-color:var(--q-color, #94a3b8);
    }
    .qualidade-item i{
        font-size:20px;line-height:1;margin-bottom:6px;
        color:var(--q-color, #475569);
    }
    .qualidade-item .lbl{
        font-size:12px;font-weight:600;line-height:1.15;text-align:center;color:#475569;
    }
    .qualidade-item .qtd{
        position:absolute;top:4px;right:6px;
        background:var(--q-color, #94a3b8);color:#fff;
        font-size:10px;font-weight:700;line-height:1;
        padding:3px 6px;border-radius:8px;
        min-width:18px;text-align:center;
    }
    @media (max-width:480px){
        .qualidade-grid{grid-template-columns:1fr 1fr 1fr;}
    }
</style>

<div class="ui-card mini-card qualidade-card">
    <div class="header" style="display:flex;justify-content:space-between;align-items:center;">
        <span><i class="fa fa-shield-alt"></i> Qualidade</span>
        <a href="<?php echo base_url('gestao_corporativa/Qualidade'); ?>" style="font-size:10px;color:#0d9488;text-decoration:none;font-weight:700;text-transform:uppercase;letter-spacing:.04em;">
            <i class="fa fa-chart-bar"></i> Dashboard
        </a>
    </div>
    <div class="qualidade-grid">
        <?php foreach ($qualidade_modules as $m): ?>
            <?php if (!has_permission_intranet('modules', '', $m['perm']) && !is_admin()) continue; ?>
            <a href="<?php echo $m['href']; ?>" class="qualidade-item" title="<?php echo $m['label']; ?>" style="--q-color: <?php echo $m['cor']; ?>;">
                <?php $q = (int) ($contagens[$m['perm']] ?? 0); if ($q > 0): ?>
                    <span class="qtd"><?php echo $q > 999 ? '999+' : $q; ?></span>
                <?php endif; ?>
                <i class="<?php echo $m['icon']; ?>"></i>
                <span class="lbl"><?php echo $m['label']; ?></span>
            </a>
        <?php endforeach; ?>
    </div>
</div>
