<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head_intranet_novo(false); ?>
<?php $this->load->view('gestao_corporativa/css_background'); ?>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/lte/plugins/select2/css/select2.min.css">

<style>
    .au-form-page{max-width:1100px;margin:24px auto;padding:0 18px;}
    .form-card{background:#fff;border:1px solid #e5e7eb;border-radius:10px;margin-bottom:14px;}
    .form-card-header{padding:12px 18px;border-bottom:1px solid #eef1f4;font-weight:600;color:#1f2937;font-size:14px;}
    .form-card-body{padding:18px;}
    .form-grid-2{display:grid;grid-template-columns:1fr 1fr;gap:14px;}
    .form-grid-3{display:grid;grid-template-columns:1fr 1fr 1fr;gap:14px;}
    .form-grid-4{display:grid;grid-template-columns:1fr 1fr 1fr 1fr;gap:14px;}
    @media(max-width:900px){.form-grid-2,.form-grid-3,.form-grid-4{grid-template-columns:1fr 1fr;}}
    @media(max-width:600px){.form-grid-2,.form-grid-3,.form-grid-4{grid-template-columns:1fr;}}
</style>

<?php
$a = $auditoria ?? [];
$pre = $preencher ?? [];
$g = function ($k, $def = '') use ($a, $pre) { return html_escape($a[$k] ?? $pre[$k] ?? $def); };
$gi = function ($k) use ($a, $pre) { return (int) ($a[$k] ?? $pre[$k] ?? 0); };
?>

<div class="content">
    <div class="au-form-page">
        <ol class="breadcrumb" style="background:#fff;">
            <li><a href="<?php echo base_url('gestao_corporativa/intranet'); ?>"><i class="fa fa-home"></i> Home</a></li>
            <li><a href="<?php echo base_url('gestao_corporativa/Auditoria'); ?>">Auditorias</a></li>
            <li class="active"><?php echo !empty($a) ? 'Editar' : 'Nova'; ?></li>
        </ol>

        <?php echo form_open('gestao_corporativa/Auditoria/save'); ?>
        <?php if (!empty($a)): ?><input type="hidden" name="id" value="<?php echo (int) $a['id']; ?>"><?php endif; ?>

        <div class="form-card">
            <div class="form-card-header">Identificação</div>
            <div class="form-card-body">
                <div class="form-grid-3">
                    <div class="form-group">
                        <label class="control-label">Código <small class="text-muted">(auto)</small></label>
                        <input type="text" name="codigo" class="form-control" value="<?php echo $g('codigo'); ?>">
                    </div>
                    <div class="form-group" style="grid-column:span 2;">
                        <label class="control-label">Título <span class="text-danger">*</span></label>
                        <input type="text" name="titulo" class="form-control" required maxlength="250" value="<?php echo $g('titulo'); ?>">
                    </div>
                </div>
                <div class="form-grid-3">
                    <div class="form-group">
                        <label class="control-label">Tipo</label>
                        <select name="tipo" class="form-control">
                            <?php foreach ($this->Auditoria_model->get_tipos() as $t): ?>
                                <option value="<?php echo $t; ?>" <?php echo ($a['tipo'] ?? 'interna') === $t ? 'selected' : ''; ?>><?php echo $this->Auditoria_model->get_tipo_label($t); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Status</label>
                        <select name="status" class="form-control">
                            <?php foreach ($this->Auditoria_model->get_statuses() as $s): ?>
                                <option value="<?php echo $s; ?>" <?php echo ($a['status'] ?? 'planejada') === $s ? 'selected' : ''; ?>><?php echo $this->Auditoria_model->get_status_label($s); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Resultado</label>
                        <select name="resultado" class="form-control">
                            <?php foreach ($this->Auditoria_model->get_resultados() as $r): ?>
                                <option value="<?php echo $r; ?>" <?php echo ($a['resultado'] ?? 'pendente') === $r ? 'selected' : ''; ?>><?php echo $this->Auditoria_model->get_resultado_label($r); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label">Descrição</label>
                    <textarea name="descricao" class="form-control" rows="2"><?php echo $g('descricao'); ?></textarea>
                </div>
                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="control-label">Escopo (o que será auditado)</label>
                        <textarea name="escopo" class="form-control" rows="3"><?php echo $g('escopo'); ?></textarea>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Norma de referência</label>
                        <input type="text" name="norma_referencia" class="form-control" value="<?php echo $g('norma_referencia'); ?>" placeholder="ex: ONA Nível 2 / ISO 9001:2015 / RDC 36">
                    </div>
                </div>
            </div>
        </div>

        <div class="form-card">
            <div class="form-card-header">Quando, quem, onde</div>
            <div class="form-card-body">
                <div class="form-grid-4">
                    <div class="form-group">
                        <label class="control-label">Data planejada</label>
                        <input type="date" name="dt_planejada" class="form-control" value="<?php echo html_escape($a['dt_planejada'] ?? ''); ?>">
                    </div>
                    <div class="form-group">
                        <label class="control-label">Data realizada</label>
                        <input type="date" name="dt_realizada" class="form-control" value="<?php echo html_escape($a['dt_realizada'] ?? ''); ?>">
                    </div>
                    <div class="form-group">
                        <label class="control-label">Duração (horas)</label>
                        <input type="text" name="duracao_horas" class="form-control" value="<?php echo $g('duracao_horas'); ?>" placeholder="ex: 4">
                    </div>
                    <div class="form-group">
                        <label class="control-label">Auditor líder</label>
                        <select name="auditor_lider_id" class="form-control select2">
                            <option value="">— selecionar —</option>
                            <?php foreach ($staffs as $s): ?>
                                <option value="<?php echo (int) $s['staffid']; ?>" <?php echo $gi('auditor_lider_id') === (int) $s['staffid'] ? 'selected' : ''; ?>><?php echo html_escape($s['firstname'] . ' ' . $s['lastname']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="control-label">Setor auditado</label>
                        <select name="setor_auditado_id" class="form-control select2">
                            <option value="">— sem setor —</option>
                            <?php foreach ($departments as $d): ?>
                                <option value="<?php echo (int) $d['departmentid']; ?>" <?php echo $gi('setor_auditado_id') === (int) $d['departmentid'] ? 'selected' : ''; ?>><?php echo html_escape($d['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Auditados (pessoas entrevistadas)</label>
                        <select name="auditados[]" id="auditados" class="form-control" multiple>
                            <?php $sel = array_column($auditados ?? [], 'staff_id'); ?>
                            <?php foreach ($staffs as $s): $sid = (int) $s['staffid']; ?>
                                <option value="<?php echo $sid; ?>" <?php echo in_array($sid, $sel) ? 'selected' : ''; ?>><?php echo html_escape($s['firstname'] . ' ' . $s['lastname']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-card">
            <div class="form-card-header">Documento + Checklist + Vínculos</div>
            <div class="form-card-body">
                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="control-label">Documento auditado (POP)</label>
                        <select name="documento_referencia_id" class="form-control select2">
                            <option value="">— sem POP específico —</option>
                            <?php foreach ($documentos as $d): ?>
                                <option value="<?php echo (int) $d['id']; ?>" <?php echo $gi('documento_referencia_id') === (int) $d['id'] ? 'selected' : ''; ?>><?php echo html_escape($d['codigo'] . ' — ' . $d['titulo']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Checklist usado (Formulário)</label>
                        <select name="formulario_id" class="form-control select2">
                            <option value="">— sem checklist —</option>
                            <?php foreach ($formularios as $f): ?>
                                <option value="<?php echo (int) $f['id']; ?>" <?php echo $gi('formulario_id') === (int) $f['id'] ? 'selected' : ''; ?>><?php echo html_escape($f['titulo']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="form-grid-3">
                    <div class="form-group">
                        <label class="control-label">Projeto</label>
                        <select name="project_id" id="project_id" class="form-control select2">
                            <option value="">— sem projeto —</option>
                            <?php foreach ($projects as $p): ?>
                                <option value="<?php echo (int) $p['id']; ?>" <?php echo $gi('project_id') === (int) $p['id'] ? 'selected' : ''; ?>><?php echo html_escape($p['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Plano de Ação</label>
                        <select name="plano_id" class="form-control select2">
                            <option value="">— sem plano —</option>
                            <?php foreach ($planos as $pl): ?>
                                <option value="<?php echo (int) $pl['id']; ?>" <?php echo $gi('plano_id') === (int) $pl['id'] ? 'selected' : ''; ?>><?php echo html_escape($pl['titulo']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Grupo</label>
                        <select name="grupo_id" class="form-control select2">
                            <option value="">— sem grupo —</option>
                            <?php foreach ($grupos as $gr): ?>
                                <option value="<?php echo (int) $gr['id']; ?>" <?php echo $gi('grupo_id') === (int) $gr['id'] ? 'selected' : ''; ?>><?php echo html_escape($gr['titulo']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-card">
            <div class="form-card-body" style="text-align:right;">
                <a href="<?php echo base_url('gestao_corporativa/Auditoria'); ?>" class="btn btn-default">Cancelar</a>
                <button type="submit" class="btn btn-info"><i class="fa fa-save"></i> Salvar</button>
            </div>
        </div>

        <?php echo form_close(); ?>
    </div>
</div>

<?php init_tail(); ?>
<script src="<?php echo base_url() ?>assets/lte/plugins/select2/js/select2.full.min.js"></script>
<script>
$(function () {
    $('.select2').select2({width:'100%'});
    $('#auditados').select2({width:'100%', placeholder:'Selecionar pessoas…'});
});
</script>
</body>
</html>
