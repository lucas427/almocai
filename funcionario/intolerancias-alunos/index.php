<?php

require 'init.php';

/* Carrega o template básico e seus valores/componentes (exceto main) */
$intol_alunos = file_get_contents($root_path.'template.html');
$intol_alunos = str_replace("{title}", "Gerenciar intolerâncias dos alunos", $intol_alunos);
$intol_alunos = str_replace("{peso_fonte}", "", $intol_alunos);
$intol_alunos = str_replace(
	"{{nav}}", file_get_contents($root_path.'componentes/nav-funcionario-trp.html'), $intol_alunos);
$intol_alunos = str_replace(
	"{{footer}}", file_get_contents($root_path.'componentes/footer.html'), $intol_alunos);
$intol_alunos = str_replace(
	"{{scripts}}", file_get_contents('scripts.js'), $intol_alunos);

/* {{main}} (conteúdo principal da página) */
$intolsUser = IntoleranciaUsuarioDao::SelectTodas();

$intolsUserHTML = "";
foreach($intolsUser as $intolUser) {
	$doc = $intolUser->getDocumento();
	$doc_path = $root_path.'arquivos/intolerancias/'.$doc;

	$aluno = UsuarioDao::SelectPorIntolerancia($intolUser->getCodigo());
	$intol = $intolUser->getIntolerancia();

	$intolUserHTML = file_get_contents("intolerancia.html");

	$checked_pendente = '';
	$checked_rejeitar = '';
	$checked_validar = '';

	$motivo_rejeicao = '';
	
	switch ($intolUser->getEstado()->getCodigo()) {
		case PENDENTE:
			$estado_cor = 'pendente';
			$checked_pendente = ' checked ';
			break;
		case REJEITADA:
			$estado_cor = 'rejeitada';
			$checked_rejeitar = ' checked ';
			break;
		case VALIDADA:
			$estado_cor = 'validada';
			$checked_validar = ' checked ';
			break;
	}

	if ($intolUser->getMotivo_rejeicao() != null) {
		$motivo_rejeicao = $intolUser->getMotivo_rejeicao();
	}
	
	$intolUserHTML = str_replace("{codigo}", $intolUser->getCodigo(), $intolUserHTML);
	$intolUserHTML = rawurldecode(str_replace("{aluno_nome}", $aluno->getNome(), $intolUserHTML));
	$intolUserHTML = str_replace("{username}", $aluno->getUsername(), $intolUserHTML);
	$intolUserHTML = rawurldecode(str_replace("{intolerancia}", $intol->getDescricao(), $intolUserHTML));
	$intolUserHTML = str_replace("{estado_cor}", $estado_cor, $intolUserHTML);

	$intolUserHTML = str_replace("{checked_pendente}", $checked_pendente, $intolUserHTML);
	$intolUserHTML = str_replace("{checked_rejeitar}", $checked_rejeitar, $intolUserHTML);
	$intolUserHTML = str_replace("{checked_validar}", $checked_validar, $intolUserHTML);

	$intolUserHTML = str_replace("{motivo_rejeicao}", $motivo_rejeicao, $intolUserHTML);


	$intolUserHTML = str_replace("{doc_path}", $doc_path, $intolUserHTML);

	$intolsUserHTML .= $intolUserHTML;
}

$main = file_get_contents("main.html");
$main = str_replace("{{intolerancias}}", $intolsUserHTML, $main);

$intol_alunos = str_replace("{{main}}", $main, $intol_alunos);

$intol_alunos = str_replace("{root_path}", $root_path, $intol_alunos);
print $intol_alunos;
