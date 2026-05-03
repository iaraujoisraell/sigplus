<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head_intranet_novo(false); ?>
<?php $this->load->view('gestao_corporativa/css_background'); ?>

<style>
    .qd-page{max-width:1500px;margin:24px auto;padding:0 18px;}
    .qd-toolbar{display:flex;justify-content:space-between;align-items:center;margin-bottom:14px;}
    .qd-toolbar h2{margin:0;font-size:22px;color:#1f2937;display:flex;align-items:center;gap:10px;}
    .qd-toolbar h2 i{color:#0d9488;}

    /* Hero KPI cards */
    .qd-hero{display:grid;grid-template-columns:repeat(6,1fr);gap:10px;margin-bottom:18px;}
    @media(max-width:1100px){.qd-hero{grid-template-columns:repeat(3,1fr);}}
    @media(max-width:600px){.qd-hero{grid-template-columns:repeat(2,1fr);}}
    .qd-hero .kpi{background:#fff;border:1px solid #e5e7eb;border-radius:10px;padding:14px;border-left:4px solid var(--c, #94a3b8);}
    .qd-hero .kpi .lbl{font-size:10px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.04em;}
    .qd-hero .kpi .val{font-size:28px;font-weight:700;line-height:1;margin-top:6px;color:var(--c, #1f2937);}
    .qd-hero .kpi .sub{font-size:10px;color:#94a3b8;margin-top:6px;}
    .qd-hero .kpi .sub strong{color:#dc2626;}

    /* Faixa RAC */
    .qd-rac-band{background:linear-gradient(135deg,#312e81,#1e3a8a);color:#fff;border-radius:10px;padding:14px 18px;margin-bottom:18px;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;}
    .qd-rac-band .titulo{font-size:13px;opacity:.8;text-transform:uppercase;letter-spacing:.06em;}
    .qd-rac-band .item-rac{font-size:14px;}
    .qd-rac-band .item-rac strong{font-size:16px;display:block;margin-bottom:2px;}
    .qd-rac-band a{color:#fff;text-decoration:underline;}

    /* Grid principal */
    .qd-grid{display:grid;grid-template-columns:1fr 1fr;gap:14px;}
    @media(max-width:900px){.qd-grid{grid-template-columns:1fr;}}

    .qd-card{background:#fff;border:1px solid #e5e7eb;border-radius:10px;margin-bottom:14px;}
    .qd-card-header{padding:12px 18px;border-bottom:1px solid #eef1f4;font-weight:600;color:#1f2937;font-size:14px;display:flex;justify-content:space-between;align-items:center;}
    .qd-card-header a{font-size:11px;color:#0a66c2;text-decoration:none;font-weight:600;}
    .qd-card-body{padding:0;}

    .qd-row{display:grid;grid-template-columns:1fr auto;gap:10px;align-items:center;padding:10px 18px;border-bottom:1px solid #f1f5f9;}
    .qd-row:last-child{border-bottom:0;}
    .qd-row .ttl{font-size:13px;color:#1f2937;font-weight:600;text-decoration:none;display:block;}
    .qd-row .ttl:hover{color:#0a66c2;}
    .qd-row .meta{font-size:11px;color:#64748b;margin-top:2px;display:flex;gap:10px;flex-wrap:wrap;}
    .qd-row .right{text-align:right;}
    .qd-row .codigo{font-family:monospace;font-size:10px;color:#94a3b8;font-weight:700;}
    .qd-row .valor-grande{font-size:18px;font-weight:700;line-height:1;}
    .qd-row .valor-grande small{font-size:9px;color:#94a3b8;display:block;font-weight:400;}
    .qd-row.compact{padding:8px 18px;}

    .qd-status{display:inline-block;padding:2px 8px;border-radius:6px;font-size:10px;font-weight:700;letter-spacing:.04em;text-transform:uppercase;}
    .qd-empty{text-align:center;padding:24px;color:#94a3b8;font-size:13px;}

    .pct-bar{height:5px;background:#f3f4f6;border-radius:999px;margin-top:6px;overflow:hidden;width:100%;}
    .pct-bar > div{height:100%;border-radius:999px;}

    .badge-prazo{font-size:10px;padding:2px 6px;border-radius:4px;font-weight:600;}
    .badge-prazo.atrasado{background:#fee2e2;color:#991b1b;}
    .badge-prazo.proximo{background:#fef3c7;color:#92400e;}

    .sev-mini{display:inline-flex;align-items:center;justify-content:center;background:var(--c);color:#fff;border-radius:4px;padding:2px 8px;font-size:11px;font-weight:700;}
</style>

<div class="content">
    <div class="qd-page">
        <ol class="breadcrumb" style="background:#fff;">
            <li><a href="<?php echo base_url('gestao_corporativa/intranet'); ?>"><i class="fa fa-home"></i> Home</a></li>
            <li class="active">Dashboard de Qualidade</li>
        </ol>

        <div class="qd-toolbar">
            <h2><i class="fa fa-shield-alt"></i> Dashboard de Qualidade</h2>
            <small style="color:#94a3b8;"><?php echo date('d/m/Y H:i'); ?></small>
        </div>

        <!-- Faixa RAC -->
        <?php if ($rac_proxima || $rac_ultima): ?>
            <div class="qd-rac-band">
                <div>
                    <div class="titulo"><i class="fa fa-balance-scale"></i> Análise Crítica pela Direção</div>
                </div>
                <?php if ($rac_proxima): ?>
                    <div class="item-rac">
                        <strong>Próxima RAC <?php echo $rac_proxima['dt_realizacao'] ? '· ' . date('d/m/Y', strtotime($rac_proxima['dt_realizacao'])) : ''; ?></strong>
                        <a href="<?php echo base_url('gestao_corporativa/Rac/view/' . (int) $rac_proxima['id']); ?>"><?php echo html_escape($rac_proxima['codigo'] . ' — ' . $rac_proxima['titulo']); ?></a>
                    </div>
                <?php endif; ?>
                <?php if ($rac_ultima): ?>
                    <div class="item-rac">
                        <strong>Última realizada · <?php echo date('d/m/Y', strtotime($rac_ultima['dt_realizacao'])); ?></strong>
                        <a href="<?php echo base_url('gestao_corporativa/Rac/view/' . (int) $rac_ultima['id']); ?>"><?php echo html_escape($rac_ultima['codigo']); ?></a>
                    </div>
                <?php endif; ?>
                <a href="<?php echo base_url('gestao_corporativa/Rac'); ?>" style="background:rgba(255,255,255,.15);padding:8px 14px;border-radius:6px;font-size:13px;font-weight:600;">Ver todas →</a>
            </div>
        <?php endif; ?>

        <!-- Hero KPIs -->
        <div class="qd-hero">
            <div class="kpi" style="--c:#dc2626;">
                <div class="lbl">Indicadores em alerta</div>
                <div class="val"><?php echo count($ind_critico) + count($ind_atencao); ?></div>
                <div class="sub"><?php echo count($ind_critico); ?> crítico · <?php echo count($ind_atencao); ?> atenção</div>
            </div>
            <div class="kpi" style="--c:#dc2626;">
                <div class="lbl">Riscos críticos</div>
                <div class="val"><?php echo $riscos_criticos; ?></div>
                <div class="sub">de <?php echo $riscos_total; ?> ativos<?php if ($riscos_revisao_vencida): ?> · <strong><?php echo $riscos_revisao_vencida; ?></strong> com revisão vencida<?php endif; ?></div>
            </div>
            <div class="kpi" style="--c:#7c3aed;">
                <div class="lbl">NCs de auditoria abertas</div>
                <div class="val"><?php echo $achados_ncs_abertas; ?></div>
                <div class="sub"><?php echo $aud_planejadas; ?> auditoria(s) planejada(s)</div>
            </div>
            <div class="kpi" style="--c:#f59e0b;">
                <div class="lbl">Ocorrências abertas</div>
                <div class="val"><?php echo $ocorr_total_abertas; ?></div>
                <div class="sub"><?php if ($ocorr_atrasadas > 0): ?><strong><?php echo $ocorr_atrasadas; ?></strong> vencidas<?php else: ?>nenhuma vencida<?php endif; ?></div>
            </div>
            <div class="kpi" style="--c:#16a34a;">
                <div class="lbl">Planos ativos</div>
                <div class="val"><?php echo $plano_total_ativos; ?></div>
                <div class="sub"><?php if ($plano_atrasados > 0): ?><strong><?php echo $plano_atrasados; ?></strong> atrasado(s)<?php else: ?>todos no prazo<?php endif; ?></div>
            </div>
            <div class="kpi" style="--c:#ea580c;">
                <div class="lbl">Treinamentos em curso</div>
                <div class="val"><?php echo $trein_em_andamento; ?></div>
                <div class="sub"><?php if ($trein_atrasados > 0): ?><strong><?php echo $trein_atrasados; ?></strong> atrasado(s)<?php else: ?>todos no prazo<?php endif; ?></div>
            </div>
        </div>

        <div class="qd-grid">
            <!-- COLUNA ESQUERDA -->
            <div>
                <!-- Indicadores em alerta -->
                <div class="qd-card">
                    <div class="qd-card-header">
                        <span><i class="fa fa-chart-line" style="color:#dc2626;"></i> Indicadores em alerta</span>
                        <a href="<?php echo base_url('gestao_corporativa/Indicador'); ?>">ver todos →</a>
                    </div>
                    <div class="qd-card-body">
                        <?php $alerta = array_merge($ind_critico, $ind_atencao); ?>
                        <?php if (empty($alerta)): ?>
                            <div class="qd-empty"><i class="fa fa-check-circle fa-2x" style="color:#16a34a;display:block;margin-bottom:6px;"></i> Todos os indicadores na meta!</div>
                        <?php else: ?>
                            <?php foreach ($alerta as $i):
                                $ava = $i['avaliacao'];
                                $val_fmt = $i['ultimo_valor'] !== null ? number_format((float) $i['ultimo_valor'], (int) $i['casas_decimais'], ',', '.') . ($i['unidade'] ? ' ' . $i['unidade'] : '') : '—';
                                $meta_fmt = $i['meta'] !== null ? number_format((float) $i['meta'], (int) $i['casas_decimais'], ',', '.') . ($i['unidade'] ? ' ' . $i['unidade'] : '') : '—';
                            ?>
                                <div class="qd-row">
                                    <div>
                                        <span class="codigo"><?php echo html_escape($i['codigo']); ?></span>
                                        <a class="ttl" href="<?php echo base_url('gestao_corporativa/Indicador/view/' . (int) $i['id']); ?>"><?php echo html_escape($i['nome']); ?></a>
                                        <div class="meta">
                                            <span class="qd-status" style="background:<?php echo $ava['cor']; ?>20;color:<?php echo $ava['cor']; ?>;"><?php echo $ava['label']; ?></span>
                                            <span>Meta: <?php echo $meta_fmt; ?></span>
                                            <?php if (!empty($i['responsavel_nome'])): ?><span><i class="fa fa-user"></i> <?php echo html_escape($i['responsavel_nome']); ?></span><?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="right">
                                        <div class="valor-grande" style="color:<?php echo $ava['cor']; ?>;"><?php echo $val_fmt; ?></div>
                                        <small><?php echo $ava['pct'] !== null ? $ava['pct'] . '%' : ''; ?></small>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Riscos top -->
                <div class="qd-card">
                    <div class="qd-card-header">
                        <span><i class="fa fa-shield-alt" style="color:#dc2626;"></i> Top riscos abertos</span>
                        <a href="<?php echo base_url('gestao_corporativa/Risco'); ?>">matriz completa →</a>
                    </div>
                    <div class="qd-card-body">
                        <?php if (empty($riscos_top)): ?>
                            <div class="qd-empty">Nenhum risco crítico aberto.</div>
                        <?php else: ?>
                            <?php foreach ($riscos_top as $r):
                                $cor = $this->Risco_model->get_nivel_color($r['nivel']);
                            ?>
                                <div class="qd-row">
                                    <div>
                                        <span class="codigo"><?php echo html_escape($r['codigo']); ?></span>
                                        <a class="ttl" href="<?php echo base_url('gestao_corporativa/Risco/view/' . (int) $r['id']); ?>"><?php echo html_escape($r['titulo']); ?></a>
                                        <div class="meta">
                                            <span class="sev-mini" style="--c:<?php echo $cor; ?>;background:<?php echo $cor; ?>;">Sev. <?php echo (int) $r['severidade']; ?></span>
                                            <span><?php echo $this->Risco_model->get_nivel_label($r['nivel']); ?></span>
                                            <span><?php echo $this->Risco_model->get_categoria_label($r['categoria']); ?></span>
                                            <?php if ($r['responsavel_nome']): ?><span><i class="fa fa-user"></i> <?php echo html_escape($r['responsavel_nome']); ?></span><?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Treinamentos próximos -->
                <div class="qd-card">
                    <div class="qd-card-header">
                        <span><i class="fa fa-graduation-cap" style="color:#ea580c;"></i> Treinamentos próximos / em curso</span>
                        <a href="<?php echo base_url('gestao_corporativa/Treinamento'); ?>">ver todos →</a>
                    </div>
                    <div class="qd-card-body">
                        <?php if (empty($trein_proximos)): ?>
                            <div class="qd-empty">Nenhum treinamento programado.</div>
                        <?php else: ?>
                            <?php foreach ($trein_proximos as $t):
                                $cor_st = $this->Treinamento_model->get_status_color($t['status']);
                            ?>
                                <div class="qd-row">
                                    <div>
                                        <span class="codigo"><?php echo html_escape($t['codigo']); ?></span>
                                        <a class="ttl" href="<?php echo base_url('gestao_corporativa/Treinamento/view/' . (int) $t['id']); ?>"><?php echo html_escape($t['titulo']); ?></a>
                                        <div class="meta">
                                            <span class="qd-status" style="background:<?php echo $cor_st; ?>20;color:<?php echo $cor_st; ?>;"><?php echo $this->Treinamento_model->get_status_label($t['status']); ?></span>
                                            <?php if ($t['dt_inicio']): ?><span><i class="fa fa-calendar"></i> <?php echo date('d/m/Y H:i', strtotime($t['dt_inicio'])); ?></span><?php endif; ?>
                                            <span><i class="fa fa-laptop"></i> <?php echo $this->Treinamento_model->get_modalidade_label($t['modalidade']); ?></span>
                                            <?php if ($t['instrutor_nome']): ?><span><i class="fa fa-user"></i> <?php echo html_escape($t['instrutor_nome']); ?></span><?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="right">
                                        <div class="valor-grande" style="color:#0a66c2;"><?php echo (int) $t['total_inscritos']; ?></div>
                                        <small>inscritos</small>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- COLUNA DIREITA -->
            <div>
                <!-- NCs de auditoria abertas -->
                <div class="qd-card">
                    <div class="qd-card-header">
                        <span><i class="fa fa-clipboard-check" style="color:#7c3aed;"></i> Não-conformidades abertas (auditoria)</span>
                        <a href="<?php echo base_url('gestao_corporativa/Auditoria'); ?>">ver auditorias →</a>
                    </div>
                    <div class="qd-card-body">
                        <?php if (empty($achados_recentes)): ?>
                            <div class="qd-empty">Nenhuma NC aberta. 👍</div>
                        <?php else: ?>
                            <?php foreach ($achados_recentes as $ac):
                                $cor_ac = $this->Auditoria_model->get_tipo_achado_color($ac['tipo']);
                                $atras = !empty($ac['prazo_tratamento']) && $ac['prazo_tratamento'] < date('Y-m-d');
                            ?>
                                <div class="qd-row">
                                    <div>
                                        <span class="codigo"><?php echo html_escape($ac['aud_codigo']); ?></span>
                                        <a class="ttl" href="<?php echo base_url('gestao_corporativa/Auditoria/view/' . (int) $ac['aud_id']); ?>"><?php echo html_escape(mb_strimwidth($ac['descricao'], 0, 90, '…')); ?></a>
                                        <div class="meta">
                                            <span class="qd-status" style="background:<?php echo $cor_ac; ?>;color:#fff;"><?php echo $this->Auditoria_model->get_tipo_achado_label($ac['tipo']); ?></span>
                                            <span><?php echo $this->Auditoria_model->get_status_achado_label($ac['status']); ?></span>
                                            <?php if ($ac['responsavel_nome']): ?><span><i class="fa fa-user"></i> <?php echo html_escape($ac['responsavel_nome']); ?></span><?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="right">
                                        <?php if ($atras): ?>
                                            <span class="badge-prazo atrasado">venceu <?php echo date('d/m', strtotime($ac['prazo_tratamento'])); ?></span>
                                        <?php elseif ($ac['prazo_tratamento']): ?>
                                            <span class="badge-prazo proximo"><?php echo date('d/m', strtotime($ac['prazo_tratamento'])); ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Ocorrências abertas -->
                <div class="qd-card">
                    <div class="qd-card-header">
                        <span><i class="far fa-flag" style="color:#dc2626;"></i> Ocorrências abertas</span>
                        <a href="<?php echo base_url('gestao_corporativa/Registro_ocorrencia'); ?>">ver todas →</a>
                    </div>
                    <div class="qd-card-body">
                        <?php if (empty($ocorr_recentes)): ?>
                            <div class="qd-empty">Nenhuma ocorrência aberta.</div>
                        <?php else: ?>
                            <?php
                            $st_lbl = [1 => 'Aberta', 2 => 'Em progresso', 3 => 'Respondida', 4 => 'Em espera'];
                            $st_cor = [1 => '#dc2626', 2 => '#f59e0b', 3 => '#0a66c2', 4 => '#7c3aed'];
                            foreach ($ocorr_recentes as $o):
                                $atrasada = !empty($o['validade']) && $o['validade'] < date('Y-m-d');
                            ?>
                                <div class="qd-row">
                                    <div>
                                        <a class="ttl" href="<?php echo base_url('gestao_corporativa/Registro_ocorrencia/view/' . (int) $o['id']); ?>"><?php echo html_escape($o['subject']); ?></a>
                                        <div class="meta">
                                            <span class="qd-status" style="background:<?php echo ($st_cor[$o['status']] ?? '#94a3b8'); ?>20;color:<?php echo ($st_cor[$o['status']] ?? '#94a3b8'); ?>;"><?php echo ($st_lbl[$o['status']] ?? '—'); ?></span>
                                            <?php if (!empty($o['categoria_nome'])): ?><span><i class="fa fa-tag"></i> <?php echo html_escape($o['categoria_nome']); ?></span><?php endif; ?>
                                            <?php if (!empty($o['responsavel_nome'])): ?><span><i class="fa fa-user"></i> <?php echo html_escape($o['responsavel_nome']); ?></span><?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="right">
                                        <?php if ($atrasada): ?>
                                            <span class="badge-prazo atrasado">vencido</span>
                                        <?php elseif (!empty($o['validade'])): ?>
                                            <span class="badge-prazo" style="color:#94a3b8;"><?php echo date('d/m', strtotime($o['validade'])); ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Planos atrasados -->
                <div class="qd-card">
                    <div class="qd-card-header">
                        <span><i class="fas fa-clipboard-list" style="color:#16a34a;"></i> Planos de ação ativos</span>
                        <a href="<?php echo base_url('gestao_corporativa/Plano_acao'); ?>">ver todos →</a>
                    </div>
                    <div class="qd-card-body">
                        <?php if (empty($planos_recentes)): ?>
                            <div class="qd-empty">Nenhum plano ativo.</div>
                        <?php else: ?>
                            <?php foreach ($planos_recentes as $p):
                                $atrasado = !empty($p['dt_fim']) && $p['dt_fim'] < date('Y-m-d');
                                $proximo  = !$atrasado && !empty($p['dt_fim']) && $p['dt_fim'] <= date('Y-m-d', strtotime('+7 days'));
                            ?>
                                <div class="qd-row">
                                    <div>
                                        <a class="ttl" href="<?php echo base_url('gestao_corporativa/Plano_acao/view/' . (int) $p['id']); ?>"><?php echo html_escape($p['titulo']); ?></a>
                                        <div class="meta">
                                            <span class="qd-status" style="background:#dcfce7;color:#166534;"><?php echo strtoupper($p['metodologia']); ?></span>
                                            <?php if ($p['responsavel_nome']): ?><span><i class="fa fa-user"></i> <?php echo html_escape($p['responsavel_nome']); ?></span><?php endif; ?>
                                            <?php if ($p['project_name']): ?><span><i class="fa fa-folder-open"></i> <?php echo html_escape(mb_strimwidth($p['project_name'], 0, 25, '…')); ?></span><?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="right">
                                        <?php if ($p['dt_fim']): ?>
                                            <span class="badge-prazo <?php echo $atrasado ? 'atrasado' : ($proximo ? 'proximo' : ''); ?>" style="<?php echo !$atrasado && !$proximo ? 'color:#94a3b8;' : ''; ?>">
                                                <?php echo $atrasado ? 'atrasado · ' : ($proximo ? '' : ''); ?><?php echo date('d/m', strtotime($p['dt_fim'])); ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Documentos com baixa cobertura -->
                <?php if (!empty($doc_baixa_cobertura)): ?>
                    <div class="qd-card">
                        <div class="qd-card-header">
                            <span><i class="far fa-folder-open" style="color:#0d9488;"></i> Documentos com baixa leitura</span>
                            <a href="<?php echo base_url('gestao_corporativa/Documento'); ?>">ver todos →</a>
                        </div>
                        <div class="qd-card-body">
                            <?php foreach ($doc_baixa_cobertura as $d):
                                $pct = (int) $d['pct'];
                                $cor = $pct < 50 ? '#dc2626' : '#f59e0b';
                            ?>
                                <div class="qd-row">
                                    <div style="width:100%;">
                                        <span class="codigo"><?php echo html_escape($d['codigo']); ?></span>
                                        <a class="ttl" href="<?php echo base_url('gestao_corporativa/Documento/view/' . (int) $d['id']); ?>"><?php echo html_escape($d['titulo']); ?></a>
                                        <div class="meta"><span><i class="fa fa-eye"></i> <?php echo $d['lidos']; ?> de <?php echo $d['total']; ?> leram (<?php echo $pct; ?>%)</span></div>
                                        <div class="pct-bar"><div style="width:<?php echo $pct; ?>%;background:<?php echo $cor; ?>;"></div></div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

    </div>
</div>

<?php init_tail(); ?>
</body>
</html>
