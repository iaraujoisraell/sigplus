<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head_intranet_novo(false); ?>
<?php $this->load->view('gestao_corporativa/css_background'); ?>

<link rel="stylesheet" href="<?php echo base_url() ?>assets/lte/plugins/select2/css/select2.min.css">
<link rel="stylesheet" href="<?php echo base_url() ?>assets/lte/plugins/summernote/summernote-bs4.min.css">

<style>
    .fm-page{max-width:980px;margin:24px auto;padding:0 18px;}
    .fm-card{background:#fff;border:1px solid #e5e7eb;border-radius:10px;margin-bottom:14px;border-left:4px solid #0a66c2;}
    .fm-card-header{padding:14px 18px;border-bottom:1px solid #eef1f4;font-weight:600;color:#1f2937;font-size:14px;display:flex;justify-content:space-between;align-items:center;}
    .fm-card-body{padding:18px;}

    .fm-titulo{font-size:24px;font-weight:700;color:#1f2937;border:0;border-bottom:2px solid transparent;padding:6px 0;width:100%;background:transparent;}
    .fm-titulo:focus{outline:none;border-bottom-color:#0a66c2;}
    .fm-desc{font-size:13px;color:#475569;border:0;width:100%;padding:6px 0;background:transparent;resize:vertical;min-height:40px;}
    .fm-desc:focus{outline:none;border-bottom:1px dashed #cbd5e1;}

    .fm-meta-row{display:grid;grid-template-columns:1fr 1fr 1fr;gap:14px;margin-top:14px;padding-top:14px;border-top:1px solid #f1f5f9;}
    .fm-meta-row label{font-size:11px;font-weight:600;color:#475569;text-transform:uppercase;letter-spacing:.04em;}
    .fm-meta-row select,.fm-meta-row input{width:100%;border:1px solid #d0d5dd;border-radius:6px;padding:7px 10px;font-size:13px;}
    @media(max-width:768px){.fm-meta-row{grid-template-columns:1fr;}}

    .fm-vinc-row{display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:10px;margin-top:10px;}

    .fm-perg{background:#fff;border:1px solid #e5e7eb;border-radius:10px;margin-bottom:10px;border-left:4px solid transparent;transition:.15s;}
    .fm-perg.active{border-left-color:#0a66c2;box-shadow:0 2px 8px rgba(10,102,194,.08);}
    .fm-perg-collapsed{padding:14px 18px;display:flex;justify-content:space-between;align-items:center;cursor:pointer;}
    .fm-perg-collapsed .ttl{font-weight:600;color:#1f2937;font-size:14px;}
    .fm-perg-collapsed .ttl .num{color:#94a3b8;font-weight:400;margin-right:4px;}
    .fm-perg-collapsed .meta{font-size:11px;color:#64748b;display:flex;gap:10px;align-items:center;}
    .fm-perg-expanded{padding:18px;display:none;}
    .fm-perg.active .fm-perg-expanded{display:block;}
    .fm-perg.active .fm-perg-collapsed{display:none;}

    .fm-perg-grid{display:grid;grid-template-columns:1fr 220px;gap:14px;}
    @media(max-width:768px){.fm-perg-grid{grid-template-columns:1fr;}}
    .fm-perg-titulo{font-size:15px;font-weight:600;color:#1f2937;border:0;background:#f8fafc;border-radius:6px;padding:10px 12px;width:100%;}
    .fm-perg-titulo:focus{outline:none;background:#fff;box-shadow:0 0 0 2px #0a66c2;}
    .fm-perg-desc{font-size:12px;color:#475569;border:0;background:transparent;width:100%;padding:6px 0;margin-top:4px;resize:vertical;min-height:30px;border-bottom:1px dashed transparent;}
    .fm-perg-desc:focus{outline:none;border-bottom-color:#cbd5e1;}
    .fm-perg-tipo{width:100%;border:1px solid #d0d5dd;border-radius:6px;padding:8px 10px;font-size:13px;background:#fff;}

    .fm-opcoes{margin-top:12px;}
    .fm-opcao{display:flex;align-items:center;gap:8px;padding:6px 0;}
    .fm-opcao .marker{color:#94a3b8;font-size:14px;width:18px;text-align:center;}
    .fm-opcao input.txt{flex:1;border:0;background:transparent;border-bottom:1px solid transparent;padding:4px 6px;font-size:13px;}
    .fm-opcao input.txt:focus{outline:none;border-bottom-color:#0a66c2;background:#fff;}
    .fm-opcao .rm{background:none;border:0;color:#94a3b8;cursor:pointer;font-size:14px;visibility:hidden;}
    .fm-opcao:hover .rm{visibility:visible;}
    .fm-opcao .rm:hover{color:#dc2626;}
    .fm-add-opcao{display:flex;align-items:center;gap:8px;padding:6px 0;color:#64748b;cursor:pointer;font-size:13px;}
    .fm-add-opcao:hover{color:#0a66c2;}

    .fm-perg-footer{display:flex;justify-content:space-between;align-items:center;margin-top:14px;padding-top:14px;border-top:1px solid #f1f5f9;}
    .fm-perg-footer .left{display:flex;gap:14px;align-items:center;font-size:13px;}
    .fm-perg-footer label{font-size:13px;color:#475569;font-weight:400;cursor:pointer;display:flex;align-items:center;gap:6px;}
    .fm-perg-footer .actions{display:flex;gap:6px;}
    .fm-perg-footer .actions button{background:none;border:0;color:#64748b;cursor:pointer;font-size:14px;padding:4px 8px;border-radius:4px;}
    .fm-perg-footer .actions button:hover{background:#f3f4f6;color:#0a66c2;}
    .fm-perg-footer .actions button.del:hover{color:#dc2626;}

    .fm-toolbar{position:sticky;top:0;z-index:10;background:rgba(241,241,241,.95);backdrop-filter:blur(8px);padding:12px 0;margin:-24px -18px 14px;padding-left:18px;padding-right:18px;border-bottom:1px solid #e5e7eb;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:10px;}
    .fm-toolbar .left{display:flex;align-items:center;gap:12px;}
    .fm-toolbar .left a{color:#475569;text-decoration:none;font-size:13px;}
    .fm-toolbar .left a:hover{color:#0a66c2;}
    .fm-toolbar .right{display:flex;gap:6px;}

    .fm-add-btn{display:flex;justify-content:center;margin:14px 0;}
    .fm-add-btn button{background:#0a66c2;color:#fff;border:0;border-radius:50%;width:48px;height:48px;font-size:20px;cursor:pointer;box-shadow:0 4px 12px rgba(10,102,194,.3);}
    .fm-add-btn button:hover{background:#084b8e;}

    .fm-status-pill{display:inline-block;padding:2px 10px;border-radius:6px;font-size:11px;font-weight:700;letter-spacing:.04em;text-transform:uppercase;}
    .fm-status-pill.rascunho{background:#fef3c7;color:#92400e;}
    .fm-status-pill.publicado{background:#dcfce7;color:#166534;}
    .fm-status-pill.encerrado{background:#e0e7ff;color:#3730a3;}
    .fm-status-pill.arquivado{background:#f1f5f9;color:#475569;}

    #fm-saving{font-size:12px;color:#64748b;display:none;align-items:center;gap:6px;}
    #fm-saving.show{display:inline-flex;}
    #fm-saved{font-size:12px;color:#16a34a;display:none;align-items:center;gap:6px;}
    #fm-saved.show{display:inline-flex;}
</style>

<div class="content">
    <div class="fm-page">

        <div class="fm-toolbar">
            <div class="left">
                <a href="<?php echo base_url('gestao_corporativa/Formulario'); ?>"><i class="fa fa-arrow-left"></i> Voltar</a>
                <span class="fm-status-pill <?php echo $form['status']; ?>" id="fm-status-pill"><?php echo $this->Formulario_model->get_status_label($form['status']); ?></span>
                <span id="fm-saving"><i class="fa fa-spinner fa-spin"></i> salvando…</span>
                <span id="fm-saved"><i class="fa fa-check"></i> salvo</span>
            </div>
            <div class="right">
                <a href="<?php echo base_url('formularios/web/' . $form['form_key']); ?>" target="_blank" class="btn btn-default btn-sm"><i class="fa fa-eye"></i> Pré-visualizar</a>
                <a href="<?php echo base_url('gestao_corporativa/Formulario/respostas/' . (int) $form['id']); ?>" class="btn btn-default btn-sm"><i class="fa fa-table"></i> Respostas</a>
                <button class="btn btn-info btn-sm" id="btn-publicar"><i class="fa fa-paper-plane"></i> <?php echo $form['status'] === 'publicado' ? 'Encerrar' : 'Publicar'; ?></button>
            </div>
        </div>

        <input type="hidden" id="form_id" value="<?php echo (int) $form['id']; ?>">

        <!-- Cabeçalho do formulário -->
        <div class="fm-card">
            <div class="fm-card-body">
                <input type="text" class="fm-titulo" id="fm-titulo" value="<?php echo html_escape($form['titulo']); ?>" placeholder="Título do formulário">
                <textarea class="fm-desc" id="fm-desc" placeholder="Descrição (opcional)"><?php echo html_escape($form['descricao']); ?></textarea>

                <div class="fm-meta-row">
                    <div>
                        <label>Status</label>
                        <select id="fm-status">
                            <?php foreach ($statuses as $s): ?>
                                <option value="<?php echo $s; ?>" <?php echo $form['status'] === $s ? 'selected' : ''; ?>><?php echo $this->Formulario_model->get_status_label($s); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label>Visibilidade</label>
                        <select id="fm-visibilidade">
                            <option value="publica" <?php echo $form['visibilidade'] === 'publica' ? 'selected' : ''; ?>>Pública (qualquer link)</option>
                            <option value="restrita" <?php echo $form['visibilidade'] === 'restrita' ? 'selected' : ''; ?>>Restrita (login obrigatório)</option>
                        </select>
                    </div>
                    <div>
                        <label>Mensagem após envio</label>
                        <input type="text" id="fm-msg" value="<?php echo html_escape($form['success_submit_msg']); ?>" placeholder="Obrigado pela sua resposta!">
                    </div>
                </div>

                <div class="fm-vinc-row">
                    <div>
                        <label style="font-size:11px;font-weight:600;color:#475569;text-transform:uppercase;">Projeto</label>
                        <select id="fm-project_id" class="select2">
                            <option value="">— sem vínculo —</option>
                            <?php foreach ($projects as $p): ?>
                                <option value="<?php echo (int) $p['id']; ?>" <?php echo (int) $form['project_id'] === (int) $p['id'] ? 'selected' : ''; ?>><?php echo html_escape($p['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label style="font-size:11px;font-weight:600;color:#475569;text-transform:uppercase;">Fase do projeto</label>
                        <select id="fm-fase_id" class="select2">
                            <option value="">— sem fase —</option>
                            <?php
                            $fases_iniciais = !empty($form['project_id']) ? $this->Projeto_fase_model->list_options($form['project_id']) : [];
                            foreach ($fases_iniciais as $fa): ?>
                                <option value="<?php echo (int) $fa['id']; ?>" <?php echo (int) $form['fase_id'] === (int) $fa['id'] ? 'selected' : ''; ?>><?php echo html_escape($fa['codigo_sequencial'] . ' ' . $fa['titulo']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lista de perguntas -->
        <div id="fm-perguntas">
            <?php foreach ($perguntas as $i => $p): ?>
                <?php $this->load->view('gestao_corporativa/formularios/_pergunta_row', ['p' => $p, 'i' => $i, 'tipos' => $tipos]); ?>
            <?php endforeach; ?>
        </div>

        <div class="fm-add-btn">
            <button title="Adicionar pergunta" id="btn-add-pergunta"><i class="fa fa-plus"></i></button>
        </div>

    </div>
</div>

<!-- Template de pergunta nova (vazia) -->
<template id="tpl-pergunta">
    <?php $this->load->view('gestao_corporativa/formularios/_pergunta_row', [
        'p' => ['id' => 0, 'title' => '', 'descricao' => '', 'tipo' => 'text', 'required' => 0, 'pagina' => 1, 'opcoes' => [], 'configuracao_arr' => []],
        'i' => 0,
        'tipos' => $tipos,
    ]); ?>
</template>

<?php init_tail(); ?>
<script src="<?php echo base_url() ?>assets/lte/plugins/select2/js/select2.full.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/jquery-ui/jquery-ui.min.js"></script>

<script>
$(function () {
    $('.select2').select2({width:'100%'});

    var FORM_ID = parseInt($('#form_id').val(), 10);
    var URL_BASE = '<?php echo base_url('gestao_corporativa/Formulario/'); ?>';
    var saveTimer = null;

    function setSaving(state) {
        if (state === 'saving') { $('#fm-saving').addClass('show'); $('#fm-saved').removeClass('show'); }
        if (state === 'saved')  { $('#fm-saving').removeClass('show'); $('#fm-saved').addClass('show'); setTimeout(function(){$('#fm-saved').removeClass('show');}, 1500); }
    }

    function debouncedSaveForm() {
        clearTimeout(saveTimer);
        saveTimer = setTimeout(saveForm, 600);
    }

    function saveForm() {
        setSaving('saving');
        $.post(URL_BASE + 'save', {
            id: FORM_ID,
            titulo: $('#fm-titulo').val(),
            descricao: $('#fm-desc').val(),
            success_submit_msg: $('#fm-msg').val(),
            status: $('#fm-status').val(),
            visibilidade: $('#fm-visibilidade').val(),
            project_id: $('#fm-project_id').val(),
            fase_id: $('#fm-fase_id').val(),
        }, function () {
            setSaving('saved');
            updateStatusPill();
        }, 'json');
    }

    function updateStatusPill() {
        var st = $('#fm-status').val();
        $('#fm-status-pill').removeClass('rascunho publicado encerrado arquivado').addClass(st)
            .text({rascunho:'Rascunho', publicado:'Publicado', encerrado:'Encerrado', arquivado:'Arquivado'}[st] || st);
        $('#btn-publicar').html(st === 'publicado' ? '<i class="fa fa-stop"></i> Encerrar' : '<i class="fa fa-paper-plane"></i> Publicar');
    }

    // Auto-save no cabeçalho
    $('#fm-titulo, #fm-desc, #fm-msg').on('input', debouncedSaveForm);
    $('#fm-status, #fm-visibilidade').on('change', saveForm);
    $('#fm-project_id').on('change', function () {
        var pid = $(this).val();
        var $f = $('#fm-fase_id');
        $f.empty().append('<option value="">— sem fase —</option>');
        if (pid) {
            $.getJSON('<?php echo base_url('gestao_corporativa/Projeto_fase/list_options'); ?>/' + pid, function (data) {
                (data || []).forEach(function (f) {
                    $f.append($('<option>').val(f.id).text(f.codigo_sequencial + ' ' + f.titulo));
                });
                $f.trigger('change.select2');
            });
        } else {
            $f.trigger('change.select2');
        }
        saveForm();
    });
    $('#fm-fase_id').on('change', saveForm);

    $('#btn-publicar').on('click', function () {
        var atual = $('#fm-status').val();
        $('#fm-status').val(atual === 'publicado' ? 'encerrado' : 'publicado').trigger('change');
    });

    /* ---------- Perguntas ---------- */

    $(document).on('click', '.fm-perg-collapsed', function () {
        $('.fm-perg').removeClass('active');
        $(this).closest('.fm-perg').addClass('active');
    });

    $('#btn-add-pergunta').on('click', function () {
        $.post(URL_BASE + 'pergunta_add', { form_id: FORM_ID, tipo: 'text' }, function (resp) {
            if (!resp.ok) return;
            var html = $('#tpl-pergunta').html();
            var $el = $(html).attr('data-id', resp.pergunta.id);
            $el.find('[name=id]').val(resp.pergunta.id);
            $('#fm-perguntas').append($el);
            $('.fm-perg').removeClass('active');
            $el.addClass('active');
            $el.find('.fm-perg-titulo').focus();
            renumerarPerguntas();
        }, 'json');
    });

    $(document).on('input change', '.fm-perg .auto-save', function () {
        var $perg = $(this).closest('.fm-perg');
        clearTimeout($perg.data('timer'));
        $perg.data('timer', setTimeout(function () { savePergunta($perg); }, 500));
    });

    function savePergunta($perg) {
        setSaving('saving');
        var pid = $perg.data('id');
        $.post(URL_BASE + 'pergunta_save', {
            id: pid,
            title: $perg.find('.fm-perg-titulo').val(),
            descricao: $perg.find('.fm-perg-desc').val(),
            tipo: $perg.find('.fm-perg-tipo').val(),
            required: $perg.find('.fm-perg-required').is(':checked') ? 1 : 0,
            pagina: $perg.find('.fm-perg-pagina').val() || 1,
            placeholder: $perg.find('.fm-perg-placeholder').val(),
        }, function () {
            setSaving('saved');
            atualizarBadgeColapsado($perg);
        }, 'json');
    }

    function atualizarBadgeColapsado($perg) {
        var titulo = $perg.find('.fm-perg-titulo').val() || '(sem título)';
        var tipo = $perg.find('.fm-perg-tipo option:selected').text();
        $perg.find('.fm-perg-collapsed .ttl-text').text(titulo);
        $perg.find('.fm-perg-collapsed .tipo-text').text(tipo);
        $perg.find('.fm-perg-collapsed .req-text').toggle($perg.find('.fm-perg-required').is(':checked'));
    }

    $(document).on('change', '.fm-perg-tipo', function () {
        var $perg = $(this).closest('.fm-perg');
        var tipo = $(this).val();
        $perg.find('.fm-opcoes-wrap').toggle(['radio','checkbox','select'].indexOf(tipo) >= 0);
        $perg.find('.fm-opcao .marker').text(
            tipo === 'radio' ? '◯' :
            tipo === 'checkbox' ? '☐' :
            tipo === 'select' ? '▾' : '·'
        );
    });

    $(document).on('click', '.fm-perg-del', function () {
        var $perg = $(this).closest('.fm-perg');
        if (!confirm('Excluir esta pergunta?')) return;
        $.post(URL_BASE + 'pergunta_delete', { id: $perg.data('id') }, function () {
            $perg.remove();
            renumerarPerguntas();
        }, 'json');
    });

    $(document).on('click', '.fm-perg-dup', function () {
        // duplicar — copia título e tipo, cria nova pergunta
        var $perg = $(this).closest('.fm-perg');
        $.post(URL_BASE + 'pergunta_add', {
            form_id: FORM_ID,
            tipo: $perg.find('.fm-perg-tipo').val(),
            title: $perg.find('.fm-perg-titulo').val() + ' (cópia)',
        }, function (resp) {
            if (!resp.ok) return;
            location.reload();
        }, 'json');
    });

    function renumerarPerguntas() {
        $('#fm-perguntas .fm-perg').each(function (i) {
            $(this).find('.fm-perg-collapsed .num').text((i + 1) + '.');
            $(this).find('.fm-perg-num').text(i + 1);
        });
    }
    renumerarPerguntas();

    // Drag & drop reordenar
    if ($.fn.sortable) {
        $('#fm-perguntas').sortable({
            handle: '.fm-perg-drag',
            placeholder: 'fm-perg-ph',
            update: function () {
                var ordem = [];
                $('#fm-perguntas .fm-perg').each(function () { ordem.push($(this).data('id')); });
                $.post(URL_BASE + 'pergunta_reorder', { form_id: FORM_ID, ordem: ordem });
                renumerarPerguntas();
            }
        });
    }

    /* ---------- Opções ---------- */

    $(document).on('click', '.fm-add-opcao', function () {
        var $perg = $(this).closest('.fm-perg');
        var pid = $perg.data('id');
        $.post(URL_BASE + 'opcao_add', { pergunta_id: pid, name: 'Opção' }, function (resp) {
            if (!resp.ok) return;
            var tipo = $perg.find('.fm-perg-tipo').val();
            var marker = tipo === 'radio' ? '◯' : tipo === 'checkbox' ? '☐' : tipo === 'select' ? '▾' : '·';
            var $row = $('<div class="fm-opcao" data-id="' + resp.id + '">' +
                '<span class="marker">' + marker + '</span>' +
                '<input type="text" class="txt" value="' + resp.name + '">' +
                '<button class="rm" type="button"><i class="fa fa-times"></i></button>' +
                '</div>');
            $perg.find('.fm-opcoes').append($row);
            $row.find('.txt').focus().select();
        }, 'json');
    });

    $(document).on('input', '.fm-opcao .txt', function () {
        var $opc = $(this).closest('.fm-opcao');
        clearTimeout($opc.data('timer'));
        $opc.data('timer', setTimeout(function () {
            $.post(URL_BASE + 'opcao_save', { id: $opc.data('id'), name: $opc.find('.txt').val() });
        }, 500));
    });

    $(document).on('click', '.fm-opcao .rm', function () {
        var $opc = $(this).closest('.fm-opcao');
        $.post(URL_BASE + 'opcao_delete', { id: $opc.data('id') });
        $opc.remove();
    });
});
</script>
</body>
</html>
