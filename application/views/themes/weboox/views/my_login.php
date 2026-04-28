<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="kt-grid kt-grid--ver kt-grid--root">
	<div class="kt-grid kt-grid--hor kt-grid--root  kt-login kt-login--v6 kt-login--signin" id="kt_login">
		<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--desktop kt-grid--ver-desktop kt-grid--hor-tablet-and-mobile">
			<div class="kt-grid__item  kt-grid__item--order-tablet-and-mobile-2  kt-grid kt-grid--hor kt-login__aside">
				<div class="kt-login__wrapper">
					<div class="kt-login__container">
						<div class="kt-login__body">
							<div class="kt-login__logo">
								<a href="#">
									<img src="<?= base_url('uploads/company/logo_dark.png')?>">
								</a>
							</div>
							<div class="kt-login__signin">
								<div class="kt-login__head">
									<h3 class="kt-login__title"><?php echo _l('clients_login_heading_no_register'); ?></h3>
								</div>
								<div class="kt-login__form">
									<?php if(isset($error)) { ?>
										<div class="alert alert-danger"><?php echo $error; ?></div>
									<?php } ?>

									<?php echo form_open($this->uri->uri_string(),array('class'=>'kt-form', 'id' => 'login_formulare')); ?>
										<div class="form-group input-group-prepend">
											<div class="input-group-prepend">				    	
												<span class="input-group-text" id="basic-addon1">				    		
													<i class="fa fa-envelope"></i>				    	
												</span>
											</div>										
											<input type="text" autofocus="true" class="form-control" name="email" id="email" placeholder="<?php echo _l('clients_login_email'); ?>">
											<?php echo form_error('email'); ?>
										</div>
										<div class="form-group input-group-prepend">
											<div class="input-group-prepend">				    	
												<span class="input-group-text" id="basic-addon1">				    		
													<i class="fa fa-lock"></i>				    	
												</span>
											</div>										
											<input type="password" class="form-control" name="password" id="password" placeholder="<?php echo _l('clients_login_password'); ?>">
											<?php echo form_error('password'); ?>
										</div>
										<div class="kt-login__extra">
											<label class="kt-checkbox">
												<input type="checkbox" name="remember"> <?php echo _l('clients_login_remember'); ?>
												<span></span>
											</label>

											<a href="<?php echo site_url('clients/forgot_password'); ?>" id="kt_login_forgot"><?php echo _l('customer_forgot_password'); ?></a>
										</div>
										<div class="kt-login__actions">
											<button id="kt_login_signin_submit" class="btn btn-brand btn-pill btn-elevate" type="submit"><?php echo _l('clients_login_login_string'); ?></button>
										</div>
										<?php echo form_close(); ?>

									</form>
								</div>
							</div>
						</div>
					</div>

					<?php if(get_option('allow_registration') == 1) { ?>
						<div class="kt-login__account">
							<span class="kt-login__account-msg">
								Ainda não possui cadastro?
							</span>&nbsp;&nbsp;
							<a href="<?php echo site_url('clients/register'); ?>" id="kt_login_signup" class="kt-login__account-link"><?php echo _l('clients_register_string'); ?></a>
						</div>
					<?php } ?>

				</div>
			</div>
			<div class="kt-grid__item kt-grid__item--fluid kt-grid__item--center kt-grid kt-grid--ver kt-login__content login-background">
				<div class="kt-login__section">
					<div class="kt-login__block">
						<!-- <h3 class="kt-login__title">Join Our Community</h3>
						<div class="kt-login__desc">
							Lorem ipsum dolor sit amet, coectetuer adipiscing
							<br>elit sed diam nonummy et nibh euismod
						</div> -->
					</div>
				</div>
			</div>
		</div>
	</div>
</div>