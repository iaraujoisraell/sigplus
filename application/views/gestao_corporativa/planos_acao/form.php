<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head_intranet_novo(); ?>
<?php $this->load->view('gestao_corporativa/css_background'); ?>

<link rel="stylesheet" href="<?php echo base_url() ?>assets/lte/plugins/select2/css/select2.min.css">
<link rel="stylesheet" href="<?php echo base_url() ?>assets/lte/plugins/summernote/summernote-bs4.min.css">

<style>
    .pa-form-page{max-width:1200px;margin:24px auto;padding:0 18px;}
    .form-card{background:#fff;border:1px solid #e5e7eb;border-radius:10px;margin-bottom:14px;}
    .form-card-header{padding:12px 18px;border-bottom:1px solid #eef1f4;font-weight:600;color:#1f2937;font-size:14px;}
    .form-card-body{padding:18px;}
    .form-grid-3{display:grid;grid-template-columns:1fr 1fr 1fr;gap:14px;}
    .form-grid-4{display:grid;grid-template-columns:1fr 1fr 1fr 1fr;gap:14px;}
    @media (max-width:900px){.form-grid-3,.form-grid-4{grid-template-columns:1fr 1fr;}}
    @media (max-width:600px){.form-grid-3,.form-grid-4{grid-template-columns:1fr;}}

    .item-5w2h{background:#f9fafb;border:1px solid #e5e7eb;border-radius:8px;padding:14px;margin-bottom:8px;}
    .item-5w2h .row1,.item-5w2h .row2{display:grid;gap:10px;margin-bottom:8px;}
    .item-5w2h .row1{grid-template-columns:1fr 1fr;}
    .item-5w2h .row2{grid-template-columns:1fr 1fr 1fr;}
    .item-5w2h .row3{display:grid;grid-template-columns:1fr 1fr 140px 140px;gap:10px;align-items:center;}
    @media (max-width:768px){.item-5w2h .row1,.item-5w2h .row2,.item-5w2h .row3{grid-template-columns:1fr;}}
    .item-5w2h textarea,.item-5w2h input,.item-5w2h select{width:100%;border:1px solid #d0d5dd;border-radius:6px;padding:7px 10px;font-size:13px;}
    .item-5w2h label{font-size:11px;font-weight:600;color:#475569;text-transform:uppercase;letter-spacing:.04em;}
    .item-5w2h .footer-row{display:flex;justify-content:space-between;align-items:center;margin-top:8px;}
    .item-5w2h .footer-row .gerar{font-size:12px;color:#475569;}
    .item-5w2h .remove{background:none;border:0;color:#dc2626;cursor:pointer;font-size:14px;}
    .item-5w2h .task-link{background:#dcfce7;color:#166534;padding:2px 8px;border-radius:6px;font-size:11px;font-weight:600;}

    .ishikawa-grid{display:grid;grid-template-columns:1fr 1fr;gap:14px;}
    @media (max-width:768px){.ishikawa-grid{grid-template-columns:1fr;}}
    .ishikawa-grid label{font-weight:600;color:#475569;font-size:13px;}
    .ishikawa-grid textarea{width:100%;border:1px solid #d0d5dd;border-radius:6px;padding:8px 10px;min-height:80px;font-size:13px;}

    .add-row{margin-top:8px;background:#eaf2fb;color:#0a66c2;border:1px dashed #c7d7ea;border-radius:8px;padding:8px 12px;font-weight:600;cursor:pointer;font-size:13px;width:100%;}
    .add-row:hover{background:#dceaff;}
    .note-editor.note-frame .note-editing-area .note-editable{min-height:160px;}
</style>

<div class="content">
    <div class="pa-form-page">
        <ol class="breadcrumb" style="background:#fff;">
            <li><a href="<?php echo base_url('gestao_corporativa/intranet'); ?>"><i class="fa fa-home"></i> Home</a></li>
            <li><a href="<?php echo base_url('gestao_corporativa/Plano_acao'); ?>">Planos de Ação</a></li>
            <li class="active"><?php echo !empty($plano) ? 'Editar' : 'Novo'; ?></li>
        </ol>

        <?php echo form_open('gestao_corporativa/Plano_acao/save', ['id' => 'form-plano']); ?>
        <?php if (!empty($plano)): ?>
            <input type="hidden" name="id" value="<?php echo (int) $plano['id']; ?>">
        <?php endif; ?>

        <div class="form-card">
            <div class="form-card-header">Dados do Plano</div>
            <div class="form-card-body">
                <div class="form-group">
                    <label class="control-label">Título <span class="text-danger">*</span></label>
                    <input type="text" name="titulo" class="form-control" required maxlength="250" value="<?php echo html_escape($plano['titulo'] ?? ''); ?>">
                </div>

                <div class="form-grid-4">
                    <div class="form-group">
                        <label class="control-label">Projeto</label>
                        <select name="project_id" id="project_id" class="form-control select2">
                            <option value="">— sem projeto —</option>
                            <?php foreach ($projects as $p): ?>
                                <option value="<?php echo (int) $p['id']; ?>" <?php echo (int) ($plano['project_id'] ?? $project_id) === (int) $p['id'] ? 'selected' : ''; ?>><?php echo html_escape($p['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Ata vinculada</label>
                        <select name="ata_id" id="ata_id" class="form-control select2">
                            <option value="">— nenhuma —</option>
                            <?php foreach ($atas as $a): ?>
                                <option value="<?php echo (int) $a['id']; ?>" <?php echo (int) ($plano['ata_id'] ?? $ata_id) === (int) $a['id'] ? 'selected' : ''; ?>><?php echo html_escape($a['titulo']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Responsável</label>
                        <select name="responsavel_id" class="form-control select2">
                            <option value="">— selecionar —</option>
                            <?php foreach ($staffs as $s): ?>
                                <option value="<?php echo (int) $s['staffid']; ?>" <?php echo (int) ($plano['responsavel_id'] ?? 0) === (int) $s['staffid'] ? 'selected' : ''; ?>><?php echo html_escape($s['firstname'] . ' ' . $s['lastname']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Status</label>
                        <select name="status" class="form-control">
                            <?php foreach ($this->Plano_acao_model->get_statuses() as $s): ?>
                                <option value="<?php echo $s; ?>" <?php echo ($plano['status'] ?? 'aberto') === $s ? 'selected' : ''; ?>><?php echo $this->Plano_acao_model->get_status_label($s); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-grid-3">
                    <div class="form-group">
                        <label class="control-label">Início</label>
                        <input type="date" name="dt_inicio" class="form-control" value="<?php echo html_escape($plano['dt_inicio'] ?? ''); ?>">
                    </div>
                    <div class="form-group">
                        <label class="control-label">Prazo</label>
                        <input type="date" name="dt_fim" class="form-control" value="<?php echo html_escape($plano['dt_fim'] ?? ''); ?>">
                    </div>
                    <div class="form-group">
                        <label class="control-label">Metodologia</label>
                        <select name="metodologia" id="metodologia" class="form-control">
                            <option value="5w2h" <?php echo ($plano['metodologia'] ?? '5w2h') === '5w2h' ? 'selected' : ''; ?>>5W2H</option>
                            <option value="ishikawa" <?php echo ($plano['metodologia'] ?? '') === 'ishikawa' ? 'selected' : ''; ?>>Ishikawa (6M)</option>
                            <option value="ambos" <?php echo ($plano['metodologia'] ?? '') === 'ambos' ? 'selected' : ''; ?>>Ambos</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label">Grupo vinculado <small class="text-muted">(opcional)</small></label>
                    <?php
                    $empresa_id = (int) $this->session->userdata('empresa_id');
                    $me = (int) get_staff_user_id();
                    $grupo_atual = $this->input->get('grupo_id') ?: ($plano['grupo_id'] ?? null);
                    $grupos_meus = $this->db->query("SELECT DISTINCT g.id, g.titulo FROM tbl_grupos g
                        LEFT JOIN tbl_grupos_membros m ON m.grupo_id = g.id AND m.deleted = 0
                        WHERE g.deleted = 0 AND g.empresa_id = $empresa_id
                          AND (g.lider_id = $me OR g.user_create = $me OR m.staff_id = $me)
                        ORDER BY g.titulo")->result_array();
                    ?>
                    <select name="grupo_id" class="form-control select2">
                        <option value="">— sem grupo —</option>
                        <?php foreach ($grupos_meus as $g): ?>
                            <option value="<?php echo (int) $g['id']; ?>" <?php echo (int) $grupo_atual === (int) $g['id'] ? 'selected' : ''; ?>><?php echo html_escape($g['titulo']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label class="control-label">Descrição</label>
                    <textarea id="descricao" name="descricao"><?php echo html_escape($plano['descricao'] ?? ''); ?></textarea>
                </div>
            </div>
        </div>

        <div class="form-card" id="card-5w2h">
            <div class="form-card-header">5W2H — Itens de ação <small class="text-muted">marque "gerar task" pra criar tarefa automaticamente</small></div>
            <div class="form-card-body">
                <div id="lista-5w2h">
                    <?php if (!empty($itens_5w2h)): ?>
                        <?php foreach ($itens_5w2h as $w): ?>
                            <?php $this->load->view('gestao_corporativa/planos_acao/_5w2h_row', ['w' => $w, 'staffs' => $staffs]); ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <button type="button" class="add-row" id="add-5w2h"><i class="fa fa-plus"></i> Adicionar item 5W2H</button>
            </div>
        </div>

        <div class="form-card" id="card-ishikawa">
            <div class="form-card-header">Análise Ishikawa (6M) <small class="text-muted">causa-raiz por categoria</small></div>
            <div class="form-card-body">
                <div class="form-group">
                    <label>Problema</label>
                    <textarea name="ishikawa[problema]" class="form-control" rows="2"><?php echo html_escape($ishikawa['problema'] ?? ''); ?></textarea>
                </div>
                <div class="ishikawa-grid">
                    <div><label>🔧 Máquina</label><textarea name="ishikawa[maquina]"><?php echo html_escape($ishikawa['maquina'] ?? ''); ?></textarea></div>
                    <div><label>📋 Método</label><textarea name="ishikawa[metodo]"><?php echo html_escape($ishikawa['metodo'] ?? ''); ?></textarea></div>
                    <div><label>👥 Mão de obra</label><textarea name="ishikawa[mao_obra]"><?php echo html_escape($ishikawa['mao_obra'] ?? ''); ?></textarea></div>
                    <div><label>📦 Material</label><textarea name="ishikawa[material]"><?php echo html_escape($ishikawa['material'] ?? ''); ?></textarea></div>
                    <div><label>🌱 Meio ambiente</label><textarea name="ishikawa[meio_ambiente]"><?php echo html_escape($ishikawa['meio_ambiente'] ?? ''); ?></textarea></div>
                    <div><label>📐 Medida</label><textarea name="ishikawa[medida]"><?php echo html_escape($ishikawa['medida'] ?? ''); ?></textarea></div>
                </div>
                <div class="form-group" style="margin-top:14px;">
                    <label>Causa raiz</label>
                    <textarea name="ishikawa[causa_raiz]" class="form-control" rows="2"><?php echo html_escape($ishikawa['causa_raiz'] ?? ''); ?></textarea>
                </div>
                <div class="form-group">
                    <label>Ação corretiva</label>
                    <textarea name="ishikawa[acao_corretiva]" class="form-control" rows="2"><?php echo html_escape($ishikawa['acao_corretiva'] ?? ''); ?></textarea>
                </div>
            </div>
        </div>

        <div class="form-card">
            <div class="form-card-body" style="text-align:right;">
                <a href="<?php echo base_url('gestao_corporativa/Plano_acao'); ?>" class="btn btn-default">Cancelar</a>
                <button type="submit" class="btn btn-info"><i class="fa fa-save"></i> Salvar</button>
            </div>
        </div>

        <?php echo form_close(); ?>
    </div>
</div>

<template id="tpl-5w2h">
    <div class="item-5w2h">
        <input type="hidden" name="itens_5w2h[__INDEX__][id]" value="0">
        <div class="row1">
            <div><label>What — O que</label><textarea name="itens_5w2h[__INDEX__][what]" rows="2" required></textarea></div>
            <div><label>Why — Por quê</label><textarea name="itens_5w2h[__INDEX__][why]" rows="2"></textarea></div>
        </div>
        <div class="row2">
            <div><label>Where — Onde</label><input type="text" name="itens_5w2h[__INDEX__][where]"></div>
            <div><label>When — Quando</label><input type="date" name="itens_5w2h[__INDEX__][when]"></div>
            <div><label>Who — Quem</label>
                <select name="itens_5w2h[__INDEX__][who_id]" class="select2">
                    <option value="">—</option>
                    <?php foreach ($staffs as $s): ?>
                        <option value="<?php echo (int) $s['staffid']; ?>"><?php echo html_escape($s['firstname'] . ' ' . $s['lastname']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="row3">
            <div><label>How — Como</label><textarea name="itens_5w2h[__INDEX__][how]" rows="2"></textarea></div>
            <div><label>How much — Custo (R$)</label><input type="text" name="itens_5w2h[__INDEX__][how_much]" placeholder="0,00"></div>
            <div><label>Status</label>
                <select name="itens_5w2h[__INDEX__][status]">
                    <option value="aberto">Aberto</option>
                    <option value="em_andamento">Em andamento</option>
                    <option value="concluido">Concluído</option>
                    <option value="cancelado">Cancelado</option>
                </select>
            </div>
            <div style="display:flex;align-items:center;justify-content:flex-end;">
                <button type="button" class="remove js-remove"><i class="fa fa-trash"></i></button>
            </div>
        </div>
        <div class="footer-row">
            <label class="gerar"><input type="checkbox" name="itens_5w2h[__INDEX__][gerar_task]" value="1"> gerar task ao salvar</label>
        </div>
    </div>
</template>

<?php init_tail(); ?>
<script src="<?php echo base_url() ?>assets/lte/plugins/select2/js/select2.full.min.js"></script>
<script src="<?php echo base_url() ?>assets/lte/plugins/summernote/summernote-bs4.min.js"></script>
<script src="<?php echo base_url() ?>assets/lte/plugins/summernote/lang/summernote-pt-BR.min.js"></script>

<script>
$(function () {
    $('.select2').select2({width:'100%'});

    $('#descricao').summernote({
        lang:'pt-BR', height:160,
        toolbar:[['style',['style']],['font',['bold','italic','underline']],['color',['color']],['para',['ul','ol','paragraph']],['insert',['link']]]
    });

    function refreshCardsByMetodologia() {
        var m = $('#metodologia').val();
        $('#card-5w2h').toggle(m === '5w2h' || m === 'ambos');
        $('#card-ishikawa').toggle(m === 'ishikawa' || m === 'ambos');
    }
    refreshCardsByMetodologia();
    $('#metodologia').on('change', refreshCardsByMetodologia);

    var idx = <?php echo (int) (isset($itens_5w2h) ? count($itens_5w2h) : 0) + 100; ?>;
    $('#add-5w2h').on('click', function () {
        var html = $('#tpl-5w2h').html().replace(/__INDEX__/g, idx++);
        var $row = $(html);
        $('#lista-5w2h').append($row);
        $row.find('.select2').select2({width:'100%'});
    });

    $(document).on('click', '.js-remove', function () {
        $(this).closest('.item-5w2h').remove();
    });

    // Recarrega atas quando troca projeto
    $('#project_id').on('change', function () {
        var pid = $(this).val();
        if (!pid) return;
        $.get('<?php echo base_url('gestao_corporativa/Plano_acao/atas_por_projeto'); ?>/' + pid, function (atas) {
            var $sel = $('#ata_id');
            var atual = $sel.val();
            $sel.empty().append('<option value="">— nenhuma —</option>');
            atas.forEach(function (a) {
                $sel.append('<option value="' + a.id + '"' + (atual == a.id ? ' selected' : '') + '>' + a.titulo + '</option>');
            });
            $sel.trigger('change.select2');
        }, 'json');
    });
});
</script>
