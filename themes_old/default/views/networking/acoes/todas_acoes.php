<?php
include('db_connection.php');


$usuario_id = $usuario_selecionado;


?>
<?php
function geraTimestamp($data) {
    $partes = explode('/', $data);
    return mktime(0, 0, 0, $partes[1], $partes[0], $partes[2]);
}
?>
  <script src="//code.jquery.com/jquery-1.10.2.js"></script>
  <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
  

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
        //div_response.text('aqui teste');
        $.ajax({
            data: sortable_data,
            type: 'POST',
            url: '../../save.php',
            success:function(result) {
                div_response.text(result);
            }
        });
         }, 500);
    });
    
  });


  </script>
  
  <script>
  $(function() {
    $( "#sortable2" ).sortable();
    $( "#sortable2" ).disableSelection();
    
      
    var ul_sortable = $('.sortable2');
    ul_sortable.sortable({
        revert: 100,
        placeholder: 'placeholder'
    });
    ul_sortable.disableSelection();
    var btn_save = $('li.save'),
        div_response = $('#response2');
    btn_save.on('mouseup', function(e) {
        e.preventDefault();
        setTimeout(function(){ 
        var sortable_data = ul_sortable.sortable('serialize');
        //div_response.text('aqui teste');
        
        $.ajax({
            data: sortable_data,
            type: 'POST',
            url: '../../save2.php',
            success:function(result) {
                div_response.text(result);
            }
        });
         }, 500);
    });
    
  });

  </script>
  
  <script>
  $(function() {
    $( "#sortable3" ).sortable();
    $( "#sortable3" ).disableSelection();
    
      
    var ul_sortable = $('.sortable3');
    ul_sortable.sortable({
        revert: 100,
        placeholder: 'placeholder'
    });
    ul_sortable.disableSelection();
    var btn_save = $('li.save'),
        div_response = $('#response3');
    btn_save.on('mouseup', function(e) {
        e.preventDefault();
        setTimeout(function(){ 
        var sortable_data = ul_sortable.sortable('serialize');
        //div_response.text('aqui teste');
        $.ajax({
            data: sortable_data,
            type: 'POST',
            url: '../../save3.php',
            success:function(result) {
                div_response.text(result);
            }
        });
         }, 500);
    });
    
  });

  </script>

  <script>
  $(function() {
    $( "#sortable4" ).sortable();
    $( "#sortable4" ).disableSelection();
    
   // alert('aqui');
      
    var ul_sortable = $('.sortable4');
    ul_sortable.sortable({
        revert: 100,
        placeholder: 'placeholder'
    });
    ul_sortable.disableSelection();
    var btn_save = $('li.save'),
        div_response = $('#response4');
    btn_save.on('mouseup', function(e) {
        e.preventDefault();
        setTimeout(function(){ 
        var sortable_data = ul_sortable.sortable('serialize');
        //div_response.text('aqui teste');
        $.ajax({
            data: sortable_data,
            type: 'POST',
            url: '../../save4.php',
            success:function(result) {
                div_response.text(result);
            }
        });
         }, 500);
    });
    
  });

  function salvaChat() {
  $.ajax({
    type: "POST",
    url: "../../save_chat.php",
    data: {
      mensagem: $('#mensagem').val(),
      acao: $('#acao').val(),
      user: $('#user').val()
    },
    success: function(data) {
      $('#response').html(data);
    }
  });
}




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
    
  <body  onload='javascript:scrollinto();'  class="hold-transition skin-green sidebar-collapse  sidebar-mini">

    <div class="content-wrapper">
    
     
    <!-- Content Header (Page header) 
    <div class="alert alert-success" id="response" role="alert"></div>
    <div class="alert alert-success" id="response2" role="alert"></div>
    <div class="alert alert-success" id="response3" role="alert"></div>
    <div class="alert alert-success" id="response4" role="alert"></div>
    -->
    <section class="content-header">
      <h1>
        Minhas Atividades
        <small>Painel de Controle</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
      </ol>
    </section>

      
    <!-- Main content -->
    <section class="content">
      <!-- Small boxes (Stat box) -->
      <div class="row">
          
              <?php
                            $attrib = array('data-toggle' => 'validator', 'role' => 'form');
                            echo form_open_multipart("Login_Projetos/controleAtividades", $attrib);
                            
                            ?>
              <div class="col-md-4">
                  <div class="form-group">


                      <?php
                      $wu4[''] = 'Selecione o Usuário Desejado';
                      foreach ($users as $user) {
                          $wu4[$user->user_id] = $user->nome . ' ' . $user->last . ' - ' . $user->setor;
                      }
                      //  echo form_dropdown('responsavel[]', $wu4, (isset($_POST['responsavel']) ? $_POST['responsavel'] : ""), 'id="slResponsavel"  class="form-control  select" data-placeholder="' . lang("Selecione o Responsável") . ' "  style="width:100%;" multiple  required="required"');
                      echo form_dropdown('responsavel', $wu4, (isset($_POST['responsavel']) ? $_POST['responsavel'] : $usuario_id), 'id="slResponsavel" required="required"  class="form-control  select" data-placeholder="' . lang("Selecione o(s) Responsavel(eis)") . ' "   style="width:100%;"   ');
                      ?>
                  </div>
              </div>
              <div class="col-md-4">
                  <input type="submit" value="FILTRAR" class="btn btn-success">
              </div>  
            <?php echo form_close(); ?>
      </div>
       <?php
           if($usuario_id){
                $result = mysqli_query($link, "SELECT * FROM `sma_planos`  WHERE `responsavel` = $usuario_id and status_tipo = 1 and `status` = 'PENDENTE'  ORDER BY data_termino asc ");
                $qtde_reg = mysqli_num_rows($result);
                
                $result2 = mysqli_query($link, "SELECT * FROM `sma_planos`  WHERE `responsavel` = $usuario_id and status_tipo = 2 and `status` = 'PENDENTE'  ORDER BY position asc");
                $qtde_reg2 = mysqli_num_rows($result2);
                
                $result3 = mysqli_query($link, "SELECT * FROM `sma_planos`  WHERE `responsavel` = $usuario_id and status_tipo = 3 and `status` = 'PENDENTE'  ORDER BY position asc");
                $qtde_reg3 = mysqli_num_rows($result3);
                
                $result4 = mysqli_query($link, "SELECT * FROM `sma_planos`  WHERE `responsavel` = $usuario_id and status_tipo = 4 and `status` = 'PENDENTE'  ORDER BY position asc");
                $qtde_reg4 = mysqli_num_rows($result4);
            }
          ?>
      
      
      
      <div class="row">
          
        <div style="border-right: 1px solid #000000" class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-orange">
            
            
            <a  href="#" class="small-box-footer">Não Iniciada (<?php echo $qtde_reg; ?>) </a>
          </div>
        </div>
        <!-- ./col -->
        <div style="border-right: 1px solid #000000" class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-green-gradient">
           
           
            <a href="#" class="small-box-footer">Fazendo (<?php echo $qtde_reg2; ?>) </a>
          </div>
        </div>
        <!-- ./col -->
        <div style="border-right: 1px solid #000000" class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-red">
            
            
            <a href="#" class="small-box-footer">StandBy (<?php echo $qtde_reg3; ?>) </a>
          </div>
        </div>
        <!-- ./col -->
        <div  class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-black">
           
            
              <a href="#" style="color: #ffffff" class="small-box-footer">Aguardando (<?php echo $qtde_reg4; ?>) </a>
          </div>
        </div>
        <!-- ./col -->
      </div>
      
     <div class="container">   
    </div>
    
      <div class="row">
          
           <?php
           
                $result = mysqli_query($link, "SELECT * FROM `sma_planos`  WHERE `responsavel` = $usuario_id and status_tipo = 1 and `status` = 'PENDENTE'  ORDER BY data_termino asc ");
            ?>
        <!-- TIPO 1 -->
         <section style="border-right: 1px solid #000000"  class="col-lg-3 connectedSortable sortable">
            <?php
            while ($row = mysqli_fetch_assoc($result)) {
                 $status = $row['status'];
                 $anexo = $row['anexo'];
                 $data_prazo = $row['data_termino'];
                 $id_plano = $row['idplanos']; 
                 
                if ($status == 'PENDENTE') {
                    $dataHoje = date('Y-m-d H:i:s');

                    /*
                     * SE A DATA ATUAL FOR < A DATA DO PRAZO
                     * PENDENTE
                     */
                    if ($dataHoje <= $data_prazo) {
                        $novo_status = ' Vence em';
                        $label = "warning";
                        $nstatus = 1;
                    }

                    /*
                     * SE A DATA ATUAL FOR > A DATA DO PRAZO
                     * ATRASADO (X DIAS)
                     * +5 DIAS
                     * +10 DIAS
                     * 
                     */
                    if ($dataHoje > $data_prazo) {
                        $novo_status = 'Atrasado a ';
                        $label = "danger";
                        $nstatus = 2;
                    }   

                        // Usa a função criada e pega o timestamp das duas datas:
                        $time_inicial = geraTimestamp($this->sma->hrld(date('Y-m-d H:i:s')));
                        $time_final = geraTimestamp($this->sma->hrld($row['data_termino']));
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
                        }else if ($dias < '-15') {
                            $qtde_dias = '+15';
                        }else  {
                            $qtde_dias = $dias;
                        }
                        $qtde_dias = str_replace('-', '', $qtde_dias);
                    
                        $projetos_usuario = $this->atas_model->getAtaProjetoByID_ATA($row['idatas']);
                         $evento =  $this->atas_model->getAllitemEventoByID($row['eventos']);   
               
                } 
                ?> 
                 <?php if ($nstatus == '1') {
                                          
                                          $botao = "orange";
                                          $color = "#ffffff";
                                          } else if ($nstatus == '2') {
                                              
                                            if ($dias >= '-5') {
                                                $botao = "red";
                                                $color = "#ffffff";
                                            } else if (($dias < '-5') && ($dias >= '-10')) { 
                                                 $botao = "red";
                                                $color = "#ffffff";
                                         } else if ($dias < '-10') { 
                                             $botao = "#000000";
                                                $color = "#ffffff";
                                        } 
                                        
                                          }
                                          ?> 
           
            
            <li class="save " id=item-<?php echo $row['idplanos'] ?>>
                 Ação : <?php echo $row['idplanos']; ?>  <?php if($row['id_ticket']){ echo '| Id Ticket : '. $row['id_ticket']; } ?> 
            <a href="#">
            <i class="fa "></i>
            
            <span class="pull-right-container">
              <span style="background-color:<?php echo $botao; ?>; color: <?php echo $color; ?>" class="label  pull-right"><?php echo $novo_status .  $qtde_dias . ' dias  '; ?> (<?php echo $this->sma->hrld( $row['data_termino']);  ?>)</span>
            </span>
          </a>
                    <div id="<?php echo $row['idplanos']; ?>"  class=" box  collapsed-box " >
                        <div class="box-header">
                            <?php echo $projetos_usuario->projetos; ?>
                            <br>
                            <?php echo $evento->evento.'/'.$evento->item; ?>
                               
                            
                            <div class="box-tools pull-right " data-toggle="tooltip" title="Status">
                                
                                <div class="btn-group" data-toggle="btn-toggle">
                                    <table>
                                        <tr>
                                            <td><a href="atualizaStatusAtividade/<?php echo $id_plano; ?>/2" onclick="" class="btn btn-block" ><i class="fa fa-mail-forward"></i></a></td>
                                            <td><button  type="button" class="btn btn-block  btn-sm" data-widget="collapse"><i class="fa fa-plus"></i></button></td>
                                        </tr>
                                    </table>
                                    
                                    
                                  
                                </div>
                            </div>
                            <hr>
                            <p style="font-weight: bold"> <?php echo utf8_encode($row['descricao']); ?>
                            <?php if ($row['anexo']) { ?>
                                <a target="_blank" href="<?= site_url('../assets/uploads/atas/' . $row['anexo']) ?>" class="tip btn btn-file" title="<?= lang('Fazer Download do Anexo') ?>">
                                    <span class="glyphicon glyphicon-paperclip"></span>
                                    <span class="glyphicon-class">Ver Anexo</span>
                                </a>
                             <?php } ?>
                            </p>
                            <p> <?php echo utf8_encode($row['observacao']); ?></p>
                            
                            
                        </div>
                       
                               
                        <div  class="box-footer text-black" id="container<?php echo $row['idplanos'] ?>"  >
                            <div  class="row">
                                <?php
                                    $observacoes =  $this->atas_model->getAllHistoricoAcoes($row['idplanos']);
                                    if($observacoes){
                                ?>
                                
                                <div  id="id_chat">
                                    <div  class="box-body chat" id="chat-box"  >
                                    <!-- chat item -->
                                     <?php
                                        $observacoes =  $this->atas_model->getAllHistoricoAcoes($row['idplanos']);
                                         foreach ($observacoes as $observacao) {
                                                $usuario = $observacao->user;
                                                $dados_user = $this->site->getUser($usuario);
                                                
                                                $avatar = $dados_user->avatar;
                                                $genero = $dados_user->gender;
                                     ?>
                                                <div class="item">
                                                    <img src="<?= $avatar ? $assets . '../../../assets/uploads/avatars/thumbs/' . $avatar : $assets . 'images/' . $genero . '.png'; ?>" alt="user image" class="online">

                                                    <p class="message">
                                                        <a href="#" class="name">
                                                            <small class="text-muted pull-right"><i class="fa fa-clock-o"></i> <?php echo $this->sma->hrld( $observacao->data_envio); ?></small>
                                                            <?php echo  $observacao->username; ?>
                                                        </a>
                                                        <?php echo  $observacao->observacao; ?>
                                                    </p>
                                                    <?php if($observacao->anexo != null){  ?>
                                                    <div class="attachment">
                                                        <h4>Anexo:</h4>

                                                        <p class="filename">
                                                            Theme-thumbnail-image.jpg
                                                        </p>

                                                        <div class="pull-right">
                                                            <a  class="btn btn-primary btn-sm btn-flat" href="<?= base_url() ?>assets/uploads/historico_acoes/<?php echo $observacao->anexo; ?>" target="_blank">Abrir</a>

                                                        </div>
                                                    </div>
                                                     <?php } ?>
                                                    <!-- /.attachment -->
                                                </div>
                                        <?php
                                        }
                                        ?>
                                   
                                    </div>
                                </div>    
                                    <?php } ?>         
                               
                             </div>
                        </div>
                       <form>
                                   <input type="hidden" name="user" id="user" value="<?php echo $usuario_sessao; ?>">
                                   <input type="hidden" name="acao" id="acao" value="<?php echo $row['idplanos']; ?>">
                                 
                           <div class="box-footer">
                                        <div class="input-group">
                                            <div class="input-group-btn">
                                                <a  class="btn btn-facebook" href="<?= site_url('welcome/manutencao_acao_new/'.$row['idplanos']); ?>"><?= lang('Abrir') ?></a>
                                                        <div class="box-tools pull-right" data-toggle="tooltip" title="Status">
                                                            <div class="btn-group" data-toggle="btn-toggle">
                                                                    <a  class="btn btn-success" href="<?= site_url('welcome/retorno_new/'.$row['idplanos']); ?>"><?= lang('Concluir') ?></a>

                                                            </div>
                                                        </div>
                                            </div>
                                            
                                        </div>
                                    </div>
                                </form>
                    </div>
                </li>

                <?php
            }
            ?>        
         </section>
        <?php
            mysqli_free_result($result);
        ?>
       
        <div id="tipo2">
        
       <?php
            $result2 = mysqli_query($link, "SELECT * FROM `sma_planos`  WHERE `responsavel` = $usuario_id and status_tipo = 2 and `status` = 'PENDENTE'  ORDER BY position asc");
        ?>

        <!-- TIPO 2 -->
        <section style="border-right: 1px solid #000000"  class="col-lg-3 connectedSortable sortable2">
            <?php
            while ($row2 = mysqli_fetch_assoc($result2)) {
                 $status = $row2['status'];
                 $data_prazo = $row2['data_termino'];

                if ($status == 'PENDENTE') {
                    $dataHoje = date('Y-m-d H:i:s');

                    /*
                     * SE A DATA ATUAL FOR < A DATA DO PRAZO
                     * PENDENTE
                     */
                    if ($dataHoje <= $data_prazo) {
                        $novo_status = ' Vence em';
                        $label = "warning";
                         $nstatus = 1;
                    }

                    /*
                     * SE A DATA ATUAL FOR > A DATA DO PRAZO
                     * ATRASADO (X DIAS)
                     * +5 DIAS
                     * +10 DIAS
                     * 
                     */
                    if ($dataHoje > $data_prazo) {
                        $novo_status = 'Atrasado a ';
                        $label = "danger";
                         $nstatus = 2;
                    }   

                        // Usa a função criada e pega o timestamp das duas datas:
                        $time_inicial = geraTimestamp($this->sma->hrld(date('Y-m-d H:i:s')));
                        $time_final = geraTimestamp($this->sma->hrld($row2['data_termino']));
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
                        }else if ($dias < '-15') {
                            $qtde_dias = '+15';
                        }else  {
                            $qtde_dias = $dias;
                        }
                        $qtde_dias = str_replace('-', '', $qtde_dias);
                    
                        $projetos_usuario = $this->atas_model->getAtaProjetoByID_ATA($row2['idatas']);
                         $evento =  $this->atas_model->getAllitemEventoByID($row2['eventos']);   
                }
                ?> 
             <?php if ($nstatus == '1') {
                                          
                                          $botao = "orange";
                                          $color = "#ffffff";
                                          } else if ($nstatus == '2') {
                                              
                                            if ($dias >= '-5') {
                                                $botao = "red";
                                                $color = "#ffffff";
                                            } else if (($dias < '-5') && ($dias >= '-10')) { 
                                                 $botao = "red";
                                                $color = "#ffffff";
                                         } else if ($dias < '-10') { 
                                             $botao = "#000000";
                                                $color = "#ffffff";
                                        } 
                                        
                                          }
                                          ?> 
             
            <li class="save " id=item-<?php echo $row2['idplanos'] ?>>
                Ação : <?php echo $row2['idplanos']; ?>  <?php if($row2['id_ticket']){ echo '| Id Ticket : '. $row2['id_ticket']; } ?> 
            <a href="#">
            <i class="fa "></i>
            
            <span class="pull-right-container">
              <span style="background-color:<?php echo $botao; ?>; color: <?php echo $color; ?>" class="label  pull-right"><?php echo $novo_status .  $qtde_dias . ' dias  '; ?> (<?php echo $this->sma->hrld( $row2['data_termino']);  ?>)</span>
            </span>
          </a>
                    <div id="<?php echo $row2['idplanos']; ?>"  class=" box collapsed-box" >
                         <div class="box-header">
                            <?php echo $projetos_usuario->projetos; ?>
                            <br>
                            <?php echo $evento->evento.'/'.$evento->item; ?>
                               
                            
                            <div class="box-tools pull-right" data-toggle="tooltip" title="Status">
                                <div class="btn-group" data-toggle="btn-toggle">
                                    
                                   <table>
                                        <tr>
                                            <td><a href="atualizaStatusAtividade/<?php echo $row2['idplanos']; ?>/1" onclick="" class="btn btn-block" ><i class="fa fa-mail-reply"></i></a></td>
                                            <td><a href="atualizaStatusAtividade/<?php echo $row2['idplanos']; ?>/3" onclick="" class="btn btn-block" ><i class="fa fa-mail-forward"></i></a></td>
                                            <td><button  type="button" class="btn btn-block  btn-sm" data-widget="collapse"><i class="fa fa-plus"></i></button></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <hr>
                            <p style="font-weight: bold"> <?php echo utf8_encode($row2['descricao']); ?>
                            <?php if ($row2['anexo']) { ?>
                                <a target="_blank" href="<?= site_url('../assets/uploads/atas/' . $row2['anexo']) ?>" class="tip btn btn-file" title="<?= lang('Fazer Download do Anexo') ?>">
                                    <span class="glyphicon glyphicon-paperclip"></span>
                                    <span class="glyphicon-class">Ver Anexo</span>
                                </a>
                             <?php } ?>
                            </p>
                            <p> <?php echo utf8_encode($row2['observacao']); ?></p>
                            
                            
                        </div>
                        <script>
                             $(document).ready(function(){
                             $("#container<?php echo $row2['idplanos'] ?>").scrollTop($("#container<?php echo $row2['idplanos'] ?>")[0].scrollHeight);
                            })
                       </script> 
                               
                        <div  class="box-footer text-black" id="container<?php echo $row2['idplanos'] ?>"  >
                            <div  class="row">
                                <?php
                                    $observacoes =  $this->atas_model->getAllHistoricoAcoes($row2['idplanos']);
                                    if($observacoes){
                                ?>
                                
                                <div  id="id_chat">
                                    <div  class="box-body chat" id="chat-box"  >
                                    <!-- chat item -->
                                     <?php
                                        $observacoes2 =  $this->atas_model->getAllHistoricoAcoes($row2['idplanos']);
                                         foreach ($observacoes2 as $observacao2) {
                                                $usuario2 = $observacao2->user;
                                                $dados_user2 = $this->site->getUser($usuario2);
                                                
                                                $avatar2 = $dados_user2->avatar;
                                                $genero2 = $dados_user2->gender;
                                     ?>
                                                <div class="item">
                                                    <img src="<?= $avatar2 ? $assets . '../../../assets/uploads/avatars/thumbs/' . $avatar2 : $assets . 'images/' . $genero2 . '.png'; ?>" alt="user image" class="online">

                                                    <p class="message">
                                                        <a href="#" class="name">
                                                            <small class="text-muted pull-right"><i class="fa fa-clock-o"></i> <?php echo $this->sma->hrld( $observacao2->data_envio); ?></small>
                                                            <?php echo  $observacao2->username; ?>
                                                        </a>
                                                        <?php echo  $observacao2->observacao; ?>
                                                    </p>
                                                    <?php if($observacao2->anexo != null){  ?>
                                                    <div class="attachment">
                                                        <h4>Anexo:</h4>

                                                        <p class="filename">
                                                            Theme-thumbnail-image.jpg
                                                        </p>

                                                        <div class="pull-right">
                                                            <a  class="btn btn-primary btn-sm btn-flat" href="<?= base_url() ?>assets/uploads/historico_acoes/<?php echo $observacao2->anexo; ?>" target="_blank">Abrir</a>

                                                        </div>
                                                    </div>
                                                     <?php } ?>
                                                    <!-- /.attachment -->
                                                </div>
                                        <?php
                                        }
                                        ?>
                                   
                                    </div>
                                </div>    
                                    <?php
                                      }
                                        ?>         
                               
                             </div>
                        </div>
                       <form>
                                   <input type="hidden" name="user" id="user" value="<?php echo $usuario_sessao; ?>">
                                   <input type="hidden" name="acao" id="acao" value="<?php echo $row2['idplanos']; ?>">
                                 
                           <div class="box-footer">
                                        <div class="input-group">
                                            <div class="input-group-btn">
                                                <a  class="btn btn-facebook" href="<?= site_url('welcome/manutencao_acao_new/'.$row2['idplanos']); ?>"><?= lang('Abrir') ?></a>
                                                        <div class="box-tools pull-right" data-toggle="tooltip" title="Status">
                                                            <div class="btn-group" data-toggle="btn-toggle">
                                                                    <a  class="btn btn-success" href="<?= site_url('welcome/retorno_new/'.$row2['idplanos']); ?>"><?= lang('Concluir') ?></a>

                                                            </div>
                                                        </div>
                                            </div>
                                            
                                        </div>
                                    </div>
                                </form>
                    </div>
                </li>

                <?php
            }
            ?>        
         </section>
        <?php
            mysqli_free_result($result2);
         ?>
        
        </div>
        
        
         <?php
            $result3 = mysqli_query($link, "SELECT * FROM `sma_planos`  WHERE `responsavel` = $usuario_id and status_tipo = 3 and `status` = 'PENDENTE'  ORDER BY position asc");
        ?>

        <!-- TIPO 3 -->
        <section style="border-right: 1px solid #000000" class="col-lg-3 connectedSortable sortable3">
                    <?php
                    while ($row3 = mysqli_fetch_assoc($result3)) {
                        $status = $row3['status'];
                 $data_prazo = $row3['data_termino'];

                if ($status == 'PENDENTE') {
                    $dataHoje = date('Y-m-d H:i:s');

                    /*
                     * SE A DATA ATUAL FOR < A DATA DO PRAZO
                     * PENDENTE
                     */
                    if ($dataHoje <= $data_prazo) {
                        $novo_status = ' Vence em';
                        $label = "warning";
                         $nstatus = 1;
                    }

                    /*
                     * SE A DATA ATUAL FOR > A DATA DO PRAZO
                     * ATRASADO (X DIAS)
                     * +5 DIAS
                     * +10 DIAS
                     * 
                     */
                    if ($dataHoje > $data_prazo) {
                        $novo_status = 'Atrasado a ';
                        $label = "danger";
                         $nstatus = 2;
                    }   

                        // Usa a função criada e pega o timestamp das duas datas:
                        $time_inicial = geraTimestamp($this->sma->hrld(date('Y-m-d H:i:s')));
                        $time_final = geraTimestamp($this->sma->hrld($row3['data_termino']));
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
                        }else if ($dias < '-15') {
                            $qtde_dias = '+15';
                        }else  {
                            $qtde_dias = $dias;
                        }
                        $qtde_dias = str_replace('-', '', $qtde_dias);
                    
                        $projetos_usuario = $this->atas_model->getAtaProjetoByID_ATA($row3['idatas']);
                         $evento =  $this->atas_model->getAllitemEventoByID($row3['eventos']);   
                }
                        ?> 
             <?php if ($nstatus == '1') {
                                          
                                          $botao = "orange";
                                          $color = "#ffffff";
                                          } else if ($nstatus == '2') {
                                              
                                            if ($dias >= '-5') {
                                                $botao = "red";
                                                $color = "#ffffff";
                                            } else if (($dias < '-5') && ($dias >= '-10')) { 
                                                 $botao = "red";
                                                $color = "#ffffff";
                                         } else if ($dias < '-10') { 
                                             $botao = "#000000";
                                                $color = "#ffffff";
                                        } 
                                        
                                          }
                                          ?> 
                        
                        <li class="save " id=item-<?php echo $row3['idplanos']; ?>>
                             Ação : <?php echo $row3['idplanos']; ?>  <?php if($row3['id_ticket']){ echo '| Id Ticket : '. $row3['id_ticket']; } ?> 
                    <a href="#">
                    <i class="fa "></i>

                    <span class="pull-right-container">
                      <span style="background-color:<?php echo $botao; ?>; color: <?php echo $color; ?>"  class="label  pull-right"><?php echo $novo_status .  $qtde_dias . ' dias  '; ?> (<?php echo $this->sma->hrld( $row3['data_termino']);  ?>)</span>
                    </span>
                  </a>
                            <div id="<?php echo $row3['idplanos']; ?>"  class=" box collapsed-box" >
                                 <div class="box-header">
                            <?php echo $projetos_usuario->projetos; ?>
                            <br>
                            <?php echo $evento->evento.'/'.$evento->item; ?>
                               
                            
                            <div class="box-tools pull-right" data-toggle="tooltip" title="Status">
                                <div class="btn-group" data-toggle="btn-toggle">
                                    
                                    <?php if ($nstatus == '1') {
                                          
                                          $botao = "orange";
                                          $color = "#ffffff";
                                          } else if ($nstatus == '2') {
                                              
                                            if ($dias >= '-5') {
                                                $botao = "red";
                                                $color = "#ffffff";
                                            } else if (($dias < '-5') && ($dias >= '-10')) { 
                                                 $botao = "red";
                                                $color = "#ffffff";
                                         } else if ($dias < '-10') { 
                                             $botao = "#000000";
                                                $color = "#ffffff";
                                        } 
                                        
                                          }
                                          ?> 

 
                                    <table>
                                        <tr>
                                            <td><a href="atualizaStatusAtividade/<?php echo $row3['idplanos']; ?>/2" onclick="" class="btn btn-block" ><i class="fa fa-mail-reply"></i></a></td>
                                            <td><a href="atualizaStatusAtividade/<?php echo $row3['idplanos']; ?>/4" onclick="" class="btn btn-block" ><i class="fa fa-mail-forward"></i></a></td>
                                            <td><button  type="button" class="btn btn-block  btn-sm" data-widget="collapse"><i class="fa fa-plus"></i></button></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <hr>
                            <p style="font-weight: bold"> <?php echo utf8_encode($row3['descricao']); ?>
                             <?php if ($row3['anexo']) { ?>
                                <a target="_blank" href="<?= site_url('../assets/uploads/atas/' . $row3['anexo']) ?>" class="tip btn btn-file" title="<?= lang('Fazer Download do Anexo') ?>">
                                    <span class="glyphicon glyphicon-paperclip"></span>
                                    <span class="glyphicon-class">Ver Anexo</span>
                                </a>
                             <?php } ?>
                            </p>
                            <p> <?php echo utf8_encode($row3['observacao']); ?></p>
                            
                            <div class="input-group">
                                <div class="input-group-btn">
                                    <p> <?php echo utf8_encode($row3['descricao']); ?></p>
                                </div>
                            </div> 
                        </div>
                                <!-- /.box-header -->
                                <div  class="box-footer text-black" id="container<?php echo $row3['idplanos'] ?>"  >
                            <div  class="row">
                                <?php
                                    $observacoes3 =  $this->atas_model->getAllHistoricoAcoes($row3['idplanos']);
                                    if($observacoes3){
                                ?>
                                
                                <div  id="id_chat">
                                    <div  class="box-body chat" id="chat-box"  >
                                    <!-- chat item -->
                                     <?php
                                        $observacoes3 =  $this->atas_model->getAllHistoricoAcoes($row3['idplanos']);
                                         foreach ($observacoes3 as $observacao) {
                                                $usuario = $observacao->user;
                                                $dados_user = $this->site->getUser($usuario);
                                                
                                                $avatar = $dados_user->avatar;
                                                $genero = $dados_user->gender;
                                     ?>
                                                <div class="item">
                                                    <img src="<?= $avatar ? $assets . '../../../assets/uploads/avatars/thumbs/' . $avatar : $assets . 'images/' . $genero . '.png'; ?>" alt="user image" class="online">

                                                    <p class="message">
                                                        <a href="#" class="name">
                                                            <small class="text-muted pull-right"><i class="fa fa-clock-o"></i> <?php echo $this->sma->hrld( $observacao->data_envio); ?></small>
                                                            <?php echo  $observacao->username; ?>
                                                        </a>
                                                        <?php echo  $observacao->observacao; ?>
                                                    </p>
                                                    <?php if($observacao->anexo != null){  ?>
                                                    <div class="attachment">
                                                        <h4>Anexo:</h4>

                                                        <p class="filename">
                                                            Theme-thumbnail-image.jpg
                                                        </p>

                                                        <div class="pull-right">
                                                            <a  class="btn btn-primary btn-sm btn-flat" href="<?= base_url() ?>assets/uploads/historico_acoes/<?php echo $observacao->anexo; ?>" target="_blank">Abrir</a>

                                                        </div>
                                                    </div>
                                                     <?php } ?>
                                                    <!-- /.attachment -->
                                                </div>
                                        <?php
                                        }
                                        ?>
                                   
                                    </div>
                                </div>    
                                    <?php
                                      }
                                        ?>         
                               
                             </div>
                        </div>
                       <form>
                                   <input type="hidden" name="user" id="user" value="<?php echo $usuario_sessao; ?>">
                                   <input type="hidden" name="acao" id="acao" value="<?php echo $row3['idplanos']; ?>">
                                 
                           <div class="box-footer">
                                        <div class="input-group">
                                            <div class="input-group-btn">
                                                <a  class="btn btn-facebook" href="<?= site_url('welcome/manutencao_acao_new/'.$row3['idplanos']); ?>"><?= lang('Abrir') ?></a>
                                                        <div class="box-tools pull-right" data-toggle="tooltip" title="Status">
                                                            <div class="btn-group" data-toggle="btn-toggle">
                                                                    <a  class="btn btn-success" href="<?= site_url('welcome/retorno_new/'.$row3['idplanos']); ?>"><?= lang('Concluir') ?></a>

                                                            </div>
                                                        </div>
                                            </div>
                                            
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </li>
                        <?php
                    }
                    ?>
                    
         </section>
        <?php
            mysqli_free_result($result3);
         ?>
       
        <?php
            $result4 = mysqli_query($link, "SELECT * FROM `sma_planos`  WHERE `responsavel` = $usuario_id and status_tipo = 4 and `status` = 'PENDENTE'  ORDER BY position asc");
        ?>

        <!-- TIPO 4 -->
        <section  class="col-lg-3 connectedSortable sortable4">
            
               
                    <?php
                    while ($row4 = mysqli_fetch_assoc($result4)) {
                          $status = $row4['status'];
                 $data_prazo = $row4['data_termino'];

                if ($status == 'PENDENTE') {
                    $dataHoje = date('Y-m-d H:i:s');

                    /*
                     * SE A DATA ATUAL FOR < A DATA DO PRAZO
                     * PENDENTE
                     */
                    if ($dataHoje <= $data_prazo) {
                        $novo_status = ' Vence em';
                        $label = "warning";
                         $nstatus = 1;
                    }

                    /*
                     * SE A DATA ATUAL FOR > A DATA DO PRAZO
                     * ATRASADO (X DIAS)
                     * +5 DIAS
                     * +10 DIAS
                     * 
                     */
                    if ($dataHoje > $data_prazo) {
                        $novo_status = 'Atrasado a ';
                        $label = "danger";
                         $nstatus = 2;
                    }   

                        // Usa a função criada e pega o timestamp das duas datas:
                        $time_inicial = geraTimestamp($this->sma->hrld(date('Y-m-d H:i:s')));
                        $time_final = geraTimestamp($this->sma->hrld($row4['data_termino']));
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
                        }else if ($dias < '-15') {
                            $qtde_dias = '+15';
                        }else  {
                            $qtde_dias = $dias;
                        }
                        $qtde_dias = str_replace('-', '', $qtde_dias);
                    
                        $projetos_usuario = $this->atas_model->getAtaProjetoByID_ATA($row4['idatas']);
                         $evento =  $this->atas_model->getAllitemEventoByID($row4['eventos']);   
                }
                        ?> 
             <?php if ($nstatus == '1') {
                                          
                                          $botao = "orange";
                                          $color = "#ffffff";
                                          } else if ($nstatus == '2') {
                                              
                                            if ($dias >= '-5') {
                                                $botao = "red";
                                                $color = "#ffffff";
                                            } else if (($dias < '-5') && ($dias >= '-10')) { 
                                                 $botao = "red";
                                                $color = "#ffffff";
                                         } else if ($dias < '-10') { 
                                             $botao = "#000000";
                                                $color = "#ffffff";
                                        } 
                                        
                                          }
                                          ?> 
                         
                        <li class="save " id=item-<?php echo $row4['idplanos']; ?>>
                            Ação : <?php echo $row4['idplanos']; ?>  <?php if($row4['id_ticket']){ echo '| Id Ticket : '. $row4['id_ticket']; } ?> 
                    <a href="#">
                    <i class="fa "></i>

                    <span class="pull-right-container">
                      <span style="background-color:<?php echo $botao; ?>; color: <?php echo $color; ?>"  class="label  pull-right"><?php echo $novo_status .  $qtde_dias . ' dias  '; ?> (<?php echo $this->sma->hrld( $row4['data_termino']);  ?>)</span>
                    </span>
                  </a>
                            <div id="<?php echo $row4['idplanos']; ?>"  class=" box collapsed-box" >
                                 <div class="box-header">
                            <?php echo $projetos_usuario->projetos; ?>
                            <br>
                            <?php echo $evento->evento.'/'.$evento->item; ?>
                               
                            
                            <div class="box-tools pull-right" data-toggle="tooltip" title="Status">
                                <div class="btn-group" data-toggle="btn-toggle">
                                    <table>
                                        <tr>
                                            <td><a href="atualizaStatusAtividade/<?php echo $row4['idplanos']; ?>/3" onclick="" class="btn btn-block" ><i class="fa fa-mail-reply"></i></a></td>
                                            <td><button  type="button" class="btn btn-block  btn-sm" data-widget="collapse"><i class="fa fa-plus"></i></button></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <hr>
                            <p style="font-weight: bold"> <?php echo utf8_encode($row4['descricao']); ?>
                                <?php if ($row4['anexo']) { ?>
                                <a target="_blank" href="<?= site_url('../assets/uploads/atas/' . $row4['anexo']) ?>" class="tip btn btn-file" title="<?= lang('Fazer Download do Anexo') ?>">
                                    <span class="glyphicon glyphicon-paperclip"></span>
                                    <span class="glyphicon-class">Ver Anexo</span>
                                </a>
                             <?php } ?>
                            </p>
                            <p> <?php echo utf8_encode($row4['observacao']); ?></p>
                            
                            
                        </div>
                                <!-- /.box-header -->
                                <div  class="box-footer text-black" id="container<?php echo $row4['idplanos'] ?>"  >
                            <div  class="row">
                                <?php
                                    $observacoes4 =  $this->atas_model->getAllHistoricoAcoes($row4['idplanos']);
                                    if($observacoes4){
                                ?>
                                
                                <div  id="id_chat">
                                    <div  class="box-body chat" id="chat-box"  >
                                    <!-- chat item -->
                                     <?php
                                        $observacoes4 =  $this->atas_model->getAllHistoricoAcoes($row4['idplanos']);
                                         foreach ($observacoes4 as $observacao) {
                                                $usuario = $observacao->user;
                                                $dados_user = $this->site->getUser($usuario);
                                                
                                                $avatar = $dados_user->avatar;
                                                $genero = $dados_user->gender;
                                     ?>
                                                <div class="item">
                                                    <img src="<?= $avatar ? $assets . '../../../assets/uploads/avatars/thumbs/' . $avatar : $assets . 'images/' . $genero . '.png'; ?>" alt="user image" class="online">

                                                    <p class="message">
                                                        <a href="#" class="name">
                                                            <small class="text-muted pull-right"><i class="fa fa-clock-o"></i> <?php echo $this->sma->hrld( $observacao->data_envio); ?></small>
                                                            <?php echo  $observacao->username; ?>
                                                        </a>
                                                        <?php echo  $observacao->observacao; ?>
                                                    </p>
                                                    <?php if($observacao->anexo != null){  ?>
                                                    <div class="attachment">
                                                        <h4>Anexo:</h4>

                                                        <p class="filename">
                                                            Theme-thumbnail-image.jpg
                                                        </p>

                                                        <div class="pull-right">
                                                            <a  class="btn btn-primary btn-sm btn-flat" href="<?= base_url() ?>assets/uploads/historico_acoes/<?php echo $observacao->anexo; ?>" target="_blank">Abrir</a>

                                                        </div>
                                                    </div>
                                                     <?php } ?>
                                                    <!-- /.attachment -->
                                                </div>
                                        <?php
                                        }
                                        ?>
                                   
                                    </div>
                                </div>    
                                    <?php
                                      }
                                        ?>         
                               
                             </div>
                        </div>
                       <form>
                                   <input type="hidden" name="user" id="user" value="<?php echo $usuario_sessao; ?>">
                                   <input type="hidden" name="acao" id="acao" value="<?php echo $row['idplanos']; ?>">
                                 
                           <div class="box-footer">
                                        <div class="input-group">
                                            <div class="input-group-btn">
                                                <a  class="btn btn-facebook" href="<?= site_url('welcome/manutencao_acao_new/'.$row4['idplanos']); ?>"><?= lang('Abrir') ?></a>
                                                        <div class="box-tools pull-right" data-toggle="tooltip" title="Status">
                                                            <div class="btn-group" data-toggle="btn-toggle">
                                                                    <a  class="btn btn-success" href="<?= site_url('welcome/retorno_new/'.$row4['idplanos']); ?>"><?= lang('Concluir') ?></a>

                                                            </div>
                                                        </div>
                                            </div>
                                            
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </li>
                        <?php
                    }
                    ?>
                    
         </section>
        <?php
            mysqli_free_result($result4);
        //}
      //  mysqli_close($link);
        ?>
      </div>

    </section>
    <!-- /.content -->
  </div>
  
</body>