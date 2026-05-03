<?php
defined('BASEPATH') or exit('No direct script access allowed');
$this->load->model('Risco_model');
$filtros = [];
$qs_param = '';
switch ($vinculo_tipo ?? '') {
    case 'project': $filtros['project_id'] = (int) $vinculo_id; $qs_param = 'project_id'; break;
    case 'plano':   $filtros['plano_id']   = (int) $vinculo_id; $qs_param = 'plano_id';   break;
    case 'grupo':   $filtros['grupo_id']   = (int) $vinculo_id; $qs_param = 'grupo_id';   break;
    default: return;
}
$riscos = $this->Risco_model->listar($filtros);
$titulo = $card_title ?? 'Riscos';
$model = $this->Risco_model;
?>

<style>
    .rkv-row{display:grid;grid-template-columns:50px 1fr auto;gap:12px;align-items:center;padding:10px 0;border-bottom:1px solid #f1f5f9;}
    .rkv-row:last-child{border-bottom:0;}
    .rkv-sev{display:flex;flex-direction:column;align-items:center;justify-content:center;background:var(--c);color:#fff;border-radius:6px;padding:4px;}
    .rkv-sev .num{font-size:16px;font-weight:700;line-height:1;}
    .rkv-sev .lbl{font-size:8px;text-transform:uppercase;font-weight:700;}
    .rkv-row .codigo{font-family:monospace;font-size:11px;color:#94a3b8;font-weight:700;}
    .rkv-row .titulo{font-weight:600;color:#1f2937;font-size:13px;text-decoration:none;}
    .rkv-row .titulo:hover{color:#0a66c2;}
    .rkv-row .meta{font-size:11px;color:#64748b;display:flex;gap:10px;flex-wrap:wrap;margin-top:3px;}
    .rkv-empty{text-align:center;padding:24px 12px;color:#94a3b8;font-size:13px;}
</style>

<div class="ata-card form-card">
    <div class="ata-card-header form-card-header" style="display:flex;justify-content:space-between;align-items:center;">
        <span><i class="fa fa-shield-alt"></i> <?php echo html_escape($titulo); ?> (<?php echo count($riscos); ?>)</span>
        <a href="<?php echo base_url('gestao_corporativa/Risco/add?' . $qs_param . '=' . (int) $vinculo_id); ?>" class="btn btn-info btn-xs"><i class="fa fa-plus"></i> Novo</a>
    </div>
    <div class="ata-card-body form-card-body">
        <?php if (empty($riscos)): ?>
            <div class="rkv-empty"><i class="fa fa-shield-alt fa-2x" style="color:#cbd5e1;display:block;margin-bottom:6px;"></i> Nenhum risco vinculado.</div>
        <?php else: ?>
            <?php foreach ($riscos as $r): $cor = $model->get_nivel_color($r['nivel']); ?>
                <div class="rkv-row">
                    <div class="rkv-sev" style="--c:<?php echo $cor; ?>;background:<?php echo $cor; ?>;">
                        <div class="num"><?php echo (int) $r['severidade']; ?></div>
                        <div class="lbl"><?php echo strtoupper(substr($model->get_nivel_label($r['nivel']), 0, 3)); ?></div>
                    </div>
                    <div>
                        <span class="codigo"><?php echo html_escape($r['codigo']); ?></span>
                        <a class="titulo" href="<?php echo base_url('gestao_corporativa/Risco/view/' . (int) $r['id']); ?>"><?php echo html_escape($r['titulo']); ?></a>
                        <div class="meta">
                            <span><i class="fa fa-tag"></i> <?php echo $model->get_categoria_label($r['categoria']); ?></span>
                            <span><?php echo $model->get_status_label($r['status']); ?></span>
                            <?php if ($r['responsavel_nome']): ?><span><i class="fa fa-user"></i> <?php echo html_escape($r['responsavel_nome']); ?></span><?php endif; ?>
                        </div>
                    </div>
                    <a href="<?php echo base_url('gestao_corporativa/Risco/view/' . (int) $r['id']); ?>" class="btn btn-default btn-xs"><i class="fa fa-eye"></i></a>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
