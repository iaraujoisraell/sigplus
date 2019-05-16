
    <?php 
    $usuario =  $this->session->userdata('user_id'); 

    ?>
<div style="background-color: #f2f2f2" class="content-wrapper">
    <div class="col-lg-12">
    <div class="box">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?php echo 'Usuários'; ?>
            <small><?php echo 'Lista de Usuários'; ?> </small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?= site_url('admin'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Usuário</li>
        </ol>
    </section>
    <div class="box-header">
        <span class="pull-right-container">
            <div class=" clearfix no-border">
                <a title="Abrir Nova Ata" class="btn btn-primary pull-right" href="<?= site_url('admin/novo_usuario/'); ?>" >  
                    <i class="fa fa-plus"></i>  Novo Usuário
                </a> 
            </div>
        </span>
    </div>
    </div>
    </div>    
    <br>
    <div class="col-lg-12">
        <div class="col-lg-12">
            <div class="row">
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
            </div>
        </div>
    </div>           
    <!-- Main content -->
    <section  class="content">
        <div class="col-lg-12">
            <div class="row">
               <div class="box">
                          
                  <br>
                <div class="table-responsive">
                    <div class="box-body">
                        <table style="width: 100%;" id="usuarios_lista" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                        <th style="width:1%; text-align: center;">-</th>
                        <th style="width:35%;" ><?php echo $this->lang->line("Nome"); ?></th>
                        <th style="width:24%;"><?php echo $this->lang->line("E-mail"); ?></th>
                        <th style="width:10%;"><?php echo $this->lang->line("Valid. E-mail?"); ?></th>
                        <th style="width:10%;"><?php echo $this->lang->line("Status"); ?></th>
                        <th style="width:5%;  text-align: center;"><?php echo $this->lang->line("Reenviar"); ?></th>
                        <th style="width:5%;  text-align: center;"><?php echo $this->lang->line("Senha"); ?></th>
                        <th style="width:10%;  text-align: center;"><?php echo $this->lang->line("Editar"); ?></th>
                    </tr>
                    </thead>
                        <tbody>
                             <?php
                                $wu4[''] = '';
                                $cont = 1;
                               // print_r($usuarios); exit;
                                foreach ($usuarios as $user) {

                                   $hoje = date('Y-m-s');
                                   $status = $user->active;

                                    if($status == 0){
                                        $status_ata = 'Inativo';
                                        $label = "danger";
                                    }else 
                                    if($status == 1){
                                        $status_ata = 'Ativo';
                                        $label = "success";
                                    }
                                    
                                  
                                    
                                    if($user->confirmou_email == 0){
                                        $confirmou = "NÃO";
                                    }else{
                                        $confirmou = "SIM";
                                    }

                                ?>               

                                    <tr  >

                                        <td style="width: 1%;  "><?php echo $cont++; ?></td> 
                                        <td style="width: 35%; "><small ><?php echo $user->first_name.' - '.$user->setor; if($user->consultor == 1){ ?> <label class="label label-warning" >Consultor</label> <?php }else{ ?> <label class="label label-primary" ><?php echo $user->cargo; ?></label> <?php } ?></small></td>
                                        <td style="width: 24%; "><small ><?php echo $user->email; ?></small></td> 
                                        <td style="width: 10%;  "><small ><?php echo $confirmou; ?></small></td>
                                        <td style="width: 10%;  text-align: center;"><small class="label label-<?php echo $label; ?>" ><?php echo $status_ata; ?></small></td> 
                                        <td style="width: 5%; font-size: 12;">
                                            <?php  if($status == 0){ ?>
                                            <a title="Reenviar E-mail para Validação de Cadastro?" class="btn btn-primary"  href="<?= site_url('admin/reenviaEmailCredenciais/'.$user->id); ?>"><i class="fa fa-envelope"></i></a>
                                            <?php } ?>
                                        </td>
                                        <td style="width: 5%; font-size: 12;"><a title="Alterar Senha" class="btn bg-gray"  href="<?= site_url('admin/alterarSenhaUsuario/'.$user->id); ?>" data-toggle="modal" data-target="#myModal"><i class="fa fa-key"></i></a></td> 
                                        <td style="width: 10%; font-size: 12;">
                                            <a title="Editar Registro" class="btn btn-default"  href="<?= site_url('admin/editar_usuario/'.$user->id); ?>"><i class="fa fa-edit"></i>  ABRIR </a>
                                        </td>    
                                    </tr>
                                    <?php
                                }
                                ?>
                        </tbody>
                    </table>
                        <br><br><br><br>
                    </div>    
                </div>

            </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
    </div>
          <script>
  $(function () {
  $('#usuarios_lista').DataTable({
      "order": [[ 0, "asc" ]]
    })
  })
</script>