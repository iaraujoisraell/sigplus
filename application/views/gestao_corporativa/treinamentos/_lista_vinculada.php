<?php
/**
 * Partial reutilizável: lista de treinamentos vinculados a projeto/plano/grupo/documento.
 * Espera: $vinculo_tipo ('project'|'plano'|'grupo'|'documento'), $vinculo_id
 */
defined('BASEPATH') or exit('No direct script access allowed');

$this->load->model('Treinamento_model');
$filtros = [];
$qs_param = '';
switch ($vinculo_tipo ?? '') {
    case 'project':   $filtros['project_id']   = (int) $vinculo_id; $qs_param = 'project_id';   break;
    case 'plano':     $filtros['plano_id']     = (int) $vinculo_id; $qs_param = 'plano_id';     break;
    case 'grupo':     $filtros['grupo_id']     = (int) $vinculo_id; $qs_param = 'grupo_id';     break;
    case 'documento': $filtros['documento_id'] = (int) $vinculo_id; $qs_param = 'documento_id'; break;
    default: return;
}
$treinamentos = $this->Treinamento_model->listar($filtros);
$titulo = $card_title ?? 'Treinamentos';
?>

<style>
    .trv-row{display:grid;grid-template-columns:1fr auto auto;gap:12px;align-items:center;padding:10px 0;border-bottom:1px solid #f1f5f9;}
    .trv-row:last-child{border-bottom:0;}
    .trv-row .titulo{font-weight:600;color:#1f2937;font-size:14px;text-decoration:none;}
    .trv-row .titulo:hover{color:#0a66c2;}
    .trv-row .codigo{font-family:monospace;font-size:11px;color:#94a3b8;font-weight:700;}
    .trv-row .meta{font-size:12px;color:#64748b;display:flex;gap:10px;flex-wrap:wrap;margin-top:3px;}
    .trv-row .stats{font-size:11px;color:#64748b;text-align:right;}
    .trv-row .stats strong{color:#1f2937;font-size:14px;}
    .trv-status{display:inline-block;padding:2px 8px;border-radius:6px;font-size:10px;font-weight:700;text-transform:uppercase;}
    .trv-empty{text-align:center;padding:24px 12px;color:#94a3b8;font-size:13px;}
</style>

<div class="ata-card form-card">
    <div class="ata-card-header form-card-header" style="display:flex;justify-content:space-between;align-items:center;">
        <span><i class="fa fa-graduation-cap"></i> <?php echo html_escape($titulo); ?> (<?php echo count($treinamentos); ?>)</span>
        <a href="<?php echo base_url('gestao_corporativa/Treinamento/add?' . $qs_param . '=' . (int) $vinculo_id); ?>" class="btn btn-info btn-xs"><i class="fa fa-plus"></i> Novo</a>
    </div>
    <div class="ata-card-body form-card-body">
        <?php if (empty($treinamentos)): ?>
            <div class="trv-empty"><i class="fa fa-graduation-cap fa-2x" style="color:#cbd5e1;display:block;margin-bottom:6px;"></i> Nenhum treinamento vinculado.</div>
        <?php else: ?>
            <?php foreach ($treinamentos as $t):
                $cor = $this->Treinamento_model->get_status_color($t['status']);
            ?>
                <div class="trv-row">
                    <div>
                        <div><span class="codigo"><?php echo html_escape($t['codigo']); ?></span> <span class="trv-status" style="background:<?php echo $cor; ?>20;color:<?php echo $cor; ?>;"><?php echo $this->Treinamento_model->get_status_label($t['status']); ?></span></div>
                        <a class="titulo" href="<?php echo base_url('gestao_corporativa/Treinamento/view/' . (int) $t['id']); ?>"><?php echo html_escape($t['titulo']); ?></a>
                        <div class="meta">
                            <span><i class="fa fa-laptop"></i> <?php echo $this->Treinamento_model->get_modalidade_label($t['modalidade']); ?></span>
                            <?php if ($t['carga_horaria']): ?><span><i class="fa fa-clock"></i> <?php echo (float) $t['carga_horaria']; ?>h</span><?php endif; ?>
                            <?php if ($t['dt_inicio']): ?><span><i class="fa fa-calendar"></i> <?php echo date('d/m/Y', strtotime($t['dt_inicio'])); ?></span><?php endif; ?>
                            <?php if (!empty($t['instrutor_nome'])): ?><span><i class="fa fa-chalkboard-teacher"></i> <?php echo html_escape($t['instrutor_nome']); ?></span><?php endif; ?>
                        </div>
                    </div>
                    <div class="stats">
                        <strong><?php echo (int) $t['total_inscritos']; ?></strong> insc.<br>
                        <small><?php echo (int) $t['total_presentes']; ?> pres. / <?php echo (int) $t['total_aprovados']; ?> aprov.</small>
                    </div>
                    <a href="<?php echo base_url('gestao_corporativa/Treinamento/view/' . (int) $t['id']); ?>" class="btn btn-default btn-xs"><i class="fa fa-eye"></i></a>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
