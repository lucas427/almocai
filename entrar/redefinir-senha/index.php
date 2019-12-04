<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

// Função que envia o e-mail
function EnviarEmail($hash, $email) {

	$from = '****';
	$fromPassword = '****';
	
	$mail = new PHPMailer();
	$mail->isSMTP();
	$mail->Host = '****';
	$mail->SMTPAuth = true;
	$mail->SMTPSecure = 'tls';
	$mail->Username = $from;
	$mail->Password = $fromPassword;
	$mail->Port = 587;

	$root = "https://fabricadetecnologias.ifc-riodosul.edu.br/almocai";
	$uri = "/entrar/nova-senha/?hash={$hash}&email={$email}";
	
	$to = $GLOBALS['email'];
	$link = $root.$uri;

	$subject = utf8_decode("Almoçaí - Redefinir senha");
		
	$mail->setFrom($from, utf8_decode('Suporte - Almoçaí'));
	$mail->addReplyTo($from, utf8_decode('Suporte - Almoçaí'));
	$mail->addAddress($to);


	$content = file_get_contents('modelo-mensagem.html');
	$content = str_replace('{link}', $link, $content);
	$content = str_replace('{year}', date("Y"), $content);
	
	
	$mail->isHTML(true);
	$mail->Subject = $subject;
	$mail->Body    = $content;
	$mail->AltBody = 'Link para recuperar senha: '. $link;
	$mail->CharSet = 'UTF-8';
	$mail->Encoding = 'base64';

	return !$mail->send() ? false : true;

}


$root_path = "../../";

require_once "{$root_path}classes/UsuarioDao.class.php";

$email = isset($_POST['email']) ? $_POST['email'] : "";

if ($email == "")
{
	$msg_email_enviado = "";

} else{
	if(UsuarioDao::VerificaEmail($email) > 0){
		// Carrega usuário do BD pelo e-mail e carrega hash
		$usuario = UsuarioDao::SelectPorEmail($email);

		$hash = $usuario->hash();

		if (EnviarEmail($hash, $email)) {
			$msg_email_enviado = file_get_contents("msg_email_enviado.html");
		} else {
			$msg_email_enviado = file_get_contents("msg_email_invalido.html");
			$errorMessage = 'Não foi possível enviar o email, tente novamente mais tarde. Caso o erro persista, contate a Cordenação Geral de Ensino';
			$msg_email_enviado = str_replace('{errorMessage}', $errorMessage, $msg_email_enviado);
		}

	}
	else{
		$msg_email_enviado = file_get_contents("msg_email_invalido.html");
		$errorMessage = 'O email inserido é invalido. Verifique seu email e tente novamente!';
		$msg_email_enviado = str_replace('{errorMessage}', $errorMessage, $msg_email_enviado);
	}
}

$main = file_get_contents("main.html");
$main = str_replace("{{msg_email_enviado}}", $msg_email_enviado, $main);

$pagina = file_get_contents("{$root_path}template.html");
$pagina = str_replace("{title}", "Redefinir senha", $pagina);
$pagina = str_replace("{peso_fonte}", "", $pagina);
$pagina = str_replace("{{nav}}", "", $pagina);
$pagina = str_replace("{{footer}}", "", $pagina);
$pagina = str_replace("{{scripts}}", "", $pagina);
$pagina = str_replace("{{main}}", $main, $pagina);

$pagina = str_replace("{root_path}", $root_path, $pagina);

print $pagina;