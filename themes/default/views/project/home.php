<?php
$usuario = $this->session->userdata('user_id');
$projetos = $this->projetos_model->getProjetoAtualByID_completo();
$id_projeto = $projetos->id;
$nome_projeto = $projetos->nome_projeto;
$gerente_projeto = $projetos->gerente_area;
$gerente_dados = $this->site->geUserByID($gerente_projeto);


/*
 * VERIFICA SE TEM AÇÕES AGUARDANDO VALIDAÇÃO
 */
//$quantidadeAvalidacao = $this->site->getAllPlanosAguardandoValidacao($id_projeto);
//$acoes_aguardando_validacao = $quantidadeAvalidacao->quantidade;

    $status = $projetos->status;
    if($status == 'ATIVO'){
       $status_label = 'success'; 
    }else if($status == 'CANCELADO'){
        $status_label = 'danger'; 
    }else if($status == 'EM AGUARDO'){
        $status_label = 'warning'; 
    }else if($status == 'CONCLUÍDO'){
        $status_label = 'primary'; 
    }
?>
 <?php
    function geraTimestamp($data) {
        $partes = explode('/', $data);
        return mktime(0, 0, 0, $partes[1], $partes[0], $partes[2]);
    }
    ?>
<style>
       



#result {
  width: 100%;
  height: 50px;
  line-height: 50px;
 
  margin-top: 100px;
  text-align: center;
  font-size: 30px;
  color: #fff;
  background: #ddd;
}

.r-slider-button {
  background: #efefef;
  box-shadow: 0 3px 7px 0 rgba(0, 0, 0, 0.5);
  border-radius: 100%;
  text-align: center;
}

.r-slider-button:before {
  content: "";
  position: absolute;
  height: 14px;
  width: 14px;
  top: 5px;
  left: 5px;
  border-radius: 100%;
  background: #777;
  box-shadow: inset 0 1px 3px 1px #222;
}

.r-slider-line {
  background: #ddd;
  border-radius: 16px;
  box-shadow: inset 0 2px 2px 0px rgba(0, 0, 0, 0.3);
}

.r-slider-fill {
  background: #fc9b00;
  border-radius: 16px;
  box-shadow: 0 1px 3px 0px rgba(0, 0, 0, 0.7);
  background-image: repeating-linear-gradient(
    45deg,
    transparent,
    transparent 1px,
    rgba(20, 20, 20, 0.1) 4px,
    rgba(20, 20, 20, 0.1) 5px
  );
}

.r-slider-fill:before {
  content: "";
  position: absolute;
  height: 1px;
  width: 100%;
  top: 1px;
  background: #ffc823;
}

.r-slider-fill:after {
  content: "";
  position: absolute;
  height: 1px;
  width: 100%;
  bottom: 0px;
  background: #ca6008;
}

.r-slider-label {
  position: absolute;
  text-align: center;
  line-height: 2px;
  bottom: -30px;
  font-size: 13px;
  color: #999;
  font-weight: bold;
}
</style>

    <?php 
    $usuario =  $this->session->userdata('user_id'); 

    ?>
    

    
   <div  class="col-md-12"> 
        
                   
             <script>
                var A = new slider({
                    start: 0,
                    step: 1,
                    end: <?php echo $total_dias; ?>,
                    value: [0, <?php echo $andamento_dias; ?>],
                    container: "#container",
                    ondrag: showResult,
                    labelStep: 20,
                    style: { line_width: 10, button_width: 24, button_height: 24}
                });
                 $('slider').css('background', '#ccc'); 
                function showResult(obj) {
                  // 


                   <?php $dias_de = "<script>+obj.value[0]+</script>" ?> ;
                      //      alert(obj.value[0]);
                     $("#result").html("Previsto De " +  adicionarDiasData_de(obj.value[0])   + " Até " + obj.value[1]);
                }

               function adicionarDiasData_de(dias){
                  var hoje        = new Date();
                  var dataVenc    = new Date(hoje.getTime() + (dias * 24 * 60 * 60 * 1000));
                  return dataVenc.getDate() + "/" + (dataVenc.getMonth() + 1) + "/" + dataVenc.getFullYear();
                }

                var novaData = adicionarDiasData(5);

            </script>
      </div>     
                                     
                           
    
    <br>
       
    <!-- Main content -->
    <section class="content">
        
    <div class="row">
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3><?php echo $total_acoes; ?></h3>

              <p>Ações</p>
            </div>
            <div class="icon">
              <i class="ion ion-android-list"></i>
            </div>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-green">
            <div class="inner">
              <h3><?php echo $concluido; ?><sup style="font-size: 20px"></sup></h3>

              <p>Ações Concluídas</p>
            </div>
            <div class="icon">
              <i class="ion ion-android-checkbox"></i>
            </div>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-yellow">
            <div class="inner">
              <h3><?php echo $pendente; ?></h3>

              <p>Ações Pendentes</p>
            </div>
            <div class="icon">
              <i class="ion ion-alert"></i>
            </div>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-red">
            <div class="inner">
              <h3><?php echo $atrasadas; ?></h3>

              <p>Ações Atrasadas</p>
            </div>
            <div class="icon">
              <i class="ion ion-clock"></i>
            </div>
          </div>
        </div>
        <!-- ./col -->
      </div>
    
    <!-- DESEMPENHO DO PROJETO -->    
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
       google.charts.load('current', {'packages':['line']});
      google.charts.setOnLoadCallback(drawChart);

    function drawChart() {

      var data = new google.visualization.DataTable();
      data.addColumn('string', 'Data');
      data.addColumn('number', 'Ações Concluídas');
      data.addColumn('number', 'Ações Atrasadas');
      data.addColumn('number', 'Ações Pendentes');
      

      data.addRows([
        <?php
    foreach ($acoes_tempo as $acao_tempo) {

        $t_projeto = $acao_tempo->projeto;
       // if($t_projeto == $projeto_selecionado){
        $t_data = $acao_tempo->data;
        $n_data = implode("/", array_reverse(explode("-", $t_data)));
        $t_acoes = $acao_tempo->total_acoes;
        $t_concluido = $acao_tempo->total_concluido;
        $t_pendente = $acao_tempo->total_pendentes;
        $t_atrasado = $acao_tempo->total_atrasados;

       // }
        ?>  
        ['<?php echo $n_data; ?>',  <?php echo $t_concluido; ?>, <?php echo $t_atrasado; ?>, <?php echo $t_pendente; ?>],
    <?php
    }
    /*
     *  [1,  37.8, 80.8, 41.8],       
        [2,  30.9, 69.5, 32.4],
        [3,  25.4,   57, 25.7],
        [4,  11.7, 18.8, 10.5],
        [5,  11.9, 17.6, 10.4],
        [6,   8.8, 13.6,  7.7],
        [7,   7.6, 12.3,  9.6],
        [8,  12.3, 29.2, 10.6],
        [9,  16.9, 42.9, 14.8],
        [10, 12.8, 30.9, 11.6],
        [11,  5.3,  7.9,  4.7],
        [12,  6.6,  8.4,  5.2],
        [13,  4.8,  6.3,  3.6],
        [14,  4.2,  6.2,  3.4]
     */
    ?>        
       
      ]);

      var options = {
        chart: {
         // title: 'Desempenho do Projeto',
          //subtitle: 'Baseado nas ações'
        },
        width: 500,
        height: 200
      };

      var chart = new google.charts.Line(document.getElementById('linechart_material'));

      chart.draw(data, google.charts.Line.convertOptions(options));
    }
    </script>
     <!-- FIM DESEMPENHO DO PROJETO -->     
     
    <div class="row">
        <div class="col-lg-6">
                <div class="box box-primary">
            <div class="box-header">
              <i class="fa fa-line-chart"></i>

              <h3 class="box-title">DESEMPENHO DO PROJETO</h3>
            </div>
                
           <div class="box-body chat" id="chat-box">
                
                <div  id="main_content_wrap" class="outer">
                    <section id="main_content" class="inner">
                       <div id="linechart_material" style="max-width: 100%; height: 200px"></div>
                     </section>   
                </div>
            </div>
            </div>   
                
            </div>      
        <!-- AÇÕES POR AREA -->   
        <div class="col-lg-6">
            <div class="box box-primary">
            <div class="box-header">
              <i class="fa fa-pie-chart"></i>

              <h3 class="box-title">ATIVIDADES POR ÁREA</h3>
            </div>
                
           <div class="box-body chat" id="chat-box">
                
                <div  id="main_content_wrap" class="outer">
                    <section id="main_content" class="inner">
                       <div id="piechart" style="width: 100%; height: 200px;"></div>
                     </section>   
                </div>
            </div>
            </div>
            <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
            <script type="text/javascript">
                        google.charts.load('current', {'packages':['corechart']});
                google.charts.setOnLoadCallback(drawChart);

                function drawChart() {

                                    var data = google.visualization.arrayToDataTable([
                                    ['Área', 'Porcentagem'],
                    <?php
                    foreach ($areas_projeto as $area_pai) {


                        $id_pai = $area_pai->id_pai;
                        $superintendencia = $area_pai->descricao;

                        $quantidade_area = $this->projetos_model->getAcoesSetorPaiByProjeto($id_pai);
                        $qtde_area2 = $quantidade_area->qtde;
                        ?>
                                 ['<?php echo $superintendencia; ?>', <?php echo $qtde_area2; ?>],

                        <?php
                    }
                    ?>
     ]);
               var options = {
                        title: 'Total de Ações divididas por Áreas'
                };

                var chart = new google.visualization.PieChart(document.getElementById('piechart'));

                chart.draw(data, options);
                }
                </script>
        </div>    
        
        <div class="col-lg-6">
            <div class="box box-danger">
            <div class="box-header">
              <i class="fa fa-pie-chart"></i>

              <h3 class="box-title">AÇÕES POR ÁREA</h3>
            </div>
                
             <div class="box-body chat" id="chat-box">
                
                <div  id="main_content_wrap" class="outer">
                    <section id="main_content" class="inner">
                        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
                         <?php

                            $cont = 1;
                            foreach ($areas_projeto as $area_projeto) {

                                $id_pai = $area_projeto->id_pai;
                                $nome_setor = $area_projeto->descricao;
                                //$qtde_acao = $area_projeto->qtde_acao;

                                // PEGA A QTDE DE AÇÕES POR AREAS E POR PROJETO
                                $quantidade_area = $this->projetos_model->getAcoesSetorPaiByProjeto($id_pai);
                                $qtde_area = $quantidade_area->qtde;

                                $perc_area = ($qtde_area * 100) / $total_acoes;

                                if (($perc_area < 1) && ($perc_area > 0)) {
                                    $perc_area = 1;
                                }

                                //qtde AÇÕES CONCLUÍDAS POR ÁREA
                                $acoes_concluidas_area = $this->projetos_model->getAcoesConcluidasSetorPaiByProjeto($id_pai);
                                $concluidas_setor_pai = $acoes_concluidas_area->qtde;
                                //qtde AÇÕES ´PENDENTE POR ÁREA
                                $acoes_pendente_area = $this->projetos_model->getAcoesPendenteSetorPaiByProjeto($id_pai, 1);
                                $pendente_area =  $acoes_pendente_area->qtde;

                                //qtde AÇÕES ATRASADAS POR ÁREA
                                $acoes_atrasadas_area = $this->projetos_model->getAcoesPendenteSetorPaiByProjeto($id_pai, 2);
                                $atrasadas_area =  $acoes_atrasadas_area->qtde;

                                //PERCENTUAL DE CONCLUSÃO POR ÁREA
                                $perc_conc_area = ($concluidas_setor_pai * 100) / $qtde_area;
                                if ($perc_conc_area == 0) {
                                    $perc_conc_area = 0;
                                }
                                
                                if($qtde_area > 0){
                        ?>
                        
                        
                                <script type="text/javascript">
                                google.charts.load('current', {'packages':['corechart']});
                                google.charts.setOnLoadCallback(drawChart);    
                                  function drawChart() {
                                    var data = google.visualization.arrayToDataTable([
                                      ['Ações', 'Quantidade'],
                                      ['Work',     0],
                                      ['Ações Arasadas',      <?php echo $atrasadas_area; ?>],
                                      ['Ações Pendentes',  <?php echo $pendente_area; ?>],
                                      ['Ações Concluídas', <?php echo $concluidas_setor_pai; ?>],
                                      ['Sleep',    0]
                                    ]);

                                    var options = {
                                      title: '<?php echo $nome_setor; ?>',
                                      is3D: true,
                                    };

                                    var chart = new google.visualization.PieChart(document.getElementById('<?php echo $id_pai; ?>'));
                                    chart.draw(data, options);
                                  }
                                </script>

                           
                                 <div id="<?php echo $id_pai; ?>" class="col-lg-6"  style=" height: 200px;"></div>
                          
                       <?php 
                                }
                       }

                        ?>
                         
                        
                        
                       
                       
                     </section>   
                </div>
            </div>
            </div>
        </div>
        
        <div class="col-lg-6">
            <div class="box box-danger">
            <div class="box-header">
              <i class="fa fa-pie-chart"></i>

              <h3 class="box-title">AÇÕES POR ÁREA - DESCRIÇÃO</h3>
            </div>
                
             <div class="box-body chat" id="chat-box">
                
                <div  id="main_content_wrap" class="outer">
                    <section id="main_content" class="inner">
                     
                         <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
                         <script type="text/javascript">
                          google.charts.load('current', {'packages':['table']});
                          google.charts.setOnLoadCallback(drawTable);

                          function drawTable() {
                            var data = new google.visualization.DataTable();


                            data.addColumn('string', 'Area');
                            data.addColumn('string', 'Ações');
                            data.addColumn('string', 'Concluídas');
                            data.addColumn('string', 'Pendentes');
                            data.addColumn('string', 'Atrasadas');
                            data.addRows([
                                 <?php
                                foreach ($areas_projeto as $area_projeto) {
                                    $id_pai = $area_projeto->id_pai;
                                    $superintendencia = $area_projeto->descricao;
                                    // PEGA A QTDE DE AÇÕES POR AREAS E POR PROJETO
                                    $quantidade_area = $this->projetos_model->getAcoesSetorPaiByProjeto($id_pai);
                                    $qtde_area = $quantidade_area->qtde;

                                    //qtde AÇÕES CONCLUÍDAS POR ÁREA
                                    $acoes_concluidas_area = $this->projetos_model->getAcoesConcluidasSetorPaiByProjeto($id_pai);
                                    $concluidas_setor_pai = $acoes_concluidas_area->qtde;
                                    //qtde AÇÕES ´PENDENTE POR ÁREA
                                    $acoes_pendente_area = $this->projetos_model->getAcoesPendenteSetorPaiByProjeto($id_pai, 1);
                                    $pendente_area =  $acoes_pendente_area->qtde;
                                    //qtde AÇÕES ATRASADAS POR ÁREA
                                    $acoes_atrasadas_area = $this->projetos_model->getAcoesPendenteSetorPaiByProjeto($id_pai, 2);
                                    $atrasadas_area =  $acoes_atrasadas_area->qtde;
                                    ?>

                                     ['<?php echo $superintendencia; ?>',  '<?php echo $qtde_area; ?>', '<?php echo $concluidas_setor_pai; ?>', '<?php echo $pendente_area; ?>', '<?php echo $atrasadas_area; ?>'],                                             
                                    <?php
                                    //$cont++;
                                }
                                ?>

                            ]);

                            var table = new google.visualization.Table(document.getElementById('table_div'));

                            table.draw(data, {showRowNumber: true, width: '100%', height: '100%'});
                          }
                        </script>
                       <div class="col-lg-12">
                            <div id="table_div" style="width: 100%; height: 100%;"></div>  
                       </div>
                       
                   
                     </section>   
                </div>
            </div>
            </div>
        </div>
        
     </div>    
     
    <br>
      <!-- TIMELINE DOS EVENTOS -->
    
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        google.charts.load("current", {packages:["timeline"]});
        google.charts.setOnLoadCallback(drawChart);
        function drawChart() {
                var container = document.getElementById('timeline');
        var chart = new google.visualization.Timeline(container);
        var dataTable = new google.visualization.DataTable();
        dataTable.addColumn({ type: 'string', id: 'Term' });
        dataTable.addColumn({ type: 'string', id: 'Name' });
        dataTable.addColumn({ type: 'date', id: 'Start' });
        dataTable.addColumn({ type: 'date', id: 'End' });

        dataTable.addRows([
    <?php
        $cont = 1;

        foreach ($eventos as $evento) {

            $data_ini = substr("$evento->data_inicio", 0, 10);
            $data_ini_n = str_replace("-", ",", $data_ini);

            $partes_ini_n = explode("-", $data_ini);
            $dia_ini_n = $partes_ini_n[2];
            $mes_ini_n = $partes_ini_n[1];
            $ano_ini_n = $partes_ini_n[0];
            $nova_data_ini = $ano_ini_n . ',' . $mes_ini_n . ',' . $dia_ini_n;

            $data_fim = substr("$evento->data_fim", 0, 10);
            $data_fim_n = str_replace("-", ",", $data_fim);

            $partes_fim_n = explode("-", $data_fim);
            $dia_fim_n = $partes_fim_n[2];
            $mes_fim_n = $partes_fim_n[1] - 1;
            $ano_fim_n = $partes_fim_n[0];
            $nova_data_fim = $ano_fim_n . ',' . $mes_fim_n . ',' . $dia_fim_n;

            ?>    
                [ "<?php echo $evento->tipo; ?>", "<?php  echo  $evento->nome_evento; ?>",  new Date("<?php echo $nova_data_ini; ?>"), new Date("<?php echo $data_fim_n; ?>") ],
            <?php

        }
        //
        //[ '1', 'George Washington', new Date(2017, 12, 04), new Date(2017, 12, 8) ],
        //[ '2', 'John Adams',        new Date(2017, 12, 11),  new Date(2017, 12, 15) ],
        //[ '3', 'Thomas Jefferson',  new Date(2017, 12, 13),  new Date(2018,1 , 28) ]
        ?>  


        ]);

                var options = {
                timeline: { groupByRowLabel: true, colorByRowLabel: true  }
        };

        chart.draw(dataTable, options);
        }

                        //}
    </script>
    <!-- FIM TIMELINE DOS EVENTOS -->   
    <div class="row">
        <div class="col-md-12">
          <!-- Custom Tabs -->
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#tab_1" data-toggle="tab">Timeline</a></li>
              <li><a href="#tab_2" data-toggle="tab">Fases</a></li>
            </ul>
            <div class="tab-content">
              
              <!-- /.tab-pane -->
              <div class="tab-pane" id="tab_2">
                 <?php
                        $fases = $this->projetos_model->getAllFaseByProjeto();
                        foreach ($fases as $fase) {
                            $fase_id = $fase->id;

                            $soma_porc_acoes_concluidas_fase = 0;
                            $soma_porc_acoes_pendentes_fase = 0;
                            $soma_porc_acoes_atrasadas_fase = 0;
                            $cont_qtde_item_fase = 0;
                            $cont_qtde_item_evento_fase = 0;
                            $coma_total_acoes_itens = 0;
                            $coma_total_acoes_concluidas_itens = 0;
                            $soma_valores_zerado = 0;
                            $cont_qtde_evento = 0;

                             $ordem = 'ordem';
                             $eventos = $this->projetos_model->getAllEventosProjetoByFase($fase_id);
                             foreach ($eventos as $evento) {

                                  $cont_qtde_item_evento = 0;
                                   $soma_acoes_evento = 0;
                                   
                                   $intes_eventos2 = $this->projetos_model->getAllItemEventosProjeto($evento->id,'tipo','asc');
                                   foreach ($intes_eventos2 as $item2) {

                                            $quantidade_acoes_item = $this->projetos_model->getQuantidadeAcaoByItemEvento($item2->id);

                                            //Qtde de Ações concluídas
                                            $concluido = $this->projetos_model->getAcoesConcluidasByPItemEvento($item2->id);
                                            $quantidade_acoes_concluidas_item =  $concluido->quantidade;
                                            $coma_total_acoes_concluidas_itens += $quantidade_acoes_concluidas_item;

                                            //Qtde de ações Pendentes
                                            $item_pendente =    $this->projetos_model->getAcoesPendentesByItemEvento($item2->id);
                                            $item_avalidacao =  $this->projetos_model->getAcoesAguardandoValidacaoByItemEvento($item2->id);
                                            $itens_pendentes =  $item_pendente->quantidade + $item_avalidacao->quantidade;
                                            $soma_porc_acoes_pendentes_fase += $itens_pendentes;

                                            $atrasadas = $this->projetos_model->getAcoesAtrasadasByItemEvento($item2->id);
                                            $acoes_atrasadas = $atrasadas->quantidade;
                                            $soma_porc_acoes_atrasadas_fase += $acoes_atrasadas;

                                            $cont_qtde_item_fase += $quantidade_acoes_item->quantidade;
                                           /**  SOMA AS PORCENTAGENS DO ÍTEM PARA GERAR A MÉDIA DO EVENTO*/
                                            $cont_qtde_item_evento ++;
                                           // $soma_valores_zerado ++;
                                           $cont_qtde_item_evento_fase ++;
                                             if ($quantidade_acoes_item->quantidade == 0){
                                                //$cont_qtde_item_fase += 1;
                                            }
                                            
                                            
                                        }
                                        
                                     if($cont_qtde_item_evento == 0){
                                         $soma_valores_zerado += 1;
                                    }else{
                                     //   $soma_itens_sem_acao += $cont_qtde_item_evento;
                                    }
                                    
                                    $cont_qtde_evento++;
                             }
                            
                             if($cont_qtde_evento == 0){
                                     $soma_valores_zerado += 1;
                                }
                          //   echo 'total : '.$soma_acoes_evento.' Conc :'.$coma_total_acoes_concluidas_itens;

                             $total_acoes_projeto = $cont_qtde_item_fase + $soma_itens_sem_acao + $soma_valores_zerado;
                             $total_acoes_pendentes =  $soma_porc_acoes_pendentes_fase + $soma_valores_zerado + $soma_itens_sem_acao;

                             $porc_concluido = ($coma_total_acoes_concluidas_itens * 100)/$total_acoes_projeto;
                             $porc_pendente = ($total_acoes_pendentes * 100)/$total_acoes_projeto;
                             $porc_atrasado = ($soma_porc_acoes_atrasadas_fase * 100)/$total_acoes_projeto;

                        ?>


                            <table style="width:100%; background-color: gray" id="example-table" class="table table-striped table-bordered table-hover table-striped">

                                    <tr>
                                        <td style="width: 25%; ">
                                            <div class="portlet-title">
                                                <h4><?php echo $fase->nome_fase; ?></h4>
                                            </div>
                                        </td>
                                        <td style="width: 75%; ">
                                            <div class="progress">
                                              <div class="progress-bar progress-bar-success" role="progressbar" style="width:<?php echo $porc_concluido;  ?>%">
                                               <?php if($porc_concluido != 100){ echo  substr($porc_concluido,0,5); }else{ echo $porc_concluido; } ?> % Concluído
                                              </div>
                                              <div class="progress-bar progress-bar-warning" role="progressbar" style="width:<?php echo $porc_pendente;  ?>%">
                                               <?php if($porc_pendente != 100){ echo  substr($porc_pendente,0,5); }else{ echo $porc_pendente; } ?>% Pendente
                                              </div>
                                              <div class="progress-bar progress-bar-danger" role="progressbar" style="width:<?php  echo $porc_atrasado;  ?>%">
                                               <?php if($porc_atrasado != 100){ echo  substr($porc_atrasado,0,5); }else{ echo $porc_atrasado; } ?>% Atrasado
                                              </div>
                                            </div>
                                        </td>
                                    </tr>
                                </table>




                            <?php } ?>
              </div>
              <!-- /.tab-pane -->
              
              <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
              
              <div class="tab-pane active" id="tab_1">
               <div class="row">
                   <div class="col-lg-12">
                        <div style="min-height:100%;  width: 100%;  " id="timeline"></div>
                   </div>
                </div>   
              </div>
              <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
          </div>
          <!-- nav-tabs-custom -->
        </div>    
    </div> 
    
  
    
    
    </section>
    