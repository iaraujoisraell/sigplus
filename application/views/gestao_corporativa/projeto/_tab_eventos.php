<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php
$pid = (int) $project->id;
$this->load->model('Eventoplus_model');
$eventos = $this->Eventoplus_model->listar(['project_id' => $pid]);
?>

<style>
    .ptab-row{background:#fff;border:1px solid #e5e7eb;border-radius:10px;padding:12px 16px;margin-bottom:8px;display:grid;grid-template-columns:60px 1fr auto;gap:12px;align-items:center;}
    .ptab-row .data-box{text-align:center;border-radius:8px;background:#f3f6fa;padding:6px 0;}
    .ptab-row .data-box .dia{font-size:18px;font-weight:700;color:#0a66c2;line-height:1;}
    .ptab-row .data-box .mes{font-size:10px;font-weight:700;color:#64748b;text-transform:uppercase;}
    .ptab-row .titulo a{color:#1f2937;font-weight:600;font-size:14px;text-decoration:none;}
    .ptab-row .titulo a:hover{color:#0a66c2;}
    .ptab-row .meta{font-size:12px;color:#64748b;margin-top:4px;display:flex;gap:12px;flex-wrap:wrap;}
    .ptab-empty{text-align:center;padding:40px;color:#94a3b8;}
    .ptab-toolbar{display:flex;justify-content:space-between;align-items:center;margin:14px 0;}
    .ev-dot{display:inline-block;width:10px;height:10px;border-radius:50%;margin-right:4px;vertical-align:middle;}
</style>

<div class="row" style="padding-top:10px;">
    <div class="col-md-12">
        <div class="ptab-toolbar">
            <h4 style="margin:0;">Eventos (<?php echo count($eventos); ?>)</h4>
            <a href="<?php echo base_url('gestao_corporativa/Eventoplus/add?project_id=' . $pid); ?>" class="btn btn-info btn-sm"><i class="fa fa-plus"></i> Novo evento</a>
        </div>

        <?php if (empty($eventos)): ?>
            <div class="ptab-empty"><i class="far fa-calendar-alt fa-2x" style="color:#cbd5e1;display:block;margin-bottom:6px;"></i> Nenhum evento vinculado.</div>
        <?php else: ?>
            <?php $meses = ['','jan','fev','mar','abr','mai','jun','jul','ago','set','out','nov','dez']; foreach ($eventos as $e):
                $ts = strtotime($e['start']);
            ?>
                <div class="ptab-row">
                    <div class="data-box">
                        <div class="dia"><?php echo date('d', $ts); ?></div>
                        <div class="mes"><?php echo $meses[(int) date('n', $ts)]; ?></div>
                    </div>
                    <div>
                        <div class="titulo">
                            <span class="ev-dot" style="background:<?php echo html_escape($e['color'] ?? '#0a66c2'); ?>;"></span>
                            <a href="<?php echo base_url('gestao_corporativa/Eventoplus/view/' . (int) $e['id']); ?>"><?php echo html_escape($e['title']); ?></a>
                        </div>
                        <div class="meta">
                            <span><i class="fa fa-clock-o"></i><?php echo date('d/m/Y H:i', $ts); ?></span>
                            <?php if (!empty($e['onde'])): ?><span><i class="fa fa-map-marker"></i><?php echo html_escape($e['onde']); ?></span><?php endif; ?>
                            <?php if (!empty($e['criador_nome'])): ?><span><i class="fa fa-user"></i><?php echo html_escape($e['criador_nome']); ?></span><?php endif; ?>
                        </div>
                    </div>
                    <a href="<?php echo base_url('gestao_corporativa/Eventoplus/view/' . (int) $e['id']); ?>" class="btn btn-default btn-sm"><i class="fa fa-eye"></i> Ver</a>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
