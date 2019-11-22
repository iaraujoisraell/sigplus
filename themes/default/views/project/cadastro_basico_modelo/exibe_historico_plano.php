<?php

$tipo = $_GET['tipo'];

if (isset($_POST)) {
    include('../../db_connection.php');

    // DEFINE O FUSO HORARIO COMO O HORARIO DE BRASILIA
    date_default_timezone_set('America/Manaus');
// CRIA UMA VARIAVEL E ARMAZENA A HORA ATUAL DO FUSO-HORÀRIO DEFINIDO (BRASÍLIA)
 //   $dataLocal = date('d/m/Y H:i:s', time());
    
    $date_hoje = date('Y-m-d H:i:s', time()); 
    $ip = $_SERVER["REMOTE_ADDR"];
    
    $observacao = $_POST['observacao'];
    $observacao = str_replace("'",'',$observacao);
    $id_plano = $_POST['id_plano'];
    $usuario = $_POST['usuario'];
    $empresa = $_POST['empresa'];
   //echo $id_plano; exit;
  //UPLOAD
    if($observacao){
    $sql = "INSERT INTO sig_planos_historico (usuario, data_envio, observacao, ip, idplanos,  status_msg, tipo) VALUE('$usuario','$date_hoje','$observacao','$ip','$id_plano','0','$tipo')";
    mysqli_query($link, $sql);
    
    // SALVA A NOTIFICAÇÃO
    // VERIFICA QUEM VAI RECEBER A NOTIFICAÇÃO
    $sql_historico = "SELECT idplanos as id_acao, idplano, p.projeto as id_projeto, pa.usuario as responsavel_pa, i.validar_acoes_item as valida_item, i.responsavel as responsavel_item,
                    e.validar_acoes_evento as valida_evento, e.responsavel as responsavel_evento, f.validar_acoes_fase as valida_fase, f.responsavel_aprovacao as responsavel_fase,
                    j.edp_id as coordenador_projeto

                    FROM sig_planos p
                    left join sig_plano_acao    pa on pa.id = p.idplano
                    left join sig_item_evento   i  on i.id = p.eventos
                    left join sig_eventos       e  on e.id = i.evento
                    left join sig_fases_projeto f  on f.id = e.fase_id
                    left join sig_projetos j  on j.id = p.projeto
                    where idplanos = $id_plano ";
    $result_notificacao = mysqli_query($link, $sql_historico); //$link->query($sql_historico);
    $row_notificacao = $result_notificacao->fetch_assoc();
    
    $id_projeto = $row_notificacao["id_projeto"];
    $responsavel_pa = $row_notificacao["responsavel_pa"];
    
    $valida_item = $row_notificacao["valida_item"];
    $responsavel_item = $row_notificacao["responsavel_item"];
    
    $valida_evento = $row_notificacao["valida_evento"];
    $responsavel_evento = $row_notificacao["responsavel_evento"];
    
    $valida_fase = $row_notificacao["valida_fase"];
    $responsavel_fase = $row_notificacao["responsavel_fase"];
    
    $coordenador_projeto = $row_notificacao["coordenador_projeto"];
    
    $title = "Ação : $id_plano ";
    $texto_msg = "Você recebeu uma mensagem da Ação : $id_plano . Acesse o link para visualizar.";
    
    
    if($id_projeto){
        
        
        
        if($valida_item == 1){
            
            $resonsavel_evento_item = 0;
              $sql_notificacoes = "INSERT INTO sig_notifications (id_from,   id_to,     title,      text,          lida, data,  idplano, empresa, networking, project, admin) "
                                       . " VALUE( '$usuario','$responsavel_item', '$title', '$texto_msg', '0', '$date_hoje', '$id_plano','$empresa','1', '1', '0')";
            mysqli_query($link, $sql_notificacoes);  
        }
        
        
        
        if($valida_evento == 1){
                
                //if o responsvael pelo evento for = ao responsavel pelo item, nao envia
                if($responsavel_item == $responsavel_evento) {
                    $resonsavel_evento_item = 1;
                    //ECHO 'já notificou <br>';
                  }else{
                      //envia
                      $resonsavel_evento_item = 0;
                      $sql_notificacoes = "INSERT INTO sig_notifications (id_from,   id_to,     title,      text,          lida, data,  idplano, empresa, networking, project, admin) "
                                               . " VALUE( '$usuario','$responsavel_evento', '$title', '$texto_msg', '0', '$date_hoje', '$id_plano','$empresa','1', '1', '0')";
                    mysqli_query($link, $sql_notificacoes);  
                  }
                
        }else{
            //NÃO VALIDA EVENTO
        }
        
        
        
         // VALIDA A FASE
        if($valida_fase == 1){

            //se os responsaveis do item e do evento forem os mesmos, verifica se o da fase é o mesmo do evento
            if($resonsavel_evento_item == 1){

                if($responsavel_fase == $responsavel_evento) {
                    $resonsavel_fase_evento = 1; // se for o mesmo
                    //echo 'é o mesmo responsável da fase. ja notificou <br>';
                }else{
                    $resonsavel_fase_evento = 0;// se o responsável for diferente

                    // verifica se o da fase é o mesmo do item
                    if($responsavel_fase == $responsavel_item) {
                        //se for o mesmo ele já foi notificado
                        
                    }else{
                        // envia notificação
                        $sql_notificacoes = "INSERT INTO sig_notifications (id_from,   id_to,     title,      text,          lida, data,  idplano, empresa, networking, project, admin) "
                                       . " VALUE( '$usuario','$responsavel_fase', '$title', '$texto_msg', '0', '$date_hoje', '$id_plano','$empresa','1', '1', '0')";
                        mysqli_query($link, $sql_notificacoes);  
                    }

                }
            }else{
                // se os responsáveis do item e evento forem diferentes
                
                if($responsavel_fase == $responsavel_evento) {
                    $resonsavel_fase_evento = 1; // se for o mesmo
                    echo 'é o mesmo responsável do evento. ja notificou <br>';
                }else{
                    $resonsavel_fase_evento = 0;// se o responsável for diferente

                
                    // verifica se o da fase é o mesmo do item
                    if($responsavel_fase == $responsavel_item) {
                        //se for o mesmo ele já foi notificado
                        
                    }else{
                        // envia notificação
                        $sql_notificacoes = "INSERT INTO sig_notifications (id_from,   id_to,     title,      text,          lida, data,  idplano, empresa, networking, project, admin) "
                                       . " VALUE( '$usuario','$responsavel_fase', '$title', '$texto_msg', '0', '$date_hoje', '$id_plano','$empresa','1', '1', '0')";
                        mysqli_query($link, $sql_notificacoes);  
                    }

                }
                
                
                
                
            }


        }else{
            // NÃO VALIDA A FASE
            //echo 'não valida fase'; exit;
        }
        
        
        //exit;
        
    }else{
      $sql_notificacoes = "INSERT INTO sig_notifications (id_from,   id_to,     title,      text,          lida, data,  idplano, empresa, networking, project, admin) "
                                               . " VALUE( '$usuario','$responsavel_pa', '$title', '$texto_msg', '0', '$date_hoje', '$id_plano','$empresa','1', '1', '0')";
     mysqli_query($link, $sql_notificacoes);  
    }
    
   // 
    
    }
   // echo 'Salvo!';
   
}

?>

<meta charset="utf-8">

<?php 
    $sql_historico = "SELECT * FROM sig_planos_historico p inner join sig_users u on u.id = p.usuario where idplanos = $id_plano  order by p.id desc";
    $result = mysqli_query($link, $sql_historico); //$link->query($sql_historico);
    
    if ($result->num_rows > 0) {
     $total_obs = $result->num_rows;    
     while($row = $result->fetch_assoc()) {     
     $tipo = $row["tipo"];    
     if($tipo == 1){
         $label = "primary";
     }else if($tipo == 2){
         $label = "warning";
     }
?>
    <div class="col-md-12"  >
    <div class="form-group">          
        <font class="label label-<?php echo $label; ?>" style="font-size: 10px; font-weight: bold"><?php echo $total_obs--.' - '. $row["first_name"]; ?>  </font> 
          <font class="label label-default" style="font-size: 8px; font-style: italic">( <?php echo date('d/m/Y H:i:s', strtotime($row["data_envio"])); ?> )</font>
          <font style="font-size: 12px;"> : <?php echo $row["observacao"]; ?> </font>
          
      <?php if($row["anexo"] != null){  ?> 
      <font style="font-size: 12px;"><a href="<?= base_url() ?>assets/uploads/historico_acoes/<?php echo $row["anexo"]; ?>" target="_blank"><i class="fa fa-chain"></i>Ver Anexo</a></font><?php } ?>
      </div>
    </div>
 <?php }
    }
    mysqli_close($link);
   ?> 
       