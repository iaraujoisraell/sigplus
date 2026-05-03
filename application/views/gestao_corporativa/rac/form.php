<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head_intranet_novo(false); ?>
<?php $this->load->view('gestao_corporativa/css_background'); ?>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/lte/plugins/select2/css/select2.min.css">
<link rel="stylesheet" href="<?php echo base_url() ?>assets/lte/plugins/summernote/summernote-bs4.min.css">

<style>
    .rac-form-page{max-width:1100px;margin:24px auto;padding:0 18px;}
    .form-card{background:#fff;border:1px solid #e5e7eb;border-radius:10px;margin-bottom:14px;}
    .form-card-header{padding:12px 18px;border-bottom:1px solid #eef1f4;font-weight:600;color:#1f2937;font-size:14px;}
    .form-card-body{padding:18px;}
    .form-grid-2{display:grid;grid-template-columns:1fr 1fr;gap:14px;}
    .form-grid-3{display:grid;grid-template-columns:1fr 1fr 1fr;gap:14px;}
    .form-grid-4{display:grid;grid-template-columns:1fr 1fr 1fr 1fr;gap:14px;}
    @media(max-width:900px){.form-grid-2,.form-grid-3,.form-grid-4{grid-template-columns:1fr 1fr;}}

    .snap-row{display:grid;grid-template-columns:repeat(4,1fr);gap:8px;margin-bottom:10px;}
    .snap-box{background:#f8fafc;border:1px solid #e5e7eb;border-radius:8px;padding:10px;text-align:center;border-left:3px solid var(--c, #94a3b8);}
    .snap-box .lbl{font-size:10px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.04em;}
    .snap-box .val{font-size:20px;font-weight:700;color:var(--c, #1f2937);line-height:1;margin-top:3px;}
    @media(max-width:768px){.snap-row{grid-template-columns:1fr 1fr;}}
</style>

<?php $r = $rac ?? []; ?>

<div class="content">
    <div class="rac-form-page">
        <ol class="breadcrumb" style="background:#fff;">
            <li><a href="<?php echo base_url('gestao_corporativa/intranet'); ?>"><i class="fa fa-home"></i> Home</a></li>
            <li><a href="<?php echo base_url('gestao_corporativa/Rac'); ?>">RAC</a></li>
            <li class="active"><?php echo !empty($r) ? 'Editar' : 'Nova'; ?></li>
        </ol>

        <?php echo form_open('gestao_corporativa/Rac/save'); ?>
        <?php if (!empty($r)): ?><input type="hidden" name="id" value="<?php echo (int) $r['id']; ?>"><?php endif; ?>

        <div class="form-card">
            <div class="form-card-header">Identificação</div>
            <div class="form-card-body">
                <div class="form-grid-3">
                    <div class="form-group">
                        <label class="control-label">Código</label>
                        <input type="text" name="codigo" class="form-control" value="<?php echo html_escape($r['codigo'] ?? ''); ?>" placeholder="auto">
                    </div>
                    <div class="form-group" style="grid-column:span 2;">
                        <label class="control-label">Título <span class="text-danger">*</span></label>
                        <input type="text" name="titulo" class="form-control" required value="<?php echo html_escape($r['titulo'] ?? ('RAC ' . date('Y') . ' - ' . date('m'))); ?>">
                    </div>
                </div>
                <div class="form-grid-4">
                    <div class="form-group">
                        <label class="control-label">Período (início)</label>
                        <input type="date" name="periodo_inicio" class="form-control" required value="<?php echo html_escape($r['periodo_inicio'] ?? date('Y-01-01')); ?>">
                    </div>
                    <div class="form-group">
                        <label class="control-label">Período (fim)</label>
                        <input type="date" name="periodo_fim" class="form-control" required value="<?php echo html_escape($r['periodo_fim'] ?? date('Y-m-d')); ?>">
                    </div>
                    <div class="form-group">
                        <label class="control-label">Data realização</label>
                        <input type="date" name="dt_realizacao" class="form-control" value="<?php echo html_escape($r['dt_realizacao'] ?? ''); ?>">
                    </div>
                    <div class="form-group">
                        <label class="control-label">Status</label>
                        <select name="status" class="form-control">
                            <?php foreach ($this->Rac_model->get_statuses() as $s): ?>
                                <option value="<?php echo $s; ?>" <?php echo ($r['status'] ?? 'planejada') === $s ? 'selected' : ''; ?>><?php echo $this->Rac_model->get_status_label($s); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="form-grid-4">
                    <div class="form-group">
                        <label class="control-label">Presidida por</label>
                        <select name="presidida_por_id" class="form-control select2">
                            <option value="">— selecionar —</option>
                            <?php foreach ($staffs as $s): ?>
                                <option value="<?php echo (int) $s['staffid']; ?>" <?php echo (int) ($r['presidida_por_id'] ?? 0) === (int) $s['staffid'] ? 'selected' : ''; ?>><?php echo html_escape($s['firstname'] . ' ' . $s['lastname']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Secretário(a)</label>
                        <select name="secretario_id" class="form-control select2">
                            <option value="">— selecionar —</option>
                            <?php foreach ($staffs as $s): ?>
                                <option value="<?php echo (int) $s['staffid']; ?>" <?php echo (int) ($r['secretario_id'] ?? 0) === (int) $s['staffid'] ? 'selected' : ''; ?>><?php echo html_escape($s['firstname'] . ' ' . $s['lastname']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Modalidade</label>
                        <select name="modalidade" class="form-control">
                            <?php foreach ($this->Rac_model->get_modalidades() as $m): ?>
                                <option value="<?php echo $m; ?>" <?php echo ($r['modalidade'] ?? 'presencial') === $m ? 'selected' : ''; ?>><?php echo $this->Rac_model->get_modalidade_label($m); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Local / link</label>
                        <input type="text" name="local" class="form-control" value="<?php echo html_escape($r['local'] ?? ''); ?>">
                    </div>
                </div>
                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="control-label">Projeto vinculado <small class="text-muted">(opcional — RAC pode ser global ou por projeto)</small></label>
                        <select name="project_id" class="form-control select2">
                            <option value="">— RAC global —</option>
                            <?php foreach ($projects as $p): ?>
                                <option value="<?php echo (int) $p['id']; ?>" <?php echo (int) ($r['project_id'] ?? 0) === (int) $p['id'] ? 'selected' : ''; ?>><?php echo html_escape($p['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Data próxima RAC</label>
                        <input type="date" name="proxima_rac" class="form-control" value="<?php echo html_escape($r['proxima_rac'] ?? ''); ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label">Escopo da análise</label>
                    <textarea name="escopo" class="form-control" rows="2"><?php echo html_escape($r['escopo'] ?? ''); ?></textarea>
                </div>

                <div class="form-group">
                    <label class="control-label">Participantes do comitê</label>
                    <select name="participantes[]" id="participantes" class="form-control" multiple>
                        <?php $sel = array_column($participantes ?? [], 'staff_id'); ?>
                        <?php foreach ($staffs as $s): $sid = (int) $s['staffid']; ?>
                            <option value="<?php echo $sid; ?>" <?php echo in_array($sid, $sel) ? 'selected' : ''; ?>><?php echo html_escape($s['firstname'] . ' ' . $s['lastname']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>

        <?php if ($snapshot): ?>
            <div class="form-card">
                <div class="form-card-header">📊 Snapshot do período <small style="color:#94a3b8;font-weight:400;">(referência pra análise)</small></div>
                <div class="form-card-body">
                    <div class="snap-row">
                        <div class="snap-box" style="--c:#dc2626;"><div class="lbl">Indicadores críticos</div><div class="val"><?php echo (int) $snapshot['indicadores_critico']; ?></div></div>
                        <div class="snap-box" style="--c:#f59e0b;"><div class="lbl">Indicadores em atenção</div><div class="val"><?php echo (int) $snapshot['indicadores_atencao']; ?></div></div>
                        <div class="snap-box" style="--c:#16a34a;"><div class="lbl">Indicadores OK</div><div class="val"><?php echo (int) $snapshot['indicadores_atingido']; ?></div></div>
                        <div class="snap-box"><div class="lbl">Total indicadores</div><div class="val"><?php echo (int) $snapshot['indicadores_total']; ?></div></div>
                    </div>
                    <div class="snap-row">
                        <div class="snap-box" style="--c:#dc2626;"><div class="lbl">Riscos críticos</div><div class="val"><?php echo (int) $snapshot['riscos_criticos']; ?></div></div>
                        <div class="snap-box" style="--c:#f59e0b;"><div class="lbl">Auditorias com NC</div><div class="val"><?php echo (int) $snapshot['auditorias_ncs']; ?></div></div>
                        <div class="snap-box" style="--c:#0a66c2;"><div class="lbl">Ocorrências no período</div><div class="val"><?php echo (int) $snapshot['ocorrencias_total']; ?></div></div>
                        <div class="snap-box" style="--c:#dc2626;"><div class="lbl">Planos atrasados</div><div class="val"><?php echo (int) $snapshot['planos_atrasados']; ?></div></div>
                    </div>
                    <div class="snap-row">
                        <div class="snap-box" style="--c:#16a34a;"><div class="lbl">Treinamentos realizados</div><div class="val"><?php echo (int) $snapshot['treinamentos_realizados']; ?></div></div>
                        <div class="snap-box" style="--c:#0d9488;"><div class="lbl">Documentos publicados</div><div class="val"><?php echo (int) $snapshot['docs_publicados']; ?></div></div>
                        <div class="snap-box"><div class="lbl">Ocorrências abertas</div><div class="val"><?php echo (int) $snapshot['ocorrencias_abertas']; ?></div></div>
                        <div class="snap-box"><div class="lbl">NCs abertas</div><div class="val"><?php echo (int) $snapshot['auditorias_ncs_abertas']; ?></div></div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <div class="form-card">
            <div class="form-card-header">Análise crítica (registre as discussões)</div>
            <div class="form-card-body">
                <div class="form-group">
                    <label class="control-label">📈 Indicadores e desempenho</label>
                    <textarea id="ai_ind" name="analise_indicadores"><?php echo $r['analise_indicadores'] ?? ''; ?></textarea>
                </div>
                <div class="form-group">
                    <label class="control-label">⚠️ Análise dos riscos identificados</label>
                    <textarea id="ai_risc" name="analise_riscos"><?php echo $r['analise_riscos'] ?? ''; ?></textarea>
                </div>
                <div class="form-group">
                    <label class="control-label">🔍 Resultados das auditorias internas</label>
                    <textarea id="ai_aud" name="analise_auditorias"><?php echo $r['analise_auditorias'] ?? ''; ?></textarea>
                </div>
                <div class="form-group">
                    <label class="control-label">🚩 Não-conformidades e ocorrências</label>
                    <textarea id="ai_oc" name="analise_ocorrencias"><?php echo $r['analise_ocorrencias'] ?? ''; ?></textarea>
                </div>
                <div class="form-group">
                    <label class="control-label">😊 Satisfação dos clientes/cooperados</label>
                    <textarea id="ai_sat" name="analise_satisfacao"><?php echo $r['analise_satisfacao'] ?? ''; ?></textarea>
                </div>
                <div class="form-group">
                    <label class="control-label">💡 Oportunidades de melhoria</label>
                    <textarea id="ai_op" name="analise_oportunidades"><?php echo $r['analise_oportunidades'] ?? ''; ?></textarea>
                </div>
            </div>
        </div>

        <div class="form-card">
            <div class="form-card-header">Saídas da reunião</div>
            <div class="form-card-body">
                <div class="form-group">
                    <label class="control-label">🎯 Decisões estratégicas</label>
                    <textarea id="ai_dec" name="decisoes_estrategicas"><?php echo $r['decisoes_estrategicas'] ?? ''; ?></textarea>
                </div>
                <div class="form-group">
                    <label class="control-label">💰 Recursos necessários</label>
                    <textarea id="ai_rec" name="recursos_necessarios"><?php echo $r['recursos_necessarios'] ?? ''; ?></textarea>
                </div>
            </div>
        </div>

        <div class="form-card">
            <div class="form-card-body" style="text-align:right;">
                <a href="<?php echo base_url('gestao_corporativa/Rac'); ?>" class="btn btn-default">Cancelar</a>
                <button type="submit" class="btn btn-info"><i class="fa fa-save"></i> Salvar</button>
            </div>
        </div>

        <?php echo form_close(); ?>
    </div>
</div>

<?php init_tail(); ?>
<script src="<?php echo base_url() ?>assets/lte/plugins/select2/js/select2.full.min.js"></script>
<script src="<?php echo base_url() ?>assets/lte/plugins/summernote/summernote-bs4.min.js"></script>
<script src="<?php echo base_url() ?>assets/lte/plugins/summernote/lang/summernote-pt-BR.min.js"></script>
<script>
$(function () {
    $('.select2').select2({width:'100%'});
    $('#participantes').select2({width:'100%', placeholder:'Selecionar membros do comitê…'});
    $('#ai_ind, #ai_risc, #ai_aud, #ai_oc, #ai_sat, #ai_op, #ai_dec, #ai_rec').summernote({
        lang:'pt-BR', height:120,
        toolbar:[['style',['bold','italic']],['para',['ul','ol']],['insert',['link']]]
    });
});
</script>
</body>
</html>
