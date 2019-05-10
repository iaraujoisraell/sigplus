

    
    <?php 
    $usuario =  $this->session->userdata('user_id'); 

    ?>
    <div class="col-lg-12">
        <div class="box">
        <section class="content-header">
            <h1>
                <?php echo 'Colaboradores'; ?>
               
            </h1>
            <ol class="breadcrumb">
                <li><a href="<?= site_url('welcome'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Colaboradores</li>
            </ol>
        </section>
        
        <br>
        </div>    
    </div>
    <div class="col-lg-12">
    <!-- Content Header (Page header) -->
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
    <!-- Main content -->
    </div>
    <section  class="content">
        <div class="col-lg-12">
            <div class="row">
                <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">Lista</h3>
                  <?php
                    $colaboradores = $this->networking_model->getAllColaboradoresByEmpresa();
                    $cont_colaborador = 0;
                    foreach ($colaboradores as $colaborador) {
                        
                     $cont_colaborador++;   
                    }
                  ?>  
                  <div class="box-tools pull-right">
                    <span class="label label-primary"><?php echo $cont_colaborador; ?> Membros</span>
                    
                  </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body no-padding">
                  <ul class="users-list clearfix">
                   <?php
                    $colaboradores = $this->networking_model->getAllColaboradoresByEmpresa();
                    //$cont_colaborador = 0;
                    foreach ($colaboradores as $colaborador) {
                        $id_colaborador = $colaborador->id;
                        $nome_colaborador = $colaborador->first_name;
                        $generoc = $colaborador->gender;
                        $avatar = $colaborador->avatar;
                         $cargo = $colaborador->cargo;
                        ?>
                     
                      
                      <li>
                      <img style="width: 88px; height: 88px;" class="img-circle" src="<?= $avatar ? $assets . '../../../assets/uploads/avatars/thumbs/' . $avatar : $assets . 'images/' . $generoc . '.png'; ?>" alt="User Image">
                      <a class="users-list-name" href="<?= site_url('welcome/profile_visitor/'.$id_colaborador); ?>"><?php echo $nome_colaborador; ?></a>
                      <span class="users-list-date"><?php echo $cargo ?></span>
                            <a title="Abrir Chat" class="btn bg-blue-active" href="#"><i class="fa fa-wechat"></i></a> 
                              <a title="Visualizar Perfil" class="btn bg-light-blue-active" href="<?= site_url('welcome/profile_visitor/'.$id_colaborador); ?>"><i class="fa fa-user"></i></a>
                       
                     
                    </li>
                    <?php
                    }
                    ?>    
                      
                   
                  </ul>
                  <!-- /.users-list -->
                </div>
                <!-- /.box-body -->
                
                <!-- /.box-footer -->
              </div>
                
                
           
      <!-- /.row (main row) -->
            </div>
        </div>
    </section>
    <!-- /.content -->
 


