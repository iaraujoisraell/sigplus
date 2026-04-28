<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head_menu_admin(false); ?>
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
			<?php
			echo form_open($this->uri->uri_string(),array('id'=>'invoice-form','class'=>'_transaction_form invoice-form'));
			if(isset($invoice)){
				echo form_hidden('isedit');
			}
			?>
			<div class="col-md-12">
				<?php $this->load->view('admin/invoices/invoice_template'); ?>
			</div>
			<?php echo form_close(); ?>
			<?php $this->load->view('admin/invoice_items/item'); ?>
		</div>
	</div>
</div>
<?php init_tail(); ?>
<script>
	$(function(){
		validate_invoice_form();
	    // Init accountacy currency symbol
	    init_currency();
	    // Project ajax search
	    init_ajax_project_search_by_customer_id();
	    // Maybe items ajax search
	    init_ajax_search('items','#item_select.ajax-search',undefined,admin_url+'items/search');
	});
           filtraConvenio();
</script>
</body>
</html>
