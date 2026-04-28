<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head_intranet_novo(false); ?>
<style>
         body {
         font-family:'Open Sans';
         background:#f1f1f1;
         }
         h3 {
         margin-top: 7px;
         font-size: 18px;
         }
        
         .install-row.install-steps {
         margin-bottom:15px;
         box-shadow: 0px 0px 1px #d6d6d6;
         }
        
         .control-label {
         font-size:13px;
         font-weight:600;
         }
         .padding-10 {
         padding:10px;
         }
         .mbot15 {
         margin-bottom:15px;
         }
         .bg-default {
         background: #03a9f4;
         border:1px solid #03a9f4;
         color:#fff;
         }
         .bg-success {
         border: 1px solid #dff0d8;
         }
         .bg-not-passed {
         border:1px solid #f1f1f1;
         border-radius:2px;
         }
         .bg-not-passed {
         border-right:0px;
         }
         .bg-not-passed.finish {
         border-right:1px solid #f1f1f1 !important;
         }
         .bg-not-passed h5 {
         font-weight:normal;
         color:#6b6b6b;
         }
         .form-control {
         box-shadow:none;
         }
         .bold {
         font-weight:600;
         }
         .col-xs-5ths,
         .col-sm-5ths,
         .col-md-5ths,
         .col-lg-5ths {
         position: relative;
         min-height: 1px;
         padding-right: 15px;
         padding-left: 15px;
         }
         .col-xs-5ths {
         width: 20%;
         float: left;
         }
         b {
         font-weight:600;
         }
         .bootstrap-select .btn-default {
         background: #fff !important;
         border: 1px solid #d6d6d6 !important;
         box-shadow: none;
         color: #494949 !important;
         padding: 6px 12px;
         }
      </style>
<div >
	<div class="content">
		<div class="row">
			<div class="col-md-8 col-md-offset-2">
				<div class="panel_s">
					<div class="panel-body">
					<h4 class="no-margin">
					<?php echo $title; ?>
					</h4>
					<hr class="hr-panel-heading" />
						<?php echo form_open($this->uri->uri_string()); ?>

						<?php $value = (isset($announcement) ? $announcement->name : ''); ?>
						<?php echo render_input('name','announcement_name',$value); ?>

						<p class="bold"><?php echo _l('announcement_message'); ?></p>
						<?php $contents = ''; if(isset($announcement)){$contents = $announcement->message;} ?>
						<?php echo render_textarea('message','',$contents,array(),array(),'','tinymce'); ?>

						<div class="checkbox checkbox-primary checkbox-inline">
							<input type="checkbox" name="showtostaff" id="showtostaff" <?php if(isset($announcement)){if($announcement->showtostaff == 1){echo 'checked';} } else {echo 'checked';} ?>>
							<label for="showtostaff"><?php echo _l('announcement_show_to_staff'); ?></label>
						</div>
						<div class="checkbox checkbox-primary checkbox-inline">
							<input type="checkbox" name="showtousers" id="showtousers" <?php if(isset($announcement)){if($announcement->showtousers == 1){echo 'checked';}} ?>>
							<label for="showtousers"><?php echo _l('announcement_show_to_clients'); ?></label>
						</div>
						<div class="checkbox checkbox-primary checkbox-inline">
							<input type="checkbox" name="showname" id="showname" <?php if(isset($announcement)){if($announcement->showname == 1){echo 'checked';}} ?>>
							<label for="showname"><?php echo _l('announcement_show_my_name'); ?></label>
						</div>
						<button type="submit" class="btn btn-info pull-right"><?php echo _l('submit'); ?></button>
						<?php echo form_close(); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php init_tail(); ?>
<script>
	$(function(){
		appValidateForm($('form'),{name:'required'});
	});
</script>
</body>
</html>
