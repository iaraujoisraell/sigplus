<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="kt-container  kt-grid__item kt-grid__item--fluid" style="margin-bottom: 60px;">
    <div class="row">
        <div class="col-lg-12">
            <div class="kt-portlet" id="kt_portlet">
                <div class="kt-portlet__body"><?php echo validation_errors('<div class="alert alert-danger text-center">', '</div>'); ?>
                <div class="row">
                    <div class="panel_s">
                        <div class="panel-body">
                            <h4 class="terms-and-conditions-heading"><?php echo _l('terms_and_conditions'); ?></h4>
                            <hr />
                            <div class="tc-content terms-and-conditions-content">
                                <?php echo $terms; ?>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
</div>
