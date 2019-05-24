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
 
 
?>     

<!-- Bootstrap 3.3.7 -->
  <body class="sidebar-mini skin-blue-light sidebar-collapse">
      
      <div class="wrapper">
          
<?php

$this->load->view($this->theme . 'topo'); 

?>

          <?php

?>
<div class="content-wrapper">
    
    
    <br>
        
          