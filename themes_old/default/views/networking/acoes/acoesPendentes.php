

<body class="hold-transition skin-green sidebar-collapse  sidebar-mini">
<div class="wrapper">

    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                Minhas Ações Pendentes
                <small>Painel de Controle</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Dashboard</li>
            </ol>


                <section class="content">
                <?php
                $cont_atrasadas = 0;
                $total_pendentes = 0;
                foreach ($planos as $plano) {
                    $evento = $this->atas_model->getAllitemEventoByID($plano->eventos);

                    $ata_user = $this->atas_model->getAtaUserByAtaUser($plano->idatas, $usuario);
                    $result = $ata_user->id;

                    $status = $plano->status;
                    $data_prazo = $plano->data_termino;


                    if ($status == 'PENDENTE') {
                        $dataHoje = date('Y-m-d H:i:s');
                        // $total_pendentes++;
                        /*
                         * SE A DATA ATUAL FOR < A DATA DO PRAZO
                         * PENDENTE
                         */
                        if ($dataHoje <= $data_prazo) {
                            $novo_status = 'PENDENTE';
                        }

                        /*
                         * SE A DATA ATUAL FOR > A DATA DO PRAZO
                         * ATRASADO (X DIAS)
                         * +5 DIAS
                         * +10 DIAS
                         * 
                         */
                        if ($dataHoje > $data_prazo) {
                            $novo_status = 'ATRASADO';
                            $cont_atrasadas++;

                            // Usa a função criada e pega o timestamp das duas datas:
                            $time_inicial = geraTimestamp($this->sma->hrld($dataHoje));
                            $time_final = geraTimestamp($this->sma->hrld($data_prazo));
                            // Calcula a diferença de segundos entre as duas datas:
                            $diferenca = $time_final - $time_inicial; // 19522800 segundos
                            // Calcula a diferença de dias
                            $dias = (int) floor($diferenca / (60 * 60 * 24)); // 225 dias

                            if ($dias >= '-5') {
                                $qtde_dias = $dias;
                            } else if (($dias < '-5') && ($dias >= '-10')) {
                                $qtde_dias = $dias;
                            } else if ($dias < '-10') {
                                $qtde_dias = $dias;
                            } else if ($dias < '-15') {
                                $qtde_dias = '+15';
                            }
                            $qtde_dias = str_replace('-', '', $qtde_dias);
                        }
                    } else if ($status == 'AGUARDANDO VALIDAÇÃO') {
                        $novo_status = 'AGUARDANDO VALIDAÇÃO';
                    }

                    $projetos_usuario = $this->atas_model->getAtaProjetoByID_ATA($plano->idatas);
                }
                ?>   

                <br>

                <table>
                    <thead>
                        <tr >
                            <th >Ações Atrasadas : <?php echo $cont_atrasadas; ?></th>
                        </tr>
                    </thead> 
                </table>
                <br>

                <!-- DIV TABLE AÇÕES PENDENTES -->  
                <div id="acoes_pendentes">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="portlet portlet-default">



                                <div class="portlet-body">
                                    <div class="table-responsive">

                                        <table id="example-table" class="table table-striped table-bordered table-hover table-green">
                                            <thead>
                                                <tr style="background-color: orange;">
                                                    <th>ID</th>
                                                    <th>PROJETO</th>
                                                    <th>ATA</th>
                                                    <th>EVENTO</th>
                                                    <th>DESCRIÇÃO</th>

                                                    <th>RECEBI EM</th>
                                                    <th>PRAZO</th>
                                                    <th>STATUS</th>
                                                    <th>Anexo</th>
                                                    <th>Ações Vinculadas</th>
                                                    <th>Ver Ação</th>
                                                    <th>Retorno</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php

                                                function geraTimestamp($data) {
                                                    $partes = explode('/', $data);
                                                    return mktime(0, 0, 0, $partes[1], $partes[0], $partes[2]);
                                                }
                                                ?>
                                                <?php
                                                $wu4[''] = '';

                                                foreach ($planos as $plano) {
                                                    $evento = $this->atas_model->getAllitemEventoByID($plano->eventos);

                                                    $ata_user = $this->atas_model->getAtaUserByAtaUser($plano->idatas, $usuario);
                                                    $result = $ata_user->id;

                                                    $status = $plano->status;
                                                    $data_prazo = $plano->data_termino;


                                                    if ($status == 'PENDENTE') {
                                                        $dataHoje = date('Y-m-d H:i:s');

                                                        /*
                                                         * SE A DATA ATUAL FOR < A DATA DO PRAZO
                                                         * PENDENTE
                                                         */
                                                        if ($dataHoje <= $data_prazo) {
                                                            $novo_status = 'PENDENTE';
                                                        }

                                                        /*
                                                         * SE A DATA ATUAL FOR > A DATA DO PRAZO
                                                         * ATRASADO (X DIAS)
                                                         * +5 DIAS
                                                         * +10 DIAS
                                                         * 
                                                         */
                                                        if ($dataHoje > $data_prazo) {
                                                            $novo_status = 'ATRASADO';


                                                            // Usa a função criada e pega o timestamp das duas datas:
                                                            $time_inicial = geraTimestamp($this->sma->hrld($dataHoje));
                                                            $time_final = geraTimestamp($this->sma->hrld($data_prazo));
                                                            // Calcula a diferença de segundos entre as duas datas:
                                                            $diferenca = $time_final - $time_inicial; // 19522800 segundos
                                                            // Calcula a diferença de dias
                                                            $dias = (int) floor($diferenca / (60 * 60 * 24)); // 225 dias

                                                            if ($dias >= '-5') {
                                                                $qtde_dias = $dias;
                                                            } else if (($dias < '-5') && ($dias >= '-10')) {
                                                                $qtde_dias = $dias;
                                                            } else if ($dias < '-10') {
                                                                $qtde_dias = $dias;
                                                            } else if ($dias < '-15') {
                                                                $qtde_dias = '+15';
                                                            }
                                                            $qtde_dias = str_replace('-', '', $qtde_dias);
                                                        }
                                                    } else if ($status == 'AGUARDANDO VALIDAÇÃO') {
                                                        $novo_status = 'AGUARDANDO VALIDAÇÃO';
                                                    }

                                                    $projetos_usuario = $this->atas_model->getAtaProjetoByID_ATA($plano->idatas);
                                                    ?>   

                                                    <tr   class="odd gradeX">
                                                        <td><?php echo $plano->idplanos; IF($plano->id_ticket){ echo 'ID Helpdesk: '.$plano->id_ticket; } ?>   </td> 
                                                        <td><?php echo $projetos_usuario->projetos; ?></td> 
                                                        <td><?php echo $plano->idatas; ?> <?php if ($result) { ?> <a title="Download ATA" href="<?= site_url('welcome/pdf/' . $plano->idatas); ?> "><i class="fa fa-file-pdf-o"></i></a> <?php } else { ?>  <?php } ?></td>     
                                                        <td><?php echo $evento->evento . '/' . $evento->item; ?></td> 

                                                        <td>
                                                            <table>
                                                                <tr>
                                                                    <td>
                                                                        <?php echo $plano->descricao; ?>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>
                                                                        <font  style="font-size: 10px; "><?php echo $plano->tipo; ?> <?php echo $plano->processo; ?> <?php echo $plano->item_roteiro; ?></font>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>   

                                                        <td>
                                                            <?php if ($this->sma->hrld($plano->data_elaboracao) != '0000-00-00 00:00:00') {
                                                                    echo $this->sma->hrld($plano->data_elaboracao);
                                                                } else {
                                                                    echo $this->sma->hrld($projetos_usuario->data_ata);
                                                                } ?> 
                                                        </td>    
                                                        <td class="center"><font style="font-size: 16px;font-weight: bold"> <?php if ($plano->data_termino != '0000-00-00 00:00:00') {
                                                                            echo $this->sma->hrld($plano->data_termino);
                                                                        } ?></font></td>     



                                                        <?php if ($novo_status == 'PENDENTE') {
                                                            ?>
                                                            <td style="background-color: #CB3500;color: #ffffff" class="center"><?php echo $novo_status; ?></td>

                                                            <?php
                                                        } else if ($novo_status == 'ATRASADO') {

                                                            if ($dias >= '-5') {
                                                                ?>
                                                                <td style=" background-color: #c7254e; color: #ffffff;" class="center"><?php echo $novo_status . '  (' . $qtde_dias . ' dias ) '; ?></td>
                                                            <?php } else if (($dias < '-5') && ($dias >= '-10')) { ?> 
                                                                <td style=" background-color: #d2322d; color: #ffffff;" class="center"><?php echo $novo_status . '  (' . $qtde_dias . ' dias ) '; ?></td>

                                                            <?php } else if ($dias < '-10') { ?> 
                                                                <td style=" background-color: #000000; color: #ffffff;" class="center"><?php echo $novo_status . '  (' . $qtde_dias . ' dias ) '; ?></td>

                                                        <?php } ?> 


                                                        <?php } else if ($novo_status == 'AGUARDANDO VALIDAÇÃO') { ?>
                                                            <td style=" background-color: orange; color: #ffffff;" class="center"><?php echo $novo_status; ?></td>



                                                        <?php } else { ?> 
                                                            <td style=" background-color: orange; color: #ffffff;" class="center">-</td>
                                                        <?php } ?>  

                                                            <td>
                                                                <?php if ($plano->anexo) { ?>
                                                                    <a target="_blank" href="<?= site_url('../assets/uploads/atas/' . $plano->anexo) ?>" class="tip btn btn-file" title="<?= lang('Fazer Download do Anexo') ?>">
                                                                        <span class="glyphicon glyphicon-paperclip"></span>
                                                                        <span class="glyphicon-class">Ver Anexo</span>
                                                                    </a>
                                                                 <?php } ?>
                                                            </td>


                                                        <td class="center">
                                                            <table>
                                                                <?php
                                                                $acoes_vinculadas = $this->atas_model->getAllAcoesVinculadas($plano->idplanos);
                                                                foreach ($acoes_vinculadas as $acao_vinculada) {
                                                                    IF ($acao_vinculada) {
                                                                        if ($acao_vinculada->id_vinculo == 0) {
                                                                            $acao_vinculada->id_vinculo = "";
                                                                        }
                                                                        ?>
                                                                        <tr>
                                                                            <td class="center">
                                                                                <a  class="btn"  href="<?= site_url('welcome/manutencao_acao_vinculo/' . $acao_vinculada->id_vinculo); ?>"> <?php echo $acao_vinculada->id_vinculo; ?> </a>
                                                                            </td>
                                                                        </tr>

            <?php
        }
    }
    ?>
                                                            </table>            
                                                        </td>
                                                        <td class="center">
                                                            <a style="background-color: chocolate; color: #ffffff;" class="btn fa fa-folder-open-o" href="<?= site_url('welcome/manutencao_acao_new/' . $plano->idplanos); ?>"> </a>
                                                        </td>
                                                        <td class="center">


                                                            <?php if (($plano->status == 'PENDENTE') || ($plano->status == 'ABERTO')) { ?>
                                                                <a style="color: #ffffff; background-color: crimson; background-color: darkcyan; "  class="btn fa fa-exchange"  href="<?= site_url('welcome/retorno_new/' . $plano->idplanos); ?>"> Retornar</a>
                                                    <?php } else if ($plano->status == 'AGUARDANDO VALIDAÇÃO') { ?>
                                                                <a style="color: blue;" class="btn fa fa-clock-o"  href="" ></a>
                                                    <?php } ?>
                                                        </td>
                                                    </tr>
    <?php
}
?>



                                            </tbody>

                                        </table>
                                    </div>
                                    <!-- /.table-responsive -->
                                </div>
                                <!-- /.portlet-body -->

                            </div>
                            <!-- /.portlet -->

                        </div>
                        <!-- /.col-lg-12 -->



                    </div>
                </div>
                <!-- /.FIM AÇÕES PENDENTES -->
            </section>

    </div>
    <!-- /.page-content -->

    <!-- /#wrapper -->
</div>
</body>
