  <?php
    function resume( $var, $limite ){	// Se o texto for maior que o limite, ele corta o texto e adiciona 3 pontinhos.	
              if (strlen($var) > $limite)	{
                    $var = substr($var, 0, $limite);		
                    $var = trim($var) . "...";	

              }return $var;

              }
          ?>

<?php
        $acao = $this->atas_model->getPlanoByID($idplano);
        $usuario = $this->session->userdata('user_id');
//$users = $this->site->geUserByID($acao->responsavel);                              
        ?>    
<?php
$projetos = $this->projetos_model->getProjetoAtualByID_completo();
$id_projeto = $projetos->id;
$nome_projeto = $projetos->nome_projeto;
?>


    
    <div class="col-lg-12">
    <div class="box">
    <section class="content-header">
          <h1>
            <?php echo 'Convites para a ATA '.$ata->sequencia; ?>
              
                  
          </h1>
          <ol class="breadcrumb">
            <li><a href="<?= site_url('welcome'); ?>"><i class="fa fa-home"></i> Minhas Atas</a></li>
            <li class="active">Enviar Convites</li>
          </ol>

        </section>
        <br>
    </div> 
    </div>
    
    <div class="row">  
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
    
    <section  class="content">
    <div class="row">    
       
    <?php
$ataAtual = $this->atas_model->getAtaByID($id);
 $statusAta = $ataAtual->status;
 
 
?>
    <div class="col-lg-12">
        <div class="box">
            <div  class="nav-tabs-custom">
                <ul style="background-color: #ddd; " class="nav nav-tabs">
                    <li class="active"><a href="#discussao"  data-toggle="tab"><b>Convites - Participantes <i class="fa fa-send-o"></i></b></a></li> 
                    
                   
                </ul>
                <div class="tab-content">
                    <div class="active tab-pane" id="discussao">
                        <div class="row">
                            
                            <div class="clearfix"></div>
                            <?php
                            $attrib = array('data-toggle' => 'validator', 'role' => 'form');
                            echo form_open_multipart("welcome/convite_ata", $attrib);
                            echo form_hidden('id', $id);
                            ?>
                            <div class="col-lg-12">
                           
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <?= lang("Data", "dateAta"); ?>
                                        <input name="dateAta" value="<?php echo $ata->data_ata; ?>" required="true" disabled="true" class="form-control" type="date" >

                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <?= lang("hora Início ", "data_termino"); ?>

                                        <input name="hora_inicio" value="<?php echo $ata->hora_inicio; ?>" disabled="true" required="true" class="form-control" type="time" >

                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <?= lang("hora Fim ", "data_termino"); ?>
                                        <input name="hora_fim" required="true" value="<?php echo $ata->hora_termino; ?>" disabled="true" class="form-control" type="time" >

                                    </div>
                                </div>    
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <?= lang("Local ", "sllocal"); ?>
                                        <?php echo form_input('local', (isset($_POST['local']) ? $_POST['local'] : $ata->local), 'maxlength="200" disabled class="form-control input-tip" required="required" id="sllocal"'); ?>
                                    </div>
                                </div>


                            </div>    
                            
                            <div class="col-md-12">
                             <div class="col-sm-12">
                                    <div class="form-group">
                                        <?= lang("Assunto", "assunto"); ?>
                                        <?php echo form_input('assunto', (isset($_POST['assunto']) ? $_POST['assunto'] : $ata->assunto), 'maxlength="200" disabled class="form-control input-tip" required="required" id="assunto"'); ?>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-12">
                                <!-- ITEM EVENTO -->
                                <div class="col-md-12">
                                    <div  class="form-group">
                                        <?php if ($statusAta == 1) { ?>
                                        <?= lang("Esta ATA esta finalizada. É possível ver somentes os convites enviados. ", "sldescricao"); ?>
                                        <?php } else { ?>
                                        <?= lang("Texto para o Convite ", "sldescricao"); ?>
                                        <textarea class="form-control  input-tip " name="descricao_texto" style="height: 120px;" id="sldescricao" required="true">
                                            <h3>Convite</h3>
                                            Prezado(a) <br>
                                            Venho através deste lhe convidar para a Reunião sobre: <b> <?php echo $ata->assunto; ?></b> <br>
                                            A ser realizado no dia : <b><?php echo date('d/m/Y', strtotime($ata->data_ata)); ?></b> <br>
                                            De : <b><?php echo $ata->hora_inicio; ?></b> as <b><?php echo $ata->hora_termino; ?></b> <br>
                                            Local : <b><?php echo $ata->local; ?></b> <br>
                                            <br><br> 
                                            Pauta : <br>
                                            <?php echo $ata->pauta; ?>
                                        </textarea  >
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                            
                             <div class="col-md-12">    
                                    <div class="col-md-12" >
                                        <div class="form-group">
                                            
                                            
                                            <?php if ($statusAta == 1) { ?>
                                                

                                            <?php
                                            } else {
                                             ?>
                                            <div class="col-md-12">
                                            <table width="100%">
                                                    <tr>
                                                        <td><?= lang("Usuário", "usuario"); ?> </td>
                                                        <td><?= lang("Selecionar", "slparticipantes"); ?> <i class="fa fa-info-circle" title="Pessoas que deverão receber o convite. "></i></td>
                                                        <td><?= lang("Participante", "participante"); ?> </td>
                                                        <td><?= lang("Vinculo", "vinculo"); ?> </td>
                                                    </tr>
                                                

                                              
                                                <?php
                                                $cont_part = 1;
                                                $participantes_cadastrados_ata = $this->atas_model->getAtaUserParticipante_ByID_ATA($id);
                                                foreach ($participantes_cadastrados_ata as $participante_cadastrados) {

                                                    if ($participante_cadastrados) {
                                                        $participante = $participante_cadastrados->participante;
                                                        $vinculo = $participante_cadastrados->vinculo;
                                                        $confirmacao = $participante_cadastrados->confirmacao;
                                                        
                                                        if ($confirmacao){
                                                        if($confirmacao == 3){
                                                            $bt = "primary";
                                                            $bt_texto = "convidado";
                                                        }else if($confirmacao == 1){
                                                            $bt = "success";
                                                            $bt_texto = "Respondido";
                                                        }else if($confirmacao == 0){
                                                            $bt = "default";
                                                            $bt_texto = "Respondido";
                                                        }else {
                                                            $bt = "primary";
                                                            $bt_texto = "";
                                                        }
                                                        }else{
                                                             $bt = "primary";
                                                            $bt_texto = "";
                                                        }
                                                        
                                                        if($participante == 1){
                                                            $fa = "fa-check-circle";
                                                        }else {
                                                            $fa = "fa-circle-o";
                                                        }
                                                        
                                                        if($vinculo == 1){
                                                            $fa_v = "fa-check-circle";
                                                        }else {
                                                            $fa_v = "fa-circle-o";
                                                        }
                                                        ?>

                                                       <tr>
                                                           <td width="40%"> <?php echo $cont_part++.' - ' . $participante_cadastrados->nome . ' - ' . $participante_cadastrados->setor; ?> <label class="label label-<?php echo $bt; ?>"><?php echo $bt_texto; ?></label> </td>
                                                                <td width="15%"><input type="checkbox" value="1" <?php echo $check; ?>  name="<?php echo $participante_cadastrados->id; ?>convidar" id="participar"></td>
                                                                <td width="15%"><i class="fa <?php echo $fa; ?>"></i></td>
                                                                <td width="10%"><i class="fa <?php echo $fa_v; ?>"></i></td>
                                                        </tr>

                                                        <?php
                                                    }
                                                }
                                                ?>
                                                  </table>
                                                <br><br>
                                                </div>
                                            <?php } ?>
                                            <br><br>

                                        </div>
                                    </div>

                                </div> 
                            
                            <center>

                                <div class="col-md-12">
                                    <?php if($statusAta == 0){ ?>
                                      <?php echo form_submit('add_item', lang("Confirmar"), 'id="add_item" class="btn btn-primary " style="padding: 6px 15px; margin:15px 0;"" '); ?>
                                    <?php } ?>
                                        <a  class="btn btn-danger" class="close" data-dismiss="modal"  href="<?= site_url('Welcome/plano_acao/'.$id); ?>"><?= lang('Voltar') ?></a>

                                </div>
                                 </center>
                             <?php echo form_close(); ?>
                            <br><br><br>
                        </div>
                        
                        
                        
                      <div class="row">
                          <div class="col-lg-12">
                              <div class="col-lg-12">
                                <div class="portlet portlet-default">
                            
                            <div class="portlet-body">
                                <div class="table-responsive">
                                    <table id="example1" class="table table-striped sorting_asc_disabled table-bordered table-hover table-green">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>USUÁRIO</th>
                                                <th>OBRIGATÓRIO</th>
                                                <th>PRESENÇA CONFIRMADA</th>
                                                
                                                <th>DT CONVITE</th>
                                                <th>DT CONFIRMAÇÃO</th>
                                                <th>STATUS CONVITE</th>
                                                <th>OPÇÕES</th>
                                                
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                           $cont = 1;
                                            $invites = $this->atas_model->getAllInvitesByAta($id);
                                            foreach ($invites as $invite) {
                                                $status = $invite->status;
                                                $obrigatorio = $invite->obrigatorio;
                                                $confirmacao = $invite->confirmacao;
                                                
                                                $data_confirmacao = $invite->data_confirmacao;
                                                
                                                if($data_confirmacao){
                                                    $data_conf = date('d/m/Y H:i', strtotime($invite->data_confirmacao));
                                                }else{
                                                    $data_conf = "";
                                                }
                                                
                                                if($status == 1){
                                                    $status_desc = "Ativo";
                                                }else if($status == 0){
                                                    $status_desc = "Cancelado";
                                                }
                                                
                                                if($obrigatorio == 1){
                                                    $obrig_desc = "SIM";
                                                }else if($obrigatorio == 0){
                                                    $obrig_desc = "NÃO";
                                                }
                                                
                                                if($confirmacao == 1){
                                                    $confirmacao_desc = "SIM";
                                                }else if($confirmacao == 0){
                                                    $confirmacao_desc = "NÃO";
                                                }else if($confirmacao == 2){
                                                    $confirmacao_desc = "CIENTE";
                                                }else if($confirmacao == 3){
                                                     $confirmacao_desc = "AGUARDANDO";
                                                }
                                                //$acoes = $this->Atas_Model->getAllAcoes($plano->idplanos);
                                                ?>   
                                                <tr class="odd gradeX">
                                                    <td><font  style="font-size: 12px;"><?php echo $cont++; ?></font></td>
                                                    <td><font  style="font-size: 12px;"><?php echo $invite->nome.' - '.$invite->setor; ?></font></td>
                                                    <td><font  style="font-size: 12px;"><?php echo $obrig_desc; ?></font></td>
                                                   <td class="center">
                                                        <font  style="font-size: 12px;"> <?php echo $confirmacao_desc; ?></font>
                                                    </td>     
                                                    <td class="center">
                                                        <font  style="font-size: 12px;"> <?php echo date('d/m/Y H:i', strtotime($invite->data_criacao)); ?></font>
                                                    </td>
                                                   <td class="center"><?php echo $data_conf; ?></td>
                                                   <td class="center"><?php echo $status_desc; ?></td>

                                                    
                                                        <td>
                                                            <div class="text-center">
                                                                <div class="btn-group text-left">
                                                                    <button style="color:#ffffff" type="button" class="btn btn-default btn-xs btn-primary dropdown-toggle" data-toggle="dropdown">
                                                                Selecione <span class="caret"></span></button>
                                                                <ul class="dropdown-menu pull-right" role="menu"> 
                                                                     <li><a title="Cancelar Convite" href="<?= site_url('welcome/manutencao_acao_pendente/' . $plano->idplanos); ?>"><i class="fa fa-times-circle"></i>Cancelar </a></li>
                                                                 </ul>
                                                            </div>
                                                            </div>
                                                        </td>
                                                        <?php //} ?>  

                                                </tr>
                                            <?php
                                            }
                                        ?>




                                        </tbody>
                                    </table>
                                    <br><br>
                                </div>
                                <!-- /.table-responsive -->
                            </div>
                            <!-- /.portlet-body -->
                        </div>
                              </div>    
                          </div>    
                      </div>
                        
                    </div>
                   
                     
                </div>    
            </div>
            
            
            
            <div class="row">
                <br>
                <BR>
            </div>
            
        </div>        
    </div>
        
   
    
    
        
    </div>
   
        
    </section>    

