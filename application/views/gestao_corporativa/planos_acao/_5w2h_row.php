<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php $idx = $w['id'] ?? uniqid(); ?>
<div class="item-5w2h">
    <input type="hidden" name="itens_5w2h[<?php echo $idx; ?>][id]" value="<?php echo (int) ($w['id'] ?? 0); ?>">
    <div class="row1">
        <div><label>What — O que</label><textarea name="itens_5w2h[<?php echo $idx; ?>][what]" rows="2" required><?php echo html_escape($w['what'] ?? ''); ?></textarea></div>
        <div><label>Why — Por quê</label><textarea name="itens_5w2h[<?php echo $idx; ?>][why]" rows="2"><?php echo html_escape($w['why'] ?? ''); ?></textarea></div>
    </div>
    <div class="row2">
        <div><label>Where — Onde</label><input type="text" name="itens_5w2h[<?php echo $idx; ?>][where]" value="<?php echo html_escape($w['where'] ?? ''); ?>"></div>
        <div><label>When — Quando</label><input type="date" name="itens_5w2h[<?php echo $idx; ?>][when]" value="<?php echo html_escape($w['when'] ?? ''); ?>"></div>
        <div><label>Who — Quem</label>
            <select name="itens_5w2h[<?php echo $idx; ?>][who_id]" class="select2">
                <option value="">—</option>
                <?php foreach ($staffs as $s): ?>
                    <option value="<?php echo (int) $s['staffid']; ?>" <?php echo (int) ($w['who_id'] ?? 0) === (int) $s['staffid'] ? 'selected' : ''; ?>><?php echo html_escape($s['firstname'] . ' ' . $s['lastname']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="row3">
        <div><label>How — Como</label><textarea name="itens_5w2h[<?php echo $idx; ?>][how]" rows="2"><?php echo html_escape($w['how'] ?? ''); ?></textarea></div>
        <div><label>How much (R$)</label><input type="text" name="itens_5w2h[<?php echo $idx; ?>][how_much]" value="<?php echo html_escape($w['how_much'] ?? ''); ?>"></div>
        <div><label>Status</label>
            <select name="itens_5w2h[<?php echo $idx; ?>][status]">
                <?php foreach (['aberto'=>'Aberto', 'em_andamento'=>'Em andamento', 'concluido'=>'Concluído', 'cancelado'=>'Cancelado'] as $sk => $sv): ?>
                    <option value="<?php echo $sk; ?>" <?php echo ($w['status'] ?? 'aberto') === $sk ? 'selected' : ''; ?>><?php echo $sv; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div style="display:flex;align-items:center;justify-content:flex-end;">
            <button type="button" class="remove js-remove"><i class="fa fa-trash"></i></button>
        </div>
    </div>
    <div class="footer-row">
        <?php if (!empty($w['task_id'])): ?>
            <span class="task-link"><i class="fa fa-check"></i> Task #<?php echo (int) $w['task_id']; ?> vinculada</span>
        <?php else: ?>
            <label class="gerar"><input type="checkbox" name="itens_5w2h[<?php echo $idx; ?>][gerar_task]" value="1"> gerar task ao salvar</label>
        <?php endif; ?>
    </div>
</div>
