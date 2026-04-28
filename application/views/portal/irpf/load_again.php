<?php if ($error == true) { ?>
    <div class="card card-body mt-3">

        <div class="row mt-2">

            <div class="col-12 col-md-12 col-xl-12 mt-md-0 position-relative">
                <div class="card card-plain h-100"  id="card-plain-info" style="display: block;">
                    <div class="card-header pb-0 pt-0">
                        <div class="row">
                            <div class="col-md-6 d-flex align-items-center">
                                <h6 class="mb-0">INFORMAÇÕES PARA DECLARAÇÃO DE IMPOSTO DE RENDA</h6>
                                <?php // print_r($info_principal); ?>
                            </div>
                            <div class="col-md-6 text-end">
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

                    <div class="card-body col-md-12" id="info_ans">
                        <div class="alert alert-dismissible fade show" style="width: 100%; background-color: gray; color: white;" role="alert">
                            <span class="alert-icon align-middle">
                                <span class="material-icons text-md text-white">
                                    error
                                </span>
                            </span>
                            <span class="alert-text text-white">
                                <strong>
                                    Sem dados para esse ano.
                                </strong>
                            </span>
                        </div>


                    </div>
                </div>
            </div>

        </div>
    </div>
<?php } else { ?>
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

                            <div class="card-body">
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
