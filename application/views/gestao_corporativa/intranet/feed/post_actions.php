<?php
$pid = (int) $post['postid'];
$liked = $this->newsfeed_model->user_liked_post($pid);
?>
<style>
    .feed-post-actions{
        display:flex;
        gap:4px;
        padding:4px 6px;
        border-top:1px solid #edf1f5;
    }
    .feed-action-btn{
        flex:1;
        height:42px;
        border:none;
        background:transparent;
        color:#56687a;
        font-size:14px;
        font-weight:600;
        border-radius:6px;
        display:inline-flex;
        align-items:center;
        justify-content:center;
        gap:8px;
        cursor:pointer;
        transition:.15s ease;
    }
    .feed-action-btn:hover{ background:#f3f6fa; color:#1d2226; }
    .feed-action-btn .fa{ font-size:17px; }
    .feed-action-btn.is-active{ color:#0a66c2; }
    .feed-action-btn.is-active .fa{ color:#0a66c2; }
</style>

<div class="feed-post-actions">
    <button type="button"
            class="feed-action-btn feed-action-like<?= $liked ? ' is-active' : '' ?>"
            id="like-btn-<?= $pid ?>"
            data-postid="<?= $pid ?>"
            data-liked="<?= $liked ? '1' : '0' ?>"
            onclick="toggle_like(this)">
        <i class="fa fa-thumbs-<?= $liked ? 'up' : 'o-up' ?>"></i>
        <span><?= $liked ? 'Curtido' : 'Curtir' ?></span>
    </button>

    <button type="button" class="feed-action-btn" onclick="focusComment(<?= $pid ?>)">
        <i class="fa fa-comment-o"></i>
        <span>Comentar</span>
    </button>
</div>

<script>
(function () {
    if (window.__feedActionsBound) return;
    window.__feedActionsBound = true;

    window.toggle_like = function (btn) {
        var id = btn.dataset.postid;
        var liked = btn.dataset.liked === '1';
        var url = '<?= base_url('gestao_corporativa/feed/') ?>' + (liked ? 'unlike_post/' : 'like_post/') + id;

        $.post(url).always(function () {
            btn.dataset.liked = liked ? '0' : '1';
            btn.classList.toggle('is-active', !liked);
            btn.querySelector('.fa').className = 'fa fa-thumbs-' + (liked ? 'o-up' : 'up');
            btn.querySelector('span').textContent = liked ? 'Curtir' : 'Curtido';
            $.post('<?= base_url('gestao_corporativa/feed/init_post_likes') ?>', { postid: id }, function (html) {
                $('#likes' + id).html(html);
            });
        });
    };

    window.focusComment = function (id) {
        var input = document.getElementById('comentario' + id);
        if (input) { input.focus(); input.scrollIntoView({ behavior: 'smooth', block: 'center' }); }
    };

    window.moderate_comment = function (commentId, postId, status) {
        if (status === 'rejected' && !confirm('Rejeitar este comentário?')) return;
        $.post('<?= base_url('gestao_corporativa/feed/moderate_comment') ?>/' + commentId + '/' + status)
            .done(function () {
                $.post('<?= base_url('gestao_corporativa/feed/init_post_comments') ?>/' + postId, { refresh_post_comments: 1 }, function (html) {
                    $('#trocar' + postId).html(html);
                });
            });
    };
})();
</script>
