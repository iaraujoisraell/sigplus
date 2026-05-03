<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head_intranet_novo(false); ?>
<?php $this->load->view('gestao_corporativa/css_background'); ?>

<style>
    .ma-page{max-width:1100px;margin:24px auto;padding:0 18px;}
    .ma-toolbar{display:flex;justify-content:space-between;align-items:center;margin-bottom:14px;gap:12px;}
    .ma-toolbar h2{margin:0;font-size:22px;color:#1f2937;display:flex;align-items:center;gap:10px;}
    .ma-toolbar h2 i{color:#dc2626;}

    .ma-counter{display:grid;grid-template-columns:repeat(4,1fr);gap:10px;margin-bottom:16px;}
    .ma-counter .box{background:#fff;border:1px solid #e5e7eb;border-radius:10px;padding:14px;border-left:3px solid var(--c, #94a3b8);}
    .ma-counter .lbl{font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.04em;}
    .ma-counter .val{font-size:24px;font-weight:700;color:var(--c, #1f2937);line-height:1;margin-top:4px;}
    @media(max-width:768px){.ma-counter{grid-template-columns:1fr 1fr;}}

    .ma-card{background:#fff;border:1px solid #e5e7eb;border-radius:10px;margin-bottom:14px;}
    .ma-card-header{padding:12px 18px;border-bottom:1px solid #eef1f4;font-weight:600;color:#1f2937;font-size:14px;display:flex;align-items:center;gap:8px;}

    .ma-row{display:grid;grid-template-columns:1fr auto;gap:12px;align-items:center;padding:12px 18px;border-bottom:1px solid #f1f5f9;}
    .ma-row:last-child{border-bottom:0;}
    .ma-row:hover{background:#f8fafc;}
    .ma-row .ttl{font-size:14px;color:#1f2937;font-weight:600;text-decoration:none;display:block;}
    .ma-row .ttl:hover{color:#0a66c2;}
    .ma-row .meta{font-size:11px;color:#64748b;margin-top:4px;display:flex;gap:10px;flex-wrap:wrap;}
    .ma-row .right{text-align:right;}
    .ma-row .origem{font-size:10px;color:#94a3b8;text-transform:uppercase;letter-spacing:.04em;font-weight:700;}

    .badge-prazo{display:inline-block;font-size:10px;padding:3px 8px;border-radius:4px;font-weight:700;text-transform:uppercase;letter-spacing:.04em;}
    .badge-prazo.atrasado{background:#fee2e2;color:#991b1b;}
    .badge-prazo.hoje{background:#fef3c7;color:#92400e;}
    .badge-prazo.proximo{background:#fef3c7;color:#a16207;}
    .badge-prazo.normal{background:#f1f5f9;color:#475569;}

    .ma-empty{text-align:center;padding:40px;color:#94a3b8;font-size:13px;}
    .ma-empty i{display:block;margin-bottom:8px;font-size:32px;color:#cbd5e1;}
</style>

<?php
function _badge_prazo($d) {
    if (empty($d)) return '<span class="badge-prazo normal">sem prazo</span>';
    $hoje = date('Y-m-d');
    if ($d < $hoje)              return '<span class="badge-prazo atrasado">atrasado · ' . date('d/m', strtotime($d)) . '</span>';
    if ($d == $hoje)             return '<span class="badge-prazo hoje">hoje</span>';
    if ($d <= date('Y-m-d', strtotime('+7 days')))
                                 return '<span class="badge-prazo proximo">' . date('d/m', strtotime($d)) . '</span>';
    return '<span class="badge-prazo normal">' . date('d/m/Y', strtotime($d)) . '</span>';
}
?>

<div class="content">
    <div class="ma-page">
        <ol class="breadcrumb" style="background:#fff;">
            <li><a href="<?php echo base_url('gestao_corporativa/intranet'); ?>"><i class="fa fa-home"></i> Home</a></li>
            <li class="active">Minhas Ações</li>
        </ol>

        <div class="ma-toolbar">
            <h2><i class="fa fa-tasks"></i> Minhas Ações</h2>
            <small style="color:#94a3b8;">tudo que está sob sua responsabilidade</small>
        </div>

        <div class="ma-counter">
            <div class="box" style="--c:#dc2626;">
                <div class="lbl">Tarefas</div>
                <div class="val"><?php echo count($tasks); ?></div>
            </div>
            <div class="box" style="--c:#7c3aed;">
                <div class="lbl">Decisões de atas</div>
                <div class="val"><?php echo count($decisoes); ?></div>
            </div>
            <div class="box" style="--c:#16a34a;">
                <div class="lbl">Itens de plano</div>
                <div class="val"><?php echo count($itens5w2h); ?></div>
            </div>
            <div class="box" style="--c:#0d9488;">
                <div class="lbl">Aprovações pendentes</div>
                <div class="val"><?php echo count($aprovacoes_doc); ?></div>
            </div>
        </div>

        <!-- TASKS -->
        <div class="ma-card">
            <div class="ma-card-header">
                <i class="fa fa-tasks" style="color:#dc2626;"></i> Tarefas (<?php echo count($tasks); ?>)
            </div>
            <?php if (empty($tasks)): ?>
                <div class="ma-empty"><i class="fa fa-check-circle"></i> Nenhuma tarefa atribuída a você.</div>
            <?php else: ?>
                <?php foreach ($tasks as $t): ?>
                    <div class="ma-row">
                        <div>
                            <span class="origem">Tarefa</span>
                            <a class="ttl" href="<?php echo admin_url('tasks/view/' . (int) $t['id']); ?>" target="_blank"><?php echo html_escape($t['name']); ?></a>
                            <div class="meta">
                                <?php if (!empty($t['project_name'])): ?>
                                    <span><i class="fa fa-folder-open"></i> <?php echo html_escape(mb_strimwidth($t['project_name'], 0, 40, '…')); ?></span>
                                <?php endif; ?>
                                <span><i class="fa fa-flag"></i> Prioridade <?php echo (int) $t['priority']; ?></span>
                            </div>
                        </div>
                        <div class="right"><?php echo _badge_prazo($t['duedate']); ?></div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- DECISÕES DE ATAS -->
        <div class="ma-card">
            <div class="ma-card-header">
                <i class="far fa-file-alt" style="color:#7c3aed;"></i> Decisões de atas (<?php echo count($decisoes); ?>)
            </div>
            <?php if (empty($decisoes)): ?>
                <div class="ma-empty"><i class="far fa-file-alt"></i> Nenhuma decisão atribuída a você.</div>
            <?php else: ?>
                <?php foreach ($decisoes as $d): ?>
                    <div class="ma-row">
                        <div>
                            <span class="origem">Decisão · Ata</span>
                            <a class="ttl" href="<?php echo base_url('gestao_corporativa/Ata/view/' . (int) $d['ata_id']); ?>"><?php echo html_escape(mb_strimwidth(strip_tags($d['descricao']), 0, 100, '…')); ?></a>
                            <div class="meta">
                                <span><i class="fa fa-link"></i> <?php echo html_escape($d['ata_titulo']); ?></span>
                                <?php if (!empty($d['task_id'])): ?>
                                    <span style="color:#16a34a;"><i class="fa fa-check"></i> Task #<?php echo (int) $d['task_id']; ?> gerada</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="right"><?php echo _badge_prazo($d['prazo']); ?></div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- 5W2H -->
        <div class="ma-card">
            <div class="ma-card-header">
                <i class="fas fa-clipboard-list" style="color:#16a34a;"></i> Itens de Plano de Ação (<?php echo count($itens5w2h); ?>)
            </div>
            <?php if (empty($itens5w2h)): ?>
                <div class="ma-empty"><i class="fas fa-clipboard-list"></i> Nenhum item de plano atribuído a você.</div>
            <?php else: ?>
                <?php foreach ($itens5w2h as $w): ?>
                    <div class="ma-row">
                        <div>
                            <span class="origem">5W2H · Plano de Ação</span>
                            <a class="ttl" href="<?php echo base_url('gestao_corporativa/Plano_acao/view/' . (int) $w['plano_id']); ?>"><?php echo html_escape(mb_strimwidth(strip_tags($w['what']), 0, 100, '…')); ?></a>
                            <div class="meta">
                                <span><i class="fa fa-link"></i> <?php echo html_escape($w['plano_titulo']); ?></span>
                                <?php if (!empty($w['local'])): ?><span><i class="fa fa-map-marker-alt"></i> <?php echo html_escape($w['local']); ?></span><?php endif; ?>
                                <?php if (!empty($w['task_id'])): ?>
                                    <span style="color:#16a34a;"><i class="fa fa-check"></i> Task #<?php echo (int) $w['task_id']; ?> gerada</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="right"><?php echo _badge_prazo($w['prazo']); ?></div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- APROVAÇÕES DE DOCUMENTO -->
        <?php if (!empty($aprovacoes_doc)): ?>
            <div class="ma-card">
                <div class="ma-card-header">
                    <i class="far fa-folder-open" style="color:#0d9488;"></i> Documentos aguardando sua aprovação (<?php echo count($aprovacoes_doc); ?>)
                </div>
                <?php foreach ($aprovacoes_doc as $a): ?>
                    <div class="ma-row">
                        <div>
                            <span class="origem">Aprovação · Documento</span>
                            <a class="ttl" href="<?php echo base_url('gestao_corporativa/Documento/view/' . (int) $a['doc_id']); ?>"><?php echo html_escape($a['titulo']); ?></a>
                            <div class="meta">
                                <span style="font-family:monospace;color:#94a3b8;font-weight:700;"><?php echo html_escape($a['codigo']); ?></span>
                                <span>etapa <?php echo (int) $a['fluxo_sequencia']; ?></span>
                            </div>
                        </div>
                        <div class="right">
                            <a href="<?php echo base_url('gestao_corporativa/Documento/view/' . (int) $a['doc_id']); ?>" class="btn btn-info btn-xs"><i class="fa fa-check"></i> Revisar</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php init_tail(); ?>
</body>
</html>
