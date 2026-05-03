<?php
/** @var array $p pergunta */
/** @var int $i índice */
/** @var array $tipos */
$p = $p ?? [];
$opcoes = $p['opcoes'] ?? [];
$cfg = $p['configuracao_arr'] ?? [];
$tipo = $p['tipo'] ?? 'text';
$is_opt = in_array($tipo, ['radio', 'checkbox', 'select'], true);
$marker = $tipo === 'radio' ? '◯' : ($tipo === 'checkbox' ? '☐' : ($tipo === 'select' ? '▾' : '·'));
?>
<div class="fm-perg" data-id="<?php echo (int) ($p['id'] ?? 0); ?>">
    <div class="fm-perg-collapsed">
        <div class="ttl">
            <span class="num"><?php echo ($i + 1); ?>.</span>
            <span class="ttl-text"><?php echo html_escape($p['title'] ?? '(sem título)'); ?></span>
        </div>
        <div class="meta">
            <span class="tipo-text"><?php echo html_escape($tipos[$tipo] ?? $tipo); ?></span>
            <span class="req-text" style="<?php echo empty($p['required']) ? 'display:none;' : ''; ?>color:#dc2626;">obrigatória</span>
            <i class="fa fa-pencil" style="color:#94a3b8;"></i>
        </div>
    </div>

    <div class="fm-perg-expanded">
        <div class="fm-perg-grid">
            <div>
                <input type="text" class="fm-perg-titulo auto-save" value="<?php echo html_escape($p['title'] ?? ''); ?>" placeholder="Pergunta">
                <textarea class="fm-perg-desc auto-save" placeholder="Descrição/dica (opcional)"><?php echo html_escape($p['descricao'] ?? ''); ?></textarea>
            </div>
            <div>
                <select class="fm-perg-tipo auto-save">
                    <?php foreach ($tipos as $k => $label): ?>
                        <option value="<?php echo $k; ?>" <?php echo $tipo === $k ? 'selected' : ''; ?>><?php echo html_escape($label); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="fm-opcoes-wrap" style="<?php echo $is_opt ? '' : 'display:none;'; ?>">
            <div class="fm-opcoes">
                <?php foreach ($opcoes as $o): ?>
                    <div class="fm-opcao" data-id="<?php echo (int) $o['id']; ?>">
                        <span class="marker"><?php echo $marker; ?></span>
                        <input type="text" class="txt" value="<?php echo html_escape($o['name']); ?>">
                        <button class="rm" type="button"><i class="fa fa-times"></i></button>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="fm-add-opcao">
                <span class="marker" style="color:#cbd5e1;font-size:14px;width:18px;text-align:center;"><?php echo $marker; ?></span>
                <span>Adicionar opção</span>
            </div>
        </div>

        <div style="margin-top:10px;">
            <input type="text" class="fm-perg-placeholder auto-save" value="<?php echo html_escape($cfg['placeholder'] ?? ''); ?>" placeholder="Placeholder/dica de preenchimento (opcional)" style="width:100%;border:1px solid #d0d5dd;border-radius:6px;padding:7px 10px;font-size:13px;">
        </div>

        <div class="fm-perg-footer">
            <div class="left">
                <label>
                    <input type="checkbox" class="fm-perg-required auto-save" <?php echo !empty($p['required']) ? 'checked' : ''; ?>> Obrigatória
                </label>
                <label style="display:flex;gap:6px;align-items:center;">
                    Página
                    <input type="number" class="fm-perg-pagina auto-save" value="<?php echo (int) ($p['pagina'] ?? 1); ?>" min="1" style="width:60px;border:1px solid #d0d5dd;border-radius:6px;padding:4px 6px;">
                </label>
            </div>
            <div class="actions">
                <button class="fm-perg-drag" type="button" title="Arrastar"><i class="fa fa-arrows-alt"></i></button>
                <button class="fm-perg-dup" type="button" title="Duplicar"><i class="fa fa-copy"></i></button>
                <button class="fm-perg-del del" type="button" title="Excluir"><i class="fa fa-trash"></i></button>
            </div>
        </div>
    </div>
</div>
