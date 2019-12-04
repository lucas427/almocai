<?php
$root_path = "../../";

include("{$root_path}valida_secao.php");
valida_secao($root_path);

require_once("{$root_path}classes/UsuarioDao.class.php");
require_once("{$root_path}classes/Frequencia.class.php");
require_once("{$root_path}classes/Alimentacao.class.php");

$usuario = new Usuario;
$usuario->setCodigo($_SESSION['codigo']);

if (isset($_POST['acao'])) $acao = $_POST['acao'];
else if (isset($_GET['acao'])) $acao = $_GET['acao'];
else $acao = '';

if ($acao == 'AlterarEmail') {

  $usuario->setEmail($_POST['email']);
  UsuarioDao::UpdateEmail($usuario);

  header("location:{$root_path}aluno/perfil/?sucesso=email_alterado");

} else if ($acao == 'AlterarSenha') {

  $senhaAntiga = $_POST['senhaAntiga'];
  $_POST['senhaAntiga'] = '';

  $senhaNova = $_POST['senhaNova'];
  $_POST['senhaNova'] = '';

  $usuario = UsuarioDao::SelectPorCodigo($usuario->getCodigo());
  if ($usuario->getSenha() == $senhaAntiga) {
    $usuario->setSenha($senhaNova);
    UsuarioDao::UpdateSenha($usuario);
    session_destroy();
    header("location:{$root_path}entrar/?sucesso=senha_alterada");
  } else {
    header("location:{$root_path}aluno/perfil/?erro=senha_antiga_incorreta"); // retornar erro ao usuário
  }
} else if ($acao == 'SalvarFrequencia') {

  $frequencia = new Frequencia;
  $frequencia->setCodigo($_POST['frequencia']);
  $usuario->setFrequencia($frequencia);
  UsuarioDao::UpdateFrequencia($usuario);
  header("location:{$root_path}aluno/perfil/?freq_selecionada={$frequencia->getCodigo()}");

} else if ($acao == 'SalvarAlimentacao') {

  $alimentacao = new Alimentacao;
  $alimentacao->setCodigo($_POST['alimentacao']);
  $usuario->setAlimentacao($alimentacao);
  UsuarioDao::UpdateAlimentacao($usuario);
  header("location:{$root_path}aluno/perfil/");
} else if ($acao == 'CadastrarIntolerancia') { 

} else if ($acao == 'RemoverIntrolerancia') { 

}
