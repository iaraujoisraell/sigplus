<?php
$current_user = $this->staff_model->get(get_staff_user_id());

$current_avatar = base_url('assets/images/user-placeholder.jpg');
if (!empty($current_user->profile_image)) {
    $current_avatar = base_url('uploads/staff_profile_images/' . get_staff_user_id() . '/small_' . $current_user->profile_image);
}
?>

<style>
    .feed-post-comment-form{
        display:flex;
        align-items:center;
        gap:10px;
        padding:8px 20px 18px;
        border-top:1px solid #edf1f5;
    }

    .feed-post-comment-avatar{
        width:34px;
        height:34px;
        border-radius:50%;
        object-fit:cover;
        flex-shrink:0;
        border:1px solid #e5e7eb;
    }

    .feed-post-comment-input-wrap{
        flex:1;
        display:flex;
        align-items:center;
        gap:8px;
        min-width:0;
    }

    .feed-post-comment-input{
        flex:1;
        height:40px;
        border:1px solid #d0d5dd;
        border-radius:999px;
        padding:0 14px;
        font-size:14px;
        outline:none;
        box-shadow:none;
    }

    .feed-post-comment-input:focus{
        border-color:#98a2b3;
    }

    .feed-post-comment-btn{
        height:40px;
        padding:0 16px;
        border:none;
        border-radius:999px;
        background:#0a66c2;
        color:#fff;
        font-size:14px;
        font-weight:600;
        cursor:pointer;
        transition:.18s ease;
    }

    .feed-post-comment-btn:hover{
        background:#0858a8;
    }

    @media (max-width: 768px){
        .feed-post-comment-form{
            padding:8px 14px 14px;
        }

        .feed-post-comment-btn{
            padding:0 14px;
            font-size:13px;
        }
    }
</style>

<div class="feed-post-comment-form">
    <img class="feed-post-comment-avatar" src="<?= $current_avatar ?>" alt="Usuário">

    <div class="feed-post-comment-input-wrap">
        <input
            type="text"
            class="feed-post-comment-input"
            id="comentario<?= $postid ?>"
            placeholder="Escreva um comentário..."
        >

        <button type="button" class="feed-post-comment-btn" onclick="refresh_coments(<?= $postid ?>);">
            Enviar
        </button>
    </div>
</div>