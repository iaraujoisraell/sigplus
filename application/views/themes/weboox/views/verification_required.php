<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>


<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container ">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title">
                <?php echo _l('email_verification_required'); ?>
            </h3>
        </h3>
        <div class="kt-subheader__breadcrumbs">
            <a href="<?php echo site_url('clients') ?>" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
            <span class="kt-subheader__breadcrumbs-separator"></span>
            <a href="<?php echo site_url('clients') ?>" class="kt-subheader__breadcrumbs-link">
                Dashboard	                    </a>
                <span class="kt-subheader__breadcrumbs-separator"></span>
                <a href="<?php echo site_url('clients/projects') ?>" class="kt-subheader__breadcrumbs-link">
                    <?php echo _l('email_verification_required'); ?>    </a>
                </div>
            </div>
        </div>
    </div>


    <div class="kt-container  kt-grid__item kt-grid__item--fluid" style="margin-bottom: 60px;">
        <div class="row">
            <div class="col-lg-12">
                <div class="kt-portlet" id="kt_portlet">
                    <div class="kt-portlet__body">
                        <div class="row">
                            <div class="col-md-12">


                                    <div class="panel-body">

                                        <div class="alert alert-warning">
                                            <h4 class="verification-required-heading"><?php echo _l('email_verification_required_message'); ?></h4><br>

                                        </div>

                                        <b><p class="bold verification-required-message"><?php echo _l('email_verification_required_message_mail', site_url('verification/resend')); ?></p></b>

                                    </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
