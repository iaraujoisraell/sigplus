<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Dashboard
        <small>Control panel</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
      </ol>
    </section>
    <?php
    // PRESTADORES
    $prestadores = $this->atas_model->getQtdePrestadoresSai();
    $qtde_prestador = $prestadores->qtde_prestadores;
    // ESPECIALIDADES
    $especialidaes = $this->atas_model->getQtdeEspecialidadesSai();
    $qtde_especialidade = $especialidaes->qtde_especialidade;
    // SERVIÇOS ATIVOS
    $servicos = $this->atas_model->getQtdeServicosSai();
    $qtde_servico = $servicos->qtde_servicos;
     // SERVIÇOS ATIVOS
    $lista_espera = $this->atas_model->getQtdeListaEsperaSai();
    $qtde_espera = $lista_espera->qtde_espera;
    ?>
    <!-- Main content -->
    <section class="content">
      <!-- Small boxes (Stat box) -->
      <div class="row">
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-red">
            <div class="inner">
              <h3><?php echo $qtde_espera; ?></h3>

              <p>Lista Espera</p>
            </div>
            <div class="icon">
              <i class="fa fa-list"></i>
            </div>
            <a href="<?= site_url('atendimento/lista_espera'); ?>" class="small-box-footer">Visualizar<i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>  
          
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3><?php echo $qtde_prestador; ?></h3>

              <p>Prestadores</p>
            </div>
            <div class="icon">
              <i class="fa fa-user-md"></i>
            </div>
            <a href="<?= site_url('atendimento/prestadores'); ?>" class="small-box-footer">Visualizar<i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-green">
            <div class="inner">
              <h3><?php echo $qtde_especialidade; ?><sup style="font-size: 20px"></sup></h3>

              <p>Especialidades</p>
            </div>
            <div class="icon">
              <i class="fa fa-medkit"></i>
            </div>
            <a href="<?= site_url('atendimento/especialidades'); ?>" class="small-box-footer">Visualizar <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-yellow">
            <div class="inner">
              <h3><?php echo $qtde_servico; ?></h3>

              <p>Serviços</p>
            </div>
            <div class="icon">
              <i class="fa fa-stethoscope"></i>
            </div>
            <a href="<?= site_url('atendimento/servicos_ativos'); ?>" class="small-box-footer">Visualizar <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        
        <!-- ./col -->
      </div>
      <!-- /.row -->
      <!-- Main row -->
      <div class="row">
        <!-- Left col -->
        
        <!-- /.Left col -->
        <!-- right col (We are only adding the ID to make the widgets sortable)-->
        <section class="col-lg-5 connectedSortable">

         

        </section>
        <!-- right col -->
      </div>
      <!-- /.row (main row) -->

    </section>
    <!-- /.content -->
  </div>