<?php


	header('Content-Type: text/html; charset=utf-8');
	setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
	date_default_timezone_set('America/Manaus');
			
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
	
	include_once 'phpmailer/PHPMailerAutoload.php';
	include_once './protocolo/templates/templateemail.php';
        
        include_once ("./protocolo/mysql.connect.php");
        include_once ("./protocolo/cadProtocoloModel.php");
	
	$cadProtocoloModel = new cadProtocoloModel();
	
	/*$conn = mysqli_connect('40.40.0.92', 'financeiro', 'financeiro', 'protocolo');
	
	if (mysqli_connect_errno()){
	  echo ("Failed to connect to MySQL: " . mysqli_connect_error());
	} */
        
        
                        $_Host 		= gethostbyname("smtp.office365.com");
			$_Username 	= "webmaster@unimedmanaus.coop.br";
			$_Password 	= "W3b@Un!m3d";
			
                        $assunto = "TEste Evnio";
                        $corpo = "TESTE";
			
			$mail = new PHPMailer;
			$mail->isSMTP();
			$mail->Host 		= $_Host;
			$mail->Username 	= $_Username;
			$mail->Password 	= $_Password;
			$mail->Port 		= 465;
                       // $mail->SMTPSecure 		= 'ssl';
                        
                      $mail->SMTPAuth 	= true;
			$mail->isHTML(true);
			$mail->CharSet = 'UTF-8';
			
			$mail->From 		= $_Username;
			$mail->FromName 	= 'Webmaster Unimed Manaus';
                        $mail->addAddress(trim('iaraujo.israel@gmail.com'));
                        
                        $mail->Subject 	 = $assunto;
			$mail->Body		 = templateEmail($corpo);
			$mail->AltBody 	 = strip_tags($corpo);
                        
                        
			
			if($mail->send()) {
				echo "Enviado.";
				
			} else {
				echo "ERRO: " . $mail->ErrorInfo;
				
			}
                       
                        
        echo 'aqui'; exit;
	
	/*irei selecionar quem nao foi enviado*/
        /*
	$result = $conn->query("select * from protocolo.protocolo_email where (falha is null OR falha = 'FALHOU') order by datcadastro desc limit 2");
	
	if ($result->num_rows > 0) {
		
		while($row = $result->fetch_assoc()) {
			
			//echo "<pre>"; print_r($row); exit;
			
			$codemail		= $row['codemail'];
			$email			= $row['email'];
			$msg			= $row['msg'];
			$copia			= $row['copia'];
			$cpfremetente	= $row['cpfremetente'];
			$numprotocolo	= $row['numprotocolo'];
			
			$assunto		= "Unimed Manaus | Contato referente ao protocolo: " . $numprotocolo;
			
			$dados_pro = $cadProtocoloModel->dadosProtocoloPeloNumero($numprotocolo);
			
			$corpo  = "Olá, este contato é referente ao protocolo de número: <b>" . $numprotocolo . "</b>
						
						<b>Beneficiário	:</b>	" . $dados_pro['nomeremetente'] . "
						<b>Carteirinha	:</b>	" . $cpfremetente 				. "
						<b>Tipo			:</b>	" . $dados_pro['tipo'] 			. "
						<b>Assunto		:</b>	" . $dados_pro['assunto'] 		. "
					<hr/>
						<b>Mensagem		:</b>	" . utf8_encode($msg) 			. "
					<hr/>
						<b>Unimed Manaus</b>
						4009-8686 | 0800-7029088
						Unimed Manaus. Cuidar de você. Esse é o plano
						Email automático. Não responda.";
			
			//echo "<pre>"; print_r($_corpo); exit;
			
			$_Host 		= gethostbyname("smtp.office365.com");
			$_Username 	= "webmaster@unimedmanaus.coop.br";
			$_Password 	= "W3b@Un!m3d";
			
			
			$mail = new PHPMailer;
			$mail->isSMTP();
			$mail->Host 		= $_Host;
			$mail->Username 	= $_Username;
			$mail->Password 	= $_Password;
			$mail->Port 		= 587;
			$mail->SMTPAuth 	= true;
			$mail->isHTML(true);
			$mail->CharSet = 'UTF-8';
			
			$mail->From 		= $_Username;
			$mail->FromName 	= 'Webmaster Unimed Manaus';
			
			
			$emails = explode(',',$email);
			if( !empty($emails) ){
				foreach($emails as $email){
					$mail->addAddress(trim($email));
				}
			}
			
			
			$copias = explode(',',$copia);
			if( !empty($copias) ){
				foreach($copias as $copia){
					$mail->AddCC(trim($copia)); // Copia
				}
			}
                        
                        
			
			
			$result2 = $conn->query( "select * from protocolo.protocolo_email_anexos pea
										inner join protocolo.protocolo_documentos pd on pd.codprodocumento = pea.codprodocumento
											where codemail = '". $codemail ."';" );
			
			if ($result2->num_rows > 0) {
				while($row2 = $result2->fetch_assoc()) {
					$mail->AddAttachment($_SERVER['DOCUMENT_ROOT'] . '/protocolos/' . $row2['caminho'], $row2['nomarquivo']);  // Insere um anexo
				}
			}
			
			$mail->Subject 	 = $assunto;
			$mail->Body		 = templateEmail($corpo);
			$mail->AltBody 	 = strip_tags($corpo);
			
			if($mail->send()) {
				echo "Enviado.";
				$conn->query("update protocolo.protocolo_email set falha = 'OK' where codemail = '$codemail'");
			} else {
				echo "ERRO: " . $mail->ErrorInfo;
				$conn->query("update protocolo.protocolo_email set falha = 'FALHOU' where codemail = '$codemail'");
			}
			
		}
	} */
		
?>
