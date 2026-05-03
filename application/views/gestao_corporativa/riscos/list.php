<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head_intranet_novo(false); ?>
<?php $this->load->view('gestao_corporativa/css_background'); ?>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/lte/plugins/select2/css/select2.min.css">

<style>
    .rk-page{max-width:1400px;margin:24px auto;padding:0 18px;}
    .rk-toolbar{display:flex;justify-content:space-between;align-items:center;margin-bottom:14px;gap:12px;flex-wrap:wrap;}
    .rk-toolbar h2{margin:0;font-size:20px;color:#1f2937;}
    .rk-filters{display:grid;grid-template-columns:repeat(auto-fill,minmax(170px,1fr));gap:10px;background:#fff;border:1px solid #e5e7eb;border-radius:10px;padding:14px;margin-bottom:14px;}
    .rk-filters label{font-size:11px;font-weight:600;color:#475569;text-transform:uppercase;letter-spacing:.04em;display:block;margin-bottom:4px;}
    .rk-filters input,.rk-filters select{width:100%;border:1px solid #d0d5dd;border-radius:6px;padding:7px 10px;font-size:13px;}

    .rk-section{background:#fff;border:1px solid #e5e7eb;border-radius:10px;padding:18px;margin-bottom:14px;}
    .rk-section h3{margin:0 0 14px;font-size:15px;color:#1f2937;}

    /* Matriz 5x5 */
    .rk-matriz{display:grid;grid-template-columns:auto repeat(5, 1fr);gap:4px;font-size:12px;}
    .rk-matriz .axis-y,.rk-matriz .axis-x{display:flex;align-items:center;justify-content:center;font-weight:600;color:#475569;text-align:center;padding:6px;}
    .rk-matriz .axis-x{height:30px;}
    .rk-matriz .axis-y{writing-mode:vertical-rl;transform:rotate(180deg);min-height:60px;}
    .rk-matriz .header-y,.rk-matriz .header-x{font-weight:700;color:#94a3b8;font-size:10px;text-transform:uppercase;text-align:center;padding:4px;}
    .rk-matriz .cell{border-radius:8px;padding:8px;min-height:60px;display:flex;flex-direction:column;justify-content:flex-start;color:#fff;font-weight:700;position:relative;cursor:pointer;transition:.15s;border:2px solid transparent;}
    .rk-matriz .cell:hover{border-color:#1f2937;transform:scale(1.02);}
    .rk-matriz .cell .num{font-size:24px;line-height:1;text-shadow:0 1px 3px rgba(0,0,0,.3);}
    .rk-matriz .cell .lbl{font-size:9px;opacity:.85;text-transform:uppercase;letter-spacing:.04em;text-align:right;margin-top:auto;}

    .rk-matriz-wrap{display:grid;grid-template-columns:1fr 220px;gap:14px;align-items:start;}
    @media(max-width:900px){.rk-matriz-wrap{grid-template-columns:1fr;}}
    .rk-legenda{font-size:12px;}
    .rk-legenda .item{display:flex;align-items:center;gap:8px;padding:6px 0;}
    .rk-legenda .swatch{width:18px;height:18px;border-radius:4px;}

    .rk-list{display:flex;flex-direction:column;gap:8px;}
    .rk-row{display:grid;grid-template-columns:80px 1fr auto auto;gap:14px;align-items:center;background:#fff;border:1px solid #e5e7eb;border-radius:10px;padding:12px 16px;border-left:4px solid var(--c, #94a3b8);}
    .rk-row:hover{box-shadow:0 4px 12px rgba(0,0,0,.06);}
    .rk-row .sev-box{display:flex;flex-direction:column;align-items:center;gap:2px;background:var(--c);color:#fff;border-radius:8px;padding:6px;}
    .rk-row .sev-box .num{font-size:22px;font-weight:700;line-height:1;}
    .rk-row .sev-box .lbl{font-size:9px;text-transform:uppercase;letter-spacing:.04em;font-weight:700;}
    .rk-row .codigo{font-family:monospace;font-size:11px;color:#94a3b8;font-weight:700;}
    .rk-row .ttl{font-size:14px;font-weight:600;color:#1f2937;text-decoration:none;display:block;}
    .rk-row .ttl:hover{color:#0a66c2;}
    .rk-row .meta{font-size:11px;color:#64748b;display:flex;flex-wrap:wrap;gap:10px;margin-top:4px;}
    .rk-status{padding:2px 8px;border-radius:6px;font-size:10px;font-weight:700;text-transform:uppercase;}

    .rk-empty{text-align:center;padding:48px;color:#94a3b8;}
</style>

<?php
$model = $this->Risco_model;
function _cor_cel($model, $i, $p) {
    return $model->get_nivel_color($model->calc_nivel($model->calc_severidade($p, $i)));
}
?>

<div class="content">
    <div class="rk-page">
        <ol class="breadcrumb" style="background:#fff;">
            <li><a href="<?php echo base_url('gestao_corporativa/intranet'); ?>"><i class="fa fa-home"></i> Home</a></li>
            <li class="active">Matriz de Riscos</li>
        </ol>

        <div class="rk-toolbar">
            <h2><i class="fa fa-shield-alt"></i> Matriz de Riscos (<?php echo count($riscos); ?>)</h2>
            <a href="<?php echo base_url('gestao_corporativa/Risco/add'); ?>" class="btn btn-info"><i class="fa fa-plus"></i> Novo risco</a>
        </div>

        <form method="get" action="">
            <div class="rk-filters">
                <div>
                    <label>Buscar</label>
                    <input type="text" name="busca" value="<?php echo html_escape($filtros['busca'] ?? ''); ?>" placeholder="título, código…">
                </div>
                <div>
                    <label>Categoria</label>
                    <select name="categoria">
                        <option value="">— todas —</option>
                        <?php foreach ($model->get_categorias() as $k => $v): ?>
                            <option value="<?php echo $k; ?>" <?php echo $filtros['categoria'] === $k ? 'selected' : ''; ?>><?php echo $v; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label>Nível</label>
                    <select name="nivel">
                        <option value="">— todos —</option>
                        <?php foreach (['baixo','moderado','alto','critico'] as $n): ?>
                            <option value="<?php echo $n; ?>" <?php echo $filtros['nivel'] === $n ? 'selected' : ''; ?>><?php echo $model->get_nivel_label($n); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label>Status</label>
                    <select name="status">
                        <option value="">— todos —</option>
                        <?php foreach ($model->get_statuses() as $s): ?>
                            <option value="<?php echo $s; ?>" <?php echo $filtros['status'] === $s ? 'selected' : ''; ?>><?php echo $model->get_status_label($s); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label>Projeto</label>
                    <select name="project_id" class="select2">
                        <option value="">— todos —</option>
                        <?php foreach ($projects as $p): ?>
                            <option value="<?php echo (int) $p['id']; ?>" <?php echo (int) $filtros['project_id'] === (int) $p['id'] ? 'selected' : ''; ?>><?php echo html_escape($p['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label>Visão</label>
                    <?php $visao = !empty($filtros['criei']) ? 'criei' : (!empty($filtros['responsavel_meu']) ? 'resp' : ''); ?>
                    <select name="visao" onchange="
                        this.form.querySelector('input[name=criei]').value           = this.value === 'criei' ? 1 : '';
                        this.form.querySelector('input[name=responsavel_meu]').value = this.value === 'resp'  ? 1 : '';
                        this.form.submit();
                    ">
                        <option value="">Todos</option>
                        <option value="criei" <?php echo $visao === 'criei' ? 'selected' : ''; ?>>Que criei</option>
                        <option value="resp"  <?php echo $visao === 'resp'  ? 'selected' : ''; ?>>Sou responsável</option>
                    </select>
                    <input type="hidden" name="criei" value="<?php echo $visao === 'criei' ? 1 : ''; ?>">
                    <input type="hidden" name="responsavel_meu" value="<?php echo $visao === 'resp' ? 1 : ''; ?>">
                </div>
                <div>
                    <label>&nbsp;</label>
                    <button type="submit" class="btn btn-default" style="width:100%;"><i class="fa fa-filter"></i> Filtrar</button>
                </div>
            </div>
        </form>

        <!-- Matriz visual -->
        <div class="rk-section">
            <h3>Matriz Probabilidade × Impacto</h3>
            <div class="rk-matriz-wrap">
                <div class="rk-matriz">
                    <div></div>
                    <div class="header-x" style="grid-column:span 5;">PROBABILIDADE →</div>

                    <div class="header-y">IMPACTO ↑</div>
                    <?php for ($p = 1; $p <= 5; $p++): ?>
                        <div class="axis-x"><?php echo $p; ?> · <?php echo $model->get_prob_label($p); ?></div>
                    <?php endfor; ?>

                    <?php for ($i = 5; $i >= 1; $i--): ?>
                        <div class="axis-y"><?php echo $i; ?> · <?php echo $model->get_imp_label($i); ?></div>
                        <?php for ($p = 1; $p <= 5; $p++):
                            $cor = _cor_cel($model, $i, $p);
                            $items = $matriz[$i][$p] ?? [];
                        ?>
                            <div class="cell" style="background:<?php echo $cor; ?>;" title="Severidade <?php echo $i * $p; ?> — <?php echo count($items); ?> risco(s)" onclick="filtrarCelula(<?php echo $p; ?>, <?php echo $i; ?>)">
                                <div class="num"><?php echo count($items); ?></div>
                                <div class="lbl"><?php echo $i * $p; ?></div>
                            </div>
                        <?php endfor; ?>
                    <?php endfor; ?>
                </div>

                <div class="rk-legenda">
                    <strong style="font-size:11px;color:#475569;text-transform:uppercase;letter-spacing:.04em;">Níveis</strong>
                    <div class="item"><div class="swatch" style="background:#16a34a;"></div> Baixo (1–4)</div>
                    <div class="item"><div class="swatch" style="background:#f59e0b;"></div> Moderado (5–9)</div>
                    <div class="item"><div class="swatch" style="background:#ea580c;"></div> Alto (10–15)</div>
                    <div class="item"><div class="swatch" style="background:#dc2626;"></div> Crítico (16–25)</div>
                    <hr style="margin:10px 0;">
                    <small style="color:#94a3b8;font-size:11px;">Severidade = Probabilidade × Impacto</small>
                </div>
            </div>
        </div>

        <!-- Lista -->
        <div class="rk-list">
            <?php if (empty($riscos)): ?>
                <div class="rk-empty">
                    <i class="fa fa-shield-alt fa-3x" style="color:#cbd5e1;"></i>
                    <h4>Nenhum risco cadastrado.</h4>
                </div>
            <?php else: ?>
                <?php foreach ($riscos as $r):
                    $cor = $model->get_nivel_color($r['nivel']);
                ?>
                    <div class="rk-row" style="--c:<?php echo $cor; ?>;">
                        <div class="sev-box" style="background:<?php echo $cor; ?>;">
                            <div class="num"><?php echo (int) $r['severidade']; ?></div>
                            <div class="lbl"><?php echo $model->get_nivel_label($r['nivel']); ?></div>
                        </div>
                        <div>
                            <span class="codigo"><?php echo html_escape($r['codigo']); ?></span>
                            <a class="ttl" href="<?php echo base_url('gestao_corporativa/Risco/view/' . (int) $r['id']); ?>"><?php echo html_escape($r['titulo']); ?></a>
                            <div class="meta">
                                <span class="rk-status" style="background:<?php echo $cor; ?>20;color:<?php echo $cor; ?>;"><?php echo $model->get_status_label($r['status']); ?></span>
                                <span><i class="fa fa-tag"></i> <?php echo $model->get_categoria_label($r['categoria']); ?></span>
                                <span><i class="fa fa-shield-alt"></i> <?php echo $model->get_tratamento_label($r['tipo_tratamento']); ?></span>
                                <span>P<?php echo (int) $r['probabilidade']; ?> × I<?php echo (int) $r['impacto']; ?></span>
                                <?php if (!empty($r['setor_nome'])): ?><span><i class="fa fa-building"></i> <?php echo html_escape($r['setor_nome']); ?></span><?php endif; ?>
                                <?php if (!empty($r['responsavel_nome'])): ?><span><i class="fa fa-user"></i> <?php echo html_escape($r['responsavel_nome']); ?></span><?php endif; ?>
                                <?php if (!empty($r['project_name'])): ?><span><i class="fa fa-folder"></i> <?php echo html_escape($r['project_name']); ?></span><?php endif; ?>
                            </div>
                        </div>
                        <div>
                            <?php if ($r['dt_revisao']):
                                $atras = $r['dt_revisao'] < date('Y-m-d');
                            ?>
                                <span style="font-size:11px;color:<?php echo $atras ? '#dc2626' : '#94a3b8'; ?>;">
                                    <i class="fa fa-redo"></i> revisar <?php echo date('d/m/Y', strtotime($r['dt_revisao'])); ?>
                                </span>
                            <?php endif; ?>
                        </div>
                        <a href="<?php echo base_url('gestao_corporativa/Risco/view/' . (int) $r['id']); ?>" class="btn btn-default btn-xs"><i class="fa fa-eye"></i></a>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php init_tail(); ?>
<script src="<?php echo base_url() ?>assets/lte/plugins/select2/js/select2.full.min.js"></script>
<script>
$(function(){$('.select2').select2({width:'100%'});});
function filtrarCelula(prob, imp) {
    // não há filtro direto por prob+imp, mas vamos rolar até a lista (pessoas vão ver as bolinhas)
    document.querySelector('.rk-list').scrollIntoView({behavior:'smooth'});
}
</script>
</body>
</html>
