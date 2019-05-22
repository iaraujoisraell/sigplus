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
              <li class="active"><a href="#tab_1" data-toggle="tab">Setores</a></li>
              
            </ul>
            <div class="tab-content">
              <div class="tab-pane active" id="tab_1">
                  <!-- 
                AÇÕES POR ÁREA E SETOR
                -->
                <div class="row">
                    <div class="col-lg-12">

                        <div class="portlet portlet-green">
                            
                            <div class="portlet-body">
                                <ul class="nav nav-tabs">

                                    <?php
                                    $cont = 1;
                                    foreach ($areas_projeto as $area_projeto) {

                                       $id_pai = $area_projeto->id_pai;
                                       $superintendencia = $area_projeto->descricao;

                                        // PEGA A QTDE DE AÇÕES POR AREAS E POR PROJETO
                                        $quantidade_area = $this->projetos_model->getAcoesSetorPaiByProjeto($id_pai);
                                        $qtde_area = $quantidade_area->qtde;
                                        ?>
                                    <li <?php if ($cont == 1) { ?> class="active" <?php } ?> ><a class="  tile <?php if ($cont % 2 == 0) { ?> blue <?php } else { ?> orange <?php } ?>  " href="#<?php echo $id_pai; ?>" data-toggle="tab"><?php echo $superintendencia; ?></a>
                                    </li>

                                    <?php
                                    $cont++;
                                }
                                
                                ?>
                                </ul>
                                <div id="myTabContent" class="tab-content">
                                    <?php
                                    $cont = 1;
                                    foreach ($areas_projeto as $area_projeto) {

                                           $id_pai = $area_projeto->id_pai;
                                           $superintendencia = $area_projeto->descricao;

                                            // PEGA A QTDE DE AÇÕES POR AREAS E POR PROJETO
                                            $quantidade_area = $this->projetos_model->getAcoesSetorPaiByProjeto($id_pai);
                                            $qtde_area = $quantidade_area->qtde;
                                        ?>
                                        <!-- 
                                            TABELA COM AS AÇÕES DO SETOR
                                        -->
                                        <div class="tab-pane fade in <?php if ($cont == 1) { ?> active <?php } ?>"  id="<?php echo $id_pai; ?>">

                                            <!-- 
                                                AQUI MOSTRA AS CAIXAS COM AS 
                                            QUANTIDADE DE AÇÕES DOS SETORES DA ÁREA SELECIONADA

                                            -->
                                        <?php
                                        $cont_setor_area = 1;
                                        // PEGA OS SETORES POR AREAS E POR PROJETO  $projeto,
                                         if($perfil_atual == 2){
                                             //GESTOR
                                         //   $setores_areas = $this->atas_model->getAllSetorAreaUsuario($projeto, $usuario,$id_superintendente);
                                        }else  if($perfil_atual == 3){
                                            //SUPERINTENDENTE

                                           //         $setores_areas = $this->atas_model->getAllSetorArea($projeto, $id_superintendente);//$this->projetos_model->getAllSetorArea($id_superintendente);                                                           
                                        }else{
                                            //EDP

                                        }

                                        $setores_areas = $this->atas_model->getAllSetorArea($id_pai); 
                                        $cont_caixa = 1;
                                        foreach ($setores_areas as $setor_area) {

                                            $id_setor = $setor_area->id;
                                            $nome_setor = $setor_area->nome;
                                           

                                            // PEGA A QTDE DE AÇÕES POR AREAS E POR PROJETO
                                            $quantidade_acoes_setor = $this->atas_model->getAllitemPlanosProjetoSetorCont($id_setor);
                                            $qtde_total_acoes_setor = $quantidade_acoes_setor->quantidade;
                                            // PEGA A QTDE DE AÇÕES CONCLUÍDAS POR AREAS E POR PROJETO
                                            $quantidade_acoes_concluida_setor = $this->atas_model->getAllitemPlanosProjetoSetorContConcluido($id_setor);
                                            $qtde_concluido_setor = $quantidade_acoes_concluida_setor->quantidade;
                                            // PEGA A QTDE DE AÇÕES PENDENTES POR AREAS E POR PROJETO
                                            $quantidade_acoes_pendente_setor = $this->atas_model->getAllitemPlanosProjetoSetorContPendente('PENDENTE', $id_setor);
                                            $qtde_pendente_setor = $quantidade_acoes_pendente_setor->quantidade;
                                            // PEGA A QTDE DE AÇÕES PENDENTES POR AREAS E POR PROJETO
                                            $quantidade_acoes_atrasadas_setor = $this->atas_model->getAllitemPlanosProjetoSetorContPendente('ATRASADO',$id_setor);
                                            $qtde_atrasadas_setor = $quantidade_acoes_atrasadas_setor->quantidade;
                                            // PEGA A QTDE DE AÇÕES PENDENTES AGUARDANDO VALIDAÇÃO POR AREAS E POR PROJETO
                                            $quantidade_acoes_avalidacao_setor = $this->atas_model->getAllitemPlanosProjetoSetorContAguardandoValidacao($id_setor);
                                            $qtde_avalidacao_setor = $quantidade_acoes_avalidacao_setor->quantidade;

                                            $total_pendentes = $qtde_pendente_setor + $qtde_avalidacao_setor;

                                            //CALCULA A PERCENTAGEM CONCLUIDAS
                                            $calc_conc = $qtde_concluido_setor * 100;
                                            $perc_concluida_setor = ($calc_conc / $qtde_total_acoes_setor);
                                            
                                            //CALCULA A PERCENTAGEM PENDENTES
                                            $calc_pendente = $total_pendentes * 100;
                                            $perc_pendente_setor = ($calc_pendente / $qtde_total_acoes_setor);
                                            
                                           
                                            ?>
                                                <!-- 
                                            AQUI MOSTRA AS CAIXAS COM AS 
                                        QUANTIDADE DE AÇÕES POR SETOR
                                                -->      
                                                <script type="text/javascript">
                                                      google.charts.load("current", {packages:["corechart"]});
                                                      google.charts.setOnLoadCallback(drawChart);
                                                      function drawChart() {
                                                        var data = google.visualization.arrayToDataTable([
                                                          ['Ações', 'Quantidade'],
                                                          ['Work',     0],
                                                          ['Ações Arasadas',      <?php echo $qtde_atrasadas_setor; ?>],
                                                          ['Ações Pendentes',  <?php echo $total_pendentes; ?>],
                                                          ['Ações Concluídas', <?php echo $qtde_concluido_setor; ?>],
                                                          ['Sleep',    0]
                                                        ]);

                                                        var options = {
                                                          title: '<?php echo $nome_setor; ?>',
                                                          is3D: true,
                                                        };

                                                        var chart = new google.visualization.PieChart(document.getElementById('<?php echo $id_setor; ?>'));
                                                        chart.draw(data, options);
                                                      }
                                                    </script>
                                                    
                                                <div style="height: 200px; margin-top: 5px;" class="col-md-4 ">
                                                     <div id="<?php echo $id_setor; ?>" style="width: 400px; height: 200px;"></div>
                                                </div>
                                                  


                                        <?php   
                                           
                                            
                                        }
                                       
                                        ?>   

                                        <div class="row">
                                                <div class="col-lg-12">

                                                       <div class="portlet-body">
                                                            <ul class="nav nav-tabs">
                                                        <?php
                                                            
                                                            $cont_setor_area2 = 1;
                                                            // PEGA OS SETORES POR AREAS E POR PROJETO  $projeto,

                                                            $setores_areas = $this->atas_model->getAllSetorArea($id_pai);
                                                            foreach ($setores_areas as $setor_area2) {
                                                                $id_setor = $setor_area2->id;
                                                                $nome_setor2 = $setor_area2->nome;
                                                                // AKI CARREGA O NOME DO SETOR NA ABA
                                                                ?>
                                                                    <li class="<?php if ($cont_setor_area2++ == 1) { ?> active <?php } ?>">
                                                                        <a href="#setor<?php echo $id_setor; ?>" data-toggle="tab"><?php echo $nome_setor2; ?></a>
                                                                    </li>

                                                            <?php } ?>   
                                                            </ul>
                                                        </div>

                                                        <div id="myPillsContent" class="tab-content">
                                                            <?php
                                                           
                                                            $cont_setor_area3 = 1;
                                                            $setores_areas2 = $this->atas_model->getAllSetorArea($id_pai);
                                                            
                                                          // print_r($setores_areas2);
                                                            foreach ($setores_areas2 as $setor_area3) {
                                                                    $id_setor2 = $setor_area3->id;
                                                                    //echo $id_setor;
                                                                    $nome_setor3 = $setor_area3->nome;

                                                                    // }
                                                                    ?>
                                                                    <div class="tab-pane fade in <?php if ($cont_setor_area3 == 1) { ?> active <?php } ?>" id="setor<?php echo $id_setor2; ?>">
                                                                    <!--
                                                                    AQUI CARREGA O NOME DE CASA RESPONSÁVEL PELAS AÇÕES DO SETOR
                                                                    -->
                                                                    <br>
                                                                    <div class="row">
                                                                        <div class="col-lg-12">
                                                                            <div class="portlet portlet-default">
                                                                               

                                                                                <div class="portlet-body">
                                                                                    <ul id="myTab" class="nav nav-tabs">
                                                                                            <?php
                                                                                            $cont_user_setor_area = 1;
                                                                                            // PEGA OS SETORES POR AREAS E POR PROJETO  $projeto,
                                                                                            
                                                                                            $user_setores = $this->atas_model->getAllUserPlanosProjetoSetor($id_setor2); //$this->projetos_model->getAllSetorArea($id_superintendente);
                                                                                           // ECHO $setor_area3->setor_id;
                                                                                            foreach ($user_setores as $user_setor) {

                                                                                               $id = $user_setor->id;
                                                                                                $nome = $user_setor->nome;

                                                                                                ?>
                                                                                        <li class=""><a class="btn btn-default" href="#nome<?php echo $id.$id_setor2 ?>" data-toggle="tab"><?php echo $nome; ?></a>
                                                                                                   </li>
                                                                                                <?php $cont_user_setor_area++;
                                                                                            } 

                                                                                            ?>

                                                                                    </ul>
                                                                                    
                                                                                    <div id="myTabContent" class="tab-content">
                                                                                        <?php

                                                                                        $cont_user_setor_area_acao = 1;
                                                                                        // PEGA OS SETORES POR AREAS E POR PROJETO  $projeto,
                                                                                        $user_setores2 = $this->atas_model->getAllUserPlanosProjetoSetor($id_setor2); //$this->projetos_model->getAllSetorArea($id_superintendente);
                                                                                        foreach ($user_setores2 as $user_setor2) {

                                                                                            $id1 = $user_setor2->id;
                                                                                            $nome = $user_setor2->nome;

                                                                                            ?>
                                                                                            <div class="tab-pane fade " id="nome<?php echo $id1.$id_setor2 ?>">

                                                                                                 <div class="portlet-body">
                                                                                                    <?php
                                                                                                        $wu4[''] = '';
                                                                                                        $cont_presidencia = 0;
                                                                                                        /*
                                                                                                         * CARREGA AS AÇÕES DE UM PROJETO E UM SETOR E USUÁRIO
                                                                                                         * 
                                                                                                         */
                                                                                                        $cont_quantidade_atrasado_acoes = 0;
                                                                                                        $cont_quantidade_pendente_acoes = 0;
                                                                                                        $cont_quantidade_av_acoes = 0;
                                                                                                        $cont_quantidade_cancelados = 0;
                                                                                                        $cont_quantidade_concluido_acoes = 0;
                                                                                                        $acoes_setor = $this->atas_model->getAllitemPlanosProjetoSetorUser($id_setor2, $id1);

                                                                                                        foreach ($acoes_setor as $plano_precidencia) {
                                                                                                            $cont_presidencia ++;
                                                                                                          //  $data_prazo = $plano_precidencia->data_termino;
                                                                                                          //  $data_entrega = $plano_precidencia->data_retorno_usuario;
                                                                                                            $status2 = $plano_precidencia->status;
                                                                                                           // $descricao = $plano_precidencia->descricao;

                                                                                                            if ($status2 == 'CONCLUÍDO') {
                                                                                                                $cont_quantidade_concluido_acoes++;
                                                                                                                /*
                                                                                                                 * SE A DATA DE CONLUSÃO FOR <= A DATA DO PRAZO
                                                                                                                 * CONCLUÍDO
                                                                                                                 */

                                                                                                                if ($data_entrega <= $data_prazo) {
                                                                                                                    $novo_status = 'CONCLUÍDO';

                                                                                                                }

                                                                                                                /*
                                                                                                                 * SE A DATA DE CONLUSÃO FOR > A DATA DO PRAZO
                                                                                                                 * CONCLUÍDO FORA DO PRAZO
                                                                                                                 */
                                                                                                                if ($data_entrega > $data_prazo) {
                                                                                                                    $novo_status = 'CONCLUÍDO FORA DO PRAZO';
                                                                                                                }
                                                                                                            } else

                                                                                                            if ($status2 == 'PENDENTE') {
                                                                                                                $dataHoje = date('Y-m-d H:i:s');
                                                                                                                /*
                                                                                                                 * SE A DATA ATUAL FOR < A DATA DO PRAZO
                                                                                                                 * PENDENTE
                                                                                                                 */
                                                                                                                if ($dataHoje <= $data_prazo) {
                                                                                                                    $novo_status = 'PENDENTE';
                                                                                                                    $cont_quantidade_pendente_acoes++;
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
                                                                                                                    $cont_quantidade_atrasado_acoes ++;

                                                                                                                    // Usa a função criada e pega o timestamp das duas datas:
                                                                                                                    $time_inicial = geraTimestamp($this->sma->hrld($dataHoje));
                                                                                                                    $time_final = geraTimestamp($this->sma->hrld($data_prazo));
                                                                                                                    // Calcula a diferença de segundos entre as duas datas:
                                                                                                                    $diferenca = $time_final - $time_inicial; // 19522800 segundos
                                                                                                                    // Calcula a diferença de dias
                                                                                                                    $dias = (int) floor($diferenca / (60 * 60 * 24)); // 225 dias

                                                                                                                    if ($dias >= '-5') {
                                                                                                                        $qtde_dias = '+5';
                                                                                                                    } else if (($dias < '-5') && ($dias >= '-10')) {
                                                                                                                        $qtde_dias = '+10';
                                                                                                                    } else if ($dias < '-10') {
                                                                                                                        $qtde_dias = '+15';
                                                                                                                    }
                                                                                                                }
                                                                                                            } else 

                                                                                                            if ($status2 == 'AGUARDANDO VALIDAÇÃO') {
                                                                                                                $novo_status = 'AGUARDANDO VALIDAÇÃO';
                                                                                                                $cont_quantidade_av_acoes++;
                                                                                                            }else 

                                                                                                            if ($status2 == 'CANCELADO') {
                                                                                                                $novo_status = 'CANCELADO';
                                                                                                                $cont_quantidade_cancelados++;
                                                                                                            }
                                                                                                            ?>   

                                                                                                    <?php 

                                                                                                        }

                                                                                                    ?>
                                                                                                     <br>
                                                                                                     <label class="label label-primary">Total de Ações: <?php echo $cont_presidencia; ?></label>
                                                                                                     <label class="label label-success">Ações Concluídas: <?php echo $cont_quantidade_concluido_acoes; ?></label>
                                                                                                     <label class="label label-warning">Ações Pendentes: <?php echo $cont_quantidade_pendente_acoes + $cont_quantidade_av_acoes; ?></label>
                                                                                                     <label class="label label-danger">Ações Atrasadas: <?php echo $cont_quantidade_atrasado_acoes; ?></label>
                                                                                                     <label class="label bg-black">Ações Canceladas: <?php echo $cont_quantidade_cancelados; ?></label>
                                                                                                    <br><br>
                                                                                                    
                                                                                                    
                                                                                                    
                                                                                                    <div class="table-responsive">
                                                                                                        <div class="box-body">                                   
                                                                                                        <table id="dashboard_setores" class="table-bordered table-striped" >
                                                                                                            <thead>
                                                                                                                <tr>
                                                                                                                    <th>-</th>
                                                                                                                   
                                                                                                                    <th>Descrição</th>
                                                                                                                    <th>Dt Prazo</th>
                                                                                                                    <th>Dt Conclusão</th>
                                                                                                                    <th>Responsável</th>
                                                                                                                    <th>Setor</th>
                                                                                                                    <th style="width:10%;  text-align: center;">Status</th>
                                                                                                                    <th style="width:10%;  text-align: center;"><?php echo $this->lang->line("Opções"); ?></th>
                                                                                                                </tr>
                                                                                                            </thead>
                                                                                                            <tbody>
                                                                                                                        <?php
                                                                                                                        $wu4[''] = '';
                                                                                                                        $cont_presidencia = 0;
                                                                                                                        //$acoes_por_areas = $this->projetos_model->getAllitemPlanosProjetoArea($projeto, $id_superintendente);
                                                                                                                        /*
                                                                                                                         * CARREGA AS AÇÕES DE UM PROJETO E UM SETOR E USUÁRIO
                                                                                                                         * 
                                                                                                                         */

                                                                                                                        $acoes_setor2 = $this->atas_model->getAllitemPlanosProjetoSetorUser($id_setor2, $id1);
                                                                                                                        foreach ($acoes_setor2 as $plano_precidencia2) {
                                                                                                                            $cont_presidencia ++;
                                                                                                                            $id_plano = $plano_precidencia2->idplanos;
                                                                                                                            $data_prazo = $plano_precidencia2->data_termino;
                                                                                                                            $data_entrega_demanda = $plano_precidencia2->data_entrega_demanda;
                                                                                                                            $data_entrega = $plano_precidencia2->data_retorno_usuario;
                                                                                                                            $status = $plano_precidencia2->status;
                                                                                                                            $descricao = $plano_precidencia2->descricao; //
                                                                                                                            
                                                                                                                            if ($status == 'CONCLUÍDO') {

                                                                                                                                /*
                                                                                                                                 * SE A DATA DE CONLUSÃO FOR <= A DATA DO PRAZO
                                                                                                                                 * CONCLUÍDO
                                                                                                                                 */
                                                                                                                                if ($data_entrega <= $data_prazo) {
                                                                                                                                    $novo_status = 'CONCLUÍDO';
                                                                                                                                }

                                                                                                                                /*
                                                                                                                                 * SE A DATA DE CONLUSÃO FOR > A DATA DO PRAZO
                                                                                                                                 * CONCLUÍDO FORA DO PRAZO
                                                                                                                                 */
                                                                                                                                if ($data_entrega > $data_prazo) {
                                                                                                                                    $novo_status = 'CONCLUÍDO FORA DO PRAZO';
                                                                                                                                }
                                                                                                                            } else

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
                                                                                                                                 * */

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
                                                                                                                                        $qtde_dias = '+5';
                                                                                                                                    } else if (($dias < '-5') && ($dias >= '-10')) {
                                                                                                                                        $qtde_dias = '+10';
                                                                                                                                    } else if ($dias < '-10') {
                                                                                                                                        $qtde_dias = '+15';
                                                                                                                                    }
                                                                                                                                }
                                                                                                                            } 
                                                                                                                            else if ($status == 'AGUARDANDO VALIDAÇÃO') {
                                                                                                                                $novo_status = 'AGUARDANDO VALIDAÇÃO';
                                                                                                                                
                                                                                                                            }else if ($status == 'CANCELADO') {
                                                                                                                                $novo_status = 'CANCELADO';
                                                                                                                               
                                                                                                                            }
                                                                                                                            ?>   
                                                                                                                    <tr >
                                                                                                                        <td><font style="font-size: 12px;"><?php echo $cont_presidencia; ?></font></td>
                                                                                                                      
                                                                                                                       
                                                                                                                        <td ><font style="font-size: 12px;"><?php echo $id_plano.' - '.$descricao; ?> </font></td>
                                                                                                                        <td class="center"><font style="font-size: 10px;"><?php echo date('d/m/Y', strtotime($data_prazo)); ?></font></td>
                                                                                                                        <td class="center">
                                                                                                                            <font style="font-size: 10px;"> 
                                                                                                                                <?php
                                                                                                                                if ($status == 'CONCLUÍDO') {
                                                                                                                                    echo date('d/m/Y', strtotime($plano_precidencia2->data_retorno_usuario));
                                                                                                                                }?>
                                                                                                                            </font>
                                                                                                                        </td>
                                                                                                                        <td class="center"><font style="width: 10%;font-size: 12px;"> <?php echo $plano_precidencia2->first_name; ?></font></td>
                                                                                                                        <td class="center"><font style="width: 10%;font-size: 12px;"> <?php echo $plano_precidencia2->setor ?> </font></td>
                                                                                                                   
                                                                                                                    <?php if ($novo_status == 'CONCLUÍDO') { ?>
                                                                                                                            <td style="width: 10%;background-color: #007700;color: #ffffff" class="center"><font style="font-size: 12px;"><?php echo $novo_status; ?></font></td>
                                                                                                                    <?php } else if ($novo_status == 'CONCLUÍDO FORA DO PRAZO') {
                                                                                                                        ?>
                                                                                                                            <td style="width: 10%;background-color: #99ca63" class="center"><font style="font-size: 12px;"><?php echo $novo_status; ?></font></td>
                                                                                                                           <?php } else if ($novo_status == 'PENDENTE') {  ?>
                                                                                                                            <td style="width: 10%;background-color: #CB3500;color: #ffffff" class="center"><font style="font-size: 12px;"><?php echo $novo_status; ?></font></td>
                                                                                                                       <?php
                                                                                                                            } else if ($novo_status == 'ATRASADO') {
                                                                                                                                if ($dias >= '-5') {
                                                                                                                                    ?>
                                                                                                                                <td style=" background-color: #c7254e; color: #ffffff;" class="center"><font style="font-size: 12px;"><?php echo $novo_status . '  (' . $qtde_dias . ' dias ) '; ?></font></td>
                                                                                                                            <?php } else if (($dias < '-5') && ($dias >= '-10')) { ?> 
                                                                                                                                <td style="width: 10%; background-color: #d2322d; color: #ffffff;" class="center"><font style="font-size: 12px;"><?php echo $novo_status . '  (' . $qtde_dias . ' dias ) '; ?></font></td>

                                                                                                                            <?php } else if ($dias < '-10') { ?> 
                                                                                                                                <td style="width: 10%; background-color: gray; color: #ffffff;" class="center"><font style="font-size: 12px;"><?php echo $novo_status . '  (' . $qtde_dias . ' dias ) '; ?></font></td>

                                                                                                                            <?php } ?> 


                                                                                                                        <?php } else if ($novo_status == 'AGUARDANDO VALIDAÇÃO') { ?>
                                                                                                                            <td style="width: 10%; background-color: orange; color: #ffffff;" class="center"><font style="font-size: 12px;"><?php echo $novo_status; ?></font></td>
                                                                                                                       <?php } else if ($novo_status == 'CANCELADO') { ?>
                                                                                                                            <td style="width: 10%; background-color: #000000; color: #ffffff;" class="center"><font style="font-size: 12px;"><?php echo $novo_status; ?></font></td>
                                                                                                                            
                                                                                                                        <?php }?>     
                                                                                                                            <td style="width: 10%;"><a title="Visualizar Registro" class="btn btn-dropbox"  href="<?= site_url('project/consultar_acao/' .$id_plano); ?>"><i class="fa fa-edit"></i>  ABRIR </a></td> 
                                                                                                                    </tr>
                                                                                                                        <?php
                                                                                                                    }
                                                                                                                    ?>

                                                                                                            </tbody>

                                                                                                        </table>
                                                                                                        </div>    
                                                                                                    </div>
                                                                                                    <!-- /.table-responsive -->
                                                                                                </div>


                                                                                            </div>
                                                                                                <?php $cont_user_setor_area_acao++;
                                                                                            }  ?>

                                                                                        </div>
                                                                                </div>
                                                                                            <!-- /.portlet-body -->
                                                                            </div>
                                                                            <!-- /.portlet -->
                                                                        </div>
                                                                    </div>
                                                                    <!-- FIM AÇÃO POR USUARIO -->



                                                                </div>

                                                               <?php
                                                                $cont_setor_area3++;
                                                                }
                                                              ?>   
                                                            </div>

                                                 </div>
                                            </div>
                                                
                                       </div>
                                        <?php
                                        $cont++;
                                    }
                                    ?>       
                                </div>
                            </div>
                            <!-- /.portlet-body -->
                        </div>
                        <!-- /.portlet -->

                    </div>
                    <!-- /.col-lg-12 -->
                </div>
               
                 
              </div>
            
            </div>
            <!-- /.tab-content -->
          </div>
          <!-- nav-tabs-custom -->
        </div>    
    </div> 
    
  
    
    
    </section>
    

    
