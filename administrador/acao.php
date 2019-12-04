<?php

/**
 * Página de controle de ação geral para o administrador. Usada tanto no gerenciamento de funcionários quanto no de alunos porque essas páginas funcionam praticamente do mesmo jeito.
 * As funções usadas nas ações (InsertUsuario(), Redir() etc.) estão declaradas neste arquivo, no seu fim
 */

// Página que verifica seção, carrega objetos que serão usados, determina ação, root path etc.
require 'acao_init.php';

if ($acao == 'Insert') {
  InsertUsuario();
  Redir();

} else if ($acao == 'Update') {  
  UpdateUsuario();
  Redir();

} else if ($acao == 'Delete') {  
  UsuarioDao::Delete($_GET['codigo']);
  Redir();

} else if($acao == 'ValidarUsuario'){
  $NumeroUsuarios = count(UsuarioDao::Select('username-exato', $_GET['username']));
  echo $NumeroUsuarios;
}

/**
 * Insere um novo usuário no banco de dados. Primeiro o instancia e depois o insere no BD por um método do DAO
 */
function InsertUsuario()
{
  // Instancia novo usuário com as informações do formulário
  $usuario = new Usuario;
  $usuario->setCodigo($_POST['codigo']);
  $usuario->setNome(htmlspecialchars($_POST['nome']));
  $usuario->setUsername(htmlspecialchars($_POST['username']));
  $usuario->setSenha($_POST['senha']);
  $usuario->setTipo($_POST['tipo']);

  // Insere esse usuário no BD pelo DAO
  UsuarioDao::Insert($usuario);
}

/**
 * Atualiza o registro de um aluno. Altera seu nome e sua senha (se ela foi mudada no formulário)
 */
function UpdateUsuario()
{
  // Instancia um usuário com código e nome do formulário
  $usuario = new Usuario;
  $usuario->setCodigo($_POST['codigo']);
  $usuario->setUsername(htmlspecialchars($_POST['username']));
  $usuario->setNome(htmlspecialchars($_POST['nome']));

  // Primeiro atualiza apenas o nome e o username
  UsuarioDao::Update2($usuario);

  // Só atualiza a senha se uma senha veio do formulário
  if ($_POST['senha'] != '') {

    $usuario->setSenha($_POST['senha']);

    UsuarioDao::UpdateSenha($usuario);
  }
}

/**
 * Redireciona o administrador para a página de onde veio conforme o tipo de usuário que inseriu, atualizou ou deletou
 */
function Redir()
{
  $tipo = isset($_POST['tipo']) ? 
    $_POST['tipo'] 
    : $_GET['tipo'];

  if ($tipo == 'ALUNO')
    $redir = $GLOBALS['root_path'] . "administrador/alunos/";
  else if ($tipo == 'FUNCIONARIO')
    $redir = $GLOBALS['root_path'] . "administrador/funcionarios/";
    
  header("location:{$redir}");
}