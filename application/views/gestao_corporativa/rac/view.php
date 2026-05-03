<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head_intranet_novo(false); ?>
<?php $this->load->view('gestao_corporativa/css_background'); ?>

<style>
    .rac-view-page{max-width:1100px;margin:24px auto;padding:0 18px;}
    .rac-card{background:#fff;border:1px solid #e5e7eb;border-radius:10px;margin-bottom:14px;}
    .rac-card-header{padding:14px 18px;border-bottom:1px solid #eef1f4;font-weight:600;color:#1f2937;font-size:14px;display:flex;justify-content:space-between;align-items:center;}
    .rac-card-body{padding:18px;}
    .rac-meta{display:flex;flex-wrap:wrap;gap:14px;font-size:13px;color:#475569;margin-top:8px;}
    .rac-status{padding:3px 10px;border-radius:6px;font-size:11px;font-weight:700;text-transform:uppercase;}

    .secao{padding:14px 0;border-bottom:1px solid #f1f5f9;}
    .secao:last-child{border-bottom:0;}
    .secao h4{margin:0 0 8px;font-size:14px;color:#1f2937;}
    .secao .conteudo{font-size:13px;color:#475569;line-height:1.6;}

    .snap-row{display:grid;grid-template-columns:repeat(4,1fr);gap:8px;}
    .snap-box{background:#f8fafc;border:1px solid #e5e7eb;border-radius:8px;padding:10px;text-align:center;border-left:3px solid var(--c, #94a3b8);}
    .snap-box .lbl{font-size:10px;font-weight:700;color:#64748b;text-transform:uppercase;}
    .snap-box .val{font-size:20px;font-weight:700;color:var(--c, #1f2937);line-height:1;margin-top:3px;}
    @media(max-width:768px){.snap-row{grid-template-columns:1fr 1fr;}}

    .part-pill{display:inline-flex;align-items:center;gap:6px;padding:5px 10px;background:#f1f5f9;border-radius:6px;margin:2px;font-size:12px;}
    .part-pill .papel{font-size:9px;background:#0a66c2;color:#fff;padding:1px 5px;border-radius:3px;text-transform:uppercase;}
    .part-pill.ausente{opacity:.5;}
</style>

<?php $model = $this->Rac_model; ?>

<div class="content">
    <div class="rac-view-page">
        <ol class="breadcrumb" style="background:#fff;">
            <li><a href="<?php echo base_url('gestao_corporativa/intranet'); ?>"><i class="fa fa-home"></i> Home</a></li>
            <li><a href="<?php echo base_url('gestao_corporativa/Rac'); ?>">RAC</a></li>
            <li class="active"><?php echo html_escape($rac['codigo']); ?></li>
        </ol>

        <div class="rac-card">
            <div class="rac-card-header" style="border-left:4px solid <?php echo $model->get_status_color($rac['status']); ?>;border-top-left-radius:10px;">
                <div>
                    <strong style="font-family:monospace;color:#94a3b8;"><?php echo html_escape($rac['codigo']); ?></strong>
                    <span class="rac-status" style="background:<?php echo $model->get_status_color($rac['status']); ?>20;color:<?php echo $model->get_status_color($rac['status']); ?>;margin-left:8px;"><?php echo $model->get_status_label($rac['status']); ?></span>
                </div>
                <?php if ($pode_editar): ?>
                    <a href="<?php echo base_url('gestao_corporativa/Rac/edit/' . (int) $rac['id']); ?>" class="btn btn-default btn-sm"><i class="fa fa-pencil"></i> Editar</a>
                <?php endif; ?>
            </div>
            <div class="rac-card-body">
                <h2 style="margin:0 0 6px;font-size:22px;color:#1f2937;"><?php echo html_escape($rac['titulo']); ?></h2>
                <div class="rac-meta">
                    <span><i class="fa fa-calendar"></i> Período: <?php echo date('d/m/Y', strtotime($rac['periodo_inicio'])); ?> → <?php echo date('d/m/Y', strtotime($rac['periodo_fim'])); ?></span>
                    <?php if ($rac['dt_realizacao']): ?><span style="color:#16a34a;"><i class="fa fa-check"></i> realizada <?php echo date('d/m/Y', strtotime($rac['dt_realizacao'])); ?></span><?php endif; ?>
                    <span><i class="fa fa-laptop"></i> <?php echo $model->get_modalidade_label($rac['modalidade']); ?></span>
                    <?php if ($rac['local']): ?><span><i class="fa fa-map-marker-alt"></i> <?php echo html_escape($rac['local']); ?></span><?php endif; ?>
                    <?php if ($rac['presidida_nome']): ?><span><i class="fa fa-user-tie"></i> Presidida por <?php echo html_escape($rac['presidida_nome']); ?></span><?php endif; ?>
                    <?php if ($rac['secretario_nome']): ?><span><i class="fa fa-pencil"></i> Secretário(a): <?php echo html_escape($rac['secretario_nome']); ?></span><?php endif; ?>
                    <?php if ($rac['proxima_rac']): ?><span style="color:#94a3b8;"><i class="fa fa-redo"></i> Próxima: <?php echo date('d/m/Y', strtotime($rac['proxima_rac'])); ?></span><?php endif; ?>
                </div>
                <?php if ($rac['escopo']): ?>
                    <div style="margin-top:10px;padding:10px;background:#f8fafc;border-radius:8px;font-size:13px;color:#475569;">
                        <strong>Escopo:</strong> <?php echo nl2br(html_escape($rac['escopo'])); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <?php if (!empty($participantes)): ?>
            <div class="rac-card">
                <div class="rac-card-header">Comitê (<?php echo count($participantes); ?>)</div>
                <div class="rac-card-body">
                    <?php foreach ($participantes as $p): ?>
                        <span class="part-pill <?php echo (int) $p['presente'] === 1 ? '' : 'ausente'; ?>">
                            <?php echo html_escape($p['staff_nome']); ?>
                            <span class="papel"><?php echo $model->get_papel_label($p['papel']); ?></span>
                            <?php if ((int) $p['presente'] === 1): ?><i class="fa fa-check" style="color:#16a34a;"></i><?php endif; ?>
                        </span>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <?php if ($snapshot): ?>
            <div class="rac-card">
                <div class="rac-card-header">📊 Snapshot do período</div>
                <div class="rac-card-body">
                    <div class="snap-row" style="margin-bottom:10px;">
                        <div class="snap-box" style="--c:#dc2626;"><div class="lbl">Indicadores críticos</div><div class="val"><?php echo (int) $snapshot['indicadores_critico']; ?></div></div>
                        <div class="snap-box" style="--c:#f59e0b;"><div class="lbl">Indicadores em atenção</div><div class="val"><?php echo (int) $snapshot['indicadores_atencao']; ?></div></div>
                        <div class="snap-box" style="--c:#16a34a;"><div class="lbl">Indicadores OK</div><div class="val"><?php echo (int) $snapshot['indicadores_atingido']; ?></div></div>
                        <div class="snap-box" style="--c:#dc2626;"><div class="lbl">Riscos críticos</div><div class="val"><?php echo (int) $snapshot['riscos_criticos']; ?></div></div>
                    </div>
                    <div class="snap-row">
                        <div class="snap-box" style="--c:#7c3aed;"><div class="lbl">Auditorias com NC</div><div class="val"><?php echo (int) $snapshot['auditorias_ncs']; ?></div></div>
                        <div class="snap-box" style="--c:#0a66c2;"><div class="lbl">Ocorrências</div><div class="val"><?php echo (int) $snapshot['ocorrencias_total']; ?></div></div>
                        <div class="snap-box" style="--c:#dc2626;"><div class="lbl">Planos atrasados</div><div class="val"><?php echo (int) $snapshot['planos_atrasados']; ?></div></div>
                        <div class="snap-box" style="--c:#16a34a;"><div class="lbl">Treinamentos realizados</div><div class="val"><?php echo (int) $snapshot['treinamentos_realizados']; ?></div></div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <?php
        $secoes = [
            ['analise_indicadores',    '📈 Indicadores e desempenho'],
            ['analise_riscos',         '⚠️ Análise dos riscos'],
            ['analise_auditorias',     '🔍 Resultados das auditorias'],
            ['analise_ocorrencias',    '🚩 NCs e ocorrências'],
            ['analise_satisfacao',     '😊 Satisfação dos clientes'],
            ['analise_oportunidades',  '💡 Oportunidades de melhoria'],
            ['decisoes_estrategicas',  '🎯 Decisões estratégicas'],
            ['recursos_necessarios',   '💰 Recursos necessários'],
        ];
        $tem_conteudo = false;
        foreach ($secoes as $sec) if (!empty($rac[$sec[0]])) { $tem_conteudo = true; break; }
        ?>

        <?php if ($tem_conteudo): ?>
            <div class="rac-card">
                <div class="rac-card-header">Análise Crítica e Saídas</div>
                <div class="rac-card-body">
                    <?php foreach ($secoes as $sec): ?>
                        <?php if (!empty($rac[$sec[0]])): ?>
                            <div class="secao">
                                <h4><?php echo $sec[1]; ?></h4>
                                <div class="conteudo"><?php echo $rac[$sec[0]]; ?></div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

    </div>
</div>

<?php init_tail(); ?>
</body>
</html>
