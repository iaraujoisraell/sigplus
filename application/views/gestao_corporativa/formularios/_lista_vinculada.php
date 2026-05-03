<?php
/**
 * Partial reutilizável: lista de formulários vinculados a ata/plano/grupo/projeto.
 * Espera as variáveis:
 *  - $vinculo_tipo  string: 'ata' | 'plano' | 'grupo' | 'project'
 *  - $vinculo_id    int
 *  - $card_title    string (opcional)
 */
defined('BASEPATH') or exit('No direct script access allowed');

$this->load->model('Formulario_model');
$filtros = [];
$qs_param = '';
switch ($vinculo_tipo ?? '') {
    case 'ata':     $filtros['ata_id']     = (int) $vinculo_id; $qs_param = 'ata_id';     break;
    case 'plano':   $filtros['plano_id']   = (int) $vinculo_id; $qs_param = 'plano_id';   break;
    case 'grupo':   $filtros['grupo_id']   = (int) $vinculo_id; $qs_param = 'grupo_id';   break;
    case 'project': $filtros['project_id'] = (int) $vinculo_id; $qs_param = 'project_id'; break;
    default: return;
}
$formularios = $this->Formulario_model->listar($filtros);
$titulo = $card_title ?? 'Formulários';
?>

<style>
    .fmv-row{display:grid;grid-template-columns:1fr auto;gap:12px;align-items:center;padding:10px 0;border-bottom:1px solid #f1f5f9;}
    .fmv-row:last-child{border-bottom:0;}
    .fmv-row .titulo{font-weight:600;color:#1f2937;font-size:14px;text-decoration:none;display:flex;align-items:center;gap:8px;}
    .fmv-row .titulo:hover{color:#0a66c2;}
    .fmv-row .meta{font-size:12px;color:#64748b;margin-top:3px;display:flex;gap:12px;flex-wrap:wrap;}
    .fmv-row .actions{display:flex;gap:6px;}
    .fmv-status{display:inline-block;padding:2px 8px;border-radius:6px;font-size:10px;font-weight:700;letter-spacing:.04em;text-transform:uppercase;}
    .fmv-status.rascunho{background:#fef3c7;color:#92400e;}
    .fmv-status.publicado{background:#dcfce7;color:#166534;}
    .fmv-status.encerrado{background:#e0e7ff;color:#3730a3;}
    .fmv-status.arquivado{background:#f1f5f9;color:#475569;}
    .fmv-empty{text-align:center;padding:24px 12px;color:#94a3b8;font-size:13px;}
</style>

<div class="ata-card form-card">
    <div class="ata-card-header form-card-header" style="display:flex;justify-content:space-between;align-items:center;">
        <span><i class="fa fa-list-alt"></i> <?php echo html_escape($titulo); ?> (<?php echo count($formularios); ?>)</span>
        <a href="<?php echo base_url('gestao_corporativa/Formulario/add?' . $qs_param . '=' . (int) $vinculo_id); ?>" class="btn btn-info btn-xs"><i class="fa fa-plus"></i> Novo</a>
    </div>
    <div class="ata-card-body form-card-body">
        <?php if (empty($formularios)): ?>
            <div class="fmv-empty"><i class="fa fa-list-alt fa-2x" style="color:#cbd5e1;display:block;margin-bottom:6px;"></i> Nenhum formulário vinculado.</div>
        <?php else: ?>
            <?php foreach ($formularios as $f): ?>
                <div class="fmv-row">
                    <div>
                        <a class="titulo" href="<?php echo base_url('gestao_corporativa/Formulario/edit/' . (int) $f['id']); ?>">
                            <span class="fmv-status <?php echo $f['status']; ?>"><?php echo $this->Formulario_model->get_status_label($f['status']); ?></span>
                            <?php echo html_escape($f['titulo']); ?>
                        </a>
                        <div class="meta">
                            <span><i class="fa fa-question-circle"></i> <?php echo (int) $f['total_perguntas']; ?> perguntas</span>
                            <span><i class="fa fa-comments"></i> <?php echo (int) $f['total_respostas']; ?> respostas</span>
                            <span><i class="fa fa-user"></i> <?php echo html_escape($f['criador_nome']); ?></span>
                        </div>
                    </div>
                    <div class="actions">
                        <?php if ($f['status'] === 'publicado'): ?>
                            <a href="<?php echo base_url('formularios/web/' . $f['form_key']); ?>" target="_blank" class="btn btn-success btn-xs" title="Link público"><i class="fa fa-external-link-alt"></i></a>
                        <?php endif; ?>
                        <a href="<?php echo base_url('gestao_corporativa/Formulario/respostas/' . (int) $f['id']); ?>" class="btn btn-default btn-xs" title="Respostas"><i class="fa fa-table"></i></a>
                        <a href="<?php echo base_url('gestao_corporativa/Formulario/edit/' . (int) $f['id']); ?>" class="btn btn-default btn-xs" title="Editar"><i class="fa fa-pencil"></i></a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
