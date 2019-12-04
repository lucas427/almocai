<?php

/**
 * Redireciona o usuário que acabou de entrar no sistema para sua interface correspondente. Ou para a página de login, caso não haja seção.
 */

require_once 'classes/UsuarioDao.class.php';

if (isset($_COOKIE['almifctkn'])) {
	$usuario = UsuarioDao::SelectPorToken($_COOKIE['almifctkn']);
	if ($usuario) {
		session_start();

		$_SESSION['codigo'] = $usuario->getCodigo();
		$_SESSION['username'] = $usuario->getUsername();
		$_SESSION['nome'] = $usuario->getNome();
		$_SESSION['tipo'] = $usuario->getTipo();
	}
}

include('valida_secao.php');
valida_secao("");

if ($_SESSION['tipo'] == 'ADMINISTRADOR') {
	header("location:administrador/");

} else if ($_SESSION['tipo'] == 'FUNCIONARIO') {
	header("location:funcionario/");

} else if ($_SESSION['tipo'] == 'ALUNO') {
	header("location:aluno/");

}
?>


<script>
	if ('serviceWorker' in navigator) {
		window.addEventListener('load', function() {
			navigator.serviceWorker.register('service-worker.js', {scope: '/'})
				.then(reg => {
					console.log('Service worker registered! 😎', reg);
				})
				.catch(err => {
					console.log('😥 Service worker registration failed: ', err);
				});
		});
	}
</script>