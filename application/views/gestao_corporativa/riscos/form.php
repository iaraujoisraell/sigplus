<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head_intranet_novo(false); ?>
<?php $this->load->view('gestao_corporativa/css_background'); ?>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/lte/plugins/select2/css/select2.min.css">

<style>
    .rk-form-page{max-width:1100px;margin:24px auto;padding:0 18px;}
    .form-card{background:#fff;border:1px solid #e5e7eb;border-radius:10px;margin-bottom:14px;}
    .form-card-header{padding:12px 18px;border-bottom:1px solid #eef1f4;font-weight:600;color:#1f2937;font-size:14px;}
    .form-card-body{padding:18px;}
    .form-grid-2{display:grid;grid-template-columns:1fr 1fr;gap:14px;}
    .form-grid-3{display:grid;grid-template-columns:1fr 1fr 1fr;gap:14px;}
    @media(max-width:900px){.form-grid-2,.form-grid-3{grid-template-columns:1fr;}}

    .pi-grid{display:grid;grid-template-columns:1fr 1fr 1fr;gap:14px;align-items:end;}
    .pi-preview{background:#f8fafc;border:1px solid #e5e7eb;border-radius:8px;padding:14px;text-align:center;}
    .pi-preview .num{font-size:36px;font-weight:700;line-height:1;}
    .pi-preview .lbl{font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.04em;margin-top:4px;}
</style>

<?php
$r = $risco ?? [];
$pre = $preencher ?? [];
$g = function ($k, $def = '') use ($r, $pre) { return html_escape($r[$k] ?? $pre[$k] ?? $def); };
$gi = function ($k) use ($r, $pre) { return (int) ($r[$k] ?? $pre[$k] ?? 0); };
$model = $this->Risco_model;
$prob_atual = (int) ($r['probabilidade'] ?? 1);
$imp_atual  = (int) ($r['impacto'] ?? 1);
$sev_atual  = $model->calc_severidade($prob_atual, $imp_atual);
$nivel_atual= $model->calc_nivel($sev_atual);
?>

<div class="content">
    <div class="rk-form-page">
        <ol class="breadcrumb" style="background:#fff;">
            <li><a href="<?php echo base_url('gestao_corporativa/intranet'); ?>"><i class="fa fa-home"></i> Home</a></li>
            <li><a href="<?php echo base_url('gestao_corporativa/Risco'); ?>">Riscos</a></li>
            <li class="active"><?php echo !empty($r) ? 'Editar' : 'Novo'; ?></li>
        </ol>

        <?php echo form_open('gestao_corporativa/Risco/save'); ?>
        <?php if (!empty($r)): ?><input type="hidden" name="id" value="<?php echo (int) $r['id']; ?>"><?php endif; ?>

        <div class="form-card">
            <div class="form-card-header">Identificação</div>
            <div class="form-card-body">
                <div class="form-grid-3">
                    <div class="form-group">
                        <label class="control-label">Código <small class="text-muted">(auto)</small></label>
                        <input type="text" name="codigo" class="form-control" value="<?php echo $g('codigo'); ?>" placeholder="ex: RSK-001">
                    </div>
                    <div class="form-group" style="grid-column:span 2;">
                        <label class="control-label">Título <span class="text-danger">*</span></label>
                        <input type="text" name="titulo" class="form-control" required maxlength="250" value="<?php echo $g('titulo'); ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label">Descrição</label>
                    <textarea name="descricao" class="form-control" rows="2"><?php echo $g('descricao'); ?></textarea>
                </div>
                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="control-label">Categoria</label>
                        <select name="categoria" class="form-control">
                            <?php foreach ($model->get_categorias() as $k => $v): ?>
                                <option value="<?php echo $k; ?>" <?php echo ($r['categoria'] ?? 'operacional') === $k ? 'selected' : ''; ?>><?php echo $v; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Status</label>
                        <select name="status" class="form-control">
                            <?php foreach ($model->get_statuses() as $s): ?>
                                <option value="<?php echo $s; ?>" <?php echo ($r['status'] ?? 'identificado') === $s ? 'selected' : ''; ?>><?php echo $model->get_status_label($s); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-card">
            <div class="form-card-header">Análise (causa, consequência, controles)</div>
            <div class="form-card-body">
                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="control-label">Causa</label>
                        <textarea name="causa" class="form-control" rows="3"><?php echo $g('causa'); ?></textarea>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Consequência</label>
                        <textarea name="consequencia" class="form-control" rows="3"><?php echo $g('consequencia'); ?></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label">Controles existentes</label>
                    <textarea name="controles_existentes" class="form-control" rows="2"><?php echo $g('controles_existentes'); ?></textarea>
                </div>
            </div>
        </div>

        <div class="form-card">
            <div class="form-card-header">Avaliação Probabilidade × Impacto</div>
            <div class="form-card-body">
                <div class="pi-grid">
                    <div class="form-group">
                        <label class="control-label">Probabilidade (1-5)</label>
                        <select name="probabilidade" id="prob" class="form-control" onchange="atualizar()">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <option value="<?php echo $i; ?>" <?php echo $prob_atual === $i ? 'selected' : ''; ?>><?php echo $i; ?> — <?php echo $model->get_prob_label($i); ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Impacto (1-5)</label>
                        <select name="impacto" id="imp" class="form-control" onchange="atualizar()">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <option value="<?php echo $i; ?>" <?php echo $imp_atual === $i ? 'selected' : ''; ?>><?php echo $i; ?> — <?php echo $model->get_imp_label($i); ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="pi-preview" id="preview-box" style="background:<?php echo $model->get_nivel_color($nivel_atual); ?>20;border-color:<?php echo $model->get_nivel_color($nivel_atual); ?>;">
                        <div class="num" id="preview-sev" style="color:<?php echo $model->get_nivel_color($nivel_atual); ?>;"><?php echo $sev_atual; ?></div>
                        <div class="lbl" id="preview-nivel" style="color:<?php echo $model->get_nivel_color($nivel_atual); ?>;"><?php echo $model->get_nivel_label($nivel_atual); ?></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-card">
            <div class="form-card-header">Tratamento</div>
            <div class="form-card-body">
                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="control-label">Tipo de tratamento</label>
                        <select name="tipo_tratamento" class="form-control">
                            <?php foreach ($model->get_tratamentos() as $t): ?>
                                <option value="<?php echo $t; ?>" <?php echo ($r['tipo_tratamento'] ?? 'mitigar') === $t ? 'selected' : ''; ?>><?php echo $model->get_tratamento_label($t); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Plano de Ação vinculado</label>
                        <select name="plano_id" class="form-control select2">
                            <option value="">— sem vínculo —</option>
                            <?php foreach ($planos as $pl): ?>
                                <option value="<?php echo (int) $pl['id']; ?>" <?php echo $gi('plano_id') === (int) $pl['id'] ? 'selected' : ''; ?>><?php echo html_escape($pl['titulo']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label">Plano de tratamento (descrição livre)</label>
                    <textarea name="plano_tratamento" class="form-control" rows="3"><?php echo $g('plano_tratamento'); ?></textarea>
                </div>
            </div>
        </div>

        <div class="form-card">
            <div class="form-card-header">Responsabilidade e vínculos</div>
            <div class="form-card-body">
                <div class="form-grid-3">
                    <div class="form-group">
                        <label class="control-label">Setor</label>
                        <select name="setor_id" class="form-control select2">
                            <option value="">— sem setor —</option>
                            <?php foreach ($departments as $d): ?>
                                <option value="<?php echo (int) $d['departmentid']; ?>" <?php echo $gi('setor_id') === (int) $d['departmentid'] ? 'selected' : ''; ?>><?php echo html_escape($d['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Responsável</label>
                        <select name="responsavel_id" class="form-control select2">
                            <option value="">— selecionar —</option>
                            <?php foreach ($staffs as $s): ?>
                                <option value="<?php echo (int) $s['staffid']; ?>" <?php echo $gi('responsavel_id') === (int) $s['staffid'] ? 'selected' : ''; ?>><?php echo html_escape($s['firstname'] . ' ' . $s['lastname']); ?></option>
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
                        <label class="control-label">Fase</label>
                        <select name="fase_id" id="fase_id" class="form-control select2">
                            <option value="">— sem fase —</option>
                            <?php
                            $proj_sel = $gi('project_id');
                            $fases_ini = $proj_sel ? $this->Projeto_fase_model->list_options($proj_sel) : [];
                            foreach ($fases_ini as $fa): ?>
                                <option value="<?php echo (int) $fa['id']; ?>" <?php echo $gi('fase_id') === (int) $fa['id'] ? 'selected' : ''; ?>><?php echo html_escape($fa['codigo_sequencial'] . ' ' . $fa['titulo']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Próxima revisão</label>
                        <input type="date" name="dt_revisao" class="form-control" value="<?php echo html_escape($r['dt_revisao'] ?? ''); ?>">
                    </div>
                </div>
            </div>
        </div>

        <div class="form-card">
            <div class="form-card-body" style="text-align:right;">
                <a href="<?php echo base_url('gestao_corporativa/Risco'); ?>" class="btn btn-default">Cancelar</a>
                <button type="submit" class="btn btn-info"><i class="fa fa-save"></i> Salvar</button>
            </div>
        </div>

        <?php echo form_close(); ?>
    </div>
</div>

<?php init_tail(); ?>
<script src="<?php echo base_url() ?>assets/lte/plugins/select2/js/select2.full.min.js"></script>
<script>
var nivelLabels = {baixo:'Baixo',moderado:'Moderado',alto:'Alto',critico:'Crítico'};
var nivelCores = {baixo:'#16a34a',moderado:'#f59e0b',alto:'#ea580c',critico:'#dc2626'};
function calcNivel(s){if(s>=16)return'critico';if(s>=10)return'alto';if(s>=5)return'moderado';return'baixo';}
function atualizar(){
    var p = parseInt($('#prob').val(),10), i = parseInt($('#imp').val(),10);
    var sev = p * i;
    var nv = calcNivel(sev);
    $('#preview-sev').text(sev).css('color', nivelCores[nv]);
    $('#preview-nivel').text(nivelLabels[nv]).css('color', nivelCores[nv]);
    $('#preview-box').css({background: nivelCores[nv] + '20', borderColor: nivelCores[nv]});
}
$(function(){
    $('.select2').select2({width:'100%'});
    $('#project_id').on('change', function(){
        var pid = $(this).val(); var $f = $('#fase_id');
        $f.empty().append('<option value="">— sem fase —</option>');
        if(pid){
            $.getJSON('<?php echo base_url('gestao_corporativa/Projeto_fase/list_options'); ?>/' + pid, function(data){
                (data || []).forEach(function(f){ $f.append($('<option>').val(f.id).text(f.codigo_sequencial + ' ' + f.titulo)); });
                $f.trigger('change.select2');
            });
        } else { $f.trigger('change.select2'); }
    });
});
</script>
</body>
</html>
