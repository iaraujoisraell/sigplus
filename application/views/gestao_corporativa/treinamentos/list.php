<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head_intranet_novo(false); ?>
<?php $this->load->view('gestao_corporativa/css_background'); ?>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/lte/plugins/select2/css/select2.min.css">

<style>
    .tr-page{max-width:1400px;margin:24px auto;padding:0 18px;}
    .tr-toolbar{display:flex;justify-content:space-between;align-items:center;margin-bottom:14px;gap:12px;flex-wrap:wrap;}
    .tr-toolbar h2{margin:0;font-size:20px;color:#1f2937;}
    .tr-filters{display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:10px;background:#fff;border:1px solid #e5e7eb;border-radius:10px;padding:14px;margin-bottom:14px;}
    .tr-filters label{font-size:11px;font-weight:600;color:#475569;text-transform:uppercase;letter-spacing:.04em;display:block;margin-bottom:4px;}
    .tr-filters input,.tr-filters select{width:100%;border:1px solid #d0d5dd;border-radius:6px;padding:7px 10px;font-size:13px;}

    .tr-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(310px,1fr));gap:14px;}
    .tr-card{background:#fff;border:1px solid #e5e7eb;border-radius:10px;padding:18px;border-left:4px solid #94a3b8;transition:.15s;display:flex;flex-direction:column;gap:8px;}
    .tr-card:hover{box-shadow:0 4px 12px rgba(0,0,0,.06);}
    .tr-card .codigo{font-family:monospace;font-size:11px;color:#94a3b8;font-weight:700;}
    .tr-card .ttl{font-weight:600;color:#1f2937;font-size:15px;text-decoration:none;}
    .tr-card .ttl:hover{color:#0a66c2;}
    .tr-card .meta{font-size:12px;color:#64748b;display:flex;flex-wrap:wrap;gap:10px;}
    .tr-status{display:inline-block;padding:2px 8px;border-radius:6px;font-size:10px;font-weight:700;letter-spacing:.04em;text-transform:uppercase;}
    .tr-card .stats{display:grid;grid-template-columns:1fr 1fr 1fr;gap:8px;border-top:1px solid #f1f5f9;padding-top:8px;font-size:12px;}
    .tr-card .stats div{text-align:center;}
    .tr-card .stats .num{font-size:18px;font-weight:700;color:#0a66c2;}
    .tr-card .stats .lbl{font-size:10px;color:#94a3b8;text-transform:uppercase;}
    .tr-empty{grid-column:1/-1;text-align:center;padding:48px;color:#94a3b8;background:#fff;border:1px dashed #e5e7eb;border-radius:10px;}
</style>

<div class="content">
    <div class="tr-page">
        <ol class="breadcrumb" style="background:#fff;">
            <li><a href="<?php echo base_url('gestao_corporativa/intranet'); ?>"><i class="fa fa-home"></i> Home</a></li>
            <li class="active">Treinamentos</li>
        </ol>

        <div class="tr-toolbar">
            <h2><i class="fa fa-graduation-cap"></i> Treinamentos (<?php echo count($treinamentos); ?>)</h2>
            <a href="<?php echo base_url('gestao_corporativa/Treinamento/add'); ?>" class="btn btn-info"><i class="fa fa-plus"></i> Novo treinamento</a>
        </div>

        <form method="get" action="">
            <div class="tr-filters">
                <div>
                    <label>Buscar</label>
                    <input type="text" name="busca" value="<?php echo html_escape($filtros['busca'] ?? ''); ?>" placeholder="título, código…">
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
                        <?php foreach ($this->Treinamento_model->get_statuses() as $s): ?>
                            <option value="<?php echo $s; ?>" <?php echo $filtros['status'] === $s ? 'selected' : ''; ?>><?php echo $this->Treinamento_model->get_status_label($s); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label>Modalidade</label>
                    <select name="modalidade">
                        <option value="">— todas —</option>
                        <?php foreach ($this->Treinamento_model->get_modalidades() as $m): ?>
                            <option value="<?php echo $m; ?>" <?php echo $filtros['modalidade'] === $m ? 'selected' : ''; ?>><?php echo $this->Treinamento_model->get_modalidade_label($m); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label>Visão</label>
                    <?php $visao = !empty($filtros['criei']) ? 'criei' : (!empty($filtros['responsavel_meu']) ? 'resp' : (!empty($filtros['inscrito_eu']) ? 'inscrito' : '')); ?>
                    <select name="visao" onchange="
                        this.form.querySelector('input[name=criei]').value           = this.value === 'criei' ? 1 : '';
                        this.form.querySelector('input[name=responsavel_meu]').value = this.value === 'resp'  ? 1 : '';
                        this.form.querySelector('input[name=inscrito_eu]').value     = this.value === 'inscrito' ? 1 : '';
                        this.form.submit();
                    ">
                        <option value="">Todos</option>
                        <option value="criei"    <?php echo $visao === 'criei'    ? 'selected' : ''; ?>>Que criei</option>
                        <option value="resp"     <?php echo $visao === 'resp'     ? 'selected' : ''; ?>>Sou instrutor</option>
                        <option value="inscrito" <?php echo $visao === 'inscrito' ? 'selected' : ''; ?>>Estou inscrito</option>
                    </select>
                    <input type="hidden" name="criei" value="<?php echo $visao === 'criei' ? 1 : ''; ?>">
                    <input type="hidden" name="responsavel_meu" value="<?php echo $visao === 'resp' ? 1 : ''; ?>">
                    <input type="hidden" name="inscrito_eu" value="<?php echo $visao === 'inscrito' ? 1 : ''; ?>">
                </div>
                <div>
                    <label>&nbsp;</label>
                    <button type="submit" class="btn btn-default" style="width:100%;"><i class="fa fa-filter"></i> Filtrar</button>
                </div>
            </div>
        </form>

        <div class="tr-grid">
            <?php if (empty($treinamentos)): ?>
                <div class="tr-empty">
                    <i class="fa fa-graduation-cap fa-3x" style="color:#cbd5e1;"></i>
                    <h4>Nenhum treinamento cadastrado.</h4>
                </div>
            <?php else: ?>
                <?php foreach ($treinamentos as $t):
                    $cor = $this->Treinamento_model->get_status_color($t['status']);
                    $pct_pres = $t['total_inscritos'] > 0 ? round($t['total_presentes'] * 100 / $t['total_inscritos']) : 0;
                ?>
                    <div class="tr-card" style="border-left-color:<?php echo $cor; ?>;">
                        <div class="codigo"><?php echo html_escape($t['codigo']); ?></div>
                        <a class="ttl" href="<?php echo base_url('gestao_corporativa/Treinamento/view/' . (int) $t['id']); ?>"><?php echo html_escape($t['titulo']); ?></a>
                        <div class="meta">
                            <span class="tr-status" style="background:<?php echo $cor; ?>20;color:<?php echo $cor; ?>;"><?php echo $this->Treinamento_model->get_status_label($t['status']); ?></span>
                            <span><i class="fa fa-laptop"></i> <?php echo $this->Treinamento_model->get_modalidade_label($t['modalidade']); ?></span>
                            <?php if ($t['carga_horaria']): ?><span><i class="fa fa-clock"></i> <?php echo (float) $t['carga_horaria']; ?>h</span><?php endif; ?>
                        </div>
                        <div class="meta">
                            <?php if ($t['dt_inicio']): ?><span><i class="fa fa-calendar"></i> <?php echo date('d/m/Y H:i', strtotime($t['dt_inicio'])); ?></span><?php endif; ?>
                            <?php if (!empty($t['instrutor_nome'])): ?><span><i class="fa fa-chalkboard-teacher"></i> <?php echo html_escape($t['instrutor_nome']); ?></span>
                            <?php elseif (!empty($t['instrutor_externo'])): ?><span><i class="fa fa-chalkboard-teacher"></i> <?php echo html_escape($t['instrutor_externo']); ?></span><?php endif; ?>
                        </div>
                        <div class="meta">
                            <?php if (!empty($t['documento_titulo'])): ?>
                                <span><i class="far fa-folder-open"></i> <?php echo html_escape($t['documento_codigo'] . ' — ' . mb_strimwidth($t['documento_titulo'], 0, 40, '…')); ?></span>
                            <?php endif; ?>
                            <?php if (!empty($t['project_name'])): ?><span><i class="fa fa-folder"></i> <?php echo html_escape($t['project_name']); ?></span><?php endif; ?>
                        </div>
                        <div class="stats">
                            <div><div class="num"><?php echo (int) $t['total_inscritos']; ?></div><div class="lbl">Inscritos</div></div>
                            <div><div class="num" style="color:#16a34a;"><?php echo (int) $t['total_presentes']; ?></div><div class="lbl">Presentes</div></div>
                            <div><div class="num" style="color:#7c3aed;"><?php echo (int) $t['total_aprovados']; ?></div><div class="lbl">Aprovados</div></div>
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
