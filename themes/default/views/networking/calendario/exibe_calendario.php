<?php
  /*
     * 1 - INSERT
     * 2 - STATUS = CONCLUIDO
     */
 $opcao = $_GET['opcao'];
 //$id_plano = $_GET['id_plano'];
 //$atualizou = $_GET['atualizou'];
 


 
if (isset($_POST)) {
    include('../../db_connection.php');

    // DEFINE O FUSO HORARIO COMO O HORARIO DE BRASILIA
    date_default_timezone_set('America/Manaus');
// CRIA UMA VARIAVEL E ARMAZENA A HORA ATUAL DO FUSO-HORÀRIO DEFINIDO (BRASÍLIA)
 //   $dataLocal = date('d/m/Y H:i:s', time());
    
    $date_hoje = date('Y-m-d H:i:s', time()); 
    $ip = $_SERVER["REMOTE_ADDR"];
    
   // $descricao_checklist = $_POST['descricao_checklist'];
    $empresa = $_POST['empresa'];
    $usuario = $_POST['usuario'];
   
    $mes_atual = date("m");
    $ano_atual = date("Y");
     
 
  
}


?>



<meta charset="utf-8">

<?php 

   ?> 
 <div id="calendar"></div>      

<script>
  $(function () {

    /* initialize the external events
     -----------------------------------------------------------------*/
    function init_events(ele) {
      ele.each(function () {

        // create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
        // it doesn't need to have a start or end
        var eventObject = {
          title: $.trim($(this).text()) // use the element's text as the event title
        }

        // store the Event Object in the DOM element so we can get to it later
        $(this).data('eventObject', eventObject)

        // make the event draggable using jQuery UI
        $(this).draggable({
          zIndex        : 1070,
          revert        : true, // will cause the event to go back to its
          revertDuration: 0  //  original position after the drag
        })

      })
    }

    init_events($('#external-events div.external-event'))
       
    /* initialize the calendar
     -----------------------------------------------------------------*/
    //Date for the calendar events (dummy data)
    var date = new Date()
    var d    = date.getDate(),
        m    = date.getMonth(),
        y    = date.getFullYear()
    $('#calendar').fullCalendar({
      header    : {
        left  : 'prev,next today',
        center: 'title',
        right : 'month,agendaWeek,agendaDay'
      },
      buttonText: {
        today: 'today',
        month: 'month',
        week : 'week',
        day  : 'day'
      },
   
      //MINHAS AÇÕES
      events    : [
        
        <?php
        if($opcao == 0){
        //1 - MINHA AGENDA
            $sql_minha_agenda = "SELECT * FROM sig_invites i
            inner join sig_users_setor us on us.id = i.user_destino
            where us.users_id = $usuario and i.empresa = $empresa and confirmacao in (1,2)";
            $result_ma = mysqli_query($link, $sql_minha_agenda);
             if ($result_ma->num_rows > 0) {
                   while($row = $result_ma->fetch_assoc()) {     
                    $id_invite = $row["id"]; 
                    $tipo = $row["tipo"];
                    $data_de = $row["data_evento"];
                    $data_ate = $row["data_evento"];
                    
                    $hora_inicio = $row["hora_inicio"];
                    $hora_fim = $row["hora_fim"];
                    
                    $status = $row["status"];
                    
                    /******DATA DE****************************************/
                    $partes_data_de = explode("-", $data_de);
                    $ano_de = $partes_data_de[0];
                    $mes_de = $partes_data_de[1] - 1;
                    $dia_de = $partes_data_de[2];
                    /******DATA ATÉ****************************************/
                    $partes_data_ate = explode("-", $data_ate);
                    $ano_ate = $partes_data_ate[0];
                    $mes_ate = $partes_data_ate[1] - 1;
                    $dia_ate = $partes_data_ate[2] + 1;
                    /*******HORA INICIO***************************************/
                    $partes_hora_inicio = explode(":", $hora_inicio);
                    $hora_inicio = $partes_hora_inicio[0];
                    $min_inicio =  $partes_hora_inicio[1];
                    $seg_inicio =  $partes_hora_inicio[2];
                    /*******HORA TERMINO***************************************/
                    $partes_hora_termino = explode(":", $hora_fim);
                    $hora_fim = $partes_hora_termino[0];
                    $min_fim =  $partes_hora_termino[1];
                    $seg_fim =  $partes_hora_termino[2];
                    /*************************************************************/
                    if($dia_de < 10){
                    $rest_dia_de = substr("$dia_de", -1, 1);
                    $dia_de = $rest_dia_de;
                    }

                    if($mes_de < 10){
                        $rest_mes_de = substr("$mes_de", -1, 1);
                        $mes_de = $rest_mes_de;
                    }
                    
                    if($status == '1'){
                        $backgroud = 'green';
                    }else if($status == '2'){
                        $backgroud = '#3c8dbc';
                    }
//
           
            ?>   
               {
              title          : '<?php echo strip_tags($tipo); ?>',
              //   start          : new Date(y, m, d, 12, 0),
              //end            : new Date(y, m, d, 14, 0),
              start          : new Date(<?php echo $ano_de; ?>, <?php echo $mes_de; ?>, <?php echo $dia_de; ?>, <?php echo $hora_inicio; ?>, <?php echo $min_inicio; ?> ),
              end            : new Date(<?php echo $ano_ate; ?>, <?php echo $mes_ate; ?>, <?php echo $dia_ate; ?>),
              allDay         : false,
              url            : 'index.php/welcome/convites/107/88',
              backgroundColor: '#ca195a', //Primary (light-blue)
              borderColor    : '#ca195a' //Primary (light-blue)
            },
            <?php   }//END WHILE
             }// END IF
             /*************************** fim minha agenda********************************************************/
             
             
         // 2 - MINHAS AÇÕES
            $mes_atual = date("m");
            $ano_atual = date("Y");
            $sql_minhas_acoes = "SELECT idplanos,sequencial, data_entrega_demanda, data_termino, responsavel, descricao, status  FROM sig_planos "
                    . " where responsavel = $usuario and empresa = $empresa and status in('CONCLUÍDO','AGUARDANDO VALIDAÇÃO','PENDENTE') "
                    . " order by data_entrega_demanda asc";
            $result = mysqli_query($link, $sql_minhas_acoes); //$link->query($sql_historico);
            if ($result->num_rows > 0) {
                 $total_obs = 1;    
                 while($row = $result->fetch_assoc()) {     
                    $idplanos = $row["idplanos"]; 
                    $sequencial = $row["sequencial"]; 
                    $titulo = $row["descricao"];
                    $data_de = $row["data_entrega_demanda"];
                    $data_ate = $row["data_termino"];
                    $status = $row["status"];
                    
                    $partes_data_de = explode("-", $data_de);
                    $ano_de = $partes_data_de[0];
                    $mes_de = $partes_data_de[1] - 1;
                    $dia_de = $partes_data_de[2];

                    $partes_data_ate = explode("-", $data_ate);
                    $ano_ate = $partes_data_ate[0];
                    $mes_ate = $partes_data_ate[1] - 1;
                    $dia_ate = $partes_data_ate[2] + 1;
                    
                    if($dia_de < 10){
                    $rest_dia_de = substr("$dia_de", -1, 1);
                    $dia_de = $rest_dia_de;
                    }

                    if($mes_de < 10){
                        $rest_mes_de = substr("$mes_de", -1, 1);
                        $mes_de = $rest_mes_de;
                    }
                    
                    if($status == 'CONCLUÍDO'){
                        $backgroud = 'green';
                    }else if($status == 'AGUARDANDO VALIDAÇÃO'){
                        $backgroud = '#3c8dbc';
                    }else if($status == 'PENDENTE'){
                        $backgroud = 'orange';
                    }
                    $backgroud = 'orange';
           
        ?>   
           {
          title          : '<?php echo 'Ação '.$sequencial.' - '. strip_tags($titulo); ?>',
          start          : new Date(<?php echo $ano_de; ?>, <?php echo $mes_de; ?>, <?php echo $dia_de; ?>),
          end            : new Date(<?php echo $ano_ate; ?>, <?php echo $mes_ate; ?>, <?php echo $dia_ate; ?>),
          allDay         : true,
          url            : 'index.php/welcome/consultar_acao/<?php echo $idplanos; ?>',
          backgroundColor: '<?php echo $backgroud; ?>', //Primary (light-blue)
          borderColor    : '<?php echo $backgroud; ?>' //Primary (light-blue)
        },
        <?php   }//END WHILE
           }//END IF   - END MINHAS AÇÕES  
         
         // ------------------------------------------ FIM MINHAS AÇÕES ----------------------------------------
           
            // 3 - MARCOS DO PROJETO
             $sql_marcos_projeto = "
            -- EQUIPE DO PROJETO
            SELECT distinct m.projetos_id, p.projeto as projeto, m.empresa_id,m.descricao,m.data_prevista
            FROM sig_projetos_marcos m
            inner join sig_projetos p on p.id = m.projetos_id
            inner join sig_projetos_equipes e on e.projeto_id = m.projetos_id
            where e.user_responsavel = $usuario and e.empresa_id = $empresa

            UNION
            -- PARTES INTERESSADA
            SELECT distinct m.projetos_id, p.projeto as projeto, m.empresa_id,m.descricao,m.data_prevista
            FROM sig_projetos_marcos m
            inner join sig_projetos p on p.id = m.projetos_id
            inner join sig_projetos_partes_interessadas i on i.projetos_id = m.projetos_id
            where i.usuario_interessado = $usuario and i.empresa_id = $empresa

            UNION
            -- GERENTE DO PROJETO
            SELECT distinct m.projetos_id, p.projeto as projeto, m.empresa_id,m.descricao,m.data_prevista
            FROM sig_projetos_marcos m
            inner join sig_projetos p on p.id = m.projetos_id
            inner join sig_users_setor us on us.id = p.gerente_area
            where us.users_id = $usuario and p.empresa_id = $empresa

            UNION
            -- COORDENADOR DO PROJETO
            SELECT distinct m.projetos_id, p.projeto as projeto, m.empresa_id,m.descricao,m.data_prevista
            FROM sig_projetos_marcos m
            inner join sig_projetos p on p.id = m.projetos_id
            inner join sig_users_setor us on us.id = p.edp_id
            where us.users_id = $usuario and p.empresa_id = $empresa";

            $result_marcos = mysqli_query($link, $sql_marcos_projeto); //$link->query($sql_historico);
            if ($result_marcos->num_rows > 0) {
                 $total_obs = 1;    
                 while($row = $result_marcos->fetch_assoc()) {     
                    $titulo = $row["descricao"];
                    $projeto = $row["projeto"];
                    $data_de = $row["data_prevista"];
                    $data_ate = $row["data_prevista"];

                        $partes_data_de = explode("-", $data_de);
                        $ano_de = $partes_data_de[0];
                        $mes_de = $partes_data_de[1] - 1;
                        $dia_de = $partes_data_de[2];

                        $partes_data_ate = explode("-", $data_ate);
                        $ano_ate = $partes_data_ate[0];
                        $mes_ate = $partes_data_ate[1] - 1;
                        $dia_ate = $partes_data_ate[2] + 1;

                        if($dia_de < 10){
                        $rest_dia_de = substr("$dia_de", -1, 1);
                        $dia_de = $rest_dia_de;
                        }

                        if($mes_de < 10){
                            $rest_mes_de = substr("$mes_de", -1, 1);
                            $mes_de = $rest_mes_de;
                        }


                        $backgroud = '#0000FF';



            ?>   
               {
              title          : '<?php echo $titulo. ' - '. $projeto; ?>',
              start          : new Date(<?php echo $ano_de; ?>, <?php echo $mes_de; ?>, <?php echo $dia_de; ?>),
              end            : new Date(<?php echo $ano_ate; ?>, <?php echo $mes_ate; ?>, <?php echo $dia_ate; ?>),
              allDay         : true,

              backgroundColor: '<?php echo $backgroud; ?>', //Primary (light-blue)
              borderColor    : '#3c8dbc' //Primary (light-blue)
            },
            <?php   }//END WHILE
               }//END IF   
           
           // ----------------------------------- FIM DO MARCOS DO PROJETO -----------------------------   //
            
           //PLANO DE AÇÃO
            $sql_plano_acao = "SELECT * FROM sig_plano_acao where networking = 1 and empresa = $empresa and usuario = $usuario";
            $result_pa = mysqli_query($link, $sql_plano_acao);
             if ($result_pa->num_rows > 0) {
                   while($row = $result_pa->fetch_assoc()) {     
                    $id_plano = $row["id"]; 
                    //$tipo = $row["tipo"];
                    $data_de = $row["data_pa"];
                    $data_ate = $row["data_termino_previsto"];
                    $titulo = $row["assunto"];
                    $status = $row["status"];
                    
                    $partes_data_de = explode("-", $data_de);
                    $ano_de = $partes_data_de[0];
                    $mes_de = $partes_data_de[1] - 1;
                    $dia_de = $partes_data_de[2];

                    $partes_data_ate = explode("-", $data_ate);
                    $ano_ate = $partes_data_ate[0];
                    $mes_ate = $partes_data_ate[1] - 1;
                    $dia_ate = $partes_data_ate[2] + 1;
                    
                    if($dia_de < 10){
                    $rest_dia_de = substr("$dia_de", -1, 1);
                    $dia_de = $rest_dia_de;
                    }

                    if($mes_de < 10){
                        $rest_mes_de = substr("$mes_de", -1, 1);
                        $mes_de = $rest_mes_de;
                    }
                    
                    if($status == '1'){
                        $backgroud = 'gray';
                    }else {
                        $backgroud = 'gray';
                    }

                    
                    
           
        ?>   
           {
          title          : '<?php echo 'P.A.  '.$id_plano.' : '. $titulo; ?>',
          start          : new Date(<?php echo $ano_de; ?>, <?php echo $mes_de; ?>, <?php echo $dia_de; ?>),
          end            : new Date(<?php echo $ano_ate; ?>, <?php echo $mes_ate; ?>, <?php echo $dia_ate; ?>),
          allDay         : true,
          backgroundColor: '<?php echo $backgroud; ?>', //Primary (light-blue)
          borderColor    : '<?php echo $backgroud; ?>' //Primary (light-blue)
        },
        <?php   }//END WHILE
           }//END IF 
         // ----------------------------------- FIM DO PLANO DE AÇÃO -----------------------------   //
         
               
               
            // FERIADOS   
            $sql_feriados = "SELECT *  FROM sig_feriados";
            $result_feriados = mysqli_query($link, $sql_feriados); //$link->query($sql_historico);
            if ($result_feriados->num_rows > 0) {
             $total_obs = 1;    
             while($row = $result_feriados->fetch_assoc()) {     
                $titulo = $row["descricao"];
                $data_de = $row["data_feriado"];
                $data_ate = $row["data_feriado"];
                    
                    $partes_data_de = explode("-", $data_de);
                    $ano_de = $partes_data_de[0];
                    $mes_de = $partes_data_de[1] - 1;
                    $dia_de = $partes_data_de[2];

                    $partes_data_ate = explode("-", $data_ate);
                    $ano_ate = $partes_data_ate[0];
                    $mes_ate = $partes_data_ate[1] - 1;
                    $dia_ate = $partes_data_ate[2] + 1;
                    
                    if($dia_de < 10){
                    $rest_dia_de = substr("$dia_de", -1, 1);
                    $dia_de = $rest_dia_de;
                    }

                    if($mes_de < 10){
                        $rest_mes_de = substr("$mes_de", -1, 1);
                        $mes_de = $rest_mes_de;
                    }
                    
                    
                    $backgroud = 'red';
                    
           
        ?>   
           {
          title          : '<?php echo $titulo; ?>',
          start          : new Date(<?php echo $ano_de; ?>, <?php echo $mes_de; ?>, <?php echo $dia_de; ?>),
          end            : new Date(<?php echo $ano_ate; ?>, <?php echo $mes_ate; ?>, <?php echo $dia_ate; ?>),
          allDay         : true,
          backgroundColor: '<?php echo $backgroud; ?>', //Primary (light-blue)
          borderColor    : 'red' //Primary (light-blue)
        },
        <?php   }//END WHILE
           }//END IF 
           
           // --------------------- FIM FERIADOS ----------------------------------------------------
           
           
           // TAREFAS
            $sql_tarefas = "SELECT * FROM sig_tarefas where user = $usuario and empresa = $empresa and data_inicio != '' and data_termino != '' ";
            $result_tarefas = mysqli_query($link, $sql_tarefas); //$link->query($sql_historico);
            if ($result_tarefas->num_rows > 0) {
                 $total_obs = 1;    
                 while($row = $result_tarefas->fetch_assoc()) {     
                    $titulo = $row["descricao"];
                    $data_de = $row["data_inicio"];
                    $data_ate = $row["data_termino"];

                    $partes_data_de = explode("-", $data_de);
                    $ano_de = $partes_data_de[0];
                    $mes_de = $partes_data_de[1] - 1;
                    $dia_de = $partes_data_de[2];

                    $partes_data_ate = explode("-", $data_ate);
                    $ano_ate = $partes_data_ate[0];
                    $mes_ate = $partes_data_ate[1] - 1;
                    $dia_ate = $partes_data_ate[2] + 1;

                    if($dia_de < 10){
                    $rest_dia_de = substr("$dia_de", -1, 1);
                    $dia_de = $rest_dia_de;
                    }

                    if($mes_de < 10){
                        $rest_mes_de = substr("$mes_de", -1, 1);
                        $mes_de = $rest_mes_de;
                    }


                    $backgroud = 'black';



            ?>   
               {
              title          : '<?php echo $titulo; ?>',
              start          : new Date(<?php echo $ano_de; ?>, <?php echo $mes_de; ?>, <?php echo $dia_de; ?>),
              end            : new Date(<?php echo $ano_ate; ?>, <?php echo $mes_ate; ?>, <?php echo $dia_ate; ?>),
              allDay         : true,

              backgroundColor: '<?php echo $backgroud; ?>', //Primary (light-blue)
              borderColor    : 'black' //Primary (light-blue)
            },
            <?php   }//END WHILE
               }//END IF 
           // --------------------- FIM TAREFAS ----------------------------------------------------
           
               
           // ANIVERSARIANTES
           $sql_aniversario = "SELECT first_name, data_aniversario FROM sig_users where empresa_id = $empresa and data_aniversario != ''";
        $result_aniversario = mysqli_query($link, $sql_aniversario); //$link->query($sql_historico);
        if ($result_aniversario->num_rows > 0) {
             $total_obs = 1;    
             while($row = $result_aniversario->fetch_assoc()) {    
                $nome = $row["first_name"]; 
                $titulo = "Niver - $nome";
                $data_de = $row["data_aniversario"];
                $data_ate = $row["data_aniversario"];
                    
                    $partes_data_de = explode("-", $data_de);
                    $ano_de = $partes_data_de[0];
                    $mes_de = $partes_data_de[1] - 1;
                    $dia_de = $partes_data_de[2];

                    $partes_data_ate = explode("-", $data_ate);
                    $ano_ate = $partes_data_ate[0];
                    $mes_ate = $partes_data_ate[1] - 1;
                    $dia_ate = $partes_data_ate[2] + 1;
                    
                    if($dia_de < 10){
                    $rest_dia_de = substr("$dia_de", -1, 1);
                    $dia_de = $rest_dia_de;
                    }

                    if($mes_de < 10){
                        $rest_mes_de = substr("$mes_de", -1, 1);
                        $mes_de = $rest_mes_de;
                    }
                    
                    
                    $backgroud = '#088A68';
                    
           
        ?>   
           {
          title          : '<?php echo $titulo; ?>',
          start          : new Date(<?php echo $ano_de; ?>, <?php echo $mes_de; ?>, <?php echo $dia_de; ?>),
          end            : new Date(<?php echo $ano_ate; ?>, <?php echo $mes_ate; ?>, <?php echo $dia_ate; ?>),
          allDay         : true,
          backgroundColor: '<?php echo $backgroud; ?>', //Primary (light-blue)
          borderColor    : '#088A68' //Primary (light-blue)
        },
        <?php   }//END WHILE
           }//END IF 
           
           // --------------------- FIM ANIVERSARIANTES----------------------------------------------------
        ?>
         
          
                        
        <?php
        }
        
        //MINHA AGENDA
        else if($opcao == 1){
            $sql_minha_agenda = "SELECT * FROM sig_invites i
            inner join sig_users_setor us on us.id = i.user_destino
            where us.users_id = $usuario and i.empresa = $empresa and confirmacao in (1,2)";
            $result_ma = mysqli_query($link, $sql_minha_agenda);
             if ($result_ma->num_rows > 0) {
                   while($row = $result_ma->fetch_assoc()) {     
                    $id_invite = $row["id"]; 
                    $tipo = $row["tipo"];
                    $data_de = $row["data_evento"];
                    $data_ate = $row["data_evento"];
                    
                     $hora_inicio = $row["hora_inicio"];
                    $hora_fim = $row["hora_fim"];
                    
                    $status = $row["status"];
                    
                    $partes_data_de = explode("-", $data_de);
                    $ano_de = $partes_data_de[0];
                    $mes_de = $partes_data_de[1] - 1;
                    $dia_de = $partes_data_de[2];

                    $partes_data_ate = explode("-", $data_ate);
                    $ano_ate = $partes_data_ate[0];
                    $mes_ate = $partes_data_ate[1] - 1;
                    $dia_ate = $partes_data_ate[2] + 1;
                    /*******HORA INICIO***************************************/
                    $partes_hora_inicio = explode(":", $hora_inicio);
                    $hora_inicio = $partes_hora_inicio[0];
                    $min_inicio =  $partes_hora_inicio[1];
                    $seg_inicio =  $partes_hora_inicio[2];
                    /*******HORA TERMINO***************************************/
                    $partes_hora_termino = explode(":", $hora_fim);
                    $hora_fim = $partes_hora_termino[0];
                    $min_fim =  $partes_hora_termino[1];
                    $seg_fim =  $partes_hora_termino[2];
                    /*************************************************************/
                    
                    if($dia_de < 10){
                    $rest_dia_de = substr("$dia_de", -1, 1);
                    $dia_de = $rest_dia_de;
                    }

                    if($mes_de < 10){
                        $rest_mes_de = substr("$mes_de", -1, 1);
                        $mes_de = $rest_mes_de;
                    }
                    
                    if($status == '1'){
                        $backgroud = 'green';
                    }else if($status == '2'){
                        $backgroud = '#3c8dbc';
                    }

           
        ?>   
           {
          title          : '<?php echo strip_tags($tipo); ?>',
          start          : new Date(<?php echo $ano_de; ?>, <?php echo $mes_de; ?>, <?php echo $dia_de; ?>, <?php echo $hora_inicio; ?>, <?php echo $min_inicio; ?> ),
          end            : new Date(<?php echo $ano_ate; ?>, <?php echo $mes_ate; ?>, <?php echo $dia_ate; ?>),
          allDay         : false,
          url            : 'index.php/welcome/convites/107/88',
          backgroundColor: '#ca195a', //Primary (light-blue)
          borderColor    : '#ca195a' //Primary (light-blue)
        },
        <?php   }//END WHILE
             }// END IF
            
        }// MINHA AGENDA
        
        else if($opcao == 2){
            //MINHAS AÇÕES

            $mes_atual = date("m");
            $ano_atual = date("Y");
            $sql_minhas_acoes = "SELECT idplanos,sequencial, data_entrega_demanda, data_termino, responsavel, descricao, status  FROM sig_planos "
                    . " where responsavel = $usuario and empresa = $empresa and status in('CONCLUÍDO','AGUARDANDO VALIDAÇÃO','PENDENTE') "
                    . " order by data_entrega_demanda asc";
            
            $result = mysqli_query($link, $sql_minhas_acoes); //$link->query($sql_historico);
            if ($result->num_rows > 0) {
                 $total_obs = 1;    
                 while($row = $result->fetch_assoc()) {     
                    $idplanos = $row["idplanos"]; 
                    $sequencial = $row["sequencial"]; 
                    $titulo = $row["descricao"];
                    $data_de = $row["data_entrega_demanda"];
                    $data_ate = $row["data_termino"];
                    $status = $row["status"];
                    
                    $partes_data_de = explode("-", $data_de);
                    $ano_de = $partes_data_de[0];
                    $mes_de = $partes_data_de[1] - 1;
                    $dia_de = $partes_data_de[2];

                    $partes_data_ate = explode("-", $data_ate);
                    $ano_ate = $partes_data_ate[0];
                    $mes_ate = $partes_data_ate[1] - 1;
                    $dia_ate = $partes_data_ate[2] + 1;
                    
                    if($dia_de < 10){
                    $rest_dia_de = substr("$dia_de", -1, 1);
                    $dia_de = $rest_dia_de;
                    }

                    if($mes_de < 10){
                        $rest_mes_de = substr("$mes_de", -1, 1);
                        $mes_de = $rest_mes_de;
                    }
                    
                    if($status == 'CONCLUÍDO'){
                        $backgroud = 'green';
                    }else if($status == 'AGUARDANDO VALIDAÇÃO'){
                        $backgroud = '#3c8dbc';
                    }else if($status == 'PENDENTE'){
                        $backgroud = 'orange';
                    }

           
        ?>   
           {
          title          : '<?php echo $sequencial.' - '. strip_tags($titulo); ?>',
          start          : new Date(<?php echo $ano_de; ?>, <?php echo $mes_de; ?>, <?php echo $dia_de; ?>),
          end            : new Date(<?php echo $ano_ate; ?>, <?php echo $mes_ate; ?>, <?php echo $dia_ate; ?>),
          allDay         : true,
          url            : 'index.php/welcome/consultar_acao/<?php echo $idplanos; ?>',
          backgroundColor: 'orange', //Primary (light-blue)
          borderColor    : 'orange' //Primary (light-blue)
        },
        <?php   }//END WHILE
           }//END IF 
        } //FIM MINHAS AÇÕES
        
        // MINHAS TAREFAS
        else if($opcao == 3){
            
        $mes_atual = date("m");
        $ano_atual = date("Y");
        $sql_tarefas = "SELECT * FROM sig_tarefas where user = $usuario and empresa = $empresa and data_inicio != '' and data_termino != '' ";
        $result_tarefas = mysqli_query($link, $sql_tarefas); //$link->query($sql_historico);
        if ($result_tarefas->num_rows > 0) {
             $total_obs = 1;    
             while($row = $result_tarefas->fetch_assoc()) {     
                $titulo = $row["descricao"];
                $data_de = $row["data_inicio"];
                $data_ate = $row["data_termino"];
                    
                $partes_data_de = explode("-", $data_de);
                $ano_de = $partes_data_de[0];
                $mes_de = $partes_data_de[1] - 1;
                $dia_de = $partes_data_de[2];

                $partes_data_ate = explode("-", $data_ate);
                $ano_ate = $partes_data_ate[0];
                $mes_ate = $partes_data_ate[1] - 1;
                $dia_ate = $partes_data_ate[2] + 1;

                if($dia_de < 10){
                $rest_dia_de = substr("$dia_de", -1, 1);
                $dia_de = $rest_dia_de;
                }

                if($mes_de < 10){
                    $rest_mes_de = substr("$mes_de", -1, 1);
                    $mes_de = $rest_mes_de;
                }


                $backgroud = 'black';
                   

           
        ?>   
           {
          title          : '<?php echo $titulo; ?>',
          start          : new Date(<?php echo $ano_de; ?>, <?php echo $mes_de; ?>, <?php echo $dia_de; ?>),
          end            : new Date(<?php echo $ano_ate; ?>, <?php echo $mes_ate; ?>, <?php echo $dia_ate; ?>),
          allDay         : true,
        
          backgroundColor: '<?php echo $backgroud; ?>', //Primary (light-blue)
          borderColor    : 'black' //Primary (light-blue)
        },
        <?php   }//END WHILE
           }//END IF 
        }// END TAREFAS
        
        // MARCOS DO PROJETO
        else if($opcao == 4){
            
        $mes_atual = date("m");
        $ano_atual = date("Y");
        $sql_marcos_projeto = "
        -- EQUIPE DO PROJETO
        SELECT distinct m.projetos_id, p.projeto as projeto, m.empresa_id,m.descricao,m.data_prevista
        FROM sig_projetos_marcos m
        inner join sig_projetos p on p.id = m.projetos_id
        inner join sig_projetos_equipes e on e.projeto_id = m.projetos_id
        where e.user_responsavel = $usuario and e.empresa_id = $empresa

        UNION
        -- PARTES INTERESSADA
        SELECT distinct m.projetos_id, p.projeto as projeto, m.empresa_id,m.descricao,m.data_prevista
        FROM sig_projetos_marcos m
        inner join sig_projetos p on p.id = m.projetos_id
        inner join sig_projetos_partes_interessadas i on i.projetos_id = m.projetos_id
        where i.usuario_interessado = $usuario and i.empresa_id = $empresa

        UNION
        -- GERENTE DO PROJETO
        SELECT distinct m.projetos_id, p.projeto as projeto, m.empresa_id,m.descricao,m.data_prevista
        FROM sig_projetos_marcos m
        inner join sig_projetos p on p.id = m.projetos_id
        inner join sig_users_setor us on us.id = p.gerente_area
        where us.users_id = $usuario and p.empresa_id = $empresa

        UNION
        -- COORDENADOR DO PROJETO
        SELECT distinct m.projetos_id, p.projeto as projeto, m.empresa_id,m.descricao,m.data_prevista
        FROM sig_projetos_marcos m
        inner join sig_projetos p on p.id = m.projetos_id
        inner join sig_users_setor us on us.id = p.edp_id
        where us.users_id = $usuario and p.empresa_id = $empresa";
        
        $result_marcos = mysqli_query($link, $sql_marcos_projeto); //$link->query($sql_historico);
        if ($result_marcos->num_rows > 0) {
             $total_obs = 1;    
             while($row = $result_marcos->fetch_assoc()) {     
                $titulo = $row["descricao"];
                $data_de = $row["data_prevista"];
                $data_ate = $row["data_prevista"];
                $projeto = $row["projeto"];    
                    $partes_data_de = explode("-", $data_de);
                    $ano_de = $partes_data_de[0];
                    $mes_de = $partes_data_de[1] - 1;
                    $dia_de = $partes_data_de[2];

                    $partes_data_ate = explode("-", $data_ate);
                    $ano_ate = $partes_data_ate[0];
                    $mes_ate = $partes_data_ate[1] - 1;
                    $dia_ate = $partes_data_ate[2] + 1;
                    
                    if($dia_de < 10){
                    $rest_dia_de = substr("$dia_de", -1, 1);
                    $dia_de = $rest_dia_de;
                    }

                    if($mes_de < 10){
                        $rest_mes_de = substr("$mes_de", -1, 1);
                        $mes_de = $rest_mes_de;
                    }
                    
                  
                    $backgroud = '#0000FF';
                   

           
        ?>   
           {
          title          : '<?php echo $titulo. ' - '. $projeto; ?>',
          start          : new Date(<?php echo $ano_de; ?>, <?php echo $mes_de; ?>, <?php echo $dia_de; ?>),
          end            : new Date(<?php echo $ano_ate; ?>, <?php echo $mes_ate; ?>, <?php echo $dia_ate; ?>),
          allDay         : true,
        
          backgroundColor: '<?php echo $backgroud; ?>', //Primary (light-blue)
          borderColor    : '#3c8dbc' //Primary (light-blue)
        },
        <?php   }//END WHILE
           }//END IF 
        }// END MARCOS
        
        // PLANO DE AÇÃO
        else if ($opcao == 5){
       
            $sql_plano_acao = "SELECT * FROM sig_plano_acao where networking = 1 and empresa = $empresa and usuario = $usuario";
            $result_pa = mysqli_query($link, $sql_plano_acao);
             if ($result_pa->num_rows > 0) {
                   while($row = $result_pa->fetch_assoc()) {     
                    $id_plano = $row["id"]; 
                    //$tipo = $row["tipo"];
                    $data_de = $row["data_pa"];
                    $data_ate = $row["data_termino_previsto"];
                    $titulo = $row["assunto"];
                    $status = $row["status"];
                    
                    $partes_data_de = explode("-", $data_de);
                    $ano_de = $partes_data_de[0];
                    $mes_de = $partes_data_de[1] - 1;
                    $dia_de = $partes_data_de[2];

                    $partes_data_ate = explode("-", $data_ate);
                    $ano_ate = $partes_data_ate[0];
                    $mes_ate = $partes_data_ate[1] - 1;
                    $dia_ate = $partes_data_ate[2] + 1;
                    
                    if($dia_de < 10){
                    $rest_dia_de = substr("$dia_de", -1, 1);
                    $dia_de = $rest_dia_de;
                    }

                    if($mes_de < 10){
                        $rest_mes_de = substr("$mes_de", -1, 1);
                        $mes_de = $rest_mes_de;
                    }
                    
                    if($status == '1'){
                        $backgroud = 'gray';
                    }else {
                        $backgroud = 'gray';
                    }

                    
                    
           
        ?>   
           {
          title          : '<?php echo 'P.A.  '.$id_plano.' : '. $titulo; ?>',
          start          : new Date(<?php echo $ano_de; ?>, <?php echo $mes_de; ?>, <?php echo $dia_de; ?>),
          end            : new Date(<?php echo $ano_ate; ?>, <?php echo $mes_ate; ?>, <?php echo $dia_ate; ?>),
          allDay         : true,
          backgroundColor: '<?php echo $backgroud; ?>', //Primary (light-blue)
          borderColor    : '<?php echo $backgroud; ?>' //Primary (light-blue)
        },
        <?php   }//END WHILE
           }//END IF 
        }// END PLANO DE AÇÃO
        
        // FERIADOS
        else if ($opcao == 6){
            $sql_feriados = "SELECT *  FROM sig_feriados";
            $result_feriados = mysqli_query($link, $sql_feriados); //$link->query($sql_historico);
            if ($result_feriados->num_rows > 0) {
             $total_obs = 1;    
             while($row = $result_feriados->fetch_assoc()) {     
                $titulo = $row["descricao"];
                $data_de = $row["data_feriado"];
                $data_ate = $row["data_feriado"];
                    
                    $partes_data_de = explode("-", $data_de);
                    $ano_de = $partes_data_de[0];
                    $mes_de = $partes_data_de[1] - 1;
                    $dia_de = $partes_data_de[2];

                    $partes_data_ate = explode("-", $data_ate);
                    $ano_ate = $partes_data_ate[0];
                    $mes_ate = $partes_data_ate[1] - 1;
                    $dia_ate = $partes_data_ate[2] + 1;
                    
                    if($dia_de < 10){
                    $rest_dia_de = substr("$dia_de", -1, 1);
                    $dia_de = $rest_dia_de;
                    }

                    if($mes_de < 10){
                        $rest_mes_de = substr("$mes_de", -1, 1);
                        $mes_de = $rest_mes_de;
                    }
                    
                    
                    $backgroud = 'red';
                    
           
        ?>   
           {
          title          : '<?php echo $titulo; ?>',
          start          : new Date(<?php echo $ano_de; ?>, <?php echo $mes_de; ?>, <?php echo $dia_de; ?>),
          end            : new Date(<?php echo $ano_ate; ?>, <?php echo $mes_ate; ?>, <?php echo $dia_ate; ?>),
          allDay         : true,
          backgroundColor: '<?php echo $backgroud; ?>', //Primary (light-blue)
          borderColor    : 'red' //Primary (light-blue)
        },
        <?php   }//END WHILE
           }//END IF 
        }// END FERIADOS
        
        // ANIVERSARIANTES
        else if ($opcao == 7){
        $sql_aniversario = "SELECT first_name, data_aniversario FROM sig_users where empresa_id = $empresa and data_aniversario != ''";
        $result_aniversario = mysqli_query($link, $sql_aniversario); //$link->query($sql_historico);
        if ($result_aniversario->num_rows > 0) {
             $total_obs = 1;    
             while($row = $result_aniversario->fetch_assoc()) {    
                $nome = $row["first_name"]; 
                $titulo = "Niver - $nome";
                $data_de = $row["data_aniversario"];
                $data_ate = $row["data_aniversario"];
                    
                    $partes_data_de = explode("-", $data_de);
                    $ano_de = $partes_data_de[0];
                    $mes_de = $partes_data_de[1] - 1;
                    $dia_de = $partes_data_de[2];

                    $partes_data_ate = explode("-", $data_ate);
                    $ano_ate = $partes_data_ate[0];
                    $mes_ate = $partes_data_ate[1] - 1;
                    $dia_ate = $partes_data_ate[2] + 1;
                    
                    if($dia_de < 10){
                    $rest_dia_de = substr("$dia_de", -1, 1);
                    $dia_de = $rest_dia_de;
                    }

                    if($mes_de < 10){
                        $rest_mes_de = substr("$mes_de", -1, 1);
                        $mes_de = $rest_mes_de;
                    }
                    
                    
                    $backgroud = '#088A68';
                    
           
        ?>   
           {
          title          : '<?php echo $titulo; ?>',
          start          : new Date(<?php echo $ano_de; ?>, <?php echo $mes_de; ?>, <?php echo $dia_de; ?>),
          end            : new Date(<?php echo $ano_ate; ?>, <?php echo $mes_ate; ?>, <?php echo $dia_ate; ?>),
          allDay         : true,
          backgroundColor: '<?php echo $backgroud; ?>', //Primary (light-blue)
          borderColor    : '#088A68' //Primary (light-blue)
        },
        <?php   }//END WHILE
           }//END IF 
        }// END ANIVERSARIANTES
        ?>
        
        
      ],
      
      /*
      events    : [
        {
          title          : 'All Day Event',
          start          : new Date(y, m, 1),
          backgroundColor: '#f56954', //red
          borderColor    : '#f56954' //red
        },
        {
          title          : 'Long Event',
          start          : new Date(y, m, 5),
          end            : new Date(y, m, 20),
          backgroundColor: '#f39c12', //yellow
          borderColor    : '#f39c12' //yellow
        },
        {
          title          : 'Meeting',
          start          : new Date(y, m, d, 10, 30),
          allDay         : false,
          backgroundColor: '#0073b7', //Blue
          borderColor    : '#0073b7' //Blue
        },
        {
          title          : 'Lunch',
          start          : new Date(y, m, d, 12, 0),
          end            : new Date(y, m, d, 14, 0),
          allDay         : false,
          backgroundColor: '#00c0ef', //Info (aqua)
          borderColor    : '#00c0ef' //Info (aqua)
        },
        {
          title          : 'Birthday Party',
          start          : new Date(y, m, d + 1, 19, 0),
          end            : new Date(y, m, d + 1, 22, 30),
          allDay         : false,
          backgroundColor: '#00a65a', //Success (green)
          borderColor    : '#00a65a' //Success (green)
        },
        {
          title          : 'Click for Google',
          start          : new Date(y, m, 26),
          end            : new Date(y, m, 29),
          url            : 'http://google.com/',
          backgroundColor: '#3c8dbc', //Primary (light-blue)
          borderColor    : '#3c8dbc' //Primary (light-blue)
        }
      ],
        */
      
      
      
      editable  : true,
      droppable : true, // this allows things to be dropped onto the calendar !!!
      drop      : function (date, allDay) { // this function is called when something is dropped

        // retrieve the dropped element's stored Event Object
        var originalEventObject = $(this).data('eventObject')

        // we need to copy it, so that multiple events don't have a reference to the same object
        var copiedEventObject = $.extend({}, originalEventObject)

        // assign it the date that was reported
        copiedEventObject.start           = date
        copiedEventObject.allDay          = allDay
        copiedEventObject.backgroundColor = $(this).css('background-color')
        copiedEventObject.borderColor     = $(this).css('border-color')

        // render the event on the calendar
        // the last `true` argument determines if the event "sticks" (http://arshaw.com/fullcalendar/docs/event_rendering/renderEvent/)
        $('#calendar').fullCalendar('renderEvent', copiedEventObject, true)

        // is the "remove after drop" checkbox checked?
        if ($('#drop-remove').is(':checked')) {
          // if so, remove the element from the "Draggable Events" list
          $(this).remove()
        }

      }
    })

    /* ADDING EVENTS */
    var currColor = '#3c8dbc' //Red by default
    //Color chooser button
    var colorChooser = $('#color-chooser-btn')
    $('#color-chooser > li > a').click(function (e) {
      e.preventDefault()
      //Save color
      currColor = $(this).css('color')
      //Add color effect to button
      $('#add-new-event').css({ 'background-color': currColor, 'border-color': currColor })
    })
    $('#add-new-event').click(function (e) {
      e.preventDefault()
      //Get value and make sure it is not null
      var val = $('#new-event').val()
      if (val.length == 0) {
        return
      }

      //Create events
      var event = $('<div />')
      event.css({
        'background-color': currColor,
        'border-color'    : currColor,
        'color'           : '#fff'
      }).addClass('external-event')
      event.html(val)
      $('#external-events').prepend(event)

      //Add draggable funtionality
      init_events(event)

      //Remove event from text input
      $('#new-event').val('')
    })
  })
</script>