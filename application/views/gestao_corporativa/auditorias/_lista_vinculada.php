<?php
defined('BASEPATH') or exit('No direct script access allowed');
$this->load->model('Auditoria_model');
$filtros = []; $qs_param = '';
switch ($vinculo_tipo ?? '') {
    case 'project':   $filtros['project_id']   = (int) $vinculo_id; $qs_param = 'project_id';   break;
    case 'plano':     $filtros['plano_id']     = (int) $vinculo_id; $qs_param = 'plano_id';     break;
    case 'grupo':     $filtros['grupo_id']     = (int) $vinculo_id; $qs_param = 'grupo_id';     break;
    case 'documento': $filtros['documento_id'] = (int) $vinculo_id; $qs_param = 'documento_id'; break;
    default: return;
}
$auditorias = $this->Auditoria_model->listar($filtros);
$titulo = $card_title ?? 'Auditorias';
$model = $this->Auditoria_model;
?>

<style>
    .auv-row{display:grid;grid-template-columns:1fr auto auto;gap:12px;align-items:center;padding:10px 0;border-bottom:1px solid #f1f5f9;}
    .auv-row:last-child{border-bottom:0;}
    .auv-row .codigo{font-family:monospace;font-size:11px;color:#94a3b8;font-weight:700;}
    .auv-row .titulo{font-weight:600;color:#1f2937;font-size:14px;text-decoration:none;}
    .auv-row .titulo:hover{color:#0a66c2;}
    .auv-row .meta{font-size:11px;color:#64748b;display:flex;gap:10px;flex-wrap:wrap;margin-top:3px;}
    .auv-pill{padding:2px 8px;border-radius:6px;font-size:10px;font-weight:700;text-transform:uppercase;}
    .auv-empty{text-align:center;padding:24px 12px;color:#94a3b8;font-size:13px;}
</style>

<div class="ata-card form-card">
    <div class="ata-card-header form-card-header" style="display:flex;justify-content:space-between;align-items:center;">
        <span><i class="fa fa-clipboard-check"></i> <?php echo html_escape($titulo); ?> (<?php echo count($auditorias); ?>)</span>
        <a href="<?php echo base_url('gestao_corporativa/Auditoria/add?' . $qs_param . '=' . (int) $vinculo_id); ?>" class="btn btn-info btn-xs"><i class="fa fa-plus"></i> Nova</a>
    </div>
    <div class="ata-card-body form-card-body">
        <?php if (empty($auditorias)): ?>
            <div class="auv-empty"><i class="fa fa-clipboard-check fa-2x" style="color:#cbd5e1;display:block;margin-bottom:6px;"></i> Nenhuma auditoria vinculada.</div>
        <?php else: ?>
            <?php foreach ($auditorias as $a):
                $cor_st = $model->get_status_color($a['status']);
                $cor_res = $model->get_resultado_color($a['resultado']);
            ?>
                <div class="auv-row">
                    <div>
                        <span class="codigo"><?php echo html_escape($a['codigo']); ?></span>
                        <a class="titulo" href="<?php echo base_url('gestao_corporativa/Auditoria/view/' . (int) $a['id']); ?>"><?php echo html_escape($a['titulo']); ?></a>
                        <div class="meta">
                            <span class="auv-pill" style="background:<?php echo $cor_st; ?>20;color:<?php echo $cor_st; ?>;"><?php echo $model->get_status_label($a['status']); ?></span>
                            <span class="auv-pill" style="background:<?php echo $cor_res; ?>20;color:<?php echo $cor_res; ?>;"><?php echo $model->get_resultado_label($a['resultado']); ?></span>
                            <?php if ($a['dt_planejada']): ?><span><i class="fa fa-calendar"></i> <?php echo date('d/m/Y', strtotime($a['dt_planejada'])); ?></span><?php endif; ?>
                            <?php if ($a['auditor_nome']): ?><span><i class="fa fa-user-tie"></i> <?php echo html_escape($a['auditor_nome']); ?></span><?php endif; ?>
                        </div>
                    </div>
                    <div style="text-align:right;font-size:11px;color:#475569;">
                        <strong style="color:#dc2626;font-size:14px;"><?php echo (int) $a['total_ncs']; ?></strong> NC(s)<br>
                        <small><?php echo (int) $a['total_achados']; ?> achados</small>
                    </div>
                    <a href="<?php echo base_url('gestao_corporativa/Auditoria/view/' . (int) $a['id']); ?>" class="btn btn-default btn-xs"><i class="fa fa-eye"></i></a>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
