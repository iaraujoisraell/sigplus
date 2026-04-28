<?php
	
	//	$conn = mysqli_connect('162.214.205.254', 'wwsigp_sig', 'Sigplus*2024', 'wwsigp_sigplus');	
	$conn = mysqli_connect('54.158.243.192', 'wwsigp_sig', 'Sigplus*2024', 'wwsigp_sigplus');	
	
	$conn->set_charset("utf8");

	if (mysqli_connect_errno()){
	  echo ("Failed to connect to MySQL: " . mysqli_connect_error());
	}
	
	$sql = "select * from tbl_intranet_sms where status = 0 and deleted = 0 ";
	$result = $conn->query($sql);
 
	//echo 'aqui'; exit;
	
	if ($result->num_rows > 0) {
		
		while($row = $result->fetch_assoc()) {
		
			$codsms			= $row['id'];
			$numero			= $row['phone_destino'];
			$msg			= $row['mensagem'];
			
			$numero = preg_replace("/[^0-9]/", "", $numero);
			
			// CHAMA A API SMS		   
			$ch   = curl_init();
	  
			curl_setopt_array($ch, array(
				CURLOPT_URL => 'https://sistemaweb.unimedmanaus.com.br/sigplus/api/Sms',
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => '',
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 0,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => 'POST',
				CURLOPT_POSTFIELDS =>'{ "numero": '.$numero.', 
									  "msg": "'.$msg.'"
									  }',
				CURLOPT_HTTPHEADER => array(
				  'Content-Type: application/json; charset=utf-8'
				),
			  ));
			  $resultado = curl_exec($ch);
			  print_R( $resultado); exit; 
			//$resultado = EnviaSMSTWW($numero, $canal, $msg);
		
			if($resultado == 'OK'){
				$data_hoje = date('Y-m-d H:i:s');
				$conn->query("update tbl_intranet_sms set data_envio = '$data_hoje' , status = 1 where id = $codsms");
			}else{
				$conn->query("update tbl_intranet_sms set status = 2 where id = $codsms");
			}
                        
                        
                       
			
			
			$_enviaSMS = new EnviaSMS();
			
			$_enviaSMS->ddd_set($ddd);
			$_enviaSMS->numero_set($numero);
			$_enviaSMS->mensagem_set($msg);

			if ($_enviaSMS->realizaEnvio()) {
				$conn->query("update db_novo.envio_sms set enviado = 'S' where codsms = '$codsms'");
			}else{
				$conn->query("update db_novo.envio_sms set enviado = 'N', falha = '".$_enviaSMS->msgErro()."' where codsms = '$codsms'");
			}
			
			
		}
	}
	
?>
