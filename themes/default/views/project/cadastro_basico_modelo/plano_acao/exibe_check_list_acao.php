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
    
    $id_plano = $_POST['id_plano'];
    $usuario = $_POST['usuario'];
   
    
  
  //INSERT
    
   // echo 'Salvo!';
   
}

?>
   <script>
        function exibe_andamento(plano) {
              $.ajax({
                type: "POST",
                url: "themes/default/views/networking/minhas_acoes/exibe_andamento.php?tipo=0&id_plano="+plano+"&atualizou=0",
                
                success: function(data) {
                  $('#exibe_andamento').html(data);
                 
                }

              });

            }
    </script>    
    
<script type="text/javascript">
    function div_check_list_on(id_registro, id_plano) {
      $.ajax({
        type: "POST",
        url: "themes/default/views/project/cadastro_basico_modelo/exibe_check_list_acao.php?tipo=2&id_check="+id_registro,
        data: {
          descricao_checklist: $('#descricao_checklist').val(),
          id_plano: $('#id_plano').val(),
          usuario: $('#usuario').val()
        },
        success: function(data) {
          $('#div_checklist').html(data);
          exibe_andamento(id_plano);
        }
        

      });
     document.getElementById("descricao_checklist").value = "";
    }

    function div_check_list_off(id_registro, id_plano) {
      $.ajax({
        type: "POST",
        url: "themes/default/views/project/cadastro_basico_modelo/exibe_check_list_acao.php?tipo=3&id_check="+id_registro,
        data: {
          descricao_checklist: $('#descricao_checklist').val(),
          id_plano: $('#id_plano').val(),
          usuario: $('#usuario').val()
        },
        success: function(data) {
          $('#div_checklist').html(data);
           exibe_andamento(id_plano);
        }

      });
     document.getElementById("descricao_checklist").value = "";
    }

function div_check_list_delete(id_registro) {
      $.ajax({
        type: "POST",
        url: "themes/default/views/project/cadastro_basico_modelo/exibe_check_list_acao.php?tipo=4&id_check="+id_registro,
        data: {
          descricao_checklist: $('#descricao_checklist').val(),
          id_plano: $('#id_plano').val(),
          usuario: $('#usuario').val()
        },
        success: function(data) {
          $('#div_checklist').html(data);

        }

      });
     document.getElementById("descricao_checklist").value = "";
    }
    
     
    </script>
<meta charset="utf-8">

<?php 
    $sql_historico = "SELECT * FROM sig_plano_checklist where plano_id = $id_plano  order by id asc";
    $result = mysqli_query($link, $sql_historico); //$link->query($sql_historico);
    
    if ($result->num_rows > 0) {
     $total_obs = 1;    
     while($row = $result->fetch_assoc()) {     
     $status = $row["status"];    
     if($status == 0){
         $label = "warning";
         $texto = "Em andamento";
     }else if($status == 1){
         $label = "success";
         $texto = "Concluído";
     }
?>
    
        <div class="box-body">
            <!-- See dist/js/pages/dashboard.js to activate the todoList plugin -->
            <ul class="todo-list">
                <li>     
                    <font style="font-size: 16px;">  <?php echo $total_obs++; ?> </font>
                    <font style="font-size: 16px;"> : <?php echo strip_tags($row["descricao"]); ?> </font>
                    <font class="label label-<?php echo $label; ?>" style="font-size: 14px;"> <?php echo $texto; ?> </font>
                    
                </li>
            </ul>
        </div>
   
 <?php }
    }
    mysqli_close($link);
   ?> 
       