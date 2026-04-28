<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="table-responsive">
    <div class="">
        <?php 
            $invoice_id =  $invoice->id;
            $client_id =  $invoice->clientid;
        ?>
        <a href="<?php echo admin_url('invoices/add_nota_fiscal/'.$invoice_id.'/'.$client_id); ?>" target="_blank" class="btn btn-primary">Add Nota Fiscal Avulsa</a>
    </div>
    <br>
    <table class="table table-hover no-mtop">
        <thead>
            <tr>
                <th><span class="bold"><?php echo 'Número'; ?></span></th>
                <th><span class="bold"><?php echo 'NFCe'; ?></span></th>
                <th><span class="bold"><?php echo 'Status'; ?></span></th>
                <th><span class="bold"><?php echo 'CNPJ'; ?></span></th>
                <th><span class="bold"><?php echo 'Data Emissão'; ?></span></th>
                <th><span class="bold"><?php echo 'Código Verificação'; ?></span></th>
                <th><span class="bold"><?php echo 'Valor'; ?></span></th>
                <th><span class="bold"><?php echo _l('options'); ?></span></th>
            </tr>
        </thead>
        <tbody>
            <?php
            /*
            $dddwhats = $vendedor_info->ddd_whatsapp;
            $whats = $vendedor_info->whatsapp;
            $m_whats = $dddwhats.$whats;
             */
            
            foreach($notas_fiscais as $payment){
            $status = $payment['status'];    
            $invoiceid = $payment['invoice_id'];
            $dados_invoice = $this->invoices_model->get($invoiceid);
            $client_id = $dados_invoice->clientid;
            $dados_cliente = $this->clients_model->get($client_id);
            $phonenumber = $dados_cliente->phonenumber;
            $link_url = $payment['url'];
            
            if($status == 'autorizado'){
                $status_lab = "<label class='label label-success'>$status</label>";
            }else if($status == 'cancelado'){
                $status_lab = "<label class='label label-danger'>$status</label>";
            }
            
            
            ?>
            <tr class="payment">
                <td><?php echo $payment['numero']; ?></td>
                <td><a target="_blank" class="btn-default pull-center" href="<?php echo $payment['url']; ?>"><i class="fa fa-file-pdf-o"></i></a></td>
                <td><?php echo $status_lab; ?></td>
                <td><?php echo $payment['cnpj_prestador']; ?></td>
                <td><?php echo _d($payment['data_emissao']); ?></td>
                <td><?php echo $payment['codigo_verificacao']; ?></td>
                <td><?php echo ' '.app_format_money($payment['valor'], 'R$ '); ?></td>
                <td>
                    <?php /*
                    <a href="<?php echo admin_url('payments/payment/'.$payment['paymentid']); ?>" class="btn btn-default btn-icon"><i class="fa fa-pencil-square-o"></i></a> */ ?>
                    <?php if(has_permission('payments','','delete')){ ?>
                    <a href="<?php echo admin_url('invoices/cancelar_nota_fiscal/'.$payment['id'] . '/' . $payment['invoice_id']); ?>" title="Cancelar NFc-e" class="btn btn-danger btn-icon _delete"><i title="Cancelar NFc-e" class="fa fa-ban"></i></a>
                    <?php }
                    
                    if($phonenumber){
                        $whatsapp = "<a class='btn btn-success btn-icon'  title = 'Whatsapp Web' target='_blank' href='https://api.whatsapp.com/send?phone=55$phonenumber&text=$link_url '><i title = 'Whatsapp Web' class='fa fa-whatsapp'></i></a>";
                        echo $whatsapp;
                    }
                   
                    ?>
                    
                    
                    
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
    
    
    <br><br> 
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
                    
                    
                     $attachments = get_all_customer_attachments($client_id, $invoiceid);
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
                            <!--  <button type="button" data-toggle="modal" data-file-name="<?php echo $_att['file_name']; ?>" data-id="<?php echo $_att['id']; ?>" data-filetype="<?php echo $_att['filetype']; ?>" data-path="<?php echo $path; ?>" data-target="#send_file" class="btn btn-warning btn-icon"><i class="fa fa-list"></i></button> -->
                          
                            <?php if($type == 'customer'){ ?>
                                <a href="<?php echo admin_url('clients/delete_nota_avulsa/'.$_att['rel_id'].'/'.$_att['id']); ?>"  class="btn btn-danger btn-icon _delete"><i class="fa fa-remove"></i></a>
                            <?php } ?>
                        </td>
                    <?php } ?>
                </tr>
            <?php } 
            //$this->load->view('admin/clients/modals/add_file_nf_modal');
            ?>
        </tbody>
    </table>
</div>

</div>
</div>
