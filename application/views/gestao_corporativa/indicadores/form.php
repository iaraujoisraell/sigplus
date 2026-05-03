<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head_intranet_novo(false); ?>
<?php $this->load->view('gestao_corporativa/css_background'); ?>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/lte/plugins/select2/css/select2.min.css">

<style>
    .ind-form-page{max-width:980px;margin:24px auto;padding:0 18px;}
    .form-card{background:#fff;border:1px solid #e5e7eb;border-radius:10px;margin-bottom:14px;}
    .form-card-header{padding:12px 18px;border-bottom:1px solid #eef1f4;font-weight:600;color:#1f2937;font-size:14px;}
    .form-card-body{padding:18px;}
    .form-grid-2{display:grid;grid-template-columns:1fr 1fr;gap:14px;}
    .form-grid-3{display:grid;grid-template-columns:1fr 1fr 1fr;gap:14px;}
    .form-grid-4{display:grid;grid-template-columns:1fr 1fr 1fr 1fr;gap:14px;}
    @media(max-width:900px){.form-grid-2,.form-grid-3,.form-grid-4{grid-template-columns:1fr 1fr;}}
    @media(max-width:600px){.form-grid-2,.form-grid-3,.form-grid-4{grid-template-columns:1fr;}}
</style>

<div class="content">
    <div class="ind-form-page">
        <ol class="breadcrumb" style="background:#fff;">
            <li><a href="<?php echo base_url('gestao_corporativa/intranet'); ?>"><i class="fa fa-home"></i> Home</a></li>
            <li><a href="<?php echo base_url('gestao_corporativa/Indicador'); ?>">Indicadores</a></li>
            <li class="active"><?php echo !empty($indicador) ? 'Editar' : 'Novo'; ?></li>
        </ol>

        <?php echo form_open('gestao_corporativa/Indicador/save'); ?>
        <?php if (!empty($indicador)): ?><input type="hidden" name="id" value="<?php echo (int) $indicador['id']; ?>"><?php endif; ?>

        <div class="form-card">
            <div class="form-card-header">Identificação</div>
            <div class="form-card-body">
                <div class="form-grid-3">
                    <div class="form-group">
                        <label class="control-label">Código <small class="text-muted">(auto)</small></label>
                        <input type="text" name="codigo" class="form-control" value="<?php echo html_escape($indicador['codigo'] ?? ''); ?>" placeholder="ex: IND-001">
                    </div>
                    <div class="form-group" style="grid-column:span 2;">
                        <label class="control-label">Nome <span class="text-danger">*</span></label>
                        <input type="text" name="nome" class="form-control" required maxlength="250" value="<?php echo html_escape($indicador['nome'] ?? ''); ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label">Descrição</label>
                    <textarea name="descricao" class="form-control" rows="2"><?php echo html_escape($indicador['descricao'] ?? ''); ?></textarea>
                </div>
                <div class="form-group">
                    <label class="control-label">Fórmula de cálculo <small class="text-muted">(opcional, descritivo)</small></label>
                    <input type="text" name="formula" class="form-control" value="<?php echo html_escape($indicador['formula'] ?? ''); ?>" placeholder="ex: (atendidos no prazo / total) * 100">
                </div>
            </div>
        </div>

        <div class="form-card">
            <div class="form-card-header">Meta e medição</div>
            <div class="form-card-body">
                <div class="form-grid-4">
                    <div class="form-group">
                        <label class="control-label">Unidade</label>
                        <input type="text" name="unidade" class="form-control" maxlength="20" value="<?php echo html_escape($indicador['unidade'] ?? ''); ?>" placeholder="%, dias, R$, n…">
                    </div>
                    <div class="form-group">
                        <label class="control-label">Casas decimais</label>
                        <input type="number" name="casas_decimais" class="form-control" min="0" max="6" value="<?php echo (int) ($indicador['casas_decimais'] ?? 2); ?>">
                    </div>
                    <div class="form-group">
                        <label class="control-label">Periodicidade</label>
                        <select name="periodicidade" class="form-control">
                            <?php foreach ($this->Indicador_model->get_periodicidades() as $p): ?>
                                <option value="<?php echo $p; ?>" <?php echo ($indicador['periodicidade'] ?? 'mensal') === $p ? 'selected' : ''; ?>><?php echo $this->Indicador_model->get_periodicidade_label($p); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Status</label>
                        <select name="status" class="form-control">
                            <?php foreach ($this->Indicador_model->get_statuses() as $s): ?>
                                <option value="<?php echo $s; ?>" <?php echo ($indicador['status'] ?? 'ativo') === $s ? 'selected' : ''; ?>><?php echo $this->Indicador_model->get_status_label($s); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-grid-3">
                    <div class="form-group">
                        <label class="control-label">Meta (valor numérico)</label>
                        <input type="text" name="meta" class="form-control" value="<?php echo html_escape($indicador['meta'] ?? ''); ?>" placeholder="ex: 95">
                    </div>
                    <div class="form-group">
                        <label class="control-label">Tolerância (margem amarela)</label>
                        <input type="text" name="tolerancia" class="form-control" value="<?php echo html_escape($indicador['tolerancia'] ?? ''); ?>" placeholder="ex: 5">
                    </div>
                    <div class="form-group">
                        <label class="control-label">Tipo de meta</label>
                        <select name="meta_tipo" class="form-control">
                            <?php foreach ($this->Indicador_model->get_meta_tipos() as $t): ?>
                                <option value="<?php echo $t; ?>" <?php echo ($indicador['meta_tipo'] ?? 'maior_melhor') === $t ? 'selected' : ''; ?>><?php echo $this->Indicador_model->get_meta_tipo_label($t); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
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
                                <option value="<?php echo (int) $d['departmentid']; ?>" <?php echo (int) ($indicador['setor_id'] ?? 0) === (int) $d['departmentid'] ? 'selected' : ''; ?>><?php echo html_escape($d['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Responsável</label>
                        <select name="responsavel_id" class="form-control select2">
                            <option value="">— selecionar —</option>
                            <?php foreach ($staffs as $s): ?>
                                <option value="<?php echo (int) $s['staffid']; ?>" <?php echo (int) ($indicador['responsavel_id'] ?? 0) === (int) $s['staffid'] ? 'selected' : ''; ?>><?php echo html_escape($s['firstname'] . ' ' . $s['lastname']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Cor</label>
                        <input type="color" name="cor" class="form-control" value="<?php echo html_escape($indicador['cor'] ?? '#0a66c2'); ?>" style="height:38px;padding:2px;">
                    </div>
                </div>
                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="control-label">Projeto vinculado <small class="text-muted">(opcional)</small></label>
                        <select name="project_id" id="project_id" class="form-control select2">
                            <option value="">— sem projeto —</option>
                            <?php foreach ($projects as $p): ?>
                                <option value="<?php echo (int) $p['id']; ?>" <?php echo (int) ($indicador['project_id'] ?? 0) === (int) $p['id'] ? 'selected' : ''; ?>><?php echo html_escape($p['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Fase</label>
                        <select name="fase_id" id="fase_id" class="form-control select2" data-current="<?php echo (int) ($indicador['fase_id'] ?? 0); ?>">
                            <option value="">— sem fase —</option>
                            <?php
                            $fases_ini = !empty($indicador['project_id']) ? $this->Projeto_fase_model->list_options($indicador['project_id']) : [];
                            foreach ($fases_ini as $fa): ?>
                                <option value="<?php echo (int) $fa['id']; ?>" <?php echo (int) ($indicador['fase_id'] ?? 0) === (int) $fa['id'] ? 'selected' : ''; ?>><?php echo html_escape($fa['codigo_sequencial'] . ' ' . $fa['titulo']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-card">
            <div class="form-card-body" style="text-align:right;">
                <a href="<?php echo base_url('gestao_corporativa/Indicador'); ?>" class="btn btn-default">Cancelar</a>
                <button type="submit" class="btn btn-info"><i class="fa fa-save"></i> Salvar</button>
            </div>
        </div>

        <?php echo form_close(); ?>
    </div>
</div>

<?php init_tail(); ?>
<script src="<?php echo base_url() ?>assets/lte/plugins/select2/js/select2.full.min.js"></script>
<script>
$(function(){
    $('.select2').select2({width:'100%'});
    $('#project_id').on('change', function () {
        var pid = $(this).val();
        var $f = $('#fase_id');
        $f.empty().append('<option value="">— sem fase —</option>');
        if (pid) {
            $.getJSON('<?php echo base_url('gestao_corporativa/Projeto_fase/list_options'); ?>/' + pid, function (data) {
                (data || []).forEach(function (f) {
                    $f.append($('<option>').val(f.id).text(f.codigo_sequencial + ' ' + f.titulo));
                });
                $f.trigger('change.select2');
            });
        } else { $f.trigger('change.select2'); }
    });
});
</script>
</body>
</html>
