<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head_intranet_novo(); ?>
<?php $this->load->view('gestao_corporativa/css_background'); ?>

<link rel="stylesheet" href="<?php echo base_url() ?>assets/lte/plugins/select2/css/select2.min.css">
<link rel="stylesheet" href="<?php echo base_url() ?>assets/lte/plugins/summernote/summernote-bs4.min.css">

<style>
    .gp-form{max-width:1100px;margin:24px auto;padding:0 18px;}
    .form-card{background:#fff;border:1px solid #e5e7eb;border-radius:10px;margin-bottom:14px;}
    .form-card-header{padding:12px 18px;border-bottom:1px solid #eef1f4;font-weight:600;color:#1f2937;font-size:14px;}
    .form-card-body{padding:18px;}
    .form-grid-3{display:grid;grid-template-columns:1fr 1fr 1fr;gap:14px;}
    .form-grid-4{display:grid;grid-template-columns:1fr 1fr 1fr 1fr;gap:14px;}
    @media (max-width:768px){.form-grid-3,.form-grid-4{grid-template-columns:1fr 1fr;}}
    @media (max-width:480px){.form-grid-3,.form-grid-4{grid-template-columns:1fr;}}

    .membro-card{background:#f9fafb;border:1px solid #e5e7eb;border-radius:8px;padding:8px 10px;display:grid;grid-template-columns:1fr 160px auto;gap:8px;align-items:center;margin-bottom:6px;}
    .membro-card select,.membro-card input{width:100%;border:1px solid #d0d5dd;border-radius:6px;padding:7px 10px;font-size:13px;}
    .membro-card .remove{background:none;border:0;color:#dc2626;cursor:pointer;}
    @media (max-width:768px){.membro-card{grid-template-columns:1fr;}}
    .add-row{margin-top:6px;background:#eaf2fb;color:#0a66c2;border:1px dashed #c7d7ea;border-radius:8px;padding:8px 12px;font-weight:600;cursor:pointer;font-size:13px;width:100%;}
    .add-row:hover{background:#dceaff;}
</style>

<div class="content">
    <div class="gp-form">
        <ol class="breadcrumb" style="background:#fff;">
            <li><a href="<?php echo base_url('gestao_corporativa/intranet'); ?>"><i class="fa fa-home"></i> Home</a></li>
            <li><a href="<?php echo base_url('gestao_corporativa/Workgroup'); ?>">Grupos</a></li>
            <li class="active"><?php echo !empty($grupo) ? 'Editar' : 'Novo'; ?></li>
        </ol>

        <?php echo form_open('gestao_corporativa/Workgroup/save'); ?>
        <?php if (!empty($grupo)): ?><input type="hidden" name="id" value="<?php echo (int) $grupo['id']; ?>"><?php endif; ?>

        <div class="form-card">
            <div class="form-card-header">Sobre o grupo</div>
            <div class="form-card-body">
                <div class="form-group">
                    <label class="control-label">Título <span class="text-danger">*</span></label>
                    <input type="text" name="titulo" class="form-control" required maxlength="250" value="<?php echo html_escape($grupo['titulo'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label class="control-label">Objetivo</label>
                    <textarea name="objetivo" class="form-control" rows="2" placeholder="Por que este grupo existe? Que demanda vai resolver?"><?php echo html_escape($grupo['objetivo'] ?? ''); ?></textarea>
                </div>

                <div class="form-grid-4">
                    <div class="form-group">
                        <label class="control-label">Líder</label>
                        <select name="lider_id" class="form-control select2">
                            <option value="">Eu mesmo</option>
                            <?php foreach ($staffs as $s): ?>
                                <option value="<?php echo (int) $s['staffid']; ?>" <?php echo (int) ($grupo['lider_id'] ?? 0) === (int) $s['staffid'] ? 'selected' : ''; ?>><?php echo html_escape($s['firstname'] . ' ' . $s['lastname']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="control-label">Status</label>
                        <select name="status" class="form-control">
                            <?php foreach ($this->Workgroup_model->get_statuses() as $s): ?>
                                <option value="<?php echo $s; ?>" <?php echo ($grupo['status'] ?? 'ativo') === $s ? 'selected' : ''; ?>><?php echo $this->Workgroup_model->get_status_label($s); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="control-label">Início</label>
                        <input type="date" name="dt_inicio" class="form-control" value="<?php echo html_escape($grupo['dt_inicio'] ?? ''); ?>">
                    </div>

                    <div class="form-group">
                        <label class="control-label">Fim previsto</label>
                        <input type="date" name="dt_fim_prevista" class="form-control" value="<?php echo html_escape($grupo['dt_fim_prevista'] ?? ''); ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label">Projeto vinculado <small class="text-muted">(opcional)</small></label>
                    <select name="project_id" class="form-control select2">
                        <option value="">— sem projeto —</option>
                        <?php foreach ($projects as $p): ?>
                            <option value="<?php echo (int) $p['id']; ?>" <?php echo (int) ($grupo['project_id'] ?? $project_id) === (int) $p['id'] ? 'selected' : ''; ?>><?php echo html_escape($p['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label class="control-label">Descrição</label>
                    <textarea id="descricao" name="descricao"><?php echo html_escape($grupo['descricao'] ?? ''); ?></textarea>
                </div>
            </div>
        </div>

        <div class="form-card">
            <div class="form-card-header">Membros <small class="text-muted">apenas eles veem o grupo (além de você e o líder)</small></div>
            <div class="form-card-body">
                <div id="lista-membros">
                    <?php foreach ($membros ?? [] as $m): ?>
                        <?php $this->load->view('gestao_corporativa/grupos/_membro_row', ['m' => $m, 'staffs' => $staffs]); ?>
                    <?php endforeach; ?>
                </div>
                <button type="button" class="add-row" id="add-membro"><i class="fa fa-plus"></i> Adicionar membro</button>
            </div>
        </div>

        <div class="form-card">
            <div class="form-card-body" style="text-align:right;">
                <a href="<?php echo base_url('gestao_corporativa/Workgroup'); ?>" class="btn btn-default">Cancelar</a>
                <button type="submit" class="btn btn-info"><i class="fa fa-save"></i> Salvar</button>
            </div>
        </div>

        <?php echo form_close(); ?>
    </div>
</div>

<template id="tpl-membro">
    <div class="membro-card">
        <select name="membros[__INDEX__][staff_id]" class="select2-staff" style="width:100%;">
            <option value="">Selecionar colaborador</option>
            <?php foreach ($staffs as $s): ?>
                <option value="<?php echo (int) $s['staffid']; ?>"><?php echo html_escape($s['firstname'] . ' ' . $s['lastname']); ?></option>
            <?php endforeach; ?>
        </select>
        <select name="membros[__INDEX__][papel]">
            <option value="membro">Membro</option>
            <option value="lider">Líder</option>
            <option value="observador">Observador</option>
        </select>
        <button type="button" class="remove js-remove"><i class="fa fa-trash"></i></button>
    </div>
</template>

<?php init_tail(); ?>
<script src="<?php echo base_url() ?>assets/lte/plugins/select2/js/select2.full.min.js"></script>
<script src="<?php echo base_url() ?>assets/lte/plugins/summernote/summernote-bs4.min.js"></script>
<script src="<?php echo base_url() ?>assets/lte/plugins/summernote/lang/summernote-pt-BR.min.js"></script>

<script>
$(function () {
    $('.select2, .select2-staff').select2({width:'100%'});
    $('#descricao').summernote({lang:'pt-BR', height:160,
        toolbar:[['style',['style']],['font',['bold','italic','underline']],['para',['ul','ol','paragraph']],['insert',['link']]]});

    var idx = <?php echo (int) (isset($membros) ? count($membros) : 0) + 100; ?>;
    $('#add-membro').on('click', function () {
        var html = $('#tpl-membro').html().replace(/__INDEX__/g, idx++);
        var $row = $(html);
        $('#lista-membros').append($row);
        $row.find('.select2-staff').select2({width:'100%'});
    });

    $(document).on('click', '.js-remove', function () {
        $(this).closest('.membro-card').remove();
    });
});
</script>
