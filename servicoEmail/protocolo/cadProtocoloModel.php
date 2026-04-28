<?php

class cadProtocoloModel extends MysqlConnectionProtocolo{
	private $search;
                
	function __construct(){
		parent::__construct();
	}
	
//============================================== INSERTS      ==================================================================
        public function insertCadProtocolo($dados) {
			
			//if($_SERVER['REMOTE_ADDR'] == '10.11.20.162'){
				//echo "<pre>"; print_r($_SESSION); exit;
			//}
			
			$id_setor_origem = ( isset($_SESSION['protocolo']['myIdSetor']) ? $_SESSION['protocolo']['myIdSetor'] : $dados["setorenvio"]);
			
			$confidencial = (isset($dados['confidencial']) ? "S" : "N");
			
			$Query = "INSERT INTO protocolo.protocolos (
							 numprotocolo, 
							 anoprotocolo, 
							 assunto, 
							 nomeremetente, 
							 cpfremetente, 
							 telefone, 
							 email, 
							 observacao, 
							 status, 
							 confidencial, 
							 codsetoratual, 
							 canatendimento,
							 cobcontratual,
							 rolans,
							 codtipo,
							 numprotocologpu,
							 codmotivo
					 ) VALUES (
							 :numprotocolo, 
							 :anoprotocolo, 
							 :assunto, 
							 :nomeremetente, 
							 :cpfremetente, 
							 :telefone, 
							 :email, 
							 :observacao, 
							 :status, 
							 :confidencial, 
							 :codsetoratual, 
							 :canatendimento,
							 :cobcontratual,
							 :rolans,
							 :codtipo,
							 :numprotocologpu,
							 :codmotivo
					 );";
					
			try{
					$this->insert = $this->MySql->prepare($Query);
					$this->insert->bindValue(":numprotocolo", $dados['numprotocolo'], PDO::PARAM_STR);	
					$this->insert->bindValue(":anoprotocolo", date('Y'), PDO::PARAM_STR);	
					$this->insert->bindValue(":assunto", $dados['assunto'], PDO::PARAM_STR);	
					$this->insert->bindValue(":nomeremetente", $dados['nomeremetente'], PDO::PARAM_STR);	
					$this->insert->bindValue(":cpfremetente", $dados['cpfremetente'], PDO::PARAM_STR);
					$this->insert->bindValue(":telefone", $dados['telefone'], PDO::PARAM_STR);
					$this->insert->bindValue(":email", $dados['email'], PDO::PARAM_STR);
					$this->insert->bindValue(":observacao", $dados['observacaoprotocolo'], PDO::PARAM_STR);	
					$this->insert->bindValue(":status", "P", PDO::PARAM_STR);	
					$this->insert->bindValue(":confidencial", $confidencial, PDO::PARAM_STR);	
					$this->insert->bindValue(":codsetoratual", $id_setor_origem, PDO::PARAM_STR);	
					$this->insert->bindValue(":canatendimento", $dados['canatendimento'], PDO::PARAM_STR);	
					$this->insert->bindValue(":cobcontratual", @$dados['cobcontratual'], PDO::PARAM_STR);	
					$this->insert->bindValue(":rolans", @$dados['rolans'], PDO::PARAM_STR);	
					$this->insert->bindValue(":codtipo", $dados['codtipo'], PDO::PARAM_STR);	
					$this->insert->bindValue(":numprotocologpu", $dados['numprotocologpu'], PDO::PARAM_STR);	
					$this->insert->bindValue(":codmotivo", @$dados['codmotivo'], PDO::PARAM_STR);	
					
					if($this->insert->execute()){
						$ultimo_id_inserido = $this->MySql->lastInsertId();
						return $ultimo_id_inserido;
					}else{
						return '';
					}
			}catch(PDOExeption $e){
				die($e->getMessage());
			}
		}
		public function EnviarSMS($_msg, $_numero, $_cpfremetente, $_numprotocolo){
			
			$_msg = "UNIMED: " . $_msg;
			
			//somente numero
			$_numero = preg_replace("/[^0-9]/", "", $_numero);
			
			$posicao_3 = $_numero[2];
			
			if( $posicao_3 == 8 OR $posicao_3 == 9 ){
				
				//$_resultado = EnviaSMSTWW($_numero, $_msg);
				
				$_resultado = NULL;
				
				if( $this->SalvarSMS($_numero,$_msg, $_resultado, $_cpfremetente, $_numprotocolo) ){
					return true;
				}else{
					return false;
				}
			}else{
				return true;
			}
		}
		public function SalvarSMS($_numero,$_msg, $_erro, $_cpfremetente, $_numprotocolo){
			
			$Query = "INSERT INTO protocolo.protocolo_sms (datcadastro, numero, msg, falha, cpfremetente, codusucadastro, numprotocolo) 
							VALUES (:v1,:v2,:v3,:v4,:v5,:v6,:v7);";
                
			try{
				$this->insert = $this->MySql->prepare($Query);
				$this->insert->bindValue(":v1", date("Y-m-d H:i:s"), PDO::PARAM_STR);	
				$this->insert->bindValue(":v2", $_numero, PDO::PARAM_STR);	
				$this->insert->bindValue(":v3", $_msg, PDO::PARAM_STR);	
				$this->insert->bindValue(":v4", $_erro, PDO::PARAM_STR);	
				$this->insert->bindValue(":v5", $_cpfremetente, PDO::PARAM_STR);	
				$this->insert->bindValue(":v6", (isset($_SESSION['protocolo']['myId']) ? $_SESSION['protocolo']['myId'] : '519'), PDO::PARAM_STR);	
				$this->insert->bindValue(":v7", $_numprotocolo, PDO::PARAM_STR);	
				
				if( $this->insert->execute() ){
					return true;
				}else{
					return false;
				}
				
			}catch(PDOExeption $e){
				die($e->getMessage());
			}
		}
		public function enviarEmail($_assunto, $_msg, $_email, $_copia, $_anexos){
			
			//echo "<pre>"; print_r($_anexos); exit;
			
			include_once $_SERVER['DOCUMENT_ROOT'] . '/tarefas_agendadas/phpmailer/PHPMailerAutoload.php';
			
			$_Host 				= gethostbyname('smtp.office365.com');
			$_Username 			= 'unimed.informa@unimedmanaus.coop.br';
			$_Password 			= 'un!medinform@';
			
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
			
			/*destinatarios*/
			$mail->addAddress($_email);
			
			/*copias*/
			$copias = explode(',',$_copia);
			if( !empty($copias) ){
				foreach($copias as $copia){
					$mail->AddCC($copia); // Copia
				}
			}
			
			/*anexos*/
			if( !empty($_anexos) ){
				foreach( $_anexos as $codprodocumento => $values ){
					$dadosAnexo = $this->retornaDadosDoAnexo($codprodocumento); 
					$mail->AddAttachment($_SERVER['DOCUMENT_ROOT'] . '/protocolos/' . $dadosAnexo['caminho'], $dadosAnexo['nomarquivo']);  // Insere um anexo
				}
			}
			
			$mail->Subject 	 = $_assunto;
			$mail->Body		 = $_msg;
			$mail->AltBody 	 = strip_tags($_msg);
			
			if( $mail->send() ) {
				return 'OK';
			} else {
				return $mail->ErrorInfo;
			}
		}
		
		public function SalvarEmail($_msg, $_email, $_copia, $_cpfremetente, $_numprotocolo, $_anexos, $_resultado_envio){
			
			$Query = "INSERT INTO protocolo.protocolo_email (datcadastro, codusucadastro, email, copia, cpfremetente, numprotocolo, msg, falha) 
							VALUES (:datcadastro, :codusucadastro, :email, :copia, :cpfremetente, :numprotocolo, :msg, :falha);";
                
			try{
				$this->insert = $this->MySql->prepare($Query);
				$this->insert->bindValue(":datcadastro", date("Y-m-d H:i:s"), PDO::PARAM_STR);	
				$this->insert->bindValue(":codusucadastro", (isset($_SESSION['protocolo']['myId']) ? $_SESSION['protocolo']['myId'] : '519'), PDO::PARAM_STR);	
				$this->insert->bindValue(":email", $_email, PDO::PARAM_STR);	
				$this->insert->bindValue(":copia", $_copia, PDO::PARAM_STR);	
				$this->insert->bindValue(":cpfremetente", $_cpfremetente, PDO::PARAM_STR);	
				$this->insert->bindValue(":numprotocolo", $_numprotocolo, PDO::PARAM_STR);	
				$this->insert->bindValue(":msg", $_msg, PDO::PARAM_STR);	
				$this->insert->bindValue(":falha", $_resultado_envio, PDO::PARAM_STR);	
				
				if( $this->insert->execute() ){
					
					$codemail = $this->MySql->lastInsertId();
					
					if( !empty($_anexos) ){
						foreach( $_anexos as $codprodocumento => $values ){
							$this->SalvarEmailAnexos( $codemail, $codprodocumento);
						}
					}
					
					return true;
				}else{
					return false;
				}
			}catch(PDOExeption $e){
				die($e->getMessage());
			}
		}
		public function SalvarEmailAnexos( $codemail, $codprodocumento){
			
			$Query = "INSERT INTO protocolo.protocolo_email_anexos (codemail, codprodocumento) VALUES (:codemail,:codprodocumento);";
                
			try{
				$this->insert = $this->MySql->prepare($Query);
				$this->insert->bindValue(":codemail", $codemail, PDO::PARAM_STR);	
				$this->insert->bindValue(":codprodocumento", $codprodocumento, PDO::PARAM_STR);	
				$this->insert->execute();
			}catch(PDOExeption $e){
				die($e->getMessage());
			}
		}
        public function insertProEventos($id_protocolo, $evento, $observacao, $setorusucadastro){
			
			$Query = "INSERT INTO protocolo.protocolo_eventos (	
															codprotocolo, 
															datacriacao, 
															evento, 
															observacao, 
															codusucadastro, 
															ipusucadastro, 
															hostusucadastro, 
															setorusucadastro,
															codsetorusucadastro
														) VALUES (
															:codprotocolo,
															:datacriacao,
															:evento,
															:observacao,
															:codusucadastro,
															:ipusucadastro,
															:hostusucadastro,
															:setorusucadastro,
															:codsetorusucadastro
														);";
                
			try{
				
				$codusucadastro   	= '519';
				$ipusucadastro    	= '00.00.00.00';
				$hostusucadastro  	= 'unimedmanaus';
				$setorusucadastro 	= 'GTI';
				$codsetorusucadastro= '6';
				
				if( isset($_SESSION['protocolo']['myId']) ){
					$codusucadastro   = $_SESSION['protocolo']['myId'];
					$ipusucadastro    = $_SESSION['protocolo']['myIp'];
					$hostusucadastro  = $_SESSION['protocolo']['myHostName'];
					$setorusucadastro = $_SESSION['protocolo']['mySetor'];
					$codsetorusucadastro = $_SESSION['protocolo']['myIdSetor'];
				}
				
				$this->insert = $this->MySql->prepare($Query);
				$this->insert->bindValue(":codprotocolo", $id_protocolo);	
				$this->insert->bindValue(":datacriacao", date("Y-m-d H:i:s"));	
				$this->insert->bindValue(":evento", $evento);	
				$this->insert->bindValue(":observacao", $observacao);	
				$this->insert->bindValue(":codusucadastro", $codusucadastro);	
				$this->insert->bindValue(":ipusucadastro", $ipusucadastro);	
				$this->insert->bindValue(":hostusucadastro", $hostusucadastro);	
				$this->insert->bindValue(":setorusucadastro", $setorusucadastro);	
				$this->insert->bindValue(":codsetorusucadastro", $codsetorusucadastro);	
				$this->insert->execute();
							
			}catch(PDOExeption $e){
				die($e->getMessage());
			}
        }
        public function insertProDocumentos($id_protocolo, $nome, $numero, $qtde_paginas, $nome_aleatorio, $nome_anexo) {
                
			$query = "INSERT INTO protocolo.protocolo_documentos (
												codprotocolo, 
												datacriacao, 
												nome, 
												numero, 
												qtdpaginas, 
												assunto, 
												codusucadastro, 
												ipusucadastro, 
												hostusucadastro, 
												caminho, 
												nomarquivo
												)
										VALUES (
												:codprotocolo, 
												:datacriacao, 
												:nome, 
												:numero, 
												:qtdpaginas, 
												:assunto, 
												:codusucadastro, 
												:ipusucadastro, 
												:hostusucadastro, 
												:caminho, 
												:nomarquivo
												);";

			$path = "view/pgs/protocolos/uploads/" . $nome_aleatorio;
			
			try{
				$this->insert = $this->MySql->prepare($query);
							$this->insert->bindValue(":codprotocolo", $id_protocolo, PDO::PARAM_STR);
							$this->insert->bindValue(":datacriacao", date("Y-m-d H:i:s"), PDO::PARAM_STR);
							$this->insert->bindValue(":nome", $nome, PDO::PARAM_STR);
							$this->insert->bindValue(":numero", $numero, PDO::PARAM_STR);
							$this->insert->bindValue(":qtdpaginas", $qtde_paginas, PDO::PARAM_STR);
							$this->insert->bindValue(":assunto", NULL, PDO::PARAM_STR);
							$this->insert->bindValue(":codusucadastro", $_SESSION['protocolo']['myId'], PDO::PARAM_STR);
							$this->insert->bindValue(":ipusucadastro", $_SESSION['protocolo']['myIp'], PDO::PARAM_STR);
							$this->insert->bindValue(":hostusucadastro", $_SESSION['protocolo']['myHostName'], PDO::PARAM_STR);
							$this->insert->bindValue(":caminho", $path, PDO::PARAM_STR);
							$this->insert->bindValue(":nomarquivo", $nome_anexo, PDO::PARAM_STR);
							$this->insert->execute();
							
			}catch(PDOExeption $e){
				die($e->getMessage());
			}
		}
		public function insertProArquivos($codprotocolo, $descricao, $tipo, $nome_aleatorio, $nome_anexo) {
                
			$query = "INSERT INTO protocolo.protocolo_anexos (
												codprotocolo, 
												datacriacao, 
												descricao, 
												tipo, 
												codusucadastro, 
												caminho, 
												nomarquivo
											) VALUES (
												:codprotocolo, 
												:datacriacao, 
												:descricao, 
												:tipo, 
												:codusucadastro, 
												:caminho,
												:nomarquivo
											);";

			$path = "view/pgs/protocolos/uploads/" . $nome_aleatorio;
			
			$codusucadastro = ( isset($_SESSION['protocolo']['myId']) ? $_SESSION['protocolo']['myId'] : NULL );
			
			try{
				$this->insert = $this->MySql->prepare($query);
				
				$this->insert->bindValue(":codprotocolo", $codprotocolo, PDO::PARAM_STR);
				$this->insert->bindValue(":datacriacao", date("Y-m-d H:i:s"), PDO::PARAM_STR);
				$this->insert->bindValue(":descricao", $descricao, PDO::PARAM_STR);
				$this->insert->bindValue(":tipo", $tipo, PDO::PARAM_STR);
				$this->insert->bindValue(":codusucadastro", $codusucadastro, PDO::PARAM_STR);
				$this->insert->bindValue(":caminho", $path, PDO::PARAM_STR);
				$this->insert->bindValue(":nomarquivo", $nome_anexo, PDO::PARAM_STR);
				
				if( $this->insert->execute() ){
					return true;
				}else{
					return false;
				}
			}catch(PDOExeption $e){
				die($e->getMessage());
			}
		}
        public function insertProDespacho($id_protocolo, $id_setor_origem, $id_setor_destino, $observacao){
                
			$Query = "INSERT INTO protocolo.protocolo_despachos (
												codprotocolo, 
												dataenviou, 
												codsetorigem, 
												codsetdestino, 
												observacao, 
												status, 
												codusuenviou, 
												ipusuenviou, 
												hostusuenviou, 
												datarecebeu, 
												codusurecebeu, 
												ipusurecebeu, 
												hostusurecebeu
										) VALUES (
												:codprotocolo, 
												:dataenviou, 
												:codsetorigem, 
												:codsetdestino, 
												:observacao, 
												:status, 
												:codusuenviou, 
												:ipusuenviou, 
												:hostusuenviou, 
												:datarecebeu, 
												:codusurecebeu, 
												:ipusurecebeu, 
												:hostusurecebeu
											);";

			//quando o setor de protocolos criar um protocolo ja automaticamente  o recebe.
			if($id_setor_origem == 7 && $id_setor_destino == 7){ //id 7 setor de protocolos
				$datarecebeu 	= date("Y-m-d H:i:s");
				$codusurecebeu 	= $_SESSION['protocolo']['myId'];
				$ipusurecebeu 	= $_SESSION['protocolo']['myIp'];
				$hostusurecebeu = $_SESSION['protocolo']['myHostName'];
				$status			= "R";
			}else{
				$datarecebeu 	= null;
				$codusurecebeu 	= null;
				$ipusurecebeu 	= null;
				$hostusurecebeu = null;
				$status			= "D";
			}
			
			try{
				$this->insert = $this->MySql->prepare($Query);
							$this->insert->bindValue(":codprotocolo", $id_protocolo, PDO::PARAM_STR);	
							$this->insert->bindValue(":dataenviou", date("Y-m-d H:i:s"), PDO::PARAM_STR);	
							$this->insert->bindValue(":codsetorigem", $id_setor_origem, PDO::PARAM_STR);	
							$this->insert->bindValue(":codsetdestino", $id_setor_destino, PDO::PARAM_STR);	
							$this->insert->bindValue(":observacao", nl2br($observacao), PDO::PARAM_STR);	
							$this->insert->bindValue(":status", $status, PDO::PARAM_STR);	
							$this->insert->bindValue(":codusuenviou", $_SESSION['protocolo']['myId'], PDO::PARAM_STR);	
							$this->insert->bindValue(":ipusuenviou", $_SESSION['protocolo']['myIp'], PDO::PARAM_STR);	
							$this->insert->bindValue(":hostusuenviou", $_SESSION['protocolo']['myHostName'], PDO::PARAM_STR);	
							$this->insert->bindValue(":datarecebeu", $datarecebeu, PDO::PARAM_STR);	
							$this->insert->bindValue(":codusurecebeu", $codusurecebeu, PDO::PARAM_STR);	
							$this->insert->bindValue(":ipusurecebeu", $ipusurecebeu, PDO::PARAM_STR);	
							$this->insert->bindValue(":hostusurecebeu", $hostusurecebeu, PDO::PARAM_STR);	
							
							if($this->insert->execute()){
								return $this->MySql->lastInsertId();
							}else{
								return '';
							}
			}catch(PDOExeption $e){
				die($e->getMessage());
			}
        }
//================================================================ SELECTS ====================================================================
        public function verificaSetorAtualProtocolos($codprotocolo) {
            $id_setor = $this->selectIdSetorPorSigla($_SESSION['protocolo']['mySetor']);
            
            $Query = "select * from protocolo.protocolos p where p.codprotocolo = :codprotocolo and p.codsetoratual = :id_setor;";
            
            try {
                    $this->search = $this->MySql->prepare($Query);
                    $this->search->bindValue(":codprotocolo", $codprotocolo);
                    $this->search->bindValue(":id_setor", $id_setor);
                    $this->search->execute();
            } catch(PDOExeption $e) {
                    die($e->getMessage());
            }
			
            if($show = $this->search->fetch(PDO::FETCH_OBJ))
                return true;
            else
                return false;
        }
		
		public function getDataTableSMSEnviados($cpfremetente,$numprotocolo) {
			
            $Query = "SELECT * from protocolo.protocolo_sms p 
							inner join seguranca.seg_usuario u on u.sus_id = p.codusucadastro
						WHERE p.cpfremetente = :cpfremetente and p.numprotocolo = :numprotocolo
							ORDER BY p.codsms desc;";
            
            try {
                    $this->search = $this->MySql->prepare($Query);
                    $this->search->bindValue(":cpfremetente", $cpfremetente);
                    $this->search->bindValue(":numprotocolo", $numprotocolo);
                    $this->search->execute();
            } catch(PDOExeption $e) {
                    die($e->getMessage());
            }
			
			while($show = $this->search->fetch(PDO::FETCH_OBJ)){
				
				print "<tr>
							<td>". $show->codsms ."</td>
							<td>". $show->sus_nome . ' (' . $this->getDataHora($show->datcadastro)->format('d/m/Y H:i') .")</td>
							<td>". $show->numero ."</td>
							<td>". $show->cpfremetente ."</td>
							<td>". $show->numprotocolo ."</td>
							<td>". $show->msg ."</td>
							<td class='status_envio_". $show->falha ."'>". ( empty($show->falha) ? "Pendente" : $show->falha ) ."</td>
						</tr>";
			}
        }
		
		public function getDataTableEmailsEnviados($cpfremetente,$numprotocolo) {
			
            $Query = "SELECT 	e.codemail, 
								u.sus_nome, 
								e.datcadastro, 
								REPLACE(e.email, ',', '<br>') as email, 
								REPLACE(e.copia, ',', '<br>') as copia, 
								e.msg, 
								e.falha, 
								GROUP_CONCAT('<br><br>',CONCAT(pd.nome,' (',pd.nomarquivo,')')) as anexos 
						FROM protocolo.protocolo_email e 
							INNER JOIN seguranca.seg_usuario u ON u.sus_id = e.codusucadastro
							LEFT JOIN protocolo.protocolo_email_anexos pea ON pea.codemail = e.codemail
								LEFT JOIN protocolo.protocolo_documentos pd ON pd.codprodocumento = pea.codprodocumento
						WHERE e.cpfremetente = :cpfremetente AND e.numprotocolo = :numprotocolo
							GROUP BY e.codemail
								ORDER BY e.codemail desc;";
            
            try {
                    $this->search = $this->MySql->prepare($Query);
                    $this->search->bindValue(":cpfremetente", $cpfremetente);
                    $this->search->bindValue(":numprotocolo", $numprotocolo);
                    $this->search->execute();
            } catch(PDOExeption $e) {
                    die($e->getMessage());
            }
			
			while($show = $this->search->fetch(PDO::FETCH_OBJ)){
				
				print "<tr>
							<td>". $show->codemail ."</td>
							<td>". $show->sus_nome . ' (' . $this->getDataHora($show->datcadastro)->format('d/m/Y H:i') .")</td>
							<td>". $show->email ."</td>
							<td>". str_replace(',','<br>',$show->copia) ."</td>
							<td>". $show->msg ."</td>
							<td>". $show->anexos ."</td>
							<td class='status_envio_". $show->falha ."'>". ( empty($show->falha) ? "Pendente" : $show->falha ) ."</td>
						</tr>";
			}
        }
        
        public function getEmailsEnviados($cpfremetente,$numprotocolo) {
        	
        	$Query = "SELECT 	e.codemail,
								u.sus_nome,
								e.datcadastro,
								REPLACE(e.email, ',', '<br>') as email,
								REPLACE(e.copia, ',', '<br>') as copia,
								e.msg,
								e.falha,
								GROUP_CONCAT('<br><br>',CONCAT(pd.nome,' (',pd.nomarquivo,')')) as anexos
						FROM protocolo.protocolo_email e
							INNER JOIN seguranca.seg_usuario u ON u.sus_id = e.codusucadastro
							LEFT JOIN protocolo.protocolo_email_anexos pea ON pea.codemail = e.codemail
								LEFT JOIN protocolo.protocolo_documentos pd ON pd.codprodocumento = pea.codprodocumento
						WHERE e.cpfremetente = :cpfremetente AND e.numprotocolo = :numprotocolo
							GROUP BY e.codemail
								ORDER BY e.codemail desc;";
        	
        	try {
        		$this->search = $this->MySql->prepare($Query);
        		$this->search->bindValue(":cpfremetente", $cpfremetente);
        		$this->search->bindValue(":numprotocolo", $numprotocolo);
        		$this->search->execute();
        	} catch(PDOExeption $e) {
        		die($e->getMessage());
        	}
        	
        	$array=array();
        	
        	while($show = $this->search->fetch(PDO::FETCH_OBJ)){
        		$array[] = $show;
        	}
        	
        	return $array;
        }
		
		public function dadosProtocoloPeloNumero($numeroProtocolo){
			
			$_query = "SELECT * FROM protocolo.protocolos p 
							LEFT JOIN protocolo.protocolo_tipos pt on pt.codtipo = p.codtipo 
								WHERE p.numprotocolo = :numprotocolo;";
			
			$p_sql = $this->MySql->prepare($_query);
			$p_sql->bindValue(":numprotocolo", $numeroProtocolo);
			
			if($p_sql->execute()) {
				if($show = $p_sql->fetch(PDO::FETCH_ASSOC)) {
					return $show;
				}else {
					return '';
				}
			}else {
				die('Erro ao verificar numero de protocolo, entre em contato com o TI.');
			}
		}
		
        public function verificaNumeroSequencia() {
			
			//busca pelo ultimo numero gerado na data atual		
			
			$_query = "SELECT p.numprotocolo from protocolo.protocolo_eventos e
						  inner join protocolo.protocolos p on p.codprotocolo = e.codprotocolo
						  where e.evento = 'cadastrado' and DATE_FORMAT(e.datacriacao, '%Y-%m-%d') = DATE(curdate())
							and p.canatendimento != 'T'
								ORDER BY e.datacriacao DESC, e.codproevento DESC LIMIT 1;";
			
			$ano = date('Y');
			
			$p_sql = $this->MySql->prepare($_query);
			$p_sql->bindValue(":ano", $ano);
			
			if($p_sql->execute()) {
				if($show = $p_sql->fetch(PDO::FETCH_ASSOC)) {
					
					$numero_protocolo = $show["numprotocolo"];
					$seq = substr($numero_protocolo,15,20);
					$num = substr($numero_protocolo,0,-5);
					
					return $num . str_pad(($seq + 1), 5, "0", STR_PAD_LEFT);
				}else {
					$reg_ans = "311961";
					$diaMesAno = date('Ymd');
					return $reg_ans.$diaMesAno.'100000';
				}
			}else {
				die('Erro ao buscar numero de protocolo, entre em contato com o TI.');
			}
			
        }
		
        public function selectIdSetorPorSigla($sigla_setor) {

            $Query = "select cs.cse_id from cadastro.cad_setor cs where cs.cse_setor_sigla = :sigla_setor;";
		
			try {
					$this->search = $this->MySql->prepare($Query);
					$this->search->bindValue(":sigla_setor", $sigla_setor);
					$this->search->execute();
			} catch(PDOExeption $e) {
					die($e->getMessage());
			}
                
			if($show = $this->search->fetch(PDO::FETCH_OBJ))
				return $show->cse_id;
			else
				return "";
		}
		public function selectSiglaSetor($cse_id) {

            $Query = "select cs.cse_setor_sigla from cadastro.cad_setor cs where cs.cse_id = :cse_id;";
		
			try {
					$this->search = $this->MySql->prepare($Query);
					$this->search->bindValue(":cse_id", $cse_id);
					$this->search->execute();
			} catch(PDOExeption $e) {
					die($e->getMessage());
			}
                
			if($show = $this->search->fetch(PDO::FETCH_OBJ))
				return $show->cse_setor_sigla;
			else
				return "";
		}
        public function selectDadosProtocoloPorId($id_protocolo) {
			
			$Query = "select 
						pro.*,
						OBTER_CLASSE_COR_PROTOCOLO(pro.status) as cor_classe,
						OBTER_DESCRICAO_STATUS_PROTOCOLO(pro.status) as descricaoStatus,
						OBTER_DESCRICAO_CANAL_ATENDIMENTO(pro.canatendimento) as descricaoCanalAtendimento,
						pt.*,
						cs.*,
						SUBSTRING_INDEX(SUBSTRING_INDEX(pro.nomeremetente, ' ', 1), ' ', -1)  AS primeiroNome,
						a.senha, 
						a.codatendimento, 
						a.datahorafim, 
						a.tipo as tipoAtendimento,
						pm.descricao as motivo, 
						pro.codprotocolo,
						DATE_FORMAT(pe.datacriacao,'%d/%m/%Y %H:%i:%s') as datacriacao,
						pe.datacriacao as datacriacao_ing,
						DATE_FORMAT(DATE_ADD(pe.datacriacao,INTERVAL pt.prazo DAY),'%d/%m/%Y') as dt_vencimento,
						TIMESTAMPDIFF(DAY,DATE_FORMAT(DATE_ADD(pe.datacriacao,INTERVAL pt.prazo DAY),'%Y-%m-%d %H:%i:%s'),current_date()) as dias_vencimento,
						DATE_FORMAT(a.datcadastro,'%d/%m/%Y %H:%i') as datatendimento
					from protocolo.protocolos pro 
						inner join cadastro.cad_setor cs on cs.cse_id = pro.codsetoratual
						inner join protocolo.protocolo_eventos pe on pe.codprotocolo = pro.codprotocolo and pe.evento = 'cadastrado'
						inner join protocolo.protocolo_tipos pt on pt.codtipo = pro.codtipo
						left join protocolo.protocolo_motivos pm on pm.codmotivo = pro.codmotivo
						left join protocolo.protocolo_atendimento pa on pa.codprotocolo = pro.codprotocolo
                        left join protocolo.atendimento a on a.codatendimento = pa.codatendimento
							where pro.codprotocolo = :id_protocolo;";
			
			//print_r($Query);
			//exit;
               
			try {
				$this->search = $this->MySql->prepare($Query);
				$this->search->bindValue(":id_protocolo", $id_protocolo);
				$this->search->execute();
			} catch(PDOExeption $e) {
				die($e->getMessage());
			}
			
			if($show = $this->search->fetch(PDO::FETCH_OBJ)){
				return $show;
			}else{
				return "";
			}
        }
		
        public function getDataTableDocumentosAnexados($id_protocolo, $status){
			
			$Query = "select pd.codprotocolo, pd.codprodocumento, pd.nome, pd.numero, pd.qtdpaginas, pd.datacriacao, pd.nomarquivo, pd.caminho, su.sus_nome, cs.cse_setor_descricao 
						from protocolo.protocolo_documentos pd
							inner join seguranca.seg_usuario su on su.sus_id = pd.codusucadastro
							inner join cadastro.cad_setor cs on cs.cse_id = su.cse_id_protocolo
						where pd.codprotocolo = :id_protocolo AND pd.status = 'A' order by pd.datacriacao;";
			
			try {
					$this->search = $this->MySql->prepare($Query);
					$this->search->bindValue(":id_protocolo", $id_protocolo);
					$this->search->execute();
			} catch(PDOExeption $e) {
					die($e->getMessage());
			}
			
			while($show = $this->search->fetch(PDO::FETCH_OBJ)){
				
				print "<tr id='row'>
					<td>".$show->nome."</td>
					<td>".$show->numero."</td>
					<td>".$show->qtdpaginas."</td>
					<td>".$show->sus_nome."</td>
					<td>".$show->cse_setor_descricao."</td>
					<td>".$this->getDataHora($show->datacriacao)->format('d/m/Y H:i:s')."</td>
					<td><a href='http://unimedweb.unimedmanaus.com.br/protocolos/". $show->caminho ."' target='_blank'>".$show->nomarquivo."</a></td>";
					
					if($status == '1' && $show->cse_setor_descricao == $_SESSION['protocolo']['mySetorDesc']){
						$img = "<img data-codprodocumento='". $show->codprodocumento ."' data-nomedocumento='". $show->nome ."' src='view/img/remove.png' class='removeRow' />";
					}else{
						$img = "--/--";
					}
					
					print "<td>$img</td>";
				
				print "</tr>";
			}
		}
			 
		public function getDataTableDocumentosAnexadosImpressao($id_protocolo){
			
			$Query = "SELECT pd.codprotocolo, pd.codprodocumento, pd.nome, pd.numero, pd.qtdpaginas,
								pd.datacriacao, pd.nomarquivo, pd.caminho, su.sus_nome, cs.cse_setor_descricao 
						FROM protocolo.protocolo_documentos pd
							INNER JOIN seguranca.seg_usuario su on su.sus_id = pd.codusucadastro
							INNER JOIN cadastro.cad_setor cs on cs.cse_id = su.cse_id_protocolo
						WHERE pd.codprotocolo = :id_protocolo AND pd.status = 'A';";
			
			try {
				$this->search = $this->MySql->prepare($Query);
				$this->search->bindValue(":id_protocolo", $id_protocolo);
				$this->search->execute();
			}catch(PDOExeption $e) {
				die($e->getMessage());
			}
				
			while($show = $this->search->fetch(PDO::FETCH_OBJ)) {
				print "<tr id='row'>
					<td>".$show->nome."</td>
					<td>".$show->numero."</td>
					<td>".$show->qtdpaginas."</td>
					<td>".$show->sus_nome."</td>
					<td>".$show->cse_setor_descricao."</td>
					<td>".$this->getDataHora($show->datacriacao)->format('d/m/Y H:i:s')."</td>
					<td><a href='". $show->caminho ."' target='_blank'>".$show->nomarquivo."</a></td>
					</tr>";
			}
		}
		
		public function anexosDoProtocolo($id_protocolo){
			
			$Query = "select * from protocolo.protocolo_documentos pd
						where pd.codprotocolo = :id_protocolo AND pd.status = 'A' order by pd.datacriacao;";
			
			try {
					$this->search = $this->MySql->prepare($Query);
					$this->search->bindValue(":id_protocolo", $id_protocolo);
					$this->search->execute();
			} catch(PDOExeption $e) {
					die($e->getMessage());
			}
			
			$array=array();
			
			while($show = $this->search->fetch(PDO::FETCH_OBJ)){
				$array[] = $show;
			}
			
			return $array;
		}
		
		public function retornaDadosDoAnexo($codprodocumento){
			
			$Query = "select * from protocolo.protocolo_documentos pd where pd.codprodocumento = :codprodocumento;";
			
			try {
					$this->search = $this->MySql->prepare($Query);
					$this->search->bindValue(":codprodocumento", $codprodocumento);
					$this->search->execute();
			} catch(PDOExeption $e) {
					die($e->getMessage());
			}
			
			if($show = $this->search->fetch(PDO::FETCH_ASSOC)){
				return $show;
			}else{
				return '';
			}
		}
			
        public function getDataTableDespachos($id_protocolo) {
				
				$Query = "select pd.codprodespacho ,pd.status, cs1.cse_setor_descricao as setor_origem, cs2.cse_setor_descricao as setor_destino, su1.sus_nome as nome_enviou, pd.dataenviou, su2.sus_nome as nome_recebeu, pd.datarecebeu, pd.observacao 
							from protocolo.protocolo_despachos pd
							inner join seguranca.seg_usuario su1 on su1.sus_id = pd.codusuenviou
							left join seguranca.seg_usuario su2 on su2.sus_id = pd.codusurecebeu
							inner join cadastro.cad_setor cs1 on cs1.cse_id = pd.codsetorigem
							left join cadastro.cad_setor cs2 on cs2.cse_id = pd.codsetdestino
							where pd.codprotocolo = :id_protocolo; order by pd.dataenviou";

                try {
                        $this->search = $this->MySql->prepare($Query);
                        $this->search->bindValue(":id_protocolo", $id_protocolo);
                        $this->search->execute();
                } catch(PDOExeption $e) {
                        die($e->getMessage());
                }
                
                while($show = $this->search->fetch(PDO::FETCH_OBJ)) {
					print "<tr id='row'>
							<td>".$show->codprodespacho."</td>
							<td>".$this->defineNomeStatusDespacho($show->status)."</td>
							<td>".$show->setor_origem."</td>
							<td>".$show->setor_destino."</td>
							<td>".$show->nome_enviou. ' - ' .$this->getDataHora($show->dataenviou)->format('d/m/Y H:i:s')."</td>
							<td>".(!empty($show->datarecebeu) ? ($show->nome_recebeu. ' - ' .$this->getDataHora($show->datarecebeu)->format('d/m/Y H:i:s')) : '--/--')."</td>
							<td>".(!empty($show->observacao) ? $show->observacao : '--/--')."</td>
							</tr>";
                    }
        }       
		
        public function verificaSetorPodeVerDespachos($id_protocolo, $sigla_setor){
			
            $id_setor = $this->selectIdSetorPorSigla($sigla_setor);
			
            $Query = "select * from protocolo.protocolo_despachos pd where pd.codprotocolo = :id_protocolo and pd.codsetdestino = :id_setor;";
            
            try {
                    $this->search = $this->MySql->prepare($Query);
                    $this->search->bindValue(":id_protocolo", $id_protocolo);
                    $this->search->bindValue(":id_setor", $id_setor);
                    $this->search->execute();
            } catch(PDOExeption $e) {
                    die($e->getMessage());
            }
            if($show = $this->search->fetch(PDO::FETCH_OBJ)){
                return true;
            }else{
                return false;
            }
        }
        public function defineNomeStatusDespacho($status){
            switch ($status) {
                case 'D':
                    return 'Despachado';
                    break;
                case 'R':
                    return 'Recebido';
                    break;
                default:
                    break;
            }
        }
        public function getDataTableEventos($id_protocolo){
			  
			$Query = "select pe.codproevento, pe.datacriacao, pe.evento, cd.cse_setor_descricao, sus.sus_nome, pe.observacao, pe.setorusucadastro
						from protocolo.protocolo_eventos pe 
							inner join seguranca.seg_usuario sus on sus.sus_id = pe.codusucadastro
							inner join cadastro.cad_setor cd on cd.cse_id = sus.cse_id_protocolo
								where pe.codprotocolo = :id_protocolo 
									order by pe.codproevento desc";
			
			try {
				$this->search = $this->MySql->prepare($Query);
				$this->search->bindValue(":id_protocolo", $id_protocolo);
				$this->search->execute();
			} catch(PDOExeption $e) {
					die($e->getMessage());
			}
			
			while($show = $this->search->fetch(PDO::FETCH_OBJ)){
				
				print "<tr id='row'>
						<td>".$show->codproevento."</td>
						<td>".$show->evento."</td>
						<td>".$show->sus_nome." - ". $show->cse_setor_descricao ."</td>
						<td>".$show->setorusucadastro."</td>
						<td>".$this->getDataHora($show->datacriacao)->format('d/m/Y H:i:s')."</td>
						<td>".(!empty($show->observacao) ? $show->observacao : '--/--')."</td>
					</tr>";
			}
        }
		public function RetornaDadosEventoCadastro($id_protocolo){
			
			$Query = "select pe.datacriacao, pe.evento, cd.cse_setor_descricao ,sus.sus_nome, pe.observacao 
						from protocolo.protocolo_eventos pe 
							inner join seguranca.seg_usuario sus on sus.sus_id = pe.codusucadastro
							inner join cadastro.cad_setor cd on cd.cse_id = sus.cse_id_protocolo
								where pe.codprotocolo = :id_protocolo and (pe.evento = 'cadastrado' OR pe.evento = 'finalizado') order by pe.datacriacao";
			
			try {
					$this->search = $this->MySql->prepare($Query);
					$this->search->bindValue(":id_protocolo", $id_protocolo);
					$this->search->execute();
			} catch(PDOExeption $e) {
					die($e->getMessage());
			}
			while($show = $this->search->fetch(PDO::FETCH_OBJ)){
				print "<tr id='row'>
					<td>".$show->evento."</td>
					<td>".$show->sus_nome."</td>
					<td>".$show->cse_setor_descricao."</td>
					<td>".  $this->getDataHora($show->datacriacao)->format('d/m/Y H:i:s')."</td>
					<td>".(!empty($show->observacao)?$show->observacao:'--/--')."</td>
				</tr>";
			}
        }
        public function selectConsultaProtocolos($dados){
            
			$id_meu_setor = $this->selectIdSetorPorSigla($_SESSION['protocolo']['mySetor']);
			
			$where = Array();
			
			$numero 			= $dados[0]['value'];
			$cpfcnpj 			= $dados[1]['value'];
			$numprotocologpu 	= $dados[2]['value'];
			
			if( !empty($numero) ){ $where[] = " numprotocolo = :numero"; }
			if( !empty($cpfcnpj) ){ $where[] = " cpfremetente = :cpfcnpj"; }
			if( !empty($numprotocologpu) ){ $where[] = " numprotocologpu = :numprotocologpu"; }
			
			$Query = "	select 	pro.codprotocolo, 
								pro.status, 
								pro.numprotocologpu, 
								pro.numprotocolo, 
								pe.datacriacao, 
								pro.codsetoratual, 
								cs.cse_setor_descricao as setor_atual, 
								pro.nomeremetente, 
								pro.cpfremetente, 
								pt.tipo,
								pro.assunto, 
								pd.status as status_despacho,
								pro.clientepodeeditar
								
						from protocolo.protocolos pro
							inner join cadastro.cad_setor cs on cs.cse_id = pro.codsetoratual
							inner join protocolo.protocolo_eventos pe on pe.codprotocolo = pro.codprotocolo and pe.evento = 'cadastrado'
							left join protocolo.protocolo_despachos pd on pd.codprodespacho = pro.coddespachoatual
							left join protocolo.protocolo_tipos pt on pt.codtipo = pro.codtipo
							";
			
			if( sizeof( $where ) )
			$Query .= ' WHERE '.implode( ' AND ',$where );
		
			$Query .= ";";
			
			try {
				
				$this->search = $this->MySql->prepare($Query);
				
				if( !empty($numero) ){ $this->search->bindValue(":numero", $numero); }
				if( !empty($cpfcnpj) ){ $this->search->bindValue(":cpfcnpj", $cpfcnpj); }
				if( !empty($numprotocologpu) ){ $this->search->bindValue(":numprotocologpu", $numprotocologpu); }
				
				$this->search->execute();
				
			} catch(PDOExeption $e) {
				die($e->getMessage());
			}
			
			$array = array();
			$cont = 0;
			
			while($show = $this->search->fetch(PDO::FETCH_OBJ)){
					
				$array[$cont] = array(
					"codprotocolo"  	=> $show->codprotocolo,
					"status"        	=> $this->defineNomeStatusProtocolo($show->status),
					"codstatus"     	=> $show->status,
					"numprotocolo"  	=> $show->numprotocolo,  
					"numprotocologpu"  	=> $show->numprotocologpu,  
					"datacriacao"  		=> $this->getDataHora($show->datacriacao)->format('d/m/Y H:i:s'),
					"setor_atual"   	=> $show->setor_atual,
					"cpfremetente"  	=> $show->cpfremetente,
					"nomeremetente" 	=> $show->nomeremetente,
					"tipo" 				=> $show->tipo,
					"assunto"       	=> $show->assunto,
					"clientepodeeditar" => $show->clientepodeeditar
				);
				$cont++;
			}
				
			print(json_encode($array));
        }
		public function protocolosDoBeneficiario($carteirinha){
				
			$Query = "select 	pro.codprotocolo, 
								pro.status, 
								pro.numprotocologpu, 
								pro.numprotocolo, 
								pro.anoprotocolo, 
								pro.codsetoratual, 
								pro.nomeremetente, 
								pro.assunto, 
								pro.canatendimento,
								pro.clientepodeeditar,
								cs.cse_setor_descricao as setor_atual, 
								pd.status as status_despacho,
								pt.tipo,
								pe.datacriacao
								
							from protocolo.protocolos pro
								inner join protocolo.protocolo_eventos pe on pe.codprotocolo = pro.codprotocolo and pe.evento = 'cadastrado'
								inner join cadastro.cad_setor cs on cs.cse_id = pro.codsetoratual
								left join protocolo.protocolo_despachos pd on pd.codprodespacho = pro.coddespachoatual
								left join protocolo.protocolo_tipos pt on pt.codtipo = pro.codtipo
							where cpfremetente = :carteirinha
								order by pe.datacriacao desc;";
                
			try {
					$this->search = $this->MySql->prepare($Query);
					$this->search->bindValue(":carteirinha", $carteirinha);
					$this->search->execute();
			} catch(PDOExeption $e) {
					die($e->getMessage());
			}
				
			$array = array();
			$cont = 0;
			
			while($show = $this->search->fetch(PDO::FETCH_OBJ)){
				
				$array[$cont] = array(
					"codprotocolo"  	=> $show->codprotocolo,
					"status"        	=> $this->defineNomeStatusProtocolo($show->status),
					"codstatus"     	=> $show->status,
					"numprotocolo"  	=> $show->numprotocolo,  
					"numprotocologpu" 	=> $show->numprotocologpu,  
					"anoprotocolo"  	=> $show->anoprotocolo,
					"setor_atual"   	=> $show->setor_atual,
					"nomeremetente" 	=> $show->nomeremetente,
					"assunto"       	=> $show->assunto,
					"tipo"       		=> $show->tipo,
					"datacriacao"   	=> $this->getDataHora($show->datacriacao)->format('d/m/Y H:i:s'),
					"datacriacao_ing"	=> $show->datacriacao,
					"clientepodeeditar" => $show->clientepodeeditar
				);
				$cont++;
			}
			
			print(json_encode($array));
        }
		public function mostraQuantidadesPendentes(){
            
			$Query = "select sum(q) as quantidade 
							from (
								select count(*) as q from protocolo.protocolos p 
									where p.status = 'P' and p.codsetoratual = :myIdSetor and p.coddespachoatual is null
										 -- and ( p.clientepodeeditar is null or p.clientepodeeditar = 'N' )
								union all                 
								
								select count(*) as q from protocolo.protocolos p 
									inner join protocolo.protocolo_despachos pd on pd.codprodespacho = p.coddespachoatual
									   and pd.status != 'D'
											where p.status = 'P' and p.codsetoratual = :myIdSetor
												 -- and ( p.clientepodeeditar is null or p.clientepodeeditar = 'N' )
							)as q;";
			
            try {
				$this->search = $this->MySql->prepare($Query);
				$this->search->bindValue(":myIdSetor", $_SESSION['protocolo']['myIdSetor']);
				$this->search->execute();
				
            } catch(PDOExeption $e) {
                    die($e->getMessage());
            }
			
            if($show = $this->search->fetch(PDO::FETCH_OBJ)){
				return $show->quantidade;
            }else{
				return "";
            }
        }
        public function mostraQuantidadesReceber(){
            
            $Query = "select count(*) as quantidade 
						from protocolo.protocolos p 
							inner join protocolo.protocolo_despachos pd on pd.codprodespacho = p.coddespachoatual
                                and pd.codsetdestino = :mySetor 
								and pd.status = 'D' 
							where p.status = 'P' 
							-- and ( p.clientepodeeditar is null or p.clientepodeeditar = 'N' )
							;";
            
            try {
                    $this->search = $this->MySql->prepare($Query);
                    $this->search->bindValue(":mySetor", $_SESSION['protocolo']['myIdSetor']);
                    $this->search->execute();
            } catch(PDOExeption $e) {
                    die($e->getMessage());
            }
			
            if($show = $this->search->fetch(PDO::FETCH_OBJ)){
                    return $show->quantidade;
            }else{
                    return "";
            }
        }
		public function notificacaoChatRecebido(){
            
            $Query = "SELECT 
							pro.codprotocolo, 
							pro.numprotocolo, 
							pro.nomeremetente, 
							SUBSTRING_INDEX(SUBSTRING_INDEX(pro.nomeremetente, ' ', 1), ' ', -1)  AS primeiroNome,
							DATE_FORMAT(p.datcadastro,'%d/%m %H:%i') as data,
							count(p.codchat) as qtde_msg
						FROM protocolo.protocolo_chat p 
							INNER JOIN protocolo.protocolos pro on pro.codprotocolo = p.codprotocolo and pro.status = 'P' and pro.codsetoratual = :myIdSetor
								WHERE p.notificado is null AND p.codusuario is null
									group by p.codprotocolo
										order by p.codchat desc;";
            
            try {
                    $this->search = $this->MySql->prepare($Query);
                    $this->search->bindValue(":myIdSetor", $_SESSION['protocolo']['myIdSetor']);
                    $this->search->execute();
            } catch(PDOExeption $e) {
                    die($e->getMessage());
            }
			
			$arr=array();
			
            while($show = $this->search->fetch(PDO::FETCH_ASSOC)){
				$arr[] = $show;
            }
			return $arr;
        }
		
		public function notificacaoChatEncerrar($codprotocolo){
            
            $query = "UPDATE protocolo.protocolo_chat SET notificado = 'S' where codprotocolo = :codprotocolo;";
			
            try{
				$this->insert = $this->MySql->prepare($query);
				$this->insert->bindValue(":codprotocolo", $codprotocolo, PDO::PARAM_STR);
				$this->insert->execute();
            }catch(PDOExeption $e){
                    die($e->getMessage());
            }    
        }
		
		public function clientePodeEditar($codprotocolo,$clientepodeeditar){
			
			$query = "UPDATE protocolo.protocolos SET clientepodeeditar = '$clientepodeeditar' WHERE codprotocolo = :codprotocolo;";
			
            try{
				$this->insert = $this->MySql->prepare($query);
				$this->insert->bindValue(":codprotocolo", $codprotocolo, PDO::PARAM_STR);
				if( $this->insert->execute() ){
					return true;
				}else{
					return false;
				}
            }catch(PDOExeption $e){
                    die($e->getMessage());
            } 
		}
		
		public function mostraQuantidadesInativados(){
            				
			$Query = "SELECT count(*) as quantidade
							FROM protocolo.protocolos p 
								WHERE p.codprotocolo = (
									SELECT e.codprotocolo 
										FROM protocolo.protocolo_eventos e
											inner join seguranca.seg_usuario u on u.sus_id = e.codusucadastro
										WHERE p.codsetoratual = :myIdSetor AND e.codprotocolo = p.codprotocolo and e.evento = 'inativado'
											ORDER BY e.datacriacao DESC 
												LIMIT 1
								)
							and p.status = 'I' 
							and p.codsetoratual = :myIdSetor
							-- and ( p.clientepodeeditar is null or p.clientepodeeditar = 'N' );
							";
            
            try {
				$this->search = $this->MySql->prepare($Query);
				$this->search->bindValue(":myIdSetor", $_SESSION['protocolo']['myIdSetor']);
				$this->search->execute();
				
            } catch(PDOExeption $e) {
				die($e->getMessage());
            }
			
            if($show = $this->search->fetch(PDO::FETCH_OBJ)){
				return $show->quantidade;
            }else{
				return "";
            }
        }
		
        public function mostraQuantidadesCadastrados() {
            
            $Query = "SELECT 
							count(DISTINCT p.codprotocolo) as quantidade
						FROM protocolo.protocolos p 
								inner join cadastro.cad_setor cs on cs.cse_id = p.codsetoratual
								inner join protocolo.protocolo_eventos pe on pe.codprotocolo = p.codprotocolo and pe.evento = 'cadastrado'
								inner join seguranca.seg_usuario sus on sus.sus_id = pe.codusucadastro
						WHERE pe.codsetorusucadastro = :myIdSetor
							 and p.canatendimento != 'W'";
            
            try {
                    $this->search = $this->MySql->prepare($Query);
                    $this->search->bindValue(":myIdSetor", $_SESSION['protocolo']['myIdSetor']);
                    $this->search->execute();
					
            } catch(PDOExeption $e) {
                    die($e->getMessage());
            }
			
            if($show = $this->search->fetch(PDO::FETCH_OBJ)){
				return $show->quantidade;
            }else{
				return "";
			}
        }
		public function mostraQuantidadesFinalizados() {
            
            $Query = "SELECT 
							count(DISTINCT p.codprotocolo) as quantidade
						FROM protocolo.protocolos p 
								inner join cadastro.cad_setor cs on cs.cse_id = p.codsetoratual
								inner join protocolo.protocolo_eventos pe on pe.codprotocolo = p.codprotocolo and pe.evento = 'finalizado'
								inner join seguranca.seg_usuario sus on sus.sus_id = pe.codusucadastro
						WHERE p.codsetoratual = :myIdSetor AND p.status = 'F'";
            
            try {
                    $this->search = $this->MySql->prepare($Query);
                    $this->search->bindValue(":myIdSetor", $_SESSION['protocolo']['myIdSetor']);
                    $this->search->execute();
            } catch(PDOExeption $e) {
                    die($e->getMessage());
            }
			
            if($show = $this->search->fetch(PDO::FETCH_OBJ)){
				return $show->quantidade;
            }else{
				return "";
			}
        }
		
        public function mostraQuantidadesParaAcompanhamento() {
            
            $setor = $this->selectIdSetorPorSigla($_SESSION['protocolo']['mySetor']);
            
            $Query = "select count(distinct(p.codprotocolo)) as quantidade from protocolo.protocolos p 
						inner join protocolo.protocolo_eventos pe on pe.codprotocolo = p.codprotocolo and pe.evento = 'cadastrado'
						inner join seguranca.seg_usuario u on u.sus_id = pe.codusucadastro -- and u.sus_setor != :setor_atual
                        inner join protocolo.protocolo_despachos pd on pd.codprotocolo = p.codprotocolo and pd.codsetorigem = :cod_setor_destino
							where IF(pe.setorusucadastro is not null,pe.setorusucadastro,u.sus_setor) != :setor_atual;";
            
            try {
                    $this->search = $this->MySql->prepare($Query);
                    $this->search->bindValue(":cod_setor_destino", $setor);
					$this->search->bindValue(":setor_atual", $_SESSION['protocolo']['mySetor']);
                    $this->search->execute();
            } catch(PDOExeption $e) {
                    die($e->getMessage());
            }
			
            if($show = $this->search->fetch(PDO::FETCH_OBJ)){
                    return $show->quantidade;
            }else{
                    return "";
            }
        }
        public function getDataTableListaProtocolosParaAcompanhamento(){
            
            $id_setor_destino = $this->selectIdSetorPorSigla($_SESSION['protocolo']['mySetor']);
            
            $Query = "select 	pd.codprodespacho, 
								p.codprotocolo, 
								p.status, 
								p.numprotocolo, 
								pe.datacriacao, 
								DATE_FORMAT(DATE_ADD(pe.datacriacao,INTERVAL pt.prazo DAY),'%d/%m/%Y') as dt_vencimento,
								TIMESTAMPDIFF(DAY,DATE_FORMAT(DATE_ADD(pe.datacriacao,INTERVAL pt.prazo DAY),'%Y-%m-%d %H:%i:%s'),current_date()) as dias_vencimento,
								cd.cse_setor_descricao, 
								p.nomeremetente, 
								p.assunto, 
								pt.tipo, 
								pt.prazo, 
								p.canatendimento, 
								pm.descricao as motivo
                            from protocolo.protocolos p 
                                inner join protocolo.protocolo_eventos pe on pe.codprotocolo = p.codprotocolo and pe.evento = 'cadastrado'
								inner join seguranca.seg_usuario u on u.sus_id = pe.codusucadastro
                                inner join cadastro.cad_setor cd on cd.cse_id = p.codsetoratual
                                inner join protocolo.protocolo_despachos pd on pd.codprotocolo = p.codprotocolo and pd.codsetorigem = :codsetdestino
								inner join protocolo.protocolo_tipos pt on pt.codtipo = p.codtipo
								left join protocolo_motivos pm ON pm.codmotivo = p.codmotivo
							where IF(pe.setorusucadastro is not null,pe.setorusucadastro,u.sus_setor) != :setor_usuario
								group by p.codprotocolo
									order by p.codprotocolo desc;";
            
            try {
                    $this->search = $this->MySql->prepare($Query);
                    $this->search->bindValue(":codsetdestino", $id_setor_destino);
					$this->search->bindValue(":setor_usuario", $_SESSION['protocolo']['mySetor']);
                    $this->search->execute();
					
            } catch(PDOExeption $e) {
                    die($e->getMessage());
            }
			
            while($show = $this->search->fetch(PDO::FETCH_OBJ)){
				
				if( $show->status == 'P' ){
					$dias_vencimento = ($show->dias_vencimento > 0 ? "Vencido há " : "Vence em ") . abs($show->dias_vencimento) . " Dia(s)";
				}else{
					$dias_vencimento = "--/--";
				}
				
				print "<tr id='row'>
							<td>
								<input type='hidden' name='codprotocolo' value='".$show->codprotocolo."'/>
								<input type='hidden' name='codprodespacho' value='".$show->codprodespacho."'/>
								<img src='view/jvs/treeview/img/check.png' />
							</td>
							<td><span class='label label-". $this->defineCorStatusProtocolo($show->status) ."'>". $this->defineNomeStatusProtocolo($show->status) ."</span></td>
							<td>".$this->getDataHora($show->datacriacao)->format('d/m/Y H:i:s')."</td>
							<td>".$show->prazo." Dias</td>
							<td>".$show->dt_vencimento."</td>
							<td><span class='label label-". $this->defineCorVencimentoProtocolo($show->dias_vencimento,$show->status) ."'>".$dias_vencimento."</span></td>
							<td>".$show->numprotocolo."</td>
							<td>".$show->cse_setor_descricao."</td>
							<td style='text-align: left;'>".$show->nomeremetente."</td>
							<td style='text-align: left;'>".$this->ucwords_improved($show->assunto)."</td>
							<td style='text-align: left;'>".$show->tipo."</td>
							<td style='text-align: left;'>".$this->RetornaCanalAtendimento($show->canatendimento)."</td>
						</tr>";
            }
        }
        public function getDataTableListaProtocolosReceber(){
            
            $id_setor_destino = $this->selectIdSetorPorSigla($_SESSION['protocolo']['mySetor']);
            
            $Query = "select 
						pd.codprodespacho, 
						p.codprotocolo, 
						p.status, 
						p.numprotocolo, 
						pe.datacriacao, 
						DATE_FORMAT(DATE_ADD(pe.datacriacao,INTERVAL pt.prazo DAY),'%d/%m/%Y') as dt_vencimento,
						TIMESTAMPDIFF(DAY,DATE_FORMAT(DATE_ADD(pe.datacriacao,INTERVAL pt.prazo DAY),'%Y-%m-%d %H:%i:%s'),current_date()) as dias_vencimento,
						cd.cse_setor_descricao, 
						p.nomeremetente, 
						p.assunto, 
						pt.tipo, 
						pt.prazo, 
						p.canatendimento, 
						pm.descricao as motivo
					from protocolo.protocolos p 
                        inner join protocolo.protocolo_eventos pe on pe.codprotocolo = p.codprotocolo and pe.evento = 'cadastrado'
                        inner join protocolo.protocolo_despachos pd on pd.codprodespacho = p.coddespachoatual and pd.codsetdestino = :id_setor_destino and pd.status = 'D'
                        inner join cadastro.cad_setor cd on cd.cse_id = p.codsetoratual
						inner join protocolo.protocolo_tipos pt on pt.codtipo = p.codtipo
						left join protocolo_motivos pm ON pm.codmotivo = p.codmotivo
					where p.status = 'P' and ( p.clientepodeeditar is null or p.clientepodeeditar = 'N' )
						group by pd.codprodespacho, p.codprotocolo, p.status, p.numprotocolo, pe.datacriacao, cd.cse_setor_descricao, p.nomeremetente, p.assunto
							order by abs(TIMESTAMPDIFF(DAY,DATE_FORMAT(DATE_ADD(pe.datacriacao,INTERVAL pt.prazo DAY),'%Y-%m-%d %H:%i:%s'),current_date())) asc;";
            
            try {
                    $this->search = $this->MySql->prepare($Query);
                    $this->search->bindValue(":id_setor_destino", $id_setor_destino);
                    $this->search->execute();
					
            } catch(PDOExeption $e) {
                    die($e->getMessage());
            }
			
            while($show = $this->search->fetch(PDO::FETCH_OBJ)){
				
				$this->printTabelaConteudoHome($show);
			}
        }
		
		public function retornaProtocolosReceber(){
            
            $Query = "select 
						pd.codprodespacho, 
						p.codprotocolo, 
						p.status, 
                        OBTER_DESCRICAO_STATUS_PROTOCOLO(p.status) as desc_status,
						OBTER_CLASSE_COR_PROTOCOLO(p.status) as cor_classe,
						p.numprotocolo, 
						DATE_FORMAT(pe.datacriacao,'%d/%m/%Y %H:%i:%s') as datacriacao, 
						DATE_FORMAT(DATE_ADD(pe.datacriacao,INTERVAL pt.prazo DAY),'%d/%m/%Y') as dt_vencimento,
						TIMESTAMPDIFF(DAY,DATE_FORMAT(DATE_ADD(pe.datacriacao,INTERVAL pt.prazo DAY),'%Y-%m-%d %H:%i:%s'),current_date()) as dias_vencimento,
						cd.cse_setor_descricao, 
						p.nomeremetente, 
						p.assunto, 
                        p.codtipo,
						pt.tipo, 
						pt.prazo, 
						OBTER_DESCRICAO_CANAL_ATENDIMENTO(p.canatendimento) as canatendimento
						
					from protocolo.protocolos p 
							inner join protocolo.protocolo_eventos pe on pe.codprotocolo = p.codprotocolo and pe.evento = 'cadastrado'
							inner join protocolo.protocolo_despachos pd on pd.codprodespacho = p.coddespachoatual and pd.codsetdestino = :myIdSetor and pd.status = 'D'
							inner join cadastro.cad_setor cd on cd.cse_id = p.codsetoratual
							inner join protocolo.protocolo_tipos pt on pt.codtipo = p.codtipo
						
					where p.status = 'P' 
						  -- and ( p.clientepodeeditar is null or p.clientepodeeditar = 'N' )
						group by pd.codprodespacho, 
								 p.codprotocolo, 
								 p.status, 
								 p.numprotocolo, 
								 pe.datacriacao, 
								 cd.cse_setor_descricao, 
								 p.nomeremetente, 
								 p.assunto
							order by pt.tipo asc, abs(TIMESTAMPDIFF(DAY,DATE_FORMAT(DATE_ADD(pe.datacriacao,INTERVAL pt.prazo DAY),'%Y-%m-%d %H:%i:%s'),current_date())) asc;";
            
            try {
                    $this->search = $this->MySql->prepare($Query);
                    $this->search->bindValue(":myIdSetor", $_SESSION['protocolo']['myIdSetor']);
                    $this->search->execute();
					
            } catch(PDOExeption $e) {
                    die($e->getMessage());
            }
			
            $array=array();
			
            while($show = $this->search->fetch(PDO::FETCH_ASSOC)){
				$array[] = $show;
			}
			return $array;
        }
		
		public function getDataTableListaProtocolosPendentes(){
            
            $id_setor_destino = $this->selectIdSetorPorSigla($_SESSION['protocolo']['mySetor']);
            
			$Query = "
					 SELECT * FROM
					(
						SELECT 
							pd.codprodespacho, 
							p.codprotocolo, 
							p.status, 
							p.numprotocolo, 
							pe.datacriacao, 
							DATE_FORMAT(DATE_ADD(pe.datacriacao,INTERVAL pt.prazo DAY),'%d/%m/%Y') as dt_vencimento,
							TIMESTAMPDIFF(DAY,DATE_FORMAT(DATE_ADD(pe.datacriacao,INTERVAL pt.prazo DAY),'%Y-%m-%d %H:%i:%s'),current_date()) as dias_vencimento,
							cd.cse_setor_descricao, 
							p.nomeremetente, 
							p.assunto, 
							pt.tipo, 
							pt.prazo, 
							p.canatendimento, 
							pm.descricao as motivo
						FROM protocolo.protocolos p 
							inner join protocolo.protocolo_eventos pe on pe.codprotocolo = p.codprotocolo and pe.evento = 'cadastrado'
							left join protocolo.protocolo_despachos pd on pd.codprodespacho = p.coddespachoatual and pd.codsetdestino = :id_setor_destino and pd.status = 'D'
							inner join cadastro.cad_setor cd on cd.cse_id = p.codsetoratual
							inner join protocolo.protocolo_tipos pt on pt.codtipo = p.codtipo
							left join protocolo_motivos pm ON pm.codmotivo = p.codmotivo
						WHERE p.status = 'P' 
								and p.codsetoratual = :id_setor_destino 
								and p.coddespachoatual is null
								and ( p.clientepodeeditar is null or p.clientepodeeditar = 'N' )
						GROUP BY p.codprotocolo, p.status, p.numprotocolo, pe.datacriacao, cd.cse_setor_descricao, p.nomeremetente, p.assunto
								
					UNION ALL
                        
						SELECT 
							pd.codprodespacho, 
							p.codprotocolo, 
							p.status, 
							p.numprotocolo, 
							pe.datacriacao, 
							DATE_FORMAT(DATE_ADD(pe.datacriacao,INTERVAL pt.prazo DAY),'%d/%m/%Y') as dt_vencimento,
							TIMESTAMPDIFF(DAY,DATE_FORMAT(DATE_ADD(pe.datacriacao,INTERVAL pt.prazo DAY),'%Y-%m-%d %H:%i:%s'),current_date()) as dias_vencimento,
							cd.cse_setor_descricao, 
							p.nomeremetente, 
							p.assunto, 
							pt.tipo, 
							pt.prazo, 
							p.canatendimento, 
							pm.descricao as motivo
                        FROM protocolo.protocolos p 
							inner join protocolo.protocolo_eventos pe on pe.codprotocolo = p.codprotocolo and pe.evento = 'cadastrado'
							inner join protocolo.protocolo_despachos pd on pd.codprodespacho = p.coddespachoatual and pd.status != 'D'
							inner join cadastro.cad_setor cd on cd.cse_id = p.codsetoratual
							inner join protocolo.protocolo_tipos pt on pt.codtipo = p.codtipo
							left join protocolo_motivos pm ON pm.codmotivo = p.codmotivo
                        WHERE p.status = 'P' 
							and p.codsetoratual = :id_setor_destino
							and ( p.clientepodeeditar is null or p.clientepodeeditar = 'N' )
						GROUP BY p.codprotocolo, p.status, p.numprotocolo, pe.datacriacao, cd.cse_setor_descricao, p.nomeremetente, p.assunto
					) a order by abs(a.dias_vencimento) asc;";
			
            try {
                    $this->search = $this->MySql->prepare($Query);
                    $this->search->bindValue(":id_setor_destino", $id_setor_destino);
                    $this->search->execute();
					
            } catch(PDOExeption $e) {
                    die($e->getMessage());
            }
			
            while($show = $this->search->fetch(PDO::FETCH_OBJ)){
				$this->printTabelaConteudoHome($show);
			}
        }
		
		public function retornaProtocolosPendentes(){
            
			$Query = "SELECT * FROM 
							(
								SELECT 
									pd.codprodespacho, 
									p.codprotocolo, 
									p.status,
									OBTER_DESCRICAO_STATUS_PROTOCOLO(p.status) as desc_status,
									OBTER_CLASSE_COR_PROTOCOLO(p.status) as cor_classe,
									p.numprotocolo, 
									DATE_FORMAT(pe.datacriacao,'%d/%m/%Y %H:%i:%s') as datacriacao, 
									DATE_FORMAT(DATE_ADD(pe.datacriacao,INTERVAL pt.prazo DAY),'%d/%m/%Y') as dt_vencimento,
									TIMESTAMPDIFF(DAY,DATE_FORMAT(DATE_ADD(pe.datacriacao,INTERVAL pt.prazo DAY),'%Y-%m-%d %H:%i:%s'),current_date()) as dias_vencimento,
									cd.cse_setor_descricao, 
									p.nomeremetente, 
									p.assunto, 
									p.codtipo,
									pt.tipo, 
									pt.prazo, 
									OBTER_DESCRICAO_CANAL_ATENDIMENTO(p.canatendimento) as canatendimento
									
								FROM protocolo.protocolos p 
									inner join protocolo.protocolo_eventos pe on pe.codprotocolo = p.codprotocolo and pe.evento = 'cadastrado'
									left join protocolo.protocolo_despachos pd on pd.codprodespacho = p.coddespachoatual and pd.codsetdestino = :myIdSetor and pd.status = 'D'
									inner join cadastro.cad_setor cd on cd.cse_id = p.codsetoratual
									inner join protocolo.protocolo_tipos pt on pt.codtipo = p.codtipo
								
								WHERE p.status = 'P' 
										and p.codsetoratual = :myIdSetor
										and p.coddespachoatual is null
										-- and ( p.clientepodeeditar is null or p.clientepodeeditar = 'N' )
								GROUP BY p.codprotocolo, p.status, p.numprotocolo, pe.datacriacao, cd.cse_setor_descricao, p.nomeremetente, p.assunto
										
							UNION ALL
								
								SELECT 
									pd.codprodespacho, 
									p.codprotocolo, 
									p.status,
									OBTER_DESCRICAO_STATUS_PROTOCOLO(p.status) as desc_status,
									OBTER_CLASSE_COR_PROTOCOLO(p.status) as cor_classe,
									p.numprotocolo, 
									DATE_FORMAT(pe.datacriacao,'%d/%m/%Y %H:%i:%s') as datacriacao, 
									DATE_FORMAT(DATE_ADD(pe.datacriacao,INTERVAL pt.prazo DAY),'%d/%m/%Y') as dt_vencimento,
									TIMESTAMPDIFF(DAY,DATE_FORMAT(DATE_ADD(pe.datacriacao,INTERVAL pt.prazo DAY),'%Y-%m-%d %H:%i:%s'),current_date()) as dias_vencimento,
									cd.cse_setor_descricao, 
									p.nomeremetente, 
									p.assunto, 
									p.codtipo,
									pt.tipo, 
									pt.prazo, 
									OBTER_DESCRICAO_CANAL_ATENDIMENTO(p.canatendimento) as canatendimento
									
								FROM protocolo.protocolos p 
									inner join protocolo.protocolo_eventos pe on pe.codprotocolo = p.codprotocolo and pe.evento = 'cadastrado'
									inner join protocolo.protocolo_despachos pd on pd.codprodespacho = p.coddespachoatual and pd.status != 'D'
									inner join cadastro.cad_setor cd on cd.cse_id = p.codsetoratual
									inner join protocolo.protocolo_tipos pt on pt.codtipo = p.codtipo
								
								WHERE p.status = 'P' 
									and p.codsetoratual = :myIdSetor
									-- and ( p.clientepodeeditar is null or p.clientepodeeditar = 'N' )
								GROUP BY p.codprotocolo, p.status, p.numprotocolo, pe.datacriacao, cd.cse_setor_descricao, p.nomeremetente, p.assunto
							) a order by a.tipo asc, abs(a.dias_vencimento) asc;";
			
            try {
                    $this->search = $this->MySql->prepare($Query);
                    $this->search->bindValue(":myIdSetor", $_SESSION['protocolo']['myIdSetor']);
                    $this->search->execute();
					
            } catch(PDOExeption $e) {
                    die($e->getMessage());
            }
			
			$array=array();
			
            while($show = $this->search->fetch(PDO::FETCH_ASSOC)){
				$array[] = $show;
			}
			return $array;
        }
		
		public function getDataTableListaProtocolosInativados(){
            
            $codsetoratual = $_SESSION['protocolo']['myIdSetor'];
            			
			$Query = "select 
							pd.codprodespacho, 
							p.codprotocolo, 
							p.status, 
							p.numprotocolo, 
							(SELECT e.datacriacao 
									FROM protocolo.protocolo_eventos e
								WHERE e.codprotocolo = p.codprotocolo and e.evento = 'cadastrado'
									ORDER BY e.datacriacao DESC LIMIT 1) as datacriacao,
							DATE_FORMAT(DATE_ADD((SELECT e.datacriacao 
									FROM protocolo.protocolo_eventos e
								WHERE e.codprotocolo = p.codprotocolo and e.evento = 'cadastrado'
									ORDER BY e.datacriacao DESC LIMIT 1),INTERVAL pt.prazo DAY),'%d/%m/%Y') as dt_vencimento,
							TIMESTAMPDIFF(DAY,DATE_FORMAT(DATE_ADD((SELECT e.datacriacao 
									FROM protocolo.protocolo_eventos e
								WHERE e.codprotocolo = p.codprotocolo and e.evento = 'cadastrado'
									ORDER BY e.datacriacao DESC LIMIT 1),INTERVAL pt.prazo DAY),'%Y-%m-%d %H:%i:%s'),current_date()) as dias_vencimento,
							cd.cse_setor_descricao, 
							p.nomeremetente, 
							p.assunto, 
							pt.tipo, 
							pt.prazo, 
							p.canatendimento, 
							pm.descricao as motivo
						FROM protocolo.protocolos p 
							left join protocolo.protocolo_despachos pd on pd.codprodespacho = p.coddespachoatual and pd.codsetdestino = :codsetoratual and pd.status = 'R'
							inner join cadastro.cad_setor cd on cd.cse_id = p.codsetoratual
							inner join protocolo.protocolo_tipos pt on pt.codtipo = p.codtipo
							left join protocolo_motivos pm ON pm.codmotivo = p.codmotivo
						WHERE 
							p.status = 'I' 
							AND p.codsetoratual = :codsetoratual
							AND ( p.clientepodeeditar is null or p.clientepodeeditar = 'N' )
								ORDER BY (SELECT e.datacriacao 
									FROM protocolo.protocolo_eventos e
								WHERE e.codprotocolo = p.codprotocolo and e.evento = 'cadastrado'
									ORDER BY e.datacriacao DESC LIMIT 1) desc;";
            
            try {
                    $this->search = $this->MySql->prepare($Query);
                    $this->search->bindValue(":codsetoratual", $codsetoratual);
                    $this->search->execute();
					
            } catch(PDOExeption $e) {
                    die($e->getMessage());
            }
			
            while($show = $this->search->fetch(PDO::FETCH_OBJ)){
				$this->printTabelaConteudoHome($show);
			}
        }
		public function retornaProtocolosInativados(){
            		
			$Query = "SELECT 
							pd.codprodespacho, 
							p.codprotocolo, 
							p.status, 
							OBTER_DESCRICAO_STATUS_PROTOCOLO(p.status) as desc_status,
							OBTER_CLASSE_COR_PROTOCOLO(p.status) as cor_classe,
							p.numprotocolo, 
							(SELECT DATE_FORMAT(e.datacriacao,'%d/%m/%Y %H:%i:%s') 
									FROM protocolo.protocolo_eventos e
								WHERE e.codprotocolo = p.codprotocolo and e.evento = 'cadastrado'
									ORDER BY e.datacriacao DESC LIMIT 1) as datacriacao,
							DATE_FORMAT(DATE_ADD((SELECT e.datacriacao 
									FROM protocolo.protocolo_eventos e
								WHERE e.codprotocolo = p.codprotocolo and e.evento = 'cadastrado'
									ORDER BY e.datacriacao DESC LIMIT 1),INTERVAL pt.prazo DAY),'%d/%m/%Y') as dt_vencimento,
							TIMESTAMPDIFF(DAY,DATE_FORMAT(DATE_ADD((SELECT e.datacriacao 
									FROM protocolo.protocolo_eventos e
								WHERE e.codprotocolo = p.codprotocolo and e.evento = 'cadastrado'
									ORDER BY e.datacriacao DESC LIMIT 1),INTERVAL pt.prazo DAY),'%Y-%m-%d %H:%i:%s'),current_date()) as dias_vencimento,
							cd.cse_setor_descricao, 
							p.nomeremetente, 
							p.assunto, 
							p.codtipo,
							pt.tipo, 
							pt.prazo, 
							OBTER_DESCRICAO_CANAL_ATENDIMENTO(p.canatendimento) as canatendimento
							
						FROM protocolo.protocolos p 
							left join protocolo.protocolo_despachos pd on pd.codprodespacho = p.coddespachoatual and pd.codsetdestino = :myIdSetor and pd.status = 'R'
							inner join cadastro.cad_setor cd on cd.cse_id = p.codsetoratual
							inner join protocolo.protocolo_tipos pt on pt.codtipo = p.codtipo

						WHERE 
							p.status = 'I' AND p.codsetoratual = :myIdSetor
								ORDER BY (SELECT e.datacriacao FROM protocolo.protocolo_eventos e
													WHERE e.codprotocolo = p.codprotocolo and e.evento = 'cadastrado'
														ORDER BY e.datacriacao DESC LIMIT 1) desc;";
            
            try {
				$this->search = $this->MySql->prepare($Query);
				$this->search->bindValue(":myIdSetor", $_SESSION['protocolo']['myIdSetor']);
				$this->search->execute();
					
            } catch(PDOExeption $e) {
				die($e->getMessage());
            }
			
            $array=array();
			
            while($show = $this->search->fetch(PDO::FETCH_ASSOC)){
				$array[] = $show;
			}
			return $array;
        }
		public function printTabelaConteudoHome($show){
			
			if( $show->status == 'P' ){
				$dias_vencimento = ($show->dias_vencimento > 0 ? "Vencido há " : "Vence em ") . abs($show->dias_vencimento) . " Dia(s)";
			}else{
				$dias_vencimento = "--/--";
			}

			print "<tr id='row'>
						<td>
							<input type='hidden' name='codprotocolo' value='".$show->codprotocolo."'/>
							<input type='hidden' name='codprodespacho' value='".$show->codprodespacho."'/>
							<img src='view/jvs/treeview/img/check.png' />
						</td>
						<td><span class='label label-". $this->defineCorStatusProtocolo($show->status) ."'>".$this->defineNomeStatusProtocolo($show->status)."</span></td>
						<td>".$this->getDataHora($show->datacriacao)->format('d/m/Y H:i:s')."</td>
						<td>".$show->prazo." Dias</td>
						<td>".$show->dt_vencimento."</td>
						<td><span class='label label-". $this->defineCorVencimentoProtocolo($show->dias_vencimento,$show->status) ."'>".$dias_vencimento."</span></td>
						<td>".$show->numprotocolo."</td>
						<td>".$show->cse_setor_descricao."</td>
						<td style='text-align: left;'>".$show->nomeremetente."</td>
						<td style='text-align: left;'>".$this->ucwords_improved($show->assunto)."</td>
						<td style='text-align: left;'>".$show->tipo."</td>
						<td style='text-align: left;'>".$this->RetornaCanalAtendimento($show->canatendimento)."</td>
					</tr>";
		}
        public function defineNomeStatusProtocolo($status){
            switch ($status) {
                case 'P':
                    return 'Pendente';
                break;
                case 'F':
                    return 'Finalizado';
                break;
                case 'I':
                    return 'Inativo';
                break;
                default:
                    break;
            }
        }
		public function defineCorStatusProtocolo($status){
            switch ($status) {
                case 'F':
                    return 'success';
                break;
                case 'P':
                    return 'warning';
                break;
                case 'I':
                    return 'danger';
                break;
                default:
                    break;
            }
        }
		public function defineCorVencimentoProtocolo($dias,$status){
            
			if( $dias > 0 ){
				return 'default';
			}else if( $dias <= 0 && $dias >= -5 ){
				return 'danger';
			}else{
				return 'warning';
			}
        }
        public function getDataTableListaProtocolosCadastrados(){
            
            $myIdSetor = $_SESSION['protocolo']['myIdSetor'];
            
            $Query = "SELECT 
						'' as codprodespacho, 
						p.codprotocolo, 
						p.status, 
						p.numprotocolo, 
						cs.cse_setor_descricao, 
						p.nomeremetente, 
						p.assunto, 
						pec.datacriacao, 
						DATE_FORMAT(DATE_ADD(pec.datacriacao,INTERVAL pt.prazo DAY),'%d/%m/%Y') as dt_vencimento,
						TIMESTAMPDIFF(DAY,DATE_FORMAT(DATE_ADD(pec.datacriacao,INTERVAL pt.prazo DAY),'%Y-%m-%d %H:%i:%s'),current_date()) as dias_vencimento,
						pt.tipo, 
						pt.prazo, 
						p.canatendimento, 
						pm.descricao as motivo
					FROM protocolo.protocolos p 
                            inner join cadastro.cad_setor cs on cs.cse_id = p.codsetoratual
							inner join protocolo.protocolo_eventos pec on pec.codprotocolo = p.codprotocolo and pec.evento = 'cadastrado'
                            inner join seguranca.seg_usuario sus on sus.sus_id = pec.codusucadastro
							inner join protocolo.protocolo_tipos pt on pt.codtipo = p.codtipo
							left join protocolo_motivos pm ON pm.codmotivo = p.codmotivo
					WHERE pec.codsetorusucadastro = :myIdSetor
							and p.canatendimento != 'W'
					GROUP BY p.codprotocolo, p.status, p.numprotocolo, cs.cse_setor_descricao, p.nomeremetente, p.assunto
							ORDER BY pec.datacriacao desc
								LIMIT 200;";
            
            try {
				$this->search = $this->MySql->prepare($Query);
				//$this->search->bindValue(":evento", $evento);
				$this->search->bindValue(":myIdSetor", $myIdSetor);
				$this->search->execute();
				
            } catch(PDOExeption $e) {
                    die($e->getMessage());
            }
			
            while($show = $this->search->fetch(PDO::FETCH_OBJ)){
				
				$this->printTabelaConteudoHome($show);
			}
        }
		public function retornaProtocolosCadastrados(){
              
            $Query = "SELECT 
							'' as codprodespacho, 
							p.codprotocolo, 
							p.status, 
							OBTER_DESCRICAO_STATUS_PROTOCOLO(p.status) as desc_status,
							OBTER_CLASSE_COR_PROTOCOLO(p.status) as cor_classe,
							p.numprotocolo, 
							DATE_FORMAT(pe.datacriacao,'%d/%m/%Y %H:%i:%s') as datacriacao, 
							DATE_FORMAT(DATE_ADD(pe.datacriacao,INTERVAL pt.prazo DAY),'%d/%m/%Y') as dt_vencimento,
							TIMESTAMPDIFF(DAY,DATE_FORMAT(DATE_ADD(pe.datacriacao,INTERVAL pt.prazo DAY),'%Y-%m-%d %H:%i:%s'),current_date()) as dias_vencimento,
							cs.cse_setor_descricao, 
							p.nomeremetente, 
							p.assunto, 
							p.codtipo,
							pt.tipo, 
							pt.prazo, 
							OBTER_DESCRICAO_CANAL_ATENDIMENTO(p.canatendimento) as canatendimento
							
						FROM protocolo.protocolos p 
								inner join cadastro.cad_setor cs on cs.cse_id = p.codsetoratual
								inner join protocolo.protocolo_eventos pe on pe.codprotocolo = p.codprotocolo and pe.evento = 'cadastrado'
								inner join seguranca.seg_usuario sus on sus.sus_id = pe.codusucadastro
								inner join protocolo.protocolo_tipos pt on pt.codtipo = p.codtipo
								
						WHERE pe.setorusucadastro = :mySetor
								and p.canatendimento != 'W'
						GROUP BY p.codprotocolo, 
								 p.status, 
								 p.numprotocolo, 
								 cs.cse_setor_descricao, 
								 p.nomeremetente, 
								 p.assunto
						ORDER BY pt.tipo asc, pe.datacriacao desc
							LIMIT 200;";
            
            try {
				$this->search = $this->MySql->prepare($Query);
				$this->search->bindValue(":mySetor", $_SESSION['protocolo']['mySetor']);
				$this->search->execute();
				
            } catch(PDOExeption $e) {
                    die($e->getMessage());
            }
			
            $array=array();
			
            while($show = $this->search->fetch(PDO::FETCH_ASSOC)){
				$array[] = $show;
			}
			return $array;
        }
		public function getDataTableListaProtocolosFinalizados(){
            
            $codsetoratual = $_SESSION['protocolo']['myIdSetor'];
            
            $Query = "SELECT 
						'' as codprodespacho, 
						p.codprotocolo, 
						p.status, 
						p.numprotocolo, 
						cs.cse_setor_descricao, 
						p.nomeremetente, 
						p.assunto, 
						pec.datacriacao, 
						DATE_FORMAT(DATE_ADD(pec.datacriacao,INTERVAL pt.prazo DAY),'%d/%m/%Y') as dt_vencimento,
						TIMESTAMPDIFF(DAY,DATE_FORMAT(DATE_ADD(pec.datacriacao,INTERVAL pt.prazo DAY),'%Y-%m-%d %H:%i:%s'),current_date()) as dias_vencimento,
						pt.tipo, 
						pt.prazo, 
						p.canatendimento, 
						pm.descricao as motivo
					FROM protocolo.protocolos p 
                            inner join cadastro.cad_setor cs on cs.cse_id = p.codsetoratual
							inner join protocolo.protocolo_eventos pec on pec.codprotocolo = p.codprotocolo and pec.evento = 'cadastrado'
                            inner join protocolo.protocolo_eventos pe on pe.codprotocolo = p.codprotocolo and pe.evento = 'finalizado'
                            inner join seguranca.seg_usuario sus on sus.sus_id = pe.codusucadastro
							inner join protocolo.protocolo_tipos pt on pt.codtipo = p.codtipo
							left join protocolo_motivos pm ON pm.codmotivo = p.codmotivo
								WHERE p.codsetoratual = :codsetoratual AND p.status = 'F'
									GROUP BY p.codprotocolo, p.status, p.numprotocolo, cs.cse_setor_descricao, p.nomeremetente, p.assunto
										ORDER BY pe.datacriacao desc
											LIMIT 200;";
            
            try {
				$this->search = $this->MySql->prepare($Query);
				//$this->search->bindValue(":evento", $evento);
				$this->search->bindValue(":codsetoratual", $codsetoratual);
				$this->search->execute();
				
            } catch(PDOExeption $e) {
                    die($e->getMessage());
            }
			
            while($show = $this->search->fetch(PDO::FETCH_OBJ)){
				
				$this->printTabelaConteudoHome($show);
			}
        }
		public function retornaProtocolosFinalizados(){
            
            $Query = "SELECT 
						'' as codprodespacho, 
						p.codprotocolo, 
						p.status, 
						OBTER_DESCRICAO_STATUS_PROTOCOLO(p.status) as desc_status,
						OBTER_CLASSE_COR_PROTOCOLO(p.status) as cor_classe,
						p.numprotocolo, 
						DATE_FORMAT(pe.datacriacao,'%d/%m/%Y %H:%i:%s') as datacriacao, 
						DATE_FORMAT(DATE_ADD(pe.datacriacao,INTERVAL pt.prazo DAY),'%d/%m/%Y') as dt_vencimento,
						TIMESTAMPDIFF(DAY,DATE_FORMAT(DATE_ADD(pe.datacriacao,INTERVAL pt.prazo DAY),'%Y-%m-%d %H:%i:%s'),current_date()) as dias_vencimento,
						cs.cse_setor_descricao, 
						p.nomeremetente, 
						p.assunto, 
						p.codtipo,
						pt.tipo, 
						pt.prazo, 
						OBTER_DESCRICAO_CANAL_ATENDIMENTO(p.canatendimento) as canatendimento
						
					FROM protocolo.protocolos p 
                            inner join cadastro.cad_setor cs on cs.cse_id = p.codsetoratual
							inner join protocolo.protocolo_eventos pec on pec.codprotocolo = p.codprotocolo and pec.evento = 'cadastrado'
                            inner join protocolo.protocolo_eventos pe on pe.codprotocolo = p.codprotocolo and pe.evento = 'finalizado'
                            inner join seguranca.seg_usuario sus on sus.sus_id = pe.codusucadastro
							inner join protocolo.protocolo_tipos pt on pt.codtipo = p.codtipo
							
					WHERE p.codsetoratual = :myIdSetor AND p.status = 'F'
						GROUP BY p.codprotocolo, p.status, p.numprotocolo, cs.cse_setor_descricao, p.nomeremetente, p.assunto
							ORDER BY pt.tipo asc, pe.datacriacao desc
								LIMIT 200;";
            
            try {
				$this->search = $this->MySql->prepare($Query);
				$this->search->bindValue(":myIdSetor", $_SESSION['protocolo']['myIdSetor']);
				$this->search->execute();
				
            } catch(PDOExeption $e) {
                    die($e->getMessage());
            }
			
            $array=array();
			
            while($show = $this->search->fetch(PDO::FETCH_ASSOC)){
				$array[] = $show;
			}
			return $array;
        }
        public function verificaExibirBotao($id_protocolo){
            
            $id_setor_destino = $this->selectIdSetorPorSigla($_SESSION['protocolo']['mySetor']);
            
            $Query = "select p.status as status_protocolo, pd.status as status_despacho, pd.codsetdestino, p.codsetoratual
						from protocolo.protocolos p
								left join protocolo.protocolo_despachos pd on p.coddespachoatual = pd.codprodespacho
									where p.codprotocolo = :id_protocolo;";
            
            try {
                    $this->search = $this->MySql->prepare($Query);
                    $this->search->bindValue(":id_protocolo", $id_protocolo);
                    $this->search->execute();
            } catch(PDOExeption $e) {
                    die($e->getMessage());
            }
			
            if($show = $this->search->fetch(PDO::FETCH_OBJ)){
				
                if( 
					// se status = P, despacho = R, destino = meu setor
					( $show->status_protocolo == "P" && $show->status_despacho == "R" && $show->codsetdestino == $id_setor_destino ) || 
					// se status = P, despacho = NULL, destino = NULL, setor atual = meu setor
					( $show->status_protocolo == "P" && $show->status_despacho == NULL && $show->codsetdestino == NULL && $show->codsetoratual == $id_setor_destino ) ||
					// se for o ADM
					( $_SESSION['protocolo']['myId'] == '413' ) 
				){
					return 1;
				}else if ( 
						// se status = I, despacho = R, destino = meu setor
						( $show->status_protocolo == "I" && $show->status_despacho == "R" && $show->codsetdestino == $id_setor_destino ) ||
						// se status = I, despacho = NULL, destino = NULL, setor atual = meu setor
						( $show->status_protocolo == "I" && $show->status_despacho == NULL && $show->codsetdestino == NULL && $show->codsetoratual == $id_setor_destino ) 
				){
					return 2;
				} else if(
						// se status = F, despacho = R, destino = meu setor
						( $show->status_protocolo == "F" && $show->status_despacho == "R" && $show->codsetdestino == $id_setor_destino ) ||
						// se status = F, despacho = NULL, destino = NULL, setor atual = meu setor
						( $show->status_protocolo == "F" && $show->status_despacho == NULL && $show->codsetdestino == NULL && $show->codsetoratual == $id_setor_destino ) 
				){
					return 3;
				}else{
					return 0;
				}
            }else {
				return false;
            }
        }
        public function verificaEventoExisteProtocolo($codprotocolo, $evento) {
			
            $Query = "select * from protocolo.protocolo_eventos pe where pe.codprotocolo = :codprotocolo and pe.evento = :evento;";
            
            try {
                    $this->search = $this->MySql->prepare($Query);
                    $this->search->bindValue(":codprotocolo", $codprotocolo);
                    $this->search->bindValue(":evento", $evento);
                    $this->search->execute();
            } catch(PDOExeption $e) {
                    die($e->getMessage());
            }
			
            if($show = $this->search->fetch(PDO::FETCH_OBJ)){
				return true;
            }else{
				return false;
            }
        }
		
		public function selectAssObsAnterior($id_protocolo) {
			
			$query = "SELECT * FROM protocolo.protocolos p WHERE p.codprotocolo = :codprotocolo;";
					
			try {
				$this->search = $this->MySql->prepare($query);
				$this->search->bindValue(":codprotocolo", $id_protocolo);
				$this->search->execute();
			}catch(PDOException $e) {
				die($e->getMessage());
			}
			
			if($show = $this->search->fetch())
				return $show;
			else
				return "Error";
		}
//======================== CHAT ============================================
		public function retornaChat($id_protocolo) {
			
			$query = "SELECT p.* ,
							date_format(p.datcadastro,'%d/%m/%Y %H:%i') as datcadastro,
							u.sus_nome,
							u.sus_setor 
						FROM protocolo.protocolo_chat p 
							LEFT JOIN seguranca.seg_usuario u ON u.sus_id = p.codusuario 
								WHERE p.codprotocolo = :codprotocolo;";
					
			try {
				$this->search = $this->MySql->prepare($query);
				$this->search->bindValue(":codprotocolo", $id_protocolo);
				$this->search->execute();
			}catch(PDOException $e) {
				die($e->getMessage());
			}
			
			$array = array();
			
			while($show = $this->search->fetch(PDO::FETCH_ASSOC)) {
				$array[] = $show;
			}
			
			return $array;
		}
		public function insereChat($codprotocolo,$texto,$codusuario){
			
			try{
				$this->insert = $this->MySql->prepare("INSERT INTO protocolo.protocolo_chat (codprotocolo,texto,datcadastro,codusuario,ip) VALUES (:codprotocolo,:texto,:datcadastro,:codusuario,:ip);");
				
				$this->insert->bindValue(":codprotocolo", $codprotocolo, PDO::PARAM_STR);	
				$this->insert->bindValue(":texto", $texto, PDO::PARAM_STR);	
				$this->insert->bindValue(":datcadastro", date('Y-m-d H:i:s'), PDO::PARAM_STR);	
				$this->insert->bindValue(":codusuario", $codusuario, PDO::PARAM_STR);	
				$this->insert->bindValue(":ip", $_SERVER['REMOTE_ADDR'], PDO::PARAM_STR);	
				
				if( $this->insert->execute() ){
					return true;
				}else{
					return false;
				}
			}catch(PDOExeption $e){
				die($e->getMessage());
			}
		}
		public function retornaQtdeMsgChat($id_protocolo) {
			
			$query = "SELECT count(*) as qtde
						FROM protocolo.protocolo_chat p 
							LEFT JOIN seguranca.seg_usuario u ON u.sus_id = p.codusuario 
								WHERE p.codprotocolo = :codprotocolo;";
					
			try {
				$this->search = $this->MySql->prepare($query);
				$this->search->bindValue(":codprotocolo", $id_protocolo);
				$this->search->execute();
			}catch(PDOException $e) {
				die($e->getMessage());
			}
			
			if($show = $this->search->fetch(PDO::FETCH_ASSOC)) {
				return $show['qtde'];
			}else{
				return 0;
			}
		}
		public function retornaQtdeArquivos($id_protocolo) {
			
			$query = "SELECT count(*) as qtde
						FROM protocolo.protocolo_anexos p 
							WHERE p.codprotocolo = :codprotocolo AND p.status = 'A';";
					
			try {
				$this->search = $this->MySql->prepare($query);
				$this->search->bindValue(":codprotocolo", $id_protocolo);
				$this->search->execute();
			}catch(PDOException $e) {
				die($e->getMessage());
			}
			
			if($show = $this->search->fetch(PDO::FETCH_ASSOC)) {
				return $show['qtde'];
			}else{
				return 0;
			}
		}
		public function retornaQtdeDocsInternos($id_protocolo) {
			
			$query = "SELECT count(*) as qtde
						FROM protocolo.protocolo_documentos d 
							LEFT JOIN seguranca.seg_usuario u ON u.sus_id = d.codusucadastro
								WHERE d.codprotocolo = :codprotocolo and d.status = 'A';";
					
			try {
				$this->search = $this->MySql->prepare($query);
				$this->search->bindValue(":codprotocolo", $id_protocolo);
				$this->search->execute();
			}catch(PDOException $e) {
				die($e->getMessage());
			}
			
			if($show = $this->search->fetch(PDO::FETCH_ASSOC)) {
				return $show['qtde'];
			}else{
				return 0;
			}
		}
		
//================================================================ UPDATES ====================================================================
        public function updateRecebeProtocolo($id_protocolo, $id_despacho){
            
            $id_setor = $this->selectIdSetorPorSigla($_SESSION['protocolo']['mySetor']);
            
			if( $this->atualizaCodSetorAtual($id_protocolo, $id_setor) ){
				
					$query = "UPDATE protocolo.protocolo_despachos "
							. "SET codusurecebeu = (:v1), "
							. "ipusurecebeu = (:v2), "
							. "hostusurecebeu = (:v3), "
							. "status = (:v4), "
							. "datarecebeu = (:v5) "
							. "where codprodespacho = (:v6);";
				   
					try{
						$this->insert = $this->MySql->prepare($query);
							$this->insert->bindValue(":v1", $_SESSION['protocolo']['myId'], PDO::PARAM_STR);	
							$this->insert->bindValue(":v2", $_SESSION['protocolo']['myIp'], PDO::PARAM_STR);	
							$this->insert->bindValue(":v3", $_SESSION['protocolo']['myHostName'], PDO::PARAM_STR);	
							$this->insert->bindValue(":v4", "R", PDO::PARAM_STR);
							$this->insert->bindValue(":v5", date("Y-m-d H:i:s"), PDO::PARAM_STR);
							$this->insert->bindValue(":v6", $id_despacho, PDO::PARAM_STR);
								if($this->insert->execute()){
									print TRUE;
								}else{
									print FALSE;
								}
					}catch(PDOExeption $e){
						die($e->getMessage());
					}
			}else{
				print FALSE;
				exit;
			}
        }
		public function updateReabreProtocolo($codprotocolo, $observacao){
            
            $query = "UPDATE protocolo.protocolos SET status = (:v1) where codprotocolo = (:v2);";
			
            try{
                    $this->insert = $this->MySql->prepare($query);
                    $this->insert->bindValue(":v1", "P", PDO::PARAM_STR);	
                    $this->insert->bindValue(":v2", $codprotocolo, PDO::PARAM_STR);
                    
                    if($this->insert->execute()){
                        //if(!$this->verificaEventoExisteProtocolo($codprotocolo, "ativado")){
                            $this->insertProEventos($codprotocolo, "reaberto", $observacao, NULL);
                        //}
                    }
            }catch(PDOExeption $e){
                    die($e->getMessage());
            }    
        }
        public function updateAtivaProtocolo($codprotocolo, $observacao){
            
            $query = "UPDATE protocolo.protocolos SET status = (:v1) where codprotocolo = (:v2);";
			
            try{
                    $this->insert = $this->MySql->prepare($query);
                    $this->insert->bindValue(":v1", "P", PDO::PARAM_STR);	
                    $this->insert->bindValue(":v2", $codprotocolo, PDO::PARAM_STR);
                    
                    if($this->insert->execute()){
                        //if(!$this->verificaEventoExisteProtocolo($codprotocolo, "ativado")){
                            $this->insertProEventos($codprotocolo, "ativado", $observacao, NULL);
                        //}
                    }
            }catch(PDOExeption $e){
                    die($e->getMessage());
            }    
        }
		
		public function updateCadastroProtocolo($dados) {
             
			//echo "<pre>"; print_r($dados); exit;
			
			$codprotocolo 		= $dados["codprotocolo"];
			$assunto 			= $dados["assunto"];
			$telefone 			= $dados["telefone"];
			$clientepodeeditar 	= ( !empty($dados["clientepodeeditar"]) ? $dados["clientepodeeditar"] : NULL );
			$email 				= $dados["email"];
			$numprotocologpu 	= $dados["numprotocologpu"];
			$observacao 		= $dados["observacaoprotocolo"];
			
            $query = "UPDATE protocolo.protocolos SET 
								assunto 		= :assunto, 
								observacao 		= :observacao, 
								numprotocologpu = :numprotocologpu,
								email 			= :email,
								telefone		= :telefone,
								clientepodeeditar = :clientepodeeditar
									WHERE codprotocolo = :codprotocolo;";
			
            try{
                    $this->insert = $this->MySql->prepare($query);
                    $this->insert->bindValue(":assunto", $assunto);	
                    $this->insert->bindValue(":observacao", $observacao);	
                    $this->insert->bindValue(":numprotocologpu", $numprotocologpu);	
                    $this->insert->bindValue(":email", $email);	
                    $this->insert->bindValue(":telefone", $telefone);	
                    $this->insert->bindValue(":codprotocolo", $codprotocolo);	
                    $this->insert->bindValue(":clientepodeeditar", $clientepodeeditar);	
                    
					$dados_anteriores = $this->selectAssObsAnterior($codprotocolo); //dados anteriores antes de salvar para comparação
					
					//echo "<pre>"; print_r(strlen($dados_anteriores['observacao']) . "  - " . strlen($observacao)); exit;
					
					if($this->insert->execute()){
						
						$observacao = "";
						$observacao .= " Assunto Anterior: " . ( $dados_anteriores['assunto'] != $assunto ? $dados_anteriores['assunto'] : "Sem alteração!" );
						$observacao .= "\nObservação Anterior: " . ( $dados_anteriores['observacao'] != $observacao ? $dados_anteriores['observacao'] : "Sem alteração!" );
						$observacao .= "\nProtocolo GPU Anterior: " . ( $dados_anteriores['numprotocologpu'] != $numprotocologpu ? $dados_anteriores['numprotocologpu'] : "Sem alteração!" );
						$observacao .= "\nEmail Anterior: " . ( $dados_anteriores['email'] != $email ? $dados_anteriores['email'] : "Sem alteração!" );
						$observacao .= "\nTelefone Anterior: " . ( $dados_anteriores['telefone'] != $telefone ? $dados_anteriores['telefone'] : "Sem alteração!" );
						$observacao .= "\nCliente pode editar Anterior: " . ( $dados_anteriores['clientepodeeditar'] != $clientepodeeditar ? $dados_anteriores['clientepodeeditar'] : "Sem alteração!" );
						 
						/*
						$observacao = " Assunto Anterior: " 		. ( $dados_anteriores['assunto'] != $assunto ? $dados_anteriores['assunto'] : "Sem alteração!" ) . " 
										Observação Anterior: " 		. ( $dados_anteriores['observacao'] != $observacao ? $dados_anteriores['observacao'] : "Sem alteração!" ) . "
										Protocolo GPU Anterior: " 	. ( $dados_anteriores['numprotocologpu'] != $numprotocologpu ? $dados_anteriores['numprotocologpu'] : "Sem alteração!" ) . "
										Email Anterior: " 			. ( $dados_anteriores['email'] != $email ? $dados_anteriores['email'] : "Sem alteração!" ) . "
										Telefone Anterior: " 		. ( $dados_anteriores['telefone'] != $telefone ? $dados_anteriores['telefone'] : "Sem alteração!" ) . "
										Cliente pode editar Anterior: " . ( $dados_anteriores['clientepodeeditar'] != $clientepodeeditar ? $dados_anteriores['clientepodeeditar'] : "Sem alteração!" );
						*/
						
						$this->insertProEventos($codprotocolo, "editado", nl2br($observacao), NULL);
						
						$array['status'] = true;
						
					}else {
						$array['status'] = false;
						$array['msg'] = "Erro ao atualizar protocolo, entre em contato com a T.I";
					}
					
					print json_encode($array);
					
            }catch(PDOExeption $e){
                    die($e->getMessage());
            }
        }
		
		public function inativaProDocumento($id_protocolo, $id_documento) {
			$query = "UPDATE protocolo.protocolo_documentos SET status = 'I' WHERE codprotocolo = (:v1) AND codprodocumento = (:v2);";
			try{
				$this->insert = $this->MySql->prepare($query);
				$this->insert->bindValue(":v1", $id_protocolo);
				$this->insert->bindValue(":v2", $id_documento);
				if($this->insert->execute()){
					print json_encode(true);
				}else{
					print json_encode(false);
				}
			}catch(PDOException $e){
				die($e->getMessage());
			}
		}
		
        public function updateInativaProtocolo($codprotocolo, $observacao){
            
            $query = "UPDATE protocolo.protocolos SET status = (:v1) where codprotocolo = (:v2);";
            try{
                    $this->insert = $this->MySql->prepare($query);
                    $this->insert->bindValue(":v1", "I", PDO::PARAM_STR);	
                    $this->insert->bindValue(":v2", $codprotocolo, PDO::PARAM_STR);	
                    
                    if($this->insert->execute()){
                        //if(!$this->verificaEventoExisteProtocolo($codprotocolo, "inativado")){
                            $this->insertProEventos($codprotocolo, "inativado", $observacao, NULL);
                        //}
                    }
            }catch(PDOExeption $e){
                    die($e->getMessage());
            }    
        }
        public function atualizaCodDespachoAtualProtocolo($id_protocolo ,$cod_despacho_atual){
            
            $query = "UPDATE protocolo.protocolos SET coddespachoatual = (:v1) where codprotocolo = (:v2);";
            try{
                    $this->insert = $this->MySql->prepare($query);
                    $this->insert->bindValue(":v1", $cod_despacho_atual, PDO::PARAM_STR);	
                    $this->insert->bindValue(":v2", $id_protocolo, PDO::PARAM_STR);	
                    $this->insert->execute();
            }catch(PDOExeption $e){
                    die($e->getMessage());
            }    
        }
		public function atualizaCodSetorAtual($codprotocolo, $codsetoratual){
			
			$query = "UPDATE protocolo.protocolos SET codsetoratual = (:v1) where codprotocolo = (:v2);";
			
            try{
                    $this->insert = $this->MySql->prepare($query);
                    $this->insert->bindValue(":v1", $codsetoratual, PDO::PARAM_STR);	
                    $this->insert->bindValue(":v2", $codprotocolo, PDO::PARAM_STR);	
                    
                    if($this->insert->execute()){
						return true;
					}else{
						return false;
					}
            }catch(PDOExeption $e){
                    die($e->getMessage());
            }   
		}
        public function updateConcluiProtocolo($codprotocolo, $observacao){
            
            $query = "UPDATE protocolo.protocolos SET status = (:v1) where codprotocolo = (:v2);";
			
            try{
                    $this->insert = $this->MySql->prepare($query);
                    $this->insert->bindValue(":v1", "F", PDO::PARAM_STR);	
                    $this->insert->bindValue(":v2", $codprotocolo, PDO::PARAM_STR);	
                    
                    if($this->insert->execute()){
                        //if(!$this->verificaEventoExisteProtocolo($codprotocolo, "concluido")){
                            $this->insertProEventos($codprotocolo, "finalizado", $observacao, NULL);
                        //}
                    }
            }catch(PDOExeption $e){
                    die($e->getMessage());
            }    
        }
		public function VerificaNumeroProtocolo($numeroProtocolo){
			
			$_query = "SELECT * FROM protocolo.protocolos p WHERE p.numprotocolo = :numprotocolo;";
			
			$p_sql = $this->MySql->prepare($_query);
			$p_sql->bindValue(":numprotocolo", $numeroProtocolo);
			
			if($p_sql->execute()) {
				
				if($show = $p_sql->fetch(PDO::FETCH_ASSOC)) {
					return true;
				}else {
					return false;
				}
			}else{
				die('Erro ao verificar numero de protocolo, entre em contato com o TI.');
			}
		}
		public function GeraSenhaAtendimento(){
			
			$_query = "SELECT a.senha FROM protocolo.atendimento a 
							WHERE curdate() = date_format(a.datcadastro,'%Y-%m-%d') 
									and a.unidade is null
									and a.codsetorusuinicio = :codsetorusuinicio
										order by cast(a.senha as unsigned integer) desc limit 1;";
			
			$p_sql = $this->MySql->prepare($_query);
			$p_sql->bindValue(":codsetorusuinicio", $_SESSION['protocolo']['myIdSetor']);
			
			if($p_sql->execute()) {
				
				if($show = $p_sql->fetch(PDO::FETCH_ASSOC)) {
					return $show['senha'] + 1;
				}else {
					return 1;
				}
			}else {
				die('Erro ao verificar numero de senha, entre em contato com o TI.');
			}
		}
		public function GeraSenhaAtendimentoAgendamento($data){
			
			$data = new DateTime($data);
			
			$_query = "SELECT a.senha FROM protocolo.atendimento a 
							WHERE date(a.datagendamento) = :data
									and a.unidade = 'CS'
									and a.tipo = 'A'
										order by cast(a.senha as unsigned integer) desc limit 1;";
			
			$p_sql = $this->MySql->prepare($_query);
			
			$p_sql->bindValue(":data",$data->format('Y-m-d'));
			
			if($p_sql->execute()) {
				
				if($show = $p_sql->fetch(PDO::FETCH_ASSOC)) {
					return $show['senha'] + 1;
				}else {
					return 1;
				}
			}else {
				die('Erro ao verificar numero de senha, entre em contato com o TI.');
			}
		}
		
		public function insereAtendimento($dados){
			
			//echo "<pre>"; print_r($dados); exit;
			    
			$Query = "INSERT INTO protocolo.atendimento (
															status,
															senha, 
															canatendimento, 
															datcadastro,
															datahorainicio, 
															datchamado,
															tipo,
															codusuinicio, 
															ipusuinicio, 
															hostusuinicio,
															setorusuinicio,
															codsetorusuinicio
														) VALUES (
															:status,
															:senha, 
															:canatendimento, 
															:datcadastro,
															:datahorainicio,
															:datchamado,
															:tipo,
															:codusuinicio, 
															:ipusuinicio, 
															:hostusuinicio,
															:setorusuinicio,
															:codsetorusuinicio
														);";
                
			try{
				$this->insert = $this->MySql->prepare($Query);
				$this->insert->bindValue(":status", 'A', PDO::PARAM_STR);
				$this->insert->bindValue(":senha", strtoupper($dados['senha']), PDO::PARAM_STR);	
				$this->insert->bindValue(":canatendimento", $dados['canatendimento'], PDO::PARAM_STR);	
				$this->insert->bindValue(":datcadastro", date("Y-m-d H:i:s"), PDO::PARAM_STR);	
				$this->insert->bindValue(":datahorainicio", date("Y-m-d H:i:s"), PDO::PARAM_STR);	
				$this->insert->bindValue(":datchamado", date("Y-m-d H:i:s"), PDO::PARAM_STR);	
				$this->insert->bindValue(":tipo", 'N', PDO::PARAM_STR);
				$this->insert->bindValue(":codusuinicio", $dados['codusuinicio'], PDO::PARAM_STR);	
				$this->insert->bindValue(":ipusuinicio", $dados['ipusuinicio'], PDO::PARAM_STR);	
				$this->insert->bindValue(":hostusuinicio", $dados['hostusuinicio'], PDO::PARAM_STR);	
				$this->insert->bindValue(":setorusuinicio", $dados['setorusuinicio'], PDO::PARAM_STR);	
				$this->insert->bindValue(":codsetorusuinicio", $dados['codsetorusuinicio'], PDO::PARAM_STR);	
				
				if( $this->insert->execute() ){
					return $this->MySql->lastInsertId();
				}else{
					return '';
				}
			}catch(PDOExeption $e){
				die($e->getMessage());
			}
		}
		public function retornaDadosAtendimento($codatendimento){
			
			$_query = "SELECT a.*, u_inicio.sus_nome as usu_inicio, u_fim.sus_nome as usu_fim
							FROM protocolo.atendimento a 
								INNER JOIN seguranca.seg_usuario u_inicio ON u_inicio.sus_id = a.codusuinicio
								LEFT  JOIN seguranca.seg_usuario u_fim ON u_fim.sus_id = a.codusufim
							WHERE a.codatendimento = :codatendimento;";
			
			$p_sql = $this->MySql->prepare($_query);
			
			$p_sql->bindValue(":codatendimento", $codatendimento);
			
			if($p_sql->execute()) {
				
				if($show = $p_sql->fetch(PDO::FETCH_ASSOC)) {
					return $show;
				}else {
					return '';
				}
			}else{
				die('Erro ao retornar dados do atendimento, entre em contato com o TI.');
			}
		}
		public function ultimoAtendimentoAberto(){
			
			$_query = "SELECT a.*, u_inicio.sus_nome as usu_inicio, u_fim.sus_nome as usu_fim
							FROM protocolo.atendimento a 
								INNER JOIN seguranca.seg_usuario u_inicio ON u_inicio.sus_id = a.codusuinicio
								LEFT  JOIN seguranca.seg_usuario u_fim ON u_fim.sus_id = a.codusufim
							WHERE a.codusuinicio = :codusuinicio AND a.status = 'A' AND a.codusufim IS NULL
								ORDER BY a.codatendimento desc;";
			
			$p_sql = $this->MySql->prepare($_query);
			$p_sql->bindValue(":codusuinicio", $_SESSION['protocolo']['myId']);
			
			if($p_sql->execute()) {
				
				if($show = $p_sql->fetch(PDO::FETCH_ASSOC)) {
					return $show;
				}else {
					return '';
				}
			}else{
				die('Erro ao retornar ultimo atendimento, entre em contato com o TI.');
			}
		}
		public function atendimentosDoUsuario(){
			
			$_query = "SELECT a.*, 
								u_inicio.sus_nome as usu_inicio, 
								u_fim.sus_nome as usu_fim,
								(select count(*) from protocolo.protocolo_atendimento pa where pa.codatendimento = a.codatendimento) qtde_protocolos
							FROM protocolo.atendimento a 
								INNER JOIN seguranca.seg_usuario u_inicio ON u_inicio.sus_id = a.codusuinicio
								LEFT  JOIN seguranca.seg_usuario u_fim ON u_fim.sus_id = a.codusufim
							WHERE a.codusuinicio = :codusuinicio AND a.status = 'A'
								ORDER BY a.codatendimento desc
									LIMIT 200;";
			
			$p_sql = $this->MySql->prepare($_query);
			$p_sql->bindValue(":codusuinicio", $_SESSION['protocolo']['myId']);
			
			if($p_sql->execute()) {
				
				$array = array();
				
				while($show = $p_sql->fetch(PDO::FETCH_ASSOC)) {
					$array[] = $show;
				}
				
				return $array;
			}else{
				die('Erro ao retornar atendimentos do usuario, entre em contato com o TI.');
			}
		}
		public function todosAtendimentos(){
			
			$_query = "SELECT a.*, 
								u_inicio.sus_nome as usu_inicio, 
								u_fim.sus_nome as usu_fim,
								(select count(*) from protocolo.protocolo_atendimento pa where pa.codatendimento = a.codatendimento) qtde_protocolos
							FROM protocolo.atendimento a 
								INNER JOIN seguranca.seg_usuario u_inicio ON u_inicio.sus_id = a.codusuinicio
								LEFT  JOIN seguranca.seg_usuario u_fim ON u_fim.sus_id = a.codusufim
							WHERE a.status = 'A'
									ORDER BY a.codatendimento desc
										LIMIT 40;";
			
			$p_sql = $this->MySql->prepare($_query);
			
			if($p_sql->execute()) {
				
				$array = array();
				
				while($show = $p_sql->fetch(PDO::FETCH_ASSOC)) {
					$array[] = $show;
				}
				
				return $array;
			}else{
				die('Erro ao retornar todos os atendimentos, entre em contato com o TI.');
			}
		}
		public function protocolosDoAtendimento($codatendimento){
			
			$_query = "SELECT *
							FROM protocolo.protocolo_atendimento pa 
								INNER JOIN protocolo.protocolos p ON p.codprotocolo = pa.codprotocolo
									INNER JOIN cadastro.cad_setor cs on cs.cse_id = p.codsetoratual
										LEFT JOIN protocolo.protocolo_tipos pt on pt.codtipo = p.codtipo
							WHERE pa.codatendimento = :codatendimento
								ORDER BY p.codprotocolo desc;";
			
			$p_sql = $this->MySql->prepare($_query);
			$p_sql->bindValue(":codatendimento", $codatendimento);
			
			if($p_sql->execute()) {
				
				$array = array();
				
				while($show = $p_sql->fetch(PDO::FETCH_ASSOC)) {
					$array[] = $show;
				}
				
				return $array;
			}else{
				die('Erro ao retornar ultimo atendimento, entre em contato com o TI.');
			}
		}
		public function fechaAtendimento($dados){
			
			$query = "UPDATE protocolo.atendimento SET 
								datahorafim = :datahorafim,
								codusufim = :codusufim,
								ipusufim = :ipusufim,
								hostusufim = :hostusufim,
								setorusufim = :setorusufim,
								codsetorusufim = :codsetorusufim,
								status = :status
							where codatendimento = :codatendimento;";
			
            try{
				$this->insert = $this->MySql->prepare($query);
				$this->insert->bindValue(":datahorafim", date("Y-m-d H:i:s"), PDO::PARAM_STR);	
				$this->insert->bindValue(":codusufim", $dados['codusufim'], PDO::PARAM_STR);	
				$this->insert->bindValue(":ipusufim", $dados['ipusufim'], PDO::PARAM_STR);	
				$this->insert->bindValue(":hostusufim", $dados['hostusufim'], PDO::PARAM_STR);	
				$this->insert->bindValue(":setorusufim", $dados['setorusufim'], PDO::PARAM_STR);
				$this->insert->bindValue(":codsetorusufim", $dados['codsetorusufim'], PDO::PARAM_STR);
				$this->insert->bindValue(":status", 'A', PDO::PARAM_STR);
				$this->insert->bindValue(":codatendimento", $dados['codatendimento'], PDO::PARAM_STR);
				
				if($this->insert->execute()){
					return true;
				}else{
					return false;
				}
            }catch(PDOExeption $e){
                    die($e->getMessage());
            }  
		}
		public function vinculaProtocoloAtendimento($codprotocolo, $codatendimento){
			
			//echo "<pre>"; print_r($dados); exit;
			    
			$Query = "INSERT INTO protocolo.protocolo_atendimento (
															codprotocolo, 
															codatendimento
														) VALUES (
															:codprotocolo, 
															:codatendimento
														);";
                
			try{
				$this->insert = $this->MySql->prepare($Query);
				$this->insert->bindValue(":codprotocolo", $codprotocolo, PDO::PARAM_STR);	
				$this->insert->bindValue(":codatendimento", $codatendimento, PDO::PARAM_STR);	
				
				if( $this->insert->execute() ){
					return true;
				}else{
					return false;
				}
			}catch(PDOExeption $e){
				die($e->getMessage());
			}
		}
		public function geraRelatorioProtocolos($setor,$periodo,$status,$codtipo,$assunto){
			
			//echo "<pre>"; print_r($setor); exit;
			
			$WHERE = array();
			
			if( !empty( $status  ) ) $WHERE[] = "p.status = '$status'";
			if( !empty( $codtipo ) ) $WHERE[] = "pt.codtipo = '$codtipo'";
			if( !empty( $assunto ) ) $WHERE[] = "p.assunto like '%$assunto%'";
			
			if( !empty( $periodo ) ) { $datcriacao = "and DATE_FORMAT(pe.datacriacao,'%Y-%m') = '$periodo'"; }else{ $datcriacao = ""; };
			if( !empty( $setor   ) ) { $setores = "and u.sus_setor = '$setor'"; }else{ $setores = ""; };
			
			$Query = "SELECT 
                       CASE p.status WHEN 'P' THEN 'Pedente' WHEN 'F' THEN 'Finalizado' WHEN 'I' THEN 'Inativado' ELSE p.status END as status,
                       DATE_FORMAT(pe.datacriacao,'%d/%m/%Y %H:%i:%s') as datacriacao,
                       p.numprotocolo,
                       p.nomeremetente as beneficiario,
                       p.cpfremetente as carteirinha,
                       LOWER(p.assunto) as assunto,
                       LOWER(p.observacao) as observacao,
					   u.sus_setor as setor,
					   u.sus_nome as usuario,
					   pt.tipo,
					   pm.descricao as motivo
                        FROM protocolo.protocolos p
							left join protocolo_motivos pm ON pm.codmotivo = p.codmotivo
							inner join protocolo.protocolo_eventos pe on pe.codprotocolo = p.codprotocolo and pe.evento = 'cadastrado' $datcriacao
							inner join seguranca.seg_usuario u on u.sus_id = pe.codusucadastro $setores
							left join protocolo.protocolo_tipos pt on pt.codtipo = p.codtipo
						";
			
			if( !empty($WHERE) ) $Query .= ' WHERE '.implode(' AND ', $WHERE );
			
			$Query .= " order by pe.datacriacao desc";
			
			//echo "<pre>"; print_r($Query); exit;
			
			try {
				$this->search = $this->MySql->prepare($Query);
				//$this->search->bindParam(":nome", $this->cadProtocoloModel);
				$this->search->execute();

			} catch(PDOException $e) {
				die($e->getMessage());
			}

			$_result = $this->search->fetchAll(PDO::FETCH_ASSOC);
			//echo "</pre>"; print_r($_result); exit;
			return $_result;
			
		}
		public function geraRelatorioPendentesGeral($setores,$periodo,$codtipo){
			
			//echo "<pre>"; print_r($setor); exit;
			
			if( !empty( $codtipo   ) ) { $codtipo = "and pt.codtipo = '$codtipo'"; }else{ $codtipo = ""; };
			if( !empty( $periodo   ) ) { $periodo = "and DATE_FORMAT(e.datacriacao,'%Y-%m') = '$periodo'"; }else{ $periodo = ""; };
			if( !empty( $setores   ) ) { $setores = "and c.cse_id = '$setores'"; }else{ $setores = ""; };
			
			$Query = "select 
							DATE_FORMAT(e.datacriacao,'%d/%m/%Y %H:%i') as datacriacao,
							c.cse_setor_descricao as setor, 
							p.numprotocolo,
							p.nomeremetente,
							p.cpfremetente,
							p.assunto,
							pt.tipo,
							p.status	
							from protocolo.protocolos p 
									inner join protocolo.protocolo_eventos e on e.codprotocolo = p.codprotocolo and e.evento = 'cadastrado' $periodo
									inner join protocolo.protocolo_tipos pt on pt.codtipo = p.codtipo $codtipo
									inner join seguranca.seg_usuario u on u.sus_id = e.codusucadastro
									inner join cadastro.cad_setor c on c.cse_id = p.codsetoratual $setores
									  where p.status = 'P' and p.clientepodeeditar != 'S';";
			
			//echo "<pre>"; print_r($Query); exit;
			
			try {
				$this->search = $this->MySql->prepare($Query);
				//$this->search->bindParam(":nome", $this->cadProtocoloModel);
				$this->search->execute();

			} catch(PDOException $e) {
				die($e->getMessage());
			}

			$_result = $this->search->fetchAll(PDO::FETCH_ASSOC);
			//echo "</pre>"; print_r($_result); exit;
			return $_result;
			
		}
		public function geraRelatorioPendentesQuantitativo($setores,$periodo,$codtipo){
			
			//echo "<pre>"; print_r($setor); exit;
			
			if( !empty( $codtipo   ) ) { $codtipo = "and pt.codtipo = '$codtipo'"; }else{ $codtipo = ""; };
			if( !empty( $periodo   ) ) { $periodo = "and DATE_FORMAT(e.datacriacao,'%Y-%m') = '$periodo'"; }else{ $periodo = ""; };
			if( !empty( $setores   ) ) { $setores = "and c.cse_id = '$setores'"; }else{ $setores = ""; };
			
			$Query = "select a.setor, a.tipo, sum(a.qtde) as qtde from (

						select c.cse_setor_descricao as setor, pt.tipo, count(p.codtipo) as qtde
								
							from protocolo.protocolos p 
									inner join protocolo.protocolo_eventos e on e.codprotocolo = p.codprotocolo and e.evento = 'cadastrado' $periodo
									inner join protocolo.protocolo_tipos pt on pt.codtipo = p.codtipo $codtipo
									inner join seguranca.seg_usuario u on u.sus_id = e.codusucadastro
									inner join cadastro.cad_setor c on c.cse_id = p.codsetoratual $setores
									  where p.status = 'P' -- and p.clientepodeeditar != 'S'
							   group by p.codsetoratual, p.codtipo
							   
					) a group by a.setor, a.tipo order by a.setor asc, a.tipo asc;";
			
			//echo "<pre>"; print_r($Query); exit;
			
			try {
				$this->search = $this->MySql->prepare($Query);
				//$this->search->bindParam(":nome", $this->cadProtocoloModel);
				$this->search->execute();

			} catch(PDOException $e) {
				die($e->getMessage());
			}

			$_result = $this->search->fetchAll(PDO::FETCH_ASSOC);
			//echo "</pre>"; print_r($_result); exit;
			return $_result;
			
		}
		public function geraRelatorioProtocolosDespachos($setor,$periodo,$status,$codtipo){
			
			//echo "<pre>"; print_r($setor); exit;
			
			$setor = $this->selectIdSetorPorSigla($setor);
			
			$WHERE = array();
			
			if( !empty( $status   ) ) $WHERE[] = "p.status = '$status'";
			if( !empty( $codtipo   ) ) $WHERE[] = "pt.codtipo = '$codtipo'";
			
			if( !empty( $periodo   ) ) { $datenvio = "and DATE_FORMAT(pd.dataenviou,'%Y-%m') = '$periodo'"; }else{ $datenvio = ""; };
			
			$Query = "select 
                       CASE p.status WHEN 'P' THEN 'Pedente' WHEN 'F' THEN 'Finalizado' WHEN 'I' THEN 'Inativado' ELSE p.status END as status,
                       DATE_FORMAT(pe.datacriacao,'%d/%m/%Y %H:%i:%s') as datacriacao,
                       p.numprotocolo,
                       p.nomeremetente as beneficiario,
                       p.cpfremetente as carteirinha,
                       LOWER(p.assunto) as assunto,
                       LOWER(p.observacao) as observacao,
					   u.sus_setor as setor,
					   u.sus_nome as usuario,
					   pt.tipo,
					   pm.descricao as motivo
                        from protocolo.protocolos p
							left join protocolo_motivos pm ON pm.codmotivo = p.codmotivo
							inner join protocolo.protocolo_eventos pe on pe.codprotocolo = p.codprotocolo and pe.evento = 'cadastrado'
								inner join seguranca.seg_usuario u on u.sus_id = pe.codusucadastro
									inner join protocolo.protocolo_despachos pd on pd.codprotocolo = p.codprotocolo and pd.codsetdestino = '$setor' $datenvio
										left join protocolo.protocolo_tipos pt on pt.codtipo = p.codtipo
								";
			
			if( !empty($WHERE) ) $Query .= ' WHERE '.implode(' AND ', $WHERE );
			
			$Query .= " group by p.codprotocolo order by pd.dataenviou desc";
			
			//echo "<pre>"; print_r($Query); exit;
			
			try {
				$this->search = $this->MySql->prepare($Query);
				//$this->search->bindParam(":nome", $this->cadProtocoloModel);
				$this->search->execute();

			} catch(PDOException $e) {
				die($e->getMessage());
			}

			$_result = $this->search->fetchAll(PDO::FETCH_ASSOC);
			//echo "</pre>"; print_r($_result); exit;
			return $_result;
			
		}
		public function geraRelatorioMotivos($codtipo,$ano){
			
			$Query = "select pm.codmotivo, pm.descricao from protocolo.protocolo_motivos pm where pm.codtipo = '$codtipo';";
			
			$this->search = $this->MySql->prepare($Query);
			$this->search->execute();
			
			$motivos = $this->search->fetchAll(PDO::FETCH_ASSOC);
			
			$resultado=array();
			
			foreach($motivos as $motivo){
				
				$desmotivo = $motivo["descricao"];
				$codmotivo = $motivo["codmotivo"];
				
				$meses = array("Jan" => '01', "Fev" => '02', "Mar" => '03',"Abr" => '04', "Mai" => '05', "Jun" => '06', "Jul" => '07', "Ago" => '08', "Set" => '09',"Out" => '10', "Nov" => '11', "Dez" => '12');
				
				foreach($meses as $desc => $mes){
					
					$Query = "select count(p.codmotivo) as qtde from protocolo.protocolos p 
								inner join protocolo.protocolo_eventos e on e.codprotocolo = p.codprotocolo and e.evento = 'cadastrado' and DATE_FORMAT(e.datacriacao, '%Y-%m') = '$ano-$mes'
									where p.codtipo = '$codtipo' and p.codmotivo = '$codmotivo' and p.status in ('F','P')
										group by p.codmotivo;";
					
					$this->search = $this->MySql->prepare($Query);
					$this->search->execute();
					
					$show = $this->search->fetch(PDO::FETCH_ASSOC);
					
					@$resultado[$desmotivo][$desc] += ( empty($show['qtde']) ? 0 : $show['qtde'] );
				}
				
			}
			
			return $resultado;
		}
		public function geraRelatorioProtocolosPorUnimed($dt_inicial,$dt_final){
			
			$Query = "select 
							LPAD(substr(p.cpfremetente,1,4),4,'0') as codunimed
							,CASE substr(p.cpfremetente,1,4)
								WHEN '0079' THEN 'UNIMED MANAUS'
								WHEN '0985' THEN 'UNIMED FAMA'
								WHEN '0865' THEN 'UNIMED CNU'
								ELSE 'OUTRAS FEDERAÇÕES' END as unimed
							,p.cpfremetente as carteirinha
							,p.numprotocolo
							,date_format(pe.datacriacao,'%Y-%m-%d') as datacadastro
							,p.status
							,p.assunto
							
						from protocolo.protocolos p 
							inner join protocolo.protocolo_eventos pe on pe.codprotocolo = p.codprotocolo 
								and pe.evento = 'cadastrado' 
								and date_format(pe.datacriacao,'%Y-%m-%d') between '$dt_inicial' and '$dt_final'
						where length(p.cpfremetente) = 17
								and p.status in('P','F')
						order by 2 desc;";
			
			//echo "<pre>"; print_r($Query); exit;
			
			try {
				$this->search = $this->MySql->prepare($Query);
				$this->search->execute();

			} catch(PDOException $e) {
				die($e->getMessage());
			}

			$_result = $this->search->fetchAll(PDO::FETCH_ASSOC);
			
			return $_result;
		}
		public function geraRelatorioRCC($codtipo,$dt_inicial,$dt_final){
			
			$Query = "select 	
							a.setor as setor_atual,
							a.tipo as tipo_protocolo, 
							a.numprotocolo as numero_protocolo, 
							DATE_FORMAT(a.datacriacao,'%d/%m/%Y') as data_cadastro, 
							DATE_FORMAT(a.datacriacao,'%H:%i') as hora_cadastro, 
							a.canatendimento as canal_atendimento,
							a.nomeremetente as beneficiario, 
							a.cpfremetente as carteirinha, 
							a.assunto, 
							a.status, 
							a.sus_nome as usuario_cadastro,
							a.codtipo,
							a.codprotocolo,
							a.telefone
						from (

							select u.sus_nome, c.cse_setor_descricao as setor, pt.tipo, p.canatendimento, p.codprotocolo, p.numprotocolo, e.datacriacao, p.nomeremetente, p.cpfremetente, p.assunto, p.status, p.codtipo, p.telefone
									
								from protocolo.protocolos p 
										inner join protocolo.protocolo_eventos e on e.codprotocolo = p.codprotocolo 
											AND e.evento = 'cadastrado' 
											AND DATE_FORMAT(e.datacriacao,'%Y-%m-%d') between '$dt_inicial' and '$dt_final'
										inner join protocolo.protocolo_tipos pt on pt.codtipo = p.codtipo 
											AND pt.codtipo = '$codtipo'
										inner join seguranca.seg_usuario u on u.sus_id = e.codusucadastro
										inner join cadastro.cad_setor c on c.cse_id = p.codsetoratual 
								   group by p.codprotocolo
									   
						) a order by a.datacriacao desc;";
			
			//echo "<pre>"; print_r($Query); exit;
			
			try {
				$this->search = $this->MySql->prepare($Query);
				$this->search->execute();

			} catch(PDOException $e) {
				die($e->getMessage());
			}

			$_result = $this->search->fetchAll(PDO::FETCH_ASSOC);
			
			return $_result;
		}
		public function geraRelatorioRCC_geral($dt_inicial,$dt_final){
			
			$Query = "select 	
							a.setor as setor_atual,
							a.tipo as tipo_protocolo, 
							a.numprotocolo as numero_protocolo, 
							DATE_FORMAT(a.datacriacao,'%d/%m/%Y') as data_cadastro, 
							DATE_FORMAT(a.datacriacao,'%H:%i') as hora_cadastro, 
							a.canatendimento as canal_atendimento,
							a.nomeremetente as beneficiario, 
							a.cpfremetente as carteirinha, 
							a.assunto, 
							a.status, 
							a.sus_nome as usuario_cadastro,
							a.codtipo,
							a.codprotocolo,
							a.telefone
						from (

							select u.sus_nome, c.cse_setor_descricao as setor, pt.tipo, p.canatendimento, p.codprotocolo, p.numprotocolo, e.datacriacao, p.nomeremetente, p.cpfremetente, p.assunto, p.status, p.codtipo, p.telefone
									
								from protocolo.protocolos p 
										inner join protocolo.protocolo_eventos e on e.codprotocolo = p.codprotocolo 
											AND e.evento = 'cadastrado' 
											AND DATE_FORMAT(e.datacriacao,'%Y-%m-%d') between '$dt_inicial' and '$dt_final'
										inner join protocolo.protocolo_tipos pt on pt.codtipo = p.codtipo 
										inner join seguranca.seg_usuario u on u.sus_id = e.codusucadastro
										inner join cadastro.cad_setor c on c.cse_id = p.codsetoratual 
								   group by p.codprotocolo
									   
						) a order by a.datacriacao desc;";
			
			//echo "<pre>"; print_r($Query); exit;
			
			try {
				$this->search = $this->MySql->prepare($Query);
				$this->search->execute();

			} catch(PDOException $e) {
				die($e->getMessage());
			}

			$_result = $this->search->fetchAll(PDO::FETCH_ASSOC);
			
			return $_result;
		}
		public function geraColunasDadosComplementares($codtipo,$codprotocolo){
			
			$p_sql = $this->MySql->prepare("select c.tabela, c.descricao, v.valor , c.tipo
												from  protocolo.protocolo_campos c 
													left join protocolo.protocolo_valores v on v.codcampo = c.codcampo and v.codprotocolo = '$codprotocolo'
														where c.codtipo = '$codtipo' and c.ativo = 'S' and c.tipo != 'A'
															order by c.descricao asc;");
			
			if($p_sql->execute()) {
				$array=array();
				while($show = $p_sql->fetch(PDO::FETCH_ASSOC)) {
					$array[] = $show;
				}
				return $array;
			}
		}
		public function geraRelatorioRCC_009_010($dt_inicial,$dt_final){
			
			$Query = "select 	
							a.setor as setor_atual,
							a.tipo as tipo_protocolo, 
							a.numprotocolo as numero_protocolo, 
							DATE_FORMAT(a.datacriacao,'%d/%m/%Y') as data_cadastro, 
							DATE_FORMAT(a.datacriacao,'%H:%i') as hora_cadastro, 
							a.canatendimento as canal_atendimento,
							a.nomeremetente as beneficiario, 
							a.cpfremetente as carteirinha, 
							a.assunto, 
							a.status, 
							a.sus_nome as usuario_cadastro,
							a.codtipo,
							a.codprotocolo,
							a.telefone,
							a.clientepodeeditar,
							a.classificacao
						from (

							select u.sus_nome, c.cse_setor_descricao as setor, pt.tipo, p.canatendimento, p.codprotocolo, p.numprotocolo, e.datacriacao, p.nomeremetente, p.cpfremetente, p.assunto, p.status, p.codtipo, p.telefone, p.clientepodeeditar, OBTER_CLASSIFICACAO_PROTOCOLO(p.codprotocolo,p.codsetoratual,'D') as classificacao
									
								from protocolo.protocolos p 
										inner join protocolo.protocolo_eventos e on e.codprotocolo = p.codprotocolo 
											AND e.evento = 'cadastrado' 
											AND DATE_FORMAT(e.datacriacao,'%Y-%m-%d') between '$dt_inicial' and '$dt_final'
										inner join protocolo.protocolo_tipos pt on pt.codtipo = p.codtipo 
											AND pt.codtipo in(111,112)
										inner join seguranca.seg_usuario u on u.sus_id = e.codusucadastro
										inner join cadastro.cad_setor c on c.cse_id = p.codsetoratual 
								   group by p.codprotocolo
									   
						) a order by a.codtipo asc, a.datacriacao desc;";
			
			//echo "<pre>"; print_r($Query); exit;
			
			try {
				$this->search = $this->MySql->prepare($Query);
				$this->search->execute();

			} catch(PDOException $e) {
				die($e->getMessage());
			}

			$_result = $this->search->fetchAll(PDO::FETCH_ASSOC);
			
			return $_result;
		}
		
		public function retornaValorProcedimentosTabelaTuss($valor){
			
			$p_sql = $this->MySql->prepare("select t.descricao from protocolo.tbl_tuss t where t.codigo = '$valor';");
			
			if($p_sql->execute()) {
				if($show = $p_sql->fetch(PDO::FETCH_ASSOC)) {
					return $show['descricao'];
				}else{
					return null;
				}
			}
		}
		
		public function retornaGruposDeAssunto(){
			
			$_query = "SELECT * FROM 
						protocolo.protocolo_grupos pg
							where pg.ativo = 'S'
								order by grupo asc";
			
			$p_sql = $this->MySql->prepare($_query);
			
			if($p_sql->execute()) {
				$array=array();
				while($show = $p_sql->fetch(PDO::FETCH_ASSOC)) {
					$array[] = $show;
				}
				return $array;
			}else {
				die('Erro ao verificar numero de protocolo, entre em contato com o TI.');
			}
			
		}
		public function retornaTiposDeAssunto(){
			
			$_query = "SELECT pt.*, pg.grupo FROM 
							protocolo.protocolo_tipos pt
								inner join protocolo.protocolo_grupos pg on pg.codgrupo = pt.codgrupo and pg.ativo = 'S'
									order by pg.codgrupo asc, pt.tipo asc";
			
			$p_sql = $this->MySql->prepare($_query);
			
			if( $p_sql->execute() ) {
				
				$array=array();
				
				while($show = $p_sql->fetch(PDO::FETCH_ASSOC)) {
					$array[] = $show;
				}
				
				return $array;
			}else {
				die('Erro ao verificar numero de protocolo, entre em contato com o TI.');
			}
		}
		public function retornaComentariosDoProtocolo($codprotocolo){
			
			$_query = "SELECT pc.*, u.sus_nome as usuario
							FROM protocolo.protocolo_comentarios pc
								INNER JOIN seguranca.seg_usuario u on u.sus_id = pc.codusucadastro
							WHERE pc.codprotocolo = '$codprotocolo'
									ORDER BY pc.codcomentario desc";
			
			$p_sql = $this->MySql->prepare($_query);
			
			if( $p_sql->execute() ) {
				
				$array=array();
				
				while($show = $p_sql->fetch(PDO::FETCH_ASSOC)) {
					$array[] = $show;
				}
				
				return $array;
			}else {
				die('Erro ao verificar comentario do protocolo, entre em contato com o TI.');
			}
		}
		public function retornaDadosTipoPeloCodigo($codtipo){
			
			$_query = "SELECT * FROM protocolo.protocolo_tipos pt where pt.codtipo = '$codtipo' order by tipo asc";
			
			$p_sql = $this->MySql->prepare($_query);
			
			if($p_sql->execute()) {
			
				if($show = $p_sql->fetch(PDO::FETCH_ASSOC)) {
					return $show;
				}else{
					return '';
				}
			}else {
				die('Erro ao verificar numero de protocolo, entre em contato com o TI.');
			}
		}
		public function retornaCamposDoTipo($codtipo){
			
			$_query = "select c.*, t.tipo as assunto, cs.cse_setor_descricao as setorpermitidodesc
							from protocolo.protocolo_campos c 
								inner join protocolo.protocolo_tipos t on t.codtipo = c.codtipo
								left join cadastro.cad_setor cs on cs.cse_id = c.setorpermitido
									where c.codtipo = '$codtipo' order by c.separador asc, c.sequencia asc;";
			
			$p_sql = $this->MySql->prepare($_query);
			
			$options=array();
			
			if($p_sql->execute()) {
				
				while($show = $p_sql->fetch(PDO::FETCH_ASSOC)) {
					$options[] = $show;
				}
			}
			
			return($options);
		}
		public function retornaCamposDoTipoWeb($codtipo){
			
			$_query = "select c.*, t.tipo as assunto, cs.cse_setor_descricao as setorpermitidodesc
							from protocolo.protocolo_campos c 
								inner join protocolo.protocolo_tipos t on t.codtipo = c.codtipo
								left join cadastro.cad_setor cs on cs.cse_id = c.setorpermitido
							where c.codtipo = '$codtipo' and c.web = 'S' 
								order by c.separador asc, c.sequencia asc;";
			
			$p_sql = $this->MySql->prepare($_query);
			
			$options=array();
			
			if($p_sql->execute()) {
				
				while($show = $p_sql->fetch(PDO::FETCH_ASSOC)) {
					$options[] = $show;
				}
			}
			
			return($options);
		}
		public function retornaDetalhesCampo($codcampo){
			
			$_query = "select * from protocolo.protocolo_campos c where c.codcampo = '$codcampo';";
			
			$p_sql = $this->MySql->prepare($_query);
			
			if($p_sql->execute()) {
				if($show = $p_sql->fetch(PDO::FETCH_ASSOC)) {
					return $show;
				}else{
					return null;
				}
			}
		}
		public function retornaSequenciaProximaDoTipo($codtipo){
			
			$_query = "SELECT c.sequencia FROM protocolo.protocolo_campos c where c.codtipo = '$codtipo' order by c.sequencia desc limit 1;";
			
			$p_sql = $this->MySql->prepare($_query);
			
			if($p_sql->execute()) {
			
				if($show = $p_sql->fetch(PDO::FETCH_ASSOC)) {
					return $show['sequencia'] + 1;
				}else{
					return 1;
				}
			}else {
				die('Erro ao verificar sequencia do tipo, entre em contato com o TI.');
			}
		}
		public function RetornaCanalAtendimento($canal){
			if($canal == 'P'){
				return 'Presencial';
			}else if($canal == 'T'){
				return 'Telefônico';
			}else if($canal == 'W'){
				return 'Website';
			}else if($canal == 'E'){
				return 'Email';
			}else{
				return 'Indefinido';
			}
		}
		public function RetornaTempoEmAberto($datcadastro, $status){
			
			if($status != 'P'){
				return '--/--';
			}else{
				
				$datatime1 = new DateTime($datcadastro);
				$datatime2 = new DateTime(date('Y-m-d H:i:s'));

				$data1  = $datatime1->format('Y-m-d H:i:s');
				$data2  = $datatime2->format('Y-m-d H:i:s');

				$diff = $datatime1->diff($datatime2);
				$hours = $diff->h + ($diff->days * 24);
				
				if($hours >= 8064){ //Maior ou igual a um ano
					$aux = (int) $aux = $hours/8064;
					return "Há ".(($hours/8064 > 1)?"mais de ":"").$aux." ano".(($aux >= 2)?"s":"");
				} else if($hours >= 672){ //Maior ou igual a um mês
					$aux = (int) $aux = $hours/672;
					return  "Há ".(($hours/672 > 1)?"mais de ":"").$aux." m".(($aux >= 2)?"eses":"ês");
				} else if($hours >= 168){ //Maior ou igual a uma semana
					$aux = (int) $aux = $hours/168;
					return  "Há ".(($hours/168 > 1)?"mais de ":"").$aux." semana".(($aux >= 2)?"s":"");
				} else if($hours >= 24){ //Maior ou igual a um dia
					$aux = (int) $aux = $hours/24;
					return  "Há ".$aux." dia".(($aux >= 2)?"s":"");			
				} else if($hours >= 1){ //Diferente de 0 (horas)
					return  "Há ".$hours." hora".(($hours >= 2)?"s":"");	
				} else if($hours == 0){ //Se não for em minutos
					return "Há alguns minutos";
				} else{
					return "Sem registro";
				} 												
			}
		}
		public function retornaTempoAtendimento($date1,$date2){
			
			$datatime1 = new DateTime($date1);
			$datatime2 = new DateTime($date2);

			$data1  = $datatime1->format('Y-m-d H:i:s');
			$data2  = $datatime2->format('Y-m-d H:i:s');

			$diferenca = $datatime1->diff($datatime2);
			
			//echo "<pre>"; print_r($diferenca);
			
			return $diferenca;
		}
		public function retornaMotivosAtivosDoTipo($codtipo){
				 
			$_query = "select * from protocolo.protocolo_motivos m where m.codtipo = '$codtipo' and m.ativo = 'S' order by m.descricao asc;";
		
			$p_sql = $this->MySql->prepare($_query);
			
			$options=array();
			
			if($p_sql->execute()) {
				
				while($show = $p_sql->fetch(PDO::FETCH_ASSOC)) {
					$options[] = $show;
				}
			}
			
			return($options);
		}
		public function retornaMotivosDoTipo($codtipo){
				
			$_query = "select m.*, t.tipo from protocolo.protocolo_motivos m 
							inner join protocolo.protocolo_tipos t on t.codtipo = m.codtipo
								where m.codtipo = '$codtipo' order by m.descricao asc;";
		
			$p_sql = $this->MySql->prepare($_query);
			
			$options=array();
			
			if($p_sql->execute()) {
				
				while($show = $p_sql->fetch(PDO::FETCH_ASSOC)) {
					$options[] = $show;
				}
			}
			
			return($options);
		}
		public function retornaSeparadoresDoTipo($codtipo){
				
			$_query = "select s.*, t.tipo from protocolo.protocolo_separadores s 
							inner join protocolo.protocolo_tipos t on t.codtipo = s.codtipo
								where s.codtipo = '$codtipo' order by s.descricao asc;";
		
			$p_sql = $this->MySql->prepare($_query);
			
			$options=array();
			
			if($p_sql->execute()) {
				
				while($show = $p_sql->fetch(PDO::FETCH_ASSOC)) {
					$options[] = $show;
				}
			}
			
			return($options);
		}
		public function retornaDescricaoDoSeparador($codseparador,$codtipo){
				
			$_query = "select * from protocolo.protocolo_separadores s 
								where s.codseparador = '$codseparador' and s.codtipo = '$codtipo';";
		
			$p_sql = $this->MySql->prepare($_query);
			
			if($p_sql->execute()) {
				
				if($show = $p_sql->fetch(PDO::FETCH_ASSOC)) {
					return $show['descricao'];
				}else{
					return $codseparador;
				}
			}
		}
		public function retornaCodSetorInicialPeloTipo($codtipo){
			
			$_query = "SELECT * FROM protocolo.protocolo_tipos pt where pt.codtipo = '$codtipo' order by tipo asc";
			
			$p_sql = $this->MySql->prepare($_query);
			
			if($p_sql->execute()) {
				
				if($show = $p_sql->fetch(PDO::FETCH_ASSOC)) {
					return $show['codsetorinicial'];
				}else{
					return null;
				}
			}
		}
		public function formInsereComentario($dados){
			
			//echo "<pre>"; print_r($dados); exit;
			
			try{
				$this->insert = $this->MySql->prepare("INSERT INTO protocolo.protocolo_comentarios (codprotocolo, comentario, datcadastro, codusucadastro, setorusucadastro) VALUES (:codprotocolo, :comentario, :datcadastro, :codusucadastro, :setorusucadastro);");
				
				$this->insert->bindValue(":codprotocolo", $dados['codprotocolo'], PDO::PARAM_STR);	
				$this->insert->bindValue(":comentario", $dados['comentario'], PDO::PARAM_STR);	
				$this->insert->bindValue(":datcadastro", date('Y-m-d H:i:s'), PDO::PARAM_STR);	
				$this->insert->bindValue(":codusucadastro", $dados['codusucadastro'], PDO::PARAM_STR);	
				$this->insert->bindValue(":setorusucadastro", $dados['setorusucadastro'], PDO::PARAM_STR);	
				
				if( $this->insert->execute() ){
					return true;
				}else{
					return false;
				}
			}catch(PDOExeption $e){
				die($e->getMessage());
			}
		}
		
//================================================================ CONFIGURACOES ====================================================================		
		public function formNovoTipo($dados){
			
			//echo "<pre>"; print_r($dados); exit;
			
			try{
				$this->insert = $this->MySql->prepare("INSERT INTO protocolo.protocolo_tipos (tipo, codgrupo, ativo, prazo, web) VALUES (:tipo,:codgrupo,:ativo,:prazo,:web);");
				
				$this->insert->bindValue(":tipo", $dados['tipo'], PDO::PARAM_STR);	
				$this->insert->bindValue(":codgrupo", $dados['codgrupo'], PDO::PARAM_STR);	
				$this->insert->bindValue(":ativo", $dados['ativo'], PDO::PARAM_STR);	
				$this->insert->bindValue(":prazo", $dados['prazo'], PDO::PARAM_STR);	
				$this->insert->bindValue(":web", $dados['web'], PDO::PARAM_STR);	
				
				if( $this->insert->execute() ){
					return true;
				}else{
					return false;
				}
			}catch(PDOExeption $e){
				die($e->getMessage());
			}
		}
		public function formAtualizaTipo($codtipo, $dados){
			
			//echo "<pre>"; print_r($dados); exit;
			
			try{
				$this->insert = $this->MySql->prepare("UPDATE protocolo.protocolo_tipos SET 
																tipo = :tipo, 
																codgrupo = :codgrupo, 
																ativo = :ativo, 
																prazo = :prazo,
																web	= :web
																	WHERE codtipo = :codtipo;");
				
				$this->insert->bindValue(":tipo", $dados['tipo'], PDO::PARAM_STR);	
				$this->insert->bindValue(":codgrupo", $dados['codgrupo'], PDO::PARAM_STR);	
				$this->insert->bindValue(":ativo", $dados['ativo'], PDO::PARAM_STR);	
				$this->insert->bindValue(":prazo", $dados['prazo'], PDO::PARAM_STR);	
				$this->insert->bindValue(":web", $dados['web'], PDO::PARAM_STR);	
				$this->insert->bindValue(":codtipo", $codtipo, PDO::PARAM_STR);	
				
				if( $this->insert->execute() ){
					return true;
				}else{
					return false;
				}
			}catch(PDOExeption $e){
				die($e->getMessage());
			}
		}
		
		public function formNovoMotivo($dados){
			
			//echo "<pre>"; print_r($dados); exit;
			
			try{   
				$this->insert = $this->MySql->prepare("INSERT INTO protocolo.protocolo_motivos (codtipo, descricao, ativo) VALUES (:codtipo,:descricao,:ativo);");
				
				$this->insert->bindValue(":codtipo", $dados['codtipo'], PDO::PARAM_STR);	
				$this->insert->bindValue(":descricao", $dados['descricao'], PDO::PARAM_STR);	
				$this->insert->bindValue(":ativo", $dados['ativo'], PDO::PARAM_STR);	
				
				if( $this->insert->execute() ){
					return true;
				}else{
					return false;
				}
			}catch(PDOExeption $e){
				die($e->getMessage());
			}
		}
		public function formAtualizaMotivo($codmotivo, $dados){
			
			//echo "<pre>"; print_r($dados); exit;
			
			try{
				$this->insert = $this->MySql->prepare("UPDATE protocolo.protocolo_motivos SET descricao = :descricao, ativo = :ativo WHERE codmotivo = :codmotivo;");
				
				$this->insert->bindValue(":descricao", $dados['descricao'], PDO::PARAM_STR);	
				$this->insert->bindValue(":ativo", $dados['ativo'], PDO::PARAM_STR);	
				$this->insert->bindValue(":codmotivo", $codmotivo, PDO::PARAM_STR);	
				
				if( $this->insert->execute() ){
					return true;
				}else{
					return false;
				}
			}catch(PDOExeption $e){
				die($e->getMessage());
			}
		}
		
		public function formNovoSeparador($dados){
			
			//echo "<pre>"; print_r($dados); exit;
			
			try{   
				$this->insert = $this->MySql->prepare("INSERT INTO protocolo.protocolo_separadores (codtipo, descricao) VALUES (:codtipo,:descricao);");
				
				$this->insert->bindValue(":codtipo", $dados['codtipo'], PDO::PARAM_STR);	
				$this->insert->bindValue(":descricao", $dados['descricao'], PDO::PARAM_STR);	
				
				if( $this->insert->execute() ){
					return true;
				}else{
					return false;
				}
			}catch(PDOExeption $e){
				die($e->getMessage());
			}
		}
		public function formAtualizaSeparador($codseparador, $dados){
			
			//echo "<pre>"; print_r($dados); exit;
			
			try{
				$this->insert = $this->MySql->prepare("UPDATE protocolo.protocolo_separadores SET descricao = :descricao WHERE codseparador = :codseparador;");
				
				$this->insert->bindValue(":descricao", $dados['descricao'], PDO::PARAM_STR);	
				$this->insert->bindValue(":codseparador", $codseparador, PDO::PARAM_STR);	
				
				if( $this->insert->execute() ){
					return true;
				}else{
					return false;
				}
			}catch(PDOExeption $e){
				die($e->getMessage());
			}
		}
		
		public function formNovoCampo($dados){
			
			//echo "<pre>"; print_r($dados); exit;
			
			try{   
				$this->insert = $this->MySql->prepare("INSERT INTO protocolo.protocolo_campos (codtipo, descricao, tipo, obrigatorio, ativo, sequencia, setorpermitido, web, separador,obs, tabela, dataobrigatorio) VALUES (:codtipo,:descricao,:tipo,:obrigatorio,:ativo,:sequencia,:setorpermitido,:web,:separador,:obs,:tabela,:dataobrigatorio);");
				
				$this->insert->bindValue(":codtipo", $dados['codtipo'], PDO::PARAM_STR);	
				$this->insert->bindValue(":descricao", $dados['descricao'], PDO::PARAM_STR);	
				$this->insert->bindValue(":tipo", $dados['tipo'], PDO::PARAM_STR);	
				$this->insert->bindValue(":obrigatorio", $dados['obrigatorio'], PDO::PARAM_STR);	
				$this->insert->bindValue(":sequencia", $dados['sequencia'], PDO::PARAM_STR);	
				$this->insert->bindValue(":ativo", $dados['ativo'], PDO::PARAM_STR);	
				$this->insert->bindValue(":setorpermitido", $dados['setorpermitido'], PDO::PARAM_STR);	
				$this->insert->bindValue(":web", $dados['web'], PDO::PARAM_STR);	
				$this->insert->bindValue(":separador", $dados['separador'], PDO::PARAM_STR);	
				$this->insert->bindValue(":obs", $dados['obs'], PDO::PARAM_STR);	
				$this->insert->bindValue(":tabela", $dados['tabela'], PDO::PARAM_STR);	
				$this->insert->bindValue(":dataobrigatorio", date('Y-m-d H:i:s'), PDO::PARAM_STR);
				
				if( $this->insert->execute() ){
					return true;
				}else{
					return false;
				}
			}catch(PDOExeption $e){
				die($e->getMessage());
			}
		}
		public function formAtualizaCampo($codcampo, $dados){
			
			//echo "<pre>"; print_r($dados); exit;
			
			try{
				$this->insert = $this->MySql->prepare("UPDATE protocolo.protocolo_campos SET 
																	descricao = :descricao, 
																	tipo = :tipo, 
																	tabela = :tabela,
																	obrigatorio = :obrigatorio, 
																	ativo = :ativo, 
																	sequencia = :sequencia,
																	setorpermitido = :setorpermitido,
																	web = :web,
																	separador = :separador,
																	obs = :obs,
																	regra = :regra
																WHERE codcampo = :codcampo;");
				
				$this->insert->bindValue(":descricao", $dados['descricao'], PDO::PARAM_STR);	
				$this->insert->bindValue(":tipo", $dados['tipo'], PDO::PARAM_STR);	
				$this->insert->bindValue(":tabela", $dados['tabela'], PDO::PARAM_STR);	
				$this->insert->bindValue(":obrigatorio", $dados['obrigatorio'], PDO::PARAM_STR);	
				$this->insert->bindValue(":ativo", $dados['ativo'], PDO::PARAM_STR);	
				$this->insert->bindValue(":sequencia", $dados['sequencia'], PDO::PARAM_STR);	
				$this->insert->bindValue(":setorpermitido", $dados['setorpermitido'], PDO::PARAM_STR);	
				$this->insert->bindValue(":web", $dados['web'], PDO::PARAM_STR);	
				$this->insert->bindValue(":separador", $dados['separador'], PDO::PARAM_STR);	
				$this->insert->bindValue(":obs", $dados['obs'], PDO::PARAM_STR);	
				$this->insert->bindValue(":regra", $dados['regra'], PDO::PARAM_STR);	
				$this->insert->bindValue(":codcampo", $codcampo, PDO::PARAM_STR);	
				
				if( $this->insert->execute() ){
					return true;
				}else{
					return false;
				}
			}catch(PDOExeption $e){
				die($e->getMessage());
			}
		}
		
		public function formAtualizaCampoColuna($codcampo,$coluna,$valor){
			
			//echo "<pre>"; print_r($dados); exit;
			
			try{
				$this->insert = $this->MySql->prepare("UPDATE protocolo.protocolo_campos SET 
																	$coluna = :valor
																WHERE codcampo = :codcampo;");
				
				$this->insert->bindValue(":valor", $valor, PDO::PARAM_STR);	
				$this->insert->bindValue(":codcampo", $codcampo, PDO::PARAM_STR);	
				
				if( $this->insert->execute() ){
					return true;
				}else{
					return false;
				}
			}catch(PDOExeption $e){
				die($e->getMessage());
			}
		}
		
		public function frmNovaOpcao($dados){
			
			//echo "<pre>"; print_r($dados); exit;
			
			try{   
				$this->insert = $this->MySql->prepare("INSERT INTO protocolo.protocolo_opcoes (codcampo, descricao, valor, ativo) VALUES (:codcampo,:descricao,:valor,:ativo);");
				
				$this->insert->bindValue(":codcampo", $dados['codcampo'], PDO::PARAM_STR);	
				$this->insert->bindValue(":descricao", $dados['descricao'], PDO::PARAM_STR);	
				$this->insert->bindValue(":valor", $dados['valor'], PDO::PARAM_STR);	
				$this->insert->bindValue(":ativo", $dados['ativo'], PDO::PARAM_STR);	
				
				if( $this->insert->execute() ){
					return true;
				}else{
					return false;
				}
			}catch(PDOExeption $e){
				die($e->getMessage());
			}
		}
		public function frmAtualizaOpcao($codopcao, $dados){
			
			//echo "<pre>"; print_r($dados); exit;
			
			try{
				$this->insert = $this->MySql->prepare("UPDATE protocolo.protocolo_opcoes SET descricao = :descricao, valor = :valor, ativo = :ativo WHERE codopcao = :codopcao;");
				
				$this->insert->bindValue(":descricao", $dados['descricao'], PDO::PARAM_STR);	
				$this->insert->bindValue(":valor", $dados['valor'], PDO::PARAM_STR);	
				$this->insert->bindValue(":ativo", $dados['ativo'], PDO::PARAM_STR);	
				$this->insert->bindValue(":codopcao", $codopcao, PDO::PARAM_STR);	
				
				if( $this->insert->execute() ){
					return true;
				}else{
					return false;
				}
			}catch(PDOExeption $e){
				die($e->getMessage());
			}
		}
		public function criaInstrucao($dados){
			
			//echo "<pre>"; print_r($dados); exit;
			
			$this->insert = $this->MySql->prepare("INSERT INTO protocolo.protocolo_instrucoes (descricao, codtipo, caminho, nome, tipo, datcadastro) VALUES (:descricao, :codtipo, :caminho, :nome, :tipo, :datcadastro);");
			$this->insert->bindValue(":descricao", $dados["descricao"], PDO::PARAM_STR);	
			$this->insert->bindValue(":codtipo", $dados["codtipo"], PDO::PARAM_STR);	
			$this->insert->bindValue(":caminho", $dados["caminho"], PDO::PARAM_STR);	
			$this->insert->bindValue(":nome", $dados["nome"], PDO::PARAM_STR);	
			$this->insert->bindValue(":tipo", $dados["tipo"], PDO::PARAM_STR);	
			$this->insert->bindValue(":datcadastro", date('Y-m-d H:i:s'), PDO::PARAM_STR);	
			  
			if( $this->insert->execute() ){
				return true;
			}else{
				return false;
			}
		}
		public function excluiInstrucao($codinstrucao){
			if($this->MySql->query("DELETE v.* FROM protocolo.protocolo_instrucoes v WHERE v.codinstrucao = '$codinstrucao';")){
				return true;
			}else{
				return false;
			}
		}
		public function webInstrucao($codinstrucao,$value){
				
			$value = ($value == 'S' ? 'N' : 'S' );
			
			if($this->MySql->query("UPDATE protocolo.protocolo_instrucoes SET web = '$value' WHERE codinstrucao = '$codinstrucao';")){
				return true;
			}else{
				return false;
			}
		}
		public function retornaInstrucoesDoTipo($codtipo){
				
			$_query = "select i.*, t.tipo as tipoProtocolo 
							from protocolo.protocolo_instrucoes i 
								inner join protocolo.protocolo_tipos t on t.codtipo = i.codtipo
									where i.codtipo = '$codtipo' order by i.tipo asc;";
		
			$p_sql = $this->MySql->prepare($_query);
			
			$options=array();
			
			if($p_sql->execute()) {
				
				while($show = $p_sql->fetch(PDO::FETCH_ASSOC)) {
					$options[] = $show;
				}
			}
			
			return($options);
		}
		public function validaDadosCompObrigatorios($codprotocolo,$codtipo,$datacriacao){
			
			$id_meu_setor = $_SESSION['protocolo']['myIdSetor'];
			 
			$_query = "select c.codcampo, c.descricao, v.valor
							from protocolo.protocolo_campos c
							  left join protocolo.protocolo_valores v on v.codcampo = c.codcampo and v.codprotocolo = '$codprotocolo'
								where c.codtipo = '$codtipo' 
										and c.ativo = 'S' 
										and c.obrigatorio = 'S'
										and ( c.dataobrigatorio is null OR c.dataobrigatorio < '$datacriacao')
										and (c.setorpermitido = '$id_meu_setor' OR c.setorpermitido = '0');";
			
			//echo $_query;
			
			$p_sql = $this->MySql->prepare($_query);
			
			$campos = array();
			
			if($p_sql->execute()) {
				
				while($show = $p_sql->fetch(PDO::FETCH_ASSOC)) {
					if( empty($show['valor']) ){
						$campos[$show['codcampo']] = $show;
					}
				}
			}
			return $campos;
		}
//================================================================ CLASSIFICACAO ====================================================================
		public function formNovaClassificacao($dados){
			
			//echo "<pre>"; print_r($dados); exit;
			
			try{
				$this->insert = $this->MySql->prepare("INSERT INTO protocolo.classificacao (codsetor,descricao, codcor) VALUES (:codsetor,:descricao,:codcor);");
				
				$this->insert->bindValue(":codsetor", $_SESSION['protocolo']['myIdSetor']);
				$this->insert->bindValue(":descricao", $dados['descricao'], PDO::PARAM_STR);	
				$this->insert->bindValue(":codcor", $dados['codcor'], PDO::PARAM_STR);	
				
				if( $this->insert->execute() ){
					return true;
				}else{
					return false;
				}
			}catch(PDOExeption $e){
				die($e->getMessage());
			}
		}
		public function formAtualizaClassificacao($codclassificacao, $dados){
			
			//echo "<pre>"; print_r($dados); exit;
			
			try{
				$this->insert = $this->MySql->prepare("UPDATE protocolo.classificacao SET 
																descricao = :descricao, 
																codcor = :codcor
																	WHERE codclassificacao = :codclassificacao;");
				
				$this->insert->bindValue(":descricao", $dados['descricao'], PDO::PARAM_STR);	
				$this->insert->bindValue(":codcor", $dados['codcor'], PDO::PARAM_STR);	
				$this->insert->bindValue(":codclassificacao", $codclassificacao, PDO::PARAM_STR);
				
				if( $this->insert->execute() ){
					return true;
				}else{
					return false;
				}
			}catch(PDOExeption $e){
				die($e->getMessage());
			}
		}
		public function retornaClassificacaoDoSetor(){
			
			$_query = "SELECT pc.*, s.*, cc.codigo as cor_codigo FROM 
						protocolo.classificacao pc
						inner join cadastro.cad_setor s on s.cse_id = pc.codsetor
						left join protocolo.classificacao_cores cc on cc.codcor = pc.codcor
							where pc.codsetor = :myIdSetor";
			
			try {
				$this->search = $this->MySql->prepare($_query);
				
				$this->search->bindValue(":myIdSetor", $_SESSION['protocolo']['myIdSetor']);
				
				if( $this->search->execute() ){
					$array=array();
					while($show = $this->search->fetch(PDO::FETCH_ASSOC)) {
						$array[] = $show;
					}
					return $array;
				}
            } catch(PDOExeption $e) {
                    die($e->getMessage());
            }
			
		}
		public function retornaCoresClassificacao(){
			
			$_query = "SELECT * FROM protocolo.classificacao_cores cc";
			
			try {
				$this->search = $this->MySql->prepare($_query);
				
				if( $this->search->execute() ){
					$array=array();
					while($show = $this->search->fetch(PDO::FETCH_ASSOC)) {
						$array[] = $show;
					}
					return $array;
				}
            } catch(PDOExeption $e) {
                    die($e->getMessage());
            }
			
		}
		public function retornaCoresClassificacaoDisponiveis(){
			
			$_query = "SELECT * FROM protocolo.classificacao_cores cc
							WHERE cc.codcor not in( select c.codcor from protocolo.classificacao c where c.codsetor = :myIdSetor );";
			
			try {
				$this->search = $this->MySql->prepare($_query);
				
				$this->search->bindValue(":myIdSetor", $_SESSION['protocolo']['myIdSetor']);
				
				if( $this->search->execute() ){
					$array=array();
					while($show = $this->search->fetch(PDO::FETCH_ASSOC)) {
						$array[] = $show;
					}
					return $array;
				}
            } catch(PDOExeption $e) {
                    die($e->getMessage());
            }
			
		}
		public function retornaClassificacaoDoProtocolo($codprotocolo){
			
			$_query = "SELECT pc.*, 
							  date_format(pc.datcadastro,'%d/%m/%Y %H:%i') as datcadastro_desc, 
							  u.sus_nome as usuario,
							  c.descricao, 
							  cc.codigo as cor_codigo
						FROM protocolo.protocolo_classificacao pc
						LEFT JOIN protocolo.classificacao c ON c.codclassificacao = pc.codclassificacao
						LEFT JOIN protocolo.classificacao_cores cc ON cc.codcor = c.codcor
						LEFT JOIN seguranca.seg_usuario u ON u.sus_id = pc.codusuario
							where pc.codsetor = :myIdSetor and pc.codprotocolo = :codprotocolo
								order by codproclassificacao desc limit 1";
			
			try {
				$this->search = $this->MySql->prepare($_query);
				
				$this->search->bindValue(":myIdSetor", $_SESSION['protocolo']['myIdSetor']);
				$this->search->bindValue(":codprotocolo", $codprotocolo);
				
				if( $this->search->execute() ){
					if($show = $this->search->fetch(PDO::FETCH_ASSOC)) {
						return $show;
					}else{
						return NULL;
					}
				}
            } catch(PDOExeption $e) {
                    die($e->getMessage());
            }
			
		}
		public function atualizaClassificacaoProtocolo($dados){
			
			//echo "<pre>"; print_r($dados); exit;
			
			/*deleta a classificacao deste protocolo para este setor*/
			$this->MySql->query("DELETE FROM protocolo.protocolo_classificacao WHERE codprotocolo = '". $dados['codprotocolo'] ."' AND codsetor = '". $_SESSION['protocolo']['myIdSetor'] ."' ;");
			
			/*depois insere um novo registro escolhido*/
			try{
				$this->insert = $this->MySql->prepare("INSERT INTO protocolo.protocolo_classificacao (
																									  datcadastro,
																									  codusuario,
																									  codprotocolo,
																									  codsetor,
																									  codclassificacao
																									) VALUES (
																									  :datcadastro,
																									  :codusuario,
																									  :codprotocolo,
																									  :codsetor,
																									  :codclassificacao
																									);");
				
				$this->insert->bindValue(":datcadastro", (date('Y-m-d H:i:s')));
				$this->insert->bindValue(":codusuario", $_SESSION['protocolo']['myId'], PDO::PARAM_STR);	
				$this->insert->bindValue(":codprotocolo", $dados['codprotocolo'], PDO::PARAM_STR);	
				$this->insert->bindValue(":codsetor", $_SESSION['protocolo']['myIdSetor'], PDO::PARAM_STR);	
				$this->insert->bindValue(":codclassificacao", $dados['codclassificacao'], PDO::PARAM_STR);	
				
				if( $this->insert->execute() ){
					return true;
				}else{
					return false;
				}
			}catch(PDOExeption $e){
				die($e->getMessage());
			}
		}
		public function estatisticaClassificacao(){
			
			/*$_query = "select c.descricao, 
						cc.codigo, 
						(select count(*) from protocolo.protocolo_classificacao g 
							inner join protocolo.protocolos p on p.codprotocolo = g.codprotocolo and p.status = 'P' and p.codsetoratual = :myIdSetor
								where g.codclassificacao = c.codclassificacao
							) as qtde
							
							from protocolo.classificacao c 
								inner join protocolo.classificacao_cores cc on cc.codcor = c.codcor
									where c.codsetor = :myIdSetor
										order by c.codclassificacao desc;";*/
										
			$_query = "select c.codclassificacao, 
							  c.descricao, 
							  cc.codigo, 
							  (select count(*) from protocolo.protocolo_classificacao g 
								inner join protocolo.protocolos p on p.codprotocolo = g.codprotocolo and p.status = 'P' and p.codsetoratual = :myIdSetor
									where g.codclassificacao = c.codclassificacao
							   ) as qtde
							
							from protocolo.classificacao c 
								inner join protocolo.classificacao_cores cc on cc.codcor = c.codcor
									where c.codsetor = :myIdSetor
									
						union all
         
						select '0' as codclassificacao, 'Sem Classificação' as descricao, '#000000' as codigo, sum(y.qtde) as qtde 
							from (
								select count(*) as qtde from protocolo.protocolo_classificacao g 
									inner join protocolo.protocolos p on p.codprotocolo = g.codprotocolo and p.status = 'P' and p.codsetoratual = :myIdSetor
										where g.codsetor = :myIdSetor and g.codclassificacao = 0

							union all			

								select count(*) as qtde from protocolo.protocolos p
									where p.status = 'P' and p.codsetoratual = :myIdSetor and p.codprotocolo not in (select g.codprotocolo from protocolo.protocolo_classificacao g where g.codprotocolo and g.codsetor = :myIdSetor)
							) as y
									;";
			
			try {
				$this->search = $this->MySql->prepare($_query);
				
				$this->search->bindValue(":myIdSetor", $_SESSION['protocolo']['myIdSetor']);
				
				if( $this->search->execute() ){
					$array=array();
					while($show = $this->search->fetch(PDO::FETCH_ASSOC)) {
						$array[] = $show;
					}
					return $array;
				}
            } catch(PDOExeption $e) {
                    die($e->getMessage());
            }
		}
		
		public function visualizaClassificacao($codclassificacao){
			
			if( $codclassificacao == 0 ){
				
				$_query = "select * 
								from (
									select p.*, pt.tipo, DATE_FORMAT(pe.datacriacao,'%d/%m/%Y %H:%i:%s') as datacriacao
										from protocolo.protocolo_classificacao g 
											inner join protocolo.protocolos p on p.codprotocolo = g.codprotocolo and p.status = 'P' and p.codsetoratual = :myIdSetor
											inner join protocolo.protocolo_tipos pt on pt.codtipo = p.codtipo
											inner join protocolo.protocolo_eventos pe on pe.codprotocolo = p.codprotocolo and pe.evento = 'cadastrado'
										where g.codsetor = :myIdSetor and g.codclassificacao = :codclassificacao

								union all			

									select p.*, pt.tipo , DATE_FORMAT(pe.datacriacao,'%d/%m/%Y %H:%i:%s') as datacriacao
										from protocolo.protocolos p
											inner join protocolo.protocolo_tipos pt on pt.codtipo = p.codtipo
											inner join protocolo.protocolo_eventos pe on pe.codprotocolo = p.codprotocolo and pe.evento = 'cadastrado'
										where p.status = 'P' and p.codsetoratual = :myIdSetor and p.codprotocolo not in (select g.codprotocolo from protocolo.protocolo_classificacao g where g.codprotocolo and g.codsetor = :myIdSetor)
								) as y;";
			}else{
				$_query = "select p.*, pt.tipo, DATE_FORMAT(pe.datacriacao,'%d/%m/%Y %H:%i:%s') as datacriacao
							from protocolo.protocolos p 
								inner join protocolo.protocolo_tipos pt on pt.codtipo = p.codtipo
								inner join protocolo.protocolo_eventos pe on pe.codprotocolo = p.codprotocolo and pe.evento = 'cadastrado'
								inner join protocolo.protocolo_classificacao c on c.codprotocolo = p.codprotocolo and c.codsetor = :myIdSetor and c.codclassificacao = :codclassificacao
								inner join protocolo.classificacao cc on cc.codclassificacao = c.codclassificacao and cc.codsetor = :myIdSetor
							where p.status = 'P' and p.codsetoratual = :myIdSetor;";
			}
			
			try {
				$this->search = $this->MySql->prepare($_query);
				
				$this->search->bindValue(":myIdSetor", $_SESSION['protocolo']['myIdSetor']);
				$this->search->bindValue(":codclassificacao", $codclassificacao);
				
				if( $this->search->execute() ){
					$array=array();
					while($show = $this->search->fetch(PDO::FETCH_ASSOC)) {
						$array[] = $show;
					}
					return $array;
				}
            } catch(PDOExeption $e) {
                    die($e->getMessage());
            }
		}

//================================================================ FUNCOES ====================================================================
		public function retornaAquivos($codprotocolo){
				
			$_query = "select 
						a.* ,
						date_format(a.datacriacao,'%d/%m/%Y %H:%i:%s') as desc_datacriacao,
						case a.tipo
							when 'I' then 'Anexado Via Protocolos'
							when 'E' then 'Anexado Via Portal Cliente'
							else a.tipo end as desc_tipo,
						if(a.codusucadastro is null,'Beneficiário',concat(u.sus_nome,' - ',u.sus_setor)) as usuario
					from protocolo.protocolo_anexos a
						left join seguranca.seg_usuario u on u.sus_id = a.codusucadastro
						where a.codprotocolo = '$codprotocolo' and a.status = 'A'
							order by a.codproanexo desc;";
		
			$p_sql = $this->MySql->prepare($_query);
			
			$options=array();
			
			if($p_sql->execute()) {
				
				while($show = $p_sql->fetch(PDO::FETCH_ASSOC)) {
					$options[] = $show;
				}
			}
			return($options);
		}
		public function retornaOpcoesDoCampo($codcampo){
				
			$_query = "select * from protocolo.protocolo_opcoes o where o.codcampo = '$codcampo' order by o.descricao asc;";
		
			$p_sql = $this->MySql->prepare($_query);
			
			$options=array();
			
			if($p_sql->execute()) {
				
				while($show = $p_sql->fetch(PDO::FETCH_ASSOC)) {
					$options[] = $show;
				}
			}
			
			return($options);
		}
		public function retornaOpcoesDoCampoPorTabela($tabela){
			 
			$_query = "select * from protocolo.$tabela order by descricao asc;";
			
			$p_sql = $this->MySql->prepare($_query);
			
			$options=array();
			
			if($p_sql->execute()) {
				
				while($show = $p_sql->fetch(PDO::FETCH_ASSOC)) {
					$options[] = $show;
				}
			}
			
			return($options);
			
		}
		public function retornaValorDoCampo($codprotocolo,$codcampo){
				
			$_query = "select v.valor from protocolo.protocolo_valores v where v.codprotocolo = '$codprotocolo' and v.codcampo = '$codcampo';";
		
			$p_sql = $this->MySql->prepare($_query);
			
			if( $p_sql->execute() ) {
				
				if($show = $p_sql->fetch(PDO::FETCH_ASSOC)) {
					return $show['valor'];
				}else{
					return '';
				}
			}
		}
		
		public function retornaProtocolosPendentesOuvidoria($carteirinha){
				
			$_query = "
					select  p.numprotocolo,
							p.status,
							(select count(1) from protocolo.protocolo_valores v where v.codcampo = '272' and v.valor = p.numprotocolo) as pendente
						from protocolo.protocolos p 
							where p.cpfremetente = '$carteirinha' and p.status = 'P' and p.codsetoratual != '32';
						";
			
			$p_sql = $this->MySql->prepare($_query);
			
			if( $p_sql->execute() ) {
				
				$arr=array();
				while($show = $p_sql->fetch(PDO::FETCH_ASSOC)) {
					$arr[] = $show;
				}
				return $arr;
			}
		}
		
		public function insereValores($codprotocolo, $valores){
			
			//echo "<pre>"; print_r($valores); exit;
			
			/*deleta todos os campos que nao sao do tipo arquivo*/
			$this->MySql->query("DELETE v.* FROM protocolo.protocolo_valores v 
									INNER JOIN protocolo.protocolo_campos c ON c.codcampo = v.codcampo and c.tipo != 'A'
											WHERE v.codprotocolo = '$codprotocolo';");
			
			$erro = true;
			
			if(!empty($valores)){
				
				foreach($valores as $codcampo => $valor){
					
					try{   
						$this->insert = $this->MySql->prepare("INSERT INTO protocolo.protocolo_valores (codprotocolo, codcampo, valor) VALUES (:codprotocolo,:codcampo,:valor);");
						$this->insert->bindValue(":codprotocolo", $codprotocolo, PDO::PARAM_STR);	
						$this->insert->bindValue(":codcampo", $codcampo, PDO::PARAM_STR);	
						$this->insert->bindValue(":valor", $valor, PDO::PARAM_STR);	
						
						if( !$this->insert->execute() ){
							$erro = false;
						}
					}catch(PDOExeption $e){
						die($e->getMessage());
					}
				}
			}
			
			return $erro;
		}
		public function insereValoresArquivos($codprotocolo, $arquivos){
			
			$caminho = "../view/pgs/protocolos/uploads/";
			
			$erro = true;
			
			if(!empty($arquivos)){
				
				foreach($arquivos['name'] as $codcampo => $nomearq){
					
					$temp = $arquivos['tmp_name'][$codcampo];
					
					try{
						$nomeAleatorio = md5(uniqid(time())) . strrchr($nomearq, ".");
						
						if ( !move_uploaded_file($temp, $caminho . $nomeAleatorio) ){
							$arr['msg'] = 'Não foi possível anexar o arquivo';
						}else{
							
							/*deleta os valores do tipo arquivo do campo a ser inserido*/
							$this->MySql->query("DELETE v.* FROM protocolo.protocolo_valores v WHERE v.codprotocolo = '$codprotocolo' and v.codcampo = '$codcampo';");
							
							$this->insert = $this->MySql->prepare("INSERT INTO protocolo.protocolo_valores (codprotocolo, codcampo, valor) VALUES (:codprotocolo,:codcampo,:valor);");
							
							$this->insert->bindValue(":codprotocolo", $codprotocolo, PDO::PARAM_STR);	
							$this->insert->bindValue(":codcampo", $codcampo, PDO::PARAM_STR);	
							$this->insert->bindValue(":valor", $nomeAleatorio, PDO::PARAM_STR);	
							
							if( !$this->insert->execute() ){
								$erro = false;
							}
						}
					}catch(PDOExeption $e){
						die($e->getMessage());
					}
				}
			}
			
			return $erro;
		}
		//		=================== CLIENTE ==================
		public function insereAtualizaDadosCliente($dados){
			
			$cpfremetente 	= $dados["cpfremetente"];
			$nomeremetente 	= $dados["nomeremetente"];
			$telefone 		= $dados["telefone"];
			$email 			= $dados["email"];
			
			$p_sql = $this->MySql->prepare("SELECT * FROM protocolo.protocolo_clientes c WHERE c.cpfremetente = '$cpfremetente';");
			
			if( $p_sql->execute() ) {
				
				if($show = $p_sql->fetch(PDO::FETCH_ASSOC)) {
					$this->insert = $this->MySql->prepare("UPDATE protocolo.protocolo_clientes SET nomeremetente = :nomeremetente, telefone = :telefone, email = :email WHERE cpfremetente = :cpfremetente;");
				}else{
					$this->insert = $this->MySql->prepare("INSERT INTO protocolo.protocolo_clientes (cpfremetente, nomeremetente, telefone, email) VALUES (:cpfremetente, :nomeremetente, :telefone, :email);");
				}
				
				$this->insert->bindValue(":cpfremetente", $cpfremetente, PDO::PARAM_STR);	
				$this->insert->bindValue(":nomeremetente", $nomeremetente, PDO::PARAM_STR);	
				$this->insert->bindValue(":telefone", $telefone, PDO::PARAM_STR);	
				$this->insert->bindValue(":email", $email, PDO::PARAM_STR);	
				if( $this->insert->execute() ){
					return true;
				}else{
					return false;
				}
			}
		}
		public function retornaClienteCadastrado($cpfremetente){
				
			$_query = "select * from protocolo.protocolo_clientes c where c.cpfremetente = '$cpfremetente';";
			
			$p_sql = $this->MySql->prepare($_query);
			
			if( $p_sql->execute() ) {
				
				$array = array();
				
				if($show = $p_sql->fetch(PDO::FETCH_ASSOC)) {
					
					$array = array(
					   "CODCLIENTE"	=> $show['codcliente'],
					   "NOME"		=> $show['nomeremetente'],
					   "TELEFONE"	=> $show['telefone'],
					   "EMAIL"		=> $show['email']
					);
					
				}
				return $array;
			}
		}
		public function retornaClienteCadastradoCodigo($codcliente){
				
			$_query = "select * from protocolo.protocolo_clientes c where c.codcliente = '$codcliente';";
			
			$p_sql = $this->MySql->prepare($_query);
			
			if( $p_sql->execute() ) {
				
				$array = array();
				
				if($show = $p_sql->fetch(PDO::FETCH_ASSOC)) {
					
					$array = array(
					   "CODCLIENTE"	=> $show['codcliente'],
					   "NOME"		=> $show['nomeremetente'],
					   "TELEFONE"	=> $show['telefone'],
					   "EMAIL"		=> $show['email']
					);
					
				}
				return $array;
			}
		}
		public function retornaProtocolosPendentesDoSetor($dia, $cse_id){
			
			$CONDICAO = ( $dia < 0 ? " = $dia" : ">= $dia" );
			
			$_query = " select 
							p.numprotocolo,
							p.prazo, 
							DATE_FORMAT(p.datacriacao,'%d/%m/%Y') as dt_cadastro, 
							DATE_FORMAT(DATE_ADD(p.datacriacao,INTERVAL p.prazo DAY),'%d/%m/%Y') as dt_vencimento, 
							dayofweek(DATE_ADD(p.datacriacao,INTERVAL p.prazo DAY)) as dt_vencimento_semana,
							TIMESTAMPDIFF(DAY,DATE_FORMAT(DATE_ADD(p.datacriacao,INTERVAL p.prazo DAY),'%Y-%m-%d %H:%i:%s'),current_date()) as dias_vencimento,
							p.cse_setor_descricao,
							p.tipo
						from (

						-- protocolos a receber
						select pt.prazo, pe.datacriacao, p.*, cd.cse_setor_descricao, pt.tipo
							from protocolo.protocolos p 
							inner join protocolo.protocolo_eventos pe on pe.codprotocolo = p.codprotocolo and pe.evento = 'cadastrado'
							inner join protocolo.protocolo_despachos pd on pd.codprodespacho = p.coddespachoatual and pd.codsetdestino = '$cse_id' and pd.status = 'D'
							inner join cadastro.cad_setor cd on cd.cse_id = pd.codsetdestino
							inner join protocolo.protocolo_tipos pt on pt.codtipo = p.codtipo
							where p.status = 'P'

						union all

						-- protocolos recebidos sem despachos
						select pt.prazo, pe.datacriacao, p.*, cd.cse_setor_descricao, pt.tipo
							from protocolo.protocolos p 
								inner join protocolo.protocolo_eventos pe on pe.codprotocolo = p.codprotocolo and pe.evento = 'cadastrado'
								inner join cadastro.cad_setor cd on cd.cse_id = p.codsetoratual
								inner join protocolo.protocolo_tipos pt on pt.codtipo = p.codtipo
							where p.status = 'P' and p.codsetoratual = '$cse_id' and p.coddespachoatual is null
								
						union all

						-- protocolos recebidos com despachos	
						select pt.prazo, pe.datacriacao, p.*, cd.cse_setor_descricao, pt.tipo
							from protocolo.protocolos p 
								inner join protocolo.protocolo_eventos pe on pe.codprotocolo = p.codprotocolo and pe.evento = 'cadastrado'
								inner join protocolo.protocolo_despachos pd on pd.codprodespacho = p.coddespachoatual and pd.status != 'D'
								inner join cadastro.cad_setor cd on cd.cse_id = p.codsetoratual
								inner join protocolo.protocolo_tipos pt on pt.codtipo = p.codtipo
							where p.status = 'P' and p.codsetoratual = '$cse_id'
							   
						) p WHERE 
								TIMESTAMPDIFF(DAY,DATE_FORMAT(DATE_ADD(p.datacriacao,INTERVAL p.prazo DAY),'%Y-%m-%d %H:%i:%s'),current_date()) $CONDICAO;";
			
			$p_sql = $this->MySql->prepare($_query);
			
			$array = array();
			
			if( $p_sql->execute() ) {
				while($show = $p_sql->fetch(PDO::FETCH_ASSOC)) {
					$array[] = $show;
				}
			}
			return $array;
		}
		public function pesquisa_tbl_autocomplete($termo,$tabela){
			
			$_query = "select o.codigo as id, CONCAT(o.codigo,CONCAT(' - ',CONCAT(o.descricao))) as name 
								from protocolo.$tabela o 
									where (o.descricao LIKE '%$termo%' OR o.codigo LIKE '%$termo%') ORDER BY o.descricao ASC LIMIT 10;";
			
			$p_sql = $this->MySql->prepare($_query);
			
			$array=array();
			
			if($p_sql->execute()) {
				
				while($show = $p_sql->fetch(PDO::FETCH_ASSOC)) {
					$array[] = $show;
				}
			}
			
			return($array);
		}
		
		public function retornaTabelaPesquisa(){
			
			$_query = "SELECT table_name 
							FROM information_schema.tables 
								WHERE table_type in('base table','view') 
									AND table_schema = 'protocolo' 
									AND table_name like '%tbl_%';";
			
			$p_sql = $this->MySql->prepare($_query);
			
			$array=array();
			
			if($p_sql->execute()) {
				
				while($show = $p_sql->fetch(PDO::FETCH_ASSOC)) {
					$array[] = $show;
				}
			}
			
			return($array);
		}
		
		public function retorna_descricao_pelo_codigo($codigo,$tabela){
			
			$_query = "select o.descricao
							from protocolo.$tabela o 
								where o.codigo = '$codigo';";
			
			$p_sql = $this->MySql->prepare($_query);
			
			if($p_sql->execute()) {
				
				if($show = $p_sql->fetch(PDO::FETCH_ASSOC)) {
					return $show['descricao'];
				}else{
					return '';
				}
			}
		}
		public function retorna_descricao_pelo_valor($valor,$tabela){
			
			$_query = "select o.descricao
							from protocolo.$tabela o 
								where o.valor = '$valor';";
			
			$p_sql = $this->MySql->prepare($_query);
			
			if($p_sql->execute()) {
				
				if($show = $p_sql->fetch(PDO::FETCH_ASSOC)) {
					return $show['descricao'];
				}else{
					return '';
				}
			}
		}
		public function retornaProtocolosParaInativacao(){
				   
			$_query = "select 	s.codprotocolo, 
								s.numprotocolo, 
								date_format(e.datacriacao,'%d/%m/%Y %H:%i:%s') as datacriacao, 
								date_format(DATE_ADD(e.datacriacao, INTERVAL 120 HOUR),'%d/%m/%Y %H:%i:%s') as datacriacao_mais_24h, 
								date_format(sysdate(),'%d/%m/%Y %H:%i:%s') as datacriacao_limite
									from protocolo.protocolos s 
										inner join protocolo.protocolo_eventos e on e.codprotocolo = s.codprotocolo and e.evento = 'cadastrado'
									where s.canatendimento = 'W'
										and s.clientepodeeditar = 'S'
										and s.status = 'P'
										and DATE_ADD(e.datacriacao, INTERVAL 120 HOUR) < sysdate();";
		
			$p_sql = $this->MySql->prepare($_query);
			
			$array=array();
			
			if($p_sql->execute()) {
				
				while($show = $p_sql->fetch(PDO::FETCH_ASSOC)) {
					$array[] = $show;
				}
			}
			
			return($array);
		}
		
		public function getLinhaTempo($id_protocolo){
            
            $Query = "select * from
						(

							select  e.setorusucadastro as setor, 
									e.datacriacao as data, 
									e.evento 
										from protocolo.protocolo_eventos e 
											where e.codprotocolo = :codprotocolo and e.evento = 'cadastrado'

							union all

							select s.cse_setor_sigla as setor, 
								   d.dataenviou as data, 
								   'despacho' as evento 
									from protocolo.protocolo_despachos d 
										inner join cadastro.cad_setor s on s.cse_id = d.codsetdestino 
									where d.codprotocolo = :codprotocolo

							union all

							select  e.setorusucadastro as setor, 
									e.datacriacao, 
									e.evento as data 
										from protocolo.protocolo_eventos e 
											where e.codprotocolo = :codprotocolo and e.evento = 'finalizado'

						) a order by a.data asc;";
            
            try {
                    $this->search = $this->MySql->prepare($Query);
                    $this->search->bindValue(":codprotocolo", $id_protocolo);
                    $this->search->execute();
            } catch(PDOExeption $e) {
                    die($e->getMessage());
            }
			
			$arr=array();
			
            while($show = $this->search->fetch(PDO::FETCH_ASSOC)){
				$arr[] = $show;
            }
			return $arr;
        }
		
		public function preparaDadosLinhaTempo($array){
			
			$formatado=array();

			foreach( $array as $k => $v ){
				
				if( $v['evento'] == 'finalizado' ){
					continue;
				}
				
				$formatado[$k]["setor"]   = $v['setor'];
				$formatado[$k]["evento"] = $v['evento'];
				$formatado[$k]["dt_ini"]  = $v['data'];
				
				if( !empty($array[$k+1]['data']) ){
					$formatado[$k]["dt_fim"] = $array[$k+1]['data'];
				}else{
					$formatado[$k]["dt_fim"] = date('Y-m-d H:i:s');
				}
			}
			
			return $formatado;
		}
		
		public function validaRegraCampo($campo,$valCampo){
			
			if( !empty($valCampo) ){
				
				include_once $_SERVER['DOCUMENT_ROOT'] . '/protocolos/controller/regrascampos.php';
				
				$regrascampos = new regrascampos();
				
				if( method_exists($regrascampos, $campo['regra']) ){ //se tiver a funcao no arquivo
					
					$retorno = $regrascampos->$campo['regra']($campo,$valCampo);
					
					return $retorno;
				}
			}
		}
		
		public function validaCampoObrigatorio($campo,$valCampo){
			
			if($_SERVER['REMOTE_ADDR'] == '10.11.20.162'){
				//echo "<pre>"; print_r($campo); exit; 
			}
			
			if( $campo['obrigatorio'] == 'S' && empty($valCampo) ){
				return "O campo: " . $campo['descricao'] . ' é obrigatório! Pode ser necessário primeiro Atualizar as Informações';
			}
		}
		
		//  ==============================AGENDAMENTO===============
		
		public function retornaDiasAgendamento(){
			
			$config = $this->retornaConfigAgendamento();
			
			$dateStart = new DateTime(date("Y-m-d"));
			$dateEnd = new DateTime(date("Y-m-d"));
			
			//habilita as datas a partir da data inicial do projeto
			if( strtotime($config['dtinicioprojeto']) > strtotime(date("Y-m-d")) ){
				$dateStart = new DateTime($config['dtinicioprojeto']);
				$dateEnd = new DateTime($config['dtinicioprojeto']);
			}
			
			//adiciona dias na data final
			$dateEnd = $dateEnd->modify('+2month');
			
			//caso tenha uma data final do projeto
			if( strtotime($config['dtfimprojeto']) < $dateEnd->getTimestamp() ){
				$dateEnd = new DateTime($config['dtfimprojeto']);
			}
			
			$dateRange = array();
			
			while($dateStart <= $dateEnd){
				
				//somente segunda a sexta
				if( $dateStart->format('w') != 0 && $dateStart->format('w') != 6 ){
					
					//somente os nao feriados
					if( !$this->validaSeFeriado($dateStart->format('Y-m-d')) ){
						
						//somente os dias com vagas
						if( $this->validaVagasDoDia($dateStart->format('d/m/Y')) ){
							$dateRange[] = $dateStart->format('d/m/Y');
						}
					}
				}
				
				$dateStart = $dateStart->modify('+1day');
			}
			
			return $dateRange;
		}
		
		public function retornaConfigAgendamento(){
			
			$_query = "select * from protocolo.agendamento_config;";
		
			$p_sql = $this->MySql->prepare($_query);
			
			if($p_sql->execute()) {
				
				if($show = $p_sql->fetch(PDO::FETCH_ASSOC)) {
					return $show;
				}else{
					return null;
				}
			}
		}
		
		public function retornaQtdeAtendimentoAtual(){
			
			$config = $this->retornaConfigAgendamento();
			
			$ts  = strtotime($config['horainicial']);
			$tsf = strtotime($config['horafinal']);
			
			$periodos=0;
			while ($ts < $tsf) {
				
				$periodos++;
				
				$horinicial = date('H:i:s',$ts);
				
				$ts += $config['intervalo']*60;
			}
			
			$totalAtendimentos = $periodos * $config['qtdeperiodo'];
			
			return $totalAtendimentos;
		}
		
		public function validaVagasDoDia($data){
			
			$_query = "select count(*) as qtde from protocolo.atendimento a 
						where a.tipo = 'A'
								and date_format(a.datagendamento,'%d/%m/%Y') = :data
								and a.status != 'R';";
			
			$p_sql = $this->MySql->prepare($_query);
			
			$p_sql->bindValue(':data', $data);
			
			if($p_sql->execute()) {
				
				if($show = $p_sql->fetch(PDO::FETCH_ASSOC)) {
					
					$qtdeAtendimentoAtual = $this->retornaQtdeAtendimentoAtual();
					
					if( $show['qtde'] < $qtdeAtendimentoAtual ){
						return true;
					}else{
						return false;
					}
				}else{
					return false;
				}
			}
		}
		public function CalculaQuantidadeDeVagas($data){
			
			//echo "<pre>"; print_r($data); exit;
			
			$data_formatada = new DateTime($data);
			
			$config = $this->retornaConfigAgendamento();
			
			$_query = "select count(*) as qtde from protocolo.atendimento a 
						where a.tipo = 'A'
								and a.status != 'R'
								and a.datagendamento = :data;";
			
			$p_sql = $this->MySql->prepare($_query);
			
			$p_sql->bindValue(':data', $data);
			
			if($p_sql->execute()) {
				
				if($show = $p_sql->fetch(PDO::FETCH_ASSOC)) {
					
					//se a hora for de almoco, a qtde por periodo é menor
					if( $data_formatada->format('H:i:s') >= $config['horainicial_almoco'] && $data_formatada->format('H:i:s') <= $config['horafinal_almoco'] ){
						$_quantidade = $config['qtdeperiodo_almoco'] - $show['qtde'];
					}else{
						$_quantidade = $config['qtdeperiodo'] - $show['qtde'];
					}
					
					return ( $_quantidade <= 0 ? 0 : $_quantidade );
				}else{
					return 0;
				}
			}
		}
		public function validaClienteAgendado($dados){
			
			$codcliente = $dados['codcliente'];
			$data 		= $dados['data'];
			
			//echo "<pre>"; print_r($dados); exit;
			
			$_query = "select * from protocolo.atendimento a
						where a.tipo = 'A'
								and a.codcliente = :codcliente
								and a.status in('L')
								and date(a.datagendamento) >= curdate();";
			
			$p_sql = $this->MySql->prepare($_query);
			
			//$p_sql->bindValue(':data', $data);
			$p_sql->bindValue(':codcliente', $codcliente);
			
			if($p_sql->execute()) {
				
				if($show = $p_sql->fetch(PDO::FETCH_ASSOC)) {
					return $show;
				}else{
					return null;
				}
			}
		}
		public function insereAtendimentoAgendamento($dados){
			
			//echo "<pre>"; print_r($dados); exit;
			
			$codcliente = $dados['codcliente'];
			$data 		= $dados['data'];
			
			$senha_proxima = $this->GeraSenhaAtendimentoAgendamento($data);
			
			$_query = "INSERT INTO atendimento (senha, 
												canatendimento,
												status, 
												tipo, 
												unidade, 
												datcadastro, 
												datagendamento,
												codcliente
											) values (
												:senha, 
												:canatendimento,
												:status, 
												:tipo, 
												:unidade, 
												:datcadastro, 
												:datagendamento,
												:codcliente);";
			
			$p_sql = $this->MySql->prepare($_query);
			
			$p_sql->bindValue(':senha', $senha_proxima);
			$p_sql->bindValue(':canatendimento', 'W');
			$p_sql->bindValue(':status', 'L'); 
			$p_sql->bindValue(':tipo', 'A');
			$p_sql->bindValue(':unidade', 'CS'); 
			$p_sql->bindValue(':datcadastro', date('Y-m-d H:i:s')); 
			$p_sql->bindValue(':datagendamento', $data);
			$p_sql->bindValue(':codcliente', $codcliente);
			
			if($p_sql->execute()) {
				return $this->MySql->lastInsertId();
			}else{
				return null;
			}
		}
		public function validaSeFeriado($data){
			
			//echo "<pre>"; print_r($data); exit;
			
			$_query = "select * from protocolo.agendamento_feriados f where f.data = :data;";
			
			$p_sql = $this->MySql->prepare($_query);
			
			$p_sql->bindValue(':data', $data);
			
			if($p_sql->execute()) {
				
				if($show = $p_sql->fetch(PDO::FETCH_ASSOC)) {
					return true;
				}else{
					return false;
				}
			}
		}
		
		public function retornaMeusAgendamentos($dados){
			
			$_query = "select
							a.codatendimento,
							concat(a.tipo,lpad(a.senha,3,'0')) as senha,
							c.*,
							date_format(a.datagendamento,'%d/%m/%Y %H:%i') as datagendamento,
							date_format(a.datcadastro,'%d/%m/%Y %H:%i') as datcadastro,
							a.status
						from protocolo.atendimento a
							inner join protocolo.protocolo_clientes c on c.codcliente = a.codcliente and c.cpfremetente = :cpfremetente
								where a.tipo = 'A' and a.status in('L') and date(a.datagendamento) >= curdate();";
			
			$p_sql = $this->MySql->prepare($_query);
			
			$p_sql->bindValue(':cpfremetente', $dados['cpfremetente']);
			
			if( $p_sql->execute() ) {
				
				$array = array();
				
				while($show = $p_sql->fetch(PDO::FETCH_ASSOC)) {
					$array[] = $show;	
				}
				return $array;
			}
		}
		
		//  ========================================================
		
		public function retornaDiaDaSemana($diasemana){
			
			switch($diasemana){          
				  case"1": return "Domingo";     break;          
				  case"2": return "Segunda-Feira"; break;          
				  case"3": return "Terça-Feira";   break;          
				  case"4": return "Quarta-Feira";  break;          
				  case"5": return "Quinta-Feira";  break;          
				  case"6": return "Sexta-Feira";   break;          
				  case"7": return "Sábado";    break;         
				  default: return "Inválido"; break;
			}
		}
        public function getDataHora($obj){
            return $date = new DateTime($obj);
        }
		public function convertemMinuscula($term) { 
			$palavra = strtolower(strtr(strtoupper($term),"ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÜÚÞßÇ","àáâãäåæçèéêëìíîïðñòóôõö÷øùüúþÿç")); 
			return $palavra; 
		}
		public function ucwords_improved($s, $e = array('e')){
			return join(' ',
					   array_map(
						   create_function(
							   '$s',
							   'return (!in_array($s, ' . var_export($e, true) . ')) ? ucfirst($s) : $s;'
						   ),
						   explode(
							   ' ',
							   $this->convertemMinuscula($s)
						   )
					   )
				   );
		}
        
}

?>