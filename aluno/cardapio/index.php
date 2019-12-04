<?php

require 'init.php';

// Carrega template geral
$cardapio = file_get_contents($root_path . "template.html");

// Carrega main.html
$main = file_get_contents("main.html");
$cardapio = str_replace("{{main}}", $main, $cardapio);

// Título da página
$title = "Cardápio";
$cardapio = str_replace("{title}", $title, $cardapio);

// Carrega componentes/nav-transparent.html 
$nav = file_get_contents($root_path . "componentes/nav-transparent.html");
$cardapio = str_replace("{{nav}}", $nav, $cardapio);

// Carrega componentes/footer.html 
$footer = file_get_contents($root_path . "componentes/footer.html");
$cardapio = str_replace("{{footer}}", $footer, $cardapio);

// Carrega scripts.js
$scripts = file_get_contents("scripts.js");
$cardapio = str_replace("{{scripts}}", $scripts, $cardapio);

// Vazios (peso_fonte)
$cardapio = str_replace("{peso_fonte}", "", $cardapio);

/**
 * Carrega a semana/cardápio / os cartões de cada dia / os alimentos
 */
if (SemanaCardapioDao::SemanaExisteData(date("Y-m-d"))) {

	$data = Funcoes::CorrigeData(date("Y-m-d"));

	// Pega a semana do BD a partir da data
	$semana = SemanaCardapioDao::SelectPorData($data);

	// Pega os dias da semana do BD
	$semana = SemanaCardapioDao::SelectDias($semana);
	$dias = $semana->getDias();

	// Pega os alimentos de cada dia do BD
	for ($i = 0; $i < count($dias); $i++) {
		$dias[$i] = DiaAlmocoDao::SelectAlimentos($dias[$i]);
		$alimentos[$i] = $dias[$i]->getAlimentos();
	}

	// Cria cada <li> alimento </li>
	for ($i = 0; $i < count($alimentos); $i++) {
		for ($j = 0; $j < count($alimentos[$i]); $j++) {      

			// Carrega o template <li>alimento</li> e coloca o nome e ícone do alimento
			$li = file_get_contents("alimento_li.html");
			$alimentos[$i][$j] = str_replace("{nome_alimento}", $alimentos[$i][$j]->getDescricao(), $li);      
			// As variáveis que antes eram objetos tornam-se strings aqui
		}
	}

	// $disabled = variável que armazena se deve bloquear o aluno a alterar sua presença
	// começa "disabled" (texto que irá nos botões para os tornar inacessíveis)
	// mas se torna "" quando determina que o usuário já pode alterar sua presença
	$disabled = " disabled ";
	
	// Cria cada cartão/dia
	for ($i = 0; $i < count($dias); $i++) {
		$data = $dias[$i]->getData();
		$diaSemana = $dias[$i]->getDiaSemana();

		// se em um dia o aluno pode mudar presença, pode também nos dias restantes
		// $disabled != "" -> não precisa verificar mais de uma vez
		if (
			$disabled != "" &&
			$dias[$i]->alunoPodeMudarPresenca(date("Y-m-d H:i:s"))
		) $disabled = "";

		// Presença do aluno no dia
		$pres = UsuarioDao::SelectPresenca($dias[$i]->getCodigo(), $_SESSION['codigo']);
		if ($pres == 1) {
			$fundo_cor = ' aluno__confirmado ';
			$cor_texto_presenca = 'cardapio__confirmacao--confirmado';
			$texto_presenca = 'Almoçarei <i class="material-icons" style="transform: translateY(3px);">thumb_up</i>';
		} else {
			$fundo_cor = ' aluno__negado ';
			$cor_texto_presenca = 'cardapio__confirmacao--negado';
			$texto_presenca = 'Não almoçarei <i class="material-icons" style="transform: translateY(3px);">thumb_down</i>';
		}

		$dia[$i] = file_get_contents("dia_cartao.html");
		$dia[$i] = str_replace("{disabled}", $disabled, $dia[$i]);
		$dia[$i] = str_replace("{dia_cod}", $dias[$i]->getCodigo(), $dia[$i]);
		$dia[$i] = str_replace("{fundo_cor}", $fundo_cor, $dia[$i]);
		$dia[$i] = str_replace("{cor_texto_presenca}", $cor_texto_presenca, $dia[$i]);
		$dia[$i] = str_replace("{texto_presenca}", $texto_presenca, $dia[$i]);


		$alimentosHTML = "";


		// Concatena os <li>alimento</li> em um $alimentosHTML para cada dia
		for ($j = 0; $j < count($alimentos[$i]); $j++) {
			$alimentosHTML .= $alimentos[$i][$j];
		}

		// Carrega os valores e a lista de alimentos ao template dia_cartao.html
		$dia[$i] = str_replace("{{alimentos}}", $alimentosHTML, $dia[$i]);
		$dia[$i] = str_replace("{dia_semana}", $diaSemana, $dia[$i]);
		$dia[$i] = str_replace("{num_dia}", $i + 1, $dia[$i]);
	}

	// Cria o conjunto de cartões (concatena para {{dias_cartoes}} do main.html)
	$dias_cartoes = "";
	for ($i = 0; $i < count($dia); $i++) {
		$dias_cartoes .= $dia[$i];
	}
	/**
	 * Fim do carregamento do cardápio
	 */

	// Não mostra erro de cardápio indisponível
	$cardapio_indisponivel = "";

	// Carrega período da semana
	$data_inic = date("d/m", strtotime($semana->getData_inicio()));
	$data_fim = date("d/m", strtotime($semana->getData_inicio() . ' + 3 days'));
	$periodo = $data_inic . " a " . $data_fim;
} else {

	$dias_cartoes = "";
	$cardapio_indisponivel = file_get_contents("cardapio_indisponivel.html");
	$periodo = "";
}

$cardapio = str_replace('{periodo_cardapio}', $periodo, $cardapio);
$cardapio = str_replace("{{dias_cartoes}}", $dias_cartoes, $cardapio);
$cardapio = str_replace("{{cardapio_indisponivel}}", $cardapio_indisponivel, $cardapio);


// Carrega caminho à raiz (root_path) SEMPRE NO FINAL, ANTES DO PRINT
$cardapio = str_replace("{root_path}", $root_path, $cardapio);
print($cardapio);

