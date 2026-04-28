<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="widget relative" id="widget-<?php echo create_widget_id(); ?>" data-name="<?php echo _l('quick_stats'); ?>">
  

    <!--<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script> -->
    <style>
        /* Make the image fully responsive */
        .carousel-inner img {
            width: 100%;
            height: 100%;
        }
        .carousel-inner {
            text-align: center;
            
        }
        

    </style>
    <div class="card">
    <div id="demo" class="carousel slide" data-ride="carousel" >

            <!-- Indicators -->
            <ul class="carousel-indicators">
                <div class="carousel-inner">
                    <?php
                    $total = count($banner);

                    if ($total > 0) {
                        $i = 0;
                        foreach ($banner as $bann) {

                            
                            if ($i == 0) {
                                $a = 'active';
                            } else {
                                $a = '';
                            }
                            ?>
                            <li data-target="#demo" data-slide-to="<?php echo $i; ?>" class="<?php echo $a; ?>"></li>
                            <?php
                            $i++;
                        }
                    } else {
                        ?>
                        <li data-target="#demo" data-slide-to="padrao" class="active"></li>
                    <?php } ?>
                </div>

            </ul>

            <!-- The slideshow -->
            <div class="carousel-inner">
                <div class="carousel-inner">
                    <?php
                    if ($total > 0) {
                        $i = 0;
                        foreach ($banner as $bann) {

                            $separado = explode('.', $bann->foto);
                            $tipo = end($separado);
                            if ($i == 0) {
                                $a = 'active';
                            } else {
                                $a = '';
                            }
                            ?>
                            <div class="carousel-item <?php echo $a; ?>">
                                <?php if($tipo == 'mp4'){;?>
                                <iframe width="1100" height="500" src="https://www.youtube.com/embed/3BH0A6dWhj0" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                <?php 
                                } else {?>
                                <img src="<?php echo base_url(); ?>assets/intranet/img/avisos/<?php echo $bann->foto; ?>" alt="<?php echo $bann->titulo; ?>" width="1100" height="500">
                                <?php }?>
                            </div>
                            <?php
                            $i++;
                        }
                    } else {
                        ?>
                        <div class="carousel-item active">
                            <img src="<?php echo base_url(); ?>assets/intranet/img/avisos/intranet_m.jpeg" alt="padrao">
                        </div>
                    <?php } ?>
                </div>
            </div>


            <!-- Left and right controls -->
            <a class="carousel-control-prev " href="#demo" data-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </a>
            <a class="carousel-control-next" href="#demo" data-slide="next">
                <span class="carousel-control-next-icon"></span>
            </a>
        </div>
   </div>
</div>
