<?php

session_start();

// Caminho à raiz do projeto
$root_path = "../";

$entrar = file_get_contents($root_path . 'template.html');

// Título da página
$title = "Entrar";
$entrar = str_replace('{title}', $title, $entrar);

// Vazios (nav, footer, scripts, peso_fonte)
$entrar = str_replace('{{nav}}', "", $entrar);
$entrar = str_replace('{{footer}}', "", $entrar);
$entrar = str_replace('{peso_fonte}', "", $entrar);

// Scripts + erro ao logar
$scripts = file_get_contents("scripts.js");
$erro_trigger = "";
if (isset($_GET['erro'])) {
  $erro_trigger = "erroLogin()";
}
$scripts = str_replace("{erro_trigger}", $erro_trigger, $scripts);
$entrar = str_replace("{{scripts}}", $scripts, $entrar);

$senha_alterada_sucesso = "";

// Mostra se a senha foi alterada com sucesso
if (isset($_GET['sucesso'])) {
  if ($_GET['sucesso'] == 'senha_alterada') {
    $senha_alterada_sucesso = file_get_contents("senha_alterada_sucesso.html");
  }
}

$main = file_get_contents('main.html');

$main = str_replace("{{ senha_alterada_sucesso }}", $senha_alterada_sucesso, $main);

$entrar = str_replace('{{main}}', $main, $entrar);

$entrar = str_replace('{root_path}', $root_path, $entrar);
print($entrar);
