<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head_intranet_novo(false); ?>
<?php $this->load->view('gestao_corporativa/css_background'); ?>

<link rel="stylesheet" href="<?php echo base_url() ?>assets/lte/plugins/select2/css/select2.min.css">

<style>
    .doc-page{max-width:1400px;margin:24px auto;padding:0 18px;}
    .doc-toolbar{display:flex;justify-content:space-between;align-items:center;margin-bottom:14px;gap:12px;flex-wrap:wrap;}
    .doc-toolbar h2{margin:0;font-size:20px;color:#1f2937;}
    .doc-filters{display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:10px;background:#fff;border:1px solid #e5e7eb;border-radius:10px;padding:14px;margin-bottom:14px;}
    .doc-filters label{font-size:11px;font-weight:600;color:#475569;text-transform:uppercase;letter-spacing:.04em;display:block;margin-bottom:4px;}
    .doc-filters input,.doc-filters select{width:100%;border:1px solid #d0d5dd;border-radius:6px;padding:7px 10px;font-size:13px;}

    .doc-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(320px,1fr));gap:14px;}
    .doc-card{background:#fff;border:1px solid #e5e7eb;border-radius:10px;padding:18px;display:flex;flex-direction:column;gap:8px;transition:.15s;border-left:4px solid #94a3b8;}
    .doc-card:hover{box-shadow:0 4px 12px rgba(0,0,0,.06);}
    .doc-card .ttl{font-weight:600;color:#1f2937;font-size:15px;text-decoration:none;}
    .doc-card .ttl:hover{color:#0a66c2;}
    .doc-card .codigo{font-size:11px;color:#94a3b8;font-family:monospace;font-weight:700;}
    .doc-card .desc{font-size:13px;color:#475569;line-height:1.4;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;}
    .doc-card .meta{font-size:12px;color:#64748b;display:flex;flex-wrap:wrap;gap:10px;margin-top:4px;}
    .doc-card .actions{display:flex;justify-content:space-between;align-items:center;margin-top:8px;padding-top:8px;border-top:1px solid #eef1f4;}
    .doc-status{display:inline-block;padding:2px 8px;border-radius:6px;font-size:10px;font-weight:700;letter-spacing:.04em;text-transform:uppercase;}
    .doc-status.rascunho{background:#fef3c7;color:#92400e;}
    .doc-status.em_aprovacao{background:#fef3c7;color:#92400e;}
    .doc-status.aprovado{background:#dcfce7;color:#166534;}
    .doc-status.publicado{background:#dbeafe;color:#1e40af;}
    .doc-status.reprovado{background:#fee2e2;color:#991b1b;}
    .doc-status.arquivado{background:#f1f5f9;color:#475569;}

    .doc-empty{grid-column:1/-1;text-align:center;padding:48px;color:#94a3b8;background:#fff;border:1px dashed #e5e7eb;border-radius:10px;}
    .ciente-bar{height:4px;background:#f3f4f6;border-radius:999px;margin-top:6px;overflow:hidden;}
    .ciente-bar > div{height:100%;background:linear-gradient(90deg,#16a34a,#0a66c2);}
</style>

<div class="content">
    <div class="doc-page">
        <ol class="breadcrumb" style="background:#fff;">
            <li><a href="<?php echo base_url('gestao_corporativa/intranet'); ?>"><i class="fa fa-home"></i> Home</a></li>
            <li class="active">Documentos</li>
        </ol>

        <div class="doc-toolbar">
            <h2><i class="far fa-folder-open"></i> Documentos (<?php echo count($documentos); ?>)</h2>
            <a href="<?php echo base_url('gestao_corporativa/Documento/add'); ?>" class="btn btn-info"><i class="fa fa-plus"></i> Novo documento</a>
        </div>

        <form method="get" action="">
            <div class="doc-filters">
                <div>
                    <label>Buscar</label>
                    <input type="text" name="busca" value="<?php echo html_escape($filtros['busca'] ?? ''); ?>" placeholder="título, código…">
                </div>
                <div>
                    <label>Categoria</label>
                    <select name="categoria_id" class="select2">
                        <option value="">— todas —</option>
                        <?php foreach ($categorias as $c): ?>
                            <option value="<?php echo (int) $c['id']; ?>" <?php echo (int) $filtros['categoria_id'] === (int) $c['id'] ? 'selected' : ''; ?>><?php echo html_escape($c['titulo']); ?></option>
                        <?php endforeach; ?>
                    </select>
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
                    <label>Visão</label>
                    <?php $visao = !empty($filtros['criei']) ? 'criei' : (!empty($filtros['responsavel_meu']) ? 'resp' : (!empty($filtros['meu']) ? 'meu' : '')); ?>
                    <select name="visao" onchange="
                        this.form.querySelector('input[name=criei]').value           = this.value === 'criei' ? 1 : '';
                        this.form.querySelector('input[name=responsavel_meu]').value = this.value === 'resp'  ? 1 : '';
                        this.form.querySelector('input[name=meu]').value             = this.value === 'meu'   ? 1 : '';
                        this.form.submit();
                    ">
                        <option value="">Todos</option>
                        <option value="criei" <?php echo $visao === 'criei' ? 'selected' : ''; ?>>Que criei</option>
                        <option value="resp"  <?php echo $visao === 'resp'  ? 'selected' : ''; ?>>Sou responsável</option>
                        <option value="meu"   <?php echo $visao === 'meu'   ? 'selected' : ''; ?>>Tudo que envolve eu</option>
                    </select>
                    <input type="hidden" name="criei" value="<?php echo $visao === 'criei' ? 1 : ''; ?>">
                    <input type="hidden" name="responsavel_meu" value="<?php echo $visao === 'resp' ? 1 : ''; ?>">
                    <input type="hidden" name="meu" value="<?php echo $visao === 'meu' ? 1 : ''; ?>">
                </div>
                <div>
                    <label>&nbsp;</label>
                    <label style="display:flex;align-items:center;gap:6px;font-size:13px;font-weight:400;text-transform:none;color:#475569;cursor:pointer;">
                        <input type="checkbox" name="publicado" value="1" <?php echo !empty($filtros['publicado']) ? 'checked' : ''; ?>> Só publicados
                    </label>
                </div>
                <div>
                    <label>&nbsp;</label>
                    <button type="submit" class="btn btn-default" style="width:100%;"><i class="fa fa-filter"></i> Filtrar</button>
                </div>
            </div>
        </form>

        <div class="doc-grid">
            <?php if (empty($documentos)): ?>
                <div class="doc-empty">
                    <i class="far fa-folder-open fa-3x" style="color:#cbd5e1;"></i>
                    <h4>Nenhum documento encontrado.</h4>
                </div>
            <?php else: ?>
                <?php foreach ($documentos as $d):
                    $cor = $this->Documentos_model->get_status_color($d['status_slug']);
                    $pct = (int) $d['total_destinatarios'] > 0
                        ? round((int) $d['total_lidos'] * 100 / (int) $d['total_destinatarios'])
                        : 0;
                ?>
                    <div class="doc-card" style="border-left-color:<?php echo $cor; ?>;">
                        <div class="codigo"><?php echo html_escape($d['codigo']); ?></div>
                        <a class="ttl" href="<?php echo base_url('gestao_corporativa/Documento/view/' . (int) $d['id']); ?>">
                            <?php echo html_escape($d['titulo']); ?>
                        </a>
                        <?php if (!empty($d['descricao'])): ?>
                            <div class="desc"><?php echo html_escape(strip_tags($d['descricao'])); ?></div>
                        <?php endif; ?>
                        <div class="meta">
                            <span class="doc-status <?php echo $d['status_slug']; ?>"><?php echo $this->Documentos_model->get_status_label($d['status_slug']); ?></span>
                            <?php if (!empty($d['categoria_nome'])): ?><span><i class="fa fa-tag"></i> <?php echo html_escape($d['categoria_nome']); ?></span><?php endif; ?>
                            <?php if (!empty($d['project_name'])): ?><span><i class="fa fa-folder-open"></i> <?php echo html_escape($d['project_name']); ?></span><?php endif; ?>
                            <span><i class="fa fa-code-branch"></i> v<?php echo (int) $d['numero_versao']; ?></span>
                        </div>
                        <div class="meta">
                            <span><i class="fa fa-user"></i> <?php echo html_escape($d['criador_nome']); ?></span>
                            <span><i class="fa fa-calendar"></i> <?php echo date('d/m/Y', strtotime($d['data_cadastro'])); ?></span>
                        </div>
                        <?php if ((int) $d['total_destinatarios'] > 0): ?>
                            <div>
                                <div class="meta"><span><i class="fa fa-eye"></i> <?php echo (int) $d['total_lidos']; ?> de <?php echo (int) $d['total_destinatarios']; ?> leram (<?php echo $pct; ?>%)</span></div>
                                <div class="ciente-bar"><div style="width:<?php echo $pct; ?>%;"></div></div>
                            </div>
                        <?php endif; ?>
                        <div class="actions">
                            <div>
                                <a href="<?php echo base_url('gestao_corporativa/Documento/view/' . (int) $d['id']); ?>" class="btn btn-default btn-xs"><i class="fa fa-eye"></i> Abrir</a>
                                <?php if ($this->Documentos_model->pode_editar($d)): ?>
                                    <a href="<?php echo base_url('gestao_corporativa/Documento/edit/' . (int) $d['id']); ?>" class="btn btn-default btn-xs"><i class="fa fa-pencil"></i> Editar</a>
                                <?php endif; ?>
                            </div>
                            <?php if (!empty($d['file'])): ?>
                                <a href="<?php echo base_url('gestao_corporativa/Documento/download/' . (int) $d['id']); ?>" class="btn btn-success btn-xs"><i class="fa fa-download"></i></a>
                            <?php endif; ?>
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
