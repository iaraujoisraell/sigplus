<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head_intranet_novo(); ?>
<?php $this->load->view('gestao_corporativa/css_background'); ?>

<style>
    .ata-page{max-width:1180px;margin:24px auto;padding:0 18px;}
    .ata-header{display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;margin-bottom:18px;}
    .ata-header h1{margin:0;font-size:22px;font-weight:700;color:#1f2937;}
    .ata-filters{display:grid;grid-template-columns:repeat(4,1fr) auto;gap:10px;margin-bottom:14px;}
    .ata-filters input,.ata-filters select{border:1px solid #d0d5dd;border-radius:8px;padding:8px 12px;font-size:13px;outline:none;}
    .ata-filters input:focus,.ata-filters select:focus{border-color:#0a66c2;}
    @media (max-width:900px){.ata-filters{grid-template-columns:1fr 1fr;}}

    .ata-row{background:#fff;border:1px solid #e5e7eb;border-radius:10px;padding:14px 18px;margin-bottom:8px;display:grid;grid-template-columns:1fr auto;gap:14px;align-items:center;transition:.15s;}
    .ata-row:hover{border-color:#0a66c2;box-shadow:0 4px 10px rgba(10,102,194,.06);}
    .ata-row .titulo{font-size:15px;font-weight:600;color:#1f2937;margin:0;}
    .ata-row .titulo a{color:#1f2937;text-decoration:none;}
    .ata-row .titulo a:hover{color:#0a66c2;}
    .ata-row .meta{display:flex;gap:14px;font-size:12px;color:#64748b;margin-top:6px;flex-wrap:wrap;}
    .ata-row .meta i{color:#94a3b8;margin-right:4px;}
    .ata-row .actions{display:flex;gap:6px;}
    .ata-row .actions a{padding:6px 12px;border-radius:6px;font-size:12px;font-weight:600;text-decoration:none;border:1px solid #d0d5dd;color:#475569;}
    .ata-row .actions a:hover{border-color:#0a66c2;color:#0a66c2;}

    .status-pill{display:inline-block;font-size:11px;font-weight:600;padding:2px 9px;border-radius:999px;letter-spacing:.02em;}
    .status-aberta{background:#dbeafe;color:#1e40af;}
    .status-em_revisao{background:#fef3c7;color:#92400e;}
    .status-finalizada{background:#dcfce7;color:#166534;}
    .status-cancelada{background:#fee2e2;color:#991b1b;}

    .ata-empty{text-align:center;padding:50px 20px;color:#64748b;background:#fff;border-radius:12px;border:1px dashed #e5e7eb;}
    .ata-empty i{font-size:38px;color:#cbd5e1;margin-bottom:10px;display:block;}
</style>

<div class="content">
    <div class="ata-page">
        <div class="ata-header">
            <h1>Atas de Reunião</h1>
            <div>
                <?php if (has_permission_intranet('atas', '', 'create') || is_admin()): ?>
                    <a href="<?php echo base_url('gestao_corporativa/Ata/add' . (!empty($filtros['project_id']) ? '?project_id=' . (int) $filtros['project_id'] : '')); ?>" class="btn btn-info">
                        <i class="fa fa-plus"></i> Nova ata
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <form method="get" class="ata-filters">
            <input type="text" name="busca" placeholder="Buscar por título ou local" value="<?php echo html_escape($filtros['busca'] ?? ''); ?>">
            <select name="status">
                <option value="">Todos status</option>
                <?php foreach ($this->Ata_model->get_statuses() as $s): ?>
                    <option value="<?php echo $s; ?>" <?php echo ($filtros['status'] ?? '') === $s ? 'selected' : ''; ?>><?php echo $this->Ata_model->get_status_label($s); ?></option>
                <?php endforeach; ?>
            </select>
            <select name="project_id">
                <option value="">Todos os projetos</option>
                <?php
                $empresa_id = (int) $this->session->userdata('empresa_id');
                $projects = $this->db->select('id, name')->where('empresa_id', $empresa_id)->order_by('name', 'asc')->get('tblprojects')->result_array();
                foreach ($projects as $p): ?>
                    <option value="<?php echo (int) $p['id']; ?>" <?php echo ((int) ($filtros['project_id'] ?? 0)) === (int) $p['id'] ? 'selected' : ''; ?>><?php echo html_escape($p['name']); ?></option>
                <?php endforeach; ?>
            </select>
            <select name="minha">
                <option value="">Todas</option>
                <option value="1" <?php echo !empty($filtros['minha']) ? 'selected' : ''; ?>>Apenas minhas</option>
            </select>
            <button type="submit" class="btn btn-default">Filtrar</button>
        </form>

        <?php if (empty($atas)): ?>
            <div class="ata-empty">
                <i class="fa fa-file-text-o"></i>
                <p>Nenhuma ata encontrada com esses filtros.</p>
            </div>
        <?php else: ?>
            <?php foreach ($atas as $a): ?>
                <div class="ata-row">
                    <div>
                        <h4 class="titulo">
                            <a href="<?php echo base_url('gestao_corporativa/Ata/view/' . (int) $a['id']); ?>"><?php echo html_escape($a['titulo']); ?></a>
                            <span class="status-pill status-<?php echo $a['status']; ?>" style="margin-left:8px;"><?php echo $this->Ata_model->get_status_label($a['status']); ?></span>
                        </h4>
                        <div class="meta">
                            <?php if (!empty($a['data'])): ?><span><i class="fa fa-calendar"></i><?php echo date('d/m/Y', strtotime($a['data'])); ?><?php if (!empty($a['hora_inicio'])): ?> · <?php echo substr($a['hora_inicio'], 0, 5); ?><?php endif; ?></span><?php endif; ?>
                            <?php if (!empty($a['local'])): ?><span><i class="fa fa-map-marker"></i><?php echo html_escape($a['local']); ?></span><?php endif; ?>
                            <?php if (!empty($a['responsavel_nome'])): ?><span><i class="fa fa-user"></i><?php echo html_escape($a['responsavel_nome']); ?></span><?php endif; ?>
                            <?php if (!empty($a['project_name'])): ?><span><i class="fa fa-folder-o"></i><?php echo html_escape($a['project_name']); ?></span><?php endif; ?>
                        </div>
                    </div>
                    <div class="actions">
                        <a href="<?php echo base_url('gestao_corporativa/Ata/view/' . (int) $a['id']); ?>"><i class="fa fa-eye"></i> Ver</a>
                        <?php if (has_permission_intranet('atas', '', 'edit') || is_admin()): ?>
                            <a href="<?php echo base_url('gestao_corporativa/Ata/edit/' . (int) $a['id']); ?>"><i class="fa fa-pencil"></i> Editar</a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<?php init_tail(); ?>
