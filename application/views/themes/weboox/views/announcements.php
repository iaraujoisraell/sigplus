<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>


<<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--ver kt-grid--stretch">
    <div class="kt-container kt-body  kt-grid kt-grid--ver" id="kt_body">
		<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor">

                <!-- begin:: Subheader -->
    			<div class="kt-subheader   kt-grid__item" id="kt_subheader">
                <div class="kt-subheader__main">
                    <h3 class="kt-subheader__title">
                        <?php echo _l('announcements'); ?>
                    </h3>
                </h3>
                <div class="kt-subheader__breadcrumbs">
                    <a href="<?php echo site_url('clients') ?>" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
                    <span class="kt-subheader__breadcrumbs-separator"></span>
                    <a href="<?php echo site_url('clients') ?>" class="kt-subheader__breadcrumbs-link">
                        Dashboard	                    </a>
                        <span class="kt-subheader__breadcrumbs-separator"></span>
                        <a href="<?php echo site_url('clients/announcements') ?>" class="kt-subheader__breadcrumbs-link">
                            <?php echo _l('announcements'); ?>    </a>
                        </div>
                    </div>
    					<div class="kt-subheader__toolbar">
    						<div class="kt-subheader__wrapper">
    							<a href="<?php echo site_url('clients/tickets'); ?>" class="btn kt-subheader__btn-secondary">
    								<?php echo _l('support'); ?>
    							</a>
    							<div class="dropdown dropdown-inline" data-toggle="kt-tooltip" title="<?php echo _l('calendar'); ?>" data-placement="top">
    								<a href="<?php echo site_url('clients/calendar'); ?>" class="btn btn-danger kt-subheader__btn-options" aria-haspopup="true" aria-expanded="false">
    									<?php echo _l('calendar'); ?>
    								</a>
    								<div class="dropdown-menu dropdown-menu-right">
    									<a class="dropdown-item" href="#"><i class="la la-plus"></i> New Product</a>
    									<a class="dropdown-item" href="#"><i class="la la-user"></i> New Order</a>
    									<a class="dropdown-item" href="#"><i class="la la-cloud-download"></i> New Download</a>
    									<div class="dropdown-divider"></div>
    									<a class="dropdown-item" href="#"><i class="la la-cog"></i> Settings</a>
    								</div>
    							</div>
    						</div>
    					</div>
    				</div>

    				<!-- end:: Subheader -->

        </div>
    </div>
</div>

    <div class="kt-container  kt-grid__item kt-grid__item--fluid" style="margin-bottom: 60px;">
        <div class="row">
            <div class="col-lg-12">
                <div class="kt-portlet" id="kt_portlet">
                    <div class="kt-portlet__body"><?php echo validation_errors('<div class="alert alert-danger text-center">', '</div>'); ?>
                        <div class="row" >
                                <div class="panel-body">
                                    <?php if(count($announcements) > 0){ ?>
                                        <table class="table dt-table table-announcements" data-order-col="1" data-order-type="desc">
                                            <thead>
                                                <tr>
                                                    <th class="th-announcement-name"><?php echo _l('announcement_name'); ?></th>
                                                    <th class="th-announcement-date"><?php echo _l('announcement_date_list'); ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach($announcements as $announcement){ ?>
                                                    <tr>
                                                        <td><a href="<?php echo site_url('clients/announcement/'.$announcement['announcementid']); ?>"><?php echo $announcement['name']; ?></a></td>
                                                        <td data-order="<?php echo $announcement['dateadded']; ?>"><?php echo _d($announcement['dateadded']); ?></td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    <?php } else { ?>
                                        <h2 style="margin-bottom: 200px;"><?php echo _l('no_announcements'); ?></h2>
                                    <?php } ?>
                                </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
