<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--ver kt-grid--stretch">
	<div class="kt-container kt-body  kt-grid kt-grid--ver" id="kt_body">
		<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor">

			<!-- begin:: Subheader -->
			<div class="kt-subheader   kt-grid__item" id="kt_subheader">
				<div class="kt-subheader__main">
					<h3 class="kt-subheader__title" id="greeting"></h3>
					<div class="kt-subheader__breadcrumbs">
						<a href="#" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
						<span class="kt-subheader__breadcrumbs-separator"></span>
						<a href="" class="kt-subheader__breadcrumbs-link">
							Dashboard </a>
							<!-- <span class="kt-subheader__breadcrumbs-separator"></span> -->
							<!-- <a href="" class="kt-subheader__breadcrumbs-link">
							Default Dashboard </a> -->
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

				<div class="kt-container  kt-grid__item kt-grid__item--fluid" style="margin-bottom: 60px;">
					<div class="row">
						<div class="" style="width:100%;">
							<div class="kt-portlet" id="kt_portlet" style="padding-bottom:30px;">
								<div class="kt-portlet__body"><?php echo validation_errors('<div class="alert alert-danger text-center">', '</div>'); ?>
								<h4  style="color:#84c529;" class="projects-summary-heading no-mtop mbot15"><?php echo _l('projects_summary'); ?></h4>
								<br>
								<div class="row">
									<?php get_template_part('projects/project_summary'); ?>
								</div>

							</div>

						</div>
					</div>
				</div>
			</div>


			<div class="kt-container  kt-grid__item kt-grid__item--fluid" style="margin-bottom: 60px;">
				<div class="row">
					<div class="" style="width:100%">
						<div class="kt-portlet" id="kt_portlet" style="padding-bottom:30px;">
							<div class="kt-portlet__body"><?php echo validation_errors('<div class="alert alert-danger text-center">', '</div>'); ?>
							<h4 class="bold"><?php echo _l('clients_quick_invoice_info'); ?></h4>
							<a href="<?php echo site_url('clients/statement'); ?>"><?php echo _l('view_account_statement'); ?></a>
							<div class="row">

								<?php
								if(has_contact_permission('invoices')){ ?>
									<div class="panel-body" style="width:100%;">


										<hr />
										<?php get_template_part('invoices_stats'); ?>

										<div class="col-md-3 col-sm-12">

											<?php if(count($payments_years) > 0){ ?>
												<div class="form-group">
													<select data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>" class="form-control" id="payments_year" name="payments_years" data-width="100%" onchange="total_income_bar_report();" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
														<?php foreach($payments_years as $year) { ?>
															<option value="<?php echo $year['year']; ?>"<?php if($year['year'] == date('Y')){echo 'selected';} ?>>
																<?php echo $year['year']; ?>
															</option>
														<?php } ?>
													</select>
												</div>
											<?php } ?>
											<?php if(is_client_using_multiple_currencies()){ ?>
												<div id="currency" class="form-group mtop15" data-toggle="tooltip" title="<?php echo _l('clients_home_currency_select_tooltip'); ?>">
													<select data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>" class="form-control" name="currency">
														<?php foreach($currencies as $currency){
															$selected = '';
															if($currency['isdefault'] == 1){
																$selected = 'selected';
															}
															?>
															<option value="<?php echo $currency['id']; ?>" <?php echo $selected; ?>><?php echo $currency['symbol']; ?> - <?php echo $currency['name']; ?></option>
														<?php } ?>
													</select>
												</div>
											<?php } ?>

										</div>


										<div class="col-md-12">
											<div class="relative" style="max-height:400px;">
												<canvas id="client-home-chart" height="400" class="animated fadeIn"></canvas>
											</div>
										</div>

									<?php } ?>
								</div>
							</div>

						</div>

					</div>
				</div>
			</div>
		</div>




		<div class="kt-container  kt-grid__item kt-grid__item--fluid" style="margin-bottom: 60px;">
			<div class="row">
				<div class="" style="width:100%;">
					<div class="kt-portlet" id="kt_portlet">
						<div class="kt-portlet__body"><?php echo validation_errors('<div class="alert alert-danger text-center">', '</div>'); ?>

						<?php echo get_template_part('tickets'); ?>
					</div>

				</div>
			</div>
		</div>
	</div>






</div>





<!--End::Dashboard 4-->
</div>

<!-- end:: Content -->
</div>
</div>
</div>

<script>
var greetDate = new Date();
var hrsGreet = greetDate.getHours();

var greet;
if (hrsGreet < 12)
greet = "<?php echo _l('good_morning'); ?>,";
else if (hrsGreet >= 12 && hrsGreet <= 17)
greet = "<?php echo _l('good_afternoon'); ?>,";
else if (hrsGreet >= 17 && hrsGreet <= 24)
greet = "<?php echo _l('good_evening'); ?>,";

if(greet) {
	document.getElementById('greeting').innerHTML =
	'<b>' + greet + ' <?php echo $contact->firstname; ?>!</b>';
}
</script>
