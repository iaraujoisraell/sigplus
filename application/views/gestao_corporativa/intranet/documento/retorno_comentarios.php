<?php foreach ($obss as $obs): ?>
                            <div class="post clearfix">
                                <div class="user-block">
                                    <?php echo staff_profile_image($current_user->staffid,array('img','img-responsive','staff-profile-image-small','pull-left')); ?>
                                    <span class="username">
                                        <a href="#"><?php echo $obs['firstname'] . ' ' . $obs['lastname']; ?></a>
                                    </span>
                                    <span class="description">Sent you a message - <?php echo strftime('%d-%m-%Y %H:%M:%S', strtotime($obs['data_created'])); ?></span>
                                </div>

                                <p>
                                    <?php echo $obs['obs']; ?>
                                </p>
                            </div>
                            <?php endforeach; ?>