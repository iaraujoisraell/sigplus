

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
                                    <div class="card-header pb-0 pt-0">
                                        <div class="row">
                                            <div class="col-md-8 d-flex align-items-center">
                                                <h6 class="mb-0">RELATÓRIO IRPF</h6>
                                                <?php // print_r($info_principal); ?>
                                            </div>

                                        </div>
                                        <div class="row mt-3">

                                            <div class="col-lg-6 col-md-6 col-12 mt-3">
                                                <div class="d-flex flex-column">
                                                    <span class="mb-2 text-xs"><?php echo $info_principal->NM_FANTASIA ?></span>
                                                    <span class="mb-2 text-xs">CNPJ: <span class="text-dark font-weight-bold ms-2"><?php echo $info_principal->CNPJ; ?></span></span>
                                                    <span class="mb-2 text-xs">Endereço: <span class="text-dark ms-2 font-weight-bold"><?php echo $info_principal->DS_ENDERECO; ?>, <?php echo $info_principal->NR_ENDERECO; ?> - <?php echo $info_principal->CD_CEP; ?> <?php echo $info_principal->DS_MUNICIPIO; ?> <?php echo $info_principal->SG_ESTADO; ?></span></span>
                                                    <span class="mb-2 text-xs">Fone: <span class="text-dark ms-2 font-weight-bold"><?php echo $info_principal->FONE; ?></span></span>
                                                    <span class="mb-2 text-xs">Site: <span class="text-dark ms-2 font-weight-bold"><?php echo $info_principal->DS_SITE_INTERNET; ?></span></span>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-12">
                                                <ul class="list-group">
                                                    <li class="list-group-item border-0 d-flex p-4 mb-2 bg-gray-100 border-radius-lg">
                                                        <div class="d-flex flex-column">
                                                            <h6 class="mb-3 text-sm"><?php echo $info_principal->NM_CLIENTE; ?></h6>
                                                            <span class="mb-2 text-xs">Endereço: <span class="text-dark font-weight-bold ms-2"><?php echo $info_principal->DS_RUA; ?>, <?php echo $info_principal->DS_COMPLEMENTO; ?></span></span>
                                                            <span class="mb-2 text-xs">CPF: <span class="text-dark font-weight-bold ms-2"><?php echo $info_principal->CD_CPF_CNPJ; ?></span></span>
                                                            <span class="mb-2 text-xs">Codigo do Beneficiário Titular: <span class="text-dark ms-2 font-weight-bold"><?php echo $info_principal->CD_USUARIO_PLANO; ?></span></span>

                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="row" id="load_again">
                        <div class="col-md-12">
                            <div class="card card-body mt-3">

                                <div class="row mt-2">

                                    <div class="col-12 col-md-12 col-xl-12 mt-md-0 position-relative">
                                        <div class="card card-plain h-100"  id="card-plain-info" style="display: block;">
                                            <div class="card-header pb-0 pt-0">
                                                <div class="row">
                                                    <div class="col-md-8">
                                                        <h6 class="mb-0">INFORMAÇÕES PARA DECLARAÇÃO DE IMPOSTO DE RENDA</h6><br>

                                                    </div>

                                                    <div class="col-md-2 text-end">
                                                        <a class="" href="<?php echo base_url(); ?>portal/irpf/relatorio_pdf/<?php echo $ano; ?>" target="_blank">

                                                            <!--<span class="btn-inner--icon">-->
                                                                <i class="material-icons-round opacity-10">picture_as_pdf</i>
                                                            <!--</span>
                                                            <!--<span class="btn-inner--text">GERAR</span>-->
                                                        </a>
                                                                                                                  <!--<a class=" ml-auto align-items-center" href="<?php echo base_url(); ?>portal/irpf/relatorio_pdf/<?php echo $ano; ?>" target="_blank">
                                                                                                                      <i class="material-icons-round opacity-10">picture_as_pdf</i>
                                                                                                                      GERAR PDF
                                                                                                                  </a>-->
                                                    </div>
                                                    <div class="col-md-2 text-end">

                                                        <div class="input-group input-group-outline">
                                                            <select class="form-control" id="ano_base" name="ano_base" onchange="load_again('<?php echo $info_perfil->vat; ?>', this.value);">

                                                                <?php foreach ($anos as $ano_a) { ?>
                                                                    <option value="<?php echo $ano_a; ?>" <?php
                                                                    if ($ano_a == $ano) {
                                                                        echo 'selected';
                                                                    }
                                                                    ?>><?php echo $ano_a; ?></option>
                                                                        <?php } ?>


                                                            </select>
                                                        </div>
                                                    </div>
                                                    

                                                </div>

                                            </div>

                                            <div class="card-body" id="loading">


                                                <?php //print_r($info_meses);  ?>
                                                <table class="table table-flush" id="datatable-search">
                                                    <thead class="thead-light">
                                                        <tr>
                                                            <th>Referência</th>
                                                            <th>Carteirinha</th>
                                                            <th>Nome</th>
                                                            <th>Valor</th>
                                                            <th>Titularidade</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach ($info_meses as $info) { ?>
                                                            <tr>
                                                                <td class="text-sm font-weight-normal"><?php echo $info->COMPETENCIA; ?></td>
                                                                <td class="text-sm font-weight-normal"><?php echo $info->CARTEIRA; ?></td>
                                                                <td class="text-sm font-weight-normal"><?php echo $info->NOME; ?></td>
                                                                <td class="text-sm font-weight-normal"><?php echo $info->VALOR; ?></td>
                                                                <td class="text-sm font-weight-normal">
                                                                    <span class="badge badge-<?php
                                                                    if ($info->TITULARIDADE == 'Dependente') {
                                                                        echo 'warning';
                                                                    } elseif ($info->TITULARIDADE == 'Titular') {
                                                                        echo 'info';
                                                                    }
                                                                    ?>"><?php echo $info->TITULARIDADE; ?></span>
                                                                </td>

                                                            </tr>
                                                        <?php } ?>

                                                    </tbody>
                                                </table>

                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="card card-body mt-3">

                                <div class="row mt-2">

                                    <div class="col-12 col-md-12 col-xl-12 mt-md-0 position-relative">
                                        <div class="card card-plain h-100"  id="card-plain-info" style="display: block;">
                                            <div class="card-header pb-0 pt-0">
                                                <div class="row">
                                                    <div class="col-md-6 d-flex align-items-center">
                                                        <h6 class="mb-0">RESUMO INDIVIDUAL</h6>
                                                        <?php // print_r($info_principal);     ?>
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="card-body">
                                                <table class="table table-flush" id="datatable-search">
                                                    <thead class="thead-light">
                                                        <tr>
                                                            <th>Carteirinha</th>
                                                            <th>Nome</th>
                                                            <th>Valor</th>
                                                            <th>Titularidade</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach ($info_total as $info) { ?>
                                                            <tr>
                                                                <td class="text-sm font-weight-normal"><?php echo $info->CARTEIRA; ?></td>
                                                                <td class="text-sm font-weight-normal"><?php echo $info->NOME; ?></td>
                                                                <td class="text-sm font-weight-normal"><?php echo $info->VALOR; ?> </td>
                                                                <td class="text-sm font-weight-normal">
                                                                    <span class="badge badge-<?php
                                                                    if ($info->TITULARIDADE == 'Dependente') {
                                                                        echo 'warning';
                                                                    } elseif ($info->TITULARIDADE == 'Titular') {
                                                                        echo 'info';
                                                                    }
                                                                    ?>"><?php echo $info->TITULARIDADE; ?></span>
                                                                </td>

                                                            </tr>
                                                        <?php } ?>

                                                    </tbody>
                                                </table>

                                            </div>
                                            <div class="card-footer" style="text-align: center;">
                                                <div class="row" >
                                                    <div class="col-md-3 d-flex align-items-center">

                                                    </div>
                                                    <div class="col-md-6 d-flex align-items-center">
                                                        <table class="table table-flush" id="datatable-search">
                                                            <thead class="thead-light">
                                                                <tr>
                                                                    <th>VALOR TOTAL</th>
                                                                    <th class="font-weight-normal"><?php echo $info_total_gastos->TOTAL; ?></th>
                                                                </tr>
                                                            </thead>
                                                        </table>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

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
        <script>

                                    function change(add) {
                                        if (add == 'add') {
                                            document.getElementById('card-plain-add').style.display = 'block';
                                            document.getElementById('card-plain-info').style.display = 'none';
                                        } else {
                                            document.getElementById('card-plain-add').style.display = 'none';
                                            document.getElementById('card-plain-info').style.display = 'block';
                                        }
                                    }
                                    if (document.getElementById('edit-deschiption')) {
                                        var quill = new Quill('#edit-deschiption', {
                                            theme: 'snow', // Specify theme in configuration
                                            name: 'descricao'
                                        });
                                    }
                                    ;

                                    if (document.getElementById('choices-category')) {
                                        var element = document.getElementById('choices-category');
                                        const example = new Choices(element, {
                                            searchEnabled: false
                                        });
                                    }
                                    ;

                                    if (document.getElementById('choices-sizes')) {
                                        var element = document.getElementById('choices-sizes');
                                        const example = new Choices(element, {
                                            searchEnabled: false
                                        });
                                    }
                                    ;

                                    if (document.getElementById('choices-currency')) {
                                        var element = document.getElementById('choices-currency');
                                        const example = new Choices(element, {
                                            searchEnabled: false
                                        });
                                    }
                                    ;

                                    if (document.getElementById('choices-tags')) {
                                        var tags = document.getElementById('choices-tags');
                                        const examples = new Choices(tags, {
                                            removeItemButton: true
                                        });

                                        examples.setChoices(
                                                [{
                                                        value: 'One',
                                                        label: 'Expired',
                                                        disabled: true
                                                    },
                                                    {
                                                        value: 'Two',
                                                        label: 'Out of Stock',
                                                        selected: true
                                                    }
                                                ],
                                                'value',
                                                'label',
                                                false,
                                                );
                                    }
        </script>

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
//                                            
                                    function load_again(cpf, ano) {
                                        $('#load_again').html("<div style='width: 100%; text-align: center;' > <div class='spinner-border text-info' role='status'> <span class='sr-only'>Loading...</span> </div></div>");
                                        $.ajax({
                                            type: "POST",
                                            url: "<?php echo base_url('portal/irpf/load_again'); ?>",
                                            data: {
                                                cpf: cpf,
                                                ano: ano,
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