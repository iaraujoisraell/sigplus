<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--ver kt-grid--stretch">
    <div class="kt-container kt-body  kt-grid kt-grid--ver" id="kt_body">
		<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor">

                <!-- begin:: Subheader -->
    			<div class="kt-subheader   kt-grid__item" id="kt_subheader">
                <div class="kt-subheader__main">
                    <h3 class="kt-subheader__title">
                        <?php echo _l('clients_my_invoices'); ?>
                    </h3>
                </h3>
                <div class="kt-subheader__breadcrumbs">
                    <a href="<?php echo site_url('clients') ?>" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
                    <span class="kt-subheader__breadcrumbs-separator"></span>
                    <a href="<?php echo site_url('clients') ?>" class="kt-subheader__breadcrumbs-link">
                        Dashboard	                    </a>
                        <span class="kt-subheader__breadcrumbs-separator"></span>
                        <a href="<?php echo site_url('clients/projects') ?>" class="kt-subheader__breadcrumbs-link">
                            <?php echo _l('clients_my_invoices'); ?>    </a>
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
        <div class="row" >
            <div class="col-lg-12">
                <div class="kt-portlet" id="kt_portlet">
                    <div class="kt-portlet__body"><?php echo validation_errors('<div class="alert alert-danger text-center">', '</div>'); ?>
                        <div class="row">
                            <div class="col-md-12 mb-3">

                                <?php if(has_contact_permission('invoices')){ ?>
                                    <a href="<?php echo site_url('clients/statement'); ?>" class="view-account-statement"><?php echo _l('view_account_statement'); ?></a>
                                <?php } ?>
                            </div>

                            <?php get_template_part('invoices_stats'); ?>
                            <hr />
                            <div class="col-md-12">

                                <table class="table dt-table table-invoices" data-order-col="1" data-order-type="desc">
                                    <thead>
                                        <tr>
                                            <th class="th-invoice-number"><?php echo _l('clients_invoice_dt_number'); ?></th>
                                            <th class="th-invoice-date"><?php echo _l('clients_invoice_dt_date'); ?></th>
                                            <th class="th-invoice-duedate"><?php echo _l('clients_invoice_dt_duedate'); ?></th>
                                            <th class="th-invoice-amount"><?php echo _l('clients_invoice_dt_amount'); ?></th>
                                            <th class="th-invoice-status"><?php echo _l('clients_invoice_dt_status'); ?></th>
                                            <?php
                                            $custom_fields = get_custom_fields('invoice',array('show_on_client_portal'=>1));
                                            foreach($custom_fields as $field){ ?>
                                                <th><?php echo $field['name']; ?></th>
                                            <?php } ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($invoices as $invoice){ ?>
                                            <tr>
                                                <td data-order="<?php echo $invoice['number']; ?>"><a href="<?php echo site_url('invoice/' . $invoice['id'] . '/' . $invoice['hash']); ?>" class="invoice-number"><?php echo format_invoice_number($invoice['id']); ?></a></td>
                                                <td data-order="<?php echo $invoice['date']; ?>"><?php echo _d($invoice['date']); ?></td>
                                                <td data-order="<?php echo $invoice['duedate']; ?>"><?php echo _d($invoice['duedate']); ?></td>
                                                <td data-order="<?php echo $invoice['total']; ?>"><?php echo app_format_money($invoice['total'], $invoice['currency_name']); ?></td>
                                                <td><?php echo format_invoice_status($invoice['status'], 'inline-block', true); ?></td>
                                                <?php foreach($custom_fields as $field){ ?>
                                                    <td><?php echo get_custom_field_value($invoice['id'],$field['id'],'invoice'); ?></td>
                                                <?php } ?>
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
</div>
