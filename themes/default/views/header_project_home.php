<html>
   
    
<head>
    <meta charset="utf-8">
    <base href="<?= site_url() ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $Settings->site_name; ?></title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  
  
    
    
  <link rel="stylesheet" href="<?= $assets ?>bi/bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?= $assets ?>bi/bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="<?= $assets ?>bi/bower_components/Ionicons/css/ionicons.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="<?= $assets ?>bi/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?= $assets ?>bi/dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="<?= $assets ?>bi/dist/css/skins/_all-skins.min.css">
  
  
   <link rel="stylesheet" href="<?= $assets ?>bi/bower_components/fullcalendar/dist/fullcalendar.min.css">
  <link rel="stylesheet" href="<?= $assets ?>bi/bower_components/fullcalendar/dist/fullcalendar.print.min.css" media="print">
  
  
   <link href="<?= $assets ?>styles/theme_novo.css" rel="stylesheet"/>
  <link href="<?= $assets ?>styles/style_novo.css" rel="stylesheet"/>
  <script type="text/javascript" src="<?= $assets ?>js/jquery-2.0.3.min.js"></script>
  <script type="text/javascript" src="<?= $assets ?>js/jquery-migrate-1.2.1.min.js"></script>
  <script type="text/javascript" src="<?= $assets ?>js/funcoes.js"></script>
    
  
  
    <script type="text/javascript">
        /* Máscaras ER */
        function mascara(o,f){
           v_obj=o
           v_fun=f
           setTimeout("execmascara()",1)
        }
        function execmascara(){
           v_obj.value=v_fun(v_obj.value)
        }
        function mcep(v){
           v=v.replace(/\D/g,"")                    //Remove tudo o que não é dígito
           v=v.replace(/^(\d{5})(\d)/,"$1-$2")         //Esse é tão fácil que não merece explicações
           return v
        }
        function mdata(v){
           v=v.replace(/\D/g,"");                    //Remove tudo o que não é dígito
           v=v.replace(/(\d{2})(\d)/,"$1/$2");       
           v=v.replace(/(\d{2})(\d)/,"$1/$2");       

           v=v.replace(/(\d{2})(\d{2})$/,"$1$2");
           return v;
        }
        function mrg(v){
               v=v.replace(/\D/g,'');
               v=v.replace(/^(\d{2})(\d)/g,"$1.$2");
               v=v.replace(/(\d{3})(\d)/g,"$1.$2");
               v=v.replace(/(\d{3})(\d)/g,"$1-$2");
               return v;
        }
        function mvalor(v){
           v=v.replace(/\D/g,"");//Remove tudo o que não é dígito
           v=v.replace(/(\d)(\d{8})$/,"$1.$2");//coloca o ponto dos milhões
           v=v.replace(/(\d)(\d{5})$/,"$1.$2");//coloca o ponto dos milhares

           v=v.replace(/(\d)(\d{2})$/,"$1,$2");//coloca a virgula antes dos 2 últimos dígitos
           return v;
        }
        function id( el ){
               return document.getElementById( el );
        }
        function next( el, next )
        {
               if( el.value.length >= el.maxLength ) 
                       id( next ).focus(); 
        }
    </script>
  

</head>
 <?php 
      function exibirData($data){
	$rData = explode("-", $data);
	$rData = $rData[2].'/'.$rData[1].'/'.$rData[0];
	return $rData;
   }
    ?>
<?php 
 $usuario = $this->session->userdata('user_id');
 $users_dados = $this->site->geUserByID($usuario);
         
 $modulo_atual_id = $users_dados->modulo_atual;
 $menu_atual = $users_dados->menu_atual;
 $empresa_atual = $users_dados->menu_atual;
 
 $modulo = $this->owner_model->getModuloById($modulo_atual_id);
 $nome_modulo = $modulo->descricao;
 $cor_modulo = $modulo->cor;

 $modulo = $this->owner_model->getModuloById($modulo_atual_id);
 $nome_modulo = $modulo->descricao;
 
?>     

<!-- Bootstrap 3.3.7 -->
  <body class="sidebar-mini skin-blue-light">
      
      <div class="wrapper">
          
<?php

$this->load->view($this->theme . 'topo'); 
$this->load->view($this->theme . 'menu_esquerdo'); 
?>

          
<?php
$projetos = $this->projetos_model->getProjetoAtualByID_completo();
$id_projeto = $projetos->id;
$nome_projeto = $projetos->nome_projeto;
$gerente = $projetos->gerente_area;
$resp_tecnico_fase = $this->atas_model->getUserSetorByUserSetor($gerente);
$gerente_projeto = $resp_tecnico_fase->nome;
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
<div class="content-wrapper">
    
    
    <br>
        <div class="col-lg-12 ">
            <?php
                $dt_inicio = $projetos->dt_inicio;
                $dt_fim = $projetos->dt_final;
                $hoje = date("Y-m-d");
                $dias_projeto = $this->projetos_model->getDiferencaDiasProjeto($dt_inicio, $dt_fim);
                $dias_projeto_hoje = $this->projetos_model->getDiferencaDiasProjeto($dt_inicio, $hoje);
                $total_dias = $dias_projeto->dias;
                $andamento_dias = $dias_projeto_hoje->dias;
                if($andamento_dias > $total_dias){
                    $andamento_dias = $total_dias;
                }
                $percentagem = ($andamento_dias * 100)/$total_dias;
                ?>
                <div class="progress-group">
                    <span class="progress-text">Dias Completados</span>
                    <span class="progress-number"><b><?php echo $andamento_dias; ?></b>/<?php echo $total_dias; ?> dias</span>

                    <div class="progress sm">
                      <div class="progress-bar progress-bar-primary" style="width: <?php echo $percentagem; ?>%"></div>
                    </div>
                  </div> 
          <ol  class="breadcrumb">
              <li ><i class="fa fa-bookmark"></i>    <?php echo $nome_projeto.' '; ?> </li> <small class="label label-<?php echo $status_label; ?>" ><?php echo $status; ?></small>
              <li ><i class="fa fa-user"></i>  Gerente:    <?php echo $gerente_projeto; ?></li>
              <li  ><i class="fa fa-calendar"></i> Início : <?php echo date("d/m/Y", strtotime($projetos->dt_inicio)); ?> </li>
              <li ><i class="fa fa-flag-checkered"></i> Fim : <?php echo date("d/m/Y", strtotime($projetos->dt_final)); ?> </li>
               <?php if($status == 'EM AGUARDO'){ ?> 
                     <li class="pull-right"> <a title="O Projeto deve estar ativo para que se possa cadastrar ações e gerencia-lo." href="<?= site_url('Project/ativarProjeto/'.$projetos->id); ?>" ><small class="btn btn-success btn-sm" > ATIVAR PROJETO <i class="fa fa-check"></i></small></a>  </li>
            <?php }  ?>   
           </ol>
            
             <?php
                $soma_porc_acoes_concluidas_fase = 0;
                $soma_porc_acoes_pendentes_fase = 0;
                $soma_porc_acoes_atrasadas_fase = 0;
                $cont_qtde_item_fase = 0;
                $cont_qtde_item_evento_fase = 0;
                $soma_total_acoes_itens = 0;
                $coma_total_acoes_concluidas_itens = 0;
                $soma_valores_zerado = 0;
                $soma_fase_sem_evento = 0;
                $cont_qtde_evento = 0;
                $fases = $this->projetos_model->getAllFaseByProjeto();
                foreach ($fases as $fase) {
                    $fase_id = $fase->id;
                    $eventos = $this->projetos_model->getAllEventosProjetoByFase($fase_id);
                    
                    foreach ($eventos as $evento) {
                       $soma_acoes_evento = 0;
                       $cont_qtde_item_evento = 0;
                       
                       $intes_eventos2 = $this->projetos_model->getAllItemEventosProjeto($evento->id);
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

                                /*
                                *  SOMA AS PORCENTAGENS DO ÍTEM PARA GERAR A MÉDIA DO EVENTO
                                */

                                $cont_qtde_item_evento ++;
                                // $soma_valores_zerado ++;
                                $cont_qtde_item_evento_fase ++;

                                if ($quantidade_acoes_item->quantidade == 0){
                                    //$cont_qtde_item_fase += 1;
                                }
                                //   echo $fase->nome_fase.' / '.$evento->nome_evento.' / '.$item2->descricao.'; Conc : '.$quantidade_acoes_concluidas_item.'; Pend : '.$itens_pendentes.'; Atra : '.$soma_porc_acoes_atrasadas_fase.'; Total : '.$cont_qtde_item_fase;
                                // echo '<br>';

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
                        }else{
                         //   $soma_itens_sem_acao += $cont_qtde_item_evento;
                        }
                     
                }     

                  //   echo 'total : '.$soma_acoes_evento.' Conc :'.$coma_total_acoes_concluidas_itens;

                 $total_acoes_projeto = $cont_qtde_item_fase + $soma_itens_sem_acao + $soma_valores_zerado;
                 $total_acoes_pendentes =  $soma_porc_acoes_pendentes_fase + $soma_valores_zerado + $soma_itens_sem_acao;

                 $porc_concluido = ($coma_total_acoes_concluidas_itens * 100)/$total_acoes_projeto;
                 $porc_pendente = ($total_acoes_pendentes * 100)/$total_acoes_projeto;
                 $porc_atrasado = ($soma_porc_acoes_atrasadas_fase * 100)/$total_acoes_projeto;
     
            $soma_porc = $porc_concluido + $porc_pendente + $porc_atrasado;
            ?>
            <div  class="progress">
                <div class="progress-bar progress-bar-success" role="progressbar" style="width:<?php echo $porc_concluido; ?>%">
                    <?php
                    if ($porc_concluido < 100 && $porc_concluido > 0 ) {
                        echo substr($porc_concluido, 0, 4).' % Concluído';
                    } else if($porc_concluido == 100){
                        echo $porc_concluido.' % Concluído';
                    }else{
                        echo $porc_concluido.' % Concluído';
                    }
                    ?> 
                </div>
                <div class="progress-bar progress-bar-warning" role="progressbar" style="width:<?php echo $porc_pendente; ?>%">
                    <?php
                    if ($porc_pendente < 100 && $porc_pendente > 0) {
                        echo substr($porc_pendente, 0, 4).' % Pendente';
                    } else if($porc_pendente == 100) {
                        echo $porc_pendente.' % Pendente';
                    }else {
                        echo $porc_pendente.' % Pendente';
                    }
                    ?>
                </div>
                <div class="progress-bar progress-bar-danger" role="progressbar" style="width:<?php echo $porc_atrasado; ?>%">
                    <?php
                    if ($porc_atrasado < 100 && $porc_atrasado > 0) {
                        echo substr($porc_atrasado, 0, 4).' % Atrasado';
                    } else if($porc_atrasado == 100) {
                        echo $porc_atrasado.' % Atrasado';
                    } else{
                        echo $porc_atrasado.' % Atrasado';
                    }
                    ?>
                </div>
                <?php
                    if($soma_porc == 0){
                        echo '0 %';
                    }
                ?>
            </div>
               
            
          