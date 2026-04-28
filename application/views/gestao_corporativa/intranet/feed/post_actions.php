<div class="feed-post-actions">
    <button onclick="refresh_likes(<?= $post['postid']; ?>)">
        👍 Curtir
    </button>

    <button onclick="focusComment(<?= $post['postid']; ?>)">
        💬 Comentar
    </button>

    <button>🔁 Compartilhar</button>
    <button>✉️ Enviar</button>
</div>