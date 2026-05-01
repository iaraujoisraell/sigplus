<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php
$me_id = (int) get_staff_user_id();
$me_avatar = staff_profile_image_caminho($me_id);
$grupo_id = (int) $grupo['id'];
$is_owner_grupo = (int) $grupo['lider_id'] === $me_id || (int) $grupo['user_create'] === $me_id;
?>

<style>
    .gp-disc-compose{display:flex;gap:10px;align-items:flex-start;background:#f8fafc;border:1px solid #e5e7eb;border-radius:10px;padding:12px;margin-bottom:14px;}
    .gp-disc-compose img.avatar{width:36px;height:36px;border-radius:50%;flex-shrink:0;}
    .gp-disc-compose .compose-body{flex:1;}
    .gp-disc-compose textarea{width:100%;border:1px solid #d0d5dd;border-radius:8px;padding:8px 12px;font-size:13px;min-height:54px;resize:vertical;outline:none;}
    .gp-disc-compose textarea:focus{border-color:#0a66c2;}
    .gp-disc-compose .compose-actions{display:flex;justify-content:space-between;align-items:center;margin-top:8px;}
    .gp-disc-compose .tipo-radio label{font-size:12px;color:#475569;margin-right:10px;cursor:pointer;}
    .gp-disc-compose .btn-enviar{background:#0a66c2;color:#fff;border:0;padding:7px 16px;border-radius:999px;font-size:13px;font-weight:600;cursor:pointer;}
    .gp-disc-compose .btn-enviar:hover{background:#0858a8;}
    .gp-disc-compose .btn-enviar:disabled{opacity:.5;cursor:default;}

    .gp-disc-list{display:flex;flex-direction:column;gap:10px;}
    .gp-disc-post{background:#fff;border:1px solid #e5e7eb;border-radius:10px;padding:12px 14px;display:grid;grid-template-columns:42px 1fr;gap:10px;}
    .gp-disc-post.is-fixado{border-color:#fbbf24;background:#fffbeb;}
    .gp-disc-post.is-anotacao{background:#eff6ff;border-color:#bfdbfe;}
    .gp-disc-post.is-sistema{background:#f8fafc;color:#64748b;font-style:italic;font-size:12px;border-style:dashed;}
    .gp-disc-post .avatar{width:36px;height:36px;border-radius:50%;background:#dbeafe;color:#0a66c2;font-weight:700;display:flex;align-items:center;justify-content:center;font-size:13px;overflow:hidden;}
    .gp-disc-post .avatar img{width:100%;height:100%;object-fit:cover;}
    .gp-disc-post .post-head{display:flex;justify-content:space-between;align-items:center;gap:10px;margin-bottom:4px;}
    .gp-disc-post .post-author{font-size:13px;font-weight:600;color:#1f2937;}
    .gp-disc-post .post-meta{font-size:11px;color:#94a3b8;}
    .gp-disc-post .post-actions{display:flex;gap:8px;font-size:12px;}
    .gp-disc-post .post-actions a{color:#94a3b8;text-decoration:none;cursor:pointer;}
    .gp-disc-post .post-actions a:hover{color:#0a66c2;}
    .gp-disc-post .post-actions .pin-on{color:#d97706;}
    .gp-disc-post .post-conteudo{font-size:14px;color:#1f2937;line-height:1.55;white-space:pre-wrap;word-break:break-word;}
    .gp-disc-post .badge-anotacao{background:#3b82f6;color:#fff;font-size:10px;font-weight:700;padding:1px 7px;border-radius:6px;}
    .gp-disc-post .badge-fixado{background:#f59e0b;color:#fff;font-size:10px;font-weight:700;padding:1px 7px;border-radius:6px;}
    .gp-disc-empty{padding:40px;text-align:center;color:#94a3b8;font-size:13px;}
</style>

<div class="gp-disc-compose">
    <img src="<?php echo $me_avatar; ?>" alt="me" class="avatar" onerror="this.style.display='none'">
    <div class="compose-body">
        <textarea id="gp-post-conteudo" placeholder="Escreva uma mensagem ou anotação..."></textarea>
        <div class="compose-actions">
            <div class="tipo-radio">
                <label><input type="radio" name="gp-post-tipo" value="mensagem" checked> Mensagem</label>
                <label><input type="radio" name="gp-post-tipo" value="anotacao"> Anotação</label>
            </div>
            <button type="button" class="btn-enviar" id="gp-post-enviar">Publicar</button>
        </div>
    </div>
</div>

<div class="gp-disc-list" id="gp-disc-list" data-grupo-id="<?php echo $grupo_id; ?>" data-is-owner="<?php echo $is_owner_grupo ? 1 : 0; ?>">
    <?php if (empty($posts)): ?>
        <div class="gp-disc-empty">Sem mensagens ainda. Seja o primeiro a postar.</div>
    <?php else: ?>
        <?php foreach ($posts as $p): ?>
            <?php $this->load->view('gestao_corporativa/grupos/_post_row', ['p' => $p, 'me_id' => $me_id, 'is_owner_grupo' => $is_owner_grupo]); ?>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<script>
$(function () {
    var GRUPO_ID = <?php echo $grupo_id; ?>;

    $('#gp-post-enviar').on('click', function () {
        var conteudo = $('#gp-post-conteudo').val().trim();
        if (!conteudo) return;
        var tipo = $('input[name="gp-post-tipo"]:checked').val() || 'mensagem';

        var $btn = $(this).prop('disabled', true).text('Publicando...');
        $.post('<?php echo base_url('gestao_corporativa/Workgroup/add_post'); ?>/' + GRUPO_ID, {
            conteudo: conteudo,
            tipo: tipo
        }, function (resp) {
            if (resp && resp.ok) location.reload();
            else { alert('Falha ao publicar.'); $btn.prop('disabled', false).text('Publicar'); }
        }, 'json').fail(function () { alert('Erro de rede.'); $btn.prop('disabled', false).text('Publicar'); });
    });

    $(document).on('click', '.gp-post-delete', function (e) {
        e.preventDefault();
        if (!confirm('Excluir esta mensagem?')) return;
        var pid = $(this).data('id');
        $.post('<?php echo base_url('gestao_corporativa/Workgroup/delete_post'); ?>/' + pid, {}, function (resp) {
            if (resp && resp.ok) location.reload();
        }, 'json');
    });

    $(document).on('click', '.gp-post-fixar', function (e) {
        e.preventDefault();
        var pid = $(this).data('id');
        $.post('<?php echo base_url('gestao_corporativa/Workgroup/fixar_post'); ?>/' + pid, {}, function (resp) {
            if (resp && resp.ok) location.reload();
        }, 'json');
    });
});
</script>
