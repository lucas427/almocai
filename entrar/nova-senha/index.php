<?php

$root_path = "../../";

require_once "{$root_path}classes/UsuarioDao.class.php";

// Função de redirecionamento, usada nos vários casos em que o usuário não pode acessar essa página
function Redir()
{
	header("location:{$GLOBALS['root_path']}entrar/");
}

// Pega usuário do banco
if (isset($_GET['email'])) {
	$email = $_GET['email'];

	$usuario = UsuarioDao::SelectPorEmail($email);
} else Redir();

// Confirma hash
if (isset($_GET['hash'])) {
	$hash = $_GET['hash'];

	if ($usuario->hash() != $hash) Redir();

} else Redir();

// Gera a página de alteração de senha

$main = file_get_contents("main.html");
$main = str_replace("{usuario_codigo}", $usuario->getCodigo(), $main);
$main = str_replace("{usuario_email}", $usuario->getEmail(), $main);

$novas = file_get_contents("{$root_path}template.html");

$novas = str_replace("{title}", "Redefinir senha", $novas);
$novas = str_replace("{peso_fonte}", "", $novas);
$novas = str_replace("{{nav}}", "", $novas);
$novas = str_replace("{{footer}}", "", $novas);
$novas = str_replace("{{scripts}}", "", $novas);
$novas = str_replace("{{main}}", $main, $novas);

// TODO verificação com JS se nova senha = confirma nova senha

$novas = str_replace("{root_path}", $root_path, $novas);
print $novas;