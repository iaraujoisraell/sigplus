<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<style>
    .pendentes-page{max-width:920px;margin:30px auto;padding:0 18px;}
    .pendentes-header{display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;margin-bottom:18px;}
    .pendentes-title{font-size:22px;font-weight:700;color:#1f2937;margin:0;display:flex;align-items:center;gap:10px;}
    .pendentes-title .badge-count{background:#ef4444;color:#fff;font-size:13px;font-weight:700;padding:4px 12px;border-radius:999px;}
    .pendentes-tools{display:flex;gap:8px;align-items:center;}
    .pendentes-tools input{border:1px solid #d0d5dd;border-radius:8px;padding:8px 12px;font-size:13px;width:240px;outline:none;}
    .pendentes-tools input:focus{border-color:#0a66c2;}
    .pendentes-alert{background:#fffbeb;border:1px solid #fde68a;color:#78350f;padding:12px 16px;border-radius:10px;margin-bottom:18px;display:flex;gap:10px;align-items:flex-start;font-size:14px;}
    .pendentes-alert i{color:#d97706;margin-top:2px;}

    .ci-list{display:flex;flex-direction:column;gap:10px;}
    .ci-pend-item{background:#fff;border:1px solid #e5e7eb;border-radius:10px;padding:14px 18px;display:grid;grid-template-columns:auto 1fr auto;gap:14px;align-items:center;transition:.15s;}
    .ci-pend-item:hover{border-color:#0a66c2;box-shadow:0 4px 12px rgba(10,102,194,.08);}
    .ci-pend-item.is-urgente{border-left:4px solid #ef4444;}
    .ci-pend-item.is-alta{border-left:4px solid #f59e0b;}

    .ci-pend-avatar{width:42px;height:42px;border-radius:50%;background:#dbeafe;color:#0a66c2;font-weight:700;display:flex;align-items:center;justify-content:center;font-size:15px;flex-shrink:0;}
    .ci-pend-meta{min-width:0;}
    .ci-pend-meta .codigo{font-size:11px;color:#6b7280;font-weight:600;text-transform:uppercase;letter-spacing:.04em;}
    .ci-pend-meta .titulo{font-size:15px;font-weight:600;color:#1f2937;margin-top:2px;line-height:1.3;}
    .ci-pend-meta .preview{color:#64748b;font-size:13px;line-height:1.45;margin-top:6px;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;}
    .ci-pend-meta .info{display:flex;gap:14px;margin-top:8px;flex-wrap:wrap;font-size:12px;color:#64748b;}
    .ci-pend-meta .info i{margin-right:4px;color:#94a3b8;}
    .ci-pend-meta .badges{display:flex;gap:6px;margin-top:6px;flex-wrap:wrap;}
    .badge-pill{display:inline-block;font-size:11px;font-weight:600;padding:2px 8px;border-radius:999px;letter-spacing:.02em;}
    .badge-categoria{background:#eef2ff;color:#4338ca;}
    .badge-prioridade-alta{background:#fef3c7;color:#92400e;}
    .badge-prioridade-urgente{background:#fee2e2;color:#991b1b;}
    .badge-cc{background:#f3f4f6;color:#6b7280;}
    .badge-retorno{background:#fce7f3;color:#9d174d;}

    .ci-pend-actions{display:flex;gap:6px;align-items:center;}
    .ci-pend-actions .btn-ver{background:#0a66c2;color:#fff;border:0;border-radius:8px;padding:9px 16px;font-size:13px;font-weight:600;cursor:pointer;text-decoration:none;display:inline-flex;align-items:center;gap:6px;white-space:nowrap;}
    .ci-pend-actions .btn-ver:hover{background:#0858a8;color:#fff;}

    .ci-empty{text-align:center;padding:60px 20px;color:#64748b;background:#fff;border-radius:12px;border:1px dashed #e5e7eb;}
    .ci-empty i{font-size:42px;color:#a7f3d0;margin-bottom:12px;display:block;}
    .ci-empty h3{margin:0 0 6px;color:#1f2937;font-size:18px;font-weight:600;}

    @media (max-width:640px){
        .ci-pend-item{grid-template-columns:1fr;}
        .ci-pend-actions{justify-content:flex-end;}
    }
</style>

<section class="content">
    <div class="pendentes-page">
        <div class="pendentes-header">
            <h1 class="pendentes-title">
                Comunicados pendentes
                <?php if (!empty($comunicados)): ?>
                    <span class="badge-count"><?php echo count($comunicados); ?></span>
                <?php endif; ?>
            </h1>
            <?php if (!empty($comunicados) && count($comunicados) > 4): ?>
                <div class="pendentes-tools">
                    <input type="text" id="filtro-pend" placeholder="Filtrar por título ou autor...">
                </div>
            <?php endif; ?>
        </div>

        <?php if (empty($comunicados)): ?>
            <div class="ci-empty">
                <i class="fa fa-check-circle"></i>
                <h3>Tudo em dia!</h3>
                <p>Você não tem comunicados pendentes no momento.</p>
            </div>
        <?php else: ?>
            <div class="pendentes-alert">
                <i class="fa fa-exclamation-triangle"></i>
                <div>
                    <strong>Atenção.</strong> Você precisa dar ciência nos comunicados abaixo. Clique em "Visualizar" pra ler e confirmar leitura.
                </div>
            </div>

            <div class="ci-list" id="ci-list">
                <?php foreach ($comunicados as $c):
                    $ci_id   = $c['ci'];
                    $send_id = $c['send_id'];
                    $codigo  = !empty($c['codigo']) ? $c['codigo'] : '#' . $ci_id;
                    $titulo  = !empty($c['titulo']) ? $c['titulo'] : '(sem título)';
                    $autor   = !empty($c['autor_nome']) ? trim($c['autor_nome']) : '—';
                    $setor   = !empty($c['setor_nome']) ? $c['setor_nome'] : '';
                    $dt      = !empty($c['dt_send']) ? date('d/m/Y H:i', strtotime($c['dt_send'])) : '';
                    $prior   = isset($c['prioridade']) ? $c['prioridade'] : 'normal';
                    $cat     = !empty($c['categoria']) ? $c['categoria'] : '';
                    $is_cc   = !empty($c['cc']);
                    $req_ret = !empty($c['retorno']);
                    $preview = '';
                    if (!empty($c['descricao'])) {
                        $preview = trim(strip_tags($c['descricao']));
                        $preview = mb_strlen($preview) > 220 ? mb_substr($preview, 0, 220) . '…' : $preview;
                    }

                    $iniciais = '';
                    foreach (preg_split('/\s+/', $autor) as $part) {
                        if ($part !== '' && $part !== '—') $iniciais .= mb_strtoupper(mb_substr($part, 0, 1));
                        if (mb_strlen($iniciais) >= 2) break;
                    }
                    if ($iniciais === '') $iniciais = '?';

                    $row_class = 'ci-pend-item';
                    if ($prior === 'urgente') $row_class .= ' is-urgente';
                    elseif ($prior === 'alta') $row_class .= ' is-alta';
                ?>
                    <div class="<?php echo $row_class; ?>" data-search="<?php echo html_escape(strtolower($titulo . ' ' . $autor . ' ' . $codigo)); ?>">
                        <div class="ci-pend-avatar"><?php echo $iniciais; ?></div>
                        <div class="ci-pend-meta">
                            <div class="codigo">CI <?php echo html_escape($codigo); ?></div>
                            <div class="titulo"><?php echo html_escape($titulo); ?></div>
                            <?php if ($preview !== ''): ?>
                                <div class="preview"><?php echo html_escape($preview); ?></div>
                            <?php endif; ?>
                            <div class="info">
                                <span><i class="fa fa-user"></i><?php echo html_escape($autor); ?></span>
                                <?php if ($setor): ?><span><i class="fa fa-building-o"></i><?php echo html_escape($setor); ?></span><?php endif; ?>
                                <?php if ($dt): ?><span><i class="fa fa-clock-o"></i><?php echo $dt; ?></span><?php endif; ?>
                            </div>
                            <?php if ($cat || $prior !== 'normal' || $is_cc || $req_ret): ?>
                                <div class="badges">
                                    <?php if ($cat): ?><span class="badge-pill badge-categoria"><?php echo html_escape(ucfirst($cat)); ?></span><?php endif; ?>
                                    <?php if ($prior === 'alta'): ?><span class="badge-pill badge-prioridade-alta">Prioridade alta</span><?php endif; ?>
                                    <?php if ($prior === 'urgente'): ?><span class="badge-pill badge-prioridade-urgente">Urgente</span><?php endif; ?>
                                    <?php if ($req_ret): ?><span class="badge-pill badge-retorno">Exige retorno</span><?php endif; ?>
                                    <?php if ($is_cc): ?><span class="badge-pill badge-cc">CC</span><?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="ci-pend-actions">
                            <a class="btn-ver" href="<?php echo base_url('gestao_corporativa/intra/Comunicado/visualizar_comunicado/?id=' . $ci_id . '&send_id=' . $send_id); ?>">
                                <i class="fa fa-eye"></i> Visualizar
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<script>
(function () {
    var input = document.getElementById('filtro-pend');
    if (!input) return;
    input.addEventListener('input', function () {
        var q = this.value.trim().toLowerCase();
        document.querySelectorAll('#ci-list .ci-pend-item').forEach(function (el) {
            el.style.display = !q || el.dataset.search.indexOf(q) !== -1 ? '' : 'none';
        });
    });
})();
</script>
