<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--ver kt-grid--stretch">
    <div class="kt-container kt-body  kt-grid kt-grid--ver" id="kt_body">
		<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor">

                <!-- begin:: Subheader -->
    			<div class="kt-subheader   kt-grid__item" id="kt_subheader">
                <div class="kt-subheader__main">
        			<h3 class="kt-subheader__title">
        				<?php echo _l('knowledge_base'); ?>
        			</h3>
        		</h3>
        		<div class="kt-subheader__breadcrumbs">
        			<a href="<?php echo site_url('clients') ?>" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
        			<span class="kt-subheader__breadcrumbs-separator"></span>
        			<a href="<?php echo site_url('clients') ?>" class="kt-subheader__breadcrumbs-link">
        				Dashboard	                    </a>
        				<span class="kt-subheader__breadcrumbs-separator"></span>
        				<a href="<?php echo site_url('knowledge-base') ?>" class="kt-subheader__breadcrumbs-link">
        					<?php echo _l('knowledge_base'); ?>    </a>
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
					<div class="kt-portlet__body" style="padding-bottom:108px;"><?php echo validation_errors('<div class="alert alert-danger text-center">', '</div>'); ?>

						<div class="row">
							<div class="col-md-6 mx-auto">
								<?php echo form_open(site_url('knowledge-base/search'),array('method'=>'GET','id'=>'kb-search-form')); ?>
								<div class="input-group">
									<input type="text" name="q" class="form-control" placeholder="<?php echo _l('have_a_question'); ?>">
									<div class="input-group-append">
										<button class="btn btn-brand" type="submit"><?php echo _l('kb_search'); ?></button>
									</div>
								</div>
								<?php echo form_close(); ?>
							</div>
						</div>
						<div class="panel_s">
							<div class="panel-body">
								<?php if(count($articles) == 0){ ?>
									<p class="no-margin"><?php echo _l('clients_knowledge_base_articles_not_found'); ?></p>
								<?php } ?>
								<?php if(isset($category)){
									// Category articles list
									get_template_part('knowledge_base/category_articles_list', array('articles'=>$articles));
								}  else if(isset($search_results)) {
									// Search results
									get_template_part('knowledge_base/search_results', array('articles'=>$articles));
								} else {
									// Default page
									get_template_part('knowledge_base/categories', array('articles'=>$articles));
								}
								hooks()->do_action('after_kb_groups_customers_area');
								?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
