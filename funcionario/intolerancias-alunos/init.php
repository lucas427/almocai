<?php

$root_path = "../../";

include $root_path.'valida_secao.php';
valida_secao_tipo($root_path, ['FUNCIONARIO', 'ADMINISTRADOR']);

require_once $root_path.'classes/StatementBuilder.class.php';
require_once $root_path.'classes/Conexao.class.php';
require_once $root_path.'classes/AbsCodigo.class.php';
require_once $root_path.'classes/AbsCodigoDescricao.class.php';
require_once $root_path.'classes/Usuario.class.php';
require_once $root_path.'classes/UsuarioDao.class.php';
require_once $root_path.'classes/Intolerancia.class.php';
require_once $root_path.'classes/IntoleranciaDao.class.php';
require_once $root_path.'classes/IntoleranciaEstado.class.php';
require_once $root_path.'classes/IntoleranciaEstadoDao.class.php';
require_once $root_path.'classes/IntoleranciaUsuario.class.php';
require_once $root_path.'classes/IntoleranciaUsuarioDao.class.php';

/* Estados possíveis das intolerâncias dos usuários */
define("PENDENTE", 1);
define("REJEITADA", 2);
define("VALIDADA", 3);