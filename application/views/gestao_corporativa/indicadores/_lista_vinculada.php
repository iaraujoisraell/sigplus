<?php
/**
 * Partial reutilizável: lista de indicadores vinculados a projeto/fase/etc.
 * Espera: $vinculo_tipo ('project'|'fase'), $vinculo_id
 */
defined('BASEPATH') or exit('No direct script access allowed');

$this->load->model('Indicador_model');
$filtros = [];
$qs_param = '';
switch ($vinculo_tipo ?? '') {
    case 'project': $filtros['project_id'] = (int) $vinculo_id; $qs_param = 'project_id'; break;
    case 'fase':    $filtros['fase_id']    = (int) $vinculo_id; $qs_param = 'fase_id';    break;
    default: return;
}
$indicadores = $this->Indicador_model->listar($filtros);
$titulo = $card_title ?? 'Indicadores';
?>

<style>
    .indv-row{display:grid;grid-template-columns:1fr auto auto;gap:12px;align-items:center;padding:10px 0;border-bottom:1px solid #f1f5f9;}
    .indv-row:last-child{border-bottom:0;}
    .indv-row .titulo{font-weight:600;color:#1f2937;font-size:14px;text-decoration:none;}
    .indv-row .titulo:hover{color:#0a66c2;}
    .indv-row .codigo{font-family:monospace;font-size:11px;color:#94a3b8;font-weight:700;}
    .indv-row .meta{font-size:11px;color:#64748b;display:flex;gap:10px;margin-top:3px;}
    .indv-row .valor{font-size:18px;font-weight:700;line-height:1;text-align:right;}
    .indv-row .valor small{display:block;color:#94a3b8;font-weight:400;font-size:10px;margin-top:2px;}
    .indv-status{display:inline-block;padding:2px 8px;border-radius:6px;font-size:10px;font-weight:700;text-transform:uppercase;}
    .indv-empty{text-align:center;padding:24px 12px;color:#94a3b8;font-size:13px;}
</style>

<div class="ata-card form-card">
    <div class="ata-card-header form-card-header" style="display:flex;justify-content:space-between;align-items:center;">
        <span><i class="fa fa-chart-line"></i> <?php echo html_escape($titulo); ?> (<?php echo count($indicadores); ?>)</span>
        <a href="<?php echo base_url('gestao_corporativa/Indicador/add?' . $qs_param . '=' . (int) $vinculo_id); ?>" class="btn btn-info btn-xs"><i class="fa fa-plus"></i> Novo</a>
    </div>
    <div class="ata-card-body form-card-body">
        <?php if (empty($indicadores)): ?>
            <div class="indv-empty"><i class="fa fa-chart-line fa-2x" style="color:#cbd5e1;display:block;margin-bottom:6px;"></i> Nenhum indicador vinculado.</div>
        <?php else: ?>
            <?php foreach ($indicadores as $i):
                $ava = $i['avaliacao'];
                $val = $i['ultimo_valor'];
                $val_fmt = $val !== null ? number_format((float) $val, (int) $i['casas_decimais'], ',', '.') . ($i['unidade'] ? ' ' . $i['unidade'] : '') : '—';
                $per = $i['ultimo_periodo'] ? $this->Indicador_model->format_periodo($i['periodicidade'], $i['ultimo_periodo']) : '';
            ?>
                <div class="indv-row">
                    <div>
                        <div><span class="codigo"><?php echo html_escape($i['codigo']); ?></span> <span class="indv-status" style="background:<?php echo $ava['cor']; ?>20;color:<?php echo $ava['cor']; ?>;"><?php echo $ava['label']; ?></span></div>
                        <a class="titulo" href="<?php echo base_url('gestao_corporativa/Indicador/view/' . (int) $i['id']); ?>"><?php echo html_escape($i['nome']); ?></a>
                        <div class="meta">
                            <?php if (!empty($i['responsavel_nome'])): ?><span><i class="fa fa-user"></i> <?php echo html_escape($i['responsavel_nome']); ?></span><?php endif; ?>
                            <span><i class="fa fa-clock"></i> <?php echo $this->Indicador_model->get_periodicidade_label($i['periodicidade']); ?></span>
                            <span><i class="fa fa-database"></i> <?php echo (int) $i['total_medicoes']; ?> coletas</span>
                        </div>
                    </div>
                    <div class="valor" style="color:<?php echo $ava['cor']; ?>;">
                        <?php echo $val_fmt; ?>
                        <small><?php echo $per; ?></small>
                    </div>
                    <a href="<?php echo base_url('gestao_corporativa/Indicador/view/' . (int) $i['id']); ?>" class="btn btn-default btn-xs"><i class="fa fa-eye"></i></a>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
