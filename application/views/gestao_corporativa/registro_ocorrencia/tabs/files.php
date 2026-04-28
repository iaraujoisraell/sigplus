<div role="tabpanel" class="tab-pane" id="files">
    <hr class="no-mtop" />


    <div class="row w-100 col-md-12 mbot15" id="">
        <div class="alert alert-info alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h5><i class="icon fa fa-info-circle"></i> Lista de anexos do Registro de Ocorrência</h5>
        </div>
        <hr />
        <?php
        $arquivos = explode(',', $ro->arquivos);
        //print_r($arquivos); exit;
        if (count($arquivos) > 0) {

            foreach ($arquivos as $attachment) {
                ?>

                <div class="col-md-12">
                    <p class="text-muted">NOTIFICANTE - <?php echo date("d/m/Y H:i:s", strtotime($ro->date_created)); ?> (Notificante)</p>
                    <?php
                    if ($attachment != '') {

                        $path = base_url() . "assets/intranet/arquivos/ro_arquivos/" . $attachment;
                        $is_image = is_image($path);

                        $extensao = pathinfo($attachment, PATHINFO_EXTENSION);
                        if ($is_image) {
                            echo '<div class="preview_image">';
                        }
                        ?>
                        <a target="_blank" href="<?php echo base_url() . "assets/intranet/arquivos/ro_arquivos/" . $attachment; ?>" class="col-md-12display-block mbot5"<?php if ($is_image) { ?> data-lightbox="attachment-ticket-10" <?php } ?>>
                            <i class="<?php echo get_mime_class_simple($extensao); ?>"></i> <?php echo $attachment; ?>
                            <?php if ($is_image) { ?>
                                <img class="mtop5" src="<?php echo base_url() . "assets/intranet/arquivos/ro_arquivos/" . $attachment; ?>">
                            <?php } ?>
                        </a>
                        <?php
                        if ($is_image) {
                            echo '</div>';
                        }
                        echo '<hr />';
                        ?>
                    
                    <?php
                }?>
                    </div>
            <?php }
        }
        ?>
        <?php
        $arquivos = [];
        $this->load->model('Categorias_campos_model');
        $files = $this->Categorias_campos_model->get_campos_file_values($ro->id, 'r.o');
        foreach ($files as $file) {
            if($file['value'] != ''){
            array_push($arquivos, $file['value']);
            }
        }
        //print_r($arquivos); exit;
        if (count($arquivos) > 0) {

            foreach ($arquivos as $attachment) {
                ?>
                <div class="col-md-12">

                    <p class="text-muted"><?php echo strtoupper($t['nome_campo']); ?> - <?php echo date("d/m/Y", strtotime($t['data_cadastro'])); ?> (Notificante)</p>

                    <?php
                    if ($attachment != '') {

                        $path = base_url() . "assets/intranet/arquivos/ro_arquivos/" . $attachment;
                        $is_image = is_image($path);

                        $extensao = pathinfo($attachment, PATHINFO_EXTENSION);
                        if ($is_image) {
                            echo '<div class="preview_image">';
                        }
                        ?>
                        <a target="_blank" href="<?php echo base_url() . "assets/intranet/arquivos/ro_arquivos/campo_file/" . $attachment; ?>" class="col-md-12display-block mbot5"<?php if ($is_image) { ?> data-lightbox="attachment-ticket-10" <?php } ?>>
                            <i class="<?php echo get_mime_class_simple($extensao); ?>"></i> <?php echo $attachment; ?>
                            <?php if ($is_image) { ?>
                                <img class="mtop5" src="<?php echo base_url() . "assets/intranet/arquivos/ro_arquivos/" . $attachment; ?>">
                            <?php } ?>
                        </a>
                        <?php
                        if ($is_image) {
                            echo '</div>';
                        }
                        echo '<hr />';
                        ?>

                        <?php }
                    ?>
                </div>
            <?php
            }
        }
        ?>

    </div>

</div>



