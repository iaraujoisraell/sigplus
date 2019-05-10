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
  
  
 
   <link href="<?= $assets ?>styles/theme_novo.css" rel="stylesheet"/>
  <link href="<?= $assets ?>styles/style_novo.css" rel="stylesheet"/>
  <script type="text/javascript" src="<?= $assets ?>js/jquery-2.0.3.min.js"></script>
  <script type="text/javascript" src="<?= $assets ?>js/jquery-migrate-1.2.1.min.js"></script>
  <script type="text/javascript" src="<?= $assets ?>js/funcoes.js"></script>
    
  
  
  
  

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
  <body class="sidebar-mini skin-blue-light sidebar-collapse">
      
      <div class="wrapper">
          
<?php

$this->load->view($this->theme . 'topo'); 
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
<div class="content-wrapper">
    
    
    <br>
        <div class="col-lg-12">
          <ol class="breadcrumb">
              <li ><i class="fa fa-bookmark"></i>  Projeto:    <?php echo $nome_projeto; ?></li>
              <li ><i class="fa fa-user"></i>  Gerente:    <?php echo $gerente_projeto; ?></li>
              <li  ><i class="fa fa-calendar"></i> Início : <?php echo date("d/m/Y", strtotime($projetos->dt_inicio)); ?> </li>
              <li ><i class="fa fa-flag-checkered"></i> Fim : <?php echo date("d/m/Y", strtotime($projetos->dt_final)); ?> </li>

          </ol>
            </div>
          