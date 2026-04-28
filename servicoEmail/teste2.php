<?php


$Nome		= 'Israel Araujo';	// Pega o valor do campo Nome
$Fone		= '92991553632';	// Pega o valor do campo Telefone
$Email		= 'iaraujo.israel@gmail.com';	// Pega o valor do campo Email
$Mensagem	= 'teste';	// Pega os valores do campo Mensagem

// Variável que junta os valores acima e monta o corpo do email

$Vai 		= "Nome: $Nome\n\nE-mail: $Email\n\nTelefone: $Fone\n\nMensagem: $Mensagem\n";

require_once("PHPMailer/class.phpmailer.php");

define('GUSER', 'iaraujo.israel@gmail.com');	// <-- Insira aqui o seu GMail
define('GPWD', 'Iaraujo.1985');		// <-- Insira aqui a senha do seu GMail

function smtpmailer($para, $de, $de_nome, $assunto, $corpo) { 
    	global $error;
	$mail = new PHPMailer();
        print_R($mail); exit;
	$mail->IsSMTP();		// Ativar SMTP
	$mail->SMTPDebug = 0;		// Debugar: 1 = erros e mensagens, 2 = mensagens apenas
	$mail->SMTPAuth = true;		// Autenticação ativada
	$mail->SMTPSecure = 'ssl';	// SSL REQUERIDO pelo GMail
	$mail->Host = 'smtp.gmail.com';	// SMTP utilizado
	$mail->Port = 587;  		// A porta 587 deverá estar aberta em seu servidor
	$mail->Username = GUSER;
	$mail->Password = GPWD;
	$mail->SetFrom($de, $de_nome);
	$mail->Subject = $assunto;
	$mail->Body = $corpo;
	$mail->AddAddress($para);
       
	if(!$mail->Send()) {
		$error = 'Mail error: '.$mail->ErrorInfo; 
		return false;
	} else {
		$error = 'Mensagem enviada!';
		return true;
	}
         echo 'aqui'; exit;
}

// Insira abaixo o email que irá receber a mensagem, o email que irá enviar (o mesmo da variável GUSER), 
 if (smtpmailer('israel.araujo@unimedmanaus.coop.br', 'iaraujo.israel@gmail.com', 'Israel Araujo', 'Teste', $Vai)) {

	Header("location:http://www.dominio.com.br/obrigado.html"); // Redireciona para uma página de obrigado.

}
if (!empty($error)) echo $error;
    
	/*
	include_once 'phpmailer/PHPMailerAutoload.php';
	$_Host 		= 'smtp.gmail.com'; gethostbyname("smtp.office365.com");
        $_Username 	=  "iaraujo.israel@gmail.com"; //"webmaster@unimedmanaus.coop.br";
        $_Password 	= 'Iaraujo'; //"W3b@Un!m3d";
                        
        $mail = new \PHPMailer();
	$mail->isSMTP();
	$mail->Host = $_Host;
        $mail->Username = $_Username;
	$mail->Password = $_Password;
	$mail->Port = 587;
	$mail->SMTPSecure = 'tls';
	$mail->SMTPAuth = true;
	
	$mail->setFrom('webmaster@unimedmanaus.coop.br', 'Meu Nome');
	$mail->addReplyTo('israel.araujo@unimedmanaus.coop.br', 'Israel ARaujo');
	$mail->addAddress('iaraujo.israel@gmail.com', 'Israel');
	//$mail->addCC('samuel-vicente-moraes@outlook.com', 'Moraes');
	//$mail->addBCC('samuel-vicente-moraes@outlook.com', 'Moraes');
	$mail->Subject = 'Envio de email';
	$mail->CharSet = 'UTF-8';
	$mail->msgHTML("<p>Mensagem de <b>boas-vindas</b>!</p>");
	$mail->AltBody = 'Mensagem de boas-vindas';
	//$mail->addAttachment(__DIR__ . '/logo-devmedia.png');

	if (!$mail->send()) {
		die("Erro no envio do e-mail: {$mail->ErrorInfo}");
	}

	echo 'Mensagem enviada com sucesso';
        
        */
        
                       
	
	
		
?>
