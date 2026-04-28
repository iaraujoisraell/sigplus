<div class="content-wrapper">
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <div class="container-fluid py-4" >
            <div class="row mt-4">
                <div class="col-xl-10" style=" margin: 0 auto;">
                    <div class="card">
                        <div class="card-header">
                            <div class="page-title">
                                <h1><?php echo $documento->titulo; ?>
                                </h1>

                                <?php echo $documento->descricao; ?>
                            </div>

                        </div>

                        <div class="card-body">
                            <div class="row">

                                <?php
                                $docs = explode(",", $documento->file);
                                $pasta_destino = trim($documento->pasta_destino);
                                $media = 'media';
                                $file = $documento->file;
                                $lido = $documento->lido;
                                //echo $lido;
                                $url_arquivo = base_url($media . $pasta_destino . $file);
                                if (count($docs) > 0):
                                    foreach ($docs as $doc):
                                        $tipo = explode(".", $doc);
                                        for ($i = 0; $i < count($tipo); $i++) {
                                            $extensao = $tipo[$i];
                                        }
                                        ?>
                                        <div class="col-6 col-md-4 col-xl-3 col-xxl-2">
                                            <div class="app-card app-card-doc shadow-sm h-100">
                                                <div class="app-card-thumb-holder p-3" ><a href="<?php echo $url_arquivo; ?>" target="_blank">
                                                        <?php if ($extensao == 'pdf') { ?>
                                                            <span class="icon-holder">
                                                                <i class="fas fa-file-pdf pdf-file"></i>
                                                            </span>
                                                        <?php } elseif ($extensao == 'xlsx') { ?>
                                                            <span class="icon-holder">
                                                                <i class="fas fa-file-excel excel-file"></i>
                                                            </span>
                                                        <?php } elseif ($extensao == 'zip') { ?>
                                                            <span class="icon-holder">
                                                                <i class="fas fa-file-archive zip-file"></i>
                                                            </span>
                                                        <?php } elseif ($extensao == 'mp4' or $extensao == 'gif') { ?>
                                                            <span class="icon-holder">
                                                                <i class="fas fa-file-video video-file"></i>
                                                            </span>
                                                        <?php } elseif ($extensao == 'jpg' or $extensao == 'jpeg' or $extensao == 'png') { ?>
                                                            <IMG class="w-100 h-100" src="<?php echo base_url("assets/intranet/img/ci/$doc"); ?>">
                                                        <?php } elseif ($extensao == 'txt') { ?>
                                                            <span class="icon-holder">
                                                                <i class="fas fa-file-alt text-file"></i>
                                                            </span>
                                                        <?php } elseif ($extensao == 'ppsx' or $extensao == 'ppt') { ?>
                                                            <span class="icon-holder">
                                                                <i class="fas fa-file-powerpoint ppt-file"></i>
                                                            </span>
                                                        <?php } ?>
                                                        <?php if ($new == true): ?>
                                                            <span class="badge bg-success">NOVO</span>
                                                        <?php endif; ?>
                                                    </a>
                                                </div>
                                                <div class="app-card-body p-3 has-card-actions">

                                                    <h4 class="app-doc-title truncate mb-0"><a href="<?php echo $url_arquivo; ?>" target="_blanck"> <?php echo $doc; ?></a></h4>
                                                    <div class="app-doc-meta">
                                                        <ul class="list-unstyled mb-0">
                                                            <li><span class="text-muted">Tipo:</span> <?php echo $extensao; ?></li>
                                                        </ul>
                                                    </div><!--//app-doc-meta-->

                                                </div>

                                            </div><!--//app-card-->

                                            <br>
                                        </div><!--//col-->
                                    <?php
                                    endforeach;
                                endif;
                                ?>
                            </div>
                        </div>
                        <div class="card-footer">
                           <!-- <span class="text-sm col--md-8">Criado por: <?php echo $staff->firstname . ' ' . $staff->lastname; ?>, <?php
                            $DataEspecifica = new DateTime($documento->data_cadastro);
                            echo $DataEspecifica->format('d-m-Y');
                            ?>.
                            </span> -->
                            <br>
                            <?php if ($documento->lido == 1) { ?>
                                <a type="button" class="btn btn-warning col-md-4" href="<?php echo base_url('admin'); ?>">Fechar</a>  
<?php } else { ?>
                                <a type="button" class="btn btn-info col-md-4" href="<?php echo base_url(); ?>gestao_corporativa/intra/Documentos/ciente/<?php echo $documento->send_id; ?>">Estou Ciente</a>

<?php } ?>
                        </div>
                    </div>
                </div>

            </div>


        </div>
    </main>
</div>

<style>
            

            .app-card .app-card-header{
                border-bottom:1px solid #e7e9ed
            }
            .app-card .app-card-title{
                font-size:1.125rem;
                margin-bottom:0
            }
            .app-card .card-header-action{
                font-size:.875rem
            }
            .app-card .card-header-action a:hover{
                text-decoration:underline
            }
            .app-card .form-select-holder{
                display:inline-block
            }
            .app-card .btn-close{
                padding:1rem
            }
            .app-card .btn-close:focus{
                box-shadow:none
            }
            .app-card-stat{
                text-align:center
            }
            .app-card-stat .stats-type{
                font-size:.875rem;
                color:#828d9f;
                text-transform:uppercase
            }
            .app-card-stat .stats-figure{
                font-size:2rem;
                color:#252930
            }
            .app-card-stat .stats-meta{
                font-size:.875rem;
                color:#828d9f
            }
            .app-card-progress-list .item{
                position:relative;
                border-bottom:1px solid #e7e9ed
            }
            .app-card-progress-list .item:hover .title{
                color:#252930
            }
            .app-card-progress-list .item:last-child{
                border:none
            }
            .app-card-progress-list .item .title{
                font-size:.875rem;
                font-weight:500
            }
            .app-card-progress-list .item .meta{
                font-size:.875rem;
                color:#828d9f
            }
            .app-card-progress-list .item-link-mask{
                position:absolute;
                width:100%;
                height:100%;
                display:block;
                left:0;
                top:0
            }
            .app-card-progress-list .progress{
                height:.5rem
            }
            .app-card-stats-table .table{
                font-size:.875rem
            }
            .app-card-stats-table .meta{
                color:#828d9f;
                font-weight:500;
                font-size:.875rem
            }
            .app-card-stats-table .stat-cell{
                text-align:right
            }
            .app-card-basic{
                height:100%
            }
            .app-card-basic .title{
                font-size:1rem
            }
            .app-card .app-icon-holder{
                display:inline-block;
                background:#edfdf6;
                color:#15a362;
                width:50px;
                height:50px;
                padding-top:10px;
                font-size:1rem;
                text-align:center;
                border-radius:50%
            }
            .app-card .app-icon-holder.icon-holder-mono{
                background:#f5f6fe;
                color:#828d9f
            }
            .app-card .app-icon-holder svg{
                width:24px;
                height:24px
            }
            .app-card .app-card-body.has-card-actions{
                position:relative;
                padding-right:1rem !important
            }
            .app-card .app-card-body .app-card-actions{
                display:inline-block;
                width:30px;
                height:30px;
                text-align:center;
                border-radius:50%;
                position:absolute;
                z-index:10;
                right:.75rem;
                top:.75rem
            }
            .app-card .app-card-body .app-card-actions:hover{
                background:#f5f6fe
            }
            .app-card .app-card-body .app-card-actions .dropdown-menu{
                font-size:.8125rem
            }
            .app-card-doc:hover{
                box-shadow:0 .5rem 1rem rgba(0,0,0,.15) !important
            }
            .app-card-doc .app-card-thumb-holder{
                background:#e9eaf1;
                text-align:center;
                position:relative;
                height:112px
            }
            .app-card-doc .app-card-thumb-holder .app-card-thumb{
                overflow:hidden;
                position:absolute;
                left:0;
                top:0;
                width:100%;
                height:100%;
                background:#000
            }
            .app-card-doc .app-card-thumb-holder .thumb-image{
                -webkit-opacity:.7;
                -moz-opacity:.7;
                opacity:.7;
                width:100%;
                height:auto
            }
            .app-card-doc .app-card-thumb-holder:hover{
                background:#fafbff
            }
            .app-card-doc .app-card-thumb-holder:hover .thumb-image{
                -webkit-opacity:1;
                -moz-opacity:1;
                opacity:1
            }
            .app-card-doc .app-card-thumb-holder .badge{
                position:absolute;
                right:.5rem;
                top:.5rem
            }
            .app-card-doc .app-card-thumb-holder .icon-holder{
                font-size:40px;
                display:inline-block;
                margin:0 auto;
                width:80px;
                height:80px;
                border-radius:50%;
                background:#fff;
                padding-top:10px
            }
            .app-card-doc .app-card-thumb-holder .icon-holder .pdf-file{
                color:#da2d27
            }
            .app-card-doc .app-card-thumb-holder .icon-holder .text-file{
                color:#66a0fd
            }
            .app-card-doc .app-card-thumb-holder .icon-holder .excel-file{
                color:#0da95f
            }
            .app-card-doc .app-card-thumb-holder .icon-holder .ppt-file{
                color:#f4b400
            }
            .app-card-doc .app-card-thumb-holder .icon-holder .video-file{
                color:#935dc1
            }
            .app-card-doc .app-card-thumb-holder .icon-holder .zip-file{
                color:#252930
            }
            .app-card-doc .app-doc-title{
                font-size:.875rem
            }
            .app-card-doc .app-doc-title a{
                color:#252930
            }
            .app-card-doc .app-doc-title.truncate{
                max-width:calc(100% - 30px);
                display:inline-block;
                overflow:hidden;
                text-overflow:ellipsis;
                white-space:nowrap
            }
            .app-card-doc .app-doc-meta{
                font-size:.75rem
            }
            .table-search-form .form-control{
                height:2rem;
                min-width:auto
            }
            .app-dropdown-menu{
                font-size:.875rem
            }
            .app-card-orders-table .table{
                font-size:.875rem
            }
            .app-card-orders-table .table .cell{
                border-color:#e7e9ed;
                color:#5d6778;
                vertical-align:middle
            }
            .app-card-orders-table .cell span{
                display:inline-block
            }
            .app-card-orders-table .cell .note{
                display:block;
                color:#828d9f;
                font-size:.75rem
            }
            .app-card-orders-table .btn-sm,.app-card-orders-table .btn-group-sm>.btn{
                padding:.125rem .5rem;
                font-size:.75rem
            }
            .app-card-orders-table .truncate{
                max-width:250px;
                display:inline-block;
                overflow:hidden;
                text-overflow:ellipsis;
                white-space:nowrap
            }
            .app-nav-tabs{
                background:#fff;
                padding:0
            }
            .app-nav-tabs .nav-link{
                color:#5d6778;
                font-size:.875rem;
                font-weight:bold
            }
            .app-nav-tabs .nav-link.active{
                color:#15a362;
                border-bottom:2px solid #15a362
            }
            .app-nav-tabs .nav-link.active:hover{
                background:none
            }
            .app-nav-tabs .nav-link:hover{
                background:#edfdf6;
                color:#15a362
            }
            .app-pagination .pagination{
                font-size:.875rem
            }
            .app-pagination .pagination .page-link{
                color:#5d6778;
                padding:.25rem .5rem
            }
            .app-pagination .pagination .page-item.active .page-link{
                background:#747f94;
                color:#fff;
                border-color:#747f94
            }
            .app-pagination .pagination .page-item.disabled .page-link{
                color:#9fa7b5
            }
            .app-card-accordion .app-card-title{
                font-size:1.125rem
            }
            .app-card-accordion .faq-accordion .accordion-item{
                border-radius:0;
                border:none;
                border-bottom:1px solid #e7e9ed
            }
            .app-card-accordion .faq-accordion .accordion-item:last-child{
                border-bottom:none
            }
            .app-card-accordion .faq-accordion .accordion-header{
                border:none
            }
            .app-card-accordion .faq-accordion .accordion-button{
                padding:1rem;
                border-radius:0;
                border:none;
                box-shadow:none;
                background:none;
                padding-left:0;
                font-size:1rem;
                text-decoration:none;
                color:#15a362
            }
            .app-card-accordion .faq-accordion .accordion-button:after{
                display:none
            }
            .app-card-accordion .faq-accordion .accordion-body{
                padding-left:0;
                padding-right:0;
                padding-top:0;
                font-size:1rem
            }
            .app-card-account{
                height:100%
            }
            .app-card-account .item{
                font-size:.875rem
            }
            .app-card-account .item .profile-image{
                width:60px;
                height:60px
            }
            .app-card-account .item .btn-sm,.app-card-account .item .btn-group-sm>.btn{
                padding:.125rem .5rem;
                font-size:.75rem
            }
            .settings-section .section-title{
                font-size:1.25rem
            }
            .settings-section .section-intro{
                font-size:.875rem
            }
            .app-card-settings{
                font-size:1rem
            }
            .app-card-settings .form-label{
                font-weight:bold
            }
            .app-card-settings .form-control{
                font-size:1rem
            }
            .app-404-page{
                padding-top:2rem
            }
            .app-404-page .page-title{
                font-size:3rem;
                line-height:.8;
                font-weight:bold
            }
            .app-404-page .page-title span{
                font-size:1.5rem
            }
            .chart-container{
                position:relative
            }
            .app-table-hover>tbody>tr:hover{
                background-color:#fafbff
            }
            .app-card-notification .notification-type .badge{
                font-size:.65rem;
                text-transform:uppercase
            }
            .app-card-notification .profile-image{
                width:60px;
                height:60px
            }
            .app-card-notification .notification-title{
                font-size:1.125rem
            }
            .app-card-notification .notification-content{
                font-size:.875rem
            }
            .app-card-notification .notification-meta{
                font-size:.75rem;
                color:#828d9f
            }
            .app-card-notification .action-link{
                font-size:.875rem
            }
            .app-card-notification .app-card-footer{
                background:#fafbff
            }
            @media(min-width: 1200px){
                .table-search-form .form-control{
                    min-width:300px
                }
            }
            @media(max-width: 575.98px){
                .app-card-stat .stats-figure{
                    font-size:1.125rem
                }
                .app-card-stat .stats-type{
                    font-size:.75rem
                }
            }
        </style>
