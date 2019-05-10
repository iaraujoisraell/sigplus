<?php
  /*
     * 1 - INSERT
     * 2 - STATUS = CONCLUIDO
     */
 $tipo_operacao = $_GET['tipo'];
 

if (isset($_POST)) {
    include('../../../db_connection.php');

    // DEFINE O FUSO HORARIO COMO O HORARIO DE BRASILIA
    date_default_timezone_set('America/Manaus');
// CRIA UMA VARIAVEL E ARMAZENA A HORA ATUAL DO FUSO-HORÀRIO DEFINIDO (BRASÍLIA)
 //   $dataLocal = date('d/m/Y H:i:s', time());
    
    $date_hoje = date('Y-m-d H:i:s', time()); 
    $ip = $_SERVER["REMOTE_ADDR"];
    
 // Repassa a variável do upload
    $arquivo = isset($_FILES["document"]) ? $_FILES["document"] : FALSE;
 
    $ata = $_POST['ata'];
   //echo 'ata : '.$ata;
  //  $id_plano = $_POST['id_plano'];
  //  $usuario = $_POST['usuario'];
   
    
  
if($tipo_operacao == 4){
      $id_check = $_GET['id_check'];
     
      $sql = "DELETE FROM sig_atas_audios where id = $id_check";   
      if ($link->query($sql) === TRUE) {
      //  echo "Registro Apagado com Sucesso!";
        } else {
       // echo "Error deleting record: " . $link->error;
        }
        
    }    
   // echo 'Salvo!';
   
}

?>
 <script>
        function exibe_andamento(plano) {
              $.ajax({
                type: "POST",
                url: "themes/default/views/networking/minhas_acoes/exibe_andamento.php?tipo=0&id_plano="+plano+"&atualizou=0",
                
                success: function(data) {
                  $('#audio_div').html(data);
                 
                }

              });

            }
    </script>    
    
<script type="text/javascript">
   

function div_check_list_delete(id_registro, ata) {
      $.ajax({
        type: "POST",
        url: "themes/default/views/networking/ata/audio/exibe_audios.php?tipo=4&id_check="+id_registro,
        data: {
          ata: ata
        },
        success: function(data) {
          $('#audio_div').html(data);

        }

      });
    
    }
    
     
    </script>
<meta charset="utf-8">

<?php 

    $sql_historico = "SELECT * FROM sig_atas_audios where atasid = $ata  order by id asc";
    $result = mysqli_query($link, $sql_historico); //$link->query($sql_historico);
    
    if ($result->num_rows > 0) {
     $total_obs = 1;    
     while($row = $result->fetch_assoc()) {     
    
         $arquivo = $row["arquivo"];
?>
    
        <div class="box-body">
            
            <!-- See dist/js/pages/dashboard.js to activate the todoList plugin -->
            <ul class="todo-list">
                <li>     
                    <font style="font-size: 16px;">  <?php  echo $total_obs++; ?> </font>
                    <font style="font-size: 16px;"> : <?php echo strip_tags($row["arquivo"]); ?> </font>
                    <font class="label label-default" style="font-size: 14px;"> <?php echo date('d/m/Y', strtotime($row["data_registro"])); ?> </font>
                    <font class="label label-primary" style="font-size: 14px;"> <a href="assets/uploads/atas/audios/<?php echo $arquivo; ?>" target="_blank"><i style="color: #ffffff;" class="fa fa-play"></i></a> </font>
                    <div class="tools">
                      
                        
                        <button   title="Apagar Registro" onclick="div_check_list_delete(<?php echo $row["id"]; ?>, <?php echo $ata; ?>);"><i class="fa fa-trash-o"></i></button>
                    </div>
                </li>
            </ul>
        </div>
   
 <?php  }
    }
    mysqli_close($link);
   ?> 
       