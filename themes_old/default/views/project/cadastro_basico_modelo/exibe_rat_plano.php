<?php
 /*
     * 1 - INSERT
     * 2 - DELETE
     */
 $tipo_operacao = $_GET['tipo'];
 
 
 
// INSERT 

if (isset($_POST)) {
    include('../../db_connection.php');

   // DEFINE O FUSO HORARIO COMO O HORARIO DE BRASILIA
    date_default_timezone_set('America/Manaus');
    // CRIA UMA VARIAVEL E ARMAZENA A HORA ATUAL DO FUSO-HORÀRIO DEFINIDO (BRASÍLIA)
    //   $dataLocal = date('d/m/Y H:i:s', time());
    
    $cont_validacao = 0;
    //DATA DA RAT
    if(empty($_POST['data_rat']))
    {
   // echo '<font style="color: red"> Informe a Data da Rat </font>'; 
    }else{
    $data_rat = $_POST['data_rat'];
    $cont_validacao++;
    }
    
    if(empty($_POST['hora_inicio']))
    {
  //  echo '<font style="color: red"> Informe a Hora de Inicio</font>'; 
    }else{
    $hora_inicio = $_POST['hora_inicio'];
    $cont_validacao++;
    }
    
    if(empty($_POST['hora_fim']))
    {
   // echo '<font style="color: red"> Informe a Hora do Término</font>'; 
    }else{
    $hora_fim = $_POST['hora_fim'];
    $cont_validacao++;
    }
    
    if(empty($_POST['descricao_rat']))
    {
    //echo '<font style="color: red"> Descreva a Atividade</font>'; 
    }else{
    $descricao_rat = $_POST['descricao_rat'];
    $cont_validacao++;
    }
    
     if($tipo_operacao == 1){
      if($cont_validacao == 4){
        $tempo = gmdate('H:i', strtotime( $hora_fim) - strtotime( $hora_inicio  ) );
      }else{
          echo '<font style="color: red"> Preencha todos os campos</font>'; 
      }
     }
    $date_hoje = date('Y-m-d H:i:s', time()); 
    $ip = $_SERVER["REMOTE_ADDR"];
    $id_plano = $_POST['id_plano'];
    
    if(empty($_POST['usuario']))
    {
    //echo '<font style="color: red"> Descreva a Atividade</font>'; 
    }else{
    $usuario = $_POST['usuario'];
    }
    
    if(empty($_POST['empresa']))
    {
    //echo '<font style="color: red"> Descreva a Atividade</font>'; 
    }else{
     $empresa = $_POST['empresa'];
    }
    
   
    
    if($tipo_operacao == 1){
   
    if($cont_validacao == 4){
    $sql = "INSERT INTO sig_planos_rat (usuario, data_registro,data_rat, hora_inicio, hora_fim, tempo, descricao, ip, planoid,  empresa) VALUE('$usuario','$date_hoje','$data_rat','$hora_inicio','$hora_fim','$tempo','$descricao_rat','$ip','$id_plano','$empresa')";
    mysqli_query($link, $sql);
    }
   // echo 'Salvo!';
   
    }else if($tipo_operacao == 2){
      $id_rat = $_GET['id_rat'];
      $sql = "DELETE FROM sig_planos_rat where id = $id_rat";   
      if ($link->query($sql) === TRUE) {
        echo "Registro Apagado com Sucesso!";
    } else {
      //  echo "Error deleting record: " . $conn->error;
    }

    //$link->close();
     // mysqli_query($link, $sql);
   
}
   
}

?>
<script>
        function deletar_rat(id_registro) {
            
              $.ajax({
                type: "POST",
                url: "themes/default/views/project/cadastro_basico_modelo/exibe_rat_plano.php?tipo=2&id_rat="+id_registro,
                data: {
                 
                  id_plano: $('#id_plano').val()
                 
                },
                success: function(data) {
                  $('#conteudo_rat').html(data);
                
                }

              });

            }
    </script>
<meta charset="utf-8">

<div class="table-responsive  ">
    <table style="font-size: 12px;" class="table table-striped">
        <thead>
            <tr>
                <th style="width: 5%;">ID</th>
                <th style="width: 10%;">DATA</th>
                <th style="width: 10%;">INÍCIO</th>
                <th style="width: 10%;">TÉRMINO</th>
                <th style="width: 10%;">TEMPO</th>
                <th style="width: 45%;">DESCRICAO</th>
                <th style="width: 10%;">EXCLUIR</th>
            </tr>
        </thead>

        <tbody>

           <?php 
            $sql_historico = "SELECT * FROM sig_planos_rat  where planoid = $id_plano  order by id asc";
            $result = mysqli_query($link, $sql_historico); 
            $cont_planoContinuo = 1;

            if ($result->num_rows > 0) {

             while($row = $result->fetch_assoc()) {     
                $data_rat = $row["data_rat"];
        ?>
           
             <tr >
                <td style="width: 5%;" class="center"><?php echo $cont_planoContinuo++; ?></td>
                <td style="width: 10%;" class="center"><?php echo date("d/m/Y", strtotime($data_rat)); ?></td>
                <td style="width: 10%;" class="center"><?php echo $row["hora_inicio"]; ?></td>
                <td style="width: 10%;" class="center"><?php echo $row["hora_fim"]; ?></td>
                <td style="width: 10%;" class="center"><?php echo $row["tempo"]; ?></td>
                <td style="width: 45%;" class="center"><?php echo $row["descricao"]; ?></td>
                <td style="width: 10%;" class="center"><button  class="btn btn-danger" onclick="deletar_rat(<?php echo $row["id"]; ?>);"><i class="fa fa-trash"></i></button> </td>
                </tr>
         <?php }

            }
            mysqli_close($link);
           ?> 

        </tbody>

    </table>
</div>

       