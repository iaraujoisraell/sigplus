<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head_intranet_novo(); ?>
<?php $this->load->view('gestao_corporativa/css_background'); ?>

<link rel="stylesheet" href="<?php echo base_url() ?>assets/lte/plugins/select2/css/select2.min.css">
<link rel="stylesheet" href="<?php echo base_url() ?>assets/lte/plugins/summernote/summernote-bs4.min.css">

<style>
    .ev-form{max-width:1100px;margin:24px auto;padding:0 18px;}
    .form-card{background:#fff;border:1px solid #e5e7eb;border-radius:10px;margin-bottom:14px;}
    .form-card-header{padding:12px 18px;border-bottom:1px solid #eef1f4;font-weight:600;color:#1f2937;font-size:14px;}
    .form-card-body{padding:18px;}
    .form-grid-2{display:grid;grid-template-columns:1fr 1fr;gap:14px;}
    .form-grid-3{display:grid;grid-template-columns:1fr 1fr 1fr;gap:14px;}
    .form-grid-4{display:grid;grid-template-columns:1fr 1fr 1fr 1fr;gap:14px;}
    @media (max-width:768px){.form-grid-2,.form-grid-3,.form-grid-4{grid-template-columns:1fr;}}
</style>

<div class="content">
    <div class="ev-form">
        <ol class="breadcrumb" style="background:#fff;">
            <li><a href="<?php echo base_url('gestao_corporativa/intranet'); ?>"><i class="fa fa-home"></i> Home</a></li>
            <li><a href="<?php echo base_url('gestao_corporativa/Eventoplus'); ?>">Eventos</a></li>
            <li class="active"><?php echo !empty($evento) ? 'Editar' : 'Novo'; ?></li>
        </ol>

        <?php echo form_open('gestao_corporativa/Eventoplus/save'); ?>
        <?php if (!empty($evento)): ?><input type="hidden" name="id" value="<?php echo (int) $evento['id']; ?>"><?php endif; ?>

        <div class="form-card">
            <div class="form-card-header">Sobre o evento</div>
            <div class="form-card-body">
                <div class="form-group">
                    <label class="control-label">Título <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control" required value="<?php echo html_escape($evento['title'] ?? ''); ?>">
                </div>

                <div class="form-grid-4">
                    <div class="form-group">
                        <label class="control-label">Início <span class="text-danger">*</span></label>
                        <input type="date" name="start" class="form-control" required value="<?php echo !empty($evento['start']) ? date('Y-m-d', strtotime($evento['start'])) : date('Y-m-d'); ?>">
                    </div>
                    <div class="form-group">
                        <label class="control-label">Hora início</label>
                        <input type="time" name="start_time" class="form-control" value="<?php echo !empty($evento['start']) ? date('H:i', strtotime($evento['start'])) : '09:00'; ?>">
                    </div>
                    <div class="form-group">
                        <label class="control-label">Fim</label>
                        <input type="date" name="end" class="form-control" value="<?php echo !empty($evento['end']) ? date('Y-m-d', strtotime($evento['end'])) : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label class="control-label">Hora fim</label>
                        <input type="time" name="end_time" class="form-control" value="<?php echo !empty($evento['end']) ? date('H:i', strtotime($evento['end'])) : ''; ?>">
                    </div>
                </div>

                <div class="form-grid-3">
                    <div class="form-group">
                        <label class="control-label">Local</label>
                        <input type="text" name="onde" class="form-control" value="<?php echo html_escape($evento['onde'] ?? ''); ?>">
                    </div>
                    <div class="form-group">
                        <label class="control-label">Cor</label>
                        <input type="color" name="color" class="form-control p-2" value="<?php echo html_escape($evento['color'] ?? '#0a66c2'); ?>">
                    </div>
                    <div class="form-group">
                        <label class="control-label" style="display:block;">Visibilidade</label>
                        <label class="checkbox-inline" style="margin-top:8px;">
                            <input type="checkbox" name="public" value="1" <?php echo !empty($evento['public']) ? 'checked' : ''; ?>>
                            Visível para outros usuários
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label">Descrição</label>
                    <textarea id="description" name="description"><?php echo html_escape($evento['description'] ?? ''); ?></textarea>
                </div>
            </div>
        </div>

        <div class="form-card">
            <div class="form-card-header">Vínculos <small class="text-muted">opcional — relacione este evento a outros itens</small></div>
            <div class="form-card-body">
                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="control-label">Projeto</label>
                        <select name="project_id" class="form-control select2">
                            <option value="">— sem projeto —</option>
                            <?php foreach ($projects as $p): ?>
                                <option value="<?php echo (int) $p['id']; ?>" <?php echo (int) ($evento['project_id'] ?? 0) === (int) $p['id'] ? 'selected' : ''; ?>><?php echo html_escape($p['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Grupo</label>
                        <select name="grupo_id" class="form-control select2">
                            <option value="">— sem grupo —</option>
                            <?php foreach ($grupos as $g): ?>
                                <option value="<?php echo (int) $g['id']; ?>" <?php echo (int) ($evento['grupo_id'] ?? 0) === (int) $g['id'] ? 'selected' : ''; ?>><?php echo html_escape($g['titulo']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Ata</label>
                        <select name="ata_id" class="form-control select2">
                            <option value="">— sem ata —</option>
                            <?php foreach ($atas as $a): ?>
                                <option value="<?php echo (int) $a['id']; ?>" <?php echo (int) ($evento['ata_id'] ?? 0) === (int) $a['id'] ? 'selected' : ''; ?>><?php echo html_escape($a['titulo']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Plano de ação</label>
                        <select name="plano_id" class="form-control select2">
                            <option value="">— sem plano —</option>
                            <?php foreach ($planos as $pa): ?>
                                <option value="<?php echo (int) $pa['id']; ?>" <?php echo (int) ($evento['plano_id'] ?? 0) === (int) $pa['id'] ? 'selected' : ''; ?>><?php echo html_escape($pa['titulo']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-card">
            <div class="form-card-body" style="text-align:right;">
                <a href="<?php echo base_url('gestao_corporativa/Eventoplus'); ?>" class="btn btn-default">Cancelar</a>
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
    $('#description').summernote({lang:'pt-BR', height:160,
        toolbar:[['style',['style']],['font',['bold','italic','underline']],['para',['ul','ol','paragraph']],['insert',['link']]]});
});
</script>
