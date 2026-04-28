<?php
$comments = $this->newsfeed_model->get_post_comments($postid);
?>

<style>
    .feed-post-comments{
        padding:4px 20px 4px;
        border-top:1px solid #edf1f5;
    }

    .feed-comment-item{
        display:flex;
        gap:10px;
        margin-bottom:12px;
    }

    .feed-comment-item:last-child{
        margin-bottom:0;
    }

    .feed-comment-avatar img,
    .feed-comment-avatar .staff-profile-image-small{
        width:32px !important;
        height:32px !important;
        border-radius:50% !important;
        object-fit:cover;
    }

    .feed-comment-body{
        flex:1;
        min-width:0;
    }

    .feed-comment-bubble{
        background:#f2f4f7;
        border-radius:14px;
        padding:10px 12px;
    }

    .feed-comment-author{
        font-size:13px;
        font-weight:700;
        color:#111827;
        margin-bottom:2px;
    }

    .feed-comment-text{
        font-size:13px;
        color:#344054;
        line-height:1.45;
    }

    .feed-comment-meta{
        margin-top:4px;
        font-size:12px;
        color:#667085;
    }

    @media (max-width: 768px){
        .feed-post-comments{
            padding:4px 14px;
        }
    }
</style>

<?php if (!empty($comments)) { ?>
    <div class="feed-post-comments" id="trocar<?= $postid ?>">
        <?php foreach ($comments as $comment) { ?>
            <div class="feed-comment-item">
                <div class="feed-comment-avatar">
                    <?= staff_profile_image($comment['userid'], ['staff-profile-image-small']) ?>
                </div>

                <div class="feed-comment-body">
                    <div class="feed-comment-bubble">
                        <div class="feed-comment-author">
                            <?= html_escape(get_staff_full_name($comment['userid'])) ?>
                        </div>

                        <div class="feed-comment-text">
                            <?= check_for_links($comment['content']) ?>
                        </div>
                    </div>

                    <div class="feed-comment-meta">
                        <?= _dt($comment['dateadded']) ?>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
<?php } else { ?>
    <div class="feed-post-comments" id="trocar<?= $postid ?>"></div>
<?php } ?>