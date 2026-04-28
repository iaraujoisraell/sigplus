<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--ver kt-grid--stretch">
    <div class="kt-container kt-body  kt-grid kt-grid--ver" id="kt_body">
		<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor">

                <!-- begin:: Subheader -->
    			<div class="kt-subheader   kt-grid__item" id="kt_subheader">
                <div class="kt-subheader__main">
                    <h3 class="kt-subheader__title">
                        <?php echo _l('proposals'); ?>
                    </h3>
                </h3>
                <div class="kt-subheader__breadcrumbs">
                    <a href="<?php echo site_url('clients') ?>" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
                    <span class="kt-subheader__breadcrumbs-separator"></span>
                    <a href="<?php echo site_url('clients') ?>" class="kt-subheader__breadcrumbs-link">
                        Dashboard	                    </a>
                        <span class="kt-subheader__breadcrumbs-separator"></span>
                        <a href="<?php echo site_url('clients/proposals') ?>" class="kt-subheader__breadcrumbs-link">
                            <?php echo _l('proposals'); ?>    </a>
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
                            <div class="panel_s section-heading section-proposals">
                                <div class="panel-body">
                                    <h4 class="no-margin section-text"><?php echo _l('proposals'); ?></h4>
                                </div>
                            </div>

                            <div class="col-md-12">

                                <table class="table dt-table table-proposals" data-order-col="3" data-order-type="desc">
                                    <thead>
                                        <tr>
                                            <th class="th-proposal-number"><?php echo _l('proposal') . ' #'; ?></th>
                                            <th class="th-proposal-subject"><?php echo _l('proposal_subject'); ?></th>
                                            <th class="th-proposal-total"><?php echo _l('proposal_total'); ?></th>
                                            <th class="th-proposal-open-till"><?php echo _l('proposal_open_till'); ?></th>
                                            <th class="th-proposal-date"><?php echo _l('proposal_date'); ?></th>
                                            <th class="th-proposal-status"><?php echo _l('proposal_status'); ?></th>
                                            <?php
                                            $custom_fields = get_custom_fields('proposal',array('show_on_client_portal'=>1));
                                            foreach($custom_fields as $field){ ?>
                                                <th><?php echo $field['name']; ?></th>
                                            <?php } ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($proposals as $proposal){ ?>
                                            <tr>
                                                <td>
                                                    <a href="<?php echo site_url('proposal/'.$proposal['id'].'/'.$proposal['hash']); ?>" class="td-proposal-url">
                                                        <?php echo format_proposal_number($proposal['id']); ?>
                                                        <?php
                                                        if ($proposal['invoice_id']) {
                                                            echo '<br /><span class="text-success proposal-invoiced">' . _l('estimate_invoiced') . '</span>';
                                                        }
                                                        ?>
                                                    </a>
                                                    <td>
                                                        <a href="<?php echo site_url('proposal/'.$proposal['id'].'/'.$proposal['hash']); ?>" class="td-proposal-url-subject">
                                                            <?php echo $proposal['subject']; ?>
                                                        </a>
                                                        <?php
                                                        if ($proposal['invoice_id'] != NULL) {
                                                            $invoice = $this->invoices_model->get($proposal['invoice_id']);
                                                            echo '<br /><a href="' . site_url('invoice/' . $invoice->id . '/' . $invoice->hash) . '" target="_blank" class="td-proposal-invoice-url">' . format_invoice_number($invoice->id) . '</a>';
                                                        } else if ($proposal['estimate_id'] != NULL) {
                                                            $estimate = $this->estimates_model->get($proposal['estimate_id']);
                                                            echo '<br /><a href="' . site_url('estimate/' . $estimate->id . '/' . $estimate->hash) . '" target="_blank" class="td-proposal-estimate-url">' . format_estimate_number($estimate->id) . '</a>';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td data-order="<?php echo $proposal['total']; ?>">
                                                        <?php
                                                        if ($proposal['currency'] != 0) {
                                                            echo app_format_money($proposal['total'], get_currency($proposal['currency']));
                                                        } else {
                                                            echo app_format_money($proposal['total'], get_base_currency());
                                                        }
                                                        ?>
                                                    </td>
                                                    <td data-order="<?php echo $proposal['open_till']; ?>"><?php echo _d($proposal['open_till']); ?></td>
                                                    <td data-order="<?php echo $proposal['date']; ?>"><?php echo _d($proposal['date']); ?></td>
                                                    <td><?php echo format_proposal_status($proposal['status']); ?></td>
                                                    <?php foreach($custom_fields as $field){ ?>
                                                        <td><?php echo get_custom_field_value($proposal['id'],$field['id'],'proposal'); ?></td>
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
