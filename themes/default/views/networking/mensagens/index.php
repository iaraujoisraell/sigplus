  <?php 
    $usuario =  $this->session->userdata('user_id'); 
   ?>
  <div class="col-lg-12">
    <div class="box">
     <section class="content-header">
                          <h1>
                            <?php echo 'Mensagens'; ?>
                            <small><?php echo 'Minhas Mensagens'; ?> </small>
                          </h1>
                          <ol class="breadcrumb">
                            <li><a href="<?= site_url('welcome'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
                            <li class="active">Mensagens</li>
                          </ol>
                        </section>
                    
                     <div class="box-header">
                    <span class="pull-right-container">
                       <div class=" clearfix no-border">
                           
                         
                        </div>
                    </span>
                </div>
        <br><br>
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
                           <?php if($acao_id){ 
                            $texto = 'Descrição da Ação';   
                            }else{
                             $texto = 'Observação';      
                            }
                            ?>
                              <br>
                            <div class="table-responsive">
                                <div class="box-body">
                                    <table id="convites" class="table table-bordered table-striped">
                                    <thead>
                                    <tr>
                                    <th style="width:10%;" ><?php echo $this->lang->line("Data Envio/ Remetente"); ?></th>
                                    <th style="width:15%;" ><?php echo $this->lang->line("Assunto"); ?></th>
                                    <th style="width:30%;" ><?php echo $this->lang->line("Mensagem"); ?></th>
                                    <th style="width:30%;" ><?php echo $texto; ?></th>
                                    <th style="width:5%;  text-align: center;" ><?php echo $this->lang->line("Mensagem Lida?"); ?></th>
                                    <th style="width:10%;"><?php echo $this->lang->line("Opções"); ?></th>
                                

                                </tr>
                                </thead>
                                    <tbody>
                                         <?php
                                            $wu4[''] = '';
                                            $cont_tar = 1;
                                            foreach ($mensagens as $mensagem) {

                                                $confirmacao = $mensagem->lida;
                                                if($confirmacao == 1){
                                                    $bt = "bg-green";
                                                    $bt_texto = "SIM";
                                                }else if($confirmacao == 0){
                                                    $bt = "bg-black";
                                                    $bt_texto = "NÃO";
                                                    $bt_new = "bg-green";
                                                }
                                                
                                                $convite = $mensagem->convite;
                                                $acao_id = $mensagem->idplano;
                                                
                                                $users_dados = $this->site->geUserByID($mensagem->id_from);
                                                
                                                $acao = $this->networking_model->getPlanoByIdAndUsuario($acao_id);
                                                
                                            ?>               
                                                <tr  >
                                                    <td style="width: 10%; text-align: center;">
                                                        <small ><?php echo date('d/m/Y H:m:i', strtotime($mensagem->data_envio)); ?></small><br>
                                                        <a href="<?= site_url('welcome/profile_visitor/'.$mensagem->id_from); ?>" title="Ver Perfil" ><small title="Remetente da mensagem" class="label bg-gray" ><?php echo  $users_dados->first_name; ?></small></a>
                                                    </td>
                                                    <td style="width: 15%;  font-size: 14px;">
                                                        <small class="label bg-blue"><?php echo $mensagem->title; ?></small> 
                                                            <?php if($confirmacao == 0){ ?> <small title="Você recebeu uma nova ação ou mensagem na ação" class="label <?php echo $bt_new; ?>">New</small> <?php } ?></td> 
                                                    <td style="width: 30%; text-align: center;"><small  ><?php echo $mensagem->text; ?></small></td>
                                                    <td style="width: 30%; text-align: center;"><small  ><?php echo $acao->descricao; ?></small></td>
                                                    <td style="width: 5%; text-align: center;"><small class="label pull-right <?php echo $bt; ?> " ><?php echo $bt_texto; ?></small></td> 
                                                    <?php if($acao_id){ ?>
                                                    <td style="width: 10%; text-align: center;"><a href="<?= site_url('welcome/dados_cadastrais_acao/'.$acao_id); ?>" title="Ver Ação" class="btn btn-warning" ><i class="fa fa-folder-open-o"></i></a></td> 
                                                    <?php }else{ ?>
                                                    <td style="width: 10%; text-align: center;"><small  >-</small></td> 
                                                    <?php } ?>

                                                      
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
  $('#convites').DataTable({
      "order": [[ 0, "desc" ]]
    })
  })
</script>        
 
 