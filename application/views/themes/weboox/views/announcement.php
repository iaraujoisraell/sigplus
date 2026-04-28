<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>


<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--ver kt-grid--stretch">
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
                    <div class="kt-portlet__body" style="padding-bottom: 108px;"><?php echo validation_errors('<div class="alert alert-danger text-center">', '</div>'); ?>
                    <div class="row" style="margin-bottom: 60px;">
                        <div class="panel_s section-heading section-announcement">
                            <div class="panel-body">
                                <h4 class="bold no-margin announcement-heading section-text"><?php echo $announcement->name; ?></h4>
                                <div class="mtop5 announcement-date"><?php echo _l('announcement_date',_d($announcement->dateadded)); ?></div>
                                <?php if($announcement->showname == 1){ echo _l('announcement_from') . ' ' . $announcement->userid; } ?>
                            </div>
                        </div>
                        <div class="panel_s">
                            <div class="panel-body tc-content announcement-content">
                                <?php echo $announcement->message; ?>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
