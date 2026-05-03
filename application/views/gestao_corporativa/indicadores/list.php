<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head_intranet_novo(false); ?>
<?php $this->load->view('gestao_corporativa/css_background'); ?>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/lte/plugins/select2/css/select2.min.css">

<style>
    .ind-page{max-width:1400px;margin:24px auto;padding:0 18px;}
    .ind-toolbar{display:flex;justify-content:space-between;align-items:center;margin-bottom:14px;gap:12px;flex-wrap:wrap;}
    .ind-toolbar h2{margin:0;font-size:20px;color:#1f2937;}
    .ind-filters{display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:10px;background:#fff;border:1px solid #e5e7eb;border-radius:10px;padding:14px;margin-bottom:14px;}
    .ind-filters label{font-size:11px;font-weight:600;color:#475569;text-transform:uppercase;letter-spacing:.04em;display:block;margin-bottom:4px;}
    .ind-filters input,.ind-filters select{width:100%;border:1px solid #d0d5dd;border-radius:6px;padding:7px 10px;font-size:13px;}

    .ind-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(290px,1fr));gap:14px;}
    .ind-card{background:#fff;border:1px solid #e5e7eb;border-radius:10px;padding:18px;border-left:4px solid #94a3b8;transition:.15s;}
    .ind-card:hover{box-shadow:0 4px 12px rgba(0,0,0,.06);}
    .ind-card .codigo{font-family:monospace;font-size:11px;color:#94a3b8;font-weight:700;}
    .ind-card .ttl{display:block;font-weight:600;color:#1f2937;font-size:14px;text-decoration:none;margin:4px 0 8px;}
    .ind-card .ttl:hover{color:#0a66c2;}
    .ind-card .valor-box{display:flex;justify-content:space-between;align-items:flex-end;padding:10px 0;border-top:1px solid #f1f5f9;border-bottom:1px solid #f1f5f9;margin:8px 0;}
    .ind-card .valor-box .valor{font-size:28px;font-weight:700;line-height:1;}
    .ind-card .valor-box .meta{text-align:right;font-size:11px;color:#94a3b8;}
    .ind-card .valor-box .meta strong{color:#475569;font-size:13px;display:block;}
    .ind-card .meta-info{font-size:11px;color:#64748b;display:flex;flex-wrap:wrap;gap:10px;margin-top:6px;}
    .ind-status{display:inline-block;padding:2px 8px;border-radius:6px;font-size:10px;font-weight:700;letter-spacing:.04em;text-transform:uppercase;}
    .ind-pct-bar{height:6px;background:#f3f4f6;border-radius:999px;margin-top:6px;overflow:hidden;}
    .ind-pct-bar > div{height:100%;border-radius:999px;}
    .ind-empty{grid-column:1/-1;text-align:center;padding:48px;color:#94a3b8;background:#fff;border:1px dashed #e5e7eb;border-radius:10px;}
</style>

<div class="content">
    <div class="ind-page">
        <ol class="breadcrumb" style="background:#fff;">
            <li><a href="<?php echo base_url('gestao_corporativa/intranet'); ?>"><i class="fa fa-home"></i> Home</a></li>
            <li class="active">Indicadores</li>
        </ol>

        <div class="ind-toolbar">
            <h2><i class="fa fa-chart-line"></i> Indicadores (<?php echo count($indicadores); ?>)</h2>
            <a href="<?php echo base_url('gestao_corporativa/Indicador/add'); ?>" class="btn btn-info"><i class="fa fa-plus"></i> Novo indicador</a>
        </div>

        <form method="get" action="">
            <div class="ind-filters">
                <div>
                    <label>Buscar</label>
                    <input type="text" name="busca" value="<?php echo html_escape($filtros['busca'] ?? ''); ?>" placeholder="nome, código…">
                </div>
                <div>
                    <label>Setor</label>
                    <select name="setor_id" class="select2">
                        <option value="">— todos —</option>
                        <?php foreach ($departments as $d): ?>
                            <option value="<?php echo (int) $d['departmentid']; ?>" <?php echo (int) $filtros['setor_id'] === (int) $d['departmentid'] ? 'selected' : ''; ?>><?php echo html_escape($d['name']); ?></option>
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
                    <label>Status</label>
                    <select name="status">
                        <option value="">— todos —</option>
                        <?php foreach ($this->Indicador_model->get_statuses() as $s): ?>
                            <option value="<?php echo $s; ?>" <?php echo $filtros['status'] === $s ? 'selected' : ''; ?>><?php echo $this->Indicador_model->get_status_label($s); ?></option>
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
                    <button type="submit" class="btn btn-default" style="width:100%;"><i class="fa fa-filter"></i> Filtrar</button>
                </div>
            </div>
        </form>

        <div class="ind-grid">
            <?php if (empty($indicadores)): ?>
                <div class="ind-empty">
                    <i class="fa fa-chart-line fa-3x" style="color:#cbd5e1;"></i>
                    <h4>Nenhum indicador cadastrado.</h4>
                </div>
            <?php else: ?>
                <?php foreach ($indicadores as $i):
                    $ava = $i['avaliacao'];
                    $val = $i['ultimo_valor'];
                    $val_fmt = $val !== null ? number_format((float) $val, (int) $i['casas_decimais'], ',', '.') . ($i['unidade'] ? ' ' . $i['unidade'] : '') : '—';
                    $meta_fmt = $i['meta'] !== null ? number_format((float) $i['meta'], (int) $i['casas_decimais'], ',', '.') . ($i['unidade'] ? ' ' . $i['unidade'] : '') : '—';
                    $per_fmt = $i['ultimo_periodo'] ? $this->Indicador_model->format_periodo($i['periodicidade'], $i['ultimo_periodo']) : '';
                    $pct = $ava['pct'] !== null ? min(150, max(0, (float) $ava['pct'])) : 0;
                ?>
                    <div class="ind-card" style="border-left-color:<?php echo $ava['cor']; ?>;">
                        <div class="codigo"><?php echo html_escape($i['codigo']); ?></div>
                        <a class="ttl" href="<?php echo base_url('gestao_corporativa/Indicador/view/' . (int) $i['id']); ?>"><?php echo html_escape($i['nome']); ?></a>

                        <div class="valor-box">
                            <div>
                                <div class="valor" style="color:<?php echo $ava['cor']; ?>;"><?php echo $val_fmt; ?></div>
                                <?php if ($per_fmt): ?>
                                    <div style="font-size:11px;color:#94a3b8;"><?php echo $per_fmt; ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="meta">
                                Meta<strong><?php echo $meta_fmt; ?></strong>
                                <?php if ($ava['pct'] !== null): ?>
                                    <span style="color:<?php echo $ava['cor']; ?>;font-weight:700;font-size:11px;"><?php echo $ava['pct']; ?>%</span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <?php if ($ava['pct'] !== null): ?>
                            <div class="ind-pct-bar"><div style="width:<?php echo min(100, $pct); ?>%;background:<?php echo $ava['cor']; ?>;"></div></div>
                        <?php endif; ?>

                        <div class="meta-info" style="margin-top:8px;">
                            <span class="ind-status" style="background:<?php echo $ava['cor']; ?>20;color:<?php echo $ava['cor']; ?>;"><?php echo $ava['label']; ?></span>
                            <span><i class="fa fa-clock"></i> <?php echo $this->Indicador_model->get_periodicidade_label($i['periodicidade']); ?></span>
                            <?php if (!empty($i['setor_nome'])): ?><span><i class="fa fa-building"></i> <?php echo html_escape($i['setor_nome']); ?></span><?php endif; ?>
                            <?php if (!empty($i['responsavel_nome'])): ?><span><i class="fa fa-user"></i> <?php echo html_escape($i['responsavel_nome']); ?></span><?php endif; ?>
                            <span><i class="fa fa-database"></i> <?php echo (int) $i['total_medicoes']; ?> coletas</span>
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
