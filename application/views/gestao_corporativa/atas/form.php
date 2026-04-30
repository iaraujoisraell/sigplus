<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head_intranet_novo(); ?>
<?php $this->load->view('gestao_corporativa/css_background'); ?>

<link rel="stylesheet" href="<?php echo base_url() ?>assets/lte/plugins/select2/css/select2.min.css">
<link rel="stylesheet" href="<?php echo base_url() ?>assets/lte/plugins/summernote/summernote-bs4.min.css">

<style>
    .ata-form-page{max-width:1100px;margin:24px auto;padding:0 18px;}
    .form-card{background:#fff;border:1px solid #e5e7eb;border-radius:10px;margin-bottom:14px;}
    .form-card-header{padding:12px 18px;border-bottom:1px solid #eef1f4;font-weight:600;color:#1f2937;font-size:14px;display:flex;justify-content:space-between;align-items:center;}
    .form-card-body{padding:18px;}
    .form-grid-2{display:grid;grid-template-columns:1fr 1fr;gap:14px;}
    .form-grid-3{display:grid;grid-template-columns:1fr 1fr 1fr;gap:14px;}
    @media (max-width:768px){.form-grid-2,.form-grid-3{grid-template-columns:1fr;}}

    .lista-itens{display:flex;flex-direction:column;gap:8px;}
    .item-card{background:#f9fafb;border:1px solid #e5e7eb;border-radius:8px;padding:10px;display:grid;grid-template-columns:1fr 200px 140px auto;gap:8px;align-items:center;}
    .item-card input,.item-card select,.item-card textarea{width:100%;border:1px solid #d0d5dd;border-radius:6px;padding:6px 10px;font-size:13px;}
    .item-card .remove{background:none;border:0;color:#dc2626;cursor:pointer;font-size:14px;}
    .item-card .gerar-task{font-size:12px;color:#475569;display:flex;align-items:center;gap:4px;}
    @media (max-width:900px){.item-card{grid-template-columns:1fr;}}

    .participante-card{background:#f9fafb;border:1px solid #e5e7eb;border-radius:8px;padding:10px;display:grid;grid-template-columns:120px 1fr 1fr auto;gap:8px;align-items:center;}
    .participante-card select,.participante-card input{width:100%;border:1px solid #d0d5dd;border-radius:6px;padding:6px 10px;font-size:13px;}
    @media (max-width:900px){.participante-card{grid-template-columns:1fr;}}

    .add-row{margin-top:8px;background:#eaf2fb;color:#0a66c2;border:1px dashed #c7d7ea;border-radius:8px;padding:8px 12px;font-weight:600;cursor:pointer;font-size:13px;width:100%;}
    .add-row:hover{background:#dceaff;}
    .note-editor.note-frame{border:1px solid #d0d5dd;border-radius:6px;}
    .note-editor.note-frame .note-editing-area .note-editable{min-height:140px;}
</style>

<div class="content">
    <div class="ata-form-page">
        <ol class="breadcrumb" style="background:#fff;">
            <li><a href="<?php echo base_url('gestao_corporativa/intranet'); ?>"><i class="fa fa-home"></i> Home</a></li>
            <li><a href="<?php echo base_url('gestao_corporativa/Ata'); ?>">Atas</a></li>
            <li class="active"><?php echo !empty($ata) ? 'Editar' : 'Nova'; ?></li>
        </ol>

        <?php echo form_open('gestao_corporativa/Ata/save', ['id' => 'form-ata']); ?>
        <?php if (!empty($ata)): ?>
            <input type="hidden" name="id" value="<?php echo (int) $ata['id']; ?>">
        <?php endif; ?>

        <div class="form-card">
            <div class="form-card-header">Dados da Ata</div>
            <div class="form-card-body">
                <div class="form-group">
                    <label class="control-label">Título <span class="text-danger">*</span></label>
                    <input type="text" name="titulo" class="form-control" required maxlength="250" value="<?php echo html_escape($ata['titulo'] ?? ''); ?>">
                </div>

                <div class="form-grid-4">
                    <div class="form-group">
                        <label class="control-label">Projeto</label>
                        <select name="project_id" id="project_id" class="form-control select2">
                            <option value="">— sem projeto —</option>
                            <?php foreach ($projects as $p): ?>
                                <option value="<?php echo (int) $p['id']; ?>" <?php echo (int) ($ata['project_id'] ?? $project_id) === (int) $p['id'] ? 'selected' : ''; ?>><?php echo html_escape($p['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="control-label">Responsável</label>
                        <select name="responsavel_id" class="form-control select2">
                            <option value="">— selecionar —</option>
                            <?php foreach ($staffs as $s): ?>
                                <option value="<?php echo (int) $s['staffid']; ?>" <?php echo (int) ($ata['responsavel_id'] ?? 0) === (int) $s['staffid'] ? 'selected' : ''; ?>><?php echo html_escape($s['firstname'] . ' ' . $s['lastname']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="control-label">Status</label>
                        <select name="status" class="form-control">
                            <?php foreach ($this->Ata_model->get_statuses() as $s): ?>
                                <option value="<?php echo $s; ?>" <?php echo ($ata['status'] ?? 'aberta') === $s ? 'selected' : ''; ?>><?php echo $this->Ata_model->get_status_label($s); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="control-label">Quem pode ver</label>
                        <select name="visibilidade" id="visibilidade" class="form-control">
                            <option value="publica" <?php echo ($ata['visibilidade'] ?? 'publica') === 'publica' ? 'selected' : ''; ?>>Toda a empresa</option>
                            <option value="restrita" <?php echo ($ata['visibilidade'] ?? '') === 'restrita' ? 'selected' : ''; ?>>Restrita (apenas listados em "Quem mais pode visualizar")</option>
                        </select>
                    </div>
                </div>

                <style>
                    .form-grid-4{display:grid;grid-template-columns:1fr 1fr 1fr 1fr;gap:14px;}
                    @media (max-width:900px){.form-grid-4{grid-template-columns:1fr 1fr;}}
                </style>

                <div class="form-grid-3">
                    <div class="form-group">
                        <label class="control-label">Data</label>
                        <input type="date" name="data" class="form-control" value="<?php echo html_escape($ata['data'] ?? date('Y-m-d')); ?>">
                    </div>
                    <div class="form-group">
                        <label class="control-label">Horário (início — fim)</label>
                        <div style="display:flex;gap:8px;">
                            <input type="time" name="hora_inicio" class="form-control" value="<?php echo html_escape(substr($ata['hora_inicio'] ?? '', 0, 5)); ?>">
                            <input type="time" name="hora_fim" class="form-control" value="<?php echo html_escape(substr($ata['hora_fim'] ?? '', 0, 5)); ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Local</label>
                        <input type="text" name="local" class="form-control" value="<?php echo html_escape($ata['local'] ?? ''); ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label">Pauta</label>
                    <textarea id="pauta" name="pauta"><?php echo html_escape($ata['pauta'] ?? ''); ?></textarea>
                </div>

                <div class="form-group">
                    <label class="control-label">Discussões</label>
                    <textarea id="discussoes" name="discussoes"><?php echo html_escape($ata['discussoes'] ?? ''); ?></textarea>
                </div>

                <div class="form-group">
                    <label class="control-label">Observações</label>
                    <textarea name="observacoes" class="form-control" rows="3"><?php echo html_escape($ata['observacoes'] ?? ''); ?></textarea>
                </div>
            </div>
        </div>

        <div class="form-card">
            <div class="form-card-header">
                Participantes
                <small class="text-muted">Colaboradores presentes na reunião</small>
            </div>
            <div class="form-card-body">
                <div id="lista-participantes" class="lista-itens">
                    <?php foreach ($participantes ?? [] as $p): ?>
                        <?php $this->load->view('gestao_corporativa/atas/_pessoa_row', ['p' => $p, 'tipo' => 'participantes', 'staffs' => $staffs]); ?>
                    <?php endforeach; ?>
                </div>
                <button type="button" class="add-row js-add-pessoa" data-tipo="participantes"><i class="fa fa-plus"></i> Adicionar participante</button>
            </div>
        </div>

        <div class="form-card">
            <div class="form-card-header">
                Convidados
                <small class="text-muted">Pessoas externas (não colaboradores) — informe nome, e-mail e organização</small>
            </div>
            <div class="form-card-body">
                <div id="lista-convidados" class="lista-itens">
                    <?php foreach ($convidados ?? [] as $c): ?>
                        <?php $this->load->view('gestao_corporativa/atas/_convidado_row', ['c' => $c]); ?>
                    <?php endforeach; ?>
                </div>
                <button type="button" class="add-row js-add-convidado"><i class="fa fa-plus"></i> Adicionar convidado</button>
            </div>
        </div>

        <div class="form-card">
            <div class="form-card-header">
                Quem mais pode visualizar
                <small class="text-muted">Colaboradores que não estiveram, mas precisam ter acesso à ata</small>
            </div>
            <div class="form-card-body">
                <div id="lista-visualizadores" class="lista-itens">
                    <?php foreach ($visualizadores ?? [] as $v): ?>
                        <?php $this->load->view('gestao_corporativa/atas/_pessoa_row', ['p' => $v, 'tipo' => 'visualizadores', 'staffs' => $staffs]); ?>
                    <?php endforeach; ?>
                </div>
                <button type="button" class="add-row js-add-pessoa" data-tipo="visualizadores"><i class="fa fa-plus"></i> Adicionar visualizador</button>
                <p class="text-muted small" style="margin-top:8px;margin-bottom:0;">
                    <i class="fa fa-info-circle"></i>
                    Quando "Quem pode ver" estiver em <strong>"Toda a empresa"</strong>, esta lista é apenas informativa.
                    Quando estiver em <strong>"Restrita"</strong>, <strong>somente</strong> os adicionados aqui +
                    o responsável e o criador da ata terão acesso. Participantes e convidados <strong>não</strong>
                    dão acesso automático — adicione-os aqui também se precisarem visualizar.
                </p>
            </div>
        </div>

        <div class="form-card">
            <div class="form-card-header">
                Decisões / Encaminhamentos
                <small class="text-muted">Marque "gerar task" pra criar tarefa automaticamente</small>
            </div>
            <div class="form-card-body">
                <div id="lista-decisoes" class="lista-itens">
                    <?php if (!empty($decisoes)): ?>
                        <?php foreach ($decisoes as $d): ?>
                            <?php $this->load->view('gestao_corporativa/atas/_decisao_row', ['d' => $d, 'staffs' => $staffs]); ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <button type="button" class="add-row" id="add-decisao"><i class="fa fa-plus"></i> Adicionar decisão</button>
            </div>
        </div>

        <div class="form-card">
            <div class="form-card-body" style="text-align:right;">
                <a href="<?php echo base_url('gestao_corporativa/Ata'); ?>" class="btn btn-default">Cancelar</a>
                <button type="submit" class="btn btn-info"><i class="fa fa-save"></i> Salvar</button>
            </div>
        </div>

        <?php echo form_close(); ?>
    </div>
</div>

<!-- Templates -->
<template id="tpl-pessoa">
    <div class="pessoa-card">
        <select name="__TIPO__[__INDEX__][staff_id]" class="select2-staff" style="width:100%;">
            <option value="">Selecionar colaborador</option>
            <?php foreach ($staffs as $s): ?>
                <option value="<?php echo (int) $s['staffid']; ?>"><?php echo html_escape($s['firstname'] . ' ' . $s['lastname']); ?></option>
            <?php endforeach; ?>
        </select>
        <button type="button" class="remove js-remove"><i class="fa fa-trash"></i></button>
    </div>
</template>

<template id="tpl-convidado">
    <div class="convidado-card">
        <input type="text" name="convidados[__INDEX__][nome]" placeholder="Nome" required>
        <input type="email" name="convidados[__INDEX__][email]" placeholder="E-mail">
        <input type="text" name="convidados[__INDEX__][organizacao]" placeholder="Organização">
        <button type="button" class="remove js-remove"><i class="fa fa-trash"></i></button>
    </div>
</template>

<style>
    .pessoa-card{background:#f9fafb;border:1px solid #e5e7eb;border-radius:8px;padding:8px 10px;display:grid;grid-template-columns:1fr auto;gap:8px;align-items:center;}
    .convidado-card{background:#f9fafb;border:1px solid #e5e7eb;border-radius:8px;padding:8px 10px;display:grid;grid-template-columns:2fr 2fr 2fr auto;gap:8px;align-items:center;}
    .convidado-card input{width:100%;border:1px solid #d0d5dd;border-radius:6px;padding:7px 10px;font-size:13px;}
    @media (max-width:900px){.convidado-card{grid-template-columns:1fr;}}
</style>

<template id="tpl-decisao">
    <div class="item-card">
        <div>
            <textarea name="decisoes[__INDEX__][descricao]" rows="2" placeholder="Descrição da decisão" required></textarea>
        </div>
        <select name="decisoes[__INDEX__][responsavel_id]" class="select2">
            <option value="">Responsável</option>
            <?php foreach ($staffs as $s): ?>
                <option value="<?php echo (int) $s['staffid']; ?>"><?php echo html_escape($s['firstname'] . ' ' . $s['lastname']); ?></option>
            <?php endforeach; ?>
        </select>
        <input type="date" name="decisoes[__INDEX__][prazo]" placeholder="Prazo">
        <div style="display:flex;flex-direction:column;align-items:flex-end;gap:6px;">
            <label class="gerar-task"><input type="checkbox" name="decisoes[__INDEX__][gerar_task]" value="1"> gerar task</label>
            <button type="button" class="remove js-remove"><i class="fa fa-trash"></i></button>
        </div>
    </div>
</template>

<?php init_tail(); ?>
<script src="<?php echo base_url() ?>assets/lte/plugins/select2/js/select2.full.min.js"></script>
<script src="<?php echo base_url() ?>assets/lte/plugins/summernote/summernote-bs4.min.js"></script>
<script src="<?php echo base_url() ?>assets/lte/plugins/summernote/lang/summernote-pt-BR.min.js"></script>

<script>
$(function () {
    $('.select2, .select2-staff').select2({width:'100%'});

    var snConfig = {
        lang:'pt-BR', height:160,
        toolbar:[['style',['style']],['font',['bold','italic','underline']],['color',['color']],['para',['ul','ol','paragraph']],['insert',['link']],['view',['codeview']]]
    };
    $('#pauta, #discussoes').summernote(snConfig);

    var idxs = { participantes: 100, convidados: 100, visualizadores: 100, decisoes: 100 };

    $('.js-add-pessoa').on('click', function () {
        var tipo = $(this).data('tipo'); // participantes ou visualizadores
        var html = $('#tpl-pessoa').html().replace(/__INDEX__/g, idxs[tipo]++).replace(/__TIPO__/g, tipo);
        var $row = $(html);
        $('#lista-' + tipo).append($row);
        $row.find('.select2-staff').select2({width:'100%'});
    });

    $('.js-add-convidado').on('click', function () {
        var html = $('#tpl-convidado').html().replace(/__INDEX__/g, idxs.convidados++);
        $('#lista-convidados').append(html);
    });

    $('#add-decisao').on('click', function () {
        var html = $('#tpl-decisao').html().replace(/__INDEX__/g, idxs.decisoes++);
        var $row = $(html);
        $('#lista-decisoes').append($row);
        $row.find('.select2').select2({width:'100%'});
    });

    $(document).on('click', '.js-remove', function () {
        $(this).closest('.item-card, .pessoa-card, .convidado-card').remove();
    });
});
</script>
