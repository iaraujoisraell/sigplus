<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--ver kt-grid--stretch">
    <div class="kt-container kt-body  kt-grid kt-grid--ver" id="kt_body">
		<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor">

                <!-- begin:: Subheader -->
    			<div class="kt-subheader   kt-grid__item" id="kt_subheader">
                <div class="kt-subheader__main">
                    <h3 class="kt-subheader__title">
                        <?php echo _l('clients_contracts'); ?>
                    </h3>
                </h3>
                <div class="kt-subheader__breadcrumbs">
                    <a href="<?php echo site_url('clients') ?>" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
                    <span class="kt-subheader__breadcrumbs-separator"></span>
                    <a href="<?php echo site_url('clients') ?>" class="kt-subheader__breadcrumbs-link">
                        Dashboard	                    </a>
                        <span class="kt-subheader__breadcrumbs-separator"></span>
                        <a href="<?php echo site_url('clients/contracts') ?>" class="kt-subheader__breadcrumbs-link">
                            <?php echo _l('clients_contracts'); ?>    </a>
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
                                <h3 class="text-white contracts-summary-heading no-mtop mbot15 badge badge-primary"><?php echo _l('contract_summary_by_type'); ?></h3>
                                <div class="relative" style="max-height:300px;">
                                    <canvas class="chart" height="300" id="contracts-by-type-chart"></canvas>
                                </div>
                            </div>
                            <div class="col-md-12">

                                <table class="table dt-table table-contracts" data-order-col="4" data-order-type="asc">
                                    <thead>
                                        <tr>
                                            <th class="th-contracts-subject"><?php echo _l('clients_contracts_dt_subject'); ?></th>
                                            <th class="th-contracts-type"><?php echo _l('clients_contracts_type'); ?></th>
                                            <th class="th-contracts-signature"><?php echo _l('signature'); ?></th>
                                            <th class="th-contracts-start-date"><?php echo _l('clients_contracts_dt_start_date'); ?></th>
                                            <th class="th-contracts-end-date"><?php echo _l('clients_contracts_dt_end_date'); ?></th>
                                            <?php
                                            $custom_fields = get_custom_fields('contracts',array('show_on_client_portal'=>1));
                                            foreach($custom_fields as $field){ ?>
                                                <th><?php echo $field['name']; ?></th>
                                            <?php } ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($contracts as $contract){
                                            $expiry_class = '';
                                            if (!empty($contract['dateend'])) {
                                                $_date_end = date('Y-m-d', strtotime($contract['dateend']));
                                                if ($_date_end < date('Y-m-d')) {
                                                    $expiry_class = 'alert-danger';
                                                }
                                            }
                                            ?>
                                            <tr class="<?php echo $expiry_class; ?>">
                                                <td>
                                                    <?php
                                                    echo '<a href="'.site_url('contract/'.$contract['id'].'/'.$contract['hash']).'" class="td-contract-url">'.$contract['subject'].'</a>';
                                                    ?>
                                                </td>
                                                <td><?php echo $contract['type_name']; ?></td>
                                                <td>
                                                    <?php
                                                    if(!empty($contract['signature'])) {
                                                        echo '<span class="text-success td-contract-is-signed">' . _l('is_signed') . '</span>';
                                                    } else {
                                                        echo '<span class="text-muted td-contract-not-signed">' . _l('is_not_signed') . '</span>';
                                                    }
                                                    ?>
                                                </td>
                                                <td data-order="<?php echo $contract['datestart']; ?>"><?php echo _d($contract['datestart']); ?></td>
                                                <td data-order="<?php echo $contract['dateend']; ?>"><?php echo _d($contract['dateend']); ?></td>
                                                <?php foreach($custom_fields as $field){ ?>
                                                    <td><?php echo get_custom_field_value($contract['id'],$field['id'],'contracts'); ?></td>
                                                <?php } ?>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>


                            <script>
                            var contracts_by_type = '<?php echo $contracts_by_type_chart; ?>';
                            </script>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
