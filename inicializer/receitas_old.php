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
 * DESPESAS
 */
/*
 * conta quantas DESTESAS tem
 
$query_count_despesas = "SELECT count(*) FROM `sma_transactions` WHERE status != 'paid' and date <= NOW() and  type = 'receita'
           order by date asc ";
$sql_count_transactions_despesas = mysql_query($query_count_despesas);
$resultado_cont_despesas = mysql_result($sql_count_transactions_despesas, 0);


$query_despesas = "SELECT * FROM `sma_transactions` WHERE status != 'paid' and date <= NOW() and  type = 'receita' order by date asc ";
$sql_transactions_despesas = mysql_query($query_despesas);
/*
 * FIM DESPESAS
 */









/*
 * ************************RECEITAS****************************
 */

//VERIFICA AS EMPRESAS 
$query_empresas = "SELECT distinct su.empresa_id as empresa_id, razaoSocial, emailResponsavel
         FROM `sma_transactions` as st inner join `sma_users` as su on st.usuario_id = su.username 
        inner join `sma_empresa` as semp on semp.id = su.empresa_id 
        left join `sma_sales` as sa on sa.id = st.venda_id 
        WHERE st.status != 'paid' and st.date <= NOW() and type = 'receita' order by st.date asc ";
$sql_empresas = mysql_query($query_empresas);
while ($linhas_empresas = mysql_fetch_array($sql_empresas)) {
           
       $id_empresa = $linhas_empresas['empresa_id'];
       $razaoSocial = $linhas_empresas['razaoSocial'];
       $email = $linhas_empresas['emailResponsavel'];
            
         


/*
 * conta quantas receitas tem a empresa
 */
$query_count = "SELECT count(*) FROM `sma_transactions` as st 
inner join `sma_users` as su on st.usuario_id = su.username
left join `sma_sales` as sa on sa.id = st.venda_id
WHERE status != 'paid' and st.date <= NOW() and type = 'receita' and su.empresa_id = $id_empresa order by st.date asc ";
$sql_count_transactions = mysql_query($query_count);
$resultado_cont = mysql_result($sql_count_transactions, 0);

$query = "SELECT st.id as id, st.payer as payer,st.description as description, st.date as date_st, st.amount as amount, st.type as type,
    st.account as account, sa.reference_no as reference_no 
    FROM `sma_transactions` as st 
inner join `sma_users` as su on st.usuario_id = su.username
left join `sma_sales` as sa on sa.id = st.venda_id
WHERE status != 'paid' and st.date <= NOW() and type = 'receita' and su.empresa_id = $id_empresa order by st.date asc ";
$sql_transactions = mysql_query($query);
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
                                        <h4> VOCÊ TEM '.$resultado_cont.' RECEITAS NÃO RECEBIDAS ATÉ A DATA DE HOJE ' .$data_atual.'</h4>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div class="portlet-body">
                                <div class="table-responsive">
                                    <table id="example-table" class="table table-striped table-bordered table-hover table-green">
                                        <thead>
                                            <tr>
                                                <th>-</th>
                                                <th>DATA DO VENCIMENTO</th>
                                                <th>VALOR</th>
                                                <th>CLIENTE</th>
                                                <th>REFERENTE</th>
                                                <th>CONTA</th>
                                                 <th>DIAS DE ATRASO</th>
                                            </tr>
                                        </thead>
                                        <tbody>';
        
        
       $soma_valor = 0;
        while ($transactions = mysql_fetch_array($sql_transactions)) {
            $cont++;
            
            $id = $transactions['id'];
            $payer = $transactions['payer'];
            $description = $transactions['description'];
            /*FORMATA A DATA*/
            $date_trasaction = $transactions['date_st'];
            $date_trasaction = substr($date_trasaction, 0, 10); 
            $date_trasaction = explode("-", $date_trasaction);
            $date_trasaction = $date_trasaction[2]."/".$date_trasaction[1]."/".$date_trasaction[0];
            
            $amount = $transactions['amount'];
            $soma_valor += $amount;
            
            $type = $transactions['type'];
            $conta = $transactions['account'];
            $reference = $transactions['reference_no'];
            
            /*
             * SOMA OS DIAS EM ATRASOS
             */
            $date_vencimento = $transactions['date_st'];
            $date_vencimento = substr($date_vencimento, 0, 10); 
            $date_vencimento = explode("-", $date_vencimento);
            $date_vencimento = $date_vencimento[2]."/".$date_vencimento[1]."/".$date_vencimento[0];
            
            
            
            // Usa a função criada e pega o timestamp das duas datas:
            $time_inicial = geraTimestamp($date_vencimento);
            $time_final = geraTimestamp($data_atual);
            // Calcula a diferença de segundos entre as duas datas:
            $diferenca = $time_final - $time_inicial; // 19522800 segundos
            // Calcula a diferença de dias
            $dias = (int)floor( $diferenca / (60 * 60 * 24)); // 225 dias
            
            if ($reference){
                $referencia = $reference;
            }else{
                $referencia = $description;
            }
           
            $texto .= '<tr class="odd gradeX">';
            $texto .= '<td>'.$cont.'</td>';
            $texto .= '<td> '.$date_trasaction.'</td>';
            $texto .= '<td> '.'R$ ' . number_format($amount, 2);'</td>';
            $texto .= '<td> '.$payer.'</td>';
            $texto .= '<td> '.$referencia.' </td>';
            $texto .= '<td> '.$conta.' </td>';
            $texto .= '<td>'.$dias.'</td>';
            $texto .= '</tr>';
          
           
         
           }

           $texto .= '</tbody>
               
                                        <thead>
                                            <tr>
                                                <th colspan="2">VALOR TOTAL A RECEBER</th>
                                                
                                                <th colspan="5">'.'R$ ' . number_format($soma_valor, 2);'</th>
                                                
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                        <td></td>
                                        <td></td>
                                        </tr>
                                        </tbody>


                                        
                                   </table>
                                    
                                    
                         
                                </div>
                                

                                <!-- /.table-responsive -->
                            </div>
                            <!-- /.portlet-body -->
                        </div>
                        <!-- /.portlet -->

                    </div>';

                                                
         

                                                
 function enviar_email() {
        
        $assunto = "Relário Financeiro Diário - RECEITAS ABERTAS";
       
        $email_remetente =  'suporte@gestorfacil.online';
        
        $headers  = "MIME-Version: 1.0\n";
	$headers .= "Content-type: text/html; charset=iso-8859-1\n";
	$headers .= "X-Priority: 3\n";
	$headers .= "X-MSMail-Priority: Normal\n";
	$headers .= "X-Mailer: php\n";
	$headers .= "From: GESTOR FÁCIL ONLINE -  <$email_remetente>\n";
	

	$this->envinadoEmail	=	mail($email, $assunto, $texto, $headers);
        
        
        $this->session->set_flashdata('message', 'Mensagem enviada com sucesso!');
        $this->session->set_flashdata('type', 'warning');
         
    }

}


while ($hora){
     echo "<meta HTTP-EQUIV='refresh' CONTENT='0;URL=teste_funcao.php'>";
    echo $hora. '<br>';
    
    if($hora == '00:40:15'){
        $this->enviar_email();
        break;
        echo "<meta HTTP-EQUIV='refresh' CONTENT='0;URL=teste_funcao.php'>";
    }else{
        exit;
    }
    
}


?>

