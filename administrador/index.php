<?php

/**
 * Página inicial do administrador. Basicamente mantém 2 links, um para o gerenciamento de alunos e outro para o gerenciamento de funcionários
 */

$root_path = "../";

// Valida seção para que só adminsitradores acessem o painel
include($root_path."valida_secao.php");
valida_secao_tipo($root_path, 'ADMINISTRADOR');

// Valores a ser carregados no template {}
$title = 'Painel do administrador';
$peso_fonte = ",200";

// Componentes HTML a ser carregados no template {{}}
$nav = file_get_contents($root_path."componentes/nav-administrador.html");
$main = file_get_contents("main.html");
$footer = file_get_contents($root_path."componentes/footer.html");
$scripts = file_get_contents('scripts.js');

// Carregamento dos valores e componentes no template
$painel_adm = file_get_contents($root_path."template.html");
$painel_adm = str_replace("{title}", $title, $painel_adm);
$painel_adm = str_replace("{peso_fonte}", $peso_fonte, $painel_adm);
$painel_adm = str_replace("{{nav}}", $nav, $painel_adm);
$painel_adm = str_replace("{{footer}}", $footer, $painel_adm);
$painel_adm = str_replace("{{scripts}}", $scripts, $painel_adm);
$painel_adm = str_replace("{{main}}", $main, $painel_adm);

$painel_adm = str_replace("{root_path}", $root_path, $painel_adm);

// Renderiza a página
print($painel_adm);
?>