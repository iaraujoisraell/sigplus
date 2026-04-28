
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

                        <div class="row">
                            <?PHP if (is_file('assets/portal/cooperados/acoes/' . $this->session->userdata('cd_pessoa') . '.pdf')) { ?>

                                <div class="col-md-3 col-6">
                                    <a href="<?php echo base_url('assets/portal/cooperados/acoes/' . $this->session->userdata('cd_pessoa') . '.pdf'); ?>" target="_blank">
                                        <div class="card">
                                            <div class="card-header mx-4 p-3 text-center">
                                                <div class="icon icon-shape icon-lg bg-gradient-info shadow text-center border-radius-lg">
                                                    <i class="material-icons opacity-10">picture_as_pdf</i>
                                                </div>
                                            </div>
                                            <div class="card-body pt-0 p-3 text-center">
                                                <h6 class="text-center mb-0">Ações 2024</h6>
                                                <span class="text-xs"><?php echo $this->session->userdata('cd_pessoa') . '.pdf';?></span>
                                            </div>
                                        </div>
                                    </a>
                                </div>

                            <?php } ?>
                            <?PHP 
                            $permission = false;
                            $file = $this->session->userdata('cd_pessoa');
                            if (is_file('assets/portal/cooperados/cotacapital/' . $file . '.pdf')) {
                                $permission = true;
                            } elseif (is_file('assets/portal/cooperados/cotacapital/' . substr($file, 0, -1) . '.pdf')) {
                                $permission = true;
                                $file = substr($file, 0, -1);
                            }
                            if ($permission == true) { ?>

                                <div class="col-md-3 col-6">
                                    <a href="<?php echo base_url('assets/portal/cooperados/cotacapital/' . $file . '.pdf'); ?>" target="_blank">
                                        <div class="card">
                                            <div class="card-header mx-4 p-3 text-center">
                                                <div class="icon icon-shape icon-lg bg-gradient-info shadow text-center border-radius-lg">
                                                    <i class="material-icons opacity-10">picture_as_pdf</i>
                                                </div>
                                            </div>
                                            <div class="card-body pt-0 p-3 text-center">
                                                <h6 class="text-center mb-0">Cota Capital 2024</h6>
                                                <span class="text-xs"><?php echo $this->session->userdata('cd_pessoa') . '.pdf';?></span>
                                            </div>
                                        </div> 
                                    </a>
                                </div>

                            <?php } ?>
                            <?PHP if (is_file('assets/portal/cooperados/informeirpf/' . $this->session->userdata('cd_pessoa') . '.pdf')) { ?>

                                <div class="col-md-3 col-6"> 
                                    <a href="<?php echo base_url('assets/portal/cooperados/informeirpf/' . $this->session->userdata('cd_pessoa') . '.pdf'); ?>" target="_blank">
                                        <div class="card">
                                            <div class="card-header mx-4 p-3 text-center">
                                                <div class="icon icon-shape icon-lg bg-gradient-info shadow text-center border-radius-lg">
                                                    <i class="material-icons opacity-10">picture_as_pdf</i>
                                                </div>
                                            </div>
                                            <div class="card-body pt-0 p-3 text-center">
                                                <h6 class="text-center mb-0">Informe IRPF</h6>
                                                <span class="text-xs"><?php echo $this->session->userdata('cd_pessoa') . '.pdf';?></span>
                                            </div>
                                        </div> 
                                    </a>
                                </div>

                            <?php } ?>

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


                    <script src="<?php echo base_url(); ?>assets/portal/js/core/bootstrap.min.js"></script>
                    <script src="<?php echo base_url(); ?>assets/portal/js/plugins/perfect-scrollbar.min.js"></script>
                    <script src="<?php echo base_url(); ?>assets/portal/js/plugins/smooth-scrollbar.min.js"></script>



                    <script src="<?php echo base_url(); ?>assets/portal/js/material-dashboard.min.js?v=3.0.6"></script>
                    </body>
                    </html>