<?php
$root_path = "../../";
include($root_path . "valida_secao.php");
valida_secao_tipo($root_path, 'FUNCIONARIO');

require_once($root_path . "classes/Alimento.class.php");
require_once($root_path . "classes/AlimentoDao.class.php");

if (isset($_POST['acao'])) $acao = $_POST['acao'];
else if (isset($_GET['acao'])) $acao = $_GET['acao'];
else $acao = '';

if ($acao == 'AddAlimento') {

	$alimento = new Alimento;
	$alimento->setDescricao(ucfirst(htmlspecialchars($_POST['alimento'])));
	$alimento->setTipo($_POST['tipo']);

	$dia_cod = $_POST['dia_cod'];

	AlimentoDao::Inserir($alimento, $dia_cod);

	$semana_cod = $_POST['semana_cod'];

	header("location:index.php?cod={$semana_cod}#".$dia_cod);

} else if ($acao == 'AddAlimentoSemana') {

	$alimento = new Alimento;
	$alimento->setDescricao(ucfirst(htmlspecialchars($_POST['nome'])));
	$alimento->setTipo($_POST['tipo']);

	$semana_cod = $_POST['semana_cod'];

	AlimentoDao::InserirEmSemana($alimento, $semana_cod);

	header("location:index.php?cod={$semana_cod}#addSemana");

} else if ($acao == 'DeletarAlimento') {

	$alimento = new Alimento;
	$alimento->setCodigo($_GET['cod']);

	AlimentoDao::Deletar($alimento);

	$semana_cod = $_GET['semana_cod'];

	header("location:index.php?cod={$semana_cod}");
}
