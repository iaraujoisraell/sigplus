<?php
header("Content-type: text/html; charset=utf-8");
include './connections/conexao.php';
date_default_timezone_set('America/Manaus');
$date = date('Y-m-d');
$hora = date('H:i:s');

 $cont = 0;

?>

<head>

    

    <!-- PACE LOAD BAR PLUGIN - This creates the subtle load bar effect at the top of the page. -->
    <link href="css/plugins/pace/pace.css" rel="stylesheet">
    <script src="js/plugins/pace/pace.js"></script>

    <!-- GLOBAL STYLES - Include these on every page. -->
    <link href="css/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href='http://fonts.googleapis.com/css?family=Ubuntu:300,400,500,700,300italic,400italic,500italic,700italic' rel="stylesheet" type="text/css">
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel="stylesheet" type="text/css">
    <link href="icons/font-awesome/css/font-awesome.min.css" rel="stylesheet">

    <!-- PAGE LEVEL PLUGIN STYLES -->
    <link href="css/plugins/datatables/datatables.css" rel="stylesheet">

    <!-- THEME STYLES - Include these on every page. -->
    <link href="css/style.css" rel="stylesheet">
    <link href="css/plugins.css" rel="stylesheet">

    <!-- THEME DEMO STYLES - Use these styles for reference if needed. Otherwise they can be deleted. -->
    <link href="css/demo.css" rel="stylesheet">

    <!--[if lt IE 9]>
      <script src="js/html5shiv.js"></script>
      <script src="js/respond.min.js"></script>
    <![endif]-->

</head>

                                        
<?php


// Cria uma função que retorna o timestamp de uma data no formato DD/MM/AAAA
function geraTimestamp($data) {
$partes = explode('/', $data);
return mktime(0, 0, 0, $partes[1], $partes[0], $partes[2]);
}












/*
 * ************************RECEITAS****************************
 */

//VERIFICA AS EMPRESAS 
$query_empresas = "SELECT distinct su.empresa_id as empresa_id, razaoSocial, emailResponsavel
         FROM `sma_transactions` as st inner join `sma_users` as su on st.usuario_id = su.username 
        inner join `sma_empresa` as semp on semp.id = su.empresa_id 
        left join `sma_sales` as sa on sa.id = st.venda_id 
        WHERE st.status != 'paid' and st.date <= NOW() and type = 'receita' order by st.date asc ";
$sql_empresas = mysqli_query($conexao,$query_empresas);
while ($linhas_empresas = mysqli_fetch_array($sql_empresas, MYSQLI_ASSOC)) {
           
       $id_empresa = $linhas_empresas['empresa_id'];
       $razaoSocial = $linhas_empresas['razaoSocial'];
       $email = $linhas_empresas['emailResponsavel'];
            
         
       
       
       
/*
 * DESPESAS
 */
/*
 * conta quantas DESTESAS tem
 */
$query_count_despesas = "SELECT count(*) as count FROM `sma_transactions` as st 
inner join `sma_users` as su on st.usuario_id = su.username
left join `sma_sales` as sa on sa.id = st.venda_id
WHERE status != 'paid' and st.date <= NOW() and type = 'despesa' and su.empresa_id = $id_empresa order by st.date asc ";
$sql_count_transactions_despesas = mysqli_query($conexao,$query_count_despesas);
//$resultado_cont = mysqli_result($sql_count_transactions);

while ($count_receitas_despesas = mysqli_fetch_array($sql_count_transactions_despesas, MYSQLI_ASSOC)) {
           
       $count_transactions_despesas = $count_receitas_despesas['count'];
}  



/*
 * FIM DESPESAS
 */       


/*
 * conta quantas receitas tem a empresa
 */
$query_count = "SELECT count(*) as count FROM `sma_transactions` as st 
inner join `sma_users` as su on st.usuario_id = su.username
left join `sma_sales` as sa on sa.id = st.venda_id
WHERE status != 'paid' and st.date <= NOW() and type = 'receita' and su.empresa_id = $id_empresa order by st.date asc ";
$sql_count_transactions = mysqli_query($conexao,$query_count);
//$resultado_cont = mysqli_result($sql_count_transactions);

while ($count_receitas = mysqli_fetch_array($sql_count_transactions, MYSQLI_ASSOC)) {
           
       $count_transactions = $count_receitas['count'];
}       


 /*
  * FIM QUERY RECEITAS
  */      
            $data_atual =  date('Y-m-d H:i:s');
            $data_atual = substr($data_atual, 0, 10); 
            $data_atual = explode("-", $data_atual);
            $data_atual = $data_atual[2]."/".$data_atual[1]."/".$data_atual[0];      
        
   //TABLE RECEITAS     
        $texto =  '<div class="col-lg-12">
           
                        <div class="portlet portlet-default">
                            <div class="portlet-heading">
                                <div class="portlet-title">
                                    <h4>OLÁ '.$razaoSocial. ' </h4>
                                        <div class="clearfix"></div>
                                        <div class="clearfix"></div>
                                        <h4> VOCÊ TEM '.$count_transactions.' RECEITAS NÃO RECEBIDAS ATÉ A DATA DE HOJE ' .$data_atual.'</h4>
                                        
                                        <h4> E VOCÊ TEM '.$count_transactions_despesas.' DESPESAS NÃO PAGAS ATÉ A DATA DE HOJE ' .$data_atual.'</h4>

                                </div>
                                <div class="clearfix"></div>
                                <table>'; 
                                if($count_transactions>0){
                                  $texto .=  '<h3>Para visualizar o relatório de Receitas <a href="receitas.php" > click aqui </a></h3>';
                                }
                                '</table>
                                   
                                    <table>'; 
                                if($count_transactions_despesas>0){
                                    $texto .=  ' <h3>Para visualizar o relatório de Despesas <a href="despesas.php" > click aqui </a></h3>';
                                }
                                '
                                     </table>
                            </div>
                            
               
                                    
                                    
                                    
                         
                                </div>
                                

                                <!-- /.table-responsive -->
                            </div>
                            <!-- /.portlet-body -->
                        </div>
                        <!-- /.portlet -->

                    </div>';

                                       
 

}

echo $texto . '<br>';

while ($hora){
   //  echo "<meta HTTP-EQUIV='refresh' CONTENT='0;URL=receitas.php'>";
     echo $hora. '<br>';
    
    if($hora == '01:49:45'){
    // if($hora){
    $assunto = "Gestor Fácil Online - Relário Financeiro de Receitas e Despesas abertas";
       
        $email_remetente =  'suporte@gestorfacil.online';
       
        $headers = "From: $email_remetente\r\n" .
               
               "X-Mailer: PHP/" . phpversion() . "\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

    
	$to = "iaraujo.israel@gmail.com";
	
	
	//Concatenando os dados para exibição em html
	
	
	if (mail($to, $assunto, $texto, $headers)) {
		$resultado3 = 1;
	} else {
		$resultado3 = 0;
	}
        
        // echo '<br>'.$resultado3;
    
       
      //  break;
       // echo "<meta HTTP-EQUIV='refresh' CONTENT='0;URL=receitas.php'>";
        
    }else{
        exit;
    }
    
}


?>

