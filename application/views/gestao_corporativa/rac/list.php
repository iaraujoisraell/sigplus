<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head_intranet_novo(false); ?>
<?php $this->load->view('gestao_corporativa/css_background'); ?>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/lte/plugins/select2/css/select2.min.css">

<style>
    .rac-page{max-width:1400px;margin:24px auto;padding:0 18px;}
    .rac-toolbar{display:flex;justify-content:space-between;align-items:center;margin-bottom:14px;gap:12px;flex-wrap:wrap;}
    .rac-filters{display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:10px;background:#fff;border:1px solid #e5e7eb;border-radius:10px;padding:14px;margin-bottom:14px;}
    .rac-filters label{font-size:11px;font-weight:600;color:#475569;text-transform:uppercase;display:block;margin-bottom:4px;}
    .rac-filters input,.rac-filters select{width:100%;border:1px solid #d0d5dd;border-radius:6px;padding:7px 10px;font-size:13px;}

    .rac-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(330px,1fr));gap:14px;}
    .rac-card{background:#fff;border:1px solid #e5e7eb;border-radius:10px;padding:18px;border-left:4px solid #94a3b8;display:flex;flex-direction:column;gap:8px;}
    .rac-card .codigo{font-family:monospace;font-size:11px;color:#94a3b8;font-weight:700;}
    .rac-card .ttl{font-weight:600;color:#1f2937;font-size:15px;text-decoration:none;}
    .rac-card .ttl:hover{color:#0a66c2;}
    .rac-card .meta{font-size:12px;color:#64748b;display:flex;flex-wrap:wrap;gap:10px;}
    .rac-status{padding:2px 8px;border-radius:6px;font-size:10px;font-weight:700;text-transform:uppercase;}
    .rac-empty{grid-column:1/-1;text-align:center;padding:48px;color:#94a3b8;background:#fff;border:1px dashed #e5e7eb;border-radius:10px;}
</style>

<div class="content">
    <div class="rac-page">
        <ol class="breadcrumb" style="background:#fff;">
            <li><a href="<?php echo base_url('gestao_corporativa/intranet'); ?>"><i class="fa fa-home"></i> Home</a></li>
            <li class="active">RAC</li>
        </ol>

        <div class="rac-toolbar">
            <h2 style="margin:0;font-size:20px;color:#1f2937;"><i class="fa fa-balance-scale"></i> Análise Crítica pela Direção (<?php echo count($racs); ?>)</h2>
            <a href="<?php echo base_url('gestao_corporativa/Rac/add'); ?>" class="btn btn-info"><i class="fa fa-plus"></i> Nova RAC</a>
        </div>

        <form method="get" action="">
            <div class="rac-filters">
                <div><label>Buscar</label><input type="text" name="busca" value="<?php echo html_escape($filtros['busca'] ?? ''); ?>" placeholder="título, código…"></div>
                <div><label>Ano</label>
                    <select name="ano">
                        <option value="">— todos —</option>
                        <?php for ($y = (int) date('Y'); $y >= (int) date('Y') - 4; $y--): ?>
                            <option value="<?php echo $y; ?>" <?php echo (int) $filtros['ano'] === $y ? 'selected' : ''; ?>><?php echo $y; ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div><label>Status</label>
                    <select name="status">
                        <option value="">— todos —</option>
                        <?php foreach ($this->Rac_model->get_statuses() as $s): ?>
                            <option value="<?php echo $s; ?>" <?php echo $filtros['status'] === $s ? 'selected' : ''; ?>><?php echo $this->Rac_model->get_status_label($s); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div><label>Projeto</label>
                    <select name="project_id" class="select2">
                        <option value="">— todos —</option>
                        <?php foreach ($projects as $p): ?>
                            <option value="<?php echo (int) $p['id']; ?>" <?php echo (int) $filtros['project_id'] === (int) $p['id'] ? 'selected' : ''; ?>><?php echo html_escape($p['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div><label>&nbsp;</label><button type="submit" class="btn btn-default" style="width:100%;"><i class="fa fa-filter"></i> Filtrar</button></div>
            </div>
        </form>

        <div class="rac-grid">
            <?php if (empty($racs)): ?>
                <div class="rac-empty"><i class="fa fa-balance-scale fa-3x" style="color:#cbd5e1;"></i><h4>Nenhuma RAC ainda.</h4></div>
            <?php else: ?>
                <?php foreach ($racs as $r): $cor = $this->Rac_model->get_status_color($r['status']); ?>
                    <div class="rac-card" style="border-left-color:<?php echo $cor; ?>;">
                        <div class="codigo"><?php echo html_escape($r['codigo']); ?></div>
                        <a class="ttl" href="<?php echo base_url('gestao_corporativa/Rac/view/' . (int) $r['id']); ?>"><?php echo html_escape($r['titulo']); ?></a>
                        <div class="meta">
                            <span class="rac-status" style="background:<?php echo $cor; ?>20;color:<?php echo $cor; ?>;"><?php echo $this->Rac_model->get_status_label($r['status']); ?></span>
                            <span><i class="fa fa-calendar"></i> <?php echo date('d/m/Y', strtotime($r['periodo_inicio'])); ?> → <?php echo date('d/m/Y', strtotime($r['periodo_fim'])); ?></span>
                        </div>
                        <div class="meta">
                            <?php if ($r['dt_realizacao']): ?><span style="color:#16a34a;"><i class="fa fa-check"></i> realizada <?php echo date('d/m/Y', strtotime($r['dt_realizacao'])); ?></span><?php endif; ?>
                            <?php if ($r['presidida_nome']): ?><span><i class="fa fa-user-tie"></i> <?php echo html_escape($r['presidida_nome']); ?></span><?php endif; ?>
                            <span><i class="fa fa-users"></i> <?php echo (int) $r['total_presentes']; ?>/<?php echo (int) $r['total_participantes']; ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php init_tail(); ?>
<script src="<?php echo base_url() ?>assets/lte/plugins/select2/js/select2.full.min.js"></script>
<script>$(function(){$('.select2').select2({width:'100%'});});</script>
</body>
</html>
