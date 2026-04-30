<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php $idx = $m['id'] ?? uniqid(); ?>
<div class="membro-card">
    <select name="membros[<?php echo $idx; ?>][staff_id]" class="select2-staff" style="width:100%;">
        <option value="">Selecionar colaborador</option>
        <?php foreach ($staffs as $s): ?>
            <option value="<?php echo (int) $s['staffid']; ?>" <?php echo (int) ($m['staff_id'] ?? 0) === (int) $s['staffid'] ? 'selected' : ''; ?>>
                <?php echo html_escape($s['firstname'] . ' ' . $s['lastname']); ?>
            </option>
        <?php endforeach; ?>
    </select>
    <select name="membros[<?php echo $idx; ?>][papel]">
        <?php foreach (['membro' => 'Membro', 'lider' => 'Líder', 'observador' => 'Observador'] as $k => $v): ?>
            <option value="<?php echo $k; ?>" <?php echo ($m['papel'] ?? 'membro') === $k ? 'selected' : ''; ?>><?php echo $v; ?></option>
        <?php endforeach; ?>
    </select>
    <button type="button" class="remove js-remove"><i class="fa fa-trash"></i></button>
</div>
