 <script src="//code.jquery.com/jquery-1.10.2.js"></script>
  <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script> 

<style>
#result {
  width: 100%;
  height: 50px;
  line-height: 50px;
 
  margin-top: 100px;
  text-align: center;
  font-size: 30px;
  color: #fff;
  background: #ddd;
}

.r-slider-button {
  background: #efefef;
  box-shadow: 0 3px 7px 0 rgba(0, 0, 0, 0.5);
  border-radius: 100%;
  text-align: center;
}

.r-slider-button:before {
  content: "";
  position: absolute;
  height: 14px;
  width: 14px;
  top: 5px;
  left: 5px;
  border-radius: 100%;
  background: #777;
  box-shadow: inset 0 1px 3px 1px #222;
}

.r-slider-line {
  background: #ddd;
  border-radius: 16px;
  box-shadow: inset 0 2px 2px 0px rgba(0, 0, 0, 0.3);
}

.r-slider-fill {
  background: #fc9b00;
  border-radius: 16px;
  box-shadow: 0 1px 3px 0px rgba(0, 0, 0, 0.7);
  background-image: repeating-linear-gradient(
    45deg,
    transparent,
    transparent 1px,
    rgba(20, 20, 20, 0.1) 4px,
    rgba(20, 20, 20, 0.1) 5px
  );
}

.r-slider-fill:before {
  content: "";
  position: absolute;
  height: 1px;
  width: 100%;
  top: 1px;
  background: #ffc823;
}

.r-slider-fill:after {
  content: "";
  position: absolute;
  height: 1px;
  width: 100%;
  bottom: 0px;
  background: #ca6008;
}

.r-slider-label {
  position: absolute;
  text-align: center;
  line-height: 2px;
  bottom: -30px;
  font-size: 13px;
  color: #999;
  font-weight: bold;
}
</style>

<script>
  $(function() {
    $( "#sortable" ).sortable();
    $( "#sortable" ).disableSelection();
    
      
    var ul_sortable = $('.sortable');
    ul_sortable.sortable({
        revert: 100,
        placeholder: 'placeholder'
    });
    ul_sortable.disableSelection();
    var btn_save = $('li.save'),
        div_response = $('#response');
    btn_save.on('mouseup', function(e) {
        e.preventDefault();
        setTimeout(function(){ 
        var sortable_data = ul_sortable.sortable('serialize');
      //  div_response.text('aqui teste');
        $.ajax({
            data: sortable_data,
            type: 'POST',
            url: '../../../../escopo_slider/save_fase.php',
            success:function(result) {
               // location.reload();
                div_response.text(result);
            }
        });
         }, 500);
    });
    
  });






  </script>
 <style>
      li {list-style-type:none;}
      
      #scroll {
          width:300px;
          height:170px;
          background-color:#F2F2F2;
          overflow:auto;
        }
  </style>
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
                           <a style="background-color: green; color: #ffffff;" title="Editar Registro" class="btn btn-default pull-right" href="<?= site_url('owner/novoCadastroBasico/'.$tabela_id."/".$menu_id); ?>" data-toggle="modal" data-target="#myModal">  
                           <i class="fa fa-plus"></i>  Novo Cadastro 
                           </a> 
                         
                        </div>
                    </span>
                </div>
                 
                 
                 
                 
         <!--   <div class="alert alert-success" id="response" role="alert"></div> -->
            
         <div class="box-body">
                    <section   class="col-lg-12 connectedSortable sortable ">
                      
                                 <?php
                                    echo $tabela_nome.'<br>';
                                 
                                    $wu4[''] = '';
                                    $cont_lista = 1;
                                    foreach ($cadastros as $cadastro) {
                                        $id_cadastro = $cadastro->id;
                                      //  echo 'id'.$id_cadastro.'<Br>';
                                        
                                      //  echo $tabela_fk;
                                    ?>               
                                           
                                            <?php
                                            foreach ($campos as $campo) {
                                                $nome_campo = $campo->campo;
                                                $tabela_fk = $campo->fk;
                                                
                                                if($tabela_fk != 0){
                                                    if($cadastro->$nome_campo > 0){
                                                    
                                                    
                                                    
                                                    $tabelas_fk = $this->owner_model->getTableById($tabela_fk);//tabela relacionamento
                                                    $nome_tabela = $tabelas_fk->tabela;//nome da tabela
                                                    
                                                    $colunas_tabela_fk = $this->owner_model->getAllCamposDropdownFK($tabela_id,$tabela_fk); //colunas que sera(o) visualizadas
                                                    $dados_tabelas_fk = $this->owner_model->getTableByNameAndId($nome_tabela, $cadastro->$nome_campo);
                                                    
                                                          $cont_col = 1;
                                                          foreach ($colunas_tabela_fk as $coluna) {
                                                          $id_coluna = $coluna->campo_id;
                                                          $campo_id_fk = $this->owner_model->getCampoById($id_coluna);
                                                          
                                                        //  $descricao_campo = $campo_id_fk->campo;
                                                          
                                                          if($cont_col <= 1){
                                                              $descricao_campo = $campo_id_fk->campo;
                                                          }else if($cont_col > 1){
                                                              $descricao_campo .= ' - '.$campo_id_fk->campo; 
                                                              //echo $descricao_campo; exit;
                                                          }
                                                          
                                                           //echo $cont_col;

                                                          $cont_col++; 
                                                         }
                                                         $descricao_campo = $dados_tabelas_fk->$descricao_campo;
                                                         //echo $nome_campo;
                                                     
                                                    }else{
                                                        $descricao_campo =  "";
                                                    }
                                                }else{
                                                    $descricao_campo = $cadastro->$nome_campo;
                                                }    
                                                 if($descricao_campo){
                                            ?> 
                                                <li style="background-color: #ffffff;" class="save" id=item-<?php echo $id_cadastro; ?>> 

                                                    <div class="box box-primary collapsed-box">
                                                        <div style="background-color: #ffffff;" class="box-header with-border">
                                                        <span class="handle">
                                                          <i class="fa fa-ellipsis-v"></i>
                                                          <i class="fa fa-ellipsis-v"></i>
                                                      </span>

                                                        <h3 class="box-title"><?php echo $descricao_campo; ?></h3>

                                                        <?php
                                                            $pais = $this->owner_model->getTablesHierarquicoRaiz($tabela_nome, $id_cadastro);
                                                            $cont_pai = 0;
                                                            foreach ($pais as $pai) {
                                                                //$id_coluna = $coluna->campo_id;
                                                                $cont_pai++;
                                                            }
                                                        ?>
                                                        <?php if($cont_pai > 0){ ?>
                                                        <div class="box-tools pull-right">
                                                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                                                            </button>
                                                        </div>
                                                        <?php } ?>
                                                    </div>
                                                    
                                                     <?php
                                                        $pais = $this->owner_model->getTablesHierarquicoRaiz($tabela_nome, $id_cadastro);
                                                        $cont_pai2 = 0;
                                                        foreach ($pais as $pai) {
                                                          $id_pai = $pai->pai;
                                                          $id_filho = $pai->id;
                                                          $nome_filho = $pai->nome;
                                                        // echo $id_pai;
                                                          
                                                          $filhos = $this->owner_model->getTablesHierarquicoPais($tabela_nome, $id_filho);
                                                          
                                                          $cont_filho = 0;
                                                          foreach ($filhos as $filho) {
                                                              $cont_filho++;
                                                             
                                                          }
                                                          
                                                          if($cont_filho >= 1){
                                                            
                                                              ?>    
                                                            
                                                        <div id="<?php echo $filho->id; ?>" class="box-body">
                                                            <ul class="todo-list" >  
                                                             <li> 

                                                                <div class="portlet-body">

                                                                    <div class="box box-primary">
                                                                        <div class="box-header">
                                                                            <span class="handle">
                                                                        <i class="fa fa-ellipsis-v"></i>
                                                                        <i class="fa fa-ellipsis-v"></i>
                                                                      </span>


                                                                          <h3 class="box-title"><?php echo $nome_filho; ?></h3>
                                                                           <div class="box-tools pull-right">
                                                                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                                                                            </button>
                                                                        </div>

                                                                        </div>
                                                                        <!-- /.box-header -->
                                                                        <div class="box-body">
                                                                          <!-- See dist/js/pages/dashboard.js to activate the todoList plugin -->
                                                                          <ul class="todo-list">
                                                                            <?php foreach ($filhos as $filho) { 
                                                                                
                                                                                 $filhos_netos = $this->owner_model->getTablesHierarquicoPais($tabela_nome, $filho->id);
                                                          
                                                                                  $cont_neto = 0;
                                                                                  foreach ($filhos_netos as $neto) {
                                                                                      $cont_neto++;

                                                                                  }
                                                          
                                                                                ?>  
                                                                              
                                                                              <?php if($cont_neto >= 1){ ?>
                                                                              
                                                                                <div id="<?php echo $filho->id; ?>" class="box-body">
                                                                                    <ul class="todo-list" >  
                                                                                     <li> 

                                                                                        <div class="portlet-body">

                                                                                            <div class="box box-primary">
                                                                                                <div class="box-header">
                                                                                                    <span class="handle">
                                                                                                <i class="fa fa-ellipsis-v"></i>
                                                                                                <i class="fa fa-ellipsis-v"></i>
                                                                                              </span>


                                                                                                  <h3 class="box-title"><?php echo $filho->nome; ?></h3>
                                                                                                   <div class="box-tools pull-right">
                                                                                                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                                                                                                    </button>
                                                                                                </div>

                                                                                                </div>
                                                                                                <!-- /.box-header -->
                                                                                                <div class="box-body">
                                                                                                  <!-- See dist/js/pages/dashboard.js to activate the todoList plugin -->
                                                                                                  <ul class="todo-list">
                                                                                                      <?php foreach ($filhos_netos as $neto) {  ?>
                                                                                                      <li>
                                                                                                          <!-- drag handle -->
                                                                                                          <span class="handle">
                                                                                                                <i class="fa fa-ellipsis-v"></i>
                                                                                                                <i class="fa fa-ellipsis-v"></i>
                                                                                                              </span>
                                                                                                          <!-- checkbox -->
                                                                                                          <!-- todo text -->
                                                                                                          <span class="text"><?php echo $neto->nome; ?></span>
                                                                                                          <!-- Emphasis label -->
                                                                                                           <!-- General tools such as edit or delete-->
                                                                                                          <div class="tools">
                                                                                                            <i class="fa fa-edit"></i>
                                                                                                            <i class="fa fa-trash-o"></i>
                                                                                                          </div>
                                                                                                        </li>
                                                                                                      <?php } ?>
                                                                                                    </ul>
                                                                                                </div>
                                                                                                <!-- /.box-body -->

                                                                                              </div>
                                                                                     </div>
                                                                                    </li>
                                                                                </ul>       
                                                                                </div> 
                                                                              <?php }else{  ?>
                                                                              <div class="box-body">
                                                                                    <ul class="todo-list">
                                                                                        
                                                                                      <?php // foreach ($filhos as $filho) {  ?>  
                                                                                        
                                                                                      <li>
                                                                                          <!-- drag handle -->
                                                                                          <span class="handle">
                                                                                                <i class="fa fa-ellipsis-v"></i>
                                                                                                <i class="fa fa-ellipsis-v"></i>
                                                                                              </span>
                                                                                          <!-- checkbox -->
                                                                                         
                                                                                          <!-- todo text -->
                                                                                          <span class="text"><?php echo $filho->nome; ?></span>
                                                                                          <!-- Emphasis label -->
                                                                                          <!-- General tools such as edit or delete-->
                                                                                          <div class="tools">
                                                                                            <i class="fa fa-edit"></i>
                                                                                            <i class="fa fa-trash-o"></i>
                                                                                          </div>
                                                                                        </li>
                                                                                      <?php // } ?>   
                                                                                    </ul>
                                                                              </div>
                                                                                <?php } ?>
                                                                              
                                                                            <?php } ?>
                                                                            

                                                                          </ul>
                                                                        </div>
                                                                        <!-- /.box-body -->

                                                                      </div>
                                                             </div>
                                                            </li>
                                                        </ul>       
                                                        </div>  
                                                        <?php 
                                                              
                                                          }else {
                                                             ?>    
                                                            
                                                        <div class="box-body">
                                                                          <!-- See dist/js/pages/dashboard.js to activate the todoList plugin -->
                                                                          <ul class="todo-list">
                                                                           <?php foreach ($filhos as $filho) { ?>    
                                                                            <li>
                                                                              <!-- drag handle -->
                                                                              <span class="handle">
                                                                                    <i class="fa fa-ellipsis-v"></i>
                                                                                    <i class="fa fa-ellipsis-v"></i>
                                                                                  </span>
                                                                              <!-- checkbox -->
                                                                               <!-- todo text -->
                                                                              <span class="text"><?php echo $filho->nome ?></span>
                                                                               <small class="label label-danger"><i class="fa fa-clock-o"></i> 2 mins</small>
                                                                              <!-- General tools such as edit or delete-->
                                                                              <div class="tools">
                                                                                <i class="fa fa-edit"></i>
                                                                                <i class="fa fa-trash-o"></i>
                                                                              </div>
                                                                            </li>
                                                                            <?php } ?>
                                                                            

                                                                          </ul>
                                                                        </div>
                                                        
                                                        <?php  
                                                          }
                                                        ?>    
                                                            
                                                        
                                                        
                                                        <?php    
                                                            $cont_pai2++;
                                                        }
                                                        ?>
                                                     
                                                       
                                                    </div>   

                                                </li>
                                            <?php }
                                                 
                                                 
                                            }  ?>
                                            
                                           
                                            
                                           
                                       
                                        <?php
                                    }
                                    ?>
                           
                    </section>
           </div>     
      </div>
        
      <!-- /.row (main row) -->

    </section>
    <!-- /.content -->
    
 </div>

