<?php
$postid  = $post['postid'];
$creator = $post['creator'];

$member = $this->staff_model->get($creator);

$firstname = isset($member->firstname) ? $member->firstname : '';
$lastname  = isset($member->lastname) ? $member->lastname : '';
$name      = trim($firstname . ' ' . $lastname);

$avatar = base_url('assets/images/user-placeholder.jpg');
if (!empty($member->profile_image)) {
    $avatar = base_url('uploads/staff_profile_images/' . $creator . '/small_' . $member->profile_image);
}

/*
|----------------------------------------------------------
| Tenta extrair a primeira imagem do conteúdo
|----------------------------------------------------------
*/
$content_raw = isset($post['content']) ? $post['content'] : '';

$post_image = '';
if (preg_match('/<img[^>]+src=["\']([^"\']+)["\']/i', $content_raw, $matches)) {
    $post_image = $matches[1];
}

/*
|----------------------------------------------------------
| Remove a primeira imagem do conteúdo para não duplicar
|----------------------------------------------------------
*/
$content_text = preg_replace('/<p[^>]*>\s*<img[^>]+>\s*<\/p>/i', '', $content_raw, 1);
$content_text = preg_replace('/<img[^>]+>/i', '', $content_text, 1);

/*
|----------------------------------------------------------
| Cargo / subtítulo
|----------------------------------------------------------
*/
$post_role = '';
if (!empty($member->cargo)) {
    $post_role = $member->cargo;
}

/*
|----------------------------------------------------------
| Contadores
|----------------------------------------------------------
*/
$total_post_likes = total_rows(db_prefix() . 'newsfeed_post_likes', [
    'postid' => $postid,
]);

$total_comments = total_rows(db_prefix() . 'newsfeed_post_comments', [
    'postid' => $postid,
]);
?>

<style>
    .feed-post{
        background:#fff;
        border:1px solid #dde3ea;
        border-radius:16px;
        overflow:hidden;
        box-shadow:0 1px 2px rgba(16,24,40,.04);
        margin-bottom:18px;
    }

    .feed-post-header{
        display:flex;
        align-items:flex-start;
        justify-content:space-between;
        gap:14px;
        padding:18px 20px 10px;
    }

    .feed-post-user{
        display:flex;
        align-items:flex-start;
        gap:12px;
        min-width:0;
        flex:1;
    }

    .feed-post-avatar{
        width:52px;
        height:52px;
        border-radius:50%;
        object-fit:cover;
        border:1px solid #e5e7eb;
        background:#f8fafc;
        flex-shrink:0;
    }

    .feed-post-meta{
        min-width:0;
        flex:1;
    }

    .feed-post-name{
        font-size:15px;
        font-weight:700;
        color:#0f172a;
        line-height:1.25;
        margin-bottom:2px;
    }

    .feed-post-role{
        font-size:13px;
        color:#667085;
        line-height:1.35;
        margin-bottom:2px;
    }

    .feed-post-date{
        font-size:12px;
        color:#667085;
        line-height:1.2;
    }

    .feed-post-header-right{
        display:flex;
        align-items:center;
        gap:10px;
        color:#667085;
        flex-shrink:0;
        padding-top:2px;
    }

    .feed-post-header-btn{
        width:32px;
        height:32px;
        border:none;
        background:transparent;
        border-radius:50%;
        color:#667085;
        display:inline-flex;
        align-items:center;
        justify-content:center;
        cursor:pointer;
        text-decoration:none !important;
        transition:.18s ease;
    }

    .feed-post-header-btn:hover{
        background:#f2f4f7;
        color:#344054;
    }

    .feed-post-content{
        padding:0 20px 14px;
        color:#1f2937;
        font-size:15px;
        line-height:1.65;
        word-break:break-word;
    }

    .feed-post-content p{
        margin:0 0 12px 0;
    }

    .feed-post-content p:last-child{
        margin-bottom:0;
    }

    .feed-post-content img{
        max-width:100%;
        height:auto;
        border-radius:10px;
        display:block;
        margin:12px auto;
    }

    .feed-post-content a{
        color:#0a66c2;
        text-decoration:none;
    }

    .feed-post-content a:hover{
        text-decoration:underline;
    }

    .feed-post-media-wrap{
        padding:0 0 0;
    }

    .feed-post-image{
        display:block;
        width:100%;
        max-height:420px;
        object-fit:cover;
        background:#eef2f6;
        border-top:1px solid #edf1f5;
        border-bottom:1px solid #edf1f5;
    }

    .feed-post-stats{
        display:flex;
        align-items:center;
        justify-content:space-between;
        gap:10px;
        padding:10px 20px;
        font-size:13px;
        color:#667085;
        border-bottom:1px solid #edf1f5;
    }

    .feed-post-stats-left,
    .feed-post-stats-right{
        display:flex;
        align-items:center;
        gap:6px;
        flex-wrap:wrap;
    }

    .feed-post-reactions{
        display:inline-flex;
        align-items:center;
        gap:4px;
    }

    .feed-post-reaction-badge{
        width:18px;
        height:18px;
        border-radius:50%;
        display:inline-flex;
        align-items:center;
        justify-content:center;
        font-size:10px;
        color:#fff;
    }

    .feed-post-reaction-like{
        background:#0a66c2;
    }

    .feed-post-reaction-love{
        background:#e11d48;
    }

    .feed-post-body-divider{
        display:none;
    }

    @media (max-width: 768px){
        .feed-post{
            border-radius:14px;
        }

        .feed-post-header{
            padding:14px 14px 8px;
        }

        .feed-post-avatar{
            width:46px;
            height:46px;
        }

        .feed-post-content{
            padding:0 14px 12px;
            font-size:14px;
            line-height:1.6;
        }

        .feed-post-image{
            max-height:280px;
        }

        .feed-post-stats{
            padding:10px 14px;
            font-size:12px;
            flex-direction:column;
            align-items:flex-start;
        }
    }
</style>

<div class="ui-card feed-post">

    <div class="feed-post-header">
        <div class="feed-post-user">
            <img class="feed-post-avatar" src="<?= $avatar ?>" alt="<?= html_escape($name) ?>">

            <div class="feed-post-meta">
                <div class="feed-post-name"><?= html_escape($name) ?></div>

                <?php if (!empty($post_role)) { ?>
                    <div class="feed-post-role"><?= html_escape($post_role) ?></div>
                <?php } ?>

                <div class="feed-post-date">
                    Publicado em <?= _dt($post['datecreated']) ?>
                </div>
            </div>
        </div>

        <div class="feed-post-header-right">
            <button type="button" class="feed-post-header-btn" title="Mais opções">
                <i class="fa fa-ellipsis-h"></i>
            </button>

            <?php if ((has_permission_intranet('feed', '', 'delete') || is_admin()) && get_staff_user_id() == $creator) { ?>
                <a
                    href="<?= base_url('gestao_corporativa/feed/delete_post/' . $postid) ?>"
                    class="feed-post-header-btn"
                    title="Excluir post"
                    onclick="return confirm('Deseja realmente excluir esta publicação?');"
                >
                    <i class="fa fa-times"></i>
                </a>
            <?php } ?>
        </div>
    </div>

    <div class="feed-post-content">
        <?= $content_text; ?>
    </div>

    <?php if (!empty($post_image)) { ?>
        <div class="feed-post-media-wrap">
            <img class="feed-post-image" src="<?= $post_image ?>" alt="Imagem do post">
        </div>
    <?php } ?>

    <div class="feed-post-stats">
        <div class="feed-post-stats-left">
            <div class="feed-post-reactions">
                <span class="feed-post-reaction-badge feed-post-reaction-like">
                    <i class="fa fa-thumbs-up"></i>
                </span>
                <?php if ((int)$total_post_likes > 1) { ?>
                    <span class="feed-post-reaction-badge feed-post-reaction-love">
                        <i class="fa fa-heart"></i>
                    </span>
                <?php } ?>
            </div>

            <span><?= (int)$total_post_likes ?> curtida<?= ((int)$total_post_likes == 1 ? '' : 's') ?></span>
        </div>

        <div class="feed-post-stats-right">
            <span><?= (int)$total_comments ?> comentário<?= ((int)$total_comments == 1 ? '' : 's') ?></span>
        </div>
    </div>

    <?php $this->load->view('gestao_corporativa/intranet/feed/post_actions', ['post' => $post]); ?>

    <?php $this->load->view('gestao_corporativa/intranet/feed/post_comments', ['postid' => $postid]); ?>

    <?php $this->load->view('gestao_corporativa/intranet/feed/post_form', ['postid' => $postid]); ?>

</div>