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
    $id_plano = $_POST['id_plano'];
    $usuario = $_POST['usuario'];
   
   
  //UPLOAD
    if($observacao){
    $sql = "INSERT INTO sig_planos_historico (usuario, data_envio, observacao, ip, idplanos,  status_msg, tipo) VALUE('$usuario','$date_hoje','$observacao','$ip','$id_plano','0','$tipo')";
    mysqli_query($link, $sql);
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
          <font style="font-size: 12px;"> : <?php echo strip_tags($row["observacao"]); ?> </font>
          
      <?php if($row["anexo"] != null){  ?> 
      <font style="font-size: 12px;"><a href="<?= base_url() ?>assets/uploads/historico_acoes/<?php echo $row["anexo"]; ?>" target="_blank"><i class="fa fa-chain"></i>Ver Anexo</a></font><?php } ?>
      </div>
    </div>
 <?php }
    }
    mysqli_close($link);
   ?> 
       