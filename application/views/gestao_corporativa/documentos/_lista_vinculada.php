<?php
/**
 * Partial reutilizável: lista de documentos vinculados a ata/plano/grupo/projeto.
 * Espera:
 *  - $vinculo_tipo  string: 'ata' | 'plano' | 'grupo' | 'project'
 *  - $vinculo_id    int
 */
defined('BASEPATH') or exit('No direct script access allowed');

$this->load->model('Documentos_model');
$filtros = [];
$qs_param = '';
switch ($vinculo_tipo ?? '') {
    case 'ata':     $filtros['ata_id']     = (int) $vinculo_id; $qs_param = 'ata_id';     break;
    case 'plano':   $filtros['plano_id']   = (int) $vinculo_id; $qs_param = 'plano_id';   break;
    case 'grupo':   $filtros['grupo_id']   = (int) $vinculo_id; $qs_param = 'grupo_id';   break;
    case 'project': $filtros['project_id'] = (int) $vinculo_id; $qs_param = 'project_id'; break;
    default: return;
}
$documentos = $this->Documentos_model->listar($filtros);
$titulo = $card_title ?? 'Documentos';
?>

<style>
    .docv-row{display:grid;grid-template-columns:1fr auto;gap:12px;align-items:center;padding:10px 0;border-bottom:1px solid #f1f5f9;}
    .docv-row:last-child{border-bottom:0;}
    .docv-row .titulo{font-weight:600;color:#1f2937;font-size:14px;text-decoration:none;}
    .docv-row .titulo:hover{color:#0a66c2;}
    .docv-row .codigo{font-family:monospace;font-size:11px;color:#94a3b8;font-weight:700;}
    .docv-row .meta{font-size:12px;color:#64748b;margin-top:3px;display:flex;gap:12px;flex-wrap:wrap;}
    .docv-row .actions{display:flex;gap:6px;}
    .docv-status{display:inline-block;padding:2px 8px;border-radius:6px;font-size:10px;font-weight:700;letter-spacing:.04em;text-transform:uppercase;}
    .docv-status.rascunho{background:#fef3c7;color:#92400e;}
    .docv-status.em_aprovacao{background:#fef3c7;color:#92400e;}
    .docv-status.aprovado{background:#dcfce7;color:#166534;}
    .docv-status.publicado{background:#dbeafe;color:#1e40af;}
    .docv-status.reprovado{background:#fee2e2;color:#991b1b;}
    .docv-empty{text-align:center;padding:24px 12px;color:#94a3b8;font-size:13px;}
</style>

<div class="ata-card form-card">
    <div class="ata-card-header form-card-header" style="display:flex;justify-content:space-between;align-items:center;">
        <span><i class="far fa-folder-open"></i> <?php echo html_escape($titulo); ?> (<?php echo count($documentos); ?>)</span>
        <a href="<?php echo base_url('gestao_corporativa/Documento/add?' . $qs_param . '=' . (int) $vinculo_id); ?>" class="btn btn-info btn-xs"><i class="fa fa-plus"></i> Novo</a>
    </div>
    <div class="ata-card-body form-card-body">
        <?php if (empty($documentos)): ?>
            <div class="docv-empty"><i class="far fa-folder-open fa-2x" style="color:#cbd5e1;display:block;margin-bottom:6px;"></i> Nenhum documento vinculado.</div>
        <?php else: ?>
            <?php foreach ($documentos as $d): ?>
                <div class="docv-row">
                    <div>
                        <div>
                            <span class="codigo"><?php echo html_escape($d['codigo']); ?></span>
                            <span class="docv-status <?php echo $d['status_slug']; ?>"><?php echo $this->Documentos_model->get_status_label($d['status_slug']); ?></span>
                        </div>
                        <a class="titulo" href="<?php echo base_url('gestao_corporativa/Documento/view/' . (int) $d['id']); ?>">
                            <?php echo html_escape($d['titulo']); ?>
                        </a>
                        <div class="meta">
                            <?php if (!empty($d['categoria_nome'])): ?><span><i class="fa fa-tag"></i> <?php echo html_escape($d['categoria_nome']); ?></span><?php endif; ?>
                            <span><i class="fa fa-user"></i> <?php echo html_escape($d['criador_nome']); ?></span>
                            <?php if ((int) $d['total_destinatarios'] > 0): ?>
                                <span><i class="fa fa-eye"></i> <?php echo (int) $d['total_lidos']; ?>/<?php echo (int) $d['total_destinatarios']; ?> leram</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="actions">
                        <?php if (!empty($d['file'])): ?>
                            <a href="<?php echo base_url('gestao_corporativa/Documento/download/' . (int) $d['id']); ?>" class="btn btn-success btn-xs" title="Baixar"><i class="fa fa-download"></i></a>
                        <?php endif; ?>
                        <a href="<?php echo base_url('gestao_corporativa/Documento/view/' . (int) $d['id']); ?>" class="btn btn-default btn-xs"><i class="fa fa-eye"></i> Abrir</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
