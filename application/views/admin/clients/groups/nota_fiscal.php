<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="modal fade" id="customer_file_share_file_with" data-total-contacts="<?php echo count($contacts); ?>" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title"><?php echo _l('share_file_with'); ?></h4>
        </div>
        <div class="modal-body">
            <?php echo form_hidden('file_id'); ?>
            <?php echo render_select('share_contacts_id[]',$contacts,array('id',array('firstname','lastname')),'customer_contacts',array(get_primary_contact_user_id($client->userid)),array('multiple'=>true,'data-actions-box'=>true),array(),'','',false); ?>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
            <button type="button" class="btn btn-info" onclick="do_share_file_contacts();"><?php echo _l('confirm'); ?></button>
        </div>
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<h4 class="no-mtop bold"><?php echo 'Notas Fiscais Avulsas'; ?>
<br />
<small class="text-info"><?php echo 'Arquivos'; ?></small>
</h4>
<hr />

    
    <div class="attachments">
        <div class="mtop25">

            <table class="table dt-table scroll-responsive" data-order-col="2" data-order-type="desc">
                <thead>
                    <tr>
                        <th width="30%"><?php echo _l('customer_attachments_file'); ?></th>
                        <th width="30%"><?php echo 'Info'; ?></th>
                        
                        <th><?php echo _l('file_date_uploaded'); ?></th>
                        <th><?php echo _l('options'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                   // $cliente_id = $invoice->clientid;
                   // $fatura_id = $invoice->id;
                    
                     //$attachments = get_all_customer_attachments($cliente_id, $fatura_id);
                    foreach($attachments as $type => $attachment){
                        $download_indicator = 'id';
                        $key_indicator = 'rel_id';
                        $upload_path = get_upload_path_by_type($type);
                        if($type == 'invoice' || $type == 'proposal' || $type == 'estimate' || $type == 'credit_note'){
                            $url = site_url() .'download/file/sales_attachment/';
                            $download_indicator = 'attachment_key';
                        } else if($type == 'contract'){
                            $url = site_url() .'download/file/contract/';
                            $download_indicator = 'attachment_key';
                        } else if($type == 'lead'){
                            $url = site_url() .'download/file/lead_attachment/';
                        } else if($type == 'task'){
                            $url = site_url() .'download/file/taskattachment/';
                            $download_indicator = 'attachment_key';
                        } else if($type == 'ticket'){
                            $url = site_url() .'download/file/ticket/';
                            $key_indicator = 'ticketid';
                        } else if($type == 'customer'){
                            $url = site_url() .'download/file/client/';
                            $download_indicator = 'attachment_key';
                        } else if($type == 'expense'){
                            $url = site_url() .'download/file/expense/';
                            $download_indicator = 'rel_id';
                        }
                        ?>
                        <?php foreach($attachment as $_att){
                            ?>
                            <tr id="tr_file_<?php echo $_att['id']; ?>">
                                <td>
                                   <?php
                                   $path = $upload_path . $_att[$key_indicator] . '/' . $_att['file_name'];
                                   $is_image = false;
                                   if(!isset($_att['external'])) {
                                    $attachment_url = $url . $_att[$download_indicator];
                                    $is_image = is_image($path);
                                    $img_url = site_url('download/preview_image?path='.protected_file_url_by_path($path,true).'&type='.$_att['filetype']);
                                    $lightBoxUrl = site_url('download/preview_image?path='.protected_file_url_by_path($path).'&type='.$_att['filetype']);
                                } else if(isset($_att['external']) && !empty($_att['external'])){

                                    if(!empty($_att['thumbnail_link']) && $_att['external'] == 'dropbox'){
                                        $is_image = true;
                                        $img_url = optimize_dropbox_thumbnail($_att['thumbnail_link']);
                                    }

                                    $attachment_url = $_att['external_link'];
                                }
                                if($is_image){
                                    echo '<div class="preview_image">';
                                }
                                ?>
                                <a href="<?php if($is_image){ echo isset($lightBoxUrl) ? $lightBoxUrl : $img_url; } else {echo $attachment_url; } ?>"<?php if($is_image){ ?> data-lightbox="customer-profile" <?php } ?> class="display-block mbot5">
                                    <?php if($is_image){ ?>
                                   
                                        <div class="table-image">
                                            <div class="text-center"><i class="fa fa-spinner fa-spin mtop30"></i></div>
                                            <img src="#" class="img-table-loading" data-orig="<?php echo $img_url; ?>">
                                        </div>
                                    <?php } else { ?>
                                    
                                     <i class="<?php echo get_mime_class($_att['filetype']); ?>"></i> <?php echo $_att['file_name']; ?>
                                 <?php } ?>
                             </a>
                             <?php if($is_image){ echo '</div>'; } ?>
                         </td>
                         <td>
                            <?php
                            echo 'Fat : '.$_att['fatura'].'<br>';
                            echo 'Nro : '.$_att['nf_numero'].'<br>';
                            echo 'CNPJ : '.$_att['nf_cnpj'].'<br>';
                            echo 'Valor : '.$_att['valor'].'<br>';
                            echo 'Dt Emissão : '.$_att['data_emissao'].'<br>';
                            ?>
                        </td>
                        <td data-order="<?php echo $_att['dateadded']; ?>"><?php echo _dt($_att['dateadded']); ?></td>
                        <td>
                              <button type="button" data-toggle="modal" data-file-name="<?php echo $_att['file_name']; ?>" data-id="<?php echo $_att['id']; ?>" data-filetype="<?php echo $_att['filetype']; ?>" data-path="<?php echo $path; ?>" data-target="#send_file" class="btn btn-warning btn-icon"><i class="fa fa-list"></i></button>
                          
                            <?php if($type == 'customer'){ ?>
                                <a href="<?php echo admin_url('clients/delete_attachment/'.$_att['rel_id'].'/'.$_att['id']); ?>"  class="btn btn-danger btn-icon _delete"><i class="fa fa-remove"></i></a>
                            <?php } ?>
                        </td>
                    <?php } ?>
                </tr>
            <?php } // 23:05  ---    7.8% ?>
        </tbody>
    </table>
</div>

</div>
<?php
//$this->load->view('admin/clients/modals/send_file_modal');
$this->load->view('admin/clients/modals/add_file_nf_modal');

