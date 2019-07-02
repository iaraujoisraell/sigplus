
    <?php 
    $usuario =  $this->session->userdata('user_id'); 

    ?>
<div style="background-color: #f2f2f2" class="content-wrapper">
    <div class="col-lg-12">
    <div class="box">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?php echo 'Logs de Acesso'; ?>
            <small><?php echo 'Registro de Logins'; ?> </small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?= site_url('admin'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Logs de Acesso</li>
        </ol>
    </section>
    <div class="box-header">
        <span class="pull-right-container">
            
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
    
    <!-- FILTRO  -->

<br>
    
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
                        <th style="width:25%;" ><?php echo $this->lang->line("Nome"); ?></th>
                        <th style="width:25%;"><?php echo $this->lang->line("E-mail"); ?></th>
                        <th style="width:30%;"><?php echo $this->lang->line("Data Acesso"); ?></th>
                        <th style="width:10%;"><?php echo $this->lang->line("Status"); ?></th>
                       
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
                                    
                                  
                                   

                                ?>               

                                    <tr  >

                                        <td style="width: 1%;  "><?php echo $cont++; ?></td> 
                                        <td style="width: 25%; "><small ><?php echo $user->first_name; ?> </small></td>
                                        <td style="width: 25%; "><small ><?php echo $user->email; ?></small></td> 
                                        <td style="width: 30%;  "><small ><?php echo date('d/m/Y H:i:s', strtotime( $user->time)); ?></small></td>
                                        <td style="width: 10%;  text-align: center;"><small class="label label-<?php echo $label; ?>" ><?php echo $status_ata; ?></small></td> 
                                           
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