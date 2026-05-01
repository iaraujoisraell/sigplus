<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head_intranet_novo(); ?>
<?php $this->load->view('gestao_corporativa/css_background'); ?>

<style>
    .ev-page{max-width:1180px;margin:24px auto;padding:0 18px;}
    .ev-header{display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;margin-bottom:18px;}
    .ev-header h1{margin:0;font-size:22px;font-weight:700;color:#1f2937;}
    .ev-filters{display:grid;grid-template-columns:repeat(4,1fr) auto;gap:10px;margin-bottom:14px;}
    .ev-filters input,.ev-filters select{border:1px solid #d0d5dd;border-radius:8px;padding:8px 12px;font-size:13px;outline:none;}
    @media (max-width:900px){.ev-filters{grid-template-columns:1fr 1fr;}}

    .ev-row{background:#fff;border:1px solid #e5e7eb;border-radius:10px;padding:14px 18px;margin-bottom:8px;display:grid;grid-template-columns:60px 1fr auto;gap:14px;align-items:center;transition:.15s;}
    .ev-row:hover{border-color:#0a66c2;box-shadow:0 4px 10px rgba(10,102,194,.06);}
    .ev-row .data-box{text-align:center;border-radius:8px;background:#f3f6fa;padding:6px 0;}
    .ev-row .data-box .dia{font-size:18px;font-weight:700;color:#0a66c2;line-height:1;}
    .ev-row .data-box .mes{font-size:10px;font-weight:700;color:#64748b;text-transform:uppercase;}
    .ev-row .titulo{font-size:15px;font-weight:600;color:#1f2937;margin:0;}
    .ev-row .titulo a{color:#1f2937;text-decoration:none;}
    .ev-row .titulo a:hover{color:#0a66c2;}
    .ev-row .meta{display:flex;gap:14px;font-size:12px;color:#64748b;margin-top:4px;flex-wrap:wrap;}
    .ev-row .meta i{color:#94a3b8;margin-right:4px;}
    .ev-row .actions a{padding:6px 12px;border-radius:6px;font-size:12px;font-weight:600;text-decoration:none;border:1px solid #d0d5dd;color:#475569;}
    .ev-row .actions a:hover{border-color:#0a66c2;color:#0a66c2;}
    .ev-row .color-dot{display:inline-block;width:10px;height:10px;border-radius:50%;margin-right:6px;vertical-align:middle;}
    .ev-empty{text-align:center;padding:50px 20px;color:#64748b;background:#fff;border-radius:12px;border:1px dashed #e5e7eb;}
    .ev-empty i{font-size:38px;color:#cbd5e1;margin-bottom:10px;display:block;}
</style>

<div class="content">
    <div class="ev-page">
        <div class="ev-header">
            <h1>Eventos</h1>
            <a href="<?php echo base_url('gestao_corporativa/Eventoplus/add'); ?>" class="btn btn-info"><i class="fa fa-plus"></i> Novo evento</a>
        </div>

        <form method="get" class="ev-filters">
            <input type="text" name="busca" placeholder="Buscar por título, descrição ou local" value="<?php echo html_escape($filtros['busca'] ?? ''); ?>">
            <select name="meu">
                <option value="">Todos</option>
                <option value="1" <?php echo !empty($filtros['meu']) ? 'selected' : ''; ?>>Apenas meus</option>
            </select>
            <select name="futuros">
                <option value="">Todos</option>
                <option value="1" <?php echo !empty($filtros['futuros']) ? 'selected' : ''; ?>>Somente futuros</option>
            </select>
            <select name="project_id">
                <option value="">Todos projetos</option>
                <?php
                $empresa_id = (int) $this->session->userdata('empresa_id');
                $projects = $this->db->select('id, name')->where('empresa_id', $empresa_id)->order_by('name', 'asc')->get('tblprojects')->result_array();
                foreach ($projects as $p): ?>
                    <option value="<?php echo (int) $p['id']; ?>" <?php echo ((int) ($filtros['project_id'] ?? 0)) === (int) $p['id'] ? 'selected' : ''; ?>><?php echo html_escape($p['name']); ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="btn btn-default">Filtrar</button>
        </form>

        <?php if (empty($eventos)): ?>
            <div class="ev-empty"><i class="far fa-calendar-alt"></i><p>Nenhum evento encontrado.</p></div>
        <?php else: ?>
            <?php $meses = ['','jan','fev','mar','abr','mai','jun','jul','ago','set','out','nov','dez']; foreach ($eventos as $e):
                $ts = strtotime($e['start']);
            ?>
                <div class="ev-row">
                    <div class="data-box">
                        <div class="dia"><?php echo date('d', $ts); ?></div>
                        <div class="mes"><?php echo $meses[(int) date('n', $ts)]; ?></div>
                    </div>
                    <div>
                        <h4 class="titulo">
                            <span class="color-dot" style="background:<?php echo html_escape($e['color'] ?? '#0a66c2'); ?>;"></span>
                            <a href="<?php echo base_url('gestao_corporativa/Eventoplus/view/' . (int) $e['id']); ?>"><?php echo html_escape($e['title']); ?></a>
                        </h4>
                        <div class="meta">
                            <span><i class="fa fa-clock-o"></i><?php echo date('d/m/Y H:i', $ts); ?><?php if (!empty($e['end'])): ?> – <?php echo date('H:i', strtotime($e['end'])); ?><?php endif; ?></span>
                            <?php if (!empty($e['onde'])): ?><span><i class="fa fa-map-marker"></i><?php echo html_escape($e['onde']); ?></span><?php endif; ?>
                            <?php if (!empty($e['criador_nome'])): ?><span><i class="fa fa-user"></i><?php echo html_escape($e['criador_nome']); ?></span><?php endif; ?>
                            <?php if (!empty($e['project_name'])): ?><span><i class="fa fa-folder-o"></i><?php echo html_escape($e['project_name']); ?></span><?php endif; ?>
                            <?php if (!empty($e['ata_titulo'])): ?><span><i class="far fa-file-alt"></i><?php echo html_escape($e['ata_titulo']); ?></span><?php endif; ?>
                            <?php if (!empty($e['plano_titulo'])): ?><span><i class="fas fa-clipboard-list"></i><?php echo html_escape($e['plano_titulo']); ?></span><?php endif; ?>
                            <?php if (!empty($e['grupo_titulo'])): ?><span><i class="fas fa-users-cog"></i><?php echo html_escape($e['grupo_titulo']); ?></span><?php endif; ?>
                        </div>
                    </div>
                    <div class="actions">
                        <a href="<?php echo base_url('gestao_corporativa/Eventoplus/view/' . (int) $e['id']); ?>"><i class="fa fa-eye"></i> Ver</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<?php init_tail(); ?>
