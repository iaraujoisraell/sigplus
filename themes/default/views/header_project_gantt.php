<html>
   
    
<head>
    <meta charset="utf-8">
    <base href="<?= site_url() ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $Settings->site_name; ?></title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    
    

  <!-- Font Awesome -->
  <!-- DataTables -->
  <!-- Theme style -->
  <link rel="stylesheet" href="<?= $assets ?>bi/dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="<?= $assets ?>bi/dist/css/skins/_all-skins.min.css">
  
  
 
  
  
  <link rel=stylesheet href="<?= $assets ?>gantt/platform.css" type="text/css">
  <link rel=stylesheet href="<?= $assets ?>gantt/libs/jquery/dateField/jquery.dateField.css" type="text/css">

  <link rel=stylesheet href="<?= $assets ?>gantt/gantt.css" type="text/css">
  <link rel=stylesheet href="<?= $assets ?>gantt/ganttPrint.css" type="text/css" media="print">

  <script src="http://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

  <script src="<?= $assets ?>gantt/libs/jquery/jquery.livequery.1.1.1.min.js"></script>
  <script src="<?= $assets ?>gantt/libs/jquery/jquery.timers.js"></script>

  <script src="<?= $assets ?>gantt/libs/utilities.js"></script>
  <script src="<?= $assets ?>gantt/libs/forms.js"></script>
  <script src="<?= $assets ?>gantt/libs/date.js"></script>
  <script src="<?= $assets ?>gantt/libs/dialogs.js"></script>
  <script src="<?= $assets ?>gantt/libs/layout.js"></script>
  <script src="<?= $assets ?>gantt/libs/i18nJs.js"></script>
  <script src="<?= $assets ?>gantt/libs/jquery/dateField/jquery.dateField.js"></script>
  <script src="<?= $assets ?>gantt/libs/jquery/JST/jquery.JST.js"></script>

  <script type="text/javascript" src="<?= $assets ?>gantt/libs/jquery/svg/jquery.svg.min.js"></script>
  <script type="text/javascript" src="<?= $assets ?>gantt/libs/jquery/svg/jquery.svgdom.1.8.js"></script>


  <script src="<?= $assets ?>gantt/ganttUtilities.js"></script>
  <script src="<?= $assets ?>gantt/ganttTask.js"></script>
  <script src="<?= $assets ?>gantt/ganttDrawerSVG.js"></script>
  <script src="<?= $assets ?>gantt/ganttZoom.js"></script>
  <script src="<?= $assets ?>gantt/ganttGridEditor.js"></script>
  <script src="<?= $assets ?>gantt/ganttMaster.js"></script>  
  
  
   

</head>
 
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
  <body style=" overflow-x: hidden; overflow-y: auto;"  class="sidebar-mini skin-blue-light sidebar-collapse">
      
      
          
<?php

//$this->load->view($this->theme . 'topo_gantt'); 
//$this->load->view($this->theme . 'menu_esquerdo'); 
?>

          
<?php
$projetos = $this->projetos_model->getProjetoAtualByID_completo();
$id_projeto = $projetos->id;
$nome_projeto = $projetos->nome_projeto;
$gerente = $projetos->gerente_area;
$resp_tecnico_fase = $this->atas_model->getUserSetorByUserSetor($gerente);
$gerente_projeto = $resp_tecnico_fase->nome;


?>
    
    
   
        <div class="col-lg-12">
          <ol class="breadcrumb">
              <i  class="fa fa-bookmark "></i> <h1>  <?php echo $nome_projeto; ?> </h1>
             

          </ol>
            </div>
          