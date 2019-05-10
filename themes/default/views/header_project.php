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
          <ol  class="breadcrumb">
              <li ><i class="fa fa-bookmark"></i>    <?php echo $nome_projeto.' '; ?> </li> <small class="label label-<?php echo $status_label; ?>" ><?php echo $status; ?></small>
              <li ><i class="fa fa-user"></i>  Gerente:    <?php echo $gerente_projeto; ?></li>
              <li  ><i class="fa fa-calendar"></i> Início : <?php echo date("d/m/Y", strtotime($projetos->dt_inicio)); ?> </li>
              <li ><i class="fa fa-flag-checkered"></i> Fim : <?php echo date("d/m/Y", strtotime($projetos->dt_final)); ?> </li>
       
          </ol>
             
             <?php if($status == 'EM AGUARDO'){ ?> 
              <a title="O Projeto deve estar ativo para que se possa cadastrar ações e gerencia-lo." href="<?= site_url('Project/ativarProjeto/'.$projetos->id); ?>" ><small class="btn btn-success" > ATIVAR PROJETO <i class="fa fa-sign-in"></i></small></a>  
            <?php }  ?>   
                <?php
            $dt_inicio = $projetos->dt_inicio;
            $dt_fim = $projetos->dt_final;
            $hoje = date("Y-m-d");
            $dias_projeto = $this->projetos_model->getDiferencaDiasProjeto($dt_inicio, $dt_fim);
            $dias_projeto_hoje = $this->projetos_model->getDiferencaDiasProjeto($dt_inicio, $hoje);
            $total_dias = $dias_projeto->dias;
            $andamento_dias = $dias_projeto_hoje->dias;
           

            ?>
           <div style="background-color: #ffffff;" class="progress progress-sm active">
                <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: 20%">
                  <span class="sr-only">20% Complete</span>
                </div>
              </div>
            </div>
          