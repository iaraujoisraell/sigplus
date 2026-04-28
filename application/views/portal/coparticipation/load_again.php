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
    <?php
} else {
    //print_r($info_c);
    ?>

    <div class="col-md-12">
        <div class="card mt-3">

            <?php //print_r($info_c->array);  ?>
            <div class="card-body">
                <div class="col-md-12">
                    <a href="<?php echo base_url(); ?>portal/coparticipation/pdf?comp=<?php echo $principal['MES_COMPETENCIA']; ?>">
                        <span class="material-icons alert-icon align-middle">
                            picture_as_pdf
                        </span> GERAR PDF

                    </a>
                </div>
                <br>
                <div class="row mt-3">
                    <?php //print_r($principal); //exit; ?>

                    <div class="col-lg-5 col-md-5 col-12 ">
                        <div class="d-flex flex-column">


                            <span class="mb-2 text-xs">Pagador: <span class="text-dark font-weight-bold ms-2"><?php echo $principal['PAGADOR']; ?> (Contrato: <?php echo $principal['CONTRATO']; ?>)</span></span>
                            <span class="mb-2 text-xs">Beneficiário: <span class="text-dark ms-2 font-weight-bold"><?php echo $principal['NOME_BENEFICIARIO']; ?> (Carteirinha: <?php echo $principal['CARTEIRINHA']; ?>)</span></span>
                            <span class="mb-2 text-xs">Valor total: <span class="text-dark ms-2 font-weight-bold">R$ <?php echo $total; ?></span></span>

                        </div>
                    </div>
                </div>

                <?php foreach ($info as $carteirinha) { ?>
                    <br>
                    <div class="table-responsive">
                        <table class="table table-flush" id="datatable-search">
                            <thead class="thead-light">
                                <tr class="text-center">
                                    <th colspan="5">Beneficiário: <?php echo $carteirinha[0]['NOME_BENEFICIARIO']; ?> (<?php echo $carteirinha[0]['CARTEIRINHA']; ?>)</th>
                                </tr>
                                <tr class="text-center">
                                    <th>Item</th>
                                    <th>Prestador</th>
                                    <th>Data</th>
                                    <th>Valor Base</th>
                                    <th>Participação</th>
                                </tr>
                            </thead>
                            <tbody>

                                <?php
                                $valor_base = 0;
                                $valor_cop = 0;
                                foreach ($carteirinha as $item) {
                                    ?>
                                    <?php $valor_base = $valor_base + str_replace(",", ".", $item['VL_BASE']); ?>
                                    <?php $valor_cop = $valor_cop + str_replace(",", ".", $item['VL_COPARTICIPACAO']); ?>

                                    <tr class="text-center">

                                        <td class="text-sm font-weight-normal">

                                            <?php echo $item['ITEM']; ?>
                                        </td>

                                        <td class="text-sm font-weight-normal">

                                            <?php echo $item['PRESTADOR']; ?>
                                        </td>

                                        <td class="text-sm font-weight-normal">


                                            <?php
                                            echo $item['DATA_ITEM'];
                                            ?>
                                        </td>
                                        <td class="text-sm font-weight-normal">

                                            <span class="badge badge-warning">R$ <?php echo $item['VL_BASE']; ?></span>

                                        </td>

                                        <td class="text-sm font-weight-normal">

                                            <span class="badge badge-success">R$ <?php echo $item['VL_COPARTICIPACAO']; ?></span>

                                        </td>

                                    </tr>


                                    <?php
                                    //$valor = $valor + $item['VL_COPARTICIPACAO'];
                                }
                                ?>
                                <tr >
                                    <th  colspan="3" style="text-align: right;">

                                        Valores por beneficiário:
                                    </th>


                                    <th class="text-sm font-weight-normal">

                                        <span class="badge badge-info">R$ <?php echo number_format($valor_base, 2, ',', '.'); ?></span>
                                    </th>

                                    <th class="text-sm font-weight-normal">

                                        <span class="badge badge-info">R$ <?php echo number_format($valor_cop, 2, ',', '.'); ?></span>
                                    </th>
                                </tr>


                            </tbody>

                        </table>

                    </div>
                <?php } ?>

            </div>

        </div>
    </div>
<?php } ?>