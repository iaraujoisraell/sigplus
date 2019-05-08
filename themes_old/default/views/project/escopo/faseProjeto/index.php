<?php
$usuario = $this->session->userdata('user_id');
$projetos = $this->projetos_model->getProjetoAtualByID_completo();
$id_projeto = $projetos->id;
$nome_projeto = $projetos->nome_projeto;
$gerente_projeto = $projetos->gerente_area;
$gerente_dados = $this->site->geUserByID($gerente_projeto);


/*
 * VERIFICA SE TEM AÇÕES AGUARDANDO VALIDAÇÃO
 */
//$quantidadeAvalidacao = $this->site->getAllPlanosAguardandoValidacao($id_projeto);
//$acoes_aguardando_validacao = $quantidadeAvalidacao->quantidade;

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
    
  
  
    <!-- Content Header (Page header) -->
     
    <br>
    <div class="col-lg-12">
    <div class="box">
        
    <section class="content-header">
                    <h1><?php echo $nome_projeto ; ?></h1>    
                  <h2>
                    <?php echo 'Cadastro de '. $titulo ; ?>
                  
                  </h2>
                  <ol class="breadcrumb">
                    <li><a href="<?= site_url('owner'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active">Empresa</li>
                  </ol>
                </section>

        <div class="box-header">
            <span class="pull-right-container">
               <div class=" clearfix no-border">
                   <a  title="Cadastrar Nova Fase" class="btn btn-default pull-right" href="<?= site_url('project/novoCadastroFase/'.$tabela_id."/".$menu_id.'/fases_projetos'); ?>" data-toggle="modal" data-target="#myModal">  
                   <i class="fa fa-plus"></i>   Nova Fase
                   </a> 

                </div>
            </span>
        </div>
        <br>
    </div> 
    </div> 
    
    <div class="col-lg-12">
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
    </div>
    <!-- Main content -->
   
   
    
  
  
    <!-- Content Header (Page header) -->
     
    <br>
    <!-- Main content -->
    <section class="content">

     <div class="row">  
        <div class="col-lg-12">
        <div class="box">       
             <div class="box-body">
                       
                           
                           <ul class="todo-list">
                              
                                 <?php
                                    $wu4[''] = '';
                                    $cont_lista = 1;
                                    foreach ($cadastros as $cadastro) {
                                        $id_fase =  $cadastro->id;
                                        $nome_fase =  $cadastro->nome_fase;
                                        $responsavel = $cadastro->responsavel_aprovacao;
                                        $resp_tecnico_fase = $this->site->getUser($responsavel);
                                        $resp_fase = $resp_tecnico_fase->first_name;
                                        
                                        $controler_fase = 'project';
                                        $funcao_add_evento = 'novoCadastroEvento';
                                        $funcao_edit_evento = 'editar_fases_projetos';
                                        $funcao_excluir_evento = 'deletarCadastro';
                                        $tabela_evento_id = 21;
                                        $menu_id_evento = 64;
                                        $tabela_evento = 75;
                                    ?>               
                                    <li>
                                       <div class="box box-default collapsed-box box-solid">
                                                <div class="box-header with-border">
                                                    <h3 class="box-title"><?php echo $nome_fase; ?></h3>
                                                    <small class="label label-primary"> <i class="fa fa-calendar"></i><?php echo '  De :  ' . exibirData($cadastro->data_inicio) ?> </small> <small class="label label-primary"><i class="fa fa-calendar"></i> <?php echo ' Até :  ' . exibirData($cadastro->data_fim); ?> </small> 
                                                    <small class="label label-success"> <i class="fa fa-user"></i><?php echo ' '.$resp_fase; ?> </small>
                                                    <div style="margin-right: 30px;" class="tools">
                                                        <small  ><a style="background-color: <?php echo 'green'; ?>; color: <?php echo '#ffffff'; ?>;" title="<?php echo 'Cadastrar Novo Evento'; ?>" class="btn fa fa-plus" href="<?= site_url($controler_fase.'/'.$funcao_add_evento.'/'.$tabela_evento.'/'.$id_fase.'/'.$menu_id_evento.'/fases_projetos'); ?>" data-toggle="modal" data-target="#myModal">  </a></small>    
                                                        <small  ><a style="background-color: <?php echo 'orange'; ?>; color: <?php echo '#ffffff'; ?>;" title="<?php echo 'Editar Fase'; ?>" class="btn fa fa-edit" href="<?= site_url($controler_fase.'/'.$funcao_edit_evento.'/'.$tabela_evento_id.'/'.$id_fase.'/'.$menu_id_evento.'/fases_projetos'); ?>" data-toggle="modal" data-target="#myModal">  </a></small>
                                                        <small  ><a style="background-color: <?php echo 'red'; ?>; color: <?php echo '#ffffff'; ?>;" title="<?php echo 'Deletar Fase'; ?>" class="btn fa fa-trash" href="<?= site_url($controler_fase.'/'.$funcao_excluir_evento.'/'.$tabela_evento_id.'/'.$id_fase.'/'.$menu_id_evento.'/fases_projetos'); ?>" data-toggle="modal" data-target="#myModal">  </a></small>
                                                    </div>
                                                    <div class="box-tools pull-left">

                                                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i  class="fa fa-2x fa-plus"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <div id="<?php echo $id_fase; ?>" class="box-body">
                                                    <div class="portlet-body">
                                                        <ul class="todo-list">
                                                            <?php
                                                            /*
                                                             * EVENTOS
                                                             */
                                                             $eventos = $this->projetos_model->getAllEventosProjetoByFase($id_fase);
                                                             $cont_evento = 1;
                                                             foreach ($eventos as $evento) {
                                                                 $id_evento = $evento->id;
                                                                $data_inicio_evento = $evento->data_inicio;
                                                                $data_fim_evento = $evento->data_fim;
                                                                $responsavel_tecnico_id = $evento->responsavel;
                                                                $resp_tecnico = $this->site->getUser($responsavel_tecnico_id);
                                                               //  echo $id_evento;


                                                                $controler_edit_evento = 'project';
                                                                $funcao_add_item_evento = 'novoItemEvento';
                                                                $funcao_edit_evento = 'editar_evento_projetos';
                                                                $funcao_excluir_evento = 'excluir_evento_projetos';
                                                                $tabela_evento_id = 75;
                                                                $tabela_item_evento = 76;
                                                                $menu_id_evento = 64;
                                                            ?>
                                                            <li>
                                                                <div class="box box-default collapsed-box box-solid">
                                                                    <div class="box-header with-border">
                                                                        <font style="font-size: 14px;"><?php echo $cont_evento . ' - ' . $evento->nome_evento; ?> <small class="label label-warning"> <i class="fa fa-calendar"></i>
                                                                             <?php echo '  De :  ' . exibirData(substr($data_inicio_evento, 0, 10)) ?> </small> <small class="label label-warning"> <i class="fa fa-calendar"></i><?php echo ' Até :  ' . exibirData(substr($data_fim_evento, 0, 10)); ?> </small>  
                                                                             <small class="label label-success"> <i class="fa fa-user"></i> <?php echo ' '.$resp_tecnico->first_name; ?> </small></font>
                                                                        <div style="margin-right: 30px;" class="tools">
                                                                                <small  ><a style="background-color: <?php echo 'blue'; ?>; color: <?php echo '#ffffff'; ?>;" title="<?php echo 'Novo Item do Evento'; ?>" class="btn fa fa-plus" href="<?= site_url($controler_fase.'/'.$funcao_add_item_evento.'/'.$tabela_evento.'/'.$id_evento.'/'.$menu_id_evento.'/fases_projetos'); ?>" data-toggle="modal" data-target="#myModal">  </a></small>    
                                                                                <small  ><a style="background-color: orange; color: #ffffff;" title="Editar Evento" class="btn fa fa-edit" href="<?= site_url($controler_edit_evento.'/'.$funcao_edit_evento.'/'.$tabela_evento_id.'/'.$id_evento.'/'.$menu_id_evento.'/fases_projetos'); ?>" data-toggle="modal" data-target="#myModal"></a></small>
                                                                                <small  ><a style="background-color: red; color: #ffffff;" title="Excluir Evento" class="btn fa fa-trash" href="<?= site_url($controler_edit_evento.'/'.$funcao_excluir_evento.'/'.$tabela_evento_id.'/'.$id_evento.'/'.$menu_id_evento.'/fases_projetos'); ?>" data-toggle="modal" data-target="#myModal">  </a></small>
                                                                        </div>
                                                                      <div class="box-tools pull-right">
                                                                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                                                                        </button>
                                                                      </div>
                                                                      <!-- /.box-tools -->
                                                                    </div>


                                                                    <div id="<?php echo $id_evento; ?>" class="box-body">

                                                                         <div class="portlet-body">
                                                                             <div class="table-responsive">
                                                                                <table style="width:100%;" id="table" class="table" >
                                                                                <thead>
                                                                                    <tr>

                                                                                        <td style="width:20%;  font-size: 16px; font-weight: bold; "> Itens </td>

                                                                                    </tr>
                                                                                </thead>


                                                                                <?php
                                                                                $intes_eventos = $this->projetos_model->getAllItemEventosProjeto($id_evento, 'tipo', 'asc');
                                                                                $cont = 1;
                                                                                $cont_item_evento = 1;
                                                                                foreach ($intes_eventos as $item) {


                                                                                $data_inicio = $item->dt_inicio;
                                                                                $data_fim = $item->dt_fim;

                                                                                $controler_edit_item = 'project';
                                                                                $funcao_edit_item = 'editar_item_evento_projetos';
                                                                                $funcao_excluir_item = 'deletarCadastro';
                                                                                $tabela_item_id = 76;
                                                                                $menu_id_item = 64;
                                                                                ?>
                                                                                        <tr>
                                                                                            <td style="width:20%; text-align: justify  ">
                                                                                                <font style="width: 70%; text-align: justify;"><?php echo $cont_evento . '.' . $cont_item_evento . ' - ' . $item->descricao; ?> </font>
                                                                                                <small class="label label-default"> <?php echo '  De :  ' . exibirData(substr($data_inicio, 0, 10)) ?> </small> <small class="label label-default"> <?php echo ' Até :  ' . exibirData(substr($data_fim, 0, 10)); ?> </small> 
                                                                                                <div style="margin-right: 30px;" class="tools">
                                                                                                    <small  ><a style="background-color: orange; color: #ffffff;" title="Editar Item" class="btn fa fa-edit" href="<?= site_url($controler_edit_item.'/'.$funcao_edit_item.'/'.$tabela_item_id.'/'.$item->id.'/'.$menu_id_item.'/fases_projetos'); ?>" data-toggle="modal" data-target="#myModal">  </a></small>
                                                                                                    <small  ><a style="background-color: red; color: #ffffff;" title="Excluir Item" class="btn fa fa-trash" href="<?= site_url($controler_edit_item.'/'.$funcao_excluir_item.'/'.$tabela_item_id.'/'.$item->id.'/'.$menu_id_item.'/fases_projetos'); ?>" data-toggle="modal" data-target="#myModal">  </a></small>
                                                                                                </div>
                                                                                            </td>
                                                                                        </tr>


                                                                                    <?php
                                                                                    $cont_item_evento++;
                                                                                }
                                                                                $cont_evento++;
                                                                                ?>


                                                                                </table>
                                                                             </div>
                                                                         </div>    
                                                                    </div>
                                                            </div>
                                                            </li>
                                                            <?php
                                                             }
                                                            ?>
                                                        </ul>
                                                    </div>    
                                                </div>
                                                        <!-- /.box-body -->
                                        </div> 
                                    </li>    
                                        
                                        <?php
                                    }
                                    ?>
                                    
                            </ul>
                        
                    </div>
        </div>
        </div>
    </div>     
        
      <!-- /.row (main row) -->

    </section>
    <!-- /.content -->
    
   
    