

<!DOCTYPE html>
<html lang="en" translate="no">
    <?php $this->load->view('portal/includes/head'); ?>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <body class="g-sidenav-show  bg-gray-200">


        <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NKDMSK6" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
        <?php $this->load->view('portal/includes/menu'); ?>


        <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">

            <?php $this->load->view('portal/includes/navbar'); ?>

            <div class="container-fluid px-2 px-md-4">
                <?php if ($error == true) { ?>
                    <div class="card card-body">

                        <div class="row">

                            <div class="col-12 col-md-12 col-xl-12 mt-md-0 position-relative">
                                <div class="card card-plain h-100"  id="card-plain-info" style="display: block;">
                                    <div class="card-header pb-0 pt-0">
                                        <div class="row">

                                            <div class="col-md-12 d-flex align-items-center mt-3">
                                                <div class="alert alert-dismissible fade show" style="width: 100%; background-color: gray; color: white;" role="alert">
                                                    <span class="alert-icon align-middle">
                                                        <span class="material-icons text-md text-white">
                                                            error
                                                        </span>
                                                    </span>
                                                    <span class="alert-text text-white">
                                                        <strong>
                                                            Sem relatórios para <?php echo $info_perfil->company; ?>.
                                                        </strong>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                <?php } else { ?>
                    <div class="card card-body">

                        <div class="row">

                            <div class="col-12 col-md-12 col-xl-12 mt-md-0 position-relative">
                                <div class="card card-plain h-100"  id="card-plain-info" style="display: block;">
                                    <div class="card-header pb-0 pt-0 mt-2">
                                        <div class="row">
                                            <div class="col-md-8 d-flex align-items-center">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <h6 class="mb-0">Relatório de Coparticipação</h6>
                                                    </div>
                                                    <?php // print_r($info_principal); ?>
                                                    <div class="col-md-12 mt-3">
                                                        <p class="text-sm">
                                                            Por favor, informe a competência que deseja gerar relatório.<br><span class="text-danger font-weight-light" >A competência atual é disponibilizada a partir do dia 12.</span>
                                                        </p>
                                                    </div>

                                                </div>




                                            </div>

                                            <div class="col-md-4 text-center">
                                                        <div class="row border-0 d-flex  mb-2 bg-gray-100 border-radius-lg p-3">
                                                            
                                                            <div class="col-md-12">
                                                                <span class="mb-2 text-xs">Selecione a competência: <span class="text-dark font-weight-bold ms-2"><?php echo $info_principal->DS_RUA; ?>, <?php echo $info_principal->DS_COMPLEMENTO; ?></span></span>

                                                                <?php //print_r($competencias);?>
                                                                <select class="form-control" name="competencia" id="competencia" onchange="search(this.value);">
                                                                       
                                                                    <option value="" selected disabled>Selecione</option>
                                                                        <?php foreach($competencias as $comp){?>
                                                                        <option value="<?php echo $comp['value'];?>"><?php echo $comp['label'];?></option>
                                                                        <?php }?>
                                                                    </select>
                                                            </div>
                                                            
                                                           
                                                        </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>
                    <div class="row" id="load_again">
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
                                <a href="" class="font-weight-bold" target="_blank"> Sigplus</a>/<a href="" class="font-weight-bold" target="_blank">Unimed Manaus</a>
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
        </main>


        <script src="<?php echo base_url(); ?>assets/portal/js/core/popper.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/portal/js/core/bootstrap.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/portal/js/plugins/perfect-scro llbar.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/portal/js/plugins/smooth-scrollbar.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/portal/js/plugins/choices.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/portal/js/plugins/dropzone.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/portal/js/plugins/quill.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/portal/js/plugins/multistep-form.js"></script>


        <script src="<?php echo base_url(); ?>assets/portal/js/plugins/dragula/dragula.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/portal/js/plugins/jkanban/jkanban.js"></script>
        <script>
                                    var win = navigator.platform.indexOf('Win') > -1;
                                    if (win && document.querySelector('#sidenav-scrollbar')) {
                                        var options = {
                                            damping: '0.5'
                                        }
                                        Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
                                    }
        </script>

        <script async defer src="https://buttons.github.io/buttons.js"></script>

        <script src="<?php echo base_url(); ?>assets/portal/js/material-dashboard.min.js?v=3.0.6"></script>
        <script defer src="https://static.cloudflareinsights.com/beacon.min.js/vb26e4fa9e5134444860be286fd8771851679335129114" integrity="sha512-M3hN/6cva/SjwrOtyXeUa5IuCT0sedyfT+jK/OV+s+D0RnzrTfwjwJHhd+wYfMm9HJSrZ1IKksOdddLuN6KOzw==" data-cf-beacon='{"rayId":"7b245aadcf00117d","version":"2023.3.0","r":1,"token":"1b7cbb72744b40c580f8633c6b62637e","si":100}' crossorigin="anonymous"></script>

        <script>

                                    //
                                    if (document.getElementById('competencia')) {
                                        var country = document.getElementById('competencia');
                                        const example = new Choices(country);
                                    }
                                    
                                    function search(comp) {
                                        $('#load_again').html("<div style='width: 100%; text-align: center;' > <div class='spinner-border text-info' role='status'> <span class='sr-only'>Loading...</span> </div></div>");
                                   

                                        $.ajax({
                                            type: "POST",
                                            url: "<?php echo base_url('portal/Coparticipation/search'); ?>",
                                            data: {
                                                comp: comp,
                                                "<?php echo $this->security->get_csrf_token_name(); ?>": "<?php echo $this->security->get_csrf_hash(); ?>"
                                            },
                                            success: function (data) {
                                                $('#load_again').html(data);
                                            }
                                        });
                                    }

                                    
        </script>


    </body>
</html>