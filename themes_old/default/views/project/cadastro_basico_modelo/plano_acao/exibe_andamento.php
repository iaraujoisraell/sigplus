<?php
  /*
     * 1 - INSERT
     * 2 - STATUS = CONCLUIDO
     */
 $tipo_operacao = $_GET['tipo'];
 $id_plano = $_GET['id_plano'];
 $atualizou = $_GET['atualizou'];
 

 
if (isset($_POST)) {
    include('../../../db_connection.php');

    // DEFINE O FUSO HORARIO COMO O HORARIO DE BRASILIA
    date_default_timezone_set('America/Manaus');
// CRIA UMA VARIAVEL E ARMAZENA A HORA ATUAL DO FUSO-HORÀRIO DEFINIDO (BRASÍLIA)
 //   $dataLocal = date('d/m/Y H:i:s', time());
    
    $date_hoje = date('Y-m-d H:i:s', time()); 
    $ip = $_SERVER["REMOTE_ADDR"];
    
   // $descricao_checklist = $_POST['descricao_checklist'];
   // $id_plano = $_POST['id_plano'];
   // $usuario = $_POST['usuario'];
   
    
  
    // ATUALIZA O ANDAMENTO DA AÇÃO NO CADASTRO DA AÇÃO
    if($tipo_operacao == 1){
      $andamento = $_GET['andamento'];
      
      if ($andamento >= 0) {

            $sql = "UPDATE sig_planos SET andamento = $andamento where idplanos = $id_plano";
            if ($link->query($sql) === TRUE) {
                // echo "Registro Apagado com Sucesso!";
            } else {
                //   echo "Error deleting record: " . $link->error;
            }
        } else if ($tipo_operacao == 3) {
            $id_check = $_GET['id_check'];

            $sql = "UPDATE sig_plano_checklist SET STATUS = 0 where id = $id_check";
            if ($link->query($sql) === TRUE) {
                // echo "Registro Apagado com Sucesso!";
            } else {
                //  echo "Error deleting record: " . $link->error;
            }
        }
    }    
   // echo 'Salvo!';
   
}

?>
<script type="text/javascript">
   
    
     function atualiza_andamento(plano, andamento, atualizou) {
              $.ajax({
                type: "POST",
                url: "themes/default/views/networking/minhas_acoes/exibe_andamento.php?tipo=1&id_plano="+plano+"&andamento="+andamento+"&atualizou="+atualizou,
                
                success: function(data) {
                  $('#exibe_andamento').html(data);
                 
                }

              });

            }
    
    </script>
<meta charset="utf-8">

<?php 
 
 if($atualizou){
    
 }else{
     $atualizou =  0;
 }
if($id_plano){

    $sql_historico = "SELECT * FROM sig_plano_checklist where plano_id = $id_plano ";
    $result = mysqli_query($link, $sql_historico); //$link->query($sql_historico);
    
    if ($result->num_rows > 0) {
         $cont_total = 0;
         $cont_status1 = 0;
         $cont_status0 = 0;
         while($row = $result->fetch_assoc()) {     
             $status = $row["status"];    
             if($status == 0){
                 $cont_status0++;
             }else if($status == 1){
                 $cont_status1++;
             }
             $cont_total++;
        }
      
        $porcentagem = ($cont_status1 * 100)/$cont_total;
      
        // SE ATUALIZOU = 1, ELE FOI ATUALIZADO DIRETO PELO CAMPO ATUALIZAR E NAO PELO CHECKLIST
        if($atualizou != 1){
        $sql = "UPDATE sig_planos SET andamento = $porcentagem where idplanos = $id_plano";
        if ($link->query($sql) === TRUE) {
            // echo "Registro Apagado com Sucesso!";
        }
        
        }
        
    }
   
    
    
    $sql_andamento_plano = "SELECT andamento FROM sig_planos where idplanos = $id_plano ";
    $result_plano = mysqli_query($link, $sql_andamento_plano); //$link->query($sql_historico);
    $row_andamento_plano = $result_plano->fetch_assoc();
    $andamento_plano = $row_andamento_plano['andamento'];
     if($andamento_plano == 0){
            $andamento_plano = "";
        }
     mysqli_close($link);
}

   ?> 
<br>
 <div class="row">
     <div style="margin-top: 5px;" class="col-md-10">    
        <div style="height: 30px;" class="progress progress-sm active">
            <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $andamento_plano; ?>%">
                <span ><?php echo $andamento_plano; ?><?php if($andamento_plano > 0){ ?> % Completo <?php }else if($porcentagem == 0){ ?> 0% <?php } ?></span>
            </div>
        </div>      
    </div> 
    
    
</div>      
<script>//exibe_andamento(<?php echo $id_plano; ?>)</script>