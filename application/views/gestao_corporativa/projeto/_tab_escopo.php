<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php
$pid = (int) $project->id;
$this->load->model('Projeto_fase_model');
$this->load->model('Staff_model');
$staffs = $this->Staff_model->get();
$statuses = $this->Projeto_fase_model->get_statuses();
?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jstree@3.3.16/dist/themes/default/style.min.css">
<style>
    .escopo-toolbar{display:flex;gap:8px;align-items:center;margin:14px 0;flex-wrap:wrap;}
    .escopo-toolbar input[type="text"]{width:240px;}
    .escopo-grid{display:grid;grid-template-columns:1fr 380px;gap:0;background:#fff;border:1px solid #e5e7eb;border-radius:10px;overflow:hidden;}
    .escopo-tree{padding:14px 16px;border-right:1px solid #eef1f4;min-height:400px;}
    .escopo-side{padding:16px 18px;background:#fafbfc;}
    .escopo-side h4{font-size:13px;font-weight:700;color:#1f2937;text-transform:uppercase;letter-spacing:.04em;margin:0 0 10px;}
    .escopo-side .empty{color:#9aa7b3;font-size:13px;text-align:center;padding:30px 0;}
    .escopo-side .form-group label{font-size:12px;font-weight:600;color:#475569;}
    .escopo-side .actions{display:flex;gap:8px;margin-top:14px;}
    .escopo-side .actions .btn{flex:1;}
    @media (max-width:960px){.escopo-grid{grid-template-columns:1fr;}.escopo-tree{border-right:0;border-bottom:1px solid #eef1f4;}}

    .fase-badge{font-size:10px;font-weight:600;padding:1px 7px;border-radius:999px;color:#fff;margin-left:6px;vertical-align:middle;}
    .fase-prazo,.fase-resp{font-size:11px;color:#64748b;margin-left:8px;}
    .fase-prazo i,.fase-resp i{margin-right:3px;}
    .fase-progress{display:inline-block;width:80px;height:6px;background:#f3f4f6;border-radius:999px;margin-left:10px;vertical-align:middle;overflow:hidden;}
    .fase-progress-bar{display:block;height:100%;border-radius:999px;}

    .jstree-default .jstree-anchor{height:auto;padding:6px 8px;line-height:1.4;}
    .jstree-default .jstree-clicked{background:#e3f0ff;box-shadow:none;}
    .jstree-default .jstree-hovered{background:#f0f6ff;}
</style>

<div class="row" style="padding-top:10px;">
    <div class="col-md-12">
        <div class="escopo-toolbar">
            <button type="button" class="btn btn-info btn-sm" id="btn-add-fase-raiz" onclick="if(window.SigEscopo){SigEscopo.openCreate('');}else{console.error('SigEscopo não inicializado');}"><i class="fa fa-plus"></i> Fase raiz</button>
            <button type="button" class="btn btn-default btn-sm" id="btn-expand" onclick="if(window.SigEscopo){SigEscopo.expand();}"><i class="fa fa-expand"></i> Expandir</button>
            <button type="button" class="btn btn-default btn-sm" id="btn-collapse" onclick="if(window.SigEscopo){SigEscopo.collapse();}"><i class="fa fa-compress"></i> Recolher</button>
            <input type="text" id="fase-search" class="form-control input-sm" placeholder="Buscar fase...">
        </div>

        <div class="escopo-grid">
            <div class="escopo-tree">
                <div id="fases-tree"></div>
                <p class="text-muted small" style="margin-top:14px;">
                    <i class="fa fa-info-circle"></i>
                    Arraste fases para reordenar ou aninhar. Clique pra editar. Botão direito tem mais opções.
                </p>
            </div>
            <div class="escopo-side" id="fase-side">
                <div class="empty">
                    <i class="fa fa-arrow-left"></i><br>
                    Selecione uma fase ou crie uma nova.
                </div>
            </div>
        </div>
    </div>
</div>

<script id="fase-form-tpl" type="text/template">
    <h4 id="fase-form-title">Editar fase</h4>
    <input type="hidden" id="fase-id">
    <input type="hidden" id="fase-parent-id">
    <input type="hidden" id="fase-project-id" value="<?php echo $pid; ?>">

    <div class="form-group">
        <label>Título <span class="text-danger">*</span></label>
        <input type="text" class="form-control" id="fase-titulo" required>
    </div>

    <div class="form-group">
        <label>Status</label>
        <select class="form-control" id="fase-status">
            <?php foreach ($statuses as $s): ?>
                <option value="<?php echo $s; ?>"><?php echo $this->Projeto_fase_model->get_status_label($s); ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="row">
        <div class="col-md-6 form-group">
            <label>Início previsto</label>
            <input type="date" class="form-control" id="fase-dt-ini">
        </div>
        <div class="col-md-6 form-group">
            <label>Fim previsto</label>
            <input type="date" class="form-control" id="fase-dt-fim">
        </div>
    </div>

    <div class="form-group">
        <label>Responsável</label>
        <select class="form-control" id="fase-resp" style="width:100%;">
            <option value="">— selecionar —</option>
            <?php foreach ($staffs as $s): ?>
                <option value="<?php echo (int) $s['staffid']; ?>"><?php echo html_escape($s['firstname'] . ' ' . $s['lastname']); ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <label>Progresso (%)</label>
        <input type="number" class="form-control" id="fase-pct" min="0" max="100" value="0">
    </div>

    <div class="form-group">
        <label>Descrição</label>
        <textarea class="form-control" id="fase-desc" rows="3"></textarea>
    </div>

    <div class="actions">
        <button type="button" class="btn btn-info" id="fase-save"><i class="fa fa-save"></i> Salvar</button>
        <button type="button" class="btn btn-default" id="fase-cancel">Cancelar</button>
    </div>
</script>

<script>
function _bootSigEscopo() {
    if (typeof window.jQuery === 'undefined') {
        return setTimeout(_bootSigEscopo, 50);
    }
    if (typeof jQuery.fn.jstree === 'undefined') {
        var s = document.createElement('script');
        s.src = 'https://cdn.jsdelivr.net/npm/jstree@3.3.16/dist/jstree.min.js';
        s.onload = _initSigEscopoBody;
        s.onerror = function () { console.error('jstree falhou ao carregar'); _initSigEscopoBody(); };
        document.head.appendChild(s);
        return;
    }
    _initSigEscopoBody();
}
function _initSigEscopoBody() {
window.SigEscopo = (function () {
    var PID  = <?php echo $pid; ?>;
    var URL_TREE     = '<?php echo base_url('gestao_corporativa/Projeto_fase/tree_data'); ?>/' + PID;
    var URL_SAVE     = '<?php echo base_url('gestao_corporativa/Projeto_fase/save'); ?>';
    var URL_DELETE   = '<?php echo base_url('gestao_corporativa/Projeto_fase/delete'); ?>';
    var URL_REORDER  = '<?php echo base_url('gestao_corporativa/Projeto_fase/reorder'); ?>';
    var CSRF = { name: '<?php echo $this->security->get_csrf_token_name(); ?>',
                 hash: '<?php echo $this->security->get_csrf_hash(); ?>' };
    function withCsrf(d){ d=d||{}; d[CSRF.name]=CSRF.hash; return d; }

    function $tree(){ return jQuery('#fases-tree'); }
    function $side(){ return jQuery('#fase-side'); }
    function formTpl(){ return jQuery('#fase-form-tpl').html(); }

    function buildTree() {
        var $t = $tree();
        if (!$t.length || typeof jQuery.fn.jstree === 'undefined') {
            console.warn('escopo: jstree ou container ausente');
            return;
        }
        $t.jstree('destroy');
        $t.jstree({
            core: { data: { url: URL_TREE, dataType: 'json' }, check_callback: true, themes: { stripes: true, dots: false } },
            plugins: ['dnd', 'wholerow', 'contextmenu', 'search'],
            search: { show_only_matches: true, show_only_matches_children: true },
            contextmenu: {
                items: function (node) {
                    return {
                        add:  { label: 'Adicionar sub-fase', icon: 'fa fa-plus',     action: function () { openCreate(node.data.id); } },
                        edit: { label: 'Editar',             icon: 'fa fa-pencil',   action: function () { openEdit(node); } },
                        sep: '---',
                        del:  { label: 'Excluir (cascata)',  icon: 'fa fa-trash',    action: function () { confirmDelete(node.data.id); } }
                    };
                }
            }
        });
        $t.on('select_node.jstree', function (_e, sel) { openEdit(sel.node); });
        $t.on('move_node.jstree', function (_e, data) {
            var parent = data.parent === '#' ? '' : data.parent.replace(/^f/, '');
            jQuery.post(URL_REORDER, withCsrf({ id: data.node.data.id, parent_id: parent, position: data.position }))
                .always(function () { $tree().jstree(true).refresh(); });
        });
    }

    function clearSide(){ $side().html('<div class="empty"><i class="fa fa-arrow-left"></i><br>Selecione uma fase ou crie uma nova.</div>'); }

    function renderForm(d, parent_id) {
        $side().html(formTpl());
        jQuery('#fase-form-title').text(d ? 'Editar fase' : 'Nova fase');
        jQuery('#fase-id').val(d ? d.id : '');
        jQuery('#fase-parent-id').val(parent_id || '');
        jQuery('#fase-titulo').val(d ? d.titulo : '').focus();
        jQuery('#fase-status').val(d ? d.status : 'planejada');
        jQuery('#fase-dt-ini').val(d ? (d.dt_inicio_prev || '') : '');
        jQuery('#fase-dt-fim').val(d ? (d.dt_fim_prev || '') : '');
        jQuery('#fase-resp').val(d ? d.responsavel_id : '');
        jQuery('#fase-pct').val(d ? d.percentual : 0);
        jQuery('#fase-desc').val(d ? (d.descricao || '') : '');
        if (jQuery.fn.select2) {
            jQuery('#fase-resp').select2({ width: '100%', placeholder: '— selecionar —', allowClear: true });
        }
    }
    function openEdit(node){ renderForm(node.data, ''); }
    function openCreate(parent_id){ renderForm(null, parent_id || ''); }

    function save() {
        var titulo = (jQuery('#fase-titulo').val() || '').trim();
        if (!titulo) { alert('Título obrigatório.'); return; }
        var payload = withCsrf({
            id: jQuery('#fase-id').val(),
            project_id: PID,
            parent_id: jQuery('#fase-parent-id').val(),
            titulo: titulo,
            status: jQuery('#fase-status').val(),
            dt_inicio_prev: jQuery('#fase-dt-ini').val(),
            dt_fim_prev: jQuery('#fase-dt-fim').val(),
            responsavel_id: jQuery('#fase-resp').val(),
            percentual: jQuery('#fase-pct').val() || 0,
            descricao: jQuery('#fase-desc').val(),
        });
        var $btn = jQuery('#fase-save');
        $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Salvando...');

        jQuery.ajax({
            url: URL_SAVE,
            type: 'POST',
            data: payload,
            dataType: 'text',
            success: function (raw) {
                console.log('escopo save raw response:', raw);
                var resp = null;
                try { resp = JSON.parse(raw); } catch (e) {
                    console.error('escopo: resposta não é JSON', e);
                    alert('Falha ao salvar — resposta inválida do servidor (veja console).');
                    $btn.prop('disabled', false).html('<i class="fa fa-save"></i> Salvar');
                    return;
                }
                if (resp && resp.ok) {
                    if (jQuery.fn.jstree) {
                        try { $tree().jstree(true).refresh(); } catch (e) { location.reload(); return; }
                    } else {
                        location.reload(); return;
                    }
                    clearSide();
                } else {
                    alert('Servidor retornou: ' + raw);
                    $btn.prop('disabled', false).html('<i class="fa fa-save"></i> Salvar');
                }
            },
            error: function (xhr) {
                console.error('escopo save error:', xhr.status, xhr.responseText);
                alert('Falha ao salvar (HTTP ' + xhr.status + '). Veja o console.');
                $btn.prop('disabled', false).html('<i class="fa fa-save"></i> Salvar');
            }
        });
    }

    function confirmDelete(id) {
        if (!confirm('Excluir esta fase e todas as sub-fases?')) return;
        jQuery.post(URL_DELETE + '/' + id, withCsrf(), function () {
            $tree().jstree(true).refresh();
            clearSide();
        }, 'json');
    }

    // Event delegation no document — funciona mesmo se o botão for re-renderizado
    jQuery(document)
        .off('click.escopo')
        .on('click.escopo', '#btn-add-fase-raiz', function () { openCreate(''); })
        .on('click.escopo', '#btn-expand',        function () { $tree().jstree('open_all'); })
        .on('click.escopo', '#btn-collapse',      function () { $tree().jstree('close_all'); })
        .on('click.escopo', '#fase-save',         function () { save(); })
        .on('click.escopo', '#fase-cancel',       function () { clearSide(); });

    var searchTimer;
    jQuery(document).off('keyup.escopo').on('keyup.escopo', '#fase-search', function () {
        clearTimeout(searchTimer);
        var v = jQuery(this).val();
        searchTimer = setTimeout(function () { $tree().jstree(true).search(v); }, 200);
    });

    jQuery(function () {
        console.log('SigEscopo init, project=' + PID);
        buildTree();
    });

    return {
        build: buildTree,
        openCreate: openCreate,
        expand:   function () { if (jQuery.fn.jstree) $tree().jstree('open_all'); },
        collapse: function () { if (jQuery.fn.jstree) $tree().jstree('close_all'); }
    };
})();
}
_bootSigEscopo();
</script>
