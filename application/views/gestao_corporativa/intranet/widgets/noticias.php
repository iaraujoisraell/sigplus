<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="widget relative" id="widget-<?php echo create_widget_id(); ?>" data-name="<?php echo _l('quick_stats'); ?>">

    <div class="card card-default">
        <div class="card-header">
            <a style="background-color: white; color: black;" href="<?php echo base_url('gestao_corporativa/intra/Pubs/ver_todas') ?>"> <h3 class="card-title">Notícias</h3></a>

            <div class="card-tools">
                <span class="badge badge-warning"><?php echo count($noticia); ?></span>

            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <?php
                setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
                date_default_timezone_set('America/Sao_Paulo');
                foreach ($noticia as $n):
                    $titulo = $n['titulo'];
                    $texto = $n['descricao'];
                    $tam = strlen($texto); // Verifica o tamanho do texto.
                    $max = 50; // exibirá apenas os 400 primeiros caracteres de um texto.

                    if ($tam > $max) { // Se o texto for maior do que 400, retira o restante.
                        $conteudo = substr($texto, 0, $max - $tam);
                    }
                    ?>

                    <!--<div class=" d-flex flex-column justify-content-end">
                        <a style="background-color: white; color: black;"  href="<?php echo base_url('gestao_corporativa/intra/Pubs/ver_aviso') ?>?id=<?= $n['id'] ?>"><?php echo $titulo; ?></a>
                        <font style="font-size: 10px">(<?php echo strftime('%d de %B de %Y', strtotime($n['data_cadastro'])); ?>)</font>
                    </div>-->

                    <div class="card mb-2 bg-gradient-dark" onclick='window.open("<?php echo base_url('gestao_corporativa/intra/Pubs/ver_aviso/');?>?id=<?= $n['id'] ?>", "_blank");'>
                        <img class="card-img-top" src="<?php echo base_url();?>assets/intranet/img/avisos/<?php echo $n['foto']; ?>" >
                        <div class="card-img-overlay d-flex flex-column justify-content-end">
                            <h5 class="card-title text-primary text-white"><?php echo $titulo; ?></h5>
                            <!--<a class="card-text text-white pb-2 pt-1" href="<?php echo $n['link']; ?>" target="_blank"><?php //echo $conteudo; ?>...</a>-->
                        </div>
                    </div>






                    <?php
                endforeach;
                ?>
            </div>
        </div>
    </div>
</div>