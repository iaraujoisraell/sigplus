<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php echo form_hidden('project_id',$project->id); ?>

<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--ver kt-grid--stretch">
    <div class="kt-container kt-body  kt-grid kt-grid--ver" id="kt_body">
		<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor">

                <!-- begin:: Subheader -->
    			<div class="kt-subheader   kt-grid__item" id="kt_subheader">
                <div class="kt-subheader__main">
        			<h3 class="kt-subheader__title">
        				<?php echo _l('project'); ?>
        			</h3>
        		</h3>
        		<div class="kt-subheader__breadcrumbs">
        			<a href="<?php echo site_url('clients') ?>" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
        			<span class="kt-subheader__breadcrumbs-separator"></span>
        			<a href="<?php echo site_url('clients/projects') ?>" class="kt-subheader__breadcrumbs-link">
        				<?php echo _l('clients_my_projects'); ?>                  </a>
        				<span class="kt-subheader__breadcrumbs-separator"></span>
        				<a href="<?php echo site_url('knowledge-base') ?>" class="kt-subheader__breadcrumbs-link">
        					<?php echo _l('project'); ?>    </a>
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
                        <div class="col-md-12">

                            <div class="panel_s">
                                <div class="panel-body">
                                    <h3 class="bold mtop10 project-name pull-left"><?php echo $project->name; ?>
                                        <span style="background-color: <?php echo $project_status['color']; ?>; color:white; font-size:16px;" class="badge badge-primary"><?php echo $project_status['name']; ?></span>
                                    </h3>
                                    <?php if($project->settings->view_tasks == 1 && $project->settings->create_tasks == 1){ ?>
                                        <a href="<?php echo site_url('clients/project/'.$project->id.'?group=new_task'); ?>" class="btn btn-info pull-right mtop5"><?php echo _l('new_task'); ?></a>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="panel_s">
                                <div class="panel-body">
                                    <?php get_template_part('projects/project_tabs'); ?>
                                    <div class="clearfix mtop15"></div>
                                    <?php get_template_part('projects/'.$group); ?>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
