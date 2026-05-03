<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head_intranet_novo(false); ?>
<?php $this->load->view('gestao_corporativa/css_background'); ?>

<style>
    .rk-view-page{max-width:1100px;margin:24px auto;padding:0 18px;}
    .rk-card{background:#fff;border:1px solid #e5e7eb;border-radius:10px;margin-bottom:14px;}
    .rk-card-header{padding:14px 18px;border-bottom:1px solid #eef1f4;font-weight:600;color:#1f2937;font-size:14px;display:flex;justify-content:space-between;align-items:center;}
    .rk-card-body{padding:18px;}

    .rk-hero{display:grid;grid-template-columns:160px 1fr;gap:18px;align-items:center;}
    @media(max-width:600px){.rk-hero{grid-template-columns:1fr;}}
    .rk-sev-big{background:var(--c);color:#fff;border-radius:12px;padding:24px;text-align:center;}
    .rk-sev-big .num{font-size:64px;font-weight:700;line-height:1;}
    .rk-sev-big .lbl{font-size:13px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;margin-top:4px;}
    .rk-sev-big .pi{font-size:11px;opacity:.85;margin-top:8px;}

    .rk-meta{display:flex;flex-wrap:wrap;gap:14px;font-size:13px;color:#475569;}
    .rk-meta span{display:flex;align-items:center;gap:6px;}
    .rk-status{padding:3px 10px;border-radius:6px;font-size:11px;font-weight:700;text-transform:uppercase;}

    .rk-cell-2{display:grid;grid-template-columns:1fr 1fr;gap:14px;}
    @media(max-width:768px){.rk-cell-2{grid-template-columns:1fr;}}
    .rk-cell{background:#f8fafc;border:1px solid #e5e7eb;border-radius:8px;padding:12px;}
    .rk-cell .lbl{font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.04em;margin-bottom:4px;}
    .rk-cell .val{font-size:13px;color:#1f2937;line-height:1.5;}

    .av-table{width:100%;border-collapse:collapse;font-size:12px;}
    .av-table th,.av-table td{padding:8px 12px;text-align:left;border-bottom:1px solid #eef1f4;}
    .av-table th{background:#f8fafc;font-size:10px;text-transform:uppercase;color:#475569;font-weight:700;}
</style>

<?php $model = $this->Risco_model; $cor = $model->get_nivel_color($risco['nivel']); ?>

<div class="content">
    <div class="rk-view-page">
        <ol class="breadcrumb" style="background:#fff;">
            <li><a href="<?php echo base_url('gestao_corporativa/intranet'); ?>"><i class="fa fa-home"></i> Home</a></li>
            <li><a href="<?php echo base_url('gestao_corporativa/Risco'); ?>">Riscos</a></li>
            <li class="active"><?php echo html_escape($risco['codigo']); ?></li>
        </ol>

        <div class="rk-card">
            <div class="rk-card-header" style="border-left:4px solid <?php echo $cor; ?>;border-top-left-radius:10px;">
                <div>
                    <strong style="font-family:monospace;color:#94a3b8;"><?php echo html_escape($risco['codigo']); ?></strong>
                    <span class="rk-status" style="background:<?php echo $cor; ?>20;color:<?php echo $cor; ?>;margin-left:8px;"><?php echo $model->get_status_label($risco['status']); ?></span>
                </div>
                <?php if ($pode_editar): ?>
                    <a href="<?php echo base_url('gestao_corporativa/Risco/edit/' . (int) $risco['id']); ?>" class="btn btn-default btn-sm"><i class="fa fa-pencil"></i> Editar</a>
                <?php endif; ?>
            </div>
            <div class="rk-card-body">
                <div class="rk-hero" style="--c:<?php echo $cor; ?>;">
                    <div class="rk-sev-big">
                        <div class="num"><?php echo (int) $risco['severidade']; ?></div>
                        <div class="lbl"><?php echo $model->get_nivel_label($risco['nivel']); ?></div>
                        <div class="pi">P<?php echo (int) $risco['probabilidade']; ?> × I<?php echo (int) $risco['impacto']; ?></div>
                    </div>
                    <div>
                        <h2 style="margin:0 0 8px;font-size:22px;color:#1f2937;"><?php echo html_escape($risco['titulo']); ?></h2>
                        <?php if (!empty($risco['descricao'])): ?>
                            <p style="font-size:14px;color:#475569;margin:0 0 10px;"><?php echo html_escape($risco['descricao']); ?></p>
                        <?php endif; ?>
                        <div class="rk-meta">
                            <span><i class="fa fa-tag"></i> <?php echo $model->get_categoria_label($risco['categoria']); ?></span>
                            <span><i class="fa fa-shield-alt"></i> <?php echo $model->get_tratamento_label($risco['tipo_tratamento']); ?></span>
                            <?php if ($risco['setor_nome']): ?><span><i class="fa fa-building"></i> <?php echo html_escape($risco['setor_nome']); ?></span><?php endif; ?>
                            <?php if ($risco['responsavel_nome']): ?><span><i class="fa fa-user"></i> <?php echo html_escape($risco['responsavel_nome']); ?></span><?php endif; ?>
                            <?php if ($risco['project_name']): ?><span><i class="fa fa-folder"></i> <?php echo html_escape($risco['project_name']); ?></span><?php endif; ?>
                            <?php if ($risco['plano_titulo']): ?><span><i class="fas fa-clipboard-list"></i> <a href="<?php echo base_url('gestao_corporativa/Plano_acao/view/' . (int) $risco['plano_id']); ?>"><?php echo html_escape($risco['plano_titulo']); ?></a></span><?php endif; ?>
                            <?php if ($risco['dt_revisao']):
                                $atras = $risco['dt_revisao'] < date('Y-m-d');
                            ?>
                                <span style="color:<?php echo $atras ? '#dc2626' : '#94a3b8'; ?>;"><i class="fa fa-redo"></i> revisar <?php echo date('d/m/Y', strtotime($risco['dt_revisao'])); ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php if (!empty($risco['causa']) || !empty($risco['consequencia']) || !empty($risco['controles_existentes'])): ?>
            <div class="rk-card">
                <div class="rk-card-header">Análise</div>
                <div class="rk-card-body">
                    <div class="rk-cell-2" style="margin-bottom:14px;">
                        <?php if (!empty($risco['causa'])): ?>
                            <div class="rk-cell"><div class="lbl">🔍 Causa</div><div class="val"><?php echo nl2br(html_escape($risco['causa'])); ?></div></div>
                        <?php endif; ?>
                        <?php if (!empty($risco['consequencia'])): ?>
                            <div class="rk-cell"><div class="lbl">⚠️ Consequência</div><div class="val"><?php echo nl2br(html_escape($risco['consequencia'])); ?></div></div>
                        <?php endif; ?>
                    </div>
                    <?php if (!empty($risco['controles_existentes'])): ?>
                        <div class="rk-cell"><div class="lbl">🛡 Controles existentes</div><div class="val"><?php echo nl2br(html_escape($risco['controles_existentes'])); ?></div></div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>

        <?php if (!empty($risco['plano_tratamento'])): ?>
            <div class="rk-card">
                <div class="rk-card-header">Plano de tratamento</div>
                <div class="rk-card-body" style="font-size:14px;color:#1f2937;line-height:1.6;"><?php echo nl2br(html_escape($risco['plano_tratamento'])); ?></div>
            </div>
        <?php endif; ?>

        <?php if (count($avaliacoes) > 1): ?>
            <div class="rk-card">
                <div class="rk-card-header">Evolução da severidade</div>
                <div class="rk-card-body">
                    <canvas id="grafico" height="80"></canvas>
                </div>
            </div>
        <?php endif; ?>

        <div class="rk-card">
            <div class="rk-card-header">Histórico de avaliações (<?php echo count($avaliacoes); ?>)</div>
            <table class="av-table">
                <thead><tr><th>Data</th><th>Probabilidade</th><th>Impacto</th><th>Severidade</th><th>Observação</th><th>Por</th></tr></thead>
                <tbody>
                    <?php foreach (array_reverse($avaliacoes) as $a):
                        $cor_a = $model->get_nivel_color($model->calc_nivel($a['severidade']));
                    ?>
                        <tr>
                            <td><?php echo date('d/m/Y H:i', strtotime($a['dt_avaliacao'])); ?></td>
                            <td><?php echo (int) $a['probabilidade']; ?></td>
                            <td><?php echo (int) $a['impacto']; ?></td>
                            <td><strong style="color:<?php echo $cor_a; ?>;"><?php echo (int) $a['severidade']; ?></strong></td>
                            <td><?php echo html_escape($a['observacao']); ?></td>
                            <td><?php echo html_escape($a['staff_nome'] ?? '—'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php init_tail(); ?>
<?php if (count($avaliacoes) > 1): ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
(function(){
    var labels = <?php echo json_encode(array_map(function($a){ return date('d/m', strtotime($a['dt_avaliacao'])); }, $avaliacoes)); ?>;
    var dados  = <?php echo json_encode(array_map(function($a){ return (int) $a['severidade']; }, $avaliacoes)); ?>;
    new Chart(document.getElementById('grafico').getContext('2d'), {
        type: 'line',
        data: { labels: labels, datasets: [{ label: 'Severidade', data: dados, borderColor: '<?php echo $cor; ?>', backgroundColor: '<?php echo $cor; ?>22', tension: 0.3, fill: true }] },
        options: { responsive: true, scales: { y: { beginAtZero: true, max: 25 } } }
    });
})();
</script>
<?php endif; ?>
</body>
</html>
