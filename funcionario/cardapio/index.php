<?php

require 'init.php';

$title = 'Gerenciar cardápio - Almoçaí';
$peso_fonte = ",200,700";
$nav = file_get_contents($root_path . "componentes/nav-funcionario-trp.html");
$footer = file_get_contents($root_path . "componentes/footer.html");
$scripts = file_get_contents("scripts.js");



/**
 * MAIN
 */
// carrega semana conforme código ou data ou mostra erro de cardápio indisponível
if (isset($_GET['cod'])) {
	$cardapio_existe = SemanaCardapioDao::SemanaExisteCodigo($_GET['cod']);
	$cardapio = $cardapio_existe ? 
		SemanaCardapioDao::SelectPorCodigo($_GET['cod'])
		: false;
} else {
	$cardapio_existe = SemanaCardapioDao::SemanaExisteData(date("Y-m-d"));
	$cardapio = $cardapio_existe ?
		SemanaCardapioDao::SelectPorData(date("Y-m-d"))
		: false;
}

if (!$cardapio) {

	$cardapio_indisponivel = file_get_contents('cardapio_indisponivel.html');
	$add_alimento_semana = "";
	$dias = "";
	$intervalo_semana = "";
} else {

	$cardapio_indisponivel = ""; // vazio pois cardápio está disponível

	// formulário para adicionar alimento em toda a semana
	$semana_cod = $cardapio->getCodigo();

	$add_alimento_semana = file_get_contents("add_alimento_semana.html");

	$add_alimento_semana = str_replace("{semana_cod}", $semana_cod, $add_alimento_semana);

	// carrega dias 
	$cardapio = SemanaCardapioDao::SelectDias($cardapio);

	// cartões dos dias
	$dias = ""; // variável onde os dias da semana serão concatenados

	foreach ($cardapio->getDias() as $dia) {

		$dia_codigo = $dia->getCodigo();
		$data = date("d/m", strtotime($dia->getData()));
		$dia_da_semana = $dia->getDiaSemana();
		$cor_texto = [
			'Segunda-feira' => '',
			'Terça-feira' => 'text-azul',
			'Quarta-feira' => 'text-amarelo',
			'Quinta-feira' => 'text-vermelho'
		];

		// carrega alimentos do dia
		$dia = DiaAlmocoDao::SelectAlimentos($dia);
		$alimentos = $dia->getAlimentos();

		// mostra alimentos do dia / gera {itens}
		$itens = "";
		
		if (count($alimentos) > 0) {
			$itens .= "<ul class='collection'>";
			foreach ($alimentos as $alimento) {

				$codigo = $alimento->getCodigo();
				$nome = $alimento->getDescricao();
				$tipo = $alimento->getTipo();

				// carrega valores do alimento em item.html
				$item = file_get_contents("item.html");
				$item = str_replace("{nome}", $nome, $item);
				$item = str_replace("{codigo}", $codigo, $item);
				$item = str_replace("{semana_codigo}", $semana_cod, $item);

				// concatena
				$itens .= $item;
			}
			$itens .= "</ul>";
		}

		// carrega valores e componentes em dia.html
		$dia = file_get_contents("dia.html");
		$dia = str_replace("{dia_da_semana}", $dia_da_semana, $dia);
		$dia = str_replace("{data}", $data, $dia);
		$dia = str_replace("{cor_texto}", $cor_texto["$dia_da_semana"], $dia);
		$dia = str_replace("{codigo}", $dia_codigo, $dia);
		$dia = str_replace("{semana_codigo}", $semana_cod, $dia);
		$dia = str_replace("{{ itens }}", $itens, $dia);

		// concatena
		$dias .= $dia;
	}

	$intervalo_semana = date("d/m", strtotime($cardapio->getData_inicio())) . " a " .
		date("d/m", strtotime($cardapio->getData_inicio() . " + 3 days"));
}



$main = file_get_contents("main.html");

$main = str_replace("{intervalo_semana}", $intervalo_semana, $main);
$main = str_replace("{{ add_alimento_semana }}", $add_alimento_semana, $main);
$main = str_replace("{{ dias }}", $dias, $main);
$main = str_replace("{{ cardapio_indisponivel }}", $cardapio_indisponivel, $main);
/**
 * FIM MAIN
 */

$gercard = file_get_contents($root_path . "template.html"); // gercard = gerenciar cardapio

$gercard = str_replace("{title}", $title, $gercard);
$gercard = str_replace("{peso_fonte}", $peso_fonte, $gercard);
$gercard = str_replace("{{nav}}", $nav, $gercard);
$gercard = str_replace("{{main}}", $main, $gercard);
$gercard = str_replace("{{footer}}", $footer, $gercard);
$gercard = str_replace("{{scripts}}", $scripts, $gercard);

$gercard = str_replace("{root_path}", $root_path, $gercard);
print $gercard;
