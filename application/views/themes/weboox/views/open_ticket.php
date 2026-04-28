<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php echo form_open_multipart('clients/open_ticket',array('id'=>'open-new-ticket-form')); ?>

<div class="col-md-12">

    <?php hooks()->do_action('before_client_open_ticket_form_start'); ?>

    <div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--ver kt-grid--stretch">
        <div class="kt-container kt-body  kt-grid kt-grid--ver" id="kt_body">
    		<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor">

                    <!-- begin:: Subheader -->
        			<div class="kt-subheader   kt-grid__item" id="kt_subheader">
                    <div class="kt-subheader__main">
                        <h3 class="kt-subheader__title">
                            <?php echo _l('clients_ticket_open_subject'); ?>
                        </h3>
                    </h3>
                    <div class="kt-subheader__breadcrumbs">
                        <a href="<?php site_url('clients') ?>" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
                        <span class="kt-subheader__breadcrumbs-separator"></span>
                        <a href="<?php site_url('clients') ?>" class="kt-subheader__breadcrumbs-link">
                            Dashboard	                    </a>
                            <span class="kt-subheader__breadcrumbs-separator"></span>
                            <a href="<?php site_url('clients/open_ticket') ?>" class="kt-subheader__breadcrumbs-link">
                                <?php echo _l('clients_ticket_open_subject'); ?>           </a>

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
                        <!--begin::Portlet-->
                        <div class="kt-portlet" id="kt_portlet">

                            <div class="kt-portlet__body"><?php echo validation_errors('<div class="alert alert-danger text-center">', '</div>'); ?>

                                <div class="form-group open-ticket-subject-group">
                                    <label for="subject"><?php echo _l('customer_ticket_subject'); ?></label>
                                    <input type="text" class="form-control" name="subject" id="subject" value="<?php echo set_value('subject'); ?>">
                                    <?php echo form_error('subject'); ?>
                                </div>
                                <?php if(total_rows(db_prefix().'projects',array('clientid'=>get_client_user_id())) > 0 && has_contact_permission('projects')){ ?>
                                    <div class="form-group open-ticket-project-group">
                                        <label for="project_id"><?php echo _l('project'); ?></label>
                                        <select data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>" name="project_id" id="project_id" class="form-control selectpicker">
                                            <option value=""></option>
                                            <?php foreach($projects as $project){ ?>
                                                <option value="<?php echo $project['id']; ?>" <?php echo set_select('project_id',$project['id']); ?><?php if($this->input->get('project_id') == $project['id']){echo ' selected';} ?>><?php echo $project['name']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                <?php } ?>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group open-ticket-department-group">
                                            <label for="department"><?php echo _l('clients_ticket_open_departments'); ?></label>
                                            <select data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>" name="department" id="department" class="form-control selectpicker">
                                                <option value=""></option>
                                                <?php foreach($departments as $department){ ?>
                                                    <option value="<?php echo $department['departmentid']; ?>" <?php echo set_select('department',$department['departmentid'],(count($departments) == 1 ? true : false)); ?>>
                                                        <?php echo $department['name']; ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                            <?php echo form_error('department'); ?>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group open-ticket-priority-group">
                                            <label for="priority"><?php echo _l('clients_ticket_open_priority'); ?></label>
                                            <select data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>" name="priority" id="priority" class="form-control selectpicker">
                                                <option value=""></option>
                                                <?php foreach($priorities as $priority){ ?>
                                                    <option value="<?php echo $priority['priorityid']; ?>" <?php echo set_select('priority', $priority['priorityid'], hooks()->apply_filters('new_ticket_priority_selected', 2) == $priority['priorityid']); ?>>
                                                        <?php echo ticket_priority_translate($priority['priorityid']); ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                            <?php echo form_error('priority'); ?>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                if(get_option('services') == 1 && count($services) > 0){ ?>
                                    <div class="form-group open-ticket-service-group">
                                        <label for="service"><?php echo _l('clients_ticket_open_service'); ?></label>
                                        <select data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>" name="service" id="service" class="form-control selectpicker">
                                            <option value=""></option>
                                            <?php foreach($services as $service){ ?>
                                                <option value="<?php echo $service['serviceid']; ?>" <?php echo set_select('service',$service['serviceid'],(count($services) == 1 ? true : false)); ?>><?php echo $service['name']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                <?php } ?>
                                <div class="custom-fields">
                                    <?php echo render_custom_fields('tickets','',array('show_on_client_portal'=>1)); ?>
                                </div>




                                <div class="form-group open-ticket-message-group">
                                    <label for=""><?php echo _l('clients_ticket_open_body'); ?></label>
                                    <textarea name="message" id="message" class="form-control" rows="15"><?php echo set_value('message'); ?></textarea>
                                </div>

                                <div class="panel-footer attachments_area open-ticket-attachments-area">
                                    <div class="attachments">
                                        <div class="attachment">
                                            <div class="form-group">
                                                <label for="attachment" class="control-label"><?php echo _l('clients_ticket_attachments'); ?></label>
                                                <div class="input-group">
                                                    <input type="file" extension="<?php echo str_replace('.','',get_option('ticket_attachments_file_extensions')); ?>" filesize="<?php echo file_upload_max_size(); ?>" class="form-control" name="attachments[0]" accept="<?php echo get_ticket_form_accepted_mimes(); ?>">
                                                    <span class="input-group-append">
                                                        <button class="btn btn-success add_more_attachments p8-half" data-max="<?php echo get_option('maximum_allowed_ticket_attachments'); ?>" type="button"><i class="fa fa-plus" style="color:white;"></i></button>
                                                    </span>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>


                                <div class="col-md-12 mtop20">
                                    <button type="submit" class="btn btn-info" data-form="#open-new-ticket-form" autocomplete="off" data-loading-text="<?php echo _l('wait_text'); ?>"><?php echo _l('submit'); ?></button>
                                </div>
                            </div>
                        </div>
                        <!--end::Portlet-->
                    </div>
                </div>
            </div>
</div>

        <?php echo form_close(); ?>
