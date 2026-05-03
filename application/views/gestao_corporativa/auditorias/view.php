<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head_intranet_novo(false); ?>
<?php $this->load->view('gestao_corporativa/css_background'); ?>

<style>
    .au-view-page{max-width:1100px;margin:24px auto;padding:0 18px;}
    .au-card{background:#fff;border:1px solid #e5e7eb;border-radius:10px;margin-bottom:14px;}
    .au-card-header{padding:14px 18px;border-bottom:1px solid #eef1f4;font-weight:600;color:#1f2937;font-size:14px;display:flex;justify-content:space-between;align-items:center;}
    .au-card-body{padding:18px;}

    .au-meta{display:flex;flex-wrap:wrap;gap:14px;font-size:13px;color:#475569;margin-top:8px;}
    .au-meta span{display:flex;align-items:center;gap:6px;}
    .au-status{padding:3px 10px;border-radius:6px;font-size:11px;font-weight:700;text-transform:uppercase;}

    .ach-table{width:100%;border-collapse:collapse;font-size:13px;}
    .ach-table th,.ach-table td{padding:10px 12px;text-align:left;border-bottom:1px solid #eef1f4;vertical-align:top;}
    .ach-table th{background:#f8fafc;font-size:11px;text-transform:uppercase;color:#475569;font-weight:700;}
    .ach-pill{display:inline-block;padding:2px 8px;border-radius:6px;font-size:10px;font-weight:700;text-transform:uppercase;color:#fff;}

    .form-novo-achado{background:#f8fafc;border:1px dashed #cbd5e1;border-radius:8px;padding:14px;}
    .form-novo-achado .grid{display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:10px;}
    .form-novo-achado label{font-size:11px;font-weight:600;color:#475569;text-transform:uppercase;display:block;margin-bottom:4px;}
    .form-novo-achado input,.form-novo-achado select,.form-novo-achado textarea{width:100%;border:1px solid #d0d5dd;border-radius:6px;padding:7px 10px;font-size:13px;}
</style>

<?php $model = $this->Auditoria_model; ?>

<div class="content">
    <div class="au-view-page">
        <ol class="breadcrumb" style="background:#fff;">
            <li><a href="<?php echo base_url('gestao_corporativa/intranet'); ?>"><i class="fa fa-home"></i> Home</a></li>
            <li><a href="<?php echo base_url('gestao_corporativa/Auditoria'); ?>">Auditorias</a></li>
            <li class="active"><?php echo html_escape($auditoria['codigo']); ?></li>
        </ol>

        <div class="au-card">
            <div class="au-card-header" style="border-left:4px solid <?php echo $model->get_resultado_color($auditoria['resultado']); ?>;border-top-left-radius:10px;">
                <div>
                    <strong style="font-family:monospace;color:#94a3b8;"><?php echo html_escape($auditoria['codigo']); ?></strong>
                    <span class="au-status" style="background:<?php echo $model->get_status_color($auditoria['status']); ?>20;color:<?php echo $model->get_status_color($auditoria['status']); ?>;margin-left:8px;"><?php echo $model->get_status_label($auditoria['status']); ?></span>
                    <span class="au-status" style="background:<?php echo $model->get_resultado_color($auditoria['resultado']); ?>20;color:<?php echo $model->get_resultado_color($auditoria['resultado']); ?>;"><?php echo $model->get_resultado_label($auditoria['resultado']); ?></span>
                </div>
                <?php if ($pode_editar): ?>
                    <a href="<?php echo base_url('gestao_corporativa/Auditoria/edit/' . (int) $auditoria['id']); ?>" class="btn btn-default btn-sm"><i class="fa fa-pencil"></i> Editar</a>
                <?php endif; ?>
            </div>
            <div class="au-card-body">
                <h2 style="margin:0 0 6px;font-size:22px;color:#1f2937;"><?php echo html_escape($auditoria['titulo']); ?></h2>
                <?php if ($auditoria['descricao']): ?>
                    <p style="font-size:14px;color:#475569;margin:0 0 10px;"><?php echo html_escape($auditoria['descricao']); ?></p>
                <?php endif; ?>
                <div class="au-meta">
                    <span><i class="fa fa-tag"></i> <?php echo $model->get_tipo_label($auditoria['tipo']); ?></span>
                    <?php if ($auditoria['norma_referencia']): ?><span><i class="fa fa-book"></i> <?php echo html_escape($auditoria['norma_referencia']); ?></span><?php endif; ?>
                    <?php if ($auditoria['dt_planejada']): ?><span><i class="fa fa-calendar"></i> <?php echo date('d/m/Y', strtotime($auditoria['dt_planejada'])); ?></span><?php endif; ?>
                    <?php if ($auditoria['dt_realizada']): ?><span style="color:#16a34a;"><i class="fa fa-check"></i> realizada <?php echo date('d/m/Y', strtotime($auditoria['dt_realizada'])); ?></span><?php endif; ?>
                    <?php if ($auditoria['duracao_horas']): ?><span><i class="fa fa-clock"></i> <?php echo (float) $auditoria['duracao_horas']; ?>h</span><?php endif; ?>
                    <?php if ($auditoria['auditor_nome']): ?><span><i class="fa fa-user-tie"></i> <?php echo html_escape($auditoria['auditor_nome']); ?></span><?php endif; ?>
                    <?php if ($auditoria['setor_nome']): ?><span><i class="fa fa-building"></i> <?php echo html_escape($auditoria['setor_nome']); ?></span><?php endif; ?>
                </div>

                <?php if ($auditoria['escopo']): ?>
                    <div style="background:#f8fafc;border-radius:8px;padding:12px;margin-top:12px;">
                        <strong style="font-size:11px;color:#64748b;text-transform:uppercase;">Escopo</strong>
                        <div style="font-size:13px;color:#1f2937;margin-top:4px;line-height:1.5;"><?php echo nl2br(html_escape($auditoria['escopo'])); ?></div>
                    </div>
                <?php endif; ?>

                <?php if ($auditoria['doc_titulo'] || $auditoria['form_titulo'] || $auditoria['project_name']): ?>
                    <div class="au-meta" style="margin-top:12px;padding-top:12px;border-top:1px solid #f1f5f9;">
                        <?php if ($auditoria['doc_titulo']): ?>
                            <span><i class="far fa-folder-open"></i> POP: <a href="<?php echo base_url('gestao_corporativa/Documento/view/' . (int) $auditoria['documento_referencia_id']); ?>"><?php echo html_escape($auditoria['doc_codigo'] . ' — ' . $auditoria['doc_titulo']); ?></a></span>
                        <?php endif; ?>
                        <?php if ($auditoria['form_titulo']): ?>
                            <span><i class="fa fa-list-alt"></i> Checklist: <a href="<?php echo base_url('gestao_corporativa/Formulario/edit/' . (int) $auditoria['formulario_id']); ?>"><?php echo html_escape($auditoria['form_titulo']); ?></a> — <a href="<?php echo base_url('formularios/web/' . app_generate_hash()); ?>" target="_blank" style="color:#0a66c2;">[link de resposta]</a></span>
                        <?php endif; ?>
                        <?php if ($auditoria['project_name']): ?><span><i class="fa fa-folder"></i> <?php echo html_escape($auditoria['project_name']); ?></span><?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <?php if (!empty($auditados)): ?>
            <div class="au-card">
                <div class="au-card-header">Auditados (<?php echo count($auditados); ?>)</div>
                <div class="au-card-body">
                    <?php foreach ($auditados as $au): ?>
                        <span style="display:inline-block;padding:4px 10px;background:#f1f5f9;border-radius:6px;margin:2px;font-size:12px;">
                            <?php echo html_escape($au['staff_nome']); ?>
                            <?php if ($au['cargo']): ?> <small style="color:#94a3b8;"><?php echo html_escape($au['cargo']); ?></small><?php endif; ?>
                        </span>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <div class="au-card">
            <div class="au-card-header">
                Achados (<?php echo count($achados); ?>)
                <?php
                $ncs = 0; foreach ($achados as $a) if (in_array($a['tipo'], ['nc_maior','nc_menor'])) $ncs++;
                ?>
                <small style="color:#94a3b8;font-weight:400;"><?php echo $ncs; ?> não-conformidade(s)</small>
            </div>

            <?php if (!empty($achados)): ?>
                <table class="ach-table">
                    <thead>
                        <tr>
                            <th>Tipo</th>
                            <th>Descrição</th>
                            <th>Requisito</th>
                            <th>Responsável</th>
                            <th>Prazo</th>
                            <th>Status</th>
                            <th>Tratamento</th>
                            <?php if ($pode_editar): ?><th></th><?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($achados as $ac):
                            $cor = $model->get_tipo_achado_color($ac['tipo']);
                            $atras = !empty($ac['prazo_tratamento']) && $ac['status'] !== 'fechado' && $ac['prazo_tratamento'] < date('Y-m-d');
                        ?>
                            <tr>
                                <td><span class="ach-pill" style="background:<?php echo $cor; ?>;"><?php echo $model->get_tipo_achado_label($ac['tipo']); ?></span></td>
                                <td>
                                    <strong style="color:#1f2937;"><?php echo nl2br(html_escape(mb_strimwidth($ac['descricao'], 0, 200, '…'))); ?></strong>
                                    <?php if ($ac['evidencia']): ?>
                                        <br><small style="color:#64748b;"><i class="fa fa-paperclip"></i> <?php echo html_escape(mb_strimwidth($ac['evidencia'], 0, 100, '…')); ?></small>
                                    <?php endif; ?>
                                </td>
                                <td><small style="font-family:monospace;color:#475569;"><?php echo html_escape($ac['requisito'] ?? '—'); ?></small></td>
                                <td><small><?php echo html_escape($ac['responsavel_nome'] ?? '—'); ?></small></td>
                                <td>
                                    <?php if ($ac['prazo_tratamento']): ?>
                                        <small style="color:<?php echo $atras ? '#dc2626' : '#475569'; ?>;font-weight:<?php echo $atras ? '700' : '400'; ?>;"><?php echo date('d/m/Y', strtotime($ac['prazo_tratamento'])); ?></small>
                                    <?php else: ?>
                                        —
                                    <?php endif; ?>
                                </td>
                                <td><span style="color:<?php echo $ac['status'] === 'fechado' ? '#16a34a' : ($ac['status'] === 'em_tratamento' ? '#f59e0b' : '#dc2626'); ?>;font-weight:600;font-size:12px;"><?php echo $model->get_status_achado_label($ac['status']); ?></span></td>
                                <td>
                                    <?php if ($ac['plano_titulo']): ?>
                                        <a href="<?php echo base_url('gestao_corporativa/Plano_acao/view/' . (int) $ac['plano_id']); ?>" style="font-size:11px;"><i class="fas fa-clipboard-list"></i> Plano</a>
                                    <?php endif; ?>
                                    <?php if ($ac['ocorrencia_id']): ?>
                                        <a href="<?php echo base_url('gestao_corporativa/Registro_ocorrencia/view/' . (int) $ac['ocorrencia_id']); ?>" style="font-size:11px;"><i class="far fa-flag"></i> R.O #<?php echo (int) $ac['ocorrencia_id']; ?></a>
                                    <?php endif; ?>
                                </td>
                                <?php if ($pode_editar): ?>
                                    <td style="text-align:right;white-space:nowrap;">
                                        <?php if (!$ac['ocorrencia_id'] && in_array($ac['tipo'], ['nc_maior','nc_menor'])): ?>
                                            <a href="<?php echo base_url('gestao_corporativa/Auditoria/achado_gerar_ocorrencia/' . (int) $ac['id']); ?>" class="btn btn-warning btn-xs" onclick="return confirm('Gerar uma R.O para esta não-conformidade?')"><i class="far fa-flag"></i> Gerar R.O</a>
                                        <?php endif; ?>
                                        <a href="<?php echo base_url('gestao_corporativa/Auditoria/achado_delete/' . (int) $ac['id']); ?>" class="btn btn-default btn-xs" onclick="return confirm('Excluir achado?')"><i class="fa fa-trash text-danger"></i></a>
                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>

            <?php if ($pode_editar): ?>
                <div style="padding:18px;">
                    <form method="post" action="<?php echo base_url('gestao_corporativa/Auditoria/achado_save'); ?>" class="form-novo-achado">
                        <input type="hidden" name="auditoria_id" value="<?php echo (int) $auditoria['id']; ?>">
                        <strong style="font-size:13px;color:#475569;">Adicionar achado</strong>
                        <div class="grid" style="margin-top:8px;">
                            <div>
                                <label>Tipo</label>
                                <select name="tipo">
                                    <?php foreach ($model->get_tipos_achado() as $t): ?>
                                        <option value="<?php echo $t; ?>" <?php echo $t === 'observacao' ? 'selected' : ''; ?>><?php echo $model->get_tipo_achado_label($t); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div>
                                <label>Requisito (norma/POP)</label>
                                <input type="text" name="requisito" placeholder="ex: ONA 3.1.2 / POP-005">
                            </div>
                        </div>
                        <div>
                            <label>Descrição do achado <span style="color:#dc2626;">*</span></label>
                            <textarea name="descricao" rows="2" required></textarea>
                        </div>
                        <div>
                            <label>Evidência observada</label>
                            <textarea name="evidencia" rows="2"></textarea>
                        </div>
                        <div class="grid">
                            <div>
                                <label>Responsável pelo tratamento</label>
                                <select name="responsavel_id">
                                    <option value="">—</option>
                                    <?php foreach ($staffs as $s): ?>
                                        <option value="<?php echo (int) $s['staffid']; ?>"><?php echo html_escape($s['firstname'] . ' ' . $s['lastname']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div>
                                <label>Prazo de tratamento</label>
                                <input type="date" name="prazo_tratamento">
                            </div>
                        </div>
                        <div class="grid">
                            <div>
                                <label>Plano de Ação vinculado (opcional)</label>
                                <select name="plano_id">
                                    <option value="">—</option>
                                    <?php foreach ($planos as $pl): ?>
                                        <option value="<?php echo (int) $pl['id']; ?>"><?php echo html_escape($pl['titulo']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div>
                                <label>Status</label>
                                <select name="status">
                                    <option value="aberto">Aberto</option>
                                    <option value="em_tratamento">Em tratamento</option>
                                    <option value="fechado">Fechado</option>
                                </select>
                            </div>
                        </div>
                        <div style="text-align:right;margin-top:8px;">
                            <button type="submit" class="btn btn-info btn-sm"><i class="fa fa-plus"></i> Adicionar achado</button>
                        </div>
                    </form>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php init_tail(); ?>
</body>
</html>
