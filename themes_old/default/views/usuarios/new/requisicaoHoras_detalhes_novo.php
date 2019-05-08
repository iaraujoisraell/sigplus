<?php 
    $usuario = $this->session->userdata('user_id');
  //  $projetos_usuario = $this->site->getProjetoAtualByID_completo($usuario);
    //   a.`id` , a.`id_acao` , r.`descricao` , idatas, i.descricao, e.nome_evento, pj.id as id_projeto, pj.projeto       
?>


    <div class="content-wrapper">
        <section class="content-header">
          <h1>
            Requisição de Horas
            <small>Lançamento de horas </small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="<?= site_url('welcome/home'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Dashboard</li>
          </ol>

        </section>
        <section class="content">
            <div class="row">


                    <?php

                    
                    ?>

            <div class="col-md-12">
            <h3>Competência : <?php echo $meses[$competencia->mes].'/'.$competencia->ano; ?></h3>
            <?php



               ?>
            <ol class="breadcrumb">
                    <li> Horas Extra : <font style="color: blue;"> <?php echo "{$horashe}:{$minutoshe}"; ?></font></li>
                    <li class="active">Banco de Horas : <font style="color: green;"> <?php echo "{$horasbh}:{$minutosbh}"; ?></font></li>
                    <li class="active">Débito de Horas : <font style="color: red;"> <?php echo "- {$horas}:{$minutos}"; ?> </font></li>
                </ol>


                <?php
                $attrib = array('data-toggle' => 'validator', 'role' => 'form');
                echo form_open_multipart("Projetos/Eventos_index_form", $attrib);

                ?>
            <p><a href="<?= site_url('welcome/verAcaoRequisicaoHoras/'.$periodo2->id_acao); ?>" data-toggle="modal" data-target="#myModal">TESTE</a></p>
                <div class="portlet-body">
                    <div class="table-responsive">
                        <table id="example-table" class="table table-striped table-bordered table-hover table-green">
                            <thead>
                             <tr style="background-color: orange; width: 100%;">

                                <th style="width: 15%;">DATA</th>
                                <th style="width: 25%;">Descrição Justificativa</th>
                                <th style="width: 5%;"> Entrada</th>
                                <th style="width: 5%;"> Entrada almoço</th>
                                <th style="width: 5%;"> Saída almoço</th>
                                <th style="width: 5%;"> Saída </th>
                                <th style="width: 5%;">Crédito</th>
                                <th style="width: 5%;">Débito</th>
                                <th style="width: 10%;">Ação</th>
                                <th style="width: 5%;">H.Extra</th>
                                <th style="width: 10%;"> Justificar</th>
                            </tr>

                            </thead>
                            <tbody>
                                 <?php
                                    $wu4[''] = '';
                                    $cont = 1;
                                    foreach ($lacamentos as $periodo) {
                                        $id_cript = encrypt($periodo->id,'UNIMED');
                                        $meses = array(
                                            '1'=>'Janeiro',
                                            '2'=>'Fevereiro',
                                            '3'=>'Março',
                                            '4'=>'Abril',
                                            '5'=>'Maio',
                                            '6'=>'Junho',
                                            '7'=>'Julho',
                                            '8'=>'Agosto',
                                            '9'=>'Setembro',
                                            '10'=>'Outubro',
                                            '11'=>'Novembro',
                                            '12'=>'Dezembro'
                                        );

                                        

                                            ?>               

                                        <tr  >

                                            <td style="width: 15%;"><?php echo $periodo->dia.'/'.substr($meses[$periodo->mes], 0, 3); ?> <small class="label pull-right <?php if(($dia_semana == "Sabado")||($dia_semana == "Domingo")){ ?> bg-orange <?php }else{ ?> bg-gray <?php } ?> "><?php echo '('.$dia_semana.')'; ?></small></td> 
                                            <td style="width: 25%;"><?php echo $periodo->descricao; ?> </td>
                                            <td style="width: 5%;"><?php echo $periodo->hora_inicio; ?></td>
                                            <td style="width: 5%;"><?php echo $periodo->entrada_intervalo; ?></td> 
                                            <td style="width: 5%;"><?php echo $periodo->saida_intervalo; ?></td> 
                                            <td style="width: 5%;"><?php echo $periodo->hora_fim_confirmado; ?> </td>
                                            <td style="width: 5%; color: blue; "><?php echo $periodo->saldo; ?></td>
                                            <td style="width: 5%;  color: red;  "><?php echo $periodo->debito; ?></td>
                                            <td style="width: 10%; ">
                                                
                                            </td>
                                            <td style="width: 5%;"><?php echo $periodo->hora_extra; ?> </td>
                                            <td style="width: 10%;" class="center">
                                                <?php if($status == 1){ 
                                                        echo $periodo->confirmacao;
                                                        ?>

                                                    <?php }else{ 

                                                    //      <a style="background-color: green; color: #ffffff;" title="Novo Registro" class="btn fa fa-plus" href="<?= site_url('welcome/novaRequisicaoHorasDetalhes/'.$periodo->id); " data-toggle="modal" data-target="#myModal">  </a>
                                                            if(($periodo->saldo)||($periodo->debito)){ ?>
                                                                <a style="background-color: chocolate; color: #ffffff;" title="Editar Registro" class="btn fa fa-edit" href="<?= site_url('welcome/editarRequisicaoHorasDetalhes/'.$periodo->id); ?>" data-toggle="modal" data-target="#myModal">  </a>

                                                        <?php 
                                                            }else{
                                                                //   <a style="background-color: green; color: #ffffff;" class="btn fa fa-plus" href="<?= site_url('welcome/editarRequisicaoHorasDetalhes_nova/'.$periodo->id); " data-toggle="modal" data-target="#myModal">  </a>
                                                            }


                                                        }
                                                    ?>
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


        </div>
            
            
          <br>
        <br>
    </div>


    

