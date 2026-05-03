<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head_intranet_novo(false); ?>
<?php $this->load->view('gestao_corporativa/css_background'); ?>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/lte/plugins/select2/css/select2.min.css">

<style>
    .ac-page{max-width:1400px;margin:24px auto;padding:0 18px;}
    .ac-toolbar{display:flex;justify-content:space-between;align-items:center;margin-bottom:14px;gap:12px;flex-wrap:wrap;}
    .ac-toolbar h2{margin:0;font-size:20px;color:#1f2937;}
    .ac-filters{display:grid;grid-template-columns:repeat(auto-fill,minmax(170px,1fr));gap:10px;background:#fff;border:1px solid #e5e7eb;border-radius:10px;padding:14px;margin-bottom:14px;}
    .ac-filters label{font-size:11px;font-weight:600;color:#475569;text-transform:uppercase;letter-spacing:.04em;display:block;margin-bottom:4px;}
    .ac-filters input,.ac-filters select{width:100%;border:1px solid #d0d5dd;border-radius:6px;padding:7px 10px;font-size:13px;}

    .ac-list{display:flex;flex-direction:column;gap:8px;}
    .ac-row{display:grid;grid-template-columns:auto 1fr auto auto;gap:14px;align-items:center;background:#fff;border:1px solid #e5e7eb;border-radius:10px;padding:12px 16px;border-left:4px solid var(--c, #94a3b8);transition:.15s;}
    .ac-row:hover{box-shadow:0 4px 12px rgba(0,0,0,.06);}
    .ac-row.done{opacity:.6;}
    .ac-row.done .nome{text-decoration:line-through;}

    .ac-check{width:22px;height:22px;border:2px solid #cbd5e1;border-radius:50%;display:flex;align-items:center;justify-content:center;cursor:pointer;background:#fff;flex-shrink:0;transition:.12s;}
    .ac-check:hover{border-color:#16a34a;}
    .ac-check.done{background:#16a34a;border-color:#16a34a;color:#fff;}
    .ac-check i{font-size:11px;}

    .ac-row .nome{font-size:14px;font-weight:600;color:#1f2937;cursor:pointer;display:block;}
    .ac-row .nome:hover{color:#0a66c2;}
    .ac-row .meta{font-size:11px;color:#64748b;display:flex;flex-wrap:wrap;gap:10px;margin-top:4px;}
    .ac-row .meta span{display:inline-flex;align-items:center;gap:4px;}

    .ac-prio{display:inline-block;width:14px;height:14px;border-radius:50%;flex-shrink:0;}
    .ac-status-pill{padding:2px 8px;border-radius:6px;font-size:10px;font-weight:700;letter-spacing:.04em;text-transform:uppercase;}

    .badge-prazo{font-size:10px;padding:3px 8px;border-radius:4px;font-weight:700;text-transform:uppercase;}
    .badge-prazo.atrasado{background:#fee2e2;color:#991b1b;}
    .badge-prazo.hoje{background:#fef3c7;color:#92400e;}
    .badge-prazo.normal{background:#f1f5f9;color:#475569;}

    .ac-empty{text-align:center;padding:48px;color:#94a3b8;background:#fff;border:1px dashed #e5e7eb;border-radius:10px;}

    .modal{display:none;position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(15,23,42,.6);z-index:1000;align-items:flex-start;justify-content:center;padding:40px 12px;}
    .modal.show{display:flex;}
    .modal .box{background:#fff;border-radius:10px;width:100%;max-width:680px;max-height:90vh;overflow:auto;}
    .modal .box header{padding:14px 18px;border-bottom:1px solid #eef1f4;font-weight:600;color:#1f2937;display:flex;justify-content:space-between;align-items:center;}
    .modal .box header .close{background:none;border:0;font-size:20px;color:#94a3b8;cursor:pointer;}
    .modal .box .body{padding:18px;}
    .modal .box footer{padding:14px 18px;border-top:1px solid #eef1f4;display:flex;justify-content:flex-end;gap:6px;}
    .modal .form-group{margin-bottom:12px;}
    .modal label{font-size:11px;font-weight:600;color:#475569;text-transform:uppercase;letter-spacing:.04em;display:block;margin-bottom:4px;}
    .modal input,.modal select,.modal textarea{width:100%;border:1px solid #d0d5dd;border-radius:6px;padding:7px 10px;font-size:13px;}
    .modal .grid-2{display:grid;grid-template-columns:1fr 1fr;gap:10px;}
    .modal .grid-3{display:grid;grid-template-columns:1fr 1fr 1fr;gap:10px;}
</style>

<div class="content">
    <div class="ac-page">
        <ol class="breadcrumb" style="background:#fff;">
            <li><a href="<?php echo base_url('gestao_corporativa/intranet'); ?>"><i class="fa fa-home"></i> Home</a></li>
            <li class="active">Ações</li>
        </ol>

        <div class="ac-toolbar">
            <h2><i class="fa fa-tasks"></i> Ações (<?php echo count($acoes); ?>)</h2>
            <button type="button" class="btn btn-info" onclick="abrirModal()"><i class="fa fa-plus"></i> Nova ação</button>
        </div>

        <form method="get" action="">
            <div class="ac-filters">
                <div>
                    <label>Buscar</label>
                    <input type="text" name="busca" value="<?php echo html_escape($filtros['busca'] ?? ''); ?>" placeholder="título, descrição…">
                </div>
                <div>
                    <label>Projeto</label>
                    <select name="project_id" class="select2">
                        <option value="">— todos —</option>
                        <?php foreach ($projects as $p): ?>
                            <option value="<?php echo (int) $p['id']; ?>" <?php echo (int) $filtros['project_id'] === (int) $p['id'] ? 'selected' : ''; ?>><?php echo html_escape($p['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label>Status</label>
                    <select name="status">
                        <option value="">— todos —</option>
                        <?php foreach ($this->Acao_model->get_statuses() as $sid => $s): ?>
                            <option value="<?php echo $sid; ?>" <?php echo (int) $filtros['status'] === $sid ? 'selected' : ''; ?>><?php echo $s['label']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label>Prioridade</label>
                    <select name="priority">
                        <option value="">— todas —</option>
                        <?php foreach ($this->Acao_model->get_priorities() as $pid => $p): ?>
                            <option value="<?php echo $pid; ?>" <?php echo (int) $filtros['priority'] === $pid ? 'selected' : ''; ?>><?php echo $p['label']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label>Visão</label>
                    <?php $visao = !empty($filtros['criei']) ? 'criei' : (!empty($filtros['atribuida_eu']) ? 'atrib' : (!empty($filtros['envolve_eu']) ? 'env' : '')); ?>
                    <select name="visao" onchange="
                        this.form.querySelector('input[name=criei]').value        = this.value === 'criei' ? 1 : '';
                        this.form.querySelector('input[name=atribuida_eu]').value = this.value === 'atrib' ? 1 : '';
                        this.form.querySelector('input[name=envolve_eu]').value   = this.value === 'env'   ? 1 : '';
                        this.form.submit();
                    ">
                        <option value="">Todos</option>
                        <option value="criei" <?php echo $visao === 'criei' ? 'selected' : ''; ?>>Que criei</option>
                        <option value="atrib" <?php echo $visao === 'atrib' ? 'selected' : ''; ?>>Atribuídas a mim</option>
                        <option value="env"   <?php echo $visao === 'env'   ? 'selected' : ''; ?>>Que envolvem eu</option>
                    </select>
                    <input type="hidden" name="criei" value="<?php echo $visao === 'criei' ? 1 : ''; ?>">
                    <input type="hidden" name="atribuida_eu" value="<?php echo $visao === 'atrib' ? 1 : ''; ?>">
                    <input type="hidden" name="envolve_eu" value="<?php echo $visao === 'env' ? 1 : ''; ?>">
                </div>
                <div>
                    <label>&nbsp;</label>
                    <label style="display:flex;align-items:center;gap:6px;font-size:12px;font-weight:400;text-transform:none;color:#475569;cursor:pointer;">
                        <input type="checkbox" name="atrasadas" value="1" <?php echo !empty($filtros['atrasadas']) ? 'checked' : ''; ?>> Só atrasadas
                    </label>
                    <label style="display:flex;align-items:center;gap:6px;font-size:12px;font-weight:400;text-transform:none;color:#475569;cursor:pointer;">
                        <input type="checkbox" name="concluidas" value="1" <?php echo !empty($filtros['concluidas']) ? 'checked' : ''; ?>> Mostrar concluídas
                    </label>
                </div>
                <div>
                    <label>&nbsp;</label>
                    <button type="submit" class="btn btn-default" style="width:100%;"><i class="fa fa-filter"></i> Filtrar</button>
                </div>
            </div>
        </form>

        <div class="ac-list">
            <?php if (empty($acoes)): ?>
                <div class="ac-empty">
                    <i class="fa fa-tasks fa-3x" style="color:#cbd5e1;"></i>
                    <h4>Nenhuma ação encontrada.</h4>
                </div>
            <?php else: ?>
                <?php foreach ($acoes as $a):
                    $cor_status = $this->Acao_model->get_status_color($a['status']);
                    $cor_prio = $this->Acao_model->get_priority_color($a['priority']);
                    $is_done = (int) $a['status'] === 5;
                    $atrasado = !$is_done && !empty($a['duedate']) && $a['duedate'] < date('Y-m-d');
                    $hoje = !$is_done && !empty($a['duedate']) && $a['duedate'] === date('Y-m-d');
                ?>
                    <div class="ac-row <?php echo $is_done ? 'done' : ''; ?>" style="--c:<?php echo $cor_status; ?>;">
                        <div class="ac-check <?php echo $is_done ? 'done' : ''; ?>" data-id="<?php echo (int) $a['id']; ?>" data-done="<?php echo $is_done ? '1' : '0'; ?>" onclick="toggleDone(this)" title="<?php echo $is_done ? 'Reabrir' : 'Marcar como concluída'; ?>">
                            <i class="fa fa-check"></i>
                        </div>
                        <div>
                            <span class="nome" onclick="abrirModal(<?php echo (int) $a['id']; ?>)"><?php echo html_escape($a['name']); ?></span>
                            <div class="meta">
                                <span class="ac-prio" style="background:<?php echo $cor_prio; ?>;" title="<?php echo $this->Acao_model->get_priority_label($a['priority']); ?>"></span>
                                <span class="ac-status-pill" style="background:<?php echo $cor_status; ?>20;color:<?php echo $cor_status; ?>;"><?php echo $this->Acao_model->get_status_label($a['status']); ?></span>
                                <?php if (!empty($a['project_name'])): ?><span><i class="fa fa-folder-open"></i> <?php echo html_escape(mb_strimwidth($a['project_name'], 0, 30, '…')); ?></span><?php endif; ?>
                                <?php if (!empty($a['ata_titulo'])): ?><span><i class="far fa-file-alt"></i> <?php echo html_escape(mb_strimwidth($a['ata_titulo'], 0, 25, '…')); ?></span><?php endif; ?>
                                <?php if (!empty($a['plano_titulo'])): ?><span><i class="fas fa-clipboard-list"></i> <?php echo html_escape(mb_strimwidth($a['plano_titulo'], 0, 25, '…')); ?></span><?php endif; ?>
                                <?php if (!empty($a['grupo_titulo'])): ?><span><i class="fas fa-users-cog"></i> <?php echo html_escape(mb_strimwidth($a['grupo_titulo'], 0, 25, '…')); ?></span><?php endif; ?>
                                <?php if (!empty($a['assigned_nomes'])): ?><span><i class="fa fa-user"></i> <?php echo html_escape(mb_strimwidth($a['assigned_nomes'], 0, 40, '…')); ?></span><?php endif; ?>
                                <?php if (!empty($a['criador_nome'])): ?><span style="color:#94a3b8;">criada por <?php echo html_escape($a['criador_nome']); ?></span><?php endif; ?>
                            </div>
                        </div>
                        <div>
                            <?php if ($atrasado): ?>
                                <span class="badge-prazo atrasado">vencida <?php echo date('d/m', strtotime($a['duedate'])); ?></span>
                            <?php elseif ($hoje): ?>
                                <span class="badge-prazo hoje">hoje</span>
                            <?php elseif (!empty($a['duedate'])): ?>
                                <span class="badge-prazo normal"><?php echo date('d/m/Y', strtotime($a['duedate'])); ?></span>
                            <?php endif; ?>
                        </div>
                        <div>
                            <a href="<?php echo admin_url('tasks/view/' . (int) $a['id']); ?>" target="_blank" class="btn btn-default btn-xs" title="Abrir editor avançado"><i class="fa fa-external-link-alt"></i></a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal" id="modal-acao">
    <div class="box">
        <header>
            <span id="modal-titulo">Nova ação</span>
            <button class="close" onclick="fecharModal()">×</button>
        </header>
        <form id="form-acao" method="post" action="<?php echo base_url('gestao_corporativa/Acao/save'); ?>">
            <div class="body">
                <input type="hidden" name="id" id="ac-id">
                <div class="form-group">
                    <label>Título <span style="color:#dc2626;">*</span></label>
                    <input type="text" name="name" id="ac-name" required>
                </div>
                <div class="form-group">
                    <label>Descrição</label>
                    <textarea name="description" id="ac-desc" rows="3"></textarea>
                </div>
                <div class="grid-3">
                    <div class="form-group">
                        <label>Prioridade</label>
                        <select name="priority" id="ac-priority">
                            <?php foreach ($this->Acao_model->get_priorities() as $pid => $p): ?>
                                <option value="<?php echo $pid; ?>" <?php echo $pid === 2 ? 'selected' : ''; ?>><?php echo $p['label']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" id="ac-status">
                            <?php foreach ($this->Acao_model->get_statuses() as $sid => $s): ?>
                                <option value="<?php echo $sid; ?>" <?php echo $sid === 1 ? 'selected' : ''; ?>><?php echo $s['label']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Prazo</label>
                        <input type="date" name="duedate" id="ac-duedate">
                    </div>
                </div>
                <div class="form-group">
                    <label>Atribuir a</label>
                    <select name="assigned[]" id="ac-assigned" multiple>
                        <?php foreach ($staffs as $s): ?>
                            <option value="<?php echo (int) $s['staffid']; ?>"><?php echo html_escape($s['firstname'] . ' ' . $s['lastname']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="grid-2">
                    <div class="form-group">
                        <label>Projeto</label>
                        <select name="project_id" id="ac-project_id">
                            <option value="">— sem projeto —</option>
                            <?php foreach ($projects as $p): ?>
                                <option value="<?php echo (int) $p['id']; ?>"><?php echo html_escape($p['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Fase</label>
                        <select name="fase_id" id="ac-fase_id">
                            <option value="">— sem fase —</option>
                        </select>
                    </div>
                </div>
                <div class="grid-3">
                    <div class="form-group">
                        <label>Ata</label>
                        <select name="ataid" id="ac-ataid"><option value="">— nenhuma —</option></select>
                    </div>
                    <div class="form-group">
                        <label>Plano de Ação</label>
                        <select name="planoid" id="ac-planoid"><option value="">— nenhum —</option></select>
                    </div>
                    <div class="form-group">
                        <label>Grupo</label>
                        <select name="grupoid" id="ac-grupoid"><option value="">— nenhum —</option></select>
                    </div>
                </div>
            </div>
            <footer>
                <button type="button" class="btn btn-default" onclick="fecharModal()">Cancelar</button>
                <button type="submit" class="btn btn-info"><i class="fa fa-save"></i> Salvar</button>
            </footer>
        </form>
    </div>
</div>

<?php init_tail(); ?>
<script src="<?php echo base_url() ?>assets/lte/plugins/select2/js/select2.full.min.js"></script>
<script>
$(function () {
    $('.select2').select2({width:'100%'});
    $('#ac-assigned, #ac-project_id, #ac-fase_id, #ac-ataid, #ac-planoid, #ac-grupoid, #ac-priority, #ac-status').select2({width:'100%', dropdownParent: $('#modal-acao')});

    // pré-carrega selects auxiliares
    $.getJSON('<?php echo base_url('gestao_corporativa/Acao/ata_options'); ?>', function (data) {
        var $s = $('#ac-ataid');
        (data || []).forEach(function (a) { $s.append($('<option>').val(a.id).text('#' + a.id + ' ' + a.titulo)); });
    });
    $.getJSON('<?php echo base_url('gestao_corporativa/Acao/plano_options'); ?>', function (data) {
        var $s = $('#ac-planoid');
        (data || []).forEach(function (a) { $s.append($('<option>').val(a.id).text('#' + a.id + ' ' + a.titulo)); });
    });
    $.getJSON('<?php echo base_url('gestao_corporativa/Acao/grupo_options'); ?>', function (data) {
        var $s = $('#ac-grupoid');
        (data || []).forEach(function (a) { $s.append($('<option>').val(a.id).text('#' + a.id + ' ' + a.titulo)); });
    });

    $('#ac-project_id').on('change', function () {
        var pid = $(this).val();
        var $f = $('#ac-fase_id');
        $f.empty().append('<option value="">— sem fase —</option>');
        if (pid) {
            $.getJSON('<?php echo base_url('gestao_corporativa/Projeto_fase/list_options'); ?>/' + pid, function (data) {
                (data || []).forEach(function (f) { $f.append($('<option>').val(f.id).text(f.codigo_sequencial + ' ' + f.titulo)); });
                $f.trigger('change.select2');
            });
        } else { $f.trigger('change.select2'); }
    });
});

function abrirModal(id) {
    $('#form-acao')[0].reset();
    $('#ac-assigned').val(null).trigger('change');
    $('#modal-titulo').text(id ? 'Editar ação' : 'Nova ação');
    $('#ac-id').val(id || '');
    if (id) {
        $.getJSON('<?php echo base_url('gestao_corporativa/Acao/get_acao'); ?>/' + id, function (a) {
            if (!a) return;
            $('#ac-name').val(a.name);
            $('#ac-desc').val(a.description);
            $('#ac-priority').val(a.priority).trigger('change');
            $('#ac-status').val(a.status).trigger('change');
            $('#ac-duedate').val(a.duedate);
            if (a.rel_type === 'project') $('#ac-project_id').val(a.rel_id).trigger('change');
            $('#ac-fase_id').val(a.fase_id);
            $('#ac-ataid').val(a.ataid).trigger('change');
            $('#ac-planoid').val(a.planoid).trigger('change');
            $('#ac-grupoid').val(a.grupoid).trigger('change');
            $('#ac-assigned').val(a.assigned_ids || []).trigger('change');
        });
    }
    $('#modal-acao').addClass('show');
}
function fecharModal() { $('#modal-acao').removeClass('show'); }
$('#modal-acao').on('click', function (e) { if (e.target === this) fecharModal(); });

function toggleDone(el) {
    var $el = $(el), id = $el.data('id'), done = $el.data('done') == '1';
    var url = done ? '<?php echo base_url('gestao_corporativa/Acao/reabrir'); ?>/' + id
                   : '<?php echo base_url('gestao_corporativa/Acao/concluir'); ?>/' + id;
    $.post(url, function () { location.reload(); });
}
</script>
</body>
</html>
