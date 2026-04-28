
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


                            <div class="col-md-12 col-12">
                                <div class="card mb-0">
                                    <div class="card-header">

                                        <h6 class="mb-0">Ficha Financeira - Relatório de Perdas
                                            <a class="text-right" style=" position: absolute;right: 25px;" href="<?php echo base_url(); ?>portal/dashboard/financial_pdf" target="_blank">
                                                <i class="material-icons-round opacity-10">picture_as_pdf</i>
                                            </a>
                                        </h6>

                                    </div>
                                    <div class="card-body mt-0">
                                        <div class="row bg-gray-100 border-radius-lg p-3">



                                            <span class="col-4 text-xs mb-2 ">Dr(a) <span class="text-dark ms-2 font-weight-bold"><?php echo strtoupper($info_perfil->company); ?></span></span>
                                            <span class="col-4 text-xs mb-2 ">CPF: <span class="text-dark font-weight-bold ms-2"><?php echo $info_perfil->vat; ?></span></span>
                                            <span class="col-4 text-xs mb-2 ">Código: <span class="text-dark ms-2 font-weight-bold"><?php echo $info_perfil->cd_pessoa; ?></span></span>

                                        </div>
                                    </div>
                                </div>
                                <?php foreach ($financial['detailed'] as $title) { ?>
                                    <div class="card mt-5">
                                        <div class="card-header mx-4 p-3 font-weight-bold badge badge-info">
                                            PERDAS <?php echo $title['TITULO']; ?>

                                        </div>
                                        <div class="card-body pt-0 p-3 ">
                                            <br>
                                            <p class="text-dark ms-2 font-weight-bold text-xs text-center"><?php echo $title['TITULO2']; ?></p>
                                            <table class="table table-flush text-center" id="datatable-search">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th>ANO</th>
                                                        <?php foreach ($years as $year) { ?>
                                                            <th><?php echo $year; ?></th>
                                                        <?php } ?>
                                                        <th>TOTAL</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($title['ANOS'] as $info) { ?>
                                                        <tr>
                                                            <td class="text-sm font-weight-normal"><?php echo $info['ANO']; ?></td>
                                                            <?php foreach ($years as $year) { ?>
                                                                <td class="text-sm font-weight-normal"><?php echo $info[$year]; ?></td>
                                                            <?php } ?>
                                                            <td class="text-sm font-weight-normal">
                                                                <span class="badge badge-warning"><?php echo $info['TOTAL']; ?></span>
                                                            </td>

                                                        </tr>
                                                    <?php } ?>

                                                </tbody>
                                            </table>
                                        </div>
                                        <?php if ($financial['general'][$title['TITULO']] == '0') {
                                            ?>
                                            <div class="card-foot mx-4 p-3 font-weight-bold badge badge-warning">
                                                SALDO QUITADO

                                            </div>
                                        <?php } elseif ($financial['general'][$title['TITULO']] < '0') { 
                                            $receive = str_replace('-', '', $financial['general'][$title['TITULO']]);
                                            $_receive = $_receive + $receive;
                                            ?>
                                            <div class="card-foot mx-4 p-3 font-weight-bold badge badge-success">
                                                SALDO A RECEBER: <?php echo formatarParaReais($receive); ?>

                                            </div>
                                        <?php } else { 
                                            $pay = str_replace('-', '', $financial['general'][$title['TITULO']]);
                                            $_pay = $_pay + $pay;?>
                                            <div class="card-foot mx-4 p-3 font-weight-bold badge badge-danger">
                                                SALDO A PAGAR: <?php echo formatarParaReais($pay); ?>

                                            </div>
                                        <?php } ?>

                                        <?php unset($financial['general'][$title['TITULO']]); ?>

                                    </div> 
                                <?php }
                                
                                //print_r($financial['general']); ?>
                                
                                <div class="card mt-5  text-center">
                                      
                                        <div class="card-body pt-0 p-3 ">
                                            <table class="table table-flush" id="datatable-search">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th>SALDO A PAGAR</th>
                                                        <th>SALDO A RECEBER</th>
                                                        <th>SALDO FINAL</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                        <tr>
                                                            <td class="text-sm font-weight-normal">
                                                                <span class="badge badge-danger"><?php echo formatarParaReais($_pay); ?></span>
                                                            </td>
                                                            <td class="text-sm font-weight-normal">
                                                                <span class="badge badge-success"><?php echo formatarParaReais($_receive); ?></span>
                                                            </td>
                                                            <td class="text-sm font-weight-normal">
                                                                <span class="badge badge-warning"><?php echo formatarParaReais($financial['general']['TOTAL']);?></span>
                                                            </td>

                                                        </tr>

                                                </tbody>
                                            </table>
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


                    <script src="<?php echo base_url(); ?>assets/portal/js/core/bootstrap.min.js"></script>
                    <script src="<?php echo base_url(); ?>assets/portal/js/plugins/perfect-scrollbar.min.js"></script>
                    <script src="<?php echo base_url(); ?>assets/portal/js/plugins/smooth-scrollbar.min.js"></script>



                    <script src="<?php echo base_url(); ?>assets/portal/js/material-dashboard.min.js?v=3.0.6"></script>
                    </body>
                    </html>
                    <?php

                    function formatarParaReais($numero) {
                        // Converte a string para um número float
                        $numero_float = (float) $numero;

                        // Formata o número para o formato brasileiro de moeda
                        $numero_formatado = number_format($numero_float, 2, ',', '.');

                        // Adiciona o símbolo de R$ ao início da string
                        return 'R$ ' . $numero_formatado;
                    }
                    ?>