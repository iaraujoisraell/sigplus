<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head_intranet_novo(false); ?>
<?php $this->load->view('gestao_corporativa/css_background'); ?>

<link rel="stylesheet" href="<?php echo base_url() ?>assets/lte/plugins/select2/css/select2.min.css">

<style>
    .fm-page{max-width:1400px;margin:24px auto;padding:0 18px;}
    .fm-toolbar{display:flex;justify-content:space-between;align-items:center;margin-bottom:14px;gap:12px;flex-wrap:wrap;}
    .fm-toolbar h2{margin:0;font-size:20px;color:#1f2937;}
    .fm-filters{display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:10px;background:#fff;border:1px solid #e5e7eb;border-radius:10px;padding:14px;margin-bottom:14px;}
    .fm-filters label{font-size:11px;font-weight:600;color:#475569;text-transform:uppercase;letter-spacing:.04em;display:block;margin-bottom:4px;}
    .fm-filters input,.fm-filters select{width:100%;border:1px solid #d0d5dd;border-radius:6px;padding:7px 10px;font-size:13px;}

    .fm-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(320px,1fr));gap:14px;}
    .fm-card{background:#fff;border:1px solid #e5e7eb;border-radius:10px;padding:18px;display:flex;flex-direction:column;gap:8px;transition:.15s;}
    .fm-card:hover{box-shadow:0 4px 12px rgba(0,0,0,.06);border-color:#0a66c2;}
    .fm-card .ttl{font-weight:600;color:#1f2937;font-size:15px;text-decoration:none;display:flex;align-items:center;gap:8px;}
    .fm-card .ttl:hover{color:#0a66c2;}
    .fm-card .desc{font-size:13px;color:#475569;line-height:1.4;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;}
    .fm-card .meta{font-size:12px;color:#64748b;display:flex;flex-wrap:wrap;gap:10px;margin-top:4px;}
    .fm-card .meta span{display:inline-flex;align-items:center;gap:4px;}
    .fm-card .actions{display:flex;justify-content:space-between;align-items:center;margin-top:8px;padding-top:8px;border-top:1px solid #eef1f4;}
    .fm-status{display:inline-block;padding:2px 8px;border-radius:6px;font-size:11px;font-weight:700;letter-spacing:.04em;text-transform:uppercase;}
    .fm-status.rascunho{background:#fef3c7;color:#92400e;}
    .fm-status.publicado{background:#dcfce7;color:#166534;}
    .fm-status.encerrado{background:#e0e7ff;color:#3730a3;}
    .fm-status.arquivado{background:#f1f5f9;color:#475569;}

    .fm-empty{grid-column:1/-1;text-align:center;padding:48px;color:#94a3b8;background:#fff;border:1px dashed #e5e7eb;border-radius:10px;}
</style>

<div class="content">
    <div class="fm-page">
        <ol class="breadcrumb" style="background:#fff;">
            <li><a href="<?php echo base_url('gestao_corporativa/intranet'); ?>"><i class="fa fa-home"></i> Home</a></li>
            <li class="active">Formulários</li>
        </ol>

        <div class="fm-toolbar">
            <h2><i class="fa fa-list-alt"></i> Formulários (<?php echo count($formularios); ?>)</h2>
            <a href="<?php echo base_url('gestao_corporativa/Formulario/add'); ?>" class="btn btn-info"><i class="fa fa-plus"></i> Novo formulário</a>
        </div>

        <form method="get" action="">
            <div class="fm-filters">
                <div>
                    <label>Buscar</label>
                    <input type="text" name="busca" value="<?php echo html_escape($filtros['busca'] ?? ''); ?>" placeholder="título ou descrição">
                </div>
                <div>
                    <label>Projeto</label>
                    <select name="project_id" class="select2">
                        <option value="">— todos —</option>
                        <?php foreach ($projects as $p): ?>
                            <option value="<?php echo (int) $p['id']; ?>" <?php echo (int) $filtros['project_id'] === (int) $p['id'] ? 'selected' : ''; ?>><?php echo html_escape($p['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label>Status</label>
                    <select name="status">
                        <option value="">— todos —</option>
                        <?php foreach ($this->Formulario_model->get_statuses() as $s): ?>
                            <option value="<?php echo $s; ?>" <?php echo $filtros['status'] === $s ? 'selected' : ''; ?>><?php echo $this->Formulario_model->get_status_label($s); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label>Visão</label>
                    <select name="meu" onchange="this.form.submit()">
                        <option value="">Todos</option>
                        <option value="1" <?php echo !empty($filtros['meu']) ? 'selected' : ''; ?>>Que criei</option>
                    </select>
                </div>
                <div>
                    <label>&nbsp;</label>
                    <button type="submit" class="btn btn-default" style="width:100%;"><i class="fa fa-filter"></i> Filtrar</button>
                </div>
            </div>
        </form>

        <div class="fm-grid">
            <?php if (empty($formularios)): ?>
                <div class="fm-empty">
                    <i class="fa fa-list-alt fa-3x" style="color:#cbd5e1;"></i>
                    <h4>Nenhum formulário encontrado.</h4>
                    <p>Crie o primeiro clicando em "Novo formulário".</p>
                </div>
            <?php else: ?>
                <?php foreach ($formularios as $f): ?>
                    <div class="fm-card">
                        <a class="ttl" href="<?php echo base_url('gestao_corporativa/Formulario/edit/' . (int) $f['id']); ?>">
                            <i class="fa fa-list-alt" style="color:#0a66c2;"></i>
                            <?php echo html_escape($f['titulo']); ?>
                        </a>
                        <?php if (!empty($f['descricao'])): ?>
                            <div class="desc"><?php echo html_escape(strip_tags($f['descricao'])); ?></div>
                        <?php endif; ?>
                        <div class="meta">
                            <span class="fm-status <?php echo $f['status']; ?>"><?php echo $this->Formulario_model->get_status_label($f['status']); ?></span>
                            <span><i class="fa fa-question-circle"></i> <?php echo (int) $f['total_perguntas']; ?> perg.</span>
                            <span><i class="fa fa-comments"></i> <?php echo (int) $f['total_respostas']; ?> resp.</span>
                        </div>
                        <div class="meta">
                            <?php if (!empty($f['project_name'])): ?>
                                <span><i class="fa fa-folder-open"></i> <?php echo html_escape($f['project_name']); ?></span>
                            <?php endif; ?>
                            <span><i class="fa fa-user"></i> <?php echo html_escape($f['criador_nome']); ?></span>
                            <span><i class="fa fa-calendar"></i> <?php echo date('d/m/Y', strtotime($f['data_created'])); ?></span>
                        </div>
                        <div class="actions">
                            <div>
                                <a href="<?php echo base_url('gestao_corporativa/Formulario/edit/' . (int) $f['id']); ?>" class="btn btn-default btn-xs"><i class="fa fa-pencil"></i> Editar</a>
                                <a href="<?php echo base_url('gestao_corporativa/Formulario/respostas/' . (int) $f['id']); ?>" class="btn btn-default btn-xs"><i class="fa fa-table"></i> Respostas</a>
                            </div>
                            <div>
                                <?php if ($f['status'] === 'publicado'): ?>
                                    <a href="<?php echo base_url('formularios/web/' . $f['form_key']); ?>" target="_blank" class="btn btn-success btn-xs" title="Abrir formulário público"><i class="fa fa-external-link-alt"></i></a>
                                <?php endif; ?>
                                <a href="<?php echo base_url('gestao_corporativa/Formulario/duplicar/' . (int) $f['id']); ?>" class="btn btn-default btn-xs" title="Duplicar"><i class="fa fa-copy"></i></a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php init_tail(); ?>
<script src="<?php echo base_url() ?>assets/lte/plugins/select2/js/select2.full.min.js"></script>
<script>
    $(function () { $('.select2').select2({width: '100%'}); });
</script>
</body>
</html>
