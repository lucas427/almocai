<?php

require 'init.php';

/* Inicialização do layout */

$title = "Intolerâncias";
$peso_fonte = "";
$nav = file_get_contents($root_path."componentes/nav-transparent.html");
$footer = file_get_contents($root_path."componentes/footer.html");
$scripts = file_get_contents("scripts.js");

$intolerancias = file_get_contents($root_path."template.html");
$intolerancias = str_replace("{title}", $title, $intolerancias);
$intolerancias = str_replace("{peso_fonte}", $peso_fonte, $intolerancias);
$intolerancias = str_replace("{{nav}}", $nav, $intolerancias);
$intolerancias = str_replace("{{footer}}", $footer, $intolerancias);
$intolerancias = str_replace("{{scripts}}", $scripts, $intolerancias);


/* Conteúdo principal da página */

$main = file_get_contents("main.html");

$intolsBD = IntoleranciaDao::SelectTodas();

$opcoes = "";
foreach ($intolsBD as $intol) {
	$opcao = file_get_contents("opcao_intolerancia.html");
	$opcao = str_replace("{codigo}", $intol->getCodigo(), $opcao);
	$opcao = str_replace("{descricao}", $intol->getDescricao(), $opcao);
	$opcoes .= $opcao;
}
$main = str_replace("{{opcoes_intolerancia}}", $opcoes, $main);

$intolerancias = str_replace("{{main}}", $main, $intolerancias);


/* root_path + Renderização da página */

$intolerancias = str_replace("{root_path}", $root_path, $intolerancias);
print $intolerancias;