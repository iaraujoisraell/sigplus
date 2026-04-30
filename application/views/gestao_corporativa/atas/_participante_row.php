<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php $idx = $p['id'] ?? uniqid(); $tipo = $p['tipo'] ?? 'interno'; ?>
<div class="participante-card">
    <select name="participantes[<?php echo $idx; ?>][tipo]" class="js-tipo">
        <option value="interno" <?php echo $tipo === 'interno' ? 'selected' : ''; ?>>Interno</option>
        <option value="externo" <?php echo $tipo === 'externo' ? 'selected' : ''; ?>>Externo</option>
    </select>
    <select name="participantes[<?php echo $idx; ?>][staff_id]" class="js-staff select2" <?php echo $tipo !== 'interno' ? 'style="display:none"' : ''; ?>>
        <option value="">Selecionar staff</option>
        <?php foreach ($staffs as $s): ?>
            <option value="<?php echo (int) $s['staffid']; ?>" <?php echo (int) ($p['staff_id'] ?? 0) === (int) $s['staffid'] ? 'selected' : ''; ?>><?php echo html_escape($s['firstname'] . ' ' . $s['lastname']); ?></option>
        <?php endforeach; ?>
    </select>
    <input type="text" name="participantes[<?php echo $idx; ?>][nome]" class="js-nome" placeholder="Nome (externo)" value="<?php echo html_escape($p['nome'] ?? ''); ?>" <?php echo $tipo !== 'externo' ? 'style="display:none"' : ''; ?>>
    <button type="button" class="remove js-remove"><i class="fa fa-trash"></i></button>
</div>
