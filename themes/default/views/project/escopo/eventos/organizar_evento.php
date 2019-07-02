<?php
$fase_dados = $this->projetos_model->getFaseByID($fase_id);
$nome_fase = $fase_dados->nome_fase;

?>
    
  <script type="text/javascript">
        function novaFase(opcao){
            
            if(opcao == 1){
             //   document.getElementById("hiddenDiv").style.visibility ="visible";
                document.getElementById("divNovaFase").style.display = "block";
            }else{
                document.getElementById("divNovaFase").style.display = "none";
            }

        }


    </script>
  
    <!-- Content Header (Page header) -->
     
    
    <div class="col-lg-12">
    <div class="box">
        
    <section class="content-header">
                    <h1> <?php echo 'Re-ordernar os eventos da Fase '.$nome_fase ; ?></h1>    
                  
                  <ol class="breadcrumb">
                    <li><a href="<?= site_url('project'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active">Escopo</li>
                    <li class="active">Ordernar Eventos</li>
                  </ol>
                </section>
        <div class="box-header">
            <span class="pull-right-container">
               <div class=" clearfix no-border">
                    <a style="color: #ffffff;" title="<?php echo 'Lista de Fases'; ?>" class="btn btn-primary  pull-left" href="<?= site_url('project/fases_projetos/'.$menu); ?>"  > <i class="fa fa-fast-backward"></i> Voltar </a>
               </div>
            </span>
        </div>
        <br>
    </div> 
    </div> 
    <div class="col-lg-12">
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
     <!-- Content Header (Page header) -->
      
    <!-- Main content -->
    <section class="content">
    <div class="row">  
        
      <div class="col-lg-12">
       <div class="box">       
             
        <div class="box box-primary">
            <div class="box-header">
              <i class="ion ion-clipboard"></i>
              <h3 class="box-title"> Eventos</h3>
            </div>
            <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form', 'id' => 'add-customer-form');
                echo form_open_multipart("project/organizarEventos", $attrib); 
                //echo form_hidden('fase', $fase); 
                echo form_hidden('ordernar_evento', '1'); 
            ?>
            <!-- /.box-header -->
            <div class="box-body">
              <!-- See dist/js/pages/dashboard.js to activate the todoList plugin -->
              <ul class="todo-list">
                   <?php
                    $wu4[''] = '';
                    $cont_lista = 1;
                    foreach ($eventos as $cadastro) {
                        $id_fase =  $cadastro->id;
                        $nome_fase =  $cadastro->nome_evento;
                    ?>     
                  
                        <li>
                          <!-- drag handle -->
                          <span class="handle">
                            <i class="fa fa-ellipsis-v"></i>
                            <i class="fa fa-ellipsis-v"></i>
                          </span>
                          <!-- todo text -->
                          <input type="hidden" name="ordem_fase[]" value="<?php echo $cont_lista; ?>">
                          <input type="hidden" name="codigo_fase[]" value="<?php echo $id_fase; ?>">
                          <span class="text"><?php echo $nome_fase; ?></span>
                          <!-- Emphasis label -->
                        </li>
                    <?php
                    $cont_lista++;
                    }
                    ?>
                
              </ul>
            </div>
            <div class="modal-footer">
            <?php echo form_submit('add_customer', lang('Salvar'), 'class="btn btn-primary"'); ?>
            <a class="btn btn-danger " onclick="history.go(-1)" > Fechar</a>
        </div>
            
            <?php echo form_close(); ?>
            <!-- /.box-body -->
            
          </div>
      </div>
     </div>
    </div>     
        
      <!-- /.row (main row) -->

    </section>
    <!-- /.content -->
    
   
    