<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>


<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--ver kt-grid--stretch">
    <div class="kt-container kt-body  kt-grid kt-grid--ver" id="kt_body">
		<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor">

                <!-- begin:: Subheader -->
    			<div class="kt-subheader   kt-grid__item" id="kt_subheader">
                <div class="kt-subheader__main">
                    <h3 class="kt-subheader__title">
                        <?php echo _l('customer_profile_files'); ?>
                    </h3>
                </h3>
                <div class="kt-subheader__breadcrumbs">
                    <a href="<?php echo site_url('clients') ?>" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
                    <span class="kt-subheader__breadcrumbs-separator"></span>
                    <a href="<?php echo site_url('clients') ?>" class="kt-subheader__breadcrumbs-link">
                        Dashboard	                    </a>
                        <span class="kt-subheader__breadcrumbs-separator"></span>
                        <a href="<?php echo site_url('clients/files') ?>" class="kt-subheader__breadcrumbs-link">
                            <?php echo _l('customer_profile_files'); ?>    </a>
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
                    <div class="kt-portlet__body" style="padding-bottom: 108px;"><?php echo validation_errors('<div class="alert alert-danger text-center">', '</div>'); ?>
                        <div class="row">

                            <div class="col-md-12">

                                <?php echo form_open_multipart(site_url('clients/upload_files'),array('class'=>'dropzone','id'=>'files-upload')); ?>
                                <input type="file" name="file" multiple class="hide"/>
                            </div>
                            <?php echo form_close(); ?>
                            <div class="mtop15 mbot15 text-right">
                                <button class="gpicker" data-on-pick="customerFileGoogleDriveSave">
                                    <i class="fa fa-google" aria-hidden="true"></i>
                                    <?php echo _l('choose_from_google_drive'); ?>
                                </button>
                                <?php if(get_option('dropbox_app_key') != ''){ ?>
                                    <div id="dropbox-chooser-files"></div>
                                <?php } ?>
                            </div>
                            <?php if(count($files) == 0){ ?>
                                <hr class="hr-panel-heading" />
                                <div class="text-center mt-3">
                                    <h4 class="no-margin"><?php echo _l('no_files_found'); ?></h4>
                                </div>
                            <?php } else { ?>
                                <div class="col-md-12">

                                    <table class="table dt-table mtop15 table-files" data-order-col="1" data-order-type="desc">
                                        <thead>
                                            <tr>
                                                <th class="th-files-file"><?php echo _l('customer_attachments_file'); ?></th>
                                                <th class="th-files-date-uploaded"><?php echo _l('file_date_uploaded'); ?></th>
                                                <?php if(get_option('allow_contact_to_delete_files') == 1){ ?>
                                                    <th class="th-files-option"><?php echo _l('options'); ?></th>
                                                <?php } ?>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach($files as $file){ ?>
                                                <tr>
                                                    <td>
                                                        <?php
                                                        $url = site_url() .'download/file/client/';
                                                        $path = get_upload_path_by_type('customer') . $file['rel_id'] . '/' . $file['file_name'];
                                                        $is_image = false;
                                                        if(!isset($file['external'])) {
                                                            $attachment_url = $url . $file['attachment_key'];
                                                            $is_image = is_image($path);
                                                            $img_url = site_url('download/preview_image?path='.protected_file_url_by_path($path,true).'&type='.$file['filetype']);
                                                        } else if(isset($file['external']) && !empty($file['external'])){
                                                            if(!empty($file['thumbnail_link'])){
                                                                $is_image = true;
                                                                $img_url = optimize_dropbox_thumbnail($file['thumbnail_link']);
                                                            }
                                                            $attachment_url = $file['external_link'];
                                                        }
                                                        if($is_image){
                                                            echo '<div class="preview_image">';
                                                        }
                                                        ?>
                                                        <a href="<?php echo $attachment_url; ?>"<?php echo (isset($file['external']) && !empty($file['external']) ? ' target="_blank"' : ''); ?>
                                                            class="display-block mbot5">
                                                            <?php if($is_image){ ?>
                                                                <div class="table-image">
                                                                    <div class="text-center"><i class="fa fa-spinner fa-spin mtop30"></i></div>
                                                                    <img src="#" class="img-table-loading" data-orig="<?php echo $img_url; ?>">
                                                                </div>
                                                            <?php } else { ?>
                                                                <i class="<?php echo get_mime_class($file['filetype']); ?>"></i> <?php echo $file['file_name']; ?>
                                                            <?php } ?>
                                                        </a>
                                                        <?php if($is_image){ echo '</div>'; } ?>
                                                    </td>
                                                    <td data-order="<?php echo $file['dateadded']; ?>"><?php echo _dt($file['dateadded']); ?></td>
                                                    <?php if(get_option('allow_contact_to_delete_files') == 1) { ?>
                                                        <td>
                                                            <?php if($file['contact_id'] == get_contact_user_id()){ ?>
                                                                <a href="<?php echo site_url('clients/delete_file/'.$file['id'].'/general'); ?>"
                                                                    class="btn btn-danger btn-icon _delete file-delete"><i class="fa fa-remove"></i></a>
                                                                <?php } ?>
                                                            </td>
                                                        <?php } ?>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    <?php } ?>
                                </div>



                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
