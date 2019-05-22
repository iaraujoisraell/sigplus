  <?php
    function resume( $var, $limite ){	// Se o texto for maior que o limite, ele corta o texto e adiciona 3 pontinhos.	
              if (strlen($var) > $limite)	{
                    $var = substr($var, 0, $limite);		
                    $var = trim($var) . "...";	

              }return $var;

              }
          ?>

<?php
   $usuario = $this->session->userdata('user_id');
   if(!$projeto->aba){
       $projeto->aba = 1;
   }
   
   
    $status = $projeto->status;
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

       
            
    <div class="col-lg-12">
    <div class="box">
        <section class="content-header">
             <h1>
            <?php echo $projeto->projeto; ?>
              <small class="label label-<?php echo $status_label; ?>" ><?php echo $status; ?></small>
                
          </h1>
          <ol class="breadcrumb">
            <li><a href="<?= site_url('project'); ?>"><i class="fa fa-home"></i> Home</a></li>
            <li >Projetos</li>
            <li class="active">Editar Projeto</li>
          </ol>

        </section>
        <br>
    </div> 
    </div>
    
    <div class="row">  
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
    </div>
    
    <section  class="content">
    <div class="row">    
    <div class="col-lg-12">
        <div class="box">
                               
                <div  class="nav-tabs-custom">
                    <ul style="background-color: #d2d6de; " class="nav nav-tabs">
                        <li class="<?php if($projeto->aba == 1){ ?> active <?php } ?>"><a href="#cadastro"  data-toggle="tab"><b>Dados Cadastrais <i class="fa fa-file-text-o"></i></b></a></li>
                        <li class="<?php if($projeto->aba == 2){ ?> active <?php } ?>" ><a href="#equipe" data-toggle="tab"><b>Equipe <i class="fa fa-users"></i></b></a></li> 
                        <li class="<?php if($projeto->aba == 3){ ?> active <?php } ?>"><a href="#marcos" data-toggle="tab"><b>Marcos <i class="fa fa-calendar-check-o"></i></b></a></li>
                        <li class="<?php if($projeto->aba == 4){ ?> active <?php } ?>"><a href="#arquivos" data-toggle="tab"><b>Arquivos <i class="fa fa-folder-open"></i></b></a></li>
                        <li class="<?php if($projeto->aba == 5){ ?> active <?php } ?>" ><a href="#partes_interessadas" data-toggle="tab"><b>Partes Interessadas <i class="fa fa-user-circle-o"></i></b></a></li>
                        <li class="<?php if($projeto->aba == 6){ ?> active <?php } ?>"><a title="Acesso de Usuários ao Projeto" href="#acesso" data-toggle="tab"><b>Acesso <i class="fa fa-user-secret"></i></b></a></li>
                        <li class="<?php if($projeto->aba == 7){ ?> active <?php } ?>"><a href="#historico" data-toggle="tab"><b>Histórico <i class="fa fa-comments-o"></i></b></a></li>
                        <li class="<?php if($projeto->aba == 8){ ?> active <?php } ?>"><a href="#log" data-toggle="tab"><b>Log <i class="fa fa-search"></i></b></a></li>

                    </ul>
                    <div class="tab-content">
                        <!-- DADOS CADASTRAIS -->
                        <div class="<?php if($projeto->aba == 1){ ?> active <?php } ?> tab-pane" id="cadastro">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <center>
                                            <h2> DADOS CADASTRAIS DO PROJETO   </h2>
                                        </center>
                                    </div>
                                </div>

                                <div class="clearfix"></div>
                                <?php
                                $attrib = array('data-toggle' => 'validator', 'role' => 'form');
                                echo form_open_multipart("project/editarProjeto/", $attrib);
                                echo form_hidden('idprojeto', $projeto->id);
                                ?>
                                <div class="col-md-12">
                                    <!-- ITEM EVENTO -->
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <?= lang("Nome do Projeto", "projeto"); ?>
                                            <?php echo form_input('projeto', (isset($_POST['projeto']) ? $_POST['projeto'] : $projeto->projeto), 'maxlength="250" required class="form-control input-tip"  id="projeto"'); ?>
                                        </div>
                                    </div>

                                    <div class="col-md-6">

                                        <div class="form-group">

                                            <?= lang("Cliente", "cliente"); ?> <i title="O cliente pode ser a própria empresa ou um setor interno da empresa, representa quem será o 'dono' projeto." class="fa fa-info-circle"> </i> <small></small>
                                            <a  title="Novo Cliente" class="btn btn-primary pull-right" href="<?= site_url('project/novoCadastroBasico/' . 91 . "/" . 28); ?>" data-toggle="modal" data-target="#myModal">  
                                                <i class="fa fa-plus"></i> 
                                            </a>
                                            <?php
                                            $wu_cliente[''] = '';
                                            foreach ($clientes as $cliente) {
                                                $wu_cliente[$cliente->id] = $cliente->name;
                                            }
                                            //  echo form_dropdown('responsavel[]', $wu4, (isset($_POST['responsavel']) ? $_POST['responsavel'] : ""), 'id="slResponsavel"  class="form-control  select" data-placeholder="' . lang("Selecione o Responsável") . ' "  style="width:100%;" multiple  required="required"');
                                            echo form_dropdown('cliente', $wu_cliente, (isset($_POST['cliente']) ? $_POST['cliente'] : "$projeto->cliente"), 'id="cliente" required="true"  class="form-control  select" data-placeholder="' . lang("Selecione o Cliente") . ' "   style="width:100%;" ');
                                            ?>

                                        </div>

                                    </div>

                                    <div class="col-md-6">

                                        <div class="form-group">

                                            <?= lang("Categoria", "categoria"); ?> <i title="Categoria do projeto." class="fa fa-info-circle"> </i> <small></small>
                                            <a  title="Nova Categoria" class="btn btn-primary pull-right" href="<?= site_url('project/novoCadastroBasico/' . 96 . "/" . 28); ?>" data-toggle="modal" data-target="#myModal">  
                                                <i class="fa fa-plus"></i> 
                                            </a>
                                            <?php
                                            $wu_categoria[''] = '';
                                            foreach ($categorias as $categoria) {
                                                $wu_categoria[$categoria->id] = $categoria->descricao;
                                            }
                                            //  echo form_dropdown('responsavel[]', $wu4, (isset($_POST['responsavel']) ? $_POST['responsavel'] : ""), 'id="slResponsavel"  class="form-control  select" data-placeholder="' . lang("Selecione o Responsável") . ' "  style="width:100%;" multiple  required="required"');
                                            echo form_dropdown('categoria', $wu_categoria, (isset($_POST['categoria']) ? $_POST['categoria'] : "$projeto->categoria"), 'id="categoria" required="true"  class="form-control  select" data-placeholder="' . lang("Selecione a Categoria") . ' "   style="width:100%;" ');
                                            ?>

                                        </div>

                                    </div>


                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <?= lang("Data Início *", "data_inicio"); ?> 
                                            <?php if($status == 'ATIVO'){ ?>
                                            <a  title="Nova Categoria" class="btn btn-warning pull-right" href="<?= site_url('project/alterarDataInicial'); ?>" data-toggle="modal" data-target="#myModal">  
                                                <i class="fa fa-edit"></i> 
                                            </a>
                                            <?php } ?>
                                            <input name="data_inicio" <?php if($status == 'ATIVO'){ ?> readonly="true" <?php } ?> required="true" value="<?php echo $projeto->dt_inicio; ?>" class="form-control" type="date" >
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <?= lang("Data Término *", "data_termino"); ?>
                                             <?php if($status == 'ATIVO'){ ?>
                                            <a  title="Nova Categoria" class="btn btn-warning pull-right" href="<?= site_url('project/novoCadastroBasico/' . 96 . "/" . 28); ?>" data-toggle="modal" data-target="#myModal">  
                                                <i class="fa fa-edit"></i> 
                                            </a>
                                            <?php } ?>
                                            <input name="data_termino" <?php if($status == 'ATIVO'){ ?> readonly="true" <?php } ?> required="true" value="<?php echo $projeto->dt_final; ?>" class="form-control" type="date" >
                                        </div>
                                    </div>
                                    <!-- GERENTE -->
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <?= lang("Gerente do Projeto", "gerente"); ?> <i title="É o responsável por conduzir o projeto e alcançar seus objetivos." class="fa fa-info-circle"> </i> <small> </small>
                                            <?php
                                            $wu4[''] = '';
                                            foreach ($users as $user) {
                                                $wu4[$user->id] = $user->nome . ' ' . $user->last . ' - ' . $user->setor;
                                            }
                                            //  echo form_dropdown('responsavel[]', $wu4, (isset($_POST['responsavel']) ? $_POST['responsavel'] : ""), 'id="slResponsavel"  class="form-control  select" data-placeholder="' . lang("Selecione o Responsável") . ' "  style="width:100%;" multiple  required="required"');
                                            echo form_dropdown('gerente', $wu4, (isset($_POST['gerente']) ? $_POST['gerente'] : $projeto->gerente_area), 'id="gerente" required="true"  class="form-control  select" data-placeholder="' . lang("Selecione o Gerente") . ' "   style="width:100%;" ');
                                            ?>
                                        </div>
                                    </div>
                                    <!-- COORDENADOR -->
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <?= lang("Coordenador do Projeto", "coordenador"); ?> <i title="No SigPlus, o coodenador do projeto gerencia as ações, recebe notificações, avalia e retorna as ações. O Gerente também pode fazer o papel do coordenador do projeto." class="fa fa-info-circle"> </i> <small> </small>
                                            <?php
                                            $wu42[''] = '';
                                            foreach ($users as $user) {
                                                $wu42[$user->id] = $user->nome . ' ' . $user->last . ' - ' . $user->setor;
                                            }
                                            //  echo form_dropdown('responsavel[]', $wu4, (isset($_POST['responsavel']) ? $_POST['responsavel'] : ""), 'id="slResponsavel"  class="form-control  select" data-placeholder="' . lang("Selecione o Responsável") . ' "  style="width:100%;" multiple  required="required"');
                                            echo form_dropdown('coordenador', $wu42, (isset($_POST['coordenador']) ? $_POST['coordenador'] : $projeto->edp_id), 'id="coordenador"   class="form-control  select" data-placeholder="' . lang("Selecione o Coordenador") . ' "   style="width:100%;"   ');
                                            ?>
                                        </div>
                                    </div>

                                </div>
                                <div class="col-md-12">

                                    <div class="col-md-6">
                                        <!-- JUSTIFICATIVA -->  

                                        <div  class="form-group">
                                            <?= lang("Justificativa do Projeto ", "historico"); ?><small> </small>
                                            <?php echo form_textarea('justificativa', (isset($_POST['justificativa']) ? $_POST['justificativa'] : $projeto->justificativa), 'class="form-control  input-tip "    id="justificativa"  '); ?>
                                        </div>

                                        <!-- OBJETIVO -->  
                                        <div  class="form-group">
                                            <?= lang("Objetivos do Projeto ", "objetivo"); ?><small> </small>
                                            <?php echo form_textarea('objetivo', (isset($_POST['objetivo']) ? $_POST['objetivo'] : $projeto->objetivo), 'class="form-control"   id="objetivo"  '); ?>
                                        </div>
                                        <!-- DESCRIÇÃO -->  
                                        <div class="form-group">
                                            <?= lang("Descrição do Projeto", "descricao"); ?><small> </small>
                                            <?php echo form_textarea('descricao', (isset($_POST['descricao']) ? $_POST['descricao'] : $projeto->descricao), 'class="form-control"   id="descricao"  '); ?>
                                        </div>

                                    </div>

                                    <div class="col-md-6">
                                        <!-- PREMISSAS -->
                                        <div class="form-group">
                                            <?= lang("Premissas", "Premissas"); ?><small> </small>
                                            <?php echo form_textarea('premissas', (isset($_POST['premissas']) ? $_POST['premissas'] : $projeto->premissas), 'class="form-control"   id="Premissas"  '); ?>
                                        </div>
                                        <!-- PORQUE -->  
                                        <div class="form-group">
                                            <?= lang("Restrições", "restricoes"); ?><small> </small>
                                            <?php echo form_textarea('restricoes', (isset($_POST['restricoes']) ? $_POST['restricoes'] : $projeto->restricoes), 'class="form-control"   id="restricoes"  '); ?>
                                        </div>
                                        <!-- COMO -->  
                                        <div class="form-group">
                                            <?= lang("Benefícios", "beneficios"); ?><small></small>
                                            <?php echo form_textarea('beneficios', (isset($_POST['beneficios']) ? $_POST['beneficios'] : $projeto->beneficios), 'class="form-control"  id="beneficios"  '); ?>
                                        </div>

                                    </div>

                                </div>    

                                <center>
                                    <div class="col-md-12">
                                        <?php echo form_submit('add_item', lang("Atualizar"), 'id="add_item" class="btn btn-success" style="padding: 6px 15px; margin:15px 0;" onclick="alertas();" '); ?>
                                        <a  class="btn btn-danger"   href="<?= site_url('project/index/'); ?>"><?= lang('Sair') ?></a>
                                    </div>
                                </center>
                                <?php echo form_close(); ?>
                            </div>  
                        </div>
                         <!-- EQUIPE DO PROJETO -->
                        <div class="<?php if($projeto->aba == 2){ ?> active <?php } ?> tab-pane" id="equipe">
                            <div class="row">
                            <?php
                            $attrib = array('data-toggle' => 'validator', 'role' => 'form');
                            echo form_open_multipart("project/adicionarEquipeProjeto", $attrib);
                            echo form_hidden('idprojeto', $projeto->id);
                            
                            ?>
                               <div class="col-md-12">
                                    <div class="form-group">
                                        <center>
                                            <h2> EQUIPE DO PROJETO   </h2>
                                        </center>
                                    </div>
                                </div>
                                
                                <div class="col-md-12">
                                  <div class="box box-primary collapsed-box box-solid">
                                    <div class="box-header with-border">
                                      <h3 class="box-title text-bold">Cadastrar equipe</h3>

                                      <div class="box-tools pull-right">
                                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                                        </button>
                                      </div>
                                      <!-- /.box-tools -->
                                    </div>
                                    <!-- /.box-header -->
                                    <div class="box-body">
                                    <div class="col-sm-12">    
                                     <div class="col-sm-6">
                                        <div class="form-group">
                                            <?= lang("Função *", "funcao"); ?> <i title="Descrição do Marco. " class="fa fa-info-circle"> </i> <small> </small>
                                            <input type="text" placeholder="Ex.: Ger. de Projeto, Líder de projeto, Analista, Programador, Testador, ..." name="funcao" required="true"  class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <?= lang("Usuário Responsável", "usuario_responsavel_equipe"); ?> <i title="É o responsável pela função/cargo informado." class="fa fa-info-circle"> </i> <small> </small>
                                            <?php
                                            $wu4_equipe[''] = '';
                                            foreach ($users as $user) {
                                                $wu4_equipe[$user->id_user] = $user->nome . ' ' . $user->last . ' - ' . $user->setor;
                                            }
                                            //  echo form_dropdown('responsavel[]', $wu4, (isset($_POST['responsavel']) ? $_POST['responsavel'] : ""), 'id="slResponsavel"  class="form-control  select" data-placeholder="' . lang("Selecione o Responsável") . ' "  style="width:100%;" multiple  required="required"');
                                            echo form_dropdown('usuario_responsavel_equipe', $wu4_equipe, (isset($_POST['usuario_equipe']) ? $_POST['usuario_equipe'] : ""), 'id="usuario_responsavel_equipe" required="true"  class="form-control  select" data-placeholder="' . lang("Selecione o Responsável") . ' "   style="width:100%;" ');
                                            ?>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <?= lang("Papéis e Responsabilidades", "papel_responsabilidade"); ?> <i title="Descrição dos papéis/ Responsabilidades que serão executados pelo usuário. " class="fa fa-info-circle"> </i> <small> </small>
                                                 <?php echo form_textarea('papel_responsabilidade', (isset($_POST['papel_responsabilidade']) ? $_POST['papel_responsabilidade'] : ""), 'class="form-control"   id="papel_responsabilidade"  '); ?>
                                      
                                        </div>
                                    </div>
                                    <center>
                                        <div class="col-md-12">
                                            <?php echo form_submit('add_equipe', lang("Adicionar Equipe"), 'id="add_item" class="btn btn-primary " style="padding: 6px 15px; margin:15px 0;"  " '); ?>
                                            <?php echo form_close(); ?>
                                        </div>
                                    </center>
                                        
                                        </div>
                                    </div>
                                    <!-- /.box-body -->
                                  </div>
                                  <!-- /.box -->
                                </div>
                                <div class="col-sm-12">
                                        <div class="portlet-body">
                                            <table id="equipe_projeto" class="table table-striped sorting_asc_disabled table-bordered table-hover table-green">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 5%;"><font style="font-size: 10px;">ID</font></th>
                                                        <th style="width: 20%; font-size: 10px;">RESPONSÁVEL</th>
                                                        <th style=" width: 20%; font-size: 10px;">FUNÇÃO</th>
                                                        <th style="width: 10%;"><font style="font-size: 10px;">PAPEL/RESPONSABILIDADE</font></th>
                                                        <th style="width: 10%;"><font style="font-size: 10px;">EXCLUIR</font></th>
                                                       
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $wu4[''] = '';
                                                    $cont_equipe = 1;
                                                     foreach ($equipes as $equipe) {
                                                        
                                                      ?>
                                                      <tr class="odd gradeX">
                                                          <td style="width: 5%;"><?php echo $cont_equipe++; ?></td>
                                                          <td style="width: 20%; font-size: 14px;"><?php echo $equipe->first_name; ?></td>
                                                           <td style="width: 20%;  font-size: 14px;"><?php echo $equipe->funcao; ?></td>
                                                           <td style="width: 30%;  font-size: 14px;"><?php echo $equipe->descricao; ?></td>
                                                          <td class="center" style="width: 10%; ">
                                                          <a style="color: #ffffff;"  title="Remover Equipe do Projeto." href="<?= site_url('project/removerEquipeProjeto/' . $equipe->id_equipe); ?>" class="btn btn-danger"><i class="fa fa-trash-o"></i> </a>
                                                          </td>
                                                      </tr>
                                                      <?php
                                                      }
                                                    ?>




                                                </tbody>
                                            </table>

                                        </div> 
                                    </div>
                            </div> 
                        </div>
                        <!-- MARCOS DO PROJETO -->
                         <div class="<?php if($projeto->aba == 3){ ?> active <?php } ?> tab-pane" id="marcos">
                            <div class="row">
                             
                             <div class="col-md-12">
                                    <div class="form-group">
                                        <center>
                                            <h2> PRINCIPAIS MARCOS PROJETO <i class="fa fa-info-circle" title="Datas Importantes para o projeto"></i>  </h2>
                                        </center>
                                    </div>
                                </div>
                                
                             <div class="col-md-12">
                              <div class="box box-primary collapsed-box box-solid">
                                <div class="box-header with-border">
                                  <h3 class="box-title text-bold ">Cadastrar Marco do projeto</h3>

                                  <div class="box-tools pull-right">
                                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                                    </button>
                                  </div>
                                  <!-- /.box-tools -->
                                </div>
                                <!-- /.box-header -->
                                <div class="box-body">
                                   <?php
                            $attrib = array('data-toggle' => 'validator', 'role' => 'form');
                            echo form_open_multipart("project/adicionarMArcoProjeto", $attrib);
                            echo form_hidden('idprojeto', $projeto->id);
                            
                            ?>
                                <div class="col-sm-12">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <?= lang("Descrição", "descricao"); ?> <i title="Descrição do Marco. Ex: Abertura do Projeto, Datas de Viradas, Datas de Interrupções, etc. " class="fa fa-info-circle"> </i> <small> </small>
                                            <input type="text" name="descricao" required="true"  class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <?= lang("Data Prevista", "usuario_acesso"); ?> <i  class="fa fa-info-circle"> </i> <small> </small>
                                            <input type="date" name="data_prevista" required="true"  class="form-control">
                                        </div>
                                    </div>
                                    <center>
                                        <div class="col-md-12">
                                            <?php echo form_submit('add_marco', lang("Adcionar Marco"), 'id="add_item" class="btn btn-primary " style="padding: 6px 15px; margin:15px 0;"  " '); ?>
                                            <?php echo form_close(); ?>
                                        </div>
                                    </center>
                                </div>
                                <!-- /.box-body -->
                              </div>
                              <!-- /.box -->
                            </div>   
                                    <div class="portlet-body">
                                            <table id="marcos_projeto" class="table table-striped sorting_asc_disabled table-bordered table-hover table-green">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 5%;"><font style="font-size: 10px;">ID</font></th>
                                                        <th><font style="font-size: 10px;">DESCRIÇÃO</th>
                                                        <th style="width: 15%;"><font style="font-size: 10px;">DATA PREVISTA</th>
                                                        <th style="width: 10%;"><font style="font-size: 10px;">EXCLUIR</th>
                                                       
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $wu4[''] = '';
                                                    $cont_marco = 1;
                                                     foreach ($marcos as $marco) {
                                                         $criador = $acesso->criador;
                                                      ?>
                                                      <tr class="odd gradeX">
                                                          <td><?php echo $cont_marco++; ?></td>
                                                          <td><?php echo $marco->descricao; ?></td>
                                                          <td><?php echo date('d/m/Y', strtotime($marco->data_prevista)); ?></td>
                                                          <td class="center">
                                                          <a style="color: #ffffff;"  href="<?= site_url('project/removerMarcoProjeto/' . $marco->id); ?>" class="btn btn-danger"><i class="fa fa-trash-o"></i> </a>
                                                          </td>
                                                      </tr>
                                                      <?php
                                                      }
                                                    ?>




                                                </tbody>
                                            </table>

                                        </div> 
                                   
                                </div>
                            </div>  
                        </div>
                        <!-- ARQUIVOS DO PROJETO -->
                        <div class="<?php if($projeto->aba == 4){ ?> active <?php } ?> tab-pane" id="arquivos">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <center>
                                            <h2> ARQUIVOS DO PROJETO   </h2>
                                        </center>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                  <div class="box box-primary collapsed-box box-solid">
                                    <div class="box-header with-border">
                                      <h3 class="box-title text-bold">Cadastrar Arquivo</h3>

                                      <div class="box-tools pull-right">
                                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                                        </button>
                                      </div>
                                      <!-- /.box-tools -->
                                    </div>
                                    <!-- /.box-header -->
                                    <div class="box-body">
                                       <?php
                                        $attrib = array('data-toggle' => 'validator', 'role' => 'form');
                                        echo form_open_multipart("project/adiciona_arquivos_projetos", $attrib);
                                         echo form_hidden('idprojeto', $projeto->id);
                                        ?>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                        <?= lang("Descrição", "descricao"); ?>
                                        <?php echo form_input('descricao_arquivo', (isset($_POST['descricao']) ? $_POST['descricao'] : ""), 'class="form-control input" required="true"  maxlength="250"   id="descricao"  '); ?>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <?= lang("Arquivo", "descricao"); ?>
                                                <input id="document" type="file" data-browse-label="<?= lang('browse'); ?>" name="document" value="<?php echo $projeto->anexo; ?>" data-show-upload="false"
                                                       data-show-preview="false" class="form-control file">
                                            </div>
                                        </div>
                                        <center>
                                            <div class="col-md-12">
                                    <?php echo form_submit('add_arquivo', lang("Adcionar Arquivo"), 'id="add_item" class="btn btn-primary " style="padding: 6px 15px; margin:15px 0;"  " '); ?>
                                    <?php echo form_close(); ?>
                                            </div>
                                        </center>
                                    </div>
                                    <!-- /.box-body -->
                                  </div>
                                  <!-- /.box -->
                                </div>
                                <div class="col-md-12">
                                <div class="portlet-body">
                                <table id="arquivos_projeto" class="table table-striped sorting_asc_disabled table-bordered table-hover table-green">
                                    <thead>
                                        <tr>
                                            <th><font style="font-size: 10px;">ID</font></th>
                                            <th><font style="font-size: 10px;">DESCRIÇÃO</th>
                                            <th><font style="font-size: 10px;">ARQUIVO</th>
                                            <th><font style="font-size: 10px;">DOWNLOAD</th>
                                            <th><font style="font-size: 10px;">EXCLUIR</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $cont_arquivo = 1;
                                    foreach ($arquivos as $arquivo) {
                                        ?>   
                                            <tr class="odd gradeX">
                                                <td><font style="font-size: 12px;"><?php echo $cont_arquivo++; ?></font></td>
                                                <td><font style="font-size: 12px;"><?php echo $arquivo->descricao; ?></font></td>
                                                <td><font style="font-size: 12px;"><?php echo $arquivo->anexo; ?></font></td>
                                                <td><font style="font-size: 12px;"><a target="_blank" href="assets/uploads/projetos/arquivos/<?php echo $arquivo->anexo; ?>" class="tip btn btn-file" title="<?= lang('Arquivo em Anexo') ?>"><i class="fa fa-download"></i></a></font></td>  
                                                <td class="center">
                                                 <a style="color: #ffffff;"  title="Remover o Arquivo do Projeto." href="<?= site_url('project/remove_arquivo_projeto/' . $arquivo->id); ?>" class="btn btn-danger"><i class="fa fa-trash-o"></i> </a>
                                                         </td>
                                            </tr>
                                        <?php
                                    }

                                    ?>




                                    </tbody>
                                </table>
                                </div>     
                                </div> 
                               
                            </div>   
                        </div>  
                        <!-- PARTES INTERESSADAS DO PROJETO -->
                        <div class="<?php if($projeto->aba == 5){ ?> active <?php } ?> tab-pane" id="partes_interessadas">
                            <div class="row">
                            <div class="col-md-12">
                                    <div class="form-group">
                                        <center>
                                            <h2> REGISTRO DAS PARTES INTERESSADAS   </h2>
                                        </center>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                  <div class="box box-primary collapsed-box box-solid">
                                    <div class="box-header with-border">
                                      <h3 class="box-title text-bold">Cadastrar Parte Interessada</h3>

                                      <div class="box-tools pull-right">
                                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                                        </button>
                                      </div>
                                      <!-- /.box-tools -->
                                    </div>
                                    <!-- /.box-header -->
                                    <div class="box-body">
                                      <?php
                            $attrib = array('data-toggle' => 'validator', 'role' => 'form');
                            echo form_open_multipart("project/adicionarParteInteressadaProjeto", $attrib);
                            echo form_hidden('idprojeto', $projeto->id);
                            
                            ?>
                               
                                <div class="col-sm-12">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <?= lang("Parte Interessada", "usuario_equipe"); ?> <i title="É preciso estar cadastrado no sistema." class="fa fa-info-circle"> </i> <small> </small>
                                            <?php
                                            $wu4_pi[''] = '';
                                            foreach ($users as $user) {
                                                $wu4_pi[$user->id_user] = $user->nome . ' ' . $user->last . ' - ' . $user->setor;
                                            }
                                            //  echo form_dropdown('responsavel[]', $wu4, (isset($_POST['responsavel']) ? $_POST['responsavel'] : ""), 'id="slResponsavel"  class="form-control  select" data-placeholder="' . lang("Selecione o Responsável") . ' "  style="width:100%;" multiple  required="required"');
                                            echo form_dropdown('usuario_interessado', $wu4_pi, (isset($_POST['usuario_interessado']) ? $_POST['usuario_interessado'] : ""), 'id="usuario_equipe" required="true"  class="form-control  select" data-placeholder="' . lang("Selecione o Responsável") . ' "   style="width:100%;" ');
                                            ?>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <?= lang("Grupo", "descricao"); ?> <small> </small>
                                            <input type="text" placeholder="Ex.: Cliente, Equipe, Gerente, Diretoria,..." name="descricao" required="true"  class="form-control">
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <?= lang("Nível de Engajamento", "tipo"); ?> <i title="Apoiador - Suporta o projeto; Neutro - Tem conhecimento sobre o projeto, porém, está neutro; Resistente - Se tiver oportunidade, prejudicará o andamento do projeto; Desinformado - Não tem informação sobre o projeto, por isso, não tem posição formada; Lidera - Engajado em garantir o sucesso do projeto; " class="fa fa-info-circle"> </i>
                                            <?php $pst[''] = '';
                                              $pst['APOIADOR'] = lang('APOIADOR');
                                              $pst['NEUTRO'] = lang('NEUTRO');
                                              $pst['RESISTENTE'] = lang('RESISTENTE');
                                              $pst['DESINFORMADO'] = lang('DESINFORMADO');
                                              $pst['LIDERA'] = lang('LIDERA');
                                              
                                               echo form_dropdown('tipo_engajamento', $pst, (isset($_POST['tipo']) ? $_POST['tipo'] : $ata->tipo), 'id="tipo"  class="form-control " data-placeholder="' . lang("select") . ' ' . lang("o Nível de Engajamento") . '" required="required"   style="width:100%;" ');
                                            ?> 

                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <?= lang("Enivar Status Report automaticamente?", "status_report"); ?> <i title="Se a opção foir sim, o sistema irá enviar semanalmente um status report do projeto." class="fa fa-info-circle"> </i>
                                            <?php $pst2[''] = '';
                                              $pst2['SIM'] = lang('SIM');
                                              $pst2['NÃO'] = lang('NÃO');
                                              
                                              
                                               echo form_dropdown('status_report', $pst2, (isset($_POST['status_report']) ? $_POST['status_report'] : ""), 'id="status_report"  class="form-control " data-placeholder="' . lang("Selecione") . ' ' . lang("uma opção") . '" required="required"   style="width:100%;" ');
                                            ?> 

                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <?= lang("Estratégias e Comentários", "estrategia_observacao"); ?> <i title="Estratégias para ganhar mais suporte ou reduzir resistências. Avaliação do impacto e Comentários" class="fa fa-info-circle"> </i> <small> </small>
                                                 <?php echo form_textarea('estrategia_observacao', (isset($_POST['estrategia_observacao']) ? $_POST['estrategia_observacao'] : ""), 'class="form-control"   id="estrategia_observacao"  '); ?>
                                      
                                        </div>
                                    </div>
                                    <center>
                                        <div class="col-md-12">
                                            <?php echo form_submit('add_parte_interessada', lang("Adcionar Parte Interessada"), 'id="add_parte_interessada" class="btn btn-primary " style="padding: 6px 15px; margin:15px 0;"  " '); ?>
                                            <?php echo form_close(); ?>
                                        </div>
                                    </center>
                                    </div>
                                    <!-- /.box-body -->
                                  </div>
                                  <!-- /.box -->
                                </div>
                                
                           
                             
                                            <?php
                                            $wu4[''] = '';
                                            $cont_parte = 1;
                                             foreach ($partes as $parte) {
                                                  $dados_user_pi = $this->site->getUser($parte->usuario_interessado);  
                                                  $tipo_pi = $parte->tipo_engajamento;
                                                  
                                                  if($tipo_pi == 'LIDERA'){
                                                      $desc_tipo = "success";
                                                  }else if($tipo_pi == 'NEUTRO'){
                                                      $desc_tipo = "default";
                                                  }else if($tipo_pi == 'RESISTENTE'){
                                                      $desc_tipo = "danger";
                                                  }else if($tipo_pi == 'APOIADOR'){
                                                       $desc_tipo = "primary";
                                                  }else if($tipo_pi == 'DESINFORMADO'){
                                                      $desc_tipo = "warning";
                                                  }
                                              ?>
                                            <div class="col-md-12">
                                                <div class="box-tools pull-right" data-toggle="tooltip" title="Status">
                                                <div class="btn-group" data-toggle="btn-toggle">
                                                 
                                                  <a href="<?= site_url('project/removerParteInteressadaProjeto/' . $parte->id); ?>" class="btn btn-danger btn-sm active"><i class="fa fa-trash "></i></a>
                                                </div>
                                              </div>
                                              <!-- Box Comment -->
                                              <div class="box box-widget">
                                                <div class="box-header with-border">
                                                  <div class="user-block">
                                                    <img src="<?= $dados_user_pi->avatar ? $assets . '../../../assets/uploads/avatars/thumbs/' . $dados_user_pi->avatar : $assets . 'images/' . $this->session->userdata('gender') . '.png'; ?>" class="img-circle" alt="User Image">
                                                    <span class="username"><a href="#"><?php echo $dados_user_pi->first_name; ?></a></span>
                                                    <span class="description"><?php echo $parte->descricao; ?> - <?php echo 'Status Report : '. $parte->status_report; ?></span>
                                                  </div>
                                                 
                                                </div>
                                                <!-- /.box-header -->
                                                <div class="box-body">
                                                  <font   class="label label-<?php echo $desc_tipo; ?>" style="font-size: 12px; font-weight: bold"><?php echo $parte->tipo_engajamento; ?>  </font>
                                                  <br>
                                                  <p style="font-size: 12px; "><?php echo strip_tags($parte->estrategia_observacao); ?> </p>

                                                </div>
                                                   


                                              </div>
                                              <!-- /.box -->
                                            </div>

                                              <?php
                                              }
                                            ?>

                                </div>
                            </div> 
                        </div>
                        <!-- ACESSO AO PROJETO -->
                        <div class="<?php if($projeto->aba == 6){ ?> active <?php } ?> tab-pane" id="acesso">
                            <div class="row">
                             <div class="col-md-12">
                                    <div class="form-group">
                                        <center>
                                            <h2> PESSOAS QUE TEM AUTORIZAÇÃO PARA ACESSAR O PROJETO   </h2>
                                        </center>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                  <div class="box box-primary collapsed-box box-solid">
                                    <div class="box-header with-border">
                                      <h3 class="box-title text-bold">Cadastrar nova autorização de acesso</h3>

                                      <div class="box-tools pull-right">
                                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                                        </button>
                                      </div>
                                      <!-- /.box-tools -->
                                    </div>
                                    <!-- /.box-header -->
                                    <div class="box-body">
                                    <?php
                                    $attrib = array('data-toggle' => 'validator', 'role' => 'form');
                                    echo form_open_multipart("project/adicionarAcessoProjeto", $attrib);
                                    echo form_hidden('idprojeto', $projeto->id);
                                    ?>
                                    <div class="col-sm-12">
                                        <div class="col-sm-12">

                                            <div class="form-group">
                                                <?= lang("Usuário", "usuario_acesso"); ?> <i title="Este usuário passará a ter acesso ao Projeto." class="fa fa-info-circle"> </i> <small> </small>
                                                <?php
                                                $wu4_acesso[''] = '';
                                                foreach ($users as $user) {
                                                    $wu4_acesso[$user->id_user] = $user->nome . ' ' . $user->last . ' - ' . $user->setor;
                                                }
                                                //  echo form_dropdown('responsavel[]', $wu4, (isset($_POST['responsavel']) ? $_POST['responsavel'] : ""), 'id="slResponsavel"  class="form-control  select" data-placeholder="' . lang("Selecione o Responsável") . ' "  style="width:100%;" multiple  required="required"');
                                                echo form_dropdown('usuario_acesso', $wu4_acesso, (isset($_POST['usuario_acesso']) ? $_POST['usuario_acesso'] : ""), 'id="usuario_acesso" required="true"  class="form-control  select" data-placeholder="' . lang("Selecione o Usuário") . ' "   style="width:100%;" ');
                                                ?>
                                            </div>
                                        </div>
                                        <center>
                                            <div class="col-md-12">
                                                <?php echo form_submit('add_acesso', lang("Adcionar Usuário"), 'id="add_item" class="btn btn-primary " style="padding: 6px 15px; margin:15px 0;"  " '); ?>
                                                <?php echo form_close(); ?>
                                            </div>
                                        </center>
                                    </div>
                                    <!-- /.box-body -->
                                  </div>
                                  <!-- /.box -->
                                </div>
                                  <div class="portlet-body">
                                        <table id="acesso_projeto" class="table table-striped sorting_asc_disabled table-bordered table-hover table-green">
                                            <thead>
                                                <tr>
                                                    <th style="width: 5%;"><font style="font-size: 10px;">ID</font></th>
                                                    <th><font style="font-size: 10px;">USUÁRIO</th>
                                                    <th style="width: 10%;"><font style="font-size: 10px;">PERMISSÕES</th>
                                                    <th style="width: 10%;"><font style="font-size: 10px;">EXCLUIR</th>

                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $wu4[''] = '';
                                                $cont_acesso = 1;
                                                 foreach ($acessos as $acesso) {
                                                     $criador = $acesso->criador;
                                                  ?>
                                                  <tr class="odd gradeX">
                                                      <td><?php echo $cont_acesso++; ?></td>
                                                      <td><?php echo $acesso->first_name; ?></td>
                                                      <td><a class="btn btn-primary "  href="assets/uploads/planos/arquivos/<?php echo $arquivo->anexo; ?>"  title="<?= lang('Arquivo em Anexo') ?>">  <i class="fa fa-key"></i></a></td>
                                                      <td class="center">
                                                      <?php if($criador == 1){ ?>
                                                      <a  title="O Criador do Projeto não pode remover o acesso." disabled="true" readonly="true" class="btn btn-danger"><i class="fa fa-trash-o"></i> </a>
                                                      <?php } else if($criador == 0){ ?> 
                                                      <a style="color: #ffffff;"  title="Remover o acesso ao projeto." href="<?= site_url('project/removerAcessoProjeto/' . $acesso->id_cadastro); ?>" class="btn btn-danger"><i class="fa fa-trash-o"></i> </a>
                                                      <?php } ?>     
                                                      </td>
                                                  </tr>
                                                  <?php
                                                  }
                                                ?>




                                            </tbody>
                                        </table>
                                    </div> 
                                </div>
                            </div>   
                        </div> 
                      
                           <!-- HISTORICO DO PROJETO -->                   
                        <div class="<?php if($projeto->aba == 7){ ?> active <?php } ?> tab-pane" id="historico">
                             <div class="row">
                               
                               
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <center>
                                            <h2> REGISTRO DE HISTÓRICOS DO PROJETO  <i title="O Registro de Histórico, centraliza os principais registros de acontecimentos que ocorrem durante o projeto, e que precisam ser registrados. Ex: Alteração de Escopo, Alteração de Cronograma. " class="fa fa-info-circle"> </i>  </h2>
                                        </center>
                                    </div>
                                </div>
                                 
                                <div class="col-md-12">
                                  <div class="box box-primary collapsed-box box-solid">
                                    <div class="box-header with-border">
                                      <h3 class="box-title text-bold">Cadastrar novo Registro</h3>

                                      <div class="box-tools pull-right">
                                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                                        </button>
                                      </div>
                                      <!-- /.box-tools -->
                                    </div>
                                    <!-- /.box-header -->
                                    <div class="box-body">
                                        <?php
                                        $attrib = array('data-toggle' => 'validator', 'role' => 'form');
                                        echo form_open_multipart("project/adiciona_historico_projetos", $attrib);
                                        echo form_hidden('idprojeto', $projeto->id);
                                        ?>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <?= lang("Assunto", "titulo"); ?> <i title="Ex: Mudança de Escopo, Alteração no cronograma, Mudança de Equipe,... " class="fa fa-info-circle"> </i> <small> </small>
                                                <input type="text" placeholder="" name="titulo" maxlength="250" required="true"  class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <?= lang("Descrição *", "observacao_historico"); ?> <i title="Descrição mais detalhadas do registro. " class="fa fa-info-circle"> </i> <small> </small>
                                                <?php echo form_textarea('observacao_historico', (isset($_POST['observacao_historico']) ? $_POST['observacao_historico'] : ""), 'class="form-control"   id="observacao_historico"  '); ?>

                                            </div>
                                        </div>


                                        <center>
                                            <div class="col-md-12">
                                                <?php echo form_submit('add_observacao_historico', lang("Adcionar Registro"), 'id="add_item" class="btn btn-primary " style="padding: 6px 15px; margin:15px 0;"  " '); ?>
                                                <?php echo form_close(); ?>
                                            </div>
                                        </center>
                                    </div>
                                    <!-- /.box-body -->
                                  </div>
                                  <!-- /.box -->
                                </div> 
                                <div class="form-group">
                                        <div id="conteudo">
                                            <br>
                                             <?php
                                                    $cont_obs = 1 ;
                                                     foreach ($historicos as $observacao) {

                                                        $titulo = $observacao->titulo;
                                                        $historico = $observacao->historico;
                                                         
                                                        $dados_user = $this->site->getUser($observacao->usuario);  
                                                    ?>
                                                     <div class="col-md-12">
                                                      <!-- Box Comment -->
                                                      <div class="box box-widget">
                                                        <div class="box-header with-border">
                                                          <div class="user-block">
                                                            <img src="<?= $dados_user->avatar ? $assets . '../../../assets/uploads/avatars/thumbs/' . $dados_user->avatar : $assets . 'images/' . $this->session->userdata('gender') . '.png'; ?>" class="img-circle" alt="User Image">
                                                            <span class="username"><a href="#"><?php echo $dados_user->first_name; ?></a></span>
                                                            <span class="description"><?php echo date('d/m/Y H:i:s', strtotime( $observacao->data_envio)); ?></span>
                                                          </div>
                                                          
                                                        </div>
                                                        <!-- /.box-header -->
                                                        <div class="box-body">
                                                          <font   class="label label-success" style="font-size: 12px; font-weight: bold"><?php echo $observacao->titulo; ?>  </font>
                                                       
                                                          <br>
                                                          <p style="font-size: 12px; "><?php echo strip_tags($observacao->historico); ?> </p>
                                                        
                                                        </div>


                                                      </div>
                                                      <!-- /.box -->
                                                    </div>
                                                    
                                                    
                                                 <?php 
                                                     }
                                                   ?>   
                                        </div>              
                                    </div>
                                <br><br>
                            </div>
                            <br>          
                        </div>
                        <!-- LOG DO PROJETO -->
                         <div class="<?php if($projeto->aba == 8){ ?> active <?php } ?> tab-pane" id="log">
                            <div class="row">
                             
                             <div class="col-md-12">
                                    <div class="form-group">
                                        <center>
                                            <h2> LOGS PROJETO   </h2>
                                        </center>
                                    </div>
                                </div>
                                
                             <div class="col-md-12">
                              
                                    <div class="portlet-body">
                                            <table style=" font-size: 12px;" id="log_projeto" class="table table-striped sorting_asc_disabled table-bordered table-hover table-green">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 5%;">ID</th>
                                                        <th style="width: 15%;">QUANDO</th>
                                                        <th style="width: 15%;">QUEM</th>
                                                        <th style="width: 35%;">AÇÃO</th>
                                                        <th style="width: 15%;">ANTES</th>
                                                        <th style="width: 15%;">DEPOIS</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $wu4[''] = '';
                                                    $cont_log = 1;
                                                     foreach ($logs as $log) {
                                                        $dados_user = $this->site->getUser($log->usuario);
                                                            $nome = $dados_user->first_name;
                                                      ?>
                                                      <tr  class="odd gradeX">
                                                            <td style="width: 5%;" class="center"><?php echo $cont_log++; ?></td>
                                                            <td style="width: 15%;" class="center"><?php echo date('d/m/Y H:i:s', strtotime($log->data_registro)); ?></td>
                                                            <td style="width: 15%;" class="center"><?php echo $nome; ?></td>
                                                            <td style="width: 35%;" class="center"><?php echo $log->descricao; ?></td>
                                                            <td style="width: 15%;" class="center"><?php echo $log->antes; ?></td>
                                                            <td style="width: 15%;" class="center"><?php echo $log->depois; ?></td>

                                                            </tr>
                                                      <?php
                                                      }
                                                    ?>




                                                </tbody>
                                            </table>

                                        </div> 
                                   
                                </div>
                            </div>  
                        </div>   
                          
                    </div>
                </div>    
                                <br><br>
        </div>
        <br>
    </div>
    </div>
    </section>    
         <script>
  $(function () {
  $('#log_projeto').DataTable({
      "order": [[ 0, "desc" ]]
    })
  })  //acesso_projeto
  
  $(function () {
  $('#acesso_projeto').DataTable({
      "order": [[ 0, "desc" ]]
    })
  })  //acesso_projeto
  
  $(function () {
  $('#arquivos_projeto').DataTable({
      "order": [[ 0, "desc" ]]
    })
  })  //acesso_projeto
  
   $(function () {
  $('#marcos_projeto').DataTable({
      "order": [[ 0, "desc" ]]
    })
  })  //acesso_projeto
  
     $(function () {
  $('#equipe_projeto').DataTable({
      "order": [[ 0, "desc" ]]
    })
  })  //acesso_projeto
</script>

<script>
  $(function () {
    //Initialize Select2 Elements
    $('.select2').select2()

    //Datemask dd/mm/yyyy
    $('#datemask').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' })
    //Datemask2 mm/dd/yyyy
    $('#datemask2').inputmask('mm/dd/yyyy', { 'placeholder': 'mm/dd/yyyy' })
    //Money Euro
    $('[data-mask]').inputmask()

    //Date range picker
    //$('#reservation').daterangepicker();
    
     $(function() { $("#reservation").daterangepicker({
            locale: { format: 'DD/MM/YYYY' } ,  language: 'pt-BR',
            minDate: '<?php echo exibirData($evento->dt_inicio) ?>',
            maxDate: '<?php echo exibirData($evento->dt_fim) ?>'
        
        }); });
     

    //Date range picker with time picker
    $('#reservationtime').daterangepicker({ timePicker: true, timePickerIncrement: 30, format: 'DD/MM/YYYY' })
    
    
    //Date range as a button
    $('#daterange-btn').daterangepicker(
      {
        ranges   : {
          'Today'       : [moment(), moment()],
          'Yesterday'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
          'Last 7 Days' : [moment().subtract(6, 'days'), moment()],
          'Last 30 Days': [moment().subtract(29, 'days'), moment()],
          'This Month'  : [moment().startOf('month'), moment().endOf('month')],
          'Last Month'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
        startDate: moment().subtract(29, 'days'),
        endDate  : moment()
      },
      function (start, end) {
        $('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
      }
    )

    //Date picker
    $('#datepicker').datepicker({
      autoclose: true,
      format: 'dd/mm/yyyy',                
    language: 'pt-BR'
    })
    

    //iCheck for checkbox and radio inputs
    $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
      checkboxClass: 'icheckbox_minimal-blue',
      radioClass   : 'iradio_minimal-blue'
    })
    //Red color scheme for iCheck
    $('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
      checkboxClass: 'icheckbox_minimal-red',
      radioClass   : 'iradio_minimal-red'
    })
    //Flat red color scheme for iCheck
    $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
      checkboxClass: 'icheckbox_flat-green',
      radioClass   : 'iradio_flat-green'
    })

    //Colorpicker
    $('.my-colorpicker1').colorpicker()
    //color picker with addon
    $('.my-colorpicker2').colorpicker()

    //Timepicker
    $('.timepicker').timepicker({
      showInputs: false
    })
  })
</script>

