<?php if (count($cdcs) > 0) { ?>
    <div class="alert alert-info alert-dismissible">
        <p style=""><i class="icon fa fa-check-circle-o"></i> Clique no documento para vincular.</p><br>
        <div class="row">
            <?php foreach ($cdcs as $cdc) { ?>
                <div class="col-md-6">
                    <a href="javascript:void(0);" onclick="select_cdc('<?php echo $cdc['id']; ?>');">
                        <div class="wrimagecard wrimagecard-topimage">
                            <div class="wrimagecard-topimage_header" style="background-color: #f0f5ff; ">
                                <center><i class="fa fa-file-pdf-o" style="color: blue; font-size: 30px;"></i></center>
                            </div>

                            <div class="wrimagecard-topimage_title" style="text-align: center;">
                                <span class="bold"><?php echo $cdc['codigo']; ?></span> <br>
                                <?php echo $cdc['titulo']; ?>

                            </div>

                        </div>
                    </a>
                </div>

            <?php }
            ?>

        </div>
    </div>
<?php } else { ?>
    <div class="alert alert-danger alert-dismissible">
        <h5 style=""><i class="icon fa fa-exclamation-circle"></i> Documentos não encontrados.</h5>
    </div>
<?php } ?>

