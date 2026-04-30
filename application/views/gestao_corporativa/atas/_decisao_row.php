<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php $idx = $d['id'] ?? uniqid(); ?>
<div class="item-card">
    <input type="hidden" name="decisoes[<?php echo $idx; ?>][id]" value="<?php echo (int) ($d['id'] ?? 0); ?>">
    <div>
        <textarea name="decisoes[<?php echo $idx; ?>][descricao]" rows="2" required><?php echo html_escape($d['descricao'] ?? ''); ?></textarea>
    </div>
    <select name="decisoes[<?php echo $idx; ?>][responsavel_id]" class="select2">
        <option value="">Responsável</option>
        <?php foreach ($staffs as $s): ?>
            <option value="<?php echo (int) $s['staffid']; ?>" <?php echo (int) ($d['responsavel_id'] ?? 0) === (int) $s['staffid'] ? 'selected' : ''; ?>><?php echo html_escape($s['firstname'] . ' ' . $s['lastname']); ?></option>
        <?php endforeach; ?>
    </select>
    <input type="date" name="decisoes[<?php echo $idx; ?>][prazo]" value="<?php echo html_escape($d['prazo'] ?? ''); ?>">
    <div style="display:flex;flex-direction:column;align-items:flex-end;gap:6px;">
        <?php if (!empty($d['task_id'])): ?>
            <span class="text-success" style="font-size:12px;"><i class="fa fa-check"></i> Task #<?php echo (int) $d['task_id']; ?></span>
        <?php else: ?>
            <label class="gerar-task"><input type="checkbox" name="decisoes[<?php echo $idx; ?>][gerar_task]" value="1"> gerar task</label>
        <?php endif; ?>
        <button type="button" class="remove js-remove"><i class="fa fa-trash"></i></button>
    </div>
</div>
