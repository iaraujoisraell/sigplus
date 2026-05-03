<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head_intranet_novo(false); ?>
<?php $this->load->view('gestao_corporativa/css_background'); ?>

<style>
    .doc-view-page{max-width:1100px;margin:24px auto;padding:0 18px;}
    .doc-card{background:#fff;border:1px solid #e5e7eb;border-radius:10px;margin-bottom:14px;}
    .doc-card-header{padding:14px 18px;border-bottom:1px solid #eef1f4;font-weight:600;color:#1f2937;font-size:14px;display:flex;justify-content:space-between;align-items:center;}
    .doc-card-body{padding:18px;}

    .doc-meta{display:flex;flex-wrap:wrap;gap:14px;font-size:13px;color:#475569;margin:8px 0 0;}
    .doc-meta span{display:flex;align-items:center;gap:6px;}
    .doc-status{display:inline-block;padding:4px 12px;border-radius:6px;font-size:11px;font-weight:700;letter-spacing:.04em;text-transform:uppercase;}
    .doc-status.rascunho{background:#fef3c7;color:#92400e;}
    .doc-status.em_aprovacao{background:#fef3c7;color:#92400e;}
    .doc-status.aprovado{background:#dcfce7;color:#166534;}
    .doc-status.publicado{background:#dbeafe;color:#1e40af;}
    .doc-status.reprovado{background:#fee2e2;color:#991b1b;}
    .doc-status.arquivado{background:#f1f5f9;color:#475569;}

    .conteudo-doc{font-size:14px;color:#1f2937;line-height:1.6;}
    .conteudo-doc h1,.conteudo-doc h2,.conteudo-doc h3{margin-top:14px;}
    .conteudo-doc table{border-collapse:collapse;width:100%;margin:8px 0;}
    .conteudo-doc table td,.conteudo-doc table th{border:1px solid #e5e7eb;padding:6px 10px;}

    .file-attached{display:flex;align-items:center;gap:12px;padding:12px 16px;background:#f0f9ff;border:1px solid #bae6fd;border-radius:8px;}
    .file-attached i{font-size:24px;color:#0a66c2;}
    .file-attached .nome{flex:1;font-size:14px;color:#1f2937;font-weight:500;}

    .aprov-row,.dest-row{display:flex;align-items:center;gap:10px;padding:8px 0;border-bottom:1px solid #f1f5f9;}
    .aprov-row:last-child,.dest-row:last-child{border-bottom:0;}
    .avatar{width:32px;height:32px;border-radius:50%;background:#0a66c2;color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:11px;flex-shrink:0;text-transform:uppercase;}
    .aprov-row .nome,.dest-row .nome{flex:1;font-size:13px;color:#1f2937;}
    .aprov-row .nome small,.dest-row .nome small{display:block;color:#94a3b8;font-size:11px;}
    .aprov-status{padding:3px 10px;border-radius:6px;font-size:10px;font-weight:700;text-transform:uppercase;}
    .aprov-status.s0{background:#fef3c7;color:#92400e;}
    .aprov-status.s1{background:#dcfce7;color:#166534;}
    .aprov-status.s2{background:#fee2e2;color:#991b1b;}

    .obs-item{padding:10px 0;border-bottom:1px solid #f1f5f9;display:flex;gap:10px;}
    .obs-item:last-child{border-bottom:0;}
    .obs-item .body{flex:1;}
    .obs-item .nome{font-size:13px;font-weight:600;color:#1f2937;}
    .obs-item .data{font-size:11px;color:#94a3b8;}
    .obs-item .obs{margin-top:4px;font-size:13px;color:#475569;line-height:1.5;}

    .actions-bar{position:sticky;top:0;background:#fff;padding:12px 18px;border-bottom:1px solid #e5e7eb;z-index:10;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:10px;border-radius:10px 10px 0 0;}
</style>

<div class="content">
    <div class="doc-view-page">
        <ol class="breadcrumb" style="background:#fff;">
            <li><a href="<?php echo base_url('gestao_corporativa/intranet'); ?>"><i class="fa fa-home"></i> Home</a></li>
            <li><a href="<?php echo base_url('gestao_corporativa/Documento'); ?>">Documentos</a></li>
            <li class="active"><?php echo html_escape($doc['codigo']); ?></li>
        </ol>

        <div class="doc-card">
            <div class="actions-bar">
                <div>
                    <span class="doc-status <?php echo $doc['status_slug']; ?>"><?php echo $this->Documentos_model->get_status_label($doc['status_slug']); ?></span>
                    <strong style="margin-left:8px;font-family:monospace;color:#94a3b8;"><?php echo html_escape($doc['codigo']); ?></strong>
                </div>
                <div style="display:flex;gap:6px;">
                    <?php if ($pode_editar): ?>
                        <a href="<?php echo base_url('gestao_corporativa/Documento/edit/' . (int) $doc['id']); ?>" class="btn btn-default btn-sm"><i class="fa fa-pencil"></i> Editar</a>
                        <?php if (empty($doc['publicado'])): ?>
                            <a href="<?php echo base_url('gestao_corporativa/Documento/publicar/' . (int) $doc['id']); ?>" class="btn btn-info btn-sm" onclick="return confirm('Publicar este documento?')"><i class="fa fa-paper-plane"></i> Publicar</a>
                        <?php endif; ?>
                    <?php endif; ?>
                    <?php if (!empty($doc['file'])): ?>
                        <a href="<?php echo base_url('gestao_corporativa/Documento/download/' . (int) $doc['id']); ?>" class="btn btn-success btn-sm"><i class="fa fa-download"></i> Baixar</a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="doc-card-body">
                <h2 style="margin:0 0 8px;font-size:24px;color:#1f2937;"><?php echo html_escape($doc['titulo']); ?></h2>
                <?php if (!empty($doc['descricao'])): ?>
                    <p style="font-size:14px;color:#475569;margin:0 0 12px;"><?php echo html_escape($doc['descricao']); ?></p>
                <?php endif; ?>
                <div class="doc-meta">
                    <?php if (!empty($doc['categoria_nome'])): ?><span><i class="fa fa-tag"></i> <?php echo html_escape($doc['categoria_nome']); ?></span><?php endif; ?>
                    <?php if (!empty($doc['setor_nome'])): ?><span><i class="fa fa-building"></i> <?php echo html_escape($doc['setor_nome']); ?></span><?php endif; ?>
                    <?php if (!empty($doc['responsavel_nome'])): ?><span><i class="fa fa-user-tie"></i> <?php echo html_escape($doc['responsavel_nome']); ?></span><?php endif; ?>
                    <?php if (!empty($doc['project_name'])): ?><span><i class="fa fa-folder-open"></i> <?php echo html_escape($doc['project_name']); ?></span><?php endif; ?>
                    <span><i class="fa fa-code-branch"></i> v<?php echo (int) $doc['numero_versao']; ?></span>
                    <span><i class="fa fa-user"></i> <?php echo html_escape($doc['criador_nome']); ?> · <?php echo date('d/m/Y', strtotime($doc['data_cadastro'])); ?></span>
                    <?php if (!empty($doc['data_publicacao'])): ?>
                        <span style="color:#16a34a;"><i class="fa fa-check-circle"></i> Publicado em <?php echo date('d/m/Y', strtotime($doc['data_publicacao'])); ?></span>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <?php if (!empty($doc['file'])): ?>
            <div class="doc-card">
                <div class="doc-card-header">Arquivo anexo</div>
                <div class="doc-card-body">
                    <div class="file-attached">
                        <i class="fa fa-file-alt"></i>
                        <span class="nome"><?php echo html_escape($doc['file']); ?></span>
                        <a href="<?php echo base_url('gestao_corporativa/Documento/download/' . (int) $doc['id']); ?>" class="btn btn-success btn-sm"><i class="fa fa-download"></i> Baixar</a>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <?php if (!empty($doc['conteudo'])): ?>
            <div class="doc-card">
                <div class="doc-card-header">Conteúdo</div>
                <div class="doc-card-body">
                    <div class="conteudo-doc"><?php echo $doc['conteudo']; ?></div>
                </div>
            </div>
        <?php endif; ?>

        <?php if (!empty($aprovadores)): ?>
            <div class="doc-card">
                <div class="doc-card-header">Fluxo de aprovação</div>
                <div class="doc-card-body">
                    <?php foreach ($aprovadores as $i => $a):
                        $ini = '';
                        foreach (preg_split('/\s+/', $a['staff_nome']) as $p) { if ($p !== '') $ini .= mb_strtoupper(mb_substr($p, 0, 1)); if (mb_strlen($ini) >= 2) break; }
                    ?>
                        <div class="aprov-row">
                            <div class="avatar"><?php echo $ini ?: '?'; ?></div>
                            <div class="nome">
                                <?php echo ($i + 1) . '. ' . html_escape($a['staff_nome']); ?>
                                <?php if (!empty($a['cargo'])): ?><small><?php echo html_escape($a['cargo']); ?></small><?php endif; ?>
                            </div>
                            <?php if ((int) $a['status'] === 1): ?>
                                <span class="aprov-status s1"><i class="fa fa-check"></i> Aprovado</span>
                                <?php if (!empty($a['dt_aprovacao'])): ?><small style="color:#94a3b8;font-size:11px;"><?php echo date('d/m/Y H:i', strtotime($a['dt_aprovacao'])); ?></small><?php endif; ?>
                            <?php elseif ((int) $a['status'] === 2): ?>
                                <span class="aprov-status s2"><i class="fa fa-times"></i> Reprovado</span>
                            <?php else: ?>
                                <span class="aprov-status s0">Aguardando</span>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>

                    <?php if ($aprovador_atual && (int) $aprovador_atual['staff_id'] === (int) get_staff_user_id()): ?>
                        <div style="margin-top:16px;padding:14px;background:#fffbeb;border:1px solid #fde68a;border-radius:8px;">
                            <strong style="color:#92400e;">É a sua vez de aprovar este documento.</strong>
                            <form method="post" action="<?php echo base_url('gestao_corporativa/Documento/aprovar/' . (int) $doc['id']); ?>" style="margin-top:10px;">
                                <textarea name="observacao" class="form-control" rows="2" placeholder="Observação (opcional, máx. 45 chars)" maxlength="45"></textarea>
                                <div style="margin-top:8px;display:flex;gap:6px;">
                                    <button type="submit" class="btn btn-success btn-sm"><i class="fa fa-check"></i> Aprovar</button>
                                    <button type="submit" formaction="<?php echo base_url('gestao_corporativa/Documento/reprovar/' . (int) $doc['id']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Reprovar?')"><i class="fa fa-times"></i> Reprovar</button>
                                </div>
                            </form>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>

        <?php if (!empty($destinatarios)):
            $me = (int) get_staff_user_id();
            $eu_dest = null;
            foreach ($destinatarios as $d) { if ((int) $d['staff_id'] === $me) { $eu_dest = $d; break; } }
            $total_lidos = 0; foreach ($destinatarios as $d) if ((int) $d['lido'] === 1) $total_lidos++;
            $pct = round($total_lidos * 100 / count($destinatarios));
        ?>
            <div class="doc-card">
                <div class="doc-card-header">
                    <span>Destinatários (<?php echo $total_lidos; ?> de <?php echo count($destinatarios); ?> leram — <?php echo $pct; ?>%)</span>
                    <?php if ($eu_dest && (int) $eu_dest['lido'] === 0 && !empty($doc['publicado'])): ?>
                        <a href="<?php echo base_url('gestao_corporativa/Documento/ciente/' . (int) $doc['id']); ?>" class="btn btn-success btn-xs"><i class="fa fa-check"></i> Marcar como lido</a>
                    <?php endif; ?>
                </div>
                <div class="doc-card-body">
                    <?php foreach ($destinatarios as $d):
                        $ini = ''; foreach (preg_split('/\s+/', $d['staff_nome'] ?? '') as $p) { if ($p !== '') $ini .= mb_strtoupper(mb_substr($p, 0, 1)); if (mb_strlen($ini) >= 2) break; }
                    ?>
                        <div class="dest-row">
                            <div class="avatar" style="background:<?php echo (int) $d['lido'] === 1 ? '#16a34a' : '#94a3b8'; ?>;"><?php echo $ini ?: '?'; ?></div>
                            <div class="nome"><?php echo html_escape($d['staff_nome']); ?>
                                <?php if (!empty($d['cargo'])): ?><small><?php echo html_escape($d['cargo']); ?></small><?php endif; ?>
                            </div>
                            <?php if ((int) $d['lido'] === 1): ?>
                                <span class="aprov-status s1"><i class="fa fa-check"></i> Lido <?php echo !empty($d['dt_lido']) ? date('d/m H:i', strtotime($d['dt_lido'])) : ''; ?></span>
                            <?php else: ?>
                                <span class="aprov-status s0">Pendente</span>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <div class="doc-card">
            <div class="doc-card-header">Comentários (<?php echo count($observacoes); ?>)</div>
            <div class="doc-card-body">
                <form method="post" action="<?php echo base_url('gestao_corporativa/Documento/add_observacao/' . (int) $doc['id']); ?>" style="margin-bottom:12px;">
                    <textarea name="observacao" class="form-control" rows="2" placeholder="Comentário…" required></textarea>
                    <div style="text-align:right;margin-top:6px;">
                        <button type="submit" class="btn btn-default btn-sm"><i class="fa fa-comment"></i> Comentar</button>
                    </div>
                </form>
                <?php if (empty($observacoes)): ?>
                    <p style="text-align:center;color:#94a3b8;font-size:13px;">Sem comentários ainda.</p>
                <?php else: ?>
                    <?php foreach ($observacoes as $o):
                        $ini = ''; foreach (preg_split('/\s+/', $o['staff_nome'] ?? '') as $p) { if ($p !== '') $ini .= mb_strtoupper(mb_substr($p, 0, 1)); if (mb_strlen($ini) >= 2) break; }
                    ?>
                        <div class="obs-item">
                            <div class="avatar"><?php echo $ini ?: '?'; ?></div>
                            <div class="body">
                                <div class="nome"><?php echo html_escape($o['staff_nome'] ?? '—'); ?> <span class="data">· <?php echo date('d/m/Y H:i', strtotime($o['data_created'])); ?></span></div>
                                <div class="obs"><?php echo nl2br(html_escape($o['obs'])); ?></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <?php $this->load->view('gestao_corporativa/treinamentos/_lista_vinculada', [
            'vinculo_tipo' => 'documento',
            'vinculo_id'   => (int) $doc['id'],
            'card_title'   => 'Treinamentos sobre este documento',
        ]); ?>

        <?php $this->load->view('gestao_corporativa/auditorias/_lista_vinculada', [
            'vinculo_tipo' => 'documento',
            'vinculo_id'   => (int) $doc['id'],
            'card_title'   => 'Auditorias sobre este documento',
        ]); ?>
    </div>
</div>

<?php init_tail(); ?>
</body>
</html>
