<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head_intranet_novo(false); ?>
<?php $this->load->view('gestao_corporativa/css_background'); ?>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/lte/plugins/select2/css/select2.min.css">
<link rel="stylesheet" href="<?php echo base_url() ?>assets/lte/plugins/summernote/summernote-bs4.min.css">

<style>
    .tr-form-page{max-width:1100px;margin:24px auto;padding:0 18px;}
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
$t = $treinamento ?? [];
$pre = $preencher ?? [];
$g = function ($k, $def = '') use ($t, $pre) {
    return html_escape($t[$k] ?? $pre[$k] ?? $def);
};
$gi = function ($k) use ($t, $pre) {
    return (int) ($t[$k] ?? $pre[$k] ?? 0);
};
?>

<div class="content">
    <div class="tr-form-page">
        <ol class="breadcrumb" style="background:#fff;">
            <li><a href="<?php echo base_url('gestao_corporativa/intranet'); ?>"><i class="fa fa-home"></i> Home</a></li>
            <li><a href="<?php echo base_url('gestao_corporativa/Treinamento'); ?>">Treinamentos</a></li>
            <li class="active"><?php echo !empty($t) ? 'Editar' : 'Novo'; ?></li>
        </ol>

        <?php echo form_open('gestao_corporativa/Treinamento/save'); ?>
        <?php if (!empty($t)): ?><input type="hidden" name="id" value="<?php echo (int) $t['id']; ?>"><?php endif; ?>

        <div class="form-card">
            <div class="form-card-header">Dados do treinamento</div>
            <div class="form-card-body">
                <div class="form-grid-3">
                    <div class="form-group">
                        <label class="control-label">Código <small class="text-muted">(auto)</small></label>
                        <input type="text" name="codigo" class="form-control" value="<?php echo $g('codigo'); ?>" placeholder="ex: TRN-0001">
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
                <div class="form-group">
                    <label class="control-label">Conteúdo programático</label>
                    <textarea id="conteudo_programatico" name="conteudo_programatico"><?php echo $t['conteudo_programatico'] ?? ''; ?></textarea>
                </div>
            </div>
        </div>

        <div class="form-card">
            <div class="form-card-header">Quando, onde e quem dá</div>
            <div class="form-card-body">
                <div class="form-grid-4">
                    <div class="form-group">
                        <label class="control-label">Modalidade</label>
                        <select name="modalidade" class="form-control">
                            <?php foreach ($this->Treinamento_model->get_modalidades() as $m): ?>
                                <option value="<?php echo $m; ?>" <?php echo ($t['modalidade'] ?? 'presencial') === $m ? 'selected' : ''; ?>><?php echo $this->Treinamento_model->get_modalidade_label($m); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Carga horária (h)</label>
                        <input type="text" name="carga_horaria" class="form-control" value="<?php echo $g('carga_horaria'); ?>" placeholder="ex: 4">
                    </div>
                    <div class="form-group">
                        <label class="control-label">Início</label>
                        <input type="datetime-local" name="dt_inicio" class="form-control" value="<?php echo !empty($t['dt_inicio']) ? date('Y-m-d\TH:i', strtotime($t['dt_inicio'])) : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label class="control-label">Fim</label>
                        <input type="datetime-local" name="dt_fim" class="form-control" value="<?php echo !empty($t['dt_fim']) ? date('Y-m-d\TH:i', strtotime($t['dt_fim'])) : ''; ?>">
                    </div>
                </div>
                <div class="form-grid-3">
                    <div class="form-group">
                        <label class="control-label">Local <small class="text-muted">(presencial) / link (online)</small></label>
                        <input type="text" name="local" class="form-control" value="<?php echo $g('local'); ?>">
                    </div>
                    <div class="form-group">
                        <label class="control-label">Instrutor (interno)</label>
                        <select name="instrutor_staff_id" class="form-control select2">
                            <option value="">— ninguém —</option>
                            <?php foreach ($staffs as $s): ?>
                                <option value="<?php echo (int) $s['staffid']; ?>" <?php echo (int) ($t['instrutor_staff_id'] ?? 0) === (int) $s['staffid'] ? 'selected' : ''; ?>><?php echo html_escape($s['firstname'] . ' ' . $s['lastname']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Instrutor externo <small class="text-muted">(se for o caso)</small></label>
                        <input type="text" name="instrutor_externo" class="form-control" value="<?php echo $g('instrutor_externo'); ?>" placeholder="nome / empresa">
                    </div>
                </div>
            </div>
        </div>

        <div class="form-card">
            <div class="form-card-header">Vínculos</div>
            <div class="form-card-body">
                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="control-label">Documento (POP) relacionado</label>
                        <select name="documento_id" class="form-control select2">
                            <option value="">— sem vínculo —</option>
                            <?php foreach ($documentos as $d): ?>
                                <option value="<?php echo (int) $d['id']; ?>" <?php echo $gi('documento_id') === (int) $d['id'] ? 'selected' : ''; ?>><?php echo html_escape($d['codigo'] . ' — ' . $d['titulo']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Plano de Ação</label>
                        <select name="plano_id" class="form-control select2">
                            <option value="">— sem vínculo —</option>
                            <?php foreach ($planos as $p): ?>
                                <option value="<?php echo (int) $p['id']; ?>" <?php echo $gi('plano_id') === (int) $p['id'] ? 'selected' : ''; ?>><?php echo html_escape($p['titulo']); ?></option>
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
            <div class="form-card-header">Avaliação e participantes</div>
            <div class="form-card-body">
                <div class="form-grid-3">
                    <div class="form-group">
                        <label class="control-label">Status</label>
                        <select name="status" class="form-control">
                            <?php foreach ($this->Treinamento_model->get_statuses() as $s): ?>
                                <option value="<?php echo $s; ?>" <?php echo ($t['status'] ?? 'planejado') === $s ? 'selected' : ''; ?>><?php echo $this->Treinamento_model->get_status_label($s); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Nota mínima p/ aprovar</label>
                        <input type="text" name="nota_minima" class="form-control" value="<?php echo $g('nota_minima'); ?>" placeholder="ex: 7">
                    </div>
                    <div class="form-group">
                        <label class="control-label">&nbsp;</label>
                        <label style="display:flex;align-items:center;gap:6px;font-weight:400;margin-top:8px;">
                            <input type="checkbox" name="emite_certificado" value="1" <?php echo !empty($t['emite_certificado']) ? 'checked' : ''; ?>> Emite certificado
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label">Participantes</label>
                    <select name="participantes[]" id="participantes" class="form-control" multiple>
                        <?php $sel_part = array_column($participantes ?? [], 'staff_id'); ?>
                        <?php foreach ($staffs as $s):
                            $sid = (int) $s['staffid'];
                        ?>
                            <option value="<?php echo $sid; ?>" <?php echo in_array($sid, $sel_part) ? 'selected' : ''; ?>><?php echo html_escape($s['firstname'] . ' ' . $s['lastname']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>

        <div class="form-card">
            <div class="form-card-body" style="text-align:right;">
                <a href="<?php echo base_url('gestao_corporativa/Treinamento'); ?>" class="btn btn-default">Cancelar</a>
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
    $('#participantes').select2({width:'100%', placeholder:'Selecionar pessoas…'});
    $('#conteudo_programatico').summernote({
        lang:'pt-BR', height:200,
        toolbar:[['style',['style']],['font',['bold','italic','underline']],['para',['ul','ol','paragraph']],['table',['table']],['insert',['link']]]
    });
    $('#project_id').on('change', function () {
        var pid = $(this).val();
        var $f = $('#fase_id');
        $f.empty().append('<option value="">— sem fase —</option>');
        if (pid) {
            $.getJSON('<?php echo base_url('gestao_corporativa/Projeto_fase/list_options'); ?>/' + pid, function (data) {
                (data || []).forEach(function (f) { $f.append($('<option>').val(f.id).text(f.codigo_sequencial + ' ' + f.titulo)); });
                $f.trigger('change.select2');
            });
        } else { $f.trigger('change.select2'); }
    });
});
</script>
</body>
</html>
