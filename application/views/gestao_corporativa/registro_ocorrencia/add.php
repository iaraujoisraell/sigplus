<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head_intranet_novo(); ?>
<?php $this->load->view('gestao_corporativa/css_background'); ?>

<style>
    .ro-section { background:#fff; border:1px solid #e6ebef; border-radius:8px; padding:18px 22px; margin-bottom:14px; }
    .ro-section-title { font-size:13px; color:#1f2d3d; font-weight:700; text-transform:uppercase; letter-spacing:.04em; margin:0 0 14px; padding-bottom:10px; border-bottom:1px solid #eef1f4; }
    .ro-section-title .step { display:inline-block; width:22px; height:22px; line-height:22px; text-align:center; background:#3b82f6; color:#fff; border-radius:50%; font-size:12px; margin-right:8px; }
    .ro-sla-card { background:#f8fafc; border:1px solid #e2e8f0; border-radius:6px; padding:14px; height:100%; }
    .ro-sla-card .sla-label { font-size:10px; color:#64748b; font-weight:700; text-transform:uppercase; letter-spacing:.05em; margin-top:8px; }
    .ro-sla-card .sla-value { font-size:13px; color:#1f2d3d; font-weight:600; margin-top:2px; }
    .ro-sla-card .sla-empty { color:#94a3b8; font-style:italic; font-size:13px; padding:30px 10px; text-align:center; }
    .ro-action-bar { display:flex; justify-content:space-between; align-items:center; padding:14px 0; }
    .ro-action-bar .draft-status { font-size:11px; color:#94a3b8; }
    .ro-chip-atendimento { display:inline-block; background:#eff6ff; border:1px solid #bfdbfe; color:#1d4ed8; padding:4px 10px; border-radius:14px; font-size:12px; font-weight:600; }
</style>

<div class="content">
    <div class="row">
        <?php echo form_open_multipart($this->uri->uri_string(), ['id' => 'ro_form', 'onsubmit' => "document.getElementById('disabled_top').disabled = true; document.getElementById('disabled_bot') && (document.getElementById('disabled_bot').disabled = true);"]); ?>
        <input type="hidden" name="registro_atendimento_id" id="registro_atendimento_id" value="<?php echo $registro_atendimento_id; ?>">

        <div class="col-md-12">
            <ol class="breadcrumb" style="background-color: white;">
                <li><a href="<?= base_url('gestao_corporativa/intranet'); ?>"><i class="fa fa-home"></i> Home</a></li>
                <li><a href="<?= base_url('gestao_corporativa/Registro_ocorrencia'); ?>"><i class="fa fa-backward"></i> Registros de Ocorrência</a></li>
                <li class="active">Novo Registro</li>
            </ol>
        </div>

        <div class="col-md-12">
            <div class="ro-action-bar">
                <div>
                    <h4 style="margin:0;font-weight:700;">Novo Registro de Ocorrência</h4>
                    <?php if (!empty($registro_atendimento_id)): ?>
                        <span class="ro-chip-atendimento mtop5"><i class="fa fa-link"></i> Vinculado ao atendimento #<?= (int) $registro_atendimento_id; ?></span>
                    <?php endif; ?>
                </div>
                <div>
                    <span class="draft-status" id="draft-status"></span>
                    <a href="<?= base_url('gestao_corporativa/Registro_ocorrencia'); ?>" class="btn btn-default">Cancelar</a>
                    <button type="button" class="btn btn-default" id="btn-draft-clear" title="Descartar rascunho local"><i class="fa fa-trash"></i></button>
                    <button type="submit" class="btn btn-primary" id="disabled_top"><i class="fa fa-save"></i> Salvar</button>
                </div>
            </div>
        </div>

        <div class="col-md-12">

            <div class="ro-section">
                <div class="ro-section-title"><span class="step">1</span> Categoria e Contexto</div>
                <div class="row">
                    <div class="col-md-7">
                        <?php
                        echo render_select('categoria_id', $categorias, ['id', 'titulo', 'name'], 'Categoria', [], ['required' => 'true']);
                        ?>
                    </div>
                    <div class="col-md-5">
                        <div class="ro-sla-card" id="sla-card">
                            <div class="sla-empty">
                                <i class="fa fa-arrow-left"></i> Escolha uma categoria pra ver setor responsável, prazo e quem será notificado.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="ro-section">
                <div class="ro-section-title"><span class="step">2</span> Detalhes da Ocorrência</div>
                <div class="row">
                    <div class="col-md-12">
                        <?php echo render_input('assunto', 'Assunto', '', 'text', ['required' => 'true']); ?>
                    </div>
                    <div class="col-md-4">
                        <?php echo render_date_input('data_ocorrido', 'Data do Ocorrido', ''); ?>
                    </div>
                    <div class="col-md-4">
                        <?php
                        $priorities['callback_translate'] = 'ticket_priority_translate';
                        echo render_select('priority', $priorities, ['priorityid', 'name'], 'ticket_settings_priority', hooks()->apply_filters('new_ticket_priority_selected', 3), ['required' => 'true']);
                        ?>
                    </div>
                    <div class="col-md-12">
                        <?php echo render_textarea('descricao', 'Relato Detalhado', '', [], [], '', 'tinymce'); ?>
                    </div>
                </div>

                <div id="trocar"></div>
            </div>

            <div class="ro-section">
                <div class="ro-section-title"><span class="step">3</span> Anexos</div>
                <div class="row attachments">
                    <div class="attachment">
                        <div class="col-md-12 mbot15">
                            <div class="form-group">
                                <label for="attachments" class="control-label"><?php echo _l('ticket_add_attachments'); ?></label>
                                <div class="input-group">
                                    <input type="file" class="form-control" name="attachments[0]" data-target="assets/intranet/arquivos/ro_arquivos/" data-name_value="RO-ATTACHMENT<?php echo uniqid(); ?>">
                                    <span class="input-group-btn">
                                        <button class="btn btn-success add_more_attachments p8-half" data-max="5" type="button"><i class="fa fa-plus"></i></button>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="ro-action-bar">
                <div>
                    <a href="<?= base_url('gestao_corporativa/Registro_ocorrencia'); ?>" class="btn btn-default">Cancelar</a>
                </div>
                <div>
                    <button type="submit" class="btn btn-primary" id="disabled_bot"><i class="fa fa-save"></i> Salvar Registro</button>
                </div>
            </div>
        </div>

        <?php echo form_close(); ?>
    </div>
</div>

<?php init_tail(); ?>

<script>
(function () {
    const STAFF_ID = '<?= (int) get_staff_user_id(); ?>';
    const URL_META = '<?= base_url('gestao_corporativa/Registro_ocorrencia/get_categoria_meta'); ?>';

    // ===== Card de SLA =====
    function renderSlaCard(meta) {
        const $card = $('#sla-card');
        if (!meta || !meta.ok) {
            $card.html('<div class="sla-empty">Não foi possível carregar dados da categoria.</div>');
            return;
        }
        const escape = s => $('<div>').text(s || '').html();
        let notif = '';
        if (meta.notificados && meta.notificados.length) {
            const first = meta.notificados.slice(0, 3).map(escape).join(', ');
            const more = meta.notificados.length > 3 ? ' <span class="text-muted">+ ' + (meta.notificados.length - 3) + '</span>' : '';
            notif = first + more;
        } else {
            notif = '<span class="text-muted">ninguém configurado</span>';
        }
        const atuantes = (meta.atuantes && meta.atuantes.length)
            ? meta.atuantes.map(escape).join(' · ')
            : '<span class="text-muted">sem atuantes</span>';
        const prazoLabel = meta.prazo
            ? meta.prazo + ' dia(s) — vence em ' + meta.validade_br
            : '<span class="text-muted">sem prazo definido</span>';

        $card.html(
            '<div class="sla-label">Setor responsável</div>' +
            '<div class="sla-value">' + escape(meta.setor_nome || '—') + '</div>' +
            '<div class="sla-label">Prazo / Validade prevista</div>' +
            '<div class="sla-value">' + prazoLabel + '</div>' +
            '<div class="sla-label">Será notificado</div>' +
            '<div class="sla-value">' + notif + '</div>' +
            '<div class="sla-label">Atuantes</div>' +
            '<div class="sla-value">' + atuantes + '</div>'
        );
    }

    $(document).on('change', '#categoria_id', function () {
        const id = $(this).val();
        if (!id) return;

        // Meta da categoria (paralelo)
        $.getJSON(URL_META + '/' + encodeURIComponent(id)).done(renderSlaCard).fail(() => {
            $('#sla-card').html('<div class="sla-empty">Falha ao carregar dados da categoria.</div>');
        });

        // Campos dinâmicos (igual ao fluxo anterior)
        $.ajax({
            type: 'POST',
            url: '<?= base_url('gestao_corporativa/categorias_campos/retorno_categoria_campos?rel_type=r.o'); ?>',
            data: { categoria_id: id },
            success: function (data) {
                $('#trocar').html(data);
                // O partial injeta um botão "Salvar" próprio; já temos no topo e na base, escondemos pra não duplicar
                $('#trocar').find('button[type="submit"]').closest('.panel_s').hide();
            }
        });
    });

    // ===== Rascunho local (localStorage) =====
    const DRAFT_KEY = 'ro_draft_' + STAFF_ID + '_<?= (int) $registro_atendimento_id; ?>';
    const DRAFT_FIELDS = ['categoria_id', 'assunto', 'data_ocorrido', 'priority', 'descricao'];

    function saveDraft() {
        const payload = { _ts: Date.now() };
        DRAFT_FIELDS.forEach(name => {
            const $el = $('[name="' + name + '"]');
            if ($el.length) payload[name] = $el.val();
        });
        try {
            localStorage.setItem(DRAFT_KEY, JSON.stringify(payload));
            $('#draft-status').text('rascunho salvo · ' + new Date().toLocaleTimeString().slice(0, 5));
        } catch (e) {}
    }

    function loadDraft() {
        try {
            const raw = localStorage.getItem(DRAFT_KEY);
            if (!raw) return null;
            const p = JSON.parse(raw);
            if (!p || !p._ts) return null;
            // descarta rascunho > 24h
            if (Date.now() - p._ts > 24 * 3600 * 1000) {
                localStorage.removeItem(DRAFT_KEY);
                return null;
            }
            return p;
        } catch (e) { return null; }
    }

    function clearDraft() {
        try { localStorage.removeItem(DRAFT_KEY); } catch (e) {}
        $('#draft-status').text('');
    }

    function applyDraft(p) {
        DRAFT_FIELDS.forEach(name => {
            if (!(name in p)) return;
            const $el = $('[name="' + name + '"]');
            if (!$el.length) return;
            $el.val(p[name]);
            if ($el.is('select')) $el.selectpicker && $el.selectpicker('refresh');
        });
        // Dispara change da categoria pra carregar campos dinâmicos + card SLA
        if (p.categoria_id) $('#categoria_id').trigger('change');
    }

    $(function () {
        const p = loadDraft();
        if (p) {
            const minutos = Math.round((Date.now() - p._ts) / 60000);
            if (confirm('Encontrei um rascunho de ' + minutos + ' min atrás. Recuperar?')) {
                applyDraft(p);
            } else {
                clearDraft();
            }
        }
        // autosave a cada 5s se algo mudou
        setInterval(saveDraft, 5000);
        $('#btn-draft-clear').on('click', function () {
            if (confirm('Descartar rascunho local?')) clearDraft();
        });
    });

    // ===== Submit (upload de attachments antes de submeter o form de fato) =====
    $(function () {
        $('#ro_form').on('submit', function (e) {
            e.preventDefault();
            const uploadForm = $('#ro_form');
            const formData = new FormData($('#ro_form')[0]);
            const fieldToTargetMap = {};
            const fieldValueMap = {};
            uploadForm.find('input[type="file"]').each(function () {
                const field = $(this);
                fieldToTargetMap[field.attr('name')] = field.data('target');
                fieldValueMap[field.attr('name')] = field.data('name_value');
            });
            formData.append('fieldToTargetMap', JSON.stringify(fieldToTargetMap));
            formData.append('fieldValueMap', JSON.stringify(fieldValueMap));
            formData.append('target', '0');
            $.ajax({
                type: 'POST',
                url: '<?= base_url('controle_upload/All.php'); ?>',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    try {
                        const obj = JSON.parse(response);
                        $('form input[type="file"]').each(function () {
                            const fileInput = $(this);
                            const inputName = fileInput.attr('name');
                            fileInput.attr('type', 'text');
                            fileInput.val(obj[inputName] || '');
                        });
                    } catch (err) {}
                    clearDraft();
                    $('#ro_form').off('submit').submit();
                }
            });
        });
    });
})();
</script>
