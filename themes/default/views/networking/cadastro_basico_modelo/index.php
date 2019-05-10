
<div class="content-wrapper">
        <?php 
    
    $usuario =  $this->session->userdata('user_id'); 
      function exibirData($data){
	$rData = explode("-", $data);
	$rData = $rData[2].'/'.$rData[1].'/'.$rData[0];
	return $rData;
   }
    ?>
    <?php 
    
    $usuario =  $this->session->userdata('user_id'); 

    ?>
  
    <!-- Content Header (Page header) -->
    
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
                <div class="box-header">
                    <span class="pull-right-container">
                       <div class=" clearfix no-border">
                           <a style="background-color: green; color: #ffffff;" title="Editar Registro" class="btn btn-default pull-right" href="<?= site_url('owner/novoCadastroBasico/'.$tabela_id."/".$menu_id); ?>" data-toggle="modal" data-target="#myModal">  
                           <i class="fa fa-plus"></i>  Novo Cadastro 
                           </a> 
                         
                        </div>
                    </span>
                </div>
           
                  <br>
                    <div class="box-body">
                       <table id="example1" class="table table-bordered table-striped">
                            <thead>
                             <tr style=" width: 100%;">

                                <th style="width: 5%;">Id</th>
                                <th style="">Projeto</th>
                                <th style="">Início</th>
                                <th style="">Fim</th>
                                
                                <th style="">% Conc.</th>
                                <th style="">Gerente</th>
                                <th style="">Status</th>
                                
                                <th style="">Editar</th>
                                <th style="">Cancelar</th>
                                
                                
                                
                            </tr>
                            </thead>
                            <tbody>
                                 <?php
                                    $wu4[''] = '';
                                    $cont_lista = 1;
                                    foreach ($cadastros as $cadastro) {
                                        $gerente = $cadastro->gerente_area;
                                        $resp_tecnico_fase = $this->site->getUser($gerente);
                                        $gerente_projeto = $resp_tecnico_fase->first_name;
                                        
                                        $status = $cadastro->status;
                                        if($status == 'ATIVO'){
                                           $status_label = 'primary'; 
                                        }else if($status == 'CANCELADO'){
                                            $status_label = 'danger'; 
                                        }else if($status == 'AGUARDANDO'){
                                            $status_label = 'warning'; 
                                        }else if($status == 'CONCLUÍDO'){
                                            $status_label = 'success'; 
                                        }
                                    ?>               

                                        <tr  >

                                            <td style="width: 5%;"><small ><?php echo $cont_lista++; ?></small> </td> 
                                            <td > <small ><?php echo $cadastro->projeto; ?></small></td>
                                            <td > <small ><?php echo exibirData($cadastro->dt_inicio); ?></small></td>
                                            <td > <small ><?php echo exibirData($cadastro->dt_final); ?></small></td>
                                           
                                            <td > <small ><?php echo ''; ?></small></td>
                                            <td > <small ><?php echo $gerente_projeto; ?></small></td>
                                            <td > <small class="label label-<?php echo $status_label; ?>" ><?php echo $status; ?></small></td>
                                            <td > <small ><a style="background-color: <?php echo 'orange'; ?>; color: <?php echo '#ffffff'; ?>;" title="<?php echo 'Editar Fase'; ?>" class="btn fa fa-edit" href="<?= site_url($controler_fase.'/'.$funcao_edit_evento.'/'.$tabela_evento_id.'/'.$id_fase.'/'.$menu_id_evento.'/fases_projetos'); ?>" data-toggle="modal" data-target="#myModal">  </a></small></td>
                                            <td > <small ><a style="background-color: <?php echo 'red'; ?>; color: <?php echo '#ffffff'; ?>;" title="<?php echo 'Deletar Fase'; ?>" class="btn fa fa-trash" href="<?= site_url($controler_fase.'/'.$funcao_excluir_evento.'/'.$tabela_evento_id.'/'.$id_fase.'/'.$menu_id_evento.'/fases_projetos'); ?>" data-toggle="modal" data-target="#myModal">  </a></small></td>
                                            
                                            <?php 
                                            
                                            foreach ($botoes_menu as $botao) {
                                                $botao_id = $botao->botao_id;
                                                $controle_bd = $botao->controle;
                                                $funcao_bt = $botao->funcao;
                                                
                                                $dados_botao =  $this->owner_model->getBotaoCadastroById($botao_id);
                                                $nome_botao = $dados_botao->descricao;
                                                $icon_botao = $dados_botao->icon;
                                                $background_botao = $dados_botao->background;
                                                $cor_botao = $dados_botao->cor;
                                                $observacao_botao = $dados_botao->observacao;
                                                
                                                //PEDA OS DADOS DO CONTROLE
                                                 $dados_controle = $this->owner_model->getControleById($controle_bd);//tabela relacionamento
                                                 $nome_controle = $dados_controle->descricao;
                                                 
                                                 //PEDA OS DADOS DA FUNÇÃO
                                                 $dados_funcao = $this->owner_model->getFuncaoById($funcao_bt);//tabela relacionamento
                                                 $nome_funcao = $dados_funcao->funcao;
                                            ?>
                                             <td style="width: 10%; "><small  ><a style="background-color: <?php echo $background_botao; ?>; color: <?php echo $cor_botao; ?>;" title="<?php echo $nome_botao.' '.$observacao_botao; ?>" class="btn fa fa-<?php echo $icon_botao; ?>" href="<?= site_url($nome_controle.'/'.$nome_funcao.'/'.$tabela_id.'/'.$cadastro->id.'/'.$menu_id); ?>" data-toggle="modal" data-target="#myModal">  </a></small></td>
                                          
                                            <?php } ?>
                                            

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

