<?php
/*
$usuario = $this->session->userdata('user_id');
$projetos = $this->projetos_model->getProjetoAtualByID_completo();
$id_projeto = $projetos->id;
$nome_projeto = $projetos->nome_projeto;
$gerente_projeto = $projetos->gerente_area;
$gerente_dados = $this->site->geUserByID($gerente_projeto);
 * 
 */


/*
 * VERIFICA SE TEM AÇÕES AGUARDANDO VALIDAÇÃO
 
//$quantidadeAvalidacao = $this->site->getAllPlanosAguardandoValidacao($id_projeto);
//$acoes_aguardando_validacao = $quantidadeAvalidacao->quantidade;

    $status =  $projetos->status;
    if($status == 'ATIVO'){
       $status_label = 'success'; 
    }else if($status == 'CANCELADO'){
        $status_label = 'danger'; 
    }else if($status == 'EM AGUARDO'){
        $status_label = 'warning'; 
    }else if($status == 'CONCLUÍDO'){
        $status_label = 'primary'; 
    }
 * */
 
?>
<script type="text/javascript">
        function div_calendario(opcao) {
             
          $.ajax({
            type: "POST",
            url: "themes/default/views/networking/calendario/exibe_calendario.php?opcao="+opcao,
           
            data: {
              descricao_checklist: $('#descricao_checklist').val(),
              empresa: $('#empresa').val(),
              usuario: $('#usuario').val(),
              //MINHA AGENDA
              minha_agenda: $('#minha_agenda').val()
            
            },
            success: function(data) {
              $('#div_calendario').html(data);

            }

          });
        // document.getElementById("descricao_checklist").value = "";
        }
        </script>

<div class="col-lg-12">
        <div class="box">
        <section class="content-header">
            <h1>
                <?php echo 'Calendário'; ?>
                <small><?php echo ''; ?> </small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="<?= site_url('project'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Calendário</li>
            </ol>
        </section>
        <div class="box-header">
                    
                </div>
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
<?php
$usuario = $this->session->userdata('user_id');
$empresa = $this->session->userdata('empresa');
?>
<input type="hidden" id="usuario" value="<?php echo $usuario; ?>">
<input type="hidden" id="empresa" value="<?php echo $empresa; ?>">
<section class="content">
      <div class="row">
        <div class="col-md-3">
          <div class="box box-solid">
            <div class="box-header with-border">
                <h4 class="box-title">Minhas Agendas</h4> <button onchange="alert(this.checked)" class="btn btn-primary btn-sm  pull-right"><i class="fa fa-refresh"></i></button>
            </div>
            <div class="box-body">
                
               
              <button onclick="div_calendario(1);" class="col-md-12 pull-right btn  bg-maroon-active ">Minha Agenda <i class="fa fa-calendar"></i></button>
              <button onclick="div_calendario(2);" class="col-md-12 btn btn-warning" >Minhas Ações  <i class="fa fa-bookmark"></i></button>
              <button onclick="div_calendario(3);" class="col-md-12 btn bg-black-active" >Tarefas  <i class="fa fa-check-square-o"></i></button>
              <button onclick="div_calendario(4);" class="col-md-12 btn btn-bitbucket" >Marcos do Projeto  <i class="fa fa-calendar-check-o"></i></button>
              <button onclick="div_calendario(5);" class="col-md-12 btn bg-gray-active" >Plano de Ações  <i class="fa fa-tasks"></i></button>
              <button onclick="div_calendario(6);" class="col-md-12 btn bg-red-active" >Feriados  <i class="fa fa-calendar-plus-o"></i></button>
              <button onclick="div_calendario(7);" class="col-md-12 btn btn-success" >Aniversariantes <i class="fa fa-birthday-cake"></i></button>
              
            </div>
            <!-- /.box-body -->
          </div>
          
        </div>
        <!-- /.col -->
        <div class="col-md-9">
          <div class="box box-primary">
            <div class="box-body no-padding">
              <!-- THE CALENDAR -->
              <div id="div_calendario">
                  <script>div_calendario(0);</script>
              </div>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /. box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>

