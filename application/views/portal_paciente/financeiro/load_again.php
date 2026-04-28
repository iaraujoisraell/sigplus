<?php if ($error == true) { ?>
    <div class="col-md-12">
        <div class="card mt-3">
            <div class="card-body">
                <div class="alert alert-dismissible fade show" style="width: 100%; background-color: gray; color: white;" role="alert">
                    <span class="alert-icon align-middle">
                        <span class="material-icons text-md text-white">
                            error
                        </span>
                    </span>
                    <span class="alert-text text-white">

                        Sem Registros.

                    </span>
                </div>
            </div>
        </div><!-- comment -->
    </div>
<?php } else { ?>

    <div class="col-md-12">
        <div class="card mt-3">

            <?php //print_r($info);?>
            <div class="card-body">
                <div class="col-md-12">
                    <a href="<?php echo base_url();?>portal/financeiro/history_pdf?de=<?php echo $de;?>&ate=<?php echo $ate;?>&carteirinha=<?php echo $carteirinha;?>">
                        <span class="material-icons alert-icon align-middle">
                            picture_as_pdf
                        </span> GERAR PDF

                    </a>
                </div>
                <div class="table-responsive">
                    <table class="table table-flush" id="datatable-search">
                        <thead class="thead-light">
                            <tr class="text-center">
                                <th>Contrato</th>
                                <th>Referência</th>
                                <th>Título</th>
                                <th>Pagador</th>
                                <th>Vencimento</th>
                                <th>Total</th>
                                
                                <th>Status</th>
                                <th>Detalhamento</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php
                            $i = 1;
                            foreach ($info as $dado) {
                                ?>
                                <tr class="text-center">
                                    <td class="text-sm font-weight-normal"><?php echo $dado->contrato; ?></td>
                                    <td class="text-sm font-weight-normal"><?php echo date("d/Y", strtotime($dado->referencia)); ?></td>
                                    <td class="text-sm font-weight-normal"><?php echo $dado->nr_titulo; ?></td>
                                    <td class="text-sm font-weight-normal"><?php echo $dado->nome_pagador; ?> <?php if ($dado->cpf_pagador) { ?>(<?php echo $dado->cpf_pagador; ?>)<?php } ?></td>

                                    <td class="text-sm font-weight-normal">

                                        <?php echo $dado->dt_vencimento;?>
                                    </td>
                                    <td class="text-sm font-weight-normal">

                                        <span class="badge badge-warning">R$ <?php echo $dado->vl_titulo; ?></span>
                                    </td>
                                    
                                    <td class="text-sm font-weight-normal">

                                        <span class="badge badge-info"><?php echo $dado->status_titulo; ?></span>
                                        <?php if( $dado->status_titulo == 'Liquidado'){
                                          echo '<p>('.$dado->dt_liquidacao.')</p>';
                                        }?>
                                    </td>
                                    <td class="text-sm font-weight-normal"><a class="btn btn-sm btn-warning mb-0 mx-auto" onclick="
                                                    var i;
                                                    for (i = 0; i < document.getElementsByClassName('det<?php echo $i; ?>').length; i++) {
                                                        if (document.getElementsByClassName('det<?php echo $i; ?>')[i].style.display == 'none') {
                                                            document.getElementsByClassName('det<?php echo $i; ?>')[i].style.display = '';
                                                            $('#see_det<?php echo $i; ?>').html('Fechar');
                                                        } else {
                                                            document.getElementsByClassName('det<?php echo $i; ?>')[i].style.display = 'none';
                                                            $('#see_det<?php echo $i; ?>').html('Detalhes');
                                                        }

                                                    }
                                                                              " id="see_det<?php echo $i; ?>">Detalhes</a></td>
                                </tr>

                                <tr class="text-center det<?php echo $i; ?>" style="display: none;" id="det<?php echo $i; ?>">

                                    <td class="text-sm font-weight-normal" colspan="8">
                                        <?php if (count($dado->det) > 0) { ?>
                                            <div class="table-responsive" style="padding: 20px;">
                                                <table class="table table-flush" id="datatable-search">
                                                    <thead class="thead-light">
                                                        <tr class="text-center">
                                                            <th>Dependência</th>
                                                            <th>Nome</th>
                                                            <th>Carteirinha</th>
                                                            <th>Mensalidade</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>

                                                        <?php foreach ($dado->det as $det) { ?>
                                                            <tr class="text-center">
                                                                <td class="text-sm font-weight-normal"><span class="badge badge-dark"><?php echo $det->dependencia; ?></span></td>

                                                                <td class="text-sm font-weight-normal">

                                                                    <?php echo $det->nome; ?>
                                                                </td>
                                                                <td class="text-sm font-weight-normal">

                                                                    <?php echo $det->cd_beneficiario; ?>
                                                                </td>
                                                                <td class="text-sm font-weight-normal">

                                                                    <span class="badge badge-warning">R$ <?php echo $det->vl_mensalidade; ?></span>
                                                                </td>
                                                            </tr>
                                                            <?php
                                                        }
                                                        ?>

                                                    </tbody>
                                                </table>

                                            </div>
                                            <?php
                                        } else {
                                            echo 'Sem Dados';
                                        }
                                        ?>

                                        <?php
                                        $i++;
                                    }
                                    ?>
                                </td>


                            </tr>


                        </tbody>
                    </table>

                </div>

            </div>

        </div>
    </div>
<?php } ?>