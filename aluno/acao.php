<?php

if (!isset($_POST['acao']) && !isset($_GET['acao'])) {
  header("location:index.php");
}

$root_path = "../";
include("{$root_path}valida_secao.php");
valida_secao_tipo($root_path, 'ALUNO');
require_once("{$root_path}classes/UsuarioDao.class.php");
require_once("{$root_path}classes/DiaAlmoco.class.php");
require_once("{$root_path}classes/DiaAlmocoDao.class.php");
require_once("{$root_path}classes/AlunoPresenca.class.php");

$acao = $_POST['acao'];

$dia_cod = isset($_POST['dia_cod']) ? $_POST['dia_cod'] : '';

// Para qual página o usuário deve ser redirecionado após cadastrar a ação
$redir = $_POST['redir'];
if ($redir == 'cardapio') {
  $redir = "{$root_path}aluno/cardapio/#{$dia_cod}";
} else if ($redir == 'index') {
  $redir = "{$root_path}aluno";
}

CadPresenca($acao, $dia_cod);

header("location:{$redir}");


// FUNÇÕES


function CadPresenca ($acao, $dia_cod) {
  $user = new Usuario;
  $user->setCodigo($_SESSION['codigo']);

  $presenca = new AlunoPresenca;
  $presenca->setAluno($user);
  
       if ($acao == 'PresencaSim') $presenca->setPresenca(1);
  else if ($acao == 'PresencaNao') $presenca->setPresenca(0);

  $dia = new DiaAlmoco;
  $dia->setCodigo($dia_cod);
  $dia->setPresenca($presenca);

  DiaAlmocoDao::InserirPresencas($dia);
}

?>