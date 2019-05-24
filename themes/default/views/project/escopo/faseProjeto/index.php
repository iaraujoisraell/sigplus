<?php
$usuario = $this->session->userdata('user_id');
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
     
    <br>
    <div class="col-lg-12">
    <div class="box">
        
    <section class="content-header">
                    <h1> <?php echo 'Cadastro de '. $titulo ; ?></h1>    
                  
                  <ol class="breadcrumb">
                    <li><a href="<?= site_url('project'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active">Projetos</li>
                    <li class="active">Fases</li>
                  </ol>
                </section>
        
        <div class="box-header">
            <span class="pull-right-container">
               <div class=" clearfix no-border">
                   
                    <a style="color: #ffffff;" title="<?php echo 'Cadastrar Fase'; ?>" class="btn btn-primary fa fa-plus pull-right" href="<?= site_url('project/novoCadastroFase'); ?>" > Adicionar Fase </a>
                
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
                    $validar_acoes_fase = $cadastro->validar_acoes_fase;

                    $controler_fase = 'project';
                    $funcao_add_evento = 'novoCadastroEvento';
                    $funcao_edit_evento = 'editar_fases_projetos';
                    $funcao_excluir_evento = 'deletarCadastro';
                    $tabela_evento_id = $tabela_id;
                    $menu_id_evento = $menu;
                    $tabela_evento = $tabela_id;
                ?>               
                <li>
                   <div class="box box-primary collapsed box-solid">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo $nome_fase; ?></h3>
                            <small title="Data de Início desta Fase." class="label bg-navy"> <i class="fa fa-calendar"></i><?php echo '  Início :  ' . exibirData($cadastro->data_inicio) ?> </small> 
                            <small title="Data Término desta Fase." class="label bg-green-gradient"><i class="fa fa-calendar"></i> <?php echo ' Fim :  ' . exibirData($cadastro->data_fim); ?> </small> 
                            <small title="Este usuário é responsável por validar as ações desta Fase." class="label bg-blue-gradient"> <i class="fa fa-user"></i><?php echo ' '.$resp_fase; ?> </small>
                            <?php if($validar_acoes_fase == 1){ ?>
                            <small title="Este usuário é responsável por validar as ações desta Fase." class="label label-warning"> <i class="fa fa-bookmark" title="Este usuário é responsável por validar as ações desta Fase."></i>  </small>
                            <?php } ?>
                            <div style="margin-right: 30px;" class="tools">
                                <small  ><a style="color: #ffffff;" title="<?php echo 'Editar Fase'; ?>" class="btn btn-warning fa fa-edit" href="<?= site_url('project/editar_fases_projetos/'.$id_fase); ?>" >  </a></small>
                                <small  ><a style="color: #ffffff;" title="<?php echo 'Deletar Fase'; ?>" class="btn btn-danger fa fa-trash" href="<?= site_url($controler_fase.'/'.$funcao_excluir_evento.'/'.$tabela_evento_id.'/'.$id_fase.'/'.$menu_id_evento.'/fases_projetos'); ?>" data-toggle="modal" data-target="#myModal">  </a></small>
                            </div>
                            <div class="box-tools pull-left">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i  class="fa  fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div id="<?php echo $id_fase; ?>" class="box-body">

                        <div class="col-lg-12">
                        <a style="color: #ffffff;" title="<?php echo 'Cadastrar Novo Evento'; ?>" class="btn btn-info fa fa-plus pull-right" href="<?= site_url($controler_fase.'/'.$funcao_add_evento.'/'.$id_fase); ?>" > Adicionar Evento </a>
                        </div>
                        <br><br>
                            <div class="portlet-body">
                                <ul style="list-style-type: none">
                                    <?php
                                    /*
                                     * EVENTOS chevron-up
                                     */
                                     $eventos = $this->projetos_model->getAllEventosProjetoByFase($id_fase);
                                     $cont_evento = 1;
                                     foreach ($eventos as $evento) {
                                         $id_evento = $evento->id;
                                        $data_inicio_evento = $evento->data_inicio;
                                        $data_fim_evento = $evento->data_fim;
                                        $responsavel_tecnico_id = $evento->responsavel;
                                        $resp_tecnico = $this->site->getUser($responsavel_tecnico_id);
                                        $validar_acoes_evento = $evento->validar_acoes_evento;
                                       //  echo $id_evento;


                                        $controler_edit_evento = 'project';
                                        $funcao_add_item_evento = 'novoItemEvento';
                                        $funcao_edit_evento = 'editar_evento_projetos';
                                        $funcao_excluir_evento = 'excluir_evento_projetos';
                                        $tabela_evento_id = 75;
                                        $tabela_item_evento = 76;
                                        $menu_id_evento = $menu;
                                    ?>
                                    <li>

                                        <div class="box box-default collapsed box-solid">

                                            <div class="box-header with-border">

                                                <font style="font-size: 14px;"><?php echo $cont_evento . ' - ' . $evento->nome_evento; ?> 
                                                <small title="Data de Início deste Evento." class="label bg-navy"> <i class="fa fa-calendar"></i><?php echo '  Início :  ' . exibirData(substr($data_inicio_evento, 0, 10)) ?> </small> 
                                                <small title="Data Término deste Evento." class="label bg-green-gradient"> <i class="fa fa-calendar"></i><?php echo ' Fim :  ' . exibirData(substr($data_fim_evento, 0, 10)); ?> </small>  
                                                <small title="Este usuário é responsável por validar as ações deste evento." class="label bg-blue-gradient"> <i class="fa fa-user"></i> <?php echo ' '.$resp_tecnico->first_name; ?> </small>
                                                <?php if($validar_acoes_evento == 1){ ?>
                                                <small title="Este usuário é responsável por validar as ações deste evento." class="label label-warning"> <i class="fa fa-bookmark" ></i>  </small>
                                                <?php } ?>
                                                </font>
                                                <div style="margin-right: 30px;" class="tools">
                                                        <small  ><a style=" color: #ffffff;" title="Editar Evento" class="btn btn-warning fa fa-edit" href="<?= site_url($controler_edit_evento.'/'.$funcao_edit_evento.'/'.$id_evento); ?>" ></a></small>
                                                        <small  ><a style=" color: #ffffff;" title="Excluir Evento" class="btn btn-danger fa fa-trash" href="<?= site_url($controler_edit_evento.'/'.$funcao_excluir_evento.'/'.$id_evento); ?>" data-toggle="modal" data-target="#myModal">  </a></small>
                                                </div>
                                              <div class="box-tools pull-right">
                                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                                </button>
                                              </div>
                                              <!-- /.box-tools -->
                                            </div>


                                            <div id="<?php echo $id_evento; ?>" class="box-body">


                                                <a style="color: <?php echo '#ffffff'; ?>;" title="<?php echo 'Novo Item do Evento'; ?>" class="btn btn-instagram fa fa-plus pull-right" href="<?= site_url($controler_fase.'/'.$funcao_add_item_evento.'/'.$id_evento); ?>" > Adicionar Item </a>

                                                <br><br>
                                                 <div class="portlet-body">
                                                     <div class="table-responsive">
                                                         <h3>Itens</h3>
                                                        <table style="width:100%;" id="table" class="table" >
                                                        <thead>
                                                            
                                                        </thead>
                                                            <?php
                                                            $intes_eventos = $this->projetos_model->getAllItemEventosProjeto($id_evento, 'tipo', 'asc');
                                                            $cont = 1;
                                                            $cont_item_evento = 1;
                                                            foreach ($intes_eventos as $item) {

                                                            $responsavel_item = $item->responsavel;
                                                            $resp_item = $this->site->getUser($responsavel_item);
                                                            $validar_acoes_item = $item->validar_acoes_item;
                                                            $horas_previstas = $item->horas_previstas;
                                                            
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
                                                                        <small title="Data de Início deste Item de Evento." class="label bg-navy"> <?php echo '  Início :  ' . exibirData(substr($data_inicio, 0, 10)) ?> </small> 
                                                                        <small title="Data Término deste Item de Evento." class="label bg-green-gradient"> <?php echo ' Fim :  ' . exibirData(substr($data_fim, 0, 10)); ?> </small> 
                                                                        <?php if($horas_previstas > 0){ ?>
                                                                            <small title="Número de horas prevista para este item do evento." class="label label-danger"> <i class="fa fa-clock-o"></i> <?php echo ' '.$horas_previstas.' h'; ?> </small>
                                                                         <?php } ?>
                                                                        <?php if($responsavel_item > 0){ ?>
                                                                        <small title="Este usuário é responsável por validar as ações deste item do evento." class="label bg-blue-gradient"> <i class="fa fa-user"></i> <?php echo ' '.$resp_item->first_name; ?> </small>
                                                                         <?php } ?>
                                                                        <?php if($validar_acoes_item == 1){ ?>
                                                                        <small title="Este usuário é responsável por validar as ações deste item do evento." class="label label-warning"> <i class="fa fa-bookmark" ></i>  </small>
                                                                        <?php } ?>
                                                                        
                                                                        <div style="margin-right: 30px;" class="tools">
                                                                            <small  ><a  title="Editar Item" class="btn btn-warning fa fa-edit" href="<?= site_url($controler_edit_item.'/'.$funcao_edit_item.'/'.$item->id); ?>" >  </a></small>
                                                                            <small  ><a  title="Excluir Item" class="btn btn-danger fa fa-trash" href="<?= site_url($controler_edit_item.'/'.$funcao_excluir_item.'/'.$tabela_item_id.'/'.$item->id.'/'.$menu_id_item.'/fases_projetos'); ?>" data-toggle="modal" data-target="#myModal">  </a></small>
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
    
   
    