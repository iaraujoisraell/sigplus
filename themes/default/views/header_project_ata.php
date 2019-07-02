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
  <!-- daterange picker -->
  <link rel="stylesheet" href="<?= $assets ?>bi/bower_components/bootstrap-daterangepicker/daterangepicker.css">
  <!-- bootstrap datepicker -->
  <link rel="stylesheet" href="<?= $assets ?>bi/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
  <!-- iCheck for checkboxes and radio inputs -->
  <link rel="stylesheet" href="<?= $assets ?>bi/plugins/iCheck/all.css">
  <!-- Bootstrap Color Picker -->
  <link rel="stylesheet" href="<?= $assets ?>bi/bower_components/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css">
  <!-- Bootstrap time Picker -->
  <link rel="stylesheet" href="<?= $assets ?>bi/plugins/timepicker/bootstrap-timepicker.min.css">
  <!-- Select2 -->
  <link rel="stylesheet" href="<?= $assets ?>bi/bower_components/select2/dist/css/select2.min.css">
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
?>
<div class="content-wrapper">
    
    
    <br>
        <?php
    $this->load->view($this->theme . 'header_project_cabecalho'); 
    ?> 
          