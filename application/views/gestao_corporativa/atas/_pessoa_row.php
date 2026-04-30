<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php $idx = $p['id'] ?? uniqid(); ?>
<div class="pessoa-card">
    <select name="<?php echo html_escape($tipo); ?>[<?php echo $idx; ?>][staff_id]" class="select2-staff" style="width:100%;">
        <option value="">Selecionar colaborador</option>
        <?php foreach ($staffs as $s): ?>
            <option value="<?php echo (int) $s['staffid']; ?>" <?php echo (int) ($p['staff_id'] ?? 0) === (int) $s['staffid'] ? 'selected' : ''; ?>>
                <?php echo html_escape($s['firstname'] . ' ' . $s['lastname']); ?>
            </option>
        <?php endforeach; ?>
    </select>
    <button type="button" class="remove js-remove"><i class="fa fa-trash"></i></button>
</div>
