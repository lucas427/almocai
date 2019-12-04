<?php
$root_path = "../";
include($root_path."valida_secao.php");
valida_secao_tipo($root_path, ['FUNCIONARIO', 'ADMINISTRADOR']);
require_once($root_path."classes/DiaAlmocoDao.class.php");
require_once($root_path."classes/SemanaCardapioDao.class.php");

date_default_timezone_set("America/Sao_Paulo");

$func = file_get_contents($root_path."template.html");

$title = 'Página inicial - Funcionário';
$peso_fonte = ',200,700';
$nav = file_get_contents($root_path."componentes/nav-funcionario-trp.html");
$footer = file_get_contents($root_path."componentes/footer.html");
$scripts = file_get_contents("scripts.js");

$func = str_replace("{title}", $title, $func);
$func = str_replace("{peso_fonte}", $peso_fonte, $func);
$func = str_replace("{{nav}}", $nav, $func);
$func = str_replace("{{footer}}", $footer, $func);
$func = str_replace("{{scripts}}", file_get_contents("scripts.js"), $func);

// $data = date("Y-m-d");
$data = Funcoes::CorrigeData(date("Y-m-d"));
/**
 * MAIN
 */
$main = file_get_contents("main.html");
// Cartões de presença
if (SemanaCardapioDao::SemanaExisteData($data)) {
  // Não mostra o erro de cardápio indisponível
  $erro_card_indisp = "";
  $dia = DiaAlmocoDao::SelectPorData($data);
  // Cartão de contagem de presenças
  $contagem = DiaAlmocoDao::ContagemPresencas($dia->getCodigo());
  // Carrega as contagens de presenças
  $cartoes_presenca = file_get_contents("cartoes_presenca.html");
  $cartoes_presenca = str_replace("{qtd_almocos}", $contagem[0], $cartoes_presenca);
  $cartoes_presenca = str_replace("{qtd_carnivoros}", $contagem[1], $cartoes_presenca);
  $cartoes_presenca = str_replace("{qtd_vegetarianos}", $contagem[2], $cartoes_presenca);
  $cartoes_presenca = str_replace("{qtd_veganos}", $contagem[3], $cartoes_presenca);
} else {
  $erro_card_indisp = file_get_contents("erro_cardapio_indisponivel.html");
  $cartoes_presenca = "";
}
// Carrega valores e componentes no template
$main = str_replace("{data}", date("d/m"), $main);
$main = str_replace("{{erro_qtd_indefinida}}", "", $main);
$main = str_replace("{{erro_cardapio_indisponivel}}", $erro_card_indisp, $main);
$main = str_replace("{{cartoes_presenca}}", $cartoes_presenca, $main);
// Carrega main na página
$func = str_replace("{{main}}", $main, $func);

$func = str_replace("{root_path}", $root_path, $func);

print($func);
?>