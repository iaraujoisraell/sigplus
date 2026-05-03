<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head_intranet_novo(); ?>
<?php $this->load->view('gestao_corporativa/css_background'); ?>

<style>
    .pa-page{max-width:1180px;margin:24px auto;padding:0 18px;}
    .pa-header{display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;margin-bottom:18px;}
    .pa-header h1{margin:0;font-size:22px;font-weight:700;color:#1f2937;}
    .pa-filters{display:grid;grid-template-columns:repeat(4,1fr) auto;gap:10px;margin-bottom:14px;}
    .pa-filters input,.pa-filters select{border:1px solid #d0d5dd;border-radius:8px;padding:8px 12px;font-size:13px;outline:none;}
    @media (max-width:900px){.pa-filters{grid-template-columns:1fr 1fr;}}

    .pa-row{background:#fff;border:1px solid #e5e7eb;border-radius:10px;padding:14px 18px;margin-bottom:8px;display:grid;grid-template-columns:1fr auto;gap:14px;align-items:center;transition:.15s;}
    .pa-row:hover{border-color:#0a66c2;box-shadow:0 4px 10px rgba(10,102,194,.06);}
    .pa-row.is-atrasado{border-left:4px solid #ef4444;}
    .pa-row .titulo{font-size:15px;font-weight:600;color:#1f2937;margin:0;}
    .pa-row .titulo a{color:#1f2937;text-decoration:none;}
    .pa-row .titulo a:hover{color:#0a66c2;}
    .pa-row .meta{display:flex;gap:14px;font-size:12px;color:#64748b;margin-top:6px;flex-wrap:wrap;}
    .pa-row .meta i{color:#94a3b8;margin-right:4px;}
    .pa-row .actions{display:flex;gap:6px;}
    .pa-row .actions a{padding:6px 12px;border-radius:6px;font-size:12px;font-weight:600;text-decoration:none;border:1px solid #d0d5dd;color:#475569;}
    .pa-row .actions a:hover{border-color:#0a66c2;color:#0a66c2;}

    .status-pill{display:inline-block;font-size:11px;font-weight:600;padding:2px 9px;border-radius:999px;}
    .status-aberto{background:#dbeafe;color:#1e40af;}
    .status-em_execucao{background:#fef9c3;color:#854d0e;}
    .status-concluido{background:#dcfce7;color:#166534;}
    .status-cancelado{background:#fee2e2;color:#991b1b;}
    .status-atrasado{background:#fecaca;color:#7f1d1d;}

    .metodologia-pill{background:#eef2ff;color:#4338ca;font-size:10px;font-weight:600;padding:2px 8px;border-radius:6px;margin-left:4px;}

    .pa-empty{text-align:center;padding:50px 20px;color:#64748b;background:#fff;border-radius:12px;border:1px dashed #e5e7eb;}
    .pa-empty i{font-size:38px;color:#cbd5e1;margin-bottom:10px;display:block;}
</style>

<div class="content">
    <div class="pa-page">
        <div class="pa-header">
            <h1>Planos de Ação</h1>
            <div>
                <?php if (has_permission_intranet('planos_acao', '', 'create') || is_admin()): ?>
                    <a href="<?php echo base_url('gestao_corporativa/Plano_acao/add' . (!empty($filtros['project_id']) ? '?project_id=' . (int) $filtros['project_id'] : '')); ?>" class="btn btn-info">
                        <i class="fa fa-plus"></i> Novo plano
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <form method="get" class="pa-filters">
            <input type="text" name="busca" placeholder="Buscar por título ou descrição" value="<?php echo html_escape($filtros['busca'] ?? ''); ?>">
            <select name="status">
                <option value="">Todos status</option>
                <?php foreach ($this->Plano_acao_model->get_statuses() as $s): ?>
                    <option value="<?php echo $s; ?>" <?php echo ($filtros['status'] ?? '') === $s ? 'selected' : ''; ?>><?php echo $this->Plano_acao_model->get_status_label($s); ?></option>
                <?php endforeach; ?>
            </select>
            <select name="project_id">
                <option value="">Todos os projetos</option>
                <?php
                $empresa_id = (int) $this->session->userdata('empresa_id');
                $projects = $this->db->select('id, name')->where('empresa_id', $empresa_id)->order_by('name', 'asc')->get('tblprojects')->result_array();
                foreach ($projects as $p): ?>
                    <option value="<?php echo (int) $p['id']; ?>" <?php echo ((int) ($filtros['project_id'] ?? 0)) === (int) $p['id'] ? 'selected' : ''; ?>><?php echo html_escape($p['name']); ?></option>
                <?php endforeach; ?>
            </select>
            <?php $visao = !empty($filtros['criei']) ? 'criei' : (!empty($filtros['responsavel_meu']) ? 'resp' : (!empty($filtros['minha']) ? 'minha' : '')); ?>
            <select name="visao" onchange="
                this.form.querySelector('input[name=criei]').value           = this.value === 'criei' ? 1 : '';
                this.form.querySelector('input[name=responsavel_meu]').value = this.value === 'resp'  ? 1 : '';
                this.form.querySelector('input[name=minha]').value           = this.value === 'minha' ? 1 : '';
                this.form.submit();
            ">
                <option value="">Todos</option>
                <option value="criei" <?php echo $visao === 'criei' ? 'selected' : ''; ?>>Que criei</option>
                <option value="resp"  <?php echo $visao === 'resp'  ? 'selected' : ''; ?>>Sou responsável</option>
                <option value="minha" <?php echo $visao === 'minha' ? 'selected' : ''; ?>>Tudo que envolve eu</option>
            </select>
            <input type="hidden" name="criei" value="<?php echo $visao === 'criei' ? 1 : ''; ?>">
            <input type="hidden" name="responsavel_meu" value="<?php echo $visao === 'resp' ? 1 : ''; ?>">
            <input type="hidden" name="minha" value="<?php echo $visao === 'minha' ? 1 : ''; ?>">
            <button type="submit" class="btn btn-default">Filtrar</button>
        </form>

        <?php if (empty($planos)): ?>
            <div class="pa-empty">
                <i class="fa fa-tasks"></i>
                <p>Nenhum plano de ação encontrado.</p>
            </div>
        <?php else: ?>
            <?php $hoje = date('Y-m-d'); foreach ($planos as $p):
                $atrasado = !empty($p['dt_fim']) && $p['dt_fim'] < $hoje && in_array($p['status'], ['aberto', 'em_execucao'], true);
                $row_class = 'pa-row' . ($atrasado ? ' is-atrasado' : '');
            ?>
                <div class="<?php echo $row_class; ?>">
                    <div>
                        <h4 class="titulo">
                            <a href="<?php echo base_url('gestao_corporativa/Plano_acao/view/' . (int) $p['id']); ?>"><?php echo html_escape($p['titulo']); ?></a>
                            <span class="status-pill status-<?php echo $p['status']; ?>" style="margin-left:8px;"><?php echo $this->Plano_acao_model->get_status_label($p['status']); ?></span>
                            <span class="metodologia-pill"><?php echo strtoupper($p['metodologia']); ?></span>
                            <?php if ($atrasado): ?><span class="status-pill status-atrasado">Atrasado</span><?php endif; ?>
                        </h4>
                        <div class="meta">
                            <?php if (!empty($p['responsavel_nome'])): ?><span><i class="fa fa-user"></i><?php echo html_escape($p['responsavel_nome']); ?></span><?php endif; ?>
                            <?php if (!empty($p['project_name'])): ?><span><i class="fa fa-folder-o"></i><?php echo html_escape($p['project_name']); ?></span><?php endif; ?>
                            <?php if (!empty($p['ata_titulo'])): ?><span><i class="fa fa-file-text-o"></i><?php echo html_escape($p['ata_titulo']); ?></span><?php endif; ?>
                            <?php if (!empty($p['dt_fim'])): ?><span><i class="fa fa-calendar"></i>Prazo: <?php echo date('d/m/Y', strtotime($p['dt_fim'])); ?></span><?php endif; ?>
                        </div>
                    </div>
                    <div class="actions">
                        <a href="<?php echo base_url('gestao_corporativa/Plano_acao/view/' . (int) $p['id']); ?>"><i class="fa fa-eye"></i> Ver</a>
                        <?php if (has_permission_intranet('planos_acao', '', 'edit') || is_admin()): ?>
                            <a href="<?php echo base_url('gestao_corporativa/Plano_acao/edit/' . (int) $p['id']); ?>"><i class="fa fa-pencil"></i> Editar</a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<?php init_tail(); ?>
