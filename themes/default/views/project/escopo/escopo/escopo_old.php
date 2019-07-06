<?php 
      function exibirData($data){
	$rData = explode("-", $data);
	$rData = $rData[2].'/'.$rData[1].'/'.$rData[0];
	return $rData;
   }
    ?>
<!DOCTYPE html>
<html lang="en">

    <?php $this->load->view($this->theme . 'header_dashboard'); ?>
   
 
  <link rel="stylesheet" href="<?= $assets ?>bi/dist/css/AdminLTE.min.css">
 
  
    
<body>

    <div id="wrapper">

        <div id="page-wrapper">

            <div class="page-content">

                <div class="row">
                    <?php // $this->load->view($this->theme . 'status_projeto'); ?>
                </div>



            <?php


            ?>
                <br><br>
                <!-- /ATALHOS RÁPIDO -->
                <div class="row">

                    <!-- LADO ESQUERDO -->
                    <div class="col-lg-12">
                        <!-- MEUS PROJETOS -->
                        <div class="portlet portlet-default">
                            <div class="portlet-heading">
                                <div class="portlet-title">
                                    <h4>Escopo do Projeto</h4>
                                </div>
                                <div class="portlet-widgets">

                                    <span class="divider"></span>
                                    <a data-toggle="collapse" data-parent="#accordion" href="#buttons"><i class="fa fa-chevron-down"></i></a>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div id="buttons" class="panel-collapse collapse in">
                                <div class="portlet-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <?php
                                            foreach ($tipos as $tipo) {
                                                $tipo_id = $tipo->id;
                                                $tipo_fase = $tipo->nome_fase;

                                                $dados_fase = $this->projetos_model->getFaseByID($tipo_id);


                                                if($dados_fase->concluido){
                                                    $concluido = $dados_fase->concluido;
                                                }else{
                                                    $concluido = 0;
                                                }
                                                if($dados_fase->atrasado){
                                                    $atrasado = $dados_fase->atrasado;
                                                }else{
                                                    $atrasado = 0;
                                                }
                                                if($dados_fase->pendente){
                                                    $pendente = $dados_fase->pendente;
                                                }else{
                                                    $pendente = 0;
                                                }
                                                if($dados_fase->nao_iniciado > 0){
                                                    $nao_iniciado = $dados_fase->nao_iniciado;
                                                }else{
                                                   $nao_iniciado = 0;
                                                }
                                               $soma_acoes_fase = $concluido + $atrasado + $pendente + $nao_iniciado;
                                               $total_acoes_itens = $concluido + $atrasado + $pendente;
                                               if($soma_acoes_itens > 0){

                                               $porc_concluido = ($concluido * 100) / $soma_acoes_fase;
                                               $porc_pendente = ($pendente * 100) / $soma_acoes_fase;
                                               $porc_atrasado = ($atrasado * 100) / $soma_acoes_fase;
                                               $porc_nao_iniciado = ($nao_iniciado * 100) / $soma_acoes_fase;

                                               }else{
                                                $porc_concluido = 0;
                                               $porc_pendente = 0;
                                               $porc_atrasado = 0;
                                               $porc_nao_iniciado = 100;
                                               }


                                                ?>


                                                <div class="box box-primary collapsed-box box-solid">
                                                    <div class="box-header with-border">
                                                        <h3 class="box-title"><?php echo $tipo_fase; ?></h3>
                                                        <div class="box-tools pull-right">
                                                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <div  class="progress">
                                                        <div class="progress-bar progress-bar-success" role="progressbar" style="width:<?php echo $porc_concluido; ?>%">
                                                            <?php
                                                            if ($porc_concluido != 100) {
                                                                echo substr($porc_concluido, 0, 4);
                                                            } else {
                                                                echo $porc_concluido;
                                                            }
                                                            ?> % Concluído
                                                        </div>
                                                        <div class="progress-bar progress-bar-warning" role="progressbar" style="width:<?php echo $porc_pendente; ?>%">
                                                            <?php
                                                            if ($porc_pendente != 100) {
                                                                echo substr($porc_pendente, 0, 4);
                                                            } else {
                                                                echo $porc_pendente;
                                                            }
                                                            ?>% Pendente
                                                        </div>
                                                        <div class="progress-bar progress-bar-danger" role="progressbar" style="width:<?php echo $porc_atrasado; ?>%">
                                                            <?php
                                                            if ($porc_atrasado != 100) {
                                                                echo substr($porc_atrasado, 0, 4);
                                                            } else {
                                                                echo $porc_atrasado;
                                                            }
                                                            ?>% Atrasado
                                                        </div>
                                                        <div class="progress-bar bg-gray" role="progressbar" style="width:<?php echo $porc_nao_iniciado; ?>%">
                                                            <?php
                                                            if ($porc_nao_iniciado != 100) {
                                                                echo substr($porc_nao_iniciado, 0, 4);
                                                            } else {
                                                                echo $porc_nao_iniciado;
                                                            }
                                                            ?>% Não Iniciado
                                                        </div>
                                                    </div>
                                                    <!-- /.box-header -->
                                                    <div id="<?php echo $tipo_id . $id_evento; ?>" class="box-body">
                                                        <div class="portlet-body">

                                                            <?php

                                                            $cont_evento = 1;
                                                             $somaqtde_pendente = 0;


                                                            $eventos = $this->projetos_model->getAllEventosProjetoByFase($tipo_id);
                                                            foreach ($eventos as $evento) {
                                                                $id_evento = $evento->id;

                                                                $data_inicio_evento = exibirData(substr($evento->data_inicio, 0, 10)) ;
                                                                $data_fim_evento = exibirData(substr($evento->data_fim, 0, 10)) ;

                                                                $responsavel_tecnico_id = $evento->responsavel;
                                                                $resp_tecnico = $this->site->getUser($responsavel_tecnico_id);

                                                                $dados_evento = $this->projetos_model->getEventoByID($id_evento);


                                                                if($dados_evento->concluido){
                                                                    $concluido = $dados_evento->concluido;
                                                                }else{
                                                                    $concluido = 0;
                                                                }
                                                                if($dados_evento->atrasado){
                                                                    $atrasado = $dados_evento->atrasado;
                                                                }else{
                                                                    $atrasado = 0;
                                                                }
                                                                if($dados_evento->pendente){
                                                                    $pendente = $dados_evento->pendente;
                                                                }else{
                                                                    $pendente = 0;
                                                                }
                                                                if($dados_evento->nao_iniciado > 0){
                                                                    $nao_iniciado = $dados_evento->nao_iniciado;
                                                                }else{
                                                                   $nao_iniciado = 0;
                                                                }
                                                               $soma_acoes_evento = $concluido + $atrasado + $pendente + $nao_iniciado;

                                                               if($soma_acoes_evento > 0){

                                                               $porcentagem_concluido_evento = ($concluido * 100) / $soma_acoes_evento;
                                                               $porcentagem_pendente_evento = ($pendente * 100) / $soma_acoes_evento;
                                                               $porcentagem_atrasado_evento = ($atrasado * 100) / $soma_acoes_evento;
                                                               $porcentagem_nao_iniciado_evento = ($nao_iniciado * 100) / $soma_acoes_evento;

                                                               }else{
                                                                $porcentagem_concluido_evento = 0;
                                                               $porcentagem_pendente_evento = 0;
                                                               $porcentagem_atrasado_evento = 0;
                                                               $porcentagem_nao_iniciado_evento = 100;
                                                               }  


                                                                    ?>

                                                                <div class="box box-default collapsed-box box-solid">
                                                                    <div class="box-header with-border">
                                                                      <h3 class="box-title"><font style="font-size: 18px;"><?php echo $cont_evento . ' - ' . $evento->nome_evento; ?></font>  <?php echo '  ( de :  ' . substr($data_inicio_evento, 0, 10) . ' à  ' . substr($data_fim_evento, 0, 10) . ').  Resp. Técnico : ' . $resp_tecnico->first_name . ' ' . $resp_tecnico->last_name; ?></h3>

                                                                      <div class="box-tools pull-right">
                                                                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                                                                        </button>
                                                                      </div>
                                                                      <!-- /.box-tools -->
                                                                    </div>
                                                                    <div class="progress">
                                                                        <div class="progress-bar progress-bar-success" role="progressbar" style="width:<?php echo $porcentagem_concluido_evento; ?>%">
                                                                            <?php
                                                                            if ($porcentagem_concluido_evento != 100) {
                                                                                echo substr($porcentagem_concluido_evento, 0, 2);
                                                                            } else {
                                                                                echo $porcentagem_concluido_evento;
                                                                            }
                                                                            ?> % Concluído
                                                                        </div>
                                                                        <div class="progress-bar progress-bar-warning" role="progressbar" style="width:<?php echo $porcentagem_pendente_evento; ?>%">
                                                                            <?php
                                                                            if ($porcentagem_pendente_evento != 100) {
                                                                                echo substr($porcentagem_pendente_evento, 0, 2);
                                                                            } else {
                                                                                echo $porcentagem_pendente_evento;
                                                                            }
                                                                            ?>% Em Andamento
                                                                        </div>
                                                                        <div class="progress-bar progress-bar-danger" role="progressbar" style="width:<?php echo $porcentagem_atrasado_evento; ?>%">
                                                                            <?php
                                                                            if ($porcentagem_atrasado_evento != 100) {
                                                                                echo substr($porcentagem_atrasado_evento, 0, 2);
                                                                            } else {
                                                                                echo $porcentagem_atrasado_evento;
                                                                            }
                                                                            ?>% Atrasado
                                                                        </div>
                                                                        <div class="progress-bar bg-black" role="progressbar" style="width:<?php echo $porcentagem_nao_iniciado_evento; ?>%">
                                                                            <?php
                                                                            if ($porcentagem_nao_iniciado_evento != 100) {
                                                                                echo substr($porcentagem_nao_iniciado_evento, 0, 2);
                                                                            } else {
                                                                                echo $porcentagem_nao_iniciado_evento;
                                                                            }
                                                                            ?>% Não Iniciado
                                                                        </div>

                                                                    </div>
                                                                    <!-- /.box-header -->
                                                                    <div class="box-body">
                                                                            <?php
                                                                            $intes_eventos = $this->projetos_model->getAllItemEventosProjeto($evento->id);
                                                                            $cont = 1;
                                                                            ?>
                                                                         <div class="portlet-body">
                                                                             <div class="table-responsive">
                                                                                <table style="width:100%;" id="table" class="table" >
                                                                                    <thead>
                                                                                        <tr>

                                                                                            <td style="width:20%;  font-size: 16px; font-weight: bold; "> Itens </td>
                                                                                            <td style="width:45%; font-size: 16px; font-weight: bold;">Status </td>
                                                                                            <td style="width:5%; font-size: 16px; font-weight: bold; ">Ações </td>
                                                                                            <td style="width:5%; font-size: 16px; font-weight: bold;"> Conc. </td>
                                                                                            <td style="width:5%; font-size: 16px; font-weight: bold; ">Pend. </td>
                                                                                            <td style="width:5%; font-size: 16px; font-weight: bold; ">Atras. </td>
                                                                                            <td style="width:5%; font-size: 16px; font-weight: bold;">Visualizar </td>
                                                                                        </tr>
                                                                                    </thead>


                                                                                    <?php
                                                                                    $cont_item_evento = 1;

                                                                                    foreach ($intes_eventos as $item) {

                                                                                    $dados_itens = $this->projetos_model->getItemEventoByID($item->id);


                                                                                    if($dados_itens->concluido){
                                                                                        $concluido = $dados_itens->concluido;
                                                                                    }else{
                                                                                        $concluido = 0;
                                                                                    }
                                                                                    if($dados_itens->atrasado){
                                                                                        $atrasado = $dados_itens->atrasado;
                                                                                    }else{
                                                                                        $atrasado = 0;
                                                                                    }
                                                                                    if($dados_itens->pendente){
                                                                                        $pendente = $dados_itens->pendente;
                                                                                    }else{
                                                                                        $pendente = 0;
                                                                                    }
                                                                                    if($dados_itens->nao_iniciado > 0){
                                                                                        $nao_iniciado = $dados_itens->nao_iniciado;
                                                                                    }else{
                                                                                       $nao_iniciado = 0;
                                                                                    }
                                                                                   $soma_acoes_itens = $concluido + $atrasado + $pendente + $nao_iniciado;
                                                                                   $total_acoes_itens = $concluido + $atrasado + $pendente;
                                                                                   if($soma_acoes_itens > 0){

                                                                                   $porcentagem_concluido_item = ($concluido * 100) / $soma_acoes_itens;
                                                                                   $porcentagem_pendente_item = ($pendente * 100) / $soma_acoes_itens;
                                                                                   $porcentagem_atrasado_item = ($atrasado * 100) / $soma_acoes_itens;
                                                                                   $porcentagem_nao_iniciado_item = ($nao_iniciado * 100) / $soma_acoes_itens;

                                                                                   }else{
                                                                                    $porcentagem_concluido_item = 0;
                                                                                   $porcentagem_pendente_item = 0;
                                                                                   $porcentagem_atrasado_item = 0;
                                                                                   $porcentagem_nao_iniciado_item = 100;
                                                                                   }

                                                                                    ?>
                                                                                    <tr>
                                                                                                    <td style="width:20%; text-align: justify  ">
                                                                                                        <font style="width: 70%; text-align: justify;"><?php echo $cont_evento . '.' . $cont_item_evento . ' - ' . $item->descricao; ?> </font>
                                                                                                    </td>
                                                                                                     <td style="width:45%; ">
                                                                                                        <div class="progress">
                                                                                                            <div class="progress-bar progress-bar-success" role="progressbar" style="width:<?php echo $porcentagem_concluido_item; ?>%">
                                                                                                                <?php
                                                                                                                if ($porcentagem_concluido_item != 100) {
                                                                                                                    echo substr($porcentagem_concluido_item, 0, 2);
                                                                                                                } else {
                                                                                                                    echo $porcentagem_concluido_item;
                                                                                                                }
                                                                                                                ?> % Concluído
                                                                                                            </div>
                                                                                                            <div class="progress-bar progress-bar-warning" role="progressbar" style="width:<?php echo $porcentagem_pendente_item; ?>%">
                                                                                                                <?php
                                                                                                                if ($porcentagem_pendente_item != 100) {
                                                                                                                    echo substr($porcentagem_pendente_item, 0, 2);
                                                                                                                } else {
                                                                                                                    echo $porcentagem_pendente_item;
                                                                                                                }
                                                                                                                ?>% Em Andamento
                                                                                                            </div>
                                                                                                            <div class="progress-bar progress-bar-danger" role="progressbar" style="width:<?php echo $porcentagem_atrasado_item; ?>%">
                                                                                                                <?php
                                                                                                                if ($porcentagem_atrasado_item != 100) {
                                                                                                                    echo substr($porcentagem_atrasado_item, 0, 2);
                                                                                                                } else {
                                                                                                                    echo $porcentagem_atrasado_item;
                                                                                                                }
                                                                                                                ?>% Atrasado
                                                                                                            </div>
                                                                                                            <div class="progress-bar bg-gray" role="progressbar" style="width:<?php echo $porcentagem_nao_iniciado_item; ?>%">
                                                                                                                <?php
                                                                                                                if ($porcentagem_nao_iniciado_item != 100) {
                                                                                                                    echo substr($porcentagem_nao_iniciado_item, 0, 2);
                                                                                                                } else {
                                                                                                                    echo $porcentagem_nao_iniciado_item;
                                                                                                                }
                                                                                                                ?>% Não Iniciado
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </td>
                                                                                                    <td style="width:5%; ">
                                                                                                        <font style="color: #000000; font-weight: bold"><?php echo $total_acoes_itens; ?></font>
                                                                                                    </td>
                                                                                                    <td style="width:5%; ">
                                                                                                        <font style="color: green; font-weight: bold"><?php echo  $concluido; ?></font>
                                                                                                    </td>
                                                                                                    <td style="width:5%;">
                                                                                                      <font style="color: orange; font-weight: bold"><?php echo $pendente; ?></font>
                                                                                                    </td>
                                                                                                    <td style="width:5%;">
                                                                                                       <font style="color: red; font-weight: bold"><?php echo $atrasado; ?></font>
                                                                                                    </td>
                                                                                                    <td style="width:5%;">
                                                                                                       <a target="_blank" href="<?= site_url('Login_Projetos/eventos_acoes/' . $item->id) ?>" class="btn btn-default">Visualizar <i class="fa fa-chevron-circle-right"></i></a>
                                                                                                    </td>

                                                                                                </tr>


                        <?php
                        $cont_item_evento++;
                    }
                    ?>


                                                                                </table>
                                                                             </div>
                                                                         </div>    
                                                                    </div>
                                                                    <!-- /.box-body -->
                                                                </div>



                                                                    <br>

                                    <?php
                                    $cont_evento++;
                                }
                                                            ?>

                                                        </div>
                                                    </div>
                                                    <!-- /.box-body -->
                                                </div>

                                            <?php } ?>

                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>   
                <!-- /#wrapper -->
            </div>
        </div>
    </div>    
    <!-- GLOBAL SCRIPTS -->
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script src="<?= $assets ?>dashboard/js/plugins/bootstrap/bootstrap.min.js"></script>
    <script src="<?= $assets ?>dashboard/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>
    <script src="<?= $assets ?>dashboard/js/plugins/popupoverlay/jquery.popupoverlay.js"></script>
    <script src="<?= $assets ?>dashboard/js/plugins/popupoverlay/defaults.js"></script>
    <!-- Logout Notification Box -->

    <!-- /#logout -->
    <!-- Logout Notification jQuery -->
    <script src="<?= $assets ?>dashboard/js/plugins/popupoverlay/logout.js"></script>
    <!-- HISRC Retina Images -->
    <script src="<?= $assets ?>dashboard/js/plugins/hisrc/hisrc.js"></script>

    <!-- PAGE LEVEL PLUGIN SCRIPTS -->
    <script src="<?= $assets ?>dashboard/js/plugins/ladda/spin.min.js"></script>
    <script src="<?= $assets ?>dashboard/js/plugins/ladda/ladda.min.js"></script>
    <script src="<?= $assets ?>dashboard/js/plugins/bootstrap-multiselect/bootstrap-multiselect.js"></script>

    <!-- THEME SCRIPTS -->
    <script src="<?= $assets ?>dashboard/js/flex.js"></script>
    <script src="<?= $assets ?>dashboard/js/demo/buttons-demo.js"></script>

    <script src="<?= $assets ?>dashboard/js/demo/calendar-demo.js"></script>
    <script src="<?= $assets ?>dashboard/js/plugins/fullcalendar/fullcalendar.min.js"></script>
    <script src="<?= $assets ?>bi/dist/js/adminlte.min.js"></script>

</body>

</html>
