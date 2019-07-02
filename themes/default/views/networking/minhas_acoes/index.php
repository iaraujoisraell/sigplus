  <?php 
    $usuario =  $this->session->userdata('user_id'); 
    $tarefa = 'tarefas';
    ?>

 <?php

        function geraTimestamp($data) {
            $partes = explode('/', $data);
            return mktime(0, 0, 0, $partes[1], $partes[0], $partes[2]);
        }
        ?>
  <div class="col-lg-12">
    <div class="box">
     <section class="content-header">
                  <h1>
                    MINHAS AÇÕES 
                    <small><?php echo 'Registro de Ações de Projetos'; ?> </small>
                  </h1>
                  <ol class="breadcrumb">
                    <li><a href="<?= site_url('project'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active">Ações</li>
                  </ol>
                </section>

             <div class="box-header">
            <span class="pull-right-container">

            </span>
        </div>
    </div>
    </div>
<br>
<!-- FILTRO  -->
<div class="col-lg-12">
    <div class="box">
        <small><?php echo 'Filtro'; ?> </small>
        <section class="content-header">

    </section>
          <?php
            $attrib = array('data-toggle' => 'validator', 'role' => 'form');
            echo form_open_multipart("welcome/minhasAcoes", $attrib);
            echo form_hidden('idprojeto', $projeto->id);
            ?>
        
        <div class="col-md-3">
          <div class="form-group">
            
            <?php
            $wu_projetos['0'] = 'Todos';
            $usuario = $this->session->userdata('user_id');
            $projetos_users = $this->atas_model->getAllProjetosUserById_User($usuario);
            foreach ($projetos_users as $projeto_u) {
                $wu_projetos[$projeto_u->id] = $projeto_u->projeto;
            }
                echo form_dropdown('projeto_filtro', $wu_projetos, (isset($_POST['projeto_filtro']) ? $_POST['projeto_filtro'] : $projeto_filtro), 'id="reacao"  class="form-control selectpicker  select" data-placeholder="' . lang("Projetos") . ' "  style="width:100%;" ');
            ?>
                                
          </div>
        </div>
       <div class="col-md-3">
          <div class="form-group">
          <?php $pst['0'] = 'Todos';
              $pst['PENDENTE'] = lang('PENDENTE');
              $pst['ATRASADO'] = lang('ATRASADO');
              $pst['AGUARDANDO VALIDAÇÃO'] = lang('AGUARDANDO VALIDAÇÃO');
              $pst['CONCLUÍDO'] = lang('CONCLUÍDO');
              $pst['CANCELADO'] = lang('CANCELADO');
             
              ?>
             <?php  
                  echo form_dropdown('status_filtro', $pst, (isset($_POST['status_filtro']) ? $_POST['status_filtro'] : $status_filtro), 'id="tipo"  class="form-control " data-placeholder="' .  lang("Status") . '"    style="width:100%;" ');
                 ?> 
          </div>
        </div>
        
        
        
        <?php echo form_submit('add_marco', lang("Pesquisar"), 'id="add_item" class="btn btn-primary "  '); ?>
        <a href="<?= site_url('welcome/minhasAcoes'); ?>">TODOS</a>
        <?php echo form_close(); ?>
    <br>
    </div>
    </div>
<br>

    <section  class="content">
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

                <div class="box">
                    <?php
                $cont_atrasadas = 0;
                $cont_pendentes = 0;
                $cont_concluidas = 0;
                $cont_cancelado = 0;
                $cont_avalidacao = 0;
                $total_pendentes = 0;
              
                foreach ($planos_resumo as $plano) {
                     
                    $status = $plano->status;
                    $data_prazo = $plano->data_termino;
                   

                    if ($status == 'PENDENTE') {
                        $dataHoje = date('Y-m-d');

                        if ($dataHoje <= $data_prazo) {
                        //    $novo_status = 'PENDENTE';
                            $cont_pendentes++;
                        }

                        if ($dataHoje > $data_prazo) {
                        //    $novo_status = 'ATRASADO';
                            $cont_atrasadas++;
                        }
                        
                        
                    } else if ($status == 'AGUARDANDO VALIDAÇÃO') {
                    //    $novo_status = 'AGUARDANDO VALIDAÇÃO';
                        $cont_avalidacao++;
                    }else if ($status == 'CONCLUÍDO') {
                    //    $novo_status = 'AGUARDANDO VALIDAÇÃO';
                        $cont_concluidas++;
                    }else if ($status == 'CANCELADO') {
                    //    $novo_status = 'CANCELADO';
                        $cont_cancelado++;
                    }

                    //$projetos_usuario = $this->atas_model->getAtaProjetoByID_ATA($plano->idatas);
                }
                ?>   

                <br>
                <div class="col-lg-12">
                <table>
                    <thead>
                        <tr >
                            <th > <a href="<?= site_url('welcome/minhasAcoes/1'); ?>"> <font class="label bg-green-active" style="font-size: 12px; font-weight: bold"> Concluídas : <?php echo $cont_concluidas; ?> </font> </a></th>
                            <th > <a href="<?= site_url('welcome/minhasAcoes/2'); ?>"> <font class="label bg-orange-active" style="font-size: 12px; font-weight: bold"> Pendentes : <?php echo $cont_pendentes; ?></font> </a></th>
                            <th > <a href="<?= site_url('welcome/minhasAcoes/3'); ?>"> <font class="label bg-red-active" style="font-size: 12px; font-weight: bold"> Atrasadas : <?php echo $cont_atrasadas; ?></font> </a></th>
                            <th > <a href="<?= site_url('welcome/minhasAcoes/4'); ?>"> <font class="label bg-blue-gradient" style="font-size: 12px; font-weight: bold"> Aguardando Validação : <?php echo $cont_avalidacao; ?></font> </a></th>
                            <th > <a href="<?= site_url('welcome/minhasAcoes/5'); ?>"> <font class="label bg-black-active" style="font-size: 12px; font-weight: bold"> Canceladas : <?php echo $cont_cancelado; ?></font> </a></th>
                        </tr>
                    </thead> 
                </table>
                </div>    
                <br>
                      <br>
                    <div class="table-responsive">
                        <div class="box-body">
                            <table style="width: 100%" id="minhas_acoes_usuario" class="table table-bordered table-striped">
                            <thead>
                                <th style="width: 5%">AÇÃO</th>
                                <th style="width: 45%">DESCRIÇÃO</th>
                                <th style="width: 15%">ANDAMENTO</th>
                                <th>Início</th>
                                <th>Término</th>
                                <th>Conclusão</th>
                                
                                
                                <th>AÇÃO </th>
                                
                            </thead>
                            <tbody>
                                 <?php
                                    $wu4[''] = '';
                                    $cont = 1;
                                      foreach ($planos as $plano) {
                                            $evento = $this->atas_model->getAllitemEventoByID($plano->eventos);
                                            
                                            $plano_id = $plano->idplanos;
                                            $status = $plano->status;
                                            $data_prazo = $plano->data_termino;
                                            $dataHoje = date('Y-m-d');
                                            
                                            $andamento_plano = $plano->andamento;
                                            if($andamento_plano == 0){
                                                $andamento_plano = "";
                                            }
                                            
                                            if ($status == 'PENDENTE') {


                                                /*
                                                 * SE A DATA ATUAL FOR < A DATA DO PRAZO
                                                 * PENDENTE
                                                 */
                                                if ($dataHoje <= $data_prazo) {
                                                    $novo_status = 'PENDENTE';
                                                    $desc_tipo = "orange-active";
                                                }

                                                /*
                                                 * SE A DATA ATUAL FOR > A DATA DO PRAZO
                                                 * ATRASADO (X DIAS)
                                                 * +5 DIAS
                                                 * +10 DIAS
                                                 * 
                                                 */
                                                if ($dataHoje > $data_prazo) {
                                                    $novo_status = 'ATRASADO';
                                                    $desc_tipo = "red-active";

                                                  $time_inicial = geraTimestamp($this->sma->hrld($dataHoje));
                                                    $time_final = geraTimestamp($this->sma->hrld($data_prazo));
                                                  //  $time_inicial = date('d/m/Y', strtotime($dataHoje));
                                                  //  $time_final = date('d/m/Y', strtotime($data_prazo));
                                                    // Calcula a diferença de segundos entre as duas datas:
                                                    $diferenca = $time_final - $time_inicial; // 19522800 segundos
                                                    // Calcula a diferença de dias
                                                    $dias = (int) floor($diferenca / (60 * 60 * 24)); // 225 dias

                                                    if ($dias >= '-5') {
                                                        $qtde_dias = $dias;
                                                    } else if (($dias < '-5') && ($dias >= '-10')) {
                                                        $qtde_dias = $dias;
                                                    } else if ($dias < '-10') {
                                                        $qtde_dias = $dias;
                                                    } else if ($dias < '-15') {
                                                        $qtde_dias = '+15';
                                                    }
                                                    $qtde_dias = str_replace('-', '', $qtde_dias);
                                                }
                                            } else if ($status == 'AGUARDANDO VALIDAÇÃO') {
                                                $novo_status = 'AGUARDANDO VALIDAÇÃO';
                                                $desc_tipo = "blue-gradient";


                                            } else if ($status == 'CONCLUÍDO') {
                                                $novo_status = 'CONCLUÍDO';
                                                $desc_tipo = "green-active";

                                            }else if ($status == 'CANCELADO') {
                                                $novo_status = 'CANCELADO';
                                                $desc_tipo = "black-active";
                                            }

                                            if($plano->idatas){
                                            $projetos_usuario = $this->atas_model->getAtaProjetoByID_ATA($plano->idatas);
                                            $projeto = $projetos_usuario->projetos;
                                            }else if($plano->idplano){
                                            $projetos_usuario = $this->atas_model->getProjetoByID($plano->projeto);    
                                            $projeto = $projetos_usuario->projeto;
                                            }
                                            
                                            $mensagem_acao = $this->networking_model->getQtdeMensagensNaoLidasByAcaoAndUsuario($plano_id);
                                            $qtde_msg_acao = $mensagem_acao->quantidade;
                                            
                                            ?>   

                                            <tr   class="odd gradeX">
                                                <td style="width: 5%">
                                                    <font title="ID da ação" class="label bg-black-active" style="font-size: 12px; "><?php echo $plano->sequencial; ?></font>
                                                </td>
                                                <td style="width: 45%">
                                                   <?php if($projeto){ ?>
                                                    <font title="Nome do Projeto " class="label label-primary" style="font-size: 12px; "><?php  echo $projeto; ?> </font>
                                                    <?php } ?>
                                                    <font title="Local de onde foi registrado a Ação. Pode ser uma ATA ou um Plano de Ação" class="label label-default" style="font-size: 12px; "><?php if($plano->idatas){ echo 'ATA : '.$plano->idatas; }else if($plano->idplano){ echo 'P.A.: '. $plano->idplano; } ?>  </font>
                                                    <font class="label bg-<?php echo $desc_tipo; ?>" style="font-size: 12px; font-weight: bold"><?php echo $novo_status; ?> <?php  if ($novo_status == 'ATRASADO') { echo  '  (' . $qtde_dias . ' dias ) ';   } ?>  </font>
                                                    
                                                       <h2> 
                                                           <?php if($qtde_msg_acao == 1){ ?> <small title="Uma nova ação ou Mensagem na ação" class="label bg-green-active">New</small> <?php } ?>
                                                                <?php echo $plano->descricao; ?>
                                                       </h2>
                                                   
                                                                
                                                           
                                                </td>   

                                                <td style="width: 15%" class="center">
                                                    <div style="margin-top: 5px;" class="col-md-10">    
                                                        <div style="height: 30px;" class="progress progress-sm active">
                                                            <div class="progress-bar progress-bar-success <?php if($andamento_plano < 100){ ?> progress-bar-striped <?php } ?>" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $andamento_plano; ?>%">
                                                                <span ><?php echo $andamento_plano; ?><?php if($andamento_plano > 0){ ?> %  <?php }else if($porcentagem == 0){ ?> 0% <?php } ?></span>
                                                            </div>
                                                        </div>      
                                                    </div> 
                                                </td>
                                                <td class="center">
                                                    <font title="Data de Início da ação" style="font-size: 12px;" class="label label-primary"> <?php echo date('d/m/Y', strtotime($plano->data_entrega_demanda)); ?></font> 
                                                </td>     
                                                <td class="center">
                                                    <font title="Data prevista para Término da ação" style="font-size: 12px;" class="label bg-navy-active"> <?php echo  date('d/m/Y', strtotime($plano->data_termino)); ?></font>
                                                </td>
                                                 <td class="center">
                                                  <?php if($plano->status == 'CONCLUÍDO'){ ?>
                                                  <font style="font-size: 12px;" class="label label-success"> <?php echo  date('d/m/Y', strtotime($plano->data_retorno_usuario)); ?></font>
                                                <?php  } ?> 
                                                </td>
                                                <td class="center">
                                                    <?php if ($plano->status == 'PENDENTE')  { ?>
                                                        <a title="Visualizar o cadastro completo da ação" class="btn btn-primary  fa fa-folder-open-o" href="<?= site_url('welcome/dados_cadastrais_acao/' . $plano->idplanos.'/'.$projeto_filtro.'/'.$status_filtro); ?>"> </a>
                                                    <?php } else  { ?>
                                                        <a title="Visualizar o cadastro completo da ação" class="btn btn-default fa fa-folder-open-o" href="<?= site_url('welcome/consultar_acao/' . $plano->idplanos.'/'.$projeto_filtro.'/'.$status_filtro); ?>"> </a>
                                                    <?php } ?>
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
                
      <!-- /.row (main row) -->
            </div>
        </div>
    </section>
    <!-- /.content -->
 

 <script>
  $(function () {
  $('#minhas_acoes_usuario').DataTable({
      "order": [[ 3, "desc" ]]
    })
  })
</script>