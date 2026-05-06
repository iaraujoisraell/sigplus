<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head_intranet_novo(); ?>
<?php $this->load->view('gestao_corporativa/css_background'); ?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jstree@3.3.16/dist/themes/default/style.min.css">
<style>
    .wf-card { background: #fff; border: 1px solid #e6ebef; border-radius: 8px; }
    .wf-header { padding: 18px 22px; border-bottom: 1px solid #eef1f4; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; }
    .wf-header h2 { margin: 0 0 2px; font-size: 18px; font-weight: 600; color: #1f2d3d; }
    .wf-header .breadcrumbish { color: #7a8a99; font-size: 12px; }
    .wf-chips { display: flex; gap: 8px; flex-wrap: wrap; }
    .wf-chip { background: #f4f6f8; border-radius: 6px; padding: 6px 10px; font-size: 12px; color: #4a5b6a; }
    .wf-chip strong { color: #1f2d3d; }
    .wf-body { display: grid; grid-template-columns: 1fr 380px; gap: 0; min-height: 60vh; }
    .wf-tree-pane { padding: 14px 18px; border-right: 1px solid #eef1f4; }
    .wf-tree-toolbar { display: flex; gap: 8px; margin-bottom: 10px; align-items: center; }
    .wf-tree-toolbar .spacer { flex: 1; }
    .wf-tree-toolbar input { width: 220px; }
    .wf-side { padding: 18px 20px; background: #fafbfc; }
    .wf-side h4 { font-size: 14px; font-weight: 600; margin: 0 0 12px; color: #1f2d3d; text-transform: uppercase; letter-spacing: .03em; }
    .wf-side .empty { color: #9aa7b3; font-size: 13px; padding: 30px 0; text-align: center; }
    .wf-side .form-group label { font-size: 12px; color: #4a5b6a; font-weight: 500; }
    .wf-side .actions { display: flex; gap: 8px; margin-top: 14px; }
    .wf-side .actions .btn { flex: 1; }
    .wf-side .extras { margin-top: 18px; border-top: 1px dashed #e1e7ec; padding-top: 14px; }
    .wf-side .extras a { display: inline-block; margin-right: 6px; margin-bottom: 6px; }
    .badge { display: inline-block; padding: 2px 7px; font-size: 10px; border-radius: 4px; color: #fff; font-weight: 600; vertical-align: middle; }
    .badge-info { background: #1ea7c5; }
    .badge-success { background: #2ec27e; }
    .badge-danger { background: #d04545; }
    .jstree-default .jstree-anchor { height: auto; padding: 4px 6px; line-height: 1.4; }
    .jstree-default .jstree-wholerow { height: 100%; }
    .jstree-default .jstree-clicked { background: #e3f0ff; box-shadow: none; }
    .jstree-default .jstree-hovered { background: #f0f6ff; }
    @media (max-width: 992px) { .wf-body { grid-template-columns: 1fr; } .wf-side { border-top: 1px solid #eef1f4; } }
</style>

<div class="content">
    <div class="row">
        <div class="col-md-12">
            <ol class="breadcrumb" style="background-color: white;">
                <li><a href="<?= base_url('gestao_corporativa/intranet'); ?>"><i class="fa fa-home"></i> Home</a></li>
                <li><a href="<?= base_url('gestao_corporativa/intranet_admin/index/?group=workflow_settings'); ?>">Workflow - Configurações</a></li>
                <li><?= htmlspecialchars($tipo->titulo); ?></li>
                <li class="active">Fluxos</li>
            </ol>
        </div>

        <div class="col-md-12">
            <div class="wf-card">
                <div class="wf-header">
                    <div>
                        <div class="breadcrumbish">CATEGORIA</div>
                        <h2><?= htmlspecialchars(strtoupper($tipo->titulo)); ?></h2>
                    </div>
                    <div class="wf-chips">
                        <span class="wf-chip">Prazo máx. <strong><?= (int) $tipo->prazo; ?> dias</strong></span>
                        <span class="wf-chip">Setor responsável <strong><?= htmlspecialchars(get_departamento_nome($tipo->responsavel)); ?></strong></span>
                        <span class="wf-chip">Prazo cliente <strong><?= (int) $tipo->prazo_cliente; ?> dias</strong></span>
                    </div>
                </div>

                <div class="wf-body">
                    <div class="wf-tree-pane">
                        <div class="wf-tree-toolbar">
                            <button type="button" class="btn btn-info btn-sm" id="btn-add-root"><i class="fa fa-plus"></i> Nó raiz</button>
                            <button type="button" class="btn btn-default btn-sm" id="btn-expand-all"><i class="fa fa-expand"></i> Expandir</button>
                            <button type="button" class="btn btn-default btn-sm" id="btn-collapse-all"><i class="fa fa-compress"></i> Recolher</button>
                            <div class="spacer"></div>
                            <input type="text" id="wf-search" class="form-control input-sm" placeholder="Buscar...">
                        </div>
                        <div id="wf-tree"></div>
                        <p class="text-muted small" style="margin-top: 14px;">
                            <i class="fa fa-info-circle"></i>
                            Arraste nós para reordenar ou aninhar. Clique para editar. Clique direito para mais opções.
                        </p>
                    </div>

                    <div class="wf-side" id="wf-side">
                        <div class="empty">
                            <i class="fa fa-arrow-left"></i><br>
                            Selecione um nó na árvore<br>ou adicione um novo.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="wf-copy-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h4 class="modal-title">Copiar nó para outro fluxo</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="wf-copy-node-id">
                <div class="form-group">
                    <label>Categoria destino</label>
                    <select id="wf-copy-target-categoria" class="form-control" data-live-search="true">
                        <option value="">— carregando —</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Nó pai destino <span class="text-muted small">(opcional, vazio = raiz)</span></label>
                    <select id="wf-copy-target-parent" class="form-control" data-live-search="true">
                        <option value="">— Raiz —</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="wf-copy-confirm">
                    <i class="fa fa-copy"></i> Copiar
                </button>
            </div>
        </div>
    </div>
</div>

<script id="wf-form-tpl" type="text/template">
    <h4 id="wf-form-title">Editar fluxo</h4>
    <input type="hidden" id="wf-id">
    <input type="hidden" id="wf-parent-id">

    <div class="form-group">
        <label>Setor</label>
        <select class="selectpicker form-control" id="wf-setor" data-live-search="true" data-width="100%">
            <option value="">— selecionar —</option>
            <?php foreach ($departments as $d): ?>
                <option value="<?= (int) $d['departmentid']; ?>"><?= htmlspecialchars($d['name']); ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <label>Prazo (dias)</label>
        <input type="number" min="0" class="form-control" id="wf-prazo">
    </div>

    <div class="form-group">
        <label>Objetivo</label>
        <textarea class="form-control" id="wf-objetivo" rows="3"></textarea>
    </div>

    <div class="form-group">
        <label class="checkbox-inline"><input type="checkbox" id="wf-contato"> Contato cliente</label>
        <label class="checkbox-inline"><input type="checkbox" id="wf-finaliza"> Finaliza cliente</label>
    </div>

    <div class="actions">
        <button type="button" class="btn btn-info" id="wf-btn-save"><i class="fa fa-save"></i> Salvar</button>
        <button type="button" class="btn btn-default" id="wf-btn-cancel">Cancelar</button>
    </div>

    <div class="extras" id="wf-extras" style="display:none;">
        <label class="text-muted small">Mais ações</label><br>
        <a class="btn btn-warning btn-xs" id="wf-btn-question"><i class="fa fa-question"></i> Perguntas</a>
        <a class="btn btn-success btn-xs" id="wf-btn-campos"><i class="fa fa-list-ol"></i> Campos personalizados</a>
        <a class="btn btn-danger btn-xs" id="wf-btn-delete"><i class="fa fa-trash"></i> Excluir</a>
    </div>

    <div id="wf-extras-panel" style="margin-top:18px;"></div>
</script>

<?php init_tail(); ?>

<script src="https://cdn.jsdelivr.net/npm/jstree@3.3.16/dist/jstree.min.js"></script>
<script>
(function () {
    const CATEGORIA_ID = <?= (int) $tipo->id; ?>;
    const URL_TREE      = '<?= base_url('gestao_corporativa/Workflow/tree_data'); ?>/' + CATEGORIA_ID;
    const URL_SAVE      = '<?= base_url('gestao_corporativa/Workflow/node_save'); ?>';
    const URL_DELETE    = '<?= base_url('gestao_corporativa/Workflow/node_delete'); ?>';
    const URL_REORDER   = '<?= base_url('gestao_corporativa/Workflow/node_reorder'); ?>';
    const URL_SPACE     = '<?= base_url('gestao_corporativa/Workflow/mudar_space'); ?>';
    const URL_DUPLICATE = '<?= base_url('gestao_corporativa/Workflow/node_duplicate'); ?>';
    const URL_COPY      = '<?= base_url('gestao_corporativa/Workflow/node_copy_to_categoria'); ?>';
    const URL_LIST_CAT  = '<?= base_url('gestao_corporativa/Workflow/list_workflow_categorias'); ?>';
    const URL_LIST_NODE = '<?= base_url('gestao_corporativa/Workflow/list_categoria_nodes'); ?>';

    const $tree = $('#wf-tree');
    const $side = $('#wf-side');
    const formTpl = $('#wf-form-tpl').html();

    function buildTree() {
        $tree.jstree('destroy');
        $tree.jstree({
            core: {
                data: { url: URL_TREE, dataType: 'json' },
                check_callback: true,
                themes: { stripes: true, dots: false }
            },
            plugins: ['dnd', 'wholerow', 'contextmenu', 'search'],
            search: { show_only_matches: true, show_only_matches_children: true },
            contextmenu: {
                items: function (node) {
                    return {
                        add: { label: 'Adicionar filho', icon: 'fa fa-plus', action: () => openCreateForm(node.data.id) },
                        edit: { label: 'Editar', icon: 'fa fa-pencil', action: () => openEditForm(node) },
                        sep1: '---',
                        dup: { label: 'Duplicar (com filhos)', icon: 'fa fa-clone', action: () => duplicateNode(node.data.id) },
                        copy: { label: 'Copiar para outro fluxo...', icon: 'fa fa-copy', action: () => openCopyModal(node.data.id) },
                        sep2: '---',
                        del: { label: 'Excluir (cascata)', icon: 'fa fa-trash', action: () => confirmDelete(node.data.id) }
                    };
                }
            }
        });

        $tree.on('select_node.jstree', (_e, sel) => openEditForm(sel.node));

        $tree.on('move_node.jstree', (_e, data) => {
            const parentId = data.parent === '#' ? '' : data.parent.replace(/^f/, '');
            $.post(URL_REORDER, {
                id: data.node.data.id,
                categoria_id: CATEGORIA_ID,
                parent_id: parentId,
                position: data.position
            }).done(() => $tree.jstree(true).refresh()).fail(() => {
                alert('Falha ao mover. Recarregando árvore.');
                $tree.jstree(true).refresh();
            });
        });
    }

    function clearSide() {
        $side.html('<div class="empty"><i class="fa fa-arrow-left"></i><br>Selecione um nó na árvore<br>ou adicione um novo.</div>');
    }

    function renderForm(data, parentId) {
        $side.html(formTpl);
        $('#wf-form-title').text(data ? 'Editar fluxo' : 'Novo fluxo');
        $('#wf-id').val(data ? data.id : '');
        $('#wf-parent-id').val(parentId || '');
        $('#wf-setor').val(data ? data.setor : '').trigger('change');
        $('#wf-prazo').val(data ? data.prazo : 0);
        $('#wf-objetivo').val(data ? (data.objetivo || '') : '');
        $('#wf-contato').prop('checked', data ? !!data.contato_cliente : false);
        $('#wf-finaliza').prop('checked', data ? !!data.finaliza_cliente : false);
        $('#wf-extras').toggle(!!data);
        if (typeof init_selectpicker === 'function') init_selectpicker();

        $('#wf-btn-save').on('click', save);
        $('#wf-btn-cancel').on('click', clearSide);
        if (data) {
            $('#wf-btn-delete').on('click', () => confirmDelete(data.id));
            $('#wf-btn-question').on('click', () => loadSpace('question', data.id));
            $('#wf-btn-campos').on('click', () => loadSpace('campos', data.id));
        }
    }

    function openEditForm(node) { renderForm(node.data, null); }
    function openCreateForm(parentId) { renderForm(null, parentId || ''); }

    function save() {
        const payload = {
            id: $('#wf-id').val(),
            parent_id: $('#wf-parent-id').val(),
            categoria_id: CATEGORIA_ID,
            setor: $('#wf-setor').val(),
            prazo: $('#wf-prazo').val() || 0,
            objetivo: $('#wf-objetivo').val(),
            contato_cliente: $('#wf-contato').is(':checked') ? 1 : 0,
            finaliza_cliente: $('#wf-finaliza').is(':checked') ? 1 : 0,
        };
        if (!payload.setor) { alert('Selecione um setor.'); return; }

        $('#wf-btn-save').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Salvando...');
        $.post(URL_SAVE, payload).done(() => {
            $tree.jstree(true).refresh();
            clearSide();
        }).fail(() => alert('Falha ao salvar.'));
    }

    function confirmDelete(id) {
        if (!confirm('Excluir este nó e todos os filhos? Essa ação fará soft delete.')) return;
        $.post(URL_DELETE, { id, categoria_id: CATEGORIA_ID }).done(() => {
            $tree.jstree(true).refresh();
            clearSide();
        });
    }

    function duplicateNode(id) {
        if (!confirm('Duplicar este nó e todos os filhos como irmão no mesmo nível?')) return;
        $.post(URL_DUPLICATE, { id, categoria_id: CATEGORIA_ID }).done((res) => {
            if (res && res.ok) {
                $tree.jstree(true).refresh();
                clearSide();
            } else {
                alert((res && res.error) || 'Falha ao duplicar.');
            }
        }).fail(() => alert('Falha ao duplicar.'));
    }

    function openCopyModal(id) {
        $('#wf-copy-node-id').val(id);
        const $cat = $('#wf-copy-target-categoria').html('<option value="">— carregando —</option>');
        const $parent = $('#wf-copy-target-parent').html('<option value="">— Raiz —</option>');

        $.getJSON(URL_LIST_CAT, { exclude: CATEGORIA_ID }).done((rows) => {
            $cat.empty().append('<option value="">— Selecione —</option>');
            rows.forEach(r => $cat.append('<option value="' + r.id + '">' + $('<div>').text(r.titulo).html() + '</option>'));
        }).fail(() => $cat.html('<option value="">erro ao carregar</option>'));

        $('#wf-copy-modal').modal('show');
    }

    $('#wf-copy-target-categoria').on('change', function () {
        const cid = $(this).val();
        const $parent = $('#wf-copy-target-parent').html('<option value="">— Raiz —</option>');
        if (!cid) return;
        $.getJSON(URL_LIST_NODE, { categoria_id: cid }).done((rows) => {
            rows.forEach(r => {
                const label = (r.codigo_sequencial || '') + ' · ' + (r.setor_name || '(sem setor)');
                $parent.append('<option value="' + r.id + '">' + $('<div>').text(label).html() + '</option>');
            });
        });
    });

    $('#wf-copy-confirm').on('click', function () {
        const id = $('#wf-copy-node-id').val();
        const target_categoria_id = $('#wf-copy-target-categoria').val();
        const target_parent_id = $('#wf-copy-target-parent').val();
        if (!target_categoria_id) { alert('Selecione a categoria destino.'); return; }

        const $btn = $(this).prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Copiando...');
        $.post(URL_COPY, {
            id,
            source_categoria_id: CATEGORIA_ID,
            target_categoria_id,
            target_parent_id
        }).done((res) => {
            if (res && res.ok) {
                $('#wf-copy-modal').modal('hide');
                alert('Copiado com sucesso para a outra categoria.');
            } else {
                alert((res && res.error) || 'Falha ao copiar.');
            }
        }).fail(() => alert('Falha ao copiar.')).always(() => {
            $btn.prop('disabled', false).html('<i class="fa fa-copy"></i> Copiar');
        });
    });

    function loadSpace(slug, id) {
        $('#wf-extras-panel').html('<i class="fa fa-spinner fa-spin"></i>');
        $('#wf-extras-panel').load(URL_SPACE, { slug, id });
    }

    $('#btn-add-root').on('click', () => openCreateForm(''));
    $('#btn-expand-all').on('click', () => $tree.jstree('open_all'));
    $('#btn-collapse-all').on('click', () => $tree.jstree('close_all'));

    let searchTimer;
    $('#wf-search').on('keyup', function () {
        clearTimeout(searchTimer);
        const v = $(this).val();
        searchTimer = setTimeout(() => $tree.jstree(true).search(v), 200);
    });

    $(buildTree);
})();
</script>
</body>
</html>
