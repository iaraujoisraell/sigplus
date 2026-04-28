<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div >
    <h4 class="customer-profile-group-heading"><?php echo 'Anúncios'; ?></h4>
    <div class="_buttons">
        <?php if (has_permission_intranet('announcements', '', 'create') || is_admin()) { ?>
            <a href="<?php echo admin_url('announcements/announcement'); ?>" class="btn btn-info pull-left display-block"><?php echo _l('new_announcement'); ?></a>
            <div class="clearfix"></div>
            <hr class="hr-panel-heading" />
            <?php
        }
        ?>
    </div>
    <div class="clearfix"></div>
    <?php render_datatable(array(_l('name'), _l('announcement_date_list')), 'announcements'); ?>

</div>
<?php init_tail(); ?>
</body>
</html>
