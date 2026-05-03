<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head_intranet_novo(false); ?>
<?php $this->load->view('gestao_corporativa/css_background'); ?>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/lte/plugins/select2/css/select2.min.css">

<style>
    .au-page{max-width:1400px;margin:24px auto;padding:0 18px;}
    .au-toolbar{display:flex;justify-content:space-between;align-items:center;margin-bottom:14px;gap:12px;flex-wrap:wrap;}
    .au-toolbar h2{margin:0;font-size:20px;color:#1f2937;}
    .au-filters{display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:10px;background:#fff;border:1px solid #e5e7eb;border-radius:10px;padding:14px;margin-bottom:14px;}
    .au-filters label{font-size:11px;font-weight:600;color:#475569;text-transform:uppercase;letter-spacing:.04em;display:block;margin-bottom:4px;}
    .au-filters input,.au-filters select{width:100%;border:1px solid #d0d5dd;border-radius:6px;padding:7px 10px;font-size:13px;}

    .au-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(330px,1fr));gap:14px;}
    .au-card{background:#fff;border:1px solid #e5e7eb;border-radius:10px;padding:18px;border-left:4px solid #94a3b8;display:flex;flex-direction:column;gap:8px;transition:.15s;}
    .au-card:hover{box-shadow:0 4px 12px rgba(0,0,0,.06);}
    .au-card .codigo{font-family:monospace;font-size:11px;color:#94a3b8;font-weight:700;}
    .au-card .ttl{font-weight:600;color:#1f2937;font-size:15px;text-decoration:none;}
    .au-card .ttl:hover{color:#0a66c2;}
    .au-card .meta{font-size:12px;color:#64748b;display:flex;flex-wrap:wrap;gap:10px;}
    .au-status{display:inline-block;padding:2px 8px;border-radius:6px;font-size:10px;font-weight:700;letter-spacing:.04em;text-transform:uppercase;}
    .au-stats{display:grid;grid-template-columns:1fr 1fr 1fr;gap:6px;border-top:1px solid #f1f5f9;padding-top:8px;margin-top:6px;font-size:11px;text-align:center;}
    .au-stats .num{font-size:18px;font-weight:700;line-height:1;}
    .au-stats .lbl{font-size:9px;color:#94a3b8;text-transform:uppercase;}
    .au-empty{grid-column:1/-1;text-align:center;padding:48px;color:#94a3b8;background:#fff;border:1px dashed #e5e7eb;border-radius:10px;}
</style>

<div class="content">
    <div class="au-page">
        <ol class="breadcrumb" style="background:#fff;">
            <li><a href="<?php echo base_url('gestao_corporativa/intranet'); ?>"><i class="fa fa-home"></i> Home</a></li>
            <li class="active">Auditorias</li>
        </ol>

        <div class="au-toolbar">
            <h2><i class="fa fa-clipboard-check"></i> Auditorias (<?php echo count($auditorias); ?>)</h2>
            <a href="<?php echo base_url('gestao_corporativa/Auditoria/add'); ?>" class="btn btn-info"><i class="fa fa-plus"></i> Nova auditoria</a>
        </div>

        <form method="get" action="">
            <div class="au-filters">
                <div>
                    <label>Buscar</label>
                    <input type="text" name="busca" value="<?php echo html_escape($filtros['busca'] ?? ''); ?>" placeholder="título, código, escopo…">
                </div>
                <div>
                    <label>Tipo</label>
                    <select name="tipo">
                        <option value="">— todas —</option>
                        <?php foreach ($this->Auditoria_model->get_tipos() as $t): ?>
                            <option value="<?php echo $t; ?>" <?php echo $filtros['tipo'] === $t ? 'selected' : ''; ?>><?php echo $this->Auditoria_model->get_tipo_label($t); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label>Status</label>
                    <select name="status">
                        <option value="">— todos —</option>
                        <?php foreach ($this->Auditoria_model->get_statuses() as $s): ?>
                            <option value="<?php echo $s; ?>" <?php echo $filtros['status'] === $s ? 'selected' : ''; ?>><?php echo $this->Auditoria_model->get_status_label($s); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label>Resultado</label>
                    <select name="resultado">
                        <option value="">— todos —</option>
                        <?php foreach ($this->Auditoria_model->get_resultados() as $r): ?>
                            <option value="<?php echo $r; ?>" <?php echo $filtros['resultado'] === $r ? 'selected' : ''; ?>><?php echo $this->Auditoria_model->get_resultado_label($r); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label>Setor auditado</label>
                    <select name="setor_id" class="select2">
                        <option value="">— todos —</option>
                        <?php foreach ($departments as $d): ?>
                            <option value="<?php echo (int) $d['departmentid']; ?>" <?php echo (int) $filtros['setor_id'] === (int) $d['departmentid'] ? 'selected' : ''; ?>><?php echo html_escape($d['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label>Visão</label>
                    <?php $visao = !empty($filtros['criei']) ? 'criei' : (!empty($filtros['responsavel_meu']) ? 'resp' : ''); ?>
                    <select name="visao" onchange="
                        this.form.querySelector('input[name=criei]').value           = this.value === 'criei' ? 1 : '';
                        this.form.querySelector('input[name=responsavel_meu]').value = this.value === 'resp'  ? 1 : '';
                        this.form.submit();
                    ">
                        <option value="">Todos</option>
                        <option value="criei" <?php echo $visao === 'criei' ? 'selected' : ''; ?>>Que criei</option>
                        <option value="resp"  <?php echo $visao === 'resp'  ? 'selected' : ''; ?>>Sou auditor líder</option>
                    </select>
                    <input type="hidden" name="criei" value="<?php echo $visao === 'criei' ? 1 : ''; ?>">
                    <input type="hidden" name="responsavel_meu" value="<?php echo $visao === 'resp' ? 1 : ''; ?>">
                </div>
                <div>
                    <label>&nbsp;</label>
                    <button type="submit" class="btn btn-default" style="width:100%;"><i class="fa fa-filter"></i> Filtrar</button>
                </div>
            </div>
        </form>

        <div class="au-grid">
            <?php if (empty($auditorias)): ?>
                <div class="au-empty">
                    <i class="fa fa-clipboard-check fa-3x" style="color:#cbd5e1;"></i>
                    <h4>Nenhuma auditoria cadastrada.</h4>
                </div>
            <?php else: ?>
                <?php foreach ($auditorias as $a):
                    $cor_st = $this->Auditoria_model->get_status_color($a['status']);
                    $cor_res = $this->Auditoria_model->get_resultado_color($a['resultado']);
                ?>
                    <div class="au-card" style="border-left-color:<?php echo $cor_res; ?>;">
                        <div class="codigo"><?php echo html_escape($a['codigo']); ?></div>
                        <a class="ttl" href="<?php echo base_url('gestao_corporativa/Auditoria/view/' . (int) $a['id']); ?>"><?php echo html_escape($a['titulo']); ?></a>
                        <div class="meta">
                            <span class="au-status" style="background:<?php echo $cor_st; ?>20;color:<?php echo $cor_st; ?>;"><?php echo $this->Auditoria_model->get_status_label($a['status']); ?></span>
                            <span class="au-status" style="background:<?php echo $cor_res; ?>20;color:<?php echo $cor_res; ?>;"><?php echo $this->Auditoria_model->get_resultado_label($a['resultado']); ?></span>
                            <span><i class="fa fa-tag"></i> <?php echo $this->Auditoria_model->get_tipo_label($a['tipo']); ?></span>
                        </div>
                        <div class="meta">
                            <?php if ($a['dt_planejada']): ?><span><i class="fa fa-calendar"></i> <?php echo date('d/m/Y', strtotime($a['dt_planejada'])); ?></span><?php endif; ?>
                            <?php if (!empty($a['auditor_nome'])): ?><span><i class="fa fa-user-tie"></i> <?php echo html_escape($a['auditor_nome']); ?></span><?php endif; ?>
                            <?php if (!empty($a['setor_nome'])): ?><span><i class="fa fa-building"></i> <?php echo html_escape($a['setor_nome']); ?></span><?php endif; ?>
                        </div>
                        <?php if (!empty($a['doc_titulo']) || !empty($a['form_titulo'])): ?>
                            <div class="meta">
                                <?php if (!empty($a['doc_titulo'])): ?><span><i class="far fa-folder-open"></i> <?php echo html_escape($a['doc_codigo']); ?></span><?php endif; ?>
                                <?php if (!empty($a['form_titulo'])): ?><span><i class="fa fa-list-alt"></i> <?php echo html_escape(mb_strimwidth($a['form_titulo'], 0, 30, '…')); ?></span><?php endif; ?>
                            </div>
                        <?php endif; ?>
                        <div class="au-stats">
                            <div><div class="num"><?php echo (int) $a['total_achados']; ?></div><div class="lbl">Achados</div></div>
                            <div><div class="num" style="color:#dc2626;"><?php echo (int) $a['total_ncs']; ?></div><div class="lbl">NCs</div></div>
                            <div><div class="num" style="color:#16a34a;"><?php echo (int) $a['total_fechados']; ?></div><div class="lbl">Fechados</div></div>
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
