

<style>
    .card-body p img{
        max-width: 100%;
    }
</style>
<?php
foreach ($posts as $post) {
    $postid = $post['postid'];
    $content = $post['content'];
    $creator = $post['creator'];
    $data_publicacao = $post['datecreated'];

    $member = $this->staff_model->get($creator);
    $profile_image = $member->profile_image;
    $firstname = $member->firstname;
    $lastname = $member->lastname;
    $name = $firstname . ' ' . $lastname;

    /*
     * <div class="card-comment">
      <?php echo $_comments; ?>
      <!-- User image -->
      <img class="img-circle img-sm" src="<?php echo base_url(); ?>assets/lte/dist/img/user3-128x128.jpg" alt="User Image">

      <div class="comment-text">
      <span class="username">
      Maria Gonzales
      <span class="text-muted float-right">8:03 PM Today</span>
      </span><!-- /.username -->
      It is a long established fact that a reader will be distracted
      by the readable content of a page when looking at its layout.
      </div>
      <!-- /.comment-text -->
      </div>
     */
    // COMENTARIOS
    $_comments = '';
    $total_comments = total_rows(db_prefix() . 'newsfeed_post_comments', [
        'postid' => $postid,
    ]);

    if ($total_comments > 0) {
        $page = $this->input->post('page');
        if (!$this->input->post('page')) {
            $_comments .= '<div class="panel-footer post-comment">';
        }
        $comments = $this->newsfeed_model->get_post_comments($postid, $page);
        $_comments = '';
        // Add +1 becuase the first page is already inited
        $total_comment_pages = ($total_comments / $this->newsfeed_model->post_comments_limit) + 1;
        foreach ($comments as $comment) {

            //$_comments .= $this->comment_single($comment);

            $_comments .= '<div class="comment" data-commentid="' . $comment['id'] . '">';

            // $_comments .= '<div class="">';
            $_comments .= '<a href="#">' . staff_profile_image($comment['userid'], [
                        'staff-profile-image-small',
                        'no-radius',
                    ]) . '</a>';
            // $_comments .= '</div>'; // end comment-image

            $_comments .= '<div class="comment-text">';
            $_comments .= '<span class="username">';
            $_comments .= '<span class="text-muted float-right">' . _dt($comment['dateadded']) . '</span>';
            $_comments .= '</span>';
            $_comments .= '<p class="no-margin comment-content">'
                    . ' <a href="#">' . get_staff_full_name($comment['userid']) . '</a> </p>';

            $_comments .= check_for_links($comment['content']);

            if ($comment['userid'] == get_staff_user_id() || is_admin()) {
                $_comments .= '<span class="text-muted float-right"><a href="#" class="remove-post-comment" onclick="remove_post_comment(' . $comment['id'] . ',' . $comment['postid'] . '); return false;"><i class="fa fa-trash bold"></i></span></a>';
            }

            $total_comment_likes = total_rows(db_prefix() . 'newsfeed_comment_likes', [
                'commentid' => $comment['id'],
                'postid' => $comment['postid'],
            ]);
            $total_pages = $total_comment_likes / $this->newsfeed_model->post_comments_limit;
            $likes_modal = '<a href="#" onclick="return false;" data-toggle="modal" data-target="#modal_post_comment_likes" data-commentid="' . $comment['id'] . '" data-total-pages="' . $total_pages . '">';
            $_comment_likes = '';
            if ($total_comment_likes > 0) {
                $_comment_likes = ' - ' . $likes_modal . $total_comment_likes . ' <i class="fa fa-thumbs-up"></i></a>';
            } else {
                $_comment_likes .= '</a>';
            }
            if (!$this->newsfeed_model->user_liked_comment($comment['id'])) {
                $_comments .= '<p class="no-margin"><a href="#" onclick="like_comment(' . $comment['id'] . ',' . $comment['postid'] . '); return false;"><small>' . _l('newsfeed_like_this_saying') . ' ' . $_comment_likes . '</small></p>';
            } else {
                $_comments .= '<p class="no-margin"><a href="#" onclick="unlike_comment(' . $comment['id'] . ',' . $comment['postid'] . '); return false;"><small>' . _l('newsfeed_unlike_this_saying') . ' ' . $_comment_likes . ' </small></p>';
            }
            $_comments .= '</div>';
            $_comments .= '</div>';
            // $_comments .= '</div>';
            $_comments .= '<div class="clearfix"></div>';
        }

        if ($total_comments > $this->newsfeed_model->post_comments_limit && !$this->input->post('page')) {
            $_comments .= '<a href="#" onclick="load_more_comments(this); return false" class="mtop10 load-more-comments display-block" data-postid="' . $postid . '" data-total-pages="' . $total_comment_pages . '"><input type="hidden" name="page" value="1">' . _l('newsfeed_show_more_comments') . '</a>';
        }
        if (!$this->input->post('page')) {
            //  $_comments .= '</div>'; // end comments footer
        }
    }
    ?>
    <div class="card">
        <div class="card card-widget">
            <div class="card-header">
                <div class="user-block">
                    <?php
                    $url = base_url('assets/images/user-placeholder.jpg');
                    if ($profile_image) {
                        $usuario_id = $creator;
                        $url_imagem = base_url() . 'uploads/staff_profile_images/' . $usuario_id . '/small_' . $profile_image;

                        $url = $url_imagem;
                    }
                    ?>
                    <img class="img-circle" src="<?php echo $url; ?>" alt="User Avatar">

                    <span class="username"><a href="#"><?php echo $name; ?></a></span>
                    <span class="description">Publicado em : <?php echo _dt($data_publicacao); ?></span>
                </div>
                <!-- /.user-block -->
                <div class="card-tools"> 
                    <!--
                  <button type="button" class="btn btn-tool" title="Mark as read">
                    <i class="far fa-circle"></i>
                  </button> -->
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                    <?php if (has_permission_intranet('feed', '', 'delete') || is_admin()) { ?>
                    <?php if (get_staff_user_id() == $creator) { ?>
                        <a href='<?php echo base_url('gestao_corporativa/feed/delete_post/' . $postid) ?>' type="button" class="btn btn-tool" >
                            <i class="fas fa-times"></i>
                        </a>
                    <?php } ?>
                     <?php } ?>
                </div>
                <!-- /.card-tools -->
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <?php echo $content; ?>
                <div id="likes<?php echo $postid; ?>">
                    <?php
                    // LIKES
                    $_likes = '';
                    $_likes .= '<div class="panel-footer user-post-like">';
                    if (!$this->newsfeed_model->user_liked_post($postid)) {
                        $_likes .= '<button type="button" class="btn btn-default btn-icon" onclick="refresh_likes(' . $postid . ');"> <i class="fa fa-heart"></i></button>';
                    } else {
                        $_likes .= '<button type="button" class="btn btn-danger btn-icon" onclick="refresh_dislikes(' . $postid . ');"> <i class="fa fa-heart"></i></button>';
                    }
                    $_likes .= '</div>';
                    if (total_rows(db_prefix() . 'newsfeed_post_likes', [
                                'postid' => $postid,
                            ])) {
                        $_likes .= '<div class="panel-footer post-likes">';
                        $total_post_likes = total_rows(db_prefix() . 'newsfeed_post_likes', [
                            'postid' => $postid,
                        ]);
                        $this->db->select();
                        $this->db->from(db_prefix() . 'newsfeed_post_likes');
                        $this->db->join(db_prefix() . 'staff', db_prefix() . 'staff.staffid = ' . db_prefix() . 'newsfeed_post_likes.userid', 'left');
                        $this->db->where('userid !=', get_staff_user_id());
                        $this->db->where('postid', $postid);
                        $this->db->order_by('dateliked', 'asc');
                        $likes = $this->db->get()->result_array();
                        $total_likes = count($likes);
                        $total_pages = $total_likes / $this->newsfeed_model->post_likes_limit;
                        $likes_modal = '<a href="#" onclick="return false;" data-toggle="modal" data-target="#modal_post_likes" data-postid="' . $postid . '" data-total-pages="' . $total_pages . '">';
                        if ($this->newsfeed_model->user_liked_post($postid) && $total_post_likes == 1) {
                            $_likes .= _l('newsfeed_you_like_this');
                        } elseif (($this->newsfeed_model->user_liked_post($postid) && $total_post_likes > 1) || ($this->newsfeed_model->user_liked_post($postid) && $total_post_likes >= 2)) {
                            if ($total_likes == 1) {
                                $_likes .= _l('newsfeed_you_and') . ' ' . $likes[0]['firstname'] . ' ' . $likes[0]['lastname'] . ' ' . _l('newsfeed_like_this');
                            } elseif ($total_likes == 2) {
                                $_likes .= _l('newsfeed_you') . ', ' . $likes[0]['firstname'] . ' ' . $likes[0]['lastname'] . ' and ' . $likes[1]['firstname'] . ' ' . $likes[1]['lastname'] . _l('newsfeed_like_this');
                            } else {
                                $_likes .= 'You, ' . $likes[0]['firstname'] . ' ' . $likes[0]['lastname'] . ', ' . $likes[1]['firstname'] . ' ' . $likes[1]['lastname'] . ' and ' . $likes_modal . ' ' . ($total_likes - 2) . ' ' . _l('newsfeed_one_other') . '</a> ' . _l('newsfeed_like_this');
                            }
                        } else {
                            $i = 1;
                            foreach ($likes as $like) {
                                if ($i > 3) {
                                    $_total_likes = ($total_likes - 3);
                                    if ($_total_likes != 0) {
                                        $_likes = substr($_likes, 0, -2);
                                        $_likes .= $likes_modal . ' ' . _l('newsfeed_and') . ' ' . $_total_likes . ' </a>' . _l('newsfeed_like_this');
                                    } else {
                                        $_likes = substr($_likes, 0, -2) . ' ' . _l('newsfeed_like_this');
                                    }

                                    break;
                                }
                                $_likes .= $like['firstname'] . ' ' . $like['lastname'] . ', ';

                                $i++;
                            }
                            if ($i < 4) {
                                $_likes = substr($_likes, 0, -2);
                                $_likes .= ' ' . _l('newsfeed_like_this');
                            }
                        }
                        $_likes .= '</div>'; // panel footer
                    }

                    echo $_likes;
                    ?>
                </div>
              <!--   <button type="button" class="btn btn-default btn-sm"><i class="fas fa-share"></i> Share</button> -->

            </div>

            <!-- /.card-body -->
            <?php if (has_permission_intranet('feed', '', 'view_coments') || is_admin()) { ?>
            <div class="card-footer card-comments" id="trocar<?php echo $postid; ?>">
                <div class="card-comment">
                    <?php echo $_comments; ?>
                    <!-- User image -->


                    <!-- /.comment-text -->
                </div>
            </div>
            <?php }?>

            <!-- /.card-footer -->
             <?php if (has_permission_intranet('feed', '', 'coment') || is_admin()) { ?>
            <div class="card-footer">
                <!-- <form action="#" method="post">-->
                <?php
                $profile_image = $current_user->profile_image;
                $url = base_url('assets/images/user-placeholder.jpg');
                if ($profile_image) {
                    ?>
                        <img class="img-fluid img-circle img-sm" style="min-width: 30px; min-height: 30px; max-height: 30px; max-width: 30px;"  src="<?php echo base_url(); ?>uploads/staff_profile_images/<?php echo $profile_image; ?>" alt="User Avatar">
                <?php } else { ?>
                        <img class="img-fluid img-circle img-sm" style="min-width: 30px; min-height: 30px; max-height: 30px; max-width: 30px;"  src="<?php echo $url; ?>" alt="User Avatar">
                <?php } ?>

                <!-- .img-push is used to add margin to elements next to floating images -->

                <div class="img-push">
                    <div class="input-group input-group-sm mb-0">
                        <input class="form-control form-control-sm" placeholder="Digite aqui.." name="comentario"  id="comentario<?php echo $postid; ?>">
                        <div class="input-group-append">
                            <button type="button" onclick="refresh_coments(<?php echo $postid; ?>);" class="btn btn-primary">Enviar</button>
                        </div>
                    </div>
                </div><!-- comment -->


                <!-- </form>-->
            </div>
            <?php }?>
            <!-- /.card-footer -->
        </div>
    </div>    
    <hr>
    <br>
<?php } ?>

<!-- Likes -->



<script>
    function refresh_coments(id) {
        //alert('cdjnn'); exit;
        var input = document.querySelector('#comentario' + id);
        var texto = input.value;
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('gestao_corporativa/feed/add_comment'); ?>",
            data: {
                postid: id,
                texto: texto,
                page: '<?php echo $page; ?>'
            },
            success: function (data) {
                $('#trocar' + id).html(data);
            }
        });
        document.getElementById('comentario').value = ''; // Limpa o campo
    }


    function refresh_likes(id) {

        like_post(id);
        //alert('cdjnn'); exit;
        var id = id;
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('gestao_corporativa/feed/init_post_likes'); ?>",
            data: {
                postid: id
            },
            success: function (data) {
                $('#likes' + id).html(data);
            }
        });

    }

    function refresh_dislikes(id) {

        unlike_post(id);
        //alert('cdjnn'); exit;
        var id = id;
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('gestao_corporativa/feed/init_post_likes'); ?>",
            data: {
                postid: id
            },
            success: function (data) {
                $('#likes' + id).html(data);
            }
        });

    }
</script>

<!--<div class="img-push">

                    <input type="text" class="form-control form-control-sm" name="coment_post_<?php echo $postid; ?>  " id="coment_post_<?php echo $postid; ?>" placeholder="Press enter to post comment">
                    <button type="submit">publicar</button>
                </div><!-- comment -->
