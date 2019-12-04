<?php

// Valida seção, inclui classes que serão usadas etc.
require 'init.php';

$usuario = UsuarioDao::SelectPorCodigo($_SESSION['codigo']);

/**
 * Variáveis e componentes do template.html
 */
// Declaração das variáveis e componentes
$title = "Aluno";
$peso_fonte = '';
$nav = file_get_contents($root_path . "componentes/nav-transparent.html");
$footer = file_get_contents($root_path . "componentes/footer.html");
$scripts = file_get_contents("scripts.js");

// Carregamento das variáveis e componentes no template
$aluno = file_get_contents($root_path . "template.html");
$aluno = str_replace("{title}", $title, $aluno);
$aluno = str_replace("{peso_fonte}", $peso_fonte, $aluno);
$aluno = str_replace("{{nav}}", $nav, $aluno);
$aluno = str_replace("{{footer}}", $footer, $aluno);
$aluno = str_replace("{{scripts}}", $scripts, $aluno);

/**
 * Conteúdo da página (main)
 */
$main = file_get_contents("main.html");

// Variáveis e componentes do main
$nome = $usuario->getNome();
$data = date("Y-m-d");
$dataCorrigida = Funcoes::CorrigeData($data);
$datahj = date("d/m", strtotime($dataCorrigida));

/**
 * Mostra o dia e os botões de marcar presença ou ausência
 */

$disabled_msg = "";

if (SemanaCardapioDao::SemanaExisteData($data)) {
  
  // Componentes que não serão mostrados
  $cardapio_ind = ""; // Erro de cardápio indisponível (se a semana existe, está disponível)

  // Carrega o dia do banco de dados com a data corrigida (o BD só guarda 4 dias da semana, mas o usuário precisa poder acessar do final de semana também)
  $dia = DiaAlmocoDao::SelectPorData($dataCorrigida);

  // Cartão com os botões para marcar presença ou ausência no dia  
  $cartao_presenca = file_get_contents("cartao_presenca.html");
  $cartao_presenca = str_replace("{data_hoje}", $datahj, $cartao_presenca);
  $cartao_presenca = str_replace("{dia_cod}", $dia->getCodigo(), $cartao_presenca);

  $disabled = "";
  if (! $dia->alunoPodeMudarPresenca(date("Y-m-d H:i:s"))) {
    $disabled = " disabled ";
    $disabled_msg = file_get_contents("cartao_presenca_disabledmsg.html");
  }
  $cartao_presenca = str_replace("{disabled}", $disabled, $cartao_presenca);

  // Variáveis do componente que mostra a presença marcada pelo usuário
  $pres = UsuarioDao::SelectPresenca($dia->getCodigo(), $usuario->getCodigo());
  if ($pres == 'nao-selecionada') {
    $cor = 'amarelo';
    $fundo_cor = " ";
    $txt = 'Ainda não selecionei <i class="material-icons" style="transform: translateY(3px);">thumbs_up_down</i>';
  } else if ($pres) {
    $cor = 'verde'; // por padrão verde
    $fundo_cor = ' aluno__confirmado ';
    $txt = ' Almoçarei <i class="material-icons" style="transform: translateY(3px);">thumb_up</i>';
  } else {
    $cor = 'vermelho';
    $fundo_cor = ' aluno__negado ';
    $txt = 'Não almoçarei <i class="material-icons" style="transform: translateY(3px);">thumb_down</i>';
  }

  // Componente HTML que mostra a presença marcada pelo usuário
  $Cpres_selec = file_get_contents("cartao_presenca_selecionada.html");
  $Cpres_selec = str_replace("{cor}", $cor, $Cpres_selec);
  $Cpres_selec = str_replace("{presenca_selecionada}", $txt, $Cpres_selec);
  // O cartão com a opção selecionada é mostrado dentro do cartão em que o usuário seleciona "Sim" ou "Não" (presença ou ausência)
  $cartao_presenca = str_replace("{{cartao_presenca_selecionada}}", $Cpres_selec, $cartao_presenca);
  $cartao_presenca = str_replace("{cor}", $cor, $cartao_presenca);
  // Adiciona a cor de fundo ao cartão de presenã amiguito
  $cartao_presenca = str_replace("{fundo_cor}", $fundo_cor, $cartao_presenca);

  // Carrega os alimentos do dia do BD
  $alimentos = AlimentoDao::SelectPorDia($dia->getCodigo());
  
  // Itens: conjunto de alimentos do dia
  $itens = "";

  // Sem alimentos - mensagem caso ainda não haja alimentos no dia
  $sem_alimentos = "";

  if (count($alimentos) == 0) {
    $sem_alimentos = file_get_contents("cartao_dia_semAlimentos.html");
  } else {
    foreach ($alimentos as $alimento) {
      
      // Carrega o nome e o ícone do alimento no template da linha
      $item = file_get_contents("cartao_dia_item.html");
      $item = str_replace("{nome}", $alimento->getDescricao(), $item);
  
      // Concatena
      $itens .= $item;
    }
  }


  // Carrega o dia do BD no template do dia 
  $dia_semana = $dia->getDiaSemana();
  $num_dia = $NUM_DIA[$dia->getDiaSemana()]; // array $NUM_DIA[] em config.php

  $cartao_dia = file_get_contents("cartao_dia.html");
  $cartao_dia = str_replace("{{sem_alimentos}}", $sem_alimentos, $cartao_dia);
  $cartao_dia = str_replace("{{itens}}", $itens, $cartao_dia);  
  $cartao_dia = str_replace("{dia_semana}", $dia_semana, $cartao_dia);  
  $cartao_dia = str_replace("{num_dia}", $num_dia, $cartao_dia);

} else { // caso não exista a semana
  $cartao_dia = ""; // não será mostrado cartão do dia (não existe esse dia)
  $cardapio_ind = file_get_contents("cardapio_indisponivel.html"); // mostra erro de cardápio indisponível
  $cartao_presenca = ""; // não mostra opção de marcar presença ou não
}

// Carrega valores e componentes no template
$main = str_replace("{nome}", $nome, $main);
$main = str_replace("{{cartao_dia}}", $cartao_dia, $main);
$main = str_replace("{{cardapio_indisponivel}}", $cardapio_ind, $main);
$main = str_replace("{{cartao_presenca}}", $cartao_presenca, $main);
$main = str_replace("{{disabled_msg}}", $disabled_msg, $main);

$aluno = str_replace("{{main}}", $main, $aluno);
$aluno = str_replace("{root_path}", $root_path, $aluno);

print($aluno);
