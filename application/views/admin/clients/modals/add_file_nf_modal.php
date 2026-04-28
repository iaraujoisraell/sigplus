<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!-- Client send file modal -->
<div class="modal fade" id="send_file" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <?php echo form_open('admin/misc/add_nota_fiscal/'); ?>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><?php echo 'Informações da Nota'; ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">                      
                        <?php //echo render_input('fatura','fatura'); ?>
                        <?php echo render_input('numero','numero'); ?>
                        <?php echo render_input('cnpj','cnpj'); ?>
                        <?php echo render_input('valor','valor'); ?>
                        
                        <label>  Dt Emissão</label>
                        <input type="date" name="data_emissao" id="data_emissao" required="true" class="form-control" >
                         
                        <?php echo form_hidden('id',''); ?>
                        <?php echo form_hidden('file_path',''); ?>
                        <?php echo form_hidden('filetype',''); ?>
                        <?php echo form_hidden('file_name',''); ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                <button type="submit" class="btn btn-info"><?php echo 'Salvar'; ?></button>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>
