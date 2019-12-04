<?php

require 'init.php';

/* Para onde o usuário deverá ser redirecionado após a ação */
/* Valor padrão = root */
$redir = $root_path;

if (isset($_POST['acao'])) {
	$acao = $_POST['acao'];
} else if (isset($_GET['acao'])) {
	$acao = $_GET['acao'];
} else {
	$acao = "";
}


if ($acao == 'alterarEstadoIntolerancia')
{	
	$intolUs = new IntoleranciaUsuario;
	$intolUs->setCodigo($_POST['codigo']);
	$intolUs->setMotivo_rejeicao($_POST['motivo_rejeicao']);

	$intolEst = new IntoleranciaEstado;
	if ($_POST['estado'] == 'pendente') $intolEst->setCodigo(PENDENTE);
	else if ($_POST['estado'] == 'validar') $intolEst->setCodigo(VALIDADA);
	else if ($_POST['estado'] == 'rejeitar') $intolEst->setCodigo(REJEITADA);

	$intolUs->setEstado($intolEst);

	IntoleranciaUsuarioDao::UpdateEstado($intolUs);

	$redir = $root_path.'funcionario/intolerancias-alunos/';
}

header('location:'.$redir);