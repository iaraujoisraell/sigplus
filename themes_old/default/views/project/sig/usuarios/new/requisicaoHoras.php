<?php



        
        ?>

<body class="hold-transition skin-green sidebar-collapse  sidebar-mini">


    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                Requisição de Horas
                <small>Painel de Controle</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Dashboard</li>
            </ol>


            <section class="col-lg-12 connectedSortable">
               

                <br>

                
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
                                                    <th>-</th>
                                                    <th>COMPETÊNCIA</th>
                                                    <th>DE</th>
                                                    <th>ATÉ</th>
                                                    <th>STATUS</th>
                                                    <th>Abrir</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                
                                                <?php
                                                $usuario = $this->session->userdata('user_id');    
                                                $wu4[''] = '';
                                                $cont = 1;
                                                foreach ($periodos as $periodo) {
                                                    
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
                                                    
                                                   $status =  $periodo->status_verificacao;
                                                   
                                                    if($status == 1){
                                                        $status_periodo = 'FECHADO';
                                                    }else{
                                                        $status_periodo = 'ABERTO';
                                                    }
                                                     
                                                   $id_cript =  str_replace('=', '_' , base64_encode($periodo->id.'5M4N46543213321877'));
                                                   
                                                    
                                                   ?>   

                                                    <tr   class="odd gradeX">
                                                        <td><?php echo $cont++. '-'.$teste; ?></td> 
                                                        <td><?php echo $meses[$periodo->mes].'/'.$periodo->ano; ?></td> 
                                                        <td><?php echo $periodo->de; ?></td> 
                                                        <td><?php echo $periodo->ate; ?></td>    
                                                        <td <?php if($status == 1){ ?> style="background-color: green; color: #ffffff;" <?php } ?> ><?php echo $status_periodo; ?> </td> 
                                                       
                                                       
                                                        <td class="center">
                                                            <a style="background-color: chocolate; color: #ffffff;" class="btn fa fa-folder-open-o" href="<?= site_url('welcome/requisicaoHorasDetalhes/'.$id_cript); ?>"> </a>
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

</body>
