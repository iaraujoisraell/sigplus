<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--ver kt-grid--stretch">
    <div class="kt-container kt-body  kt-grid kt-grid--ver" id="kt_body">
		<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor">

                <!-- begin:: Subheader -->
    			<div class="kt-subheader   kt-grid__item" id="kt_subheader">
                <div class="kt-subheader__main">
                    <h3 class="kt-subheader__title">
                        <?php echo _l('gdpr'); ?>
                    </h3>
                </h3>
                <div class="kt-subheader__breadcrumbs">
                    <a href="<?php echo site_url('clients') ?>" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
                    <span class="kt-subheader__breadcrumbs-separator"></span>
                    <a href="<?php echo site_url('clients') ?>" class="kt-subheader__breadcrumbs-link">
                        Dashboard	                    </a>
                        <span class="kt-subheader__breadcrumbs-separator"></span>
                        <a href="<?php echo site_url('clients/gdpr') ?>" class="kt-subheader__breadcrumbs-link">
                            <?php echo _l('gdpr'); ?>    </a>
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
                        <div class="panel_s">
                            <div class="panel-body">
                                <h4 class="no-margin"><?php echo _l('gdpr'); ?></h4>
                            </div>
                        </div>
                        <div class="panel_s">
                            <div class="panel-body">
                                <div class="col-md-12">
                                    <div class="mbot20">
                                        <?php echo get_option('gdpr_page_top_information_block'); ?>
                                    </div>
                                </div>
                                <?php if(is_gdpr() && get_option('gdpr_enable_terms_and_conditions') == '1'){ ?>
                                    <div class="col-md-4">
                                        <div class="gdpr-right">
                                            <h3 class="gdpr-right-heading"><?php echo _l('gdpr_right_to_be_informed'); ?></h3>
                                            <a href="<?php echo terms_url(); ?>" class="btn btn-info"><?php echo _l('terms_and_conditions'); ?></a>
                                        </div>
                                    </div>
                                <?php } ?>
                                <div class="col-md-4">
                                    <div class="gdpr-right">
                                        <h3 class="gdpr-right-heading"><?php echo _l('gdpr_right_of_access'); ?></h3>
                                        <a href="<?php echo site_url('clients/profile'); ?>" class="btn btn-info"><?php echo _l('edit_my_information'); ?></a>
                                    </div>
                                </div>
                                <?php if(is_gdpr() && get_option('gdpr_contact_enable_right_to_be_forgotten') == '1'){ ?>
                                    <div class="col-md-4">
                                        <div class="gdpr-right">
                                            <h3 class="gdpr-right-heading"><?php echo _l('gdpr_right_to_erasure'); ?></h3>
                                            <a href="#" data-toggle="modal" data-target="#dataRemoval" class="btn btn-info"><?php echo _l('request_data_removal'); ?></a>
                                        </div>
                                    </div>
                                <?php } ?>
                                <?php if(is_gdpr() && get_option('gdpr_data_portability_contacts') == '1') { ?>
                                    <div class="col-md-4">
                                        <div class="gdpr-right">
                                            <h3 class="gdpr-right-heading"><?php echo _l('gdpr_right_to_data_portability'); ?></h3>
                                            <a href="<?php echo site_url('clients/export'); ?>" class="btn btn-info"><?php echo _l('export_my_data'); ?></a>
                                        </div>
                                    </div>
                                <?php } ?>
                                <?php if(is_gdpr() && get_option('gdpr_enable_consent_for_contacts') == '1'){ ?>
                                    <div class="col-md-4">
                                        <div class="gdpr-right">
                                            <h3 class="gdpr-right-heading"><?php echo _l('gdpr_consent'); ?></h3>
                                            <a href="<?php echo contact_consent_url(get_contact_user_id()); ?>" class="btn btn-info"><?php echo _l('gdpr_consent'); ?></a>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                        <?php if(is_gdpr() && get_option('gdpr_contact_enable_right_to_be_forgotten') == '1'){ ?>
                            <div class="modal fade" tabindex="-1" role="dialog" id="dataRemoval">
                                <div class="modal-dialog" role="document">
                                    <?php echo form_open(); ?>
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                            <h4 class="modal-title"><?php echo _l('request_data_removal'); ?></h4>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <?php echo form_hidden('removal_request',true); ?>
                                                <label for="removal_description" class="control-label"><?php echo _l('explanation_for_data_removal'); ?></label>
                                                <textarea name="removal_description" id="removal_description" class="form-control" rows="4" placeholder="<?php echo _l('briefly_describe_why_remove_data'); ?>"></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                                            <button type="submit" class="btn btn-info _delete"><?php echo _l('confirm'); ?></button>
                                        </div>
                                    </div>
                                    <!-- /.modal-content -->
                                    <?php echo form_close(); ?>
                                </div>
                                <!-- /.modal-dialog -->
                            </div>
                            <!-- /.modal -->
                        <?php } ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
