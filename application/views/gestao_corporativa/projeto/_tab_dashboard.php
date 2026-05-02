<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php
$pid = (int) $project->id;
$this->load->model('Projeto_fase_model');
$d = $this->Projeto_fase_model->dashboard($pid);
?>

<style>
    .pdash-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:14px;margin-bottom:18px;}
    .pdash-card{background:#fff;border:1px solid #e5e7eb;border-radius:10px;padding:16px;}
    .pdash-card .label{font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.04em;}
    .pdash-card .value{font-size:28px;font-weight:700;color:#1f2937;margin-top:4px;line-height:1;}
    .pdash-card .sub{font-size:11px;color:#94a3b8;margin-top:4px;}
    .pdash-card.is-warn{border-color:#fde68a;background:#fffbeb;}
    .pdash-card.is-warn .value{color:#92400e;}
    .pdash-card.is-good{border-color:#bbf7d0;background:#f0fdf4;}
    .pdash-card.is-good .value{color:#16a34a;}

    .pdash-progress-wrap{background:#fff;border:1px solid #e5e7eb;border-radius:10px;padding:18px;margin-bottom:18px;}
    .pdash-progress-wrap .label{font-size:13px;font-weight:600;color:#1f2937;}
    .pdash-progress-bar{height:14px;background:#f3f4f6;border-radius:999px;margin-top:8px;overflow:hidden;}
    .pdash-progress-bar > div{height:100%;background:linear-gradient(90deg,#0a66c2,#16a34a);border-radius:999px;transition:.3s;}

    .pdash-quick-actions{display:flex;gap:8px;flex-wrap:wrap;margin-top:14px;}
    .pdash-quick-actions a{padding:7px 14px;border:1px solid #d0d5dd;border-radius:8px;font-size:13px;font-weight:600;text-decoration:none;color:#475569;}
    .pdash-quick-actions a:hover{border-color:#0a66c2;color:#0a66c2;}
</style>

<div class="row" style="padding-top:10px;">
    <div class="col-md-12">
        <div class="pdash-grid">
            <div class="pdash-card">
                <div class="label">Fases</div>
                <div class="value"><?php echo $d['fases_total']; ?></div>
                <div class="sub"><?php echo $d['fases_concluidas']; ?> concluídas · <?php echo $d['fases_em_execucao']; ?> em execução</div>
            </div>
            <div class="pdash-card <?php echo $d['fases_atrasadas'] > 0 ? 'is-warn' : ''; ?>">
                <div class="label">Fases atrasadas</div>
                <div class="value"><?php echo $d['fases_atrasadas']; ?></div>
                <div class="sub">prazo previsto vencido</div>
            </div>
            <div class="pdash-card">
                <div class="label">Atas</div>
                <div class="value"><?php echo $d['atas']; ?></div>
            </div>
            <div class="pdash-card">
                <div class="label">Planos de ação</div>
                <div class="value"><?php echo $d['planos_total']; ?></div>
                <div class="sub"><?php echo $d['planos_ativos']; ?> em andamento</div>
            </div>
            <div class="pdash-card">
                <div class="label">Tasks abertas</div>
                <div class="value"><?php echo $d['tasks_abertas']; ?></div>
            </div>
            <div class="pdash-card">
                <div class="label">Grupos</div>
                <div class="value"><?php echo $d['grupos']; ?></div>
            </div>
        </div>

        <div class="pdash-progress-wrap">
            <div class="label">Progresso geral (média das fases)</div>
            <div class="pdash-progress-bar"><div style="width:<?php echo $d['pct_geral']; ?>%;"></div></div>
            <div style="text-align:right;font-size:12px;color:#475569;margin-top:6px;"><?php echo $d['pct_geral']; ?>%</div>
        </div>

        <div class="pdash-quick-actions">
            <a href="<?php echo base_url('gestao_corporativa/Ata/add?project_id=' . $pid); ?>"><i class="fa fa-plus"></i> Nova ata</a>
            <a href="<?php echo base_url('gestao_corporativa/Plano_acao/add?project_id=' . $pid); ?>"><i class="fa fa-plus"></i> Novo plano</a>
            <a href="<?php echo base_url('gestao_corporativa/Workgroup/add?project_id=' . $pid); ?>"><i class="fa fa-plus"></i> Novo grupo</a>
            <a href="<?php echo base_url('gestao_corporativa/Eventoplus/add?project_id=' . $pid); ?>"><i class="fa fa-plus"></i> Novo evento</a>
        </div>
    </div>
</div>
