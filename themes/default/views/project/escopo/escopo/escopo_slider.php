<?php
?>
<!DOCTYPE html>
<html lang="en">

<?php
//$this->load->view($this->theme . 'usuarios/new/header_user', $meta);
// $this->load->view($this->theme . 'usuarios/new/head'); ?>
   
 <head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
 <title>SIG - SISTEMA INTEGRADO DE GESTÃO </title>
 
  <script src="//code.jquery.com/jquery-1.10.2.js"></script>
  <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="<?= $assets ?>bi/bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?= $assets ?>bi/bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="<?= $assets ?>bi/bower_components/Ionicons/css/ionicons.min.css">
  <!-- bootstrap slider -->
  <link rel="stylesheet" href="<?= $assets ?>bi/plugins/bootstrap-slider/slider.css">
 
  <!-- Theme style -->
  <link rel="stylesheet" href="<?= $assets ?>bi/dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="<?= $assets ?>bi/dist/css/skins/_all-skins.min.css">
  <!-- Google Font -->
  <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
  
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

  
</head>

<script>
  $(function() {
    $( "#sortable" ).sortable();
    $( "#sortable" ).disableSelection();
    
      
    var ul_sortable = $('.sortable');
    ul_sortable.sortable({
        revert: 100,
        placeholder: 'placeholder'
    });
    ul_sortable.disableSelection();
    var btn_save = $('li.save'),
        div_response = $('#response');
    btn_save.on('mouseup', function(e) {
        e.preventDefault();
        setTimeout(function(){ 
        var sortable_data = ul_sortable.sortable('serialize');
      //  div_response.text('aqui teste');
        $.ajax({
            data: sortable_data,
            type: 'POST',
            url: '../../../../escopo_slider/save_fase.php',
            success:function(result) {
               // location.reload();
                div_response.text(result);
            }
        });
         }, 500);
    });
    
  });






  </script>
  <style>
      li {list-style-type:none;}
      
      #scroll {
          width:300px;
          height:170px;
          background-color:#F2F2F2;
          overflow:auto;
        }
  </style>
  
  
<script src="<?= $assets ?>slider/js/jquery.min.js"></script>
<script src="<?= $assets ?>slider/js/r-slider.js"></script>
  
  
  
  <body class="hold-transition sidebar-collapse sidebar-mini">
      
       
      
 
             <?php
                $usuario = $this->session->userdata('user_id');
                $projetos = $this->site->getProjetoAtualByID_completo($usuario);
                $perfil_atual = $projetos->group_id;
                $perfis_user = $this->site->getUserGroupAtual($perfil_atual);

              
                ?> 
                   
                <center>
                            <br><br>
                            <div style="width: 70%;" >
                                <?php
                                $usuario = $this->session->userdata('user_id');
                                $projetos_user = $this->site->getAllProjetosUsers($usuario);
                                $cont = 1;
                                $qtde_perfis_user = 0;
                                //   foreach ($projetos_user as $item) {
                                $id_projeto = $projetos->projeto_atual;
                                $wu3[''] = '';
                                $projeto = $this->atas_model->getProjetoByID($id_projeto);


                                /*
                                 * VERIFICA SE TEM AÇÕES AGUARDANDO VALIDAÇÃO
                                 */
                                $quantidadeAvalidacao = $this->site->getAllPlanosAguardandoValidacao($id_projeto);
                                $acoes_aguardando_validacao = $quantidadeAvalidacao->quantidade;
                                ?>

                                <a href="<?= site_url('Login_Projetos/projeto_ata/' . $projeto->id); ?>" class="btn btn-block btn-social btn-lg " style="background-color: <?php echo $projeto->botao; ?>">
                                    <i style="color:#ffffff;" class="fa fa-tasks fa-fw fa-3x"></i>
                                    <font style="color:#ffffff; font-weight:bold;">  <?php echo $projeto->projeto; ?>  </font>  
                                <?php if ($acoes_aguardando_validacao > 0) { ?>  
                                    <font style="color:#ffffff; font-size: 14px; margin-left: 15px;"><?php if ($acoes_aguardando_validacao > 1) { ?>  
                                        <?php echo $acoes_aguardando_validacao; ?> Ações A.Validação 
                                            <?php } else { ?>  <?php echo $acoes_aguardando_validacao; ?> Ação A. Validação <?php } ?>
                                    </font>  
                                        <?php } ?>
                                </a>
                                    <?php
                                    $soma_porc_acoes_concluidas_fase = 0;
                                    $soma_porc_acoes_pendentes_fase = 0;
                                    $soma_porc_acoes_atrasadas_fase = 0;
                                    $cont_qtde_item_fase = 0;
                                    $cont_qtde_item_evento_fase = 0;
                                    $coma_total_acoes_itens = 0;
                                    $coma_total_acoes_concluidas_itens = 0;
                                    $soma_valores_zerado = 0;
                                    $soma_itens_sem_acao = 0;

                                    $tipos = $this->projetos_model->getAllTipoEventosProjeto($id_projeto,'ordem','asc');
                                    foreach ($tipos as $tipo) {
                                        $tipo_evento = $tipo->tipo;


                                        $ordem = 'ordem';
                                        $eventos = $this->projetos_model->getAllEventosProjetoByTipo($tipo_evento, $id_projeto, $ordem, 'asc');
                                        foreach ($eventos as $evento) {


                                            $soma_acoes_evento = 0;
                                            $cont_qtde_item_evento = 0;
                                            $intes_eventos2 = $this->projetos_model->getAllItemEventosProjeto($evento->id, 'tipo', 'asc');
                                            foreach ($intes_eventos2 as $item2) {

                                                $quantidade_acoes_item = $this->projetos_model->getQuantidadeAcaoByItemEvento($item2->id);

                                                //Qtde de Ações concluídas
                                                $concluido = $this->projetos_model->getAcoesConcluidasByPItemEvento($item2->id);
                                                $quantidade_acoes_concluidas_item = $concluido->quantidade;
                                                $coma_total_acoes_concluidas_itens += $quantidade_acoes_concluidas_item;

                                                //Qtde de ações Pendentes
                                                $item_pendente = $this->projetos_model->getAcoesPendentesByItemEvento($item2->id);
                                                $item_avalidacao = $this->projetos_model->getAcoesAguardandoValidacaoByItemEvento($item2->id);
                                                $itens_pendentes = $item_pendente->quantidade + $item_avalidacao->quantidade;
                                                $soma_porc_acoes_pendentes_fase += $itens_pendentes;

                                                $atrasadas = $this->projetos_model->getAcoesAtrasadasByItemEvento($item2->id);
                                                $acoes_atrasadas = $atrasadas->quantidade;

                                                $soma_porc_acoes_atrasadas_fase += $acoes_atrasadas;

                                                $cont_qtde_item_fase += $quantidade_acoes_item->quantidade;



                                                /*
                                                 *  SOMA AS PORCENTAGENS DO ÍTEM PARA GERAR A MÉDIA DO EVENTO
                                                 */


                                                $cont_qtde_item_evento ++;
                                                // $soma_valores_zerado ++;
                                                $cont_qtde_item_evento_fase ++;

                                                if ($quantidade_acoes_item->quantidade == 0) {
                                                    //$cont_qtde_item_fase += 1;
                                                }
                                            }


                                            if ($cont_qtde_item_evento == 0) {

                                                $soma_valores_zerado += 1;
                                            } else {
                                                $soma_itens_sem_acao += $cont_qtde_item_evento;
                                            }
                                        }
                                    }
                                    //echo $soma_porc_acoes_atrasadas_fase;

                                    $total_acoes_projeto = $cont_qtde_item_fase + $soma_itens_sem_acao + $soma_valores_zerado;
                                    $total_acoes_pendentes = $soma_porc_acoes_pendentes_fase + $soma_valores_zerado + $soma_itens_sem_acao;

                                    // $total_atrasado = $coma_total_acoes_concluidas_itens - $total_acoes_pendentes;


                                    $porc_concluido = ($coma_total_acoes_concluidas_itens * 100) / $total_acoes_projeto;
                                    $porc_pendente = ($total_acoes_pendentes * 100) / $total_acoes_projeto;
                                    $porc_atrasado = ($soma_porc_acoes_atrasadas_fase * 100) / $total_acoes_projeto;
                                    ?>
                                <div class="progress">
                                    <div class="progress-bar progress-bar-success" role="progressbar" style="width:<?php echo $porc_concluido; ?>%">
                                <?php if ($porc_concluido != 100) {
                                    echo substr($porc_concluido, 0, 4);
                                } else {
                                    echo $porc_concluido;
                                } ?> % Concluído
                                    </div>
                                    <div class="progress-bar progress-bar-warning" role="progressbar" style="width:<?php echo $porc_pendente; ?>%">
                                <?php if ($porc_pendente != 100) {
                                    echo substr($porc_pendente, 0, 2);
                                } else {
                                    echo $porc_pendente;
                                } ?>% Pendente
                                    </div>
                                    <div class="progress-bar progress-bar-danger" role="progressbar" style="width:<?php echo $porc_atrasado; ?>%">
                                <?php if ($porc_atrasado != 100) {
                                    echo substr($porc_atrasado, 0, 4);
                                } else {
                                    echo $porc_atrasado;
                                } ?>% Atrasado
                                    </div>
                                </div>

                            </div>   
                            
                            <div class="page-title col-md-12">
                                <section   class="col-lg-12  ">
                                   <div class="col-md-12"> 
                                    <ol class="breadcrumb">
                                        <li><i class="fa fa-user"></i>  Gestor do Projeto:    <?php echo $projetos->gerente_area; ?>
                                        </li>
                                        <li ><i class="fa fa-calendar"></i> Início : <?php echo date("d/m/Y", strtotime($projetos->dt_inicio)); ?> </li>
                                         <li ><i class="fa fa-calendar"></i> Fim : <?php echo date("d/m/Y", strtotime($projetos->dt_final)); ?> </li>
                                    </ol>
                                        <?php
                                        $dt_inicio = $projetos->dt_inicio;
                                        $dt_fim = $projetos->dt_final;
                                        $dias_projeto = $this->projetos_model->getDiferencaDiasProjeto($dt_inicio, $dt_fim);
                                        $total_dias = $dias_projeto->dias;


                                        ?>
                                          
                                          
                                       <div id="container">
                                           
                                           
                                       </div>
                                        <div style="margin-top: 100px;" id="result">Previsto De <?php echo date("d/m/Y", strtotime($projetos->dt_inicio)); ?> Até <?php echo date("d/m/Y", strtotime($projetos->dt_final)); ?> </div>
                                        <script>
                                            var A = new slider({
                                                start: 0,
                                                step: 1,
                                                end: <?php echo $total_dias; ?>,
                                                value: [0, <?php echo $total_dias; ?>],
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
                                </section>      
                                 
                                
                        </div>
                           
                        </center>
                         
  
  <br><bR><br>
                        
                  
               <!--  FASES DO PROJETO  -->
              <!--   <div class="alert alert-success" id="response" role="alert"></div> -->
               
              <div style="margin-top: 30px;" class="col-md-12">
                        <h3>FASES DO PROJETO</h3>
                            
                        <section   class="col-lg-12 connectedSortable sortable ">
                        
                              <?php
                              $fases_projeto = $this->projetos_model->getAllFasesProjeto($projetos->projeto_atual);
                             
                              // print_r($regras_analise);
                              foreach ($fases_projeto as $regAnalise) {
                                  $cgs_ra[$regAnalise->id] = $regAnalise->nome_fase;
                                  
                                   $inicio_fase = $this->projetos_model->getDiferencaDiasProjeto($dt_inicio, $regAnalise->data_inicio);
                                   $qtde_dias_inicio = $inicio_fase->dias;
                                //  echo $qtde_dias_inicio.'<br>';
                                  
                                   $dias_fase = $this->projetos_model->getDiferencaDiasProjeto($regAnalise->data_inicio, $regAnalise->data_fim);
                                   $qtde_dias_fim = $dias_fase->dias;
                                  // echo $qtde_dias_fim;
                                   
                                 // echo $regAnalise->id;
                                  ?>
                                 <li style="background-color: #ffffff;" class="save" id=item-<?php echo $regAnalise->id; ?>>
                                     
                                     
                                      <div class="box box-default collapsed-box box-solid">
                                          <div style="background-color: #ffffff;" class="box-header with-border">
                                                <span class="handle">
                                                  <i class="fa fa-ellipsis-v"></i>
                                                  <i class="fa fa-ellipsis-v"></i>
                                              </span>
                                              
                                                <h3 class="box-title"><?php echo $regAnalise->nome_fase; ?></h3>
                                                
                                                <input type="text" value="" class="slider form-control" data-slider-min="-<?php echo $qtde_dias_inicio; ?>" data-slider-max="<?php echo $total_dias; ?>"
                                                 data-slider-step="5" data-slider-value="[<?php echo $qtde_dias_inicio; ?>,<?php echo $qtde_dias_fim; ?>]" data-slider-orientation="horizontal"
                                                 data-slider-selection="before" data-slider-tooltip="show" data-slider-id="<?php echo $regAnalise->cor_slider; ?>">
                                                
                                                <div class="box-tools pull-right">
                                                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                                                    </button>
                                                </div>
                                                
                                            </div>
                                            <div id="<?php echo $regAnalise->id; ?>" class="box-body">
                                               <div class="portlet-body">
                                              --
                                              </div>
                                           </div>   
                                      </div>   
                                     
                                     

                                  </li>
                                  <?php
                              }
                              ?>

                         
                      </section>
                    </div>
               <br>
    <!-- /.content -->
<!-- jQuery UI 1.11.4 -->
<script src="<?= $assets ?>slider/js/jquery.min.js"></script>
<script src="<?= $assets ?>slider/js/r-slider.js"></script>


<script src="<?= $assets ?>bi/bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="<?= $assets ?>bi/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- FastClick -->
<script src="<?= $assets ?>bi/bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="<?= $assets ?>bi/dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?= $assets ?>bi/dist/js/demo.js"></script>
<!-- Bootstrap slider -->

 <script src="<?= $assets ?>bi/plugins/bootstrap-slider/bootstrap-slider.js"></script>



<!-- <script src="<?= $assets ?>bi/bower_components/jquery-ui/jquery-ui.min.js"></script> -->
 
 
    <script>
      $(function () {
        /* BOOTSTRAP SLIDER */
        $('.slider').slider()
      })
    </script>      
                
    </body>

</html>
