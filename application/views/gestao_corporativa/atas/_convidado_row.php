<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php $idx = $c['id'] ?? uniqid(); ?>
<div class="convidado-card">
    <input type="text" name="convidados[<?php echo $idx; ?>][nome]" placeholder="Nome" required value="<?php echo html_escape($c['nome'] ?? ''); ?>">
    <input type="email" name="convidados[<?php echo $idx; ?>][email]" placeholder="E-mail" value="<?php echo html_escape($c['email'] ?? ''); ?>">
    <input type="text" name="convidados[<?php echo $idx; ?>][organizacao]" placeholder="Organização" value="<?php echo html_escape($c['organizacao'] ?? ''); ?>">
    <button type="button" class="remove js-remove"><i class="fa fa-trash"></i></button>
</div>
