<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head_intranet_novo(false); ?>
<?php $this->load->view('gestao_corporativa/css_background'); ?>

<style>
    .tr-view-page{max-width:1100px;margin:24px auto;padding:0 18px;}
    .tr-card{background:#fff;border:1px solid #e5e7eb;border-radius:10px;margin-bottom:14px;}
    .tr-card-header{padding:14px 18px;border-bottom:1px solid #eef1f4;font-weight:600;color:#1f2937;font-size:14px;display:flex;justify-content:space-between;align-items:center;}
    .tr-card-body{padding:18px;}
    .tr-meta{display:flex;flex-wrap:wrap;gap:14px;font-size:13px;color:#475569;margin-top:8px;}
    .tr-meta span{display:flex;align-items:center;gap:6px;}
    .tr-status{display:inline-block;padding:4px 12px;border-radius:6px;font-size:11px;font-weight:700;letter-spacing:.04em;text-transform:uppercase;}

    .part-table{width:100%;border-collapse:collapse;font-size:13px;}
    .part-table th,.part-table td{padding:10px 12px;text-align:left;border-bottom:1px solid #eef1f4;}
    .part-table th{background:#f8fafc;font-size:11px;text-transform:uppercase;color:#475569;font-weight:700;}
    .part-table tr:hover{background:#f8fafc;}
    .avatar{width:28px;height:28px;border-radius:50%;background:#0a66c2;color:#fff;display:inline-flex;align-items:center;justify-content:center;font-weight:700;font-size:10px;}
    .pres-pill{display:inline-block;padding:2px 8px;border-radius:4px;font-size:10px;font-weight:700;text-transform:uppercase;}
    .pres-pill.presente{background:#dcfce7;color:#166534;}
    .pres-pill.ausente{background:#fee2e2;color:#991b1b;}
    .pres-pill.inscrito{background:#fef3c7;color:#92400e;}
</style>

<div class="content">
    <div class="tr-view-page">
        <ol class="breadcrumb" style="background:#fff;">
            <li><a href="<?php echo base_url('gestao_corporativa/intranet'); ?>"><i class="fa fa-home"></i> Home</a></li>
            <li><a href="<?php echo base_url('gestao_corporativa/Treinamento'); ?>">Treinamentos</a></li>
            <li class="active"><?php echo html_escape($treinamento['codigo']); ?></li>
        </ol>

        <div class="tr-card">
            <div class="tr-card-header">
                <div>
                    <strong style="font-family:monospace;color:#94a3b8;"><?php echo html_escape($treinamento['codigo']); ?></strong>
                    <span class="tr-status" style="background:<?php echo $this->Treinamento_model->get_status_color($treinamento['status']); ?>20;color:<?php echo $this->Treinamento_model->get_status_color($treinamento['status']); ?>;margin-left:8px;"><?php echo $this->Treinamento_model->get_status_label($treinamento['status']); ?></span>
                </div>
                <?php if ($pode_editar): ?>
                    <a href="<?php echo base_url('gestao_corporativa/Treinamento/edit/' . (int) $treinamento['id']); ?>" class="btn btn-default btn-sm"><i class="fa fa-pencil"></i> Editar</a>
                <?php endif; ?>
            </div>
            <div class="tr-card-body">
                <h2 style="margin:0 0 6px;font-size:22px;color:#1f2937;"><?php echo html_escape($treinamento['titulo']); ?></h2>
                <?php if (!empty($treinamento['descricao'])): ?>
                    <p style="font-size:14px;color:#475569;margin:0 0 8px;"><?php echo html_escape($treinamento['descricao']); ?></p>
                <?php endif; ?>
                <div class="tr-meta">
                    <span><i class="fa fa-laptop"></i> <?php echo $this->Treinamento_model->get_modalidade_label($treinamento['modalidade']); ?></span>
                    <?php if ($treinamento['carga_horaria']): ?><span><i class="fa fa-clock"></i> <?php echo (float) $treinamento['carga_horaria']; ?>h</span><?php endif; ?>
                    <?php if ($treinamento['dt_inicio']): ?><span><i class="fa fa-calendar"></i> <?php echo date('d/m/Y H:i', strtotime($treinamento['dt_inicio'])); ?><?php echo $treinamento['dt_fim'] ? ' → ' . date('H:i', strtotime($treinamento['dt_fim'])) : ''; ?></span><?php endif; ?>
                    <?php if ($treinamento['local']): ?><span><i class="fa fa-map-marker-alt"></i> <?php echo html_escape($treinamento['local']); ?></span><?php endif; ?>
                    <?php if ($treinamento['instrutor_nome']): ?><span><i class="fa fa-chalkboard-teacher"></i> <?php echo html_escape($treinamento['instrutor_nome']); ?></span>
                    <?php elseif ($treinamento['instrutor_externo']): ?><span><i class="fa fa-chalkboard-teacher"></i> <?php echo html_escape($treinamento['instrutor_externo']); ?> (externo)</span><?php endif; ?>
                </div>

                <?php if ($treinamento['documento_titulo'] || $treinamento['plano_titulo'] || $treinamento['grupo_titulo'] || $treinamento['project_name']): ?>
                    <div class="tr-meta" style="margin-top:8px;padding-top:8px;border-top:1px solid #f1f5f9;">
                        <?php if ($treinamento['documento_titulo']): ?>
                            <span><i class="far fa-folder-open"></i> POP: <a href="<?php echo base_url('gestao_corporativa/Documento/view/' . (int) $treinamento['documento_id']); ?>"><?php echo html_escape($treinamento['documento_codigo'] . ' — ' . $treinamento['documento_titulo']); ?></a></span>
                        <?php endif; ?>
                        <?php if ($treinamento['plano_titulo']): ?>
                            <span><i class="fas fa-clipboard-list"></i> <a href="<?php echo base_url('gestao_corporativa/Plano_acao/view/' . (int) $treinamento['plano_id']); ?>"><?php echo html_escape($treinamento['plano_titulo']); ?></a></span>
                        <?php endif; ?>
                        <?php if ($treinamento['grupo_titulo']): ?>
                            <span><i class="fas fa-users-cog"></i> <a href="<?php echo base_url('gestao_corporativa/Workgroup/view/' . (int) $treinamento['grupo_id']); ?>"><?php echo html_escape($treinamento['grupo_titulo']); ?></a></span>
                        <?php endif; ?>
                        <?php if ($treinamento['project_name']): ?>
                            <span><i class="fa fa-folder"></i> <?php echo html_escape($treinamento['project_name']); ?></span>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <?php if (!empty($treinamento['conteudo_programatico'])): ?>
            <div class="tr-card">
                <div class="tr-card-header">Conteúdo programático</div>
                <div class="tr-card-body"><?php echo $treinamento['conteudo_programatico']; ?></div>
            </div>
        <?php endif; ?>

        <div class="tr-card">
            <div class="tr-card-header">
                Participantes (<?php echo count($participantes); ?>)
                <?php $presentes = 0; foreach ($participantes as $p) if ($p['status_inscricao'] === 'presente') $presentes++; ?>
                <small style="color:#94a3b8;font-weight:400;"><?php echo $presentes; ?> presente(s)</small>
            </div>
            <?php if (empty($participantes)): ?>
                <div style="text-align:center;padding:30px;color:#94a3b8;">Nenhum participante inscrito.</div>
            <?php else: ?>
                <table class="part-table">
                    <thead>
                        <tr>
                            <th>Pessoa</th>
                            <th>Status</th>
                            <th>Presença</th>
                            <?php if ($treinamento['nota_minima'] !== null): ?><th>Nota</th><th>Aprovação</th><?php endif; ?>
                            <?php if ($pode_editar): ?><th></th><?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($participantes as $p):
                            $ini = ''; foreach (preg_split('/\s+/', $p['staff_nome']) as $part) { if ($part !== '') $ini .= mb_strtoupper(mb_substr($part, 0, 1)); if (mb_strlen($ini) >= 2) break; }
                        ?>
                            <tr>
                                <td><div class="avatar"><?php echo $ini ?: '?'; ?></div> <?php echo html_escape($p['staff_nome']); ?>
                                    <?php if ($p['cargo']): ?><br><small style="color:#94a3b8;"><?php echo html_escape($p['cargo']); ?></small><?php endif; ?>
                                </td>
                                <td><span class="pres-pill <?php echo $p['status_inscricao']; ?>"><?php echo $p['status_inscricao']; ?></span></td>
                                <td>
                                    <?php if ($p['dt_presenca']): ?><?php echo date('d/m H:i', strtotime($p['dt_presenca'])); ?><?php endif; ?>
                                </td>
                                <?php if ($treinamento['nota_minima'] !== null): ?>
                                    <td>
                                        <?php if ($pode_editar): ?>
                                            <form method="post" action="<?php echo base_url('gestao_corporativa/Treinamento/nota/' . (int) $p['id']); ?>" style="display:flex;gap:4px;">
                                                <input type="text" name="nota" value="<?php echo $p['nota'] !== null ? number_format((float) $p['nota'], 2, ',', '') : ''; ?>" style="width:60px;border:1px solid #d0d5dd;border-radius:4px;padding:3px 6px;font-size:12px;">
                                                <button type="submit" class="btn btn-default btn-xs"><i class="fa fa-save"></i></button>
                                            </form>
                                        <?php else: ?>
                                            <?php echo $p['nota'] !== null ? number_format((float) $p['nota'], 2, ',', '') : '—'; ?>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($p['aprovado'] === '1' || $p['aprovado'] === 1): ?>
                                            <span class="pres-pill presente">Aprovado</span>
                                        <?php elseif ($p['aprovado'] === '0' || $p['aprovado'] === 0): ?>
                                            <span class="pres-pill ausente">Reprovado</span>
                                        <?php else: ?>
                                            <span style="color:#94a3b8;">—</span>
                                        <?php endif; ?>
                                    </td>
                                <?php endif; ?>
                                <?php if ($pode_editar): ?>
                                    <td style="text-align:right;">
                                        <a href="<?php echo base_url('gestao_corporativa/Treinamento/presenca/' . (int) $p['id'] . '?presente=' . ($p['status_inscricao'] === 'presente' ? '0' : '1')); ?>" class="btn btn-default btn-xs">
                                            <?php echo $p['status_inscricao'] === 'presente' ? 'Marcar ausente' : 'Marcar presente'; ?>
                                        </a>
                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php init_tail(); ?>
</body>
</html>
