<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head_intranet_novo(); ?>
<?php $this->load->view('gestao_corporativa/css_background'); ?>

<style>
    .gp-page{max-width:1180px;margin:24px auto;padding:0 18px;}
    .gp-header{display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;margin-bottom:18px;}
    .gp-header h1{margin:0;font-size:22px;font-weight:700;color:#1f2937;}
    .gp-filters{display:grid;grid-template-columns:repeat(4,1fr) auto;gap:10px;margin-bottom:14px;}
    .gp-filters input,.gp-filters select{border:1px solid #d0d5dd;border-radius:8px;padding:8px 12px;font-size:13px;outline:none;}
    @media (max-width:900px){.gp-filters{grid-template-columns:1fr 1fr;}}

    .gp-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(330px,1fr));gap:14px;}
    .gp-card{background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:18px;transition:.15s;text-decoration:none;color:inherit;display:block;}
    .gp-card:hover{border-color:#0a66c2;box-shadow:0 6px 16px rgba(10,102,194,.08);transform:translateY(-1px);color:inherit;text-decoration:none;}
    .gp-card .head{display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:8px;gap:10px;}
    .gp-card h3{margin:0;font-size:16px;font-weight:700;color:#1f2937;line-height:1.35;}
    .gp-card .obj{color:#64748b;font-size:13px;line-height:1.5;margin-top:6px;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;}
    .gp-card .meta{display:flex;flex-wrap:wrap;gap:10px;margin-top:12px;font-size:12px;color:#64748b;}
    .gp-card .meta i{color:#94a3b8;margin-right:4px;}
    .gp-card .stats{display:flex;gap:14px;margin-top:12px;padding-top:10px;border-top:1px solid #f3f4f6;font-size:12px;color:#475569;}
    .gp-card .stats .stat{display:flex;align-items:center;gap:4px;}
    .gp-card .stats .stat strong{color:#1f2937;font-size:14px;}

    .status-pill{display:inline-block;font-size:11px;font-weight:600;padding:2px 9px;border-radius:999px;}
    .status-ativo{background:#dbeafe;color:#1e40af;}
    .status-pausado{background:#fef3c7;color:#92400e;}
    .status-concluido{background:#dcfce7;color:#166534;}
    .status-cancelado{background:#fee2e2;color:#991b1b;}

    .gp-empty{text-align:center;padding:60px 20px;color:#64748b;background:#fff;border-radius:12px;border:1px dashed #e5e7eb;}
    .gp-empty i{font-size:42px;color:#cbd5e1;margin-bottom:12px;display:block;}
</style>

<div class="content">
    <div class="gp-page">
        <div class="gp-header">
            <h1>Grupos</h1>
            <a href="<?php echo base_url('gestao_corporativa/Workgroup/add'); ?>" class="btn btn-info"><i class="fa fa-plus"></i> Novo grupo</a>
        </div>

        <form method="get" class="gp-filters">
            <input type="text" name="busca" placeholder="Buscar por título ou objetivo" value="<?php echo html_escape($filtros['busca'] ?? ''); ?>">
            <select name="status">
                <option value="">Todos status</option>
                <?php foreach ($this->Workgroup_model->get_statuses() as $s): ?>
                    <option value="<?php echo $s; ?>" <?php echo ($filtros['status'] ?? '') === $s ? 'selected' : ''; ?>><?php echo $this->Workgroup_model->get_status_label($s); ?></option>
                <?php endforeach; ?>
            </select>
            <select name="project_id">
                <option value="">Todos projetos</option>
                <?php
                $empresa_id = (int) $this->session->userdata('empresa_id');
                $projects = $this->db->select('id, name')->where('empresa_id', $empresa_id)->order_by('name', 'asc')->get('tblprojects')->result_array();
                foreach ($projects as $p): ?>
                    <option value="<?php echo (int) $p['id']; ?>" <?php echo ((int) ($filtros['project_id'] ?? 0)) === (int) $p['id'] ? 'selected' : ''; ?>><?php echo html_escape($p['name']); ?></option>
                <?php endforeach; ?>
            </select>
            <select name="meu">
                <option value="">Todos</option>
                <option value="1" <?php echo !empty($filtros['meu']) ? 'selected' : ''; ?>>Apenas dos quais participo</option>
            </select>
            <button type="submit" class="btn btn-default">Filtrar</button>
        </form>

        <?php if (empty($grupos)): ?>
            <div class="gp-empty">
                <i class="fa fa-users"></i>
                <p>Nenhum grupo encontrado.</p>
            </div>
        <?php else: ?>
            <div class="gp-grid">
                <?php foreach ($grupos as $g): ?>
                    <a href="<?php echo base_url('gestao_corporativa/Workgroup/view/' . (int) $g['id']); ?>" class="gp-card">
                        <div class="head">
                            <h3><?php echo html_escape($g['titulo']); ?></h3>
                            <span class="status-pill status-<?php echo $g['status']; ?>"><?php echo $this->Workgroup_model->get_status_label($g['status']); ?></span>
                        </div>
                        <?php if (!empty($g['objetivo'])): ?>
                            <div class="obj"><?php echo html_escape($g['objetivo']); ?></div>
                        <?php endif; ?>
                        <div class="meta">
                            <?php if (!empty($g['lider_nome'])): ?><span><i class="fa fa-star"></i><?php echo html_escape($g['lider_nome']); ?></span><?php endif; ?>
                            <?php if (!empty($g['project_name'])): ?><span><i class="fa fa-folder-o"></i><?php echo html_escape($g['project_name']); ?></span><?php endif; ?>
                            <?php if (!empty($g['dt_fim_prevista'])): ?><span><i class="fa fa-clock-o"></i><?php echo date('d/m/Y', strtotime($g['dt_fim_prevista'])); ?></span><?php endif; ?>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php init_tail(); ?>
