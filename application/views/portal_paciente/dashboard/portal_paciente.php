<!--
=========================================================
* Material Dashboard 2 PRO - v3.0.6
=========================================================

* Product Page:  https://www.creative-tim.com/product/material-dashboard-pro 
* Copyright 2023 Creative Tim (https://www.creative-tim.com)
* Coded by Creative Tim

=========================================================

* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
-->
<!DOCTYPE html>
<html lang="en" translate="no">
<?php $this->load->view('portal/includes/head'); ?>

<body class="g-sidenav-show  bg-gray-200">


    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NKDMSK6" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>

    <?php $this->load->view('portal/includes/menu'); ?>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">

        <?php $this->load->view('portal/includes/navbar'); ?>

        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 position-relative z-index-2">
                    <div class="row mt-md-0">
                        <div class="col-12 col-sm-12">
                            <div class="alert alert-dismissible alert-success fade show" style="" role="alert">
                                <span class="alert-icon align-middle">
                                    <span class="material-icons text-md text-white">
                                        sentiment_satisfied_alt
                                    </span>
                                </span>
                                <span class="alert-text text-white">
                                    <strong>
                                        Bem-vindo(a), <?php echo $info_perfil->nome_completo; ?>!
                                    </strong>
                                </span>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <?php if ($master == '2') { ?>
                            <div class="alert alert-dismissible alert-info fade show" style="" role="alert">
                                <a class="nav-link" href="<?php echo base_url('portal/financeiro/index/' . $master) ?> ">
                                  
                                   <span class="alert-text text-white">  <i class="fas fa-hand-holding-usd"></i> Acessar Boletos </span>
                                </a>
                               
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <?php } ?>
                        </div>
                        <div class="col-lg-8 col-md-12 ">
                            <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
                                <div class="carousel-inner ">
                                    <div class="carousel-item active">
                                        <div class="page-header min-vh-50 border-radius-xl" style="  background-repeat: no-repeat;  background-size:100%; background-image: url('<?php echo base_url(); ?>assets/portal/img/unimed/banner_1.jpg');">

                                        </div>
                                    </div>
                                    <div class="carousel-item">
                                        <div class="page-header min-vh-50 border-radius-xl" style="  background-repeat: no-repeat;  background-size:100%;background-image: url('<?php echo base_url(); ?>assets/portal/img/unimed/banner_2.jpg');">

                                        </div>
                                    </div>
                                    <div class="carousel-item ">
                                        <div class="page-header min-vh-50 border-radius-xl" style="  background-repeat: no-repeat;  background-size:100%; background-image: url('<?php echo base_url(); ?>assets/portal/img/unimed/banner_3.jpg');">

                                        </div>
                                    </div>
                                    <div class="carousel-item">
                                        <div class="page-header min-vh-50 border-radius-xl" style="  background-repeat: no-repeat;  background-size:100%; background-image: url('<?php echo base_url(); ?>assets/portal/img/unimed/banner_4.png');">

                                        </div>
                                    </div>
                                    <div class="carousel-item">
                                        <div class="page-header min-vh-50 border-radius-xl" style="  background-repeat: no-repeat;  background-size:100%; background-image: url('<?php echo base_url(); ?>assets/portal/img/unimed/banner_5.gif');">

                                        </div>
                                    </div>
                                </div>
                                <div class="min-vh-50 position-absolute w-100 top-0">
                                    <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-bs-slide="prev">
                                        <span class="carousel-control-prev-icon position-absolute bottom-50" aria-hidden="true"></span>
                                        <span class="visually-hidden">Previous</span>
                                    </a>
                                    <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-bs-slide="next">
                                        <span class="carousel-control-next-icon position-absolute bottom-50" aria-hidden="true"></span>
                                        <span class="visually-hidden">Next</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-12">
                            <div class="row">
                                <div class="col-md-6 col-sm-6 col-6  mt-md-4">
                                    <div class="card" data-animation="true">
                                        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                                            <a class="d-block blur-shadow-image">
                                                <img src="<?php echo base_url(); ?>assets/portal/img/unimed/link_1.jpg" alt="img-blur-shadow" class="img-fluid shadow border-radius-lg">
                                            </a>
                                            <div class="colored-shadow" style="background-image: url(&quot<?php echo base_url(); ?>assets/portal/img/unimed/product-1-min.jpg&quot;);"></div>
                                        </div>
                                        <div class="card-body text-center">
                                            <button class="d-flex mt-n6 mx-auto btn btn-link text-info me-auto border-0 text-center" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Acessar">
                                                <a href="http://viverbem.unimedmanaus.com.br/" target="_blank"> <i class="material-icons text-lg">visibility</i></a>
                                            </button>
                                            <h8 class="font-weight-normal mt-3">
                                                <a href="http://viverbem.unimedmanaus.com.br/" target="_blank">Viver Bem</a>
                                            </h8>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-6  mt-md-4">
                                    <div class="card" data-animation="true">
                                        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                                            <a class="d-block blur-shadow-image">
                                                <img src="<?php echo base_url(); ?>assets/portal/img/unimed/link_2.jpg" alt="img-blur-shadow" class="img-fluid shadow border-radius-lg">
                                            </a>
                                            <div class="colored-shadow" style="background-image: url(&quot<?php echo base_url(); ?>assets/portal/img/unimed/product-1-min.jpg&quot;);"></div>
                                        </div>
                                        <div class="card-body text-center">
                                            <button class="d-flex mt-n6 mx-auto btn btn-link text-info me-auto border-0 text-center" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Acessar">
                                                <a href="http://clube.unimedmanaus.com.br/" target="_blank"><i class="material-icons text-lg">visibility</i></a>
                                            </button>
                                            <h8 class="font-weight-normal mt-3">
                                                <a href="http://clube.unimedmanaus.com.br/" target="_blank">Vantagens</a>
                                            </h8>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-6 mt-5 mt-md-4">
                                    <div class="card" data-animation="true">
                                        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2" style="text-align: center;">
                                            <a class="d-block blur-shadow-image">
                                                <img src="<?php echo base_url(); ?>assets/portal/img/unimed/link_3.jpg" alt="img-blur-shadow" class="img-fluid shadow border-radius-lg">
                                            </a>
                                            <div class="colored-shadow" style="background-image: url(&quot<?php echo base_url(); ?>assets/portal/img/unimed/product-1-min.jpg&quot;);"></div>
                                        </div>
                                        <div class="card-body text-center">
                                            <button class="d-flex mt-n6 mx-auto btn btn-link text-info me-auto border-0 text-center" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Acessar">
                                                <a href="https://www.unimed.coop.br/site/guia-medico/#/" target="_blank"> <i class="material-icons text-lg">visibility</i> </a>
                                            </button>
                                            <h8 class="font-weight-normal mt-3">
                                                <a href="https://www.unimed.coop.br/site/guia-medico/#/" target="_blank">Guia Médico</a>
                                            </h8>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-6 mt-5 mt-md-4">
                                    <div class="card" data-animation="true">
                                        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2" style="text-align: center;">
                                            <a class="d-block blur-shadow-image">
                                                <img src="<?php echo base_url(); ?>assets/portal/img/unimed/link_4.jpg" alt="img-blur-shadow" class="img-fluid shadow border-radius-lg">
                                            </a>
                                            <div class="colored-shadow" style="background-image: url(&quot<?php echo base_url(); ?>assets/portal/img/unimed/product-1-min.jpg&quot;);"></div>
                                        </div>
                                        <div class="card-body text-center">
                                            <button class="d-flex mt-n6 mx-auto btn btn-link text-info me-auto border-0 text-center" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Acessar">
                                                <a href="https://portaloperadora.unimedmanaus.com.br/PlanodeSaude/pls_paginaGuiaMedico.jsp" target="_blank"> <i class="material-icons text-lg">visibility</i></a>
                                            </button>
                                            <h8 class="font-weight-normal mt-3">
                                                <a href="https://portaloperadora.unimedmanaus.com.br/PlanodeSaude/pls_paginaGuiaMedico.jsp" target="_blank">Guia Médico</a>
                                            </h8>
                                        </div>

                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>


                    <div class="row mt-5">
                        <div class="col-lg-3 col-md-12 col-sm-12">
                            <div class="card" data-animation="true">
                                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                                    <a class="d-block blur-shadow-image" href="<?php echo base_url(); ?>portal/dashboard/single/1">
                                        <img src="<?php echo base_url(); ?>assets/portal/img/unimed/noticia_sm_1.jpg" alt="img-blur-shadow" class="img-fluid shadow border-radius-lg">
                                    </a>
                                    <div class="colored-shadow" style="background-image: url(&quot;<?php echo base_url(); ?>assets/portal/img/unimed/product-2-min.jpg&quot;);"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-12 col-sm-12 mt-5 mt-lg-0">
                            <div class="card" data-animation="true">
                                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                                    <a class="d-block blur-shadow-image" href="<?php echo base_url(); ?>portal/dashboard/single/2">
                                        <img src="<?php echo base_url(); ?>assets/portal/img/unimed/noticia_sm_2.jpg" alt="img-blur-shadow" class="img-fluid shadow border-radius-lg">
                                    </a>
                                    <div class="colored-shadow" style="background-image: url(&quot;<?php echo base_url(); ?>assets/portal/img/unimed/product-2-min.jpg&quot;);"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-12 col-sm-12 mt-5 mt-lg-0">
                            <div class="card" data-animation="true">
                                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                                    <a class="d-block blur-shadow-image" href="<?php echo base_url(); ?>portal/dashboard/single/3">
                                        <img src="<?php echo base_url(); ?>assets/portal/img/unimed/noticia_sm_3.jpg" alt="img-blur-shadow" class="img-fluid shadow border-radius-lg">
                                    </a>
                                    <div class="colored-shadow" style="background-image: url(&quot;<?php echo base_url(); ?>assets/portal/img/unimed/product-2-min.jpg&quot;);"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-12 col-sm-12 mt-5 mt-lg-0">
                            <div class="card" data-animation="true">
                                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                                    <a class="d-block blur-shadow-image" href="<?php echo base_url(); ?>portal/dashboard/single/4">
                                        <img src="<?php echo base_url(); ?>assets/portal/img/unimed/noticia_sm_4.jpg" alt="img-blur-shadow" class="img-fluid shadow border-radius-lg">
                                    </a>
                                    <div class="colored-shadow" style="background-image: url(&quot;<?php echo base_url(); ?>assets/portal/img/unimed/product-2-min.jpg&quot;);"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <footer class="footer py-4  ">
                <div class="container-fluid">
                    <div class="row align-items-center justify-content-lg-between">
                        <div class="col-lg-6 mb-lg-0 mb-4">
                            <div class="copyright text-center text-sm text-muted text-lg-start">
                                © <script>
                                    document.write(new Date().getFullYear())
                                </script>,
                                made with <i class="fa fa-heart"></i> by
                                <a href="" class="font-weight-bold" target="_blank">Sigplus</a>/<a href="" class="font-weight-bold" target="_blank">Unimed Manaus</a>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <ul class="nav nav-footer justify-content-center justify-content-lg-end">
                                <li class="nav-item">
                                    <a href="" class="nav-link pe-0 text-muted" target="_blank">Dúvidas Frequentes</a>
                                </li>
                                <li class="nav-item">
                                    <a href="" class="nav-link pe-0 text-muted" target="_blank">Sobre Nós</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </main>


    <script src="<?php echo base_url(); ?>assets/portal/js/core/popper.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/portal/js/core/bootstrap.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/portal/js/plugins/perfect-scrollbar.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/portal/js/plugins/smooth-scrollbar.min.js"></script>

    <script src="<?php echo base_url(); ?>assets/portal/js/plugins/dragula/dragula.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/portal/js/plugins/jkanban/jkanban.js"></script>
    <script src="<?php echo base_url(); ?>assets/portal/js/plugins/chartjs.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/portal/js/plugins/world.js"></script>


    <script async defer src="https://buttons.github.io/buttons.js"></script>

    <script src="<?php echo base_url(); ?>assets/portal/js/material-dashboard.min.js?v=3.0.6"></script>
    <script defer src="https://static.cloudflareinsights.com/beacon.min.js/v52afc6f149f6479b8c77fa569edb01181681764108816" integrity="sha512-jGCTpDpBAYDGNYR5ztKt4BQPGef1P0giN6ZGVUi835kFF88FOmmn8jBQWNgrNd8g/Yu421NdgWhwQoaOPFflDw==" data-cf-beacon='{"rayId":"7d99751e491f21d4","version":"2023.4.0","r":1,"token":"1b7cbb72744b40c580f8633c6b62637e","si":100}' crossorigin="anonymous"></script>
</body>

</html>