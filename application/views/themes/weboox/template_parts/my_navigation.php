<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
					<style>
					.kt-notification .kt-notification__item:after {
						display:none !important;
					}
					</style>
					<?php if(is_client_logged_in()) { ?>
					<!-- begin:: Header Mobile -->
					<div id="kt_header_mobile" class="kt-header-mobile  kt-header-mobile--fixed ">
						<div class="kt-header-mobile__logo">
							<a href="<?php echo site_url('clients') ?>">
								<img alt="Logo" src="<?php echo base_url('uploads/company/logo.png'); ?>" />
							</a>
						</div>
						<div class="kt-header-mobile__toolbar">
							<button class="kt-header-mobile__toolbar-toggler" id="kt_header_mobile_toggler"><span></span></button>
							<button class="kt-header-mobile__toolbar-topbar-toggler" id="kt_header_mobile_topbar_toggler"><i class="flaticon-more-1"></i></button>
						</div>
					</div>
					<!-- end:: Header Mobile -->
					<!-- begin:: Header -->
					<div class="kt-grid kt-grid--hor kt-grid--root">
						<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--ver kt-page">
							<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor kt-wrapper" id="kt_wrapper">
								<div id="kt_header" class="kt-header  kt-header--fixed " data-ktheader-minimize="on">
									<div class="kt-container">
										<!-- begin:: Brand -->
										<div class="kt-header__brand   kt-grid__item" id="kt_header_brand">
											<a class="kt-header__brand-logo" href="<?php echo site_url('clients') ?>">
												<img id="logo-branco" src="<?php echo base_url('uploads/company/logo.png')?>" alt="Logo" class="logo-default" />
												<img id="logo-default" src="<?php echo base_url('uploads/company/logo_dark.png')?>" alt="Logo" class="logo-sticky" />
											</a>
										</div>									
										<!-- end:: Brand -->
										<!-- begin: Header Menu -->
										<button class="kt-header-menu-wrapper-close" id="kt_header_menu_mobile_close_btn"><i class="la la-close"></i></button>
										<div class="kt-header-menu-wrapper kt-grid__item kt-grid__item--fluid" id="kt_header_menu_wrapper">
											<div class="menu-titulo"><h3>Dashboard</h3></div>
											<div id="kt_header_menu" class="kt-header-menu kt-header-menu-mobile ">
												<ul class="kt-menu__nav ">
													<?php if((get_option('use_knowledge_base') == 1 )) { ?>
														<li class="kt-menu__item  kt-menu__item--submenu kt-menu__item--rel <?php if($this->uri->segment(2)=="knowledge-base"){echo "kt-menu__item--open ";}?><?php if($this->uri->segment(2)=="knowledge-base"){echo "kt-menu__item--open ";}?> " data-ktmenu-submenu-toggle="click" aria-haspopup="true">
															<a href="<?php echo site_url('knowledge-base'); ?>" class="<?php if($this->uri->segment(2)=="knowledge-base"){echo "kt-menu__item--open ";}?><?php if($this->uri->segment(2)=="knowledge-base"){echo "kt-menu__item--open";}?> kt-menu__link"><span class="kt-menu__link-text"><?php echo _l('clients_knowledge_base'); ?></span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
														</li>
													<?php } ?>
													<?php if(has_contact_permission('projects')){ ?>
														<li class="kt-menu__item  kt-menu__item--submenu kt-menu__item--rel <?php if($this->uri->segment(2)=="projects"){echo "kt-menu__item--open ";}?><?php if($this->uri->segment(2)=="projects"){echo "kt-menu__item--open ";}?> " data-ktmenu-submenu-toggle="click" aria-haspopup="true">
															<a href="<?php echo site_url('clients/projects'); ?>" class="<?php if($this->uri->segment(2)=="projects"){echo "kt-menu__item--open ";}?><?php if($this->uri->segment(2)=="projects"){echo "kt-menu__item--open";}?> kt-menu__link "><span class="kt-menu__link-text"><?php echo _l('projects'); ?></span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
														</li>
													<?php } ?>
													<li class="kt-menu__item  kt-menu__item--submenu kt-menu__item--rel <?php if($this->uri->segment(2)=="invoices"){echo "kt-menu__item--open ";}?><?php if($this->uri->segment(2)=="invoices"){echo "kt-menu__item--open ";}?>" data-ktmenu-submenu-toggle="click" aria-haspopup="true">
														<?php if(has_contact_permission('invoices')){ ?>
															<a href="<?php echo site_url('clients/invoices'); ?>" class="<?php if($this->uri->segment(2)=="invoices"){echo "kt-menu__item--open ";}?><?php if($this->uri->segment(2)=="invoices"){echo "kt-menu__item--open ";}?> kt-menu__link "><span class="kt-menu__link-text"><?php echo _l('invoices'); ?></span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
														<?php } ?>
													</li>
													<?php if(has_contact_permission('contracts')){ ?>
														<li class="kt-menu__item  kt-menu__item--submenu kt-menu__item--rel <?php if($this->uri->segment(2)=="contracts"){echo "kt-menu__item--open ";}?><?php if($this->uri->segment(2)=="contracts"){echo "kt-menu__item--open ";}?> " data-ktmenu-submenu-toggle="click" aria-haspopup="true">
															<a href="<?php echo site_url('clients/contracts'); ?>" class="<?php if($this->uri->segment(2)=="contracts"){echo "kt-menu__item--open ";}?><?php if($this->uri->segment(2)=="contracts"){echo "kt-menu__item--open";}?> kt-menu__link "><span class="kt-menu__link-text"><?php echo _l('contracts'); ?></span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
														</li>
													<?php } ?>
													<?php if(has_contact_permission('estimates')){ ?>
														<li class="kt-menu__item  kt-menu__item--submenu kt-menu__item--rel <?php if($this->uri->segment(2)=="estimates"){echo "kt-menu__item--open ";}?><?php if($this->uri->segment(2)=="estimates"){echo "kt-menu__item--open ";}?> " data-ktmenu-submenu-toggle="click" aria-haspopup="true">
															<a href="<?php echo site_url('clients/estimates'); ?>" class="<?php if($this->uri->segment(2)=="estimates"){echo "kt-menu__item--open ";}?><?php if($this->uri->segment(2)=="estimates"){echo "kt-menu__item--open";}?> kt-menu__link "><span class="kt-menu__link-text"><?php echo _l('estimates'); ?></span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
														</li>
													<?php } ?>
													<?php if(has_contact_permission('proposals')){ ?>
														<li class="kt-menu__item  kt-menu__item--submenu kt-menu__item--rel <?php if($this->uri->segment(2)=="proposals"){echo "kt-menu__item--open ";}?><?php if($this->uri->segment(2)=="proposals"){echo "kt-menu__item--open ";}?> " data-ktmenu-submenu-toggle="click" aria-haspopup="true">
															<a href="<?php echo site_url('clients/proposals'); ?>" class="<?php if($this->uri->segment(2)=="proposals"){echo "kt-menu__item--open ";}?><?php if($this->uri->segment(2)=="proposals"){echo "kt-menu__item--open";}?> kt-menu__link "><span class="kt-menu__link-text"><?php echo _l('proposals'); ?></span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
														</li>
													<?php } ?>
													<li class="kt-menu__item  kt-menu__item--submenu kt-menu__item--rel <?php if($this->uri->segment(2)=="files"){echo "kt-menu__item--open ";}?><?php if($this->uri->segment(2)=="files"){echo "kt-menu__item--open ";}?> " data-ktmenu-submenu-toggle="click" aria-haspopup="true">
														<a href="<?php echo site_url('clients/files'); ?>" class="<?php if($this->uri->segment(2)=="files"){echo "kt-menu__item--open ";}?><?php if($this->uri->segment(2)=="files"){echo "kt-menu__item--open";}?> kt-menu__link "><span class="kt-menu__link-text"><?php echo _l('customer_profile_files'); ?></span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
													</li>
												</ul>
												<hr class="menu-linha">
												<ul class="kt-menu__nav menu-mobile">
													<li class="kt-menu__item  kt-menu__item--submenu kt-menu__item--rel menu-empresa">
														<!--begin: User bar -->
														<div>
															<div class="menu-div">
																<img class="menu-perfil" alt="<?php echo $contact->firstname . ' ' . $contact->lastname; ?>" title="<?php echo $contact->firstname . ' ' . $contact->lastname; ?>" src="<?php echo contact_profile_image_url($contact->id,'thumb'); ?>">
																<br>
																<span class="kt-header__topbar-welcome">Olá,</span>
																<span class="kt-header__topbar-username"><?php echo $contact->firstname; ?></span>
															</div>
															<!--begin: Navigation -->
															<div class="menu-mobile">
																				<a href="<?php echo site_url('clients/profile'); ?>" class="kt-notification__item">
																					<div class="kt-notification__item-icon">
																						<i class="flaticon2-calendar-3 kt-font-success"></i>
																					</div>
																					<div class="kt-notification__item-details">
																						<div class="kt-notification__item-title kt-font-bold">
																							<?php echo _l('clients_nav_profile'); ?>
																						</div>
																						<!-- <div class="kt-notification__item-time">
																						Account settings and more
																					</div> -->
																					</div>
																				</a>
																			<?php if($contact->is_primary == 1){ ?>
																				<a href="<?php echo site_url('clients/company'); ?>" class="kt-notification__item">
																					<div class="kt-notification__item-icon">
																						<i class="flaticon2-mail kt-font-warning"></i>
																					</div>
																					<div class="kt-notification__item-details">
																						<div class="kt-notification__item-title kt-font-bold">
																							<?php echo _l('client_company_info'); ?>
																						</div>
																						<!-- <div class="kt-notification__item-time">
																							Inbox and tasks
																						</div> -->
																					</div>
																				</a>
																			<?php } ?>
																	<?php if(get_option('show_subscriptions_in_customers_area') == '1' && can_logged_in_contact_update_credit_card()) { ?>
																		<a href="<?php echo site_url('clients/credit_card'); ?>" class="kt-notification__item">
																			<div class="kt-notification__item-icon">
																				<i class="flaticon2-rocket-1 kt-font-danger"></i>
																			</div>
																			<div class="kt-notification__item-details">
																				<div class="kt-notification__item-title kt-font-bold">
																					<?php echo _l('credit_card'); ?>
																				</div>
																				<!-- <div class="kt-notification__item-time">
																				Logs and notifications
																			</div> -->
																		</div>
																	</a>
																<?php } ?>
																<a href="<?php echo site_url('clients/announcements'); ?>" class="kt-notification__item">
																	<div class="kt-notification__item-icon">
																		<i class="flaticon2-hourglass kt-font-brand"></i>
																	</div>
																	<div class="kt-notification__item-details">
																		<div class="kt-notification__item-title kt-font-bold">
																			<?php echo _l('announcements'); ?>
																		</div>
																		<!-- <div class="kt-notification__item-time">
																		latest tasks and projects
																	</div> -->
																</div>
																</a>
									
															</div>
														</div>
													</li>								
													<!--
													<li class="kt-menu__item  kt-menu__item--submenu kt-menu__item--rel menu-sair" data-ktmenu-submenu-toggle="click" aria-haspopup="true">
														<a href="<?php echo site_url('authentication/logout');?>" class="btn btn-label btn-label-brand btn-sm btn-bold"><?php echo _l('clients_nav_logout'); ?></a>
													</li>
													-->
												</ul>								
											</div>
											<div class="kt-menu__item  kt-menu__item--submenu kt-menu__item--rel menu-sair" data-ktmenu-submenu-toggle="click" aria-haspopup="true">
												<a href="<?php echo site_url('authentication/logout');?>" class="btn btn-label btn-label-brand btn-sm btn-bold"><?php echo _l('clients_nav_logout'); ?></a>
											</div>							
										</div>
										<!-- end: Header Menu -->
										<!--begin: Language bar -->
										<?php if(can_logged_in_contact_change_language()) {	?>										
											<div id="menu-idiomas" class="kt-header__topbar-item kt-header__topbar-item--langs menu-idiomas">
												<div class="kt-header__topbar-wrapper" data-toggle="dropdown" data-offset="10px,0px">
													<span class="kt-header__topbar-icon">
														<?php if(!$client->default_language) { ?>
															<img class="" src="<?php echo base_url('assets/themes/weboox/images/') . 'english' . ".svg"; ?>" alt="" />
														<?php } else { ?>

															<img class="" src="<?php echo base_url('assets/themes/weboox/images/') . $client->default_language . ".svg"; ?>" alt="" />
														<?php } ?>
													</span>
												</div>
												<div class="dropdown-menu dropdown-menu-fit dropdown-menu-right dropdown-menu-anim modal-idiomas">
													<ul class="kt-nav kt-margin-t-10 kt-margin-b-10">
														<?php foreach($this->app->get_available_languages() as $user_lang) { ?>
															<li  class="kt-nav__item kt-nav__item--active <?php if($client->default_language == $user_lang){echo 'active';} ?>" >
																<a href="<?php echo site_url('clients/change_language/'.$user_lang); ?>" class="kt-nav__link">
																	<span class="kt-nav__link-icon"><img src="<?php echo base_url('assets/themes/weboox/images/') . $user_lang . ".svg"; ?>" alt="" /></span>
																	<span class="kt-nav__link-text"> <?php echo ucfirst($user_lang); ?></span>
																</a>
															</li>
														<?php } ?>
													</ul>
												</div>
											</div>
										<?php } ?>
										<!--end: Language bar -->												
										<!-- begin:: Header Topbar -->
										<div class="kt-header__topbar kt-grid__item">
											<?php $_announcements = get_announcements_for_user(false); ?>
											<!--begin: Notifications -->
											<div class="kt-header__topbar-item dropdown">
												<div class="kt-header__topbar-wrapper" data-toggle="dropdown" data-offset="10px,0px">
													<span class="kt-header__topbar-icon kt-pulse kt-pulse--light">
														<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
															<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
																<rect id="bound" x="0" y="0" width="24" height="24" />
																<path d="M2.56066017,10.6819805 L4.68198052,8.56066017 C5.26776695,7.97487373 6.21751442,7.97487373 6.80330086,8.56066017 L8.9246212,10.6819805 C9.51040764,11.267767 9.51040764,12.2175144 8.9246212,12.8033009 L6.80330086,14.9246212 C6.21751442,15.5104076 5.26776695,15.5104076 4.68198052,14.9246212 L2.56066017,12.8033009 C1.97487373,12.2175144 1.97487373,11.267767 2.56066017,10.6819805 Z M14.5606602,10.6819805 L16.6819805,8.56066017 C17.267767,7.97487373 18.2175144,7.97487373 18.8033009,8.56066017 L20.9246212,10.6819805 C21.5104076,11.267767 21.5104076,12.2175144 20.9246212,12.8033009 L18.8033009,14.9246212 C18.2175144,15.5104076 17.267767,15.5104076 16.6819805,14.9246212 L14.5606602,12.8033009 C13.9748737,12.2175144 13.9748737,11.267767 14.5606602,10.6819805 Z" id="Combined-Shape" fill="#000000" opacity="0.3" />
																<path d="M8.56066017,16.6819805 L10.6819805,14.5606602 C11.267767,13.9748737 12.2175144,13.9748737 12.8033009,14.5606602 L14.9246212,16.6819805 C15.5104076,17.267767 15.5104076,18.2175144 14.9246212,18.8033009 L12.8033009,20.9246212 C12.2175144,21.5104076 11.267767,21.5104076 10.6819805,20.9246212 L8.56066017,18.8033009 C7.97487373,18.2175144 7.97487373,17.267767 8.56066017,16.6819805 Z M8.56066017,4.68198052 L10.6819805,2.56066017 C11.267767,1.97487373 12.2175144,1.97487373 12.8033009,2.56066017 L14.9246212,4.68198052 C15.5104076,5.26776695 15.5104076,6.21751442 14.9246212,6.80330086 L12.8033009,8.9246212 C12.2175144,9.51040764 11.267767,9.51040764 10.6819805,8.9246212 L8.56066017,6.80330086 C7.97487373,6.21751442 7.97487373,5.26776695 8.56066017,4.68198052 Z" id="Combined-Shape" fill="#000000" />
															</g>
														</svg>
														<!--<i class="flaticon2-bell-alarm-symbol"></i>-->
														<span class="kt-pulse__ring"></span>
													</span>
													<!--<span class="kt-badge kt-badge--light"></span>-->
												</div>
												<div class="dropdown-menu dropdown-menu-fit dropdown-menu-right dropdown-menu-anim dropdown-menu-xl modal-anuncios">
													<form>
														<!--begin: Head -->
														<div class="kt-head kt-head--skin-dark kt-head--fit-x kt-head--fit-b notifications-background" >
															<h3 class="kt-head__title">
																<?php echo _l('announcements'); ?>
																&nbsp;
																<span class="btn btn-success btn-sm btn-bold btn-font-md"><?php echo count($_announcements); ?> </span>
															</h3>
															<ul class="nav nav-tabs nav-tabs-line nav-tabs-bold nav-tabs-line-3x nav-tabs-line-success kt-notification-item-padding-x" role="tablist">
																<!-- <li class="nav-item">
																	<a class="nav-link active show" data-toggle="tab" href="#topbar_notifications_notifications" role="tab" aria-selected="true">Alerts</a>
																</li> -->
															</ul>
														</div>
														<!--end: Head -->
														<div class="tab-content">
															<div class="tab-pane active show" id="topbar_notifications_notifications" role="tabpanel">
																<div class="kt-notification kt-margin-t-10 kt-margin-b-10 kt-scroll" data-scroll="true" data-height="300" data-mobile-height="200">
																<?php if(count($_announcements) > 0){ ?>
																	<?php foreach($_announcements as $__announcement){ ?>
																	<a href="<?php echo site_url('clients/dismiss_announcement/'.$__announcement['announcementid']); ?>" class="kt-notification__item">
																		<div class="kt-notification__item-icon">
																			<i class="flaticon2-line-chart kt-font-success"></i>
																		</div>
																		<div class="kt-notification__item-details">
																			<div class="kt-notification__item-title">
																				<h4><?php echo _l('announcement'); ?>!</h4> <?php if($__announcement['showname'] == 1){ echo '<br /><small>'._l('announcement_from').' '. $__announcement['userid']; } ?></small><br />
																				<?php echo check_for_links($__announcement['message']); ?>
																			</div>
																			<div class="kt-notification__item-time">
																				<h4 class="bold no-margin font-medium">
																					<small>
																						<?php echo _l('announcement_date',_dt($__announcement['dateadded'])); ?></small>
																				</h4>
																			</div>
																		</div>
																	</a>
																	<?php } ?>
																<?php } else { ?>
																	<div class="kt-notification__item-detail">
																		<div class="kt-notification__item-title">
																			<h4 class="mx-auto text-center"><?php echo _l('no_announcements'); ?></h4>
																		</div>
																	</div>
																<?php } ?>
																</div>
															</div>
														</div>
													</form>
												</div>
											</div>
										</div>
										<!--end: Notifications -->										
										<!--begin: User bar -->
										<div class="kt-header__topbar-item kt-header__topbar-item--user dropdown">
											<div class="kt-header__topbar-wrapper" data-toggle="dropdown" data-offset="10px,0px">
												<!--<span class="kt-header__topbar-welcome">Olá,</span>
												<span class="kt-header__topbar-username"><?php //echo $contact->firstname; ?></span>
												<span class="kt-header__topbar-icon"><b><?php //echo $contact->firstname[0]; ?></b></span>-->
												<img id="menu-perfil-desktop" class="menu-perfil-desktop" alt="<?php echo $contact->firstname . ' ' . $contact->lastname; ?>" src="<?php echo contact_profile_image_url($contact->id,'thumb'); ?>">
											</div>
											<div class="dropdown-menu dropdown-menu-fit dropdown-menu-right dropdown-menu-anim dropdown-menu-xl modal-usuario">
												<!--begin: Head -->
												<div class="kt-user-card kt-user-card--skin-dark kt-notification-item-padding-x user-background" >
													<div class="kt-user-card__avatar">
														<img class="menu-perfil-modal" alt="<?php echo $contact->firstname . ' ' . $contact->lastname; ?>" src="<?php echo contact_profile_image_url($contact->id,'thumb'); ?>">
														<!--use below badge element instead the user avatar to display username's first letter(remove kt-hidden class to display it) -->
														<span class="kt-badge kt-hidden kt-badge--lg kt-badge--rounded kt-badge--bold kt-font-success"><?php echo $contact->firstname[0]; ?></span>
													</div>
													<div class="kt-user-card__name">
														<?php echo $contact->firstname . ' ' . $contact->lastname; ?>
													</div>
													<!-- <div class="kt-user-card__badge">
														<span class="btn btn-success btn-sm btn-bold btn-font-md">23 messages</span>
													</div> -->
												</div>
												<!--end: Head -->
												<!--begin: Navigation -->
												<div class="kt-notification">
													<a href="<?php echo site_url('clients/profile'); ?>" class="kt-notification__item">
														<div class="kt-notification__item-icon">
															<i class="flaticon2-calendar-3 kt-font-success"></i>
														</div>
														<div class="kt-notification__item-details">
															<div class="kt-notification__item-title kt-font-bold">
																<?php echo _l('clients_nav_profile'); ?>
															</div>
															<!-- <div class="kt-notification__item-time">
															Account settings and more
														</div> -->
														</div>
													</a>
													<?php if($contact->is_primary == 1){ ?>
													<a href="<?php echo site_url('clients/company'); ?>" class="kt-notification__item">
														<div class="kt-notification__item-icon">
															<i class="flaticon2-mail kt-font-warning"></i>
														</div>
														<div class="kt-notification__item-details">
															<div class="kt-notification__item-title kt-font-bold">
																<?php echo _l('client_company_info'); ?>
															</div>
															<!-- <div class="kt-notification__item-time">
																Inbox and tasks
															</div> -->
														</div>
													</a>
													<?php } ?>
													<?php if(get_option('show_subscriptions_in_customers_area') == '1' && can_logged_in_contact_update_credit_card()) { ?>
													<a href="<?php echo site_url('clients/credit_card'); ?>" class="kt-notification__item">
														<div class="kt-notification__item-icon">
															<i class="flaticon2-rocket-1 kt-font-danger"></i>
														</div>
														<div class="kt-notification__item-details">
															<div class="kt-notification__item-title kt-font-bold">
																<?php echo _l('credit_card'); ?>
															</div>
															<!-- <div class="kt-notification__item-time">
																Logs and notifications
															</div> -->
														</div>
													</a>
													<?php } ?>
													<a href="<?php echo site_url('clients/announcements'); ?>" class="kt-notification__item">
														<div class="kt-notification__item-icon">
															<i class="flaticon2-hourglass kt-font-brand"></i>
														</div>
														<div class="kt-notification__item-details">
															<div class="kt-notification__item-title kt-font-bold">
																<?php echo _l('announcements'); ?>
															</div>
															<!-- <div class="kt-notification__item-time">
																latest tasks and projects
															</div> -->
														</div>
													</a>
													<div class="kt-notification__custom kt-space-between">
														<a href="<?php echo site_url('authentication/logout');?>" class="btn btn-label btn-label-brand btn-sm btn-bold"><?php echo _l('clients_nav_logout'); ?></a>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<!--end: Navigation -->
							</div>
						</div>
						<!--end: User bar -->
					</div>
					<!-- end:: Header Topbar -->
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	window.onscroll = function() {addClass()};

	function addClass() {
		var logo_default = document.getElementById("logo-default");
		var logo_branco = document.getElementById("logo-branco");
		var logo_idiomas = document.getElementById("menu-idiomas");
		var perfil_cliente = document.getElementById("menu-perfil-desktop");
		
		if (document.body.scrollTop > 50 || document.documentElement.scrollTop > 50) {
			logo_branco.classList.remove("logo-default");
			logo_branco.classList.add("logo-sticky");
			logo_default.classList.remove("logo-sticky");
			logo_default.classList.add("logo-default");	
			
			logo_idiomas.classList.remove("menu-idiomas");
			logo_idiomas.classList.add("menu-idiomas-scroll");			
			
			perfil_cliente.classList.remove("menu-perfil-desktop");
			perfil_cliente.classList.add("menu-perfil-desktop-scroll");
		} else {
			logo_branco.classList.remove("logo-sticky");
			logo_branco.classList.add("logo-default");
			logo_default.classList.remove("logo-default");
			logo_default.classList.add("logo-sticky");		

			logo_idiomas.classList.remove("menu-idiomas-scroll");
			logo_idiomas.classList.add("menu-idiomas");	

			perfil_cliente.classList.remove("menu-perfil-desktop-scroll");
			perfil_cliente.classList.add("menu-perfil-desktop");			
		}
	}
</script>	
<?php } else { ?>
    <?php if((get_option('use_knowledge_base') == 1 && !is_client_logged_in() && get_option('knowledge_base_without_registration') == 1) || (get_option('use_knowledge_base') == 1 && is_client_logged_in())){ ?>
    <?php } } ?>