<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php
$is_sistema = $p['tipo'] === 'sistema';
$is_anotacao = $p['tipo'] === 'anotacao';
$is_fixado  = !empty($p['fixado']);

$classes = ['gp-disc-post'];
if ($is_sistema)  $classes[] = 'is-sistema';
if ($is_anotacao) $classes[] = 'is-anotacao';
if ($is_fixado)   $classes[] = 'is-fixado';

$autor = trim((string) ($p['autor_nome'] ?? '—'));
$iniciais = '';
foreach (preg_split('/\s+/', $autor) as $part) {
    if ($part !== '' && $part !== '—') $iniciais .= mb_strtoupper(mb_substr($part, 0, 1));
    if (mb_strlen($iniciais) >= 2) break;
}
if ($iniciais === '') $iniciais = '?';

$pode_excluir = (int) $p['autor_id'] === (int) $me_id || $is_owner_grupo;
?>
<div class="<?php echo implode(' ', $classes); ?>">
    <div class="avatar"><?php echo $iniciais; ?></div>
    <div>
        <div class="post-head">
            <div>
                <span class="post-author"><?php echo html_escape($autor); ?></span>
                <?php if ($is_anotacao): ?> <span class="badge-anotacao">ANOTAÇÃO</span><?php endif; ?>
                <?php if ($is_fixado): ?> <span class="badge-fixado"><i class="fa fa-thumbtack"></i> FIXADO</span><?php endif; ?>
                <span class="post-meta">· <?php echo date('d/m/Y H:i', strtotime($p['dt_created'])); ?></span>
            </div>
            <?php if (!$is_sistema): ?>
                <div class="post-actions">
                    <?php if ($is_owner_grupo): ?>
                        <a href="#" class="gp-post-fixar <?php echo $is_fixado ? 'pin-on' : ''; ?>" data-id="<?php echo (int) $p['id']; ?>" title="<?php echo $is_fixado ? 'Desafixar' : 'Fixar'; ?>">
                            <i class="fa fa-thumbtack"></i>
                        </a>
                    <?php endif; ?>
                    <?php if ($pode_excluir): ?>
                        <a href="#" class="gp-post-delete" data-id="<?php echo (int) $p['id']; ?>" title="Excluir">
                            <i class="fa fa-trash"></i>
                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
        <div class="post-conteudo"><?php echo $is_sistema ? $p['conteudo'] : nl2br(html_escape($p['conteudo'])); ?></div>
    </div>
</div>
