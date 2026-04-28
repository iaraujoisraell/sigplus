

<!DOCTYPE html>
<html lang="en" translate="no">
    <?php $this->load->view('portal/includes/head'); ?>
    <body class="g-sidenav-show bg-gray-200">


        <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NKDMSK6" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>

        <?php $this->load->view('portal/includes/menu'); ?>
        <div class="main-content position-relative max-height-vh-100 h-100">

            <?php $this->load->view('portal/includes/navbar'); ?>

            <div class="container-fluid px-2 px-md-4">
                <!--<div class="page-header min-height-300 border-radius-xl" style="background-image: url('https://images.unsplash.com/photo-1531512073830-ba890ca4eba2?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&ixlib=rb-1.2.1&auto=format&fit=crop&w=1920&q=80');">
                    <span class="mask  bg-gradient-primary  opacity-6"></span>
                </div>-->
                <?php if ($msg_error) { ?>

                    <div class="alert alert-danger alert-dismissible text-white fade show" role="alert">
                        <span class="alert-icon align-middle">
                            <span class="material-icons text-md">
                                thumb_down_off_alt
                            </span>
                        </span>
                        <span class="alert-text"><strong>Falha!</strong> <?php echo $msg_error; ?></span>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php } ?>

                <?php if ($msg_success) { ?>
                    <div class="alert alert-success alert-dismissible text-white fade show" role="alert">
                        <span class="alert-icon align-middle">
                            <span class="material-icons text-md">
                                thumb_up_off_alt
                            </span>
                        </span>
                        <span class="alert-text"><strong>Sucesso!</strong> <?php echo $msg_success; ?></span>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php } ?>
                <div class="card card-body mx-3 mx-md-4 mt-3">
                    <div class="row gx-4 mb-2">
                        <div class="col-auto">
                            <div class="avatar avatar-xl position-relative">
                                <img src="<?php echo base_url('assets/images/user-placeholder.jpg'); ?>" alt="profile_image" class="w-100 border-radius-lg shadow-sm">
                            </div>
                        </div>
                        <div class="col-auto my-auto">
                            <div class="h-100">
                                <h5 class="mb-1">
                                    <?php echo $info_perfil->company; ?>
                                </h5>
                                <p class="mb-0 font-weight-normal text-sm">
                                    Unimed Manaus / Cliente
                                </p>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 my-sm-auto ms-sm-auto me-sm-0 mx-auto mt-3">
                            <div class="nav-wrapper position-relative end-0">
                                <ul class="nav nav-pills nav-fill p-1" role="tablist">
                                    <li class="nav-item" onclick="change('info', 'edit');">
                                        <a class="nav-link mb-0 px-0 py-1 active " data-bs-toggle="tab"  role="tab" aria-selected="true">
                                            <i class="material-icons text-lg position-relative">home</i>
                                            <span class="ms-1">Informaçãoes</span>
                                        </a>
                                    </li>
                                    <li class="nav-item" onclick="change('edit', 'info');">
                                        <a class="nav-link mb-0 px-0 py-1 " data-bs-toggle="tab"  role="tab" aria-selected="false">
                                            <i class="material-icons text-lg position-relative">settings</i>
                                            <span class="ms-1">Editar Perfil</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <?php //print_r($info);?>

                    <div class="row" id="info" style="display: block;">
                        <div class="row">
                            <div class="col-12 col-md-6 col-xl-6  mt-4 position-relative">
                                <div class="card card-plain h-100">
                                    <div class="card-header pb-0 p-3">
                                        <div class="row">
                                            <div class="col-md-8 d-flex align-items-center">
                                                <h6 class="mb-0">Informações de Perfil</h6>
                                            </div>
                                            <div class="col-md-4 text-end">
                                                <a onclick="change('edit', 'info');">
                                                    <i class="fas fa-user-edit text-secondary text-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Profile"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body p-3">
                                        <p class="text-sm">
                                            Abaixo estão suas informações cadastrais, para editar clique em Editar Perfil.
                                        </p>
                                        <hr class="horizontal gray-light my-2">
                                        <ul class="list-group">
                                            <li class="list-group-item border-0 ps-0 pt-0 text-sm"><strong class="text-dark">Nome:</strong> &nbsp; <?php echo $info->NOME; ?></li>
                                            <li class="list-group-item border-0 ps-0 pt-0 text-sm"><strong class="text-dark">Carteirinha:</strong> &nbsp; <?php echo $info->CARTEIRINHA; ?></li>
                                            <li class="list-group-item border-0 ps-0 pt-0 text-sm"><strong class="text-dark">Tipo:</strong> &nbsp; <?php
                                                if ($this->session->userdata('pf') == true) {
                                                    echo 'Pessoa Física';
                                                } else {
                                                    echo 'Pessoa Jurídica';
                                                }
                                                ?></li>
                                            <li class="list-group-item border-0 ps-0 text-sm"><strong class="text-dark">Telefone:</strong> &nbsp; (<?php echo $info->DDD_CELULAR; ?>) <?php echo $info->CELULAR; ?></li>
                                            <li class="list-group-item border-0 ps-0 text-sm"><strong class="text-dark">Email:</strong> &nbsp; <?php echo $info->EMAIL; ?></li>
                                            <li class="list-group-item border-0 ps-0 text-sm"><strong class="text-dark">Endereço:</strong> &nbsp; <?php echo $info->LOGRADOURO; ?> <?php echo $info->NUMERO; ?>, <?php echo $info->BAIRRO; ?>, <?php echo $info->LOCALIDADE; ?> - <?php echo $info->UF; ?></li>
                                            <li class="list-group-item border-0 ps-0 pt-0 text-sm"><strong class="text-dark">Coparticipação:</strong> &nbsp; <?php
                                                if ($this->session->userdata('coparticipation') == true) {
                                                    echo 'Sim';
                                                } else {
                                                    echo 'Não';
                                                }
                                                ?></li>

                                        </ul>
                                    </div>
                                </div>
                                <hr class="vertical dark">
                            </div>

                            <div class="col-12 col-xl-6 mt-xl-0 mt-4 position-relative">
                                <div class="card card-plain h-100">
                                    <div class="card-header pb-0 p-3">
                                        <h6 class="mb-0">Protocolos recentes</h6>
                                    </div>
                                    <div class="card-body p-3">
                                        <ul class="list-group">
                                            <?php
                                            $i = 0;
                                            foreach ($atendimentos as $a) {
                                                if ($i < 5) {
                                                    ?>
                                                    <li class="list-group-item border-0 d-flex align-items-center px-0 mb-2 pt-0">

                                                        <div class="d-flex align-items-start flex-column justify-content-center">
                                                            <h6 class="mb-0 text-sm"><?php echo $a['protocolo']; ?></h6>
                                                            <p class="mb-0 text-xs"><?php echo $a['date_created']; ?></p>
                                                        </div>
                                                        <a class="btn btn-outline-info mb-0 ms-auto  btn-tooltip" href="<?php echo base_url('portal/profile/view/'.$a['id']);?>">Saber mais</a>
                                                    </li>
                                                    <?php
                                                } $i++;
                                            }
                                            ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <?PHP //print_r($info); ?>
                    <div class="row mt-3" id="edit" style="display: none;">
                        <div class="col-12 col-md-6 col-xl-12 mt-md-0 mt-4 position-relative">
                            <div class="card card-plain h-100">
                                <?php echo form_open(site_url('portal/profile/edit')); ?>
                                <div class="card-header pb-0 p-3">
                                    <div class="row">
                                        <div class="col-md-8 d-flex align-items-center">
                                            <h6 class="mb-0">Editar Perfil</h6>


                                        </div>
                                        <div class="col-md-4 text-end">
                                            <button type="submit" class="btn btn-outline-success btn-sm mb-0">
                                                Salvar <i class="fas fa-user-edit text-secondary text-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Profile"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body p-3">
                                    <div class="row">

                                        <div class="col-md-6">
                                            <div class="input-group input-group-static mb-4">
                                                <label class="form-label">Nome</label>
                                                <input type="text" class="form-control" value="<?php echo $info->NOME; ?>" name="nome" disabled>
                                            </div> 
                                        </div>
                                        <div class="col-md-6">
                                            <div class="input-group input-group-static mb-4">
                                                <label class="form-label">Email</label>
                                                <input type="text" class="form-control" value="<?php echo $info->EMAIL; ?>" name="email">
                                            </div> 
                                        </div>

                                        <div class="col-md-4">
                                            <div class="input-group input-group-static mb-4">
                                                <label class="form-label">Carteirinha</label>
                                                <input type="text" class="form-control" value="<?php echo $info->CARTEIRINHA; ?>" name="carteirinha" disabled>
                                            </div> 
                                        </div>
                                        <div class="col-md-4">
                                            <div class="input-group input-group-static mb-4">
                                                <label class="form-label">Telefone</label>
                                                <input type="text" class="form-control" value="<?php echo $info->DDD_CELULAR . ' ' . $info->CELULAR; ?>" name="celular">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="input-group input-group-static mb-4">
                                                <label class="form-label">CEP</label>
                                                <input type="text" class="form-control" value="<?php echo $info->CEP; ?>"  name="cep" id="cep">
                                            </div> 
                                        </div>

                                        <div class="col-md-6">
                                            <div class="input-group input-group-static mb-4">
                                                <label class="form-label">Bairro</label>
                                                <input type="text" class="form-control" value="<?php echo $info->BAIRRO; ?>" readonly name="bairro" id="bairro">
                                            </div> 
                                        </div>

                                        <div class="col-md-6">
                                            <div class="input-group input-group-static mb-4">
                                                <label class="form-label">Logradouro</label>
                                                <input type="text" class="form-control" value="<?php echo $info->LOGRADOURO; ?>" readonly name="logradouro" id="logradouro">
                                            </div> 
                                        </div>

                                        <div class="col-md-3">
                                            <div class="input-group input-group-static mb-4">
                                                <label class="form-label">Número</label>
                                                <input type="text" class="form-control" value="<?php echo $info->NUMERO; ?>" name="numero" id="numero">
                                            </div> 
                                        </div>
                                        <div class="col-md-3">
                                            <div class="input-group input-group-static mb-4">
                                                <label class="form-label">Localidade</label>
                                                <input type="text" class="form-control" value="<?php echo $info->LOCALIDADE; ?>" readonly name="localidade" id="localidade">
                                            </div> 
                                        </div>
                                        <div class="col-md-3">
                                            <div class="input-group input-group-static mb-4">
                                                <label class="form-label">UF</label>
                                                <input type="text" class="form-control" value="<?php echo $info->UF; ?>" readonly name="uf" id="uf">
                                            </div> 
                                        </div>
                                        <div class="col-md-3">
                                            <div class="input-group input-group-static mb-4">
                                                <label class="form-label">Código IBGE</label>
                                                <input type="text" class="form-control" value="<?php echo $info->CODIGO_IBGE; ?>" readonly name="codigo_ibge" id="codigo_ibge">
                                            </div> 
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" class="form-control" value="<?php echo $info->CD_PESSOA; ?>" readonly name="cod_pessoa">
                                <?php echo form_close(); ?>
                            </div>
                            <hr class="vertical dark">
                        </div>
                    </div>
                </div>
                <?php if ($registros == true || count($workflows) > 0) { ?>

                    <?php if (count($workflows) > 0) { ?>
                        <div class="card card-body mx-3 mx-md-4 mt-3">
                            <div class="mb-1 ps-3">
                                <h6 class="mb-1">SOLICITAÇÕES</h6>
                                <p class="text-sm">Abaixo estão todas suas solicitações.</p>
                            </div>
                            <div class="row">

                                <?php foreach ($workflows as $w) { ?>
                                    <div class="col-xl-3 col-md-3 mb-xl-0 mb-4">
                                        <div class="card card-blog card-plain">
                                            <div class="card-body p-3">
                                                <a data-bs-placement="right" title="Acompanhar Solicitação" href="<?php echo base_url(); ?>portal/workflow/workflow/<?php echo $w['id']; ?>"><p class="mb-0 text-xs font-weight-bold"><?php echo $w['titulo']; ?> #<?php echo $w['id']; ?></p></a>
                                                <p class="mb-0 text-xs font-weight-bold">(<?php echo $w['protocolo']; ?>)</p>

                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>

                        </div>

                    <?php } ?>
                    <?php
                    foreach ($categorias as $categoria) {
                        if (count($categoria['registros']) > 0) {
                            ?>
                            <div class="card card-body mx-3 mx-md-4 mt-3">
                                <div class="mb-1 ps-3">
                                    <h6 class="mb-1"><?php echo strtoupper($categoria['titulo']); ?></h6>
                                    <p class="text-sm">Abaixo estão todos seus registros de <?php echo $categoria['titulo']; ?>.</p>
                                </div>
                                <div class="row">
                                    <?php foreach ($categoria['registros'] as $registro) { ?>
                                        <div class="col-xl-3 col-md-3 mb-xl-0 mb-4">
                                            <div class="card card-blog card-plain">
                                                <div class="card-body p-3">
                                                    <a class="mb-0 text-xs font-weight-bold" data-bs-placement="right" title="Função indisponível">RO #<?php echo $registro['id']; ?></a>
                                                    <p class="mb-0 text-xs font-weight-bold">(<?php echo $registro['protocolo']; ?>)</p>
                                                    <p class="mb-2 text-sm">
                                                        <?php echo $registro['subject']; ?>
                                                    </p>
                                                    <div class="d-flex align-items-center justify-content-between">
                                                        <?php $status = get_ro_status($registro['status']); ?>
                                                        <span class="badge badge-<?php echo $status['class']; ?>"><?php echo $status['label']; ?></span>
                                                        <div class="avatar-group mt-2">
                                                            <a href="javascript:;" class="avatar avatar-xs rounded-circle" data-bs-toggle="tooltip" data-bs-placement="bottom" title="<?php
                                                            if ($registro['atribuido_a']) {
                                                                echo get_staff_full_name($registro['atribuido_a']);
                                                            } else {
                                                                echo 'Aguardando';
                                                            }
                                                            ?>">
                                                                <img alt="Image placeholder" src="<?php echo base_url('assets/images/user-placeholder.jpg'); ?>">
                                                            </a>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>

                                        </div>



                                    <?php } ?>
                                    <hr class="vertical dark">
                                </div>
                            </div>
                        <?php } ?>

                    <?php } ?>


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
        </div><!-- comment -->


        <script src="<?php echo base_url() ?>assets/portal/js/core/popper.min.js"></script>
        <script src="<?php echo base_url() ?>assets/portal/js/core/bootstrap.min.js"></script>
        <script src="<?php echo base_url() ?>assets/portal/js/plugins/perfect-scrollbar.min.js"></script>
        <script src="<?php echo base_url() ?>assets/portal/js/plugins/smooth-scrollbar.min.js"></script>

        <script src="<?php echo base_url() ?>assets/portal/js/plugins/dragula/dragula.min.js"></script>
        <script src="<?php echo base_url() ?>assets/portal/js/plugins/jkanban/jkanban.js"></script>
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

        <script src="<?php echo base_url() ?>assets/portal/js/material-dashboard.min.js?v=3.0.6"></script>
        <script defer src="https://static.cloudflareinsights.com/beacon.min.js/vb26e4fa9e5134444860be286fd8771851679335129114" integrity="sha512-M3hN/6cva/SjwrOtyXeUa5IuCT0sedyfT+jK/OV+s+D0RnzrTfwjwJHhd+wYfMm9HJSrZ1IKksOdddLuN6KOzw==" data-cf-beacon='{"rayId":"7b29a07c5fa31b20","version":"2023.3.0","r":1,"token":"1b7cbb72744b40c580f8633c6b62637e","si":100}' crossorigin="anonymous"></script>
        <!-- Adicionando JQuery -->
        <script src="https://code.jquery.com/jquery-3.6.4.min.js"
                integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8="
        crossorigin="anonymous"></script>

        <!-- Adicionando Javascript -->
        <script>

                                    $(document).ready(function () {

                                        function limpa_formulário_cep() {
                                            // Limpa valores do formulário de cep.
                                            $("#logradouro").val("");
                                            $("#bairro").val("");
                                            $("#localidade").val("");
                                            $("#uf").val("");
                                            $("#codigo_ibge").val("");
                                        }

                                        //Quando o campo cep perde o foco.
                                        $("#cep").blur(function () {

                                            //Nova variável "cep" somente com dígitos.
                                            var cep = $(this).val().replace(/\D/g, '');

                                            //Verifica se campo cep possui valor informado.
                                            if (cep != "") {

                                                //Expressão regular para validar o CEP.
                                                var validacep = /^[0-9]{8}$/;

                                                //Valida o formato do CEP.
                                                if (validacep.test(cep)) {

                                                    //Preenche os campos com "..." enquanto consulta webservice.
                                                    $("#logradouro").val("...");
                                                    $("#bairro").val("...");
                                                    $("#localidade").val("...");
                                                    $("#uf").val("...");
                                                    $("#codigo_ibge").val("...");

                                                    //Consulta o webservice viacep.com.br/
                                                    $.getJSON("https://viacep.com.br/ws/" + cep + "/json/?callback=?", function (dados) {

                                                        if (!("erro" in dados)) {
                                                            //Atualiza os campos com os valores da consulta.
                                                            $("#logradouro").val(dados.logradouro);
                                                            $("#bairro").val(dados.bairro);
                                                            $("#localidade").val(dados.localidade);
                                                            $("#uf").val(dados.uf);
                                                            $("#codigo_ibge").val(dados.ibge);
                                                        } //end if.
                                                        else {
                                                            //CEP pesquisado não foi encontrado.
                                                            limpa_formulário_cep();
                                                            alert("CEP não encontrado.");
                                                        }
                                                    });
                                                } //end if.
                                                else {
                                                    //cep é inválido.
                                                    limpa_formulário_cep();
                                                    alert("Formato de CEP inválido.");
                                                }
                                            } //end if.
                                            else {
                                                //cep sem valor, limpa formulário.
                                                limpa_formulário_cep();
                                            }
                                        });
                                    });
                                    function change(show, hide) {
                                        document.getElementById(show).style.display = 'block';
                                        document.getElementById(hide).style.display = 'none';

                                    }


        </script>

    </body>
</html>