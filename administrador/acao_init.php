<?php

$root_path = "../";

// Valida a seção do administrador
include "{$root_path}valida_secao.php";
valida_secao_tipo($root_path, "ADMINISTRADOR");

// Inclui objeto de acesso a dados da tabela Usuario
require_once "{$root_path}classes/UsuarioDao.class.php";

// Carrega a ação a ser realizada
if (isset($_POST['acao'])) $acao = $_POST['acao'];
else if (isset($_GET['acao'])) $acao = $_GET['acao'];
else $acao = '';