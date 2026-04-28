<html lang="en" translate="no">
    <?php $this->load->view('portal/includes/head'); ?>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <body class="g-sidenav-show bg-gray-200">
        <aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3   bg-gradient-dark" id="sidenav-main">
            <?php $this->load->view('portal/includes/menu'); ?>
        </aside>
        <main class="main-content max-height-vh-100 h-100">
            <?php $this->load->view('portal/includes/navbar'); ?> 
            <div class="container-fluid px-2 px-md-4">
                <?php
                if ($exist == true) {
                    $this->load->model('Company_model');
                    $datas = $this->Company_model->get_ticket_date();
                    ?>
                    <div class="card card-body">
                        <div class="row">
                            <div class="card-plain h-100"  id="card-plain-info" style="display: block;">
                                <div class="card-header pb-0 pt-2">
                                    <div class="row">
                                        <div class="col-md-12 d-flex align-items-center">
                                            <h6 class="mb-0">Boletos em aberto</h6>
                                        </div>

                                    </div>
                                    <hr>
                                </div>
                                <div class="card-body p-3">
                                    <div class="table-responsive">
                                        <table class="table table-flush" id="datatable-search">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>Boleto</th>
                                                    <th>Nr. T&iacute;tulo</th>
                                                    <th>Per&iacute;odo</th>
                                                    <th>Vencimento</th>
                                                    <th>Dias em Aberto</th>
                                                    <th>C&oacute;digo de Barras</th>
                                                    
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                foreach ($result as $pagamento) {
                                                    $periodo = $pagamento['PERIODO'];
                                                    $mes = substr($periodo, 0, 2);
                                                    $ano = substr($periodo, 3, 4);

                                                    $mes_Atual = date('m');
                                                    $ano_Atual = date('Y');

                                                    //if ($mes <= $mes_Atual && $ano <= $ano_Atual) {
                                                    ?>


                                                    <tr>
                                                        <td class="text-sm font-weight-normal">
                                                            <?php
                                                            $maior = true;
                                                            //if (strtotime($pagamento['DATA_VENCIMENTO_COMPLETA']) < strtotime(date('Y-m-d'))) {
                                                            if (strtotime($pagamento['DATA_VENCIMENTO_COMPLETA']) < strtotime(date('Y-m-d'))) {
                                                                //exit;
                                                                $maior = false;
                                                                $diferenca = strtotime(date('Y-m-d')) - strtotime($pagamento['DATA_VENCIMENTO_COMPLETA']);
                                                                $dias = floor($diferenca / (60 * 60 * 24));
                                                            }


                                                            if ($dias < 10 || $maior == true) {
                                                                if(in_array($pagamento['DATA_VENCIMENTO_COMPLETA'], $datas)) {
                                                                    ?>
                                                                  

                                                                    <?php echo form_open_multipart(base_url().'portal/financeiro/visualizar_boleto/',array('id'=>'boleto-form', 'target'=>'BLOQUETO')) ;?>
                                                                        <INPUT TYPE="hidden" name="boleto" VALUE="<?php echo $pagamento['NR_TITULO']; ?>">
                                                                        <INPUT TYPE="hidden" name="cpf" VALUE="<?php echo $cpf; ?>">
                                                                        <button TYPE="submit" target="_blank" class="btn btn-sm btn-warning mb-0 mx-auto" class="btn_gerar_boleto" >Emitir</button>
                                                                   
                                                                    <?php echo form_close(); ?>

                                                                <?php } else { ?>
                                                            <button  class="btn btn-sm btn-warning mb-0 mx-auto" disabled >Indisponível</button><br>
                                                                <span class="mb-0 text-xs font-weight-bold" style="color: red;">Data Indisponível.</span><br>
                                                                <?php } ?>
                                                            <?php } else { ?>
                                                                <button  class="btn btn-sm btn-warning mb-0 mx-auto" disabled >Indisponível</button><br>
                                                                <span class="mb-0 text-xs font-weight-bold" style="color: red;">Tempo Excedido.</span><br>
                                                            <?php } ?>


                                                        </td>
                                                        <td class="text-sm font-weight-normal"><?php echo $pagamento['NR_TITULO']; ?></td>
                                                        <td class="text-sm font-weight-normal"><?php echo $pagamento['PERIODO']; ?></td>
                                                        <td class="text-sm font-weight-normal">
                                                            <span class="badge badge-warning"><?php echo $pagamento['DATA_VENCIMENTO']; ?></span>

                                                        </td>
                                                        <td class="text-sm font-weight-normal">
                                                            <span class="badge badge-warning"> <?php
                                                                if (is_numeric($pagamento['DIAS_ABERTOS'])) {
                                                                    echo $pagamento['DIAS_ABERTOS'] . ' DIAS';
                                                                } else {
                                                                    echo $pagamento['DIAS_ABERTOS'];
                                                                }
                                                                ?></span>

                                                        </td>
                                                        <td class="text-sm font-weight-normal">

                                                            <?php echo $pagamento['NR_BLOQUETO_EDTADO']; ?>
                                                        </td>
                                                        
                                                    </tr>

                                                    <?php
                                                    // }
                                                }
                                                ?>


                                            </tbody>
                                        </table>
                                    </div>



                                </div>
                            </div>

                        </div>
                    </div>

                <?php } else { ?>

                    <div class="alert alert-dismissible fade show" style="background-color: gray;" role="alert">
                        <span class="alert-text text-white"><strong>Títulos Indisponíveis.</strong> Sem títulos disponíveis para esse CPF</span>

                        <button type="button" class="btn-close text-white" data-bs-dismiss="alert" aria-label="Close">
                            <span class="text-white" aria-hidden="true">&times;</span>
                        </button>
                    </div><br>
                <?php } ?>
                <footer class="footer py-4">
                    <div class="container-fluid">
                        <div class="row align-items-center justify-content-lg-between">
                            <div class="col-lg-6 mb-lg-0 mb-4">
                                <div class="copyright text-center text-sm text-muted text-lg-start">
                                    © <script>
                                        document.write(new Date().getFullYear())
                                    </script>,
                                    feito por
                                    <a href="" class="font-weight-bold" target="_blank">Sigplus - Soluções Corporativas</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
        </main>
        <!--   Core JS Files   -->
        <script src="<?php echo base_url(); ?>assets/portal/js/core/popper.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/portal/js/core/bootstrap.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/portal/js/plugins/perfect-scrollbar.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/portal/js/plugins/smooth-scrollbar.min.js"></script>
        <!-- Kanban scripts -->
        <script src="<?php echo base_url(); ?>assets/portal/js/plugins/dragula/dragula.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/portal/js/plugins/jkanban/jkanban.js"></script>
        <script src="<?php echo base_url(); ?>assets/portal/js/plugins/leaflet.js"></script>
        <script src="<?php echo base_url(); ?>assets/portal/js/plugins/nouislider.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/portal/js/plugins/choices.min.js"></script>

        <script src="<?php echo base_url(); ?>assets/portal/js/plugins/dropzone.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/portal/js/plugins/quill.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/portal/js/plugins/multistep-form.js"></script>
        <!-- Github buttons -->
        <script async defer src="https://buttons.github.io/buttons.js"></script>
        <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
        <script src="<?php echo base_url(); ?>assets/portal/js/material-dashboard.min.js?v=3.0.2"></script>
        <script>
                                        var win = navigator.platform.indexOf('Win') > -1;
                                        if (win && document.querySelector('#sidenav-scrollbar')) {
                                            var options = {
                                                damping: '0.5'
                                            }
                                            Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
                                        }
        </script>
        <!-- Adicionando JQuery -->

        <script src="https://igorescobar.github.io/jQuery-Mask-Plugin/js/jquery.mask.min.js"></script>   

        <!-- Github buttons -->
        <script async defer src="https://buttons.github.io/buttons.js"></script>
        <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
        <script src="../../../assets/js/material-dashboard.min.js?v=3.0.2"></script>


        <!-- Adicionando Javascript -->


    </body>

</html>


