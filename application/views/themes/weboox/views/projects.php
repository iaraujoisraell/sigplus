<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--ver kt-grid--stretch">
    <div class="kt-container kt-body  kt-grid kt-grid--ver" id="kt_body">
		<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor">

                <!-- begin:: Subheader -->
    			<div class="kt-subheader   kt-grid__item" id="kt_subheader">
                    <div class="kt-subheader__main">
                        <h3 class="kt-subheader__title">
                            <?php echo _l('clients_my_projects'); ?>
                        </h3>
                    </h3>
                    <div class="kt-subheader__breadcrumbs">
                        <a href="<?php echo site_url('clients') ?>" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
                        <span class="kt-subheader__breadcrumbs-separator"></span>
                        <a href="<?php echo site_url('clients') ?>" class="kt-subheader__breadcrumbs-link">
                            Dashboard	                    </a>
                            <span class="kt-subheader__breadcrumbs-separator"></span>
                            <a href="<?php echo site_url('clients/projects') ?>" class="kt-subheader__breadcrumbs-link">
                                <?php echo _l('projects_summary'); ?>     </a>
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
                        <div class="row">


                            <?php get_template_part('projects/project_summary'); ?>

                            <hr />
                            <div class="col-md-12">

                            <table class="table dt-table table-projects" data-order-col="2" data-order-type="desc">
                                <thead>
                                    <tr>
                                        <th class="th-project-name"><?php echo _l('project_name'); ?></th>
                                        <th class="th-project-start-date"><?php echo _l('project_start_date'); ?></th>
                                        <th class="th-project-deadline"><?php echo _l('project_deadline'); ?></th>
                                        <th class="th-project-billing-type"><?php echo _l('project_billing_type'); ?></th>
                                        <?php
                                        $custom_fields = get_custom_fields('projects',array('show_on_client_portal'=>1));
                                        foreach($custom_fields as $field){ ?>
                                            <th><?php echo $field['name']; ?></th>
                                        <?php } ?>
                                        <th><?php echo _l('project_status'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($projects as $project){ ?>
                                        <tr>
                                            <td><a href="<?php echo site_url('clients/project/'.$project['id']); ?>"><?php echo $project['name']; ?></a></td>
                                            <td data-order="<?php echo $project['start_date']; ?>"><?php echo _d($project['start_date']); ?></td>
                                            <td data-order="<?php echo $project['deadline']; ?>"><?php echo _d($project['deadline']); ?></td>
                                            <td>
                                                <?php
                                                if($project['billing_type'] == 1){
                                                    $type_name = 'project_billing_type_fixed_cost';
                                                } else if($project['billing_type'] == 2){
                                                    $type_name = 'project_billing_type_project_hours';
                                                } else {
                                                    $type_name = 'project_billing_type_project_task_hours';
                                                }
                                                echo _l($type_name);
                                                ?>
                                            </td>
                                            <?php foreach($custom_fields as $field){ ?>
                                                <td><?php echo get_custom_field_value($project['id'],$field['id'],'projects'); ?></td>
                                            <?php } ?>
                                            <td>
                                                <?php
                                                $status = get_project_status_by_id($project['status']);
                                                echo '<span class="label inline-block" style="color:'.$status['color'].';border:1px solid '.$status['color'].'">'.$status['name'].'</span>';
                                                ?>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
