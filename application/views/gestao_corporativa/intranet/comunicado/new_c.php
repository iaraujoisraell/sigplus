<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head_intranet_novo(); ?>
<?php $this->load->view('gestao_corporativa/css_background'); ?>

<link rel="stylesheet" href="<?php echo base_url() ?>assets/lte/plugins/select2/css/select2.min.css">
<link rel="stylesheet" href="<?php echo base_url() ?>assets/lte/plugins/summernote/summernote-bs4.min.css">

<style>
    .ci-card{background:#fff;border:1px solid #e5e7eb;border-radius:10px;margin-bottom:14px;}
    .ci-card-header{padding:12px 18px;border-bottom:1px solid #eef1f4;font-weight:600;color:#1f2937;font-size:14px;}
    .ci-card-body{padding:16px 18px;}
    .ci-meta-row{display:grid;grid-template-columns:1fr 1fr 1fr 1fr;gap:14px;}
    @media (max-width:768px){.ci-meta-row{grid-template-columns:1fr 1fr;}}
    .ci-attach-list{margin-top:8px;}
    .ci-attach-list .file-row{display:flex;align-items:center;gap:8px;padding:6px 10px;background:#f9fafb;border-radius:6px;margin-bottom:4px;font-size:13px;}

    /* Dual listbox */
    .dual-list{display:grid;grid-template-columns:1fr 60px 1fr;gap:10px;align-items:stretch;}
    .dual-list-pane{border:1px solid #d0d5dd;border-radius:8px;display:flex;flex-direction:column;overflow:hidden;background:#fff;}
    .dual-list-pane header{padding:8px 12px;background:#f8fafc;border-bottom:1px solid #e5e7eb;font-size:12px;font-weight:600;color:#475569;display:flex;justify-content:space-between;align-items:center;}
    .dual-list-pane header .count{background:#0a66c2;color:#fff;border-radius:999px;padding:1px 8px;font-size:11px;}
    .dual-list-pane input.search{border:0;border-bottom:1px solid #eef1f4;padding:8px 12px;font-size:13px;outline:none;}
    .dual-list-pane input.search:focus{border-bottom-color:#0a66c2;}
    .dual-list-pane .items{flex:1;overflow-y:auto;max-height:280px;min-height:200px;padding:4px 0;}
    .dual-list-pane .group-label{font-size:11px;font-weight:700;color:#94a3b8;text-transform:uppercase;padding:6px 12px 2px;margin-top:4px;border-top:1px dashed #eef1f4;display:flex;justify-content:space-between;align-items:center;cursor:pointer;}
    .dual-list-pane .group-label:hover{color:#0a66c2;background:#f0f6fb;}
    .dual-list-pane .group-label .add-all{font-size:10px;color:#0a66c2;font-weight:600;}
    .dual-list-pane .item{padding:6px 12px;cursor:pointer;font-size:13px;display:flex;justify-content:space-between;align-items:center;}
    .dual-list-pane .item:hover{background:#eaf2fb;}
    .dual-list-pane .item.selected{background:#dceaff;color:#0a66c2;font-weight:500;}
    .dual-list-pane .item .small{color:#94a3b8;font-size:11px;}
    .dual-list-pane .empty{padding:18px;text-align:center;color:#94a3b8;font-size:12px;}
    .dual-list-actions{display:flex;flex-direction:column;justify-content:center;gap:6px;}
    .dual-list-actions button{border:1px solid #d0d5dd;background:#fff;border-radius:6px;padding:6px;cursor:pointer;color:#475569;}
    .dual-list-actions button:hover{background:#0a66c2;color:#fff;border-color:#0a66c2;}
    @media (max-width:768px){.dual-list{grid-template-columns:1fr;}.dual-list-actions{flex-direction:row;justify-content:center;}}

    /* Summernote tweaks */
    .note-editor.note-frame{border:1px solid #d0d5dd;border-radius:6px;}
    .note-editor.note-frame .note-editing-area .note-editable{min-height:280px;}
</style>

<div class="content">
    <div class="row">
        <?php echo form_open_multipart('gestao_corporativa/intra/Comunicado/add_comunicado', ['id' => 'form-novo-ci']); ?>

        <div class="col-md-12">
            <ol class="breadcrumb" style="background-color: white;">
                <li><a href="<?= base_url('gestao_corporativa/intranet'); ?>"><i class="fa fa-home"></i> Home</a></li>
                <li><a href="<?= base_url('gestao_corporativa/intra/comunicado'); ?>">Comunicados Internos</a></li>
                <li class="active">Novo</li>
            </ol>
        </div>

        <div class="col-md-12">

            <div class="ci-card">
                <div class="ci-card-header">Informações</div>
                <div class="ci-card-body">
                    <div class="form-group">
                        <label class="control-label">Título <span class="text-danger">*</span></label>
                        <input type="text" name="titulo" class="form-control" required maxlength="250">
                    </div>

                    <div class="ci-meta-row">
                        <div class="form-group">
                            <label class="control-label">Setor <span class="text-danger">*</span></label>
                            <?php
                            $this->load->model('Departments_model');
                            $departments = $this->Departments_model->get_staff_departments();
                            ?>
                            <select name="setor_id" class="form-control" required>
                                <option value="">— selecionar —</option>
                                <?php foreach ($departments as $d): ?>
                                    <option value="<?php echo (int) $d['departmentid']; ?>"><?php echo html_escape($d['name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="control-label">Categoria</label>
                            <select name="categoria" class="form-control">
                                <option value="">— nenhuma —</option>
                                <option value="informativo">Informativo</option>
                                <option value="norma">Norma / Procedimento</option>
                                <option value="urgente">Urgente</option>
                                <option value="treinamento">Treinamento</option>
                                <option value="outro">Outro</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="control-label">Prioridade</label>
                            <select name="prioridade" class="form-control">
                                <option value="normal" selected>Normal</option>
                                <option value="alta">Alta</option>
                                <option value="baixa">Baixa</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="control-label">Expira em</label>
                            <input type="date" name="dt_expiracao" class="form-control">
                        </div>
                    </div>

                    <div class="form-group" style="margin-top:6px;">
                        <label class="control-label">Descrição</label>
                        <textarea id="descricao" name="descricao"></textarea>
                    </div>

                    <div class="form-group">
                        <label class="control-label">Anexos</label>
                        <input type="file" name="attachments[]" id="ci-files" multiple class="form-control" accept="<?php echo get_ticket_form_accepted_mimes(); ?>">
                        <div class="ci-attach-list" id="ci-attach-list"></div>
                    </div>

                    <div class="form-group">
                        <label class="checkbox-inline">
                            <input type="checkbox" name="retorno" value="1"> Exigir retorno por escrito ao dar ciência
                        </label>
                    </div>
                </div>
            </div>

            <div class="ci-card">
                <div class="ci-card-header">Destinatários <span class="text-danger">*</span></div>
                <div class="ci-card-body">
                    <?php
                    $departments_staffs = $this->Intranet_model->get_departamentos_staffs_selecionados();
                    $grouped = [];
                    foreach ($departments_staffs as $v) {
                        $key = $v['name'] ?: '(sem setor)';
                        $grouped[$key][] = $v;
                    }
                    ksort($grouped);

                    $dest_data = [];
                    foreach ($grouped as $groupName => $items) {
                        foreach ($items as $v) {
                            $dest_data[] = [
                                'value' => $v['staffid'] . '-' . $v['staffdepartmentid'],
                                'name'  => trim($v['firstname'] . ' ' . $v['lastname']),
                                'group' => $groupName,
                            ];
                        }
                    }

                    $this->load->model('Staff_model');
                    $staffs = $this->Staff_model->get();
                    $cc_data = [];
                    foreach ($staffs as $staff) {
                        $cc_data[] = [
                            'value' => (int) $staff['staffid'],
                            'name'  => trim($staff['firstname'] . ' ' . $staff['lastname']),
                            'group' => '',
                        ];
                    }
                    ?>

                    <div class="dual-list" data-target="for_staffs[]" data-source='<?php echo htmlspecialchars(json_encode($dest_data), ENT_QUOTES); ?>'>
                        <div class="dual-list-pane js-pane-available">
                            <header>Disponíveis <span class="count">0</span></header>
                            <input type="text" class="search" placeholder="Buscar pessoa ou setor...">
                            <div class="items"></div>
                        </div>
                        <div class="dual-list-actions">
                            <button type="button" class="js-add-all" title="Adicionar todos visíveis">»»</button>
                            <button type="button" class="js-add" title="Adicionar selecionados">»</button>
                            <button type="button" class="js-remove" title="Remover selecionados">«</button>
                            <button type="button" class="js-remove-all" title="Remover todos">««</button>
                        </div>
                        <div class="dual-list-pane js-pane-selected">
                            <header>Selecionados <span class="count">0</span></header>
                            <input type="text" class="search" placeholder="Buscar nos selecionados...">
                            <div class="items"></div>
                        </div>
                    </div>

                    <div style="margin-top:22px;">
                        <label class="control-label">Cópia (CC)</label>
                        <div class="dual-list" data-target="cc[]" data-source='<?php echo htmlspecialchars(json_encode($cc_data), ENT_QUOTES); ?>'>
                            <div class="dual-list-pane js-pane-available">
                                <header>Disponíveis <span class="count">0</span></header>
                                <input type="text" class="search" placeholder="Buscar pessoa...">
                                <div class="items"></div>
                            </div>
                            <div class="dual-list-actions">
                                <button type="button" class="js-add-all" title="Adicionar todos visíveis">»»</button>
                                <button type="button" class="js-add" title="Adicionar selecionados">»</button>
                                <button type="button" class="js-remove" title="Remover selecionados">«</button>
                                <button type="button" class="js-remove-all" title="Remover todos">««</button>
                            </div>
                            <div class="dual-list-pane js-pane-selected">
                                <header>Selecionados <span class="count">0</span></header>
                                <input type="text" class="search" placeholder="Buscar nos selecionados...">
                                <div class="items"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="ci-card">
                <div class="ci-card-body" style="text-align:right;">
                    <button type="submit" name="action" value="draft" class="btn btn-default">
                        <i class="fa fa-save"></i> Salvar rascunho
                    </button>
                    <button type="submit" name="action" value="publish" class="btn btn-info">
                        <i class="fa fa-paper-plane"></i> Publicar e enviar
                    </button>
                </div>
            </div>
        </div>

        <?php echo form_close(); ?>
    </div>
</div>

<?php init_tail(); ?>

<script src="<?php echo base_url() ?>assets/lte/plugins/summernote/summernote-bs4.min.js"></script>
<script src="<?php echo base_url() ?>assets/lte/plugins/summernote/lang/summernote-pt-BR.min.js"></script>

<script>
$(function () {
    // ===== Editor de descrição =====
    $('#descricao').summernote({
        lang: 'pt-BR',
        height: 280,
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'italic', 'underline', 'clear']],
            ['fontsize', ['fontsize']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['insert', ['link', 'picture']],
            ['view', ['fullscreen', 'codeview']],
        ],
        placeholder: 'Escreva o comunicado...'
    });

    // ===== Dual listbox =====
    function escHtml(s){ return String(s).replace(/[&<>"']/g, function(c){return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c];}); }

    $('.dual-list').each(function () {
        var $root = $(this);
        var name = $root.data('target');
        var source = $root.data('source') || [];
        var hasGroups = source.some(function(it){ return it.group && it.group !== ''; });

        var state = {
            available: source.map(function(x){ return Object.assign({}, x); }),
            selected: []
        };

        function renderPane(which, filter) {
            var $items = $root.find('.js-pane-' + which + ' .items');
            var data = state[which];
            filter = (filter || '').toLowerCase();
            if (filter) {
                data = data.filter(function (it) {
                    return it.name.toLowerCase().indexOf(filter) !== -1 ||
                           (it.group || '').toLowerCase().indexOf(filter) !== -1;
                });
            }

            if (data.length === 0) {
                $items.html('<div class="empty">' + (which === 'available' ? 'Nada disponível' : 'Nenhum selecionado') + '</div>');
            } else {
                var html = '';
                if (hasGroups && which === 'available') {
                    var byGroup = {};
                    data.forEach(function (it) {
                        var g = it.group || '(sem setor)';
                        (byGroup[g] = byGroup[g] || []).push(it);
                    });
                    Object.keys(byGroup).sort().forEach(function (g) {
                        html += '<div class="group-label" data-group="' + escHtml(g) + '"><span>' + escHtml(g) + ' (' + byGroup[g].length + ')</span><span class="add-all">+ todos</span></div>';
                        byGroup[g].forEach(function (it) {
                            html += '<div class="item" data-value="' + escHtml(it.value) + '"><span>' + escHtml(it.name) + '</span></div>';
                        });
                    });
                } else {
                    data.forEach(function (it) {
                        html += '<div class="item" data-value="' + escHtml(it.value) + '"><span>' + escHtml(it.name) + '</span>' + (it.group ? '<span class="small">' + escHtml(it.group) + '</span>' : '') + '</div>';
                    });
                }
                $items.html(html);
            }
            $root.find('.js-pane-' + which + ' .count').text(state[which].length);
        }

        function move(direction, all) {
            var src = direction === 'add' ? 'available' : 'selected';
            var dst = direction === 'add' ? 'selected' : 'available';
            var $srcItems = $root.find('.js-pane-' + src + ' .item.selected');
            var values;

            if (all) {
                var $visible = $root.find('.js-pane-' + src + ' .items .item');
                values = $visible.map(function () { return $(this).data('value') + ''; }).get();
            } else {
                values = $srcItems.map(function () { return $(this).data('value') + ''; }).get();
            }
            if (values.length === 0) return;

            var moving = state[src].filter(function (it) { return values.indexOf(String(it.value)) !== -1; });
            state[src] = state[src].filter(function (it) { return values.indexOf(String(it.value)) === -1; });
            state[dst] = state[dst].concat(moving);

            renderPane(src, $root.find('.js-pane-' + src + ' .search').val());
            renderPane(dst, $root.find('.js-pane-' + dst + ' .search').val());
            syncHidden();
        }

        function syncHidden() {
            $root.find('.dual-hidden-input').remove();
            state.selected.forEach(function (it) {
                $('<input type="hidden" class="dual-hidden-input">')
                    .attr('name', name)
                    .attr('value', it.value)
                    .appendTo($root);
            });
        }

        // Eventos
        $root.on('click', '.item', function (e) {
            $(this).toggleClass('selected');
        });

        $root.on('dblclick', '.js-pane-available .item', function () {
            $(this).addClass('selected');
            move('add', false);
        });
        $root.on('dblclick', '.js-pane-selected .item', function () {
            $(this).addClass('selected');
            move('remove', false);
        });

        $root.on('click', '.js-pane-available .group-label', function () {
            var g = $(this).data('group');
            $root.find('.js-pane-available .item').each(function () {
                var $it = $(this);
                var v = $it.data('value') + '';
                var found = state.available.find(function (x) { return String(x.value) === v && (x.group || '(sem setor)') === g; });
                if (found) $it.addClass('selected');
            });
            move('add', false);
        });

        $root.find('.js-add').on('click', function () { move('add', false); });
        $root.find('.js-remove').on('click', function () { move('remove', false); });
        $root.find('.js-add-all').on('click', function () { move('add', true); });
        $root.find('.js-remove-all').on('click', function () { move('remove', true); });

        $root.find('.js-pane-available .search').on('input', function () { renderPane('available', this.value); });
        $root.find('.js-pane-selected .search').on('input', function () { renderPane('selected', this.value); });

        renderPane('available', '');
        renderPane('selected', '');
        syncHidden();
    });

    // ===== Anexos =====
    var $list = $('#ci-attach-list');
    $('#ci-files').on('change', function () {
        $list.empty();
        Array.from(this.files).forEach(function (f) {
            $list.append('<div class="file-row"><i class="fa fa-paperclip"></i> ' + f.name + ' <span class="text-muted small">(' + Math.round(f.size/1024) + ' KB)</span></div>');
        });
    });

    // ===== Submit =====
    $('#form-novo-ci').on('submit', function (e) {
        var hasDest = $(this).find('input[name="for_staffs[]"]').length > 0;
        var action = $(document.activeElement).val() || 'publish';
        if (action === 'publish' && !hasDest) {
            e.preventDefault();
            alert('Selecione ao menos um destinatário antes de publicar.');
        }
    });
});
</script>
