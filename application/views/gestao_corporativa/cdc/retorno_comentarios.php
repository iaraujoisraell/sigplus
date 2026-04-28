<?php foreach ($obss as $obs): ?>
                                
                                <article class="msg-container <?php if($obs['user_created'] == get_staff_user_id()){ echo 'msg-self'; } else { echo 'msg-remote';}?>" id="msg-0">
                                    <div class="msg-box">
                                        <?php if($obs['user_created'] != get_staff_user_id()){?>
                                         <?php echo staff_profile_image($current_user->staffid, array('img', 'img-responsive', 'staff-profile-image-small', 'pull-left')); ?>
                                        <?php }?>
                                        <div class="flr">
                                            <div class="messages">
                                                <p class="msg" id="msg-1">
                                                    <?php echo $obs['obs']; ?>
                                                </p>
                                                
                                            </div>
                                            <span class="timestamp"><span class="username"><?php echo get_staff_full_name($obs['user_created']); ?></span>&bull;<span class="posttime"><?php echo _d($obs['data_created']); ?></span></span>
                                        </div>
                                        <?php //echo staff_profile_image($current_user->staffid, array('img', 'img-responsive', 'staff-profile-image-small', 'pull-left')); ?>
                                        <?php if($obs['user_created'] == get_staff_user_id()){?>
                                         <?php echo staff_profile_image($current_user->staffid, array('img', 'img-responsive', 'staff-profile-image-small', 'pull-left')); ?>
                                        <?php }?>
                                    </div>
                                </article>
                                <?php endforeach; ?>