<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head_intranet_novo(); ?>
<?php $this->load->view('gestao_corporativa/css_background'); ?>

<style>
    .gp-view{max-width:1100px;margin:24px auto;padding:0 18px;}
    .gp-card{background:#fff;border:1px solid #e5e7eb;border-radius:10px;margin-bottom:14px;}
    .gp-card-header{padding:14px 18px;border-bottom:1px solid #eef1f4;font-weight:600;color:#1f2937;font-size:14px;display:flex;justify-content:space-between;align-items:center;}
    .gp-card-body{padding:18px;}
    .gp-title-row{display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:14px;}
    .gp-title-row h1{margin:0;font-size:24px;font-weight:700;color:#1f2937;}
    .gp-title-row .objetivo{color:#475569;font-size:14px;margin-top:8px;}
    .gp-meta{display:flex;flex-wrap:wrap;gap:18px;color:#475569;font-size:13px;margin-top:10px;}
    .gp-meta i{color:#94a3b8;margin-right:4px;}

    .status-pill{display:inline-block;font-size:11px;font-weight:600;padding:3px 10px;border-radius:999px;}
    .status-ativo{background:#dbeafe;color:#1e40af;}
    .status-pausado{background:#fef3c7;color:#92400e;}
    .status-concluido{background:#dcfce7;color:#166534;}
    .status-cancelado{background:#fee2e2;color:#991b1b;}

    .nav-tabs-pill{display:flex;gap:4px;border-bottom:1px solid #eef1f4;padding:0 4px;}
    .nav-tabs-pill button{background:none;border:0;padding:12px 18px;font-size:13px;font-weight:600;color:#64748b;cursor:pointer;border-bottom:2px solid transparent;}
    .nav-tabs-pill button.active{color:#0a66c2;border-bottom-color:#0a66c2;}
    .nav-tabs-pill button:hover{color:#0a66c2;}
    .nav-tabs-pill .count{background:#e5e7eb;color:#475569;font-size:10px;font-weight:700;padding:1px 7px;border-radius:999px;margin-left:4px;}
    .nav-tabs-pill button.active .count{background:#0a66c2;color:#fff;}
    .tab-pane{display:none;}
    .tab-pane.active{display:block;}

    .item-row{padding:10px 14px;border-radius:8px;border:1px solid #e5e7eb;background:#fafbfc;margin-bottom:6px;display:grid;grid-template-columns:1fr auto;gap:10px;align-items:center;}
    .item-row a{color:#1f2937;text-decoration:none;font-weight:600;font-size:14px;}
    .item-row a:hover{color:#0a66c2;}
    .item-row .meta-sub{font-size:12px;color:#64748b;margin-top:3px;}
    .item-row .badge{font-size:10px;padding:2px 7px;border-radius:6px;font-weight:600;}

    .membros-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:10px;}
    .membro-pp{background:#f8fafc;border:1px solid #e5e7eb;border-radius:8px;padding:10px 12px;display:flex;align-items:center;gap:10px;}
    .membro-pp .avatar{width:36px;height:36px;border-radius:50%;background:#dbeafe;color:#0a66c2;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:13px;}
    .membro-pp .info{min-width:0;}
    .membro-pp .nome{font-size:13px;font-weight:600;color:#1f2937;}
    .membro-pp .papel{font-size:11px;color:#64748b;}
    .papel-lider{color:#92400e !important;font-weight:700;}
    .papel-observador{color:#6366f1 !important;}

    .empty{padding:30px;text-align:center;color:#94a3b8;font-size:13px;}
</style>

<div class="content">
    <div class="gp-view">
        <ol class="breadcrumb" style="background:#fff;">
            <li><a href="<?php echo base_url('gestao_corporativa/intranet'); ?>"><i class="fa fa-home"></i> Home</a></li>
            <li><a href="<?php echo base_url('gestao_corporativa/Workgroup'); ?>">Grupos</a></li>
            <li class="active"><?php echo html_escape($grupo['titulo']); ?></li>
        </ol>

        <div class="gp-card">
            <div class="gp-card-body">
                <div class="gp-title-row">
                    <div style="flex:1;min-width:250px;">
                        <h1><?php echo html_escape($grupo['titulo']); ?></h1>
                        <?php if (!empty($grupo['objetivo'])): ?>
                            <div class="objetivo"><i class="fa fa-bullseye"></i> <?php echo html_escape($grupo['objetivo']); ?></div>
                        <?php endif; ?>
                        <div class="gp-meta">
                            <span class="status-pill status-<?php echo $grupo['status']; ?>"><?php echo $this->Workgroup_model->get_status_label($grupo['status']); ?></span>
                            <?php if (!empty($grupo['lider_nome'])): ?><span><i class="fa fa-star"></i>Líder: <?php echo html_escape($grupo['lider_nome']); ?></span><?php endif; ?>
                            <?php if (!empty($grupo['project_name'])): ?><span><i class="fa fa-folder-o"></i><?php echo html_escape($grupo['project_name']); ?></span><?php endif; ?>
                            <?php if (!empty($grupo['dt_inicio']) || !empty($grupo['dt_fim_prevista'])): ?>
                                <span><i class="fa fa-calendar"></i>
                                    <?php echo !empty($grupo['dt_inicio']) ? date('d/m/Y', strtotime($grupo['dt_inicio'])) : '—'; ?> →
                                    <?php echo !empty($grupo['dt_fim_prevista']) ? date('d/m/Y', strtotime($grupo['dt_fim_prevista'])) : '—'; ?>
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div>
                        <?php if (!empty($pode_editar)): ?>
                            <a href="<?php echo base_url('gestao_corporativa/Workgroup/edit/' . (int) $grupo['id']); ?>" class="btn btn-default btn-sm"><i class="fa fa-pencil"></i> Editar</a>
                        <?php endif; ?>
                        <?php if (!empty($pode_sair)): ?>
                            <a href="<?php echo base_url('gestao_corporativa/Workgroup/sair/' . (int) $grupo['id']); ?>"
                               class="btn btn-default btn-sm"
                               onclick="return confirm('Tem certeza que deseja sair deste grupo?');"
                               style="color:#dc2626;"><i class="fa fa-sign-out"></i> Sair do grupo</a>
                        <?php endif; ?>
                    </div>
                </div>

                <?php if (!empty($grupo['descricao'])): ?>
                    <div style="margin-top:16px;font-size:14px;line-height:1.6;color:#1f2937;"><?php echo $grupo['descricao']; ?></div>
                <?php endif; ?>
            </div>
        </div>

        <div class="gp-card">
            <?php
            $this->load->model('Formulario_model');
            $formularios_grupo = $this->Formulario_model->listar(['grupo_id' => (int) $grupo['id']]);
            ?>
            <div class="nav-tabs-pill">
                <button data-tab="discussao" class="active"><i class="fa fa-comments"></i> Discussão <span class="count"><?php echo count($posts ?? []); ?></span></button>
                <button data-tab="atas"><i class="far fa-file-alt"></i> Atas <span class="count"><?php echo count($atas); ?></span></button>
                <button data-tab="planos"><i class="fas fa-clipboard-list"></i> Planos <span class="count"><?php echo count($planos); ?></span></button>
                <button data-tab="tasks"><i class="fa fa-tasks"></i> Ações <span class="count"><?php echo count($tasks); ?></span></button>
                <button data-tab="formularios"><i class="fa fa-list-alt"></i> Formulários <span class="count"><?php echo count($formularios_grupo); ?></span></button>
                <?php
                $this->load->model('Documentos_model');
                $documentos_grupo = $this->Documentos_model->listar(['grupo_id' => (int) $grupo['id']]);
                $this->load->model('Treinamento_model');
                $treinamentos_grupo = $this->Treinamento_model->listar(['grupo_id' => (int) $grupo['id']]);
                $this->load->model('Risco_model');
                $riscos_grupo = $this->Risco_model->listar(['grupo_id' => (int) $grupo['id']]);
                $this->load->model('Auditoria_model');
                $auditorias_grupo = $this->Auditoria_model->listar(['grupo_id' => (int) $grupo['id']]);
                ?>
                <button data-tab="documentos"><i class="far fa-folder-open"></i> Documentos <span class="count"><?php echo count($documentos_grupo); ?></span></button>
                <button data-tab="treinamentos"><i class="fa fa-graduation-cap"></i> Treinamentos <span class="count"><?php echo count($treinamentos_grupo); ?></span></button>
                <button data-tab="riscos"><i class="fa fa-shield-alt"></i> Riscos <span class="count"><?php echo count($riscos_grupo); ?></span></button>
                <button data-tab="auditorias"><i class="fa fa-clipboard-check"></i> Auditorias <span class="count"><?php echo count($auditorias_grupo); ?></span></button>
                <button data-tab="membros"><i class="fa fa-users"></i> Membros <span class="count"><?php echo count($membros); ?></span></button>
            </div>
            <div class="gp-card-body">
                <div class="tab-pane active" data-pane="discussao">
                    <?php $this->load->view('gestao_corporativa/grupos/_discussao', ['grupo' => $grupo, 'posts' => $posts ?? []]); ?>
                </div>

                <div class="tab-pane atas-pane" data-pane="atas">
                    <?php if (empty($atas)): ?>
                        <div class="empty">Sem atas vinculadas a este grupo.</div>
                        <p style="text-align:center;"><a href="<?php echo base_url('gestao_corporativa/Ata/add?grupo_id=' . (int) $grupo['id']); ?>" class="btn btn-info btn-sm"><i class="fa fa-plus"></i> Nova ata</a></p>
                    <?php else: ?>
                        <?php foreach ($atas as $a): ?>
                            <div class="item-row">
                                <div>
                                    <a href="<?php echo base_url('gestao_corporativa/Ata/view/' . (int) $a['id']); ?>"><?php echo html_escape($a['titulo']); ?></a>
                                    <div class="meta-sub">
                                        <span class="badge status-pill status-<?php echo $a['status']; ?>" style="font-size:10px;"><?php echo $this->Ata_model->get_status_label($a['status']); ?></span>
                                        <?php if (!empty($a['data'])): ?> · <?php echo date('d/m/Y', strtotime($a['data'])); ?><?php endif; ?>
                                        <?php if (!empty($a['responsavel_nome'])): ?> · <?php echo html_escape($a['responsavel_nome']); ?><?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        <p><a href="<?php echo base_url('gestao_corporativa/Ata/add?grupo_id=' . (int) $grupo['id']); ?>" class="btn btn-default btn-sm"><i class="fa fa-plus"></i> Nova ata</a></p>
                    <?php endif; ?>
                </div>

                <div class="tab-pane" data-pane="planos">
                    <?php if (empty($planos)): ?>
                        <div class="empty">Sem planos vinculados a este grupo.</div>
                        <p style="text-align:center;"><a href="<?php echo base_url('gestao_corporativa/Plano_acao/add?grupo_id=' . (int) $grupo['id']); ?>" class="btn btn-info btn-sm"><i class="fa fa-plus"></i> Novo plano</a></p>
                    <?php else: ?>
                        <?php foreach ($planos as $p): ?>
                            <div class="item-row">
                                <div>
                                    <a href="<?php echo base_url('gestao_corporativa/Plano_acao/view/' . (int) $p['id']); ?>"><?php echo html_escape($p['titulo']); ?></a>
                                    <div class="meta-sub">
                                        <span class="badge"><?php echo strtoupper($p['metodologia']); ?></span>
                                        · <?php echo $this->Plano_acao_model->get_status_label($p['status']); ?>
                                        <?php if (!empty($p['responsavel_nome'])): ?> · <?php echo html_escape($p['responsavel_nome']); ?><?php endif; ?>
                                        <?php if (!empty($p['dt_fim'])): ?> · prazo <?php echo date('d/m/Y', strtotime($p['dt_fim'])); ?><?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        <p><a href="<?php echo base_url('gestao_corporativa/Plano_acao/add?grupo_id=' . (int) $grupo['id']); ?>" class="btn btn-default btn-sm"><i class="fa fa-plus"></i> Novo plano</a></p>
                    <?php endif; ?>
                </div>

                <div class="tab-pane" data-pane="tasks">
                    <?php if (empty($tasks)): ?>
                        <div class="empty">Sem tasks vinculadas a este grupo. Tasks geradas a partir de decisões de atas ou itens 5W2H aparecem aqui.</div>
                    <?php else: ?>
                        <?php foreach ($tasks as $t): ?>
                            <div class="item-row">
                                <div>
                                    <a href="<?php echo admin_url('tasks/view/' . (int) $t['id']); ?>" target="_blank"><?php echo html_escape($t['name']); ?></a>
                                    <div class="meta-sub">
                                        <?php if (!empty($t['staff_nome'])): ?><?php echo html_escape($t['staff_nome']); ?><?php endif; ?>
                                        <?php if (!empty($t['duedate'])): ?> · prazo <?php echo date('d/m/Y', strtotime($t['duedate'])); ?><?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <div class="tab-pane" data-pane="formularios">
                    <?php $this->load->view('gestao_corporativa/formularios/_lista_vinculada', [
                        'vinculo_tipo' => 'grupo',
                        'vinculo_id'   => (int) $grupo['id'],
                    ]); ?>
                </div>

                <div class="tab-pane" data-pane="documentos">
                    <?php $this->load->view('gestao_corporativa/documentos/_lista_vinculada', [
                        'vinculo_tipo' => 'grupo',
                        'vinculo_id'   => (int) $grupo['id'],
                    ]); ?>
                </div>

                <div class="tab-pane" data-pane="treinamentos">
                    <?php $this->load->view('gestao_corporativa/treinamentos/_lista_vinculada', [
                        'vinculo_tipo' => 'grupo',
                        'vinculo_id'   => (int) $grupo['id'],
                    ]); ?>
                </div>

                <div class="tab-pane" data-pane="riscos">
                    <?php $this->load->view('gestao_corporativa/riscos/_lista_vinculada', [
                        'vinculo_tipo' => 'grupo',
                        'vinculo_id'   => (int) $grupo['id'],
                    ]); ?>
                </div>

                <div class="tab-pane" data-pane="auditorias">
                    <?php $this->load->view('gestao_corporativa/auditorias/_lista_vinculada', [
                        'vinculo_tipo' => 'grupo',
                        'vinculo_id'   => (int) $grupo['id'],
                    ]); ?>
                </div>

                <div class="tab-pane" data-pane="membros">
                    <?php if (empty($membros)): ?>
                        <div class="empty">Sem membros ainda.</div>
                    <?php else: ?>
                        <div class="membros-grid">
                            <?php foreach ($membros as $m):
                                $iniciais = '';
                                foreach (preg_split('/\s+/', $m['staff_nome'] ?? '') as $part) {
                                    if ($part !== '') $iniciais .= mb_strtoupper(mb_substr($part, 0, 1));
                                    if (mb_strlen($iniciais) >= 2) break;
                                }
                                if ($iniciais === '') $iniciais = '?';
                            ?>
                                <div class="membro-pp">
                                    <div class="avatar"><?php echo $iniciais; ?></div>
                                    <div class="info">
                                        <div class="nome"><?php echo html_escape($m['staff_nome'] ?? '—'); ?></div>
                                        <div class="papel papel-<?php echo $m['papel']; ?>"><?php echo ucfirst($m['papel']); ?><?php if (!empty($m['staff_cargo'])): ?> · <?php echo html_escape($m['staff_cargo']); ?><?php endif; ?></div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php init_tail(); ?>
<script>
$(document).on('click', '.nav-tabs-pill button', function () {
    var tab = $(this).data('tab');
    $('.nav-tabs-pill button').removeClass('active');
    $(this).addClass('active');
    $('.tab-pane').removeClass('active');
    $('.tab-pane[data-pane="' + tab + '"]').addClass('active');
});
</script>
