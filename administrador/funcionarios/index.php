<?php

// Valida a seção e registra e inclui valores necessários para a página
require 'init.php';

/**
 * Carrega valores do template e o popula (o padrão de todas as páginas)
 */
$ger_func = file_get_contents($root_path."template.html");

// Valores e componentes do template
$title = "Gerenciamento de funcionários";
$peso_fonte = ",200,700";
$nav = file_get_contents($root_path."componentes/nav-administrador.html");
$footer = file_get_contents($root_path."componentes/footer.html");
$scripts = file_get_contents($root_path."administrador/componentes/scripts.js");
$scripts .= file_get_contents($root_path."administrador/componentes/ajax.js");

// Carregando valores e componentes no template
$ger_func = str_replace("{title}", $title, $ger_func);
$ger_func = str_replace("{peso_fonte}", $peso_fonte, $ger_func);
$ger_func = str_replace("{{nav}}", $nav, $ger_func);
$ger_func = str_replace("{{footer}}", $footer, $ger_func);
$ger_func = str_replace("{{scripts}}", $scripts, $ger_func);

/**
 * Gera conteúdo da página com função de geradores.php
 */
$pesquisa = isset($_POST['pesquisa']) ? $_POST['pesquisa'] : 'TODOS';
$main = gerarMain('funcionario', $pesquisa, $root_path);


// Carrega MAIN na página de gerenciamento
$ger_func = str_replace("{{main}}", $main, $ger_func);

// Carregando root_path na página
$ger_func = str_replace("{root_path}", $root_path, $ger_func);

// Renderização da página
print($ger_func);
