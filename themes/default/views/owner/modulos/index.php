<?php 
    $usuario = $this->session->userdata('user_id');
//    $projetos_usuario = $this->site->getProjetoAtualByID_completo($usuario);
    //   a.`id` , a.`id_acao` , r.`descricao` , idatas, i.descricao, e.nome_evento, pj.id as id_projeto, pj.projeto       
?>

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

</head>

    

<!-- Bootstrap 3.3.7 -->
  <body class="hold-transition skin-blue sidebar-mini">
      <div class="wrapper">
<?php

$this->load->view($this->theme . 'topo'); 
$this->load->view($this->theme . 'owner/menu_esquerdo'); 
?>
<div class="content-wrapper">
    
    <?php 
    
    $usuario =  $this->session->userdata('user_id'); 

    ?>
  
    <!-- Content Header (Page header) -->
     <section class="content-header">
          <h1>
            <?php echo $titulo; ?>
            <small><?php echo $descricao_titulo; ?> </small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="<?= site_url('owner'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Empresa</li>
          </ol>

        </section>
    <br>
    <!-- Main content -->
    <section class="content">
        <?php if ($Settings->mmode) { ?>
                        <div class="alert alert-warning">
                            <button data-dismiss="alert" class="close" type="button">×</button>
                            <?= lang('site_is_offline') ?>
                        </div>
                    <?php }
                    if ($error) { ?>
                        <div class="alert alert-danger">
                            <button data-dismiss="alert" class="close" type="button">×</button>
                            <ul class="list-group"><?= $error; ?></ul>
                        </div>
                    <?php }
                    if ($message) { ?>
                        <div class="alert alert-success">
                            <button data-dismiss="alert" class="close" type="button">×</button>
                            <ul class="list-group"><?= $message; ?></ul>
                        </div>
                    <?php } ?>
        
             <div class="box">
                <div class="box-header">
                    <span class="pull-right-container">
                       <div class=" clearfix no-border">
                            <button  type="button" class="btn btn-default pull-right" data-toggle="modal" data-target="#modal-insert">
                                <i class="fa fa-plus"></i>  Novo Cadastro </button>
                        </div>
                    </span>
                </div>
           
                  <br>
                    <div class="box-body">
                       <table id="example1" class="table table-bordered table-striped">
                            <thead>
                             <tr style=" width: 100%;">

                                <th style="width: 5%;">Id</th>
                                <th style="width: 65%;">Módulo</th>
                                <th style="width: 10%;"> Icon</th>
                                <th style="width: 10%;">Editar</th>
                                <th style="width: 10%;">Excluir</th>
                            </tr>
                            </thead>
                            <tbody>
                                 <?php
                                    $wu4[''] = '';
                                    $cont = 1;
                                    foreach ($modulos as $empresa) {
                                    ?>               

                                        <tr  >

                                            <td style="width: 5%;"><?php echo $cont++; ?> </td> 
                                            <td style="width: 65%;"><?php echo $empresa->descricao; ?><small style="color: #000000;" class="label pull-right" > <?php echo $empresa->fantazia; ?></small> </td>
                                            <td style="width: 10%;"><small   ><i class="fa fa-<?php echo $empresa->icon; ?>"></i></small></td>
                                            <td style="width: 10%; "><small  ><a style="background-color: chocolate; color: #ffffff;" title="Editar Registro" class="btn fa fa-edit" href="<?= site_url('owner/editarModulo/'.$empresa->id); ?>" data-toggle="modal" data-target="#myModal">  </a></small></td>
                                            <td style="width: 10%; "><small  ><a style="background-color: red; color: #ffffff;" title="Deletar Registro" class="btn fa fa-trash" href="<?= site_url('owner/deletarModulo/'.$empresa->id); ?>" data-toggle="modal" data-target="#myModal">  </a></small></td>
                                            

                                        </tr>
                                        <?php
                                    }
                                    ?>
                            </tbody>
                        </table>
                    </div>
                
      </div>
        
      <!-- /.row (main row) -->

    </section>
    <!-- /.content -->
    
 </div>

<div  class="modal fade" id="modal-insert">
          <div  class="modal-dialog">
            <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('Cadastro de Módulo'); ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form', 'id' => 'add-customer-form');
        echo form_open_multipart("owner/modulos", $attrib); ?>
        <div class="modal-body">
           

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group company">
                        <?= lang("Descrição", "company"); ?>
                        <?php echo form_input('descricao', '', 'class="form-control tip" maxlength="300" id="descricao" required="true" data-bv-notempty="true"'); ?>
                    </div>
                    <div class="form-group company">
                        <?= lang("Fa icon", "fantazia"); ?>
                        <?php echo form_input('icon', '', 'class="form-control tip" maxlength="150" id="icon" '); ?>
                    </div>
                    
                    
                </div>
                
            </div>


        </div>
        <div class="modal-footer">
            <?php echo form_submit('add_customer', lang('Salvar'), 'class="btn btn-primary"'); ?>
        </div>
    </div>
    <?php echo form_close(); ?>
            <!-- /.modal-content -->
          </div>
        </div>

          </div>
