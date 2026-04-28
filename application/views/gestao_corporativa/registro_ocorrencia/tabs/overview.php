<div role="tabpanel" class="tab-pane active" id="overview">
    <div class="row">
        <div class="col-md-3 border-right ticket-submitter-info ticket-submitter-info">




            <p class="text-muted" style="margin-top: 15px;">
                <strong style="text-transform: uppercase;  font-weight: bold;">Categoria:</strong>: <?php echo $ro->titulo; ?>
            </p>
            <p class="text-muted" style="margin-top: 15px;">
                <strong style="text-transform: uppercase;  font-weight: bold;">Data de Cadastro:</strong>: <?php echo date("d/m/Y H:i:s", strtotime($ro->date_created)); ?>
            </p>
            <p class="text-muted" style="margin-top: 15px;">
                <strong style="text-transform: uppercase;  font-weight: bold;">Data do Ocorrido:</strong>: <?php echo date("d/m/Y", strtotime($ro->date)); ?>
            </p>
            <p class="text-muted" style="margin-top: 15px;">
                <strong style="text-transform: uppercase;  font-weight: bold;">Prioridade:</strong>: <?php
                if ($ro->priority == 1) {
                    echo 'BAIXA';
                } elseif ($ro->priority == 2) {
                    echo 'MÉDIA';
                } else {
                    echo 'ALTA';
                }
                ?>
            </p>
            <p class="text-muted" style="margin-top: 15px;">
                <strong style="text-transform: uppercase;  font-weight: bold;">Assumido por</strong>: <?php
                if (!$ro->atribuido_a) {
                    echo 'AINDA NÃO ASSUMIDO';
                } else {
                    echo get_staff_full_name($ro->atribuido_a);
                }
                ?>
            </p>
            <div class="btn btn-default btn-xs">Notificante : <?php
                if ($ro->anonimo == 1) {
                    echo 'ANÔNIMO';
                } elseif ($ro->firstname) {
                    echo $ro->firstname . ' ' . $ro->lastname;
                } else {
                    echo 'Cliente via Portal';
                }
                ?></div>
            <br>

        </div>
        <div class="col-md-9">
            <div class="row">

                <div class="col-md-12 text-right">

                    <?php if (!empty($ticket->message)) { ?>
                        <a href="#" onclick="print_ticket_message(<?php echo $ticket->ticketid; ?>, 'ticket'); return false;" class="mright5"><i class="fa fa-print"></i></a>
                    <?php } ?>

                </div>
            </div>


            <hr />
            <div id="desc_relato" class="tc-content" style="margin-left: 15px; margin-right: 15px; text-justify: auto;">

                <strong class="text-muted" style="text-transform: uppercase;  font-weight: bold;">Descrição: </strong><?php echo check_for_links($ro->report); ?>


            </div>
            <hr />
            <?php
            $campos = [];
            $values_info['campos'] = $this->Categorias_campos_model->get_values($ro->id, 'r.o', '0');
            $this->load->view('gestao_corporativa/categorias_campos/values_info', $values_info);
            ?>

<?php echo "AQUI";?>

            <div class="col-md-12 w-100">
                <?php
                $arquivos = explode(',', $ro->arquivos);
                if (count($arquivos) > 0) {
                    echo '<hr />';
                    foreach ($arquivos as $attachment) {
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
                        }
                    }
                }
                ?>
            </div>
            <?php
            $campos = [];
            $values_info['rel_type'] = 'ro';
            $values_info['sub'] = 'more/'.$ro->id.'/';
            $values_info['campos'] = $this->Categorias_campos_model->get_values($ro->id, 'ro_more');
            if (count($values_info['campos']) > 0) {
                ?>
                <div  class="panel_s col-md-12">
                    <div class="panel-heading">
                        Informações Adicionais
                    </div>
                    <div class="panel-body" id="">
                        <?php
                        $this->load->view('gestao_corporativa/categorias_campos/values_info', $values_info);
                        ?>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</div>
