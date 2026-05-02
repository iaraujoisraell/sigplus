<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php
$pid = (int) $project->id;
$this->load->model('Plano_acao_model');
$planos = $this->Plano_acao_model->listar(['project_id' => $pid]);
$hoje = date('Y-m-d');
?>

<style>
    .ptab-row{background:#fff;border:1px solid #e5e7eb;border-radius:10px;padding:12px 16px;margin-bottom:8px;display:grid;grid-template-columns:1fr auto;gap:12px;align-items:center;}
    .ptab-row.is-atrasado{border-left:4px solid #ef4444;}
    .ptab-row .titulo a{color:#1f2937;font-weight:600;font-size:14px;text-decoration:none;}
    .ptab-row .titulo a:hover{color:#0a66c2;}
    .ptab-row .meta{font-size:12px;color:#64748b;margin-top:4px;display:flex;gap:12px;flex-wrap:wrap;}
    .ptab-empty{text-align:center;padding:40px;color:#94a3b8;}
    .ptab-toolbar{display:flex;justify-content:space-between;align-items:center;margin:14px 0;}
    .pill{font-size:11px;font-weight:600;padding:2px 9px;border-radius:999px;}
    .pill-aberto{background:#dbeafe;color:#1e40af;}
    .pill-em_execucao{background:#fef9c3;color:#854d0e;}
    .pill-concluido{background:#dcfce7;color:#166534;}
    .pill-cancelado{background:#fee2e2;color:#991b1b;}
    .pill-atrasado{background:#fecaca;color:#7f1d1d;}
    .metod{background:#eef2ff;color:#4338ca;font-size:10px;font-weight:700;padding:2px 7px;border-radius:6px;}
</style>

<div class="row" style="padding-top:10px;">
    <div class="col-md-12">
        <div class="ptab-toolbar">
            <h4 style="margin:0;">Planos de Ação (<?php echo count($planos); ?>)</h4>
            <a href="<?php echo base_url('gestao_corporativa/Plano_acao/add?project_id=' . $pid); ?>" class="btn btn-info btn-sm"><i class="fa fa-plus"></i> Novo plano</a>
        </div>

        <?php if (empty($planos)): ?>
            <div class="ptab-empty"><i class="fas fa-clipboard-list fa-2x" style="color:#cbd5e1;display:block;margin-bottom:6px;"></i> Nenhum plano vinculado ainda.</div>
        <?php else: ?>
            <?php foreach ($planos as $p):
                $atrasado = !empty($p['dt_fim']) && $p['dt_fim'] < $hoje && in_array($p['status'], ['aberto','em_execucao'], true);
                $cls = 'ptab-row' . ($atrasado ? ' is-atrasado' : '');
            ?>
                <div class="<?php echo $cls; ?>">
                    <div>
                        <div class="titulo">
                            <a href="<?php echo base_url('gestao_corporativa/Plano_acao/view/' . (int) $p['id']); ?>"><?php echo html_escape($p['titulo']); ?></a>
                            <span class="pill pill-<?php echo $p['status']; ?>"><?php echo $this->Plano_acao_model->get_status_label($p['status']); ?></span>
                            <span class="metod"><?php echo strtoupper($p['metodologia']); ?></span>
                            <?php if ($atrasado): ?><span class="pill pill-atrasado">Atrasado</span><?php endif; ?>
                        </div>
                        <div class="meta">
                            <?php if (!empty($p['responsavel_nome'])): ?><span><i class="fa fa-user"></i><?php echo html_escape($p['responsavel_nome']); ?></span><?php endif; ?>
                            <?php if (!empty($p['dt_fim'])): ?><span><i class="fa fa-clock-o"></i>Prazo: <?php echo date('d/m/Y', strtotime($p['dt_fim'])); ?></span><?php endif; ?>
                            <?php if (!empty($p['ata_titulo'])): ?><span><i class="far fa-file-alt"></i><?php echo html_escape($p['ata_titulo']); ?></span><?php endif; ?>
                        </div>
                    </div>
                    <a href="<?php echo base_url('gestao_corporativa/Plano_acao/view/' . (int) $p['id']); ?>" class="btn btn-default btn-sm"><i class="fa fa-eye"></i> Ver</a>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
