<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php
$pid = (int) $project->id;
$this->load->model('Formulario_model');
$formularios = $this->Formulario_model->listar(['project_id' => $pid]);
?>

<style>
    .ptab-fm-row{background:#fff;border:1px solid #e5e7eb;border-radius:10px;padding:14px 18px;margin-bottom:8px;display:grid;grid-template-columns:1fr auto;gap:14px;align-items:center;}
    .ptab-fm-row .titulo a{color:#1f2937;font-weight:600;font-size:14px;text-decoration:none;}
    .ptab-fm-row .titulo a:hover{color:#0a66c2;}
    .ptab-fm-row .meta{font-size:12px;color:#64748b;margin-top:4px;display:flex;gap:14px;flex-wrap:wrap;}
    .ptab-fm-row .actions{display:flex;gap:6px;}
    .fm-status{display:inline-block;padding:2px 8px;border-radius:6px;font-size:10px;font-weight:700;letter-spacing:.04em;text-transform:uppercase;}
    .fm-status.rascunho{background:#fef3c7;color:#92400e;}
    .fm-status.publicado{background:#dcfce7;color:#166534;}
    .fm-status.encerrado{background:#e0e7ff;color:#3730a3;}
    .fm-status.arquivado{background:#f1f5f9;color:#475569;}
    .ptab-empty{text-align:center;padding:40px;color:#94a3b8;}
    .ptab-toolbar{display:flex;justify-content:space-between;align-items:center;margin:14px 0;}
</style>

<div class="row" style="padding-top:10px;">
    <div class="col-md-12">
        <div class="ptab-toolbar">
            <h4 style="margin:0;">Formulários (<?php echo count($formularios); ?>)</h4>
            <a href="<?php echo base_url('gestao_corporativa/Formulario/add?project_id=' . $pid); ?>" class="btn btn-info btn-sm"><i class="fa fa-plus"></i> Novo formulário</a>
        </div>

        <?php if (empty($formularios)): ?>
            <div class="ptab-empty"><i class="fa fa-list-alt fa-2x" style="color:#cbd5e1;display:block;margin-bottom:6px;"></i> Nenhum formulário vinculado.</div>
        <?php else: ?>
            <?php foreach ($formularios as $f): ?>
                <div class="ptab-fm-row">
                    <div>
                        <div class="titulo">
                            <span class="fm-status <?php echo $f['status']; ?>"><?php echo $this->Formulario_model->get_status_label($f['status']); ?></span>
                            <a href="<?php echo base_url('gestao_corporativa/Formulario/edit/' . (int) $f['id']); ?>"><?php echo html_escape($f['titulo']); ?></a>
                        </div>
                        <div class="meta">
                            <span><i class="fa fa-question-circle"></i> <?php echo (int) $f['total_perguntas']; ?> perguntas</span>
                            <span><i class="fa fa-comments"></i> <?php echo (int) $f['total_respostas']; ?> respostas</span>
                            <span><i class="fa fa-user"></i> <?php echo html_escape($f['criador_nome']); ?></span>
                            <span><i class="fa fa-calendar"></i> <?php echo date('d/m/Y', strtotime($f['data_created'])); ?></span>
                        </div>
                    </div>
                    <div class="actions">
                        <?php if ($f['status'] === 'publicado'): ?>
                            <a href="<?php echo base_url('formularios/web/' . $f['form_key']); ?>" target="_blank" class="btn btn-success btn-xs" title="Link público"><i class="fa fa-external-link-alt"></i></a>
                        <?php endif; ?>
                        <a href="<?php echo base_url('gestao_corporativa/Formulario/respostas/' . (int) $f['id']); ?>" class="btn btn-default btn-xs"><i class="fa fa-table"></i> Respostas</a>
                        <a href="<?php echo base_url('gestao_corporativa/Formulario/edit/' . (int) $f['id']); ?>" class="btn btn-default btn-xs"><i class="fa fa-pencil"></i> Editar</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
