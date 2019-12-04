<?php

/**
 * Página com funções que geram os componentes das páginas de gerenciamento do ADM.
 * Foi feito assim porque muito das duas páginas (de alunos e de funcionários) é praticamente igual.
 * O que muda, basicamente, é a cor e, nos formulários, o tipo de usuário tratado.
 */

$root_path = "../../";

/**
 * Gera todo o conteúdo da página de gerenciamento
 * 
 * @param string $tipo o tipo de usuário ('aluno' ou 'funcionario')
 * @param string $pesquisa a pesquisa que pode ou não ter sido realizada
 * @param string $root_path
 * 
 * @return string código HTML da página main
 */
function gerarMain ($tipo, $pesquisa, $root_path) {
  // Carrega o template MAIN
  $main = file_get_contents($root_path . "administrador/componentes/main.html");

  $main = str_replace("{tipo}", $tipo, $main);
  
  // Formulário de adicionar usuários
  $add_users = gerarAddUsers ($tipo, $root_path);
  $main = str_replace("{{adicionar_usuario}}", $add_users, $main);

  // Tabela de listagem de usuários
  $list_users = gerarListUsers ($tipo, $pesquisa, $root_path);
  $main = str_replace("{{listagem_usuarios}}", $list_users, $main);

  // Determina as cores dos componentes da página
  if ($tipo == 'aluno') {
    // vazio -> verde por padrão
    $cor_texto = "";
    $cor_btn = "";
  } else if ($tipo == 'funcionario') { 
    $cor_texto = "text-azul";
    $cor_btn = "azul";
  }

  $main = str_replace("{cor_texto}", $cor_texto, $main);
  $main = str_replace("{cor_btn}", $cor_btn, $main);

  return $main;
}


/**
 * Gera o formulário para adicionar usuários
 */
function gerarAddUsers($tipo, $root_path) {
  // Os tipos registrados no BD estão em letra maiúscula
  $tipoBD = strtoupper($tipo);

  $add_users = file_get_contents($root_path . "administrador/componentes/adicionar_usuario.html");
  $add_users = str_replace("{tipo}", $tipo, $add_users);
  $add_users = str_replace("{tipoBD}", $tipoBD, $add_users);

  return $add_users;
}

/**
 * Gera a listagem de usuários
 */
function gerarListUsers ($tipo, $pesquisa, $root_path) {
  // Os tipos registrados no BD estão em letra maiúscula
  $tipoBD = strtoupper($tipo);

  // Seleciona os usuários do BD 
  $usersBD = UsuarioDao::Select2($tipoBD, $pesquisa);

  // Conjunto de linhas que forma a tabela da listagem
  $users = gerarLinhas($usersBD);

  $list_users = file_get_contents($root_path . "administrador/componentes/listagem_usuarios.html");
  $list_users = str_replace("{tipo}", $tipo, $list_users);
  $list_users = str_replace("{{linhas_usuario}}", $users, $list_users);

  return $list_users;
}


/**
 * Gera o conjunto de linhas (usuários) listados na tabela
 */
function gerarLinhas ($users) {
  // Inicializa a variável
  $linhas = "";

  if ($users !== null) { // só para não retornar erro caso não existam usuários cadastrados
    // Para cada usuário, carrega o template da linha e o preenche
    foreach ($users as $user) {
      $linha = file_get_contents($GLOBALS['root_path']."administrador/componentes/linha_usuario.html");
      
      $codigo = $user->getCodigo();
      $username = $user->getUsername();
      $nome = $user->getNome();
      $email = $user->getEmail();
      $tipo = $user->getTipo();

      $linha = str_replace("{codigo}", $codigo, $linha);
      $linha = str_replace("{username}", $username, $linha);
      $linha = str_replace("{nome}", $nome, $linha); 
      $linha = str_replace("{email}", $email, $linha); 
      $linha = str_replace("{tipo}", $tipo, $linha);
      
      // Concatena a linha do usuário com o conjunto completo das linhas
      $linhas .= $linha;  
    }
  }

  return $linhas;
}
?>