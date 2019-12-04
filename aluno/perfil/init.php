<?php

include "{$root_path}valida_secao.php";
valida_secao_tipo($root_path, ['ALUNO', 'ADMINISTRADOR']);

require_once "{$root_path}classes/UsuarioDao.class.php";
require_once "{$root_path}classes/AlimentacaoDao.class.php";
require_once "{$root_path}classes/FrequenciaDao.class.php";
require_once "{$root_path}classes/Intolerancia.class.php";
require_once "{$root_path}classes/IntoleranciaUsuarioDao.class.php";
include "funcoes.php";

$usuario = new Usuario;
$usuario->setCodigo($_SESSION['codigo']);
$usuario = UsuarioDao::perfilCompleto($usuario);

/* Estados das intolerâncias */
define("PENDENTE", 1);
define("REJEITADA", 2);
define("VALIDADA", 3);
