<?php
	
	//$conn = mysqli_connect('162.214.205.254', 'wwsigp_sig', 'Sigplus*2024', 'wwsigp_sigplus');	
	$conn = mysqli_connect('54.158.243.192', 'wwsigp_sig', 'Sigplus*2024', 'wwsigp_sigplus');	
	
	$conn->set_charset("utf8");

	if (mysqli_connect_errno()){
	  echo ("Failed to connect to MySQL: " . mysqli_connect_error());
	}
	
	$result = $this->Comunicacao_model->EnviaSMSTWW('92991553632', 'msg teste', 'Boleto', '4', true);
	
	$sql = "select * from tbl_boleto_sms where status = 0 and deleted = 0 ";
    //echo $sql; exit;
	$result = $conn->query($sql);
	//echo 'aqui'; exit;
//	print_r($result->num_rows); exit;
	if ($result->num_rows > 0) {
		
		while($row = $result->fetch_assoc()) {
		
			$codsms			= $row['id'];
			$numero			= $row['phone_destino'];
			$msg			= $row['mensagem'];
			
			$numero = preg_replace("/[^0-9]/", "", $numero);
		//	echo $numero; exit;
			// CHAMA A API SMS		   
			$ch   = curl_init();
	  
			curl_setopt_array($ch, array(
				CURLOPT_URL => 'http://189.2.65.2/sigplus/api/Sms',
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => '',
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 0,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => 'POST',
				CURLOPT_POSTFIELDS =>'{ "numero": "'.$numero.'", 
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
                        
                        
                       
			
			/*
			$_enviaSMS = new EnviaSMS();
			
			$_enviaSMS->ddd_set($ddd);
			$_enviaSMS->numero_set($numero);
			$_enviaSMS->mensagem_set($msg);

			if ($_enviaSMS->realizaEnvio()) {
				$conn->query("update db_novo.envio_sms set enviado = 'S' where codsms = '$codsms'");
			}else{
				$conn->query("update db_novo.envio_sms set enviado = 'N', falha = '".$_enviaSMS->msgErro()."' where codsms = '$codsms'");
			}
			*/
			
		}
	}
	
?>
