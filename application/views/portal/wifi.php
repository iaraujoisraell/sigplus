
<?php

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="apple-touch-icon" sizes="76x76" href="<?php echo base_url(); ?>assets/portal/img/apple-icon.png">
        <link rel="icon" type="image/png" href="<?php echo base_url(); ?>uploads/company/<?php echo $icon; ?>">
        <title>
            UNIMED MANAUS
        </title>

        <link id="pagestyle" href="<?php echo base_url(); ?>assets/portal/css/material-dashboard.min.css?v=3.0.6" rel="stylesheet" />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <style>
            .async-hide {
                opacity: 0 !important
            }
        </style>
    </head>
    <body class="bg-gray-200">


        <nav class="navbar navbar-expand-lg position-absolute top-0 z-index-3 w-100 shadow-none my-3 navbar-transparent mt-4">
            <div class="container">
                <a class="navbar-brand font-weight-bolder ms-lg-0 ms-3 text-white" href="../../../pages/dashboards/analytics.html">
                    UNIMED MANAUS
                </a>
            </div>
        </nav>

        <main class="main-content  mt-0">
            <div class="page-header align-items-start min-height-300 m-3 border-radius-xl" style="background-image: url('https://images.unsplash.com/photo-1491466424936-e304919aada7?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1949&q=80');">
                <span class="mask bg-gradient-dark opacity-6"></span>
            </div>
            <div class="container mb-4">
                <div class="row mt-lg-n12 mt-md-n12 mt-n12 justify-content-center">
                    <div class="col-xl-4 col-lg-5 col-md-7 mx-auto">
                        <div class="card mt-8">

                            <?php
                            $continue = isset($_GET['continue']) ? $_GET['continue'] : '';
                            $ip = isset($_GET['ip']) ? $_GET['ip'] : '';
                            $ap_mac = isset($_GET['ap_mac']) ? $_GET['ap_mac'] : '';
                            $mac = isset($_GET['mac']) ? $_GET['mac'] : '';
                            $radio = isset($_GET['radio']) ? $_GET['radio'] : '';
                            $ssid = isset($_GET['ssid']) ? $_GET['ssid'] : '';
                            $ts = isset($_GET['ts']) ? $_GET['ts'] : '';
                            $redirect_uri = isset($_GET['redirect_uri']) ? $_GET['redirect_uri'] : '';
                            $user_hash = isset($_GET['user_hash']) ? $_GET['user_hash'] : '';
                            if (!$_GET['userid']) {
                                ?>
                                <?php echo form_open_multipart(base_url("Authentication/wifi/1?ts=$ts&user_hash=$user_hash")); ?>

                                <input type="hidden" name="slug" value="acess_type">

                                <?php
                                $tipos = $this->db->query('select * from tbl_intranet_acess_type where empresa_id = 4')->result_array();
                                // $tipos = [];
                                // echo 'select * from tbl_intranet_acess_type where empresa_id = 4';
                                ?>
                                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                                    <div class="bg-gradient-success shadow-success border-radius-lg py-3 pe-1 text-center py-4">
                                        <h4 class="font-weight-bolder text-white mt-1">WIFI</h4>
                                        <p class="mb-1 text-sm text-white">Selecione o tipo de acesso</p>
                                    </div>
                                </div>
                                <div class="card-body">

                                    <div class="input-group input-group-static mb-1">
                                        <label>Tipo de acesso</label>
                                        <select class="form-control" name="acess_type" id="choices-category" onchange="select(this.value);" required>
                                            <option value="" selected disabled>Nada selecionado</option>
                                            <?php foreach ($tipos as $tipo) { ?>

                                                <option value="<?php echo $tipo['id'] ?>"><?php echo $tipo['name'] ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="" id="trocar">

                                    </div>

                                    <!--
                                    <div class="input-group input-group-static mb-4">
                                        <label>Email</label>
                                        <input type="email" class="form-control" placeholder="john@email.com">
                                    </div>
                                    <div class="input-group input-group-static mb-4">
                                        <label>Password</label>
                                        <input type="password" class="form-control" placeholder="•••••••••••••">
                                    </div>
                                    <div class="form-check form-switch d-flex align-items-center mb-3">
                                        <input class="form-check-input" type="checkbox" id="rememberMe" checked>
                                        <label class="form-check-label mb-0 ms-3" for="rememberMe">Remember me</label>
                                    </div>-->
                                    <div class="text-center">
                                        <button type="submit" class="btn bg-gradient-dark w-100 mt-3 mb-0">Acessar</button>
                                    </div>
                                </div>
                                <?php
                                echo form_close();
                            } else {
                                ?>
                                <?php echo form_open(base_url("portal/signin/login_new/$hash?ts=$ts&user_hash=$user_hash")); ?>

                                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                                    <div class="bg-gradient-success shadow-success border-radius-lg py-3 pe-1 text-center py-4">
                                        <h4 class="font-weight-bolder text-white mt-1">Token de acesso</h4>
                                        <p class="mb-1 text-sm text-white">Por favor, insira o token para acesso ao Wifi</p>
                                    </div>
                                </div>
                                <div class="card-body">




                                    <div class="input-group input-group-static mb-4">
                                        <label>Token</label>
                                        <input type="password" name="token" id="token" class="form-control" placeholder="******">
                                    </div>

                                    <div class="text-center">
                                        <button type="submit" class="btn bg-gradient-dark w-100 mt-3 mb-0">Acessar Wifi</button>
                                    </div>
                                </div>
                                <?php echo form_close(); ?>
                            <?php } ?>

                            <div class="card-footer text-center pt-0 px-lg-2 px-1">
                                <p class="mb-4 text-sm mx-auto">

                                    <a href="<?php base_url('authentication/login'); ?>" class="text-success text-gradient font-weight-bold">PORTAL DO CLIENTE</a>

                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <footer class="footer position-absolute bottom-2 py-2 w-100">
                <div class="container">
                    <div class="row align-items-center justify-content-lg-between">
                        <div class="col-12 col-md-6 my-auto">
                            <div class="copyright text-center text-sm  text-lg-start">
                                © <script>
                                        document.write(new Date().getFullYear())
                                    </script>,
                                    made with <i class="fa fa-heart"></i> by
                                    <a href="" class="font-weight-bold" target="_blank">Sigplus</a>/<a href="" class="font-weight-bold" target="_blank">Unimed Manaus</a>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <ul class="nav nav-footer justify-content-center justify-content-lg-end">
                                <li class="nav-item">
                                    <a href="https://www.unimedmanaus.com.br/" class="nav-link " target="_blank">Site Unimed</a>
                                </li>
                                <li class="nav-item">
                                    <a href="https://www.unimedmanaus.com.br/" class="nav-link " target="_blank">Sobre Nós</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </footer>
        </main>

        <script src="<?php echo base_url(); ?>assets/portal/js/plugins/choices.min.js"></script>
        <script>
                                    var win = navigator.platform.indexOf('Win') > -1;
                                    if (win && document.querySelector('#sidenav-scrollbar')) {
                                        var options = {
                                            damping: '0.5'
                                        }
                                        Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
                                    }
        </script>


        <script>

                                    if (document.getElementById('choices-category')) {
                                        var element = document.getElementById('choices-category');
                                        const example = new Choices(element, {
                                            searchEnabled: false
                                        });
                                    }
                                    ;
        </script>


        <script src="<?php echo base_url(); ?>assets/portal/js/material-dashboard.min.js?v=3.0.6"></script>

        <script>


                                    function select(id) {
                                        $.ajax({
                                            type: "POST",
                                            url: "<?php echo base_url('Authentication/wifi/1'); ?>",
                                            data: {
                                                slug: "select_acess_type", id: id, "<?php echo $this->security->get_csrf_token_name(); ?>": "<?php echo $this->security->get_csrf_hash(); ?>"
                                            },
                                            success: function (data) {
                                                $('#trocar').html(data);
                                            }
                                        });

                                    }
        </script>
     </body>
</html>