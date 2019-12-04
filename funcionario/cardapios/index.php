<?php

require 'init.php';

$title = 'Cardápios';
$peso_fonte = ',200,700';
$nav = file_get_contents($root_path."componentes/nav-funcionario-trp.html");
$footer = file_get_contents($root_path."componentes/footer.html");
$scripts = file_get_contents("scripts.js");

// Trata de erros, mostra mensagens aos usuários
$erro = "";
if (isset($_GET['erro'])) {
	if ($_GET['erro'] == 'inicio_deve_ser_segunda') {
		$erro_msg = "<b>Erro: </b> você inseriu uma data inicial que não é segunda-feira. Para criar uma semana, deve-se inserir a data inicial como segunda-feira.";
	} else if ($_GET['erro'] == 'semana_ja_existe') {
		$erro_msg = "<b>Erro: </b> você inseriu a data de uma semana que já existe. Altere a semana na página de cardápios ou crie a próxima.";
	}
	$erro = file_get_contents("erro.html");
	$erro = str_replace("{erro_msg}", $erro_msg, $erro);
}

// Cria cartões das semanas
$semanas = SemanaCardapioDao::SelectTodos();
$cartoes = "";
foreach ($semanas as $semana) {
	$data_inicio = date("d/m", strtotime($semana->getData_inicio()));
	$data_fim = date("d/m", strtotime($semana->getData_inicio() . " + 3 days"));
	$semana_cod = $semana->getCodigo();

	$diaEhDaSemana = SemanaCardapioDao::diaEhDaSemana(date("Y-m-d"), $semana_cod);
	$cor = $diaEhDaSemana ? " almocai azul " : " green-gradient ";
	$cor_botao = $diaEhDaSemana ? "text-azul" : "";

	$cartao = file_get_contents("cartao_semana.html");
	$cartao = str_replace("{data_inicio}", $data_inicio, $cartao);
	$cartao = str_replace("{data_fim}", $data_fim, $cartao);
	$cartao = str_replace("{semana_cod}", $semana_cod, $cartao);
	$cartao = str_replace("{cor}", $cor, $cartao);
	$cartao = str_replace("{cor_botao}", $cor_botao, $cartao);

	$cartoes .= $cartao;
}

$cardapios = file_get_contents("{$root_path}template.html");

$main = file_get_contents("main.html");
$main = str_replace("{{cartoes_semanas}}", $cartoes, $main);
$main = str_replace("{{erro}}", $erro, $main);

$cardapios = str_replace("{title}", $title, $cardapios);
$cardapios = str_replace("{peso_fonte}", $peso_fonte, $cardapios);
$cardapios = str_replace("{{nav}}", $nav, $cardapios);
$cardapios = str_replace("{{main}}", $main, $cardapios);
$cardapios = str_replace("{{footer}}", $footer, $cardapios);
$cardapios = str_replace("{{scripts}}", $scripts, $cardapios);

$cardapios = str_replace("{root_path}", $root_path, $cardapios);
print($cardapios);