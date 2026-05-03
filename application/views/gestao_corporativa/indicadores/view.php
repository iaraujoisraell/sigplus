<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head_intranet_novo(false); ?>
<?php $this->load->view('gestao_corporativa/css_background'); ?>

<style>
    .ind-view-page{max-width:1100px;margin:24px auto;padding:0 18px;}
    .ind-card{background:#fff;border:1px solid #e5e7eb;border-radius:10px;margin-bottom:14px;}
    .ind-card-header{padding:14px 18px;border-bottom:1px solid #eef1f4;font-weight:600;color:#1f2937;font-size:14px;display:flex;justify-content:space-between;align-items:center;}
    .ind-card-body{padding:18px;}

    .ind-hero{display:grid;grid-template-columns:1fr 1fr 1fr;gap:14px;}
    .ind-hero .box{padding:14px;border-radius:8px;background:#f8fafc;text-align:center;}
    .ind-hero .lbl{font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.04em;}
    .ind-hero .val{font-size:32px;font-weight:700;line-height:1;margin-top:6px;}
    .ind-hero .sub{font-size:11px;color:#94a3b8;margin-top:4px;}
    @media(max-width:768px){.ind-hero{grid-template-columns:1fr;}}

    .ind-meta-row{display:flex;flex-wrap:wrap;gap:14px;font-size:13px;color:#475569;margin-top:12px;}
    .ind-meta-row span{display:flex;align-items:center;gap:6px;}

    .medicoes-table{width:100%;border-collapse:collapse;font-size:13px;}
    .medicoes-table th,.medicoes-table td{padding:10px 12px;text-align:left;border-bottom:1px solid #eef1f4;}
    .medicoes-table th{background:#f8fafc;font-size:11px;text-transform:uppercase;font-weight:700;color:#475569;}
    .medicoes-table tr:hover{background:#f8fafc;}

    .grafico-wrap{padding:8px;}
    canvas{max-width:100%;}

    .nova-medicao{background:#f0f9ff;border:1px dashed #bae6fd;border-radius:8px;padding:14px;}
</style>

<div class="content">
    <div class="ind-view-page">
        <ol class="breadcrumb" style="background:#fff;">
            <li><a href="<?php echo base_url('gestao_corporativa/intranet'); ?>"><i class="fa fa-home"></i> Home</a></li>
            <li><a href="<?php echo base_url('gestao_corporativa/Indicador'); ?>">Indicadores</a></li>
            <li class="active"><?php echo html_escape($indicador['codigo']); ?></li>
        </ol>

        <div class="ind-card">
            <div class="ind-card-header" style="border-left:4px solid <?php echo html_escape($indicador['cor']); ?>;border-top-left-radius:10px;">
                <div>
                    <strong style="font-family:monospace;color:#94a3b8;"><?php echo html_escape($indicador['codigo']); ?></strong>
                    <span style="margin-left:10px;font-size:16px;color:#1f2937;"><?php echo html_escape($indicador['nome']); ?></span>
                </div>
                <div style="display:flex;gap:6px;">
                    <?php if ($pode_editar): ?>
                        <a href="<?php echo base_url('gestao_corporativa/Indicador/edit/' . (int) $indicador['id']); ?>" class="btn btn-default btn-sm"><i class="fa fa-pencil"></i> Editar</a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="ind-card-body">
                <?php if (!empty($indicador['descricao'])): ?>
                    <p style="font-size:13px;color:#475569;margin:0 0 10px;"><?php echo html_escape($indicador['descricao']); ?></p>
                <?php endif; ?>
                <?php if (!empty($indicador['formula'])): ?>
                    <p style="font-size:12px;color:#94a3b8;margin:0 0 14px;font-family:monospace;background:#f8fafc;padding:6px 10px;border-radius:6px;display:inline-block;">
                        <i class="fa fa-calculator"></i> <?php echo html_escape($indicador['formula']); ?>
                    </p>
                <?php endif; ?>

                <?php
                $ultima = !empty($medicoes) ? end($medicoes) : null;
                $val = $ultima ? (float) $ultima['valor'] : null;
                $val_fmt = $val !== null ? number_format($val, (int) $indicador['casas_decimais'], ',', '.') . ($indicador['unidade'] ? ' ' . $indicador['unidade'] : '') : '—';
                $meta_fmt = $indicador['meta'] !== null ? number_format((float) $indicador['meta'], (int) $indicador['casas_decimais'], ',', '.') . ($indicador['unidade'] ? ' ' . $indicador['unidade'] : '') : '—';
                $ava = $this->Indicador_model->_avaliar([
                    'ultimo_valor' => $val, 'meta' => $indicador['meta'],
                    'meta_tipo' => $indicador['meta_tipo'], 'tolerancia' => $indicador['tolerancia'],
                ]);
                ?>
                <div class="ind-hero">
                    <div class="box" style="border-left:4px solid <?php echo $ava['cor']; ?>;">
                        <div class="lbl">Última medição</div>
                        <div class="val" style="color:<?php echo $ava['cor']; ?>;"><?php echo $val_fmt; ?></div>
                        <div class="sub"><?php echo $ultima ? $this->Indicador_model->format_periodo($indicador['periodicidade'], $ultima['periodo_referencia']) : 'sem dados'; ?></div>
                    </div>
                    <div class="box">
                        <div class="lbl">Meta</div>
                        <div class="val" style="color:#1f2937;"><?php echo $meta_fmt; ?></div>
                        <div class="sub"><?php echo $this->Indicador_model->get_meta_tipo_label($indicador['meta_tipo']); ?></div>
                    </div>
                    <div class="box">
                        <div class="lbl">Status</div>
                        <div class="val" style="color:<?php echo $ava['cor']; ?>;font-size:18px;"><?php echo $ava['label']; ?></div>
                        <div class="sub"><?php echo $ava['pct'] !== null ? $ava['pct'] . '% da meta' : ''; ?></div>
                    </div>
                </div>

                <div class="ind-meta-row">
                    <span><i class="fa fa-clock"></i> <?php echo $this->Indicador_model->get_periodicidade_label($indicador['periodicidade']); ?></span>
                    <?php if (!empty($indicador['setor_nome'])): ?><span><i class="fa fa-building"></i> <?php echo html_escape($indicador['setor_nome']); ?></span><?php endif; ?>
                    <?php if (!empty($indicador['responsavel_nome'])): ?><span><i class="fa fa-user"></i> <?php echo html_escape($indicador['responsavel_nome']); ?></span><?php endif; ?>
                    <?php if (!empty($indicador['project_name'])): ?><span><i class="fa fa-folder-open"></i> <?php echo html_escape($indicador['project_name']); ?></span><?php endif; ?>
                </div>
            </div>
        </div>

        <?php if (!empty($medicoes)): ?>
            <div class="ind-card">
                <div class="ind-card-header">Evolução</div>
                <div class="ind-card-body grafico-wrap">
                    <canvas id="grafico" height="100"></canvas>
                </div>
            </div>
        <?php endif; ?>

        <?php if ($pode_editar): ?>
            <div class="ind-card">
                <div class="ind-card-header">Registrar nova medição</div>
                <div class="ind-card-body">
                    <form method="post" action="<?php echo base_url('gestao_corporativa/Indicador/medicao_save'); ?>" class="nova-medicao">
                        <input type="hidden" name="indicador_id" value="<?php echo (int) $indicador['id']; ?>">
                        <div style="display:grid;grid-template-columns:1fr 1fr 2fr auto;gap:10px;align-items:end;">
                            <div>
                                <label style="font-size:11px;font-weight:700;color:#475569;text-transform:uppercase;">Período</label>
                                <input type="text" name="periodo_referencia" class="form-control" value="<?php echo $periodo_atual; ?>" required>
                            </div>
                            <div>
                                <label style="font-size:11px;font-weight:700;color:#475569;text-transform:uppercase;">Valor <?php if ($indicador['unidade']): ?>(<?php echo html_escape($indicador['unidade']); ?>)<?php endif; ?></label>
                                <input type="text" name="valor" class="form-control" required>
                            </div>
                            <div>
                                <label style="font-size:11px;font-weight:700;color:#475569;text-transform:uppercase;">Observação</label>
                                <input type="text" name="observacao" class="form-control">
                            </div>
                            <div>
                                <button type="submit" class="btn btn-info"><i class="fa fa-plus"></i> Registrar</button>
                            </div>
                        </div>
                        <small class="text-muted" style="margin-top:6px;display:block;">Se já existe medição para este período, será atualizada.</small>
                    </form>
                </div>
            </div>
        <?php endif; ?>

        <div class="ind-card">
            <div class="ind-card-header">Histórico (<?php echo count($medicoes); ?>)</div>
            <div class="ind-card-body" style="padding:0;">
                <?php if (empty($medicoes)): ?>
                    <p style="text-align:center;padding:24px;color:#94a3b8;">Sem medições ainda. Registre a primeira acima.</p>
                <?php else: ?>
                    <table class="medicoes-table">
                        <thead>
                            <tr>
                                <th>Período</th>
                                <th>Valor</th>
                                <th>Observação</th>
                                <th>Coletor</th>
                                <th>Data</th>
                                <?php if ($pode_editar): ?><th></th><?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach (array_reverse($medicoes) as $m):
                                $val_m = number_format((float) $m['valor'], (int) $indicador['casas_decimais'], ',', '.') . ($indicador['unidade'] ? ' ' . $indicador['unidade'] : '');
                                $ava_m = $this->Indicador_model->_avaliar([
                                    'ultimo_valor' => $m['valor'], 'meta' => $indicador['meta'],
                                    'meta_tipo' => $indicador['meta_tipo'], 'tolerancia' => $indicador['tolerancia'],
                                ]);
                            ?>
                                <tr>
                                    <td><?php echo $this->Indicador_model->format_periodo($indicador['periodicidade'], $m['periodo_referencia']); ?></td>
                                    <td><strong style="color:<?php echo $ava_m['cor']; ?>;"><?php echo $val_m; ?></strong></td>
                                    <td><?php echo html_escape($m['observacao'] ?? ''); ?></td>
                                    <td><?php echo html_escape($m['staff_nome'] ?? '—'); ?></td>
                                    <td><?php echo $m['dt_coleta'] ? date('d/m/Y H:i', strtotime($m['dt_coleta'])) : '—'; ?></td>
                                    <?php if ($pode_editar): ?>
                                        <td><a href="<?php echo base_url('gestao_corporativa/Indicador/medicao_delete/' . (int) $m['id']); ?>" class="btn btn-default btn-xs" onclick="return confirm('Excluir medição?')"><i class="fa fa-trash text-danger"></i></a></td>
                                    <?php endif; ?>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php init_tail(); ?>
<?php if (!empty($medicoes)): ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
(function(){
    var labels = <?php
        $lbls = array_map(function ($m) use ($indicador) {
            return $this->Indicador_model->format_periodo($indicador['periodicidade'], $m['periodo_referencia']);
        }, $medicoes);
        echo json_encode($lbls);
    ?>;
    var valores = <?php echo json_encode(array_map(function ($m) { return (float) $m['valor']; }, $medicoes)); ?>;
    var meta = <?php echo $indicador['meta'] !== null ? (float) $indicador['meta'] : 'null'; ?>;

    var ctx = document.getElementById('grafico').getContext('2d');
    var datasets = [{
        label: 'Valor',
        data: valores,
        borderColor: '<?php echo $indicador['cor']; ?>',
        backgroundColor: '<?php echo $indicador['cor']; ?>22',
        tension: 0.3, fill: true, pointRadius: 4, pointHoverRadius: 6,
    }];
    if (meta !== null) {
        datasets.push({
            label: 'Meta',
            data: valores.map(function(){ return meta; }),
            borderColor: '#16a34a', borderDash: [6,4],
            pointRadius: 0, fill: false, tension: 0,
        });
    }
    new Chart(ctx, {
        type: 'line',
        data: { labels: labels, datasets: datasets },
        options: {
            responsive: true,
            interaction: { mode: 'index', intersect: false },
            plugins: { legend: { position: 'bottom' } },
            scales: { y: { beginAtZero: false } }
        }
    });
})();
</script>
<?php endif; ?>
</body>
</html>
