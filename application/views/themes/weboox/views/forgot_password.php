<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container ">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title">
                <?php echo _l('customer_forgot_password_heading'); ?>
            </h3>
        </h3>
        <div class="kt-subheader__breadcrumbs">
            <a href="<?php echo site_url('clients') ?>" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
            <span class="kt-subheader__breadcrumbs-separator"></span>
            <a href="<?php echo site_url('authentication/login') ?>" class="kt-subheader__breadcrumbs-link">
                Login	                    </a>
                <span class="kt-subheader__breadcrumbs-separator"></span>
                <a href="<?php echo site_url('clients/forgot_password') ?>" class="kt-subheader__breadcrumbs-link">
                    <?php echo _l('customer_forgot_password_heading'); ?>    </a>
                </div>
            </div>
        </div>
    </div>


    <div class="kt-container  kt-grid__item kt-grid__item--fluid" style="margin-bottom: 60px;">
        <div class="row">
            <div class="col-lg-12">
                <div class="kt-portlet" id="kt_portlet">
                    <div class="kt-portlet__body">

                        <div class="mtop40">
                            <div class="col-md-4 col-md-offset-4 text-center forgot-password-heading">
                                <h1 class="text-uppercase mbot20"><?php echo _l('customer_forgot_password_heading'); ?></h1>
                            </div>
                            <div class="col-md-4 col-md-offset-4">
                                <div class="panel_s">
                                    <div class="panel-body">
                                        <?php echo validation_errors('<div class="alert alert-danger text-center">', '</div>'); ?>
                                        <?php echo form_open($this->uri->uri_string(),['id'=>'forgot-password-form']); ?>

                                        <?php if($this->session->flashdata('message-danger')){ ?>
                                            <div class="alert alert-danger">
                                                <?php echo $this->session->flashdata('message-danger'); ?>
                                            </div>
                                        <?php } ?>
                                        <?php echo render_input('email','customer_forgot_password_email','','email'); ?>
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-info btn-block"><?php echo _l('customer_forgot_password_submit'); ?></button>
                                        </div>
                                        <?php echo form_close(); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
